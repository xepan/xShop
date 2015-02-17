<?php
namespace xShop;
class View_Badges_OrderPage extends \View_BadgeGroup{
	
		function init(){
			parent::init();


			$total_orders =$this->add('xShop/Model_Order');
			$order=$total_orders->count()->getOne();
			$bg=$this->add('View_BadgeGroup');
			$v=$bg->add('View_Badge')->set('Total order')->setCount($order)->setCountSwatch('ink');


			$placed_order = $this->add('xShop/Model_Order');
			$placed = $placed_order->addCondition('status','submitted')->count()->getOne();
			$bg->add('View_Badge')->set('placed order')->setCount($placed)->setCountSwatch('ink');		
	


			// $order_placed_with_payament = $this->add('xShop/Model_Order');
			// $placed = $order_placed_with_payament->addCondition('order_status','20')->count()->getOne();
			// $bg->add('View_Badge')->set('placed order with Payment')->setCount($placed)->setCountSwatch('ink');		
			

			// $order_placed_with_COD = $this->add('xShop/Model_Order');
			// $placed = $order_placed_with_COD->addCondition('order_status','30')->count()->getOne();
			// $bg->add('View_Badge')->set('placed order with COD')->setCount($placed)->setCountSwatch('ink');		
	
			$order_shipping = $this->add('xShop/Model_Order');
			$placed = $order_shipping->addCondition('status','shipping')->count()->getOne();
			$bg->add('View_Badge')->set('shipping order')->setCount($placed)->setCountSwatch('ink');		
	
			// $order_shipped = $this->add('xShop/Model_Order');
			// $placed = $order_shipped->addCondition('status','50')->count()->getOne();
			// $bg->add('View_Badge')->set('shipped order')->setCount($placed)->setCountSwatch('ink');		
	

			}
}