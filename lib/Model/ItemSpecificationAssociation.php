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
}