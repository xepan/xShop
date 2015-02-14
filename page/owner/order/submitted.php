<?php
class page_xShop_page_owner_order_submitted extends page_xShop_page_owner_main{
	function init(){
		parent::init();

		$crud=$this->add('CRUD',array('grid_class'=>'xShop/Grid_Order','allow_add'=>false,'allow_edit'=>false,'allow_del'=>false));
		$crud->setModel('xShop/Model_Order_Submitted');

		$crud->addAction('approve'); // as per acl
		$crud->addAction('redesign'); // as per acl
		$crud->addAction('process_now');
	}
}		