<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "correos": 
			  correos();
			  break;
		case "add":
			  agregarcorreos();
			  break;
		case "edit":
			  editarcorreos();
			  break;
		case "del":
			  eliminarcorreos();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	

	function correos() 
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

		$query  = "SELECT id, nombre, correo, cargo FROM listacorreos where 1=1 $where ";
		
		if ($sidx!="")
		$query .= "ORDER BY $sidx $sord ";
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
			$response->rows[$i]['cell']=array('',$row['id'],$row['nombre'],$row['correo'],$row['cargo']);
			$i++;
		}        
		echo json_encode($response);
	}
	
	function agregarcorreos() 
	{
		global $mysqli;		
		$nombre = $_REQUEST['nombre'];
		$correo = $_REQUEST['correo'];
		$cargo = $_REQUEST['cargo'];
		
		
		$bsector  = "SELECT correo FROM listacorreos where correo = '$correo' ";
		$resultbp = $mysqli->query($bsector);
		$nbrows = $resultbp->num_rows;
		if($nbrows > 0){
			echo 1;
		} else {
			$query = "INSERT INTO listacorreos VALUES(null, '$correo', '$nombre', '$cargo')";
			$result = $mysqli->query($query);
			bitacora($_SESSION['usuario'], "ListaCorreos", "Agregar", "Agregar: ".$query);
			echo 0;
		}		
	}
	
	function editarcorreos() 
	{
		global $mysqli;
		$id = $_REQUEST['id'];
		$nombre = $_REQUEST['nombre'];
		$correo = $_REQUEST['correo'];
		$cargo = $_REQUEST['cargo'];
		
		$query = "UPDATE listacorreos SET nombre = '$nombre', correo = '$correo', cargo = '$cargo' WHERE id = '$id'";
		$result = $mysqli->query($query);
		bitacora($_SESSION['usuario'], "ListaCorreos", "Editar", "Editar: ".$query);	
	}
	
	function eliminarcorreos() 
	{
		global $mysqli;		
		$id = $_REQUEST['id'];
		
		$query = "DELETE FROM listacorreos WHERE id = '$id'";
		$result = $mysqli->query($query);
		bitacora($_SESSION['usuario'], "ListaCorreos", "Eliminar", "Eliminar: ".$query);	
	}
	
?>
