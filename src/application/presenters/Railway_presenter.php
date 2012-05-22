<?php

require_once(APPPATH . 'presenters/ROTA_Presenter.php');

class Railway_presenter extends ROTA_Presenter
{
	
	
	private $_CI;
	
	public $data = array();
	
	
	public function __construct($data)
	{
		parent::__construct($data);
	}
	
	
	public function latlng()
	{
		return $this->data['r_lat'] . ',' . $this->data['r_lng'];
	}
	
	
}