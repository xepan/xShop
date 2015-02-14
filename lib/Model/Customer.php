<?php

namespace xShop;

class Model_Customer extends Model_MemberDetails{
	function init(){
		parent::init();

		$this->hasMany('xShop/Oppertunity','customer_id');
		$this->hasMany('xShop/Quotation','customer_id');
	}
}