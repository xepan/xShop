<?php

namespace xShop;

class View_Item_AddToCart extends \View{
	public $item_model;
	public $item_member_design_model;
	public $name;
	public $show_custom_fields=false;
	public $show_qty_selection=false;
	public $options = array();

	function init(){
		parent::init();
		$this->options['item_id'] = $this->item_model->id;
	}

	function render(){
		$this->js(true)->_load('item/addtocart')->xepan_xshop_addtocart($this->options);
		parent::render();
	}
}

/*
options:{
		item_id: undefined,
		item_member_design_id: undefined,

		show_qty: false,
		qty_from_set_only: false,
		qty_set: {},

		show_custom_fields: false,
		custom_fields:{
			size : {
				type: 'DropDown',
				values:[
					{value:9},
					{value:10},
					{
						value: 11,
						filters:{
							color: 'red' // This is filter
						}
					},
				]
			},
			color: {
				type: 'Color',
				values:[
					{value:'red'},
					{
						value:'green',
						filters :{
							size: [9,11] // not available in 9 and 11 sizes
						}
					}
				]
			}
		},
	},
*/