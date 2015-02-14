<?php
namespace xShop;

class Model_Order_Shipping extends Model_Order{
	function init(){
		parent::init();

		$this->addCondition('status','shipping');
	}
}