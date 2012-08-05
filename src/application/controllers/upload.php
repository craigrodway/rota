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

class Upload extends ShackController
{
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	
	
	public function image()
	{
		// Try to handle the image upload
		$config['upload_path'] = realpath(FCPATH . '../../storage/images');
		$config['encrypt_name'] = TRUE;
		$config['allowed_types'] = 'jpg';
		$config['max_size']	= '3072';
		$config['max_width']  = '3000';
		$config['max_height']  = '2000';

		if ($this->input->get('qqfile'))
		{
			$this->load->library('upload', $config);
			if ($this->upload->do_upload())
			{
				$res = array(
					'status' => 'ok',
					'success' => TRUE,
					'data' => $this->upload->data(),
				);
				
				// Success! Process image
				$this->load->library('photo');
				$i_id = $this->photo->add_image($res['data']['file_name']);
				
				if ($i_id)
				{
					$res['i_id'] = $i_id;
				}
				else
				{
					$res['status'] = 'err';
					$res['msg'] = $this->photo->lasterr;
				}
			}
			else
			{
				$res = array(
					'status' => 'err',
					'msg' => strip_tags($this->upload->display_errors()),
				);
			}
		}
		else
		{
			$res = array(
				'status' => 'err',
				'msg' => 'No file to upload',
			);
		}
		
		$this->json = $res;

	}
	
	
	
	
}

/* End of file: application/controllers/upload.php */