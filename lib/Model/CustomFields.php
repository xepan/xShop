<?php

namespace xShop;

class Model_CustomFields extends \Model_Table{
	public $table='xshop_custom_fields';

	function init(){
		parent::init();

		$this->hasOne('xShop/Product','product_id');
		//TODO for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		
		$this->addField('name');
		$this->addField('value');

		$this->hasMany('xShop/CustomFieldValue','customefield_id');

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}

