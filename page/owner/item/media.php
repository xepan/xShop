<?php

class page_xShop_page_owner_item_media extends Page{
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
		$crud = $this->add('CRUD');
		$item_images_model = $this->add('xShop/Model_ItemImages')->addCondition('item_id',$item_id);
		$item_images_model->setOrder('id','desc');
		$crud->setModel($item_images_model);
		if(!$crud->isEditing()){
			$g = $crud->grid;
			$g->addMethod('format_image_thumbnail',function($g,$f){
				$g->current_row_html[$f] = '<img style="height:40px;max-height:40px;" src="'.$g->current_row[$f].'"/>';
			});
			$g->addFormatter('item_image','image_thumbnail');
			$g->addQuickSearch(array('category_name'));
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

		//Crud
		$crud = $this->add('CRUD');
		$attachment_model = $this->add('xShop/Model_Attachments')->addCondition('item_id',$item_id);
		$attachment_model->setOrder('id','desc');

		if($form->isSubmitted()){
			$form->update();
			$form->js()->univ()->successMessage('Information Updtaed')->execute();
		}

		$crud->setModel($attachment_model);
		if(!$crud->isEditing()){
			$g = $crud->grid;
			$g->addMethod('format_attachment',function($g,$f){
				$g->current_row_html[$f] = '<a class="glyphicon glyphicon-folder-open" target="_blank" style="height:40px;max-height:40px;" href="'.$g->current_row[$f].'"></a>';
			});
			$g->addFormatter('attachment_url','attachment');

			$g->addQuickSearch(array('category_name'));
			$g->addPaginator($ipp=50);					
		}
	}
	
}