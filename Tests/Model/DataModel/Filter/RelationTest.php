<?php
namespace FOF30\Tests\DataModel\Filter\Relation;

use FOF30\Model\DataModel\Filter\Relation;
use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Helpers\ReflectionHelper;

/**
 * @covers      FOF30\Model\DataModel\Filter\Relation::<protected>
 * @covers      FOF30\Model\DataModel\Filter\Relation::<private>
 * @package     FOF30\Tests\DataModel\Filter\Relation
 */
class RelationTest extends DatabaseTest
{
    /**
     * @group       RelationFilter
     * @group       RelationFilterConstruct
     * @covers      FOF30\Model\DataModel\Filter\Relation::__construct
     */
    public function test__construct()
    {
        $subquery = \JFactory::getDbo()->getQuery(true);
        $subquery->select('*')->from('test');

        $filter = new Relation(\JFactory::getDbo(), 'foo', $subquery);

        $this->assertEquals('foo', ReflectionHelper::getValue($filter, 'name'), 'Relation::__construct Failed to set filter name');
        $this->assertEquals('relation', ReflectionHelper::getValue($filter, 'type'), 'Relation::__construct Failed to set filter type');
        $this->assertEquals($subquery, ReflectionHelper::getValue($filter, 'subQuery'), 'Relation::__construct Failed to set the subQuery field');
    }

    /**
     * @group       RelationFilter
     * @group       RelationFilterCallback
     * @covers      FOF30\Model\DataModel\Filter\Relation::callback
     */
    public function testCallback()
    {
        $subquery = \JFactory::getDbo()->getQuery(true);
        $subquery->select('*')->from('test');

        $filter = new Relation(\JFactory::getDbo(), 'foo', $subquery);

        $result = $filter->callback(function($query){
            $query->where('bar = 1');

            return $query;
        });

        $check  = 'SELECT *
FROM test
WHERE bar = 1';

        $this->assertEquals($check, trim((string)$result), 'Relation::callback Returned the wrong result');
    }

    /**
     * @group       RelationFilter
     * @group       RelationFilterGetFieldName
     * @covers      FOF30\Model\DataModel\Filter\Relation::getFieldName
     */
    public function testGetFieldName()
    {
        $subquery = \JFactory::getDbo()->getQuery(true);
        $subquery->select('*')->from('test');

        $filter = new Relation(\JFactory::getDbo(), 'foo', $subquery);

        $result = $filter->getFieldName();

        $check = '(
SELECT *
FROM test)';

        $this->assertEquals($check, $result, 'Relation::getFieldName Returned the wrong result');
    }
}
