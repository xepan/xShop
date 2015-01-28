<?php

namespace xShop;

class View_Tools_ItemDetail extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	
	function init(){
		parent::init();
		
		$this->addClass('xshop-item');
		// $this->js(true)->_load('jquery-elevatezoom');
		$this->api->stickyGET('xsnb_item_id');
		$config_model=$this->add('xShop/Model_Configuration');
		$item=$this->add('xShop/Model_Item');
		if(!$_GET['xsnb_item_id'])
			return;

		$item->load($_GET['xsnb_item_id']);		
		$this->setModel($item);
		
		//Show Images
		$col_width = "12";
		if($this->html_attributes['show-image']){
			$col_width = "4";
			//$images = $this->add('xShop/View_Lister_itemImages',null,'item_images');
			//$images->setModel($this->add('xShop/Model_ItemImages')->addCondition('item_id',$_GET['xsnb_item_id']));
		}else{
			$this->template->tryDel('item_images');
		}
		
		//Item Offer Supplier Shipping Info
		$this->add('LoremIpsum',null,'item_offer_supplier_shipping_info')->setLength(1,100);

		if($item['show_attachment']){
			$attachment_model=$this->add('xShop/Model_Attachments');
			$attachment_model->addCondition('item_id',$_GET['xsnb_item_id']);
			$attachment_model->tryLoadAny();
			$this->template->set('attachment_url',$attachment_model['attachment_url']);
			$this->template->set('attachment_name',$attachment_model['name']);
		}else
			$this->template->tryDel('xshop_item_attachment');
		
		

		// $this->add('View',null,'description123')->setHtml($item['description']);
		//adding tag to item detail options
		$this->template->Set('xshop_item_tags',str_replace(',', " ", $item['tags']));


		$this->api->template->trySet('page_title',$item['name']);

		if(!$item['show_price']){
			$this->template->tryDel('xshop_itemdetail_price');
		}
		
		if($this->html_attributes['xshop_item_detail_images']==1){
			$this->template->tryDel('xshop_item_detail_images');	
		}

		if($item['allow_comments']){	
			$config_model->tryLoadAny();
			if($item['comment_api']){
				if($config_model['disqus_code'])
					$this->template->trySetHTML('xshop_item_discus',$config_model['disqus_code']);
				else
					$this->template->trySetHTML('xshop_item_discus',"<div class='alert alert-info'>Place Your Discus Code...in Configuration</div>");			
			} 
		}
		
		$this->add('View',null,'send_button')->set('Send Enquiry')->addClass('btn btn-default');
		
		//add custom btn in item detail
		$config_model->tryLoadAny();
		if($config_model['add_custom_button']){
			if($this->model['add_custom_button']){
				$this->add('View',null,'xshop_item_custom_btn')->set($this->model['custom_button_text'])->addClass('btn btn-default');
				$this->template->trySetHTML('xshop_item_custom_btn_url',$this->model['custom_button_url']);
			}else{
				$this->add('View',null,'xshop_item_custom_btn')->set($config_model['custom_button_text'])->addClass('btn btn-default');
				$this->template->trySetHTML('xshop_item_custom_btn_url',$config_model['custom_button_url']);
			}		
		}
		//end of custom btn in item detail		

		//AddToCart
		//if Item Designable 
			if($this->model['is_designable']){
				// add Personalioze View
				$this->add('Button',null,'xshop_item_cart_btn')->set('Personalize')->js('click',$this->js()->univ()->location("index.php?subpage=".$this->html_attributes['personalization-page']."&xsnb_design_item_id=".$this->model->id));
			}else{
				//add AddToCart View
				$this->add('xShop/View_Item_AddToCart',array('name'=>'cust_'.$this->model->id,'item_model'=>$this->model,'show_custom_fields'=>1,'show_price'=>$this->model['show_price'], 'show_qty_selection'=>1),'xshop_item_cart_btn');
			}

		// Item Detail and Comments specification
		$tabs = $this->add('Tabs',null,'xshop_item_detail_information');
		$detail_tab = $tabs->addTab('Detail');
		$specification_tab = $tabs->addTab('Specification');
		$enquiry_tab = $tabs->addTab('Enquiry');

		$item_description = $item['description'];
		//Live Edit of item Detail (server site live edit )
		if( $this->api->edit_mode == true ){		
			$this->js(true)->_load('xshopContentUpdate');
			$str = '<div class="epan-container epan-sortable-component epan-component  ui-sortable component-outline epan-sortable-extra-padding ui-selected xshop-item-detail-content-live-edit" component_type="Container" id="xshop_item_detail_content_live_edit_"'.$item['id'].'>';
			$str.= $item_description;
			$str.="</div>";
			
			// $btn Todoooooooooooooooo???????????????/	
			$btn = 'onclick="javascript:$(this).univ().producDetailUpdate(';
			$btn.= '\'xshop_item_detail_content_live_edit_'.$item['id'].'\' , \''.$item['id'].'\' , \''.$item['sku'].'\')"';
			$str.='<div id="xshop_item_detail_live_edit_update" class="btn btn-danger pull-right btn-block" '.$btn.'>Update</div>';
			$item_description = $str;
		}
		//Detail tabs	
		$detail_tab->add('View')->setHtml($item_description);
		$detail_tab->add('View')->setHtml($item_description);
		
		//

		$enquiry_form=$this->add('Form',null,'xshop_item_enquiry_form');
		$enquiry_form->addField('line','name');
		$enquiry_form->addField('line','contact_no');
		$enquiry_form->addField('line','email_id');
		$enquiry_form->addField('text','message');
		$enquiry_form->addSubmit('Send');
		if($enquiry_form->Submitted()){	
			$item_enq_model=$this->add('xShop/Model_itemEnquiry');
			$epan=$this->add('Model_Epan');
			$epan->tryLoadAny();
			// throw new \Exception("Error Processing Request".$item[]);
			$item_enq_model->createNew($enquiry_form['name'],$enquiry_form['contact_no'],$enquiry_form['email_id'],$enquiry_form['message'],$item['id'],$item['sku'],$item['name']);
				
			if($item['enquiry_send_to_self']){
				$item->sendEnquiryMail($epan['email_id'],$enquiry_form['name'],$enquiry_form['contact_no'],$enquiry_form['email_id'],$enquiry_form['message'],$enquiry_form,$item['name'],$item['SKU']);
			}
				
			if($item['enquiry_send_to_supplier']){
				// $item_enq_model->createNew($enquiry_form['name'],$enquiry_form['contact_no'],$enquiry_form['email_id'],$enquiry_form['message'],$item['id'],$item['sku'],$item['name']);				
				$item->sendEnquiryMail($item['supplier_email_id'],$enquiry_form['name'],$enquiry_form['contact_no'],$enquiry_form['email_id'],$enquiry_form['message'],$enquiry_form,$item['name'],$item['sku']);
			}

			if($item['enquiry_send_to_manufacturer']){
				// $item_enq_model->createNew($enquiry_form['name'],$enquiry_form['contact_no'],$enquiry_form['email_id'],$enquiry_form['message'],$item['id'],$item['sku'],$item['name']);
				$item->sendEnquiryMail($item['manufacturer_email_id'],$enquiry_form['name'],$enquiry_form['contact_no'],$enquiry_form['email_id'],$enquiry_form['message'],$enquiry_form,$item['name'],$item['SKU']);
				// throw new \Exception($item['manufacturer_email_id']);
			}	
			
			if($item['item_enquiry_auto_reply']){
				$item->sendEnquiryMail($enquiry_form['email_id'],null,null,null,null,null,$item['name'],$item['sku'],'1');
			}

			$enquiry_form->js(true,$enquiry_form->js()->reload())->univ()->successMessage('Enquiry Form Send Success fully')->execute();
		}
	}

	function defaultTemplate(){
		$this->app->pathfinder->base_location->addRelativeLocation(
		    'epan-components/'.__NAMESPACE__, array(
		        'php'=>'lib',
		        'template'=>'templates',
		        'css'=>'templates/css',
		        'js'=>'templates/js',
		    )
		);
		
		return array('view/xShop-ItemDetail');
	}
}