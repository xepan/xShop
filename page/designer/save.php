<?php

class page_xShop_page_designer_save extends Page {
	function page_index(){

		if($_POST['xshop_item_design']){
			$item_model =  $this->add('xShop/Model_Item')->tryload($_POST['item_id']);
			if($item_model->loaded()){
				$item_model['designs'] = $_POST['xshop_item_design'];
				$item_model->saveAndUnload();
				echo "true";
				exit;
			}
			echo "false";
			exit;
		}

	}
}		