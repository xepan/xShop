<?php

class page_xShop_page_owner_quotation_redesign extends page_xShop_page_owner_main{

	function page_index(){

		$crud=$this->add('CRUD',array('grid_class'=>'xShop/Grid_Quotation',
									'allow_edit'=>false,
									'allow_del'=>false,
									'allow_add'=>false
									));
		$crud->setModel('xShop/Quotation_Redesign');
		$crud->addAction('redesign',array('toolbar'=>false));
	}
}
