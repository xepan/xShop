jQuery.widget("ui.xepan_xshop_addtocart",{

	options:{
		selected_custom_field_values: {},
		fields_and_their_types:{},
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
			self.options.fields_and_their_types[custom_field] = custom_field_details.type;
			switch(custom_field_details.type){
				case 'Color':
					color_box = $('<div class="xshop-item-custom-color-box '+custom_field+'"></div>').appendTo(self.element);
					$.each(custom_field_details.values, function (color_code, filter_info_object){
					box = $('<div class="xshop-item-custom-field-value '+ color_code.replace('#','') +'"></div>').appendTo(color_box);
					box.css('width','20px');
					box.css('height','20px');
					box.css('float','left');
					box.css('background-color',color_code);
					box.click(function(event){
						if($(this).hasClass('disabled')){
							alert('Oops');
							return;
						}
						$(this).parent().find('.xshop-item-custom-field-value').removeClass('selected');
						$(this).addClass('selected');
						self.custom_field_clicked(custom_field,color_code);
					});
				});
				break;
				case 'DropDown':
					title =$('<div>'+custom_field+'</div>').appendTo(self.element);
					select = $('<select class="xshop-item-custom-field-select '+custom_field+'"></select>').appendTo(title);
					opt = $('<option value="xshop-undefined" class="xshop-item-custom-field-value">Select</options>').appendTo(select);
					$.each(custom_field_details.values, function (custom_field_value, filter_info_object){
						opt = $('<option value="'+custom_field_value+'" class="xshop-item-custom-field-value '+custom_field_value+'">'+custom_field_value+'</options>').appendTo(select);
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

		// console.log(custom_field + ' :: ' + value_selected);
		// set as current selected value in widget level scope
		self.options.selected_custom_field_values[custom_field] = value_selected;
		// call rate changer function
		// look for any filter to activate
			//enable all fields first
			$(self.element).find('.xshop-item-custom-field-value').attr('disabled',false).removeClass('disabled');
			$(self.element).find('option.xshop-item-custom-field-value').parent().selectmenu('refresh');
			// filter out all selected customfields values
			$.each(self.options.custom_fields, function(custom_field, custom_field_details){
				if(self.options.selected_custom_field_values[custom_field] !=undefined){
					if(custom_field_details['values'][self.options.selected_custom_field_values[custom_field]]['filter_count'] != '0'){
						filters =  custom_field_details['values'][self.options.selected_custom_field_values[custom_field]]['filters'];
						$.each(filters, function(index,a_filter){
							$.each(a_filter, function(filed_to_filter,value_to_filter){
								switch(self.options.fields_and_their_types[filed_to_filter]){
									case 'Color':
										// console.log('.'+filed_to_filter+' .'+ value_to_filter.replace('#',''));
										$(self.element).find('.'+filed_to_filter+' .'+ value_to_filter.replace('#','')).addClass('disabled');
									break;
									case 'DropDown':
										if(self.options.selected_custom_field_values[filed_to_filter]==value_to_filter){
											$("."+filed_to_filter+" option[value='"+value_to_filter+"']").parent().val('xshop-undefined');
										}
										$("."+filed_to_filter+" option[value='"+value_to_filter+"']").attr('disabled', true).parent().selectmenu('refresh');
										// $(self.element).find('.'+filed_to_filter).attr('disabled',true).addClass('disabled');

									break;
								}
								// console.log('filterting '+ filed_to_filter + ' = ' + value_to_filter);
							});
						});
					}
				}
			});

		// alert('TODOs here');
	}


	
});