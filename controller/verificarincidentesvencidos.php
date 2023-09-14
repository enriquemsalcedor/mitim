<?php
/**
**
*ENVIO DE CORREO DE INCIDENTES VENCIDOS O SLA
**
**/

header('Content-Type: text/html; charset=UTF-8');
error_reporting(1);

require_once("../conexion.php");
	
//ENVIO DE CORREO SI HAY INCIDENTES VENCIDOS

$query  = " SELECT a.id, a.titulo, a.asignadoa, a.fechacreacion,
			CONCAT(a.fechacreacion,' ', horacreacion), a.fechavencimiento, IFNULL(f.nombre, a.solicitante) AS solicitante
			FROM incidentes a
			LEFT JOIN usuarios f ON a.solicitante = f.correo
			WHERE fechavencimiento < CURDATE() AND a.estado NOT IN (16,17) ";

//debug($query);
$result = $mysqli->query($query);
if($result->num_rows > 0){
	while($row = $result->fetch_assoc()){
		//if($row['fechavencimiento'] < date('Y-m-d') ) {
			$incidente 	= $row['id'];
			$titulo 	= $row['titulo'];
			$fcreacion 	= $row['fechacreacion'];
			$asignadoa 	= $row['asignadoa'];
			$solicitante 	= $row['solicitante'];
			$fechavencimiento = $row['fechavencimiento'];
				
			if (filter_var($asignadoa, FILTER_VALIDATE_EMAIL)) {
				$correo [] = $asignadoa;				
			}else{
				foreach([$asignadoa] as $asig){
					$correo [] = $asig;
				}
			}				
			//print_r($correo); exit();		

			logdebug('verificarVencidos',date('Y-m-d HH:mm:ss')." - Incidente: #$incidente - Titulo: $titulo - Solicitado: $solicitante - El dia: $fcreacion - Fecha Vencimiento: $fechavencimiento" );			

			$asunto = "Incumplimiento del Incidente #$incidente $titulo";

			$msjhtml = "<table border='0' style='margin: 40px;'>
							<tr><td colspan='4'>Incidente #$incidente</td></tr>
							<tr><td colspan='4'>Titulo: $titulo</td></tr>
							<tr><td colspan='4'>Solicitado por: $solicitante</td></tr>
							<tr><td colspan='4'>Fecha de reporte: $fcreacion</td></tr>
							<tr><td colspan='4'><br/></td></tr>
							<tr><td colspan='4'>La fecha limite de cumplimiento establecida es el $fechavencimiento</td></tr>
							<tr><td colspan='4'>Favor proceder a resolver el mismo en la brevedad posible.</td></tr>";
			$msjhtml .= "</table>";
			
			$cuerpo = "";
			$cuerpo .= "<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; '>";
			$cuerpo .= "<img src='http://web.maxialatam.com:8010/repositorio-tema/assets/img/maxia.jpg' style='width: initial;height: 60px;float: left; position: absolute !important;'>";
			$cuerpo .= "<p style='margin:auto; font-weight:bold; width: 100%; text-align: center;'>Maxia Toolkit<br>";
			$cuerpo .= "Gestión de Soporte<br>";
			$cuerpo .= "</div>";
			$cuerpo .= $msjhtml;
			$cuerpo .= "<div style='background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;'>";
			$cuerpo .= "© ".date('Y')." Maxia Latam";
			$cuerpo .= "</div>";	
			
			$correo = array('daniel.coronel@maxialatam.com');
			foreach($correo as $destino){
			   $mail->addAddress($destino);
			}
			//$mail->addAddress($correo);
			//$mail->addReplyTo('daniel.coronel@maxialatam.com', 'Daniel Coronel');
			//$mail->addCC('');
			//$mail->addBCC('');		
			$mail->FromName = "Maxia Toolkit - SYM";
			$mail->isHTML(true); // Set email format to HTML
			$mail->Subject = $asunto;
			//$mail->MsgHTML($cuerpo);
			$mail->Body = $cuerpo;
			$mail->AltBody = "Maxia Toolkit - SYM: $asunto";
			/*if(!$mail->send()) {
				echo 'Mensaje no pudo ser enviado. ';
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				echo 'Ha sido enviado el Correo Exitosamente ';
				//echo true;
			}*/
			exit();
		//}
	}
}
?>