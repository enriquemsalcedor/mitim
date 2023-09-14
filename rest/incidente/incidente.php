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
		/*--datos que debo enviar del storage--*/
		$idusuario 		= $_REQUEST['idusuario'];
		//$sectionInfiniteScroll = $_REQUEST['idInfinitiScroll'];
		
		/*
		$usuario 		= $_REQUEST['usuario'];
		$nivel 			= $_REQUEST['nivel'];
		$idempresas 	= $_REQUEST['idempresas'];
		$iddepartamentos= $_REQUEST['iddepartamentos'];
		$idclientes 	= $_REQUEST['idclientes'];
		$idproyectos 	= $_REQUEST['idproyectos'];	
		$idambientes	= $_REQUEST['sitio'];	
		*/		
		/*------------------------------------- */    
		global $mysqli;
		$start	= 0;
		$limit	= 50;

		$numero			= (!empty($_REQUEST['numero']) ? $_REQUEST['numero'] : '');
		$desde  		= (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '');
		$hasta  		= (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : '');
		$idambientes  	= (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : '');
		$idproyectos	= (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idcategorias  	= (!empty($_REQUEST['idcategorias']) ? $_REQUEST['idcategorias'] : '');
		$idestados  	= (!empty($_REQUEST['idestados']) ? $_REQUEST['idestados'] : '');
		$asignadoa  	= (!empty($_REQUEST['asignadoa']) ? $_REQUEST['asignadoa'] : '');
		$modalidad  	= (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '');
		$idactivos  	= (!empty($_REQUEST['idactivos']) ? $_REQUEST['idactivos'] : '');
	    $solicitante  	= (!empty($_REQUEST['solicitante']) ? $_REQUEST['solicitante'] : '');
		
		$where = '';
		if($numero != ''){
			$where .= " AND a.id = $numero ";
		}
		if($desde != ''){
			$where .= " AND a.fechacreacion >= '$desde' ";
		}
		if($hasta != ''){
			$where .= " AND a.fechacreacion <= '$hasta' ";
		}
		if($idambientes != ''){
			$where .= " AND a.idambientes = '$idambientes' ";
		}
		if($idproyectos != ''){
			$where .= " AND a.idproyectos = $idproyectos ";
		}
		if($idcategorias!=''){
			$where .= " AND a.idcategorias = $idcategorias ";
		}
		if($idestados != ''){
			$where .= " AND a.idestados = $idestados ";
		}
		if($asignadoa != ''){
			$where .= " AND a.asignadoa = '$asignadoa' ";
		}
		if($modalidad != ''){
			$where .= " AND m.modalidad = '$modalidad' ";
		}
		if($idactivos != ''){
			$where .= " AND a.idactivos = '$idactivos' ";
		}
		if($solicitante != ''){
			$where .= " AND a.solicitante = '$solicitante'";
		}
		
		$query  = " SELECT a.id, e.nombre AS estado, LEFT(a.titulo,45) as titulo, a.titulo as titulott,
					IFNULL(j.nombre, a.solicitante) AS solicitante, a.fechacreacion, a.horacreacion, a.fechacierre,
					f.nombre AS categoria, g.nombre AS subcategoria, a.asignadoa, l.nombre AS nomusuario, 
					c.nombre AS ambiente, m.serie, mar.nombre as marca, r.nombre as modelo, m.modalidad, h.prioridad, a.fecharesolucion, 
					case when a.fechacierre IS NULL OR LENGTH(ltrim(rTrim(a.fechacierre))) > 0
					then a.fechacreacion else a.fechacierre end as fechaorden,
					n.descripcion as idempresas, o.nombre as iddepartamentos, p.nombre as idclientes, a.estadoant
					FROM incidentes2 a
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
					";
		
		$query .= " WHERE a.tipo = 'incidentes' ";
		$query .= permisos('correctivos', '', $idusuario);
		$query  .= " $where ";
		$query  .= " AND (a.fechacreacion > DATE_SUB(CURDATE(),INTERVAL 1 YEAR) OR a.idestados <> 17 OR a.idestados <> 16) ";
		$query  .= " GROUP BY a.id ";
		$query  .= " ORDER BY a.id desc ";
		$query .= " LIMIT ".$start.", ".$limit;
		debug($query);
		$result = $mysqli->query($query);
		if($result->num_rows > 0 ){
			$json=array();
		 	while($row = $result->fetch_assoc()){
				$siglasestado = strtoupper(substr(str_replace(' ','',$row['estado']), 0, 2));
				$json[]=array(
					'id' => $row['id'],
					'titulo'=> $row['titulo'],
					'fechacreacion' => $row['fechacreacion'],
					//'unidadejecutora' => $row['unidadejecutora'],
					'Siglas' => $siglasestado
				);	
		 	}
			echo json_encode($json);
		}	else{

			echo json_encode([]);
		}
	/*---------------------------------------*/
	}else{      
		$response['status'] = "fail-Incident";
		$response['cod'] = '004';    
		echo json_encode($response);
	}
?>