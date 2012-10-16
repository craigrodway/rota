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

class Autologin_model extends MY_Model
{
	
	
	protected $_table = 'auto_login';
	protected $_primary = NULL;
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	
	
	public function get_private_key($al_a_id, $al_series)
	{
		$sql = 'SELECT al_key, al_ua FROM `' . $this->_table . '` WHERE al_a_id = ? AND al_series = ? LIMIT 1';
		$query = $this->db->query($sql, array($al_a_id, $al_series));
		if ($query)
		{
			$row = $query->row_array();
			
			// Check user agent matches
			if (element('al_ua', $row) == $this->input->user_agent())
			{
				return $row['al_key'];
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	
	
	
	public function insert($data = array())
	{
		$data['al_created'] = time();
		$data['al_ua'] = $this->input->user_agent();
		return parent::insert($data);
	}
	
	
	
	
	public function update($al_a_id, $al_series, $al_key)
	{
		$sql = 'UPDATE `' . $this->_table . '`
				SET al_created = ?, al_key = ?, al_ua = ?
				WHERE al_a_id = ? AND al_series = ?
				LIMIT 1';
		
		$ua = $this->input->user_agent();
		
		return $this->db->query($sql, array(time(), $al_key, $ua, $al_a_id, $al_series));
	}
	
	
	
	
	public function delete($al_a_id, $al_series)
	{
		$sql = 'DELETE FROM `' . $this->_table . '` WHERE al_a_id = ? AND al_series = ? LIMIT 1';
		return $this->db->query($sql, array($al_a_id, $al_series));
	}
	
	
	
	
	/**
	 * Remove all expired keys
	 */
	public function purge()
	{
		$sql = 'DELETE FROM `' . $this->_table . '` WHERE al_created < ?';
		return $this->db->query($sql, array(time() - config_item('auto_login_expire')));
	}
	
	
	
	
}

/* End of file: application/models/autologin_model.php */