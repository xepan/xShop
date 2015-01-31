<?php
  
namespace xShop;

class View_CartItem extends \View{
	public $cart_item=array();
	public $html_attributes = array();
	function init(){
		parent::init();
		
		$this->addClass('xshop-cartdetail');
	}
	
	function setModel($model){
		$this->template->trySet('id',$model['id']);
		//Sno
		if($this->html_attributes['show-cart-items-sno']){
			$str = '<td class="xshop-cart-item-sno">1</td>';
			$this->template->setHtml('sno',$str);
		}

		//Image 
		if($this->html_attributes['show-cart-items-image']){
			$img_model=$this->add('xShop/Model_ItemImages');
			$img_model->getImageUrl($model['item_id']);
			$img_url = $img_model['image_url']?:"logo.svg";

			// get preview image of editable items
			if($model['item_member_design_id']){
				$img_url='index.php?page=xShop_page_designer_thumbnail&item_member_design_id='.$model['item_member_design_id'].'&width=80';
			}

			$str ='<td class="xshop-cart-item-image col-md-1">
					<img width="100%" src="'.$img_url.'"class="xcart-image"/></td>';
			$this->template->setHtml('image',$str);	
		}

		//name
		if($this->html_attributes['show-cart-items-name']){
			$name = '<td class="xshop-cart-item-name col-md-3">';
			$name.= '<div class="xshop-cart-item-code"><span class="xshop-cart-item-code-label">code</span>'.$model['item_code'].'</div>';
			$name.= '<div>'.$model['item_name'].'</div>';			
			// IF designable_item from designer then add Preview btn as well ????????????????
			if($model['item_member_design_id']){
				$name.= '<a target="_blank" href='.
							$this->api->url(null,array('subpage'=>$this->html_attributes['item-designer-page'],
														'xsnb_design_item_id'=>'not-available',
														'xsnb_design_template'=>'false',
														'item_member_design_id'=>$model['item_member_design_id']
														)).'> Edit </a>';
				$name.= '<a href="#"> Preview </a>';
			}

			//Add Custom Fields 
			$cfs = "";
			$a = $model['custom_fields']?:array();
			foreach ($a as $key => $value) {
				$cfs = "".$key." : ".$value."</br>";
			}

			$name.= '<div class="xshop-cart-item-custom-fields">'.$cfs.'</div>';
			
			$name.="</td>";
			$this->template->setHtml('name', $name);
		}

		//Qty and Rate
		if($this->html_attributes['show-cart-items-qty-rate']){
			// $v = $this->add('View',null,'qty')->setHtml('');
			//Form			
			$form=$this->add('Form',null,'qty',array('form/empty'));
			// if qty_from_set_only ???????????????????
				// add dropdown type filed with values
			// else
				$q_f=$form->addField('line','cart_item_id')->set($model['id']);
				$q_f=$form->addField('Number','qty')->set($model['qty'])->addClass('cart-spinner');
			// $q_f->setAttr('size',1);
			// $q_f->js(true)->spinner(array('min'=>1));
			$r_f=$form->addField('line','rate')->set($model['rateperitem']);
			$r_f->setAttr( 'disabled', 'true' )->addClass('disabled_input');
			
			$r_f_hidden=$form->addField('hidden','rateperitem')->set($model['rateperitem']);

			$this->api->js()->_load( 'xShop-js' );
			$q_f->js(true)->univ()->numericField();
			$q_f->js( 'change', $form->js()->submit() );//->univ()->calculateRate($q_f,$r_f_hidden,$r_f);

			$btn_submit=$form->add('View')->addClass('xshop-cart-qty-update-btn')->set('Update');
			$btn_submit->js('click')->submit();
			
			if($form->isSubmitted()){
				$all_cart_item_model = $this->add('xShop/Model_Cart');
				$all_cart_item_model->load($form['cart_item_id']);
				$all_cart_item_model->updateCart($form['cart_item_id'],$form['qty']);
				// $item['qty']=$form['qty'];
				$cart_model=$this->add('xShop/Model_Cart');
				$total_amount=$cart_model->getTotalAmount();
				$total_saving=$cart_model->getTotalDiscount();
				$total_item=$cart_model->getItemCount();

				$js=array();
				// set rate and amount in row
				$js[] = $r_f->js()->val($all_cart_item_model['rateperitem']);
				$js[] = $this->js()->find('.xshop-cart-item-subtotal')->text($all_cart_item_model['total_amount']);
				$js[] = $q_f->js()->val($all_cart_item_model['qty']);
				// set total amount
				$js[] = $form->js()->_selector('.xshop-cart-price-count')->text($total_amount);
				$js[] = $form->js()->_selector('.xshop-cart-total-amount-figure')->text($total_amount);
				$js[] = $form->js()->_selector('.xshop-cart-total-saving-amount-figure')->text($total_saving);
				$form->js(null,$js)->univ()->successMessage('Cart Update Successfully')->execute();
			}
		}else{
			$this->template->tryDel('qty_rate');
		}

		//Delivery Information
		if($this->html_attributes['show-cart-items-delivery-info']){
			$str = '<td class="xshop-cart-item-delivery col-md-3">';
			$str.= $model['shipping_charge'].' tax:'.$model['tax'];
			$str.="</td>";
			$this->template->setHtml('delivery_detail',$str);
		}

		//subtotal
		if($this->html_attributes['show-cart-items-subtotal']){
			$str = '<td class="xshop-cart-item-subtotal col-md-2">';
			$str.= $model['total_amount'];
			$str.="</td>";
			$this->template->setHtml('subtotal',$str);
		}

		//remove btn
		if(!$this->html_attributes['show-cart-items-remove-btn']){
			$this->template->tryDel('remove');
		}
		


	


		parent::setModel($model); 	
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
		return array('view/xShop-CartItem');	
	}
}	