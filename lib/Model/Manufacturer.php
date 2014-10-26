<?php

namespace xShop;
class Model_Manufacturer extends \Model_Table {
	var $table= "xshop_manufacturer";
	function init(){
		parent::init();

		//TODO for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		
		$this->addField('name')->caption('Company Name')->mandatory(true);
		$this->addField('office_address')->type('text')->mandatory(true);
		$this->addField('city');
		$this->addField('state');
		$this->addField('country');
		$this->addField('zip_code')->caption('Zip/postal code');
		$this->addField('logo_url')->display(array('form'=>'ElImage'));
		$this->addField('phone_no')->type('number');
		$this->addField('mobile_no')->type('number');
		$this->addField('email_id');
		$this->addField('website_url');
		$this->addField('description')->type('text')->display(array('form'=>'RichText'));
		$this->addField('is_active')->type('boolean')->defaultValue('true');

		$this->hasMany('xShop/Product','manufacturer_id');

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
	
}