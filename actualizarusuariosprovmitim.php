<?php

include("conexion.php");

$query ="SELECT id,nombre FROM proveedores WHERE idcliente = 3";
$result = $mysqli->query($query);
while($row = $result->fetch_assoc()){
	$id_proveedor = $row['id'];
	$nombre_proveedor = $row['nombre'];
	 
	$update = "UPDATE usuarios SET idproveedor = ".$id_proveedor." WHERE nombre = '".$nombre_proveedor."'";
	$mysqli->query($update);
}
		
?>		