<?php
	// Variables de configuracion
	$video = 'ayudavideos/MaestroSitios.mp4';//Url del video	
	$w = 200;//Ancho de las capturas finales
	$h = 112;//Alto de las capturas finales
	$nc = 10;//Numero de miniaturas
	/*
    //Utilizando ffprobe para extraer la informacion del video en formato json
	exec('ffprobe -i '.$video.' -v quiet -print_format json -show_format -show_streams -hide_banner', $out, $res);
	
	//Veficar si ocurrio un error
	if($res==1){ echo 'Error al leer informacion del video'; exit;}
	
	//Decodificando la informacion en json
	$info = json_decode(implode($out));
	
	//Datos a extraer
	$time = $info->format->duration;//Duracion total en segundos
	$vw = $info->streams[0]->width;//Ancho del video
	$vh = $info->streams[0]->height;//Alto del video
	
	//Calculando el intervalo de captura
	$intervalo = ($time/($nc-1));
	
	//Capturando los fotogramas segun el intervalo calculado
	//Las capturas se guardaran en la carpeta "tem" en formato .jpg
	exec('ffmpeg -i '.$video.' -vf fps=1/'.$intervalo.' tem/i%d.jpg', $out, $res);
	
	//Veficar si ocurrio un error
	if($res==1){ echo 'Error al realizar capturas del video'; exit;}
	
	//Creando la miniatura con el tamaño total
	$miniatura = @imagecreatetruecolor($w*$nc, $h);
	
	//Imagen temporal para ajustar las capturas al tamaño indicado
	$tem = @imagecreatetruecolor($w, $h);

	for($i=1; $i<=$nc; $i++){
		
		//Cargando la captura No. $i
		$src=imagecreatefromjpeg('tem/i'.$i.'.jpg');
		
		//Calculando la proporcion para escalar las capturas
		if($vw>$vh)		
			$p=$w/$vw;//Para videos horizontales
		else
			$p=$h/$vh;//Para videos verticales		
		
		//Obteniendo nuevo ancho y alto
		$nw=round($vw*$p); 
		$nh=round($vh*$p);
		
		//Calculando la posicion para ajustar
		$x=round(($w/2)-($nw/2));
		$y=round(($h/2)-($nh/2));
		
		//Copiando y ajustando la captura a la imagen temporal
		imagecopyresampled($tem, $src, $x, $y, 0, 0, $nw, $nh, $vw, $vh);
		
		//Calculando la posicion en miniatura
		$px = $w*($i-1);
				
		//Copiando la captura ajustada a la miniatura final
		imagecopy($miniatura , $tem, $px, 0, 0, 0, $w, $h);		
	}
		
	//Guardando miniatura en formato .jpg con 80% de calidad
	imagejpeg($miniatura, 'preview.jpg', 80);
	
	imagedestroy($tem);
	imagedestroy($src);	
	imagedestroy($miniatura);*/
?>