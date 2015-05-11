<?php

namespace FOF30\Tests\DataModel\Relation\Relation\HasMany;

use FOF30\Model\DataModel\Relation\HasMany;
use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once 'HasManyDataprovider.php';

/**
 * @covers      FOF30\Model\DataModel\Relation\HasMany::<protected>
 * @covers      FOF30\Model\DataModel\Relation\HasMany::<private>
 * @package     FOF30\Tests\DataModel\Relation\HasMany
 */
class HasManyTest extends DatabaseTest
{
    /**
     * @group           HasMany
     * @group           HasManyConstruct
     * @covers          FOF30\Model\DataModel\Relation\HasMany::__construct
     * @dataProvider    HasManyDataprovider::getTestConstruct
     */
    public function testConstruct($test, $check)
    {
        $msg = 'HasMany::__construct %s - Case: '.$check['case'];
        $model    = $this->buildModel();
        $relation = new HasMany($model, 'Children', $test['local'], $test['foreign']);

        $this->assertEquals($check['local'], ReflectionHelper::getValue($relation, 'localKey'), sprintf($msg, 'Failed to set the local key'));
        $this->assertEquals($check['foreign'], ReflectionHelper::getValue($relation, 'foreignKey'), sprintf($msg, 'Failed to set the foreign key'));
    }

    /**
     * @group           HasMany
     * @group           HasManyGetCountSubquery
     * @covers          FOF30\Model\DataModel\Relation\HasMany::getCountSubquery
     */
    public function testGetCountSubquery()
    {
        //\PHPUnit_Framework_Error_Warning::$enabled = false;

        $model    = $this->buildModel();
        $relation = new HasMany($model, 'Children');

        $query = $relation->getCountSubquery();

        $check = '
SELECT COUNT(*)
FROM `#__fakeapp_children` AS `reltbl`
WHERE `reltbl`.`fakeapp_parent_id` = `#__fakeapp_parents`.`fakeapp_parent_id`';

        $this->assertEquals($check, $query, 'HasMany::getCountSubquery Returned the wrong query');
    }

    /**
     * @group           HasMany
     * @group           HasManyGetNew
     * @covers          FOF30\Model\DataModel\Relation\HasMany::getNew
     */
    public function testGetNew()
    {
        $model    = $this->buildModel();
        $model->find(2);
        $relation = new HasMany($model, 'Children');

        $new = $relation->getNew();

        $this->assertInstanceOf('Fakeapp\Site\Model\Children', $new);
        $this->assertEquals(2, $new->getFieldValue('fakeapp_parent_id'), 'HasMany::getNew Failed to prime the new record');
    }

    /**
     * @param   string    $class
     *
     * @return \FOF30\Model\DataModel
     */
    protected function buildModel($class = null)
    {
        if(!$class)
        {
            $class = '\FOF30\Tests\Stubs\Model\DataModelStub';
        }

        $config = array(
            'idFieldName' => 'fakeapp_parent_id',
            'tableName'   => '#__fakeapp_parents'
        );

        return new $class(static::$container, $config);
    }
}