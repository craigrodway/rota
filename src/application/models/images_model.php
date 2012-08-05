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

class Images_model extends MY_Model
{
	
	
	protected $_table = 'images';
	protected $_primary = 'i_id';
	
	protected $_filter_types = array(
		'where' => array('i_id', 'i_a_id'),
	);
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	
	
	/**
	 * Get all images for a specific railway
	 */
	public function railway($r_id)
	{
		$sql = 'SELECT *
				FROM railways_images
				LEFT JOIN images ON ri_i_id = i_id
				WHERE ri_r_id = ? ' . 
				$this->filter_sql() .
				$this->order_sql() .
				$this->limit_sql();
		
		return $this->db->query($sql, array($r_id))->result_array();
	}
	
	
	
	
	/**
	 * Get all images for a specific news post
	 */
	public function news($n_id)
	{
		$sql = 'SELECT *
				FROM news_images
				LEFT JOIN images ON ni_i_id = i_id
				WHERE ni_n_id = ? ' . 
				$this->filter_sql() .
				$this->order_sql() .
				$this->limit_sql();
		
		return $this->db->query($sql, array($n_id))->result_array();
	}
	
	
	
	
}

/* End of file: application/models/images_model.php */