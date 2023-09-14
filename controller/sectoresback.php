<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "sectores": 
			  sectores();
			  break;
		case "add":
			  agregarsectores();
			  break;
		case "edit":
			  editarsectores();
			  break;
		case "del":
			  eliminarsectores();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	

	function sectores() 
	{
		global $mysqli;		
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		if(!$sidx) $sidx =1;
		$where = "";
		if ($_GET['_search'] == 'true' && !isset($_GET['filters'])) {
			$searchField = $_GET['searchField'];
			$searchOper = $_GET['searchOper'];
			$searchString = $_GET['searchString'];
			$where = getWhereClause($searchField,$searchOper,$searchString);
		} elseif ($_GET['_search'] == 'true') {
			$filters = $_GET['filters'];
			$where = getWhereClauseFilters($filters);
		}

		$query  = "SELECT id, nombre, descripcion FROM maestro WHERE tipo = 'Sectores' $where ";
		
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
		$i=0;
		while($row = $result->fetch_assoc()){
			$response->rows[$i]['id']=$row['id'];
			$response->rows[$i]['cell']=array('',$row['id'],$row['nombre'],$row['descripcion']);
			$i++;
		}        
		echo json_encode($response);
	}
	
	function agregarsectores() 
	{
		global $mysqli;		
		$nombre = $_REQUEST['nombre'];
		$descripcion = $_REQUEST['descripcion'];
		
		$bsector  = "SELECT nombre FROM maestro where nombre = '$nombre' AND tipo = 'Sectores' ";
		$resultbp = $mysqli->query($bsector);
		$nbrows = $resultbp->num_rows;
		if($nbrows > 0){
			echo 1;
		} else {
			$query = "INSERT INTO maestro VALUES(null, '$nombre', '$descripcion', 'Sectores', '0')";
			$result = $mysqli->query($query);
			bitacora($_SESSION['usuario'], "Sectores", "Agregar", "Agregar: ".$query);
			echo 0;
		}		
	}
	
	function editarsectores() 
	{
		global $mysqli;
		$id = $_REQUEST['id'];
		$nombre = $_REQUEST['nombre'];
		$descripcion = $_REQUEST['descripcion'];
		
		$query = "UPDATE maestro SET nombre = '$nombre', descripcion = '$descripcion' WHERE id = '$id'";
		$result = $mysqli->query($query);
		bitacora($_SESSION['usuario'], "Sectores", "Editar", "Editar: ".$query);	
	}
	
	function eliminarsectores() 
	{
		global $mysqli;		
		$id = $_REQUEST['id'];
		
		$query = "DELETE FROM maestro WHERE id = '$id'";
		$result = $mysqli->query($query);
		bitacora($_SESSION['usuario'], "Sectores", "Eliminar", "Eliminar: ".$query);	
	}
	
?>