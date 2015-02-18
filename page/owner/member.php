<?php

class page_xShop_page_owner_member extends page_xShop_page_owner_main{

	function page_index(){

		$crud=$this->app->layout->add('CRUD');
        $crud->add('xHR/Controller_Acl');
		

		$members=$this->add('xShop/Model_MemberDetails');
		

		if(!$crud->isEditing()){
		$users_join = $members->leftJoin('users','users_id');
		$users_join->addField('username','username');
		$users_join->addField('email','email');
		$users_join->addField('is_user_active','is_active')->type('boolean');
		$users_join->addField('joining_date','created_at')->type('date');

			$btn = $crud->grid->addButton('System User to Member Create');
			if($btn->isClicked()){
				foreach ($um=$this->add('Model_Users') as $junk) {
					$nm = $this->add('xShop/Model_MemberDetails');
					$nm->addCondition('users_id',$um->id);
					$nm->tryLoadAny();
					$nm->save();
				}
			$crud->grid->js()->reload()->execute();
			}

			// $crud->grid->add('misc/Export');
			$crud->grid->addQuickSearch(array('users','email','address','city','mobile_number'));
			$crud->grid->addPaginator($ipp=50);
		}
		
		$members->setOrder('id');
		$crud->setModel($members);
		
	}
}	