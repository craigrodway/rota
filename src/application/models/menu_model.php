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
		$nav[] = array('stations', 'Stations');
		$nav[] = array('railways', 'Railways');
		return $nav;
	}
	
	
	
	
	/**
	 * Logged-in user menu ('the shack')
	 */
	function loggedin()
	{
		$nav = array();
		$nav[] = array('shack/news', 'Post news update', 'news');
		$nav[] = array('shack/upload-log', 'Upload log', 'log');
		$nav[] = array('shack/account', 'My account', 'account');
		$nav[] = array('account/logout', 'Log out', 'login');
		return $nav;
	}
	
	
	
	
	/**
	 * Anonymous - register/login
	 */
	function guest()
	{
		$nav = array();
		$nav[] = array('account/create', 'Create an account', 'register');
		$nav[] = array('account/login', 'Log In', 'login');
		return $nav;
	}
	
	
	
	/**
	 * Admin navigation
	 */
	function admin()
	{
		$nav = array();
		$nav[] = array('admin/home', 'Admin home');
		$nav[] = array('admin/accounts', 'Accounts');
		$nav[] = array('admin/events', 'Events');
		$nav[] = array('admin/stations', 'Stations');
		$nav[] = array('admin/news', 'News');
		$nav[] = array('admin/railways', 'Railways');
		return $nav;
	}
	
	
	
	
}


/* End of file: application/models/menu_model.php */