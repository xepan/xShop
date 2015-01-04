xShop_Image_Editor = function(parent){
	var self = this;
	this.parent = parent;
	this.current_image_component = undefined;

	this.element = $('<div id="xshop-designer-text-editor" style="display:block" class="xshop-options-editor"></div>').appendTo(this.parent);
	this.image_button_set = $('<div class="btn-group btn-group-xs" role="group"></div>').appendTo(this.element);
	this.image_manager = $('<div class="btn btn-xs"><span class="glyphicon glyphicon-film"></span></div>').appendTo(this.image_button_set);	
	this.image_crop_resize = $('<div class="btn btn-xs"><span class="glyphicon glyphicon-">C&R</span></div>').appendTo(this.image_button_set);
	this.image_replace = $('<div class="btn btn-xs"><span class="glyphicon glyphicon-">Replace</span></div>').appendTo(this.image_button_set);
	this.image_duplicate = $('<div class="btn btn-xs"><span class="glyphicon glyphicon-">Duplicate</span></div>').appendTo(this.image_button_set);
	// this.image_manager = $('<div class="btn btn-xs"><span class="glyphicon glyphicon-film"></span></div>').appendTo(this.image_button_set);

	this.image_manager.click(function(event){
		options ={modal:false,
					width:800	
				};
		$.univ().frameURL('Add Images From...','index.php?page=xShop_page_designer_itemimages',options);
	});

	this.image_crop_resize.click(function(event){
		// var self =this;
		// console.log(self.current_image_component);
		url = self.current_image_component.options.url;		
		
		xx= $('<div class="xshop-designer-image-crop"></div>');
		crop_image = $('<img class="xshop-img" src='+url+'></img>').appendTo(xx);
		x = $('<div></div>').appendTo(crop_image);
		y = $('<div></div>').appendTo(crop_image);
		width = $('<div></div>').appendTo(crop_image);
		height = $('<div></div>').appendTo(crop_image);
		
		xx.dialog({
			minWidth: 800,
			modal:true,
			open: function( event, ui ) {
				$(crop_image).cropper({
				    multiple: true,
				    data: {
					    x: 480,
					    y: 60,
					    width: 640,
					    height: 360
					  },  
					done: function(data) {
						$(x).val(Math.round(data.x));
						$(y).val(Math.round(data.y));
						$(width).val(Math.round(data.width));
						$(height).val(Math.round(data.height));
					    // console.log(Math.round(data.width));
					  }
				});
				var $titlebar = $.find('.ui-dialog-titlebar');
				continue_btn = $('<button class="btn btn-default pull-right">Continue</button>').appendTo($titlebar);
				continue_btn.click(function(){
					self.current_image_component.options.crop_x = $(x).val();
					self.current_image_component.options.crop_y = $(y).val();
					self.current_image_component.options.crop_width = $(width).val();
					self.current_image_component.options.crop_height = $(height).val();
					self.current_image_component.options.crop = true;
					self.current_image_component.render();
					$('.xshop-designer-image-crop').dialog('close');
				});
			},

			close: function( event, ui ) {
				console.log(self.current_image_component.canvas);
			}
		});
		// console.log(self.current_image_component);
		//TODO CROP and RESIZE The Image not No
	});

	this.image_replace.click(function(event){
		options ={modal:false,
					width:800	
				};
		$.univ().frameURL('Add Images From...','index.php?page=xShop_page_designer_itemimages',options);

	});

	this.image_duplicate.click(function(event){
		//TODO CROP and RESIZE The Image not No
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
		width:'400',
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
		alignment_left:false,
		alignment_center:false,
		alignment_right:false,
		// Designer properties
		movable: true,
		colorable: true,
		editable: true,
		default_url:'templates/images/logo.png',
		url:undefined,
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
		// alert('Hi called');
	}

	this.addImage = function(image_url){
		var self=this;
		//create new ImageComponent type object
		var new_image = new Image_Component();
		new_image.init(self.designer_tool,self.canvas, self.editor);
		// feed default values for its parameters
		new_image.x=0;
		new_image.y=0;
		new_image.options.url = image_url;
		//Set Options
		new_image.url = image_url;
		console.log(new_image);
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
	}

	this.renderTool = function(parent){
		var self=this;
		this.parent = parent;
		
		tool_btn = $('<div class="btn btn-deault xshop-designer-image-toolbtn btn-xs"><i class="glyphicon glyphicon-picture"></i><br>Image</div>').appendTo(parent.find('.xshop-designer-tool-topbar-buttonset')).data('tool',self);
		this.editor = new xShop_Image_Editor(parent.find('.xshop-designer-tool-topbar-options'));

		// CREATE NEW TEXT COMPONENT ON CANVAS
		tool_btn.click(function(event){
			self.designer_tool.current_selected_component = undefined;
			options ={modal:false,
					width:800	
				};
			$.univ().frameURL('Add Images From...','index.php?page=xShop_page_designer_itemimages',options);
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
			}).resizable({
				aspectRatio: true,
				autoHide: true,
				handles: "ne,se,sw,nw",

				stop:function(e,ui){
					self.options.x = ui.position.left / self.designer_tool.zoom;
					self.options.y = ui.position.top / self.designer_tool.zoom;
					self.options.width = ui.originalSize.width;
					self.options.height = ui.originalSize.height;
					self.render();
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
					crop_x: self.options.crop_x,
					crop_y: self.options.crop_y,
					crop_height: self.options.crop_height,
					crop_width: self.options.crop_width,
					replace_image: self.options.replace_image,
					rotation_angle:self.options.rotation_angle,
					url:self.options.url,
					crop:self.options.crop,
					zoom: self.designer_tool.zoom,
					width:self.options.width,
					height:self.options.height
					},
		})
		.done(function(ret) {
			console.log(self);
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