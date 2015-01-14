<?php

namespace xShop;

class Model_ItemSpecificationAssociation extends \SQL_Model{
	public $table = "xshop_item_spec_ass";

	function init(){
		parent::init();

		$this->hasOne('xShop/Item','item_id');
		$this->hasOne('xShop/Specification','specification_id');

		$this->addField('value');
		$this->addField('highlight_it')->type('boolean');

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function duplicate($item_id){
		$new_asso = $this->add('xShop/Model_ItemSpecificationAssociation');
		foreach ($this as $junk) {
			$new_asso['item_id'] = $item_id;
			$new_asso['specification_id'] = $junk['specification_id'];
			$new_asso['value'] = $junk['value'];
			$new_asso['highlight_it'] = $junk['highlight_it'];
			$new_asso->saveAndUnload();
		}
	}
}