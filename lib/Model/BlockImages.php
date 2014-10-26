<?php

namespace xShop;

class Model_BlockImages extends \Model_Table {
	var $table= "xshop_blockimages";
	function init(){
		parent::init();
		
		$this->hasOne('xShop/AddBlock','block_id');
		$this->addField('image_url')->mandatory(true)->display(array('form'=>'ElImage'));
		$this->addField('link');
		$this->addField('alt_text');
		$this->addField('title');

		// $this->addExpression('block_name')->set(function($m,$q){
		// 	return $m->refSQL('block_id')->fieldQuery('name');
		// });
		
		// $this->add('dynamic_model/Controller_AutoCreator');		
	}
}

