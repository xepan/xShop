<?php

class page_xShop_page_designer_rendertext extends Page {
	function init(){
		parent::init();



		$image = new \Imagick();
		$draw = new \ImagickDraw();
		$pixel = new \ImagickPixel( 'none' );

		/* New image */
		$image->newImage(200, 75, $pixel);
		/* Black text */
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