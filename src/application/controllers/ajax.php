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
	
	
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */