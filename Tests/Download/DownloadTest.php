<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Download\Adapter;

use FOF30\Download\Adapter\Curl;
use FOF30\Download\Download;
use FOF30\Tests\Helpers\Download\FakeCurl;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Timer\Timer;

require_once __DIR__ . '/../Helpers/Download/FakeCurlImporter.php';
require_once __DIR__ . '/DownloadDataprovider.php';

/**
 * @covers  FOF30\Download\Download::<protected>
 * @covers  FOF30\Download\Download::<private>
 */
class DownloadTest extends FOFTestCase
{
	public static function setUpBeforeClass()
	{
		global $fofTest_FakeCurl_Active;
		$fofTest_FakeCurl_Active = true;

		parent::setUpBeforeClass();
	}

	public static function tearDownAfterClass()
	{
		global $fofTest_FakeCurl_Active;
		$fofTest_FakeCurl_Active = false;

		parent::tearDownAfterClass();
	}

	/**
	 * @covers FOF30\Download\Download::__construct
	 * @covers FOF30\Download\Download::getFiles
	 * @covers FOF30\Download\Download::scanDirectory
	 */
	public function testConstructor()
	{
		$download = new Download(static::$container);

		$this->assertInstanceOf('\\FOF30\\Download\\Download', $download, 'Download object must be an instance of FOF30\\Download\\Download');
	}

	/**
	 * @covers FOF30\Download\Download::setAdapter
	 *
	 * @dataProvider    FOF30\Tests\Download\DownloadDataprovider::getTestSetAdapter
	 */
	public function testSetAdapter($className, $shouldChange = true)
	{
		$download = new Download(static::$container);
		$download->setAdapter('curl');
		$this->assertInstanceOf('\\FOF30\\Download\\Adapter\\Curl', ReflectionHelper::getValue($download, 'adapter'), 'Initially forced adapter should be cURL');
		$download->setAdapter($className);

		if ($shouldChange)
		{
			$this->assertNotInstanceOf('\\FOF30\\Download\\Adapter\\Curl', ReflectionHelper::getValue($download, 'adapter'), 'Forced adapter should be NOT still be cURL');
		}
		else
		{
			$this->assertInstanceOf('\\FOF30\\Download\\Adapter\\Curl', ReflectionHelper::getValue($download, 'adapter'), 'Forced adapter should still be cURL');
		}
	}

	/**
	 * @covers FOF30\Download\Download::getAdapterName
	 *
	 * @dataProvider    FOF30\Tests\Download\DownloadDataprovider::getTestGetAdapterName
	 */
	public function testGetAdapterName($className = null, $expected = null)
	{
		$download = new Download(static::$container);
		$download->setAdapter($className);

		$actual = $download->getAdapterName();

		$this->assertEquals($expected, $actual, "Download adapter $actual does not match $expected");
	}

	/**
	 * @covers  FOF30\Download\Download::getFromUrl
	 *
	 * @dataProvider    FOF30\Tests\Download\DownloadDataprovider::getTestGetFromUrl
	 *
	 * @param array $config
	 * @param array $test
	 */
	public function testGetFromUrl(array $config, array $test)
	{
		FakeCurl::setUp($config);

		$download = new Download(static::$container);
		$download->setAdapter('curl');

		$ret = $download->getFromURL($test['url']);

		if ($test['false'])
		{
			$this->assertFalse($ret);
		}
		else
		{
			$retSize = 0;

			if (is_string($ret))
			{
				$retSize = strlen($ret);
			}

			$this->assertEquals($test['retSize'], $retSize, $test['message']);
		}
	}


	/**
	 * @covers  FOF30\Download\Download::importFromUrl
	 *
	 * @dataProvider    FOF30\Tests\Download\DownloadDataprovider::getTestImportFromUrl
	 *
	 * @param array $config
	 * @param array $params
	 * @param array $test
	 */
	public function testImportFromUrl(array $config, array $params, array $test)
	{
		// Set up the FakeCurl simulator
		FakeCurl::setUp($config);

		// Get the download class
		$download = new Download(static::$container);
		$download->setAdapter('curl');

		// Initialise
		$loopAllowed = $test['loop'];

		// Get the output file name
		$platformDirs = static::$container->platform->getPlatformBaseDirs();
		$tmpDir = $platformDirs['tmp'];
		$localFilename = $tmpDir . '/test.dat';

		// Set up the download parameters
		$params['localFilename'] = $localFilename;
		#$params['maxExecTime'] = $test['loop'] ? 10000 : 0;
		$params['maxExecTime'] = 0;

		if (isset($test['localfile']))
		{
			if (empty($test['localfile']))
			{
				unset($params['localFilename']);
			}
			else
			{
				$params['localFilename'] = $test['localfile'];
			}
		}

		// Remove the local filename if it's still there
		@unlink($localFilename);

		do
		{
			$ret = $download->importFromURL($params);

			if ($loopAllowed)
			{
				$loopAllowed = !(($ret['frag'] == -1) || ($ret['error']));
			}

			$params = array_merge($params, $ret);

			if (isset($params['localFilename']) && !empty($params['localFilename']))
			{
				$localFilename = $params['localFilename'];
			}
		}
		while ($loopAllowed);

		foreach ($test['expect'] as $k => $v)
		{
			// Validate expected parameters
			$this->assertEquals($v, $ret[$k], $test['message'] . " (returned $k does not match)");
		}

		// Check the return size
		if (!$test['expect']['error'])
		{
			$fileSize = @filesize($localFilename);
			$this->assertEquals($test['retSize'], $fileSize, $test['message'] . " (size doesn't match {$test['retSize']})");
		}

		// Remove the local filename if it's still there
		@unlink($localFilename);

	}
}