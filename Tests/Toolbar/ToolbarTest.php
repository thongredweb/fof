<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Toolbar;

use FOF30\Input\Input;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;
use FOF30\Tests\Stubs\Model\DataModelStub;
use FOF30\Tests\Stubs\Toolbar\ToolbarStub;
use FOF30\Toolbar\Toolbar;

require_once 'ToolbarDataprovider.php';
require_once JPATH_TESTS.'/Stubs/Joomla/JToolbarHelper.php';

/**
 * @covers  FOF30\Toolbar\Toolbar::<protected>
 * @covers  FOF30\Toolbar\Toolbar::<private>
 */
class ToolbarTest extends FOFTestCase
{
    /**
     * @covers          FOF30\Toolbar\Toolbar::__construct
     */
    public function test__construct()
    {
        $msg = 'Toolbar::__construct %s';
        $config = array(
            'renderFrontendButtons' => true,
            'renderFrontendSubmenu' => true
        );

        $platform = static::$container->platform;
        // Otherwise the toolbar will try to load the js framework
        $platform::$isCli = true;
        $platform::$authorise = function(){
            // To keep it simply, the user has access to everything
            return true;
        };

        $toolbar = new Toolbar(static::$container, $config);

        $perms   = ReflectionHelper::getValue($toolbar, 'perms');
        $buttons = ReflectionHelper::getValue($toolbar, 'renderFrontendButtons');
        $submenu = ReflectionHelper::getValue($toolbar, 'renderFrontendSubmenu');

        $checkPerms = (object) array(
            'manage'    => true,
            'create'    => true,
            'edit'      => true,
            'editstate' => true,
            'delete'    => true,
        );

        $this->assertTrue($buttons, sprintf($msg, 'Failed to set the frontend buttons flag'));
        $this->assertTrue($submenu, sprintf($msg, 'Failed to set the frontend submenu flag'));
        $this->assertEquals($checkPerms, $perms, sprintf($msg, 'Failed to set the permissions'));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::renderToolbar
     * @dataProvider    ToolbarDataprovider::getTestRenderToolbar
     */
    public function testRenderToolbar($test, $check)
    {
        $msg = 'Toolbar::renderToolbar %s - Case: '.$check['case'];

        $controller = $this->getMock('\FOF30\Tests\Stubs\Controller\ControllerStub', array('getName', 'getTask'), array(static::$container));
        $controller->expects($this->any())->method('getName')->willReturn($test['mock']['getName']);
        $controller->expects($this->any())->method('getTask')->willReturn($test['mock']['getTask']);

        $dispacher = $this->getMock('FOF30\Dispatcher\Dispatcher', array('getController'), array(static::$container));
        $dispacher->expects($this->any())->method('getController')
            ->willReturn($test['mock']['getController'] ? $controller : null);

        $appConfig = $this->getMock('FOF30\Configuration\Configuration', array('get'), array(), '', false);
	    $appConfig->expects($this->any())->method('get')->willReturnCallback(function ($something) use ($test, $check) {
		    if (strrpos($something, 'renderFrontendButtons') !== false)
		    {
			    return false;
		    }

		    if ($something == $check['config'])
		    {
			    return $test['mock']['config'];
		    }
	    });

        $container = new TestContainer(array(
            'input'      => new Input($test['input']),
            'dispatcher' => $dispacher,
            'appConfig'  => $appConfig
        ));

        $toolbar = new ToolbarStub($container);

        ReflectionHelper::setValue($toolbar, 'useConfigurationFile', $test['useConfig']);

	    $toolbar->renderToolbar($test['view'], $test['task']);

        $methods = $toolbar->methodCounter;

        $this->assertEquals($check['counter'], $methods, sprintf($msg, 'Failed to correctly invoke "on" methods'));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::onCpanelsBrowse
     * @dataProvider    ToolbarDataprovider::getTestOnCpanelsBrowse
     */
    public function testOnCpanelsBrowse($test, $check)
    {
        $msg = 'Toolbar::onCpanelsBrowse %s - Case: '.$check['case'];

        \JToolbarHelper::resetMethods();

        $platform = static::$container->platform;
        $platform::$isAdmin = $test['mock']['isAdmin'];

        $toolbar = $this->getMock('FOF30\Tests\Stubs\Toolbar\ToolbarStub', array('renderSubmenu', 'isDataView'), array(static::$container));
        $toolbar->expects($check['submenu'] ? $this->once() : $this->never())->method('renderSubmenu');
        $toolbar->expects($this->any())->method('isDataView')->willReturn($test['mock']['dataView']);

        ReflectionHelper::setValue($toolbar, 'renderFrontendSubmenu', $test['submenu']);
        ReflectionHelper::setValue($toolbar, 'renderFrontendButtons', $test['buttons']);

        $toolbar->onCpanelsBrowse();

        $methods = \JToolbarHelper::$methodCounter;

        $this->assertEquals($check['methods'], $methods, sprintf($msg, 'Failed to invoke JToolbar methods'));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::onBrowse
     * @dataProvider    ToolbarDataprovider::getTestOnBrowse
     */
    public function testOnBrowse($test, $check)
    {
        $msg = 'Toolbar::onBrowse %s - Case: '.$check['case'];

        \JToolbarHelper::resetMethods();

        $TestContainer = static::$container;
        $options       = array();

        if($test['model'])
        {
            $options['factory'] = new ClosureHelper(array(
                'model' => function() use ($test, $TestContainer){

                    if($test['model'] == 'checkin'){
                        $config = array(
                            'idFieldName' => 'foftest_foobar_id',
                            'tableName'   => '#__foftest_foobars'
                        );
                    }
                    else{
                        $config = array(
                            'idFieldName' => 'foftest_bare_id',
                            'tableName'   => '#__foftest_bares'
                        );
                    }

                    return new DataModelStub($TestContainer, $config);
                }
            ));
        }

        $container = new TestContainer($options);

        $platform = $container->platform;
        $platform::$isAdmin = $test['mock']['isAdmin'];

        $toolbar = $this->getMock('FOF30\Tests\Stubs\Toolbar\ToolbarStub', array('renderSubmenu', 'isDataView'), array($container));
        $toolbar->expects($check['submenu'] ? $this->once() : $this->never())->method('renderSubmenu');
        $toolbar->expects($this->any())->method('isDataView')->willReturn($test['mock']['dataView']);

        ReflectionHelper::setValue($toolbar, 'renderFrontendSubmenu', $test['submenu']);
        ReflectionHelper::setValue($toolbar, 'renderFrontendButtons', $test['buttons']);
        ReflectionHelper::setValue($toolbar, 'perms', (object) $test['perms']);

        $toolbar->onBrowse();

        $methods = \JToolbarHelper::$methodCounter;

        $this->assertEquals($check['methods'], $methods, sprintf($msg, 'Failed to invoke JToolbar methods'));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::onRead
     * @dataProvider    ToolbarDataprovider::getTestOnRead
     */
    public function testOnRead($test, $check)
    {
        $msg = 'Toolbar::onRead %s - Case: '.$check['case'];

        \JToolbarHelper::resetMethods();

        $platform = static::$container->platform;
        $platform::$isAdmin = $test['mock']['isAdmin'];

        $toolbar = $this->getMock('FOF30\Tests\Stubs\Toolbar\ToolbarStub', array('renderSubmenu', 'isDataView'), array(static::$container));
        $toolbar->expects($check['submenu'] ? $this->once() : $this->never())->method('renderSubmenu');
        $toolbar->expects($this->any())->method('isDataView')->willReturn($test['mock']['dataView']);

        ReflectionHelper::setValue($toolbar, 'renderFrontendSubmenu', $test['submenu']);
        ReflectionHelper::setValue($toolbar, 'renderFrontendButtons', $test['buttons']);

        $toolbar->onRead();

        $methods = \JToolbarHelper::$methodCounter;

        $this->assertEquals($check['methods'], $methods, sprintf($msg, 'Failed to invoke JToolbar methods'));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::onAdd
     * @dataProvider    ToolbarDataprovider::getTestOnAdd
     */
    public function testOnAdd($test, $check)
    {
        $msg = 'Toolbar::onAdd %s - Case: '.$check['case'];

        \JToolbarHelper::resetMethods();

        $platform = static::$container->platform;
        $platform::$isAdmin = $test['mock']['isAdmin'];

        $toolbar = $this->getMock('FOF30\Tests\Stubs\Toolbar\ToolbarStub', array('isDataView'), array(static::$container));
        $toolbar->expects($this->any())->method('isDataView')->willReturn($test['mock']['dataView']);

        ReflectionHelper::setValue($toolbar, 'renderFrontendButtons', $test['buttons']);
        ReflectionHelper::setValue($toolbar, 'perms', (object) $test['perms']);

        $toolbar->onAdd();

        $methods = \JToolbarHelper::$methodCounter;

        $this->assertEquals($check['methods'], $methods, sprintf($msg, 'Failed to invoke JToolbar methods'));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::onEdit
     * @dataProvider    ToolbarDataprovider::getTestOnEdit
     */
    public function testOnEdit($test, $check)
    {
        $platform = static::$container->platform;
        $platform::$isAdmin = $test['mock']['isAdmin'];

        $toolbar = $this->getMock('FOF30\Tests\Stubs\Toolbar\ToolbarStub', array('onAdd'), array(static::$container));
        $toolbar->expects($check['onAdd'] ? $this->once() : $this->never())->method('onAdd');

        ReflectionHelper::setValue($toolbar, 'renderFrontendButtons', $test['buttons']);

        $toolbar->onEdit();
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::clearLinks
     */
    public function testClearLinks()
    {
        $toolbar = new ToolbarStub(static::$container);

        ReflectionHelper::setValue($toolbar, 'linkbar', array(1,2,3));

        $toolbar->clearLinks();

        $this->assertEmpty(ReflectionHelper::getValue($toolbar, 'linkbar'));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::getLinks
     */
    public function testGetLinks()
    {
        $toolbar = new ToolbarStub(static::$container);
        $links   = array(1,2,3);

        ReflectionHelper::setValue($toolbar, 'linkbar', $links);

        $result = $toolbar->getLinks();

        $this->assertEquals($links, $result);
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::appendLink
     * @dataProvider    ToolbarDataprovider::getTestAppendLink
     */
    public function testAppendLink($test, $check)
    {
        $msg = 'Toolbar::appendLink %s - Case: '.$check['case'];

        $toolbar = new ToolbarStub(static::$container);

        ReflectionHelper::setValue($toolbar, 'linkbar', $test['mock']['linkbar']);

        $toolbar->appendLink($test['name'], $test['link'], $test['active'], $test['icon'], $test['parent']);

        $linkbar = ReflectionHelper::getValue($toolbar, 'linkbar');

        $this->assertEquals($check['linkbar'], $linkbar, sprintf($msg, 'Failed to correctly build the links'));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::prefixLink
     */
    public function testPrefixLink()
    {
        $toolbar = new ToolbarStub(static::$container);

        ReflectionHelper::setValue($toolbar, 'linkbar', array('some', 'links'));

        $link = array('name' => 'foobar', 'link' => null, 'active' => false, 'icon' => '');

        $toolbar->prefixLink('foobar');

        $linkbar = ReflectionHelper::getValue($toolbar, 'linkbar');

        $this->assertEquals($link, array_shift($linkbar));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::renderSubmenu
     * @dataProvider    ToolbarDataprovider::getTestRenderSubmenu
     */
    public function testRenderSubmenu($test, $check)
    {
        $msg     = 'Toolbar::renderSubmenu %s - Case: '.$check['case'];
        $checker = array();

        $container = new TestContainer(array(
            'input' => new Input($test['input'])
        ));

        $toolbar = $this->getMock('FOF30\Tests\Stubs\Toolbar\ToolbarStub', array('getMyViews', 'appendLink'), array($container));
        $toolbar->expects($this->any())->method('getMyViews')->willReturn($test['myviews']);
        $toolbar->expects($this->any())->method('appendLink')
            ->willReturnCallback(function($name, $link, $active) use(&$checker){
                $checker[] = array($name, $link, $active);
        });

        $toolbar->renderSubmenu();

        $this->assertEquals($check['links'], $checker, sprintf($msg, 'Failed to create the links'));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::getRenderFrontendButtons
     */
    public function testGetRenderFrontendButtons()
    {
        $toolbar = new ToolbarStub(static::$container);

        ReflectionHelper::setValue($toolbar, 'renderFrontendButtons', true);

        $this->assertTrue($toolbar->getRenderFrontendButtons());
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::setRenderFrontendButtons
     */
    public function testSetRenderFrontendButtons()
    {
        $toolbar = new ToolbarStub(static::$container);

        $toolbar->setRenderFrontendButtons(true);

        $this->assertTrue(ReflectionHelper::getValue($toolbar, 'renderFrontendButtons'));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::getRenderFrontendSubmenu
     */
    public function testGetRenderFrontendSubmenu()
    {
        $toolbar = new ToolbarStub(static::$container);

        ReflectionHelper::setValue($toolbar, 'renderFrontendSubmenu', true);

        $this->assertTrue($toolbar->getRenderFrontendSubmenu());
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::setRenderFrontendSubmenu
     */
    public function testSetRenderFrontendSubmenu()
    {
        $toolbar = new ToolbarStub(static::$container);

        $toolbar->setRenderFrontendSubmenu(true);

        $this->assertTrue(ReflectionHelper::getValue($toolbar, 'renderFrontendSubmenu'));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::isDataView
     * @dataProvider    ToolbarDataprovider::getTestIsDataView
     */
    public function testIsDataView($test, $check)
    {
        $msg = 'Toolbar::isDataView %s - Case: '.$check['case'];

        $platform = static::$container->platform;
        $platform::$template = 'fake_test_template';
        $platform::$uriBase  = 'www.example.com';

        $TestContainer = static::$container;

        $controller = $this->getMock('\FOF30\Tests\Stubs\Controller\ControllerStub', array('getView'), array(static::$container));
        $controller->expects($this->any())->method('getView')->willReturnCallback(function() use ($test, $TestContainer){
            if(!is_null($test['mock']['getView'])){
                return new $test['mock']['getView']($TestContainer);
            }

            return null;
        });

        $dispacher = $this->getMock('FOF30\Dispatcher\Dispatcher', array('getController'), array(static::$container));
        $dispacher->expects($this->any())->method('getController')
            ->willReturn($test['mock']['getController'] ? $controller : null);

        $container = new TestContainer(array(
            'dispatcher' => $dispacher
        ));

        $toolbar = new ToolbarStub($container);

        ReflectionHelper::setValue($toolbar, 'isDataView', $test['mock']['cache']);

        $result = $toolbar->isDataView();

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }
}
