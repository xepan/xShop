<?php

/*
default_value: self.options.default_value,
crop:self.options.crop,
crop_x: self.options.crop_x,
crop_y: self.options.crop_y,
crop_height: self.options.crop_height,
crop_width: self.options.crop_width,
replace_image: self.options.replace_image,
rotation_angle:self.options.rotation_angle,
url:self.options.url,
zoom: self.designer_tool.zoom,
width:self.options.width,
height:self.options.height,
max_width: self.designer_tool.safe_zone.width(),
max_height: self.designer_tool.safe_zone.height(),
auto_fit: is_new_image===true;
*/

class page_xShop_page_designer_renderimage extends Page {
	
	function init(){
		parent::init();
		$options=array();

		$options['url'] = dirname(getcwd()).$_GET['url'];
		if(!file_exists($options['url'])) return;

		$zoom = $options['zoom'] = $_GET['zoom'];
		$options['width'] = $_GET['width'] * $zoom ;
		$options['height'] = $_GET['height'] * $zoom;
		$options['max_width'] = $_GET['max_width'];
		$options['max_height'] = $_GET['max_height'];

		$options['crop'] = $_GET['crop'] =='true';
		$options['crop_x'] = $_GET['crop_x'];
		$options['crop_y'] = $_GET['crop_y'];

		$options['crop_width'] = $_GET["crop_width"];
		$options['crop_height'] = $_GET["crop_height"];

		$options['rotation_angle'] = $_GET['rotation_angle'];

		$cont = $this->add('xShop/Controller_RenderImage',array('options'=>$options));
		$cont->show('png',3,true,false);
		
		return;
			$image = file_get_contents(dirname(getcwd()).$_GET['url']);
	   		$name = tempnam("/tmp", "image");
	   		file_put_contents($name, $image);
	   		$new_image = new Imagick($name);

	   		if($_GET['crop']=='true'){
	   			$new_image->cropImage($_GET['crop_width']*$_GET['zoom'], $_GET['crop_height']*$_GET['zoom'], $_GET['crop_x'], $_GET['crop_y']);
	   			$new_image->thumbnailImage($_GET['crop_width']*$_GET['zoom'],$_GET['crop_height']*$_GET['zoom']);
	   		}else
	   			$new_image->thumbnailImage($_GET['width']*$_GET['zoom'],0);

	   	// 	if($_GET['replace_image']=='true'){
	 			// // $new_image->cropThumbnailImage( $width,0 );
	   	// 		// $new_image->thumbnailImage($_GET['crop_width']*$_GET['zoom'],$_GET['crop_height']*$_GET['zoom']);
	   	// 	}	
	   			
	   		
	   		// header( "Content-Type: image/png");
	   		// echo $new_image;
	   		echo "<img src='data:image/png;base64,".base64_encode($new_image)."' style='max-width:100%; width:100%'/>".rand(1000,9999);
			$new_image->clear();
			$new_image->destroy();
			// echo "<img src='$url'/>";
		exit;
	}
}