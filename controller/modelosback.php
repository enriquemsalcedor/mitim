<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){
		case "cargar": 
			  cargar();
			  break;
		case "getmodelos": 
			  getmodelos();
			  break;
		case "createmodelos": 
			  createmodelos();
			  break;
		case "updatemodelos": 
			  updatemodelos();
			  break;
		case "deletemodelos": 
			  deletemodelos();
			  break;	    
		case "hayRelacion":
			  hayRelacion();
			  break;

		default:
			  echo "{failure:true}";
			  break;
	}	
	
	function cargar(){
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
		//ASC or DESC
		*/
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); 
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		/*--------------------------------------------------------------------*/
		$nivel = $_SESSION["nivel"]; 
		$query = " SELECT a.id, a.nombre, a.descripcion, b.nombre as marca
				   FROM modelos a
				   INNER JOIN marcas b ON a.idmarcas = b.id ";

		$query .= "WHERE a.id!=0";
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
				if ($column == 'marca') {
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
			a.descripcion like '%".$searchGeneral."%' OR
			b.nombre like '%".$searchGeneral."%')";
    	}
	
	    $query  .= " $where ";*/
	    
		debugL($query,"estados");
	    $query .= " GROUP BY id ";
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		//$query  .= " ORDER BY a.nombre ASC LIMIT $start, $length ";
		$query  .= " ORDER BY a.nombre ASC ";
		
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


			$btnVer = '<a class="dropdown-item text-warning" href="modelo.php?id='.$row['id'].'&type=view"><i class="fas fa-eye mr-2"></i>Ver</a>';

			$btnEditar = '<a class="dropdown-item text-info" href="modelo.php?id='.$row['id'].'&type=edit"><i class="fas fa-pen mr-2"></i>Editar</a>';

			$btnEliminar='<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>';


			if(($nivel == 1) || ($nivel == 2)){
				$acciones.=$btnEditar;
				$acciones.=$btnEliminar;
			}else if($nivel>2){
				$acciones.=$btnVer;

			}


			$acciones.='
									</div>
								</div>
							</td>';

			 $resultado[]  = array(
				'id' 			=>	$row['id'],
				'acciones' 		=>	$acciones,
				'nombre'		=>  $row['nombre'],
				'descripcion'	=>  $row['descripcion'],
				'marca' 		=>	$row['marca'],
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
 



	function getmodelos(){
		global $mysqli;
		$nivel		= (isset($_SESSION["nivel"]) ? $_SESSION["nivel"] : null);

		$return_delete = 0;
		if($nivel!=null){//NO SESSION
			$idmodelos = $_REQUEST['idmodelos'];
			$query 		= "	SELECT * FROM modelos WHERE id = '$idmodelos' ";
			$result 	= $mysqli->query($query);
			
			while($row = $result->fetch_assoc()){			
				$resultado = array(
					'nombre' 		=>	$row['nombre'],
					'descripcion'	=>  $row['descripcion'],
					'idmarcas'	=>	$row['idmarcas']	
				);
			}
			
			if( isset($resultado) ) {
				echo json_encode($resultado);
			} else {
				echo "0";
			}
		}else{
			echo "0";// NO SESSION

		}
		
	}
	
	function createmodelos(){
		global $mysqli;		 
		$nombre 		= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$descripcion 	= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');
		$idmarcas		= (!empty($_REQUEST['idmarcas']) ? $_REQUEST['idmarcas'] : '0');
		$existe 		= 0;
		
		$sql = "SELECT COUNT(id) AS total FROM modelos WHERE nombre = '".$nombre."' AND idmarcas = ".$idmarcas."";
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
			$query 	= '	INSERT INTO	modelos (nombre, descripcion, idmarcas) VALUES ("'.$nombre.'", "'.$descripcion.'", "'.$idmarcas.'") ';
			$result = $mysqli->query($query);
			$id = $mysqli->insert_id;
			
			if($result == true){
				$campos = array(
					'Nombre' 		=> $nombre,
					'Descripción' 	=> $descripcion,
					'Marcas' 		=> getValor('nombre','marcas',$idmarcas,'')
				);
				nuevoRegistro('Modelos','Modelos',$id,$campos,$query);
				echo 1;
			}else{
				echo 0;
			}
		} 
	}
	
	function updatemodelos(){
		global $mysqli;		
		$id 			= (!empty($_REQUEST['idmodelos']) ? $_REQUEST['idmodelos'] : '');
		$nombre 		= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$descripcion 	= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');
		$idmarcas		= (!empty($_REQUEST['idmarcas']) ? $_REQUEST['idmarcas'] : '0');
		



		$existe 		= 0;
		
		$sql = "SELECT COUNT(id) AS total FROM modelos WHERE nombre = '".$nombre."' AND idmarcas = ".$idmarcas." AND id !='".$id."'";
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

			$valoresold = getRegistroSQL("SELECT a.nombre AS Nombre, a.descripcion AS 'Descripción', b.nombre AS marca
										  FROM modelos a
										  INNER JOIN marcas b ON a.idmarcas = b.id
										  WHERE a.id = '".$id."' ");		
			
			$query 	= '	UPDATE modelos SET nombre = "'.$nombre.'", descripcion = "'.$descripcion.'", idmarcas = "'.$idmarcas.'" WHERE id = "'.$id.'" ';
			$result = $mysqli->query($query);	
			
			if($result == true){			
				$campos = array(
					'Nombre' 		=> $nombre,
					'Descripción' 	=> $descripcion,
					'Marca' 		=> getValor('nombre','marcas',$idmarcas,''),
				);		
//			    actualizarRegistro('Modelos','Modelos',$id,$valoresold,$campos,$query);
			    echo 1;
			}else{
			    echo 0;
			}

		}





	}


	function hayRelacion(){
	    global $mysqli;
	    
	    $id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
	    
	    $existe_activo = 0;
	    $qact = "SELECT 
					activos.id
				FROM activos 
				WHERE activos.idmodelos = '$id' LIMIT 1;";

        
        $rQAct = $mysqli->query($qact);
		if($rQAct->num_rows > 0){ 
            $existe_activo = 1; 
        }


		if(
			($existe_activo == 1) 

			
		){
			echo 1;
		}else{
			echo 0;
		}
	}

	
	function deletemodelos(){
		global $mysqli;		

		$nivel		= (isset($_SESSION["nivel"]) ? $_SESSION["nivel"] : null);
		$return_delete = 0;
		if($nivel!=null){//NO SESSION
			if($nivel!=7){//NO AUTORIZADO

				$id 	= (!empty($_REQUEST['idmodelos']) ? $_REQUEST['idmodelos'] : '');
				$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');			
				$query 	= "DELETE FROM modelos WHERE id = '$id'";
				$result = $mysqli->query($query);	
				
				if($result == true){		    
					eliminarRegistro('Modelos','Modelos',$nombre,$id,$query);
					$return_delete = 1;
				}
		
			}else{
				$return_delete = "No Authorized";

			}
		}else{
			$return_delete = "null";
		}

		echo $return_delete;


	}
	
?>