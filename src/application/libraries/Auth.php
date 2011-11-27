<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Classroombookings. Hassle-free resource booking for schools. <http://classroombookings.com/>
 * Copyright (C) 2006-2011 Craig A Rodway <craig.rodway@gmail.com>
 *
 * This file is part of Classroombookings.
 * Classroombookings is licensed under the Affero GNU GPLv3 license.
 * Please see license-classroombookings.txt for the full license text.
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
	
	
	
	public function hash_password($password = null)
	{
		if (!$password) return false;
		$this->CI->load->helper('string');
		$salt = random_string('alnum', 10);
		return $this->hash_with_salt($password, $salt);
	}
	
	
	
	
	private function _hash_with_salt($password, $salt)
	{
		$global_salt = $this->CI->config->item('encryption_key');
		$sha1 = sha1($salt . $password . $global_salt);
		for ($i = 0; $i < 1000; $i++)
		{
			$sha1 = sha1($sha1 . (($i % 2 == 0) ? $password : $salt));
		}
		return 'rota#' . $salt . '#' . $sha1;
	}
	
	
	
	
	public function check_password($password, $hashed)
	{
		$parts = explode('#', $hashed);
		$salt = $parts[1];
		return ($this->_hash_with_salt($password, $salt) == $hashed);
	}
	
	
	
	
}

/* End of file: application/librarys/Auth.php */