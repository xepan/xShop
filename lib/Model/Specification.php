<?php

namespace xShop;

class Model_Specification extends \Model_Table{
	public $table='xshop_specifications';

	function init(){
		parent::init();
		
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		$this->hasOne('xShop/Application','application_id');

		$f = $this->addField('name')->mandatory(true)->group('a~6~<i class=\'fa fa-cog\'> Item Custom Fields</i>')->mandatory(true);
		$f->icon = 'fa fa-circle~blue';
			
		$this->hasMany('xShop/ItemSpecificationAssociation','specification_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}

