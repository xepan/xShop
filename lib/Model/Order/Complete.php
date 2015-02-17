<?php
namespace xShop;

class Model_Order_Complete extends Model_Order{
	function init(){
		parent::init();

		$this->addCondition('status','complete');
	}
}