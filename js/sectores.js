jQuery(function($) {
	var grid_selector = "#grid-sectores";
	var pager_selector = "#pager-sectores";	
	
	//Resize
	$(window).on("resize", function () {
	        var newWidth = $(grid_selector).closest(".ui-jqgrid").parent().width();
	        $(grid_selector).jqGrid("setGridWidth", newWidth, true);
	});
	
	jQuery(grid_selector).jqGrid({
		//data: grid_data,
		//datatype: "local",
		url:'controller/sectoresback.php?oper=sectores',
		datatype: "json",
		height: "auto",
		colNames: ['','#','Nombre','Descripci&oacute;n'],
		colModel: [
			{name:'myac', index:'', width:60, fixed:true, sortable:false, resize:false, editable: false,
				formatter:'actions', search: false,
				formatoptions:{ 
					keys:true,
					delbutton: true,//disable delete button
					delOptions:{recreateForm: true, beforeShowForm:beforeDeleteCallback},
					//editformbutton:true, editOptions:{recreateForm: true, beforeShowForm:beforeEditCallback}
				}
			},
			{ name: 'id', width: 50, search:false, hidden: true },
			{ name: 'nombre', width: 150, editable: true, align: "left", editrules: {required: true}, formoptions:{rowpos: 1, colpos: 1} },
			{ name: 'descripcion', width: 180, editable: true, align: "left", formoptions:{rowpos: 2, colpos: 1} }
		],
		autowidth: true,
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		gridview: true,
		height: "100%",
		//toppager: true,					
		//multiselect: true,
		//multikey: "ctrlKey",
        multiboxonly: true,			
		loadComplete : function() {
			var table = this;
			setTimeout(function(){
				styleCheckbox(table);						
				updateActionIcons(table);
				updatePagerIcons(table);
				enableTooltips(table);
			}, 0);						
		},			
		editurl: "controller/sectoresback.php",//nothing is saved
		caption: "Sectores",
		ondblClickRow: function (rowid, iRow,iCol) {
			var row_id = $(grid_selector).getGridParam('selrow');
			jQuery(grid_selector).editRow(row_id, true);
			//jQuery(grid_selector).editCell(iRow, iCol, true);
		}			
		//,autowidth: true,

	});
	$(grid_selector).jqGrid('filterToolbar',{
		stringResult: true, searchOnEnter: false, defaultSearch: 'cn', ignoreCase: true
	});
	//navButtons
	jQuery(grid_selector).jqGrid('navGrid',pager_selector,
		{ 	//navbar options
			edit: false,
			editicon : 'edit',
			add: true,
			addicon : 'add',
			del: false,
			delicon : 'del',
			search: false,
			searchicon : 'search',
			refresh: true,
			refreshicon : 'refresh',
			view: false,
			viewicon : 'view',
		},
		{
			// edit options
			height: "100%",
			width: "100%",
			closeAfterEdit: true,
			recreateForm: true,
			viewPagerButtons: true,
			beforeShowForm : function(e) {
				var form = $(e[0]);
				form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
				style_edit_form(form);
			}
		}, 
        {
			// add options
			//height: "100%",
			width: "100%",
			closeAfterAdd: true,
			recreateForm: true,
			viewPagerButtons: false,
			beforeShowForm : function(e) {							
				var form = $(e[0]);
				form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
				//DESCRIPCION
				var $tr = $("#tr_descripcion"), // 'name' is the column name
				$label = $tr.children("td.CaptionTD"),
				$data = $tr.children("td.DataTD");
				$data.attr("colspan", "4");
				$data.children("input").css("width", "99%");
				//NOMBRE
				var $nombre = $("#tr_nombre"), $labelnombre = $nombre.children("td.CaptionTD"), $datanombre = $nombre.children("td.DataTD");
				$datanombre.attr("colspan", "4");
				$datanombre.children("input").css("width", "99%");
				//$label.hide();
				style_edit_form(form);
			},
			afterComplete: function (response, postdata) {
				//console.log(response.responseText);
				if(response.responseText == '1'){
					//alert('El RUC ya existe');
					demo.showSwal('error-message','ERROR!','El Sector ya existe');
				}else{
					//alert('Proveedor Agregado Exitosamente');
					demo.showSwal('success-message','Buen trabajo!','Sector Agregado Exitosamente');
				}
		    }
        },
        {}, // del options
        {}, // search options
        {   // view options
            beforeShowForm: function(form) {
            	//console.log('view options');
            	//$(".ui-inline-edit").hide();
				form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
            	$('#ViewTbl_grid-centrocostos .ui-inline-edit').parent().hide();
            	$('#v_descripcion span:first-child').hide();
                $("tr#trv_id",form[0]).show();
                style_view_form(form);
            },
            afterclickPgButtons: function(whichbutton, form, rowid) {
                $("tr#trv_id",form[0]).show();
            }
        }
	)
	
	//unlike navButtons icons, action icons in rows seem to be hard-coded
	function updateActionIcons(table) {
		//ACCIONES COL 
		$(table).closest(".ui-jqgrid").find(".ui-sghref>span.glyphicon-eye-open").removeClass("ui-icon glyphicon glyphicon-eye-open center blue") .addClass("icon-col blue fa fa-eye");
		$(table).closest(".ui-jqgrid").find(".ui-sghref>span.glyphicon-eye-close").removeClass("ui-icon glyphicon glyphicon-eye-close center blue") .addClass("icon-col blue fa fa-eye-slash");
		$(table).closest(".ui-jqgrid-btable").find(".subgrid-cell>span.fa-chevron-right").removeClass("ui-icon ace-icon fa fa-chevron-right center blue") .addClass("icon-col blue fa fa-chevron-right");
		$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.ui-icon-pencil").removeClass("ui-icon ui-icon-pencil") .addClass("icon-col blue fa fa-pencil");
		$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.ui-icon-trash").removeClass("ui-icon ui-icon-trash") .addClass("icon-col red fa fa-trash");
		$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.ui-icon-disk").removeClass("ui-icon ui-icon-disk") .addClass("icon-col green-light fa fa-check");
		$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.ui-icon-cancel").removeClass("ui-icon ui-icon-cancel") .addClass("icon-col red fa fa-times");
		
		//ACCIONES FOOTER - add, edit, view, del, search, refresh 
		$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.add").removeClass("ui-icon add") .addClass("icon-actions green-light fa fa-plus");
		$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.edit").removeClass("ui-icon edit") .addClass("icon-actions yellow fa fa-pencil");
		$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.view").removeClass("ui-icon view") .addClass("icon-actions blue fa fa-eye");
		$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.del").removeClass("ui-icon del") .addClass("icon-actions red fa fa-times");
		$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.search").removeClass("ui-icon search") .addClass("icon-actions blue fa fa-search");
		$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.refresh").removeClass("ui-icon refresh") .addClass("icon-actions green-light fa fa-refresh");
	}
	
	//replace icons with FontAwesome icons like above
	function updatePagerIcons(table) {
		//PAGINACIÓN
		$(table).closest(".ui-jqgrid").find(".ui-pg-button>span.ui-icon-seek-first").removeClass("ui-icon ui-icon ui-icon-seek-first") .addClass("icon-pager fa fa-angle-double-left");
		$(table).closest(".ui-jqgrid").find(".ui-pg-button>span.ui-icon-seek-prev").removeClass("ui-icon ui-icon ui-icon-seek-prev") .addClass("icon-pager fa fa-angle-left");
		$(table).closest(".ui-jqgrid").find(".ui-pg-button>span.ui-icon-seek-next").removeClass("ui-icon ui-icon ui-icon-seek-next") .addClass("icon-pager fa fa-angle-right");
		$(table).closest(".ui-jqgrid").find(".ui-pg-button>span.ui-icon-seek-end").removeClass("ui-icon ui-icon ui-icon-seek-end") .addClass("icon-pager fa fa-angle-double-right");
	}
	
	function style_edit_form(form) {
		//update buttons classes
		var buttons = form.next().find('.EditButton .fm-button');
		buttons.addClass('btn btn-sm').find('[class*="-icon"]').hide();//ui-icon, s-icon
		buttons.eq(0).addClass('btn-primary').prepend('<i class="ace-icon fa fa-check"></i>');
		buttons.eq(1).prepend('<i class="ace-icon fa fa-times"></i>')
		
		buttons = form.next().find('.navButton a');
		buttons.find('.ui-icon').hide();
		buttons.eq(0).append('<i class="ace-icon fa fa-chevron-left"></i>');
		buttons.eq(1).append('<i class="ace-icon fa fa-chevron-right"></i>');		
	}
	
	function style_delete_form(form) {
		var buttons = form.next().find('.EditButton .fm-button');
		buttons.addClass('btn btn-sm btn-white btn-round').find('[class*="-icon"]').hide();//ui-icon, s-icon
		buttons.eq(0).addClass('btn-danger').prepend('<i class="ace-icon fa fa-trash-o"></i>');
		buttons.eq(1).addClass('btn-default').prepend('<i class="ace-icon fa fa-times"></i>')
	}
	
	function style_view_form(form) {
		//update buttons classes
		var buttons = form.next().find('.EditButton .fm-button');
		buttons.addClass('btn btn-sm').find('[class*="-icon"]').hide();//ui-icon, s-icon
		buttons.eq(0).addClass('btn-danger').prepend('<i class="ace-icon fa fa-close"></i>   ');
		
		buttons = form.next().find('.navButton a');
		buttons.find('.ui-icon').hide();
		buttons.eq(0).append('<i class="ace-icon fa fa-chevron-left"></i>  ');
		buttons.eq(1).append('<i class="ace-icon fa fa-chevron-right"></i>');		
	}

	function beforeDeleteCallback(e) {
		var form = $(e[0]);
		if(form.data('styled')) return false;
		
		form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
		style_delete_form(form);
		
		form.data('styled', true);
	}

	function beforeEditCallback(e) {
		var form = $(e[0]);
		form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
		style_edit_form(form);
	}

	function styleCheckbox(table) {
	/**
		$(table).find('input:checkbox').addClass('ace')
		.wrap('<label />')
		.after('<span class="lbl align-top" />')


		$('.ui-jqgrid-labels th[id*="_cb"]:first-child')
		.find('input.cbox[type=checkbox]').addClass('ace')
		.wrap('<label />').after('<span class="lbl align-top" />');
	*/
	}

	function enableTooltips(table) {
		$('.navtable .ui-pg-button').tooltip({container:'body'});
		$(table).find('.ui-pg-div').tooltip({container:'body'});
	}
	
	//MARCAR SIDEBAR	
	//MARCAR SIDEBAR
	$("li").removeClass("active");
	$('a').attr("aria-expanded","false");
	$("div#archivo").addClass("in");
	$('div#archivo').parent().addClass("active");
	$('div#archivo').siblings('a').attr("aria-expanded","true");
	$("li#mnuSectores").addClass("active");
});


