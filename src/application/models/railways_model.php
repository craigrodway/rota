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
	
	
	
	
	/**
	 * Get list of all railways as objects
	 */
	function get_all($page = 0, $limit = 10, $filter_params = array())
	{
		if ( ! empty($filter_params))
		{
			$this->db->like(array_filter($filter_params, 'strlen'));
		}
		$this->db->order_by('r_name', 'asc');
		
		// Only limit results if specified (explicitly supply NULL to get ALL railways)
		if ($page !== NULL && $limit !== NULL)
		{
			$this->db->limit($limit, $page);
		}
		
		$query = $this->db->get('railways');
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}
	
	
	
	
	/**
	 * Count all railways
	 */
	function count_all($filter_params = array())
	{
		if ( ! empty($filter_params))
		{
			$this->db->like(array_filter($filter_params, 'strlen'));
		}
		$query = $this->db->get('railways');
		return $query->num_rows();
	}
	
	
	
	
	/**
	 * Get a single railway by its ID
	 */
	function get($railway_id = NULL)
	{
		if ( ! $railway_id) return FALSE;
		
		$sql = "SELECT
					railways.*,
					CONCAT_WS(',', r_lat, r_lng)
				FROM railways
				WHERE r_id = ?
				LIMIT 1";
		$query = $this->db->query($sql, array($railway_id));
		
		if ($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			return FALSE;
		}
	}
	
	
	
	
	/**
	 * Get a single railway using slug
	 */
	function get_by_slug($slug = '')
	{
		if ($slug == '')
		{
			return FALSE;
		}
		
		$sql = "SELECT
					railways.*,
					CONCAT_WS(',', r_lat, r_lng) AS r_latlng
				FROM railways
				WHERE r_slug = ?
				LIMIT 1";
		$query = $this->db->query($sql, array($slug));
		
		if ($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			return FALSE;
		}
	}
	
	
	
	
	/**
	 * Add a new railway using supplied data
	 *
	 * @param array $data		Array of DB columns => values to add
	 * @return int		ID of new railway
	 */
	function add($data = array())
	{
		if (empty($data)) return FALSE;
		return $this->db->insert('railways', $data);
	}
	
	
	
	
	/**
	 * Update details for railway
	 *
	 * @param int $r_id		Railway ID to update
	 * @param array $data		Data to update railway with
	 * @return bool		True on successful update
	 */
	function edit($r_id = NULL, $data = array())
	{
		if ( ! $r_id) return FALSE;
		if (empty($data)) return FALSE;
		
		$this->db->where('r_id', $r_id);
		return $this->db->update('railways', $data);
	}
	
	
	
	
	/**
	 * Actually delete a railway from the database
	 *
	 * @param int $r_id		Railway ID to delete
	 * @return bool		True if railway has been removed
	 */
	function delete($r_id = NULL)
	{
		if ( ! $r_id) return FALSE;
		
		$sql = 'DELETE FROM railways WHERE r_id = ? LIMIT 1';
		$query = $this->db->query($sql, array($r_id));
		
		return ($this->db->affected_rows() == 1);
	}
	
	
	
	
	/**
	 * Get an image from remote server and save locally
	 *
	 * @param string $url		URL of picture to retrieve
	 * @return mixed		String containing path if successful. False if error.
	 */
	function get_remote_image($url = '')
	{
		// Path to file storage
		$dir = '../../storage/';
		// Configure filename
		$orig_filename = explode(".", basename($url));
		$new_filename = strtolower("railway-" . uniqid() . "." . end($orig_filename));
		$filepath = $dir . $new_filename; 
		$lfile = fopen($filepath, "w");
		
		// Check if we can write
		if ( ! is_really_writable($filepath))
		{
			$this->lasterr = "Path ($filepath) is not really writable.";
			return FALSE;
		}
		
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
		$status = FALSE;
		$info = curl_getinfo($ch);
		
		curl_close($ch);
		fclose($lfile);
		
		// Must be 200 OK and an image content type
		if ($info['http_code'] == 200 && in_array($info['content_type'], $valid_types))
		{
			$status = TRUE;
		}
		
		if ($status == FALSE)
		{
			@unlink($filepath);
			$this->lasterr = "Error retrieving remote image - status code was {$info['http_code']}.";
			return FALSE;
		}
		
		// TODO:
		//  - resize/copy as appropriate 
		
		// Return path to file
		return $filepath;
	}
	
	
	
	
}

/* End of file: application/models/railways_model.php */