<?php

class page_xShop_page_owner_item extends page_xShop_page_owner_main{

	function init(){
		parent::init();
		
		$application_id=$this->api->recall('xshop_application_id');		
		
		$cols = $this->app->layout->add('Columns');
		$cat_col = $cols->addColumn(3);
		$item_col = $cols->addColumn(9);
		
		//Category
		// $cat_col->add('xShop/View_Badges_CategoryPage');
		$category_model = $cat_col->add('xShop/Model_Category');
		$category_model->addCondition('application_id',$application_id);	
		$category_model->setOrder('id','desc');
		
		//category form
		$form = $cat_col->add('Form',null,null,array('form/stacked'));
		$cat_f = $form->addField('dropdown','category')->setEmptyText('All');;
		$cat_f->setModel($category_model);
		$form->addSubmit()->set('Filter');

		//Item
		$item_model = $cat_col->add('xShop/Model_Item');
		$item_model = $item_model->applicationItems($application_id);
		$item_crud = $cat_col->add('CRUD',array('grid_class'=>'xShop/Grid_Item'));

		if($form->isSubmitted()){
			$form->js(null,$item_crud->js()->reload(array('category_id'=>$form['category'])))->execute();
			// $filter_box = $cat_col->add('View_Box')->setHTML('Items for <b>'. $this->add('xShop/Model_Category')->load($_GET['category_id'])->get('name').'</b>' );
			// $filter_box->add('Icon',null,'Button')
   //          ->addComponents(array('size'=>'mega'))
   //          ->set('cancel-1')
   //          ->addStyle(array('cursor'=>'pointer'))
   //          ->on('click',function($js) use($filter_box,$item_col) {
   //              return $filter_box->js(null,$cat_col->js()->reload())->hide()->execute();
   //          });
		}

		if($_GET['category_id']){
			$this->api->stickyGET('category_id');
			$cat_item_join = $item_model->join('xshop_category_item.item_id');
			$cat_item_join->addField('category_id');
			$cat_item_join->addField('is_associate');
			$item_model->addCondition('category_id',$_GET['category_id']);
			$item_model->addCondition('is_associate',true);
		}

		$item_crud->setModel($item_model,array('name','sku','is_publish','short_description','description','default_qty','default_qty_unit','original_price','sale_price','rank_weight','created_at','expiry_date','allow_attachment','allow_enquiry','allow_saleable','show_offer','show_detail','show_price','show_manufacturer_detail','show_supplier_detail','new','feature','latest','mostviewed','enquiry_send_to_admin','item_enquiry_auto_reply','allow_comments','comment_api','add_custom_button','custom_button_text','custom_button_url','meta_title','meta_description','tags','offer_id','offer_position','is_designable','designer_id','is_template'),array('name'));

		if(!$item_crud->isEditing()){
			$item_crud->grid->addMethod('format_name',function($g,$f)use($item_col){
				$g->current_row_html[$f]='<a href="javascript:void(0)" onclick="'.$item_col->js()->reload(array('item_id'=>$g->model->id)).'">'.$g->current_row[$f].'</a>';
			});
			$item_crud->grid->addFormatter('name','name');
		}

		//
		$item_col->add('xShop/View_Badges_ItemPage');
		if($_GET['item_id']){
			$this->api->stickyGET('item_id');
			$filter_box = $item_col->add('View_Box')->setHTML('Item :: '.$this->add('xShop/Model_Item')->load($_GET['item_id'])->get('name'));
			$filter_box->add('Icon',null,'Button')
            ->addComponents(array('size'=>'mega'))
            ->set('cancel-1')
            ->addStyle(array('cursor'=>'pointer'))
            ->on('click',function($js) use($filter_box,$item_col) {
                $filter_box->api->stickyForget('item_id');
                return $filter_box->js(null,$item_col->js()->reload())->hide()->execute();
            });

			$tab = $item_col->add('Tabs');
			$tab->addTabURL('xShop/page/owner/item_basic','Basic',array('item_id'));
			$tab->addTabURL('xShop/page/owner/item_attributes','Attributes',array('item_id'));
			$tab->addTabURL('xShop/page/owner/item_qtyandprice','Qty & Price',array('item_id'));
			$tab->addTabURL('xShop/page/owner/item_media','Media',array('item_id'));
			$tab->addTabURL('xShop/page/owner/item_category','Category',array('item_id'));
			$tab->addTabURL('xShop/page/owner/item_affliate','Affiliate',array('item_id'));
			$tab->addTabURL('xShop/page/owner/item_preview','Preview',array('item_id'));
			$tab->addTabURL('xShop/page/owner/item_seo','SEO',array('item_id'));
			$tab->addTabURL('xShop/page/owner/item_stock','Stock',array('item_id'));
		}else{
			$item_col->add('View_Warning')->set('Select any one Item');
		}

	}

	
	function page_details(){
		$item_id=$this->api->stickyGET('xshop_items_id');	
		$item_model = $this->add('xShop/Model_Item');
		$item_model->getItem($item_id);
		$product_view = $this->add('xShop/View_ItemDetail');
		$product_view->setModel($item_model);
	}

}