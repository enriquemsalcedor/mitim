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
		case "getmarcas": 
			  getmarcas();
			  break;
		case "createmarcas": 
			  createmarcas();
			  break;
		case "updatemarcas": 
			  updatemarcas();
			  break;
		case "deletemarcas": 
			  deletemarcas();
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
		$query = " SELECT a.id, a.nombre, a.descripcion FROM marcas a ";
		$query  .= " WHERE a.id!=0 ";
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
			a.descripcion like '%".$searchGeneral."%'
			)";
    	}
    	  
        $query  .= " $where ";*/
		debugL($query,"marcas");
	    $query .= " GROUP BY a.id ";
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		//$query  .= " ORDER BY a.nombre LIMIT $start, $length ";
		$query  .= " ORDER BY a.nombre";
		
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



			$btnVer = '<a class="dropdown-item text-warning" href="marca.php?id='.$row['id'].'&type=view"><i class="fas fa-eye mr-2"></i>Ver</a>';

			$btnEditar = '<a class="dropdown-item text-info" href="marca.php?id='.$row['id'].'&type=edit"><i class="fas fa-pen mr-2"></i>Editar</a>';

			$btnEliminar='<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'" href="#"><i class="fas fa-trash mr-2"></i>Eliminar</a>';


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

/*if($nivel==1 || $nivel==2){//ADMIN  Y SOPORTE -> TODOS 

			}
			if($nivel==3){//ING/TEC ->  ¿TODOS?

			}

			if($nivel==4){//CLIENTE SYM ->  ¿TODOS?

			}

			if($nivel==4){//CLIENTE SYM ->  ¿TODOS?

			}

			if($nivel==5){//DIRECTORES / GERENTES ->  ¿TODOS?

			}
			if($nivel==6){//QA ->  ¿TODOS?

			}


			if($nivel==7){//CLIENTE SYM -> VER Y CREAR

			}
*/
			$resultado[] = array(
				'id' 			=>	$row['id'],
				'acciones' 		=>	$acciones, 
				'nombre'		=>	$row['nombre'],
				'descripcion' 	=>	$row['descripcion'],
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
	
	function createmarcas(){
		global $mysqli; 	
		
		$nombre			= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$descripcion	= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');
		$existe 		= 0;
		
		$sql = "SELECT COUNT(id) AS total FROM marcas WHERE nombre = '".$nombre."'";
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
			$query 	= '	INSERT INTO	marcas (nombre, descripcion, fecha)	VALUES ( "'.$nombre.'", "'.$descripcion.'", now() ) ';
			$result = $mysqli->query($query);
			$id = $mysqli->insert_id;
			
			if($result == true){
				$campos = array(
					'Nombre' 		=> $nombre,
					'Descripción' 	=> $descripcion
				);
				nuevoRegistro('Marcas','Marcas',$id,$campos,$query);
				echo 1;
			}else{
				echo 0;
			}
		} 
	}
	
	function getmarcas(){
		global $mysqli;		
		$nivel		= (isset($_SESSION["nivel"]) ? $_SESSION["nivel"] : null);
		$return_delete = 0;
		if($nivel!=null){//NO SESSION


				$idmarcas = (!empty($_REQUEST['idmarcas']) ? $_REQUEST['idmarcas'] : '');
				$query 		= "	SELECT * FROM marcas WHERE id = '".$idmarcas."' ";
				$result 	= $mysqli->query($query);


				while($row = $result->fetch_assoc()){			
					$resultado = array(  
						'nombre'	 	=>	$row['nombre'],
						'descripcion' 	=>	$row['descripcion']		
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
	
	function updatemarcas(){
		global $mysqli;
		
		$id			 	= (!empty($_REQUEST['idmarcas']) ? $_REQUEST['idmarcas'] : '');
		$nombre			= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$descripcion	= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');
		$existe			= 0;
	


		$sql = "SELECT COUNT(id) AS total FROM marcas WHERE nombre = '".$nombre."' AND id !='".$id."'";
		$rsql = $mysqli->query($sql);
		if($reg = $rsql->fetch_assoc()){
			$count = $reg["total"];
			if($count >= 1){
				$existe = 1;
			}
		}

		if($existe==1){
			echo 2;

		}else{

			$valoresold = getRegistroSQL("SELECT nombre AS Nombre, descripcion AS 'Descripción' FROM marcas WHERE id = '".$id."' ");
			$query 	= '	UPDATE marcas SET nombre = "'.$nombre.'", descripcion = "'.$descripcion.'" WHERE id = "'.$id.'" ';
			$result = $mysqli->query($query);	
			
			if($result == true){
				$campos = array(
					'Nombre' 			=> $nombre,
					'Descripción' 		=> $descripcion
				);		
			    actualizarRegistro('Marcas','Marcas',$id,$valoresold,$campos,$query);
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
				WHERE activos.idmarcas = '$id' LIMIT 1;"; 

	    $existe_modelo = 0;
	    $qMod = "SELECT modelos.id FROM modelos 
                WHERE modelos.idmarcas= '$id' LIMIT 1;"; 

//	    $qMod = "SELECT modelos.id FROM modelos 
 //               	INNER JOIN marcas ON marcas.id = modelos.idmarcas
 //               WHERE modelos.id= '$id' LIMIT 1;"; 



        $rQAct = $mysqli->query($qact);
		if($rQAct->num_rows > 0){ 
            $existe_activo = 1; 
        }

        $rQMod = $mysqli->query($qMod);
		if($rQMod->num_rows > 0){ 
            $existe_modelo = 1; 
        }



		if(
			($existe_activo == 1) || 
			($existe_modelo == 1) 
		){
			echo 1;
		}else{
			echo 0;
		}
	}


	function deletemarcas(){
		global $mysqli;
		$nivel		= (isset($_SESSION["nivel"]) ? $_SESSION["nivel"] : null);
		$return_delete = 0;

		if($nivel!=null){//NO SESSION
			if($nivel!=7){//NO AUTORIZADO
				$id		= (!empty($_REQUEST['idmarcas']) ? $_REQUEST['idmarcas'] : '');
				$nombre	= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');			
				$query 	=  " DELETE FROM marcas WHERE id = '$id' ";
				$result =  $mysqli->query($query);	
				if($result == true){		
					eliminarRegistro('Marcas','Marcas',$nombre,$id,$query);
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