<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Configuration\Domain;

use FOF30\Tests\Helpers\FOFTestCase;

/**
 * @covers  FOF30\Configuration\Configuration::<protected>
 * @covers  FOF30\Configuration\Configuration::<private>
 */
class ConfigurationTest extends FOFTestCase
{
	/** @var   array  The data returned from parsing the XML file, used to test fetching data */
	protected $data = array();

	/**
	 * @return  void
	 */
	protected function setUp()
	{
		self::$container->backEndPath = realpath(__DIR__ . '/../_data/configuration');
	}

	/**
	 * @covers  FOF30\Configuration\Configuration::__construct
	 * @covers  FOF30\Configuration\Configuration::parseComponent
	 * @covers  FOF30\Configuration\Configuration::parseComponentArea
	 * @covers  FOF30\Configuration\Configuration::getDomains
	 * @covers  FOF30\Configuration\Configuration::get
	 *
	 * @return  void
	 */
	public function testConstructor()
	{
		$x = self::$container->appConfig;

		$this->assertInstanceOf('\\FOF30\\Configuration\\Configuration', $x, 'Configuration object must be of correct type');

		$actual = $x->get('models.Orders.field.enabled', null);

		$this->assertEquals('published', $actual, 'get() must return valid domain data');
	}
}