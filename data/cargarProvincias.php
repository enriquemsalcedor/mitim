<?php 
	include_once("../conexion.php");
	global $mysqli;
	$query = "SELECT distinct provincia from datos";
	$consulta	= $mysqli->query($query);
	$resultado 	= '';
	while($row = $consulta->fetch_array()){
		$resultado[] = array(
			'id' => $row[0],
		 	'nombre'=> $row[0]			
 		);
	}
	echo '{"success": true, "resultado": ' . json_encode($resultado) . '}';
?>