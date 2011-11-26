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

class Accounts_model extends CI_Model
{
	
	
	public $lasterr;
	
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('string_helper');
	}
	
	
	
	
	function create_account($data = array(), $send_email = true)
	{
		if (!isset('email', $data))
		{
			return false;
		}
		
		$email = $data['email'];
		$password = (isset($data['password']) 
			? $data['password']
			: random_string('alnum');
		
		 $this->db->insert('accounts', array(
		 	'email' => $email,
			'password' => $this->auth->encrypt_password($password)
		 ))
		
	}


}

/* End of file: application/models/accounts_model.php */