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
		//$this->form_validation->set_error_delimiters('<div class="alert-message block-message error">', '</div>');
		$this->form_validation->set_error_delimiters('<li>', '</li>');
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
		$config['per_page'] = 15; 
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config); 
		
		$body['railways'] = $this->railways_model->get_all($pager, $config['per_page'], $filter_params);
		$body['filter_params'] = $filter_params;
		
		$data['body'] = $this->load->view('admin/railways/index', $body, TRUE);
		$data['title'] = 'Railways';
		$data['sidebar'] = null;
		
		$this->page($data);
	}
	
	
	
	
	/**
	 * Add a new railway
	 */
	function add()
	{
		$data['title'] = 'Add a railway';
		$data['body'] = $this->load->view('admin/railways/addedit', NULL, TRUE);
		$data['sidebar'] = null;
		$this->page($data);
	}
	
	
	
	
	function edit($railway_id = null)
	{
		if ( ! $railway_id) redirect('admin/railways');
		
		$body['railway'] = $this->railways_model->get($railway_id);
		$data['body'] = $this->load->view('admin/railways/addedit', $body, TRUE);
		$data['title'] = 'Edit railway';
		$data['sidebar'] = null;
		$this->page($data);
	}
	
	
	
	
	function save()
	{
		$railway_id = $this->input->post('railway_id');
		
		$this->form_validation
			->set_rules('name', 'Railway name', 'required|trim|max_length[100]')
			->set_rules('url', 'Web address', 'prep_url')
			->set_rules('info_src', 'trim')
			->set_rules('photo_url', 'Photo URL')
			->set_rules('postcode', 'Postcode', 'max_length[8]')
			->set_rules('locator', 'Locator square')
			->set_rules('wab', 'WAB');
		
		if ($this->form_validation->run() == FALSE)
		{
			return ($railway_id) ? $this->edit($railway_id) : $this->add();
		}
		else
		{
			// OK!
			
			$data['name'] = $this->input->post('name');
			$data['url'] = $this->input->post('url');
			$data['info_src'] = strip_tags($this->input->post('info_src'));
			$data['info_html'] = nl2br($data['info_src']);
			$data['postcode'] = $this->input->post('postcode');
			$data['wab'] = $this->input->post('wab');
			$data['locator'] = $this->input->post('locator');
			
			$photo_url = $this->input->post('photo_url');
			
			if ($photo_url)
			{
				$photo = $this->railways_model->get_remote_image($photo_url);
				if ($photo == false)
				{
					$this->session->set_flashdata('warning', '<strong>Problem:</strong> could not retrieve photo.');
				}
			}
			
			if ($railway_id)
			{
				// Update
				$op = $this->railways_model->edit($railway_id, $data);
				$ok = "<strong>{$data['name']}</strong> has been updated successfully.";
				$err = 'An error occurred while updating the railway.';
			}
			else
			{
				// Add
				$op = $this->railways_model->add($data);
				$ok = "<strong>{$data['name']}</strong> has been added successfully.";
				$err = 'An error occurred while adding the railway';
			}
			
			$msg_type = ($op) ? 'success' : 'error';
			$msg = ($op) ? $ok : $err;
			$this->session->set_flashdata($msg_type, $msg);
			redirect('admin/railways');
			
		}
	}
	
	
	
}

/* End of file: application/controllers/admin/railways.php */