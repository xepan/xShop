<?php

namespace xShop;

class Model_QuantitySetCondition extends \Model_Table{
	public $table = 'xshop_item_quantity_set_conditions';

	function init(){
		parent::init();

		$this->hasOne('xShop/QuantitySet','quantityset_id');
		$this->hasOne('xShop/Model_CustomFieldValue','custom_field_value_id');
		// $this->addField('name'); // To give special name to a quantity Set Conditions.. leave empty to have qty value here too
		$this->addHook('beforeSave',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$old_model = $this->add('xShop/Model_QuantitySetCondition');		
		$old_model->addCondition('quantityset_id',$this['quantityset_id'])
				->addCondition('custom_field_value_id',$this['custom_field_value_id'])
				->addCondition('id','<>',$this->id)
				->tryLoadAny();
		if($old_model->loaded()){
			throw $this->Exception('Custom Value Already Exist','ValidityCheck')->setField('custom_field_value_id');
		}
	}
}