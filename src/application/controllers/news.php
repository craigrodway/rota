<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . '/presenters/News_presenter.php');
require_once(APPPATH . '/presenters/Railway_presenter.php');

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
		// $this->layout->set_content('sidebar', '');
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
	
	
	
	
	public function article($year, $slug = '')
	{
		$this->news_model->limit(1);
		if ( ! $news = $this->news_model->get_by('n_slug', $slug))
		{
			show_404();
		}
		
		$news = new News_presenter($news);
		
		$this->data['news'] =& $news;
		$this->layout->set_title($news->n_title());
		$this->layout->set_js('rota.gallery');
	}
	
	
	
	
	/**
	 * All articles for given year
	 */
	public function event($year)
	{
		$filter['n_e_year'] = (int) $year;
		$this->news_model->set_filter($filter);
		$this->news_model->order_by('n_datetime_posted', 'desc');
		
		$this->data['news'] = presenters('News', $this->news_model->get_all());
		
		$this->layout->set_view('content', 'news/index');
		$this->layout->set_title('News: ' . $year. ' event');
	}
	
	
	
	
	/**
	 * All articles for given railway
	 */
	public function railway($slug)
	{
		// Attempt to find railway by the supplied URL slug
		$railway = $this->railways_model->get_by_slug($slug);
		
		if ($railway)
		{
			// Found it. Load it.
			$r = new Railway_presenter($railway);
			$this->data['railway'] =& $r;
		}
		else
		{
			// Not found
			redirect('news');
		}
		
		$filter['n_r_id'] = $r->r_id();
		
		$this->news_model->set_filter($filter);
		$this->news_model->order_by('n_datetime_posted', 'desc');
		
		$this->data['news'] = presenters('News', $this->news_model->get_all());
		
		$this->layout->set_view('content', 'news/index');
		$this->layout->set_title('News with ' . $r->r_name());
	}
	
	
	
	
	/**
	 * All articles by author operator
	 */
	public function author($slug)
	{
		// Attempt to find railway by the supplied URL slug
		$this->operators_model->limit(1);
		$operator = $this->operators_model->get_by('o_slug', $slug);
		
		if ($operator)
		{
			// Found it. Load it.
			$op = new Operator_presenter($operator);
			$this->data['operator'] =& $op;
		}
		else
		{
			// Not found
			redirect('news');
		}
		
		$filter = array('n_o_id' => $op->o_id());
		
		$this->news_model->set_filter($filter);
		$this->news_model->order_by('n_datetime_posted', 'desc');
		
		$this->data['news'] = presenters('News', $this->news_model->get_all());
		
		$this->layout->set_view('content', 'news/index');
		$this->layout->set_title('News by ' . $op->o_callsign() . ' ' . $op->o_name());
	}
	
	
	
}

/* End of file: application/controllers/shack/operators.php */