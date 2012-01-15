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

class Railways extends MY_Controller
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Googlemaps');
	}
	
	
	
	
	/**
	 * Railways
	 */
	function index()
	{
		return $this->grid();
	}
	
	
	
	
	function grid()
	{
		
		$data['tab'] = 'grid';
		$data['railways'] = $this->railways_model->get_all(NULL, NULL);
		$this->layout->set_title('Railways');
		$this->layout->set_view('content', 'railways/index');
		$this->layout->page($data);
	}
	
	
	
	
	function map()
	{
		$data['tab'] = 'map';
		$data['railways'] = $this->railways_model->get_all(NULL, NULL);
		
		// Do map
		$this->load->library('googlemaps');
		$mapconfig['zoom'] = 'auto';
		$mapconfig['cluster'] = TRUE;
		$mapconfig['center'] = 'United Kingdom';
		$this->googlemaps->initialize($mapconfig);
		
		// Place all stations on the map
		foreach ($data['railways'] as $r)
		{
			$latlng = "{$r->r_lat},{$r->r_lng}";
			if (strlen($latlng) > 1 && ! preg_match('/0\.0/', $latlng))
			{
				$marker = array();
				$marker['title'] = $r->r_name;
				$marker['position'] = $latlng;
				$marker['icon'] = base_url('assets/img/markers/steamtrain.png');
				// TODO: Use a view for the infowindow content (add desc + photo?)
				$marker['infowindow_content'] = addslashes(anchor('railways/' . $r->r_slug, $r->r_name));
				$this->googlemaps->add_marker($marker);
			}
		}
		
		$data['map'] = $this->googlemaps->create_map();
		
		$this->layout->set_title('Railways map');
		$this->layout->set_view('content', 'railways/index');
		$this->layout->page($data);
	}
	
	
	
	
	public function info($slug)
	{
		$data['railway'] = $this->railways_model->get_by_slug($slug);
		
		if ($data['railway'])
		{
			$this->layout->set_title($data['railway']->name);
			
			$mapconfig['center'] = $data['railway']->latlng;
			$mapconfig['zoom'] = '9';
			$this->googlemaps->initialize($mapconfig);
			
			$marker = array();
			$marker['title'] = $data['railway']->name;
			$marker['position'] = $data['railway']->latlng;
			$marker['icon'] = base_url('assets/img/markers/steamtrain.png');
			$this->googlemaps->add_marker($marker);
			
			$data['map'] = $this->googlemaps->create_map();
		}
		else
		{
			$this->layout->set_title('Railway information');
			$data['search'] = $this->railways_model->get_all(NULL, NULL, array('slug' => $slug));
		}
		
		$this->layout->set_view('content', 'railways/info');
		$this->layout->page($data);
	}
	
	
	
	
	public function _remap($method, $params = array())
	{
		// If requested method isn't any of these, look it up as a railway slug
		if (in_array($method, array('index', 'edit', 'grid', 'map')))
		{
			$this->$method();
		}
		else
		{
			$this->info($method);
		}
	}
	
	
	
	
}

/* End of file: ./application/controllers/railways.php */