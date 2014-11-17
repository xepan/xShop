<?php

class page_xShop_page_owner_product extends page_xShop_page_owner_main{

	function page_index(){
		// parent::init();
		
		$col_static=$this->add('Columns');
		$i_col=$col_static->addColumn(3);
		$d_col=$col_static->addColumn(3);
		$i_col->add('View_Info')->set("Item out of stock");
		$d_col->add('View_Error')->set("Disable Product");

		$col=$this->add('Columns');
		$p_col=$col->addColumn(12);

		$model = $p_col->add('xShop/Model_Product');

		$crud=$p_col->add('CRUD',array('frame_options'=>array('beforeClose'=>'alert("sdf");')));
		$crud->setModel($model,array('supplier_id','manufacturer_id','name','sku','short_description','description','original_price','sale_price','created_at','expiry_date','meta_title','meta_description','rank_weight','tags','allow_comments','allow_attachment','comment_api','show_offer','show_detail','allow_enquiry','allow_saleable','show_price','show_manufacturer_detail','show_supplier_detail','add_custom_button','custom_button_text','custom_button_url','enquiry_send_to_self','enquiry_send_to_supplier','enquiry_send_to_manufacturer','product_enquiry_auto_reply','is_publish','new','feature','latest','mostviewed'),array('name','sku','sale_price'));
		$crud->add('Controller_FormBeautifier');
		// if($crud->isEditing()){
		// 	$model_cat=$crud->form->getElement('categoryproduct_id')->getModel();
		// 	$model_cat->title_field="category_name";
		// }
				
		if($crud->grid){
			$crud->grid->addColumn('expander','details');
			$crud->grid->addColumn('expander','categories');
			$crud->grid->addQuickSearch(array('sku','name','sale_price'));
			$crud->grid->addPaginator($ipp=50);
		}

		$crud->addRef('xShop/ProductImages',array('label'=>'Images'));
		$crud->addRef('xShop/CustomFields',array('label'=>'Custome Fields'));
		$crud->addRef('xShop/Attachments',array('label'=>'Attachment'));
		
		// $c_col->add('View_Info')->set('Category');
		// $crud1=$c_col->add('CRUD');
		// $m=$this->add('xShop/Model_CategoryProduct');
		// $crud1->setModel($m);
	}

	function page_categories(){

		$pro_id=$this->api->stickyGET('xshop_products_id');			
		
		$grid=$this->add('Grid');
		$cat_model=$this->add('xShop/Model_Category');
		$cat_model->addCondition('is_active',true);
		$cat_model->addExpression('category_group')->set(function($m,$q){
			return $m->refSQL('categorygroup_id')->fieldQuery('name');
		});
		
		$cat_model->addExpression('status')->set(function($m,$q)use($pro_id){
			$category_prod_model=$m->add('xShop/Model_CategoryProduct',array('table_alias'=>'c'));
			$category_prod_model->addCondition('category_id',$q->getField('id'));
			$category_prod_model->addCondition('product_id',$pro_id);
			return $category_prod_model->count();
		})->type('boolean');

		
		$grid->setModel($cat_model,array('category_name','category_group','status'));
		// $grid->addSelectable('category_name');
		$grid->addColumn('Button','save','Swap Select');
		// $grid->addColumn('Button','cancle');
		// $save->js('click',$grid->reload())->univ()->successMessage('Save Changes Successfully',array())->execute();

		if($_GET['save']){
			
			$catpro_model=$this->add('xShop/Model_CategoryProduct');
			$status=$catpro_model->getStatus($_GET['save'],$pro_id);
			if($status){
				//if(categoryproduct model has record)
				//to unactive karo loaded record
				$catpro_model->swapActive($status);
			}
			else{
				//if categoryproduct model has no record then create new entry			
				$catpro_model->createNew($_GET['save'],$pro_id);
			}
			
			$product_model=$this->add('xShop/Model_Product');
			$product_model->load($pro_id);
			$product_model->updateSearchString($pro_id);

			$grid->js(null,$this->js()->univ()->successMessage('Save Changes'))->reload()->execute();		
		}

		$grid->addQuickSearch(array('category_name'));
		$grid->addPaginator($ipp=20);
	}

	function page_details(){
		$pro_id=$this->api->stickyGET('xshop_products_id');	
		$product_model = $this->add('xShop/Model_Product');
		$product_model->getProduct($pro_id);
		$product_view = $this->add('xShop/View_ProductDetail');
		$product_view->setModel($product_model);	
	}

}