<?php
	include("conexion.php");
	$method = $_SERVER['REQUEST_METHOD'];        
    $action =isset($_REQUEST['op'])?$_REQUEST['op']:"";
    
    if ($method =='GET'){
        $action = $_REQUEST['op'];   
        switch($action){ 
            case 'incidentes':
                incidentes();
                break;
            case 'getIncidente':
                getIncidente();
                break; 
			case "actualizarIncidente":
				actualizarIncidente();
				break;
            default:
                echo "{failure-GET:true}";
                break;
        }
    }elseif ($method =='POST') {
        switch($action){ 
    	    case "nuevoreporte":
				nuevoreporte();
				break;
    	    default:
                echo "{failure-POST:true}";
                break;
        } 
	} 
    
	function formulario(){
        
        $data['id']         =   (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
        /*-DATOS-DEL-PACIENTE-------------------------------------------------*/
        $data['numero']	= (!empty($_REQUEST['numero']) ? $_REQUEST['numero'] : '');
		$data['desde']  = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '');
		$data['hasta'] 	= (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : '');
		$data['idambientes'] = (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : '');
		$data['idproyectos'] = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$data['idcategorias'] = (!empty($_REQUEST['idcategorias']) ? $_REQUEST['idcategorias'] : '');
		$data['idestados'] = (!empty($_REQUEST['idestados']) ? $_REQUEST['idestados'] : '');
		$data['asignadoa'] = (!empty($_REQUEST['asignadoa']) ? $_REQUEST['asignadoa'] : '');
		$data['modalidad'] = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '');
		$data['idactivos'] = (!empty($_REQUEST['idactivos']) ? $_REQUEST['idactivos'] : '');
	    $data['solicitante'] = (!empty($_REQUEST['solicitante']) ? $_REQUEST['solicitante'] : '');
		$data['idclientes'] = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$data['page']		= (!empty($_REQUEST['page']) ? $_REQUEST['page'] : 1);
	    
	    return $data;
    }
	
  
    function incidentes(){
		global $mysqli;
		$data=formulario();
        
		$where = '';
		
		if($numero != ''){
			$where .= " AND a.id = '".$data['numero']."' ";
		}
		
		if($desde != ''){
			$where .= " AND a.fechacreacion >= '".$data['desde']."' ";
		}
		
		if($hasta != ''){
			$where .= " AND a.fechacreacion <= '".$data['hasta']."' ";
		}
		
		if($idambientes != ''){
			$where .= " AND a.idambientes = '".$data['idambientes']."' ";
		}
		
		if($idproyectos != ''){
			$where .= " AND a.idproyectos = '".$data['idproyectos']."'";
		}
		
		if($idcategorias!=''){
			$where .= " AND a.idcategorias = '".$data['idcategorias']."'";
		}
		
		if($idestados != ''){

		    if($idestados == 'not'){
			    $where .= " AND a.idestados IN (12,13,14,15,18,26,28,31,33,42,43,44,45,46,47,48,49,50,51)";
			}else{
			    $where .= " AND a.idestados IN ('".$data['idestados']."') ";
			}
			
		}
		
		if($asignadoa != ''){
			$where .= " AND a.asignadoa = '".$data['asignadoa']."' ";
		}
		
		if($modalidad != ''){

			$where .= " AND ti.id IN ('".$data['modalidad']."')";
		}
		
		if($idactivos != ''){
			$where .= " AND a.idactivos = '".$data['idactivos']."' ";
		}
		
		if($solicitante != ''){
			$where .= " AND a.solicitante = '".$data['solicitante']."' ";
		}
		
		if($idclientes != ''){
			$where .= " AND a.idclientes = '".$data['idclientes'].""; 
		}           
	
		
		$query  = " SELECT a.id, e.nombre AS estado, LEFT(a.titulo,45) as titulo, a.titulo as titulott,
					IFNULL(j.nombre, a.solicitante) AS solicitante, a.fechacreacion, a.horacreacion, a.fechacierre,
					f.nombre AS categoria, g.nombre AS subcategoria, a.asignadoa, l.nombre AS nomusuario, 
					c.nombre AS ambiente, m.serie, mar.nombre as marca, r.nombre as modelo, m.modalidad, h.prioridad, a.fecharesolucion, 
					case when a.fechacierre IS NULL OR LENGTH(ltrim(rTrim(a.fechacierre))) > 0
					then a.fechacreacion else a.fechacierre end as fechaorden,
					n.descripcion as idempresas, o.nombre as iddepartamentos, p.nombre as idclientes, a.estadoant
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN activos m ON a.idactivos = m.id AND a.idambientes = m.idambientes
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					LEFT JOIN marcas mar ON m.idmarcas = mar.id
					LEFT JOIN modelos r ON m.idmodelos = r.id
					LEFT JOIN activostipos ti ON ti.id = m.idtipo";
		
		$query  .= " WHERE a.tipo = 'incidentes' ";

		//$query  .= permisos('correctivos', '', $data['id']);
		$query  .= " $where ";
		$query  .= " AND (a.fechacreacion > DATE_SUB(CURDATE(),INTERVAL 1 YEAR) OR a.idestados <> 17 OR a.idestados <> 16) ";
		$query  .= " GROUP BY a.id";
		
		if(!$result = $mysqli->query($query)){
		    die($mysqli->error);  
    	}
    	/*-TOTAL-REGISTROS----------------------------------------------------*/	
    	$recordsTotal = $result->num_rows;
    	$inicio = $data['page'] * 10 - 10;
    	$query .= " ORDER BY a.id DESC LIMIT $inicio, 10 ";
    	debugL($query,"consultas");
    	/*-REGISTRO-FILTRADO--------------------------------------------------*/
    	$result = $mysqli->query($query);
    	$records = $result->num_rows;
		//varble infiniti scroll numero de registros
		$response = array();
		if($result->num_rows > 0 ){
		 	
		 	while($row = $result->fetch_assoc()){
				$siglasestado = strtoupper(substr(str_replace(' ','',$row['estado']), 0, 2));
				
				$response['data'][]=array(
					'id'        => $row['id'],
					'titulo'    => $row['titulo'],
					'fechacreacion' => $row['fechacreacion'],
					'Siglas'    => $siglasestado,
					'tipo'      =>'correctivo');	
		 	}
    		
    		$response['estatus'] = "ok";
    		$response['totalResults'] = intval($recordsTotal);
    		$response['recordsFiltered'] = intval($records);
    		echo json_encode($response);  
    		
		}else{
		    
		    $response['estatus'] = "ok";
		    $response['data'] = array();
            echo json_encode($response);
		}
	}
	
	function getIncidente(){
		global $mysqli;
		$id = $_REQUEST['id'];
		
		$query  = " SELECT a.contacto,a.id, a.titulo, a.descripcion, a.idestados, a.idambientes, a.idsubambientes, a.idclientes, a.idproyectos, 
					a.iddepartamentos, a.fechacreacion, a.horacreacion, a.asignadoa, a.idcategorias, a.idsubcategorias, a.idprioridades, 
					a.resolucion, a.fecharesolucion, a.horaresolucion, a.reporteservicio
					FROM incidentes a
					WHERE a.id IN ($id)";
		debug($query);
		$result = $mysqli->query($query);
		if($result->num_rows > 0 ){
			$json = array();
			while($row = $result->fetch_assoc()){
				$json[]= array(
							'id' => $row['id'],
							'titulo' => $row['titulo'],
							'descripcion' => $row['descripcion'],
							'idestados' => $row['idestados'],
							'idambientes' => $row['idambientes'],
							'idsubambientes' => $row['idsubambientes'],
							'idclientes' => $row['idclientes'],
							'idproyectos' => $row['idproyectos'],
							'iddepartamentos' => $row['iddepartamentos'],
							'fechacreacion' => $row['fechacreacion'],
							'horacreacion' => $row['horacreacion'],
							'asignadoa' => $row['asignadoa'],
							'idcategorias' => $row['idcategorias'],
							'idsubcategorias' => $row['idsubcategorias'],
							'idprioridades' => $row['idprioridades'],
							'resolucion' => $row['resolucion'],
							'fecharesolucion' => $row['fecharesolucion'],
							'horaresolucion' => $row['horaresolucion'],
							'reporteservicio' => $row['reporteservicio'],
							'contacto'=>$row['contacto']
						);
			}
			echo json_encode($json);
		}else{
			echo json_encode("no hay registros");
		}
	}
	
	function actualizarIncidente()
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
		$idambiente   		= (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : '');//unidad
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
		$queryInc = $mysqli->query("SELECT idestados, asignadoa FROM incidentes WHERE id = '$id'");
		while ($rowInc = $queryInc->fetch_assoc()) {
			$estadoInc = $rowInc['idestados'];
			$asignadoaInc = $rowInc['asignadoa'];
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
							
			echo true;
		}else{
			echo false;
		}
   }
   
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
		$correo [] = 'isai.carvajal@maxialatam.com';
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		$correo [] = 'yamarys.powell@maxialatam.com'; 
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
		$correo [] = 'isai.carvajal@maxialatam.com';
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		$correo [] = 'yamarys.powell@maxialatam.com'; 
		  
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
				//$mail->addAddress($destino);
			}else{
				if( $destino != 'mesadeayuda@innovacion.gob.pa' ){
					//$mail->addAddress($destino);
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
	
	function nuevoreporte(){
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
			//bitacora($usuario, "Correctivo Movil", $accion, $id, $query);
			//IMPRIMIR MENSAJE
			//echo "El correo fue enviado con éxito.";
			echo $id;
		}else{
			echo 0;
		}
	}
?>