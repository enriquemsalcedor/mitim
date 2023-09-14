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
//$username = 'toolkit@maxialatam.com';
//$password = '9uL!JeWCAG3nzMNV'; 

$username = 'desarrollomaxia@outlook.com';
$password = 'maxia123';

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
	debugL('SOLICITANTE:'.$solicitante.'-TÍTULO:'.$titulo,'DEBUGLSOLICITANTE');
	if (strpos($v['from']['address'],'gmail') !== false)
		$v['message'] = utf8_decode($v['message']);
	$descripcion = trim(strip_tags($v['message']));
	
	$titulo = str_replace("'"," ",$titulo);
	$titulo = str_replace("´"," ",$titulo);
	$descripcion = str_replace("'"," ",$descripcion);
	$descripcion = str_replace("´"," ",$descripcion);
	
	if ($titulo!=""){
		$titulo = trim($titulo);
	}else{
		$titulo = "Sin Asunto";
	}
	/*
	if ($solicitante == 'no-replymesadeayuda@innovacion.gob.pa') {
	    $solicitante = 'mesadeayuda@innovacion.gob.pa';
    }
	*/
	$poscoin1 = strpos($titulo, "Incidente #");	
	$poscoin2 = strpos($titulo, "Preventivo #");
	$poscoin3 = strpos($titulo, "[Ticket#");
	$poscoin4 = strpos($titulo, "[Mesa de Ayuda]");
	$poscoin5 = strpos($titulo, "[Service Desk]");
	$poscoin6 = strpos($titulo, "[Mesade Ayuda}");
	$poscoin7 = strpos($titulo, "Correctivo #");
	$poscoin8 = strpos($titulo, "Mail System Error");
	$crearincidente = 0;
	debugL('$titulo: '.$titulo);
	
	//CORREOS NO DESEADOS
	$nodeseado = 'ayuda@getsigmacare.com';
	if($solicitante != $nodeseado){
		if ($poscoin1 !== false || $poscoin2 !== false || $poscoin3 !== false || $poscoin4 !== false || $poscoin5 !== false || $poscoin6 !== false || $poscoin7 !== false || $poscoin8 !== false) {
			debugL('PASO if');
			if($titulo != "Sin Asunto"){
				if ($solicitante == 'no-replymesadeayuda@innovacion.gob.pa') {
					$solicitante = 'mesadeayuda@innovacion.gob.pa';
				}
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
				
				if ($poscoin3 !== false || $poscoin4 !== false || $poscoin5 !== false || $poscoin6 !== false) {
					if ($poscoin4 !== false || $poscoin5 !== false || $poscoin6 !== false) {
						$arrtitulo  = explode(':', $titulo);
						$arrnuminc  = $arrtitulo[0];
						$tinc = strpos($titulo, "INC ");
						$treq = strpos($titulo, "REQ ");
						if($tinc !== false){
							$arrnum 	= explode('INC ', $arrnuminc);
						}else{
							$arrnum 	= explode('REQ ', $arrnuminc);
						}
						$numinc 	= $arrnum[1];
						
						$queryMM  	= " SELECT id FROM incidentes WHERE titulo like '%$numinc%' ";
						$resultMM 	= $mysqli->query($queryMM); 
						if($resultMM->num_rows > 0){
							$rowMM 		= $resultMM->fetch_assoc();
							$incidente	= $rowMM['id'];
						}else{
							$crearincidente = 1;
						}
					}else{
						$queryMM  	= " SELECT id FROM incidentes WHERE titulo = '$titulo' ";
						$resultMM 	= $mysqli->query($queryMM);
						if($resultMM->num_rows > 0){
							$rowMM 		= $resultMM->fetch_assoc();
							$incidente	= $rowMM['id'];
						}else{
							$crearincidente = 1;
						}	
					}	
				}else{ //OPCIONES DE UN INCIDENTE O PRVENTIVO
					debugL('PASO OPCIONES');
					$posCom 	= strpos($titulo, " - Comentario");
					$posAsig 	= strpos($titulo, " ha sido Asignado");
					$posAct 	= strpos($titulo, " ha sido Actualizado");				
					$posAdj 	= strpos($titulo, " - Nuevo adjunto");
					$posRes 	= strpos($titulo, " ha sido Resuelto");
					
					if ($posCom !== false || $posAsig !== false || $posAct !== false || $posAdj !== false || $posRes !== false ) {
						$crearincidente = 0;
					}
				}
				
				if ($poscoin2 !== false) {
					$palabra = 'Preventivos';
				} else {
					$palabra = 'Incidentes';
				}
				
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
				
				if($crearincidente == 0){ //EL INCIDENTE ESTA CREADO
					debugL('INSERTAR COMENTARIO');
					$expdes = strpos($descripcion, 'De: ');
					//$expdes1 = strpos($descripcion, 'De: Maxia - Toolkit <toolkit@maxialatam.com>');
					//$expdes2 = strpos($descripcion, 'Maxia - Toolkit');
					if ($expdes !== false) {
						$arrdesc = explode('De: ',$descripcion);
						$descripcion = $arrdesc[0];
					}
					/*
					if ($expdes1 !== false) {
						$arrdesc = explode('De: Maxia - Toolkit <toolkit@maxialatam.com>',$descripcion);
					}elseif ($expdes2 !== false) {
						$arrdesc = explode('Maxia - Toolkit',$descripcion);
					}
					
					$expdes2 = strpos($arrdesc[0], 'De: ');
					if ($expdes2 !== false) {
						$arrdescrip = explode('De: ',$arrdesc[0]);
						$descripcion = $arrdescrip[0];
					}else{
						$descripcion = $arrdesc[0];
					}				
					*/
					if($descripcion != ''){
						$queryC = "INSERT INTO comentarios VALUES(null, '".$palabra."', $incidente, '".$descripcion."', 'Público', '".$sesusuario."', now(),'NO')";
					}				
					debugL($queryC);				
					if($mysqli->query($queryC)){
						bitacora($sesusuario, "Incidentes", "Se ha registrado un Comentario desde el correo para el Incidente #".$incidente, $incidente, $queryC);
					}
					//AMBIENTE NEW
					//$mysqli2->query($queryC);
				}else{
					$descripcion = limpiarUTF8($descripcion);							
					$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';
					preg_match ( $pattern, $solicitante, $solicitante );
					if($solicitante[0] != 'viva-noreply@microsoft.com' && $solicitante[0] != 'Viva-noreply@microsoft.com' && $solicitante[0] != 'no-reply@microsoft.com' && $solicitante[0] != 'mailer-daemon@googlemail.com' && $solicitante[0] != 'cortana@microsoft.com' && $solicitante[0] != 'support@byondit.zohodesk.com'){									
						 debugL('SOLICITANTE:'.$solicitante.'-SOLICITANTE[0]:'.$solicitante[0].'-TÍTULO:'.$titulo,'DEBUGLSOLICITANTE');
						$esLaboratorio = strpos($titulo, "Registro #");
						$esVobo 	   = strpos($titulo, "Vobo |");
						 
						if($esLaboratorio !== true && $esVobo !== true){	 
						$query = "INSERT INTO incidentes(id, titulo, descripcion, idambientes, idsubambientes, idestados, idcategorias, idsubcategorias, idprioridades, origen, creadopor, 
								solicitante, asignadoa, departamento, fechacreacion, horacreacion, notificar, idemail, fechareal, horareal, idempresas, idclientes, 
								idproyectos, iddepartamentos,tipo)
								VALUES(null, '$titulo', '$descripcion', '0', '0', '12', '0', 
								'0', '2', 'email', '".$solicitante[0]."', '".$solicitante[0]."', '', '','$fechacreacion', 
								'$horacreacion', '', '$idemail', '$fechacreacion', '$horacreacion', 1, 0, 0, 0, 'incidentes') ";
						}else{
							debugL($titulo.'-es Laboratorio - No Insert');
						}
						debug('VERIF.:'.$query);
					}else{
						debugL('1-es mycrosoft - no insert');
					}
					//debug($query);
					if($mysqli->query($query)){
						$id = $mysqli->insert_id;
						nuevoincidente($titulo, $descripcion, $id, $fechacreacion, $horacreacion, $solicitante[0]);					
						notificarCEstado($id,'','creado','','',$fechacreacion, $descripcion, $solicitante[0], $solicitante[0], $titulo);
						
						//CREAR REGISTRO EN ESTADOS INCIDENTES
						$queryE = " INSERT INTO incidentesestados VALUES(null, $id, 12, 12, $idusuario, now(), now(), 0) ";
						$mysqli->query($queryE);
						
						//AMBIENTE NEW
						//$mysqli2->query($queryE);
						
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
					// AMBIENTE NEW
					//$mysqli2->query($query);
				}			
			}		
		}else{
			debugL('PASO else');
			if (strpos($titulo, 'Undeliverable') !== false || strpos($titulo, 'Undelivered Mail Returned to Sender') !== false || strpos($titulo, 'Mail delivery failed') !== false || strpos($titulo, 'Datos del agua') !== false || strpos($titulo, 'No se puede entregar:') !== false || (strpos($titulo, '(comentario del usuario)') !== false ) || (strpos($titulo, '(comentario del usuario)') !== false || (strpos($titulo, 'Re: Aprobada Requisici') !== false )))
			{
				debugL('PASO 1: '.$titulo);
			   logDebug('Undeliverable', "ID: $idemail - ESTATUS: $status - TITULO: $titulo - SOLICITANTE: $solicitante - reply_to: $reply_to");
			}else{
				debugL('PASO 2: '.$titulo);
				if ($solicitante == 'no-replymesadeayuda@innovacion.gob.pa') {
					$solicitante = 'mesadeayuda@innovacion.gob.pa';
				}
				if ($solicitante ==  'mesadeayuda@innovacion.gob.pa') {
					$arrtitulo  = explode(':', $titulo);
					$arrnuminc  = $arrtitulo[0];
					$tinc = strpos($titulo, "INC ");
					$treq = strpos($titulo, "REQ ");
					if($tinc !== false){
						$arrnum 	= explode('INC ', $arrnuminc);
					}else{
						$arrnum 	= explode('REQ ', $arrnuminc);
					}
					$numinc 	= $arrnum[1];
					
					$queryMM  	= " SELECT id FROM incidentes WHERE titulo like '%$numinc%' ";
					$resultMM 	= $mysqli->query($queryMM);
					if($resultMM->num_rows > 0){
						$rowMM 		= $resultMM->fetch_assoc();
						$incidente	= $rowMM['id'];
					}else{
						$crearincidente = 1;
					}				
				}else{
					if ($poscoin1 !== false || $poscoin2 !== false) {
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
					}else{
						$crearincidente = 1;
					}
				}
				
				//USUARIO
				$queryU  	= " SELECT id, usuario FROM usuarios WHERE correo = '$solicitante' ";
				$resultU 	= $mysqli->query($queryU);
				if($resultU->num_rows > 0){
					$rowU 		= $resultU->fetch_assoc();
					$sesusuario	= $rowU['usuario'];
					$idusuario	= $rowU['id'];
				}else{
					$sesusuario = 'correo sin registrar';
					$idusuario	= 0;
				}
				
				if($crearincidente == 0){ //EL INCIDENTE ESTA CREADO	
					debugL('INSERTAR COMENTARIO 2');
					$expdes = strpos($descripcion, 'De: ');
					if ($expdes !== false) {
						$arrdesc = explode('De: ',$descripcion);
						$descripcion = $arrdesc[0];
					}
					if($descripcion != ''){
						$queryC = "INSERT INTO comentarios VALUES(null, '".$palabra."', $incidente, '".$descripcion."', 'Público', '".$sesusuario."', now(),'NO')";
					}
					debugL($queryC);				
					if($mysqli->query($queryC)){
						bitacora($sesusuario, "Incidentes", "Se ha registrado un Comentario desde el correo para el Incidente #".$incidente, $incidente, $queryC);
					}
					//AMBIENTE NEW
					//$mysqli2->query($queryC);
				}else{
					debugL('PASO INSERT');
					$descripcion = limpiarUTF8($descripcion);							 
					$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';
					preg_match ( $pattern, $solicitante, $solicitante );
					if($solicitante[0] != 'viva-noreply@microsoft.com' && $solicitante[0] != 'Viva-noreply@microsoft.com' && $solicitante[0] != 'no-reply@microsoft.com' && $solicitante[0] != 'mailer-daemon@googlemail.com' && $solicitante[0] != 'cortana@microsoft.com' && $solicitante[0] != 'support@byondit.zohodesk.com'){									
						
						$esLaboratorio = strpos($titulo, "Registro #");
						$esVobo 	   = strpos($titulo, "Vobo |");
						
						if($esLaboratorio !== true && $esVobo !== true){ 
							$query = "INSERT INTO incidentes(id, titulo, descripcion, idambientes, idsubambientes, idestados, idcategorias,
									idsubcategorias, idprioridades, origen, creadopor, solicitante, asignadoa, departamento, fechacreacion,
									horacreacion, notificar, idemail, fechareal, horareal,idempresas,idclientes,idproyectos,iddepartamentos,tipo)
									VALUES(null, '$titulo', '$descripcion', '0', '0', '12',
									'0', '0', '2', 'email', '".$solicitante[0]."', '".$solicitante[0]."', '', '',
									'$fechacreacion', '$horacreacion', '','$idemail','$fechacreacion', '$horacreacion',1,0,0,0,'incidentes') ";
							debugL('SOLICITANTE:'.$solicitante.'-SOLICITANTE[0]:'.$solicitante[0].'-TÍTULO:'.$titulo,'DEBUGLSOLICITANTE');
						}else{
							debugL($titulo.'-es Laboratorio - No Insert');
						}
						debug('VERIF.:'.$query);
					}else{
						debugL('2- es mycrosoft - No Insert');
					}
					debugL($query);
					if($mysqli->query($query)){
						$id = $mysqli->insert_id;
						nuevoincidente($titulo, $descripcion, $id, $fechacreacion, $horacreacion, $solicitante[0]);					
						notificarCEstado($id,'','creado','','',$fechacreacion, $descripcion, $solicitante[0], $solicitante[0], $titulo);
						
						//CREAR REGISTRO EN ESTADOS INCIDENTES
						$queryE = " INSERT INTO incidentesestados VALUES(null, $id, 12, 12, $idusuario, now(), now(), 0) ";
						$mysqli->query($queryE);
						
						//AMBIENTE NEW
						//$mysqli2->query($queryE);
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
					// AMBIENTE NEW
					//$mysqli2->query($query);
				}
			}	
		}
	}else{
		$hoy = date("Y-m-d H:i:s");
		debugL('CORREO NO DESEADO:'.$solicitante.', fecha: '.$hoy,'CORREO_NO_DESEADO');
	}
}

//ENVIAR CORREO AL SOLICITANTE DEL INCIDENTE Y SOPORTE
function nuevoincidente($titulo, $descripcion, $incidente, $fecha, $hora, $solicitante){
	global $mysqli, $mail;
	
	//SOLICITANTE
	if($solicitante !=''){
		if (filter_var($solicitante, FILTER_VALIDATE_EMAIL)) {
				$correo [] = $solicitante;
		}else{
			$result = $mysqli->query("SELECT correo FROM usuarios WHERE id = '$solicitante'");
			while ($row=$result->fetch_assoc()) {
				$correo [] = $row['correo'];
			}
		}
		//Asunto
		if($solicitante == 'mesadeayuda@innovacion.gob.pa' || $solicitante == 'soporteaig@innovacion.gob.pa' ){    
		    $asunto = $titulo." (Incidente Maxia #$incidente) ha sido Creado";
		    $enviar = 0;
		}else{
			$asunto = "Incidente #$incidente ha sido Creado";
			$enviar = 1;
		}
		
		//Cuerpo
		$fecha = implode('/',array_reverse(explode('-', $fecha)));
		$cuerpo = '';		
		$cuerpo .= "<div style='background-color: #FFFFFF; margin: 0 6%; padding: 1% 2%; color: #3e4954;'>
						<div style='text-align: right;'><b>Fecha:</b> ".$fecha."&nbsp;&nbsp;&nbsp;</div>";
		$cuerpo .= "	<br><b>".$titulo."</b>";
		$cuerpo .= "	<p style='width: 100%;'>Buen día,<br>Gracias por contactar al Centro de Soporte, su caso ha sido asignado a nuestros Ingenieros especializados quienes los contactarán brevemente para mas detalles sobre el caso.<p>";
		$cuerpo .= "	<br><br>
					</div>";
		//Correo
		//if ($enviar==1)
		enviarMensajeIncidente($asunto,$cuerpo,$correo);
	}		
}

//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
function notificarCEstado($incidente,$notificar,$accion,$estadoold,$estadonew,$fechacreacion, $descripcion, $solicitante, $creadopor, $titulo){
	global $mysqli;
		$asunto = "Incidente #$incidente ha sido Creado"; 
			
		//DATOS DEL CORREO
		$usuarioSes = $_SESSION['usuario'];
		$consultaUA = $mysqli->query("SELECT nombre FROM usuarios WHERE usuario = '$usuarioSes' LIMIT 1 ");
		while ($registroUA = $consultaUA->fetch_assoc()) {
			$usuarioAct = $registroUA['nombre'];
		} 
		
		//MENSAJE		
		$mensaje = "<div style='padding: 30px;font-family: arial,sans-serif;margin: 0 6%; background-color: #fff; padding: 1% 3%;'>
					<div style='font-size: 22px; color: #333; margin: 4% 0 4% 4%;'>".$usuarioAct." ha creado el correctivo #".$incidente."</div>";				
 
		$mensaje .= "	
						<p style='width:100%; margin-left: 1%;'><br><a href='http://toolkit.maxialatam.com/soporte/correctivo.php?id=$incidente' 	target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver correctivo</a></p>
						<br>
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 3% 4% 0 4%;'>Atributos</div>
						<table style='width: 100%; margin: 0 4% 0 4%;'>
							<tr>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Solicitante del servicio</div>".$usuarioAct."</td>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Sitio</div>".$sitio."</td>
							</tr>
							<tr>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Recibido en</div>".$fechacreacion."</td>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Departamento</div>".$departamento."</td>
							</tr>
							<tr>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Asignado a</div>".$nasignadoa."</td>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Prioridad</div>".$prioridad."</td>
							</tr>
							<tr>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Creado desde</div> Correo</td>
								<td style='padding: 15px 0;'></td>
							</tr>
						</table>
						
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Título</div>
						<div style='margin: 0 4%; color: #3e4954; text-align: justify; line-height: 150%;'>".$titulo."</div>
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 3% 4% 0 4%;'>Descripción</div>
						<div style='margin: 0 4%; color: #3e4954; text-align: justify; line-height: 150%;'>".$descripcion."</div>
						"; 
			
			$mensaje .= "</div>";

		//USUARIOS DE SOPORTE
		//$correo [] = 'ana.porras@maxialatam.com';
		$correo [] = 'isai.carvajal@maxialatam.com';
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';		
		$correo [] = 'maria.baena@maxialatam.com';		
		//$correo [] = 'yamarys.powell@maxialatam.com'; 
		
		//ASUNTO
		$innovacion = 'soporteaig@innovacion.gob.pa';
		if($solicitante == $innovacion || $creadopor == $innovacion){
			$asunto = $row['titulo'];
		}		
		enviarMensajeIncidente($asunto,$mensaje,$correo);
}

function enviarMensajeIncidente($asunto,$mensaje,$correos) {
	global $mysqli, $mail;
	$correo = array_unique($correos);
	$cuerpo = "";
	$cuerpo .= "<div style='background:#f6fbf8'>
					<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; margin: 0 6% 0 6%'>
						<img src='https://toolkit.maxialatam.com/soporte/images/encabezado-maxia-c.png' style='width: auto; float: left;'>
						<div style='width: 100%; text-align: center; margin-right: 27%; padding-top: 1%; color: #333; font-weight: bold;'>
							<div>Maxia SyM</div><div>Gestión de Soporte</div>
						</div>
					</div>";
	$cuerpo .= $mensaje;
	$cuerpo .= "	<div style='margin: 0 6% 0 6%; background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;clear: both;'>
						© ".date('Y')." Maxia Latam
					</div>
				</div>";	
	
	$mail->clearAddresses();
	foreach($correo as $destino){
	   $mail->addAddress($destino);
	}
	$mail->FromName = "Maxia Toolkit - SYM";
	$mail->isHTML(true); // Set email format to HTML
	$mail->Subject = $asunto;
	//$mail->MsgHTML($cuerpo);
	$mail->Body = $cuerpo;
	$mail->AltBody = "Maxia Toolkit - SYM: $asunto";
	if(!$mail->send()) {
		echo 'Mensaje no pudo ser enviado. ';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		//echo 'Ha sido enviado el correo Exitosamente';
		echo true;
	}  
}
	
function convert_to ( $source, $target_encoding ) {
// detect the character encoding of the incoming file
$encoding = mb_detect_encoding( $source, "auto" );
   
// escape all of the question marks so we can remove artifacts from
// the unicode conversion process
$target = str_replace( "?", "[question_mark]", $source );
   
// convert the string to the target encoding
$target = mb_convert_encoding( $target, $target_encoding, $encoding);
   
// remove any question marks that have been introduced because of illegal characters
$target = str_replace( "?", "", $target );
   
// replace the token string "[question_mark]" with the symbol "?"
$target = str_replace( "[question_mark]", "?", $target );

return $target;
}

?>