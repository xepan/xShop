<?php

class page_xShop_page_designer_save extends Page {
	function page_index(){

		if(!$this->api->auth->isLoggedIn()){
			//not logged in save current design in session and return to login page
			// TODOOOOOOOOOOOOOOOOOo.
			$this->js(null)->univ()->frameURL('Login First',$this->api->url('xShop_page_designer_login'))->execute();
			echo "false:9";
			exit;
		}

		$designer = $this->add('xShop/Model_MemberDetails');
		if(!$designer->loadLoggedIn()) {
			// Current logged in user, either user is logged out or does not have any member entry
			// So...
			echo "false :17";
			exit;
		}

		if($_POST['item_member_design_id']){
			$target = $this->item = $this->add('xShop/Model_ItemMemberDesign')->tryLoad($_POST['item_member_design_id']);
		}

		if($_POST['item_id']  and !isset($target)){
			$target = $this->item = $this->add('xShop/Model_Item')->tryLoad($_POST['item_id']);
			if(!$target->loaded()){
				echo "false : 28";
				exit;
			} 

		}

		if(isset($target) and $_POST['designer_mode']=='true' and $target['designer_id']== $designer->id){
			// am I the designer of item ?? .. checked in if condition above

			// check for required specifications like width / height

			// set designer_mode=true to desginer js
			$target['designs'] = $_POST['xshop_item_design'];
			$target->save();
			echo "true";
			exit;
			
		}elseif(isset($target) and ($_POST['designer_mode']=='false' OR !isset($_POST['designer_mode'])) and /* Made by me only --> */ $target['member_id'] == $designer->id ){
			// set target model to member_item_assos
			// set designer_mode=false to desginer js
			if($target->loaded()){
				$target['designs'] = $_POST['xshop_item_design'];
				$target->save();
			}else{
				$target['item_id'] = $_POST['item_id'];
				$target['member_id'] = $desginer['id'];
				$target['designs']	= $_POST['xshop_item_design'];
				$target->save();
			}		
			echo $target['id'];
			exit;
		}elseif(($_POST['designer_mode']=='false' OR !isset($_POST['designer_mode'])) and /*Saving first time .. no saved id sent --> */ !$_POST['item_member_design_id']){
			$target = $this->add('xShop/Model_ItemMemberDesign');
			$target['item_id']= $_POST['item_id'];
			$target['member_id'] = $designer->id;
			$target['designs']	= $_POST['xshop_item_design'];
			$target->save();
			echo $target['id'];
			exit;
		}
		else{
			// NOTHING ??? .. Something wrong .. 
			// url not proper
			// Or target cold not be get
			// or trying to design template whose owner is not you (HAKINGGGGG)
			// or trying to edit a design not made by you (Hakinggg)
			// Put Common error for all
			// throw $this->exception('Something gone wrong... Please try again later');
			echo "false: 66";
			exit;
		}
	}
}		