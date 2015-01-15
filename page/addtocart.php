<?php

class page_xShop_page_addtocart extends Page{
	function init(){
		parent::init();

		$item = $this->add('xShop/Model_Item')->load($_POST['item_id']);

		$cart = $this->add('xShop/Model_Cart');
		$cart->addToCart($item->id,$item['sku'],$item['name'],$_POST['qty'],$rate=0,json_decode($_POST['custome_fields'],true),$otherfield=null);

		$this->js(null,$this->js()->univ()->successMessage('Item Added to Cart'))->_selector('.xshop-cart')->trigger('reload')->execute();
		// foreach ($cart as $j) {
		// 	echo $j['item_id'];
		// }

		// echo "alert('Done');";
		exit;
	}
}