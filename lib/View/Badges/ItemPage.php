<?php

namespace xShop;

class View_Badges_ItemPage extends \View_BadgeGroup{
	
	function init(){
		parent::init();
		$application_id=$this->api->recall('xshop_application_id');
<<<<<<< HEAD

		$item_model = $this->add('xShop/Model_Item');

		$this->add('View_Badge')->set(' Total Item ')->setCount($item_model->getItemCount($application_id))->setCountSwatch('ink');
		$publish = $item_model->getPublishCount($application_id)->getOne();
		if($publish)
			$this->add('View_Badge')->set(' Publish Item ')->setCount($publish)->setCountSwatch('green');
		
		$unactive = $this->add('xShop/Model_Item')->getUnpublishCount($application_id)->getOne();
		if($unactive)
			$this->add('View_Badge')->set(' Unpublish Item ')->setCount($unactive)->setCountSwatch('red');		
=======
		
		$item_model = $this->add('xShop/Model_Item');
		
		$this->add('View_Badge')->set(' Total Item ')->setCount($item_model->getItemCount($application_id))->setCountSwatch('ink');
		$this->add('View_Badge')->set(' Publish Item ')->setCount($item_model->getPublishCount($application_id))->setCountSwatch('green');
		$this->add('View_Badge')->set(' Unpublish Item ')->setCount($this->add('xShop/Model_Item')->getUnpublishCount($application_id))->setCountSwatch('red');
>>>>>>> 2853e7d1ab0cc29d7091b2cabb7bb2bdad9ad499
	}
}