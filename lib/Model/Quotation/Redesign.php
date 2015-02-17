<?php

namespace xShop;

class Model_Quotation_Redesign extends Model_Quotation{

	function init(){
		parent::init();
		
		$this->addCondition('status','redesign');

	}
}