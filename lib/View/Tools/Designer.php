<?php

namespace xShop;

class View_Tools_Designer extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	public $item=null;
	public $render_designer=false;
	public $designer_mode=false;
	public $specifications=array('width'=>false,'height'=>false,'unit'=>false);

	function init(){
		parent::init();
		$this->addClass('xshop-designer-tool');

		if(isset($this->api->xepan_xshopdesigner_included)){
			// throw $this->exception('Designer Tool Cannot be included twise on same page','StopRender');
		}else{
			$this->api->xepan_xshopdesigner_included = true;
		}

		$designer = $this->add('xShop/Model_MemberDetails');
		if(!$designer->loadLoggedIn()) {
			// Current logged in user, either user is logged out or does not have any member entry
			// So...
			return; 
		}
		
		if($_GET['item_member_design_id']){
			$target = $this->item = $this->add('xShop/Model_ItemMemberDesign')->tryLoad($_GET['item_member_design_id']);
			if(!$target->loaded()) return;
			$item = $target->ref('item_id');
		}

		if($_GET['xsnb_design_item_id']  and !isset($target)){
			$target = $this->item = $this->add('xShop/Model_Item')->tryLoad($_GET['xsnb_design_item_id']);
			if(!$target->loaded()){
				return;	
			} 
			$item = $target;
		}
		
		// check for required specifications like width / height

		if(!($this->specification['width'] = $item->specification('width')) OR !($this->specification['height'] = $item->specification('height'))){
			$this->add('View_Error')->set('Item Does not have \'width\' and/or \'height\' specification(s) set');
			return;
		}




		if(isset($target) and $_GET['xsnb_design_template']=='true' and $target['designer_id']== $designer->id){
			// am I the designer of item ?? .. checked in if condition above
			// set designer_mode=true to desginer js
			$this->designer_mode = true;	
			$this->render_designer = true;	
		}elseif(isset($target) and $_GET['xsnb_design_template']=='false' and $target['member_id'] == $designer->id ){
			// set target model to member_item_assos
			// set designer_mode=false to desginer js

			$this->render_designer = true;	
		}else{
			// NOTHING ??? .. Something wrong .. 
			// url not proper
			// Or target cold not be get
			// or trying to design template whose owner is not you (HAKINGGGGG)
			// or trying to edit a design not made by you (Hakinggg)
			// Put Common error for all
			// throw $this->exception('Something gone wrong... Please try again later');
			$this->add('View_Error')->set('Something gone wrong');
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
			
			$this->js(true)->xepan_xshopdesigner(array('width'=>210,
														'height'=>279,
														'trim'=>5,
														'unit'=>'mm',
														'designer_mode'=> $this->designer_mode,
														'design'=>$this->item['designs'],
														'item_id'=>$_GET['xsnb_design_item_id'],
														'item_member_design_id' => $_GET['item_member_design_id']
												));
		}
		parent::render();
	}

}