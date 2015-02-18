<?php
class page_xShop_page_owner_order_draft extends page_xShop_page_owner_main{
	function init(){
		parent::init();

		$crud=$this->add('CRUD',array('grid_class'=>'xShop/Grid_Order'));
		$crud->setModel('xShop/Model_Order_Draft');
		$crud->add('xHR/Controller_Acl');
		
		if(!$crud->isEditing()){
			$crud->grid->removeColumn('order_from');
		}else{
			$crud->form->add('View_Error')->set('Payment Advanced');
		}	
	}
}		