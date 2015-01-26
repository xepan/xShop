<?php

// ?width=&height=&item_id=&item_member_design_id=&page_name=&layout=

class page_xShop_page_designer_thumbnail extends Page {
	
	public $print_ratio = 3;
	public $false_array=array('undefined','null','false',false);

	function init(){
		parent::init();


		$item_id = $_GET['xsnb_design_item_id']?:false;
		$item_member_design_id = !in_array($_GET['item_member_design_id'], $this->false_array) ? $_GET['item_member_design_id']:false;
		$xsnb_design_template = $_GET['xsnb_design_template']=='true'?true:false;

		// if item_id and design_template .. all ok .. no restrictions

		// if member_item_id then must be member it self or any backend member;
	
		$member = $this->add('xShop/Model_MemberDetails');
		$member_logged_in = $member->loadLoggedIn();

		if($item_member_design_id){
			$target = $this->item = $this->add('xShop/Model_ItemMemberDesign')->tryLoad($item_member_design_id);
			if(!$target->loaded()){
				echo "could not load design";
				exit;
			} 
			$item =$target->ref('item_id');
		}

		if($item_id  and !isset($target)){
			$target = $this->item = $this->add('xShop/Model_Item')->tryLoad($item_id);
			if(!$target->loaded()){
				echo "could not load item";
				exit;
			}
			$item = $target;
		}

		if($item_member_design_id and $target['member_id'] != $member->id AND !$this->api->auth->model->isBackEndUser()){
			echo "You are not allowed to take the design preview ";
			exit;
		}

		// $design = json_decode($this->item['designs'],true);
		// $design = $design['design']; // trimming other array values like px_width etc
		// $design = json_encode($design);

		$design = $target['designs'];
		$design = json_decode($design,true);
		
		$this->px_width = $design['px_width'] ;


		$design=$design['design'];
		// echo "<pre>";
		// print_r($design);
		// echo "</pre>";

		// foreach ($design as $page_name => $layouts) {
		// 	foreach ($layouts as $layout_name => $content) {
		// 		echo $page_name .' ' . $layout_name . ' <br/>';
		// 	}
		// }
		// exit;

		$this->specification = $this->fetchDimensions($item);

		if(!$_GET['width'] AND !$_GET['height']){
			$width = $this->px_width;
			$height = $this->specification['height'] * $width/$this->specification['width'];
		}elseif($_GET['width'] and !$_GET['height']){
			$width = $_GET['width'];
			$height = $this->specification['height'] * $width/$this->specification['width'];
		}elseif(!$_GET['width'] and $_GET['height']){
			$height = $_GET['height'];
			$width = $this->specification['width'] * $height / $this->specification['height'];
		}else{
			$width=$_GET['width'];
			$height=$_GET['height'];
		}

		$this->print_ratio = $width/$this->px_width;

		// echo "width $width <br/>";
		// echo "height $height <br/>";
		// echo "px_width $this->px_width <br/>";
		// echo "print_ratio $this->print_ratio <br/>";
		// exit;

		$img = new \PHPImage($width,$height);

		$content = $design[$_GET['page_name']?:'Front Page'][$_GET['layout_name']?:'Main Layout'];

		$background_options = json_decode($content['background'],true);
		// $background_options['width']= $width * $this->print_ratio;
		// $background_options['height']= $height * $this->print_ratio;
		$this->addImage($background_options,$img);

		// components
		foreach ($content['components'] as $comp) {
			$options = json_decode($comp,true);
			if($options['type']=='Image'){
				$this->addImage($options,$img);
			}
			if($options['type'] == 'Text'){
				$this->addText($options,$img);
			}
		}
		// $pdf->Cell(40,10,$layout_name);
		// $this->putWaterMark($pdf);
		$img->show(false,false);
		// echo "<img src='data:image/jpg;base64, ".$img->show(true,true)."' />";
		exit;
	}

	function putWaterMark($img){
		$pdf->SetAlpha(0.8);
		$pdf->SetFont('Arial','B',30);
	    $pdf->SetTextColor(255,192,203);
	    $pdf->Rotate(45,0,0);
	    $pdf->Text(0,$this->specification['height'],'printonclick.com');
	    $pdf->Rotate(0);
		$pdf->SetAlpha(1);
	    // $this->RotatedText(35,190,'W a t e r m a r k   d e m o',45);
	}

	function addImage($options, $img){
		if($options['url']){
			$options['url'] = dirname(getcwd()).$options['url'];
			$options['width'] = $options['width'] * $this->print_ratio;
			$options['height'] = $options['height'] * $this->print_ratio;
			$options['x'] = $options['x'] * $this->print_ratio;
			$options['y'] = $options['y'] * $this->print_ratio;

			$cont = $this->add('xShop/Controller_RenderImage',array('options'=>$options));
			$data = $cont->show('png',1,false,true);
			$img->addImage($data, $this->pixcelToUnit($options['x']), $this->pixcelToUnit($options['y']), $this->pixcelToUnit($options['width']), $this->pixcelToUnit($options['height']));
		}
	}


	function addText($options, $img){
		if($options['text']){
			$options['desired_width'] = $options['width'] * $this->print_ratio;
			$options['x'] = $options['x'] * $this->print_ratio;
			$options['y'] = $options['y'] * $this->print_ratio;
			$options['font_size'] = $options['font_size'] * ($this->print_ratio / 1.328352013);
			$options['text_color'] = $options['color_formatted'];

			// echo "<pre>";
			// print_r($options);
			// echo "</pre>";
			// exit;

			$cont = $this->add('xShop/Controller_RenderText',array('options'=>$options));
			$options['height'] = $cont->new_height /  $this->print_ratio;

			$data = $cont->show('png',1,false,true);
			// $pdf->MemImage($data, 0, 0, 100, 20);
			$img->addImage($data, $this->pixcelToUnit($options['x']), $this->pixcelToUnit($options['y']), $this->pixcelToUnit($options['desired_width']), $this->pixcelToUnit($options['height'] * $this->print_ratio));
		}
	}

	function fetchDimensions($item){
		$this->specification=array();
		preg_match_all("/^([0-9]+)\s*([a-zA-Z]+)\s*$/", $item->specification('width'),$temp);
		$this->specification['width']= $temp[1][0];
		preg_match_all("/^([0-9]+)\s*([a-zA-Z]+)\s*$/", $item->specification('height'),$temp);
		$this->specification['height']= $temp[1][0];
		$this->specification['unit']=$temp[2][0];

		preg_match_all("/^([0-9]+)\s*([a-zA-Z]+)\s*$/", $item->specification('trim'),$temp);
		$this->specification['trim']= $temp[1][0];

		return $this->specification;
	}

	function getOrientation($specification){
		if($specification['width'] > $specification['height'])
			return 'l';
		else
			return 'p';
	}

	function pixcelToUnit($pixels){
		return $pixels;
		return $this->print_ratio * $pixels;
	}
}