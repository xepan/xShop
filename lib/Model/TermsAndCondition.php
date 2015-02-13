<?php

namespace xShop;
class Model_TermsAndCondition extends \Model_Table{
	public $table="xshop_termsandcondition";
	function init(){
		parent::init();
		
		$this->addField('name');
		$this->hasMany('xShop/Quotation','termsandcondition_id');

		$this->add('dynamic_model/Controller_AutoCreator');

	}
}