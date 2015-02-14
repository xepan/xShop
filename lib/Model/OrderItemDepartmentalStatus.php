<?php

namespace xShop;

class Model_OrderItemDepartmentalStatus extends \SQL_Model{
	public $table ="xshop_orderitem_departmental_status";

	function init(){
		parent::init();

		$this->hasOne('xShop/OrderDetails','orderitem_id');
		$this->hasOne('xHR/Department','department_id');
		$this->addField('status')->enum(array('-','received','approved','assigned','processing','processed','completed','forwarded'))->defaultValue('-');

		$this->add('dynamic_model/Controller_AutoCreator');
	}

}