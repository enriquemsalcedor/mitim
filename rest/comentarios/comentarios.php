<?php
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
  header('content-type: application/json; charset=utf-8');
  header('Content-Type: application/JSON');
  include("../../conexion.php");
  require_once("../../controller/Encoding.php");
    use \ForceUTF8\Encoding;
  
    $method = $_SERVER['REQUEST_METHOD'];        
    
    if ($method =='GET') 
    {
     $action = $_REQUEST['action'];   
     switch($action)
     {
    	case "verComentario":
    	  		comentarios();
    	  		break;
    	case "addComentario":
    	  		addcomentarios();
    			break;
    	case "notificarComentario":
    		    notificarComentarios();
    			break;
    	default:
    			echo "{failure:true}";
    			break;
    	}
    }

function comentarios() {
	global $mysqli;
    $id=$_REQUEST['id'];
	$idusuario = $_REQUEST['idusuario'];

	$query  = " SELECT a.id, a.idmodulo, a.comentario, date_format(a.fecha,'%d/%m/%Y') as fecha, b.nombre, a.visibilidad
				FROM comentarios a
				LEFT JOIN usuarios b ON a.usuario = b.usuario
				WHERE modulo = 'Incidentes' AND idmodulo = $id ";
	$query .= permisos('combos', 'comentario', $idusuario);
	/*	
	if($nivel == 4){
		$query .= " AND a.visibilidad = 'Público' ";
	}*/
	$query  .= " ORDER BY a.id DESC ";
	$json="";
	$result = $mysqli->query($query);
	$json=array();
	if($result->num_rows > 0 ){
	   while($row = $result->fetch_assoc())
		 {
			$json[]=array(
          'nombre'=>$row['nombre'],
          'fecha'=>$row['fecha'],
          'visibilidad'=>$row['visibilidad'],
          'comentario'=>$row['comentario'])	;
		 }			
			echo json_encode($json);
	}else{
			echo json_encode([]);
	}
}

function addcomentarios() {
        global $mysqli;
		/*-------------------------------*/
		$usuario 	= $_REQUEST['usuario'];
		$incidente	= $_REQUEST['id'];
		$comentario = $_REQUEST['comentario'];
		$visibilidad = $_REQUEST['visibilidad'];
		/*-------------------------------*/
		//$fecha 		= date("Y-m-d H:i:s");
		$id_preventivo = 0;
		
		$sql = "SELECT tipo FROM incidentes WHERE id = ".$incidente."";
		$res = $mysqli->query($sql);
		if ($reg = $res->fetch_assoc()) {
			$tipo = $reg['tipo'];
		}
		if($tipo == 'incidentes'){
			$nombreInc = 'Correctivo';
		}else{
			$nombreInc = 'Preventivo';
		}

		$queryI = "INSERT INTO comentarios VALUES(null, 'Incidentes', $incidente, '$comentario', '$visibilidad', '$usuario', NOW(), 'NO')";
		if($mysqli->query($queryI)){
			$id = $mysqli->insert_id;
			//BITACORA
			bitacora($usuario, "Incidentes", "Se ha registrado un Comentario para el ".$nombreInc." #".$incidente, $incidente, $queryI);
			//ENVIAR NOTIFICACION
			//notificarComentarios($incidente,$comentario,$visibilidad);
			echo true;
		}else{
			echo false;
		}}


function notificarComentarios(){
		global $mysqli;
		
		$incidente = $_REQUEST['id'];
		$comentario = $_REQUEST['comentario'];
		$visibilidad = $_REQUEST['visibilidad'];
		$usuarios = $_REQUEST['usuario'];

		//CREADOR - SOLICITANTE - ASIGNADO
		$query  = "SELECT a.titulo, IFNULL(i.correo, a.creadopor) AS creadopor, 
					a.solicitante, a.asignadoa, a.notificar
					FROM incidentes a
					LEFT JOIN usuarios i ON a.creadopor = i.id OR a.creadopor = i.correo
					WHERE a.id = $incidente AND i.id != 0 ";
					
		$result = $mysqli->query($query);
		while ($row = $result->fetch_assoc()) {
			if($visibilidad != 'Privado'){
				$correo [] = $row['creadopor'];
				$correo [] = $row['solicitante'];
				$notificar = $row['notificar'];
				
				//Usuarios que quieren que se les notifique (Enviar Notificacion a)
				$notificar = json_decode($notificar);
				if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
					$correo [] = "$notificar";				
				}else{
					if(!empty($notificar)){
						foreach($notificar as $notif){
							$correo [] = $notif;
						}
					}
				}				
			}
			
			//USUARIO O GRUPO DE USUARIOS ASIGNADOS
			$asignadoaN	= '';		
			if($row['asignadoa'] != ''){
				$asignadoa  = $row['asignadoa'];
				if (filter_var($asignadoa, FILTER_VALIDATE_EMAIL)) {
					$correo [] = "$asignadoa";				
				}else{
					foreach([$asignadoa] as $asig){
						$correo [] = $asig;
					}
				}			
				$query2 = " SELECT nombre FROM usuarios WHERE ";
				if (filter_var($row['asignadoa'], FILTER_VALIDATE_EMAIL)) {
					$query2 .= "correo = '".$row['asignadoa']."'";
				}else{
					$query2 .= "correo IN (".$row['asignadoa'].") ";
				}
				$consulta = $mysqli->query($query2);
				while($rec = $consulta->fetch_assoc()){
					$asignadoaN .= $rec['nombre']." , ";
				}			
			}		
		}
		
		//DATOS DEL CORREO
		$consultaUA = $mysqli->query(" SELECT nombre FROM usuarios WHERE usuario = '$usuarios' LIMIT 1 ");
		while ($registroUA = $consultaUA->fetch_assoc()) {
			$usuarioAct = $registroUA['nombre'];
		}
		//DATOS
		$query  = " SELECT a.id, a.titulo, a.descripcion, c.nombre AS ambiente, a.resolucion, h.prioridad, a.idproyectos,
					a.origen, a.asignadoa, IFNULL(i.nombre, a.creadopor) AS creadopor, 
					IFNULL(j.nombre, a.solicitante) AS solicitante, a.creadopor AS ccreadopor, a.solicitante AS csolicitante,
					a.departamento, IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0),CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion, a.tipo
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN activos d ON a.idactivos = d.id
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.resueltopor = k.correo
					WHERE a.id = $incidente ";
					
		$result 		= $mysqli->query($query);
		$row 			= $result->fetch_assoc();
		$tipo 			= $row['tipo'];
		$fechacreacion 	= $row['fechacreacion'];
		$descripcion	= $row['descripcion'];
		$solicitante	= $row['solicitante'];
		$creadopor		= $row['creadopor'];
		$csolicitante	= $row['csolicitante'];
		$ccreadopor		= $row['ccreadopor'];
		$departamento	= $row['departamento'];
		$prioridad		= $row['prioridad'];
		$sitio 			= $row['ambiente'];
		$resolucion 	= $row['resolucion'];
		$idproyectos 	= $row['idproyectos'];
		$nasignadoa 	= $asignadoaN;
		$comentarios	= '';
		$bitacora		= '';
		
		if($tipo == 'incidentes'){
			$nombreMay = 'Correctivo'; 
			$nombreMin = 'correctivo';
		}else{
			$nombreMay = 'Preventivo';
			$nombreMin = 'preventivo';
		}
		//COMENTARIOS
		$consultaC = $mysqli->query("SELECT a.comentario, b.nombre AS nombreusuario, a.visibilidad, a.fecha FROM comentarios a INNER JOIN usuarios b ON b.usuario = a.usuario WHERE a.idmodulo = ".$incidente." AND a.visibilidad != 'Privado' AND a.comentario != '".$comentario."' ORDER BY a.id DESC LIMIT 3");
		if($consultaC->num_rows > 0){
			//COMENTARIOS
			$tablacomentarios = "<table style='border-collapse: collapse; margin: 0 4% 0 4%; width:-webkit-fill-available;'>
								<thead><tr><th align='left'>Fecha</th><th align='left'>Usuario</th><th align='left'>Comentario</th></tr></thead><tbody>";
			while ($registroC = $consultaC->fetch_assoc()) { 
				$numerocom++;
				($numerocom % 2) == 0 ? $backgcol = "#ffffff" : $backgcol = "#f6f6f6";
				$tablacomentarios .= "<tr style='background-color: ".$backgcol."'><td style='border-top: 1px solid #ddd; width: 15%; color: #3e4954; font-size: small; line-height: 150%;'>".$registroC['fecha']."</td><td style='border-top: 1px solid #ddd; width: 16%; color: #3e4954; font-size: small; line-height: 150%;'>".$registroC['nombreusuario']."</td><td style='border-top: 1px solid #ddd; color: #3e4954; font-size: small; line-height: 150%; text-align: justify;'>".$registroC['comentario']."</td></tr>";
			}
			$tablacomentarios .= "</tbody></table>";
		}else{
			$tablacomentarios ="No existen comentarios anteriores.";
		}
		//BITACORA
		$consultaB = $mysqli->query("SELECT accion FROM bitacora WHERE identificador = $incidente ");
		while ($registroB = $consultaB->fetch_assoc()) {
			$bitacora .= $registroB['accion'].'<br>';
		}
		$enviar = 1;
		$isist = '';
		if($csolicitante == 'mesadeayuda@innovacion.gob.pa' || $ccreadopor == 'mesadeayuda@innovacion.gob.pa' ){
			$titulo 	= $row['titulo'];
			$arrtitulo  = explode(':', $titulo);
			$arrnuminc  = $arrtitulo[0];
			$tinc = strpos($titulo, "INC ");
			$treq = strpos($titulo, "REQ ");
			if($tinc !== false){
				$arrnum = explode('INC ', $arrnuminc);
				$isist 	= " - INC ".$arrnum[1];
			}else{
				$arrnum = explode('REQ ', $arrnuminc);
				$isist 	= " - REQ ".$arrnum[1];
			}
			$numinc = $arrnum[1];
		    $asunto = $nombreMay." #$incidente - Comentario - INC $numinc";
			$enviar = 0;
    	} else {
			$numinc = '';
    	    $asunto = $nombreMay." #$incidente - Comentario ";
		}
		
		$mensaje  = "<div style='margin: 0 6%; background: #FFFFFF; padding: 30px;font-family: poppins, sans-serif;'>
					<div style='margin: 0 6% 0 6%; font-size: 22px;width:100%; color:#333; margin-left: 4%'>".$usuarioAct." ha comentado el ".$nombreMin." #".$incidente." - ".$isist."</div>			
					<div style='font-size: 14px; margin: 2% 4% 0 4%; text-align: justify; line-height: 150%;'><span style='color: #222; font-weight: 600;'>Comentario:</span> ".$comentario."</div>
					<p style='width:100%; margin-left: 1%;'><br><a href='http://toolkit.maxialatam.com/soporte/".$nombreMin.".php?id=$incidente&vercom=1' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Añadir un comentario</a></p>
					<br>
					<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Comentarios anteriores</div><br><div style='margin: 0 4%'>";
					if($tablacomentarios != ''){
							$mensaje .= $tablacomentarios;
						}
						$mensaje .="
					</div><br><br>
					<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Atributos</div>
					<table style='width: 100%; margin: 0 4% 0 4%;'>
						<tr>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Solicitante del servicio</div>".$solicitante."</td>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Ubicación</div>".$sitio."</td>
						</tr>
						<tr>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Recibido en</div>".$fechacreacion."</td>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Departamento</div>".$departamento."</td>
						</tr>
						<tr>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Asignado a</div>".$nasignadoa."</td>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Prioridad</div>".$prioridad."</td>
						</tr>
					</table>
					</div>";
		//USUARIOS DE SOPORTE
		$correo [] = 'ana.porras@maxialatam.com';
		$correo [] = 'isai.carvajal@maxialatam.com';
		enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
	}
//$correos,
function enviarMensajeIncidente($asunto,$mensaje,$correos,$adjuntos,$tipo) {
		global $mysqli, $mail;
		$correo = array_unique($correos);
		$cuerpo = "";
		$cuerpo .= "<div style='background:#f6fbf8'>
						<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; margin: 0 6% 0 6%'>";
		$cuerpo .= "		<img src='https://toolkit.maxialatam.com/soporte/images/encabezado-maxia-c.png' style='width: auto; float: left;'>";
		$cuerpo .= "		<div style='width: 100%; text-align: center; margin-right: 27%; padding-top: 1%; color: #333; font-weight: bold;'>
								<div>Maxia SyM</div><div>Gestión de Soporte</div>
							</div>";
		$cuerpo .= "	</div>";
		$cuerpo .= $mensaje;
		$cuerpo .= "	<div style='margin: 0 6% 0 6%; background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;clear: both;'>";
		$cuerpo .= "© ".date('Y')." Maxia Latam";
		$cuerpo .= "	</div>
					</div>";	
		
		$mail->clearAddresses();
		//echo "CORREO ES:".json_encode($correo);
		foreach ($correo as $clave => $destino){ 
			if($tipo == 'comentario'){
				$mail->addAddress($destino);
			}else{ 
				if( $destino != 'mesadeayuda@innovacion.gob.pa' ){
					$mail->addAddress($destino);
				}
			}					  
		}
		
		$mail->FromName = "Maxia Toolkit - Soporte";
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = $asunto;
		//$mail->MsgHTML($cuerpo);
		$mail->Body = $cuerpo;
		$mail->AltBody = "Maxia Toolkit - Soporte: $asunto";
		if($adjuntos != ''){
			foreach($adjuntos as $adjunto){
				//debug('uadjunto: '.$adjunto);
				$mail->AddAttachment($adjunto);
			}
		}
		if(!$mail->send()) {
			echo 'Mensaje no pudo ser enviado. ';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
			debug('Mensaje no pudo ser enviado');
		} else {
			debug('Ha sido enviado el correo Exitosamente');
			//echo 'Ha sido enviado el correo Exitosamente';
			foreach($adjuntos as $adjunto){
				if(is_file($adjunto))
				unlink($adjunto); //elimino el fichero
			}
			echo true;
		}
	}
	

?>