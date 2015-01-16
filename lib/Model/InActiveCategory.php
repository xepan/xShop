<?php

namespace xShop;
class Model_InActiveCategory extends Model_Category{
	function init(){
		parent::init();
		$this->addCondition('is_active',false);
	}
}