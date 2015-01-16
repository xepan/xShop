<?php

namespace xShop;

class View_Item_Specifications extends \View{
	public $item_model;
	public $name;
	function init(){
		parent::init();
		
		$specification = $this->add('xShop/Model_Specification');
		$specification_j = $specification->Join('xshop_item_spec_ass.specification_id');
		$specification_j->addField('id');
		$specification_j->addField('item_id');
		$specification_j->addField('value');
		$specification_j->addField('highlight_it');
		$specification->addCondition('item_id',$this->item_model->id);
		$specification->addCondition('highlight_it',true);
		
		$string = "";
		foreach ($specification as  $junk) {
			$string .= "<p>".$specification['name']." - ".$specification['value']."</p>";
		}
		if(!empty($string))
			$this->add('View')->setHTML($string)->addClass('');

	}
}