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
		$this->form_validation->set_error_delimiters('<div class="alert-message block-message error">', '</div>');
	}
	
	
	
	
	function login()
	{
		if ($this->input->post('email'))
		{
			$email = trim($this->input->post('email'));
			$password = $this->input->post('password');
			
			$auth = $this->auth->login($email, $password);
			
			if (!$auth)
			{
				$this->session->set_flashdata('error', 'Error: ' . $this->auth->lasterr);
				redirect(current_url());
			}
			else
			{
				$this->session->set_flashdata('success', 'You are now been logged in!');
				redirect();
			}
		}
		else
		{
			// show form
			$data['title'] = 'Log in with your ROTA account';
			$data['body'] = $this->load->view('account/login', null, true);
			$data['sidebar'] = null;
			$this->page($data);
		}
	}
	
	
	
	
	/**
	 * Log the user out and redirect to home page
	 */
	function logout()
	{
		if ( ! $this->auth->logged_in())
		{
			redirect();
		}
		$this->auth->logout();
		redirect();
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
			$this->form_validation->set_message('is_unique', 'There is already an account with that email address.');
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
	
	
	
	
	/**
	 * Handle the landing page for verifying an account
	 */
	function verify($code = null)
	{
		if ($code == null)
		{
			// Show simple page if no code supplied
			$data['title'] = 'Create an account';
			$data['body'] = $this->load->view('account/verify1', null, true);
			$this->page($data);
		}
		else
		{
			// Look up the account using the code.
			$account = $this->accounts_model->find_by_verify($code);
			if ( ! $account)
			{
				show_error('Could not find the account specified. Please check and try again. Perhaps it has already been verified?', 404);
			}
			else
			{
				$body['account'] = $account;
				$data['title'] = 'Verify your account';
				$data['body'] = $this->load->view('account/verify2', $body, true);
				$this->page($data);
			}
		}
	}
	
	
	
	
	/**
	 * First time - set a password for the account
	 */
	function setpassword()
	{
		$this->form_validation->set_rules('password1', 'Password', 'required|min_length[4]|max_length[100]');
		$this->form_validation->set_rules('password2', 'Password confirmation', 'matches[password1]');
		
		$verify_code = $this->input->post('verify');
		$password1 = $this->input->post('password1');
		$password2 = $this->input->post('password2');
		
		if ($this->form_validation->run() == false)
		{
			return $this->verify($verify_code);
		}
		else
		{
			$account = $this->accounts_model->find_by_verify($verify_code);
			if ( ! $account) show_error('Could not find account using verification code ' . $verify_code);
			
			// Attempt to set the password. Should only fail if blank!
			$set = $this->accounts_model->set_password($account->account_id, $password1);
			
			if ( ! $set)
			{
				show_error('An error occurred when trying to set your chosen password.');
			}
			else
			{
				// Now attempt to verify & enable the account
				$verify = $this->accounts_model->verify($verify_code);
				if (!$verify)
				{
					show_error('An error occurred when trying to enable your account.');
				}
			}
			
			// OK! Now log them in
			$login = $this->auth->login($account->email, $password1);
			
			if ( ! $login)
			{
				$this->session->set_flashdata('error', 'Could not log you in: ' . $this->auth->lasterr);
			}
			else
			{
				$this->session->set_flashdata('success', 'Your password has been set and you are now logged in.');
			}
			
			redirect('shack/profiles/add');
			
		}
	}
	
	
}

/* End of file: application/controllers/account.php */