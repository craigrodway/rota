<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH . '/presenters/Railway_presenter.php');

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
		$this->data['nav_active'] = 'railways/map';
		$this->data['subnav'] = $this->menu_model->subnav_railways();
	}
	
	
	
	
	/**
	 * Railways
	 */
	function index()
	{
		$this->map();
	}
	
	
	
	
	function grid()
	{
		$this->data['railways'] = presenters('Railway', $this->railways_model->get_all(NULL, NULL));
		$this->layout->set_title('Railways list');
	}
	
	
	
	
	function map()
	{
		$this->auto_view = FALSE;
		$this->layout->set_view('content_full', 'railways/map');
		$this->layout->set_css('../vendor/leaflet/leaflet');
		$this->layout->set_js(array('../vendor/leaflet/leaflet', '../vendor/leaflet/bing'));
		$this->layout->set_title('Railways map');
	}
	
	
	
	
	public function info($slug)
	{
		// Attempt to find railway by the supplied URL slug
		$railway = $this->railways_model->get_by_slug($slug);
		
		if ($railway)
		{
			// Found it. Load it.
			$r = new Railway_presenter($railway);
			$this->layout->set_title($r->r_name());
			$this->data['railway'] = $r;
		}
		else
		{
			// Not found. Load 'search' suggestion page
			$this->layout->set_title('Railways');
			$this->data['search'] = presenters('Railway', $this->railways_model->get_all(NULL, NULL, array('slug' => $slug)));
		}
		
		// Set view file manually due to remapping
		$this->layout->set_view('content', 'railways/info');
		
		if ($r->latlng())
		{
			// Load map in full-width view if location present
			$this->layout->set_view('content_full', 'railways/info/map');
			$this->layout->set_view('links', 'railways/info/map_toggle');
		}
		
		$this->layout->set_css('../vendor/leaflet/leaflet');
		$this->layout->set_js(array('../vendor/leaflet/leaflet', '../vendor/leaflet/bing', 'rota.gallery'));
	}
	
	
	
	/**
	 * Remap the CI request, running the requested method and (auto-)loading the view
	 */
	public function _remap($method, $arguments)
	{
		if (in_array($method, array('index', 'list', 'map')))
		{
			// Requested method exists in the class - run it
			if ($method == 'list') $method = 'grid';
			call_user_func_array(array($this, $method), array_slice($this->uri->rsegments, 2));
		}
		else
		{
			$this->info($method);
		}
		
		// The class function has ran, done its stuff and set $this->data vars...
		// ... now auto-load the view.
		$this->_load_view();
	}
	
	
	
	
}

/* End of file: ./application/controllers/railways.php */