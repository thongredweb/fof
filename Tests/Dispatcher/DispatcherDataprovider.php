<?php

class DispatcherDataprovider
{
    public static function getTest__construct()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'defaultView' => null,
                    'input' => array()
                )
            ),
            array(
                'case' => 'Nothing passed in the input, no default view',
                'defaultView' => 'main',
                'view' => 'main',
                'layout' => '',
                'containerView' => 'main'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'defaultView' => 'test',
                    'input' => array()
                )
            ),
            array(
                'case' => 'Nothing passed in the input, with default view',
                'defaultView' => 'test',
                'view' => 'test',
                'layout' => '',
                'containerView' => 'test'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'defaultView' => null,
                    'input' => array(
                        'view' => 'foobars',
                        'layout' => 'default'
                    )
                )
            ),
            array(
                'case' => 'Data passed in the input, no default view',
                'defaultView' => 'main',
                'view' => 'foobars',
                'layout' => 'default',
                'containerView' => 'foobars'
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
                'case'   => 'Requesting a non-existing property',
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestDispatch()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'input' => array(),
                    'execute' => true,
                    'before' => true,
                    'after' => true,
                    'isCli' => false,
                )
            ),
            array(
                'case' => 'Not in CLI, everything went fine',
                'exception' => false,
                'events' => array('before' => 1, 'after' => 1, 'beforeCli' => 0, 'afterCli' => 0)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'input' => array(),
                    'execute' => true,
                    'before' => 'throw',
                    'after' => true,
                    'isCli' => false
                )
            ),
            array(
                'case' => 'Not in CLI, onBefore throws an exception',
                'exception' => true,
                'events' => array('before' => 1, 'after' => 0, 'beforeCli' => 0, 'afterCli' => 0)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'input' => array(),
                    'execute' => false,
                    'before' => true,
                    'after' => true,
                    'isCli' => false
                )
            ),
            array(
                'case' => 'Not in CLI, execute returns false',
                'exception' => true,
                'events' => array('before' => 1, 'after' => 0, 'beforeCli' => 0, 'afterCli' => 0)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'input' => array(),
                    'execute' => true,
                    'before' => true,
                    'after' => 'throw',
                    'isCli' => false
                )
            ),
            array(
                'case' => 'Not in CLI, onAfter returns false',
                'exception' => true,
                'events' => array('before' => 1, 'after' => 1, 'beforeCli' => 0, 'afterCli' => 0)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'input' => array(),
                    'execute' => true,
                    'before' => true,
                    'after' => true,
                    'isCli' => true
                )
            ),
            array(
                'case' => 'In CLI, everything went fine',
                'exception' => false,
                'events' => array('before' => 0, 'after' => 0, 'beforeCli' => 1, 'afterCli' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'input' => array(),
                    'execute' => true,
                    'before' => 'throw',
                    'after' => true,
                    'isCli' => false
                )
            ),
            array(
                'case' => 'Not in CLI, onBefore throws an exception',
                'exception' => true,
                'events' => array('before' => 0, 'after' => 0, 'beforeCli' => 1, 'afterCli' => 0)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'input' => array(),
                    'execute' => false,
                    'before' => true,
                    'after' => true,
                    'isCli' => false
                )
            ),
            array(
                'case' => 'Not in CLI, execute returns false',
                'exception' => true,
                'events' => array('before' => 0, 'after' => 0, 'beforeCli' => 1, 'afterCli' => 0)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'input' => array(),
                    'execute' => true,
                    'before' => true,
                    'after' => 'throw',
                    'isCli' => false
                )
            ),
            array(
                'case' => 'Not in CLI, onAfter returns false',
                'exception' => true,
                'events' => array('before' => 0, 'after' => 0, 'beforeCli' => 1, 'afterCli' => 1)
            )
        );

        return $data;
    }
}