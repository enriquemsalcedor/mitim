<?php
    include_once("../conexion.php");
    


	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "bitacoras": 
              bitacoras();
			  break;
		case "listbitacora": 
              listbitacora();
			  break;
		case "getbitacora": 
              getbitacora();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}

	function bitacoras() 
	{
	global $mysqli;
		
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		$desde = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '');
		$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : '');
		 
		if(!$sidx) $sidx =1;	
		
		$query  = " SELECT b.nombre AS usuario, a.fecha, a.modulo, a.accion
					FROM bitacora a 
					INNER JOIN usuarios b ON a.usuario = b.usuario
					WHERE 1 = 1
					";
		
		if ($desde!="")
			$query .= "AND a.fecha >= '$desde' ";
		if ($hasta!="")
			$query .= "AND a.fecha <= '$hasta' ";
		
		$result = $mysqli->query($query); 
		$count = $result->num_rows;
		
		if( $count >0 ) {
		$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 1;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		
		$query .= " LIMIT ".$start.", ".$limit;
		$result = $mysqli->query($query);
		
		$response = new StdClass;
		
		$response->page = $page;
		$response->total = $total_pages;
		$response->records = $count;
		$i=0; $id=1;
		while($row = $result->fetch_assoc()){
			$response->rows[$i]['id']=$id;
			$response->rows[$i]['cell']=array('',$id++,$row['usuario'],$row['fecha'],$row['modulo'],$row['accion']);
			$i++;
		} 
		echo json_encode($response);	
	}
	
	function listbitacora() 
	{
		global $mysqli;		
		$where = "";
		$where2 = array();		
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');		
		
		$draw = $_REQUEST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    $start    = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	

		$rowperpage   = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);

		$usuario  = $_SESSION['usuario'];

        $vacio = array();
		$columns   = (!empty($_REQUEST['columns']) ? $_REQUEST['columns'] : $vacio);

//        $searchValue = mysqli_real_escape_string($con,$_POST['search']['value']); // Search value
        
        
    
		$nivel    = $_SESSION['nivel'];

		$query  = " SELECT a.id, b.nombre AS usuario, a.fecha, a.modulo, LEFT(a.accion,45) as accion, a.accion  as acciontt, a.identificador, a.sentencia AS sentenciatt , LEFT(a.sentencia,45) as sentencia
					FROM bitacora a 
					INNER JOIN usuarios b ON a.usuario = b.usuario
					WHERE 1 = 1
					";

//        echo var_dump($_REQUEST);


		if($nivel == 5 || $nivel == 7){
			$query .=" AND a.usuario = '".$usuario."'";
		}
		$hayFiltros = 0;
		for($i=0 ; $i<count($columns);$i++){
			$column = $_REQUEST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			if ($_REQUEST['columns'][$i]['search']['value']!="") {

                
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);
				
				if ($column == 'usuario') {
					$column = 'b.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}

				if ($column == 'fecha') {
					$column = 'a.fecha';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'modulo') {
					$column = 'a.modulo';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'accion') {
					$column = 'a.accion';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'identificador') {
					$column = 'a.identificador';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'sentencia') {
					$column = 'a.sentencia';
					$where2[] = " $column like '%".$campo."%' ";
				}
//				$where2[] = " $column like '%".$campo."%' ";


				$hayFiltros++;
			}
		}		
		if ($hayFiltros > 0)
			$where = " AND ".implode(" AND " , $where2)." ";// id like '%searchValue%' or name like '%searchValue%'
		else
			$where = "";
		

		$searchGeneral   = (!empty($_POST['search']['value']) ? $_POST['search']['value'] : '');		
		if($searchGeneral != ''){
			$where.= " AND (
							b.nombre like '%".$searchGeneral."%' OR
							a.fecha like '%".$searchGeneral."%' OR
							a.modulo like '%".$searchGeneral."%' OR
							a.accion like '%".$searchGeneral."%'
			)";


		}

		$query .= " GROUP BY a.id ";


		$query  .= " $where ";
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
//		$query  .= " ORDER BY a.id DESC LIMIT $start, $length ";
		$query  .= " ORDER BY a.id DESC  LIMIT ".$start.",".$rowperpage;
//		$query  .= "GROUP BY a.id, b.nombre AS usuario, a.fecha, a.modulo, a.accion, a.identificador, a.sentencia ORDER BY a.id DESC";
		//debug($query);
		$resultado = array();
		$result = $mysqli->query($query);
//		echo $query;

		$recordsFiltered = $result->num_rows;
		$response = array();
		$response['data'] = array();
		$resultado = array();
//		echo $query;
		
		while($row = $result->fetch_assoc()){	

			$sentencia ="";
			$longsentencia = strlen($row['sentencia']);
			if($longsentencia>42){
				$points = " ...";
				$sentencia = "<span data-toggle='tooltip' class='prueba' data-placement='right' data-original-title='".$row['sentenciatt']."'>".$row['sentenciatt'].$points."</span>";
			}else{ 
				$sentencia = $row['sentencia'];
			}

			$accion ="";
			$longaccion = strlen($row['accion']);
			if($longaccion>42){
				$points = " ...";
				$accion = "<span data-toggle='tooltip' class='prueba' data-placement='right' data-original-title='".$row['acciontt']."'>".$row['accion'].$points."</span>";
			}else{ 
				$accion = $row['accion'];
			}

		    $acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center droptable">
								    <a class="dropdown-item text-warning" href="bitacora-ne.php?id='.$row['id'].'"><i class="fas fa-eye mr-2"></i>Ver</a>
								</div>
							</div>
						</td>';
		    $resultado[] = array(			
				'id' 			=>	$row['id'],	
				'acciones' 	=> $acciones,
				'usuario'		=>	$row['usuario'], 
				'fecha'			=>	$row['fecha'],
				'modulo'		=>	$row['modulo'],
				'accion'		=>	$row['acciontt'],/*
				'identificador'	=>	$row['identificador'],
				'sentencia'		=>	$sentencia,*/
			);
		}


		$response = array(
		  "draw" => intval($draw),
		  "recordsTotal" => intval($recordsTotal),
		  "recordsFiltered" => intval($recordsTotal),
		  "data" => $resultado,
//		  "sql"  => $query
		);
        
        
		
		echo json_encode($response);
	}
	
	function getbitacora(){
		global $mysqli;
		
		$idbitacora	= $_REQUEST['idbitacora'];
		$query 		= "	SELECT * FROM bitacora WHERE id = '$idbitacora' ";
		$result 	= $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			$resultado = array(
				'usuario'		=>	$row['usuario'], 
				'fecha' 		=>	$row['fecha'],
				'modulo' 		=>	$row['modulo'],
				'accion' 		=>	$row['accion'],
				'identificador' =>	$row['identificador'],
				'sentencia' 	=>	$row['sentencia']
			);
		}
			if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}
	
?>