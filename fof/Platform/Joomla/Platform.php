<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Platform\Joomla;

use FOF30\Inflector\Inflector;
use FOF30\Input\Input;
use FOF30\Platform\Base\Platform as BasePlatform;

defined('_JEXEC') or die;

/**
 * Part of the FOF Platform Abstraction Layer.
 *
 * This implements the platform class for Joomla! 3
 *
 * @since    2.1
 */
class Platform extends BasePlatform
{
	/** @var null|bool Is this a CLI application? */
	protected static $isCLI = null;

	/** @var null|bool Is this an administrator application? */
	protected static $isAdmin = null;

	/**
	 * The table and table field cache object, used to speed up database access
	 *
	 * @var  \JRegistry|null
	 */
	private $_cache = null;

	/**
	 * Checks if the current script is run inside a valid CMS execution
	 *
	 * @see PlatformInterface::checkExecution()
	 *
	 * @return bool
	 */
	public function checkExecution()
	{
		return defined('_JEXEC');
	}

	/**
	 * Raises an error, using the logic requested by the CMS (PHP Exception or dedicated class)
	 *
	 * @param   integer  $code
	 * @param   string   $message
	 *
	 * @return  void
	 *
	 * @throws  \Exception
	 */
	public function raiseError($code, $message)
	{
		throw new \Exception($message, $code);
	}

	/**
	 * Main function to detect if we're running in a CLI environment and we're admin
	 *
	 * @return  array  isCLI and isAdmin. It's not an associative array, so we can use list.
	 */
	protected function isCliAdmin()
	{
		if (is_null(static::$isCLI) && is_null(static::$isAdmin))
		{
			try
			{
				if (is_null(\JFactory::$application))
				{
					static::$isCLI = true;
				}
				else
				{
					$app = \JFactory::getApplication();
					static::$isCLI = $app instanceof \Exception || $app instanceof \JApplicationCli;
				}
			}
			catch (\Exception $e)
			{
				static::$isCLI = true;
			}

			if (static::$isCLI)
			{
				static::$isAdmin = false;
			}
			else
			{
				static::$isAdmin = !\JFactory::$application ? false : \JFactory::getApplication()->isAdmin();
			}
		}

		return array(static::$isCLI, static::$isAdmin);
	}

	/**
	 * Returns absolute path to directories used by the CMS.
	 *
	 * @see PlatformInterface::getPlatformBaseDirs()
	 *
	 * @return  array  A hash array with keys root, public, admin, tmp and log.
	 */
	public function getPlatformBaseDirs()
	{
		return array(
			'root'   => JPATH_ROOT,
			'public' => JPATH_SITE,
			'admin'  => JPATH_ADMINISTRATOR,
			'tmp'    => \JFactory::getConfig()->get('tmp_path'),
			'log'    => \JFactory::getConfig()->get('log_path')
		);
	}

	/**
	 * Returns the base (root) directories for a given component.
	 *
	 * @param   string $component   The name of the component. For Joomla! this
	 *                              is something like "com_example"
	 *
	 * @see PlatformInterface::getComponentBaseDirs()
	 *
	 * @return  array  A hash array with keys main, alt, site and admin.
	 */
	public function getComponentBaseDirs($component)
	{
		if (!$this->isBackend())
		{
			$mainPath = JPATH_SITE . '/components/' . $component;
			$altPath = JPATH_ADMINISTRATOR . '/components/' . $component;
		}
		else
		{
			$mainPath = JPATH_ADMINISTRATOR . '/components/' . $component;
			$altPath = JPATH_SITE . '/components/' . $component;
		}

		return array(
			'main'  => $mainPath,
			'alt'   => $altPath,
			'site'  => JPATH_SITE . '/components/' . $component,
			'admin' => JPATH_ADMINISTRATOR . '/components/' . $component,
		);
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
		return \JFactory::getApplication()->getTemplate($params);
	}

	/**
	 * Get application-specific suffixes to use with template paths. This allows
	 * you to look for view template overrides based on the application version.
	 *
	 * @return  array  A plain array of suffixes to try in template names
	 */
	public function getTemplateSuffixes()
	{
		$jversion = new \JVersion;
		$versionParts = explode('.', $jversion->RELEASE);
		$majorVersion = array_shift($versionParts);
		$suffixes = array(
			'.j' . str_replace('.', '', $jversion->getHelpVersion()),
			'.j' . $majorVersion,
		);

		return $suffixes;
	}

	/**
	 * Return the absolute path to the application's template overrides
	 * directory for a specific component. We will use it to look for template
	 * files instead of the regular component directories. If the application
	 * does not have such a thing as template overrides return an empty string.
	 *
	 * @param   string  $component The name of the component for which to fetch the overrides
	 * @param   boolean $absolute  Should I return an absolute or relative path?
	 *
	 * @return  string  The path to the template overrides directory
	 */
	public function getTemplateOverridePath($component, $absolute = true)
	{
		list($isCli, $isAdmin) = $this->isCliAdmin();

		if (!$isCli)
		{
			if ($absolute)
			{
				$path = JPATH_THEMES . '/';
			}
			else
			{
				$path = $isAdmin ? 'administrator/templates/' : 'templates/';
			}

			if (substr($component, 0, 7) == 'media:/')
			{
				$directory = 'media/' . substr($component, 7);
			}
			else
			{
				$directory = 'html/' . $component;
			}

			$path .= $this->getTemplate() .
				'/' . $directory;
		}
		else
		{
			$path = '';
		}

		return $path;
	}

	/**
	 * Load the translation files for a given component.
	 *
	 * @param   string $component   The name of the component. For Joomla! this
	 *                              is something like "com_example"
	 *
	 * @see PlatformInterface::loadTranslations()
	 *
	 * @return  void
	 */
	public function loadTranslations($component)
	{
		if ($this->isBackend())
		{
			$paths = array(JPATH_ROOT, JPATH_ADMINISTRATOR);
		}
		else
		{
			$paths = array(JPATH_ADMINISTRATOR, JPATH_ROOT);
		}

		$jlang = $this->getLanguage();
		$jlang->load($component, $paths[0], 'en-GB', true);
		$jlang->load($component, $paths[0], null, true);
		$jlang->load($component, $paths[1], 'en-GB', true);
		$jlang->load($component, $paths[1], null, true);
	}

	/**
	 * Authorise access to the component in the back-end.
	 *
	 * @param   string $component The name of the component.
	 *
	 * @see PlatformInterface::authorizeAdmin()
	 *
	 * @return  boolean  True to allow loading the component, false to halt loading
	 */
	public function authorizeAdmin($component)
	{
		if ($this->isBackend())
		{
			// Master access check for the back-end, Joomla! 1.6 style.
			$user = \JFactory::getUser();

			if (!$user->authorise('core.manage', $component)
				&& !$user->authorise('core.admin', $component)
			)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Return a user object.
	 *
	 * @param   integer $id   The user ID to load. Skip or use null to retrieve
	 *                        the object for the currently logged in user.
	 *
	 * @see PlatformInterface::getUser()
	 *
	 * @return  \JUser  The JUser object for the specified user
	 */
	public function getUser($id = null)
	{
		return \JFactory::getUser($id);
	}

	/**
	 * Returns the JDocument object which handles this component's response.
	 *
	 * @see PlatformInterface::getDocument()
	 *
	 * @return  \JDocument
	 */
	public function getDocument()
	{
		$document = null;

		if (!$this->isCli())
		{
			try
			{
				$document = \JFactory::getDocument();
			}
			catch (\Exception $exc)
			{
				$document = null;
			}
		}

		return $document;
	}

	/**
	 * Returns an object to handle dates
	 *
	 * @param   mixed $time     The initial time
	 * @param   null  $tzOffest The timezone offset
	 * @param   bool  $locale   Should I try to load a specific class for current language?
	 *
	 * @return  \JDate object
	 */
	public function getDate($time = 'now', $tzOffest = null, $locale = true)
	{
		if ($locale)
		{
			return \JFactory::getDate($time, $tzOffest);
		}
		else
		{
			return new \JDate($time, $tzOffest);
		}
	}

	/**
	 * Return the \JLanguage instance of the CMS/application
	 *
	 * @return \JLanguage
	 */
	public function getLanguage()
	{
		return \JFactory::getLanguage();
	}

	/**
	 * Returns the database driver object of the CMS/application
	 *
	 * @return \JDatabaseDriver
	 */
	public function getDbo()
	{
		return \JFactory::getDbo();
	}

	/**
	 * This method will try retrieving a variable from the request (input) data.
	 *
	 * @param   string   $key          The user state key for the variable
	 * @param   string   $request      The request variable name for the variable
	 * @param   Input    $input        The Input object with the request (input) data
	 * @param   mixed    $default      The default value. Default: null
	 * @param   string   $type         The filter type for the variable data. Default: none (no filtering)
	 * @param   boolean  $setUserState Should I set the user state with the fetched value?
	 *
	 * @see PlatformInterface::getUserStateFromRequest()
	 *
	 * @return  mixed  The value of the variable
	 */
	public function getUserStateFromRequest($key, $request, $input, $default = null, $type = 'none', $setUserState = true)
	{
		list($isCLI, $isAdmin) = $this->isCliAdmin();

		unset($isAdmin); // Just to make phpStorm happy

		if ($isCLI)
		{
			return $input->get($request, $default, $type);
		}

		$app = \JFactory::getApplication();

		if (method_exists($app, 'getUserState'))
		{
			$old_state = $app->getUserState($key, $default);
		}
		else
		{
			$old_state = null;
		}

		$cur_state = (!is_null($old_state)) ? $old_state : $default;
		$new_state = $input->get($request, null, $type);

		// Save the new value only if it was set in this request
		if ($setUserState)
		{
			if ($new_state !== null)
			{
				$app->setUserState($key, $new_state);
			}
			else
			{
				$new_state = $cur_state;
			}
		}
		elseif (is_null($new_state))
		{
			$new_state = $cur_state;
		}

		return $new_state;
	}

	/**
	 * Load plugins of a specific type. Obviously this seems to only be required
	 * in the Joomla! CMS.
	 *
	 * @param   string $type The type of the plugins to be loaded
	 *
	 * @see PlatformInterface::importPlugin()
	 *
	 * @return void
	 *
	 * @codeCoverageIgnore
	 */
	public function importPlugin($type)
	{
		if (!$this->isCli())
		{
			\JLoader::import('joomla.plugin.helper');
			\JPluginHelper::importPlugin($type);
		}
	}

	/**
	 * Execute plugins (system-level triggers) and fetch back an array with
	 * their return values.
	 *
	 * @param   string $event The event (trigger) name, e.g. onBeforeScratchMyEar
	 * @param   array  $data  A hash array of data sent to the plugins as part of the trigger
	 *
	 * @see PlatformInterface::runPlugins()
	 *
	 * @return  array  A simple array containing the results of the plugins triggered
	 *
	 * @codeCoverageIgnore
	 */
	public function runPlugins($event, $data)
	{
		if (!$this->isCli())
		{
			return \JEventDispatcher::getInstance()->trigger($event, $data);
		}
		else
		{
			return array();
		}
	}

	/**
	 * Perform an ACL check.
	 *
	 * @param   string $action    The ACL privilege to check, e.g. core.edit
	 * @param   string $assetname The asset name to check, typically the component's name
	 *
	 * @see PlatformInterface::authorise()
	 *
	 * @return  boolean  True if the user is allowed this action
	 */
	public function authorise($action, $assetname)
	{
		if ($this->isCli())
		{
			return true;
		}

		return \JFactory::getUser()->authorise($action, $assetname);
	}

	/**
	 * Is this the administrative section of the component?
	 *
	 * @see PlatformInterface::isBackend()
	 *
	 * @return  boolean
	 */
	public function isBackend()
	{
		list ($isCli, $isAdmin) = $this->isCliAdmin();

		return $isAdmin && !$isCli;
	}

	/**
	 * Is this the public section of the component?
	 *
	 * @see PlatformInterface::isFrontend()
	 *
	 * @return  boolean
	 */
	public function isFrontend()
	{
		list ($isCli, $isAdmin) = $this->isCliAdmin();

		return !$isAdmin && !$isCli;
	}

	/**
	 * Is this a component running in a CLI application?
	 *
	 * @see PlatformInterface::isCli()
	 *
	 * @return  boolean
	 */
	public function isCli()
	{
		list ($isCli, $isAdmin) = $this->isCliAdmin();

		return !$isAdmin && $isCli;
	}

	/**
	 * Is AJAX re-ordering supported? This is 100% Joomla!-CMS specific. All
	 * other platforms should return false and never ask why.
	 *
	 * @see PlatformInterface::supportsAjaxOrdering()
	 *
	 * @return  boolean
	 *
	 * @codeCoverageIgnore
	 */
	public function supportsAjaxOrdering()
	{
		return true;
	}

	/**
	 * Is the global F0F cache enabled?
	 *
	 * @return  boolean
	 *
	 * @codeCoverageIgnore
	 */
	public function isGlobalF0FCacheEnabled()
	{
		return !(defined('JDEBUG') && JDEBUG);
	}

	/**
	 * Saves something to the cache. This is supposed to be used for system-wide
	 * F0F data, not application data.
	 *
	 * @param   string $key     The key of the data to save
	 * @param   string $content The actual data to save
	 *
	 * @return  boolean  True on success
	 */
	public function setCache($key, $content)
	{
		$registry = $this->getCacheObject();

		$registry->set($key, $content);

		return $this->saveCache();
	}

	/**
	 * Retrieves data from the cache. This is supposed to be used for system-side
	 * F0F data, not application data.
	 *
	 * @param   string $key     The key of the data to retrieve
	 * @param   string $default The default value to return if the key is not found or the cache is not populated
	 *
	 * @return  string  The cached value
	 */
	public function getCache($key, $default = null)
	{
		$registry = $this->getCacheObject();

		return $registry->get($key, $default);
	}

	/**
	 * Gets a reference to the cache object, loading it from the disk if
	 * needed.
	 *
	 * @param   boolean $force Should I forcibly reload the registry?
	 *
	 * @return  \JRegistry
	 */
	private function &getCacheObject($force = false)
	{
		// Check if we have to load the cache file or we are forced to do that
		if (is_null($this->_cache) || $force)
		{
			// Try to get data from Joomla!'s cache
			$cache = \JFactory::getCache('fof', '');
			$this->_cache = $cache->get('cache', 'fof');

			if (!is_object($this->_cache) || !($this->_cache instanceof \JRegistry))
			{
				// Create a new JRegistry object
				\JLoader::import('joomla.registry.registry');
				$this->_cache = new \JRegistry;
			}
		}

		return $this->_cache;
	}

	/**
	 * Save the cache object back to disk
	 *
	 * @return  boolean  True on success
	 */
	private function saveCache()
	{
		// Get the JRegistry object of our cached data
		$registry = $this->getCacheObject();

		$cache = \JFactory::getCache('fof', '');

		return $cache->store($registry, 'cache', 'fof');
	}

	/**
	 * Clears the cache of system-wide F0F data. You are supposed to call this in
	 * your components' installation script post-installation and post-upgrade
	 * methods or whenever you are modifying the structure of database tables
	 * accessed by F0F. Please note that F0F's cache never expires and is not
	 * purged by Joomla!. You MUST use this method to manually purge the cache.
	 *
	 * @return  boolean  True on success
	 */
	public function clearCache()
	{
		$false = false;
		$cache = \JFactory::getCache('fof', '');
		$cache->store($false, 'cache', 'fof');
	}

	/**
	 * Returns an object that holds the configuration of the current site.
	 *
	 * @return  \JRegistry
	 *
	 * @codeCoverageIgnore
	 */
	public function getConfig()
	{
		return \JFactory::getConfig();
	}

	/**
	 * logs in a user
	 *
	 * @param   array $authInfo authentification information
	 *
	 * @return  boolean  True on success
	 */
	public function loginUser($authInfo)
	{
		\JLoader::import('joomla.user.authentication');
		$options = array('remember' => false);
		$authenticate = \JAuthentication::getInstance();
		$response = $authenticate->authenticate($authInfo, $options);

		// User failed to authenticate: maybe he enabled two factor authentication?
		// Let's try again "manually", skipping the check vs two factor auth
		// Due the big mess with encryption algorithms and libraries, we are doing this extra check only
		// if we're in Joomla 2.5.18+ or 3.2.1+
		if ($response->status != \JAuthentication::STATUS_SUCCESS && method_exists('JUserHelper', 'verifyPassword'))
		{
			$db = \JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('id, password')
				->from('#__users')
				->where('username=' . $db->quote($authInfo['username']));
			$result = $db->setQuery($query)->loadObject();

			if ($result)
			{
				$match = \JUserHelper::verifyPassword($authInfo['password'], $result->password, $result->id);

				if ($match === true)
				{
					// Bring this in line with the rest of the system
					$user = \JUser::getInstance($result->id);
					$response->email = $user->email;
					$response->fullname = $user->name;

					if (\JFactory::getApplication()->isAdmin())
					{
						$response->language = $user->getParam('admin_language');
					}
					else
					{
						$response->language = $user->getParam('language');
					}

					$response->status = \JAuthentication::STATUS_SUCCESS;
					$response->error_message = '';
				}
			}
		}

		if ($response->status == \JAuthentication::STATUS_SUCCESS)
		{
			$this->importPlugin('user');
			$results = $this->runPlugins('onLoginUser', array((array)$response, $options));

			unset($results); // Just to make phpStorm happy

			\JLoader::import('joomla.user.helper');
			$userid = \JUserHelper::getUserId($response->username);
			$user = $this->getUser($userid);

			$session = \JFactory::getSession();
			$session->set('user', $user);

			return true;
		}

		return false;
	}

	/**
	 * logs out a user
	 *
	 * @return  boolean  True on success
	 */
	public function logoutUser()
	{
		\JLoader::import('joomla.user.authentication');
		$app = \JFactory::getApplication();
		$options = array('remember' => false);
		$parameters = array('username' => $this->getUser()->username);

		return $app->triggerEvent('onLogoutUser', array($parameters, $options));
	}

	/**
	 * Add a log file for FOF
	 *
	 * @param   string  $file
	 *
	 * @return  void
	 *
	 * @codeCoverageIgnore
	 */
	public function logAddLogger($file)
	{
		\JLog::addLogger(array('text_file' => $file), \JLog::ALL, array('fof'));
	}

	/**
	 * Logs a deprecated practice. In Joomla! this results in the $message being output in the
	 * deprecated log file, found in your site's log directory.
	 *
	 * @param   string $message The deprecated practice log message
	 *
	 * @return  void
	 *
	 * @codeCoverageIgnore
	 */
	public function logDeprecated($message)
	{
		\JLog::add($message, \JLog::WARNING, 'deprecated');
	}

	/**
	 * Adds a message to the application's debug log
	 *
	 * @param   string  $message
	 *
	 * @return  void
	 *
	 * @codeCoverageIgnore
	 */
	public function logDebug($message)
	{
		\JLog::add($message, \JLog::DEBUG, 'fof');
	}

	/**
	 * Returns the root URI for the request.
	 *
	 * @param   boolean $pathonly If false, prepend the scheme, host and port information. Default is false.
	 * @param   string  $path     The path
	 *
	 * @return  string  The root URI string.
	 *
	 * @codeCoverageIgnore
	 */
	public function URIroot($pathonly = false, $path = null)
	{
		\JLoader::import('joomla.environment.uri');

		return \JUri::root($pathonly, $path);
	}

	/**
	 * Returns the base URI for the request.
	 *
	 * @param   boolean $pathonly If false, prepend the scheme, host and port information. Default is false.
	 *
	 * @return  string  The base URI string
	 *
	 * @codeCoverageIgnore
	 */
	public function URIbase($pathonly = false)
	{
		\JLoader::import('joomla.environment.uri');

		return \JUri::base($pathonly);
	}

	/**
	 * Method to set a response header.  If the replace flag is set then all headers
	 * with the given name will be replaced by the new one (only if the current platform supports header caching)
	 *
	 * @param   string  $name    The name of the header to set.
	 * @param   string  $value   The value of the header to set.
	 * @param   boolean $replace True to replace any headers with the same name.
	 *
	 * @return  void
	 *
	 * @codeCoverageIgnore
	 */
	public function setHeader($name, $value, $replace = false)
	{
		\JFactory::getApplication()->setHeader($name, $value, $replace);
	}

	/**
	 * In platforms that perform header caching, send all headers.
	 *
	 * @return  void
	 *
	 * @codeCoverageIgnore
	 */
	public function sendHeaders()
	{
		\JFactory::getApplication()->sendHeaders();
	}

	/**
	 * Immediately terminate the containing application's execution
	 *
	 * @param   int  $code  The result code which should be returned by the application
	 *
	 * @return  void
	 */
	public function closeApplication($code = 0)
	{
		\JFactory::getApplication()->close($code);
	}
}