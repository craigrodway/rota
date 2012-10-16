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

class Accounts_model extends MY_Model
{
	
	
	protected $_table = 'accounts';
	protected $_primary = 'a_id';
	
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('string_helper');
	}
	
	
	
	
	/**
	 * Get all accounts
	 */
	function get_all()
	{
		$sql = "SELECT
					accounts.*,
					(SELECT COUNT(o_a_id) FROM operators WHERE o_a_id = a_id) AS a_operator_count
				FROM accounts" . 
				$this->filter_sql() .
				$this->order_sql() .
				$this->limit_sql();
		
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
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
					(SELECT COUNT(o_a_id) FROM operators WHERE o_a_id = a_id) AS a_operator_count
				FROM accounts
				WHERE 1 = 1
				$sql_where
				LIMIT 1";
		$query = $this->db->query($sql, array($a_id));
		
		if ($query->num_rows() == 1)
		{
			$account = $query->row_array();
			unset($account['a_password']);
			return $account;
		}
		else
		{
			return FALSE;
		}
	}
	
	
	
	
	/**
	 * Account creation function. Adds a 'created' timestamp
	 *
	 * @param array $data		Array of data of CB columns => values
	 * @return mixed		Int: auto_incremement ID; False on failure
	 */
	public function insert($data)
	{
		$data['a_created'] = date('Y-m-d H:i:s');
		return parent::insert($data);
	}
	
	
	
	
	/**
	 * Update details for account
	 *
	 * @param int $a_id		Account ID to update
	 * @param array $data		Data to update account with (keys should match column names)
	 * @param string $where		Any additional WHERE clauses to use
	 * @return bool		True on successful update
	 */
	public function update($id, $data, $where = '')
	{
		// Ensure these are not set
		unset($data['a_created']);
		unset($data['a_lastlogin']);
		return parent::update($id, $data, $where);
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
		if ( ! isset($data['email']))
		{
			return FALSE;
		}
		
		$verify = random_string('alnum', 10);
		
		$insert = $this->insert(array(
			'a_email' => trim($data['email']),
			'a_type' => 'member',
			'a_created' => date('Y-m-d H:i:s'),
			'a_enabled' => 0,
			'a_verify' => $verify
		));
		
		if ($insert === FALSE)
		{
			return FALSE;
		}
		
		if ($send_email === FALSE)
		{
			return $verify;
		}
		else
		{
			$this->load->library('parser');
			$this->load->library('email');
			$parsedata['verifyurl'] = site_url('account/verify/' . $verify);
			$mailbody = $this->parser->parse('emails/create-account', $parsedata, TRUE);
			
			$this->email->from(config_item('email_from_addr'), config_item('email_from_name'));
			$this->email->to(trim($data['email']));
			$this->email->subject('Railways on the Air account verification');
			$this->email->message($mailbody);
			return $this->email->send();
		}
		
	}
	
	
	
	
	/**
	 * With the supplied verification code, set the account status 
	 * to verified and enabled.
	 *
	 * @param string $code		The verification code of the account to enable
	 * @return bool
	 */
	function verify($code)
	{		
		$sql = "UPDATE
					accounts
				SET
					a_verify = NULL,
					a_enabled = 1
				WHERE
					a_verify = ?
				LIMIT 1";
		
		$this->db->query($sql, array($code));
		
		return ($this->db->affected_rows() == 1);
	}
	
	
	
	
	/**
	 * Set an account's password to a new value
	 *
	 * @param int $account_id		ID of account to update
	 * @param string $password		Plain-text password to set (it will be hashed)
	 */
	function set_password($account_id, $password)
	{
		$hashed = $this->auth->hash_password($password);
		
		$sql = 'UPDATE accounts
				SET a_password = ?
				WHERE a_id = ?
				LIMIT 1';
		
		$query = $this->db->query($sql, array($hashed, $account_id));
		
		return ($this->db->affected_rows() == 1);
	}
	
	
}

/* End of file: application/models/accounts_model.php */