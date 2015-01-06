<?php

namespace xShop;

class View_Lister_DesignerItemImages extends \CompleteLister{

	function setModel($model){
		parent::setModel($model);
	
	}
	// function formatRow(){
	// 	$this->current_row['zoom3_image_url'] = $this->model['image_url'];

	// }
	function defaultTemplate(){

		$this->app->pathfinder->base_location->addRelativeLocation(
		    'epan-components/'.__NAMESPACE__, array(
		        'php'=>'lib',
		        'template'=>'templates',
		        'css'=>'templates/css',
		        'js'=>'templates/js',
		    )
		);
		return array('view/xShop-DesignerItemImages');
	}
}