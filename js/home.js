var tecnicos, radiologos, teleradiologos, dias, turnos; 
var estudios, informados, esperadosi, esperadosr, mapa;
var modalidad="*";
var provincia="*";
	
jQuery(function($) {
	var lastSel, lastSel2;
	
	$.jgrid.defaults.styleUI = 'Bootstrap';
	
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
	
	cargarDatos('CSS');
	abrirUnidad('CSS');
	tablaRadiologos('CSS');
	tablaTecnicos('CSS');
	actualizarTiempos('CSS');
	
	$('#cmbProvincias').on('change', function (e) {
		provincia = this.value;
		filtrarProvincia(this.value);
	});
	
	$('#cmbModalidades').on('change', function (e) {
		modalidad = this.value;
		filtrarModalidad(this.value);
	});
	
	$.ajax({
		url: "controller/homeback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "MAPA"
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
				zoom: 8
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
		$('.ui-jqdialog').remove();
	});
	
	//Resize
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

function cargarDatos(unidad) {
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
		url: "controller/homeback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "DATOS",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"unidad":unidad
		}
	}).done(function(data) {
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
		xvalor = buscarValor('Ultrasonido', xano, data);
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
		xvalor = buscarValor('Ultrasonido', xano, data);
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
		xvalor = buscarValor('Ultrasonido', xano, data);
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

function abrirUnidad(point) {
	$.ajax({
		url: "controller/homeback.php",
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
		$("#txtImagen").html("<img src='images/sedes/"+data.rows[0].codigo+".jpg' width=150px />");
		$("#txtUnidad").html("<strong>" + data.rows[0].unidad + "</strong>");
		$("#txtTecnicos").html("T\u00E9cnicos: <strong>" + data.rows[0].tecnicos + "</strong>");
		$("#txtRadiologos").html("Radi\u00F3logos: <strong>" + data.rows[0].radiologos + "</strong>");
		$("#txtTeleRadiologos").html("Radi\u00F3logos (Remotos): <strong>" + data.rows[0].teleradiologos + "</strong>");
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
	});
	$.ajax({
		url: "controller/homeback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "DATOSUNIDADSOP",
			"unidad": point,
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val()
		}
	}).done(function(data) {
		console.log(data);
		$("#txtUndPreventivas").html("Mttos. Preventivos: <strong>" + data.rows[0].preventivas + "</strong>");
		$("#txtUndPreventivasFin").html("Realizados: <strong>" + data.rows[0].preventivasfin + "</strong>");
		$("#txtUndPreventivasPen").html("Pendientes: <strong>" + data.rows[0].preventivaspen + "</strong>");
		$("#txtUndCorrectivas").html("Incidentes: <strong>" + data.rows[0].correctivas + "</strong>");
		$("#txtUndCorrectivasFin").html("Finalizados: <strong>" + data.rows[0].correctivasfin + "</strong>");
		$("#txtUndCorrectivasPen").html("Abiertos: <strong>" + data.rows[0].correctivaspen + "</strong>");
		
		datosModalidad = data.rows[0].datos;
		inactivos = 0; activos=0;  descartados=0;
		$.each(datosModalidad, function(index, value){
			if (value.estado=='ACTIVO')
				activos++;
			else if (value.estado=='INACTIVO')
					inactivos++;
				else
					descartados++;
					
		});
		disponibilidadUnidad(activos,inactivos,descartados);
		urlOrdenes='controller/soporteback.php?opcion=DETALLES2&id=' + data.rows[0].codigo;
		$("#grid-unidades-win").jqGrid('setGridParam', { url: urlOrdenes });
		$("#grid-unidades-win").jqGrid('clearGridData');
		$("#grid-unidades-win").trigger('reloadGrid');
		var newWidth = $("#grid-unidades-win").closest(".ui-jqgrid").parent().width();
		$("#grid-unidades-win").jqGrid("setGridWidth", newWidth, true);
		urlOrdenesPen='controller/soporteback.php?opcion=DETALLES4&id=' + data.rows[0].codigo;
		$("#grid-unidades-win-pen").jqGrid('setGridParam', { url: urlOrdenesPen });
		$("#grid-unidades-win-pen").jqGrid('clearGridData');
		$("#grid-unidades-win-pen").trigger('reloadGrid');
		var newWidthPen = $("#grid-unidades-win-pen").closest(".ui-jqgrid").parent().width();
		$("#grid-unidades-win-pen").jqGrid("setGridWidth", newWidthPen, true);
		//prodesperada();
		//$("#txtRx").html(data.rx);
		//$("#txtFl").html(formatNumber.new(data.rows[2].valor));
		//$("#txtEdPorc").html((data.rows[4].valor * 100 / data.rows[0].valor).toFixed(1) + " %");
	});
}

function actualizarTiempos(unidad) {
	$.ajax({
		url: "controller/homeback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "TIEMPOSESPERA",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"unidad":unidad
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
			credits: {
				enabled: false
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
			credits: {
				enabled: false
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
		cargarDatos('CSS');
		abrirUnidad('CSS');
		tablaRadiologos('CSS');
		tablaTecnicos('CSS');
		actualizarTiempos('CSS');
		url:'controller/homeback.php?opcion=UNIDADES&agno=&mes=';
		agno=$("#filtro-desde").val();
		mes=$("#filtro-hasta").val();
		urlOrdenes = 'controller/homeback.php?opcion=UNIDADES&agno='+agno+'&mes='+mes;
		$("#grid-unidades").jqGrid('setGridParam', { url: urlOrdenes });
		$("#grid-unidades").jqGrid('clearGridData');
		$("#grid-unidades").trigger('reloadGrid');
		
		urlOrdenes = 'controller/soporteback.php?opcion=UNIDADES&agno='+agno+'&mes='+mes;
		$("#grid-unidadessop").jqGrid('setGridParam', { url: urlOrdenes });
		$("#grid-unidadessop").jqGrid('clearGridData');
		$("#grid-unidadessop").trigger('reloadGrid');
		
	}
	
	function mostrarHospitales() {
		$.ajax({
			url: "controller/homeback.php",
			cache: false,
			dataType: "json",
			method: "POST",
			data: {
				"opcion": "MAPA",
				"tipo" : "Hospital",
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
	
	function mostrarPoliclinicas() {
		$.ajax({
			url: "controller/homeback.php",
			cache: false,
			dataType: "json",
			method: "POST",
			data: {
				"opcion": "MAPA",
				"tipo" : "Poli",
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
	
	function mostrarUlaps() {
		$.ajax({
			url: "controller/homeback.php",
			cache: false,
			dataType: "json",
			method: "POST",
			data: {
				"opcion": "MAPA",
				"tipo" : "Ulap",
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
	
	function mostrarTodos() {
		$.ajax({
			url: "controller/homeback.php",
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
	
	function filtrarProvincia(provincia) {
		$.ajax({
			url: "controller/homeback.php",
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
	
	
function disponibilidadUnidad(activos,inactivos,descartados) {
	var options = {
			chart: {
				renderTo: 'disponibilidadUnidad',
				plotBackgroundColor: null,
				plotBorderWidth: 0,
				plotShadow: false,
				height: 220
			},
			title: {
				text: null
			},
			subtitle: {
				text: 'Disponibilidad de Equipos'
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					dataLabels: {
						enabled: true,
						distance: -5,
						style: {
							fontWeight: 'bold',
							color: 'black'
						}
					},
					//startAngle: -90,
					//endAngle: 90,
					center: ['50%', '50%']
				}
				
			},
			exporting: { 
				enabled: false 
			},
			credits: {
				enabled: false
			},
			series: [{
				type: 'pie',
				name: 'Porc.',
				//innerSize: '50%',
				data: [
					['Activos', activos],
					['Inactivos', inactivos]//,['Descartados', descartados]
				]
			}]
	};
		
	var chart = new Highcharts.Chart(options);
	
}


function tablaPendientes(unidad) {
	var agno = $("#filtro-desde").val();
	var mes = $("#filtro-hasta").val();
	var urlPendientes = 'controller/homeback.php?opcion=PENDIENTES&id=' + unidad +'&agno='+agno+'&mes='+mes;
	// Grid de incidentes pendientes de la unidad
	var grid_selector_win_pen 	= "#grid-unidades-win-pen";
	
	//Resize
	$(window).on("resize", function () {
		var newWidth = $("#grid-unidades-win-pen").closest(".ui-jqgrid").parent().width();
		$("#grid-unidades-win-pen").jqGrid("setGridWidth", newWidth, true);
		var newWidth = $("#grid-unidades-win").closest(".ui-jqgrid").parent().width();
		$("#grid-unidades-win").jqGrid("setGridWidth", newWidth, true);
		var newWidth = $("#grid-tableT").closest(".ui-jqgrid").parent().width();
		$("#grid-tableT").jqGrid("setGridWidth", newWidth, true);
		var newWidth = $("#grid-tableR").closest(".ui-jqgrid").parent().width();
		$("#grid-tableR").jqGrid("setGridWidth", newWidth, true);
		
	});
	
	jQuery(grid_selector_win_pen).jqGrid({
		url:urlPendientes,
		datatype: "json",
		colNames: ['Nro','Titulo','Descripción','Estado','Asignado A','Creado el'],
		colModel: [
			{ name: 'id', width: 30, search: false, sortable: false },
			{ name: 'titulo', width: 160, search: false, sortable: false,
				cellattr: function (rowId, cellValue, rowObject) {
					return ' title="' + $(rowObject)[2] + '"';
					console.log($(rowObject)[2]);
				}
			},
			{ name: 'descripcion', index:'descripcion', width:400, editable: true, hidden:true},
			{ name: 'estado', width: 60, search: false, align: 'center', sortable: false },
			{ name: 'asignadoa', width: 110, search: false, sortable: false},
			{ name: 'fechacreacion', index: 'correctivos', width: 60, search: false, sortable: false, 
				formatter:'date'}
		],	
		rowNum:10,
		rowList:[10,20,30],
		pager: '#pager-unidades-win-pen',
		ignoreCase:true,
		viewrecords: true,
		rowNum:100,
		altRows: true,
		gridview:true,
		width: '100%',
		height: '100%',
		caption: 'Incidentes Abiertos',
		ondblClickRow: function (rowid, iRow,iCol) {
			var row_id = $(grid_selector_win_pen).getGridParam('selrow');
			window.open("ordendetalle.php?num="+row_id, "_blank");
			//jQuery("#mygrid").editCell(iRow, iCol, true);
		}
	});
}
	
	
	//Grid de incidentes preventivos y correctivos por equipo dentro ventana de unidades
	
	var grid_selector_win 	= "#grid-unidades-win";
	
	jQuery(grid_selector_win).jqGrid({
		subGrid : true,
		subGridOptions : {
			plusicon : "glyphicon glyphicon-eye-open center green",
			minusicon  : "glyphicon glyphicon-eye-close center green",
			openicon : "ace-icon fa fa-chevron-right center green"
		},
		subGridRowExpanded: function (subgrid_id, rowId) {
			var subgrid_table_id, pager_id;
			subgrid_table_id = subgrid_id+"_t";
			pager_id = "p_"+subgrid_table_id;
			$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
			jQuery("#"+subgrid_table_id).jqGrid({
				url:'controller/soporteback.php?opcion=DETALLES3&id=' + rowId, 
				datatype: "json",
				colNames:['#','Fecha','Estado','Tipo','Titulo','Descripcion'],
				colModel:[
					{name:'id',index:'id',width:40},
					{name:'fechacreacion',index:'fechacreacion',width:50,formatter: "date"},
					{name:'estado',index:'estado',width:50},
					{name:'tipo',index:'tipo',width:50},
					{name:'titulo',index:'titulo',width:120},
					{name:'descripcion',index:'descripcion',width:150, classes: "textInDiv",
						formatter: function (v) {
							return '<div>' + $.jgrid.htmlEncode(v) + '</div>';
						}}
								
				], 
				width: '100%',
				autoWidth: true,
				viewrecords: true,
				rowNum:1000
			});
			jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{edit:false,add:false,del:false});
			var newWidth = $("#"+subgrid_table_id).closest(".ui-jqgrid").parent().width();
			$("#"+subgrid_table_id).jqGrid("setGridWidth", newWidth, true);
		},
		url:'controller/soporteback.php?opcion=DETALLES2&id=',
		datatype: "json",
		colNames: ['Modalidad','Equipo','Estado','Preventivos','Correctivos'],
		colModel: [
			{ name: 'modalidad', width: 70, search: false, sortable: false },
			{ name: 'equipo', width: 180, search: false, align: 'center', sortable: false },
			{ name: 'estado', width: 60, search: false, align: 'center', sortable: false },
			{ name: 'preventivos', index: 'preventivos', width: 60, search: false, sortable: false, 
				formatter:'number', align: 'right',
				formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 0}},
			{ name: 'correctivos', index: 'correctivos', width: 60, search: false, sortable: false, 
				formatter:'number', align: 'right',
				formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 0}}
		],	
		viewrecords: true,
		rowNum:100,
		altRows: true,
		gridview:true,
		width: '100%',
		height: '100%',
		caption: 'Incidentes por Equipo',
		ondblClickRow: function (rowid, iRow,iCol) {
			var row_id = $(grid_selector_win).getGridParam('selrow');
			window.open("ordendetalle.php?num="+row_id, "_blank");
			//jQuery("#mygrid").editCell(iRow, iCol, true);
		}
	});
	
function tablaRadiologos(unidad) {
	var agno = $("#filtro-desde").val();
	var mes = $("#filtro-hasta").val();
	var urlRadiologos = 'controller/homeback.php?opcion=RADIOLOGOS&id=' + unidad +'&agno='+agno+'&mes='+mes;
	
	jQuery("#grid-tableR").jqGrid({
		url:urlRadiologos,
		datatype: "json",
		colNames:['Unidad','Nombre','Dia','Horario','Mod.', 'Informados', 'Dias', 'Puntos'],
		colModel:[
			{name:'Unidad',index:'unidad',width:90,search:true},
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
		sortname: 'unidad',
		viewrecords: true,
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
	
	
	$(window).on("resize", function () {
		var newWidth = $("#grid-tableR").closest(".ui-jqgrid").parent().width();
		$("#grid-tableR").jqGrid("setGridWidth", newWidth, true);	
	});
	
	
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
	
function tablaTecnicos(unidad) {
	var urlTecnicos = 'controller/homeback.php?opcion=TECNICOS&id=' + unidad +'&agno='+$("#filtro-desde").val()+'&mes='+$("#filtro-hasta").val();
	
	var grid_selectorT = "#grid-tableT";
	var pager_selectorT = "#grid-pagerT";
	
	jQuery("#grid-tableT").jqGrid({
		url: urlTecnicos,
		datatype: "json",
		colNames:['Unidad','Nombre','Dia','Horario','Mod.', 'Realizados', 'Puntos'],
		colModel:[
			{name:'Unidad',index:'unidad',width:90,search:true},
			{name:'Nombre',index:'nombre',width:40,search:true},
			{name:'Dia',index:'tipodia',width:30,search:true},
			{name:'Horario',index:'tipohora',width:30,search:true},
			{name:'Modalidad',index:'modalidad', width:30,search:true},
			{name:'Realizados',index:'estudios', width:20, sorttype:"int", align:"right",search:false},
			{name:'Valor',index:'valor', width:20, sorttype:"int", align:"right",search:false}
		], 
		autowidth: true,
		rowNum:10,
		rowList:[10,20,30],
		pager: '#grid-pagerT',
		sortname: 'unidad',
		viewrecords: true,
		
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
	
	$(window).on("resize", function () {
		var newWidth = $("#grid-tableT").closest(".ui-jqgrid").parent().width();
		$("#grid-tableT").jqGrid("setGridWidth", newWidth, true);
	});
	/*/resize on sidebar collapse/expand
	var parent_column = $(grid_selectorT).closest('[class*="col-"]');
	$(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
		if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
			//setTimeout is for webkit only to give time for DOM changes and then redraw!!!
			setTimeout(function() {
				$(grid_selectorT).jqGrid( 'setGridWidth', parent_column.width() );
			}, 0);
		}
	});*/
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


	

	
	



