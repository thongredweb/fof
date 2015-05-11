<?php

class ControllerFactoryDataprovider
{
    public static function getTestMake()
    {
        $data[] = array(
            array(
                'name' => '',
                'config' => array(),
                'component' => 'com_fakeapp',
                'backend_path' => JPATH_TESTS.'/Stubs/Fakeapp/Admin'
            ),
            array(
                'case' => 'Name not provided',
                'exception' => true,
                'result' => '',
                'autoRouting' => '',
                'csrf' => 0,
                'view' => '',
                'model' => '',
                'priv' => array(),
                'cache' => array(),
                'taskMap' => array(),
            )
        );

        $data[] = array(
            array(
                'name' => 'foobars',
                'config' => array(),
                'component' => 'com_fakeapp',
                'backend_path' => ''
            ),
            array(
                'case' => 'The default datacontroller exists',
                'exception' => false,
                'result' => 'Fakeapp\Site\Controller\DefaultDataController',
                'autoRouting' => 1,
                'csrf' => 2,
                'view' => 'Foobars',
                'model' => 'Foobars',
                'priv' => array(
                    '*editown' => 'core.edit.own',
                    'add' => 'core.create',
                    'apply' => '&getACLForApplySave',
                    'archive' => 'core.edit.state',
                    'cancel' => 'core.edit.state',
                    'copy' => '@add',
                    'edit' => 'core.edit',
                    'loadhistory' => '@edit',
                    'orderup' => 'core.edit.state',
                    'orderdown' => 'core.edit.state',
                    'publish' => 'core.edit.state',
                    'remove' => 'core.delete',
                    'save' => '&getACLForApplySave',
                    'savenew' => 'core.create',
                    'saveorder' => 'core.edit.state',
                    'trash' => 'core.edit.state',
                    'unpublish' => 'core.edit.state',
                ),
                'cache' => array('browse', 'read'),
                'taskMap' => array(
                    'browse' => 'browse',
                    'read' => 'read',
                    'add' => 'add',
                    'edit' => 'edit',
                    'apply' => 'apply',
                    'copy' => 'copy',
                    'save' => 'save',
                    'savenew' => 'savenew',
                    'cancel' => 'cancel',
                    'publish' => 'publish',
                    'unpublish' => 'unpublish',
                    'archive' => 'archive',
                    'trash' => 'trash',
                    'checkin' => 'checkin',
                    'saveorder' => 'saveorder',
                    'orderdown' => 'orderdown',
                    'orderup' => 'orderup',
                    'remove' => 'remove',
                    'getIDsFromRequest' => 'getIDsFromRequest',
                    'loadhistory' => 'loadhistory',
                    'getItemidURLSuffix' => 'getItemidURLSuffix',
                    'display' => 'display',
                    'main' => 'main',
                    '__default' => 'main'
                ),
            )
        );

        $data[] = array(
            array(
                'name' => 'foobars',
                'config' => array(
                    'taskMap' => array(
                        'browse' => 'main'
                    )
                ),
                'component' => 'com_dummyapp',
                'backend_path' => ''
            ),
            array(
                'case' => 'The default datacontroller does not exist, passing the config',
                'exception' => false,
                'result' => 'FOF30\\Controller\\DataController',
                'autoRouting' => 1,
                'csrf' => 2,
                'view' => 'Foobars',
                'model' => 'Foobars',
                'priv' => array(
                    '*editown' => 'core.edit.own',
                    'add' => 'core.create',
                    'apply' => '&getACLForApplySave',
                    'archive' => 'core.edit.state',
                    'cancel' => 'core.edit.state',
                    'copy' => '@add',
                    'edit' => 'core.edit',
                    'loadhistory' => '@edit',
                    'orderup' => 'core.edit.state',
                    'orderdown' => 'core.edit.state',
                    'publish' => 'core.edit.state',
                    'remove' => 'core.delete',
                    'save' => '&getACLForApplySave',
                    'savenew' => 'core.create',
                    'saveorder' => 'core.edit.state',
                    'trash' => 'core.edit.state',
                    'unpublish' => 'core.edit.state',
                ),
                'cache' => array('browse', 'read'),
                'taskMap' => array(
                    'browse' => 'main',
                    'read' => 'read',
                    'add' => 'add',
                    'edit' => 'edit',
                    'apply' => 'apply',
                    'copy' => 'copy',
                    'save' => 'save',
                    'savenew' => 'savenew',
                    'cancel' => 'cancel',
                    'publish' => 'publish',
                    'unpublish' => 'unpublish',
                    'archive' => 'archive',
                    'trash' => 'trash',
                    'checkin' => 'checkin',
                    'saveorder' => 'saveorder',
                    'orderdown' => 'orderdown',
                    'orderup' => 'orderup',
                    'remove' => 'remove',
                    'getIDsFromRequest' => 'getIDsFromRequest',
                    'loadhistory' => 'loadhistory',
                    'getItemidURLSuffix' => 'getItemidURLSuffix',
                    'display' => 'display',
                    'main' => 'main',
                    '__default' => 'main'
                ),
            )
        );

        $data[] = array(
            array(
                'name' => 'foobars',
                'config' => array(),
                'component' => 'com_dummyapp',
                'backend_path' => JPATH_TESTS.'/Stubs/Dummyapp/Admin'
            ),
            array(
                'case' => 'The default datacontroller does not exist, reading fof xml file',
                'exception' => false,
                'result' => 'FOF30\\Controller\\DataController',
                'autoRouting' => 1,
                'csrf' => 2,
                'view' => 'Foobars',
                'model' => 'Foobars',
                'priv' => array(
                    '*editown' => 'core.edit.own',
                    'add' => 'core.create',
                    'apply' => '&getACLForApplySave',
                    'archive' => 'core.edit.state',
                    'cancel' => 'core.edit.state',
                    'copy' => '@add',
                    'edit' => 'core.edit',
                    'loadhistory' => '@edit',
                    'orderup' => 'core.edit.state',
                    'orderdown' => 'core.edit.state',
                    'publish' => 'core.edit.state',
                    'remove' => 'core.delete',
                    'save' => '&getACLForApplySave',
                    'savenew' => 'core.create',
                    'saveorder' => 'core.edit.state',
                    'trash' => 'core.edit.state',
                    'unpublish' => 'core.edit.state',
                ),
                'cache' => array('browse', 'read'),
                'taskMap' => array(
                    'browse' => 'read',
                    'read' => 'read',
                    'add' => 'add',
                    'edit' => 'edit',
                    'apply' => 'apply',
                    'copy' => 'copy',
                    'save' => 'save',
                    'savenew' => 'savenew',
                    'cancel' => 'cancel',
                    'publish' => 'publish',
                    'unpublish' => 'unpublish',
                    'archive' => 'archive',
                    'trash' => 'trash',
                    'checkin' => 'checkin',
                    'saveorder' => 'saveorder',
                    'orderdown' => 'orderdown',
                    'orderup' => 'orderup',
                    'remove' => 'remove',
                    'getIDsFromRequest' => 'getIDsFromRequest',
                    'loadhistory' => 'loadhistory',
                    'getItemidURLSuffix' => 'getItemidURLSuffix',
                    'display' => 'display',
                    'main' => 'main',
                    '__default' => 'main'
                ),
            )
        );

        return $data;
    }
}
