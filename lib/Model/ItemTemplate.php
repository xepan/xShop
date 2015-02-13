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
		$fields = array_diff($fields,array('id','sku','designer_id','created_at'));
		
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
		$duplicate_template['is_publish'] = false;
		$duplicate_template->save();

		if($create_default_design_also){
			$new_design = $this->add('xShop/Model_ItemMemberDesign');
			$new_design['member_id'] = $designer->id;
			$new_design['item_id'] = $duplicate_template->id;
			$new_design['designs'] = $duplicate_template['designs'];
			$new_design->save();
		}

		//Specification and value Duplicate
		$old_specification = $this->add('xShop/Model_ItemSpecificationAssociation')->addCondition('item_id',$this->id);
		$new_asso = $old_specification->duplicate($duplicate_template['id']);

		//Custom and value Field Duplicate
		$old_asso = $this->add('xShop/Model_ItemCustomFieldAssos')->addCondition('item_id',$this->id);
		foreach ($old_asso as $junk){
			$new_asso = $old_asso->duplicate($duplicate_template['id']);
			//New Custom Field Association with old Vlaues
			$old_custom_value = $this->add('xShop/Model_CustomFieldValue')->addCondition('itemcustomfiledasso_id',$old_asso['id']);
			foreach ($old_custom_value as $junk){
				$new_custom_value = $old_custom_value->duplicate($new_asso['id'],$duplicate_template['id']);
				$new_custom_value->unload();
				}
			$new_asso->unload();
		}

		//Category Association Duplicate
		$cat_item_asso = $this->add('xShop/Model_CategoryItem')->addCondition('item_id',$this->id);
		$cat_item_asso->duplicate($duplicate_template['id']);
		
		//Image Dupliacte
		$image = $this->add('xShop/Model_ItemImages')->addCondition('customefieldvalue_id',Null)->addCondition('item_id',$this->id);
		$image->duplicate($duplicate_template['id']);

		//Attachment Document Dupliacte
		$docs = $this->add('xShop/Model_Attachments')->addCondition('item_id',$this->id);
		$docs->duplicate($duplicate_template['id']);

		//Deaprtment Association Duplicate
		$department_ass_model = $this->add('xShop/Model_ItemDepartmentAssociation');
		$department_ass_model->duplicate($this['id']);

		return $duplicate_template;
	}

}

