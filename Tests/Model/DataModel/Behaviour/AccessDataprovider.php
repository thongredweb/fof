<?php

class AccessDataprovider
{
    public static function getTestOnAfterBuildQuery()
    {
        $data[] = array(
            array(
                'tableid' => 'foftest_bare_id',
                'table'   => '#__foftest_bares'
            ),
            array(
                'access' => false
            )
        );

        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table'   => '#__foftest_foobars'
            ),
            array(
                'access' => true
            )
        );

        return $data;
    }

    public static function getTestOnAfterLoad()
    {
        // DataModel has not the access field
        $data[] = array(
            array(
                'tableid' => 'foftest_bare_id',
                'table'   => '#__foftest_bares',
                'mock'    => array(
                    'userAccess' => '',
                    'access'     => ''
                )
            ),
            array(
                'reset' => false
            )
        );

        // User has access to the data
        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table'   => '#__foftest_foobars',
                'mock'    => array(
                    'userAccess' => array(10,5,1),
                    'access'     => 10
                )
            ),
            array(
                'reset' => false
            )
        );

        // No access
        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table'   => '#__foftest_foobars',
                'mock'    => array(
                    'userAccess' => array(5,1),
                    'access'     => 10
                )
            ),
            array(
                'reset' => true
            )
        );

        return $data;
    }
}