<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Utils;

class StringProvider
{
	public static function getTestToBool()
	{
		return array(
			// $value, $expected, $message
			array(1, true, '1 is true'),
			array('true', true, 'true is true'),
			array('yes', true, 'yes is true'),
			array('on', true, 'on is true'),
			array('enabled', true, 'enabled is true'),
			array(0, false, '0 is false'),
			array('false', false, 'false is false'),
			array('no', false, 'no is false'),
			array('off', false, 'off is false'),
			array('disabled', false, 'disabled is false'),
			array('foobar', true, 'foobar is true'),
			array('', false, 'blank is false'),
		);
	}
}