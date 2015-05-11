<?php

namespace FOF30\Tests\DataModel;

use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Stubs\Model\DataModelStub;

require_once 'PublishDataprovider.php';

/**
 * @covers      FOF30\Model\DataModel::<protected>
 * @covers      FOF30\Model\DataModel::<private>
 * @package     FOF30\Tests\DataModel
 */
class DataModelPublishTest extends DatabaseTest
{
    /**
     * @group           DataModel
     * @group           DataModelArchive
     * @covers          FOF30\Model\DataModel::archive
     * @dataProvider    PublishDataprovider::getTestArchive
     */
    public function testArchive($test, $check)
    {
        $msg     = 'DataModel::getFieldValue %s - Case: '.$check['case'];
        $methods = array();

        $config = array(
            'idFieldName' => $test['tableid'],
            'tableName'   => $test['table']
        );

        if($test['mock']['before'])
        {
            $methods['onBeforeArchive'] = $test['mock']['before'];
        }

        if($test['mock']['after'])
        {
            $methods['onAfterArchive'] = $test['mock']['after'];
        }

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('save', 'getId'), array(static::$container, $config, $methods));
        $model->expects($this->any())->method('getId')->willReturn(1);
        $model->expects($check['save'] ? $this->once() : $this->never())->method('save');

        $dispatcher = $this->getMock('\\FOF30\\Event\\Dispatcher', array('trigger'), array(static::$container));
        $dispatcher->expects($this->exactly($check['dispatcher']))->method('trigger')->withConsecutive(
            array($this->equalTo('onBeforeArchive')),
            array($this->equalTo('onAfterArchive'))
        );

        ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);
        ReflectionHelper::setValue($model, 'aliasFields', $test['mock']['alias']);

        if($check['exception'])
        {
            $this->setExpectedException('Exception');
        }

        $result = $model->archive();

        if($check['save'])
        {
            $enabled = $model->getFieldAlias('enabled');
            $value   = $model->$enabled;

            $this->assertEquals(2, $value, sprintf($msg, 'Should set the value of the enabled field to 2'));
        }

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel', $result, sprintf($msg, 'Should return an istance of itself'));
    }

    /**
     * @group           DataModel
     * @group           DataModelArchive
     * @covers          FOF30\Model\DataModel::archive
     */
    public function testArchiveException()
    {
        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $this->setExpectedException('FOF30\Model\DataModel\Exception\RecordNotLoaded');

        $model = new DataModelStub(static::$container, $config);
        $model->archive();
    }

    /**
     * @group           DataModel
     * @group           DataModelTrash
     * @covers          FOF30\Model\DataModel::trash
     * @dataProvider    PublishDataprovider::getTestTrash
     */
    public function testTrash($test, $check)
    {
        $before = 0;
        $after  = 0;
        $msg    = 'DataModel::trash %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        // I am passing those methods so I can double check if the method is really called
        $methods = array(
            'onBeforeTrash' => function() use(&$before){
                $before++;
            },
            'onAfterTrash' => function() use(&$after){
                $after++;
            }
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('save', 'getId', 'findOrFail'), array(static::$container, $config, $methods));
        $model->expects($this->any())->method('getId')->willReturn(1);
        $model->expects($check['find'] ? $this->once() : $this->never())->method('findOrFail')->willReturn(null);

        // Let's mock the dispatcher, too. So I can check if events are really triggered
        $dispatcher = $this->getMock('\\FOF30\\Event\\Dispatcher', array('trigger'), array(static::$container));
        $dispatcher->expects($this->exactly($check['dispatcher']))->method('trigger')->withConsecutive(
            array($this->equalTo('onBeforeTrash')),
            array($this->equalTo('onAfterTrash'))
        );

        ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);

        $result = $model->trash($test['id']);

        $enabled = $model->getFieldValue('enabled');

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
        $this->assertEquals($check['before'], $before, sprintf($msg, 'Failed to call the onBefore method'));
        $this->assertEquals($check['after'], $after, sprintf($msg, 'Failed to call the onAfter method'));
        $this->assertSame($check['enabled'], $enabled, sprintf($msg, 'Failed to set the enabled field'));
    }

    /**
     * @group           DataModel
     * @group           DataModelTrash
     * @covers          FOF30\Model\DataModel::trash
     * @dataProvider    PublishDataprovider::getTestTrashException
     */
    public function testTrashException($test, $check)
    {
        $config = array(
            'idFieldName' => $test['tableid'],
            'tableName'   => $test['table']
        );

        $this->setExpectedException($check['exception']);

        $model = new DataModelStub(static::$container, $config);
        $model->trash($test['id']);
    }

    /**
     * @group           DataModel
     * @group           DataModelPublish
     * @covers          FOF30\Model\DataModel::publish
     * @dataProvider    PublishDataprovider::getTestPublish
     */
    public function testPublish($test, $check)
    {
        $before = 0;
        $after  = 0;
        $msg    = 'DataModel::publish %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => $test['tableid'],
            'tableName'   => $test['table']
        );

        // I am passing those methods so I can double check if the method is really called
        $methods = array(
            'onBeforePublish' => function() use(&$before){
                $before++;
            },
            'onAfterPublish' => function() use(&$after){
                $after++;
            }
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('save', 'getId'), array(static::$container, $config, $methods));
        $model->expects($this->any())->method('getId')->willReturn(1);

        // Let's mock the dispatcher, too. So I can check if events are really triggered
        $dispatcher = $this->getMock('\\FOF30\\Event\\Dispatcher', array('trigger'), array(static::$container));
        $dispatcher->expects($this->exactly($check['dispatcher']))->method('trigger')->withConsecutive(
            array($this->equalTo('onBeforePublish')),
            array($this->equalTo('onAfterPublish'))
        );

        ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);

        $result = $model->publish($test['state']);

        $enabled = $model->getFieldValue('enabled');

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
        $this->assertEquals($check['before'], $before, sprintf($msg, 'Failed to call the onBefore method'));
        $this->assertEquals($check['after'], $after, sprintf($msg, 'Failed to call the onAfter method'));
        $this->assertEquals($check['enabled'], $enabled, sprintf($msg, 'Failed to set the enabled field'));
    }

    /**
     * @group           DataModel
     * @group           DataModelPublish
     * @covers          FOF30\Model\DataModel::publish
     */
    public function testPublishException()
    {
        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $this->setExpectedException('FOF30\Model\DataModel\Exception\RecordNotLoaded');

        $model = new DataModelStub(static::$container, $config);
        $model->publish();
    }

    /**
     * @group           DataModel
     * @group           DataModelRestore
     * @covers          FOF30\Model\DataModel::restore
     * @dataProvider    PublishDataprovider::getTestrestore
     */
    public function testRestore($test, $check)
    {
        $before = 0;
        $after  = 0;
        $msg    = 'DataModel::restore %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => $test['tableid'],
            'tableName'   => $test['table']
        );

        // I am passing those methods so I can double check if the method is really called
        $methods = array(
            'onBeforeRestore' => function() use(&$before){
                $before++;
            },
            'onAfterRestore' => function() use(&$after){
                $after++;
            }
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('save', 'getId', 'findOrFail'), array(static::$container, $config, $methods));
        $model->expects($this->any())->method('getId')->willReturn(1);
        $model->expects($check['find'] ? $this->once() : $this->never())->method('findOrFail');

        // Let's mock the dispatcher, too. So I can check if events are really triggered
        $dispatcher = $this->getMock('\\FOF30\\Event\\Dispatcher', array('trigger'), array(static::$container));
        $dispatcher->expects($this->exactly($check['dispatcher']))->method('trigger')->withConsecutive(
            array($this->equalTo('onBeforeRestore')),
            array($this->equalTo('onAfterRestore'))
        );

        ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);

        $result = $model->restore($test['id']);

        $enabled = $model->getFieldValue('enabled');

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
        $this->assertEquals($check['before'], $before, sprintf($msg, 'Failed to call the onBefore method'));
        $this->assertEquals($check['after'], $after, sprintf($msg, 'Failed to call the onAfter method'));
        $this->assertSame($check['enabled'], $enabled, sprintf($msg, 'Failed to set the enabled field'));
    }

    /**
     * @group           DataModel
     * @group           DataModelRestore
     * @covers          FOF30\Model\DataModel::restore
     */
    public function testRestoreException()
    {
        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $this->setExpectedException('FOF30\Model\DataModel\Exception\RecordNotLoaded');

        $model = new DataModelStub(static::$container, $config);
        $model->restore();
    }

    /**
     * @group           DataModel
     * @group           DataModelUnpublish
     * @covers          FOF30\Model\DataModel::unpublish
     * @dataProvider    PublishDataprovider::getTestUnpublish
     */
    public function testUnpublish($test, $check)
    {
        $before = 0;
        $after  = 0;
        $msg    = 'DataModel::unpublish %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => $test['tableid'],
            'tableName'   => $test['table']
        );

        // I am passing those methods so I can double check if the method is really called
        $methods = array(
            'onBeforeUnpublish' => function() use(&$before){
                $before++;
            },
            'onAfterUnpublish' => function() use(&$after){
                $after++;
            }
        );

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', array('save', 'getId'), array(static::$container, $config, $methods));
        $model->expects($this->any())->method('getId')->willReturn(1);

        // Let's mock the dispatcher, too. So I can check if events are really triggered
        $dispatcher = $this->getMock('\\FOF30\\Event\\Dispatcher', array('trigger'), array(static::$container));
        $dispatcher->expects($this->exactly($check['dispatcher']))->method('trigger')->withConsecutive(
            array($this->equalTo('onBeforeUnpublish')),
            array($this->equalTo('onAfterUnpublish'))
        );

        ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);

        $result = $model->unpublish();

        $enabled = $model->getFieldValue('enabled');

        $this->assertInstanceOf('\\FOF30\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
        $this->assertEquals($check['before'], $before, sprintf($msg, 'Failed to call the onBefore method'));
        $this->assertEquals($check['after'], $after, sprintf($msg, 'Failed to call the onAfter method'));
        $this->assertSame($check['enabled'], $enabled, sprintf($msg, 'Failed to set the enabled field'));
    }

    /**
     * @group           DataModel
     * @group           DataModelUnpublish
     * @covers          FOF30\Model\DataModel::unpublish
     */
    public function testUnpublishException()
    {
        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $this->setExpectedException('FOF30\Model\DataModel\Exception\RecordNotLoaded');

        $model = new DataModelStub(static::$container, $config);
        $model->unpublish();
    }
}
