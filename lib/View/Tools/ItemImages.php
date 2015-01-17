<?php

namespace xShop;

class View_Tools_ItemImages extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	function init(){
		parent::init();
			if(!$_GET['xsnb_item_id']){
			$this->add('View_Error')->set('Item id not Define');
			return;
		}
		$product=$this->add('xShop/Model_Item');
		$product->load($_GET['xsnb_item_id']);
		$images_lister = $this->add('xShop/View_Lister_ItemImages');
		$images_lister->setModel($this->add('xShop/Model_ItemImages')->addCondition('item_id',$_GET['xsnb_item_id']));	
	}

	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}