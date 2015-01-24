jQuery.widget("ui.xepan_xshop_addtocart",{
	
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

	_create: function(){
		$(this.element).css('border','2px solid red');
		
		if(this.options.show_custom_fields){
			this.populateCustomFields();
		}

		if(this.options.show_qty){
			this.populateQtyFields();
		}

		this.populateAddToCartButton();
	},

	populateAddToCartButton: function(){
		var self= this;
		add_to_cart_btn = $('<button>Add To Cart</button>').appendTo(self.element);
		$(add_to_cart_btn).bind('click',self.add_to_cart_handler);
	},

	add_to_cart_handler: function(event){
		alert('Button Clicked');
	}


	
});