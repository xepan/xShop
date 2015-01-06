<?php

class page_xShop_page_owner_order extends page_xShop_page_owner_main{

	function page_index(){
		

		$application_id=$this->api->recall('xshop_application_id');
		
		//Badges 
		$this->app->layout->add('xShop/View_Badges_OrderPage');
		

		$order_model = $this->add('xShop/Model_Order');
		//$order_model->addCondition('application_id',$application_id);	
		$order_model->setOrder('id','desc');
						
		$crud=$this->app->layout->add('CRUD');
		$crud->setModel($order_model);
		
		if($crud->isEditing()){	
			$parent_model = $crud->form->getElement('parent_id')->getModel();
			$parent_model->title_field='order_name';
			$parent_model->addCondition('application_id',$application_id);
		}
	}
}