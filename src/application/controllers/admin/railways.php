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
		$this->load->library('photo');
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
				->set_rules('r_info', 'trim')
				->set_rules('r_photo_url', 'Photo URL')
				->set_rules('r_postcode', 'Postcode', 'strtoupper|trim|max_length[8]')
				->set_rules('r_locator', 'Locator square', 'strtoupper|trim|max_length[8]')
				->set_rules('r_wab', 'WAB', 'strtoupper|trim');
			
			if ($this->form_validation->run())
			{
				// OK!
				
				// Parse the Markdown and convert to HTML
				$r_info_html = parse_markdown($this->input->post('r_info_source'));
				
				// All data for railway from form
				$data = array(
					'r_name' => $this->input->post('r_name'),
					'r_url' => $this->input->post('r_url'),
					'r_info_source' => $this->input->post('r_info_source'),
					'r_info_html' => $r_info_html,
					'r_postcode' => $this->input->post('r_postcode'),
					'r_wab' => $this->input->post('r_wab'),
					'r_locator' => $this->input->post('r_locator'),
					'r_lat' => $this->input->post('r_lat'),
					'r_lng' => $this->input->post('r_lng'),
				);
				
				// Generate or upgdate the slug
				$data['r_slug'] = ($r_id) ? $this->slug->create_uri($data, $r_id) : $this->slug->create_uri($data);
				
				if ($r_id)
				{
					// Update
					$data['r_datetime_updated'] = date('Y-m-d H:i:s');
					
					$op = $this->railways_model->update($r_id, $data);
					$ok = "<strong>{$data['r_name']}</strong> has been updated successfully.";
					$err = 'An error occurred while updating the railway.';
				}
				else
				{
					// Add
					$op = $this->railways_model->insert($data);
					$r_id = $op;
					$ok = "<strong>{$data['r_name']}</strong> has been added successfully.";
					$err = 'An error occurred while adding the railway';
				}
				
				// Do image processing
				
				// Added photo by URL?
				$photo_url = $this->input->post('r_photo_url');
				if ($photo_url)
				{
					$i_id = $this->photo->add_image($photo_url);
					if ($i_id)
					{
						$this->railways_model->add_image($r_id, $i_id);
					}
					else
					{
						$this->session->set_flashdata('warning', '<strong>Problem adding photo:</strong> ' . $this->photo->lasterr);
					}
				}
				
				// Images uploaded via AJAX are added to the hidden i_id input
				// Add them to the railway
				if ($op !== FALSE && $this->input->post('i_id'))
				{
					foreach ($this->input->post('i_id') as $i_id)
					{
						$this->railways_model->add_image($r_id, $i_id);
					}
				}
				
				// Railway WAS added/updated, and standard file upload was submitted
				if ($op !== FALSE && isset($_FILES['userfile']))
				{
					// Init upload of file
					$config['upload_path'] = realpath(FCPATH . '../../storage/images');
					$config['encrypt_name'] = TRUE;
					$config['allowed_types'] = 'jpg';
					$config['max_size']	= '3072';
					$config['max_width']  = '3000';
					$config['max_height']  = '2000';
					$this->load->library('upload', $config);
					
					if ($this->upload->do_upload())
					{
						$this->load->library('photo');
						$upload = $this->upload->data();
						$i_id = $this->photo->add_image($upload['file_name']);
						if ($i_id)
						{
							$this->railways_model->add_image($r_id, $i_id);
						}
						else
						{
							$this->session->set_flashdata('error', $this->photo->lasterr);
						}
					}
					else
					{
						$this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
					}
				}
				
				// Set message/status and go back to news section 
				$msg_type = ($op !== FALSE) ? 'success' : 'error';
				$msg = ($op !== FALSE) ? $ok : $err;
				$this->session->set_flashdata($msg_type, $msg);
				
				redirect('admin/railways');
				
			}		// End validation == OK
			
		}	// End input POST
		
		$this->layout->set_js(array(
			'../vendor/markitup/jquery.markitup',
			'../vendor/markitup/sets/markdown/set',
			'fileuploader',
		));
		$this->layout->set_css(array(
			'../vendor/markitup/skins/simple/style',
			'../vendor/markitup/sets/markdown/style',
		));
		
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
	
	
	
	
	public function remove_image()
	{
		if ($this->railways_model->remove_image($this->input->post('r_id'), $this->input->post('i_id')))
		{
			$res = array('status' => 'ok');
		}
		else
		{
			$res = array('status' => 'err');
		}
		$res['query'] = $this->db->last_query();
		$this->json = $res;
	}
	
	
	
}

/* End of file: application/controllers/admin/railways.php */