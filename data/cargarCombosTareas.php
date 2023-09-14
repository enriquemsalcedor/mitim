<?php 
	include_once("../conexion.php");
	global $mysqli;
	$query = "SELECT id, descripcion FROM tareas";
	//debug($query);
	$consulta	= $mysqli->query($query);
	$resultado 	= '';
	while($row = $consulta->fetch_array()){
		$resultado[] = array(
			'id' => $row[0],
		 	'nombre'=> $row[1]			
 		);
	}
	//debug($resultado);
	echo '{"success": true, "resultado": ' . json_encode($resultado) . '}';
?>