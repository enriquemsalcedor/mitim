<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){
		case "cargarproyectos": 
			  cargarproyectos();
			  break;
		case "getproyectos": 
			  getproyectos();
			  break;
		case "createproyectos": 
			  createproyectos();
			  break;
		case "updateproyectos": 
			  updateproyectos();
			  break;
		case "deleteproyectos": 
			  deleteproyectos();
			  break;
		case "hayRelacionPro":
			  hayRelacionPro();
			  break;
		case "getProyectosCategorias":
			  getProyectosCategorias();
			  break;
		case "getProyectosAmbientes":
			  getProyectosAmbientes();
			  break; 
		case "getProyectosEstados":
			  getProyectosEstados();
			  break;
		case "getProyectosDepartamentos":
			  getProyectosDepartamentos();
			  break; 
		case "getProyectosPrioridades":
			  getProyectosPrioridades();
			  break; 
		case "getProyectosEtiquetas":
			  getProyectosEtiquetas();
			  break;
		case "asociarCategorias":
			  asociarCategorias();
			  break;
		case "asociarAmbientes":
			  asociarAmbientes();
			  break;
		case "asociarSubambientes":
			  asociarSubambientes();
			  break;
		case "asociarDepartamentos":
			  asociarDepartamentos();
			  break;
		case "asociarEstados":
			  asociarEstados();
			  break;
		case "asociarSubcategorias":
			  asociarSubcategorias();
			  break; 
		case "asociarPrioridades":
			  asociarPrioridades();
			  break;
		case "asociaretiquetas":
			  asociaretiquetas();
			  break;
		case "hayRelacionPc": 
			  hayRelacionPc();
			  break;
		case "hayRelacionPa": 
			  hayRelacionPa();
			  break;
		case "hayRelacionSubc": 
			  hayRelacionSubc();
			  break;
		case "hayRelacionSuba": 
			  hayRelacionSuba();
			  break;
		case "hayRelacionDep": 
			  hayRelacionDep();
			  break;
		case "hayRelacionEs": 
			  hayRelacionEs();
			  break;
		case "hayRelacionPd": 
			  hayRelacionPd();
			  break;
		case "hayRelacionEt": 
			  hayRelacionEt();
			  break;
		case "eliminarCategoriasPuente": 
			  eliminarCategoriasPuente();
			  break;
		case "eliminarSubcategoriasPuente": 
			  eliminarSubcategoriasPuente();
			  break;
		case "eliminarAmbientesPuente": 
			  eliminarAmbientesPuente();
			  break;
		case "eliminarSubambientesPuente": 
			  eliminarSubambientesPuente();
			  break;
		case "eliminarEstadosPuente": 
			  eliminarEstadosPuente();
			  break;
		case "eliminarDepartamentosPuente": 
			  eliminarDepartamentosPuente();
			  break; 
		case "eliminarPrioridadesPuente": 
		      eliminarPrioridadesPuente();
			  break;
		case "eliminarEtiquetasPuente": 
			  eliminarEtiquetasPuente();
			  break;
	    case "cargarContactos": 
			  cargarContactos();
			  break;
		case "createcontactos": 
			  createcontactos();
			  break;
		case "updatecontactos": 
			  updatecontactos();
			  break;
		case "deletecontactos": 
			  deletecontactos();
			  break;
		case "cargarContratos": 
			cargarContratos();
			  break;
		case "createcontratos": 
			 createcontratos();
			 break;
		case "updatecontratos": 
			  updatecontratos();
			  break;
		case "deletecontratos": 
			 deletecontratos();
			 break;
		case "hayNotificaciones": 
			 hayNotificaciones();
			 break;
		case "updatenotificaciones": 
			 updatenotificaciones();
			 break;
		case "deletenotificaciones": 
			 deletenotificaciones();
			 break;
		case "getnotificaciones": 
			 getnotificaciones();
			 break;
		case "verHorasContratadas": 
			 verHorasContratadas();
			  break;
		case "getetiquetas": 
			 getetiquetas();
			 break;
		case "editarTipoCategorias": 
			 editarTipoCategorias();
			 break;
		case "editarTipoEstados": 
			 editarTipoEstados();
			 break;
		default:
			  echo "{failure:true}";
			  break;
	}	
	
	function cargarproyectos(){
		global $mysqli;
		
		$where = "";
		$where2 = array();
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		//contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);
		/*----------------------------------------------------------------------
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		//Obtener el nombre de la columna de clasificación de su índice
		$orderBy= (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ?$_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );
		//ASC or DESC*/
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); 
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		/*--------------------------------------------------------------------*/
		$query = " SELECT a.id, a.codigo, a.nombre
					FROM proyectos a "; 
		
		$result = $mysqli->query($query);
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		$resultado = array();
		while($row = $result->fetch_assoc()){
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center droptable"> 
										<a class="dropdown-item text-info" href="proyecto.php?id='.$row['id'].'&type=edit"><i class="fas fa-pen mr-2"></i>Editar</a><a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
									</div>
								</div>
							</td>';
							
			$resultado[] = array(
				'id' 			=>	$row['id'],
				'acciones' 		=>	$acciones,  
				'codigo'		=>	$row['codigo'],
				'nombre'	 	=>	$row['nombre'] 
			);
		}
		
		$response = array(
			"draw" => intval($draw),
			"recordsTotal" => intval($recordsTotal),
			"recordsFiltered" => intval($recordsTotal),
			"data" => $resultado
		  ); 
		echo json_encode($response);
	}	
	
	
	function getproyectos(){
		global $mysqli;
		
		$id 	= $_REQUEST['id'];
		$query 	= "	SELECT a.codigo, a.nombre, a.descripcion, a.correlativo, a.estado
					FROM proyectos a 
					WHERE a.id = ".$id."";
					//echo $query;
		$result = $mysqli->query($query);
		
		if($row = $result->fetch_assoc()){
			$resultado = array(  
				'codigo'			=>	$row['codigo'],
				'nombre'	 		=>	$row['nombre'],
				'descripcion'	 	=>	$row['descripcion'],
				'correlativo' 		=>	$row['correlativo'], 
				'nombrecliente'     =>	$row['nombrecliente'], 
				'estado'            =>	$row['estado']
			);
		}
		
		if( isset($resultado) ) {
			echo json_encode($resultado); 
		} else {
			echo "0";
		}
	}	
	
	
	function deleteproyectos(){
		global $mysqli;
		
		$id 	= $_REQUEST['id'];
		$query 	= "DELETE FROM proyectos WHERE id = '$id'";
		$result = $mysqli->query($query);		
	    
	    if ($result==true){
	        
	        //Actualiza Proyectos asociados al usuario
	        $qUser = " SELECT * FROM usuarios 
	                   WHERE idproyectos LIKE '$id,%' OR idproyectos LIKE '%,$id' OR idproyectos LIKE '%,$id,%' ";
	                           
	        $result = $mysqli->query($qUser);
	       
	        while($row = $result->fetch_assoc()){
	            
	            $idusuario   = $row['id']; 
			    $idproyusers = $row['idproyectos']; 
    		    $idproyusers = str_replace($id.',','',$idproyusers);
    		    $idproyusers = str_replace(','.$id.',',',',$idproyusers);
    		    $idproyusers = str_replace(','.$id,'',$idproyusers);
    		    
    		    $qUpd   = "UPDATE usuarios SET idproyectos = '$idproyusers' WHERE id = $idusuario ";
    		    $resUpd = $mysqli->query($qUpd); 
    		     
	        }         
	        
	        bitacora($_SESSION['usuario'], "Proyectos", "El proyecto #".$id." ha sido eliminado", $id , $query);
	        
	        echo 1;
	    }else{
	        echo 0;
	    }
	}
	
	function updateproyectos(){
		global $mysqli;
		
		$id 			  = $_REQUEST['id'];  
		$codigo		   	  = (!empty($_REQUEST['codigo']) ? $_REQUEST['codigo'] : '');
		$nombre			  = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$correlativo	  = (!empty($_REQUEST['correlativo']) ? $_REQUEST['correlativo'] : ''); 
		$idclientes		  = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0); 
		$descripcion	  = (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');
		$estado	          = (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : '');
		
		$query 	= "	UPDATE proyectos
		            SET codigo = '".$codigo."', 
    		            nombre = '".$nombre."', 
    		            correlativo = '".$correlativo."', 
    		            descripcion = '".$descripcion."',
    		            estado = '".$estado."'
		            WHERE id = ".$id."";
		
		$result = $mysqli->query($query);	
		
		if($result==true){
		    
		    bitacora($_SESSION['usuario'], "Proyectos", "El proyecto #".$id." ha sido editado", $id , $query);
		    
	        echo 1;
	    }else{
	        echo 0;
	    }
	}
	
	function createproyectos(){
		global $mysqli;
		
		$codigo		 = (!empty($_REQUEST['codigo']) ? $_REQUEST['codigo'] : '');
		$nombre		 = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$correlativo = (!empty($_REQUEST['correlativo']) ? $_REQUEST['correlativo'] : ''); 
		$idclientes	 = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0);
		$descripcion = (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');
		
		//Evitar duplicado
		$sql = "SELECT nombre FROM proyectos WHERE nombre = '".$nombre."'"; 
		//echo $sql;
		$rsql = $mysqli->query($sql);
		if($rsql->num_rows > 0){
		    echo 2;
		}else{
		    $query 	= "	INSERT INTO	proyectos (codigo,nombre,correlativo,fecha,descripcion)
    					VALUES ('".$codigo."','".$nombre."','".$correlativo."',CURDATE(),'".$descripcion."')";
    					
    		$result = $mysqli->query($query);
    		$idproy = $mysqli->insert_id;
    		
    		if($result==true){
    		    
    		    bitacora($_SESSION['usuario'], "Proyectos", "El proyecto #".$idproy." ha sido creado", $idproy, $query);
    			echo 1;
    		}else{
    			echo 0;
    		}
		} 
	}
  
	function hayRelacionPro(){
	    global $mysqli;
	    
	    $id = $_REQUEST['id'];
	    
	    $existe = array(
            'correctivos'    => 0,
            'preventivos'   => 0,
            'postventas'    => 0
        ); 
        
        $sql = "SELECT 
                    (SELECT CASE WHEN COUNT(id) > 0 THEN 1 ELSE 0 END FROM incidentes WHERE tipo = 'incidentes' AND idproyectos = ".$id." LIMIT 1) AS correctivos, 
                     (SELECT CASE WHEN COUNT(id) > 0 THEN 1 ELSE 0 END FROM incidentes WHERE tipo = 'preventivos' AND idproyectos = ".$id." LIMIT 1) AS preventivos,
                     (SELECT CASE WHEN COUNT(id) > 0 THEN 1 ELSE 0 END FROM postventas WHERE idproyectos = ".$id." LIMIT 1) AS postventas 
                     ";
        $r = $mysqli->query($sql);
        if($row = $r->fetch_assoc()){
            $existe['correctivos']   = $row['correctivos']; 
            $existe['preventivos']   = $row['preventivos']; 
            $existe['postventas']    = $row['postventas'];  
        } 
                  
	    echo json_encode($existe);
	    
	}  
	
	function getProyectosCategorias(){
		global $mysqli;
		 
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		
		$query 	= "	SELECT a.id, b.id AS idpuente, a.nombre, b.tipo 
					FROM categorias a 
					INNER JOIN categoriaspuente b ON b.idcategorias = a.id 
					WHERE 1  
					AND b.idproyectos = ".$idproyectos."";
					
		$result = $mysqli->query($query);
		$totalcat = $result->num_rows;
		
		while($row = $result->fetch_assoc()){
			
			$idcategorias = $row['id'];
			$subcategorias = array();
			$sql = " SELECT a.id, a.nombre, b.id AS idpuentesub 
					 FROM subcategorias a 
					 INNER JOIN subcategoriaspuente b ON b.idsubcategorias = a.id 
					 WHERE 1 
					 AND b.idproyectos = ".$idproyectos." AND b.idcategorias = ".$idcategorias."";
			
			$rsql = $mysqli->query($sql);
			while($reg = $rsql->fetch_assoc()){
				
				$subcategorias [] = array(
					'id'		=>	$reg['id'],
					'nombre'	=>	$reg['nombre'],
					'idpuentesub'	=>	$reg['idpuentesub']
					
				);
			
			}
			
			$resultado [] = array(  
				'id'			=>	$row['id'],
				'nombre'		=>	$row['nombre'], 
				'idpuente'		=>	$row['idpuente'], 
				'tipo'			=>	$row['tipo'], 
				'subcategorias'	=>  $subcategorias,
				'totalcat'	    =>  $totalcat
			);
		}
		
		if( isset($resultado) ) {
			echo json_encode($resultado); 
		} else {
			echo "0";
		}
	}	
	
	function getProyectosAmbientes(){
		global $mysqli;
		 
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		
		$query 	= "	SELECT a.id, a.nombre, b.id AS idpuente
					FROM ambientes a 
					INNER JOIN ambientespuente b ON b.idambientes = a.id 
					WHERE 1  
					AND b.idproyectos = ".$idproyectos."";
					
		$result = $mysqli->query($query);
		$totalamb = $result->num_rows;
		
		while($row = $result->fetch_assoc()){
			
			$idambientes = $row['id'];
			$subambientes = array();
			$sql = " SELECT a.id, a.nombre, b.id AS idpuentesub 
					 FROM subambientes a 
					 INNER JOIN subambientespuente b ON b.idsubambientes = a.id 
					 WHERE 1 
					 AND b.idproyectos = ".$idproyectos." AND b.idambientes = ".$idambientes."";
			
			$rsql = $mysqli->query($sql);
			while($reg = $rsql->fetch_assoc()){
				
				$subambientes [] = array(
					'id'		=>	$reg['id'],
					'nombre'	=>	$reg['nombre'],
					'idpuentesub'	=>	$reg['idpuentesub'],
				);
			
			}
			
			$resultado [] = array(  
				'id'			=>	$row['id'],
				'nombre'		=>	$row['nombre'], 
				'idpuente'		=>	$row['idpuente'], 
				'subambientes'	=>	$subambientes,
				'totalamb'	    =>	$totalamb
			);
		}
		
		if( isset($resultado) ) {
			echo json_encode($resultado); 
		} else {
			echo "0";
		}
	}
	
		function getProyectosEstados(){
			global $mysqli;
			 
			$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
			
			$query 	= "	SELECT a.id, a.nombre, b.id AS idpuente, b.tipo, b.descripcion 
						FROM estados a 
						INNER JOIN estadospuente b ON b.idestados = a.id 
						WHERE 1  
						AND b.idproyectos = ".$idproyectos."";
						
			$result = $mysqli->query($query);
			$totalest = $result->num_rows;
			
			while($row = $result->fetch_assoc()){
				 
				
				$resultado [] = array(  
					'id'		  =>	$row['id'],
					'nombre'	  =>	$row['nombre'], 
					'idpuente'	  =>	$row['idpuente'], 
					'tipo'		  =>	$row['tipo'], 
					'descripcion' =>	$row['descripcion'], 
					'totalest'	  =>	$totalest
				);
			}
			
			if( isset($resultado) ) {
				echo json_encode($resultado); 
			} else {
				echo "0";
			}
		}		
		
		function getProyectosDepartamentos(){
			global $mysqli;
			 
			$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
			
			$query 	= "	SELECT a.id, a.nombre, b.id AS idpuente 
						FROM departamentos a 
						INNER JOIN departamentospuente b ON b.iddepartamentos = a.id 
						WHERE 1  
						AND b.idproyectos = ".$idproyectos."";
						
			$result = $mysqli->query($query);
			$totaldep = $result->num_rows;
			
			while($row = $result->fetch_assoc()){
				 
				
				$resultado [] = array(  
					'id'		=>	$row['id'],
					'nombre'	=>	$row['nombre'], 
					'idpuente'	=>	$row['idpuente'],
					'totaldep'	=>	$totaldep
				);
			}
			
			if( isset($resultado) ) {
				echo json_encode($resultado); 
			} else {
				echo "0";
			}
		}
		
		function getProyectosPrioridades(){
			global $mysqli;
			 
			$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
			
			$query 	= "	SELECT a.id, a.prioridad AS nombre, b.id AS idpuente 
						FROM sla a 
						INNER JOIN slapuente b ON b.idprioridades = a.id 
						WHERE 1  
						AND b.idproyectos = ".$idproyectos."";
						
			$result = $mysqli->query($query);
			$totalpri = $result->num_rows;
			
			while($row = $result->fetch_assoc()){
				 
				
				$resultado [] = array(  
					'id'		=>	$row['id'],
					'nombre'	=>	$row['nombre'], 
					'idpuente'	=>	$row['idpuente'],
					'totalpri'	=>	$totalpri
				);
			}
			
			if( isset($resultado) ) {
				echo json_encode($resultado); 
			} else {
				echo "0";
			}
		}

		function getProyectosEtiquetas(){
			global $mysqli;
			 
			$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
			
			$query 	= "	SELECT a.id, a.nombre, b.id AS idpuente, c.valor AS color 
						FROM etiquetas a 
						INNER JOIN proyectosetiquetas b ON b.idetiquetas = a.id
						INNER JOIN colores c ON c.id = a.idcolores
						WHERE 1  
						AND b.idproyectos = ".$idproyectos."";
						
			$result = $mysqli->query($query);
			$totaletiq = $result->num_rows;
			
			while($row = $result->fetch_assoc()){
				 
				
				$resultado [] = array(  
					'id'		=>	$row['id'],
					'nombre'	=>	$row['nombre'], 
					'idpuente'	=>	$row['idpuente'],
					'color'		=>	$row['color'],
					'totaletiq'	=>	$totaletiq
				);
			}
			
			if( isset($resultado) ) {
				echo json_encode($resultado); 
			} else {
				echo "0";
			}
		}
		
		
	function asociarCategorias(){
		global $mysqli;		 
		
		$id 		  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$nombre	  	  = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$tipo	  	  = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');
		$modulo		  = (!empty($_REQUEST['modulo']) ? $_REQUEST['modulo'] : '');
		$idusuarios	  = $_SESSION['user_id'];
		
		if($id != ""){
			
			$sql = " SELECT id FROM categoriaspuente 
					 WHERE 
					 idclientes = ".$idclientes." 
					 AND idproyectos = ".$idproyectos."
					 AND idcategorias = ".$id."
					 AND tipo LIKE '%".$tipo."%'";
			//echo $sql;
			$rsql = $mysqli->query($sql);
			
			//Evitar duplicado
			if($rsql->num_rows > 0){
				echo 2;
			}else{ 
				$query 	= "	INSERT INTO	categoriaspuente (idempresas,idclientes, idproyectos, idcategorias, tipo, idusuarios) 
							VALUES (1, ".$idclientes.", ".$idproyectos.", ".$id.", '".$tipo."', ".$idusuarios.")";
				
				$result = $mysqli->query($query);
				$result == true ? $respuesta = 1 : $respuesta = 0;
				$idcategorias = $mysqli->insert_id;
				bitacora($_SESSION['usuario'], "Categorías asociación - ".$modulo."", "El registro #".$idcategorias." ha sido creado", $idcategorias, $query);
				echo $respuesta;
			}
		}else{
			$sql = " INSERT INTO categorias (nombre) VALUES ('".$nombre."')";
			$result = $mysqli->query($sql);
			$idcate = $mysqli->insert_id;
			bitacora($_SESSION['usuario'], "Categorías - ".$modulo."", "La categoría #".$idcate." ha sido creada", $idcate, $sql);
			
			$result == true ? $response = 1 : $response = 0;
			if($response == 1){
				$query 	= "	INSERT INTO	categoriaspuente (idempresas,idclientes, idproyectos, idcategorias, tipo, idusuarios) 
							VALUES (1, ".$idclientes.", ".$idproyectos.", ".$idcate.", '".$tipo."',".$idusuarios.")";
							$result = $mysqli->query($query);
							$result == true ? $respuesta = 1 : $respuesta = 0;
							$idcategoriasp = $mysqli->insert_id;
							bitacora($_SESSION['usuario'], "Categorías asociación - ".$modulo."", "El registro #".$idcategoriasp." ha sido creado", $idcategoriasp, $query);
							echo $respuesta;
			}
		}
		 
	}	
	
	function asociarAmbientes(){
		global $mysqli;		 
		 
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$nombre	  	  = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$responsables = (!empty($_REQUEST['responsables']) ? $_REQUEST['responsables'] : '');
		$modulo		  = (!empty($_REQUEST['modulo']) ? $_REQUEST['modulo'] : '');
		$idusuarios	  = $_SESSION['user_id'];
		 
		$sql =" SELECT a.nombre FROM ambientes a INNER JOIN ambientespuente b ON b.idambientes = a.id WHERE nombre = '".$nombre."' AND b.idproyectos = '".$idproyectos."'"; 
		$rsql = $mysqli->query($sql);
		
		//Evitar duplicado
		if($rsql->num_rows > 0){
			echo 2;
		}else{
			$query = " INSERT INTO ambientes (nombre,responsables) VALUES ('".$nombre."','".$responsables."')";
			$result = $mysqli->query($query);
			$result == true ? $respuesta = 1 : $respuesta = 0;
			$idambientes = $mysqli->insert_id;
			bitacora($_SESSION['usuario'], "Ambientes - ".$modulo."", "El ambiente #".$idambientes." ha sido creado", $idambientes, $query);
			
			if($respuesta == 1){
				
				$query 	= "	INSERT INTO	ambientespuente (idempresas,idclientes, idproyectos, idambientes, fechacreacion, idusuarios) 
							VALUES (1, ".$idclientes.", ".$idproyectos.", ".$idambientes.", NOW(), ".$idusuarios.")";
						
				$result = $mysqli->query($query);
				$idcate = $mysqli->insert_id;
				$result == true ? $respuesta = 1 : $respuesta = 0;
				$idambientesp = $mysqli->insert_id;
				bitacora($_SESSION['usuario'], "Ambientes asociación - ".$modulo."", "El registro #".$idambientesp." ha sido creado", $idambientesp, $query);
				echo $respuesta;
			
			}else{
				echo 0;
			}
		} 
	}	
	
	function asociarSubambientes(){
		global $mysqli;		 
		 
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idambientes  = (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : '');
		$nombre	  	  = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : ''); 
		$modulo		  = (!empty($_REQUEST['modulo']) ? $_REQUEST['modulo'] : '');
		$idusuarios	  = $_SESSION['user_id'];
		 
		$sql =" SELECT a.nombre FROM subambientes a INNER JOIN subambientespuente b ON b.idsubambientes = a.id WHERE nombre = '".$nombre."' AND b.idproyectos = '".$idproyectos."' AND b.idambientes = '".$idambientes."'"; 
		$rsql = $mysqli->query($sql);
		
		//Evitar duplicado
		if($rsql->num_rows > 0){
			echo 2;
		}else{
			$query = " INSERT INTO subambientes (nombre) VALUES ('".$nombre."')";
			$result = $mysqli->query($query);
			$result == true ? $respuesta = 1 : $respuesta = 0;
			$idsubambientes = $mysqli->insert_id;
			bitacora($_SESSION['usuario'], "Subambientes - ".$modulo."", "El subambiente #".$idsubambientes." ha sido creado", $idsubambientes, $query);
			if($respuesta == 1){
				
				$query 	= "	INSERT INTO	subambientespuente (idempresas,idclientes, idproyectos, idambientes, idsubambientes, fechacreacion, idusuarios) 
							VALUES (1, ".$idclientes.", ".$idproyectos.", ".$idambientes.", ".$idsubambientes.", NOW(), ".$idusuarios.")";
						//echo $query;
				$result = $mysqli->query($query); 
				$result == true ? $respuesta = 1 : $respuesta = 0;
				$idsubambientesp = $mysqli->insert_id;
				bitacora($_SESSION['usuario'], "Subambientes asociación - ".$modulo."", "El registro #".$idsubambientesp." ha sido creado", $idsubambientesp, $query);
				echo $respuesta;
			
			}else{
				echo 0;
			}
		} 
	}
	
	function asociarEstados(){
		global $mysqli;		 
		
		$id 		  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$nombre		  = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$descripcion = (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');
		$tipo		  = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');
		$modulo		  = (!empty($_REQUEST['modulo']) ? $_REQUEST['modulo'] : '');
		$idusuarios	  = $_SESSION['user_id'];
		
		if($id != ""){
			
			$sql = " SELECT id FROM estadospuente 
					 WHERE 
					 idclientes = ".$idclientes." 
					 AND idproyectos = ".$idproyectos."
					 AND idestados = ".$id."";
			//echo $sql;
			$rsql = $mysqli->query($sql);
			
			//Evitar duplicado
			if($rsql->num_rows > 0){
				echo 2;
			}else{ 
				$query 	= "	INSERT INTO	estadospuente (idempresas,idclientes, idproyectos, idestados, tipo, 
							descripcion, fechacreacion, idusuarios) 
							VALUES (1, ".$idclientes.", ".$idproyectos.", ".$id.", '".$tipo."', '".$descripcion."',  NOW(), ".$idusuarios.")";
				
				$result = $mysqli->query($query);
				$result == true ? $respuesta = 1 : $respuesta = 0;
				$idestadosp = $mysqli->insert_id;
				bitacora($_SESSION['usuario'], "Estados asociación - ".$modulo." ", "El registro #".$idestadosp." ha sido creado", $idestadosp, $query);
				echo $respuesta;
			}
		}else{
			$sql = " INSERT INTO estados (nombre,tipo) VALUES ('".$nombre."','')";
			$result = $mysqli->query($sql);
			$idest = $mysqli->insert_id;
			bitacora($_SESSION['usuario'], "Estados - ".$modulo."", "El estado #".$idest." ha sido creado", $idest, $sql);
			
			$result == true ? $response = 1 : $response = 0;
			if($response == 1){
				$query 	= "	INSERT INTO	estadospuente (idempresas,idclientes, idproyectos, idestados, tipo,
							descripcion, fechacreacion, idusuarios) 
							VALUES (1, ".$idclientes.", ".$idproyectos.", ".$idest.", '".$tipo."', '".$descripcion."',  NOW(), ".$idusuarios.")";
							
							$result = $mysqli->query($query);
							$result == true ? $respuesta = 1 : $respuesta = 0;
							$idestadosp = $mysqli->insert_id;
							bitacora($_SESSION['usuario'], "Estados asociación - ".$modulo."", "El registro #".$idestadosp." ha sido creado", $idestadosp, $query);
							echo $respuesta;
			}
		} 
	}	
	
	function asociarDepartamentos(){
		global $mysqli;		 
		
		$id 		  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$nombre  	  = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$tipo  	      = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');
		$modulo		  = (!empty($_REQUEST['modulo']) ? $_REQUEST['modulo'] : '');
		$idusuarios	  = $_SESSION['user_id'];
		
		if($id != ""){
			
			$sql = " SELECT id FROM departamentospuente 
					 WHERE 
					 idclientes = ".$idclientes." 
					 AND idproyectos = ".$idproyectos."
					 AND iddepartamentos = ".$id."";
			//echo $sql;
			$rsql = $mysqli->query($sql);
			
			//Evitar duplicado
			if($rsql->num_rows > 0){
				echo 2;
			}else{ 
				$query 	= "	INSERT INTO	departamentospuente (idempresas,idclientes, idproyectos, iddepartamentos, fechacreacion, idusuarios) 
							VALUES (1, ".$idclientes.", ".$idproyectos.", ".$id.", NOW(), ".$idusuarios.")";
				
				$result = $mysqli->query($query);
				$result == true ? $respuesta = 1 : $respuesta = 0;
				$iddepartamentosp = $mysqli->insert_id;
				bitacora($_SESSION['usuario'], "Departamentos asociación - ".$modulo."", "El registro #".$iddepartamentosp." ha sido creado", $iddepartamentosp, $query);
				echo $respuesta;
			}
		}else{
			$sql = " INSERT INTO departamentos (nombre,tipo) VALUES ('".$nombre."','".$tipo."')";
			//echo $sql;
			$result = $mysqli->query($sql);
			$iddep = $mysqli->insert_id;
			bitacora($_SESSION['usuario'], "Departamentos - ".$modulo."", "El departamento #".$iddep." ha sido creado", $iddep, $sql);
			
			$result == true ? $response = 1 : $response = 0;
			if($response == 1){
				$query 	= "	INSERT INTO	departamentospuente (idempresas,idclientes, idproyectos, iddepartamentos, fechacreacion, idusuarios) 
							VALUES (1, ".$idclientes.", ".$idproyectos.", ".$iddep.", NOW(), ".$idusuarios.")";
							
							$result = $mysqli->query($query);
							$result == true ? $respuesta = 1 : $respuesta = 0;
							$iddepartamentosp = $mysqli->insert_id;
							bitacora($_SESSION['usuario'], "Departamentos asociación - ".$modulo."", "El registro #".$iddepartamentosp." ha sido creado", $iddepartamentosp, $query);
							echo $respuesta;
			}
		} 
	}
	
	function asociarSubcategorias(){
		global $mysqli;
		
		$id   	 		 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idclientes   	 = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  	 = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idcategorias	 = (!empty($_REQUEST['idcategorias']) ? $_REQUEST['idcategorias'] : ''); 
		$nombre     	 = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : ''); 
		$modulo		  = (!empty($_REQUEST['modulo']) ? $_REQUEST['modulo'] : '');
		$idusuarios	  = $_SESSION['user_id'];
		
		if($id != ""){
			
			$sql = " SELECT id FROM subcategoriaspuente 
					 WHERE 
					 idclientes = ".$idclientes." 
					 AND idproyectos = ".$idproyectos."
					 AND idcategorias = ".$idcategorias."
					 AND idsubcategorias = ".$id."";
			
			$rsql = $mysqli->query($sql);
			
			//Evitar duplicado
			if($rsql->num_rows > 0){
				echo 2;
			}else{ 
				$query 	= "	INSERT INTO	subcategoriaspuente (idempresas,idclientes, idproyectos, idcategorias, idsubcategorias, idusuarios) 
							VALUES (1, ".$idclientes.", ".$idproyectos.", ".$idcategorias.", ".$id.", ".$idusuarios.")";
				//echo $query;
				$result = $mysqli->query($query);
				$result == true ? $respuesta = 1 : $respuesta = 0;
				$idsubcategoriasp = $mysqli->insert_id;
				bitacora($_SESSION['usuario'], "Subcategorías asociación - ".$modulo."", "El registro #".$idsubcategoriasp." ha sido creado", $idsubcategoriasp, $query);
				echo $respuesta;
			}
		}else{
			$sql = " INSERT INTO subcategorias (nombre) VALUES ('".$nombre."')";
			//echo $sql;
			$result = $mysqli->query($sql);
			$idsub = $mysqli->insert_id;
			bitacora($_SESSION['usuario'], "Subcategorías - ".$modulo."", "La subcategoría #".$idsub." ha sido creada", $idsub, $sql);
			$result == true ? $response = 1 : $response = 0;
			if($response == 1){
				$query 	= "	INSERT INTO	subcategoriaspuente (idempresas,idclientes,  idproyectos, idcategorias, idsubcategorias, idusuarios) 
							VALUES (1, ".$idclientes.", ".$idproyectos.", ".$idcategorias.", ".$idsub.", ".$idusuarios.")";
							//echo $query;
							$result = $mysqli->query($query);
							$result == true ? $respuesta = 1 : $respuesta = 0;
							$idsubcategoriasp = $mysqli->insert_id;
							bitacora($_SESSION['usuario'], "Subcategorías asociación - ".$modulo."", "El registro #".$idsubcategoriasp." ha sido creado", $idsubcategoriasp, $query);
							echo $respuesta;
			}
		}
		
	}
	 
	function asociarPrioridades(){
		global $mysqli;		 
		
		$id 		     = (!empty($_REQUEST['idprioridades']) ? $_REQUEST['idprioridades'] : '');
		$idclientes      = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos     = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$nombre		     = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');  
		$descripcion 	 = (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');  
		$tiemporespuesta = (!empty($_REQUEST['tiemporespuesta']) ? $_REQUEST['tiemporespuesta'] : 0); 
		$modulo		  	 = (!empty($_REQUEST['modulo']) ? $_REQUEST['modulo'] : '');		
		$idusuarios	     = $_SESSION['user_id'];
		
		if($id != ""){
			
			$sql = " SELECT id FROM slapuente 
					 WHERE 
					 idclientes = ".$idclientes." 
					 AND idproyectos = ".$idproyectos."
					 AND idprioridades = ".$id."";
			//echo $sql;
			$rsql = $mysqli->query($sql);
			
			//Evitar duplicado
			if($rsql->num_rows > 0){
				echo 2;
			}else{ 
				$query 	= "	INSERT INTO	slapuente (idclientes, idproyectos, idprioridades, tiemporespuesta, fechacreacion, idusuarios) 
							VALUES ( ".$idclientes.", ".$idproyectos.", ".$id.",  ".$tiemporespuesta.", NOW(), ".$idusuarios.")";
				
				$result = $mysqli->query($query);
				$result == true ? $respuesta = 1 : $respuesta = 0;
				$idprioridadesp = $mysqli->insert_id;
				bitacora($_SESSION['usuario'], "Prioridades asociación - ".$modulo."", "El registro #".$idprioridadesp." ha sido creado", $idprioridadesp, $query);
				echo $respuesta;
			}
		}else{
			$sql = " INSERT INTO sla (prioridad) VALUES ('".$nombre."')";
			//echo $sql;
			$result = $mysqli->query($sql);
			$idpri = $mysqli->insert_id;
			bitacora($_SESSION['usuario'], "Prioridades - ".$modulo."", "La prioridad #".$idpri." ha sido creada", $idpri, $sql);
			
			$result == true ? $response = 1 : $response = 0;
			if($response == 1){
				$query 	= "	INSERT INTO	slapuente (idclientes, idproyectos, idprioridades, tiemporespuesta, fechacreacion, idusuarios) 
							VALUES ( ".$idclientes.", ".$idproyectos.", ".$idpri.",  ".$tiemporespuesta.", NOW(), ".$idusuarios.")";
				//echo $query;
				$result = $mysqli->query($query);
				$result == true ? $respuesta = 1 : $respuesta = 0;
				$idprioridadesp = $mysqli->insert_id;
				bitacora($_SESSION['usuario'], "Prioridades asociación - ".$modulo."", "El registro #".$idprioridadesp." ha sido creado", $idprioridadesp, $query);
				echo $respuesta;
			}
		} 
	}

	function hayRelacionSubc(){
		global $mysqli;
		 
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idcategorias = (!empty($_REQUEST['idcategorias']) ? $_REQUEST['idcategorias'] : '');
		$idsubcategorias = (!empty($_REQUEST['idsubcategorias']) ? $_REQUEST['idsubcategorias'] : '');
		$existe = array(   
            'correctivos' 	=> 0,
            'preventivos' 	=> 0,
            'postventas' 	=> 0 
        );
		
		$qInc = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'incidentes' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idcategorias = ".$idcategorias." 
				  AND idsubcategorias = ".$idsubcategorias." 
				  LIMIT 1";
       //echo $qInc;
		$rInc = $mysqli->query($qInc);
		if($rInc->num_rows > 0){ 
            $existe['correctivos'] = 1; 
        }
		
		$qPrev = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'preventivos' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idcategorias = ".$idcategorias." 
				  AND idsubcategorias = ".$idsubcategorias." 
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['preventivos'] = 1; 
        }  
		
		$qPrev = " SELECT id FROM postventas 
					WHERE  
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idcategorias = ".$idcategorias." 
				  AND idsubcategorias = ".$idsubcategorias." 
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['postventas'] = 1; 
        } 
        
		echo json_encode($existe);
	}
	 
	function hayRelacionSuba(){
		global $mysqli;
		 
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idambientes = (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : '');
		$idsubambientes = (!empty($_REQUEST['idsubambientes']) ? $_REQUEST['idsubambientes'] : '');
		$existe = array(   
            'correctivos' 	=> 0,
            'preventivos' 	=> 0,
            'postventas' 	=> 0 
        );
		
		$qInc = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'incidentes' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idambientes = ".$idambientes." 
				  AND idsubambientes = ".$idsubambientes." 
				  LIMIT 1";
       //echo $qInc;
		$rInc = $mysqli->query($qInc);
		if($rInc->num_rows > 0){ 
            $existe['correctivos'] = 1; 
        }
		
		$qPrev = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'preventivos' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idambientes = ".$idambientes." 
				  AND idsubambientes = ".$idsubambientes." 
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['preventivos'] = 1; 
        }  
		
		$qPrev = " SELECT id FROM postventas 
					WHERE  
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idambientes = ".$idambientes." 
				  AND idsubambientes = ".$idsubambientes."  
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['postventas'] = 1; 
        } 
        
		echo json_encode($existe);
	}
	
	function hayRelacionPc(){
		global $mysqli;
		 
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idcategorias = (!empty($_REQUEST['idcategorias']) ? $_REQUEST['idcategorias'] : '');
		$existe = array(  
            'subcategorias' => 0,
            'correctivos' 	=> 0,
            'preventivos' 	=> 0,
            'postventas' 	=> 0,
        );
		
		$qInc = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'incidentes' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idcategorias = ".$idcategorias." 
				  LIMIT 1";
       //echo $qInc;
		$rInc = $mysqli->query($qInc);
		if($rInc->num_rows > 0){ 
            $existe['correctivos'] = 1; 
        }
		
		$qPrev = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'preventivos' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idcategorias = ".$idcategorias." 
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['preventivos'] = 1; 
        } 
		
		$qSub = "  	SELECT id FROM subcategoriaspuente
						WHERE 
				     idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idcategorias = ".$idcategorias."
				  LIMIT 1"; 
                 //echo $qSub;  
        $rSub = $mysqli->query($qSub);
		if($rSub->num_rows > 0){ 
            $existe['subcategorias'] = 1;
        }
		
		$qPrev = " SELECT id FROM postventas 
					WHERE  
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idcategorias = ".$idcategorias." 
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['postventas'] = 1; 
        }
		
		echo json_encode($existe);
	}
	
	function hayRelacionEs(){
		global $mysqli;
		 
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idestados = (!empty($_REQUEST['idestados']) ? $_REQUEST['idestados'] : '');
		$existe = array(   
            'correctivos' 	=> 0,
            'preventivos' 	=> 0,
            'postventas' 	=> 0,
        );
		
		$qInc = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'incidentes' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idestados = ".$idestados." 
				  LIMIT 1";
       //echo $qInc;
		$rInc = $mysqli->query($qInc);
		if($rInc->num_rows > 0){ 
            $existe['correctivos'] = 1; 
        }
		
		$qPrev = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'preventivos' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idestados = ".$idestados."   
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['preventivos'] = 1; 
        }  
		
		$qPrev = " SELECT id FROM postventas 
					WHERE  
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idestados = ".$idestados."
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['postventas'] = 1; 
        }
		
		echo json_encode($existe);
	}
	
	function hayRelacionDep(){
		global $mysqli;
		 
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$iddepartamentos = (!empty($_REQUEST['iddepartamentos']) ? $_REQUEST['iddepartamentos'] : '');
		$existe = array(   
            'correctivos' 	=> 0,
            'preventivos' 	=> 0,
            'postventas' 	=> 0,
        );
		
		$qInc = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'incidentes' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND iddepartamentos = ".$iddepartamentos." 
				  LIMIT 1";
       //echo $qInc;
		$rInc = $mysqli->query($qInc);
		if($rInc->num_rows > 0){ 
            $existe['correctivos'] = 1; 
        }
		
		$qPrev = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'preventivos' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND iddepartamentos = ".$iddepartamentos."   
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['preventivos'] = 1; 
        }  
		
		$qPrev = " SELECT id FROM postventas 
					WHERE  
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND iddepartamentos = ".$iddepartamentos."
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['postventas'] = 1; 
        }
		
		echo json_encode($existe);
	}
	
	function hayRelacionPa(){
		global $mysqli;
		 
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idambientes = (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : '');
		$existe = array(  
            'subambientes' => 0,
            'correctivos' 	=> 0,
            'preventivos' 	=> 0,
            'postventas' 	=> 0,
        );
		
		$qInc = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'incidentes' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idambientes = ".$idambientes." 
				  LIMIT 1";
       //echo $qInc;
		$rInc = $mysqli->query($qInc);
		if($rInc->num_rows > 0){ 
            $existe['correctivos'] = 1; 
        }
		
		$qPrev = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'preventivos' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idambientes = ".$idambientes."  
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['preventivos'] = 1; 
        } 
		
		$qSub = "  	SELECT id FROM subambientespuente
						WHERE 
				     idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idambientes = ".$idambientes." 
				  LIMIT 1"; 
                 //echo $qSub;  
        $rSub = $mysqli->query($qSub);
		if($rSub->num_rows > 0){ 
            $existe['subambientes'] = 1;
        }
		
		$qPrev = " SELECT id FROM postventas 
					WHERE  
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idambientes = ".$idambientes." 
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['postventas'] = 1; 
        }
		
		echo json_encode($existe);
	}

	function hayRelacionPd(){
		global $mysqli;
		 
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idprioridades = (!empty($_REQUEST['idprioridades']) ? $_REQUEST['idprioridades'] : '');
		$existe = array(  
            'correctivos' 	=> 0,
            'preventivos' 	=> 0,
            'postventas' 	=> 0,
        );
		
		$qInc = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'incidentes' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idprioridades = ".$idprioridades." 
				  LIMIT 1";
       //echo $qInc;
		$rInc = $mysqli->query($qInc);
		if($rInc->num_rows > 0){ 
            $existe['correctivos'] = 1; 
        }
		
		$qPrev = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'preventivos' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idprioridades = ".$idprioridades."  
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['preventivos'] = 1; 
        }  
		
		$qPrev = " SELECT id FROM postventas 
					WHERE  
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idprioridades = ".$idprioridades." 
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['postventas'] = 1; 
        }
		
		echo json_encode($existe);
	}
	
	function hayRelacionEt(){
	    global $mysqli;
	    
	    $id = $_REQUEST['id'];
	    
	    $existe = array(
            'correctivos' => 0 
        ); 
        
        $sql = "SELECT 
                    (SELECT CASE WHEN COUNT(id) > 0 THEN 1 ELSE 0 END FROM incidentes WHERE tipo = 'incidentes' AND idetiquetas = ".$id." LIMIT 1) AS correctivos
                     ";
        $r = $mysqli->query($sql);
        if($row = $r->fetch_assoc()){
            $existe['correctivos'] = $row['correctivos']; 
        } 
                  
	    echo json_encode($existe);
	    
	}

	function eliminarCategoriasPuente(){
		
		global $mysqli;
		
		$id  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		
		$query 	= "DELETE FROM categoriaspuente WHERE id = ".$id.""; 
		$result = $mysqli->query($query);
		
		if($result==true){
			
			bitacora($_SESSION['usuario'], "Categorias", "La categoria #".$id." ha sido eliminada", $id , $query);
			
			echo 1;
		}else{
			echo 0;
		} 
	}	
	
	function eliminarSubcategoriasPuente(){
		
		global $mysqli;
		
		$id  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		
		$query 	= "DELETE FROM subcategoriaspuente WHERE id = ".$id.""; 
		
		$result = $mysqli->query($query);
		
		if($result==true){
			
			bitacora($_SESSION['usuario'], "Subcategorias", "La subcategoria #".$id." ha sido eliminada", $id , $query);
			
			echo 1;
		}else{
			echo 0;
		} 
	}
	
	function eliminarAmbientesPuente(){
		
		global $mysqli;
		
		$id  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		
		$query 	= "DELETE FROM ambientespuente WHERE id = ".$id.""; 
		
		$result = $mysqli->query($query);
		
		if($result==true){
			
			bitacora($_SESSION['usuario'], "Ubicaciones", "La ubicación #".$id." ha sido eliminada", $id , $query);
			
			echo 1;
		}else{
			echo 0;
		} 
	}
	
	
	function eliminarSubambientesPuente(){
		
		global $mysqli;
		
		$id  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		
		$query 	= "DELETE FROM subambientespuente WHERE id = ".$id.""; 
		
		$result = $mysqli->query($query);
		
		if($result==true){
			
			bitacora($_SESSION['usuario'], "Áreas", "El área #".$id." ha sido eliminada", $id , $query);
			
			echo 1;
		}else{
			echo 0;
		} 
	}
	
	function eliminarEstadosPuente(){
		
		global $mysqli;
		
		$id  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		
		$query 	= "DELETE FROM estadospuente WHERE id = ".$id.""; 
		
		$result = $mysqli->query($query);
		
		if($result==true){
			
			bitacora($_SESSION['usuario'], "Estados", "El estado #".$id." ha sido eliminado", $id , $query);
			
			echo 1;
		}else{
			echo 0;
		} 
	}	
	
	function eliminarDepartamentosPuente(){
		
		global $mysqli;
		
		$id  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		
		$query 	= "DELETE FROM departamentospuente WHERE id = ".$id.""; 
		
		$result = $mysqli->query($query);
		
		if($result==true){
			
			bitacora($_SESSION['usuario'], "Departamentos", "El departamento #".$id." ha sido eliminado", $id , $query);
			
			echo 1;
		}else{
			echo 0;
		} 
	} 
	
	function eliminarPrioridadesPuente(){
		
		global $mysqli;
		
		$id  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		
		$query 	= "DELETE FROM slapuente WHERE id = ".$id.""; 
		
		$result = $mysqli->query($query);
		
		if($result==true){
			
			bitacora($_SESSION['usuario'], "Prioridades", "La prioridad #".$id." ha sido eliminada", $id , $query);
			
			echo 1;
		}else{
			echo 0;
		} 
	} 
	
	function eliminarEtiquetasPuente(){
		
		global $mysqli;
		
		$id  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		
		$query 	= "DELETE FROM proyectosetiquetas WHERE id = ".$id.""; 
		
		$result = $mysqli->query($query);
		
		if($result==true){
			
			bitacora($_SESSION['usuario'], "Etiquetas", "La etiqueta #".$id." ha sido eliminada", $id , $query);
			
			echo 1;
		}else{
			echo 0;
		} 
	} 

	
	function cargarContactos(){
		global $mysqli;
		
		$where = "";
		$where2 = array();
		$id     = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		//contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);
		/*----------------------------------------------------------------------
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		//Obtener el nombre de la columna de clasificación de su índice
		$orderBy= (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ?$_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );
		//ASC or DESC*/
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); 
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		/*--------------------------------------------------------------------*/
		$query = " SELECT id, nombre, tlf_oficina, movil, email FROM proyectoscontactos WHERE idproyectos = ".$id." "; 
		//echo $query;
		$result = $mysqli->query($query);
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		$resultado = array();
		while($row = $result->fetch_assoc()){
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center droptable">
								        <a class="dropdown-item text-info boton-editar-contacto" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Editar</a>
										<a class="dropdown-item text-danger boton-eliminar-contacto" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
									</div>
								</div>
							</td>';
							
			$resultado[] = array(
				'id' 		=>	$row['id'],
				'acciones' 	=>	$acciones, 
				'nombre'	=>	$row['nombre'],
				'tlfoficina'=>	$row['tlf_oficina'],
				'movil'	 	=>	$row['movil'], 
				'email' 	=>	$row['email']
			);
		}
		
		$response = array(
			"draw" => intval($draw),
			"recordsTotal" => intval($recordsTotal),
			"recordsFiltered" => intval($recordsTotal),
			"data" => $resultado
		  ); 
		echo json_encode($response);
	}
	
	function createcontactos(){
		global $mysqli;
		
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : ''); 
		$nombre		 = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$tlf_oficina = (!empty($_REQUEST['tlf_oficina']) ? $_REQUEST['tlf_oficina'] : ''); 
		$movil	     = (!empty($_REQUEST['movil']) ? $_REQUEST['movil'] : '');
		$email       = (!empty($_REQUEST['email']) ? $_REQUEST['email'] : '');
		
		//Evitar duplicado
		$sql = "SELECT email FROM proyectoscontactos WHERE email = '".$email."' AND idproyectos = ".$idproyectos.""; 
		//echo $sql;
		$rsql = $mysqli->query($sql);
		if($rsql->num_rows > 0){
		    echo 2;
		}else{
		    $query 	= "	INSERT INTO	proyectoscontactos (idproyectos,nombre,tlf_oficina,movil,email)
    					VALUES (".$idproyectos.",'".$nombre."','".$tlf_oficina."','".$movil."','".$email."')";			
    		$result = $mysqli->query($query);
    		
    		if($result==true){
    	
    		    $idcontacto = $mysqli->insert_id;
    		    bitacora($_SESSION['usuario'], "Edición de proyectos", "El contacto #".$idcontacto." ha sido creado", $idcontacto, $query);
    			echo 1;
    		}else{
    			echo 0;
    		}
		}
	}
	
	function updatecontactos(){
		global $mysqli;
		
		$idcontactos = (!empty($_REQUEST['idcontactos']) ? $_REQUEST['idcontactos'] : ''); 
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : ''); 
		$nombre		 = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$tlf_oficina = (!empty($_REQUEST['tlf_oficina']) ? $_REQUEST['tlf_oficina'] : ''); 
		$movil	     = (!empty($_REQUEST['movil']) ? $_REQUEST['movil'] : '');
		$email       = (!empty($_REQUEST['email']) ? $_REQUEST['email'] : '');
		
		$query 	= "	UPDATE proyectoscontactos
		            SET nombre = '".$nombre."', 
    		            tlf_oficina = '".$tlf_oficina."', 
    		            movil = '".$movil."', 
    		            email = '".$email."'  
		            WHERE id = ".$idcontactos." AND idproyectos =".$idproyectos."";
		
		$result = $mysqli->query($query);	
		
		if($result==true){
		    
		    bitacora($_SESSION['usuario'], "Edición de proyectos", "El contacto #".$idcontactos." ha sido editado", $id , $query);
		    
	        echo 1;
	    }else{
	        echo 0;
	    }
	}
	
	function deletecontactos(){
		global $mysqli;
		
		$id 	= $_REQUEST['id'];
		$query 	= "DELETE FROM proyectoscontactos WHERE id = '$id'";
		$result = $mysqli->query($query);		
	    
	    if ($result==true){ 
	        
	        bitacora($_SESSION['usuario'], "Edición de proyectos", "El contacto #".$id." ha sido eliminado", $id , $query);
	        
	        echo 1;
	    }else{
	        echo 0;
	    }
	}
	
	function cargarContratos(){
		global $mysqli;
		
		$where = "";
		$where2 = array();
		$id     = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		//contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);
		/*----------------------------------------------------------------------
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		//Obtener el nombre de la columna de clasificación de su índice
		$orderBy= (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ?$_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );
		//ASC or DESC*/
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); 
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		/*--------------------------------------------------------------------*/
		$query = " SELECT id, idproyectos, tiposervicio, horascontratadas, fechainicio, fechafin, estado  FROM proyectoscontratos WHERE idproyectos = ".$id." ORDER BY id DESC "; 
		//echo $query;
		$result = $mysqli->query($query);
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		$resultado = array();
		while($row = $result->fetch_assoc()){
			$idproyectos = $row['idproyectos'];
			
			$respuesta = calcularHorasTrabajadas($idproyectos);
			$arr_resp  = explode("-",$respuesta);
			$horastrabajadas = $arr_resp[0];
			$horasrestantes = $arr_resp[1];
			$registros = $arr_resp[2];				 
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center droptable"> 
										<a class="dropdown-item text-info boton-editar-contrato" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Editar</a>
										<a class="dropdown-item text-danger boton-eliminar-contrato" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
									</div>
								</div>
							</td>';
							
			$resultado[] = array(
				'id' 			  =>	$row['id'],
				'acciones' 		  =>	$acciones, 
				'tiposervicio'	  =>	$row['tiposervicio'],
				'horascontratadas'=>	$row['horascontratadas'],
				'horastrabajadas' =>	"<span data-toggle='tooltip' data-placement='right' data-original-title='".$registros."'>".$horastrabajadas."</span>",
				'horasrestantes'  =>	$horasrestantes,						 
				'fechainicio'	  =>	$row['fechainicio'], 
				'fechafin' 		  =>	$row['fechafin'],
				'estado' 		  =>	$row['estado']				  
			);
		}
		
		$response = array(
			"draw" => intval($draw),
			"recordsTotal" => intval($recordsTotal),
			"recordsFiltered" => intval($recordsTotal),
			"data" => $resultado
		  ); 
		echo json_encode($response);
	}

	function createcontratos(){
		global $mysqli;
		
		$idproyectos 	  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : ''); 
		$tiposervicio	  = (!empty($_REQUEST['tiposervicio']) ? $_REQUEST['tiposervicio'] : '');
		$horascontratadas = (!empty($_REQUEST['horascontratadas']) ? $_REQUEST['horascontratadas'] : ''); 
		$fechainicio	  = (!empty($_REQUEST['fechainicio']) ? $_REQUEST['fechainicio'] : '');
		$fechafin         = (!empty($_REQUEST['fechafin']) ? $_REQUEST['fechafin'] : '');
		$estado 	      = (!empty($_REQUEST['estadocontrato']) ? $_REQUEST['estadocontrato'] : '');
		  
		$query 	= "	INSERT INTO	proyectoscontratos (idproyectos,tiposervicio,horascontratadas,fechainicio,fechafin,estado)
					VALUES (".$idproyectos.",'".$tiposervicio."','".$horascontratadas."','".$fechainicio."','".$fechafin."','".$estado."')";			
		$result = $mysqli->query($query);
		
		if($result==true){
	
			$idcontacto = $mysqli->insert_id;
			bitacora($_SESSION['usuario'], "Edición de proyectos", "El contrato #".$idcontacto." ha sido creado", $idcontacto, $query);
			echo 1;
		}else{
			echo 0;
		} 
	}
    
    function updatecontratos(){
		global $mysqli;
		
		$idcontratos 	  = (!empty($_REQUEST['idcontratos']) ? $_REQUEST['idcontratos'] : ''); 
    	$idproyectos 	  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : ''); 
		$tiposervicio	  = (!empty($_REQUEST['tiposervicio']) ? $_REQUEST['tiposervicio'] : '');
		$horascontratadas = (!empty($_REQUEST['horascontratadas']) ? $_REQUEST['horascontratadas'] : ''); 
		$fechainicio	  = (!empty($_REQUEST['fechainicio']) ? $_REQUEST['fechainicio'] : '');
		$fechafin         = (!empty($_REQUEST['fechafin']) ? $_REQUEST['fechafin'] : '');
		$estado 	      = (!empty($_REQUEST['estadocontrato']) ? $_REQUEST['estadocontrato'] : '');
		
		$query 	= "	UPDATE proyectoscontratos
		            SET tiposervicio = '".$tiposervicio."', 
    		            horascontratadas = '".$horascontratadas."', 
    		            fechainicio = '".$fechainicio."', 
    		            fechafin = '".$fechafin."',
						estado = '".$estado."'
		            WHERE id = ".$idcontratos." AND idproyectos =".$idproyectos."";
		
		$result = $mysqli->query($query);	
		
		if($result==true){
		    
		    bitacora($_SESSION['usuario'], "Edición de proyectos", "El contrato #".$idcontratos." ha sido editado", $id , $query);
		    
	        echo 1;
	    }else{
	        echo 0;
	    }
	}
	
	function deletecontratos(){
		global $mysqli;
		
		$id 	= $_REQUEST['id'];
		$query 	= "DELETE FROM proyectoscontratos WHERE id = '$id'";
		//echo $query;
		$result = $mysqli->query($query);		
	    
	    if ($result==true){ 
	        
	        bitacora($_SESSION['usuario'], "Edición de proyectos", "El contrato #".$id." ha sido eliminado", $id , $query);
	        
	        echo 1;
	    }else{
	        echo 0;
	    }
	}
	
	function hayNotificaciones(){
		global $mysqli;
		 
		$query 	= " SELECT id, idproyectos, DATEDIFF(fechafin,CURDATE()) AS dias FROM proyectoscontratos WHERE estado = 'Activo' AND fechafin >= CURDATE() GROUP BY id HAVING dias = 30 ORDER BY id ASC "; 
		$result = $mysqli->query($query);		
	    
	    $total = $result->num_rows; 
	    if($total > 0){  
		 
	        //Usuarios de soporte
			$idusuarios["icarvajal"] = "0";
			$idusuarios["frios"] = "0"; 	 
			$idusuarios["aanderson"] = "0";  
			$idusuarios["admin"] = "0";
			
	        $usuarios = json_encode($idusuarios);
								
	        while($row = $result->fetch_assoc()){ 
			
	            $idproyectos = $row['idproyectos'];  
				
	            $v = " SELECT idproyectos,descripcion FROM proyectosnotificaciones WHERE idproyectos = '".$idproyectos."' AND tipo = 'Fin de proyecto' AND fecha = '". date("Y-m-d") ."'"; 
	            $rv = $mysqli->query($v); 
	            $total = $rv->num_rows; 

	            if($total == '0'){
	                
	                 $sql = " INSERT INTO proyectosnotificaciones (idproyectos,tipo,descripcion,fecha,hora,usuarios) VALUES (".$idproyectos.",'Fin de proyecto','Falta un mes para que finalice el proyecto ".$idproyectos."','". date("Y-m-d") ."','". date("H:i:s") ."','".$usuarios."')"; 
							   
	                    $rsql = $mysqli->query($sql); 
	            }  
	        }  			   
	    }else{
	        echo 0;
	    }
	}
		
 	function updatenotificaciones(){
		global $mysqli;
		
		$id 	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$usuario = $_SESSION['usuario'];
		
		$query = " UPDATE proyectosnotificaciones SET usuarios = JSON_REPLACE(usuarios,'$.".$usuario."','1') WHERE id = ".$id."";
		//echo $query;
		$result = $mysqli->query($query);
		$result == true ? $response = 1 : $response = 0;
		
		echo $response;
	}
	
	function deletenotificaciones(){
		global $mysqli;
		 
		$id 	 	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : ''); 
		$tipo 	 	 = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : ''); 
		$usuario 	 = $_SESSION['usuario'];
		
		$query = " UPDATE proyectosnotificaciones SET usuarios = JSON_REMOVE(usuarios,'$.".$usuario."') WHERE id = ".$id."";
		//echo $query;
		$result = $mysqli->query($query);
		$result == true ? $response = 1 : $response = 0;
		
		echo $response;
	}
	
		function getnotificaciones(){
	    global $mysqli;
	    
	    $usuario = str_replace('.','',$_SESSION['usuario']);
	    $notific = "1"; 
		$el = '';						
	    $query = "  SELECT id, idproyectos, idmodulo, tipo, descripcion, fecha, hora, JSON_EXTRACT(REPLACE(usuarios,'.',''),'$.".$usuario."') AS existe    
	                FROM proyectosnotificaciones 
	                WHERE 1 ORDER BY id DESC LIMIT 10";  
	    $result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			 
			$id			  = $row["id"];
			$idproyectos  = $row["idproyectos"];
			$idmodulo 	  = $row["idmodulo"];
			$tipo 		  = $row["tipo"];
			$descripcion  = $row["descripcion"];
			$fecha        = $row["fecha"];
			$hora         = $row["hora"];
			$existe       = $row["existe"];  
			
			if($existe == '"0"') $notific = "0";  
			$existe == '"1"' ? $classfont = '': $classfont = 'font-w600';
			$existe == '"1"' ? $styleview = 'style="color: #cbcadb !important;"' : $styleview = '';
			if($tipo == 'Cambio de estado correctivo') $descripcion = "El estado del correctivo # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span> ha cambiado de ".$descripcion;
			if($tipo == 'Cambio de estado preventivo') $descripcion = "El estado del preventivo # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span> ha cambiado de ".$descripcion;
			if($tipo == 'Cambio de estado postventa') $descripcion = "El estado de la postventa # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span> ha cambiado de ".$descripcion;
			if($tipo == 'Cambio de estado flota') $descripcion = "El estado de la flota # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span> ha cambiado de ".$descripcion;
			if($tipo == 'Cambio de estado laboratorio') $descripcion = "El estado del laboratorio # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span> ha cambiado de ".$descripcion;
			
			if($tipo == 'Comentario realizado correctivo') $descripcion = "Fue realizado un comentario en el correctivo # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span>";
			if($tipo == 'Comentario realizado preventivo') $descripcion = "Fue realizado un comentario en el preventivo # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span>"; 
			if($tipo == 'Compromiso realizado postventa') $descripcion = "Fue creado un compromiso en la postventa # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span>"; 
			if($tipo == 'Comentario realizado laboratorio') $descripcion = "Fue realizado un comentario en laboratorio # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span>"; 
			if($tipo == 'Comentario realizado flota') $descripcion = "Fue realizado un comentario en flota # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span>"; 
			
			if($tipo == 'Adjunto realizado correctivo') $descripcion = "Fue agregado un adjunto en el correctivo # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span>"; 
			if($tipo == 'Adjunto realizado preventivo') $descripcion = "Fue agregado un adjunto en el preventivo # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span>"; 
			if($tipo == 'Adjunto realizado postventa') $descripcion = "Fue agregado un adjunto en la postventa # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span>";
			if($tipo == 'Adjunto realizado laboratorio') $descripcion = "Fue agregado un adjunto en laboratorio # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span>";
			if($tipo == 'Adjunto realizado flota') $descripcion = "Fue agregado un adjunto en flota # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span>";
			
			if($tipo == 'Fuera de servicio') $descripcion = "El correctivo # <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$idmodulo."</span> ha sido actualizado a <span class='".$classfont." fs-12 text-black'  ".$styleview.">Fuera de servicio</span>";
			if($tipo == 'Fin de proyecto'){
				$sql = " SELECT nombre FROM proyectos WHERE id = ".$idproyectos."";
				$rsql = $mysqli->query($sql);
				if($reg = $rsql->fetch_assoc()){
					$proyecto    = $reg["nombre"];
					$descripcion = "Falta un mes para que finalice el proyecto <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$proyecto."</span>";
				}
			}
			if($tipo == 'Fin de horas contratadas'){
				$sql = " SELECT nombre FROM proyectos WHERE id = ".$idproyectos."";
				$rsql = $mysqli->query($sql);
				if($reg = $rsql->fetch_assoc()){
					$proyecto    = $reg["nombre"];
					$descripcion = "Las horas contratadas del proyecto <span class='".$classfont." fs-12 text-black'  ".$styleview.">".$proyecto."</span> están por finalizar";
				}
			}
			
			
			if($existe != ""){
				 
					$el .= '<li  class="p-3" style="border-bottom: 1px solid #eee;cursor: pointer;">
								<div class="d-flex bd-highlight">
									<div>
										<span class="fs-12 text-black" '.$styleview.'>'.$descripcion.'</span>
										<p class="m-0 fs-12" '.$styleview.'>'.$fecha.'-'.$hora.'</p>
									</div>
									<div class="ml-auto">
										<a class="btn btn-primary btn-xs sharp mr-1 ir-enlace" data-id="'.$id.'" data-idproyectos="'.$idproyectos.'" data-idmodulo="'.$idmodulo.'" data-tipo="'.$tipo.'" style="border-radius:13px !important"><i class="fa fa-link"></i></a>
										<a class="btn btn-danger btn-xs sharp eliminar-notificacion" data-id="'.$id.'" data-idproyectos="'.$idproyectos.'" data-idmodulo="'.$idmodulo.'" data-tipo="'.$tipo.'" style="border-radius:13px !important"><i class="fa fa-trash" ></i></a>
									</div>
								</div>
							</li>'; 
					 
			} 	        
		} 
	    
		if($el == ""){
			$el .= '<li  class="p-3">
						<div class="d-flex bd-highlight">
							<div>
								<span class="font-w600 text-black">No hay notificaciones pendientes</span> 
							</div>
							<div class="ml-auto"> 
							</div>
						</div>
					</li>';
		}
		
		$response = array( 
		        'notific'  => $notific,
		        'dropdown' => $el
		    );	
		echo json_encode($response);
	}
	
	function verHorasContratadas(){
		global $mysqli;
		$nrohoras = 0;
		$nrominut = 0;
	
		$sqlPC = "	SELECT id, idproyectos, tiposervicio, horascontratadas, fechainicio, fechafin FROM proyectoscontratos WHERE estado = 'Activo' ";
		$resultPC = $mysqli->query($sqlPC);
	    while($rowPC = $resultPC->fetch_assoc()){
			
			$idcontratos 	  = $rowPC["id"];
			$idproyectos 	  = $rowPC["idproyectos"];
			$tiposervicio 	  = $rowPC["tiposervicio"];
			$horascontratadas = $rowPC["horascontratadas"];
			$fechainicio 	  = $rowPC["fechainicio"];
			$fechafin 		  = $rowPC["fechafin"];
			 
			$pos_corr = strrpos($tiposervicio, 'correctivos'); 
			if ($pos_corr !== false) {
				$hor_corr = horasTrabajadas('incidentes',$idproyectos,$horascontratadas,$fechainicio,$fechafin);
			}else{
				$hor_corr = 0;
			}
			$pos_prev = strrpos($tiposervicio, 'preventivos'); 
			if ($pos_prev !== false) {
				$hor_prev = horasTrabajadas('preventivos',$idproyectos,$horascontratadas,$fechainicio,$fechafin);
			}else{
				$hor_prev = 0;
			}
			$pos_post = strrpos($tiposervicio, 'postventas'); 
			if ($pos_post !== false) {
				$hor_post = horasTrabajadas('postventas',$idproyectos,$horascontratadas,$fechainicio,$fechafin);
			}else{
				$hor_post = 0;
			}
			//echo "HORAS CORRECTIVOS ES: ".$hor_corr."<br>";
			//echo "HORAS PREVENTIVOS ES: ".$hor_prev."<br>";
			//echo "HORAS POSTVENTAS ES: ".$hor_post."<br>";
			$totalFinal = $hor_corr + $hor_prev + $hor_post; 
			
			$horastrabajadas = $totalFinal;
			if($horastrabajadas != 0){ 
				$restoHorastrabajadas = $horascontratadas - $horastrabajadas;
			}else{
				$restoHorastrabajadas = 0;
			} 
			
			//echo "EL RESTO ES:".$restoHorastrabajadas."<BR>";
			//echo "EL horascontratadas ES:".$horascontratadas."<BR>";
			//echo "EL horastrabajadas ES:".$horastrabajadas."<BR>";
			if($restoHorastrabajadas <= 10 && $horascontratadas != 0 && $horastrabajadas != 0){ 
				
				//Usuarios de soporte
				$idusuarios["icarvajal"] = "0";
				$idusuarios["frios"] = "0";
				$idusuarios["aanderson"] = "0";
				$idusuarios["admin"] = "0";
				
				$usuarios = json_encode($idusuarios); 
				
				$v = " SELECT idproyectos,descripcion FROM proyectosnotificaciones WHERE idproyectos = ".$idproyectos." AND tipo = 'Fin de horas contratadas' AND  fecha = '". date("Y-m-d") ."'"; 
				$rv = $mysqli->query($v); 
	            $total = $rv->num_rows; 

	            if($total == '0'){
					$sql = " INSERT INTO proyectosnotificaciones (idproyectos,tipo,descripcion,fecha,hora,usuarios) VALUES (".$idproyectos.",'Fin de horas contratadas','Las horas contratadas del proyecto #".$idproyectos." están por finalizar','". date("Y-m-d") ."','". date("H:i:s") ."','".$usuarios."')"; 
	                $rsql = $mysqli->query($sql);
				} 
			} 
		} 
	}
	
	function horasTrabajadas($modulo,$idproyectos,$horascontratadas,$fechainicio,$fechafin){
		global $mysqli;
		$nrohoras = 0;
		$nrominut = 0;
		$registros = '';
		
		if($modulo == 'postventas'){
			$tabla = 'postventas';
		}else{
			$tabla = 'incidentes';
		}
		 
		$sqlInc = " SELECT id,horastrabajadas FROM ".$tabla." WHERE idestados = '16' AND fechacreacion >= '".$fechainicio."' AND fechacreacion <= '".$fechafin."' AND MONTH(fechacreacion) = MONTH(CURRENT_DATE()) AND horastrabajadas != 0 AND idproyectos = ".$idproyectos." ";
		//echo $sqlInc."<br>";
		if($modulo == 'incidentes'){
			$sqlInc .= " AND tipo = 'incidentes'";
		}
		if($modulo == 'preventivos'){
			$sqlInc .= " AND tipo = 'preventivos'";
		}  
		$resultInc = $mysqli->query($sqlInc);
		
		if($numreg = $resultInc->num_rows > 0){
			while($rowInc = $resultInc->fetch_assoc()){
				
				$registros .= $rowInc['id'].",";
				
				$strHorastrabajadas = $rowInc["horastrabajadas"]; 
				$pos = strpos($strHorastrabajadas, ":"); 
				if ($pos === false) { 
					$nrohoras +=  $strHorastrabajadas;
					$nrominut += 0;
				} else {
					$arrhoras = explode(":",$strHorastrabajadas);
					$nrohoras += $arrhoras[0];
					$nrominut += $arrhoras[1];
				}   
				//Ajustar minutos a horas - Horas Trabajadas
				$mindiv = $nrominut / 60;
				$minutf = $nrominut - (intval($mindiv) * 60); 
				$minutf = str_pad($minutf, 2, "0", STR_PAD_LEFT);
				
				$horasf = $nrohoras + intval($mindiv);
				$totalFinal = $horasf; 
			}
		}else{
			$totalFinal = 0;
		}  
		$registros = rtrim($registros, ",");
		return $totalFinal."-".$registros;
	}
	
	function calcularHorasTrabajadas($idproyectos){
		global $mysqli;
		$nrohoras		= 0;
		$nrominut 		= 0;
		$totalregistros = "";
		
		$sqlPC = "	SELECT id, tiposervicio, horascontratadas, fechainicio, fechafin FROM proyectoscontratos WHERE idproyectos = ".$idproyectos." AND estado = 'Activo' ";
		$resultPC = $mysqli->query($sqlPC);
	    while($rowPC = $resultPC->fetch_assoc()){
			
			$idcontratos 	  = $rowPC["id"]; 
			$tiposervicio 	  = $rowPC["tiposervicio"];
			$horascontratadas = $rowPC["horascontratadas"];
			$fechainicio 	  = $rowPC["fechainicio"];
			$fechafin 		  = $rowPC["fechafin"];
			 //echo "tiposervicio es:".$tiposervicio;
			$pos_corr = strpos($tiposervicio, 'correctivos'); 
			if ($pos_corr !== false) {
				$resp_corr = horasTrabajadas('incidentes',$idproyectos,$horascontratadas,$fechainicio,$fechafin);
				$arr_corr  = explode("-",$resp_corr);
				$hor_corr = $arr_corr[0];
				$reg_corr = $arr_corr[1];
			}else{
				$hor_corr = 0;
			}
			$pos_prev = strpos($tiposervicio, 'preventivos'); 
			if ($pos_prev !== false) {
				$resp_prev = horasTrabajadas('preventivos',$idproyectos,$horascontratadas,$fechainicio,$fechafin);
				$arr_prev  = explode("-",$resp_prev);
				$hor_prev = $arr_prev[0];
				$reg_prev = $arr_prev[1];
			}else{
				$hor_prev = 0;
			}
			$pos_post = strpos($tiposervicio, 'postventas'); 
			if ($pos_post !== false) { 
				$resp_post = horasTrabajadas('postventas',$idproyectos,$horascontratadas,$fechainicio,$fechafin);
				$arr_post  = explode("-",$resp_post);
				$hor_post = $arr_post[0];
				$reg_post = $arr_post[1];
			}else{ 
				$hor_post = 0;
			} 
			$totalFinal = $hor_corr + $hor_prev + $hor_post; 
			if($reg_corr != "") $totalregistros .= " Correctivos: (".$reg_corr."),";
			if($reg_prev != "") $totalregistros .= " Preventivos: (".$reg_prev."),";
			if($reg_post != "") $totalregistros .= " Postventas: (".$reg_post."),"; 
			$totalregistros = rtrim($totalregistros,",");
			
			$horastrabajadas = $totalFinal;
			if($horastrabajadas != 0){ 
				$restoHorastrabajadas = $horascontratadas - $horastrabajadas;
			}else{
				$restoHorastrabajadas = 0;
			} 
			
			return $horastrabajadas."-".$restoHorastrabajadas."-".$totalregistros;
			//echo "EL RESTO ES:".$restoHorastrabajadas."<BR>";
			//echo "EL horascontratadas ES:".$horascontratadas."<BR>";
			//echo "EL horastrabajadas ES:".$horastrabajadas."<BR>"; 
		} 
	}
	
	function getetiquetas(){
		global $mysqli;
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');  
		
		$query  = " SELECT a.id, a.nombre, b.id AS idcolores, b.valor, c.idproyectos
					FROM etiquetas a 
					INNER JOIN colores b ON b.id = a.idcolores 
                    LEFT JOIN proyectosetiquetas c ON c.idetiquetas = a.id AND c.idproyectos = ".$idproyectos." ";		
					
		$result = $mysqli->query($query); 
		
		$arrEtq = array();
		while($row = $result->fetch_assoc()){
			$existe = $row['idproyectos'];
			$existe != "" ? $check = true : $check = false;
			$arrEtq[] = array(  "id" => $row['id'], "idcolores" => $row['idcolores'], "valor" => $row['valor'], "nombre" => $row['nombre'], "check" => $check );
		}
		echo json_encode($arrEtq);
	}
	
	function asociaretiquetas(){
		global $mysqli;
		 
		$idclientes  = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : ''); 
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : ''); 
		$etiquetas 	 = (!empty($_REQUEST['etiquetas']) ? $_REQUEST['etiquetas'] : ''); 
		$response 	 = 1;
		
		if(is_array($etiquetas)){
			foreach ($etiquetas as $etiq){ 
				//Evitar duplicado
				$sql = " SELECT id FROM proyectosetiquetas WHERE idclientes = ".$idclientes." AND idproyectos = ".$idproyectos." AND idetiquetas =".$etiq."";
				$rsql = $mysqli->query($sql);
				if($rsql->num_rows > 0){
					//Sí existe en bd y en array --> Mantener
				}else{
					//No existe en bd y sí existe en array --> Agregar
					$query 	= "	INSERT INTO	proyectosetiquetas (idclientes,idproyectos,idetiquetas)
								VALUES (".$idclientes.",".$idproyectos.",".$etiq.")";
					$result = $mysqli->query($query); 
					if (!$result) {
						$response = 0;
					}
				}				
			}
		}else{
			if($etiquetas != ""){
				//Evitar duplicado 
				$sql = " SELECT id FROM proyectosetiquetas WHERE idclientes = ".$idclientes." AND idproyectos = ".$idproyectos." AND idetiquetas =".$etiq."";
				$rsql = $mysqli->query($sql);
				if($rsql->num_rows > 0){
					//Existe en bd y en array --> Mantener
				}else{
					//No existe en bd y sí existe en array --> Agregar
					$query 	= "	INSERT INTO	proyectosetiquetas (idclientes,idproyectos,idetiquetas)
								VALUES (".$idclientes.",".$idproyectos.",".$etiq.")";
					$result = $mysqli->query($query);
					if (!$result) {
						$response = 0;
					}
				} 
			}
		} 
		/* if(is_array($etiquetas)){
			foreach ($etiquetas as $etiq){
				 */
				$sqlDel = " SELECT idetiquetas FROM proyectosetiquetas WHERE idclientes = ".$idclientes." AND idproyectos = ".$idproyectos."";
				$rsqlDel = $mysqli->query($sqlDel); 
				if($rsqlDel->num_rows > 0){
					while($reg = $rsqlDel->fetch_assoc()){
						
						$idetiquetasbd = $reg["idetiquetas"];
						if(is_array($etiquetas)){
							if (in_array($idetiquetasbd, $etiquetas)) {
							}else{
								//Si existe en bd y no existe en array --> Eliminar
								$delE = " DELETE FROM proyectosetiquetas WHERE idclientes = ".$idclientes." AND idproyectos = ".$idproyectos." AND idetiquetas = ".$idetiquetasbd."";
								$mysqli->query($delE);
							} 
						}else{
							
						} 
					}
				} 
		/* 	}	
		}else{
			if($etiquetas != ""){
				$sqlDel = " SELECT idetiquetas FROM proyectosetiquetas WHERE idclientes = ".$idclientes." AND idproyectos = ".$idproyectos." AND idetiquetas = ".$idetiquetas."";
				$rsqlDel = $mysqli->query($sqlDel); 
				if($rsqlDel->num_rows > 0){
				}else{ 
					//Si existe en bd y no existe en array --> Eliminar
					$delE = " DELETE FROM proyectosetiquetas WHERE idclientes = ".$idclientes." AND idproyectos = ".$idproyectos." AND idetiquetas = ".$idetiquetas."";
					$mysqli->query($delE);
				}  
			}
		} */ 
		echo $response;
	}
	
	function editarTipoCategorias(){
		global $mysqli;
		
		$idcategorias 		= (!empty($_REQUEST['idcategorias']) ? $_REQUEST['idcategorias'] : ''); 
		$idcategoriaspuente = (!empty($_REQUEST['idcategoriaspuente']) ? $_REQUEST['idcategoriaspuente'] : ''); 
		$tipo		 		= (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : ''); 
		
		$query 	= "	UPDATE categoriaspuente
		            SET tipo = '".$tipo."'
		            WHERE idcategorias = ".$idcategorias." AND id =".$idcategoriaspuente."";
		
		$result = $mysqli->query($query);	
		
		if($result==true){
		    
		    bitacora($_SESSION['usuario'], "Proyectos", "El módulo de la categoría #".$idcategoriaspuente." ha sido editado", $idcategoriaspuente , $query);
		    
	        echo 1;
	    }else{
	        echo 0;
	    }
	}	
	
	function editarTipoEstados(){
		global $mysqli;
		
		$idestados 		 = (!empty($_REQUEST['idestados']) ? $_REQUEST['idestados'] : ''); 
		$idestadospuente = (!empty($_REQUEST['idestadospuente']) ? $_REQUEST['idestadospuente'] : ''); 
		$tipo		 	 = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : ''); 
		$descripcion	 = (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : ''); 
		
		$query 	= "	UPDATE 
						estadospuente
		            SET 
						tipo = '".$tipo."', 
						descripcion = '".$descripcion."'
		            WHERE 
						idestados = ".$idestados." 
						AND id =".$idestadospuente."";
		
		$result = $mysqli->query($query);	
		
		if($result==true){
		    
		    bitacora($_SESSION['usuario'], "Proyectos", "El módulo del estado #".$idestadospuente." ha sido editado", $idestadospuente , $query);
		    
	        echo 1;
	    }else{
	        echo 0;
	    }
	}
?>