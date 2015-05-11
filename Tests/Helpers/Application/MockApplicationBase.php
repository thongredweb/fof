<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Helpers\Application;

use FOF30\Tests\Helpers\FOFTestCase;

class MockApplicationBase
{
	/**
	 * Gets the methods of the JApplicationBase object.
	 *
	 * @return  array
	 *
	 * @since   3.4
	 */
	public static function getMethods()
	{
		return array(
			'close',
			'getIdentity',
			'registerEvent',
			'triggerEvent',
			'loadDispatcher',
			'loadIdentity',
		);
	}

	/**
	 * Adds mock objects for some methods.
	 *
	 * @param  FOFTestCase                              $test       A test object.
	 * @param  \PHPUnit_Framework_MockObject_MockObject $mockObject The mock object.
	 * @param  array                                    $options    A set of options to configure the mock.
	 *
	 * @return  \PHPUnit_Framework_MockObject_MockObject  The object with the behaviours added
	 *
	 * @since   3.4
	 */
	public static function addBehaviours($test, $mockObject, $options)
	{
		$test->assignMockReturns(
			$mockObject,
			array('close' => true)
		);

		return $mockObject;
	}

	/**
	 * Creates and instance of the mock JApplicationBase object.
	 *
	 * @param   FOFTestCase $test    A test object.
	 * @param   array       $options A set of options to configure the mock.
	 *
	 * @return  \PHPUnit_Framework_MockObject_MockObject
	 *
	 * @since   11.3
	 */
	public static function create($test, $options = array())
	{
		// Set expected server variables.
		if (!isset($_SERVER['HTTP_HOST']))
		{
			$_SERVER['HTTP_HOST'] = 'localhost';
		}

		// Collect all the relevant methods in JApplicationBase.
		$methods = self::getMethods();

		// Create the mock.
		$mockObject = $test->getMock(
			'\JApplicationBase',
			$methods,
			// Constructor arguments.
			array(),
			// Mock class name.
			'',
			// Call original constructor.
			true
		);

		$mockObject = self::addBehaviours($test, $mockObject, $options);

		return $mockObject;
	}
}