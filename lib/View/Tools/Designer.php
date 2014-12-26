<?php

namespace xShop;

class View_Tools_Designer extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	
	function init(){
		parent::init();
		if(isset($this->api->xepan_xshopdesigner_included)){
			// throw $this->exception('Designer Tool Cannot be included twise on same page','StopRender');
		}else{
			$this->api->xepan_xshopdesigner_included = true;
		}

		$this->addClass('xshop-designer-tool');

	}

	function render(){
		
		$this->app->pathfinder->base_location->addRelativeLocation(
		    'epan-components/'.__NAMESPACE__, array(
		        'php'=>'lib',
		        'template'=>'templates',
		        'css'=>array('templates/css','templates/js'),
		        'img'=>array('templates/css','templates/js'),
		        'js'=>'templates/js',

		    )
		);

		$this->api->jquery->addStylesheet('designer/designer');
		$this->api->template->appendHTML('js_include','<script src="epan-components/xShop/templates/js/designer/designer.js"></script>'."\n");
		//Jquery Color Picker
		$this->api->jquery->addStylesheet('designer/jquery.colorpicker');
		$this->api->template->appendHTML('js_include','<script src="epan-components/xShop/templates/js/designer/jquery.colorpicker.js"></script>'."\n");

		$this->js(true)->xepan_xshopdesigner(array('width'=>95,'height'=>55,'trim'=>5,'unit'=>'mm','design'=>array(array('type'=>'Text','text'=>'hello'),array('type'=>'Bacground','url'=>'path.jgp','crop'=>array(1,2,3,4)))));
		parent::render();
	}

}