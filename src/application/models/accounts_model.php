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
		$this->db->order_by('a_email', 'asc');
		
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
	
	
	
	
	/**
	 * Get a single account by ID or email
	 *
	 * @param mixed $a_id		Specify either numeric account ID or email address
	 * @return object		Account object (excluding hashed password)
	 */
	function get($a_id = NULL)
	{
		if ( ! $a_id) return FALSE;
		
		if (is_numeric($a_id))
		{
			$sql_where = ' AND a_id = ?';
		}
		else
		{
			$sql_where = ' AND a_email = ?';
		}
		
		$sql = "SELECT
					accounts.*,
					COUNT((SELECT operators.o_id FROM operators WHERE o_a_id = a_id)) AS a_operators_count
				FROM accounts
				WHERE 1 = 1
				$sql_where
				LIMIT 1";
		$query = $this->db->query($sql, array($a_id));
		
		if ($query->num_rows() == 1)
		{
			$account = $query->row();
			unset($account->a_password);
			return $account;
		}
		else
		{
			return FALSE;
		}
	}
	
	
	
	
	/**
	 * Admin-side account creation - accept all values from $data param.
	 *
	 * @param array $data		Array of values to be set.
	 * @return int		ID of new account
	 */
	public function add($data)
	{
		$verify = ($data['send_email'] == TRUE) ? random_string('alnum', 10) : NULL;
		
		$insert = $this->db->insert('accounts', array(
			'a_email' => trim($data['a_email']),
			'a_created' => date('Y-m-d H:i:s'),
			'a_enabled' => $data['a_enabled'],
			'a_verify' => $verify
		));
		
		return $this->db->insert_id();
	}
	
	
	
	
	/**
	 * Update details for account
	 *
	 * @param int $a_id		Account ID to update
	 * @param array $data		Data to update account with (keys should match column names)
	 * @return bool		True on successful update
	 */
	function edit($a_id = NULL, $data = array())
	{
		if ( ! $a_id) return FALSE;
		if (empty($data)) return FALSE;
		
		// Don't allow these to be set
		unset($data['a_created']);
		unset($data['a_lastlogin']);
		
		$this->db->where('a_id', $a_id);
		return $this->db->update('accounts', $data);
	}
	
	
	
	
	/**
	 * Account creation function for front-end signups.
	 *
	 * Default account type will be user, it won't be enabled, and will need verification.
	 *
	 * @param array $data		Array containing email address
	 * @param bool $send_email		Whether or not to send the verification email
	 * @return mixed		Verification code on success, FALSE on error
	 */
	function create_account($data = array(), $send_email = FALSE)
	{
		if (!isset($data['email']))
		{
			return FALSE;
		}
		
		$verify = random_string('alnum', 10);
		
		$insert = $this->db->insert('accounts', array(
			'a_email' => trim($data['email']),
			'a_type' => 'user',
			'a_created' => date('Y-m-d H:i:s'),
			'a_enabled' => 'N',
			'a_verify' => $verify
		));
		
		if ($insert == FALSE)
		{
			return FALSE;
		}
		
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