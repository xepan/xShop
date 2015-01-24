<?php

namespace xShop;

class View_Item_AddToCart extends \View{
	public $item_model;
	public $item_member_design_model;
	public $name;
	public $show_custom_fields=false;
	public $show_qty_selection=false;
	public $options = array();
	public $qty_set = array();

	function init(){
		parent::init();
		$custom_filed_array = array();

		//Get All Item Associated Custom Field
		$custom_fields = $this->item_model->getAssociatedCustomFields();
		foreach ($custom_fields as $custom_field_id){
			$cf_model = $this->add('xShop/Model_CustomFields')->load($custom_field_id);
			$cf_value_array = $cf_model->getCustomValue();
			$custom_filed_array[$cf_model['name']] = array(
													'type'=>$cf_model['type'],
													'values' => $cf_value_array
												);
		}

		//Get All Item Qnatity Set 
		$qty_set_array = array();
		$qty_set_array = $this->item_model->getQtySet();

		$this->options['item_id'] = $this->item_model->id;
		$this->options['item_member_design_id'] = $this->item_member_design_model['id'];
		$this->options['show_custom_fields'] = $this->show_custom_fields;
		$this->options['show_qty'] = $this->item_member_design_model['id'];
		$this->options['qty_from_set_only'] = $this->show_qty_selection;
		$this->options['qty_set'] = $qty_set_array;
		$this->options['custom_fields'] = $custom_filed_array;

		// echo"<pre>";
		// print_r($this->options);
		// // print_r($qty_set_array);
		// echo"<\pre>";
		// exit;
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
		qty_set: {
			Values:{
				value:{
					name:'Default',
					qty:1,
					old_price:100,
					price:90,
					conditions:{
							custom_fields_condition_id:'custom_field_value_id'
					.......//QyantitySetCondition_id :Custom Fields Calue Id ................
						}
				}
			}
		},

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