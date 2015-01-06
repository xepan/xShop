<?php

namespace xShop;

class Grid_Category extends \Grid{
	function init(){
		parent::init();
		
		$this->add_sno();
		$this->addQuickSearch(array('name','parent'));
		$this->addPaginator($ipp=100);
	}

	function recursiveRender(){
		$this->addClass('panel panel-default');
		$this->addClass('mygrid');//Todo for reload of crud->grid 
		$this->js('reload')->reload();//adding trigger 

		parent::recursiveRender();
	}

}