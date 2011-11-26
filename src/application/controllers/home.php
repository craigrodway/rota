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

class Home extends MY_Controller
{
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	public function index()
	{
		$data['title'] = 'Welcome to Railways on the Air';
		$data['body'] = $this->load->view('home/index', null, true);
		$this->page($data);
	}
	
	
	
	public function about()
	{
		$data['title'] = 'About the event';
		$data['body'] = '';	//$this->load->view('home/about');
		$this->page($data);
	}
	
	
	
	
	public function contact()
	{
		$data['title'] = 'Contact us';
		$data['body'] = '';
		$this->page($data);
	}
	
	
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */