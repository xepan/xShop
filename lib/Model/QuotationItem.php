<?php

namespace xShop;
class Model_QuotationItem extends \Model_Table{
	public $table="xshop_quotation_item";
	function init(){
		parent::init();
		$this->hasOne('xShop/Quotation','quotation_id');
		$this->hasOne('xShop/Item','item_id');
		
		// $this->addField('name');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}