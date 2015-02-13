<?php
class page_xShop_page_owner_oppertunity extends page_xShop_page_owner_main{
	function init(){
		parent::init();

		$crud=$this->app->layout->add('CRUD');
		$crud->setModel('xShop/Oppertunity');
		$crud->addAction('quotation',array('toolbar'=>false));
	}
}