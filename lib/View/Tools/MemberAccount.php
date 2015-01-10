<?php

namespace xShop;

class View_Tools_MemberAccount extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	function init(){
		parent::init();

		if($this->api->auth->model->loaded()){
			$this->add('View_Info')->set('Member Panel'." id = ".$this->api->auth->model->id);
			$tab = $this->add('Tabs');
			$account_tab = $tab->addTab('AccountInformation');

			$member=$account_tab->add('xShop/Model_MemberDetails')->addCondition('users_id',$this->api->auth->model->id);
			$member->tryLoadAny();
			$form=$account_tab->add('Form');
			$form->setModel($member);
			$form->addSubmit('Update');

			$users=$this->add('Model_Users')->load($this->api->auth->model->id);
			if($form->isSubmitted()){
				$form->update();
				$this->js(null,$form->js()->univ()->successMessage('Update Information Successfully'))->reload()->execute();			
			}

			//MEMBER ORDER tab
			$order_tab = $tab->addTab('Order','order');
			$order_tab->add('xShop/View_MemberOrder');

			// MEMBER DESIGNS
			$design_tab = $tab->addTab('Designs','designs');
			$design_tab->add('View')->set('Deisgns');
			$form = $this->add('Form');
			$template_model = $design_tab->add('xShop/Model_ItemTemplate');
			$form->addField('dropdown','item_template')->setModel($template_model);

			$form->addSubmit('Duplicate');
			if($form->isSubmitted()){
				$template_model->load($form['item_template'])->duplicate();
				$form->js()->univ()->successMessage('Design Duplicated')->execute();
			}

		}
		else{
			$this->add('View_Error')->set('you are not Logged in');
		}

	}
	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}