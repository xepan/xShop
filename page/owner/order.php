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
		if($_GET['print']){			
			$this->js()->univ()->newWindow($this->api->url("xShop/page_printorder",array('order_id'=>$_GET['print'],'cut_page'=>1,'subpage'=>'xshop-junk')),null,'height=689,width=1246,scrollbar=1')->execute();
		} 
		if(!$crud->isEditing()){
			$crud->grid->addQuickSearch(array('member','order_id','amount','billing_address','shipping_address','order_date'));
			$crud->grid->addPaginator($ipp=50);
         	 $crud->grid->addColumn('expander','detail');
         	 $crud->grid->addColumn('button','print');
		}

    }

    function page_detail(){
    	$crud = $this->add('CRUD');
        $order_id = $this->api->stickyGET('xshop_orders_id');
        $order_detail=$this->add('xShop/Model_OrderDetails');
        $order_detail->addCondition('order_id',$order_id);
        $crud->setModel($order_detail);
	} 

}