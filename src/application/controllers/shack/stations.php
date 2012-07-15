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
	}
	
	
	
	
	/**
	 * Event registrations index
	 */
	function index()
	{
		$this->layout->set_title('Event registrations');
		
		$this->stations_model->order_by('s_e_year', 'DESC');
		$this->data['stations'] = presenters('Station', $this->stations_model->get_by('o_a_id', $this->session->userdata('a_id')));
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
			$this->form_validation->set_rules('s_o_id', 'Operator', 'required|integer');
			
			if ($this->form_validation->run())
			{
				$data = array(
					's_o_id' => $this->input->post('s_o_id'),
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
		
		$this->layout->set_js('jquery.autocomplete-min');
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
	
	
	
	
}

/* End of file: application/controllers/shack/stations.php */