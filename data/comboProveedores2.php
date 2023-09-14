<?php 
	include_once("../conexion.php");
	global $mysqli;
	$query = "SELECT id, nombre FROM proveedores";
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