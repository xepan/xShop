<?php

namespace xShop;

class Model_ItemTemplate extends Model_Item{

	function init(){
		parent::init();
		
		$this->addCondition('is_template',true);
		
	}

	function duplicate(){
		$duplicate_template = $this->add('xShop/Model_ItemTemplate');
		$fields=$this->getActualFields();
		$fields = array_diff($fields,array('id','sku'));
		// echo "<pre>";
		// print_r($fields);
		// echo "</pre>";
		foreach ($fields as $fld) {
			// throw new \Exception("Error Processing Request".$this['name']);
			$duplicate_template[$fld] = $this[$fld];
		}
		$duplicate_template->save();
		$duplicate_template['name'] = $this['name'].'-Copy';
		$duplicate_template['sku'] = $this['sku'].'-' . $duplicate_template->id;
		$duplicate_template->save();
	}

}

