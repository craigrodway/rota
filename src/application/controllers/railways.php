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
		$body['tab'] = 'grid';
		$body['railways'] = $this->railways_model->get_all(NULL, NULL);
		$data['title'] = 'Railways';
		$data['body'] = $this->load->view('railways/index', $body, TRUE);
		$data['sidebar'] = NULL;
		$this->page($data);
	}
	
	
	
	
	function map()
	{
		$body['tab'] = 'map';
		$body['railways'] = $this->railways_model->get_all(NULL, NULL);
		
		// Do map
		$this->load->library('googlemaps');
		$mapconfig['zoom'] = 'auto';
		$mapconfig['cluster'] = TRUE;
		$mapconfig['center'] = 'United Kingdom';
		$this->googlemaps->initialize($mapconfig);
		// Place all stations on the map
		foreach ($body['railways'] as $r)
		{
			$latlng = "{$r->lat},{$r->lng}";
			if (strlen($latlng) > 1 && ! preg_match('/0\.0/', $latlng))
			{
				$marker = array();
				$marker['position'] = $latlng;
				$marker['icon'] = base_url('assets/img/markers/steamtrain.png');
				// TODO: Use a view for the infowindow content (add desc + photo?)
				$marker['infowindow_content'] = addslashes(anchor('railways/' . $r->slug, $r->name));
				$this->googlemaps->add_marker($marker);
			}
		}
		
		$data['map'] = $this->googlemaps->create_map();
		
		$body['map'] = $data['map'];
		
		$data['title'] = 'Railways map';
		$data['body'] = $this->load->view('railways/index', $body, TRUE);
		$data['sidebar'] = NULL;
		$this->page($data);
	}
	
	
	
	
	public function info($slug)
	{
		$this->output->enable_profiler(true);
		$body['railway'] = $this->railways_model->get_by_slug($slug);
		
		if ($body['railway'])
		{
			$data['title'] = $body['railway']->name;
		}
		else
		{
			$data['title'] = 'Railway information';
			$body['search'] = $this->railways_model->get_all(NULL, NULL, array('slug' => $slug));
		}
		
		$data['sidebar'] = NULL;
		$data['body'] = $this->load->view('railways/info', $body, TRUE);
		$this->page($data);
	}
	
	
	
	
	public function _remap($method, $params = array())
	{
		// If requested method isn't in the Magic 3, look it up as a railway slug
		if (in_array($method, array('index', 'grid', 'map')))
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