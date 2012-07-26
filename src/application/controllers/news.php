<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH . '/presenters/News_presenter.php');

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

class News extends MY_Controller
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('news_model');
		$this->load->helper('text_helper');
		$this->layout->set_content('sidebar', '');
	}
	
	
	
	
	/**
	 * News listing
	 */
	function index()
	{
		$this->news_model->set_filter();
		$this->news_model->order_by('n_datetime_posted', 'desc');
		
		$this->data['news'] = presenters('News', $this->news_model->get_all());
		
		$this->layout->set_title('News');
	}
	
	
	
	
	public function article($year, $slug)
	{
		echo "Looking for $slug...";
	}
	
	
	
	
}

/* End of file: application/controllers/shack/operators.php */