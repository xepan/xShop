<?php

namespace xShop;

class Model_Oppertunity extends \Model_Table{
	public $table="xshop_oppertunity";
	function init(){
		parent::init();

		$this->hasOne('xMarketingCampaign/Lead','lead_id');
		// $this->hasOne('xShop/Customer','customer_id');
		$this->addField('name');

		$this->hasMany('xShop/Quotation','oppertunity_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function quotation(){
		return "Quotation";
	}
}