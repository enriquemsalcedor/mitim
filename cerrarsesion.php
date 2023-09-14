<?php
require 'conexion.php';
$PHPSESSID = $_COOKIE['PHPSESSID'];

$update = " UPDATE
			session_data_historial a 
			INNER JOIN session_data b ON a.session_id = b.session_id
			SET a.usuario =  '".$_SESSION['usuario']."', a.session_data = b.session_data, a.session_expire = b.session_expire, a.fechafin = now()
			WHERE a.session_id = '".$_COOKIE['PHPSESSID']."' AND a.fechasesion = b.fechasesion ";
$result = $mysqli->query($update);

if($result == true){
	setcookie("usuario", "", time()-3600);
	setcookie("user_id", "", time()-3600);
	setcookie("nombreUsuario", "", time()-3600);
	setcookie("nivel", "", time()-3600);
	setcookie("unidad", "", time()-3600);
	setcookie("sitio", "", time()-3600);
	setcookie("idempresas", "", time()-3600);
	setcookie("idclientes", "", time()-3600);
	setcookie("idproyectos", "", time()-3600);
	setcookie("iddepartamentos", "", time()-3600);
	setcookie("correousuario", "", time()-3600);
	$session->stop();
	header("Refresh:0; url=index.php");
}	
?>