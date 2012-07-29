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
 * Store images in DB and handle resizing
 *
 * @author		Craig A Rodway
 */
class Photo
{
	
	// Handle to CI object
	private $_CI;
	
	// Image dimension variations
	private $variations = array(
		'c100x100',
		'w580',
		'c220x145',
	);
	
	// Path to store all original files
	public $orig_path;
	
	// Path where process files go
	public $image_path; 
	
	// Last error message
	public $lasterr;
	
	
	public function __construct()
	{
		$this->_CI =& get_instance();
		$this->_CI->load->model('images_model');
		$this->_CI->load->library('image_lib');
		
		// Set paths
		$this->orig_path = realpath(FCPATH . '../../storage/images');
		$this->image_path = realpath(FCPATH . '/assets/uploads');
		
		log_message('debug', 'Photo library loaded.');
		log_message('debug', "Source orig_path is: {$this->orig_path}");
		log_message('debug', "Destination image_path is: {$this->image_path}");
	}
	
	
	
	
	/**
	 * Add an uploaded image to the database and process the variations
	 *
	 * @param string $file_name		File name of image to process
	 * @param array $data		Extra data for the images table (Railway/News IDs)
	 * @param bool $process		Whether to process the image for resizing or not
	 * @return mixed		New ID on success
	 */
	public function add_image($file_name = '', $extra_data = array(), $process = TRUE)
	{
		log_message('debug', 'Photo lib: add_image(): Filename: ' . $file_name);
		
		$file = $this->orig_path . '/' . $file_name;
		if ( ! file_exists($file))
		{
			log_message('debug', 'Photo lib: add_image(): File ' . $file . ' does not exist');
			$this->lasterr = 'File ' . $file . ' does not exist.';
			return FALSE;
		}
		
		// Generate unique name for this image
		$base_name = uniqid($_SERVER['REMOTE_PORT'], TRUE);
		
		log_message('debug', 'Photo lib: add_image(): Generated base name is: ' . $base_name);
		
		// Get size of image
		$size = $this->_get_size($file);
		
		log_message('debug', 'Photo lib: add_image(): Dimensions of image: ' . var_export($size, TRUE));
		
		// Array of data for the database
		$data = array(
			'i_a_id' => $this->_CI->session->userdata('a_id'),
			'i_e_year' => element('i_e_year', $extra_data, NULL),
			'i_r_id' => element('i_r_id', $extra_data, NULL),
			'i_n_id' => element('i_n_id', $extra_data, NULL),
			'i_orig_file_name' => $file_name,
			'i_orig_file_ext' => strtolower(end(explode('.', $file_name))),
			'i_orig_width' => $size['w'],
			'i_orig_height' => $size['h'],
			'i_file_base_name' => $base_name,
			'i_datetime_uploaded' => date('Y-m-d H:i:s'),
		);
		
		$i_id = $this->_CI->images_model->insert($data);
		
		if ($i_id !== FALSE)
		{
			log_message('debug', 'Photo lib: add_image(): Image added to database, ID ' . $i_id);
			// Success! Return the result of the process method or TRUE
			return ($process) ? $this->process_image($i_id) : TRUE;
		}
		else
		{
			log_message('debug', 'Photo lib: add_image(): Error adding database entry.');
			$this->lasterr = 'Unable to add image to database.';
			return FALSE;
		}
		
	}
	
	
	
	
	/**
	 * Process an original image for the variations on dimensions/resizing
	 *
	 * @param int $id		Image ID to process
	 * @param int 
	 */
	public function process_image($i_id)
	{
		log_message('debug', 'Photo lib: process_image(): Processing image ID ' . $i_id);
		
		$image = $this->_CI->images_model->get($i_id);
		if ( ! $image)
		{
			log_message('debug', 'Photo lib: process_image(): Could not find image ID ' . $i_id);
			$this->lasterr = 'Could not find image ID ' . $i_id;
			return FALSE;
		}
		
		// Got image.
		
		// Array containing return values of processing functions
		$results = array();
		
		// Error counter
		$err = 0;
		
		// Loop through the variations
		foreach ($this->variations as $var)
		{
			// Type. c = cropped; w = width
			$type = $var{0};
			
			log_message('debug', "Photo lib: process_image($i_id): Variation type: $type");
			
			// Dimensions for this variation
			$dims = substr($var, 1, strlen($var));
			if (strpos($dims, 'x') === FALSE)
			{
				$w = (int) $dims;
				
				log_message('debug', "Photo lib: process_image($i_id): Variation dimension width: $w");
			}
			else
			{
				list($w, $h) = explode('x', $dims);
				$w = (int) $w;
				$h = (int) $h;
				
				log_message('debug', "Photo lib: process_image($i_id): Width: $w, Height: $h");
			}
			
			if ($type == 'c')
			{
				// Crop and resize to given dimensions
				$results[$var] = $this->_crop_resize($image, $w, $h);
			}
			elseif ($type == 'w')
			{
				// Just resize to width $w
				$results[$var] = $this->_resize($image, $w);
			}
			
			if ( ! $results[$var])
			{
				$err++;
			}
		}
		
		log_message('debug', "Photo lib: process_image($i_id): Finished. Error count: {$err}.");
		
		if ($err > 0)
		{
			$this->lasterr = 'One or more image variations could not be completed.';
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	
	
	
	/**
	 * Just resize the image using longest side width = $w
	 */
	private function _resize($image, $w)
	{
		$i_id = $image['i_id'];
		
		log_message('debug', "Photo lib: _resize($i_id)");
		
		// Original file path and location
		$orig_file = realpath($this->orig_path . '/' . $image['i_orig_file_name']);
		
		log_message('debug', "Photo lib: _resize($i_id): File: {$orig_file}");
		
		// New destination path
		$dest_path = $this->image_path . '/' . $image['i_id'];
		@mkdir($dest_path);
		
		log_message('debug', "Photo lib: _resize($i_id): Destination path: $dest_path");
		
		// Generate new file name
		$file_name = $image['i_file_base_name'] . '_w' . $w . '.' . $image['i_orig_file_ext'];
		
		log_message('debug', "Photo lib: _resize($i_id): New file name: $file_name");
		
		// Image processing library configuration
		$config['image_library'] = 'gd2';
		$config['source_image']	= $orig_file;
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['new_image'] = $dest_path . '/' . $file_name;
		$config['master_dim'] = 'width';
		$config['width'] = $w;
		$config['height'] = $w;
		
		$this->_CI->image_lib->clear();
		$this->_CI->image_lib->initialize($config);
		
		if ( ! $this->_CI->image_lib->resize())
		{
			$err = $this->_CI->image_lib->display_errors();
			$this->_CI->image_lib->clear();
			
			log_message('debug', "Photo lib: _resize($i_id): Resize failure: " . $err);
			
			return FALSE;
		}
		
		$this->_CI->image_lib->clear();
		
		log_message('debug', "Photo lib: _resize($i_id): Finished.");
		
		return TRUE;
		
	}
	
	
	
	
	/**
	 * Crop and resize image
	 */
	private function _crop_resize($image, $w, $h)
	{
		$i_id = $image['i_id'];
		
		log_message('debug', "Photo lib: _crop_resize($i_id)");
		
		// Original file path and location
		$orig_file = realpath($this->orig_path . '/' . $image['i_orig_file_name']);
		
		log_message('debug', "Photo lib: _crop_resize($i_id): File: {$orig_file}");
		
		// New destination path
		$dest_path = $this->image_path . '/' . $image['i_id'];
		@mkdir($dest_path);
		
		log_message('debug', "Photo lib: _crop_resize($i_id): Destination path: $dest_path");
		
		// Generate new file name
		$file_name = $image['i_file_base_name'] . '_c' . $w . 'x' . $h . '.' . $image['i_orig_file_ext'];
		
		log_message('debug', "Photo lib: _resize($i_id): New file name: $file_name");
		
		// Get master dimension for size
		$master_dim = ($image['i_orig_width'] - $w < $image['i_orig_height'] - $h
			? 'width' 
			: 'height');
		
		log_message('debug', "Photo lib: _crop_resize($i_id): Master dimension: $master_dim");
		
		$perc = max( (100 * $w) / $image['i_orig_width'], (100 * $h) / $image['i_orig_height']);
		$perc = round($perc, 0);
		$w_d = round(($perc * $image['i_orig_width']) / 100, 0);
		$h_d = round(($perc * $image['i_orig_height']) / 100, 0);
		
		log_message('debug', "Photo lib: _crop_resize($i_id): Perc: $perc. w_d: $w_d. h_d: $h_d");
		
		$temp_file = $dest_path . '/tmp_' . $file_name;
		
		log_message('debug', "Photo lib: _crop_resize($i_id): Temp file: " . $temp_file);
		
		// Image processing library configuration
		$config['image_library'] = 'gd2';
		$config['source_image']	= $orig_file;
		$config['new_image'] = $temp_file;
		$config['maintain_ratio'] = TRUE;
		$config['create_thumb'] = FALSE;
		$config['master_dim'] = $master_dim;
		$config['width'] = $w_d + 1;
		$config['height'] = $w_d + 1;
		
		$this->_CI->image_lib->clear();
		$this->_CI->image_lib->initialize($config);
		
		if ( ! $this->_CI->image_lib->resize())
		{
			$err = $this->_CI->image_lib->display_errors();
			log_message('debug', "Photo lib: _crop_resize($i_id): Resize failed: $err");
			return FALSE;
		}
		
		unset($config);
		
		// Get size of newly generated file
		$size = $this->_get_size($temp_file);
		
		log_message('debug', "Photo lib: _crop_resize($i_id): Dimensions of temp file: " . var_export($size, TRUE));
		
		$config['image_library'] = 'gd2';
		$config['source_image']	= $temp_file;
		$config['new_image'] = $dest_path . '/' . $file_name;
		$config['maintain_ratio'] = FALSE;
		$config['width'] = $w;
		$config['height'] = $h;
		$config['y_axis'] = round(($size['h'] - $h) / 2);
		$config['x_axis'] = 0;
		
		log_message('debug', "Photo lib: _crop_resize($i_id): Y axis: {$config['y_axis']}");
		
		$this->_CI->image_lib->clear();
		$this->_CI->image_lib->initialize($config);
		if ( ! $this->_CI->image_lib->crop())
		{
			$err = $this->_CI->image_lib->display_errors();
			log_message('debug', "Photo lib: _crop_resize($i_id): Crop failed: $err");
			@unlink($temp_file);
			return FALSE;
		}
		
		@unlink($temp_file);
		
		return TRUE;
		
	}
	
	
	
	
	/**
	 * Get the size/dimensions of given image
	 */
	private function _get_size($file_path)
	{
		$img = getimagesize($file_path);
		return array(
			'w' => $img['0'],
			'h' => $img['1']
		);
	}
	
	
	
	
}