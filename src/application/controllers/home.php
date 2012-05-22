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
		$this->layout->set_title('Welcome to Railways on the Air');
	}
	
	
	
	public function about()
	{
		$this->layout->set_title('About the event');
		$this->layout->set_content('content', 'About the event');
	}
	
	
	
	
	public function contact()
	{
		$this->layout->set_title('Contact us');
		$this->layout->set_content('content', 'Contact us about the event');
	}
	
	
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */