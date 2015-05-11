<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Helpers\Application;

use FOF30\Tests\Helpers\FOFTestCase;

class MockDocument
{
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
		$methods = array(
			'parse',
			'render',
			'test',
		);

		// Create the mock.
		$mockObject = $test->getMock(
			'\JDocument',
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
				'parse' => $mockObject,
				// An additional 'test' method for confirming this object is successfully mocked.
				'test'  => 'ok'
			)
		);

		return $mockObject;
	}
}