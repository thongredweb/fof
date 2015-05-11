<?php

namespace FOF30\Tests\DataController;

use FOF30\Factory\Exception\ModelNotFound;
use FOF30\Input\Input;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;
use FOF30\Tests\Stubs\Controller\DataControllerStub;
use FOF30\Tests\Stubs\Model\DataModelStub;

require_once 'DataControllerDataprovider.php';

/**
 * @covers      FOF30\Controller\DataController::<protected>
 * @covers      FOF30\Controller\DataController::<private>
 * @package     FOF30\Tests\DataController
 */
class DataControllertest extends DatabaseTest
{
    /**
     * @covers          FOF30\Controller\DataController::__construct
     * @dataProvider    DataControllerDataprovider::getTest__construct
     */
    public function test__construct($test, $check)
    {
        $msg = 'DataController::__construct %s - Case: '.$check['case'];

        $config = array();

        if($test['model'])
        {
            $config['modelName'] = $test['model'];
        }

        if($test['view'])
        {
            $config['viewName'] = $test['view'];
        }

        if($test['cache'])
        {
            $config['cacheableTasks'] = $test['cache'];
        }

        if($test['privileges'])
        {
            $config['taskPrivileges'] = $test['privileges'];
        }

        $controller = new DataControllerStub(static::$container, $config);

        $modelName = ReflectionHelper::getValue($controller, 'modelName');
        $viewName  = ReflectionHelper::getValue($controller, 'viewName');
        $cache     = ReflectionHelper::getValue($controller, 'cacheableTasks');
        $privileges = ReflectionHelper::getValue($controller, 'taskPrivileges');

        $this->assertEquals($check['model'], $modelName, sprintf($msg, 'Failed to set the correct modelName'));
        $this->assertEquals($check['view'], $viewName, sprintf($msg, 'Failed to set the correct viewName'));
        $this->assertEquals($check['cache'], $cache, sprintf($msg, 'Failed to set the correct task cache'));
        $this->assertEquals($check['privileges'], $privileges, sprintf($msg, 'Failed to set the correct task privileges'));
    }

    /**
     * @covers          FOF30\Controller\DataController::execute
     * @dataProvider    DataControllerDataprovider::getTestExecute
     */
    public function testExecute($test, $check)
    {
        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('getCrudTask', 'read'), array(self::$container));
        $controller->expects($check['getCrud'] ? $this->once() : $this->never())->method('getCrudTask')->willReturn('read');

        try{
            $controller->execute($test['task']);
        }
        catch(ModelNotFound $e)
        {
            // I don't care if I can't find the model, I'm just checking if the correct task is invoked
        }
    }

    /**
     * @covers          FOF30\Controller\DataController::getView
     * @dataProvider    DataControllerDataprovider::getTestGetView
     */
    public function testGetView($test, $check)
    {
        $msg        = 'DataController::getView %s - Case: '.$check['case'];
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
        $controller = new DataControllerStub($container, $test['constructConfig']);

        ReflectionHelper::setValue($controller, 'viewName', $test['mock']['viewName']);
        ReflectionHelper::setValue($controller, 'view', $test['mock']['view']);
        ReflectionHelper::setValue($controller, 'viewInstances', $test['mock']['instances']);
        ReflectionHelper::setValue($controller, 'hasForm', $test['mock']['hasForm']);

        $result = $controller->getView($test['name'], $test['config']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Created the wrong view'));
        $this->assertEquals($check['viewName'], $arguments['name'], sprintf($msg, 'Created the wrong view name'));
        $this->assertEquals($check['type'], $arguments['type'], sprintf($msg, 'Created the wrong view type'));
        $this->assertEquals($check['config'], $arguments['config'], sprintf($msg, 'Passed the wrong config'));
    }

    /**
     * @group           DataController
     * @group           DataControllerBrowse
     * @covers          FOF30\Controller\DataController::browse
     * @dataProvider    DataControllerDataprovider::getTestBrowse
     */
    public function testBrowse($test, $check)
    {
        $msg = 'DataController::browse %s - Case: '.$check['case'];

        $checker = array();
        $input = new Input($test['mock']['input']);

        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => $input
        ));

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('display', 'getModel'), array($container));
        $controller->expects($this->any())->method('display')->with($this->equalTo($check['display']));

        $controller->expects($this->any())->method('getModel')->willReturn(new ClosureHelper(array(
            'savestate' => function($self, $state) use(&$checker){
                $checker['savestate'] = $state;
            },
            'setFormName' => function($self, $formName) use(&$checker){
                $checker['setFormName'] = $formName;
            },
            'getForm' => function() use($test){
                return $test['mock']['getForm'];
            }
        )));

        ReflectionHelper::setValue($controller, 'cacheableTasks', $test['mock']['cache']);
        ReflectionHelper::setValue($controller, 'layout', $test['mock']['layout']);

        $controller->browse();

        $hasForm = ReflectionHelper::getValue($controller, 'hasForm');

        $this->assertEquals($check['savestate'], $checker['savestate'], sprintf($msg, 'Failed to correctly set the savestate'));
        $this->assertEquals($check['formName'], $checker['setFormName'], sprintf($msg, 'Failed to correctly set the form name'));
        $this->assertEquals($check['hasForm'], $hasForm, sprintf($msg, 'Failed to set hasForm'));
    }

    /**
     * @covers          FOF30\Controller\DataController::read
     * @dataProvider    DataControllerDataprovider::getTestRead
     */
    public function testRead($test, $check)
    {
        $msg     = 'DataController::read %s - Case: '.$check['case'];

        $modelMethods = array('getId', 'getForm', 'setFormName');
        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', $modelMethods, array(), '', false);

        $model->expects($this->any())->method('getId')->willReturnCallback(function() use (&$test){
            return array_shift($test['mock']['getId']);
        });

        $model->expects($this->any())->method('getForm')->willReturn($test['mock']['getForm']);
        $model->expects($this->any())->method('setFormName')->with($check['setForm']);


        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('getModel', 'getIDsFromRequest', 'display'), array(static::$container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')
            ->willReturn($test['mock']['ids']);

        $controller->expects($this->any())->method('display')->with($this->equalTo($check['display']));

        if($check['exception'])
        {
            $this->setExpectedException('FOF30\Controller\Exception\ItemNotFound', 'COM_FAKEAPP_ERR_NESTEDSET_NOTFOUND');
        }

        ReflectionHelper::setValue($controller, 'layout', $test['mock']['layout']);
        ReflectionHelper::setValue($controller, 'cacheableTasks', $test['mock']['cache']);

        $controller->read();

        $layout  = ReflectionHelper::getValue($controller, 'layout');
        $hasForm = ReflectionHelper::getValue($controller, 'hasForm');

        $this->assertEquals($check['layout'], $layout, sprintf($msg, 'Failed to set the layout'));
        $this->assertEquals($check['hasForm'], $hasForm, sprintf($msg, 'Failed to set the hasForm flag'));
    }

    /**
     * @covers          FOF30\Controller\DataController::add
     * @dataProvider    DataControllerDataprovider::getTestAdd
     */
    public function testAdd($test, $check)
    {
        $msg = 'DataController::add %s - Case: '.$check['case'];
        $sessionMock = array();

        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'session'       => new ClosureHelper(array(
                'set' => function($self, $key, $value, $namespace) use(&$sessionMock){
                    $sessionMock[$namespace.'.'.$key] = $value;
                },
                'get' => function($self, $key, $default, $namespace) use(&$sessionMock){
                    $key = $namespace.'.'.$key;

                    if(isset($sessionMock[$key])){
                        return $sessionMock[$key];
                    }

                    return $default;
                }
            ))
        ));

        $container->session->set('dummycontrollers.savedata', $test['mock']['session'], 'com_fakeapp');

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('reset', 'bind', 'getForm', 'setFormName'), array(), '', false);
        $model->expects($check['bind'] ? $this->once() : $this->never())->method('bind')->with($check['bind']);
        $model->expects($this->any())->method('setFormName')->with($this->equalTo($check['formName']));
        $model->expects($this->any())->method('getForm')->willReturn($test['mock']['getForm']);

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('getModel', 'display'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('display')->with($this->equalTo($check['display']));

        ReflectionHelper::setValue($controller, 'layout', $test['mock']['layout']);
        ReflectionHelper::setValue($controller, 'cacheableTasks', $test['mock']['cache']);

        $controller->add();

        $layout  = ReflectionHelper::getValue($controller, 'layout');
        $hasForm = ReflectionHelper::getValue($controller, 'hasForm');
        $sessionData = $container->session->get('dummycontrollers.savedata', null, 'com_fakeapp');

        $this->assertEquals($check['layout'], $layout, sprintf($msg, 'Failed to set the layout'));
        $this->assertEquals($check['hasForm'], $hasForm, sprintf($msg, 'Failed to set the hasForm flag'));
        $this->assertNull($sessionData, sprintf($msg, 'Failed to wipe session data'));
    }

    /**
     * @group           DataControllerEdit
     * @covers          FOF30\Controller\DataController::edit
     * @dataProvider    DataControllerDataprovider::getTestEdit
     */
    public function testEdit($test, $check)
    {
        $msg = 'DataController::edit %s - Case: '.$check['case'];
        $sessionMock = array();

        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
            'session'       => new ClosureHelper(array(
                'set' => function($self, $key, $value, $namespace) use(&$sessionMock){
                    $sessionMock[$namespace.'.'.$key] = $value;
                },
                'get' => function($self, $key, $default, $namespace) use(&$sessionMock){
                    $key = $namespace.'.'.$key;

                    if(isset($sessionMock[$key])){
                        return $sessionMock[$key];
                    }

                    return $default;
                }
            ))
        ));

        $container->session->set('dummycontrollers.savedata', $test['mock']['session'], 'com_fakeapp');

        $modelMethods = array('getId', 'lock', 'bind', 'setFormName', 'getForm', 'isLocked');
        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', $modelMethods, array(), '', false);
        $model->expects($this->any())->method('getId')->willReturn($test['mock']['getId']);

        $method = $model->expects($this->any())->method('lock');

        if($test['mock']['lock'] === 'throw')
        {
            $method->willThrowException(new \Exception('Exception thrown while locking'));
        }
        else
        {
            $method->willReturn(null);
        }

        $model->expects($check['bind'] ? $this->once() : $this->never())->method('bind')->with($this->equalTo($check['bind']));
        $model->expects($this->any())->method('setFormName')->with($this->equalTo($check['formName']));
        $model->expects($this->any())->method('getForm')->willReturn($test['mock']['getForm']);

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub',
            array('getModel', 'getIDsFromRequest', 'setRedirect', 'display'), array($container));

        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($check['getFromReq'] ? $this->once() : $this->never())->method('getIDsFromRequest');
        $controller->expects($check['redirect'] ? $this->once() : $this->never())->method('setRedirect')
            ->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo('error'));
        $controller->expects($this->any())->method('display')->with($this->equalTo($check['display']));

        ReflectionHelper::setValue($controller, 'layout', $test['mock']['layout']);
        ReflectionHelper::setValue($controller, 'cacheableTasks', $test['mock']['cache']);

        $controller->edit();

        $layout  = ReflectionHelper::getValue($controller, 'layout');
        $hasForm = ReflectionHelper::getValue($controller, 'hasForm');
        $sessionData = $container->session->get('dummycontrollers.savedata', null, 'com_fakeapp');

        $this->assertEquals($check['layout'], $layout, sprintf($msg, 'Failed to set the layout'));
        $this->assertEquals($check['hasForm'], $hasForm, sprintf($msg, 'Failed to set the hasForm flag'));
        $this->assertNull($sessionData, sprintf($msg, 'Failed to wipe session data'));
    }

    /**
     * @covers          FOF30\Controller\DataController::apply
     * @dataProvider    DataControllerDataprovider::getTestApply
     */
    public function testApply($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'id' => $test['mock']['id'],
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
        ));

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'applySave', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('applySave')->willReturn($test['mock']['apply']);
        $controller->expects($check['redirect'] ? $this->once() : $this->never())->method('setRedirect')
            ->with($this->equalTo($check['url']), $this->equalTo($check['msg']));

        $controller->apply();
    }

    /**
     * @covers          FOF30\Controller\DataController::copy
     * @dataProvider    DataControllerDataprovider::getTestCopy
     */
    public function testCopy($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            ))
        ));

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('find', 'copy'), array($container), '', false);
        $model->expects($this->any())->method('find')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['find']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in find');
                }

                return $ret;
            }
        );

        $model->expects($this->any())->method('copy')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['copy']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in copy');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->copy();
    }

    /**
     * @covers          FOF30\Controller\DataController::save
     * @dataProvider    DataControllerDataprovider::getTestSave
     */
    public function testSave($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            ))
        ));

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'applySave', 'setRedirect'), array($container));
        $controller->expects($this->once())->method('applySave')->willReturn($test['mock']['apply']);
        $controller->expects($check['redirect'] ? $this->once() : $this->never())->method('setRedirect')
            ->with($this->equalTo($check['url']), $this->equalTo($check['msg']))
        ;

        $controller->save();
    }

    /**
     * @covers          FOF30\Controller\DataController::savenew
     * @dataProvider    DataControllerDataprovider::getTestSavenew
     */
    public function testSavenew($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            ))
        ));

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'applySave', 'setRedirect'), array($container));
        $controller->expects($this->once())->method('applySave')->willReturn($test['mock']['apply']);
        $controller->expects($check['redirect'] ? $this->once() : $this->never())->method('setRedirect')
            ->with($this->equalTo($check['url']), $this->equalTo($check['msg']))
        ;

        $controller->savenew();
    }

    /**
     * @covers          FOF30\Controller\DataController::cancel
     * @dataProvider    DataControllerDataprovider::getTestCancel
     */
    public function testCancel($test, $check)
    {
        $sessionMock['com_fakeapp.dummycontroller.savedata'] = $test['mock']['session'];

        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
            'session'       => new ClosureHelper(array(
                'set' => function($self, $key, $value, $namespace) use(&$sessionMock){
                    $sessionMock[$namespace.'.'.$key] = $value;
                }
            ))
        ));

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('unlock', 'getId'), array($container), '', false);
        $model->expects($this->any())->method('getId')->willReturn($test['mock']['getId']);

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($check['getFromReq'] ? $this->once() : $this->never())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);
        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']));

        $controller->cancel();

        $this->assertNull($sessionMock['com_fakeapp.dummycontrollers.savedata'], 'DataController::cancel Failed to clear the session');
    }

    /**
     * @covers          FOF30\Controller\DataController::publish
     * @dataProvider    DataControllerDataprovider::getTestPublish
     */
    public function testPublish($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            ))
        ));

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('publish'), array($container, $config));
        $model->expects($this->any())->method('publish')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['publish']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in publish');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->publish();
    }

    /**
     * @covers          FOF30\Controller\DataController::unpublish
     * @dataProvider    DataControllerDataprovider::getTestUnpublish
     */
    public function testUnpublish($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            ))
        ));

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('unpublish'), array($container, $config));
        $model->expects($this->any())->method('unpublish')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['unpublish']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in unpublish');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->unpublish();
    }

    /**
     * @covers          FOF30\Controller\DataController::archive
     * @dataProvider    DataControllerDataprovider::getTestArchive
     */
    public function testArchive($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            ))
        ));

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('archive'), array($container, $config));
        $model->expects($this->any())->method('archive')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['archive']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in archive');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->archive();
    }

    /**
     * @covers          FOF30\Controller\DataController::trash
     * @dataProvider    DataControllerDataprovider::getTestTrash
     */
    public function testTrash($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            ))
        ));

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('trash'), array($container, $config));
        $model->expects($this->any())->method('trash')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['trash']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in trash');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->trash();
    }

    /**
     * @covers          FOF30\Controller\DataController::checkin
     * @dataProvider    DataControllerDataprovider::getTestCheckin
     */
    public function testCheckin($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            ))
        ));

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('checkin'), array($container, $config));
        $model->expects($this->any())->method('checkin')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['checkin']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in checkin');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->checkin();
    }

    /**
     * The best way to test with method is to run it and check vs the database
     *
     * @group           DataControllerSaveOrder
     * @covers          FOF30\Controller\DataController::saveorder
     * @dataProvider    DataControllerDataprovider::getTestsaveorder
     */
    public function testSaveorder($test, $check)
    {
        $msg = 'DataController::saveorder %s - Case: '.$check['case'];

        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'order'     => $test['ordering'],
                'returnurl' => $test['returnurl'] ? base64_encode($test['returnurl']) : '',
            ))
        ));

        $config = array(
            'autoChecks'  => false,
            'idFieldName' => $test['id'],
            'tableName'   => $test['table']
        );

        $model      = new DataModelStub($container, $config);
        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);
        $controller->expects($this->once())->method('setRedirect')
            ->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->saveorder();

        $db = self::$container->db;

        $query = $db->getQuery(true)
                    ->select('foftest_foobar_id')
                    ->from($db->qn('#__foftest_foobars'))
                    ->order($db->qn('ordering').' ASC');
        $rows = $db->setQuery($query)->loadColumn();

        $this->assertEquals($check['rows'], $rows, sprintf($msg, 'Failed to save the order of the rows'));
    }

    /**
     * @covers          FOF30\Controller\DataController::orderdown
     * @dataProvider    DataControllerDataprovider::getTestOrderdown
     */
    public function testOrderdown($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            ))
        ));

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('move', 'getId'), array($container, $config));
        $model->expects($this->once())->method('getId')->willReturn($test['mock']['getId']);
        $model->expects($this->any())->method('move')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['move']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in move');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($check['getFromReq'] ? $this->once() : $this->never())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->orderdown();
    }

    /**
     * @covers          FOF30\Controller\DataController::orderup
     * @dataProvider    DataControllerDataprovider::getTestOrderup
     */
    public function testOrderup($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            ))
        ));

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('move', 'getId'), array($container, $config));
        $model->expects($this->once())->method('getId')->willReturn($test['mock']['getId']);
        $model->expects($this->any())->method('move')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['move']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in move');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($check['getFromReq'] ? $this->once() : $this->never())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->orderup();
    }

    /**
     * @group           DataController
     * @group           DataControllerRemove
     * @covers          FOF30\Controller\DataController::remove
     * @dataProvider    DataControllerDataprovider::getTestRemove
     */
    public function testRemove($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            ))
        ));

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('find', 'delete'), array($container, $config));
        $model->expects($this->any())->method('find')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['find']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in find');
                }

                return $ret;
            }
        );

        $model->expects($this->any())->method('delete')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['delete']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in delete');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->remove();
    }

    /**
     * @covers          FOF30\Controller\DataController::getModel
     * @dataProvider    DataControllerDataprovider::getTestGetModel
     */
    public function testGetModel($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp'
        ));

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $controller = new DataControllerStub($container);

        ReflectionHelper::setValue($controller, 'modelName', $test['mock']['modelname']);

        if($check['exception'])
        {
            $this->setExpectedException('FOF30\Controller\Exception\NotADataModel');
        }

        $model = $controller->getModel($test['name'], $config);

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel', $model, 'DataController::getModel should return a DataModel');
    }

    /**
     * @covers          FOF30\Controller\DataController::getIDsFromRequest
     * @dataProvider    DataControllerDataprovider::getTestGetIDsFromRequest
     */
    public function testGetIDsFromRequest($test, $check)
    {
        $msg = 'DataController::getIDsFromRequest %s - Case: '.$check['case'];

        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'cid' => $test['mock']['cid'],
                'id'  => $test['mock']['id'],
                'foftest_foobar_id' => $test['mock']['kid']
            ))
        ));

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('find'), array($container, $config));
        $model->expects($check['load'] ? $this->once() : $this->never())->method('find')->with($check['loadid']);

        $controller = new DataControllerStub($container);

        $result = $controller->getIDsFromRequest($model, $test['load']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong value'));
    }

    /**
     * @covers          FOF30\Controller\DataController::loadHistory
     * @dataProvider    DataControllerDataprovider::getTestLoadHistory
     */
    public function testLoadHistory($test, $check)
    {
        $msg = 'DataController::loadhistory %s - Case: '.$check['case'];

        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'version_id' => $test['mock']['version'],
                'returnurl'  => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : ''
            ))
        ));

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('loadhistory', 'store', 'lock', 'unlock', 'getState'), array($container, $config));
        $model->expects($this->any())->method('loadhistory')->willReturnCallback(function() use ($test){
            if($test['mock']['history'] == 'exception'){
                throw new \Exception('Load history error');
            }
        })
            ->with($this->equalTo($check['version_id']), $this->equalTo($check['alias']));

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('getModel', 'checkACL', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('setRedirect')->with(
            $this->equalTo($check['url']),
            $this->equalTo($check['msg']),
            $this->equalTo($check['type'])
        );

        $controller->expects($this->any())->method('checkACL')->willReturn($test['mock']['checkACL']);

        $result = $controller->loadhistory();

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @covers          FOF30\Controller\DataController::getItemidURLSuffix
     * @dataProvider    DataControllerDataprovider::getTestGetItemidURLSuffix
     */
    public function testGetItemidURLSuffix($test, $check)
    {
        $msg = 'DataController::getItemidURLSuffix %s - Case: '.$check['case'];

        $container = new TestContainer(array(
            'input' => new Input(array(
                'Itemid' => $test['mock']['itemid']
            ))
        ));

        $platform = static::$container->platform;
        $platform::$isCli   = !$test['mock']['frontend'];
        $platform::$isAdmin = !$test['mock']['frontend'];

        $controller = new DataControllerStub($container);

        $result = $controller->getItemidURLSuffix();

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong value'));
    }
}