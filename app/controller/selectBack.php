<?php
	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	header('content-type: application/json; charset=utf-8');
	header('Content-Type: application/JSON');
	include("../../conexion.php");	
	
	$method = $_SERVER['REQUEST_METHOD'];        
	if ($method =='GET'){
		$action = $_REQUEST['action'];   
		switch($action){
			case "clientes":
					clientes();
					break;
			case "proyectos":
					proyectos();
					break;
			case "categorias":
					categorias();
					break;
			case "ambientes":
					ambientes();
					break;
			case "prioridades":
					prioridades();
					break;
			case "estados":
					estados();
					break;
			case "departamentosGrupos":
					departamentosGrupos();
					break;
			case "asignadoa":
					asignadoa();
					break;
			default:
					echo "{failure:true}";
					break;
		}
	}
	
	function clientes(){
	    
		global $mysqli;
		$idusuario = $_REQUEST['idusuario'];
		$json = array();
		
		$query  = " SELECT a.id, a.nombre FROM clientes a ";
		//$query .= permisos('combos', 'clientes', $idusuario);
		
		$query  .= " ORDER BY a.nombre ASC ";
		debug($query);

		$result = $mysqli->query($query);		
		if($result->num_rows > 0 ){			
			while($row = $result->fetch_assoc()){
			   $json[] = array(
							'value'	=> $row['id'],
							'name'	=> $row['nombre']
						);
			}			
		}
		echo json_encode($json);
	}
	
	function proyectos() {
		global $mysqli;
		$idusuario = $_REQUEST['idusuario'];
		$json = array();
		
		$query  = " SELECT a.id, a.nombre, b.siglas,a.idclientes
					FROM proyectos a
					LEFT JOIN clientes b ON a.idclientes = b.id ";
		//$query .= permisos('combos', 'proyectos', $idusuario);		
		//debug($query);
		$query  .= " ORDER BY a.nombre ASC ";
		$result = $mysqli->query($query);
		
		if($result->num_rows > 0 ){			
			while($row = $result->fetch_assoc()){
				$json[]= array(
							'value'	=> $row['id'],
							'name'	=> $row['nombre'],
							'siglas'=> $row['siglas'],
							'idclientes'=> $row['idclientes'],
							
						);
			}
			echo json_encode($json);
		}
	}
	
function categorias(){
		global $mysqli;
		$combo = ''; 
		if(isset($_REQUEST['tipo'])){ $tipo = $_REQUEST['tipo']; }else{ $tipo = ''; } 
		if(isset($_REQUEST['idproyectos'])){ $idproyectos = $_REQUEST['idproyectos']; }else{ $idproyectos = ''; } 
		$nivel =(!empty($_REQUEST['nivel'])? intval($_REQUEST['nivel']):'');
		 
		$query  = " SELECT a.id, a.nombre 
					FROM categorias a 
					INNER JOIN categoriaspuente b ON b.idcategorias = a.id  					
					WHERE 1=1 ";
		if($nivel == 1 && $nivel == 2){
			if($idproyectos != '' && $idproyectos != 'undefined'){
				if(is_array($idproyectos)){ $idproyectos = implode(',',$idproyectos); }
				$query  .= " AND b.idproyectos in ($idproyectos)  ";
			}  
			
		}else{
			 if($idproyectos != '' && $idproyectos != 'undefined'){
				if(is_array($idproyectos)){ $idproyectos = implode(',',$idproyectos); }
				$query  .= " AND b.idproyectos in ($idproyectos) AND a.id !=138 ";  
			}
		}
		$query  .= " AND b.tipo LIKE '%".$tipo."%' ";
		$query  .= " ORDER BY a.nombre ASC ";
		$result = $mysqli->query($query);
		$json = array();
		while($row = $result->fetch_assoc()){ 
			$json[]= array(
							'value'	=>$row['id'],
							'name'	=>$row['nombre'] 
						);
		}
		 
		echo json_encode($json);	
	} 
	
	function ambientes(){
		global $mysqli;
		$idusuario	=$_REQUEST['idusuario'];
		$nivel		=(!empty($_REQUEST['nivel'])? intval($_REQUEST['nivel']):'');
		$idcliente 	= (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyecto	= (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$json = array();
		
		$query  = " SELECT a.id, a.nombre FROM ambientes a
					INNER JOIN ambientespuente b ON b.idambientes = a.id
					WHERE 1 = 1 ";
		//$query .= permisos('combos', 'ambientes', $idusuario);
		if($idcliente != ''){
			$query  .= " AND find_in_set(".$idcliente.",b.idclientes) ";
		}
		if($idproyecto != ''){
			$query  .= " AND find_in_set(".$idproyecto.",b.idproyectos) ";
		}
		$query .= " GROUP BY a.id ORDER BY a.nombre ASC ";
		
		$result = $mysqli->query($query);
		if($result->num_rows > 0 ){				
			while($row = $result->fetch_assoc()){
				$json[]= array(
							'value'	=> $row['id'],
							'name'	=> $row['nombre']
						);
			}			
		}
		echo json_encode($json);
	}
	
	function prioridades(){
		global $mysqli;
		$idusuario	=$_REQUEST['idusuario'];
		$idclientes	 = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0);
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : 0);
		$json = array();
		
		$query  = " SELECT a.id, a.prioridad FROM sla a INNER JOIN slapuente b ON b.idprioridades = a.id WHERE b.idclientes = ".$idclientes." AND b.idproyectos = ".$idproyectos."";
		//$query .= permisos('combos', 'prioridades', $idusuario);	
		$query .= " ORDER BY prioridad ASC ";
		//echo $query;
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			$json[]= array(
						'value'=>$row['id'],
						'name'=>$row['prioridad']
					);
		}
		echo json_encode($json);			
	}
	
	function estados(){    
		global $mysqli;
		$idusuario  = $_REQUEST['idusuario'];
		$tipo		= (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : 'Correctivo'); 
		if(isset($_REQUEST['idproyectos'])){ $idproyectos = $_REQUEST['idproyectos']; }else{ $idproyectos = ''; }
		//$tipo		= 'Correctivo'; 
		$json = array();
		
		$query  = " SELECT a.id, a.nombre 
					FROM estados a 
					INNER JOIN estadospuente b ON b.idestados = a.id  					
					WHERE 1 = 1 AND FIND_IN_SET('".$tipo."',b.tipo) ";
		//$query .= permisos('combos', 'estados', $idusuario);
		if($idproyectos != '' && $idproyectos != 'undefined'){
			if(is_array($idproyectos)){ $idproyectos = implode(',',$idproyectos); }
			$query  .= " AND b.idproyectos in ($idproyectos) ";
		}
		$query .= " ORDER BY a.nombre ASC ";
		debugL($query,"debugEstados");
		$result = $mysqli->query($query);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){ 				
				$json[] = array(
								'value'	=> $row['id'],
								'name'	=> $row['nombre']
							);
			}
		}
	    
		echo json_encode($json);
	}
	
	function departamentosGrupos(){
		global $mysqli;
		//$nombre,
		$departamento= $_REQUEST['iddepartamento'];
		$idempresas	='';
		$nivel 		= $_REQUEST['nivel'];
		$usuario 	= $_REQUEST['usuario'];
		$json  = array();
		$jsond = array();
		$jsong = array();
		if(isset($_REQUEST['idproyectos'])){ $idproyectos = $_REQUEST['idproyectos']; }else{ $idproyectos = ''; } 
		
		if($nivel == 1 || $nivel == 2){
			//DEPARTAMENTOS
			$query  = " SELECT a.id, a.nombre
						FROM departamentos a 
						INNER JOIN departamentospuente b ON b.iddepartamentos = a.id  					
						WHERE 1 = 1 ";		
			if($idempresas != ""){
				$query .= " AND b.idempresas IN (".$idempresas.")";
			}
			if($idproyectos != '' && $idproyectos != 'undefined'){
				if(is_array($idproyectos)){ $idproyectos = implode(',',$idproyectos); }
				$query  .= " AND b.idproyectos in ($idproyectos) ";
			}
			$query .= " AND a.tipo = 'departamento' ";
			$query  .= " ORDER BY a.nombre ASC";
			//echo $query;
			$result = $mysqli->query($query);
			
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){ 
					$jsond[] = array(
									'value'	=> $row['id'],
									'name'	=> $row['nombre']
								);
				}
			}
				
			//GRUPOS
			$query  = " SELECT a.id, a.nombre
						FROM departamentos a 
						INNER JOIN departamentospuente b ON b.iddepartamentos = a.id  					
						WHERE 1 = 1 ";		
			if($idempresas !=""){
				$query .= " AND b.idempresas IN (".$idempresas.")";
			}
			$query .= " AND a.tipo = 'grupo' ";
			$query .= " GROUP BY nombre ORDER BY nombre ASC ";	
			//echo $query;			
			$result = $mysqli->query($query);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){ 
					$jsong[] = array(
									'value'	=> $row['id'],
									'name'	=> $row['nombre']
								);			
				}
			}
		}else{
			//DEPARTAMENTOS
			$query  = " SELECT a.id, a.nombre 
						FROM departamentos a
						INNER JOIN departamentospuente b ON b.iddepartamentos = a.id
						INNER JOIN usuarios c ON FIND_IN_SET(a.id, c.iddepartamentos)					
						WHERE c.usuario = '".$usuario."' ";		
			if($idempresas !=""){
				$query .= " AND FIND_IN_SET(b.idempresas, ".$idempresas.") ";
			}
			$query .= " AND a.tipo = 'departamento' ";
			$query  .= " ORDER BY a.nombre ASC ";
			$result = $mysqli->query($query);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){ 
					$jsond[] =	array(
									'value'	=> $row['id'],
									'name'	=> $row['nombre']
								);
				}
			}
			//GRUPOS
			$query  = " SELECT a.id, a.nombre 
						FROM departamentos a
						INNER JOIN departamentospuente b ON b.iddepartamentos = a.id
						INNER JOIN usuarios c ON FIND_IN_SET(a.id, c.iddepartamentos)						
						WHERE c.usuario = '".$usuario."' ";		
			if($idempresas !=""){
				$query .= " AND FIND_IN_SET(a.idempresas, ".$idempresas.") ";
			}
			$query .= " AND a.tipo = 'grupo' ";
			$query .= " GROUP BY nombre ORDER BY a.nombre ASC ";
			$result = $mysqli->query($query);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){ 
					$jsong[] = array(
									'value'	=> $row['id'],
									'name'	=> $row['nombre']
								);
				}
			}
		}
		$json = array_merge($jsond, $jsong);
		echo json_encode($json);
	} 
	
	function asignadoa(){
		global $mysqli;
		$idusuario = $_REQUEST['idusuario'];
		$iddepartamentos = (!empty($_REQUEST['iddepartamentos'])?$_REQUEST['iddepartamentos']:'');		
		$json = array();
        
		$query  = " SELECT id, correo, nombre, estado,iddepartamentos FROM usuarios WHERE nombre != '' ";
		if($iddepartamentos !=''){
			$query .= " AND FIND_IN_SET('".$iddepartamentos."',iddepartamentos) ";
		}
		$query .=" ORDER BY nombre ASC ";
		//debug('comboxdepartamentosgrupos: '.$query);
		$result = $mysqli->query($query);		
		while($row = $result->fetch_assoc()){
			$json[]= array(
						'value'	=>$row['correo'],
						'name'	=>$row['nombre'],
						'estado'=>$row['estado'],
						'iddepartamentos'=>$row['iddepartamentos']
					);
		}
		echo json_encode($json);
    }
?>