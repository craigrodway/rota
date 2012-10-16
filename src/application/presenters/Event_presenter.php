<?php

require_once(APPPATH . 'presenters/ROTA_Presenter.php');

class Event_presenter extends ROTA_Presenter
{
	
	
	private $_CI;
	
	public $data = array();
	
	
	public function __construct($data = array())
	{
		parent::__construct($data);
	}
	
	
	/**
	 * Returns if the event is 'current' or not.
	 * 
	 * @return boolean
	 */
	public function is_current()
	{
		return $this->e_current('boolean');
	}
	
	
	public function e_current($format = 'boolean')
	{
		$current = (element('e_current', $this->data) == 1);
		
		switch ($format)
		{
			case 'boolean':
				return $current;
			break;
			
			case 'icon':
				return ($current)
					? '<img src="img/global/icons/silk/bullet_tick.png" alt="Current">'
					: '<img src="img/global/icons/silk/bullet_cross.png" alt="Not current">';
			break;
		}
	}
	
	
	public function e_start_date($format = 'l dS F Y')
	{
		return date($format, strtotime(element('e_start_date', $this->data)));
	}
	
	
	public function e_end_date($format = 'l dS F Y')
	{
		return date($format, strtotime(element('e_end_date', $this->data)));
	}
	
	
	public function view_stations_icon()
	{
		return icon_link('silk/transmit', 'admin/stations/?s_e_year=' . $this->e_year(), 'View stations');
	}
	
	
	public function edit_icon()
	{
		return icon_link('silk/edit', 'admin/events/set/' . $this->e_year(), 'Edit');
	}
	
	
	public function delete_icon()
	{
		$attrs = array(
			'data-id' => $this->e_year(),
			'data-name' => $this->e_year(),
			'data-text' => 'Are you sure you want to remove this event?
				All stations registered for this event will also be removed.',
			'data-url' => site_url('admin/events/delete'),
			'rel' => 'delete',
		);
		return icon_link('silk/delete', '#', 'Delete', $attrs);
	}
	
	
}