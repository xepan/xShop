<?php

class page_xShop_page_owner_item_basic extends page_xShop_page_owner_main{
	function init(){
		parent::init();
		
		if(!$_GET['item_id'])
			return;
		
		$this->api->stickyGET('item_id');
		$selected_item_model = $this->add('xShop/Model_Item')->load($_GET['item_id']);		
		if(!$selected_item_model->loaded())
			return;
		
		$form = $this->add('Form_Stacked');
		$form->setModel($selected_item_model,array('name','sku','is_publish','is_party_publish','short_description','rank_weight','created_at','expiry_date','is_saleable','is_downloadable','is_designable','is_enquiry_allow','is_template','show_detail','show_price','new','feature','latest','mostviewed','is_visible_sold','offer_id','offer_position','allow_comments','comment_api','add_custom_button','custom_button_label','custom_button_url','reference','theme_code','description'));
		$form->addSubmit()->set('Update');

		$form->add('Controller_FormBeautifier');
		if($form->isSubmitted()){	
			$form->update();
			$form->js()->univ()->successMessage('Item Updtaed')->execute();
		}


	}
}