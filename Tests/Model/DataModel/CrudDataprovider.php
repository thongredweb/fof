<?php

class DataModelCrudDataprovider
{
    public static function getTestSave()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'dataSave'   => null,
                    'dataCreate' => null,
                    'dataUpdate' => null,
                    'blankId'    => false
                ),
                'id'        => 1,
                'table_id'  => 'foftest_bare_id',
                'table'     => '#__foftest_bares',
                'relations' => null,
                'data'      => array('title' => 'foobar'),
                'ordering'  => '',
                'ignore'    => null
            ),
            array(
                'case'        => 'Updating object without any "special" field',
                'reorder'     => false,
                'modelEvents' => array('onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1),
                'dispEvents'  => array('onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1),
                'id'          => 1,
                'row'         => (object) array('foftest_bare_id' => 1, 'title' => 'foobar'),
                'created_on'  => false,
                'modified_on' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'dataSave'   => array('title' => 'foobar'),
                    'dataCreate' => null,
                    'dataUpdate' => null,
                    'blankId'    => false
                ),
                'id'        => 1,
                'table_id'  => 'foftest_bare_id',
                'table'     => '#__foftest_bares',
                'relations' => null,
                'data'      => null,
                'ordering'  => '',
                'ignore'    => null
            ),
            array(
                'case'        => 'Updating the record, change the data in the onBeforeSave dispatcher event',
                'reorder'     => false,
                'modelEvents' => array('onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1),
                'dispEvents'  => array('onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1),
                'id'          => 1,
                'row'         => (object) array('foftest_bare_id' => 1, 'title' => 'foobar'),
                'created_on'  => false,
                'modified_on' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'dataSave'   => null,
                    'dataCreate' => null,
                    'dataUpdate' => array('title' => 'foobar'),
                    'blankId'    => false
                ),
                'id'        => 1,
                'table_id'  => 'foftest_bare_id',
                'table'     => '#__foftest_bares',
                'relations' => null,
                'data'      => null,
                'ordering'  => '',
                'ignore'    => null
            ),
            array(
                'case'        => 'Updating the record, change the data in the dispatcher event',
                'reorder'     => false,
                'modelEvents' => array('onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1),
                'dispEvents'  => array('onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1),
                'id'          => 1,
                'row'         => (object) array('foftest_bare_id' => 1, 'title' => 'foobar'),
                'created_on'  => false,
                'modified_on' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'dataSave'   => null,
                    'dataCreate' => null,
                    'dataUpdate' => null,
                    'blankId'    => true
                ),
                'id'        => 1,
                'table_id'  => 'foftest_bare_id',
                'table'     => '#__foftest_bares',
                'relations' => null,
                'data'      => null,
                'ordering'  => '',
                'ignore'    => null
            ),
            array(
                'case'        => 'Blank out the id before saving the record (dispatcher event)',
                'reorder'     => false,
                'modelEvents' => array('onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1),
                'dispEvents'  => array('onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1),
                'id'          => 'max',
                'row'         => (object) array('title' => 'First Row'),
                'created_on'  => false,
                'modified_on' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'dataSave'   => null,
                    'dataCreate' => null,
                    'dataUpdate' => null,
                    'blankId'    => false
                ),
                'id'        => null,
                'table_id'  => 'foftest_bare_id',
                'table'     => '#__foftest_bares',
                'relations' => null,
                'data'      => array('title' => 'foobar'),
                'ordering'  => '',
                'ignore'    => null
            ),
            array(
                'case'        => 'Inserting a new record without any "special" field',
                'reorder'     => false,
                'modelEvents' => array('onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1),
                'dispEvents'  => array('onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1),
                'id'          => 'max',
                'row'         => (object) array('title' => 'foobar'),
                'created_on'  => false,
                'modified_on' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'dataSave'   => null,
                    'dataCreate' => array('title' => 'foobar'),
                    'dataUpdate' => null,
                    'blankId'    => false
                ),
                'id'        => null,
                'table_id'  => 'foftest_bare_id',
                'table'     => '#__foftest_bares',
                'relations' => null,
                'data'      => null,
                'ordering'  => '',
                'ignore'    => null
            ),
            array(
                'case'        => 'Inserting a new record, changing the data in the onBeforeCreate dispatcher event',
                'reorder'     => false,
                'modelEvents' => array('onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1),
                'dispEvents'  => array('onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1),
                'id'          => 'max',
                'row'         => (object) array('title' => 'foobar'),
                'created_on'  => false,
                'modified_on' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'dataSave'   => null,
                    'dataCreate' => null,
                    'dataUpdate' => null,
                    'blankId'    => false
                ),
                'id'        => 1,
                'table_id'  => 'foftest_foobar_id',
                'table'     => '#__foftest_foobars',
                'relations' => null,
                'data'      => array('title' => 'foobar'),
                'ordering'  => '',
                'ignore'    => null
            ),
            array(
                'case'        => 'Updating object with special field',
                'reorder'     => false,
                'modelEvents' => array('onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1),
                'dispEvents'  => array('onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1),
                'id'          => 1,
                'row'         => (object) array(
                    'foftest_foobar_id' => 1,
                    'title' => 'foobar',
                    // Slug is not updated since we are moking the dispatcher events
                    'slug' => 'guinea-pig-row',
                    'enabled' => 1,
                    'ordering' => 1,
                    'hits' => 0,
                    'asset_id' => 0,
                    'access' => 2,
                    'locked_on' => '0000-00-00 00:00:00',
                    'locked_by' => 0,
                    'created_by' => 42,
                    'modified_by' => 99,
                    'created_on' => '0000-00-00 00:00:00'
                ),
                'created_on'  => false,
                'modified_on' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'dataSave'   => null,
                    'dataCreate' => null,
                    'dataUpdate' => null,
                    'blankId'    => false
                ),
                'id'        => 1,
                'table_id'  => 'foftest_foobar_id',
                'table'     => '#__foftest_foobars',
                'relations' => null,
                'data'      => array('title' => 'foobar'),
                'ordering'  => 'ordering',
                'ignore'    => null
            ),
            array(
                'case'        => 'Updating object with special field, passing an ordering field',
                'reorder'     => "`ordering` = '1'",
                'modelEvents' => array('onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1),
                'dispEvents'  => array('onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1),
                'id'          => 1,
                'row'         => (object) array(
                    'foftest_foobar_id' => 1,
                    'title' => 'foobar',
                    // Slug is not updated since we are moking the dispatcher events
                    'slug' => 'guinea-pig-row',
                    'enabled' => 1,
                    'ordering' => 1,
                    'hits' => 0,
                    'asset_id' => 0,
                    'access' => 2,
                    'locked_on' => '0000-00-00 00:00:00',
                    'locked_by' => 0,
                    'created_by' => 42,
                    'modified_by' => 99,
                    'created_on' => '0000-00-00 00:00:00'
                ),
                'created_on'  => false,
                'modified_on' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'dataSave'   => null,
                    'dataCreate' => null,
                    'dataUpdate' => null,
                    'blankId'    => false
                ),
                'id'        => null,
                'table_id'  => 'foftest_foobar_id',
                'table'     => '#__foftest_foobars',
                'relations' => null,
                'data'      => array('title' => 'foobar'),
                'ordering'  => '',
                'ignore'    => null
            ),
            array(
                'case'        => 'Inserting a new record with special field',
                'reorder'     => false,
                'modelEvents' => array('onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1),
                'dispEvents'  => array('onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1),
                'id'          => 'max',
                'row'         => (object) array(
                    'title' => 'foobar',
                    // Slug is not updated since we are moking the dispatcher events
                    'slug' => '',
                    'enabled' => 0,
                    'ordering' => 0,
                    'hits' => 0,
                    'asset_id' => 0,
                    'access' => 0,
                    'locked_on' => '0000-00-00 00:00:00',
                    'locked_by' => 0,
                    'created_by' => 99,
                    'modified_by' => 0,
                    'modified_on' => '0000-00-00 00:00:00'
                ),
                'created_on'  => true,
                'modified_on' => false
            )
        );

        return $data;
    }

    public static function getTestBind()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'beforeDisp' => null
                ),
                'data' => array(
                    'foftest_bare_id' => 1,
                    'title' => 'test'
                ),
                'ignore'  => array(),
                'tableid' => 'foftest_bare_id',
                'table'   => '#__foftest_bares'
            ),
            array(
                'case' => 'Data array contains properties that exists',
                'dispatcher' => 2,
                'bind' => array('foftest_bare_id' => 1, 'title' => 'test')
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'beforeDisp' => null
                ),
                'data' => array(
                    'foftest_bare_id' => 1,
                    'title' => 'test'
                ),
                'ignore' => array('title'),
                'tableid' => 'foftest_bare_id',
                'table'   => '#__foftest_bares'
            ),
            array(
                'case' => 'Data array contains properties that exists, ignoring some of them (array format)',
                'dispatcher' => 2,
                'bind' => array('foftest_bare_id' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'beforeDisp' => null
                ),
                'data' => array(
                    'foftest_foobar_id' => 1,
                    'title' => 'test',
                    'enabled' => 1
                ),
                'ignore' => 'title enabled',
                'tableid' => 'foftest_foobar_id',
                'table'   => '#__foftest_foobars'
            ),
            array(
                'case' => 'Data array contains properties that exists, ignoring some of them (string format)',
                'dispatcher' => 2,
                'bind' => array('foftest_foobar_id' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'beforeDisp' => null
                ),
                'data' => array(
                    'foftest_bare_id' => 1,
                    'title' => 'test',
                    'foobar' => 'foo'
                ),
                'ignore' => array(),
                'tableid' => 'foftest_bare_id',
                'table'   => '#__foftest_bares'
            ),
            array(
                'case' => 'Trying to bind a property that does not exist',
                'dispatcher' => 2,
                'bind' => array('foftest_bare_id' => 1, 'title' => 'test')
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'beforeDisp' => array(
                        'foftest_bare_id' => 1,
                        'title' => 'test',
                    )
                ),
                'data' => null,
                'ignore' => array(),
                'tableid' => 'foftest_bare_id',
                'table'   => '#__foftest_bares'
            ),
            array(
                'case' => 'Passing invalid data, however the onBeforeBind converts it to a valid one',
                'dispatcher' => 2,
                'bind' => array('foftest_bare_id' => 1, 'title' => 'test')
            )
        );

        return $data;
    }

    public static function getTestBindException()
    {
        $data[] = array(
            array(
                'data' => ''
            )
        );

        $data[] = array(
            array(
                'data' => 1
            )
        );

        $data[] = array(
            array(
                'data' => null
            )
        );

        $data[] = array(
            array(
                'data' => false
            )
        );

        return $data;
    }

    public static function getTestCheck()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'auto' => false
                ),
                'load'  => null
            ),
            array(
                'case' => 'No autochecks set',
                'exception' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'auto' => true
                ),
                'load'  => 1
            ),
            array(
                'case' => 'Table loaded',
                'exception' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'auto' => true
                ),
                'load'  => null
            ),
            array(
                'case' => 'Check failed',
                'exception' => 'COM_FAKEAPP_NESTEDSET_ERR_TITLE_EMPTY'
            )
        );

        return $data;
    }

    public static function getTestDelete()
    {
        $data[] = array(
            array(
                'id'   => null,
                'soft' => true
            ),
            array(
                'case' => 'Id not provided, soft delete',
                'trash' => true,
                'force' => false
            )
        );

        $data[] = array(
            array(
                'id'   => null,
                'soft' => false
            ),
            array(
                'case' => 'Id not provided, db delete',
                'trash' => false,
                'force' => true
            )
        );

        $data[] = array(
            array(
                'id'   => 2,
                'soft' => true
            ),
            array(
                'case' => 'Id provided, soft delete',
                'trash' => true,
                'force' => false
            )
        );

        $data[] = array(
            array(
                'id'   => 2,
                'soft' => false
            ),
            array(
                'case' => 'Id provided, db delete',
                'trash' => false,
                'force' => true
            )
        );

        return $data;
    }

    public static function getTestFindOrFail()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'getId' => 1
                ),
                'keys' => null
            ),
            array(
                'case' => 'Record found, not passing any keys',
                'exception' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getId' => null
                ),
                'keys' => null
            ),
            array(
                'case' => 'Record not found, not passing any keys',
                'exception' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getId' => 1
                ),
                'keys' => 1
            ),
            array(
                'case' => 'Record found, passing keys',
                'exception' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getId' => null
                ),
                'keys' => 1
            ),
            array(
                'case' => 'Record not found, passing keys',
                'exception' => true
            )
        );

        return $data;
    }

    public static function getTestFind()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'id'       => '',
                    'state_id' => 0,
                    'keys'     => null
                ),
                'keys' => 1
            ),
            array(
                'case' => 'Passing the record id',
                'bind' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id'       => 0,
                    'state_id' => 1,
                    'keys'     => null
                ),
                'keys' => ''
            ),
            array(
                'case' => 'No argument, no object id, getting it from the state',
                'bind' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id'       => 1,
                    'state_id' => 0,
                    'keys'     => null
                ),
                'keys' => ''
            ),
            array(
                'case' => 'No argument, getting the id from the object',
                'bind' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id'       => null,
                    'state_id' => 0,
                    'keys'     => 1
                ),
                'keys' => ''
            ),
            array(
                'case' => 'No argument, getting the id from the event dispatcher',
                'bind' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id'       => null,
                    'state_id' => 0,
                    'keys'     => null
                ),
                'keys' => null
            ),
            array(
                'case' => 'No key set anywhere',
                'bind' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id'       => null,
                    'state_id' => 0,
                    'keys'     => null
                ),
                'keys' => array(
                    'title' => 'First Row'
                )
            ),
            array(
                'case' => 'Passing an indexed array',
                'bind' => true
            )
        );

        return $data;
    }

    public static function getTestForceDelete()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'id' => 1
                ),
                'id' => 1
            ),
            array(
                'case' => 'Passing the id',
                'id'   => 1,
                'find' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id' => 1
                ),
                'id' => null
            ),
            array(
                'case' => 'Loaded record',
                'id'   => 1,
                'find' => false
            )
        );

        return $data;
    }

    public static function getTestFirstOrCreate()
    {
        $data[] = array(
            array(
                'mock' => array(
                    // I just need to return any value to flag the record as loaded
                    'first' => 'foobar'
                )
            ),
            array(
                'case' => 'I was able to get first record',
                'create' => false,
                'result' => 'foobar'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'first' => null
                )
            ),
            array(
                'case' => "I couldn't get the first record",
                'create' => true,
                'result' => 'object'
            )
        );

        return $data;
    }

    public static function getTestFirstOrFail()
    {
        $data[] = array(
            array(
                'mock' => array(
                    // I just need to return any value to flag the record as loaded
                    'first' => 'foobar'
                )
            ),
            array(
                'case' => 'I was able to get first record',
                'exception' => false,
                'result' => 'foobar'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'first' => null
                )
            ),
            array(
                'case' => "I couldn't get the first record",
                'exception' => true,
                'result' => ''
            )
        );

        return $data;
    }

    public static function getTestFirstOrNew()
    {
        $data[] = array(
            array(
                'mock' => array(
                    // I just need to return any value to flag the record as loaded
                    'first' => 'foobar'
                )
            ),
            array(
                'case' => 'I was able to get first record',
                'reset' => false,
                'result' => 'foobar'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'first' => null
                )
            ),
            array(
                'case' => "I couldn't get the first record",
                'reset' => true,
                'result' => 'object'
            )
        );

        return $data;
    }

    public static function getTestStore()
    {
        $data[] = array(
            array(
                'exception' => false
            ),
            array(
                'result' => true
            )
        );

        $data[] = array(
            array(
                'exception' => true
            ),
            array(
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestLoad()
    {
        $data[] = array(
            array(
                'reset'     => false,
                'exception' => false
            ),
            array(
                'reset'  => false,
                'result' => true
            )
        );

        $data[] = array(
            array(
                'reset'     => true,
                'exception' => true
            ),
            array(
                'reset'  => true,
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestCanDelete()
    {
        // Empty join array
        $data[] = array(
            array(
                'id'    => 1,
                'load'  => 0,
                'joins' => array()
            ),
            array(
                'exception' => false,
                'message'   => ''
            )
        );

        // Joins are not defined
        $data[] = array(
            array(
                'id'    => 1,
                'load'  => 0,
                'joins' => null
            ),
            array(
                'exception' => false,
                'message'   => ''
            )
        );

        // Parent with 1 child - ID passed
        $data[] = array(
            array(
                'id'    => 1,
                'load'  => 0,
                'joins' => array(
                    array(
                        'idfield'   => 'fakeapp_child_id',
                        'idalias'   => 'child_id',
                        'name'      => '#__fakeapp_children',
                        'joinfield' => 'fakeapp_parent_id',
                        'label'     => 'CHILD'
                    )
                )
            ),
            array(
                'exception' => '\RuntimeException',
                'message'   => '<ul><li>COM_FAKEAPP_PARENTS_NODELETE_CHILD</li></ul>'
            )
        );

        // Parent with 1 child - Loaded table
        $data[] = array(
            array(
                'id'    => 0,
                'load'  => 1,
                'joins' => array(
                    array(
                        'idfield'   => 'fakeapp_child_id',
                        'idalias'   => 'child_id',
                        'name'      => '#__fakeapp_children',
                        'joinfield' => 'fakeapp_parent_id',
                        'label'     => 'CHILD'
                    )
                )
            ),
            array(
                'exception' => '\RuntimeException',
                'message'   => '<ul><li>COM_FAKEAPP_PARENTS_NODELETE_CHILD</li></ul>'
            )
        );

        // Parent with no children - delete allowed
        $data[] = array(
            array(
                'id'    => 3,
                'load'  => 0,
                'joins' => array(
                    array(
                        'idfield'   => 'fakeapp_child_id',
                        'idalias'   => 'child_id',
                        'name'      => '#__fakeapp_children',
                        'joinfield' => 'fakeapp_parent_id',
                        'label'     => 'CHILD'
                    )
                )
            ),
            array(
                'exception' => false,
                'message'   => ''
            )
        );

        // Join array missing some fields
        $data[] = array(
            array(
                'id'    => 3,
                'load'  => 0,
                'joins' => array(
                    array(
                        'idfield'   => 'fakeapp_child_id',
                        'idalias'   => 'child_id',
                        'name'      => '#__fakeapp_children',
                        'joinfield' => 'fakeapp_parent_id',
                    )
                )
            ),
            array(
                'exception' => '\InvalidArgumentException',
                'message'   => ''
            )
        );

        // Table not loaded an no id is passed
        $data[] = array(
            array(
                'id'    => 0,
                'load'  => 0,
                'joins' => array()
            ),
            array(
                'exception' => '\InvalidArgumentException',
                'message'   => ''
            )
        );

        return $data;
    }
}