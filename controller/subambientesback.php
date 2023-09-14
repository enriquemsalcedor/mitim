<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "subambientes": 
			  subambientes();
			  break;		
		case "guardarSubambiente":
			  guardarSubambiente();
			  break;
		case "actualizarSubambiente":
			  actualizarSubambiente();
			  break;
	    case "getSubambiente":
			  getSubambiente();
			  break;
		case "eliminarSubambientes":
			  eliminarSubambientes();
			  break;
		case "hayRelacion":
			  hayRelacion();
			  break;
		case "cargarsubambientespuente": 
			  cargarsubambientespuente();
			  break;
		case "asociarsubambientespuente": 
			  asociarsubambientespuente();
			  break;
		case "eliminarsubambientespuente": 
			  eliminarsubambientespuente();
			  break;
		case "hayRelacionPs": 
			  hayRelacionPs();
			  break; 
		default:
			  echo "{failure:true}";
			  break;
	}	

	function subambientes() 
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
		$nivel				 = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		$idclientes 		 = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : 0);
		$idproyectos 		 = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : 0);
		
		$query  = " SELECT a.id, LEFT(a.nombre,45) as nombre, 
			        LEFT(GROUP_CONCAT( DISTINCT c.nombre SEPARATOR  ', ' ),45) AS ambientes,
					GROUP_CONCAT( DISTINCT c.nombre SEPARATOR ', ' ) AS ambientestt,
					LEFT(GROUP_CONCAT( DISTINCT d.nombre SEPARATOR  ', ' ),45) AS clientes, 
				    LEFT(GROUP_CONCAT( DISTINCT e.nombre SEPARATOR  ', ' ),45) AS proyectos,
				    GROUP_CONCAT( DISTINCT d.nombre SEPARATOR  ', ' ) AS clientestt, 
				    GROUP_CONCAT( DISTINCT e.nombre SEPARATOR  ', ' ) AS proyectostt
		            FROM subambientes a 
		            INNER JOIN subambientespuente b ON b.idsubambientes = a.id
		            LEFT JOIN ambientes c ON FIND_IN_SET(c.id, b.idambientes)
				    LEFT JOIN clientes d  ON FIND_IN_SET(d.id, b.idclientes)
                    LEFT JOIN proyectos e  ON FIND_IN_SET(e.id, b.idproyectos)
		            WHERE a.id!=0 "; 
		 
		if($nivel == 4 || $nivel == 7){
			if($idclientes != ''){
				//$arr = strpos($idclientes, ',');
				//if ($arr !== false) {
					$query  .= " AND b.idclientes IN (".$idclientes.") ";
				//}else{
				/* 	$query  .= " AND find_in_set(".$idclientes.",b.idclientes) ";
				}   */
			}
			if($idproyectos != ''){
				//$arr = strpos($idproyectos, ',');
				//if ($arr !== false) {
					$query  .= " AND b.idproyectos IN (".$idproyectos.") ";
				/* }else{
					$query  .= " AND find_in_set(".$idproyectos.",b.idproyectos) ";
				}  */ 
			}	
		}		
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
				if ($column == 'idambientes') {
					$column = 'b.nombre';
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
			b.nombre like '%".$searchGeneral."%'
			)";
    	}

		$query  .= " $where ";*/
		debugL($query,"estados");
	    $query .= " GROUP BY id ";
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		//$query  .= " ORDER BY a.id ASC LIMIT $start, $length ";
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
								    <a class="dropdown-item text-info" href="subambiente.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>
								    <a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
								</div>
							</div>
						</td>';


			$resultado[] = array(
				'id' 			=>	$row['id'],	
				'acciones' 		=>	$acciones,
				'nombre' 		=>	mb_strtoupper($row['nombre']),  
				'ambientes'    => "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['ambientestt']."'>".$row['ambientes']."</span>",
				'clientes'    => "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['clientestt']."'>".$row['clientes']."</span>",
				'proyectos' 	=> "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['proyectostt']."'>".$row['proyectos']."</span>",
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
	
	function  guardarSubambiente(){
		global $mysqli;
		
		$nombre  	      = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');  
		$idambientes      = (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : ''); 
		$modulo 		= 'Subambientes';
		$idusuarios	  = $_SESSION['user_id'];
		
		$sql = " SELECT b.idclientes, b.idproyectos 
				 FROM ambientes a INNER JOIN ambientespuente b ON b.idambientes = a.id WHERE a.id = ".$idambientes."";
				 //echo $sql;
		$rta = $mysqli->query($sql);
		if($reg = $rta->fetch_assoc()){
			$idclientes = $reg['idclientes'];
			$idproyectos = $reg['idproyectos'];
		}
		$query  = " INSERT INTO subambientes (nombre) VALUES ('$nombre') ";
	    $result = $mysqli->query($query);
	    	//echo $query;
		if($result==true){
			$idsubambientes = $mysqli->insert_id;
			bitacora($_SESSION['usuario'], "Subambientes - ".$modulo."", "El subambiente #".$idsubambientes." ha sido creado", $idsubambientes, $query);
			
			$query 	= "	INSERT INTO	subambientespuente (idempresas,idclientes, idproyectos, idambientes, idsubambientes, fechacreacion, idusuarios) 
						VALUES (1, ".$idclientes.", ".$idproyectos.", ".$idambientes.", ".$idsubambientes.", NOW(), ".$idusuarios.")";
					//echo $query;
			$result = $mysqli->query($query); 
			$result == true ? $respuesta = 1 : $respuesta = 0;
			$idsubambientesp = $mysqli->insert_id;	
			bitacora($_SESSION['usuario'], "Subambientes asociación - ".$modulo."", "El registro #".$idsubambientesp." ha sido creado", $idsubambientesp, $query);		
			
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function actualizarSubambiente() 
	{
		global $mysqli;
		
		$id  		=  (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$nombre 	=  (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		//$idambientes=  (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : '0');									   
  		
		$query  = " UPDATE subambientes SET nombre = '".$nombre."' WHERE id = '".$id."' ";
		//echo $query;
		$result = $mysqli->query($query);
		
		if($result == true){
		    bitacora($_SESSION['usuario'], "Subambientes", "El Subambiente #".$id." ha sido actualizado", $id, $query);			
			echo 1;
		}else{
			echo 0;
		}
	}

	function getSubambiente(){
		global $mysqli;
		
		$idsubambientes = (!empty($_REQUEST['idsubambientes']) ? $_REQUEST['idsubambientes'] : '');
		$query 	= "	SELECT * FROM subambientes WHERE id = '".$idsubambientes."' ";
		$result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			$resultado = array(
				'nombre' 		=>	$row['nombre'],  
				'idambientes'	=>	$row['idambientes']
			);
		}
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo 0;
		}
	}
	

		function hayRelacion(){
	    global $mysqli;
	    
	    $id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
	    
	    $existe_preventivo	= 0;

	    $existe_activo = 0;

      
	    $qprev = "SELECT 
						incidentes.id
					FROM incidentes
					WHERE incidentes.idsubambientes = $id AND incidentes.tipo = 'preventivos' LIMIT 1;";

        $rQprev = $mysqli->query($qprev);
		if($rQprev->num_rows > 0){ 
            $existe_preventivo = 1; 
        }


	    $qact = "SELECT 
					activos.id
				FROM activos 
				WHERE activos.idsubambientes = '$id' LIMIT 1;";

        $rQAct = $mysqli->query($qact);
		if($rQAct->num_rows > 0){ 
            $existe_activo = 1; 
        }

//        echo $existe_activo." - ".$existe_preventivo;

		if(
			($existe_preventivo  == 1) ||
			($existe_activo == 1) 
		){
			echo 1;
		}else{
			echo 0;
		}
	}



	function eliminarSubambientes(){
		global $mysqli;
		
		$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		
		$query = "DELETE FROM subambientes WHERE id = '".$id."' ";
		$result = $mysqli->query($query);
		
		if($result == true){
		    bitacora($_SESSION['usuario'], "Subambientes", "El Subambiente #".$id." ha sido eliminado", $id , $query);
			echo 1;
		}else{
			echo 0;
		}
	}	
	
	
		function cargarsubambientespuente(){
		
		global $mysqli; 
		$idsubambiente  = (!empty($_REQUEST['idsubambiente']) ? $_REQUEST['idsubambiente'] : 0);
		
		$query = " 	SELECT b.id, a.nombre, b.idclientes, b.idproyectos, b.idambientes, b.idsubambientes,
					c.nombre AS ambiente, d.nombre AS cliente, e.nombre AS proyecto 
					FROM subambientes a 
					INNER JOIN subambientespuente b ON b.idsubambientes = a.id 
					INNER JOIN ambientes c ON b.idambientes = c.id 
					INNER JOIN clientes d ON b.idclientes = d.id 
					INNER JOIN proyectos e ON b.idproyectos = e.id 
					WHERE a.id = ".$idsubambiente." 
					ORDER BY d.nombre, e.nombre, c.nombre ASC ";
					//echo $query;
		$result = $mysqli->query($query);
		$resultado = array();
		while($row = $result->fetch_assoc()){
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center"><a class="dropdown-item text-danger boton-eliminar" data-idcliente="'.$row['idcliente'].'" data-idproyecto="'.$row['idproyecto'].'" data-idambiente="'.$row['idambiente'].'" data-idsubambiente="'.$row['idsubambiente'].'" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
									</div>
								</div>
							</td>';
							
			$resultado[] = array(
				'id' 		=>	$row['id'],
				'acciones' 	=>	$acciones, 
				'nombre'	=>	$row['nombre'], 
				'cliente'	=>	$row['cliente'], 
				'proyecto'	=>	$row['proyecto'], 
				'ambiente'	=>	$row['ambiente'], 
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
	
	function asociarsubambientespuente(){
		
		global $mysqli;	
		
		$id 		  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idambientes = (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : '');  
		$idusuarios	  = (!empty($_SESSION['user_id']) ? $_SESSION['user_id'] : '');
		
		$sql = " SELECT id FROM subambientespuente 
				 WHERE 
				 idclientes = ".$idclientes." 
				 AND idproyectos = ".$idproyectos." 
				 AND idambientes = ".$idambientes."
				 AND idsubambientes = ".$id.""; 
				// echo $sql;
		$rsql = $mysqli->query($sql);
		
		//Evitar duplicado
		if($rsql->num_rows > 0){
			echo 2;
		}else{
			$query 	= "	INSERT INTO	subambientespuente (idclientes, idproyectos, idambientes, idsubambientes,fechacreacion, idusuarios) VALUES (".$idclientes.", ".$idproyectos.", ".$idambientes.", ".$id.", NOW(), ".$idusuarios.")";
		//	echo $query;
			$result = $mysqli->query($query);
			
			$result == true ? $response = 1 : $response = 0;
			echo $response;
		}
	}  
	
	function eliminarsubambientespuente(){
		
		global $mysqli;
		
		$id = $_REQUEST['id'];
		
		$query 	= "DELETE FROM subambientespuente WHERE id = ".$id."";
		$result = $mysqli->query($query);
		
		if($result==true){
		    
		    bitacora($_SESSION['usuario'], "Areas", "La area #".$id." ha sido eliminada", $id , $query);
		    
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function hayRelacionPs(){
		global $mysqli;
		
		$id   		    = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idcliente      = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : '');
		$idproyecto     = (!empty($_REQUEST['idproyecto']) ? $_REQUEST['idproyecto'] : '');
		$idambiente    = (!empty($_REQUEST['idambiente']) ? $_REQUEST['idambiente'] : '');
		$idsubambiente = (!empty($_REQUEST['idsubambiente']) ? $_REQUEST['idsubambiente'] : '');
		$registros      = "";  
		$respuesta	    = array( 
            'valor'     => 0,
            'registros' => 0 
        );
		
		if($tipo == 'correctivos'){
			$modulo = "incidentes"; 
		}elseif($tipo == 'preventivos'){
			$modulo = "incidentes"; 
		}elseif($tipo == 'postventas'){
			$modulo = "postventas"; 
		}
		
		$sql = " SELECT id FROM ".$modulo." 
					 WHERE idclientes = ".$idcliente." 
					 AND idproyectos = ".$idproyecto." 
					 AND idambientes = ".$idcategoria." 
					 AND idsubambientes = ".$idsubcategoria." ";
					 /* echo $sql; */
		$rsql = $mysqli->query($sql);
		if($rsql->num_rows > 0){
			
			$respuesta['valor'] = 1;
			while($reg = $rsql->fetch_assoc()){
				$registros .= $reg["id"].","; 
			}
			$registros = substr($registros, 0, -1); 
			$respuesta['registros'] = $registros;
			
			echo json_encode($respuesta);
		}else{
			echo json_encode($respuesta); 
		}
	}
?>