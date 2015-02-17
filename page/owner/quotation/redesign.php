<?php

class page_xShop_page_owner_quotation_redesign extends page_xShop_page_owner_main{

	function page_index(){

		$crud=$this->add('CRUD',array('grid_class'=>'xShop/Grid_Quotation'));
		$crud->setModel('xShop/Quotation_Redesign');
		$crud->add('xHR/Controller_Acl');
	}
}
