<?php

class page_xShop_page_owner_item_qtyandprice extends Page{

	function init(){
		parent::init();
		$this->api->stickyGET('item_id');
	}
	
	function page_index(){
		
		$this->add('View_Info')->set('Display Basic Price For Item Here Again As Form .. updatable');
		
		$tabs = $this->add('Tabs');
		$tabs->addTabURL('./cf','Custom Field Based Price');
		$tabs->addTabURL('./qty','Quantity Based Price');
	}

	function page_cf(){
		$item = $this->add('xShop/Model_Item')->load($_GET['item_id']);
		$item->includeCustomeFieldValues(array('rate_effect','value_name'=>'name'));
		
		$item->getElement('rate_effect')->display(array('grid'=>'grid/inline'));

		$crud = $this->add('Grid');
		$crud->setModel($item,array('customfield','value_name','rate_effect'));
	}

	function page_qty(){
		$item = $this->add('xShop/Model_Item')->load($_GET['item_id']);
		$crud = $this->add('CRUD');
		$crud->setModel($item->ref('xShop/QuantitySet'));
	}
}