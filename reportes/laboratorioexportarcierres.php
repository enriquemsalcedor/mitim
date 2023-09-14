<?php
	include("../conexion.php");
	require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';

	global $mysqli;
	 
	$ids = $_REQUEST['ids'];
	$tipo = $_REQUEST['tipo'];
	/** Error reporting */
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);

	//load phpspreadsheet class using namespaces
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	//call xlsx writer class to make an xlsx file
	use PhpOffice\PhpSpreadsheet\IOFactory;
	//make a new spreadsheet object
	$spreadsheet = new Spreadsheet();
	//obtener la hoja activa actual, (que es la primera hoja)
	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setTitle('Laboratorio');
	
	$fontColor = new \PhpOffice\PhpSpreadsheet\Style\Color();
	$fontColor->setRGB('ffffff');
	$center = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
	);
	$right = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
			)
	);
	$left = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			)
	);
	$bordes = [
		'borders' => [
			'top' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			],
			'left' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			]
			,'right' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			]
			,'bottom' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			],
		]
	];
	
	$bordesbotton = [
		'borders' => [ 
			'bottom' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			],
		]
	];
	$bordestop = [
		'borders' => [ 
			'top' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			],
		]
	];
	
	$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
	$drawing->setName('Logo');
	$drawing->setDescription('Logo');
	$drawing->setPath('../images/MaxiaSym.png');
	$drawing->setHeight(72);
	$drawing->setCoordinates('A4');
	$drawing->setOffsetY(2);
	$drawing->setWorksheet($spreadsheet->getActiveSheet());
	/*
	$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing();
	$drawing->setName('PhpSpreadsheet logo');
	$drawing->setPath('../images/9w.jpg');
	$drawing->setHeight(36);
	$spreadsheet->getActiveSheet()->getHeaderFooter()->addImage($drawing, \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter::IMAGE_HEADER_LEFT);
	$spreadsheet->getActiveSheet()->setBreak('A4', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
	*/
	//SENTENCIA BASE
	$query  = " SELECT b.orden, b.nroorden, b.fecha, c.nombre as usuario
				FROM laboratorio a 
				INNER JOIN laboratoriocierres b ON b.idequipo = a.id
				INNER JOIN usuarios c ON c.usuario = b.usuario
				WHERE ";
	if($tipo != 'listar'){
		$query .= " a.id IN (".$ids.") ";
	}else{
		$query .= "b.nroorden = '".$ids."'";
	}
	$query .= "ORDER BY a.id DESC ";
	debug('TIPO:'.$tipo.' - QUERY EXPORTARCIERRE ES:'.$query); 		
	$result = $mysqli->query($query);
	if($row = $result->fetch_assoc()){	
		$nroorden = $row['nroorden'];
		$orden = $row['orden'];
		$fecha = $row['fecha'];
		$usuario = $row['usuario'];
	}else{
		$nroorden = "";
		$orden = "";
		$fecha = "";
		$usuario = "";
	} 
	$codigo = substr($orden, -3); 
	//TITULO	
	$spreadsheet->getActiveSheet()->setCellValue('C4', 'ORDEN DE DESPACHO SALIDA DE LAB ELECTRÓNICA');
	$spreadsheet->getActiveSheet()->getStyle('C4')->applyFromArray($center);
	$spreadsheet->getActiveSheet()->mergeCells('C4:G4');
	$spreadsheet->getActiveSheet()->getStyle('C4:G4')->getFont()->setBold(true)->setSize(20);
	$spreadsheet->getActiveSheet()->getStyle('C4:G4')->applyFromArray($bordes);
	$spreadsheet->getActiveSheet()->getStyle('A4:C4')->applyFromArray($bordestop);
	
	$spreadsheet->getActiveSheet()->setCellValue('C5', 'CÓDIGO: MX-LE-RS-'.$codigo);
	$spreadsheet->getActiveSheet()->mergeCells('C5:E6');
	$spreadsheet->getActiveSheet()->getStyle('C5:E5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
	$spreadsheet->getActiveSheet()->getStyle('C5:E5')->applyFromArray($center);
	$spreadsheet->getActiveSheet()->getStyle('C5:E5')->getFont()->setBold(true)->setSize(11);
	$spreadsheet->getActiveSheet()->getStyle('C5:E6')->applyFromArray($bordes);
	//$spreadsheet->getActiveSheet()->getStyle('C4:G5')->applyFromArray($bordes);
	//$spreadsheet->getActiveSheet()->getStyle('C5:F5')->applyFromArray($bordes);
	
	$spreadsheet->getActiveSheet()->setCellValue('F5', 'FECHA:');
	$spreadsheet->getActiveSheet()->setCellValue('F6', 'ORDEN:');
	$spreadsheet->getActiveSheet()->getStyle('F5')->applyFromArray($center);
	$spreadsheet->getActiveSheet()->getStyle('F6')->applyFromArray($center);
	$spreadsheet->getActiveSheet()->getStyle("F5")->getFont()->setBold(true)->setSize(11);
	$spreadsheet->getActiveSheet()->getStyle("F6")->getFont()->setBold(true)->setSize(11);
	 
	$spreadsheet->getActiveSheet()->setCellValue('G5', $fecha);
	$spreadsheet->getActiveSheet()->setCellValue('G6', $nroorden);
	$spreadsheet->getActiveSheet()->getStyle('C5:G5')->applyFromArray($bordes);
	$spreadsheet->getActiveSheet()->getStyle('C6:G6')->applyFromArray($bordes);
	 
	// ENCABEZADO 
$spreadsheet->getActiveSheet()
	->setCellValue('A7', '#')
	->setCellValue('B7', 'Orden de Trabajo')
	->setCellValue('C7', 'Equipo')  
	->setCellValue('D7', 'Marca')
	->setCellValue('E7', 'Modelo')    
	->setCellValue('F7', 'Número de Serie')
	->setCellValue('G7', 'Estado del equipo'); 
	//$spreadsheet->getActiveSheet()
	//->setCellValue('G7', 'ESTADO DEL EQUIPO');
	//$spreadsheet->getActiveSheet()->mergeCells('G7:I7');
	
	$spreadsheet->getActiveSheet()->getStyle('A7:G7')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle("A7:G7")->applyFromArray($center);
	$spreadsheet->getActiveSheet()->getStyle('A7:G7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	$spreadsheet->getActiveSheet()->getStyle('A7:G7')->applyFromArray($bordes);
	$spreadsheet->getActiveSheet()->getRowDimension('7')->setRowHeight(25);
	$spreadsheet->getActiveSheet()->getStyle('A7:G7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); 
	 
	$query2  = " SELECT a.id, a.titulo, a.marca, a.modelo, a.serie, a.diagnostico
				FROM laboratorio a 
				INNER JOIN laboratoriocierres b ON b.idequipo = a.id 
				WHERE ";
	if($tipo != 'listar'){
		$query2 .= " a.id IN (".$ids.") ";
	}else{
		$query2 .= "b.nroorden = '".$ids."'";
	}
	$query2 .= "ORDER BY a.id DESC ";
	$result2 = $mysqli->query($query2);
	$i = 8;
	$k = 1;
	while($row = $result2->fetch_assoc()){ 
		$diagnostico = ucwords($row['diagnostico']);
		if($diagnostico=='Sinasignar'){
			$diagnostico = 'Sin Asignar';
		}
		
		$spreadsheet->getActiveSheet()
		->setCellValue('A'.$i, $k)
		->setCellValue('B'.$i, $row['id'])
		->setCellValue('C'.$i, ucwords($row['titulo']))
		->setCellValue('D'.$i, ucwords($row['marca']))
		->setCellValue('E'.$i, ucwords($row['modelo']))
		->setCellValue('F'.$i, ucwords($row['serie']))
		->setCellValue('G'.$i, ucwords($diagnostico));
		//$spreadsheet->getActiveSheet()
		//->setCellValue('G'.$i, strtoupper($diagnostico));
		
		$spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($center);
		$spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($center);
		
		$spreadsheet->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setBold(false)->setSize(14); 
		//$spreadsheet->getActiveSheet()->mergeCells('G'.$i.':I'.$i);
		$spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($bordes);
		$spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($bordes);
		$spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($bordes);
		$spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($bordes);
		$spreadsheet->getActiveSheet()->getStyle('E'.$i)->applyFromArray($bordes);
		$spreadsheet->getActiveSheet()->getStyle('F'.$i)->applyFromArray($bordes);
		$spreadsheet->getActiveSheet()->getStyle('G'.$i)->applyFromArray($bordes); 
		//$spreadsheet->getActiveSheet()->getStyle('G'.$i.':I'.$i)->applyFromArray($bordes);
		
		$i++;
		$k++;
	} 
	$fin1 = $i+1;
	$fin2 = $fin1++;
	$fin3 = $fin2+3;
	$fin4 = $fin3+3; 
	//$spreadsheet->getActiveSheet()->mergeCells('A'.$fin1.':I'.$fin1);
	
	$spreadsheet->getActiveSheet()
	->setCellValue('A'.$fin2, 'Firma: Laboratorio de Electrónica   ');
	$spreadsheet->getActiveSheet()->mergeCells('A'.$fin2.':F'.$fin2);	
	$spreadsheet->getActiveSheet()->getStyle('A'.$fin2.':F'.$fin2)->getFont()->setBold(true)->setSize(13);
	
	$spreadsheet->getActiveSheet()
	->setCellValue('G'.$fin2, '');
	$spreadsheet->getActiveSheet()->getStyle('G'.$fin2)->getFont()->setBold(true)->setSize(13);
	$spreadsheet->getActiveSheet()->getStyle('G'.$fin2)->applyFromArray($bordesbotton); 
	//$spreadsheet->getActiveSheet()->mergeCells('G'.$fin2.':I'.$fin2);
	//$spreadsheet->getActiveSheet()->getStyle('G'.$fin2.':I'.$fin2)->getFont()->setBold(true)->setSize(13);
	//$spreadsheet->getActiveSheet()->getStyle('G'.$fin2.':I'.$fin2)->applyFromArray($bordesbotton);
	
	$spreadsheet->getActiveSheet()
	->setCellValue('A'.$fin3, 'Firma: Almacenista   ');
	$spreadsheet->getActiveSheet()->mergeCells('A'.$fin3.':F'.$fin3);
	$spreadsheet->getActiveSheet()->getStyle('A'.$fin3.':F'.$fin3)->getFont()->setBold(true)->setSize(13);
	$spreadsheet->getActiveSheet()->getStyle('G'.$fin3)->applyFromArray($bordesbotton);
	
	$spreadsheet->getActiveSheet()
	->setCellValue('A'.$fin4, 'Firma: Colaborador Operativo( Persona que transporta el equipo)   ');
	$spreadsheet->getActiveSheet()->mergeCells('A'.$fin4.':F'.$fin4);
	$spreadsheet->getActiveSheet()->getStyle('A'.$fin4.':F'.$fin4)->getFont()->setBold(true)->setSize(13);
	$spreadsheet->getActiveSheet()->getStyle('G'.$fin4)->applyFromArray($bordesbotton); 
	
	//Ancho automatico	
	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5); 
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20); 
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40); 
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30); 
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30); 
	$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true); 
	//$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	//$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	
	$hoy = date('dmY');
	$nombreArc = 'Laboratorio - Cierres'.$hoy.'.xlsx';
	// redirect output to client browser
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Type: application/vnd.ms-excel'); //xls
	header('Content-Disposition: attachment;filename='.$nombreArc);
	header('Cache-Control: max-age=0');	
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');
	
?>