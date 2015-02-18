<?php

class page_xShop_page_printorder extends Page{

	function init(){
		parent::init();
		$order_id = $this->api->StickyGET('order_id');
		$order=$this->add('xShop/Model_Order')->load($order_id);
		$print=$this->add('xShop/View_PrintOrder');
		$print->setModel($order);
        $crud->add('xHR/Controller_Acl');
		
	}
}
	
