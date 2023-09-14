<?php
/**
**
*ENVIO DE CORREO DE MANTENIMIENTOS PREVENTIVOS SEMANALES
**
**/
header('Content-Type: text/html; charset=UTF-8');
error_reporting(1);
ini_set('max_execution_time', 300);
require_once("../conexion.php");

/* $numsem = (date('W')+2);
$numsemana = str_pad($numsem, 2, "0", STR_PAD_LEFT); */
$fechaActual = date('d-m-Y');	
$fechaSegundos = strtotime($fechaActual);	
$numsemana = date('W', $fechaSegundos);
//echo 'numsemana: '.$numsemana;

$asunto = "Mantenimientos Preventivos para la Semana ".$numsemana." del Año ".date('Y');

$mensaje = 'PRUEBA DE ENVIO DE CORREO DESDE SOPORTE';	
$correo = '';
enviarMensaje($asunto,$mensaje,$correo);

function enviarMensaje($asunto,$mensaje,$correo) {
	global $mysqli, $mail;
	$cuerpo = "";
	
	$cuerpo .= "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<div style='padding: 5px 10px; font-size: 14px; margin-bottom:50px;color: #000000; '>
						<!--<img src='https://toolkit.maxialatam.com/soporte/images/logo-consorcio.jpg' style='width: initial;height: 80px;float: left;position: absolute;'>-->
						<img src='http://toolkit.maxialatam.com/repositorio-tema/assets/img/logosym-header.png' style='width: auto; float: left;'>
						<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;'>PRUEBAS</p>
					</div>
					".$mensaje."
				</div>";
//	echo 'cuerpo: '.$cuerpo;
	$mail->addAddress('lismary.18@gmail.com');
	/*
	if($correo != ''){
		foreach($correo as $destino){
		   $mail->addAddress($destino);
		}
	}
	*/
	//COPIA OCULTA
	//$mail->addBCC('lismary.18@gmail.com');
	$mail->FromName = "Maxia Toolkit - SYM";
	$mail->isHTML(true); // Set email format to HTML
	$mail->Subject = $asunto;
	//$mail->MsgHTML($cuerpo);
	$mail->Body = $cuerpo;
	$mail->AltBody = "Maxia Toolkit - SYM: ".$asunto;
	echo 'asunto: '.$asunto;
	if(!$mail->send()) {
		echo 'Mensaje no pudo ser enviado. ';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo 'Ha sido enviado el correo Exitosamente';
		// clear all addresses and attachments for the next mail
		$mail->ClearAddresses();
		echo true;
	}  
}

?>