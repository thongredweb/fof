<?php
/**
 * @package        FOF
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace FOF30\Tests\Factory\Magic;

use FOF30\Factory\Magic\DispatcherFactory;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;

/**
 * @covers      FOF30\Factory\Magic\DispatcherFactory::<protected>
 * @covers      FOF30\Factory\Magic\DispatcherFactory::<private>
 * @package     FOF30\Tests\Factory
 */
class DispatcherFactoryTest extends FOFTestCase
{
    /**
     * @covers          FOF30\Factory\Magic\DispatcherFactory::make
     * @dataProvider    getTestMake
     */
    public function testMake($test, $check)
    {
        $msg = 'DispatcherFactory::make %s - Case: '.$check['case'];

        $config['componentName'] = $test['component'];

        if($test['backend_path'])
        {
            $config['backEndPath'] = $test['backend_path'];
        }

        $container = new TestContainer($config);

        // Required so we force FOF to read the fof.xml file
        $dummy = $container->appConfig;

        $factory = new DispatcherFactory($container);

        $result = $factory->make(array());

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }

    public function getTestMake()
    {
        $data[] = array(
            array(
                'component' => 'com_fakeapp',
                'backend_path' => JPATH_TESTS.'/Stubs/Fakeapp/Admin'
            ),
            array(
                'case'   => 'DefaultDispatcher exists',
                'result' => 'Fakeapp\Site\Dispatcher\DefaultDispatcher'
            )
        );

        $data[] = array(
            array(
                'component' => 'com_dummyapp',
                'backend_path' => JPATH_TESTS.'/Stubs/Dummyapp/Admin'
            ),
            array(
                'case'   => 'DefaultDispatcher does not exist',
                'result' => 'FOF30\\Dispatcher\\Dispatcher'
            )
        );

        return $data;
    }
}