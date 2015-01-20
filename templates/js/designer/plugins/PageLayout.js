Layout_Tool = function(parent){
	var self = this;
	this.parent=parent;
	// this.text = params.text != undefined?params.text:'Enter Text';
	this.init = function(designer,canvas,page_tool){
		self.designer_tool = designer;
		self.canvas = canvas;
		self.page_tool = page_tool;

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
					$('.xshop-designer-layoutbtn').removeClass('ui-selected');
					self.page_tool.updateBreadcrumb(self.page_tool.parent);
					$(this).addClass('ui-selected');
				});
			if(index == self.designer_tool.current_layout) {
				$(layout_btn).addClass('ui-selected');
			}else{
				$(layout_btn).removeClass('ui-selected');
			}
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
		this.updateBreadcrumb(this.parent);
	}

	this.initExisting = function(params){

	}
	
	this.updateBreadcrumb = function(parent){
		$('.xshop-designer-show-page').remove();
		this.breadcrumb = $('<ol class="xshop-designer-show-page breadcrumb"></ol>').prependTo(parent);
		this.home_breadcrumb = $('<li><a href="#">Home</a></li>').appendTo(this.breadcrumb);
		this.home_breadcrumb.click(function(){
			$('.xshop-designer-pagelayout').show();
			$('.xshop-designer-layout').hide();
		});

		this.page_breadcrumb = $('<li>'+self.designer_tool.current_page+'</li>').appendTo(this.breadcrumb);
		this.layout_breadcrumb = $('<li>'+self.designer_tool.current_layout+'</li>').appendTo(this.breadcrumb);
		// $(str).appendTo(this.show_page_btn);
	}

	this.renderTool = function(){
		var self = this;
		$('.xshop-designer-pagelayout').show();
		$('.xshop-designer-pagebtn').remove();
		page_layout_toolbar = $('<div class="xshop-designer-pagelayout clearfix"></div>').appendTo($.find(".xshop-designer-tool-bottombar"));
		
		$.each(self.designer_tool.pages_and_layouts,function(index,page){
			page_btn = $('<div class="xshop-designer-pagebtn"><h3>'+index+'</h3></div>').appendTo(page_layout_toolbar).data('page',index);
			page_btn.click(function(event){
				layout = new Layout_Tool();
				layout.init(self.designer_tool,self.canvas,self);
				layout.renderTool(index);
				self.designer_tool.current_page = index;
				self.designer_tool.current_layout = 'Main Layout';
				self.designer_tool.render();
				self.updateBreadcrumb(self.parent);
				$('.xshop-designer-pagebtn').removeClass('ui-selected');
				$(this).addClass('ui-selected');
			});

			if(index == self.designer_tool.current_page) {
				$(page_btn).addClass('ui-selected');
			}else{
				$(page_btn).removeClass('ui-selected');
			}

		});

	}
}