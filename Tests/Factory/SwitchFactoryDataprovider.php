<?php

class SwitchFactoryDataprovider
{
    public static function getTestController()
    {
        $data[] = array(
            array(
                'backend' => false,
                'view' => 'foobars'
            ),
            array(
                'case' => 'Frontend controller, found',
                'result' => 'Fakeapp\Site\Controller\Foobars'
            )
        );

        $data[] = array(
            array(
                'backend' => true,
                'view' => 'foobars'
            ),
            array(
                'case' => 'Backend controller, found',
                'result' => 'Fakeapp\Site\Controller\Foobars'
            )
        );

        $data[] = array(
            array(
                'backend' => false,
                'view' => 'bares'
            ),
            array(
                'case' => 'Backend controller, found',
                'result' => 'Fakeapp\Admin\Controller\Bare'
            )
        );

        return $data;
    }

    public static function getTestModel()
    {
        $data[] = array(
            array(
                'backend' => false,
                'view' => 'foobars'
            ),
            array(
                'case' => 'Frontend model, found',
                'result' => 'Fakeapp\Site\Model\Foobar'
            )
        );

        $data[] = array(
            array(
                'backend' => true,
                'view' => 'foobars'
            ),
            array(
                'case' => 'Backend model, found',
                'result' => 'Fakeapp\Site\Model\Foobar'
            )
        );

        return $data;
    }

    public static function getTestView()
    {
        $data[] = array(
            array(
                'backend' => false,
                'view' => 'parents'
            ),
            array(
                'case' => 'Frontend view, found',
                'result' => 'Fakeapp\Site\View\Parents\Html'
            )
        );

        $data[] = array(
            array(
                'backend' => false,
                'view' => 'bares'
            ),
            array(
                'case' => 'Frontend view, found',
                'result' => 'Fakeapp\Admin\View\Bare\Html'
            )
        );

        $data[] = array(
            array(
                'backend' => true,
                'view' => 'parents'
            ),
            array(
                'case' => 'Backend view, found',
                'result' => 'Fakeapp\Site\View\Parents\Html'
            )
        );

        return $data;
    }

    public static function getTestDispatcher()
    {
        $data[] = array(
            array(
                'backend' => false,
                'component' => 'com_fakeapp'
            ),
            array(
                'case' => 'Frontend dispatcher, found',
                'result' => 'Fakeapp\Admin\Dispatcher\Dispatcher'
            )
        );

        $data[] = array(
            array(
                'backend' => true,
                'component' => 'com_fakeapp'
            ),
            array(
                'case' => 'Backend dispatcher, found',
                'result' => 'Fakeapp\Admin\Dispatcher\Dispatcher'
            )
        );

        $data[] = array(
            array(
                'backend' => true,
                'component' => 'com_dummyapp'
            ),
            array(
                'case' => 'Dispatcher not found, fall back to the standard one',
                'result' => 'FOF30\Dispatcher\Dispatcher'
            )
        );

        return $data;
    }

    public static function getTestToolbar()
    {
        $data[] = array(
            array(
                'backend' => false,
                'component' => 'com_fakeapp'
            ),
            array(
                'case' => 'Frontend toolbar, found',
                'result' => 'Fakeapp\Site\Toolbar\Toolbar'
            )
        );

        $data[] = array(
            array(
                'backend' => true,
                'component' => 'com_fakeapp'
            ),
            array(
                'case' => 'Backend toolbar, found',
                'result' => 'Fakeapp\Site\Toolbar\Toolbar'
            )
        );

        $data[] = array(
            array(
                'backend' => true,
                'component' => 'com_dummyapp'
            ),
            array(
                'case' => 'Toolbar not found, fall back to the standard one',
                'result' => 'FOF30\Toolbar\Toolbar'
            )
        );

        return $data;
    }

    public static function getTestTransparentAuthentication()
    {
        $data[] = array(
            array(
                'backend' => false,
                'component' => 'com_fakeapp'
            ),
            array(
                'case' => 'Frontend transparentAuthentication, found',
                'result' => 'Fakeapp\Admin\TransparentAuthentication\TransparentAuthentication'
            )
        );

        $data[] = array(
            array(
                'backend' => true,
                'component' => 'com_fakeapp'
            ),
            array(
                'case' => 'Backend transparentAuthentication, found',
                'result' => 'Fakeapp\Admin\TransparentAuthentication\TransparentAuthentication'
            )
        );

        $data[] = array(
            array(
                'backend' => true,
                'component' => 'com_dummyapp'
            ),
            array(
                'case' => 'TransparentAuthentication not found, fall back to the standard one',
                'result' => 'FOF30\TransparentAuthentication\TransparentAuthentication'
            )
        );

        return $data;
    }
}
