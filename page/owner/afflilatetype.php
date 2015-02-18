<?php


class page_xShop_page_owner_afflilateytype extends page_xShop_page_owner_main{
	function init(){
		parent::init();

		
		
		$crud=$this->app->layout->add('CRUD');
		$crud->setModel('xShop/Model_AfflilateType');

			$crud->add('xHR/Controller_Acl');
	}
}