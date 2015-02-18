<?php
namespace xShop;
class Model_ApprovedQuotation extends Model_Quotation{
	function init(){
		parent::init();

		$this->addCondition('status','approved');
	}

	function creatOrder(){
		return "creatOrder";
	}
}