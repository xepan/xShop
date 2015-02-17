<?php

class page_xShop_page_owner_quotation extends page_xShop_page_owner_main{
	function page_index(){

		$tab = $this->app->layout->add('Tabs');
		$draft_tab = $tab->addTabURL('xShop_page_owner_quotation_draft','Draft');
		$submit_tab = $tab->addTabURL('xShop_page_owner_quotation_submit','Submitted');
		$redesign_tab = $tab->addTabURL('xShop_page_owner_quotation_redesign','Redesign');
		$approve_tab = $tab->addTabURL('xShop_page_owner_quotation_approve','Approved');
		
		// $p=$crud->addFrame('communication_frame');
		// if($p) $p->add('View_Error')->set($crud->id);
		
	}

}