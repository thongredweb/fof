<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Helpers\Timer;

/**
 * Utility class to fake the microtime intrinsic function
 */
class FakeTimer
{
	/** @var null The microtime returned by this class */
	public static $microtime = null;

	public static function microtime($get_as_float = null)
	{
		if (empty(static::$microtime))
		{
			self::syncMicrotime();
		}

		if ($get_as_float)
		{
			return static::$microtime;
		}

		$int = sprintf('%d', floor(static::$microtime));
		$decimal = sprintf('%0.6f', static::$microtime - floor(static::$microtime));

		return $decimal . ' ' . $int;
	}

	public static function applyDelta($delta)
	{
		static::$microtime += $delta;
	}

	public static function syncMicrotime()
	{
		static::$microtime = \microtime(true);
	}
}