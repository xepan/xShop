<?php

class page_xShop_page_owner_item_qtyandprice extends Page{
	function init(){
		parent::init();
		
		$this->add('View_Info')->set('Basic');
		
	}
}