<?php

namespace xShop;

class Model_QuantitySet extends \Model_Table{
	public $table = 'xshop_item_quantity_sets';

	function init(){
		parent::init();

		$this->hasOne('xShop/Item','item_id');
		$this->addField('name'); // To give special name to a quantity Set .. leave empty to have qty value here too
		$this->addField('qty')->type('number')->mandatory(true);
		$this->addField('old_price')->type('money')->mandatory(true);
		$this->addField('price')->type('money')->mandatory(true);
		$this->addField('is_default')->type('boolean')->defaultValue(false);

		$this->addExpression('custom_fields_conditioned')->set(function($m,$q){
			$temp =$m->refSQL('xShop/QuantitySetCondition');
			return $temp->_dsql()->group('quantityset_id')->del('fields')->field('count(*)');
		});

		$this->addHook('beforeSave',$this);
		$this->addHook('afterInsert',$this);

		$this->hasMany('xShop/QuantitySetCondition','quantityset_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		if(trim($this['name'])=='') $this['name']=$this['qty'];
	}

	function afterInsert(){
		$item = $this->add('xShop/Model_Item')->load($this['item_id']);
		$cf_array = $item->getAssociatedCustomFields();
		// foreach ($cf_array as $junk) {			
		// }
	}
}