<?php

namespace xShop;

class Model_Attachments extends \Model_Table{
	public $table='xshop_attachments';

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);	
		$this->hasOne('xShop/Item','item_id');
		
		$this->addField('name')->mandatory(true)->group('a~6~Item Attachments');
		$this->add('filestore/Field_Image','attachment_url_id')->mandatory(true);
		// $this->addField('attachment_url')->display(array('form'=>'ElImage'))->mandatory(true)->mandatory(true)->group('a~6');

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function duplicate($item_id){
		$new = $this->add('xShop/Model_Attachments');
		foreach ($this as $junk) {
			$new['name'] = $junk['name'];
			$new['item_id'] = $item_id;
			$new['attachment_url'] = $junk['attachment_url'];
			$new->saveAndUnload();
		}
	}
}

