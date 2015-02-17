<?php

namespace xShop;

class Menu_User extends \Menu_Vertical{
	function init(){
		parent::init();
		$this->addItem('Dashboard','xProduction_page_owner_user_dashboard');
		$this->addItem('Sales work','xProduction_page_owner_user_jobcards');
	}
}