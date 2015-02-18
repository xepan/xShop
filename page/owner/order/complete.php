<?php
class page_xShop_page_owner_order_complete extends page_xShop_page_owner_main{
	function init(){
		parent::init();

		$crud=$this->add('CRUD',array('grid_class'=>'xShop/Grid_Order'));
		$crud->setModel('xShop/Model_Order_Complete');
		$crud->add('xHR/Controller_Acl');
	}
}		