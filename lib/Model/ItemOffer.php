<?php
namespace xShop;
class Model_ItemOffer extends \Model_Table {
	var $table= "xshop_itemoffers";
	function init(){
		parent::init();

		$this->hasOne('xShop/Application','application_id');
		$this->addField('name');
		$this->add('filestore/Field_Image','offer_image_id')->mandatory(true);

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}