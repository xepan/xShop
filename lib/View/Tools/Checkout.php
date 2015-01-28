<?php
 
namespace xShop;

class View_Tools_Checkout extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	
	function init(){
		parent::init();

		$this->js()->_load('xShop-js');
		//Memorize checkout page if not logged in
		$this->api->memorize('next_url',$this->api->url());

		$this->postOrderProcess();
		
		//Check for the authtentication
		//Redirect to Login Page
		if($this->html_attributes['xshop_checkout_noauth_subpage_url']=='on'){
			if(!$this->html_attributes['xshop_checkout_noauth_subpage'] or $this->html_attributes['xshop_checkout_noauth_subpage'] ==""){
				$this->add('View_Error')->set('Subpage Name Cannot be Empty');
				return;
			}
			
			$auth = $this->add('xShop/Controller_Auth',array('redirect_subpage'=>$this->html_attributes['xshop_checkout_noauth_subpage']));
			$auth->checkCredential();
		}

		// add Login View if not loggedIn
		if($this->html_attributes['xshop_checkout_noauth_view'] == "on"){
			$auth = $this->add('xShop/Controller_Auth',array('substitute_view'=>"baseElements/View_Tools_UserPanel"));
			if(!$auth->checkCredential())
				return;
		}

		//Cart model
		$cart=$this->add('xShop/Model_Cart');
		$item=$this->add('xShop/Model_Item');

		$form=$this->add('Form_Stacked');
		$c = $form->add('Columns');

		$total_field = $c->addColumn(3)->addField('line','total');
		$total_field->setAttr('disabled',true)->addClass('atk-span-2');

		$discount_field = $c->addColumn(3)->addField('line','discount_voucher');
		
		$discount_amount_field = $c->addColumn(3)->addField('line','discount_amount');
		$discount_amount_field->setAttr('disabled',true);

		$net_amount_field = $c->addColumn(3)->addField('line','net_amount');
		$net_amount_field->setAttr('disabled',true);		
							
		$discount_field->js('change')->univ()->validateVoucher($discount_field,$form,$discount_amount_field,$total_field,$net_amount_field);

		$total_field->set($cart->getTotalAmount());
		$net_amount_field->set($cart->getTotalAmount());	
		
		$col=$form->add('Columns');
		$colleft=$col->addColumn(6);
		$colright=$col->addColumn(6);

		$member=$this->add('xShop/Model_MemberDetails');
		if($this->api->auth->model->id){
			$member->addCondition('users_id',$this->api->auth->model->id);
			$member->tryLoadAny();
		}

		$form->setModel($member,array('address','landmark','city','state','country','pincode'));

		$b_a=$form->getElement('address');
		$b_a->setCaption('Billing Address')->js(true)->closest('div.atk-form-row')->appendTo($colleft);
		$b_l=$form->getElement('landmark');
		$b_l->js(true)->closest('div.atk-form-row')->appendTo($colleft);
		$b_c=$form->getElement('city');
		$b_c->js(true)->closest('div.atk-form-row')->appendTo($colleft);
		$b_s=$form->getElement('state');
		$b_s->js(true)->closest('div.atk-form-row')->appendTo($colleft);
		$b_country=$form->getElement('country');
		$b_country->js(true)->closest('div.atk-form-row')->appendTo($colleft);
		$b_p=$form->getElement('pincode');
		$b_p->js(true)->closest('div.atk-form-row')->appendTo($colleft);
		$form->addField('Checkbox','i_read',"I have Read All trems & Conditions")->validateNotNull()->js(true)->closest('div.atk-form-row')->appendTo($colleft);
		
		$s_a=$form->addField('text','shipping_address')->validateNotNull(true);
		$s_a->js(true)->closest('div.atk-form-row')->appendTo($colright);
		$s_l=$form->addField('line','s_landmark','Landmark')->validateNotNull(true);
		$s_l->js(true)->closest('div.atk-form-row')->appendTo($colright);
		$s_c=$form->addField('line','s_city','City')->validateNotNull(true);
		$s_c->js(true)->closest('div.atk-form-row')->appendTo($colright);
		$s_s=$form->addField('line','s_state','State')->validateNotNull(true);
		$s_s->js(true)->closest('div.atk-form-row')->appendTo($colright);
		$s_country=$form->addField('line','s_country','Country')->validateNotNull(true);
		$s_country->js(true)->closest('div.atk-form-row')->appendTo($colright);
		$s_p=$form->addField('Number','s_pincode','Pincode')->validateNotNull(true);
		$s_p->js(true)->closest('div.atk-form-row')->appendTo($colright);		
		$shipping=$form->addButton('Copy Address');
		$shipping->js(true)->appendTo($colright);
		
		// Copy billing Address to shipping address		
		$shipping->js('click')->univ()->copyBillingAddress($b_a,$b_l,$b_c,$b_s,$b_country,$b_p,$s_a,$s_l,$s_c,$s_s,$s_country,$s_p);

		// add all active payment gateways
		$pay_gate_field = $form->addField('DropDown','payment_gateway_selected')->setEmptyText('Please Select Your Payment Method')->validateNotNull(true);
		$pay_gate_field->setModel($this->add('xShop/Model_PaymentGateway')->addCondition('is_active',true));

		$form->addSubmit('PlaceOrder');
		
		if($form->isSubmitted()){
			$cart=$this->add('xShop/Model_Cart');
			if(!$form['i_read'])
				$form->displayError('i_read','It is Must');

			$order=$this->add('xShop/Model_Order');
			$new_order = $order->placeOrder($form->getAllFields());	
			$this->api->memorize('order_done',$new_order);																												
			$cart_items->emptyCart();
			
			$this->js(null, $this->js()->univ()->successMessage('Order Placed Successfully'))
				->reload(array('order_done'=>'true'))->execute();
		}
	}

	function postOrderProcess(){
		if($_GET['order_done'] =='true'){
			
		}
	}

	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}