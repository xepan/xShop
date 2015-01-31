<?php

class page_xShop_page_member_order extends Page{
	function page_index(){
		
		$member = $this->add('xShop/Model_MemberDetails');
		if(!$member->loadLoggedIn()){
			$this->add('View_Error')->set('Not Authorized');
			return;
		}
			
		$this->add('xShop/View_MemberOrder');
	}
}