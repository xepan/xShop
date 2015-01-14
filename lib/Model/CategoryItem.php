<?php
namespace xShop;

class Model_CategoryItem extends \Model_Table{
	var $table="xshop_category_item";
	var $table_alias = 'catitem';

	function init(){
		parent::init();
		
		$this->hasOne('xShop/Category','category_id')->defaultValue('Null');
		$this->hasOne('xShop/Item','item_id')->defaultValue('Null');
		
		$this->addField('is_associate')->type('boolean');
			
		$this->add('dynamic_model/Controller_AutoCreator'); 
	}

	function createNew($cat_id,$item_id){
		$old_model = $this->add('xShop/Model_CategoryItem');
		$old_model->addCondition('category_id',$cat_id);
		$old_model->addCondition('item_id',$item_id);
		$old_model->addCondition('is_associate',false);
		$old_model->tryLoadAny();
		if($old_model->loaded()){
			$old_model['is_associate'] = true;
			$old_model->saveandUnload();
		}else{
			$cat_item_cf_model = $this->add('xShop/Model_CategoryItem');
			$cat_item_cf_model['category_id'] = $cat_id;
			$cat_item_cf_model['item_id'] = $item_id;
			$cat_item_cf_model['is_associate'] = true;
			$cat_item_cf_model->saveandUnload();
		}
	}
	
	function getStatus($cat_id,$item_id){
		$this->addCondition('category_id',$cat_id);
		$this->addCondition('item_id',$item_id);
		$this->addCondition('is_associate',false);
		$this->tryLoadAny();
		return $this;
	}

	function duplicate($item_id){
		$new = $this->add('xShop/Model_CategoryItem');
		foreach ($this as $junk){
			$new->createNew($this['category_id'],$item_id);
		}
	}
}	