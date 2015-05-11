<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Hal;

use FOF30\Hal\Document;
use FOF30\Hal\Link;
use FOF30\Hal\Links;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

class DocumentTest extends FOFTestCase
{
	/** @var Document */
	protected $document = null;

	/**
	 * @covers FOF30\Hal\Document::__construct
	 */
	public function testConstruct()
	{
		$data = array(
			'test1'     => 'one',
			'test2'     => 'two',
			'testArray' => array(
				'testUno' => 'uno',
				'testDue' => 'Due',
			)
		);

		$this->document = new Document($data);

		// Make sure the internal _data property is an array
		$this->assertInternalType(
			'array',
			$this->getObjectAttribute($this->document, '_data'),
			'Line: ' . __LINE__ . '.'
		);

		// Make sure the internal _data property is the array we passed
		$this->assertEquals(
			$data,
			$this->getObjectAttribute($this->document, '_data'),
			'Line: ' . __LINE__ . '.'
		);

		// Make sure the internal _links property is an object which is an instance of Hal\Links
		$this->assertInternalType(
			'object',
			$this->getObjectAttribute($this->document, '_links'),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertTrue(
			$this->getObjectAttribute($this->document, '_links') instanceof Links,
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * @covers  FOF30\Hal\Document::addLink
	 */
	public function testAddLink()
	{
        $data = array(
            'test1'     => 'one',
            'test2'     => 'two',
            'testArray' => array(
                'testUno' => 'uno',
                'testDue' => 'Due',
            )
        );

        $myDocument = new Document($data);

		// Make sure we can add links
		$myLink = new Link('http://www.example.com/link1.json', false, 'test', null, 'A test link');
		$myDocument->addLink('foo', $myLink);

		$links = $myDocument->getLinks();
		$this->assertEquals(
			$myLink,
			$links['foo'],
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * @covers  FOF30\Hal\Document::addLink
	 */
	public function testAddLink_append()
	{
        $data = array(
            'test1'     => 'one',
            'test2'     => 'two',
            'testArray' => array(
                'testUno' => 'uno',
                'testDue' => 'Due',
            )
        );

        $myDocument = new Document($data);
        $myLink = new Link('http://www.example.com/link1.json', false, 'test', null, 'A test link');
        $myDocument->addLink('foo', $myLink);

		// Make sure trying to add links with replace=false adds, doesn't replace, links
		$myOtherLink = new Link('http://www.example.com/otherLink.json', false, 'test', null, 'Another test link');
		$myDocument->addLink('foo', $myOtherLink, false);

		$links = $this->getObjectAttribute($myDocument, '_links')->getLinks();

		$this->assertEquals(
			$myLink,
			$links['foo'][0],
			'Line: ' . __LINE__ . '.'
		);
		$this->assertEquals(
			$myOtherLink,
			$links['foo'][1],
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * @covers  FOF30\Hal\Document::addLink
	 */
	public function testAddLink_replace()
	{
        $data = array(
            'test1'     => 'one',
            'test2'     => 'two',
            'testArray' => array(
                'testUno' => 'uno',
                'testDue' => 'Due',
            )
        );

        $myDocument = new Document($data);
        $myLink = new Link('http://www.example.com/link1.json', false, 'test', null, 'A test link');
        $myDocument->addLink('foo', $myLink);

        // Make sure trying to add links with replace=false adds, doesn't replace, links
        $myOtherLink = new Link('http://www.example.com/otherLink.json', false, 'test', null, 'Another test link');
        $myDocument->addLink('foo', $myOtherLink, false);

		// Make sure trying to add links with replace=false adds, doesn't replace, links
		$myOtherLink = new Link('http://www.example.com/otherLink.json', false, 'test', null, 'Another test link');
		$myDocument->addLink('foo', $myOtherLink, true);
		$links = $myDocument->getLinks();
		$this->assertEquals(
			$myOtherLink,
			$links['foo'],
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * @covers FOF30\Hal\Document::addLinks
	 */
	public function testAddLinks()
	{
        $data = array(
            'test1'     => 'one',
            'test2'     => 'two',
            'testArray' => array(
                'testUno' => 'uno',
                'testDue' => 'Due',
            )
        );

        $myDocument = new Document($data);
        $myLink = new Link('http://www.example.com/link1.json', false, 'test', null, 'A test link');
        $myDocument->addLink('foo', $myLink);

        // Make sure trying to add links with replace=false adds, doesn't replace, links
        $myOtherLink = new Link('http://www.example.com/otherLink.json', false, 'test', null, 'Another test link');
        $myDocument->addLink('foo', $myOtherLink, false);

		$myLinks = array(
			new Link('http://www.example.com/foo.json', false, 'foobar1'),
			new Link('http://www.example.com/bar.json', false, 'foobar2'),
		);

        $myDocument->addLinks('foo', $myLinks, false);
		$links = $myDocument->getLinks();

		$this->assertNotEquals(
			$myLinks,
			$links['foo'],
			'Line: ' . __LINE__ . '.'
		);

        $myDocument->addLinks('foo', $myLinks, true);
		$links = $myDocument->getLinks();

		$this->assertEquals(
			$myLinks,
			$links['foo'],
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * @covers FOF30\Hal\Document::addData
	 */
	public function testAddData_append()
	{
        $data = array(
            'test1'     => 'one',
            'test2'     => 'two',
            'testArray' => array(
                'testUno' => 'uno',
                'testDue' => 'Due',
            )
        );

        $document = new Document($data);
        $myLink = new Link('http://www.example.com/link1.json', false, 'test', null, 'A test link');
        $document->addLink('foo', $myLink);

        // Make sure trying to add links with replace=false adds, doesn't replace, links
        $myOtherLink = new Link('http://www.example.com/otherLink.json', false, 'test', null, 'Another test link');
        $document->addLink('foo', $myOtherLink, false);

        $myLinks = array(
            new Link('http://www.example.com/foo.json', false, 'foobar1'),
            new Link('http://www.example.com/bar.json', false, 'foobar2'),
        );

        $document->addLinks('foo', $myLinks, false);

		$extraData = array('newData' => 'something');

		$document->addData($extraData, false);

		$data = $this->getObjectAttribute($document, '_data');

		$this->assertArrayHasKey(
			0,
			$data,
			'Line: ' . __LINE__ . '.'
		);

		$this->assertEquals(
			(object)$extraData,
			$data[0],
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * @covers FOF30\Hal\Document::addData
	 */
	public function testAddData_replace()
	{
        $data = array(
            'test1'     => 'one',
            'test2'     => 'two',
            'testArray' => array(
                'testUno' => 'uno',
                'testDue' => 'Due',
            )
        );

        $document = new Document($data);
        $myLink = new Link('http://www.example.com/link1.json', false, 'test', null, 'A test link');
        $document->addLink('foo', $myLink);

        // Make sure trying to add links with replace=false adds, doesn't replace, links
        $myOtherLink = new Link('http://www.example.com/otherLink.json', false, 'test', null, 'Another test link');
        $document->addLink('foo', $myOtherLink, false);

        $myLinks = array(
            new Link('http://www.example.com/foo.json', false, 'foobar1'),
            new Link('http://www.example.com/bar.json', false, 'foobar2'),
        );

        $document->addLinks('foo', $myLinks, false);

		$extraData = array('newData' => 'something');

		$document->addData($extraData, true);
		$data = $this->getObjectAttribute($document, '_data');

		$this->assertInternalType(
			'object',
			$data,
			'Line: ' . __LINE__ . '.'
		);

		$this->assertEquals(
			(object)$extraData,
			$data,
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * @covers FOF30\Hal\Document::addData
	 */
	public function testAddData_fromScratch()
	{
        $data = array(
            'test1'     => 'one',
            'test2'     => 'two',
            'testArray' => array(
                'testUno' => 'uno',
                'testDue' => 'Due',
            )
        );

        $document = new Document($data);
        $myLink = new Link('http://www.example.com/link1.json', false, 'test', null, 'A test link');
        $document->addLink('foo', $myLink);

        // Make sure trying to add links with replace=false adds, doesn't replace, links
        $myOtherLink = new Link('http://www.example.com/otherLink.json', false, 'test', null, 'Another test link');
        $document->addLink('foo', $myOtherLink, false);

        $myLinks = array(
            new Link('http://www.example.com/foo.json', false, 'foobar1'),
            new Link('http://www.example.com/bar.json', false, 'foobar2'),
        );

        $document->addLinks('foo', $myLinks, false);

		ReflectionHelper::setValue($document, '_data', null);

		$extraData = array('newData' => 'something');

		$document->addData($extraData, true);
		$data = $this->getObjectAttribute($document, '_data');

		$this->assertInternalType(
			'object',
			$data,
			'Line: ' . __LINE__ . '.'
		);

		$this->assertEquals(
			(object)$extraData,
			$data,
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * @covers FOF30\Hal\Document::addEmbedded
	 */
	public function testAddEmbedded()
	{
        $data = array(
            'test1'     => 'one',
            'test2'     => 'two',
            'testArray' => array(
                'testUno' => 'uno',
                'testDue' => 'Due',
            )
        );

        $document = new Document($data);

		$newDocument = new Document(array('newDocData' => 'something something something data'));

		// Add an embedded document
		$document->addEmbedded('childDoc', $newDocument);

		$embedded = $this->getObjectAttribute($document, '_embedded');

		$this->assertEquals(
			$newDocument,
			$embedded['childDoc'],
			'Line: ' . __LINE__ . '.'
		);

		// Append another embedded document
		$otherDocument = new Document(array('otherDocData' => 'other data'));
		$document->addEmbedded('childDoc', $otherDocument, false);
		$embedded = $this->getObjectAttribute($document, '_embedded');

		$this->assertInternalType(
			'array',
			$embedded['childDoc'],
			'Line: ' . __LINE__ . '.'
		);

		$this->assertEquals(
			$newDocument,
			$embedded['childDoc'][0],
			'Line: ' . __LINE__ . '.'
		);

		$this->assertEquals(
			$otherDocument,
			$embedded['childDoc'][1],
			'Line: ' . __LINE__ . '.'
		);

		// Replace embedded document
		$document->addEmbedded('childDoc', $otherDocument, true);
		$embedded = $this->getObjectAttribute($document, '_embedded');

		$this->assertNotInternalType(
			'array',
			$embedded['childDoc'],
			'Line: ' . __LINE__ . '.'
		);

		$this->assertEquals(
			$otherDocument,
			$embedded['childDoc'],
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * @covers FOF30\Hal\Document::getLinks
	 */
	public function testGetLinks()
	{
        $data = array(
            'test1'     => 'one',
            'test2'     => 'two',
            'testArray' => array(
                'testUno' => 'uno',
                'testDue' => 'Due',
            )
        );

        $document = new Document($data);
        $myLink = new Link('http://www.example.com/foo.json', false, 'test', null, 'A test link');
        $document->addLink('foo', $myLink);

        // Make sure trying to add links with replace=false adds, doesn't replace, links
        $myOtherLink = new Link('http://www.example.com/bar.json', false, 'test', null, 'Another test link');
        $document->addLink('foo', $myOtherLink, false);

        $extraData = array('newData' => 'something');

        $document->addData($extraData, false);

		$allLinks = $document->getLinks();

		$this->assertInternalType(
			'array',
			$allLinks,
			'Line: ' . __LINE__ . '.'
		);

		$this->assertArrayHasKey(
			'foo',
			$allLinks,
			'Line: ' . __LINE__ . '.'
		);

		$links = $document->getLinks('foo');

		$this->assertInternalType(
			'array',
			$links,
			'Line: ' . __LINE__ . '.'
		);

		$this->assertEquals(
			$links,
			$allLinks['foo'],
			'Line: ' . __LINE__ . '.'
		);

		$this->assertCount(
			2,
			$links,
			'Line: ' . __LINE__ . '.'
		);

		$this->assertTrue(
			$links[0] instanceof Link,
			'Line: ' . __LINE__ . '.'
		);

		$this->assertEquals(
			'http://www.example.com/foo.json',
			$links[0]->href,
			'Line: ' . __LINE__ . '.'
		);

		$this->assertTrue(
			$links[1] instanceof Link,
			'Line: ' . __LINE__ . '.'
		);

		$this->assertEquals(
			'http://www.example.com/bar.json',
			$links[1]->href,
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * @covers FOF30\Hal\Document::getEmbedded
	 */
	public function testGetEmbedded()
	{
        $data = array(
            'test1'     => 'one',
            'test2'     => 'two',
            'testArray' => array(
                'testUno' => 'uno',
                'testDue' => 'Due',
            )
        );

        $document = new Document($data);

        $newDocument = new Document(array('newDocData' => 'something something something data'));

        // Add an embedded document
        $document->addEmbedded('childDoc', $newDocument);

        $embedded = $this->getObjectAttribute($document, '_embedded');

        $this->assertEquals(
            $newDocument,
            $embedded['childDoc'],
            'Line: ' . __LINE__ . '.'
        );

        // Append another embedded document
        $otherDocument = new Document(array('otherDocData' => 'other data'));
        $document->addEmbedded('childDoc', $otherDocument, true);


		$allEmbedded = $this->getObjectAttribute($document, '_embedded');
		$testEmbedded = $document->getEmbedded();

		$this->assertEquals(
			$allEmbedded,
			$testEmbedded,
			'Line: ' . __LINE__ . '.'
		);

		$testEmbedded = $document->getEmbedded('childDoc');

		$this->assertEquals(
			$allEmbedded['childDoc'],
			$testEmbedded,
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * @covers FOF30\Hal\Document::getData
	 */
	public function testGetData()
	{
        $data = array(
            'test1'     => 'one',
            'test2'     => 'two',
            'testArray' => array(
                'testUno' => 'uno',
                'testDue' => 'Due',
            )
        );

        $document = new Document($data);

		$realData = $this->getObjectAttribute($document, '_data');
		$data = $document->getData();

		$this->assertEquals(
			$realData,
			$data,
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * @covers FOF30\Hal\Document::render
	 *
	 * @expectedException \RuntimeException
	 */
	public function testRender_exception()
	{
		$data = array(
			'test1'     => 'one',
			'test2'     => 'two',
			'testArray' => array(
				'testUno' => 'uno',
				'testDue' => 'Due',
			)
		);

		$document = new Document($data);

		$document->render('foobar_is_invalid');
	}

	/**
	 * @covers FOF30\Hal\Document::render
	 */
	public function testRender_success()
	{
		$data = array(
			'test1'     => 'one',
			'test2'     => 'two',
			'testArray' => array(
				'testUno' => 'uno',
				'testDue' => 'Due',
			)
		);

		$document = new Document($data);

		$data = $document->render('json');

		$this->assertInternalType(
			'string',
			$data,
			'Line: ' . __LINE__ . '.'
		);
	}
}
