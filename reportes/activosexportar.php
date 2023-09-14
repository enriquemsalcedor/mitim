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
	$spreadsheet->getActiveSheet()->setCellValue('A1', 'Reporte de Activos');
	$spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
	//$spreadsheet->getActiveSheet()->getStyle("A1")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->mergeCells('A1:P1');
	
	// ENCABEZADO 
	$spreadsheet->getActiveSheet()
	->setCellValue('A4', 'Serial 1')
	->setCellValue('B4', 'Serial 2')
	->setCellValue('C4', 'Nombre')
	->setCellValue('D4', 'Marca')		
	->setCellValue('E4', 'Modelo') 
	//->setCellValue('F4', 'Casa Médica')  
	->setCellValue('F4', 'Ubicación') 
	->setCellValue('G4', 'Tipo') 
	//->setCellValue('I4', 'Area') 
	->setCellValue('H4', 'Estado') 
	->setCellValue('I4', 'Fase')  
	->setCellValue('J4', 'Fecha tope mantenimiento') 
	->setCellValue('K4', 'Fecha instalación') 
	//->setCellValue('L4', 'Empresas')
	->setCellValue('L4', 'Clientes')
	->setCellValue('M4', 'Proyectos');
	
	$spreadsheet->getActiveSheet()->getStyle('A4:M4')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle("A4:M4")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->getStyle('A4:M4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	// ALTURA
	$spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(20);
	//BORDES
	$spreadsheet->getActiveSheet()->getStyle('A4:M4')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
	$spreadsheet->getActiveSheet()->getStyle('M4')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
	$spreadsheet->getActiveSheet()->getStyle('M4')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
	// b.codigo AS codigound,
	//SENTENCIA BASE
	$query  = "  SELECT a.id, a.serie AS codequipo, LEFT(a.nombre,45) AS equipo, a.activo, ma.nombre AS marca, mo.nombre AS modelo,
				  ti.nombre AS modalidad, a.estado, a.fase, DATE_FORMAT(a.fechatopemant,'%m/%d/%Y') AS fechatopemant, DATE_FORMAT(a.fechainst,'%m/%d/%Y') AS fechainst, b.nombre AS uni, 
				 c.descripcion AS idempresas, d.nombre AS idclientes, e.nombre AS idproyectos 
				 FROM activos a 
				 LEFT JOIN ambientes b ON a.idambientes = b.id 
				 LEFT JOIN subambientes sub ON sub.id = a.idsubambientes
				 LEFT JOIN empresas c ON a.idempresas = c.id 
				 LEFT JOIN clientes d ON a.idclientes = d.id 
				 LEFT JOIN proyectos e ON a.idproyectos = e.id 
				 LEFT JOIN marcas ma ON a.idmarcas = ma.id 
				 LEFT JOIN modelos mo ON a.idmodelos = mo.id 
				 LEFT JOIN activostipos ti ON ti.id = a.idtipo
				 LEFT JOIN usuarios us ON us.id = a.idresponsables
				 WHERE 1 = 1 ";	
	if($bserial1 !=''){
		$query .= " AND a.serie LIKE '%$bserial1%' ";
	}				
	if($bserial2 !=''){
		$query .= " AND a.activo LIKE '%$bserial2%' ";
	}		
	if($bnombre !=''){
		$query .= " AND a.nombre LIKE '%$bnombre%' ";
	}				
	if($bmarca !=''){
		$query .= " AND ma.nombre LIKE '%$bmarca%' ";
	}				
	if($bmodelo !=''){
		$query .= " AND mo.nombre LIKE '%$bmodelo%' ";
	} 
	if($bresponsable !=''){
		$query .= " AND us.nombre LIKE '%$bresponsable%' ";
	} 
	if($bcodigound !=''){
		$query .= " AND c.codigound LIKE '%$bcodigound%' ";
	} 
	if($bubicacion !=''){
		$query .= " AND b.nombre LIKE '%$bubicacion%' ";
	} 
	if($btipo !=''){
		$query .= " AND ti.nombre LIKE '%$btipo%' ";
	} 
	if($barea !=''){
		$query .= " AND sub.nombre LIKE '%$barea%' ";
	} 
	if($bestado !=''){
		$query .= " AND a.estado LIKE '$bestado%' ";
	} 
	if($bfase !=''){
		$query .= " AND a.fase LIKE '$bfase%' ";
	} 
	/*if($bcomentarios !=''){
		$query .= " AND a.comentarios LIKE '$bcomentarios%' ";
	} */
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
		$query .= " AND d.nombre LIKE '%$bclientes%' ";
	}
	if($bproyectos !=''){
		$query .= " AND e.nombre LIKE '%$bproyectos%' ";
	} 
	if($nivel != 1 && $nivel != 2){
		if($idclientes != ''){
			$arr = strpos($idclientes, ',');
			if ($arr !== false) {
				$query  .= " AND a.idclientes IN (".$idclientes.") ";
			}else{
				$query  .= " AND find_in_set(".$idclientes.",a.idclientes) ";
			}  
		}
		if($idproyectos != ''){
			$arr = strpos($idproyectos, ',');
			if ($arr !== false) {
				$query  .= " AND a.idproyectos IN (".$idproyectos.") ";
			}else{
				$query  .= " AND find_in_set(".$idproyectos.",a.idproyectos) ";
			}  
		}	
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
	debugL($query,"CARGARACTIVOS");
	$result = $mysqli->query($query);
	while($row = $result->fetch_assoc()){
		if( $sitios != $row['codequipo']) { // OTRO sitios
			$fini = $i;
			$sitios = $row['codequipo']; 
			
			$spreadsheet->getActiveSheet()
			->setCellValue('A'.$i, mb_strtoupper($row['codequipo']))
			->setCellValue('B'.$i, mb_strtoupper($row['activo']))
			->setCellValue('C'.$i, mb_strtoupper($row['equipo']))
			->setCellValue('D'.$i, mb_strtoupper($row['marca']))
			->setCellValue('E'.$i, mb_strtoupper($row['modelo']))
			//->setCellValue('F'.$i, $row['casamedica']) 
			->setCellValue('F'.$i, mb_strtoupper($row['uni'])) 
			->setCellValue('G'.$i, mb_strtoupper($row['modalidad'])) 
			//->setCellValue('I'.$i, $row['area']) 
			->setCellValue('H'.$i, mb_strtoupper($row['estado'])) 
			->setCellValue('I'.$i, mb_strtoupper($row['fase']))  
			->setCellValue('J'.$i, $row['fechatopemant']) 
			->setCellValue('K'.$i, $row['fechainst']) 
			//->setCellValue('L'.$i, mb_strtoupper($row['idempresas'])) 
			->setCellValue('L'.$i, mb_strtoupper($row['idclientes'])) 
			->setCellValue('M'.$i, mb_strtoupper($row['idproyectos'])); 
			$i++; 
		}			
	}
	$spreadsheet->getActiveSheet()->getStyle("A".($ffin+1).":"."N".($ffin+1))->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
	
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