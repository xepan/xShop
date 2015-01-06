<?php

class page_xShop_page_owner_shopsnblogs extends page_xShop_page_owner_main {
	
	function init(){
		parent::init();
		$tabs= $this->app->layout->add('Tabs');
		$shop_tab = $tabs->addTabURL('./shops','Shops');
		$shop_tab = $tabs->addTabURL('./blogs','Blogs');
	}

	function page_shops(){
		$crud= $this->add('CRUD',array('grid_class'=>'xShop/Grid_Shop'));
		$crud->setModel('xShop/Shop');
		
		$cf_crud = $crud->addRef('xShop/CustomFields',array('label'=>'Custom Fields'));
		$sp_crud = $crud->addRef('xShop/Specification',array('label'=>'Specifications'));
		// if($cf_crud and $cf_crud->isEditing()){
		// 	$cf_crud->form->getElement('type')->js('change',$this->js()->alert('asdasd'));
		// }
	}

	function page_blogs(){
		$crud= $this->add('CRUD',array('grid_class'=>'xShop/Grid_Blog'));
		$crud->setModel('xShop/Blog');

	}
}