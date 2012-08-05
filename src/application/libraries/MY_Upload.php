<?php

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

class MY_Upload extends CI_Upload
{
	
	
	public function __construct($props = array())
	{
		parent::__construct($props);
	}
	
	
	
	
	/**
	 * override do_upload() to allow uploads via XHR
	 */
	public function do_upload($field = 'userfile')
	{
		if (isset($_GET['qqfile']))
		{
			// Do custom stuff. But first, some checking that our parent does.
			
			// Is the upload path valid?
			if ( ! $this->validate_upload_path())
			{
				// errors will already be set by validate_upload_path() so just return FALSE
				return FALSE;
			}
			
			// Handle to the file being sent
			$input = fopen("php://input", "r");
			
			// Temporary file to save the upload data to
			$temp_file_name = tempnam(sys_get_temp_dir(), 'rota');
			$temp_handle = fopen($temp_file_name, 'w+');
			
			log_message('debug', "MY_Upload: do_upload(): Temp file name: $temp_file_name.");
			
			if ( ! $temp_handle)
			{
				// temp directory error
				$this->set_error('upload_no_temp_directory');
				return FALSE;
			}
			
			// Get the data
			$real_size = stream_copy_to_stream($input, $temp_handle);
			fclose($input);
			
			log_message('debug', "MY_Upload: do_upload(): File size of uploaded file: $real_size");
			
			// Check the sent size is the actual size of the content
			if ( (int) $_SERVER['CONTENT_LENGTH'] !== $real_size)
			{
				// Uh-oh. Mis-matched size.
				$this->set_error('upload_unable_to_write_file');
				return FALSE;
			}
			
			// Set the uploaded data as class variables
			$this->file_temp = $temp_file_name;
			$this->file_size = $real_size;
			$this->_file_mime_type(array('tmp_name' => $temp_file_name, 'tmp_path' => sys_get_temp_dir()));
			$this->file_type = preg_replace("/^(.+?);.*$/", "\\1", $this->file_type);
			$this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));
			$this->file_name = $this->_prep_filename($_GET['qqfile']);
			$this->file_ext	 = $this->get_extension($this->file_name);
			$this->client_name = $this->file_name;
			
			// Is the file type allowed to be uploaded?
			if ( ! $this->is_allowed_filetype())
			{
				$this->set_error('upload_invalid_filetype');
				return FALSE;
			}
			
			// if we're overriding, let's now make sure the new name and type is allowed
			if ($this->_file_name_override != '')
			{
				$this->file_name = $this->_prep_filename($this->_file_name_override);

				// If no extension was provided in the file_name config item, use the uploaded one
				if (strpos($this->_file_name_override, '.') === FALSE)
				{
					$this->file_name .= $this->file_ext;
				}

				// An extension was provided, lets have it!
				else
				{
					$this->file_ext	 = $this->get_extension($this->_file_name_override);
				}

				if ( ! $this->is_allowed_filetype(TRUE))
				{
					$this->set_error('upload_invalid_filetype');
					return FALSE;
				}
			}
			
			// Convert the file size to kilobytes
			if ($this->file_size > 0)
			{
				$this->file_size = round($this->file_size/1024, 2);
			}

			// Is the file size within the allowed maximum?
			if ( ! $this->is_allowed_filesize())
			{
				$this->set_error('upload_invalid_filesize');
				return FALSE;
			}
			
			// Are the image dimensions within the allowed size?
			// Note: This can fail if the server has an open_basdir restriction.
			if ( ! $this->is_allowed_dimensions())
			{
				$this->set_error('upload_invalid_dimensions');
				return FALSE;
			}
			
			// Sanitize the file name for security
			$this->file_name = $this->clean_file_name($this->file_name);

			// Truncate the file name if it's too long
			if ($this->max_filename > 0)
			{
				$this->file_name = $this->limit_filename_length($this->file_name, $this->max_filename);
			}

			// Remove white spaces in the name
			if ($this->remove_spaces == TRUE)
			{
				$this->file_name = preg_replace("/\s+/", "_", $this->file_name);
			}
			
			/*
			 * Validate the file name
			 * This function appends an number onto the end of
			 * the file if one with the same name already exists.
			 * If it returns false there was a problem.
			 */
			$this->orig_name = $this->file_name;

			if ($this->overwrite == FALSE)
			{
				$this->file_name = $this->set_filename($this->upload_path, $this->file_name);

				if ($this->file_name === FALSE)
				{
					return FALSE;
				}
			}
			
			/*
			 * Run the file through the XSS hacking filter
			 * This helps prevent malicious code from being
			 * embedded within a file.  Scripts can easily
			 * be disguised as images or other file types.
			 */
			if ($this->xss_clean)
			{
				if ($this->do_xss_clean() === FALSE)
				{
					$this->set_error('upload_unable_to_write_file');
					return FALSE;
				}
			}
			
			// Move the file to the final destination
			
			log_message('debug', "MY_Upload: do_upload(): Going to open {$this->upload_path}{$this->file_name} for writing.");
			
			$target = @fopen($this->upload_path.$this->file_name, "w");
			if ( ! $target)
			{
				log_message('debug', "MY_Upload: do_upload(): Failed.");
				fclose($temp_handle);
				@unlink($temp_file_name);
				$this->set_error('upload_destination_error');
				return FALSE;
			}
			
			fseek($temp_handle, 0, SEEK_SET);
			
			if ( ! @stream_copy_to_stream($temp_handle, $target))
			{
				log_message('debug', "MY_Upload: do_upload(): Failed to copy temporary file to target.");
				fclose($temp_handle);
				@unlink($temp_file_name);
				$this->set_error('upload_destination_error');
				return FALSE;
			}
			
			// All succeeded!
			
			// Close the destination file handle
			fclose($target);
			
			// Close the temp file and remove it
			fclose($temp_handle);
			@unlink($temp_file_name);
			
			/*
			 * Set the finalized image dimensions
			 * This sets the image width/height (assuming the
			 * file was an image).  We use this information
			 * in the "data" function.
			 */
			$this->set_image_properties($this->upload_path.$this->file_name);
			
			return TRUE;
		}
		else
		{
			// Not via AJAX - use parent upload for normal file upload
			return parent::do_upload($field);
		}
	}
	
	
	
	
}

/* End of file: application/libraries/MY_Upload.php */