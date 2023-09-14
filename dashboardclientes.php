<?php 
	include_once "funciones.php"; 
	if(!isset($_SESSION['usuario'])) {
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
	<title>Maxia Toolkit | Soporte | Inicio</title>
	<?php linksheader(); ?>
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.theme.css">
	<link rel="stylesheet" type="text/css" href="styles/style.css">
	<link rel="stylesheet" type="text/css" href="styles/framework.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
	<!-- page specific plugin styles -->
	<link rel="stylesheet" href="elFinder/css/elfinder.min.css" />
	<link rel="stylesheet" href="css/dashboard.css">
	<link rel="stylesheet" href="css/jqgrid.css">
	<link rel="stylesheet" href="css/jquery.circliful.css">
	<style>
		.btn-menu {
			width:150px;
		}
		.btn-menu option {
			width:150px;
		}
		.header {
			position: fixed;
			top: 0;
			left: 0;
		}
		.footer {
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100%;
			padding: 0px !important;
		}
		.modal-dialog .card, .cardtable {
			border-radius: 0px!important;
		}
		.tabs {
			margin-top: 0px;
			padding-top: 0px;
		}
		.tab-titles {
			position: fixed;
			width: 100%;
			z-index: 996;
		}
		.card, .cardtable {
			padding-bottom: 0px!important;
			margin-bottom: 0px!important;
		}
		 #page-content, .page-content {
			padding-bottom: 0px!important;
			margin-bottom: 0px!important;
			min-height: 0px!important;
		}
		.content {
			padding-top: 5px;
			/*overflow: auto!important;*/
			padding-bottom: 0px!important;
			margin-bottom: 0px!important;
		}
		.dataTables_wrapper ::-webkit-scrollbar {
			width: 12px;
		} 
		.dataTables_wrapper ::-webkit-scrollbar-track {
			box-shadow: inset 0 0 5px grey; 
			border-radius: 10px;
		} 
		.dataTables_wrapper ::-webkit-scrollbar-thumb {
			background: #267DBD; 
			border-radius: 10px;
		}
		.pagination {
			margin: 0px 0px 0px 0px!important;
		}
		.toggle-vis {
			color: #ffffff!important;
			cursor: pointer;
		}
		div.dataTables_wrapper {
			margin: 0 auto;
		}
		thead
		{
			padding: 0px 0px 0px 0px !important;
			margin: 0px 0px 0px 0px !important;
		}
		
		.dataTables_scrollHead th input[type="text"]
		{
			width:100%!important;
			border-radius: 0px!important;
			background: #1e3d7a!important;
			color:#fff!important;
			border-style: hidden;
		}
		.dataTables_scrollHead th ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
			color: white!important;
			opacity: 1; /* Firefox */
		}
		.dataTables_scrollHead th :-ms-input-placeholder { /* Internet Explorer 10-11 */
			color: white!important;
		}
		.dataTables_scrollHead th ::-ms-input-placeholder { /* Microsoft Edge */
			color: white!important;
		}
		.dataTables_wrapper .dataTables_paginate .paginate_button {
			padding: 0px 0px 0px 0px !important;
			margin: 0px 0px 0px 0px !important;
		}
		.dataTables_wrapper {
			padding: 10px 20px 0px 20px !important;
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
		.select2-choice { 
			width: 100%;
			text-align: left;
			color:#fff!important;
			/*border-style: hidden hidden solid hidden!important;
			border-color:#fff!important;*/
		}
		div.dataTables_wrapper {
			width: 100%;
			margin: 0 auto;
		}
		.infobox {
			margin: 0px 10px 0px 10px!important;
		}
		.infobox > .infobox-data {
			margin: 0 0 0 0!important; 
		}
		.col-sm-1 {
			width: 9.666666%;
		}
	</style>
</head>

<body>
	<div id="page-transitions">
        <?php menusup(); ?>
		<?php menu(); ?>
		<div id="page-content" class="page-content">	
			<div id="page-content-scroll" class=""><!--Enables this element to be scrolled --> 	
				<div class="tabs">
					<div class="tab-titles">
						<a href="dashboard.php" class="active-tab-button uppercase bold" data-tab="tab-1"><i class="fa fa-refresh"></i> Dashboard</a>
						<a href="#" onclick="abrirFiltrosMasivos();" class="uppercase bold"><i class="fa fa-filter"></i> Filtros</a>
						<a href="#" onclick="window.open('controller/incidentessback.php?oper=exportarExcel', '_blank');" class="uppercase bold"><i class="fa fa-file-excel-o"></i> Exportar a Excel</a>
					</div>
				</div>
				<div class="content">
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
				
						<!-- MAPA -->
						<div class="col-xs-6">
							<div class="card mapa">
								<div class="card-content">
									<div>
										<div class="content-menu">
											<div class="tab-menup">
												<button type="button" class="btn-menu active" onclick="mostrarTodos();">Todos</button>
											</div>
											<!--
											<div class="tab-menup">
												<select class="btn-menu" id="cmbProvincias">
													<option value="*">Provincias</option>
													<option value="Bocas del Toro">Bocas del Toro</option>
													<option value="Chiriquí">Chiriquí</option>
													<option value="Coclé">Coclé</option>
													<option value="Colón">Colón</option>
													<option value="Herrera">Herrera</option>
													<option value="Los Santos">Los Santos</option>
													<option value="Panama">Panama</option>
													<option value="Veraguas">Veraguas</option>
												</select>
											</div>-->
											<div class="tab-menup">
												<select class="btn-menu" id="cmbUnidades">
													<option value="CSS">Unidades</option>
													<option value="PAHAA">Complejo Hosp. Dr. Arnulfo Arias Madrid</option>
													<option value="COHMA">Complejo Hosp. Manuel Amador Guerrero</option>
													<option value="BOHAL">Hosp. de Almirante</option>
													<option value="BOHCG">Hosp. Chiriquí Grande (Bocas del Toro)</option>
													<option value="PAHOT">Hosp. de Especialidades Pediatricas Omar Torrijos</option>
													<option value="CHHDA">Hosp. Dionisio Arrocha (Puerto Armuelles)</option>
													<option value="PAHSJ">Hosp. Dra. Susana Jones Cano</option>
													<option value="HEHGC">Hosp. Dr. Gustavo Nelson Collado (Chitré)</option>
													<option value="VEHEA">Hosp. Dr. Ezequiel Abadia (Soná)</option>
													<option value="PAHDI">Hosp. Regional 24 de diciembre</option>
													<option value="PAHCH">Hosp. Regional de Chepo</option>
													<option value="CHHRH">Hosp. Regional Dr. Rafael Hernández</option>
													<option value="CLHRE">Hosp. Regional Dr. Rafael Estévez (Aguadulce)</option>
													<option value="BOHRD">Hosp. Regional Raúl Dávila Mena (Changuinola)</option>
													<option value="BOPGU">Pol. de Guabito</option>
													<option value="CHPEP">Pol. Dr. Ernesto P. Balladares (Boquete)</option>
													<option value="CHPGR">Pol. Gustavo A. Ross (David)</option>
													<option value="CHPPE">Pol. Dr. Pablo Espinosa (Bugaba)</option>
													<option value="CLPMP">Pol. Dr. Manuel Paulino Ocaña (Penonomé)</option>
													<option value="CLPSJ">Pol. San Juan de Dios (Natá)</option>
													<option value="COPHE">Pol. Hugo Spadafora (Colón)</option>
													<option value="COPSA">Pol. de Sabanitas</option>
													<option value="HEPRR">Pol. Roberto Ramírez de Diego (Chitré)</option>
													<option value="LSPMC">Pol. Dr Miguel Cárdenas Barahona (Las Tablas)</option>
													<option value="LSPSJ">Pol. San Juan de Dios (Los Santos)</option>
													<option value="PAPAG">Pol. Don Alejandro De La Guardia Hijo (Bethania)</option>
													<option value="PAPBG">Pol. Dr. Blas Gómez Chetro (Arraiján)</option>
													<option value="PAPCV">Pol. Dr. Carlos N. Brin (San Francisco)</option>
													<option value="PAPGG">Pol. Generoso Guardia (Santa Librada)</option>
													<option value="PAPJJ">Pol. Don Joaquín José Vallarino (Juan Díaz)</option>
													<option value="PAPJV">Pol. Juan Vega Méndez (San Carlos)</option>
													<option value="PAPMF">Pol. Dr. Manuel Ferrer Valdés</option>
													<option value="PAPMM">Pol. Manuel María Valdés (San Miguelito)</option>
													<option value="PAPPR">Pol. Presidente Remón</option>
													<option value="PAPSB">Pol. Santiago Barraza (Chorrera)</option>
													<option value="VEPHD">Pol. Horacio Díaz Gómez (Santiago)</option>
													<option value="CLPMR">Pol. Manuel de Jesús Rojas (Aguadulce)</option>
													<option value="PAUEC">ULAPS Dr. Edilberto Culiolis (Las Cumbres)</option>
													<option value="PAUMH">ULAPS Máximo Herrera (Hipódromo)</option>
													<option value="PAUCA">ULAPS de Capira</option>
													<option value="PAUCV">ULAPS Carlos Velarde (San Cristóbal)</option>
													<option value="LSUGU">ULAPS de Guararé</option>
													<option value="LSUTO">ULAPS de Tonosí</option>
													<option value="COUPO">ULAPS de Portobelo</option>
												</select>
											</div>
											<div class="tab-menup">
												<select class="btn-menu" id="cmbModalidades">
													<option value="*">Modalidades</option>
													<option value="Angiografía">Angiografía</option>
													<option value="Fluoroscopia">Fluoroscopia</option>
													<option value="Mamografía">Mamografía</option>
													<option value="Radiología Convencional">Radiología Convencional</option>
													<option value="Resonancia Magnetica">Resonancia Magnetica</option>
													<option value="Tomografía Computada">Tomografía Computada</option>
													<option value="Ultrasonido">Ultrasonido</option>
												</select>
											</div>
											<!--
											<div class="tab-menup">
												<button type="button" class="btn-menu" onclick="mostrarHospitales();">Hosp.</button>
											</div>
											
											<div class="tab-menup">
												<button type="button" class="btn-menu" onclick="mostrarPoliclinicas();">Pol.</button>
											</div>
											
											<div class="tab-menup">
												<button type="button" class="btn-menu" onclick="mostrarUlaps();">Ulaps</button>
											</div>-->
										</div>
									</div>
									
									<div class="clearfix"></div>
									
									<div class="x_content">
										<div id="map" style="height: 320px;"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-6">	
						<!-- GRAFICOS -->
							<div class="card">
								<div class="card-content">
									<ul class="nav nav-pills nav-pills-primary">
										<li class="active">
											<a href="#graph1" data-toggle="tab">Preventivos</a>
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
				</div>
			</div>
		</div>
	</div>
</body>
<?php linksfooter(); ?>
<!-- page specific plugin scripts -->
<script src="scripts/custom.js"></script>
<script src="scripts/plugins.js"></script>
<script src="../repositorio-lib/elFinder/js/elfinder.min.js" ></script>
<script src="../repositorio-lib/elFinder/js/i18n/elfinder.es.js" ></script>
<!-- page specific plugin scripts -->
<script src="assets/js/graph/js/highcharts.js"></script>
<script src="assets/js/graph/js/highcharts-more.js"></script>
<script src="assets/js/graph/js/highcharts-3d.js"></script>
<script src="assets/js/graph/js/modules/solid-gauge.js"></script>
<script src="assets/js/graph/js/modules/data.js"></script>
<script src="assets/js/graph/js/modules/exporting.js"></script>
<script src="assets/js/graph/js/csv/export-csv.js"></script>
<script>
function initMap() {	
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
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"opcion": "MAPA",
			"agno":$("#filtro-desde").val(),
			"mes":$("#filtro-hasta").val(),
			"unidad": 'CSS',
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
				abrirFicha(xUnidad);
				actualizarTablas(xUnidad);
				tendenciaEstudios(xUnidad);
				agendamientos(xUnidad);
				$("h3#tituloUnidad").html(marker.title.text);
				console.log(marker.title.text);
			});
		});

        //Associate the styled map with the MapTypeId and set it to display.
        map.mapTypes.set('styled_map', styledMapType);
        map.setMapTypeId('styled_map');
      
	});
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC037nleP4v84LrVNzb4a0fn33Ji37zC18&callback=initMap" async defer></script>
<script>
	var pUnidad = '<?php if (isset($_SESSION['unidad'])) echo $_SESSION['unidad']; else echo ''; ?>';
</script>
<script src="js/jquery.circliful.min.js"></script>
<script src="js/dashboard.js"></script>
</html>