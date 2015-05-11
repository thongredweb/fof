<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Helpers\Application;


use FOF30\Tests\Helpers\FOFTestCase;

class MockApplicationCms extends MockApplicationWeb
{
	/**
	 * Gets the methods of the JApplicationCms object.
	 *
	 * @return  array
	 *
	 * @since   3.4
	 */
	public static function getMethods()
	{
		// Collect all the relevant methods in JApplicationCms (work in progress).
		$methods = array(
			'getMenu',
			'getPathway',
			'getTemplate',
			'getLanguageFilter',
			'initialiseApp',
			'isAdmin',
			'isSite',
			'getUserState',
			'getUserStateFromRequest'
		);

		return array_merge($methods, parent::getMethods());
	}

	/**
	 * Adds mock objects for some methods.
	 *
	 * @param   FOFTestCase                                 $test        A test object.
	 * @param   \PHPUnit_Framework_MockObject_MockObject  $mockObject  The mock object.
	 * @param   array                                    $options     A set of options to configure the mock.
	 *
	 * @return  \PHPUnit_Framework_MockObject_MockObject  The object with the behaviours added
	 *
	 * @since   3.4
	 */
	public static function addBehaviours($test, $mockObject, $options)
	{
		// Mock calls to JApplicationCms::getMenu();
		$mockObject->expects($test->any())->method('getMenu')->will($test->returnValue(MockMenu::create($test)));

		return parent::addBehaviours($test, $mockObject, $options);
	}

	/**
	 * Creates and instance of the mock JApplicationCms object.
	 *
	 * The test can implement the following overrides:
	 * - mockAppendBody
	 * - mockGetBody
	 * - mockPrepentBody
	 * - mockSetBody
	 *
	 * If any *Body methods are implemented in the test class, all should be implemented otherwise behaviour will be unreliable.
	 *
	 * @param   FOFTestCase  $test         A test object.
	 * @param   array     $options      A set of options to configure the mock.
	 * @param   array     $constructor  An array containing constructor arguments to inject into the mock.
	 *
	 * @return  \PHPUnit_Framework_MockObject_MockObject
	 *
	 * @since   3.2
	 */
	public static function create($test, $options = array(), $constructor = array())
	{
		// Set expected server variables.
		if (!isset($_SERVER['HTTP_HOST']))
		{
			$_SERVER['HTTP_HOST'] = 'localhost';
		}

		$methods = self::getMethods();

		if (isset($options))
			// Create the mock.
			$mockObject = $test->getMock(
				'\JApplicationCms',
				$methods,
				// Constructor arguments.
				$constructor,
				// Mock class name.
				'',
				// Call original constructor.
				true
			);

		$mockObject = self::addBehaviours($test, $mockObject, $options);

		return $mockObject;
	}
}