xShop_Image_Editor = function(parent){
	var self = this;
	this.parent = parent;
	this.current_text_component = undefined;

	this.element = $('<div id="xshop-designer-text-editor" style="display:block" class="xshop-options-editor"></div>').appendTo(this.parent);

	this.image_manager = $('<div class="btn btn-xs"><span class="glyphicon glyphicon-align-center"></span></div>').appendTo(this.element);

	this.image_manager.click(function(event){
		$.univ().frameURL('Hello','index.php?page=abcd');
	});

	this.setImageComponent = function(component){
		this.current_image_component  = component;
	}
}

Image_Component = function (params){
	this.parent=undefined;
	this.designer_tool= undefined;
	this.canvas= undefined;
	this.element = undefined;
	this.editor = undefined;
	this.xhr = undefined;

	this.options = {
		x:0,
		y:0,
		width:'100%',
		height:'100%',
		url:'templates/images/logo.png',
		font: "OpenSans",
		font_size: '12',
		color_cmyk:"0,0,0,100",
		color_formatted:"#000000",
		bold: false,
		italic:false,
		underline:false,
		stokethrough:false,
		rotation_angle:0,
		locked: false,
		alignment_left:false,
		alignment_center:false,
		alignment_right:false,
		// Designer properties
		movable: true,
		colorable: true,
		editable: true,
		default_url:'templates/images/logo.png',
		z_index:0,
		resizable: true,
		auto_fit: false,
		frontside:true,
		backside:false,
		multiline: false,
		// System properties
		type: 'Image'
	};

	this.init = function(designer,canvas, editor){
		this.designer_tool = designer;
		this.canvas = canvas;
		if(editor !== undefined)
			this.editor = editor;
	}

	this.initExisting = function(params){

	}

	this.renderTool = function(parent){
		var self=this;
		this.parent = parent;
		
		tool_btn = $('<div class="btn btn-deault btn-xs"><i class="glyphicon glyphicon-picture"></i><br>Text</div>').appendTo(parent.find('.xshop-designer-tool-topbar-buttonset'));
		this.editor = new xShop_Image_Editor(parent.find('.xshop-designer-tool-topbar-options'));

		// CREATE NEW TEXT COMPONENT ON CANVAS
		tool_btn.click(function(event){
			// create new TextComponent type object
			var new_image = new Image_Component();
			new_image.init(self.designer_tool,self.canvas, self.editor);
			// feed default values for its parameters
			new_image.x=0;
			new_image.y=0;
			new_image.url="templates/images/logo.png";
			// add this Object to canvas components array
			
			// console.log(self.designer_tool.current_page);

			self.designer_tool.pages_and_layouts[self.designer_tool.current_page][self.designer_tool.current_layout].components.push(new_image);
			new_image.render();
			
			$(new_image.element).data('component',new_image);
			
			$(new_image.element).click(function(event) {
	            $('.ui-selected').removeClass('ui-selected');
	            $(this).addClass('ui-selected');
	            $('.xshop-options-editor').hide();
	            self.editor.element.show();
	            self.designer_tool.option_panel.show();
	            self.designer_tool.freelancer_panel.FreeLancerComponentOptions.element.show();
	            self.designer_tool.current_selected_component = new_image;
	            self.editor.setImageComponent(new_image);
	            self.designer_tool.freelancer_panel.setComponent($(this).data('component'));
		        event.stopPropagation();
			});
		});


	}


	this.render = function(){
		var self = this;
		if(this.element == undefined){
			this.element = $('<div style="position:absolute" class="xshop-designer-component"><span></span></div>').appendTo(this.canvas);
			this.element.draggable({
				containment: 'parent',
				smartguides:".xshop-designer-component",
			    tolerance:5,
				stop:function(e,ui){
					var position = ui.position;
					self.options.x = position.left / self.designer_tool.zoom;
					self.options.y = position.top / self.designer_tool.zoom;
				}
			});
		}

		this.element.css('top',self.options.y  * self.designer_tool.zoom);
		this.element.css('left',self.options.x * self.designer_tool.zoom);
		// this.element.find('img').width((this.element.find('img').width() * self.designer_tool.delta_zoom /100));
		// this.element.find('img').height((this.element.find('img').height() * self.designer_tool.delta_zoom/100));

		if(this.xhr != undefined)
			this.xhr.abort();

		this.xhr = $.ajax({
			url: 'index.php?page=xShop_page_designer_renderimage',
			type: 'GET',
			data: {default_value: self.options.default_value,
					color: self.options.color_formatted,
					font: self.options.font,
					font_size: self.options.font_size,
					bold: self.options.bold,
					italic: self.options.italic,
					underline:self.options.underline,
					stokethrough:self.options.stokethrough,
					rotation_angle:self.options.rotation_angle,
					alignment_left:self.options.alignment_left,
					alignment_right:self.options.alignment_right,
					alignment_center:self.options.alignment_center,
					zoom: self.designer_tool.zoom
					},
		})
		.done(function(ret) {
			$(ret).appendTo(self.element.find('span').html(''));
			self.xhr=undefined;
		})
		.fail(function(ret) {
			// evel(ret);
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		

		// this.element.text(this.text);
		// this.element.css('left',this.x);
		// this.element.css('top',this.y);
	}

}