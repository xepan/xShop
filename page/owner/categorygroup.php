<?php

class page_xShop_page_owner_categorygroup extends page_xShop_page_owner_main{

	function page_index(){

		$catgroup_model=$this->add('xShop/Model_CategoryGroup');
		$crud=$this->add('CRUD');
		$crud->setModel($catgroup_model,array('name'));
		$crud->add('Controller_FormBeautifier',array('params'=>array('f/addClass'=>'stacked')));
		if($crud->grid){
			$crud->grid->addcolumn('expander','category','categories');
		}
	}
}	