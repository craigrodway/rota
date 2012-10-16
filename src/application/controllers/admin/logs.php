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

require(APPPATH . '/presenters/Station_presenter.php');

class Logs extends AdminController
{
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	public function index()
	{
		$this->layout->set_title('Logs');
		
		// Current event for registrations
		$event = $this->events_model->get_current();
		if ( ! $event)
		{
			show_error('Could not find current event.');
		}
		$year = element('e_year', $event);
		$this->stations_model->set_filter(array('s_e_year' => $year));
		$this->stations_model->order_by('s_date_registered', 'desc');
		$this->data['current_stations'] = presenters('Station', $this->stations_model->get_all());
		
		
		// Last event - uploaded
		$this->stations_model->clear_filter();
		$this->stations_model->set_filter(array(
			's_e_year' => $year - 1,
			's_log_status' => 'uploaded',
		));
		$this->stations_model->order_by('s_date_registered', 'desc');
		$this->data['previous_stations_uploaded'] = presenters('Station', $this->stations_model->get_all());
		
		// Last event - waiting
		$this->stations_model->clear_filter();
		$this->stations_model->set_filter(array(
			's_e_year' => $year - 1,
			's_log_status' => 'waiting',
		));
		$this->stations_model->order_by('s_date_registered', 'desc');
		$this->data['previous_stations_waiting'] = presenters('Station', $this->stations_model->get_all());
		
		$this->data['previous_event'] = $year - 1;
		$this->data['current_event'] = $year;
	}
	
	
	
	
}

/* End of file: application/controllers/admin/logs.php */