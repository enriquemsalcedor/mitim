<?php
header('Set-Cookie: same-site-cookie=foo; SameSite=Lax');
header('Set-Cookie: cross-site-cookie=bar; SameSite=None; Secure');

//header('Set-Cookie: cross-site-cookie=name; SameSite=None; Secure');
include_once("conexion.php");
/* if (session_status() !== PHP_SESSION_ACTIVE){session_start();}
$location = strrpos($_SERVER['PHP_SELF'],'index.php'); */
/* if(!isset($_SESSION['usuario'])  && $location != true){
	header('Location: index.php');
	exit;
} */
 
function autoVersiones(){
	echo '?v='.rand(1000, 9999);
}

function verificarLogin() { 
	//Si cookies y sesiones están vacías
	if( !isset( $_SESSION['usuario'] ) && !isset( $_COOKIE['usuario'] ) ) { 
		 header('Location: index.php');
		 	//debug('1-cookie:'.$_COOKIE['usuario'].'-sesion:'.$_SESSION['usuario']); 
	}else{
		//SI AMBAS ESTAN LLENAS
		if( isset( $_SESSION['usuario'] ) && isset( $_COOKIE['usuario'] ) ) {
			//debug('6-cookie:'.$_COOKIE['usuario'].'-sesion:'.$_SESSION['usuario']);	
		}else{
			//Si sesión está vacía
			if( !isset( $_SESSION['usuario'] ) ) {
				//Si cookie está llena 
				//Creo las sesiones a partir de las cookies
				if( isset( $_COOKIE['usuario'] ) ) {
					$_SESSION['usuario']		= $_COOKIE['usuario']; 
					$_SESSION['user_id']		= $_COOKIE['user_id'];
					$_SESSION['nombreUsuario']	= $_COOKIE['nombreUsuario'];
					$_SESSION['nivel']			= $_COOKIE['nivel'];
					$_SESSION['unidad']			= $_COOKIE['unidad'];
					$_SESSION['sitio']			= $_COOKIE['sitio'];
					$_SESSION['idempresas']		= $_COOKIE['idempresas'];
					$_SESSION['idclientes']		= $_COOKIE['idclientes'];
					$_SESSION['idproyectos'] 	= $_COOKIE['idproyectos'];
					$_SESSION['iddepartamentos']= $_COOKIE['iddepartamentos'];
					$_SESSION['correousuario']	= $_COOKIE['correousuario']; 
					return 1;
					//debugL('2-cookie:'.$_COOKIE['usuario'].'-sesion:'.$_SESSION['usuario']);
				}else{
					//debugL('3-cookie:'.$_COOKIE['usuario'].'-sesion:'.$_SESSION['usuario']);
				}
			}else{
				//Si sesión está llena y cookie está vacía
				//Creo las cookies a partir de la sesión
				if( !isset( $_COOKIE['usuario'] ) ) {
					setcookie("usuario", $_SESSION['usuario'], time() + 31536000);
					setcookie("user_id", $_SESSION['user_id'], time() + 31536000);
					setcookie("nombreUsuario", $_SESSION['nombreUsuario'], time() + 31536000);
					setcookie("nivel", $_SESSION['nivel'], time() + 31536000);
					setcookie("unidad", $_SESSION['unidad'], time() + 31536000);
					setcookie("sitio", $_SESSION['sitio'], time() + 31536000);
					setcookie("idempresas", $_SESSION['idempresas'], time() + 31536000);
					setcookie("idclientes", $_SESSION['idclientes'], time() + 31536000);
					setcookie("idproyectos", $_SESSION['idproyectos'], time() + 31536000);
					setcookie("iddepartamentos", $_SESSION['iddepartamentos'], time() + 31536000);
					setcookie("correousuario", $_SESSION['correousuario'], time() + 31536000);
					//debugL('4-cookie:'.$_COOKIE['usuario'].'-sesion:'.$_SESSION['usuario']);
				}else{
					//debugL('5-cookie:'.$_COOKIE['usuario'].'-sesion:'.$_SESSION['usuario']);
				} 
			} 
		}
	} 	
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

function validarSession() {
	if (!isset($_SESSION['usrUsuario']))
		echo "<script>location.href='index.php';</script>";	
}

function showname(){  
    global $mysqli;
    
    $usuario = $_SESSION['usuario'];
	
	$query = " SELECT nombre FROM usuarios WHERE usuario = '$usuario'";	           
	$result = $mysqli->query($query);	
	if($row = $result->fetch_assoc()){
	  echo $row['nombre'];  
	}  
}

function timedown() {
	global $mysqli;
	/*$query  = " SELECT i.id, a.codigound, a.unidad, a.codequipo, a.marca, a.modalidad, a.equipo, 
				a.modelo, a.estado, f.desde
				FROM activos a
				INNER JOIN fueraservicio f ON f.codequipo = a.codequipo
				INNER JOIN incidentes i ON i.id = f.incidente
				WHERE i.estado < 16 
				ORDER BY a.codigound ";*/
	
	$query = "SELECT i.id, a.codigound, a.unidad, a.codequipo, a.marca, a.modalidad, a.equipo, 
				a.modelo, a.estado, i.fechacreacion as desde 
			  FROM incidentes i
			  INNER JOIN activos a ON a.codequipo = i.serie
			  WHERE i.idprioridad=6 and i.estado < 16 
			  GROUP BY i.serie
			  ORDER BY i.id DESC";
			  
	$result = $mysqli->query($query);
	while($row = $result->fetch_assoc()){
		if ($row['codigound']==''){
			$row['codigound']='empty';
		} else {
			$row['codigound']='sedes/'.$row['codigound'];
		}
		echo '
		<div class="news-list-item">
			<a href="incidente.php?id='.$row['id'].'">
			<img class="img-list-round" src="images/'.$row['codigound'].'.jpg" alt="img">
			<strong>'.$row['codequipo'].' | '.$row['modalidad'].'  | <b> INACTIVO </b>
				<br/>'.$row['equipo'].' | '.$row['marca'].' | '.$row['modelo'].'</strong>
			<span>'.$row['unidad'].'</span>
			</a>
		</div>			
		';
	}
}

function comentarios($id) {
	global $mysqli;
	$query  = " SELECT a.id, a.idmodulo, a.comentario, date_format(a.fecha,'%m/%d/%Y') as fecha, b.nombre, a.visibilidad
				FROM comentarios a
				LEFT JOIN usuarios b ON a.usuario = b.usuario
				WHERE modulo = 'Incidentes' AND idmodulo = $id ";
	if($_SESSION['nivel'] == 4){
		$query .= " AND a.visibilidad = 'Público' ";
	}
	$query  .= " ORDER BY a.id desc ";
	$result = $mysqli->query($query);
	
	while($row = $result->fetch_assoc()){
		echo '
		<div class="blog-post-home">
			<!--<a href="#"><img src="images/pictures/3.jpg" class="responsive-image"></a>-->
			<strong class="font-17">' .$row['nombre']. '</strong>
			<span><i class="fa fa-clock-o"></i> <a href="#"> ' .$row['fecha']. '</a> | 
			<a href="#"><i class="fa fa-eye color-blue-light"></i>' .$row['visibilidad']. '</a></span>
			<div class="clear"></div>
			<p>' .$row['comentario']. '</p>
		</div>	
		
		';
	}	
}

function linksheader(){
	echo '
	<meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="images/loginmitim.png" />
    <link rel="icon" type="image/png" href="images/loginmitim.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />    
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport"/>
    <meta name="viewport" content="width=device-width" />    
	<link rel="canonical" href="https://maxiatoolkit.com" />
    <meta name="keywords" content="Soporte, Sistema, Maxia, Toolkit, Materiales, Control, Administrativo, Ventas, Unidades, Cantidades, Items, Costos">
    <meta name="description" content="Sistema profesional de gestion de Soporte Maxia Toolkit">
    <meta itemprop="name" content="Sistema profesional de gestion de Soporte Maxia Toolkit">
    <meta itemprop="description" content="Sistema profesional de gestion de Soporte Maxia Toolkit es un sistema que permite el facil control.">
    <meta itemprop="image" content="../repositorio-tema/assets/img/maxia.jpg">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@Maxia">
    <meta name="twitter:title" content="Sistema profesional de gestion de Soporte Maxia Toolkit">
    <meta name="twitter:description" content="Sistema profesional de gestion de Soporte Maxia Toolkit es un sistema que permite el facil control.">
    <meta name="twitter:creator" content="@Maxia">
    <meta name="twitter:image" content="../repositorio-tema/assets/img/maxia.jpg"> 
    <!-- Bootstrap core CSS -->
    <link href="../repositorio-tema/assets/css/bootstrap.min.css" rel="stylesheet" />
    <!--  Fonts and icons -->
    <link href="../repositorio-tema/assets/css/font-awesome.min.css" rel="stylesheet">
	<!--<script src="https://kit.fontawesome.com/7f9e31f86a.js" crossorigin="anonymous"></script>-->
    <link href="../repositorio-tema/assets/fonts/fonts.googleapis.com.css" rel="stylesheet" />
	<link href="css/demo.css" rel="stylesheet" >
	<link href="../repositorio-tema/assets/css/bootstrap-material-datetimepicker.css" rel="stylesheet" >
	<!-- <link href="../repositorio-tema/assets/css/select2.min.css" rel="stylesheet" /> -->
	<link href="../repositorio-tema/css/select2-4.0.6.min.css" rel="stylesheet" />
	<link href="css/jquery-ui.theme.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="styles/style.css">
	<link rel="stylesheet" type="text/css" href="styles/framework.css">
	<link rel="stylesheet" type="text/css" href="styles/estilos.css">
	<link rel="stylesheet" type="text/css" href="styles/ajustes.css">
	<link href="styles/imagen.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
    <script type="text/javascript"> var nivel = "'.$_SESSION['nivel'].'"; var idproyectos = "'.$_SESSION['idproyectos'].'"; var temp = "'.$_SESSION['user_id'].'"; </script>
	';
}

function linksheaderindex(){
	echo '
	<!-- Canonical SEO -->
    <link rel="canonical" href="https://maxiatoolkit.com" />
    <!--  Social tags -->
    <meta name="keywords" content="Inventario, Sistema, Maxia, Toolkit, Materiales, Control, Administrativo, Ventas, Unidades, Cantidades, Items, Costos, Articulos, Devoluciones, Transacciones, Depositos, Unidades, Egresos, Ingresos ">
    <meta name="description" content="Sistema profesional de gestion de inventario Maxia Toolkit">
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="Sistema profesional de gestion de inventario Maxia Toolkit">
    <meta itemprop="description" content="Sistema profesional de gestion de inventario Maxia Toolkit es un sistema que permite el facil control de ingresos y egresos de mercancia.">
    <meta itemprop="image" content="../repositorio-tema/assets/img/maxia.jpg">
    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@Maxia">
    <meta name="twitter:title" content="Sistema profesional de gestion de inventario Maxia Toolkit">
    <meta name="twitter:description" content="Sistema profesional de gestion de inventario Maxia Toolkit es un sistema que permite el facil control de ingresos y egresos de mercancia.">
    <meta name="twitter:creator" content="@Maxia">
    <meta name="twitter:image" content="../repositorio-tema/assets/img/maxia.jpg">
    <!-- Bootstrap core CSS -->
    <link href="../repositorio-tema/assets/css/bootstrap.min.css" rel="stylesheet" />
    <!--  Material Dashboard CSS -->
    <link href="../repositorio-tema/assets/css/material-dashboard.css" rel="stylesheet" />
    <!--  CSS for Demo Purpose, dont include it in your project     -->
    <link href="../repositorio-tema/assets/css/demo.css" rel="stylesheet" />
    <!--  Fonts and icons -->
    <link href="../repositorio-tema/assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="../repositorio-tema/assets/fonts/fonts.googleapis.com.css" rel="stylesheet" type="text/css" />
	<link href="css/demo.css" rel="stylesheet">
	';
}

function linksfooter(){
	echo '
		<script src="../repositorio-tema/assets/js/jquery-3.1.1.min.js" type="text/javascript"></script>
		<script src="../repositorio-tema/assets/js/jquery-ui.min.js" type="text/javascript"></script>
		<script src="../repositorio-tema/assets/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="../repositorio-tema/assets/js/material.min.js" type="text/javascript"></script>
		<script src="../repositorio-tema/assets/js/jquery.validate.min.js"></script>
		<script src="../repositorio-tema/assets/js/moment.min.js"></script>
		<script src="../repositorio-tema/assets/js/jquery.bootstrap-wizard.js"></script>
		<script src="../repositorio-tema/assets/js/bootstrap-notify.js"></script>
		<script src="../repositorio-tema/assets/js/jquery.sharrre.js"></script>
		<script src="../repositorio-tema/assets/js/bootstrap-datetimepicker.js"></script>
		<script src="../repositorio-tema/assets/js/nouislider.min.js"></script>
		<script src="../repositorio-tema/assets/js/jquery.select-bootstrap.js"></script>
		<script src="../repositorio-tema/assets/js/sweetalert2.js"></script>
		<script src="../repositorio-tema/assets/js/jasny-bootstrap.min.js"></script>
		<script src="../repositorio-tema/assets/js/fullcalendar.min.js"></script>
		<script src="../repositorio-tema/assets/js/jquery.tagsinput.js"></script>
		<script src="../repositorio-tema/assets/js/material-dashboard.js"></script>
		<script src="../repositorio-tema/assets/js/demo.js"></script>
		<script src="../repositorio-tema/assets/js/bootstrap-material-datetimepicker.js"></script>
		<script src="../repositorio-tema/assets/js/datepicker-es.js"></script>
		<!-- <script src="assets/js/select2.min.js"></script> -->
		<script src="../repositorio-tema/js/select2-4.0.6.min.js"></script>
		<script src="../repositorio-tema/js/select2-es.js"></script>
		<script src="../repositorio-tema/js/jquery.autocomplete.js"></script>
		<script src="../repositorio-tema/assets/js/cookies.js"></script>
		<script src="../repositorio-tema/assets/js/jquery.easypiechart.min.js"></script>
		<script src="../repositorio-tema/assets/js/jquery.datatables.js"></script>
		<script src="js/funciones.js"></script>
	';
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

function infoEstadisticaSop2($txt, $id, $idPorca, $idPorcb, $color, $tipo, $year, $img) {
	if( $tipo == 'year' ){
		echo "
			<div class='col-xs-12 col-sm-3 box-year infobox infobox-$color' style='background:url(\"images/dashboard/hsma.png\") no-repeat;'>
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
					<p class='year-footer'>Avance al: ".date("m/d/Y")."</p>
				</div>
			</div>
		";
	}else{
		echo "
			<div class='col-xs-12 col-sm-2 box-year-tipo infobox infobox-$color' style='background:url(\"images/dashboard/$img\");height:120px;'>
				<div class='infobox-data infotipos'>
					<div class='infobox-content'>$txt</div>
				</div>
				<div class='infobox-progress'>
					<br />
					<div class='easy-pie-chart percentage' data-percent='' data-size='50' id='$idPorcb'>
						<span class='percent' id='$idPorca'></span>
					</div>
				</div>
				<div class='infobox-data infotipos-footer'>
					
				</div>
			</div>
		";
	}
}

function menusup(){
    echo '<div id="header" class="header-logo-app header-dark">
			<a href="#" class="header-title"></a>
			<a href="#" class="header-logo enabled"></a>
			<!--<a href="inicio.php" class="header-icon header-icon-1 no-border font-14"><i class="fa fa-home"></i></a> -->
			<a href="#" class="header-icon header-icon-1 hamburger-animated" data-deploy-menu="menu-1"></a>
			<div id="nombremenu"></div>
			<a class="header-title">';
			    showname();
	echo    '</a>'; 
	/*
	if($_SERVER["REQUEST_URI"] == '/soporte/dashboard.php'){
		echo    '<form name="seldash" style=" text-align: right; right: 180px; ">
				<select style="background-color: #1e3d7a;" name="cambiar" onchange="document.location.href=document.seldash.cambiar.options[document.seldash.cambiar.selectedIndex].value; 
					return false;"> 
					<option value="">DASHBOARD</option>
					<option value="http://toolkit.maxialatam.com/pcm/dashboard.php">PCM</option>
					<option value="http://toolkit.maxialatam.com/pem/dashboard.php">PEM</option>
				</select></form>';
	}*/
	
	echo	'</div>';
}

function menusupclientes(){
    echo '<div id="header" class="header-logo-app header-dark">
			<a href="#" class="header-title"></a>
			<a href="#" class="header-logo enabled"></a>
			<a data-deploy-menu="menu-4" href="#" class="header-icon header-icon-4 no-border font-14" data-deploy-menu="menu-4"><i class="fa fa-tachometer"></i></a>
			<a href="#" class="header-icon header-icon-1 hamburger-animated" data-deploy-menu="menu-1"></a>
			<a class="header-title">';
			    showname();
	echo    '</a></div>';
}

function navheader(){
	echo '	<a href="#" class="brand-logo">
				<img class="logo-abbr" src="./images/loginmitim.jpeg" alt="" style="max-width: 43px; border-radius: 50%;">
			</a>
			 <script type="text/javascript"> var nivel = "'.$_SESSION['nivel'].'"; var idproyectos = "'.$_SESSION['idproyectos'].'"; var temp = "'.$_SESSION['user_id'].'"; </script>';
}
function navheaderbotones(){
    $nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre.' ');
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	echo '             <ul class="navbar-nav header-right">
	                       <li class="nav-item dropdown notification_dropdown">
                                <a id="icono-notificaciones" class="nav-link bell bell-link bg-warning" href="javascript:;">
                                    <span class="btn-icon" data-toggle="tooltip" title="Notificaciones" id="notificaciones"><i class="fa fa-bell text-white i-header"></i></span>
                                </a>
							</li>
                           <li class="nav-item dropdown notification_dropdown">
                                <a id="icono-refrescar"class="nav-link bg-success"   href="javascript:;">
                                    <span class="btn-icon" data-toggle="tooltip" title="Refrescar" id="refrescar"><i class="fa fa-refresh text-white i-header"></i></span>
                                </a>
							</li>
							 <li class="nav-item dropdown notification_dropdown">
                                <a id="icono-limpiar"class="nav-link bg-success"  href="javascript:;">
                                    <span class="btn-icon" data-toggle="tooltip" title="Limpiar" id="limpiarCol"><i class="fa fa-eraser text-white i-header"></i></span>
                                </a>
							</li>
                          <li class="nav-item dropdown notification_dropdown">
                                <a id="icono-filtrosmasivos"class="nav-link bell bell-link bg-success" onclick="abrirFiltrosMasivos()"  href="javascript:;">
                                    <span class="btn-icon" data-toggle="tooltip" title="Filtros" id="filtrosmasivos"><i class="fa fa-filter text-white i-header"></i></span>
                                </a>
							</li> 
							<li class="nav-item dropdown notification_dropdown">
                                <a id="icono-reportes" style="display: none;" class="nav-link bell bell-link bg-success" onclick="preventivosPendientesMes()" data-toggle="tooltip" title="" data-original-title="Preventivos pendientes al mes">
									<i class="fas fa-file-download text-white i-header" aria-hidden="true"></i>
                                </a>
							</li>
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="javascript:;" role="button" data-toggle="dropdown">
                                    <div class="round-header">'.$inombre.'</div>
                                    <div class="header-info">
                                        <span>'.$nombre.'</span>
                                    </div>
                                </a>
                            </li>
                        </ul>';
}
                            


function menu() {
    global $mysqli;
	$nivel = $_SESSION['nivel'];
	$usuario = $_SESSION['usuario'];
	$res = 0;
	$val = 0;	  
	if($nivel == 3){
	    $query = "SELECT * from usuarios where usuario = '$usuario' and nivel = '$nivel' and cargo = 'Implementador'";
	    $result = $mysqli->query($query);
	    if($result->num_rows >0){
		    $res = 1;
	    }    
	}
	//Usuarios CRM - Proyecto Vue Pacs
	$consulta = " SELECT usuario,nombre FROM usuarios WHERE usuario = '$usuario' AND 42 AND find_in_Set(42,idproyectos) ";
	$respuesta = $mysqli->query($consulta);
	if($respuesta->num_rows >0){
		$val = 1;
	}
	//Pertenece a Maxia
	$esmaxia = 0;
	$email   = $_SESSION['correousuario'];
	$dato    = explode('@', $email);
	$dominio = trim($dato[1]);
	if($dominio == 'maxialatam.com'){
		$esmaxia = 1;
	} 
	
	//Niveles: 3:Ing/Técnicos - 4:Clientes
	//Pertenece al departamento Desarrollo
	$iddepartamentos = (!empty($_SESSION['iddepartamentos']) ? $_SESSION['iddepartamentos'] : 0); 
	$desarrollo = strpos($iddepartamentos, '15'); 
	echo '
		<div id="menu-1" class="menu-wrapper menu-light menu-sidebar-left menu-large">
			<div class="menu-scroll">
				<a href="dashboard.php" class="menu-logo"></a>
				<em class="menu-sub-logo">SOPORTE Y MTTO. INTEGRAL</em>
				<div class="menu">
					<em class="menu-divider">Men&uacute; Principal<i class="fa fa-navicon"></i></em>';
					if ($nivel != 7):
					echo '<a class="menu-item" href="dashboard.php"><i class="font-15 fa color-blue-dark fa-bar-chart-o"></i><strong>Dashboard</strong></a>';
    				endif;
					echo '<!--<a class="menu-item" href="gestordoc.php"><i class="font-15 fa color-blue-dark fa-book"></i><strong>Gestor Documental</strong></a>-->';					
					if ($nivel == 1 || $nivel == 2  || $nivel == 3 || $nivel == 4 || $nivel == 5 || $nivel == 6 || $nivel == 7 || $val == 1):
						echo '<a class="menu-item" href="calendario.php"><i class="font-15 fa color-blue-dark fa-calendar"></i><strong>Calendario</strong></a>';
					endif;
					if ($nivel == 1 || $nivel == 2 ):
						echo '
						<!--<a class="menu-item" href="plan.php"><i class="font-15 fa color-blue-dark fa-tasks"></i><strong>Plan de Mtto.</strong></a>-->';
					endif;
					if ($nivel == 1 || $nivel == 2 || $nivel == 3 || $nivel == 4 || $nivel == 5 || $nivel == 6 || $nivel == 7 ):
						echo '
						<a class="menu-item" href="correctivos.php"><i class="font-15 fa color-blue-dark fa-tasks"></i><strong>Correctivos</strong></a>';
					endif;
					if ($nivel == 1 || $nivel == 2 || $nivel == 3 || ($nivel == 4 && $_SESSION['idclientes'] != 25 && $_SESSION['idusuario'] != 534 && $_SESSION['idusuario'] != 536 && $_SESSION['idusuario'] != 535) || $nivel == 5 || $nivel == 6 || $nivel == 7 ):
						echo '
						<a class="menu-item" href="preventivos.php"><i class="font-15 fa color-blue-dark fa-calendar-check-o"></i><strong>Preventivos</strong></a>';
					endif;
					if ($nivel==1 || $nivel==2):
					echo '
					<a class="menu-item" href="postventas.php"><i class="font-15 fa color-blue-dark fa-wpforms"></i><strong>Postventas</strong></a>';
					endif;
					//if ($nivel==1 || $usuario == 'mbatista' || $usuario == 'umague' || $usuario == 'mrodriguez' || $usuario == 'gdiaz' || $usuario == 'jgarate' || $usuario == 'adelvalle' || $usuario == 'icarvajal' || $usuario == 'frios' || $usuario == 'aporras'):
					if($esmaxia == 1 || $desarrollo !== false):
					echo '
					<a class="menu-item" href="laboratorio.php"><i class="font-15 fa color-blue-dark fa-wrench"></i><strong>Laboratorio</strong></a>';
					endif;
					if($esmaxia == 1 || $usuario == 'Mbaena' || $usuario == 'axel.anderson' || $usuario == 'maguero' || $desarrollo !== false):
					echo '
					<a class="menu-item" href="flotas.php"><i class="font-15 fa color-blue-dark fa-car"></i><strong>Flotas</strong></a>
					<a class="menu-item d-none" href="plan.php"><i class="font-15 fas color-blue-dark fa-check"></i><strong>Plan de Mtto.</strong></a>';
					endif;
					if ($nivel==1 || $nivel==2 || $nivel==7 || $res==1 || $usuario == 'abarrancos'):
					echo '
					<!--a class="menu-item" href="incidenteslab.php"><i class="font-15 fa color-blue-dark fa-tasks"></i><strong>Incidentes Lab.</strong></a-->
					<a class="menu-item" href="activos.php"><i class="font-15 fa color-blue-dark fa-cubes"></i><strong>Activos</strong></a>';
					endif;
					if ($nivel==1 || $nivel==2 || $nivel == 3  || $nivel == 5  || $nivel == 7):
					echo '
					<a class="menu-item" href="baseconocimientos.php"><i class="font-15 fa color-blue-dark fa-book"></i><strong>Base de Conocimientos</strong></a>';
					endif;
					if ($nivel==1 || $nivel==2):
					echo '
					<a class="menu-item" href="actas.php"><i class="font-16 fa color-blue-dark fa-file"></i><strong>Actas</strong></a>';
					endif;
					if ($nivel==1 || $nivel==2 || $nivel==7):
					echo '<a class="menu-item" data-submenu="sub-2" href="#"><i class="font-16 fa color-blue-dark fa-building-o"></i><strong>Maestros</strong></a>
					<div id="sub-2" class="submenu-item">';
					endif;
					if ($nivel==1 || $nivel==2):
					echo '<!--<a class="menu-item no-border" href="#"><strong>Listas de Correos</strong></a>-->
						<a class="menu-item no-border" href="clientes.php"><strong>Clientes</strong></a>
						<!--<a class="menu-item no-border" href="proyectos.php"><strong>Proyectos</strong></a>-->
						<a class="menu-item no-border" href="departamentos.php"><strong>Departamentos / Grupos</strong></a>';
					endif; 
					if ($nivel==1 || $nivel==2 || $nivel==7):
					echo '
						<a class="menu-item no-border" href="proveedores.php"><strong>Proveedores</strong></a>
						<a class="menu-item no-border" href="ambientes.php"><strong>Ubicaciones</strong></a>
						<a class="menu-item no-border" href="subambientes.php"><strong>Áreas</strong></a>
						<a class="menu-item no-border" href="tipos.php"><strong>Tipos</strong></a>
						<a class="menu-item no-border" href="subtipos.php"><strong>Subtipos</strong></a> 
						<a class="menu-item no-border" href="marcas.php"><strong>Marcas</strong></a> 
						<a class="menu-item no-border" href="modelos.php"><strong>Modelos</strong></a>'; 
					endif;
					if ($nivel==1 || $nivel==2):
					echo '<a class="menu-item no-border" href="estados.php"><strong>Estados</strong></a>
						<a class="menu-item no-border" href="niveles.php"><strong>Niveles</strong></a>
						<a class="menu-item no-border" href="prioridades.php"><strong>Prioridades</strong></a>';
					endif;
					if ($nivel==1 || $nivel==2 || $nivel==7):
					echo '</div>';
					endif;
					if ($nivel==1 || $nivel==2 || $nivel==7):
					echo '<a class="menu-item" data-submenu="sub-3" href="#"><i class="font-16 fa color-blue-dark fa-users"></i><strong>Seguridad</strong></a>
					<div id="sub-3" class="submenu-item">';
					echo '<a class="menu-item no-border" href="usuarios.php"><strong>Usuarios</strong></a>'; 
					endif;
					if ($nivel==1 || $nivel==2):
					echo '<a class="menu-item no-border" href="permisos.php"><strong>Notificaciones</strong></a>';
					endif;
					if ($nivel==1 || $nivel==2 || $nivel==7):	 
					echo '<a class="menu-item no-border" href="bitacora.php"><strong>Bit&aacute;cora</strong></a>';
					echo '</div>';
					endif;
					if ($nivel==1 || $nivel==2):
					echo '<a class="menu-item" href="ayudavideos.php"><i class="font-15 fa color-blue-dark fa-question-circle"></i><strong>Ayuda</strong></a>
					'; 
					endif;
					echo '
					<a href="cerrarsesion.php" class="menu-item close-menu" ><i class="font-14 fa color-orange-dark fa-times"></i><strong>Salir</strong></a>
					<ul class="link-list" style="padding-left:20px; padding-right:20px">		
					<li>
						<a class="inner-link-list" href="#"><i class="fa fa-angle-down"></i><i class="font-13 fa fa-key color-orange-dark"></i><span>Cambiar clave</span></a>
						<ul class="link-list">
							<li>
								<div class="input-simple-1 has-icon input-green full-bottom"><em>Nueva clave</em><i class="fa fa-key"></i><input type="password" id="nuevaclave" name="nuevaclave"></div>
									<a href="javascript:cambiarClave();" data-deploy-menu="notification" class="button regularbold button-green color-white">Cambiar Clave</a>
								</div>
							</li>
						</ul>
					</li>	
					</ul>
					
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
function cierreforzado()
{ 
	header('Location: cerrarsesion.php');
	die();
}

function permisosUrl(){
	$nivel = $_SESSION['nivel'];
	if (strpos($_SERVER['REQUEST_URI'], '?')!==false) {
		$temp=explode("?", $_SERVER['REQUEST_URI']);
		$_SERVER['REQUEST_URI']=$temp[0];
	}
	//Pertenece a Maxia
	$esmaxia = 0;
	$email   = $_SESSION['correousuario'];
	$dato    = explode('@', $email);
	$dominio = trim($dato[1]);
	if($dominio == 'maxialatam.com'){
		$esmaxia = 1;
	}
	//Desarrollo
	$iddepartamentos = (!empty($_SESSION['iddepartamentos']) ? $_SESSION['iddepartamentos'] : 0); 
	$desarrollo = strpos($iddepartamentos, '15'); 
	$sistema="soporte";
	 
	if ($_SERVER['REQUEST_URI']=='/'.$sistema.'/dashboard.php') {
		if ( $nivel != 7 ){}else{cierreforzado();}
	}elseif($_SERVER['REQUEST_URI']=='/'.$sistema.'/calendario.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/correctivos.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/correctivo.php'
		){
		if( $nivel == 1 || $nivel == 2 || $nivel == 3 || $nivel == 4 || $nivel == 5 || $nivel == 6 || $nivel == 7 ){}else{cierreforzado();}
	}elseif($_SERVER['REQUEST_URI']=='/'.$sistema.'/preventivos.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/preventivo.php'
		){
		if( $nivel == 1 || $nivel == 2 || $nivel == 3 || 
		($nivel == 4 && $_SESSION['idclientes'] != 25 && $_SESSION['idusuario'] != 534 && $_SESSION['idusuario'] != 536 && $_SESSION['idusuario'] != 535) 
		|| $nivel == 5 || $nivel == 6 || $nivel == 7 ){}else{cierreforzado();}
	}elseif($_SERVER['REQUEST_URI']=='/'.$sistema.'/activos.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/activo.php'
		){
		if( $nivel==1 || $nivel==2 || $nivel==5 || $nivel==7 || $res==1 || $usuario == 'abarrancos' ){}else{cierreforzado();}
	}elseif($_SERVER['REQUEST_URI']=='/'.$sistema.'/laboratorios.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/laboratorio.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/flotas.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/flota.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/plan.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/planactividad.php' 
		){
		if($esmaxia == 1 || $desarrollo !== false){}else{cierreforzado();}
	}elseif($_SERVER['REQUEST_URI']=='/'.$sistema.'/baseconocimientos.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/basedeconocimiento-v.php'
		){
		if ($nivel==1 || $nivel==2 || $nivel == 3  || $nivel == 5  || $nivel == 7){}else{cierreforzado();}
	}elseif($_SERVER['REQUEST_URI']=='/'.$sistema.'/usuarios.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/usuario.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/bitacora.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/bitacora-ne.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/proveedores.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/proveedor.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/ambientes.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/ambiente.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/subambientes.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/subambiente.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/tipos.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/tipo.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/subtipos.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/subtipo.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/marcas.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/marca.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/modelos.php' ||
			$_SERVER['REQUEST_URI']=='/'.$sistema.'/modelo.php' 
		){
		if ($nivel==1 || $nivel==2 ||  $nivel==5 || $nivel==7){}else{cierreforzado();}
	}elseif(
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/semaforo.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/asistente.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/postventas.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/postventa.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/reportes.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/clientes.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/cliente.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/proyectos.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/proyecto.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/categorias.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/categoria.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/subcategorias.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/subcategoria.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/departamentos.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/departamento.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/estados.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/estado.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/niveles.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/nivel.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/prioridades.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/prioridad.php' || 
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/permisos.php' ||
		$_SERVER['REQUEST_URI']=='/'.$sistema.'/permiso.php'
		){
		if ($nivel == 1 || $nivel == 2){}else{ cierreforzado();}
	}
}
function menuplantilla() {
	$nivel = $_SESSION['nivel'];
	
	//Pertenece a Maxia
	$esmaxia = 0;
	$email   = $_SESSION['correousuario'];
	$dato    = explode('@', $email);
	$dominio = trim($dato[1]);
	if($dominio == 'maxialatam.com'){
		$esmaxia = 1;
	}
	
	$iddepartamentos = (!empty($_SESSION['iddepartamentos']) ? $_SESSION['iddepartamentos'] : 0); 
	$desarrollo = strpos($iddepartamentos, '15'); 
	
	echo ' 
		<div class="deznav">
            <div class="deznav-scroll">
                <ul class="metismenu" id="menu">';
					if ($nivel != 7):
						echo '
						<li><a href="dashboard.php" aria-expanded="false" data-toggle="tooltip" title="Dashboard" data-placement="right">
								<i class="fa fa-bar-chart-o"></i>
								<span class="nav-text">Dashboard</span>
							</a>
						</li>
						';
					endif;
					
					if ($nivel == 1 || $nivel == 2 || $nivel == 3 || $nivel == 4 || $nivel == 5 || $nivel == 6 || $nivel == 7 || $val == 1):
						echo '
						<li><a href="calendario.php" aria-expanded="false" data-toggle="tooltip" title="Calendario" data-placement="right">
								<i class="fa fa-calendar"></i>
								<span class="nav-text">Calendario</span>
							</a>
						</li>';
					endif;
					if ($nivel==1 || $nivel==2):
						echo '	<li><a href="semaforo.php" data-toggle="tooltip" title="Semáforo de atención" data-placement="right">
										<i class="fa fa-traffic-light"></i>
										<span class="nav-text">Semáforo de atención</span>
									</a>
								</li>
								<!--<li><a href="asistente.php" data-toggle="tooltip" title="Configuración de proyectos" data-placement="right">
										<i class="fa fa-gears"></i>
										<span class="nav-text">Configuración de proyectos</span>
									</a>
								</li>-->
						';
						endif;
					if ($nivel == 1 || $nivel == 2 || $nivel == 3 || $nivel == 4 || $nivel == 5 || $nivel == 6 || $nivel == 7 ):
						echo '
						<li><a href="correctivos.php" aria-expanded="false" data-toggle="tooltip" title="Correctivos" data-placement="right">
								<i class="fa fa-tasks"></i>
								<span class="nav-text">Correctivos</span>
							</a>
						</li>';
					endif;
					if ($nivel == 1 || $nivel == 2 || $nivel == 3 || ($nivel == 4 && $_SESSION['idclientes'] != 25 && $_SESSION['idusuario'] != 534 && $_SESSION['idusuario'] != 536 && $_SESSION['idusuario'] != 535) || $nivel == 5 || $nivel == 6 || $nivel == 7 ):
						echo '
						<li><a href="preventivos.php" aria-expanded="false" data-toggle="tooltip" title="Preventivos" data-placement="right">
								<i class="fa fa-calendar-check-o"></i>
								<span class="nav-text">Preventivos</span>
							</a>
						</li>';
					endif;
					if ($nivel==1 || $nivel==2):
						echo '
						<li><a href="postventas.php" data-toggle="tooltip" title="Postventas" data-placement="right">
								<i class="fa fa-wpforms"></i>
								<span class="nav-text">Postventas</span>
							</a>
						</li>';
					endif;
					//if ($nivel==1 || $usuario == 'mbatista' || $usuario == 'umague' || $usuario == 'mrodriguez' || $usuario == 'gdiaz' || $usuario == 'jgarate' || $usuario == 'adelvalle' || $usuario == 'icarvajal' || $usuario == 'frios' || $usuario == 'aporras'):
					if($esmaxia == 1 || $desarrollo !== false):
						echo '
						<li><a href="laboratorios.php" data-toggle="tooltip" title="Laboratorio" data-placement="right">
								<i class="fa fa-wrench"></i>
								<span class="nav-text">Laboratorio</span>
							</a>
						</li>';
					endif;
					if($esmaxia == 1 || $desarrollo !== false):
						echo '
						<li><a href="flotas.php" data-toggle="tooltip" title="Solicitudes de Flotas" data-placement="right">
								<i class="fa fa-car"></i>
								<span class="nav-text">Solicitudes de Flotas</span>
							</a>
							</li>
							<li>
							<a href="plan.php" data-toggle="tooltip" title="Plan de Mantenimiento" data-placement="right">
								<i class="fas fa-check"></i>
								<span class="nav-text">Plan de Mtto.</span>
							</a>
						</li>';
					endif;
					if($nivel == 1 || $nivel==2):
						echo '
							<li><a href="reportes.php" data-toggle="tooltip" title="Reportes" data-placement="right">
									<i class="fa fa-file-excel-o"></i>
									<span class="nav-text">Reportes</span>
								</a>
							</li>';
					endif;
					if ($nivel==1 || $nivel==2 || $nivel==5 || $nivel==7 || $res==1 || $usuario == 'abarrancos'):
						echo '
						<!--a class="menu-item" href="incidenteslab.php"><i class="font-15 fa color-blue-dark fa-tasks"></i><strong>Incidentes Lab.</strong></a-->
						<li><a href="activos.php" data-toggle="tooltip" title="Activos" data-placement="right">
								<i class="fa fa-cubes"></i>
								<span class="nav-text">Activos</span>
							</a>
						</li>';
					endif;
					if ($nivel==1 || $nivel==2 || $nivel == 3  || $nivel == 5  || $nivel == 7):
						echo '
						<li><a href="baseconocimientos.php" data-toggle="tooltip" title="Base de Conocimientos" data-placement="right">
								<i class="fa fa-book"></i>
								<span class="nav-text">Base de Conocimientos</span>
							</a>
						</li>';
					endif;
					if ($nivel==1 || $nivel==2):
						echo '
						<li><a href="actas.php" data-toggle="tooltip" title="Actas" data-placement="right">
								<i class="fa fa-file"></i>
								<span class="nav-text">Actas</span>
							</a>
						</li>
						';
					endif;
					
					if ($nivel==1 || $nivel==2 || $nivel==5 || $nivel==7):
					echo '
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <i class="fa fa-building-o"></i>
                            <span class="nav-text">Maestros</span>
                        </a>
                        <ul aria-expanded="false">';
						if ($nivel==1 || $nivel==2):
							echo' 
								<li><a href="clientes.php">Clientes</a></li>
								<li><a href="proyectos.php">Proyectos</a></li>
								<li><a href="categorias.php">Categorías</a></li>
								<li><a href="subcategorias.php">Subcategorías</a></li>
								<li><a href="departamentos.php">Departamentos / Grupos</a></li>
							';
						endif; 
						if ($nivel==1 || $nivel==2 || $nivel==5 || $nivel==7):
							echo'
								<li><a href="proveedores.php">Proveedores</a></li>
								<li><a href="ambientes.php">Ubicaciones</a></li>
								<li><a href="subambientes.php">Áreas</a></li>
								<li><a href="tipos.php">Tipos</a></li>
								<li><a href="subtipos.php">Subtipos</a></li>
								<li><a href="marcas.php">Marcas</a></li>
								<li><a href="modelos.php">Modelos</a></li>
							';
						endif; 
						if ($nivel==1 || $nivel==2):
							echo'
								<li><a href="estados.php">Estados</a></li>
								<li><a href="niveles.php">Niveles</a></li>
								<li><a href="prioridades.php">Prioridades</a></li>
								<li><a href="publicidades.php">Publicidad APP</a></li>
							';
						endif; 
					echo '
                        </ul>
						</li>
						';
					endif;
					if ($nivel==1 || $nivel==2 ||  $nivel==5 || $nivel==7):
					echo '
						<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
								<i class="fas fa-shield-alt"></i>
								<span class="nav-text">Seguridad</span>
							</a>
							<ul aria-expanded="false">';
					endif;
					if ($nivel==1 || $nivel==2 ||  $nivel==5 || $nivel==7):
							echo '<li><a href="usuarios.php">Usuarios</a></li>';
							echo '<li><a href="bitacora.php">Bitácora</a></li>';
					endif;
					if ($nivel==1 || $nivel==2):
							echo '<li><a href="permisos.php">Notificaciones</a></li>';
					endif;
					if ($nivel==1 || $nivel==2 ||  $nivel==5 || $nivel==7):
					echo '</ul>
						</li>
					'; 
					endif;
					if ($nivel==1 || $nivel==2):
						echo '
						<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false" data-toggle="tooltip" title="Ayudas" data-placement="right">
								<i class="far fa-question-circle"></i>
								<span class="nav-text">Ayudas</span>
							</a> 
						</li>';
					endif;
					echo '<li><a href="cerrarsesion.php" aria-expanded="false" data-toggle="tooltip" title="Cerrar sesión" data-placement="right">
							<i class="fa fa-sign-out-alt"></i>
							<span class="nav-text">Cerrar sesión</span>
						</a>
                    </li>
                </ul>
            </div>
        </div>
	';
}

function menuclientes() {
	$nivel = $_SESSION['nivel'];
	echo '
		<div id="menu-1" class="menu-wrapper menu-light menu-sidebar-left menu-large">
			<div class="menu-scroll">
				<a href="dashboard.php" class="menu-logo"></a>
				<em class="menu-sub-logo">SOPORTE Y MTTO. INTEGRAL</em>
				<div class="menu">
					<em class="menu-divider">Men&uacute; Principal<i class="fa fa-navicon"></i></em>
					<!--<a class="menu-item" href="gestordoc.php"><i class="font-15 fa color-blue-dark fa-book"></i><strong>Gestor Documental</strong></a> -->
					<a class="menu-item" href="incidentes.php"><i class="font-15 fa color-blue-dark fa-tasks"></i><strong>Incidentes</strong></a>
					<a class="menu-item" href="preventivos.php"><i class="font-15 fa color-blue-dark fa-calendar-check-o"></i><strong>Preventivos</strong></a>
    				<a class="menu-item" href="calendario.php"><i class="font-15 fa color-blue-dark fa-calendar"></i><strong>Calendario</strong></a>
					<a href="cerrarsesion.php" class="menu-item close-menu" ><i class="font-14 fa color-orange-dark fa-times"></i><strong>Salir</strong></a>
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
                    <label class="col-xs-12 control-label" for="textinput">Desde</label>
					<div class="input-group box-input">
					  <input type="text" id="filtro-desde" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar" onclick="document.getElementById(\'filtro-desde\').focus()"></i></span>
					</div>
					<label class="col-xs-12 control-label" for="textinput">Hasta</label>
					<div class="input-group box-input">
					  <input type="text" id="filtro-hasta" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar" onclick="document.getElementById(\'filtro-hasta\').focus()"></i></span>
					</div>
                </li>
            </ul>
        </div>
    </div>
	';
}

function configuracionFiltrosMasivos(){
	echo '
	<div class="fixed-plugin">
        <div class="dropdown show-dropdown">
            <a href="#" data-toggle="dropdown" onclick="abrirFiltrosMasivos()">
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
            </ul>
        </div>
    </div>
	';
}

function usuarioActual() {
	echo '
		<li class="light-blue">
			<a data-toggle="dropdown" href="#" class="dropdown-toggle">
				<img class="nav-user-photo" src="../repositorio-tema/assets/avatars/user.jpg" alt="Usuario actual" />
				<span class="user-info">
					<small>Bienvenido,</small>
					'.$_SESSION['nombreUsuario'].'
				</span>

				<i class="ace-icon fa fa-caret-down"></i>
			</a>

			<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
				<li>
					<a href="#">
						<i class="ace-icon fa fa-cog"></i>
						Configuraci&oacute;n
					</a>
				</li>

				<li>
					<a href="profile.html">
						<i class="ace-icon fa fa-user"></i>
						Perfil
					</a>
				</li>

				<li class="divider"></li>

				<li>
					<a href="#">
						<i class="ace-icon fa fa-power-off"></i>
						Cerrar sesi&oacute;n
					</a>
				</li>
			</ul>
		</li>
	';
}

	
function menuCalendario() {
	echo '
		<li class="">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon fa fa-calendar"></i>
				<span class="menu-text"> Per&iacute;odo </span>

				<b class="arrow fa fa-angle-down"></b>
			</a>

			<b class="arrow"></b>

			<ul class="submenu">
				<li class="">
					<i class="menu-icon fa fa-caret-right"></i>
					<form class="form-horizontal" role="form">
						<div class="form-group">
							<div class="col-xs-12 col-sm-9">
								<select multiple="" id="cmbAgnos" name="cmbAgnos[]" class="select2" data-placeholder="Selecione A&ntilde;o(s)...">
									<option value="">&nbsp;</option>
									<option value="2014">2014</option>
									<option value="2015">2015</option>
									<option value="2016">2016</option>
									<option value="2017">2017</option>
									<option value="2018">2018</option>
									<option value="2019">2019</option>
									<option value="2020">2020</option>
									<option value="2021">2021</option>
								</select>
							</div>
							<div class="col-xs-12 col-sm-9">
								<select multiple="" id="cmbMeses" name="cmbMeses[]" class="select2" data-placeholder="Selecione mes(es)...">
									<option value="">&nbsp;</option>
									<option value="0">Todo el a&ntilde;o</option>
									<option value="1">Enero</option>
									<option value="2">Febrero</option>
									<option value="3">Marzo</option>
									<option value="4">Abril</option>
									<option value="5">Mayo</option>
									<option value="6">Junio</option>
									<option value="7">Julio</option>
									<option value="8">Agosto</option>
									<option value="9">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>
						</div>
					</form>
					<b class="arrow"></b>
				</li>
			</ul>
		</li>		
	';
}


function menuUnidades() {
	global $connect;
	
	$cadena =  '
		<li class="">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon fa fa-desktop"></i>
				<span class="menu-text">
					Unidades
				</span>

				<b class="arrow fa fa-angle-down"></b>
			</a>

			<b class="arrow"></b>

			<ul class="submenu">
				<li class="">
					<a href="#" class="dropdown-toggle">
						<i class="menu-icon fa fa-caret-right"></i>

						Hospitales
						<b class="arrow fa fa-angle-down"></b>
					</a>

					<b class="arrow"></b>

					<ul class="submenu">
						<li class="">
							<form class="form-horizontal" role="form">
								<div class="form-group">
									<div class="col-xs-12 col-sm-9">
										<select multiple="" id="cmbHospitales" name="cmbHospitales[]" class="select2" data-placeholder="Selecione hospital(es)...">
											<option value="">&nbsp;</option>
											';
										$consulta = mysqli_query($connect, "select codigo, nombre from unidades where tipo = 'H'");
										while ( $fila = mysqli_fetch_array($consulta) )
											$cadena .= "<option value='$fila[0]'>".utf8_encode($fila[1])."</option>\n";
										$cadena .='
										</select>
									</div>
								</div>
							</form>
							<b class="arrow"></b>
						</li>
					</ul>
				</li>

				<li class="">
					<a href="#" class="dropdown-toggle">
						<i class="menu-icon fa fa-caret-right"></i>

						Policl&iacute;nicas
						<b class="arrow fa fa-angle-down"></b>
					</a>

					<b class="arrow"></b>

					<ul class="submenu">
						<li class="">
							<form class="form-horizontal" role="form">
								<div class="form-group">
									<div class="col-xs-12 col-sm-9">
										<select multiple="" id="cmbPoliclinicas" name="cmbPoliclinicas[]" class="select2" data-placeholder="Selecione policl&iacute;nica(s)...">
											<option value="">&nbsp;</option>
											';
										$consulta = mysqli_query($connect, "select codigo, nombre from unidades where tipo = 'P'");
										while ( $fila = mysqli_fetch_array($consulta) )
											$cadena .= "<option value='$fila[0]'>".utf8_encode($fila[1])."</option>\n";
										$cadena .='
										</select>
									</div>
								</div>
							</form>
							<b class="arrow"></b>
						</li>
					</ul>
				</li>
				
				<li class="">
					<a href="#" class="dropdown-toggle">
						<i class="menu-icon fa fa-caret-right"></i>

						ULAPS
						<b class="arrow fa fa-angle-down"></b>
					</a>

					<b class="arrow"></b>

					<ul class="submenu">
						<li class="">
							<form class="form-horizontal" role="form">
								<div class="form-group">
									<div class="col-xs-12 col-sm-9">
										<select multiple="" id="cmbUlaps" name="cmbUlaps[]" class="select2" data-placeholder="Selecione ulpas...">
											<option value="">&nbsp;</option>
											';
										$consulta = mysqli_query($connect, "select codigo, nombre from unidades where tipo = 'U'");
										while ( $fila = mysqli_fetch_array($consulta) )
											$cadena .= "<option value='$fila[0]'>".utf8_encode($fila[1])."</option>\n";
										$cadena .='
										</select>
									</div>
								</div>
							</form>
							<b class="arrow"></b>
						</li>
					</ul>
				</li>
			</ul>
		</li>
	';
	echo $cadena;
}

function menuModalidades() {
	echo '
		<li class="">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon fa fa-tag"></i>
				<span class="menu-text"> Modalidades </span>

				<b class="arrow fa fa-angle-down"></b>
			</a>

			<b class="arrow"></b>
			<ul class="submenu">
				<li class="">
					<i class="menu-icon fa fa-caret-right"></i>
					<form class="form-horizontal" role="form">
						<div class="form-group">
							<div class="col-xs-12 col-sm-9">
								<select multiple="" id="cmbModalidades" name="cmbModalidades[]" class="select2" data-placeholder="Selecciones modalida(es)...">
									<option value="\'\'">&nbsp;</option>
									<option value="\'DX\'">Radiolog&iacute;a Convencional</option>
									<option value="\'CT\'">Tomograf&iacute;a Computada</option>
									<option value="\'US\'">Ultrasonido</option>
									<option value="\'MG\'">Mamograf&iacute;a</option>
									<option value="\'MR\'">Resonancia Magn&eacute;tica</option>
									<option value="\'RF\'">Fluoroscop&iacute;a</option>
									<option value="\'XA\'">Angiograf&iacute;a</option>
									<option value="\'IO\'">Dental</option>
								</select>
							</div>
						</div>
					</form>
					<b class="arrow"></b>
				</li>
			</ul>
		</li>
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

function estudiosRealizados() {
	echo '
		<div class="infobox infobox-blue">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-file-image-o"></i>
			</div>

			<div class="infobox-data">
				<span class="infobox-data-number" id="estudiosRealizados"></span>
				<div class="infobox-content">Est. Realizados</div>
			</div>
			<!-- <div class="stat stat-important">4%</div> -->
		</div>
	';
}

function estudiosInformados() {
	echo '
		<div class="infobox infobox-black">
			<div class="infobox-progress">
				<div class="easy-pie-chart percentage" data-percent="" data-size="46" id="estudiosInformadosPorcGra">
					<span class="percent" id="estudiosInformadosPorc"></span>%
				</div>
			</div>

			<div class="infobox-data">
				<span class="infobox-data-number" id="estudiosInformados"></span>
				<div class="infobox-content">Est. Informados</div>
			</div>
		</div>
	';
}

function tblTecnicos() {
	echo '
		<div class="widget-box transparent">
			<div class="widget-header widget-header-flat">
				<h4 class="widget-title lighter">
					<i class="ace-icon fa fa-star orange"></i>
					Productividad T&eacute;cnicos
				</h4>

				<div class="widget-toolbar">
					<a href="#" data-action="collapse">
						<i class="ace-icon fa fa-chevron-up"></i>
					</a>
				</div>
			</div>

			<div class="widget-body">
				<div class="widget-main no-padding">
					<table class="table table-bordered table-striped">
						<thead class="thin-border-bottom">
							<tr>
								<th>
									<i class="ace-icon fa fa-caret-right blue"></i>Nombre
								</th>

								<th>
									<i class="ace-icon fa fa-caret-right blue"></i>Unidad
								</th>

								<th>
									<i class="ace-icon fa fa-caret-right blue"></i>Modalidades
								</th>

								<th>
									<i class="ace-icon fa fa-caret-right blue"></i>Cantidad
								</th>

								<th class="hidden-480">
									<i class="ace-icon fa fa-caret-right blue"></i>Valor
								</th>
							</tr>
						</thead>

						<tbody>
							<tr>
								<td>James Bond</td>
								<td>Panama</td>
								<td>internet.com</td>

								<td>
									<small>
										<s class="red">$29.99</s>
									</small>
									<b class="green">$19.99</b>
								</td>

								<td class="hidden-480">
									<span class="label label-info arrowed-right arrowed-in">on sale</span>
								</td>
							</tr>

						</tbody>
					</table>
				</div><!-- /.widget-main -->
			</div><!-- /.widget-body -->
		</div><!-- /.widget-box -->
	';
}


function pie() {
	echo '
		<div class="footer">
			<div class="footer-inner">
				<div class="footer-content">
					<span class="bigger-120">
						<strong>&copy; '.date('Y').' - Desarrollado por <a href="http://www.maxialatam.com" target="_blank"><img src="../repositorio-tema/assets/images/logomaxia16.png"> Maxia Latam</a></strong>
					</span>

				</div>
			</div>
		</div>';
}

function cargarTablaTecnicos() {
	global $connect;
	
	$agno = $_GET['cmbAgnos'];
	if ($agno=='null') 
		$agno = (int) date('Y');
	
	$mes = $_GET['cmbMeses'];
	if ($mes=='null') {
		$mes = (int) date('m');  
		$mes--;
		if ($mes==0) {
			$mes = 12;
			$agno --;
		}
	} else {
		if ($mes=='0') {
			$mes = "1,2,3,4,5,6,7,8,9,10,11,12";
		}
	}
	
	$hospitales = $_GET['cmbHospitales'];
	if ($hospitales=='null') 
		$hospitales = '';
	
	$policlinicas = $_GET['cmbPoliclinicas'];
	if ($policlinicas=='null') 
		$policlinicas = '';
	
	$ulaps = $_GET['cmbUlaps'];
	if ($ulaps=='null') 
		$ulaps = '';
	
	$unidad = $hospitales;
	if ($policlinicas != '') {
		if ($unidad == '')
			$unidad = $policlinicas;
		else
			$unidad = $unidad . ',' .  $policlinicas;
	}
	
	if ($ulaps != '') {
		if ($unidad == '')
			$unidad = $ulaps;
		else
			$unidad = $unidad . ',' .  $ulaps;
	}
	
	if ($unidad == '')
		$unidad='1000';
	
	$modalidades = $_GET['cmbModalidades'];
	if ($modalidades=='null') {
		$modalidades = "'CR', 'CT', 'DX', 'Ge', 'IO', 'MG', 'MR', 'OT', 'RF', 'US', 'XA'";
	}
	
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =3;
	if(!$sord) $sord ='ASC';

	$where = "";
	if ($_GET['_search'] == 'true') {
		$searchField = $_GET['searchField'];
		$searchOper = $_GET['searchOper'];
		$searchString = $_GET['searchString'];
		$where = getWhereClause($searchField,$searchOper,$searchString);
	}

	
	$SQL = "
		select 
			unidad, nombre, modalidad, dia, horario,
			count(*) as count 
		from tecnicos 
		where codigounidad in ($unidad) 
			and modalidad in ($modalidades) 
			and agno in ($agno) 
			and mes in ($mes) 
			$where 
		group by unidad, nombre, modalidad, dia, horario";
	
	$result = mysqli_query( $connect, $SQL );
	$count = mysqli_num_rows($result);
	
	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	
	$SQL = "
		select 
			id, 
			unidad, 
			nombre, 
			dia,
			horario,
			modalidad, 
			sum(realizados) as realizados, 
			sum(valor) as valor
		from tecnicos 
		where codigounidad in ($unidad) 
			and modalidad in ($modalidades) 
			and agno in ($agno) 
			and mes in ($mes) 
			$where 
		group by unidad, nombre, modalidad, dia, horario
		order by $sidx $sord 
		limit $start , $limit";

	$result = mysqli_query( $connect, $SQL );

	header("Content-type: text/xml;charset=utf-8");
 
	$s = "<?xml version='1.0' encoding='utf-8'?>";
	$s .=  "<rows>";
	$s .= "<page>".$page."</page>";
	$s .= "<total>".$total_pages."</total>";
	$s .= "<records>".$count."</records>";
	 
	// be sure to put text data in CDATA
	while($row = mysqli_fetch_array($result,MYSQL_ASSOC)) {
		$s .= "<row id='". $row['id']."'>";  
		$s .= "<cell>". $row['id']."</cell>";
		$s .= "<cell>". $row['unidad']."</cell>";
		$s .= "<cell>". $row['nombre']."</cell>";
		$s .= "<cell>". $row['dia']."</cell>";
		$s .= "<cell>". $row['horario']."</cell>";
		$s .= "<cell>". $row['modalidad']."</cell>";
		$s .= "<cell>". $row['realizados']."</cell>";
		$s .= "<cell><![CDATA[". $row['valor']."]]></cell>";
		$s .= "</row>";
	}
	$s .= "</rows>"; 
	 
	echo utf8_encode($s);
}

function exportarTablaTecnicos() {
	global $connect;
	
	if (isset($_REQUEST['cmbAgnos']) && $_REQUEST['cmbAgnos']!='') {
		$agnopost = $_REQUEST['cmbAgnos'];
		$agno = implode(",",$agnopost);
	} else 
		$agno = (int) date('Y');
	
	if (isset($_REQUEST['cmbMeses']) && $_REQUEST['cmbMeses']!='') {
		$mespost = $_REQUEST['cmbMeses'];
		$mes = implode(",",$mespost);
		if ($mes=="0") {
			$mes = "1,2,3,4,5,6,7,8,9,10,11,12";
		}
	} else {
		$mes = (int) date('m');  
		$mes--;
		if ($mes==0) {
			$mes = 12;
			$agno --;
		}
	}
	
	if (isset($_REQUEST['cmbHospitales']) && $_REQUEST['cmbHospitales']!='') {
		$hospitalespost = $_REQUEST['cmbHospitales'];
		$hospitales = implode(",",$hospitalespost);
	} else 
		$hospitales = '';
	
	if (isset($_REQUEST['cmbPoliclinicas']) && $_REQUEST['cmbPoliclinicas']!='') {
		$policlinicaspost = $_REQUEST['cmbPoliclinicas'];
		$policlinicas = implode(",",$policlinicaspost);
	} else 
		$policlinicas = '';
	
	if (isset($_REQUEST['cmbUlaps']) && $_REQUEST['cmbUlaps']!='') {
		$ulapspost = $_REQUEST['cmbUlaps'];
		$ulaps = implode(",",$ulapspost);
	} else 
		$ulaps = '';
	
	$unidad = $hospitales;
	if ($policlinicas != '') {
		if ($unidad == '')
			$unidad = $policlinicas;
		else
			$unidad = $unidad . ',' .  $policlinicas;
	}
	
	if ($ulaps != '') {
		if ($unidad == '')
			$unidad = $ulaps;
		else
			$unidad = $unidad . ',' .  $ulaps;
	}
	
	if ($unidad == '')
		$unidad='1000';
	
	if (isset($_REQUEST['cmbModalidades']) && $_REQUEST['cmbModalidades']!='') {
		$modalidadespost = $_REQUEST['cmbModalidades'];
		$modalidades = implode(",",$modalidadespost);
		//$modalidades .= ",'Ge'";
	} else {
		$modalidades = "'CR', 'CT', 'DX', 'Ge', 'IO', 'MG', 'MR', 'OT', 'RF', 'US', 'XA'";
		//$modalidades = '';
	}
	
	
	$SQL = "
		select id, unidad, nombre, dia, horario, modalidad, sum(realizados) as realizados, sum(valor) as valor
		from tecnicos 
		where codigounidad in ($unidad) 
			and modalidad in ($modalidades) 
			and agno in ($agno) 
			and mes in ($mes) 
		group by unidad, nombre, modalidad, dia, horario
		order by unidad, nombre, modalidad";
		
	$result = mysqli_query( $connect, $SQL );

	set_include_path(get_include_path() . PATH_SEPARATOR . '../repositorio-tema/assets/classes/');
	
	include_once 'PHPExcel.php';
	include_once 'PHPExcel/IOFactory.php';
	
	$estiloTituloReporte = array(
		'font' => array(
			'name'      => 'Verdana',
			'bold'      => true,
			'italic'    => false,
			'strike'    => false,
			'size' =>10,
			'color'     => array(
				'rgb' => '000000'
			)
		),
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			'rotation' => 0,
			'wrap' => TRUE
		)
	);
	 
	$estiloTituloColumnas = array(
		'font' => array(
			'name'  => 'Arial',
			'bold'  => true,
			'color' => array(
				'rgb' => 'FFFFFF'
			)
		),
		'fill' => array(
			'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
	  'rotation'   => 90,
			'startcolor' => array(
				'rgb' => 'b4b4b4'
			),
			'endcolor' => array(
				'argb' => '808080'
			)
		),
		'borders' => array(
			'top' => array(
				'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
				'color' => array(
					'rgb' => '143860'
				)
			),
			'bottom' => array(
				'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
				'color' => array(
					'rgb' => '143860'
				)
			)
		),
		'alignment' =>  array(
			'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			'wrap'      => TRUE
		)
	);
	 
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("CSS - Maxia Dashboard");
	$objPHPExcel->getProperties()->setLastModifiedBy("CSS - Maxia Dashboard");
	$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Dashboard");
	$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Productividad Equipos");
	$objPHPExcel->getProperties()->setDescription("Productividad de Equipos, generado por el sistema Dashboard de la CSS.");
	$objPHPExcel->getProperties()->setKeywords("office 2007 openxml php");
	$objPHPExcel->getProperties()->setCategory("Dashboard");
	
	$objPHPExcel->setActiveSheetIndex(0);
	
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(10); 	
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('LogoCSS');
	$objDrawing->setDescription('LogoCSS');
	$logo = '../repositorio-tema/assets/images/teleradiologia.png'; // Provide path to your logo file
	$objDrawing->setPath($logo);  //setOffsetY has no effect
	$objDrawing->setCoordinates('A1');
	$objDrawing->setHeight(50); // logo height
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet()); 
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);		
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
	
	$objPHPExcel->getActiveSheet()->mergeCells('B1:H1');
	$objPHPExcel->getActiveSheet()->mergeCells('B2:H2');
	$objPHPExcel->getActiveSheet()->mergeCells('B3:H3');
	
	//$objPHPExcel->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
	
	$objPHPExcel->getActiveSheet()->setCellValue('B1','CAJA DEL SEGURO SOCIAL');
	$objPHPExcel->getActiveSheet()->setCellValue('B2','DEPARTAMENTO NACIONAL DE RADIOLOGÃ�A MÃ‰DICA');
	$objPHPExcel->getActiveSheet()->setCellValue('B3','REPORTE PRODUCTIVIDAD TECNICOS POR DIA Y HORARIO');
	$objPHPExcel->getActiveSheet()->setCellValue('F4','Fecha del Reporte: '.date('d/M/Y'));
	$fila = 6;
	
	$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->applyFromArray($estiloTituloReporte);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':H'.$fila)->applyFromArray($estiloTituloColumnas);
	
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, 'Unidad');
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, 'Nombre');
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, 'Dia');
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, 'Horario');
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, 'Modalidad');
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, 'Realizados');
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, 'Valor');
	
	$fila++;
	
	$totalValor = 0;
	$totalRealizados = 0;
	while($row = mysqli_fetch_array($result,MYSQL_ASSOC)) {
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $row['unidad']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, utf8_encode($row['nombre']));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, utf8_encode($row['dia']));
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, utf8_encode($row['horario']));
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, utf8_encode($row['modalidad']));
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, utf8_encode($row['realizados']));
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $row['valor']);
		$totalValor += $row['valor'];
		$totalRealizados += $row['realizados'];
		$fila++;
	}
	$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':H'.$fila)->applyFromArray($estiloTituloColumnas);
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $totalRealizados);
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $totalValor);
	//$objPHPExcel->getActiveSheet()->getStyle('A7:G'.$fila)->applyFromArray($estiloInformacion);
	$objPHPExcel->getActiveSheet()->setTitle('Productividad Tecnicos');
	
	$objPHPExcel->setActiveSheetIndex(0);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$archivo = '../repositorio-tema/assets/xls/productividadTecnicos'.date('Ymd').'.xlsx';
	$objWriter->save($archivo);
	
	$response = array(
		 'success' => true,
		 'archivo' => $archivo
	 );
	echo json_encode($response);
	//	echo $archivo;
}


?>