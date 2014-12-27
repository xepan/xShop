Image_Component = function (params){
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
		tool_btn = $('<div class="btn btn-deault">Image</div>').appendTo(parent.find('.xshop-designer-tool-topbar-buttonset'));
		tool_btn.click(function(event){
			console.log($(this).text());
		});
	}
}