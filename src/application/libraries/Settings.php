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

class Settings
{


	private $CI;
	private $_settings = array();
	
	
	function Settings()
	{
		// Load original CI object
		$this->CI =& get_instance();
		// Get all settings and store in local array
		$this->_init();
	}
	
	
	
	
	/**
	 * Pull all settings from DB and store in local array
	 */
	private function _init()
	{
		$this->CI->db->select('*', FALSE);
		$this->CI->db->from('settings');
		$query = $this->CI->db->get();
		if ($query->num_rows() == 0)
		{
			return false;
		}
		
		$result = $query->result();
		foreach($result as $r)
		{
			$this->settings[$r->name] = $r->value;
			$this->CI->config->set_item($r->name, $r->value);
		}
		
		if (is_array($this->settings))
		{
			log_message('debug', 'Settings have been initialised');
		}
	}
	
	
	
	
	/**
	 * Get one or mroe setting values
	 *
	 * @param empty|string|array $name One setting name or 1D array of names. Empty = all settings
	 * @return string|array String value, or 2D array of names => values
	 */
	public function get($name = null)
	{
		if ($name === null)
		{
			return $this->settings;
		}
		elseif (is_array($name))
		{
			$ret = array();
			foreach($name as $n)
			{
				$ret[$n] = $this->_get_one($n);
			}
			return $ret;
		}
		elseif (is_string($name))
		{
			return $this->_get_one($name);
		}
	}
	
	
	
	private function _get_one($name = null)
	{
		if (!$name) return false;
		return (array_key_exists($name, $this->settings))
			? $this->settings[$name]
			: false;
	}
	
	
	
	
	/**
	 * Save one or more settings
	 *
	 * @param string|array $name One name, or 2D array of names => values
	 * @param string $value Value of $name if saving only one item
	 * @return bool
	 */
	function save($name = NULL, $value = NULL)
	{
		// Check first parameter type
		if (is_array($name))
		{
			// $name is actually an array of settings
			$sql = 'REPLACE INTO settings (name, value) VALUES ';
			foreach($name as $n => $v){
				$sql .= sprintf("('%s', '%s'),", $n, $v);
			}
			$sql = preg_replace('/,$/', '', $sql);
			$query = $this->CI->db->query($sql);
			return ($query) ? true : false;
		}
		else
		{
			// One name, one value.
			if($name != NULL && $name != NULL)
			{
				$sql = 'REPLACE INTO settings (name, value) VALUES (?, ?)';
				$query = $this->CI->db->query($sql, array($name, $value));
				return ($query) ? true : false;
			}
			else
			{
				return false;
			}
		}	// else is_array($name)
	}
	
	
	
	
}
?>
