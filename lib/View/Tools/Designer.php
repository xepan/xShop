<?php

namespace xShop;

class View_Tools_Designer extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	public $item=null;
	public $render_designer=false;
	public $designer_mode=false;

	function init(){
		parent::init();
		$this->addClass('xshop-designer-tool');

		if(isset($this->api->xepan_xshopdesigner_included)){
			// throw $this->exception('Designer Tool Cannot be included twise on same page','StopRender');
		}else{
			$this->api->xepan_xshopdesigner_included = true;
		}

		if($_GET['xsnb_design_item_id']){
			$item = $this->item = $this->add('xShop/Model_Item')->tryLoad($_GET['xsnb_design_item_id']);
			if(!$item->loaded()) return;
			if($_GET['xsnb_designer_item_desgin_mode']){
				if($this->api->auth->isLoggedIn()){
					$designer = $this->add('xShop/Model_MemberDetails');
					if(!$designer->loadLoggedIn()) return;
					if($item['designer_id'] == $designer->id)
						$this->designer_mode = true;
				}
			}
		}


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

		if(!$this->render_designer){
			$this->api->jquery->addStylesheet('designer/designer');
			$this->api->template->appendHTML('js_include','<script src="epan-components/xShop/templates/js/designer/designer.js"></script>'."\n");
			//Jquery Color Picker
			$this->api->jquery->addStylesheet('designer/jquery.colorpicker');
			$this->api->template->appendHTML('js_include','<script src="epan-components/xShop/templates/js/designer/jquery.colorpicker.js"></script>'."\n");
			// Jquery Cropper 
			$this->api->jquery->addStylesheet('designer/cropper');
			$this->api->template->appendHTML('js_include','<script src="epan-components/xShop/templates/js/designer/cropper.js"></script>'."\n");
			
			// $ited_id = $this->api->stickyGET('xshop_item_id');
			$item_model = $this->add('xShop/Model_Item')->tryLoadAny();

			$this->js(true)->xepan_xshopdesigner(array('width'=>210,'height'=>279,'trim'=>5,'unit'=>'mm','designer_mode'=>$this->designer_mode,'design'=>$item_model['designs']));
		}
		parent::render();
	}

}