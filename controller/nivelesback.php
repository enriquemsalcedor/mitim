<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "niveles": 
			  niveles();
			  break;		
		case "createnivel":
			  createnivel();
			  break;
		case "updatenivel":
			  updatenivel();
			  break;
	    case "getnivel":
			  getnivel();
			  break;
		case "deletenivel":
			  deletenivel();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	

	function niveles() 
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
		//ASC or DESC
		*/
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); 
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);

		$query  = " SELECT * FROM niveles WHERE id!=0 ";
		/*--------------------------------------------------------------------
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
				
				if ($column == 'nombre') {
					$column = 'nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				
				if ($column == 'descripcion') {
					$column = 'descripcion';
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
			nombre like '%".$searchGeneral."%' OR
			descripcion like '%".$searchGeneral."%')";
    	}
		
		$query  .= " $where ";
		*/
		debugL($query,"prioridades");
	    $query .= " GROUP BY id ";
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		$query  .= " ORDER BY nombre ASC";
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		$response = array();
		$resultado = array();
		
		while($row = $result->fetch_assoc()){	
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center droptable">
									<a class="dropdown-item text-info" href="nivel.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>';
            $acciones .= '<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>';
            $acciones .= '</div>
							</div>
						</td>';
			$resultado[] = array(			
				'id' 			=>	$row['id'],	
				'nombre'		=>	$row['nombre'], 
				'descripcion'	=>	$row['descripcion'],
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
	
	function  createnivel()
	{
		global $mysqli;
		$nombre			= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : ''); 
		$descripcion	= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion']: '');
		$noti1	= (!empty($_REQUEST['noti1']) ? $_REQUEST['noti1']: '');
		$noti2	= (!empty($_REQUEST['noti2']) ? $_REQUEST['noti2']: '');
		$noti3	= (!empty($_REQUEST['noti3']) ? $_REQUEST['noti3']: '');
		$noti4	= (!empty($_REQUEST['noti4']) ? $_REQUEST['noti4']: '');
		$noti5	= (!empty($_REQUEST['noti5']) ? $_REQUEST['noti5']: '');
		$noti6	= (!empty($_REQUEST['noti6']) ? $_REQUEST['noti6']: '');
		$noti7	= (!empty($_REQUEST['noti7']) ? $_REQUEST['noti7']: '');
		$noti8	= (!empty($_REQUEST['noti8']) ? $_REQUEST['noti8']: '');
		$noti9	= (!empty($_REQUEST['noti9']) ? $_REQUEST['noti9']: '');
		$noti10	= (!empty($_REQUEST['noti10']) ? $_REQUEST['noti10']: '');
		$noti11	= (!empty($_REQUEST['noti11']) ? $_REQUEST['noti11']: '');
		$noti12	= (!empty($_REQUEST['noti12']) ? $_REQUEST['noti12']: '');
		$noti13	= (!empty($_REQUEST['noti13']) ? $_REQUEST['noti13']: '');
		
		$query = " INSERT INTO niveles VALUES (null,'$nombre','$descripcion',curdate()) ";
		$result = $mysqli->query($query);
	    	
		if($result==true){		    
		    $idnivel = $mysqli->insert_id;	    
			$query = " INSERT INTO notificacionesxniveles VALUES (null,'$idnivel','$noti1','$noti2','$noti3','$noti4','$noti5','$noti6','$noti7','$noti8','$noti9','$noti10','$noti11','$noti12','$noti13') ";
			$result = $mysqli->query($query);
		    bitacora($_SESSION['usuario'], "Niveles", "El Nivel #".$idnivel." ha sido creada", $idnivel, $query);
			echo 1;
		}else{
			echo $query;
		}
	}
		
	function updatenivel() 
	{
		global $mysqli;
		$id  	 		= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
		$nombre			= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : ''); 
		$descripcion	= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion']: '');
		$noti1	= (!empty($_REQUEST['noti1']) ? $_REQUEST['noti1']: '');
		$noti2	= (!empty($_REQUEST['noti2']) ? $_REQUEST['noti2']: '');
		$noti3	= (!empty($_REQUEST['noti3']) ? $_REQUEST['noti3']: '');
		$noti4	= (!empty($_REQUEST['noti4']) ? $_REQUEST['noti4']: '');
		$noti5	= (!empty($_REQUEST['noti5']) ? $_REQUEST['noti5']: '');
		$noti6	= (!empty($_REQUEST['noti6']) ? $_REQUEST['noti6']: '');
		$noti7	= (!empty($_REQUEST['noti7']) ? $_REQUEST['noti7']: '');
		$noti8	= (!empty($_REQUEST['noti8']) ? $_REQUEST['noti8']: '');
		$noti9	= (!empty($_REQUEST['noti9']) ? $_REQUEST['noti9']: '');
		$noti10	= (!empty($_REQUEST['noti10']) ? $_REQUEST['noti10']: '');
		$noti11	= (!empty($_REQUEST['noti11']) ? $_REQUEST['noti11']: '');
		$noti12	= (!empty($_REQUEST['noti12']) ? $_REQUEST['noti12']: '');
		$noti13	= (!empty($_REQUEST['noti13']) ? $_REQUEST['noti13']: '');
		$query = "  UPDATE niveles SET nombre = '$nombre', descripcion = '$descripcion'
					WHERE id = '$id' ";
		$result = $mysqli->query($query);
		$query = "  UPDATE notificacionesxniveles SET noti1 = '$noti1', noti2 = '$noti2', noti3 = '$noti3', noti4 = '$noti4', 
							noti5 = '$noti5',noti6 = '$noti6',noti7 = '$noti7',noti8 = '$noti8',noti9 = '$noti9',noti10 = '$noti10',noti11 = '$noti11',
							noti12 = '$noti12',noti13 = '$noti13'
					WHERE idnivel = '$id' ";
		$result = $mysqli->query($query);
		if($result==true){
		    $idnivel = $id;
		    bitacora($_SESSION['usuario'], "Niveles", "El Nivel #".$idnivel." ha sido actualizada", $idnivel, $query);
			echo 1;
		}else{
			echo $query;
		} 
	}

	function getnivel(){
		global $mysqli;
		
		$idniveles	= (!empty($_REQUEST['idniveles']) ? $_REQUEST['idniveles'] : 0);
		$query 		= "	SELECT * FROM niveles WHERE id = '$idniveles' ";
		$result 	= $mysqli->query($query);
		$querynotificacion 		= "SELECT * from notificacionesxniveles where idnivel = '$idniveles'";
		$resultnotificacion 	= $mysqli->query($querynotificacion);
		while($row = $result->fetch_assoc()){
			$rowresultados = $resultnotificacion ->fetch_assoc();
			$resultado = array(
				'nombre'			=>	$row['nombre'], 
				'descripcion' 		=>	$row['descripcion'],
				'noti1' 		=>	$rowresultados['noti1'],
				'noti2' 		=>	$rowresultados['noti2'],
				'noti3' 		=>	$rowresultados['noti3'],
				'noti4' 		=>	$rowresultados['noti4'],
				'noti5' 		=>	$rowresultados['noti5'],
				'noti6' 		=>	$rowresultados['noti6'],
				'noti7' 		=>	$rowresultados['noti7'],
				'noti8' 		=>	$rowresultados['noti8'],
				'noti9' 		=>	$rowresultados['noti9'],
				'noti10' 		=>	$rowresultados['noti10'],
				'noti11' 		=>	$rowresultados['noti11'],
				'noti12' 		=>	$rowresultados['noti12'],
				'noti13' 		=>	$rowresultados['noti13'],
			);
		}
			if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}
	
	

	function deletenivel() 
	{
		global $mysqli;		
		$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
		$queryvalidar = "SELECT * from usuarios where nivel =".$id;
		$resultvalidar = $mysqli->query($queryvalidar);
		if($resultvalidar->num_rows == 0){
			$query = "DELETE FROM niveles WHERE id = '$id' ";		
			$result = $mysqli->query($query);
			if($result==true){		    
				bitacora($_SESSION['usuario'], "Niveles", "El Nivel #".$id." ha sido eliminada", $id , $query);
				echo 1;		    
			}else{
				echo 0;
			}
		}else{
			echo 2;
		}
	}	
?>