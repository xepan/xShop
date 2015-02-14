<?php

namespace xShop;
class Model_Category extends \Model_Table{
	var $table="xshop_categories";
	var $table_alias = 'category';

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		$this->hasOne('xShop/Application','application_id');

		//Do for category model with self loop of parent category
		$this->hasOne('xShop/ParentCategory','parent_id')->defaultValue('Null')->group('x~6');
		$f = $this->addField('name')->Caption('Category Name')->mandatory(true)->sortable(true)->group('a~6~<i class="fa fa-cog"></i> TITLE');
		$f->icon = "fa fa-folder~red";
		$f = $this->addField('order_no')->type('int')->hint('Greatest order number display first and only integer number require')->defaultValue(0)->sortable(true)->group('a~4');
		$f->icon = "fa fa-sort-amount-desc~blue";
		$f = $this->addField('is_active')->type('boolean')->defaultValue(true)->group('a~2');
		$f->icon = "fa fa-exclamation~blue";
		$f = $this->add('filestore/Field_Image','image_url_id')->group('b~6~Category Images');
		// $f = $this->addField('image_url')->display(array('form'=>'ElImage'));
		$f->icon = "glyphicon glyphicon-picture~blue";		
		$f = $this->addField('alt_text')->group('b~6');
		$f->icon = "glyphicon glyphicon-pencil~blue";		
		$f = $this->addField('description')->type('text')->display(array('form'=>'RichText'))->group('c~12');
		$f->icon = "fa fa-pencil~blue";

		$this->addField('meta_title');//->group('e~12~asd');
		$this->addField('meta_description')->type('text');//->group('e~12~bl');
		$this->addField('meta_keywords');//->group('e~12~bl');

		$this->hasMany('xShop/Category','parent_id',null,'SubCategories');
		$this->hasMany('xShop/CategoryItem','category_id');
		
		$parent_join = $this->leftJoin('xshop_categories','parent_id');
		$this->addExpression('category_name')->set('concat('.$this->table_alias.'.name,"- (",IF('.$parent_join->table_alias.'.name is null,"",'.$parent_join->table_alias.'.name),")")');		
		
		// $this->hasMany('xShop/ItemCustomFieldAssos','category_id');		
				
		$this->addHook('beforeDelete',$this);
		$this->addHook('beforeSave',$this);
		
		// $this->add('dynamic_model/Controller_AutoCreator'); 
	}


	function beforeSave($m){
		// Category name Cannot be same in Parent Category
		if($this->nameExistInParent())
			throw $this->Exception('Name Already Exist','ValidityCheck')->setField('name');

	}


	function beforeDelete($m){
		
		$category_parent = $this->add('xShop/Model_Category');
		$category_parent->addCondition('parent_id',$m->id);
		$category_parent->tryLoadAny();
		if($category_parent->loaded()){
			$category_parent->api->js(true)->univ()->errorMessage('first delete its all child category')->execute();
		}

		$this->ref('xShop/CategoryItem')->deleteAll();
	}

	function nameExistInParent(){ //Check Duplicasy on Name Exist in Parent Category
		return $this->ref('parent_id')->loaded()? $this->ref('parent_id')->ref('SubCategories')->addCondition('name',$this['name'])->addCondition('id','<>',$this->id)->tryLoadAny()->loaded(): false;
	}

	function getSubCategory(){
		if(!$this->loaded())
			return;

		$cat_model = $this->add('xShop/Model_Category');
		$cat_model->addCondition('parent_id',$this['id']);
		//Return Only one Level Child Category
		$cat_array = array();
		$cat_array[] = $this['id'];
		foreach ($cat_model as $id => $obj) {
			$cat_array[] = $id;
		}
		return $cat_array;
	}

}