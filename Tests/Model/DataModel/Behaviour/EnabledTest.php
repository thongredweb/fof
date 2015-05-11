<?php
namespace FOF30\Tests\DataModel;

use FOF30\Model\DataModel\Behaviour\Enabled;
use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Stubs\Model\DataModelStub;

require_once 'EnabledDataprovider.php';

/**
 * @covers      FOF30\Model\DataModel\Behaviour\Enabled::<protected>
 * @covers      FOF30\Model\DataModel\Behaviour\Enabled::<private>
 * @package     FOF30\Tests\DataModel\Behaviour\Enabled
 */
class EnabledTest extends DatabaseTest
{
    /**
     * @group           Behaviour
     * @group           EnabledOnBeforeBuildQuery
     * @covers          FOF30\Model\DataModel\Behaviour\Enabled::onBeforeBuildQuery
     * @dataProvider    EnabledDataprovider::getTestOnBeforeBuildQuery
     */
    public function testOnBeforeBuildQuery($test, $check)
    {
        $msg = 'Own::onAfterBuildQuery %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => $test['tableid'],
            'tableName'   => $test['table']
        );

        $model = new DataModelStub(static::$container, $config);

        $query      = \JFactory::getDbo()->getQuery(true)->select('*')->from('test');
        $dispatcher = $model->getBehavioursDispatcher();
        $behavior   = new Enabled($dispatcher);

        $behavior->onBeforeBuildQuery($model, $query);

        $where = ReflectionHelper::getValue($model, 'whereClauses');

        $this->assertCount($check['count'], $where, sprintf($msg, 'Failed to set the where'));
    }

    /**
     * @group           Behaviour
     * @group           EnabledOnAfterLoad
     * @covers          FOF30\Model\DataModel\Behaviour\Enabled::onAfterLoad
     * @dataProvider    EnabledDataprovider::getTestOnAfterLoad
     */
    public function testOnAfterLoad($test, $check)
    {
        $config = array(
            'idFieldName' => $test['tableid'],
            'tableName'   => $test['table']
        );

        $platform = static::$container->platform;
        $platform::$user = (object)array('id' => 99);

        $model = $this->getMock('FOF30\Tests\Stubs\Model\DataModelStub', array('reset', 'getFieldValue'), array(static::$container, $config));
        $model->expects($check['reset'] ? $this->once() : $this->never())->method('reset');
        $model->expects($this->any())->method('getFieldValue')->willReturn($test['mock']['enabled']);

        $dispatcher = $model->getBehavioursDispatcher();
        $behavior   = new Enabled($dispatcher);

        $keys = array();
        $behavior->onAfterLoad($model, $keys);
    }
}
