<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Timer;

use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\Timer\FakeTimer;
use FOF30\Timer\Timer;

require_once __DIR__ . '/../Helpers/Timer/FakeTimerImporter.php';

/**
 * @covers  FOF30\Timer\Timer::<protected>
 * @covers  FOF30\Timer\Timer::<private>
 */
class TimerTest extends FOFTestCase
{
	public static function setUpBeforeClass()
	{
		global $fofTest_FakeTimer_Active;
		$fofTest_FakeTimer_Active = true;

		parent::setUpBeforeClass();
	}

	public static function tearDownAfterClass()
	{
		global $fofTest_FakeTimer_Active;
		$fofTest_FakeTimer_Active = false;

		parent::tearDownAfterClass();
	}

	/**
	 * @covers  FOF30\Timer\Timer::__construct
	 */
	public function testConstructor()
	{
		FakeTimer::$microtime = 123456;

		$timer = new Timer(8, 33);

		$this->assertInstanceOf('FOF30\\Timer\\Timer', $timer, 'Timer must be an instance of FOF\'s Timer class');
		$this->assertEquals(123456, ReflectionHelper::getValue($timer, 'start_time'), 'The start time must be fetched from microtime');
		$this->assertEquals(2.64, ReflectionHelper::getValue($timer, 'max_exec_time'), 'The max exec time must use the provided max exec time and bias', 0.001);
	}

	/**
	 * @covers  FOF30\Timer\Timer::__wakeup
	 */
	public function testWakeup()
	{
		FakeTimer::$microtime = 123456;
		$timer = new Timer(8, 33);
		$serialisedTimer = serialize($timer);
		unset($timer);
		FakeTimer::$microtime = 876543.21;
		$timer = unserialize($serialisedTimer);

		$this->assertEquals(876543.21, ReflectionHelper::getValue($timer, 'start_time'), 'The start time must be fetched from scratch after waking up', 0.0000000001);
	}

	/**
	 * @covers  FOF30\Timer\Timer::resetTime
	 */
	public function testResetTime()
	{
		FakeTimer::$microtime = 123456;
		$timer = new Timer(8, 33);
		FakeTimer::$microtime = 876543.21;
		$timer->resetTime();

		$this->assertEquals(876543.21, ReflectionHelper::getValue($timer, 'start_time'), 'Resetting the timer must read a new value from microtime', 0.0000000001);

	}

	/**
	 * @covers  FOF30\Timer\Timer::getRunningTime
	 */
	public function testGetRunningTime()
	{
		FakeTimer::$microtime = 123456;
		$timer = new Timer(8, 33);

		FakeTimer::$microtime = 123456.64;
		$runningTime = $timer->getRunningTime();

		$this->assertEquals(0.64, $runningTime, 'Running time must depend on current microtime', 0.0000000001);

		FakeTimer::$microtime = 123458.64;
		$runningTime = $timer->getRunningTime();

		$this->assertEquals(2.64, $runningTime, 'Running time must depend on current microtime (2)', 0.0000000001);


		FakeTimer::$microtime = 123459.64;
		$runningTime = $timer->getRunningTime();

		$this->assertEquals(3.64, $runningTime, 'Running time must depend on current microtime (3)', 0.0000000001);
	}

	/**
	 * @covers  FOF30\Timer\Timer::getTimeLeft
	 */
	public function testGetTimeLeft()
	{
		FakeTimer::$microtime = 123456;
		$timer = new Timer(8, 33);

		FakeTimer::$microtime = 123456.64;
		$timeLeft = $timer->getTimeLeft();

		$this->assertEquals(2.0, $timeLeft, 'Time left must depend on current microtime', 0.0000000001);

		FakeTimer::$microtime = 123458.64;
		$timeLeft = $timer->getTimeLeft();

		$this->assertEquals(0, $timeLeft, 'Time left must depend on current microtime (2)', 0.0000000001);


		FakeTimer::$microtime = 123459.64;
		$timeLeft = $timer->getTimeLeft();

		$this->assertEquals(-1.0, $timeLeft, 'Time left can be negative when we have run out of time', 0.0000000001);
	}
}