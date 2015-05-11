<?php
/**
 * @package        FOF
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace FOF30\Tests\Factory;

use FOF30\Factory\MagicFactory;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\TestContainer;

require_once 'MagicFactoryDataprovider.php';

/**
 * @covers      FOF30\Factory\MagicFactory::<protected>
 * @covers      FOF30\Factory\MagicFactory::<private>
 * @package     FOF30\Tests\Factory
 */
class MagicFactoryTest extends FOFTestCase
{
    /**
     * @group           MagicFactory
     * @covers          FOF30\Factory\MagicFactory::controller
     * @dataProvider    MagicFactoryDataprovider::getTestController
     */
    public function testController($test, $check)
    {
        $msg   = 'MagicFactory::controller %s - Case: '.$check['case'];

        $factory = new MagicFactory(static::$container);

        $result = $factory->controller($test['view']);

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           MagicFactory
     * @covers          FOF30\Factory\MagicFactory::model
     * @dataProvider    MagicFactoryDataprovider::getTestModel
     */
    public function testModel($test, $check)
    {
        $msg   = 'MagicFactory::model %s - Case: '.$check['case'];

        $factory = new MagicFactory(static::$container);

        $result = $factory->model($test['view']);

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           MagicFactory
     * @covers          FOF30\Factory\MagicFactory::view
     * @dataProvider    MagicFactoryDataprovider::getTestView
     */
    public function testView($test, $check)
    {
        $msg   = 'MagicFactory::view %s - Case: '.$check['case'];

        $platform = static::$container->platform;
        $platform::$template = 'fake_test_template';
        $platform::$uriBase  = 'www.example.com';

        $factory = new MagicFactory(static::$container);

        $result = $factory->view($test['view']);

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           MagicFactory
     * @covers          FOF30\Factory\MagicFactory::dispatcher
     * @dataProvider    MagicFactoryDataprovider::getTestDispatcher
     */
    public function testDispatcher($test, $check)
    {
        $msg   = 'MagicFactory::dispatcher %s - Case: '.$check['case'];

	    $config    = array(
		    'backEndPath' => JPATH_TESTS . '/Stubs/Fakeapp/Admin'
	    );

	    if (!$test['backend'])
	    {
		    $config['componentNamespace'] = 'WhateverMan';
	    }

	    $container = new TestContainer($config);

        $platform = $container->platform;
        $platform::$isAdmin = $test['backend'];

        // Required so we force FOF to read the fof.xml file
        $dummy = $container->appConfig;

        $factory = new MagicFactory($container);

        $result = $factory->dispatcher();

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           MagicFactory
     * @covers          FOF30\Factory\MagicFactory::transparentAuthentication
     * @dataProvider    MagicFactoryDataprovider::getTestTransparentAuthentication
     */
    public function testTransparentAuthentication($test, $check)
    {
        $msg   = 'MagicFactory::transparentAuthentication %s - Case: '.$check['case'];

	    $config    = array(
		    'backEndPath' => JPATH_TESTS . '/Stubs/Fakeapp/Admin'
	    );

	    if (!$test['backend'])
	    {
		    $config['componentNamespace'] = 'WhateverMan';
	    }

	    $container = new TestContainer($config);

        $platform = $container->platform;
        $platform::$isAdmin = $test['backend'];

        // Required so we force FOF to read the fof.xml file
        $dummy = $container->appConfig;

        $factory = new MagicFactory($container);

        $result = $factory->transparentAuthentication();

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }
}