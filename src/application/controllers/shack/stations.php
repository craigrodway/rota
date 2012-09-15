<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH . '/presenters/Event_presenter.php');
require(APPPATH . '/presenters/Station_presenter.php');

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

class Stations extends ShackController
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		
		$this->data['sidebar_menu'] = $this->menu_model->account();
		$this->layout->set_view('sidebar', 'shack/sidebar');
		
		// Current event to register for
		$this->data['current_event'] = new Event_presenter($this->events_model->get_current());
		
		// Slug library config for operator callsigns
		$config = array(
			'field' => 'o_slug',
			'title' => 'o_name',
			'table' => 'operators',
			'id' => 'o_id',
		);
		$this->load->library('slug', $config);
	}
	
	
	
	
	/**
	 * Event registrations index
	 */
	function index()
	{
		$this->layout->set_title('Event registrations');
		
		$this->stations_model->order_by('s_e_year', 'DESC');
		$this->data['stations'] = presenters('Station', $this->stations_model->get_by('o_a_id', $this->session->userdata('a_id')));
		
		$this->layout->set_js('fileuploader');
	}
	
	
	
	
	/**
	 * Register or update a station
	 */
	function register($s_id = NULL)
	{
		// Account ID
		$a_id = $this->session->userdata('a_id');
		
		if ($this->input->post())
		{
			// Form submitted - register or update
						
			if ( ! $this->input->post('s_o_id'))
			{
				// No operator ID. Presume we're adding a new operator
				$this->form_validation
					->set_rules("o_type", 'Type', 'required|alpha')
					->set_rules("o_name", 'Name', 'required|trim')
					->set_rules("o_callsign", 'Callsign', 'required|trim|min_length[4]|max_length[10]|strtoupper')
					->set_rules("o_url", 'URL', 'valid_url|prep_url|trim')
					->set_rules("o_info_src", 'Information', 'trim');
				
				if ($this->form_validation->run())
				{
					// Create new operator
					$data = array(
						'o_a_id' => $this->session->userdata('a_id'),
						'o_type' => element('o_type', $this->input->post(), 'person'),
						'o_name' => $this->input->post('o_name'),
						'o_callsign' => $this->input->post('o_callsign'),
						'o_url' => $this->input->post('o_url'),
						'o_info_src' => $this->input->post('o_info_src'),
						'o_info_html' => parse_markdown($this->input->post('o_info_src')),
					);
					
					$data['o_slug'] = ($id) ? $this->slug->create_uri($data, $id) : $this->slug->create_uri($data);
					
					$add = $this->operators_model->insert($data);
					if ( ! $add)
					{
						$this->session->set_flashdata('error', 'Could not add new operator with those details.');
						redirect('shack/stations/register');
					}
					else
					{
						// Get new operator ID
						$o_id = $add;
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Problem with supplied details: ' . validation_errors());
					redirect('shack/stations/register');
				}
				
				$this->form_validation->clear_rules();
			}
			
			if ($s_id)
			{
				// Updating a station
				$this->form_validation->set_rules('s_r_id', 'Railway', 'integer');
			}
			else
			{
				// Registering a new station - railway is required
				$this->form_validation->set_rules('s_r_id', 'Railway', 'integer');
			}
			
			$this->form_validation->set_rules('railway_new', 'Railway name', 'trim|max_length[100]');
			$this->form_validation->set_rules('s_o_id', 'Operator', 'integer');
			
			if ($this->form_validation->run())
			{
				// Use operator ID + callsign from new one added, or from POST
				$data = array(
					's_o_id' => (int) (isset($o_id)) ? $o_id : $this->input->post('s_o_id'),
					's_callsign' => (isset($o_id)) ? $this->input->post('o_callsign') : $this->input->post('s_callsign'),
				);
				
				if ($this->input->post('s_r_id'))
				{
					$data['s_r_id'] = $this->input->post('s_r_id');
				}
				else
				{
					// No railway ID selected - make new one from name supplied
					$railway = array(
						'r_name' => $this->input->post('railway_new'),
					);
					
					// Create new railway
					$data['s_r_id'] = $this->railways_model->insert($railway);
					
					if ($data['s_r_id'])
					{
						// Success - store data temporarily so page can show message asking user to add more info
						$this->session->set_flashdata('new_railway_id', $data['s_r_id']);
						$this->session->set_flashdata('new_railway_name', $railway['r_name']);
					}
					else
					{
						// Failed to create railway - cannot continue with rest of registration
						$this->session->set_flashdata('error', 'Unable to create the new railway <em>' . $railway['r_name'] . '</em>.');
						redirect('shack/stations/register/' . $s_id);
					}
				}
				
				if ( ! $s_id)
				{
					// Registering for 'current' event
					$data['s_e_year'] = $this->data['current_event']->e_year();
				}
				
				// Check that no duplicates exist
				$filter = array(
					's_e_year' => $data['s_e_year'],
					's_o_id' => $data['s_o_id'],
					's_r_id' => $data['s_r_id'],
					's_callsign' => $data['s_callsign'],
				);
				
				$this->stations_model->set_filter($filter);
				if ($stations = $this->stations_model->get_all())
				{
					//$this->output->enable_profiler(true);
					//return;
					$this->session->set_flashdata('error', 'Only one registration per operator per railway is permitted.');
					redirect('shack/stations/register');
				}
				
				if ($s_id)
				{
					// Updating a station
					$op = $this->stations_model->update($s_id, $data);
					$ok = 'Your registration has been updated.';
					$err = 'Your registration could not be updated.';
				}
				else
				{
					$op = $this->stations_model->insert($data);
					$ok = 'Your registration has been successful.';
					$err = 'Your registration did not succeed.';
				}
				
				if ($op)
				{
					$this->session->set_flashdata('success', $ok);
				}
				else
				{
					$this->session->set_flashdata('error', $err);
				}
				
				redirect('shack/stations');
			}
		}
		
		if ($s_id)
		{
			$this->layout->set_title('Update station');
			
			// Make sure station being edited belongs to user logged in
			$this->stations_model->set_filter(array(
				'o_a_id' => $a_id
			));
			$this->data['station'] = new Station_presenter($this->stations_model->get($s_id));
			$this->data['s_id'] = $s_id;
		}
		else
		{
			$this->layout->set_title('Register a station');
			$this->data['station'] = new Station_presenter();
		}
		
		// Railways from previous stations of this account
		$this->railways_model->order_by('r_name', 'asc');
		$this->data['past_railways'] = presenters('Railway', $this->railways_model->get_past($a_id));
		
		// Operators belonging to the account
		$this->data['operators'] = presenters('Operator', $this->operators_model->get_by('o_a_id', $a_id));
		
		$this->layout->set_js(array(
			'jquery.autocomplete-min',
			'../vendor/markitup/jquery.markitup',
			'../vendor/markitup/sets/markdown/set',
			'fileuploader',
		));
		$this->layout->set_css(array(
			'../vendor/markitup/skins/simple/style',
			'../vendor/markitup/sets/markdown/style',
		));
	}
	
	
	
	
	public function deregister()
	{
		$this->view = FALSE;
		
		$id = $this->input->post('id');
		if ( ! $id)
		{
			redirect('shack/stations');
		}
		
		// Get information about the station
		$station = $this->stations_model->get($id);
		
		// Check the operator of the station's account is the logged in user
		if ($station['o_a_id'] !== $this->session->userdata('a_id'))
		{
			$this->session->set_flashdata('error', 'That is not your station to de-register.');
			redirect('shack/stations');
		}
		
		$delete = $this->stations_model->delete($id);
		
		if ($delete == true)
		{
			$msg_type = 'success';
			$msg = 'The station has been de-registered.';
		}
		else
		{
			$msg_type = 'error';
			$msg = 'There was an error de-registering the station. ' . $this->stations_model->lasterr;
		}
		
		$this->session->set_flashdata($msg_type, $msg);
		
		redirect('shack/stations');
	}
	
	
	
	
	public function upload_log()
	{
		// Try to handle the image upload
		$config['upload_path'] = realpath(FCPATH . '../../storage/logs');
		$config['encrypt_name'] = TRUE;
		$config['allowed_types'] = 'doc|docx|xls|xlsx|pdf|jpg|jpeg|png';
		$config['max_size']	= '3072';
		$config['max_width']  = '3000';
		$config['max_height']  = '2000';

		if ($this->input->get('qqfile'))
		{
			$this->load->library('upload', $config);
			if ($this->upload->do_upload())
			{
				$upload_data = $this->upload->data();
				
				// Got the file. Update station entry.
				
				$s_id = $this->input->get('s_id');
				$station = $this->stations_model->get($s_id);
				$station = new Station_presenter($station);
				
				// Get station and check account matches
				if ($station->operator->o_a_id() == $this->session->userdata('a_id'))
				{
					// Got station info and the account ID matches logged in user.
					
					// Update the file
					$data = array(
						's_log_file_name' => $upload_data['file_name'],
						's_date_log_uploaded' => date('Y-m-d H:i:s'),
						's_log_status' => 'uploaded',
					);
					
					// Update DB with log info
					if ($this->stations_model->update($s_id, $data))
					{
						$res = array(
							'status' => 'ok',
							'success' => TRUE,
							'data' => $this->upload->data(),
						);
						
						$this->session->set_flashdata('success', 'Thanks, your log file has been received!');
					}
					else
					{
						$res = array(
							'status' => 'err',
							'success' => FALSE,
							'msg' => 'Failed to record the log file.',
						);
					}
				}
				else
				{
					$res = array(
						'status' => 'err',
						'success' => FALSE,
						'msg' => 'Station ID was invalid.',
					);
				}
				
			}
			else
			{
				$res = array(
					'status' => 'err',
					'success' => FALSE,
					'msg' => strip_tags($this->upload->display_errors()),
				);
			}
		}
		else
		{
			$res = array(
				'status' => 'err',
				'success' => FALSE,
				'msg' => 'No file to upload',
			);
		}
		
		$this->json = $res;
	}
	
	
	
	
}

/* End of file: application/controllers/shack/stations.php */