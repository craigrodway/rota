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
	
	
	
	
	/**
	 * Get all accounts
	 */
	function get_all($page = NULL, $limit = NULL)
	{
		$this->db->order_by('r_name', 'asc');
		
		// Only limit results if specified
		if ($page !== NULL && $limit !== NULL)
		{
			$this->db->limit($limit, $page);
		}
		
		$query = $this->db->get('accounts');
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}
	
	
	
	
	function create_account($data = array(), $send_email = FALSE)
	{
		if (!isset($data['email']))
		{
			return FALSE;
		}
		
		$verify = random_string('alnum', 10);
		
		$this->db->insert('accounts', array(
			'a_email' => trim($data['email']),
			'a_created' => date('Y-m-d H:i:s'),
			'a_enabled' => 'N',
			'a_verify' => $verify
		));
		
		if ($send_email == FALSE)
		{
			return $verify;
		}
		else
		{
			$this->load->library('parser');
			$this->load->library('email');
			$parsedata['verifyurl'] = site_url('account/verify/' . $verify);
			$mailbody = $this->parser->parse('emails/create-account', $parsedata, TRUE);
			
			$this->email->from('no-reply@barac.org.uk', 'ROTA Admin');
			$this->email->to(trim($data['email']));
			$this->email->subject('Railways on the Air account verification');
			$this->email->message($mailbody);
			$this->email->send();
		}
		
	}
	
	
	
	
	/**
	 * Find and retrieve an account by the verification code
	 */
	function find_by_verify($code = NULL)
	{
		if (!$code) return FALSE;
		
		$sql = 'SELECT * FROM accounts WHERE a_verify = ? LIMIT 1';
		$query = $this->db->query($sql, array($code));
		if ($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			return FALSE;
		}
	}
	
	
	
	
	/**
	 * With the supplied verification code, set the account status 
	 * to verified and enabled.
	 */
	function verify($code = NULL)
	{
		if (!$code) return false;
		
		$sql = "UPDATE accounts SET a_verify = NULL, a_enabled = 'Y'
				WHERE a_verify = ?";
		$query = $this->db->query($sql, array($code));
		if ($this->db->affected_rows() == 1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	
	
	
	/**
	 * Set an account's password to a new value
	 */
	function set_password($account_id = NULL, $password = NULL)
	{
		if (!$account_id) return FALSE;
		if (!$password) return FALSE;
		
		$hashed = $this->auth->hash_password($password);
		
		$sql = 'UPDATE accounts SET a_password = ?
				WHERE a_id = ? LIMIT 1';
		$query = $this->db->query($sql, array($hashed, $account_id));
		return ($this->db->affected_rows() == 1);
	}


}

/* End of file: application/models/accounts_model.php */