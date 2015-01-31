<?php
namespace xShop;
class Model_MemberDetails extends \Model_Table{
	public $table="xshop_memberdetails";
	function init(){
		parent::init();

		$this->hasOne('xShop/Users','users_id')->mandatory(true);
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		
		$this->addField('address')->type('text');
		$this->addField('billing_address')->type('text');
		$this->addField('shipping_address')->type('text');
		// $this->addField('is_verify')->type('boolean')->defaultValue(false);
		// $this->addField('join_on')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));
		// $this->addField('verified_on')->type('datetime')->defaultValue(null);
		$this->addField('landmark');
		$this->addField('city');
		$this->addField('state');
		$this->addField('country');
		$this->addField('mobile_number');
		$this->addField('pincode');
		
		$this->addField('is_active')->type('boolean')->defaultValue(true);
						
		$this->hasMany('xShop/Order','member_id');
		$this->hasMany('xShop/DiscountVoucherUsed','member_id');
		$this->hasMany('xShop/ItemMemberDesign','member_id');
		$this->hasMany('xShop/MemberImages','member_id');
		
		$this->addExpression('name')->set(function($m,$q){
			return $m->refSQL('users_id')->fieldQuery('name');
		});

		$this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete(){
		throw $this->exception('TODOOOOOOOOOOO');
	}


	function beforeSave(){
		$existing_check = $this->add('xShop/Model_MemberDetails');
		$existing_check->addCondition('users_id',$this['users_id']);
		$existing_check->addCondition('id','<>',$this->id);
		$existing_check->tryLoadAny();
		if($existing_check->loaded())
			throw $this->exception('User is already member','ValidityCheck')->setField('users_id');
	}

	function Verify($emailId,$activation_code){
		// throw new \Exception("$emailId", 1);
		$member=$this->add('xShop/Model_MemberDetails');
		$member->addCondition('emailID',$emailId);
		$member->addCondition('activation_code',$activation_code);
		$member->tryLoadAny();
		if($member->loaded()){
			$member['is_verify']= true;
			$member->save();
			return true;
		}
		else
			return false;		
	}

	function is_registered($userName){
		$member=$this->add('xShop/Model_MemberDetails');
		$member->addCondition('emailID',$userName);
		$member->tryloadAny();
		if($member->loaded()){
			return true;
		}else{
			return false;
		}
	}


/** RAKESH WORK */
	function sendVerificationMail(){

		if(!$this->loaded()) throw $this->exception('Model Must Be Loaded Before Email Send');

		$this['activation_code'] = rand(10000,99999);
		$this->save();

		$epan=$this->add('Model_Epan');//load epan model
		$epan->tryLoadAny();
	
		$l=$this->api->locate('addons','xecommApp', 'location');
			$this->api->pathfinder->addLocation(
			$this->api->locate('addons','xecommApp'),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css'
				)
			)->setParent($l);
			$tm=$this->add( 'TMail_Transport_PHPMailer' );
			$msg=$this->add( 'SMLite' );
			$msg->loadTemplate( 'mail/registrationMail' );

			//$msg->trySet('epan',$this->api->current_website['name']);		
			$enquiry_entries="some text related to register verification";
			$msg->trySetHTML('form_entries',$enquiry_entries);
			$msg->trySetHTML('activation_code',$this['activation_code']);

			$email_body=$msg->render();	

			$subject ="You Got a New Ecomm Customer";
				
			try{
			
				$tm->send($this['emailID'], $epan['email_username'], $subject, $email_body ,false,null);
			}catch( phpmailerException $e ) {
				// throw $e;
				$this->api->js(null,'$("#form-'.$_REQUEST['form_id'].'")[0].reset()')->univ()->errorMessage( $e->errorMessage() . " " . $epan['email_username'] )->execute();
			}catch( Exception $e ) {
				throw $e;
			}
	}

	function is_current_user(){
		if($this['users_id'] == $this->api->auth->model->id)
			return true;
		return false;
	}

	function loadLoggedIn(){
		if($this->loaded()) $this->unload();
		if(!$this->api->auth->isLoggedIn()) return false;
		
		$this->addCondition('users_id',$this->api->auth->model->id);
		$this->tryLoadAny();
		if(!$this->loaded()) return false;
		return true;
	}

	function sendSubscribtionMail(){

		if(!$this->loaded()) throw $this->exception('Model Must Be Loaded Before Email Send');
		
		$l=$this->api->locate('addons','xecommApp', 'location');
			$this->api->pathfinder->addLocation(
			$this->api->locate('addons','xecommApp'),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css'
				)
			)->setParent($l);
			$tm=$this->add( 'TMail_Transport_PHPMailer' );
			$msg=$this->add( 'SMLite' );
			$msg->loadTemplate( 'mail/subscribeMail' );

			//$msg->trySet('epan',$this->api->current_website['name']);		
			$enquiry_entries="some text related to register verification";
			$msg->trySetHTML('form_entries',$enquiry_entries);

			$email_body=$msg->render();	

			$subject ="Your Epan Got An Enquiry !!!";

			try{
				$tm->send( "", "info@epan.in", $subject, $email_body ,false,null);
			}catch( phpmailerException $e ) {
				// throw $e;
				$this->api->js($this->api->xecommauth->model->sendOrderDetail(),null,'$("#form-'.$_REQUEST['form_id'].'")[0].reset()')->univ()->errorMessage( $e->errorMessage() . " " . ""  )->execute();
			}catch( Exception $e ) {
				throw $e;
			}
	}

	

	function changePassword($old_passsword,$new_password){
		if(!$this->loaded())
			throw new \Exception('modal must be loaded at password change time');

		if($this['password']==$old_passsword){
			$this['password']=$new_password;
			$this->save();
			return true;
		}
		else{
			return false;
		}
	}

	function changeAddress($data){
		if(!$this->loaded())
			throw new \Exception('modal must be loaded at password change time');
		
		$this['address']=$data['street_address'];
		$this['landmark']=$data['landmark'];
		$this['city']=$data['city'];
		$this['state']=$data['state'];
		$this['country']=$data['country'];
		$this['pincode']=$data['pincode'];
		$this['mobile_number']=$data['mobile_number'];
		
		if($this->save())
			return true;
		else
			return false;
	}

	function updateInformation($info=array()){
		if(!$this->loaded())
			throw new \Exception("MemberDetails Model must be loaded before updatig information");
		
		$this['address']=$info['address'];				
		$this['shipping_address']=$info['address'];				
		$this['landmark']=$info['landmark'];
		$this['city']=$info['city'];
		$this['state']=$info['state'];
		$this['country']=$info['country'];
		$this['pincode']=$info['pincode'];
		$this['mobile_number']=$info['mobile_number'];

		$this->Update();
	}	

	function getAllDetail($user_id){
		$this->addCondition('users_id',$user_id);
		$this->tryLoadAny();
		return $this;
	}

	function is_verify($users_id){
		 $this->Load($users_id);
		 if($this['is_verify']==1){
		 return true;
		 }else 
		 	return false;
	}

	function deactivate(){
		if(!$this->loaded())
			throw new \Exception("Model Must be Loaded");

		$this['is_active'] = false;
		$this->save();
		return true;
	}

}