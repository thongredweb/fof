<?php

class MagicMethodsDataprovider
{
    public static function getTest__construct()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'state' => array()
                ),
                'id'          => 'foftest_bare_id',
                'table'       => '#__foftest_bares',
                'knownFields' => null,
                'autoChecks'  => true,
                'skipChecks'  => array('title'),
                'aliasFields' => array('foobar' => 'title'),
                'behaviours'  => array('foo', 'bar'),
                'fillable'    => '',
                'guarded'     => '',
                'relations'   => null
            ),
            array(
                'case' => 'Passing id, tablename, autochecks are on, skipchecks fields, alias field and behaviours',
                'addBehaviour' => 4,
                'id' => 'foftest_bare_id',
                'table' => '#__foftest_bares',
                'fields' => array(
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
                ),
                'autochecks' => true,
                'skipchecks' => array('title'),
                'alias' => array('foobar' => 'title'),
                'fillable' => array(),
                'autofill' => false,
                'guarded' => array(),
                'values'  => array(),
                'relations' => array(),
                'counterApp' => 0,
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'state' => array()
                ),
                'id'          => null,
                'table'       => null,
                'knownFields' => null,
                'autoChecks'  => null,
                'skipChecks'  => null,
                'aliasFields' => null,
                'behaviours'  => null,
                'fillable'    => null,
                'guarded'     => null,
                'relations'   => null
            ),
            array(
                'case' => 'Not passing anything',
                'addBehaviour' => 2,
                'id' => 'fakeapp_test_id',
                'table' => '#__fakeapp_tests',
                'fields' => array(
                    'fakeapp_test_id' => (object) array(
                        'Field' => 'fakeapp_test_id',
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
                ),
                'autochecks' => true,
                'skipchecks' => array(),
                'alias' => array(),
                'fillable' => array(),
                'autofill' => false,
                'guarded' => array(),
                'values'  => array(),
                'relations' => array(),
                'counterApp' => 0,
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'state' => array()
                ),
                'id'          => 'foftest_bare_id',
                'table'       => '#__foftest_bares',
                'knownFields' => array(
                    'foftest_bare_id' => (object) array(
                        'Field' => 'foftest_bare_id',
                        'Type' => 'int(10) unsigned',
                        'Default' => null,
                    ),
                    'title' => (object) array(
                        'Field' => 'title',
                        'Type' => 'varchar(50)',
                        'Default' =>null,
                    )
                ),
                'autoChecks'  => null,
                'skipChecks'  => null,
                'aliasFields' => null,
                'behaviours'  => null,
                'fillable'    => '',
                'guarded'     => '',
                'relations'   => null
            ),
            array(
                'case' => 'Passing id, tablename and known fields',
                'addBehaviour' => 2,
                'id' => 'foftest_bare_id',
                'table' => '#__foftest_bares',
                'fields' => array(
                    'foftest_bare_id' => (object) array(
                        'Field' => 'foftest_bare_id',
                        'Type' => 'int(10) unsigned',
                        'Default' => null,
                    ),
                    'title' => (object) array(
                        'Field' => 'title',
                        'Type' => 'varchar(50)',
                        'Default' =>null,
                    )
                ),
                'autochecks' => true,
                'skipchecks' => array(),
                'alias' => array(),
                'fillable' => array(),
                'autofill' => false,
                'guarded' => array(),
                'values'  => array(),
                'relations' => array(),
                'counterApp' => 0,
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'state' => array()
                ),
                'id'          => 'foftest_bare_id',
                'table'       => '#__foftest_bares',
                'knownFields' => null,
                'autoChecks'  => null,
                'skipChecks'  => null,
                'aliasFields' => array('foobar' => 'description'),
                'behaviours'  => null,
                'fillable'    => array('title', 'wrong', 'foobar'),
                'guarded'     => '',
                'relations'   => null
            ),
            array(
                'case' => 'Setting up fillable fields, no guarded ones',
                'addBehaviour' => 2,
                'id' => 'foftest_bare_id',
                'table' => '#__foftest_bares',
                'fields' => null,
                'autochecks' => true,
                'skipchecks' => array(),
                'alias' => array('foobar' => 'description'),
                'fillable' => array('title', 'description'),
                'autofill' => true,
                'guarded' => array(),
                'values'  => array(),
                'relations' => array(),
                'counterApp' => 0,
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'state' => array(
                        'title' => 'test'
                    )
                ),
                'id'          => 'foftest_bare_id',
                'table'       => '#__foftest_bares',
                'knownFields' => null,
                'autoChecks'  => null,
                'skipChecks'  => null,
                'aliasFields' => array('foobar' => 'description'),
                'behaviours'  => null,
                'fillable'    => array('title', 'wrong', 'foobar'),
                'guarded'     => '',
                'relations'   => null
            ),
            array(
                'case' => 'Setting up fillable fields, no guarded ones, data in the request',
                'addBehaviour' => 2,
                'id' => 'foftest_bare_id',
                'table' => '#__foftest_bares',
                'fields' => null,
                'autochecks' => true,
                'skipchecks' => array(),
                'alias' => array('foobar' => 'description'),
                'fillable' => array('title', 'description'),
                'autofill' => true,
                'guarded' => array(),
                'values'  => array('title' => 'test', 'description' => null),
                'relations' => array(),
                'counterApp' => 0,
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'state' => array(
                        'title' => 'test',
                        'description' => 'test'
                    )
                ),
                'id'          => 'foftest_bare_id',
                'table'       => '#__foftest_bares',
                'knownFields' => null,
                'autoChecks'  => null,
                'skipChecks'  => null,
                'aliasFields' => array('foobar' => 'description'),
                'behaviours'  => null,
                'fillable'    => null,
                'guarded'     => array('foobar'),
                'relations'   => null
            ),
            array(
                'case' => 'Setting up guarded fields, no fillable ones, data in the request',
                'addBehaviour' => 2,
                'id' => 'foftest_bare_id',
                'table' => '#__foftest_bares',
                'fields' => null,
                'autochecks' => true,
                'skipchecks' => array(),
                'alias' => array('foobar' => 'description'),
                'fillable' => array(),
                'autofill' => true,
                'guarded' => array('description'),
                'values'  => array('title' => 'test', 'description' => null),
                'relations' => array(),
                'counterApp' => 0,
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'state' => array(
                        'title'       => 'test',
                        'description' => 'test'
                    )
                ),
                'id'          => 'id',
                'table'       => '#__foftest_defaults',
                'knownFields' => null,
                'autoChecks'  => null,
                'skipChecks'  => null,
                'aliasFields' => null,
                'behaviours'  => null,
                'fillable'    => array('title', 'description'),
                'guarded'     => array('description'),
                'relations'   => null
            ),
            array(
                'case' => 'Setting up guarded fields AND fillable ones, data in the request',
                'addBehaviour' => 2,
                'id' => 'id',
                'table' => '#__foftest_defaults',
                'fields' => null,
                'autochecks' => true,
                'skipchecks' => array(),
                'alias' => array(),
                'fillable' => array('title', 'description'),
                'autofill' => true,
                'guarded' => array('description'),
                'values'  => array('title' => 'test', 'description' => null, 'start_date' => '0000-00-00 00:00:00'),
                'relations' => array(),
                'counterApp' => 0,
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'state' => array(
                        'title'       => 'test',
                        'description' => 'test'
                    )
                ),
                'id'          => 'id',
                'table'       => '#__foftest_defaults',
                'knownFields' => null,
                'autoChecks'  => null,
                'skipChecks'  => null,
                'aliasFields' => null,
                'behaviours'  => null,
                'fillable'    => array('title', 'description'),
                'guarded'     => array('description'),
                'relations'   => null
            ),
            array(
                'case' => 'Setting up guarded fields AND fillable ones, data in the request, table with defaults values',
                'addBehaviour' => 2,
                'id' => 'id',
                'table' => '#__foftest_defaults',
                'fields' => null,
                'autochecks' => true,
                'skipchecks' => array(),
                'alias' => array(),
                'fillable' => array('title', 'description'),
                'autofill' => true,
                'guarded' => array('description'),
                'values'  => array('title' => 'test', 'description' => null, 'start_date' => '0000-00-00 00:00:00'),
                'relations' => array(),
                'counterApp' => 0,
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'state' => array()
                ),
                'id'          => 'foftest_parent_id',
                'table'       => '#__foftest_parents',
                'knownFields' => null,
                'autoChecks'  => null,
                'skipChecks'  => null,
                'aliasFields' => null,
                'behaviours'  => null,
                'fillable'    => null,
                'guarded'     => null,
                'relations'   => ''
            ),
            array(
                'case' => 'Passing a relation - Wrong format',
                'addBehaviour' => 2,
                'id' => 'foftest_parent_id',
                'table' => '#__foftest_parents',
                'fields' => null,
                'autochecks' => true,
                'skipchecks' => array(),
                'alias' => array(),
                'fillable' => array(),
                'autofill' => false,
                'guarded' => array(),
                'values'  => array(),
                'relations' => array(),
                'counterApp' => 0,
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'state' => array()
                ),
                'id'          => 'foftest_parent_id',
                'table'       => '#__foftest_parents',
                'knownFields' => null,
                'autoChecks'  => null,
                'skipChecks'  => null,
                'aliasFields' => null,
                'behaviours'  => null,
                'fillable'    => null,
                'guarded'     => null,
                'relations'   => array()
            ),
            array(
                'case' => 'Passing a relation - Wrong format',
                'addBehaviour' => 2,
                'id' => 'foftest_parent_id',
                'table' => '#__foftest_parents',
                'fields' => null,
                'autochecks' => true,
                'skipchecks' => array(),
                'alias' => array(),
                'fillable' => array(),
                'autofill' => false,
                'guarded' => array(),
                'values'  => array(),
                'relations' => array(),
                'counterApp' => 0,
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'state' => array()
                ),
                'id'          => 'foftest_parent_id',
                'table'       => '#__foftest_parents',
                'knownFields' => null,
                'autoChecks'  => null,
                'skipChecks'  => null,
                'aliasFields' => null,
                'behaviours'  => null,
                'fillable'    => null,
                'guarded'     => null,
                'relations'   => array('dummy' => '')
            ),
            array(
                'case' => 'Passing a relation - Wrong format',
                'addBehaviour' => 2,
                'id' => 'foftest_parent_id',
                'table' => '#__foftest_parents',
                'fields' => null,
                'autochecks' => true,
                'skipchecks' => array(),
                'alias' => array(),
                'fillable' => array(),
                'autofill' => false,
                'guarded' => array(),
                'values'  => array(),
                'relations' => array(),
                'counterApp' => 0,
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'state' => array()
                ),
                'id'          => 'foftest_parent_id',
                'table'       => '#__foftest_parents',
                'knownFields' => null,
                'autoChecks'  => null,
                'skipChecks'  => null,
                'aliasFields' => null,
                'behaviours'  => null,
                'fillable'    => null,
                'guarded'     => null,
                'relations'   => array(
                    array(
                        'itemName' => 'children',
                        'type' => 'hasMany',
                        'foreignModelClass' => 'Fakeapp\Model\Children',
                        'localKey' => 'foftest_parent_id',
                        'foreignKey' => 'foftest_parent_id'
                    )
                )
            ),
            array(
                'case' => 'Passing a relation - Correct format',
                'addBehaviour' => 2,
                'id' => 'foftest_parent_id',
                'table' => '#__foftest_parents',
                'fields' => null,
                'autochecks' => true,
                'skipchecks' => array(),
                'alias' => array(),
                'fillable' => array(),
                'autofill' => false,
                'guarded' => array(),
                'values'  => array(),
                'relations' => array('children'),
                'counterApp' => 0,
            )
        );

        return $data;
    }

    public static function getTest__call()
    {
        $data[] = array(
            array(
                'method'   => 'dummyProperty',
                'argument' => null,
                'mock'     => array(
                    'magic' => false
                )
            ),
            array(
                'case'     => 'Property with a specific method, no argument passed',
                'method'   => 'scopeDummyProperty',
                'property' => 'dummyProperty',
                'value'    => 'default',
                'count'    => 1,
                'magic'    => false,
                'relationCall' => false
            )
        );

        $data[] = array(
            array(
                'method'   => 'dummyPropertyNoFunction',
                'argument' => null,
                'mock'     => array(
                    'magic' => false
                )
            ),
            array(
                'case'     => 'Property without a specific method, no argument passed',
                'method'   => 'scopeDummyPropertyNoFunction',
                'property' => 'dummyPropertyNoFunction',
                'value'    => 'default',
                'count'    => 0,
                'magic'    => true,
                'relationCall' => false
            )
        );

        $data[] = array(
            array(
                'method'   => 'dummyPropertyNoFunction',
                'argument' => array('test', null),
                'mock'     => array(
                    'magic' => true
                )
            ),
            array(
                'case'     => 'Property without a specific method, a magic method exists inside the relation manager',
                'method'   => 'scopeDummyPropertyNoFunction',
                'property' => 'dummyPropertyNoFunction',
                'value'    => 'default',
                'count'    => 0,
                'magic'    => true,
                'relationCall' => true
            )
        );

        return $data;
    }

    public static function getTest__isset()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'getField'  => 1,
                    'magic'     => '',
                    'alias'     => array(),
                    'relationGet' => null
                ),
                'property' => 'foftest_foobar_id'
            ),
            array(
                'case'          => 'Field is set and has a NOT NULL value',
                'getField'      => 'foftest_foobar_id',
                'magic'         => false,
                'relationGet'   => false,
                'isset'         => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getField'  => null,
                    'magic'     => '',
                    'alias'     => array(),
                    'relationGet' => null
                ),
                'property' => 'foftest_foobar_id'
            ),
            array(
                'case'          => 'Field is set and has a NULL value',
                'getField'      => 'foftest_foobar_id',
                'magic'         => false,
                'relationGet'   => false,
                'isset'         => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getField'  => 1,
                    'magic'     => '',
                    'alias'     => array(
                        'foobar' => 'foftest_foobar_id'
                    ),
                    'relationGet' => null
                ),
                'property' => 'foobar'
            ),
            array(
                'case'          => 'Field had an alias and has a NOT NULL value',
                'getField'      => 'foftest_foobar_id',
                'magic'         => false,
                'relationGet'   => false,
                'isset'         => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getField'  => null,
                    'magic'     => '',
                    'alias'     => array(
                        'foobar' => 'foftest_foobar_id'
                    ),
                    'relationGet' => null
                ),
                'property' => 'foobar'
            ),
            array(
                'case'          => 'Field had an alias and has a NULL value',
                'getField'      => 'foftest_foobar_id',
                'magic'         => false,
                'relationGet'   => false,
                'isset'         => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getField'  => null,
                    'magic'     => false,
                    'alias'     => array(),
                    'relationGet' => null
                ),
                'property' => 'foobar'
            ),
            array(
                'case'          => 'Field is not set and is not a magic property',
                'getField'      => false,
                'magic'         => 'foobar',
                'relationGet'  => false,
                'isset'         => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getField'  => null,
                    'magic'     => true,
                    'alias'     => array(),
                    'relationGet' => 1
                ),
                'property' => 'foobar'
            ),
            array(
                'case'          => 'Field is not set and is a magic property, returns NOT NULL',
                'getField'      => false,
                'magic'         => 'foobar',
                'relationGet'   => true,
                'isset'         => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getField'  => null,
                    'magic'     => true,
                    'alias'     => array(),
                    'relationGet' => null
                ),
                'property' => 'foobar'
            ),
            array(
                'case'          => 'Field is not set and is a magic property, returns NULL',
                'getField'      => false,
                'magic'         => 'foobar',
                'relationGet'   => true,
                'isset'         => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getField'  => false,
                    'magic'     => '',
                    'alias'     => array(),
                    'relationGet' => null
                ),
                'property' => 'fltState'
            ),
            array(
                'case'          => 'Field starts with flt, no magic property set',
                'getField'      => null,
                'magic'         => 'state',
                'relationGet'   => false,
                'isset'         => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getField'  => false,
                    'magic'     => true,
                    'alias'     => array(),
                    'relationGet' => null
                ),
                'property' => 'fltState'
            ),
            array(
                'case'          => 'Field starts with flt, magic property set and returns NULL',
                'getField'      => null,
                'magic'         => 'state',
                'relationGet'   => true,
                'isset'         => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getField'  => false,
                    'magic'     => true,
                    'alias'     => array(),
                    'relationGet' => 1
                ),
                'property' => 'fltState'
            ),
            array(
                'case'          => 'Field starts with flt, magic property set and returns NOT NULL',
                'getField'      => null,
                'magic'         => 'state',
                'relationGet'   => true,
                'isset'         => true
            )
        );

        return $data;
    }

    public static function getTest__get()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'getField'    => 1,
                    'getState'    => 0,
                    'magic'       => '',
                    'alias'       => array(),
                    'relationGet' => null
                ),
                'property' => 'foftest_foobar_id'
            ),
            array(
                'case'          => 'Standard field of the DataModel',
                'getField'      => 'foftest_foobar_id',
                'getState'      => false,
                'magic'         => false,
                'relationGet'   => false,
                'get'           => 1
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getField'    => 1,
                    'getState'    => 0,
                    'magic'       => '',
                    'alias'       => array(
                        'foobar' => 'foftest_foobar_id'
                    ),
                    'relationGet' => null
                ),
                'property' => 'foobar'
            ),
            array(
                'case'          => 'Standard field with an alias of the DataModel',
                'getField'      => 'foftest_foobar_id',
                'getState'      => false,
                'magic'         => false,
                'relationGet'   => false,
                'get'           => 1
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getField'    => 0,
                    'getState'    => 1,
                    'magic'       => false,
                    'alias'       => array(),
                    'relationGet' => null
                ),
                'property' => 'foobar'
            ),
            array(
                'case'          => 'Field with has not a magic property method inside the relation manager',
                'getField'      => false,
                'getState'      => 'foobar',
                'magic'         => 'foobar',
                'relationGet'   => false,
                'get'           => 1
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getField'    => 0,
                    'getState'    => 0,
                    'magic'       => true,
                    'alias'       => array(),
                    'relationGet' => 1
                ),
                'property' => 'foobar'
            ),
            array(
                'case'          => 'Field has a magic property method inside the relation manager',
                'getField'      => false,
                'getState'      => false,
                'magic'         => 'foobar',
                'relationGet'   => 'foobar',
                'get'           => 1
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getField'    => 0,
                    'getState'    => 1,
                    'magic'       => false,
                    'alias'       => array(),
                    'relationGet' => null
                ),
                'property' => 'fltFoobar'
            ),
            array(
                'case'          => 'Field with has not a magic property method inside the relation manager - Magic name',
                'getField'      => false,
                'getState'      => 'foobar',
                'magic'         => 'foobar',
                'relationGet'   => false,
                'get'           => 1
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getField'    => 0,
                    'getState'    => 0,
                    'magic'       => true,
                    'alias'       => array(),
                    'relationGet' => 1
                ),
                'property' => 'fltFoobar'
            ),
            array(
                'case'          => 'Field has a magic property method inside the relation manager - Magic name',
                'getField'      => false,
                'getState'      => false,
                'magic'         => 'foobar',
                'relationGet'   => 'foobar',
                'get'           => 1
            )
        );

        return $data;
    }

    public static function getTest__set()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'alias'    => array()
                ),
                'property' => 'foftest_foobar_id',
                'value'    => 10
            ),
            array(
                'case'     => 'Setting a property that exists in the table',
                'call'     => false,
                'count'    => 0,
                'method'   => '',
                'setField' => 'foftest_foobar_id',
                'setState' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'alias'    => array(
                        'foobar' => 'foftest_foobar_id'
                    )
                ),
                'property' => 'foobar',
                'value'    => 10
            ),
            array(
                'case'     => 'Setting a property that exists in the table using an alias',
                'call'     => false,
                'count'    => 0,
                'method'   => '',
                'setField' => 'foftest_foobar_id',
                'setState' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'alias'    => array()
                ),
                'property' => 'foobar',
                'value'    => 10
            ),
            array(
                'case'     => 'Property does not exists, so we set the state',
                'call'     => false,
                'count'    => 0,
                'method'   => 'scopeFoobar',
                'setField' => false,
                'setState' => 'foobar'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'alias'    => array()
                ),
                'property' => 'dummyNoProperty',
                'value'    => 10
            ),
            array(
                'case'     => 'Property does not exists, but we have a magic method scope',
                'call'     => false,
                'count'    => 1,
                'method'   => 'scopeDummyNoProperty',
                'setField' => false,
                'setState' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'alias'    => array()
                ),
                'property' => 'fltFoobar',
                'value'    => 10
            ),
            array(
                'case'     => 'Property does not exists, but its name is magic for the state',
                'call'     => false,
                'count'    => 0,
                'method'   => 'scopeFoobar',
                'setField' => false,
                'setState' => 'foobar'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'alias'    => array()
                ),
                'property' => 'scopeFoobar',
                'value'    => 10
            ),
            array(
                'case'     => 'Property does not exists, but its name is magic for the state - Going to invoke the call method of the model',
                'call'     => true,
                'count'    => 0,
                'method'   => 'scopeFoobar',
                'setField' => false,
                'setState' => false
            )
        );

        return $data;
    }
}