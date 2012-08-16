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

class Events extends MY_Controller
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->data['nav_active'] = 'events';
		
		$this->data['current_event'] = new Event_presenter($this->events_model->get_current());
		$this->data['all_events'] = presenters('Event', $this->events_model->get_past_present());
	}
	
	
	
	
	function index($year = NULL)
	{
		// Check if a year has been supplied
		if ($year === NULL)
		{
			// No year! Use the current event that's already set
			$event = $this->data['current_event'];
			$year = $event->e_year();
		}
		else
		{
			// Get the event info for the requested year
			$this->events_model->limit(1);
			$event = new Event_presenter($this->events_model->get_by('e_year', $year));
		}
		
		$this->data['view_mode'] = 'list';
		
		// Set the chosen year in the controller data
		$this->data['year'] = $year;
		
		$this->stations_model->order_by('s_date_registered', 'desc');
		$this->data['stations'] = presenters('Station', $this->stations_model->get_by('s_e_year', $year));
		
		$this->data['stats'] = $this->stations_model->stats($year);
		
		$this->data['hide_title'] = TRUE;
		
		$this->layout->set_view('content_full', 'events/header');
		$this->layout->set_view('content', 'events/stations');
		
		$this->layout->set_title($event->e_year() . ' Event');
	}
	
	
	
	
	function map($year = NULL)
	{
		$this->auto_view = FALSE;
		
		// Check if a year has been supplied
		if ($year === NULL)
		{
			// No year! Use the current event that's already set
			$event = $this->data['current_event'];
			$year = $event->e_year();
		}
		else
		{
			// Get the event info for the requested year
			$this->events_model->limit(1);
			$event = new Event_presenter($this->events_model->get_by('e_year', $year));
		}
		
		$this->data['view_mode'] = 'map';
		
		// Set the chosen year in the controller data
		$this->data['year'] = $year;
		
		$this->stations_model->order_by('s_date_registered', 'desc');
		$this->data['stations'] = presenters('Station', $this->stations_model->get_by('s_e_year', $year));
		
		$this->data['stats'] = $this->stations_model->stats($year);
		
		$this->data['hide_title'] = TRUE;
		
		// Load 2 views into the "content full" section
		$content_full = $this->load->view('events/header', $this->data, TRUE);
		$content_full .= $this->load->view('events/map', $this->data, TRUE);
		
		$this->layout->set_content('content_full', $content_full);
		$this->layout->set_css('../vendor/leaflet/leaflet');
		$this->layout->set_js(array('../vendor/leaflet/leaflet', '../vendor/leaflet/bing'));
		$this->layout->set_title('Events map');
	}
	
	
	
	
	public function pdf($year)
	{
		$this->auto_view = FALSE;
		
		// Set the chosen year in the controller data
		$this->data['year'] = $year;
		
		$this->stations_model->order_by('s_date_registered', 'desc');
		$this->data['stations'] = presenters('Station', $this->stations_model->get_by('s_e_year', $year));
		
		$this->data['stats'] = $this->stations_model->stats($year);
		
		// Get document in HTML format
		$document = $this->load->view('events/pdf', $this->data, TRUE);;
		
		// Include the PDF library and generate it
		require_once(APPPATH . 'third_party/dompdf/dompdf_config.inc.php');
		$dompdf = new DOMPDF();
		$dompdf->load_html($document);
		$dompdf->set_paper('a4', 'landscape');

		$dompdf->render();
		
		$pdf = $dompdf->output();
		
		$file_name = 'Railways on the Air ' . $year . '.pdf';
		
		// Set header to dorce download
		$this->output->set_content_type('application/pdf');
		$this->output->set_header("Content-Disposition: attachment; filename={$file_name}");
		$this->output->set_header("Cache-Control: private");
		$this->output->set_header("Pragma: token");
		$this->output->set_header("Expires: 0");
		$this->output->set_output($pdf);

	}
	
	
	
	
}

/* End of file: ./application/controllers/events.php */