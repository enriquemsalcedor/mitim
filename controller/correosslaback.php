<?php
include_once("../conexion.php");
	
$query = "  SELECT 
			a.numero, b.nombre as servicio, c.nombre as sistema, a.actividad, 
			a.proveedor, a.observaciones, e.nombre as sector, f.nombre as edificio, 
			a.fecha, g.nombre as estatus, s.prioridad, a.tipo, a.plan, u.nombre as usuario
			FROM ordenes a
			LEFT JOIN maestro b ON a.servicio = b.id
			LEFT JOIN maestro c ON a.sistema = c.id
			LEFT JOIN maestro e ON a.sector = e.id
			LEFT JOIN maestro f ON a.edificio = f.id
			LEFT JOIN maestro g ON a.estatus = g.id
			LEFT JOIN sla s ON a.prioridad = s.id
			INNER JOIN usuarios u ON a.usuario = u.usuario
			WHERE a.estatus in (35,38) 
				AND a.fecha < now() 
				AND DATE_ADD(a.fecha, INTERVAL 's.dias s.horas' DAY_HOUR) < now()
			LIMIT 1,2 ";
$consulta = $mysqli->query($query);
while ($rec = $consulta->fetch_assoc()) {
	$numero = $rec['numero'];
	$servicio = $rec['servicio'];
	$sistema = $rec['sistema'];
	$actividad = $rec['actividad'];
	$responsable = $rec['proveedor'];
	$observaciones = $rec['observaciones'];
	$sector = $rec['sector'];
	$edificio = $rec['edificio'];
	$fecha = $rec['fecha'];
	$estado = $rec['estatus'];
	$tipo = $rec['tipo'];
	$incidente = $rec['plan'];
	$usuario = $rec['usuario'];
	
	$mensajeHtml = "<table border=0>
						<tr><td colspan=4>Maxia Toolkit</td></tr>
						<tr><td colspan=4>Gesti&oacute;n de Mantenimiento</td></tr>
						<tr><td colspan=4>La Nueva Joya</td></tr>
						<tr><td colspan=4>&nbsp;</td></tr>
						<tr><td colspan=4>Incumplimiento de SLA</td></tr>
						<tr><td colspan=4>Estatus: $estado</td></tr>
						<tr><td colspan=4>Tipo: $tipo</td></tr>
						<tr><td colspan=4>Creado por: $usuario</td></tr>
						<tr><td colspan=4>Orden de mantenimiento Nro. $numero</td></tr>";
	if ($tipo == "Correctiva")
		$mensajeHtml .= "<tr><td colspan=4>Incidente Nro. $incidente</td></tr>";
	$mensajeHtml .= "	<tr><td colspan=4>&nbsp;</td></tr>
						<tr><td>Servicio:</td><td colspan=3>$servicio</td></tr>
						<tr><td>Sistema:</td><td colspan=3>$sistema</td></tr>
						<tr><td>Actividad:</td><td colspan=3>$actividad</td></tr>
						<tr><td>Sector:</td><td colspan=3>$sector</td></tr>
						<tr><td>Edificio:</td><td colspan=3>$edificio</td></tr>
						<tr><td>Responsable:</td><td colspan=3>$responsable</td></tr>
						<tr><td colspan=4>&nbsp;</td></tr>
						<tr><td colspan=4>Descripci&oacute;n</td></tr>
						<tr><td colspan=4>$observaciones</td></tr>";
	$mensajeHtml .= "</table>";
	//$destinatario = $correos; 	
	//$to="dycoronel@gmail.com";
	$from="toolkit@maxialatam.com";
	$from_name="Maxia Toolkit - La Nueva Joya";
	//$msg="<strong>$mensaje</strong>"; // HTML message
	$subject="HTML message";

	$asunto = "Incumplimiento de SLA"; 		
	$cuerpo = "<html><head><title>Incidente</title></head><body>"; 		
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
	
	$query = " SELECT nombre, correo FROM usuarios WHERE usuario = '$responsable' ";
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	$nombreresp = $row["nombre"];
	//$correoresp = $row["correo"];
	$correoresp = "daniel.coronel@maxialatam.com";
	$mail->addAddress($correoresp, $nombreresp);
	//if(!$mail->send()){
	// return "Failure";
	//}
}
	
?>