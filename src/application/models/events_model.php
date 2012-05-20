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

class Events_model extends CI_Model
{
	
	
	public $lasterr;
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	
	
	/**
	 * Get all of the events
	 */
	function get_all()
	{
		$sql = "SELECT
					events.*,
					IF(CURDATE() > e_end_date, 1, 0) AS 'e_should_be_current',
					(SELECT COUNT(s_o_id) FROM stations WHERE s_e_id = e_id) AS e_stations_count
				FROM events
				ORDER BY e_year DESC";
		
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}
	
	
	
	
}

/* End of file: application/models/events_model.php */