<?php
namespace xShop;

class Model_Order_Processed extends Model_Order{
	function init(){
		parent::init();

		$this->addCondition('status','processed');
	}
}