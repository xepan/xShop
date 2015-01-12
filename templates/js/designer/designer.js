// xEpan Designer jQuery Widget for extended xShop elements 
jQuery.widget("ui.xepan_xshopdesigner",{
	pages_and_layouts: {
		"Front Page": {
			"Main Layout": {
				components: [],
				background:undefined
			}
		}
	},

	current_selected_component : undefined,
	// components:[],
	current_page:'Front Page',
	current_layout: 'Main Layout',
	item_id:undefined,
	item_member_design_id:undefined,
	canvas:undefined,
	safe_zone: undefined,
	zoom: 1,
	delta_zoom: 0,
	px_width:undefined,
	option_panel: undefined,
	freelancer_panel: undefined,
	editors : [],

	options:{
		// Layout Options
		showTopBar: true,
		// ComponentsIncluded: ['Background','Text','Image','Help'], // Plugins
		IncludeJS: ['FreeLancerPanel'], // Plugins
		ComponentsIncluded: ['BackgroundImage','Text','Image','PDF','ZoomPlus','ZoomMinus','Save'], // Plugins
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
		// Load Plugin Files
		// 
		$.each(this.options.IncludeJS, function(index, js_file) {
			$.atk4.includeJS("epan-components/xShop/templates/js/designer/plugins/"+js_file+".js");
		});

		$.each(this.options.ComponentsIncluded, function(index, component) {
			$.atk4.includeJS("epan-components/xShop/templates/js/designer/plugins/"+component+".js");
		});

		// Page Layout Load js
		$.atk4.includeJS("epan-components/xShop/templates/js/designer/plugins/PageLayout.js");
		$.atk4.includeJS("epan-components/xShop/templates/js/designer/plugins/PageLayout.js");

		$.atk4(function(){
			var workplace = self.setupWorkplace();
			window.setTimeout(function(){
				self.setupCanvas(workplace);
				if(self.options.showTopBar){
					self.setupToolBar();
				}
				self.loadDesign();
				self.render();
			},200);
		});

		// this.setupComponentPanel(workplace);
	},

	loadDesign: function(){
		var self = this;
		if(self.options.design == "" || !self.options.design) return;
		saved_design = JSON.parse(self.options.design);
		$.each(saved_design,function(page_name,page_object){
			$.each(page_object,function(layout_name,layout_object){
				$.each(layout_object.components,function(key,value){
					value = JSON.parse(value);
					var temp = new window[value.type + "_Component"]();
					temp.init(self, self.canvas, self.editors[value.type]);
					temp.options = value;
					self.pages_and_layouts[page_name][layout_name]['components'][key] = temp;
				});
				
				var temp = new BackgroundImage_Component();
				temp.init(self, self.canvas);
				temp.options = JSON.parse(layout_object.background);
				self.pages_and_layouts[page_name][layout_name]['background'] = temp;
			});

		});
	},

	setupToolBar: function(){
		var self=this;
		var top_bar = $('<div class="xshop-designer-tool-topbar"></div>');
		top_bar.prependTo(this.element);
		var bottom_bar = $('<div class="xshop-designer-tool-bottombar"></div>');
		bottom_bar.appendTo($.find('.col-md-12_removed'));

		var buttons_set = $('<div class="xshop-designer-tool-topbar-buttonset pull-left"></div>').appendTo(top_bar);
		this.option_panel = $('<div class="xshop-designer-tool-topbar-options pull-right" style="display:none"></div>').appendTo(top_bar);
		
		this.remove_btn = $('<div>X</div>').appendTo(this.option_panel);

		this.remove_btn.click(function(event){
			$.each(self.pages_and_layouts[self.current_page][self.current_layout].components, function(index,cmp){
				if(cmp === self.current_selected_component){
					// console.log(self.pages_and_layouts);
					$(self.current_selected_component.element).remove();
					self.pages_and_layouts[self.current_page][self.current_layout].components.splice(index,1);
					self.current_selected_component = null;
					self.option_panel.hide();
					console.log(self.pages_and_layouts);
					// self.render();
				}
			});
		});

		if(this.options.designer_mode){
			this.freelancer_panel = new FreeLancerPanel(top_bar,self, self.canvas);
			this.freelancer_panel.init();
		}

		$.each(this.options.ComponentsIncluded, function(index, component) {
			var temp = new window[component+"_Component"]();
			temp.init(self, self.canvas);
			tool_btn = temp.renderTool(top_bar) ;
			self.editors[component] = temp.editor;
		});
		
		//Page and Layout Setup
		var temp = new window["PageLayout_Component"]();
		temp.init(self, self.canvas, bottom_bar);
		bottom_tool_btn = temp.renderTool() ;
		self.bottom_bar = temp;
		// Hide options if not clicked on any component
		$(this.canvas).click(function(event){
			$('.ui-selected').removeClass('ui-selected');
			self.option_panel.hide();
			self.current_selected_component = undefined;
			if(self.options.designer_mode){
				self.freelancer_panel.FreeLancerComponentOptions.element.hide();
			}
			$('div.guidex').css('display','none');
			$('div.guidey').css('display','none');
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
		
		this.safe_zone = $('<div class="xshop-desiner-tool-safe-zone" style="position:absolute"></div>').appendTo(this.canvas);
		this.guidex= $('<div class="guidex"></div>').appendTo($('body'));
		this.guidey= $('<div class="guidey"></div>').appendTo($('body'));
	},

	render: function(param){
		var self = this;
		console.log('sdf');

		this.canvas.css('height',this.options.height + this.options.unit); // In Given Unit
		this.canvas.height(this.canvas.height() * this._getZoom()); // get in pixel .height() and multiply by zoom 

		this.safe_zone.css('width',(this.options.width - (this.options.trim * 2)) + this.options.unit); // In given unit
		this.safe_zone.css('height',(this.options.height - (this.options.trim * 2)) + this.options.unit); // In given UNit

		this.safe_zone.width(this.safe_zone.width() * this._getZoom()); // get width in pixels and multiply by our zoom
		this.safe_zone.height(this.safe_zone.height() * this._getZoom()); // get height in pixels and multiply by our zoom

		var trim_in_px= (this.canvas.width() - this.safe_zone.width()) / 2;
		this.safe_zone.css('margin-left',trim_in_px);
		this.safe_zone.css('margin-right',trim_in_px);
		this.safe_zone.css('margin-top',trim_in_px);
		this.safe_zone.css('margin-bottom',trim_in_px);
		
		console.log('Components in '+ self.pages_and_layouts[self.current_page][self.current_layout].components.length);
		$.each(self.pages_and_layouts[self.current_page][self.current_layout].components, function(index, component) {
			component.render();
		});

		self.pages_and_layouts[self.current_page][self.current_layout].background.render();
	},

	_getZoom:function(){
		var zoom = (this.canvas.width())/ this.px_width;
		if(zoom != this.zoom){
			this.delta_zoom = this.zoom + zoom;
			this.zoom = zoom;
		}
		return this.zoom;
	},

	_isDesignerMode:function(){
		return this.options.designer_mode;
	},
	get_widget: function(){
		return this;
	},

	check: function(){
		// console.log(this.components);
	}

});



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


$.ui.plugin.add("draggable", "smartguides", {
	start: function(event, ui) {
		var i = $(this).data("uiDraggable");
		// console.log(this.data());
		o = i.options;
		i.elements = [];
		$(o.smartguides.constructor != String ? ( o.smartguides.items || ':data(uiDraggable)' ) : o.smartguides).each(function() {
			var $t = $(this); var $o = $t.offset();
			if(this != i.element[0]) i.elements.push({
				item: this,
				width: $t.outerWidth(), height: $t.outerHeight(),
				top: $o.top, left: $o.left
			});
		});
	},
	drag: function(event, ui) {
		var inst = $(this).data("uiDraggable"), o = inst.options;
		var d = o.tolerance;
        $(".guidex").css({"display":"none"});
        $(".guidey").css({"display":"none"});
            var x1 = ui.offset.left, x2 = x1 + inst.helperProportions.width,
                y1 = ui.offset.top, y2 = y1 + inst.helperProportions.height;
            for (var i = inst.elements.length - 1; i >= 0; i--){
                var l = inst.elements[i].left, r = l + inst.elements[i].width,
                    t = inst.elements[i].top, b = t + inst.elements[i].height;
                    var ls = Math.abs(l - x2) <= d;
                    var lss = Math.abs(l - x1) <= d;
                    var rs = Math.abs(r - x1) <= d;
                    var ts = Math.abs(t - y2) <= d;
                    var bs = Math.abs(b - y1) <= d;
                if(lss){
                    ui.position.left = inst._convertPositionTo("relative", { top: 0, left: l }).left - inst.margins.left;
                    $(".guidex").css({"left":l-d+4,"display":"block"});
                }
                if(ls) {
                    ui.position.left = inst._convertPositionTo("relative", { top: 0, left: l - inst.helperProportions.width }).left - inst.margins.left;
                    $(".guidex").css({"left":l-d+4,"display":"block"});
                }
                if(rs) {
                    ui.position.left = inst._convertPositionTo("relative", { top: 0, left: r }).left - inst.margins.left;
                     $(".guidex").css({"left":r-d+4,"display":"block"});
                }
                
                if(ts) {
                    ui.position.top = inst._convertPositionTo("relative", { top: t - inst.helperProportions.height, left: 0 }).top - inst.margins.top;
                    $(".guidey").css({"top":t-d+4,"display":"block"});
                }
                if(bs) {
                    ui.position.top = inst._convertPositionTo("relative", { top: b, left: 0 }).top - inst.margins.top;
                    $(".guidey").css({"top":b-d+4,"display":"block"});
                }
            };
        },

        stop: function(event, ui){
        	$(".guidex").hide();
        	$(".guidey").hide();
        }
});
