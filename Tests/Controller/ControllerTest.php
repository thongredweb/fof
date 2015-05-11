<?php
/**
 * @package        FOF
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace FOF30\Tests\Controller;

use FOF30\Input\Input;
use FOF30\Tests\Helpers\ApplicationTestCase;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;
use FOF30\Tests\Stubs\Controller\ControllerStub;
use FOF30\Tests\Stubs\Model\ModelStub;
use FOF30\Tests\Stubs\View\ViewStub;

require_once 'ControllerDataprovider.php';

/**
 * @covers      FOF30\Controller\Controller::<protected>
 * @covers      FOF30\Controller\Controller::<private>
 * @package     FOF30\Tests\Controller
 */
class ControllerTest extends ApplicationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $_SERVER['HTTP_HOST'] = 'www.example.com';
    }

    protected function tearDown()
    {
        parent::tearDown();

        if(isset($_SERVER['HTTP_HOST']))
        {
            unset($_SERVER['HTTP_HOST']);
        }
    }

    /**
     * @group           Controller
     * @group           ControllerConstruct
     * @covers          FOF30\Controller\Controller::__construct
     * @dataProvider    ControllerDataprovider::getTest__construct
     */
    public function test__construct($test, $check)
    {
        $containerSetup = array(
            'componentName' => 'com_eastwood',
            'input' => new Input(
                array(
                    'layout' => $test['layout']
                )
            )
        );

        $msg       = 'Controller::__construct %s - Case: '.$check['case'];
        $container = new TestContainer($containerSetup);

        // First of all let's get the mock of the object WITHOUT calling the constructor
        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\ControllerStub', array('registerDefaultTask', 'setModelName', 'setViewName'), array(), '', false);
        $controller->expects($this->once())->method('registerDefaultTask')->with($this->equalTo($check['defaultTask']));
        $controller->expects($check['viewName'] ? $this->once() : $this->never())->method('setViewName')->with($this->equalTo($check['viewName']));
        $controller->expects($check['modelName'] ? $this->once() : $this->never())->method('setModelName')->with($this->equalTo($check['modelName']));

        // Now I can explicitly call the constructor
        $controller->__construct($container, $test['config']);

        $layout = ReflectionHelper::getValue($controller, 'layout');
        $name   = ReflectionHelper::getValue($controller, 'name');
        $autoRoute = ReflectionHelper::getValue($controller, 'autoRouting');
        $csrf      = ReflectionHelper::getValue($controller, 'csrfProtection');

        $this->assertEquals($check['layout'], $layout, sprintf($msg, 'Failed to set the layout'));
        $this->assertEquals($check['name'], $name, sprintf($msg, 'Failed to set the name'));
        $this->assertEquals($check['autoroute'], $autoRoute, sprintf($msg, 'Failed to set the autoRouting'));
        $this->assertEquals($check['csrf'], $csrf, sprintf($msg, 'Failed to set the CSRF protection'));

    }

    /**
     * @group           Controller
     * @group           ControllerConstruct
     * @covers          FOF30\Controller\Controller::__construct
     */
    public function test__constructTaskMap()
    {
        $controller = new ControllerStub(static::$container);

        $tasks = ReflectionHelper::getValue($controller, 'taskMap');

        // Remove reference to __call magic method
        unset($tasks['__call']);

        $check = array(
            'onBeforeDummy' => 'onBeforeDummy',
            'onAfterDummy'  => 'onAfterDummy',
            'display'       => 'display',
            'main'          => 'main',
            '__default'     => 'main'
        );

        $this->assertEquals($check, $tasks, 'Controller::__construct failed to create the taskMap array');
    }

    /**
     * @group           Controller
     * @group           ControllerConstruct
     * @covers          FOF30\Controller\Controller::__get
     * @dataProvider    ControllerDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $msg = 'Controller::__get %s - Case: '.$check['case'];

        $input = new Input();

        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input'         => $input
        ));

        $controller = new ControllerStub($container);

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
     * @group           Controller
     * @group           ControllerExecute
     * @covers          FOF30\Controller\Controller::execute
     * @dataProvider    ControllerDataprovider::getTestExecute
     */
    public function testExecute($test, $check)
    {
        $msg        = 'Controller::execute %s - Case: '.$check['case'];
        $before     = 0;
        $task       = 0;
        $after      = 0;

        $controller = new ControllerStub(static::$container, array(), array(
            'onBeforeDummy' => function() use (&$before, $test){
                $before++;
                return $test['mock']['before'];
            },
            'onAfterDummy' => function() use (&$after, $test){
                $after++;
                return $test['mock']['after'];
            },
            $test['task'] => function() use(&$task, $test){
                $task++;
                return $test['mock']['task'];
            }
        ));

        ReflectionHelper::setValue($controller, 'taskMap', $test['mock']['taskMap']);

        $result = $controller->execute($test['task']);

        $doTask = ReflectionHelper::getValue($controller, 'doTask');

        $this->assertEquals($check['doTask'], $doTask, sprintf($msg, 'Failed to set the $doTask property'));
        $this->assertEquals($check['before'], $before, sprintf($msg, 'Invoked the onBefore<task> method the wrong amount of times'));
        $this->assertEquals($check['task'], $task, sprintf($msg, 'Invoked the <task> method the wrong amount of times'));
        $this->assertEquals($check['after'], $after, sprintf($msg, 'Invoked the onAfter<task> method the wrong amount of times'));
        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong value'));
    }

    /**
     * @group           Controller
     * @group           ControllerExecute
     * @covers          FOF30\Controller\Controller::execute
     */
    public function testExecuteException()
    {
        $this->setExpectedException('FOF30\Controller\Exception\TaskNotFound');

        $controller = new ControllerStub(static::$container);

        ReflectionHelper::setValue($controller, 'taskMap', array());

        $controller->execute('foobar');
    }

    /**
     * @group           Controller
     * @group           ControllerDisplay
     * @covers          FOF30\Controller\Controller::display
     * @dataProvider    ControllerDataprovider::getTestDisplay
     */
    public function testDisplay($test, $check)
    {
        $msg = 'Controller::display %s - Case: '.$check['case'];

        $layoutCounter = 0;
        $layoutCheck   = null;
        $modelCounter  = 0;
        $container     = new TestContainer(array(
            'componentName' => 'com_eastwood'
        ));

        $platform = $container->platform;
        $platform::$template = 'fake_test_template';

        $viewMethods = array('setDefaultModel', 'setLayout', 'display');
        $view = $this->getMock('\\FOF30\\Tests\\Stubs\\View\\ViewStub', $viewMethods, array($container));
        $view->expects($this->any())->method('setDefaultModel')->willReturnCallback(
            function($model) use (&$modelCounter){
                $modelCounter++;
            }
        );
        $view->expects($this->any())->method('setLayout')->willReturnCallback(
            function($layout) use (&$layoutCounter, &$layoutCheck){
                $layoutCounter++;
                $layoutCheck = $layout;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\ControllerStub', array('getView', 'getModel'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($test['mock']['getModel']);
        $controller->expects($this->any())->method('getView')->willReturn($view);

        ReflectionHelper::setValue($controller, 'task'  , $test['mock']['task']);
        ReflectionHelper::setValue($controller, 'doTask', $test['mock']['doTask']);
        ReflectionHelper::setValue($controller, 'layout', $test['mock']['layout']);

        $controller->display();

        $this->assertEquals($check['modelCounter'], $modelCounter, sprintf($msg, 'Failed to set view default model the correct amount of times'));
        $this->assertEquals($check['layoutCounter'], $layoutCounter, sprintf($msg, 'Failed to set view layout the correct amount of times'));
        $this->assertEquals($check['layout'], $layoutCheck, sprintf($msg, 'Set the wrong view layout'));
        $this->assertEquals($check['task'], $view->task, sprintf($msg, 'Set the wrong view task'));
        $this->assertEquals($check['doTask'], $view->doTask, sprintf($msg, 'Set the wrong view doTask'));
    }

    /**
     * @group           Controller
     * @group           ControllerGetModel
     * @covers          FOF30\Controller\Controller::getModel
     * @dataProvider    ControllerDataprovider::getTestGetModel
     */
    public function testGetModel($test, $check)
    {
        $msg          = 'Controller::getModel %s - Case: '.$check['case'];
        $passedModel  = '';
        $passedConfig = array();

        $container  = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'factory'       => new ClosureHelper(array(
                'model' => function($self, $modelName, $config) use ($test, &$passedModel, &$passedConfig){
                    $passedModel  = $modelName;
                    $passedConfig = $config;

                    return $test['mock']['getModel'];
                }
            ))
        ));

        $controller = new ControllerStub($container, $test['constructConfig']);

        ReflectionHelper::setValue($controller, 'modelName', $test['mock']['modelName']);
        ReflectionHelper::setValue($controller, 'view', $test['mock']['view']);
        ReflectionHelper::setValue($controller, 'modelInstances', $test['mock']['instances']);

        $result = $controller->getModel($test['name'], $test['config']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
        $this->assertEquals($check['modelName'], $passedModel, sprintf($msg, 'Created the wrong model name'));
        $this->assertEquals($check['config'], $passedConfig, sprintf($msg, 'Passed the wrong config'));
    }

    /**
     * @group           Controller
     * @group           ControllerGetView
     * @covers          FOF30\Controller\Controller::getView
     * @dataProvider    ControllerDataprovider::getTestGetView
     */
    public function testGetView($test, $check)
    {
        $msg        = 'Controller::getView %s - Case: '.$check['case'];
        $arguments  = array(
            'name'   => '',
            'type'   => '',
            'config' => array()
        );

        $container  = new TestContainer(array(
            'componentName' => 'com_eastwood',
            'input' => new Input(array(
                'format' => $test['mock']['format']
            )),
            'factory'  => new ClosureHelper(array(
                'view' => function($self, $viewName, $type, $config) use ($test, &$arguments){
                    $arguments['name'] = $viewName;
                    $arguments['type'] = $type;
                    $arguments['config'] = $config;

                    return $test['mock']['getView'];
                }
            ))
        ));
        $controller = new ControllerStub($container, $test['constructConfig']);

        ReflectionHelper::setValue($controller, 'viewName', $test['mock']['viewName']);
        ReflectionHelper::setValue($controller, 'view', $test['mock']['view']);
        ReflectionHelper::setValue($controller, 'viewInstances', $test['mock']['instances']);

        $result = $controller->getView($test['name'], $test['config']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Created the wrong view'));
        $this->assertEquals($check['viewName'], $arguments['name'], sprintf($msg, 'Created the wrong view name'));
        $this->assertEquals($check['type'], $arguments['type'], sprintf($msg, 'Created the wrong view type'));
        $this->assertEquals($check['config'], $arguments['config'], sprintf($msg, 'Passed the wrong config'));
    }

    /**
     * @group           Controller
     * @group           ControllerSetViewName
     * @covers          FOF30\Controller\Controller::setViewName
     */
    public function testSetViewName()
    {
        $controller = new ControllerStub(new TestContainer(array(
            'componentName' => 'com_eastwood'
        )));
        $controller->setViewName('foobar');

        $value = ReflectionHelper::getValue($controller, 'viewName');

        $this->assertEquals('foobar', $value, 'Controller::setViewName failed to set the view name');
    }

    /**
     * @group           Controller
     * @group           ControllerSetModelName
     * @covers          FOF30\Controller\Controller::setModelName
     */
    public function testSetModelName()
    {
        $controller = new ControllerStub(new TestContainer(array(
            'componentName' => 'com_eastwood'
        )));
        $controller->setModelName('foobar');

        $value = ReflectionHelper::getValue($controller, 'modelName');

        $this->assertEquals('foobar', $value, 'Controller::setModelName failed to set the model name');
    }

    /**
     * @group           Controller
     * @group           ControllerSetModel
     * @covers          FOF30\Controller\Controller::setModel
     */
    public function testSetModel()
    {
        $model      = new ModelStub(new TestContainer(array(
            'componentName' => 'com_eastwood'
        )));
        $controller = new ControllerStub(new TestContainer(array(
            'componentName' => 'com_eastwood'
        )));
        $controller->setModel('foobar', $model);

        $models = ReflectionHelper::getValue($controller, 'modelInstances');

        $this->assertArrayHasKey('foobar', $models, 'Controller::setModel Failed to save the model');
        $this->assertSame($model, $models['foobar'], 'Controller::setModel Failed to store the same copy of the passed model');
    }

    /**
     * @group           Controller
     * @group           ControllerSetView
     * @covers          FOF30\Controller\Controller::setView
     */
    public function testSetView()
    {
        $view       = new ViewStub(new TestContainer(array(
            'componentName' => 'com_eastwood'
        )));
        $controller = new ControllerStub(new TestContainer(array(
            'componentName' => 'com_eastwood'
        )));
        $controller->setView('foobar', $view);

        $views = ReflectionHelper::getValue($controller, 'viewInstances');

        $this->assertArrayHasKey('foobar', $views, 'Controller::setView Failed to save the view');
        $this->assertSame($view, $views['foobar'], 'Controller::setView Failed to store the same copy of the passed view');
    }

    /**
     * @group           Controller
     * @group           ControllerGetTask
     * @covers          FOF30\Controller\Controller::getName
     */
    public function testGetName()
    {
        $controller = new ControllerStub(static::$container);

        ReflectionHelper::setValue($controller, 'name', null);

        $name = $controller->getName();

        $this->assertEquals('ControllerStub', $name, 'Controller::getName Failed to fetch the correct controller name');
    }

    /**
     * @group           Controller
     * @group           ControllerGetTask
     * @covers          FOF30\Controller\Controller::getName
     */
    public function testGetNameException()
    {
        $this->setExpectedException('FOF30\Controller\Exception\CannotGetName');

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\ControllerStub', array('registerTask'), array(static::$container));

        ReflectionHelper::setValue($controller, 'name', null);

        $controller->getName();
    }

    /**
     * @group           Controller
     * @group           ControllerGetTask
     * @covers          FOF30\Controller\Controller::getTask
     */
    public function testGetTask()
    {
        $controller = new ControllerStub(new TestContainer(array(
            'componentName' => 'com_eastwood'
        )));
        ReflectionHelper::setValue($controller, 'task', 'foobar');

        $task = $controller->getTask();

        $this->assertEquals('foobar', $task, 'Controller::getTask failed to return the current task');
    }

    /**
     * @group           Controller
     * @group           ControllerGetTasks
     * @covers          FOF30\Controller\Controller::getTasks
     */
    public function testGetTasks()
    {
        $controller = new ControllerStub(new TestContainer(array(
            'componentName' => 'com_eastwood'
        )));
        ReflectionHelper::setValue($controller, 'methods', array('foobar'));

        $tasks = $controller->getTasks();

        $this->assertEquals(array('foobar'), $tasks, 'Controller::getTasks failed to return the internal tasks');
    }

    /**
     * @group           Controller
     * @group           ControllerRedirect
     * @covers          FOF30\Controller\Controller::redirect
     * @dataProvider    ControllerDataprovider::getTestRedirect
     */
    public function testRedirect($test, $check)
    {
        $msg        = 'Controller::redirect %s - Case: '.$check['case'];
        $controller = new ControllerStub(new TestContainer(array(
            'componentName' => 'com_eastwood'
        )));
        $counter    = 0;
        $fakeapp    = new ClosureHelper(array(
            'redirect' => function () use(&$counter){
                $counter++;
            }
        ));

        ReflectionHelper::setValue($controller, 'redirect', $test['mock']['redirect']);

        // Let's save current app istances, I'll have to restore them later
        if(is_object(\JFactory::$application))
        {
            $oldapp = clone \JFactory::$application;
        }
        else
        {
            $oldapp = \JFactory::$application;
        }

        \JFactory::$application = $fakeapp;

        $result = $controller->redirect();

        \JFactory::$application = $oldapp;

        // If the redirection has been invoked, I have to nullify the result. In the real world I would be immediatly
        // redirected to another page.
        if($counter)
        {
            $result = null;
        }

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
        $this->assertEquals($check['redirect'], $counter, sprintf($msg, 'Failed to perform the redirection'));
    }

    /**
     * @group           Controller
     * @group           ControllerRegisterDefaultTask
     * @covers          FOF30\Controller\Controller::registerDefaultTask
     */
    public function testRegisterDefaultTask()
    {
        // In this test I just want to check the result, since I'll test the registerTask in another test
        $container  = new TestContainer(array(
            'componentName' => 'com_eastwood'
        ));
        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\ControllerStub', array('registerTask'), array($container));
        $result     = $controller->registerDefaultTask('dummy');

        $this->assertInstanceOf('\\FOF30\\Controller\\Controller', $result, 'Controller::registerDefaultTask should return an instance of itself');
    }

    /**
     * @group           Controller
     * @group           ControllerRegisterTask
     * @covers          FOF30\Controller\Controller::registerTask
     * @dataProvider    ControllerDataprovider::getTestRegisterTask
     */
    public function testRegisterTask($test, $check)
    {
        $msg        = 'Controller::registerDefaultTask %s - Case: '.$check['case'];
        $container  = new TestContainer(array(
            'componentName' => 'com_eastwood'
        ));
        $controller = new ControllerStub($container);

        ReflectionHelper::setValue($controller, 'methods', $test['mock']['methods']);

        $result  = $controller->registerTask($test['task'], $test['method']);

        $taskMap = ReflectionHelper::getValue($controller, 'taskMap');

        $this->assertInstanceOf('\\FOF30\\Controller\\Controller', $result, sprintf($msg, 'Should return an instance of itself'));

        if($check['register'])
        {
            $this->assertArrayHasKey(strtolower($test['task']), $taskMap, sprintf($msg, 'Should add the method to the internal mapping'));
        }
        else
        {
            $this->assertArrayNotHasKey(strtolower($test['task']), $taskMap, sprintf($msg, 'Should not add the method to the internal mapping'));
        }
    }

    /**
     * @group           Controller
     * @group           ControllerUnregisterTask
     * @covers          FOF30\Controller\Controller::unregisterTask
     */
    public function testUnregisterTask()
    {
        $msg        = 'Controller::unregisterDefaultTask %s';
        $container  = new TestContainer(array(
            'componentName' => 'com_eastwood'
        ));
        $controller = new ControllerStub($container);

        ReflectionHelper::setValue($controller, 'taskMap', array('foo' => 'bar'));

        $result  = $controller->unregisterTask('foo');

        $taskMap = ReflectionHelper::getValue($controller, 'taskMap');

        $this->assertInstanceOf('\\FOF30\\Controller\\Controller', $result, sprintf($msg, 'Should return an instance of itself'));
        $this->assertArrayNotHasKey('foo', $taskMap, sprintf($msg, 'Should remove the task form the mapping'));
    }

    /**
     * @group           Controller
     * @group           ControllerSetMessage
     * @covers          FOF30\Controller\Controller::setMessage
     * @dataProvider    ControllerDataprovider::getTestSetMessage
     */
    public function testSetMessage($test, $check)
    {
        $msg        = 'Controller::setMessage %s - Case: '.$check['case'];
        $controller = new ControllerStub(new TestContainer(array(
            'componentName' => 'com_eastwood'
        )));

        ReflectionHelper::setValue($controller, 'message', $test['mock']['previous']);

        if(is_null($test['type']))
        {
            $result  = $controller->setMessage($test['message']);
        }
        else
        {
            $result  = $controller->setMessage($test['message'], $test['type']);
        }

        $message = ReflectionHelper::getValue($controller, 'message');
        $type    = ReflectionHelper::getValue($controller, 'messageType');

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Should return the previous message'));
        $this->assertEquals($check['message'], $message, sprintf($msg, 'Did not set the message correctly'));
        $this->assertEquals($check['type'], $type, sprintf($msg, 'Did not set the message type correctly'));
    }

    /**
     * @group           Controller
     * @group           ControllerSetRedirect
     * @covers          FOF30\Controller\Controller::setRedirect
     * @dataProvider    ControllerDataprovider::getTestSetRedirect
     */
    public function testSetRedirect($test, $check)
    {
        $msg        = 'Controller::setRedirect %s - Case: '.$check['case'];
        $controller = new ControllerStub(new TestContainer(array(
            'componentName' => 'com_eastwood'
        )));

        ReflectionHelper::setValue($controller, 'messageType', $test['mock']['type']);

        $result  = $controller->setRedirect($test['url'], $test['msg'], $test['type']);

        $redirect = ReflectionHelper::getValue($controller, 'redirect');
        $message  = ReflectionHelper::getValue($controller, 'message');
        $type     = ReflectionHelper::getValue($controller, 'messageType');

        $this->assertInstanceOf('\\FOF30\\Controller\\Controller', $result, sprintf($msg, 'Should return an instance of itself'));
        $this->assertEquals($check['redirect'], $redirect, sprintf($msg, 'Did not set the redirect url correctly'));
        $this->assertEquals($check['message'], $message, sprintf($msg, 'Did not set the message correctly'));
        $this->assertEquals($check['type'], $type, sprintf($msg, 'Did not set the message type correctly'));
    }
}
