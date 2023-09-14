<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "subtipos": 
			  subtipos();
			  break;		
		case "guardarSubtipo":
			  guardarSubtipo();
			  break;
		case "actualizarSubtipo":
			  actualizarSubtipo();
			  break;
	    case "getSubtipo":
			  getSubtipo();
			  break;
		case "eliminarSubtipo":
			  eliminarSubtipo();
			  break;
	    case "cargarCampos":
			  cargarCampos();
			  break;
		case "guardarCampo":
			  guardarCampo();
			  break;
		case "eliminarCampo":
			  eliminarCampo();
			  break;
		case "existeCampos":
			  existeCampos();
			  break;
		case "eliminarCamposTemp":
			  eliminarCamposTemp();
			  break;
		case "getCampos":
			  getCampos();
			  break;
		case "hayRelacion":
			  hayRelacion();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	

	function subtipos() 
	{
		global $mysqli;		
		$where  = array();
		$where2 = "";		
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');		
		
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);
		//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    $orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );
		//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); 
		// ASC or DESC
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		
		$nivel				 = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		$idclientes 		 = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : 0);
		$idproyectos 		 = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : 0); 
		
		
		$query  = " SELECT a.id, a.nombre AS subtipo, b.nombre AS tipo, c.nombre AS cliente, d.nombre AS proyecto
		            FROM activossubtipos a 
					LEFT JOIN activostipos b ON b.id = a.idtipo
					LEFT JOIN clientes c ON c.id = a.idcliente
					LEFT JOIN proyectos d ON d.id = a.idproyecto
		            WHERE 1 = 1 "; 
		            
		if($nivel == 4 || $nivel == 7){
			if($idclientes != ''){
				$arr = strpos($idclientes, ',');
				if ($arr !== false) {
					$query  .= " AND a.idcliente IN (".$idclientes.") ";
				}else{
					$query  .= " AND find_in_set(".$idclientes.",a.idcliente) ";
				}  
			}
			if($idproyectos != ''){
				$arr = strpos($idproyectos, ',');
				if ($arr !== false) {
					$query  .= " AND a.idproyecto IN (".$idproyectos.") ";
				}else{
					$query  .= " AND find_in_set(".$idproyectos.",a.idproyecto) ";
				}  
			}	
		}
		/*$hayFiltros = 0;
		for($i=0 ; $i<count($_REQUEST['columns']);$i++){
			$column = $_REQUEST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			if ($_REQUEST['columns'][$i]['search']['value']!="") {
				
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo); 
				
				if ($column == 'tipo') {
					$column = 'b.nombre';
					$where[] = " $column like '%".$campo."%' ";
				} 
				
				if ($column == 'subtipo') {
					$column = 'a.nombre';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'cliente') {
					$column = 'c.nombre';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'proyecto') {
					$column = 'd.nombre';
					$where[] = " $column like '%".$campo."%' ";
				} 
				
				$hayFiltros++;
			}
		}
		
		if ($hayFiltros > 0)
			$where = " AND ".implode(" AND " , $where)." ";// id like '%searchValue%' or name like '%searchValue%'
		else
			$where = "";
		
		$query  .= " $where $where2";*/
		$query .= " GROUP BY a.id ";
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		$query  .= " ORDER BY a.id DESC LIMIT $start, $length ";
		//echo "SUBTIPOS:".$query;
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		
		$response ['data']= array();
		
		while($row = $result->fetch_assoc()){	
			
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center">
								    <a class="dropdown-item text-info" href="subambiente.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>
								    <a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
								</div>
							</div>
						</td>';

			$response['data'][] = array(
			    'id' 		=>	$row['id'],	
				'acciones' 	=>$acciones, 
				'tipo' 		=>	$row['tipo'], 
				'subtipo' 	=>	$row['subtipo'], 
				'cliente' 	=>	$row['cliente'], 
				'proyecto' 	=>	$row['proyecto'] 
			);
		}
		echo json_encode($response); 
	}
	
	function  guardarSubtipo()
	{
		global $mysqli;
		
		$idtipo    = (!empty($_REQUEST['idtipo']) ? $_REQUEST['idtipo'] : 0);  
		$nombre    = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : 0);  
		$idcliente = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : 0);  
		$idproyecto = (!empty($_REQUEST['idproyecto']) ? $_REQUEST['idproyecto'] : 0);  
		$creadopor = (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : 0);   
		
		$query  = " INSERT INTO activossubtipos 
					(idtipo,nombre,idcliente,idproyecto) VALUES 
					('".$idtipo."','".$nombre."','".$idcliente."','".$idproyecto."') ";
	    //echo $query;
		$result = $mysqli->query($query); 
		if($result==true){
		    $idsubtipo = $mysqli->insert_id;
			
			$queryD = " UPDATE activossubtiposcampos SET 
						idsubtipo = '".$idsubtipo."' 
						WHERE idsubtipo = 0 
						AND creadopor = '".$creadopor."'";
			//echo $queryD;
			$result = $mysqli->query($queryD);
			
		    bitacora($_SESSION['usuario'], "Subtipos", "El Subtipo #".$idsubtipo." ha sido creado", $idsubtipo, $query);			
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function actualizarSubtipo() 
	{
		global $mysqli;
		$id 	   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);   
		$idtipo    = (!empty($_REQUEST['idtipo']) ? $_REQUEST['idtipo'] : 0);  
		$nombre    = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : 0);  
		$idcliente = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : 0);  
		$idproyecto = (!empty($_REQUEST['idproyecto']) ? $_REQUEST['idproyecto'] : 0); 
		$creadopor = (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : 0);    
						 
		$campos = array( 
						'Cliente'  => getValores('nombre','clientes',$idcliente),
						'Proyecto' => getValores('nombre','proyectos',$idproyecto),
						'Tipo' 	     => getValores('nombre','activostipos',$idtipo),
						'Nombre'     => $nombre 
					);
		
		$valoresold = getRegistroSQL("  SELECT c.nombre AS 'Cliente', d.nombre AS 'Proyecto', a.nombre AS 'Tipo', a.nombre AS 'Nombre'
										FROM activossubtipos a 
										LEFT JOIN activostipos b ON b.id = a.idtipo 
										LEFT JOIN clientes c ON c.id = a.idcliente 
										LEFT JOIN proyectos d ON d.id = a.idproyecto 
										WHERE 1 = 1
										AND a.id = '".$id."' "); 
		
		$query  = " UPDATE activossubtipos SET 
					idtipo = '".$idtipo."',
					nombre = '".$nombre."',  
					idcliente = '".$idcliente."',  
					idproyecto = '".$idproyecto."'  
					WHERE id = '".$id."' ";	
					//echo $query;
		$result = $mysqli->query($query);
		
		if($result == true){ 
		
			$queryC = " UPDATE activossubtiposcampos SET
						idsubtipo = '".$id."'
						WHERE idsubtipo = 0 
						AND creadopor = '".$creadopor."'";
						
			$resultC = $mysqli->query($queryC);			
		    actualizarRegistro('Subtipos','Subtipos',$id,$valoresold,$campos,$query);
			
			echo 1;
		}else{
			echo 0;
		}
	}
 
	function limpiarFecha($fecha){
		if($fecha == 'null'){
			$fecha = str_replace("'","",$fecha); 
		}else{
			$fecha = "'".$fecha."'"; 
		}
		return $fecha;
	}
	
	function getSubtipo(){
		global $mysqli;
		
		$idsubtipo = (!empty($_REQUEST['idsubtipo']) ? $_REQUEST['idsubtipo'] : '');
		
		$query 	= "	SELECT * FROM activossubtipos WHERE id = '".$idsubtipo."' ";
		$result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			$resultado = array(
				'idcliente' 		  =>	$row['idcliente'],  
				'idproyecto' 		  =>	$row['idproyecto'],  
				'id' 			  	  =>	$row['id'],  
				'idtipo' 		      =>	$row['idtipo'],
				'nombre' 			  =>	$row['nombre']
			);
		}
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo 0;
		}
	} 	
	
	/*************************************************CAMPOS DE SUBTIPOS****************************************************/
	
	function cargarCampos() 
		{
			global $mysqli;
			$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
			$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
			$orderBy	= (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
			$orderType	= (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
			$start   	= (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);
			$length   = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
			$idsubtipo =  (!empty($_REQUEST['idsubtipo']) ? $_REQUEST['idsubtipo'] : 0);    
			$usuario     =  (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : '');   
			 
			$query = "  SELECT a.id,a.nombre,a.tipo,a.opciones
						FROM activossubtiposcampos a  
						WHERE 1 = 1 AND idsubtipo = ".$idsubtipo." ";
			if($idsubtipo == 0){
				$query .= "	AND creadopor = '".$usuario."' ";
			}else{
				$query .= "	OR (idsubtipo = 0 AND creadopor = '".$usuario."')";
			}			 
			while(!$result = $mysqli->query($query)){
				die($mysqli->error);  
			 } 		
			
			$resultado = array();
			//echo $query;
			while($row = $result->fetch_assoc()){  
				$resultado[] = array(
					'id'	   => $row['id'],
					'acciones' => "<div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>
										<span class='icon-col red fa fa-trash boton-eliminar' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Campo' data-placement='right'></span>  
								  </div>",
					'nombre'   => $row['nombre'], 
					'tipo' 	   => $row['tipo'], 
					'opciones' => $row['opciones']
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
		
		function guardarCampo(){
			global $mysqli;
			
			$idsubtipo 	= (!empty($_REQUEST["idsubtipo"]) ? $_REQUEST["idsubtipo"] : 0);
			$nombre 	= (!empty($_REQUEST["nombre"]) ? $_REQUEST["nombre"] : "");
			$tipo 		= (!empty($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : ""); 
			$opciones 	= (!empty($_REQUEST["opciones"]) ? $_REQUEST["opciones"] : ""); 
			$creadopor 	= (!empty($_SESSION["usuario"]) ? $_SESSION["usuario"] : "");

			$query = '	INSERT INTO activossubtiposcampos (idsubtipo,nombre,tipo,opciones,creadopor) 
						VALUES( "'.$idsubtipo.'", "'.$nombre.'", "'.$tipo.'",  "'.$opciones.'", "'.$creadopor.'")';
			//echo $query;
			$result = $mysqli->query($query);

			if($result==true){
				$id = $mysqli->insert_id;
				//Bitácora
				$campos = array(
					'Subtipo'  => $idsubtipo,
					'Nombre'   => $nombre, 
					'Tipo'     => $tipo, 					
					'Opciones' => $opciones				
					);
				nuevoRegistro('Subtipos','Subtipos',$id,$campos,$query);				
				echo $id;
			}else{
				echo 0;
			}
		}
		
		function eliminarCampo() {
			global $mysqli;		
			$id 	= $_REQUEST['id'];
			$nombre = $_REQUEST['nombre'];
			
			$query = "DELETE FROM activossubtiposcampos WHERE id = ".$id."";
			
			$result = $mysqli->query($query);
			 if($result==true){
				eliminarRegistro('Subtipos','Subtipos',$nombre,$id,$query);
				echo 1;		    
			}else{
				echo 0;
			} 	
		}
		
		function eliminarSubtipo() {
			global $mysqli;		
			$id 	= $_REQUEST['id'];
			$nombre = $_REQUEST['nombre'];
			
			//Eliminar Subtipo
			$query = "DELETE FROM activossubtipos WHERE id = ".$id."";
			debugL("ELIMINARSUBTIPO:".$query);
			$result = $mysqli->query($query);
			 if($result==true){
				 
				//Eliminar campos del Subtipo 
				$queryC = "DELETE FROM activossubtiposcampos WHERE idsubtipo = '".$id."'"; 
				$resultC = $mysqli->query($queryC);
				
				eliminarRegistro('Subtipos','Subtipos',$nombre,$id,$query);
				echo 1;		    
			}else{
				echo 0;
			} 	
		}
		
		function existeCampos(){
			global $mysqli;
			 
			$idsubtipo = (!empty($_REQUEST["idsubtipo"]) ? $_REQUEST["idsubtipo"] : 0);
			
			$query = "  SELECT COUNT(*) AS total FROM activossubtiposcampos 
						WHERE idsubtipo = ".$idsubtipo." ";
			//echo $query;
			$result = $mysqli->query($query); 
			if($row = $result->fetch_assoc()){
				$total = $row["total"];
				if($total > 0){
					echo 1;
				}else{
					echo 0;
				}
			}
		}
		
		function hayRelacion(){
			global $mysqli;
			 
			$id = (!empty($_REQUEST["id"]) ? $_REQUEST["id"] : 0);
			
			$query = "  SELECT COUNT(*) AS total FROM activos 
						WHERE idsubtipo = ".$id." ";
			//echo $query;
			$result = $mysqli->query($query); 
			if($row = $result->fetch_assoc()){
				$total = $row["total"];
				if($total > 0){
					echo 1;
				}else{
					echo 0;
				}
			}
		}
		
		function eliminarCamposTemp(){
			global $mysqli;	  
			$creadopor = $_SESSION["usuario"];
			
			$query = " DELETE FROM activossubtiposcampos WHERE idsubtipo = 0 AND creadopor = '".$creadopor."'";
			//echo $query;
			$result = $mysqli->query($query);
			
			if($result == true){ 
				echo 1;
			}else{ 
				echo 0;
			}
		}
		
	function getCampos(){
		global $mysqli;
		
		$idsubtipo = (!empty($_REQUEST['idsubtipo']) ? $_REQUEST['idsubtipo'] : '');
		
		$query 	= "	SELECT * FROM activossubtiposcampos WHERE idsubtipo = '".$idsubtipo."' ";
		//echo $query;
		$result = $mysqli->query($query);
		$resultado = array();
		while($row = $result->fetch_assoc()){
			$resultado[] = array(
				'id' 		=>	$row['id'],  
				'nombre' 	=>	$row['nombre'],  
				'tipo' 		=>	$row['tipo'],  
				'opciones'  =>	$row['opciones'] 
			);
		}
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo 0;
		}
	}
?>