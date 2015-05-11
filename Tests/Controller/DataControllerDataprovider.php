<?php

class DataControllerDataprovider
{
    public static function getTest__construct()
    {
        $data[] = array(
            array(
                'model' => '',
                'view'  => '',
                'cache' => '',
                'privileges' => ''
            ),
            array(
                'case'  => 'Model and view not passed',
                'model' => 'dummycontrollers',
                'view'  => 'dummycontrollers',
                'cache' => array('browse', 'read'),
                'privileges' => array(
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
                )
            )
        );

        $data[] = array(
            array(
                'model' => 'custom',
                'view'  => 'foobar',
                'cache' => 'foo, bar',
                'privileges' => array('foo' => 'core.foo.bar')
            ),
            array(
                'case'  => 'Model, cacheable task, privileges and view passed',
                'model' => 'custom',
                'view'  => 'foobar',
                'cache' => array('foo', 'bar'),
                'privileges' => array(
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
                    'foo' => 'core.foo.bar'
                )
            )
        );

        return $data;
    }

    public static function getTestExecute()
    {
        // Default task, the controller should detect the correct one
        $data[] = array(
            array(
                'task' => 'default'
            ),
            array(
                'getCrud' => true,
            )
        );

        // Task passed
        $data[] = array(
            array(
                'task' => 'read'
            ),
            array(
                'getCrud' => false,
            )
        );

        return $data;
    }

    public static function getTestGetView()
    {
        $data[] = array(
            array(
                'name' => 'foobar',
                'config' => array(),
                'constructConfig' => array(),
                'mock' => array(
                    'view' => null,
                    'viewName' => null,
                    'instances' => array(),
                    'format'    => null,
                    'getView'   => 'mocked',
                    'hasForm'   => false
                )
            ),
            array(
                'case'   => 'Creating HTML view, name passed, view not cached, internal reference are empty',
                'result' => 'mocked',
                'viewName' => 'foobar',
                'type'     => 'html',
                'config' => array()
            )
        );

        $data[] = array(
            array(
                'name' => 'foobar',
                'config' => array(),
                'constructConfig' => array(),
                'mock' => array(
                    'view' => null,
                    'viewName' => null,
                    'instances' => array(),
                    'format'    => null,
                    'getView'   => 'mocked',
                    'hasForm'   => true
                )
            ),
            array(
                'case'   => 'The controller has a form',
                'result' => 'mocked',
                'viewName' => 'foobar',
                'type'     => 'form',
                'config' => array()
            )
        );

        $data[] = array(
            array(
                'name' => 'foobar',
                'config' => array(),
                'constructConfig' => array(),
                'mock' => array(
                    'view' => null,
                    'viewName' => null,
                    'instances' => array(),
                    'format'    => 'html',
                    'getView'   => 'mocked',
                    'hasForm'   => false
                )
            ),
            array(
                'case'   => 'Creating HTML view, name passed, view not cached, internal reference are empty',
                'result' => 'mocked',
                'viewName' => 'foobar',
                'type'     => 'html',
                'config' => array()
            )
        );

        $data[] = array(
            array(
                'name' => null,
                'config' => array(),
                'constructConfig' => array(),
                'mock' => array(
                    'view' => null,
                    'viewName' => 'foobar',
                    'instances' => array(),
                    'format'    => null,
                    'getView'   => 'mocked',
                    'hasForm'   => false
                )
            ),
            array(
                'case'   => 'Creating HTML view, name not passed, fetched from the viewName property',
                'result' => 'mocked',
                'viewName' => 'foobar',
                'type'     => 'html',
                'config' => array()
            )
        );

        $data[] = array(
            array(
                'name' => null,
                'config' => array(),
                'constructConfig' => array(),
                'mock' => array(
                    'view' => 'foobar',
                    'viewName' => null,
                    'instances' => array(),
                    'format'    => null,
                    'getView'   => 'mocked',
                    'hasForm'   => false
                )
            ),
            array(
                'case'   => 'Creating HTML view, name not passed, fetched from the view property',
                'result' => 'mocked',
                'viewName' => 'foobar',
                'type'     => 'html',
                'config' => array()
            )
        );

        $data[] = array(
            array(
                'name' => 'foobar',
                'config' => array(),
                'constructConfig' => array(),
                'mock' => array(
                    'view' => null,
                    'viewName' => null,
                    'instances' => array(),
                    'format'    => 'json',
                    'getView'   => 'mocked',
                    'hasForm'   => false
                )
            ),
            array(
                'case'   => 'Creating JSON view, name passed, view not cached, internal reference are empty',
                'result' => 'mocked',
                'viewName' => 'foobar',
                'type'     => 'json',
                'config' => array()
            )
        );

        $data[] = array(
            array(
                'name' => 'foobar',
                'config' => array(),
                'constructConfig' => array(),
                'mock' => array(
                    'view' => null,
                    'viewName' => null,
                    'instances' => array(),
                    'format'    => 'json',
                    'getView'   => 'mocked',
                    'hasForm'   => true
                )
            ),
            array(
                'case'   => 'The controller has a form, but this is a JSON view',
                'result' => 'mocked',
                'viewName' => 'foobar',
                'type'     => 'json',
                'config' => array()
            )
        );

        $data[] = array(
            array(
                'name' => 'foobar',
                'config' => array(),
                'constructConfig' => array(),
                'mock' => array(
                    'view' => null,
                    'viewName' => null,
                    'instances' => array('foobar' => 'cached'),
                    'format'    => null,
                    'getView'   => 'mocked',
                    'hasForm'   => false
                )
            ),
            array(
                'case'   => 'Creating HTML view, fetched from the cache',
                'result' => 'cached',
                'viewName' => '',
                'type'     => '',
                'config' => array()
            )
        );

        $data[] = array(
            array(
                'name' => 'foobar',
                'config' => array('foo' => 'bar'),
                'constructConfig' => array(),
                'mock' => array(
                    'view' => null,
                    'viewName' => null,
                    'instances' => array(),
                    'format'    => null,
                    'getView'   => 'mocked',
                    'hasForm'   => false
                )
            ),
            array(
                'case'   => 'Creating HTML view, name and config passed, view not cached, internal reference are empty',
                'result' => 'mocked',
                'viewName' => 'foobar',
                'type'     => 'html',
                'config' => array('foo' => 'bar')
            )
        );

        $data[] = array(
            array(
                'name' => 'foobar',
                'config' => array(),
                'constructConfig' => array(
                    'viewConfig' => array(
                        'foo' => 'bar'
                    )
                ),
                'mock' => array(
                    'view' => null,
                    'viewName' => null,
                    'instances' => array(),
                    'format'    => null,
                    'getView'   => 'mocked',
                    'hasForm'   => false
                )
            ),
            array(
                'case'   => 'Creating HTML view, name and config passed (in constructor), view not cached, internal reference are empty',
                'result' => 'mocked',
                'viewName' => 'foobar',
                'type'     => 'html',
                'config' => array('foo' => 'bar')
            )
        );

        return $data;
    }

    public static function getTestBrowse()
    {
        $data[]= array(
            array(
                'mock' => array(
                    'getForm' => false,
                    'cache' => array('browse', 'read'),
                    'layout' => null,
                    'input' => array(
                        'savestate' => 0
                    )
                )
            ),
            array(
                'case' => "Don't want any state saving",
                'display' => true,
                'savestate' => 0,
                'formName' => 'form.default',
                'hasForm' => false
            )
        );

        $data[]= array(
            array(
                'mock' => array(
                    'getForm' => true,
                    'cache' => array('read'),
                    'layout' => 'foobar',
                    'input' => array(
                        'savestate' => -999
                    )
                )
            ),
            array(
                'case' => "State saved, with layout and form, task not in the cache",
                'display' => false,
                'savestate' => true,
                'formName' => 'form.foobar',
                'hasForm' => true
            )
        );

        $data[]= array(
            array(
                'mock' => array(
                    'getForm' => false,
                    'cache' => array('browse', 'read'),
                    'layout' => null,
                    'input' => array()
                )
            ),
            array(
                'case' => "Variable not set, by default I save the stater",
                'display' => true,
                'savestate' => true,
                'formName' => 'form.default',
                'hasForm' => false
            )
        );

        return $data;
    }

    public static function getTestRead()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'getId'  => array(3, null),
                    'ids'    => 0,
                    'layout' => '',
                    'getForm' => false,
                    'cache' => array('browse', 'read')
                )
            ),
            array(
                'case'         => 'Getting the id from the model, using the default layout',
                'getIdFromReq' => 0,
                'display'      => true,
                'exception'    => false,
                'layout'       => 'item',
                'hasForm'      => false,
                'setForm'      => 'form.item'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getId'  => array(3, null),
                    'ids'    => 0,
                    'layout' => '',
                    'getForm' => false,
                    'cache' => array('browse')
                )
            ),
            array(
                'case'         => 'Getting the id from the model, using the default layout, task is not cacheable',
                'getIdFromReq' => 0,
                'display'      => false,
                'exception'    => false,
                'layout'       => 'item',
                'hasForm'      => false,
                'setForm'      => 'form.item'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getId'  => array(3, null),
                    'ids'    => 0,
                    'layout' => 'custom',
                    'getForm' => true,
                    'cache' => array('browse', 'read')
                )
            ),
            array(
                'case'         => 'Getting the id from the model, using a custom layout, with form',
                'getIdFromReq' => 0,
                'display'      => true,
                'exception'    => false,
                'layout'       => 'custom',
                'hasForm'      => true,
                'setForm'      => 'form.custom'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getId'  => array(false, 3),
                    'ids'    => array(3),
                    'layout' => '',
                    'getForm' => false,
                    'cache' => array('browse', 'read')
                )
            ),
            array(
                'case'         => 'Getting the id from the request, using the default layout',
                'getIdFromReq' => 1,
                'display'      => true,
                'exception'    => false,
                'layout'       => 'item',
                'hasForm'      => false,
                'setForm'      => 'form.item'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getId'  => array(false, 3),
                    'ids'    => array(),
                    'layout' => '',
                    'getForm' => false,
                    'cache' => array('browse', 'read')
                )
            ),
            array(
                'case'         => 'Getting the id from the request, something wrong happens - part 1',
                'getIdFromReq' => 1,
                'display'      => true,
                'exception'    => true,
                'layout'       => 'item',
                'hasForm'      => false,
                'setForm'      => 'form.item'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getId'  => array(false, false),
                    'ids'    => array(3),
                    'layout' => '',
                    'getForm' => false,
                    'cache' => array('browse', 'read')
                )
            ),
            array(
                'case'         => 'Getting the id from the request, something wrong happens - part 2',
                'getIdFromReq' => 1,
                'display'      => true,
                'exception'    => true,
                'layout'       => 'item',
                'hasForm'      => false,
                'setForm'      => 'form.item'
            )
        );

        return $data;
    }

    public static function getTestAdd()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'session' => '',
                    'getForm' => false,
                    'layout'  => '',
                    'cache'   => array('browse', 'read')
                )
            ),
            array(
                'case'     => 'No data in the session, no layout, no cache, no form',
                'display'  => false,
                'bind'     => '',
                'formName' => 'form.form',
                'layout'   => 'form',
                'hasForm'  => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'session' => array('foo' => 'bar'),
                    'getForm' => true,
                    'layout'  => 'test',
                    'cache'   => array('browse', 'read', 'add')
                )
            ),
            array(
                'case'     => 'Data in the session, with layout, cache and form',
                'display'  => true,
                'bind'     => array('foo' => 'bar'),
                'formName' => 'form.test',
                'layout'   => 'test',
                'hasForm'  => true
            )
        );

        return $data;
    }

    public static function getTestEdit()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'session' => null,
                    'getId' => true ,
                    'lock'  => true,
                    'layout'=> '',
                    'getForm' => false,
                    'cache'   => array('browse', 'read')
                )
            ),
            array(
                'case'       => 'Getting the id from the model, no layout, no data in the session, everything works fine',
                'bind'       => false,
                'getFromReq' => false,
                'redirect'   => false,
                'url'        => '',
                'msg'        => '',
                'display'    => false,
                'layout'     => 'form',
                'formName'   => 'form.form',
                'hasForm'    => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'session' => null,
                    'getId' => true ,
                    'lock'  => true,
                    'layout'=> '',
                    'getForm' => false,
                    'cache'   => array('browse', 'read', 'edit')
                )
            ),
            array(
                'case'       => 'Task is cached',
                'bind'       => false,
                'getFromReq' => false,
                'redirect'   => false,
                'url'        => '',
                'msg'        => '',
                'display'    => true,
                'layout'     => 'form',
                'formName'   => 'form.form',
                'hasForm'    => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'session' => array('foo' => 'bar'),
                    'getId' => false ,
                    'lock'  => true,
                    'layout'=> 'custom',
                    'getForm' => false,
                    'cache'   => array('browse', 'read')
                )
            ),
            array(
                'case'       => 'Getting the id from the request, with layout, fetch data from the session, everything works fine',
                'bind'       => array('foo' => 'bar'),
                'getFromReq' => true,
                'redirect'   => false,
                'url'        => '',
                'msg'        => '',
                'display'    => false,
                'layout'     => 'custom',
                'formName'   => 'form.custom',
                'hasForm'    => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'session' => array('foo' => 'bar'),
                    'getId' => false ,
                    'lock'  => true,
                    'layout'=> 'custom',
                    'getForm' => true,
                    'cache'   => array('browse', 'read')
                )
            ),
            array(
                'case'       => 'We have a form',
                'bind'       => array('foo' => 'bar'),
                'getFromReq' => true,
                'redirect'   => false,
                'url'        => '',
                'msg'        => '',
                'display'    => false,
                'layout'     => 'custom',
                'formName'   => 'form.custom',
                'hasForm'    => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'session' => null,
                    'getId' => true ,
                    'lock'  => 'throw',
                    'layout'=> '',
                    'getForm' => false,
                    'cache'   => array('browse', 'read')
                )
            ),
            array(
                'case'       => 'Lock throws an error, no custom url',
                'bind'       => false,
                'getFromReq' => false,
                'redirect'   => true,
                'url'        => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'        => 'Exception thrown while locking',
                'display'    => false,
                'layout'     => '',
                'formName'   => '',
                'hasForm'    => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => 'http://www.example.com/index.php?view=custom',
                    'session' => null,
                    'getId' => true ,
                    'lock'  => 'throw',
                    'layout'=> '',
                    'getForm' => false,
                    'cache'   => array('browse', 'read')
                )
            ),
            array(
                'case'       => 'Lock throws an error, custom url set',
                'bind'       => false,
                'getFromReq' => false,
                'redirect'   => true,
                'url'        => 'http://www.example.com/index.php?view=custom',
                'msg'        => 'Exception thrown while locking',
                'display'    => false,
                'layout'     => '',
                'formName'   => '',
                'hasForm'    => false
            )
        );

        return $data;
    }

    public static function getTestApply()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'id'        => 3,
                    'returnurl' => '',
                    'apply'     => true
                )
            ),
            array(
                'redirect' => true,
                'url'      => 'index.php?option=com_fakeapp&view=dummycontroller&task=edit&id=3',
                'msg'      => 'COM_FAKEAPP_LBL_DUMMYCONTROLLER_SAVED'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id'        => 3,
                    'returnurl' => 'http://www.example.com/index.php?view=custom',
                    'apply'     => true
                )
            ),
            array(
                'redirect' => true,
                'url'      => 'http://www.example.com/index.php?view=custom',
                'msg'      => 'COM_FAKEAPP_LBL_DUMMYCONTROLLER_SAVED'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id'        => 3,
                    'returnurl' => '',
                    'apply'     => false
                )
            ),
            array(
                'redirect' => false,
                'url'      => '',
                'msg'      => ''
            )
        );

        return $data;
    }

    public static function getTestCopy()
    {
        // Everything works fine, no custom redirect set
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'find'      => array(true),
                    'copy'      => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => 'COM_FAKEAPP_LBL_DUMMYCONTROLLER_COPIED',
                'type' => null
            )
        );

        // Everything works fine, custom redirect set
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => 'http://www.example.com/index.php?view=custom',
                    'find'      => array(true),
                    'copy'      => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'http://www.example.com/index.php?view=custom',
                'msg'  => 'COM_FAKEAPP_LBL_DUMMYCONTROLLER_COPIED',
                'type' => null
            )
        );

        // Copy throws an error
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'find'      => array(true),
                    'copy'      => array('throw'),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => 'Exception in copy',
                'type' => 'error'
            )
        );

        // Find throws an error
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'find'      => array('throw'),
                    'copy'      => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => 'Exception in find',
                'type' => 'error'
            )
        );

        return $data;
    }

    public static function getTestSave()
    {
        // Everything is fine, no custom redirect set
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'apply'     => true
                )
            ),
            array(
                'redirect' => true,
                'url' => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg' => 'COM_FAKEAPP_LBL_DUMMYCONTROLLER_SAVED'
            )
        );

        // Everything is fine, custom redirect set
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => 'http://www.example.com/index.php?view=custom',
                    'apply'     => true
                )
            ),
            array(
                'redirect' => true,
                'url' => 'http://www.example.com/index.php?view=custom',
                'msg' => 'COM_FAKEAPP_LBL_DUMMYCONTROLLER_SAVED'
            )
        );

        // An error occurs, no custom redirect set
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'apply'     => false
                )
            ),
            array(
                'redirect' => false,
                'url' => '',
                'msg' => ''
            )
        );

        return $data;
    }

    public static function getTestSavenew()
    {
        // Everything is fine, no custom redirect set
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'apply'     => true
                )
            ),
            array(
                'redirect' => true,
                'url' => 'index.php?option=com_fakeapp&view=dummycontroller&task=add',
                'msg' => 'COM_FAKEAPP_LBL_DUMMYCONTROLLER_SAVED'
            )
        );

        // Everything is fine, custom redirect set
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => 'http://www.example.com/index.php?view=custom',
                    'apply'     => true
                )
            ),
            array(
                'redirect' => true,
                'url' => 'http://www.example.com/index.php?view=custom',
                'msg' => 'COM_FAKEAPP_LBL_DUMMYCONTROLLER_SAVED'
            )
        );

        // An error occurs, no custom redirect set
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'apply'     => false
                )
            ),
            array(
                'redirect' => false,
                'url' => '',
                'msg' => ''
            )
        );

        return $data;
    }

    public static function getTestCancel()
    {
        // Getting the id from the model, no custom redirect set
        $data[] = array(
            array(
                'mock' => array(
                    'session'   => array('foo' => 'bar'),
                    'returnurl' => '',
                    'getId'     => 3,
                    'ids'       => array()
                )
            ),
            array(
                'getFromReq' => false,
                'url'        => 'index.php?option=com_fakeapp&view=dummycontrollers'
            )
        );

        // Getting the id from request, no custom redirect set
        $data[] = array(
            array(
                'mock' => array(
                    'session'   => null,
                    'returnurl' => '',
                    'getId'     => null,
                    'ids'       => array(3)
                )
            ),
            array(
                'getFromReq' => true,
                'url'        => 'index.php?option=com_fakeapp&view=dummycontrollers'
            )
        );

        // Getting the id from the model, custom redirect set
        $data[] = array(
            array(
                'mock' => array(
                    'session'   => null,
                    'returnurl' => 'http://www.example.com/index.php?view=custom',
                    'getId'     => 3,
                    'ids'       => array()
                )
            ),
            array(
                'getFromReq' => false,
                'url'        => 'http://www.example.com/index.php?view=custom'
            )
        );

        return $data;
    }

    public static function getTestPublish()
    {
        // Everything works fine, no custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'publish'   => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => null,
                'type' => null
            )
        );

        // Everything works fine, custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => 'http://www.example.com/index.php?view=custom',
                    'publish'   => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'http://www.example.com/index.php?view=custom',
                'msg'  => null,
                'type' => null
            )
        );

        // Publish throws an error, no custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'publish'   => array('throw'),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => 'Exception in publish',
                'type' => 'error'
            )
        );

        return $data;
    }

    public static function getTestUnpublish()
    {
        // Everything works fine, no custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'unpublish'   => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => null,
                'type' => null
            )
        );

        // Everything works fine, custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => 'http://www.example.com/index.php?view=custom',
                    'unpublish'   => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'http://www.example.com/index.php?view=custom',
                'msg'  => null,
                'type' => null
            )
        );

        // Unpublish throws an error, no custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'unpublish'   => array('throw'),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => 'Exception in unpublish',
                'type' => 'error'
            )
        );

        return $data;
    }

    public static function getTestArchive()
    {
        // Everything works fine, no custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'archive'   => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => null,
                'type' => null
            )
        );

        // Everything works fine, custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => 'http://www.example.com/index.php?view=custom',
                    'archive'   => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'http://www.example.com/index.php?view=custom',
                'msg'  => null,
                'type' => null
            )
        );

        // Archive throws an error, no custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'archive'   => array('throw'),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => 'Exception in archive',
                'type' => 'error'
            )
        );

        return $data;
    }

    public static function getTestTrash()
    {
        // Everything works fine, no custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'trash'     => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => null,
                'type' => null
            )
        );

        // Everything works fine, custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => 'http://www.example.com/index.php?view=custom',
                    'trash'     => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'http://www.example.com/index.php?view=custom',
                'msg'  => null,
                'type' => null
            )
        );

        // Trash throws an error, no custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'trash'     => array('throw'),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => 'Exception in trash',
                'type' => 'error'
            )
        );

        return $data;
    }

    public static function getTestCheckin()
    {
        // Everything works fine, no custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'checkin'   => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => null,
                'type' => null
            )
        );

        // Everything works fine, custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => 'http://www.example.com/index.php?view=custom',
                    'checkin'   => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'http://www.example.com/index.php?view=custom',
                'msg'  => null,
                'type' => null
            )
        );

        // Trash throws an error, no custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'checkin'   => array('throw'),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => 'Exception in checkin',
                'type' => 'error'
            )
        );

        return $data;
    }

    public static function getTestSaveorder()
    {
        $data[] = array(
            array(
                'ordering'  => array(3,1,2,4),
                'returnurl' => '',
                'id'        => 'foftest_foobar_id',
                'table'     => '#__foftest_foobars',
                'mock' => array(
                    'ids' => array(1,2,3,4)
                )
            ),
            array(
                'case' => 'No custom redirect set',
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => null,
                'type' => null,
                'rows' => array(2,3,1,4,5)
            )
        );

        $data[] = array(
            array(
                'ordering'  => array(3,1,2,4),
                'returnurl' => 'http://www.example.com/index.php?view=custom',
                'id'        => 'foftest_foobar_id',
                'table'     => '#__foftest_foobars',
                'mock' => array(
                    'ids' => array(1,2,3,4)
                )
            ),
            array(
                'case' => 'Custom redirect set',
                'url'  => 'http://www.example.com/index.php?view=custom',
                'msg'  => null,
                'type' => null,
                'rows' => array(2,3,1,4,5)
            )
        );

        $data[] = array(
            array(
                'ordering'  => array(3,1,2,4),
                'returnurl' => '',
                'id'        => 'foftest_bare_id',
                'table'     => '#__foftest_bares',
                'mock' => array(
                    'ids' => array(1,2,3,4)
                )
            ),
            array(
                'case' => 'Table with no ordering support',
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => '#__foftest_bares does not support ordering.',
                'type' => 'error',
                'rows' => array(1,2,3,4,5)
            )
        );

        return $data;
    }

    public static function getTestOrderdown()
    {
        // Everything works fine, no custom redirect set, getting the id from the model
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'move'      => array(true),
                    'getId'     => 3,
                    'ids'       => array()
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'getFromReq' => false,
                'msg'  => null,
                'type' => null
            )
        );

        // Everything works fine, no custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'move'      => array(true),
                    'getId'     => null,
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'getFromReq' => true,
                'msg'  => null,
                'type' => null
            )
        );

        // Everything works fine, custom redirect set, getting the id from the model
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => 'http://www.example.com/index.php?view=custom',
                    'move'      => array(true),
                    'getId'     => 3,
                    'ids'       => array()
                )
            ),
            array(
                'url'  => 'http://www.example.com/index.php?view=custom',
                'getFromReq' => false,
                'msg'  => null,
                'type' => null
            )
        );

        // Move throws an error, no custom redirect set, getting the id from the model
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'move'      => array('throw'),
                    'getId'     => 3,
                    'ids'       => array()
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'getFromReq' => false,
                'msg'  => 'Exception in move',
                'type' => 'error'
            )
        );

        return $data;
    }

    public static function getTestOrderup()
    {
        // Everything works fine, no custom redirect set, getting the id from the model
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'move'      => array(true),
                    'getId'     => 3,
                    'ids'       => array()
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'getFromReq' => false,
                'msg'  => null,
                'type' => null
            )
        );

        // Everything works fine, no custom redirect set, getting the id from the request
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'move'      => array(true),
                    'getId'     => null,
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'getFromReq' => true,
                'msg'  => null,
                'type' => null
            )
        );

        // Everything works fine, custom redirect set, getting the id from the model
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => 'http://www.example.com/index.php?view=custom',
                    'move'      => array(true),
                    'getId'     => 3,
                    'ids'       => array()
                )
            ),
            array(
                'url'  => 'http://www.example.com/index.php?view=custom',
                'getFromReq' => false,
                'msg'  => null,
                'type' => null
            )
        );

        // Move throws an error, no custom redirect set, getting the id from the model
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'move'      => array('throw'),
                    'getId'     => 3,
                    'ids'       => array()
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'getFromReq' => false,
                'msg'  => 'Exception in move',
                'type' => 'error'
            )
        );

        return $data;
    }

    public static function getTestRemove()
    {
        // Everything works fine, no custom redirect set
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'find'      => array(true),
                    'delete'    => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => 'COM_FAKEAPP_LBL_DUMMYCONTROLLER_DELETED',
                'type' => null
            )
        );

        // Everything works fine, custom redirect set
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => 'http://www.example.com/index.php?view=custom',
                    'find'      => array(true),
                    'delete'    => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'http://www.example.com/index.php?view=custom',
                'msg'  => 'COM_FAKEAPP_LBL_DUMMYCONTROLLER_DELETED',
                'type' => null
            )
        );

        // Delete throws an error
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'find'      => array(true),
                    'delete'    => array('throw'),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => 'Exception in delete',
                'type' => 'error'
            )
        );

        // Find throws an error
        $data[] = array(
            array(
                'mock' => array(
                    'returnurl' => '',
                    'find'      => array('throw'),
                    'delete'    => array(true),
                    'ids'       => array(3)
                )
            ),
            array(
                'url'  => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'  => 'Exception in find',
                'type' => 'error'
            )
        );

        return $data;
    }

    public static function getTestGetModel()
    {
        $data[] = array(
            array(
                'name' => 'datafoobars',
                'mock' => array(
                    'modelname' => null
                )
            ),
            array(
                'exception' => false
            )
        );

        $data[] = array(
            array(
                'name' => null,
                'mock' => array(
                    'modelname' => 'datafoobars'
                )
            ),
            array(
                'exception' => false
            )
        );

        $data[] = array(
            array(
                'name' => null,
                'mock' => array(
                    'modelname' => 'foobar'
                )
            ),
            array(
                'exception' => true
            )
        );

        return $data;
    }

    public static function getTestGetIDsFromRequest()
    {
        $data[] = array(
            array(
                'load' => false,
                'mock' => array(
                    'cid' => array(),
                    'id'  => 0,
                    'kid' => 0
                )
            ),
            array(
                'case'   => 'Everything is empty, not asked for loading',
                'result' => array(),
                'load'   => false,
                'loadid' => null
            )
        );

        $data[] = array(
            array(
                'load' => true,
                'mock' => array(
                    'cid' => array(),
                    'id'  => 0,
                    'kid' => 0
                )
            ),
            array(
                'case'   => 'Everything is empty, asked for loading',
                'result' => array(),
                'load'   => false,
                'loadid' => null
            )
        );

        $data[] = array(
            array(
                'load' => false,
                'mock' => array(
                    'cid' => array(3,4,5),
                    'id'  => 0,
                    'kid' => 0
                )
            ),
            array(
                'case'   => 'Passed an array of id (cid), not asked for loading',
                'result' => array(3,4,5),
                'load'   => false,
                'loadid' => null
            )
        );

        $data[] = array(
            array(
                'load' => true,
                'mock' => array(
                    'cid' => array(3,4,5),
                    'id'  => 0,
                    'kid' => 0
                )
            ),
            array(
                'case'   => 'Passed an array of id (cid), asked for loading',
                'result' => array(3,4,5),
                'load'   => true,
                'loadid' => array('id' => 3)
            )
        );

        $data[] = array(
            array(
                'load' => false,
                'mock' => array(
                    'cid' => array(),
                    'id'  => 3,
                    'kid' => 0
                )
            ),
            array(
                'case'   => 'Passed a single id (id) , not asked for loading',
                'result' => array(3),
                'load'   => false,
                'loadid' => null
            )
        );

        $data[] = array(
            array(
                'load' => true,
                'mock' => array(
                    'cid' => array(),
                    'id'  => 3,
                    'kid' => 0
                )
            ),
            array(
                'case'   => 'Passed a single id (id), asked for loading',
                'result' => array(3),
                'load'   => true,
                'loadid' => array('id' => 3)
            )
        );

        $data[] = array(
            array(
                'load' => false,
                'mock' => array(
                    'cid' => array(),
                    'id'  => 0,
                    'kid' => 3
                )
            ),
            array(
                'case'   => 'Passed a single id (kid) , not asked for loading',
                'result' => array(3),
                'load'   => false,
                'loadid' => null
            )
        );

        $data[] = array(
            array(
                'load' => true,
                'mock' => array(
                    'cid' => array(),
                    'id'  => 0,
                    'kid' => 3
                )
            ),
            array(
                'case'   => 'Passed a single id (kid), asked for loading',
                'result' => array(3),
                'load'   => true,
                'loadid' => array('id' => 3)
            )
        );

        $data[] = array(
            array(
                'load' => false,
                'mock' => array(
                    'cid' => array(4,5,6),
                    'id'  => 3,
                    'kid' => 0
                )
            ),
            array(
                'case'   => 'Passing an array of id (cid) and a single id (id), not asked for loading',
                'result' => array(4,5,6),
                'load'   => false,
                'loadid' => null
            )
        );

        $data[] = array(
            array(
                'load' => false,
                'mock' => array(
                    'cid' => array(4,5,6),
                    'id'  => 0,
                    'kid' => 3
                )
            ),
            array(
                'case'   => 'Passing an array of id (cid) and a single id (kid), not asked for loading',
                'result' => array(4,5,6),
                'load'   => false,
                'loadid' => null
            )
        );

        $data[] = array(
            array(
                'load' => false,
                'mock' => array(
                    'cid' => array(),
                    'id'  => 4,
                    'kid' => 3
                )
            ),
            array(
                'case'   => 'Passing a single id (id and kid), not asked for loading',
                'result' => array(4),
                'load'   => false,
                'loadid' => null
            )
        );

        $data[] = array(
            array(
                'load' => false,
                'mock' => array(
                    'cid' => array(4,5,6),
                    'id'  => 3,
                    'kid' => 7
                )
            ),
            array(
                'case'   => 'Passing everything, not asked for loading',
                'result' => array(4,5,6),
                'load'   => false,
                'loadid' => null
            )
        );

        return $data;
    }

    public static function getTestLoadHistory()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'version'   => 1,
                    'returnurl' => '',
                    'history'   => '',
                    'checkACL'  => true
                )
            ),
            array(
                'case'       => 'Everything is going smooth',
                'version_id' => 1,
                'alias'      => 'com_fakeapp.dummycontroller',
                'url'        => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'        => 'JLIB_APPLICATION_SUCCESS_LOAD_HISTORY',
                'type'       => null,
                'result'     => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'version'   => 1,
                    'returnurl' => 'www.example.com',
                    'history'   => '',
                    'checkACL'  => true
                )
            ),
            array(
                'case'       => 'Everything is going smooth, custom redirect',
                'version_id' => 1,
                'alias'      => 'com_fakeapp.dummycontroller',
                'url'        => 'www.example.com',
                'msg'        => 'JLIB_APPLICATION_SUCCESS_LOAD_HISTORY',
                'type'       => null,
                'result'     => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'version'   => 1,
                    'returnurl' => '',
                    'history'   => 'exception',
                    'checkACL'  => true
                )
            ),
            array(
                'case'       => 'Load history throws an error',
                'version_id' => 1,
                'alias'      => 'com_fakeapp.dummycontroller',
                'url'        => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'        => 'Load history error',
                'type'       => 'error',
                'result'     => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'version'   => 1,
                    'returnurl' => '',
                    'history'   => '',
                    'checkACL'  => false
                )
            ),
            array(
                'case'       => 'Check ACL returns false',
                'version_id' => 1,
                'alias'      => 'com_fakeapp.dummycontroller',
                'url'        => 'index.php?option=com_fakeapp&view=dummycontrollers',
                'msg'        => 'JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED',
                'type'       => 'error',
                'result'     => false
            )
        );

        return $data;
    }

    public static function getTestGetItemidURLSuffix()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'frontend' => false,
                    'itemid'   => 0
                )
            ),
            array(
                'case'   => 'Backend, not Itemid set',
                'result' => ''
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'frontend' => false,
                    'itemid'   => 130
                )
            ),
            array(
                'case'   => 'Backend, with Itemid set',
                'result' => ''
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'frontend' => true,
                    'itemid'   => 0
                )
            ),
            array(
                'case'   => 'Frontend, not Itemid set',
                'result' => ''
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'frontend' => true,
                    'itemid'   => 130
                )
            ),
            array(
                'case'   => 'Frontend, with Itemid set',
                'result' => '&Itemid=130'
            )
        );

        return $data;
    }
}