(function($) {
    /**
     * KeyUp with delay event setup
     * 
     * @link http://stackoverflow.com/questions/1909441/jquery-keyup-delay#answer-12581187
     * @param function callback
     * @param int ms
     */
    $.fn.delayKeyup = function(callback, ms){
            $(this).keyup(function( event ){
                var srcEl = event.currentTarget;
                if( srcEl.delayTimer )
                    clearTimeout (srcEl.delayTimer );
                srcEl.delayTimer = setTimeout(function(){ callback( $(srcEl) ); }, ms);
            });

        return $(this);
    };
})(jQuery);

getDesignerWidget = function(){
	return $('.xshop-designer-tool').xepan_xshopdesigner('get_widget');
}

FreeLancerPanel = function(parent, designer, canvas){
	this.parent = parent;
	this.designer_tool = designer;
	this.canvas = canvas;
	this.current_component = undefined;
	this.element =undefined;

	this.init =  function(){
		var self =this;
		ft_btn_set = $('<div class="btn-group" style="display:none;"></div>');
		$('<a title="" data-toggle="dropdown" class="btn dropdown-toggle" data-original-title="Font Size">FT&nbsp;<b class="caret"></b></a>').appendTo(ft_btn_set);
        ft_btn_list = $('<ul class="dropdown-menu"></ul>').appendTo(ft_btn_set);

        this.btn_movable = $('<li class=""><span class="glyphicon glyphicon-ok" style="display:none"></span> Movable</li>').appendTo(ft_btn_list);
        this.btn_colorable = $('<li><span class="glyphicon glyphicon-ok" style="display:none"></span> Colorable</li>').appendTo(ft_btn_list);
        this.btn_editable = $('<li><span class="glyphicon glyphicon-ok" style="display:none"></span> Editable</li>').appendTo(ft_btn_list);
        this.btn_zindex = $('<li><a><font size="3">Z-index</font></a></li>').appendTo(ft_btn_list);
        this.btn_resizable = $('<li><a><font size="3">Resizable</font></a></li>').appendTo(ft_btn_list);
        this.btn_autofit = $('<li><a><font size="3">Autofit</font></a></li>').appendTo(ft_btn_list);
        this.btn_multiline = $('<li><a><font size="3">Multiline Text</font></a></li>').appendTo(ft_btn_list);
			
		this.element = $(ft_btn_set).appendTo(this.parent);

		// add Flyout
		// add movable button
		// 		its onclick event
		// 	add dsfdf
		
		this.btn_movable.click(function(event){
			self.current_component.options.movable = !self.current_component.options.movable;
			$(this).find('span').toggle();
			if(self.current_component.options.movable){
				self.current_component.element.draggable('enable');
			}else{
				self.current_component.element.draggable('disable');
			}
			
		});

		this.btn_colorable.click(function(event){
			self.current_component.options.colorable = !self.current_component.options.colorable;
			if(self.current_component.options.colorable){
				self.current_component.editor.text_color_picker.next('button').show();
			}else{
				self.current_component.editor.text_color_picker.next('button').hide();
			}
			$(this).find('span').toggle();
		});

		this.btn_editable.click(function(event){
			self.current_component.options.editable = !self.current_component.options.editable;
			if(self.current_component.options.editable){
				self.current_component.editor.text_input.show();
			}else{
				self.current_component.editor.text_input.hide();
			}
			$(this).find('span').toggle();
		});



	}

	this.setComponent = function(component){
		this.current_component = component;
		console.log(this.current_component);

		if(this.current_component.options.movable){
			$(this.btn_movable).find('span').show();
		}
		else{
			$(this.btn_movable).find('span').hide();
		}

		if(this.current_component.options.colorable){
			$(this.btn_colorable).find('span').show();
		}
		else{
			$(this.btn_colorable).find('span').hide();
		}

		if(this.current_component.options.editable){
			$(this.btn_editable).find('span').show();
		}
		else{
			$(this.btn_editable).find('span').hide();
		}
	}

}

xShop_Text_Editor = function(parent){
	var self = this;
	this.parent = parent;
	this.current_text_component = undefined;

	text_editor = $('<div id="xshop-designer-text-editor" style="display:block"> </div>').appendTo(this.parent);

	// add font_selection with preview
	this.font_selector = $('<select class="btn btn-xs"></select>').appendTo(text_editor);
	// get all fonts via ajax
	$.ajax({
		url: 'index.php?page=xShop_page_designer_fonts',
		type: 'GET',
		data: {param1: 'value1'},
	})
	.done(function(ret) {
		$(ret).appendTo(self.font_selector);
		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

	$(this.font_selector).change(function(event){
		self.current_text_component.options.font = $(this).val();
		// $('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();
	});
	
	// font size
	this.font_size = $('<select class="btn btn-xs"></select>').appendTo(text_editor);

	for (var i = 7; i < 50; i++) {
		$('<option value="'+i+'">'+i+'</option>').appendTo(this.font_size);
	};

	$(this.font_size).change(function(event){
		self.current_text_component.options.font_size = $(this).val();
		$('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();
	});

	// B/I/U
	this.text_button_set = $('<div class="btn-group btn-group-xs" role="group" aria-label="Bold/Italic/Underline"></div>').appendTo(text_editor);
	this.text_bold_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-bold"></span></div>').appendTo(this.text_button_set);
	this.text_italic_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-italic"></span></div>').appendTo(this.text_button_set);
	this.text_underline_btn = $('<div class="btn btn-default"><span class="icon-underline"></span></div>').appendTo(this.text_button_set);
	this.text_strokethrough_btn = $('<div class="btn btn-default"><span class="icon-strike"></span></div>').appendTo(this.text_button_set);
	/*Bold Text Render*/
	$(this.text_bold_btn).click(function(event){
		if(!self.current_text_component.options.bold)
			self.current_text_component.options.bold = true;
		else
			self.current_text_component.options.bold = false;
		$('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();
	});

	//Underline Text
	$(this.text_underline_btn).click(function(event){
		self.current_text_component.options.stokethrough = false;
		
		if(!self.current_text_component.options.underline)
			self.current_text_component.options.underline = true;
		else
			self.current_text_component.options.underline = false;
		
		$('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();
	});
	
	//Stroke Through
	$(this.text_strokethrough_btn).click(function(event){
		self.current_text_component.options.underline = false;		
		if(!self.current_text_component.options.stokethrough)
			self.current_text_component.options.stokethrough = true;
		else
			self.current_text_component.options.stokethrough = false;

		$('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();
	});

	// L/M/R/J align
	this.text_button_set = $('<div class="btn-group btn-group-xs" role="group" aria-label="Text Alignment"></div>').appendTo(text_editor);
	this.text_align_center_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-align-center"></span></div>').appendTo(this.text_button_set);
	this.text_align_right_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-align-right"></span></div>').appendTo(this.text_button_set);
	this.text_align_justify_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-align-justify"></div>').appendTo(this.text_button_set);
	
	//LEFT Text Alignment
	$(this.text_align_left_btn).click(function(){
		if(!self.current_text_component.options.alignment_left)
			self.current_text_component.options.alignment_left = true;
		else
			self.current_text_component.options.alignment_left = false;

		self.current_text_component.options.alignment_center = false;
		self.current_text_component.options.alignment_right = false;
		$('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();
	});
	
	//RIGHT Text Alignment
	$(this.text_align_right_btn).click(function(){
		if(!self.current_text_component.options.alignment_right)
			self.current_text_component.options.alignment_right = true;
		else
			self.current_text_component.options.alignment_right = false;

		self.current_text_component.options.alignment_left = false;
		self.current_text_component.options.alignment_center = false;
		$('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();
	});

	//CENTER Text Alignment
	$(this.text_align_center_btn).click(function(){
		if(!self.current_text_component.options.alignment_center)
			self.current_text_component.options.alignment_center = true;
		else
			self.current_text_component.options.alignment_center = false;

		self.current_text_component.options.alignment_left = false;
		self.current_text_component.options.alignment_right = false;
		$('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();
	});

	//Ordered List
	this.text_button_set = $('<div class="btn-group btn-group-xs" role="group" aria-label="Orderd List"></div>').appendTo(text_editor);
	this.text_order_list_ul_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-list"></span></div>').appendTo(this.text_button_set);
	this.text_indent_left_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-indent-left"></span></div>').appendTo(this.text_button_set);
	this.text_indent_right_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-indent-right"></div>').appendTo(this.text_button_set);
	this.text_symbol_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-plus"></div>').appendTo(this.text_button_set);
	
	// Angle
	this.text_button_set = $('<div class="btn-group btn-group-xs" role="group" aria-label="Text Alignment"></div>').appendTo(text_editor);
	this.text_rotate_anticlockwise = $('<div class="btn btn-default btn-xs"><span class="glyphicon glyphicon-repeat" style="-moz-transform: scaleX(-1);-o-transform: scaleX(-1);-webkit-transform: scaleX(-1);transform: scaleX(-1);filter: FlipH;-ms-filter: "FlipH";"></span></div>').appendTo(this.text_button_set);
	this.text_rotate_clockwise = $('<div class="btn btn-default btn-xs"><span class="glyphicon glyphicon-repeat"></span></div>').appendTo(this.text_button_set);

	//Rotation AntiClockWise Difference with -45 deg
	$(this.text_rotate_anticlockwise).click(function(event){
		var angle_rotate = self.current_text_component.options.rotation_angle;
		if(angle_rotate==0)
			angle_rotate = 360;
		self.current_text_component.options.rotation_angle = angle_rotate-45;
		$('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();

	});

	//Rotation ClockWise Difference with +45 deg
	$(this.text_rotate_clockwise).click(function(event){
		var angle_rotate = self.current_text_component.options.rotation_angle;
		if(angle_rotate==360)
			angle_rotate = 0;
		self.current_text_component.options.rotation_angle = angle_rotate+45;
		$('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();		
	});

	// Color
	this.text_color_picker = $('<input id="xshop-colorpicker-full" type="text" style="display:block">').appendTo(text_editor);
	$(this.text_color_picker).colorpicker({
		parts:          'full',
        alpha:          false,
        showOn:         'both',
        buttonColorize: true,
        showNoneButton: true,
        ok: function(event, color){
        	console.log(color);
        	self.current_text_component.options.color_cmyk = parseInt((color.cmyk.c)*100)+','+parseInt((color.cmyk.m)*100)+','+parseInt((color.cmyk.y)*100)+','+parseInt((color.cmyk.k)*100);
        	self.current_text_component.options.color_formatted = '#'+color.formatted;
        	self.current_text_component.render();
        	$('.xshop-designer-tool').xepan_xshopdesigner('check');
        }
	});

	this.text_input = $('<textarea></textarea>').appendTo(text_editor);
	$(this.text_input).delayKeyup(function(el){
		self.current_text_component.options.text = $(el).val();
		if(self.current_text_component.designer_tool.options.designer_mode){
			self.current_text_component.options.default_value= $(el).val();
		}
		self.current_text_component.render();
	},500);
	
	this.setTextComponent = function(component){
		this.current_text_component  = component;
		$(this.font_size).val(component.options.font_size);
		$(this.font_selector).val(component.options.font);
		$(this.text_color_picker).val(component.options.color_formatted);
		$(this.text_color_picker).colorpicker('setColor',component.options.color_formatted);
		
		if(!this.current_text_component.options.colorable)
			this.text_color_picker.next('button').hide();

		this.text_input.val(this.current_text_component.options.text);
		if(!this.current_text_component.options.editable)
			this.text_input.hide();

	}

}

Text_Component = function (params){
	this.parent=undefined;
	this.designer_tool= undefined;
	this.canvas= undefined;
	this.element = undefined;
	this.editor = undefined;

	this.options = {
		x:0,
		y:0,
		width:'100%',
		height:'100%',
		text:'Enter Text',
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
		default_value:'Enter Text',
		z_index:0,
		resizable: true,
		auto_fit: false,
		multiline: false,
		// System properties
		type: 'Text'
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
		tool_btn = $('<div class="btn btn-deault">Text</div>').appendTo(parent.find('.xshop-designer-tool-topbar-buttonset'));
		this.editor = new xShop_Text_Editor(parent.find('.xshop-designer-tool-topbar-options'));

		// CREATE NEW TEXT COMPONENT ON CANVAS
		tool_btn.click(function(event){
			// create new TextComponent type object
			var new_text = new Text_Component();
			new_text.init(self.designer_tool,self.canvas, self.editor);
			// feed default values for its parameters
			new_text.x=0;
			new_text.y=0;
			new_text.text="Your Text";
			// add this Object to canvas components array
			self.designer_tool.components.push(new_text);
			new_text.render();
			$(new_text.element).data('component',new_text);
			
			$(new_text.element).click(function(event) {
	            $('.ui-selected').removeClass('ui-selected');
	            $(this).addClass('ui-selected');
	            self.designer_tool.option_panel.show();
	            self.designer_tool.freelancer_panel.element.show();
	            self.editor.setTextComponent($(this).data('component'));
	            self.designer_tool.freelancer_panel.setComponent($(this).data('component'));
		        event.stopPropagation();
			});
		});


	}

	this.render = function(){
		var self = this;
		if(this.element == undefined){
			this.element = $('<div style="position:absolute"><span></span></div>').appendTo(this.canvas);
			this.element.draggable({
				containment: 'parent',
				stop:function(e,ui){
					var position = ui.position;
					self.options.x = position.left;
					self.options.y = position.top;
				}
			});
		}
		$.ajax({
			url: 'index.php?page=xShop_page_designer_rendertext',
			type: 'GET',
			data: {default_value: self.options.default_value,
					color: self.options.color_formatted,
					font: self.options.font,
					font_size: self.options.font_size,
					bold: self.options.bold,
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
			// console.log(ret);
		})
		.fail(function(ret) {
			evel(ret);
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
		tool_btn = $('<div class="btn btn-deault">PDF</div>').appendTo(parent.find('.xshop-designer-tool-topbar-buttonset'));

		// CREATE NEW TEXT COMPONENT ON CANVAS
		tool_btn.click(function(event){
			// create new TextComponent type object
			
		});

	}

	this.render = function(){
		var self = this;
	}
}

ZoomPlus_Component = function (params){
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
		tool_btn = $('<div class="btn btn-deault">ZOOM +</div>').appendTo(parent.find('.xshop-designer-tool-topbar-buttonset'));

		// CREATE NEW TEXT COMPONENT ON CANVAS
		tool_btn.click(function(event){
			// create new TextComponent type object
			
		});

	}

	this.render = function(){
		var self = this;
	}
}

ZoomMinus_Component = function (params){
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
		tool_btn = $('<div class="btn btn-deault">ZOOM -</div>').appendTo(parent.find('.xshop-designer-tool-topbar-buttonset'));

		// CREATE NEW TEXT COMPONENT ON CANVAS
		tool_btn.click(function(event){
			// create new TextComponent type object
			
		});

	}

	this.render = function(){
		var self = this;
	}
}

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
		this.parent = parent;
		this.component_option_array = [];
		tool_btn = $('<div class="btn btn-deault">Save</div>').appendTo(parent.find('.xshop-designer-tool-topbar-buttonset'));
		tool_btn.click(function(event){
			$(self.designer_tool.components).each(function(index,component){
				$(self.designer_tool.components[index].options).each(function(index,options){
					self.component_option_array.push(JSON.stringify(options));
				});
			});
			self.component_option_array = [];

			//Save all option 
			// $.ajax({
			// 		url: 'index.php?page=xShop_page_designer_save',
			// 		type: 'GET',
			// 		data: {param1: 'value1'},
			// 	})
			// 	.done(function(ret) {
			// 		console.log("success");
			// 	})
			// 	.fail(function() {
			// 		console.log("error");
			// 	})
			// 	.always(function() {
			// 		console.log("complete");
			// 	});	

		});
	}
}


// xEpan Designer jQuery Widget for extended xShop elements 
jQuery.widget("ui.xepan_xshopdesigner",{
	components:[],
	canvas:undefined,
	safe_zone: undefined,
	zoom: 1,
	px_width:undefined,
	option_panel: undefined,
	freelancer_panel: undefined,

	options:{
		// Layout Options
		showTopBar: true,
		// ComponentsIncluded: ['Background','Text','Image','Help'], // Plugins
		ComponentsIncluded: ['Text','Image','PDF','ZoomPlus','ZoomMinus','Save'], // Plugins
		design: [],
		designer_mode: false,
		width: undefined,
		height: undefined
	},
	_create: function(){
		this.setupLayout();
	},
	setupLayout: function(){
		var self = this;
		var workplace = this.setupWorkplace();
		window.setTimeout(function(){
			self.setupCanvas(workplace);
			if(self.options.showTopBar){
				self.setupToolBar();
			}
		},200);
		// this.setupComponentPanel(workplace);
	},
	setupToolBar: function(){
		var self=this;
		var top_bar = $('<div class="xshop-designer-tool-topbar"></div>');
		top_bar.prependTo(this.element);

		var buttons_set = $('<div class="xshop-designer-tool-topbar-buttonset pull-left"></div>').appendTo(top_bar);
		this.option_panel = $('<div class="xshop-designer-tool-topbar-options pull-right" style="display:none"></div>').appendTo(top_bar);
		
		if(this.options.designer_mode){
			this.freelancer_panel = new FreeLancerPanel(top_bar,self, self.canvas);
			this.freelancer_panel.init();
		}

		$.each(this.options.ComponentsIncluded, function(index, component) {
			var temp = new window[component+"_Component"]();
			temp.init(self, self.canvas);
			tool_btn = temp.renderTool(top_bar) ;
		});
		
		// Hide options if not clicked on any component
		$(this.canvas).click(function(event){
			$('.ui-selected').removeClass('ui-selected');
			self.option_panel.hide();
			self.freelancer_panel.element.hide();
			event.stopPropagation();
		});
	},

	setupWorkplace: function(){
		return $('<div class="xshop-designer-tool-workplace"></div>').appendTo(this.element);
	},

	setupComponentPanel: function(workplace){
		this.component_panel = $('<div id="xshop-designer-component-panel" class=" col-md-3">Nothing Selecetd</div>').appendTo(workplace);
	},

	setupCanvas: function(workplace){
		var self = this;
		var outer_column = $('<div class="col-md-12_removed"></div>').appendTo(workplace);
		this.canvas = $('<div class="xshop-desiner-tool-canvas atk-move-center" style="position:relative"></div>').appendTo(outer_column);
		
		this.canvas.css('width',this.options.width + this.options.unit); // In given Unit
		this.px_width = this.canvas.width(); // Save in pixel for actual should be width

		if(this.canvas.width() > workplace.width()){
			this.canvas.css('width', workplace.width() - 20 + 'px');
		}

		if(this.canvas.width() < (workplace.width()/2)){
			this.canvas.width((workplace.width()/2));
		}

		this.canvas.css('height',this.options.height + this.options.unit); // In Given Unit
		// console.log(this.canvas.height());
		this.canvas.height(this.canvas.height() * this._getZoom()); // get in pixel .height() and multiply by zoom 

		this.safe_zone = $('<div class="xshop-desiner-tool-safe-zone" style="position:absolute"></div>').appendTo(this.canvas);
		this.safe_zone.css('width',(this.options.width - (this.options.trim * 2)) + this.options.unit); // In given unit
		this.safe_zone.css('height',(this.options.height - (this.options.trim * 2)) + this.options.unit); // In given UNit

		this.safe_zone.width(this.safe_zone.width() * this._getZoom()); // get width in pixels and multiply by our zoom
		this.safe_zone.height(this.safe_zone.height() * this._getZoom()); // get height in pixels and multiply by our zoom

		var trim_in_px= (this.canvas.width() - this.safe_zone.width()) / 2;
		this.safe_zone.css('margin-left',trim_in_px);
		this.safe_zone.css('margin-right',trim_in_px);
		this.safe_zone.css('margin-top',trim_in_px);
		this.safe_zone.css('margin-bottom',trim_in_px);

	},

	render: function(param){
		console.log('Called by ' + param.msg);
		$(this.TextPanel).TextPanel('test');
	},

	_getZoom:function(){
		this.zoom = (this.canvas.width())/ this.px_width;
		return this.zoom;
	},

	_isDesignerMode:function(){
		return this.options.designer_mode;
	},
	get_widget: function(){
		return this;
	},

	check: function(){
		console.log(this.components);
	}

});