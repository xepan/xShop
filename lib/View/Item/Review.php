<?php

namespace xShop;

class View_Item_Review extends \View{
	function init(){
		parent::init();
		$this->add('View')->set(' Reviews *****');
	}
}