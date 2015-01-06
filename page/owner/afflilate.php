<?php


class page_xShop_page_owner_afflilate extends page_xShop_page_owner_main{
	function init(){
		parent::init();

		//$partyitemsvp = $this->partyitemsvp();
		//View badge
		// $m = $this->add('xShop/Model_Affiliate');
		// $bg=$this->app->layout->add('View_BadgeGroup');		
		// $total_manufacturer_item=$this->add('xShop/Model_Manufacturer')->count()->getOne();
		// $total_supplier_item=$this->add('xShop/Model_Supplier')->count()->getOne();
		// $bg=$this->app->layout->add('View_BadgeGroup');
		// $v=$bg->add('View_Badge')->set('Total Manufacturer Item')->setCount($total_manufacturer_item)->setCountSwatch('ink');
		// $v=$bg->add('View_Badge')->set('Total Supplier Item')->setCount($total_supplier_item)->setCountSwatch('green');
		
		// $party_model = $this->add('xShop/Model_Affiliate');
		// $crud=$this->app->layout->add('CRUD');
		// // $party_model->removeElement('epan_id');
		// $crud->setModel($party_model);
		
		// $item_category_model = $this->add('xShop/Model_CategoryItem');
		// $item_category_model->hasMany('xShop/Item','item_category_id');
		
		// $crud->add('Controller_FormBeautifier');
		// if(!$crud->isEditing()){
		// 	$g = $crud->grid;
		// 	$g->addMethod('format_items',function($g,$f)use($partyitemsvp){
		// 		$g->current_row_html[$f]= '<a href="javascript:void(0)" onclick="'.$g->js()->univ()->frameURL('Items For "'.$g->model['name'].'"',$g->api->url($partyitemsvp,array('affiliate_id'=>$g->model->id))).'">'.$g->current_row[$f].'</a>';
		// 	$g->addFormatter('items','items');
		// 	});
		// 	$g->addQuickSearch(array('name','mobile_no','address'));
		// 	$g->addPaginator($ipp=50);
		// }

		$cols = $this->app->layout->add('Columns');
		$type_col = $cols->addColumn(3);
		$aff_col = $cols->addColumn(9);
		$afflilate_type_model = $this->add('xShop/Model_AffiliateType');
		$type_crud=$type_col->add('CRUD');

		$type_crud->setModel($afflilate_type_model);//,array('name'));
		$afflilate_model = $this->add('xShop/Model_Affiliate');

		if(!$type_crud->isEditing()){
			$g=$type_crud->grid;
			$g->addMethod('format_filterafflilate',function($g,$f)use($aff_col){
				$g->current_row_html[$f]='<a href="javascript:void(0)" onclick="'. $aff_col->js()->reload(array('afflilatetype_id'=>$g->model->id)) .'">'.$g->current_row[$f].'</a>';
			});
			$g->addFormatter('name','filterafflilate');
			$g->add_sno();
		}

		if($_GET['afflilatetype_id']){
			$this->api->stickyGET('afflilatetype_id');
			$filter_box = $aff_col->add('View_Box')->setHTML(' Affiliate for <b>'. $afflilate_type_model->load($_GET['afflilatetype_id'])->get('name').'</b>' );
			
			$filter_box->add('Icon',null,'Button')
            ->addComponents(array('size'=>'mega'))
            ->set('cancel-1')
            ->addStyle(array('cursor'=>'pointer'))
            ->on('click',function($js) use($filter_box,$aff_col) {
                $filter_box->api->stickyForget('afflilatetype_id');
                return $filter_box->js(null,$aff_col->js()->reload())->hide()->execute();
            });
            
			$afflilate_model->addCondition('affiliatetype_id',$_GET['afflilatetype_id']);
		}

		$aff_crud=$aff_col->add('CRUD');

		$aff_crud->setModel($afflilate_model);//,array('name'));

	}
    

}

		
