<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Event;


use FOF30\Event\Observable;
use FOF30\Tests\Helpers\ApplicationTestCase;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Stubs\Event\FirstObserver;

class ObserverTest extends FOFTestCase
{
	/** @var Observable */
	private $dispatcher;

	public static $attachArguments = null;

	public function testConstructor()
	{
		$dummy = new FirstObserver($this->dispatcher);

		$this->assertEquals($dummy, self::$attachArguments);
		$this->assertEquals($this->dispatcher, ReflectionHelper::getValue($dummy, 'subject'));
	}

	public function testGetObservableEvents()
	{
		$dummy = new FirstObserver($this->dispatcher);

		$observableEvents = $dummy->getObservableEvents();

		$this->assertEquals(array(
			'returnConditional',
			'identifyYourself',
			'chain',
		), $observableEvents);
	}

	protected function setUp()
	{
		$this->dispatcher = $this->getMockBuilder('\\FOF30\\Event\\Observable')
			->disableOriginalConstructor()
			->setMethods(array('attach', 'detach', 'trigger'))
			->getMock();

		$this->dispatcher
			->expects($this->any())
			->method('attach')
			->will($this->returnCallback(function($arg){
				ObserverTest::$attachArguments = $arg;
			}));

		$this->dispatcher
			->expects($this->any())
			->method('detach')
			->willReturnSelf();

		$this->dispatcher
			->expects($this->any())
			->method('trigger')
			->willReturn(array());
	}
}
 