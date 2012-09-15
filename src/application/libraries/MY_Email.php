<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Email extends CI_Email {
	
	private $_CI;
	private $_subject_plain;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->_CI =& get_instance();
	}
	
	
	/**
	 * Keep a plain-text representation of the subject set without encoding
	 */
	public function subject($subject = '')
	{
		parent::subject($subject);
		$this->_subject_plain = $subject;
	}
	
	
	/**
	 * Send message as usual but keep a record of it
	 */
	public function send()
	{
		$data = array(
			'm_rcpt_to' => $this->_recipients,
			'm_subject' => $this->_subject_plain,
			'm_headers' => $this->_header_str,
			'm_body' => $this->_body,
			'm_datetime' => date('Y-m-d H:i:s'),
			'm_sent' => 0,
			'm_debug' => '',
		);
		
		$response = parent::send();
		if ($response)
		{
			$data['m_sent'] = 1;
		}
		
		if (count($this->_debug_msg) > 0)
		{
			foreach ($this->_debug_msg as $val)
			{
				$data['m_debug'] .= $val;
			}
		}
		
		$this->_CI->load->model('messages_model');
		$this->_CI->messages_model->insert($data);
		
		return $response;
		
	}
	
	
}