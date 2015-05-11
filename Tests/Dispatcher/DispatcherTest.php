<?php

namespace FOF30\Tests\Dispatcher;

use FOF30\Dispatcher\Dispatcher;
use FOF30\Input\Input;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;
use Mockery\CountValidator\Exception;

require_once 'DispatcherDataprovider.php';

/**
 * @covers      FOF30\Dispatcher\Dispatcher::<protected>
 * @covers      FOF30\Dispatcher\Dispatcher::<private>
 * @package     FOF30\Tests\Dispatcher
 */
class DispatcherTest extends FOFTestCase
{
    /**
     * @covers          FOF30\Dispatcher\Dispatcher::__construct
     * @dataProvider    DispatcherDataprovider::getTest__construct
     */
    public function test__construct($test, $check)
    {
        $msg = 'Dispatcher::__construct %s - Case: '.$check['case'];

        $container = new TestContainer(array(
            'input' => new Input($test['mock']['input'])
        ));

        $config = array();

        if($test['mock']['defaultView'])
        {
            $config['defaultView'] = $test['mock']['defaultView'];
        }

        $dispatcher = new Dispatcher($container, $config);

        $defView = ReflectionHelper::getValue($dispatcher, 'defaultView');
        $view    = ReflectionHelper::getValue($dispatcher, 'view');
        $layout  = ReflectionHelper::getValue($dispatcher, 'layout');
        $containerView = $container->input->get('view', null);

        $this->assertEquals($check['defaultView'], $defView, sprintf($msg, 'Failed to set the default view'));
        $this->assertEquals($check['view'], $view, sprintf($msg, 'Failed to set the view'));
        $this->assertEquals($check['layout'], $layout, sprintf($msg, 'Failed to set the layout'));
        $this->assertEquals($check['containerView'], $containerView, sprintf($msg, 'Failed to correctly update the container input'));
    }

    /**
     * @covers          FOF30\Dispatcher\Dispatcher::__get
     * @dataProvider    DispatcherDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $msg = 'Dispatcher::__get %s - Case: '.$check['case'];

        $input = new Input();

        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input'         => $input
        ));

        $controller = new Dispatcher($container);

        $property = $test['method'];

        // Suppress the error, so I can check the code executed AFTER the warning
        $result = @$controller->$property;

        if($check['result'])
        {
            $this->assertSame($input, $result, sprintf($msg, 'Returned the wrong result'));
        }
        else
        {
            $this->assertNull($result, sprintf($msg, 'Returned the wrong result'));
        }
    }

    /**
     * @covers          FOF30\Dispatcher\Dispatcher::dispatch
     * @dataProvider    DispatcherDataprovider::getTestDispatch
     */
    public function testDispatch($test, $check)
    {
        $msg = 'Dispatcher::dispatch %s - Case: '.$check['case'];
        $events = array(
            'before' => 0,
            'after'  => 0,
            'beforeCli' => 0,
            'afterCli'  => 0
        );

        $container = new TestContainer(array(
            'input' => new Input($test['mock']['input']),
            'factory' => new ClosureHelper(array(
                'controller' => function() use ($test){
                    return new ClosureHelper(array(
                        'execute' => function() use ($test){
                            return $test['mock']['execute'];
                        },
                        'redirect' => function(){}
                    ));
                }
            ))
        ));

        if($check['exception'])
        {
            $this->setExpectedException('FOF30\Dispatcher\Exception\AccessForbidden');
        }

        // Let's mock the after/before methods, so I can double check them
        $methods = array(
            'onBeforeDispatch' => function() use ($test, &$events){
                $events['before']++;
                if($test['mock']['before'] === 'throw'){
                    throw new \Exception();
                }
            },
            'onAfterDispatch' => function() use ($test, &$events){
                $events['after']++;

                if($test['mock']['after'] === 'throw'){
                    throw new \Exception();
                }
            },
            'onBeforeDispatchCLI' => function() use ($test, &$events){
                $events['beforeCli']++;
                if($test['mock']['before'] === 'throw'){
                    throw new \Exception();
                }
            },
            'onAfterDispatchCLI' => function() use ($test, &$events){
                $events['afterCli']++;

                if($test['mock']['after'] === 'throw'){
                    throw new \Exception();
                }
            },
        );

        $platform = $container->platform;
        $platform::$isCli = $test['mock']['isCli'];

        $dispatcher = $this->getMock('FOF30\Tests\Stubs\Dispatcher\DispatcherStub',
            array('transparentAuthenticationLogin', 'transparentAuthenticationLogout'), array($container, array(), $methods));

        $dispatcher->dispatch();

        $this->assertEquals($check['events'], $events, sprintf($msg, 'Failed to correctly invoke the before/after events'));
    }
}