<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Railways on the Air
 * Copyright (C) 2011 Craig A Rodway <craig.rodway@gmail.com>
 *
 * Licensed under the Open Software License version 3.0
 * 
 * This source file is subject to the Open Software License (OSL 3.0) that is
 * bundled with this package in the files license.txt. It is also available 
 * through the world wide web at this URL:
 * http://opensource.org/licenses/OSL-3.0
 */

/**
 * The menu model contains functions that return arrays of menu items specific 
 * to pages within the app. 
 *
 * 0: Slug
 * 1: Title
 */

class Menu_model extends CI_Model
{
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 * Main navigation for guests
	 */
	function primary()
	{
		$nav = array();
		$nav[] = array('home', 'Home');
		$nav[] = array('about', 'About');
		$nav[] = array('news', 'News');
		$nav[] = array('contact', 'Contact');
		$nav[] = array('events', 'Events');
		$nav[] = array('railways/map', 'Railways');
		return $nav;
	}
	
	
	
	/**
	 * Main navigation for guests
	 */
	function subnav_railways()
	{
		$nav = array();
		$nav[] = array('railways/map', 'Map');
		$nav[] = array('railways/list', 'List');
		return $nav;
	}
	
	
	
	
	/**
	 * Logged-in user menu ('the shack')
	 */
	function loggedin()
	{
		$nav = array();
		$nav[] = array('shack/news/post', 'Post news update', 'news');
		$nav[] = array('shack/account/upload-log', 'Upload log', 'log');
		$nav[] = array('shack/account', 'My account', 'account');
		$nav[] = array('account/logout', 'Log out', 'logout');
		return $nav;
	}
	
	
	
	
	public function account()
	{
		$nav = array();
		$nav[] = array('shack/account', 'Account details');
		$nav[] = array('shack/operators', 'Radio operator profiles');
		$nav[] = array('shack/stations', 'Event registrations');
		$nav[] = array('shack/news', 'My news items');
		return $nav;
	}
	
	
	
	
	/**
	 * Anonymous - register/login
	 */
	function guest()
	{
		$nav = array();
		$nav[] = array('account', 'Create an account', 'register');
		$nav[] = array('account', 'Log In', 'login');
		return $nav;
	}
	
	
	
	/**
	 * Admin navigation
	 */
	function admin()
	{
		$nav = array();
		$nav[] = array('admin/logs', 'Logs');
		$nav[] = array('admin/accounts', 'Accounts');
		$nav[] = array('admin/operators', 'Operators');
		$nav[] = array('admin/events', 'Events');
		$nav[] = array('admin/stations', 'Stations');
		$nav[] = array('admin/news', 'News');
		$nav[] = array('admin/railways', 'Railways');
		return $nav;
	}
	
	
	
	
}


/* End of file: application/models/menu_model.php */