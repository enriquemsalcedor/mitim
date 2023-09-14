<?php
	include("../conexion.php");
	require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';
	
	global $mysqli;
	//SESSION
	$usuario 	= $_SESSION['usuario'];
	$nivel 		= $_SESSION['nivel'];
	//LocalStorage
	$bcodequipo		= $_REQUEST['bcodequipo'];
	$bactivo		= $_REQUEST['bactivo'];
	$bequipo		= $_REQUEST['bequipo'];
	$bmarca 		= $_REQUEST['bmarca'];
	$bmodelo 		= $_REQUEST['bmodelo'];
	$bcasamedica	= $_REQUEST['bcasamedica']; 
	$bcodigound		= $_REQUEST['bcodigound']; 
	$bunidad		= $_REQUEST['bunidad']; 
	$bmodalidad		= $_REQUEST['bmodalidad']; 
	$barea			= $_REQUEST['barea']; 
	$bestado		= $_REQUEST['bestado']; 
	$bfase			= $_REQUEST['bfase']; 
	$bcomentarios	= $_REQUEST['bcomentarios']; 
	$bfechatopemant	= $_REQUEST['bfechatopemant']; 
	$bfechainst		= $_REQUEST['bfechainst'];  
	$bempresas		= $_REQUEST['bempresas'];  
	$bclientes		= $_REQUEST['bclientes'];  
	$bproyectos		= $_REQUEST['bproyectos'];  
	
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
	$sheet->setTitle('Preventivos');
	
	$fontColor = new \PhpOffice\PhpSpreadsheet\Style\Color();
	$fontColor->setRGB('ffffff');
	$style = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
	);
	
	//TITULO	
	$spreadsheet->getActiveSheet()->setCellValue('A1', 'Reporte de Activos');
	$spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
	//$spreadsheet->getActiveSheet()->getStyle("A1")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->mergeCells('A1:P1');
	
	// ENCABEZADO 
	$spreadsheet->getActiveSheet()
	->setCellValue('A4', 'Serie')
	->setCellValue('B4', 'Activo')
	->setCellValue('C4', 'Equipo')
	->setCellValue('D4', 'Marca')		
	->setCellValue('E4', 'Modelo') 
	->setCellValue('F4', 'Casa Médica')  
	->setCellValue('G4', 'Ambiente') 
	->setCellValue('H4', 'Modalidad') 
	->setCellValue('I4', 'Area') 
	->setCellValue('J4', 'Estado') 
	->setCellValue('K4', 'Fase')  
	->setCellValue('L4', 'Fecha Tope Mant.') 
	->setCellValue('M4', 'Fecha Ist') 
	->setCellValue('N4', 'Empresas')
	->setCellValue('O4', 'Clientes')
	->setCellValue('P4', 'Proyectos');
	
	$spreadsheet->getActiveSheet()->getStyle('A4:P4')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle("A4:P4")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->getStyle('A4:P4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	// ALTURA
	$spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(20);
	//BORDES
	$spreadsheet->getActiveSheet()->getStyle('A4:P4')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
	$spreadsheet->getActiveSheet()->getStyle('P4')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
	$spreadsheet->getActiveSheet()->getStyle('P4')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
	
	//SENTENCIA BASE
	$query  = "     SELECT a.id, a.codequipo, LEFT(a.equipo,45) as equipo, a.activo, a.marca, a.modelo, a.casamedica,
					a.codigound, a.modalidad, a.area, a.estado, a.fase, LEFT(a.comentarios,45) as comentarios, a.fechatopemant,
					a.fechainst, b.unidad as uni, c.descripcion as idempresas, d.nombre as idclientes, e.nombre as idproyectos 
					FROM activos a 
					LEFT JOIN unidades b ON a.codigound = b.codigo 
					LEFT JOIN empresas c ON a.idempresas = c.id 
					LEFT JOIN clientes d ON a.idclientes = d.id 
					LEFT JOIN proyectos e ON a.idproyectos = e.id 
					WHERE 1 = 1 ";	
	if($bcodequipo !=''){
		$query .= " AND a.codequipo LIKE '%$bcodequipo%' ";
	}				
	if($bactivo !=''){
		$query .= " AND a.activo LIKE '%$bactivo%' ";
	}		
	if($bequipo !=''){
		$query .= " AND a.equipo LIKE '%$bequipo%' ";
	}				
	if($bmarca !=''){
		$query .= " AND a.marca LIKE '%$bmarca%' ";
	}				
	if($bmodelo !=''){
		$query .= " AND a.modelo LIKE '%$bmodelo%' ";
	} 
	if($bcasamedica !=''){
		$query .= " AND a.casamedica LIKE '%$bcasamedica%' ";
	} 
	if($bcodigound !=''){
		$query .= " AND c.codigound LIKE '%$bcodigound%' ";
	} 
	if($bunidad !=''){
		$query .= " AND b.unidad LIKE '%$bunidad%' ";
	} 
	if($bmodalidad !=''){
		$query .= " AND a.modalidad LIKE '%$bmodalidad%' ";
	} 
	if($barea !=''){
		$query .= " AND a.area LIKE '%$barea%' ";
	} 
	if($bestado !=''){
		$query .= " AND a.estado LIKE '$bestado%' ";
	} 
	if($bfase !=''){
		$query .= " AND a.fase LIKE '$bfase%' ";
	} 
	if($bcomentarios !=''){
		$query .= " AND a.comentarios LIKE '$bcomentarios%' ";
	} 
	if($bfechatopemant !=''){
		$query .= " AND a.fechatopemant LIKE '$bfechatopemant%' ";
	} 
	if($bfechainst !=''){
		$query .= " AND a.fechainst LIKE '$bfechainst%' ";
	}
	if($bempresas !=''){
		$query .= " AND c.descripcion LIKE '$bempresas%' ";
	}
	if($bclientes !=''){
		$query .= " AND d.nombre LIKE '$bclientes%' ";
	}
	if($bproyectos !=''){
		$query .= " AND e.nombre LIKE '$bproyectos%' ";
	} 
	
	//debug($query);
	//Definir fuente
	$spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
	$i = 5;
	$pos = $i;
	$fini = $i;
	$ffin = 0;
	$sitios = '';
	
	$query .= "GROUP BY a.id ";
	$query .= "ORDER BY a.id DESC";
	debugL("EXPORTARACTIVOS:".$query);
	$result = $mysqli->query($query);
	while($row = $result->fetch_assoc()){
		if( $sitios != $row['codequipo']) { // OTRO sitios
			$fini = $i;
			$sitios = $row['codequipo']; 
			
			$spreadsheet->getActiveSheet()
			->setCellValue('A'.$i, $row['codequipo'])
			->setCellValue('B'.$i, $row['activo'])
			->setCellValue('C'.$i, $row['equipo'])
			->setCellValue('D'.$i, $row['marca'])
			->setCellValue('E'.$i, $row['modelo']) 
			->setCellValue('F'.$i, $row['casamedica']) 
			->setCellValue('G'.$i, $row['uni']) 
			->setCellValue('H'.$i, $row['modalidad']) 
			->setCellValue('I'.$i, $row['area']) 
			->setCellValue('J'.$i, $row['estado']) 
			->setCellValue('K'.$i, $row['fase'])  
			->setCellValue('L'.$i, $row['fechatopemant']) 
			->setCellValue('M'.$i, $row['fechainst']) 
			->setCellValue('N'.$i, $row['idempresas']) 
			->setCellValue('O'.$i, $row['idclientes']) 
			->setCellValue('P'.$i, $row['idproyectos']); 
			$i++; 
		}			
	}
	$spreadsheet->getActiveSheet()->getStyle("A".($ffin+1).":"."P".($ffin+1))->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
	
	//Ancho automatico
	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40); 
	$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40); 
	$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(40); 
	$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(40); 
	$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(40); 
	$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(40); 
	$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(40); 
	$spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(40); 
	$spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(40);  
	$spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(40);  
	$spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(40);  
	$spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(40);  
	$spreadsheet->getActiveSheet()->getStyle('A5:P'.$i)->getAlignment()->setWrapText(true);
	
	$hoy = date('dmY');
	$nombreArc = 'Activos - '.$hoy.'.xlsx';
	guardarRegistroG('Activos', 'Fue generado un archivo con el nombre: '.$nombreArc);
	// redirect output to client browser
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Type: application/vnd.ms-excel'); //xls
	header('Content-Disposition: attachment;filename='.$nombreArc);
	header('Cache-Control: max-age=0');	
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');
		
?>