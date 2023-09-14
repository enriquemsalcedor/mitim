<?php
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
  header('content-type: application/json; charset=utf-8');
  header('Content-Type: application/JSON');
  header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");
  //capurando el tipo de metodo                
	include("../../conexion.php");	
	$method = $_SERVER['REQUEST_METHOD'];        
		
	if ($method =='GET') 
	{
		$action = $_REQUEST['action'];   
		switch($action)
		{
			case "login":
				login();
				break;
			case "recoverypass":
				recoverypass();
				break;
			case "cambiarClave":
			     cambiarClave();
			     break;
			default:
				echo "{failure:true}";
				break;
			}
   }


function cambiarClave() {
	global $mysqli;		
	$id 	= $_REQUEST['idusuario'];
	$clave  = $_REQUEST['clave'];
		
	$query = "UPDATE usuarios SET clave = '$clave' WHERE id = '$id'";		
	$result = $mysqli->query($query);
	if($result==true){		    
		 bitacora($_SESSION['usuario'], "Usuarios", "Se ha cambiado la clave del usuario #".$id."", $id , $query);
		$response['cod'] = true;
		echo json_encode($response);
	}else{
		$response['cod'] = false;
		echo json_encode($response);
	}		
}

	
function recoverypass(){
    global $mysqli;	
     
    $usuario = $_REQUEST['usuario'];
    $query   = " SELECT * FROM usuarios WHERE usuario = '$usuario' ";
    debug($query);
	$result  = $mysqli->query($query);
    if($row = $result->fetch_assoc()){
		$nombre=$row['nombre'];
		$correo=$row['correo'];
        $usuario=$row['usuario'];
        $clave=$row['clave'];
		$fechaRecuperacion = date('Y-m-d');
		$horaRecuperacion = date('H:i:s');
        notificarCorrero($correo,$usuario,$clave,$nombre,$fechaRecuperacion,$horaRecuperacion);
    } else {
		$response['cod'] = true;
		$response['msg'] = 'usuario no existe';
		echo json_encode($response);    
    }

}
 
/*-ENVIA CORREO RECUPERACION DE PASSWORD--------------------------------------------*/ 
function  notificarCorrero($correo,$usuario,$clave,$nombre,$fechaRecuperacion,$horaRecuperacion){
		$asunto = "Recuperacion de clave #$correo"; 
		//MENSAJE		
		$mensaje = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$nombre." has logrado con exito recuperar tu clave</b></p>";				
		$mensaje .= "<p style='width:100%;'>
						<br><br>
						<p style='font-size: 18px;width:100%;'></p>
						<br>
						<p style='width:100%;'>Tu clave es: ".$clave."</p>
						<br>
						<p style='width:100%;'>Recuperado desde la APP</p>
						<br>
						<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin: auto;padding: 10px;width:100%;'>Detalle</p>
						<table style='width: 50%;'>
							<tr>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Solicitante del servicio</div>".$nombre."</td>
							</tr>
							<tr>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Solicitado el</div>".$fechaRecuperacion."</td>
							</tr>
							<tr>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Hora de la Solicitud</div>".$horaRecuperacion."</td>
							</tr>
						<table>
						"; 
			
			$mensaje .= "</div>";
		enviarMensajeRecuperacion($asunto,$mensaje,$correo);
}

/*---------------------------------------------------------------------------*/ 
function enviarMensajeRecuperacion($asunto,$mensaje,$correo) {
		global $mysqli, $mail;
		
		$correo[]=array_unique($correos);//christopher.carnevale.p@gmail.com';
		$cuerpo = "";
		$cuerpo .= "<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; '>";
		$cuerpo .= "<img src='http://toolkit.maxialatam.com/repositorio-tema/assets/img/maxia.jpg' style='width: initial;height: 60px;float: left; position: absolute !important;'>";
		$cuerpo .= "<p style='margin:auto; font-weight:bold; width: 100%; text-align: center;'>Maxia Toolkit<br>";
		$cuerpo .= "Gestión de Soporte<br>";
		$cuerpo .= "</div>";
		$cuerpo .= $mensaje;
		$cuerpo .= "<div style='background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;'>";
		$cuerpo .= "© ".date('Y')." Maxia Latam";
		$cuerpo .= "</div>";	
		
		$mail->clearAddresses();
		foreach($correo as $destino){
		    if( $destino != 'mesadeayuda@innovacion.gob.pa' )
				{
					$mail->addAddress($destino);
				}
		}
		
		$mail->FromName = "Maxia Toolkit - Soporte";
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = $asunto;
		//$mail->MsgHTML($cuerpo);
		$mail->Body = $cuerpo;
		$mail->AltBody = "Maxia Toolkit - Soporte: $asunto";
		
		if(!$mail->send()) 
		{
			echo 'Mensaje no pudo ser enviado. ';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
			debug('Mensaje no pudo ser enviado');
		} else {
			debug('Ha sido enviado el correo Exitosamente');
			//echo 'Ha sido enviado el correo Exitosamente';
			echo true;
		}
}
?>