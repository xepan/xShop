Layout_Tool = function(parent){
	var self = this;
	this.parent=undefined;
	// this.text = params.text != undefined?params.text:'Enter Text';
	this.init = function(designer,canvas){
		this.designer_tool = designer;
		this.canvas = canvas;
	}

	this.renderTool = function(parent,page_name){
		var self = this;
		// console.log(page_name);
		$.each(self.designer_tool.pages_and_layouts[page_name],function(index,layout){
			self.layout_name  = index;
			//display the page button 
			$('.xshop-designer-show-page').show();
			//hide page button view
			$('.xshop-designer-pagelayout').hide();
			//remove all previous added layout
			$('.xshop-designer-layoutbtn').remove();
			//display the layout view
			$('.xshop-designer-layout').show();
			//add new Layout of current selected page
			layout_btn = $('<div class="xshop-designer-layoutbtn"><h3>'+index+'</h3></div>').appendTo($.find('.xshop-designer-layout'));
				layout_btn.click(function(){
					self.designer_tool.current_page = page_name;
					self.designer_tool.current_layout = self.layout_name;
					self.designer_tool.render();
					console.log(self.designer_tool);
				});
		});
		// console.log(self.designer_tool);
	}	
}

PageLayout_Component = function (params){
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

		show_page_btn = $('<div class="xshop-designer-show-page glyphicon glyphicon-thumbs-up" style="display:none;">Show Page</div>').appendTo($.find(".xshop-designer-tool-bottombar"));
		show_page_btn.click(function(){
			$('.xshop-designer-pagelayout').show();
			$('.xshop-designer-layout').hide();	
		});

		page_layout_toolbar = $('<div class="xshop-designer-pagelayout"></div>').appendTo($.find(".xshop-designer-tool-bottombar"));

		layout_div = $('<div class="xshop-designer-layout"></div>').appendTo($.find(".xshop-designer-tool-bottombar"));
		$.each(self.designer_tool.pages_and_layouts,function(index,page){
			page_btn = $('<div class="xshop-designer-pagebtn"><h3>'+index+'</h3></div>').appendTo(page_layout_toolbar);
			page_btn.click(function(event){
				layout = new Layout_Tool(layout_div);
				layout.init(self.designer_tool,self.canvas);
				layout.renderTool(layout_div,index);
			});
		});			
	}
}