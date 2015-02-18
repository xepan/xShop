<?php
namespace xShop;

class Model_Order_Submitted extends Model_Order{
	function init(){
		parent::init();

		$this->addCondition('status','submitted');
	}
}