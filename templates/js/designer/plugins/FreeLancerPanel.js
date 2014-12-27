FreeLancerPageLayoutManager = function(parent,designer, canvas){
	
}

FreeLancerComponentOptions = function(parent, designer, canvas){
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


FreeLancerPanel = function(parent, designer, canvas){
	this.parent = parent;
	this.designer_tool = designer;
	this.canvas = canvas;
	this.current_component = undefined;
	this.element = undefined;
	this.FreeLancerComponentOptions=undefined;

	this.init =  function(){
		this.element = $('<div></div>').appendTo(this.parent);
		this.FreeLancerComponentOptions = new FreeLancerComponentOptions(this.element,this.designer_tool, this.canvas);
		this.FreeLancerComponentOptions.init();
	}

	this.setComponent = function(component){
		this.FreeLancerComponentOptions.setComponent(component);
	}
	// create page_layout_manager
	// create tools option manager

}