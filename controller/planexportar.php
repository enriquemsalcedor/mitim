<?php
	include("../conexion.php");

	global $mysqli;
	$usuario 		 = $_SESSION['usuario'];
	$nivel 			 = $_SESSION['nivel']; 
	//LocalStorage
	$bid 			= $_REQUEST['bid'];
	$bservicio		= $_REQUEST['bservicio'];
	$bsistema 		= $_REQUEST['bsistema'];
	$btitulo		= $_REQUEST['btitulo'];
	$bfrecuencia 	= $_REQUEST['bfrecuencia'];
	$bresponsable	= $_REQUEST['bresponsable'];
	$bambiente 		= $_REQUEST['bambiente'];
	$bsubambiente	= $_REQUEST['bsubambiente']; 
	
	/** Error reporting */
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	//date_default_timezone_set('Europe/London');
	
	$id 	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
	$data 	 = '';
	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

	/** Include PHPExcel */
	//require_once dirname(__FILE__) . '../../repositorio-lib/xls/Classes/PHPExcel.php';
	require_once '../../repositorio-lib/xls/Classes/PHPExcel.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Maxia Latam")
	->setLastModifiedBy("Maxia Latam")
	->setTitle("Reporte de Plan de Mantenimiento")
	->setSubject("Reporte de Plan de Mantenimiento")
	->setDescription("Reporte de Plan de Mantenimiento")
	->setKeywords("Reporte de Plan de Mantenimiento")
	->setCategory("Reportes");
	
	//ESTILOS
	$styleArray = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN
			)
		)
	);
	$fontColor = new PHPExcel_Style_Color();
	$fontColor->setRGB('ffffff');

	$fontGreen = new PHPExcel_Style_Color();
	$fontGreen->setRGB('00b355');
	$fontRed = new PHPExcel_Style_Color();
	$fontRed->setRGB('ff0000');
	
	$style = array(
			'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
	);
	$style2 = array(
			'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			)
	);

	//TITULO	
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Reporte de Plan de Mantenimiento');
	$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
	$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($style);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
	
	// ENCABEZADO 
	$objPHPExcel->getActiveSheet()
	->setCellValue('A4', '# Id')
	->setCellValue('B4', 'Tarea')
	->setCellValue('C4', 'Frecuencia')
	->setCellValue('D4', 'Responsable') 
	->setCellValue('E4', 'Ambientes')  
	->setCellValue('F4', 'Subambientes')	
	->setCellValue('G4', 'Formulario')
	->setCellValue('H4', 'Observación')
	->setCellValue('I4', 'Tipo de plan')
	->setCellValue('J4', 'Empresas')
	->setCellValue('K4', 'Clientes')
	->setCellValue('L4', 'Proyectos')
	->setCellValue('M4', 'Categorias')
	->setCellValue('N4', 'Subcategorias')
	->setCellValue('O4', 'Departamento')
	->setCellValue('P4', 'Centro de costos')
	->setCellValue('Q4', 'Entregables'); 
	
	//LETRA
	$objPHPExcel->getActiveSheet()->getStyle('A4:Q4')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
	$objPHPExcel->getActiveSheet()->getStyle("A4:Q4")->applyFromArray($style);
	//FONDO
	$objPHPExcel->getActiveSheet()->getStyle('A4:Q4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('63b9db');
	
	$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Actividades' AND usuario = '".$_SESSION['usuario']."'";
	$result = $mysqli->query($query);
	if($result->num_rows >0){
		$row = $result->fetch_assoc();				
		if (!isset($_REQUEST['data'])) {
			$data = $row['filtrosmasivos'];
		}
	}
	$where2 = '';
	if($data != ''){
		$data = json_decode($data);
		if(!empty($data->idambientesf)){
			$idambientesf = json_encode($data->idambientesf);
			if($idambientesf != '[""]'){
				$where2 .= " AND d.id IN ($idambientesf)";
			}				
		} 
		if(!empty($data->idsubambientesf)){
			$idsubambientesf = json_encode($data->idsubambientesf);
			if($idsubambientesf != '[""]'){
				$where2 .= " AND e.id IN ($idsubambientesf)";
			}
		}
		if(!empty($data->responsablef)){
			$responsablef = json_encode($data->responsablef);
			if($responsablef != '[""]'){
				$where2 .= " AND a.responsable IN ($responsablef)";
			}
		} 
		$vowels = array("[", "]");
		$where2 = str_replace($vowels, "", $where2);
	}
	
	//SENTENCIA BASE
	$query  = " SELECT a.id, a.titulo, a.frecuencia, f.nombre AS responsables,
				a.formulario, a.observacion, a.tipoplan, d.nombre as ambientes, e.nombre as subambientes, 
				g.descripcion as empresas, 
				h.nombre as clientes, i.nombre as proyectos, j.nombre as categorias, k.nombre as subcategorias, 
				l.nombre as departamentos, m.nombre as centrocostos, n.nombre as entregables
				FROM plan a 
				INNER JOIN ambientes d ON a.idambientes = d.id
				INNER JOIN subambientes e ON a.idsubambientes = e.id
				INNER JOIN usuarios f ON a.responsable = f.correo
				INNER JOIN empresas g ON a.idempresas = g.id
				INNER JOIN clientes h ON a.idclientes = h.id
				INNER JOIN proyectos i ON a.idproyectos = i.id
				INNER JOIN categorias j ON a.idcategorias = j.id
				INNER JOIN subcategorias k ON a.idsubcategorias = k.id
				INNER JOIN departamentos l ON a.iddepartamentos = l.id
				INNER JOIN centrocostos m ON a.idcentrocostos = m.id
				INNER JOIN entregables n ON a.identregables = n.id
				WHERE a.tipoplan <> 'N' "; 
				
	//LocalStorage
	if($bid != ''){
		$where2 .= " AND a.id like '%".$bid."%' ";
	}
	if($btitulo != ''){
		$where2 .= " AND a.titulo like '%".$btitulo."%' ";
	}
	if($bfrecuencia != ''){
		$where2 .= " AND a.frecuencia like '%".$bfrecuencia."%' ";
	}
	if($bresponsable != ''){
		$where2 .= " AND f.nombre like '%".$bresponsable."%' ";
	}		
	if($bambiente != ''){
		$where2 .= " AND d.nombre like '%".$bambiente."%' ";
	}
	if($bsubambiente != ''){
		$where2 .= " AND e.nombre like '%".$bsubambiente."%' ";
	} 
	//CUERPO
	//Definir fuente
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);					
	
	$query  .= " $where2 ORDER BY a.id desc ";
	//debug('Exportarc: '.$query);
	$result = $mysqli->query($query);
	$i = 5;		
	while($row = $result->fetch_assoc()){ 
		$numeroreq = str_pad($row['id'], 4, "0", STR_PAD_LEFT);		
		$objPHPExcel->getActiveSheet()
		->setCellValue('A'.$i, $numeroreq)
		->setCellValue('B'.$i, $row['titulo'])
		->setCellValue('C'.$i, $row['frecuencia'])
		->setCellValue('D'.$i, $row['responsables']) 
		->setCellValue('E'.$i, $row['ambientes'])     
		->setCellValue('F'.$i, $row['subambientes'])		
		->setCellValue('G'.$i, $row['formulario'])
		->setCellValue('H'.$i, $row['observacion'])
		->setCellValue('I'.$i, $row['tipoplan'])
		->setCellValue('J'.$i, $row['empresas'])
		->setCellValue('K'.$i, $row['clientes']) 
		->setCellValue('L'.$i, $row['proyectos'])     
		->setCellValue('M'.$i, $row['categorias'])
		->setCellValue('N'.$i, $row['subcategorias'])
		->setCellValue('O'.$i, $row['departamentos']) 
		->setCellValue('P'.$i, $row['centrocostos'])     
		->setCellValue('Q'.$i, $row['entregables']);
		
		$i++;
	}

	//Ancho automatico
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);

	//Renombrar hoja de Excel
	$objPHPExcel->getActiveSheet()->setTitle('Actividades - Plan de Mtto.');

	//Redirigir la salida al navegador del cliente
	$hoy = date('dmY');
	$nombreArc = 'Plan de Mantenimiento - '.$hoy.'.xls';
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$nombreArc.'"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit();
?>