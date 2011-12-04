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

class Railways_model extends CI_Model
{
	
	
	public $lasterr;
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	function get_all($page = 0, $limit = 10, $filter_params = array())
	{
		if ( ! empty($filter_params))
		{
			/*foreach ($filter_params as $n => $v)
			{
				if ( ! empty($v)) $this->db->like($n, "$v");
			}*/
			$this->db->like(array_filter($filter_params, 'strlen'));
		}
		$this->db->order_by('name', 'asc');
		$this->db->limit($limit, $page);
		$query = $this->db->get('railways');
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}
	
	
	
	function count_all($filter_params = array())
	{
		if ( ! empty($filter_params))
		{
			foreach ($filter_params as $n => $v)
			{
				if ( ! empty($v)) $this->db->like($n, "$v");
			}
		}
		$query = $this->db->get('railways');
		return $query->num_rows();
	}
	
	
	
	
}

/* End of file: application/models/railways_model.php */