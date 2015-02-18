<?php
class page_xShop_page_owner_item_itemdetail extends page_xShop_page_owner_main{
	function init(){
		parent::init();
		
		$this->add('xShop/View_Tools_ItemDetail');
		
	}
}