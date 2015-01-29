<?php

namespace xShop;

class View_Tools_xCart extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	function init(){
		parent::init();

		$this->addClass('xshop-cart');
		$proceed_page  = $this->html_attributes['cart-proceed-page'];
		$cart_detail_page  = "?subpage=".$this->html_attributes['cart-detail-page']?:'home';

		
		if($_GET['order_place']=="true"){
			$this->api->memorize('next_url',array('subpage'=>$_GET['subpage']));
			//Check for user logged in
			$auth = $this->add('xShop/Controller_Auth',array('redirect_subpage'=>$this->html_attributes['show-cart-noauth-subpage-url']));
			$auth->checkCredential();
			
			//Place Order
			$order = $this->add('xShop/Model_Order');
			$new_order = $order->placeOrderFromCart();

			// Empty Cart
			$this->add('xShop/Model_Cart')->emptyCart();

			$this->api->memorize('checkout_order',$new_order);
			//Redirect to Proceed/checkout Page with New Order Id
			$this->api->redirect($this->api->url(null,array('subpage'=>$proceed_page)));
		}


		$this->template->Set('xshop_cart_detail_page',$cart_detail_page);
		// value passing game via body using attr from add to cart button 
		$this->js('reload')->reload();


		//add Cart model work as a session
		$cart_model=$this->add('xShop/Model_Cart');
		$item_model=$this->add('xShop/Model_Item');

		if($_GET['item_id'] AND $_GET['item_id'] != 'undefined'){
			$item_model->load($_GET['item_id']);
			$cart_model->addToCart($_GET['item_id'],$qty,$item_member_design_id,$custom_fields);
		}

		//Get Total amount and Total Item
		$total_amount=$cart_model->getTotalAmount();
		$total_item=$cart_model->getItemCount();
		// Show Total Item added in Cart
		if($this->html_attributes['show-item-count']){
			$str = '<div class="xshop-cart-item-count"><span class="xshop-cart-item-count-label">';
			$str.=$total_item;
			$str.=" item(s)</span></div>";
			$this->template->setHtml('xshop_cart_items',$str);
		}else {
			$this->template->tryDel('xshop_cart_items');
		}

		// Show Cart Total Price
		if($this->html_attributes['show-price-count']){
			$str = '<div class="xshop-cart-price-count"><span class="xshop-cart-currency-sign">';
			$str.= ' ₹ </span><span class="xshop-cart-price-count-label">';
			$str.= $total_amount;
			$str .='</span></div>';
			$this->template->setHtml('xshop_cart_price',$str);
		}else{
			$this->template->tryDel('xshop_cart_price');
		}

		//Empty Button
		if($this->html_attributes['show-empty']){
			$empty_btn=$this->add('Button',null,'xshop_cart_empty_btn')->set('empty')->addClass('btn xshop-cart-empty-btn');
			if($empty_btn->isClicked()){
				$cart_model->emptyCart();								
				$this->api->js()->univ()->redirect('/')->execute();
			}
		}else{
			$this->template->tryDel('xshop_cart_empty_btn');
		}

		//CheckOut button
		if($this->html_attributes['show-checkout']){
			$checkout_btn=$this->add('Button',null,'xshop_cart_checkout_btn')->set('Check out')->addClass('btn xshop-cart-checkout-btn');
			if($checkout_btn->isClicked()){
				$this->api->js()->univ()->redirect(null,array('subpage'=>$this->html_attributes['xshop_ipb_checkout_page']))->execute();
			}
		}else{
			$this->template->tryDel('xshop_cart_checkout_btn');
		}

		//Detail Cart btn
		if($this->html_attributes['show-cartdetail']){
			//Virtual Page added
			$xshop_cart = $this->add('VirtualPage')->set(function($p)use($total_item,$cart_model){
				if($total_item <= 0){
					$p->add('View_Error')->set('Cart is Empty');
				}else{
					//Cart All Item added
					foreach ($cart_model as $junk){
						$ci_view=$p->add('xShop/View_CartItem',array('new'=>$cart_model['id'],'html_attributes'=>$this->html_attributes));
						$ci_view->setModel($cart_model);
					}
				}

			});

			$view_btn=$this->add('Button',null,'xshop_cart_viewcart_btn')->set('View Cart')->addClass('btn xshop-cart-viewcart-btn');
			if($view_btn->isClicked()){
				$this->api->js()->univ()->frameURL('MY CART',$this->api->url($xshop_cart->getURL()))->execute();
				// $this->api->js()->univ()->redirect(null,array('subpage'=>$this->html_attributes['xshop_ipb_cart_detail_page']))->execute();
			}
		}else{
			$this->template->tryDel('xshop_cart_viewcart_btn');
		}

		//Cart Detail View
		if($this->html_attributes['show-cart-items']){
			if($total_item <= 0){
				$this->add('View_Error')->set('Cart is Empty');
			}else{
				foreach ($cart_model as $junk) {
					$ci_view=$this->add('xShop/View_CartItem',array('new'=>$cart_model['id'],'html_attributes'=>$this->html_attributes),'xshop_cart_detail');
					$ci_view->setModel($cart_model);
				}
			}
		}else{
			$this->template->tryDel('xshop_cart_detail');
		}

		//Total and total Deiscount
		if($this->html_attributes['show-cart-total-estimate-bar']){

			$str = '<div class="xshop-cart-detail-estimate-container"><div class="xshop-cart-total-saving-amount">Total Discount <div class="xshop-cart-total-saving-amount-figure">'. $cart_model->getTotalDiscount() .'</div></div>';
			$str.= '<div class="xshop-cart-total-amount">Estimated Total Amount <div class="xshop-cart-total-amount-figure">'.$cart_model->getTotalAmount().'</div></div>';
			$str.= "</div>";
			$this->template->SetHTML('xshop_cart_estimate',$str);
		}else{
			$this->template->tryDel('xshop-cart-detail-estimate-container');
		}

		//Show Proceed Btn or not
		if($this->html_attributes['show-proceed']){			
			$place_order_btn = $this->add('View',null,'xshop_cart_proceed')->set('Place order')->addClass('xshop-cart-proceed-btn');
			$place_order_btn->js('click')->reload(array('order_place'=>'true'));
			// ->setElement('a')->setAttr('href','index.php?subpage='.$proceed_page);
		}else{
			$this->template->tryDel('xshop_cart_proceed');
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

