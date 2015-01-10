<?php

class page_xShop_page_designer_save extends Page {
	function page_index(){

		if(!$this->api->auth->model->id){
			return false;
		}

		if($_POST['item_id']){
			// load Member Designer
			$designer = $this->add('xShop/Model_MemberDestails');
		}else{
			echo "false";
			exit;
		}

		// if($_POST['designer_mode']){ /* if Designer Mode ie True*/
		// 	if($_POST['xshop_item_design']){
		// 		$item_model =  $this->add('xShop/Model_Item')->tryload($_POST['item_id']);
		// 		$item_model->addCondition('designer_id',$this->api->auth->model->id);
		// 		if($item_model->loaded()){
		// 			$item_model['designs'] = $_POST['xshop_item_design'];
		// 			$item_model->saveAndUnload();
		// 			echo "true";
		// 			exit;
		// 		}
		// 		echo "false";
		// 		exit;
		// 	}

		// }else{
		// 	$memberdesign_model = $this->add('xShop/Model_ItemMemberDesign');
		// 	$memberdesign_model->addCondition('item_id',$_POST['item_id']);
		// 	$memberdesign_model->addCondition('member_id',$this->api->auth->model->id);
		// 	if($memberdesign_model->loaded()){
		// 		$memberdesign_model['designs'] = $_POST['xshop_item_design'];
		// 		$memberdesign_model->saveAndUnload(); 
		// 		echo "true";
		// 		exit;
		// 	}
		// 	echo "false";
		// 	exit;
		// }

	}
}		