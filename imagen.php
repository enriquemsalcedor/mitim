<?php
	//<img src="create_image.php?s=008080_F_1000_200&t=Sample%20Image%20Drawn%20By%20PHP" alt="GD Library Example Image" >
	//https://toolkit.maxialatam.com/soporte/imagen.php?s=008080_F_1000_200&t=EJEMPLO
	$setting = isset($_GET['s']) ? $_GET['s'] : "FFF_111_1000_600";
	$setting = explode("_",$setting );
	$img = array();

	switch ($n = count($setting)) {
		case $n > 4 :
		case 3:
			$setting[3] = $setting[2];
		case 4:
			$img['width'] = (int) $setting[2];
			$img['height'] = (int) $setting[3];
		case 2:
			$img['color'] = $setting[1];
			$img['background'] = $setting[0];
			break;
		default:
			list($img['background'],$img['color'],$img['width'],$img['height']) = array('F','0',1000,600);
			break;
	}

	$background = explode(",",hex2rgb($img['background']));
	$color = explode(",",hex2rgb($img['color']));
	$width = empty($img['width']) ? 1000 : $img['width'];
	$height = empty($img['height']) ? 600 : $img['height'];
	$string = (string) isset($_GET['t']) ? $_GET['t'] : $width ."x". $height;
	$hola = 'Hola Lisbeth esta es una prueba';

	header("Content-Type: image/png");
	$image = @imagecreate($width, $height)
    or die("Cannot Initialize new GD image stream");

	$background_color = imagecolorallocate($image, $background[0], $background[1], $background[2]);
	$text_color = imagecolorallocate($image, $color[0], $color[1], $color[2]);
	$fuente = imageloadfont('./Hollow_8x16_LE.gdf');

	imagestring($image, 5, 5, 5, $string, $text_color);
	imagestring($image, 5, 20, 20, $hola, $text_color); //image, font, x, y, color
	imagepng($image);
	imagepng($image, 'images/111.png');
	imagedestroy($image);

	function hex2rgb($hex) {
		// Copied
	   $hex = str_replace("#", "", $hex);

	   switch (strlen($hex)) {
		   case 1:
			   $hex = $hex.$hex;
		   case 2:
			  $r = hexdec($hex);
			  $g = hexdec($hex);
			  $b = hexdec($hex);
			   break;
		   case 3:
			  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
			  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
			  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
			   break;
		   default:
			  $r = hexdec(substr($hex,0,2));
			  $g = hexdec(substr($hex,2,2));
			  $b = hexdec(substr($hex,4,2));
			  break;
	   }

	   $rgb = array($r, $g, $b);
	   return implode(",", $rgb);
	}
?>