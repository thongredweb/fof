<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Platform;


use FOF30\Platform\Joomla\Platform;
use FOF30\Tests\Helpers\Application\MockLanguage;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\MockSession;
use FOF30\Tests\Helpers\Platform\UserForAdminAuth;
use FOF30\Tests\Helpers\ReflectionHelper;

/**
 * @covers FOF30\Platform\Joomla\Platform::<protected>
 * @covers FOF30\Platform\Joomla\Platform::<private>
 */
class PlatformJoomlaTest extends FOFTestCase
{
	/** @var Platform The object being tested */
	protected $platform = null;

	protected function setUp()
	{
		parent::setUp();

		$this->saveFactoryState();
		$this->platform = new Platform(static::$container);
	}

	protected function tearDown()
	{
		$this->restoreFactoryState();
		MockSession::$user = null;

		parent::tearDown();
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::checkExecution
	 */
	public function testCheckExecution()
	{
		$this->assertTrue($this->platform->checkExecution());
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::raiseError
	 */
	public function testRaiseError()
	{
		$this->setExpectedException('\Exception', 'Test', 123);

		$this->platform->raiseError(123, 'Test');
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::isCli
	 * @covers FOF30\Platform\Joomla\Platform::isCliAdmin
	 *
	 * @dataProvider FOF30\Tests\Platform\PlatformJoomlaProvider::getTestIsCli
	 */
	public function testIsCli($mockApplicationType, $expected, $message)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($mockApplicationType);

		$actual = $this->platform->isCli();

		$this->assertEquals($expected, $actual, $message);
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::isBackend
	 * @covers FOF30\Platform\Joomla\Platform::isCliAdmin
	 *
	 * @dataProvider FOF30\Tests\Platform\PlatformJoomlaProvider::getTestIsBackend
	 */
	public function testIsBackend($mockApplicationType, $expected, $message)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($mockApplicationType);

		$actual = $this->platform->isBackend();

		$this->assertEquals($expected, $actual, $message);
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::isFrontend
	 * @covers FOF30\Platform\Joomla\Platform::isCliAdmin
	 *
	 * @dataProvider FOF30\Tests\Platform\PlatformJoomlaProvider::getTestIsFrontend
	 */
	public function testIsFrontend($mockApplicationType, $expected, $message)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($mockApplicationType);

		$actual = $this->platform->isFrontend();

		$this->assertEquals($expected, $actual, $message);
	}

	/**
	 * @param $mockApplicationType
	 */
	private function forceApplicationTypeAndResetPlatformCliAdminCache($mockApplicationType)
	{
		$_SERVER['HTTP_HOST'] = 'www.example.com';
		$config = \JFactory::getConfig(JPATH_SITE . '/configuration.php');
		\JFactory::$session = $this->getMockSession();

		// Get the correct mock application
		switch ($mockApplicationType)
		{
			case 'cli':
				$mockApplication = new \JApplicationCli();
				break;

			case 'site':
			default:
				$mockApplication = new \JApplicationSite(null, $config);
				break;

			case 'admin':
				$mockApplication = new \JApplicationAdministrator(null, $config);
				break;

			case 'exception':
				$mockApplication = new \Exception('This is not an application');
		}

		// Set the mock application to JFactory
		\JFactory::$application = $mockApplication;

		// Reset Platform's internal cache
		$reflector = new \ReflectionClass('FOF30\\Platform\\Joomla\\Platform');
		$propIsCli = $reflector->getProperty('isCLI');
		$propIsCli->setAccessible(true);
		$propIsCli->setValue(null);
		$propIsAdmin = $reflector->getProperty('isAdmin');
		$propIsAdmin->setAccessible(true);
		$propIsAdmin->setValue(null);
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::getPlatformBaseDirs
	 */
	public function testGetPlatformBaseDirs()
	{
		$baseDirs = $this->platform->getPlatformBaseDirs();

		$expectedDirs = array(
			'root'   => JPATH_ROOT,
			'public' => JPATH_ROOT,
			'admin'  => JPATH_ROOT . '/administrator',
			'tmp'    => JPATH_ROOT . '/tmp',
			'log'    => JPATH_ROOT . '/logs'
		);

		$this->assertInternalType('array', $baseDirs);

		foreach ($expectedDirs as $k => $v)
		{
			$this->assertArrayHasKey($k, $baseDirs, "Platform directories must contain $k key");
			$this->assertEquals($v, $baseDirs[$k], "Platform directories key $k must have value $v");
		}
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::getComponentBaseDirs
	 *
	 * @dataProvider FOF30\Tests\Platform\PlatformJoomlaProvider::getTestComponentBaseDirs
	 */
	public function testGetComponentBaseDirs($area, $expectedMain, $expectedAlt)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($area);

		$actual = $this->platform->getComponentBaseDirs('com_foobar');

		$this->assertInternalType('array', $actual);

		$expected = array(
			'site' => JPATH_SITE . '/components/com_foobar',
			'admin' => JPATH_SITE . '/administrator/components/com_foobar',
			'main' => JPATH_SITE . '/' . $expectedMain,
			'alt' => JPATH_SITE . '/' . $expectedAlt,
		);

		foreach ($expected as $k => $v)
		{
			$this->assertArrayHasKey($k, $actual, "Component directories must contain $k key");
			$this->assertEquals($v, $actual[$k], "Platform directories key $k must have value $v with app type $area ");
		}
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::getTemplate
	 */
	public function testGetTemplate()
	{
		$_SERVER['HTTP_HOST'] = 'www.example.com';

		\JFactory::$session = $this->getMockSession();

		$expected = \JFactory::getApplication('site')->getTemplate();
		$actual = $this->platform->getTemplate();

		$this->assertEquals($expected, $actual, "getTemplate() must return the application's template");
	}


	/**
	 * @covers FOF30\Platform\Joomla\Platform::getUser
	 */
	public function testGetUser()
	{
		$_SERVER['HTTP_HOST'] = 'www.example.com';

		$fakeSession = $this->getMockSession();
		\JFactory::$session = $fakeSession;

		// Required to let JFactory know which application to load
		\JFactory::getApplication('site');

		$actual = $this->platform->getUser();

		$user = \JFactory::$session->get('user');

		$this->assertInstanceOf('\JUser', $actual, "getUser() must return a JUser object");
		$this->assertEquals($user, $actual, "getUser() must return the requested user object");
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::getDocument
	 */
	public function testGetDocument()
	{
		$_SERVER['HTTP_HOST'] = 'www.example.com';

		$fakeSession = $this->getMockSession();
		\JFactory::$session = $fakeSession;

		// Required to let JFactory know which application to load
		\JFactory::getApplication('site');

		$expected = \JFactory::getDocument();

		$reflector = new \ReflectionClass('FOF30\\Platform\\Joomla\\Platform');
		$propIsCli = $reflector->getProperty('isCLI');
		$propIsCli->setAccessible(true);
		$propIsCli->setValue(true);
		$propIsAdmin = $reflector->getProperty('isAdmin');
		$propIsAdmin->setAccessible(true);
		$propIsAdmin->setValue(false);

		// CLI app: null document
		$actual = $this->platform->getDocument();
		$this->assertNull($actual, "CLI app must return a null document");

		$propIsCli->setValue(false);
		$actual = $this->platform->getDocument();

		$this->assertInstanceOf('\JDocument', $actual, "getDocument() must return a JDocument object");
		$this->assertEquals($expected, $actual, "getDocument() must return the document from JFactory");
	}


	/**
	 * @covers FOF30\Platform\Joomla\Platform::getLanguage
	 *
	 */
	public function testGetLanguage()
	{
		$expected = \JFactory::getLanguage();
		$actual = $this->platform->getLanguage();

		$this->assertInstanceOf('\JLanguage', $actual, "getLanguage() must return a JLanguage object");
		$this->assertEquals($expected, $actual, "getLanguage() must return the language object from JFactory");
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::getDbo
	 *
	 */
	public function testGetDbo()
	{
		$expected = \JFactory::getDbo();
		$actual = $this->platform->getDbo();

		$this->assertInstanceOf('\JDatabaseDriver', $actual, "getDbo() must return a JDatabaseDriver object");
		$this->assertEquals($expected, $actual, "getDbo() must return the database object from JFactory");
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::getTemplateSuffixes
	 *
	 * @XXXdataProvider FOF30\Tests\Platform\PlatformJoomlaProvider::getTestGetTemplateSuffixes
	 *
	 */
	public function testGetTemplateSuffixes()
	{
		$jversion = new \JVersion;

		if (substr($jversion->RELEASE, 0, 2) != '3.')
		{
			$this->markTestIncomplete('testGetTemplateSuffixes will only run on Joomla! 3');
		}

		$expected = array(
			'.j3' . substr($jversion->RELEASE, 2),
			'.j3'
		);

		$actual = $this->platform->getTemplateSuffixes();

		$this->assertEquals($expected, $actual, "getTemplateSuffixes must return two suffixes, for the minor and major Joomla! CMS release");
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::getTemplateOverridePath
	 *
	 * @dataProvider FOF30\Tests\Platform\PlatformJoomlaProvider::getTestGetTemplateOverridePath
	 *
	 */
	public function testGetTemplateOverridePath($applicationType, $component, $absolute, $expected, $message)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($applicationType);

		if ($applicationType != 'cli')
		{
			$app = \JFactory::getApplication();
			$fakeTemplate = (object)array(
				'template' => 'system'
			);
			ReflectionHelper::setValue($app, 'template', $fakeTemplate);
		}

		$actual = $this->platform->getTemplateOverridePath($component, $absolute);

		$this->assertEquals($expected, $actual, $message);
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::loadTranslations
	 *
	 * @dataProvider FOF30\Tests\Platform\PlatformJoomlaProvider::getTestLoadTranslations
	 *
	 */
	public function testLoadTranslations($appType, $regularPath, $otherPath, $message)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($appType);

		$mockLanguage = MockLanguage::create($this);
		MockLanguage::$loadedLanguages = array();
		\JFactory::$language = $mockLanguage;

		$this->platform->loadTranslations('com_foobar');

		$expected = array(
			// $extension = 'joomla', $basePath = JPATH_BASE, $lang = null, $reload = false, $default = true
			array('com_foobar', $regularPath, 'en-GB', true, true),
			array('com_foobar', $regularPath, null, true, true),
			array('com_foobar', $otherPath, 'en-GB', true, true),
			array('com_foobar', $otherPath, null, true, true),
		);

		$this->assertEquals($expected, MockLanguage::$loadedLanguages, $message);
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::authorizeAdmin
	 *
	 * @dataProvider FOF30\Tests\Platform\PlatformJoomlaProvider::getTestAuthorizeAdmin
	 *
	 */
	public function testAuthorizeAdmin($appType, $auths, $expected, $message)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($appType);

		$fakeUser = new UserForAdminAuth();
		$fakeUser->allowedAuths = $auths;
		MockSession::$user = $fakeUser;

		$actual = $this->platform->authorizeAdmin('com_foobar');

		$this->assertEquals($expected, $actual, $message);
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::authorise
	 *
	 * @dataProvider FOF30\Tests\Platform\PlatformJoomlaProvider::getTestAuthorise
	 *
	 */
	public function testAuthorise($appType, $auths, $assetName, $action, $expected, $message)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($appType);

		$fakeUser = new UserForAdminAuth();
		$fakeUser->allowedAuths = $auths;
		MockSession::$user = $fakeUser;

		$actual = $this->platform->authorise($action, $assetName);

		$this->assertEquals($expected, $actual, $message);
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::getDate
	 */
	public function testGetDate()
	{
		$date = $this->platform->getDate('now', 'GMT', true);
		$this->assertInstanceOf('\\JDate', $date, "getDate must return a JDate object");

		$date = $this->platform->getDate('now', 'GMT', false);
		$this->assertInstanceOf('\\JDate', $date, "getDate must return a JDate object");
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::getCacheObject
	 *
	 * @group platformJoomlaCache
	 */
	public function testGetCacheObject()
	{
		// Force-enable the cache
		$config = \JFactory::getConfig();
		$config->set('caching', 1);
		$config->set('cachetime', 15);

		// Make sure there is no cache object set anywhere
		$false = false;
		$cache = \JFactory::getCache('fof', '');
		$cache->store($false, 'cache', 'fof');

		$reflector = new \ReflectionClass($this->platform);
		$propCache = $reflector->getProperty('_cache');
		$propCache->setAccessible(true);
		$propCache->setValue($this->platform, null);

		// Invoke the getCacheObject and assert its data type
		$method = $reflector->getMethod('getCacheObject');
		$method->setAccessible(true);
		$object = $method->invoke($this->platform, true);

		$this->assertInstanceOf('\\JRegistry', $object, 'Cache object must be an instance of JRegistry');

		// Make sure we had no data
		$foo = $object->get('foo', null);
		$this->assertNull($foo, 'The returned cache object must be empty when there is no cache set anywhere');

		// Save an object into the cache
		$object->set('foo', 'bar');
		$cache->store($object, 'cache', 'fof');

		// Kill the cache in the object
		$propCache->setValue($this->platform, null);

		// Invoke the getCacheObject and assert its data type
		$method = $reflector->getMethod('getCacheObject');
		$method->setAccessible(true);
		$object = $method->invoke($this->platform, true);

		$this->assertInstanceOf('\\JRegistry', $object, 'Cache object must be an instance of JRegistry');

		// Make sure we have data
		$foo = $object->get('foo', null);
		$this->assertEquals('bar', $foo, 'The returned cached object must not be empty when there is a cache set');
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::setCache
	 * @covers FOF30\Platform\Joomla\Platform::getCacheObject
	 * @covers FOF30\Platform\Joomla\Platform::saveCache
	 *
	 * @group platformJoomlaCache
	 */
	public function testSetCache()
	{
		// Force-enable the cache
		$config = \JFactory::getConfig();
		$config->set('caching', 1);
		$config->set('cachetime', 15);

		// Make sure there is no cache object set anywhere
		$false = false;
		$cache = \JFactory::getCache('fof', '');
		$cache->store($false, 'cache', 'fof');

		$reflector = new \ReflectionClass($this->platform);
		$propCache = $reflector->getProperty('_cache');
		$propCache->setAccessible(true);
		$propCache->setValue($this->platform, null);

		// Invoke the getCacheObject and assert its data type
		$method = $reflector->getMethod('getCacheObject');
		$method->setAccessible(true);
		$object = $method->invoke($this->platform, true);

		$this->assertInstanceOf('\\JRegistry', $object, 'Cache object must be an instance of JRegistry');

		// Make sure we had no data
		$foo = $object->get('foo', null);
		$this->assertNull($foo, 'The returned cache object must be empty when there is no cache set anywhere');

		// Save an object into the cache
		$this->platform->setCache('foo', 'bar');

		// Kill the cache in the object
		$propCache->setValue($this->platform, null);

		// Invoke the getCacheObject and assert its data type
		$method = $reflector->getMethod('getCacheObject');
		$method->setAccessible(true);
		$object = $method->invoke($this->platform, true);

		$this->assertInstanceOf('\\JRegistry', $object, 'Cache object must be an instance of JRegistry');

		// Make sure we had no data
		$foo = $object->get('foo', null);
		$this->assertEquals('bar', $foo, 'The returned cached object must not be empty when there is a cache set');
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::getCache
	 * @covers FOF30\Platform\Joomla\Platform::getCacheObject
	 *
	 * @group platformJoomlaCache
	 */
	public function testGetCache()
	{
		// Force-enable the cache
		$config = \JFactory::getConfig();
		$config->set('caching', 1);
		$config->set('cachetime', 15);

		// Make sure there is no cache object set anywhere
		$false = false;
		$cache = \JFactory::getCache('fof', '');
		$cache->store($false, 'cache', 'fof');

		$reflector = new \ReflectionClass($this->platform);
		$propCache = $reflector->getProperty('_cache');
		$propCache->setAccessible(true);
		$propCache->setValue($this->platform, null);

		// Make sure we had no data
		$foo = $this->platform->getCache('foo', null);
		$this->assertNull($foo, 'The returned cache object must be empty when there is no cache set anywhere');

		// Save an object into the cache
		$this->platform->setCache('foo', 'bar');

		// Kill the cache in the object
		$propCache->setValue($this->platform, null);

		// Make sure we have data
		$foo = $this->platform->getCache('foo', null);
		$this->assertEquals('bar', $foo, 'The returned cached object must not be empty when there is a cache set');
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::clearCache
	 *
	 * @group platformJoomlaCache
	 */
	public function testClearCache()
	{
		// Force-enable the cache
		$config = \JFactory::getConfig();
		$config->set('caching', 1);
		$config->set('cachetime', 15);

		// Make sure there is no cache object set anywhere
		$false = false;
		$cache = \JFactory::getCache('fof', '');
		$cache->store($false, 'cache', 'fof');

		$reflector = new \ReflectionClass($this->platform);
		$propCache = $reflector->getProperty('_cache');
		$propCache->setAccessible(true);
		$propCache->setValue($this->platform, null);

		// Make sure we had no data
		$foo = $this->platform->getCache('foo', null);
		$this->assertNull($foo, 'The returned cache object must be empty when there is no cache set anywhere');

		// Save an object into the cache
		$this->platform->setCache('foo', 'bar');

		// Kill the cache in the object
		$propCache->setValue($this->platform, null);

		// Make sure we have data
		$foo = $this->platform->getCache('foo', null);
		$this->assertEquals('bar', $foo, 'The returned cached object must not be empty when there is a cache set');

		// Clear the cache
		$this->platform->clearCache();

		// Kill the cache in the object
		$propCache->setValue($this->platform, null);

		// Make sure we had no data
		$foo = $this->platform->getCache('foo', null);
		$this->assertNull($foo, 'The returned cache object must be empty when there is no cache set any more');
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::loginUser
	 *
	 * @XXXdataProvider FOF30\Tests\Platform\PlatformJoomlaProvider::getTestLoginUser
	 *
	 */
	public function testLoginUser()
	{
		// TODO
		$this->markTestIncomplete('Not yet implemented');
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::logoutUser
	 *
	 * @XXXdataProvider FOF30\Tests\Platform\PlatformJoomlaProvider::getTestLogoutUser
	 *
	 */
	public function testLogoutUser()
	{
		// TODO
		$this->markTestIncomplete('Not yet implemented');
	}

	/**
	 * @covers FOF30\Platform\Joomla\Platform::getUserStateFromRequest
	 *
	 * @XXXdataProvider FOF30\Tests\Platform\PlatformJoomlaProvider::getTestGetUserStateFromRequest
	 *
	 */
	public function testGetUserStateFromRequest()
	{
		// TODO
		$this->markTestIncomplete('Not yet implemented');
	}
}