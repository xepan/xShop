<?php

class page_xShop_page_owner_order_customfields extends page_xShop_page_owner_main{
	function init(){
		parent::init();

		echo $_GET['item_id'];
	}
}