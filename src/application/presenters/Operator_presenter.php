<?php

require_once(APPPATH . 'presenters/ROTA_Presenter.php');

class Operator_presenter extends ROTA_Presenter
{
	
	
	private $_CI;
	
	public $data = array();
	
	
	public function __construct($data = array())
	{
		parent::__construct($data);
	}
	
	
	public function o_type($format = 'icon', $img_tag = TRUE)
	{
		$type = element('o_type', $this->data);
		
		switch ($format)
		{
			
			case 'longtext':
				$types = array(
					'person' => 'Individual',
					'club' => 'Club or group',
				);
				return element($type, $types, NULL);
			break;
			
			case 'text':
				return $type;
			break;
			
			case 'html':
				$label = ucfirst($type);
				$types = array(
					'person' => 'blue',
					'club' => 'green',
				);
				
				$class = element($type, $types, 'black');
				
				return '<span class="label ' . $class . '">' . $label . '</span>';
			break;
			
			case 'icon':
				$types = array(
					'person' => 'img/global/icons/silk/person.png',
					'club' => 'img/global/icons/silk/club.png',
					/*'person' => 'img/global/icons/station-individual.png',
					'club' => 'img/global/icons/station-club.png',*/
				);
				
				$img = element($type, $types, NULL);
				
				if ($img_tag == FALSE && $img !== NULL)
				{
					return $img;
				}
				
				return ($img !== NULL)
					? '<img src="' . $img . '" alt="Operator type: ' . $type . '">'
					: '';
			break;
		}
	}
	
	
	public function stations_icon()
	{
		return icon_link('silk/transmit', 'admin/stations/?s_o_id=' . $this->o_id(), 'View stations');
	}
	
	
	public function account_icon()
	{
		return icon_link('silk/account', 'admin/accounts/set/' . $this->o_a_id(), 'Edit account');
	}
	
	
	public function edit_icon()
	{
		return icon_link('silk/edit', 'admin/operators/set/' . $this->o_id(), 'Edit');
	}
	
	
	public function delete_icon()
	{
		$attrs = array(
			'data-id' => $this->o_id(),
			'data-name' => $this->o_callsign(),
			'data-text' => 'Are you sure you want to remove this operator?
				All registrations for events will also be removed.',
			'data-url' => site_url('admin/operators/delete'),
			'rel' => 'delete',
		);
		return icon_link('silk/delete', '#', 'Delete', $attrs);
	}
	
	
}