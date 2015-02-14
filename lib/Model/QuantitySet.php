<?php

namespace xShop;

class Model_QuantitySet extends \Model_Table{
	public $table = 'xshop_item_quantity_sets';

	function init(){
		parent::init();

		$this->hasOne('xShop/Item','item_id');
		$this->addField('name'); // To give special name to a quantity Set .. leave empty to have qty value here too
		$this->addField('qty')->type('number')->mandatory(true);
		$this->addField('price')->type('money')->mandatory(true);

		$this->addHook('beforeSave',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		if(trim($this['name'])=='') $this['name']=$this['qty'];
	}
}