<?php

class page_xShop_page_designer_rendertext extends Page {
	function init(){
		parent::init();

		$zoom= $_GET['zoom'];
		$point_size = $_GET['font_size'];
		$font = $_GET['font'].'-Regular';

		$image = new Imagick();
		$draw = new ImagickDraw();
		$pixel = new ImagickPixel( 'none' );

		$draw->setFillColor($_GET['color']);
		
		$draw->setFontSize($_GET['font_size'] * $zoom * 1.328352013); // Font size to pixel conversion

		if($_GET['bold']=='true'){
			if(file_exists(getcwd().'/epan-components/xShop/templates/fonts/'.$_GET['font'].'-Bold.ttf'))
				$font = $_GET['font'].'-Bold';
			else
				$draw->setFontWeight(700);
		}

		if($_GET['italic']=='true'){
			if(file_exists(getcwd().'/epan-components/xShop/templates/fonts/'.$_GET['font'].'-Italic.ttf'))
				$font = $_GET['font'].'-Italic';
			else
				$font = $_GET['font'].'-Regular';
		}

		if($_GET['italic']=='true' and $_GET['bold']=='true'){
			if(file_exists(getcwd().'/epan-components/xShop/templates/fonts/'.$_GET['font'].'-BoldItalic.ttf'))
				$font = $_GET['font'].'-BoldItalic';
			else
				$font = $_GET['font'].'-Regular';
		}


		if($_GET['underline']=='true'){
			// 1-Text will be normal 2-Underline 3-Upperline 4- stroke-through  
			$draw->setTextDecoration(2);
		}
		if($_GET['stokethrough']=='true'){
			$draw->setTextDecoration(4);
		}

		$draw->setFont('epan-components/xShop/templates/fonts/'.$font.'.ttf');
		//Text Alignment :: 3-Left 2-Center 1-Right
		if($_GET['alignment_left']=='true')
			$draw->setTextAlignment(3);
		if($_GET['alignment_center']=='true')
			$draw->setTextAlignment(2);
		if($_GET['alignment_right']=='true')
			$draw->setTextAlignment(1);


		$metrics = $image->queryFontMetrics ($draw, $_GET['text']);
		print_r($metrics);
		
		$draw->annotation(0, $metrics['ascender'], $_GET['text']);

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
		// if($_GET['rotation_angle']){	
		// }
		// $draw->rotate(90);

		// $image->drawImage($draw);
		$image->annotateImage($draw,0,$textheight,0, $_GET['text']);
		$image->rotateimage($pixel,$_GET['rotation_angle']);
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