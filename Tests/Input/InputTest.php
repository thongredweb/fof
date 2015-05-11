<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Input;

use FOF30\Input\Input;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

/**
 * @covers  FOF30\Input\Input::<protected>
 * @covers  FOF30\Input\Input::<private>
 */
class InputTest extends FOFTestCase
{
	/**
	 * @covers        FOF30\Input\Input::__construct
	 *
	 * @dataProvider  FOF30\Tests\Input\InputProvider::getTestConstructor
	 *
	 * @backupGlobals enabled
	 */
	public function testConstructor($source, $superGlobals, $match, $message)
	{
		// Initialise superglobals for this test
		$_GET = isset($superGlobals['get']) ? $superGlobals['get'] : array();
		$_POST = isset($superGlobals['post']) ? $superGlobals['post'] : array();
		$_FILES = isset($superGlobals['files']) ? $superGlobals['files'] : array();
		$_COOKIE = isset($superGlobals['cookie']) ? $superGlobals['cookie'] : array();
		$_ENV = isset($superGlobals['env']) ? $superGlobals['env'] : array();
		$_SERVER = isset($superGlobals['server']) ? $superGlobals['server'] : array();
		$_REQUEST = isset($superGlobals['request']) ? $superGlobals['request'] : array();

		$input = new Input($source);
		$data = ReflectionHelper::getValue($input, 'data');

		$this->assertInternalType('array', $data, $message);

		foreach ($match as $k => $v)
		{
			$this->assertArrayHasKey($k, $data, $message);
			$this->assertEquals($v, $data[$k], $message);
		}
	}

	/**
	 * @covers       FOF30\Input\Input::get
	 * @covers       FOF30\Input\Input::_cleanVar
	 *
	 * @dataProvider FOF30\Tests\Input\InputProvider::getTestGet
	 */
	public function testGet($key, $filter, $expected, $message)
	{
		$input = new Input(InputProvider::getSampleInputData());
		$actual = $input->get($key, null, $filter);

		$delta = 0.0;

		if (in_array($filter, array('float', 'double')))
		{
			$delta = 0.000001;
		}

		$this->assertEquals($expected, $actual, $message, $delta);
	}

	/**
	 * @covers       FOF30\Input\Input::getData
	 */
	public function testGetData()
	{
		$input = new Input(InputProvider::getSampleInputData());

		$data = $input->getData();

		$this->assertInternalType('array', $data, 'getData must return an array');
		$this->assertEquals(InputProvider::getSampleInputData(), $data, 'getData must return the exact input data');
	}

	/**
	 * @covers       FOF30\Input\Input::__call
	 *
	 * @dataProvider FOF30\Tests\Input\InputProvider::getTestMagicCall
	 */
	public function testMagicCall($key, $filter, $expected, $message)
	{
		$method = 'get' . ucfirst($filter);

		$input = new Input(InputProvider::getSampleInputData());
		$actual = $input->$method($key, null);

		$delta = 0.0;

		if (in_array($filter, array('float', 'double')))
		{
			$delta = 0.000001;
		}

		$this->assertEquals($expected, $actual, $message, $delta);
	}
}
