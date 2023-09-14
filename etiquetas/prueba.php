<?php

// Incluimos las bibliotecas necesarias

//require('qrlib.php'); 
require("../conexion.php");
require dirname(__FILE__) . '/../../repositorio-lib/fpdf/fpdf.php';

// Creamos una instancia de la clase FPDF
$pdf = new FPDF('P','cm','A4');
/*
// Conectamos a la base de datos MySQL y recuperamos la información del equipo
// que queremos imprimir en la etiqueta
//$mysqli = new mysqli('host', 'usuario', 'contraseña', 'base_de_datos');
$query = "	SELECT a.nombre AS activo, b.nombre AS modelo, a.serie, c.nombre AS area 
			FROM activos a 
			LEFT JOIN modelos b ON b.id = a.idmodelos 
			LEFT JOIN subambientes c ON c.id = a.idsubambientes
			WHERE a.id IN (1,2) ";
$result = $mysqli->query($query);

// Agregamos una nueva página al documento PDF
$pdf->AddPage();

while($equipo = $result->fetch_assoc()){
// Generamos el código QR utilizando la información del equipo
//$codigo_qr = QRcode::png($equipo['codigo_qr']);

// Imprimimos el nombre del equipo y su código QR en la etiqueta
$pdf->SetFont('Arial', 'B', 16);
$pdf->setX(0);
$pdf->Cell(237.73, 28.35, strtoupper(utf8_decode($equipo['activo'])),1,1,'C');
$pdf->SetFont('Arial', 'B', 14);
$pdf->setX(0);
$pdf->Cell(80, 10, utf8_decode('MODELO: '.$equipo['modelo']),1,1);
$pdf->setX(0);
$pdf->Cell(80, 10, utf8_decode('SERIE: '.$equipo['serie']),1,1);
$pdf->setX(0);
$pdf->Cell(80, 10, utf8_decode('UBICACIÓN: '.$equipo['area']),1,1);
$pdf->setX(0);
$pdf->Cell(80, 10, utf8_decode('REPORTES'),1,1);
$pdf->setX(0);
$pdf->Cell(80, 10, utf8_decode('ESCANEAR CÓDIGO QR'),1,1);
$pdf->setX(0);
$pdf->Cell(80, 10, utf8_decode('ENVIAR CORREO A:'),1,1);
$pdf->setX(0);
$pdf->Cell(80, 10, utf8_decode('soporte@maxialatam.com'),1,1);
$pdf->setX(0);
$pdf->Cell(80, 10, utf8_decode('LLAMAR AL: 302-0112'),1,1);
//$pdf->Image($codigo_qr, 10, 10, 30, 30);

}
*/
// Agregamos la primera página y establecemos sus dimensiones
// Establecemos los márgenes y deshabilitamos el salto de página automático
$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(false);

// Agregamos la primera página
$pdf->AddPage();

// Establecemos el color y grosor del borde
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(224, 224, 224);
$pdf->SetLineWidth(0.05);

// Agregamos las etiquetas a la primera página
$pdf->Cell(8.4, 10.1, 'Este es el texto de mi celda', 1, 0, 'C');


$pdf->Output('I',"prueba.pdf");

?>