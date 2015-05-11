<?php

class ToolbarDataprovider
{
    public static function getTestRenderToolbar()
    {
        $data[] = array(
	        //test
            array(
                'mock' => array(
                    'getName'       => '',
                    'getTask'       => '',
                    'getController' => '',
                    'config'        => ''
                ),
                'input'     => array(
                    'tmpl' => 'component'
                ),
                'useConfig' => false,
                'view'      => null,
                'task'      => null
            ),
            //check
            array(
                'case'         => 'Component template, no render_toolbar flag',
                'config'       => '',
                'counter'      => array()
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => '',
                    'getTask'       => '',
                    'getController' => '',
                    'config'        => ''
                ),
                'input'     => array(
                    'tmpl' => '',
                    'render_toolbar' => 0
                ),
                'useConfig' => false,
                'view'      => null,
                'task'      => null
            ),
            array(
                'case'         => 'No template, render_toolbar flag set to off',
                'config'       => '',
                'counter'      => array()
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => '',
                    'getTask'       => '',
                    'getController' => null,
                    'config'        => ''
                ),
                'input'     => array(),
                'useConfig' => false,
                'view'      => null,
                'task'      => null
            ),
            array(
                'case'         => 'No view/task passed, no view/task in the input',
                'config'       => 'views.Cpanels.toolbar.main',
                'counter'      => array()
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => '',
                    'getTask'       => '',
                    'getController' => null,
                    'config'        => ''
                ),
                'input'     => array(),
                'useConfig' => false,
                'view'      => 'view',
                'task'      => null
            ),
            array(
                'case'         => 'View passed, no view/task in the input',
                'config'       => 'views.Views.toolbar.main',
                'counter'      => array('onViews' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => '',
                    'getTask'       => '',
                    'getController' => null,
                    'config'        => ''
                ),
                'input'     => array(),
                'useConfig' => false,
                'view'      => 'foobar',
                'task'      => 'task'
            ),
            array(
                'case'         => 'View/task passed',
                'config'       => 'views.Foobars.toolbar.task',
                'counter'      => array('onTask' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => '',
                    'getTask'       => '',
                    'getController' => null,
                    'config'        => ''
                ),
                'input'     => array(),
                'useConfig' => false,
                'view'      => 'foobar',
                'task'      => 'dummy'
            ),
            array(
                'case'         => 'View/task passed',
                'config'       => 'views.Foobars.toolbar.dummy',
                'counter'      => array('onFoobarsDummy' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => '',
                    'getTask'       => '',
                    'getController' => null,
                    'config'        => ''
                ),
                'input'     => array(
                    'view' => 'foobar',
                    'task' => 'dummy'
                ),
                'useConfig' => false,
                'view'      => null,
                'task'      => null
            ),
            array(
                'case'         => 'No View/task passed, fetching them from the input',
                'config'       => 'views.Foobars.toolbar.dummy',
                'counter'      => array('onFoobarsDummy' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => 'foobar',
                    'getTask'       => 'dummy',
                    'getController' => true,
                    'config'        => ''
                ),
                'input'     => array(),
                'useConfig' => false,
                'view'      => null,
                'task'      => null
            ),
            array(
                'case'         => 'No View/task passed, no view/task in the input, using controller default',
                'config'       => 'views.Foobars.toolbar.dummy',
                'counter'      => array('onFoobarsDummy' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => 'foobar',
                    'getTask'       => 'dummy',
                    'getController' => true,
                    'config'        => array(1)
                ),
                'input'     => array(),
                'useConfig' => true,
                'view'      => null,
                'task'      => null
            ),
            array(
                'case'         => 'Using the configuration file',
                'config'       => 'views.Foobars.toolbar.dummy',
                'counter'      => array('onFoobarsDummy' => 1)
            )
        );

        return $data;
    }

    public static function getTestOnCpanelsBrowse()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => false
                ),
                'submenu' => false,
                'buttons' => false
            ),
            array(
                'case' => 'On backend, no dataview',
                'submenu' => true,
                'methods' => array('title' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => true
                ),
                'submenu' => false,
                'buttons' => false
            ),
            array(
                'case' => 'On backend, is a dataview',
                'submenu' => true,
                'methods' => array('title' => 1, 'preferences' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => false,
                    'dataView' => false
                ),
                'submenu' => false,
                'buttons' => false
            ),
            array(
                'case' => 'No admin, no buttons',
                'submenu' => false,
                'methods' => array()
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => false,
                    'dataView' => false
                ),
                'submenu' => true,
                'buttons' => false
            ),
            array(
                'case' => 'On frontend, with submenu',
                'submenu' => true,
                'methods' => array()
            )
        );

        return $data;
    }

    public static function getTestOnBrowse()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => false
                ),
                'submenu' => false,
                'buttons' => false,
                'perms'   => array(),
                'model'   => ''
            ),
            array(
                'case' => 'On backend, no dataview',
                'submenu' => true,
                'methods' => array('title' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => true
                ),
                'submenu' => false,
                'buttons' => false,
                'perms'   => array(
                    'manage'    => true,
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true,
                ),
                'model'   => ''
            ),
            array(
                'case' => 'On backend, is a dataview',
                'submenu' => true,
                'methods' => array(
                    'title' => 1,
                    'addNew' => 1,
                    'editList' => 1,
                    'divider' => 2,
                    'publishList' => 1,
                    'unpublishList' => 1,
                    'deleteList' => 1
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => true
                ),
                'submenu' => false,
                'buttons' => false,
                'perms'   => array(
                    'manage'    => true,
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true,
                ),
                'model'   => 'checkin'
            ),
            array(
                'case' => 'On backend, is a dataview, model with checkin support',
                'submenu' => true,
                'methods' => array(
                    'title' => 1,
                    'addNew' => 1,
                    'editList' => 1,
                    'divider' => 2,
                    'publishList' => 1,
                    'unpublishList' => 1,
                    'deleteList' => 1,
                    'checkin' => 1
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => true
                ),
                'submenu' => false,
                'buttons' => false,
                'perms'   => array(
                    'manage'    => true,
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true,
                ),
                'model'   => 'bare'
            ),
            array(
                'case' => 'On backend, is a dataview, model no checkin support',
                'submenu' => true,
                'methods' => array(
                    'title' => 1,
                    'addNew' => 1,
                    'editList' => 1,
                    'divider' => 2,
                    'publishList' => 1,
                    'unpublishList' => 1,
                    'deleteList' => 1
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => true
                ),
                'submenu' => false,
                'buttons' => false,
                'perms'   => array(
                    'manage'    => false,
                    'create'    => false,
                    'edit'      => false,
                    'editstate' => false,
                    'delete'    => false,
                ),
                'model'   => ''
            ),
            array(
                'case' => 'On backend, is a dataview, user can\'t do anything',
                'submenu' => true,
                'methods' => array(
                    'title' => 1
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => false,
                    'dataView' => false
                ),
                'submenu' => false,
                'buttons' => false,
                'perms'   => array(),
                'model'   => ''
            ),
            array(
                'case' => 'No admin, no buttons',
                'submenu' => false,
                'methods' => array()
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => false,
                    'dataView' => false
                ),
                'submenu' => true,
                'buttons' => false,
                'perms'   => array(),
                'model'   => ''
            ),
            array(
                'case' => 'On frontend, with submenu',
                'submenu' => true,
                'methods' => array()
            )
        );

        return $data;
    }

    public static function getTestOnRead()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => false
                ),
                'submenu' => false,
                'buttons' => false
            ),
            array(
                'case' => 'On backend, no dataview',
                'submenu' => true,
                'methods' => array('title' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => true
                ),
                'submenu' => false,
                'buttons' => false
            ),
            array(
                'case' => 'On backend, is a dataview',
                'submenu' => true,
                'methods' => array('title' => 1, 'back' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => false,
                    'dataView' => false
                ),
                'submenu' => false,
                'buttons' => false
            ),
            array(
                'case' => 'No admin, no buttons',
                'submenu' => false,
                'methods' => array()
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => false,
                    'dataView' => false
                ),
                'submenu' => true,
                'buttons' => false
            ),
            array(
                'case' => 'On frontend, with submenu',
                'submenu' => true,
                'methods' => array()
            )
        );

        return $data;
    }

    public static function getTestOnAdd()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => false,
                    'dataView' => false
                ),
                'buttons' => false,
                'perms'   => array()
            ),
            array(
                'case' => 'No admin, no buttons',
                'methods' => array()
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => false,
                    'dataView' => false
                ),
                'buttons' => true,
                'perms'   => array(
                    'manage'    => true,
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true,
                ),
            ),
            array(
                'case' => 'No admin, with buttons',
                'methods' => array(
                    'title' => 1
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => false
                ),
                'buttons' => false,
                'perms'   => array(
                    'manage'    => true,
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true,
                ),
            ),
            array(
                'case' => 'On backend, is not a dataview',
                'methods' => array(
                    'title' => 1
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => true
                ),
                'buttons' => false,
                'perms'   => array(
                    'manage'    => true,
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true,
                ),
            ),
            array(
                'case' => 'On backend, is a dataview',
                'methods' => array(
                    'title' => 1,
                    'apply' => 1,
                    'save'  => 1,
                    'custom' => 1,
                    'cancel' => 1
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => true
                ),
                'buttons' => false,
                'perms'   => array(
                    'manage'    => false,
                    'create'    => false,
                    'edit'      => false,
                    'editstate' => false,
                    'editown'   => false,
                    'delete'    => false,
                ),
            ),
            array(
                'case' => 'On backend, is a dataview, user can\'t do anything',
                'methods' => array(
                    'title' => 1,
                    'save'  => 1,
                    'cancel' => 1
                )
            )
        );

        return $data;
    }

    public static function getTestOnEdit()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin' => false
                ),
                'buttons' => false
            ),
            array(
                'onAdd' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin' => true
                ),
                'buttons' => false
            ),
            array(
                'onAdd' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin' => false
                ),
                'buttons' => true
            ),
            array(
                'onAdd' => true
            )
        );

        return $data;
    }

    public static function getTestAppendLink()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'linkbar' => array()
                ),
                'name'   => 'foobar',
                'link'   => null,
                'active' => false,
                'icon'   => null,
                'parent' => ''
            ),
            array(
                'case'    => 'No parent link, no links with the same name',
                'linkbar' => array(
                    'foobar' => array('name' => 'foobar', 'link' => null, 'active' => false, 'icon' => null)
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'linkbar' => array(
                        'foobar' => array('name' => 'foobar', 'link' => null, 'active' => false, 'icon' => null)
                    )
                ),
                'name'   => 'foobar',
                'link'   => 'new_link',
                'active' => false,
                'icon'   => null,
                'parent' => ''
            ),
            array(
                'case'    => 'No parent link, link with the same name',
                'linkbar' => array(
                    'foobar' => array('name' => 'foobar', 'link' => 'new_link', 'active' => false, 'icon' => null)
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'linkbar' => array(
                        'foobar' => array('name' => 'foobar', 'link' => null, 'active' => false, 'icon' => null,
                                          'items' => array('some values'))
                    )
                ),
                'name'   => 'foobar',
                'link'   => 'new_link',
                'active' => false,
                'icon'   => null,
                'parent' => ''
            ),
            array(
                'case'    => 'No parent link, link with the same name and with some children',
                'linkbar' => array(
                    'foobar' => array(
                        'name'   => 'foobar',
                        'link'   => 'new_link',
                        'active' => false,
                        'icon'   => null,
                        'items' => array(
                            array('name' => 'foobar', 'link' => 'new_link', 'active' => false, 'icon' => null),
                            'some values'
                        )
                    )
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'linkbar' => array()
                ),
                'name'   => 'foobar',
                'link'   => null,
                'active' => true,
                'icon'   => null,
                'parent' => 'parent'
            ),
            array(
                'case'    => 'With parent link, no links with the same name',
                'linkbar' => array(
                    'parent' => array(
                        'name'   => 'parent',
                        'link'   => null,
                        'active' => true,
                        'icon'   => null,
                        'items'  => array(
                            array('name' => 'foobar', 'link' => null, 'active' => true, 'icon' => null),
                        ),
                        'dropdown' => 1
                    )
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'linkbar' => array(
                        'parent' => array(
                            'name'   => 'parent',
                            'link'   => null,
                            'active' => true,
                            'icon'   => null,
                            'items'  => array(
                                array('name' => 'foobar', 'link' => null, 'active' => true, 'icon' => null),
                            ),
                            'dropdown' => 1
                        )
                    )
                ),
                'name'   => 'dummy',
                'link'   => null,
                'active' => true,
                'icon'   => null,
                'parent' => 'parent'
            ),
            array(
                'case'    => 'With parent link, parent already exists',
                'linkbar' => array(
                    'parent' => array(
                        'name'   => 'parent',
                        'link'   => null,
                        'active' => true,
                        'icon'   => null,
                        'items'  => array(
                            array('name' => 'foobar', 'link' => null, 'active' => true, 'icon' => null),
                            array('name' => 'dummy', 'link' => null, 'active' => true, 'icon' => null),
                        ),
                        'dropdown' => 1
                    )
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'linkbar' => array(
                        'parent' => array(
                            'name'   => 'parent',
                            'link'   => 'some link',
                            'active' => true,
                            'icon'   => null,
                            'items'  => array(
                                array('name' => 'foobar', 'link' => null, 'active' => true, 'icon' => null),
                            )
                        )
                    )
                ),
                'name'   => 'dummy',
                'link'   => null,
                'active' => true,
                'icon'   => null,
                'parent' => 'parent'
            ),
            array(
                'case'    => 'With parent link, parent already exists with a link and no dropdown',
                'linkbar' => array(
                    'parent' => array(
                        'name'   => 'parent',
                        'link'   => 'some link',
                        'active' => true,
                        'icon'   => null,
                        'items'  => array(
                            array(
                                'name'   => 'parent',
                                'link'   => 'some link',
                                'active' => true,
                                'icon'   => null,
                                'items'  => array(
                                    array('name' => 'foobar', 'link' => null, 'active' => true, 'icon' => null),
                                )
                            ),
                            array('name' => 'dummy', 'link' => null, 'active' => true, 'icon' => null),
                        ),
                        'dropdown' => 1
                    )
                )
            )
        );

        return $data;
    }

    public static function getTestRenderSubmenu()
    {
        $data[] = array(
            array(
                'input' => array(),
                'myviews' => array()
            ),
            array(
                'case' => 'MyView returns empty',
                'links' => array()
            )
        );

        $data[] = array(
            array(
                'input' => array(
                    'view' => 'items'
                ),
                'myviews' => array(
                    'foobars',
                    'items'
                )
            ),
            array(
                'case' => 'We have some views',
                'links' => array(
                    array('Foobars', 'index.php?option=com_fakeapp&view=foobars', null),
                    array('Items', 'index.php?option=com_fakeapp&view=items', true),
                )
            )
        );

        return $data;
    }

    public static function getTestIsDataView()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'getController' => false,
                    'getView' => null,
                    'cache'   => null
                )
            ),
            array(
                'case' => "The disptacher doesn't return a controller",
                'result' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getController' => true,
                    'getView' => null,
                    'cache'   => null
                )
            ),
            array(
                'case' => "The controller doesn't return a view",
                'result' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getController' => true,
                    'getView' => 'FOF30\Tests\Stubs\View\ViewStub',
                    'cache'   => null
                )
            ),
            array(
                'case' => "The view is not a data-aware one",
                'result' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getController' => true,
                    'getView' => 'FOF30\Tests\Stubs\View\DataView\RawStub',
                    'cache'   => null
                )
            ),
            array(
                'case' => "The view is a data-aware one",
                'result' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getController' => false,
                    'getView' => null,
                    'cache'   => 'dummy'
                )
            ),
            array(
                'case' => "Result is cached",
                'result' => 'dummy'
            )
        );

        return $data;
    }
}