<?php

namespace xShop;

class Model_PaymentGateway extends \SQL_Model {
	public $table ="xshop_payment_gateways";

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		$this->addField('name');
		$this->addField('default_parameters')->type('text');
		$this->addField('parameters')->type('text');

		$this->addField('processing')->enum(array('OnSite','OffSite'));
		$this->addField('is_active')->type('boolean')->defaultValue(true);

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}