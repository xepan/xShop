<?php

namespace xShop;

class View_Tools_Item extends \componentBase\View_Component{
	function init(){
		parent::init();
		
		$this->api->stickyGET('search');
		$this->api->stickyGET('category_id');

		$application_id=$this->html_attributes['xshop_item_application_id']?$this->html_attributes['xshop_item_application_id']:0;
		if(!$application_id){
			$this->add('View_Error')->set('Please Select category Group');
			return;
		}
			
		//Item Colunm Width 
		$width = 12;
		if($this->html_attributes['xshop-grid-column']){	
			$width = 12 / $this->html_attributes['xshop-grid-column'];
		}
		$column_width = 'col-md-'.$width.' col-sm-'.$width.' col-xl-'.$width;

		$item_lister_view=$this->add('xShop/View_Lister_Item',
								array('xshop_item_display_layout'=>$this->html_attributes['xshop_itemlayout'],
										'xshop_item_grid_column'=>$column_width,
										'xshop_item_topbar'=>$this->html_attributes['xshop_item_topbar'],
										'xshop_item_categorygroup_id'=>$this->html_attributes['xshop_item_categorygroup_id']?$this->html_attributes['xshop_item_categorygroup_id']:0,
										'fancy_box_on'=>$this->html_attributes['xshop_item_fancy_box'],							
										'item_detail_url'=>$this->html_attributes['xshop_item_hover_detail_page'],					
										'item_detail_onclick'=>$this->html_attributes['xshop_item_hover'],
										'item_short_description'=>$this->html_attributes['xshop_item_short_description'],
										'xshop_item_detail_on_image_click'=>$this->html_attributes['xshop_item_detail_on_image_click']
										));

		$item_model=$this->add('xShop/Model_Item');
		$item_model->addCondition('is_publish',true);
		$item_model->addCondition('application_id',$application_id);
		$item_type=$this->html_attributes['xshop_itemtype'];
		// Selection of item according to options if $item_type is null then default value All
		if($item_type and $item_type !='all')
			$item_model->addcondition($item_type,true);
		// item Model according to application
		$item_join=$item_model->leftJoin('xshop_category_item.item_id','id');
		$item_join->addField('category_id');
		//Category Wise item Loading
		if($_GET['xshop_category_id']){
			$item_model->addCondition('category_id',$_GET['xshop_category_id']);
		}
		//-------------------------------------
		
		//Search Filter				
		if($search=$_GET['search']){		
			$item_model->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN BOOLEAN MODE)');
			$item_model->addCondition('Relevance','>',0);
	 		$item_model->setOrder('Relevance','Desc');
		}
		//---------------------

		if($item_model->count()->getOne() != 0)
			$item_lister_view->template->del('no_record_found');
		
		$item_model->_dsql()->group('item_id'); // Multiple category association shows multiple times item so .. grouped
		$item_model->setOrder('created_at','desc');
		
		$item_lister_view->setModel($item_model);
		
		//Add Painator to item List
		// $paginator = $item_lister_view->add('Paginator');
		// $paginator->ipp($this->html_attributes['xshop_item_paginator']?:12);
		// ------------------------------------------

		//loading custom CSS file	
		$item_css = 'epans/'.$this->api->current_website['name'].'/xshopcustom.css';
		$this->api->template->appendHTML('js_include','<link id="xshop-item-customcss-link" type="text/css" href="'.$item_css.'" rel="stylesheet" />'."\n");
		// -------------------------------------------
	}

	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}