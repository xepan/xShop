<?php

class page_xShop_page_designer_rendertext extends Page {
	function init(){
		parent::init();

		$zoom = $_GET['zoom'];
		$font_size = $_GET['font_size'] * ($zoom / 1.328352013);
		$font = $_GET['font'].'-Regular';
		$text = $_GET['text'];
		$text_color = $_GET['color'];
		$desired_width=$_GET['width'] * $zoom;

		if($_GET['bold']=='true'){
			if(file_exists(getcwd().'/epan-components/xShop/templates/fonts/'.$_GET['font'].'-Bold.ttf'))
				$font = $_GET['font'].'-Bold';
			// else
				// $draw->setFontWeight(700);
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

		$font_path = getcwd().'/epan-components/xShop/templates/fonts/'.$font.'.ttf';

		$p= new PHPImage($desired_width,10);
		$p->setFont($font_path);
		$p->setFontSize($font_size);
	    $p->textBox($text, array('width' => $desired_width, 'x' => 0, 'y' => 0));
	    $size = $p->getTextBoxSize($font_size, 0, $font_path, $p->last_text);

		$new_width = abs($size[0]) + abs($size[2]); // distance from left to right
		$new_height = abs($size[1]) + abs($size[5]); // distance from top to bottom

	    $p1 = new PHPImage($desired_width , $new_height); 
	    $p1->setFont($font_path);
		$p1->setFontSize($font_size);
	    $p1->setTextColor($p1->hex2rgb($text_color));
	    $p1->setAlignHorizontal('right');
	    $p1->textBox($text, array('width' => $new_width, 'x' => 0, 'y' => 0));
	    $p1->setOutput('png',3);
	    $p1->show();
		
		return;

		$image = new Imagick();
		$draw = new ImagickDraw();
		$pixel = new ImagickPixel( 'none' );

		$draw->setFillColor($text_color);
		
		$draw->setFontSize($font_size * $zoom * 1.328352013); // Font size to pixel conversion



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


		$metrics = $image->queryFontMetrics ($draw, $text);
		// print_r($metrics);
		
		$draw->annotation(0, $metrics['ascender'], $text);

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
		// echo $image;
		echo base64_encode($image);
		
		$image->clear();
		$image->destroy();
		$draw->clear();
		$draw->destroy();	
		exit;

	}
}