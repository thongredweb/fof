<?php
namespace FOF30\Tests\DataModel;

use FOF30\Model\DataModel\Behaviour\Access;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Helpers\TestContainer;

require_once 'AccessDataprovider.php';

/**
 * @covers      FOF30\Model\DataModel\Behaviour\Access::<protected>
 * @covers      FOF30\Model\DataModel\Behaviour\Access::<private>
 * @package     FOF30\Tests\DataModel\Behaviour\Access
 */
class AccessTest extends DatabaseTest
{
    /**
     * @group           Behaviour
     * @group           AccessOnAfterBuildQuery
     * @covers          FOF30\Model\DataModel\Behaviour\Access::onAfterBuildQuery
     * @dataProvider    AccessDataprovider::getTestOnAfterBuildQuery
     */
    public function testOnAfterBuildQuery($test, $check)
    {
        $config = array(
            'idFieldName' => $test['tableid'],
            'tableName'   => $test['table']
        );

        $model = $this->getMock('\FOF30\Tests\Stubs\Model\DataModelStub', array('applyAccessFiltering'), array(static::$container, $config));
        $model->expects($check['access'] ? $this->once() : $this->never())->method('applyAccessFiltering');

        $query      = \JFactory::getDbo()->getQuery(true)->select('*')->from('test');
        $dispatcher = $model->getBehavioursDispatcher();
        $filter     = new Access($dispatcher);

        $filter->onAfterBuildQuery($model, $query);
    }

    /**
     * @group           Behaviour
     * @group           AccessOnAfterLoad
     * @covers          FOF30\Model\DataModel\Behaviour\Access::onAfterLoad
     * @dataProvider    AccessDataprovider::getTestOnAfterLoad
     */
    public function testOnAfterLoad($test, $check)
    {
        $container = new TestContainer();
        $platform  = $container->platform;
        $platform::$user = new ClosureHelper(array(
            'getAuthorisedViewLevels' => function() use ($test){
                return $test['mock']['userAccess'];
            }
        ));

        $config = array(
            'idFieldName' => $test['tableid'],
            'tableName'   => $test['table']
        );

        $model = $this->getMock('FOF30\Tests\Stubs\Model\DataModelStub', array('reset', 'getFieldValue'), array($container, $config));
        $model->expects($check['reset'] ? $this->once() : $this->never())->method('reset');
        $model->expects($this->any())->method('getFieldValue')->willReturn($test['mock']['access']);

        $query      = \JFactory::getDbo()->getQuery(true)->select('*')->from('test');
        $dispatcher = $model->getBehavioursDispatcher();
        $filter     = new Access($dispatcher);

        $filter->onAfterLoad($model, $query);
    }
}
