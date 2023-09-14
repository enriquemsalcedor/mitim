<?php
header('Content-Type: text/html; charset=utf-8');
//error_reporting(1);
require_once("../conexion.php");
//require_once("../conexion2.php");
require_once("class.imap.php");
require_once("Encoding.php");
use \ForceUTF8\Encoding;

$mailbox = 'outlook.office365.com';
$port = '993'; 

$username = 'soportemitim@outlook.com';
$password = 'S3rv1c0W3b.';

//$username = 'soporte@mitim.app';
//$password = 'Dragonfly06@';

//$username = 'pruebaslocalessistemas@outlook.com';
//$password = 'avefenix123';


$imap = new Imap();
$connection_result = $imap->connect('{outlook.office365.com:993/imap/ssl}INBOX', $username, $password);
if ($connection_result !== true) {
	echo 'Error:'.$connection_result; 
	exit;
}
$messages = $imap->getMessages('html'); 

function limpiarUTF8($string){
	$stringlm = str_replace('ï¿½','',$string);
	$stringf  = Encoding::fixUTF8($stringlm); 
	$valoract = html_entity_decode($stringf);
	$valornvo    = htmlspecialchars_decode($valoract);
	return $valornvo;
}
foreach($messages as $v) {
	$idemail = $v['uid'];	
	$fechacreacion = date("Ymd"); //$msg->date;
	$horacreacion = date("H:i:s"); // $msg->time;
	$solicitante = $v['from']['address'];
	$titulo = $v['subject'];
	
	if (strpos($v['from']['address'],'gmail') !== false)
		$v['message'] = utf8_decode($v['message']);
	$descripcion = trim(strip_tags($v['message']));
	
	$titulo = str_replace("'"," ",$titulo);
	$titulo = str_replace("´"," ",$titulo);
	$descripcion = str_replace("'"," ",$descripcion);
	$descripcion = str_replace("´"," ",$descripcion);
	$titulo = ($titulo!="") ? trim($titulo) : "Sin Asunto"; 
	$titulo_leads_mitim = strpos($titulo, "Nuevo envío de Contacto web");
	$crearincidente = 0; 
	 
	if ($titulo_leads_mitim !== false) {
		if($titulo != "Sin Asunto"){
			$ncomentario	= strpos($titulo, " - ");
			$nincidente		= strpos($titulo, " ha ");
			if ($ncomentario !== false) {
				$arrTitulo = explode(' - ',$titulo);
			}
			if ($nincidente !== false) {
				$arrTitulo = explode(' ha ',$titulo);
			}
			$arrIncidente = explode('#',$arrTitulo[0]);
			$incidente = $arrIncidente[1];
				
			//USUARIO
			$queryU  	= " SELECT id,usuario FROM usuarios WHERE correo = '$solicitante' ";
			$resultU 	= $mysqli->query($queryU);
			if($resultU->num_rows > 0){
				$rowU 		= $resultU->fetch_assoc();
				$sesusuario	= $rowU['usuario'];
				$idusuario	= $rowU['id'];
			}else{
				$sesusuario = 'correo sin registrar';
				$idusuario	= 0;
			}
			 
			$descripcion = limpiarUTF8($descripcion);							
			$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';
			preg_match ( $pattern, $solicitante, $solicitante );
			if($solicitante[0] != 'viva-noreply@microsoft.com' && $solicitante[0] != 'Viva-noreply@microsoft.com' && $solicitante[0] != 'no-reply@microsoft.com' && $solicitante[0] != 'mailer-daemon@googlemail.com' && $solicitante[0] != 'cortana@microsoft.com' && $solicitante[0] != 'support@byondit.zohodesk.com'){									
					
				$query = "INSERT INTO incidentes(id, titulo, descripcion, idambientes, idsubambientes, idestados, idcategorias, idsubcategorias, idprioridades, origen, creadopor, 
						solicitante, asignadoa, departamento, fechacreacion, horacreacion, notificar, idemail, fechareal, horareal, idempresas, idclientes, 
						idproyectos, iddepartamentos,tipo)
						VALUES(null, '$titulo', '$descripcion', '0', '0', '3', '0', 
						'0', '2', 'email', '".$solicitante[0]."', '".$solicitante[0]."', '', '','$fechacreacion', 
						'$horacreacion', '', '$idemail', '$fechacreacion', '$horacreacion', 1, 0, 0, 0, 'incidentes') ";
					echo 'QUERY:'.$query;
			} 
			//debug($query);
			if($mysqli->query($query)){
				$id = $mysqli->insert_id; 
				
				//CREAR REGISTRO EN ESTADOS INCIDENTES
				$queryE = " INSERT INTO incidentesestados VALUES(null, $id, 12, 12, $idusuario, now(), now(), 0) ";
				$mysqli->query($queryE);
										
				//CREAR CARPETA DE ID INCIDENTES Y COMENTARIOS
				$myPath = '../incidentes/';
				if (!file_exists($myPath))
					mkdir($myPath, 0777);
				$myPath = '../incidentes/'.$id.'/';
				$target_path2 = utf8_decode($myPath);
				if (!file_exists($target_path2))
					mkdir($target_path2, 0777);
				// Si se descargaron comentarios se mueven a la carpeta del incidente
				if (count($v['attachments'])>0) {
					for($i=0;$i<count($v['attachments']);$i++) {
						rename('attachments/'.$v['attachments'][$i],'../incidentes/'.$id.'/'.$v['attachments'][$i]);
					}
				}
				bitacora($sesusuario, "Incidentes Correo", 'El Incidente #'.$id.' ha sido Creado exitosamente', $id, $query);				
			}  	
		}		
	} 
	 
}  

?>