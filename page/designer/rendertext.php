<?php

class page_xShop_page_designer_rendertext extends Page {
	function init(){
		parent::init();

		$options=array();

		$zoom = $options['zoom'] = $_GET['zoom'];
		$options['font_size'] = $_GET['font_size'] * ($zoom / 1.328352013);
		$options['font'] = $_GET['font'];
		$options['text'] = $_GET['text'];
		$options['text_color'] = $_GET['color'];
		$options['desired_width'] = $_GET['width'] * $zoom;

		$options['bold'] = $_GET['bold']=='true'?true:false;
		$options['italic'] = $_GET['italic']=='true'?true:false;
		$options['underline'] = $_GET['underline']=='true'?true:false;

		

		$cont = $this->add('xShop/Controller_RenderText',array('options'=>$options));
		$cont->show('png',3,true,false); // exiting as well

	}
}