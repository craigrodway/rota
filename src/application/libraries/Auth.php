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

require_once(APPPATH . '/third_party/phpass.php');

class Auth
{

	public $lasterr;
	
	private $_CI;
	
	private $_phpass = NULL;
	private $_phpass_iteration_count = 8;
	private $_phpass_portable = FALSE;
	
	private $_hash_algorithm = 'sha256';
	
	
	
	function __construct()
	{
		$this->_CI =& get_instance();
		
		if ( ! $this->logged_in())
		{
			// Not already logged in, attempt auto-login
			$this->_auto_login();
		}
	}
	
	
	public function logged_in()
	{
		return ($this->_CI->session->userdata('a_id'));
	}
	
	
	
	
	/**
	 * Check for authentication and level. Either redirect or return true/false.
	 *
	 * @param string $type User type to check for
	 * @param bool $return Only return the result of the check
	 * @return bool
	 */
	public function check($type = NULL, $return = FALSE)
	{
		// Default value
		$auth_ok = FALSE;
		
		if ($type == NULL)
		{
			// No type to check - only make sure user is logged in
			if ($this->logged_in())
			{
				$auth_ok = TRUE;
			}
		}
		else
		{
			$ok_types = explode(',', $type);
			if (in_array($this->_CI->session->userdata('a_type'), $ok_types))
			{
				$auth_ok = TRUE;
			}
		}
		
		if ($return === TRUE)
		{
			return $auth_ok;
		}
		else
		{
			if ($auth_ok === FALSE)
			{
				show_error('You are not authorised to access that page.');
			}
		}
	}
	
	
	
	
	/**
	 * Attempt to create a login session by authenticating email+pwd in the DB
	 */
	public function login($email = NULL, $password = NULL)
	{
		if ( ! $email)
		{
			$this->lasterr = 'Empty email address.';
			return FALSE;
		}
		
		if ( ! $password)
		{
			$this->lasterr = 'Empty password.';
			return FALSE;
		}
		
		$email = trim($email);
		
		$sql = "SELECT a_id, a_password 
				FROM accounts
				WHERE a_email = ?
				AND a_enabled = 1
				AND a_verify IS NULL
				LIMIT 1";
				
		$query = $this->_CI->db->query($sql, array($email));
		
		if ($query->num_rows() == 1)
		{
			$account = $query->row_array();
			$match = $this->check_password($password, $account['a_password']);
			if ($match === TRUE)
			{
				// If passwords match, create session
				if ($this->_create_session($account['a_id']))
				{
					// Created session - now do auto-login if enabled
					// Set auto-login if being used
					if (config_item('auto_login_enable'))
					{
						$this->_set_auto_login($account['a_id']);
					}
					
					return TRUE;
				}
				else
				{
					// Could not create session
					return FALSE;
				}
			}
			else
			{
				// Bad password
				$this->lasterr = 'Incorrect email address and/or password.';
				return FALSE;
			}
		}
		else
		{
			// No account found (num_rows != 1)
			$this->lasterr = 'Incorrect email address and/or password.';
			return FALSE;
		}
	}
	
	
	
	
	/**
	 * Log the logged-in user out, destroying the session
	 */
	public function logout()
	{
		$a_id = $this->_CI->session->userdata('a_id');
		
		// Set session data to NULL (include all fields!)
		$sessdata['a_id'] = null;
		$sessdata['a_email'] = null;
		$sessdata['a_type'] = null;
		
		// Set empty session data
		$this->_CI->session->set_userdata($sessdata);
		$this->_CI->session->unset_userdata($sessdata);
		
		// Destroy session
		$this->_CI->session->sess_destroy();
		
		// Remove the auto-login cookie
		$this->_delete_auto_login();
		
		// Verify session has been destroyed by retrieving info that should have gone 
		return ( ! $this->_CI->session->userdata('a_id'));
	}
	
	
	
	
	/**
	 * Handle the creation of the browser session for the supplied user
	 */
	private function _create_session($a_id)
	{
		$this->_CI->load->helper('date');
		
		$sql = "SELECT * FROM accounts
				WHERE a_id = ? 
				AND a_enabled = 1
				AND a_verify IS NULL
				LIMIT 1";
		
		$query = $this->_CI->db->query($sql, array($a_id));
		
		if ($query->num_rows() == 1)
		{
			// Get account.
			$account = $query->row_array();
			
			// Update last login timestamp
			$sql = 'UPDATE accounts SET a_lastlogin = NOW() WHERE a_id = ? LIMIT 1';
			$this->_CI->db->query($sql, array($account['a_id']));
			
			// Gather info for session
			$sessdata['a_id'] = $account['a_id'];
			$sessdata['a_email'] = $account['a_email'];
			$sessdata['a_type'] = $account['a_type'];
			
			// Set session data
			$this->_CI->session->set_userdata($sessdata);
			
			return TRUE;
		}
		else
		{
			// Couldn't find account!
			$this->lasterr = "Could not create browser session for $a_id.";
			return FALSE;
		}
	}
	
	
	
	
	/**
	 * Publicly-accessible function for hashing a supplied plaintext password
	 */
	public function hash_password($password)
	{
		$this->_init_phpass();
		return $this->_phpass->HashPassword($password);
	}
	
	
	
	
	/**
	 * Check if a given plaintext password matches the (expected) hash
	 */
	public function check_password($password, $stored_hash)
	{
		$this->_init_phpass();
		return $this->_phpass->CheckPassword($password, $stored_hash);
	}
	
	
	
	private function _init_phpass()
	{
		if ($this->_phpass === NULL)
		{
			$this->_phpass = new PasswordHash($this->_phpass_iteration_count, $this->_phpass_portable);
		}
	}
	
	
	
	
	private function _set_auto_login($a_id, $series = NULL)
	{
		$this->_CI->load->model('autologin_model');
		
		// Generate keys
		list($public, $private) = $this->_generate_keys();
		
		// create new series or update current series
		if ( ! $series)
		{
			list($series) = $this->_generate_keys();
			$this->_CI->autologin_model->insert(array(
				'al_a_id' => $a_id,
				'al_series' => $series,
				'al_key' => $private,
			));
		}
		else
		{
			$this->_CI->autologin_model->update($a_id, $series, $private);
		}
		
		// Create and set the cookie
		$this->_set_cookie(array(
			'al_a_id' => $a_id,
			'al_series' => $series,
			'al_key' => $public,
		));
	}
	
	
	
	private function _auto_login()
	{
		if ($cookie = $this->_read_cookie())
		{
			log_message('debug', 'Auth: _auto_login(): Got cookie.');
			
			// remove expired keys
			$this->_CI->load->model('autologin_model');
			$this->_CI->autologin_model->purge();
			
			// Get private key
			$private = $this->_CI->autologin_model->get_private_key($cookie['al_a_id'], $cookie['al_series']);
			
			if ($this->_validate_keys($cookie['al_key'], $private))
			{
				log_message('debug', 'Auth: _auto_login(): Keys have been validated. Creating session.');
				
				// Create logged in session for user
				$this->_create_session($cookie['al_a_id']);
				// Extend the autologin cookie
				$this->_set_auto_login($cookie['al_a_id'], $cookie['al_series']);
				
				return TRUE;
			}
			else
			{
				log_message('debug', 'Auth: _auto_login(): Keys were invalid.');
				
				// the key was not valid, strange stuff going on
				// remove the active session to prevent theft!
				$this->_delete_auto_login();
			}
		}
		
		return FALSE;
	}
	
	
	
	/**
	 * Disable the current autologin key and remove the cookie
	 */
	private function _delete_auto_login()
	{
		if ($cookie = $this->_read_cookie())
		{
			// Remove current series
			$this->_CI->load->model('autologin_model');
			$this->_CI->autologin_model->delete($cookie['al_a_id'], $cookie['al_series']);
			// Clear the cookie
			$this->_CI->input->set_cookie(array(
				'name' => config_item('auto_login_cookie'),
				'value' => '',
				'expire' => '',
			));
		}
	}
	
	
	
	
	/**
	 * Generate public/private key pair
	 * 
	 * @return array
	 */
	private function _generate_keys()
	{
		$public = hash($this->_hash_algorithm, uniqid(rand()));
		$private = hash_hmac($this->_hash_algorithm, $public, config_item('encryption_key'));
		return array($public, $private);
	}
	
	
	
	
	/**
	 * Validate public/private key pair
	 * 
	 * @param string $public
	 * @param string $private
	 * @return boolean
	 */
	private function _validate_keys($public, $private)
	{
		$check = hash_hmac($this->_hash_algorithm, $public, config_item('encryption_key'));
		return $check === $private;
	}
	
	
	
	
	/**
	 * Write auto-login cookie
	 * 
	 * @param array $data		Data including the account ID, series and public key
	 * @return bool
	 */
	private function _set_cookie($data = array())
	{
		$this->_CI->load->library('encrypt');
		
		$data = array(
			'name' => config_item('auto_login_cookie'),
			'value' => $this->_CI->encrypt->encode(json_encode($data)),
			'expire' => config_item('auto_login_expire'),
		);
		
		log_message('debug', 'Auth: _set_cookie(): Setting auto-login cookie ' . $data['name']);
		
		return $this->_CI->input->set_cookie($data);
	}
	
	
	
	
	/**
	 * Read auto-login cookie
	 *
	 * @return array
	 */
	public function _read_cookie()
	{
		$cookie = $this->_CI->input->cookie(config_item('auto_login_cookie'), TRUE);

		if ( ! $cookie)
		{
			log_message('debug', 'Auth: _read_cookie(): No auto-login cookie ' . config_item('auto_login_cookie') . ' present.');
			return FALSE;
		}
		
		// Decrypt cookie data
		$this->_CI->load->library('encrypt');
		$data = (array) json_decode($this->_CI->encrypt->decode($cookie));
		
		if (isset($data['al_a_id']) && isset($data['al_series']) && isset($data['al_key']))
		{
			log_message('debug', 'Auth: _read_cookie(): All data from cookie is present.');
			return $data;
		}
		
		log_message('debug', 'Auth: _read_cookie(): Failed to read cookie data or not all values present. Data: ' . var_export($data));
		return FALSE;
	}
	
	
	
	
}

/* End of file: application/libraries/Auth.php */