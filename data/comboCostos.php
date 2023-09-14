<?php 
include_once("../conexion.php");
global $mysqli;

$cmbCostos = '';
if (isset($_REQUEST['cmbCostos'])) {
	$cmbCostos = $_REQUEST['cmbCostos'];   
}


if($cmbCostos!='')
{
	$query = "SELECT unidad, costo_unidad FROM costos WHERE id='".$cmbCostos."'";
	//debug($query);
	$consulta	= $mysqli->query($query);
	$resultado 	= '';
	while($row = $consulta->fetch_array()){
		$resultado[] = array(
			'unidad' => $row[0],
		 	'costo_unidad'=> $row[1]
 		);
	}
	echo '{"success": true, "resultado": ' . json_encode($resultado) . '}';
}
else
{
	$query = "SELECT id, descripcion FROM costos";
	$consulta	= $mysqli->query($query);
	$resultado 	= '';
	while($row = $consulta->fetch_array()){
		$resultado[] = array(
			'id' => $row[0],
		 	'descripcion'=> $row[1]			
 		);
	}
	echo '{"success": true, "resultado": ' . json_encode($resultado) . '}';
}
	
?>