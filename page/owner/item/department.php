<?php

class page_xShop_page_owner_item_department extends page_xShop_page_owner_main{
	function init(){
		parent::init();

		$item_id = $this->api->stickyGET('item_id');
		$application_id=$this->api->recall('xshop_application_id');

		$item = $this->add('xShop/Model_Item')->load($item_id);
		
		$grid=$this->add('Grid');
		$department = $this->add('xHR/Model_Department',array('table_alias'=>'mc'));
		$department->addCondition('is_active',true);
		$department->addCondition('is_production_department',true);

		// selector form
		$form = $this->add('Form');
		$item_department_field = $form->addField('hidden','item_department')->set(json_encode($item->getAssociatedDepartment()));
		$form->addSubmit('Update');
	
		$grid->setModel($department,array('name'));
		$grid->addSelectable($item_department_field);

		if($form->isSubmitted()){
			$item->ref('xShop/ItemDepartmentAssociation')->_dsql()->set('is_active',0)->update();
			
			$selected_department = array();
			$selected_department = json_decode($form['item_department'],true);
			foreach ($selected_department as $department_id) {
				$department_model = $this->add('xHR/Model_Department');
				$department_model->load($department_id);
				$department_model->createAssociationWithItem($_GET['item_id']);
			}
			$form->js(null,$this->js()->univ()->successMessage('Updated'))->reload()->execute();
		}		
		$grid->addQuickSearch(array('name'));
		$grid->addPaginator($ipp=100);
	}
}