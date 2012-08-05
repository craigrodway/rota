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

class News_model extends MY_Model
{
	
	
	protected $_table = 'news';
	protected $_primary = 'n_id';
	
	protected $_filter_types = array(
		'where' => array('n_e_year', 'n_r_id', 'n_o_id'),
		'like' => array('o_name', 'o_callsign', 'r_name', 'n_title'),
	);
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	
	
	public function get_all()
	{
		$sql = 'SELECT *
				FROM `' . $this->_table . '`
				LEFT JOIN events ON n_e_year = e_year
				LEFT JOIN operators ON n_o_id = o_id
				LEFT JOIN railways ON n_r_id = r_id
				WHERE 1 = 1 ' .
				$this->filter_sql() .
				$this->order_sql() .
				$this->limit_sql();
		
		$news = $this->db->query($sql)->result_array();
		
		foreach ($news as &$n)
		{
			$n['images'] = $this->images_model->news($n['n_id']);
		}
		
		return $news;
	}
	
	
	
	
	public function get($id)
	{
		$news = parent::get($id);
		$this->images_model->order_by('i_datetime_uploaded', 'desc');
		$news['images'] = $this->images_model->news($news['n_id']);
		return $news;
	}
	
	
	
	
	public function get_by($key, $value)
	{
		$sql = 'SELECT *
				FROM `' . $this->_table . '`
				LEFT JOIN events ON n_e_year = e_year
				LEFT JOIN operators ON n_o_id = o_id
				LEFT JOIN railways ON n_r_id = r_id
				WHERE `' . $key .'` = ?' .
				$this->filter_sql() .
				$this->order_sql() .
				$this->limit_sql();
		
		$query = $this->db->query($sql, array($value));
		
		$this->images_model->order_by('i_datetime_uploaded', 'desc');
		
		if ($this->_limit === 1)
		{
			$row = $query->row_array();
			$row['images'] = $this->images_model->news($row['n_id']);
			return $row;
		}
		else
		{
			$result = $query->result_array();
			foreach ($result as &$row)
			{
				$row['images'] = $this->images_model->news($row['n_id']);
			}
			return $result;
		}
	}
	
	
	
	
	public function add_image($n_id, $i_id)
	{
		$data = array(
			'ni_n_id' => $n_id,
			'ni_i_id' => $i_id,
		);
		
		$sql = $this->db->insert_string('news_images', $data);
		return $this->db->query($sql);
	}
	
	
	
	
	public function remove_image($n_id, $i_id)
	{
		$sql = 'DELETE FROM news_images WHERE ni_n_id = ? AND ni_i_id = ? LIMIT 1';
		return $this->db->query($sql, array($n_id, $i_id));
	}
	
	
	
	
}

/* End of file: application/models/news_model.php */