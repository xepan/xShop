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
			if($_GET['xsnb_designer_item_desgin_mode']=='true'){
				if($this->api->auth->isLoggedIn()){
					$designer = $this->add('xShop/Model_MemberDetails');
					if(!$designer->loadLoggedIn()) return;
					if($item['designer_id'] == $designer->id){
						$this->designer_mode = $_GET['xsnb_designer_item_desgin_mode'];
					}
				}
			}else{
				$designer = $this->add('xShop/Model_MemberDetails');
				if(!$designer->loadLoggedIn()) return;

				$item = $this->item = $this->add('xShop/Model_ItemMemberDesign')
									->addCondition('item_id',$_GET['xsnb_design_item_id'])
									->addCondition('member_id',$designer->id)
									->tryLoadAny();
				if(!$item->loaded()) return;
				if($this->api->auth->isLoggedIn()){
					if($item['member_id'] == $designer->id){
						$this->designer_mode = $_GET['xsnb_designer_item_desgin_mode'];
					}
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
			
			$this->js(true)->xepan_xshopdesigner(array('width'=>210,
														'height'=>279,
														'trim'=>5,
														'unit'=>'mm',
														'designer_mode'=>$this->designer_mode,
														'design'=>$this->item['designs'],
														'item_id'=>$_GET['xsnb_design_item_id'],
														'item_member_design_id' => $_GET['item_member_design_id']
												));
		}
		parent::render();
	}

}