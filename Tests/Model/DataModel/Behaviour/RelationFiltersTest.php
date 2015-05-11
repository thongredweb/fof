<?php
namespace FOF30\Tests\DataModel;

use Fakeapp\Site\Model\Parents;
use FOF30\Model\DataModel\Behaviour\RelationFilters;
use FOF30\Tests\Helpers\DatabaseTest;

require_once 'RelationFiltersDataprovider.php';

/**
 * @covers      FOF30\Model\DataModel\Behaviour\RelationFilters::<protected>
 * @covers      FOF30\Model\DataModel\Behaviour\RelationFilters::<private>
 * @package     FOF30\Tests\DataModel\Behaviour\RelationFilters
 */
class RelationFiltersTest extends DatabaseTest
{
    /**
     * @group           Behaviour
     * @group           RelationFiltersOnAfterBuildQuery
     * @covers          FOF30\Model\DataModel\Behaviour\RelationFilters::onAfterBuildQuery
     * @dataProvider    RelationFiltersDataprovider::getTestOnAfterBuildQuery
     */
    public function testOnAfterBuildQuery($test, $check)
    {
        \PHPUnit_Framework_Error_Warning::$enabled = false;

        $msg = 'RelationFilters::onAfterBuildQuery %s - Case: '.$check['case'];

        $config = array(
            'relations'   => array(
                array(
                    'itemName' => 'children',
                    'type' => 'hasMany',
                    'foreignModelClass' => 'Children',
                    'localKey' => 'fakeapp_parent_id',
                    'foreignKey' => 'fakeapp_parent_id'
                )
            )
        );

        /** @var \FOF30\Model\DataModel $model */
        $model = new Parents(static::$container, $config);

        $query      = \JFactory::getDbo()->getQuery(true)->select('*')->from('test');
        $dispatcher = $model->getBehavioursDispatcher();
        $filter     = new RelationFilters($dispatcher);

        // I have to setup a filter
        $model->has('children', $test['operator'], $test['value']);

        $filter->onAfterBuildQuery($model, $query);

        $this->assertEquals($check['query'], trim((string) $query), sprintf($msg, 'Failed to build the search query'));
    }
}

