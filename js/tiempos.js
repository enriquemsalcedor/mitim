var tecnicos, radiologos, teleradiologos, dias, turnos; 
var estudios, informados, esperadosi, esperadosr, mapa;
var modalidad="*";
var provincia="*";

jQuery(function($) {
	var lastSel, lastSel2;
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
	//tendenciaEstudios();
	//tendenciaTiempos();
	
	$('#cmbProvincias').on('change', function (e) {
		provincia = this.value;
		filtrarProvincia(this.value);
	});
	
	$('#cmbModalidades').on('change', function (e) {
		modalidad = this.value;
		filtrarModalidad(this.value);
	});
	
	$.ajax({
		url: "controller/tiemposback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "MAPA",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"tipo": "",
			"modalidad": ""
		}
	}).done(function(data) {
		mapa = new Maplace({
			type: 'circle',
			locations: data,
			controls_on_map: false,
			map_options: {
				mapTypeId: google.maps.MapTypeId.TERRAIN,
				zoom: 8,
				zoomControl: false,
				streetViewControl: false,
				scrollwheel: false,
				scaleControl: false
			},
			listeners: {
				click: function(map, event) {
					//map.setOptions({scrollwheel: true});
				}
			}
		}).Load();
		//console.log(mapa);
	});
	
	$('.switch-sidebar-mini input').change();
});

function zoomIn() {
	mapa.oMap.setZoom(mapa.oMap.getZoom()+1);
	for (var i = 0, len = mapa.circles.length; i < len; i++) {
		nuevoTamano = mapa.circles[i].getRadius() * 0.6;
		mapa.circles[i].setRadius(nuevoTamano);
	}
	
};


function zoomOut() {
	mapa.oMap.setZoom(mapa.oMap.getZoom()-1);
	for (var i = 0, len = mapa.circles.length; i < len; i++) {
		nuevoTamano = mapa.circles[i].getRadius() * 1.6;
		mapa.circles[i].setRadius(nuevoTamano);
	}
};

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

function buscarValor(variable, agno, data, tipo) {
	var xvalor = 0
	for(var i = 0; i < data.rows.length; i++)
	{
		if(data.rows[i].modalidad == variable && data.rows[i].ano == agno)
		{
			if (tipo=='a')
				return data.rows[i].valora;
			else
				return data.rows[i].valori;
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
		url: "controller/tiemposback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "DATOS",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val()
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
		$("#txttotal").html("Promedios");
		$("#txttotalPorc").html("Atencion / Informes");
		$("#txtAg").html(Number(buscarValor('Angiografia', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Angiografia', xano, data, 'i')).toFixed(1));
		$("#txtFl").html(Number(buscarValor('Fluoroscopia', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Fluoroscopia', xano, data, 'i')).toFixed(1));
		$("#txtMg").html(Number(buscarValor('Mamografia', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Mamografia', xano, data, 'i')).toFixed(1));
		$("#txtEd").html(Number(buscarValor('Otros Estudios', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Otros Estudios', xano, data, 'i')).toFixed(1));
		$("#txtRx").html(Number(buscarValor('Radiologia Convencional', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Radiologia Convencional', xano, data, 'i')).toFixed(1));
		$("#txtRm").html(Number(buscarValor('Resonancia Magnetica', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Resonancia Magnetica', xano, data, 'i')).toFixed(1));
		$("#txtTc").html(Number(buscarValor('Tomografia Computada', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Tomografia Computada', xano, data, 'i')).toFixed(1));
		$("#txtUs").html(Number(buscarValor('Ultrasonido', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Ultrasonido', xano, data, 'i')).toFixed(1));
		
		total = 0;
		var xano = data.rows[i].ano;
		while (i<data.rows.length && data.rows[i].ano==xano) {
			total += Number(data.rows[i].valor);
			i++;
		}
		$("#txtAgno2").html(xano);
		$("#txttotal2").html("Promedios");
		$("#txttotal2Porc").html("Atencion / Informes");
		$("#txtAg2").html(Number(buscarValor('Angiografia', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Angiografia', xano, data, 'i')).toFixed(1));
		$("#txtFl2").html(Number(buscarValor('Fluoroscopia', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Fluoroscopia', xano, data, 'i')).toFixed(1));
		$("#txtMg2").html(Number(buscarValor('Mamografia', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Mamografia', xano, data, 'i')).toFixed(1));
		$("#txtEd2").html(Number(buscarValor('Otros Estudios', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Otros Estudios', xano, data, 'i')).toFixed(1));
		$("#txtRx2").html(Number(buscarValor('Radiologia Convencional', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Radiologia Convencional', xano, data, 'i')).toFixed(1));
		$("#txtRm2").html(Number(buscarValor('Resonancia Magnetica', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Resonancia Magnetica', xano, data, 'i')).toFixed(1));
		$("#txtTc2").html(Number(buscarValor('Tomografia Computada', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Tomografia Computada', xano, data, 'i')).toFixed(1));
		$("#txtUs2").html(Number(buscarValor('Ultrasonido', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Ultrasonido', xano, data, 'i')).toFixed(1));
		
		total = 0;
		var xano = data.rows[i].ano;
		while (i<data.rows.length && data.rows[i].ano==xano) {
			total += Number(data.rows[i].valor);
			i++;
		}
		$("#txtAgno3").html(xano);
		$("#txttotal3").html("Promedios");
		$("#txttotal3Porc").html("Atencion / Informes");
		$("#txtAg3").html(Number(buscarValor('Angiografia', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Angiografia', xano, data, 'i')).toFixed(1));
		$("#txtFl3").html(Number(buscarValor('Fluoroscopia', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Fluoroscopia', xano, data, 'i')).toFixed(1));
		$("#txtMg3").html(Number(buscarValor('Mamografia', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Mamografia', xano, data, 'i')).toFixed(1));
		$("#txtEd3").html(Number(buscarValor('Otros Estudios', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Otros Estudios', xano, data, 'i')).toFixed(1));
		$("#txtRx3").html(Number(buscarValor('Radiologia Convencional', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Radiologia Convencional', xano, data, 'i')).toFixed(1));
		$("#txtRm3").html(Number(buscarValor('Resonancia Magnetica', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Resonancia Magnetica', xano, data, 'i')).toFixed(1));
		$("#txtTc3").html(Number(buscarValor('Tomografia Computada', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Tomografia Computada', xano, data, 'i')).toFixed(1));
		$("#txtUs3").html(Number(buscarValor('Ultrasonido', xano, data, 'a')).toFixed(1) + " / " + Number(buscarValor('Ultrasonido', xano, data, 'i')).toFixed(1));
	});
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
		//prodesperada();
		//$("#txtRx").html(data.rx);
		//$("#txtFl").html(formatNumber.new(data.rows[2].valor));
		//$("#txtEdPorc").html((data.rows[4].valor * 100 / data.rows[0].valor).toFixed(1) + " %");
	});
}


/*
		Grid dentro ventana de unidades
	*/
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
		/*tendenciaEstudios();
		tendenciaTiempos();
		url:'controller/dashboardback.php?opcion=UNIDADES&agno=&mes=';
		agno=$("#filtro-desde").val();
		mes=$("#filtro-hasta").val();
		urlOrdenes = 'controller/dashboardback.php?opcion=UNIDADES&agno='+agno+'&mes='+mes;
		$("#grid-unidades").jqGrid('setGridParam', { url: urlOrdenes });
		$("#grid-unidades").jqGrid('clearGridData');
		$("#grid-unidades").trigger('reloadGrid');
		*/
	}
	
	function mostrarHospitales() {
		$.ajax({
			url: "controller/tiemposback.php",
			cache: false,
			dataType: "json",
			method: "POST",
			data: {
				"opcion": "MAPA",
				"agno":$("#filtro-desde").val(),
				"mes":$("#filtro-hasta").val(),
				"tipo": "Hospital",
				"modalidad": modalidad
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
	
	function mostrarPoliclinicas() {
		$.ajax({
			url: "controller/tiemposback.php",
			cache: false,
			dataType: "json",
			method: "POST",
			data: {
				"opcion": "MAPA",
				"agno":$("#filtro-desde").val(),
				"mes":$("#filtro-hasta").val(),
				"tipo": "Poli",
				"modalidad": modalidad
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
	
	function mostrarUlaps() {
		$.ajax({
			url: "controller/tiemposback.php",
			cache: false,
			dataType: "json",
			method: "POST",
			data: {
				"opcion": "MAPA",
				"agno":$("#filtro-desde").val(),
				"mes":$("#filtro-hasta").val(),
				"tipo": "Ulap",
				"modalidad": modalidad
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
	
	function mostrarTodos() {
		$.ajax({
			url: "controller/tiemposback.php",
			cache: false,
			dataType: "json",
			method: "POST",
			data: {
				"opcion": "MAPA",
				"agno":$("#filtro-desde").val(),
				"mes":$("#filtro-hasta").val(),
				"tipo" : "",
				"modalidad": modalidad
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
	
	function filtrarProvincia(provincia) {
		$.ajax({
			url: "controller/tiemposback.php",
			cache: false,
			dataType: "json",
			method: "POST",
			data: {
				"opcion": "MAPA",
				"agno":$("#filtro-desde").val(),
				"mes":$("#filtro-hasta").val(),
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
	
	function mostrarModalidad(pmodalidad) {
		modalidad = pmodalidad;
		mostrarTodos();
	}
	
	
	



