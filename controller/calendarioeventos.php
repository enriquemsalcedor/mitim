<?php
    include_once("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "eventos": 
              eventos();
			  break;
		case "getCategoria":
			 getCategoria();
			 break;
		case "guardarfiltros": 
              guardarfiltros();
			  break;
		case "abrirfiltros": 
              abrirfiltros();
			  break;
	    case "verificarfiltros": 
              verificarfiltros();
			  break;
	    case "limpiarFiltrosMasivos": 
              limpiarFiltrosMasivos();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}

	function eventos(){
	    
		global $mysqli;
		
		$where2 = ""; 
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		$start = $_REQUEST['start'];
		$end   = $_REQUEST['end'];
		$event_array = array();
		$hayFiltrosC = 0;
		$hayFiltrosP = 0;
		$hayFiltrosF = 0;
		
		$queryF 	= "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Calendario' AND usuario = '".$_SESSION['usuario']."'";		
		$result = $mysqli->query($queryF);
		if($result->num_rows >0){
			$row = $result->fetch_assoc();				
			if (!isset($_REQUEST['data'])) {
				$data = $row['filtrosmasivos'];
			}
		}
		
		if($data != ''){
			$data = json_decode($data);
			
			if(!empty($data->tipoprev)){ 
				if(!empty($data->desdef)){
					$desdef = json_encode($data->desdef);
					$where2 .= " AND a.fechareal >= $desdef ";
				} 
				if(!empty($data->hastaf)){
					$hastaf = json_encode($data->hastaf);
					$where2 .= " AND a.fechareal <= $hastaf ";
				}
			}else{
				if(!empty($data->desdef)){
					$desdef = json_encode($data->desdef);
					$where2 .= " AND a.fechacreacion >= $desdef ";
				} else {
					//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
				}
				if(!empty($data->hastaf)){
					$hastaf = json_encode($data->hastaf);
					$where2 .= " AND a.fechacreacion <= $hastaf ";
				}
			} 
			
			/* if(!empty($data->desdef)){
				$desdef = json_encode($data->desdef);
				$where2 .= " AND a.fechacreacion >= $desdef ";
			} else {
				//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where2 .= " AND a.fechacreacion <= $hastaf ";
			} */
			if(!empty($data->categoriaf)){
				$categoriaf = json_encode($data->categoriaf);
				if($categoriaf != '[""]'){
					$where2 .= " AND a.idcategorias IN ($categoriaf)";
				}
			}
			if(!empty($data->subcategoriaf)){
				$subcategoriaf = json_encode($data->subcategoriaf);
				if($subcategoriaf != '[""]'){
					$where2 .= " AND a.idsubcategorias IN ($subcategoriaf)";
				}
			}			
			if(!empty($data->idempresasf)){
				$idempresasf = json_encode($data->idempresasf);
				if($idempresasf != '[""]'){
					$where2 .= " AND a.idempresas IN ($idempresasf)"; 
				}				
			}
			if(!empty($data->iddepartamentosf)){
				$iddepartamentosf = json_encode($data->iddepartamentosf);
				$where2 .= " AND a.iddepartamentos IN ($iddepartamentosf)"; 
			}
			if(!empty($data->idclientesf)){
				$idclientesf = json_encode($data->idclientesf);
				if($idclientesf != '[""]'){
					$where2 .= " AND a.idclientes IN ($idclientesf)"; 
				}				
			}
			if(!empty($data->idproyectosf)){
				$idproyectosf = json_encode($data->idproyectosf);
				if($idproyectosf != '[""]'){
					$where2 .= " AND a.idproyectos IN ($idproyectosf)"; 
				}				
			}
			if(!empty($data->prioridadf)){
				$prioridadf = json_encode($data->prioridadf);
				if($prioridadf != '[""]'){
					$where2 .= " AND a.idprioridades IN ($prioridadf)";
				}				
			}
			if(!empty($data->modalidadf)){
				$modalidadf = json_encode($data->modalidadf);
				if($modalidadf != '[""]'){
					$where2 .= " AND m.modalidad IN ($modalidadf)";
				}
			}
			if(!empty($data->marcaf)){
				$marcaf = json_encode($data->marcaf);
				if($marcaf != '[""]'){
					$where2 .= " AND m.marca IN ($marcaf)"; 
				}
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				if($solicitantef != '[""]'){
					$where2 .= " AND a.solicitante IN ($solicitantef)";
				}
			}
			if(!empty($data->estadof)){
				$estadof = json_encode($data->estadof);
				if($estadof != '[""]'){
					$where2 .= " AND a.idestados IN ($estadof)";
				}
			}
			if(!empty($data->asignadoaf)){
				$asignadoaf = json_encode($data->asignadoaf);
				$asignadoaf = '';
				$i = 0;
				foreach($data->asignadoaf as $usuarios){
					if($i > 0)
						$asignadoaf .=",";
					$asignadoaf .= "'$usuarios'";
					$i++;
				}
				if($asignadoaf != "''"){
					$where2 .= " AND a.asignadoa IN ($asignadoaf)";	
				}
			}
			if(!empty($data->idambientesf)){
				$idambientesf = json_encode($data->idambientesf);
				 if($idambientesf !== '[""]'){ 
					$where2 .= " AND a.idambientes IN ($idambientesf)";
				}
			}	
			if(!empty($data->tipoinc)){ 
				//$where2 .= " AND ca.tipo = 'correctivos' "; 
				$hayFiltrosC = 1;
			}	
			if(!empty($data->tipoprev)){ 
				//$where2 .= " AND  ca.tipo = 'preventivos' ";
				$hayFiltrosP = 1;
			}
			if(!empty($data->tiposol)){ 
				//$where2 .= " AND  a.tipo = 'flotas' ";
				$hayFiltrosF = 1;
			}
			
			$vowels = array("[", "]");
			$where2 = str_replace($vowels, "", $where2);
		}
		$nivel 		= $_SESSION['nivel'];
		$idusuario 	= $_SESSION['user_id'];
		$usuario 	= $_SESSION['usuario'];
		
		$queryj = "SELECT idempresas, idclientes, idproyectos, idambientes, iddepartamentos from usuarios where id = ".$idusuario;
		$resultj = $mysqli->query($queryj); 
		$row = $resultj->fetch_assoc();
		$idempresas  = $row['idempresas'];
		$idclientes  = $row['idclientes'];
		$idproyectos = $row['idproyectos'];
		$idambientes = $row['idambientes'];
		$iddepartamentos = $row['iddepartamentos'];
		$query 			= "";
		 
		if(($hayFiltrosC == 1 || $hayFiltrosP == 1) || ($hayFiltrosC == 0 && $hayFiltrosP == 0 && $hayFiltrosF == 0)){
			$query 	.= " SELECT a.id, a.titulo, u.nombre AS responsable, am.nombre AS ambiente, a.tipo,
						CONCAT(IFNULL(DATE(a.fechareal),a.fechacreacion),' ',IFNULL(TIME(a.horareal), IFNULL(a.horacreacion,CURTIME()) ) ) AS fecha1,
						DATE_ADD(CONCAT(IFNULL(DATE(a.fechareal),a.fechacreacion),' ',IFNULL(TIME(a.horareal), IFNULL(a.horacreacion,CURTIME()) ) ), INTERVAL 240 MINUTE) AS fecha2
						FROM incidentes a
						LEFT JOIN usuarios u ON a.asignadoa = u.correo 
						LEFT JOIN usuarios us ON a.solicitante = us.correo 
						LEFT JOIN ambientes am ON a.idambientes = am.id
						LEFT JOIN categorias ca ON a.idcategorias = ca.id
						LEFT JOIN usuarios j ON a.solicitante = j.correo
						LEFT JOIN usuarios l ON a.asignadoa = l.correo
						WHERE 1 ";
		if($hayFiltrosC == 1 && $hayFiltrosP === 0) $query .= "AND a.tipo = 'incidentes'";
			if($hayFiltrosP == 1 && $hayFiltrosC === 0) $query .= "AND a.tipo = 'preventivos'";
		}  

		//Estado Agendado
		$query .="  AND a.idestados >= 12
					AND IFNULL(DATE(a.fechareal),a.fechacreacion) >= '$start' AND IFNULL(DATE(a.fechareal),a.fechacreacion)  <= '$end' ";
		//ESTADOS: 16-Resuelto, 17-Cerrado			
		
		$query .= permisos('calendario', '',$idusuario);
		/*
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idempresas in ($idempresas) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idclientes in ($idclientes) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idproyectos in ($idproyectos) ";
		}
		if($nivel == 3) {
			$query  .= "AND u.usuario = '".$usuario."' ";
		} elseif($nivel == 4){
			if($idambientes != ''){
				$idambientes = explode(',',$idambientes);
				$idambientes = implode("','", $idambientes);
				$query  .= "AND (us.usuario = '".$_SESSION['usuario']."' OR a.idambientes IN ('".$idambientes."') OR a.idclientes in ($idclientes) ) ";
			}else{
				if($iddepartamentos != ''){
					$query  .= "AND FIND_IN_SET(a.iddepartamentos,'".$iddepartamentos."')  ";
				}else{
					$query  .= " OR us.usuario = '".$usuario."' ";
				}
			}
		}
		*/
		$query  .= " $where2";
		//debugL("QUERYCAL:".$query,"CARGAR EVENTOSCALENDARIO");
		$result = $mysqli->query($query);
		$count = $result->num_rows;
		if($count > 0){
			//$row = $result->fetch_assoc();
			while($row = $result->fetch_assoc()){
				//debugL($row['id']);
				$tipo = $row['tipo'];
				if ($tipo == 'incidentes') {
					$className  = 'eventIncidente';
					$color="#369DC9"; 
				}elseif($tipo == 'preventivos') {
					$className = 'eventPreventivo';
				    $color="#36C95F"; 
				}elseif($tipo == 'postventas'){ 
					$className = 'eventPostventa';
				    $color="#D2813D"; 
				}elseif($tipo == 'flotas'){ 
					$className = 'eventFlotas';
				    $color="#6610f2"; 
				}
				$event_array[] = array(
					'id' 		=> $row['id'],
					'title'		=> $row['titulo'].' - '.$row['responsable'].' - '.$row['ambiente'],
					'start' 	=> $row['fecha1'],
					'end' 		=> $row['fecha2'],
					'backgroundColor'=>$color,
					'tipo'=>$tipo
				);
			}
		}
		//debugL('CAL2:'.json_encode($event_array));
		echo json_encode($event_array);	
	}
	
	function getCategoria(){
		global $mysqli;
		$id = (!empty($_GET['idincidente']) ? $_GET['idincidente'] : 0);
		$resultado 	 = array();
		
		$query = " SELECT idcategoria FROM incidentes WHERE id = '$id'";
				   
		$result = $mysqli->query($query);
		if($row = $result->fetch_assoc()){
			$resultado = $row['idcategoria'];
		}		
		echo $resultado;
	}
	
	function guardarfiltros() {
		global $mysqli;
		$data = $_REQUEST['data'];
		$usuario = $_SESSION['usuario'];
		$query  = " SELECT * FROM usuariosfiltros
					WHERE modulo = 'Calendario' AND usuario = '".$usuario."' ";

		$result = $mysqli->query($query);
		$count = $result->num_rows;
		
		if( $count > 0 ) 
			$query = "UPDATE usuariosfiltros SET filtrosmasivos = '$data' WHERE modulo = 'Calendario' AND usuario = '".$usuario."' ";
		else
			$query = "INSERT INTO usuariosfiltros VALUES (null, '".$usuario."', 'Calendario', '', '$data')";
		if($mysqli->query($query))
			echo true;		
	} 
	
	function abrirfiltros() {
		global $mysqli;
		$usuario = $_SESSION['usuario'];
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Calendario' AND usuario = '".$usuario."' ";
		$result = $mysqli->query($query);
		$response = new StdClass;
		if($result->num_rows >0){
			$row = $result->fetch_assoc();				
			$data = $row['filtrosmasivos'];
			$response->data = $data;
		} else {
			$response->data = '';
		}
		
		$response->success = true;
		echo json_encode($response);
	}
	
	function verificarfiltros() {
		global $mysqli;
		$usuario = $_SESSION['usuario'];
		$query = "SELECT filtrosmasivos FROM usuariosfiltros 
				  WHERE modulo = 'Calendario' 
				  AND usuario = '".$usuario."' ";
		$result = $mysqli->query($query);
		$response = 0;
		if($result->num_rows >0){
			$row = $result->fetch_assoc();				
			$data = $row['filtrosmasivos'];
			$filtrosmasivos = json_decode($data);
			foreach($filtrosmasivos as $clave => $valor){
				if($valor != '' || $valor != 0){
					$response = 1;
				}
			}
		} else {
			$response = 0;
		}
		echo $response;
	}
	
	function limpiarFiltrosMasivos(){
		global $mysqli;
		$usuario = $_SESSION['usuario'];
		
		$query = "DELETE FROM usuariosfiltros WHERE modulo = 'Calendario' AND usuario = '".$usuario."' ";
		if($mysqli->query($query))
			echo true;		
	}
?>