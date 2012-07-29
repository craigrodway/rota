<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH . '/presenters/News_presenter.php');

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

class News extends AdminController
{
	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('news_model');

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		
		// Configure and load slug library
		$this->load->library('slug', array(
			'field' => 'n_slug',
			'title' => 'n_title',
			'table' => 'news',
			'id' => 'n_id',
		));
	}
	
	
	
	
	/**
	 * News listing
	 */
	function index()
	{
		$this->data['filter'] = $this->input->get(NULL, TRUE);
		
		$this->news_model->set_filter($this->data['filter']);
		$this->news_model->order_by('n_datetime_posted', 'desc');
		
		$this->data['news'] = presenters('News', $this->news_model->get_all());
		
		$this->data['events'] = $this->events_model->dropdown('e_year');
		
		$this->layout->set_title('News');
		$this->layout->set_view('links', 'admin/news/index-links');
	}
	
	
	
	
	/**
	 * Page to edit or create a news article
	 */
	function set($n_id = NULL)
	{
		if ($n_id)
		{
			// Edit article
			if ( ! $news = $this->news_model->get($n_id))
			{
				show_error('Could not find requested news article.', 404);
			}
			$this->data['news'] = new News_presenter($news);
			$this->layout->set_title('Edit news article');
		}
		else
		{
			// Adding new article
			$this->data['news'] = new Event_presenter();
			$this->layout->set_title('Write new article');
		}
		
		if ($this->input->post())
		{
			$this->form_validation
				->set_rules('n_title', 'Title', 'required|trim|max_length[128]')
				->set_rules('n_content', 'Content', 'required')
				->set_rules('n_e_year', 'Event year', 'exact_length[4]')
				->set_rules('n_r_id', 'Railway', 'integer')
				->set_rules('n_o_id', 'Operator', 'itneger');
			
			if ($this->form_validation->run())
			{
				$post = $this->input->post(NULL, TRUE);
				
				$data = array(
					'n_o_id' => element('n_o_id', $post, NULL),
					'n_r_id' => element('n_r_id', $post, NULL),
					'n_e_year' => element('n_e_year', $post, NULL),
					'n_content' => element('n_content', $post),
					'n_title' => element('n_title', $post),
				);
				
				$data['n_slug'] = ($n_id) ? $this->slug->create_uri($data, $n_id) : $this->slug->create_uri($data);
				
				if ($n_id)
				{
					// Update
					$op = $this->news_model->update($n_id, $data);
					$ok = "The news article has been updated successfully.";
					$err = 'An error occurred while updating the news article.';
				}
				else
				{
					// Add
					
					// Set date/time
					$data['n_datetime_posted'] = date('Y-m-d H:i:s');
					
					$op = $this->news_model->insert($data);
					$ok = "The news article has been created successfully.";
					$err = 'An error occurred while adding the event.';
				}
				
				$msg_type = ($op !== FALSE) ? 'success' : 'error';
				$msg = ($op !== FALSE) ? $ok : $err;
				$this->session->set_flashdata($msg_type, $msg);
				
				redirect('admin/news');
			}
			
		}
		
		$this->data['events'] = $this->events_model->dropdown('e_year');
		$this->data['operators'] = $this->operators_model->dropdown('o_callsign_o_name');
		$this->data['railways'] = $this->railways_model->dropdown('r_name');
		
		$this->layout->set_js('../vendor/redactor/redactor');
		$this->layout->set_css('../vendor/redactor/css/redactor');
		
	}
	
	
	
	
	/**
	 * Delete an article (only accepts POSTed data)
	 */
	function delete()
	{
		$this->view = FALSE;
		
		$n_id = $this->input->post('id');
		if ( ! $n_id)
		{
			redirect('admin/news');
		}
		
		$delete = $this->news_model->delete($n_id);
		
		if ($delete == TRUE)
		{
			$msg_type = 'success';
			$msg = 'The article has been deleted successfully.';
		}
		else
		{
			$msg_type = 'error';
			$msg = 'Problem removing article - ' . $this->news_model->lasterr;
		}
		$this->session->set_flashdata($msg_type, $msg);
		
		redirect('admin/news');
	}
	
	
	
	
}

/* End of file: application/controllers/admin/news.php */