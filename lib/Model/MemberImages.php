<?php

namespace xShop;

class Model_MemberImages extends \Model_Table {
	var $table= "xshop_member_images";
	function init(){
		parent::init();
		
		$this->hasOne('xShop/MemberDetails','member_id');

		$f = $this->add('filestore/Field_Image','image_id')->mandatory(true);
		// $f = $this->addField('image_url');//->mandatory(true)->display(array('form'=>'ElImage'))->group('a~12~<i class="glyphicon glyphicon-picture"></i> Media Management');
		$f->icon ="glyphicon glyphicon-picture~blue";
		$f = $this->addField('alt_text')->group('a~11~bl');
		$f->icon ="glyphicon glyphicon-pencil~blue";
		$f = $this->addField('title')->group('a~11~bl');
		$f->icon ="glyphicon glyphicon-pencil~blue";

		$this->add('dynamic_model/Controller_AutoCreator');		
	}
}