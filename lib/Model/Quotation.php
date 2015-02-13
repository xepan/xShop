<?php

namespace xShop;

class Model_Quotation extends \Model_Table{
	public $table="xshop_quotation";

	function init(){
		parent::init();
		$this->hasOne('xMarketingCampaign/Lead','lead_id');
		$this->hasOne('xShop/Oppertunity','oppertunity_id');
		$this->hasOne('xShop/Customer','customer_id');
		$this->hasOne('xShop/TermsAndCondition','termsandcondition_id');

		$this->addField('name');
		$this->addField('status')->enum(array('draft','approved','redesign','submit'))->defaultValue('draft');

		$this->hasMany('xShop/QuotationItem','quotation_id');

		// $this->addExpression('name')->set(function($m,$q){
		// 	return $m->refSQL('xShop/QuotationItem')->count();
		// });

		
		
		$this->add('dynamic_model/Controller_AutoCreator');
		
		
	}

	function reject($message){
		return "reject";

	}
	function approved(){
		return "approved";
	}

	function sendMail(){
		return "sendMail";
	}

}