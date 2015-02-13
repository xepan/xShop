<?php

class page_xShop_page_owner_quotation_draft extends page_xShop_page_owner_main{

	function page_index(){

		$crud=$this->add('CRUD',array('grid_class'=>'xShop/Grid_Quotation'));
		$crud->setModel('xShop/Quotation_Draft');
		$crud->addAction('submit',array('toolbar'=>false));
		$crud->addAction('sendMail',array('toolbar'=>false));
	}
}
