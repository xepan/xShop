<?php

use Omnipay\Common\GatewayFactory;

class page_xShop_page_owner_paygateconfig extends page_xShop_page_owner_main {
	
	function init(){
		parent::init();

		$btn = $this->app->layout->add('Button')->set('Update');


		$crud =$this->app->layout->add('CRUD');
		$crud->setModel('xShop/PaymentGateway',array('is_active','name','processing'));
			
		if(!$crud->isEditing()){
			$crud->grid->addColumn('expander','config');
		}

		if($btn->isClicked()){
			$gateway = new GatewayFactory();
			$payment_gateway = $gateway->getSupportedGateways();
			// Gate All OmniPay Payment Gateway
			// $payment_gateway = array();
			// $gateway_list = scandir("vendor/omnipay",1);
			// $gateway_list  = array_diff($gateway_list, array('.', '..'));
			// foreach ($gateway_list as $name){
			// 	if(count(glob("vendor/omnipay/".$name."/*")) != 0){
			// 		$sub_gateway = scandir("vendor/omnipay/".$name."/src",1);
			// 		$sub_gateway  = array_diff($sub_gateway, array('.', '..'));
	
			// 		foreach ($sub_gateway as $sub_gateway_name) {
			// 			//Remove SubFolders
			// 			if(!is_dir("vendor/omnipay/".$name."/src/".$sub_gateway_name)){
			// 				//Remove GateWay from File name							
			// 				$payment_gateway[] = rtrim($name."_".str_replace(".php","",str_replace("Gateway", "", $sub_gateway_name)),'_');
			// 			}
			// 		}
			// 	}
			// }	
				
			//Save in SQL Model
			foreach ($payment_gateway as $gateway) {
				//tryload  PaymentGateway Model with name
				$pg_model = $this->add('xShop/Model_PaymentGateway');
				$pg_model->addCondition('name',$gateway);
				$pg_model->tryLoadAny();
				try {
					//create OmniPay Object
					$gateway_factory = GatewayFactory::create($gateway);
					$pg_model['default_parameters'] = $gateway_factory->getDefaultParameters();//getDefault Params
					$pg_model['processing'] = $pg_model['processing']?: "OffSite";
					$pg_model->saveAndUnload();
 				} catch (Exception $e) {

 				}

			}
			$crud->grid->js()->reload()->execute();
		}
	}

	function page_config(){
		$payment_gateway = $this->add('xShop/Model_PaymentGateway')->load($this->api->stickyGET('xshop_payment_gateways_id'));

		$form = $this->add('Form');

		$fields = json_decode($payment_gateway['default_parameters'],true);
		$values = json_decode($payment_gateway['parameters'],true);
		// echo "<pre>";
		// print_r($fields);
		// echo "</pre>";
		foreach ($fields as $field => $value) {
			if(is_array($value)){
				$form->addField('DropDown',$field)->setValueList($value)->set($values[$field]);
			}else
				$form->addField('line',$field)->set($values[$field]);
		}

		$form->addSubmit('Update');

		if($form->isSubmitted()){
			$fields = json_decode($payment_gateway['default_parameters'],true);
			foreach ($fields as $field => $value) {
				if(is_array($value))
					$fields[$field] = $value[$form[$field]];
				else
					$fields[$field] = $form[$field];
			}
			$payment_gateway['parameters'] = json_encode($fields);

			$payment_gateway->save();
			$form->js()->univ()->successMessage('Updated')->execute();

		}


	}
}