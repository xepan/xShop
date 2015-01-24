jQuery.widget("ui.xepan_xshop_addtocart",{

	options:{
		selected_custom_field_values: {},
		item_id: undefined,
		item_member_design_id: undefined,

		show_qty: false,
		qty_from_set_only: false,
		qty_set: {},
		
		show_custom_fields: false,
		custom_fields:{},
	},

	_create: function(){
		var self = this;

		console.log(self.options);

		if(this.options.show_custom_fields){
			this.populateCustomFields();
		}

		if(this.options.show_qty){
			// this.populateQtyFields();
		}

		this.populateAddToCartButton();
	},

	populateAddToCartButton: function(){
		var self= this;
		add_to_cart_btn = $('<button class="btn btn-default btn-xs">Add To Cart</button>').appendTo(self.element);
		$(add_to_cart_btn).bind('click',self.add_to_cart_handler);
	},

	add_to_cart_handler: function(event){
		alert('Button Clicked');
	},

	populateCustomFields: function(){
		var self = this;

		$.each(self.options.custom_fields, function(custom_field, custom_field_details){
			switch(custom_field_details.type){
				case 'Color':
					$.each(custom_field_details.values, function (color_code, filter_info_object){
					box = $('<div class="xshop-item-custom-color-box"></div>').appendTo(self.element);
					box.css('width','20px');
					box.css('height','20px');
					box.css('float','left');
					box.css('background-color',color_code);
					box.click(function(event){
						self.custom_field_clicked(custom_field,color_code);
					});
				});
				break;
				case 'DropDown':
					select = $('<select class="xshop-item-custom-field-select"></div>').appendTo(self.element);
					$.each(custom_field_details.values, function (custome_field_value, filter_info_object){
						opt = $('<option value="'+custome_field_value+'">'+custome_field_value+'</options>').appendTo(select);
					});
					select.selectmenu({
						change: function(event,data){
							self.custom_field_clicked(custom_field,data.item.value);
						}
					});
				break;
			}


		});


	},

	custom_field_clicked : function(custom_field, value_selected){
		var self = this;

		console.log(custom_field + ' :: ' + value_selected);
		// set as current selected value in widget level scope
		self.options.selected_custom_field_values[custom_field] = value_selected;
		// highlight current custom field and un-highlight others
		// call rate changer function
		// look for any filter to activate
	}


	
});