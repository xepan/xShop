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
		$form->setModel($item,array('original_price','sale_price','minimum_order_qty','maximum_order_qty','qty_unit','qty_from_set_only'));
		$form->addSubmit()->set('Update');

		if($form->isSubmitted()){
			$form->update();
			$form->js(null,$this->js()->reload())->univ()->successMessage('Item Updtaed')->execute();
		}
		$form->add('Controller_FormBeautifier');

		$crud = $this->add('CRUD');
		$crud->setModel($item->ref('xShop/QuantitySet'),array('name','qty','price'),array('name','qty','old_price','price','is_default'));
		
		if(!$crud->isEditing()){
			$g = $crud->grid;
			$g->addColumn('expander','conditions');

			$g->addMethod('format_image_thumbnail',function($g,$f){
				if($g->model['is_default'])
					$g->current_row_html[$f] = "";
			});
			$g->addFormatter('conditions','image_thumbnail');
		}
	}

	function page_conditions(){
		$item_id = $_GET['item_id'];

		$item_model = $this->add('xShop/Model_Item')->load($_GET['item_id']);
        $application_id = $this->api->recall('xshop_application_id');
		$qs_id = $this->api->stickyGET('xshop_item_quantity_sets_id');
		
		$qty_set_condition_model = $this->add('xShop/Model_QuantitySetCondition')
							->addCondition('quantityset_id',$qs_id);

		$crud = $this->add('CRUD');
		$crud->setModel($qty_set_condition_model,array('custom_field_value_id'),array('custom_field_value'));

        /*
            Get All item's custom fields and let select its value
            Must have extra value called '*' or Any
           
        */    
        if($crud->isEditing()){
            $custom_values_model = $crud->form->getElement('custom_field_value_id')->getModel();
            // add  expression 'Custome_Field/Value' style and make it title field
            
            $custom_values_model->addExpression('field_name_with_value')->set(function($m,$q){
				$custome_field_m = $m->refSQL('customfield_id');
				return "(concat((".$custome_field_m->_dsql()->del('fields')->field('name')->render()."),' :: ',".$q->getField('name')."))";
			});

			$custom_values_model->title_field='field_name_with_value';
			$cus_field_j = $custom_values_model->join('xshop_category_item_customfields','itemcustomfiledasso_id');
			$cus_field_j->addField('item_id');
			$custom_values_model->addCondition('item_id',$item_id)
						->addCondition('is_active',true);           
        }else{
            
            $custom_values_model = $crud->getModel()->getElement('custom_field_value_id')->getModel();
            // add  expression 'Custome_Field/Value' style and make it title field
            $custom_values_model->addExpression('field_name_with_value')->set(function($m,$q){
				$custome_field_m = $m->refSQL('customfield_id');
				return "(concat((".$custome_field_m->_dsql()->del('fields')->field('name')->render()."),' :: ',".$q->getField('name')."))";
			});
            $custom_values_model->title_field='field_name_with_value';

        }		
	}

}