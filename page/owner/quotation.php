<?php

class page_xShop_page_owner_quotation extends page_xShop_page_owner_main{
	function page_index(){

		$tab = $this->app->layout->add('Tabs');
			$draft=$tab->addTab('Draft');
				$crud=$draft->add('CRUD');
				$draft_model=$this->add('xShop/Model_Quotation');	
				$crud->setModel($draft_model);
				$draft_model->addCondition('status','draft');
			
				$p=$crud->addFrame('communication_frame');
				if($p) $p->add('View_Error')->set($crud->id);
			
			
			$approved=$tab->addTab('Approved');
				$approved_crud=$approved->add('CRUD');
				$approved_crud->setModel('xShop/ApprovedQuotation');
				$approved_crud->addAction('creatOrder',array('toolbar'=>false));
			
			$re_design=$tab->addTab('Redesign');
				$redesign_crud=$re_design->add('CRUD');
				$redesign_crud->setModel('xShop/RedesignQuotation');
			
			$submit=$tab->addTab('Submit');
				$submit_model=$this->add('xShop/Model_Quotation');
			
			$sub_crud=$submit->add('CRUD');
				$sub_crud->setModel($submit_model);

		$crud->addAction('reject',array('toolbar'=>false));
		$crud->addAction('approved',array('toolbar'=>false));
		$crud->addAction('submit',array('toolbar'=>false));
		$crud->addAction('sendMail',array('toolbar'=>false));

		if(!$crud->isEditing()){
			$grid=$crud->grid;
			$grid->addColumn('Expander','item');
		}

	}

	function page_item(){
		$quotation_id=$this->api->stickyGET('xshop_quotation_id');
		$item_model = $this->add('xShop/Model_QuotationItem');
		$item_model->addCondition('quotation_id',$quotation_id);
		$item_crud=$this->add('CRUD');
		$item_crud->setModel($item_model);
	}
}