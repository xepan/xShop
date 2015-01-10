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
		foreach ($fields as $fld) {
			$duplicate_template[$fld] = $this[$fld];
		}
		$duplicate_template->save();
		$duplicate_template['name'] = $this['name'].'-Copy';
		$duplicate_template['designer_id'] = $this->api->auth->model->id;
		$duplicate_template['sku'] = $this['sku'].'-' . $duplicate_template->id;
		$duplicate_template->save();
	}

}

