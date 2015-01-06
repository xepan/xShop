<?php

namespace xShop;

class Model_ItemReview extends \SQL_Model{
	public $table = 'xshop_item_reviews';
	
	function init(){
		parent::init();
		$this->hasOne('xShop/Item','item_id');
		$this->addField('rating')->type('number');
		$this->addField('review')->type('text');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}