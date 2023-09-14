<?php
    include_once("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "eventos": 
              eventos();
			  break;
		case "eventosprev": 
              eventosprev();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}

	function eventos() 
	{
		global $mysqli;
		
		$start = $_REQUEST['start'];
		$end   = $_REQUEST['end'];
		$chkt = (!empty($_REQUEST['chkt']) ? $_REQUEST['chkt'] : '');
		$chki = (!empty($_REQUEST['chki']) ? $_REQUEST['chki'] : '');
		$chkp = (!empty($_REQUEST['chkp']) ? $_REQUEST['chkp'] : '');
		$chka = (!empty($_REQUEST['chka']) ? $_REQUEST['chka'] : '');
		$chkc = (!empty($_REQUEST['chkc']) ? $_REQUEST['chkc'] : '');
		$cmbr = (!empty($_REQUEST['cmbr']) ? $_REQUEST['cmbr'] : '');
		$cmbm = (!empty($_REQUEST['cmbm']) ? $_REQUEST['cmbm'] : '');
		
		$query  = "
			SELECT a.id,a.titulo, a.estado, a.descripcion, a.idcategoria,
			case when a.fechacierre IS NULL OR a.fechacierre = ''
				then concat(a.fechacreacion,' ',a.horacreacion) 
				else concat(a.fechacierre,' ',a.horacierre) end
			as fecha1,
			case when a.fechacierre IS NULL OR a.fechacierre = ''
				then DATE_ADD(concat(a.fechacreacion,' ',a.horacreacion), INTERVAL 240 MINUTE) 
				else DATE_ADD(concat(a.fechacierre,' ',a.horacierre), INTERVAL 240 MINUTE) end
			as fecha2, 
			u1.nombre as responsable, u.unidad 
			FROM incidentes a 
			LEFT JOIN usuarios u1 ON a.asignadoa = u1.correo 
		    LEFT JOIN usuarios u2 ON a.solicitante = u2.correo
			LEFT JOIN unidades u on u.codigo = a.unidadejecutora
			LEFT JOIN activos ac on ac.codequipo = a.serie
			WHERE a.fechacreacion is not null  and 
			case when a.fechacierre IS NULL OR a.fechacierre = ''
				then a.fechacreacion 
				else a.fechacierre end >= '$start' and 
			case when a.fechacierre IS NULL OR a.fechacierre = ''
				then a.fechacreacion 
				else a.fechacierre end <= '$end'
			";
		if($_SESSION['nivel'] == 3) {
			$query  .= "AND u1.usuario = '".$_SESSION['usuario']."' ";
		} elseif($_SESSION['nivel'] == 4){
			$query  .= "AND (u2.usuario = '".$_SESSION['usuario']."' OR a.unidadejecutora = '".$_SESSION['sitio']."') ";
		}	
		
		$query .= "AND a.idcategoria not in (12,22,35,43) ";
		
		if ($chka == 'true' AND $chkc == 'true')
			$query .= "AND  a.estado = a.estado ";
		elseif ($chka == 'true')
			$query .= "AND   a.estado < 16 ";
		elseif ($chkc == 'true')
			$query .= "AND   a.estado > 15 ";
		
		if ($chkt != 'true' AND $chki != 'true' AND $chkp != 'true' AND $chka != 'true' AND $chkc != 'true')
			$query .= "AND a.idcategoria in (12,22) AND a.estado < 16 ";
		
		if ($cmbr!='') {
			$query .= "AND a.asignadoa = '$cmbr' ";
		}
		
		if ($cmbm != '') {
			$query .= "AND ac.marca = '$cmbm' ";
		}
		
		debug($query);
		$result = $mysqli->query($query); 
		$count = $result->num_rows;		
		$row = $result->fetch_assoc();
		$fecha1 = new DateTime($row['fecha1']);
		$fecha2 = new DateTime($row['fecha2']);
		$auxfecha = new DateTime($row['fecha1']);
		$minutos = 0;
		$i=0; $id=1;
		$event_array = '';
		$hora = 0;
		$result = $mysqli->query($query); 
		while($row = $result->fetch_assoc()){
			$actividad = utf8_decode($row['titulo']);
			$estatus = $row['estado'];
			$tipo = $row['idcategoria'];
			if ($tipo == 10) {
				$className  = 'eventIncidente';
			} 
			elseif($tipo == 12) {
				$className = 'eventPreventivo';
			}
			elseif($tipo == 22) {
				$className = 'eventRecepcion';
			}else{
				$className  = 'eventIncidente';
			}
			
			$fecha1 = new DateTime($row['fecha1']);
			$fecha2 = new DateTime($row['fecha2']);
			if ($auxfecha->format('Y-m-d') == $fecha1->format('Y-m-d')) {
				$fecha1->add(new DateInterval('PT'. $minutos .'M'));
				$row['fecha1'] = $fecha1->format('Y-m-d H:i:s');
				$fecha2->add(new DateInterval('PT'. $minutos  .'M'));
				$row['fecha2'] = $fecha2->format('Y-m-d H:i:s');
				//$minutos += 50;
			} else {
				$minutos = 0;
				$fecha1->add(new DateInterval('PT'. $minutos .'M'));
				$row['fecha1'] = $fecha1->format('Y-m-d H:i:s');
				$fecha2->add(new DateInterval('PT'. $minutos  .'M'));
				$row['fecha2'] = $fecha2->format('Y-m-d H:i:s');
				$auxfecha = new DateTime($row['fecha1']);
				//$minutos += 50;
			}
			
			$event_array[] = array(
				'id' 		=> $row['id'],
				'title'		=> $row['descripcion'].' - '.$row['responsable'].' - '.$row['unidad'],
				'start' 	=> $row['fecha1'],
				'end' 		=> $row['fecha2'],
				'className' => $className,
			);
		}       
		echo json_encode($event_array);	
	}
	
	function eventosprev() 
	{
		global $mysqli;
		
		$start = $_REQUEST['start'];
		$end   = $_REQUEST['end'];
		$chkt = (!empty($_REQUEST['chkt']) ? $_REQUEST['chkt'] : '');
		$chki = (!empty($_REQUEST['chki']) ? $_REQUEST['chki'] : '');
		$chkp = (!empty($_REQUEST['chkp']) ? $_REQUEST['chkp'] : '');
		$chka = (!empty($_REQUEST['chka']) ? $_REQUEST['chka'] : '');
		$chkc = (!empty($_REQUEST['chkc']) ? $_REQUEST['chkc'] : '');
		$cmbr = (!empty($_REQUEST['cmbr']) ? $_REQUEST['cmbr'] : '');
		$cmbm = (!empty($_REQUEST['cmbm']) ? $_REQUEST['cmbm'] : '');
		
		$query  = "
			SELECT a.id,a.titulo, a.estado, a.descripcion, a.idcategoria,
			case when a.fechacierre IS NULL OR a.fechacierre = ''
				then concat(a.fechacreacion,' ',a.horacreacion) 
				else concat(a.fechacierre,' ',a.horacierre) end
			as fecha1,
			case when a.fechacierre IS NULL OR a.fechacierre = ''
				then DATE_ADD(concat(a.fechacreacion,' ',a.horacreacion), INTERVAL 240 MINUTE) 
				else DATE_ADD(concat(a.fechacierre,' ',a.horacierre), INTERVAL 240 MINUTE) end
			as fecha2, 
			u1.nombre as responsable, u.unidad 
			FROM incidentes a 
			LEFT JOIN usuarios u1 ON a.asignadoa = u1.correo 
		    LEFT JOIN usuarios u2 ON a.solicitante = u2.correo
			LEFT JOIN unidades u on u.codigo = a.unidadejecutora
			LEFT JOIN activos ac on ac.codequipo = a.serie
			WHERE a.fechacreacion is not null  and 
			case when a.fechacierre IS NULL OR a.fechacierre = ''
				then a.fechacreacion 
				else a.fechacierre end >= '$start' and 
			case when a.fechacierre IS NULL OR a.fechacierre = ''
				then a.fechacreacion 
				else a.fechacierre end <= '$end'
			";
		if($_SESSION['nivel'] == 3) {
			$query  .= "AND u1.usuario = '".$_SESSION['usuario']."' ";
		} elseif($_SESSION['nivel'] == 4){
			$query  .= "AND (u2.usuario = '".$_SESSION['usuario']."' OR a.unidadejecutora = '".$_SESSION['sitio']."') ";
		}	
		
		$query .= "AND a.idcategoria in (12,22,35,43) ";
		
		if ($chka == 'true' AND $chkc == 'true')
			$query .= "AND  a.estado = a.estado ";
		elseif ($chka == 'true')
			$query .= "AND   a.estado < 16 ";
		elseif ($chkc == 'true')
			$query .= "AND   a.estado > 15 ";
		
		if ($chkt != 'true' AND $chki != 'true' AND $chkp != 'true' AND $chka != 'true' AND $chkc != 'true')
			$query .= "AND a.idcategoria in (12,22) AND a.estado < 16 ";
		
		if ($cmbr!='') {
			$query .= "AND a.asignadoa = '$cmbr' ";
		}
		
		if ($cmbm != '') {
			$query .= "AND ac.marca = '$cmbm' ";
		}
		
		debug($query);
		$result = $mysqli->query($query); 
		$count = $result->num_rows;		
		$row = $result->fetch_assoc();
		$fecha1 = new DateTime($row['fecha1']);
		$fecha2 = new DateTime($row['fecha2']);
		$auxfecha = new DateTime($row['fecha1']);
		$minutos = 0;
		$i=0; $id=1;
		$event_array = '';
		$hora = 0;
		$result = $mysqli->query($query); 
		while($row = $result->fetch_assoc()){
			$actividad = utf8_decode($row['titulo']);
			$estatus = $row['estado'];
			$tipo = $row['idcategoria'];
			if ($tipo == 10) {
				$className  = 'eventIncidente';
			} 
			elseif($tipo == 12) {
				$className = 'eventPreventivo';
			}
			elseif($tipo == 22) {
				$className = 'eventRecepcion';
			}else{
				$className  = 'eventIncidente';
			}
			
			$fecha1 = new DateTime($row['fecha1']);
			$fecha2 = new DateTime($row['fecha2']);
			if ($auxfecha->format('Y-m-d') == $fecha1->format('Y-m-d')) {
				$fecha1->add(new DateInterval('PT'. $minutos .'M'));
				$row['fecha1'] = $fecha1->format('Y-m-d H:i:s');
				$fecha2->add(new DateInterval('PT'. $minutos  .'M'));
				$row['fecha2'] = $fecha2->format('Y-m-d H:i:s');
				//$minutos += 50;
			} else {
				$minutos = 0;
				$fecha1->add(new DateInterval('PT'. $minutos .'M'));
				$row['fecha1'] = $fecha1->format('Y-m-d H:i:s');
				$fecha2->add(new DateInterval('PT'. $minutos  .'M'));
				$row['fecha2'] = $fecha2->format('Y-m-d H:i:s');
				$auxfecha = new DateTime($row['fecha1']);
				//$minutos += 50;
			}
			
			$event_array[] = array(
				'id' 		=> $row['id'],
				'title'		=> $row['descripcion'].' - '.$row['responsable'].' - '.$row['unidad'],
				'start' 	=> $row['fecha1'],
				'end' 		=> $row['fecha2'],
				'className' => $className,
			);
		}       
		echo json_encode($event_array);	
	}
	
?>