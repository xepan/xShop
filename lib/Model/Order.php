<?php

namespace xShop;

class Model_Order extends \Model_Table{
	public $table='xshop_orders';

	function init(){
		parent::init();
		
 		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		$this->hasOne('xHR/Employee','created_by_id')->defaultValue($this->api->current_employee->id)->system(true);

		$this->hasOne('xShop/PaymentGateway','paymentgateway_id');
		$f = $this->hasOne('xShop/MemberDetails','member_id')->group('a~3~<i class="fa fa-info"></i> Order Info');
		$f->icon = "fa fa-user~red";
		$f = $this->addField('name')->caption('Order ID')->mandatory(true)->group('a~3');
		// Order status
			// placed, partial-shipped, shipped, partial-dilivered, dilivered, partial-returned, returned, canceled, complete
		// order payment status
			// unpaid, paid, refunded
		$this->addField('order_from')->enum(array('online','offline'))->defaultValue('offline');
		$f = $this->addField('status')
									->enum(
										array('draft','submitted',
											  'approved','processing',
											  'processed','shipping',
											  'complete','cancel',
											  'return'))->group('a~2');
		$f = $this->addField('on_date')->type('date')->defaultValue(date('Y-m-d'))->group('a~2');
		$f->icon ="fa fa-calendar~blue";

		$f = $this->addField('amount')->mandatory(true)->group('b~3~<i class="fa fa-money"></i> Order Amount');
		$f = $this->addField('discount_voucher')->group('b~3');
		$f = $this->addField('discount_voucher_amount')->group('b~3');
		$f = $this->addField('net_amount')->mandatory(true)->group('b~3');

		$f = $this->addField('billing_address')->mandatory(true)->group('x~6~<i class="fa fa-map-marker"> Address</i>');
		$f = $this->addField('shipping_address')->mandatory(true)->group('x~6');	
		$f = $this->addField('order_summary')->type('text')->group('y~12');

		// Payment GateWay related Info
		$this->addField('transaction_reference');
		$this->addField('transaction_response_data')->type('text');

		$this->hasMany('xShop/OrderDetails','order_id');
		$this->addHook('beforeDelete',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete($m){
		if($m['discount_voucher'] != null and $m['discount_voucher'] != 0 ){
			$discountvoucher = $this->add('xShop/Model_DiscountVoucher');		
			$discountvoucher->addCondition('name',$m['discount_voucher']);
			$discountvoucher->tryLoadAny();
			if($discountvoucher->loaded()){
				$voucher_used = $discountvoucher->ref('xShop/DiscountVoucherUsed');
				$voucher_used->addCondition('member_id',$m['member_id']);
				$voucher_used->tryLoadAny();
				$voucher_used->delete();
			}
		}
		$m->ref('xShop/OrderDetails')->deleteAll();
	}

	function placeOrderFromCart(){
		
		// $billing_address=$order_info['address'].", ".$order_info['landmark'].", ".$order_info['city'].", ".$order_info['state'].", ".$order_info['country'].", ".$order_info['pincode'];
		// $shipping_address=$order_info['shipping_address'].", ".$order_info['s_landmark'].", ".$order_info['s_city'].", ".$order_info['s_state'].", ".$order_info['s_country'].", ".$order_info['s_pincode'];
		$member = $this->add('xShop/Model_MemberDetails');
		$member->loadLoggedIn();

		$cart_items=$this->add('xShop/Model_Cart');
		$this['member_id'] = $member->id;
		$this['status'] = "submitted";
		$this['order_from'] = "online";
		// $this['billing_address'] = $billing_address;
		// $this['shipping_address'] = $shipping_address;		
		$this->save();

		$order_details=$this->add('xShop/Model_OrderDetails');
			$total_amount=0;
			foreach ($cart_items as $junk) {

				$order_details['order_id']=$this->id;
				$order_details['item_id']=$cart_items['item_id'];
				$order_details['qty']=$cart_items['qty'];
				$order_details['rate']=$cart_items['sales_amount'];//get Item Rate????????????????
				$order_details['amount']=$cart_items['total_amount'];
				$order_details['custom_fields']=json_encode($cart_items['custom_fields']);
				$total_amount+=$order_details['amount'];
				$order_details->saveAndUnload();
			}

			$this['amount']=$total_amount;
			
			//$discount_voucher_amount = 0; 
			//TODO NET AMOUNT, TAXES, DISCOUNT VOUCHER AMOUNT etc.. CALCULATING AGAIN FOR SECURITY REGION 
			// $discountvoucher=$this->add('xShop/Model_DiscountVoucher');
			// if($discountvoucher->isUsable($order_info['discount_voucher'])){
			// 	$discount_voucher_amount=$total_amount * $discountvoucher->isUsable($order_info['discount_voucher']) /100;	
			// }
			// $this['discount_voucher']=$order_info['discount_voucher'];
			// $this['discount_voucher_amount']=$discount_voucher_amount;
			$this['net_amount'] = $total_amount;
			$this->save();

			// $discountvoucher->processDiscountVoucherUsed($this['discount_voucher']);
			return $this;
	}

	function payNow($transaction_reference,$transaction_reference_data){
		$this['transaction_reference'] =  $transaction_reference;
	    $this['transaction_response_data'] = json_encode($transaction_reference_data);
	    $this->save();
	}
	function checkStatus(){
		
	}

	function getAllOrder($member_id){
		if($this->loaded())
			throw new \Exception("member model loaded nahi hona chahiye");	
			// $this->api->js(true)->univ()->errorMessage('Member Model Loded nahi hona chahiye');
		 return $this->addCondition('member_id',$member_id);
		// throw new \Exception($member['']);
	}

	function sendOrderDetail($email_id=null, $order_id=null){

		if(!$this->loaded()) throw $this->exception('Model Must Be Loaded Before Email Send');
		
		$subject ="Thanku for Order";
		$config_model=$this->add('xShop/Model_Configuration');
		$config_model->tryLoadAny();

		$epan=$this->add('Model_Epan');//load epan model
		$epan->tryLoadAny();
		
		$tm=$this->add( 'TMail_Transport_PHPMailer' );
		$print_order=$this->add('xShop/View_PrintOrder');
		$print_order->setModel($this);

		if($config_model['order_detail_email_subject']){
			$subject=$config_model['order_detail_email_subject'];
		}

		if($config_model['order_detail_email_body']){
			$email_body=$config_model['order_detail_email_body'];		
		}
		
		$user_model = $this->add('xShop/Model_MemberDetails');
		$user_model->getAllDetail($this->api->auth->model->id);
		$email_body = $print_order->getHTML(false);

		//REPLACING VALUE INTO ORDER DETAIL TEMPLATES
		$email_body = str_replace("{{user_name}}", $this->api->auth->model['name'], $email_body);
		$email_body = str_replace("{{mobile_number}}", $user_model['mobile_number'], $email_body);
		$email_body = str_replace("{{billing_address}}",$this['billing_address'], $email_body);
		$email_body = str_replace("{{shipping_address}}", $this['shipping_address'], $email_body);
		$email_body = str_replace("{{email}}", $this->api->auth->model['email'], $email_body);
		//END OF REPLACING VALUE INTO ORDER DETAIL EMAIL BODY
		
		try{
			//Send Message to All Associate Affiliates
			$tm->send($this->api->auth->model['email'], $epan['email_username'], $subject, $email_body ,false,null);
		}catch( phpmailerException $e ) {
			$this->api->js(null,'$("#form-'.$_REQUEST['form_id'].'")[0].reset()')->univ()->errorMessage( $e->errorMessage() . " " . $epan['email_username'] )->execute();
		}catch( Exception $e ) {
			throw $e;
		}
	}

	function isFromOnline(){
		return $this['order_from']=='online';
	}
	function placeOrderFromQuotation($quotation_approved_id){
		if($quotation_approved_id < 0 or $quotation_approved_id == null)
			return false;

		$approved_quotation = $this->add('xShop/Model_Quotation')->load($quotation_approved_id);
		$approved_quotation_items = $approved_quotation->ref('xShop/QuotationItem','quotation_id');

		$this['member_id'] = $this->api->auth->model->id;
		$this['status'] = "Draft";
		$this['order_from'] = "offline";
		$this->save();

		$order_details=$this->add('xShop/Model_OrderDetails');
		$total_amount=0;
			foreach ($approved_quotation_items as $junk) {

				$order_details['order_id']=$this->id;
				$order_details['item_id']=$approved_quotation_items['item_id'];
				$order_details['qty']=$approved_quotation_items['qty'];
				$order_details['rate']=$approved_quotation_items['sales_amount'];//get Item Rate????????????????
				$order_details['amount']=$approved_quotation_items['total_amount'];
				$order_details['custom_fields']=json_encode($approved_quotation_items['custom_fields']);
				$total_amount+=$order_details['amount'];
				$order_details->saveAndUnload();
			}

			$this['amount']=$total_amount;
			$this['net_amount'] = $total_amount;
			$this->save();

			// $discountvoucher->processDiscountVoucherUsed($this['discount_voucher']);
			return true;
		
	}	


}