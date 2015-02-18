<?php

namespace xShop;

class Model_CustomRate extends \Model_Table {
	public $table ='xshop_item_custom_rates';
	
	function init(){
		parent::init();

		$this->hasOne('xShop/Item','item_id');
		$this->addField('name');
		$this->addField('qty');
		$this->addField('price');

		$this->hasMany('xShop/CustomRateCustomeValueCondition','custom_rate_id');

		$this->add('Controller_Validator');
		$this->is(array(
						     'name|to_trim|required',
    						 'qty|to_trim|required',
    						 'price|to_trim|required'

		));

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}