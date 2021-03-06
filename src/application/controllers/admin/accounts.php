<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH . '/presenters/Account_presenter.php');

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
	function index($page = 0)
	{
		$filter = $this->input->get(NULL, TRUE);
		
		$this->load->library('pagination');
		
		$config['base_url'] = site_url('admin/accounts/index/');
		$config['total_rows'] = $this->accounts_model->count_all();
		$config['per_page'] = 15; 
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config);
		
		$this->accounts_model->set_filter($filter)
							 ->order_by('a_email', 'asc')
							 ->limit(15, $page);
		
		$this->data['accounts'] = $this->accounts_model->get_all();
		$this->data['filter'] =& $filter;
		
		foreach ($this->data['accounts'] as &$account)
		{
			$account = new Account_presenter($account);
		}
		
		$this->layout->set_title('Accounts');
		$this->layout->set_view('links', 'admin/accounts/index-links');
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
			if ( ! $account = $this->accounts_model->get($a_id))
			{
				show_error('Could not find requested account.', 404);
			}
			$this->data['account'] = new Account_presenter($account);
			$this->layout->set_title('Edit account');
		}
		else
		{
			// Adding new account
			$this->data['account'] = new Account_presenter();
			$this->layout->set_title('Add a new account');
		}
		
		if ($this->input->post())
		{
			// Determine which validation rules to set
			if ($a_id)
			{
				// Got ID - updating.
				$this->form_validation
					->set_rules('a_email', 'Email address', 'required|trim|max_length[100]|valid_email')
					->set_rules('a_password1', 'Password', 'trim')
					->set_rules('a_password2', 'Password (again)', 'trim|matches[a_password1]')
					->set_rules('a_type', 'Account type', 'alpha')
					->set_rules('a_enabled', 'Enabled', 'integer');
			}
			else
			{
				// No ID - adding new account
				$this->form_validation
					->set_rules('a_email', 'Email address', 'required|trim|max_length[100]|valid_email|is_unique[accounts.a_email]')
					->set_rules('a_password1', 'Password', 'trim|required|max_length[100]')
					->set_rules('a_password2', 'Password (again)', 'trim|matches[a_password1]')
					->set_rules('a_type', 'Account type', 'alpha')
					->set_rules('a_enabled', 'Enabled', 'integer');
			}
			
			if ($this->form_validation->run())
			{
				// OK!
				$data = array(
					'a_email' => $this->input->post('a_email'),
					'a_type' => $this->input->post('a_type'),
					'a_enabled' => (int) $this->input->post('a_enabled'),
				);
				
				// Set password if supplied
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
					$op = $this->accounts_model->update($a_id, $data);
					$ok = "Account <strong>{$data['a_email']}</strong> has been updated.";
					$err = 'An error occurred while updating the account.';
				}
				else
				{
					// Add
					$op = $this->accounts_model->insert($data);
					$ok = "Account has been created for <strong>{$data['a_email']}</strong>.";
					$err = 'An error occurred while adding the account.';
				}
				
				$msg_type = ($op) ? 'success' : 'error';
				$msg = ($op) ? $ok : $err;
				$this->session->set_flashdata($msg_type, $msg);
				
				redirect('admin/accounts');
				
			}		// end of validation == TRUE
			
		}		// end of POST check
		
	}
	
	
	
	
	/**
	 * Delete an account (only accepts POSTed data)
	 */
	function delete()
	{
		$this->view = FALSE;
		
		$id = $this->input->post('id');
		if ( ! $id)
		{
			redirect('admin/accounts');
		}
		
		if ($this->accounts_model->delete($id))
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