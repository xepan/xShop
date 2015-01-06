<?php

namespace xShop;

class View_Badges_ItemPage extends \View_BadgeGroup{
	
	function init(){
		parent::init();
		$application_id=$this->api->recall('xshop_application_id');
		
		$item_model = $this->add('xShop/Model_Item');
		
		$this->add('View_Badge')->set(' Total Item ')->setCount($item_model->getItemCount($application_id))->setCountSwatch('ink');
		$this->add('View_Badge')->set(' Publish Item ')->setCount($item_model->getPublishCount($application_id))->setCountSwatch('green');
		$this->add('View_Badge')->set(' Unpublish Item ')->setCount($this->add('xShop/Model_Item')->getUnpublishCount($application_id))->setCountSwatch('red');
	}
}