<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Database;


use FOF30\Database\Installer;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

/**
 * @covers  FOF30\Database\Installer::<protected>
 * @covers  FOF30\Database\Installer::<private>
 */
class InstallerTest extends FOFTestCase
{
	public function setUp()
	{
		parent::setUp();

		// Make sure the #__foobar_example table does not exist before running the tests
		$db = static::$container->db;
		$db->dropTable('#__foobar_example', true);
	}

	public function tearDown()
	{
		// Make sure the #__foobar_example table does not exist after running the tests
		$db = static::$container->db;
		$db->dropTable('#__foobar_example', true);

		parent::tearDown();
	}

	/**
	 * @covers  FOF30\Database\Installer::__construct
	 * @covers  FOF30\Database\Installer::updateSchema
	 * @covers  FOF30\Database\Installer::findSchemaXml
	 * @covers  FOF30\Database\Installer::openAndVerify
	 * @covers  FOF30\Database\Installer::conditionMet
	 *
	 * @throws \Exception
	 */
	public function testCreateNewTable()
	{
		$db = static::$container->db;
		$tables = $db->setQuery('SHOW TABLES')->loadColumn();
		$prefix = $db->getPrefix();
		$this->assertFalse(in_array($prefix . 'foobar_example', $tables), 'The table must not exist before testing starts');

		$installer = new Installer($db, __DIR__ . '/../_data/installer/pick_right');
        ReflectionHelper::setValue($installer, 'allTables', array());

		$installer->updateSchema();

		$tables = $db->setQuery('SHOW TABLES')->loadColumn();
		$prefix = $db->getPrefix();
		$this->assertTrue(in_array($prefix . 'foobar_example', $tables), 'The table must exist after running updateSchema');
	}

	/**
	 * @covers  FOF30\Database\Installer::__construct
	 * @covers  FOF30\Database\Installer::updateSchema
	 * @covers  FOF30\Database\Installer::findSchemaXml
	 * @covers  FOF30\Database\Installer::openAndVerify
	 * @covers  FOF30\Database\Installer::conditionMet
	 *
	 * @throws \Exception
	 */
	public function testInsertDataUsingEqualsChecks()
	{
		$db = static::$container->db;

        // Be sure the table exists
        $this->createTable();

		$installer = new Installer($db, __DIR__ . '/../_data/installer');
		$installer->setForcedFile(__DIR__ . '/../_data/installer/test_equals.xml');
		$installer->updateSchema();

		$query = $db->getQuery(true)->select('description')->from('#__foobar_example')->where('example_id = 1');
		$actual = $db->setQuery($query)->loadResult();
		$this->assertEquals('Example one', $actual, 'Equals with select');

		$query = $db->getQuery(true)->select('description')->from('#__foobar_example')->where('example_id = 2');
		$actual = $db->setQuery($query)->loadResult();
		$this->assertEquals('Example two', $actual, 'Test not operator');

		$query = $db->getQuery(true)->select('description')->from('#__foobar_example')->where('example_id = 3');
		$actual = $db->setQuery($query)->loadResult();
		$this->assertEquals('Example three', $actual, 'Test type=true');

		$query = $db->getQuery(true)->select('description')->from('#__foobar_example')->where('example_id = 4');
		$actual = $db->setQuery($query)->loadResult();
		$this->assertNotEquals('Example four', $actual, 'Test type=false');

		$query = $db->getQuery(true)->select('description')->from('#__foobar_example')->where('example_id = 5');
		$actual = $db->setQuery($query)->loadResult();
		$this->assertEquals('Example 5', $actual, 'Test operator OR positive');

		$query = $db->getQuery(true)->select('description')->from('#__foobar_example')->where('example_id = 6');
		$actual = $db->setQuery($query)->loadResult();
		$this->assertNotEquals('Example 6', $actual, 'Test operator OR negative');

		$query = $db->getQuery(true)->select('description')->from('#__foobar_example')->where('example_id = 7');
		$actual = $db->setQuery($query)->loadResult();
		$this->assertEquals('Example 7', $actual, 'Test operator NOR positive');

		$query = $db->getQuery(true)->select('description')->from('#__foobar_example')->where('example_id = 8');
		$actual = $db->setQuery($query)->loadResult();
		$this->assertNotEquals('Example 8', $actual, 'Test operator NOR negative');

		$query = $db->getQuery(true)->select('description')->from('#__foobar_example')->where('example_id = 9');
		$actual = $db->setQuery($query)->loadResult();
		$this->assertEquals('Example 9', $actual, 'Test operator XOR positive');

		$query = $db->getQuery(true)->select('description')->from('#__foobar_example')->where('example_id = 10');
		$actual = $db->setQuery($query)->loadResult();
		$this->assertNotEquals('Example 10', $actual, 'Test operator XOR negative');

		$query = $db->getQuery(true)->select('description')->from('#__foobar_example')->where('example_id = 11');
		$actual = $db->setQuery($query)->loadResult();
		$this->assertEquals('Example 11', $actual, 'Test operator MAYBE positive');

		$query = $db->getQuery(true)->select('description')->from('#__foobar_example')->where('example_id = 12');
		$actual = $db->setQuery($query)->loadResult();
		$this->assertNotEquals('Example 12', $actual, 'Test operator MAYBE negative');
	}

	/**
	 * @covers  FOF30\Database\Installer::__construct
	 * @covers  FOF30\Database\Installer::updateSchema
	 * @covers  FOF30\Database\Installer::findSchemaXml
	 * @covers  FOF30\Database\Installer::openAndVerify
	 * @covers  FOF30\Database\Installer::conditionMet
	 *
	 * @throws \Exception
	 */
	public function testModifyColumnType()
	{
		$db = static::$container->db;

        $this->createTable();

		$installer = new Installer($db, __DIR__ . '/../_data/installer');
		$installer->setForcedFile(__DIR__ . '/../_data/installer/test_type.xml');
		$installer->updateSchema();

		$coltypes = $db->getTableColumns('#__foobar_example', true);
		$this->assertArrayHasKey('text', $coltypes);
		$this->assertEquals('MEDIUMTEXT', strtoupper($coltypes['text']));
	}

	/**
	 * @covers  FOF30\Database\Installer::__construct
	 * @covers  FOF30\Database\Installer::updateSchema
	 * @covers  FOF30\Database\Installer::findSchemaXml
	 * @covers  FOF30\Database\Installer::openAndVerify
	 * @covers  FOF30\Database\Installer::conditionMet
	 *
	 * @throws \Exception
	 */
	public function testFailingSql()
	{
		$db = static::$container->db;

		$this->setExpectedException('RuntimeException');
		$installer = new Installer($db, __DIR__ . '/../_data/installer');
		$installer->setForcedFile(__DIR__ . '/../_data/installer/test_fail.xml');
		$installer->updateSchema();
	}

	/**
	 * @covers  FOF30\Database\Installer::__construct
	 * @covers  FOF30\Database\Installer::updateSchema
	 * @covers  FOF30\Database\Installer::findSchemaXml
	 * @covers  FOF30\Database\Installer::openAndVerify
	 * @covers  FOF30\Database\Installer::conditionMet
	 *
	 * @throws \Exception
	 */
	public function testCanFail()
	{
		$db = static::$container->db;

		$installer = new Installer($db, __DIR__ . '/../_data/installer');
		$installer->setForcedFile(__DIR__ . '/../_data/installer/test_canfail.xml');
		$installer->updateSchema();

		// Required for PHPUnit to mark the test as complete
		$this->assertTrue(true);
	}

	/**
	 * @covers  FOF30\Database\Installer::removeSchema
	 */
	public function testRemoveSchema()
	{
		$db = static::$container->db;

        $this->createTable();

		$tables = $db->setQuery('SHOW TABLES')->loadColumn();
		$prefix = $db->getPrefix();
		$this->assertTrue(in_array($prefix . 'foobar_example', $tables), 'The table must exist before testing starts');

		$installer = new Installer($db, __DIR__ . '/../_data/installer/pick_right');
		$installer->removeSchema();

		$tables = $db->setQuery('SHOW TABLES')->loadColumn();
		$prefix = $db->getPrefix();
		$this->assertFalse(in_array($prefix . 'foobar_example', $tables), 'The table must not exist after running removeSchema');
	}

    private function createTable()
    {
        $db = static::$container->db;

        $create = "CREATE TABLE IF NOT EXISTS `#__foobar_example` (
        `example_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `description` varchar(255) NOT NULL,
        `text` longtext,
    PRIMARY KEY (`example_id`)
    ) DEFAULT CHARACTER SET utf8;";

        $db->setQuery($create)->execute();
    }
}