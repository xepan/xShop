<?php

namespace xShop;

class Model_Quotation_Approve extends Model_Quotation{

	function init(){
		parent::init();

		$this->addCondition('status','approved');

	}
}