<?php
/**
 * @package        FOF
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace FOF30\Tests\View;

use Fakeapp\Admin\View\Foobar\Html;
use FOF30\Input\Input;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;
use FOF30\Tests\Stubs\Model\ModelStub;
use FOF30\Tests\Stubs\View\ViewStub;

require_once 'ViewDataprovider.php';

/**
 * @covers      FOF30\View\View::<protected>
 * @covers      FOF30\View\View::<private>
 * @package     FOF30\Tests\View
 */
class ViewTest extends FOFTestCase
{
    protected function setUp()
    {
        parent::setUp();

        // Let's set a fake template to the platform
        $platform = static::$container->platform;
        $platform::$template = 'fake_test_template';

        // This is required by View constructor, since it will create an instance of JUri
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
     * @covers          FOF30\View\View::__construct
     * @dataProvider    ViewDataprovider::getTest__construct
     */
    public function test__construct($test, $check)
    {
        $msg = 'View::__construct %s - Case: '.$check['case'];

        $platform = static::$container->platform;

        $platform::$template    = 'fake_test_template';
        $platform::$uriBase     = 'www.example.com';
        $platform::$runPlugins  = function() use($test){
            return $test['mock']['plugins'];
        };

        $view = new ViewStub(static::$container, $test['config']);

        $name           = ReflectionHelper::getValue($view, 'name');
        $layout         = ReflectionHelper::getValue($view, 'layout');
        $layoutTemplate = ReflectionHelper::getValue($view, 'layoutTemplate');
        $templatePaths  = ReflectionHelper::getValue($view, 'templatePaths');
        $baseurl        = ReflectionHelper::getValue($view, 'baseurl');
        $engines        = ReflectionHelper::getValue($view, 'viewEngineMap');

        $this->assertEquals($check['name'], $name, sprintf($msg, 'Failed to set the name'));
        $this->assertEquals($check['layout'], $layout, sprintf($msg, 'Failed to set the layout'));
        $this->assertEquals($check['layoutTemplate'], $layoutTemplate, sprintf($msg, 'Failed to set the layoutTemplate'));
        $this->assertEquals($check['templatePaths'], $templatePaths, sprintf($msg, 'Failed to set the templatePaths'));
        $this->assertEquals($check['baseurl'], $baseurl, sprintf($msg, 'Failed to set the baseurl'));
        $this->assertEquals($check['engines'], $engines, sprintf($msg, 'Failed to set the viewEngineMap'));
    }

    /**
     * @group           View
     * @covers          FOF30\View\View::__get
     * @dataProvider    ViewDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $msg = 'View::__get %s - Case: '.$check['case'];

        $input = new Input();

        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input'         => $input
        ));

        $view = new ViewStub($container);

        $property = $test['method'];

        // Suppress the error, so I can check the code executed AFTER the warning
        $result = @$view->$property;

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
     * @covers          FOF30\View\View::getName
     */
    public function testGetName()
    {
        $view = new Html(static::$container);

        ReflectionHelper::setValue($view, 'name', null);

        $name = $view->getName();

        $this->assertEquals('Foobar', $name, 'View::getName Failed to fetch the correct view name');
    }

    /**
     * @covers          FOF30\View\View::getName
     */
    public function testGetNameException()
    {
        $this->setExpectedException('FOF30\View\Exception\CannotGetName');

        $view = $this->getMock('\\FOF30\\Tests\\Stubs\\View\\ViewStub', array('escape'),
            array(static::$container, array(), array('getName' => 'parent')));

        ReflectionHelper::setValue($view, 'name', null);

        $view->getName();
    }

    /**
     * @group           View
     * @group           ViewEscape
     * @covers          FOF30\View\View::escape
     */
    public function testEscape()
    {
        $view = new ViewStub(static::$container);
        $escape = $view->escape('<>àè?"\'');

        $this->assertEquals("&lt;&gt;àè?&quot;'", $escape, 'View::escape Failed to escape the string');
    }

    /**
     * @group           View
     * @group           ViewGet
     * @covers          FOF30\View\View::get
     * @dataProvider    ViewDataprovider::getTestGet
     */
    public function testGet($test, $check)
    {
        $msg  = 'View::get %s - Case: '.$check['case'];
        $view = new ViewStub(static::$container);

        if($test['mock']['viewProperty'])
        {
            $key = $test['mock']['viewProperty']['key'];
            $view->$key = $test['mock']['viewProperty']['value'];
        }

        ReflectionHelper::setValue($view, 'defaultModel', $test['mock']['defaultModel']);
        ReflectionHelper::setValue($view, 'modelInstances', $test['mock']['instances']);

        $result = $view->get($test['property'], $test['default'], $test['model']);

        if(is_object($result))
        {
            $this->assertInstanceOf('\\FOF30\\Model\\Model', $result, sprintf($msg, 'Should return an instance of the model'));
        }
        else
        {
            $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong value'));
        }
    }

    /**
     * @group           View
     * @group           ViewGetModel
     * @covers          FOF30\View\View::getModel
     * @dataProvider    ViewDataprovider::getTestGetModel
     */
    public function testGetModel($test, $check)
    {
        $msg  = 'View::getModel %s - Case: '.$check['case'];
        $view = new ViewStub(static::$container);

        ReflectionHelper::setValue($view, 'defaultModel', $test['mock']['defaultModel']);
        ReflectionHelper::setValue($view, 'name', $test['mock']['name']);
        ReflectionHelper::setValue($view, 'modelInstances', $test['mock']['instances']);

        if($check['exception'])
        {
            $this->setExpectedException('FOF30\View\Exception\ModelNotFound');
        }

        $result = $view->getModel($test['name']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Created the wrong model'));
    }

    /**
     * @group           View
     * @group           ViewSetDefaultModel
     * @covers          FOF30\View\View::setDefaultModel
     */
    public function testSetDefaultModel()
    {
        $model = new ModelStub(static::$container);

        $view  = $this->getMock('\\FOF30\\Tests\\Stubs\\View\\ViewStub', array('setDefaultModelName', 'setModel'), array(static::$container));
        $view->expects($this->once())->method('setDefaultModelName')->with($this->equalTo('nestedset'));
        // The first param is NULL since we mocked the previous function and the property defaultModel is not set
        $view->expects($this->once())->method('setModel')->with($this->equalTo(null), $this->equalTo($model));

        $view->setDefaultModel($model);
    }

    /**
     * @group           View
     * @group           ViewSetDefaultModelName
     * @covers          FOF30\View\View::setDefaultModelName
     */
    public function testDefaultModelName()
    {
        $view = new ViewStub(static::$container);
        $view->setDefaultModelName('foobar');

        $name = ReflectionHelper::getValue($view, 'defaultModel');

        $this->assertEquals('foobar', $name, 'View::setDefaultModelName Failed to set the internal name');
    }

    /**
     * @group           View
     * @group           ViewSetModel
     * @covers          FOF30\View\View::setModel
     */
    public function testSetModel()
    {
        $model      = new ModelStub(static::$container);
        $controller = new ViewStub(static::$container);
        $controller->setModel('foobar', $model);

        $models = ReflectionHelper::getValue($controller, 'modelInstances');

        $this->assertArrayHasKey('foobar', $models, 'View::setModel Failed to save the model');
        $this->assertSame($model, $models['foobar'], 'View::setModel Failed to store the same copy of the passed model');
    }

    /**
     * @group           View
     * @group           ViewDisplay
     * @covers          FOF30\View\View::display
     * @dataProvider    ViewDataprovider::getTestDisplay
     */
    public function testDisplay($test, $check)
    {
        $msg     = 'View::display %s - Case: '.$check['case'];
        $before  = array('counter' => 0);
        $after   = array('counter' => 0);
        $methods = array();

        if(isset($test['mock']['before']))
        {
            $methods['onBeforeDummy'] = function($self) use($test, &$before){
                $before['counter']++;

                if(!$test['mock']['before'])
                {
                    throw new \Exception();
                }
            };
        }

        if(isset($test['mock']['after']))
        {
            $methods['onAfterDummy'] = function($self) use($test, &$after){
                $after['counter']++;

                if(!$test['mock']['after'])
                {
                    throw new \Exception();
                }
            };
        }

        $view = $this->getMock('\\FOF30\\Tests\\Stubs\\View\\ViewStub', array('loadTemplate', 'preRender'), array(static::$container, array(), $methods));
        $view->expects($this->any())->method('preRender')->willReturnCallback(function() use($test){
            echo $test['mock']['pre'];
        });

        if($check['exception'] === false)
        {
            $this->expectOutputString($check['output']);
        }
        else
        {
            $this->setExpectedException($check['exception']);
        }

        // Do I really invoke the load method?
        if($check['load'])
        {
            $view->expects($this->once())->method('loadTemplate')->with($check['tpl'])->willReturn($test['mock']['output']);
        }
        else
        {
            $view->expects($this->never())->method('loadTemplate');
        }

        ReflectionHelper::setValue($view, 'doTask', $test['mock']['doTask']);
        ReflectionHelper::setValue($view, 'doPreRender', $test['mock']['doPreRender']);

        $result = $view->display($test['tpl']);

        // I can run assertions only if the display method doesn't bail out with an exception
        if($check['exception'] === false)
        {
            $this->assertEquals($check['before'], $before, sprintf($msg, 'Failed to correctly process the onBefore method'));
            $this->assertEquals($check['after'], $before, sprintf($msg, 'Failed to correctly process the onAfter method'));

            // If we don't get an exception, we should return true
            $this->assertSame(true, $result, sprintf($msg, 'Should return true if an exception is not thrown'));
        }
    }

    /**
     * In this test I will only check for the result of the function
     *
     * @group           View
     * @group           ViewLoadTemplate
     * @covers          FOF30\View\View::loadTemplate
     * @dataProvider    ViewDataprovider::getTestLoadTemplate
     */
    public function testLoadTemplate($test, $check)
    {
        $msg = 'View::loadTemplate %s - Case: '.$check['case'];

        $view = $this->getMock('\\FOF30\\Tests\\Stubs\\View\\ViewStub', array('loadAnyTemplate', 'getLayout'), array(static::$container));
        $view->expects($this->any())->method('getLayout')->willReturn($test['mock']['layout']);
        $view->expects($this->any())->method('loadAnyTemplate')->willReturnCallback(
            function() use (&$test){
                $result = array_shift($test['mock']['any']);

                if($result == 'throw')
                {
                    throw new \Exception();
                }

                return $result;
            }
        );

        $viewFinder = new ClosureHelper(array(
            'getViewTemplateUris' => function() use ($test){
                return $test['mock']['viewFinder'];
            }
        ));

        ReflectionHelper::setValue($view, 'viewFinder', $viewFinder);

        if($check['exception'])
        {
            $this->setExpectedException('\Exception');
        }

        $result = $view->loadTemplate($test['tpl'], $test['strict']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong value'));
    }

    /**
     * @covers          FOF30\View\View::loadAnyTemplate
     * @dataProvider    ViewDataprovider::getTestLoadAnyTemplate
     */
    public function testLoadAnyTemplate($test, $check)
    {
        $msg = 'View::loadAnyTemplate %s - Case: '.$check['case'];
        $checkUriPath = array();

        $view = $this->getMock('\\FOF30\\Tests\\Stubs\\View\\ViewStub',
            array('getEngine', 'incrementRender', 'decrementRender', 'flushSectionsIfDoneRendering'),
            array(static::$container));

        $view->expects($this->any())->method('getEngine')->willReturn(new ClosureHelper(array(
            'get' => function() use($test){
                return $test['mock']['engineGet'];
            }
        )));

        $viewFinder = new ClosureHelper(array(
            'resolveUriToPath' => function($self, $uri, $layout, $extra) use($test, &$checkUriPath){
                $checkUriPath['uri']   = $uri;
                $checkUriPath['extra'] = $extra;
                return null;
            }
        ));

        ReflectionHelper::setValue($view, 'viewTemplateAliases', $test['mock']['alias']);
        ReflectionHelper::setValue($view, 'viewFinder', $viewFinder);

        if($test['mock']['path'])
        {
            $view->path = $test['mock']['path'];
        }

        if($test['mock']['_path'])
        {
            $view->_path = $test['mock']['_path'];
        }

        $result = $view->loadAnyTemplate($test['uri'], $test['forceParams'], $test['callback']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
        $this->assertEquals($check['uri'], $checkUriPath['uri'], sprintf($msg, 'Failed to set the correct URI'));
        $this->assertEquals($check['extra'], $checkUriPath['extra'], sprintf($msg, 'Failed to set the correct extra paths'));
    }

    /**
     * @covers          FOF30\View\View::incrementRender
     */
    public function testIncrementRender()
    {
        $view = new ViewStub(static::$container);

        ReflectionHelper::setValue($view, 'renderCount', 10);

        $view->incrementRender();

        $counter = ReflectionHelper::getValue($view, 'renderCount');

        $this->assertEquals(11, $counter, 'View::incrementRender Failed to increment the internal counter');
    }

    /**
     * @covers          FOF30\View\View::decrementRender
     */
    public function testDecrementRender()
    {
        $view = new ViewStub(static::$container);

        ReflectionHelper::setValue($view, 'renderCount', 10);

        $view->decrementRender();

        $counter = ReflectionHelper::getValue($view, 'renderCount');

        $this->assertEquals(9, $counter, 'View::decrementRender Failed to increment the internal counter');
    }

    /**
     * @covers          FOF30\View\View::doneRendering
     * @dataProvider    ViewDataprovider::getTestDoneRendering
     */
    public function testDoneRendering($test, $check)
    {
        $msg  = 'View::doneRendering %s - Case: '.$check['case'];
        $view = new ViewStub(static::$container);

        ReflectionHelper::setValue($view, 'renderCount', $test['counter']);

        $result = $view->doneRendering();

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @covers          FOF30\View\View::renderEach
     * @dataProvider    ViewDataprovider::getTestRenderEach
     */
    public function testRenderEach($test, $check)
    {
        $msg = 'View::renderEach %s - Case: '.$check['case'];

        $view = $this->getMock('\\FOF30\\Tests\\Stubs\\View\\ViewStub',
            array('loadAnyTemplate'),
            array(static::$container));

        $view->expects($check['loadAny'] ? $this->atLeastOnce() : $this->never())->method('loadAnyTemplate')
            ->willReturnCallback(function() use(&$test) {
                return array_shift($test['mock']['loadAny']);
        });

        $result = $view->renderEach('admin:com_fakeapp/foobar/default', $test['data'], 'item', $test['empty']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           View
     * @group           ViewGetLayout
     * @covers          FOF30\View\View::getLayout
     */
    public function testGetLayout()
    {
        $view = new ViewStub(static::$container);

        ReflectionHelper::setValue($view, 'layout', 'foobar');

        $this->assertEquals('foobar', $view->getLayout(), 'View::getLayout Failed to return the layout');
    }

    /**
     * @group           View
     * @group           ViewSetLayout
     * @covers          FOF30\View\View::setLayout
     * @dataProvider    ViewDataprovider::getTestSetLayout
     */
    public function testSetLayout($test, $check)
    {
        $msg  = 'View::setLayout %s - Case: '.$check['case'];
        $view = new ViewStub(static::$container);

        ReflectionHelper::setValue($view, 'layout', $test['mock']['layout']);

        $result = $view->setLayout($test['layout']);

        $layout = ReflectionHelper::getValue($view, 'layout');
        $tmpl   = ReflectionHelper::getValue($view, 'layoutTemplate');

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong value'));
        $this->assertEquals($check['layout'], $layout, sprintf($msg, 'Set the wrong layout'));
        $this->assertEquals($check['tmpl'], $tmpl, sprintf($msg, 'Set the wrong layout template'));
    }

    /**
     * @covers          FOF30\View\View::startSection
     * @dataProvider    ViewDataprovider::getTestStartSection
     */
    public function testStartSection($test, $check)
    {
        $msg  = 'View::startSection %s - Case: '.$check['case'];
        $view = new ViewStub(static::$container);

        ReflectionHelper::setValue($view, 'sections', $test['mock']['sections']);

        $view->startSection($test['section'], $test['content']);

        // I have to turn off output buffering since it's started inside the startSection method
        if($check['closeBuffer'])
        {
            @ob_end_clean();
        }

        $sections = ReflectionHelper::getValue($view, 'sections');
        $stack    = ReflectionHelper::getValue($view, 'sectionStack');

        $this->assertEquals($check['sections'], $sections, sprintf($msg, 'Failed to set the sections array'));
        $this->assertEquals($check['stack'], $stack, sprintf($msg, 'Failed to set the section stack'));
    }

    /**
     * @covers          FOF30\View\View::stopSection
     * @dataProvider    ViewDataprovider::getTestStopSection
     */
    public function testStopSection($test, $check)
    {
        $msg = 'View::stopSection %s - Case: '.$check['case'];

        $view = new ViewStub(static::$container);

        ReflectionHelper::setValue($view, 'sectionStack', $test['mock']['stack']);
        ReflectionHelper::setValue($view, 'sections', $test['mock']['sections']);

        // I have to start the output buffering, since it will be stopped inside the function
        @ob_start();

        echo $test['contents'];

        $result = $view->stopSection($test['overwrite']);

        $sections = ReflectionHelper::getValue($view, 'sections');

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
        $this->assertArrayHasKey($result, $sections, sprintf($msg, 'Failed to create the section'));
        $this->assertEquals($check['contents'], $sections[$result], sprintf($msg, 'Wrong section content'));
    }

    /**
     * @covers          FOF30\View\View::stopSection
     */
    public function testStopSectionException()
    {
        $this->setExpectedException('FOF30\View\Exception\EmptyStack');

        // I have to start the output buffering, since it will be stopped inside the function
        @ob_start();

        $view = new ViewStub(static::$container);

         $view->stopSection();
    }

    /**
     * @covers          FOF30\View\View::appendSection
     * @dataProvider    ViewDataprovider::getTestAppendSection
     */
    public function testAppendSection($test, $check)
    {
        $msg = 'View::appendSection %s - Case: '.$check['case'];

        $view = new ViewStub(static::$container);

        ReflectionHelper::setValue($view, 'sectionStack', $test['mock']['stack']);
        ReflectionHelper::setValue($view, 'sections', $test['mock']['sections']);

        // I have to start the output buffering, since it will be stopped inside the function
        @ob_start();

        echo $test['contents'];

        $result = $view->appendSection();

        $sections = ReflectionHelper::getValue($view, 'sections');

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
        $this->assertArrayHasKey($result, $sections, sprintf($msg, 'Failed to create the section'));
        $this->assertEquals($check['contents'], $sections[$result], sprintf($msg, 'Wrong section content'));
    }

    /**
     * @covers          FOF30\View\View::appendSection
     */
    public function testAppendSectionException()
    {
        $this->setExpectedException('FOF30\View\Exception\EmptyStack');

        // I have to start the output buffering, since it will be stopped inside the function
        @ob_start();

        $view = new ViewStub(static::$container);

        $view->appendSection();
    }

    /**
     * @covers          FOF30\View\View::yieldContent
     * @dataProvider    ViewDataprovider::getTestYieldContent
     */
    public function testYieldContent($test, $check)
    {
        $msg  = 'View::yieldContent %s - Case: '.$check['case'];
        $view = new ViewStub(static::$container);

        ReflectionHelper::setValue($view, 'sections', $test['mock']['sections']);

        $result = $view->yieldContent($test['section'], $test['default']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @covers          FOF30\View\View::flushSections
     */
    public function testFlushSections()
    {
        $view = new ViewStub(static::$container);

        ReflectionHelper::setValue($view, 'sections', array(1,2,3));
        ReflectionHelper::setValue($view, 'sectionStack', array(1,2,3));

        $view->flushSections();

        $this->assertEmpty(ReflectionHelper::getValue($view, 'sections'), 'View::flushSections Failed to flush the sections');
        $this->assertEmpty(ReflectionHelper::getValue($view, 'sectionStack'), 'View::flushSections Failed to flush the section stack');
    }

    /**
     * @covers          FOF30\View\View::flushSectionsIfDoneRendering
     * @dataProvider    ViewDataprovider::getTestFlushSectionsIfDoneRendering
     */
    public function testFlushSectionsIfDoneRendering($test, $check)
    {
        $view = $this->getMock('\\FOF30\\Tests\\Stubs\\View\\ViewStub', array('doneRendering', 'flushSections'), array(static::$container));
        $view->expects($this->any())->method('doneRendering')->willReturn($test['mock']['done']);
        $view->expects($check['flush'] ? $this->once() : $this->never())->method('flushSections');

        $view->flushSectionsIfDoneRendering();
    }

    /**
     * @group           View
     * @group           ViewGetLayoutTemplate
     * @covers          FOF30\View\View::getLayoutTemplate
     */
    public function testGetLayoutTemplate()
    {
        $view = new ViewStub(static::$container);

        ReflectionHelper::setValue($view, 'layoutTemplate', 'foobar');

        $this->assertEquals('foobar', $view->getLayoutTemplate(), 'View::getLayoutTemplate Failed to return the layout template');
    }

    /**
     * @group           View
     * @group           ViewGetContainer
     * @covers          FOF30\View\View::getContainer
     */
    public function testGetContainer()
    {
        $view = new ViewStub(static::$container);

        $newContainer = $view->getContainer();

        $this->assertSame(static::$container, $newContainer, 'View::getContainer Failed to return the passed container');
    }

    /**
     * @covers          FOF30\View\View::getTask
     */
    public function testGetTask()
    {
        $view = new ViewStub(static::$container);

        ReflectionHelper::setValue($view, 'task', 'foobar');

        $this->assertEquals('foobar', $view->getTask(), 'View::getTask Failed to return the task');
    }

    /**
     * @covers          FOF30\View\View::setTask
     */
    public function testSetTask()
    {
        $view = new ViewStub(static::$container);

        $result = $view->setTask('foobar');

        $task = ReflectionHelper::getValue($view, 'task');

        $this->assertEquals('foobar', $task, 'View::setTask Failed to set the task');
        $this->assertInstanceOf('FOF30\View\View', $result, 'View::setTask Should return an instance of itself');
    }

    /**
     * @covers          FOF30\View\View::getDoTask
     */
    public function testGetDoTask()
    {
        $view = new ViewStub(static::$container);

        ReflectionHelper::setValue($view, 'doTask', 'foobar');

        $this->assertEquals('foobar', $view->getDoTask(), 'View::getDoTask Failed to return the doTask');
    }

    /**
     * @covers          FOF30\View\View::setDoTask
     */
    public function testSetDoTask()
    {
        $view = new ViewStub(static::$container);

        $result = $view->setDoTask('foobar');

        $task = ReflectionHelper::getValue($view, 'doTask');

        $this->assertEquals('foobar', $task, 'View::setDoTask Failed to set the task');
        $this->assertInstanceOf('FOF30\View\View', $result, 'View::setDoTask Should return an instance of itself');
    }

    /**
     * @covers          FOF30\View\View::setPreRender
     */
    public function testSetPreRender()
    {
        $view = new ViewStub(static::$container);
        $view->setPreRender(true);

        $value = ReflectionHelper::getValue($view, 'doPreRender');

        $this->assertSame(true, $value, 'View::setPreRender Failed to set the preRender flag');
    }

    /**
     * @covers          FOF30\View\View::setPostRender
     */
    public function testSetPostRender()
    {
        $view = new ViewStub(static::$container);
        $view->setPostRender(true);

        $value = ReflectionHelper::getValue($view, 'doPostRender');

        $this->assertSame(true, $value, 'View::setPostRender Failed to set the postRender flag');
    }

    /**
     * @covers          FOF30\View\View::alias
     */
    public function testAlias()
    {
        $view = new ViewStub(static::$container);

        $view->alias('viewTemplate', 'alias');

        $aliases = ReflectionHelper::getValue($view, 'viewTemplateAliases');

        $this->assertArrayHasKey('alias', $aliases, 'View::alias Failed to set the template alias');
        $this->assertEquals('viewTemplate', $aliases['alias'], 'View::alias Failed to set the template alias');
    }
}
