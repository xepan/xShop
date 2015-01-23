<?php

namespace xShop;

class Model_CustomFields extends \Model_Table{
	public $table='xshop_custom_fields';

	function init(){
		parent::init();
		
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		$this->hasOne('xShop/Application','application_id');

		$f = $this->addField('name')->mandatory(true)->group('a~6~<i class=\'fa fa-cog\'> Item Custom Fields</i>')->mandatory(true);
		$f->icon = 'fa fa-circle~blue';
		$this->addField('type')->enum(array('DropDown','Radio Button','Color','CheckBox'));
		$f->icon = 'fa fa-circle~red';
		
		$this->hasMany('xShop/CustomFieldValue','customfield_id');
		$this->hasMany('xShop/CustomFieldValueFilterAssociation','customefield_id');
		$this->hasMany('xShop/CategoryItemCustomFields','customfield_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}

}

