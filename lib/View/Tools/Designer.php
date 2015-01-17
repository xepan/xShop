<?php

namespace xShop;

class View_Tools_Designer extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	public $item=null;
	public $render_designer=true;
	public $designer_mode=false;
	public $specifications=array('width'=>false,'height'=>false,'trim'=>false,'unit'=>false);

	function init(){
		parent::init();
		$this->addClass('xshop-designer-tool');

		if(isset($this->api->xepan_xshopdesigner_included)){
			// throw $this->exception('Designer Tool Cannot be included twise on same page','StopRender');
		}else{
			$this->api->xepan_xshopdesigner_included = true;
		}


		$designer = $this->add('xShop/Model_MemberDetails');
		$designer_loaded = $designer->loadLoggedIn();
		
		// 3. Design own in-complete design again
		if($_GET['item_member_design_id'] and $designer_loaded){
			$target = $this->item = $this->add('xShop/Model_ItemMemberDesign')->tryLoad($_GET['item_member_design_id']);
			if(!$target->loaded()) return;
			$item = $target->ref('item_id');
		}

		
		// 1. Designer wants edit template
		if($_GET['xsnb_design_item_id'] and $_GET['xsnb_design_template']=='true'  and $designer_loaded){
			$target = $this->item = $this->add('xShop/Model_Item')->tryLoad($_GET['xsnb_design_item_id']);
			if(!$target->loaded()){
				return;	
			} 
			$item = $target;

			if($target['designer_id'] != $designer->id){
				return;
			}
			$this->designer_mode = true;
		}

		// 2. New personalized item
		if($_GET['xsnb_design_item_id'] and is_numeric($_GET['xsnb_design_item_id']) and $_GET['xsnb_design_template'] !='true' and !isset($target)){
			$item = $this->add('xShop/Model_Item')->tryLoad($_GET['xsnb_design_item_id']);
			if(!$item->loaded()) {
				return;
			}

			$target = $this->item = $item->ref('xShop/ItemMemberDesign');
			$target['designs'] = $item['designs'];
		}


		
		if(!isset($target)){
			$this->render_designer = false;
			$this->add('View_Warning')->set('Insufficient Values, Item unknown or Not Authorised');
			return;
		}


		// check for required specifications like width / height
		if(!($this->specification['width'] = $item->specification('width')) OR !($this->specification['height'] = $item->specification('height')) OR !($this->specification['trim'] = $item->specification('trim'))){
			$this->add('View_Error')->set('Item Does not have \'width\' and/or \'height\' and/or \'trim\' specification(s) set');
			return;
		}else{
			// width and hirght might be like '51mm' and '91 mm' so get digit and unit sperated
			// print_r($this->specification);
			preg_match_all("/^([0-9]+)\s*([a-zA-Z]+)\s*$/", $this->specification['width'],$temp);
			$this->specification['width']= $temp[1][0];
			preg_match_all("/^([0-9]+)\s*([a-zA-Z]+)\s*$/", $this->specification['height'],$temp);
			$this->specification['height']= $temp[1][0];
			$this->specification['unit']=$temp[2][0];

			preg_match_all("/^([0-9]+)\s*([a-zA-Z]+)\s*$/", $this->specification['trim'],$temp);
			$this->specification['trim']= $temp[1][0];
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

		if($this->render_designer){
			$this->api->jquery->addStylesheet('designer/designer');
			$this->api->template->appendHTML('js_include','<script src="epan-components/xShop/templates/js/designer/designer.js"></script>'."\n");
			//Jquery Color Picker
			$this->api->jquery->addStylesheet('designer/jquery.colorpicker');
			$this->api->template->appendHTML('js_include','<script src="epan-components/xShop/templates/js/designer/jquery.colorpicker.js"></script>'."\n");
			// Jquery Cropper 
			$this->api->jquery->addStylesheet('designer/cropper');
			$this->api->template->appendHTML('js_include','<script src="epan-components/xShop/templates/js/designer/cropper.js"></script>'."\n");
			
			$design = json_decode($this->item['designs'],true);
			$design = $design['design']; // trimming other array values like px_width etc
			$design = json_encode($design);
			$this->js(true)->xepan_xshopdesigner(array('width'=>$this->specification['width'],
														'height'=>$this->specification['height'],
														'trim'=>$this->specification['trim'],
														'unit'=>'mm',
														'designer_mode'=> $this->designer_mode,
														'design'=>$design,
														'item_id'=>$_GET['xsnb_design_item_id'],
														'item_member_design_id' => $_GET['item_member_design_id']
												));
		}
		parent::render();
	}

}