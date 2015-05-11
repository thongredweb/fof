<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Helpers;

use FOF30\Platform\Joomla\Platform as PlatformJoomla;

/**
 * A specialised Joomla! platform abstraction class which can lie about running under CLI, frontend or backend.
 */
class TestJoomlaPlatform extends PlatformJoomla
{
	/** @var bool Should this platform instance report running under CLI mode? */
	public static $isCli = false;

	/** @var bool Should this platform instance report running under Joomla! backend? */
	public static $isAdmin = false;

	/** @var string|null The template name reported by this class */
	public static $template = null;

	/** @var array|null The template suffixes to return e.g. ['.j32', '.j3'] and so on */
	public static $templateSuffixes = null;

	/** @var array|null The platform base directories to return */
	public static $baseDirs = null;

    /** @var object|null The current user */
    public static $user = null;

    public static $uriBase = null;

    /** @var \Closure Supply a closure to perform additional checks */
    public static $authorise = null;

    /** @var \Closure Supply a closure to perform additional checks */
    public static $getUserStateFromRequest = null;

    /** @var \Closure Supply a closure to perform additional checks */
    public static $runPlugins = null;

    /**
     * Resets all the mock variables to their default value
     */
    public function reset()
    {
        static::$isCli            = false;
        static::$isAdmin          = false;
        static::$template         = null;
        static::$templateSuffixes = null;
        static::$baseDirs         = null;
        static::$user             = null;
        static::$uriBase          = null;
        static::$authorise        = null;
        static::$runPlugins       = null;

        static::$getUserStateFromRequest = null;
    }

    public function getUser($id = null)
    {
        if(isset(static::$user))
        {
            return static::$user;
        }

        return parent::getUser($id);
    }

    public function URIbase($pathonly = false)
    {
        if(isset(static::$uriBase))
        {
            return static::$uriBase;
        }

        return parent::URIbase($pathonly);
    }

    public function authorise($action, $assetname)
    {
        if(is_callable(static::$authorise))
        {
            return call_user_func_array(static::$authorise, array($action, $assetname));
        }

        return parent::authorise($action, $assetname);
    }

	/**
	 * Main function to detect if we're running in a CLI environment and we're admin. This method is designed to lie.
	 *
	 * @return  array  isCLI and isAdmin. It's not an associative array, so we can use list.
	 */
	protected function isCliAdmin()
	{
		return array(self::$isCli, self::$isAdmin);
	}

	/**
	 * Returns the application's template name
	 *
	 * @param   boolean|array  $params  An optional associative array of configuration settings
	 *
	 * @return  string  The template name. System is the fallback.
	 */
	public function getTemplate($params = false)
	{
		if (is_null(self::$template))
		{
			return parent::getTemplate($params);
		}

		return self::$template;
	}

	public function getTemplateSuffixes()
	{
		if (is_null(self::$templateSuffixes))
		{
			return parent::getTemplateSuffixes();
		}

		return self::$templateSuffixes;
	}

	public function getPlatformBaseDirs()
	{
		if (is_null(self::$baseDirs))
		{
			return parent::getPlatformBaseDirs();
		}

		return self::$baseDirs;
	}

	/**
	 * Sync the isCli / isAdmin with the real values
	 */
	public function resetIsCliAdmin()
	{
		list(self::$isCli, self::$isAdmin) = parent::isCliAdmin();
	}

    public function getUserStateFromRequest($key, $request, $input, $default = null, $type = 'none', $setUserState = true)
    {
        if(is_callable(static::$getUserStateFromRequest))
        {
            return call_user_func_array(static::$getUserStateFromRequest, array($key, $request, $input, $default, $type, $setUserState));
        }

        return parent::getUserStateFromRequest($key, $request, $input, $default, $type, $setUserState);
    }

    public function runPlugins($event, $data)
    {
        if(is_callable(static::$runPlugins))
        {
            return call_user_func_array(static::$runPlugins, array($event, $data));
        }

        return parent::runPlugins($event, $data);
    }
}