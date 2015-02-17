<?php

namespace xShop;

class Grid_Quotation extends \Grid{

	function setModel($m){
		parent::setModel($m);
		
		$this->addColumn('expander','items',array('page'=>'xShop_page_owner_quotation_items','descr'=>'Items'));
		// $this->addFormatter('edit','myedit');
	}

	function format_myedit($field){
		if($this->model->status() == 'approved')
			$this->current_row_html[$field]='';
	}
	

}	