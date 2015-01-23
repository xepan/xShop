<?php

namespace xShop;

class Model_ItemImages extends \Model_Table {
	var $table= "xshop_item_images";
	function init(){
		parent::init();
		
		$this->hasOne('xShop/Item','item_id');
		$this->hasOne('xShop/CustomFieldValue','customefieldvalue_id');

		$this->add('filestore/Field_Image','item_image_id')->mandatory(true);
		// $f = $this->addField('image_url');//->mandatory(true)->display(array('form'=>'ElImage'))->group('a~12~<i class="glyphicon glyphicon-picture"></i> Media Management');
		$this->addField('alt_text')->group('a~11~bl');
		$this->addField('title')->group('a~11~bl');

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function getImageUrl($item_id){
		$this->addCondition('item_id',$item_id);
		$this->tryLoadAny();
		return $this;
	}

	function duplicate($item_id,$customefieldvalue_id=null){
		$new = $this->add('xShop/Model_ItemImages');
		foreach ($this as $junk) {
			$new['item_id'] = $item_id;
			if($customefieldvalue_id)
				$new['customefieldvalue_id'] = $customefieldvalue_id;
			$new['item_image_id'] = $this['item_image_id'];
			$new['alt_text'] = $this['alt_text'];
			$new['title'] = $this['title'];
			$new->saveAndUnload();
		}
	}
}

