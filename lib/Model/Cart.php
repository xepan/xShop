<?php

namespace xShop;

class Model_Cart extends \Model{
	
	function init(){
		parent::init();
		$this->setSource('Session');

		$this->addField('item_id');
		$this->addField('item_code');
		$this->addField('item_name');
		$this->addField('item_member_design_id');
		$this->addField('rateperitem');
		$this->addField('qty');
		$this->addField('original_amount');
		$this->addField('sales_amount');
		$this->addField('shipping_charge')->defaultValue(0);
		$this->addField('tax')->defaultValue(0);
		$this->addField('total_amount')->defaultValue(0);

		$this->addField('custom_fields')->type('text');
		
	}

	function addToCart($item_id,$qty,$item_member_design_id, $custom_fields=null,$other_fields=null){
		$this->unload();

		if(!is_numeric($qty)) $qty=1;

		$item = $this->add('xShop/Model_Item')->load($item_id);
		$prices = $item->getPrice($custom_fields,$qty,'retailer');
		
		$amount = $item->getAmount($custom_fields,$qty,'retailer');

		$this['item_id'] = $item->id;
		$this['item_code'] = $item['sku'];
		$this['item_name'] = $item['name'];
		$this['rateperitem'] = $prices['sale_price'];
		$this['qty'] = $qty;
		$this['original_amount'] = $amount['original_amount'];
		$this['sales_amount'] = $amount['sale_amount'];
		$this['custom_fields'] = $custom_fields;
		$this['item_member_design_id'] = $item_member_design_id;
		$this['total_amount'] = $amount['sale_amount'] + $this['shipping_charge'] + $this['tax'];
		$this->save();
	}

	function getItemCount(){
		
		$item_count=0;
		foreach ($this as $junk) {
			$item_count += $junk['qty'];
		}

		return $item_count;
	}

	function getTotalAmount() { 
		$total_amount=0;
		$cart=$this->add('xShop/Model_Cart');
		foreach ($cart as $junk) {
			$total_amount += $junk['total_amount'];
		}

		return $total_amount;
	}

	function getTotalDiscount($percentage=false){
		$discount = 0;
		$total_amount=0;
		$original_total_amount = 0;
		$cart=$this->add('xShop/Model_Cart');
		// $carts = "";
		foreach ($cart as $junk) {
			if($junk['original_amount']){
				$total_amount += $junk['total_amount'];
				$original_total_amount += ($junk['original_amount'] + $this['shipping_charge'] + $this['tax']);
			}
		}

		return $total_amount - $original_total_amount;

	}

	function emptyCart(){
		 foreach ($this as $junk) {
			$this->delete();
		 }
	}

	function updateCart($id, $qty){
		if(!$this->loaded())
			throw new \Exception("Cart Model Not Loaded at update cart".$this['item_name']);
		
		if(!is_numeric($qty)) $qty=1;

		$item = $this->add('xShop/Model_Item')->load($this['item_id']);
		$prices = $item->getPrice($this['custom_fields'],$qty,'retailer');
		$amount = $item->getAmount($this['custom_fields'],$qty,'retailer');
		
		// throw new \Exception(print_r($prices,true). print_r($this['custom_fields'],true). " qty $qty", 1);
		


		// $this['item_id'] = $item->id;
		// $this['item_code'] = $item['sku'];
		// $this['item_name'] = $item['name'];
		$this['rateperitem'] = $prices['sale_price'];
		$this['qty'] = $qty;
		$this['original_amount'] = $amount['original_amount'];
		$this['sales_amount'] = $amount['sale_amount'];
		// $this['custom_fields'] = $custom_fields;
		// $this['item_member_design_id'] = $item_member_design_id;
		$this['total_amount'] = $amount['sale_amount'] + $this['shipping_charge'] + $this['tax'];

		// $text = "before ". $this['item_name'];

		$this->save();
		// $text .= " after ". $this['item_name'];
		// $this->unLoad();
		// throw new \Exception("Cart Model Loaded at update cart");
	}

	function remove($cartitem_id){
		$this->load($cartitem_id);
		$this->delete();		
	}
	
}