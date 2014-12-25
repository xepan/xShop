<?php

class page_xShop_page_designer_fonts extends Page {
	function init(){
		parent::init();
		
		$this->api->addLocation(array(
            'ttf'=>array('epan-components/xShop/templates/fonts')
        ))->setParent($this->api->pathfinder->base_location);

		$p=$this->api->pathfinder->searchDir('ttf','.');
		sort($p);
		// print_r($p);
		$m= $this->add('Model');
		$m->setSource('Array',$p);
		$opts="";
		
		foreach ($m as $junk) {
			$opts .= "<option value='".str_replace(".ttf", "", $m['name'])."'>".str_replace(".ttf", "", $m['name'])."</option>";
		}
		echo $opts;
		exit;
		// $options = '<option>1</option>';
		// echo $options;
		// exit;
	}
}