<?php

class page_xShop_page_owner_item_seo extends page_xShop_page_owner_main{
	function init(){
		parent::init();

		if(!$_GET['item_id'])
			return;
		$item_id = $this->api->stickyGET('item_id'); 
		$form = $this->add('Form');
		$form->setModel($this->add('xShop/Model_Item')->load($item_id),array('meta_title','meta_description','tags'));
		$form->addSubmit()->set('Update');
		// $form->add('Controller_FormBeautifier');
		
		if($form->isSubmitted()){
			$form->update();
			$form->js()->univ()->successMessage('Information Updtaed')->execute();
		}
	}
}		