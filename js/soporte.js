var tecnicos, radiologos, teleradiologos, dias, turnos; 
var estudios, informados, esperadosi, esperadosr, mapa;
var modalidad="*";
var provincia="*";
var map;

$(".tab-years").click(function(){
	var id 		= $(this).attr('id');
	var tabyear = id.split('-');
	var year 	= tabyear[1];
	
	$(".year-one, #pane-2018").addClass('active');
	if(year == '2018'){
		$(".year-one, #pane-2018").addClass('active');
	}
	if(year == '2017'){
		$(".year-two").addClass('press-2017');		
		var active = $(".year-two").hasClass('press-2017');
		if(active == true){				
			var activetab = $(".year-two").hasClass('press-2017-oc');
			if(activetab == true){				
				$(".year-two").addClass('active-none-tab');
				$("#pane-2017").addClass('active-none');
				$(".year-two").removeClass('press-2017-oc');
				$("#pane-2017, .year-two").removeClass('active-all');
			}else{
				$(".year-two").removeClass('active-none-tab');
				$("#pane-2017").removeClass('active-none');
				$(".year-two").addClass('press-2017-oc');
				$("#pane-2017, .year-two").addClass('active-all');
			}
		}
	}
	if(year == '2016'){
		$(".year-three").addClass('press-2016');		
		var active = $(".year-three").hasClass('press-2016');
		if(active == true){				
			var activetab = $(".year-three").hasClass('press-2016-oc');
			if(activetab == true){				
				$(".year-three").addClass('active-none-tab');
				$("#pane-2016").addClass('active-none');
				$(".year-three").removeClass('press-2016-oc');
				$("#pane-2016, .year-three").removeClass('active-all');
			}else{
				$(".year-three").removeClass('active-none-tab');
				$("#pane-2016").removeClass('active-none');
				$(".year-three").addClass('press-2016-oc');
				$("#pane-2016, .year-three").addClass('active-all');
			}
		}
	}
});

$(".tab-years-2").click(function(){
	var id 		= $(this).attr('id');
	var tabyear = id.split('-');
	var year 	= tabyear[1];
	
	$(".year-one, #pane-2018-2").addClass('active');
	if(year == '2018'){
		$(".year-one-2, #pane-2018").addClass('active');
	}
	if(year == '2017'){
		$(".year-two-2").addClass('press-2017');		
		var active = $(".year-two-2").hasClass('press-2017');
		if(active == true){				
			var activetab = $(".year-two-2").hasClass('press-2017-oc');
			if(activetab == true){				
				$(".year-two-2").addClass('active-none-tab');
				$("#pane-2017-2").addClass('active-none');
				$(".year-two-2").removeClass('press-2017-oc');
				$("#pane-2017-2, .year-two-2").removeClass('active-all');
			}else{
				$(".year-two-2").removeClass('active-none-tab');
				$("#pane-2017-2").removeClass('active-none');
				$(".year-two-2").addClass('press-2017-oc');
				$("#pane-2017-2, .year-two-2").addClass('active-all');
			}
		}
	}
	if(year == '2016'){
		$(".year-three-2").addClass('press-2016');		
		var active = $(".year-three-2").hasClass('press-2016');
		if(active == true){				
			var activetab = $(".year-three-2").hasClass('press-2016-oc');
			if(activetab == true){				
				$(".year-three-2").addClass('active-none-tab');
				$("#pane-2016-2").addClass('active-none');
				$(".year-three-2").removeClass('press-2016-oc');
				$("#pane-2016-2, .year-three-2").removeClass('active-all');
			}else{
				$(".year-three-2").removeClass('active-none-tab');
				$("#pane-2016-2").removeClass('active-none');
				$(".year-three-2").addClass('press-2016-oc');
				$("#pane-2016-2, .year-three-2").addClass('active-all');
			}
		}
	}
});

function mapa(unidad) {	
	var styledMapType = new google.maps.StyledMapType(
            [
			  {
				"elementType": "geometry",
				"stylers": [
				  {
					"color": "#f5f5f5"
				  }
				]
			  },
			  {
				"elementType": "labels.icon",
				"stylers": [
				  {
					"visibility": "off"
				  }
				]
			  },
			  {
				"elementType": "labels.text.fill",
				"stylers": [
				  {
					"color": "#616161"
				  }
				]
			  },
			  {
				"elementType": "labels.text.stroke",
				"stylers": [
				  {
					"color": "#f5f5f5"
				  }
				]
			  },
			  {
				"featureType": "administrative.land_parcel",
				"elementType": "labels.text.fill",
				"stylers": [
				  {
					"color": "#bdbdbd"
				  }
				]
			  },
			  {
				"featureType": "poi",
				"elementType": "geometry",
				"stylers": [
				  {
					"color": "#eeeeee"
				  }
				]
			  },
			  {
				"featureType": "poi",
				"elementType": "labels.text.fill",
				"stylers": [
				  {
					"color": "#757575"
				  }
				]
			  },
			  {
				"featureType": "poi.park",
				"elementType": "geometry",
				"stylers": [
				  {
					"color": "#e5e5e5"
				  }
				]
			  },
			  {
				"featureType": "poi.park",
				"elementType": "labels.text.fill",
				"stylers": [
				  {
					"color": "#9e9e9e"
				  }
				]
			  },
			  {
				"featureType": "road",
				"elementType": "geometry",
				"stylers": [
				  {
					"color": "#ffffff"
				  }
				]
			  },
			  {
				"featureType": "road.arterial",
				"elementType": "labels.text.fill",
				"stylers": [
				  {
					"color": "#757575"
				  }
				]
			  },
			  {
				"featureType": "road.highway",
				"elementType": "geometry",
				"stylers": [
				  {
					"color": "#dadada"
				  }
				]
			  },
			  {
				"featureType": "road.highway",
				"elementType": "labels.text.fill",
				"stylers": [
				  {
					"color": "#616161"
				  }
				]
			  },
			  {
				"featureType": "road.local",
				"elementType": "labels.text.fill",
				"stylers": [
				  {
					"color": "#9e9e9e"
				  }
				]
			  },
			  {
				"featureType": "transit.line",
				"elementType": "geometry",
				"stylers": [
				  {
					"color": "#e5e5e5"
				  }
				]
			  },
			  {
				"featureType": "transit.station",
				"elementType": "geometry",
				"stylers": [
				  {
					"color": "#eeeeee"
				  }
				]
			  },
			  {
				"featureType": "water",
				"elementType": "geometry",
				"stylers": [
				  {
					"color": "#c9c9c9"
				  }
				]
			  },
			  {
				"featureType": "water",
				"elementType": "labels.text.fill",
				"stylers": [
				  {
					"color": "#9e9e9e"
				  }
				]
			  }
			],
            {name: 'Styled Map'});

	$.ajax({
		url: "controller/soporteback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "MAPA",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"unidad": unidad,
			"provincia":$("#cmbProvincias").val(),
			"modalidad":$("#cmbModalidades").val()
		}
	}).done(function(data) {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 8.50826349640597, lng: -81.30147705078129},
          zoom: 8
        });
		
		$.map(data, function (datos) {
			var marker = new google.maps.Marker({
				position: {lat: parseFloat(datos.latitud), lng: parseFloat(datos.longitud)},
				icon: datos.icon,
				title: datos.title,
				label: {
					text: datos.label,
					fontSize: '1px',
					color: '#1f4380'
				},
				map: map
			});
			
			marker.addListener('click', function() {
				map.setZoom(8);
				map.setCenter(marker.getPosition());
				var xUnidad = marker.label.text;
				cargarDatos(xUnidad);
				cargarDatos2(xUnidad);
				//abrirFichaSop(xUnidad);
				//actualizarTablas(xUnidad);
				correctivos(xUnidad);
				preventivos(xUnidad);
				$("h3#tituloUnidad").html(marker.title.text);
				console.log(marker.title.text);
			});
		});

        //Associate the styled map with the MapTypeId and set it to display.
        map.mapTypes.set('styled_map', styledMapType);
        map.setMapTypeId('styled_map');
      
	});
}
	
jQuery(function($) {
	var lastSel, lastSel2;
	
	$.jgrid.defaults.styleUI = 'Bootstrap';
	$("li#mnuSoporte").addClass("active");
	console.log(proyecto);
	if (nivel>2 && proyecto!=1) {
		$("#yearsCSS").css('display', 'none');
		$("#mapaCSS").css('display', 'none');
	}
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
	
	cargarDatos(pUnidad);
	cargarDatos2(pUnidad);
	//mapa(pUnidad);
	//abrirFichaSop(pUnidad);
	/*tablaRadiologos(pUnidad);
	tablaTIncidentes(pUnidad);
	tablaIncidentes(pUnidad);
	tablaHistorial(pUnidad);*/
	correctivos(pUnidad);
	preventivos(pUnidad);
	correctivos2(pUnidad);
	correctivos3(pUnidad);
	correctivos4(pUnidad);
	
	function filtrar(xUnidad){
		cargarDatos(xUnidad);
		cargarDatos2(xUnidad);
		mapa(xUnidad);
		//abrirFichaSop(xUnidad);
		//actualizarTablas(xUnidad);
		correctivos(xUnidad);
		preventivos(xUnidad);
		correctivos2(xUnidad);
		correctivos3(xUnidad);
		correctivos4(xUnidad);
	}
	
	$('#cmbProvincias').on('change', function (e) {
		filtrar($('#cmbUnidades').val());
	});
	
	$('#cmbUnidades').on('change', function (e) {
		console.log(this);
		filtrar(this.value);
	});
	
	$('#cmbModalidades').on('change', function (e) {
		filtrar($('#cmbUnidades').val());
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
		url: "controller/soporteback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "DATOS",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"provincia":provincia,
			"unidad":unidad,
			"modalidad":modalidad
		}
	}).done(function(data) {
		total = 100;
		var i=0;
		var xano = data.rows[i].ano;
		while (i<data.rows.length && data.rows[i].ano==xano) {
			i++;
		}	
		var xvalor = buscarValor('Total', xano, data);
		$("#txtTotal").html(formatNumber.new(xvalor));
		$("#txtTotalPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtTotalPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		$("#txtAgno").html(xano);
		var xvalor = buscarValor('Angiograma', xano, data);
		$("#txtAg").html(formatNumber.new(xvalor));
		$("#txtAgPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtAgPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Fluroscopia', xano, data);
		$("#txtFl").html(formatNumber.new(xvalor));
		$("#txtFlPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtFlPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Mamografía', xano, data);
		$("#txtMg").html(formatNumber.new(xvalor));
		$("#txtMgPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtMgPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Dental', xano, data);
		$("#txtEd").html(formatNumber.new(xvalor));
		$("#txtEdPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtEdPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Rayos X', xano, data);
		$("#txtRx").html(formatNumber.new(xvalor));
		$("#txtRxPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtRxPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Resonancia Magnetica', xano, data);
		$("#txtRm").html(formatNumber.new(xvalor));
		$("#txtRmPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtRmPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Tomografía Computada', xano, data);
		$("#txtTc").html(formatNumber.new(xvalor));
		$("#txtTcPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtTcPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Ultra Sonido', xano, data);
		$("#txtUs").html(formatNumber.new(xvalor));
		$("#txtUsPorc").html((xvalor * 100 / total).toFixed(1));
		$("#txtUsPorc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		
		xano = data.rows[i].ano;
		while (i<data.rows.length && data.rows[i].ano==xano) {
			i++;
		}	
		var xvalor = buscarValor('Total', xano, data);
		$("#txtAgno2").html(xano);
		$("#txtTotal2").html(formatNumber.new(xvalor));
		$("#txtTotal2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtTotal2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		var xvalor = buscarValor('Angiograma', xano, data);
		$("#txtAg2").html(formatNumber.new(xvalor));
		$("#txtAg2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtAg2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Fluroscopia', xano, data);
		$("#txtFl2").html(formatNumber.new(xvalor));
		$("#txtFl2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtFl2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Mamografía', xano, data);
		$("#txtMg2").html(formatNumber.new(xvalor));
		$("#txtMg2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtMg2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Dental', xano, data);
		$("#txtEd2").html(formatNumber.new(xvalor));
		$("#txtEd2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtEd2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Rayos X', xano, data);
		$("#txtRx2").html(formatNumber.new(xvalor));
		$("#txtRx2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtRx2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Resonancia Magnetica', xano, data);
		$("#txtRm2").html(formatNumber.new(xvalor));
		$("#txtRm2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtRm2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Tomografía Computada', xano, data);
		$("#txtTc2").html(formatNumber.new(xvalor));
		$("#txtTc2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtTc2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Ultra Sonido', xano, data);
		$("#txtUs2").html(formatNumber.new(xvalor));
		$("#txtUs2Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtUs2Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		
		xano = data.rows[i].ano;
		while (i<data.rows.length && data.rows[i].ano==xano) {
			i++;
		}	
		var xvalor = buscarValor('Total', xano, data);
		$("#txtAgno3").html(xano);
		$("#txtTotal3").html(formatNumber.new(xvalor));
		$("#txtTotal3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtTotal3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		var xvalor = buscarValor('Angiograma', xano, data);
		$("#txtAg3").html(formatNumber.new(xvalor));
		$("#txtAg3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtAg3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Fluroscopia', xano, data);
		$("#txtFl3").html(formatNumber.new(xvalor));
		$("#txtFl3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtFl3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Mamografía', xano, data);
		$("#txtMg3").html(formatNumber.new(xvalor));
		$("#txtMg3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtMg3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Dental', xano, data);
		$("#txtEd3").html(formatNumber.new(xvalor));
		$("#txtEd3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtEd3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Rayos X', xano, data);
		$("#txtRx3").html(formatNumber.new(xvalor));
		$("#txtRx3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtRx3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Resonancia Magnetica', xano, data);
		$("#txtRm3").html(formatNumber.new(xvalor));
		$("#txtRm3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtRm3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Tomografía Computada', xano, data);
		$("#txtTc3").html(formatNumber.new(xvalor));
		$("#txtTc3Porc").html((xvalor * 100 / total).toFixed(1));
		$("#txtTc3Porc2").attr("data-percent",(xvalor * 100 / total).toFixed(1));
		xvalor = buscarValor('Ultra Sonido', xano, data);
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

function cargarDatos2(unidad) {
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
		url: "controller/soporteback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "DATOS2",
			"desde":$("#filtro-desde").val(),
			"hasta":$("#filtro-hasta").val()
		}
	}).done(function(data) {
		console.log(data);
		fila = 0
		$("#totalOrdenes").html(formatNumber.new(data.rows[fila].ordenes));
		$("#totalOrdenesPorc").html((data.rows[fila].ordenes * 100 / data.rows[fila].ordenes) + "%");
		$("#totalOrdenesPorcg").attr("data-percent",(data.rows[fila].ordenes * 100 / data.rows[fila].ordenes).toFixed(1));
		$("#txtFinalizadas").html(formatNumber.new(data.rows[fila].finalizadas));
		$("#txtFinalizadasPorc").html((data.rows[fila].finalizadas * 100 / data.rows[fila].ordenes).toFixed(1));
		$("#txtFinalizadasPorcg").attr("data-percent",(data.rows[fila].finalizadas * 100 / data.rows[fila].ordenes).toFixed(1));
		$("#txtPendientes").html(formatNumber.new(data.rows[fila].pendientes));
		$("#txtPendientesPorc").html((data.rows[fila].pendientes * 100 / data.rows[fila].ordenes).toFixed(1));
		$("#txtPendientesPorcg").attr("data-percent",(data.rows[fila].pendientes * 100 / data.rows[fila].ordenes).toFixed(1));
		$("#txtPreventivas").html(formatNumber.new(data.rows[fila].preventivas));
		$("#txtPreventivasPorc").html((data.rows[fila].preventivas * 100 / data.rows[fila].preventivas) + "%");
		$("#txtPreventivasPorcg").attr("data-percent",(data.rows[fila].preventivas * 100 / data.rows[fila].preventivas).toFixed(1));
		$("#txtPreventivasFin").html(formatNumber.new(data.rows[fila].preventivasfin));
		$("#txtPreventivasFinPorc").html((data.rows[fila].preventivasfin * 100 / data.rows[fila].preventivas).toFixed(1));
		$("#txtPreventivasFinPorcg").attr("data-percent",(data.rows[fila].preventivasfin * 100 / data.rows[fila].preventivas).toFixed(1));
		$("#txtPreventivasPen").html(formatNumber.new(data.rows[fila].preventivaspen));
		$("#txtPreventivasPenPorc").html((data.rows[fila].preventivaspen * 100 / data.rows[fila].preventivas).toFixed(1));
		$("#txtPreventivasPenPorcg").attr("data-percent",(data.rows[fila].preventivaspen * 100 / data.rows[fila].preventivas).toFixed(1));
		$("#txtCorrectivas").html(formatNumber.new(data.rows[fila].correctivas));
		$("#txtCorrectivasPorc").html((data.rows[fila].correctivas * 100 / data.rows[fila].correctivas) + "%");
		$("#txtCorrectivasPorcg").attr("data-percent",(data.rows[fila].correctivas * 100 / data.rows[fila].correctivas).toFixed(1));
		$("#txtCorrectivasFin").html(formatNumber.new(data.rows[fila].correctivasfin));
		$("#txtCorrectivasFinPorc").html((data.rows[fila].correctivasfin * 100 / data.rows[fila].correctivas).toFixed(1));
		$("#txtCorrectivasFinPorcg").attr("data-percent",(data.rows[fila].correctivasfin * 100 / data.rows[fila].correctivas).toFixed(1));
		$("#txtCorrectivasPen").html(formatNumber.new(data.rows[fila].correctivaspen));
		$("#txtCorrectivasPenPorc").html((data.rows[fila].correctivaspen * 100 / data.rows[fila].correctivas).toFixed(1));
		$("#txtCorrectivasPenPorcg").attr("data-percent",(data.rows[fila].correctivaspen * 100 / data.rows[fila].correctivas).toFixed(1));
		
		
		fila = 1
		$("#totalOrdenes2").html(formatNumber.new(data.rows[fila].ordenes));
		$("#totalOrdenesPorc2").html((data.rows[fila].ordenes * 100 / data.rows[fila].ordenes) + "%");
		$("#totalOrdenesPorcg2").attr("data-percent",(data.rows[fila].ordenes * 100 / data.rows[fila].ordenes).toFixed(1));
		$("#txtFinalizadas2").html(formatNumber.new(data.rows[fila].finalizadas));
		$("#txtFinalizadasPorc2").html((data.rows[fila].finalizadas * 100 / data.rows[fila].ordenes).toFixed(1));
		$("#txtFinalizadasPorcg2").attr("data-percent",(data.rows[fila].finalizadas * 100 / data.rows[fila].ordenes).toFixed(1));
		$("#txtPendientes2").html(formatNumber.new(data.rows[fila].pendientes));
		$("#txtPendientesPorc2").html((data.rows[fila].pendientes * 100 / data.rows[fila].ordenes).toFixed(1));
		$("#txtPendientesPorcg2").attr("data-percent",(data.rows[fila].pendientes * 100 / data.rows[fila].ordenes).toFixed(1));
		$("#txtPreventivas2").html(formatNumber.new(data.rows[fila].preventivas));
		$("#txtPreventivasPorc2").html((data.rows[fila].preventivas * 100 / data.rows[fila].preventivas) + "%");
		$("#txtPreventivasPorcg2").attr("data-percent",(data.rows[fila].preventivas * 100 / data.rows[fila].preventivas).toFixed(1));
		$("#txtPreventivasFin2").html(formatNumber.new(data.rows[fila].preventivasfin));
		$("#txtPreventivasFinPorc2").html((data.rows[fila].preventivasfin * 100 / data.rows[fila].preventivas).toFixed(1));
		$("#txtPreventivasFinPorcg2").attr("data-percent",(data.rows[fila].preventivasfin * 100 / data.rows[fila].preventivas).toFixed(1));
		$("#txtPreventivasPen2").html(formatNumber.new(data.rows[fila].preventivaspen));
		$("#txtPreventivasPenPorc2").html((data.rows[fila].preventivaspen * 100 / data.rows[fila].preventivas).toFixed(1));
		$("#txtPreventivasPenPorcg2").attr("data-percent",(data.rows[fila].preventivaspen * 100 / data.rows[fila].preventivas).toFixed(1));
		$("#txtCorrectivas2").html(formatNumber.new(data.rows[fila].correctivas));
		$("#txtCorrectivasPorc2").html((data.rows[fila].correctivas * 100 / data.rows[fila].correctivas) + "%");
		$("#txtCorrectivasPorcg2").attr("data-percent",(data.rows[fila].correctivas * 100 / data.rows[fila].correctivas).toFixed(1));
		$("#txtCorrectivasFin2").html(formatNumber.new(data.rows[fila].correctivasfin));
		$("#txtCorrectivasFinPorc2").html((data.rows[fila].correctivasfin * 100 / data.rows[fila].correctivas).toFixed(1));
		$("#txtCorrectivasFinPorcg2").attr("data-percent",(data.rows[fila].correctivasfin * 100 / data.rows[fila].correctivas).toFixed(1));
		$("#txtCorrectivasPen2").html(formatNumber.new(data.rows[fila].correctivaspen));
		$("#txtCorrectivasPenPorc2").html((data.rows[fila].correctivaspen * 100 / data.rows[fila].correctivas).toFixed(1));
		$("#txtCorrectivasPenPorcg2").attr("data-percent",(data.rows[fila].correctivaspen * 100 / data.rows[fila].correctivas).toFixed(1));
		
		
		fila = 2
		$("#totalOrdenes3").html(formatNumber.new(data.rows[fila].ordenes));
		$("#totalOrdenesPorc3").html((data.rows[fila].ordenes * 100 / data.rows[fila].ordenes) + "%");
		$("#totalOrdenesPorcg3").attr("data-percent",(data.rows[fila].ordenes * 100 / data.rows[fila].ordenes).toFixed(1));
		$("#txtFinalizadas3").html(formatNumber.new(data.rows[fila].finalizadas));
		$("#txtFinalizadasPorc3").html((data.rows[fila].finalizadas * 100 / data.rows[fila].ordenes).toFixed(1));
		$("#txtFinalizadasPorcg3").attr("data-percent",(data.rows[fila].finalizadas * 100 / data.rows[fila].ordenes).toFixed(1));
		$("#txtPendientes3").html(formatNumber.new(data.rows[fila].pendientes));
		$("#txtPendientesPorc3").html((data.rows[fila].pendientes * 100 / data.rows[fila].ordenes).toFixed(1));
		$("#txtPendientesPorcg3").attr("data-percent",(data.rows[fila].pendientes * 100 / data.rows[fila].ordenes).toFixed(1));
		$("#txtPreventivas3").html(formatNumber.new(data.rows[fila].preventivas));
		$("#txtPreventivasPorc3").html((data.rows[fila].preventivas * 100 / data.rows[fila].preventivas) + "%");
		$("#txtPreventivasPorcg3").attr("data-percent",(data.rows[fila].preventivas * 100 / data.rows[fila].preventivas).toFixed(1));
		$("#txtPreventivasFin3").html(formatNumber.new(data.rows[fila].preventivasfin));
		$("#txtPreventivasFinPorc3").html((data.rows[fila].preventivasfin * 100 / data.rows[fila].preventivas).toFixed(1));
		$("#txtPreventivasFinPorcg3").attr("data-percent",(data.rows[fila].preventivasfin * 100 / data.rows[fila].preventivas).toFixed(1));
		$("#txtPreventivasPen3").html(formatNumber.new(data.rows[fila].preventivaspen));
		$("#txtPreventivasPenPorc3").html((data.rows[fila].preventivaspen * 100 / data.rows[fila].preventivas).toFixed(1));
		$("#txtPreventivasPenPorcg3").attr("data-percent",(data.rows[fila].preventivaspen * 100 / data.rows[fila].preventivas).toFixed(1));
		$("#txtCorrectivas3").html(formatNumber.new(data.rows[fila].correctivas));
		$("#txtCorrectivasPorc3").html((data.rows[fila].correctivas * 100 / data.rows[fila].correctivas) + "%");
		$("#txtCorrectivasPorcg3").attr("data-percent",(data.rows[fila].correctivas * 100 / data.rows[fila].correctivas).toFixed(1));
		$("#txtCorrectivasFin3").html(formatNumber.new(data.rows[fila].correctivasfin));
		$("#txtCorrectivasFinPorc3").html((data.rows[fila].correctivasfin * 100 / data.rows[fila].correctivas).toFixed(1));
		$("#txtCorrectivasFinPorcg3").attr("data-percent",(data.rows[fila].correctivasfin * 100 / data.rows[fila].correctivas).toFixed(1));
		$("#txtCorrectivasPen3").html(formatNumber.new(data.rows[fila].correctivaspen));
		$("#txtCorrectivasPenPorc3").html((data.rows[fila].correctivaspen * 100 / data.rows[fila].correctivas).toFixed(1));
		$("#txtCorrectivasPenPorcg3").attr("data-percent",(data.rows[fila].correctivaspen * 100 / data.rows[fila].correctivas).toFixed(1));
		
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
		cargarDatos(pUnidad);
		cargarDatos2(pUnidad);
		mapa(pUnidad);
		/*abrirFicha(pUnidad);
		tablaRadiologos(pUnidad);
		tablaTIncidentes(pUnidad);
		tablaIncidentes(pUnidad);
		tablaHistorial(pUnidad);*/
		correctivos(pUnidad);
		preventivos(pUnidad);
		correctivos2(pUnidad);
		correctivos3(pUnidad);
		correctivos4(pUnidad);
		url:'controller/soporteback.php?opcion=UNIDADES&agno=&mes=';
		agno=$("#filtro-desde").val();
		mes=$("#filtro-hasta").val();
		urlOrdenes = 'controller/soporteback.php?opcion=UNIDADES&agno='+agno+'&mes='+mes;
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
			url: "controller/soporteback.php",
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
			url: "controller/soporteback.php",
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
			url: "controller/soporteback.php",
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
			url: "controller/soporteback.php",
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
		cargarDatos(pUnidad);
		cargarDatos2(pUnidad);
		mapa(pUnidad);
		//abrirFichaSop(pUnidad);
		//actualizarTablas(pUnidad);
		correctivos(pUnidad);
		preventivos(pUnidad);
		correctivos2(pUnidad);
		correctivos3(pUnidad);
		correctivos4(pUnidad);
	}
	
	function filtrarProvincia(provincia) {
		$.ajax({
			url: "controller/soporteback.php",
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
	var urlPendientes = 'controller/soporteback.php?opcion=PENDIENTES&unidad=' + unidad +'&agno='+agno+'&mes='+mes;
	var urlPendientes = urlPendientes + '&provincia=' + $("#cmbProvincias").val() + '&modalidad=' + $("#cmbModalidades").val();
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
		
function tablaRadiologos(unidad) {
	var agno = $("#filtro-desde").val();
	var mes = $("#filtro-hasta").val();
	var urlRadiologos = 'controller/soporteback.php?opcion=RADIOLOGOS&unidad=' + unidad +'&agno='+agno+'&mes='+mes;
	var urlRadiologos = urlRadiologos + '&provincia=' + $("#cmbProvincias").val() + '&modalidad=' + $("#cmbModalidades").val();
	
	jQuery("#grid-tableR").jqGrid({
		url:urlRadiologos,
		datatype: "json",
		colNames:['Unidad','Nombre','Dia','Horario','Mod.', 'Informados', 'Dias', 'Puntos'],
		colModel:[
			{name:'Unidad',index:'unidad',width:40,search:true},
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
				var newWidth = $("#grid-tableR").closest(".ui-jqgrid").parent().width();
				$("#grid-tableR").jqGrid("setGridWidth", newWidth, true);
			},
		//caption:"Productividad Radi&oacute;logos"
	});
	
	//enable search/filter toolbar	
	$('#grid-tableR').jqGrid('filterToolbar',{
		stringResult: true, searchOnEnter: false, defaultSearch: 'cn', 
		ignoreCase: true, enableClear: false 
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
		document.location.href =(data.archivo);		
		$("#grid-tableR").closest(".ui-jqgrid").find('.loading').hide();
		//$("#grid-tableE").loadText = 'Cargando';
		//$('.loading').hide();
	});
}
	
function tablaTIncidentes(unidad) {
	var agno = $("#filtro-desde").val();
	var mes = $("#filtro-hasta").val();
	var urlTIncidentes = 'controller/soporteback.php?opcion=TINCIDENTES&unidad=' + unidad +'&agno='+$("#filtro-desde").val()+'&mes='+$("#filtro-hasta").val();
	var urlTIncidentes = urlTIncidentes + '&provincia=' + $("#cmbProvincias").val() + '&modalidad=' + $("#cmbModalidades").val();
	var grid_selector = "#grid-tableT";
	var pager_selector = "#grid-tableT";
	
	jQuery("#grid-tableT").jqGrid({
		url:urlTIncidentes,
		datatype: "json",
		colNames:['Unidad','Equipo','Serie','Marca','Tipo', 'Incidente', 'Nro. Incidente', 'Estado', 'Fecha'],
		colModel:[
			{name:'Unidad',index:'unidad',width:90,search:true},
			{name:'Equipo',index:'equipo',width:90,search:true},
			{name:'Serie',index:'serie',width:50,search:true},
			{name:'Marca',index:'marca',width:50,search:true},
			{name:'Nombre',index:'nombre', width:50,search:true},
			{name:'Titulo',index:'titulo', width:90, sorttype:"int", align:"right",search:false},
			{name:'Id',index:'id', width:20, sorttype:"int", align:"right",search:false},
			{name:'Estado',index:'estado', width:20, sorttype:"int", align:"right",search:false},
			{name:'FechaCreacion',index:'fechacreacion', width:20, sorttype:"int", align:"right",search:false}
		], 
		autowidth: true,
		rowNum:10,
		rowList:[10,20,30],
		pager: '#grid-pagerT',
		sortname: 'unidad',
		viewrecords: true,
		sortorder: "asc",
		grouping:true, 
		groupingView : {
			 groupField : ['Unidad','Equipo'],
			 groupDataSorted : true,
			 groupColumnShow : [false],
			 plusicon : 'fa fa-chevron-down bigger-110',
			 minusicon : 'fa fa-chevron-up bigger-110',
			 groupText: ['<b> {0} <i>(Mantenimientos: {1})</i></b>'],
			 groupSummary: [true],
			 groupCollapse: true
		},
		loadComplete : function() {
				var table = this;
				setTimeout(function(){
					//updateActionIcons(table);
					updatePagerIcons(table);
					enableTooltips(table);
				}, 0);
				var newWidth = $("#grid-tableT").closest(".ui-jqgrid").parent().width();
				$("#grid-tableT").jqGrid("setGridWidth", newWidth, true);
			},
		//caption:"Productividad Radi&oacute;logos"
	});
	
	//enable search/filter toolbar	
	$('#grid-tableT').jqGrid('filterToolbar',{
		stringResult: true, searchOnEnter: false, defaultSearch: 'cn', 
		ignoreCase: true, enableClear: false 
	});
	
	
	$('#grid-tableT').jqGrid('navGrid',"#grid-pagerT", 
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
	
	var grid_selector = "#grid-tableT";
	var pager_selector = "#grid-pagerT";
	
	
	$(window).on("resize", function () {
		var newWidth = $("#grid-tableT").closest(".ui-jqgrid").parent().width();
		$("#grid-tableT").jqGrid("setGridWidth", newWidth, true);	
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

function tablaIncidentes(unidad) {
	var urlIncidentes = 'controller/soporteback.php?opcion=INCIDENTES&unidad=' + unidad +'&agno='+$("#filtro-desde").val()+'&mes='+$("#filtro-hasta").val();
	var urlIncidentes = urlIncidentes + '&provincia=' + $("#cmbProvincias").val() + '&modalidad=' + $("#cmbModalidades").val();
	var grid_selector = "#grid-incidentes";
	var pager_selector = "#grid-incidentes";
	
	jQuery("#grid-incidentes").jqGrid({
		url: urlIncidentes,
		datatype: "json",
		colNames:['Unidad','Equipo','Serie','Marca','Tipo', 'Incidente', 'Nro. Incidente', 'Estado', 'Fecha'],
		colModel:[
			{name:'Unidad',index:'unidad',width:90,search:true},
			{name:'Equipo',index:'equipo',width:90,search:true},
			{name:'Serie',index:'serie',width:50,search:true},
			{name:'Marca',index:'marca',width:50,search:true},
			{name:'Nombre',index:'nombre', width:50,search:true},
			{name:'Titulo',index:'titulo', width:90, sorttype:"int", align:"right",search:false},
			{name:'Id',index:'id', width:20, sorttype:"int", align:"right",search:false},
			{name:'Estado',index:'estado', width:20, sorttype:"int", align:"right",search:false},
			{name:'FechaCreacion',index:'fechacreacion', width:20, sorttype:"int", align:"right",search:false}
		], 
		autowidth: true,
		height: "100%",
		rowNum:10,
		rowList:[10,20,30],
		pager: '#pager-incidentes',
		sortname: 'unidad',
		viewrecords: true,
		multiselect: false,
		gridview: true,
		autoencode: true,
		sortorder: "asc",
		grouping:true, 
		groupingView : {
			 groupField : ['Unidad','Equipo'],
			 groupDataSorted : true,
			 groupColumnShow : [false],
			 plusicon : 'fa fa-chevron-down bigger-110',
			 minusicon : 'fa fa-chevron-up bigger-110',
			 groupText: ['<b> {0} <i>(Mantenimientos: {1})</i></b>'],
			 groupSummary: [true],
			 groupCollapse: true
		}
	});
	
	//enable search/filter toolbar	
	$('#grid-incidentes').jqGrid('filterToolbar',{
		stringResult: true, searchOnEnter: false, defaultSearch: 'cn', ignoreCase: true, enableClear: false 
	});
	
	
	$('#grid-incidentes').jqGrid('navGrid',"#pager-incidentes", 
	{                
		add: false,
		edit: false,
		del: false,
		search: false,
		searchicon : 'ace-icon fa fa-search blue',
		refresh: true,
		refreshicon : 'ace-icon fa fa-refresh green'
	}).navSeparatorAdd(pager_selector,{
		sepclass : "ui-separator",sepcontent: ""}
	).navButtonAdd(pager_selector, 
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

	$(window).on("resize", function () {
		var newWidth = $("#grid-incidentes").closest(".ui-jqgrid").parent().width();
		$("#grid-incidentes").jqGrid("setGridWidth", newWidth, true);
	});
}

function tablaHistorial(unidad) {
	var urlHistorial = 'controller/soporteback.php?opcion=HISTORIAL&unidad=' + unidad +'&agno='+$("#filtro-desde").val()+'&mes='+$("#filtro-hasta").val();
	var urlHistorial = urlHistorial + '&provincia=' + $("#cmbProvincias").val() + '&modalidad=' + $("#cmbModalidades").val();
	var grid_selector = "#grid-historial";
	var pager_selector = "#grid-historial";
	
	jQuery("#grid-historial").jqGrid({
		url: urlHistorial,
		datatype: "json",
		colNames:['Unidad','Equipo','Serie','Marca','Tipo', 'Incidente', 'Nro. Incidente', 'Estado', 'Creado', 'Resuelto'],
		colModel:[
			{name:'Unidad',index:'unidad',width:90,search:true},
			{name:'Equipo',index:'equipo',width:90,search:true},
			{name:'Serie',index:'serie',width:50,search:true},
			{name:'Marca',index:'marca',width:50,search:true},
			{name:'Nombre',index:'nombre', width:50,search:true},
			{name:'Titulo',index:'titulo', width:90, sorttype:"int", align:"right",search:false},
			{name:'Id',index:'id', width:20, sorttype:"int", align:"right",search:false},
			{name:'Estado',index:'estado', width:20, sorttype:"int", align:"right",search:false},
			{name:'FechaCreacion',index:'fechacreacion', width:20, sorttype:"int", align:"right",search:false},
			{name:'FechaResolucion',index:'fecharesolucion', width:20, sorttype:"int", align:"right",search:false}
		], 
		autowidth: true,
		height: "100%",
		rowNum:500,
		rowList:[10,20,30,50,100,150,200,250,500],
		pager: '#pager-historial',
		sortname: 'unidad',
		viewrecords: true,
		multiselect: false,
		gridview: true,
		autoencode: true,
		sortorder: "asc",
		grouping:true, 
		groupingView : {
			 groupField : ['Unidad','Equipo'],
			 groupDataSorted : true,
			 groupColumnShow : [false],
			 plusicon : 'fa fa-chevron-down bigger-110',
			 minusicon : 'fa fa-chevron-up bigger-110',
			 groupText: ['<b> {0} <i>(Mantenimientos: {1})</i></b>'],
			 groupSummary: [true],
			 groupCollapse: true
		}
	});
	
	//enable search/filter toolbar	
	$('#grid-historial').jqGrid('filterToolbar',{
		stringResult: true, searchOnEnter: false, defaultSearch: 'cn', ignoreCase: true, enableClear: false 
	});
	
	
	$('#grid-historial').jqGrid('navGrid',"#pager-historial", 
	{                
		add: false,
		edit: false,
		del: false,
		search: false,
		searchicon : 'ace-icon fa fa-search blue',
		refresh: true,
		refreshicon : 'ace-icon fa fa-refresh green'
	}).navSeparatorAdd(pager_selector,{
		sepclass : "ui-separator",sepcontent: ""}
	).navButtonAdd(pager_selector, 
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

	$(window).on("resize", function () {
		var newWidth = $("#grid-historial").closest(".ui-jqgrid").parent().width();
		$("#grid-historial").jqGrid("setGridWidth", newWidth, true);
	});
}

function preventivos(unidad) {
	$.ajax({
		url: "controller/soporteback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "PREVENTIVOS",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"unidad": unidad,
			"provincia":$("#cmbProvincias").val(),
			"modalidad":$("#cmbModalidades").val()
		}
	}).done(function(data) {
		arrCategorias = [];
		arrRealizadas = [];
		arrXRealizar = [];
		for (i=0; i<data.length; i++) {
			arrCategorias.push(data[i].prov);
			arrRealizadas.push(Number(data[i].preventivasfin));
			arrXRealizar.push(Number(data[i].preventivaspen));
		}
		var gwidth = $("#tab-graficos").width() * 0.98;
		var options = {
			chart: {
				type: 'column',
				renderTo: 'ct-preventivos',
				margin: 75,
				width: gwidth,
				height: 400,
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
					text: 'Mantenimientos'
				}
			},
			legend: {
				align: 'center',
				verticalAlign: 'top'
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
				name: 'Realizados',
				lineWidth: 4,
				data: arrRealizadas
			}, {
				name: 'Por Realizar',
				lineWidth: 4,
				data: arrXRealizar
			}]
	};
		
		var chart = new Highcharts.Chart(options);
	
	
	});
}

function correctivos(unidad) {
	$.ajax({
		url: "controller/soporteback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "CORRECTIVOS",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"unidad": unidad,
			"provincia":$("#cmbProvincias").val(),
			"modalidad":$("#cmbModalidades").val()
		}
	}).done(function(data) {
		arrCategorias = [];
		arrP = [];
		arrC = [];
		i=0; j=-1;
		for (i=0; i<12; i++) {
			arrCategorias.push(data[i].categoria);
			arrP.push(Number(data[i].preventivas));
			arrC.push(Number(data[i].correctivas));
		}
		arrCategorias.reverse();
		arrP.reverse();
		arrC.reverse();
		
		var gwidth = $("#tab-graficos").width() * 0.98;
		var options = {
			chart: {
				renderTo: 'ct-correctivos',
				type: 'spline',
				width: gwidth,
				height: 400
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
					text: 'Ordenes'
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
				borderWidth: 0,
				align: 'center',
				verticalAlign: 'top'
			},
			plotOptions: {
				spline: {
					dataLabels: {
						enabled: true,
						format: '{y:.0f}'
					},
					enableMouseTracking: false
				}
			},
			credits: {
				enabled: false
			},
			series: [{
				name: 'Correctivas',
				lineWidth: 4,
				data: arrC
			}, {
				name: 'Preventivas',
				lineWidth: 4,
				data: arrP
			}/*, {
				name: 'De',
				lineWidth: 4,
				data: arrDe
			}*/]
		};
		var chart = new Highcharts.Chart(options);
	});
}

function correctivos2(unidad) {
	$.ajax({
		url: "controller/soporteback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "CORRECTIVOS2",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"unidad": unidad,
			"provincia":$("#cmbProvincias").val(),
			"modalidad":$("#cmbModalidades").val()
		}
	}).done(function(data) {
		arrCategorias = [];
		arrDiag = [];
		arrCRDR = []; 
		arrCons = []; 
		arrOtro = []; 
		arrCorr = []; 
		arrPrev = [];
		i=0; j=-1;
		for (i=0; i<12; i++) {
			arrCategorias.push(data[i].categoria);
			arrDiag.push(Number(data[i].diagnostico));
			arrCRDR.push(Number(data[i].crdr));
			arrCons.push(Number(data[i].consorcio));
			arrOtro.push(Number(data[i].otros));
			arrCorr.push(Number(data[i].correctivas));
			arrPrev.push(Number(data[i].preventivas));
		}
		arrCategorias.reverse();
		arrDiag.reverse();
		arrCRDR.reverse();
		arrCons.reverse();
		arrOtro.reverse();
		arrCorr.reverse();
		arrPrev.reverse();
		
		
		var gwidth = $("#tab-graficos").width() * 0.98;
		var options = {
			chart: {
				renderTo: 'ct-correctivos2',
				type: 'spline',
				width: gwidth,
				height: 400
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
					text: 'Ordenes'
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
				borderWidth: 0,
				align: 'center',
				verticalAlign: 'top'
			},
			plotOptions: {
				spline: {
					dataLabels: {
						enabled: true,
						format: '{y:.0f}'
					},
					enableMouseTracking: false
				}
			},
			credits: {
				enabled: false
			},
			series: [{
				name: 'Correctivas',
				lineWidth: 4,
				data: arrCorr
			},{
				name: 'Preventivas',
				lineWidth: 4,
				data: arrPrev
			},{
				name: 'Eq. Diagnóstico',
				lineWidth: 4,
				data: arrDiag
			}, {
				name: 'CR/DR',
				lineWidth: 4,
				data: arrCRDR
			}, {
				name: 'Consorcio',
				lineWidth: 4,
				data: arrCons
			},{
				name: 'Otros',
				lineWidth: 4,
				data: arrOtro
			}]
		};
		var chart = new Highcharts.Chart(options);
	});
}


function correctivos3(unidad) {
	$.ajax({
		url: "controller/soporteback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "CORRECTIVOS3",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"unidad": unidad,
			"provincia":$("#cmbProvincias").val(),
			"modalidad":$("#cmbModalidades").val()
		}
	}).done(function(data) {
		arrCategorias = [];
		arrC = [];
		arrP = [];
		arrE = [];
		for (i=0; i<data.length; i++) {
			if (data[i].correctivos > 0 || data[i].preventivos > 0) {
				arrCategorias.push(data[i].marca);
				arrC.push(Number(data[i].correctivos));
				arrP.push(Number(data[i].preventivos));
				arrE.push(Number(data[i].equipos));
			}
		}
		
		var gwidth = $("#tab-graficos").width() * 0.98;
		var options = {
			chart: {
				type: 'column',
				renderTo: 'ct-correctivos3',
				margin: 90,
				width: gwidth,
				height: 400,
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
					text: 'Ordenes'
				}
			},
			legend: {
				align: 'center',
				verticalAlign: 'top'
			},
			tooltip: {
				headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
					'<td style="padding:0"><b>{point.y:.1f} Est.</b></td></tr>',
				footerFormat: '</table>',
				shared: true,
				useHTML: true
			},
			series: [{
				type: 'column',
				name: 'Preventivos',
				lineWidth: 4,
				data: arrP
			},{
				type: 'column',
				name: 'Correctivos',
				lineWidth: 4,
				data: arrC
			},{
				type: 'spline',
				name: 'Cant. Equipos',
				lineWidth: 4,
				data: arrE
			}]
	};
		var chart = new Highcharts.Chart(options);
	
	
	});
}

function correctivos4(unidad) {
	$.ajax({
		url: "controller/soporteback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "CORRECTIVOS4",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"unidad": unidad,
			"provincia":$("#cmbProvincias").val(),
			"modalidad":$("#cmbModalidades").val()
		}
	}).done(function(data) {
		arrCategorias = [];
		arrC = [];
		arrP = [];
		arrE = [];
		for (i=0; i<data.length; i++) {
			if (data[i].correctivos > 0 || data[i].preventivos > 0) {
				arrCategorias.push(data[i].modalidad);
				arrC.push(Number(data[i].correctivos));
				arrP.push(Number(data[i].preventivos));
				arrE.push(Number(data[i].equipos));
			}
		}
		
		var gwidth = $("#tab-graficos").width() * 0.98;
		var options = {
			chart: {
				type: 'column',
				renderTo: 'ct-correctivos4',
				margin: 90,
				width: gwidth,
				height: 400,
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
					text: 'Ordenes'
				}
			},
			legend: {
				align: 'center',
				verticalAlign: 'top'
			},
			tooltip: {
				headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
					'<td style="padding:0"><b>{point.y:.1f} Est.</b></td></tr>',
				footerFormat: '</table>',
				shared: true,
				useHTML: true
			},
			series: [{
				type: 'column',
				name: 'Preventivos',
				lineWidth: 4,
				data: arrP
			},{
				type: 'column',
				name: 'Correctivos',
				lineWidth: 4,
				data: arrC
			},{
				type: 'spline',
				name: 'Cant. Equipos',
				lineWidth: 4,
				data: arrE
			}]
	};
		var chart = new Highcharts.Chart(options);
	
	
	});
}

function abrirFicha(unidad) {
	$.ajax({
		url: "controller/soporteback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "DATOSUNIDAD",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"unidad": unidad,
			"provincia":$("#cmbProvincias").val(),
			"modalidad":$("#cmbModalidades").val()
		}
	}).done(function(data) {
		//console.log(data);
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
	});
}

function actualizarTablas(unidad) {
	var agno = $("#filtro-desde").val();
	var mes = $("#filtro-hasta").val();
	var urlRadiologos = 'controller/soporteback.php?opcion=RADIOLOGOS&unidad=' + unidad +'&agno='+agno+'&mes='+mes;
	var urlRadiologos = urlRadiologos + '&provincia=' + $("#cmbProvincias").val() + '&modalidad=' + $("#cmbModalidades").val();
	$("#grid-tableR").jqGrid('setGridParam',{url:urlRadiologos});
	$("#grid-tableR").jqGrid('clearGridData');
	$("#grid-tableR").trigger("reloadGrid");
	
	var urlTecnicos = 'controller/soporteback.php?opcion=TECNICOS&unidad=' + unidad +'&agno='+$("#filtro-desde").val()+'&mes='+$("#filtro-hasta").val();
	var urlTecnicos = urlTecnicos + '&provincia=' + $("#cmbProvincias").val() + '&modalidad=' + $("#cmbModalidades").val();
	$("#grid-tableT").jqGrid('setGridParam',{url:urlTecnicos});
	$("#grid-tableT").jqGrid('clearGridData');
	$("#grid-tableT").trigger("reloadGrid");
	
	var urlHistorial = 'controller/soporteback.php?opcion=HISTORIAL&unidad=' + unidad +'&agno='+$("#filtro-desde").val()+'&mes='+$("#filtro-hasta").val();
	var urlHistorial = urlHistorial + '&provincia=' + $("#cmbProvincias").val() + '&modalidad=' + $("#cmbModalidades").val();
	$("#grid-historial").jqGrid('setGridParam',{url:urlHistorial});
	$("#grid-historial").jqGrid('clearGridData');
	$("#grid-historial").trigger("reloadGrid");
	
	var urlIncidentes = 'controller/soporteback.php?opcion=INCIDENTES&unidad=' + unidad +'&agno='+$("#filtro-desde").val()+'&mes='+$("#filtro-hasta").val();
	var urlIncidentes = urlIncidentes + '&provincia=' + $("#cmbProvincias").val() + '&modalidad=' + $("#cmbModalidades").val();
	$("#grid-incidentes").jqGrid('setGridParam',{url:urlIncidentes});
	$("#grid-incidentes").jqGrid('clearGridData');
	$("#grid-incidentes").trigger("reloadGrid");
}

	
	



