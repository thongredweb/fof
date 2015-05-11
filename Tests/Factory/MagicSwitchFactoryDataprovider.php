<?php

class MagicSwitchFactoryDataprovider
{
    public static function getTestController()
    {
        $data[] = array(
            array(
                'view' => 'foobars'
            ),
            array(
                'case' => 'Controller is immediately found',
                'result' => 'Fakeapp\Site\Controller\Foobars'
            )
        );

        $data[] = array(
            array(
                'view' => 'nothere'
            ),
            array(
                'case' => 'Controller is not found',
                'result' => 'Fakeapp\Site\Controller\DefaultDataController'
            )
        );

        return $data;
    }

    public static function getTestModel()
    {
        $data[] = array(
            array(
                'view' => 'foobars'
            ),
            array(
                'case' => 'Model is immediately found',
                'result' => 'Fakeapp\Site\Model\Foobar'
            )
        );

        $data[] = array(
            array(
                'view' => 'tests'
            ),
            array(
                'case' => 'Model is not found',
                'result' => 'FOF30\Model\DataModel'
            )
        );

        return $data;
    }

    public static function getTestView()
    {
        $data[] = array(
            array(
                'view' => 'foobars'
            ),
            array(
                'case' => 'View is immediately found',
                'result' => 'Fakeapp\Site\View\Foobars\Html'
            )
        );

        $data[] = array(
            array(
                'view' => 'tests'
            ),
            array(
                'case' => 'View is not found',
                'result' => 'FOF30\View\DataView\Html'
            )
        );

        return $data;
    }

    public static function getTestDispatcher()
    {
        $data[] = array(
            array(
                'backend' => true,
                'backend_path' => JPATH_TESTS.'/Stubs/Fakeapp/Admin',
                'component' => 'com_fakeapp'
            ),
            array(
                'case' => 'Dispatcher is found',
                'result' => 'Fakeapp\Admin\Dispatcher\Dispatcher'
            )
        );

        $data[] = array(
            array(
                'backend' => false,
                'backend_path' => JPATH_TESTS.'/Stubs/Fakeapp/Admin',
                'component' => 'com_fakeapp'
            ),
            array(
                'case' => 'Dispatcher is found',
                'result' => 'Fakeapp\Admin\Dispatcher\Dispatcher'
            )
        );

        $data[] = array(
            array(
                'backend' => false,
                'backend_path' => JPATH_TESTS.'/Stubs/Dummyapp/Admin',
                'component' => 'com_dummyapp'
            ),
            array(
                'case' => 'Dispatcher not found',
                'result' => 'FOF30\Dispatcher\Dispatcher'
            )
        );

        return $data;
    }

    public static function getTestTransparentAuthentication()
    {
        $data[] = array(
            array(
                'backend' => true,
                'backend_path' => JPATH_TESTS.'/Stubs/Fakeapp/Admin',
                'component' => 'com_fakeapp'
            ),
            array(
                'case' => 'TransparentAuthentication is found',
                'result' => 'Fakeapp\Admin\TransparentAuthentication\TransparentAuthentication'
            )
        );

        $data[] = array(
            array(
                'backend' => false,
                'backend_path' => JPATH_TESTS.'/Stubs/Fakeapp/Admin',
                'component' => 'com_fakeapp'
            ),
            array(
                'case' => 'TransparentAuthentication is found',
                'result' => 'Fakeapp\Admin\TransparentAuthentication\TransparentAuthentication'
            )
        );

        $data[] = array(
            array(
                'backend' => false,
                'backend_path' => JPATH_TESTS.'/Stubs/Dummyapp/Admin',
                'component' => 'com_dummyapp'
            ),
            array(
                'case' => 'TransparentAuthentication not found',
                'result' => 'FOF30\TransparentAuthentication\TransparentAuthentication'
            )
        );

        return $data;
    }
}
