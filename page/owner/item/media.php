<?php

class page_xShop_page_owner_item_media extends page_xShop_page_owner_main{
	function page_index(){
				
		if(!$_GET['item_id'])
			return;
		$item_id = $this->api->stickyGET('item_id');

		$tabs = $this->add('Tabs');
		$tabs->addTabURL('./images','Image');
		$tabs->addTabURL('./attachments','Attachment');
		$tabs->addTabURL('./watermark','WaterMark');

	}
	
	function page_watermark(){
		if(!$_GET['item_id'])
			return;
		$item_id = $this->api->stickyGET('item_id'); 
		$form = $this->add('Form');
		$form->setModel($this->add('xShop/Model_Item')->load($item_id),array('watermark_image_id','watermark_position','watermark_opacity','watermark_text'));
		$form->addSubmit()->set('Update');

		if($form->isSubmitted()){
			$form->update();
			$form->js()->univ()->successMessage('Information Updtaed')->execute();
		}	
			
	}

	function page_images(){
		
		$item_id = $this->api->stickyGET('item_id');

		$item_images_model = $this->add('xShop/Model_ItemImages')->addCondition('item_id',$item_id)->addCondition('customefieldvalue_id',null);
		$item_images_model->setOrder('id','desc');
		$crud = $this->add('CRUD');
		
		// if($item_images_model->ref('item_id')->get('is_designable')){
		// 	$btn = $this->add('Button')->set('Remove Images and Generate From Design');
		// 	if($btn->isClicked()){
		// 		foreach ($item_images_model as $junk) {
		// 			$item_images_model->delete();
		// 		}
		// 		$item = $item_images_model->ref('item_id');
		// 		$cont = $this->add('xShop/Controller_DesignTemplate',array('item'=>$item,'design'=>$item['designs'],'page_name'=>$_GET['page_name']?:'Front Page','layout'=>$_GET['layout_name']?:'Main Layout'));
		// 		// $image_data = $cont->show($type='png',$quality=3, $base64_encode=false, $return_data=true);

		// 		$cont->phpimage->save('/tmp/sfdsd.png');

		// 		$image = $this->add('filestore/Model_Image');
		// 		$image->import('/tmp/sfdsd.png',$mode='move');
		// 		$image->performImport();
		// 		$image->save();

		// 		// $item_images_model->save();
		// 		// $item_images_model->ref('item_image_id')->import($image,'string')->save();

		// 		$crud->grid->js()->reload()->execute();
		// 	}
		// }

		$crud->setModel($item_images_model,array('item_image_id','alt_text','title'),array('item_image','alt_text','title'));
		if(!$crud->isEditing()){
			$g = $crud->grid;
			$g->addMethod('format_image_thumbnail',function($g,$f){
				$g->current_row_html[$f] = '<img style="height:40px;max-height:40px;" src="'.$g->current_row[$f].'"/>';
			});
			$g->addFormatter('item_image','image_thumbnail');
			$g->addPaginator($ipp=50);
		}		
	}

	function page_attachments(){
		if(!$_GET['item_id'])
			return;
		$item_id = $this->api->stickyGET('item_id');
		$form = $this->add('Form');
		$form->setModel($this->add('xShop/Model_Item')->load($item_id),array('is_attachment_allow'));
		$form->addSubmit()->set('Update');

		if($form->isSubmitted()){
			$form->update();
			$form->js(null,$this->js()->reload())->univ()->successMessage('Information Updtaed')->execute();
		}
		if(!$form['is_attachment_allow'])
			return;
		//Crud
		$crud = $this->add('CRUD');
		$attachment_model = $this->add('xShop/Model_Attachments')->addCondition('item_id',$item_id);
		$attachment_model->setOrder('id','desc');


		$crud->setModel($attachment_model,array('name','attachment_url_id'),array('name','attachment_url'));
		if(!$crud->isEditing()){
			$g = $crud->grid;
			$g->addMethod('format_attachment',function($g,$f){
				$g->current_row_html[$f] = '<a class="glyphicon glyphicon-folder-open" target="_blank" style="height:40px;max-height:40px;" href="'.$g->current_row[$f].'"></a>';
			});
			$g->addFormatter('attachment_url','attachment');

			$g->addQuickSearch(array('name'));
			$g->addPaginator($ipp=50);					
		}
	}
	
}