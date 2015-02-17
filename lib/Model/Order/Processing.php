<?php
namespace xShop;

class Model_Order_Processing extends Model_Order{
	function init(){
		parent::init();

		$this->addCondition('status','processing');
	}
}