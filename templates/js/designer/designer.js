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
	text_bold_btn = $('<div class="btn btn-default"><span class="fa fa-bold"></span></div>').appendTo(text_button_set);
	text_italic_btn = $('<div class="btn btn-default"><span class="fa fa-italic"></span></div>').appendTo(text_button_set);
	text_underline_btn = $('<div class="btn btn-default"><span class="fa fa-underline"></span></div>').appendTo(text_button_set);
	text_strikethrough_btn = $('<div class="btn btn-default"><span class="fa fa-strikethrough"></span></div>').appendTo(text_button_set);

	// L/M/R/J align
	text_button_set = $('<div class="btn-group btn-group-xs" role="group" aria-label="Text Alignment"></div>').appendTo(text_editor);
	text_align_left_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-align-left"></span></div>').appendTo(text_button_set);
	text_align_center_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-align-center"></span></div>').appendTo(text_button_set);
	text_align_right_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-align-right"></span></div>').appendTo(text_button_set);
	text_align_justify_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-align-justify"></div>').appendTo(text_button_set);

	//Ordered List
	text_button_set = $('<div class="btn-group btn-group-xs" role="group" aria-label="Orderd List"></div>').appendTo(text_editor);
	text_order_list_ul_btn = $('<div class="btn btn-default"><span class="fa fa-list-ul"></span></div>').appendTo(text_button_set);
	text_order_list_ol_btn = $('<div class="btn btn-default"><span class="fa fa-list-ol"></span></div>').appendTo(text_button_set);
	text_indent_left_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-indent-left"></span></div>').appendTo(text_button_set);
	text_indent_right_btn = $('<div class="btn btn-default"><span class="glyphicon glyphicon-indent-right"></div>').appendTo(text_button_set);
	
	// Angle
	text_button_set = $('<div class="btn-group btn-group-xs" role="group" aria-label="Text Alignment"></div>').appendTo(text_editor);
	text_rotate_left = $('<div class="btn btn-default btn-xs"><span class="fa fa-undo"></span></div>').appendTo(text_button_set);
	text_rotate_right = $('<div class="btn btn-default btn-xs"><span class="glyphicon glyphicon-repeat"></span></div>').appendTo(text_button_set);

	// Color
	text_color_picker = $('<input class="btn btn-default"/>').appendTo(text_editor).univ().xEpanColorPicker();

	this.setTextComponent = function(component){
		this.current_text_component  = component;
		$(font_size).val(component.options.font_size);
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
		font: "OpenSans",
		font_size: '12',
		color:"red",
		bold: true,
		italic:false,
		underline:false,
		rotation_angle:0,
		locked: false,

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
		        if ($(this).hasClass('ui-selected')) {
		            $(this).removeClass('ui-selected');
		        } else {
		            $(this).addClass('ui-selected');
		            self.editor.setTextComponent($(this).data('component'));
		        }
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
			}).resizable();
		}

		$.ajax({
			url: 'index.php?page=xShop_page_designer_rendertext',
			type: 'GET',
			data: {default_value: self.options['default_value'],
					color: self.options['color'],
					font: self.options['font'],
					font_size: self.options['font_size'],
					bold: self.options['bold']
					},
		})
		.done(function(ret) {
			$(ret).appendTo(self.element.html(''));
			// console.log(ret);
		})
		.fail(function() {
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
		var tool_bar_options = $('<div class="xshop-designer-tool-topbar-options pull-right"></div>').appendTo(top_bar);
		
		$.each(this.options.ComponentsIncluded, function(index, component) {
			var temp = new window[component+"_Component"]();
			temp.init(self, self.canvas);
			tool_btn = temp.renderTool(top_bar) ;
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

	check: function(){
		console.log(this.components);
	}

});