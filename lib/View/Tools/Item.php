<?php

namespace xShop;

class View_Tools_Item extends \componentBase\View_ServerSideComponent{
	function init(){
		parent::init();
		
		$this->api->stickyGET('search');
		$this->api->stickyGET('category_id');

		$application_id=$this->html_attributes['xshop_item_application_id']?$this->html_attributes['xshop_item_application_id']:0;
		if(!$application_id){
			$this->add('View_Error')->set('Please Select Application');
			return;
		}
			
		//Item Colunm Width 
		$width = 12;
		if($this->html_attributes['xshop-grid-column']){	
			$width = 12 / $this->html_attributes['xshop-grid-column'];
		}
		$this->html_attributes['column_width']= 'col-md-'.$width.' col-sm-'.$width.' col-xl-'.$width;

		$item_lister_view=$this->add('xShop/View_Lister_Item',array('html_attributes'=>$this->html_attributes));

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
		$item_join->addField('is_associate');
		//Category Wise item Loading
		if($_GET['xsnb_category_id']){
			// if Category Parent ho to iske child me show karo
			$cats = $this->add('xShop/Model_Category')->load($_GET['xsnb_category_id']);
			$cats_arry = $cats->getSubCategory();
			$item_model->addCondition('category_id','in',$cats_arry);
			$item_model->addCondition('is_associate',true);
		}
		//-------------------------------------
		
		//Search Filter				
		if($search=$_GET['search']){		
			$item_model->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN BOOLEAN MODE)');
			$item_model->addCondition('Relevance','>',0);
	 		$item_model->setOrder('Relevance','Desc');
		}
		//---------------------

		
		$item_model->_dsql()->group('item_id'); // Multiple category association shows multiple times item so .. grouped
		$item_model->_dsql()->having('item_id','<>',null); 
		$item_model->setOrder('created_at','desc');
		
		// if($item_model->count()->getOne() != 0)
			// $item_lister_view->template->del('no_record_found');

		$item_lister_view->setModel($item_model);
		
		// Add Painator to item List
		// $paginator = $item_lister_view->add('Paginator');
		// $paginator->ipp($this->html_attributes['xshop_item_paginator']?:12);
		// ------------------------------------------

		//loading custom CSS file
		$item_css = 'epans'.DS.$this->api->current_website['name'].DS.$this->html_attributes['xshop_itemlayout'].".css";
		$this->api->template->appendHTML('js_include','<link id="xshop-item-customcss-link" type="text/css" href="'.$item_css.'" rel="stylesheet" />'."\n");
		// -------------------------------------------
	}

	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}