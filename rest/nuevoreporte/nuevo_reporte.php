 <?php
	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	header('content-type: application/json; charset=utf-8');
	header('Content-Type: application/JSON');
	//capurando el tipo de metodo
	include("../../conexion.php");
	
	$method = $_SERVER['REQUEST_METHOD'];        
  
	if ($method =='GET') {
    //con este request seleciono en tipo de operacion a realizar 
    $action = $_REQUEST['action'];   
		switch($action){
			case "IncidenteViaMail":
				  IncidenteViaMail();
				  break;
			case "enviarNotificacion":
				  enviarNotificacion();
				  break;
			default:
				  echo "{failure:true}";
				  break;
	    }
	}elseif ($method =='POST') {
        $action = '';
        
    	if (isset($_REQUEST['action'])) {
    		$action = $_REQUEST['action'];
    	}
    	    switch($action)
        { 
    		case "updateCorrectAddActivo":
				  updateIncidenteAddActivo();
				  break;
		   default:
                  echo "{failure:true}";
                  break;
        } 
	}
	/*else{
		$response['status'] = "fail-Incident";
		$response['cod'] = '000';
		echo json_encode($response);
	}*/

	function IncidenteViaMail(){
		global $mysqli;		
        $usuario 	= $_REQUEST['usuario'];
		//$idusuario 	= $_REQUEST['idusuario'];
		$nivel	 	= $_REQUEST['nivel'];
		$query   = " SELECT correo FROM usuarios WHERE usuario = '$usuario' ";
		$result  = $mysqli->query($query);
		if($row = $result->fetch_assoc()){
			$remitente = $row['correo'];
		}
		
		$titulo 			= (!empty($_REQUEST['titulo']) ? $_REQUEST['titulo'] : '');
		$descripcion 		= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');
		//$contacto           = (!empty($_REQUEST['contacto']) ? $_REQUEST['contacto'] : '');
		$contacto           = (!empty($_REQUEST['contacto'])? $_REQUEST['contacto']:"");
		$subcategoria 		= 0;
		$idambientes     	= 0;
		$idsubambientes     = 0;
		$idempresas 		= 1;
		$idclientes 		= 0;
		$idproyectos 		= 0;
		$iddepartamentos	= 0;
		$estado 			= 12;
		$categoria 			= 0;
		$subcategoria 		= 0; 
		$origen 			= 'APP';
		$solicitante 		= $remitente;
		$creadopor			= $remitente;  
		$fechacreacion	    = date('Y-m-d');
		$horacreacion 	    = date('H:i:s');
		$tipo= "incidentes";
		
		//CLIENTES 
		if($idclientes == 0 && $nivel == 4){
			$queryCU  	 = " SELECT idclientes FROM usuarios WHERE usuario = '".$usuario."' ";
			$resultCU 	 = $mysqli->query($queryCU);
			$rowCU 	 	 = $resultCU->fetch_assoc();
			$string = $rowCU['idclientes'];
			$findme   = ',';
			$pos = strpos($string, $findme);
			if ($pos === false) { //NO SE ENCONTRO
				$idclientes = $rowCU['idclientes'];
			}
		}
		
		if($idproyectos == 0 && $nivel == 4){
			$queryCU  	 = " SELECT idproyectos FROM usuarios WHERE usuario = '".$usuario."' ";
			$resultCU 	 = $mysqli->query($queryCU);
			$rowCU 	 	 = $resultCU->fetch_assoc();
			$string = $rowCU['idproyectos'];
			$findme   = ',';
			$pos = strpos($string, $findme);
			if ($pos === false) { //NO SE ENCONTRO
				$idproyectos = $rowCU['idproyectos'];
			}
		}
		
		$query = "  INSERT INTO incidentes (id,titulo, descripcion,idestados, idcategorias,idambientes,idsubambientes,tipo, idsubcategorias, 
					origen, creadopor, solicitante, fechacreacion, horacreacion, idempresas, idclientes, idproyectos, iddepartamentos,
					contacto)
					VALUES (null,'$titulo', '$descripcion', '$estado','$categoria','$idambientes','$idsubambientes','$tipo', 
					'$subcategoria','$origen', '$creadopor', '$solicitante', '$fechacreacion','$horacreacion','$idempresas', 
					'$idclientes', '$idproyectos', '$iddepartamentos','$contacto') ";		 
		debugL($query);
		
		if($mysqli->query($query)){
			$id = $mysqli->insert_id;
      
            if($id != ''){
				//CREAR REGISTRO EN ESTADOS INCIDENTES
				$queryE = " INSERT INTO incidentesestados VALUES(null, $id, 12, '$estado', $idusuario, now(), now(), 0) ";
				$mysqli->query($queryE);
				//debug('usuario1: '. $usuario.', titulo: '. $titulo.', descripcion: '. $descripcion.', id '. $id.', fechacreacion: '.$fechacreacion.', horacreacion '. $horacreacion.', solicitante: '.$solicitante);
				
				//CREAR CARPETA DE ID INCIDENTES Y COMENTARIOS
				$myPath = '../../incidentes/';
				if (!file_exists($myPath))
					mkdir($myPath, 0777);
				$myPath = '../../incidentes/'.$id.'/';
				$target_path2 = utf8_decode($myPath);
				if (!file_exists($target_path2))
					mkdir($target_path2, 0777);
				
				//MOVER DEL TEMP A INCIDENTES				
				$num 	= $_REQUEST['user_id'];
				$from 	= '../../upload/incidentestemp/'.$num;
				$to 	= '../../incidentes/'.$id.'/';
				//Abro el directorio que voy a leer
				if (file_exists($from)){
					$dir = opendir($from);
					//Recorro el directorio para leer los archivos que tiene
					while(($file = readdir($dir)) !== false){
						//Leo todos los archivos excepto . y ..
						if(strpos($file, '.') !== 0){
							//Copio el archivo manteniendo el mismo nombre en la nueva carpeta
							copy($from.'/'.$file, $to.'/'.$file);
						}
					}
				}				
			}
			//GUARDAR BITACORA
			$accion = 'El Correctivo #'.$id.' ha sido Creado exitosamente';
			bitacora($usuario, "Correctivo Movil", $accion, $id, $query);
			//IMPRIMIR MENSAJE
			//echo "El correo fue enviado con éxito.";
			echo $id;
		}else{
			echo 0;
		}
	}
	
function updateIncidenteAddActivo()
{
    global $mysqli;	
    
    $idincidente = $_REQUEST['idincidente'];
    $serie = $_REQUEST['serie'];
    $idActivo=$_REQUEST['idactivo'];
    
    //$conn = "SELECT id FROM activos where serie ='$serie' AND id ='$serie'";
    //$conn = "SELECT id FROM activos where id ='$idActivo'";
	 
	$conn ="SELECT a.id As idActivo,a.serie, LEFT(a.nombre,45) AS activo,a.idambientes As IdAmbiente, b.nombre AS ambiente,b.idclientes AS idCliente, d.nombre AS cliente
                FROM activos a 
				INNER JOIN ambientes b ON a.idambientes = b.id 
				INNER JOIN clientes d ON b.idclientes= d.id 
				WHERE a.id= $idActivo";
	
	$resultn = $mysqli->query($conn);		
	
	debugl($conn);	 
    
	$totn = $resultn->num_rows;
	
	if($row = $resultn->fetch_assoc()){
			$idactivo = $row['idActivo'];
			$idambiente = $row['IdAmbiente'];
			$idcliente = $row['idCliente'];
	}	
	
	if($totn > 0){
    	 $query="UPDATE incidentes SET idactivos ='$idactivo' ,idclientes='$idcliente',idambientes='$idambiente' WHERE id='$idincidente'";
    	 $result = $mysqli->query($query);
         debug($query);	 
            if($result == true)
        	 {
        	    echo 1;  
        	 }else{
        	    echo 2;
        	 }
	}else{
		echo 0;    
	}
}	

/*---------------------------------------------------------------------------*/ 
function enviarNotificacion(){
    global $mysqli;		
    
		$usuario 	= $_REQUEST['usuario'];
		$idusuario 	= $_REQUEST['user_id'];

		$query   = " SELECT correo FROM usuarios WHERE usuario = '$usuario' ";
		$result  = $mysqli->query($query);
		if($row = $result->fetch_assoc()){
			$solicitante = $row['correo'];
		}
		
		$id 	  		= $_REQUEST['id'];
		$titulo 	  	= $_REQUEST['titulo'];
		$descripcion  	= $_REQUEST['descripcion']; 
		$fechacreacion	= date('Y-m-d');
		$horacreacion 	= date('H:i:s');
		
		//ENVIAR CORREO AL CREADOR DEL INCIDENTE
		nuevoincidente($usuario, $titulo, $descripcion, $id, $fechacreacion, $horacreacion, $solicitante);
		notificarCEstadoMovil($id, '', 'creado', '', '', $fechacreacion, $descripcion, $solicitante, $solicitante,$idusuario,$usuario,$titulo);
  }


  //ENVIAR CORREO AL SOLICITANTE DEL INCIDENTE Y SOPORTE
function nuevoincidente($usuario, $titulo, $descripcion, $incidente, $fecha, $hora, $solicitante){
		global $mysqli, $mail;
		//debug('nuevoincidente '. $solicitante);
		//SOLICITANTE
		if($solicitante != ''){
			if (filter_var($solicitante, FILTER_VALIDATE_EMAIL)) {
					$correo [] = $solicitante;
			}else{
				$result = $mysqli->query("SELECT correo FROM usuarios WHERE id = '$solicitante'");
				while ($row=$result->fetch_assoc()) {
					$correo [] = $row['correo'];
				}
			}
			//Asunto
			$innovacion = 'soporteaig@innovacion.gob.pa';
			if($solicitante == $innovacion || $solicitante == 'mesadeayuda@innovacion.gob.pa' ){
				$asunto = $titulo;
			}else{
				$asunto = "Correctivo #$incidente ha sido Creado";
			}
			
			//Cuerpo
			$fecha = implode('/',array_reverse(explode('-', $fecha)));
			$cuerpo = '';		
			$cuerpo .= "<div style='background-color: #FFFFFF; margin: 0 6%; padding: 1% 2%; color: #3e4954;'><div style='text-align: right;'><b>Fecha:</b> ".$fecha."</div>";
			$cuerpo .= "<br><b>".$titulo."</b>";
			$cuerpo .= "<p style='width: 100%;'>Buen día,<br>Gracias por contactar al Centro de Soporte, su caso ha sido asignado a nuestros Ingenieros especializados quienes los contactarán brevemente para mas detalles sobre el caso.<p></div>";
			//Correo
			enviarMensajeIncidente($asunto,$cuerpo,$correo,'','');
		}
	}

//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
function notificarCEstadoMovil($incidente,$notificar,$accion,$estadoold,$estadonew,$fechacreacion, $descripcion, $solicitante, $creadopor,$userID,$sesionUser,$titulo){
		global $mysqli;
		$asunto = "Correctivo #$incidente ha sido Creado"; 
		$sitio = "";
		$departamento = "";
		$nasignadoa = "";
		$prioridad = "";	
    //DATOS DEL CORREO
    
    /*------------------de variable de sesion a parametro-------------*/
		$usuarioSes=$sesionUser;  //$usuarioSes = $_SESSION['usuario'];  
    
    $consultaUA = $mysqli->query("SELECT nombre FROM usuarios WHERE usuario = '$usuarioSes' LIMIT 1 ");
		while ($registroUA = $consultaUA->fetch_assoc()) {
			$usuarioAct = $registroUA['nombre'];
		} 
		
		//MENSAJE		
		$mensaje = "<div style='background-color: #FFFFFF; margin: 0 6%; padding: 30px;font-family: arial,sans-serif;'>
					<div style='font-size: 22px; color: #333; margin: 4% 0 4% 4%;'>".$usuarioAct." ha creado el correctivo #".$incidente."</div>";				
 
		$mensaje .= "<p style='width:100%;'>
						<a href='http://toolkit.maxialatam.com/soporte/correctivo.php?id=".$incidente."' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Correctivo</a></p>
						<br><br>
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 3% 4% 0 4%;'>Atributos</div>
						<table style='width: 100%; margin: 0 4% 0 4%;'>
							<tr>
								<td style='padding: 15px 0; font-size: small; width: 50%;'><div style='font-size: 14px;color: #808080;'>Solicitante del servicio</div>".$usuarioAct."</td>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080;'>Ambiente</div>".$sitio."</td>
							</tr>
							<tr>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080;'>Recibido en</div>".$fechacreacion."</td>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080;'>Departamento</div>".$departamento."</td>
							</tr>
							<tr>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080;'>Asignado a</div>".$nasignadoa."</td>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080;'>Prioridad</div>".$prioridad."</td>
							</tr>
							<tr>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Creado por</div>".$creadopor."</td> 
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Creado desde</div>App</td>
							</tr>
						</table>
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Título</div>
						<div style='margin: 0 4%; color: #3e4954; text-align: justify; line-height: 150%;'>".$titulo."</div>
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 3% 4% 0 4%;'>Descripción</div>
						<div style='margin: 0 4%; color: #3e4954; text-align: justify; line-height: 150%;'>".$descripcion."</div>
						"; 
			
			$mensaje .= "</div>";

		//USUARIOS DE SOPORTE
		$correo [] ='ana.porras@maxialatam.com';
		$correo [] = 'isai.carvajal@maxialatam.com';
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'lismary.goyo@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		$correo [] = 'maria.baena@maxialatam.com';
		//$correo [] = 'yamarys.powell@maxialatam.com';
    //ADJUNTOS
    /*------------------de variable de sesion a parametro-------------*/
		 $num=$userID; //$num 	= $_SESSION['user_id'];
    
	$adjuntos = array();
    $from 	= '../../upload/incidentestemp/'.$num;
	if (file_exists($from)) {
		//Abro el directorio que voy a leer
		$dir = opendir($from);
		//Recorro el directorio para leer los archivos que tiene
		while(($fileE = readdir($dir)) !== false){
			//Leo todos los archivos excepto . y ..
			if ($fileE != "." && $fileE != ".." && $fileE != ".quarantine" && $fileE != ".tmb" && $fileE != "comentarios"){
				debug('NUEVOREPORTE: ../../upload/incidentestemp/'.$num.'/'.$fileE);
				$archivo = '../../upload/incidentestemp/'.$num.'/'.$fileE;
				$adjuntos[] = $archivo;
			}				
		}
	}
		
	//ASUNTO
	$innovacion = 'soporteaig@innovacion.gob.pa';
	if($solicitante == $innovacion || $creadopor == $innovacion){
		$asunto = $row['titulo'];
	}		
	enviarMensajeIncidente($asunto,$mensaje,$correo,$adjuntos,'');
}

/*---------------------------------------------------------------------------*/ 
function enviarMensajeIncidente($asunto,$mensaje,$correos,$adjuntos,$tipo) {
		global $mysqli, $mail;
		$correo = array_unique($correos);
		//$correo[]='lismary.18@gmail.com';
		$cuerpo = "";
		$cuerpo .= "<div style='background:#f6fbf8'>
					<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; margin: 0 6% 0 6%'>";
		$cuerpo .= "		<img src='https://toolkit.maxialatam.com/soportedesarrollo/images/encabezado-maxia-c.png' style='width: auto; float: left;'>";
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
		foreach($correo as $destino){
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
				//debug('adjunto: '.$adjunto);
				$mail->AddAttachment($adjunto);
			}
		}
		if(!$mail->send()) {
			echo 'Mensaje no pudo ser enviado. ';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
			//debug('Mensaje no pudo ser enviado');
		} else {
			//debug('Ha sido enviado el correo Exitosamente');
			//echo 'Ha sido enviado el correo Exitosamente';
			foreach($adjuntos as $adjunto){
				if(is_file($adjunto))
				unlink($adjunto); //elimino el fichero
			}
			echo true;
		}
	}
  

?>