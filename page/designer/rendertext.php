<?php

class page_xShop_page_designer_rendertext extends Page {
	function init(){
		parent::init();

		header('Content-type: image/png');
		$cont = file_get_contents('templates/images/logo.png');
		echo $cont;
		exit;

	}
}