<?php

namespace xShop;

class Model_Configuration extends \Model_Table {
	var $table= "xshop_configuration";
	function init(){
		parent::init();

		//TODO for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		
		$this->addField('subject')->caption('Auto Reply Mail Subject');		
		$this->addField('message')->type('text')->display(array('form'=>'RichText'))->caption('Auto Reply Mail Message');		
		$this->addField('disqus_code')->type('text')->caption('Place the Disqus code')->PlaceHolder('Place your Disqus Code here..')->hint('Place your Discus code here'); 		
		$this->addField('add_custom_button')->type('boolean')->hint('Add Custom Button on All Product at Product Detail');
		$this->addField('custom_button_text')->hint('Add Custom Button Text');
		$this->addField('custom_button_url')->hint('Add Custom Button Url')->placeHolder('page Url like : registration, home etc.');
			
		$this->addField('order_detail_email_subject');
		$this->addField('order_detail_email_body')->type('text')->display(array('form'=>'RichText'))->caption('Order Detail Email Body')->hint('Order Placed Email Body');

		// TODO GROUP ACCESS of Category and other feature
		// $this->add('dynamic_model/Controller_AutoCreator');
	}
	
}
