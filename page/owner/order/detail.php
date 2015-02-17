<?php

class page_xShop_page_owner_order_detail extends page_xShop_page_owner_main{
    function init(){
		parent::init();
		
        $order_id = $this->api->stickyGET('xshop_orders_id');
        
        $order= $this->add('xShop/Model_Order')->load($order_id);

        $this->add('View')->set($order['member']);

        $crud_actions = array('form_class'=>'xShop/Form_OrderItem');
        if($order['status']!='draft'){
        	$crud_actions=array('form_class'=>'xShop/Form_OrderItem');
        }
            $crud_actions->add('xHR/Controller_Acl');
        $order_detail=$this->add('xShop/Model_OrderDetails');
        $order_detail->addCondition('order_id',$order_id);
		$crud = $this->add('CRUD',$crud_actions);
        $crud->setModel($order_detail);
        $crud->add('Controller_FormBeautifier');
	}
}