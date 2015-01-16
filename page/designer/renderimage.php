<?php

class page_xShop_page_designer_renderimage extends Page {
	
	function init(){
		parent::init();
		echo "<img src='templates/images/logo.png'/>";
		exit;
	}
}