jQuery.widget("ui.xepan_xshop_item_customfield",{
	
	_create: function(){
		$(this.element).css('border','2px solid red');
		console.log($(this.element).data('xshop-item-id'));

		// save in options
		// on change implement filters
		// on change do rate change
	}
	
});