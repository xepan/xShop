<?php

class page_xShop_page_owner_order_detail extends page_xShop_page_owner_main{
	function init(){
		parent::init();
		
        $order_id = $this->api->stickyGET('xshop_orders_id');
        
        $order= $this->add('xShop/Model_Order')->load($order_id);

        $this->add('View')->set($order['member']);

        $crud_actions = null;
        if($order['status']!='draft'){
        	$crud_actions=array('allow_add'=>false,'allow_del'=>false,'allow_edit'=>false);
        }

        $order_detail=$this->add('xShop/Model_OrderDetails');
        $order_detail->addCondition('order_id',$order_id);
		$crud = $this->add('CRUD',$crud_actions);
        $crud->setModel($order_detail);

	}
}