<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Helpers;

use FOF30\Container\Container;

/**
 * Base class for tests requiring a container and/or an application to be set up
 *
 * @package Awf\Tests\Helpers
 */
abstract class ApplicationTestCase extends \PHPUnit_Framework_TestCase
{
	/** @var Container A container suitable for unit testing */
	public static $container = null;

	public static function setUpBeforeClass()
	{
		self::rebuildContainer();
	}

	public static function tearDownAfterClass()
	{
		static::$container = null;
	}

	public static function rebuildContainer()
	{
		static::$container = null;
		static::$container = new TestContainer(array(
			'componentName'	=> 'com_fakeapp',
		));
	}
}