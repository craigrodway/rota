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

class MY_Controller extends CI_Controller
{
	
	protected $data;
	protected $json;
	protected $view = NULL;
	
	
	function __construct()
	{
		parent::__construct();
		
		// Configure default page items and load the layout
		$css = array('base', 'skeleton', 'layout');
		
		$js = array(
			'jquery-1.7.1.min',
			'jquery.simplemodal.1.4.2.min',
			'default',
			'//maps.google.com/maps/api/js?sensor=false'
		);
		
		$template = 'default';
		
		// set layout
		$this->layout->set_css($css)
					 ->set_js($js)
					 ->set_template($template);
		
		// Add shack menu if logged in	
		$this->data['nav']['top'] = ($this->auth->logged_in())
			? $this->menu_model->loggedin()
			: $this->menu_model->guest();
		
		// Admin user?
		$this->data['nav']['primary'] = ($this->session->userdata('a_type') == 'admin') 
			? $this->menu_model->admin()
			: $this->menu_model->primary();
		
		// sidebar
		$sidebar['news'] = array();
		$default['sidebar'] = $this->load->view('template/sidebar', $sidebar, TRUE);
		$default['body'] = '';
		
		// Merge supplied data array with local data
		$this->data = array_merge($default, $this->data);
		
		if (array_key_exists('hide_sidebar', $this->data)) $this->data['sidebar'] = NULL;
		
		// Allow the profiler to be shown using the GET var if we're in dev mode
		if (ENVIRONMENT == 'development' && $this->input->get('profiler'))
		{
			$this->output->enable_profiler(TRUE);
		}
		
		$this->data['nav_active'] = $this->uri->segment(1);
		$this->data['subnav_active'] = $this->uri->segment(1) . '/' . $this->uri->segment(2);
	}
	
	
	
	/**
	 * Remap the CI request, running the requested method and (auto-)loading the view
	 */
	public function _remap($method, $arguments)
	{
		if (method_exists($this, $method))
		{
			// Requested method exists in the class - run it
			call_user_func_array(array($this, $method), array_slice($this->uri->rsegments, 2));
		}
		else
		{
			// Doesn't exist - show 404 error.
			show_404(strtolower(get_class($this)) . '/' . $method);
		}
		
		// The class function has ran, done its stuff and set $this->data vars...
		// ... now auto-load the view.
		$this->_load_view();
	}
	
	
	
	
	/** 
	 * Auto-load the view based on path.
	 *
	 * If $view is FALSE, then don't.
	 */
	protected function _load_view()
	{
		// Back out if we've explicitly set the view to FALSE
		if ($this->view === FALSE)
		{
			return;
		}
		
		// If the JSON data is set, respond with JSON data instead of whole page
		if (is_array($this->json))
		{
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($this->json, JSON_NUMERIC_CHECK));
			return;
		}
		
		if ( ! ($this->layout->has_content('content') OR $this->layout->has_view('content')))
		{
			$view = $this->router->directory . $this->router->class . '/' . $this->router->method;
			if (file_exists(APPPATH . "views/$view.php"))
			{
				$this->layout->set_view('content', $view);
			}
			else
			{
				$this->layout->set_content('content', '<div class="alert error" style="font-size: 12px"><strong>System error</strong> - required view file not found (' . $view . ')</div>');
			}
		}
		
		// Load the variables from $this->data so they can be accessed in the layout view
		$this->load->vars($this->data);
		
		// Finally load the template as the final view (it should echo $content at least)
		$this->load->view($this->layout->get_template());
	}
	
}




class AdminController extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		// Hard auth check for admin access
		$this->auth->check('admin');
		$this->data['nav_active'] = 'admin/' . $this->uri->segment(2);
	}
	
}




class ShackController extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		// If not logged in, redirect to login/account creation with a nice message.
		// Also store URI to redirect to
		//if ( ! $this->auth->check('member', TRUE))
		if ( ! $this->auth->logged_in())
		{
			$this->session->set_flashdata('warning', 'You need to log in with an account to do that.');
			$this->session->set_userdata('uri', $this->uri->uri_string());
			redirect('account');
		}
	}
	
}