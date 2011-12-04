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

class MY_Controller extends CI_Controller
{
	
	
	private $_tpl;		// template
	public $data;		// view data
	
	
	function __construct()
	{
		parent::__construct();
		// Set template
		$this->_tpl = 'template/layout';
		// Enable profiling or not
		$this->output->enable_profiler($this->config->item('profiler'));
	}
	
	
	/** 
	 * Load the page with supplied data
	 */
	function page($data)
	{
		// header with menu
		$header['type'] = 'normal';
		$header['nav_main'] = $this->menu_model->nav_main();
		
		// Add shack menu if logged in
		if ($this->auth->logged_in())
		{
			$header['nav_shack'] = $this->menu_model->nav_shack();
		}
		
		// Admin user?
		if ($this->auth->logged_in() && $this->session->userdata('type') == 'admin')
		{
			$header['nav_admin'] = $this->menu_model->admin();
			$header['type'] = 'admin';
		}
		
		$default['header'] = $this->load->view('template/header', $header, true);
		
		// sidebar
		$sidebar['news'] = array();
		$default['sidebar'] = $this->load->view('template/sidebar', $sidebar, true);
		
		// Default body contents
		$default['body'] = '';
		
		// Merge supplied data array with local data
		$data = array_merge($default, $data);
		
		if (array_key_exists('hide_sidebar', $data)) $data['sidebar'] = null;
		
		$this->load->view($this->_tpl, $data);
	}
	
	
}




class AdminController extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->auth->check('admin');
	}
	
}