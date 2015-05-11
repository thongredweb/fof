<?php
namespace FOF30\Tests\DataModel\Relation\Relation\BelongsTo;

use FOF30\Model\DataModel\Relation\BelongsTo;
use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once 'BelongsToDataprovider.php';

/**
 * @covers      FOF30\Model\DataModel\Relation\BelongsTo::<protected>
 * @covers      FOF30\Model\DataModel\Relation\BelongsTo::<private>
 * @package     FOF30\Tests\DataModel\Relation\BelongsTo
 */
class BelongsToTest extends DatabaseTest
{
    /**
     * @group           BelongsTo
     * @group           BelongsToConstruct
     * @covers          FOF30\Model\DataModel\Relation\BelongsTo::__construct
     * @dataProvider    BelongsToDataprovider::getTestConstruct
     */
    public function testConstruct($test, $check)
    {
        $msg = 'BelongsTo::__construct %s - Case: '.$check['case'];

        $model    = $this->buildModel();
        $relation = new BelongsTo($model, 'Parents', $test['local'], $test['foreign']);

        $this->assertEquals($check['local'], ReflectionHelper::getValue($relation, 'localKey'), sprintf($msg, 'Failed to set the local key'));
        $this->assertEquals($check['foreign'], ReflectionHelper::getValue($relation, 'foreignKey'), sprintf($msg, 'Failed to set the foreign key'));
    }

    /**
     * @group           BelongsTo
     * @group           BelongsToGetNew
     * @covers          FOF30\Model\DataModel\Relation\BelongsTo::getNew
     */
    public function testGetNew()
    {
        $model = $this->buildModel();
        $relation = new BelongsTo($model, 'Parents');

        $this->setExpectedException('FOF30\Model\DataModel\Relation\Exception\NewNotSupported');

        $relation->getNew();
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
            'idFieldName' => 'fakeapp_children_id',
            'tableName'   => '#__fakeapp_children'
        );

        return new $class(static::$container, $config);
    }
}