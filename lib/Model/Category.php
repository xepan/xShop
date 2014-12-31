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
		$this->hasOne('xShop/ParentCategory','parent_id')->defaultValue('Null');
		$f = $this->addField('name')->Caption('Category Name')->mandatory(true)->sortable(true)->group('a~6');
		$f->icon = "fa fa-folder~red";
		$f = $this->addField('order_no')->type('int')->hint('Greatest order number display first and only integer number require')->defaultValue(0)->sortable(true)->group('a~4');
		$f->icon = "fa fa-sort-amount-desc~blue";
		$f = $this->addField('is_active')->type('boolean')->defaultValue(true)->group('a~2');
		$f->icon = "fa fa-exclamation~blue";		
		$f = $this->addField('image_url')->display(array('form'=>'ElImage'))->group('b~6~Category Images');
		$f->icon = "glyphicon glyphicon-picture~blue";		
		$f = $this->addField('alt_text')->group('b~6');
		$f->icon = "glyphicon glyphicon-pencil~blue";		
		$f = $this->addField('description')->type('text')->display(array('form'=>'RichText'))->group('c~12');
		$f->icon = "fa fa-pencil~blue";

		$this->addField('meta_title');
		$this->addField('meta_description')->type('text');
		$this->addField('meta_keywords');

		$this->hasMany('xShop/Category','parent_id',null,'SubCategories');
		$this->hasMany('xShop/CategoryItem','category_id');
		
		$parent_join = $this->leftJoin('xshop_categories','parent_id');
				
		$this->addExpression('category_name')->set('concat('.$this->table_alias.'.name,"- (",IF('.$parent_join->table_alias.'.name is null,"",'.$parent_join->table_alias.'.name),")")');		
		$this->addHook('beforeDelete',$this);
		$this->addHook('beforeSave',$this);
		$this->addHook('afterSave',$this);
		
		$this->hasMany('xShop/CategoryItemCustomFields','category_id');		
		// $this->add('dynamic_model/Controller_AutoCreator'); 
	}


	function beforeSave($m){
		// Category name Cannot be same in Parent Category
		if($this->existInParent($this['parent_id'],$this['name']))
			throw $this->Exception('Name Already Exist','ValidityCheck')->setField('name');

	}
	function afterSave(){
	}

	function duplicate($cat_id){
		$new_cat=$this->add('xShop/Model_Category');
		if($this->loaded()){
			$this->Unload();
			// throw new \Exception("Category Model Loaded".$cat_id);
		}

		$this->load($cat_id);
			// $new_cat['parent_id']=NULL;
			// else
		$new_cat['name']=$this['name']."-(copy)";
		$new_cat['parent_id']=$this['parent_id'];
		$new_cat['application_id']=$this['application_id'];
		$new_cat['description']=$this['description'];
		$new_cat['meta_title']=$this['meta_title'];
		$new_cat['meta_description']=$this['meta_description'];
		$new_cat['meta_keywords']=$this['meta_keywords'];
		$new_cat->save();

		$cat_old_customfield = $this->add('xShop/Model_CategoryItemCustomFields');
		$cat_old_customfield->addCondition('category_id',$cat_id);
		foreach ($cat_old_customfield as $junk) {
			$cat_new_customfield = $this->add('xShop/Model_CategoryItemCustomFields');
			$cat_new_customfield['category_id'] = $new_cat['id'];
			$cat_new_customfield['customfield_id'] = $cat_old_customfield['customfield_id'];
			$cat_new_customfield['is_allowed'] = true;
			$cat_new_customfield->saveandUnload();			
		}
		//Add Category Custom Fields
		$new_cat->Unload();

	}

	function beforeDelete($m){
		
		$category_parent = $this->add('xShop/Model_Category');
		$category_parent->addCondition('parent_id',$m->id);
		$category_parent->tryLoadAny();
		if($category_parent->loaded()){
			$category_parent->api->js(true)->univ()->errorMessage('first delete its all child category')->execute();
		}

		// Delete category and its product associatations
		
		// $custom_field = $this->add('xShop/Model_CategoryItemCustomFields');
		// $custom_field->addCondition('category_id',$m['id']);
		
		$this->ref('xShop/CategoryItemCustomFields')->deleteAll();
		$this->ref('xShop/CategoryItem')->deleteAll();

	}

	function getActiveCategory($app_id){
		$this->addCondition('application_id',$app_id);
		$this->addCondition('is_active',true);
		$this->tryLoadAny();
		return $this;
	}

	function getUnActiveCategory($app_id){
		$this->addCondition('application_id',$app_id);
		$this->addCondition('is_active',false);
		$this->tryLoadAny();
		return $this;
	}

	function existInParent($parent_id,$category_name){//Check Duplicasy on Name Exist in Parent Category
		$app_id=$this->api->recall('xshop_application_id');
		$category_old=$this->add('xShop/Model_Category');
		$category_old->addCondition('application_id',$app_id);
		
		if($this['parent_id'])
			$category_old->addCondition('parent_id',$parent_id);
		$category_old->addCondition('name',$category_name);
		$category_old->tryLoadAny();
		if($category_old->loaded())
			return true;

		return false;
	}

	function getAllAssociateCustomFields(){
		$associate_customfields= $this->ref('xShop/CategoryItemCustomFields')->addCondition('is_allowed',true)->_dsql()->del('fields')->field('customfield_id')->getAll();
		return iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($associate_customfields)),false);
	}	

	function addCustomField($customfield_id){
	
		$old_model = $this->add('xshop/Model_CategoryItemCustomFields');
		$old_model->addCondition('category_id',$this->id);
		$old_model->addCondition('customfield_id',$customfield_id);
		$old_model->addCondition('is_allowed',false);
		$old_model->tryLoadAny();
		if($old_model->loaded()){
			$old_model['is_allowed'] = true;
			$old_model->saveandUnload();
		}else{
			$cat_item_cf_model = $this->add('xshop/Model_CategoryItemCustomFields');
			$cat_item_cf_model['customfield_id'] = $customfield_id;
			$cat_item_cf_model['category_id'] = $this->id;
			$cat_item_cf_model['is_allowed'] = true;
			$cat_item_cf_model->saveandUnload();
		}

	}

}