<?php

namespace xShop;

class View_Tools_xCart extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	function init(){
		parent::init();
		$this->addClass('xshop-cart');

		// value passing game via body using attr from add to cart button 
		$this->js('reload')->reload(array('item_id'=>$this->js()->_selector('body')->attr('xshop_add_item_id')));
		//add Cart model work as a session
		$cart_model=$this->add('xShop/Model_Cart');
		$item_model=$this->add('xShop/Model_Item');

		if($_GET['item_id'] AND $_GET['item_id'] != 'undefined'){														
			// from simple add to cart button on Item lister
			$item_model->load($_GET['item_id']);			
			$cart_model->addToCart($_GET['item_id'],$item_model['sku'],$item_model['name'],1,$item_model['sale_price'], null,null);						
		}

		//Get Total amount and Total Item
		$total_amount=$cart_model->getTotalAmount();
		$total_item=$cart_model->getItemCount();


		switch ($this->html_attributes['xshop_cart_layout']) {
			case 2:
				// Show Total Item added in Cart
				if($this->html_attributes['xshop_ipb_cart_items']=='false'){
					$this->template->tryDel('xshop_cart_items');
				}else {
					$str = '<div class="xshop-cart-item-count"><span class="xshop-cart-item-count-label">';
					$str.=$total_item;
					$str.=" item(s)</span></div>";
					$this->template->setHtml('xshop_cart_items',$str);
				}

				// Show Cart Total Price
				if($this->html_attributes['xshop_ipb_cart_price']=='false'){
					$this->template->tryDel('xshop_cart_price');
				}else{
					$str = '<div class="xshop-cart-price-count"><span class="xshop-cart-currency-sign">';
					$str.= ' ₹ </span><span class="xshop-cart-price-count-label">';
					$str.= $total_amount;
					$str .='</span></div>';
					$this->template->setHtml('xshop_cart_price',$str);
				}

				//Empty Button
				if($this->html_attributes['xshop_ipb_cart_empty_btn']=='false'){
					$this->template->tryDel('xshop_cart_empty_btn');
				}else{
					$empty_btn=$this->add('Button',null,'xshop_cart_empty_btn')->set('empty')->addClass('btn xshop-cart-empty-btn');
					if($empty_btn->isClicked()){
						$cart_model->emptyCart();								
						$this->api->js()->univ()->redirect('/')->execute();
					}
				}

				//CheckOut button
				if($this->html_attributes['xshop_ipb_cart_checkout_btn']=='false'){
					$this->template->tryDel('xshop_cart_checkout_btn');
				}else{
					$checkout_btn=$this->add('Button',null,'xshop_cart_checkout_btn')->set('Check out')->addClass('btn xshop-cart-checkout-btn');
					if($checkout_btn->isClicked()){
						$this->api->js()->univ()->redirect(null,array('subpage'=>$this->html_attributes['xshop_ipb_checkout_page']))->execute();
					}
				}

				//Detail Cart btn
				if($this->html_attributes['xshop_ipb_cart_viewcart_btn']=='false'){
					$this->template->tryDel('xshop_cart_viewcart_btn');
				}else{
					//Virtual Page added
					$xshop_cart = $this->add('VirtualPage')->set(function($p)use($total_item,$cart_model){
						if($total_item <= 0){
							$p->add('View_Error')->set('Cart is Empty');
						}else{
							//Cart All Item added
							foreach ($cart_model as $junk){
								$ci_view=$p->add('xShop/View_CartItem',array('new'=>$cart_model['id']));
								$ci_view->setModel($cart_model);
							}
						}

					});

					$view_btn=$this->add('Button',null,'xshop_cart_viewcart_btn')->set('View Cart')->addClass('btn xshop-cart-viewcart-btn');
					if($view_btn->isClicked()){
						$this->api->js()->univ()->frameURL('Cart Detail',$this->api->url($xshop_cart->getURL()))->execute();
						// $this->api->js()->univ()->redirect(null,array('subpage'=>$this->html_attributes['xshop_ipb_cart_detail_page']))->execute();
					}
				}

				if($this->html_attributes['xshop_cart_detail'] or 1){
					if($total_item <= 0){
						$this->add('View_Error')->set('Cart is Empty');
					}else{
						foreach ($cart_model as $junk) {
							$ci_view=$this->add('xShop/View_CartItem',array('new'=>$cart_model['id']),'xshop_cart_detail');
							$ci_view->setModel($cart_model);
						}
						$this->add('View',null,'xshop_cart_proceed')->set('Proceed')->setElement('a')->setAttr('href','index.php?subpage='.$this->html_attributes['xshop_cartdetail_checkout_subpage']);
					}
				}else{
					$this->template->tryDel('xshop_cart_detail');
				}
					
		break;

				default:
					$this->template->tryDel('xshop_cart_with_item_no');
					$this->template->tryDel('xshop_cart_with_item_price_btn');
					$this->template->tryDel('xshop_cart_lg');
					$this->add('View_Error')->set('Please Select Cart Layout First');
					break;
			}

		//loading custom CSS file	
		$cart_css = 'epans/'.$this->api->current_website['name'].'/xshopcart.css';
		$this->api->template->appendHTML('js_include','<link id="xshop-cart-customcss-link" type="text/css" href="'.$cart_css.'" rel="stylesheet" />'."\n");		
	}	


	function defaultTemplate(){
		$this->app->pathfinder->base_location->addRelativeLocation(
		    'epan-components/'.__NAMESPACE__, array(
		        'php'=>'lib',
		        'template'=>'templates',
		        'css'=>'templates/css',
		        'js'=>'templates/js',
		    )
		);
		return array('view/xShop-xCart');		
	}	

	function render(){
		$this->js(true)->_load('cart/cart')->_selector('.xshop-cart')->xepan_xshop_cart();
		parent::render();
	}
}

