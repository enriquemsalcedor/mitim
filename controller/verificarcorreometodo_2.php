<?php
//header('Content-Type: text/html; charset=utf-8');
error_reporting(1);
require_once("../conexion.php");
require_once("class.imap.php"); 
//ini_set('default_charset', 'utf-8'); 

$mailbox = 'outlook.office365.com';
$port = '993'; 

$imap = new Imap(); 
header('Content-Type: text/html; charset=utf-8');
$hostname = '{outlook.office365.com:993/imap/ssl}INBOX';
$username = 'desarrollomaxia@outlook.com';
$password = 'maxia123';
$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to server: ' . imap_last_error());
$emails = imap_search($inbox,'UNSEEN'); 
   echo "------------------------COMIENZA SCRIPT----------------------<BR>";
if($emails) {
	echo "------------------------SI EXISTEN EMAILS-------------------<BR>";
    $output = '';
    rsort($emails); 
	foreach($emails as $email_number) { 
		echo "NRO EMAIL:".$email_number."<BR>";
		$structure = imap_fetchstructure($inbox, $email_number);
		var_dump(structure);
		$attachments = array(); 
		$overview = imap_fetch_overview($inbox,$email_number,0);
		$subject  = $overview[0]->subject;
		if($part->encoding == 4) { 
			$subject = imap_utf8($subject);
		} else if($part->encoding == 3) {
			$subject = imap_utf8($subject); 
		}else if($part->encoding == 1) {
			$subject = imap_utf8($subject); 
		} else { 
			//$subject = imap_qprint($subject); 
			$subject = imap_utf8($subject); 
		}	
		$message = getmsg($inbox,$email_number);  
		$esreenviado = strpos($subject, 'Fwd');
		echo "REENVIADO ES:".$esreenviado;
		echo "<br> SUBJECT ES:".$subject;
		
		 /* if any attachments found... */
           if(isset($structure->parts) && count($structure->parts)) 
           {  
					
               for($i = 1; $i < count($structure->parts); $i++) 
               {  	
                   if($structure->parts[$i]->ifdparameters) 
                   {   
                       foreach($structure->parts[$i]->dparameters as $object) 
                       {
                           if(strtolower($object->attribute) == 'filename') 
                           { 
                               $attachments[$i]['is_attachment'] = true;
                               //$attachments[$i]['filename'] = $object->value; //ORIGINAL
                               //echo "base64_decode DECODE filename:".base64_decode($attachments[$i]['filename']);
							   $attachments[$i]['filename'] = validarNombreArchivo(imap_utf8($object->value)); 
							   
							   //$attach_filename = validarNombre($attachments[$i]['filename']);
							  // echo "ATTACHMENT FILENAME ES:".$attach_filename;
                           }
                       }
                   }

                   if($structure->parts[$i]->ifparameters) 
                   { 
                       foreach($structure->parts[$i]->parameters as $object) 
                       {
                           if(strtolower($object->attribute) == 'name') 
                           { 
                               $attachments[$i]['is_attachment'] = true;
                               //$attachments[$i]['name'] = $object->value; //ORIGINAL
                               //echo "<br>base64_decode DECODE name:".base64_decode($attachments[$i]['name']);
							   $attachments[$i]['name'] = validarNombreArchivo(imap_utf8($object->value)); 
							   
							   //$attach_name = validarNombre($attachments[$i]['name']);
							//   echo "ATTACHMENT NAME ES:".$attach_name;
                           }
                       }
                   }

                   if($attachments[$i]['is_attachment']) 
                   {  
                       $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);

                       /* 3 = BASE64 encoding */
                       if($structure->parts[$i]->encoding == 3) 
                       { 	 
                           $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']); 
						   echo "PASÓ 1<BR>";
                       }
                       /* 4 = QUOTED-PRINTABLE encoding */
                       elseif($structure->parts[$i]->encoding == 4) 
                       {   
                           $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
						   echo "PASÓ 1<BR>";
                       }  
                   } 
               }
           }   
		   var_dump($attachments);
		//Id Email
		$idemail = $overview[0]->uid;
		//Fecha Creación
		$fechacreacion = date("Ymd");  
		//Hora Creación
		$horacreacion = date("H:i:s"); 
		$from = utf8_decode(imap_utf8($overview[0]->from));
		//Solicitante
		$solicitante = substr(($from = substr($from, (strpos($from, '<')+1))), 0, strrpos($from, '>'));
        //Título
		$titulo = $subject;
		//Mensaje 
		$css = "P {margin-top:0;margin-bottom:0;}"; 
		$message = str_replace($css," ",$message); 
		$descripcion = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $message);  
		$descripcion = str_replace("v:* {behavior:url(#default#VML);} o:* {behavior:url(#default#VML);} w:* {behavior:url(#default#VML);} .shape {behavior:url(#default#VML);}           ","",$descripcion);
		$descripcion = str_replace("</div>", "\n", $descripcion);
		$descripcion = str_replace("<br>", "\n", $descripcion); 
		
		$descripcion = html_entity_decode(strip_tags($descripcion));   
		$titulo = str_replace("'"," ",$titulo);
		$titulo = str_replace("´"," ",$titulo);
		$descripcion = str_replace("'"," ",$descripcion);
		$descripcion = str_replace("´"," ",$descripcion);
		
		if ($titulo!=""){
			$titulo = trim($titulo);
		}else{
			$titulo = "Sin Asunto";
		}
		$poscoin1 = strpos($titulo, "Incidente #");
		$poscoin2 = strpos($titulo, "Preventivo #");
		$poscoin3 = strpos($titulo, "[Ticket#");
		$poscoin4 = strpos($titulo, "[Mesa de Ayuda]");
		$poscoin5 = strpos($titulo, "[Service Desk]");
		$poscoin6 = strpos($titulo, "[Mesade Ayuda}");
		$poscoin7 = strpos($titulo, "Correctivo #");
		$poscoin8 = strpos($titulo, "Mail System Error");
		$crearincidente = 0;
 
		if ($poscoin1 !== false || $poscoin2 !== false || $poscoin3 !== false || $poscoin4 !== false || $poscoin5 !== false || $poscoin6 !== false || $poscoin7 !== false || $poscoin8 !== false) {
			
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
					 				
					if($mysqli->query($queryC)){
						bitacora($sesusuario, "Incidentes", "Se ha registrado un Comentario desde el correo para el Incidente #".$incidente, $incidente, $queryC);
					}
				}else{
				 
					$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';
					preg_match ( $pattern, $solicitante, $solicitante );
					if($solicitante[0] != 'no-reply@microsoft.com' && $solicitante[0] != 'mailer-daemon@googlemail.com' && $solicitante[0] != 'cortana@microsoft.com'){
						 
						$esLaboratorio = strpos($titulo, "Registro #");
						$esVobo 	   = strpos($titulo, "Vobo |"); 
						
						if($esLaboratorio !== true && $esVobo !== true){
							$query = "INSERT INTO incidentes(id, titulo, descripcion, idambientes, idsubambientes, idestados, idcategorias,
								idsubcategorias, idprioridades, origen, creadopor, solicitante, asignadoa, departamento, fechacreacion,
								horacreacion, notificar, idemail, fechareal, horareal,idempresas,idclientes,idproyectos,iddepartamentos,tipo)
								VALUES(null, '$titulo', '$descripcion', '0', '0', '12',
								'0', '0', '2', 'email', '".$solicitante[0]."', '".$solicitante[0]."', '', '',
								'$fechacreacion', '$horacreacion', '','$idemail','$fechacreacion', '$horacreacion',1,0,0,0,'incidentes') ";
						} 
					 } 
					 
					if($mysqli->query($query)){
						$id = $mysqli->insert_id;
						nuevoincidente($titulo, $descripcion, $id, $fechacreacion, $horacreacion, $solicitante[0]);
						notificarCEstado($id,'','creado','','',$fechacreacion, $descripcion, $solicitante[0], $solicitante[0]);
						
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
						
						$totaladjuntos = count($attachments);
						$contar = 0;
						foreach($attachments as $attachment)
					   {	
							$contar++;
						   if($attachment['is_attachment'] == 1)
						   {
							   $filename = $attachment['name']; 
							   
							   if(empty($filename)) $filename = $attachment['filename'];

							   if(empty($filename)) $filename = time() . ".dat";
							   $folder = "attachment";
							   if(!is_dir($folder))
							   {
									mkdir($folder);
							   }
							   if($esreenviado === false){
								  $fp = fopen("../incidentes/".$id."/".$filename, "w+");
								   fwrite($fp, $attachment['attachment']);
								   fclose($fp); 
								   
								   //Verificar si es imagen de firma
								   if($contar == $totaladjuntos){
										$src = "../incidentes/".$id."/".$filename;
										if (file_exists($src)) { 
											$imagen = getimagesize("../incidentes/".$id."/".$filename); //Sacamos la información.
											if($imagen != ""){
												$ancho = $imagen[0]; //Ancho.
												$alto = $imagen[1]; //Alto. 
												if(
													($ancho >= 739 && $ancho <= 741 && ($alto == 100 || $alto == 101)) ||
													($ancho == 240 && ($alto == 32 || $ancho == 69)) ||
													($ancho == 536 && $alto == 73) || 
													($ancho == 589 && $alto == 181) 
												){
													debugL(date("Y-m-d H:i:s") . " Es imagen de firma ../incidentes/".$id."/".$filename,"ImagenesEliminadasFirmas");
													unlink("../incidentes/".$id."/".$filename);
												}
											}									
										} 
								   }
							   } 
						   }
					   }
						bitacora($sesusuario, "Incidentes Correo", 'El Incidente #'.$id.' ha sido Creado exitosamente', $id, $query);				
					}
				}			
			} 		
		}else{
			 
			if (strpos($titulo, 'Undeliverable') !== false || strpos($titulo, 'Undelivered Mail Returned to Sender') !== false || strpos($titulo, 'Mail delivery failed') !== false || strpos($titulo, 'Datos del agua') !== false || strpos($titulo, 'No se puede entregar:') !== false || (strpos($titulo, '(comentario del usuario)') !== false ) || (strpos($titulo, '(comentario del usuario)') !== false || (strpos($titulo, 'Re: Aprobada Requisici') !== false )))
			{
				 
			   logDebug('Undeliverable', "ID: $idemail - ESTATUS: $status - TITULO: $titulo - SOLICITANTE: $solicitante - reply_to: $reply_to");
			}else{
				 
				if ($solicitante == 'no-replymesadeayuda@innovacion.gob.pa') {
					$solicitante = 'mesadeayuda@innovacion.gob.pa';
				}
				if ($solicitante ==  'mesadeayuda@innovacion.gob.pa') {
					$arrtitulo  = explode(':', $titulo);
					$arrnuminc  = $arrtitulo[0];
					$tinc = strpos($titulo, "INC ");
					$treq = strpos($titulo, "REQ ");
					if($tinc !== false){
						$arrnum = explode('INC ', $arrnuminc);
					}else{
						$arrnum = explode('REQ ', $arrnuminc);
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
					 
					$expdes = strpos($descripcion, 'De: ');
					if ($expdes !== false) {
						$arrdesc = explode('De: ',$descripcion);
						$descripcion = $arrdesc[0];
					}
					if($descripcion != ''){
						$queryC = "INSERT INTO comentarios VALUES(null, '".$palabra."', $incidente, '".$descripcion."', 'Público', '".$sesusuario."', now(),'NO')";
					}
					 				
					if($mysqli->query($queryC)){
						bitacora($sesusuario, "Incidentes", "Se ha registrado un Comentario desde el correo para el Incidente #".$incidente, $incidente, $queryC);
					}
				}else{
					 
					$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';
					preg_match ( $pattern, $solicitante, $solicitante );
					if($solicitante[0] != 'no-reply@microsoft.com' && $solicitante[0] != 'mailer-daemon@googlemail.com' && $solicitante[0] != 'cortana@microsoft.com'){
						
						$esLaboratorio = strpos($titulo, "Registro #");
						$esVobo 	   = strpos($titulo, "Vobo |");
						
						if($esLaboratorio !== true && $esVobo !== true){
							$query = "INSERT INTO incidentes(id, titulo, descripcion, idambientes, idsubambientes, idestados, idcategorias,
								idsubcategorias, idprioridades, origen, creadopor, solicitante, asignadoa, departamento, fechacreacion,
								horacreacion, notificar, idemail, fechareal, horareal,idempresas,idclientes,idproyectos,iddepartamentos,tipo)
								VALUES(null, '$titulo', '$descripcion', '0', '0', '12',
								'0', '0', '2', 'email', '".$solicitante[0]."', '".$solicitante[0]."', '', '',
								'$fechacreacion', '$horacreacion', '','$idemail','$fechacreacion', '$horacreacion',1,0,0,0,'incidentes') ";
						} 
						 
					 } 
					 
					if($mysqli->query($query)){
						$id = $mysqli->insert_id;
						nuevoincidente($titulo, $descripcion, $id, $fechacreacion, $horacreacion, $solicitante[0]);
						notificarCEstado($id,'','creado','','',$fechacreacion, $descripcion, $solicitante[0], $solicitante[0]);
						
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
						
						$totaladjuntos = count($attachments);
						$contar = 0;
						
						foreach($attachments as $attachment)
					   {  
							$contar++;
						   if($attachment['is_attachment'] == 1)
						   { 
							   $filename = $attachment['name'];
							   
							   if(empty($filename)) $filename = $attachment['filename']; 
							   if(empty($filename)) $filename = time() . ".dat";
							   $folder = "attachment";
							   if(!is_dir($folder))
							   {
									mkdir($folder);
							   }
							    if($esreenviado === false){
									$fp = fopen("../incidentes/".$id."/".$filename, "w+");
								    fwrite($fp, $attachment['attachment']);
								    fclose($fp);
								    //echo "EL NOMBRE DEL ARCHIVO ES:".$filename;
								    //Verificar si es imagen de firma
								    if($contar == $totaladjuntos){
										$src = "../incidentes/".$id."/".$filename;
										if (file_exists($src)) { 
											$imagen = getimagesize("../incidentes/".$id."/".$filename); //Sacamos la información.
											if($imagen != ""){
												$ancho = $imagen[0]; //Ancho.
												$alto = $imagen[1]; //Alto. 
												if(
													($ancho >= 739 && $ancho <= 741 && ($alto == 100 || $alto == 101)) ||
													($ancho == 240 && ($alto == 32 || $ancho == 69)) ||
													($ancho == 536 && $alto == 73) || 
													($ancho == 589 && $alto == 181) 
												){
													debugL(date("Y-m-d H:i:s") . " Es imagen de firma ../incidentes/".$id."/".$filename,"ImagenesEliminadasFirmas");
													unlink("../incidentes/".$id."/".$filename);
												}
											}									
										} 
								    }
								}  
						   }
						   
					   }
						bitacora($sesusuario, "Incidentes Correo", 'El Incidente #'.$id.' ha sido Creado exitosamente', $id, $query);				
					}
				}
			}	
		}
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
		$cuerpo .= "<div style='width: 100%; text-align: right;'><b>Fecha:</b> ".$fecha."&nbsp;&nbsp;&nbsp;</div>";
		$cuerpo .= "<br><b>".$titulo."</b>";
		$cuerpo .= "<p style='width: 100%;'>Buen día,<br>Gracias por contactar al Centro de Soporte, su caso ha sido asignado a nuestros Ingenieros especializados quienes los contactarán brevemente para mas detalles sobre el caso.<p>";
		$cuerpo .= "<br><br>";
		//Correo
		//if ($enviar==1)
		enviarMensajeIncidente($asunto,$cuerpo,$correo);
	}		
}

//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
function notificarCEstado($incidente,$notificar,$accion,$estadoold,$estadonew,$fechacreacion, $descripcion, $solicitante, $creadopor){
	global $mysqli;
		$asunto = "Incidente #$incidente ha sido Creado"; 
			
		//DATOS DEL CORREO
		$usuarioSes = $_SESSION['usuario'];
		$consultaUA = $mysqli->query("SELECT nombre FROM usuarios WHERE usuario = '$usuarioSes' LIMIT 1 ");
		while ($registroUA = $consultaUA->fetch_assoc()) {
			$usuarioAct = $registroUA['nombre'];
		} 
		
		//MENSAJE		
		$mensaje = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha creado el incidente #".$incidente."</b></p>";				
 
		$mensaje .= "<p style='width:100%;'>
						<a href='http://toolkit.maxialatam.com/soporte/incidentes.php?id=".$incidente."' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Incidente</a></p>
						<br><br>
						<p style='font-size: 18px;width:100%;'>".$usuarioAct." ha creado este incidente el ".$fechacreacion."</p>
						<br>
						<p style='width:100%;'>".$descripcion."</p>
						<br>
						<p style='width:100%;'>Creado desde el Correo</p>
						<br>
						<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin: auto;padding: 10px;width:100%;'>Atributos</p>
						<table style='width: 50%;'>
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
						<table>
						"; 
			
			$mensaje .= "</div>";

		//USUARIOS DE SOPORTE
		//$correo [] = 'ana.porras@maxialatam.com';
		$correo [] = 'isai.carvajal@maxialatam.com';
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		
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
	$cuerpo .= "<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; '>";
	$cuerpo .= "<img src='http://web.maxialatam.com:8010/repositorio-tema/assets/img/maxia.jpg' style='width: initial;height: 60px;float: left; position: absolute !important;'>";
	$cuerpo .= "<p style='margin:auto; font-weight:bold; width: 100%; text-align: center;'>Maxia Toolkit<br>";
	$cuerpo .= "Gestión de Soporte<br>";
	$cuerpo .= "</div>";
	$cuerpo .= $mensaje;
	$cuerpo .= "<div style='background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;'>";
	$cuerpo .= "© ".date('Y')." Maxia Latam";
	$cuerpo .= "</div>";	
	
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
	/* if(!$mail->send()) {
		echo 'Mensaje no pudo ser enviado. ';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		//echo 'Ha sido enviado el correo Exitosamente';
		echo true;
	} */ 
}  

function getmsg($mbox,$mid) {
	// input $mbox = IMAP stream, $mid = message id
	// output all the following:
	global $charset,$htmlmsg,$plainmsg,$attachments;
	$htmlmsg = $plainmsg = $charset = '';
	$attachments = array();

	// HEADER
	$h = imap_header($mbox,$mid);
	// add code here to get date, from, to, cc, subject...

	// BODY
	$s = imap_fetchstructure($mbox,$mid);
	 
	if (!$s->parts){  // simple 
		getpart($mbox,$mid,$s,0);  // pass 0 as part-number
	}else {  // multipart: cycle through each part
	 
		foreach ($s->parts as $partno0=>$p){ 
			$contenido = getpart($mbox,$mid,$p,$partno0+1);
		}
		//echo "<br>EL CONTENIDO HTML ES:".$contenido."<BR>";
		return $contenido;
	}
}
		
function getpart($mbox,$mid,$p,$partno) {
    // $partno = '1', '2', '2.1', '2.1.3', etc for multipart, 0 if simple
    global $htmlmsg,$plainmsg,$charset,$attachments;

    // DECODE DATA
    $data = ($partno)?
        imap_fetchbody($mbox,$mid,$partno):  // multipart
        imap_body($mbox,$mid);  // simple
    // Any part may be encoded, even plain text messages, so check everything.
    if ($p->encoding==4)
        $data = quoted_printable_decode($data);
    elseif ($p->encoding==3)
        $data = base64_decode($data);
    // PARAMETERS
    // get all parameters, like charset, filenames of attachments, etc.
    $params = array();
    if ($p->parameters){
		foreach ($p->parameters as $x){
			$params[strtolower($x->attribute)] = $x->value; 
		} 
	}
        
    if ($p->dparameters){
		foreach ($p->dparameters as $x){
			$params[strtolower($x->attribute)] = $x->value; 
		} 
	} 

    // ATTACHMENT
    // Any part with a filename is an attachment,
    // so an attached text file (type 0) is not mistaken as the message.
    if ($params['filename'] || $params['name']) {
		//echo "<BR> FILENAME ".$params['filename']." - NAME: ".$params['name']."<BR>";
        // filename may be given as 'Filename' or 'Name' or both
        //$filename = ($params['filename'])? $params['filename'] : $params['name'];
        // filename may be encoded, so see imap_mime_header_decode()
      //  $attachments[$filename] = $data;  // this is a problem if two files have same name
    }

    // TEXT
    if ($p->type==0 && $data) {
		//echo "<BR> DATA DECODIFICADA ES:".$data." <BR>";
        // Messages may be split in different parts because of inline attachments,
        // so append parts together with blank row.
        if (strtolower($p->subtype)=='plain'){
			$plainmsg .= trim($data) ."\n\n";
			//echo "<BR>PLAINMSG:".$plainmsg."<BR>";
		}else{
			$htmlmsg .= $data ."<br><br>";
			//echo "<BR>HTMLMSG:".$htmlmsg."<BR>";
		}            
        $charset = $params['charset'];  // assume all parts are same charset
    }

    // EMBEDDED MESSAGE
    // Many bounce notifications embed the original message as type 2,
    // but AOL uses type 1 (multipart), which is not handled here.
    // There are no PHP functions to parse embedded messages,
    // so this just appends the raw source to the main message.
    elseif ($p->type==2 && $data) {
        $plainmsg .= $data."\n\n";
    }

    // SUBPART RECURSION
    if ($p->parts) {
        foreach ($p->parts as $partno0=>$p2){
			//echo "<BR>PASÓ 4<BR>";
			getpart($mbox,$mid,$p2,$partno.'.'.($partno0+1));  // 1.2, 1.2.1, etc.
		}
            
    }   
	$contenidomensaje = $htmlmsg; 
	 
	return $contenidomensaje; 
}

function validarNombreArchivo($archivo){
	
	$formatos_permitidos =  array('doc','docx','jpeg','jpg','png','xls','xlsx','pdf'); 
	$extension = pathinfo($archivo, PATHINFO_EXTENSION);
	$name = pathinfo($archivo, PATHINFO_FILENAME);
	
	if(!in_array($extension, $formatos_permitidos) ) {
		$posBase64 = strpos($archivo, '=?UTF-8?B?'); 
		$posQprint = strpos($archivo, '=?UTF-8?Q?'); 
	   
		if($posBase64 !== false || $posQprint !== false){
			if($posBase64 !== false){
				$sinutf8 = str_replace('=?UTF-8?B?','',$archivo);
				$cadena = base64_decode($sinutf8); 
				$cadena = str_replace('?','',$cadena);
				$extension = pathinfo($cadena, PATHINFO_EXTENSION);
				$name = pathinfo($cadena, PATHINFO_FILENAME);
				$rest = substr($name, -1);
				if($rest == '.') $name = trim($name, '.');
				return $name.".".$extension;
			}else{
				debugL('no se subió el siguiente archivo:'.$archivo,"debugL_errorEnVerificarCorreo");   
			}
			if($posQprint !== false){
				$sinutf8 = str_replace('=?UTF-8?Q?','',$archivo);
				$cadena = imap_qprint($sinutf8); 
				$cadena = str_replace('?','',$cadena);
				$extension = pathinfo($cadena, PATHINFO_EXTENSION);
				$name = pathinfo($cadena, PATHINFO_FILENAME);
				$rest = substr($name, -1);
				if($rest == '.') $name = trim($name, '.');
				return $name.".".$extension;
			}else{
			   debugL('no se subió el siguiente archivo:'.$archivo,"debugL_errorEnVerificarCorreo");
			}
		}else{
			 debugL('No es Base64 ni Qprint:'.$archivo,"debugL_errorEnVerificarCorreo");
		}	 
	}else{ 
		$rest = substr($name, -1);
		if($rest == '.') $name = trim($name, '.');
		return $name.".".$extension;
	}
}

?>