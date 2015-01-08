<?php
namespace xShop;
class View_Badges_OrderPage extends \View_BadgeGroup{
	
		function init(){
			parent::init();


			$total_orders =$this->add('xShop/Model_Order');
			$order=$total_orders->count()->getOne();

			$bg=$this->add('View_BadgeGroup');
			$v=$bg->add('View_Badge')->set('Total order')->setCount($order)->setCountSwatch('ink');

			}
}