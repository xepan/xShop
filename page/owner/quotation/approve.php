<?php

class page_xShop_page_owner_quotation_approve extends page_xShop_page_owner_main{

	function page_index(){

		$crud=$this->add('CRUD',array('grid_class'=>'xShop/Grid_Quotation',
									'allow_edit'=>false,
									'allow_del'=>false,
									'allow_add'=>false
									));
		$crud->setModel('xShop/Quotation_Approve');
		$crud->addAction('creatOrder',array('toolbar'=>false));
		$crud->addAction('approved',array('toolbar'=>false));
	}
}
