<?php
class page_xShop_page_owner_item_itemdetail extends Page{
	function init(){
		parent::init();
		
		$this->add('xShop/View_Tools_ItemDetail');
		
	}
}