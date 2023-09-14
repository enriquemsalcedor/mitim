<?php 
include_once("../conexion.php");
global $mysqli;

$cmbActivos = '';
if (isset($_REQUEST['cmbActivos'])) {
	$cmbActivos = $_REQUEST['cmbActivos'];   
}


if($cmbActivos!='')
{
	$query = "SELECT tipo, costo FROM Activos WHERE id='".$cmbActivos."'";
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
	$query = "SELECT id, descripcion FROM Activos";
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