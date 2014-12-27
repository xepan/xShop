Save_Component = function (params){
	var self = this;
	this.parent=undefined;
	// this.text = params.text != undefined?params.text:'Enter Text';
	this.init = function(designer,canvas){
		this.designer_tool = designer;
		this.canvas = canvas;
	}

	this.initExisting = function(params){

	}

	this.renderTool = function(parent){
		this.parent = parent;
		this.component_option_array = [];
		tool_btn = $('<div class="btn btn-deault">Save</div>').appendTo(parent.find('.xshop-designer-tool-topbar-buttonset'));
		tool_btn.click(function(event){
			$(self.designer_tool.components).each(function(index,component){
				$(self.designer_tool.components[index].options).each(function(index,options){
					self.component_option_array.push(JSON.stringify(options));
				});
			});
			self.component_option_array = [];

			//Save all option 
			// $.ajax({
			// 		url: 'index.php?page=xShop_page_designer_save',
			// 		type: 'GET',
			// 		data: {param1: 'value1'},
			// 	})
			// 	.done(function(ret) {
			// 		console.log("success");
			// 	})
			// 	.fail(function() {
			// 		console.log("error");
			// 	})
			// 	.always(function() {
			// 		console.log("complete");
			// 	});	

		});
	}
}