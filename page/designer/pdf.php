<?php

class page_xShop_page_designer_pdf extends Page {
	
	public $print_ratio = 100;

	function init(){
		parent::init();

		$item_id = $_GET['item_id']?:false;
		$item_member_design_id = $_GET['item_member_design_id']!='undefined'?$_GET['item_member_design_id']:false;
		$xsnb_design_template = $_GET['xsnb_design_template']=='true'?true:false;

		if(!$this->api->auth->isLoggedIn()){
			echo "Something doesn't look right";
			exit;
		}

		$member = $this->add('xShop/Model_MemberDetails');
		$member_logged_in = $member->loadLoggedIn();

		if(!$member_logged_in){
			echo "Must be called from a valid loggedn in xShop Member";
			exit;
		}

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

		if($xsnb_design_template and $target['designer_id'] != $member->id){
			echo "You are not allowed to take the template preview";
			exit;
		}

		if(!$xsnb_design_template and $target['member_id'] != $member->id){
			echo "You are not allowed to take the design preview";
			exit;
		}

		$design = $target['designs'];
		$design = json_decode($design,true);
		
		$this->px_width = $design['px_width'] * $this->print_ratio;

		$design=$design['design'];

		$this->specification = $this->fetchDimensions($item);

		$pdf = new FPDF_xPdf($this->getOrientation($this->specification),$this->specification['unit'],array($this->specification['width'],$this->specification['height']));
		foreach ($design as $page_name => $layouts) {
			$pdf->AddPage();
			// $pdf->SetFont('Arial','B',16);
			// $pdf->Cell(40,10,$page_name);
			foreach ($layouts as $layout_name => $content) {
				// background
				$options = json_decode($content['background'],true);
				if(!$options['url']) continue;
				
				$options['url'] = dirname(getcwd()).$options['url'];
				$options['width'] = $options['width'] * $this->print_ratio;
				$options['height'] = $options['height'] * $this->print_ratio;

				$pdf->SetFont('Arial','B',4);
				$cont = $this->add('xShop/Controller_RenderImage',array('options'=>$options));
				$data = $cont->show('png',1,false,true);
				$pdf->MemImage($data, $options['x'], $options['y'], $this->pixcelToUnit($options['width']), $this->pixcelToUnit($options['height']));
				// components
				// $pdf->Cell(40,10,$layout_name);
			}
		}
		$pdf->Output();
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
		return $this->specification['width']/$this->px_width * $pixels;
	}
}