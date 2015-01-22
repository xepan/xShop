<?php

class page_xShop_page_owner_item_basic extends Page{
	function init(){
		parent::init();
		
		if(!$_GET['item_id'])
			return;
		
		$this->api->stickyGET('item_id');
		$selected_item_model = $this->add('xShop/Model_Item')->load($_GET['item_id']);		
		if(!$selected_item_model->loaded())
			return;
		
		$form = $this->add('Form_Stacked');
		$form->setModel($selected_item_model,array('name','sku','is_publish','is_party_publish','original_price','sale_price','short_description','rank_weight','created_at','expiry_date','minimum_order_qty','maximum_order_qty','qty_unit','qty_from_set_only','description','is_saleable','is_downloadable','is_designable','is_enquiry_allow','is_template','negative_qty_allowed','is_visible_sold','offer','offer_position','show_detail','show_price','new','feature','latest','mostviewed','enquiry_send_to_admin','item_enquiry_auto_reply','allow_comments','comment_api','meta_title','meta_description','custom_button_url','tags','reference','theme_code'));
		$form->addSubmit()->set('Update');

		if($form->isSubmitted()){	
			$form->update();
			$form->js()->univ()->successMessage('Item Updtaed')->execute();
		}

		$form->add('Controller_FormBeautifier');

	}
}