<?php

class page_xShop_page_item_basic extends Page{
	function init(){
		parent::init();
		
		if(!$_GET['item_id'])
			return;
		
		$this->api->stickyGET('item_id');
		$selected_item_model = $this->add('xShop/Model_Item')->load($_GET['item_id']);		
		if(!$selected_item_model->loaded())
			return;
		
		$form = $this->add('Form');
		$form->setModel($selected_item_model);
		$form->addSubmit()->set('Update');

		if($form->isSubmitted()){	
			$form->update();
			$form->js()->univ()->successMessage('Item Updtaed')->execute();
		}

	}
}