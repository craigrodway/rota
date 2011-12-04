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
	
	
	
	
	function get($railway_id = null)
	{
		if ( ! $railway_id) return false;
		
		$sql = 'SELECT * FROM railways WHERE railway_id = ? LIMIT 1';
		$query = $this->db->query($sql, array($railway_id));
		
		if ($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}
	
	
	
	
	function add($data = array())
	{
		if (empty($data)) return false;
		return $this->db->insert('railways', $data);
	}
	
	
	
	
	function edit($railway_id = null, $data = array())
	{
		if ( ! $railway_id) return false;
		if (empty($data)) return false;
		
		$this->db->where('railway_id', $railway_id);
		return $this->db->update('railways', $data);
	}
	
	
	
	
	function get_remote_image($url = '')
	{
		// Path to file storage
		$dir = '../../storage/';
		// Configure filename
		$orig_filename = explode(".", basename($url));
		$new_filename = "railway-" . uniqid() . "." . $orig_filename[1];
		$filepath = $dir . $new_filename; 
		$lfile = fopen($filepath, "w");
		
		// Check if we can write
		if ( ! is_really_writable($filepath)) return false;
		
		// Initialise cURL. Get remote image and save to local file
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'curl/railways-on-the-air');
		curl_setopt($ch, CURLOPT_FILE, $lfile);
		curl_exec($ch);
		
		// Validate request
		$valid_types = array('image/jpeg', 'image/png', 'image/gif');
		$status = false;
		$info = curl_getinfo($ch);
		// Not 200 OK and not an image, FAIL.
		if ($info['http_code'] == 200 && in_array($info['content_type'], $valid_types))
		{
			$status = true;
		}
		
		curl_close($ch);
		fclose($lfile);
		
		if ($status == false)
		{
			@unlink($filepath);
		}
		
		// TODO:
		//  - resize/copy as appropriate 
		
		// Return path to file
		return ($status == true) ? $filepath : false;
	}
	
	
	
	
}

/* End of file: application/models/railways_model.php */