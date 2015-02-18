<?php

class page_xShop_page_owner_item_qtyandprice extends page_xShop_page_owner_main{

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
		$crud->setModel($item->ref('xShop/QuantitySet')->setOrder(array('custom_fields_conditioned desc','qty desc','is_default asc')),array('name','qty','price'),array('name','qty','old_price','price','is_default','custom_fields_conditioned'));
		
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
		$crud->setModel($qty_set_condition_model);//,array('custom_field_value_id'),array('custom_field_value'));

        /*
            Get All item's custom fields and let select its value
            Must have extra value called '*' or Any
           
        */    
        if($crud->isEditing()){
            $custom_values_model = $crud->form->getElement('custom_field_value_id')->getModel();
			$custom_values_model->addCondition('item_id',$item_id)
						->addCondition('is_active',true);           
        }
	}

}