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

	div = $('<div></div>').appendTo(text_editor);
	this.text_input = $('<textarea class="xshop-designer-text-input" rows="1"></textarea>').appendTo(div);

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
	this.xhr = undefined;

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
		frontside:true,
		backside:false,
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
		
		tool_btn = $('<div class="btn btn-deault btn-xs"><i class="glyphicon glyphicon-text-height"></i><br>Text</div>').appendTo(parent.find('.xshop-designer-tool-topbar-buttonset'));
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
			
			console.log(self.designer_tool.current_page);

			self.designer_tool.pages_and_layouts[self.designer_tool.current_page][self.designer_tool.current_layout].components.push(new_text);
			new_text.render();
			$(new_text.element).data('component',new_text);
			
			$(new_text.element).click(function(event) {
	            $('.ui-selected').removeClass('ui-selected');
	            $(this).addClass('ui-selected');
	            self.designer_tool.option_panel.show();
	            self.designer_tool.freelancer_panel.FreeLancerComponentOptions.element.show();
	            self.editor.setTextComponent($(this).data('component'));
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