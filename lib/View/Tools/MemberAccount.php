<?php

namespace xShop;

class View_Tools_MemberAccount extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	function init(){
		parent::init();

		if(!$this->api->auth->model->loaded())
			return;
			
			$this->add('View_Info')->set('Member Panel'." id = ".$this->api->auth->model->id);
			
			$tab = $this->add('Tabs')->addClass('nav-stacked');
			//Account Information
			$tab->addTabUrl('xShop/page/member_accountinfo','Settings');

			//MEMBER ORDER tab
			$tab->addTabUrl('xShop/page/member_order','Order');

			// MEMBER DESIGNS
			$tab->addTabUrl('xShop/page/member_design','Designs',array('html_attributes'=>$this->html_attributes));

	}
	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}