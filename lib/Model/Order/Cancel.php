<?php
namespace xShop;

class Model_Order_Cancel extends Model_Order{
	function init(){
		parent::init();

		$this->addCondition('status','cancel');
	}
}