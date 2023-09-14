<?php
include_once("../conexion.php");
include_once("somosioticos_dialogflow.php");

credenciales('maxia', 'adminmt');
    
if (intent_recibido("crear_caso")) {
	$nombre = obtener_variables()['nombre'];
	$solicitante = obtener_variables()['email'];
	$telefono = obtener_variables()['telefono'];
	$descripcion = obtener_variables()['texto'];
	
	$fechacreacion = date("Ymd"); 
	$horacreacion = date("H:i:s"); 
	$titulo = "Incidente creado desde el ChatBot por $nombre. Nro. de contacto: $telefono";
	$descripcion = str_replace("'"," ",$descripcion);
	$descripcion = str_replace("´"," ",$descripcion);
	
	$query = "INSERT INTO incidentes(id, titulo, descripcion, unidadejecutora, estado, idcategoria, idsubcategoria, idprioridad, origen, creadopor, 
			solicitante, asignadoa, departamento, fechacreacion, horacreacion, notificar, idemail, fechareal, horareal, idempresas, idclientes, 
			idproyectos, iddepartamentos)
			VALUES(null, '$titulo', '$descripcion', '', '12', '0', '0', '2', 'chatbot', '$solicitante', '$solicitante', '', '',
			'$fechacreacion','$horacreacion', '', '0', '$fechacreacion', '$horacreacion', 1, 0, 0, 0) ";
			
	if($mysqli->query($query)){
		$id = $mysqli->insert_id;
		enviar_texto("Muchas gracias $nombre. Su caso fue creado con el Nro. $id");
		nuevoincidente($titulo, $descripcion, $id, $fechacreacion, $horacreacion, $solicitante);					
		$queryE = " INSERT INTO incidentesestados VALUES(null, $id, 12, 12, $idusuario, now(), now(), 0) ";
		$mysqli->query($queryE);
		$myPath = '../incidentes/';
		if (!file_exists($myPath))
			mkdir($myPath, 0777);
		$myPath = '../incidentes/'.$id.'/';
		$target_path2 = utf8_decode($myPath);
		if (!file_exists($target_path2))
			mkdir($target_path2, 0777); 
	}
}




function limpiarUTF8($string){
	$stringlm = str_replace('ï¿½','',$string);
	$stringf  = Encoding::fixUTF8($stringlm); 
	$valoract = html_entity_decode($stringf);
	$valornvo    = htmlspecialchars_decode($valoract);
	return $valornvo;
}


//ENVIAR CORREO AL SOLICITANTE DEL INCIDENTE Y SOPORTE
function nuevoincidente($titulo, $descripcion, $incidente, $fecha, $hora, $solicitante){
	global $mysqli, $mail;
	
	//SOLICITANTE
	if($solicitante !=''){
		if (filter_var($solicitante, FILTER_VALIDATE_EMAIL)) {
			$correo = $solicitante;
		}
		//Asunto
		$asunto = "Incidente #$incidente ha sido Creado";
		//Cuerpo
		$fecha = implode('/',array_reverse(explode('-', $fecha)));
		$cuerpo = '';		
		$cuerpo .= "<div style='width: 100%; text-align: right;'><b>Fecha:</b> ".$fecha."&nbsp;&nbsp;&nbsp;</div>";
		$cuerpo .= "<br><b>".$titulo."</b>";
		$cuerpo .= "<p style='width: 100%;'>Saludos,<br>Gracias por contactar al Centro de Soporte, su caso nro. $incidente ha sido asignado a nuestros Ingenieros especializados quienes los contactarán brevemente para mas detalles sobre el caso.<p>";
		$cuerpo .= "<br><br>";

		enviarMensajeIncidente($asunto,$cuerpo,$correo);
	}		
}

function enviarMensajeIncidente($asunto,$mensaje,$correo) {
	global $mysqli, $mail;
	$cuerpo = "";
	$cuerpo .= "<div style='background:#293f76; padding: 5px 0 5px 10px; display: flex; '>";
	$cuerpo .= "<img src='https://toolkit.maxialatam.com/soporte/images/encabezado-maxia.png' style='width: initial;height: 60px;float: left; position: absolute !important;'>";
	$cuerpo .= "<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; color:#ffffff;'>Maxia Toolkit<br>";
	$cuerpo .= "Gestión de Soporte<br>";
	$cuerpo .= "</div>";
	$cuerpo .= $mensaje;
	$cuerpo .= "<div style='background:#0098c4;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px; color:#ffffff;'>";
	$cuerpo .= "© ".date('Y')." Maxia Latam";
	$cuerpo .= "</div>";	
	
	$mail->clearAddresses();
	$mail->addAddress($correo);
	$mail->FromName = "Maxia Toolkit - Soporte";
	$mail->isHTML(true); // Set email format to HTML
	$mail->Subject = $asunto;
	//$mail->MsgHTML($cuerpo);
	$mail->Body = $cuerpo;
	$mail->AltBody = "Maxia Toolkit - Soporte: $asunto";
	if(!$mail->send()) {
		echo 'Mensaje no pudo ser enviado. ';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		//echo 'Ha sido enviado el correo Exitosamente';
		echo true;
	} 
}
	
function convert_to ( $source, $target_encoding ) {
    $encoding = mb_detect_encoding( $source, "auto" );
    $target = str_replace( "?", "[question_mark]", $source );
    $target = mb_convert_encoding( $target, $target_encoding, $encoding);
    $target = str_replace( "?", "", $target );
    $target = str_replace( "[question_mark]", "?", $target );
    return $target;
}

?>