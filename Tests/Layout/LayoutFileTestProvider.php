<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Layout;

class LayoutFileTestProvider
{
	public static function getTestGetPath()
	{
		$fakeBase = realpath(__DIR__ . '/../_data/layout/base');
		$templateBase = realpath(__DIR__ . '/../_data/layout/templates/system/html/layouts');
		$platformSetup = array(
			'templateSuffixes' => array('.fof'),
			'template' => 'system',
			'baseDirs' => array(
				'root'   => realpath(__DIR__ . '/../_data/layout'),
				'public' => realpath(__DIR__ . '/../_data/layout'),
				'admin'  => realpath(__DIR__ . '/../_data/layout/administrator'),
				'tmp'    => \JFactory::getConfig()->get('tmp_path'),
				'log'    => \JFactory::getConfig()->get('tmp_path')
			)
		);

		// $layoutId, $platformSetup, $expectedPath, $message
		return array(
			array('test.foo', $platformSetup, $fakeBase . '/test/foo.php', 'Getting a simple layout'),
			array('test.bar', $platformSetup, $fakeBase . '/test/bar.fof.php', 'Getting a simple layout with platform extension'),
			array('test.overridden', $platformSetup, $templateBase . '/test/overridden.php', 'Getting a simple overridden layout without platform extension'),
			array('test.baz', $platformSetup, $templateBase . '/test/baz.fof.php', 'Getting a simple overridden layout with a platform extension'),
			array('test.bat', $platformSetup, $fakeBase . '/test/bat.fof.php', 'Platform extension has priority over template override without platform extension'),
		);
	}
}