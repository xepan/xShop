<?php

class page_xShop_page_designer_rendertext extends Page {
	function init(){
		parent::init();

		$image = new Imagick();
		$draw = new ImagickDraw();
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
		$draw->setFontWeight(700);
		$draw->setFontSize($_GET['font_size']);

		/* Create text */
		$image->annotateImage($draw, 10, 45, 0, $_GET['default_value']);

		/* Give image a format */
		$image->setImageFormat('png');


		/* Output the image with headers */
		header('Content-type: image/png');
		// echo $image;
		echo "<img src='data:image/png;base64,".base64_encode($image)."' />";	
		exit;

	}
}