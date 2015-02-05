<?php

namespace xShop;

class Model_CustomRateCustomeValueCondition extends \Model_Table {
	public $table="xshop_customrate_custome_value_conditions";

	function init(){
		parent::init();

		$this->hasOne('xShop/CustomRate','custom_rate_id');
		$this->hasOne('xShop/CustomFieldValue','custom_field_value_id');
		
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}