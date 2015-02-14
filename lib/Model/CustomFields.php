<?php

namespace xShop;

class Model_CustomFields extends \Model_Table{
	public $table='xshop_custom_fields';

	function init(){
		parent::init();
		
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		$this->hasOne('xShop/Application','application_id');

		$f = $this->addField('name')->mandatory(true)->group('a~6~<i class=\'fa fa-cog\'> Item Custom Fields</i>')->mandatory(true);
		$f->icon = 'fa fa-circle~blue';
		$this->addField('type')->enum(array('DropDown','Color'));//todo add radio box and checkbox ????????????
		$f->icon = 'fa fa-circle~red';
		
		$this->hasMany('xShop/CustomFieldValue','customfield_id');
		$this->hasMany('xShop/CustomFieldValueFilterAssociation','customefield_id');
		$this->hasMany('xShop/ItemCustomFieldAssos','customfield_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function getCustomValue($for_item_id){
		if(!$this->loaded())
			throw new \Exception("custom model must be loaded");
		$cf_value_array = array();
		/*
		values:[
					{value:9},
					{value:10},
					{
						value: 11,
						filters:{
							color: 'red' // This is filter
						}
					},
				]
		*/
		//Load Custom Field Value Model
		$cf_value_model = $this->ref('xShop/CustomFieldValue')->addCondition('item_id',$for_item_id)->addCondition('is_active',true);
			//for each of value model and get its name
			foreach ($cf_value_model as $junk){
				$one_value_array = array();
				// $one_value_array['value'] = $cf_value_model['name'];
				//load filter association model
				$filter_model = $this->add('xShop/Model_CustomFieldValueFilterAssociation');
				$filter_model->addCondition('customefieldvalue_id',$cf_value_model['id']);
				$count = $filter_model->tryLoadAny()->count()->getOne();
				// $one_value_array['customfield'] = $this['name'];
				// $one_value_array['customefieldvalue_id'] = $cf_value_model['id'];
				$one_value_array['filter_count'] = $count;
				//foreach filter and get filter value
				$filter_value_array = array();
				foreach ($filter_model as $filter){
					$filter_value_array[]=array($filter_model['customfield'] => $filter_model['name']);
				}
				$one_value_array['filters'] = $filter_value_array;
				$cf_value_array = array_replace($cf_value_array, array($cf_value_model['name']=>$one_value_array));
			}

		return $cf_value_array;
	}

}

