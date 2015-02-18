<?php

namespace xShop;

class Model_OrderDetails extends \Model_Table{
	public $table='xshop_orderDetails';

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		$this->hasOne('xShop/Order','order_id');
		$this->hasOne('xShop/Item','item_id')->display(array('form'=>'autocomplete/Basic'));

		$this->addField('qty')->type('money');
		$this->addField('unit')->type('money');
		$this->addField('rate')->type('money');
		$this->addField('amount')->type('money');
		$this->addField('custom_fields')->type('text')->system(false);

		$this->addExpression('created_by_id')->set(function($m,$q){
			return $m->refSQL('order_id')->fieldQuery('created_by_id');
		});

		$this->hasMany('xShop/OrderItemDepartmentalStatus','orderitem_id');

		$this->addHook('afterInsert',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function afterInsert($obj,$new_id){
		$new_order_details = $this->add('xShop/Model_OrderDetails')->load($new_id);
		if($new_order_details->order()->isFromOnline()){
			$item_departments = $new_order_details->item()->associatedDepartments();
			foreach($item_departments as $dept){
				$new_order_details->addToDepartment($dept);
			}
		}
	}

	function departmentStatus($department){
		$relation = $this->ref('xShop/OrderItemDepartmentalStatus')->addCondition('department_id',$department->id);
		if($relation->tryLoadAny()->loaded())
			return $relation;
		else
			return false;		
	}

	function addToDepartment($department){
		$relation = $this->ref('xShop/OrderItemDepartmentalStatus')->addCondition('department_id',$department->id);
		$relation->tryLoadAny()->save();
	}

	function removeFromDepartment($department){
		$relation = $this->ref('xShop/OrderItemDepartmentalStatus')->addCondition('department_id',$department->id);
		if($relation->tryLoadAny()->loaded())
			$relation->delete();
	}

	function item(){
		return $this->ref('item_id');
	}

	function order(){
		return $this->ref('order_id');
	}

	function deptartmentalStatus(){
		return $this->ref('xShop/OrderItemDepartmentalStatus');
	}

	function nextDept(){
		foreach ($dept_status=$this->deptartmentalStatus() as $ds) {
			if(!$ds['status']) return $ds;
		}
	}

}

