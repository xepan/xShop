<?php

namespace xShop;

class Model_ItemAffiliateAssociation extends \SQL_Model{
	public $table='xshop_item_affiliate_ass';
	
	function init(){
		parent::init();

		$this->hasOne('xShop/Item','item_id');
		$this->hasOne('xShop/Affiliate','affiliate_id');

		$this->addField('is_visible_on_item_detail')->type('boolean')->defaultValue(true);
		
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}