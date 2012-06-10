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

class Events_model extends MY_Model
{
	
	
	protected $_table = 'events';
	protected $_primary = 'e_year';
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	
	
	public function get_current()
	{
		$this->limit(1);
		return $this->get_by('e_current', 'Y');
	}
	
	
	
	public function set_current()
	{
		$sql = 'UPDATE events SET e_current = "N"';
		$this->db->query($sql);
		
		$sql = 'UPDATE
					events cur
				JOIN
					events prev
						ON (YEAR(prev.e_end_date) = YEAR(cur.e_end_date)-1)
				SET
					cur.e_current = "Y"
				WHERE
					CURDATE()
						BETWEEN prev.e_end_date + INTERVAL 1 DAY 
						AND cur.e_end_date';
		return $this->db->query($sql);
	}
	
	
	
	
}

/* End of file: application/models/events_model.php */