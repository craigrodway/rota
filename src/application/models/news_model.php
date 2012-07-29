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

class News_model extends MY_Model
{
	
	
	protected $_table = 'news';
	protected $_primary = 'n_id';
	
	protected $_filter_types = array(
		'where' => array('n_e_year', 'n_r_id', 'n_o_id'),
		'like' => array('o_name', 'o_callsign', 'r_name', 'n_title'),
	);
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	
	
	public function get_all()
	{
		$sql = 'SELECT *
				FROM `' . $this->_table . '`
				LEFT JOIN events ON n_e_year = e_year
				LEFT JOIN operators ON n_o_id = o_id
				LEFT JOIN railways ON n_r_id = r_id
				WHERE 1 = 1 ' .
				$this->filter_sql() .
				$this->order_sql() .
				$this->limit_sql();
		
		return $this->db->query($sql)->result_array();
	}
	
	
	
	
}

/* End of file: application/models/news_model.php */