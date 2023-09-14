<?php
/**
**
*ENVIO DE CORREO DE ACTIVOS PARA VERIFICAR SI VA A
FINALIZAR SU VIDA ÚTIL
**
**/
header('Content-Type: text/html; charset=UTF-8');
error_reporting(1);
require_once("../conexion.php");

$numsem = (date('W')+2);
$numsemana = str_pad($numsem, 2, "0", STR_PAD_LEFT);

$asunto = " Notificación de Vida útil de activo "; 

// $idcliente  = 38;
// $idproyecto = 68;

//Cliente de Santa Fe
// $sql	= " SELECT id, fechainst, vidautil FROM activos a WHERE idclientes = ".$idcliente." AND idproyectos = ".$idproyecto." AND correoenviado = 0 ";
$sql	= " SELECT id, fechainst, vidautil FROM activos a WHERE correoenviado = 0 AND DATE_FORMAT(DATE(fechainst), '%Y-%m-%d') AND vidautil != '' ";

$result	= $mysqli->query($sql);
while($row = $result->fetch_assoc()){
	$idactivo  = $row['id'];
	$fechainst = $row['fechainst'];
	$hoy 	   = date("Y-m-d");
	$vidautil  = $row['vidautil'];
	//echo "<br><br><br><br><br><br><br>IDACTIVO:".$idactivo;
	//echo "<br>FECHAINST:".$fechainst;
	//echo "<br>HOY:".$hoy;
	//echo "<br>VIDAUTIL:".$vidautil;
	$datetime1=new DateTime($fechainst);
	$datetime2=new DateTime($hoy);
	# obtenemos la diferencia entre las dos fechas
	$interval=$datetime2->diff($datetime1);

	# obtenemos la diferencia en meses
	$intervalMeses=$interval->format("%m");
	# obtenemos la diferencia en años y la multiplicamos por 12 para tener los meses
	$intervalAnos = $interval->format("%y")*12;
	$mesesuso = $intervalMeses+$intervalAnos;
	echo "<br>FECHAINST:".$fechainst;
	echo "<br>MESESUSO:".$mesesuso;
	echo "<br>VIDAUTIL:".$vidautil;
	if($vidautil != 0 && $vidautil != "" && $vidautil != null){
		if($vidautil >= $mesesuso){
			$vidautilrestante = $vidautil - $mesesuso;
			echo "<br>ACTIVO:$idactivo VIDAUTILRESTANTE:".$vidautilrestante;
			if($vidautilrestante == 12){
				echo "<BR>TOCAENVIARCORREO";
				$mensaje 	 = "";
				$mensaje = cuerpocorreo($idactivo);
				echo "<br>EL MENSAJE ES:".$mensaje;
				$sqlU = " 	SELECT
							CASE 
								WHEN u.estado = 'Activo' 
									THEN u.correo 
								WHEN u.estado = 'Inactivo' 
									THEN '' 
								END AS correo 
								FROM activos a 
								LEFT JOIN clientes c ON c.id = a.idclientes 
								LEFT JOIN usuarios u ON u.idclientes = c.id 
								WHERE a.id = ".$idactivo;
								
				echo "<br>QUERY USERS:".$sqlU;
				$resultU = $mysqli->query($sqlU);
				
				$correo = array();
				while($rowU = $resultU->fetch_assoc()){
					if($rowU['correo'] != ""){
						$correo [] = $rowU['correo'];
					} 
				}
				
				foreach ($correo as $key => $value) { 
					$querycorreo = "SELECT * FROM notificacionesxusuarios nu
									LEFT JOIN usuarios u ON u.id = nu.idusuario
									WHERE u.correo = '$value' and noti13 = 1";
					$consultacorreo = $mysqli->query($querycorreo);
					if($consultacorreo->num_rows == 0){
						unset($correo[$key]);
					}
				}		
				//echo "<br>EL CORREO ES:".json_encode($correo);
				$enviomail = enviarMensaje($asunto,$mensaje,$correo);
				//echo "ENVIOMAIL ES:".$enviomail;
				if($enviomail == 1){
					$sqlUpd = " UPDATE activos SET correoenviado = 1 WHERE id = '".$idactivo."'";
					//echo "SQL UPD ENVÍO ES:".$sqlUpd;
					$resultUpd = $mysqli->query($sqlUpd);
				}
			}
		}	
	}
}

 


  
//CUERPO DEL CORREO
function cuerpocorreo($idactivo){
	global $mysqli;
	 
	$mensaje = ''; 
	$sqlA  = "	SELECT a.serie, LEFT(a.nombre,45) AS nombre, ma.nombre AS marca, mo.nombre AS modelo, 
				sa.nombre AS area, a.estado, a.fechainst, b.nombre AS ubicacion, d.nombre AS idclientes, 
				e.nombre AS idproyectos 
				FROM activos a 
				LEFT JOIN ambientes b ON a.idambientes = b.id 
				LEFT JOIN subambientes sa ON a.idsubambientes = sa.id 
				LEFT JOIN clientes d ON a.idclientes = d.id 
				LEFT JOIN proyectos e ON a.idproyectos = e.id 
				LEFT JOIN marcas ma ON a.idmarcas = ma.id 
				LEFT JOIN modelos mo ON a.idmodelos = mo.id 
				WHERE a.id = '".$idactivo."' ";
				
	//echo "<br>SQL A :".$sqlA;				
	$resultA = $mysqli->query($sqlA);	
	while($rowA = $resultA->fetch_assoc()){
		
		$serie 		 = $rowA['serie'];
		$nombre 	 = $rowA['nombre'];
		$marca	 	 = $rowA['marca'];
		$modelo 	 = $rowA['modelo'];  
		$estado		 = $rowA['estado']; 
		$fechainst 	 = $rowA['fechainst'];
		$ubicacion 	 = $rowA['ubicacion'];
		$area 	 	 = $rowA['area']; 
		$cliente	 = $rowA['idclientes'];
		$proyecto	 = $rowA['idproyectos'];
		//echo "<br>CUERPO CORREO - SERIE:".$serie;
		$mensaje .= "<tr style='font-size: 12px; color: #000000; text-align: center; '>
						<td style='padding: 10px 15px;'>".$serie."</td> 
						<td style='padding: 10px 15px;'>".$nombre."</td> 
						<td style='padding: 10px 15px;'>".$marca."</td>
						<td style='padding: 10px 15px;'>".$modelo."</td> 
						<td style='padding: 10px 15px;'>".$estado."</td>
						<td style='padding: 10px 15px;'>".$fechainst."</td>
						<td style='padding: 10px 15px;'>".$ubicacion."</td>
						<td style='padding: 10px 15px;'>".$area."</td> 
						<td style='padding: 10px 15px;'>".$cliente."</td>
						<td style='padding: 10px 15px;'>".$proyecto."</td>
					 </tr>"; 
		//$i++;
	}	
	return $mensaje;
}   

function enviarMensaje($asunto,$mensaje,$correo) {
	global $mysqli, $mail;
	$cuerpo = "";
	
	$cuerpo .= "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<!--<div style='padding: 5px 10px; font-size: 14px; margin-bottom:50px;color: #000000; '>
						<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;'>El equipo tiene un año de vida útil restante</p>
						<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;'></p
						<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;'></p>
						<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;'>Gestión de Soporte</p>
					</div>-->
				<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; '>
					<img src='http://toolkit.maxialatam.com/repositorio-tema/assets/img/logosym-header.png' style='width: auto; float: left;'>
					<p style='margin:auto; font-weight:bold; width: 100%; text-align: center;'>Maxia Toolkit<br>
					Gestión de Soporte<br>
				</div> 
					<br>
					<br>
					<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;'>El equipo tiene un año de vida útil restante</p>
					<br>
					<br>
					<table border='1' style='width: 100%; border: 1px solid #a8a8a8; border-collapse: collapse;'>
						<tr style='font-size: 12px; font-weight: bold; color: #ffffff; background: #293f76; text-align: center; '>
							<td style='padding: 10px 15px;'>SERIAL 1</td>
							<td style='padding: 10px 15px;'>NOMBRE</td>
							<td style='padding: 10px 15px;'>MARCA</td>
							<td style='padding: 10px 15px;'>MODELO</td>
							<td style='padding: 10px 15px;'>ESTADO</td>
							<td style='padding: 10px 15px;'>FECHA INSTALACIÓN</td>
							<td style='padding: 10px 15px;'>UBICACIÓN</td>
							<td style='padding: 10px 15px;'>ÁREA</td>
							<!--<td style='padding: 10px 15px; min-width: 65px;'>FECHAS DE MP</td>-->
							<td style='padding: 10px 15px;'>CLIENTE</td>
							<td style='padding: 10px 15px;'>PROYECTO</td>
						</tr>
						".$mensaje."
					</table>
				</div>";
	//$cuerpo .= "<p style='color: #eeeeee;font-size: 10px;padding: 0;margin: 0;'>".json_encode($correo)."</p>";

	//$mail->addAddress('lisbethagapornis@gmail.com');
	foreach($correo as $destino){
	   $mail->addAddress($destino);
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
		 //echo 'Mensaje no pudo ser enviado. ';
		 //echo 'Mailer Error: ' . $mail->ErrorInfo;
		 return 0;
	} else {
		return 1;
		//echo 'Ha sido enviado el correo Exitosamente'; 
		// $mail->ClearAddresses();
		// echo true;
	}   
} 

?>