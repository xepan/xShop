<?php

class page_xShop_page_owner_item extends page_xShop_page_owner_main{

	function init(){
		parent::init();
		
		$application_id=$this->api->recall('xshop_application_id');

		$item_model = $this->add('xShop/Model_Item');
		
		$bg=$this->app->layout->add('View_BadgeGroup');
		$v=$bg->add('View_Badge')->set(' Total Item ')->setCount($item_model->getItemCount($application_id))->setCountSwatch('ink');
		$v=$bg->add('View_Badge')->set(' Publish Item ')->setCount($item_model->getPublishCount($application_id))->setCountSwatch('green');
		$v=$bg->add('View_Badge')->set(' Unpublish Item ')->setCount($this->add('xShop/Model_Item')->getUnpublishCount($application_id))->setCountSwatch('red');
		
		$model = $this->add('xShop/Model_Item');
		$model = $model->applicationItems($application_id);

		$crud=$this->app->layout->add('CRUD',array('grid_class'=>'xShop/Grid_Item'));
		$crud->setModel($model,array('party_id','name','sku','is_publish','short_description','description','original_price','sale_price','rank_weight','created_at','expiry_date','allow_attachment','allow_enquiry','allow_saleable','show_offer','show_detail','show_price','show_manufacturer_detail','show_supplier_detail','new','feature','latest','mostviewed','enquiry_send_to_self','enquiry_send_to_supplier','enquiry_send_to_manufacturer','item_enquiry_auto_reply','allow_comments','comment_api','add_custom_button','custom_button_text','custom_button_url','meta_title','meta_description','tags'),array('name','sku','sale_price','is_publish'));
		
	}

	function page_categories(){
		
		$application_id=$this->api->recall('xshop_application_id');
		
		$item_id = $this->api->stickyGET('xshop_items_id');
		$item = $this->add('xShop/Model_Item')->load($_GET['xshop_items_id']);
		$grid=$this->add('Grid');
		$app_cat_model=$this->add('xShop/Model_ActiveCategory',array('table_alias'=>'mc'));

		// selector form
		$form = $this->add('Form');
		$app_cat_field = $form->addField('hidden','app_cat')->set(json_encode($item->getAssociatedCategories()));
		$form->addSubmit('Update');
		
		$app_cat_model->addExpression('status')->set(function($m,$q)use($item_id){
			$category_prod_model=$m->add('xShop/Model_CategoryItem',array('table_alias'=>'c'));
			$category_prod_model->addCondition('category_id',$q->getField('id'));
			$category_prod_model->addCondition('item_id',$item_id);
			$category_prod_model->addCondition('is_associate',true);
			return $category_prod_model->count();
		})->type('boolean');

		$grid->setModel($app_cat_model,array('category_name','application','status'));
		$grid->addSelectable($app_cat_field);

		if($form->isSubmitted()){
			$item->ref('xShop/CategoryItem')->_dsql()->set('is_associate',0)->update();	
			$cat_item_model = $this->add('xShop/Model_CategoryItem');
			$selected_categories = json_decode($form['app_cat'],true);
			foreach ($selected_categories as $cat_id) {
				$cat_item_model->createNew($cat_id,$_GET['xshop_items_id']);
			}		
			// Update Search String
			$item_model=$this->add('xShop/Model_Item');
			$item_model->load($item_id);
			$item_model->updateSearchString($_GET['xshop_items_id']);			
			
			$item_model->updateCustomField($item_id);
			$form->js(null,$this->js()->univ()->successMessage('Updated'))->reload()->execute();
		}		
		$grid->addQuickSearch(array('category_name'));
		$grid->addPaginator($ipp=50);
	}

	function page_details(){
		$item_id=$this->api->stickyGET('xshop_items_id');	
		$item_model = $this->add('xShop/Model_Item');
		$item_model->getItem($item_id);
		$product_view = $this->add('xShop/View_ProductDetail');
		$product_view->setModel($item_model);
	}

	function page_images(){
		$item_id=$this->api->stickyGET('xshop_items_id');
		$crud = $this->add('CRUD');
		$item_images_model = $this->add('xShop/Model_ItemImages')->addCondition('item_id',$item_id);
		$item_images_model->setOrder('id','desc');
		$crud->setModel($item_images_model);
		if(!$crud->isEditing()){
			$g = $crud->grid;
			$g->addMethod('format_image_thumbnail',function($g,$f){
				$g->current_row_html[$f] = '<img style="height:40px;max-height:40px;" src="'.$g->current_row[$f].'"/>';
			});
			$g->addFormatter('item_image','image_thumbnail');
			$g->addQuickSearch(array('category_name'));
			$g->addPaginator($ipp=50);
		}

	}

	function page_attachments(){
		$item_id=$this->api->stickyGET('xshop_items_id');
		$crud = $this->add('CRUD');
		$attachment_model = $this->add('xShop/Model_Attachments')->addCondition('item_id',$item_id);
		$attachment_model->setOrder('id','desc');
		$crud->setModel($attachment_model);
		if(!$crud->isEditing()){
			$g = $crud->grid;
			$g->addMethod('format_attachment',function($g,$f){
				$g->current_row_html[$f] = '<a class="glyphicon glyphicon-folder-open" target="_blank" style="height:40px;max-height:40px;" href="'.$g->current_row[$f].'"></a>';
			});
			$g->addFormatter('attachment_url','attachment');

		}
		$g->addQuickSearch(array('category_name'));
		$g->addPaginator($ipp=50);					
	}

	function page_custom_fields(){
		$item_id=$this->api->stickyGET('xshop_items_id');
		$application_id = $this->api->recall('xshop_application_id');
		
		$item_model = $this->add('xShop/Model_Item')->load($item_id);
		
		$custom_fields = $this->add('xShop/Model_CustomFields');
		$custom_fields->addCondition('application_id',$application_id);
		$custom_fields->tryLoadAny();

		$grid = $this->add('CRUD');
		$grid->setModel($item_model->ref('xShop/CategoryItemCustomFields'));
		
		// $form = $this->add('Form');
		// $selected_custom_fields = $form->addField('hidden','selected_custom_fields')->set(json_encode($item_model->getAssociatedCustomFields()));
		// $form->addSubmit('Update');

		// $grid->addSelectable($selected_custom_fields);

		// if($form->isSubmitted()){
		// 	$item_model->ref('xShop/CategoryItemCustomFields')->_dsql()->set('is_allowed',0)->update();
		// 	$selected_fields = json_decode($form['selected_custom_fields'],true);			
		// 	foreach ($selected_fields as $customfield_id) {
		// 		$item_model->addCustomField($customfield_id);
		// 	}
		// 	$form->js()->univ()->successMessage('Updated')->execute();
		// }
	}

	function page_specifications(){
		$item_id=$this->api->stickyGET('xshop_items_id');
		$item = $this->add('xShop/Model_Item')->load($item_id);

		$crud = $this->add('CRUD');
		$crud->setModel($item->ref('xShop/ItemSpecificationAssociation'));
	}
}