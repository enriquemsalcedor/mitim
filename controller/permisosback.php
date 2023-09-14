<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "permisos": 
			  listarpermisos();
			  break;		
		case "updatepermiso":
			  updatepermiso();
			  break;
	    case "getnotificacion":
			  getnotificacion();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	

	function listarpermisos() 
	{
		global $mysqli;
		
		$draw 		= $_REQUEST["draw"];
		
    	//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
    	$orderByColumnIndex  = $_REQUEST['order'][0]['0'];// index of the sorting column (0 index based - i.e. 0 is the first record)
    	$orderBy 	= 0;//$_REQUEST['id'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
    	$orderType 	= "DESC";//$_REQUEST['order'][0]['dir']; // ASC or DESC
    	$start   	= (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);
    	$length   	= (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
    	
		$query  = " SELECT u.id as id, u.nombre as usuario, 
							nu.noti1,nu.noti2,
							nu.noti3,nu.noti4,
							nu.noti5,nu.noti6,
							nu.noti7,nu.noti8,
							nu.noti9,nu.noti10,
							nu.noti11,nu.noti12,
							nu.noti13
					from notificacionesxusuarios nu
					INNER JOIN usuarios u on u.id = nu.idusuario
					where 1 = 1 ";					
		$hayFiltros = 0;
		$query .= " GROUP BY nu.id ";
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		$query  .= " ORDER BY u.nombre ASC";
		//debug($query);
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		$response = array();
		$response = array();
		while($row = $result->fetch_assoc()){	
		    $acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center droptable">
								    <a class="dropdown-item text-info" href="permiso.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>
								</div>
							</div>
						</td>';
			$resultado[] = array(
				'id' 			=>	$row['id'],	
				'usuario'		=>	$row['usuario'], 
				'noti1'		=>	$row['noti1'] ? 'Si' : 'No',
				'noti2'		=>	$row['noti2'] ? 'Si' : 'No',
				'noti3'		=>	$row['noti3'] ? 'Si' : 'No',
				'noti4'		=>	$row['noti4'] ? 'Si' : 'No',
				'noti5'		=>	$row['noti5'] ? 'Si' : 'No',
				'noti6'		=>	$row['noti6'] ? 'Si' : 'No',
				'noti7'		=>	$row['noti7'] ? 'Si' : 'No',
				'noti8'		=>	$row['noti8'] ? 'Si' : 'No',
				'noti9'		=>	$row['noti9'] ? 'Si' : 'No',
				'noti10'	=>	$row['noti10'] ? 'Si' : 'No',
				'noti11'	=>	$row['noti11'] ? 'Si' : 'No',
				'noti12'	=>	$row['noti12'] ? 'Si' : 'No',
				'noti13'	=>	$row['noti13'] ? 'Si' : 'No',
				'acciones' 	=> $acciones
			);
		} 
        
        $response = array(
			"draw" => intval($draw),
			"recordsTotal" => intval($recordsTotal),
			"recordsFiltered" => intval($recordsTotal),
			"data" => $resultado
		  );
		;
		echo json_encode($response); 
	}

	function updatepermiso() 
	{
		global $mysqli;
		$idusuario  	 		= (!empty($_REQUEST['idusuario']) ? $_REQUEST['idusuario'] : 0);
		$noti1	= (!empty($_REQUEST['noti1']) ? $_REQUEST['noti1']: '0');
		$noti2	= (!empty($_REQUEST['noti2']) ? $_REQUEST['noti2']: '0');
		$noti3	= (!empty($_REQUEST['noti3']) ? $_REQUEST['noti3']: '0');
		$noti4	= (!empty($_REQUEST['noti4']) ? $_REQUEST['noti4']: '0');
		$noti5	= (!empty($_REQUEST['noti5']) ? $_REQUEST['noti5']: '0');
		$noti6	= (!empty($_REQUEST['noti6']) ? $_REQUEST['noti6']: '0');
		$noti7	= (!empty($_REQUEST['noti7']) ? $_REQUEST['noti7']: '0');
		$noti8	= (!empty($_REQUEST['noti8']) ? $_REQUEST['noti8']: '0');
		$noti9	= (!empty($_REQUEST['noti9']) ? $_REQUEST['noti9']: '0');
		$noti10	= (!empty($_REQUEST['noti10']) ? $_REQUEST['noti10']: '0');
		$noti11	= (!empty($_REQUEST['noti11']) ? $_REQUEST['noti11']: '0');
		$noti12	= (!empty($_REQUEST['noti12']) ? $_REQUEST['noti12']: '0');
		$noti13	= (!empty($_REQUEST['noti13']) ? $_REQUEST['noti13']: '0');
		$query = "  UPDATE notificacionesxusuarios SET noti1 = '$noti1', noti2 = '$noti2', noti3 = '$noti3', noti4 = '$noti4', 
							noti5 = '$noti5',noti6 = '$noti6',noti7 = '$noti7',noti8 = '$noti8',noti9 = '$noti9',noti10 = '$noti10',noti11 = '$noti11',
							noti12 = '$noti12',noti13 = '$noti13'
					WHERE idusuario = '$idusuario' ";
		$result = $mysqli->query($query);
		if($result==true){
			echo 1;
		}else{
			echo $query;
		} 
	}

	function getnotificacion(){
		global $mysqli;
		
		$idusuario	= (!empty($_REQUEST['idusuario']) ? $_REQUEST['idusuario'] : 0);
		$query 		= "SELECT u.nombre, nu.* from notificacionesxusuarios nu left join usuarios u on u.id = nu.idusuario where idusuario = '$idusuario'";
		$result 	= $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			$resultado = array(
				'nombre' 		=>	$row['nombre'],
				'noti1' 		=>	$row['noti1'],
				'noti2' 		=>	$row['noti2'],
				'noti3' 		=>	$row['noti3'],
				'noti4' 		=>	$row['noti4'],
				'noti5' 		=>	$row['noti5'],
				'noti6' 		=>	$row['noti6'],
				'noti7' 		=>	$row['noti7'],
				'noti8' 		=>	$row['noti8'],
				'noti9' 		=>	$row['noti9'],
				'noti10' 		=>	$row['noti10'],
				'noti11' 		=>	$row['noti11'],
				'noti12' 		=>	$row['noti12'],
				'noti13' 		=>	$row['noti13'],
			);
		}
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}
?>