<?php

class page_xShop_page_owner_quotation_submit extends page_xShop_page_owner_main{

	function page_index(){

		$crud=$this->add('CRUD',array('grid_class'=>'xShop/Grid_Quotation'));
		$crud->setModel('xShop/Quotation_Submit');
		// $crud->addAction('approve',array('toolbar'=>false));
		// $crud->addAction('redesign',array('toolbar'=>false));

		$crud->add('xHR/Controller_Acl');
	}
}
