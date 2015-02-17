<?php

namespace xShop;

class Model_Quotation_Submit extends Model_Quotation{

	function init(){
		parent::init();

		$this->addCondition('status','submitted');
	}

	function approve(){
		$this['status'] = 'approved';
		$this->saveAndUnload();
		return "Quotation Approved";
	}

	function redesign(){
		$this['status'] = 'redesign';
		$this->saveAndUnload();
		return "Quotation Approved";
	}
}