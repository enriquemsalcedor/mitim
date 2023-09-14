<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){
		case "cargardepartamentos": 
			  cargardepartamentos();
			  break;
		case "getdepartamentos": 
			  getdepartamentos();
			  break;
		case "createdepartamentos": 
			  createdepartamentos();
			  break;
		case "updatedepartamentos": 
			  updatedepartamentos();
			  break;
		case "deletedepartamentos": 
			  deletedepartamentos();
			  break;
		case "existedepartamento":
			  existedepartamento();
			  break; 
		case "hayRelacion":
			  hayRelacion();
			  break;
		case "cargardepartamentosclientes":
			  cargardepartamentosclientes();
			  break;
		case "asociardepartamentosclientes":
			  asociardepartamentosclientes();
			  break;
		case "eliminardepartamentosclientes":
			  eliminardepartamentosclientes();
			  break;
		case "hayRelacionPc":
			  hayRelacionPc();
			  break;

		default:
			  echo "{failure:true}";
			  break;
	}	
	
	function cargardepartamentos(){
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
		$nivel 			     = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0); 
		$iddepartamentos 	 = (!empty($_SESSION['iddepartamentos']) ? $_SESSION['iddepartamentos'] : 0);
		
		$query = " SELECT a.id, a.nombre, a.descripcion, a.tipo
				   FROM departamentos a ";
				   
		if($nivel == 4 || $nivel == 7){  
			$query  .= " INNER JOIN usuarios b ON a.id IN(b.iddepartamentos)";
			
			if($iddepartamentos != ''){
				$arr = strpos($iddepartamentos, ',');
				if ($arr !== false) {
					$query  .= " AND b.iddepartamentos IN (".$iddepartamentos.") ";
				}else{
					$query  .= " AND find_in_set(".$iddepartamentos.",b.iddepartamentos) ";
				} 				
			}
			$query .= " AND b.nivel = ".$nivel."";
		}
        /*----------------------------------------------------------------------
        $hayFiltros = 0;
		for($i=0 ; $i<count($_REQUEST['columns']);$i++){
			$column = $_REQUEST['columns'][$i]['data'];
			
			if ($_REQUEST['columns'][$i]['search']['value']!="") {
			    
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);

				if ($column == 'id') {
					$column = 'a.id';
    				$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'nombre') {
					$column = 'a.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'descripcion') {
					$column = 'a.descripcion';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'tipo') {
					$column = 'a.tipo';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				
				$hayFiltros++;
			}
		}
        $query .= " WHERE a.id!=0 ";
		if ($hayFiltros > 0)
			$where = " AND ".implode(" AND " , $where2)." ";

		$searchGeneral= (!empty($_POST['search']['value']) ? $_POST['search']['value'] : '');		
		
		if($searchGeneral != ''){
			$where.= " AND (
			a.id like '%".$searchGeneral."%' OR
			a.nombre like '%".$searchGeneral."%' OR
            a.descripcion like '%".$searchGeneral."%' OR
            a.tipo like '%".$searchGeneral."%'
			)";
    	}
        
		$query  .= " $where ";*/
		debugL($query,"departamentos");
	    $query .= " GROUP BY a.id ";
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		
		$query  .= " ORDER BY a.id ASC";
		
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		$response = array();
		 
		while($row = $result->fetch_assoc()){	

			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center droptable">

						';


			$btnVer = '<a class="dropdown-item text-warning" href="departamento.php?id='.$row['id'].'&type=view"><i class="fas fa-eye mr-2"></i>Ver</a>';

			$btnEditar = '<a class="dropdown-item text-info" href="departamento.php?id='.$row['id'].'&type=edit"><i class="fas fa-pen mr-2"></i>Editar</a>';
			
			$btnAsociar = '<a class="dropdown-item text-info" href="departamentorel.php?id='.$row['id'].'"><i class="fas fa-link mr-2"></i>Asociar</a>';

			$btnEliminar='<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>';


    			if($nivel==4 || $nivel==7){
    				$acciones.=$btnVer;
    			}else{
    				$acciones.=$btnEditar;
    				$acciones.=$btnEliminar;
    			}

		    $acciones.='
								</div>
							</div>
						</td>';


			$resultado[] = array(
				'id' 						=>	$row['id'],
				'acciones' 					=>	$acciones, 
				'nombre'			 		=>	$row['nombre'], 
				'descripcion'			 	=>	$row['descripcion'], 
				'clientes'    => "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['clientestt']."'>".$row['clientes']."</span>",
				'proyectos' 	=> "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['proyectostt']."'>".$row['proyectos']."</span>",
				'tipo' 			         	=>	ucfirst($row['tipo']),
				
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
	
	
	function getdepartamentos(){
		global $mysqli;
		
		$id 	= (!empty($_REQUEST['iddepartamentos']) ? $_REQUEST['iddepartamentos'] : 0);
		$query 	= "	SELECT *
					FROM departamentos
					WHERE id = ".$id."";
					
		$result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			
			$resultado = array(  
				'nombre'			=>	$row['nombre'],
				'descripcion'	 	=>	$row['descripcion'], 	
				'tipo' 				=>	$row['tipo'] 	
			);
		}
		
		if($result==true) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}	
	
	function hayRelacion(){
	    global $mysqli;
	    
	    $id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
	    
	    $existe_correctivo	= 0;
	    $existe_preventivo	= 0;
	    $existe_laboratorio = 0;
	    $existe_postventa = 0;


	    $qcorr = "SELECT 
						incidentes.id
					FROM incidentes 
					WHERE incidentes.iddepartamentos = $id  AND incidentes.tipo = 'incidentes' LIMIT 1;";

        $rQcorr = $mysqli->query($qcorr);
		if($rQcorr->num_rows > 0){ 
            $existe_correctivo = 1; 
        }

	    $qprev = "SELECT 
						incidentes.id
					FROM incidentes
					WHERE incidentes.iddepartamentos = $id AND incidentes.tipo = 'preventivos' LIMIT 1;";

        $rQprev = $mysqli->query($qprev);
		if($rQprev->num_rows > 0){ 
            $existe_preventivo = 1; 
        }
 
	    $qlab = "SELECT 
						laboratorio.id
					FROM laboratorio 
					WHERE laboratorio.iddepartamentos = $id LIMIT 1;";

        $rQlab = $mysqli->query($qlab);
		if($rQlab->num_rows > 0){ 
            $existe_laboratorio = 1; 
        }
 
	    $qpost= "SELECT 
					postventas.id
				FROM postventas 
				WHERE postventas.iddepartamentos = $id LIMIT 1;"; 

        $rQpost = $mysqli->query($qpost);
		if($rQpost->num_rows > 0){ 
            $existe_postventa = 1; 
        }
  
		if(
			($existe_correctivo  == 1) ||
			($existe_preventivo  == 1) ||
			($existe_laboratorio == 1) ||
			($existe_postventa 	 == 1)
		){
			echo 1;
		}else{
			echo 0;
		}
	}

	
	function deletedepartamentos(){
		global $mysqli;
		
		$id 	= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
		$query 	= "DELETE FROM departamentos WHERE id = '$id'";
		$result = $mysqli->query($query);		
		if($result==true){
		    bitacora($_SESSION['usuario'], "Departamentos", "El departamento #".$id." ha sido eliminado", $id , $query);
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function updatedepartamentos(){
		global $mysqli;
		
		$id 				= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0); 
		$nombre				= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$descripcion		= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');  
		//$idempresas			= (!empty($_REQUEST['idempresas']) ? $_REQUEST['idempresas'] : ''); 
		$tipo				= (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');
		
		$query 	= "	UPDATE departamentos SET nombre = '$nombre', descripcion = '$descripcion', tipo = '$tipo' 
					WHERE id = '$id'";
		$result = $mysqli->query($query);	
		
		if($result==true){
		   
		    bitacora($_SESSION['usuario'], "Departamentos", "El departamento #".$id." ha sido editado", $id , $query);
			
			echo 1; 
		    
		}else{
			echo 0;
		}
	}
	
	function createdepartamentos(){
		global $mysqli;
		
		$nombre				= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$descripcion		= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : ''); 
		$tipo	        	= (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');
		
		//Evitar duplicado
		$bCat = " SELECT nombre FROM departamentos WHERE nombre = '".$nombre."'";
		$rNom = $mysqli->query($bCat); 
		
		if($rNom->num_rows > 0){
			echo 2;
		}else{
			$query 	= "	INSERT INTO	departamentos (nombre,descripcion,idempresas,tipo)
						VALUES ('".$nombre."','".$descripcion."',1,'".$tipo."')";
			$result = $mysqli->query($query);
			$iddepa = $mysqli->insert_id;
			
			if($result==true){ 
				
				bitacora($_SESSION['usuario'], "Departamentos", "El departamento #".$iddepa." ha sido creado", $iddepa, $query);
				
				echo 1;
				
			}else{
				echo 0;
			}
		}	 
	}
	
	function cargardepartamentosclientes(){
		
		global $mysqli; 
		$iddepartamento  = (!empty($_REQUEST['iddepartamento']) ? $_REQUEST['iddepartamento'] : 0);
		
		$query = " 	SELECT a.id, b.nombre, a.idclientes, a.idproyectos, a.iddepartamentos, c.nombre AS cliente, d.nombre AS proyecto
					FROM departamentospuente a 
					INNER JOIN departamentos b ON b.id = a.iddepartamentos
					INNER JOIN clientes c ON c.id = a.idclientes
					INNER JOIN proyectos d ON d.id = a.idproyectos
					WHERE b.id = ".$iddepartamento." ORDER BY c.nombre, d.nombre, b.nombre ASC ";
					//echo $query;
		$result = $mysqli->query($query);
		$resultado = array();
		while($row = $result->fetch_assoc()){
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center"> 
									<a class="dropdown-item text-danger boton-eliminar" data-idcliente="'.$row['idcliente'].'" data-idproyecto="'.$row['idproyecto'].'" data-iddepartamento="'.$row['iddepartamento'].'" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
									</div>
								</div>
							</td>';
							
			$resultado[] = array(
				'id' 		=>	$row['id'],
				'acciones' 	=>	$acciones, 
				'nombre'	=>	$row['nombre'],  
				'cliente'	=>	$row['cliente'], 
				'proyecto'	=>	$row['proyecto'] 
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
	
	function asociardepartamentosclientes(){
		global $mysqli;		 
		
		$id 		  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idcliente 	  = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : '');
		$idproyecto   = (!empty($_REQUEST['idproyecto']) ? $_REQUEST['idproyecto'] : ''); 
		$usuario	  = $_SESSION['usuario'];
		
		$sql = " SELECT id FROM departamentospuente 
				 WHERE 
				 idclientes = ".$idcliente." 
				 AND idproyectos = ".$idproyecto." 
				 AND iddepartamentos = ".$id."";
		//echo $sql;
		$rsql = $mysqli->query($sql);
		
		//Evitar duplicado
		if($rsql->num_rows > 0){
			echo 2;
		}else{ 
			$query 	= "	INSERT INTO	departamentospuente (idclientes, idproyectos, iddepartamentos, fechacreacion, usuario) 
						VALUES (".$idcliente.", ".$idproyecto.", ".$id.", NOW(), '".$usuario."')";
					
			$result = $mysqli->query($query);
			$idcate = $mysqli->insert_id;
			$result == true ? $respuesta = 1 : $respuesta = 0;
			echo $respuesta;
		} 
	} 
	
	function hayRelacionPc(){
		global $mysqli;
		
		$id   		  	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idcliente    	 = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : '');
		$idproyecto   	 = (!empty($_REQUEST['idproyecto']) ? $_REQUEST['idproyecto'] : '');
		$iddepartamento  = (!empty($_REQUEST['iddepartamento']) ? $_REQUEST['iddepartamento'] : ''); 
		
		$existe_correctivo	= 0;
		$existe_laboratorio = 0;
	    $existe_postventa 	= 0;
		
		$qcorr = "SELECT id FROM incidentes WHERE
					idclientes = ".$idcliente."
					AND idproyectos = ".$idproyecto."
					AND iddepartamentos = ".$iddepartamento." LIMIT 1";

        $rQcorr = $mysqli->query($qcorr);
		if($rQcorr->num_rows > 0){ 
            $existe_correctivo = 1; 
        }
		
		$qlab = "SELECT id FROM laboratorio WHERE
					idclientes = ".$idcliente."
					AND idproyectos = ".$idproyecto."
					AND iddepartamentos = ".$iddepartamento." LIMIT 1";

        $rQlab = $mysqli->query($qcorr);
		if($rQlab->num_rows > 0){ 
            $existe_laboratorio = 1; 
        }
		
		$qPv = "SELECT id FROM postventas WHERE
					idclientes = ".$idcliente."
					AND idproyectos = ".$idproyecto."
					AND iddepartamentos = ".$iddepartamento." LIMIT 1";

        $rqPv = $mysqli->query($qPv);
		if($rqPv->num_rows > 0){ 
            $existe_postventa = 1; 
        }
		
		($existe_correctivo  == 1) ||
			($existe_preventivo  == 1) ||
			($existe_laboratorio == 1) ||
			($existe_postventa 	 == 1) ?
			$respuesta = 1 : $respuesta = 0;
			echo $respuesta;
	}
	 
?>