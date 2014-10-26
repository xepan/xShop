<?php

namespace xShop;

class Model_Attachments extends \Model_Table{
	public $table='xshop_attachments';

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);	
		$this->hasOne('xShop/Product','product_id');
		
		$this->addField('name')->mandatory(true);
		$this->addField('attachment_url')->display(array('form'=>'ElImage'))->mandatory(true);

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}

