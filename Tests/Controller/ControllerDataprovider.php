<?php

class ControllerDataprovider
{
    public static function getTest__construct()
    {
        $data[] = array(
            array(
                'layout'    => null,
                'config'    => array()
            ),
            array(
                'case'        => 'No layout, no config passed',
                'defaultTask' => 'main',
                'layout'      => null,
                'viewName'    => false,
                'modelName'   => false,
                'name'        => 'dummycontroller',
                'autoroute'   => 0,
                'csrf'        => 2
            )
        );

        $data[] = array(
            array(
                'layout'    => 'foobar',
                'config'    => array(
                    'viewName'  => 'dummy',
                    'modelName' => 'dummy',
                    'default_view' => 'foobar',
                    'default_task' => 'dummy',
                    'name'         => 'dummycontroller',
                    'autoRouting'  => 1,
                    'csrfProtection' => 1
                )
            ),
            array(
                'case'        => 'Layout and config passed',
                'defaultTask' => 'dummy',
                'layout'      => 'foobar',
                'viewName'    => 'dummy',
                'modelName'   => 'dummy',
                'name'        => 'dummycontroller',
                'autoroute'   => 1,
                'csrf'        => 1
            )
        );

        return $data;
    }

    public static function getTest__get()
    {
        $data[]= array(
            array(
                'method' => 'input'
            ),
            array(
                'case'   => 'Requesting the input object from the container',
                'result' => true
            )
        );

        $data[] = array(
            array(
                'method' => 'wrong'
            ),
            array(
                'case'   => 'Requesting a non-existing method',
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestExecute()
    {
        $data[] = array(
            array(
                'task' => 'foobar',
                'mock' => array(
                    'before' => true,
                    'task'  => true,
                    'after' => true,
                    'taskMap' => array(
                        'foobar' => 'foobar',
                        '__default' => 'test'
                    )
                )
            ),
            array(
                'case' => 'Task is defined inside the taskMap array',
                'doTask' => 'foobar',
                'before' => 0,
                'task' => 1,
                'after' => 0,
                'result' => true
            )
        );

        $data[] = array(
            array(
                'task' => 'foobar',
                'mock' => array(
                    'before' => true,
                    'task'  => false,
                    'after' => true,
                    'taskMap' => array(
                        'foobar' => 'foobar',
                        '__default' => 'test'
                    )
                )
            ),
            array(
                'case' => 'Task is defined inside the taskMap array',
                'doTask' => 'foobar',
                'before' => 0,
                'task' => 1,
                'after' => 0,
                'result' => false
            )
        );

        $data[] = array(
            array(
                'task' => 'foobar',
                'mock' => array(
                    'before' => true,
                    'task'  => true,
                    'after' => true,
                    'taskMap' => array(
                        '__default' => 'foobar'
                    )
                )
            ),
            array(
                'case' => 'Task is defined as default inside the taskMap array',
                'doTask' => 'foobar',
                'before' => 0,
                'task' => 1,
                'after' => 0,
                'result' => true
            )
        );

        $data[] = array(
            array(
                'task' => 'dummy',
                'mock' => array(
                    'before' => true,
                    'task'  => true,
                    'after' => true,
                    'taskMap' => array(
                        'dummy' => 'dummy',
                        '__default' => 'test'
                    )
                )
            ),
            array(
                'case' => 'Task is defined inside the taskMap array, onBefore and onAfter return true',
                'doTask' => 'dummy',
                'before' => 1,
                'task' => 1,
                'after' => 1,
                'result' => true
            )
        );

        $data[] = array(
            array(
                'task' => 'dummy',
                'mock' => array(
                    'before' => false,
                    'task'  => true,
                    'after' => true,
                    'taskMap' => array(
                        'dummy' => 'dummy',
                        '__default' => 'test'
                    )
                )
            ),
            array(
                'case' => 'Task is defined inside the taskMap array, onBefore returns false and onAfter returns true',
                'doTask' => null,
                'before' => 1,
                'task' => 0,
                'after' => 0,
                'result' => false
            )
        );

        $data[] = array(
            array(
                'task' => 'dummy',
                'mock' => array(
                    'before' => true,
                    'task'  => true,
                    'after' => false,
                    'taskMap' => array(
                        'dummy' => 'dummy',
                        '__default' => 'test'
                    )
                )
            ),
            array(
                'case' => 'Task is defined inside the taskMap array, onBefore returns true and onAfter returns false',
                'doTask' => 'dummy',
                'before' => 1,
                'task' => 1,
                'after' => 1,
                'result' => false
            )
        );

        $data[] = array(
            array(
                'task' => 'dummy',
                'mock' => array(
                    'before' => true,
                    'task'  => false,
                    'after' => false,
                    'taskMap' => array(
                        'dummy' => 'dummy',
                        '__default' => 'test'
                    )
                )
            ),
            array(
                'case' => 'Task is defined inside the taskMap array, task returns false onBefore returns true and onAfter returns false',
                'doTask' => 'dummy',
                'before' => 1,
                'task' => 1,
                'after' => 1,
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestDisplay()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'getModel'  => false,
                    'task'      => 'foobar',
                    'doTask'    => 'foobar',
                    'layout'    => null
                )
            ),
            array(
                'case'          => 'Model not created, layout is null',
                'modelCounter'  => 0,
                'layoutCounter' => 0,
                'layout'        => null,
                'task'          => 'foobar',
                'doTask'        => 'foobar'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getModel'  => new \FOF30\Tests\Stubs\Model\ModelStub(
                                        new \FOF30\Tests\Helpers\TestContainer(array(
                                            'componentName' => 'com_eastwood')
                                        )
                                    ),
                    'task'      => 'foobar',
                    'doTask'    => 'foobar',
                    'layout'    => 'dummy'
                )
            ),
            array(
                'case'          => 'Model created, layout is not null',
                'modelCounter'  => 1,
                'layoutCounter' => 1,
                'layout'        => 'dummy',
                'task'          => 'foobar',
                'doTask'        => 'foobar'
            )
        );

        return $data;
    }

    public static function getTestGetModel()
    {
        $data[] = array(
            array(
                'name' => 'foobar',
                'config' => array(),
                'constructConfig' => array(),
                'mock' => array(
                    'view' => null,
                    'modelName' => null,
                    'instances' => array(),
                    'getModel'  => true
                )
            ),
            array(
                'case'      => 'Name passed, model not cached, internal reference are empty',
                'result'    => true,
                'modelName' => 'Foobar',
                'config'    => array(
                    'modelTemporaryInstance' => false,
                    'modelClearState'        => true,
                    'modelClearInput'        => true
                )
            )
        );

        $data[] = array(
            array(
                'name' => 'foobar',
                'config' => array('foo' => 'bar'),
                'constructConfig' => array(),
                'mock' => array(
                    'view' => null,
                    'modelName' => null,
                    'instances' => array(),
                    'getModel'  => 'mocked'
                )
            ),
            array(
                'case'   => 'Name and config passed, model not cached, internal reference are empty',
                'result' => 'mocked',
                'modelName' => 'Foobar',
                'config' => array(
                    'foo' => 'bar',
                    'modelTemporaryInstance' => false,
                    'modelClearState'        => true,
                    'modelClearInput'        => true
                )
            )
        );

        $data[] = array(
            array(
                'name' => 'foobar',
                'config' => array(),
                'constructConfig' => array(
                    'modelConfig' => array(
                        'foo' => 'bar')
                ),
                'mock' => array(
                    'view' => null,
                    'modelName' => null,
                    'instances' => array(),
                    'getModel'  => 'mocked'
                )
            ),
            array(
                'case'   => 'Name and config passed (in the constructor), model not cached, internal reference are empty',
                'result' => 'mocked',
                'modelName' => 'Foobar',
                'config' => array(
                    'foo' => 'bar',
                    'modelTemporaryInstance' => false,
                    'modelClearState'        => true,
                    'modelClearInput'        => true
                )
            )
        );

        $data[] = array(
            array(
                'name' => null,
                'config' => array(),
                'constructConfig' => array(),
                'mock' => array(
                    'view' => null,
                    'modelName' => 'foobar',
                    'instances' => array(),
                    'getModel'  => true
                )
            ),
            array(
                'case'   => 'Name not passed, model not cached, using modelName property',
                'result' => true,
                'modelName' => 'Foobar',
                'config' => array(
                    'modelTemporaryInstance' => true
                )
            )
        );

        $data[] = array(
            array(
                'name' => null,
                'config' => array(),
                'constructConfig' => array(),
                'mock' => array(
                    'view' => 'foobar',
                    'modelName' => null,
                    'instances' => array(),
                    'getModel'  => 'mocked'
                )
            ),
            array(
                'case'   => 'Name not passed, model not cached, using view property',
                'result' => 'mocked',
                'modelName' => 'Foobar',
                'config' => array(
                    'modelTemporaryInstance' => true
                )
            )
        );

        $data[] = array(
            array(
                'name'   => 'foobar',
                'config' => array(),
                'constructConfig' => array(),
                'mock'   => array(
                    'view' => null,
                    'modelName' => null,
                    'instances' => array('foobar' => 'cached'),
                    'getModel'  => true
                )
            ),
            array(
                'case'   => 'Name passed, fetching the model from the cache',
                'result' => 'cached',
                'modelName' => '',
                'config'    => array()
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
                    'getView'   => 'mocked'
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
                    'format'    => 'html',
                    'getView'   => 'mocked'
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
                    'getView'   => 'mocked'
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
                    'getView'   => 'mocked'
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
                    'getView'   => 'mocked'
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
                    'instances' => array('foobar' => 'cached'),
                    'format'    => null,
                    'getView'   => 'mocked'
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
                    'getView'   => 'mocked'
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
                    'getView'   => 'mocked'
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

    public static function getTestRedirect()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'redirect' => 'index.php'
                )
            ),
            array(
                'case'     => 'A redirect as been set',
                'result'   => null,
                'redirect' => 1
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'redirect' => null
                )
            ),
            array(
                'case'     => 'No redirection set',
                'result'   => false,
                'redirect' => 0
            )
        );

        return $data;
    }

    public static function getTestRegisterTask()
    {
        $data[] = array(
            array(
                'task'   => 'dummy',
                'method' => 'foobar',
                'mock'   => array(
                    'methods' => array('foobar')
                )
            ),
            array(
                'case'     => 'Method is mapped inside the controller',
                'register' => true
            )
        );

        $data[] = array(
            array(
                'task'   => 'dummy',
                'method' => 'foobar',
                'mock'   => array(
                    'methods' => array()
                )
            ),
            array(
                'case'     => 'Method is not mapped inside the controller',
                'register' => false
            )
        );

        return $data;
    }

    public static function getTestSetMessage()
    {
        $data[] = array(
            array(
                'message' => 'foo',
                'type'    => null,
                'mock' => array(
                    'previous' => 'bar'
                )
            ),
            array(
                'case'      => '$type argument is null',
                'result'    => 'bar',
                'message'   => 'foo',
                'type'      => 'message'
            )
        );

        $data[] = array(
            array(
                'message' => 'foo',
                'type'    => 'warning',
                'mock' => array(
                    'previous' => 'bar'
                )
            ),
            array(
                'case'      => 'Message type is defined',
                'result'    => 'bar',
                'message'   => 'foo',
                'type'      => 'warning'
            )
        );

        return $data;
    }

    public static function getTestSetRedirect()
    {
        $data[] = array(
            array(
                'url'  => 'index.php',
                'msg'  => null,
                'type' => null,
                'mock' => array(
                    'type' => null
                )
            ),
            array(
                'case'     => 'Url is set, message and type are null; controller messageType is null',
                'redirect' => 'index.php',
                'message'  => null,
                'type'     => 'info'
            )
        );

        $data[] = array(
            array(
                'url'  => 'index.php',
                'msg'  => null,
                'type' => null,
                'mock' => array(
                    'type' => 'warning'
                )
            ),
            array(
                'case'     => 'Url is set, message and type are null; controller messageType is not null',
                'redirect' => 'index.php',
                'message'  => null,
                'type'     => 'warning'
            )
        );

        $data[] = array(
            array(
                'url'  => 'index.php',
                'msg'  => null,
                'type' => 'info',
                'mock' => array(
                    'type' => 'warning'
                )
            ),
            array(
                'case'     => 'Url and type are set, message is null; controller messageType is not null',
                'redirect' => 'index.php',
                'message'  => null,
                'type'     => 'info'
            )
        );

        $data[] = array(
            array(
                'url'  => 'index.php',
                'msg'  => 'Foobar',
                'type' => 'info',
                'mock' => array(
                    'type' => 'warning'
                )
            ),
            array(
                'case'     => 'Url, type and message are set, controller messageType is not null',
                'redirect' => 'index.php',
                'message'  => 'Foobar',
                'type'     => 'info'
            )
        );

        return $data;
    }
}