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

		$this->hasOne('xHR/Employee','created_by_id')->defaultValue($this->api->current_employee->id);

		$this->addField('name');
		$this->addField('quotation_no');
		$this->addField('status')->enum(array('draft','approved','redesign','submitted'))->defaultValue('draft');


		$this->hasMany('xShop/QuotationItem','quotation_id');

		$this->add('dynamic_model/Controller_AutoCreator');
		
		
	}

	function reject($message){
		$this['status']='redesign';
		$this->saveAs('xShop/Model_Quotation');
		return "reject";
	}
	

	function sendMail(){
		return "sendMail";
	}

	function status(){
		return $this['status'];
	}

}