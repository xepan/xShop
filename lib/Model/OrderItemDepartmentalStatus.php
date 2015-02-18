<?php

namespace xShop;

class Model_OrderItemDepartmentalStatus extends \SQL_Model{
	public $table ="xshop_orderitem_departmental_status";

	function init(){
		parent::init();

		$this->hasOne('xShop/OrderDetails','orderitem_id');
		$this->hasOne('xHR/Department','department_id');
		
		$this->addExpression('Quantity')->set(function($m,$q){
			return $m->refSQL('orderitem_id')->fieldQuery('qty');
		});
		$this->addExpression('Unit')->set(function($m,$q){
			return $m->refSQL('orderitem_id')->fieldQuery('unit');
		});

		// $this->addExpression('Custom Fields')->set(function($m,$q){
		// 	return $m->refSQL('orderitem_id')->fieldQuery('custom_fields');
		// });

		$this->addExpression('status')->set(function($m,$q){
			return $m->refSQL('xProduction/JobCard')->_dsql()->limit(1)->del('fields')->field('status');
		});

		// status of previous department jobcard .. if any or null
		$this->addExpression('previous_status')->set(function($m,$q){
			// my departments
			// my previous departments : leftJOin
			// job cards of my same orderitem_id from previous departments
			// limit 1
			// 
			return $m->refSQL('xProduction/JobCard')->_dsql()->limit(1)->del('fields')->field('status');
		});		

		// hasMany JobCards
		$this->hasMany('xProduction/JobCard','orderitem_departmental_status_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function createJobCard(){
		$new_job_card = $this->add('xProduction/Model_JobCard');
		$new_job_card->addCondition('orderitem_departmental_status_id',$this->id);
		$new_job_card->tryLoadAny();

		if($new_job_card->loaded())
			throw $this->exception('Job Card Already Created');
		
		$new_job_card['orderitem_id'] = $this['orderitem_id'];
		$new_job_card['department_id'] = $this['department_id'];
		$new_job_card['name']=rand(1000,9999);
		$new_job_card->save();
		return $new_job_card;
	}

	function receive(){
		// create job card for this department and this orderitem_id;
		$jobcard_model=$this->add('xProduction/Model_JobCard');
		$jobcard_model->addCondition('orderitem_departmental_status_id',$this->id);
		$jobcard_model->addCondition('orderitem_id',$this['orderitem_id']);
		$jobcard_model->addCondition('department_id',$this['department_id']);
		$jobcard_model->tryLoadAny();
		if($jobcard_model->loaded())
			throw $this->exception('Already Recieved and Job Card Created');
		$jobcard_model['status']='received';
		$jobcard_model->save();
		// jiska status ... received hoga
		// agar previous department hai to
			// uske job card ka status complete ka do
		// creatre log/communication entry
	}

}