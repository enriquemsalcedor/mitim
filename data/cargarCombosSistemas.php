<?php 
	include_once("../conexion.php");
	global $mysqli;
	$servicio = $_REQUEST['servicio'];
	$query = "SELECT m.id, m.nombre FROM maestro m, plan p ";
	$query .= "WHERE p.sistema = m.id AND p.servicio = $servicio ";
	$query .= "GROUP BY m.nombre";
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