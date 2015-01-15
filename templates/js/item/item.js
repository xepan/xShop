jQuery.widget("ui.xepan_xshop_item",{
	
	qty:1,
	custom_fields: { },
	

	_create: function(){
		var self = this;

		// $(this.element).css('border','2px solid red');
		// console.log($(this.element).data('xshop-item-id'));

		$(this.element).find('.xshop-item-enquiry-form-btn').click(function(event){
			$.univ().frameURL('HELLO','index.php');
		});
		$(this.element).find('.xshop-item-details-in-frame-btn').click(function(event){
			$.univ().frameURL('Details','index.php?page=xShop_page_item_itemdetail&xshop_item_id='+ $(self.element).data('xshop-item-id'));
		});

		$(this.element).hover(
			function(event){
				$(this).find('.xshop-item-show-on-hover').visible();
			},
			function(event){
				$(this).find('.xshop-item-show-on-hover').invisible();
			}
		);

		// ADD TO CART management
		$(this.element).find('.xshop-item-add-to-cart').click(function(){

			var qty_to_add=1;
			// Have Qty Field ??
			if(self.element.find('.shop-item-qty').length){
				qty_to_add = self.element.find('.shop-item-qty').val();
			}

			// all custom fields values
			custom_field_values = {};
			self.element.find('.xshop-item-custom-fields select').each(function(index,cf_field){
				custom_field_values[$(this).attr('field')] = $(this).val();
			});

			$.ajax({
					url: 'index.php?page=xShop_page_addtocart',
					type: 'POST',
					datatype: "json",
					data: { 
						item_id: self.element.data('xsnb-item-id'),
						qty: qty_to_add,
						custome_fields: JSON.stringify(custom_field_values)
					},
				})
				.done(function(ret) {
					eval(ret);
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
		})

	},

});