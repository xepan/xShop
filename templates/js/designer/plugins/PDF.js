PDF_Component = function (params){
	this.parent=undefined;
	this.designer_tool= undefined;
	this.canvas= undefined;
	this.element = undefined;
	this.editor = undefined;

	this.options = {
	};

	this.init = function(designer,canvas){
		this.designer_tool = designer;
		this.canvas = canvas;
	}

	this.initExisting = function(params){

	}

	this.renderTool = function(parent){
		var self=this;
		this.parent = parent;
		tool_btn = $('<div class="btn btn-deault btn-xs"><i class="glyphicon glyphicon-file"></i><br>PDF</div>').appendTo(parent.find('.xshop-designer-tool-topbar-buttonset'));

		// CREATE NEW TEXT COMPONENT ON CANVAS
		tool_btn.click(function(event){
			// create new TextComponent type object
			
		});

	}

	this.render = function(){
		var self = this;
	}
}