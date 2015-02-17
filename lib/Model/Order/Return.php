<?php
namespace xShop;

class Model_Order_Return extends Model_Order{
	function init(){
		parent::init();

		$this->addCondition('status','return');
	}
}