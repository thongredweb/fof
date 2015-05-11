<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Utils;

use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Utils\String;

/**
 * @covers  FOF30\Utils\String::<protected>
 * @covers  FOF30\Utils\String::<private>
 */
class StringTest extends FOFTestCase
{
	/**
	 * @covers       FOF30\Utils\String::toBool
	 *
	 * @dataProvider FOF30\Tests\Utils\StringProvider::getTestToBool
	 *
	 * @param string $value
	 * @param bool   $expected
	 * @param string $message
	 */
	public function testToBool($value, $expected, $message)
	{
		$actual = String::toBool($value);

		$this->assertEquals($expected, $actual, $message);
	}
}