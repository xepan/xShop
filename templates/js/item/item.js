jQuery.widget("ui.xepan_xshop_item",{
	
	_create: function(){
		$(this.element).css('border','2px solid red');
		console.log($(this.element).data('xshop-item-id'));

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

	}
	
});