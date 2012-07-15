<?php

class ROTA_Presenter
{
	
	
	private $_CI;
	
	public $data = array();
	
	
	public function __construct($data = array())
	{
		$this->data = $data;
		//$this->_CI =& get_instance();
	}
	
	
	/**
	 * Get a field value from the data array. Returns second param value on empty
	 *
	 * @param string $field		Field/key of the data to retrieve
	 * @param string $default		Value to return if $field value is empty or not present
	 * @return string		The value of the field requested or N/A if empty
	 */
	public function get($field, $default = '')
	{
		return element($field, $this->data, $default);
	}
	
	
	/**
	 * Magic method to just get the value, but return NULL if empty.
	 *
	 * Uses function so that specific functions can be added later without
	 * having to update calling code.
	 *
	 * e.g. $object->o_field()
	 */
	public function __call($name, $args)
	{
		return $this->get($name, NULL);
	}
	
	
}