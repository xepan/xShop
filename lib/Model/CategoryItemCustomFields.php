<?php

namespace xShop;

class Model_CategoryItemCustomFields extends \Model_Table{
	public $table="xshop_category_item_customfields";

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		$this->hasOne('xShop/CustomFields','customfield_id');
		$this->hasOne('xShop/Category','category_id');
		$this->hasOne('xShop/Item','item_id');

		// $this->addField('rate_effect');
		$this->addField('created_at')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));
		$this->addField('is_active')->type('boolean')->defaultValue(true)->sortable(true);

		$this->hasMany('xShop/CustomFieldValue','itemcustomfiledasso_id');

		$this->addHook('beforeSave',$this);
		
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$old_model = $this->add('xShop/Model_CategoryItemCustomFields');
		
		$old_model->addCondition('item_id',$this['item_id'])
				->addCondition('customfield_id',$this['customfield_id'])
				->addCondition('id','<>',$this->id)
				->tryLoadAny();
		if($old_model->loaded()){
			throw $this->Exception('Custom Filed Exist','ValidityCheck')->setField('customfield_id');
		}

	}
		
}