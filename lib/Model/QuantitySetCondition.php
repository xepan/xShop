<?php

namespace xShop;

class Model_QuantitySetCondition extends \Model_Table{
	public $table = 'xshop_item_quantity_set_conditions';

	function init(){
		parent::init();

		$this->hasOne('xShop/QuantitySet','quantityset_id');
		$this->hasOne('xShop/Model_CustomFieldValue','custom_field_value_id');
		// $this->addField('name'); // To give special name to a quantity Set Conditions.. leave empty to have qty value here too
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}