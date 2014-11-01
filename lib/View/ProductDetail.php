<?php

namespace xShop;

class View_ProductDetail extends \View{
	function init(){
		parent::init();
		
	}  

	function setModel($model){
		$this->template->trySetHtml('description_html',$model['description']);
		$this->template->trySetHtml('short_description_html',$model['short_description']);
		parent::setModel($model);
	}

	function defaultTemplate(){
		$l=$this->api->locate('addons',__NAMESPACE__, 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons',__NAMESPACE__),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css'
				)
			)->setParent($l);
		return array('view/xShop-productdetailview');
	}
	
}
