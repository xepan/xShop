<?php

namespace xShop;

class Model_AddBlock extends \Model_Table{
	public $table='xshop_addblock';

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);	
		
		$this->addField('name')->caption('Block Name');
		$this->hasMany('xShop/BlockImages','block_id');
		
		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}