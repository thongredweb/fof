<?php
namespace FOF30\Tests\DataModel\Text;

use FOF30\Model\DataModel\Filter\Text;
use FOF30\Tests\Helpers\DatabaseTest;

require_once 'TextDataprovider.php';
/**
 * @covers      FOF30\Model\DataModel\Filter\Text::<protected>
 * @covers      FOF30\Model\DataModel\Filter\Text::<private>
 * @package     FOF30\Tests\DataModel\Filter\Text
 */
class TextTest extends DatabaseTest
{
    /**
     * @group       TextFilter
     * @group       TextFilterConstruct
     * @covers      FOF30\Model\DataModel\Filter\Text::__construct
     */
    public function test__construct()
    {
        $filter = new Text(\JFactory::getDbo(), (object)array('name' => 'test', 'type' => 'varchar(10)'));

        $null_value = $filter->null_value;

        $this->assertSame('', $null_value, 'Text::__construct should set the null value to an empty string');
    }

    /**
     * @group           TextFilter
     * @group           TextFilterPartial
     * @covers          FOF30\Model\DataModel\Filter\Text::partial
     * @dataProvider    TextDataprovider::getTestPartial
     */
    public function testPartial($test, $check)
    {
        $msg    = 'Text::partial %s - Case: '.$check['case'];
        $filter = new Text(\JFactory::getDbo(), (object)array('name' => 'test', 'type' => 'varchar(10)'));

        $result = $filter->partial($test['value']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to build the correct SQL query'));
    }

    /**
     * @group           TextFilter
     * @group           TextFilterExact
     * @covers          FOF30\Model\DataModel\Filter\Text::exact
     * @dataProvider    TextDataprovider::getTestExact
     */
    public function testExact($test, $check)
    {
        $msg    = 'Text::exact %s - Case: '.$check['case'];
        $filter = new Text(\JFactory::getDbo(), (object)array('name' => 'test', 'type' => 'varchar(10)'));

        $result = $filter->exact($test['value']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to build the correct SQL query'));
    }

    /**
     * @group           TextFilter
     * @group           TextFilterBetween
     * @covers          FOF30\Model\DataModel\Filter\Text::between
     */
    public function testBetween()
    {
        $filter = new Text(\JFactory::getDbo(), (object)array('name' => 'test', 'type' => 'varchar(10)'));

        $this->assertSame('', $filter->between('', ''), 'Text::between Should return an empty string');
    }

    /**
     * @group           TextFilter
     * @group           TextFilterOutside
     * @covers          FOF30\Model\DataModel\Filter\Text::outside
     */
    public function testOutside()
    {
        $filter = new Text(\JFactory::getDbo(), (object)array('name' => 'test', 'type' => 'varchar(10)'));

        $this->assertSame('', $filter->outside('', ''), 'Text::outside Should return an empty string');
    }

    /**
     * @group           TextFilter
     * @group           TextFilterInterval
     * @covers          FOF30\Model\DataModel\Filter\Text::interval
     */
    public function testInterval()
    {
        $filter = new Text(\JFactory::getDbo(), (object)array('name' => 'test', 'type' => 'varchar(10)'));

        $this->assertSame('', $filter->interval('', ''), 'Text::interval Should return an empty string');
    }
}