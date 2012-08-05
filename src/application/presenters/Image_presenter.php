<?php

require_once(APPPATH . 'presenters/ROTA_Presenter.php');

class Image_presenter extends ROTA_Presenter
{
	
	
	private $_CI;
	
	public $data = array();
	
	
	public function __construct($data = array())
	{
		parent::__construct($data);
	}
	
	
	public function src($variation = NULL)
	{
		if ($variation === NULL) return FALSE;
		$file_name = $this->data['i_file_base_name'] . '_' . $variation . '.' . $this->data['i_orig_file_ext'];
		$file_path = FCPATH . '/assets/uploads/' . $this->data['i_id'] . '/' . $file_name;
		$file_url = base_url() . 'assets/uploads/' . $this->data['i_id'] . '/' . $file_name;
		
		if ( ! file_exists($file_path))
		{
			$this->_CI =& get_instance();
			$this->_CI->load->library('photo');
			$this->_CI->photo->process_image($this->data['i_id'], $variation);
		}
		
		return $file_url;
	}
	
	
}