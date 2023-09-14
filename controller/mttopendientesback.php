<?php
	include("../conexion.php");
	//require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';
	//require_once '../../repositorio-lib/xls/Classes/PHPExcel.php';
	require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';
	
	global $mysqli;
	$desde 	= (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : ''); 
	$hasta 	= (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : ''); 
	$casa 	= (!empty($_REQUEST['casa']) ? $_REQUEST['casa'] : ''); 
	$unidad = (!empty($_REQUEST['unidadejecutora']) ? $_REQUEST['unidadejecutora'] : '');	
	
	/** Error reporting */
	/* error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);

	$spreadsheet = new PHPExcel();
	//obtener la hoja activa actual, (que es la primera hoja)
	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setTitle('Mantenimientos Pendientes');
	
	$fontColor = new PHPExcel_Style_Color();
	$fontColor->setRGB('ffffff');
	$style = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
	); */
	
	/** Error reporting */
	//error_reporting(E_ALL);
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
	$sheet->setTitle('Mantenimientos Pendientes');
	
	$fontColor = new \PhpOffice\PhpSpreadsheet\Style\Color();
	$fontColor->setRGB('ffffff');
	$style = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
	);
	
	//TITULO	
	$spreadsheet->getActiveSheet()->setCellValue('A1', 'Mantenimientos Pendientes');
	$spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
	//$spreadsheet->getActiveSheet()->getStyle("A1")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->mergeCells('A1:F1');
	
	//ENCABEZADO	
	$spreadsheet->getActiveSheet()
	->setCellValue('A3', 'Detalle')
	->setCellValue('B3', 'Total')
	->setCellValue('C3', 'Fecha según MP')
	->setCellValue('D3', 'Observaciones')
	->setCellValue('E3', 'Estado')
	->setCellValue('F3', 'Responsables');
	
	$spreadsheet->getActiveSheet()->getStyle('A3:F3')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle("A3:F3")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->getStyle('A3:F3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	$spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
	
	//SENTENCIA BASE
	$query  = " SELECT a.id, c.nombre AS equipo, c.serie, b.nombre as unidadejecutora, a.fechareal, 
				b.codigo, a.observaciones, d.nombre as estado, e.nombre AS responsables 
				FROM incidentes a
				LEFT JOIN ambientes b ON a.idambientes = b.id
				LEFT JOIN activos c ON a.idactivos = c.id
				LEFT JOIN estados d ON a.idestados = d.id
				LEFT JOIN usuarios e ON c.idresponsables = e.id
				INNER JOIN categorias f ON a.idcategorias = f.id
				WHERE 1 = 1 AND a.tipo = 'preventivos' AND (a.idestados != 16) 
				AND CURDATE() > a.fechareal AND b.nombre is not null AND b.id != 0 ";
	if($desde != ""){
		$query  .= " AND a.fechareal >= '".$desde."' ";
	}
	if($hasta != ""){
		$query  .= " AND a.fechareal <= '".$hasta."' ";
	}
	if($casa != ""){
		$query  .= " AND c.idresponsables = '".$casa."' ";
	}
	if($unidad != ""){
		$query  .= " AND b.id = '".$unidad."' ";
	}
	$query  .= " ORDER BY c.idambientes ASC ";
	//debug('mttos:'.$query);
	$result = $mysqli->query($query);
	$sum 	= $result->num_rows;
	$i 		= 4;
	$idp 	= '';
	$equipo = '';	
	$unidadejecutora = '';
	while($row = $result->fetch_assoc()){			
		//UNIDAD EJECUTORA
		if($unidadejecutora == '' || $unidadejecutora != $row['codigo']){
			$queryU  = "SELECT a.id
						FROM incidentes2 a
						LEFT JOIN ambientes b ON a.idambientes = b.id
						LEFT JOIN activos c ON a.idactivos = c.id AND b.id = c.idambientes
						WHERE 1 = 1 AND a.tipo = 'preventivos' AND (a.idestados != 16) 
						AND CURDATE() > a.fechareal AND b.nombre is not null  ";
			if($desde != ""){
				$queryU  .= " AND a.fechareal >= '".$desde."' ";
			}
			if($hasta != ""){
				$queryU  .= " AND a.fechareal <= '".$hasta."' ";
			}
			if($casa != ""){
				$queryU  .= " AND c.idresponsables = '".$casa."' ";
			}
			$queryU .= " AND b.codigo = '".$row['codigo']."' ";
			debug("QUERYU ES:".$queryU);
			$spreadsheet->getActiveSheet()->setCellValue('A'.$i, $row['unidadejecutora']);
			$spreadsheet->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(true)->setSize(10)->setColor($fontColor->setRGB('000000'));
			$spreadsheet->getActiveSheet()->getStyle("A".$i.":F".$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('c7d9f1'); 
			//debug('mttodet:'.$queryU);
			$resultU = $mysqli->query($queryU);
			$count = $resultU->num_rows;
			$spreadsheet->getActiveSheet()->setCellValue('B'.$i, $count);			
			$unidadejecutora = $row['codigo'];
			$i++;
		}
		
		//EQUIPO
		if($idp == '' || $idp != $row['id']){
			$spreadsheet->getActiveSheet()->setCellValue('A'.$i, '   Preventivo: '.$row['id'].',      Equipo: '.$row['equipo'].',      Serie: '.$row['serie']);
			$spreadsheet->getActiveSheet()->setCellValue('C'.$i, $row['fechareal']);
			$spreadsheet->getActiveSheet()->setCellValue('D'.$i, $row['observaciones']);
			$spreadsheet->getActiveSheet()->setCellValue('E'.$i, $row['estado']);
			$spreadsheet->getActiveSheet()->setCellValue('F'.$i, $row['responsables']);
			$equipo = $row['equipo'];
			$idp 	= $row['id'];
		}		
		$i++;
	}
	$spreadsheet->getActiveSheet()->setCellValue('A'.$i, 'Total');
	$spreadsheet->getActiveSheet()->setCellValue('B'.$i, $sum);
	$spreadsheet->getActiveSheet()->getStyle("A".$i.":F".$i)->getFont()->setBold(true)->setSize(12)->setColor($fontColor->setRGB('000000')); 
	$spreadsheet->getActiveSheet()->getStyle("A".$i.":F".$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('8fb3e5');
	//Ancho automatico		
	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(100);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);	
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);	
	$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);		
	//Renombrar hoja de Excel
	$spreadsheet->getActiveSheet()->setTitle('Mtto pendientes');
	$hoy = date('dmY');
	$nombreArc = 'Mtto pendientes - '.$hoy.'.xlsx';
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Type: application/vnd.ms-excel'); //xls
	header('Content-Disposition: attachment;filename='.$nombreArc);
	header('Cache-Control: max-age=0');	
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');
	/* header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$nombreArc.'"');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel5');
	$objWriter->save('php://output');
	exit();	 */

?>