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

class Ajax extends MY_Controller
{
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	
	
	public function lookup_postcode($pc)
	{
		$this->auth->check();
		
		$this->load->library('Nearby');
		$data = $this->nearby->postcode($pc);
		
		if ($data == false)
		{
			$output = array(
				'status' => 'err',
				'msg' => $this->nearby->lasterr,
			);
		}
		else
		{
			$output = array(
				'status' => 'ok',
				'data' => $data,
			);
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($output));
	}
	
	
	
	public function lookup_locator($loc)
	{
		$this->auth->check();
		
		$this->load->library('Nearby');
		$data = $this->nearby->locator($loc);
		
		if ($data == false)
		{
			$output = array(
				'status' => 'err',
				'msg' => $this->nearby->lasterr,
			);
		}
		else
		{
			$output = array(
				'status' => 'ok',
				'data' => $data,
			);
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($output));
	}
	
	
	
	
	public function staticmap()
	{
		$data['sensor'] = 'false';
		$data['coords'] = $this->input->get('coords');
		$data['zoom'] = $this->input->get('zoom');
		$data['size'] = $this->input->get('size');
		$data['maptype'] = $this->input->get('maptype');
		
		$marker = "color:red|{$data['coords']}";
		$data['markers'] = $marker;
		
		$data['urlonly'] = true;
		
		$output['src'] = $this->maps->staticmap($data); 
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($output));
		
	}
	
	
	
	
	public function railways()
	{
		$q = $this->input->get('query');
		$this->railways_model->set_filter(array('r_name' => $q));
		$this->railways_model->order_by('r_name', 'asc');
		$railways = $this->railways_model->get_all();
		
		$json = array(
			'query' => $q,
			'suggestions' => array(),
			'data' => array()
		);
		
		foreach ($railways as $r)
		{
			$json['suggestions'][] = $r['r_name'];
			$json['data'][] = $r['r_id'];
		}
		
		$this->json =& $json;
	}
	
	
	
	/**
	 * All railways with location data in GeoJSON format
	 */
	public function railways_geojson()
	{
		$geojson = array(
			'type' => 'FeaturesCollection',
			'features' => array(),
		);
		
		$railways = presenters('Railway', $this->railways_model->get_all());
		
		foreach ($railways as &$r)
		{
			if ( ! $r->latlng()) continue;
			
			$content = '<h5>' . anchor('railways/' . $r->r_slug(), $r->r_name()) . '</h5>';
			
			// Add thumbnail image if present
			if ( ! empty($r->images))
			{
				$content .= '<br><img src="'  . $r->images[0]->src('c150x150') . '" width="64" height="64" alt="">'; 
			}
			
			$feature = array(
				'type' => 'Feature',
				'id' => $r->r_id(),
				'properties' => array(
					'name' => $r->r_name(),
					'amenity' => 'Railway',
					'popupContent' => $content,
				),
				'geometry' => array(
					'type' => 'Point',
					'coordinates' => array($r->r_lng(), $r->r_lat()),
				)
			);
			
			$geojson['features'][] = $feature;
		}
		
		$this->json =& $geojson;
	}
	
	
	
	/**
	 * All events for a given year with railway location data in GeoJSON format
	 */
	public function events_geojson($year = NULL)
	{
		$geojson = array(
			'type' => 'FeaturesCollection',
			'features' => array(),
		);
		
		// Get list of all stations
		$stations = presenters('Station', $this->stations_model->get_by('s_e_year', $year));
		
		foreach ($stations as &$s)
		{
			if ( ! $s->railway->latlng()) continue;
			
			$content = '<p class="map-op-callsign">' . $s->operator->o_callsign() . '</p>
				<p class="map-op-name">' . anchor('operators/' . $s->operator->o_slug(), $s->operator->o_name()) . '</p>
				<p class="map-railway">' . anchor('railways/' . $s->railway->r_slug(), $s->railway->r_name()) . '</p>';
			
			$feature = array(
				'type' => 'Feature',
				'id' => $s->s_id(),
				'properties' => array(
					'name' => $s->operator->o_callsign() . ', ' . $s->operator->o_name(),
					'amenity' => 'Station',
					'popupContent' => $content,
				),
				'geometry' => array(
					'type' => 'Point',
					'coordinates' => array($s->railway->r_lng(), $s->railway->r_lat()),
				)
			);
			
			$geojson['features'][] = $feature;
		}
		
		$this->json =& $geojson;
	}	
	
	
}

/* End of file home.php */
/* Location: ./application/controllers/ajax.php */