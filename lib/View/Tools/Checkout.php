<?php
 
namespace xShop;
use Omnipay\Common\GatewayFactory;
class View_Tools_Checkout extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	
	function init(){
		parent::init();

		$this->js()->_load('xShop-js');
		//Memorize checkout page if not logged in
		$this->api->memorize('next_url',array('subpage'=>$_GET['subpage'],'order_id'=>$_GET['order_id']));

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

		// Check if order is owned by current member ??????

		$order = $this->api->memorize('checkout_order',$this->api->recall('checkout_order',$this->add('xShop/Model_Order')->tryLoad($_GET['order_id']?:0)));
		if(!$order->loaded()){
			$this->api->forget('checkout_order');
			$this->add('View_Error')->set('Order not found');
			return;
		}

		$member = $this->add('xShop/Model_MemberDetails');
		$member->loadLoggedIn();

		if($order['member_id'] != $member->id){
			$this->add('View_Error')->set('Order does not belongs to your account. ' . $order->id);
			return;
		}

		// ================================= PAYMENT MANAGEMENT =======================
		if($_GET['pay_now']=='true'){

			// create gateway 
			$gateway = GatewayFactory::create($order['paymentgateway']);
			
			$gateway_parameters = $order->ref('paymentgateway_id')->get('parameters');
			$gateway_parameters = json_decode($gateway_parameters,true);

			// fill default values from database
			foreach ($gateway_parameters as $param => $value) {
				$param =ucfirst($param);
				$fn ="set".$param;
				$gateway->$fn($value);
			}
			// create params for purchase ... no card prepare now (may be for next version of xepan)

			// ---- No Cards for now 
			// $formInputData = array(
			//     'firstName' => 'Bobby',
			//     'lastName' => 'Tables',
			//     'number' => '4111111111111111',
			//     'expiryMonth' => '06',
			//     'expiryYear' => '16',
			// );
			// $card = new \Omnipay\Common\CreditCard($formInputData);

			$params = array(
			    'amount' => round($order['net_amount'],2),
			    'currency' => 'USD',
			    'description' => 'Invoice Against Order Payment',
			    'transactionId' => $order->id, // invoice no 
			    // 'card'=>$card, // If creadit card information sending
			    'headerImageUrl' => 'http://xavoc.com/logo.png',
			    // 'transactionReference' => '1236Ref',
			    'returnUrl' => 'http://'.$_SERVER['HTTP_HOST'].$this->api->url(null,array('paid'=>'true','pay_now'=>'true'))->getURL(),
			    'cancelUrl' => 'http://'.$_SERVER['HTTP_HOST'].$this->api->url(null,array('canceled'=>'true','pay_now'=>'true'))->getURL()
		 	);

			// Step 2. if got returned from gateway ... manage ..

			if($_GET['paid']){
				$response = $gateway->completePurchase($params)->send();

			    if ( ! $response->isSuccessful())
			    {
			        throw new \Exception($response->getMessage());
			    }
			    $order->payNow($response->getTransactionReference(),$response->getData());
			    $this->api->forget('checkout_order');
			    $this->api->redirect($this->api->url(null,array('subpage'=>'home')));
			    exit;
			}

			// Step 1. initiate purchase ..
			try {
			    $response = $gateway->purchase($params)->send();
			    if ($response->isSuccessful() /* OR COD */) {
			        // mark order as complete if not COD
			        // Not doing onsite transactions now ...
					$responsereturn=$response->getData();
			    } elseif ($response->isRedirect()) {
			        $response->redirect();
			    } else {
			        // display error to customer
			        exit($response->getMessage());
			    }
			} catch (\Exception $e) {
			    // internal error, log exception and display a generic message to the customer
			    exit('Sorry, there was an error processing your payment. Please try again later.'. $e->getMessage(). " ". get_class($e));
			}


		}
		// ================================= PAYMENT MANAGEMENT END ===================

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

		$total_field->set($order['amount']);
		$net_amount_field->set($order['net_amount']);	
		
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

			// validate address ...
			// do discount coup validation or application
			// Update order with shipping and billing details
			// Update order with Payment Gateway selected
			$order['paymentgateway_id'] = $form['payment_gateway_selected'];
			// save order :)
			$order->save();
			// Update order in session :: checkout_order																
			$this->api->memorize('checkout_order',$order);

			$this->js(null, $this->js()->univ()->successMessage('Order Placed Successfully'))
				->redirect($this->api->url(null,array('pay_now'=>'true')))->execute();
		}
	}

	function postOrderProcess(){
		if($_GET['order_done'] =='true'){
			
		}
	}

	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}