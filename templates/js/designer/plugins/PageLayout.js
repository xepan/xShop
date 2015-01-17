Layout_Tool = function(parent){
	var self = this;
	this.parent=parent;
	// this.text = params.text != undefined?params.text:'Enter Text';
	this.init = function(designer,canvas){
		this.designer_tool = designer;
		this.canvas = canvas;
		if(this.parent == undefined)
			this.parent = $('<div class="xshop-designer-layout clearfix"></div>').appendTo($.find(".xshop-designer-tool-bottombar"));
	}

	this.renderTool = function(page_name){
		var self = this;
		// console.log(page_name);
		// console.log(self);
		$.each(self.designer_tool.pages_and_layouts[page_name],function(index,layout){
			//display the page button
			$('.xshop-designer-show-page').show();
			//hide page button view
			$('.xshop-designer-pagelayout').hide();
			//add new Layout of current selected page
			layout_btn = $('<div class="xshop-designer-layoutbtn clearfix"><h3>'+index+'</h3></div>').appendTo($.find('.xshop-designer-layout')).data('layout',index);
				layout_btn.click(function(){
					self.designer_tool.current_page = page_name;
					self.designer_tool.current_layout = $(this).data('layout');
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
	this.init = function(designer,canvas,parent){
		this.designer_tool = designer;
		this.canvas = canvas;
		this.parent = parent;
		this.show_page_btn = $('<div class="xshop-designer-show-page glyphicon glyphicon-thumbs-up" style="display:none;">Show Page</div>').appendTo(parent);
		this.show_page_btn.click(function(){
			$('.xshop-designer-pagelayout').show();
			$('.xshop-designer-layout').hide();	
			$(this).hide();
		});
	}

	this.initExisting = function(params){

	}

	this.renderTool = function(){
		var self = this;
		$('.xshop-designer-pagelayout').show();
		$('.xshop-designer-pagebtn').remove();
		page_layout_toolbar = $('<div class="xshop-designer-pagelayout clearfix"></div>').appendTo($.find(".xshop-designer-tool-bottombar"));
		
		$.each(self.designer_tool.pages_and_layouts,function(index,page){
			page_btn = $('<div class="xshop-designer-pagebtn"><h3>'+index+'</h3></div>').appendTo(page_layout_toolbar);
			page_btn.click(function(event){
				layout = new Layout_Tool();
				layout.init(self.designer_tool,self.canvas);
				layout.renderTool(index);
			});
		});

	}
}