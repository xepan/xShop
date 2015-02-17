<?php

namespace xShop;

class Model_Quotation_Draft extends Model_Quotation{

	function init(){
		parent::init();

		$this->addCondition('status','draft');
	}

	function submit(){
		$this['status'] = 'submitted';
		$this->saveAndUnload();
		return "Sended For Approval";
	}
}