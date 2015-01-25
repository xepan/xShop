jQuery.widget("ui.xepan_xshop_addtocart",{

	options:{
		selected_custom_field_values: {},
		fields_and_their_types:{},
		item_id: undefined,
		item_member_design_id: undefined,

		show_price: false,
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
			this.populateQtyFields();
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
		// 1. set as current selected value in widget level scope
		self.options.selected_custom_field_values[custom_field] = value_selected;
		
		// 2. rate changer function
		self.getRate();

		// 3. look for any filter to activate
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
	},

	populateQtyFields: function(){
		var self=this;
		// if qty_from_set_only is true
		if(self.options.qty_from_set_only !='0'){
			// add dropdown and add options from qty_sets
			qty_field = $('<select class="xshop-add-to-cart-qty"></select>').appendTo(self.element);
			$.each(self.options.qty_set,function(index,qty){
				var display_name=qty.qty;

				if(qty.name != qty.qty) display_name = qty.name + ' :: '+ qty.qty;

				$('<option value="'+qty.qty+'">'+display_name+'</option>').appendTo(qty_field);
			});
			qty_field.selectmenu({
				change: function(event,ui){
					self.getRate();
				}
			});
		}else{
			// add input box with spinner may be ...
			qty_field = $('<input class="xshop-add-to-cart-qty" type="number"/>').appendTo(self.element);
			qty_field.univ().numericField();
		}
		// add unique class under the self.element to read qty

		$(qty_field).bind('change',function(){
			self.getRate();
		});
		// $(qty_field).bind('blur',function(){
		// 	self.getRate();
		// });
	},

	getRate: function(){
		var self=this;

		if(self.options.show_price){
			var qty_to_add = 1;

			// if show_qty is on ?????????????
				// set qty_to_add = val of qty field value

			$.ajax({
				url: 'index.php?page=xShop_page_getrate',
				type: 'GET',
				datatype: "json",
				data: { 
					item_id: self.options.item_id,
					qty: qty_to_add,
					custome_fields: JSON.stringify(self.options.selected_custom_field_values)
				},
			})
			.done(function(ret) {
				rates = ret.split(',');
				console.log($(self.element).closest('.xshop-item').find('.xshop-item-old-price'));
				$(self.element).closest('.xshop-item').find('.xshop-item-old-price').text(rates[0]);
				$(self.element).closest('.xshop-item').find('.xshop-item-price').text(rates[1]);
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
		}
	}


	
});