<?php
class page_xShop_page_owner_oppertunity extends page_xShop_page_owner_main{
	function init(){
		parent::init();

		$oppertunity_model = $this->add('xShop/Model_Oppertunity');

		$crud=$this->app->layout->add('CRUD');
		$crud->setModel($oppertunity_model);


		if(!$crud->isEditing()){
			$grid =  $crud->grid;
			$grid->addColumn('text','last_contacted');

			$grid->addMethod('format_from',function($g,$f){
				$g->current_row[$f] = $g->current_row['lead']? '(L) ' . $g->current_row['lead'] :  '(C) ' . $g->current_row['customer'];
			});

			$grid->addColumn('from','from');

			$grid->removeColumn('lead');
			$grid->removeColumn('customer');
		}

		$crud->add('xHR/Controller_Acl');
		$p=$crud->addFrame('quotation',array('icon'=>'plus'));
		
		if($p){
			
			$model_quotation=$p->add('xShop/Model_Quotation');
			$model_quotation->addCondition('oppertunity_id',$crud->id);

			$c = $p->add('CRUD',array('grid_class'=>'xShop/Grid_Quotation'));

			if($c->isEditing()){
				
				$oppertunity_model->load($crud->id);			
				if($oppertunity_model['lead_id']){
					$model_quotation->addCondition('lead_id',$oppertunity_model['lead_id']);
					$model_quotation->getElement('customer_id')->system(true);
				}

				if($oppertunity_model['customer_id']){
					$model_quotation->addCondition('customer_id',$oppertunity_model['customer_id']);
					$model_quotation->getElement('lead_id')->system(true);
				}
			}
			
			$c->setModel($model_quotation);

		}
	}
}	

