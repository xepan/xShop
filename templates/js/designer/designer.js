Text_Component = function (params){
	this.parent=undefined;
	this.designer_tool= undefined;
	this.canvas= undefined;
	this.element = undefined;
	
	this.options = {
		x:0,
		y:0,
		width:'100%',
		height:'100%',
		font: "Times New Romans",
		font_size: '12pt',
		color:"black",
		bold: false,
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
		tool_btn = $('<div class="btn btn-deault">Text</div>').appendTo(parent);
		tool_btn.click(function(event){
			// create new TextComponent type object
			var new_text = new Text_Component();
			new_text.init(self.designer_tool,self.canvas);
			// feed default values for its parameters
			new_text.x=0;
			new_text.y=0;
			new_text.text="Your Text";
			self.designer_tool.components.push(new_text);
			// add this Object to canvas components array
			new_text.render();
		});
	}

	this.render = function(){
		if(this.element == undefined){
			this.element = $('<div  style="position:absolute"></div>').appendTo(this.canvas);
			this.element.draggable({
				containment: 'parent'
			});
		}

		$.ajax({
			url: 'index.php?page=xShop_page_designer_rendertext',
			type: 'GET',
			data: {param1: 'value1'},
		})
		.done(function(ret) {
			console.log(ret);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		

		this.element.text(this.text);
		this.element.css('left',this.x);
		this.element.css('top',this.y);
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
		tool_btn = $('<div class="btn btn-deault">Image</div>').appendTo(parent);
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

	_getZoom(){
		return this.zoom = 10;
	},
	_isDesignerMode(){
		return this.options.designer_mode;
	}

});