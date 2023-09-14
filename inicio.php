<?php 
	include_once "funciones.php"; 
	if(empty($_SESSION['usuario'])) {
		header("Location: index.php");
		exit;
	}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
<link rel="apple-touch-icon" sizes="76x76" href="images/favicon.png" />
<link rel="icon" type="image/png" href="images/favicon.png" />
<title>Maxia Toolkit | Soporte | Inicio</title>
<link rel="stylesheet" type="text/css" href="styles/style.css">
<link rel="stylesheet" type="text/css" href="styles/framework.css">
<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
<style>
.img-list-round {border-radius:100% !important; overflow: hidden;}
.text-derecha  {text-align:right !important;}
.header-logo-app .header-title {/*left:10px !important;*/}
.header-dark {background-color: #004473 !important;}
.bg-pem {
	background-color: #004473 !important;
	color: #FFFFFF !important;
	text-transform: none !important;
	font-family: 'Verdana' !important;
}
.footer-dark {
    background-color: #004473 !important;
	color: #FFFFFF !important;
}
.footer .copyright-text {
    opacity: 1!important;
    font-size: 11px!important;
	color: #ffffff!important;
}
</style>

</head>

<body>

<div id="page-transitions">
	<?php menusup(); ?>
	<?php menu(); ?>
	<div id="page-content" class="page-content">	
		<div id="page-content-scroll" class="header-clear-larger"><!--Enables this element to be scrolled --> 	
			<div class="content">
				<div class="timeline-cover preload-image" data-src="images/pictures/8.jpg">
					<div class="timeline-header">
						<a href="#" class="back-button"><i class="fa fa-angle-left"></i></a> 
						<a href="index.html" class="timeline-logo"></a>
						<a href="#" class="menu-icon hamburger-animated" data-deploy-menu="menu-1"></a>
					</div>
					<div class="overlay overlay-dark"></div>
					<div class="content">
						<h1 class="timeline-heading color-white thiner no-bottom">Puesta en Marcha (PEM)</h1>
						<span class="timeline-sub-heading color-white small-text">No administramos proyectos, los hacemos realidad</span>
						<div class="timeline-image preload-image" src="images/empty.png" alt="img" data-src="images/pictures/0s.png"></div>
					</div>
				</div>
				<div class="timeline-body">
					<div class="timeline-deco"></div>
					<div class="timeline-item">
						<a href="dashboard.php">
						<i class="fa fa-bar-chart-o scale-hover">I</i>
						<div class="timeline-item-content">
							<h5 class="thiner">
								DASHBOARD ESTAD&Iacute;TICO
							</h5>
							<p>&nbsp;</p>
						</div>
						</a>
					</div>
					<div class="timeline-item">
						<a href="gestordoc.php">
						<i class="fa fa-book scale-hover">I</i>
						<div class="timeline-item-content">
							<h5 class="thiner">
								GESTOR DOCUMENTAL
							</h5>
							<p>&nbsp;</p>
						</div>
						</a>
					</div>
					<div class="timeline-item">
						<a href="calendario.php">
						<i class="fa fa-calendar scale-hover"></i>
						<div class="timeline-item-content">
							<h5 class="thiner">
								CALENDARIO
							</h5>
							<p>&nbsp;</p>
						</div>
						</a>
					</div>
					<div class="timeline-item">
						<a href="actividades.php?f=0">
						<i class="bg-pem scale-hover">0</i>
						<div class="timeline-item-content">
							<h5 class="thiner">
								ALISTAMIENTO 
							</h5>
							<p>Cantidad de Actividades: <strong>15</strong> | Porcentaje de Avance: <strong>0%</strong></p>
						</div>
						</a>
					</div>	
					<div class="timeline-item">
						<a href="actividades.php?f=1">
						<i class="bg-pem scale-hover">I</i>
						<div class="timeline-item-content">
							<h5 class="thiner">
								INFRAESTRUCTURA Y OBRA CIVIL 
							</h5>
							<p>Cantidad de Actividades: <strong>203</strong> | Porcentaje de Avance: <strong>45%</strong></p>
						</div>
						</a>
					</div>	

					<div class="timeline-item">
						<a href="equipamiento.php?f=2">
						<i class="bg-pem scale-hover">II</i>
						<div class="timeline-item-content">
							<h5 class="thiner">
								EQUIPAMIENTO
							</h5>
							<p>Cantidad de Actividades: <strong>250</strong> | Porcentaje de Avance: <strong>15%</strong></p>
						</div>
						</a>
					</div>	
					
					<div class="timeline-item">
						<a href="actividades.php?f=3">
						<i class="bg-pem scale-hover">III</i>
						<div class="timeline-item-content">
							<h5 class="thiner one-two">
								LOG&Iacute;STICA DE EQUIPOS, INSUMOS Y RRHH
							</h5>
							<p>Cantidad de Actividades: <strong>123</strong> | Porcentaje de Avance: <strong>18%</strong></p>
						</div>
						</a>
					</div>	
					
					<div class="timeline-item">
						<a href="actividades.php?f=6">
						<i class="bg-pem scale-hover">VIII</i>
						<div class="timeline-item-content">
							<h5 class="thiner">
								INTEGRACIONES
							</h5>
							<p>Cantidad de Actividades: <strong>80</strong> | Porcentaje de Avance: <strong>0%</strong></p>
						</div>
						</a>
					</div>	
					
					<div class="timeline-item">
						<a href="actividades.php?f=4">
						<i class="bg-pem scale-hover">IV</i>
						<div class="timeline-item-content">
							<h5 class="thiner">
								GESTI&Oacute;N DE CAMBIO
							</h5>
							<p>Cantidad de Actividades: <strong>50</strong> | Porcentaje de Avance: <strong>15%</strong></p>
						</div>
						</a>
					</div>
					
					<div class="timeline-item">
						<a href="actividades.php?f=5">
						<i class="bg-pem scale-hover">V</i>
						<div class="timeline-item-content">
							<h5 class="thiner">
								MERCADEO Y RELACIONES P&Uacute;BLICAS
							</h5>
							<p>Cantidad de Actividades: <strong>28</strong> | Porcentaje de Avance: <strong>36%</strong></p>
						</div>
						</a>
					</div>
					
					<div class="timeline-item">
						<a href="incidentes.php">
						<i class="bg-pem scale-hover">VI</i>
						<div class="timeline-item-content">
							<h5 class="thiner">
								SOPORTE Y MANTENIMIENTO
							</h5>
							<p>Cantidad de Actividades: <strong>145</strong> | Porcentaje de Avance: <strong>0%</strong></p>
						</div>
						</a>
					</div>
					
				</div>
			</div>
			
			<div class="footer footer-dark">
				<a href="#" class="footer-logo"></a>
				<p class="copyright-text">Copyright &copy; Maxia Latam <span id="copyright-year">2018</span>. All Rights Reserved.</p>
			</div>
			
		</div>  
	</div>
	
	<a href="#" class="back-to-top-badge back-to-top-small"><i class="fa fa-angle-up"></i>Back to Top</a>
	<!-- Menu Header -->
	<div id="menu-5" data-menu-size="440" class="menu-wrapper menu-light menu-top menu-large">
		<div class="menu-scroll">
			<div class="menu">
				<em class="menu-divider">Filtros<i class="fa fa-navicon"></i></em>
				<div class="content" style="padding-left: 20px!important;padding-right: 20px!important;">
					<div class="input-simple-1 has-icon input-green full-bottom"><em>Nro. Incidente:</em><i class="fa fa-list_ol"></i><input type="number" id="numero" name="numero" value=''></div>
					<div class="input-simple-1 has-icon input-green full-bottom"><em>Desde:</em><i class="fa fa-calendar"></i><input type="date" id="desde" name="desde" value=''></div>
					<div class="input-simple-1 has-icon input-green full-bottom"><em>Hasta:</em><i class="fa fa-calendar"></i><input type="date" id="hasta" name="hasta" value=''></div>
				
					<div class="select-box select-box-1">
						<em>Unidad Ejecutora:</em>
						<?php //filtroUnidades(); ?>
					</div>
					<div class="select-box select-box-1">
						<em>Proyecto:</em>
						<?php //filtroProyectos(); ?>
					</div>
					<div class="select-box select-box-1">
						<em>Categoria:</em>
						<?php //filtroCategorias(); ?>
					</div>
					<div class="select-box select-box-1">
						<em>Estado:</em>
						<?php //filtroEstados(); ?>
					</div>
					<div class="select-box select-box-1">
						<em>Asignado a:</em>
						<?php //filtroAsignadoa(); ?>
					</div>
					<div class="select-box select-box-1">
						<em>Modalidad:</em>
						<?php //filtroModalidad(); ?>
					</div>
					<div class="select-box select-box-1">
						<em>Equipo:</em>
						<?php //filtroEquipo(); ?>
					</div>
					
					<div class="clear"></div>
				</div>

				<div class="content demo-buttons">
					<a href="javascript:filtrar();" class="button button-full button-round button-blue-3d button-green uppercase ultrabold close-menu">Filtrar</a>
					<a href="inicio.php" class="button button-full button-round button-blue-3d button-blue uppercase ultrabold close-menu">Limpiar Filtros</a>
					<a href="#" class="button button-full button-round button-red-3d button-red uppercase ultrabold close-menu">Cerrar</a>
				</div>
			</div>
		</div>
	</div>
	<div id="menu-4" data-menu-size="440" class="menu-wrapper menu-light menu-top menu-large">
		<div class="menu-scroll">
			<div class="menu">
				<em class="menu-divider">Equipos Abajo<i class="fa fa-navicon"></i></em>
				<div class="content" style="padding-left: 20px!important;padding-right: 20px!important;">
					<?php //timedown(); ?>
					<div class="clear"></div>
				</div>
				<em class="menu-divider">Disponibilidad de Equipos<i class="fa fa-navicon"></i></em>
				<div class="content" style="padding-left: 20px!important;padding-right: 20px!important;">
					<div class="content-fullscreen">
						<div class="content">
							<canvas class="chart" id="pie-chart"/></canvas>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="content demo-buttons">
					<a href="#" class="button button-full button-round button-red-3d button-red uppercase ultrabold close-menu">Cerrar</a>
				</div>
			</div>
		</div>
	</div>
</div>
	

<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript" src="scripts/custom.js"></script>
<script type="text/javascript" src="scripts/plugins.js"></script>
<script>
jQuery(function($) {
	
});

function getvalfiltrocategorias() {
	var proyecto = $("#filtroProyectos").val();
	$("#filtroCategorias").empty();
	$.get( "combosback.php?oper=categorias&idproyecto=" + proyecto, { onlydata:"true" }, function(result){ 
		$("#filtroCategorias").append(result);
		//$("#proyecto").select2();
	});
}

function getvalfiltromodalidades() {
	var unidad = $("#filtroUnidades").val();
	$("#filtroModalidad").empty();
	$.get( "combosback.php?oper=modalidades&unidad=" + unidad, { onlydata:"true" }, function(result){ 
		$("#filtroModalidad").append(result);
		//$("#proyecto").select2();
	});
}

function getvalfiltroequipos() {
	var unidad = $("#filtroUnidades").val();
	var modalidad = $("#filtroModalidad").val();
	$("#filtroEquipo").empty();
	$.get( "combosback.php?oper=equipos&unidad=" + unidad + "&modalidad=" + modalidad, { onlydata:"true" }, function(result){ 
		$("#filtroEquipo").append(result);
		//$("#proyecto").select2();
	});
}

function filtrar() {
	$.ajax({
		type: 'post',
		url: 'incidentesback.php',
		data: { 
			'oper'	: 'incidentes',
			'numero' : $("#numero").val(),
			'desde' : $("#desde").val(),
			'hasta' : $("#hasta").val(),
			'unidad' : $("#filtroUnidades").val(),
			'proyecto' : $("#filtroProyectos").val(),
			'categoria' : $("#filtroCategorias").val(),
			'estado' : $("#filtroEstados").val(),
			'asignadoa' : $("#filtroAsignadoa").val(),
			'modalidad' : $("#filtroModalidad").val(),
			'equipo' : $("#filtroEquipo").val()
		},
		success: function (response) {
			$('#tab-1').fadeOut(500);
			$('#tab-1').html(response);
			$('#tab-1').fadeIn(500);
		},
		error: function () {
			swal({title: "Error",text: "Ocurrió un error al aplicar los filtros de incidentes!", type: "error"});
		}
	});
	
	$.ajax({
		type: 'post',
		url: 'incidentesback.php',
		data: { 
			'oper'	: 'preventivos',
			'numero' : $("#numero").val(),
			'desde' : $("#desde").val(),
			'hasta' : $("#hasta").val(),
			'unidad' : $("#filtroUnidades").val(),
			'proyecto' : $("#filtroProyectos").val(),
			'categoria' : $("#filtroCategorias").val(),
			'estado' : $("#filtroEstados").val(),
			'asignadoa' : $("#filtroAsignadoa").val(),
			'modalidad' : $("#filtroModalidad").val(),
			'equipo' : $("#filtroEquipo").val()
		},
		success: function (response) {
			$('#tab-2').fadeOut(500);
			$('#tab-2').html(response);
			$('#tab-2').fadeIn(500);
		},
		error: function () {
			swal({title: "Error",text: "Ocurrió un error al aplicar los filtros de preventivos!", type: "error"});
		}
	});
	
	if ($("#filtroEquipo").val()!='') {
		$.ajax({
			type: 'post',
			url: 'incidentesback.php',
			data: { 
				'oper'	: 'equipos',
				'desde' : $("#desde").val(),
				'hasta' : $("#hasta").val(),
				'equipo' : $("#filtroEquipo").val()
			},
			success: function (response) {
				$('#tab-3').fadeOut(500);
				$('#tab-3').html(response);
				$('#tab-3').fadeIn(500);
			},
			error: function () {
				swal({title: "Error",text: "Ocurrió un error al aplicar los filtros de equipos!", type: "error"});
			}
		});	
	}
}
</script>
</body>