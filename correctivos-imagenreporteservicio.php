<?php
	//Medidas
	$width = 664;
	$height = 1024;
	
	// Establecer el tipo de contenido
	header("Content-Type: image/png");
	
	// Crear la imagen
	//$im = @imagecreate($width, $height) or die("Cannot Initialize new GD image stream");
	$im = imagecreatefrompng("images/base.png");
	//$im = imagecreatetruecolor($width, $height);

	// Crear algunos colores
	$blanco = imagecolorallocate($im, 255, 255, 255);
	$gris = imagecolorallocate($im, 128, 128, 128);
	$negro = imagecolorallocate($im, 0, 0, 0);
	// Establecer el fondo a blanco
	//imagefilledrectangle($im, 0, 0, 299, 299, $blanco);
	
	$dataURI	= "data:image/png;base64,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";
	$dataPieces = explode(',',$dataURI);
	$encodedImg = $dataPieces[1];
	$decodedImg = base64_decode($encodedImg);
	$white = imagecolorallocate($origen, 255, 255, 255);
	imagecolortransparent($decodedImg, $white);
	
	// Crear instancias de imágenes
	//$destino = imagecreatefrompng('images/base.png');
	//$origen = imagecreatefrompng('images/fragmento.png');
	//$origen = imagecreatefromstring($decodedImg);	
	
	// Obtener los nuevos tamaños
	$porcentaje = 0.6;
	$ancho = 300;
	$alto = 160;
	$nuevo_ancho = $ancho * $porcentaje;
	$nuevo_alto = $alto * $porcentaje;
	// Cargar
	$thumb = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
	imagecolortransparent($thumb, $white);
	$origen = imagecreatefromstring($decodedImg);	
	
	// Cambiar el tamaño
	imagecopyresized($thumb, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);

	// Copiar y fusionar
	imagecopymerge($im, $thumb, 5, 200, 0, 0, $nuevo_ancho, $nuevo_alto, 100); //destino, origen, x destino, y destino, x origen, y origen, ancho, alto, opacidad

	// El texto a dibujar
	$reporte = 'Reporte de solicitud de servicio';
	$hola = 'Formulario de Correctivo';
	// Reemplace la ruta por la de su propia fuente
	$fuente = 'fonts/open/OpenSans-Regular.ttf';
	$text_color = imagecolorallocate($im, 0, 0, 0);
	
	// Primero creamos nuestra caja circundante para nuestro primer texto
	//$bbox = imagettfbbox(20, 0, $fuente,$texto);
	//$x = $bbox[0] + (imagesx($im) / 2) - ($bbox[4] / 2) - 25;
	//$y = $bbox[1] + (imagesy($im) / 2) - ($bbox[5] / 2) - 5;
	
	// Añadir algo de sombra al texto
	//imagettftext($im, 20, 0, 11, 26, $gris, $fuente, $texto);
	//imagettftext($im, 20, 0, $x, $y, $gris, $fuente, $texto);
	
	//Crear rectangulo
	//imagerectangle($im, 300, 120, 140, 160, $negro); //imagen, x1(sup izq), y1(sup izq ), x2(sup der), y2(inf der), color
	//imagettftext($im, 20, 0, 150, 150, $gris, $fuente, $texto); //imagen, size, angulo, x, y, color, font, text
	
	// Añadir el texto
	imagettftext($im, 15, 0, 10, 95, $negro, $fuente, $reporte); //imagen, size, angulo, x, y, color, font, text
	
	$txtsitio 		  = "Sitio:";
	$txtdepto		  = "Departamento:";
	$txtequipo 		  = "Equipo:";
	$txtubicacion 	  = "Ubicación del Equipo:";
	$txtticket 		  = "Ticket N°.:";
	$txtmarca 		  = "Marca:";
	$txtmodelo 		  = "Modelo:";
	$txtserie 		  = "Número de Serie:";
	$txtfechacreacion = "Fecha de Solicitud:";
	$txttiposervicio  = "Tipo de Servicio ";
	$txttipocorr 	  = "Correctivo";
	$txttipoprev      = "Preventivo";
	$txttipoeval      = "Evaluación";
	$txthoraslab      = "Horas Laborales";
	$txtfecha         = "Fecha";
	$txtdia           = "Día";
	$txtmes		      = "Mes";
	$txtanio          = "Año";
	$txttiempoviaje   = "Tiempo de Viaje";
	$txttiempolabor   = "Tiempo de Labor";
	$txttiempoespera  = "Tiempo de Espera";
	$txthorainicio    = "Hora de Inicio";
	$txthorafin       = "Hora Fin";
	$txtrepuestos     = "Repuestos";
	$txtcodigorep     = "Código de Repuesto";
	$txtcantidadrep   = "Cantidad";
	$txtdescrep       = "Descripción";
	$txtfalla         = "Falla o Error Reportado";
	$txttrabajo       = "Trabajo Realizado";
	$txtobservacion   = "Observaciones";
	$txtestadoequipo  = "Estado del Equipo al Finalizar Servicio";
	$txtfuncional     = "Funcional";
	$txtfuncionalp    = "Funcional Parcial";
	$txtfueraserv     = "Fuera de Servicio";
	$txtnombretec     = "Nombre de Técnico";
	$txtfechafirmat   = "Fecha";
	$txtfirmatecnico  = "Firma Técnico";
	$txtevaluacion    = "Evaluación";
	$txtnombrecli     = "Nombre de Cliente";
	$txtfirmacliente1 = "Firma Cliente";
	$txtnombrecliente = "Nombre de Cliente";
	$txtfirmacliente2 = "Firma de Cliente";
	$txtfechafirmac   = "Fecha";
/* 	
	300 = 300;
	40 = 40;
	70 = 70;
	60 = 60;*/
	$size = 10;
	
	//imagettftext($im, 20, 0, 80, 80, $gris, $fuente, $texto); //imagen, size, angulo, x, y, color, font, text
	
	imagerectangle($im, 300, 40, 70, 60, $negro);//X1,Y1,X2,Y2
	imagettftext($im, $size, 0, 80, 55, $negro, $fuente, $txtsitio); //imagen, size, angulo, x, y, color, font, text
	
	imagerectangle($im, 300, 40, 600, 60, $negro);
	imagettftext($im, $size, 0, 310, 55, $negro, $fuente, $txtdepto);
	/*---*/
	imagerectangle($im, 230, 60, 70, 80, $negro);
	imagettftext($im, $size, 0, 80, 70, $negro, $fuente, $txtequipo);
	
	imagerectangle($im, 230,  60, 370, 80, $negro);
 	imagettftext($im, $size, 0, 240, 70, $negro, $fuente, $txtubicacion);
	
	imagerectangle($im, 370, 60, 600, 80, $negro);
	imagettftext($im, $size, 0, 380, 70, $negro, $fuente, $txtticket); 
	
	imagerectangle($im, 200, 80, 70, 100, $negro);
	imagettftext($im, $size, 0, 80, 90, $negro, $fuente, $txtmarca); 
	
	imagerectangle($im, 400, 80, 200, 100, $negro);
	imagettftext($im, $size, 0, 210, 90, $negro, $fuente, $txtmodelo);
	
	imagerectangle($im, 600, 80, 400, 100, $negro);
	imagettftext($im, $size, 0, 410, 90, $negro, $fuente, $txtserie); 
	
	imagerectangle($im, 200, 100, 70, 120, $negro);
	imagettftext($im, $size, 0, 80, 110, $negro, $fuente, $txttiposervicio);
	
	imagerectangle($im, 300, 100, 200, 120, $negro);
	imagettftext($im, $size, 0, 210, 110, $negro, $fuente, $txttipocorr);
	
	imagerectangle($im, 400, 100, 300, 120, $negro);
	imagettftext($im, $size, 0, 310, 110, $negro, $fuente, $txttipoprev);
	
	imagerectangle($im, 500, 100, 400, 120, $negro);
	imagettftext($im, $size, 0, 410, 110, $negro, $fuente, $txttipoeval);
	
	imagerectangle($im, 600, 80, 500, 100, $negro);
	imagettftext($im, $size, 0, 510, 110, $negro, $fuente, $txthoraslab); 
	
	// Usar imagepng() resultará en un texto más claro comparado con imagejpeg()
	imagepng($im);
	imagepng($im, 'images/222.jpg');
	imagedestroy($im);
	imagedestroy($origen);
?>