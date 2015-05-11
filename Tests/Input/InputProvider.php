<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Input;

use FOF30\Input\Input as FOFInput;

abstract class InputProvider
{
	/**
	 * Returns a sample input array used for testing
	 *
	 * @return array
	 */
	public static function getSampleInputData()
	{
		return array(
			'arrayVar'    => array(
				'one' => 1,
				'two' => 2.34,
				'lol' => 'wut'
			),
			'cmdOK'       => 'This_Is_Sparta.300-HotgateS',
			'cmdNotOK'    => 'Implode This !@#$%^&*()String {}:"|<>?,/;\'\[]123', // ImplodeThisString123
			'intOK'       => 1,
			'intNotOK1'   => 1.15, // 1
			'intNotOK2'   => 'lol1.15', // 115
			'uintOK'      => 128,
			'uintNotOK1'  => -128, // 128
			'uintNotOK2'  => -128.123, // 128123
			'floatOK'     => '3.1415',
			'floatNotOK1' => 'sp3.1415abcd', // 3.1415
			'boolOK1'     => 1, // true
			'boolOK2'     => 0, // false
			'boolNotOK1'  => 'lolwut', // true
			'wordOK'      => 'This_Is_OK',
			'wordNotOK1'  => 'This Is Not OK', // ThisisNotOK
			'wordNotOK2'  => '50 bottles_of_rum', // bottles_of_rum
			'alnumOK'     => 'ThisIsOK123',
			'alnumNotOK1' => 'This Is Not OK 123', // ThisisNotOK123
			'base64OK'    => 'abcdef01234567890/+=',
			'base64NotOK' => 'abcdef01234567890/+={}[]:";\',.\\<>?', // abcdef01234567890/+=
			'stringOK'    => 'Fifteen men on the dead man\'s chest-- ...Yo-ho-ho, and a bottle of rum!',
			'stringOK2'   => 'Δοκιμή και με UTF8 χαρακτήρες',
			'html'        => '<p>In Code We Trust</p>',
			'path'        => JPATH_SITE . '/administrator',
			'pathNotOK'   => JPATH_SITE . '/../administrator', // JPATH_SITE . '//administrator'
			'raw'         => "Αποτέλεσμα με UTF8 χαρακτήρες\nκαι\tειδικούς χαρακτήρες\rελέγχου"
		);
	}

	public static function getTestConstructor()
	{
		// source, globals initialisation, match, message

		$sampleInputData = self::getSampleInputData();

		return array(
			array($sampleInputData, array(), $sampleInputData, 'Initialising with an array'),
			array((object)$sampleInputData, array(), $sampleInputData, 'Initialising with an array'),
			array(new \JInput($sampleInputData), array(), $sampleInputData, 'Initialising with a JInput object'),
			array(new FOFInput($sampleInputData), array(), $sampleInputData, 'Initialising with a FOF Input object'),
			array('get', array('get' => $sampleInputData), $sampleInputData, 'Initialising with $_GET'),
			array('post', array('post' => $sampleInputData), $sampleInputData, 'Initialising with $_POST'),
			array('files', array('files' => $sampleInputData), $sampleInputData, 'Initialising with $_FILES'),
			array('cookie', array('cookie' => $sampleInputData), $sampleInputData, 'Initialising with $_COOKIE'),
			array('env', array('env' => $sampleInputData), $sampleInputData, 'Initialising with $_ENV'),
			array('server', array('server' => $sampleInputData), $sampleInputData, 'Initialising with $_SERVER'),
			array('request', array('request' => $sampleInputData), $sampleInputData, 'Initialising with $_REQUEST'),
			array(null, array('request' => $sampleInputData), $sampleInputData, 'Initialising with implicit request'),
		);
	}

	public static function getTestGet()
	{
		$sampleData = self::getSampleInputData();

		// $key, $filter, $expected, $message
		return array(
			array('arrayVar', 'array', $sampleData['arrayVar'], 'Get ARRAY data'),
			array('cmdOK', 'cmd', 'This_Is_Sparta.300-HotgateS', 'Get CMD data'),
			array('cmdNotOK', 'cmd', 'ImplodeThisString123', 'Get CMD data, filter applied'),
			array('intOK', 'int', 1, 'Get INT data'),
			array('intNotOK1', 'int', 1, 'Get INT data, filter applied'),
			array('intNotOK2', 'int', 1, 'Get INT data, filter applied (2)'),
			array('uintOK', 'uint', 128, 'Get UINT data'),
			array('uintNotOK1', 'uint', 128, 'Get UINT data, filter applied'),
			array('uintNotOK2', 'uint', 128, 'Get UINT data, filter applied (2)'),
			array('floatOK', 'float', 3.1415, 'Get FLOAT data'),
			array('floatNotOK1', 'float', 3.1415, 'Get FLOAT data, filter applied'),
			array('floatOK', 'double', 3.1415, 'Get DOUBLE data'),
			array('floatNotOK1', 'double', 3.1415, 'Get DOUBLE data, filter applied'),
			array('boolOK1', 'bool', true, 'Get BOOL data'),
			array('boolOK2', 'bool', false, 'Get BOOL data'),
			array('boolOK1', 'boolean', true, 'Get BOOLEAN data'),
			array('boolOK2', 'boolean', false, 'Get BOOLEAN data'),
			array('wordOK', 'word', 'This_Is_OK', 'Get WORD data'),
			array('wordNotOK1', 'word', 'ThisIsNotOK', 'Get WORD data, filtered'),
			array('wordNotOK2', 'word', 'bottles_of_rum', 'Get WORD data, filtered (2)'),
			array('alnumOK', 'alnum', 'ThisIsOK123', 'Get ALNUM data'),
			array('alnumNotOK1', 'alnum', 'ThisIsNotOK123', 'Get ALNUM data, filtered'),
			array('base64OK', 'base64', 'abcdef01234567890/+=', 'Get BASE64 data'),
			array('base64NotOK', 'base64', 'abcdef01234567890/+=', 'Get BASE64 data, filtered'),
			array('stringOK', 'string', 'Fifteen men on the dead man\'s chest-- ...Yo-ho-ho, and a bottle of rum!', 'Get STRING data, explicit'),
			array('stringOK', 'whatever', 'Fifteen men on the dead man\'s chest-- ...Yo-ho-ho, and a bottle of rum!', 'Get STRING data, implicit'),
			array('stringOK2', 'string', 'Δοκιμή και με UTF8 χαρακτήρες', 'Get STRING data, explicit, UTF8'),
			array('stringOK2', 'whatever', 'Δοκιμή και με UTF8 χαρακτήρες', 'Get STRING data, implicit, UTF8'),
			array('html', 'html', 'In Code We Trust', 'Get HTML data, filtered'),
			//array('path', 'path', JPATH_SITE . '/administrator', 'Get PATH data'),
			//array('pathNotOK', 'path', JPATH_SITE . '//administrator', 'Get PATH data, filtered'),
			array('raw', 'raw', "Αποτέλεσμα με UTF8 χαρακτήρες\nκαι\tειδικούς χαρακτήρες\rελέγχου", 'Get RAW data'),
			array('IDoNotExist', 'raw', null, 'Not existing key returns default value'),
		);
	}

	public static function getTestMagicCall()
	{
		$originalTests = self::getTestGet();
		// The getArray is not a magic method, it's an entirely different thing. Looking for consistency in Joomla!? LOL!
		array_shift($originalTests);

		return $originalTests;
	}
}
