<?php

namespace xShop;

class Grid_Item extends \Grid{
	function init(){
		parent::init();
		
		$this->add_sno();
		$this->addQuickSearch(array('sku','name','sale_price'));
		$this->addPaginator($ipp=100);
	}

	function setModel($m,$fields){
		parent::setModel($m,$fields);
		// $this->addColumn('expander','details');
		$this->addColumn('expander','categories');
		$this->addColumn('expander','custom_fields');
		$this->addColumn('expander','specifications');
		$this->addColumn('expander','images');
		$this->addColumn('expander','attachments');
		$this->addColumn('actions','actions');
	}

	function init_actions($field){
	    $this->columns[$field]['tpl']=$this->add('GiTemplate')->loadTemplate('column/item-grid');

	    $m=$this->model;

	    $do_flag = $this->add('VirtualPage')->set(function($p)use($m){
	        $name=$m->load($_GET['id'])['name'];
	        // $m->flag();
	        return $p->js()->univ()->alert('You have flagged '.$name)->execute();
	    });

	    $this->on('click','.do-set-default')->univ()->ajaxec(array($do_flag->getURL(), 'id'=>$this->js()->_selectorThis()->closest('tr')->data('id')));
	}
	function format_actions($field){
	    $this->current_row_html[$field] = $this->columns[$field]['tpl']->render();
	}

}