BackgroundImage_Component = function (params){
	this.parent=undefined;
	this.designer_tool= undefined;
	this.canvas= undefined;
	this.element = undefined;
	this.editor = undefined;
	this.xhr = undefined;

	this.options = {
		x:0,
		y:0,
		width:'0',
		height:'0',
		url:'templates/images/logo.png',
		crop_x: false,
		crop_y:false,
		crop_width:false,
		crop_height:false,
		crop:false,
		replace_image: false,
		rotation_angle:0,
		locked: false,
		
		editable: true,
		default_url:'templates/images/logo.png',
		url:undefined,
		auto_fit: false,
		// System properties
		type: 'BackgroundImage'
	};

	this.init = function(designer,canvas, editor){
		var self=this;
		this.designer_tool = designer;
		this.canvas = canvas;
		if(editor !== undefined)
			this.editor = editor;
	}

	this.renderTool = function(parent){
		var self=this;
		this.parent = parent;
		tool_btn = $('<div class="btn xshop-designer-backgroundimage-toolbtn"><i class="glyphicon glyphicon-picture"></i><br>BGI</div>').appendTo(parent.find('.xshop-designer-tool-topbar-buttonset')).data('tool',self);

		tool_btn.click(function(event){
			self.designer_tool.current_selected_component = self.designer_tool.pages_and_layouts[self.designer_tool.current_page][self.designer_tool.current_layout].background;
			options ={modal:false,
					width:800,
					// close:function(){
						// self.designer_tool.current_selected_component = undefined;
					// }
				};
			$.univ().frameURL('Add Images From...','index.php?page=xShop_page_designer_itemimages',options);
		});
	}


	this.render = function(){
		var self = this;
		if(this.options.url == undefined) return;
		if(this.element == undefined){
			this.element = $('<div style="position:absolute;z-index:-10;" class="xshop-designer-component"><span><img></img></span></div>').appendTo(this.canvas);
			self.options.width = self.designer_tool.screen2option(self.designer_tool.canvas.width());
			self.options.height = self.designer_tool.screen2option(self.designer_tool.canvas.height());
		}else{
			this.element.show();
		}
		this.element.css('top',self.designer_tool.option2screen(self.options.y));
		this.element.css('left',self.designer_tool.option2screen(self.options.x));
		this.element.css('width',self.designer_tool.option2screen(self.options.width));
		this.element.css('height',self.designer_tool.option2screen(self.options.height));
		// this.element.find('img').width((this.element.find('img').width() * self.designer_tool.delta_zoom /100));
		// this.element.find('img').height((this.element.find('img').height() * self.designer_tool.delta_zoom/100));

		if(this.xhr != undefined)
			this.xhr.abort();

		this.xhr = $.ajax({
			url: 'index.php?page=xShop_page_designer_renderimage',
			type: 'GET',
			data: {
					default_value: self.options.default_value,
					crop:self.options.crop,
					crop_x: self.options.crop_x,
					crop_y: self.options.crop_y,
					crop_height: self.options.crop_height,
					crop_width: self.options.crop_width,
					replace_image: self.options.replace_image,
					rotation_angle:self.options.rotation_angle,
					url:self.options.url,
					zoom: self.designer_tool.zoom,
					width: self.options.width,
					height: self.options.height
					},
		})
		.done(function(ret) {
			self.element.find('img').attr('src','data:image/jpg;base64, '+ ret).width(self.designer_tool.option2screen(self.options.width));
			self.xhr=undefined;
			// console.log(self);
		})
		.fail(function(ret) {
			// evel(ret);
			console.log("error");
		})
		.always(function() {
			console.log("BackgroundImage complete");
		});
	}

}