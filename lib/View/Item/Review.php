<?php

namespace xShop;

class View_Item_Review extends \View{
	public $item_model;
	public $name;
	function init(){
		parent::init();
		$view=$this->add('View')->setElement('img')->setAttr('src','epan-components/xShop/templates/images/5-stars.png')->setStyle(array('width'=>'70px'));
		
	}
}