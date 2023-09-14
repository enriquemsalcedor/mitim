<?php 
	include_once "funciones.php";
	if(!(isset($_SESSION['usuario']) || isset($_SESSION['empresa']))) {
		header("Location: index.php");
		exit;
	}
?>
<!DOCTYPE HTML>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
	<link rel="apple-touch-icon" sizes="76x76" href="images/favicon.png" />
	<link rel="icon" type="image/png" href="images/favicon.png" />
	<title>Maxia Toolkit | Soporte | Incidentes</title>
	<?php linksheader(); ?>
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.theme.css">
	<link rel="stylesheet" type="text/css" href="styles/style.css">
	<link rel="stylesheet" type="text/css" href="styles/framework.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
	<link rel="stylesheet" href="css/dashboard.css">
	<link rel="stylesheet" href="css/jquery.circliful.css">
	<!-- elFinder -->
	<link rel="stylesheet" href="elFinder/css/elfinder.min.css" />
	<link rel="stylesheet" href="../repositorio-lib/elFinder/themes/Material/theme-gray.css">
	<!-- DataTables -->
	<link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/datatables/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/material/css/dataTables.material.min.css">
	<!-- <link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/material.min.css"> -->
	<link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/select/css/select.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/colreorder/css/colReorder.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/fixedheader/css/fixedHeader.dataTables.min.css">
	<style>
		/* width */
		body ::-webkit-scrollbar {
			width: 10px;
		}
		/* Track */
		body ::-webkit-scrollbar-track {
			box-shadow: inset 0 0 2px grey;
			border-radius: 10px;
		}
		/* Handle */
		body ::-webkit-scrollbar-thumb {
			background: #555555; 
			border-radius: 10px;
		}
		::-webkit-scrollbar { 
		   display: block;
		   height: 12px;
		}
		.footer {
			padding: 0px;
			clear: both;
			width: 100%;
			margin: 0 auto;
			position: fixed !important;
			bottom: 0 !important;
			z-index: 100;
			background-color: #1e3d7a;
		}
		.toggle-vis {
			color: #ffffff!important;
			cursor: pointer;
		}
		.dataTables_wrapper .dataTables_paginate .paginate_button {
			padding: 0px 0px 0px 0px !important;
			margin: 0px 0px 0px 0px !important;
		}
		::-webkit-scrollbar { 
		   display: block;
		   height: 12px;
		}
		.child li {
			text-align: left!important;
		}
		table.dataTable,
		table.dataTable th,
		table.dataTable td {
		  -webkit-box-sizing: content-box;
		  -moz-box-sizing: content-box;
		  box-sizing: content-box;
		}
		table.dataTable thead .sorting {
			background-image: url('')!important;
		}
		table.dataTable thead .sorting_asc {
			background-image: url('')!important;
		}
		table.dataTable thead .sorting_desc {
			background-image: url('')!important;
		}	
		.select2-container--default .select2-selection--single .select2-selection__rendered{
		    color: #FFFFFF;
		    font-weight: initial;
		} 
		th > .select2-container{
		    width: 84% !important;
		}
		.iconcalfdesde, .iconcalfhasta{ cursor: pointer;}
		#desdef, #hastaf{ width: 95%; float: left;}
		
		.infobox {
			margin: 0px 10px 0px 10px;
		}
		.infobox > .infobox-data {
			margin: 0 0 0 0!important; 
		}
		.col-sm-1 {
			width: 9.666666666%;
		}
		.col-sm-13 {
			width: 15.25%;
		}
	</style>
</head>
<body>	
    <div id="page-transitions">
	    <?php menusupclientes(); ?>
		<?php menuclientes(); ?>
		<div id="menu-4" data-menu-size="600" class="menu-wrapper menu-light menu-bottom menu-large" style="">
			<div class="menu-scroll">
				<div class="menu">
					<em class="menu-divider">DASHBOARD<a href="javascript:pruebaDivAPdf()" class="button">Imprimir</a><i class="fa fa-navicon"></i></em>
					<div class="content" style="padding-left: 30px!important;padding-right: 30px!important;">
					<!-- AÑOS INDICADORES -->
						<div class="col-xs-6">
							<div class="content-fullscreen">
								<div class="row">
									<div class='col-xs-12 col-sm-13 box-year-tipo infobox infobox-blue' style="margin: 0px 5px 0px 5px">
										<div class='infobox-data'>
											<div class='infobox-content'><span id="txtNIncidentes"></span></div>
											<div id="txtIncidentes"></div>
										</div>
									</div>
									<div class='col-xs-12 col-sm-13 box-year-tipo infobox infobox-blue' style="margin: 0px 5px 0px 5px">
										<div class='infobox-data'>
											<div class='infobox-content'><span id="txtNIncidentesResueltos"></span></div>
											<div id="txtIncidentesResueltos"></div>
										</div>
									</div>
									<div class='col-xs-12 col-sm-13 box-year-tipo infobox infobox-blue' style="margin: 0px 5px 0px 5px">
										<div class='infobox-data'>
											<div class='infobox-content'><span id="txtNIncidentesPendientes"></span></div>
											<div id="txtIncidentesPendientes"></div>
										</div>
									</div>
									<div class='col-xs-12 col-sm-13 box-year-tipo infobox infobox-blue' style="margin: 0px 5px 0px 5px">
										<div class='infobox-data'>
											<div class='infobox-content'><span id="txtNPreventivos"></span></div>
											<div id="txtPreventivos"></div>
										</div>
									</div>
									<div class='col-xs-12 col-sm-13 box-year-tipo infobox infobox-blue' style="margin: 0px 5px 0px 5px">
										<div class='infobox-data'>
											<div class='infobox-content'><span id="txtNPreventivosRealizados"></span></div>
											<div id="txtPreventivosRealizados"></div>
										</div>
									</div>
									<div class='col-xs-12 col-sm-13 box-year-tipo infobox infobox-blue' style="margin: 0px 5px 0px 5px">
										<div class='infobox-data'>
											<div class='infobox-content'><span id="txtNPreventivosPendientes"></span></div>
											<div id="txtPreventivosPendientes"></div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="row">&nbsp;</div>
								<div class="row">
										<div class="content-fullscreen">
											<div class="chart" id="tab-graficos-col1">
												<div id="ct-tendcategorias1"></div>
											</div>
										</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="col-xs-6">
								<div class="content-fullscreen">
									<div class="chart" id="tab-graficos-col2">
										<div id="ct-tendcategorias"></div>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="clear"></div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
					<em class="menu-divider">Equipos abajo<i class="fa fa-navicon"></i></em>
					<div class="content" style="padding-left: 20px!important;padding-right: 20px!important;">
						<?php timedown(); ?>
						<div class="clear"></div>
					</div>
					<em class="menu-divider">Disponibilidad de Equipos<i class="fa fa-navicon"></i></em>
					
					<div class="card cardtable" >
					<!-- AÑOS -->
						<div class="col-xs-12">
							<div class="card years">
								<div class="card-content">
									<ul class="nav nav-pills nav-pills-primary">
										<li class="year-one active active-all">
											<a href="#pane-2018" data-toggle="tab" id="tab-2018" class="tab-years">2018</a>
										</li>
										<li class="year-two">
											<a href="#pane-2017" data-toggle="tab" id="tab-2017" class="tab-years">2017</a>
										</li>
										<li class="year-three">
											<a href="#pane-2016" data-toggle="tab" id="tab-2016" class="tab-years">2016</a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active active-all" id="pane-2018">
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>Time Up Total</div>
													<div id="txtTotal"></div>
												</div>
											<!--<div class='infobox-data infobox-footer'>
													<p class='year-footer' id='totaln'>2018</p>
												</div>-->
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>RX</div>
													<div id="txtRx"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>RM</div>
													<div id="txtRm"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>MG</div>
													<div id="txtMg"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>TC</div>
													<div id="txtTc"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>US</div>
													<div id="txtUs"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>AG</div>
													<div id="txtAg"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>FL</div>
													<div id="txtFl"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>DE</div>
													<div id="txtDe"></div>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="tab-pane" id="pane-2017">
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>Time Up Total</div>
													<div id="txtTotal2"></div>
												</div>
											<!--<div class='infobox-data infobox-footer'>
													<p class='year-footer' id='totaln'>2018</p>
												</div>-->
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>RX</div>
													<div id="txtRx2"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>RM</div>
													<div id="txtRm2"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>MG</div>
													<div id="txtMg2"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>TC</div>
													<div id="txtTc2"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>US</div>
													<div id="txtUs2"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>AG</div>
													<div id="txtAg2"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>FL</div>
													<div id="txtFl2"></div>
												</div>
											</div><div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>DE</div>
													<div id="txtDe2"></div>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="tab-pane" id="pane-2016">
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>Time Up Total</div>
													<div id="txtTotal3"></div>
												</div>
											<!--<div class='infobox-data infobox-footer'>
													<p class='year-footer' id='totaln'>2018</p>
												</div>-->
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>RX</div>
													<div id="txtRx3"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>RM</div>
													<div id="txtRm3"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>MG</div>
													<div id="txtMg3"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>TC</div>
													<div id="txtTc3"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>US</div>
													<div id="txtUs3"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>AG</div>
													<div id="txtAg3"></div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>FL</div>
													<div id="txtFl3"></div>
												</div>
											</div><div class='col-xs-12 col-sm-1 box-year-tipo infobox infobox-blue'>
												<div class='infobox-data'>
													<div class='infobox-content'>DE</div>
													<div id="txtDe3"></div>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="content" style="padding-left: 20px!important;padding-right: 20px!important;">
								<div class="content-fullscreen">
									<div class="content">
										<div class="card">
											<div class="card-content">
												<ul class="nav nav-pills nav-pills-primary">
													<li class="active">
														<a href="#graph1" data-toggle="tab">Por Categor&iacute;a</a>
													</li>
													<li>
														<a href="#graph2" data-toggle="tab">Preventivos / Correctivos</a>
													</li>
													<li>
														<a href="#graph3" data-toggle="tab">Por Categor&iacute;a</a>
													</li>
													<li>
														<a href="#graph4" data-toggle="tab">Por Marca</a>
													</li>
													<li>
														<a href="#graph5" data-toggle="tab">Por Modalidad</a>
													</li>
												</ul>
												<div class="tab-content" id="tab-graficos">
													<div class="tab-pane active" id="graph1">
														<div class="chart">
															<div id="ct-preventivos"></div>
														</div>
													</div>
													<div class="tab-pane" id="graph2">
														<div class="chart">
															<div id="ct-correctivos"></div>
														</div>
													</div>
													<div class="tab-pane" id="graph3">
														<div class="chart">
															<div id="ct-correctivos2"></div>
														</div>
													</div>
													<div class="tab-pane" id="graph4">
														<div class="chart">
															<div id="ct-correctivos3"></div>
														</div>
													</div>
													<div class="tab-pane" id="graph5">
														<div class="chart">
															<div id="ct-correctivos4"></div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
								<div class="clear"></div>
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
		<div id="page-content" class="page-content">
			
			<div id="page-content-scroll" class=""><!--Enables this element to be scrolled --> 	
				
				<div class="tabs">
					<div class="tab-titles">
						<a href="incidentes.php" class="uppercase" data-tab="tab-1"><i class="fa fa-refresh"></i> Incidentes</a>
						<a href="#" onclick="abrirdialogIncidenteNuevo();" class="uppercase"><i class="fa fa-plus-circle"></i> Nuevo</a>
						<a href="#" onclick="abrirFiltrosMasivos();" class="uppercase"><i class="fa fa-filter"></i> Filtros</a>
						<a data-deploy-menu="notification-top-1" class="uppercase dismiss-with-button" href="#" class="uppercase"><i class="fa fa-filter"></i> COLUMNAS</a>
						<a href="#" onclick="window.open('controller/incidentesback.php?oper=exportarExcel', '_blank');" class="uppercase"><i class="fa fa-file-excel-o"></i> Exportar a Excel</a>
						<a href="#" onclick="window.open('controller/incidentesback.php?oper=exportarExcelConComentarios', '_blank');" class="uppercase"><i class="fa fa-file-excel-o"></i> Exportar a Excel con Comentarios</a>
					</div>
				</div> 
				<div class="content">
					<div class="modal-container">
						<!--<img src="assets/img/loader-rolling.png" class="loader-maxia">-->
					</div>
					<div class="col-md-12">
						<table id="tablaincidentes" class="mdl-data-table display nowrap table-striped" width="100%">
							<thead>
								<tr>
									<th>-</th>
									<th></th>
									<th>Id</th>
									<th>Estado</th>
									<th>Titulo</th>
									<th>Solicitante</th>
									<th>Creación</th>
									<th>Hora creación</th>
									<th>Empresa</th>
									<th>Dep. / Grupo</th>
									<th>Cliente</th>
									<th>Proyecto</th>
									<th>Categoría</th>									
									<th>Sub Categoría</th>									
									<th>Asignado a</th>
									<th>Sitio</th>
									<th>Modalidad</th>
									<th>Serie</th>
									<th>Marca</th>
									<th>Modelo</th>
									<th>Prioridad</th>
									<th>Cierre</th>
								</tr>
							</thead>									
						</table>
					</div>
					<!-- SOLICIUDES DE SERVICIO -->
					<div id="dialog-form-sol" style="display:none" class="swal2-show" style="display: block; padding: 20px; background: rgb(255, 255, 255); min-height: 171px;" tabindex="-1">
						<h2>Reportes de Servicios y Evidencias</h2>
						<div id="elfinder"></div>
						<div class="col-xs-12 text-center">
							<button type="button" class="swal-confirm btn btn-success" onclick="cerrarDialogSol();">Aceptar</button>
							<button type="button" class="swal-cancel btn btn-danger" onclick="cerrarDialogSol();">Cerrar</button>
						</div>
						<br>
					</div>
					<!-- ADJUNTO DE COMENTARIOS -->
					<div id="dialog-form-adj" style="display:none" class="swal2-show" style="display: block; padding: 20px; background: rgb(255, 255, 255); min-height: 171px;" tabindex="-1">
						<h2>Adjuntos de comentarios</h2>
						<div id="elfinderAdj"></div>
						<div class="col-xs-12 text-center">
							<button type="button" class="swal-confirm btn btn-success" onclick="cerrarDialogAdj();">Aceptar</button>
							<button type="button" class="swal-cancel btn btn-danger" onclick="cerrarDialogAdj();">Cerrar</button>
						</div>
						<br>
					</div>
					<!-- FORMULARIO INCIDENTES NUEVO -->
					<?php include_once "incidentes-nuevo-clientes.php"; ?>
					
					<!-- FORMULARIO INCIDENTES EDITAR -->
					<?php include_once "incidentes-editar.php"; ?>
					
					<!-- FORMULARIO INCIDENTES MASIVO -->
					<?php include_once "incidentes-masivo.php"; ?>
					
					<!-- FUSIÓN -->
					<?php include_once "incidentes-fusion.php"; ?>
					
					<!-- FORMULARIO FILTROS MASIVOS -->
					<?php include_once "incidentes-filtrosmasivos.php"; ?>					
				</div>
				<div class="footer footer-dark">
					<p class="copyright-text">Copyright &copy; Maxia Latam <span id="copyright-year">2018</span>. All Rights Reserved.</p>
				</div>			
			</div>  	
		</div>
		<div id="notification-top-1" data-menu-size="120" class="menu-wrapper menu-top" style="display:none;z-index: 998;margin-top: 56px;">
			<div class="content tab-titles">				
				<a class="toggle-vis" id="c5" data-column="5">Solicitante</a> 
				<a class="toggle-vis" id="c6" data-column="6">Creaci&oacute;n</a>
				<a class="toggle-vis" id="c7" data-column="7">Hora C.</a>
				<a class="toggle-vis" id="c9" data-column="9">Departamento</a>
				<a class="toggle-vis" id="c10" data-column="10">Cliente</a>
				<a class="toggle-vis" id="c11" data-column="11">Proyecto</a> 
				<a class="toggle-vis" id="c12" data-column="12">Categor&iacute;a</a> 
				<a class="toggle-vis" id="c13" data-column="13">Subcategoria</a> 				
				<a class="toggle-vis" id="c14" data-column="14">Asignado</a>
				<a class="toggle-vis" id="c15" data-column="15">Sitio</a>
				<a class="toggle-vis" id="c16" data-column="16">Modalidad</a>
				<a class="toggle-vis" id="c17" data-column="17">Serie</a>
				<a class="toggle-vis" id="c18" data-column="18">Marca</a>
				<a class="toggle-vis" id="c19" data-column="19">Modelo</a>
				<a class="toggle-vis" id="c20" data-column="20">Prioridad</a>
				<a class="toggle-vis" id="c21" data-column="21">Cierre</a>	
				<a href="#" class="close-menu"><i class="fa fa-times"></i></a>
			</div>
		</div>
	</div>
<?php linksfooter(); ?>
<!-- page specific plugin scripts -->
<script src="scripts/custom.js"></script>
<script src="scripts/plugins.js"></script>
<script src="../repositorio-lib/elFinder/js/elfinder.min.js" ></script>
<script src="../repositorio-lib/elFinder/js/i18n/elfinder.es.js" ></script>
<script src="js/vwclientes-v2.js?"+rand()+"" ></script>
<script src="js/vwclientes-nuevo.js?"+rand()+"" ></script>
<script src="js/vwclientes-editar.js?"+rand()+"" ></script>
<script src="js/vwclientes-filtrosmasivos.js?"+rand()+"" ></script>
<script src="../repositorio-lib/uploader-master/dist/js/jquery.dm-uploader.min.js"></script>
<!-- page specific plugin scripts -->
<script src="assets/js/graph/js/highcharts.js"></script>
<script src="assets/js/graph/js/highcharts-more.js"></script>
<script src="assets/js/graph/js/highcharts-3d.js"></script>
<script src="assets/js/graph/js/modules/solid-gauge.js"></script>
<script src="assets/js/graph/js/modules/data.js"></script>
<script src="assets/js/graph/js/modules/exporting.js"></script>
<script src="assets/js/graph/js/csv/export-csv.js"></script>
<script src="comentarios/demo-ui.js"></script>
<script src="comentarios/demo-config.js"></script>
<!--  DataTables.net   -->
<script type="text/javascript" language="javascript" src="../repositorio-tema/assets/datatables/datatables/js/jquery.dataTables19.min.js"></script>
<script type="text/javascript" language="javascript" src="../repositorio-tema/assets/datatables/datatables/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" language="javascript" src="../repositorio-tema/assets/datatables/material/js/dataTables.material.min.js"></script>
<script type="text/javascript" language="javascript" src="../repositorio-tema/assets/datatables/select/js/dataTables.select.min.js"></script>
<script type="text/javascript" language="javascript" src="../repositorio-tema/assets/datatables/colreorder/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript" language="javascript" src="../repositorio-tema/assets/datatables/fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script>
	var pUnidad = '<?php if (isset($_SESSION['unidad'])) echo $_SESSION['unidad']; else echo ''; ?>';
</script>

<script src="js/jquery.circliful.min.js"></script>
<script src="js/dashboardclientes.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
<script>
    function pruebaDivAPdf() {
        var pdf = new jsPDF('p', 'pt', 'letter');
        source = $('#menu-4')[0];

        specialElementHandlers = {
            '#bypassme': function (element, renderer) {
                return true
            }
        };
        margins = {
            top: 80,
            bottom: 60,
            left: 40,
            width: 522
        };

        pdf.fromHTML(
            source, 
            margins.left, // x coord
            margins.top, { // y coord
                'width': margins.width, 
                'elementHandlers': specialElementHandlers
            },

            function (dispose) {
                pdf.save('Prueba.pdf');
            }, margins
        );
    }
</script>
</body>
</html>