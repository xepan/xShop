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
		var self = this;
		this.page = undefined;
		this.layout = undefined;
		this.parent = parent;
		this.layout_array = [];
		tool_btn = $('<div class="btn btn-deault btn-xs"><i class="glyphicon glyphicon-floppy-saved"></i><br>Save</div>').appendTo(parent.find('.xshop-designer-tool-topbar-buttonset'));
		
		tool_btn.click(function(event){
			self.layout_array = {};
			$.each(self.designer_tool.pages_and_layouts,function(index,pages){
				self.page = index;
				self.layout_array[index]= new Object;
				$.each(self.designer_tool.pages_and_layouts[index],function(index,layout){
					self.layout = index;
					self.layout_array[self.page][index]=new Object;
					self.layout_array[self.page][self.layout]['components']=[];
					$.each(layout.components,function(index,component){
						self.layout_array[self.page][self.layout]['components'].push(JSON.stringify(component.options));
					});
				});
			});
			console.log(self.layout_array);
			$.ajax({
					url: 'index.php?page=xShop_page_designer_save',
					type: 'GET',
					data: {param1: 'value1'},
				})
				.done(function(ret) {
					console.log("success");
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});	

		});
	}
}