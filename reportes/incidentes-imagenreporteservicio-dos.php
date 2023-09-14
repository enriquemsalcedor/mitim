<?php
	include("../conexion.php");
	//Medidas
	$width = 664;
	$height = 1024;
	
	// Establecer el tipo de contenido
	header("Content-Type: image/png");
	$plantilla = "base-maxia.png";
	$idincidente = (!empty($_REQUEST['idincidente']) ? $_REQUEST['idincidente'] : 0);
	$idreporte = (!empty($_REQUEST['idreporte']) ? $_REQUEST['idreporte'] : 0);
	
	$query  = " SELECT a.id AS idreporte, a.codigo, a.idincidente, a.departamento, a.ubicacionactivo, a.tiposervicio, a.fallareportada,
				a.trabajorealizado, a.observaciones, a.estadoactivo, b.idclientes, c.nombre AS sitio, d.serie AS serie, d.nombre AS equipo, 
				ma.nombre AS marca, mo.nombre AS modelo, b.asignadoa, 
				IF(( b.fechacreacion is not null OR LENGTH(ltrim(rTrim(b.fechacreacion))) > 0), b.fechacreacion,'') AS fechacreacion, 
				b.horacreacion, b.fueraservicio, a.firmatecnico, a.firmacliente1, a.firmacliente2, a.fechafirmatecnico, a.nombrecliente1, 
				a.nombrecliente2, a.fechafirmacliente, e.nombre as nombretecnico
				FROM reporteservicios a 
				INNER JOIN incidentes b ON a.idincidente = b.id 
				LEFT JOIN ambientes c ON b.idambientes = c.id
				LEFT JOIN activos d ON b.idactivos = d.id AND d.serie != ''
				LEFT JOIN usuarios e ON b.asignadoa = e.correo
				LEFT JOIN marcas ma ON d.idmarcas = ma.id
				LEFT JOIN modelos mo ON d.idmodelos = mo.id
				WHERE a.idincidente = '".$idincidente."' AND a.id='".$idreporte."' "; 
					
	//debug($query);
	$result = $mysqli->query($query);	
	if($row = $result->fetch_assoc()){
		
		$idreporte				=  $row['idreporte'];
		$codigoreporte			=  $row['codigo'];
		$idclientes				=  $row['idclientes'];
		$sitio 					=  $row['sitio'];
		$serie  				=  $row['serie']; 
		$equipo  				=  $row['equipo']; 
		$marca  				=  $row['marca'];
		$modelo  				=  $row['modelo']; 
		$asignadoa  			=  $row['asignadoa']; 
		$fechacreacion  		=  date("d-m-Y", strtotime($row['fechacreacion']));
		$fueraservicio  		=  $row['fueraservicio'];
		$departamento  			=  $row['departamento']; 
		$ubicacionactivo  		=  $row['ubicacionactivo'];
		$tiposervicio  			=  $row['tiposervicio']; 
		$fallareportada  		=  $row['fallareportada'];
		$trabajorealizado  		=  $row['trabajorealizado'];
		$observaciones  		=  $row['observaciones'];
		$firmatecnico 		 	=  $row['firmatecnico'];
		$firmacliente1 		 	=  $row['firmacliente1']; 
		$firmacliente2 	 		=  $row['firmacliente2']; 
		$fechafirmatecnico  	=  $row['fechafirmatecnico'];
		$nombrecliente1 		=  $row['nombrecliente1'];
		$nombrecliente2  		=  $row['nombrecliente2']; 
		$fechafirmacliente  	=  $row['fechafirmacliente'];
		$estadoactivo  			=  $row['estadoactivo'];
		$nombretecnico  		=  $row['nombretecnico'];
		debug($firmatecnico);
		$queryP = " SELECT plantilla FROM clientes WHERE id = ".$idclientes." ";
		$resultP = $mysqli->query($queryP);
		if($rowP = $resultP->fetch_assoc()){
			$plantilla = isset($rowP['plantilla']) ? $rowP['plantilla'] : "base-maxia.png";
		}else{
			$plantilla = "base-maxia.png";
		} 
		
	}
	
	// Crear la imagen
	//$im = @imagecreate($width, $height) or die("Cannot Initialize new GD image stream"); 
	$im = imagecreatefrompng("../images/".$plantilla."") or die("Cannot Initialize new GD image stream");
	$origen = imagecreatefrompng("../images/".$plantilla."") or die("Cannot Initialize new GD image stream");
	//$im = imagecreatetruecolor($width, $height);
	
	// Crear algunos colores
	$blanco = imagecolorallocate($im, 255, 255, 255);
	$gris = imagecolorallocate($im, 128, 128, 128);
	$negro = imagecolorallocate($im, 0, 0, 0);
	$azul  = imagecolorallocate($im, 41, 63, 118);
	$rojo  = imagecolorallocate($im, 255, 0, 0);  
	// Establecer el fondo a blanco
	//imagefilledrectangle($im, 0, 0, 299, 299, $blanco);
	
	// Reemplace la ruta por la de su propia fuente
	//putenv('GDFONTPATH=' . realpath('.'));
	$fuente = 'C:\wamp64\www\soporteqa\fonts\open\OpenSans-Regular.ttf';
	$text_color = imagecolorallocate($im, 0, 0, 0);
	//echo $fuente;
	$txtsubtit        = "Solicitud de  Servicio";
	$txttitulo        = "Reporte de Solicitud de  Servicio";
	$txtnumero        = "N° ";
	$txtsitio 		  = "Ambiente: ".$sitio;
	$txtdepto		  = "Departamento: ".$departamento;
	$txtequipo 		  = "Equipo:".$equipo;
	$txtubicacion 	  = "Ubicación del Equipo:".$ubicacionactivo;
	$txtticket 		  = "Ticket N°.: ".$idincidente;
	$txtmarca 		  = "Marca: ".$marca;
	$txtmodelo 		  = "Modelo: ".$modelo;
	$txtserie 		  = "Número de Serie: ".$serie;
	$txtfechacreacion = "Fecha de Solicitud: ".$fechacreacion;
	$txttiposervicio  = "Tipo de Servicio ";
	$txttipocorr 	  = "Correctivo";
	$txttipoprev      = "Preventivo";
	$txttipoeval      = "Evaluación";
	$txthoraslab      = "Horas Laborales";
	$txtfecha         = "Fecha";
	$txtdia           = "Día";
	$txtmes		      = "Mes";
	$txtanio          = "Año";
	$txttiempo   	  = "Tiempo de";
	$txttiempoviaje   = "Viaje";
	$txttiempolabor   = "Labor";
	$txttiempoespera  = "Espera";
	$txthorainicio    = "Hora de Inicio";
	$txthorafin       = "Hora Fin de Labores";
	$txtrepuestos     = "Repuestos";
	$txtcodigorep     = "Código de Repuesto";
	$txtcantidadrep   = "Cantidad";
	$txtdescrep       = "Descripción";
	$txtfalla         = "Falla o Error Reportado:";
	$txttrabajo       = "Trabajo Realizado:";
	$txtobservacion   = "Observaciones:";
	$txtestadoequipo  = "Estado del Equipo al Finalizar Servicio";
	$txtfuncional     = "Funcional";
	$txtfuncionalp    = "Funcional Parcial";
	$txtfueraserv     = "Fuera de Servicio";
	$txtnombretec     = "Nombre de Técnico";
	$txtfechafirmat   = "Fecha";
	$txtfirmatecnico  = "Firma Técnico";
	$txtevaluacion    = "Evaluación";
	$txtnombrecli1    = "Nombre de Cliente";
	$txtfirmacliente1 = "Firma Cliente";
	$txtnombrecli2    = "Nombre de Cliente";
	$txtfirmacliente2 = "Firma de Cliente";
	$txtfechafirmac   = "Fecha";
 
	$size 		= 8;
	$sizetitulo = 12;   
	
	
	/*----------------------------------------------CABECERA----------------------------------------------------*/
	
	$y1fila00 = 40;	
	$y2fila00 = 20;	
	
	imagefilledrectangle($im, 640, $y1fila00, 460, $y2fila00, $azul);
	imagettftext($im, $sizetitulo, 0, 470, $y2fila00+15, $blanco, $fuente, $txtsubtit); 
	
	$y1fila0 = 100;	
	imagettftext($im, $sizetitulo, 0, 20, $y1fila0, $negro, $fuente, $txttitulo);
	
	imagettftext($im, $sizetitulo, 0, 550, $y1fila0, $negro, $fuente, $txtnumero);
	imagettftext($im, $sizetitulo, 0, 575, $y1fila0, $rojo, $fuente, $codigoreporte); 
	
	//Sitio 
	//Separar Lineas Sitio
	$y1fila1 = 120;	$y2fila1 = 140;
	$liness = explode('|', wordwrap($txtsitio, 40, '|'));  
	$ys = $y1fila1+14; 
	foreach ($liness as $lins)
	{
		imagettftext($im, $size, 0, 25, $ys, $negro, $fuente, $lins); 
		$ys += 10;
	}
	//Separar Lineas Departamento
	$linesd = explode('|', wordwrap($txtdepto, 40, '|'));  
	$yd = $y1fila1+14; 
	foreach ($linesd as $lined)
	{
		imagettftext($im, $size, 0, 400, $yd, $negro, $fuente, $lined); 
		$yd += 10;
	}
	$ytotal0 = 0;
	if($ys >= $yd){
		$ytotal0 = $ys;
	}
	if($yd > $ys){
		$ytotal0 = $yd;
	} 
	
	imagerectangle($im, 395, $y1fila1, 20, $ytotal0, $negro);//X1,Y1,X2,Y2
	//imagettftext($im, $size, 0, 25, $y1fila1+14, $negro, $fuente, $txtsitio); //imagen, size, angulo, x, y, color, font, text
	
	imagerectangle($im, 640, $y1fila1, 395, $ytotal0, $negro);
	//imagettftext($im, $size, 0, 400, $y1fila1+14, $negro, $fuente, $txtdepto);
	  
	//Equipo
	$y1fila2 = $ytotal0;	$y2fila2 = 160;
	 
	//imagettftext($im, $size, 0, 25, $y1fila2+14, $negro, $fuente, $txtequipo);
	
	$linese = explode('|', wordwrap($txtequipo, 40, '|'));  
	$ye = $y1fila2+14; 
	foreach ($linese as $linee)
	{
		imagettftext($im, $size, 0, 25, $ye, $negro, $fuente, $linee); 
		$ye += 10;
	}
	
	 
 	//imagettftext($im, $size, 0, 245, $y1fila2+14, $negro, $fuente, $txtubicacion);
	
	$linesu = explode('|', wordwrap($txtubicacion, 50, '|')); 
	$yu = $y1fila2+14; 
	foreach ($linesu as $lineu)
	{
		imagettftext($im, $size, 0, 245, $yu, $negro, $fuente, $lineu); 
		$yu += 10;
	}
	
	$ytotal1 = 0;
	if($ye >= $yu){
		$ytotal1 = $ye;
	}
	if($yu > $ye){
		$ytotal1 = $yu;
	} 
	imagerectangle($im, 240, $y1fila2, 20, $ytotal1, $negro);
	
	imagerectangle($im, 240,  $y1fila2, 520, $ytotal1, $negro);
	
	imagerectangle($im, 520, $y1fila2, 640, $ytotal1, $negro); 
	imagettftext($im, $size, 0, 525, $y1fila2+14, $negro, $fuente, $txtticket); 
	
	//Marca
	
	$linesmar = explode('|', wordwrap($txtmarca, 30, '|'));  
	$ymar = $ytotal1+14; 
	foreach ($linesmar as $linemar)
	{
		imagettftext($im, $size, 0, 25, $ymar, $negro, $fuente, $linemar); 
		$ymar += 10;
	}
	
	$linesmod  = explode('|', wordwrap($txtmodelo, 30, '|'));  
	$ymod = $ytotal1+14; 
	foreach ($linesmod as $linemod)
	{
		imagettftext($im, $size, 0, 205, $ymod, $negro, $fuente, $linemod); 
		$ymod += 10;
	}
	
	$linesser = explode('|', wordwrap($txtserie, 30, '|'));  
	$yser = $ytotal1+14; 
	foreach ($linesser as $linese)
	{
		imagettftext($im, $size, 0, 405, $yser, $negro, $fuente, $linese); 
		$yser += 10;
	} 
	
	$ytotal2 = 0;
	if($ymar > $ymod && $ymar > $yser){
		$ytotal2 = $ymar; 
	} 
	if($ymod > $ymar && $ymod > $yser){
		$ytotal2 = $ymod; 
	} 
	if($yser > $ymod && $yser > $ymar){
		$ytotal2 = $yser; 
	}  
	if($ymar == $ymod && $ymod == $yser && $ymar == $yser){
		$ytotal2 = $ymar;
	}
	//debug('ytotal2:'.$ytotal2);
	$y1fila3 = 160;	$y2fila3 = 180;
	imagerectangle($im, 200, $ytotal1, 20, $ytotal2, $negro);
	//imagettftext($im, $size, 0, 25, $ytotal1+14, $negro, $fuente, $txtmarca); 
	
	imagerectangle($im, 400, $ytotal1, 200, $ytotal2, $negro);
	//imagettftext($im, $size, 0, 205, $ytotal1+14, $negro, $fuente, $txtmodelo);
	
	imagerectangle($im, 640, $ytotal1, 400, $ytotal2, $negro);
	//imagettftext($im, $size, 0, 405, $ytotal1+14, $negro, $fuente, $txtserie); 
	
	//Fecha de Solicitud
	$y1filafc = $ytotal2;	$y2filafc = $ytotal2+20; 
	
	imagerectangle ($im, 640, $y1filafc, 20, $y2filafc, $negro);
	imagettftext($im, $size, 0, 25, $y1filafc+14, $negro, $fuente, $txtfechacreacion);
	
	//Tipo Servicio
	$y1fila4 = $y2filafc;	 $y2fila4 = $y2filafc+20;
	imagerectangle($im, 216, $y1fila4, 20, $y2fila4, $negro);
	imagettftext($im, $size, 0, 25, $y1fila4+14, $negro, $fuente, $txttiposervicio);
	  
	if($tiposervicio=="correctivo"){
		$chequeadocorr = "X";
	}else{
		$chequeadocorr = " ";
	}  
	
	if($tiposervicio=="preventivo"){
		$chequeadoprev = "X";
	}else{
		$chequeadoprev = " ";
	}  
	
	if($tiposervicio=="evaluacion"){
		$chequeadoeval = "X";
	}else{
		$chequeadoeval = " ";
	}   
	
	//Correctivo
	imagerectangle($im, 640, $y1fila4, 216, $y2fila4, $negro);
	imagettftext($im, $size, 0, 221, $y1fila4+14, $negro, $fuente, $txttipocorr);
	 
	//Check Correctivo  
	imagettftext($im, $size, 0, 282, $y1fila4+14, $negro, $fuente, $chequeadocorr);
	imagerectangle($im, 290, $y1fila4+5, 280, $y2fila4-5, $negro);
	
	//Preventivo 
	imagettftext($im, $size, 0, 361, $y1fila4+14, $negro, $fuente, $txttipoprev); 
	
	//Check Preventivo
	imagettftext($im, $size, 0, 427, $y1fila4+14, $negro, $fuente, $chequeadoprev);
	imagerectangle($im, 435, $y1fila4+5, 425, $y2fila4-5, $negro);
	
	//Evaluación 
	imagettftext($im, $size, 0, 498, $y1fila4+14, $negro, $fuente, $txttipoeval); 
	
	//Check Evaluación
	imagettftext($im, $size, 0, 567, $y1fila4+14, $negro, $fuente, $chequeadoeval);
	imagerectangle($im, 575, $y1fila4+5, 565, $y2fila4-5, $negro); 
	
	/*----------------------------------------------FECHAS----------------------------------------------------*/
	
	//Horas Laborales
	$y1fila5 = $y2fila4;	$y2fila5 = $y2fila4+20; 
	
	imagefilledrectangle ($im, 640, $y1fila5, 20, $y2fila5, $azul);
	imagettftext($im, $size, 0, 300, $y1fila5+14, $blanco, $fuente, $txthoraslab); 
	 
	//TÍTULO FECHAS
	//Fecha
	$x1fecha = 115;		$x2fecha = 20;   $y1fila6 = $y2fila5;	$y2fila6 = $y2fila5+20;
	
	imagerectangle($im, $x1fecha, $y1fila6, $x2fecha, $y2fila6, $negro);
	imagettftext($im, $size, 0, $x2fecha+30, $y1fila6+14, $negro, $fuente, $txtfecha); 
	 	
	//Dia 
	$y1diatxt = $y2fila6; 		$y2diatxt = $y1diatxt+20; 		$x1diatxt = 55; 		$x2diatxt = 20;
	$y1mestxt = $y2fila6; 		$y2mestxt = $y1mestxt+20; 		$x1mestxt = 85; 		$x2mestxt = 55;
	$y1aniotxt= $y2fila6; 		$y2aniotxt= $y1aniotxt+20; 		$x1aniotxt= 115; 		$x2aniotxt= 85;
	
	imagerectangle($im, $x1diatxt, $y1diatxt, $x2diatxt, $y2diatxt, $negro);
	imagettftext($im, $size, 0, $x2diatxt+5, $y1diatxt+14, $negro, $fuente, $txtdia);  
	
	//Mes
	imagerectangle($im, $x1mestxt, $y1mestxt, $x2mestxt, $y2mestxt, $negro);
	imagettftext($im, $size, 0, $x2mestxt+5, $y1mestxt+14, $negro, $fuente, $txtmes); 
	
	//Hora
	imagerectangle($im, $x1aniotxt, $y1aniotxt, $x2aniotxt, $y2aniotxt, $negro);
	imagettftext($im, $size, 0, $x2aniotxt+5, $y1aniotxt+14, $negro, $fuente, $txtanio);   
	
	//Tiempo de viaje
	$x2tiempoviaje1 = $x1fecha;
	$x1tiempoviaje1 = $x2tiempoviaje1+100;
	$y2tiempoviaje1 = $y2aniotxt;
	
 	imagerectangle($im, $x1tiempoviaje1, $y1fila6, $x2tiempoviaje1, $y2tiempoviaje1, $negro);
	imagettftext($im, $size, 0, $x2tiempoviaje1+25, $y1fila6+16, $negro, $fuente, $txttiempo);  
	imagettftext($im, $size, 0, $x2tiempoviaje1+37, $y1fila6+28, $negro, $fuente, $txttiempoviaje);   
	 
	//Tiempo de labor 
	$x2tiempolabor1 = $x1tiempoviaje1;
	$x1tiempolabor1 = $x2tiempolabor1+100;
	$y2tiempolabor1 = $y2aniotxt;
	
	imagerectangle($im, $x1tiempolabor1, $y1fila6, $x2tiempolabor1, $y2tiempolabor1, $negro);
	imagettftext($im, $size, 0, $x2tiempolabor1+25, $y1fila6+16, $negro, $fuente, $txttiempo);  
	imagettftext($im, $size, 0, $x2tiempolabor1+37, $y1fila6+28, $negro, $fuente, $txttiempolabor);   
	
	//Tiempo de espera
	$x2tiempoespera1 = $x1tiempolabor1;
	$x1tiempoespera1 = $x2tiempoespera1+100;
	$y2tiempoespera1 = $y2aniotxt;
	
	imagerectangle($im, $x1tiempoespera1, $y1fila6, $x2tiempoespera1, $y2tiempoespera1, $negro);
	imagettftext($im, $size, 0, $x2tiempoespera1+25, $y1fila6+16, $negro, $fuente, $txttiempo); 
	imagettftext($im, $size, 0, $x2tiempoespera1+35, $y1fila6+28, $negro, $fuente, $txttiempoespera);  
	
	//Hora inicio
	$x2horainicio1 = $x1tiempoespera1;
	$x1horainicio1 = $x2horainicio1+80;
	$y2horainicio1 = $y2aniotxt;
	
	imagerectangle($im, $x1horainicio1, $y1fila6, $x2horainicio1, $y2horainicio1, $negro);
	imagettftext($im, $size, 0, $x2horainicio1+5, $y1fila6+24, $negro, $fuente, $txthorainicio);  
	
	//Hora Fin
	$x2horafin1 = $x1horainicio1;
	$x1horafin1 = $x2horafin1+145;
	$y2horafin1 = $y2aniotxt;
	
	imagerectangle($im, $x1horafin1, $y1fila6, $x2horafin1, $y2horafin1, $negro);
	imagettftext($im, $size, 0, $x2horafin1+18, $y1fila6+24, $negro, $fuente, $txthorafin);     
	 
	$y1fechas = $y2aniotxt;
	$y2fechas = $y1fechas+20;
	
	$query  = " SELECT a.id, DATE(a.fecha) as fecha, DATE_FORMAT(a.tiempoviaje,'%H:%i') AS tiempoviaje,
				DATE_FORMAT(a.tiempolabor,'%H:%i') AS tiempolabor, DATE_FORMAT(a.tiempoespera,'%H:%i') AS tiempoespera, 
				DATE_FORMAT(a.horainicio,'%H:%i') AS horainicio, DATE_FORMAT(a.horafin,'%H:%i') AS horafin 
				FROM reporteserviciosfechas a  
				WHERE a.idreporte = '$idreporte' ORDER BY a.id DESC ";
		 			
	$result = $mysqli->query($query);
	$recordsTotal = $result->num_rows; 
	
	while($row = $result->fetch_assoc()){  
	
		$fecha 			=  $row['fecha'];
		$tiempoviaje 	=  $row['tiempoviaje'];
		$tiempolabor 	=  $row['tiempolabor'];
		$tiempoespera 	=  $row['tiempoespera'];
		$horainicio 	=  $row['horainicio'];
		$horafin 		=  $row['horafin'];
		
		$dato = explode("-", $fecha); 
		
		$dia  = $dato[2];	
		$mes  = $dato[1];	
		$anio = $dato[0];		
		
		//Dia 
		$y1dia = $y2aniotxt; 	$y2dia = $y1dia+20; 		$x1dia = 55; 		$x2dia = 20;
		$y1mes = $y2aniotxt; 	$y2mes = $y1mes+20; 		$x1mes = 85; 		$x2mes = 55;
		$y1anio= $y2aniotxt; 	$y2anio= $y1anio+20; 		$x1anio= 115; 		$x2anio= 85;
		
	 	imagerectangle($im, $x1dia, $y1fechas, $x2dia, $y2fechas, $negro);
		imagettftext($im, $size, 0, $x2dia+10, $y1fechas+14, $negro, $fuente, $dia); 
		
		//Mes
		imagerectangle($im, $x1mes, $y1fechas, $x2mes, $y2fechas, $negro);
		imagettftext($im, $size, 0, $x2mes+10, $y1fechas+14, $negro, $fuente, $mes);
		
		//Año
		imagerectangle($im, $x1anio, $y1fechas, $x2anio, $y2fechas, $negro);
		imagettftext($im, $size, 0, $x2anio+4, $y1fechas+14, $negro, $fuente, $anio);  
		
		//Tiempos
		imagerectangle($im, $x1tiempoviaje1, $y1fechas, $x1anio, $y2fechas, $negro);
		imagettftext($im, $size, 0, $x2tiempoviaje1+39, $y1fechas+14, $negro, $fuente, $tiempoviaje);
		
		imagerectangle($im, $x1tiempolabor1, $y1fechas, $x1tiempoviaje1, $y2fechas, $negro);
		imagettftext($im, $size, 0, $x2tiempolabor1+40, $y1fechas+14, $negro, $fuente, $tiempolabor); 
		
		imagerectangle($im, $x1tiempoespera1, $y1fechas, $x1tiempolabor1, $y2fechas, $negro);
		imagettftext($im, $size, 0, $x2tiempoespera1+40, $y1fechas+14, $negro, $fuente, $tiempoespera);
		
		//Horas
	 	imagerectangle($im, $x1horainicio1, $y1fechas, $x1tiempoespera1, $y2fechas, $negro);
		imagettftext($im, $size, 0, $x2horainicio1+28, $y1fechas+14, $negro, $fuente, $horainicio);
		
		imagerectangle($im, $x1horafin1, $y1fechas, $x1horainicio1, $y2fechas, $negro);
		imagettftext($im, $size, 0, $x2horafin1+60, $y1fechas+14, $negro, $fuente, $horafin);   
		 
		$y1fechas=$y1fechas+20;
		$y2fechas=$y2fechas+20;
	}  
	
	/*----------------------------------------------REPUESTOS----------------------------------------------------*/	
	
	$y1filarepuestos = $y1fechas;
	$y2filarepuestos = $y1filarepuestos+20;
	
 	imagefilledrectangle($im, 640, $y1filarepuestos, 20, $y2filarepuestos, $azul);
	imagettftext($im, $size, 0, 300, $y1filarepuestos+14, $blanco, $fuente, $txtrepuestos);
 
	
	imagerectangle($im, 200, $y2filarepuestos, 20, $y2filarepuestos+20, $negro);
	imagettftext($im, $size, 0, 25, $y2filarepuestos+14, $negro, $fuente, $txtcodigorep); 
	
	imagerectangle($im, 400, $y2filarepuestos, 200, $y2filarepuestos+20, $negro);
	imagettftext($im, $size, 0, 210, $y2filarepuestos+14, $negro, $fuente, $txtcantidadrep);
	
	imagerectangle($im, 640, $y2filarepuestos, 400, $y2filarepuestos+20, $negro);
	imagettftext($im, $size, 0, 410, $y2filarepuestos+14, $negro, $fuente, $txtdescrep); 
	
	$y1repuestos = $y2filarepuestos+20;
	$y2repuestos = $y1repuestos+20;
	
	$query  = " SELECT a.id, a.codigo, a.cantidad, a.descripcion
					FROM reporteserviciosrepuestos a  
					WHERE a.idreporte = '$idreporte' ORDER BY a.id DESC ";
		 
	$result = $mysqli->query($query);
	$recordsTotal = $result->num_rows;
	while($row = $result->fetch_assoc()){
		
		$codigo		 = $row['codigo'];
		$cantidad	 = $row['cantidad'];
		$descripcion = $row['descripcion']; 
		
		$ytotal3 = 0;
		$linescod  = explode('|', wordwrap($codigo, 20, '|'));  
		$ycod = $y1repuestos+14; 
		foreach ($linescod as $linec)
		{
			imagettftext($im, $size, 0, 25, $ycod, $negro, $fuente, $linec); 
			$ycod += 10;
		}
		
		
		$linesdesc  = explode('|', wordwrap($descripcion, 40, '|'));  
		$ydesc = $y1repuestos+14; 
		foreach ($linesdesc as $linedescrip)
		{
			imagettftext($im, $size, 0, 410, $ydesc, $negro, $fuente, $linedescrip); 
			$ydesc += 10;
		}
		
		if($ycod >= $ydesc){
			$y2repuestos = $ycod;
		}
		if($ydesc > $ycod){
			$y2repuestos = $ydesc;
		} 
		imagerectangle($im, 200, $y1repuestos, 20, $y2repuestos, $negro); 
		
		//imagettftext($im, $size, 0, 25, $y1repuestos+14, $negro, $fuente, $codigo); 
		
		imagerectangle($im, 400, $y1repuestos, 200, $y2repuestos, $negro);
		imagettftext($im, $size, 0, 210, $y1repuestos+14, $negro, $fuente, $cantidad);
		
		imagerectangle($im, 640, $y1repuestos, 400, $y2repuestos, $negro);
		//imagettftext($im, $size, 0, 410, $y1repuestos+14, $negro, $fuente, $descripcion); 
		
		$y1repuestos=$y2repuestos;
		//$y2repuestos=$y2repuestos+20; 
	}    
	
	/*-----------------------------------------------FALLA------------------------------------------------------*/
	
	
	$lonfalla = strlen($txtfalla); 
	$y1filafalla = $y2repuestos;	$y2filafalla = $y1filafalla+20;  	$x2filafalla = 150; 		$x1filafalla = 20;
	 
 	imagefilledrectangle($im, $x1filafalla, $y1filafalla, $x2filafalla, $y2filafalla, $azul);
	imagettftext($im, $size, 0, $x1filafalla+5, $y1filafalla+14, $blanco, $fuente, $txtfalla); 
	
	$y1falla = $y1filafalla;//$y1filafalla+20;
	//$y2falla = $y1falla+100;
	
	
	//imagettftext($im, $size, 0, 160, $y1falla+14, $negro, $fuente, $fallareportada); 
	
	// Break it up into pieces 125 characters long
	$linesf = explode('|', wordwrap($fallareportada, 116, '|')); 
	// Starting Y position
	$yf = $y1falla+35;
	// Loop through the lines and place them on the image
	foreach ($linesf as $linef)
	{
		imagettftext($im, $size, 0, 25, $yf, $negro, $fuente, $linef);
		// Increment Y so the next line is below the previous line
		$yf += 10;
	}	
	$y2falla = $yf;
	imagerectangle($im, 640, $y1falla, 20, $y2falla, $negro); 
	/*-----------------------------------------------TRABAJO------------------------------------------------------*/
	
	$y1filatrabajo = $yf;		$y2filatrabajo = $y1filatrabajo+20;		$x2filatrabajo = 120; $x1filatrabajo = 20;
	
	
	imagefilledrectangle($im, $x1filatrabajo, $y1filatrabajo, $x2filatrabajo, $y2filatrabajo, $azul);
	imagettftext($im, $size, 0, $x1filatrabajo+5, $y1filatrabajo+14, $blanco, $fuente, $txttrabajo);
	
	$y1trabajo = $y1filatrabajo;//$y1filatrabajo+20;
	//$y2trabajo = $y1trabajo+200;
	
	
	
	// Break it up into pieces 125 characters long
	$linest = explode('|', wordwrap($trabajorealizado, 116, '|')); 
	// Starting Y position
	$yt = $y1trabajo+35;
	// Loop through the lines and place them on the image
	foreach ($linest as $linet)
	{
		imagettftext($im, $size, 0, 25, $yt, $negro, $fuente, $linet);
		// Increment Y so the next line is below the previous line
		$yt += 10;
	}	
	$y2trabajo = $yt;
	imagerectangle($im, 640, $y1trabajo, 20, $y2trabajo, $negro); 
	//imagettftext($im, $size, 0, 160, $y1trabajo+14, $negro, $fuente, $trabajorealizado); 
	
	/*-----------------------------------------------OBSERVACIONES------------------------------------------------------*/
	
	$y1filaobservacion = $y2trabajo;	$y2filaobservacion = $y1filaobservacion+20;		$x2filaobservacion = 110;	$x1filaobservacion = 20;
	
	imagefilledrectangle($im, $x1filaobservacion, $y1filaobservacion, $x2filaobservacion, $y2filaobservacion, $azul);
	imagettftext($im, $size, 0, $x1filaobservacion+5, $y1filaobservacion+14, $blanco, $fuente, $txtobservacion);
	
	$y1observacion = $y1filaobservacion;//$y1filaobservacion+20;
	//$y2observacion = $y1observacion+100;
	
	
	
	
	// Break it up into pieces 125 characters long
	$lineso = explode('|', wordwrap($observaciones, 116, '|')); 
	// Starting Y position
	$yo = $y1observacion+35;
	// Loop through the lines and place them on the image
	foreach ($lineso as $lineo)
	{
		imagettftext($im, $size, 0, 25, $yo, $negro, $fuente, $lineo);
		// Increment Y so the next line is below the previous line
		$yo += 10;
	}
	$y2observacion = $yo;
	imagerectangle($im, 640, $y1observacion, 20, $y2observacion, $negro);
	//imagettftext($im, $size, 0, 160, $y1observacion+14, $negro, $fuente, $observaciones);  
	
	
	/*-----------------------------------------------ESTADO DEL EQUIPO------------------------------------------------------*/
 	if($estadoactivo=='funcional'){
		$chequeadofunc  = "X";
	}else{
		$chequeadofunc  = " ";
	} 
	
	if($estadoactivo=='funcionalparcial'){
		$chequeadofuncp  = "X";
	}else{
		$chequeadofuncp = " ";
	} 
	
	if($estadoactivo=='fueraservicio'){
		$chequeadofserv  = "X"; 
	}else{
		$chequeadofserv = " ";
	} 
	/*$y1filaestado = $y2observacion;	*/		$y2filaestado = $y2observacion+20;
	$y1filaestado = $y2observacion;
	
	imagerectangle($im, 285, $y1filaestado, 20, $y2filaestado, $negro);
	imagettftext($im, $size, 0, 25, $y1filaestado+14, $negro, $fuente, $txtestadoequipo);
	
	//Funcional
	imagerectangle($im, 640, $y1filaestado, 285, $y2filaestado, $negro);
	imagettftext($im, $size, 0, 290, $y1filaestado+14, $negro, $fuente, $txtfuncional);
	
	//Check Funcional
	imagettftext($im, $size, 0, 353, $y1filaestado+15, $negro, $fuente, $chequeadofunc);
	imagerectangle($im, 350, $y1filaestado+5, 360, $y2filaestado-5, $negro);
	
	//Funcional Parcial
	imagettftext($im, $size, 0, 390, $y1filaestado+14, $negro, $fuente, $txtfuncionalp);
	
	//Check Funcional Parcial
	imagettftext($im, $size, 0, 488, $y1filaestado+15, $negro, $fuente, $chequeadofuncp);
	imagerectangle($im, 495, $y1filaestado+5, 485, $y2filaestado-5, $negro);
	
	//Fuera de servicio 
	imagettftext($im, $size, 0, 520, $y1filaestado+14, $negro, $fuente, $txtfueraserv);
	
	//Check Fuera de Servicio
	imagettftext($im, $size, 0, 618, $y1filaestado+15, $negro, $fuente, $chequeadofserv);
	imagerectangle($im, 625, $y1filaestado+5, 615, $y2filaestado-5, $negro); 
	/*--------------------------------------------------------PIE----------------------------------------------------------*/
	
	//Nombre del Técnico
	$y1filanombret = $y2filaestado;		$y2filanombret = $y2filaestado+40;
	$y1filatxtnombretc = $y1filanombret+14;
	$y1nombretecn  = $y1filatxtnombretc+14;
	$x1filanombretecn = 190; 
	
	imagerectangle($im, $x1filanombretecn, $y1filanombret, 20, $y2filanombret, $negro);
	imagettftext($im, $size, 0, 25, $y1filatxtnombretc, $negro, $fuente, $txtnombretec);
	
	imagettftext($im, $size, 0, 25, $y1nombretecn, $negro, $fuente, $nombretecnico); 
	
	//Firma del Técnico 
	$firmat	= $firmatecnico;
	$dataPiecesft = explode(',',$firmat); 
	$encodedImgt = $dataPiecesft[1];
	$nueva = base64_decode($encodedImgt);
	$imt = imagecreatefromstring($nueva);
	$blanco = imagecolorallocate($imt, 0, 0, 0); 
	
	// Obtener los nuevos tamaños
	$porcentajet = 0.6;
	$anchot = 300;
	$altot = 160;
	$nuevo_anchot = $anchot * $porcentajet;
	$nuevo_altot = $altot * $porcentajet; 
	
	// Cargar
	$thumbt = imagecreatetruecolor($nuevo_anchot, $nuevo_altot);
	imagecolortransparent($thumbt, $blanco);
	//$origent = imagecreatefromstring($imt) ;	 
	
	// Cambiar el tamaño
 	imagecopyresized($thumbt, $imt, 0, 0, 0, 0, $nuevo_anchot, $nuevo_altot, $anchot, $altot);
	 
	$y1filafirmat = $y2filanombret;		$y2filafirmat = $y1filafirmat+100; 
	
	// Copiar y fusionar
	imagecopymerge($im, $thumbt, 5, $y1filafirmat, 0, 0, $nuevo_anchot, $nuevo_altot, 100);  
	 
	imagerectangle($im, $x1filanombretecn, $y1filafirmat, 20, $y2filafirmat, $negro);
	imagettftext($im, $size, 0, 25, $y1filafirmat+14, $negro, $fuente, $txtfirmatecnico);  
	 
	//Fecha Firma del Técnico
	$x2fechafirmat = $x1filanombretecn;
	$x1fechafirmat = $x1filanombretecn+95;
	$y2fechafirmat = $y1filanombret;
	$y1fechafirmat = $y2filafirmat; 
	
 	imagerectangle($im, $x1fechafirmat, $y1fechafirmat, $x2fechafirmat, $y2fechafirmat, $negro);
	imagettftext($im, $size, 0, $x2fechafirmat+10, $y2fechafirmat+14, $negro, $fuente, $txtfechafirmat);
	
	if($fechafirmatecnico != ""){
		$datot = explode("-", $fechafirmatecnico); 
		$diat  = $datot[2];	
		$mest  = $datot[1];	
		$aniot = $datot[0];
		$showfechafirmatecnico = $diat."-".$mest."-".$aniot;
	}else{
		$showfechafirmatecnico = "";
	}		
	
	imagettftext($im, $size, 0, $x2fechafirmat+10, $y2fechafirmat+34, $negro, $fuente, $showfechafirmatecnico);  
	 
	//Nombre del Cliente 1
	$y1filanombrec = $y2filaestado;		
	$y2filanombrec = $y2filaestado+40; 
	$x2filanombrec = $x1fechafirmat;
	$y1filatxtnombrec = $y1filanombrec+14;
	$y1nombrecliente  = $y1filatxtnombrec+14;
	$x1filanombrecli  = $x2filanombrec+130;  
	
	imagerectangle($im, $x1filanombrecli, $y1filanombrec, $x2filanombrec, $y2filanombrec, $negro);
	imagettftext($im, $size, 0, $x2filanombrec+10, $y1filatxtnombrec, $negro, $fuente, $txtnombrecli1);
	
	imagettftext($im, $size, 0, $x2filanombrec+10, $y1nombrecliente, $negro, $fuente, $nombrecliente1);  
	
	//Firma del Cliente 1  
	$firmac	= $firmacliente1;
	$dataPiecesfc = explode(',',$firmac);
	$encodedImgc = $dataPiecesfc[1];
	$nuevac1 = base64_decode($encodedImgc);
	$imc1 = imagecreatefromstring($nuevac1);
	//imagecolortransparent($decodedImgc, $blanco); 
	
	// Obtener los nuevos tamaños
	$porcentajec = 0.6;
	$anchoc = 300;
	$altoc = 160;
	$nuevo_anchoc = $anchoc * $porcentajec;
	$nuevo_altoc = $altoc * $porcentajec; 
	// Cargar
	$thumbc = imagecreatetruecolor($nuevo_anchoc, $nuevo_altoc);
	imagecolortransparent($thumbc, $blanco); ;	 
	
	// Cambiar el tamaño
	imagecopyresized($thumbc, $imc1, 0, 0, 0, 0, $nuevo_anchoc, $nuevo_altoc, $anchoc, $altoc); 
	
	$y1filafirmac = $y2filanombrec;		$y2filafirmac = $y1filafirmac+100; 
	
	// Copiar y fusionar
	//imagecopymerge($im, $thumbc, $x2filanombrec+10, $y1filafirmac, 0, 0, $nuevo_anchoc, $nuevo_altoc, 100);
	$x2firmacliente1 = 260;
	imagecopymerge($im, $thumbc, $x2firmacliente1, $y1filafirmac, 0, 0, $nuevo_anchoc, $nuevo_altoc, 100);					
    imagerectangle($im, $x1filanombrecli, $y1filafirmac, $x2filanombrec, $y2filafirmac, $negro);
	imagettftext($im, $size, 0, $x2filanombrec+10, $y1filafirmac+14, $negro, $fuente, $txtfirmacliente1); 
	
	//Nombre del Cliente 2
	$y1filanombrec = $y2filaestado;		
	$y2filanombrec2 = $y2filaestado+40; 
	$x2filanombrec2 = $x1filanombrecli;
	$y1filatxtnombrec = $y1filanombrec+14;
	$y1nombrecliente  = $y1filatxtnombrec+14;

	$x1filanombrecli2  = $x2filanombrec2+130;  
	
	imagerectangle($im, $x1filanombrecli2, $y1filanombrec, $x2filanombrec2, $y2filanombrec2, $negro);
	imagettftext($im, $size, 0, $x2filanombrec2+10, $y1filatxtnombrec, $negro, $fuente, $txtnombrecli2);
	
	imagettftext($im, $size, 0, $x2filanombrec2+10, $y1nombrecliente, $negro, $fuente, $nombrecliente2); 
	
	//Firma del Cliente 2
	$origenc2 = "";
	if($firmacliente2 != ""){
		$firmac2	= $firmacliente2;
		$dataPiecesfc2 = explode(',',$firmac2);
		$encodedImgc2 = $dataPiecesfc2[1];
		$nuevac2 = base64_decode($encodedImgc2);
		$imc2 = imagecreatefromstring($nuevac2);
		// Obtener los nuevos tamaños
		$porcentajec2 = 0.6;
		$anchoc2 = 300;
		$altoc2 = 160;
		$nuevo_anchoc2 = $anchoc2 * $porcentajec2;
		$nuevo_altoc2 = $altoc2 * $porcentajec2;
		// Cargar
		$thumbc2 = imagecreatetruecolor($nuevo_anchoc2, $nuevo_altoc2);
		imagecolortransparent($thumbc2, $blanco);
		
		// Cambiar el tamaño
		imagecopyresized($thumbc2, $imc2, 0, 0, 0, 0, $nuevo_anchoc2, $nuevo_altoc2, $anchoc2, $altoc2);
		// Copiar y fusionar
		imagecopymerge($im, $thumbc2, $x2filanombrec2+5, $y1filafirmac, 0, 0, $nuevo_anchoc2, $nuevo_altoc2, 100); 
	 }  
	
	imagerectangle($im, $x1filanombrecli2, $y1filafirmac, $x2filanombrec2, $y2filafirmac, $negro);
	imagettftext($im, $size, 0, $x2filanombrec2+10, $y1filafirmac+14, $negro, $fuente, $txtfirmacliente2);
	$y1filafirmac = $y2filanombrec2;		$y2filafirmac = $y1filafirmac+40; 
	 
	//Fecha Firma del Cliente 2
	$x2fechafirmac = $x1filanombrecli2;
	$x1fechafirmac = $x2fechafirmac+95;
	$y2fechafirmac = $y1filanombret;
	$y1fechafirmac = $y2filafirmat;
	
 	imagerectangle($im, $x1fechafirmac, $y1fechafirmac, $x2fechafirmac, $y2fechafirmac, $negro);
	imagettftext($im, $size, 0, $x2fechafirmac+10, $y2fechafirmac+14, $negro, $fuente, $txtfechafirmac);
	
	if($fechafirmacliente != ""){
		$datoc = explode("-", $fechafirmacliente); 
		$diac  = $datoc[2];	
		$mesc  = $datoc[1];	
		$anioc = $datoc[0];
		$showfechafirmacliente = $diac."-".$mesc."-".$anioc;
	}else{
		$showfechafirmacliente = "";
	}
	 
	imagettftext($im, $size, 0, $x2fechafirmac+10, $y2fechafirmac+34, $negro, $fuente, $showfechafirmacliente);   
	 
	$queryU = " UPDATE reporteservicios SET estatus = 1 WHERE id = ".$idreporte."";
	$resultU = $mysqli->query($queryU); 
	
	//ENVÍO DE CORREO
	global $mysqli, $mail; 
		
		 if($idincidente != ''){
			//DATOS DEL CORREO
			$usuarioSes = $_SESSION['usuario'];
			$consultaUA = $mysqli->query("SELECT nombre FROM usuarios WHERE usuario = '$usuarioSes' LIMIT 1 ");
			while ($registroUA = $consultaUA->fetch_assoc()) {
				$usuarioAct = $registroUA['nombre'];
			}
			
			//USUARIOS DE SOPORTE
			//$correo [] = 'ana.porras@maxialatam.com';
			//$correo [] = 'isai.carvajal@maxialatam.com';
			//$correo [] = 'fernando.rios@maxialatam.com'; 
		
			$query  = " SELECT a.id, a.titulo, IFNULL(i.correo, a.creadopor) AS creadopor, a.notificar,
						IFNULL(j.correo, a.solicitante) AS solicitante, a.asignadoa
						FROM incidentes a
						LEFT JOIN usuarios i ON a.creadopor = i.correo
						LEFT JOIN usuarios j ON a.solicitante = j.correo
						WHERE a.id = $idincidente ";
			$result = $mysqli->query($query);
			$row 	= $result->fetch_assoc();
			
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
					$query2 .= "correo = '".$row['asignadoa']."'";
				}else{
					$query2 .= "correo IN ('".$row['asignadoa']."') ";
				}
				$consulta = $mysqli->query($query2);
				while($rec = $consulta->fetch_assoc()){
					$asignadoaN .= $rec['nombre']." , ";
				}			
			}  
			$cuerpo = "";
			$cuerpo .= "<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; '>";
			$cuerpo .= "<img src='http://toolkit.maxialatam.com/repositorio-tema/assets/img/maxia.jpg' style='width: initial;height: 60px;float: left; position: absolute !important;'>";
			$cuerpo .= "<p style='margin:auto; font-weight:bold; width: 100%; text-align: center;'>Maxia Toolkit<br>";
			$cuerpo .= "Gestión de Soporte<br>";
			$cuerpo .= "</p></div>";
			$cuerpo .= "<div style='padding: 30px;font-family: arial,sans-serif;'>
							<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha adjuntado nuevo documento al correctivo #".$idincidente."</b></p>";
			$cuerpo .= "	<p style='width:100%;'>
								<a href='http://toolkit.maxialatam.com/soporte/incidentes.php?id=".$idincidente."' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Correctivo</a></p>
							</p>
						</div>
						";
			$cuerpo .= "<div style='background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;'>";
			$cuerpo .= "© ".date('Y')." Maxia Latam";
			$cuerpo .= "</div>";	
			
			$correo = array_unique($correo);  
			
			//echo $correo;
			
			foreach($correo as $destino){
				if( $destino != 'mesadeayuda@innovacion.gob.pa' ){
					$mail->addAddress($destino);
				}			   
			} 
			//debugL('lisbethagapornis@gmail.com');
			//$mail->addAddress('lisbethagapornis@gmail.com');
			$mail->FromName = "Maxia Toolkit - SYM";
			$mail->isHTML(true); // Set email format to HTML
			if($row['solicitante'] == 'mesadeayuda@innovacion.gob.pa' || $row['creadopor'] == 'mesadeayuda@innovacion.gob.pa'){
				$mail->Subject = $row['titulo'];
			}else{
				$mail->Subject = "Correctivo #".$idincidente." - Nuevo adjunto";
			} 
			
			$mail->MsgHTML($cuerpo);
			$mail->Body = $cuerpo;
			$mail->AltBody = "Maxia Toolkit - Soporte: $asunto";
			
		//	if(!$mail->send()) { 
				//echo 'Mensaje no pudo ser enviado. ';
				//echo 'Mailer Error: ' . $mail->ErrorInfo;
		//	} else {
				//echo 'Ha sido enviado el correo Exitosamente';
				//echo true;
		//	}
			
			//echo true;
		}
	//FIN ENVÍO DE CORREO
	// Usar imagepng() resultará en un texto más claro comparado con imagejpeg()
	imagepng($im);
	imagepng($im, "../incidentes/".$idincidente."/Reporte ".$codigoreporte.".jpg"); 
	
	$queryRs = " UPDATE incidentes SET reporteservicio = '".$codigoreporte."' WHERE id = ".$idincidente."";
	$resultRs = $mysqli->query($queryRs);
	
	imagedestroy($im);
	//imagedestroy($origen); 
?>