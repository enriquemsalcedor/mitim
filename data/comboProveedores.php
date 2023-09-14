<?php 
	include_once("../conexion.php");
	global $mysqli;

$oper = '';
if (isset($_REQUEST['oper'])) {
	$oper = $_REQUEST['oper'];   
}

if($oper=='select'){
	$query = "SELECT id, nombre FROM proveedores";
	$result = $mysqli->query($query);

	$combo = "<select>";
	$combo .= "<option value=''> - </option>";
	while($row = $result->fetch_assoc()){
		$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
	}
	$combo .= "</select>";
	echo $combo;
}
else{
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
}
	
?>