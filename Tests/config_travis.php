<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 *
 * Configuration for running the unit tests on travis
 */
 
$fofTestConfig = array(
	// Point to a path where a Joomla! 3.x site is stored. It's our guinea pig!
	'3.4' => realpath(__DIR__.'/environments/3.4'),
	'3.5-dev' => realpath(__DIR__.'/environments/3.5-dev'),
	/*'dbparams' => array(
		'host'  => '127.0.0.1',
		'user'  => 'travis',
		'pwd'   => '',
		'db'    => 'fof_test'
	)*/
);
