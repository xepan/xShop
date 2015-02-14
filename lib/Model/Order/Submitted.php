<?php
namespace xShop;

class Model_Order_Submitted extends Model_Order{
	function init(){
		parent::init();

		$this->addCondition('status','submitted');
	}

	function process_now(){
		// check conditions
		// pick first - status department and forward the (all) orders
		return "This Order requires Approval";
	}
}