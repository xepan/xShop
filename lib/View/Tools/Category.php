<?php
namespace xShop;
class View_Tools_Category extends \componentBase\View_Component{
	function init(){
		parent::init();

		$application=$this->html_attributes['xshop_application_id'];
		$categories = $this->add('xShop/Model_Category',array('table_alias'=>'mc'));

		//Only Category Description
		if($this->html_attributes['show-category-description-only'] and $_GET['xsnb_category_id']){
			$cat_m = $this->add('xShop/Model_Category')->load($_GET['xsnb_category_id']);
			$this->add('View')->setHTML($cat_m['description']);
			return;
		}


		$this->template->trySet('no_of_cols',$this->html_attributes['xshop-category-grid-column']);
		
		$width = 12;
		if($this->html_attributes['show-category-column']){	
			$width = 12 / $this->html_attributes['show-category-column'];
		}
		$this->col = 'col-md-'.$width.' col-sm-'.$width.' col-xl-'.$width;
		// Define no of sub-category show in parent category
		if($this->html_attributes['xshopcategoryshowlist']){
			$this->template->trySet('xshopcategoryshowlist',$this->html_attributes['xshopcategoryshowlist']);
		}
		
		if(!$application){
			$this->add('View_Error')->set('Please Select Application or First Create Application');
			return;
		}
		elseif(!$this->html_attributes['xshop_category_url_page']){
			$this->add('View_Error')->set('Please Specify Category URL Page Name (epan page name like.. about,contactus etc..)');		
			return;
		}else{
			
			$categories->addCondition('application_id',$application);		
			$categories->addCondition('is_active',true);
			$categories->setOrder('order_no','asc');

			//todo OR Condition Using _DSQL 
	        $categories->addCondition(
	        	$categories->_dsql()->orExpr()
	            	->where('mc.parent_id', null)
	            	->where('mc.parent_id', 0)
	            	);
	        // $categories->addCondition('parent_id',Null);
	        $categories->tryLoadAny();
	        if(!$categories->loaded()){
	        	$this->add('View_Error')->setHTML('No Category Found in Selected Application');
	        	return;
	        }

			$output ="<div class='body epan-sortable-component epan-component  ui-sortable ui-selected'>";
			$output ="<ul class='sky-mega-menu sky-mega-menu-anim-slide sky-mega-menu-response-to-stack'>";
					foreach ($categories as $junk_category) {
					$output .= $this->getText($categories,$this->html_attributes['xshop_category_url_page']);
					}
			$output.="</ul></div>";
			$this->setHTML($output);
		}
		
		//loading custom CSS file	
		$category_css = 'epans/'.$this->api->current_website['name'].'/xshopcategory.css';
		$this->api->template->appendHTML('js_include','<link id="xshop-category-customcss-link" type="text/css" href="'.$category_css.'" rel="stylesheet" />'."\n");
	}

	function getText($category,$page_name){
		$item=$this->add('xShop/Model_Item');
		$cat_item_j=$item->join('xshop_category_item.item_id');
		$cat_item_j->addField('category_id');
		$item->addCondition('category_id',$category->id);
		$item->setOrder('sale_price','asc');

		$item->tryLoadAny();

		if($category->ref('SubCategories')->count()->getOne() > 0){
			$sub_category = $category->ref('SubCategories');
			$output = "<li aria-haspopup='true' class='xshop-category'>";
			$output .="<a href='".$this->api->url(null,array('subpage'=>$page_name,'xsnb_category_id'=>$category->id))."'>";
			$output .= $category['name'];
			$output .="</a>" ;
			$output .= "<div class='grid-container3'>";
			$output .= "<ul>";
			foreach ($sub_category as $junk_category) {
				$output .= $this->getText($sub_category,$page_name);
			}
			$output .= "</ul>";
			$output .= "</div>";
			$output .= "</li>";
		}else{
			// throw new \Exception($category['id'], 1);
			if($this->html_attributes['xshop_category_layout']=='Thumbnail'){
				$output = "<li class='text-center ".$this->col."'><a href='".$this->api->url(null,array('subpage'=>$page_name,'xsnb_category_id'=>$category->id))."'><img src='$category[image_url]' /><div class='sky-menu-thumbnail-name'>".$category['name']."</div></a></li>";
			}else{
				$output = "<li><a href='".$this->api->url(null,array('subpage'=>$page_name,'xsnb_category_id'=>$category->id))."'>".$category['name'];
 				if($this->html_attributes['xshop-category-show-price'])
					$output.= " " . $item['sale_price'];
				$output.="</a></li>";
			}

		}

		return $output;
	}
	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html

	function defaultTemplate(){
		$l=$this->api->locate('addons',__NAMESPACE__, 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons',__NAMESPACE__),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css',
		  		'js'=>'templates/js'
				)
			)->setParent($l);

		if($this->html_attributes['xshop_category_layout']=='Horizontal')
			return array('view/xShop-Category-Horizontal');
		elseif($this->html_attributes['xshop_category_layout']=='Vertical')
			return array('view/xShop-Category-Vertical');
		elseif($this->html_attributes['xshop_category_layout']=='MegaMenu')
			return array('view/xShop-Category-MegaMenu');
		elseif($this->html_attributes['xshop_category_layout']=='Thumbnail')
			return array('view/xShop-Category-Thumbnail');
		else
			return array('view/xShop-Category-Horizontal');
	}
}
