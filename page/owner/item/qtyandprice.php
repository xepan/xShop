<?php

class page_xShop_page_owner_item_qtyandprice extends Page{

	function init(){
		parent::init();
		$this->api->stickyGET('item_id');
	}
	
	function page_index(){
		$item = $this->add('xShop/Model_Item')->load($_GET['item_id']);
		// $this->add('View_Info')->set('Display Basic Price For Item Here Again As Form .. updatable');
		
		$form = $this->add('Form_Stacked');
		$form->setModel($item,array('minimum_order_qty','maximum_order_qty','qty_unit','qty_from_set_only'));
		$form->addSubmit()->set('Update');

		if($form->isSubmitted()){
			$form->update();
			$form->js()->univ()->successMessage('Item Updtaed')->execute();
		}
		$form->add('Controller_FormBeautifier');

		$crud = $this->add('CRUD');
		$crud->setModel($item->ref('xShop/QuantitySet'));
		
		if(!$crud->isEditing()){
			$g = $crud->grid;
			$g->addColumn('expander','condition');
		}

	}

	function page_condition(){
		$item_id = $_GET['item_id'];

		$item_model = $this->add('xShop/Model_Item')->load($_GET['item_id']);
        $application_id = $this->api->recall('xshop_application_id');
		$qs_id = $this->api->stickyGET('xshop_item_quantity_sets');
		
		$qty_set_condition_model = $this->add('xShop/Model_QuantitySetCondition')->addCondition('quantityset_id',$qs_id);

		$crud = $this->add('CRUD');
		$crud->setModel($qty_set_condition_model);

        /*
            Get All item's custom fields and let select its value
            Must have extra value called '*' or Any
           
        */    
        if($crud->isEditing()){
            $asso_model = $crud->form->getElement('custom_field_value_id')->getModel();
            // add  expression 'Custome_Field/Value' style and make it title field
            
            $asso_model->addExpression('field_name_with_value')->set(function($m,$q)use($crud){
            	// ME = CustomeFieldValue Model
            	// custom_field_model
            	// joined with values
            	// whose values_id = my id
            	// Limit 1 
            	// get custom field name
				return "(concat((".$m->refSQL('itemcustomfiledasso_id')->_dsql()->del('fields')->field('name')->render()."),' :: ',".$q->getField('name')."))";
			});
			$asso_model->title_field='field_name_with_value';
			$cus_field_j = $asso_model->join('xshop_category_item_customfields','itemcustomfiledasso_id');
			$cus_field_j->addField('item_id');
   //          $asso_model->addExpression('field_name_with_value')->set(function($m,$q)use($item_id){
   //              // return $m->refSQL('customefield_id')->fieldQuery('name');
   //              $custome_field_m = $m->add('xShop/Model_CustomFields',array('table_alias'=>'tcf'));
   //              $values_j = $custome_field_m->join('xshop_category_item_customfields.customfield_id');
   //              $values_j->addField('item_id');
   //              $values_j->addField('is_active');
   //              $custome_field_m->addCondition('item_id',$item_id);               
   //              $custome_field_m->addCondition('is_active',true);
								
   //              return "(concat('".$custome_field_m->_dsql()->del('fields')->field('name')."',' :: ','value'))";
   //          });
			
			// // $asso_model->title_field='field_name_with_value';
			$asso_model->addCondition('item_id',$item_id)
						->addCondition('is_active',true);
           
        }else{
            // $custom_values_asso_model = $crud->getModel()->getElement('itemcustomfiledasso_id')->getModel();
            // // add  expression 'Custome_Field/Value' style and make it title field
            // $custom_values_asso_model->addExpression('field_name_with_value')->set(function($m,$q)use($item_id){

            //     // return $m->refSQL('customefield_id')->fieldQuery('name');

            //     $custome_field_m = $m->add('xShop/Model_CustomFields',array('table_alias'=>'tcf'));
            //     $values_j = $custome_field_m->join('xshop_category_item_customfields.customfield_id');
            //     $values_j->addField('item_id');
            //     $custome_field_m->addCondition('item_id',$item_id);               

            //     return "(concat('".$custome_field_m->_dsql()->del('fields')->field('name')."',' :: ',".$q->getField('name')."))";
            // });
            // $custom_values_asso_model->title_field='field_name_with_value';
        }		
	}

}