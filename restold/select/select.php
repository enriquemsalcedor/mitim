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
			case "ambientes":
					ambientes();
					break;
			case "categorias":
					categorias();
					break;
			case "proyectos":
					proyectos();
					break;
			case "clientes":
					clientes();
					break;
			case "estados":
					estados();
					break;
			case "usuariosDep":
					usuariosDep();
					break;
			case "prioridades":			
					prioridades();
					break;
			case "asigandoa":
			        usuarioDepartamento();
			        break;
			case "dptgrupos":
					departamentosgrupos();
					break;
			case "solicitante":
					solicitante();
					break;
			case "comboxproyectos":
					comboxproyectos();
					break;
			case "comboxcategoria":			
					comboxcategoria();
					break;
			case "comboxusarioDep":
					comboxdepartamentosgrupos();
					break;
			case "comboxusarioDeps":
					comboxdepartamentosgruposs();
					break;
			case "comboxmodalidades":
					comboxmodalidades();
					break;
			case "comboxactivo":
					comboxactivo();
					break;
			case "comboxseriesel":
					seriesel();
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
		$query .= permisos('combos', 'clientes', $idusuario);
		
		$query  .= " ORDER BY a.nombre ASC ";
		//debug($query);

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
		$query .= permisos('combos', 'proyectos', $idusuario);		
		debug($query);
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
		$idusuario = $_REQUEST['idusuario'];
		if(isset($_REQUEST['tipo'])){ $tipo = $_REQUEST['tipo']; }else{ $tipo = 'incidente'; }
		if(isset($_REQUEST['siglasproy'])){ $siglasproy = $_REQUEST['siglasproy']; }else{ $siglasproy = 'no'; }
		//$idproyecto = (!empty($_REQUEST['idproyectos'])?$_REQUEST['idproyectos']:'');
		
		$json = array();
		
		if($siglasproy == 'si'){
			$query  = " SELECT a.id, a.nombre, b.codigo 
						FROM categorias a 
						LEFT JOIN proyectos b ON a.idproyecto = b.id
						WHERE a.id != 0 ";
			//$query .= permisos('combos', 'categorias', $idusuario);
			if ($idproyecto !="")
		    {
		        $query.="AND a.idproyecto=".$idproyecto." ";
		    }
			if($tipo == 'incidente'){
				$query  .= " AND a.tipo = 'correctivos'  ";
			}elseif($tipo == 'preventivo'){
				$query  .= " AND a.tipo = 'preventivos'  "; 
			}elseif($tipo == 'postventas'){
				$query  .= " AND a.tipo = 'postventas'  ";
			}
			$query  .= " ORDER BY a.nombre ASC ";
			$result = $mysqli->query($query);
			
			if($result->num_rows > 0 ){	
				while($row = $result->fetch_assoc()){
					$json[]= array(
								'value'	=>$row[', a.idproyectoid'],
								'name'	=>$row['nombre'],
								'codigo'=>$row['codigo']
							);
				}				
			}
		}else{
			$query  = " SELECT a.id, a.nombre, a.idproyecto FROM categorias a WHERE a.id != 0 ";	
			//$query .= permisos('combos', 'categorias', $idusuario);
			
			/*if ($idproyecto !="")
		    {
		        $query.="AND a.idproyecto=".$idproyecto." ";
		    }*/
			if($tipo == 'incidente'){
				$query  .= " AND a.tipo = 'correctivos'  ";
			}elseif($tipo == 'preventivo'){
				$query  .= " AND a.tipo = 'preventivos'  "; 
			}elseif($tipo == 'postventas'){
				$query  .= " AND a.tipo = 'postventas'  ";
			}
			$query  .= " ORDER BY nombre ASC ";
			$result = $mysqli->query($query);
			//debug('CAT'.$query);
			//debug($query);
			if($result->num_rows > 0 ){
				while($row = $result->fetch_assoc()){
					$json[]= array(
								'value'	=>$row['id'],
								'name'	=>$row['nombre'],
								'idproyecto'=>$row['idproyecto']
							);
				}
			}
		}
		echo json_encode($json);
	}

/*
	function categorias(){
		global $mysqli;
		$idusuario = $_REQUEST['idusuario'];
		if(isset($_REQUEST['tipo'])){ $tipo = $_REQUEST['tipo']; }else{ $tipo = 'incidente'; }
		if(isset($_REQUEST['siglasproy'])){ $siglasproy = $_REQUEST['siglasproy']; }else{ $siglasproy = 'no'; }
		$json = array();
		
		if($siglasproy == 'si'){
			$query  = " SELECT a.id, a.nombre, b.codigo ,a.idproyecto
						FROM categorias a 
						LEFT JOIN proyectos b ON a.idproyecto = b.id
						WHERE a.id != 0 ";
						
			$query .= permisos('combos', 'categorias', $idusuario);
			if($tipo == 'incidente'){
				$query  .= " AND a.tipo = 'correctivos'  ";
			}elseif($tipo == 'preventivo'){
				$query  .= " AND a.tipo = 'preventivos'  "; 
			}elseif($tipo == 'postventas'){
				$query  .= " AND a.tipo = 'postventas'  ";
			}
			$query  .= " ORDER BY a.nombre ASC ";
			$result = $mysqli->query($query);
			//debug($query);
			if($result->num_rows > 0 ){	
				while($row = $result->fetch_assoc()){
					$json[]= array(
								'value'	=>$row['id'],
								'name'	=>$row['nombre'],
								'codigo'=>$row['codigo'],
								'idproyecto'=>"error"
							);
				}				
			}
		}else{
			$query  = " SELECT a.id, a.nombre FROM categorias a WHERE a.id != 0 ";	
			$query .= permisos('combos', 'categorias', $idusuario);
			if($tipo == 'incidente'){
				$query  .= " AND a.tipo = 'correctivos'  ";
			}elseif($tipo == 'preventivo'){
				$query  .= " AND a.tipo = 'preventivos'  "; 
			}elseif($tipo == 'postventas'){
				$query  .= " AND a.tipo = 'postventas'  ";
			}
			$query  .= " ORDER BY nombre ASC ";
			$result = $mysqli->query($query);
			//debug($query);
			if($result->num_rows > 0 ){
				while($row = $result->fetch_assoc()){
					$json[]= array(
								'value'	=>$row['id'],
								'name'	=>$row['nombre'],
								'codigo'=>''
							);
				}
			}
		}
		echo json_encode($json);
	}
*/	
	function prioridades(){
		global $mysqli;
		$idusuario	=$_REQUEST['idusuario'];
		$json = array();
		
		$query  = " SELECT id, prioridad FROM sla WHERE id != 0 ";
		$query .= permisos('combos', 'prioridades', $idusuario);	
		$query .= " ORDER BY prioridad ASC ";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			$json[]= array(
						'value'=>$row['id'],
						'name'=>$row['prioridad']
					);
		}
		echo json_encode($json);			
	}

	function ambientes(){
		global $mysqli;
		$idusuario	=$_REQUEST['idusuario'];
		$json = array();
		
		$query  = " SELECT a.id, a.nombre AS nombre FROM ambientes a ";
		$query .= permisos('combos', 'ambientes', $idusuario);
		
		$query .= " GROUP BY a.id ORDER BY a.nombre ASC ";
		//debug($query);
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

	function usuariosDep(){
		global $mysqli;
		$idusuario = $_REQUEST['idusuario'];
		$json = array();

		$query  = " SELECT id, correo, nombre, estado FROM usuarios WHERE nombre != ''  ";
	    $query .= permisos('combos', 'usuariosDep', $idusuario);	
		$query .=" ORDER BY nombre ASC ";
		//debug($query);
		$result = $mysqli->query($query);		
		while($row = $result->fetch_assoc()){
			$json[]= array(
						'value'=>$row['correo'],
						'name'=>$row['nombre'],
						'estado'=>$row['estado']
					);
		}
		echo json_encode($json);
	}
	
	function solicitante(){
		global $mysqli;
		$json = array();

		$query  = " SELECT id, correo, nombre, estado FROM usuarios WHERE nombre != ''  ";
	 	$query .=" ORDER BY nombre ASC ";
		
		$result = $mysqli->query($query);		
		while($row = $result->fetch_assoc()){
			$json[]= array(
						'value'=>$row['correo'],
						'name'=>$row['nombre'],
						'estado'=>$row['estado']
					);
		}
		echo json_encode($json);
	}
	
	function departamentosgrupos(){
		global $mysqli;
		//$nombre,
		$departamento= $_REQUEST['iddepartamento'];
		$idempresas	='';
		$nivel 		= $_REQUEST['nivel'];
		$usuario 	= $_REQUEST['usuario'];
		$json  = array();
		$jsond = array();
		$jsong = array();
			
		if($nivel == 1 || $nivel == 2){
			//DEPARTAMENTOS
			$query  = " SELECT id, nombre FROM departamentos WHERE 1 = 1 ";		
			if($idempresas != ""){
				$query .= " AND idempresas IN (".$idempresas.")";
			}
			$query .= " AND tipo = 'departamento' ";
			$query  .= " ORDER BY nombre ASC ";
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
			$query  = " SELECT id, nombre FROM departamentos WHERE 1 = 1 ";		
			if($idempresas !=""){
				$query .= " AND idempresas IN (".$idempresas.")";
			}
			$query .= " AND tipo = 'grupo' ";
			$query .= " ORDER BY nombre ASC ";		
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
						LEFT JOIN usuarios b ON FIND_IN_SET(a.id, b.iddepartamentos)					
						WHERE usuario = '".$usuario."' ";		
			if($idempresas !=""){
				$query .= " AND FIND_IN_SET(a.idempresas, ".$idempresas.") ";
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
						LEFT JOIN usuarios b ON FIND_IN_SET(a.id, b.iddepartamentos)					
						WHERE usuario = '".$usuario."' ";		
			if($idempresas !=""){
				$query .= " AND FIND_IN_SET(a.idempresas, ".$idempresas.") ";
			}
			$query .= " AND a.tipo = 'grupo' ";
			$query .= " ORDER BY a.nombre ASC ";
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
	
	function estados(){    
		global $mysqli;
		$idusuario  = $_REQUEST['idusuario'];
		//$tipo		= (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : 'incidente'); 
		$tipo		= 'incidente'; 
		$json = array();
		
		$query  = " SELECT a.id, a.nombre FROM estados a WHERE 1 = 1 AND a.tipo = '".$tipo."' AND a.id <> 17 ";
		$query .= permisos('combos', 'estados', $idusuario);
		$query .= " ORDER BY a.nombre ASC ";
		//debugL($query);
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
	
	function comboxproyectos() {
		global $mysqli;
		$idusuario = $_REQUEST['idusuario'];
		$idclientes = (!empty($_REQUEST['idclientes'])?$_REQUEST['idclientes']:'');
		$json = array();
		
		$query  = " SELECT a.id, a.nombre, b.siglas,a.idclientes 
					FROM proyectos a
					LEFT JOIN clientes b ON a.idclientes = b.id ";
		
		$query .= permisos('combos', 'proyectos', $idusuario);		
		if ($idclientes !="")
		{
		    $query.="AND a.idclientes=".$idclientes." ";
		}
		
		//debug($query);
		$query  .= " ORDER BY a.nombre ASC ";
		$result = $mysqli->query($query);
		
		if($result->num_rows > 0 ){			
			while($row = $result->fetch_assoc()){
				$json[]= array(
							'value'	=> $row['id'],
							'name'	=> $row['nombre'],
							'siglas'=> $row['siglas'],
							'idcliente'=> $row['idclientes']
						);
			}			
		}
		echo json_encode($json);
	}	
	
	function comboxcategoria(){
		global $mysqli;
		$idusuario = $_REQUEST['idusuario'];
		if(isset($_REQUEST['tipo'])){ $tipo = $_REQUEST['tipo']; }else{ $tipo = 'incidente'; }
		
		if(isset($_REQUEST['siglasproy'])){ $siglasproy = $_REQUEST['siglasproy']; }else{ $siglasproy = 'no'; }
		$idproyecto = (!empty($_REQUEST['idproyectos'])?$_REQUEST['idproyectos']:'');
		
		$json = array();
		
		if($siglasproy == 'si'){
			$query  = " SELECT a.id, a.nombre, b.codigo 
						FROM categorias a 
						LEFT JOIN proyectos b ON a.idproyecto = b.id
						WHERE a.id != 0 ";
			//$query .= permisos('combos', 'categorias', $idusuario);
			if ($idproyecto !="")
		    {
		        $query.="AND a.idproyecto=".$idproyecto." ";
		    }
			if($tipo == 'incidente'){
				$query  .= " AND a.tipo = 'correctivos'  ";
			}elseif($tipo == 'preventivo'){
				$query  .= " AND a.tipo = 'preventivos'  "; 
			}elseif($tipo == 'postventas'){
				$query  .= " AND a.tipo = 'postventas'  ";
			}
			$query  .= " ORDER BY a.nombre ASC ";
			$result = $mysqli->query($query);
			
			if($result->num_rows > 0 ){	
				while($row = $result->fetch_assoc()){
					$json[]= array(
								'value'	=>$row['id'],
								'name'	=>$row['nombre'],
								'codigo'=>$row['codigo']
							);
				}				
			}
		}else{
			$query  = " SELECT a.id, a.nombre FROM categorias a WHERE a.id != 0 ";	
			//$query .= permisos('combos', 'categorias', $idusuario);
			if ($idproyecto !="")
		    {
		        $query.="AND a.idproyecto=".$idproyecto." ";
		    }
			if($tipo == 'incidente'){
				$query  .= " AND a.tipo = 'correctivos'  ";
			}elseif($tipo == 'preventivo'){
				$query  .= " AND a.tipo = 'preventivos'  "; 
			}elseif($tipo == 'postventas'){
				$query  .= " AND a.tipo = 'postventas'  ";
			}
			$query  .= " ORDER BY nombre ASC ";
			$result = $mysqli->query($query);
			//debug('CAT'.$query);
			//debug($query);
			if($result->num_rows > 0 ){
				while($row = $result->fetch_assoc()){
					$json[]= array(
								'value'	=>$row['id'],
								'name'	=>$row['nombre'],
								'codigo'=>''
							);
				}
			}
		}
		echo json_encode($json);
	}
	
	
	function comboxdepartamentosgrupos(){
		global $mysqli;
		$idusuario = $_REQUEST['idusuario'];
		$iddepartamentos = (!empty($_REQUEST['iddepartamentos'])?$_REQUEST['iddepartamentos']:'');		
		$json = array();
        
		$query  = " SELECT id, correo, nombre, estado,iddepartamentos FROM usuarios WHERE nombre != '' ";
		if($iddepartamentos !=''){
			$query .= " AND FIND_IN_SET('".$iddepartamentos."',iddepartamentos) ";
		}
		$query .=" ORDER BY nombre ASC ";
		debug('comboxdepartamentosgrupos: '.$query);
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
       
	function usuarioDepartamento(){
		global $mysqli;
		$idusuario = $_REQUEST['idusuario'];
		$iddepartamento = (!empty($_REQUEST['iddepartamentos'])?$_REQUEST['iddepartamentos']:'');
		
		$json = array();
        
		$query  = " SELECT id, correo, nombre, estado,iddepartamentos FROM usuarios WHERE nombre != ''  ";
	    //$query .= " AND NOT FIND_IN_SET(0,iddepartamentos) ";
	    //$query .= " AND NOT FIND_IN_SET('',iddepartamentos) ";
	    /*$query .= permisos('combos', 'usuariosDep', $idusuario);	
		if ($iddepartamento !="")
		    {
		     $query.="AND iddepartamentos=".$iddepartamento." ";
		    }*/
		$query .=" ORDER BY nombre ASC ";
		debug($query);
		//debugL($query);
		$result = $mysqli->query($query);		
		while($row = $result->fetch_assoc()){
			$json[]= array(
						'value'=>$row['correo'],
						'name'=>$row['nombre'],
						'estado'=>$row['estado'],
						'iddepartamentos'=>$row['iddepartamentos']
					);
		}
		echo json_encode($json);
	
       }       
      
    function comboxmodalidades()
	{
		global $mysqli;
		
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		$idusuario = $_REQUEST['idusuario'];
		$query  = " SELECT DISTINCT modalidad as nombre FROM activos WHERE modalidad != '' ";
		$query .= permisos('combos', 'modalidades', $idusuario);	
		//Si nivel Cliente
		$query .= " ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc())
		{
			$json[]= array('value'=>$row['nombre'],'name'=>$row['nombre'],);
		}
		echo json_encode($json);
	}
	
	function comboxactivo()
	{
		global $mysqli;
		if(isset($_REQUEST['idambiente'])){ $idsitio = $_REQUEST['idambiente']; }else{ $idsitio = ''; }
		
		if($idsitio != ''){
			$query  = " SELECT DISTINCT(serie), id, nombre FROM activos WHERE 1 = 1 AND serie != '' AND idambientes = '$idsitio' ORDER BY serie ASC ";
		}else{
			$query  = " SELECT DISTINCT(serie), id, nombre,idambientes FROM activos WHERE 1 = 1 AND serie != '' AND idambientes != '' ORDER BY serie ASC ";
		}
		$result = $mysqli->query($query);
		
		  if($result->num_rows > 0 )
		  {
		    while($row = $result->fetch_assoc())
		    {
		        $json[]= array('value'=>$row['id'],
		                        'serie'=>$row['serie'],
		                        'name'=>$row['nombre'],
		                        'idambientes'=>$row['idambientes']);
		    }
		   echo json_encode($json);
		  }else{
		   echo json_encode([]);
		  }
		
	}
	
	function seriesel()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		if(isset($_REQUEST['idserie'])){ $idactivos = $_REQUEST['idserie']; }else{ $idactivos = ''; }
		$resultado = array();
		
		if($idactivos != ''){
			$query  = " SELECT b.nombre AS marca, c.nombre AS modelo FROM activos a 
						LEFT JOIN marcas b ON b.id = a.idmarcas
						LEFT JOIN marcas c ON c.id = a.idmodelos
						WHERE 1 = 1 
						AND a.id = $idactivos ";
			$result = $mysqli->query($query);
			
			while($row = $result->fetch_assoc()){
				$resultado[] = array('marca' => $row['marca'], 'modelo' => $row['modelo']);
			}
		}else{
			$resultado[] = array('marca' => '', 'modelo' => '');
		}
		
		echo json_encode($resultado);
	}

?>