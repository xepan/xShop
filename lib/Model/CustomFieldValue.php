<?php

namespace xShop;

class Model_CustomFieldValue extends \Model_Table{
	public $table='xshop_custom_fields_value';
	public $title_field ='field_name_with_value';

	function init(){
		parent::init();
		
		//TODO for Mutiple Epan website
		// $this->addCondition('epan_id',$this->api->current_website->id);
			
		$this->hasOne('xShop/ItemCustomFieldAssos','itemcustomfiledasso_id');
		$this->hasOne('xShop/CustomFields','customfield_id');
		$this->hasOne('xShop/Item','item_id');
		
		$this->addField('name'); // actually ... its value
		// $this->addField('rate_effect');
		$this->addField('created_at')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));
		$this->addField('is_active')->type('boolean')->defaultValue(true)->sortable(true);

		$this->addExpression('field_name_with_value')->set(function($m,$q){
			$custome_field_m = $m->add('xShop/Model_CustomFields',array('table_alias'=>'x'));
			$custome_field_m->addCondition('id',$q->getField('customfield_id'));

			return "(concat((".$custome_field_m->_dsql()->fieldQuery('name')->render()."),' :: ',".$q->getField('name')."))";
		});

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

		// $temp = $this->add('xShop/Model_ItemCustomFieldAssos')->load($this['itemcustomfiledasso_id']);
		$this['customfield_id'] = $this->ref('itemcustomfiledasso_id')->get('customfield_id');
		$this['item_id'] = $this->ref('itemcustomfiledasso_id')->get('item_id');
	}

	function duplicate($asso_id,$item_id=null){
		$new_custom_value = $this->add('xShop/Model_CustomFieldValue');
		$new_custom_value['itemcustomfiledasso_id'] = $asso_id;
		$new_custom_value['customefield_id'] = $this['customefield_id'];
		$new_custom_value['name'] = $this['name'];
		// $new_custom_value['rate_effect'] = $this['rate_effect'];
		$new_custom_value['is_active'] = $this['is_active'];
		$new_custom_value->save();

		//filter Value
		$filter = $this->ref('xShop/CustomFieldValueFilterAssociation');
		if($filter->count()->getOne()){
			$filter->duplicate($item_id,$new_custom_value['id']);
		}

		$images= $this->ref('xShop/ItemImages');
		if($images->count()->getOne()){
			$images->duplicate($item_id,$new_custom_value['id']);
		}
		return $new_custom_value;
	}

}