<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "estados": 
			  estados();
			  break;		
		case "createestado":
			  createestado();
			  break;
		case "updateestado":
			  updateestado();
			  break;
	    case "getestado":
			  getestado();
			  break;
		case "deleteestado":
			  deleteestado();
			  break;

		case "hayRelacion":
			  hayRelacion();
			  break;
		case "cargarestadosclientes":
			  cargarestadosclientes();
			  break;
		case "asociarestadosclientes":
			  asociarestadosclientes();
			  break;
		case "eliminarestadosclientes":
			  eliminarestadosclientes();
			  break;
		case "hayRelacionPc":
			  hayRelacionPc();
			  break;

		default:
			  echo "{failure:true}";
			  break;
	}


	function estados() 
	{
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
		$query = " SELECT a.id, a.nombre,
				   LEFT(GROUP_CONCAT( DISTINCT c.nombre SEPARATOR  ', ' ),45) AS idclientes, 
				   LEFT(GROUP_CONCAT( DISTINCT d.nombre SEPARATOR  ', ' ),45) AS idproyectos,
				   GROUP_CONCAT( DISTINCT c.nombre SEPARATOR  ', ' ) AS idclientestt, 
				   GROUP_CONCAT( DISTINCT d.nombre SEPARATOR  ', ' ) AS idproyectostt
				   FROM estados a
				   LEFT JOIN estadospuente b ON b.idestados = a.id 
                   LEFT JOIN clientes c  ON FIND_IN_SET(c.id, b.idclientes)
                   LEFT JOIN proyectos d  ON FIND_IN_SET(d.id, b.idproyectos)
                   WHERE 1 = 1 ";
        /*--------------------------------------------------------------------
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
				if ($column == 'idclientes') {
					$column = 'c.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'idproyectos') {
					$column = 'd.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				
				$hayFiltros++;
			}
		}

		if ($hayFiltros > 0)
			$where = " AND ".implode(" AND " , $where2)." ";

		$searchGeneral= (!empty($_POST['search']['value']) ? $_POST['search']['value'] : '');		
		
		if($searchGeneral != ''){
			$where.= " AND (
			a.id like '%".$searchGeneral."%' OR
			a.nombre like '%".$searchGeneral."%' OR
			a.descripcion like '%".$searchGeneral."%' OR
			a.tipo like '%".$searchGeneral."%' OR
            c.nombre like '%".$searchGeneral."%' OR
			d.nombre like '%".$searchGeneral."%'
			)";
    	}
    	  
        $query  .= " $where ";*/
        
		debugL($query,"estados");
	    $query .= " GROUP BY id ";
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		//$query  .= " ORDER BY a.nombre ASC LIMIT $start, $length ";
		$query  .= " ORDER BY a.nombre ASC";
		
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
								    <a class="dropdown-item text-info" href="estado.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>
								    <a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
								</div>
							</div>
						</td>';
						
			/* $posc = strpos($row['tipo'], ",");  
			if($posc !== false){
				$arr = explode(",",$row['tipo']);
				$tipo = "";
				foreach($arr as $key => $val) {
					$tipo .= ( $val == 'incidente' ) ? 'Correctivo' : ucwords($val); 
					$tipo .= ", "; 
				} 
				$tipo = trim($tipo,", ");
			}else{
				$tipo = ( $row['tipo'] == 'incidente' ) ? 'Correctivo' : ucwords($row['tipo']);
			}  */
		    $resultado[] = array(			
				'id' 			=>	$row['id'],	
				'nombre'		=>	$row['nombre'], 
				//'descripcion'	=>	$row['descripcion'],
				//'tipo'			=>	$tipo,
				/*'idclientes'    => "<div data-toggle='popover'  data-trigger='hover' title='Clientes' data-content='".$row['idclientestt']."'>".$row['idclientes']."</div>",
				'idproyectos' 	=> "<div data-toggle='popover'  data-trigger='hover' title='Proyectos' data-content='".$row['idproyectostt']."'>".$row['idproyectos']."</div>",*/
				'idclientes'    => "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['idclientestt']."'>".$row['idclientes']."</span>",
				'idproyectos' 	=> "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['idproyectostt']."'>".$row['idproyectos']."</span>",
				'acciones' 		=> $acciones

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

	function  createestado()
	{
		global $mysqli;
		$nombre		 = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');  
		
		//Evitar duplicado
		$sql = " SELECT nombre FROM estados WHERE nombre = '".$nombre."'";
		$rsql = $mysqli->query($sql);
		if($rsql->num_rows > 0){ 
            echo 2;
        }else{
			$query = "  INSERT INTO estados (nombre) 
						VALUES ('".$nombre."')";
						
			$result = $mysqli->query($query);
				
			if($result==true){		    
				$idestado = $mysqli->insert_id;	    
				bitacora($_SESSION['usuario'], "Estados", "El Estado #".$idestado." ha sido creado", $idestado, $query);
				echo 1;
			}else{
				echo 0;
			}
		} 
	}

	function updateestado() 
	{
		global $mysqli;
		$id  	 		= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0); 
		$nombre			= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');  
		$idempresas 	= "1";

		//Evitar duplicado
		$sql = " SELECT nombre FROM estados WHERE nombre = '".$nombre."' AND id != ".$id."";
		$rsql = $mysqli->query($sql);
		if($rsql->num_rows > 0){ 
            echo 2;
        }else{
			$query = "  UPDATE estados 
						SET nombre = '".$nombre."' 
						WHERE 
							id = ".$id."";
							
			$result = $mysqli->query($query);
			if($result==true){
				$idestado = $mysqli->insert_id;
				bitacora($_SESSION['usuario'], "Estados", "El Estado #".$idestado." ha sido actualizado", $idestado, $query);
				echo 1;
			}else{
				echo $query;
			}
		} 
	} 

	function getestado(){
		global $mysqli;
		
		$idestados	= (!empty($_REQUEST['idestados']) ? $_REQUEST['idestados'] : 0);
		$query 		= "	SELECT * FROM estados WHERE id = ".$idestados."";
		$result 	= $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
		    
			$resultado = array(
				'nombre'		=>	$row['nombre'] 
			);
		}
			if( isset($resultado) ) {
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
					WHERE incidentes.idestados = $id  AND incidentes.tipo = 'incidentes' LIMIT 1;";

        $rQcorr = $mysqli->query($qcorr);
		if($rQcorr->num_rows > 0){ 
            $existe_correctivo = 1; 
        }
        


	    $qprev = "SELECT 
						incidentes.id
					FROM incidentes
					WHERE incidentes.idestados = $id AND incidentes.tipo = 'preventivos' LIMIT 1;";

        $rQprev = $mysqli->query($qprev);
		if($rQprev->num_rows > 0){ 
            $existe_preventivo = 1; 
        }


	    $qlab = "SELECT 
						laboratorio.id
					FROM laboratorio 
					WHERE laboratorio.estado = $id LIMIT 1;";

        $rQlab = $mysqli->query($qlab);
		if($rQlab->num_rows > 0){ 
            $existe_laboratorio = 1; 
        }



	    $qpost= "SELECT postventas.id from postventas 
					WHERE postventas.idestados  = $id LIMIT 1;";

//		echo $qpost;
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

	function deleteestado(){
		global $mysqli;		
		$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
		
		$query = "DELETE FROM estados WHERE id = ".$id." ";		
		$result = $mysqli->query($query);
		if($result==true){		    
		    bitacora($_SESSION['usuario'], "Estados", "El Estado #".$id." ha sido eliminado", $id , $query);
			echo 1;		    
		}else{
			echo 0;
		}
	}	


	function cargarestadosclientes(){
		
		global $mysqli; 
		$idestado  = (!empty($_REQUEST['idestado']) ? $_REQUEST['idestado'] : 0);
		
		$query = " 	SELECT a.id, b.nombre, a.idclientes, a.idproyectos, a.idestados, c.nombre AS cliente, d.nombre AS proyecto
					FROM estadospuente a 
					LEFT JOIN estados b ON b.id = a.idestados
					LEFT JOIN clientes c ON c.id = a.idclientes
					LEFT JOIN proyectos d ON d.id = a.idproyectos
					WHERE b.id = ".$idestado." ORDER BY c.nombre, d.nombre, b.nombre ASC ";
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
									<a class="dropdown-item text-danger boton-eliminar" data-idcliente="'.$row['idcliente'].'" data-idproyecto="'.$row['idproyecto'].'" data-idestado="'.$row['idestado'].'" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
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
	
	function hayRelacionPc(){
		global $mysqli;
		
		$id   		  	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idcliente    	 = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : '');
		$idproyecto   	 = (!empty($_REQUEST['idproyecto']) ? $_REQUEST['idproyecto'] : '');
		$idestado        = (!empty($_REQUEST['idestado']) ? $_REQUEST['idestado'] : ''); 
		
		$existe_correctivo	= 0;
		$existe_laboratorio = 0;
	    $existe_postventa 	= 0;
		
		$qcorr = "SELECT id FROM incidentes WHERE
					idclientes = ".$idcliente."
					AND idproyectos = ".$idproyecto."
					AND idestados = ".$idestado." LIMIT 1";

        $rQcorr = $mysqli->query($qcorr);
		if($rQcorr->num_rows > 0){ 
            $existe_correctivo = 1; 
        }
		
		$qlab = "SELECT id FROM laboratorio WHERE
					idclientes = ".$idcliente."
					AND idproyectos = ".$idproyecto."
					AND idestados = ".$idestado." LIMIT 1";

        $rQlab = $mysqli->query($qcorr);
		if($rQlab->num_rows > 0){ 
            $existe_laboratorio = 1; 
        }
		
		$qPv = "SELECT id FROM postventas WHERE
					idclientes = ".$idcliente."
					AND idproyectos = ".$idproyecto."
					AND idestados = ".$idestado." LIMIT 1";

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