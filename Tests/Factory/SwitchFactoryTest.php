<?php
/**
 * @package        FOF
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace FOF30\Tests\Factory;

use FOF30\Factory\SwitchFactory;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;

require_once 'SwitchFactoryDataprovider.php';

/**
 * @covers      FOF30\Factory\SwitchFactory::<protected>
 * @covers      FOF30\Factory\SwitchFactory::<private>
 * @package     FOF30\Tests\Factory
 */
class SwitchFactoryTest extends FOFTestCase
{
    /**
     * @group           SwitchFactory
     * @covers          FOF30\Factory\SwitchFactory::__construct
     */
    public function test__construct()
    {
        $factory = new SwitchFactory(static::$container);

        $this->assertTrue(ReflectionHelper::getValue($factory, 'formLookupInOtherSide'));
    }

    /**
     * @group           SwitchFactory
     * @covers          FOF30\Factory\SwitchFactory::controller
     * @dataProvider    SwitchFactoryDataprovider::getTestController
     */
    public function testController($test, $check)
    {
        $msg   = 'SwitchFactory::controller %s - Case: '.$check['case'];

        $platform = static::$container->platform;
        $platform::$isAdmin = $test['backend'];

        $factory = new SwitchFactory(static::$container);

        $result = $factory->controller($test['view']);

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           SwitchFactory
     * @covers          FOF30\Factory\SwitchFactory::model
     * @dataProvider    SwitchFactoryDataprovider::getTestModel
     */
    public function testModel($test, $check)
    {
        $msg   = 'SwitchFactory::model %s - Case: '.$check['case'];

        $platform = static::$container->platform;
        $platform::$isAdmin = $test['backend'];

        $factory = new SwitchFactory(static::$container);

        $result = $factory->model($test['view']);

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           SwitchFactory
     * @covers          FOF30\Factory\SwitchFactory::view
     * @dataProvider    SwitchFactoryDataprovider::getTestView
     */
    public function testView($test, $check)
    {
        $msg   = 'SwitchFactory::view %s - Case: '.$check['case'];
        $platform = static::$container->platform;
        $platform::$template = 'fake_test_template';
        $platform::$uriBase  = 'www.example.com';
        $platform::$isAdmin  = $test['backend'];

        $factory = new SwitchFactory(static::$container);

        $result = $factory->view($test['view']);

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           SwitchFactory
     * @covers          FOF30\Factory\SwitchFactory::dispatcher
     * @dataProvider    SwitchFactoryDataprovider::getTestDispatcher
     */
    public function testDispatcher($test, $check)
    {
        $msg   = 'SwitchFactory::dispatcher %s - Case: '.$check['case'];

        $container = new TestContainer(array(
            'componentName' => $test['component']
        ));

        $platform = $container->platform;
        $platform::$isAdmin  = $test['backend'];

        $factory = new SwitchFactory($container);

        $result = $factory->dispatcher();

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           SwitchFactory
     * @covers          FOF30\Factory\SwitchFactory::toolbar
     * @dataProvider    SwitchFactoryDataprovider::getTestToolbar
     */
    public function testToolbar($test, $check)
    {
        $msg   = 'SwitchFactory::toolbar %s - Case: '.$check['case'];

        $container = new TestContainer(array(
            'componentName' => $test['component']
        ));

        $platform = $container->platform;
        $platform::$isAdmin  = $test['backend'];

        $factory = new SwitchFactory($container);

        $result = $factory->toolbar();

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           SwitchFactory
     * @covers          FOF30\Factory\SwitchFactory::transparentAuthentication
     * @dataProvider    SwitchFactoryDataprovider::getTestTransparentAuthentication
     */
    public function testTransparentAuthentication($test, $check)
    {
        $msg   = 'SwitchFactory::transparentAuthentication %s - Case: '.$check['case'];

        $container = new TestContainer(array(
            'componentName' => $test['component']
        ));

        $platform = $container->platform;
        $platform::$isAdmin  = $test['backend'];

        $factory = new SwitchFactory($container);

        $result = $factory->transparentAuthentication();

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }
}