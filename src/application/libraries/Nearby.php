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

class Nearby
{
	
	
	private $CI;
	private $api_key;
	private $api_url;
	public $lasterr;
	
	
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->api_key = $this->CI->config->item('nearby_key');
		$this->api_url = 'http://api1.nearby.org.uk/api/convert.php';
	}
	
	
	
	
	/**
	 * Get Postcode info
	 *
	 * @param string $pc		Full UK postcode
	 * @return array		2D array of info (laglng + locator)
	 */
	public function postcode($pc = '')
	{
		// Specify API params
		$data['p'] = $pc;
		$data['in'] = 'postcode-uk';
		$data['want'] = 'iaru-wgs84,ll-wgs84';
		
		$xml = $this->_make_request($data);
		
		if ($xml == FALSE)
		{
			return FALSE;
		}
		
		// Get LatLng
		$lat = @$xml->output->ll['lat'];
		$lng = @$xml->output->ll['long'];
		$ret['latlng'] = "$lat,$lng";
		// Locator
		$ret['locator'] = (string) @$xml->output->iaru['locator'];
		
		return $ret;
	}
	
	
	
	
	/**
	 * Convert locator square into latlng format
	 *
	 * @param string $loc		Locator square
	 * @return array		2D array containing result coords in the latlng key.
	 */
	public function locator($loc)
	{
		$data['p'] = $loc;
		$data['in'] = 'iaru-wgs84';
		$data['want'] = 'll-wgs84';
		$data['need'] = 'll-wgs84';
		
		$xml = $this->_make_request($data);
		
		if ($xml == FALSE)
		{
			return FALSE;
		}
		
		// Get LatLng
		$lat = @$xml->output->ll['lat'];
		$lng = @$xml->output->ll['long'];
		$ret['latlng'] = "$lat,$lng";
		
		return $ret;
	}
	
	
	
	
	/**
	 * Make the actual HTTP request to Nearby
	 *
	 * @param array $data		Array of Nearby-named params (p, in, want, need, output)
	 * @return mixed		String of response or false if request failed
	 */
	private function _make_request($data = array())
	{
		if ( ! array_key_exists('p', $data))
		{
			$this->lasterr = 'No coordinate specified - cannot continue.';
			return FALSE;
		}
		
		// Specify output format and build up the request URL 
		$data['output'] = 'sxml';
		$url = $this->_build_url($data);
		
		// Make the HTTP request to the API
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'curl/railways-on-the-air');
		$res = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		
		// Validate request
		if ($info['http_code'] == 200)
		{
			// Attempt to parse response as XML
			try
			{
				@$xml = new SimpleXMLElement($res);
			}
			catch (Exception $e)
			{
				// Couldn't load the response as an XML string - other Nearby error?
				$this->lasterr = $res;
				return FALSE;
			}
			// Valid XML - return the XML object
			return $xml;
		}
		else
		{
			// HTTP response code wasn't 200 OK - error
			$this->lasterr = 'HTTP response code was: ' . $info['http_code'];
			return FALSE;
		}
	}
	
	
	
	
	/**
	 * Build and encode the URL. Adds the API key
	 *
	 * @param array $data		2D array of Nearby parameters
	 * @return string		String. API URL with all params urlencoded
	 */
	private function _build_url($data)
	{
		$url = $this->api_url . '?';
		$data['key'] = $this->api_key;
		foreach ($data as $k => $v)
		{
			$url .= "&$k=" . urlencode(trim($v));
		}
		return $url;
	}
	
	
	
}

/* End of file: ./application/libraries/Nearby.php */