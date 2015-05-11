<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Event;


use FOF30\Container\Container;
use FOF30\Event\Dispatcher;
use FOF30\Tests\Helpers\ApplicationTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Stubs\Event\FirstObserver;
use FOF30\Tests\Stubs\Event\SecondObserver;

/**
 * Class DispatcherTest
 *
 * @package FOF30\Tests\Event
 *
 * @coversDefaultClass FOF30\Event\Dispatcher
 */
class DispatcherTest extends ApplicationTestCase
{
	/** @var  Dispatcher */
	protected $object;

	/**
	 * @covers FOF30\Event\Dispatcher::__construct
	 */
	public function testConstructor()
	{
		$container = new Container(array(
			'componentName' => 'com_eastwood'
		));

		$myDispatcher = new Dispatcher($container);

		$this->assertInstanceOf('\\FOF30\\Event\\Dispatcher', $myDispatcher);

		$this->assertEquals(
			$container,
			ReflectionHelper::getValue($myDispatcher, 'container')
		);

		$this->assertNotEquals(
			self::$container,
			ReflectionHelper::getValue($myDispatcher, 'container')
		);
	}

	/**
	 * @covers FOF30\Event\Dispatcher::getContainer
	 */
	public function testGetContainer()
	{
		$actual = $this->object->getContainer();
		$this->assertEquals(static::$container, $actual);
	}

	/**
	 * @covers FOF30\Event\Dispatcher::attach
	 */
	public function testAttach()
	{
		ReflectionHelper::setValue($this->object, 'observers', array());
		ReflectionHelper::setValue($this->object, 'events', array());

		// Test that an observer is auto-attached to the observable dispatcher
		$observer1 = new FirstObserver($this->object);
		$observers = ReflectionHelper::getValue($this->object, 'observers');
		$this->assertCount(1, $observers);
		$this->assertEquals($observer1, $observers[get_class($observer1)]);

		// Test that another observer is auto-attached to the observable dispatcher
		$observer2 = new SecondObserver($this->object);
		$observers = ReflectionHelper::getValue($this->object, 'observers');
		$this->assertCount(2, $observers);
		$this->assertEquals($observer2, $observers[get_class($observer2)]);

		// Test that we cannot attach a new instance of the same observer class
		$observer1new = new FirstObserver($this->object);
		$observers = ReflectionHelper::getValue($this->object, 'observers');
		$this->assertCount(2, $observers);
		$this->assertNotEquals($observer1new, $observers[get_class($observer1)]);
	}

	/**
	 * @covers FOF30\Event\Dispatcher::detach
	 */
	public function testDetach()
	{
		ReflectionHelper::setValue($this->object, 'observers', array());
		ReflectionHelper::setValue($this->object, 'events', array());

		$observer1 = new FirstObserver($this->object);
		$observer2 = new SecondObserver($this->object);

		$observers = ReflectionHelper::getValue($this->object, 'observers');
		$this->assertCount(2, $observers);

		// Detaching an observer
		$this->object->detach($observer1);
		$observers = ReflectionHelper::getValue($this->object, 'observers');
		$this->assertCount(1, $observers);

		// Detaching the same observer
		$this->object->detach($observer1);
		$observers = ReflectionHelper::getValue($this->object, 'observers');
		$this->assertCount(1, $observers);

		// Detaching another observer
		$this->object->detach($observer2);
		$observers = ReflectionHelper::getValue($this->object, 'observers');
		$this->assertCount(0, $observers);
	}

	/**
	 * @covers FOF30\Event\Dispatcher::hasObserver
	 * @covers FOF30\Event\Dispatcher::hasObserverClass
	 */
	public function testHasObserver()
	{
		ReflectionHelper::setValue($this->object, 'observers', array());
		ReflectionHelper::setValue($this->object, 'events', array());

		$observer1 = new FirstObserver($this->object);

		$otherDispatcher = new Dispatcher(static::$container);
		$observer2 = new SecondObserver($otherDispatcher);

		$actual = $this->object->hasObserver($observer1);
		$this->assertTrue($actual);

		$actual = $this->object->hasObserver($observer2);
		$this->assertFalse($actual);
	}

	/**
	 * @covers FOF30\Event\Dispatcher::trigger
	 */
	public function testTrigger()
	{
		ReflectionHelper::setValue($this->object, 'observers', array());
		ReflectionHelper::setValue($this->object, 'events', array());

		$observer1 = new FirstObserver($this->object);
		$observer2 = new SecondObserver($this->object);

		// Trigger a non-existent event
		$result = $this->object->trigger('notthere');
		$this->assertEquals(array(), $result);

		// Trigger a non-existent event with data
		$result = $this->object->trigger('notthere', array('whatever', 'nevermind'));
		$this->assertEquals(array(), $result);

		// Trigger an event with one observer responding to it
		$result = $this->object->trigger('onlySecond');
		$this->assertEquals(array('only second'), $result);

		// Trigger an event with two observers responding to it
		$result = $this->object->trigger('identifyYourself');
		$this->assertEquals(array('one', 'two'), $result);

		// Trigger an event with two observers responding to it, with parameters
		$result = $this->object->trigger('returnConditional', array('one'));
		$this->assertEquals(array(true, false), $result);

		// Trigger an event with two observers responding to it, with parameters
		$result = $this->object->trigger('returnConditional', array('two'));
		$this->assertEquals(array(false, true), $result);
	}

	/**
	 * @covers FOF30\Event\Dispatcher::chainHandle
	 */
	public function testChainHandle()
	{
		ReflectionHelper::setValue($this->object, 'observers', array());
		ReflectionHelper::setValue($this->object, 'events', array());

		$observer1 = new FirstObserver($this->object);
		$observer2 = new SecondObserver($this->object);

		// Trigger a non-existent event
		$result = $this->object->chainHandle('notthere');
		$this->assertNull($result);

		// Trigger a non-existent event with data
		$result = $this->object->chainHandle('notthere', array('whatever', 'nevermind'));
		$this->assertNull($result);

		// Trigger an event with one observer responding to it
		$result = $this->object->chainHandle('onlySecond');
		$this->assertEquals('only second', $result);

		// Trigger an event with two observers responding to it
		$result = $this->object->chainHandle('identifyYourself');
		$this->assertEquals('one', $result);

		// Trigger an event with two observers responding to it, with parameters
		$result = $this->object->chainHandle('returnConditional', array('one'));
		$this->assertEquals(true, $result);

		// Trigger an event with two observers responding to it, with parameters
		$result = $this->object->chainHandle('returnConditional', array('two'));
		$this->assertEquals(false, $result);

		// Trigger a real chain handler
		$result = $this->object->chainHandle('chain', array('one'));
		$this->assertEquals('one', $result);

		// Trigger a real chain handler
		$result = $this->object->chainHandle('chain', array('two'));
		$this->assertEquals('two', $result);
	}

	protected function setUp()
	{
		$this->object = new Dispatcher(static::$container);
	}

	protected function tearDown()
	{
		ReflectionHelper::setValue($this->object, 'instances', array());
	}
}
