<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Railways on the Air
 * Copyright (C) 2011 Craig A Rodway <craig.rodway@gmail.com>
 *
 * Licensed under the Open Software License version 3.0
 * 
 * This source file is subject to the Open Software License (OSL 3.0) that is
 * bundled with this package in the files license.txt. It is also available 
 * through the world wide web at this URL:
 * http://opensource.org/licenses/OSL-3.0
 */

class Messages_model extends MY_Model
{
	
	
	protected $_table = 'messages';
	protected $_primary = 'm_id';
	
	protected $_filter_types = array(
		'where' => array('m_a_id', 'm_rcpt_to'),
		'like' => array('m_subject'),
	);
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	
	
}