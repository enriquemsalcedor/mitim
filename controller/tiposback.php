<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "tipos": 
			  tipos();
			  break;		
		case "guardarTipo":
			  guardarTipo();
			  break;
		case "actualizarTipo":
			  actualizarTipo();
			  break;
	    case "getTipo":
			  getTipo();
			  break;
		case "eliminarTipo":
			  eliminarTipo();
			  break;
		case "hayRelacion":
			  hayRelacion();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	

	function tipos() 
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
		//$idclientes 		 = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : 0);
		//$idproyectos 		 = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : 0);
		
		$query  = " SELECT a.id, a.nombre
		            FROM activostipos a 
		            WHERE a.id!=0 ";  		
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
			)";
    	}*/

        $query  .= " $where ";
		debugL($query,"estados");
	    $query .= " GROUP BY a.id ";
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

						';



			$btnVer = '<a class="dropdown-item text-warning" href="tipo.php?id='.$row['id'].'&type=view"><i class="fas fa-eye mr-2"></i>Ver</a>';

			$btnEditar = '<a class="dropdown-item text-info" href="tipo.php?id='.$row['id'].'&type=edit"><i class="fas fa-pen mr-2"></i>Editar</a>';

			$btnEliminar='<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>';


//			$acciones.=$btnVer;

			if(($nivel == 1 ) || ($nivel == 2 ) ){
				$acciones.=$btnEditar;
				$acciones.=$btnEliminar;

			}else if($nivel > 2){ //($nivel == 7)
				$acciones.=$btnVer;

			}


			    $acciones.='
									</div>
								</div>
							</td>';


			$resultado[] = array(
				'id' 			=>	$row['id'],
				'acciones' 	=>	$acciones,  
				'nombre' 			  =>	$row['nombre']   
			);
		}
		$response = array(
		  "draw" => intval($draw),
		  "recordsTotal" => intval($recordsTotal),
		  "recordsFiltered" => intval($recordsTotal),
		  "data" => $resultado,
		);
		echo json_encode($response); 
	}
	
	function  guardarTipo()
	{
		global $mysqli;
		
		//$idcliente 	= (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : 0);   
		//$idproyecto 	= (!empty($_REQUEST['idproyecto']) ? $_REQUEST['idproyecto'] : 0);
		$nombre 		= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : ''); 
		$existe 		= 0;
		
		$sql = "SELECT COUNT(id) AS total FROM activostipos WHERE nombre = '".$nombre."'";
		$rsql = $mysqli->query($sql);
		if($reg = $rsql->fetch_assoc()){
			$count = $reg["total"];
			if($count >= 1){
				$existe = 1;
			}
		}
		
		if($existe == 1){
			echo 2;
		}else{
	    	$query  = " INSERT INTO activostipos 
    					(nombre) VALUES 
    					('".$nombre."') ";
    	    //echo $query;
    		$result = $mysqli->query($query); 
    		if($result==true){
    		    $idtipo = $mysqli->insert_id;
    		    bitacora($_SESSION['usuario'], "Tipos", "El Tipo #".$idtipo." ha sido creado", $idtipo, $query);			
    			echo 1;
    		}else{
    			echo 0;
    		}
		}
	
	}
	
	function actualizarTipo() 
	{
		global $mysqli;
		$id 			 	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);   
		//$idcliente 			 = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : 0);   
		//$idproyecto 		 = (!empty($_REQUEST['idproyecto']) ? $_REQUEST['idproyecto'] : '');   
		$nombre 			 = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');    
						 
		$campos = array( 
					//	'Cliente'  => getValores('nombre','clientes',$idcliente),
					//	'Proyecto' => getValores('nombre','proyectos',$idproyecto),
						'Nombre'   => $nombre
					);
		
		$valoresold = getRegistroSQL("  SELECT a.nombre AS 'Nombre'
										FROM activostipos a   
										WHERE 1 = 1 
										AND a.id = '".$id."' "); 
		
		$query  = " UPDATE activostipos SET  
					nombre = '".$nombre."' 
					WHERE id = '".$id."' ";	
					//echo $query;
		$result = $mysqli->query($query);
		
		if($result == true){
		    //bitacora($_SESSION['usuario'], "Proveedores", "El Proveedor #".$id." ha sido actualizado", $id, $query);			
		    actualizarRegistro('Tipos','Tipos',$id,$valoresold,$campos,$query);
			
			echo 1;
		}else{
			echo 0;
		}
	} 
	
	function getTipo(){
		global $mysqli;
		
		$idtipo = (!empty($_REQUEST['idtipo']) ? $_REQUEST['idtipo'] : '');
		
		$query 	= "	SELECT * FROM activostipos WHERE id = '".$idtipo."' ";
		$result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			$resultado = array(
				//'idcliente' 		  =>	$row['idcliente'],  
				//'idproyecto' 		  =>	$row['idproyecto'],  
				'nombre' 			  =>	$row['nombre'] 
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
	    $existesubtipo	= 0;
	    $existeactivo	= 0;
		
        $qSb = " SELECT id FROM activossubtipos 
                WHERE idtipo = ".$id." LIMIT 1 ";
                  
        $rSb = $mysqli->query($qSb);
		if($rSb->num_rows > 0){ 
            $existesubtipo = 1; 
        }
        
        $qA = " SELECT id FROM activos a 
                WHERE idtipo = ".$id." LIMIT 1 ";
                  
        $rA = $mysqli->query($qA);
		if($rA->num_rows > 0){ 
            $existeactivo = 1;  
        }         
        
		if($existeactivo == 1 || $existesubtipo == 1){
			echo 1;
		}else{
			echo 0;
		}
	}
		
	function eliminarTipo() 
	{
		global $mysqli;
		
		$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		
		$query = "DELETE FROM activostipos WHERE id = '".$id."' ";
		$result = $mysqli->query($query);
		
		if($result == true){
		    bitacora($_SESSION['usuario'], "Tipos", "El Tipo #".$id." ha sido eliminado", $id , $query);
			echo 1;
		}else{
			echo 0;
		}
	}	
?>