<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Download\Adapter;

use FOF30\Download\Adapter\Curl;
use FOF30\Tests\Helpers\Download\FakeCurl;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once __DIR__ . '/../../Helpers/Download/FakeCurlImporter.php';
require_once __DIR__ . '/CurlDataprovider.php';

/**
 * @covers  FOF30\Download\Adapter\Curl::<protected>
 * @covers  FOF30\Download\Adapter\Curl::<private>
 */
class CurlTest extends FOFTestCase
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
	 * @covers  FOF30\Download\Adapter\Curl::__construct
	 */
	public function testConstructor()
	{
		$adapter = new Curl();

		$this->assertInstanceOf('FOF30\\Download\\Adapter\\Curl', $adapter, 'Adapter must match correct object type');
		$this->assertEquals(110, ReflectionHelper::getValue($adapter, 'priority'), 'Adapter priority must match');
		$this->assertEquals(true, ReflectionHelper::getValue($adapter, 'supportsFileSize'), 'Adapter must support file size');
		$this->assertEquals(true, ReflectionHelper::getValue($adapter, 'supportsChunkDownload'), 'Adapter must support chunked download');
		$this->assertEquals('curl', ReflectionHelper::getValue($adapter, 'name'), 'Adapter must have the correct name');
		$this->assertEquals(true, ReflectionHelper::getValue($adapter, 'isSupported'), 'Adapter must be supported');
	}

	/**
	 * @covers  FOF30\Download\Adapter\Curl::downloadAndReturn
	 *
	 * @dataProvider    FOF30\Tests\Download\Adapter\CurlDataprovider::getTestDownloadAndReturn
	 *
	 * @param array $config
	 * @param array $test
	 */
	public function testDownloadAndReturn(array $config, array $test)
	{
		FakeCurl::setUp($config);
		$adapter = new Curl();

		if ($test['exception'] !== false)
		{
			$this->setExpectedException($test['exception']['name'], $test['exception']['message'], $test['exception']['code']);
		}

		$ret = $adapter->downloadAndReturn($test['url'], $test['from'], $test['to']);
		$retSize = 0;

		if (is_string($ret))
		{
			$retSize = strlen($ret);
		}

		$this->assertEquals($test['retSize'], $retSize, $test['message']);
	}

	/**
	 * @covers  FOF30\Download\Adapter\Curl::getFileSize
	 *
	 * @dataProvider    FOF30\Tests\Download\Adapter\CurlDataprovider::getTestGetFileSize
	 *
	 * @param array $config
	 * @param array $test
	 */
	public function testGetFileSize(array $config, array $test)
	{
		FakeCurl::setUp($config);
		$adapter = new Curl();

		$ret = $adapter->getFileSize($test['url']);

		$this->assertEquals($test['retSize'], $ret, $test['message']);
	}
}