<?php
namespace xShop;

class Model_Order_Approved extends Model_Order{
	function init(){
		parent::init();

		$this->addCondition('status','approved');
	}
}