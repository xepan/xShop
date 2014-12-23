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
		$this->addcolumn('Button','duplicate');
		$this->addcolumn('expander','customfields');	
		// $form = $this->add('Form');
		// $selected_field = $form->addField('line','select');
		// $form->addSubmit();


		parent::recursiveRender();
	}

}