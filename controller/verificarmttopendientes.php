<?php
/**
**
*ENVIO DE CORREO DE MANTENIMIENTOS PREVENTIVOS SEMANALES
**
**/
header('Content-Type: text/html; charset=UTF-8');
error_reporting(1);
require_once("../conexion.php");

/* $numsem = (date('W')+2);
$numsemana = str_pad($numsem, 2, "0", STR_PAD_LEFT); */
$fechaActual = date('d-m-Y');	
$fechaSegundos = strtotime($fechaActual);	
$numsemana = date('W', $fechaSegundos);

$asunto = "Mantenimientos Preventivos para la Semana ".$numsemana." del Año ".date('Y');
$semana = "Semana ".$numsemana." del Año ".date('Y');

/* //FECHA MIN
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
} */

//CUERPO DEL CORREO
function cuerpocorreo($incidentes){
	global $mysqli;
	$i = 1;
	$mensaje = '';
	
	$queryMP  = "	SELECT b.nombre AS equipo, ti.nombre AS modalidad, ma.nombre AS marca, mo.nombre AS modelo, 
					b.serie, b.estado, c.nombre AS ubicacion, su.nombre AS area, d.nombre as categoria, a.fechareal,
					a.horacreacion as horario, a.fechacertificar, us.nombre AS asignadoa, a.titulo, 
					d.nombre AS nombrecategoria
					FROM incidentes a
					LEFT JOIN activos b ON a.idactivos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id 
					INNER JOIN categorias d ON a.idcategorias = d.id
					LEFT JOIN marcas ma ON b.idmarcas = ma.id
					LEFT JOIN modelos mo ON b.idmodelos = mo.id
					LEFT JOIN activostipos ti ON ti.id = b.idtipo
					LEFT JOIN usuarios us ON us.correo = a.asignadoa
					LEFT JOIN subambientes su ON a.idsubambientes = su.id
					WHERE a.id IN ($incidentes)";
	$resultMP = $mysqli->query($queryMP);	
	while($rowMP = $resultMP->fetch_assoc()){
		$nomcat 			= $rowMP['nombrecategoria'];
		$titulo 			= $rowMP['titulo'];
		$equipo 			= $rowMP['equipo'];
		$modalidad 			= $rowMP['modalidad'];
		$marca	 			= $rowMP['marca'];
		$modelo 			= $rowMP['modelo'];
		$serie 				= $rowMP['serie'];
		$estado 			= $rowMP['estado'];
		$ubicacion			= $rowMP['ubicacion'];
		$area				= $rowMP['area'];
		$categoria			= $rowMP['categoria'];
		$fechareal 			= $rowMP['fechareal'];
		$horario 			= $rowMP['horario'];
		$fechacertificar	= $rowMP['fechacertificar'];
		$asignadoa			= $rowMP['asignadoa'];
		
		$mensaje .= "<tr style='font-size: 12px; color: #000000; text-align: center; '>
						<td style='padding: 10px 15px;'>".$i."</td>";
						
			//Tipo Infraestructura
			if($nomcat == "Infraestructura"){
				
				$mensaje .="<td style='padding: 10px 15px;'>".$titulo."</td>";
				
			}else{
				
				//Tipo Activo
				$mensaje .="<td style='padding: 10px 15px;'>".$modalidad."</td>
						<td style='padding: 10px 15px;'>".$marca."</td>
						<td style='padding: 10px 15px;'>".$modelo."</td>
						<td style='padding: 10px 15px;'>".$serie."</td>
						<!--<td style='padding: 10px 15px;'>".$estado."</td>-->";
						
			} 
			
			$mensaje .="<td style='padding: 10px 15px;'>".$ubicacion."</td>";
			
			//Tipo Activo
			if($nomcat != "Infraestructura"){
				
				$mensaje .="<td style='padding: 10px 15px;'>".$area."</td>";
				
			}
			
			$mensaje .="<!--<td style='padding: 10px 15px;'>".$categoria."</td>-->
						<td style='padding: 10px 15px;'>".$fechareal."</td>
						<td style='padding: 10px 15px;'>".$horario."</td>
						<td style='padding: 10px 15px;'>".$asignadoa."</td>
					 </tr>";
		$i++;
	}	
	return $mensaje;
}

//ASIGNADOS
$query = "  SELECT GROUP_CONCAT(a.id) AS id, 
			CASE 
				WHEN usr.estado = 'Activo' 
					THEN a.asignadoa 
				WHEN usr.estado = 'Inactivo' 
					THEN '' 
				END 
				AS asignadoa, 
				GROUP_CONCAT(a.fechacreacion) AS fechacreacion, 
				GROUP_CONCAT(a.idactivos) AS idactivos, 
				cli.nombre AS cliente, c.nombre AS nombrecategoria
			FROM incidentes a
			LEFT JOIN activos b ON b.id = a.idactivos
			INNER JOIN categorias c ON c.id = a.idcategorias
			INNER JOIN clientes cli ON cli.id = a.idclientes
			LEFT JOIN usuarios usr ON usr.correo = a.asignadoa
			WHERE (CASE WHEN (a.fechacierre IS NULL) THEN a.fechacreacion 
					ELSE a.fechacierre END )  
			BETWEEN DATE_ADD(CURDATE(), INTERVAL 8 DAY) AND ADDDATE(DATE_ADD(CURDATE(), INTERVAL 8 DAY), INTERVAL 6 DAY) 
			AND a.asignadoa <> '' AND c.tipo = 'preventivos'
			AND a.idestados NOT IN (16) AND (b.idmarcas NOT IN (1,7,6) OR (b.idmarcas IS NULL AND c.nombre = 'Infraestructura'))
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
	$cliente	 = $row['cliente'];	
	$nomcat	 	 = $row['nombrecategoria'];	
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
		if($asignadoa != ""){
			if (filter_var($asignadoa, FILTER_VALIDATE_EMAIL)) {
				$correo [] = $asignadoa;
			}else{
				foreach([$asignadoa] as $asig){
					$correo [] = $asig;
				}
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
		enviarMensaje($asunto,$mensaje,$correo,$semana,'asignados',$cuatrimestre,$cliente,$nomcat);
	}	
}

//AMBIENTES
$queryU = " SELECT GROUP_CONCAT(a.id) AS id, b.responsables, GROUP_CONCAT(a.idactivos) AS idactivos,
			cli.nombre AS cliente, d.nombre AS nombrecategoria
			FROM incidentes a 
			LEFT JOIN ambientes b ON a.idambientes = b.id 
			LEFT JOIN activos c ON c.id = a.idactivos
			INNER JOIN categorias d ON d.id = a.idcategorias
			INNER JOIN clientes cli ON cli.id = a.idclientes
			WHERE (CASE WHEN (a.fechacierre IS NULL) THEN a.fechareal 
					ELSE a.fechacierre END ) 
			BETWEEN DATE_ADD(CURDATE(), INTERVAL 8 DAY) 
			AND ADDDATE(DATE_ADD(CURDATE(), INTERVAL 8 DAY), INTERVAL 6 DAY) 
			AND a.asignadoa <> '' AND d.tipo = 'preventivos' 
			AND a.idestados NOT IN (16) 
			AND (c.idmarcas NOT in (1,7,6) OR (c.idmarcas IS NULL AND d.nombre = 'Infraestructura'))
			GROUP BY b.id ";

$resultU = $mysqli->query($queryU);
if($resultU->num_rows <= 0){
	echo "SIN MANTENIMIENTOS PARA ESTA SEMANA";
	exit();
}
while($rowU = $resultU->fetch_assoc()){
	$gincidentes = $rowU['id'];
	$gidactivos  = $rowU['idactivos'];
	$responsables= $rowU['responsables'];	
	$cliente	 = $rowU['cliente'];	
	$nomcat		 = $rowU['nombrecategoria'];	
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
			
			//Excluir usuarios inactivos - Campo Responsables
			$queryr = " SELECT correo FROM usuarios WHERE correo = '".$responsables."' AND estado = 'Activo' ";
			$consultar = $mysqli->query($queryr);
			if($recr = $consultar->fetch_assoc()){
				$correo [] = $responsables;	
			}
			
		}else{
			$arrResponsable = explode(",",$responsables);
			foreach($arrResponsable as $asig){
				//Excluir usuarios inactivos - Campo Responsables
				$queryr = " SELECT correo FROM usuarios WHERE correo = '".$asig."' AND estado = 'Activo' ";
				$consultar = $mysqli->query($queryr);
				if($recr = $consultar->fetch_assoc()){
					$correo [] = $asig;
				} 
			}
		}
		//debugL($incidentes);
		$mensaje = cuerpocorreo($incidentes);	
		enviarMensaje($asunto,$mensaje,$correo,$semana,'sitios',$cuatrimestre,$cliente,$nomcat);
	}
}

function enviarMensaje($asunto,$mensaje,$correo,$semana,$tipo,$cuatrimestre,$cliente,$nomcat) {
	global $mysqli, $mail;
	$cuerpo = "";
	
	$cuerpo .= "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<div style='padding: 5px 10px; font-size: 14px; margin-bottom:50px;color: #000000; '>
						<!--<img src='https://toolkit.maxialatam.com/soporte/images/logo-consorcio.jpg' style='width: initial;height: 80px;float: left;position: absolute;'>-->
						<img src='http://toolkit.maxialatam.com/repositorio-tema/assets/img/logosym-header.png' style='width: auto; float: left;'>
						<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;'>Mantenimiento Preventivo</p>
						<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;'>".$cliente."</p>
						<!--<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;'>PROYECTO TELERADIOLOGIA</p>-->
						<!--<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;'>".$cuatrimestre." CUATRIMESTRE</p>-->
						<!--<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;'>Gestión de Soporte</p>-->
					</div>

					<table border='1' style='width: 100%; border: 1px solid #a8a8a8; border-collapse: collapse;'>
						<tr style='font-size: 12px; font-weight: bold; color: #ffffff; background: #293f76; text-align: center; '>
							<td style='padding: 10px 15px;'>#</td>";							
							
							//Tipo Infraestructura
							if($nomcat == "Infraestructura"){
								
								$cuerpo .="<td style='padding: 10px 15px;'>Título</td>";
								
							}else{
								
								//Tipo Activo
								$cuerpo .="<td style='padding: 10px 15px;'>Tipo</td>
								<td style='padding: 10px 15px;'>Marca</td>
								<td style='padding: 10px 15px;'>Modelo</td>
								<td style='padding: 10px 15px;'>Serie</td>";
								
							}
							
							$cuerpo .="<td style='padding: 10px 15px;'>Ubicación</td>";
							
							//Tipo Activo
							if($nomcat != "Infraestructura"){
								
								$cuerpo .="<td style='padding: 10px 15px;'>Área</td>";
							} 
							
							$cuerpo .="<td style='padding: 10px 15px;'>Fecha del MP (Fecha Real)</td>
							<td style='padding: 10px 15px;'>Hora Aproximada</td>
							<td style='padding: 10px 15px; min-width: 65px;'>Proveedor (Asignado a)</td>
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
		//$mail->addCC('raquel.bedoya@maxialatam.com');
		$mail->addCC('axel.anderson@maxialatam.com');
		$mail->addCC('fernando.rios@maxialatam.com');
		$mail->addCC('maria.baena@maxialatam.com');
	}
	//COPIA OCULTA
	//$mail->addBCC('lismary.18@gmail.com');
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