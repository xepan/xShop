<?php

class page_xShop_page_owner_member extends page_xShop_page_owner_main{

	function page_index(){

		$grid=$this->add('Grid');

		$members=$this->add('xShop/Model_MemberDetails');
		$users_join = $members->join('users','Users_id');
		$users_join->addField('username','username');
		$users_join->addField('email','email');
		$users_join->addField('is Active','is_active');
		$users_join->addField('joining date','created_at');

		$members->setOrder('id',true);
		$grid->setModel($members);
		$grid->add('misc/Export');
		$grid->addQuickSearch(array('users','email','address','city','mobile_number'));
		$grid->addPaginator($ipp=50);	
		
	}

}	