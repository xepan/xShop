<?php

namespace xShop;

class Controller_RenderText extends \AbstractController {
	public $options =array();
	public $phpimage;

	function init(){
		parent::init();
		$options = $this->options;
		// print_r($options);
		// exit;
		if($options['bold'] and !$options['italic']){
			if(file_exists(getcwd().'/epan-components/xShop/templates/fonts/'.$options['font'].'-Bold.ttf'))
				$options['font'] = $options['font'].'-Bold';
			// else
				// $draw->setFontWeight(700);
		}

		if($options['italic'] and !$options['bold']){
			if(file_exists(getcwd().'/epan-components/xShop/templates/fonts/'.$options['font'].'-Italic.ttf'))
				$options['font'] = $options['font'].'-Italic';
			else
				$options['font'] = $options['font'].'-Regular';
		}

		if($options['italic'] and $options['bold']){
			if(file_exists(getcwd().'/epan-components/xShop/templates/fonts/'.$options['font'].'-BoldItalic.ttf'))
				$options['font'] = $options['font'].'-BoldItalic';
			else
				$options['font'] = $options['font'].'-Regular';
		}
		if(!$options['bold'] and !$options['italic'])
			$options['font'] = $options['font'] .'-Regular';

		$font_path = getcwd().'/epan-components/xShop/templates/fonts/'.$options['font'].'.ttf';
		// echo $font_path;
		$p = new \PHPImage($options['desired_width'],10);
		$p->setFont($font_path);
		$p->setFontSize($options['font_size']);
	    $p->textBox($options['text'], array('width' => $options['desired_width'], 'x' => 0, 'y' => 0));
	    $size = $p->getTextBoxSize($options['font_size'], 0, $font_path, $p->last_text);

		$new_width = abs($size[0]) + abs($size[2]); // distance from left to right
		$new_height = abs($size[1]) + abs($size[5]); // distance from top to bottom

	    $p1 = new \PHPImage($options['desired_width'] , $new_height); 
	    $p1->setFont($font_path);
		$p1->setFontSize($options['font_size']);
	    $p1->setTextColor($p1->hex2rgb($options['text_color']));
	    // $p1->setAlignHorizontal('right');
	    $p1->textBox($options['text'], array('width' => $new_width, 'x' => 0, 'y' => 0));

	    $this->phpimage = $p1;
	    $this->new_height = $new_height;

		if($this->options['rotation_angle']){
			$p->rotate($this->options['rotation_angle']);
		}
	}

	function show($type='png',$quality=3, $base64_encode=true, $return_data=false){
		$this->phpimage->setOutput('png',3);
		return $this->phpimage->show($base64_encode,$return_data);
	}
}