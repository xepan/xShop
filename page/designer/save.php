<?php

class page_xShop_page_designer_save extends Page {
	function page_index(){

		if(!$this->api->auth->model->id){
			//not logged in save current design in session and return to login page
			return false;
		}

		if($_POST['item_id']){
			//try load item
			$item_model = $this->add('xShop/Model_Item')->tryLoad($_POST['item_id']);
			//if no loaded throw exception
			if(!$item_model->loaded()){
				throw new \Exception("Item Model not Loaded");
			}
			// load designer with loadloggin
			$designer  = $this->add('xShop/Model_MemberDetails');
			$designer->loadLoggedIn();
			// if() item designber == designer id and dedsigner mode true save in item template
			if($item_model['designer_id'] == $designer->id and $_POST['designer_mode']){
				$item_model['designs'] = $_POST['xshop_item_design'];
				$item_model->save();
				echo "true";
				exit;
			}elseif(!$_GET['designer_mode'] and $designer_id == $this->api->auth->model->id){
				//else if itemmemberdesign id save in design
				$item_member_design = $this->add('xShop/Model_ItemMemberDesign');
				$item_member_design->addCondition('item_id',$_POST['item_id']);
				$item_member_design->addCondition('member_id',$designer->id);
				$item_member_design->tryLoadAny();
				
				$item_member_design['designs'] = $_POST['xshop_item_design'];
				$item_member_design->save();
				echo "true";
				exit;
			}
		}else{
				echo "false";
				exit;
			}
	}
}		