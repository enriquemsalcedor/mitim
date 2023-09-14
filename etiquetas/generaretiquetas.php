







<?php

// Incluimos las bibliotecas necesarias

//require('../librerias/phpqrcode/qrlib.php'); 
require('../conexion.php');
require dirname(__FILE__) . '/../../repositorio-lib/fpdf/fpdf.php';

/* function QR($idactivo){
	$directorio = "../activos/".$idactivo."/qr/";   
	$codigo = $idactivo.'qr.png';  
	QRcode::png($idactivo,$directorio.$codigo,"H",6); 
}

function createFolder($directorio){  
	if(file_exists($directorio)){
		return true;
	}else{
		$target_path2 = utf8_decode($directorio);
		if (!file_exists($target_path2))
		mkdir($target_path2, 0777);
		return true;
	}
}  */

// Creamos una instancia de la clase FPDF
$pdf = new FPDF('L','cm','A4');

// Conectamos a la base de datos MySQL y recuperamos la información del equipo
// que queremos imprimir en la etiqueta
//$mysqli = new mysqli('host', 'usuario', 'contraseña', 'base_de_datos');
$query = "	SELECT a.id AS idactivo, a.nombre AS activo, b.nombre AS modelo, a.serie, c.nombre AS area 
			FROM activos a 
			LEFT JOIN modelos b ON b.id = a.idmodelos 
			LEFT JOIN subambientes c ON c.id = a.idsubambientes
			WHERE a.idclientes = 38 AND a.idproyectos = 68 ORDER BY a.id ";
$result = $mysqli->query($query);

// Agregamos una nueva página al documento PDF

$pdf->AddPage();
#Establecemos el margen inferior:
$pdf->SetAutoPageBreak(true,0.5); 
$posicion = 0;
$total = $result->num_rows;
while($equipo = $result->fetch_assoc()){
	
	$idactivo = $equipo["idactivo"];
	/* $dir_activos = '../activos/'.$idactivo.'/';
	$dirqr_activos = '../activos/'.$idactivo.'/qr/';

	if(createFolder($dir_activos)){
		if(createFolder($dirqr_activos)){
			QR($idactivo);
		}						
	} */ 
	
	 switch($posicion)
        {
            case 0:
				$pdf->setXY(1,0);
				$pdf->Line(1,0,9.4,0); //Arriba 
				$pdf->Line(1,0,1,7); //Izquierda
				$pdf->Line(9.4,0,9.4,7); //Derecha
				$pdf->Line(1,7,9.4,7); //Abajo 
				$pdf->Image("https://toolkit.maxialatam.com/soporte/images/encabezado-maxia-c.png", 1.5, 0.2, 2, 0.8, "png");
				$pdf->Image("https://toolkit.maxialatam.com/soporte/activos/".$idactivo."/qr/".$idactivo."qr.png", 3.5, 0.8, 3.1, 3.1, "png"); 
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->setXY(1,4);
				//$pdf->Cell(1,1,'',0,0,0); 
				$pdf->SetTextColor(0,0,0);
				$pdf->MultiCell(8.4, 0.4,strtoupper(utf8_decode($equipo['activo'])),0,'C');
				$pdf->SetTextColor(92,92,92);
				$pdf->Cell(8.4, 0.3,'',0,1); 	  
				//$pdf->Cell(1,0.5,'',0,0,0);
				$pdf->setX(1);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(1.4, 0.5, utf8_decode('MODELO: '),0,0);
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->Cell(6.4, 0.5, utf8_decode($equipo['modelo']),0,1); 	  
				//$pdf->Cell(1,0.5,'',0,0,0);
				$pdf->SetFont('Arial', 'B', 8);
				$y = $pdf->getY();
				$pdf->MultiCell(1.4, 0.4,utf8_decode('SERIE: '),0,'L');
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->setXY(2.4,$y);
				$pdf->MultiCell(7, 0.4,utf8_decode($equipo['serie']),0,1);
				//$pdf->Cell(1,0.5,'',0,0,0);
				$pdf->SetFont('Arial', 'B', 8);
				$y = $pdf->getY();
				$pdf->MultiCell(2, 0.4,utf8_decode('UBICACIÓN: '),0,0);
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->setXY(2.8,$y);
				$pdf->MultiCell(6.4, 0.4,utf8_decode($equipo['area']),0,1);
				/* $pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(8.4, 0.5,'',0,1,1);											  
				$pdf->Cell(8.4, 0.5, utf8_decode('REPORTES:'),0,1,1);					  
				$pdf->Cell(8.4, 0.5, utf8_decode('ESCANEAR CÓDIGO QR'),0,1,1);			  
				$pdf->Cell(8.4, 0.5, utf8_decode('ENVIAR CORREO A:'),0,1,1);			 
				$pdf->Cell(8.4, 0.5, utf8_decode('soporte@maxialatam.com'),0,1,1);		  
				$pdf->Cell(8.4, 0.5, utf8_decode('LLAMAR AL: 302-0112'),0,1,1); */			  
                $posicion++; //Aumenta posición
            break;
 
            case 1:
			
                $pdf->setXY(11,0);
				$pdf->Line(11,0,19.4,0); //Arriba 
				$pdf->Line(11,0,11,7); //Izquierda
				$pdf->Line(19.4,0,19.4,7); //Derecha
				$pdf->Line(11,7,19.4,7); //Abajo 
				$pdf->Image("https://toolkit.maxialatam.com/soporte/images/encabezado-maxia-c.png", 11.5, 0.2, 2, 0.8, "png");
				$pdf->Image("https://toolkit.maxialatam.com/soporte/activos/".$idactivo."/qr/".$idactivo."qr.png", 13.5, 0.8, 3.1, 3.1, "png");
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->setXY(11,4);
				//$pdf->Cell(1,1,'',0,0,0);
				$pdf->SetTextColor(0,0,0);
				$pdf->MultiCell(8.4, 0.4,strtoupper(utf8_decode($equipo['activo'])),0,'C');
				$pdf->Cell(8.4, 0.3,'',0,1);
				$pdf->SetTextColor(92,92,92);
				$pdf->setX(11);
				//$pdf->Cell(1,0.5,'',0,0,0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(1.4, 0.4, utf8_decode('MODELO: '),0,0);
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->Cell(7, 0.4, utf8_decode($equipo['modelo']),0,1); 	
				$pdf->setX(11);
				//$pdf->Cell(1,0.5,'',0,0,0);
				$pdf->SetFont('Arial', 'B', 8);
				$y = $pdf->getY();
				$pdf->MultiCell(1.4, 0.4,utf8_decode('SERIE: '),0,'L');
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->setXY(12.4,$y);
				$pdf->MultiCell(7, 0.4,utf8_decode($equipo['serie']),0,1);
				$pdf->setX(11);
				//$pdf->Cell(1,0.5,'',0,0,0);
				$pdf->SetFont('Arial', 'B', 8);
				$y = $pdf->getY();
				$pdf->MultiCell(2, 0.4,utf8_decode('UBICACIÓN: '),0,0);
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->setXY(12.8,$y);
				$pdf->MultiCell(6.4, 0.5,utf8_decode($equipo['area']),0,1);	
				/* $pdf->setX(11);
				$pdf->Cell(8.4, 0.5,'',0,1,1);											  //0.5CM
				$pdf->setX(11);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(8.4, 0.5, utf8_decode('REPORTES:'),0,1,1);					  //0.5CM
				$pdf->setX(11);
				$pdf->Cell(8.4, 0.5, utf8_decode('ESCANEAR CÓDIGO QR'),0,1,1);			  //0.5CM
				$pdf->setX(11);
				$pdf->Cell(8.4, 0.5, utf8_decode('ENVIAR CORREO A:'),0,1,1);			  //0.5CM
				$pdf->setX(11);
				$pdf->Cell(8.4, 0.5, utf8_decode('soporte@maxialatam.com'),0,1,1);		  //0.5CM
				$pdf->setX(11);
				$pdf->Cell(8.4, 0.5, utf8_decode('LLAMAR AL: 302-0112'),0,1,1);			  //0.5CM */
				$posicion++; //Aumenta posición
			break;
			
            case 2:
                $pdf->setXY(1,0);
				$pdf->Line(21,0,29.4,0); //Arriba 
				$pdf->Line(21,0,21,7); //Izquierda
				$pdf->Line(29.4,0,29.4,7); //Derecha
				$pdf->Line(21,7,29.4,7); //Abajo 
				$pdf->Image("https://toolkit.maxialatam.com/soporte/images/encabezado-maxia-c.png", 21.5, 0.2, 2, 0.8, "png");
				$pdf->Image("https://toolkit.maxialatam.com/soporte/activos/".$idactivo."/qr/".$idactivo."qr.png", 23.5, 0.8, 3.1, 3.1, "png");
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->setXY(21,4);
				//$pdf->Cell(1,1,'',0,0,0);
				$pdf->SetTextColor(0,0,0);
				$pdf->MultiCell(8.4, 0.4,strtoupper(utf8_decode($equipo['activo'])),0,'C');
				$pdf->Cell(8.4, 0.3,'',0,1);
				$pdf->SetTextColor(92,92,92);
				$pdf->setX(21);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(1.4, 0.4, utf8_decode('MODELO: '),0,0);
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->Cell(7, 0.4, utf8_decode($equipo['modelo']),0,1);
				$pdf->setX(21);
				$pdf->SetFont('Arial', 'B', 8);
				$y = $pdf->getY();
				$pdf->MultiCell(1.4, 0.4,utf8_decode('SERIE: '),0,'L');
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->setXY(22.4,$y);
				$pdf->MultiCell(7, 0.4,utf8_decode($equipo['serie']),0,1);
				$pdf->setX(21);
				$pdf->SetFont('Arial', 'B', 8);
				$y = $pdf->getY();
				$pdf->MultiCell(2, 0.4,utf8_decode('UBICACIÓN: '),0,0);
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->setXY(22.8,$y);
				$pdf->MultiCell(6.4, 0.4,utf8_decode($equipo['area']),0,1);
				/* $pdf->setX(21);
				$pdf->Cell(8.4, 0.5,'',0,1,1);											  //0.5CM
				$pdf->setX(21);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(8.4, 0.5, utf8_decode('REPORTES:'),0,1,1);					  //0.5CM
				$pdf->setX(21);
				$pdf->Cell(8.4, 0.5, utf8_decode('ESCANEAR CÓDIGO QR'),0,1,1);			  //0.5CM
				$pdf->setX(21);
				$pdf->Cell(8.4, 0.5, utf8_decode('ENVIAR CORREO A:'),0,1,1);			  //0.5CM
				$pdf->setX(21);
				$pdf->Cell(8.4, 0.5, utf8_decode('soporte@maxialatam.com'),0,1,1);		  //0.5CM
				$pdf->setX(21);
				$pdf->Cell(8.4, 0.5, utf8_decode('LLAMAR AL: 302-0112'),0,1,1);			  //0.5CM */
				$posicion++; //Aumenta posición
			break;
			
			 case 3:
				$pdf->setXY(1,10.5);
				$pdf->Line(1,10.5,9.4,10.5); //Arriba 
				$pdf->Line(1,10.5,1,17.5); //Izquierda
				$pdf->Line(9.4,10.5,9.4,17.5); //Derecha
				$pdf->Line(1,17.5,9.4,17.5); //Abajo 
				$pdf->Image("https://toolkit.maxialatam.com/soporte/images/encabezado-maxia-c.png", 1.5, 10.7, 2, 0.8, "png");
				$pdf->Image("https://toolkit.maxialatam.com/soporte/activos/".$idactivo."/qr/".$idactivo."qr.png", 3.5, 11.3, 3.1, 3.1, "png");
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->setY(14.5);
				//$pdf->Cell(1,1,'',0,0,0);
				$pdf->SetTextColor(0,0,0);
				$pdf->MultiCell(8.4, 0.4,strtoupper(utf8_decode($equipo['activo'])),0,'C');
				$pdf->Cell(8.4, 0.3,'',0,1);
				$pdf->SetTextColor(92,92,92);
				//$pdf->Cell(1,0.5,'',0,0,0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(1.4, 0.4, utf8_decode('MODELO: '),0,0);
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->Cell(7, 0.4, utf8_decode($equipo['modelo']),0,1);
				//$pdf->Cell(1,0.5,'',0,0,0);
				$pdf->SetFont('Arial', 'B', 8);
				$y = $pdf->getY();
				$pdf->MultiCell(1.4, 0.4,utf8_decode('SERIE: '),0,'L');
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->setXY(2.4,$y);
				$pdf->MultiCell(7, 0.4,utf8_decode($equipo['serie']),0,1);
				//$pdf->Cell(1,0.5,'',0,0,0);
				$pdf->SetFont('Arial', 'B', 8);
				$y = $pdf->getY();
				$pdf->MultiCell(2, 0.4,utf8_decode('UBICACIÓN: '),0,0);
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->setXY(2.8,$y);
				$pdf->MultiCell(6.4, 0.4,utf8_decode($equipo['area']),0,1);
				/* $pdf->Cell(8.4, 0.3,'',0,1,1);	
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(8.4, 0.5, utf8_decode('REPORTES:'),0,1);					  //0.5CM
				$pdf->Cell(8.4, 0.5, utf8_decode('ESCANEAR CÓDIGO QR'),0,1);				  //0.5CM
				$pdf->Cell(8.4, 0.5, utf8_decode('ENVIAR CORREO A:'),0,1);				  //0.5CM
				//$pdf->setY(18.4);
				$pdf->Cell(8.4, 0.5, utf8_decode('soporte@maxialatam.com'),0,1);		  //0.5CM
				$pdf->Cell(8.4, 0.5, utf8_decode('LLAMAR AL: 302-0112'),0,1);				  //0.5CM */
                $posicion++; //Aumenta posición
            break;

			case 4: 
				$pdf->setXY(11,10.5);
				$pdf->Line(11,10.5,19.4,10.5); //Arriba 
				 $pdf->Line(11,10.5,11,17.5); //Izquierda
				$pdf->Line(19.4,10.5,19.4,17.5); //Derecha
				$pdf->Line(11,17.5,19.4,17.5); //Abajo
				
				$pdf->Image("https://toolkit.maxialatam.com/soporte/images/encabezado-maxia-c.png", 11.5, 10.7, 2, 0.8, "png");
				$pdf->Image("https://toolkit.maxialatam.com/soporte/activos/".$idactivo."/qr/".$idactivo."qr.png", 13.5, 11.3, 3.1, 3.1, "png");
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->setXY(11,14.5);
				//$pdf->Cell(1,1,'',0,0,0);
				$pdf->SetTextColor(0,0,0);
				$pdf->MultiCell(8.4, 0.4,strtoupper(utf8_decode($equipo['activo'])),0,'C');
				$pdf->Cell(8.4, 0.3,'',0,1);				
				$pdf->SetTextColor(92,92,92);
				$pdf->setX(11);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(1.4, 0.4, utf8_decode('MODELO: '),0,0);
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->Cell(7, 0.4, utf8_decode($equipo['modelo']),0,1);
				//$pdf->Cell(1,0.5,'',0,0,0);
				$pdf->SetFont('Arial', 'B', 8);
				$y = $pdf->getY();
				$pdf->setX(11);
				$pdf->MultiCell(1.4, 0.4,utf8_decode('SERIE: '),0,'L');
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->setXY(12.4,$y);
				$pdf->MultiCell(7, 0.4,utf8_decode($equipo['serie']),0,1);
				//$pdf->Cell(1,0.5,'',0,0,0);
				$pdf->setX(11);
				$pdf->SetFont('Arial', 'B', 8);
				$y = $pdf->getY();
				$pdf->MultiCell(2, 0.4,utf8_decode('UBICACIÓN: '),0,0);
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->setXY(12.8,$y);
				$pdf->MultiCell(6.4, 0.4,utf8_decode($equipo['area']),0,1);
				/* $pdf->Cell(8.4, 0.3,'',0,1,1);											  //0.5CM
				$pdf->setX(11);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(8.4, 0.5, utf8_decode('REPORTES:'),0,1,1);					  //0.5CM
				$pdf->setX(11);
				$pdf->Cell(8.4, 0.5, utf8_decode('ESCANEAR CÓDIGO QR'),0,1,1);			  //0.5CM
				$pdf->setX(11);
				$pdf->Cell(8.4, 0.5, utf8_decode('ENVIAR CORREO A:'),0,1,1);			  //0.5CM
				$pdf->setX(11);
				$pdf->Cell(8.4, 0.5, utf8_decode('soporte@maxialatam.com'),0,1,1);		  //0.5CM
				$pdf->setX(11);
				$pdf->Cell(8.4, 0.5, utf8_decode('LLAMAR AL: 302-0112'),0,1,1);			  //0.5CM  */
                $posicion++; //Aumenta posición 
            break; 
			
			case 5:
				$posicion = 0;
				$pdf->setXY(24,10.5);
				$pdf->Line(21,10.5,29.4,10.5); //Arriba 
				 $pdf->Line(21,10.5,21,17.5); //Izquierda
				$pdf->Line(29.4,10.5,29.4,17.5); //Derecha
				$pdf->Line(21,17.5,29.4,17.5); //Abajo 
				$pdf->Image("https://toolkit.maxialatam.com/soporte/images/encabezado-maxia-c.png", 21.5, 10.7, 2, 0.8, "png");
				$pdf->Image("https://toolkit.maxialatam.com/soporte/activos/".$idactivo."/qr/".$idactivo."qr.png", 23.5, 11.3, 3.1, 3.1, "png");
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->setXY(21,14.5);
				//$pdf->Cell(1,1,'',0,0,0);
				$pdf->SetTextColor(0,0,0);
				$pdf->MultiCell(8.4, 0.4,strtoupper(utf8_decode($equipo['activo'])),0,'C'); 
				$pdf->Cell(8.4, 0.3,'',0,1);
				$pdf->SetTextColor(92,92,92);
				$pdf->setX(21);
				//$pdf->Cell(1,0.5,'',0,0,0);
				//$pdf->SetFont('Arial', 'B', 8);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(1.4, 0.4, utf8_decode('MODELO: '),0,0);
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->Cell(7, 0.4, utf8_decode($equipo['modelo']),0,1);
				$pdf->setX(21);
				//$pdf->Cell(1,0.5,'',0,0,0);
				$pdf->SetFont('Arial', 'B', 8);
				$y = $pdf->getY();
				$pdf->MultiCell(1.4, 0.4,utf8_decode('SERIE: '),0,'L');
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->setXY(22.4,$y);
				$pdf->MultiCell(7, 0.4,utf8_decode($equipo['serie']),0,1);
				$pdf->setX(21);
				//$pdf->Cell(1,0.5,'',0,0,0);
				$pdf->SetFont('Arial', 'B', 8);
				$y = $pdf->getY();
				$pdf->MultiCell(2, 0.4,utf8_decode('UBICACIÓN: '),0,0);
				$pdf->SetFont('Arial', 'B', 10);				
				$pdf->setXY(22.8,$y);
				$pdf->MultiCell(6.4, 0.4,utf8_decode($equipo['area']),0,1); 
				//$pdf->Cell(7.4, 0.5, utf8_decode('UBICACIÓN: '.$equipo['area']),0,1);     //0.5CM
				/* $pdf->setX(21);
				$pdf->Cell(8.4, 0.3,'',0,1,1);											  //0.5CM //AJUSTARRR
				$pdf->setX(21);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(8.4, 0.5, utf8_decode('REPORTES:'),0,1,1);					  //0.5CM
				$pdf->setX(21);
				$pdf->Cell(8.4, 0.5, utf8_decode('ESCANEAR CÓDIGO QR'),0,1,1);			  //0.5CM
				$pdf->setX(21);
				$pdf->Cell(8.4, 0.5, utf8_decode('ENVIAR CORREO A:'),0,1,1);			  //0.5CM
				$pdf->setX(21);
				$pdf->Cell(8.4, 0.5, utf8_decode('soporte@maxialatam.com'),0,1,1);		  //0.5CM
				$pdf->setX(21);
				$pdf->Cell(8.4, 0.5, utf8_decode('LLAMAR AL: 302-0112'),0,1,1);			  //0.5CM  */
                 if($i != ($total - 1))
                {
                    //$pdf->AddPage('P', 'Letter');
                    $pdf->AddPage();
                } 
            break; 
		} 
}

$pdf->Output('I',"etiqueta_equipo.pdf");

?>