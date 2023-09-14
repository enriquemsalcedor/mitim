<?php 
	include_once("../conexion.php");
	global $mysqli;
	$query = "SELECT id, nombre FROM niveles";
	$consulta	= $mysqli->query($query);
	$combo = "<select name='nivel' id='nivel'>";
	$combo .= "<option value=''> - </option>";
	while($row = $consulta->fetch_array()){
		$combo .= "<option value='".$row[0]."'>".$row[1]."</option>";
	}
	$combo .= "</select>";
	echo $combo;
?>