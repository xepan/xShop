getDesignerWidget = function(){
	return $('.xshop-designer-tool').xepan_xshopdesigner('get_widget');
}

xShop_Text_Editor = function(parent){
	var self = this;
	this.parent = parent;
	this.current_text_component = undefined;

	text_editor = $('<div id="xshop-designer-text-editor" style="display:block"> </div>').appendTo(this.parent);

	// add font_selection with preview
	font_selector = $('<select class="btn btn-xs"></select>').appendTo(text_editor);
	// get all fonts via ajax
	$.ajax({
		url: 'index.php?page=xShop_page_designer_fonts',
		type: 'GET',
		data: {param1: 'value1'},
	})
	.done(function(ret) {
		$(ret).appendTo(font_selector);
		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
	// font size
	font_size = $('<select class="btn btn-xs"></select>').appendTo(text_editor);

	for (var i = 7; i < 50; i++) {
		$('<option value="'+i+'">'+i+'</option>').appendTo(font_size);
	};

	$(font_size).change(function(event){
		self.current_text_component.options.font_size = $(this).val();
		$('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();
	});

	// B/I/U
	text_button_set = $('<div class="btn-group btn-group-xs" role="group" aria-label="Bold/Italic/Underline"></div>').appendTo(text_editor);
	text_bold_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-bold"></span></div>').appendTo(text_button_set);
	text_italic_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-italic"></span></div>').appendTo(text_button_set);
	text_underline_btn = $('<div class="btn btn-default"><span class="icon-underline"></span></div>').appendTo(text_button_set);
	text_strokethrough_btn = $('<div class="btn btn-default"><span class="icon-strike"></span></div>').appendTo(text_button_set);
	/*Bold Text Render*/
	$(text_bold_btn).click(function(event){
		if(!self.current_text_component.options.bold)
			self.current_text_component.options.bold = true;
		else
			self.current_text_component.options.bold = false;
		$('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();
	});

	//Underline Text
	$(text_underline_btn).click(function(event){
		self.current_text_component.options.stokethrough = false;
		
		if(!self.current_text_component.options.underline)
			self.current_text_component.options.underline = true;
		else
			self.current_text_component.options.underline = false;
		
		$('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();
	});
	
	//Stroke Through
	$(text_strokethrough_btn).click(function(event){
		self.current_text_component.options.underline = false;		
		if(!self.current_text_component.options.stokethrough)
			self.current_text_component.options.stokethrough = true;
		else
			self.current_text_component.options.stokethrough = false;

		$('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();
	});

	// L/M/R/J align
	text_button_set = $('<div class="btn-group btn-group-xs" role="group" aria-label="Text Alignment"></div>').appendTo(text_editor);
	text_align_left_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-align-left"></span></div>').appendTo(text_button_set);
	text_align_center_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-align-center"></span></div>').appendTo(text_button_set);
	text_align_right_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-align-right"></span></div>').appendTo(text_button_set);
	text_align_justify_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-align-justify"></div>').appendTo(text_button_set);
	
	//LEFT Text Alignment
	$(text_align_left_btn).click(function(){
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
	$(text_align_right_btn).click(function(){
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
	$(text_align_center_btn).click(function(){
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
	text_button_set = $('<div class="btn-group btn-group-xs" role="group" aria-label="Orderd List"></div>').appendTo(text_editor);
	text_order_list_ul_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-list"></span></div>').appendTo(text_button_set);
	text_indent_left_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-indent-left"></span></div>').appendTo(text_button_set);
	text_indent_right_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-indent-right"></div>').appendTo(text_button_set);
	text_symbol_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-plus"></div>').appendTo(text_button_set);
	
	// Angle
	text_button_set = $('<div class="btn-group btn-group-xs" role="group" aria-label="Text Alignment"></div>').appendTo(text_editor);
	text_rotate_anticlockwise = $('<div class="btn btn-default btn-xs"><span class="glyphicon glyphicon-repeat" style="-moz-transform: scaleX(-1);-o-transform: scaleX(-1);-webkit-transform: scaleX(-1);transform: scaleX(-1);filter: FlipH;-ms-filter: "FlipH";"></span></div>').appendTo(text_button_set);
	text_rotate_clockwise = $('<div class="btn btn-default btn-xs"><span class="glyphicon glyphicon-repeat"></span></div>').appendTo(text_button_set);

	//Rotation AntiClockWise Difference with -45 deg
	$(text_rotate_anticlockwise).click(function(event){
		var angle_rotate = self.current_text_component.options.rotation_angle;
		if(angle_rotate==0)
			angle_rotate = 360;
		self.current_text_component.options.rotation_angle = angle_rotate-45;
		$('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();

	});

	//Rotation ClockWise Difference with +45 deg
	$(text_rotate_clockwise).click(function(event){
		var angle_rotate = self.current_text_component.options.rotation_angle;
		if(angle_rotate==360)
			angle_rotate = 0;
		self.current_text_component.options.rotation_angle = angle_rotate+45;
		$('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();		
	});

	// Color
	text_color_picker = $('<input id="xshop-colorpicker-full" type="text" style="display:block">').appendTo(text_editor);
	$(text_color_picker).colorpicker({
		parts:          'full',
        alpha:          false,
        showOn:         'both',
        buttonColorize: true,
        showNoneButton: true,
        ok: function(event, color){
        	self.current_text_component.options.color_cmyk = parseInt((color.cmyk.c)*100)+','+parseInt((color.cmyk.m)*100)+','+parseInt((color.cmyk.y)*100)+','+parseInt((color.cmyk.k)*100);
        	self.current_text_component.options.color_formatted = '#'+color.formatted;
        	self.current_text_component.render();
        	$('.xshop-designer-tool').xepan_xshopdesigner('check');
        }
	});
	
	this.setTextComponent = function(component){
		this.current_text_component  = component;
		$(font_size).val(component.options.font_size);
		$(font_selector).val(component.options.font);
		$(text_color_picker).val(component.options.color);
		$(text_color_picker).colorpicker('setColor',component.options.color_formatted);
	}

	$(font_selector).change(function(event){
		self.current_text_component.options.font = $(this).val();
		// $('.xshop-designer-tool').xepan_xshopdesigner('check');
		self.current_text_component.render();
	});

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
		
		// System properties
		type: 'Text'
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
		tool_btn = $('<div class="btn btn-deault">Text</div>').appendTo(parent.find('.xshop-designer-tool-topbar-buttonset'));
		tool_btn.click(function(event){
			// create new TextComponent type object
			var new_text = new Text_Component();
			new_text.init(self.designer_tool,self.canvas);
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
	            self.editor.setTextComponent($(this).data('component'));
	            $('.xshop-designer-tool-topbar-options').show();
		        event.stopPropagation();
			});
		});

		this.editor = new xShop_Text_Editor(parent.find('.xshop-designer-tool-topbar-options'));

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
			}).resizable({
				minHeight: function(){
					return self.element.find('span').css('height');
				},
				minWidth: self.element.find('span').css('width')
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
					alignment_center:self.options.alignment_center
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


// xEpan Designer jQuery Widget for extended xShop elements 
jQuery.widget("ui.xepan_xshopdesigner",{
	components:[],
	canvas:undefined,
	safe_zone: undefined,
	zoom: 1,
	options:{
		// Layout Options
		showTopBar: true,
		// ComponentsIncluded: ['Background','Text','Image','Help'], // Plugins
		ComponentsIncluded: ['Text','Image'], // Plugins
		design: [],
		designer_mode: false,
		width: undefined,
		height: undefined
	},
	_create: function(){
		this.setupLayout();
	},
	setupLayout: function(){
		var workplace = this.setupWorkplace()
		this.setupCanvas(workplace);
		if(this.options.showTopBar){
			this.setupToolBar();
		}
		// this.setupComponentPanel(workplace);
	},
	setupToolBar: function(){
		var self=this;
		var top_bar = $('<div class="xshop-designer-tool-topbar"></div>');
		top_bar.prependTo(this.element);

		var buttons_set = $('<div class="xshop-designer-tool-topbar-buttonset pull-left"></div>').appendTo(top_bar);
		var tool_bar_options = $('<div class="xshop-designer-tool-topbar-options pull-right" style="display:none"></div>').appendTo(top_bar);
		
		$.each(this.options.ComponentsIncluded, function(index, component) {
			var temp = new window[component+"_Component"]();
			temp.init(self, self.canvas);
			tool_btn = temp.renderTool(top_bar) ;
		});
		
		// Hide options if not clicked on any component
		$(this.canvas).click(function(event){
			$('.ui-selected').removeClass('ui-selected');
			tool_bar_options.hide();
			event.stopPropagation();
		});
	},

	setupWorkplace: function(){
		return $('<div class="xshop-designer-tool-workplace row"></div>').appendTo(this.element);
	},

	setupComponentPanel: function(workplace){
		this.component_panel = $('<div id="xshop-designer-component-panel" class=" col-md-3">Nothing Selecetd</div>').appendTo(workplace);
	},

	setupCanvas: function(workplace){
		var outer_column = $('<div class="col-md-12"></div>').appendTo(workplace);
		this.canvas = $('<div class="xshop-desiner-tool-canvas atk-move-center" style="position:relative"></div>').appendTo(outer_column);
		this.canvas.css('width',this.options.width * this._getZoom());
		this.canvas.css('height',this.options.height * this._getZoom());

		this.safe_zone = $('<div class="xshop-desiner-tool-safe-zone" style="position:absolute"></div>').appendTo(this.canvas);
		this.safe_zone.css('margin',this.options.trim * this._getZoom());
		this.safe_zone.css('height',(this.options.height * this._getZoom()) - (this.options.trim * this._getZoom()*2));
		this.safe_zone.css('width',(this.options.width * this._getZoom()) - (this.options.trim * this._getZoom()*2));


	},

	render: function(param){
		console.log('Called by ' + param.msg);
		$(this.TextPanel).TextPanel('test');
	},

	_getZoom:function(){
		return this.zoom = 10;
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