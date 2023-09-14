<?php
	include_once("../conexion.php");

	if (isset($_REQUEST['opcion'])) {
		$opcion = $_REQUEST['opcion'];
		
		if ($opcion=='DATOS')
			datos();
		elseif ($opcion=='CATEGORIAS')
			categorias();
		else
			return true;
	}

function datos() {
	global $mysqli;
	
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	
	$consulta = "
			SELECT 'Total' as tipo, count(id) as cantidad
			FROM incidentes2
			WHERE fechacreacion<=$hasta 
			UNION
			SELECT 'Cerradas' as tipo, count(id) as cantidad
			FROM incidentes2
			WHERE estado > 15 and fechacreacion<=$hasta 
			UNION
			SELECT 'Pendientes' as tipo, count(id) as cantidad
			FROM incidentes2
			WHERE estado < 16 and fechacreacion<=$hasta 
			UNION
			SELECT 'Activos' as tipo, count(id) as cantidad
			FROM activos
			WHERE estado = 'ACTIVO' 

			";								

	$result = $mysqli->query($consulta);
	
	$response = new StdClass;
	$rows = array();
	$i=0;
	while($row = $result->fetch_assoc()){
		$response->rows[$i]['tipo']=$row['tipo'];
		$response->rows[$i]['cantidad']=$row['cantidad'];
		$i++;
	}        
	echo json_encode($response);
}

function categorias() {
	global $mysqli;
	
	$desde = date("Y") - 1 . date("m") + 1 . "01";
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	$usuario 		 = $_SESSION['usuario'];
	$nivel 			 = $_SESSION['nivel'];
	$idempresas 	 = $_SESSION['idempresas'];
	$iddepartamentos = $_SESSION['iddepartamentos'];
	$idclientes 	 = $_SESSION['idclientes'];
	$idproyectos 	 = $_SESSION['idproyectos'];
	$query = "SELECT concat(l.nombre, ' ', p.nombre) as name, count(i.id) as y 
			FROM incidentes2 i 
			INNER JOIN proyectos p ON p.id = i.idproyectos
			INNER JOIN clientes l ON l.id = i.idclientes
			INNER JOIN usuarios u ON i.asignadoa = u.correo
			INNER JOIN usuarios j ON i.solicitante = j.correo
			WHERE i.fechacreacion <= '$hasta' ";

	if ($nivel>2) 
		$query .= "	AND i.idempresas in ($idempresas) 
			AND i.idclientes in ($idclientes)
			AND i.idproyectos in ($idproyectos) ";
			
	if($_SESSION['sitio'] != ''){
		$sitio = $_SESSION['sitio'];
		$sitio = explode(',',$sitio);
		$sitio = implode("','", $sitio);
		$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR i.unidadejecutora IN ('".$sitio."') ) ";
	}else{
		//$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
	}			
	$query .= "
			GROUP BY name ";
	$result = $mysqli->query($query);
	debug($query);
	$response = new StdClass;
	$rows = array();
	$i=0;
	while ($row = $result->fetch_assoc()){
		$rows[] = $row;
	}
	
	echo json_encode($rows);
}

?>