<?php 
	include_once("../conexion.php");
	global $mysqli;
	$servicio = $_REQUEST['servicio'];
	$sistema = $_REQUEST['sistema'];
	$actividad = $_REQUEST['actividad'];
	$buscarTodos = (!empty($_REQUEST['buscarTodos']) ? 1 : 0);
	
	$query = "SELECT p.responsable ";
	$query .= "FROM plan p ";
	if ($buscarTodos!=1) {
		$query .= "WHERE servicio = $servicio AND sistema = $sistema AND actividad = '$actividad' ";
	}
	$query .= "GROUP BY p.responsable ";
	//debug($query);
	$consulta	= $mysqli->query($query);
	$resultado 	= '';
	while ($row = $consulta->fetch_array()){
		$resultado[] = array(
			'id' => $row[0],
		 	'nombre'=> $row[0]			
 		);
	}
	//debug($resultado);
	echo '{"success": true, "resultado": ' . json_encode($resultado) . '}';
?>