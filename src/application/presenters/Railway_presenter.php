<?php

require_once(APPPATH . 'presenters/ROTA_Presenter.php');
require_once(APPPATH . 'presenters/Image_presenter.php');

class Railway_presenter extends ROTA_Presenter
{
	
	
	private $_CI;
	
	public $data = array();
	
	
	public function __construct($data = array())
	{
		parent::__construct($data);
		
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
	
	
	public function latlng()
	{
		$lat = $this->r_lat();
		$lng = $this->r_lng();
		
		if ( ! $lat && ! $lng)
		{
			return FALSE;
		}
		else
		{
			return "{$lat},{$lng}";
		}
	}
	
	
	public function website_icon()
	{
		if ($this->r_url())
		{
			return icon_link('silk/world', $this->r_url(), 'Visit website', 'target="_blank"');
		}
		else
		{
			return '';
		}
	}
	
	
	public function edit_icon()
	{
		return icon_link('silk/edit', 'admin/railways/set/' . $this->r_id(), 'Edit');
	}
	
	
	public function delete_icon()
	{
		$attrs = array(
			'data-id' => $this->r_id(),
			'data-name' => $this->r_name(),
			'data-text' => 'Are you sure you want to remove this railway?
				All stations registered against this railway will also be removed.',
			'data-url' => site_url('admin/railways/delete'),
			'rel' => 'delete',
		);
		return icon_link('silk/delete', '#', 'Delete', $attrs);
	}
	
	
	public function public_url()
	{
		return site_url('railways/' . element('r_slug', $this->data));
	}
	
	
}