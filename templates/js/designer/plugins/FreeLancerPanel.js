PageBlock = function(parent,designer,canvas, manager){
	this.parent = parent;
	this.designer_tool = designer;
	this.canvas = canvas;
	this.manager = manager;
	this.current_component = undefined;
	this.element =undefined;

	this.add_div=undefined;
	this.input_box=undefined;
	this.add_btn=undefined;

	this.page_list_div = undefined;

	this.init = function(pages_data_array){
		var self = this;
		// make a nice div
		this.element = $('<div> I M PAGE DIV</div>').appendTo(this.parent);
		// add input box and add button
		this.add_div = $("<div></div>").appendTo(this.element);
		this.input_box =$('<input type="text" class="pull-left"/>').appendTo(this.add_div);
		this.add_btn = $('<button class="pull-right">Add</button>').appendTo(this.add_div);

		this.add_btn.click(function(event){
			self.addPage(self.input_box.val());
		});

		// make a list of current pages with remove buton
		this.page_list_div = $('<div></div>').appendTo(this.element);

		$.each(pages_data_array,function(index,page){
			self.addPage(page);
		});


		// on click of any page update layoutblock
	}

	this.addPage = function(page_name){
		var self = this;
		// validate page_name
		// existing page name
		console.log('adding ' + page_name);
		this.input_box.addClass('atk-effect-danger has-error atk-form-error');
		this.input_box.css('border-color','red');
		// add page to pagelistdiv and add to designertool pagesnadlayout object
		this.designer_tool.pages_and_layouts[page_name] =  new Object();
		this.designer_tool.pages_and_layouts[page_name]['Main Layout'] =  new Object();
		this.designer_tool.pages_and_layouts[page_name]['Main Layout'].components = [];
		// add default layout to this page as well

		page_row = $('<div class="page_row"></div>').appendTo(this.page_list_div);
		div = $('<div></div>').appendTo(page_row).html(page_name);
		rm_btn = $('<div> X </div>').appendTo(page_row).data('page_name',page_name);
		div.click(function(event){
			self.manager.layoutblock.setPage($(this).html());
		});

		rm_btn.click(function(event){
			self.removePage($(this).data('page_name'));
			$(this).closest(".page_row").remove();
		});

	}

	this.removePage = function(page_name){
		// TODO Validation :: Do not remove if 'Fron Page'
 		// if(confirm('Are you sure?'))
		this.designer_tool.pages_and_layouts[page_name] = null;	
		delete this.designer_tool.pages_and_layouts[page_name];
		console.log(this.designer_tool.pages_and_layouts);
	}

}

LayoutBlock = function(parent,designer,canvas, manager){
	this.parent = parent;
	this.designer_tool = designer;
	this.canvas = canvas;
	this.manager = manager;
	this.current_component = undefined;
	this.element =undefined;

	this.current_page= undefined;
	this.layout_list_div = undefined;

	this.init = function(){
		var self = this;
		// make a nice div
		this.element = $('<div> I M LAYOUT DIV</div>').appendTo(this.parent);
		// add input box and add button
		this.add_div = $("<div></div>").appendTo(this.element);
		this.input_box =$('<input type="text" class="pull-left"/>').appendTo(this.add_div);
		this.add_btn = $('<button class="pull-right">Add</button>').appendTo(this.add_div);

		this.add_btn.click(function(event){
			self.addLayout(self.input_box.val());
		});

		// make a list of current pages with remove buton
		this.layout_list_div = $('<div></div>').appendTo(this.element);

	}

	this.setPage = function(page_name){
		this.current_page = page_name;
		// create layout dis with remove button and its event
		console.log('changed page to ' + page_name);
	}

	this.addLayout = function(layout_name){
		
	}

	this.removeLayout = function(layout_name){

	}

}

FreeLancerPageLayoutManager = function(parent,designer, canvas){
	this.parent = parent;
	this.designer_tool = designer;
	this.canvas = canvas;
	this.current_component = undefined;
	this.element =undefined;
	this.page=undefined;
	this.pageblock=undefined;
	this.layoutblock = undefined;


	this.init = function(){
		var self = this;
		this.element = $('<div class="btn btn-default">P&L</div>').appendTo(this.parent);
		this.page = $('<div></div>').appendTo(this.element);

		this.pageblock = new PageBlock(this.page,this.designer_tool,this.canvas,this);
		this.pageblock.init(['page1','page2','page3']);

		this.layoutblock = new LayoutBlock(this.page,this.designer_tool,this.canvas,this);
		this.layoutblock.init();
		this.layoutblock.setPage('page1');


		this.page.dialog({autoOpen: false, modal: true});
		this.element.click(function(event){
			// Update recent pages and layouts 
			self.page.dialog('open');
		});
	}
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
	this.FreeLancerPageLayoutManager=undefined;

	this.init =  function(){
		this.element = $('<div></div>').appendTo(this.parent);
		this.FreeLancerPageLayoutManager = new FreeLancerPageLayoutManager(this.element,this.designer_tool, this.canvas);
		this.FreeLancerPageLayoutManager.init();
		this.FreeLancerComponentOptions = new FreeLancerComponentOptions(this.element,this.designer_tool, this.canvas);
		this.FreeLancerComponentOptions.init();
	}

	this.setComponent = function(component){
		this.FreeLancerComponentOptions.setComponent(component);
	}
	// create page_layout_manager
	// create tools option manager

}