<?php

namespace xShop;
class Model_PartyType extends \Model_Table {
	var $table= "xshop_partytype";
	function init(){
		parent::init();

		//TODO for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
	
		$this->addField('name')->hint('comma separated value');
		$this->hasMany('xShop/Party','partytype_id');
		

		$this->add('dynamic_model/Controller_AutoCreator');

		}

	}