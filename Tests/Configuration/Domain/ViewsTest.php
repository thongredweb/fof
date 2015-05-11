<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Configuration\Domain;

use FOF30\Configuration\Domain\Views;
use FOF30\Tests\Helpers\FOFTestCase;

/**
 * @covers  FOF30\Configuration\Domain\Views::<protected>
 * @covers  FOF30\Configuration\Domain\Views::<private>
 */
class ViewsTest extends FOFTestCase
{
	/** @var   Views  The object to test */
	protected $object = null;

	/** @var   array  The data returned from parsing the XML file, used to test fetching data */
	protected $data = array();

	/**
	 * @return  void
	 */
	protected function setUp()
	{
		$this->object = new Views();

		$file = realpath(__DIR__ . '/../../_data/configuration/views.xml');
		$xml = simplexml_load_file($file);

		$this->object->parseDomain($xml, $this->data);
	}

	/**
	 * @covers  FOF30\Configuration\Domain\Views::parseDomain
	 *
	 * @return  void
	 */
	public function testParseDomain()
	{
		$this->data = array();

		$file = realpath(__DIR__ . '/../../_data/configuration/views.xml');
		$xml = simplexml_load_file($file);

		$this->object->parseDomain($xml, $this->data);

		$this->assertArrayHasKey('views', $this->data, 'The models key must be set');
		$views = $this->data['views'];
		$this->assertArrayHasKey('*', $views, 'We must read the star view');
		$this->assertArrayHasKey('Item', $views, 'We must read the item view');
		$this->assertArrayHasKey('Bad', $views, 'We must read the bad view');

		$starView = $views['*'];
		$this->assertArrayHasKey('acl', $starView, 'Every view must have acl key');
		$this->assertArrayHasKey('taskmap', $starView, 'Every view must have taskmap key');
		$this->assertArrayHasKey('config', $starView, 'Every view must have config key');
		$this->assertArrayHasKey('toolbar', $starView, 'Every view must have toolbar key');

		$acl = $starView['acl'];
		$this->assertArrayHasKey('browse', $acl, 'The acl key must be an array keyed by task name');
		$this->assertEquals('foobar.something', $acl['browse'], 'The acl values must be read');

		$toolbar = $starView['toolbar'];
		$this->assertArrayHasKey('browse', $toolbar, 'The toolbar key must be an array keyed by task name');
		$this->assertArrayHasKey('title', $toolbar['browse'], 'The toolbar must have a title');
		$this->assertArrayHasKey('apply', $toolbar['browse'], 'The toolbar must have the buttons specified');
		$this->assertArrayHasKey('cancel', $toolbar['browse'], 'The toolbar must have the buttons specified');
	}

	/**
	 * @covers       FOF30\Configuration\Domain\Views::get
	 * @covers       FOF30\Configuration\Domain\Views::getTaskmap
	 *
	 * @dataProvider getTestGetTaskmap
	 *
	 * @param   string $key            Key to read
	 * @param   array  $expected       Expected key or key/value pair
	 * @param   int    $included       0 = key not exists, -1 = key exists, not match content, 1 = key exists and match
	 *                                 content
	 * @param   string $message        Failure message
	 *
	 * @return  void
	 */
	public function testGetTaskmap($key, $expected, $included, $message)
	{
		$actual = $this->object->get($this->data, $key, array());

		$this->assertInternalType('array', $actual);

		foreach ($expected as $key => $value)
		{
			switch ($included)
			{
				case 0:
					$this->assertArrayNotHasKey($key, $actual, $message);
					break;

				case -1:
					$this->assertArrayHasKey($key, $actual, $message);
					$this->assertNotEquals($value, $actual[$key], $message);
					break;

				case 1:
					$this->assertArrayHasKey($key, $actual, $message);
					$this->assertEquals($value, $actual[$key], $message);
					break;
			}
		}
	}

	public function getTestGetTaskmap()
	{
		return array(
			array('*.taskmap', array('show' => 'browse'), 1, 'Taskmap: explictly named taskmap, star view'),
			array('Item.taskmap', array('show' => 'browse'), 1, 'Taskmap: implicit taskmap from star view'),
			array('Item.taskmap', array('list' => 'browse'), 1, 'Taskmap: explictly named taskmap, explicit view'),
			array('notthere.taskmap', array('show' => 'browse'), 1, 'Taskmap: explictly named taskmap, inexistant view'),
			array('Bad.taskmap', array('show' => 'show'), 1, 'Taskmap: explictly named taskmap overrides explicit star view'),
			array('Baz.taskmap', array('show' => 'show'), -1, 'Taskmap: explictly named taskmap does not override implicit star view'),
		);
	}

	/**
	 * @covers       FOF30\Configuration\Domain\Views::get
	 * @covers       FOF30\Configuration\Domain\Views::getAcl
	 *
	 * @dataProvider getTestGetAcl
	 *
	 * @param   string $key      Key to read
	 * @param   array  $expected Expected value
	 * @param   bool   $match    True if must match, false if must not match
	 * @param   string $message  Failure message
	 *
	 * @return  void
	 */
	public function testGetAcl($key, $expected, $match, $message)
	{
		$actual = $this->object->get($this->data, $key, null);

		if ($match)
		{
			$this->assertEquals($expected, $actual, $message);
		}
		else
		{
			$this->assertNotEquals($expected, $actual, $message);
		}
	}

	public function getTestGetAcl()
	{
		return array(
			array('*.acl.browse', 'foobar.something', 1, 'ACL: explictly named acl, star view'),
			array('*.acl.baz', null, 1, 'ACL: non-existent acl, star view'),
			array('Item.acl.browse', 'foobar.something', 1, 'ACL: implicitly named acl, existing view'),
			array('Item.acl.dosomething', '', 1, 'ACL: explicitly named empty acl, existing view'),
			array('Item.acl.somethingelse', 'core.manage', 1, 'ACL: explicitly named non-empty acl, existing view'),
			array('Bad.acl.browse', 'kot', 1, 'ACL: explicitly named non-empty acl, existing view, override star view'),
			array('notthere.acl.browse', 'foobar.something', 1, 'ACL: implicitly named acl, not-existent view'),
			array('notthere.acl.kot', '', 1, 'ACL: not-existent acl, not-existent view'),
		);
	}

	/**
	 * @covers       FOF30\Configuration\Domain\Views::get
	 * @covers       FOF30\Configuration\Domain\Views::getConfig
	 *
	 * @dataProvider getTestGetConfig
	 *
	 * @param   string $key      Key to read
	 * @param   array  $expected Expected value
	 * @param   bool   $match    True if must match, false if must not match
	 * @param   string $message  Failure message
	 *
	 * @return  void
	 */
	public function testGetConfig($key, $expected, $match, $message)
	{
		$actual = $this->object->get($this->data, $key, null);

		if ($match)
		{
			$this->assertEquals($expected, $actual, $message);
		}
		else
		{
			$this->assertNotEquals($expected, $actual, $message);
		}
	}

	public function getTestGetConfig()
	{
		return array(
			array('*.config.behaviors', 'filter,access', 1, 'Config: explictly named config, star view'),
			array('*.config.foobar', null, 1, 'Config: non-existent config, star view'),
			array('Item.config.behaviors', 'filter,access', 1, 'Config: implicitly named config, existing view'),
			array('Item.config.autoRouting', '3', 1, 'Config: explicitly named empty config, existing view'),
			array('Bad.config.behaviors', '', 1, 'Config: explicitly named non-empty config, existing view, override star view'),
			array('notthere.config.behaviors', 'filter,access', 1, 'Config: implicitly named config, not-existent view'),
			array('notthere.config.kot', '', 1, 'Config: not-existent config, not-existent view'),
		);
	}


	/**
	 * @covers       FOF30\Configuration\Domain\Views::get
	 * @covers       FOF30\Configuration\Domain\Views::getToolbar
	 *
	 * @dataProvider getTestGetToolbar
	 *
	 * @param   string $key            Key to read
	 * @param   array  $expected       Expected key or key/value pair
	 * @param   int    $included       0 = key not exists, -1 = key exists, not match content, 1 = key exists and match
	 *                                 content, 2 only check key
	 * @param   string $message        Failure message
	 *
	 * @return  void
	 */
	public function testGetToolbar($key, $expected, $included, $message)
	{
		$actual = $this->object->get($this->data, $key, array());

		$this->assertInternalType('array', $actual);

		foreach ($expected as $key => $value)
		{
			switch ($included)
			{
				case 0:
					$this->assertArrayNotHasKey($key, $actual, $message);
					break;

				case -1:
					$this->assertArrayHasKey($key, $actual, $message);
					$this->assertNotEquals($value, $actual[$key], $message);
					break;

				case 1:
					$this->assertArrayHasKey($key, $actual, $message);
					$this->assertEquals($value, $actual[$key], $message);
					break;

				case 2:
					$this->assertArrayHasKey($key, $actual, $message);
					break;
			}
		}
	}

	public function getTestGetToolbar()
	{
		return array(
			array('*.toolbar.browse', array('title' => array('value' => 'COM_FOOBAR_TOOLBAR_GENERIC')), 1, 'Toolbar: explictly named Toolbar, star view'),
			array('*.toolbar.notthere', array('title' => null), 0, 'Toolbar: non-existent Toolbar, star view'),
			array('Item.toolbar.browse', array('title' => array('value' => 'COM_FOOBAR_TOOLBAR_GENERIC')), 1, 'Toolbar: implicitly named Toolbar, existing view'),
			array('Item.toolbar.browse', array('title' => array('value' => 'COM_FOOBAR_TOOLBAR_GENERIC')), 1, 'Toolbar: implicitly named Toolbar, existing view'),
			array('Item.toolbar.notthere', array('title' => null), 0, 'Toolbar: non-existent Toolbar, existing view'),
			array('Item.toolbar.edit', array('title' => array('value' => 'COM_FOOBAR_TOOLBAR_ITEM')), 1, 'Toolbar: explicitly named Toolbar, existing view'),
			array('Item.toolbar.edit', array('save' => null), 2, 'Toolbar: explicitly named Toolbar, existing view'),
			array('Item.toolbar.edit', array('saveclose' => null), 2, 'Toolbar: explicitly named Toolbar, existing view'),
			array('Item.toolbar.edit', array('apply' => null), 0, 'Toolbar: explicitly named Toolbar, existing view'),
			array('Item.bad.browse', array('apply' => null), 0, 'Toolbar: explicitly named Toolbar, override star view'),
		);
	}
}