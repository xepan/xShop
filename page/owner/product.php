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

		$crud=$p_col->add('CRUD');
		$crud->setModel($model);

		// if($crud->isEditing()){
		// 	$model_cat=$crud->form->getElement('categoryproduct_id')->getModel();
		// 	$model_cat->title_field="category_name";
		// }
				
		if($crud->grid){
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

}