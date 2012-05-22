<?php

class ROTA_Presenter
{
	
	
	private $_CI;
	
	public $data = array();
	
	
	public function __construct($data)
	{
		$this->data = $data;
		$this->_CI =& get_instance();
	}
	
	
	/**
	 * Get a field value from the data array. Returns N/A (or supplied value) on empty
	 *
	 * @param string $field		Field/key of the data to retrieve
	 * @param string $default		Value to return if $field value is empty or not present
	 * @return string		The value of the field requested or N/A if empty
	 */
	public function get($field, $default = '')
	{
		return element($field, $this->data, $default);
	}	
	
	
}