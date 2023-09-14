<?php 
	include_once("../conexion.php");
	global $mysqli;
	$servicio = $_REQUEST['servicio'];
	$sistema = $_REQUEST['sistema'];
	$query = "SELECT id, actividad FROM plan ";
	$query .= "WHERE sistema = $sistema AND servicio = $servicio ";
	$query .= "GROUP BY actividad";
	$consulta	= $mysqli->query($query);
	$resultado 	= '';
	while($row = $consulta->fetch_array()){
		$resultado[] = array(
			'id' => $row[0],
		 	'nombre'=> $row[1]			
 		);
	}
	echo '{"success": true, "resultado": ' . json_encode($resultado) . '}';
?>