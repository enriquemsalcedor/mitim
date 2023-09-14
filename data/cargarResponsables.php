<?php 
	include_once("../conexion.php");
	global $mysqli;
	//$combo = $_REQUEST['combo']; 
	$query = "SELECT usuario as id, nombre 
				FROM usuarios 
				WHERE idproveedor > 0
				ORDER BY nombre";
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