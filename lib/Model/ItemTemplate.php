<?php

namespace xShop;

class Model_ItemTemplate extends Model_Item{

	function init(){
		parent::init();
		
		$this->addCondition('is_template',true);
		
	}

	function duplicate($create_default_design_also=true){
		$duplicate_template = $this->add('xShop/Model_Item');
		$fields=$this->getActualFields();
		$fields = array_diff($fields,array('id','sku','designer_id'));
		
		foreach ($fields as $fld) {
			$duplicate_template[$fld] = $this[$fld];
		}

		$designer = $this->add('xShop/Model_MemberDetails');
		$designer->loadLoggedIn();
		
		$duplicate_template->save();
		$duplicate_template['name'] = $this['name'].'-Copy';
		$duplicate_template['designer_id'] = $designer->id;
		$duplicate_template['sku'] = $this['sku'].'-' . $duplicate_template->id;
		$duplicate_template['is_template'] = false;
		$duplicate_template->save();

		if($create_default_design_also){
			$new_design = $this->add('xShop/Model_ItemMemberDesign');
			$new_design['member_id'] = $designer->id;
			$new_design['item_id'] = $duplicate_template->id;
			$new_design['designs'] = $duplicate_template['designs'];
			$new_design->save();
		}

		return $duplicate_template;
	}

}

