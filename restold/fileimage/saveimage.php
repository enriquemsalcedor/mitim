 
<?php
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
  header('content-type: application/json; charset=utf-8');
  header('Content-Type: application/JSON');
  header('Content-Type: text/plain; charset=utf-8');


 
$imagenCodificada = file_get_contents("php://input"); //Obtener la imagen
if(strlen($imagenCodificada) <= 0) exit("No se recibió ninguna imagen");
//La imagen traerá al inicio data:image/png;base64, cosa que debemos remover
$imagenCodificadaLimpia = str_replace("data:image/png;base64,", "", urldecode($imagenCodificada));

echo $imagenCodificadaLimpia;
//Venía en base64 pero sólo la codificamos así para que viajara por la red, ahora la decodificamos y
//todo el contenido lo guardamos en un archivo

$imagenDecodificada = base64_decode($imagenCodificadaLimpia);
 
//Calcular un nombre único
$nombreImagenGuardada = "foto_" . uniqid() . ".png";
 
//Escribir el archivo
file_put_contents($nombreImagenGuardada, $imagenDecodificada);
 
//Terminar y regresar el nombre de la foto
exit($nombreImagenGuardada);


$rutaImagenSalida = __DIR__ . "/salida.png";
$imagenBinaria = base64_decode($imagenEnBase64);
$bytes = file_put_contents($rutaImagenSalida, $imagenBinaria);
echo "$bytes bytes fueron escritos en $rutaImagenSalida";


?>