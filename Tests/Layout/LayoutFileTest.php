<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Layout;


use FOF30\Layout\LayoutFile;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestJoomlaPlatform;

/**
 * @covers  FOF30\Layout\LayoutFile::<protected>
 * @covers  FOF30\Layout\LayoutFile::<private>
 */
class LayoutFileTest extends FOFTestCase
{
	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();
		// todo
	}

	public static function tearDownAfterClass()
	{
		TestJoomlaPlatform::$baseDirs = null;
		TestJoomlaPlatform::$template = null;
		TestJoomlaPlatform::$templateSuffixes = null;

		parent::tearDownAfterClass();
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->saveFactoryState();

		\JFactory::$application = $this->getMockCmsApp();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		$this->restoreFactoryState();

		parent::tearDown();
	}

	/**
	 * @covers  FOF30\Layout\LayoutFile::getPath
	 *
	 * @dataProvider FOF30\Tests\Layout\LayoutFileTestProvider::getTestGetPath
	 *
	 * @param string $layoutId      The layout to load
	 * @param array  $platformSetup Platform setup (baseDirs, template, templateSuffixes)
	 * @param string $expectedPath  The expected path which should be returned
	 * @param string $message       Failure message
	 */
	public function testGetPath($layoutId, $platformSetup, $expectedPath, $message)
	{
		// Set up the platform
		$defaultPlatformSetup = array(
			'baseDirs' => null,
			'template' => null,
			'templateSuffixes' => null
		);

		if (!is_array($platformSetup))
		{
			$platformSetup = array();
		}

		$platformSetup = array_merge($defaultPlatformSetup, $platformSetup);
		$reflector = new \ReflectionClass('FOF30\\Tests\\Helpers\\TestJoomlaPlatform');

		foreach ($platformSetup as $k => $v)
		{
			$reflector->setStaticPropertyValue($k, $v);
		}

		unset($reflector);

		// Set up a fake options JRegistry object
		$fakeOptions = new \JRegistry(array(
			'option' => 'com_foobar',
			'client' => 0,
		));

		$fakeBase = realpath(__DIR__ . '/../_data/layout/base');

		// Create the layout file object
		$layoutFile = new LayoutFile($layoutId, $fakeBase, $fakeOptions);
		$layoutFile->container = static::$container;

		// Call the protected method. Dirty, but that's what we have to test without loading and displaying an actual
		// PHP file.
		$actual = ReflectionHelper::invoke($layoutFile, 'getPath');

		$this->assertEquals($expectedPath, $actual, $message);
	}
}