<?php

class FiltersDataprovider
{
    public static function getTestOnAfterBuildQuery()
    {
        $data[] = array(
            array(
                'ignore' => false,
                'mock' => array(
                    'state' => array(
                        'id' => 1,
                    )
                )
            ),
            array(
                'case'  => 'Searching vs primary key, not ignoring the request',
                'query' => "SELECT *
FROM test"
            )
        );

        $data[] = array(
            array(
                'ignore' => true,
                'mock' => array(
                    'state' => array(
                        'id' => 1,
                    )
                )
            ),
            array(
                'case'  => 'Searching vs primary key, ignoring the request',
                'query' => "SELECT *
FROM test
WHERE (`foftest_foobar_id` = '1')"
            )
        );

        $data[] = array(
            array(
                'ignore' => false,
                'mock' => array(
                    'state' => array(
                        'title' => 'test'
                    )
                )
            ),
            array(
                'case'  => 'Searching vs text field',
                'query' => "SELECT *
FROM test
WHERE (`title` LIKE '%test%')"
            )
        );

        $data[] = array(
            array(
                'ignore' => false,
                'mock' => array(
                    'state' => array(
                        'title' => array(
                            'value' => 'one'
                        )
                    )
                )
            ),
            array(
                'case'  => 'Searching using an array',
                'query' => "SELECT *
FROM test
WHERE (`title` LIKE '%one%')"
            )
        );

        $data[] = array(
            array(
                'ignore' => false,
                'mock' => array(
                    'state' => array(
                        'created_on' => array(
                            'from' => '1979-01-01',
                            'to'   => '1981-12-31'
                        ),
                    )
                )
            ),
            array(
                'case'  => 'Searching using an array, value key not present',
                'query' => "SELECT *
FROM test"
            )
        );

        $data[] = array(
            array(
                'ignore' => false,
                'mock' => array(
                    'state' => array(
                        'created_on' => array(
                            'method' => 'between',
                            'from' => '1979-01-01',
                            'to'   => '1981-12-31'
                        ),
                    )
                )
            ),
            array(
                'case'  => 'Searching using an array, passing the method in the state - 1',
                'query' => "SELECT *
FROM test
WHERE ((`created_on` > '1979-01-01') AND (`created_on` < '1981-12-31'))"
            )
        );

        $data[] = array(
            array(
                'ignore' => false,
                'mock' => array(
                    'state' => array(
                        'created_on' => array(
                            'method' => 'between',
                            'to'   => '1981-12-31'
                        ),
                    )
                )
            ),
            array(
                'case'  => 'Searching using an array, passing the method in the state - 2',
                'query' => "SELECT *
FROM test"
            )
        );

        $data[] = array(
            array(
                'ignore' => false,
                'mock' => array(
                    'state' => array(
                        'created_on' => array(
                            'method' => 'between',
                            'from' => '1979-01-01',
                        ),
                    )
                )
            ),
            array(
                'case'  => 'Searching using an array, passing the method in the state - 3',
                'query' => "SELECT *
FROM test"
            )
        );

        $data[] = array(
            array(
                'ignore' => false,
                'mock' => array(
                    'state' => array(
                        'created_on' => array(
                            'method' => 'outside',
                            'from' => '1979-01-01',
                            'to'   => '1981-12-31'
                        ),
                    )
                )
            ),
            array(
                'case'  => 'Searching using an array, passing the method in the state - 4',
                'query' => "SELECT *
FROM test
WHERE ((`created_on` < '1979-01-01') AND (`created_on` > '1981-12-31'))"
            )
        );

        $data[] = array(
            array(
                'ignore' => false,
                'mock' => array(
                    'state' => array(
                        'created_on' => array(
                            'method' => 'interval',
                            'value' => '1979-01-01',
                            'interval' => '+1 year'
                        ),
                    )
                )
            ),
            array(
                'case'  => 'Searching using an array, passing the method in the state - 5',
                'query' => "SELECT *
FROM test
WHERE (`created_on` >= DATE_ADD(`created_on`, INTERVAL 1 year))"
            )
        );

        $data[] = array(
            array(
                'ignore' => false,
                'mock' => array(
                    'state' => array(
                        'created_on' => array(
                            'method' => 'search',
                            'value' => '1979-01-01',
                        ),
                    )
                )
            ),
            array(
                'case'  => 'Searching using an array, passing the method in the state - 6',
                'query' => "SELECT *
FROM test
WHERE (`created_on` = '1979-01-01')"
            )
        );

        $data[] = array(
            array(
                'ignore' => false,
                'mock' => array(
                    'state' => array(
                        'created_on' => array(
                            'method'  => 'between',
                            'from'    => '1979-01-01',
                            'to'      => '1981-12-31',
                            'include' => true
                        ),
                    )
                )
            ),
            array(
                'case'  => 'Searching using an array, passing the method in the state - 7',
                'query' => "SELECT *
FROM test
WHERE ((`created_on` >= '1979-01-01') AND (`created_on` <= '1981-12-31'))"
            )
        );

        $data[] = array(
            array(
                'ignore' => false,
                'mock' => array(
                    'state' => array(
                        'id' => array(
                            'method' => 'wrong',
                            'value' => '32',
                        ),
                    )
                )
            ),
            array(
                'case'  => 'Searching using an array, passing a wrong method in the state',
                'query' => "SELECT *
FROM test"
            )
        );

        $data[] = array(
            array(
                'ignore' => false,
                'mock' => array(
                    'state' => array(
                        'created_on' => array(
                            'method' => 'search',
                            'operator' => '>',
                            'value' => '1979-01-01',
                        ),
                    )
                )
            ),
            array(
                'case'  => 'Searching using an array, passing the method and operator in the state',
                'query' => "SELECT *
FROM test
WHERE (`created_on` > '1979-01-01')"
            )
        );



        $data[] = array(
            array(
                'ignore' => false,
                'mock' => array(
                    'state' => array(
                        'created_on' => (object) array(
                            'method' => 'search',
                            'operator' => '>',
                            'value' => '1979-01-01',
                        ),
                    )
                )
            ),
            array(
                'case'  => 'Searching using an object, passing the method and operator in the state',
                'query' => "SELECT *
FROM test
WHERE (`created_on` > '1979-01-01')"
            )
        );

        return $data;
    }
}