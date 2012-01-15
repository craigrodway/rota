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
 * ROTA Maps class
 *
 * Handles various map-related things.
 *
 * @author		Craig A Rodway
 */
class Maps
{


	private $CI;
	public $lasterr;
	
	
	
	function __construct()
	{
		$this->CI =& get_instance();
	}
	
	
	
	
	/**
	 * Load a Google Static Map with provided params
	 *
	 * @param array $input		2D array of parameters for the map.
	 * @return string		Either the URL ($urlonly=true) or <img> tag
	 */
	public function staticmap($input = array())
	{
		$defaults = array(
			'urlonly' => false,
			'sensor' => 'false',
			'zoom' => '14',
			'size' => '400x400',
			'maptype' => 'roadmap',
		);
		
		// Remove empty entries
		$input = array_filter($input, 'strlen');
		
		$data = array_merge($defaults, $input);
		
		$_urlonly = $data['urlonly'];
		
		unset($data['urlonly']);
		
		$marker = "color:red|{$data['coords']}";
		
		$data['markers'] = $marker;
		
		$url = 'http://maps.googleapis.com/maps/api/staticmap?';
		
		foreach ($data as $k => $v)
		{
			$qstr[] = "$k=" . urlencode($v);
		}
		
		$url .= implode('&', $qstr);
		
		if ($_urlonly == true)
		{
			return $url;
		}
		else
		{
			return '<img src="' . $url . '">';
		}
		
	}
	
	
	
	
}

/* End of file: ./application/libraries/Maps.php */