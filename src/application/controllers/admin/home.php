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

require(APPPATH . '/presenters/Station_presenter.php');

class Home extends MY_Controller
{
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	public function index()
	{
		redirect('admin/logs');
	}
	
	
	
	
}

/* End of file: application/controllers/admin/home.php */