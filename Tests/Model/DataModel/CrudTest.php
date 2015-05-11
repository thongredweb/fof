<?php
/**
 * @package        FOF
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace FOF30\Tests\DataModel;

use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;
use FOF30\Tests\Stubs\Model\DataModelStub;
use FOF30\Tests\Helpers\ObserverClosure;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\DatabaseTest;

require_once 'CrudDataprovider.php';

/**
 * @covers      FOF30\Model\DataModel::<protected>
 * @covers      FOF30\Model\DataModel::<private>
 * @package     FOF30\Tests\DataModel
 */
class DataModelCrudTest extends DatabaseTest
{
    /**
     * @group           DataModel
     * @group           DataModelSave
     * @covers          FOF30\Model\DataModel::save
     * @dataProvider    DataModelCrudDataprovider::getTestSave
     */
    public function testSave($test, $check)
    {
        //\PHPUnit_Framework_Error_Warning::$enabled = false;

        $db          = \JFactory::getDbo();
        $msg         = 'DataModel::save %s - Case: '.$check['case'];
        $events      = array('onBeforeSave'  => 0, 'onAfterSave'  => 0, 'onBeforeCreate'  => 0, 'onAfterCreate'  => 0, 'onBeforeUpdate'  => 0, 'onAfterUpdate'  => 0);
        $dispEvents  = $events;
        $modelEvents = $events;

        // I need to fake the user id, since in CLI I don't have one
        $container = new TestContainer();
        $platform  = $container->platform;
        $platform::$user = (object)array('id' => 99);

        $config = array(
            'idFieldName' => $test['table_id'],
            'tableName'   => $test['table']
        );

        $methods = array(
            'onBeforeSave' => function() use (&$modelEvents){
                $modelEvents['onBeforeSave']++;
            },
            'onAfterSave' => function() use (&$modelEvents){
                $modelEvents['onAfterSave']++;
            },
            'onBeforeCreate' => function() use (&$modelEvents){
                $modelEvents['onBeforeCreate']++;
            },
            'onAfterCreate' => function() use (&$modelEvents){
                $modelEvents['onAfterCreate']++;
            },
            'onBeforeUpdate' => function() use (&$modelEvents){
                $modelEvents['onBeforeUpdate']++;
            },
            'onAfterUpdate' => function() use (&$modelEvents){
                $modelEvents['onAfterUpdate']++;
            }
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('check', 'reorder'), array($container, $config, $methods));
        $model->expects($check['reorder'] ? $this->once() : $this->never())->method('reorder')->with($this->equalTo($check['reorder']));

        $dispatcher = $model->getBehavioursDispatcher();

        // Let's attach a custom observer, so I can mock and check all the calls performed by the dispatcher
        // P.A. The object is immediatly attached to the dispatcher, so I don't need to manually do that
        new ObserverClosure($dispatcher, array(
            'onBeforeSave' => function(&$subject, &$data) use ($test, &$dispEvents){
                if($test['mock']['blankId']){
                    $id = $test['table_id'];
                    $subject->$id = null;
                }

                if(!is_null($test['mock']['dataSave'])){
                    $data = $test['mock']['dataSave'];
                }

                $dispEvents['onBeforeSave']++;
            },
            'onBeforeCreate' => function(&$subject, &$dataObject) use($test, &$dispEvents){
                if(!is_null($test['mock']['dataCreate'])){
                    foreach($test['mock']['dataCreate'] as $prop => $value){
                        $dataObject->$prop = $value;
                    }
                }

                $dispEvents['onBeforeCreate']++;
            },
            'onAfterCreate' => function() use(&$dispEvents){
                $dispEvents['onAfterCreate']++;
            },
            'onBeforeUpdate' => function(&$subject, &$dataObject) use($test, &$dispEvents){
                if(!is_null($test['mock']['dataUpdate'])) {
                    foreach ($test['mock']['dataUpdate'] as $prop => $value) {
                        $dataObject->$prop = $value;
                    }
                }

                $dispEvents['onBeforeUpdate']++;
            },
            'onAfterUpdate' => function() use(&$dispEvents){
                $dispEvents['onAfterUpdate']++;
            },
            'onAfterSave' => function() use(&$dispEvents){
                $dispEvents['onAfterSave']++;
            }
        ));

        if($test['id'])
        {
            $model->find($test['id']);
        }

        $result = $model->save($test['data'], $test['ordering'], $test['ignore']);

        // Did I add a new record or update an old one? Let's get the correct id
        if($check['id'] == 'max')
        {
            $query = $db->getQuery(true)
                ->select('MAX('.$test['table_id'].')')
                ->from($test['table']);
            $checkid = $db->setQuery($query)->loadResult();
        }
        else
        {
            $checkid = $check['id'];
        }

        $query = $db->getQuery(true)->select('*')->from($test['table'])->where($test['table_id'].' = '.$checkid);
        $row   = $db->setQuery($query)->loadObject();

        // If the model has "time columns" I can only check if they are not null
        if($check['created_on'])
        {
            $created_on = $model->getFieldAlias('created_on');
            $this->assertNotNull($row->$created_on, sprintf($msg, 'Failed to set the creation time'));
            unset($row->$created_on);
        }

        if($check['modified_on'])
        {
            $modified_on = $model->getFieldAlias('modified_on');
            $this->assertNotNull($row->$modified_on, sprintf($msg, 'Failed to set the modification time'));
            unset($row->$modified_on);
        }

        // If I am inserting a new record I can't know its id, so let's remove it from the object
        if($check['id'] == 'max')
        {
            $id = $model->getIdFieldName();
            unset($row->$id);
        }

        // Let's merge the arrays, otherwise I'll have to write the whole list inside the dataprovider
        $check['modelEvents'] = array_merge($events, $check['modelEvents']);
        $check['dispEvents']  = array_merge($events, $check['dispEvents']);

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
        $this->assertEquals($check['modelEvents'], $modelEvents, sprintf($msg, 'Failed to invoke model events'));
        $this->assertEquals($check['dispEvents'], $dispEvents, sprintf($msg, 'Failed to invoke dispatcher events'));
        $this->assertEquals($check['row'], $row, sprintf($msg, 'Failed to correctly save the data into the db'));
    }

    /**
     * @group           DataModel
     * @group           DataModelBind
     * @covers          FOF30\Model\DataModel::bind
     * @dataProvider    DataModelCrudDataprovider::getTestBind
     */
    public function testBind($test, $check)
    {
        $msg       = 'DataModel::bind %s - Case: '.$check['case'];
        $checkBind = array();

        $config = array(
            'idFieldName' => $test['tableid'],
            'tableName'   => $test['table']
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('setFieldValue'), array(static::$container, $config));
        $model->expects($this->any())->method('setFieldValue')->willReturnCallback(
            function($key, $value) use (&$checkBind){
                $checkBind[$key] = $value;
            }
        );

        $dispatcher = $this->getMock('\\FOF30\\Event\\Dispatcher', array('trigger'), array(static::$container));
        $dispatcher->expects($this->exactly($check['dispatcher']))->method('trigger')->withConsecutive(
            array($this->equalTo('onBeforeBind')),
            array($this->equalTo('onAfterBind'))
        )
            ->willReturnCallback(
                function($event, $params) use ($test){
                    if($event == 'onBeforeBind' && !is_null($test['mock']['beforeDisp'])){
                        $params[1] = $test['mock']['beforeDisp'];
                    }
                }
            );

        ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);

        $result = $model->bind($test['data'], $test['ignore']);

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
        $this->assertEquals($check['bind'], $checkBind, sprintf($msg, 'Failed to bind the data to the model'));
    }

    /**
     * @group           DataModel
     * @group           DataModelBind
     * @covers          FOF30\Model\DataModel::bind
     * @dataProvider    DataModelCrudDataprovider::getTestBindException
     */
    public function testBindException($test)
    {
        $this->setExpectedException('InvalidArgumentException');

        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $model = new DataModelStub(static::$container, $config);

        $model->bind($test['data']);
    }

    /**
     * @group           DataModel
     * @group           DataModelCheck
     * @covers          FOF30\Model\DataModel::check
     * @dataProvider    DataModelCrudDataprovider::getTestCheck
     */
    public function testCheck($test, $check)
    {
        $msg = 'DataModel::check %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('getForm'), array(static::$container, $config));
        $model->expects($this->any())->method('getForm')->willReturn(false);

        ReflectionHelper::setValue($model, 'autoChecks', $test['mock']['auto']);

        if($test['load'])
        {
            $model->find($test['load']);
        }

        if($check['exception'])
        {
            $this->setExpectedException('RuntimeException', $check['exception']);
        }

        $result = $model->check();

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
    }

    /**
     * @group           DataModel
     * @group           DataModelCopy
     * @covers          FOF30\Model\DataModel::copy
     */
    public function testCopy()
    {
        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('save'), array(static::$container, $config));

        $model->find(2);
        $model->copy();

        $id = $model->getId();

        $this->assertNull($id, 'DataModel::copy Should set the table ID to null before saving the record');
    }

    /**
     * @group           DataModel
     * @group           DataModelDelete
     * @covers          FOF30\Model\DataModel::delete
     * @dataProvider    DataModelCrudDataprovider::getTestDelete
     */
    public function testDelete($test, $check)
    {
        $msg = 'DataModel::delete %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('trash', 'forceDelete'), array(static::$container, $config));
        $model->expects($check['trash'] ? $this->once() : $this->never())->method('trash')->willReturnSelf();
        $model->expects($check['force'] ? $this->once() : $this->never())->method('forceDelete')->willReturnSelf();

        ReflectionHelper::setValue($model, 'softDelete', $test['soft']);

        $result = $model->delete($test['id']);

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
    }

    /**
     * @group           DataModel
     * @group           DataModelFindOrFail
     * @covers          FOF30\Model\DataModel::findOrFail
     * @dataProvider    DataModelCrudDataprovider::getTestFindOrFail
     */
    public function testFindOrFail($test, $check)
    {
        $msg    = 'DataModel::findOrFail %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('find', 'getId'), array(static::$container, $config));
        $model->expects($this->any())->method('find')->willReturn(null);
        $model->expects($this->any())->method('getId')->willReturn($test['mock']['getId']);

        if($check['exception'])
        {
            $this->setExpectedException('FOF30\Model\DataModel\Exception\RecordNotLoaded');
        }

        $result = $model->findOrFail($test['keys']);

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
    }

    /**
     * @group           DataModel
     * @group           DataModelFind
     * @covers          FOF30\Model\DataModel::find
     * @dataProvider    DataModelCrudDataprovider::getTestFind
     */
    public function testFind($test, $check)
    {
        //\PHPUnit_Framework_Error_Warning::$enabled = false;

        $beforeDisp = 0;
        $afterDisp  = 0;
        $before = 0;
        $after  = 0;
        $msg    = 'DataModel::find %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        // I am passing those methods so I can double check if the method is really called
        $methods = array(
            'onBeforeLoad' => function() use(&$before){
                $before++;
            },
            'onAfterLoad' => function() use(&$after){
                $after++;
            }
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('reset', 'getId', 'bind'), array(static::$container, $config, $methods));
        $model->expects($this->any())->method('getId')->willReturn($test['mock']['id']);
        $model->expects($check['bind'] ? $this->once() : $this->never())->method('bind')->willReturn(null);

        $dispatcher = $model->getBehavioursDispatcher();

        // Let's attach a custom observer, so I can mock and check all the calls performed by the dispatcher
        // P.A. The object is immediatly attached to the dispatcher, so I don't need to manually do that
        new ObserverClosure($dispatcher, array(
            'onBeforeLoad' => function(&$subject, &$keys) use ($test, &$beforeDisp){
                if(!is_null($test['mock']['keys'])){
                    $keys = $test['mock']['keys'];
                }

                $beforeDisp++;
            },
            'onAfterLoad' => function(&$subject, $success, $keys) use(&$afterDisp){
                $afterDisp++;
            }
        ));

        if(!is_null($test['mock']['state_id']))
        {
            $model->setState('id', $test['mock']['state_id']);
        }

        $result = $model->find($test['keys']);

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
        $this->assertEquals(1, $before, sprintf($msg, 'Failed to invoke the onBefore method'));
        $this->assertEquals(1, $beforeDisp, sprintf($msg, 'Failed to invoke the onBefore event'));
        $this->assertEquals(1, $after, sprintf($msg, 'Failed to invoke the onAfter method'));
        $this->assertEquals(1, $afterDisp, sprintf($msg, 'Failed to invoke the onAfter event'));
    }

    /**
     * @group           DataModel
     * @group           DataModelForceDelete
     * @covers          FOF30\Model\DataModel::forceDelete
     * @dataProvider    DataModelCrudDataprovider::getTestForceDelete
     */
    public function testForceDelete($test, $check)
    {
        $before = 0;
        $after  = 0;
        $msg    = 'DataModel::forceDelete %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        // I am passing those methods so I can double check if the method is really called
        $methods = array(
            'onBeforeDelete' => function() use(&$before){
                $before++;
            },
            'onAfterDelete' => function() use(&$after){
                $after++;
            }
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('getId', 'findOrFail', 'reset'), array(static::$container, $config, $methods));
        $model->expects($this->once())->method('reset')->willReturn(null);
        $model->expects($this->any())->method('getId')->willReturn($test['mock']['id']);
        $model->expects($check['find'] ? $this->once() : $this->never())->method('findOrFail')->willReturn(null);

        // Let's mock the dispatcher, too. So I can check if events are really triggered
        $dispatcher = $this->getMock('\\FOF30\\Event\\Dispatcher', array('trigger'), array(static::$container));
        $dispatcher->expects($this->exactly(2))->method('trigger')->withConsecutive(
            array($this->equalTo('onBeforeDelete')),
            array($this->equalTo('onAfterDelete'))
        );

        ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);

        $result = $model->delete($test['id']);

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
        $this->assertEquals(1, $before, sprintf($msg, 'Failed to call the onBefore method'));
        $this->assertEquals(1, $after, sprintf($msg, 'Failed to call the onAfter method'));

        // Now let's check if the record was really deleted
        $db = \JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from($db->qn('#__foftest_bares'))
            ->where($db->qn('foftest_bare_id').' = '.$db->q($check['id']));
        $count = $db->setQuery($query)->loadResult();

        $this->assertEquals(0, $count, sprintf($msg, 'Failed to actually delete the record in the database'));
    }

    /**
     * @group           DataModel
     * @group           DataModelForceDelete
     * @covers          FOF30\Model\DataModel::forceDelete
     */
    public function testForceDeleteException()
    {
        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $model = new DataModelStub(static::$container, $config);

        $this->setExpectedException('FOF30\Model\DataModel\Exception\RecordNotLoaded');

        $model->forceDelete();
    }

    /**
     * @group           DataModel
     * @group           DataModelFirstOrCreate
     * @covers          FOF30\Model\DataModel::firstOrCreate
     * @dataProvider    DataModelCrudDataprovider::getTestFirstOrCreate
     */
    public function testFirstOrCreate($test, $check)
    {
        $msg = 'DataModel::firstOrCreate %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $fakeCollection = new ClosureHelper(array(
            'first' => function() use ($test){
                return $test['mock']['first'];
            }
        ));

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('get', 'create'), array(static::$container, $config));
        $model->expects($this->once())->method('get')->willReturn($fakeCollection);
        $model->expects($check['create'] ? $this->once() : $this->never())->method('create');

        $result = $model->firstOrCreate(array());

        if($check['result'] == 'object')
        {
            $this->assertInstanceOf('\\FOF30\\Model\\DataModel', $result, sprintf($msg, 'Returned the wrong value'));
        }
        else
        {
            $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong value'));
        }
    }

    /**
     * @group           DataModel
     * @group           DataModelCreate
     * @covers          FOF30\Model\DataModel::create
     */
    public function testCreate()
    {
        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('reset', 'bind', 'save'), array(static::$container, $config));
        $model->expects($this->once())->method('reset')->willReturnSelf();
        $model->expects($this->once())->method('bind')->willReturnSelf();
        $model->expects($this->once())->method('save')->willReturnSelf();

        $model->create(array('foo' => 'bar'));
    }

    /**
     * @group           DataModel
     * @group           DataModelFirstOrFail
     * @covers          FOF30\Model\DataModel::firstOrFail
     * @dataProvider    DataModelCrudDataprovider::getTestFirstOrFail
     */
    public function testFirstOrFail($test, $check)
    {
        $msg = 'DataModel::firstOrFail %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $fakeCollection = new ClosureHelper(array(
            'first' => function() use ($test){
                return $test['mock']['first'];
            }
        ));

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('get'), array(static::$container, $config));
        $model->expects($this->once())->method('get')->willReturn($fakeCollection);

        if($check['exception'])
        {
            $this->setExpectedException('FOF30\Model\DataModel\Exception\NoItemsFound');
        }

        $result = $model->firstOrFail(array());

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong value'));
    }

    /**
     * @group           DataModel
     * @group           DataModelFirstOrNew
     * @covers          FOF30\Model\DataModel::firstOrNew
     * @dataProvider    DataModelCrudDataprovider::getTestFirstOrNew
     */
    public function testFirstOrNew($test, $check)
    {
        $msg = 'DataModel::firstOrNew %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $fakeCollection = new ClosureHelper(array(
            'first' => function() use ($test){
                return $test['mock']['first'];
            }
        ));

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('get', 'reset'), array(static::$container, $config));
        $model->expects($this->once())->method('get')->willReturn($fakeCollection);
        $model->expects($check['reset'] ? $this->once() : $this->never())->method('reset');

        $result = $model->firstOrNew(array());

        if($check['result'] == 'object')
        {
            $this->assertInstanceOf('\\FOF30\\Model\\DataModel', $result, sprintf($msg, 'Returned the wrong value'));
        }
        else
        {
            $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong value'));
        }
    }

    /**
     * @group           DataModel
     * @group           DataModelStore
     * @covers          FOF30\Model\DataModel::store
     * @dataProvider    DataModelCrudDataprovider::getTestStore
     */
    public function testStore($test, $check)
    {
        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $model  = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('save'), array(static::$container, $config));

        if($test['exception'])
        {
            $model->expects($this->once())->method('save')->willThrowException(new \Exception());
        }

        $result = $model->store();

        $this->assertEquals($check['result'], $result, 'DataModel::store Returned the wrong value');
    }

    /**
     * @group           DataModel
     * @group           DataModelLoad
     * @covers          FOF30\Model\DataModel::load
     * @dataProvider    DataModelCrudDataprovider::getTestLoad
     */
    public function testLoad($test, $check)
    {
        $config = array(
            'idFieldName' => 'foftest_bare_id',
            'tableName'   => '#__foftest_bares'
        );

        $model  = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('reset', 'findOrFail'), array(static::$container, $config));
        $model->expects($check['reset'] ? $this->once() : $this->never())->method('reset');

        if($test['exception'])
        {
            $model->expects($this->once())->method('findOrFail')->willThrowException(new \Exception());
        }

        $result = $model->load(null, $test['reset']);

        $this->assertEquals($check['result'], $result, 'DataModel::load Returned the wrong value');
    }

    /**
     * @group           DataModel
     * @group           DataModelCanDelete
     * @covers          FOF30\Model\DataModel::canDelete
     * @dataProvider    DataModelCrudDataprovider::getTestCanDelete
     */
    public function testCanDelete($test, $check)
    {
        $config = array(
            'idFieldName' => 'fakeapp_parent_id',
            'tableName'   => '#__fakeapp_parents'
        );

        $model = new DataModelStub(static::$container, $config);

        if($check['exception'])
        {
            $this->setExpectedException($check['exception'], $check['message']);
        }

        if($test['load'])
        {
            $model->find($test['load']);
        }

        $model->canDelete($test['id'], $test['joins']);

        // Fake assertion to prevent PHPUnit from complaining
        $this->assertTrue(true);
    }
}
