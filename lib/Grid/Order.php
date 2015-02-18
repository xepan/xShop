<?php

namespace xShop;

class Grid_Order extends \Grid {

	function setModel($model,$fields=null){

		parent::setModel($model,array(
										'order_id','order_from','on_date',
										'amount','discount_voucher',
										'discount_voucher_amount','net_amount',
										'billing_address','shipping_address',
										));

		$this->addColumn('expander','details',array('page'=>'xShop_page_owner_order_detail','descr'=>'Details'));
		$this->addColumn('Button','print');

		if($_GET['print']){			
			$this->js()->univ()->newWindow($this->api->url("xShop/page_printorder",array('order_id'=>$_GET['print'],'cut_page'=>1,'subpage'=>'xshop-junk')),null,'height=689,width=1246,scrollbar=1')->execute();
		} 

		$this->addPaginator(100);
		$this->addQuickSearch(array('order_id'));

	}	

}