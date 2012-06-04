<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH . '/presenters/Event_presenter.php');

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

class Events extends AdminController
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<li>', '</li>');
	}
	
	
	
	
	/**
	 * Events listing
	 */
	function index()
	{
		$this->events_model->order_by('e_year', 'desc');
		$this->data['events'] = $this->events_model->get_all();
		
		foreach ($this->data['events'] as &$event)
		{
			$event = new Event_presenter($event);
		}
		
		$this->layout->set_title('Events');
		$this->layout->set_view('links', 'admin/events/index-links');
	}
	
	
	
	
	/**
	 * Page to edit or create an event
	 */
	function set($e_year = NULL)
	{
		if ($e_year)
		{
			// Edit event
			if ( ! $event = $this->events_model->get($e_year))
			{
				show_error('Could not find requested event.', 404);
			}
			$this->data['event'] = new Event_presenter($event);
			$this->layout->set_title('Edit event');
		}
		else
		{
			// Adding new event
			$this->data['event'] = new Event_presenter();
			$this->layout->set_title('Add a new event');
		}
		
		$this->layout->set_js(array('date', 'jquery.datePicker'));
		
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
		
	}
	
	
	
	
	/**
	 * Delete an event (only accepts POSTed data)
	 */
	function delete()
	{
		$this->view = FALSE;
		
		$year = $this->input->post('id');
		if ( ! $year)
		{
			redirect('admin/events');
		}
		
		$delete = $this->events_model->delete($year);
		
		if ($delete == TRUE)
		{
			$msg_type = 'success';
			$msg = 'The event has been deleted successfully.';
		}
		else
		{
			$msg_type = 'error';
			$msg = 'Problem removing event - ' . $this->events_model->lasterr;
		}
		$this->session->set_flashdata($msg_type, $msg);
		
		redirect('admin/events');
	}
	
	
	
	
}

/* End of file: application/controllers/admin/events.php */