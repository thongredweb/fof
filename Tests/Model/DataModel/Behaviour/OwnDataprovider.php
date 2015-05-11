<?php

class OwnDataprovider
{
    public static function getTestOnAfterBuildQuery()
    {
        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table'   => '#__foftest_foobars'
            ),
            array(
                'case' => 'Table with created_by column',
                'contains' => true,
                'query'  => "`created_by` = '99'"
            )
        );

        $data[] = array(
            array(
                'tableid' => 'foftest_bare_id',
                'table'   => '#__foftest_bares'
            ),
            array(
                'case' => 'Table without created_by column',
                'contains' => false,
                'query'  => "`created_by` = '99'"
            )
        );

        return $data;
    }

    public static function getTestOnAfterLoad()
    {
        // DataModel has not the created_by field
        $data[] = array(
            array(
                'tableid' => 'foftest_bare_id',
                'table'   => '#__foftest_bares',
                'mock'    => array(
                    'created_by'     => ''
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
                    'created_by'     => 99
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
                    'created_by'     => 10
                )
            ),
            array(
                'reset' => true
            )
        );

        return $data;
    }
}