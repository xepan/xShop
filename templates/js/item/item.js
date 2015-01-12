jQuery.widget("ui.xepan_xshop_item",{
	
	_create: function(){
		var self = this;
		
		// $(this.element).css('border','2px solid red');
		// console.log($(this.element).data('xshop-item-id'));

		$(this.element).find('.xshop-item-enquiry-form-btn').click(function(event){
			$.univ().frameURL('HELLO','index.php');
		});

		$(this.element).hover(
			function(event){
				$(this).find('.xshop-item-show-on-hover').visible();
			},
			function(event){
				$(this).find('.xshop-item-show-on-hover').invisible();
			}
		);

		// add to cart management
		$(this.element).find('.xshop-item-add-to-cart').click(function(){
			alert(self.element.data('xshop-item-id'));
		})

	},

});