<?php

namespace xShop;

class View_Tools_Product extends \componentBase\View_Component{
	function init(){
		parent::init();
		
		//for grid column width
		$this->api->js()->_load('xShop-js');
		$this->api->stickyGET('search');

		if(!$this->html_attributes['xshop-grid-column'])
			$column_width='25';
		else
			$column_width=100 / $this->html_attributes['xshop-grid-column'];

		$product_lister_view=$this->add('xShop/View_Lister_Product',
								array('xshop_product_display_layout'=>$this->html_attributes['xshop_productlayout'],
										'xshop_product_grid_column'=>$column_width,
										'xshop_product_topbar'=>$this->html_attributes['xshop_product_topbar'],
										'xshop_product_categorygroup_id'=>$this->html_attributes['xshop_product_categorygroup_id']?$this->html_attributes['xshop_product_categorygroup_id']:0,
										'fancy_box_on'=>$this->html_attributes['xshop_product_fancy_box'],										
										'item_detail_url'=>$this->html_attributes['xshop_product_hover_detail_page'],										
										'item_detail_onhover'=>$this->html_attributes['xshop_product_hover'],
										'xshop_product_detail_on_image_click'=>$this->html_attributes['xshop_product_detail_on_image_click']										
										));

		$product_model=$this->add('xShop/Model_Product');
		$product_model->addCondition('is_publish',true);
				
		$p_type=$this->html_attributes['xshop_producttype'];
		$cg_id=$this->html_attributes['xshop_product_categorygroup_id']?$this->html_attributes['xshop_product_categorygroup_id']:0;
		// Selection of Product according to options 
		// if $p_type is null
		// then default value

		if($p_type and $p_type !='all')
			$product_model->addcondition($p_type,true);
		//todo select product according to category group id
		if(!$cg_id)
			$this->add('View_Error')->set('Please Select category Group');
		// Product Model according to category grooup
		$p_join=$product_model->leftJoin('xshop_category_product.product_id','id');
		$cp_join=$p_join->leftJoin('xshop_categories','category_id');
		$p_join->addField('category_id');
		$cp_join->hasOne('xShop/CategoryGroup','categorygroup_id');
		$product_model->addCondition('categorygroup_id',$cg_id);
		//end of product model accordiing to category group

		if($_GET['category_id']){
			$product_model->addCondition('category_id',$_GET['category_id']);
		}
		
		
		if($search=$_GET['search']){		
			// $result->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION)');
			$product_model->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN BOOLEAN MODE)');
			$product_model->addCondition('Relevance','>',0);
			// throw new \Exception($product_model['Relevance']);
	 		$product_model->setOrder('Relevance','Desc');
		}

		if($product_model->count()->getOne() != 0)
			$product_lister_view->template->del('no_record_found');		
		
		$product_model->_dsql()->group('product_id');
		$product_lister_view->setModel($product_model);
		
		// if($product_model->count()->getOne() != 0)
		// 	$product_lister_view->template->del('no_record_found');			

		$paginator = $product_lister_view->add('Paginator');
		$paginator->ipp(9);

		//loading custom CSS file	
		$product_css = 'epans/'.$this->api->current_website['name'].'/xshopcustom.css';
		$this->api->template->appendHTML('js_include','<link id="xshop-product-customcss-link" type="text/css" href="'.$product_css.'" rel="stylesheet" />'."\n");

	}

	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html

}