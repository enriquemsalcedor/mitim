<?php
	include("../conexion.php"); 
	if (isset($_REQUEST['opcion'])) {
		$opcion = $_REQUEST['opcion'];
		
		if ($opcion == 'estados')
			estados();
		elseif ($opcion == 'categorias')
			categorias();
		elseif ($opcion == 'datosBase')
			datosBase(); 
		elseif ($opcion == 'incidentesMeses')
			incidentesMeses();
		elseif ($opcion == 'incidentesUsuarios')
			incidentesUsuarios();
		elseif ($opcion == 'fueraservicio')
			fueraservicio();
		elseif ($opcion == 'incidentesContadores')
			incidentesContadores();
		else
			return true;
	}
	
	function incidentesContadores(){
		global $mysqli;
		
		$asignados   = 0;
		$resueltos   = 0;
		$pendientes  = 0;
		
		$idusuario   = $_SESSION['user_id'];
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$tiempo  	 = (!empty($_REQUEST['tiempo']) ? $_REQUEST['tiempo'] : 'todo');
		$modulo  	 = (!empty($_REQUEST['modulo']) ? $_REQUEST['modulo'] : 'incidentes');
		if($tiempo == 'dia'){ 
			$rangofecha = ' YEAR(fechacreacion) = YEAR(NOW()) AND MONTH(fechacreacion) = MONTH(NOW()) AND DAY(fechacreacion) = DAY(NOW()) ';
		}elseif($tiempo == 'semana'){ 
			$rangofecha = ' YEAR(fechacreacion) = YEAR(NOW()) AND WEEKOFYEAR(fechacreacion) = WEEKOFYEAR(NOW()) ';
		}elseif($tiempo == 'mes'){ 
			$rangofecha = ' YEAR(fechacreacion) = YEAR(NOW()) AND MONTH(fechacreacion)=MONTH(NOW()) ';
		}elseif($tiempo == 'todo'){
			$rangofecha = ' YEAR(fechacreacion) = YEAR(NOW()) ';
		} 
		if($modulo == "correctivos"){
			$modulo = "incidentes";
		}
		if($modulo != ""){
			$rangofecha .= " AND tipo IN ('$modulo')";
		}
		
		if($idproyectos != ""){
			$rangofecha .= " AND b.idproyectos IN ($idproyectos)"; 
		}
		$sql = "SELECT 
				IFNULL(SUM(case when b.idestados = 13 then 1 else 0 end),0) as asignados, 
				IFNULL(SUM(case when b.idestados = 16 then 1 else 0 end),0) as resueltos, 
				IFNULL(SUM(case when b.idestados != 13 AND b.idestados != 16 then 1 else 0 end),0) as pendientes
				FROM incidentes b  
				LEFT JOIN usuarios j ON b.solicitante = j.correo
				LEFT JOIN usuarios l ON b.asignadoa = l.correo
				WHERE ".$rangofecha."";
		//$sql .= permisos('dashboard', '', $idusuario);
		$permisosusuario = permisos('dashboard', '', $idusuario);
		$permisosinc = str_replace('a.','b.',$permisosusuario);
		$sql .= $permisosinc;
		//debugL($sql,"debugLCONTADORES");
		$res = $mysqli->query($sql);				
		if($reg = $res->fetch_assoc()){
			$asignados = $reg["asignados"];
			$resueltos = $reg["resueltos"];
			$pendientes  = $reg["pendientes"];
		}
		
		echo json_encode(array( 'asignados' => $asignados, 'resueltos' => $resueltos, 'pendientes' => $pendientes  )	);
	}
	
	function fueraservicio() {
		
		global $mysqli;
		
		$nodisponibles = 0;
		$disponibles = 0;
		$existe		 = 1;
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$tiempo  	 = (!empty($_REQUEST['tiempo']) ? $_REQUEST['tiempo'] : '');
		$modulo  	  = (!empty($_REQUEST['modulo']) ? $_REQUEST['modulo'] : 'incidentes');
		$totalactivos = getCount("activos");
		$modulo = str_replace('correctivos','incidentes',$modulo);
		 
		if($tiempo == 'dia'){ 
			$rangofecha = ' YEAR(b.fechacreacion) = YEAR(NOW()) AND MONTH(b.fechacreacion) = MONTH(NOW()) AND DAY(b.fechacreacion) = DAY(NOW()) ';
		}elseif($tiempo == 'semana'){ 
			$rangofecha = ' WEEKOFYEAR(b.fechacreacion) = WEEKOFYEAR(NOW()) ';
		}elseif($tiempo == 'mes'){ 
			$rangofecha = ' YEAR(b.fechacreacion) = YEAR(NOW()) AND MONTH(b.fechacreacion)=MONTH(NOW()) ';
		}else{
			$rangofecha = '1';
		}
		if($modulo != ""){
			$rangofecha .= " AND b.tipo IN ('$modulo')";
		}
		if($idproyectos != ""){
			$rangofecha .= " AND b.idproyectos IN ($idproyectos)";
			$filtroproy = "AND b.idproyectos IN ($idproyectos)";
		} 
		if($modulo == 'preventivos'){
			$existe = 0;
		}else{
			$permisosusuarios .= permisos('dashboard', '', $idusuario);
			$rangofechab = str_replace('b.','i.',$rangofecha);
			$sql = " SELECT
						a.id AS idactivo,
						MAX(i.id) AS idincidente,
						(SELECT b.fueraservicio FROM incidentes b WHERE b.id=MAX(i.id) ".$permisosusuarios." AND ".$rangofecha.") AS fueraservicio,
						(SELECT b.idestados FROM incidentes b WHERE b.id=MAX(i.id) ".$permisosusuarios." AND ".$rangofecha.") AS idestados 
					FROM
					activos a
					LEFT JOIN incidentes i ON i.idactivos = a.id  AND  i.tipo = 'incidentes' AND i.idactivos != 16
					WHERE a.fueraservicio = 1
					AND ".$rangofechab."";
			
			$permisosinc = str_replace('b.','i.',$permisosusuarios);
			$sql .= $permisosinc;

			$sql .= " GROUP BY a.id ";
				
			$res = $mysqli->query($sql);
			$nodisponibles = $res->num_rows;
			$disponibles = $totalactivos - $nodisponibles; 
		}
		
		if($totalactivos == 0){
			$por_disponibles = 0;
			$porc_nodisponibles = 0;
		}else{ 
			$porc_nodisponibles = round($nodisponibles/$totalactivos*100,1);
			$por_disponibles = round($disponibles/$totalactivos*100,1); 
		} 
		echo json_encode(array( 'nodisponibles' => json_encode($porc_nodisponibles), 'disponibles' => json_encode($por_disponibles)  )	);
	}

function incidentesMeses() {
	global $mysqli;
	$nivel   = $_SESSION['nivel'];
	$usuario = (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : 0);
	$tiempo  	  = (!empty($_REQUEST['tiempo']) ? $_REQUEST['tiempo'] : 'mes');
	$modulo  	   = (!empty($_REQUEST['modulo']) ? $_REQUEST['modulo'] : 'incidentes');	
	$idproyectos   = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
	$idusuario   = $_SESSION['user_id'];
	
	$meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	$horas = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23); 
	
	if($modulo == "correctivos"){
		$modulo = "incidentes";
	}
	if($modulo == ""){
		$modulo = "incidentes";
	}
	if($tiempo == 'dia'){ 
		$rangofecha = ' YEAR(b.fechacreacion) = YEAR(NOW()) AND MONTH(b.fechacreacion) = MONTH(NOW()) AND DAY(b.fechacreacion) = DAY(NOW()) ';
		$sqltemp = " HOUR(horacreacion) AS temp ";
		$grouptemp = " GROUP BY HOUR(horacreacion) 
						ORDER BY HOUR(horacreacion) ";
	}elseif($tiempo == 'semana'){ 
	
		$sqltemp = ' DATE_FORMAT(b.fechacreacion,"%m-%d") AS temp';
		$rangofecha = " WEEKOFYEAR(b.fechacreacion) = WEEKOFYEAR(NOW()) AND YEAR(b.fechacreacion)=YEAR(NOW()) AND MONTH(b.fechacreacion) = MONTH(NOW())";
		$grouptemp = "  AND WEEKOFYEAR(b.fechacreacion) = WEEKOFYEAR(NOW()) AND YEAR(b.fechacreacion)=YEAR(NOW())
						GROUP BY DAY(b.fechacreacion) 
						ORDER BY DAY(b.fechacreacion) ";
	}elseif($tiempo == 'mes'){ 
		$rangofecha = ' YEAR(b.fechacreacion) = YEAR(NOW()) AND MONTH(b.fechacreacion)=MONTH(NOW()) ';
		$sqltemp = " MONTH(fechacreacion) AS temp ";
		$grouptemp = " GROUP BY MONTH(fechacreacion) 
						ORDER BY MONTH(fechacreacion) ";
	}else{
		$rangofecha = 'YEAR(b.fechacreacion) = YEAR(NOW())';
		$sqltemp = " MONTH(fechacreacion) AS temp ";
		$grouptemp = " GROUP BY MONTH(fechacreacion) 
						ORDER BY MONTH(fechacreacion) ";
	} 
	if($modulo != ""){
		$rangofecha .= " AND b.tipo IN ('$modulo')";
	}
	if($idproyectos != ""){
		$rangofecha .= " AND b.idproyectos IN ($idproyectos)";
	} 
	$permisosusuario = permisos('dashboard', '', $idusuario);
	$permisosinc = str_replace('a.','b.',$permisosusuario);
	//$sql .= $permisosinc;
	
	$sql = "SELECT 
				".$sqltemp.", 
				COUNT(CASE WHEN tipo = 'incidentes' AND ".$rangofecha." "; 
				//$sql .= permisos('dashboard', '', $idusuario);
	$sql .= $permisosinc;
	$sql .="	THEN 1 END) AS 'correctivos', 
				COUNT(CASE WHEN tipo = 'preventivos' AND ".$rangofecha." "; 
				//$sql .= permisos('dashboard', '', $idusuario);
	$sql .= $permisosinc;
	$sql .="  	THEN 1 END) AS 'preventivos' 
			FROM 
				incidentes AS b
				LEFT JOIN usuarios j ON b.solicitante = j.correo
				LEFT JOIN usuarios l ON b.asignadoa = l.correo
			WHERE 1  
				".$grouptemp."";
	
				//echo $sql;
	$res = $mysqli->query($sql);				
	while($reg = $res->fetch_assoc()){
		if($tiempo == 'semana'){
			$dias [] = $reg["temp"];
		}else{
			$mes [] = $meses[(int)$reg["temp"]]; 	
		} 
		$cantidadC[] = (int)$reg['correctivos'];
		$cantidadP[] = (int)$reg['preventivos'];
	} 
	$valores[] 	= array(
			'color' => '#267cbc',
			'name' 	=> "Correctivos",
			'data' 	=> $cantidadC
		);
	$valores[] 	= array(
		'color' => '#36C95F',
		'name' 	=> "Preventivos",
		'data' 	=> $cantidadP
	);
	$categorias = "";
	if($tiempo == 'dia'){ 
		$categorias = $horas;
	}elseif($tiempo == 'todo' || $tiempo == 'mes'){
		$categorias = $mes;
	}elseif($tiempo == 'semana'){
		$categorias = $dias;
	} 
	echo json_encode(array( 'categorias' => json_encode($categorias), 'valores' => json_encode($valores)  )	);
}

function incidentesUsuarios(){
	global $mysqli;
	
	$usuario 	  = $_SESSION['usuario'];
	$modulo  	  = (!empty($_REQUEST['modulo']) ? $_REQUEST['modulo'] : 'incidentes');	
	$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
	$tiempo  	  = (!empty($_REQUEST['tiempo']) ? $_REQUEST['tiempo'] : 'todo');
	$idusuario   = $_SESSION['user_id'];
	
	if($modulo == "correctivos"){
		$modulo = "incidentes";
	}
	if($modulo == ""){
		$modulo = "incidentes";
	}
	if($tiempo == 'dia'){ 
			$rangofecha = ' YEAR(fechacreacion) = YEAR(NOW()) AND MONTH(fechacreacion) = MONTH(NOW()) AND DAY(fechacreacion) = DAY(NOW()) ';
		}elseif($tiempo == 'semana'){ 
			$rangofecha = ' YEAR(fechacreacion) = YEAR(NOW()) AND WEEKOFYEAR(fechacreacion) = WEEKOFYEAR(NOW()) ';
		}elseif($tiempo == 'mes'){ 
			$rangofecha = ' YEAR(fechacreacion) = YEAR(NOW()) AND MONTH(fechacreacion)=MONTH(NOW()) ';
		}elseif($tiempo == 'todo'){
			$rangofecha = ' YEAR(fechacreacion) = YEAR(NOW()) ';
		}
	if($modulo != ""){
		$rangofecha .= " AND b.tipo IN ('$modulo')";
	}
	if($idproyectos != ""){
		$rangofecha .= " AND b.idproyectos IN ($idproyectos)";
	} 
	
	$sqlc = "SELECT correo FROM usuarios WHERE usuario = '".$usuario."'";
	$resc = $mysqli->query($sqlc);
	if($regc = $resc->fetch_assoc()){
		$correo = $regc["correo"];
	}
	$resueltos = 0;
	$pendientes = 0;
	$total = 0;
	$sql = "SELECT 
			IFNULL(SUM(case when b.idestados = 16 then 1 else 0 end),0) as resueltos, 
			IFNULL(SUM(case when b.idestados != 16 then 1 else 0 end),0) as pendientes 
			FROM incidentes b 
			LEFT JOIN usuarios j ON b.solicitante = j.correo 
			LEFT JOIN usuarios l ON b.asignadoa = l.correo
			WHERE 1 AND ".$rangofecha."";
	//$sql .= permisos('dashboard', '', $idusuario);
	$permisosusuario = permisos('dashboard', '', $idusuario);
	$permisosinc = str_replace('a.','b.',$permisosusuario);
	$sql .= $permisosinc;
	debugL($sql,"debugLINCIDENTESUSUARIOS");
	$res = $mysqli->query($sql);				
	if($reg = $res->fetch_assoc()){ 
		$resueltos = $reg["resueltos"];
		$pendientes  = $reg["pendientes"];
	} 
	$total = $resueltos + $pendientes;
	if($total == 0){ 
		$porc_resueltos = 0;
		$porc_pendientes = 0;
	}else{
		$porc_resueltos = round($resueltos/$total*100,1);
		$porc_pendientes = round($pendientes/$total*100,1);	
	} 
	echo json_encode(array( 'resueltos' => $porc_resueltos, 'pendientes' => $porc_pendientes  )	);
}

function estados() {
	global $mysqli;
	$nivel 		   = $_SESSION['nivel'];
	$tipo   	   = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');	
	$modulo  	   = (!empty($_REQUEST['modulo']) ? $_REQUEST['modulo'] : ''); 
	$idproyectos   = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');	
	$usuario 	   = (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : 0);
	$idclientes    = $_SESSION['idclientes'];
	$idusuario 	= $_SESSION['user_id'];
	
	if($nivel == 4 || $nivel == 7){
		$tiempo  = (!empty($_REQUEST['tiempo']) ? $_REQUEST['tiempo'] : '');
	}else{
		$tiempo  = (!empty($_REQUEST['tiempo']) ? $_REQUEST['tiempo'] : '');
	}	
	if($modulo == "correctivos"){
		$modulo = "incidentes";
	}
	if($modulo == ""){
		$modulo = "incidentes";
	}
	if($tiempo == 'dia'){
		//$rangofecha = 'b.fechacreacion>(b.fechacreacion-(60*60*24))';
		$rangofecha = ' YEAR(b.fechacreacion) = YEAR(NOW()) AND MONTH(b.fechacreacion) = MONTH(NOW()) AND DAY(b.fechacreacion) = DAY(NOW()) ';
	}elseif($tiempo == 'semana'){
		//$rangofecha = 'b.fechacreacion>(b.fechacreacion-(60*60*24*7))';
		$rangofecha = ' WEEKOFYEAR(b.fechacreacion) = WEEKOFYEAR(NOW()) ';
	}elseif($tiempo == 'mes'){
		//$rangofecha = 'b.fechacreacion>(b.fechacreacion-(60*60*24*30))';
		$rangofecha = ' YEAR(b.fechacreacion) = YEAR(NOW()) AND MONTH(b.fechacreacion)=MONTH(NOW()) ';
	}else{
		$rangofecha = '1';
	}
	if($modulo != ""){
		$rangofecha .= " AND b.tipo IN ('$modulo')";
	}
	if($idproyectos != ""){
		$rangofecha .= " AND b.idproyectos IN ($idproyectos)";
	} 
	
	$query = "";
	$query .= " SELECT 'Pendientes' AS name, COUNT(a.id) AS y
				FROM estados a
				INNER JOIN incidentes b ON b.idestados = a.id
				INNER JOIN categorias c ON b.idcategorias = c.id
				INNER JOIN usuarios j ON b.solicitante = j.correo
				INNER JOIN usuarios l ON b.asignadoa = l.correo
				WHERE ".$rangofecha." AND (a.id = 12 OR a.id = 13 OR a.id = 28 OR a.id = 33 OR a.id = 34)
				";
	$query .= permisos('dashboard', '', $idusuario);
	$query .= " UNION ";
	$query .= " SELECT 'En espera' AS name, COUNT(a.id) AS y
				FROM estados a
				INNER JOIN incidentes b ON b.idestados = a.id
				INNER JOIN categorias c ON b.idcategorias = c.id
				INNER JOIN usuarios j ON b.solicitante = j.correo
				INNER JOIN usuarios l ON b.asignadoa = l.correo
				WHERE ".$rangofecha." AND (a.id = 14 OR a.id = 15)
				";
	$query .= permisos('dashboard', '', $idusuario);
	//if($nivel == 4 || $nivel == 7){
		$query .= " UNION ";
		$query .= " SELECT 'Resueltos' AS name, COUNT(a.id) AS y
					FROM estados a
					INNER JOIN incidentes b ON b.idestados = a.id
					INNER JOIN categorias c ON b.idcategorias = c.id
					INNER JOIN usuarios j ON b.solicitante = j.correo
					INNER JOIN usuarios l ON b.asignadoa = l.correo
					WHERE ".$rangofecha." AND (a.id = 16 OR a.id = 17 OR a.id = 18)				  
					";
		$query .= permisos('dashboard', '', $idusuario);
	//}
	//echo $query;
	$result = $mysqli->query($query);	
	$nbrows = $result->num_rows;	
	if($nbrows > 0){
		$registros = array();
		while($row = $result->fetch_assoc()){
			$registros[] 	= array(
				'name' 	=> $row['name'],
				'y' 	=> intval($row['y'])
			);
		}		
		echo json_encode($registros);
	} else {
		echo '';
	}
}

function categorias() {
	
	global $mysqli;
	
	$nivel = $_SESSION['nivel'];
	$tipo   = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');	 
	$modulo  	   = (!empty($_REQUEST['modulo']) ? $_REQUEST['modulo'] : '');	
	$idproyectos   = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
	$usuario 	   = (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : 0);
	$idclientes    = $_SESSION['idclientes'];	
	
	if($nivel == 4 || $nivel == 7){
		$tiempo  = (!empty($_REQUEST['tiempo']) ? $_REQUEST['tiempo'] : '');
	}else{
		$tiempo  = (!empty($_REQUEST['tiempo']) ? $_REQUEST['tiempo'] : '');
	}
	if($modulo == "correctivos"){
		$modulo = "incidentes";
	}
	if($modulo == ""){
		$modulo = "incidentes";
	}
	if($tiempo == 'dia'){
		//$rangofecha = 'b.fechacreacion>(b.fechacreacion-(60*60*24))';
		$rangofecha = ' YEAR(b.fechacreacion) = YEAR(NOW()) AND MONTH(b.fechacreacion) = MONTH(NOW()) AND DAY(b.fechacreacion) = DAY(NOW()) ';
	}elseif($tiempo == 'semana'){
		//$rangofecha = 'b.fechacreacion>(b.fechacreacion-(60*60*24*7))';
		$rangofecha = ' WEEKOFYEAR(b.fechacreacion) = WEEKOFYEAR(NOW()) ';
	}elseif($tiempo == 'mes'){
		//$rangofecha = 'b.fechacreacion>(b.fechacreacion-(60*60*24*30))';
		$rangofecha = ' YEAR(b.fechacreacion) = YEAR(NOW()) AND MONTH(b.fechacreacion)=MONTH(NOW()) ';
	}else{
		$rangofecha = '1';
	}
	if($modulo != ""){
		$rangofecha .= " AND b.tipo IN ('$modulo')";
	}
	if($idproyectos != ""){
		$rangofecha .= " AND b.idproyectos IN ($idproyectos)";
	} 
	$queryC = "	SELECT DISTINCT(a.nombre) AS categorias, a.id
				FROM categorias a
				INNER JOIN incidentes b ON b.idcategorias = a.id
				INNER JOIN usuarios j ON b.solicitante = j.correo
				INNER JOIN usuarios l ON b.asignadoa = l.correo
				WHERE ".$rangofecha." AND a.nombre != 'Merged'
				";
	$queryC .= permisos('dashboard', '');
	$queryC .= " ORDER BY a.nombre ASC ";
	//debugL('CATEGORIAS:'.$queryC);
	$resultC = $mysqli->query($queryC);	
	$nbrowsC = $resultC->num_rows;	
	if($nbrowsC > 0){
		$clientes = array();
		$estados = array();
		$cantidadesp = array();
		$cantidadesee = array();
		$cantidadesr = array();
		$valores = array();	
		$estadosp = 'Pendientes';
		$estadosee = 'En espera';
		$estadosr = 'Resueltos';
		while($row = $resultC->fetch_assoc()){
			$idc = $row['id'];
			$categorias[] = $row['categorias'];
			$cantidadespe = 0;
			$cantidadesen = 0;
			$cantidadesre = 0;
			
			$queryE = " SELECT a.id, a.nombre
						FROM estados a
						INNER JOIN incidentes b ON b.idestados = a.id
						INNER JOIN categorias c ON b.idcategorias = c.id
						INNER JOIN usuarios j ON b.solicitante = j.correo
						INNER JOIN usuarios l ON b.asignadoa = l.correo
						WHERE ".$rangofecha." AND a.id != 0 AND b.idcategorias = ".$idc."						
						";
			$queryE .= permisos('dashboard', '');
			$queryE .= " GROUP BY a.id ORDER BY a.nombre ASC ";
			debug('CATE:'.$queryE);
			$resultE = $mysqli->query($queryE);	
			$nbrowsE = $resultE->num_rows;	
			if($nbrowsE > 0){							
				while($rowE = $resultE->fetch_assoc()){
					$ide = $rowE['id'];
					$queryN = " SELECT COUNT(b.id) as cant
								FROM estados a
								INNER JOIN incidentes b ON b.idestados = a.id
								INNER JOIN categorias c ON b.idcategorias = c.id
								INNER JOIN usuarios j ON b.solicitante = j.correo
								INNER JOIN usuarios l ON b.asignadoa = l.correo
								WHERE ".$rangofecha." AND a.id != 0 AND b.idcategorias = ".$idc." AND b.idestados = ".$ide."
								";
					$queryN .= permisos('dashboard', '');
					$queryN .= " GROUP BY a.id ORDER BY a.nombre ASC "; 
					debugL('CATEGORIAS2:'.$queryN);
					$resultN = $mysqli->query($queryN);
					$nbrowsN = $resultN->num_rows;	
					if($nbrowsN > 0){
						while($rowN = $resultN->fetch_assoc()){
							if($ide == 12 || $ide == 13 || $ide == 28 || $ide == 33 || $ide == 34 || $ide == 35 || $ide == 36 || $ide == 37){ //Nuevo, Asignado, Desarrollo, En Proceso, Reasignado, Nuevo, Entrada, En Proceso
								$cantidadespe = $cantidadespe + $rowN['cant'];
							}elseif($ide == 14 || $ide == 15){ //A la Espera del Cliente, En espera de repuesto
								$cantidadesen = $cantidadesen + $rowN['cant'];
							}elseif($ide == 16 || $ide == 17 || $ide == 18){ //Resuelto, Cerrado, Reporte Pendiente
								$cantidadesre = $cantidadesre + $rowN['cant'];
							}						
						}
					}else{
						if($ide == 12 || $ide == 13  || $ide == 28 || $ide == 33 || $ide == 34 || $ide == 35 || $ide == 36 || $ide == 37){ //Nuevo, Asignado, Desarrollo, En Proceso, Reasignado, Nuevo, Entrada, En Proceso
							$cantidadespe = $cantidadespe + 0;
						}elseif($ide == 14 || $ide == 15){ //A la Espera del Cliente, En espera de repuesto
							$cantidadesen = $cantidadesen + 0;
						}elseif($ide == 16 || $ide == 17 || $ide == 18){ //Resuelto, Cerrado, Reporte Pendiente
							$cantidadesre = $cantidadesre + 0;
						}
					}					
				}
			}
			$cantidadesp[] = $cantidadespe;
			$cantidadesee[] = $cantidadesen;
			$cantidadesr[] = $cantidadesre;
		}
		$valores[] 	= array(
			'name' 	=> $estadosp,
			'data' 	=> $cantidadesp
		);
		$valores[] 	= array(
			'name' 	=> $estadosee,
			'data' 	=> $cantidadesee
		);
		//if($nivel == 4 || $nivel == 7){
			$valores[] 	= array(
				'name' 	=> $estadosr,
				'data' 	=> $cantidadesr
			);
		//}
		
		echo json_encode(array( 'categorias' => json_encode($categorias), 'valores' => json_encode($valores)  )	);
	} else { 
		$valores[] 	= array(
				'name' 	=> "",
				'data' 	=> ""
			);
		echo json_encode(array( 'categorias' => json_encode("Sin resultados"), 'valores' => json_encode($valores)  )	);
	}
}

function datosBase(){
	
	$tiempo  = (!empty($_REQUEST['tiempo']) ? $_REQUEST['tiempo'] : '');
	
	if($tiempo == 'dia'){
		$hoy	   = date("d-m-Y");
		
		$respuesta = "Hoy ".$hoy;
		echo $respuesta;
		
	}elseif($tiempo == 'semana'){
		
		$numeroSemana = date("W");
		$anio		  = date("Y");
		
		$resultado = getStartAndEndDate($numeroSemana,$anio);
		
		$fechainicio = date("d-m-Y", strtotime($resultado[0]));
		$fechafin    = date("d-m-Y", strtotime($resultado[1]));
		$respuesta   = "Del ".$fechainicio." al ".$fechafin;
		echo $respuesta;
		
	}elseif($tiempo == 'mes'){
		
		$mes = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"][date("n") - 1];
		$respuesta = ucwords($mes);
		echo $respuesta;
		
	}else{
		
		$respuesta = "";

	} 
}

function getStartAndEndDate($week, $year) //semana, anio
{ 
	$firstWeekThursDay = date('W',strtotime("January $year first thursday",date(time())));

	if($firstWeekThursDay == "01")
	{
		$time      = strtotime("January $year first thursday",date(time())); 
		$time      = ($time-(4*24*3600))+(((7*$week)-6)*24*3600); 
		$return[0] = date('Y-m-d', $time);
		$time += 6*24*3600;
		$return[1] = date('Y-m-d', $time); 
	}
	else
	{
		$time = strtotime("January 1 $year", time()); 
		$time      = ($time-(4*24*3600))+(((7*$week)-6)*24*3600); 
		$return[0] = date('Y-m-d', $time);
		$time     += 6*24*3600;
		$return[1] = date('Y-m-d', $time);  
	}
	return $return;
}	 

?>