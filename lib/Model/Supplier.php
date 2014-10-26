<?php

namespace xShop;
class Model_Supplier extends \Model_Table {
	var $table= "xShop_supplier";
	function init(){
		parent::init();

		//TODO for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		
		$this->addField('name')->mandatory(true);
		$this->addField('email_id');
		$this->addField('phone_no')->type('number');
		$this->addField('mobile_no')->type('number');
		$this->addField('address')->type('text');
		$this->addField('office_address')->type('text');
		$this->addField('zip_code')->caption('Zip/postal code');
		$this->addField('city');
		$this->addField('state');
		$this->addField('country');
		$this->addField('description')->type('text')->display(array('form'=>'RichText'));
		$this->addField('is_active')->type('boolean');

		$this->hasMany('xShop/Product','supplier_id');
		
		// $this->add('dynamic_model/Controller_AutoCreator');

	}
}
