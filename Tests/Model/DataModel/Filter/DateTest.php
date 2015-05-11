<?php
namespace FOF30\Tests\DataModel\Date;

use FOF30\Model\DataModel\Filter\Date;
use FOF30\Tests\Helpers\DatabaseTest;

require_once 'DateDataprovider.php';
/**
 * @covers      FOF30\Model\DataModel\Filter\Date::<protected>
 * @covers      FOF30\Model\DataModel\Filter\Date::<private>
 * @package     FOF30\Tests\DataModel\Filter\Date
 */
class DateTest extends DatabaseTest
{
    /**
     * @covers      FOF30\Model\DataModel\Filter\Date::getDefaultSearchMethod
     */
    public function testGetDefaultSearchMethod()
    {
        $filter = new Date(\JFactory::getDbo(), (object)array('name' => 'test', 'type' => 'datetime'));

        $this->assertEquals('exact', $filter->getDefaultSearchMethod());
    }

    /**
     * @group           DateFilter
     * @group           DateFilterBetween
     * @covers          FOF30\Model\DataModel\Filter\Date::between
     * @dataProvider    DateDataprovider::getTestBetween
     */
    public function testBetween($test, $check)
    {
        $msg    = 'Date::between %s - Case: '.$check['case'];
        $filter = new Date(\JFactory::getDbo(), (object)array('name' => 'test', 'type' => 'datetime'));

        $result = $filter->between($test['from'], $test['to'], $test['include']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to build the correct SQL query'));
    }

    /**
     * @group           DateFilter
     * @group           DateFilterOutside
     * @covers          FOF30\Model\DataModel\Filter\Date::outside
     * @dataProvider    DateDataprovider::getTestOutside
     */
    public function testOutside($test, $check)
    {
        $msg    = 'Date::outside %s - Case: '.$check['case'];
        $filter = new Date(\JFactory::getDbo(), (object)array('name' => 'test', 'type' => 'datetime'));

        $result = $filter->outside($test['from'], $test['to'], $test['include']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to build the correct SQL query'));
    }

    /**
     * @group           DateFilter
     * @group           DateFilterInterval
     * @covers          FOF30\Model\DataModel\Filter\Date::interval
     * @dataProvider    DateDataprovider::getTestInterval
     */
    public function testInterval($test, $check)
    {
        $msg = 'Date::interval %s - Case: '.$check['case'];
        $filter = new Date(\JFactory::getDbo(), (object)array('name' => 'test', 'type' => 'datetime'));

        $result = $filter->interval($test['value'], $test['interval'], $test['include']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to build the correct SQL query'));
    }
}
