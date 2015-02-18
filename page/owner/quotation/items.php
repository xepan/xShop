<?php

class page_xShop_page_owner_quotation_items extends page_xShop_page_owner_main{

	function init(){
		parent::init();

		$quotation_id=$this->api->stickyGET('xshop_quotation_id');
		$item_model = $this->add('xShop/Model_QuotationItem');
		$item_model->addCondition('quotation_id',$quotation_id);
		$item_crud=$this->add('CRUD');
		$item_crud->setModel($item_model);
		$crud->add('xHR/Controller_Acl');

	}
}