<?php

require_once(APPPATH . 'presenters/ROTA_Presenter.php');

class Account_presenter extends ROTA_Presenter
{
	
	
	private $_CI;
	
	public $data = array();
	
	
	public function __construct($data = array())
	{
		parent::__construct($data);
	}
	
	
	public function a_created($format = 'd/m/Y H:i:s')
	{
		return date($format, strtotime(element('a_created', $this->data)));
	}
	
	
	public function a_lastlogin($format = 'd/m/Y H:i:s', $never = 'Never')
	{
		$date = element('a_lastlogin', $this->data, NULL);
		return ($date == NULL) ? $never : date($format, strtotime($date));
	}
	
	
	public function a_enabled($format = 'boolean')
	{
		$enabled = (element('a_enabled', $this->data, 'N') == 'Y');
		
		switch ($format)
		{
			case 'boolean':
				return $enabled;
			break;
			
			case 'icon':
				return ($enabled)
					? '<img src="img/global/icons/silk/bullet_tick.png" alt="Enabled">'
					: '<img src="img/global/icons/silk/bullet_cross.png" alt="Disabled">';
			break;
		}
	}
	
	
	public function verified($format = 'boolean')
	{
		$verified = (element('a_verify', $this->data, NULL) == NULL);
		
		switch ($format)
		{
			case 'boolean':
				return $verified;
			break;
			
			case 'icon':
				return ($verified)
					? '<img src="img/global/icons/silk/bullet_tick.png" alt="Verified">'
					: '<img src="img/global/icons/silk/bullet_cross.png" alt="Waiting verification">';
			break;
		}
	}
	
	
	public function a_type($format = 'icon')
	{
		$type = element('a_type', $this->data);
		switch ($format)
		{
			case 'text':
				return $type;
			break;
			
			case 'html':
				$label = ucfirst($type);
				$types = array(
					'member' => 'blue',
					'admin' => 'red',
				);
				
				$class = element($type, $types, 'black');
				
				return '<span class="label ' . $class . '">' . $label . '</span>';
			break;
			
			case 'icon':
				$types = array(
					'member' => 'img/global/icons/pid/person.png',
					'admin' => 'img/global/icons/pid/admin.png',
				);
				
				$img = element($type, $types, NULL);
				
				return ($img !== NULL)
					? '<img src="' . $img . '" alt="Account type: ' . $type . '">'
					: '';
			break;
		}
	}
	
	
	public function edit_icon()
	{
		return icon_link('silk/edit', 'admin/accounts/set/' . $this->a_id(), 'Edit');
	}
	
	
	public function delete_icon()
	{
		$attrs = array(
			'data-id' => $this->a_id(),
			'data-name' => $this->a_email(),
			'data-text' => 'Are you sure you want to remove this account?
				All operator profiles and event registrations will also be removed.',
			'data-url' => site_url('admin/accounts/delete'),
			'rel' => 'delete',
		);
		return icon_link('silk/delete', '#', 'Delete', $attrs);
	}
	
	
}