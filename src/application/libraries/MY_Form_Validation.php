<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class MY_Form_Validation extends CI_Form_validation
{
	
	
	protected $CI;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
	}
	
	
	
	
	/**
	 * Clear the validation rules between runs of different sets of rules
	 */
	function clear_rules()
	{
		$this->_field_data  = array();
		$this->_error_array = array();
		$this->_error_messages = array();
		return $this;
	}
	
	
	
	/** 
	 * Check the database for an existing account using the provided email address
	 */
	public function duplicate_email($email)
	{
		$sql = 'SELECT a_id FROM accounts WHERE a_email = ?';
		$query = $this->CI->db->query($sql, array($email));
		parent::set_message('duplicate_email', 'Email address is already in use.');
		return !($query->num_rows() > 0);
	}
	
	
	
}