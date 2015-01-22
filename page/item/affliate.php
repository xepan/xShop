<?php

class page_xShop_page_item_affliate extends Page{

	function init(){
		parent::init();

		$this->add('View_Info')->set('Affliation');
	}
}