<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . '/presenters/Account_presenter.php');

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
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		
		$this->data['sidebar_menu'] = $this->menu_model->account();
		$this->layout->set_view('sidebar', 'shack/sidebar');
	}
	
	
	
	
	/**
	 * "My account" details page
	 */
	function index()
	{
		$a_id = $this->session->userdata('a_id');
		if ( ! $account = $this->accounts_model->get($a_id))
		{
			show_error('Could not find your account.', 404);
		}
		
		$account = new Account_presenter($account);
		
		$this->data['account'] =& $account;
		$this->layout->set_title('Account details');
		
		if ($this->input->post())
		{
			$update_email = FALSE;
			$update_password = FALSE;
			
			if ($this->input->post('a_email') !== $account->a_email())
			{
				// Email address has changed
				$this->form_validation->set_rules('a_email', 'Email address', 'duplicate_email');
				$update_email = TRUE;
			}
			
			if ($this->input->post('a_password1') && $this->input->post('a_password2'))
			{
				$this->form_validation->set_rules('a_password1', 'Password', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('a_password2', 'Password (again)', 'trim|matches[a_password1]');
				$update_password = TRUE;
			}
			
			if ($this->form_validation->run())
			{
				$data = array();
				
				if ($update_email)
				{
					$data['a_email'] = $this->input->post('a_email');
				}
				
				if ($update_password)
				{
					$data['a_password'] = $this->auth->hash_password($this->input->post('a_password1'));
				}
				
				if ($update_email || $update_password)
				{
					if ($this->accounts_model->update($a_id, $data))
					{
						$this->session->set_flashdata('success', 'Account details have been updated.');
					}
					else
					{
						$this->session->set_flashdata('error', 'Could not update account details.');
					}
				}
				
				redirect('shack/account');
			}
		}
	}
	
	
	
	
	/** 
	 * Handle the deletion of an account
	 */
	public function delete()
	{
		if ($this->input->post('delete') == 'yes')
		{
			$a_id = $this->session->userdata('a_id');
			$this->accounts_model->delete($a_id);
			$this->auth->logout();
			redirect();
		}
		else
		{
			$this->session->set_flashdata('success', 'Your account has NOT been deleted.');
			redirect('shack/account');
		}
	}
	
	
	
	
}

/* End of file: application/controllers/shack/account.php */