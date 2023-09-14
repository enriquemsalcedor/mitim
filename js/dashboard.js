//$(document).ready( function () {

$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
$("#icono-reportes").css("display","block");
$("#idproyectos").select2({placeholder: ""});
$('.filter-name').prop('style','display:none')

// TOOLTIPS
$('[data-toggle="tooltip"]').tooltip();

/* function shFiltros() {
  var element = document.getElementById("box-filtros");
  element.classList.toggle("d-none");
} */

$.get( "controller/combosback.php?oper=proyectos", { onlydata:"true" }, function(result){ 
	$("#idproyectos").empty();
	$("#idproyectos").append(result);
	optionDefault('idproyectos');
});
 var temporalidad = '';
 var idproyectos = 0;
 
$(".filtro-tiempo").on('click', function(event){ 
	var temp = $(this).attr("id");
	if(temp == 'filtro-dia'){
        $('.filter-name').prop('style','display:block')
		$(".filtro-tiempo").removeClass("active"); 
		$(".filtro-tiempo").addClass("facebook-bg");
		$("#filtro-dia").removeClass("facebook-bg");
		$("#filtro-dia").addClass("active"); 
		temporalidad = 'dia';
		var datomodulo = $(".div-tipo .active").attr("id"); 
		if(datomodulo == 'filtro-preventivos'){
			modulo = 'preventivos';
		}else if(datomodulo == 'filtro-correctivos'){
			modulo = 'correctivos';
		}else{
			modulo = 'correctivos';
		}
		idproyectos = $("#idproyectos option:selected").val();
		cargarDash(temporalidad,modulo,idproyectos);
		datosBase(temporalidad);
		
	}else if(temp == 'filtro-semana'){
        $('.filter-name').prop('style','display:block')
		$(".filtro-tiempo").removeClass("active"); 
		$(".filtro-tiempo").addClass("facebook-bg");
		$("#filtro-semana").removeClass("facebook-bg");
		$("#filtro-semana").addClass("active"); 
		temporalidad = 'semana';
		var datomodulo = $(".div-tipo .active").attr("id");
		if(datomodulo == 'filtro-preventivos'){
			modulo = 'preventivos';
		}else if(datomodulo == 'filtro-correctivos'){
			modulo = 'correctivos';
		}else{
			modulo = 'correctivos';
		}
		idproyectos = $("#idproyectos option:selected").val();
		cargarDash(temporalidad,modulo,idproyectos); 
		datosBase(temporalidad);
		
	}else if(temp == 'filtro-mes'){
        $('.filter-name').prop('style','display:block')
		$(".filtro-tiempo").removeClass("active");
//		$(".filtro-tiempo").removeClass("button-yellow");
		$(".filtro-tiempo").addClass("facebook-bg");
		$("#filtro-mes").removeClass("facebook-bg");
		$("#filtro-mes").addClass("active");
//		$("#filtro-mes").addClass("button-yellow");
		temporalidad = 'mes';
		var datomodulo = $(".div-tipo .active").attr("id");
		if(datomodulo == 'filtro-preventivos'){
			modulo = 'preventivos';
		}else if(datomodulo == 'filtro-correctivos'){
			modulo = 'correctivos';
		}else{
			modulo = 'correctivos';
		}
		idproyectos = $("#idproyectos option:selected").val();
		cargarDash(temporalidad,modulo,idproyectos);
		datosBase(temporalidad);
		
	}else if(temp == 'filtro-todo'){

        $('.filter-name').prop('style','display:none')
		
		$(".filtro-tiempo").removeClass("active");
//		$(".filtro-tiempo").removeClass("button-yellow");
		$(".filtro-tiempo").addClass("facebook-bg");
		$("#filtro-todo").removeClass("facebook-bg");
		$("#filtro-todo").addClass("active");
//		$("#filtro-todo").addClass("button-yellow");
		temporalidad = 'todo';
		var datomodulo = $(".div-tipo .active").attr("id");
		if(datomodulo == 'filtro-preventivos'){
			modulo = 'preventivos';
		}else if(datomodulo == 'filtro-correctivos'){
			modulo = 'correctivos';
		}else{
			modulo = 'correctivos';
		}
		idproyectos = $("#idproyectos option:selected").val();
		cargarDash(temporalidad,modulo,idproyectos);
		datosBase(temporalidad);
		
	}
});

$(".filtro-tipo").on('click', function(event){
	var tipo = $(this).attr("id");
	if(tipo == 'filtro-correctivos'){
		//$(".datosmodulo").text("Correctivos");
		$("#filtro-preventivos").removeClass("active"); 
		$("#filtro-preventivos").addClass("facebook-bg");
		$("#filtro-correctivos").removeClass("facebook-bg");
		$("#filtro-correctivos").addClass("active"); 
		
		var modulo = 'correctivos';
		var datotiempo = $(".div-temporalidad .active").attr("id");
		
		if(datotiempo=='filtro-dia'){
			temporalidad = 'dia';
		}else if(datotiempo=='filtro-semana'){
			temporalidad = 'semana';
		}else if(datotiempo=='filtro-mes'){
			temporalidad = 'mes';
		}else{
			temporalidad = 'todo';
		}
		var idproyectos = $("#idproyectos option:selected").val();
		
		cargarDash(temporalidad,modulo,idproyectos);
		
	}else{
		//$(".datosmodulo").text("Preventivos");
		$("#filtro-correctivos").removeClass("active");
//		$("#filtro-correctivos").removeClass("button-yellow");
		$("#filtro-correctivos").addClass("facebook-bg");
		$("#filtro-preventivos").removeClass("facebook-bg");
		$("#filtro-preventivos").addClass("active");
//		$("#filtro-preventivos").addClass("button-yellow");
		var modulo = 'preventivos';
		var datotiempo = $(".div-temporalidad .active").attr("id");
		
		if(datotiempo=='filtro-dia'){
			temporalidad = 'dia';
		}else if(datotiempo=='filtro-semana'){
			temporalidad = 'semana';
		}else if(datotiempo=='filtro-mes'){
			temporalidad = 'mes';
		}else{
			temporalidad = 'todo';
		}
		var idproyectos = $("#idproyectos option:selected").val();
		cargarDash(temporalidad,modulo,idproyectos);
		
	}
});

$('#idproyectos').on('change',function(){
	//variables
	var datotiempo = $(".div-temporalidad .active").attr("id");
	var datomodulo = $(".div-tipo .active").attr("id");
	var idproyectos = $("#idproyectos option:selected").val();
	//condiciones
	if(datotiempo=='filtro-dia'){
		temporalidad = 'dia';
	}else if(datotiempo=='filtro-semana'){
		temporalidad = 'semana';
	}else if(datotiempo=='filtro-mes'){
		temporalidad = 'mes';
	}else{
		temporalidad = 'todo';
	}
	
	if(datomodulo == 'filtro-preventivos'){
		modulo = 'preventivos';
	}else if(datomodulo == 'filtro-correctivos'){
		modulo = 'correctivos';
	}else{
		modulo = 'correctivos';
	} 
	
	cargarDash(temporalidad,modulo,idproyectos);
});
	
//LOADER
funciones = 6;
param = 0;
function loader(param, fin){
	if(param == fin){ 
		param = 0;  
		$('#overlay').css('display','none');
	} 
}	 
cargarDash("todo","incidentes",idproyectos); 

function cargarDash(tiempo,modulo,idproyectos){	
	$('#overlay').css('display','block');
	param = 0;
	cargarContadores(tiempo,modulo,idproyectos);
	cargarResumen(tiempo,modulo,idproyectos);
	cargarIncidentesUsuarios(tiempo,modulo,idproyectos);
	cargarGraficoEstados(tiempo,modulo,idproyectos);
	cargarGraficoCategorias(tiempo,modulo,idproyectos);
	cargarFueraServicio(tiempo,modulo,idproyectos);
}

function cargarFueraServicio(tiempo,modulo,idproyectos){
	//Fuera servicio
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion" : "fueraservicio",
			"tipo"	: $("#tipo").val(),
			"tiempo": tiempo, 
			"modulo": modulo,
			"idproyectos": idproyectos
		}
	}).done(function(data) {
		const chart = new Highcharts.Chart({
			chart: {
				renderTo: 'donut-fueraservicio',
				type: 'pie'
			},
			title: {
				verticalAlign: 'middle',
				floating: true,
				text: parseInt(data.disponibles)+" %",
			},subtitle: {
						text: ''
					},
			plotOptions: {
				pie: {
					innerSize: '100%',
					dataLabels: {
								enabled: true,
								format: '<b>{series.name}</b>: {point.y}'
							},
					//showInLegend: true
				},
				series: {
					states: {
						hover: {
							enabled: false
						},
						inactive: {
							opacity: 1
						}
					}
				}
			},tooltip: {
						pointFormat: '{series.name}: <b>{point.y}</b>'
					},
			series: [
				{
					borderWidth: 0,
					name: 'Activos',
					data: [
						{
							y: parseInt(data.disponibles),
							name: "% Disponibles",
							color: "#369DC9",
						},
						{
							y: parseInt(data.nodisponibles),
							name: "% No disponibles",
							color: "#7E7E7EC4",
						}
					],
					size: '110%',
					innerSize: '70%',
					showInLegend: true,
					dataLabels: {
						enabled: false
					}
				}
			],
			credits: {
				enabled: false
			},exporting: {
						enabled: true,
						buttons: {
							contextButton: {
								menuItems: ["downloadJPEG", "downloadPDF"]
							}
						}
					},
			lang: {
				viewFullscreen:"Ver en pantalla completa",
				downloadPNG:"Descargar imagen PNG",
				downloadJPEG:"Descargar imagen",
				downloadPDF:"Descargar documento PDF",
				downloadSVG:"Descargar vector SVG",
				contextButtonTitle: "Menú"
			},
			legend: {
				//enabled: true,
				layout: 'horizontal',
				align: 'left',
				verticalAlign: 'bottom'
			}
		});
		param=param+1;
		loader(param, funciones); 
	});
}

function cargarContadores(tiempo,modulo,idproyectos){ 
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion" : "incidentesContadores",
			"tipo"	: $("#tipo").val(),
			"tiempo": tiempo,
			"modulo": modulo,
			"idproyectos": idproyectos
		}
	}).done(function(data) {
		$(".incidentesasignados").text(data.asignados);
		$(".incidentesresueltos").text(data.resueltos);
		$(".incidentespendientes").text(data.pendientes);
		
		param=param+1;
		loader(param, funciones);
	});
}

function cargarResumen(tiempo,modulo,idproyectos){ 
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion" : "incidentesMeses",
			"tipo"	: $("#tipo").val(),
			"tiempo": tiempo,
			"modulo": modulo,
			"idproyectos": idproyectos
		}
	}).done(function(data) {		
		var categorias = JSON.parse(data.categorias);
		var valores = JSON.parse(data.valores);
		let textXaxis = "";
		if(tiempo == 'dia'){
			textXaxis = 'Horas';
		}
		if(tiempo == 'mes'){
			textXaxis = 'Meses';
		}
		if(tiempo == 'semana'){
			textXaxis = 'Días';
		}
		if(tiempo == 'todo'){
			textXaxis = 'Meses';
		}
		
		Highcharts.chart('incidentesmeses', {
			chart: {
				type: 'line'
			},
			title: {
				text: 'Resumen'
			},
			subtitle: {
				text: ''
			},
			xAxis: {
					categories: categorias,
					title: {
						text: textXaxis
					}
				},
			yAxis: {
				title: {
					text: null
				}
			},
			plotOptions: {
				line: {
					dataLabels: {
						enabled: true
					},
					enableMouseTracking: false
				}
			},
			legend: {
				itemStyle: {
					fontSize:'10px',
					font: '10pt Trebuchet MS, Verdana, sans-serif',
					color: '#7e7e7e'
				},
				itemHoverStyle: {
					color: '#36C95F'
				},
				itemHiddenStyle: {
					color: '#7e7e7e'
				},
				//borderWidth: 1,
				//backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
				//shadow: true
			},
			credits: {
				enabled: false
			},
			lang: {
				viewFullscreen:"Ver en pantalla completa",
				/*printChart:"Imprimir gráfico",*/
				downloadPNG:"Descargar imagen PNG",
				downloadJPEG:"Descargar imagen",
				downloadPDF:"Descargar documento PDF",
				downloadSVG:"Descargar vector SVG",
				contextButtonTitle: "Menú"
			},
			exporting: {
				enabled: true,
				buttons: {
					contextButton: {
						menuItems: [/*"printChart",*/ "downloadJPEG", "downloadPDF"]
					}
				}
			},
			series: valores
		});
		
		param=param+1;
		loader(param, funciones);
	}); 
}

function cargarIncidentesUsuarios(tiempo,modulo,idproyectos){ 
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "incidentesUsuarios",
			"tipo"	: $("#tipo").val(),
			"tiempo": tiempo,
			"modulo": modulo,
			"idproyectos": idproyectos
		}
	}).done(function(data) {
		const chart = new Highcharts.Chart({
			chart: {
				renderTo: 'incidentesusuarios',
				type: 'pie'
			},
			title: {
				verticalAlign: 'middle',
				floating: true,
				text: parseInt(data.resueltos)+" %",
			},subtitle: {
						text: ''
					},
			plotOptions: {
				pie: {
					innerSize: '50%',
					dataLabels: {
								enabled: true,
								format: '<b>{series.name}</b>: {point.y}'
							},
					//showInLegend: true
				},
				series: {
					states: {
						hover: {
							enabled: false
						},
						inactive: {
							opacity: 1
						}
					}
				}
			},tooltip: {
						pointFormat: '{series.name}: <b>{point.y}</b>'
					},
			series: [
				{
					borderWidth: 0,
					name: 'Correctivos y preventivos',
					data: [
						{
							y: parseInt(data.resueltos),
							name: "% Resueltos",
							color: "#369DC9",
						},
						{
							y: parseInt(data.pendientes),
							name: "% Pendientes",
							color: "#2bc155",
						}
					],
					size: '110%',
					innerSize: '70%',
					showInLegend: true,
					dataLabels: {
						enabled: false
					}
				}
			],
			credits: {
				enabled: false
			},exporting: {
						enabled: true,
						buttons: {
							contextButton: {
								menuItems: ["downloadJPEG", "downloadPDF"]
							}
						}
					},
			lang: {
				viewFullscreen:"Ver en pantalla completa",
				downloadPNG:"Descargar imagen PNG",
				downloadJPEG:"Descargar imagen",
				downloadPDF:"Descargar documento PDF",
				downloadSVG:"Descargar vector SVG",
				contextButtonTitle: "Menú"
			},
			legend: {
				//enabled: true,
				layout: 'horizontal',
				align: 'left',
				verticalAlign: 'bottom'
			}
		});
		
		param=param+1;
		loader(param, funciones);
	});
}

function cargarGraficoEstados(tiempo,modulo,idproyectos){ 
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "estados",
			"tipo"	: $("#tipo").val(),
			"tiempo": tiempo,
			"modulo": modulo,
			"idproyectos": idproyectos
		}
	}).done(function(data) {
		var etiquetas = data;
		Highcharts.chart('graf-estados', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: '<b>{series.name}</b>: {point.y}'
					},
					showInLegend: true
				}
			},
			title: {
				text: 'Gráfico de estados' 
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.y}</b>'
			},
			accessibility: {
				point: {
					valueSuffix: '%'
				}
			},
			series: [{
				innerSize: '20%',
				name: 'Nº',
				colorByPoint: true,
				data: etiquetas
			}],
			lang: {
				viewFullscreen:"Ver en pantalla completa",
				downloadPNG:"Descargar imagen PNG",
				downloadJPEG:"Descargar imagen",
				downloadPDF:"Descargar documento PDF",
				downloadSVG:"Descargar vector SVG",
				contextButtonTitle: "Menú"
			},
			exporting: {
				enabled: true,
				buttons: {
					contextButton: {
						menuItems: ["downloadJPEG", "downloadPDF"]
					}
				}
			},
		});
		
		param=param+1;
		loader(param, funciones);
	});
}

function cargarGraficoCategorias(tiempo,modulo,idproyectos){ 
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "categorias",
			"tipo"	: $("#tipo").val(),
			"tiempo": tiempo,
			"modulo": modulo,
			"idproyectos": idproyectos
		}
	}).done(function(data) {
		var categorias = data.categorias;
		var categorias = JSON.parse(data.categorias);
		var valores = data.valores;
		var valores = JSON.parse(data.valores);
		Highcharts.chart('graf-categorias', {
			chart: {
				type: 'column'
			},
			title: {
				text: 'Gráfico de categorías' 
			},
			subtitle: {
				text: ''
			},
			xAxis: {
				categories: categorias,
				crosshair: true
			},
			yAxis: {
				min: 0,
				title: {
					text: ''
				}
			},
			tooltip: {
				headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
					 '<td style="padding:0"><b>{point.y} </b></td></tr>',
				footerFormat: '</table>',
				shared: true,
				useHTML: true
			},
			plotOptions: {
				column: {
					pointPadding: 0.2,
					borderWidth: 0 
				}
			},
			series: valores,
			exporting: {
				enabled: true,
				buttons: {
					contextButton: {
						menuItems: ["downloadJPEG", "downloadPDF"]
					}
				}
			},
			lang: {
				viewFullscreen:"Ver en pantalla completa", 
				downloadPNG:"Descargar imagen PNG",
				downloadJPEG:"Descargar imagen",
				downloadPDF:"Descargar documento PDF",
				downloadSVG:"Descargar vector SVG",
				contextButtonTitle: "Menú"
			}
		});
		
		param=param+1;
		loader(param, funciones);
	});
} 
  
function datosBase(temporalidad){ 
	//Mostrar Fechas  
	$.get("controller/dashboardback.php?opcion=datosBase", { tiempo: temporalidad }, function(result){ 
		$(".datos").text(result);
		if(result != ""){
			$(".div-datos-base").css("padding-top","2.5%");
		}else{
			$(".div-datos-base").css("padding-top","4%");
		}
	});
}

const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
  "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
];

getLongMonthName = function(date) {
    return monthNames[date.getMonth()];
}

let mesactual = getLongMonthName(new Date());
$('#mostrarul').click(function() {
    $('#save').toggle();
	let existe = $("#save").is(":visible");
	if(existe == true){
		$('#morris_donught').css("margin-top:","-18%");
	}
}); 
$("#save").click(function() {
	html2canvas(document.getElementById('fueraservicio')).then(canvas => {
        var w = document.getElementById("morris_donught").offsetWidth;
        var h = document.getElementById("morris_donught").offsetHeight; 
        var img = canvas.toDataURL("image/jpeg", 1);
		//w = w-60;
		//h = h+20;
		w = 300
		h = 400;
        var doc = new jsPDF('L', 'pt', [w, h]);
        doc.addImage(img, 'JPEG', 10, 0, w, h);
        doc.save('graficoequipos.pdf'); 
		clickicon = 0;		
		
    }).catch(function(e) { 
    });
}) 

const preventivosPendientesMes = () =>{ 
	$.ajax({
		type:'POST',
		url:`reportes/dashboardpreventivosexportar.php`,
		data: {
			"tiempo": "mes",
			"modulo": "preventivos",
			"idproyectos": idproyectos
		},
		dataType:'json',
		beforeSend: function() {
			$('#preloader').css('display', 'block');
		},
	}).done(function(data){
		
		var $a = $("<a>");
		$a.attr("href",data.file);
		$("body").append($a);
		$a.attr("download",data.name);
		$a[0].click();
		$a.remove(); 
		$('#preloader').css('display', 'none');
	});
}

//});


