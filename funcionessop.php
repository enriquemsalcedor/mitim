<?php
/*
Á	&Aacute;	\u00C1
á	&aacute;	\u00E1
É	&Eacute;	\u00C9
é	&eacute;	\u00E9
Í	&Iacute;	\u00CD
í	&iacute;	\u00ED
Ó	&Oacute;	\u00D3
ó	&oacute;	\u00F3
Ú	&Uacute;	\u00DA
ú	&uacute;	\u00FA
Ü	&Uuml;		\u00DC
ü	&uuml;		\u00FC
Ṅ	&Ntilde;	\u00D1
ñ	&ntilde;	\u00F1
*//*
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
if(!isset($_SESSION['usuario'])  && $_SERVER['PHP_SELF'] !='/lnjnew/index.php'){
	header('Location: index.php');
	exit;
}*/

include_once("conexion.php");

function verificarSesion() {
	if (!isset($_SESSION['nivel']))
		cerrarSesion();
}

if (isset($_REQUEST['opcion'])) {
	$opcion = $_REQUEST['opcion'];
	if ($opcion=='LO')
		cerrarSesion();
}
	
function cerrarSesion() {
	session_unset();
	session_destroy();
	header('Location: index.php');
}

function configuracionCorreo(){
	
	require_once('phpmailer/PHPMailerAutoload.php');
	$mail = new PHPMailer;
	
	$mail->isSMTP();							// Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';				// Specify main and backup SMTP servers
	$mail->SMTPAuth = true;						// Enable SMTP authentication
	$mail->Username = 'lismary.18@gmail.com';	// SMTP username
	$mail->Password = 'MarCP17*';				// SMTP password
	$mail->SMTPSecure = 'ssl';					// Enable TLS encryption, `ssl` also accepted
	$mail->Port = 465;							// TCP port to connect to
}

function buscarFila($mod) {
	global $tabla;
	$i = 0;
	$enc = false;
	while ($i<count($tabla) && !$enc) {
		if ($tabla[$i]['modalidad']==$mod)
			$enc = true;
		else
			$i++;
	}
	if (!$enc) $i=-1;
	
	return $i;
}

function infoEstadisticaAgno($txt, $id) {
	echo "
		<div class='infobox infobox-blue'>
			<div class='infobox-icon'>
				<i class='ace-icon fa fa-calendar'></i>
			</div>
			<div class='infobox-data'>
				<span class='infobox-data-number' id='$id'></span>
				<div class='infobox-content'>$txt</div>
			</div>
		</div>
	";
}

function infoEstadistica($txt, $id, $idPorca, $idPorcb, $color, $tipo, $year) {
	if( $tipo == 'year' ){
		echo "
			<div class='col-xs-12 col-sm-3 box-year infobox infobox-$color'>
				<div class='infobox-data'>
					<div class='infobox-content'>$txt</div>
				</div>
				<div class='infobox-progress'>
					<div class='easy-pie-chart percentage' data-percent='' data-size='100' id='$idPorcb'>
						<span class='percent' id='$idPorca'></span>
					</div>
				</div>
				<div class='infobox-data infobox-footer'>
					<span class='infobox-data-number' id='$id'></span>
					<p class='year-footer'>".$year."</p>
				</div>
			</div>
		";
	}else{
		echo "
			<div class='col-xs-12 col-sm-2 box-year-tipo infobox infobox-$color'>
				<div class='infobox-data infotipos'>
					<div class='infobox-content'>$txt</div>
				</div>
				<div class='infobox-progress'>
					<div class='easy-pie-chart percentage' data-percent='' data-size='46' id='$idPorcb'>
						<span class='percent' id='$idPorca'></span>
					</div>
				</div>
				<div class='infobox-data infotipos-footer'>
					<span class='infobox-data-number' id='$id'></span>
				</div>
			</div>
		";
	}
	
}

function infoEstadisticaSop($txt, $id, $idPorca, $idPorcb, $color, $tipo, $year) {
	if( $tipo == 'year' ){
		echo "
			<div class='col-xs-12 col-sm-3 box-year infobox infobox-$color'>
				<div class='infobox-data'>
					<div class='infobox-content'>$txt</div>
				</div>
				<div class='infobox-progress'>
					<div class='easy-pie-chart percentage' data-percent='' data-size='100' id='$idPorcb'>
						<span class='percent' id='$idPorca'></span>
					</div>
				</div>
				<div class='infobox-data infobox-footer'>
					<br /><br />
					<p class='year-footer'>".$year."</p>
				</div>
			</div>
		";
	}else{
		echo "
			<div class='col-xs-12 col-sm-2 box-year-tipo infobox infobox-$color'>
				<div class='infobox-data infotipos'>
					<div class='infobox-content'>$txt</div>
				</div>
				<div class='infobox-progress'>
					<div class='easy-pie-chart percentage' data-percent='' data-size='46' id='$idPorcb'>
						<span class='percent' id='$idPorca'></span>
					</div>
				</div>
				<div class='infobox-data infotipos-footer'>
					
				</div>
			</div>
		";
	}
}

function infoEstadistica2($txt, $id, $idPorca, $idPorcb, $color) {
	echo "
		<div class='infobox infobox-$color'>
			<div class='infobox-data'>
				<span class='infobox-data-number' id='$id'></span>
				<div class='infobox-content'>$txt</div>
			</div>
		</div>
	";
}

function infoEstadistica3($txt, $id, $idPorca, $idPorcb, $color) {
	echo "
		<div class='infobox infobox-$color'>
			<div class='infobox-data'>
				<div class='infobox-content'>$txt<br/><br/></div>
			</div>
			<div class='infobox-data'>
				<span class='infobox-data-number' id='$id'></span>
			</div>
		</div>
	";
}

function linksheader(){
	if (!isset($_SESSION['nivel']))
		$_SESSION['nivel']=0;
	echo '
	<!-- Canonical SEO -->
    <link rel="canonical" href="https://toolkit.maxialatam.com/dashboard" />
    <!--  Social tags -->
    <meta name="keywords" content="Dashboard, Maxia, Toolkit, Telerradiologia, CSS, Panama ">
    <meta name="description" content="Dashboard Telerradiologia CSS Panama">
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="Dashboard Telerradiologia CSS Panama">
    <meta itemprop="description" content="Dashboard Telerradiologia CSS Panama">
    <meta itemprop="image" content="assets/img/maxia.jpg">
    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@Maxia">
    <meta name="twitter:title" content="Dashboard Telerradiologia CSS Panama">
    <meta name="twitter:description" content="Dashboard Telerradiologia CSS Panama">
    <meta name="twitter:creator" content="@Maxia">
    <meta name="twitter:image" content="assets/img/maxia.jpg"> 
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <!--  Material Dashboard CSS -->
    <link href="assets/css/material-dashboard.css" rel="stylesheet" />
    <!--  CSS for Demo Purpose, dont include it in your project     -->
    <link href="assets/css/ace.min.css" rel="stylesheet" />
    <!--  Fonts and icons -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/fonts/fonts.googleapis.com.css" />
	<link rel="stylesheet" href="css/demo.css">
	<link rel="stylesheet" href="assets/css/bootstrap-material-datetimepicker.css">
	<link rel="stylesheet" href="js/bootstrap-datepicker-1.6.4/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="assets/css/select2.min.css">
	<script type="text/javascript"> var session_nivel = '.$_SESSION['nivel'].'; </script>
	';
}

function linksheaderindex(){
	echo '
	<!-- Canonical SEO -->
    <link rel="canonical" href="https://maxiatoolkit.com" />
    <!--  Social tags -->
    <meta name="keywords" content="Dashboard, Telerradiologia, CSS, Panama">
    <meta name="description" content="Dashboard Telerradiologia CSS Panama">
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="Dashboard Telerradiologia CSS Panama">
    <meta itemprop="description" content="Dashboard Telerradiologia CSS Panama">
    <meta itemprop="image" content="assets/img/maxia.jpg">
    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@Maxia">
    <meta name="twitter:title" content="Dashboard Telerradiologia CSS Panama">
    <meta name="twitter:description" content="Dashboard Telerradiologia CSS Panama">
    <meta name="twitter:creator" content="@Maxia">
    <meta name="twitter:image" content="assets/img/maxia.jpg"> 
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <!--  Material Dashboard CSS -->
    <link href="assets/css/material-dashboard.css" rel="stylesheet" />
    <!--  CSS for Demo Purpose, dont include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />
    <!--  Fonts and icons -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/fonts/fonts.googleapis.com.css" />
	<link rel="stylesheet" href="css/demo.css">
	';
}

function linksheadermanual(){
	echo '
	<!-- Canonical SEO -->
    <link rel="canonical" href="https://maxiatoolkit.com" />
    <!--  Social tags -->
    <meta name="keywords" content="Dashboard, Telerradiologia, CSS, Panama">
    <meta name="description" content="Dashboard Telerradiologia CSS Panama">
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="Dashboard Telerradiologia CSS Panama">
    <meta itemprop="description" content="Dashboard Telerradiologia CSS Panama">
    <meta itemprop="image" content="assets/img/maxia.jpg">
    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@Maxia">
    <meta name="twitter:title" content="Dashboard Telerradiologia CSS Panama">
    <meta name="twitter:description" content="Dashboard Telerradiologia CSS Panama">
    <meta name="twitter:creator" content="@Maxia">
    <meta name="twitter:image" content="../assets/img/maxia.jpg">
    <!-- Bootstrap core CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <!--  Material Dashboard CSS -->
    <link href="../assets/css/material-dashboard.css" rel="stylesheet" />
    <!--  CSS for Demo Purpose, dont include it in your project     -->
    <link href="../assets/css/demo.css" rel="stylesheet" />
    <!--  Fonts and icons -->
    <link href="../assets/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/fonts/fonts.googleapis.com.css" />
	<link rel="stylesheet" href="../css/demo.css">
	<link rel="stylesheet" href="../css/manual.css">
	';
}

function linksfooter(){
	echo '
		<!--   Core JS Files   -->
		<script src="assets/js/jquery-3.2.1.min.js" type="text/javascript"></script>
		<script src="assets/js/jquery-ui.min.js" type="text/javascript"></script>
		<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="assets/js/material.min.js" type="text/javascript"></script>
		<script src="assets/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
		<!-- Forms Validations Plugin -->
		<script src="assets/js/jquery.validate.min.js"></script>
		<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
		<script src="assets/js/moment.min.js"></script>
		<!--  Charts Plugin -->
		<script src="assets/js/chartist.min.js"></script>
		<script src="assets/js/chartist-plugin-legend.js"></script>
		<script src="assets/js/chartist-plugin-pointlabels.js"></script>
		<!--  Plugin for the Wizard -->
		<script src="assets/js/jquery.bootstrap-wizard.js"></script>
		<!--  Notifications Plugin    -->
		<script src="assets/js/bootstrap-notify.js"></script>
		<!--   Sharrre Library    -->
		<script src="assets/js/jquery.sharrre.js"></script>
		<!-- DateTimePicker Plugin -->
		<script src="assets/js/bootstrap-datetimepicker.js"></script>
		<!-- Sliders Plugin -->
		<script src="assets/js/nouislider.min.js"></script>
		<!-- Select Plugin -->
		<script src="assets/js/jquery.select-bootstrap.js"></script>
		<!-- Sweet Alert 2 plugin -->
		<script src="assets/js/sweetalert2.js"></script>
		<!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
		<script src="assets/js/jasny-bootstrap.min.js"></script>
		<!--  Full Calendar Plugin    -->
		<script src="assets/js/fullcalendar.min.js"></script>
		<!-- TagsInput Plugin -->
		<script src="assets/js/jquery.tagsinput.js"></script>
		<!-- Material Dashboard javascript methods -->
		<script src="assets/js/material-dashboard.js"></script>
		<!-- Material Dashboard DEMO methods, dont include it in your project! -->
		<script src="assets/js/demo.js"></script>
		<!-- page specific plugin scripts -->
		<script src="js/bootstrap-datepicker-1.6.4/js/bootstrap-datepicker.min.js"></script>
		<script src="js/bootstrap-datepicker-1.6.4/locales/bootstrap-datepicker.es.min.js"></script>
		<script src="js/jquery.jqGrid.min.js"></script>
		<script src="js/grid.locale-es.js"></script>​
		<script src="assets/js/bootstrap-material-datetimepicker.js"></script>
		<script src="assets/js/select2.min.js"></script>
		<script src="assets/js/jquery.easypiechart.min.js"></script>
		<!-- Cookies -->
		<script src="assets/js/cookies.js"></script>
		<!-- Funciones -->
		<script src="assets/js/jquery.basictable.min.js"></script>
		<script src="js/funciones.js"></script>
	';
}

function linksfootermanual(){
	echo '
		<!--   Core JS Files   -->
		<script src="../assets/js/jquery-3.1.1.min.js" type="text/javascript"></script>
		<script src="../assets/js/jquery-ui.min.js" type="text/javascript"></script>
		<script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="../assets/js/material.min.js" type="text/javascript"></script>
		<script src="../assets/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
		<!-- Forms Validations Plugin -->
		<script src="../assets/js/jquery.validate.min.js"></script>
		<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
		<script src="../assets/js/moment.min.js"></script>
		<!--  Charts Plugin -->
		<script src="../assets/js/chartist.min.js"></script>
		<!--  Plugin for the Wizard -->
		<script src="../assets/js/jquery.bootstrap-wizard.js"></script>
		<!--  Notifications Plugin    -->
		<script src="../assets/js/bootstrap-notify.js"></script>
		<!--   Sharrre Library    -->
		<script src="../assets/js/jquery.sharrre.js"></script>
		<!-- DateTimePicker Plugin -->
		<script src="../js/bootstrap-datepicker-1.6.4/js/bootstrap-datepicker.min.js"></script>
		<!-- Sliders Plugin -->
		<script src="../assets/js/nouislider.min.js"></script>
		<!-- Select Plugin -->
		<script src="../assets/js/jquery.select-bootstrap.js"></script>
		<!-- Sweet Alert 2 plugin -->
		<script src="../assets/js/sweetalert2.js"></script>
		<!--  Full Calendar Plugin    -->
		<script src="../assets/js/fullcalendar.min.js"></script>
		<!-- TagsInput Plugin -->
		<script src="../assets/js/jquery.tagsinput.js"></script>
		<!-- Material Dashboard javascript methods -->
		<script src="../assets/js/material-dashboard.js"></script>
		<!-- Material Dashboard DEMO methods, dont include it in your project! -->
		<script src="../assets/js/demo.js"></script>
		<!-- page specific plugin scripts -->
		<script src="../js/bootstrap-datepicker.min.js"></script>
		<script src="../js/jquery.jqGrid.min.js"></script>
		<script src="../js/grid.locale-es.js"></script>
	';
}

function menu() {
	$nivel = $_SESSION['nivel'];
	echo '
		<div id="menu-1" class="menu-wrapper menu-light menu-sidebar-left menu-large">
			<div class="menu-scroll">
				<a href="inicio.php" class="menu-logo"></a>
				<em class="menu-sub-logo">Toolkit Soporte</em>
				<div class="menu">
					<em class="menu-divider">Men&uacute; Principal<i class="fa fa-navicon"></i></em>
					<a class="menu-item" href="dashboard.php"><i class="font-15 fa color-night-light fa-home"></i><strong>Dashboard</strong></a>
					';
					if($nivel == 1 || $nivel == 2) 
					echo '
					<ul class="link-list" style="padding-left:20px; padding-right:20px">		
					<li>
						<a class="inner-link-list" href="#"><i class="fa fa-angle-down"></i><i class="font-13 fa fa-edit color-blue-dark"></i><span>Maestros</span></a>
						<ul class="link-list">
							<li id="sla">
								<a href="sla.php">Tipos de SLA</a>
							</li>
							<li id="estados">
								<a href="estados.php">Estados</a>
							</li>
							<!--
							<li id="clientes">
								<a href="clientes.php">Clientes</a>
							</li>
							-->
							<li id="proyectos">
								<a href="proyectos.php">Proyectos</a>
							</li>
							<li id="sitios">
								<a href="sitios.php">Sitios</a>
							</li>	
							<li id="grupos">
								<a href="grupos.php">Grupos</a>
							</li>
						</ul>
					</li>	
					</ul>
					<ul class="link-list" style="padding-left:20px; padding-right:20px">		
					<li>
						<a class="inner-link-list" href="#"><i class="fa fa-angle-down"></i><i class="font-13 fa fa-file color-blue-dark"></i><span>Actas</span></a>
						<ul class="link-list">
							<li id="actasc">
								<a href="actas.php">Cuatrimestrales</a>
							</li>
							<li id="actasm">
								<a href="actasmpc.php">Mensuales</a>
							</li>
							<li id="mttopendientes">
								<a href="mttopendientes.php">Mtto Pendientes</a>
							</li>
							<li id="personal">
								<a href="personal.php">Personal</a>
							</li>
						</ul>
					</li>	
					</ul>
					';
					if($nivel == 1 || $nivel == 2 || $nivel == 3 || $nivel == 4 || $nivel == 5 )
					echo '	
					<a class="menu-item" href="incidentes.php"><i class="font-15 fa color-blue-dark fa-wrench"></i><strong>Incidentes</strong></a>
					<a class="menu-item" href="preventivos.php"><i class="font-15 fa color-blue-dark fa-calendar-check-o"></i><strong>Preventivos</strong></a>
					';					
					if($nivel == 1 || $nivel == 2)
					echo'
					<a class="menu-item no-border" href="activos.php"><i class="font-14 fa color-blue-dark fa-building-o"></i><strong>Gesti&oacute;n de activos</strong></a>
					';
					if($nivel == 1 || $nivel == 2 || $nivel == 3)
					echo'
					<a class="menu-item no-border" href="calendario.php"><i class="font-14 fa color-blue-dark fa-calendar"></i><strong>Calendario</strong></a>
					';
					if($nivel == 1 || $nivel == 2)
					echo'
					<ul class="link-list" style="padding-left:20px; padding-right:20px">		
					<li>
						<a class="inner-link-list" href="#"><i class="fa fa-angle-down"></i><i class="font-13 fa fa-users color-blue-dark"></i><span>Seguridad</span></a>
						<ul class="link-list">
							<li id="bitacora">
								<a href="bitacora.php">Bitacora</a>
							</li>
							<li id="usuarios">
								<a href="usuarios.php">Usuarios</a>
							</li>
							<li id="niveles">
								<a href="niveles.php">Niveles</a>
							</li>
						</ul>
					</li>	
					</ul>				
					';
					if($nivel == 1 || $nivel == 2 || $nivel == 3 || $nivel == 4 || $nivel == 5 )
					echo '	
					<ul class="link-list" style="padding-left:20px; padding-right:20px">		
					<li>
						<a class="inner-link-list" href="#"><i class="fa fa-angle-down"></i><i class="font-13 fa fa-key color-orange-dark"></i><span>Cambiar clave</span></a>
						<ul class="link-list">
							<li>
								<div class="input-simple-1 has-icon input-green full-bottom"><em>Nueva clave</em><i class="fa fa-key"></i><input type="password" id="nuevaclave" name="nuevaclave"></div>
									<a href="javascript:cambiarClave();" data-deploy-menu="notification" class="button button-full button-round button-blue2-3d button-green uppercase ultrabold">Cambiar Clave</a>
								</div>
							</li>
						</ul>
					</li>	
					</ul>
					<a class="menu-item close-menu" href="index.php"><i class="font-14 fa color-orange-dark fa-times"></i><strong>Salir</strong></a>
				</div>
			</div>
		</div>
		<div id="notification" data-menu-size="260" class="menu-wrapper menu-light menu-modal">
			<h1 class="center-text half-top full-bottom color-green-light"><i class="fa fa-check fa-2x"></i></h1>
			<h1 class="no-top uppercase ultrabold no-bottom center-text">Operaci&oacute;n Exitosa</h1>
			<p class="color-black opacity-70 center-text full-bottom">
				Su contrase&ntilde;a ha sido modificada satisfactoriamente
			</p>
			<a href="#" class="close-menu button button-rounded button-s button-center button-green uppercase ultrabold">Cerrar</a>
		</div>
		<script>
			function cambiarClave() {
				if ( $("#nuevaclave").val()!="" ) {
					$.ajax({
					  type: "post",
					  url: "incidentesback.php",
					  data: { 
						"oper"	: "cambiarclave",
						"clave" : $("#nuevaclave").val()
					  },
					  success: function (response) {
							if(response){
								$("#nuevaclave").val("");
							}else{
								
							}
					  },
					  error: function () {
						}
				   }); 
				}
				return true;
			}
		</script>
	';
}

function menuund($unidad) {
	$nivel = $_SESSION['nivel'];
	echo '
		<div class="sidebar" data-active-color="blue" data-background-color="black">
            <div class="logo">
                <a href="dashboard.php" class="simple-text">
                    Dashboard<br />
					Telerradiolog&iacute;a
                </a>
            </div>
            <div class="logo logo-mini">
                <a href="dashboard.php" class="simple-text">
                    DT
                </a>
            </div>
            <div class="sidebar-wrapper">
                <div class="user">
                    <div class="photo">
                        <img src="assets/img/usuario.jpg" />
                    </div>
                    <div class="info">
                        <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                            '.$_SESSION['nombreUsuario'].'
                            <b class="caret"></b>
                        </a>
                        <div class="collapse" id="collapseExample">
                            <ul class="nav">
                                <li>
                                    <a href="index.php?opcion=LO">Cerrar sesi&oacute;n</a>
                                </li>
								<li>
									<a data-toggle="collapse" href="#collapseClave" class="collapsed">
										Cambiar contrase&ntilde;a
										<b class="caret"></b>
									</a>
									<div class="collapse" id="collapseClave">
										<ul class="nav nav-clave">
											<li>
												<div class="form-group label-floating is-empty" style="display: table; margin: 0 auto;">
													<input type="text" id="nuevaclave" name="nuevaclave" placeholder="Nueva clave..." />
													<span class="material-input"></span>
												</div>
												<div style="display: table; margin: 0 auto;">
												<button type="button" class="swal2-confirm btn btn-success btn-xs" onclick="cambiarClave();">Cambiar</button>
												</div>
											</li>
										</ul>
									</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <ul class="nav">
				<li id="mnuDashboard">
					<a href="dashboardund.php?und="'.$unidad.'>
						<i class="material-icons">dashboard</i>
						<p>Dashboard</p>
					</a>
				</li>
				<li id="mnuCalendario">
					<a href="tiempos.php">
						<i class="material-icons">date_range</i>
						<p>Tiempos</p>
					</a>
				</li>
				<li>
					<a data-toggle="collapse" href="#reportes" aria-expanded="true">
						<i class="material-icons">description</i>
						<p>Exportar
							<b class="caret"></b>
						</p>
					</a>
					<div class="collapse" id="reportes" aria-expanded="true">
						<ul class="nav">
							<li id="reporteActas">
								<a href="#">
									<i class="material-icons">send</i>
									<p>Excel</p>
								</a>
							</li>
							<li id="reporteIncidentes">
								<a href="#">
									<i class="material-icons">send</i>
									<p>PDF</p>
								</a></li>
							<li id="#">
								<a href="bitacora.php">
									<i class="material-icons">send</i>
									<p>CSV</p>
								</a></li>
						</ul>
					</div>
				</li>
                </ul>
            </div>
        </div>
	';
}

function menudetalle() {
	$nivel = $_SESSION['nivel'];
	echo '
		<div class="sidebar" data-active-color="blue" data-background-color="black" data-image="assets/img/maxia-latam.jpg">
            <div class="logo">
                <a href="dashboard.php" class="simple-text">
                    Maxia Toolkit
                </a>
            </div>
            <div class="logo logo-mini">
                <a href="dashboard.php" class="simple-text">
                    MT
                </a>
            </div>
            <div class="sidebar-wrapper">
                <div class="user">
                    <div class="photo">
                        <img src="assets/img/maxia.jpg" />
                    </div>
                    <div class="info">
                        <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                            '.$_SESSION['nombreUsuario'].'
                            <b class="caret"></b>
                        </a>
                        <div class="collapse" id="collapseExample">
                            <ul class="nav">
                                <li>
                                    <a href="index.php?opcion=LO">Cerrar sesi&oacute;n</a>
                                </li>
								<li>
									<a data-toggle="collapse" href="#collapseClave" class="collapsed">
										Cambiar contrase&ntilde;a
										<b class="caret"></b>
									</a>
									<div class="collapse" id="collapseClave">
										<ul class="nav nav-clave">
											<li>
												<div class="form-group label-floating is-empty" style="display: table; margin: 0 auto;">
													<input type="text" id="nuevaclave" name="nuevaclave" placeholder="Nueva clave..." />
													<span class="material-input"></span>
												</div>
												<div style="display: table; margin: 0 auto;">
												<button type="button" class="swal2-confirm btn btn-success btn-xs" onclick="cambiarClave();">Cambiar</button>
												</div>
											</li>
										</ul>
									</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <ul class="nav">
					<li>&nbsp;</li>
                </ul>
            </div>
        </div>
	';
}


function menumanual() {
	$nivel = $_SESSION['nivel'];
	echo '
		<div class="sidebar blue" data-active-color="blue" data-background-color="blue" data-image="../assets/img/maxia-latam.jpg">
            <div class="logo blue">
                <a href="index.php" class="simple-text">
                    Maxia Toolkit
                </a>
            </div>
            <div class="logo blue logo-mini">
                <a href="index.php" class="simple-text">
                    MT
                </a>
            </div>
            <div class="sidebar-wrapper">
                <div class="user">
                    <div class="photo">
                        <img src="../assets/img/maxia.jpg" />
                    </div>
                    <div class="info">
                        <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                            '.$_SESSION['nombreUsuario'].'
                            <b class="caret"></b>
                        </a>
                        <div class="collapse" id="collapseExample">
                            <ul class="nav">
                                <li>
                                    <a href="index.php">Cerrar sesi&oacute;n</a>
                                </li>
								<li>
									<a data-toggle="collapse" href="#collapseClave" class="collapsed">
										Cambiar contrase&ntilde;a
										<b class="caret"></b>
									</a>
									<div class="collapse" id="collapseClave">
										<ul class="nav nav-clave">
											<li>
												<div class="form-group label-floating is-empty" style="display: table; margin: 0 auto;">
													<input type="text" id="nuevaclave" name="nuevaclave" placeholder="Nueva clave..." />
													<span class="material-input"></span>
												</div>
												<div style="display: table; margin: 0 auto;">
												<button type="button" class="swal2-confirm btn btn-success btn-xs" onclick="cambiarClave();">Cambiar</button>
												</div>
											</li>
										</ul>
									</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <ul class="nav">
                    ';
                    if($nivel == 1 || $nivel == 2 || $nivel == 3 || $nivel == 4 || $nivel == 9):
                	echo '
                    <li id="mnuDashboard">
                        <a href="dashboard.php">
                            <i class="material-icons">dashboard</i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    ';
                    endif;
					
					if($nivel == 1):
					echo'
                    <li>
                        <a data-toggle="collapse" href="#archivo" aria-expanded="true">
                            <i class="material-icons">archive</i>
                            <p>Archivo
                                <b class="caret"></b>
                            </p>
                        </a>	
                        <div class="collapse" id="archivo" aria-expanded="true">	
                            <ul class="nav">								
                                <li id="mnuTareas">
			                        <a href="tareas.php">
			                            <i class="material-icons">view_compact</i>
			                            <p>Tareas</p>
			                        </a>
			                    </li>
										
								<li id="mnuSectores">
			                        <a href="sectores.php">
			                            <i class="material-icons">view_compact</i>
			                            <p>Sectores</p>
			                        </a>
			                    </li>
										
								<li id="mnuSubsectores">
			                        <a href="subsectores.php">
			                            <i class="material-icons">view_comfy</i>
			                            <p>Subsectores</p>
			                        </a>
			                    </li>
										
								<li id="mnuServicios">
			                        <a href="servicios.php">
			                            <i class="material-icons">business_center</i>
			                            <p>Servicios</p>
			                        </a>
			                    </li>
								
								<li id="mnuSistemas">
			                        <a href="sistemas.php">
			                            <i class="material-icons">group_work</i>
			                            <p>Sistemas</p>
			                        </a>
			                    </li>
										
								<li id="mnuProveedores">
			                        <a href="proveedores.php">
			                            <i class="material-icons">supervisor_account</i>
			                            <p>Proveedores</p>
			                        </a>
			                    </li>
								
								<li id="mnuListaCorreos">
			                        <a href="listacorreos.php">
			                            <i class="material-icons">mail</i>
			                            <p>Lista de Correos</p>
			                        </a>
			                    </li>
                            </ul>
                        </div>
                    </li>
					<li id="mnuActividades">
                        <a href="actividades.php">
                            <i class="material-icons">build</i>
                            <p>Plan de Mantenimiento</p>
                        </a>
                    </li>';
					endif;
					if($nivel == 1 || $nivel == 2 || $nivel == 3 || $nivel == 9):
					echo '
                    <li id="mnuOrdenes">
                        <a href="ordenes.php">
                            <i class="material-icons">content_paste</i>
                            <p>&Oacute;rdenes</p>
                        </a>
                    </li>';
					endif;
					if($nivel == 1 || $nivel == 3 || $nivel == 4 || $nivel == 9):
					echo '
					<li id="mnuIncidentes">
                        <a href="incidentes.php">
                            <i class="material-icons">report problem</i>
                            <p>Incidentes</p>
                        </a>
                    </li>';
					endif;
					if($nivel == 1 || $nivel == 3 || $nivel == 9):
					echo '
                    <li id="mnuActas">
                        <a href="actas.php">
                            <i class="material-icons">description</i>
                            <p>Actas</p>
                        </a>
                    </li>
					<li id="mnuCalendario">
                        <a href="calendario.php">
                            <i class="material-icons">date_range</i>
                            <p>Calendario</p>
                        </a>
                    </li>
                    <!-- REPORTES -->
                 	<li>
                        <a data-toggle="collapse" href="#reportes" aria-expanded="true">
                            <i class="material-icons">description</i>
                            <p>Reportes
                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="collapse" id="reportes" aria-expanded="true">
                            <ul class="nav">
                                <li id="reporteActas">
                                    <a href="reporteActas.php">Reportes Actas</a>
                                </li>
                            	<li id="reporteIncidentes">
                                    <a href="reporteIncidentes.php">Reportes de Incidentes</a>
                                </li>
                            	<li id="reporteOrdenes">
                                    <a href="reporteOrdenes.php">Reportes de &Oacute;rdenes</a>
                                </li>
                            </ul>
                        </div>
                    </li>';
					endif;
					if($nivel == 1):
					echo '
					<li>
                        <a data-toggle="collapse" href="#seguridad" aria-expanded="true">
                            <i class="material-icons">supervisor_account</i>
                            <p>Seguridad
                                <b class="caret"></b>
                            </p>
                        </a>	
                        <div class="collapse" id="seguridad" aria-expanded="true">	
                            <ul class="nav">								
								<!-- BITACORA -->
								<li id="mnubitacora">
									<a href="bitacora.php">
										<i class="material-icons">fingerprint</i>
										<p>Bitacora</p>
									</a>                        
								</li>
								<!-- USUARIOS -->
								<li id="mnuUsuarios">
									<a href="usuarios.php">
										<i class="material-icons">face</i>
										<p>Usuarios</p>
									</a>
								</li>
							</ul>
						</div>
					</li>';
					endif;				
				echo '	
                </ul>
            </div>
        </div>
	';
}

function configuracion(){
	echo '
	<div class="fixed-plugin">
        <div class="dropdown show-dropdown">
            <a href="#" data-toggle="dropdown">
                <i class="fa fa-cog fa-2x" style="color:#FFFFFF"></i>
            </a>
            <ul class="dropdown-menu">
                <li class="adjustments-line">
                    <a href="javascript:void(0)" class="switch-trigger">
                        <div class="togglebutton switch-sidebar-mini">
                            <label>
                                <input type="hidden">
                            </label>
                        </div>
                        <div class="clearfix"></div>
                    </a>
                </li>
                <li class="header-title">Fecha</li>
                <li class="filtroFecha">
                    <label class="col-xs-12 control-label" for="textinput">A&ntilde;o(s)</label>
					<div class="input-group box-input">
					  <input type="text" id="filtro-desde" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar" onclick="document.getElementById(\'filtro-desde\').focus()"></i></span>
					</div>
					<label class="col-xs-12 control-label" for="textinput">Mes(es)</label>
					<div class="input-group box-input">
					  <input type="text" id="filtro-hasta" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar" onclick="document.getElementById(\'filtro-hasta\').focus()"></i></span>
					</div>
                </li>
            </ul>
        </div>
    </div>
	';
}

function botonAccion() {
	echo '
		<li class="">
			<a href="#" id="btnAccion" name="btnAccion" onClick="actualizarDashboard()">
				<i class="menu-icon fa fa-refresh"></i>
				<span class="menu-text"> Actualizar </span>
			</a>

			<b class="arrow"></b>
		</li>
	';
}

function botonLimpiar() {
	echo '
		<li class="">
			<a href="#" id="btnLimpiar" name="btnLimpiar" onClick="limpiarDashboard()">
				<i class="menu-icon fa fa-power-off"></i>
				<span class="menu-text"> Limpiar </span>
			</a>

			<b class="arrow"></b>
		</li>
	';
}

function pie() {
	echo '
		<div class="footer">
			<div class="footer-inner">
				<div class="footer-content">
					<span class="bigger-120">
						<strong>&copy; '.date('Y').' - Desarrollado por <a href="http://www.maxialatam.com" target="_blank"><img src="assets/images/logomaxia16.png"> Maxia Latam</a></strong>
					</span>

				</div>
			</div>
		</div>';
}

?>