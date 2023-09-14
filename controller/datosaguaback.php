<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){
		case "cargar": 
			  cargar();
			  break;
		case "getdatosagua": 
			  getdatosagua();
			  break;
		case "createdatosagua": 
			  createdatosagua();
			  break;
		case "updatedatosagua": 
			  updatedatosagua();
			  break;
		case "deletedatosagua": 
			  deletedatosagua();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	
	
	function cargar(){
		global $mysqli;
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		$where = array();
		
		$draw = $_REQUEST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		$orderByColumnIndex  = $_REQUEST['order'][0]['0'];// index of the sorting column (0 index based - i.e. 0 is the first record)
		$orderBy = 0;//$_REQUEST['id'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
		$orderType = "DESC";//$_REQUEST['order'][0]['dir']; // ASC or DESC
	    $start   = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		
		$query = " SELECT id, fecha, consumo,turbiedad,tanque1m1,tanque1m2,tanque2m1,tanque2m2,potabilizado,tiempo,estadoplanta,disponibilidad,LEFT(notas,45) as notas
		           FROM datosagua 
		           WHERE 1 = 1 ";
		
	    $query .= " GROUP BY id ";
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		//$query  .= " ORDER BY a.id ASC LIMIT $start, $length ";
		$query  .= " ORDER BY id DESC";
		
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		$response = array();
		while($row = $result->fetch_assoc()){	
		    		    $acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center">
								    <a class="dropdown-item text-info" href="datosagua.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>
								    <a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
								</div>
							</div>
						</td>';
			$resultado[] = array(
				'id' 			=>	$row['id'],
				'acciones' 		=>	$acciones,
				'fecha'		=>	$row['fecha'],
				'consumo' 	=>	$row['consumo'],
				'turbiedad' =>	$row['turbiedad'],
				'tanque1m1' =>	$row['tanque1m1'],
				'tanque1m2' =>	$row['tanque1m2'],
				'tanque2m1' =>	$row['tanque2m1'],
				'tanque2m2' =>	$row['tanque2m2'],
				'potabilizado'   =>	$row['potabilizado'],
				'disponibilidad' =>	$row['disponibilidad'],
				'tiempo'         =>	$row['tiempo'],
				'estadoplanta'   =>	$row['estadoplanta'],
				'notas' 	     =>	$row['notas']
				
			);
		}
		$response = array(			
		  "draw" => intval($draw),
		  "recordsTotal" => intval($recordsTotal),
		  "recordsFiltered" => intval($recordsTotal),
		  "data" => $resultado
		);
		
		echo json_encode($response);
	}	
	
	function createdatosagua(){
		global $mysqli,$mail; 	
		
		$idaguas   = (!empty($_REQUEST['idaguas']) ? $_REQUEST['idaguas'] : '');
		$fecha = (!empty($_REQUEST['fecha']) ? $_REQUEST['fecha'] : '');	
		$consumo	= (!empty($_REQUEST['consumo']) ? $_REQUEST['consumo'] : 0);
		$turbiedad	= (!empty($_REQUEST['turbiedad']) ? $_REQUEST['turbiedad'] : 0);
		$tanque1m1	= (!empty($_REQUEST['tanque1m1']) ? $_REQUEST['tanque1m1'] : 0);
		$tanque1m2	= (!empty($_REQUEST['tanque1m2']) ? $_REQUEST['tanque1m2'] : 0);
		$tanque2m1	= (!empty($_REQUEST['tanque2m1']) ? $_REQUEST['tanque2m1'] : 0);
		$tanque2m2	= (!empty($_REQUEST['tanque2m2']) ? $_REQUEST['tanque2m2'] : 0);
		$potabilizado	= (!empty($_REQUEST['potabilizado']) ? $_REQUEST['potabilizado'] : 0);
		$disponibilidad	= (!empty($_REQUEST['disponibilidad']) ? $_REQUEST['disponibilidad'] : 0);
		$tiempo	= (!empty($_REQUEST['tiempo']) ? $_REQUEST['tiempo'] : '');
		$estadoplanta	= (!empty($_REQUEST['estadoplanta']) ? $_REQUEST['estadoplanta'] : 0);
		$notas	= (!empty($_REQUEST['notas']) ? $_REQUEST['notas'] : '');
		
		$query  = "DELETE FROM datosagua where fecha = '$fecha' ";
		$result = $mysqli->query($query);
		$query 	= '	INSERT INTO	datosagua (fecha, consumo,turbiedad,tanque1m1,tanque1m2,tanque2m1,tanque2m2,potabilizado,disponibilidad,tiempo,estadoplanta,notas) VALUES ( "'.$fecha.'", "'.$consumo.'", "'.$turbiedad.'", "'.$tanque1m1.'", "'.$tanque1m2.'", "'.$tanque2m1.'", "'.$tanque2m2.'", "'.$potabilizado.'", "'.$disponibilidad.'", "'.$tiempo.'", "'.$estadoplanta.'", "'.$notas.'") ';
		$result = $mysqli->query($query);
		$id = $mysqli->insert_id;
		
		if($result == true){
			$campos = array(
				'fecha'				=>	$fecha,
				'consumo' 			=>	$consumo,
				'turbiedad' 		=>	$turbiedad,
				'tanque1m1' 		=>	$tanque1m1,
				'tanque1m2' 		=>	$tanque1m2,
				'tanque2m1' 		=>	$tanque2m1,
				'tanque2m2' 		=>	$tanque2m2,
				'potabilizado' 		=>	$potabilizado,
				'disponibilidad'	=>	$disponibilidad,
				'tiempo' 			=>	$tiempo,
				'estadoplanta' 		=>	$estadoplanta,
				'notas' 			=>	$notas
			);
			nuevoRegistro('Datosagua','Datosagua',$id,$campos,$query);
			echo 1;
		}else{
			echo 0;
		}
				$mensajeHtml = "
			<table border=0>
				<tr><td colspan=4>Maxia Toolkit</td></tr>
				<tr><td colspan=4>Gesti&oacute;n de Mantenimiento</td></tr>
				<tr><td colspan=4>La Nueva Joya</td></tr>
				<tr><td colspan=4>&nbsp;</td></tr>
				<tr><td colspan=4>Datos del Agua al ".date("d/m/Y h:i a")."</td></tr>
				<tr><td colspan=4>&nbsp;</td></tr>
				<tr><td colspan=3>Consumo: $nombre</td><td><strong>$consumo</strong></td></tr>
				<tr><td colspan=3>Turbiedad:</td><td><strong>$turbiedad</strong></td></tr>
				<tr><td colspan=4>&nbsp;</td></tr>
				<tr><td colspan=3>Tanque 1 - Mod. 1:</td><td><strong>$tanque1m1</strong></td></tr>
				<tr><td colspan=3>Tanque 1 - Mod. 2:</td><td><strong>$tanque1m2</strong></td></tr>
				<tr><td colspan=3>Tanque 2 - Mod. 1:</td><td><strong>$tanque2m1</strong></td></tr>
				<tr><td colspan=3>Tanque 2 - Mod. 2:</td><td><strong>$tanque2m2</strong></td></tr>
				<tr><td colspan=3>Potabilizado:</td><td><strong>$potabilizado</strong></td></tr>
				<tr><td colspan=3>Disponibilidad de agua:</td><td><strong>$disponibilidad</strong></td></tr>
				<tr><td colspan=3>Estado del tiempo:</td><td><strong>$tiempo</strong></td></tr>
				<tr><td colspan=3>Estado de la planta:</td><td><strong>$estadoplanta</strong></td></tr>
				<tr><td colspan=4>&nbsp;</td></tr>
				<tr><td colspan=4>Notas</td></tr>
				<tr><td colspan=4>$notas</td></tr>
			</table>";
		//$destinatario = $correos; 	
		//$to="dycoronel@gmail.com";
		$from="toolkit@maxialatam.com";
		$from_name="Maxia Toolkit - La Nueva Joya";
		
		$asunto = "Datos del agua al ".date("d/m/Y h:i a"); 		
		$cuerpo = "<html><head><title>Datos del Agua</title></head><body>"; 		
		$cuerpo .= "<div style='background: #fefefe;padding: 5px 0 5px 10px;'>";
		$cuerpo .= "<img src='http://192.168.10.30/inv/assets/img/maxia.png' style='width: initial;height: 80px;float: left; position: absolute !important;'>";
		$cuerpo .= "<p style='margin: 0;font-weight:bold;width: 100%;text-align: center;'>Maxia Toolkit<br>";
		$cuerpo .= "Gestión de Mantenimiento<br />";
		$cuerpo .= "La Nueva Joya<br />";
		$cuerpo .= "</div>";		
		$cuerpo .= $mensajeHtml;
		$cuerpo .= "<div style='background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;'>";
		$cuerpo .= "© 2017 Maxia Latam";
		$cuerpo .= "</div>";
		$cuerpo .= "</body></html>";
		$mail->From = $from;
		$mail->FromName= $from_name;
		$mail->addReplyTo('daniel.coronel@maxialatam.com', 'Daniel Coronel');
		$mail->isHTML(true);
		$mail->Subject = $asunto;
		$mail->Body = $cuerpo;
		
		/*$query = " SELECT nombre, correo FROM usuarios WHERE usuario = '$responsable' ";
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
		$nombreresp = $row["nombre"];
		$correoresp = $row["correo"];
		$mail->addAddress($correoresp, $nombreresp);*/
		$mail->addAddress("nora.serracin@maxialatam.com", "Nora Serracin");
		$mail->addAddress("jesus.barrios@maxialatam.com", "Jesus Barrios");
		//$mail->send();
		echo true;
	}
	
	function getdatosagua(){
		global $mysqli;
		$idaguas   = (!empty($_REQUEST['idaguas']) ? $_REQUEST['idaguas'] : '');		
		
		$query 		= "	SELECT * FROM datosagua WHERE id = '".$idaguas."' ";
	//	echo($query);
		$result 	= $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){			
			$resultado = array(  
				'fecha'		=>	$row['fecha'],
				'consumo' 	=>	$row['consumo'],
				'turbiedad' =>	$row['turbiedad'],
				'tanque1m1' =>	$row['tanque1m1'],
				'tanque1m2' =>	$row['tanque1m2'],
				'tanque2m1' =>	$row['tanque2m1'],
				'tanque2m2' =>	$row['tanque2m2'],
				'potabilizado'   =>	$row['potabilizado'],
				'tiempo'         =>	$row['tiempo'],
				'disponibilidad' =>	$row['disponibilidad'],
				'estadoplanta'   =>	$row['estadoplanta'],
				'notas' 	     =>	$row['notas']
			);
		}
		
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}
	
	function updatedatosagua(){
		global $mysqli;
		
		$id			 	= (!empty($_REQUEST['idaguas']) ? $_REQUEST['idaguas'] : '');
		$fecha = (!empty($_REQUEST['fecha']) ? $_REQUEST['fecha'] : '');	
		$consumo	= (!empty($_REQUEST['consumo']) ? $_REQUEST['consumo'] : 0);
		$turbiedad	= (!empty($_REQUEST['turbiedad']) ? $_REQUEST['turbiedad'] : 0);
		$tanque1m1	= (!empty($_REQUEST['tanque1m1']) ? $_REQUEST['tanque1m1'] : 0);
		$tanque1m2	= (!empty($_REQUEST['tanque1m2']) ? $_REQUEST['tanque1m2'] : 0);
		$tanque2m1	= (!empty($_REQUEST['tanque2m1']) ? $_REQUEST['tanque2m1'] : 0);
		$tanque2m2	= (!empty($_REQUEST['tanque2m2']) ? $_REQUEST['tanque2m2'] : 0);
		$potabilizado	= (!empty($_REQUEST['potabilizado']) ? $_REQUEST['potabilizado'] : 0);
		$disponibilidad	= (!empty($_REQUEST['disponibilidad']) ? $_REQUEST['disponibilidad'] : 0);
		$tiempo	= (!empty($_REQUEST['tiempo']) ? $_REQUEST['tiempo'] : 'Nublado');
		$estadoplanta	= (!empty($_REQUEST['estadoplanta']) ? $_REQUEST['estadoplanta'] : 'Operativa');
		$notas	= (!empty($_REQUEST['notas']) ? $_REQUEST['notas'] : '');
		
		$valoresold = getRegistroSQL("SELECT fecha AS fecha, consumo  AS 'consumo', turbiedad as turbiedad, tanque1m1 as tanque1m1, tanque1m2 as tanque1m2, tanque2m1 as tanque2m1, tanque2m2 as tanque2m2,potabilizado as potabilizado,disponibilidad as disponibilidad,tiempo as tiempo, estadoplanta as estadoplanta,notas as notas FROM datosagua WHERE id = '".$id."' ");		
		$query 	= '	UPDATE datosagua SET fecha = "'.$fecha.'", consumo = "'.$consumo.'", turbiedad = "'.$turbiedad.'", tanque1m1 = "'.$tanque1m1.'", tanque1m2 = "'.$tanque1m2.'", tanque2m1 = "'.$tanque2m1.'", tanque2m2 = "'.$tanque2m2.'", potabilizado = "'.$potabilizado.'", disponibilidad = "'.$disponibilidad.'", tiempo = "'.$tiempo.'", estadoplanta = "'.$estadoplanta.'", notas = "'.$notas.'" WHERE id = "'.$id.'" ';

		$result = $mysqli->query($query);		
		
		if($result == true){
			$campos = array(
				'fecha'				=>	$fecha,
				'consumo' 			=>	$consumo,
				'turbiedad' 		=>	$turbiedad,
				'tanque1m1' 		=>	$tanque1m1,
				'tanque1m2' 		=>	$tanque1m2,
				'tanque2m1' 		=>	$tanque2m1,
				'tanque2m2' 		=>	$tanque2m2,
				'potabilizado' 		=>	$potabilizado,
				'disponibilidad'	=>	$disponibilidad,
				'tiempo' 			=>	$tiempo,
				'estadoplanta' 		=>	$estadoplanta,
				'notas' 			=>	$notas
			);		
		    actualizarRegistro('Datosagua','Datosagua',$id,$valoresold,$campos,$query);
			echo 1;
		}else{
			echo 0;
		}
	}
		

	
	function deletedatosagua(){
		global $mysqli;
		
		$id   = (!empty($_REQUEST['idaguas']) ? $_REQUEST['idaguas'] : '');
		$fecha = (!empty($_REQUEST['fecha']) ? $_REQUEST['fecha'] : '');		
		$query 	=  " DELETE FROM datosagua WHERE id = '$id' ";
	//	echo($query);
		$result =  $mysqli->query($query);	
		
		if($result == true){		
			eliminarRegistro('Datosagua','Datosagua',$fecha,$id,$query);
			echo 1;
		}else{
			echo 0;
		}
		
	}	

?>