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

class Railways_model extends MY_Model
{
	
	
	protected $_table = 'railways';
	protected $_primary = 'r_id';
	
	protected $_filter_types = array(
		'like' => array('r_name', 'r_slug', 'r_wab', 'r_postcode', 'r_locator'),
	);

	
	public $lasterr;
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	
	
	public function get_all()
	{
		$railways = parent::get_all();
		
		$this->images_model->order_by('i_datetime_uploaded', 'desc');
		
		foreach ($railways as &$railway)
		{
			$railway['images'] = $this->images_model->railway($railway['r_id']);
		}
		return $railways;
	}
	
	
	
	
	public function get($id)
	{
		$railway = parent::get($id);
		$this->images_model->order_by('i_datetime_uploaded', 'desc');
		$railway['images'] = $this->images_model->railway($railway['r_id']);
		return $railway;
	}
	
	
	
	
	/**
	 * Get a single railway using slug
	 */
	function get_by_slug($slug = '')
	{
		parent::limit(1);
		$railway = parent::get_by('r_slug', $slug);
		
		$this->images_model->order_by('i_datetime_uploaded', 'desc');
		$railway['images'] = $this->images_model->railway($railway['r_id']);
		return $railway;
	}
	
	
	
	
	/**
	 * Railways associated with stations registered by the provided account ID
	 *
	 * @param int $a_id		Account ID
	 * @return array
	 */
	function get_past($a_id)
	{
		$sql = 'SELECT DISTINCT railways.*
				FROM railways
				LEFT JOIN stations ON r_id = s_r_id
				LEFT JOIN operators ON s_o_id = o_id
				WHERE o_a_id = ? ' . 
				$this->order_sql() .
				$this->limit_sql();
		
		return $this->db->query($sql, array($a_id))->result_array();
	}
	
	
	
	
	public function add_image($r_id, $i_id)
	{
		$data = array(
			'ri_r_id' => $r_id,
			'ri_i_id' => $i_id,
		);
		
		$sql = $this->db->insert_string('railways_images', $data);
		return $this->db->query($sql);
	}
	
	
	
	
	public function remove_image($r_id, $i_id)
	{
		$sql = 'DELETE FROM railways_images WHERE ri_r_id = ? AND ri_i_id = ? LIMIT 1';
		return $this->db->query($sql, array($r_id, $i_id));
	}
	
	
	
	
}

/* End of file: application/models/railways_model.php */