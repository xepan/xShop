<?php

namespace xShop;

class Model_CustomFieldValueFilterAssociation extends \SQL_Model{
	public $table='xshop_customfiledvalue_filter_ass';
	
	function init(){
		parent::init();
				
		$this->hasOne('xShop/Item','item_id');
		$this->hasOne('xShop/CustomFields','customfield_id');
		$this->hasOne('xShop/CustomFieldValue','customefieldvalue_id');
		$this->addField('name');
		
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function duplicate($item_id,$customefieldvalue_id){
		$new_filter_asso = $this->add('xShop/Model_CustomFieldValueFilterAssociation');
		$new_filter_asso['item_id'] = $item_id;
		$new_filter_asso['customfield_id'] = $this['customfield_id'];
		$new_filter_asso['customefieldvalue_id'] = $customefieldvalue_id;
		$new_filter_asso['name'] = $this['name'];
		$new_filter_asso->save();
		return $new_filter_asso;
	}
}