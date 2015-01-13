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
			$design_tab = $tab->addTab('My Designs','designs');
			$form = $design_tab->add('Form');
			$crud = $design_tab->add('CRUD',array('allow_add'=>false));
			$template_model = $design_tab->add('xShop/Model_ItemTemplate');
			$form->addField('dropdown','item_template')->setModel($template_model);

			$form->addSubmit('Duplicate');
			if($form->isSubmitted()){
				$new_item = $template_model->load($form['item_template'])->duplicate($create_default_design_also=true);
				// create default design as well for this new template that is just duplicated

				$form->js(null,$crud->js()->reload())->univ()->successMessage('Design Duplicated')->execute();
			}

			$designer = $this->add('xShop/Model_MemberDetails');
			$designer->loadLoggedIn();

			$my_designs_model = $this->add('xShop/Model_ItemMemberDesign');
			$crud->setModel($my_designs_model);

			if(!$crud->isEditing()){
				$g = $crud->grid;
				//Edit Template
				$g->addColumn('edit_template');
				$g->addMethod('format_edit_template',function($g,$f)use($designer){
					if($g->model->ref('item_id')->get('designer_id') == $designer->id)
						$g->current_row_html[$f]='<a target="_blank" href='.$this->api->url(null,array('subpage'=>$this->html_attributes['xsnb-desinger-page'],'xsnb_design_item_id'=>$g->model['item_id'],'xsnb_design_template'=>'true')).'>Edit Template</a>';
					else
						$g->current_row_html[$f]='';
						
				});
				$g->addFormatter('edit_template','edit_template');
				//Edit Design
				$g->addColumn('design');
				$g->addMethod('format_design',function($g,$f)use($designer){
					$g->current_row_html[$f]='<a target="_blank" href='.$this->api->url(null,array('subpage'=>$this->html_attributes['xsnb-desinger-page'],'xsnb_design_item_id'=>'not-available','xsnb_design_template'=>'false','item_member_design_id'=>$g->model->id)).'>Design</a>';
				});
				$g->addFormatter('design','design');
			}
		}
		else{
			$this->add('View_Warning')->set('Please Log in first');
		}

	}
	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}