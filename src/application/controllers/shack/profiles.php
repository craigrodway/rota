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

class Profiles extends MY_Controller
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->auth->check('user');
	}
	
	
	public function index()
	{
		$data['title'] = 'Operator profiles';
		$data['body'] = '';		// $this->load->view('shack/profiles/index', null, true);
		$this->page($data);
	}
	
	
	/**
	 * Add an operator profile the account
	 */
	public function add()
	{
		$data['title'] = 'Add an operator profile';
		$data['body'] = '&nbsp;';		// $this->load->view('shack/index', null, true);
		$data['sidebar'] = $this->load->view('shack/profiles/sidebar', '', true);
		$this->page($data);
	}
	
	
}

/* End of file: application/controllers/shack/profiles.php */