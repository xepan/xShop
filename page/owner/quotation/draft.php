<?php

class page_xShop_page_owner_quotation_draft extends page_xShop_page_owner_main{

	function page_index(){

		$draft_quotation_model = $this->add('xShop/Model_Quotation_Draft');

		$draft_quotation_model->getElement('oppertunity_id')->system(true);

		$crud=$this->add('CRUD',array('grid_class'=>'xShop/Grid_Quotation'));
		$crud->setModel($draft_quotation_model);

		$crud->add('xHR/Controller_Acl');

		if(!$crud->isEditing()){
			$grid=$crud->grid;
			$grid->addMethod('format_to',function($g,$f){
				$g->current_row[$f]=$g->current_row['lead']? '(L) '.$g->current_row['lead']: '(C) '.$g->current_row	['customer'];
			});
			$grid->addColumn('to','to');
			$grid->removeColumn('lead');
			$grid->removeColumn('customer');
		}
			

		}

	}

