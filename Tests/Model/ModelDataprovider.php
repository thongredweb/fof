<?php

class ModelDataprovider
{
    public static function getTest__construct()
    {
        $data[] = array(
            array(
                'config'     => array()
            ),
            array(
                'case'       => 'State is not set in the config, no populate, no ignore',
                'state'      => (object) array(),
                'populate'   => false,
                'ignore'     => false,
                'name'       => 'nestedset'
            )
        );

        $data[] = array(
            array(
                'config'     => array(
                    'name' => 'mocked'
                )
            ),
            array(
                'case'       => 'State is not set in the config, no populate, no ignore',
                'state'      => (object) array(),
                'populate'   => false,
                'ignore'     => false,
                'name'       => 'mocked'
            )
        );

        $data[] = array(
            array(
                'config'    => array(
                    'state' => array(
                        'dummy' => 'test'
                    )
                )
            ),
            array(
                'case'       => 'State is set in the config (array), no populate, no ignore',
                'state'      => (object) array(
                    'dummy' => 'test'
                ),
                'populate'   => false,
                'ignore'     => false,
                'name'       => 'nestedset'
            )
        );

        $data[] = array(
            array(
                'config'    => array(
                    'state' => 'wrong'
                )
            ),
            array(
                'case'       => 'State is set in the config (string - wrong), no populate, no ignore',
                'state'      => (object) array(),
                'populate'   => false,
                'ignore'     => false,
                'name'       => 'nestedset'
            )
        );

        $data[] = array(
            array(
                'config'    => array(
                    'state' => (object) array(
                        'dummy' => 'test'
                    )
                )
            ),
            array(
                'case'       => 'State is set in the config (object), no populate, no ignore',
                'state'      => (object) array(
                    'dummy' => 'test'
                ),
                'populate'   => false,
                'ignore'     => false,
                'name'       => 'nestedset'
            )
        );

        $data[] = array(
            array(
                'config'    => array(
                    'state' => (object) array(
                        'dummy' => 'test'
                    ),
                    'use_populate' => true,
                    'ignore_request' => true
                )
            ),
            array(
                'case'       => 'State is set in the config (object), with populate and ignore',
                'state'      => (object) array(
                    'dummy' => 'test'
                ),
                'populate'   => true,
                'ignore'     => true,
                'name'       => 'nestedset'
            )
        );

        $data[] = array(
            array(
                'config'    => array(
                    'state' => (object) array(
                        'dummy' => 'test'
                    ),
                    'use_populate'   => false,
                    'ignore_request' => false
                )
            ),
            array(
                'case'       => 'State is set in the config (object), with populate and ignore (they are set to false)',
                'state'      => (object) array(
                    'dummy' => 'test'
                ),
                'populate'   => false,
                'ignore'     => false,
                'name'       => 'nestedset'
            )
        );

        return $data;
    }

    public static function getTestGetState()
    {
        $data[] = array(
            array(
                'config'  => array(),
                'key'     => '',
                'default' => 'default',
                'filter'  => 'raw',
                'mock' => array(
                    'getUserState' => 'user state',
                    'ignore'       => false
                )
            ),
            array(
                'case'   => 'No key passed',
                'result' => new stdClass()
            )
        );

        $data[] = array(
            array(
                'config'  => array(
                    'state' => array(
                        'foobar' => 'internal state'
                    )
                ),
                'key'     => 'foobar',
                'default' => 'default',
                'filter'  => 'raw',
                'mock' => array(
                    'getUserState' => 'user state',
                    'ignore'       => false,
                )
            ),
            array(
                'case'   => 'Requesting a key, got it from the internal state',
                'result' => 'internal state'
            )
        );

        $data[] = array(
            array(
                'config'  => array(),
                'key'     => 'foobar',
                'default' => 'default',
                'filter'  => 'raw',
                'mock' => array(
                    'getUserState' => 'user state',
                    'ignore'       => false
                )
            ),
            array(
                'case'   => 'Requesting a key, got it form the request',
                'result' => 'user state'
            )
        );

        $data[] = array(
            array(
                'config'  => array(),
                'key'     => 'foobar',
                'default' => 'default',
                'filter'  => 'raw',
                'mock' => array(
                    'getUserState' => null,
                    'ignore'       => false
                )
            ),
            array(
                'case'   => 'Requesting a key, the request was empty',
                'result' => 'default'
            )
        );

        $data[] = array(
            array(
                'config'  => array(),
                'key'     => 'foobar',
                'default' => 'default',
                'filter'  => 'raw',
                'mock' => array(
                    'getUserState' => 'user state',
                    'ignore'       => true
                )
            ),
            array(
                'case'   => 'Requesting a key, not found in the internal state, ignore flag is on',
                'result' => 'default'
            )
        );

        $data[] = array(
            array(
                'config'  => array(
                    'state' => array(
                        'foobar' => 'internal state'
                    )
                ),
                'key'     => 'foobar',
                'default' => 'default',
                'filter'  => 'int',
                'mock' => array(
                    'getUserState' => 'user state',
                    'ignore'       => false,
                )
            ),
            array(
                'case'   => 'Requesting a key, got it from the internal state, int filter applied',
                'result' => 0
            )
        );

        return $data;
    }

    public static function getTestSetState()
    {
        $data[] = array(
            array(
                'property' => 'foo',
                'value'    => 'bar',
                'mock'     => array(
                    'state' => null
                )
            ),
            array(
                'case' => 'Setting a propery to a value, internal state is empty',
                'result' => 'bar',
                'state' => (object) array(
                    'foo' => 'bar'
                )
            )
        );

        $data[] = array(
            array(
                'property' => 'foo',
                'value'    => 'bar',
                'mock'     => array(
                    'state' => (object) array(
                        'dummy' => 'test'
                    )
                )
            ),
            array(
                'case' => 'Setting a propery to a value, internal state is not empty',
                'result' => 'bar',
                'state' => (object) array(
                    'foo' => 'bar',
                    'dummy' => 'test'
                )
            )
        );

        $data[] = array(
            array(
                'property' => 'foo',
                'value'    => 'bar',
                'mock'     => array(
                    'state' => (object) array(
                        'foo' => 'test'
                    )
                )
            ),
            array(
                'case' => 'Trying to overwrite a propery value, internal state is not empty',
                'result' => 'bar',
                'state' => (object) array(
                    'foo' => 'bar'
                )
            )
        );

        return $data;
    }

    public static function getTestSavestate()
    {
        $data[] = array(
            array(
                'state' => true
            ),
            array(
                'case'  => 'New state is boolean true',
                'state' => true
            )
        );

        $data[] = array(
            array(
                'state' => false
            ),
            array(
                'case'  => 'New state is boolean false',
                'state' => false
            )
        );

        $data[] = array(
            array(
                'state' => 1
            ),
            array(
                'case'  => 'New state is int 1',
                'state' => true
            )
        );

        $data[] = array(
            array(
                'state' => 0
            ),
            array(
                'case'  => 'New state is int 0',
                'state' => false
            )
        );

        return $data;
    }

    public static function getTestPopulatesavestate()
    {
        // Savestate is -999 => we are going to save the state
        $data[] = array(
            array(
                'state' => -999,
                'mock'  => array(
                    'state' => null
                )
            ),
            array(
                'savestate' => 1,
                'state'     => true
            )
        );

        // We already saved the state, nothing happens
        $data[] = array(
            array(
                'state' => -999,
                'mock'  => array(
                    'state' => true
                )
            ),
            array(
                'savestate' => 0,
                'state'     => null
            )
        );

        // Savestate is 1 => we are going to save the state
        $data[] = array(
            array(
                'state' => 1,
                'mock'  => array(
                    'state' => null
                )
            ),
            array(
                'savestate' => 1,
                'state'     => 1
            )
        );

        // Savestate is -1 => we are NOT going to save the state
        $data[] = array(
            array(
                'state' => -1,
                'mock'  => array(
                    'state' => null
                )
            ),
            array(
                'savestate' => 1,
                'state'     => -1
            )
        );

        return $data;
    }
}