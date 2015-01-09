<?php

namespace xShop;

class View_Item_CustomeField extends \View{
	function init(){
		parent::init();

		$this->add('View')->set('Custom Fields');
	}
}