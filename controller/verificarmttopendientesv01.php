<?php
/**
**
*ENVIO DE CORREO DE MANTENIMIENTOS PREVENTIVOS SEMANALES
**
**/
header('Content-Type: text/html; charset=UTF-8');
error_reporting(1);
require_once("../conexion.php");

$numsem = (date('W')+2);
$numsemana = str_pad($numsem, 2, "0", STR_PAD_LEFT);

$asunto = "Mantenimientos Preventivos para la Semana ".$numsemana." del Año ".date('Y');
$semana = "Semana ".$numsemana." del Año ".date('Y');

//FECHA MIN
$queryFm = "SELECT MIN(a.fechacreacion) AS minFecha 
			FROM incidentes a 
			INNER JOIN activos b ON b.id = a.idactivos
			WHERE case when a.fechacierre IS NULL OR a.fechacierre = '' then a.fechacreacion 
			else a.fechacierre end BETWEEN DATE_ADD(CURDATE(), INTERVAL 8 DAY) AND ADDDATE(DATE_ADD(CURDATE(), INTERVAL 8 DAY), INTERVAL 6 DAY) 
			AND a.asignadoa <> '' AND (a.idcategorias = '12' OR a.idcategorias = '43') AND a.idproyectos = 1 
			AND a.idestados NOT IN (16,17) AND b.idmarcas NOT in (1,7,6) ";
$resultFm 	= $mysqli->query($queryFm);
$rowFm 		= $resultFm->fetch_assoc();
$minFecha   = $rowFm['minFecha'];
	
//CUATRIMESTRE
$queryC = " SELECT periodo FROM cuatrimestres WHERE '".$minFecha."' BETWEEN fechainicio AND fechafin ";
$resultC 	= $mysqli->query($queryC);
if($resultC->num_rows > 0){
	$rowC 			= $resultC->fetch_assoc();
	$cuatrimestre	= $rowC['periodo'];
}else{
	$cuatrimestre = '';
}

//CUERPO DEL CORREO
function cuerpocorreo($incidentes){
	global $mysqli;
	$i = 1;
	$mensaje = '';
	
	$queryMP  = "	SELECT b.nombre AS equipo, b.modalidad, ma.nombre AS marca, mo.nombre AS modelo, b.serie, b.estado, c.nombre AS ambiente, 
					d.nombre as categoria, a.fechareal, a.horacreacion as horario, a.fechacertificar
					FROM incidentes a
					INNER JOIN activos b ON a.idactivos = b.id
					INNER JOIN ambientes c ON a.idambientes = c.id
					INNER JOIN categorias d ON a.idcategorias = d.id
					LEFT JOIN marcas ma ON b.idmarcas = ma.id
					LEFT JOIN modelos mo ON b.idmodelos = mo.id
					WHERE a.id IN ($incidentes)";
	$resultMP = $mysqli->query($queryMP);	
	while($rowMP = $resultMP->fetch_assoc()){
		$equipo 			= $rowMP['equipo'];
		$modalidad 			= $rowMP['modalidad'];
		$marca	 			= $rowMP['marca'];
		$modelo 			= $rowMP['modelo'];
		$serie 				= $rowMP['serie'];
		$estado 			= $rowMP['estado'];
		$ambiente			= $rowMP['ambiente'];
		$categoria			= $rowMP['categoria'];
		$fechareal 			= $rowMP['fechareal'];
		$horario 			= $rowMP['horario'];
		$fechacertificar	= $rowMP['fechacertificar'];
		
		$mensaje .= "<tr style='font-size: 12px; color: #000000; text-align: center; '>
						<td style='padding: 10px 15px;'>".$i."</td>
						<td style='padding: 10px 15px;'>".$equipo." / ".$modalidad."</td>
						<td style='padding: 10px 15px;'>".$marca."</td>
						<td style='padding: 10px 15px;'>".$modelo."</td>
						<td style='padding: 10px 15px;'>".$serie."</td>
						<td style='padding: 10px 15px;'>".$estado."</td>
						<td style='padding: 10px 15px;'>".$ambiente."</td>
						<td style='padding: 10px 15px;'>".$categoria."</td>
						<td style='padding: 10px 15px;'>".$fechareal."</td>
						<td style='padding: 10px 15px;'>".$horario."</td>
					 </tr>";
		$i++;
	}	
	return $mensaje;
}

//ASIGNADOS
$query = "  SELECT GROUP_CONCAT(a.id) AS id, a.asignadoa, GROUP_CONCAT(a.fechacreacion) AS fechacreacion, GROUP_CONCAT(a.idactivos) AS idactivos
			FROM incidentes a
			INNER JOIN activos b ON b.id = a.idactivos
			WHERE case when a.fechacierre IS NULL OR a.fechacierre = '' then a.fechacreacion else a.fechacierre end  
			BETWEEN DATE_ADD(CURDATE(), INTERVAL 8 DAY) AND ADDDATE(DATE_ADD(CURDATE(), INTERVAL 8 DAY), INTERVAL 6 DAY) 
			AND a.asignadoa <> '' AND (a.idcategorias = '12' OR a.idcategorias = '43') AND a.idproyectos = 1
			AND a.idestados NOT IN (16,17) AND b.idmarcas NOT in (1,7,6) 
			GROUP BY a.asignadoa ";
			// CATEGORIAS: 12-Tx Mantenimiento Preventivo, 43-Tx Pruebas de Desempeño
			// ESTADOS: 16-Resuelto,  17-Cerrado
$result = $mysqli->query($query);
if($result->num_rows <= 0){
	echo "SIN MANTENIMIENTOS PARA ESTA SEMANA";
	exit();
}
while($row = $result->fetch_assoc()){
	$gincidentes = $row['id'];
	$gidactivos  = $row['idactivos'];
	$asignadoa	 = $row['asignadoa'];	
	$mensaje 	 = "";
	$aincidentes = array();
	$correo 	 = array();
	
	$arrIncidentes = explode(',',$gincidentes);
	$arrActivos = explode(',',$gidactivos);
	$a = 0;
	foreach ($arrActivos as $key => $idactivos) {
		$queryFS = "SELECT COUNT(id) AS total FROM incidentes 
					WHERE idactivos = '".$idactivos."' AND tipo = 'incidentes' AND idestados = 15 AND fueraservicio = '1' ";
		//ESTADO: 15-En espera de repuesto
		$resFS = $mysqli->query($queryFS);
		$rowFS = $resFS->fetch_assoc();
		$fueraserv = $rowFS['total'];
		if($fueraserv == 0){
			$aincidentes[] = $arrIncidentes[$a];
		}
		$a++;
	}
	$incidentes = implode(',',$aincidentes);
	
	if($incidentes != ''){
		if (filter_var($asignadoa, FILTER_VALIDATE_EMAIL)) {
			$correo [] = $asignadoa;
		}else{
			foreach([$asignadoa] as $asig){
				$correo [] = $asig;
			}
		}
		//debugL($incidentes);
		foreach ($correo as $key => $value) { 
			$querycorreo = "SELECT * FROM notificacionesxusuarios nu
							left join usuarios u on u.id = nu.idusuario
							where u.correo = '$value' and noti12 = 1";
			$consultacorreo = $mysqli->query($querycorreo);
			if($consultacorreo->num_rows == 0){
				unset($correo[$key]);
			}
		}		
		$mensaje = cuerpocorreo($incidentes);		
		enviarMensaje($asunto,$mensaje,$correo,$semana,'asignados',$cuatrimestre);
	}	
}

//AMBIENTES
$queryU = " SELECT GROUP_CONCAT(a.id) AS id, b.responsables, GROUP_CONCAT(a.idactivos) AS idactivos
			FROM incidentes a 
			INNER JOIN ambientes b ON a.idambientes = b.id 
			INNER JOIN activos c ON c.id = a.idactivos
			WHERE case when a.fechacierre IS NULL OR a.fechacierre = '' then a.fechareal else a.fechacierre end 
			BETWEEN DATE_ADD(CURDATE(), INTERVAL 8 DAY) 
			AND ADDDATE(DATE_ADD(CURDATE(), INTERVAL 8 DAY), INTERVAL 6 DAY) 
			AND a.asignadoa <> '' AND (a.idcategorias = '12' OR a.idcategorias = '43') 
			AND a.idproyectos = 1 AND a.idestados NOT IN (16,17) 
			AND c.idmarcas NOT in (1,7,6)
			GROUP BY b.id ";

$resultU = $mysqli->query($queryU);
if($resultU->num_rows <= 0){
	echo "SIN MANTENIMIENTOS PARA ESTA SEMANA";
	exit();
}
while($rowU = $resultU->fetch_assoc()){
	$gincidentes = $rowU['id'];
	$gidactivos  = $rowU['idactivos'];
	$responsables	 = $rowU['responsables'];	
	$mensaje 	 = "";
	$aincidentes = array();
	$correo 	 = array();
	
	$arrIncidentes = explode(',',$gincidentes);
	$arrActivos = explode(',',$gidactivos);
	$a = 0;
	foreach ($arrActivos as $key => $idactivos) {
		$queryFS = "SELECT COUNT(id) AS total FROM incidentes 
					WHERE idactivos = '".$idactivos."' AND tipo = 'incidentes' AND idestados = 15 AND fueraservicio = '1' ";
		//ESTADO: 15-En espera de repuesto
		$resFS = $mysqli->query($queryFS);
		$rowFS = $resFS->fetch_assoc();
		$fueraserv = $rowFS['total'];
		if($fueraserv == 0){
			$aincidentes[] = $arrIncidentes[$a];
		}
		$a++;
	}
	$incidentes = implode(',',$aincidentes);
	
	if($incidentes != ''){
		if (filter_var($responsables, FILTER_VALIDATE_EMAIL)) {
			$correo [] = $responsables;
		}else{
			$arrResponsable = explode(",",$responsables);
			foreach($arrResponsable as $asig){
				$correo [] = $asig;
			}
		}
		//debugL($incidentes);
		$mensaje = cuerpocorreo($incidentes);	
		enviarMensaje($asunto,$mensaje,$correo,$semana,'sitios',$cuatrimestre);
	}
}

function enviarMensaje($asunto,$mensaje,$correo,$semana,$tipo,$cuatrimestre) {
	global $mysqli, $mail;
	$cuerpo = "";
	
	$cuerpo .= "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<div style='padding: 5px 10px; font-size: 14px; margin-bottom:50px;color: #000000; '>
						<img src='https://toolkit.maxialatam.com/soporte/images/logo-consorcio.jpg' style='width: initial;height: 80px;float: left;position: absolute;'>
						<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;'>MANTENIMIENTO PREVENTIVO</p>
						<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;'>PROYECTO TELERADIOLOGIA</p>
						<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;'>".$cuatrimestre." CUATRIMESTRE</p>
						<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;'>Gestión de Soporte</p>
					</div>

					<table border='1' style='width: 100%; border: 1px solid #a8a8a8; border-collapse: collapse;'>
						<tr style='font-size: 12px; font-weight: bold; color: #ffffff; background: #293f76; text-align: center; '>
							<td style='padding: 10px 15px;'>#</td>
							<td style='padding: 10px 15px;'>EQUIPO/MODALIDAD</td>
							<td style='padding: 10px 15px;'>MARCA</td>
							<td style='padding: 10px 15px;'>MODELO</td>
							<td style='padding: 10px 15px;'>SERIE</td>
							<td style='padding: 10px 15px;'>ESTADO DEL EQUIPO</td>
							<td style='padding: 10px 15px;'>UNIDAD EJECUTORA</td>
							<td style='padding: 10px 15px;'>CATEGORIA</td>
							<td style='padding: 10px 15px; min-width: 65px;'>FECHAS DE MP</td>
							<td style='padding: 10px 15px;'>HORA APROXIMADA</td>
						</tr>
						".$mensaje."
					</table>
				</div>";
	//$cuerpo .= "<p style='color: #eeeeee;font-size: 10px;padding: 0;margin: 0;'>".json_encode($correo)."</p>";

	//$mail->addAddress('lisbethagapornis@gmail.com');
	foreach($correo as $destino){
	   $mail->addAddress($destino);
	}
	//$mail->addReplyTo('daniel.coronel@maxialatam.com', 'Daniel Coronel');
	
	//SOPORTE
	if($tipo == 'sitios'){
		//$mail->addCC('ana.porras@maxialatam.com');
		$mail->addCC('maylin.aguero@maxialatam.com');
		$mail->addCC('isai.carvajal@maxialatam.com');
		$mail->addCC('axel.anderson@maxialatam.com');
		$mail->addCC('raquel.bedoya@maxialatam.com');
	}
	//COPIA OCULTA
	//$mail->addBCC('lismary.18@gmail.com');
	//$mail->addBCC('lisbethagapornis@gmail.com');
	$mail->FromName = "Maxia Toolkit - SYM";
	$mail->isHTML(true); // Set email format to HTML
	$mail->Subject = $asunto;
	//$mail->MsgHTML($cuerpo);
	$mail->Body = $cuerpo;
	$mail->AltBody = "Maxia Toolkit - SYM: $asunto";
	if(!$mail->send()) {
		echo 'Mensaje no pudo ser enviado. ';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		//echo 'Ha sido enviado el correo Exitosamente';
		// clear all addresses and attachments for the next mail
		$mail->ClearAddresses();
		echo true;
	} 
}

?>