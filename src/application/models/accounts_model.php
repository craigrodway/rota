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
	
	
	
	
	function create_account($data = array(), $send_email = false)
	{
		if (!isset($data['email']))
		{
			return false;
		}
		
		$verify = random_string('alnum', 10);
		
		$this->db->insert('accounts', array(
			'email' => trim($data['email']),
			'created' => date('Y-m-d H:i:s'),
			'enabled' => 'N',
			'verify' => $verify
		));
		
		if ($send_email == false)
		{
			return $verify;
		}
		else
		{
			$this->load->library('parser');
			$this->load->library('email');
			$parsedata['verifyurl'] = site_url('account/verify/' . $verify);
			$mailbody = $this->parser->parse('emails/create-account', $parsedata, true);
			
			$this->email->from('no-reply@barac.m0php.net', 'ROTA Admin');
			$this->email->to(trim($data['email']));
			$this->email->subject('Railways on the Air account verification');
			$this->email->message($mailbody);
			$this->email->send();
		}
		
	}
	
	
	
	
	/**
	 * Find and retrieve an account by the verification code
	 */
	function find_by_verify($code = null)
	{
		if (!$code) return false;
		
		$sql = 'SELECT * FROM accounts WHERE verify = ? LIMIT 1';
		$query = $this->db->query($sql, array($code));
		if ($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}
	
	
	
	
	/**
	 * With the supplied verification code, set the account status 
	 * to verified and enabled.
	 */
	function verify($code = null)
	{
		if (!$code) return false;
		
		$sql = "UPDATE accounts SET verify = NULL, enabled = 'Y'
				WHERE verify = ?";
		$query = $this->db->query($sql, array($code));
		if ($this->db->affected_rows() == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	
	
	/**
	 * Set an account's password to a new value
	 */
	function set_password($account_id = null, $password = null)
	{
		if (!$account_id) return false;
		if (!$password) return false;
		
		$hashed = $this->auth->hash_password($password);
		
		$sql = 'UPDATE accounts SET password = ?
				WHERE account_id = ? LIMIT 1';
		$query = $this->db->query($sql, array($hashed, $account_id));
		return ($this->db->affected_rows() == 1);
	}


}

/* End of file: application/models/accounts_model.php */