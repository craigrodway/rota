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


/**
 * ROTA Image resizing class
 *
 * Take an image and create desired sizes of it.
 * Sized photos are stored in their own folder. /photos/800/example.jpg
 *
 * @author		Craig A Rodway
 */
class Photo
{
	
	
	private $CI;
	public $lasterr;
	
	
	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	
	public function resize($data)
	{
		$this->CI->load->library('image_lib');
		
		$file = $data['file'];	// path
		$sizes = $data['sizes'];	// array of sizes
		
		$file_parts = explode('/', $file);
		$filename = end($file_parts);
		
		if ( ! file_exists($file)) return false;
		
		foreach ($sizes as $size)
		{
			$config['image_library'] = 'gd2';
			$config['source_image']	= $file;
			$config['create_thumb'] = FALSE;
			$config['maintain_ratio'] = TRUE;
			 
			if (strpos($size, 'x') === FALSE)
			{
				$config['width'] = $size;
			}
			else
			{
				list($x, $y) = explode('x', $size);
				$config['width'] = $x;
				$config['height'] = $y;
			}
			
			
			$dest_path = FCPATH . '/assets/uploads/' . $size;
			@mkdir($dest_path);
			$config['new_image'] = "$dest_path/$filename";
			
			$this->CI->image_lib->initialize($config);
			
			if ( ! $this->CI->image_lib->resize())
			{
				echo $this->CI->image_lib->display_errors();
			}
			
			$this->CI->image_lib->clear();
		}
		
		
	}
	
	
	
	
}