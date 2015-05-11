<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Helpers\Application;

use FOF30\Tests\Helpers\FOFTestCase;

class MockLanguage
{
	public static $loadedLanguages = array();

	/**
	 * Creates and instance of the mock JLanguage object.
	 *
	 * @param   FOFTestCase $test A test object.
	 *
	 * @return  \PHPUnit_Framework_MockObject_MockObject
	 *
	 * @since   11.3
	 */
	public static function create($test)
	{
		// Collect all the relevant methods in JDatabase.
		$methods = static::getMethods();

		// Create the mock.
		$mockObject = $test->getMock(
			'\JLanguage',
			$methods,
			// Constructor arguments.
			array(),
			// Mock class name.
			'',
			// Call original constructor.
			false
		);

		// Mock selected methods.
		$test->assignMockReturns(
			$mockObject, array(
				'getInstance' => $mockObject,
				'getTag'      => 'en-GB',
				// An additional 'test' method for confirming this object is successfully mocked.
				'test'        => 'ok',
			)
		);

		$test->assignMockCallbacks(
			$mockObject,
			array(
				'_' => array(get_called_class(), 'mock_'),
				'load' => array(get_called_class(), 'mockload'),
			)
		);

		return $mockObject;
	}

	public static function getMethods()
	{
		return array(
			'_',
			'getInstance',
			'getTag',
			'test',
			'load'
		);
	}

	/**
	 * Callback for the mock JLanguage::_ method.
	 *
	 * @param   string  $string               The string to translate
	 * @param   boolean $jsSafe               Make the result javascript safe
	 * @param   boolean $interpretBackSlashes Interpret \t and \n
	 *
	 * @return string
	 *
	 * @since  11.3
	 */
	public function mock_($string, $jsSafe = false, $interpretBackSlashes = true)
	{
		return $string;
	}

	public function mockload($extension = 'joomla', $basePath = JPATH_BASE, $lang = null, $reload = false, $default = true)
	{
		static::$loadedLanguages[] = array($extension, $basePath, $lang, $reload, $default);
	}
}