<?php

namespace xShop;

class Model_CustomFieldValueFilterAssociation extends \SQL_Model{
	public $table='xshop_customfiledvalue_filter_ass';
	
	function init(){
		parent::init();
				
		$this->hasOne('xShop/CustomFields','customfield_id');
		$this->addField('name');
		
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}