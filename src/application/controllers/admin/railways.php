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

class Railways extends AdminController
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		
		$config = array(
			'field' => 'r_slug',
			'title' => 'r_name',
			'table' => 'railways',
			'id' => 'r_id',
		);
		$this->load->library('slug', $config);
		
	}
	
	
	
	
	/**
	 * Railways listing
	 */
	function index($page = 0)
	{
		$filter = $this->input->get(NULL, TRUE);
		$this->railways_model->set_filter($filter);
		
		$this->load->library('pagination');
		
		$config['base_url'] = site_url('admin/railways/index/');
		$config['total_rows'] = $this->railways_model->count_all();
		$config['per_page'] = 15; 
		$config['uri_segment'] = 4;
		$config['use_page_numbers'] = TRUE;
		$config['suffix'] = '?' . @http_build_query($filter);
		$this->pagination->initialize($config);
		
		
		$this->railways_model->order_by('r_name', 'asc')
							 ->limit(15, $page);

		$this->data['railways'] = $this->railways_model->get_all();
		$this->data['filter'] =& $filter;
		
		foreach ($this->data['railways'] as &$railway)
		{
			$railway = new Railway_presenter($railway);
		}

		$this->layout->set_title('Railways');
		$this->layout->set_view('links', 'admin/railways/index-links');
	}
	
	
	
	
	/**
	 * Page to edit a railway
	 */
	function set($r_id = NULL)
	{
		$data = array();
		
		if ($r_id)
		{
			// Editing railway. Get it via ID.
			if ( ! $railway = $this->railways_model->get($r_id))
			{
				show_error('Could not find requested railway.', 404);
			}
			$this->data['railway'] = new Railway_presenter($railway);
			$this->layout->set_title('Edit railway');
		}
		else
		{
			// Adding new railway
			$this->data['railway'] = new Railway_presenter();
			$this->layout->set_title('Add a railway');
		}
		
		if ($this->input->post())
		{
			$this->form_validation
				->set_rules('r_name', 'Railway name', 'required|trim|max_length[100]')
				->set_rules('r_url', 'Web address', 'trim|prep_url')
				->set_rules('r_info_src', 'trim')
				->set_rules('r_photo_url', 'Photo URL')
				->set_rules('r_postcode', 'Postcode', 'strtoupper|trim|max_length[8]')
				->set_rules('r_locator', 'Locator square', 'strtoupper|trim|max_length[8]')
				->set_rules('r_wab', 'WAB', 'strtoupper|trim');
			
			if ($this->form_validation->run())
			{
				// OK!
				$data = array(
					'r_name' => $this->input->post('r_name'),
					'r_url' => $this->input->post('r_url'),
					'r_info_src' => strip_tags($this->input->post('r_info_src')),
					'r_info_html' => nl2br($data['r_info_src']),
					'r_postcode' => $this->input->post('r_postcode'),
					'r_wab' => $this->input->post('r_wab'),
					'r_locator' => $this->input->post('r_locator'),
					'r_lat' => $this->input->post('r_lat'),
					'r_lng' => $this->input->post('r_lng'),
					'r_slug' => ($r_id) ? $this->slug->create_uri($data, $r_id)	: $this->slug->create_uri($data),
				);
				
				$photo_url = $this->input->post('r_photo_url');
				
				if ($photo_url)
				{
					$photo = $this->railways_model->get_remote_image($photo_url);
					if ($photo === FALSE)
					{
						$this->session->set_flashdata('warning', 
							'<strong>Problem:</strong> ' . $this->railways_model->lasterr);
					}
					else
					{
						$sizes = array('580', '220x180');
						$this->load->library('Photo');
						$this->photo->resize(array(
							'file' => $photo,
							'sizes' => $sizes
						));
						die();
					}
				}
				
				if ($r_id)
				{
					// Update
					$op = $this->railways_model->update($r_id, $data);
					$ok = "<strong>{$data['r_name']}</strong> has been updated successfully.";
					$err = 'An error occurred while updating the railway.';
				}
				else
				{
					// Add
					$op = $this->railways_model->insert($data);
					$ok = "<strong>{$data['r_name']}</strong> has been added successfully.";
					$err = 'An error occurred while adding the railway';
				}
				
				$msg_type = ($op !== FALSE) ? 'success' : 'error';
				$msg = ($op !== FALSE) ? $ok : $err;
				$this->session->set_flashdata($msg_type, $msg);
				
				redirect('admin/railways');
				
			}		// End validation == OK
			
		}	// End input POST
		
		//$this->layout->set_js('modules/admin_railways_addedit.js');
		
	}
	
	
	
	
	/**
	 * Delete a railway (only accepts POSTed data)
	 */
	function delete()
	{
		$this->view = FALSE;
		
		$id = $this->input->post('id');
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
		
		redirect('admin/railways');
	}
	
	
	
}

/* End of file: application/controllers/admin/railways.php */