<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Platform;


class PlatformJoomlaProvider
{
	public static function getTestIsCli()
	{
		// $mockApplicationType, $expected, $message
		return array(
			array('site', false, 'Site app is not a CLI app'),
			array('admin', false, 'Admin app is not a CLI app'),
			array('cli', true, 'Cli app is a CLI app'),
			array('exception', true, 'Exception instead of app is a CLI app'),
		);
	}

	public static function getTestIsBackend()
	{
		// $mockApplicationType, $expected, $message
		return array(
			array('site', false, 'Site app is not a backend app'),
			array('admin', true, 'Admin app is not a backend app'),
			array('cli', false, 'Cli app is not a backend app'),
			array('exception', false, 'Exception instead of app is not a backend app'),
		);
	}

	public static function getTestIsFrontend()
	{
		// $mockApplicationType, $expected, $message
		return array(
			array('site', true, 'Site app is a frontend app'),
			array('admin', false, 'Admin app is not a Frontend app'),
			array('cli', false, 'Cli app is not a Frontend app'),
			array('exception', false, 'Exception instead of app is not a Frontend app'),
		);
	}

	public static function getTestComponentBaseDirs()
	{
		// $area, $expectedMain, $expectedAlt
		return array(
			array('site', 'components/com_foobar', 'administrator/components/com_foobar'),
			array('cli', 'components/com_foobar', 'administrator/components/com_foobar'),
			array('exception', 'components/com_foobar', 'administrator/components/com_foobar'),
			array('admin', 'administrator/components/com_foobar', 'components/com_foobar'),
		);
	}

	public static function getTestGetViewTemplatePaths()
	{
		// $area, $view, $layout, $tpl, $strict, $expected, $message
		return array(
			array('site', 'item', null, null, false, array(
				'site:com_foobar/item/default',
				'site:com_foobar/items/default',
			), 'Site app, singular view, no layout, no tpl, no strict'),

			array('site', 'item', null, null, true, array(
				'site:com_foobar/item/default',
				'site:com_foobar/items/default',
			), 'Site app, singular view, no layout, no tpl, strict'),

			array('site', 'item', null, 'bar', false, array(
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
			), 'Site app, singular view, no layout, tpl bar, no strict'),

			array('site', 'item', null, 'bar', true, array(
				'site:com_foobar/item/default_bar',
				'site:com_foobar/items/default_bar',
			), 'Site app, singular view, no layout, tpl bar, strict'),

			array('site', 'item', 'foo', null, false, array(
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default',
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default',
			), 'Site app, singular view, foo layout, no tpl, no strict'),

			array('site', 'item', 'foo', null, true, array(
				'site:com_foobar/item/foo',
				'site:com_foobar/items/foo',
			), 'Site app, singular view, no layout, no tpl, strict'),

			array('site', 'item', 'foo', 'bar', false, array(
				'site:com_foobar/item/foo_bar',
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
				'site:com_foobar/items/foo_bar',
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
			), 'Site app, singular view, no layout, tpl bar, no strict'),

			array('site', 'item', 'foo', 'bar', true, array(
				'site:com_foobar/item/foo_bar',
				'site:com_foobar/items/foo_bar',
			), 'Site app, singular view, no layout, tpl bar, strict'),

			// ------

			array('site', 'items', null, null, false, array(
				'site:com_foobar/items/default',
				'site:com_foobar/item/default',
			), 'Site app, plural view, no layout, no tpl, no strict'),

			array('site', 'items', null, null, true, array(
				'site:com_foobar/items/default',
				'site:com_foobar/item/default',
			), 'Site app, plural view, no layout, no tpl, strict'),

			array('site', 'items', null, 'bar', false, array(
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
			), 'Site app, plural view, no layout, tpl bar, no strict'),

			array('site', 'items', null, 'bar', true, array(
				'site:com_foobar/items/default_bar',
				'site:com_foobar/item/default_bar',
			), 'Site app, plural view, no layout, tpl bar, strict'),

			array('site', 'items', 'foo', null, false, array(
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default',
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default',
			), 'Site app, plural view, foo layout, no tpl, no strict'),

			array('site', 'items', 'foo', null, true, array(
				'site:com_foobar/items/foo',
				'site:com_foobar/item/foo',
			), 'Site app, plural view, no layout, no tpl, strict'),

			array('site', 'items', 'foo', 'bar', false, array(
				'site:com_foobar/items/foo_bar',
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
				'site:com_foobar/item/foo_bar',
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
			), 'Site app, plural view, no layout, tpl bar, no strict'),

			array('site', 'items', 'foo', 'bar', true, array(
				'site:com_foobar/items/foo_bar',
				'site:com_foobar/item/foo_bar',
			), 'Site app, plural view, no layout, tpl bar, strict'),

			// ====================

			array('admin', 'item', null, null, false, array(
				'admin:com_foobar/item/default',
				'admin:com_foobar/items/default',
			), 'Admin app, singular view, no layout, no tpl, no strict'),

			array('admin', 'item', null, null, true, array(
				'admin:com_foobar/item/default',
				'admin:com_foobar/items/default',
			), 'Admin app, singular view, no layout, no tpl, strict'),

			array('admin', 'item', null, 'bar', false, array(
				'admin:com_foobar/item/default_bar',
				'admin:com_foobar/item/default',
				'admin:com_foobar/items/default_bar',
				'admin:com_foobar/items/default',
			), 'Admin app, singular view, no layout, tpl bar, no strict'),

			array('admin', 'item', null, 'bar', true, array(
				'admin:com_foobar/item/default_bar',
				'admin:com_foobar/items/default_bar',
			), 'Admin app, singular view, no layout, tpl bar, strict'),

			array('admin', 'item', 'foo', null, false, array(
				'admin:com_foobar/item/foo',
				'admin:com_foobar/item/default',
				'admin:com_foobar/items/foo',
				'admin:com_foobar/items/default',
			), 'Admin app, singular view, foo layout, no tpl, no strict'),

			array('admin', 'item', 'foo', null, true, array(
				'admin:com_foobar/item/foo',
				'admin:com_foobar/items/foo',
			), 'Admin app, singular view, no layout, no tpl, strict'),

			array('admin', 'item', 'foo', 'bar', false, array(
				'admin:com_foobar/item/foo_bar',
				'admin:com_foobar/item/foo',
				'admin:com_foobar/item/default_bar',
				'admin:com_foobar/item/default',
				'admin:com_foobar/items/foo_bar',
				'admin:com_foobar/items/foo',
				'admin:com_foobar/items/default_bar',
				'admin:com_foobar/items/default',
			), 'Admin app, singular view, no layout, tpl bar, no strict'),

			array('admin', 'item', 'foo', 'bar', true, array(
				'admin:com_foobar/item/foo_bar',
				'admin:com_foobar/items/foo_bar',
			), 'Admin app, singular view, no layout, tpl bar, strict'),

			// ------

			array('admin', 'items', null, null, false, array(
				'admin:com_foobar/items/default',
				'admin:com_foobar/item/default',
			), 'Admin app, plural view, no layout, no tpl, no strict'),

			array('admin', 'items', null, null, true, array(
				'admin:com_foobar/items/default',
				'admin:com_foobar/item/default',
			), 'Admin app, plural view, no layout, no tpl, strict'),

			array('admin', 'items', null, 'bar', false, array(
				'admin:com_foobar/items/default_bar',
				'admin:com_foobar/items/default',
				'admin:com_foobar/item/default_bar',
				'admin:com_foobar/item/default',
			), 'Admin app, plural view, no layout, tpl bar, no strict'),

			array('admin', 'items', null, 'bar', true, array(
				'admin:com_foobar/items/default_bar',
				'admin:com_foobar/item/default_bar',
			), 'Admin app, plural view, no layout, tpl bar, strict'),

			array('admin', 'items', 'foo', null, false, array(
				'admin:com_foobar/items/foo',
				'admin:com_foobar/items/default',
				'admin:com_foobar/item/foo',
				'admin:com_foobar/item/default',
			), 'Admin app, plural view, foo layout, no tpl, no strict'),

			array('admin', 'items', 'foo', null, true, array(
				'admin:com_foobar/items/foo',
				'admin:com_foobar/item/foo',
			), 'Admin app, plural view, no layout, no tpl, strict'),

			array('admin', 'items', 'foo', 'bar', false, array(
				'admin:com_foobar/items/foo_bar',
				'admin:com_foobar/items/foo',
				'admin:com_foobar/items/default_bar',
				'admin:com_foobar/items/default',
				'admin:com_foobar/item/foo_bar',
				'admin:com_foobar/item/foo',
				'admin:com_foobar/item/default_bar',
				'admin:com_foobar/item/default',
			), 'Admin app, plural view, no layout, tpl bar, no strict'),

			array('admin', 'items', 'foo', 'bar', true, array(
				'admin:com_foobar/items/foo_bar',
				'admin:com_foobar/item/foo_bar',
			), 'Admin app, plural view, no layout, tpl bar, strict'),

			// ====================

			array('cli', 'item', null, null, false, array(
				'site:com_foobar/item/default',
				'site:com_foobar/items/default',
			), 'CLI app, singular view, no layout, no tpl, no strict'),

			array('cli', 'item', null, null, true, array(
				'site:com_foobar/item/default',
				'site:com_foobar/items/default',
			), 'CLI app, singular view, no layout, no tpl, strict'),

			array('cli', 'item', null, 'bar', false, array(
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
			), 'CLI app, singular view, no layout, tpl bar, no strict'),

			array('cli', 'item', null, 'bar', true, array(
				'site:com_foobar/item/default_bar',
				'site:com_foobar/items/default_bar',
			), 'CLI app, singular view, no layout, tpl bar, strict'),

			array('cli', 'item', 'foo', null, false, array(
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default',
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default',
			), 'CLI app, singular view, foo layout, no tpl, no strict'),

			array('cli', 'item', 'foo', null, true, array(
				'site:com_foobar/item/foo',
				'site:com_foobar/items/foo',
			), 'CLI app, singular view, no layout, no tpl, strict'),

			array('cli', 'item', 'foo', 'bar', false, array(
				'site:com_foobar/item/foo_bar',
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
				'site:com_foobar/items/foo_bar',
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
			), 'CLI app, singular view, no layout, tpl bar, no strict'),

			array('cli', 'item', 'foo', 'bar', true, array(
				'site:com_foobar/item/foo_bar',
				'site:com_foobar/items/foo_bar',
			), 'CLI app, singular view, no layout, tpl bar, strict'),

			// ------

			array('cli', 'items', null, null, false, array(
				'site:com_foobar/items/default',
				'site:com_foobar/item/default',
			), 'CLI app, plural view, no layout, no tpl, no strict'),

			array('cli', 'items', null, null, true, array(
				'site:com_foobar/items/default',
				'site:com_foobar/item/default',
			), 'CLI app, plural view, no layout, no tpl, strict'),

			array('cli', 'items', null, 'bar', false, array(
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
			), 'CLI app, plural view, no layout, tpl bar, no strict'),

			array('cli', 'items', null, 'bar', true, array(
				'site:com_foobar/items/default_bar',
				'site:com_foobar/item/default_bar',
			), 'CLI app, plural view, no layout, tpl bar, strict'),

			array('cli', 'items', 'foo', null, false, array(
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default',
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default',
			), 'CLI app, plural view, foo layout, no tpl, no strict'),

			array('cli', 'items', 'foo', null, true, array(
				'site:com_foobar/items/foo',
				'site:com_foobar/item/foo',
			), 'CLI app, plural view, no layout, no tpl, strict'),

			array('cli', 'items', 'foo', 'bar', false, array(
				'site:com_foobar/items/foo_bar',
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
				'site:com_foobar/item/foo_bar',
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
			), 'CLI app, plural view, no layout, tpl bar, no strict'),

			array('cli', 'items', 'foo', 'bar', true, array(
				'site:com_foobar/items/foo_bar',
				'site:com_foobar/item/foo_bar',
			), 'CLI app, plural view, no layout, tpl bar, strict'),
		);
	}

	public static function getTestGetTemplateOverridePath()
	{
		// $applicationType, $component, $absolute, $expected, $message
		return array(
			array('site', 'com_foobar', false, 'templates/system/html/com_foobar', 'site, com_foobar, relative path'),
			array('site', 'com_foobar', true, JPATH_THEMES . '/system/html/com_foobar', 'site, com_foobar, absolute path'),
			array('site', 'media:/com_foobar', false, 'templates/system/media/com_foobar', 'site, media:/com_foobar, relative path'),
			array('site', 'media:/com_foobar', true, JPATH_THEMES . '/system/media/com_foobar', 'site, media:/com_foobar, absolute path'),
			array('admin', 'com_foobar', false, 'administrator/templates/system/html/com_foobar', 'admin, com_foobar, relative path'),
			array('admin', 'media:/com_foobar', false, 'administrator/templates/system/media/com_foobar', 'admin, media:/com_foobar, relative path'),
			array('cli', 'com_foobar', false, '', 'cli, com_foobar, relative path'),
			array('cli', 'com_foobar', true, '', 'cli, com_foobar, absolute path'),
			array('cli', 'media:/com_foobar', false, '', 'cli, media:/com_foobar, relative path'),
			array('cli', 'media:/com_foobar', true, '', 'cli, media:/com_foobar, absolute path'),
		);
	}

	public static function getTestLoadTranslations()
	{
		return array(
			// $appType, $regularPath, $otherPath, $message
			array('site', JPATH_ADMINISTRATOR, JPATH_SITE, "Site application, all languages must be loaded"),
			array('admin', JPATH_SITE, JPATH_ADMINISTRATOR, "Admin application, all languages must be loaded"),
			array('cli', JPATH_ADMINISTRATOR, JPATH_SITE, "CLI application, all languages must be loaded"),
			array('exception', JPATH_ADMINISTRATOR, JPATH_SITE, "Exception application, all languages must be loaded"),
		);
	}

	public static function getTestAuthorizeAdmin()
	{
		return array(
			// $appType, $auths, $expected, $message
			array('admin', array(), false, 'Admin app, no auths, not authorised'),
			array('admin', array('foo.bar#com_foobar'), false, 'Admin app, other auths, not authorised'),
			array('admin', array('core.admin#com_foobar'), true, 'Admin app, only core.admin, authorised'),
			array('admin', array('core.manage#com_foobar'), true, 'Admin app, only core.manage, authorised'),
			array('admin', array('core.manage#com_foobar', 'core.admin#com_foobar'), true, 'Admin app, all auths, authorised'),

			array('site', array(), true, 'Site app, no auths, implicitly authorised'),
			array('site', array('foo.bar#com_foobar'), true, 'Site app, other auths, implicitly authorised'),
			array('site', array('core.admin#com_foobar'), true, 'Site app, only core.admin, implicitly authorised'),
			array('site', array('core.manage#com_foobar'), true, 'Site app, only core.manage, implicitly authorised'),
			array('site', array('core.manage#com_foobar', 'core.admin#com_foobar'), true, 'Site app, all auths, implicitly authorised'),

			array('cli', array(), true, 'CLI app, no auths, implicitly authorised'),
			array('cli', array('foo.bar#com_foobar'), true, 'CLI app, other auths, implicitly authorised'),
			array('cli', array('core.admin#com_foobar'), true, 'CLI app, only core.admin, implicitly authorised'),
			array('cli', array('core.manage#com_foobar'), true, 'CLI app, only core.manage, implicitly authorised'),
			array('cli', array('core.manage#com_foobar', 'core.admin#com_foobar'), true, 'CLI app, all auths, implicitly authorised'),
		);
	}

	public static function getTestAuthorise()
	{
		return array(
			// $appType, $auths, $assetName, $action, $expected, $message

			array('site', array(), null, 'foo.bar', false, 'Site app, authorise non-existent global action'),
			array('site', array('baz.bat'), null, 'foo.bar', false, 'Site app, authorise wrong global action'),
			array('site', array('foo.bar'), null, 'foo.bar', true, 'Site app, authorise existing global action'),
			array('site', array(), 'com_foobar', 'foo.bar', false, 'Site app, authorise non-existent component action'),
			array('site', array('baz.bat#com_foobar'), 'com_foobar', 'foo.bar', false, 'Site app, authorise wrong component action'),
			array('site', array('foo.bar#com_foobar'), 'com_foobar', 'foo.bar', true, 'Site app, authorise existing component action'),

			array('admin', array(), null, 'foo.bar', false, 'Admin app, authorise non-existent global action'),
			array('admin', array('baz.bat'), null, 'foo.bar', false, 'Admin app, authorise wrong global action'),
			array('admin', array('foo.bar'), null, 'foo.bar', true, 'Admin app, authorise existing global action'),
			array('admin', array(), 'com_foobar', 'foo.bar', false, 'Admin app, authorise non-existent component action'),
			array('admin', array('baz.bat#com_foobar'), 'com_foobar', 'foo.bar', false, 'Admin app, authorise wrong component action'),
			array('admin', array('foo.bar#com_foobar'), 'com_foobar', 'foo.bar', true, 'Admin app, authorise existing component action'),

			array('cli', array(), null, 'foo.bar', true, 'CLI app, authorise non-existent global action (explicitly allowed in CLI)'),
			array('cli', array('baz.bat'), null, 'foo.bar', true, 'CLI app, authorise wrong global action (explicitly allowed in CLI)'),
			array('cli', array('foo.bar'), null, 'foo.bar', true, 'CLI app, authorise existing global action (explicitly allowed in CLI)'),
			array('cli', array(), 'com_foobar', 'foo.bar', true, 'CLI app, authorise non-existent component action (explicitly allowed in CLI)'),
			array('cli', array('baz.bat#com_foobar'), 'com_foobar', 'foo.bar', true, 'CLI app, authorise wrong component action (explicitly allowed in CLI)'),
			array('cli', array('foo.bar#com_foobar'), 'com_foobar', 'foo.bar', true, 'CLI app, authorise existing component action (explicitly allowed in CLI)'),
		);
	}
}