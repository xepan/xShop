<?php

namespace xShop;

class Model_ItemDepartmentAssociation extends \Sql_Model{
	public $table='xshop_item_department_asso';

	function init(){
		parent::init();
		
		$this->hasOne('xShop/Item','item_id');
		$this->hasOne('xHR/Department','department_id');
		$this->addField('is_active')->type('boolean')->defaultValue(true);

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}