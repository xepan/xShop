<?php
namespace xShop;

class Model_Order_Draft extends Model_Order{
	function init(){
		parent::init();

		$this->addCondition('status','draft');
	}
}