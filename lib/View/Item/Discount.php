<?php

namespace xShop;

class View_Item_Discount extends \View{
	public $item_model;
	
	function init(){
		parent::init();
		
		if(!is_numeric($this->item_model['original_price']) and $this->item_model['original_price'] <= 0) return;

		$discount = $this->item_model['original_price'] - $this->item_model['sale_price'];
		$discount_per = $discount / $this->item_model['original_price'] * 100;
		$this->set($discount_per.'%');
	}
}