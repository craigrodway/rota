<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'presenters/Operator_presenter.php');

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

class Operators extends ShackController
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		
		$this->data['sidebar_menu'] = $this->menu_model->account();
		$this->layout->set_view('sidebar', 'shack/sidebar');
		
		$config = array(
			'field' => 'o_slug',
			'title' => 'o_name',
			'table' => 'operators',
			'id' => 'o_id',
		);
		$this->load->library('slug', $config);
	}
	
	
	
	
	/**
	 * "My account" details page
	 *
	 * @param string $tab		Selected tab (default - help)
	 */
	function index($tab = 'help')
	{
		if ($this->input->post())
		{
			$err = FALSE;
			
			// validation for new operators
			$this->form_validation
				->set_rules('operator[new][o_type]', 'Type', 'required|alpha')
				->set_rules('operator[new][o_name]', 'Name', 'required')
				->set_rules('operator[new][o_callsign]', 'Callsign', 'required|min_length[4]|max_length[10]|strtoupper')
				->set_rules('operator[new][o_url]', 'URL', 'valid_url|prep_url')
				->set_rules('operator[new][o_info_src]', 'Information', 'trim');
				
			// get all POST data
			$post = $this->input->post(NULL, TRUE);
			
			// get new operator details and remove from POST array
			$new_operator = $post['operator']['new'];
			unset($post['operator']['new']);
			
			// add new operator if not empty
			if ( ! empty($new_operator['o_callsign']))
			{
				if ($this->form_validation->run())
				{
					// get all POST data again after being updated via validation
					$post = $this->input->post(NULL, TRUE);
					
					// get new operator details and remove from POST array
					$new_operator = $post['operator']['new'];
					unset($post['operator']['new']);
					
					// data array to create new operator
					$data = array(
						'o_a_id' => $this->session->userdata('a_id'),
						'o_type' => element('o_type', $new_operator, 'person'),
						'o_name' => $new_operator['o_name'],
						'o_callsign' => $new_operator['o_callsign'],
						'o_url' => $new_operator['o_url'],
						'o_info_src' => $new_operator['o_info_src'],
					);
					
					// add new operator
					$add = $this->operators_model->insert($data);
					
					if ($add)
					{
						$messages['success'][] = "New operator {$new_operator['o_callsign']} has been added.";
					}
					else
					{
						$messages['error'][] = 'New operator could not be added.';
						$err = TRUE;
					}
				}
				else
				{
					// encountered form validation error on new operator
					$err = TRUE;
					$tab = 'new';
				}
			}
			
			// $post will now be just operators to update
			if ( ! empty($post))
			{
				foreach ($post['operator'] as $id => $op)
				{
					$id = (int) $id;
					
					// check if a deletion was requested
					if ( (int) $post['delete'][$id] === 1)
					{
						if ($this->operators_model->delete($id))
						{
							$messages['success'][] = "Successfully deleted operator {$op['o_callsign']}.";
						}
						else
						{
							$messages['error'][] = "Failed to delete operator {$op['o_callsign']}.";
							$err = TRUE;
						}
					}
					
					// ensure all previous rules have been cleared and class is clean for this run
					$this->form_validation->clear_rules();

					// validation for oeprator update
					$this->form_validation
						->set_rules("operator[$id][o_type]", 'Type', 'required|alpha')
						->set_rules("operator[$id][o_name]", 'Name', 'required')
						->set_rules("operator[$id][o_callsign]", 'Callsign', 'required|min_length[4]|max_length[10]|strtoupper')
						->set_rules("operator[$id][o_url]", 'URL', 'valid_url|prep_url')
						->set_rules("operator[$id][o_info_src]", 'Information', 'trim');
					
					if ($this->form_validation->run())
					{
						// validation passed - update operator
						$data = array(
							'o_a_id' => $this->session->userdata('a_id'),
							'o_type' => element('o_type', $op, 'person'),
							'o_name' => $op['o_name'],
							'o_callsign' => $op['o_callsign'],
							'o_url' => $op['o_url'],
							'o_info_src' => $op['o_info_src'],
						);
						
						$data['o_slug'] = ($id) ? $this->slug->create_uri($data, $id) : $this->slug->create_uri($data);
						
						$update = $this->operators_model->update($id, $data);
						
						// checking affected rows to only show response when things actually change
						if ($update && $this->db->affected_rows() == 1)
						{
							// data changed, saved OK
							$messages['success'][] = "Operator {$op['o_callsign']} updated.";
						}
						elseif ( ! $update)
						{
							$messages['error'][] = "Failed to update information for {$op['o_callsign']}.";
							$err = TRUE;
						}
					}
					else
					{
						// form validation failed on the operator update.
						$tab = $op['o_callsign'];
						$err = TRUE;
					}
				}		// end foreach $operators
			}		// end if empty $post for operator updates
			
			// check status and set flash messages
			if ( ! empty($messages['error']))
			{
				$this->session->set_flashdata('error', implode('<br>', $messages['error']));
			}
			
			if ( ! empty($messages['success']))
			{
				$this->session->set_flashdata('success', implode('<br>', $messages['success']), TRUE);
			}
			
			// redirect if no errors
			if ( ! $err)
			{
				redirect('shack/operators');
			}
			
		}		// end POST check
		
		// load page
		$this->layout->set_title('Radio operator profiles');
		
		// get all profiles for logged in user
		$this->data['operators'] = $this->operators_model->get_by('o_a_id', $this->session->userdata('a_id'));
		
		foreach ($this->data['operators'] as &$op)
		{
			$op = new Operator_presenter($op);
		}
		
		// set active tab
		$this->data['tab'] = $tab;
	}
	
	
	
	
}

/* End of file: application/controllers/shack/operators.php */