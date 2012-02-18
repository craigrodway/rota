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

class Accounts extends AdminController
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<li>', '</li>');
	}
	
	
	
	
	/**
	 * Accounts listing
	 */
	function index()
	{
		$data['accounts'] = $this->accounts_model->get_all();
		$this->layout->set_title('Accounts');
		$this->layout->set_view('content', 'admin/accounts/index');
		$this->layout->page($data);
	}
	
	
	
	
}

/* End of file: application/controllers/admin/accounts.php */