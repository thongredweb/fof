<?php

class PublishDataprovider
{
    public static function getTestArchive()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'before' => '',
                    'after'  => '',
                    'alias'  => array()
                ),
                'tableid' => 'foftest_bare_id',
                'table' => '#__foftest_bares'
            ),
            array(
                'case'       => 'Table with no enabled field',
                'dispatcher' => 0,
                'save'       => false,
                'exception'  => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'before' => '',
                    'after'  => '',
                    'alias'  => array(
                        'enabled' => 'fo_enabled'
                    )
                ),
                'tableid' => 'id_foobar_aliases',
                'table' => '#__foftest_foobaraliases'
            ),
            array(
                'case'       => 'Table with enabled field (alias)',
                'dispatcher' => 2,
                'save'       => true,
                'exception'  => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'before' => '',
                    'after'  => '',
                    'alias'  => array()
                ),
                'tableid' => 'foftest_foobar_id',
                'table' => '#__foftest_foobars'
            ),
            array(
                'case'       => 'Table with enabled field',
                'dispatcher' => 2,
                'save'       => true,
                'exception'  => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'before' => function(){ return false;},
                    'after'  => '',
                    'alias'  => array()
                ),
                'tableid' => 'foftest_foobar_id',
                'table' => '#__foftest_foobars'
            ),
            array(
                'case'       => 'Table with enabled field, onBefore returns false',
                'dispatcher' => 2,
                'save'       => true,
                'exception'  => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'before' => function(){ return true;},
                    'after'  => function(){ return false;},
                    'alias'  => array()
                ),
                'tableid' => 'foftest_foobar_id',
                'table' => '#__foftest_foobars'
            ),
            array(
                'case'       => 'Table with enabled field, onAfter returns false',
                'dispatcher' => 2,
                'save'       => true,
                'exception'  => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'before' => function(){ throw new \Exception();},
                    'after'  => function(){ return false;},
                    'alias'  => array()
                ),
                'tableid' => 'foftest_foobar_id',
                'table' => '#__foftest_foobars'
            ),
            array(
                'case'       => 'Table with enabled field, onBefore throws an exception',
                'dispatcher' => 0,
                'save'       => false,
                'exception'  => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'before' => function(){ return true;},
                    'after'  => function(){ throw new \Exception();},
                    'alias'  => array()
                ),
                'tableid' => 'foftest_foobar_id',
                'table' => '#__foftest_foobars'
            ),
            array(
                'case'       => 'Table with enabled field, onAfter throws an exception',
                'dispatcher' => 1,
                'save'       => true,
                'exception'  => true
            )
        );

        return $data;
    }

    public static function getTestTrash()
    {
        $data[] = array(
            array(
                'id' => null
            ),
            array(
                'case'   => 'Table with publish support, already loaded',
                'before' => 1,
                'after'  => 1,
                'find'   => false,
                'dispatcher' => 2,
                'enabled' => -2
            )
        );

        $data[] = array(
            array(
                'id' => 1
            ),
            array(
                'case'   => 'Table with publish support, not loaded',
                'before' => 1,
                'after'  => 1,
                'find'   => true,
                'dispatcher' => 2,
                'enabled' => -2
            )
        );

        return $data;
    }

    public static function getTestTrashException()
    {
        $data[] = array(
            array(
                'tableid' => 'foftest_bare_id',
                'table' => '#__foftest_bares',
                'id' => 1
            ),
            array(
                'case'      => 'Table with no publish support',
                'exception' => 'FOF30\\Model\\DataModel\\Exception\\SpecialColumnMissing'
            )
        );

        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table' => '#__foftest_foobars',
                'id' => null
            ),
            array(
                'case'      => 'Table not loaded',
                'exception' => 'FOF30\Model\DataModel\Exception\RecordNotLoaded'
            )
        );

        return $data;
    }

    public static function getTestPublish()
    {
        $data[] = array(
            array(
                'tableid' => 'foftest_bare_id',
                'table' => '#__foftest_bares',
                'state' => 1
            ),
            array(
                'case'    => 'Table with no publish support',
                'dispatcher' => 0,
                'before'  => 0,
                'after'   => 0,
                'enabled' => null
            )
        );

        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table' => '#__foftest_foobars',
                'state' => 1
            ),
            array(
                'case'    => 'Table with publish support (record enabling)',
                'dispatcher' => 2,
                'before'  => 1,
                'after'   => 1,
                'enabled' => 1
            )
        );

        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table' => '#__foftest_foobars',
                'state' => 0
            ),
            array(
                'case'    => 'Table with publish support (record disabling)',
                'dispatcher' => 2,
                'before'  => 1,
                'after'   => 1,
                'enabled' => 0
            )
        );

        return $data;
    }

    public static function getTestRestore()
    {
        $data[] = array(
            array(
                'tableid' => 'foftest_bare_id',
                'table' => '#__foftest_bares',
                'id' => ''
            ),
            array(
                'case'   => 'Table with no publish support',
                'before' => 0,
                'after'  => 0,
                'find'   => false,
                'dispatcher' => 0,
                'enabled' => null
            )
        );

        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table' => '#__foftest_foobars',
                'id' => null
            ),
            array(
                'case'   => 'Table with publish support, already loaded',
                'before' => 1,
                'after'  => 1,
                'find'   => false,
                'dispatcher' => 2,
                'enabled' => 0
            )
        );

        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table' => '#__foftest_foobars',
                'id' => 1
            ),
            array(
                'case'   => 'Table with publish support, not loaded',
                'before' => 1,
                'after'  => 1,
                'find'   => true,
                'dispatcher' => 2,
                'enabled' => 0
            )
        );

        return $data;
    }

    public static function getTestUnpublish()
    {
        $data[] = array(
            array(
                'tableid' => 'foftest_bare_id',
                'table' => '#__foftest_bares',
            ),
            array(
                'case'   => 'Table with no publish support',
                'before' => 0,
                'after'  => 0,
                'dispatcher' => 0,
                'enabled' => null
            )
        );

        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table' => '#__foftest_foobars',
            ),
            array(
                'case'   => 'Table with publish support',
                'before' => 1,
                'after'  => 1,
                'dispatcher' => 2,
                'enabled' => 0
            )
        );

        return $data;
    }
}