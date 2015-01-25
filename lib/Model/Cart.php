<?php

namespace xShop;

class Model_Cart extends \Model{
	
	function init(){
		parent::init();
		$this->setSource('Session');

		$this->addField('item_id');
		$this->addField('item_code');
		$this->addField('item_name');
		$this->addField('rateperitem');
		$this->addField('qty');
		$this->addField('original_amount');
		$this->addField('sales_amount');
		$this->addField('shipping_charge');
		$this->addField('tax');
		$this->addField('total_amount');

		$this->addField('custom_fields');
		
	}

	function addToCart($item_id,$qty,$item_member_design_id, $custom_fields=null,$otherfield=null){
		
		$item = $this->add('xShop/Model_Item')->load($item_id);
		$prices = $item->getPrice($custom_fields,$qty,'retailer');
		$amount = $item->getAmount($custom_fields,$qty,'retailer');

		$this['item_id'] = $item->id;
		$this['item_code'] = $item['sku'];
		$this['item_name'] = $item['name'];
		$this['rateperitem'] = $prices['sales_price'];
		$this['qty'] = $qty;
		$this['original_amount'] = $amount['original_amount'];
		$this['sales_amount'] = $amount['sales_amount'];
		$this['custom_fields'] = $custom_fields;
		$this['item_member_design_id'] = $item_member_design_id;
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
			$total_amount += $junk['rate'];
		}

		return $total_amount;
	}

	function emptyCart(){
		 foreach ($this as $junk) {
			$this->delete();
		 }
	}

	function updateCart($id,$qty){
		if(!$this->loaded())
			throw new \Exception("Cart Model Not Loaded at update cart".$this['item_name']);		
		
		$this['qty']=$qty;
		$this['rate'] = $this['rateperitem'] * $qty;
		$this->save();
		// $this->unLoad();
		// throw new \Exception("Cart Model Loaded at update cart");
	}

	function remove($cartitem_id){
		$this->load($cartitem_id);
		$this->delete();		
	}
	
}