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

		$this->hasMany('xShop/OrderItemDepartmentalStatus','orderitem_id');

		$this->add('dynamic_model/Controller_AutoCreator');
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

}

