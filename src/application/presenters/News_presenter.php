<?php

require_once(APPPATH . 'presenters/ROTA_Presenter.php');
require_once(APPPATH . 'presenters/Event_presenter.php');
require_once(APPPATH . 'presenters/Operator_presenter.php');
require_once(APPPATH . 'presenters/Railway_presenter.php');
require_once(APPPATH . 'presenters/Station_presenter.php');
require_once(APPPATH . 'presenters/Image_presenter.php');

class News_presenter extends ROTA_Presenter
{
	
	
	private $_CI;
	
	public $data = array();
	public $event = array();
	public $operator = array();
	public $railway = array();
	public $station = array();
	
	
	public function __construct($data = array())
	{
		parent::__construct($data);
		
		// Create additional presenter objects for the events/operator
		$this->event = new Event_presenter($data);
		$this->operator = new Operator_presenter($data);
		$this->railway = new Railway_presenter($data);
		
		// Images array present? Make presenter objects
		if (is_array(element('images', $data)))
		{
			$this->images = presenters('Image', element('images', $data));
		}
		else
		{
			$this->images = array();
		}
	}
	
	
	public function n_datetime_posted($format = 'd/m/Y H:i')
	{
		return date($format, strtotime(element('n_datetime_posted', $this->data)));
	}
	
	
	
		
	public function edit_icon()
	{
		return icon_link('silk/edit', 'admin/news/set/' . $this->n_id(), 'Edit');
	}
	
	
	
	
	public function delete_icon()
	{
		$attrs = array(
			'data-id' => $this->n_id(),
			'data-name' => $this->n_title(),
			'data-text' => 'Are you sure you want to delete this news article?',
			'data-url' => site_url('admin/news/delete'),
			'rel' => 'delete',
		);
		return icon_link('silk/delete', '#', 'Delete', $attrs);
	}
	
	
	public function read_more_link($text = 'Read more')
	{
		$year = $this->n_datetime_posted('Y');
		return anchor('news/' . $year . '/' . element('n_slug', $this->data), $text);
	}
	
	
	
	
}