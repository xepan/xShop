<?php

class page_xShop_page_member_accountinfo extends Page{
	function page_index(){


		$member = $this->add('xShop/Model_MemberDetails');
		if(!$member->loadLoggedIn()){
			$this->add('View_Error')->set('Not Authorized');
			return;
		}

		$member->addCondition('users_id',$this->api->auth->model->id);
		$member->tryLoadAny();

		$tab = $this->add('Tabs');
		$password_tab = $tab->addTab('Change Password');
		$address_tab = $tab->addTab('Address');	
		$deactive_tab = $tab->addTab('Deactive');
		
	//============================Change Password
		$user = $this->add('Model_Users')->load($this->api->auth->model->id);
		$password_tab->add('View_Info')->set('Name :: '.$user['name']." E-mail Id:: ".$user['email']);
		$change_pass_form = $password_tab->add('Form');
		$change_pass_form->addField('password','old_password')->validateNotNull();
		$change_pass_form->addField('password','new_password')->validateNotNull();
		$change_pass_form->addField('password','retype_password')->validateNotNull();
		$change_pass_form->addSubmit('Update');
		
		if($change_pass_form->isSubmitted()){
			if( $change_pass_form['new_password'] != $change_pass_form['retype_password'])
				$change_pass_form->displayError('new_password','Password not match');
			
			if($user['password'] != $change_pass_form['old_password'])
				$change_pass_form->displayError('old_password','Password not match');

			if($user->updatePassword($change_pass_form['new_password'])){
				$change_pass_form->js()->univ()->successMessage('Password Changed Successfully')->execute();
			}

		}

	//================================Address======================
		$form=$address_tab->add('Form');
		$form->setModel($member,array('address','billing_address','shipping_address','landmark','city','state','country','mobile_number','pincode'));
		$form->addSubmit('Update');
		$users=$this->add('Model_Users')->load($this->api->auth->model->id);
		if($form->isSubmitted()){
			$form->update();
			$this->js(null,$form->js()->univ()->successMessage('Update Information Successfully'))->reload()->execute();
		}
	

	//===========================Deactivate=====================
		$deactive_form = $deactive_tab->add('Form');
		$deactive_form->addField('password','password')->validateNotNull();
		$deactive_form->addSubmit('Confirm Deactivation');
		if($deactive_form->isSubmitted()){
			if($user['password'] != $deactive_form['password'])
				$deactive_form->displayError('password','Deactivation Failed');	

			if($member->deactivate())
				$user->deactivate()?$deactive_form->js(null,$form->js()->univ()->successMessage('Account Deactivate SuccessFully'))->redirect($this->api->url(null,array('page'=>'logout')))->execute():$deactive_form->js(null,$form->js()->univ()->successMessage('Account Deactivate SuccessFully'))->reload()->execute();
		}

	}
}