<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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

class Stations extends AdminController
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<li>', '</li>');
	}
	
	
	
	
	/**
	 * Stations listing
	 */
	function index($year = NULL)
	{
		if ( ! $year)
		{
			$event = $this->events_model->get_current();
			if ( ! $event)
			{
				show_error('Could not find current event.');
			}
			$year = element('e_year', $event);
		}
		
		$this->data['year'] = $year;
		
		$this->stations_model->set_filter(array('s_e_year' => (int) $year));
		$this->stations_model->order_by('s_date_registered', 'desc');
		$this->data['stations'] = $this->stations_model->get_all();
		
		foreach ($this->data['stations'] as &$station)
		{
			$station = new Station_presenter($station);
		}
		
		$this->events_model->order_by('e_year', 'DESC');
		$this->events_model->clear_limit();
		$this->data['events'] = $this->events_model->get_all();
		
		foreach ($this->data['events'] as &$event)
		{
			$event = new Event_presenter($event);
		}
		
		$this->layout->set_title('Stations');
		$this->layout->set_view('links', 'admin/stations/index-links');
	}
	
	
	
	
	/**
	 * Register a station
	 */
	function set($s_id = NULL)
	{
		if ($s_id)
		{
			// Edit station
			if ( ! $station = $this->stations_model->get($s_id))
			{
				show_error('Could not find requested station.', 404);
			}
			$this->data['station'] = new Station_presenter($station);
			$this->layout->set_title('Edit station registration');
		}
		else
		{
			// Adding new station registration
			$this->data['station'] = new Station_presenter();
			$this->layout->set_title('Add a new station registration');
		}
		
		if ($this->input->post())
		{
			$this->form_validation
				->set_rules('e_year', 'Year', 'required|trim|exact_length[4]|is_natural_no_zero')
				->set_rules('e_start_date', 'Start date', 'required');
			
			if ($this->form_validation->run())
			{
				$start_date = $this->input->post('e_start_date');
				$end_date = date('Y-m-d', strtotime('+1 day', strtotime($start_date)));
				
				$data = array(
					'e_year' => $this->input->post('e_year'),
					'e_start_date' => $start_date,
					'e_end_date' => $end_date,
					'e_current' => 'N',
				);
				
				if ($e_year)
				{
					// Update
					$op = $this->events_model->update($e_year, $data);
					$ok = "The <strong>{$e_year}</strong> event has been updated successfully.";
					$err = 'An error occurred while updating the event.';
				}
				else
				{
					// Add
					$op = $this->events_model->insert($data);
					$ok = "The <strong>{$e_year}</strong> event has been added successfully.";
					$err = 'An error occurred while adding the event.';
				}
				
				$msg_type = ($op !== FALSE) ? 'success' : 'error';
				$msg = ($op !== FALSE) ? $ok : $err;
				$this->session->set_flashdata($msg_type, $msg);
				
				$this->events_model->set_active();
				
				redirect('admin/events');
			}
			
		}
		
		$this->data['operators'] = $this->operators_model->dropdown('o_callsign');
		$this->data['events'] = $this->events_model->get_dropdown('e_year');		
	}
	
	
	
	
	/**
	 * Delete a registration
	 */
	function delete()
	{
		$this->view = FALSE;
		
		$s_id = $this->input->post('id');
		if ( ! $s_id)
		{
			redirect('admin/stations');
		}
		
		$delete = $this->stations_model->delete($s_id);
		
		if ($delete == TRUE)
		{
			$msg_type = 'success';
			$msg = 'The stations has been de-registered.';
		}
		else
		{
			$msg_type = 'error';
			$msg = 'Problem de-registering the station - ' . $this->stations_model->lasterr;
		}
		$this->session->set_flashdata($msg_type, $msg);
		
		redirect('admin/stations');
	}
	
	
	
	
	/**
	 * Parse the legacy ROTA table and create operators/stations/accounts
	 */
	public function parse()
	{
		$this->view = FALSE;
		
		$sql = 'SELECT * 
				FROM rota_legacy
				WHERE confirm IS NULL
				ORDER BY email DESC';
		$legacy_stations = $this->db->query($sql)->result_array();
		
		foreach ($legacy_stations as $legacy)
		{
			log_message('debug', " >>> Processing legacy item ID " . element('id', $legacy));
			log_message('debug', "Year " . element('year', $legacy) . ", Callsign " . element('callsign', $legacy) . ", Railway " . element('railway_name', $legacy));
			
			$operator = NULL;
			$railway = NULL;
			$account = NULL;
			$station = array();
			
			$legacy_email = trim(element('email', $legacy, NULL));
			
			log_message('debug', "Looking for existing account for " . $legacy_email);
			
			// Find account
			if ( ! empty($legacy_email))
			{
				$this->db->limit(1);
				$this->db->like('a_email', $legacy_email);
				$query = $this->db->get('accounts');
			
				if ($query->num_rows() == 1)
				{
					// Found account
					$account = $query->row_array();
					
					log_message('debug', "Found an account to assign to new operator. Email {$account['a_email']}. ID {$account['a_id']}.");
				}
				else
				{
					// Could not find account. Create it based on the email from this legacy
					log_message('debug', "Could not find account. Creating one for " . $legacy_email);
					
					$email_parts = explode('@', $legacy_email);
					$password = strtolower($email_parts[0]);
					$password = $this->auth->hash_password($password);
					
					$account = array(
						'a_email' => $legacy_email,
						'a_created' => date('Y-m-d H:i:s'),
						'a_enabled' => 'Y',
						'a_type' => 'member',
						'a_password' => $password,
					);
					
					if ($this->db->insert('accounts', $account))
					{
						// Added new account OK - get ID
						$account['a_id'] = $this->db->insert_id();
						
						log_message('debug', "New account created for " . $account['a_email'] . ". ID: " . $account['a_id']);
					}
					else
					{
						log_message('debug', "Failed to create account for " . $account['a_email']);
					}
					
				}		// End of account found check
			
			}
			else
			{
				log_message('debug', "Email address is empty. Unable to make or find account.");
			}
				
			// Done account stuff. Continue with operator creation
			
			$types = array(
				'-1' => 'club',
				'0' => 'person',
				'1' => 'club',
			);
			
			$operator = array(
				'o_a_id' => element('a_id', $account, NULL),
				'o_type' => element($legacy['club'], $types, 'club'),
				'o_name' => trim(element('name', $legacy)),
				'o_callsign' => trim(element('callsign', $legacy)),
				'o_url' => trim(element('url', $legacy)),
			);
			
			if ($this->db->insert('operators', $operator))
			{
				// Added operator OK! get ID
				$operator['o_id'] = $this->db->insert_id();
				
				log_message('debug', "New operator created for " . $operator['o_callsign'] . " / " . $operator['o_name'] . ". ID: " . $operator['o_id']);
			}
			else
			{
				log_message('debug', "Failed to create operator for " . $operator['o_callsign'] . "/" . $operator['o_name']);
			}
			
			
			log_message('debug', "Looking for railway " . element('railway_name', $legacy));
			
			$legacy_railway_name = trim(element('railway_name', $legacy));
			$legacy_railway_url = trim(element('railway_url', $legacy));
			
			// Find railway
			$this->db->limit(1);
			$this->db->like('r_name', $legacy_railway_name);
			$this->db->or_like('r_url', $legacy_railway_url);
			$query = $this->db->get('railways');
			
			if ($query->num_rows() == 1)
			{
				// Got railway
				$railway = $query->row_array();
				log_message('debug', "Found matching railway, ID " . $railway['r_id']);
			}
			else
			{
				log_message('debug', "Could not find railway " . $legacy_railway_name);
				
				$railway = array(
					'r_name' => $legacy_railway_name,
					'r_url' => $legacy_railway_url,
				);
				
				if ($this->db->insert('railways', $railway))
				{
					$railway['r_id'] = $this->db->insert_id();
					log_message('debug', "Created new railway " . $legacy_railway_name . ". ID " . $railway['r_id']);
				}
			}
			
			if ($operator && $railway)
			{
				$station = array(
					's_e_year' => element('year', $legacy),
					's_r_id' => element('r_id', $railway),
					's_o_id' => element('o_id', $operator),
					's_date_registered' => element('date', $legacy, NULL),
				);
				
				log_message('debug', "Registering station with the following data: " . json_encode($station, JSON_NUMERIC_CHECK));
				
				$this->stations_model->insert($station);
			}
			else
			{
				log_message('debug', "ERROR - required data not present. Skipping.");
			}
			
		}
	}
	
	
	
	
}

/* End of file: application/controllers/admin/stations.php */