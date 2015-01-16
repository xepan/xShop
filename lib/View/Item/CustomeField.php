<?php

namespace xShop;
class View_Item_CustomeField extends \View{
	public $item_model;
	public $name;
	
	function init(){
		parent::init();
		
		$custom_field = $this->add('xShop/Model_CustomFields');
		$custom_field_asso_j = $custom_field->join('xshop_category_item_customfields.customfield_id');
		$custom_field_asso_j->addField('id');
		$custom_field_asso_j->addField('item_id');
		$custom_field_asso_j->addField('customfield_id');
		$custom_field->addCondition('item_id',$this->item_model->id);

		foreach ($custom_field as $junk){
			$string = "";
			
			switch ($custom_field['type']) {
				case 'DropDown': 
					$string = $this->getDropDown($custom_field['id'], $custom_field['name']);
					break;

				case 'Radio Button':
					$string = $this->getRadioButton($custom_field['id'],$custom_field['name']);
					break;

				case 'CheckBox':
					$string = $this->getCheckBox($custom_field['id'],$custom_field['name']);
					break;					
			}
			// $this->add('View')->set('Custom Fields - '.$custom_field['name']." - ".$custom_field['customfield_id']." - ".$custom_field['id'].'-'.$custom_field['type']);
			$this->add('View')->setHTML($custom_field['name'] . ' '. $string);
		}
	}

	function getDropDown($association_id, $field_name){
		$custom_field_value = $this->add('xShop/Model_CustomFieldValue')->addCondition('itemcustomfiledasso_id',$association_id);
		$html = "<select field='$field_name'>";
		foreach ($custom_field_value as $junk) {
			$html .="<option>".$junk['name']."</options>"; 
		}
		$html .="</select>";
		return $html;
	} 

	function getRadioButton($association_id, $field_name){
		$custom_field_value = $this->add('xShop/Model_CustomFieldValue')->addCondition('itemcustomfiledasso_id',$association_id);
		$html = "";
		foreach ($custom_field_value as $junk) {
			$html = '<input type="radio" name="" field="'.$field_name.'">'.$junk['name']."</input>";
		}
		return $html;
	}

	function getCheckBox($association_id, $field_name){
		$custom_field_value = $this->add('xShop/Model_CustomFieldValue')->addCondition('itemcustomfiledasso_id',$association_id);
		$html = "";
		foreach ($custom_field_value as $junk) {
			$html = '<input type="checkbox" name="" field="'.$field_name.'">'.$junk['name']."</input>";
		}
		return $html;	
	}

	function render(){
		$this->js(true)->xepan_xshop_item_customfield();
		parent::render();
	}

}