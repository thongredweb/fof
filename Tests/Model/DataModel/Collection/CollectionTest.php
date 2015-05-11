<?php

namespace FOF30\Tests\DataModel\Collection;

use FOF30\Model\DataModel\Collection;
use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Helpers\TestContainer;
use FOF30\Tests\Stubs\Model\DataModelStub;

require_once 'CollectionDataprovider.php';

class CollectionTest extends DatabaseTest
{
    /**
     * @group           DataModel
     * @group           CollectionFind
     * @covers          FOF30\Model\DataModel\Collection::find
     * @dataProvider    CollectionDataprovider::getTestFind
     */
    public function testFind($test, $check)
    {
        $msg   = 'Collection::find %s - Case: '.$check['case'];
        $items = $this->buildCollection();

        $collection = new Collection($items);

        $key = $test['key'] == 'object' ? $items[2] : $test['key'];

        $result = $collection->find($key, $test['default']);

        if($check['type'] == 'object')
        {
            $this->assertInstanceOf('FOF30\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of DataModel'));
            $this->assertEquals($check['result'], $result->getId(), sprintf($msg, 'Failed to return the correct item'));
        }
        else
        {
            $this->assertSame($check['result'], $result, sprintf($msg, 'Failed to return the correct item'));
        }
    }

    /**
     * @group           DataModel
     * @group           CollectionRemoveById
     * @covers          FOF30\Model\DataModel\Collection::removeById
     * @dataProvider    CollectionDataprovider::getTestRemoveById
     */
    public function testRemoveById($test, $check)
    {
        $msg   = 'Collection::removeById %s - Case: '.$check['case'];
        $items = $this->buildCollection();

        $collection = new Collection($items);

        $key = $test['key'] == 'object' ? $items[2] : $test['key'];

        $collection->removeById($key);

        $this->assertArrayNotHasKey($check['key'], $collection, sprintf($msg, 'Failed to remove the item'));
    }

    /**
     * @group           DataModel
     * @group           CollectionAdd
     * @covers          FOF30\Model\DataModel\Collection::add
     */
    public function testAdd()
    {
        $items = $this->buildCollection();

        $collection = new Collection($items);

        $result = $collection->add('foobar');
        $last   = $collection->pop();

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel\\Collection', $result, 'Collection::add Should return an instance of itself');
        $this->assertEquals('foobar', $last, 'Collection::add Failed to add an element');
    }

    /**
     * @group           DataModel
     * @group           CollectionContains
     * @covers          FOF30\Model\DataModel\Collection::contains
     * @dataProvider    CollectionDataprovider::getTestContains
     */
    public function testContains($test, $check)
    {
        $msg   = 'Collection::contains %s - Case: '.$check['case'];
        $items = $this->buildCollection();

        $collection = new Collection($items);

        $result = $collection->contains($test['key']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to return the correct value'));
    }

    /**
     * @group           DataModel
     * @group           CollectionFetch
     * @covers          FOF30\Model\DataModel\Collection::fetch
     */
    public function testFetch()
    {
        $this->markTestSkipped('Skipped test until we decide what Collection::fetch should do');

        /*$items = $this->buildCollection();

        $collection = new Collection($items);

        $result = $collection->fetch(2);*/
    }

    /**
     * @group           DataModel
     * @group           CollectionMax
     * @covers          FOF30\Model\DataModel\Collection::max
     */
    public function testMax()
    {
        $items = $this->buildCollection();

        $collection = new Collection($items);

        $result = $collection->max('foftest_bare_id');

        // Let's get the maximum value directly from the db
        $db = \JFactory::getDbo();

        $query = $db->getQuery(true)->select('MAX(foftest_bare_id)')->from('#__foftest_bares');
        $max   = $db->setQuery($query)->loadResult();

        $this->assertEquals($max, $result, 'Collection::max Failed to return highest value');
    }

    /**
     * @group           DataModel
     * @group           CollectionMin
     * @covers          FOF30\Model\DataModel\Collection::min
     */
    public function testMin()
    {
        $items = $this->buildCollection();

        $collection = new Collection($items);

        $result = $collection->min('foftest_bare_id');

        // Let's get the maximum value directly from the db
        $db = \JFactory::getDbo();

        $query = $db->getQuery(true)->select('MIN(foftest_bare_id)')->from('#__foftest_bares');
        $min   = $db->setQuery($query)->loadResult();

        $this->assertEquals($min, $result, 'Collection::min Failed to return lowest value');
    }

    /**
     * @group           DataModel
     * @group           CollectionModelKeys
     * @covers          FOF30\Model\DataModel\Collection::modelKeys
     */
    public function testModelKeys()
    {
        $items = $this->buildCollection();

        $collection = new Collection($items);

        $result = $collection->modelKeys();

        $this->assertEquals(array(1 => 1, 2 => 2, 3 => 3), $result, 'Collection::modelKeys Failed to get the array of primary keys');
    }

    /**
     * @group           DataModel
     * @group           CollectionMerge
     * @covers          FOF30\Model\DataModel\Collection::merge
     */
    public function testMerge()
    {
        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('getState'), array(static::$container, $config));

        $collection1 = new Collection($model->getItemsArray(0, 2));
        $collection2 = new Collection($model->getItemsArray(2, 1));

        $merge = $collection1->merge($collection2);

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel\\Collection', $merge, 'Collection::merge Should return an instance of Collection');
        $this->assertCount(3, $merge, 'Collection::merge Failed to merge two arrays');
    }

    /**
     * @group           DataModel
     * @group           CollectionDiff
     * @covers          FOF30\Model\DataModel\Collection::diff
     */
    public function testDiff()
    {
        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('getState'), array(static::$container, $config));

        $collection1 = new Collection($model->getItemsArray());
        $collection2 = new Collection($model->getItemsArray(2, 1));

        $merge = $collection1->diff($collection2);

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel\\Collection', $merge, 'Collection::diff Should return an instance of Collection');
        $this->assertCount(2, $merge, 'Collection::diff Failed to diff two arrays');
    }

    /**
     * @group           DataModel
     * @group           CollectionIntersect
     * @covers          FOF30\Model\DataModel\Collection::intersect
     */
    public function testIntersect()
    {
        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('getState'), array(static::$container, $config));

        $collection1 = new Collection($model->getItemsArray(0,2));
        $collection2 = new Collection($model->getItemsArray(0, 1));

        $merge = $collection1->intersect($collection2);

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel\\Collection', $merge, 'Collection::intersect Should return an instance of Collection');
        $this->assertCount(1, $merge, 'Collection::intersect Failed to intersect two arrays');
    }

    /**
     * @group           DataModel
     * @group           CollectionModelUnique
     * @covers          FOF30\Model\DataModel\Collection::unique
     */
    public function testUnique()
    {
        $items = $this->buildCollection();

        // Let's duplicate an item
        $items["1"] = $items[1];
        $collection = new Collection($items);
        $newCollection = $collection->unique();

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel\\Collection', $newCollection, 'Collection::unique should return an instance of Collection');
        $this->assertCount(3, $newCollection);
        $this->assertEquals(array(1 => 1, 2 => 2, 3 => 3), $collection->modelKeys());
    }

    /**
     * @group           DataModel
     * @group           CollectionModelToBase
     * @covers          FOF30\Model\DataModel\Collection::toBase
     */
    public function testToBase()
    {
        $items = $this->buildCollection();

        $collection = new Collection($items);

        $base = $collection->toBase();

        $this->assertEquals('FOF30\\Utils\\Collection', get_class($base), 'Collection::toBase Should return a BaseCollection object');
    }

    /**
     * @group           DataModel
     * @group           CollectionCall
     * @covers          FOF30\Model\DataModel\Collection::__call
     * @dataProvider    CollectionDataprovider::getTest__call
     */
    public function test__call($test, $check)
    {
        $checkCall = null;
        $items     = array();
        $msg       = 'Collection::__call %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        if($test['load'])
        {
            $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('getState'), array(static::$container, $config));
            $items = $model->getItemsArray(0, 1);
        }

        $collection = new Collection($items);

        switch ($test['arguments'])
        {
            case 0:
                $collection->dynamicCall();
                break;

            case 1:
                $collection->dynamicCall(1);
                break;

            case 2:
                $collection->dynamicCall(1, 1);
                break;

            case 3:
                $collection->dynamicCall(1, 1, 1);
                break;

            case 4:
                $collection->dynamicCall(1, 1, 1, 1);
                break;

            case 5:
                $collection->dynamicCall(1, 1, 1, 1, 1);
                break;

            case 6:
                $collection->dynamicCall(1, 1, 1, 1, 1, 1);
                break;

            case 7:
                $collection->dynamicCall(1, 1, 1, 1, 1, 1, 1);
                break;
        }

        if($item = $collection->first())
        {
            $checkCall = $item->dynamicCall;
        }

        $this->assertEquals($check['call'], $checkCall, sprintf($msg, 'Failed to correctly invoke DataModel methods'));
    }

    /**
     * Build a collection of DataModels, used inside the tests
     *
     * return   DataModel[]
     */
    protected function buildCollection()
    {
        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('getState'), array(static::$container, $config));

        return $model->getItemsArray();
    }
}