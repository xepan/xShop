// xEpan Designer jQuery Widget for extended xShop elements 
jQuery.widget("ui.xepan_xshopdesigner",{
	pages_and_layouts: {
		"Front Page": {
			"Main Layout": {
				components: []
			}
		}
	},
	// components:[],
	current_page:'front_page',
	current_layout: 'main_layout',
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
		IncludeJS: ['FreeLancerPanel'], // Plugins
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
		// Load Plugin Files
		// 
		$.each(this.options.IncludeJS, function(index, js_file) {
			$.atk4.includeJS("epan-components/xShop/templates/js/designer/plugins/"+js_file+".js");
		});

		$.each(this.options.ComponentsIncluded, function(index, component) {
			$.atk4.includeJS("epan-components/xShop/templates/js/designer/plugins/"+component+".js");
		});

		$.atk4(function(){
			var workplace = self.setupWorkplace();
			window.setTimeout(function(){
				self.setupCanvas(workplace);
				if(self.options.showTopBar){
					self.setupToolBar();
				}

				self.render();

			},200);
		});

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
			self.freelancer_panel.FreeLancerComponentOptions.element.hide();
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
	},

	render: function(param){
		var self = this;
		this.canvas.css('height',this.options.height + this.options.unit); // In Given Unit
		// console.log(this.canvas.height());
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
		

		$.each(self.pages_and_layouts[self.current_page][self.current_layout].components, function(index, component) {
			component.render();
		});
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
