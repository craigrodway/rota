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
 * ROTA Layout class
 *
 * Handles page layout
 *
 * @author		Craig A Rodway
 */
class Layout
{


	private $CI;
	private $title;
	private $js;
	private $views; 
	private $content;
	private $template;
	public $lasterr;
	
	
	function __construct()
	{
		$this->CI =& get_instance();
		// Page template view file
		$this->template = 'template/layout';
		$this->js = array();
		$this->views = array();
		$this->content = array();
	}
	
	
	
	
	/**
	 * Set the current page title
	 */
	public function set_title($text = '')
	{
		if ($text != '')
		{
			$this->title = $text;
		}
	}
	
	
	
	
	/**
	 * Get the full or page title
	 */
	public function get_title($type = 'page')
	{
		if ($type == 'full')
		{
			return $this->title . ' - ' . $this->CI->config->item('site_name');
		}
		elseif ($type == 'page')
		{
			return $this->title;
		}
	}
	
	
	
	
	/**
	 * Add a javascript file for this page
	 */
	public function add_js($file = '')
	{
		$this->js[] = $file;
		return true;
	}
	
	
	
	
	public function get_js()
	{
		return $this->js;
	}
	
	
	
	
	/**
	 * Set a view file for provided section
	 */
	public function set_view($section, $view)
	{
		$this->views[$section] = $view;
	}
	
	
	
	
	/**
	 * Explicitly set the content for provided section
	 */
	public function set_content($section, $content)
	{
		$this->content[$section] = $content;
	}
	
	
	
	
	/**
	 * Get the content for a given section. Content overrides views.
	 */
	public function get($section)
	{
		if ( ! empty($this->content[$section]))
		{
			// Have actual content - return this
			$content = $this->content[$section];
		}
		elseif ( ! empty($this->views[$section]))
		{
			// View has been set - load the view file into variable
			$content = $this->CI->load->view($this->views[$section], NULL, TRUE);
		}
		else
		{
			// Nothing at all!
			$content = 'No content to show';
		}
		return $content;
	}
	
	
	
	
	/**
	 * Load the actual page with all provided data
	 */
	public function page($data = array())
	{
		// Configure page header
		$header['type'] = 'normal';
		$header['nav_main'] = $this->CI->menu_model->nav_main();
		
		// Add shack menu if logged in
		if ($this->CI->auth->logged_in())
		{
			$header['nav_shack'] = $this->CI->menu_model->nav_shack();
		}
		
		// Admin user?
		if ($this->CI->auth->logged_in() && $this->CI->session->userdata('type') == 'admin')
		{
			$header['nav_admin'] = $this->CI->menu_model->admin();
			$header['type'] = 'admin';
		}
		
		// Add header data to main data array
		$data['header'] = $header;
		
		// Load variables if set
		if ( ! empty($data))
		{
			$this->CI->load->vars($data);
		}
		
		// Load the whole page layout
		$this->CI->load->view($this->template);
	}
	
	
	
	
	/**
	 * Returns if the layout section has been set and/or has content
	 */
	public function has($section)
	{
		$_content = ! empty($this->content[$section]);
		$_view = ! empty($this->views[$section]);
		return ( $_content OR $_view );
	}
	
	
	
}

/* End of file: ./application/libraries/Layout.php */