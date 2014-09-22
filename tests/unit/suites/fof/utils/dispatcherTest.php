<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Utils
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once __DIR__ . '/eventInspector.php';

class F0FUtilsObservableDispatcherTest extends FtestCase
{
	/**
	 * @var F0FUtilsObservableDispatcher
	 */
	protected $object;

	protected function setUp()
	{
		$this->object = new F0FUtilsObservableDispatcher;
		TestReflection::setValue($this->object, 'instance', $this->object);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
		TestReflection::setValue($this->object, 'instance', null);
	}

	/**
     * @group   F0FUtilsObservableDispatcher
     * @covers  F0FUtilsObservableDispatcher::getInstance
	 */
	public function testGetInstance()
	{
		$mock = F0FUtilsObservableDispatcher::getInstance();

		$this->assertInstanceOf(
			'F0FUtilsObservableDispatcher',
			$mock
		);

		TestReflection::setValue($this->object, 'instance', null);

		$instance = F0FUtilsObservableDispatcher::getInstance();

		$this->assertInstanceOf(
			'F0FUtilsObservableDispatcher',
			$instance,
			'Tests that getInstance returns a F0FUtilsObservableDispatcher object.'
		);

		// Push a new instance into the class.
		TestReflection::setValue($this->object, 'instance', 'foo');

		$this->assertThat(
			F0FUtilsObservableDispatcher::getInstance(),
			$this->equalTo('foo'),
			'Tests that a subsequent call to F0FUtilsObservableDispatcher::getInstance returns the cached singleton.'
		);

		TestReflection::setValue($this->object, 'instance', $mock);
	}

	/**
     * @group   F0FUtilsObservableDispatcher
     * @covers  F0FUtilsObservableDispatcher::getState()
	 */
	public function testGetState()
	{
		$this->assertThat(
			$this->object->getState(),
			$this->equalTo(null)
		);

		TestReflection::setValue($this->object, '_state', 'test');

		$this->assertThat(
			$this->object->getState(),
			$this->equalTo('test')
		);
	}

	/**
     * @group   F0FUtilsObservableDispatcher
     * @covers  F0FUtilsObservableDispatcher::register()
	 */
	public function testRegister()
	{
		// We have an empty Dispatcher object
		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(array())
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(array())
		);

		// We register a function on the event 'onTestEvent'
		$this->object->register('onTestEvent', 'F0FUtilsObservableEventMockFunction');

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(
				array(
					array('event' => 'onTestEvent', 'handler' => 'F0FUtilsObservableEventMockFunction')
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(
				array('ontestevent' => array(0))
			)
		);

		// We register the same function on a different event 'onTestOtherEvent'
		$this->object->register('onTestOtherEvent', 'F0FUtilsObservableEventMockFunction');

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(
				array(
					array('event' => 'onTestEvent', 'handler' => 'F0FUtilsObservableEventMockFunction'),
					array('event' => 'onTestOtherEvent', 'handler' => 'F0FUtilsObservableEventMockFunction')
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(
				array(
					'ontestevent' => array(0),
					'ontestotherevent' => array(1)
				)
			)
		);

		// Now we attach a class to the dispatcher
		$this->object->register('', 'F0FUtilsObservableEventInspector');

		$observers = TestReflection::getValue($this->object, '_observers');
		$object = $observers[2];

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(
				array(
					array('event' => 'onTestEvent', 'handler' => 'F0FUtilsObservableEventMockFunction'),
					array('event' => 'onTestOtherEvent', 'handler' => 'F0FUtilsObservableEventMockFunction'),
					$object
				)
			)
		);

        // Since I am using a super-class of F0FUtilsObservableEvent which extends F0FUtilsObject, I have to exclude
        // the methods of the last one
        $ignoreMethods = array('__construct', '__toString', 'def', 'get', 'getProperties', 'getError', 'getErrors',
            'set', 'setProperties', 'setError', 'update');

        $_methods = TestReflection::getValue($this->object, '_methods');

        foreach($ignoreMethods as $ignore)
        {
            if(isset($_methods[strtolower($ignore)]))
            {
                unset($_methods[strtolower($ignore)]);
            }
        }

		$this->assertThat(
			$_methods,
			$this->equalTo(
				array(
					'ontestevent' => array(0, 2),
					'ontestotherevent' => array(1)
				)
			)
		);
	}

	/**
	 * @group   F0FUtilsObservableDispatcher
     * @covers  F0FUtilsObservableDispatcher::register()
     *
	 * @expectedException  InvalidArgumentException
	 */
	public function testRegisterException()
	{
		$this->object->register('fakeevent', 'nonExistingClass');
	}

	/**
     * @group   F0FUtilsObservableDispatcher
     * @covers  F0FUtilsObservableDispatcher::trigger()
	 */
	public function testTrigger()
	{
		$this->object->register('onTestEvent', 'F0FUtilsObservableEventMockFunction');
		$this->object->register('', 'F0FUtilsObservableEventInspector');

		// We check a non-existing event
		$this->assertThat(
			$this->object->trigger('onFakeEvent'),
			$this->equalTo(array())
		);

		// Let's check the existing event "onTestEvent" without parameters
		$this->assertThat(
			$this->object->trigger('onTestEvent'),
			$this->equalTo(
				array(
					'F0FUtilsObservableDispatcherMockFunction executed',
					''
				)
			)
		);

		// Let's check the existing event "onTestEvent" with parameters
		$this->assertThat(
			$this->object->trigger('onTestEvent', array('one', 'two')),
			$this->equalTo(
				array(
					'F0FUtilsObservableDispatcherMockFunction executed',
					'onetwo'
				)
			)
		);

		// We check a situation where the observer is broken. Joomla should handle this gracefully
		TestReflection::setValue($this->object, '_observers', array());

		$this->assertThat(
			$this->object->trigger('onTestEvent'),
			$this->equalTo(array())
		);
	}

	/**
	 * @group   F0FUtilsObservableDispatcher
	 * @covers  F0FUtilsObservableDispatcher::attach()
	 */
	public function testAttach()
	{
		// Let's test an invalid observer
		$observer = array();

		$this->object->attach($observer);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(array())
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(array())
		);

		// Let's test an uncallable observer
		$observer = array('handler' => 'fakefunction', 'event' => 'onTestEvent');

		$this->object->attach($observer);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(array())
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(array())
		);

		// Let's test a callable function observer
		$observer = array('handler' => 'F0FUtilsObservableEventMockFunction', 'event' => 'onTestEvent');
		$observers = array($observer);

		$this->object->attach($observer);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(
				array(
					'ontestevent' => array(0)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo($observers)
		);

		// Let's test that an observer is not attached twice
		$observer = array('handler' => 'F0FUtilsObservableEventMockFunction', 'event' => 'onTestEvent');
		$observers = array($observer);

		$this->object->attach($observer);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(
				array(
					'ontestevent' => array(0)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo($observers)
		);

		// Let's test an invalid object
		$observer = new stdClass;

		$this->object->attach($observer);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(
				array(
					'ontestevent' => array(0)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo($observers)
		);

		// Let's test a valid event object
		$observer = new F0FUtilsObservableEventInspector($this->object);
		$observers[] = $observer;

		$this->object->attach($observer);

        // Since I am using a super-class of F0FUtilsObservableEvent which extends F0FUtilsObject, I have to exclude
        // the methods of the last one
        $ignoreMethods = array('__construct', '__toString', 'def', 'get', 'getProperties', 'getError', 'getErrors',
            'set', 'setProperties', 'setError', 'update');

        $_methods = TestReflection::getValue($this->object, '_methods');

        foreach($ignoreMethods as $ignore)
        {
            if(isset($_methods[strtolower($ignore)]))
            {
                unset($_methods[strtolower($ignore)]);
            }
        }

		$this->assertThat(
			$_methods,
			$this->equalTo(
				array(
					'ontestevent' => array(0, 1)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo($observers)
		);

		// Let's test that an object observer is not attached twice
		$observer = new F0FUtilsObservableEventInspector($this->object);

		$this->object->attach($observer);

        // Since I am using a super-class of F0FUtilsObservableEvent which extends F0FUtilsObject, I have to exclude
        // the methods of the last one
        $ignoreMethods = array('__construct', '__toString', 'def', 'get', 'getProperties', 'getError', 'getErrors',
            'set', 'setProperties', 'setError', 'update');

        $_methods = TestReflection::getValue($this->object, '_methods');

        foreach($ignoreMethods as $ignore)
        {
            if(isset($_methods[strtolower($ignore)]))
            {
                unset($_methods[strtolower($ignore)]);
            }
        }

		$this->assertThat(
			$_methods,
			$this->equalTo(
				array(
					'ontestevent' => array(0, 1)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo($observers)
		);
	}

	/**
     * @group   F0FUtilsObservableDispatcher
     * @covers  F0FUtilsObservableDispatcher::detach()
	 */
	public function testDetach()
	{
		// Adding 3 events to detach later
		$observer1 = array('handler' => 'fakefunction', 'event' => 'onTestEvent');
		$observer2 = array('handler' => 'F0FUtilsObservableEventMockFunction', 'event' => 'onTestEvent');
		$this->object->attach($observer2);
		$observer3 = new F0FUtilsObservableEventInspector($this->object);
		$this->object->attach($observer3);

		// Test removing a non-existing observer

        // Since I am using a super-class of F0FUtilsObservableEvent which extends F0FUtilsObject, I have to exclude
        // the methods of the last one
        $ignoreMethods = array('__construct', '__toString', 'def', 'get', 'getProperties', 'getError', 'getErrors',
            'set', 'setProperties', 'setError', 'update');

        $_methods = TestReflection::getValue($this->object, '_methods');

        foreach($ignoreMethods as $ignore)
        {
            if(isset($_methods[strtolower($ignore)]))
            {
                unset($_methods[strtolower($ignore)]);
            }
        }

		$this->assertThat(
			$_methods,
			$this->equalTo(
				array(
					'ontestevent' => array(0, 1)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(
				array(
					$observer2,
					$observer3
				)
			)
		);

		$return = $this->object->detach($observer1);

		$this->assertFalse($return);

        // Since I am using a super-class of F0FUtilsObservableEvent which extends F0FUtilsObject, I have to exclude
        // the methods of the last one
        $ignoreMethods = array('__construct', '__toString', 'def', 'get', 'getProperties', 'getError', 'getErrors',
            'set', 'setProperties', 'setError', 'update');

        $_methods = TestReflection::getValue($this->object, '_methods');

        foreach($ignoreMethods as $ignore)
        {
            if(isset($_methods[strtolower($ignore)]))
            {
                unset($_methods[strtolower($ignore)]);
            }
        }

		$this->assertThat(
			$_methods,
			$this->equalTo(
				array(
					'ontestevent' => array(0, 1)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(
				array(
					$observer2,
					$observer3
				)
			)
		);

		// Test removing a functional observer
		$return = $this->object->detach($observer2);

		$this->assertTrue($return);

        // Since I am using a super-class of F0FUtilsObservableEvent which extends F0FUtilsObject, I have to exclude
        // the methods of the last one
        $ignoreMethods = array('__construct', '__toString', 'def', 'get', 'getProperties', 'getError', 'getErrors',
            'set', 'setProperties', 'setError', 'update');

        $_methods = TestReflection::getValue($this->object, '_methods');

        foreach($ignoreMethods as $ignore)
        {
            if(isset($_methods[strtolower($ignore)]))
            {
                unset($_methods[strtolower($ignore)]);
            }
        }

		$this->assertThat(
			$_methods,
			$this->equalTo(
				array(
					'ontestevent' => array(1 => 1)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(
				array(
					1 => $observer3
				)
			)
		);

		// Test removing an object observer with more than one event
		$return = $this->object->detach($observer3);

		$this->assertTrue($return);

        // Since I am using a super-class of F0FUtilsObservableEvent which extends F0FUtilsObject, I have to exclude
        // the methods of the last one
        $ignoreMethods = array('__construct', '__toString', 'def', 'get', 'getProperties', 'getError', 'getErrors',
            'set', 'setProperties', 'setError', 'update');

        $_methods = TestReflection::getValue($this->object, '_methods');

        foreach($ignoreMethods as $ignore)
        {
            if(isset($_methods[strtolower($ignore)]))
            {
                unset($_methods[strtolower($ignore)]);
            }
        }

		$this->assertThat(
			$_methods,
			$this->equalTo(
				array(
					'ontestevent' => array()
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(array())
		);
	}
}
