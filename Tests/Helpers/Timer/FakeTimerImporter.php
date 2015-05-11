<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Timer;

use FOF30\Tests\Helpers\Timer\FakeTimer;

global $fofTest_FakeTimer_Active;
$fofTest_FakeTimer_Active = false;

function microtime($get_as_float = null)
{
	return FakeTimer::microtime($get_as_float);
}