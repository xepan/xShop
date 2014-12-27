<?php

class page_xShop_page_designer_rendertext extends Page {
	function init(){
		parent::init();

		$zoom= $_GET['zoom'];
		$point_size = $_GET['font_size'];


		$image = new Imagick();
		$draw = new ImagickDraw();
		$pixel = new ImagickPixel( 'none' );

		$draw->setFillColor($_GET['color']);
		$draw->setFont('epan-components/xShop/templates/fonts/'.$_GET['font'].'.ttf');
		
		$draw->setFontSize($_GET['font_size'] * $zoom * 1.328352013);

		if($_GET['bold']=='true'){
			$draw->setFontWeight(700);
		}
		if($_GET['underline']=='true'){
			// 1-Text will be normal 2-Underline 3-Upperline 4- stroke-through  
			$draw->setTextDecoration(2);
		}
		if($_GET['stokethrough']=='true'){
			$draw->setTextDecoration(4);
		}

		//Text Alignment :: 3-Left 2-Center 1-Right
		if($_GET['alignment_left']=='true')
			$draw->setTextAlignment(3);
		if($_GET['alignment_center']=='true')
			$draw->setTextAlignment(2);
		if($_GET['alignment_right']=='true')
			$draw->setTextAlignment(1);

		$metrics = $image->queryFontMetrics ($draw, $_GET['default_value']);
		print_r($metrics);
		
		$draw->annotation(0, $metrics['ascender'], $_GET['default_value']);

		//these are the values which accurately described the extent of the text and where it is to be drawn:
		$baseline = $metrics['boundingBox']['y2'];
		$textwidth = $metrics['textWidth'];
		$textheight = $metrics['textHeight'];
		/* New image */
		$image->newImage((int)($textwidth * 1), (int)($textheight * 1), $pixel);

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
		

		/* Create text */
		// $image->annotateImage($draw,10, 10,$_GET['rotation_angle'], $_GET['default_value']);
		$image->drawImage($draw);
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