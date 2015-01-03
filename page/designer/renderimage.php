<?php

class page_xShop_page_designer_renderimage extends Page {
	
	function init(){
		parent::init();
		
		if($_GET['url']){
			$image = file_get_contents(dirname(getcwd()).$_GET['url']);
	   		$name = tempnam("/tmp", "image");
	   		file_put_contents($name, $image);
	   		$new_image = new Imagick($name);
	   		if($_GET['crop']=='true'){
	   			$new_image->cropImage($_GET['crop_width']*$_GET['zoom'], $_GET['crop_height']*$_GET['zoom'], $_GET['crop_x'], $_GET['crop_y']);
	   		}

	   		$new_image->thumbnailImage($_GET['width']*$_GET['zoom'],$_GET['height']*$_GET['zoom']);
	   		header( "Content-Type: image/png");
	   		echo "<img src='data:image/png;base64,".base64_encode($new_image)."' style='max-width:100%; width:100%'/>".rand(1000,9999);
			$new_image->clear();
			$new_image->destroy();
		}
			// echo "<img src='$url'/>";
		exit;
	}
}