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
			case "ambientesnew":
					ambientesnew();
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
			case "estadosnew":
					estadosnew();
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
			case "prioridadesnew":			
					prioridadesnew();
					break;
			case "asigandoa":
			        usuarioDepartamento();
			        break;
			case "dptgrupos":
					departamentosgrupos();
					break;
			case "dptgruposnew":
					departamentosgruposnew();
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
			case "comboxcategorianew":			
					comboxcategorianew();
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
			case "sitiosclientesnew":
			        sitiosclientesnew();
			        break;
			case "sitiosclientes":
			        sitiosclientes();
			        break;
			case "tipos":
			        tipos();
			        break;
			case "solicitantes":
			        solicitantes();
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
		$query .= permisos('combos', 'proyectos', $idusuario);		
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

///----------Cambios para pase a producción nueva bd Función Prioridades---------////
	function prioridadesnew(){
		global $mysqli;
		$idusuario	=$_REQUEST['idusuario'];
		$idclientes	 = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0);
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : 0);
		$json = array();
		
		$query  = " SELECT a.id, a.prioridad FROM sla a INNER JOIN slapuente b ON b.idprioridades = a.id WHERE b.idclientes = ".$idclientes." AND b.idproyectos = ".$idproyectos."";
		$query .= permisos('combos', 'prioridades', $idusuario);	
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
	///----------Cambios para pase a producción nueva bd Función Ambientes---------////
	
	function ambientesnew(){
		global $mysqli;
		$idusuario	=$_REQUEST['idusuario'];
		$nivel		=(!empty($_REQUEST['nivel'])? intval($_REQUEST['nivel']):'');
		$idcliente 	= (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyecto	= (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$json = array();
		
		$query  = " SELECT a.id, a.nombre FROM ambientes a
					INNER JOIN ambientespuente b ON b.idambientes = a.id
					WHERE 1 = 1 ";
		$query .= permisos('combos', 'ambientes', $idusuario);
		if($idcliente != ''){
			$query  .= " AND find_in_set(".$idcliente.",b.idclientes) ";
		}
		if($idproyecto != ''){
			$query  .= " AND find_in_set(".$idproyecto.",b.idproyectos) ";
		}
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
	
	function camposelectm($campo, $valor, $condicion){
		$query = '';
		if(is_array($valor)){
			$counter = 1;
			$tot = count($valor);
			$test= " ".$condicion." ( ";
			foreach($valor as $val){
				$test .= " find_in_set($val,$campo) ";
				if($counter != $tot){
					$test .=" OR ";
				}
				$counter++;
			}
			$test .= " ) ";
			$query  .= $test;
		}else{
			$arr = strpos($valor, ',');
			if ($arr !== false) {
				$arrvalor = explode(',',$valor);
				$test= " ".$condicion." ( ";
				$tot = count($arrvalor);
				$counter = 1;
				foreach($arrvalor as $val){
					$test .= " find_in_set($val,$campo) ";
					if($counter != $tot){
						$test .=" OR ";
					}
					$counter++;
				}
				$test .= " ) ";

				$query  .= $test;
			}else{
				$query  .= " ".$condicion." find_in_set(".$valor.",$campo) ";
			}
		}
		return $query;
	}
	
	///----------Cambios para pase a producción nueva bd Función SitiosClientes---------//// Función usada en Filtros
	function sitiosclientesnew(){
		global $mysqli;
        //nivel,usuario,idclientes,idproyectos
		$nivel =(!empty($_REQUEST['nivel'])? intval($_REQUEST['nivel']):'');
	    $usuario =(!empty($_REQUEST['usuario'])? $_REQUEST['usuario']:'');
	    $idclientes =(!empty($_REQUEST['idclientes'])? $_REQUEST['idclientes']:''); 
		$idproyectos =(!empty($_REQUEST['idproyectos'])? $_REQUEST['idproyectos']:''); 
		
		$query  = " SELECT a.id, a.nombre FROM ambientes a 
					INNER JOIN ambientespuente b ON b.idambientes = a.id";
		if($nivel != 1 && $nivel != 2 && $nivel != 4 && $nivel != 7){
			$query .= " LEFT JOIN usuarios c ON find_in_set(a.id, c.idambientes)
						WHERE  c.usuario = '$usuario' ";
		}else{
			$query .= " LEFT JOIN usuarios c ON find_in_set(a.id, c.idambientes)
						WHERE 1 = 1 ";
		}
		if($idclientes != '' && $idclientes != 'undefined'){
			$widclientes = camposelectm('b.idclientes', $idclientes, 'AND');
			$query  .= $widclientes;
		}
		if($idproyectos != '' && $idproyectos != 'undefined'){
			$widproyectos = camposelectm('b.idproyectos', $idproyectos, 'AND');
			$query  .= $widproyectos;
		}
		$query .= " GROUP BY a.id ORDER BY a.nombre ";
		//debugL($query);
		$result = $mysqli->query($query);
		
		if($result->num_rows > 0 ){				
			while($row = $result->fetch_assoc()){
				$json[]= array(
							'value'	=> $row['id'],
							'name'	=> $row['nombre']
						);
			}			
		}else{
		    $json= array();
		}
		echo json_encode($json);
	}
	
	function sitiosclientes(){
		global $mysqli;
        //nivel,usuario,idclientes,idproyectos
		$nivel =(!empty($_REQUEST['nivel'])? intval($_REQUEST['nivel']):'');
	    $usuario =(!empty($_REQUEST['usuario'])? $_REQUEST['usuario']:'');
	    $idclientes =(!empty($_REQUEST['idclientes'])? $_REQUEST['idclientes']:''); 
		$idproyectos =(!empty($_REQUEST['idproyectos'])? $_REQUEST['idproyectos']:'');

		/*if($nivel != 4 || $nivel != 7){
		    
		    $qCliente="SELECT idclientes,idproyectos FROM `usuarios` WHERE usuario='".$usuario."'";
		    $result = $mysqli->query($qCliente);
			$row = $result->fetch_assoc();    
			$idclientes = (!empty($row['idclientes'])? $row['idclientes']:0);
			$idproyectos = (!empty($row['idproyectos'])? $row['idproyectos']:0);
		}*/
		
		$query  = " SELECT a.id, a.nombre FROM ambientes a ";
		if($nivel != 1 && $nivel != 2 && $nivel != 4 && $nivel != 7){
			$query .= " LEFT JOIN usuarios b ON find_in_set(a.id, b.idambientes)
						WHERE  b.usuario = '$usuario' ";
		}else{
			$query .= " LEFT JOIN usuarios b ON find_in_set(a.id, b.idambientes)
						WHERE 1 = 1 ";
		}
		if($idclientes != '' && $idclientes != 'undefined'){
			$widclientes = camposelectm('a.idclientes', $idclientes, 'AND');
			$query  .= $widclientes;
		}
		if($idproyectos != '' && $idproyectos != 'undefined'){
			$widproyectos = camposelectm('a.idproyectos', $idproyectos, 'AND');
			$query  .= $widproyectos;
		}
		$query .= " GROUP BY a.id ORDER BY a.nombre ";
		//debugL($query);
		$result = $mysqli->query($query);
		
		if($result->num_rows > 0 ){				
			while($row = $result->fetch_assoc()){
				$json[]= array(
							'value'	=> $row['id'],
							'name'	=> $row['nombre']
						);
			}			
		}else{
		    $json= array();
		}
		echo json_encode($json);
	}
	
	function solicitantes(){
		global $mysqli;
		
		$nivel = (!empty($_REQUEST['nivel']) ? $_REQUEST['nivel'] : '');
		$tipo		= (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : ""); 
		$idcliente	= (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : ""); 
		$idproyecto	= (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : "");
		
		$query  = " SELECT a.correo, a.nombre FROM usuarios a INNER JOIN niveles b ON a.nivel = b.id WHERE 1 = 1 ";
		//Si nivel Cliente
		if($nivel==4 || $nivel == 7){
			if($idclientes != ''){
				$arr = strpos($idclientes, ',');
				if ($arr !== false) {
					$query  .= " AND a.idclientes IN (".$idclientes.") ";
				}else{
					$query  .= " AND find_in_set($idclientes,a.idclientes) ";
				}  
			}
			if($idproyectos != ''){
				$arr = strpos($idproyectos, ',');
				if ($arr !== false) {
					$query  .= " AND a.idproyectos IN (".$idproyectos.") ";
				}else{
					$query  .= " AND find_in_set($idproyectos,a.idproyectos) ";
				}  
			}
		}else{
			/*if($tipo == 'filtrosmasivos'){*/ 
				if($idcliente != ''){ 
					$arr = strpos($idcliente, ',');
					if ($arr !== false) {
						$query  .= " AND a.idclientes IN (".$idcliente.") ";
					}else{
						$query  .= " AND find_in_set(".$idcliente.",a.idclientes) ";
					} 
				}
				if($idproyecto != ''){ 
					$arr = strpos($idproyecto, ',');
					if ($arr !== false) {
						$query  .= " AND a.idproyectos IN (".$idproyecto.") ";
					}else{
						$query  .= " AND find_in_set(".$idproyecto.",a.idproyectos) ";
					}
				} 
			/*}*/
		}
		
		/*if($nivel !=''){
			//$query  .=" AND a.nivel IN ($nivel) ";
			$query  .=" AND find_in_set(a.nivel,'".$nivel."') ";
		}*/
		
		$query  .=" ORDER BY a.nombre ASC ";
		debugL($query,"solicitantes");
		$result = $mysqli->query($query);
		
		if($result->num_rows > 0 ){				
			while($row = $result->fetch_assoc()){
				$json[]= array('value'	=> $row['correo'],
							    'name'	=> $row['nombre']);
			}			
		}else{
		    $json= array();
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
	
	
	///----------Cambios para pase a producción nueva bd Función DepartamentosGrupos---------////
	function departamentosgruposnew(){
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
	
	///----------Cambios para pase a producción nueva bd Función Estados---------////
	function estadosnew(){    
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
		$query .= permisos('combos', 'estados', $idusuario);
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
	
	
	///----------Cambios para pase a producción nueva bd Función Categorías---------////
	function comboxcategorianew(){
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
		//debug($query);
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
	
	function comboxmodalidades(){
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
	
	function tipos(){
		global $mysqli;
		$query  = " SELECT a.id, a.nombre FROM activostipos a WHERE 1 = 1 ";	 
		$query  .= " ORDER BY a.nombre ASC ";
		$result = $mysqli->query($query);
	
		if($result->num_rows > 0 ){
			while($row = $result->fetch_assoc()){
				$json[]=array('value'	=>$row['id'],
								'name'	=>$row['nombre']);
			}
		}else{
		    $json=array();
		}
		echo json_encode($json);
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