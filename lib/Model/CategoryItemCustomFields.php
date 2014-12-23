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

		$this->addField('created_at')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));
		$this->addField('is_allowed')->type('boolean')->defaultValue(false)->sortable(true);

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}