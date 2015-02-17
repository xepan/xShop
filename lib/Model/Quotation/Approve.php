<?php

namespace xShop;

class Model_Quotation_Approve extends Model_Quotation{

	function init(){
		parent::init();

		$this->addCondition('status','approved');

	}

	function creatOrder(){
		if(!$this->loaded())
			throw new \Exception("Model Must be Loaded before create Order",'Approved');
			
		$order = $this->add('xShop/Model_Order');
		if($order->placeOrderFromQuotation($this['id']));
			return "Order Created ";
			
		return "Something Gone Wrong";
	}

}