<?php

class page_xShop_page_owner_paygateconfig extends page_xShop_page_owner_main {
	
	function init(){
		parent::init();

		$btn = $this->app->layout->add('Button')->set('Update');


		$crud =$this->app->layout->add('CRUD');
		$crud->setModel('xShop/PaymentGateway');
		
		if($btn->isClicked()){
			
			$crud->grid->js()->reload()->execute();
		}
	}

	function page_config(){

	}
}