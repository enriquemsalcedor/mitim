<?php
// Crear una imagen de 200 x 200
$lienzo = imagecreatetruecolor(200, 200);

// Asignar colores
$rosa = imagecolorallocate($lienzo, 255, 105, 180);
$blanco = imagecolorallocate($lienzo, 255, 255, 255);
$verde = imagecolorallocate($lienzo, 132, 135, 28);

// Dibujar tres rectángulos, cada uno con su color
imagerectangle($lienzo, 50, 50, 150, 150, $rosa);
imagerectangle($lienzo, 45, 60, 120, 100, $blanco);
imagerectangle($lienzo, 100, 120, 75, 160, $verde);

// Imprimir y liberar memoria
header('Content-Type: image/jpeg');

imagejpeg($lienzo);
imagedestroy($lienzo);
?>