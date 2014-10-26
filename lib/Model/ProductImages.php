<?php

namespace xShop;

class Model_ProductImages extends \Model_Table {
	var $table= "xshop_product_images";
	function init(){
		parent::init();
		
		$this->hasOne('xShop/Product','product_id');
		
		$this->addField('image_url')->mandatory(true)->display(array('form'=>'ElImage'));
		$this->addField('alt_text');
		$this->addField('title');

		// $this->add('dynamic_model/Controller_AutoCreator');		
	}

	function getImageUrl($product_id){
		$this->addCondition('product_id',$product_id);
		$this->tryLoadAny();
		return $this;
	}
}

