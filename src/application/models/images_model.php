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

class Images_model extends MY_Model
{
	
	
	protected $_table = 'images';
	protected $_primary = 'i_id';
	
	protected $_filter_types = array(
		'where' => array('i_id', 'i_a_id', 'i_e_year', 'i_r_id', 'i_n_id'),
	);
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	
	
}

/* End of file: application/models/images_model.php */