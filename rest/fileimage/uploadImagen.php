<?php
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('content-type: application/json; charset=utf-8');
  //header('Content-Type: application/JSON');
    header('Content-Type: text/plain; charset=utf-8');
    header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");
    include("../../conexion.php");
    $idIncidente=$_POST['id'];
	$idusuario=$_POST['idusuario'];
    $directorio = "../../incidentes/".$idIncidente."/";
    $upload_directRouterFile=$directorio.basename($_FILES['file']['name']);
    $img =$_FILES['file']['name'] ;
    //Si el archivo contiene algo y es diferente de vacio
    if (isset($img) && $img != "") 
    {
        if(!file_exists($directorio))
        {
            $myPath = "../../incidentes/".$idIncidente."/";
    		$target_path2 = utf8_decode($myPath);
    		if (!file_exists($target_path2))
    		mkdir($target_path2, 0777);
        }
        //Obtenemos algunos datos necesarios sobre el archivo
        if(file_exists($directorio)){
             $tipo = $_FILES['file']['type'];
             $tamano = $_FILES['file']['size'];
             $temp = $_FILES['file']['tmp_name'];
             //Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
             /* if (!((strpos($tipo, "gif") || strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) {
                      $response['msg'] = "Laimagen no cumple con los requerimientos";
                      echo json_encode($response);
             }else {*/
            //Si la imagen es correcta en tamaño y tipo Se intenta subir al servidor
            if (move_uploaded_file($temp,$upload_directRouterFile)) {
                   $json[]=array('response'=>1,
                              'msg'=>'Evidencia agregada de forma Exitosa!!!');
                    echo json_encode($json);
					notificacionAdjunto($idIncidente,$idusuario);
            }else {
                    $json[]=array('response'=>0,
                              'msg'=>'Error La evidencia no fue agregada');
                    echo json_encode($json);
            }
             //}
        }else{
            $json[]=array('response'=>2,
                            'msg'=>'no existe el directorio!!!');
            echo json_encode($json);
        }
    }else{
        $json[]=array('response'=>3,
                        'msg'=>'Por favor agregue una imagen!!!');
        echo json_encode($json);
    }
		
	function notificacionAdjunto($incidente,$idusuario) {
		global $mysqli, $mail;
		 
		$idcoment 	= (!empty($_REQUEST['idcoment']) ? $_REQUEST['idcoment'] : '');  
		
		if($idcoment!=""){
			$queryC  = " SELECT visibilidad FROM comentarios WHERE id = $idcoment ";
			$resultC = $mysqli->query($queryC);
			if($rowC = $resultC->fetch_assoc()){
				$visibilidad = $rowC['visibilidad'];
			}else{
				$visibilidad = "";
			}
		}
		
		if($incidente != ''){
			 
			//DATOS DEL CORREO
			$usuarioSes = $_SESSION['usuario'];
			$consultaUA = $mysqli->query("SELECT nombre FROM usuarios WHERE id = ".$idusuario." AND estado = 'Activo' LIMIT 1 ");
			while ($registroUA = $consultaUA->fetch_assoc()) {
				$usuarioAct = $registroUA['nombre'];
			} 
			//$usuarioAct = "Prueba";
			//USUARIOS DE SOPORTE
			//$correo [] = 'ana.porras@maxialatam.com';
			$correo [] = 'isai.carvajal@maxialatam.com';
			$correo [] = 'fernando.rios@maxialatam.com';
			$correo [] = 'axel.anderson@maxialatam.com';
			$correo [] = 'yamarys.powell@maxialatam.com';
			
			$query  = " SELECT a.id, a.titulo, a.notificar, i.usuario AS usuariocreadopor, j.usuario AS usuariosolicitante, k.usuario AS usuarioasignadoa,
						CASE 
							WHEN i.estado = 'Activo' 
								THEN IFNULL(i.correo, a.creadopor)
							WHEN i.estado = 'Inactivo' 
								THEN '' 
							END 
							AS creadopor,
						CASE 
							WHEN j.estado = 'Activo' 
								THEN IFNULL(j.correo, a.solicitante)
							WHEN j.estado = 'Inactivo' 
								THEN '' 
							END 
							AS solicitante,
						CASE 
							WHEN k.estado = 'Activo' 
								THEN a.asignadoa
							WHEN k.estado = 'Inactivo' 
								THEN '' 
							END 
							AS asignadoa, a.tipo
						FROM incidentes a
						LEFT JOIN usuarios i ON a.creadopor = i.correo
						LEFT JOIN usuarios j ON a.solicitante = j.correo
						LEFT JOIN usuarios k ON a.asignadoa = k.correo
						WHERE a.id = ".$incidente." ";
						
			$result = $mysqli->query($query);
			$row 	= $result->fetch_assoc();
			$tipo = $row['tipo'];
				
			if($tipo == 'incidentes'){
				$nombreMay = 'Correctivo'; 
				$nombreMin = 'correctivo';
			}else{
				$nombreMay = 'Preventivo';
				$nombreMin = 'preventivo';
			}
			//USUARIO O GRUPO DE USUARIOS ASIGNADOS
			$asignadoaN	= '';		
			if($row['asignadoa'] != ''){
				$asignadoa  = $row['asignadoa'];
				if (filter_var($asignadoa, FILTER_VALIDATE_EMAIL)) {
					$correo [] = "$asignadoa";
				}else{
					foreach([$asignadoa] as $asig){
						$correo [] = $asig;
					}
				}
				$query2 = " SELECT nombre FROM usuarios WHERE ";
				if (filter_var($row['asignadoa'], FILTER_VALIDATE_EMAIL)) {
					$query2 .= "correo = '".$row['asignadoa']."' AND estado = 'Activo'"; //jesus
				}else{
					$query2 .= "correo IN (".$row['asignadoa'].") AND estado = 'Activo'"; //jesus añadi la linea AND estado ='Activo'
				}
				$consulta = $mysqli->query($query2);
				while($rec = $consulta->fetch_assoc()){
					$asignadoaN .= $rec['nombre']." , ";
				}			
			}		
			//ENVIAR CORREO AL USUARIO QUE CREO EL INCIDENTE
			if($visibilidad != 'Privado'){					 
				if($row['creadopor'] != ''){
					$creadopor  = $row['creadopor'];
					if (filter_var($creadopor, FILTER_VALIDATE_EMAIL)) {
						$correo [] = "$creadopor";				
					}else{
						foreach($creadopor as $creadop){
							$correo [] = $creadop;
						}
					}
				}
			}
			//ENVIAR CORREO AL SOLICITANTE QUE CREO EL INCIDENTE
			if($visibilidad != 'Privado'){
				if($row['solicitante'] != '' && $row['solicitante'] != $row['creadopor']){
					$solicitante  = $row['solicitante'];
					if (filter_var($solicitante, FILTER_VALIDATE_EMAIL)) {
						$correo [] = "$solicitante";
					}else{
						foreach($solicitante as $solicitantep){
							$correo [] = $solicitantep;
						}
					}
				}
			}
			//ENVIAR CORREO A LOS USUARIOS A NOTIFICAR
			if($visibilidad != 'Privado'){
				if($row['notificar'] != ''){
					$notificar  = $row['notificar'];
					if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
						
						//Excluir usuarios inactivos campo Notificar a 
						$queryn = " SELECT usuario, correo FROM usuarios WHERE correo = '".$notificar."' AND estado = 'Activo' ";
						$consultan = $mysqli->query($queryn);
						if($recn = $consultan->fetch_assoc()){
							$correo [] = $notificar;	
							$usuarionotificar  = $recn['usuario'];
						} 
					}else{
						if(is_array($notificar)){ 
							foreach($notificar as $notificarp){
								 
								//Excluir usuarios inactivos campo Notificar a 
								$queryn = " SELECT usuario, correo FROM usuarios WHERE correo = '".$notificarp."' AND estado = 'Activo' ";
								$consultan = $mysqli->query($queryn);
								if($recn = $consultan->fetch_assoc()){
									$correo [] = $notificarp;	
									$usuarionotificar  = $recn['usuario'];
								}
							}
						}else{
							
							$corchetea = '["';
							$corcheteb = '"]';
							//Excluir usuarios inactivos campo Notificar a 
							$queryn = " SELECT usuario, correo FROM usuarios WHERE correo = REPLACE(REPLACE('".$notificar."','".$corchetea."',''),'".$corcheteb."','') AND estado = 'Activo' "; 
							$consultan = $mysqli->query($queryn);
							if($recn = $consultan->fetch_assoc()){
								$correo [] = $notificarp;	
								$usuarionotificar  = $recn['usuario']; 
							} 
						}
					}
				}
			}			
			$cuerpo = "<div style='background:#f6fbf8'>
						<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; margin: 0 6% 0 6%'>";
			$cuerpo .= 	"<img src='https://toolkit.maxialatam.com/soporte/images/encabezado-maxia-c.png' style='width: auto; float: left;'>";
			$cuerpo .= "		<div style='width: 100%; text-align: center; margin-right: 27%; padding-top: 1%; color: #333; font-weight: bold;'>
								<div>Maxia SyM</div><div>Gestión de Soporte</div>
							</div>";
			$cuerpo .= "	</div>";
			$cuerpo .= "<div style='margin: 0 6%; background-color: #FFFFFF; padding: 30px;font-family: arial,sans-serif;'>
							<div style='margin: 0 6% 0 6%; font-size: 22px;width:100%; color:#333; margin-left: 4%'>".$usuarioAct." ha adjuntado nuevo documento al ".$nombreMin." #".$incidente."</div><br>";
			$cuerpo .= "	<p style='width:100%;'>
								<a href='http://toolkit.maxialatam.com/soporte/".$nombreMin.".php?id=".$incidente."' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver ".$nombreMay."</a></p>
							</p>
						</div>
						";
			$cuerpo .= "<div style='margin: 0 6% 0 6%; background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;clear: both;'>";
			$cuerpo .= "© ".date('Y')." Maxia Latam";
			$cuerpo .= "	</div></div>";	
			
			$correo = array_unique($correo);
			//debug(json_encode($correo));
			//echo $correo;
			//Correos PM Tigo
			foreach ($correo as $key => $value) { 
				if ($value == 'jose.barahona@tigo.com.pa' || $value == 'mariano.saibene@tigo.com.pa' || $value == 'fabio.beascoechea@tigo.com.pa') { 
					unset($correo[$key]); 
				}
			}			 
			//debugL("notificacionAdjunto-CORREO:".json_encode($correo),"notificacionAdjunto");			
			
			foreach ($correo as $key => $value) { 
				$querycorreo = "SELECT * FROM notificacionesxusuarios nu
								left join usuarios u on u.id = nu.idusuario
								where u.correo = '$value' and noti10 = 1";
				$consultacorreo = $mysqli->query($querycorreo);
				if($consultacorreo->num_rows == 0){
					unset($correo[$key]);
				}
			}
			foreach($correo as $destino){
				if( $destino != 'mesadeayuda@innovacion.gob.pa' ){
					//$mail->addAddress($destino);		// EVITAR ENVÍO DE CORREO A CLIENTES (DESACTIVADO)
				}			   
			}
			$mail->addAddress("lisbethagapornis@gmail.com");	 
			
			$mail->FromName = "Maxia Toolkit - SYM";
			$mail->isHTML(true); // Set email format to HTML
			if($row['solicitante'] == 'mesadeayuda@innovacion.gob.pa' || $row['creadopor'] == 'mesadeayuda@innovacion.gob.pa'){
				$mail->Subject = $row['titulo'];
			}else{
				$mail->Subject = $nombreMay." #".$incidente." - Nuevo adjunto";
			}
			
			//$mail->MsgHTML($cuerpo);
			$mail->Body = $cuerpo;
			$mail->AltBody = "Maxia Toolkit - SYM ";
			if(!$mail->send()) {
				echo 'Mensaje no pudo ser enviado. ';
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				//echo 'Ha sido enviado el correo Exitosamente';
				//echo true;
			}
			//echo 1;
		}else{
			echo 0;
		}		
	}
?>