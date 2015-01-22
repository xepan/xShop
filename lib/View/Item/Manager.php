<?php

namespace xShop;

class View_Item_Manager extends \view{
	
	function init(){
		parent::init();

		$tab = $this->add('Tabs');
		$tab->addTabURL('xShop/page/item_basic','Basic',array('item_id'));
		$tab->addTabURL('xShop/page/item_attributes','Attributes');
		$tab->addTabURL('xShop/page/item_qtyandprice','Qty & Price');
		$tab->addTabURL('xShop/page/item_media','Media');
		$tab->addTabURL('xShop/page/item_category','category');
		$tab->addTabURL('xShop/page/item_affliate','affliate');
		$tab->addTabURL('xShop/page/item_preview','preview');
	}
} 