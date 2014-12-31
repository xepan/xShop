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
		$f->icon = 'fa fa-circle~red';
		$f = $this->addField('value')->group('a~6')->mandatory(true)->hint('Comma Separated Value like red,blue,green,etc..');
		$f->icon = 'fa fa-circle~blue';
		$this->addField('type')->enum(array('DropDown','Radio Button','Color'));
		$this->hasMany('xShop/CustomFieldValue','customefield_id');

		$this->hasMany('xShop/CategoryItemCustomFields','customfield_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function createNew($category_id=false,$name,$value,$item_id=flase){
		
	}

}

