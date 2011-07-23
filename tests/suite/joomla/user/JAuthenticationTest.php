<?php
/**
 * @package     Joomla.UnitTest
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

require_once JPATH_PLATFORM.'/joomla/user/authentication.php';


/**
 * JAuthenticationTest
 *
 * Test class for JAuthentication.
 * Generated by PHPUnit on 2009-10-08 at 21:36:41.
 *
 * @package	Joomla.UnitTest
 * @subpackage Utilities
 * @runInSeparateProcess
 */
class JAuthenticationTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var	JAuthentication
	 */
	protected $object;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		// Fake JPluginHelper
		include_once JPATH_TESTS.'/suite/joomla/user/TestStubs/JPluginHelper.php';
		// Fake JDispatcher
		include_once JPATH_TESTS.'/suite/joomla/user/TestStubs/JDispatcher.php';
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
		
	}

	public function casesAuthentication()
	{
		// Successful authentication from the FakeAuthenticationPlugin
		$success = new JAuthenticationResponse;
		$success->status = JAuthentication::STATUS_SUCCESS;
		$success->type = 'fake';
		$success->username = 'test';
		$success->password = 'test';
		$success->fullname = 'test';
		// Failed authentication
		$failure = new JAuthenticationResponse;
		$failure->status = JAuthentication::STATUS_FAILURE;
		$failure->username = 'test';
		$failure->password = 'wrongpassword';
		$failure->fullname = 'test';

		return array(
			array(
				Array('username'=>'test', 'password'=>'test'),
				$success,
				'Testing correct username and password'
			),
			array(
				Array('username'=>'test', 'password'=>'wrongpassword'),
				$failure,
				'Testing incorrect username and password'
			)
		);
	}
	
	/**
	 * This checks for the correct Long Version.
	 *
	 * @return void
	 * @dataProvider casesAuthentication
	 */
	public function testAuthentication($input, $expect, $message)
	{
		$this->assertEquals(
			$expect,
			JAuthentication::authenticate($input),
			$message
		);
	}

	/**
	 * These are the authorisation test cases
	 * 
	 */
	public function casesAuthorise()
	{
		$cases = Array();
		$expect = new JAuthenticationResponse;
		$response = new JAuthenticationResponse;

		$response->username = 'test';
		$expect->status = JAuthentication::STATUS_SUCCESS;

		$cases[] = Array(
			clone($response),
			Array(clone($expect)),
			'Successful login'
		);
	
		$response->username = 'denied';
		$expect->status = JAuthentication::STATUS_DENIED;

		$cases[] = Array(
			clone($response),
			Array(clone($expect)),
			'Denied (blocked) login'
		);

		$response->username = 'expired';
		$expect->status = JAuthentication::STATUS_EXPIRED;
		
		$cases[] = Array(
			clone($response),
			Array(clone($expect)),
			'Expired login'
		);

		$response->username = 'unknown';
		$expect->status = JAuthentication::STATUS_UNKNOWN;

		$cases[] = Array(
			clone($response),
			Array(clone($expect)),
			'Unknown login'
		);

		return $cases;
	}
		

	/**
	 * This checks for the correct response to authorising a user
	 * 
	 * @return void
	 * @dataProvider casesAuthorise
	 */
	public function testAuthorise($input, $expect, $message)
	{
		$this->assertEquals(
			$expect,
			JAuthentication::authorise($input),
			$message
		);
	}
}

