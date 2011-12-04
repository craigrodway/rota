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

class Railways extends AdminController
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="alert-message block-message error">', '</div>');
	}
	
	
	
	
	/**
	 * Railways listing
	 */
	function index($pager = 0)
	{
		$filter_params = $this->input->get(NULL, TRUE);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/railways/index/');
		$config['suffix'] = '?' . @http_build_query($filter_params);
		$config['total_rows'] = $this->railways_model->count_all($filter_params);
		$config['per_page'] = 10; 
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config); 
		
		$body['railways'] = $this->railways_model->get_all($pager, 10, $filter_params);
		$body['filter_params'] = $filter_params;
		
		$data['body'] = $this->load->view('admin/railways/index', $body, TRUE);
		$data['title'] = 'Railways';
		$data['sidebar'] = null;
		
		$this->page($data);
	}
	
	
	
	
}

/* End of file: application/controllers/admin/railways.php */