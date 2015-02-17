<?php

namespace xShop;

class Form_OrderItem extends \Form_Stacked {

	function init(){
		parent::init();

		$this->addHook('submit',function($form){
			$this_order_item = $this->model;
			$depatments = $this->add('xHR/Model_Department');
			foreach ($depatments as $dept) {
            	if($form['dept_'.$dept->id]){            		
            		$this_order_item->addToDepartment($dept);
            	}else{
            		$this_order_item->removeFromDepartment($dept);
            	}
       		}
		});
	}

	function setModel($m,$fields=null){
		parent::setModel($m,$fields);
		$this->addDeptFields();
		$this->model->addHook('afterLoad',array($this,'setDepartmentFields'));

		$item_field = $this->getElement('item_id');
		$f= $item_field->other_field;

		$btn = $item_field->other_field->belowField()->add('Button')->set('CustomFields');
		$btn->js('click',$this->js()->univ()->frameURL('Custome Field Values',$this->api->url('xShop_page_owner_order_customfields')));

		return $this->model;
	}

	function addDeptFields(){
        $depatments = $this->add('xHR/Model_Department');
        foreach ($depatments as $dept) {
            $f= $this->addField('CheckBox','dept_'.$dept->id ,$depatments['name']);
            if($this->model->loaded()){
            	$status = $this->model->departmentStatus($dept);
            	$f->set($status);
            }
        }
	}

	function setDepartmentFields(){
        $depatments = $this->add('xHR/Model_Department');
        foreach ($depatments as $dept) {
            $f= $this->getElement('dept_'.$dept->id);
        	$status = $this->model->departmentStatus($dept);
        	$f->set($status);
        }
	}

	function addCustomFields(){
		// in case of add and edit
	}

	function setCustomFields(){
		// In case of edit
	}
}