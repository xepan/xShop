<?php

class page_xShop_page_owner_member extends page_xShop_page_owner_main{

	function page_index(){

		$crud=$this->app->layout->add('CRUD');

		$members=$this->add('xShop/Model_MemberDetails');
		if($crud->isEditing('add')){
			$users_join = $members->join('users','users_id');
			$users_join->addField('username','username');
			$users_join->addField('email','email');
			$users_join->addField('is Active','is_active')->type('boolean');
			$users_join->addField('joining date','created_at')->type('date');
		}

		$members->setOrder('id',true);
		$crud->setModel($members);
		if(!$crud->isEditing()){
			$crud->grid->add('misc/Export');
			$crud->grid->addQuickSearch(array('users','email','address','city','mobile_number'));
			$crud->grid->addPaginator($ipp=50);
		}
		
		
	}
}	