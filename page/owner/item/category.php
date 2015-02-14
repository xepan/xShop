<?php

class page_xShop_page_owner_item_category extends Page{
	
	function init(){
		parent::init();
		
		$item_id = $this->api->stickyGET('item_id');
		$application_id=$this->api->recall('xshop_application_id');

		$item = $this->add('xShop/Model_Item')->load($item_id);
		$grid=$this->add('Grid');
		$app_cat_model=$this->add('xShop/Model_ActiveCategory',array('table_alias'=>'mc'));

		// selector form
		$form = $this->add('Form');
		$app_cat_field = $form->addField('hidden','app_cat')->set(json_encode($item->getAssociatedCategories()));
		$form->addSubmit('Update');
		
		// $app_cat_model->addExpression('status')->set(function($m,$q)use($item_id){
		// 	$category_prod_model=$m->add('xShop/Model_CategoryItem',array('table_alias'=>'c'));
		// 	$category_prod_model->addCondition('category_id',$q->getField('id'));
		// 	$category_prod_model->addCondition('item_id',$item_id);
		// 	$category_prod_model->addCondition('is_associate',true);
		// 	return $category_prod_model->count();
		// })->type('boolean');

		$grid->setModel($app_cat_model,array('category_name'));
		$grid->addSelectable($app_cat_field);

		if($form->isSubmitted()){
			$item->ref('xShop/CategoryItem')->_dsql()->set('is_associate',0)->update();	
			$cat_item_model = $this->add('xShop/Model_CategoryItem');
			$selected_categories = json_decode($form['app_cat'],true);
			foreach ($selected_categories as $cat_id) {
				$cat_item_model->createNew($cat_id,$_GET['item_id']);
			}		
			// Update Search String
			$item_model=$this->add('xShop/Model_Item');
			$item_model->load($item_id);
			$item_model->updateSearchString($_GET['item_id']);			
			
			$item_model->updateCustomField($item_id);
			$form->js(null,$this->js()->univ()->successMessage('Updated'))->reload()->execute();
		}		
		$grid->addQuickSearch(array('category_name'));
		$grid->addPaginator($ipp=50);

	}
}