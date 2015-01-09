<?php

class page_xShop_page_owner_item extends page_xShop_page_owner_main{

	function init(){
		parent::init();
		
		$application_id=$this->api->recall('xshop_application_id');		
		
		$cols = $this->app->layout->add('Columns');
		$cat_col = $cols->addColumn(3);
		$item_col = $cols->addColumn(9);
		
		//Category
		$cat_col->add('xShop/View_Badges_CategoryPage');
		$category_model = $cat_col->add('xShop/Model_Category');
		$category_model->addCondition('application_id',$application_id);	
		$category_model->setOrder('id','desc');
						
		$cat_crud=$cat_col->add('CRUD',array('grid_class'=>'xShop/Grid_Category'));
		$cat_crud->setModel($category_model,array('parent_id','name','order_no','is_active','meta_title','meta_description','meta_keywords','image_url','alt_text','description'),array('name'));
		
		if($cat_crud->isEditing()){	
			$parent_model = $cat_crud->form->getElement('parent_id')->getModel();
			$parent_model->title_field='category_name';
			$parent_model->addCondition('application_id',$application_id);
		}else{
			$g = $cat_crud->grid;
			$g->addMethod('format_filteritem',function($g,$f)use($item_col){
				$g->current_row_html[$f]='<a href="javascript:void(0)" onclick="'. $item_col->js()->reload(array('category_id'=>$g->model->id)) .'">'.$g->current_row[$f].'</a>';
			});
			$g->addFormatter('name','filteritem');
		}

		//Item
		$item_col->add('xShop/View_Badges_ItemPage');
		$item_model = $item_col->add('xShop/Model_Item');
		$item_model = $item_model->applicationItems($application_id);

		if($_GET['category_id']){
			$this->api->stickyGET('category_id');
			$filter_box = $item_col->add('View_Box')->setHTML('Items for <b>'. $this->add('xShop/Model_Category')->load($_GET['category_id'])->get('name').'</b>' );

			$filter_box->add('Icon',null,'Button')
            ->addComponents(array('size'=>'mega'))
            ->set('cancel-1')
            ->addStyle(array('cursor'=>'pointer'))
            ->on('click',function($js) use($filter_box,$item_col) {
                $filter_box->api->stickyForget('category_id');
                return $filter_box->js(null,$item_col->js()->reload())->hide()->execute();
            });

			$cat_item_join = $item_model->join('xshop_category_item.item_id');
			$cat_item_join->addField('category_id');
			$cat_item_join->addField('is_associate');
			$item_model->addCondition('category_id',$_GET['category_id']);
			$item_model->addCondition('is_associate',true);
		}
		
		$item_crud=$item_col->add('CRUD',array('grid_class'=>'xShop/Grid_Item'));
		$item_crud->setModel($item_model,array('name','sku','is_publish','short_description','description','default_qty','default_qty_unit','original_price','sale_price','rank_weight','created_at','expiry_date','allow_attachment','allow_enquiry','allow_saleable','show_offer','show_detail','show_price','show_manufacturer_detail','show_supplier_detail','new','feature','latest','mostviewed','enquiry_send_to_admin','item_enquiry_auto_reply','allow_comments','comment_api','add_custom_button','custom_button_text','custom_button_url','meta_title','meta_description','tags','offer_id','offer_position','is_designable','designer_id'),array('name','sku','sale_price','is_publish'));
			
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
		$product_view = $this->add('xShop/View_ItemDetail');
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

			$g->addQuickSearch(array('category_name'));
			$g->addPaginator($ipp=50);					
		}
	}

	function page_custom_fields(){
		$item_id=$this->api->stickyGET('xshop_items_id');
		$application_id = $this->api->recall('xshop_application_id');
		
		$item_model = $this->add('xShop/Model_Item')->load($item_id);
		
		$custom_fields = $this->add('xShop/Model_CategoryItemCustomFields');
		$custom_fields->addCondition('item_id',$item_id);
		$custom_fields->tryLoadAny();

		$crud = $this->add('CRUD');
		$crud->setModel($custom_fields,array('customfield_id','rate_effect','is_active'),array('customfield','rate_effect','is_active'));
		if($crud->form){
			$crud->form->getElement('customfield_id')->getModel()->addCondition('application_id',$application_id);
		}
		$crud->grid->addColumn('expander','Values');
		if(!$crud->isEditing()){	
			$g = $crud->grid;
			$g->addMethod('format_Values',function($g,$f){
				$temp = $this->add('xShop/Model_CustomFieldValue')->addCondition('itemcustomfiledasso_id',$g->model->id)->tryLoadAny();
				$str = "";
				if($temp->count()->getOne())
					$str = '<span class=" atk-label atk-swatch-green">'.$temp->count()->getOne()."</span>";
							
				$g->current_row_html[$f] = $g->current_row_html[$f].$str;
			});
			$g->addFormatter('Values','Values');
		}
	}	

	function page_custom_fields_values(){
		$item_id=$this->api->stickyGET('xshop_items_id');
		$custom_field_asso_id = $this->api->stickyGET('xshop_category_item_customfields_id');
		$custom_field_id = $this->api->stickyGET('xshop_category_item_customfields_id');
		
		$custom_feild_values_model = $this->add('xShop/Model_CustomFieldValue')->addCondition('itemcustomfiledasso_id',$custom_field_id)->tryLoadAny();
		$crud = $this->add('CRUD');
		$crud->setModel($custom_feild_values_model,array('name','rate_effect'));
		
		$crud->grid->addColumn('expander','images');
		$crud->grid->addColumn('expander','filter');
	}

	function page_custom_fields_values_images(){
		$item_id=$this->api->stickyGET('xshop_items_id');
		$custom_filed_value_id = $this->api->stickyGET('xshop_custom_fields_value_id');
		$image_model = $this->add('xShop/Model_ItemImages')
					->addCondition('customefieldvalue_id',$custom_filed_value_id)
					->addCondition('item_id',$item_id)
					->tryLoadAny();
		
		$crud = $this->add('CRUD');
		$crud->setModel($image_model);
	}

	function page_custom_fields_values_filter(){
		$item_id=$this->api->stickyGET('xshop_items_id');
		$custom_field_asso_id=$this->api->stickyGET('xshop_category_item_customfields_id');
		$application_id = $this->api->recall('xshop_application_id');

		$custom_filed_value_id = $this->api->stickyGET('xshop_custom_fields_value_id');
		
		$filter_model = $this->add('xShop/Model_CustomFieldValueFilterAssociation');

		$crud = $this->add('CRUD');
		//for Custom Field Id
		$temp = $this->add('xShop/Model_CategoryItemCustomFields');
		$associated_customfiled = $temp->addCondition('item_id',$item_id)->addCondition('id','<>',$custom_field_asso_id)->_dsql()->del('fields')->field('customfield_id')->getAll();
		$associated_customfiled = iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($associated_customfiled)),false);
		// ----------------------
		if($crud->form){
			$form_model = $crud->form->getElement('customfield_id')->getModel();
			$form_model->addCondition('application_id',$application_id);
			$form_model->addCondition('id','in',$associated_customfiled);
		}

		// $filter_model->addCondition('customfield_id',);
		$crud->setModel($filter_model);
	}

	function page_specifications(){
		$item_id=$this->api->stickyGET('xshop_items_id');
		$item = $this->add('xShop/Model_Item')->load($item_id);

		$crud = $this->add('CRUD');
		$crud->setModel($item->ref('xShop/ItemSpecificationAssociation'));
	}

}