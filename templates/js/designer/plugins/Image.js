xShop_Image_Editor = function(parent){
	var self = this;
	this.parent = parent;
	this.current_image_component = undefined;

	this.element = $('<div id="xshop-designer-image-editor" style="display:block" class="xshop-options-editor"></div>').appendTo(this.parent);
	this.image_button_set = $('<div class="btn-group" role="group"></div>').appendTo(this.element);
	// this.image_manager = $('<div class="btn "><span class="glyphicon glyphicon-film"></span></div>').appendTo(this.image_button_set);
	this.image_crop_resize = $('<div class="btn "><span class="glyphicon glyphicon-">Crop</span></div>').appendTo(this.image_button_set);
	// this.image_replace = $('<div class="btn "><span class="glyphicon glyphicon-">Replace</span></div>').appendTo(this.image_button_set);
	// this.image_duplicate = $('<div class="btn "><span class="glyphicon glyphicon-">Duplicate</span></div>').appendTo(this.image_button_set);
	// this.image_manager = $('<div class="btn "><span class="glyphicon glyphicon-film"></span></div>').appendTo(this.image_button_set);
	this.image_remove = $('<div class="btn"><span class="glyphicon glyphicon-empty">Remove</span></div>').appendTo(this.image_button_set);

	// this.image_manager.click(function(event){
	// 	options ={modal:false,
	// 				width:800	
	// 			};
	// 	$.univ().frameURL('Add Images From...','index.php?page=xShop_page_designer_itemimages',options);
	// });
	this.image_remove.click(function(){
		dt  = self.current_image_component.designer_tool;
		$.each(dt.pages_and_layouts[dt.current_page][dt.current_layout].components, function(index,cmp){
			if(cmp === dt.current_selected_component){
				// console.log(self.pages_and_layouts);
				$(dt.current_selected_component.element).remove();
				dt.pages_and_layouts[dt.current_page][dt.current_layout].components.splice(index,1);
				dt.current_selected_component = null;
				dt.option_panel.hide();
			}
		});
	});

	this.image_crop_resize.click(function(event){
		// var self =this;
		// console.log(self.current_image_component);
		event.preventDefault();
		event.stopPropagation();
		url = self.current_image_component.options.url;		
		o = self.current_image_component.options;
		
		xx= $('<div class="xshop-designer-image-crop"></div>').appendTo(self.element);
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
					aspectRatio: o.width / o.height,
				    multiple: true,
				    data: {
					    x: o.crop == true? o.crop_x: 0,
					    y: o.crop == true? o.crop_y: 0,
					    width: o.crop == true? o.crop_width: $(crop_image).width(),
					    height: o.crop == true? o.crop_height: $(crop_image).height()
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
			},

			close: function( event, ui ) {
				console.log(self.current_image_component.canvas);
			},

			buttons: {
				Continue: function(){
					self.current_image_component.options.crop = true;
					self.current_image_component.options.crop_x = $(x).val();
					self.current_image_component.options.crop_y = $(y).val();
					self.current_image_component.options.crop_width = $(width).val();
					self.current_image_component.options.crop_height = $(height).val();
					self.current_image_component.render();
					$(this).dialog('close');
				}
			}
		});
		// console.log(self.current_image_component);
		//TODO CROP and RESIZE The Image not No
	});

	// this.image_replace.click(function(event){
	// 	options ={modal:false,
	// 				width:800	
	// 			};
	// 	$.univ().frameURL('Add Images From...','index.php?page=xShop_page_designer_itemimages',options);

	// });

	// this.image_duplicate.click(function(event){
	// 	//TODO CROP and RESIZE The Image not No
	// });

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
		//Set Options
		new_image.options.x=0;
		new_image.options.y=0;
		new_image.options.url = image_url;
		// console.log(new_image);
		// add this Object to canvas components array
		// console.log(self.designer_tool.current_page);
		self.designer_tool.pages_and_layouts[self.designer_tool.current_page][self.designer_tool.current_layout].components.push(new_image);
		new_image.render(true);
	}

	this.renderTool = function(parent){
		var self=this;
		this.parent = parent;
		
		tool_btn = $('<div class="btn btn-deault xshop-designer-image-toolbtn "><i class="glyphicon glyphicon-picture"></i><br>Image</div>').appendTo(parent.find('.xshop-designer-tool-topbar-buttonset')).data('tool',self);
		this.editor = new xShop_Image_Editor(parent.find('.xshop-designer-tool-topbar-options'));

		// CREATE NEW TEXT COMPONENT ON CANVAS
		tool_btn.click(function(event){
			if(self.designer_tool.current_selected_component != undefined && self.designer_tool.current_selected_component.options.type != 'Image')
				self.designer_tool.current_selected_component = undefined;

			options ={modal:false,
					width:800	
				};
			$.univ().frameURL('Add Images From...','index.php?page=xShop_page_designer_itemimages',options);
		});
	}


	this.render = function(is_new_image){
		var self = this;
		if(this.element == undefined){
			
			// self.options.width = self.designer_tool.px_width / 2;

			this.element = $('<div style="position:absolute" class="xshop-designer-component"><span><img></img></span></div>').appendTo(this.canvas);
			this.element.draggable({
				containment: self.designer_tool.safe_zone,
				smartguides:".xshop-designer-component",
				tolerance:5,
				stop:function(e,ui){
					var position = ui.position;
					self.options.x = self.designer_tool.screen2option(position.left);
					self.options.y = self.designer_tool.screen2option(position.top);
				}
			}).resizable({
				aspectRatio: false,
				autoHide: true,
				handles: "e, se, s",
				
				stop:function(e,ui){
					// self.options.x = ui.position.left / self.designer_tool.zoom;
					// self.options.y = ui.position.top / self.designer_tool.zoom;
					self.options.width = self.designer_tool.screen2option(ui.size.width) ;
					self.options.height = self.designer_tool.screen2option(ui.size.height) ;
					self.render();
				}
			});

			$(this.element).data('component',this);
		
			$(this.element).click(function(event) {
	            $('.ui-selected').removeClass('ui-selected');
	            $(this).addClass('ui-selected');
	            $('.xshop-options-editor').hide();
	            self.editor.element.show();
	            self.designer_tool.option_panel.show();
	            if(self.designer_tool.options.designer_mode){
		            self.designer_tool.freelancer_panel.FreeLancerComponentOptions.element.show();
		            self.designer_tool.freelancer_panel.setComponent($(this).data('component'));
	            }
	            self.designer_tool.current_selected_component = self;
	            self.editor.setImageComponent(self);
		        event.stopPropagation();
			});
		}else{
			this.element.show();
		}

		if(is_new_image == undefined){
			this.element.css('top',self.designer_tool.option2screen(self.options.y));
			this.element.css('left',self.designer_tool.option2screen(self.options.x));
			this.element.css('width',self.designer_tool.option2screen(self.options.width));
			this.element.css('height',self.designer_tool.option2screen(self.options.height));
		}

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
					width:self.options.width,
					height:self.options.height,
					max_width: self.designer_tool.safe_zone.width()/1.5,
					max_height: self.designer_tool.safe_zone.height()/1.5,
					auto_fit: is_new_image===true
				},
		})
		.done(function(ret) {
			self.element.find('img').attr('src','data:image/jpg;base64, '+ ret);
			if(is_new_image===true){
				window.setTimeout(function(){
					self.options.width = self.designer_tool.screen2option(self.element.find('img').width());
					self.options.height = self.designer_tool.screen2option(self.element.find('img').height());
					console.log(self.element.find('img').width());
				},200);
			}
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