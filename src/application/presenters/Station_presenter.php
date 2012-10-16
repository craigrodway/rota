<?php

require_once(APPPATH . 'presenters/ROTA_Presenter.php');
require_once(APPPATH . 'presenters/Event_presenter.php');
require_once(APPPATH . 'presenters/Operator_presenter.php');
require_once(APPPATH . 'presenters/Railway_presenter.php');

class Station_presenter extends ROTA_Presenter
{
	
	
	private $_CI;
	
	public $data = array();
	public $event = array();
	public $operator = array();
	public $railway = array();
	
	
	public function __construct($data = array())
	{
		parent::__construct($data);
		
		// Create additional presenter objects for the events/operator
		$this->event = new Event_presenter($data);
		$this->operator = new Operator_presenter($data);
		$this->railway = new Railway_presenter($data);
	}
	
	
	
	public function s_date_registered($format = 'd/m/Y H:i')
	{
		return date($format, strtotime(element('s_date_registered', $this->data)));
	}
	
	
	public function edit_icon()
	{
		return icon_link('silk/edit', 'admin/stations/set/' . $this->s_id(), 'Edit registration');
	}
	
	
	public function delete_icon()
	{
		$attrs = array(
			'data-id' => $this->s_id(),
			'data-name' => $this->operator->o_callsign(),
			'data-text' => 'Are you sure you want to remove this registration?',
			'data-url' => site_url('admin/stations/delete'),
			'rel' => 'delete',
		);
		return icon_link('silk/delete', '#', 'De-register', $attrs);
	}
	
	
	public function shack_edit_button()
	{
		//return icon_link('silk/edit', 'shack/stations/register/' . $this->s_id(), 'Edit registration');
		return anchor('shack/stations/register/' . $this->s_id(), 'Edit', 'class="black button"');
	}
	
	
	public function shack_delete_button()
	{
		$attrs = array(
			'data-id' => $this->s_id(),
			'data-name' => $this->operator->o_callsign(),
			'data-text' => 'Are you sure you want to remove this registration?',
			'data-url' => site_url('shack/stations/deregister'),
			'rel' => 'delete',
			'class' => 'red button',
		);
		//return icon_link('silk/delete', '#', 'De-register', $attrs);
		return anchor('', 'De-register', $attrs);
	}
	
	
	public function operator_link()
	{
		if ($this->operator->o_name())
		{
			return anchor('operators/' . $this->operator->o_slug(), $this->operator->get('o_name', '(Unknown)'));
		}
		else
		{
			return '(Unknown)';
		} 
	}
	
	
}