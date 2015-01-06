<?php

namespace xShop;
class Model_ActiveCategory extends Model_Category{
	function init(){
		parent::init();
		$this->addCondition('is_active',true);
	}
}