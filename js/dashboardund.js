var tecnicos, radiologos, teleradiologos, dias, turnos; 
var estudios, informados, esperadosi, esperadosr, mapa;
var modalidad="*";
var provincia="*";

jQuery(function($) {
	var grid_selector = "#grid-unidades";
	var pager_selector = "#pager-unidades";	
	var lastSel, lastSel2;
	
	//$.jgrid.defaults.width = "100%";
	//$.jgrid.defaults.responsive = true;
	$.jgrid.defaults.styleUI = 'Bootstrap';
	
	$('#tablaUnidad').basictable();
	
	Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
        return {
            radialGradient: {
                cx: 0.5,
                cy: 0.3,
                r: 0.7
            },
            stops: [
                [0, color],
                [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
            ]
        };
    });
	
	cargarDatos();
	abrirEstaUnidad();
	tendenciaEstudios();
	tendenciaTiempos();
	actualizarTiempos();
	tablaRadiologos();
	tablaTecnicos();
	
	$('#cmbProvincias').on('change', function (e) {
		provincia = this.value;
		filtrarProvincia(this.value);
	});
	
	$('#cmbModalidades').on('change', function (e) {
		modalidad = this.value;
		filtrarModalidad(this.value);
	});
	
	$.ajax({
		url: "controller/dashboardundback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "MAPA",
			"unidad": unidad
		}
	}).done(function(data) {
		mapa = new Maplace({
			locations: data,
			type: 'circle',
			circle_options: {
				radius: 4
			},
			controls_on_map: false,
			map_options: {
				mapTypeId: google.maps.MapTypeId.TERRAIN,
				zoom: 12
			},
			listeners: {
				click: function(map, event) {
					//map.setOptions({scrollwheel: false});
				}
			}
		}).Load();
		//console.log(mapa);
	});
	
	$(document).on('ajaxloadstart.page', function(e) {
		$(grid_selector_sla).jqGrid('GridUnload');
		$('.ui-jqdialog').remove();
	});
	
	
	
	//Resize
	$(window).on("resize", function () {
		var newWidth = $(grid_selector).closest(".ui-jqgrid").parent().width();
		$(grid_selector).jqGrid("setGridWidth", newWidth, true);
		var newWidth = $('#grid-tableT').closest(".ui-jqgrid").parent().width();
		$('#grid-tableT').jqGrid("setGridWidth", newWidth, true);
		var newWidth = $('#grid-tableR').closest(".ui-jqgrid").parent().width();
		$('#grid-tableR').jqGrid("setGridWidth", newWidth, true);
	});
	
	var getColumnIndexByName = function(grid,columnName) {
		var cm = grid.jqGrid('getGridParam','colModel'), i=0,l=cm.length;
		for (; i<l; i+=1) {
			if (cm[i].name===columnName) {
				return i; // return the index
			}
		}
		return -1;
	};
	
	function mostrar(cellValue, options, rowObject) {
		return 'Agendados: ' + cellValue;
	}
	
	jQuery(grid_selector).jqGrid({
		url:'controller/dashboardundback.php?opcion=DETALLES&id=' + unidad +'&agno='+$("#filtro-desde").val()+'&mes='+$("#filtro-hasta").val(),  //Comentarios
		datatype: "json",
		colNames: ['','Modalidad','Equipo','Realizados','Informados'],
		colModel: [
			{name:'myac', index:'', width:10, fixed:true, sortable:false, resize:false, editable: false,
				formatter:'actions', search: false,
				formatoptions:{ 
					keys:true,
					editbutton: false,
					delbutton: false,//disable delete button
					delOptions:{recreateForm: true, beforeShowForm:beforeDeleteCallback},
					//editformbutton:true, editOptions:{recreateForm: true, beforeShowForm:beforeEditCallback}
				}
			},
			{ name: 'modalidad', width: 250, sortable: false },
			{ name: 'equipo', width: 250, sortable: false },
			{ name: 'realizados', index: 'realizados', width: 50, search: false, 
				formatter:'number', summaryType: 'sum', align: 'right', sortable: false,
				summaryTpl : "<b>{0}</b>",
				formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 0}},
			{ name: 'informados', index: 'informados', width: 50, search: false, 
				formatter:'number', summaryType: 'sum', align: 'right', sortable: false,
				summaryTpl : "<b>{0}</b>",
				formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 0}}
		],	
		autowidth: true,
		viewrecords: true,
		rowNum:1000,
		grouping:true, 
		groupingView : {
			 groupField : ['modalidad'],
			 groupDataSorted : true,
			 groupColumnShow : [false],
			 plusicon : 'fa fa-chevron-down bigger-110',
			 minusicon : 'fa fa-chevron-up bigger-110',
			 groupText: ['<b>{0}</b>'],
			 groupSummary: [true],
			 groupSummaryPos: ["footer"],
			 groupCollapse: true

		},		
		loadComplete : function() {
			var grid = $(this),
			iCol = getColumnIndexByName(grid,'myac'); // 'act' - name of the actions column
			var table = this;
			setTimeout(function(){
				styleCheckbox(table);						
				updateActionIcons(table);
				updatePagerIcons(table);
				enableTooltips(table);
			}, 0);
		},			
		editurl: "controller/dashboardback.php",//nothing is saved
		caption: null,
		ondblClickRow: function (rowid, iRow,iCol) {
			var row_id = $(grid_selector).getGridParam('selrow');
			jQuery(grid_selector).editRow(row_id, true);
			//jQuery(grid_selector).editCell(iRow, iCol, true);
		}			
		//,autowidth: true,

	});/*
	$(grid_selector).jqGrid('filterToolbar',{
		stringResult: true, searchOnEnter: false, defaultSearch: 'cn', ignoreCase: true
	}); */
	//navButtons
	jQuery(grid_selector).jqGrid('navGrid',pager_selector,
		{ 	//navbar options
			edit: false,
			editicon : 'edit',
			add: false,
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
				style_edit_form(form);
			},
			afterComplete: function (response, postdata) {
				if(response.responseText == '1'){
					//alert('El RUC ya existe');
					demo.showSwal('error-message','ERROR!','El RUC ya existe');
				}else{
					//alert('Proveedor Agregado Exitosamente');
					demo.showSwal('success-message','Buen trabajo!','Proveedor Agregado Exitosamente');
				}
		    }
        },
        {}, // del options
        {}, // search options
        {   // view options
            beforeShowForm: function(form) {
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
	//$('.ui-jqgrid-hdiv').hide();
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
	
	//	VALIDA CARACTERES	//
	function numeros(e,t) {  
		if ((e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105) && e.keyCode != 8 && e.keyCode != 9){
			e.preventDefault();
			$(t).addClass('form-valide-error');
		}else{
			$(t).removeClass('form-valide-error');			
		}
	}
	function letras(e,t) { 
		if ((e.keyCode < 65 || e.keyCode > 90) && e.keyCode != 8 && e.keyCode != 9 && e.keyCode != 32 && e.keyCode != 0){
			e.preventDefault(); 
			$(t).addClass('form-valide-error');
		}else{
			$(t).removeClass('form-valide-error');
		}
	}
	function correos(e,t) { 
			if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($(t).val()))){
				$(t).addClass('form-valide-error');		
			}else{
				$(t).removeClass('form-valide-error');	
			}
	}

	//VALIDA CAMPOS VACIOS
	function campos(e,t) { 
		if($(t).val()=='' || $(t).val()==0){ $(t).addClass('form-valide-error');}
		else{$(t).removeClass('form-valide-error');}	
	}
	$('.switch-sidebar-mini input').change();
});

/*$("#filtro-desde, #filtro-hasta").change(function(){
	var filtro = {"desde":$("#filtro-desde").val(),"hasta":$("#filtro-hasta").val()};
	//Grid - Ordenes, Incidentes
	//$("#grid-mtto").trigger('reloadGrid');		
});*/

$('#filtro-desde').datepicker({
    format: "yyyy",
    startView: 2,
    minViewMode: 2,
    maxViewMode: 2,
    language: "es",
	clearBtn: true,
	closeBtn: true,
    multidate: true,
	showButtonPanel: true,
	onSelect:function(event){
		  event.preventDefault();
	},
    multidateSeparator: ","
});

$('#filtro-hasta').datepicker({
    format: "mm",
    startView: 1,
    minViewMode: 1,
    maxViewMode: 1,
    language: "es",
	clearBtn: true,
    multidate: true,
	closeText: "Ok",
    multidateSeparator: ","
});

function buscarValor(variable, agno, data) {
	var xvalor = 0
	for(var i = 0; i < data.rows.length; i++)
	{
	  if(data.rows[i].modalidad == variable && data.rows[i].ano == agno)
	  {
		return data.rows[i].valor;
	  }
	}
	return xvalor;
}

function cargarDatos() {
	var formatNumber = {
		separador: ",", // separador para los miles
		sepDecimal: '.', // separador para los decimales
		formatear:function (num){
			num +='';
			var splitStr = num.split('.');
			var splitLeft = splitStr[0];
			var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
			var regx = /(\d+)(\d{3})/;
			while (regx.test(splitLeft)) {
				splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
			}
			return this.simbol + splitLeft +splitRight;
		},
		new:function(num, simbol){
			this.simbol = simbol ||'';
			return this.formatear(num);
		}
	}
	
	
	$.ajax({
		url: "controller/dashboardundback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "DATOS",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"unidad": unidad
		}
	}).done(function(data) {
		/*
		Angiografia
		Fluoroscopia
		Mamografia
		Otros Estudios
		Radiologia Convencional
		Resonancia Magnetica
		Tomografia Computada
		Ultrasonido
		*/
		total = 0;
		var i=0;
		var xano = data.rows[i].ano;
		while (i<data.rows.length && data.rows[i].ano==xano) {
			total += Number(data.rows[i].valor);
			i++;
		}	
		$("#txtAgno").html(xano);
		$("#txtTotal").html(formatNumber.new(total));
		$("#txtTotalPorc").html("100");
		$("#txtTotalPorc2").attr("data-percent",100);
		var xvalor = buscarValor('Angiografia', xano, data);
		$("#txtAg").html(formatNumber.new(xvalor));
		$("#txtAgPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtAgPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Fluoroscopia', xano, data);
		$("#txtFl").html(formatNumber.new(xvalor));
		$("#txtFlPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtFlPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Mamografia', xano, data);
		$("#txtMg").html(formatNumber.new(xvalor));
		$("#txtMgPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtMgPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Otros Estudios', xano, data);
		$("#txtEd").html(formatNumber.new(xvalor));
		$("#txtEdPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtEdPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Radiologia Convencional', xano, data);
		$("#txtRx").html(formatNumber.new(xvalor));
		$("#txtRxPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtRxPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Resonancia Magnetica', xano, data);
		$("#txtRm").html(formatNumber.new(xvalor));
		$("#txtRmPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtRmPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Tomografia Computada', xano, data);
		$("#txtTc").html(formatNumber.new(xvalor));
		$("#txtTcPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtTcPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Tomografia Computada', xano, data);
		$("#txtUs").html(formatNumber.new(xvalor));
		$("#txtUsPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtUsPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		
		total = 0;
		xano = data.rows[i].ano;
		while (i<data.rows.length && data.rows[i].ano==xano) {
			total += Number(data.rows[i].valor);
			i++;
		}	
		$("#txtAgno2").html(xano);
		$("#txtTotal2").html(formatNumber.new(total));
		$("#txtTotal2Porc").html("100");
		$("#txtTotal2Porc2").attr("data-percent",100);
		var xvalor = buscarValor('Angiografia', xano, data);
		$("#txtAg2").html(formatNumber.new(xvalor));
		$("#txtAg2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtAg2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Fluoroscopia', xano, data);
		$("#txtFl2").html(formatNumber.new(xvalor));
		$("#txtFl2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtFl2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Mamografia', xano, data);
		$("#txtMg2").html(formatNumber.new(xvalor));
		$("#txtMg2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtMg2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Otros Estudios', xano, data);
		$("#txtEd2").html(formatNumber.new(xvalor));
		$("#txtEd2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtEd2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Radiologia Convencional', xano, data);
		$("#txtRx2").html(formatNumber.new(xvalor));
		$("#txtRx2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtRx2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Resonancia Magnetica', xano, data);
		$("#txtRm2").html(formatNumber.new(xvalor));
		$("#txtRm2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtRm2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Tomografia Computada', xano, data);
		$("#txtTc2").html(formatNumber.new(xvalor));
		$("#txtTc2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtTc2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Tomografia Computada', xano, data);
		$("#txtUs2").html(formatNumber.new(xvalor));
		$("#txtUs2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtUs2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		
		total = 0;
		xano = data.rows[i].ano;
		while (i<data.rows.length && data.rows[i].ano==xano) {
			total += Number(data.rows[i].valor);
			i++;
		}	
		$("#txtAgno3").html(xano);
		$("#txtTotal3").html(formatNumber.new(total));
		$("#txtTotal3Porc").html("100");
		$("#txtTotal3Porc2").attr("data-percent",100);
		var xvalor = buscarValor('Angiografia', xano, data);
		$("#txtAg3").html(formatNumber.new(xvalor));
		$("#txtAg3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtAg3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Fluoroscopia', xano, data);
		$("#txtFl3").html(formatNumber.new(xvalor));
		$("#txtFl3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtFl3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Mamografia', xano, data);
		$("#txtMg3").html(formatNumber.new(xvalor));
		$("#txtMg3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtMg3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Otros Estudios', xano, data);
		$("#txtEd3").html(formatNumber.new(xvalor));
		$("#txtEd3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtEd3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Radiologia Convencional', xano, data);
		$("#txtRx3").html(formatNumber.new(xvalor));
		$("#txtRx3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtRx3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Resonancia Magnetica', xano, data);
		$("#txtRm3").html(formatNumber.new(xvalor));
		$("#txtRm3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtRm3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Tomografia Computada', xano, data);
		$("#txtTc3").html(formatNumber.new(xvalor));
		$("#txtTc3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtTc3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Tomografia Computada', xano, data);
		$("#txtUs3").html(formatNumber.new(xvalor));
		$("#txtUs3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtUs3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		
		$('.easy-pie-chart.percentage').each(function(){
			var $box = $(this).closest('.infobox');
			var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
			var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
			var size = parseInt($(this).data('size')) || 40;
			$(this).easyPieChart({
				barColor: barColor,
				trackColor: trackColor,
				scaleColor: false,
				lineCap: 'butt',
				lineWidth: parseInt(size/10),
				animate: /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ? false : 1000,
				size: size
			});
		})
	});
}

//replace icons with FontAwesome icons like above
function updatePagerIcons(table) {
	//PAGINACIÓN
	$(table).closest(".ui-jqgrid").find(".ui-pg-button>span.ui-icon-seek-first").removeClass("ui-icon ui-icon ui-icon-seek-first") .addClass("icon-pager fa fa-angle-double-left");
	$(table).closest(".ui-jqgrid").find(".ui-pg-button>span.ui-icon-seek-prev").removeClass("ui-icon ui-icon ui-icon-seek-prev") .addClass("icon-pager fa fa-angle-left");
	$(table).closest(".ui-jqgrid").find(".ui-pg-button>span.ui-icon-seek-next").removeClass("ui-icon ui-icon ui-icon-seek-next") .addClass("icon-pager fa fa-angle-right");
	$(table).closest(".ui-jqgrid").find(".ui-pg-button>span.ui-icon-seek-end").removeClass("ui-icon ui-icon ui-icon-seek-end") .addClass("icon-pager fa fa-angle-double-right");
}

dialog = $( "#dialog-unidad" ).dialog({		
	//width: '75%', 
	width: 'auto', // overcomes width:'auto' and maxWidth bug
    maxWidth: 800,
    height: 'auto',
    modal: true,
    fluid: true, //new option
    resizable: true,
	autoOpen: false,
	position: ['center','middle']
});

function cerrarUnidad() {
	dialog.dialog('close');
}

function abrirUnidad(point) {
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "DATOSUNIDAD",
			"unidad": point,
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val()
		}
	}).done(function(data) {
		console.log(data);
		
		dialog.dialog( "open" );
		$("#txtImagen").html("<img src='images/sedes/"+data.rows[0].codigo+".jpg' />");
		$("#txtUnidad").html(point);
		$("#txtTecnicos").html("Nro. T\u00E9cnicos: <strong>" + data.rows[0].tecnicos + "</strong>");
		$("#txtRadiologos").html("Nro. Radi\u00F3logos: <strong>" + data.rows[0].radiologos + "</strong>");
		$("#txtTeleRadiologos").html("Nro. Radi\u00F3logos (Remotos): <strong>" + data.rows[0].teleradiologos + "</strong>");
		$("#txtTurnos").html("Turnos: <strong>" + data.rows[0].turno + "</strong>");
		if (Number(data.rows[0].dias)>1)
			$("#txtDias").html("Trabaja fines de semana");
		else
			$("#txtDias").html("No trabaja fines de semana");
		$("#txtRealizados").html("Estudios <br />Realizados: <strong>" + Number(data.rows[0].realizados).toLocaleString('en') + "</strong>");
		$("#txtInformados").html("Informados: <strong>" + Number(data.rows[0].informados).toLocaleString('en') + "</strong>");
		$("#txtInformadosDist").html("&nbsp;&nbsp;&nbsp;<strong>- Locales: " + Number(data.rows[0].informadosl).toLocaleString('en') + "<br />&nbsp;&nbsp;&nbsp;- Remotos: <strong>" + Number(data.rows[0].informadosr).toLocaleString('en') + "</strong>");
		$("#txtDiasAtencion").html("Atencion: <strong>" + Number(data.rows[0].atencion).toLocaleString('en') + " d</strong>");
		$("#txtDiasInforme").html("Informe: <strong>" + Number(data.rows[0].informe).toLocaleString('en') + " d</strong>");
		$("#txtDiasTotal").html("Total: <strong>" + Number(data.rows[0].total).toLocaleString('en') + " d</strong>");
		tecnicos = data.rows[0].tecnicos;
		radiologos = data.rows[0].radiologos;
		teleradiologos = data.rows[0].teleradiologos;
		turnos = data.rows[0].turno;
		dias = data.rows[0].dias;
		estudios = Number(data.rows[0].realizados);
		informados = Number(data.rows[0].informados);
		if (point.indexOf('Hospital')>-1) {
			esperadosi = (radiologos + teleradiologos) * 1000;
			esperadosr = tecnicos * 3000;
		} else {
			esperadosi = (radiologos + teleradiologos) * 1000;
			esperadosr = tecnicos * 4000;
		}
		$("#datosUnidad").html('');
		datosModalidad = data.rows[0].datos;
		$.each(datosModalidad, function(index, value){
			$("#datosUnidad").append("<tr><td>" + value.modalidad + "</td><td>" + value.cantidad + "</td><td>" + value.realizados + "</td><td>" + value.informados + "</td></tr>");
		});
		
		//$("#grid-unidades-win").jqGrid('setGridParam', { url: urlOrdenes });
		//$("#grid-unidades-win").jqGrid('clearGridData');
		//$("#grid-unidades-win").trigger('reloadGrid');
		//prodesperada();
		//$("#txtRx").html(data.rx);
		//$("#txtFl").html(formatNumber.new(data.rows[2].valor));
		//$("#txtEdPorc").html((data.rows[4].valor * 100 / data.rows[0].valor).toFixed(1) + " %");
	});
}


function abrirEstaUnidad() {
	$.ajax({
		url: "controller/dashboardundback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "DATOSESTAUNIDAD",
			"unidad": unidad,
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val()
		}
	}).done(function(data) {
		$("#txtUndImagen").html("<img src='images/sedes/"+data.rows[0].codigo+".jpg' />");
		$("#txtUndUnidad").html("<h3>" + data.rows[0].unidad + "</h3>");
		$("#txtUndTecnicos").html("Nro. T\u00E9cnicos: <strong>" + data.rows[0].tecnicos + "</strong>");
		$("#txtUndRadiologos").html("Nro. Radi\u00F3logos: <strong>" + data.rows[0].radiologos + "</strong>");
		$("#txtUndTeleRadiologos").html("Nro. Radi\u00F3logos (Remotos): <strong>" + data.rows[0].teleradiologos + "</strong>");
		$("#txtUndTurnos").html("Turnos: <strong>" + data.rows[0].turno + "</strong>");
		if (Number(data.rows[0].dias)>1)
			$("#txtUndDias").html("Trabaja fines de semana");
		else
			$("#txtUndDias").html("No trabaja fines de semana");
		$("#txtUndRealizados").html("Estudios realizados: <strong>" + Number(data.rows[0].realizados).toLocaleString('en') + "</strong>");
		$("#txtUndInformados").html("Estudios informados: <strong>" + Number(data.rows[0].informados).toLocaleString('en') + "</strong>");
		$("#txtUndInformadosDist").html("&nbsp;&nbsp;&nbsp;<strong>- Locales: " + Number(data.rows[0].informadosl).toLocaleString('en') + "<br />&nbsp;&nbsp;&nbsp;- Remotos: <strong>" + Number(data.rows[0].informadosr).toLocaleString('en') + "</strong>");
		$("#txtUndDiasAtencion").html("Atencion: <strong>" + Number(data.rows[0].atencion).toLocaleString('en') + " d</strong>");
		$("#txtUndDiasInforme").html("Informe: <strong>" + Number(data.rows[0].informe).toLocaleString('en') + " d</strong>");
		$("#txtUndDiasTotal").html("Total: <strong>" + Number(data.rows[0].total).toLocaleString('en') + " d</strong>");
		tecnicos = data.rows[0].tecnicos;
		radiologos = data.rows[0].radiologos;
		teleradiologos = data.rows[0].teleradiologos;
		turnos = data.rows[0].turno;
		dias = data.rows[0].dias;
		estudios = Number(data.rows[0].realizados);
		informados = Number(data.rows[0].informados);
		/*
		$("#datosUndUnidad").html('');
		datosModalidad = data.rows[0].datos;
		$.each(datosModalidad, function(index, value){
			$("#datosUndUnidad").append("<tr><td>" + value.modalidad + "</td><td>" + value.codequipo + "</td><td>" + value.equipo + "</td><td>" + value.realizados + "</td><td>" + value.informados + "</td></tr>");
		});
		*/
		//$("#grid-unidades-win").jqGrid('setGridParam', { url: urlOrdenes });
		//$("#grid-unidades-win").jqGrid('clearGridData');
		//$("#grid-unidades-win").trigger('reloadGrid');
		//prodesperada();
		//$("#txtUndRx").html(data.rx);
		//$("#txtUndFl").html(formatNumber.new(data.rows[2].valor));
		//$("#txtUndEdPorc").html((data.rows[4].valor * 100 / data.rows[0].valor).toFixed(1) + " %");
	});
}

function tendenciaEstudios() {
	$.ajax({
		url: "controller/dashboardundback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "TENDENCIA",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"unidad": unidad
		}
	}).done(function(data) {
		arrCategorias = [];
		arrRealizados = [];
		arrInformados = [];
		arrAgendados = [];
		arrCancelados = [];
		for (i=0; i<6; i++) {
			arrCategorias.push(data[i].periodo);
			arrRealizados.push(Number(data[i].realizados));
			arrInformados.push(Number(data[i].informados));
			arrAgendados.push(Number(data[i].agendados));
			arrCancelados.push(Number(data[i].cancelados));
		}
		
		arrCategorias.reverse();
		arrRealizados.reverse();
		arrInformados.reverse();
		arrAgendados.reverse();
		arrCancelados.reverse();
		
		var options = {
			chart: {
				renderTo: 'tendencia',
				type: 'spline',
				height: 220
			},
			title: {
				text: null
			},
			subtitle: {
				text: null
			},
			xAxis: {
				categories: arrCategorias
			},
			yAxis: {
				title: {
					text: 'Estudios (k)'
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}],
				max: null
			},
			tooltip: {
				valueDecimals: 1,
				valuePrefix: null,
				valueSuffix: null
			},
			legend: {
				borderWidth: 0
			},
			plotOptions: {
				spline: {
					dataLabels: {
						enabled: true,
						format: '{y:.1f} K'
					},
					enableMouseTracking: false
				}
			},
			series: [{
				name: 'Realizados',
				lineWidth: 4,
				data: arrRealizados
			}, {
				name: 'Informados',
				lineWidth: 4,
				data: arrInformados
			}, {
				name: 'Agendados',
				lineWidth: 4,
				data: arrAgendados
			}, {
				name: 'Cancelados',
				lineWidth: 4,
				color: '#FF0000',
				data: arrCancelados
			}]
	};
		
		var chart = new Highcharts.Chart(options);
	
	
	});
}

function tendenciaTiempos() {
	$.ajax({
		url: "controller/dashboardundback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "TIEMPOS",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"unidad": unidad
		}
	}).done(function(data) {
		arrCategorias = [];
		arrAn = []; arrDe = [];
		arrRx = []; arrFl = [];
		arrRm = []; arrTc = [];
		arrMg = []; arrUs = [];
		
		for (i=0; i<48; i++) {
			arrCategorias.push(data[i].periodo);
			arrAn.push(Number(data[i+0].dias));
			arrFl.push(Number(data[i+1].dias));
			arrMg.push(Number(data[i+2].dias));
			arrDe.push(Number(data[i+3].dias));
			arrRx.push(Number(data[i+4].dias));
			arrRm.push(Number(data[i+5].dias));
			arrTc.push(Number(data[i+6].dias));
			arrUs.push(Number(data[i+7].dias));
			i+=7;
		}
		
		arrCategorias.reverse();
		arrAn.reverse(); arrDe.reverse();
		arrRx.reverse(); arrFl.reverse();
		arrRm.reverse(); arrTc.reverse();
		arrMg.reverse(); arrUs.reverse();
		
		var options = {
			chart: {
				renderTo: 'tiempos',
				type: 'spline',
				height: 220
			},
			title: {
				text: null
			},
			subtitle: {
				text: null
			},
			xAxis: {
				categories: arrCategorias
			},
			yAxis: {
				title: {
					text: 'Dias'
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}],
				max: null
			},
			tooltip: {
				valueDecimals: 1,
				valuePrefix: null,
				valueSuffix: null
			},
			legend: {
				borderWidth: 0
			},
			plotOptions: {
				spline: {
					dataLabels: {
						enabled: true,
						format: '{y:.1f} d'
					},
					enableMouseTracking: false
				}
			},
			series: [{
				name: 'Ag',
				lineWidth: 4,
				data: arrAn
			}, {
				name: 'Rx',
				lineWidth: 4,
				data: arrRx
			}, {
				name: 'RM',
				lineWidth: 4,
				data: arrRm
			}, {
				name: 'Mg',
				lineWidth: 4,
				data: arrMg
			},{
				name: 'Us',
				lineWidth: 4,
				data: arrUs
			}, {
				name: 'Tc',
				lineWidth: 4,
				data: arrTc
			}, {
				name: 'Fl',
				lineWidth: 4,
				data: arrFl
			}, {
				name: 'De',
				lineWidth: 4,
				data: arrDe
			}]
	};
	var chart = new Highcharts.Chart(options);
	});
}

function actualizarTiempos() {
	$.ajax({
		url: "controller/dashboardundback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "TIEMPOSESPERA",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"unidad": unidad
		}
	}).done(function(data) {
		arrCategorias = [];
		arrAtencion = [];
		arrInformes = [];
		arrTotal = [];
		for (i=0; i<data.length; i++) {
			arrCategorias.push(data[i].modalidad);
			arrAtencion.push(Number(data[i].atencion) / Number(data[i].estudios));
			arrInformes.push(Number(data[i].informes) / Number(data[i].estudios));
			arrTotal.push(arrAtencion[i] + arrInformes[i]);
		}
		
		var options = {
			chart: {
				type: 'column',
				renderTo: 'tiemposespera',
				margin: 75,
				options3d: {
					enabled: true,
					alpha: 5,
					beta: 10,
					depth: 50
				}
			},
			title: {
				text: null
			},
			plotOptions: {
				column: {
					depth: 25
				}
			},
			xAxis: {
				categories: arrCategorias
			},
			yAxis: {
				title: {
					text: 'Dias'
				}
			},
			tooltip: {
				headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
					'<td style="padding:0"><b>{point.y:.1f} D.</b></td></tr>',
				footerFormat: '</table>',
				shared: true,
				useHTML: true
			},
			series: [{
				name: 'Total',
				lineWidth: 4,
				data: arrTotal
			}, {
				name: 'Atencion',
				lineWidth: 4,
				data: arrAtencion
			}, {
				name: 'Informe',
				lineWidth: 4,
				data: arrInformes
			}]
	};
		
		var chart = new Highcharts.Chart(options);
	
	
	});
}

/*
		Grid dentro ventana de unidades
	
	var grid_selector_win 	= "#grid-unidades-win";
	
	jQuery(grid_selector_win).jqGrid({
		url:'controller/dashboardback.php?opcion=DETALLES2&id=',
		datatype: "json",
		colNames: ['Modalidad','Cantidad','Agendados','Realizados','Informados'],
		colModel: [
			{ name: 'modalidad', width: 180, search: false, sortable: false },
			{ name: 'cantidad', width: 70, search: false, align: 'center', sortable: false },
			{ name: 'agendados', index: 'agendados', width: 70, search: false, sortable: false, 
				formatter:'number', align: 'center',
				formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 0}},
			{ name: 'realizados', index: 'realizados', width: 70, search: false, sortable: false, 
				formatter:'number', align: 'right',
				formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 0}},
			{ name: 'informados', index: 'informados', width: 70, search: false, sortable: false, 
				formatter:'number', align: 'right',
				formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 0}}
		],	
		viewrecords: true,
		rowNum:100,
		altRows: true,
		gridview:true,
		height: "100%",
		autoWidth: true,
		ondblClickRow: function (rowid, iRow,iCol) {
			var row_id = $(grid_selector_win).getGridParam('selrow');
			window.open("ordendetalle.php?num="+row_id, "_blank");
			//jQuery("#mygrid").editCell(iRow, iCol, true);
		}
	});
	jQuery(grid_selector_win).jqGrid('setGroupHeaders', {
	  useColSpanStyle: false, 
	  groupHeaders:[
		{startColumnName: 'modalidad', numberOfColumns: 2, titleText: 'Equipos'},
		{startColumnName: 'agendados', numberOfColumns: 3, titleText: 'Estudios'}
	  ]
	});
	*/
	
	
	
	function prodesperada() {
		var options = {
			chart: {
				renderTo: 'prodesperada',
				type: 'column',
				height: 250
			},
			title: {
				text: 'Productividad (capacidad vs real)'
			},
			xAxis: {
				categories: ['Realizados', 'Informados'],
				title: {
					text: null
				}
			},
			yAxis: [{
				min: 0,
				title: {
					text: 'Estudios'
				}
			}, {
				title: {
					text: null //'Profit (millions)'
				},
				opposite: true
			}],
			legend: {
				shadow: false
			},
			tooltip: {
				shared: true
			},
			plotOptions: {
				column: {
					grouping: false,
					shadow: false,
					borderWidth: 0
				}
			},
				series: [{
						name: 'Esperados',
						color: 'rgba(165,170,217,1)',
						data: [esperadosr, esperadosi],
						pointPadding: 0.3,
						pointPlacement: -0.2
					}, {
						name: 'Cumplidos',
						color: 'rgba(126,86,134,.9)',
						data: [estudios, informados],
						pointPadding: 0.4,
						pointPlacement: -0.2
					}]	
				}
			
			chartArea = new Highcharts.Chart(options);
	}
	

	function filtrarDashboard() {
		cargarDatos();
		abrirEstaUnidad();
		tendenciaEstudios();
		tendenciaTiempos();
		actualizarTiempos();
		tablaRadiologos();
		tablaTecnicos();
		agno=$("#filtro-desde").val();
		mes=$("#filtro-hasta").val();
		urlOrdenes = 'controller/dashboardundback.php?opcion=DETALLES&id=' + unidad +'&agno='+$("#filtro-desde").val()+'&mes='+$("#filtro-hasta").val();
		$("#grid-unidades").jqGrid('setGridParam', { url: urlOrdenes });
		$("#grid-unidades").jqGrid('clearGridData');
		$("#grid-unidades").trigger('reloadGrid');
		
	}
	
	function mostrarHospitales() {
		$.ajax({
			url: "controller/dashboardundback.php",
			cache: false,
			dataType: "json",
			method: "POST",
			data: {
				"opcion": "MAPA",
				"tipo" : "Hospital",
				"unidad" : unidad
			}
		}).done(function(data) {
			mapa.Load({
				locations: data,
				type: 'circle',
				circle_options: {
					radius: 4
				},
				controls_on_map: false,
				map_options: {
					mapTypeId: google.maps.MapTypeId.TERRAIN,
					zoom: 12
				},
				listeners: {
					click: function(map, event) {
						//map.setOptions({scrollwheel: true});
					}
				}
			});
		});
	}
	
	function mostrarPoliclinicas() {
		$.ajax({
			url: "controller/dashboardundback.php",
			cache: false,
			dataType: "json",
			method: "POST",
			data: {
				"opcion": "MAPA",
				"tipo" : "Poli",
				"unidad" : unidad
			}
		}).done(function(data) {
			mapa.Load({
				locations: data,
				type: 'circle',
				circle_options: {
					radius: 4
				},
				controls_on_map: false,
				map_options: {
					mapTypeId: google.maps.MapTypeId.TERRAIN,
					zoom: 12
				},
				listeners: {
					click: function(map, event) {
						//map.setOptions({scrollwheel: true});
					}
				}
			});
		});
	}
	
	function mostrarUlaps() {
		$.ajax({
			url: "controller/dashboardundback.php",
			cache: false,
			dataType: "json",
			method: "POST",
			data: {
				"opcion": "MAPA",
				"tipo" : "Ulap",
				"unidad" : unidad
			}
		}).done(function(data) {
			mapa.Load({
				locations: data,
				type: 'circle',
				circle_options: {
					radius: 4
				},
				controls_on_map: false,
				map_options: {
					mapTypeId: google.maps.MapTypeId.TERRAIN,
					zoom: 12
				},
				listeners: {
					click: function(map, event) {
						//map.setOptions({scrollwheel: true});
					}
				}
			});
		});
	}
	
	function mostrarTodos() {
		$.ajax({
			url: "controller/dashboardundback.php",
			cache: false,
			dataType: "json",
			method: "POST",
			data: {
				"opcion": "MAPA",
				"tipo" : "",
				"unidad" : unidad
			}
		}).done(function(data) {
			mapa.Load({
				locations: data,
				type: 'circle',
				circle_options: {
					radius: 4
				},
				controls_on_map: false,
				map_options: {
					mapTypeId: google.maps.MapTypeId.TERRAIN,
					zoom: 12
				},
				listeners: {
					click: function(map, event) {
						//map.setOptions({scrollwheel: true});
					}
				}
			});
		});
	}
	
function tablaRadiologos() {
	var urlRadiologos = 'controller/dashboardundback.php?opcion=RADIOLOGOS&id=' + unidad +'&agno='+$("#filtro-desde").val()+'&mes='+$("#filtro-hasta").val();
	
	jQuery("#grid-tableR").jqGrid({
		url:urlRadiologos,
		datatype: "json",
		colNames:['Nombre','Dia','Horario','Mod.', 'Informados', 'Dias', 'Puntos'],
		colModel:[
			{name:'Nombre',index:'nombre',width:40,search:true},
			{name:'Dia',index:'tipodia',width:30,search:true},
			{name:'Horario',index:'tipohora',width:30,search:true},
			{name:'Modalidad',index:'modalidad', width:30,search:true},
			{name:'Informados',index:'estudios', width:20, sorttype:"int", align:"right",search:false,summaryType:'sum'},
			{name:'Dias',index:'dias', width:20, sorttype:"float", align:"right",search:false,formatter: 'number', formatoptions: { decimalPlaces: 1 }},
			{name:'Valor',index:'valor', width:20, sorttype:"int", align:"right",search:false,summaryType:'sum'}
		],
		autowidth: true,
		rowNum:10,
		rowList:[10,20,30],
		pager: '#grid-pagerR',
		sortname: 'nombre',
		viewrecords: true,
		grouping:true,
		groupingView : {
			groupField : ['Nombre'],
			//groupColumnShow : [false],
			groupSummary : [true],
			groupCollapse: false
		},
		sortorder: "asc",
		loadComplete : function() {
				var table = this;
				setTimeout(function(){
					//updateActionIcons(table);
					updatePagerIcons(table);
					enableTooltips(table);
				}, 0);
			},
		caption:"Productividad Radi&oacute;logos"
	});
	
	//enable search/filter toolbar	
	$('#grid-tableR').jqGrid('filterToolbar',{
		stringResult: true, searchOnEnter: false, defaultSearch: 'cn', ignoreCase: true, enableClear: false 
	});
	
	
	$('#grid-tableR').jqGrid('navGrid',"#grid-pagerR", 
	{                
		add: false,
		edit: false,
		del: false,
		search: true,
		searchicon : 'ace-icon fa fa-search blue',
		refresh: true,
		refreshicon : 'ace-icon fa fa-refresh green'
	},
	{
		//search form
		recreateForm: true,
		afterShowSearch: function(e){
			var form = $(e[0]);
			form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
			style_search_form(form);
		},
		afterRedraw: function(){
			style_search_filters($(this));
		},
		multipleSearch: true
	}).navSeparatorAdd(pager_selector,{
		sepclass : "ui-separator",sepcontent: ""}
	).navButtonAdd(pager_selector, 
		{
			caption:"Exportar a Excel", 
			title: "Click para exportar datos a excel",
			buttonicon: "icon-actions green fa fa-save",
			onClickButton: function(){
				exportRadiologosExcel();
			},
			position:"last"
		}
	);
	
	var grid_selector = "#grid-tableR";
	var pager_selector = "#grid-pagerR";
	
	
	//resize to fit page size
	$(window).on('resize.jqGrid', function () {
		$(grid_selector).jqGrid( 'setGridWidth', $(".col-sm-7").width() );
	})
	//resize on sidebar collapse/expand
	var parent_column = $(grid_selector).closest('[class*="col-"]');
	$(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
		if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
			//setTimeout is for webkit only to give time for DOM changes and then redraw!!!
			setTimeout(function() {
				$(grid_selector).jqGrid( 'setGridWidth', parent_column.width() );
			}, 0);
		}
	});
	
	//replace icons with FontAwesome icons like above
	function updatePagerIcons(table) {
		var replacement = 
		{
			'ui-icon-seek-first' : 'ace-icon fa fa-angle-double-left bigger-140',
			'ui-icon-seek-prev' : 'ace-icon fa fa-angle-left bigger-140',
			'ui-icon-seek-next' : 'ace-icon fa fa-angle-right bigger-140',
			'ui-icon-seek-end' : 'ace-icon fa fa-angle-double-right bigger-140'
		};
		$('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function(){
			var icon = $(this);
			var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
			
			if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
		})
	}

	function enableTooltips(table) {
		$('.navtable .ui-pg-button').tooltip({container:'body'});
		$(table).find('.ui-pg-div').tooltip({container:'body'});
	}
	
}

function exportRadiologosExcel()
{

	$("#grid-tableR").closest(".ui-jqgrid").find('.loading').show();
	
   $.ajax({
		url: "funciones.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "TRX",
			"cmbAgnos": $("#cmbAgnos").val(),
			"cmbMeses": $("#cmbMeses").val(),
			"cmbHospitales": $("#cmbHospitales").val(),
			"cmbPoliclinicas": $("#cmbPoliclinicas").val(),
			"cmbUlaps": $("#cmbUlaps").val(),
			"cmbModalidades": $("#cmbModalidades").val()
		}
	}).done(function(data) {
		console.log(data);
		document.location.href =(data.archivo);		
		$("#grid-tableR").closest(".ui-jqgrid").find('.loading').hide();
		//$("#grid-tableE").loadText = 'Cargando';
		//$('.loading').hide();
	});
}
	
function tablaTecnicos() {
	var urlTecnicos = 'controller/dashboardundback.php?opcion=TECNICOS&id=' + unidad +'&agno='+$("#filtro-desde").val()+'&mes='+$("#filtro-hasta").val();
	
	var grid_selectorT = "#grid-tableT";
	var pager_selectorT = "#grid-pagerT";
	
	jQuery("#grid-tableT").jqGrid({
		url: urlTecnicos,
		datatype: "json",
		colNames:['Nombre','Dia','Horario','Mod.', 'Realizados', 'Puntos'],
		colModel:[
			{name:'Nombre',index:'nombre',width:40,search:true},
			{name:'Dia',index:'tipodia',width:30,search:true},
			{name:'Horario',index:'tipohora',width:30,search:true},
			{name:'Modalidad',index:'modalidad', width:30,search:true},
			{name:'Realizados',index:'estudios', width:20, sorttype:"int", align:"right",search:false,summaryType:'sum'},
			{name:'Valor',index:'valor', width:20, sorttype:"int", align:"right",search:false,summaryType:'sum'}
		], 
		autowidth: true,
		rowNum:10,
		rowList:[10,20,30],
		pager: '#grid-pagerT',
		sortname: 'nombre',
		viewrecords: true,
		grouping:true,
		groupingView : {
			groupField : ['Nombre'],
			//groupColumnShow : [false],
			groupSummary : [true],
			groupCollapse: false
		},
		gridview: true,
		autoencode: true,
		sortorder: "asc",
		caption:"Productividad T&eacute;cnicos"
	});
	
	//enable search/filter toolbar	
	$('#grid-tableT').jqGrid('filterToolbar',{
		stringResult: true, searchOnEnter: false, defaultSearch: 'cn', ignoreCase: true, enableClear: false 
	});
	
	
	$('#grid-tableT').jqGrid('navGrid',"#grid-pagerT", 
	{                
		add: false,
		edit: false,
		del: false,
		search: false,
		searchicon : 'ace-icon fa fa-search blue',
		refresh: true,
		refreshicon : 'ace-icon fa fa-refresh green'
	},
	{
		//search form
		recreateForm: true,
		afterShowSearch: function(e){
			var form = $(e[0]);
			form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
			style_search_form(form);
		},
		afterRedraw: function(){
			style_search_filters($(this));
		},
		multipleSearch: true
	}).navSeparatorAdd(pager_selectorT,{
		sepclass : "ui-separator",sepcontent: ""}
	).navButtonAdd(pager_selectorT, 
		{
			caption:"Exportar a Excel", 
			title: "Click para exportar datos a excel",
			buttonicon: "icon-actions green fa fa-save",
			onClickButton: function(){
				exportTecnicosExcel();
			},
			position:"last"
		}
	);
	
	//resize to fit page size
	$(window).on('resize.jqGrid', function () {
		$(grid_selectorT).jqGrid( 'setGridWidth', $(".col-sm-5").width() );
	})
	//resize on sidebar collapse/expand
	var parent_column = $(grid_selectorT).closest('[class*="col-"]');
	$(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
		if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
			//setTimeout is for webkit only to give time for DOM changes and then redraw!!!
			setTimeout(function() {
				$(grid_selectorT).jqGrid( 'setGridWidth', parent_column.width() );
			}, 0);
		}
	});
}

function exportTecnicosExcel()
{

	$("#grid-tableT").closest(".ui-jqgrid").find('.loading').show();
	
   $.ajax({
		url: "funciones.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "TTX",
			"cmbAgnos": $("#cmbAgnos").val(),
			"cmbMeses": $("#cmbMeses").val(),
			"cmbHospitales": $("#cmbHospitales").val(),
			"cmbPoliclinicas": $("#cmbPoliclinicas").val(),
			"cmbUlaps": $("#cmbUlaps").val(),
			"cmbModalidades": $("#cmbModalidades").val()
		}
	}).done(function(data) {
		console.log(data);
		document.location.href =(data.archivo);		
		$("#grid-tableT").closest(".ui-jqgrid").find('.loading').hide();
		//$("#grid-tableE").loadText = 'Cargando';
		//$('.loading').hide();
	});
}

function filtrarProvincia(provincia) {
		$.ajax({
			url: "controller/dashboardback.php",
			cache: false,
			dataType: "json",
			method: "POST",
			data: {
				"opcion": "MAPA",
				"tipo" : "",
				"modalidad": modalidad,
				"provincia": provincia
			}
		}).done(function(data) {
			mapa.Load({
				locations: data,
				type: 'circle',
				circle_options: {
					radius: 4
				},
				controls_on_map: false,
				map_options: {
					mapTypeId: google.maps.MapTypeId.TERRAIN,
					zoom: 7
				},
				listeners: {
					click: function(map, event) {
						//map.setOptions({scrollwheel: true});
					}
				}
			});
		});
	}
	
	function filtrarModalidad(modalidad) {
		$.ajax({
			url: "controller/mapaback.php",
			cache: false,
			dataType: "json",
			method: "POST",
			data: {
				"opcion": "MAPA",
				"tipo" : "",
				"modalidad": modalidad,
				"provincia": provincia
			}
		}).done(function(data) {
			mapa.Load({
				type: 'circle',
				locations: data,
				controls_on_map: false,
				map_options: {
					mapTypeId: google.maps.MapTypeId.TERRAIN,
					zoom: 8
				},
				listeners: {
					click: function(map, event) {
						//map.setOptions({scrollwheel: true});
					}
				}
			});
		});
	}


