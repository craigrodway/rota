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
		
		$config = array(
			'field' => 'slug',
			'title' => 'name',
			'table' => 'railways',
			'id' => 'railway_id',
		);
		$this->load->library('slug', $config);
	}
	
	
	
	
	function setslugs()
	{
		$all_railways = $this->railways_model->get_all(NULL, NULL);
		
		foreach ($all_railways as $r)
		{
			$data = array(
				'name' => $r->name,
			);
			$data['slug'] = $this->slug->create_uri($data, $r->railway_id);
			$this->db->where('railway_id', $r->railway_id);
			$this->db->update('railways', $data);
		}
	}
	
	
	
	
	/**
	 * Railways listing
	 */
	function index($pager = 0)
	{
		$filter_params = $this->input->get(NULL, TRUE);
		
		$this->load->library('pagination', 'google_maps');
		
		$config['base_url'] = site_url('admin/railways/index/');
		$config['suffix'] = '?' . @http_build_query($filter_params);
		$config['total_rows'] = $this->railways_model->count_all($filter_params);
		$config['per_page'] = 15; 
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config); 
		
		$body['railways'] = $this->railways_model->get_all($pager, $config['per_page'], $filter_params);
		$body['filter_params'] = $filter_params;
		
		// Get all railways to show on map
		$all_railways = $this->railways_model->get_all(NULL, NULL);
		
		// Do map
		$this->load->library('googlemaps');
		$mapconfig['zoom'] = 'auto';
		$mapconfig['cluster'] = TRUE;
		$mapconfig['center'] = 'United Kingdom';
		$this->googlemaps->initialize($mapconfig);
		// Place all stations on the map
		foreach ($all_railways as $r)
		{
			$latlng = "{$r->lat},{$r->lng}";
			if (strlen($latlng) > 1 && ! preg_match('/0\.0/', $latlng))
			{
				$marker = array();
				$marker['position'] = $latlng;
				$marker['infowindow_content'] = addslashes(anchor('admin/railways/edit/' . $r->railway_id, $r->name));
				$this->googlemaps->add_marker($marker);
			}
		}
		
		$data['map'] = $this->googlemaps->create_map();
		
		$body['map'] = $data['map'];		
		
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
		$this->session->set_userdata('redirect_to', 'admin/railways');
		$data['js'] = array('modules/ROTA.js');
		$data['title'] = 'Add a railway';
		$data['body'] = $this->load->view('admin/railways/addedit', NULL, TRUE);
		$data['sidebar'] = null;
		$this->page($data);
	}
	
	
	
	
	/**
	 * Page to edit a railway
	 */
	function edit($railway_id = null)
	{
		if ( ! $railway_id) redirect('admin/railways');
		
		// Index page to redirect to
		$this->session->set_userdata('redirect_to', $this->input->server('HTTP_REFERER'));
		
		$data['js'] = array('modules/ROTA.js');
		$body['railway'] = $this->railways_model->get($railway_id);
		$data['body'] = $this->load->view('admin/railways/addedit', $body, TRUE);
		$data['title'] = 'Edit railway';
		$data['sidebar'] = null;
		$this->page($data);
	}
	
	
	
	
	/**
	 * Add new or update a railway
	 */
	function save()
	{
		$railway_id = $this->input->post('railway_id');
		
		$this->form_validation
			->set_rules('name', 'Railway name', 'required|trim|max_length[100]')
			->set_rules('url', 'Web address', 'trim|prep_url')
			->set_rules('info_src', 'trim')
			->set_rules('photo_url', 'Photo URL')
			->set_rules('postcode', 'Postcode', 'strtoupper|trim|max_length[8]')
			->set_rules('locator', 'Locator square', 'strtoupper|trim|max_length[8]')
			->set_rules('wab', 'WAB', 'strtoupper|trim');
		
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
			$data['lat'] = $this->input->post('lat');
			$data['lng'] = $this->input->post('lng');
			$data['slug'] = ($railway_id)
				? $this->slug->create_uri($data, $railway_id)
				: $this->slug->create_uri($data);
			
			$photo_url = $this->input->post('photo_url');
			
			if ($photo_url)
			{
				$photo = $this->railways_model->get_remote_image($photo_url);
				if ($photo == false)
				{
					$this->session->set_flashdata('warning', 
						'<strong>Problem:</strong> ' . $this->railways_model->lasterr);
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
			
			$redirect_to = $this->session->userdata('redirect_to');
			redirect($redirect_to);
			
		}
	}
	
	
	
	
	/**
	 * Delete a railway (only accepts POSTed data)
	 */
	function delete()
	{
		$id = $this->input->post('railway_id');
		if ( ! $id)
		{
			redirect('admin/railways');
		}
		
		$delete = $this->railways_model->delete($id);
		
		if ($delete == true)
		{
			$msg_type = 'success';
			$msg = 'The railway has been deleted successfully.';
		}
		else
		{
			$msg_type = 'error';
			$msg = 'Problem removing railway - ' . $this->railways_model->lasterr;
		}
		$this->session->set_flashdata($msg_type, $msg);
		
		$redirect_to = $this->input->post('redirect_to');
		$redirect_to = ($redirect_to) ? $redirect_to : 'admin/railways'; 
		redirect($redirect_to);
	}
	
	
	
}

/* End of file: application/controllers/admin/railways.php */