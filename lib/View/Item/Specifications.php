<?php

namespace xShop;

class View_Item_Specifications extends \View{
	public $item_model;
	public $name;
	function init(){
		parent::init();
		$this->add('View')->set(' specifications *****');
	}
}