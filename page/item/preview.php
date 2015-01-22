<?php

class page_xShop_page_item_preview extends Page{
	function init(){
		parent::init();
		
		$this->add('View_Info')->set('Basic');
		
	}
}