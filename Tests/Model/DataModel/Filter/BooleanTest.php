<?php
namespace FOF30\Tests\DataModel\Filter\Boolean;

use FOF30\Model\DataModel\Filter\Boolean;
use FOF30\Tests\Helpers\DatabaseTest;

require_once 'BooleanDataprovider.php';

/**
 * @covers      FOF30\Model\DataModel\Filter\Boolean::<protected>
 * @covers      FOF30\Model\DataModel\Filter\Boolean::<private>
 * @package     FOF30\Tests\DataModel\Filter\Boolean
 */
class BooleanTest extends DatabaseTest
{
    /**
     * @group           BooleanFilter
     * @group           BooleanFilterIsEmpty
     * @covers          FOF30\Model\DataModel\Filter\Boolean::isEmpty
     * @dataProvider    BooleanDataprovider::getTestIsEmpty
     */
    public function testIsEmpty($test, $check)
    {
        $msg    = 'Boolean::isEmpty %s - Case: '.$check['case'];
        $filter = new Boolean(\JFactory::getDbo(), (object)array('name' => 'test', 'type' => 'tinyint(1)'));

        $result = $filter->isEmpty($test['value']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to detect if a variable is empty'));
    }
}
