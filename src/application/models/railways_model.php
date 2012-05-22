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
	
	
	
	
	/**
	 * Get a single railway using slug
	 */
	function get_by_slug($slug = '')
	{
		parent::limit(1);
		return parent::get_by('r_slug', $slug);
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