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

class Operators_model extends MY_Model
{
	
	
	protected $_table = 'operators';
	protected $_primary = 'o_id';
	
	protected $_filter_types = array(
		'like' => array('o_name', 'o_callsign', 'a_email'),
		'where' => array('o_type', 'o_a_id'),
	);
	
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('string_helper');
	}
	
	
	
	
	/**
	 * Get all rows from the table
	 *
	 * @return array 		DB result array
	 */
	public function get_all()
	{
		$sql = 'SELECT operators.*, accounts.*
				FROM operators
				LEFT JOIN accounts ON o_a_id = a_id
				WHERE 1 = 1 ' .
				$this->filter_sql() .
				$this->order_sql() .
				$this->limit_sql();
		
		return $this->db->query($sql)->result_array();
	}
	
	
	
	
	/**
	 * Custom query for dropdowns to get the name and the callsign
	 */
	public function dropdown_query()
	{
		$sql = 'SELECT
					o_id,
					CONCAT(o_callsign, " (", o_name, ")") AS o_callsign_o_name
				FROM
					operators
				ORDER BY
					o_callsign ASC';
		return $sql;
	}
	
	
	
	
}

/* End of file: application/models/operators_model.php */