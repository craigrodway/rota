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
	
	
	
	
	/**
	 * Page to edit or create an account
	 */
	function set($a_id = NULL)
	{
		$data = array();
		
		if ($a_id)
		{
			// Editing account. Get it via ID.
			if ( ! $data['account'] = $this->accounts_model->get($a_id))
			{
				show_error('Could not find requested account.', 404);
			}
			$this->layout->set_title('Edit account');
		}
		else
		{
			// Adding new account
			$this->layout->set_title('Add a new account');
		}
		
		$this->layout->set_view('content', 'admin/accounts/addedit');
		$this->layout->page($data);
	}
	
	
	
	
	/**
	 * Add new or update account
	 */
	function save()
	{
		$a_id = $this->input->post('a_id');
		
		// Determine which validation rules to set
		if ($a_id)
		{
			// Got ID - updating.
			$this->form_validation
				->set_rules('a_email', 'Email address', 'required|trim|max_length[100]|valid_email')
				->set_rules('a_password1', 'Password', 'trim')
				->set_rules('a_password2', 'Password (again)', 'trim|matches[a_password1]')
				->set_rules('a_type', 'Account type', 'alpha')
				->set_rules('a_enabled', 'Enabled', '');
		}
		else
		{
			// No ID - adding new account
			$this->form_validation
				->set_rules('a_email', 'Email address', 'required|trim|max_length[100]|valid_email|is_unique[accounts.a_email]')
				->set_rules('a_password1', 'Password', 'trim|required|max_length[100]')
				->set_rules('a_password2', 'Password (again)', 'trim|matches[a_password1]')
				->set_rules('a_type', 'Account type', 'alpha')
				->set_rules('a_enabled', 'Enabled', '');
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			return $this->set($a_id);
		}
		else
		{
			// OK!
			
			$data['a_email'] = $this->input->post('a_email');
			$data['a_type'] = $this->input->post('a_type');
			$data['a_enabled'] = $this->input->post('a_enabled');
			
			// Set password if set
			if ($this->input->post('a_password1'))
			{
				$data['a_password'] = $this->auth->hash_password($this->input->post('a_password1'));
			}
			
			// If adding new account and verification email is requested - set flag for model to do email
			if ( ! $a_id && $this->input->post('a_verify') == 'send')
			{
				$data['send_email'] = TRUE;
			}
			
			// Do required action depending on whether an account is being created or updated
			if ($a_id)
			{
				// Update
				$op = $this->accounts_model->edit($a_id, $data);
				$ok = "Account <strong>{$data['a_email']}</strong> has been updated.";
				$err = 'An error occurred while updating the account.';
			}
			else
			{
				// Add
				$op = $this->accounts_model->add($data);
				$ok = "Account has been created for <strong>{$data['a_email']}</strong>.";
				$err = 'An error occurred while adding the account.';
			}
			
			$msg_type = ($op) ? 'success' : 'error';
			$msg = ($op) ? $ok : $err;
			$this->session->set_flashdata($msg_type, $msg);
			
			redirect('admin/accounts');
			
		}
	}
	
	
	
	
	/**
	 * Delete an account (only accepts POSTed data)
	 */
	function delete()
	{
		$id = $this->input->post('a_id');
		if ( ! $id)
		{
			redirect('admin/accounts');
		}
		
		$delete = $this->accounts_model->delete($id);
		
		if ($delete == TRUE)
		{
			$msg_type = 'success';
			$msg = 'The account has been deleted successfully.';
		}
		else
		{
			$msg_type = 'error';
			$msg = 'Problem removing account - ' . $this->accounts_model->lasterr;
		}
		$this->session->set_flashdata($msg_type, $msg);
		
		redirect('admin/accounts');
	}
	
	
	
	
}

/* End of file: application/controllers/admin/accounts.php */