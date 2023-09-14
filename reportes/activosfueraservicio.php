<?php
	include("../conexion.php");
	require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';
	
	global $mysqli;
	//SESSION
	$usuario 	 = $_SESSION['usuario'];
	$nivel 		 = $_SESSION['nivel'];
	$idclientes  = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : 0);
	$idproyectos = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : 0);   
	//LocalStorage
	$bserial1		= $_REQUEST['bserial1'];
	$bserial2		= $_REQUEST['bserial2'];
	$bnombre		= $_REQUEST['bnombre'];
	$bmarca 		= $_REQUEST['bmarca'];
	$bmodelo 		= $_REQUEST['bmodelo']; 
	$bresponsable	= $_REQUEST['bresponsable'];
	$bcodigound		= $_REQUEST['bcodigound']; 
	$bubicacion		= $_REQUEST['bubicacion']; 
	$btipo			= $_REQUEST['btipo']; 
	$barea			= $_REQUEST['barea']; 
	$bestado		= $_REQUEST['bestado']; 
	$bfase			= $_REQUEST['bfase'];  
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
	$sheet->setTitle('Activos');
	
	$fontColor = new \PhpOffice\PhpSpreadsheet\Style\Color();
	$fontColor->setRGB('ffffff');
	$style = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
	);
	
	//TITULO	
	$spreadsheet->getActiveSheet()->setCellValue('A1', 'Reporte de pérdidas de activos fuera de servicio');
	$spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
	$spreadsheet->getActiveSheet()->getStyle("A1")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->mergeCells('A1:N1');
	
	// ENCABEZADO 
	$spreadsheet->getActiveSheet() 
	->setCellValue('A4', 'Nombre')
	->setCellValue('B4', 'Serial 1')
	->setCellValue('C4', 'Serial 2')
	->setCellValue('D4', 'Marca')		
	->setCellValue('E4', 'Modelo')   
	->setCellValue('F4', 'Ubicación') 
	->setCellValue('G4', 'Ingresos que genera')
	->setCellValue('H4', 'Fuera de servicio desde')
	->setCellValue('I4', 'Fuera de servicio hasta')
	->setCellValue('J4', 'Días fuera de servicio')
	->setCellValue('K4', 'Pérdida')
	->setCellValue('L4', 'Incidente')
	->setCellValue('M4', 'Cliente')
	->setCellValue('N4', 'Proyecto');
	
	$spreadsheet->getActiveSheet()->getStyle('A4:N4')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle("A4:N4")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->getStyle('A4:N4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	// ALTURA
	$spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(20);
	//BORDES
	$spreadsheet->getActiveSheet()->getStyle('A4:N4')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
	$spreadsheet->getActiveSheet()->getStyle('A4:N4')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
	$spreadsheet->getActiveSheet()->getStyle('A4:N4')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
	
	//SENTENCIA BASE
	$query  = " SELECT b.nombre, b.serie AS serial1, b.activo AS serial2, c.nombre AS marca, d.nombre AS modelo, e.nombre AS ubicacion, a.id, a.fechacreacion, a.fecharesolucion, DATEDIFF(a.fecharesolucion,a.fechacreacion) AS dias, DATE_FORMAT(a.fechacreacion,'%m/%d/%Y') AS fechacreacion, DATE_FORMAT(a.fecharesolucion,'%m/%d/%Y') AS fecharesolucion,  	
				a.fueraservicio, a.idactivos, b.ingresos, f.nombre AS cliente, g.nombre AS proyecto, a.id AS incidente 
				FROM incidentes a 
				INNER JOIN activos b ON b.id = a.idactivos 
				LEFT JOIN marcas c ON c.id = b.idmarcas
				LEFT JOIN modelos d ON d.id = b.idmodelos
				INNER JOIN ambientes e ON e.id = a.idambientes
				LEFT JOIN subambientes sub ON sub.id = b.idsubambientes
				INNER JOIN clientes f ON f.id = b.idclientes
				INNER JOIN proyectos g ON g.id = b.idproyectos
				LEFT JOIN activostipos h ON h.id = b.idtipo
				LEFT JOIN usuarios us ON us.id = b.idresponsables
				WHERE a.fueraservicio = 1 ";
				
	if($nivel == 4 || $nivel == 7){
		if($idclientes != ''){
			$arr = strpos($idclientes, ',');
			if ($arr !== false) {
				$query  .= " AND b.idclientes IN (".$idclientes.") ";
			}else{
				$query  .= " AND find_in_set(".$idclientes.",b.idclientes) ";
			}  
		}
		if($idproyectos != ''){
			$arr = strpos($idproyectos, ',');
			if ($arr !== false) {
				$query  .= " AND b.idproyectos IN (".$idproyectos.") ";
			}else{
				$query  .= " AND find_in_set(".$idproyectos.",b.idproyectos) ";
			}  
		}	
	} 
	if($bserial1 !=''){
		$query .= " AND b.serie LIKE '%$bserial1%' ";
	}				
	if($bserial2 !=''){
		$query .= " AND b.activo LIKE '%$bserial2%' ";
	}		
	if($bnombre !=''){
		$query .= " AND b.nombre LIKE '%$bnombre%' ";
	}				
	if($bmarca !=''){
		$query .= " AND c.nombre LIKE '%$bmarca%' ";
	}				
	if($bmodelo !=''){
		$query .= " AND d.nombre LIKE '%$bmodelo%' ";
	} 
	if($bubicacion !=''){
		$query .= " AND e.nombre LIKE '%$bubicacion%' ";
	} 
	if($btipo !=''){
		$query .= " AND h.nombre LIKE '%$btipo%' ";
	}  
	if($barea !=''){
		$query .= " AND sub.nombre LIKE '%$barea%' ";
	}
	if($bestado !=''){
		$query .= " AND b.estado LIKE '$bestado%' ";
	} 
	if($bfase !=''){
		$query .= " AND b.fase LIKE '$bfase%' ";
	} 
	if($bresponsable !=''){
		$query .= " AND us.nombre LIKE '%$bresponsable%' ";
	} 
	if($bfechatopemant !=''){
		$query .= " AND b.fechatopemant LIKE '$bfechatopemant%' ";
	} 
	if($bfechainst !=''){
		$query .= " AND b.fechainst LIKE '$bfechainst%' ";
	} 
	if($bclientes !=''){
		$query .= " AND f.nombre LIKE '%$bclientes%' ";
	}
	if($bproyectos !=''){
		$query .= " AND g.nombre LIKE '%$bproyectos%' ";
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
	debugL("ACTIVOS FUERASERVICIO:".$query);
	$result = $mysqli->query($query);
	while($row = $result->fetch_assoc()){
		
		$dias 	  = $row['dias'];
		$ingresos = $row['ingresos'];
		if($ingresos == ""){
			$ingresos = 0;
		} 
		if($dias == ""){
			$dias = 0;
		} 
		$perdidas = $ingresos * $dias;
		//$perdidas = 0;
		
		$spreadsheet->getActiveSheet()
		->setCellValue('A'.$i, mb_strtoupper($row['nombre']))
		->setCellValue('B'.$i, mb_strtoupper($row['serial1']))
		->setCellValue('C'.$i, mb_strtoupper($row['serial2']))
		->setCellValue('D'.$i, mb_strtoupper($row['marca']))
		->setCellValue('E'.$i, mb_strtoupper($row['modelo']))  
		->setCellValue('F'.$i, mb_strtoupper($row['ubicacion']))  
		->setCellValue('G'.$i, $ingresos)  
		->setCellValue('H'.$i, $row['fechacreacion']) 
		->setCellValue('I'.$i, $row['fecharesolucion']) 
		->setCellValue('J'.$i, $dias) 
		->setCellValue('K'.$i, $perdidas) 
		->setCellValue('L'.$i, $row['incidente']) 
		->setCellValue('M'.$i, mb_strtoupper($row['cliente'])) 
		->setCellValue('N'.$i, mb_strtoupper($row['proyecto']));
	
		//$spreadsheet->getActiveSheet()->getStyle('A'.$i.':N'.$i)->getAlignment()->applyFromArray(
			//		array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));	
		$spreadsheet->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray($style);
		$spreadsheet->getActiveSheet()->getStyle('G'.$i)->applyFromArray($style);
		$spreadsheet->getActiveSheet()->getStyle('J'.$i)->applyFromArray($style);
		$spreadsheet->getActiveSheet()->getStyle('K'.$i)->applyFromArray($style);
		$spreadsheet->getActiveSheet()->getStyle('L'.$i)->applyFromArray($style);
		$i++;  		
	}
	//$spreadsheet->getActiveSheet()->getStyle("A".($ffin+1).":"."N".($ffin+1))->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
	
	//Ancho automatico
	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(40);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40); 
	$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40); 
	$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20); 
	$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(30); 
	$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(30); 
	$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(30); 
	$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);    
	$spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);    
	$spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(40);    
	$spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(40);    
	//$spreadsheet->getActiveSheet()->getStyle('A5:N'.$i)->getAlignment()->setWrapText(true);
	
	$hoy = date('dmY');
	$nombreArc = 'Pérdidas Fuera de Servicio - '.$hoy.'.xlsx';
	guardarRegistroG('Activos', 'Fue generado un archivo con el nombre: '.$nombreArc);
	// redirect output to client browser
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Type: application/vnd.ms-excel'); //xls
	header('Content-Disposition: attachment;filename='.$nombreArc);
	header('Cache-Control: max-age=0');	
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');
		
?>