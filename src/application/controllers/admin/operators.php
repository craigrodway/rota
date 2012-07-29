<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH . '/presenters/Operator_presenter.php');

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

class Operators extends AdminController
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		$this->load->model('operators_model');
		
		$config = array(
			'field' => 'o_slug',
			'title' => 'o_name',
			'table' => 'operators',
			'id' => 'o_id',
		);
		$this->load->library('slug', $config);
	}
	
	
	
	
	/**
	 * Operators listing
	 */
	function index($page = 0)
	{
		$filter = $this->input->get(NULL, TRUE);
		$this->operators_model->set_filter($filter);
		
		$this->load->library('pagination');
		
		$config['base_url'] = site_url('admin/operators/index/');
		$config['total_rows'] = $this->operators_model->count_all();
		$config['per_page'] = 15; 
		$config['uri_segment'] = 4;
		$config['use_page_numbers'] = TRUE;
		$config['suffix'] = '?' . @http_build_query($filter);
		$this->pagination->initialize($config);
		
		$this->operators_model->order_by('o_callsign', 'asc')
							  ->limit(15, $page);
		
		$this->data['operators'] = $this->operators_model->get_all();
		$this->data['filter'] =& $filter;
		
		foreach ($this->data['operators'] as &$operator)
		{
			$operator = new Operator_presenter($operator);
		}
		
		$this->layout->set_title('Operators');
		$this->layout->set_view('links', 'admin/operators/index-links');
	}
	
	
	
	
	/**
	 * Page to edit or create an operator
	 */
	function set($o_id = NULL)
	{
		$data = array();
		
		if ($o_id)
		{
			// Editing account. Get it via ID.
			if ( ! $operator = $this->operators_model->get($o_id))
			{
				show_error('Could not find requested operator.', 404);
			}
			$this->data['operator'] = new Operator_presenter($operator);
			$this->layout->set_title('Edit operator');
		}
		else
		{
			// Adding new operator
			$this->data['operator'] = new Operator_presenter();
			$this->layout->set_title('Add a new operator');
		}
		
		// List of accounts operator can belong to
		$this->data['accounts'] = $this->accounts_model->dropdown('a_email');
		
		if ($this->input->post())
		{
			$this->form_validation
				->set_rules('o_id', 'Operator ID', '')
				->set_rules('o_a_id', 'Account ID', '')
				->set_rules('o_type', 'Type', 'required|alpha')
				->set_rules('o_name', 'Name', 'required')
				->set_rules('o_callsign', 'Callsign', 'required|min_length[4]|max_length[10]|strtoupper')
				->set_rules('o_url', 'URL', 'valid_url')
				->set_rules('o_info', 'Information', 'trim');
			
			if ($this->form_validation->run())
			{
				// OK!
				$data = array(
					'o_a_id' => (int) $this->input->post('o_a_id'),
					'o_type' => $this->input->post('o_type'),
					'o_name' => $this->input->post('o_name'),
					'o_callsign' => $this->input->post('o_callsign'),
					'o_url' => $this->input->post('o_url'),
					'o_info_src' => $this->input->post('o_info_src'),
				);
				
				$data['o_slug'] = ($o_id) ? $this->slug->create_uri($data, $o_id) : $this->slug->create_uri($data);
				
				// Do required action depending on whether an account is being created or updated
				if ($o_id)
				{
					// Update
					$op = $this->operators_model->update($o_id, $data);
					$ok = "The operator profile for <strong>{$data['o_callsign']}</strong> has been updated.";
					$err = 'An error occurred while updating the operator profile.';
				}
				else
				{
					// Add
					$op = $this->operators_model->insert($data);
					$ok = "The operator profile for <strong>{$data['o_callsign']}</strong> has been created.";
					$err = 'An error occurred while adding the account.';
				}
				
				$msg_type = ($op) ? 'success' : 'error';
				$msg = ($op) ? $ok : $err;
				$this->session->set_flashdata($msg_type, $msg);
				
				redirect('admin/operators');
				
			}		// end of validation == TRUE
			
		}		// end of POST check
		
	}
	
	
	
	
	/**
	 * Delete an account (only accepts POSTed data)
	 */
	function delete()
	{
		$this->view = FALSE;
		
		$id = $this->input->post('id');
		if ( ! $id)
		{
			redirect('admin/operators');
		}
		
		if ($this->operators_model->delete($id))
		{
			$msg_type = 'success';
			$msg = 'The operator profile has been deleted successfully.';
		}
		else
		{
			$msg_type = 'error';
			$msg = 'Problem removing operator profile - ' . $this->operators_model->lasterr;
		}
		$this->session->set_flashdata($msg_type, $msg);
		
		redirect('admin/operators');
	}
	
	
	
	
	/**
	 * Merge two or more operator accounts into one
	 */ 
	public function merge()
	{
		$this->view = FALSE;
		
		$operator_ids = $this->input->post('o_ids');
		
		if (empty($operator_ids))
		{
			$this->session->set_flashdata('warning', 'No operators selected for merging.');
			redirect('admin/operators');
		}
		
		// Loop through all operators and get info
		foreach ($operator_ids as $o_id)
		{
			echo "Operator ID $o_id...\n";
			$operator = $this->operators_model->get($o_id);
			// Loop through the attributes and add to array of all operator info
			foreach ($operator as $key => $value)
			{
				if ( ! empty($value))
				{
					$operators[$key][] = $value;
				}
				$operators[$key] = array_unique($operators[$key]);
			}
		}
		
		foreach ($operators as $key => $values)
		{
			$new_value = implode(', ', $values);
			if ($new_value !== ', ')
			{
				$new_operator[$key] = $new_value;
			}
			else
			{
				$new_operator[$key] = $new_value = NULL;
			}
		}
		
		// Unset or set specific values
		unset($new_operator['o_id']);
		$new_operator['o_a_id'] = $operators['o_a_id'][0];
		$new_operator['o_type'] = $operators['o_type'][0];
		
		// Add new operator to database
		$new_o_id = $this->operators_model->insert($new_operator);
		
		// Update the stations listing to reference the new operator ID
		$o_ids = implode(',', $operator_ids);
		$sql = 'UPDATE stations SET s_o_id = ? WHERE s_o_id IN(' . $o_ids . ')';
		$update_stations = $this->db->query($sql, array($new_o_id));
		
		// Remove old operators
		$sql = 'DELETE FROM operators WHERE o_id IN (' . $o_ids . ')';
		$delete_old = $this->db->query($sql);
		
		if ($new_o_id !== FALSE && $update_stations && $delete_old)
		{
			$this->session->set_flashdata('success', 'Operators have been merged!');
			$url = 'admin/operators/set/' . $new_o_id;
		}
		else
		{
			$this->session->set_flashdata('error', 'Failed to merge operators.');
			$url = 'admin/operators';
		}
		
		redirect($url);
	}
	
	
	
	
}

/* End of file: application/controllers/admin/accounts.php */