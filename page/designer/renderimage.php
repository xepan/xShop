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
		$image_path = dirname(getcwd()).$_GET['url'];
		if(!file_exists($image_path)) return;

		$zoom = $_GET['zoom'];
		$width = $_GET['width'] * $zoom ;
		$height = $_GET['height'] * $zoom;
		$max_width = $_GET['max_width'];
		$max_height = $_GET['max_height'];

		$crop = $_GET['crop'] =='true';
		$crop_x = $_GET['crop_x'];
		$crop_y = $_GET['crop_y'];

		$crop_width = $_GET["crop_width"];
		$crop_height = $_GET["crop_height"];
		

		$p= new PHPImage($image_path);
		
		if($width==0 and $height==0){
			if($p->getWidth() > $p->getHeight()){
				$width = $max_width;
				$height = $width * ($p->getHeight() / $p->getWidth());
			}else{
				$height = $max_height;
				$width = $height * ($p->getWidth() / $p->getHeight());
			}
		}

		$p->resize($width,$height,false,false,false);
		$p->setOutput('png',3);
		$p->show(true);
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