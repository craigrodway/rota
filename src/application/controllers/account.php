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

class Account extends MY_Controller
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
	}
	
	
	
	
	/**
	 * Page to create an account and handle the creation
	 */
	function create($post = false)
	{
		if ($this->input->post('email'))
		{
			// Get email address and create account
			$this->form_validation->set_rules('email', 'Email address', 'required|trim|valid_email|is_unique[accounts.email]');
			$email = $this->input->post('email');
			
			if ($this->form_validation->run() == true)
			{
				$create = $this->accounts_model->create_account(array(
					'email' => $email
				), true);
				$this->session->set_flashdata('success', sprintf(
					"An email has been sent to %s - please check for further details.",
					$email
				));
				redirect('account/verify');
			}
		}
		
		$data['title'] = 'Create an account';
		$data['body'] = $this->load->view('account/create', null, true);
		$this->page($data);
		
	}
	
	
	
	function verify($code = null)
	{
		$data['title'] = 'Create an account';
		$data['body'] = $this->load->view('account/verify', null, true);
		$this->page($data);
	}
	
	
}

/* End of file: application/controllers/account.php */