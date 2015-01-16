<?php

namespace xShop;
class Model_ItemMemberDesign extends \Model_Table {
	var $table= "xshop_item_member_designs";
	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		$this->hasOne('xShop/Item','item_id');
		$this->hasOne('xShop/MemberDetails','member_id');
	
		$this->addField('last_modified')->type('date')->defaultValue(date('Y-m-d'));
		$this->addField('is_ordered')->type('boolean')->defaultValue(false);
		$this->addField('designs')->type('text');

		$this->addField('is_dummy')->type('boolean')->defaultValue(false)->system(true);

		$this->add('dynamic_model/Controller_AutoCreator');

		}

}