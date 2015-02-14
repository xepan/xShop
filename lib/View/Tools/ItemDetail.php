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
		$config_model->tryLoadAny();
		$item=$this->add('xShop/Model_Item');
		if(!$_GET['xsnb_item_id'])
			return;

		$item->load($_GET['xsnb_item_id']);		
		$this->setModel($item);
	
	//======================Name===================
		
		if($this->html_attributes['show-item-name']){
			$str = '<h1 class="xshop-item-detail-name">'.$item['name'].'</h1>';
			$this->template->trySetHtml('item_name' ,$str);
		}

	//======================Sku==================
		if($this->html_attributes['show-item-code']){
			$str = '<div class="col-md-6 col-sm-6 xshop-item-detail-code">'.$item['sku'].'</div>';
			$this->template->trySetHtml('item_sku' ,$str);
		}else
			$this->template->tryDel('item_sku');
	//======================Reviews==============================		
		if($this->html_attributes['show-item-review']){
			$str = '<div class="col-md-6 col-sm-6 xshop-item-detail-review"></div>';
			$this->template->trySetHtml('review' ,$str);
		}else
			$this->template->tryDel('review');
	//======================Date======================
		if($this->html_attributes['show-item-date']){
			$str = '<span class="pull-right xshop-item-detail-date">'.$item['created_at'].'</span>';
			$this->template->trySetHtml('date',$str);		
		}

	//======================Images=====================
		$col_width = "col-md-12 col-sm-12 col-lg-12";
		if($this->html_attributes['show-image']){
			$col_width = "col-md-8 col-sm-8 col-lg-8";
			$str = '<div class="col-md-4 xshop-item-detail-images">';
			$images = $this->add('xShop/View_Tools_ItemImages')->getHTML();
			$images = $str.$images.'</div>';
			$this->template->trySetHTML('item_images',$images);
		}else{
			$this->template->tryDel('item_images');
		}

		$this->template->trySetHtml('item-detail-width',$col_width);

	//=======================Detail HEADING=================================	
		if($this->html_attributes['show-heading']){
			$str = '<div class="text-center"> <h1 class="page page-header">'.$item['name'].'</h1></div>';
			$this->template->trySetHtml('item_detail_heading',$str);
		}
		
	//======================== ITEM SHORT DESCRIPTIONS ===================
		if($this->html_attributes['show-item-short-description'])
			$this->template->trySet('item_offer_supplier_shipping_info',$item['short_description']);

	//=======================Item Price===================================	
		if( $item['show_price'] and $this->html_attributes['show-item-price']){
			$str = '<div class="xshop-item-old-price" onclick="javascript:void(0)">&#8377. '.$item['original_price'].'</div>';
			$str.= '<div class="xshop-item-price" onclick="javascript:void(0)">&#8377. '.$item['sale_price'].'</div>';
			$this->template->trySetHtml('xshop_item_detail_price',$str);
		}
	//===========================Tags ===========================================
		if( $this->html_attributes['show-item-tags'] and $item['tags']){
			$label = "Tags";
			if(trim($this->html_attributes['item-tag-label']) !="")
				$label = $this->html_attributes['item-tag-label'];
			$str = '<div class="xshop-item-detail-title xshop-item-tags-label">'.$label.'</div>';
			$str.= '<div class="xshop-item-detail-tags">'.str_replace(',', " ", $item['tags']).'</div>';
			$this->template->trySetHtml('xshop_item_tags',$str);
		}
		
	//===========================COMMENTS API ===========================
		if($item['allow_comments'] and $this->html_attributes['show-item-comment']){
			if($item['comment_api'] and $config_model['disqus_code'])
				$this->template->trySetHTML('xshop_item_discus',$config_model['disqus_code']);
			else 
				$this->template->trySetHTML('xshop_item_discus',"<div class='alert alert-warning'>Place Your Discus Code and Select Comment Api in Item or Configuration</div>");
		}	

	//======================== CUSTOM BUTTON ==========================
		if($config_model['add_custom_button']){
			if($this->model['add_custom_button']){
				$btn_label = $this->model['custom_button_label']?:$config_model['custom_button_text'];
				$btn_link  = $this->model['custom_button_url']?:$config_model['custom_button_url'];
				$str='<a class="btn btn-default xshop-item-detail-custom-link" href="'.$btn_link.'">'.$btn_label.'</a>';
				$this->template->trySetHTML('xshop_item_custom_btn',$str);
			}else{
				$this->template->tryDel('xshop_item_custom_btn');
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
				if($this->html_attributes['show-cart-section'])
					$this->add('xShop/View_Item_AddToCart',array('name'=>'cust_'.$this->model->id,'item_model'=>$this->model,'show_custom_fields'=>1,'show_price'=>$this->model['show_price'], 'show_qty_selection'=>1),'xshop_item_cart_btn');
			}

	//=====================ITEM AFFILIATES===============================
		if($this->html_attributes['show-item-affiliate']){
			$item_aff_ass = $this->add('xShop/Model_ItemAffiliateAssociation')->addCondition('item_id',$item->id);
			$label = "Affiliate's";
			if(trim($this->html_attributes['item-affiliate-label']) != "")
				$label = $this->html_attributes['item-affiliate-label'];
			$str = '<div class="xshop-item-detail-title xshop-item-affiliate-label">'.$label.'</div>';

			$str .='<div class="xshop-item-affiliate-block">';
			foreach ($item_aff_ass as $junk) {
				$aff = $this->add('xShop/Model_Affiliate')->tryload($item_aff_ass['affiliate_id']);
				$str .= '<div class="xshop-item-affiliate">'.$aff['affiliatetype']." :: ".$aff['company_name']."</div>";
				$aff->unLoad();
			}
			$str .="</div>";
			$this->template->SetHTML('xshop_item_affiliates',$str);
		}

	// =================Item Detail and specification and Attachments===================
		$detail_label = trim($this->html_attributes['item-detail-label'])?:'Description';
		$detail_header = "";
		if($this->html_attributes['show-item-detail-in-tabs']){
			$tabs = $this->add('Tabs',null,'xshop_item_detail_information');
			$detail_tab = $tabs->addTab($detail_label);
		}else{
			$detail_tab = $this;
			$detail_header = '<div class="xshop-item-detail-title xshop-item-detail-label">'.$detail_label.'</div>';
		}
		
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
		$detail_tab->add('View')->setHtml($detail_header.$item_description);
			
		//Specification
		$specification_label = trim($this->html_attributes['item-specification-label'])?:'Specification';
		if($this->html_attributes['show-item-specification']){
			$specification_tab = $this;
			if($this->html_attributes['show-item-detail-in-tabs']){
				$specification_tab = $tabs->addTab($specification_label);
			}else{
				$this->add('View')->setHtml('<div class="xshop-item-detail-title xshop-item-specification-label">'.$specification_label.'</div>');
			}

			$specification = $this->add('xShop/Model_ItemSpecificationAssociation')->addCondition('item_id',$item->id);
			$specification_tab->add('Grid')->setModel($specification,array('specification','value'));	
		}

		//Attachments
		$attachment_label = trim($this->html_attributes['item-attachment-label'])?:'Attachments';
		$attachment_header = "";
		if($item['is_attachment_allow'] and $this->html_attributes['show-item-attachment']){
			$attachment_tab = $this;
			if($this->html_attributes['show-item-detail-in-tabs']){
				$attachment_tab = $tabs->addTab($attachment_label);
			}else{
				$attachment_header = '<div class="xshop-item-detail-title xshop-item-attachment-label">'.$attachment_label.'</div>';
			}

			$attachment_model=$this->add('xShop/Model_Attachments');
			$attachment_model->addCondition('item_id',$item->id);
			$html = "";
			foreach ($attachment_model as $junk) {
				$html .= '<div class="xshop-item-attachment-link"> <a target="_blank" href="'.$attachment_model['attachment_url'];
				$html.= '"</a>'.$attachment_model['name'].'</div>';
			}
			$attachment_tab->add('View')->setHtml($attachment_header.$html)->addClass('xshop-item-attachment');
		}



	//==================Enquiry Form================================
		if($this->html_attributes['show-item-enquiry-form']){
			$label = "Enquiry Form";
			if(trim($this->html_attributes['item-enquiry-form-label']) != "")
				$label = trim($this->html_attributes['item-enquiry-form-label']);
			
			$str = '<div class="xshop-item-detail-title xshop-item-enqyiry-form-label">'.$label.'</div>';
			$this->template->trySetHtml('xshop_item_enquiry_label',$str);

			$enquiry_form=$this->add('Form',null,'xshop_item_enquiry');
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