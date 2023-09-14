<?php
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
  header('content-type: application/json; charset=utf-8');
  header('Content-Type: application/JSON');
  	header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");
  //capurando el tipo de metodo                
	include("../../conexion.php");
	
	$method = $_SERVER['REQUEST_METHOD'];        
  
      if ($method =='GET') 
      {
           $action = $_REQUEST['action'];   
    	        switch($action)
    	        {
    		        case "guardarIncidente":
    	  		        guardarIncidente();
                        break;			
    		        default:
    			        echo "{failure:true}";
    			    break;
    	        }
     
      } else {
          
        $response['status'] = "fail-Incident";
    	$response['cod'] = '004';
        echo json_encode($response);
      }

	function guardarIncidente()
	{
    global $mysqli;
    
        $usuario   	= (!empty($_REQUEST['usuario']) ? $_REQUEST['usuario'] : '');
        $user_id   = (!empty($_REQUEST['idusuario']) ? $_REQUEST['idusuario'] : '');
        $user_nivel  = (!empty($_REQUEST['user_nivel']) ? $_REQUEST['user_nivel'] : '');    
        $correo= (!empty($_REQUEST['correo']) ? $_REQUEST['correo'] : '');
        
        $id   				= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$descripcion   		= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');
		$idempresas 		= (!empty($_REQUEST['idempresas']) ? $_REQUEST['idempresas'] : 1);		
		$cliente 			= (!empty($_REQUEST['cliente']) ? $_REQUEST['cliente'] : 0);
		$proyecto 			= (!empty($_REQUEST['proyecto']) ? $_REQUEST['proyecto'] : 0);
		$iddepartamentos	= (!empty($_REQUEST['departamentos']) ? $_REQUEST['departamentos'] : 0);
		$idambiente   			= (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : '');//unidad
		$estado 			= (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : 12);
		$categoria 			= (!empty($_REQUEST['categoria']) ? $_REQUEST['categoria'] : 0);
		$subcategoria 		= (!empty($_REQUEST['subcategoria']) ? $_REQUEST['subcategoria'] : 0);
		$prioridad 			= (!empty($_REQUEST['prioridad']) ? $_REQUEST['prioridad'] : 0);
		$asignadoa   		= (!empty($_REQUEST['asignadoa']) ? $_REQUEST['asignadoa'] : '');
		$resolucion   		= (!empty($_REQUEST['resolucion']) ? $_REQUEST['resolucion'] : '');
		$fecharesolucion   	= (!empty($_REQUEST['fecharesolucion']) ? $_REQUEST['fecharesolucion'] : '');
		$horaresolucion   	= (!empty($_REQUEST['horaresolucion']) ? $_REQUEST['horaresolucion'] : '');
		$reporteservicio   	= (!empty($_REQUEST['reporteservicio']) ? $_REQUEST['reporteservicio'] : '');
		$fechacierre 		= (!empty($data['fechacierre']) ? $data['fechacierre'] : '');
		$fechacreacion		= (!empty($data['fechacreacion']) ? $data['fechacreacion'] : date("Ymd"));
		$horacreacion 		= (!empty($data['horacreacion']) ? $data['horacreacion'] : date("H:i:s"));
		$contacto 		    = (!empty($_REQUEST['contacto']) ?$_REQUEST['contacto'] : '');
		/*
		if($fecharesolucion != ''){
			$fecharesolucion = preg_split("/[\s,]+/",$fecharesolucion);
			echo $horaresolucion  = "'".$fecharesolucion[1]."'";
			echo $fecharesolucion = "'".$fecharesolucion[0]."'";			   
		}else{
			$fecharesolucion = 'null';
			$horaresolucion  = 'null';
		}
		*/
		if($fechacierre == '' && $estado == 16){
			$fechacierre	= "'".date('Y-m-d')."'";
			$horacierre 	= "'".date('H:i:s')."'";
		}elseif ($fechacierre == '') {
			$fechacierre = 'null';
			$horacierre  = 'null';
		} else {
			$horacierre  = "'".$horacierre."'";
			$fechacierre = "'".$fechacierre."'";			
		}
		$fecharesolucion = str_replace("'","",$fecharesolucion);
		$horaresolucion  = str_replace("'","",$horaresolucion);
		$fechacierre 	 = str_replace("'","",$fechacierre);
		$horacierre 	 = str_replace("'","",$horacierre);
		
		//DIAS Y HORAS
		if($prioridad != '0' || $prioridad != ''){
			$queryV  			= " SELECT dias, horas FROM sla WHERE id = '$prioridad' ";
			$resultV 			= $mysqli->query($queryV);
			$rowV 				= $resultV->fetch_assoc();
			$diasP 				= $rowV['dias'];
			$horasP 			= $rowV['horas'];
			$fechavencimiento 	= date('Y-m-d', strtotime($fechacreacion."+ ".$diasP." days"));
			$horavencimiento  	= date('H:i:s', strtotime($horacreacion." + ".$horasP." hours"));
		}
		//ACTUALIZAR
		$queryInc = $mysqli->query("SELECT idestados FROM incidentes WHERE id = '$id'");
		while ($rowInc = $queryInc->fetch_assoc()) {
			$estadoInc = $rowInc['idestados'];
		}
		$queryAsig = $mysqli->query("SELECT asignadoa FROM incidentes WHERE id = '$id'");
		while ($rowAsig = $queryAsig->fetch_assoc()) {
			$asignadoaInc = $rowAsig['asignadoa'];
		} 
		
		$descripcion = str_replace("'","",$descripcion);
		
		
		if($id != ''){
			$query = " UPDATE incidentes SET ";
			if($descripcion != ''){
				$query .= " descripcion = '$descripcion' ";
			}		
			if($idempresas != 0){
				$query .= ", idempresas = '$idempresas' ";
			}		
			if($cliente != 0 && $cliente != ''){
				$query .= ", idclientes = '$cliente' ";
			}
			if(	$contacto!=''){
			    $query .= ", contacto = '$contacto' ";
			}
			if($proyecto != 0 && $proyecto !=''){
				$query .= ", idproyectos = '$proyecto' ";
			}
			if($iddepartamentos != 0 && $iddepartamentos != ''){
				$query .= ", iddepartamentos = '$iddepartamentos' ";
			}
			if($idambiente != 0 && $idambiente != ''){
				$query .= ", idambientes = '$idambiente' ";
			}
			if($estado != 0 && $estado != ''){
				$query .= ", idestados = '$estado' ";
			}
			if($categoria != 0 && $categoria != ''){
				$query .= ", idcategorias = '$categoria' ";
			}
			if($subcategoria != 0 && $subcategoria != ''){
				$query .= ", idsubcategorias = '$subcategoria' ";
			}
			if($prioridad != 0 && $prioridad != ''){
				$query .= ", idprioridades = '$prioridad' ";
			}
			if($asignadoa !== 0 && $asignadoa !== ''){
				$query .= ", asignadoa = '$asignadoa' ";
			}
			if($resolucion != ''){
				$query .= ", resolucion = '$resolucion' ";
			}
			if($fecharesolucion != '' && $fecharesolucion != null && $fecharesolucion != 'null'){
				$query .= ", fecharesolucion = '$fecharesolucion' ";
			}
			if($horaresolucion != null && $horaresolucion != 'null'){
				$query .= ", horaresolucion = '$horaresolucion' ";
			}
			if($reporteservicio != ''){
				$query .= ", reporteservicio = '$reporteservicio' ";
			}
			if($fechacierre != '' && $fechacierre != null && $fechacierre != 'null'){
				$query .= ", fechacierre = '$fechacierre' ";
			}		
			if($horacierre != '' && $horacierre != null && $horacierre != 'null'){
				$query .= ", horacierre = '$horacierre' ";
			}		
			/*if($estado < $estadoInc){
				$query .= " , estadoant = '1' ";
			}*/
			if($estado < $estadoInc && $estado != '34' ){
			    $query .= " , estadoant = '1' ";
		    }
		    if($estadoInc != 16 && $estado == '16' ){
			    $query .= " , resueltopor = '".$correo."' ";
		    }
			
			$query .= " WHERE id = $id ";
			$query = str_replace('SET ,','SET ',$query);
			debug('UPDATEINC:'.$query);
		}
		if($mysqli->query($query)){
			//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
			if($estadoInc != $estado){
				//CREAR REGISTRO EN ESTADOS INCIDENTES
				$queryE = " SELECT estadonuevo, fechacambio FROM incidentesestados WHERE idincidentes = '$id' ORDER BY id DESC LIMIT 1 ";
				$resultE = $mysqli->query($queryE);
				if($resultE->num_rows >0){
					$rowE = $resultE->fetch_assoc();
					$estadoanterior = $estadoInc;
					$fechacambio = $rowE['fechacambio'];
				}else{
					$estadoanterior = $estadoInc;
					$qfechac = " SELECT fechacreacion FROM incidentes WHERE id = $id ";
					$rfechac = $mysqli->query($qfechac);
					$regf = $rfechac->fetch_assoc();
					$fechacambio = $regf['fechacreacion'];
				}
				
				$fechahoy = date('Y-m-d');
				$date1 = new DateTime($fechahoy);
				$date2 = new DateTime($fechacambio);
				$diff = $date1->diff($date2);
				$queryE = " INSERT INTO incidentesestados VALUES(null, $id, '$estadoanterior', '$estado', $idusuario, now(), now(), $diff->days) ";
				$mysqli->query($queryE);
			
				if($estado == 13){
					$query = "SELECT idproyectos FROM usuarios WHERE correo = '$asignadoa' ";
					$result = $mysqli->query($query);
					if($result->num_rows >0){
						$row = $result->fetch_assoc();				
						$proyectosusu = $row['idproyectos'];
					}
					//ACTUALIZAR INCIDENTE
					//$queryUP = "UPDATE incidentes SET idproyectos = '$idproyectos' WHERE id = $id ";
					//$resultUP = $mysqli->query($queryUP);
				}    

				notificarCEstado($id,$notificar,'actualizado',$estadoanterior,$estado,$usuario,$user_id,$user_nivel);
				if($prioridad == '7' && ($estado == 16 || $estado == 17)){
					$queryfs  = "UPDATE activos set estado = 'ACTIVO' WHERE codequipo = '$serie' ";
					$resultfs = $mysqli->query($queryfs);
					$queryfs  = "UPDATE fueraservicio set hasta = $fecharesolucion WHERE  incidente = $id ";
					$resultfs = $mysqli->query($queryfs);
				}
			}
			if($asignadoaInc != $asignadoa){
				notificarCAsignadoa($id,$notificar,'actualizado',$asignadoaInc,$asignadoa);
			}
			
			//$accion = 'El registro #'.$id.' ha sido Actualizado exitosamente';
			//bitacora($_REQUEST['usuario'], "Incidentes", $accion, $id, $query);
            //actualizarRegistro('Incidentes','Incidente',$id,$valoresold,$campos,$query);    
			
			//ENVIAR CORREO DE SATISFACCION - RESUELTO / CERRADO
			if($estado == 16 || $estado == 17){
				//crearMensajeSatisfaccion($id,$titulo,$solicitante);
			}				
			echo true;
		}else{
			echo false;
		}
		//echo true;
   }  

	//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
	function notificarCEstado($incidente,$notificar,$accion,$estadoold,$estadonew,$usuario,$user_id,$user_nivel){
		global $mysqli;
		
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, c.nombre AS ambiente,
					d.serie, q.nombre AS marca, r.nombre AS modelo, e.nombre AS estado, f.id AS idcategorias, f.nombre AS categoria, g.nombre AS subcategoria,
					h.prioridad, a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, IFNULL(j.nombre, a.solicitante) AS solicitante, a.asignadoa,
					a.departamento, d.modalidad, a.satisfaccion, a.comentariosatisfaccion, a.resolucion, IFNULL(k.nombre, a.resueltopor) AS resueltopor,
					IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0),CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion,
					IF(( a.fechavencimiento is not null OR LENGTH(ltrim(rTrim(a.fechavencimiento))) > 0),CONCAT(a.fechavencimiento,'  ', a.horavencimiento),'') AS fechavencimiento,
					IF(( a.fecharesolucion is not null OR LENGTH(ltrim(rTrim(a.fecharesolucion))) > 0),CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,
					IF(( a.fechacierre is not null OR LENGTH(ltrim(rTrim(a.fechacierre))) > 0),CONCAT(a.fechacierre,'  ', a.horacierre),'') AS fechacierre, a.fechamodif, a.fechacertificar, 
					a.horastrabajadas, a.comentariovisto, IFNULL(i.correo, a.creadopor) AS correocreadopor, a.notificar,
					IFNULL(j.correo, a.solicitante) AS correosolicitante, a.idclientes, a.idproyectos, a.tipo
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
					LEFT JOIN marcas q ON d.idmarcas = q.id
					LEFT JOIN modelos r ON d.idmodelos = r.id
					WHERE a.id = ".$incidente." GROUP BY a.id  ";
					
		$result = $mysqli->query($query);
		$row 	= $result->fetch_assoc();
		$tipo = $row['tipo'];
			
		if($tipo == 'incidentes'){
			$nombreMay = 'Correctivo'; 
			$nombreMin = 'correctivo';
		}else{
			$nombreMay = 'Preventivo';
			$nombreMin = 'preventivo';
		}
		$idclientes = $row['idclientes'];
		$idproyectos = $row['idproyectos'];
		//1 para quien quien creo el incidentes (Creado por)
		$correo [] = $row['correocreadopor'];
		$notificar 	= $row['notificar'];
		//2 para quien solicito o reporto el incidente (Solicitante)
		if($estadonew == 16 || $estadonew == 17){
			if($row['correosolicitante'] != 'mesadeayuda@innovacion.gob.pa'){
				$correo [] = $row['correosolicitante'];
			}			
		}
		//3 para quien se le asigno el incidente (Asignado a)	
		//USUARIO O GRUPO DE USUARIOS ASIGNADOS
		$asignadoaN	= '';		
		if($row['asignadoa'] != ''){
			$asignadoa  = $row['asignadoa'];
			if (filter_var($asignadoa, FILTER_VALIDATE_EMAIL)) {
				if( $asignadoa != 'mesadeayuda@innovacion.gob.pa' ){
					$correo [] = $asignadoa;	
				}							
			}else{
				foreach([$asignadoa] as $asig){
					if( $asig != 'mesadeayuda@innovacion.gob.pa' ){
						$correo [] = $asig;	
					}
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
		//CLIENTE AIG - USUARIOS DE PRUEBA
		if($idclientes == 13 && $estadonew == 32 && $row['asignadoa'] == 'soportemaxia@zertifika.com'){
			$queryc = " SELECT correo FROM usuarios WHERE nivel = 6 AND idclientes = 13";
			$consultac = $mysqli->query($queryc);
			while($recc = $consultac->fetch_assoc()){
				$correo [] = $recc['correo'];	
			}
		}
		//NOTIFICACION ZERTIFIKA
		if($idclientes == 13 && $estadonew == 26 && $row['asignadoa'] == 'soportemaxia@zertifika.com'){
			$correo [] = 'soportemaxia@zertifika.com';
		}
		
		//ENVIAR CORREO DEL INCIDENTE A LOS USUARIOS SELECCIONADOS
		//4 para los usuarios que quieren que se les notifique (Enviar Notificacion a)
		if($notificar != '[]' && $notificar != ''){
			$asunto    = "Notificación del Incidente #$incidente";
			$notificar = json_decode($notificar);
			if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
				if( $notificar != 'mesadeayuda@innovacion.gob.pa' ){
					$correo [] = "$notificar";	
				}
			}else{
				foreach($notificar as $notif){
					if( $notif != 'mesadeayuda@innovacion.gob.pa' ){
						$correo [] = $notif;	
					}
				}
			}
		}
		//else{
			if($accion == 'creado'){
				$asunto = $nombreMay." #$incidente ha sido Creado";
			}else{ //actualizado
				if ($estadoold != $estadonew && $estadonew == 13) {
					$asunto = $nombreMay." #$incidente ha sido Asignado";			
				} elseif ($estadoold != $estadonew && $estadonew == 16) {
					$asunto = $nombreMay." #$incidente ha sido Resuelto";	
					//if (substr($row['titulo'],0,14)=='[Service Desk]') {
					if ($row['correosolicitante']=='mesadeayuda@innovacion.gob.pa') {
						$asunto = $row['titulo']." (Incidente Maxia #$incidente) ha sido Resuelto";
					    //$correo [] = 'mesadeayuda@innovacion.gob.pa';
					}
				}
				else {
					$asunto = $nombreMay." #$incidente ha sido Actualizado";			
				}
			}
		//}
		//DATOS DEL CORREO
        $usuarioSes =$usuario;   //$usuarioSes = $_SESSION['usuario'];
		$consultaUA = $mysqli->query("SELECT nombre FROM usuarios WHERE usuario = '".$usuarioSes."' LIMIT 1 ");
		while ($registroUA = $consultaUA->fetch_assoc()) {
			$usuarioAct = $registroUA['nombre'];
		}
		//ESTADO ANTERIOR
    	$estadoant = '';
		if($estadoold != ''){
			$consultaEO = $mysqli->query("SELECT nombre FROM estados WHERE id = '".$estadoold."' ");
			if($estadonew != ''){
				$registroEO = $consultaEO->fetch_assoc();
				$estadoant = $registroEO['nombre'];
			}
		}		
    	//ESTADO NUEVO
		if($estadonew != ''){
			$consultaEN = $mysqli->query("SELECT nombre FROM estados WHERE id = '".$estadonew."' ");
			$registroEN = $consultaEN->fetch_assoc();
			$estadonue = $registroEN['nombre'];
		}else{
			$estadonue = '';
		}
		
		//DATOS
		$fechacreacion 	= $row['fechacreacion'];
		$titulo			= $row['titulo'];
		$descripcion	= $row['descripcion'];
		$solicitante	= $row['solicitante'];
		$creadopor		= $row['creadopor'];
		$departamento	= $row['departamento'];
		$prioridad		= $row['prioridad'];
		$sitio 			= $row['ambiente'];
		$resolucion 	= $row['resolucion'];
		$nasignadoa 	= $asignadoaN;
		//MENSAJE
		if($accion == 'creado'){
			$mensaje = "<div style='background-color: #FFFFFF; margin: 0 6%; padding: 30px;font-family: arial,sans-serif;'>
					<div style='font-size: 22px; color: #333; margin: 4% 0 4% 4%;'>".$usuarioAct." ha creado el ".$nombreMin." #".$incidente."</div>";
		}else{ //actualizado
			$mensaje = "<div style='background-color: #FFFFFF; margin: 0 6%; padding: 30px;font-family: arial,sans-serif;'>
					<div style='font-size: 22px; color: #333; margin: 4% 0 4% 4%;'>".$usuarioAct." ha actualizado el ".$nombreMin." #".$incidente."</div>";		
		}		
		
		if($estadonew == 13){
			$mensaje .= "<p style='color: #3e4954; margin-left: 4%; width:100%;'>El  ha sido asignado a: ".$nasignadoa."</p>";
		}elseif($estadoant !='' && $estadonue !=''){
			$mensaje .= "<p style='color: #3e4954; margin: 2% 0 5% 4%; width:100%; font-size: 22px;'>El Estado cambió de ".$estadoant." a <b>".$estadonue."</b></p>";
		}
		$mensaje .= "<p style='width:100%;'>
						<a href='http://toolkit.maxialatam.com/soporte/".$nombreMin.".php?id=".$incidente."' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver ".$nombreMay."</a></p>";
			if($estadonew == 16 || $estadonew == 17){
				//GENERAR FECHA DE CIERRE 
				$query = "  UPDATE incidentes SET fechacierre = DATE_ADD(fecharesolucion, INTERVAL 3 DAY), horacierre = horaresolucion, 
							idestados = 16 WHERE id = '".$incidente."' ";
				$mysqli->query($query);
				$mensaje .= "<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 7% 4% 1% 4%;'>Resolución</div>
					<div style='margin: 0 4%; color: #3e4954; text-align: justify; line-height: 150%;'>".$resolucion."</div>";	
			}
			$mensaje .=" 
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 3% 4% 0 4%;'>Atributos</div>
						 <table style='width: 100%; margin: 0 4% 0 4%;'>
							<tr>
								<td style='padding: 15px 0; font-size: small; width: 50%;'><div style='font-size: 14px;color: #808080;'>Solicitante del servicio</div>".$solicitante."</td>
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
		$correo [] = 'ana.porras@maxialatam.com';
		$correo [] = 'isai.carvajal@maxialatam.com';
		$correo [] = 'fernando.rios@maxialatam.com';
		//$correo [] = 'lismary.goyo@maxialatam.com';
		$correo [] = 'christopher.carnevale.p@gmail.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		/* ******************************************************************************** /
		//	Si el solicitante es la AIG solo se le enviará un correo al cambiar el estado 
		//  del incidente a Resuelto
		// ******************************************************************************** */
		
		if($user_nivel == 4){
			$num 	= $user_id;
			$from 	= '../incidentestemp/'.$num;
			$adjuntos = array();
			//Abro el directorio que voy a leer
			$dir = opendir($from);
			//Recorro el directorio para leer los archivos que tiene
			while(($fileE = readdir($dir)) !== false){
				//Leo todos los archivos excepto . y ..
				if ($fileE != "." && $fileE != ".." && $fileE != ".quarantine" && $fileE != ".tmb" && $fileE != "comentarios"){ 
					$archivo = '../../incidentestemp/'.$num.'/'.$fileE;
					$adjuntos[] = $archivo;
				}				
			}
		}else{
			$adjuntos = '';
		}
		
		if ($row['correosolicitante']=='mesadeayuda@innovacion.gob.pa') {
			$asunto = $row['titulo']." (Incidente Maxia #$incidente) ha sido Resuelto";
			if ($estadoold != $estadonew && $estadonew == 16) {
				enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
			}
		} else {
			enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
		}
		//enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
	}
	
	//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
	function notificarCAsignadoa($incidente,$notificar,$accion,$asignadoaInc,$asignadoa){
		global $mysqli;
		
		$query  = " SELECT a.id, a.titulo, a.descripcion,IFNULL(i.nombre, a.creadopor) AS creadopor, a.asignadoa, 
					IF(a.fechacreacion IS NOT NULL,CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion,
					IFNULL(i.correo, a.creadopor) AS correocreadopor, a.idclientes, a.tipo
					FROM incidentes a 
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					WHERE a.id = $incidente GROUP BY a.id ";
					
		$result = $mysqli->query($query);
		$row 	= $result->fetch_assoc();
		$tipo 	= $row['tipo'];
		$idclientes = $row['idclientes']; 
		//1 para quien quien creo el incidentes (Creado por)
		$correo [] = $row['correocreadopor'];
		
		//2 para quien se le asigno el incidente (Asignado a)	
		
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
		
		//ENVIAR CORREO DEL INCIDENTE A LOS USUARIOS SELECCIONADOS
		//4 para los usuarios que quieren que se les notifique (Enviar Notificacion a)
		if($notificar != '[]' && $notificar != ''){
			$asunto    = "Notificación del Incidente #$incidente";
			$notificar = json_decode($notificar);
			if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
				$correo [] = "$notificar";				
			}else{
				foreach($notificar as $notif){
					$correo [] = $notif;
				}
			}
		}
		
		//ASIGNADOA ANTERIOR
		$consultaAO = $mysqli->query("SELECT nombre FROM usuarios WHERE correo = '".$asignadoaInc."' ");
		$registroAO = $consultaAO->fetch_assoc();
		$asignadoaant = $registroAO['nombre'];
		
		//ASIGNADOA NUEVO
		$consultaAN = $mysqli->query("SELECT nombre FROM usuarios WHERE correo = '".$asignadoa."' ");
		$registroAN = $consultaAN->fetch_assoc();
		$asignadoanue = $registroAN['nombre'];
		//debug('anterior:'.$asignadoaant.'-'.);
		//DATOS
		$fechacreacion 	= $row['fechacreacion'];
		$titulo			= $row['titulo'];
		$descripcion	= $row['descripcion']; 
		$creadopor		= $row['creadopor']; 
		$nasignadoa 	= $asignadoaN;
		if($tipo == 'incidentes'){
			$nombreMay = 'Correctivo'; 
			$nombreMin = 'correctivo';
		}else{
			$nombreMay = 'Preventivo';
			$nombreMin = 'preventivo';
		}
		
		$asunto = $nombreMay." #$incidente ha sido Actualizado";
		
		//MENSAJE 
		$mensaje = "<div style='margin: 0 6%; background-color: #fff; padding: 1% 3%;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%; margin-left: 4%; color: #333;'>Asignado anterior: ".$asignadoaant.", Asignado nuevo: <b>".$asignadoanue."</b></p>";
		 
		$mensaje .= 	"<p style='margin-left: 1%; margin-top: 3%; width:100%;'>
						<a href='http://toolkit.maxialatam.com/soporte/".$nombreMin.".php?id=".$incidente."' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver ".$nombreMay."</a></p>
						<br><br>
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Atributos</div>
						<table style='width: 100%; margin: 0 4% 2% 4%;'> 
							<tr>
								<td style='padding: 15px 0; font-size: small; vertical-align: top;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Creado por</div>".$creadopor."</td> 
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080;'>Recibido en</div>".$fechacreacion."</td>
							</tr>
						</table> 
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Título</div>
						<div style='margin: 1% 4% 2% 4%; color: #3e4954; text-align: justify; line-height: 150%;'>".$titulo."</div>
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Descripción</div>
						<div style='margin: 0 4% 2% 4%; color: #3e4954; text-align: justify; line-height: 150%;'>".$descripcion."</div>";  
		$mensaje .= "</div>";
		//USUARIOS DE SOPORTE
		$correo [] = 'ana.porras@maxialatam.com';
		$correo [] = 'isai.carvajal@maxialatam.com';
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		//$correo [] = 'lismary.goyo@maxialatam.com';
		  
		$correo [] = 'christopher.carnevale.p@gmail.com';
		enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
	}

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
		//Eliminar correo Sin Especificar
	
		$mail->clearAddresses();
		foreach ($correo as $clave => $destino){
			//debugL($destino);
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
			if($adjuntos != ''){
				foreach($adjuntos as $adjunto){
					if(is_file($adjunto))
					unlink($adjunto); //elimino el fichero
				}
			}
			echo true;
		}
	}

?>