<?php

namespace xShop;

class Model_AffiliateType extends \Model_Table {
	var $table= "xshop_affiliatetype";
	function init(){
		parent::init();

		//Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		$this->hasOne('xShop/Application','application_id');

		$this->addField('name');
		$this->hasMany('xShop/Affiliate','affiliatetype_id');
		$this->hasMany('xShop/ItemAffiliateAssociation','affiliatetype_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	
	}
}