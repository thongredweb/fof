<?php

class ViewDataprovider
{
    public static function getTest__construct()
    {
        $data[] = array(
            array(
                'config' => array(),
                'mock'   => array(
                    'plugins' => array()
                )
            ),
            array(
                'case' => 'Empty configuration, plugins do not add any path',
                'name' => 'nestedset',
                'layout' => 'default',
                'layoutTemplate' => '_',
                'templatePaths' => array(
                    JPATH_THEMES.'/fake_test_template/html/com_fakeapp/nestedset/',
                    JPATH_SITE.'/components/com_fakeapp/View/Nestedset/tmpl/'
                ),
                'baseurl' => 'www.example.com',
                'engines' => array(
                    '.blade.php' => 'FOF30\View\Engine\BladeEngine',
                    '.php' => 'FOF30\View\Engine\PhpEngine'
                )
            )
        );

        $data[] = array(
            array(
                'config' => array(
                    'name' => 'ConfigName',
                    'template_path' => 'config/path',
                    'layout' => 'foo:bar',
                    'viewEngineMap' => array(
                        'test' => 'testEngine'
                    )
                ),
                'mock'   => array(
                    'plugins' => array(
                        array('plugin/path')
                    )
                )
            ),
            array(
                'case' => 'Values in configuration, plugins do add some paths',
                'name' => 'ConfigName',
                'layout' => 'bar',
                'layoutTemplate' => 'foo',
                'templatePaths' => array(
                    'plugin/path/',
                    JPATH_THEMES.'/fake_test_template/html/com_fakeapp/ConfigName/',
                    'config/path/'
                ),
                'baseurl' => 'www.example.com',
                'engines' => array(
                    '.blade.php' => 'FOF30\View\Engine\BladeEngine',
                    '.php' => 'FOF30\View\Engine\PhpEngine',
                    'test' => 'testEngine'
                )
            )
        );

        $data[] = array(
            array(
                'config' => array(
                    'name' => 'ConfigName',
                    'template_path' => 'config/path',
                    'layout' => 'foo:bar',
                    'viewEngineMap' => 'test => testEngine, test2 => test2Engine'
                ),
                'mock'   => array(
                    'plugins' => array(
                        array('plugin/path')
                    )
                )
            ),
            array(
                'case' => 'Values in configuration (view engines are a string), plugins do add some paths',
                'name' => 'ConfigName',
                'layout' => 'bar',
                'layoutTemplate' => 'foo',
                'templatePaths' => array(
                    'plugin/path/',
                    JPATH_THEMES.'/fake_test_template/html/com_fakeapp/ConfigName/',
                    'config/path/'
                ),
                'baseurl' => 'www.example.com',
                'engines' => array(
                    '.blade.php' => 'FOF30\View\Engine\BladeEngine',
                    '.php' => 'FOF30\View\Engine\PhpEngine',
                    'test' => 'testEngine',
                    'test2' => 'test2Engine'
                )
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

    public function getTestGet()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'viewProperty' => array(),
                    'defaultModel' => 'foobars',
                    'instances' => array(
                        'foobars' => new \FOF30\Tests\Stubs\Model\ModelStub(
                            new \FOF30\Tests\Helpers\TestContainer(array(
                                'componentName' => 'com_fakeapp'
                            ))
                        )
                    )
                ),
                'property' => 'foobar',
                'default'  => null,
                'model'    => null
            ),
            array(
                'case'   => 'Using default model, get<Property>() exists in the model',
                'result' => 'ok'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'viewProperty' => array(),
                    'defaultModel' => 'foobars',
                    'instances' => array(
                        'foobars' => new \FOF30\Tests\Stubs\Model\ModelStub(
                            new \FOF30\Tests\Helpers\TestContainer(array(
                                'componentName' => 'com_fakeapp'
                            ))
                        )
                    )
                ),
                'property' => 'dummy',
                'default'  => null,
                'model'    => null
            ),
            array(
                'case'   => 'Using default model, <Property>() exists in the model',
                'result' => 'ok'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'viewProperty' => array(),
                    'defaultModel' => 'foobars',
                    'instances' => array(
                        'foobars' => new \FOF30\Tests\Stubs\Model\ModelStub(
                            new \FOF30\Tests\Helpers\TestContainer(array(
                                'componentName' => 'com_fakeapp'
                            ))
                        )
                    )
                ),
                'property' => 'nothere',
                'default'  => 'default',
                'model'    => null
            ),
            array(
                'case'   => "Using default model, there isn't any method in the model",
                'result' => null
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'viewProperty' => array(),
                    'defaultModel' => 'dummy',
                    'instances' => array(
                        'foobars' => new \FOF30\Tests\Stubs\Model\ModelStub(
                            new \FOF30\Tests\Helpers\TestContainer(array(
                                'componentName' => 'com_fakeapp'
                            ))
                        )
                    )
                ),
                'property' => 'foobar',
                'default'  => null,
                'model'    => 'foobars'
            ),
            array(
                'case'   => 'Requesting for a specific model, get<Property>() exists in the model',
                'result' => 'ok'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'viewProperty' => array(),
                    'defaultModel' => 'dummy',
                    'instances' => array(
                        'foobars' => new \FOF30\Tests\Stubs\Model\ModelStub(
                            new \FOF30\Tests\Helpers\TestContainer(array(
                                'componentName' => 'com_fakeapp'
                            ))
                        )
                    )
                ),
                'property' => 'dummy',
                'default'  => null,
                'model'    => 'foobars'
            ),
            array(
                'case'   => 'Requesting for a specific model, <Property>() exists in the model',
                'result' => 'ok'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'viewProperty' => array(
                        'key'   => 'foobar',
                        'value' => 'test'
                    ),
                    'defaultModel' => 'foobars',
                    'instances' => array()
                ),
                'property' => 'foobar',
                'default'  => 'default',
                'model'    => null
            ),
            array(
                'case'   => 'Model not found, getting (existing) view property',
                'result' => 'test'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'viewProperty' => array(),
                    'defaultModel' => 'foobars',
                    'instances' => array()
                ),
                'property' => 'foobar',
                'default'  => 'default',
                'model'    => null
            ),
            array(
                'case'   => 'Model not found, getting (non-existing) view property',
                'result' => 'default'
            )
        );

        return $data;
    }

    public static function getTestGetModel()
    {
        $data[] = array(
            array(
                'name' => 'foobar',
                'mock' => array(
                    'name' => null,
                    'defaultModel' => null,
                    'instances' => array(
                        'foobar' => 'test'
                    )
                )
            ),
            array(
                'case'   => 'Name passed',
                'result' => 'test',
                'exception' => false
            )
        );

        $data[] = array(
            array(
                'name' => null,
                'mock' => array(
                    'name' => 'foobar',
                    'defaultModel' => null,
                    'instances' => array(
                        'foobar' => 'test'
                    )
                )
            ),
            array(
                'case'   => 'Using the view name',
                'result' => 'test',
                'exception' => false
            )
        );

        $data[] = array(
            array(
                'name' => null,
                'mock' => array(
                    'name' => null,
                    'defaultModel' => 'foobar',
                    'instances' => array(
                        'foobar' => 'test'
                    )
                )
            ),
            array(
                'case'   => 'Using the default model name',
                'result' => 'test',
                'exception' => false
            )
        );

        $data[] = array(
            array(
                'name' => 'wrong',
                'mock' => array(
                    'name' => null,
                    'defaultModel' => null,
                    'instances' => array(
                        'foobar' => 'test'
                    )
                )
            ),
            array(
                'case'   => 'Model not found',
                'result' => '',
                'exception' => true
            )
        );

        return $data;
    }

    public static function getTestDisplay()
    {
        // No template, everything is going smooth
        $data[] = array(
            array(
                'mock' => array(
                    'doTask' => 'Foobar',
                    'doPreRender' => false,
                    'pre'    => '',
                    'before' => null,
                    'after'  => null,
                    'output' => 'test'
                ),
                'tpl' => null
            ),
            array(
                'case'      => 'No template, everything is going smooth',
                'output'    => 'test',
                'tpl'       => null,
                'exception' => false,
                'load'      => true,
                'before'    => array('counter' => 0),
                'after'     => array('counter' => 0),
            )
        );

        // With template, everything is going smooth
        $data[] = array(
            array(
                'mock' => array(
                    'doTask' => 'Foobar',
                    'doPreRender' => false,
                    'pre'    => '',
                    'before' => null,
                    'after'  => null,
                    'output' => 'test'
                ),
                'tpl' => 'test'
            ),
            array(
                'case'      => 'With template, everything is going smooth',
                'output'    => 'test',
                'tpl'       => 'test',
                'exception' => false,
                'load'      => true,
                'before'    => array('counter' => 0),
                'after'     => array('counter' => 0),
            )
        );

        // With template, before/after methods are correctly called
        $data[] = array(
            array(
                'mock' => array(
                    'doTask' => 'Dummy',
                    'doPreRender' => false,
                    'pre'    => '',
                    'before' => true,
                    'after'  => true,
                    'output' => 'test'
                ),
                'tpl' => 'test'
            ),
            array(
                'case'      => 'With template, before/after methods are correctly called',
                'output'    => 'test',
                'tpl'       => 'test',
                'exception' => false,
                'load'      => true,
                'before'    => array('counter' => 1),
                'after'     => array('counter' => 1),
            )
        );

        // No template, before throws an exception
        $data[] = array(
            array(
                'mock' => array(
                    'doTask' => 'Dummy',
                    'doPreRender' => false,
                    'pre'    => '',
                    'before' => false,
                    'after'  => true,
                    'output' => 'test'
                ),
                'tpl' => null
            ),
            array(
                'case'      => 'No template, before throws an exception',
                'output'    => null,
                'tpl'       => null,
                'exception' => '\Exception',
                'load'      => false,
                'before'    => array('counter' => 1),
                'after'     => array('counter' => 0),
            )
        );

        // No template, after throws an exception
        $data[] = array(
            array(
                'mock' => array(
                    'doTask' => 'Dummy',
                    'doPreRender' => false,
                    'pre'    => '',
                    'before' => true,
                    'after'  => false,
                    'output' => 'test'
                ),
                'tpl' => null
            ),
            array(
                'case'      => 'No template, after throws an exception',
                'output'    => null,
                'tpl'       => null,
                'exception' => '\Exception',
                'load'      => true,
                'before'    => array('counter' => 1),
                'after'     => array('counter' => 1),
            )
        );

        // No template, loadTemplate returns an exception
        $data[] = array(
            array(
                'mock' => array(
                    'doTask' => 'Foobar',
                    'doPreRender' => false,
                    'pre'    => '',
                    'before' => null,
                    'after'  => null,
                    'output' => new \Exception('', 500)
                ),
                'tpl' => null
            ),
            array(
                'case'      => 'No template, loadTemplate returns an exception',
                'output'    => null,
                'tpl'       => null,
                'exception' => '\Exception',
                'load'      => true,
                'before'    => array('counter' => 0),
                'after'     => array('counter' => 0),
            )
        );

        // doPreRender is false, preRender return something
        $data[] = array(
            array(
                'mock' => array(
                    'doTask' => 'Foobar',
                    'doPreRender' => false,
                    'pre'    => 'pre-render',
                    'before' => null,
                    'after'  => null,
                    'output' => 'test'
                ),
                'tpl' => null
            ),
            array(
                'case'      => 'No template, everything is going smooth',
                'output'    => 'test',
                'tpl'       => null,
                'exception' => false,
                'load'      => true,
                'before'    => array('counter' => 0),
                'after'     => array('counter' => 0),
            )
        );

        // doPreRender is true, preRender return something
        $data[] = array(
            array(
                'mock' => array(
                    'doTask' => 'Foobar',
                    'doPreRender' => true,
                    'pre'    => 'pre-render ',
                    'before' => null,
                    'after'  => null,
                    'output' => 'test'
                ),
                'tpl' => null
            ),
            array(
                'case'      => 'No template, everything is going smooth',
                'output'    => 'pre-render test',
                'tpl'       => null,
                'exception' => false,
                'load'      => true,
                'before'    => array('counter' => 0),
                'after'     => array('counter' => 0),
            )
        );

        return $data;
    }

    public static function getTestLoadTemplate()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'layout'     => 'foobar',
                    'any'        => array('test'),
                    'viewFinder' => array('first uri')
                ),
                'tpl'    => null,
                'strict' => false
            ),
            array(
                'case'   => 'No template, no strict, we immediatly have a result',
                'result' => 'test',
                'exception' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'layout' => 'foobar',
                    'any'    => array('throw', 'throw', 'throw', 'throw', 'throw', 'throw'),
                    'viewFinder' => array('first uri', 'second uri')
                ),
                'tpl'    => null,
                'strict' => false
            ),
            array(
                'case'   => 'No template, no strict, we immediatly throw an exception',
                'result' => new \Exception(),
                'exception' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'layout' => 'foobar',
                    'any'    => array(new \Exception(), new \Exception(), new \Exception(), new \Exception(), new \Exception(), new \Exception()),
                    'viewFinder' => array('first uri', 'second uri')
                ),
                'tpl'    => null,
                'strict' => false
            ),
            array(
                'case'   => 'No template, no strict, we immediatly return an exception',
                'result' => '',
                'exception' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'layout' => 'foobar',
                    'any'    => array('throw', 'test'),
                    'viewFinder' => array('first uri', 'second uri')
                ),
                'tpl'    => null,
                'strict' => false
            ),
            array(
                'case'   => 'No template, no strict, we have a result after throwing some exceptions',
                'result' => 'test',
                'exception' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'layout' => 'foobar',
                    'any'    => array(new \Exception(), 'test'),
                    'viewFinder' => array('first uri', 'second uri')
                ),
                'tpl'    => null,
                'strict' => true
            ),
            array(
                'case'   => 'No template, no strict, loadAny returns an exception',
                'result' => 'test',
                'exception' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'layout' => 'foobar',
                    'any'    => array('test'),
                    'viewFinder' => array('first uri')
                ),
                'tpl'    => 'dummy',
                'strict' => false
            ),
            array(
                'case'   => 'With template, no strict, we immediatly have a result',
                'result' => 'test',
                'exception' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'layout' => 'foobar',
                    'any'    => array('test'),
                    'viewFinder' => array('first uri')
                ),
                'tpl'    => 'dummy',
                'strict' => true
            ),
            array(
                'case'   => 'With template and strict, we immediatly have a result',
                'result' => 'test',
                'exception' => false
            )
        );

        return $data;
    }

    public static function getTestLoadAnyTemplate()
    {
        $data[] = array(
            array(
                'uri'         => 'admin:com_fakeapp/foobar/default',
                'forceParams' => array(),
                'callback'    => null,
                'mock'        => array(
                    'alias'     => array(),
                    'engineGet' => array(
                        'type'    => 'raw',
                        'content' => 'test'
                    ),
                    'path'      => '',
                    '_path'     => ''
                )
            ),
            array(
                'case' => 'No callback, no alias, raw engine',
                'result' => 'test',
                'uri' => 'admin:com_fakeapp/foobar/default',
                'extra' => array()
            )
        );

        $data[] = array(
            array(
                'uri'         => 'admin:com_fakeapp/foobar/default',
                'forceParams' => array(),
                'callback'    => null,
                'mock'        => array(
                    'alias'     => array(),
                    'engineGet' => array(
                        'type'    => 'raw',
                        'content' => 'test'
                    ),
                    'path'      => array('template' => 'extra/path'),
                    '_path'     => ''
                )
            ),
            array(
                'case' => 'Using extra paths - 1',
                'result' => 'test',
                'uri' => 'admin:com_fakeapp/foobar/default',
                'extra' => 'extra/path'
            )
        );

        $data[] = array(
            array(
                'uri'         => 'admin:com_fakeapp/foobar/default',
                'forceParams' => array(),
                'callback'    => null,
                'mock'        => array(
                    'alias'     => array(),
                    'engineGet' => array(
                        'type'    => 'raw',
                        'content' => 'test'
                    ),
                    'path'      => '',
                    '_path'     => array('template' => 'extra/path2')
                )
            ),
            array(
                'case' => 'Using extra paths - 2',
                'result' => 'test',
                'uri' => 'admin:com_fakeapp/foobar/default',
                'extra' => 'extra/path2'
            )
        );

        $data[] = array(
            array(
                'uri'         => 'admin:com_fakeapp/foobar/default',
                'forceParams' => array(),
                'callback'    => function($view, $contents){
                    return 'callback';
                },
                'mock'        => array(
                    'alias'     => array(
                        'admin:com_fakeapp/foobar/default' => 'admin:com_fakeapp/alias/default'
                    ),
                    'engineGet' => array(
                        'type'    => 'raw',
                        'content' => 'test'
                    ),
                    'path'      => '',
                    '_path'     => ''
                )
            ),
            array(
                'case' => 'Using URI alias and callback',
                'result' => 'callback',
                'uri' => 'admin:com_fakeapp/alias/default',
                'extra' => array()
            )
        );

        $data[] = array(
            array(
                'uri'         => 'admin:com_fakeapp/foobar/default',
                'forceParams' => array(),
                'callback'    => null,
                'mock'        => array(
                    'alias'     => array(),
                    'engineGet' => array(
                        'type'    => '.php',
                        'content' => 'raw|test'
                    ),
                    'path'      => '',
                    '_path'     => ''
                )
            ),
            array(
                'case' => 'Using layout file with raw data',
                'result' => 'test',
                'uri' => 'admin:com_fakeapp/foobar/default',
                'extra' => array()
            )
        );

        $data[] = array(
            array(
                'uri'         => 'admin:com_fakeapp/foobar/default',
                'forceParams' => array(),
                'callback'    => null,
                'mock'        => array(
                    'alias'     => array(),
                    'engineGet' => array(
                        'type'    => '.php',
                        'content' => JPATH_TESTS.'/Stubs/Fakeapp/Admin/View/Foobar/tmpl/default.php'
                    ),
                    'path'      => '',
                    '_path'     => ''
                )
            ),
            array(
                'case' => 'Actually including a layout file',
                'result' => 'Layout text',
                'uri' => 'admin:com_fakeapp/foobar/default',
                'extra' => array()
            )
        );

        return $data;
    }

    public static function getTestFlushSectionsIfDoneRendering()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'done' => false
                )
            ),
            array(
                'flush' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'done' => true
                )
            ),
            array(
                'flush' => true
            )
        );

        return $data;
    }

    public static function getTestDoneRendering()
    {
        $data[] = array(
            array(
                'counter' => 0
            ),
            array(
                'case' => 'Internal counter is 0',
                'result' => true
            )
        );

        $data[] = array(
            array(
                'counter' => 10
            ),
            array(
                'case' => 'Internal counter is not 0',
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestRenderEach()
    {
        $data[] = array(
            array(
                'data'  => array(1,2),
                'empty' => 'raw|',
                'mock'  => array(
                    'loadAny' => array('first ', 'second')
                )
            ),
            array(
                'case'    => 'Data not empty',
                'result'  => 'first second',
                'loadAny' => true
            )
        );

        $data[] = array(
            array(
                'data'  => array(),
                'empty' => 'raw|default data',
                'mock'  => array(
                    'loadAny' => array('first ', 'second')
                )
            ),
            array(
                'case'    => 'Empty data',
                'result'  => 'default data',
                'loadAny' => false
            )
        );

        $data[] = array(
            array(
                'data'  => array(),
                'empty' => 'admin:com_fakeapp/foobar/empty',
                'mock'  => array(
                    'loadAny' => array('default data from layout')
                )
            ),
            array(
                'case'    => 'Empty data, loading a layout',
                'result'  => 'default data from layout',
                'loadAny' => true
            )
        );

        return $data;
    }

    public static function getTestSetLayout()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'layout' => null
                ),
                'layout' => 'foobar'
            ),
            array(
                'case'   => 'Internal layout is null, passing simple layout',
                'result' => null,
                'layout' => 'foobar',
                'tmpl'   => '_'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'layout' => 'previous'
                ),
                'layout' => 'foobar'
            ),
            array(
                'case'   => 'Internal layout is set, passing simple layout',
                'result' => 'previous',
                'layout' => 'foobar',
                'tmpl'   => '_'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'layout' => null
                ),
                'layout' => 'foo:bar'
            ),
            array(
                'case'   => 'Internal layout is null, passing layout + template',
                'result' => null,
                'layout' => 'bar',
                'tmpl'   => 'foo'
            )
        );

        return $data;
    }

    public static function getTestStartSection()
    {
        $data[] = array(
            array(
                'section' => 'foobar',
                'content' => '',
                'mock' => array(
                    'sections' => array()
                )
            ),
            array(
                'case' => 'Starting a new section with empty contents',
                'stack' => array('foobar'),
                'sections' => array(),
                'closeBuffer' => true
            )
        );

        $data[] = array(
            array(
                'section' => 'foobar',
                'content' => 'dummy content',
                'mock' => array(
                    'sections' => array()
                )
            ),
            array(
                'case' => "Adding contents to a section and it's not in the sections array",
                'stack' => array(),
                'sections' => array(
                    'foobar' => 'dummy content'
                ),
                'closeBuffer' => false
            )
        );

        $data[] = array(
            array(
                'section' => 'foobar',
                'content' => 'dummy content',
                'mock' => array(
                    'sections' => array(
                        'foobar' => 'old content'
                    )
                )
            ),
            array(
                'case' => "Adding contents to a section that's in the sections array",
                'stack' => array(),
                'sections' => array(
                    'foobar' => 'old content'
                ),
                'closeBuffer' => false
            )
        );

        $data[] = array(
            array(
                'section' => 'foobar',
                'content' => 'dummy content',
                'mock' => array(
                    'sections' => array(
                        'foobar' => '@parent old content'
                    )
                )
            ),
            array(
                'case' => "Adding contents to a section that's in the sections array, using the @parent keyword",
                'stack' => array(),
                'sections' => array(
                    'foobar' => 'dummy content old content'
                ),
                'closeBuffer' => false
            )
        );

        return $data;
    }

    public static function getTestStopSection()
    {
        $data[] = array(
            array(
                'overwrite' => false,
                'contents'  => 'test content',
                'mock' => array(
                    'stack' => array('foobar'),
                    'sections' => array(
                        'foobar' => 'old content'
                    )
                )
            ),
            array(
                'case'     => 'Not overwriting the section, current section has no @parent tag',
                'result'   => 'foobar',
                'contents' => 'old content'
            )
        );

        $data[] = array(
            array(
                'overwrite' => false,
                'contents'  => 'test content',
                'mock' => array(
                    'stack' => array('foobar'),
                    'sections' => array(
                        'foobar' => 'old content @parent'
                    )
                )
            ),
            array(
                'case'     => 'Not overwriting the section, current section has the @parent tag',
                'result'   => 'foobar',
                'contents' => 'old content test content'
            )
        );

        $data[] = array(
            array(
                'overwrite' => true,
                'contents'  => 'test content',
                'mock' => array(
                    'stack' => array('foobar'),
                    'sections' => array(
                        'foobar' => 'old content'
                    )
                )
            ),
            array(
                'case'     => 'Overwriting the section',
                'result'   => 'foobar',
                'contents' => 'test content'
            )
        );

        return $data;
    }

    public static function getTestAppendSection()
    {
        $data[] = array(
            array(
                'contents'  => 'test content',
                'mock' => array(
                    'stack' => array('foobar'),
                    'sections' => array()
                )
            ),
            array(
                'case'     => 'Section already does not exist',
                'result'   => 'foobar',
                'contents' => 'test content'
            )
        );

        $data[] = array(
            array(
                'contents'  => ' test content',
                'mock' => array(
                    'stack' => array('foobar'),
                    'sections' => array(
                        'foobar' => 'old content'
                    )
                )
            ),
            array(
                'case'     => 'Section already exists',
                'result'   => 'foobar',
                'contents' => 'old content test content'
            )
        );

        return $data;
    }

    public static function getTestYieldContent()
    {
        $data[] = array(
            array(
                'section' => 'wrong',
                'default' => '@parent Lorem ipsum',
                'mock' => array(
                    'sections' => array()
                )
            ),
            array(
                'case' => 'Section not set, using the default',
                'result' => ' Lorem ipsum'
            )
        );

        $data[] = array(
            array(
                'section' => 'present',
                'default' => '@parent Lorem ipsum',
                'mock' => array(
                    'sections' => array(
                        'present' => '@parent Found'
                    )
                )
            ),
            array(
                'case' => 'Section set',
                'result' => ' Found'
            )
        );

        return $data;
    }
}