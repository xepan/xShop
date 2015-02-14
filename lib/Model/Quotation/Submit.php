<?php

namespace xShop;

class Model_Quotation_Submit extends Model_Quotation{

	function init(){
		parent::init();

		$this->addCondition('status','submit');
	}
}