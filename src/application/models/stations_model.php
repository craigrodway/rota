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
		'where' => array('s_e_year'),
		'like' => array('o_callsign', 'o_name', 'r_name'),
	);
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
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
		return $this->db->query($sql)->result_array();
	}
	
	
	
	
	public function get($s_id)
	{
		$sql = 'SELECT *
				FROM stations
				LEFT JOIN events ON s_e_year = e_year
				LEFT JOIN operators ON s_o_id = o_id
				LEFT JOIN railways ON s_r_id = r_id
				WHERE s_id = ?
				LIMIT 1';
		return $this->db->query($sql, array($s_id))->row_array();
	}
	
	
	
	
}

/* End of file: application/models/stations_model.php */