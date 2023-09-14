<?php
$mysqli = new mysqli("127.0.0.1", "root", "M4X14W3B", "soporte");
//$mysqli = new mysqli("127.0.0.1", "root", "", "soporte");
if ($mysqli->connect_error) {
    echo "Fallo al conectar a MySQL: (" . $mysqli->connect_error . ") " . $mysqli->connect_error;
}
$sistemaactual = 'SYM';
$mysqli->query("SET NAMES utf8"); 
$mysqli->query("SET CHARACTER SET utf8");
/* 
if (session_status() !== PHP_SESSION_ACTIVE) {
	//ini_set("session.cookie_lifetime","14400");
	//ini_set("session.gc_maxlifetime","14400");
	session_start();
} */
/* ini_set("session.cookie_lifetime","14400");
ini_set("session.gc_maxlifetime","14400"); */
//session_start();
require 'zebra/Zebra_Session.php';
$session = new Zebra_Session($mysqli, 'sEcUr1tY_c0dE', $session_lifetime = 604800);
/*
ini_set(
    'session.cookie_domain',
    substr($_SERVER['SERVER_NAME'], strpos($_SERVER['SERVER_NAME'], '.'))
); 
*/
include("funcionesbitacora.php");
include("funcionespermisos.php");
 
/* if (isset($_COOKIE['usuario']) && isset($_COOKIE['clave']) && isset($_COOKIE['nivel'])) {
	$_SESSION['usuario']=$_COOKIE['usuario'];
	$_SESSION['clave']=$_COOKIE['clave'];
	$_SESSION['nivel']=$_COOKIE['nivel'];
}
if (isset($_COOKIE['user_id']) && isset($_COOKIE['nombreUsuario'])) {
	$_SESSION['user_id']=$_COOKIE['user_id'];
	$_SESSION['nombreUsuario']=$_COOKIE['nombreUsuario'];
}	
if (isset($_COOKIE['sitio']) && isset($_COOKIE['correousuario'])) {
	$_SESSION['sitio']=$_COOKIE['sitio'];
	$_SESSION['correousuario']=$_COOKIE['correousuario'];
} */

/* if (!isset($_SESSION['unidadEjecutora']))
	$_SESSION['unidadEjecutora']='';

if (!isset($_SESSION['modalidad']))
	$_SESSION['modalidad']='';
 */
ini_set('max_execution_time', 96000); 
ini_set('SMTP', 'smtp-mail.outlook.com'); 
ini_set('sendmail_from', 'toolkit@maxialatam.com'); 

date_default_timezone_set("America/Panama");

$account="toolkit@maxialatam.com";
$password="9uL!JeWCAG3nzMNV";

//include_once("phpmailer/PHPMailerAutoload.php");
include_once("phpmailer\src\PHPMailer.php");
include_once("phpmailer\src\SMTP.php");
include_once("phpmailer\src\Exception.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!isset($mail)) {
	$mail = new PHPMailer();
	//$mail->SMTPDebug = 3;
	$mail->IsSMTP();
	$mail->CharSet = 'UTF-8';
	$mail->Host = "smtp.office365.com";
	$mail->SMTPAuth= true;
	$mail->Port = 587;
	$mail->From = "toolkit@maxialatam.com";
	$mail->setFrom('toolkit@maxialatam.com', 'Maxia - Toolkit');
    $mail->addReplyTo('soporte@maxialatam.com', 'Maxia - Toolkit');
	$mail->Username= $account;
	$mail->Password= $password;
	$mail->SMTPSecure = 'tls';
}

function logDebug($descripcion, $txt) { 
	$descripcion = trim($descripcion);		
	$f = fopen("log_$descripcion.txt", "a"); 
	fwrite($f, $txt."\n \n"); 		
	fclose($f); 	
}

function debug($txt) { 		
	$f = fopen("debug.txt", "w"); 
	fwrite($f, $txt); 		
	fclose($f); 	
}

function debugL($txt,$fileName='debugL') { 		
	$fileName.='.txt';
	$f = fopen($fileName, "a"); 
	fwrite($f, $txt.PHP_EOL); 		
	fclose($f);
}

function debugG($txt,$fileName='debugL') { 		
	$fileName.='.txt';
	$f = fopen($fileName, "a"); 
	fwrite($f, $txt.PHP_EOL); 		
	fclose($f);
}


function valorImp() {
	$valorImp = 0.7;
	return $valorImp;
}

function bitacora($usuario, $modulo, $accion, $identificador, $sentencia) { 
	global $mysqli;
	$sentencia = str_replace("'"," ",$sentencia);
	$query = "Insert Into bitacora Values(null, '$usuario', now(), '$modulo', '$accion', '$identificador', '$sentencia')";
	//debug($query);
	$consulta = $mysqli->query($query); 	
} 

function calendario($usuario, $fecha, $modulo, $referencia) {
	global $mysqli;
	$query = "Insert Into calendario Values(null, '$usuario', '$fecha', '$modulo', '$referencia')";
	//debug($query);
	$consulta = $mysqli->query($query);
}

function getWhereClause($col, $oper, $val){
    //array to translate the search type
	//debug($col.', '.$oper.', '.$val);
	$ops = array(
		'eq'=>'=', //equal
		'ne'=>'<>',//not equal
		'lt'=>'<', //less than
		'le'=>'<=',//less than or equal
		'gt'=>'>', //greater than
		'ge'=>'>=',//greater than or equal
		'bw'=>'LIKE', //begins with
		'bn'=>'NOT LIKE', //doesn't begin with
		'in'=>'LIKE', //is in
		'ni'=>'NOT LIKE', //is not in
		'ew'=>'LIKE', //ends with
		'en'=>'NOT LIKE', //doesn't end with
		'cn'=>'LIKE', // contains
		'nc'=>'NOT LIKE'  //doesn't contain
	);
	
    if($oper == 'bw' || $oper == 'bn') $val .= '%';
    if($oper == 'ew' || $oper == 'en' ) $val = '%'.$val;
    if($oper == 'cn' || $oper == 'nc' || $oper == 'in' || $oper == 'ni') $val = '%'.$val.'%';
    return " and $col {$ops[$oper]} '$val' ";
}


function getWhereClauseFilters($filters) {
		global $mysqli;
		//debug($filters);
		
		$filters = json_decode($filters);
        $where = " AND ";
        $whereArray = array();
        $rules = $filters->rules;
        $groupOperation = $filters->groupOp;
        foreach($rules as $rule) {

            $fieldName = $rule->field;
            $fieldData = $mysqli->real_escape_string($rule->data);
            switch ($rule->op) {
           case "eq":
                $fieldOperation = " = '".$fieldData."'";
                break;
           case "ne":
                $fieldOperation = " != '".$fieldData."'";
                break;
           case "lt":
                $fieldOperation = " < '".$fieldData."'";
                break;
           case "gt":
                $fieldOperation = " > '".$fieldData."'";
                break;
           case "le":
                $fieldOperation = " <= '".$fieldData."'";
                break;
           case "ge":
                $fieldOperation = " >= '".$fieldData."'";
                break;
           case "nu":
                $fieldOperation = " = ''";
                break;
           case "nn":
                $fieldOperation = " != ''";
                break;
           case "in":
                $fieldOperation = " IN (".$fieldData.")";
                break;
           case "ni":
                $fieldOperation = " NOT IN '".$fieldData."'";
                break;
           case "bw":
                $fieldOperation = " LIKE '".$fieldData."%'";
                break;
           case "bn":
                $fieldOperation = " NOT LIKE '".$fieldData."%'";
                break;
           case "ew":
                $fieldOperation = " LIKE '%".$fieldData."'";
                break;
           case "en":
                $fieldOperation = " NOT LIKE '%".$fieldData."'";
                break;
           case "cn":
                $fieldOperation = " LIKE '%".$fieldData."%'";
                break;
           case "nc":
                $fieldOperation = " NOT LIKE '%".$fieldData."%'";
                break;
            default:
                $fieldOperation = "";
                break;
                }
            if($fieldOperation != "") $whereArray[] = $fieldName.$fieldOperation;
        }
        if (count($whereArray)>0) {
            $where .= join(" ".$groupOperation." ", $whereArray);
        } else {
            $where = "";
        }
		return $where;
}

function fechaDMA($fecha) { 	
	$ano = substr($fecha,0,4); 		
	$mes = substr($fecha,5,2); 		
	$dia = substr($fecha,8,2); 		
	return ($dia."/".$mes."/".$ano); 	
} 	 	

function fechaDMA2($fecha) { 	
	$ano = substr($fecha,0,4); 		
	$mes = substr($fecha,4,2); 		
	$dia = substr($fecha,6,2); 		
	return ($dia."/".$mes."/".$ano); 	
}  	

function fechaAMD($fecha) { 	
	$ano = substr($fecha,strlen($fecha)-4,4); 	
	$dia = substr($fecha,0,2); 		
	if  (substr($dia,1,1)=="/"){ 		
		$dia = "0".substr($dia,0,1); 		
		$mes = substr($fecha,2,2); 	
	} else { 			
		$mes = substr($fecha,3,2); 		
	}
 	if  (substr($mes,1,1)=="/"){ 	
		$mes = "0".substr($mes,0,1); 	
	} 		
	return ($ano.$mes.$dia); 
} 	 	

function fechaAMD2($fecha) { 	
	$ano = substr($fecha,strlen($fecha)-4,4);
	$dia = substr($fecha,0,2); 
	if  (substr($dia,1,1)=="/"){ 	
		$dia = "0".substr($dia,0,1); 	
		$mes = substr($fecha,2,2); 	
	} else { 		
		$mes = substr($fecha,3,2); 	
	} 	
	if  (substr($mes,1,1)=="/"){ 	
		$mes = "0".substr($mes,0,1); 		
	} 		
	return ($ano."-".$mes."-".$dia); 	
} 	 	

function fecha_actual($fecha) { 	
	$months = array ("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"); 	$ano = substr($fecha,0,4); 	$mes = substr($fecha,5,2); 	$dia = substr($fecha,8,2); 	$date = $dia . " de " . $months[$mes] . " del " . $ano;  	return $date;   }			 	 	function fecha_actual2() { 	$months = array ("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"); 	
	$year_now = date ("Y"); 	
	$month_now = date ("n"); 	
	$day_now = date ("j"); 	
	$date = $day_now . " dias del mes de " . $months[$month_now] . " del " . $year_now;  	
	return $date;   
}		 	 	
	
function ceros($num, $longitud) { 		
	while (strlen($num) < $longitud) 			
		$num = '0'.$num; 		
	return $num; 	
} 	 	

function enviarMensajePedido($to, $mensaje) {
 	global $mail;
	//$destinatario = $correos; 	
	//$to="dycoronel@gmail.com";
	$from="daniel.coronel@maxialatam.com";
	$from_name="Maxia Toolkit - Gestión de Compras";
	$msg="<strong>$mensaje</strong>"; // HTML message
	$subject="HTML message";

	$asunto = "Registro de pedido cargado al Maxia Toolkit"; 		
	$cuerpo = "<html><head><title>Pedido de Materiales</title></head><body><p>$mensaje</p></body></html>"; 		
	
	$mail->From = $from;
	$mail->FromName= $from_name;
	$mail->addReplyTo('daniel.coronel@maxialatam.com', 'Daniel Coronel');
	$mail->isHTML(true);
	$mail->Subject = $asunto;
	$mail->Body = $msg;
	$mail->addAddress($to);
	
	if(!$mail->send()){
	 return "Failure";
	}else{
	 echo "Success";
	}
	
	
	/*$headers = "MIME-Version: 1.0\r\n"; 		
	//$headers .= "Content-type: text/html; charset=utf-8\r\n"; 		
	//$headers .= "From: daniel.coronel@maxialatam.com\r\n";  		
	//mail($destinatario,$asunto,$cuerpo,$headers);  	
	
	require_once 'phpmail/PHPMailerAutoload.php';

	$mail = new PHPMailer;
	$mail->setFrom('daniel.coronel@maxialatam.com', 'Daniel Coronel');
	$mail->addAddress('daniel.coronel@maxialatam.com', 'Daniel Coronel');
	$mail->Subject = $asunto;
	//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
	$mail->msgHTML($mensaje);
	//$mail->AltBody = 'This is a plain-text message body';
	//$mail->addAttachment('images/phpmailer_mini.png');

	//send the message, check for errors
	if (!$mail->send()) {
		return "Error: " . $mail->ErrorInfo;
	} else {
		return "Success";
	}*/
}  
	
function json_decodificar($grid,$del_ini_externo,$del_fin_interno){
	$str=substr($grid,1,strlen($grid)-2);
	$nro_filas = substr_count($str,$del_ini_externo);
	
	$arreglo = array();
	$cad="";
	$resto=$str;
	
	for($i=0;$i<$nro_filas;$i++){
		$posicion = strpos($resto, $del_fin_interno);
		if ($posicion !== false) {
			$cad = substr($resto,0,$posicion+1);
			$resto = substr($resto,$posicion+2);
			$nro_columnas = substr_count($cad,",");
			
			$resto_fila = substr($cad,1,strlen($cad));
			$arreglo[$i] = array();
			for($j=0;$j<$nro_columnas;$j++){
				$pos = strpos($resto_fila, ",");
				if ($pos !== false) {
					$cad_fila = substr($resto_fila,0,$pos);
					$resto_fila = substr($resto_fila,$pos+1);
					$arreglo[$i][$j] = $cad_fila;
				}
			}
			$arreglo[$i][$j] = substr($resto_fila,0,strlen($resto_fila)-1);
		}
	}
	return $arreglo;
}

function contarendirectorio($ruta){ 
   
   if (is_dir($ruta)) { 
      if ($dh = opendir($ruta)) { 
         while (($file = readdir($dh)) !== false) { 
            //esta línea la utilizaríamos si queremos listar todo lo que hay en el directorio 
            //mostraría tanto archivos como directorios 
            //echo "<br>Nombre de archivo: $file : Es un: " . filetype($ruta . $file); 
            if (is_dir($ruta . $file) && $file!="." && $file!=".."){ 
               //solo si el archivo es un directorio, distinto que "." y ".." 
               echo "<br>Directorio: $ruta$file"; 
               listar_directorios_ruta($ruta . $file . "/"); 
            } 
         } 
      closedir($dh); 
      } 
   }else 
      echo "<br>No es ruta valida"; 
}

function directoriovacio($dir, $numero) {
	$cont = 0;	
	if ($numero != '') {
		$directorio = "../$dir/$numero/";
		if (file_exists($directorio)) {
			if ($dh = opendir($directorio)) { 
				while (($file = readdir($dh)) !== false) { 
					if ($file!="." && $file!=".." && $file!=".quarantine" && $file!=".tmb" && $file!="comentarios"){ 
					   $cont++;
					} 
				 }
			}
		}
		$directorio = "../$dir/$numero/comentarios/";
		if (file_exists($directorio)) {
			if ($dh = opendir($directorio)) { 
				while (($file = readdir($dh)) !== false) { 
					if ($file!="." && $file!=".." && $file!=".quarantine" && $file!=".tmb"){ 
					   $cont++;
					} 
				 }
			}
		}
	} 
	if ($cont > 0) {
		$is_empty = 1;
	} else {
		$is_empty = 0;
	}
	return $is_empty;	
}
//Obtener IP
function getRealIP(){
    if (isset($_SERVER["HTTP_CLIENT_IP"]))
    {
        return $_SERVER["HTTP_CLIENT_IP"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
    {
        return $_SERVER["HTTP_X_FORWARDED"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED"]))
    {
        return $_SERVER["HTTP_FORWARDED"];
    }
    else
    {
        return $_SERVER["REMOTE_ADDR"];
    }
}
//Obtener Navegador 
function getBrowser($user_agent){ 
	
	if(strpos($user_agent, 'MSIE') !== FALSE)
	   return 'Internet explorer';
	 elseif(strpos($user_agent, 'Edge') !== FALSE) //Microsoft Edge
	   return 'Microsoft Edge';
	 elseif(strpos($user_agent, 'Trident') !== FALSE) //IE 11
		return 'Internet explorer';
	 elseif(strpos($user_agent, 'Opera Mini') !== FALSE)
	   return "Opera Mini";
	 elseif(strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR') !== FALSE)
	   return "Opera";
	 elseif(strpos($user_agent, 'Firefox') !== FALSE)
	   return 'Mozilla Firefox';
	 elseif(strpos($user_agent, 'Chrome') !== FALSE)
	   return 'Google Chrome';
	 elseif(strpos($user_agent, 'Safari') !== FALSE)
	   return "Safari";
	 else
	   return 'No encontrado'; 
} 
?>