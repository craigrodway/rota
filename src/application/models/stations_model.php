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

class Stations_model extends MY_Model
{
	
	
	protected $_table = 'stations';
	protected $_primary = 's_id';
	
	protected $_filter_types = array(
		'where' => array('s_e_year', 's_o_id', 's_r_id', 'o_a_id'),
		'like' => array('o_callsign', 'o_name', 'r_name'),
	);
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	
	
	/**
	 * Get all of the station registrations
	 */
	public function get_all()
	{
		$sql = 'SELECT *
				FROM stations
				LEFT JOIN events ON s_e_year = e_year
				LEFT JOIN operators ON s_o_id = o_id
				LEFT JOIN railways ON s_r_id = r_id
				WHERE 1=1'
				. $this->filter_sql()
				. $this->order_sql()
				. $this->limit_sql();
		
		$result = $this->db->query($sql)->result_array();
		
		foreach ($result as &$row)
		{
			$row['images'] = $this->images_model->railway($row['r_id']);
		}
		
		return $result;
	}
	
	
	
	
	/**
	 * Get a single station registration by ID
	 *
	 * @param int $s_id		Station ID
	 * @return array
	 */
	public function get($s_id)
	{
		$sql = 'SELECT *
				FROM `' . $this->_table . '`
				LEFT JOIN events ON s_e_year = e_year
				LEFT JOIN operators ON s_o_id = o_id
				LEFT JOIN railways ON s_r_id = r_id
				WHERE s_id = ?
				LIMIT 1';
		
		$row = $this->db->query($sql, array($s_id))->row_array();
		$row['images'] = $this->images_model->railway($row['r_id']);
		return $row;
	}
	
	
	
	
	/**
	 * Get rows where the key matches value
	 *
	 * @param string $key		DB column name to select on
	 * @param string $value		Value the column should be to match
	 */
	public function get_by($key, $value)
	{
		$sql = 'SELECT * 
				FROM `' . $this->_table . '`
				LEFT JOIN events ON s_e_year = e_year
				LEFT JOIN operators ON s_o_id = o_id
				LEFT JOIN railways ON s_r_id = r_id 
				WHERE `' . $key .'` = ?' .
				$this->order_sql() .
				$this->limit_sql();
		
		$query = $this->db->query($sql, array($value));
		
		if ($this->_limit === 1)
		{
			$row = $query->row_array();
			$row['images'] = $this->images_model->railway($row['r_id']);
		}
		else
		{
			$result = $query->result_array();
			foreach ($result as &$row)
			{
				$row['images'] = $this->images_model->railway($row['r_id']);
			}
			return $result;
		}
	}
	
	
	
	
}

/* End of file: application/models/stations_model.php */