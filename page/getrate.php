<?php

class page_xShop_page_getrate extends Page{
	
	function init(){
		parent::init();
			
		$item= $this->add('xShop/Model_Item')->load($_GET['item_id']);
		extract($item->getAmount(json_decode($_GET['custome_fields'],true),$_GET['qty'],null));

		echo $original_amount.','.$sale_amount;
		exit;
	}
}