<?php

class DataModelGenericDataprovider
{
    public static function getTestGetTableFields()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'tables'     => null,
                    'tableName'  => null
                ),
                'table' => '#__foftest_bares'
            ),
            array(
                'case' => 'Table exists, abstract name, loaded cache',
                'result' => array(
                    'foftest_bare_id' => (object) array(
                        'Field' => 'foftest_bare_id',
                        'Type' => 'int(11)',
                        'Collation' => null,
                        'Null' => 'NO',
                        'Key' => 'PRI',
                        'Default' => null,
                        'Extra' => 'auto_increment',
                        'Privileges' => 'select,insert,update,references',
                        'Comment' => null
                    ),
                    'title' => (object) array(
                        'Field' => 'title',
                        'Type' => 'varchar(100)',
                        'Collation' => 'utf8_general_ci',
                        'Null' => 'NO',
                        'Key' => null,
                        'Default' =>null,
                        'Extra' => null,
                        'Privileges' => 'select,insert,update,references',
                        'Comment' => null
                    )
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'tables'     => null,
                    'tableName'  => '#__foftest_bares'
                ),
                'table' => null
            ),
            array(
                'case' => 'Table exists, abstract name, loaded cache, table name got from the object',
                'result' => array(
                    'foftest_bare_id' => (object) array(
                        'Field' => 'foftest_bare_id',
                        'Type' => 'int(11)',
                        'Collation' => null,
                        'Null' => 'NO',
                        'Key' => 'PRI',
                        'Default' => null,
                        'Extra' => 'auto_increment',
                        'Privileges' => 'select,insert,update,references',
                        'Comment' => null
                    ),
                    'title' => (object) array(
                        'Field' => 'title',
                        'Type' => 'varchar(100)',
                        'Collation' => 'utf8_general_ci',
                        'Null' => 'NO',
                        'Key' => null,
                        'Default' =>null,
                        'Extra' => null,
                        'Privileges' => 'select,insert,update,references',
                        'Comment' => null
                    )
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'tables'     => null,
                    'tableName'  => null
                ),
                'table' => '#__wrong'
            ),
            array(
                'case' => 'Table does not exist, abstract name, loaded cache',
                'result' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'tables'     => null,
                    'tableName'  => null
                ),
                'table' => 'jos_foftest_bares'
            ),
            array(
                'case' => 'Table exists, actual name, loaded cache',
                'result' => array(
                    'foftest_bare_id' => (object) array(
                        'Field' => 'foftest_bare_id',
                        'Type' => 'int(11)',
                        'Collation' => null,
                        'Null' => 'NO',
                        'Key' => 'PRI',
                        'Default' => null,
                        'Extra' => 'auto_increment',
                        'Privileges' => 'select,insert,update,references',
                        'Comment' => null
                    ),
                    'title' => (object) array(
                        'Field' => 'title',
                        'Type' => 'varchar(100)',
                        'Collation' => 'utf8_general_ci',
                        'Null' => 'NO',
                        'Key' => null,
                        'Default' =>null,
                        'Extra' => null,
                        'Privileges' => 'select,insert,update,references',
                        'Comment' => null
                    )
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'tables'     => 'nuke',
                    'tableName'  => null
                ),
                'table' => '#__foftest_bares'
            ),
            array(
                'case' => 'Table exists, abstract name, clean cache',
                'result' => array(
                    'foftest_bare_id' => (object) array(
                        'Field' => 'foftest_bare_id',
                        'Type' => 'int(11)',
                        'Collation' => null,
                        'Null' => 'NO',
                        'Key' => 'PRI',
                        'Default' => null,
                        'Extra' => 'auto_increment',
                        'Privileges' => 'select,insert,update,references',
                        'Comment' => null
                    ),
                    'title' => (object) array(
                        'Field' => 'title',
                        'Type' => 'varchar(100)',
                        'Collation' => 'utf8_general_ci',
                        'Null' => 'NO',
                        'Key' => null,
                        'Default' =>null,
                        'Extra' => null,
                        'Privileges' => 'select,insert,update,references',
                        'Comment' => null
                    )
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'tables'     => array(
                        '#__foftest_bares' => 'unset'
                    ),
                    'tableName'  => null
                ),
                'table' => '#__foftest_bares'
            ),
            array(
                'case' => 'Table exists, abstract name, table not inside the cache',
                'result' => array(
                    'foftest_bare_id' => (object) array(
                        'Field' => 'foftest_bare_id',
                        'Type' => 'int(11)',
                        'Collation' => null,
                        'Null' => 'NO',
                        'Key' => 'PRI',
                        'Default' => null,
                        'Extra' => 'auto_increment',
                        'Privileges' => 'select,insert,update,references',
                        'Comment' => null
                    ),
                    'title' => (object) array(
                        'Field' => 'title',
                        'Type' => 'varchar(100)',
                        'Collation' => 'utf8_general_ci',
                        'Null' => 'NO',
                        'Key' => null,
                        'Default' =>null,
                        'Extra' => null,
                        'Privileges' => 'select,insert,update,references',
                        'Comment' => null
                    )
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'tables'     => array(
                        '#__foftest_bares' => false
                    ),
                    'tableName'  => null
                ),
                'table' => '#__foftest_bares'
            ),
            array(
                'case' => 'Table exists, abstract name, table had a false value inside the cache',
                'result' => array(
                    'foftest_bare_id' => (object) array(
                        'Field' => 'foftest_bare_id',
                        'Type' => 'int(11)',
                        'Collation' => null,
                        'Null' => 'NO',
                        'Key' => 'PRI',
                        'Default' => null,
                        'Extra' => 'auto_increment',
                        'Privileges' => 'select,insert,update,references',
                        'Comment' => null
                    ),
                    'title' => (object) array(
                        'Field' => 'title',
                        'Type' => 'varchar(100)',
                        'Collation' => 'utf8_general_ci',
                        'Null' => 'NO',
                        'Key' => null,
                        'Default' =>null,
                        'Extra' => null,
                        'Privileges' => 'select,insert,update,references',
                        'Comment' => null
                    )
                )
            )
        );

        return $data;
    }

    public static function getTestGetDbo()
    {
        $data[] = array(
            array(
                'nuke' => false
            ),
            array(
                'case' => 'The internal db pointer is an object',
                'dbCounter' => 0
            )
        );

        $data[] = array(
            array(
                'nuke' => true
            ),
            array(
                'case' => 'The internal db pointer is not an object, getting from the container',
                'dbCounter' => 1
            )
        );

        return $data;
    }

    public static function getTestSetFieldValue()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'alias' => array()
                ),
                'name'  => 'title',
                'value' => 'bar'
            ),
            array(
                'case'  => 'Setting a method, no alias',
                'key'   => 'title',
                'value' => 'bar'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'alias' => array(
                        'foo' => 'title'
                    )
                ),
                'name'  => 'foo',
                'value' => 'bar'
            ),
            array(
                'case'  => 'Setting a method, with alias',
                'key'   => 'title',
                'value' => 'bar'
            )
        );

        return $data;
    }

    public static function getTestReset()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'recordData'      => array('foftest_bare_id' => null, 'title' => null),
                    'eagerRelations'  => array(),
                    'relationFilters' => array()
                ),
                'table_id'  => 'foftest_bare_id',
                'table'     => '#__foftest_bares',
                'default'   => true,
                'relations' => false
            ),
            array(
                'case'           => 'Table with no defaults, no relations nor filters. Resetting to default, not resetting the relations',
                'resetRelations' => false,
                'eager'          => array(),
                'data'           => array(
                    'foftest_bare_id'  => null,
                    'title'            => null
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'recordData'      => array('foftest_bare_id' => null, 'title' => null),
                    'eagerRelations'  => array(),
                    'relationFilters' => array()
                ),
                'table_id'  => 'foftest_bare_id',
                'table'     => '#__foftest_bares',
                'default'   => true,
                'relations' => false
            ),
            array(
                'case'           => 'Table with no defaults, no relations nor filters. Resetting to default, not resetting the relations. Additional fields set',
                'resetRelations' => false,
                'eager'          => array(),
                'data'           => array(
                    'foftest_bare_id'  => null,
                    'title'            => null
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'recordData'      => array('foftest_bare_id' => null, 'title' => null),
                    'eagerRelations'  => array(),
                    'relationFilters' => array()
                ),
                'table_id'  => 'foftest_bare_id',
                'table'     => '#__foftest_bares',
                'default'   => false,
                'relations' => false
            ),
            array(
                'case'           => 'Table with no defaults, no relations nor filters. Not resetting to default, not resetting the relations',
                'resetRelations' => false,
                'eager'          => array(),
                'data'           => array(
                    'foftest_bare_id'  => null,
                    'title'            => null
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'recordData'      => array('id' => null, 'title' => null, 'start_date' => null, 'description' => null),
                    'eagerRelations'  => array(),
                    'relationFilters' => array()
                ),
                'table_id'  => 'id',
                'table'     => '#__foftest_defaults',
                'default'   => true,
                'relations' => false
            ),
            array(
                'case'           => 'Table with defaults, no relations nor filters. Resetting to defaults, not resetting the relations',
                'resetRelations' => false,
                'eager'          => array(),
                'data'           => array(
                    'id'          => null,
                    'title'       => 'dummy',
                    'start_date'  => '0000-00-00 00:00:00',
                    'description' => null
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'recordData'      => array('id' => null, 'title' => null, 'start_date' => null, 'description' => null),
                    'eagerRelations'  => array(),
                    'relationFilters' => array()
                ),
                'table_id'  => 'id',
                'table'     => '#__foftest_defaults',
                'default'   => false,
                'relations' => false
            ),
            array(
                'case'           => 'Table with defaults, no relations nor filters. Not resetting to defaults, not resetting the relations',
                'resetRelations' => false,
                'eager'          => array(),
                'data'           => array(
                    'id'          => null,
                    'title'       => null,
                    'start_date'  => null,
                    'description' => null
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'recordData'      => array(),
                    'eagerRelations'  => array('foo' => 'bar'),
                    'relationFilters' => array('dummy')
                ),
                'table_id'  => 'foftest_bare_id',
                'table'     => '#__foftest_bares',
                'default'   => true,
                'relations' => false
            ),
            array(
                'case'           => 'Relations set, but we are not resetting them',
                'resetRelations' => false,
                'eager'          => array('foo' => 'bar'),
                'data'           => array(
                    'foftest_bare_id'  => null,
                    'title'            => null
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'recordData'      => array(),
                    'eagerRelations'  => array('foo' => 'bar'),
                    'relationFilters' => array('dummy')
                ),
                'table_id'  => 'foftest_bare_id',
                'table'     => '#__foftest_bares',
                'default'   => true,
                'relations' => true
            ),
            array(
                'case'           => 'Relations set, we are resetting them',
                'resetRelations' => true,
                'eager'          => array(),
                'data'           => array(
                    'foftest_bare_id'  => null,
                    'title'            => null
                )
            )
        );

        return $data;
    }

    public static function getTestGetFieldValue()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'alias' => array()
                ),
                'find'     => 1,
                'property' => 'foftest_foobar_id',
                'default'  => null
            ),
            array(
                'case'   => 'Getting a property that exists',
                'result' => 1
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'alias' => array()
                ),
                'find'     => null,
                'property' => 'foftest_foobar_id',
                'default'  => null
            ),
            array(
                'case'   => 'Getting a property that exists, record not loaded',
                'result' => null
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'alias' => array()
                ),
                'find'     => null,
                'property' => 'foobar',
                'default'  => 'test'
            ),
            array(
                'case'   => 'Getting a property that does not exist',
                'result' => 'test'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'alias' => array(
                        'foobar' => 'title'
                    )
                ),
                'find'     => 1,
                'property' => 'foobar',
                'default'  => null
            ),
            array(
                'case'   => 'Getting a property that exists using an alias',
                'result' => 'Guinea Pig row'
            )
        );

        return $data;
    }

    public static function getTestHasField()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'getAlias' => 'foftest_foobar_id',
                    'fields'   => array(
                        'foftest_foobar_id' => 'dummy'
                    )
                ),
                'field' => 'foftest_foobar_id'
            ),
            array(
                'case'   => 'Field exists, no alias',
                'result' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getAlias' => 'nothere',
                    'fields'   => array(
                        'foftest_foobar_id' => 'dummy'
                    )
                ),
                'field' => 'nothere'
            ),
            array(
                'case'   => 'Field does not exists, no alias',
                'result' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getAlias' => 'foobar',
                    'fields'   => array(
                        'foftest_foobar_id' => 'dummy'
                    )
                ),
                'field' => 'foftest_foobar_id'
            ),
            array(
                'case'   => 'Field does no exists, has an alias',
                'result' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getAlias' => 'foobar',
                    'fields'   => array(
                        'foobar' => 'dummy'
                    )
                ),
                'field' => 'foftest_foobar_id'
            ),
            array(
                'case'   => 'Field exists, has an alias',
                'result' => true
            )
        );

        return $data;
    }

    public static function getTestGetFieldAlias()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'alias' => array(
                        'foobar' => 'test'
                    )
                ),
                'field' => 'id'
            ),
            array(
                'case'   => 'Alias not set for the field',
                'result' => 'id'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'alias' => array(
                        'id' => 'foobar'
                    )
                ),
                'field' => 'id'
            ),
            array(
                'case'   => 'Alias set for the field',
                'result' => 'foobar'
            )
        );

        return $data;
    }

    public static function getTestChunk()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'count' => 0
                ),
                'chunksize' => 5
            ),
            array(
                'case' => 'Records not found',
                'get'  => 0
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'count' => 10
                ),
                'chunksize' => 5
            ),
            array(
                'case' => 'Records found they are a multiple of the chunksize',
                'get'  => 2
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'count' => 10
                ),
                'chunksize' => 4
            ),
            array(
                'case' => 'Records found they are not a multiple of the chunksize',
                'get'  => 3
            )
        );

        return $data;
    }

    public static function getTestBuildQuery()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'where' => array()
                ),
                'override' => false
            ),
            array(
                'case' => 'No limits override, no additional query, no order field or direction',
                'filter' => true,
                'where'  => array(),
                'order'  => array('`foftest_bare_id` ASC')
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'where' => array(),
                    'order' => 'title'
                ),
                'override' => false
            ),
            array(
                'case' => 'No limits override, no additional query or direction, with (known) order field',
                'filter' => true,
                'where'  => array(),
                'order'  => array('`title` ASC')
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'where' => array(),
                    'order' => 'foobar'
                ),
                'override' => false
            ),
            array(
                'case' => 'No limits override, no additional query or direction, with (unknown) order field',
                'filter' => true,
                'where'  => array(),
                'order'  => array('`foftest_bare_id` ASC')
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'where' => array(),
                    'order' => 'title',
                    'dir'   => 'asc'
                ),
                'override' => false
            ),
            array(
                'case' => 'No limits override, no additional query, with (known) order field and lowercase direction',
                'filter' => true,
                'where'  => array(),
                'order'  => array('`title` ASC')
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'where' => array(),
                    'order' => 'title',
                    'dir'   => 'DESC'
                ),
                'override' => false
            ),
            array(
                'case' => 'No limits override, no additional query, with (known) order field and uppercase direction',
                'filter' => true,
                'where'  => array(),
                'order'  => array('`title` DESC')
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'where' => array(),
                    'order' => 'title',
                    'dir'   => 'wrong'
                ),
                'override' => false
            ),
            array(
                'case' => 'No limits override, no additional query, with (known) order field and invalid direction',
                'filter' => true,
                'where'  => array(),
                'order'  => array('`title` ASC')
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'where' => array(
                        'foobar = 1'
                    ),
                    'order' => 'title',
                    'dir'   => 'DESC'
                ),
                'override' => true
            ),
            array(
                'case' => 'Limits override, additional query, with (known) order field and uppercase direction',
                'filter' => true,
                'where'  => array('foobar = 1'),
                'order'  => array('`title` DESC')
            )
        );


        return $data;
    }

    public static function getTestGet()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'limitstart' => 10,
                    'limit'      => 10
                ),
                'override'   => false,
                'limitstart' => 0,
                'limit'      => 0
            ),
            array(
                'case'       => 'Not overriding the limits',
                'limitstart' => 10,
                'limit'      => 10
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'limitstart' => 10,
                    'limit'      => 10
                ),
                'override'   => true,
                'limitstart' => 5,
                'limit'      => 5
            ),
            array(
                'case'       => 'Overriding the limits',
                'limitstart' => 5,
                'limit'      => 5
            )
        );

        return $data;
    }

    public static function getTestAddBehaviour()
    {
        $data[] = array(
            array(
                'class' => 'Foofilters'
            ),
            array(
                'case'     => 'Adding behaviour from Fakeapp\Site\Model\Behaviour',
                'class'    => 'Fakeapp\Site\Model\Behaviour\Foofilters',
                'attached' => true
            )
        );

        $data[] = array(
            array(
                'class' => 'NamedBehaviour'
            ),
            array(
                'case'     => 'Adding behaviour from Fakeapp\\Model\\Nestedset\\Behaviour\\NamedBehaviour',
                'class'    => 'Fakeapp\Site\Model\Behaviour\Nestedset\NamedBehaviour',
                'attached' => true
            )
        );

        $data[] = array(
            array(
                'class' => 'Filters'
            ),
            array(
                'case'     => 'Adding behaviour from FOF30\\Model\\DataModel\\Behaviour',
                'class'    => 'FOF30\\Model\\DataModel\\Behaviour\\Filters',
                'attached' => true
            )
        );

        $data[] = array(
            array(
                'class' => 'Wrong'
            ),
            array(
                'case'     => 'Trying to add non-existant behaviour',
                'class'    => 'FOF30\\Model\\DataModel\\Behaviour\\Wrong',
                'attached' => false
            )
        );

        return $data;
    }

    public static function getTestOrderBy()
    {
        $data[] = array(
            array(
                'field' => 'foobar',
                'dir'   => 'asc'
            ),
            array(
                'case'  => 'Passing field and direction (lowercase)',
                'field' => 'foobar',
                'dir'   => 'ASC'
            )
        );

        $data[] = array(
            array(
                'field' => 'foobar',
                'dir'   => 'desc'
            ),
            array(
                'case'  => 'Passing field and direction (lowercase)',
                'field' => 'foobar',
                'dir'   => 'DESC'
            )
        );

        $data[] = array(
            array(
                'field' => 'foobar',
                'dir'   => ''
            ),
            array(
                'case'  => 'Passing field only',
                'field' => 'foobar',
                'dir'   => 'ASC'
            )
        );

        return $data;
    }

    public static function getTestSkip()
    {
        $data[] = array(
            array(
                'limitstart' => 10
            ),
            array(
                'case' => 'Limitstart is positive',
                'limitstart' => 10
            )
        );

        $data[] = array(
            array(
                'limitstart' => null
            ),
            array(
                'case' => 'Limitstart is null',
                'limitstart' => 0
            )
        );

        $data[] = array(
            array(
                'limitstart' => -1
            ),
            array(
                'case' => 'Limitstart is negative',
                'limitstart' => 0
            )
        );

        $data[] = array(
            array(
                'limitstart' => array(1)
            ),
            array(
                'case' => 'Wrong type',
                'limitstart' => 0
            )
        );

        $data[] = array(
            array(
                'limitstart' => new stdClass()
            ),
            array(
                'case' => 'Wrong type',
                'limitstart' => 0
            )
        );

        $data[] = array(
            array(
                'limitstart' => true
            ),
            array(
                'case' => 'Wrong type',
                'limitstart' => 0
            )
        );

        return $data;
    }

    public static function getTestTake()
    {
        $data[] = array(
            array(
                'limit' => 10
            ),
            array(
                'case' => 'Limit is positive',
                'limit' => 10
            )
        );

        $data[] = array(
            array(
                'limit' => null
            ),
            array(
                'case' => 'Limit is null',
                'limit' => 0
            )
        );

        $data[] = array(
            array(
                'limit' => -1
            ),
            array(
                'case' => 'Limit is negative',
                'limit' => 0
            )
        );

        $data[] = array(
            array(
                'limit' => array(1)
            ),
            array(
                'case' => 'Wrong type',
                'limit' => 0
            )
        );

        $data[] = array(
            array(
                'limit' => new stdClass()
            ),
            array(
                'case' => 'Wrong type',
                'limit' => 0
            )
        );

        $data[] = array(
            array(
                'limit' => true
            ),
            array(
                'case' => 'Wrong type',
                'limit' => 0
            )
        );

        return $data;
    }

    public static function getTestToJson()
    {
        $data[] = array(
            array(
                'pretty' => false
            )
        );

        $data[] = array(
            array(
                'pretty' => true
            )
        );

        return $data;
    }

    public static function getTestWhere()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'foobar',
                    'hasClass' => false
                ),
                'field'  => 'foobar',
                'method' => '=',
                'values' => null
            ),
            array(
                'case'    => 'Behaviors not loaded, field is the primary key',
                'add'     => true,
                'field'   => 'foobar',
                'options' => array(
                    'method'   => 'search',
                    'value'    => null,
                    'operator' => '='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'dummy',
                    'hasClass' => true
                ),
                'field'  => 'foobar',
                'method' => '=',
                'values' => null
            ),
            array(
                'case'    => 'Behaviors loaded, field is not the primary key',
                'add'     => false,
                'field'   => 'foobar',
                'options' => array(
                    'method'   => 'search',
                    'value'    => null,
                    'operator' => '='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '<>',
                'values' => 12
            ),
            array(
                'case'    => '<> method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '!='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => 'lt',
                'values' => 12
            ),
            array(
                'case'    => 'lt method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '<'
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => 'le',
                'values' => 12
            ),
            array(
                'case'    => 'le method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '<='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => 'gt',
                'values' => 12
            ),
            array(
                'case'    => 'gt method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '>'
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => 'ge',
                'values' => 12
            ),
            array(
                'case'    => 'ge method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '>='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => 'eq',
                'values' => 12
            ),
            array(
                'case'    => 'eq method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => 'neq',
                'values' => 12
            ),
            array(
                'case'    => 'neq method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '!='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => 'ne',
                'values' => 12
            ),
            array(
                'case'    => 'ne method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '!='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '<',
                'values' => 12
            ),
            array(
                'case'    => '< method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '<'
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '!<',
                'values' => 12
            ),
            array(
                'case'    => '!< method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '!<'
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '<=',
                'values' => 12
            ),
            array(
                'case'    => '<= method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '<='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '!<=',
                'values' => 12
            ),
            array(
                'case'    => '!<= method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '!<='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '>',
                'values' => 12
            ),
            array(
                'case'    => '> method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '>'
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '!>',
                'values' => 12
            ),
            array(
                'case'    => '!> method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '!>'
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '>=',
                'values' => 12
            ),
            array(
                'case'    => '>= method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '>='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '!>=',
                'values' => 12
            ),
            array(
                'case'    => '!>= method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '!>='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '!=',
                'values' => 12
            ),
            array(
                'case'    => '!= method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '!='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '=',
                'values' => 12
            ),
            array(
                'case'    => '= method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => 'like',
                'values' => 'foobar'
            ),
            array(
                'case'    => 'like method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'partial',
                    'value'    => 'foobar'
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '~',
                'values' => 'foobar'
            ),
            array(
                'case'    => '~ method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'partial',
                    'value'    => 'foobar'
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '%',
                'values' => 'foobar'
            ),
            array(
                'case'    => '%% method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'partial',
                    'value'    => 'foobar'
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '==',
                'values' => 12
            ),
            array(
                'case'    => '== method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'exact',
                    'value'    => 12
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '=[]',
                'values' => 12
            ),
            array(
                'case'    => '=[] method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'exact',
                    'value'    => 12
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '=()',
                'values' => 12
            ),
            array(
                'case'    => '=() method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'exact',
                    'value'    => 12
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => 'in',
                'values' => 12
            ),
            array(
                'case'    => 'in method, values passed',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'exact',
                    'value'    => 12
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '()',
                'values' => 12
            ),
            array(
                'case'    => 'between method, values is not an array',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '[]',
                'values' => 12
            ),
            array(
                'case'    => '[] method, values is not an array',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '[)',
                'values' => 12
            ),
            array(
                'case'    => '[) method, values is not an array',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '(]',
                'values' => 12
            ),
            array(
                'case'    => '(] method, values is not an array',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '()',
                'values' => array(12)
            ),
            array(
                'case'    => 'between method, values is an array with a single element',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '()',
                'values' => array(12, 22)
            ),
            array(
                'case'    => 'between method, values is an array, but no from/to keys',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'between',
                    'from'     => 12,
                    'to'       => 22
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '()',
                'values' => array(12, 22, 'from' => 5)
            ),
            array(
                'case'    => 'between method, values is an array, but no "from" key',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'between',
                    'from'     => 12,
                    'to'       => 22
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '()',
                'values' => array(12, 22, 'to' => 5)
            ),
            array(
                'case'    => 'between method, values is an array, but no "to" key',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'between',
                    'from'     => 12,
                    'to'       => 22
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '()',
                'values' => array(12, 22, 'from' => 5, 'to' => 7)
            ),
            array(
                'case'    => 'between method, values is an array, with "from/to" keys',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'between',
                    'from'     => 5,
                    'to'       => 7
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => ')(',
                'values' => 12
            ),
            array(
                'case'    => 'outside method, values is not an array',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '!='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => ')[',
                'values' => 12
            ),
            array(
                'case'    => ')[ method, values is not an array',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '!='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '](',
                'values' => 12
            ),
            array(
                'case'    => ']( method, values is not an array',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '!='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '][',
                'values' => 12
            ),
            array(
                'case'    => '][ method, values is not an array',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '!='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => ')(',
                'values' => array(12)
            ),
            array(
                'case'    => 'outside method, values is an array with a single element',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '!='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => ')(',
                'values' => array(12, 22)
            ),
            array(
                'case'    => 'outside method, values is an array, but no from/to keys',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'outside',
                    'from'     => 12,
                    'to'       => 22
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => ')(',
                'values' => array(12, 22, 'from' => 5)
            ),
            array(
                'case'    => 'outside method, values is an array, but no "from" key',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'outside',
                    'from'     => 12,
                    'to'       => 22
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => ')(',
                'values' => array(12, 22, 'to' => 5)
            ),
            array(
                'case'    => 'outside method, values is an array, but no "to" key',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'outside',
                    'from'     => 12,
                    'to'       => 22
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => ')(',
                'values' => array(12, 22, 'from' => 5, 'to' => 7)
            ),
            array(
                'case'    => 'outside method, values is an array, with "from/to" keys',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'outside',
                    'from'     => 5,
                    'to'       => 7
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => 'every',
                'values' => 12
            ),
            array(
                'case'    => 'every (interval) method, values is not an array',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '*=',
                'values' => 12
            ),
            array(
                'case'    => 'interval method, values is not an array',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '*=',
                'values' => array(12)
            ),
            array(
                'case'    => 'interval method, values is an array with a single item',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12,
                    'operator' => '='
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '*=',
                'values' => array(12, 22)
            ),
            array(
                'case'    => 'interval method, values is an array, but no value/interval keys',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'interval',
                    'value'    => 12,
                    'interval' => 22
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '*=',
                'values' => array(12, 22, 'value' => 5)
            ),
            array(
                'case'    => 'interval method, values is an array, but no "value" key',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'interval',
                    'value'    => 12,
                    'interval' => 22
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '*=',
                'values' => array(12, 22, 'interval' => 5)
            ),
            array(
                'case'    => 'interval method, values is an array, but no "interval" key',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'interval',
                    'value'    => 12,
                    'interval' => 22
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '*=',
                'values' => array(12, 22, 'value' => 5, 'interval' => 7)
            ),
            array(
                'case'    => 'interval method, values is an array, with "value/interval" keys',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'interval',
                    'value'    => 5,
                    'interval' => 7
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '?=',
                'values' => 12
            ),
            array(
                'case'    => '?= method, values is not an array',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => 12
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '?=',
                'values' => array(12)
            ),
            array(
                'case'    => '?= method, values is an array with a single item',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'value'    => array(12)
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '?=',
                'values' => array(12, 22)
            ),
            array(
                'case'    => '?= method, values is an array with no "operator/value" keys',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'operator' => 12,
                    'value'    => 22
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '?=',
                'values' => array(12, 22, 'operator' => 'XX')
            ),
            array(
                'case'    => '?= method, values is an array with no "value" key',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'operator' => 12,
                    'value'    => 22
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '?=',
                'values' => array(12, 22, 'value' => 'XX')
            ),
            array(
                'case'    => '?= method, values is an array with no "operator" key',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'operator' => 12,
                    'value'    => 22
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'id_field' => 'id',
                    'hasClass' => true
                ),
                'field'  => 'id',
                'method' => '?=',
                'values' => array(12, 22, 'value' => 5, 'operator' => 'XX')
            ),
            array(
                'case'    => '?= method, values is an array with "operator/value" keys',
                'add'     => false,
                'field'   => 'id',
                'options' => array(
                    'method'   => 'search',
                    'operator' => 'XX',
                    'value'    => 5
                )
            )
        );

        return $data;
    }

    public static function getTestWith()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'relNames' => array('foobar')
                ),
                'relations' => array('foobar' => function(){})
            ),
            array(
                'case' => 'Relation known, callback applied',
                'eager' => array(
                    'foobar' => function(){}
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'relNames' => array('foobar')
                ),
                'relations' => array('foobar')
            ),
            array(
                'case' => 'Relation known, no callback',
                'eager' => array(
                    'foobar' => null
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'relNames' => array('foobar')
                ),
                'relations' => array('dummy')
            ),
            array(
                'case' => 'Relation not known',
                'eager' => array()
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'relNames' => array('foobar')
                ),
                'relations' => array()
            ),
            array(
                'case' => 'Reset the eager relations array',
                'eager' => array()
            )
        );

        return $data;
    }

    public static function getTestapplyAccessFiltering()
    {
        $data[] = array(
            array(
                'tableid' => 'foftest_bare_id',
                'table'   => '#__foftest_bares'
            ),
            array(
                'case'  => 'Table with no access support',
                'state' => false
            )
        );

        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table'   => '#__foftest_foobars'
            ),
            array(
                'case'  => 'Table with access support',
                'state' => true
            )
        );

        return $data;
    }

    public static function getTestGetContentType()
    {
        $data[] = array(
            array(
                'contentType' => 'com_fakeapp.foobar'
            ),
            array(
                'case' => 'Content Type is set',
                'exception' => false,
                'result' => 'com_fakeapp.foobar'
            )
        );

        $data[] = array(
            array(
                'contentType' => null
            ),
            array(
                'case' => 'Content Type is not set',
                'exception' => true,
                'result' => null
            )
        );

        return $data;
    }

    public static function getTestAddKnownField()
    {
        $data[] = array(
            array(
                'name'    => 'foftest_bare_id',
                'replace' => false
            ),
            array(
                'case'  => 'Try to add a field that already exists - no replace',
                'field' => 'foftest_bare_id',
                'value' => null,
                'info'  => (object)array(
                    'Field' => 'foftest_bare_id',
                    'Type' => 'int(11)',
                    'Collation' => null,
                    'Null' => 'NO',
                    'Key' => 'PRI',
                    'Default' => null,
                    'Extra' => 'auto_increment',
                    'Privileges' => 'select,insert,update,references',
                    'Comment' => null
                )
            )
        );

        $data[] = array(
            array(
                'name'    => 'foftest_bare_id',
                'replace' => true
            ),
            array(
                'case'  => 'Try to add a field that already exists - replace',
                'field' => 'foftest_bare_id',
                'value' => 'foobar',
                'info'  => (object)array(
                    'Type' => 'varchar(100)',
                    'Default' => 'foobar',
                )
            )
        );

        $data[] = array(
            array(
                'name'    => 'new_one',
                'replace' => false
            ),
            array(
                'case'  => 'Adding a field that does not exist',
                'field' => 'new_one',
                'value' => 'foobar',
                'info'  => (object)array(
                    'Type' => 'varchar(100)',
                    'Default' => 'foobar',
                )
            )
        );

        return $data;
    }

    public static function getTestGetAssetName()
    {
        $data[] = array(
            array(
                'load' => 0,
                'assetkey' => ''
            ),
            array(
                'case'      => 'Asset key not defined',
                'exception' => true,
                'result'    => ''
            )
        );

        $data[] = array(
            array(
                'load' => 0,
                'assetkey' => 'com_fakeapp.foobar'
            ),
            array(
                'case'      => 'Table not loaded',
                'exception' => true,
                'result'    => ''
            )
        );

        $data[] = array(
            array(
                'load' => 1,
                'assetkey' => 'com_fakeapp.foobar'
            ),
            array(
                'case'      => 'Asset key present and table loaded',
                'exception' => false,
                'result'    => 'com_fakeapp.foobar.1'
            )
        );

        return $data;
    }

    public static function getTestGetForm()
    {
        $data[] = array(
            array(
                'data'     => array(),
                'loadData' => true,
                'source'   => null,
                'mock'     => array(
                    'formName' => '',
                    'loadForm' => true
                )
            ),
            array(
                'case'    => 'No data, loading the form, no source, loadForm returns a valid form',
                'data'    => array(),
                'name'    => 'com_fakeapp.nestedset.form.nestedset',
                'source'  => 'form.nestedset',
                'options' => array('control' => false, 'load_data' => true),
                'result'  => true,
                'before'  => 1,
                'after'   => 1
            )
        );

        $data[] = array(
            array(
                'data'     => array(),
                'loadData' => true,
                'source'   => null,
                'mock'     => array(
                    'formName' => 'fromobject',
                    'loadForm' => true
                )
            ),
            array(
                'case'    => 'No data, loading the form, no source (taken from Model name), loadForm returns a valid form',
                'data'    => array(),
                'name'    => 'com_fakeapp.nestedset.fromobject',
                'source'  => 'fromobject',
                'options' => array('control' => false, 'load_data' => true),
                'result'  => true,
                'before'  => 1,
                'after'   => 1
            )
        );

        $data[] = array(
            array(
                'data'     => array('foobar' => '123'),
                'loadData' => false,
                'source'   => 'default',
                'mock'     => array(
                    'formName' => '',
                    'loadForm' => false
                )
            ),
            array(
                'case'    => 'With data, not loading the form, no source, loadForm returns false',
                'data'    => array('foobar' => '123'),
                'name'    => 'com_fakeapp.nestedset.default',
                'source'  => 'default',
                'options' => array('control' => false, 'load_data' => false),
                'result'  => false,
                'before'  => 1,
                'after'   => 0
            )
        );

        return $data;
    }

    public function getTestValidateForm()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'filter'   => array(123),
                    'validate' => true,
                    'errors'   => ''
                )
            ),
            array(
                'case'      => 'Validation runs fine',
                'exception' => false,
                'message'   => '',
                'result'    => array(123)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'filter'   => array(123),
                    'validate' => new \Exception(),
                    'errors'   => ''
                )
            ),
            array(
                'case'      => 'Validate returns an exception',
                'exception' => '\Exception',
                'message'   => '',
                'result'    => ''
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'filter'   => array(123),
                    'validate' => false,
                    'errors'   => array(new \Exception())
                )
            ),
            array(
                'case'      => 'Validate returns false, the error stack contains an exception',
                'exception' => '\Exception',
                'message'   => '',
                'result'    => ''
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'filter'   => array(123),
                    'validate' => false,
                    'errors'   => array('Error')
                )
            ),
            array(
                'case'      => 'Validate returns false, the error stack contains a message',
                'exception' => 'FOF30\Model\DataModel\Exception\BaseException',
                'message'   => 'Error',
                'result'    => ''
            )
        );

        return $data;
    }

    public static function getTestGetBehaviorParam()
    {
        $data[] = array(
            array(
                'name' => 'foo',
                'default' => 'test',
                'mock' => array(
                    'behaviors' => array(
                        'foo' => 'bar'
                    )
                )
            ),
            array(
                'case' => 'Behavior is set',
                'result' => 'bar'
            )
        );

        $data[] = array(
            array(
                'name' => 'foo',
                'default' => 'test',
                'mock' => array(
                    'behaviors' => array()
                )
            ),
            array(
                'case' => 'Behavior is not set',
                'result' => 'test'
            )
        );

        return $data;
    }

    public static function getTestBlacklistFilters()
    {
        $data[] = array(
            array(
                'list'  => null,
                'reset' => false
            ),
            array(
                'case'    => 'Retrieving the whole list of filters',
                'result'  => array('test'),
                'filters' => array('test')
            )
        );

        $data[] = array(
            array(
                'list'  => 'foobar',
                'reset' => false
            ),
            array(
                'case'    => 'Setting a new list - no replace',
                'result'  => null,
                'filters' => array('test', 'foobar')
            )
        );

        $data[] = array(
            array(
                'list'  => 'foobar',
                'reset' => true
            ),
            array(
                'case'    => 'Setting a new list - with replace',
                'result'  => null,
                'filters' => array('foobar')
            )
        );

        return $data;
    }
}