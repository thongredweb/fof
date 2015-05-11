<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Helpers;

use FOF30\Container\Container;

abstract class DatabaseTest extends \PHPUnit_Extensions_Database_TestCase
{
	/**
	 * Assigns mock callbacks to methods.
	 *
	 * @param   object  $mockObject  The mock object that the callbacks are being assigned to.
	 * @param   array   $array       An array of methods names to mock with callbacks.
	 *
	 * @return  void
	 *
	 * @note    This method assumes that the mock callback is named {mock}{method name}.
	 * @since   1.0
	 */
	public function assignMockCallbacks($mockObject, $array)
	{
		foreach ($array as $index => $method)
		{
			if (is_array($method))
			{
				$methodName = $index;
				$callback = $method;
			}
			else
			{
				$methodName = $method;
				$callback = array(get_called_class(), 'mock' . $method);
			}

			$mockObject->expects($this->any())
				->method($methodName)
				->will($this->returnCallback($callback));
		}
	}

	/**
	 * Assigns mock values to methods.
	 *
	 * @param   object  $mockObject  The mock object.
	 * @param   array   $array       An associative array of methods to mock with return values:<br />
	 *                               string (method name) => mixed (return value)
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function assignMockReturns($mockObject, $array)
	{
		foreach ($array as $method => $return)
		{
			$mockObject->expects($this->any())
				->method($method)
				->will($this->returnValue($return));
		}
	}

	/**
	 * Returns the default database connection for running the tests.
	 *
	 * @return  \PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
	 *
	 * @since   1.0
	 */
	protected function getConnection()
	{
        static $connection;

        if(!$connection)
        {
            // Let's use the config file of our guinea pig
            require_once JPATH_BASE.'/configuration.php';

            $config = new \JConfig();

            // P.A. Test database prefix is fixed with jos_ so we can setup common tables
            $options = array (
                'driver'	=> ((isset ($config)) && ($config->dbtype != 'mysqli')) ? $config->dbtype : 'mysql',
                'host' 		=> isset ($config) ? $config->host : '127.0.0.1',
                'user' 		=> isset ($config) ? $config->user : 'utuser',
                'password' 	=> isset ($config) ? $config->password : 'ut1234',
                'database' 	=> isset ($config) ? $config->db : 'joomla_ut',
                'prefix' 	=> 'jos_'
            );

            $pdo = new \PDO($options['driver'].':host='.$options['host'].';dbname='.$options['database'], $options['user'], $options['password']);
            $connection = $this->createDefaultDBConnection($pdo, $options['database']);
        }

        return $connection;
	}

    /**
     * Gets the data set to be loaded into the database during setup
     *
     * @return  \PHPUnit_Extensions_Database_DataSet_XmlDataSet
     *
     * @since   1.0
     */
    protected function getDataSet()
    {
        return $this->createXMLDataSet(__DIR__ . '/../Stubs/schema/database.xml');
    }

	/**
	 * Returns the database operation executed in test setup.
	 *
	 * @return  \PHPUnit_Extensions_Database_Operation_Composite
	 *
	 * @since   1.0
	 */
	protected function getSetUpOperation()
	{
        // At the moment we can safely TRUNCATE tables, since we're not using InnoDB tables nor foreign keys
        // However if we ever need them, we can use our InsertOperation and TruncateOperation to suppress foreign keys
        return new \PHPUnit_Extensions_Database_Operation_Composite(
            array(
                \PHPUnit_Extensions_Database_Operation_Factory::TRUNCATE(),
                \PHPUnit_Extensions_Database_Operation_Factory::INSERT()
            )
        );
	}

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

    public function setUp()
    {
        parent::setUp();

        // Since we're creating the platform only when we instantiate the test class, any modification
        // will be carried over in the other tests, so we have to manually reset the platform before
        // running any other test
        $platform = static::$container->platform;

        if(method_exists($platform, 'reset'))
        {
            $platform->reset();
        }
    }
}