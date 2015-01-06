<?php

namespace xShop;

class View_Badges_CategoryPage extends \View_BadgeGroup{
	
	function init(){
		parent::init();
		$application_id=$this->api->recall('xshop_application_id');

		$active = $this->add('xShop/Model_ActiveCategory')->addCondition('application_id',$application_id)->count()->getOne();
		$unactive = $this->add('xShop/Model_InActiveCategory')->addCondition('application_id',$application_id)->count()->getOne();
		$v=$this->add('View_Badge')->set(' Active Category')->setCount($active)->setCountSwatch('ink');
		if($unactive)
			$v=$this->add('View_Badge')->set(' Unactive Category')->setCount($unactive)->setCountSwatch('red');
	}
}