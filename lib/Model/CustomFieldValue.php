<?php

namespace xShop;

class Model_CustomFieldValue extends \Model_Table{
	public $table='xshop_custom_fields_value';

	function init(){
		parent::init();
		
		//TODO for Mutiple Epan website
		// $this->addCondition('epan_id',$this->api->current_website->id);
			
		$this->hasOne('xShop/CategoryItemCustomFields','itemcustomfiledasso_id');
		$this->hasOne('xShop/CustomFields','customefield_id');
		
		$this->addField('name');
		$this->addField('rate_effect');
		$this->addField('created_at')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));
		$this->addField('is_active')->type('boolean')->defaultValue(true)->sortable(true);

		$this->hasMany('xShop/ItemImages','customefieldvalue_id');
		$this->hasMany('xShop/CustomFieldValueFilterAssociation','customefieldvalue_id');
		$this->addHook('beforeSave',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$old_model = $this->add('xShop/Model_CustomFieldValue');
		
		$old_model->addCondition('itemcustomfiledasso_id',$this['itemcustomfiledasso_id'])
				->addCondition('name',$this['name'])
				->addCondition('id','<>',$this->id)
				->tryLoadAny();
		if($old_model->loaded()){
			throw $this->Exception('Custom Value Already Exist','ValidityCheck')->setField('name');
		}
	}

}