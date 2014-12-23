<?php

class page_xShop_page_owner_category extends page_xShop_page_owner_main{

	function init(){
		parent::init();
		

		$application_id=$this->api->recall('xshop_application_id');
		//Badges 
		$bg=$this->app->layout->add('View_BadgeGroup');
		$active = $this->add('xShop/Model_Category')->getActiveCategory($application_id)->count()->getOne();
		$unactive = $this->add('xShop/Model_Category')->getUnactiveCategory($application_id)->count()->getOne();
		$v=$bg->add('View_Badge')->set(' Active Category')->setCount($active)->setCountSwatch('ink');
		if($unactive)
			$v=$bg->add('View_Badge')->set(' Unactive Category')->setCount($unactive)->setCountSwatch('red');

		$category_model = $this->add('xShop/Model_Category');
		$category_model->addCondition('application_id',$application_id);	
		$category_model->setOrder('id','desc');
						
		$crud=$this->app->layout->add('CRUD',array('grid_class'=>'xShop/Grid_Category'));
		$crud->setModel($category_model,array('parent_id','name','order_no','is_active','meta_title','meta_description','meta_keywords','image_url','alt_text','description'),array('name','parent','order_no','is_active'));
		// $crud->addClass('xshop-owner-category');
		// $crud->add('Controller_FormBeautifier',array('params'=>array('f/addClass'=>'stacked')));
		
		if($crud->isEditing()){	
			$parent_model = $crud->form->getElement('parent_id')->getModel();
			$parent_model->title_field='category_name';
			$parent_model->addCondition('application_id',$application_id);
		}

		if($_GET['duplicate']){
			$this->api->stickyGET('duplicate');
			$category_model->duplicate($_GET['duplicate']);
			$crud->js(null,$crud->js()->_selector('.mygrid')->trigger('reload'))->univ()->successMessage('Duplicate Category Added Successfully')->execute();	
		}

	}

	function page_customfields(){

		$category_id = $this->api->stickyGET('xshop_categories_id');
		$application_id = $this->api->recall('xshop_application_id');
		$category_model = $this->add('xShop/Model_Category')->load($category_id);
		
		$custom_fields = $this->add('xShop/Model_CustomFields');
		$custom_fields->addCondition('application_id',$application_id);
		$custom_fields->tryLoadAny();

		$grid = $this->add('Grid');
		$grid->setModel($custom_fields,array('name'));
		
		$form = $this->add('Form');
		$selected_custom_fields = $form->addField('hidden','selected_custom_fields')->set(json_encode($category_model->getAllAssociateCustomFields()));
		$form->addSubmit('Update');

		$grid->addSelectable($selected_custom_fields);

		if($form->isSubmitted()){
			$category_model->ref('xShop/CategoryItemCustomFields')->_dsql()->set('is_allowed',0)->update();
			$selected_fields = json_decode($form['selected_custom_fields'],true);			
			foreach ($selected_fields as $customfield_id) {
				$category_model->addCustomField($customfield_id);
			}
			$form->js()->univ()->successMessage('Updated')->execute();
		}
	}	

}