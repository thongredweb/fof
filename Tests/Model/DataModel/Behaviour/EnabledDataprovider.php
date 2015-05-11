<?php

class EnabledDataprovider
{
    public static function getTestOnBeforeBuildQuery()
    {
        $data[] = array(
            array(
                'table'   => '#__foftest_bares',
                'tableid' => 'foftest_bare_id'
            ),
            array(
                'case'  => 'Table without enabled field',
                'count' => 0
            )
        );

        $data[] = array(
            array(
                'table'   => '#__foftest_foobars',
                'tableid' => 'foftest_foobar_id'
            ),
            array(
                'case'  => 'Table with enabled field',
                'count' => 1
            )
        );

        return $data;
    }

    public static function getTestOnAfterLoad()
    {
        // DataModel has not the enabled field
        $data[] = array(
            array(
                'tableid' => 'foftest_bare_id',
                'table'   => '#__foftest_bares',
                'mock'    => array(
                    'enabled'     => 0
                )
            ),
            array(
                'reset' => false
            )
        );

        // Enabled
        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table'   => '#__foftest_foobars',
                'mock'    => array(
                    'enabled'     => 1
                )
            ),
            array(
                'reset' => false
            )
        );

        // Not enabled
        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table'   => '#__foftest_foobars',
                'mock'    => array(
                    'enabled'     => 0
                )
            ),
            array(
                'reset' => true
            )
        );

        return $data;
    }
}