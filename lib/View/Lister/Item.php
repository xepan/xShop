<?php

namespace xShop;

class View_Lister_Item extends \CompleteLister{
	
	public $html_attributes;
	
	public $order_count = 0;

	function setModel($model,$fields=null){
		parent::setModel($model,$fields);
		if($this->model->count()->getOne() > 0){
			$this->template->tryDel('no_record_found');
		}
	}
	
	function formatRow(){
		$children_array =array();
		$html_objects=array(
					'sectionName' => 'ItemDiv',
					'Content'=>'',
					'class'=>"xshop-item ".$this->html_attributes['column_width'],
					'url'=>'#',
					'tag' => 'ul',
					'style'=>'',
					'extra_attr'=>array(
						'data-xsnb-item-id'=>$this->model->id
						),
					'children'=>$children_array
					);

		$detail_url = $this->app->url(null,array('xsnb_item_id'=>$this->model->id,'subpage'=>$this->html_attributes['xshop-detail-page']))->getURL();

		// handle image
		$image_parent = $html_objects;
		$image_anchor_url = $detail_url;
		$image_anchor_class="xshop-item-image-anchor";

		if($this->html_attributes['xshop_item_fancy_box']){
			$image_anchor_url='index.php?page=image&image='.($this->model->ref('xShop/ItemImages')->tryLoadAny()->get('item_image')?:"epan-components/xShop/templates/images/item_no_image.png"); // TODOOOOOOOOOOOOOOO
			$image_anchor_class.=" fancybox";
		}
		
		$image_parent =& $this->addSectionIF(
							$this->html_attributes['show-image'],
							$html_objects,
							'ItemImage_Facny_or_Anchor',
							'',
							$image_anchor_class,
							$image_anchor_url,
							'li/a',
							$this->html_attributes['order-image']
							);
		
		$this->addSectionIF(
			$this->html_attributes['show-image'],
			$image_parent, 
			'ItemImage',
			'',
			'xshop-item-img',
			'index.php?page=image&image='.($this->model->ref('xShop/ItemImages')->tryLoadAny()->get('item_image')?:"epan-components/xShop/templates/images/item_no_image.png")."&width=".$this->html_attributes['item-image-width']."&height=".$this->html_attributes['item-image-height'],
			'img',
			$this->html_attributes['order-image']
			);

		$this->addSectionIF(
			$this->html_attributes['show-offer'] AND $this->model['offer_id'],
			$image_parent,
			'Item Offer',
			'',
			'xshop-item-offer',
			$this->model->ref('offer_id')->get('offer_image'),
			'img',
			$this->html_attributes['order-offer'],
			str_replace('-'," ", $this->model['offer_position'])."position:absolute;"
			);
		$this->addSectionIF(
			$this->html_attributes['show-name'],
			$html_objects,
			'ItemName',
			$this->model['name'],
			'xshop-item-name panel-heading',
			$detail_url,
			'li/a',
			$this->html_attributes['order-name']
			);

		$this->addSectionIF(
			($this->html_attributes['show-old-price'] AND $this->model['show_price']),
			$html_objects,
			'ItemOldPrice',
			$this->model['original_price'],
			'xshop-item-old-price',
			'#',
			'li',
			$this->html_attributes['order-old-price']
			);

		$this->addSectionIF(
			$this->html_attributes['show-price'] AND $this->model['show_price'],
			$html_objects,
			'ItemPrice',
			$this->model['sale_price'],
			'xshop-item-price',
			'#',
			'li',
			$this->html_attributes['order-price']
			);


		$this->addSectionIF(
			$this->html_attributes['show-short-description'],
			$html_objects,
			'ItemDescription',
			$this->model['short_description'],
			'xshop-item-short-description',
			'#',
			'li',
			$this->html_attributes['order-short-description']
			);

		$this->addSectionIF(
			$this->html_attributes['show-add-to-cart'] and !$this->model['is_designable'],
			$html_objects,
			'ItemAddToCartView',
			$this->add('xShop/View_Item_AddToCart',array('name'=>'cust_'.$this->model->id,'item_model'=>$this->model,'show_custom_fields'=>$this->html_attributes['show-custom-fields'],'show_price'=>($this->html_attributes['show-price'] AND $this->model['show_price']) ,'show_qty_selection'=>$this->html_attributes['show-qty-selection'] ))->getHTML(),
			'xshop-item-add-to-cart',
			'#',
			'li',
			$this->html_attributes['order-add-to-cart']
			);
		$this->addSectionIF(
			$this->html_attributes['show-details-in-frame'],
			$html_objects,
			'OpenDetailsInFrame',
			'<i class="glyphicon glyphicon-edit"></i>',
			'xshop-item-details-in-frame-btn btn btn-default ',
			'#', // FrameURL JS CODE for details page
			'button',
			$this->html_attributes['order-details-in-frame']
			);

		$this->addSectionIF(
			$this->html_attributes['show-enquiry-form'],
			$html_objects,
			'OpenEnquiryFormFrame',
			'Enquiry',
			'xshop-item-enquiry-form-btn btn btn-default',
			'#', // FrameURL JS CODE for Enquiry form
			'button',
			$this->html_attributes['order-enquiry-form']
			);

		// $this->addSectionIF(
		// 	$this->html_attributes['show-custom-fields'],
		// 	$html_objects,
		// 	'CustomFields',
		// 	$this->add('xShop/View_Item_CustomeField',array('name'=>'cust_'.$this->model->id,'item_model'=>$this->model))->getHTML(),
		// 	'xshop-item-custom-fields',
		// 	'#',
		// 	'li/div',
		// 	$this->html_attributes['order-custom-fields']
		// 	);


		// short description, add to cart, 
		// <more btn>, <enquiry>, custom fields, reviews stars, Offer (hot new ..) and discounts, specifications, add to compare, add to wishlist, personalized, open in frame url

		$this->addSectionIF(
			$this->html_attributes['show-reviews'],
			$html_objects,
			'ReviewsView',
			$this->add('xShop/View_Item_Review',array('name'=>'review_'.$this->model->id,'item_model'=>$this->model))->getHTML(),
			'xshop-item-reviews',
			'#', // FrameURL JS CODE for Review Details PopOUT
			'li/div',
			$this->html_attributes['order-enquiry-form']
			);

		$this->addSectionIF(
			$this->html_attributes['show-discount'] AND ($this->model['original_price'] - $this->model['sale_price']),
			$html_objects,
			'Discount',
			$this->add('xShop/View_Item_Discount',array('name'=>'discount_'.$this->model->id,'item_model'=>$this->model))->getHTML(),
			'xshop-item-discount',
			'#', 
			'li/div',
			$this->html_attributes['order-discount']
			);

		$this->addSectionIF(
			$this->html_attributes['show-specifications'],
			$html_objects,
			'Specifications',
			$this->add('xShop/View_Item_Specifications',array('name'=>'specifications_'.$this->model->id,'item_model'=>$this->model))->getHTML(),
			'xshop-item-specifications',
			'#',
			'li/div',
			$this->html_attributes['order-specifications']
			);

		$this->addSectionIF(
			$this->html_attributes['show-personalized'] and $this->model['is_designable'],
			$html_objects,
			'Personalize',
			'Personalize',
			'xshop-item-personalize btn btn-success',
			'$(this).univ().location("index.php?subpage='.$this->html_attributes['personalization-page'].'&xsnb_design_item_id='.$this->model->id.'")', // FrameURL JS CODE for Personalized JUMP
			'li/div',
			$this->html_attributes['order-personalized']
			);

		// if(!isset($this->print_r)){
		// 	echo "<pre>";
		// 	print_r($html_objects);
		// 	echo "</pre>";
		// 	$this->print_r=true;
		// }

		$this->current_row_html['item'] = $this->getItemHTML($html_objects);

	}

	function &addSectionIF($if_test,&$parent_secition,$sectionName,$Content,$class,$url,$tag,$order,$style="",$extra_attr=array()){
		$class_ext=$class;
		$style_ext=$style;
		$empty_array=array();
		if($if_test_result = $if_test){
			if($if_test_result == 2){
				// $class_ext .= " xshop-item-show-on-hover";
				// $style_ext .= " visibility:hidden";
			}

			$this->order_count ++;
			if(!$order) $order = $this->order_count;
			if($parent_secition['children'][$order]) $order = $this->order_count;
			$parent_secition['children'][(int)$order] = $this->makeSection($sectionName,$Content,$class_ext,$url,$tag, $style_ext, $extra_attr);
			return $parent_secition['children'][$order];
		}
		return $empty_array;
	}

	function &makeSection($sectionName,$Content,$class,$url,$tag,$style, $extra_attr){
		$children_array=array();
		$section =  array(
				'sectionName' => $sectionName,
				'Content'=>$Content,
				'class'=>$class,
				'url'=>$url,
				'tag' => $tag,
				'style'=>$style,
				'extra_attr'=>$extra_attr,
				'children'=>$children_array
			);
		return $section;
	}

	function getItemHTML($section){
		$str="";
		$str = $this->renderSection($section);
		return $str;
	}

	function renderSection(&$section){
		$html="";
		
		$html.= $this->renderTagOpen($section); // only opening tag creation
		$html.= $section['Content'];
		$chilren = $section['children'];
		uksort($chilren,function($a,$b){
			return $a>$b;
		});
		foreach ($chilren as &$sub_sections) {
			$html .= $this->renderSection($sub_sections);
		}
		$html.= $this->renderTagClose($section); // only opening tag creation
		return $html;
	}

	function renderTagOpen($section){
		$tag_html="";
		$tag_str = $section['tag'];
		$tags= explode("/", $tag_str);

		// wrapper tags
		for($i=0; $i < count($tags)-1; $i++){
			$tag_html .="<".$tags[$i].">";
		}

		// main tag
		$tag=$tags[count($tags)-1];

		switch ($tag) {
			case 'img':
				$url_attr='src';
				break;
			case 'a':
				$url_attr='href';
				break;
			default:
				$url_attr="onClick";
				break;
		}
		if($section['url']=='#') $section['url']= 'javascript:void(0)';

		$extra_attr_str="";
		if(count($section['extra_attr'])){
			foreach ($section['extra_attr'] as $key => $value) {
				$extra_attr_str = $key . '='. $value.' ';
			}
		}

		return $tag_html . "<$tag $url_attr='".$section['url']."' class='".$section['class']."' style='".$section['style']."' $extra_attr_str>";
	}

	function renderTagClose($section){
		$tag_str = $section['tag'];
		$tags= array_reverse(explode("/", $tag_str));

		$tag_html="";
		// wrapper tags
		for($i=0; $i < count($tags); $i++){
			$tag_html .="</".$tags[$i].">";
		}
		return $tag_html;
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
		
		// return array('view/xShop-ItemListerGrid');
		return array('view/xShop-ItemLister');
	}

	function render(){
		// $this->js(true)->_load('item/item')->_load('item/customfield')->_selector('.xshop-item')->xepan_xshop_item();
		
		$this->api->jquery->addStylesheet('fancybox/jquery.fancybox');
		$this->api->template->appendHTML('js_include','<script src="epan-components/xShop/templates/js/fancybox/jquery.fancybox.js"></script>'."\n");
		$this->api->jquery->addStylesheet('fancybox/jquery.fancybox-buttons');
		$this->api->template->appendHTML('js_include','<script src="epan-components/xShop/templates/js/fancybox/jquery.fancybox-buttons.js"></script>'."\n");

		$this->js(true)->_selector('.fancybox')->fancybox(array('openEffect'=>'elastic','closeEffect'=>'elastic'));
		parent::render();
	}	
}


