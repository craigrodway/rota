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

class Auth
{


	private $CI;
	public $lasterr;
	
	
	
	function __construct()
	{
		$this->CI =& get_instance();
	}
	
	
	public function logged_in()
	{
		return ($this->CI->session->userdata('account_id'));
	}
	
	
	
	
	/**
	 * Check for authentication and level. Either redirect or return true/false.
	 *
	 * @param string $type User type to check for
	 * @param bool $return Only return the result of the check
	 * @return bool
	 */
	public function check($type = null, $return = false)
	{
		// Default value
		$auth_ok = false;
		
		if ($type == null)
		{
			// No type to check - only make sure user is logged in
			if ($this->logged_in())
			{
				$auth_ok = true;
			}
		}
		else
		{
			$ok_types = explode(',', $type);
			if (in_array($this->CI->session->userdata('type'), $ok_types))
			{
				$auth_ok = true;
			}
		}
		
		if ($return == true)
		{
			return $auth_ok;
		}
		else
		{
			if ($auth_ok == false)
			{
				show_error('You are not authorised to access that page.');
			}
		}
	}
	
	
	
	
	/**
	 * Attempt to create a login session by authenticating email+pwd in the DB
	 */
	public function login($email = null, $password = null)
	{
		if (!$email){
			$this->lasterr = 'Empty email address.';
			return false;
		}
		if (!$password){
			$this->lasterr = 'Empty password.';
			return false;
		}
		
		$email = trim($email);
		
		$sql = "SELECT a_password 
				FROM accounts
				WHERE a_email = ?
				AND a_enabled = 'Y'
				AND a_verify IS NULL
				LIMIT 1";
				
		$query = $this->CI->db->query($sql, array($email));
		
		if ($query->num_rows() == 1)
		{
			$account = $query->row();
			$match = $this->check_password($password, $account->a_password);
			if ($match == true)
			{
				// If passwords match, create session
				return $this->_create_session($email);
			}
			else
			{
				// Bad password
				$this->lasterr = 'Incorrect email address and/or password.';
				return false;
			}
		}
		else
		{
			// No account found (num_rows != 1)
			$this->lasterr = 'Incorrect email address and/or password.';
			return false;
		}
	}
	
	
	
	
	/**
	 * Log the logged-in user out, destroying the session
	 */
	public function logout()
	{
		$account_id = $this->CI->session->userdata('account_id');
		
		// Set session data to NULL (include all fields!)
		$sessdata['account_id'] = null;
		$sessdata['email'] = null;
		$sessdata['type'] = null;
		
		// Set empty session data
		$this->CI->session->set_userdata($sessdata);
		$this->CI->session->unset_userdata($sessdata);
		
		// Destroy session
		$this->CI->session->sess_destroy();
		
		// Verify session has been destroyed by retrieving info that should have gone 
		return (!$this->CI->session->userdata('account_id'));
	}
	
	
	
	
	/**
	 * Handle the creation of the user session
	 */
	private function _create_session($email = null)
	{
		$this->CI->load->helper('date');
		
		if (!$email)
		{
			$this->lasterr = 'No email address supplied.';
			return false;
		}
		
		$email = trim($email);
		
		$sql = "SELECT * FROM accounts
				WHERE a_email = ? 
				AND a_enabled = 'Y'
				AND a_verify IS NULL
				LIMIT 1";
		
		$query = $this->CI->db->query($sql, array($email));
		
		if ($query->num_rows() == 1)
		{
			// Get account.
			$account = $query->row();
			
			// Update last login timestamp
			$timestamp = mdate('%Y-%m-%d %H:%i:%s');
			$sql = 'UPDATE accounts SET a_lastlogin = ?
					WHERE a_id = ? LIMIT 1';
			$this->CI->db->query($sql, array($timestamp, $account->a_id));
			
			// Gather info for session
			$sessdata['account_id'] = $account->a_id;
			$sessdata['email'] = $account->a_email;
			$sessdata['type'] = $account->a_type;
			
			// Set session data
			$this->CI->session->set_userdata($sessdata);
			
			return true;
		}
		else
		{
			// Couldn't find account!
			$this->lasterr = "Could not find account $email.";
			return false;
		}
	}
	
	
	
	/**
	 * Publicly-accessible function for hashing a supplied plaintext password
	 *
	 * Generates a random salt for the password and hashes it using internal
	 * hashing function. 
	 */
	public function hash_password($password = null)
	{
		if (!$password) return false;
		$this->CI->load->helper('string');
		$salt = random_string('alnum', 10);
		return $this->_hash_with_salt($password, $salt);
	}
	
	
	
	
	/**
	 * Private function that will hash a password along with the supplied salt
	 * as well as the global site salt.
	 *
	 * Returned password is a string in the format of:
	 *     rota#<salt>#sha1
	 */
	private function _hash_with_salt($password = null, $salt = null)
	{
		if (!$password)
		{
			$this->lasterr = 'No password.';
			return false;
		}
		if (!$salt)
		{
			$this->lasterr = 'No salt.';
			return false;
		}
		
		$global_salt = $this->CI->config->item('encryption_key');
		
		$sha1 = sha1($salt . $password . $global_salt);
		for ($i = 0; $i < 1000; $i++)
		{
			$sha1 = sha1($sha1 . (($i % 2 == 0) ? $password : $salt));
		}
		return 'rota#' . $salt . '#' . $sha1;
	}
	
	
	
	
	/**
	 * Check if a given plaintext password matches the (expected) hash
	 */
	public function check_password($password, $hashed)
	{
		$parts = explode('#', $hashed);
		$salt = $parts[1];
		return ($this->_hash_with_salt($password, $salt) == $hashed);
	}
	
	
	
	
}

/* End of file: application/librarys/Auth.php */