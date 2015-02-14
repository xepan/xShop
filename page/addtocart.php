<?php

class page_xShop_page_addtocart extends Page{
	function init(){
		parent::init();

		$cart = $this->add('xShop/Model_Cart');
		$cart->addToCart($_POST['item_id'],$_POST['qty'],$_POST['item_member_design_id'],json_decode($_POST['custome_fields'],true),$otherfield=null);

		$this->js(null,$this->js()->univ()->successMessage('Item Added to Cart'))->_selector('.xshop-cart')->trigger('reload')->execute();
		
		exit;
	}
}