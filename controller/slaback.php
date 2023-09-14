<?php 

    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "sla": 
			  sla();
			  break;		
		case "createsla":
			  createsla();
			  break;
		case "updatesla":
			  updatesla();
			  break;
	    case "getsla":
			  getsla();
			  break;
		case "deletesla":
			  deletesla();
			  break;
		case "hayRelacion":
			  hayRelacion();
			  break;
		case "cargarprioridadesclientes":
			  cargarprioridadesclientes();
			  break;
		case "asociarprioridadesclientes":
			  asociarprioridadesclientes();
			  break;
		case "eliminarprioridadesclientes":
			  eliminarprioridadesclientes();
			  break;
		case "hayRelacionPc":
			  hayRelacionPc();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	


	function sla() 
	{
		global $mysqli;	

		/*----$where = "";
		$where2 = array();
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		//contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		//Obtener el nombre de la columna de clasificación de su índice
		$orderBy= (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ?$_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );
		//ASC or DESC
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); 
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		$usuario 			 = (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : 0);
		----------------------------------------------------------------*/
		$query  = " SELECT a.id, a.prioridad, a.descripcion,
				    LEFT(GROUP_CONCAT( DISTINCT c.nombre SEPARATOR  ', ' ),45) AS clientes, 
				    LEFT(GROUP_CONCAT( DISTINCT d.nombre SEPARATOR  ', ' ),45) AS proyectos,
				    GROUP_CONCAT( DISTINCT c.nombre SEPARATOR  ', ' ) AS clientestt, 
				    GROUP_CONCAT( DISTINCT d.nombre SEPARATOR  ', ' ) AS proyectostt
		            FROM sla a
		            LEFT JOIN slapuente b ON b.idprioridades = a.id
				    LEFT JOIN clientes c  ON FIND_IN_SET(c.id, b.idclientes)
                    LEFT JOIN proyectos d  ON FIND_IN_SET(d.id, b.idproyectos)
					WHERE 1 ";
		/*--------------------------------------------------------------------
		$hayFiltros = 0;
		for($i=0 ; $i<count($_REQUEST['columns']);$i++){
			$column = $_REQUEST['columns'][$i]['data'];
			
			if ($_REQUEST['columns'][$i]['search']['value']!="") {
			    
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);

				if ($column == 'id') {
					$column = 'id';
    				$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'prioridad') {
					$column = 'prioridad';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'descripcion') {
					$column = 'descripcion';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'horas') {
					$column = 'horas';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'dias') {
					$column = 'dias';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'tipo') {
					$column = 'tipo';
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
				id like '%".$searchGeneral."%' OR
            	prioridad like '%".$searchGeneral."%' OR
            	descripcion like '%".$searchGeneral."%' OR
            	dias like '%".$searchGeneral."%' OR
            	horas like '%".$searchGeneral."%' OR
            	tipo like '%".$searchGeneral."%' OR
            	activo like '%".$searchGeneral."%'
			)";
    	}
    	
		$query  .= " $where ";
		*/
		debugL($query,"prioridades");
	    $query .= " GROUP BY id ";
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		$query  .= " ORDER BY prioridad ASC";
		//$query  .= " ORDER BY prioridad ASC LIMIT $start, $length ";
	
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
								    <a class="dropdown-item text-info" href="prioridad.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>
								    <a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
								</div>
							</div>
						</td>';

			$resultado[] = array(
				'id' 			=>	$row['id'],	
				'prioridad'		=>	$row['prioridad'], 
				'descripcion'	=>	$row['descripcion'],
				'dias'			=>	$row['dias'],
				'horas'			=>	$row['horas'],
				'tipo'			=>	$row['tipo'],
				'clientes'      => "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['clientestt']."'>".$row['clientes']."                 </span>",
				'proyectos' 	=> "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['proyectostt']."'>".$row['proyectos']."</span>",
				'acciones' 	=> $acciones
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

	function  createsla()
	{
		global $mysqli;
		$prioridad		= (!empty($_REQUEST['prioridad']) ? $_REQUEST['prioridad'] : ''); 
		$descripcion	= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');
		$dias			= (!empty($_REQUEST['dias']) ? $_REQUEST['dias'] : '');
		$horas			= (!empty($_REQUEST['horas']) ? $_REQUEST['horas'] : '');
		$tipo			= (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');

		$query = " INSERT INTO sla VALUES (null,'$prioridad','$descripcion','$dias','$horas','$tipo','Activo') ";
		$result = $mysqli->query($query);
	    	
		if($result==true){		    
		    $idsla = $mysqli->insert_id;	    
		    bitacora($_SESSION['usuario'], "Prioridades", "La prioridad #".$idsla." ha sido creada", $idsla, $query);
			echo 1;
		}else{
			echo $query;
		}
	}
		
	function updatesla() 
	{
		global $mysqli;
		$id  	 		= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0 ); 
		$prioridad		= (!empty($_REQUEST['prioridad']) ? $_REQUEST['prioridad'] : ''); 
		$descripcion	= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');
		$dias			= (!empty($_REQUEST['dias']) ? $_REQUEST['dias'] : '');
		$horas			= (!empty($_REQUEST['horas']) ? $_REQUEST['horas'] : '');
		$tipo			= (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');
  		
		$query = "  UPDATE sla SET prioridad = '$prioridad', descripcion = '$descripcion',
					dias = '$dias', horas = '$horas', tipo = '$tipo'
					WHERE id = '$id' ";
		$result = $mysqli->query($query);
		
		if($result==true){
		    $idsla = $mysqli->insert_id;
		    bitacora($_SESSION['usuario'], "Prioridades", "La prioridad #".$idsla." ha sido actualizada", $idsla, $query);
			echo 1;
		}else{
			echo $query;
		} 
	}

	function getsla(){
		global $mysqli;
		
		$idsla	= (!empty($_REQUEST['idsla']) ? $_REQUEST['idsla'] : '');
		$query 		= "	SELECT * FROM sla WHERE id = '$idsla' ";
		$result 	= $mysqli->query($query);
//		echo $query;
		
		while($row = $result->fetch_assoc()){
			$resultado = array(
				'prioridad'		=>	$row['prioridad'], 
				'descripcion' 	=>	$row['descripcion'],
				'dias' 			=>	$row['dias'],
				'horas' 		=>	$row['horas'],
				'tipo' 			=>	$row['tipo'],
			);
		}
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}


	function deletesla(){

		global $mysqli;		
		$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0); ;
		
		$query = "DELETE FROM sla WHERE id = '$id' ";		
		$result = $mysqli->query($query);
		if($result==true){		    
		    bitacora($_SESSION['usuario'], "Sla", "El Sla #".$id." ha sido eliminada", $id , $query);
			echo 1;		    
		}else{
			echo 0;
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
					WHERE incidentes.idprioridades = $id  AND incidentes.tipo = 'incidentes' LIMIT 1;";

        $rQcorr = $mysqli->query($qcorr);
		if($rQcorr->num_rows > 0){ 
            $existe_correctivo = 1; 
        }
        


	    $qprev = "SELECT 
						incidentes.id
					FROM incidentes
					WHERE incidentes.idprioridades = $id AND incidentes.tipo = 'preventivos' LIMIT 1;";

        $rQprev = $mysqli->query($qprev);
		if($rQprev->num_rows > 0){ 
            $existe_preventivo = 1; 
        }


	    $qlab = "SELECT 
						laboratorio.id
					FROM laboratorio 
					WHERE laboratorio.idprioridad = $id LIMIT 1;";

        $rQlab = $mysqli->query($qlab);
		if($rQlab->num_rows > 0){ 
            $existe_laboratorio = 1; 
        }



	    $qpost= "SELECT postventas.id from postventas 
					WHERE postventas.idprioridades  = $id LIMIT 1;";

//		echo $qpost;
        $rQpost = $mysqli->query($qpost);
		if($rQpost->num_rows > 0){ 
            $existe_postventa = 1; 
        }
        
        $qpost= "SELECT slapuente.idprioridades from slapuente 
					WHERE slapuente.idprioridades  = $id LIMIT 1;";

//		echo $qpost;
        $rQpost = $mysqli->query($qpost);
		if($rQpost->num_rows > 0){ 
            $existe_proyecto = 1; 
        }

		if(
			($existe_correctivo  == 1) ||
			($existe_preventivo  == 1) ||
			($existe_laboratorio == 1) ||
			($existe_postventa 	 == 1) ||
			($existe_proyecto 	 == 1) 
		){
			echo 1;
		}else{
			echo 0;
		}
	}

	function cargarprioridadesclientes(){
		
		global $mysqli; 
		$idprioridad  = (!empty($_REQUEST['idprioridad']) ? $_REQUEST['idprioridad'] : 0);
		
		$query = " 	SELECT a.id, b.prioridad as nombre, a.idclientes, a.idproyectos, a.idprioridades, c.nombre AS cliente, d.nombre AS proyecto
					FROM slapuente a 
					LEFT JOIN sla b ON b.id = a.idprioridades
					LEFT JOIN clientes c ON c.id = a.idclientes
					LEFT JOIN proyectos d ON d.id = a.idproyectos
					WHERE b.id = ".$idprioridad." ORDER BY c.nombre, d.nombre, b.prioridad ASC ";
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
									<a class="dropdown-item text-danger boton-eliminar" data-idcliente="'.$row['idcliente'].'" data-idproyecto="'.$row['idproyecto'].'" data-idestado="'.$row['idprioridad'].'" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
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
	
	function asociarprioridadesclientes(){
		global $mysqli;		 
		
		$id 		  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idcliente 	  = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : '');
		$idproyecto   = (!empty($_REQUEST['idproyecto']) ? $_REQUEST['idproyecto'] : ''); 
		$usuario	  = $_SESSION['user_id'];
		
		$sql = " SELECT id FROM slapuente 
				 WHERE 
				 idclientes = ".$idcliente." 
				 AND idproyectos = ".$idproyecto." 
				 AND idprioridades = ".$id."";
		//echo $sql;
		$rsql = $mysqli->query($sql);
		
		//Evitar duplicado
		if($rsql->num_rows > 0){
			echo 2;
		}else{ 
			$query 	= "	INSERT INTO	slapuente (idclientes, idproyectos, idprioridades, fechacreacion, idusuarios) 
						VALUES (".$idcliente.", ".$idproyecto.", ".$id.", NOW(), '".$usuario."')";
			$result = $mysqli->query($query);
			$idcate = $mysqli->insert_id;
			$result == true ? $respuesta = 1 : $respuesta = 0;
			echo $respuesta;
		} 
	}
	
	function eliminarprioridadesclientes(){
		
		global $mysqli;
		
		$id   		  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		
		$query 	= "DELETE FROM slapuente WHERE id = ".$id.""; 
		$result = $mysqli->query($query);
		
		if($result==true){
			
			bitacora($_SESSION['usuario'], "Prioridades", "La Prioridad #".$id." ha sido eliminada", $id , $query);
			
			echo 1;
		}else{
			echo 0;
		} 
	}
	
	function hayRelacionPc(){
		global $mysqli;
		
		$id   		  	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idcliente    	 = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : '');
		$idproyecto   	 = (!empty($_REQUEST['idproyecto']) ? $_REQUEST['idproyecto'] : '');
		$idprioridad        = (!empty($_REQUEST['idestado']) ? $_REQUEST['idestado'] : ''); 
		
		$existe_correctivo	= 0;
		$existe_laboratorio = 0;
	    $existe_postventa 	= 0;
		
		$qcorr = "SELECT id FROM incidentes WHERE
					idclientes = ".$idcliente."
					AND idproyectos = ".$idproyecto."
					AND idprioridades = ".$idprioridad." LIMIT 1";

        $rQcorr = $mysqli->query($qcorr);
		if($rQcorr->num_rows > 0){ 
            $existe_correctivo = 1; 
        }
		
		$qlab = "SELECT id FROM laboratorio WHERE
					idclientes = ".$idcliente."
					AND idproyectos = ".$idproyecto."
					AND idprioridades = ".$idprioridad." LIMIT 1";

        $rQlab = $mysqli->query($qcorr);
		if($rQlab->num_rows > 0){ 
            $existe_laboratorio = 1; 
        }
		
		$qPv = "SELECT id FROM postventas WHERE
					idclientes = ".$idcliente."
					AND idproyectos = ".$idproyecto."
					AND idprioridades = ".$idprioridad." LIMIT 1";

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