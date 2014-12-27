<?php

class page_xShop_page_designer_rendertext extends Page {
	function init(){
		parent::init();

		$draw = new ImagickDraw();
		$image = new Imagick();
		$pixel = new ImagickPixel( 'none' );

		/* New image */
		$image->newImage(200, 75, $pixel);

		$profiles = $image->getImageProfiles('*', false); 
		$image->setImageColorspace (Imagick::COLORSPACE_CMYK); 
	   	// we're only interested if ICC profile(s) exist 
	   	$has_icc_profile = (array_search('icc', $profiles) !== false); 
	   	// if it doesnt have a CMYK ICC profile, we add one 
	   	if ($has_icc_profile === false) { 
	       $icc_cmyk = file_get_contents(dirname(__FILE__).'/USWebUncoated.icc'); 
	       $image->profileImage('icc', $icc_cmyk); 
	       unset($icc_cmyk); 
	   	} 
	   	// then we add an RGB profile 
	   	$icc_rgb = file_get_contents(dirname(__FILE__).'/sRGB_v4_ICC_preference.icc'); 
	   	$image->profileImage('icc', $icc_rgb); 
	   	unset($icc_rgb); 
	   	
		/* Black text */
		// echo $_GET['color'];
		// $draw->setFillColor("cmyk(".$_GET['color'].")");
		$draw->setFillColor($_GET['color']);
		/* Font properties */
		$draw->setFont('epan-components/xShop/templates/fonts/'.$_GET['font'].'.ttf');
		$draw->setFontSize($_GET['font_size']);
		if($_GET['bold']=='true'){
			$draw->setFontWeight(700);
		}
		// 1-Text will be normal 2-Underline 3-Upperline 4- stroke-through  
		if($_GET['underline']=='true'){
			echo"underline";
			$draw->setTextDecoration(2);
		}
		if($_GET['stokethrough']=='true'){
			echo"stoke";
			$draw->setTextDecoration(4);
		}

		//Text Alignment :: 3-Left 2-Center 1-Right
		if($_GET['alignment_left']=='true')
			$draw->setTextAlignment(3);
		if($_GET['alignment_center']=='true')
			$draw->setTextAlignment(2);
		if($_GET['alignment_right']=='true')
			$draw->setTextAlignment(1);

		/* Create text */
		$image->annotateImage($draw,50, 50,$_GET['rotation_angle'], $_GET['default_value']);
		
		/* Give image a format */
		$image->setImageFormat('png');


		/* Output the image with headers */
		header('Content-type: image/png');
		// echo $image;
		echo "<img src='data:image/png;base64,".base64_encode($image)."' />";	
		
		$image->clear();
		$image->destroy();
		$draw->clear();
		$draw->destroy();	
		exit;

	}
}