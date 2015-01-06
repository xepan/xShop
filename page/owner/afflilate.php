<?php


class page_xShop_page_owner_afflilate extends page_xShop_page_owner_main{
	function init(){
		parent::init();

		$partyitemsvp = $this->partyitemsvp();
		//View badge
		$m = $this->add('xShop/Model_Afflilate');
		$bg=$this->app->layout->add('View_BadgeGroup');		
		$total_manufacturer_item=$this->add('xShop/Model_Manufacturer')->count()->getOne();
		$total_supplier_item=$this->add('xShop/Model_Supplier')->count()->getOne();
		$bg=$this->app->layout->add('View_BadgeGroup');
		$v=$bg->add('View_Badge')->set('Total Manufacturer Item')->setCount($total_manufacturer_item)->setCountSwatch('ink');
		$v=$bg->add('View_Badge')->set('Total Supplier Item')->setCount($total_supplier_item)->setCountSwatch('green');
		
		$party_model = $this->add('xShop/Model_Afflilate');
		$crud=$this->app->layout->add('CRUD');
		// $party_model->removeElement('epan_id');
		$crud->setModel($party_model);
		
		$item_category_model = $this->add('xShop/Model_CategoryItem');
		$item_category_model->hasMany('xShop/Item','item_category_id');
		
		// $crud->add('Controller_FormBeautifier');
		if(!$crud->isEditing()){
			$g = $crud->grid;
			$g->addMethod('format_items',function($g,$f)use($partyitemsvp){
				$g->current_row_html[$f]= '<a href="javascript:void(0)" onclick="'.$g->js()->univ()->frameURL('Items For "'.$g->model['name'].'"',$g->api->url($partyitemsvp,array('party_id'=>$g->model->id))).'">'.$g->current_row[$f].'</a>';
			});
			$g->addFormatter('items','items');
			$g->addQuickSearch(array('name','mobile_no','address'));
			$g->addPaginator($ipp=50);
		}

	}

	function partyitemsvp(){
		
      		$g=$this->add('Grid');
		 	$g->setModel('xShop/Item',array('name','sku','is_publish','sale_price'));
		
		} 
     


}

		
