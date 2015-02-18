<?php
namespace xShop;
class Model_RedesignQuotation extends Model_Quotation{
	function init(){
		parent::init();

		$this->addCondition('status','redesign');
	}
}