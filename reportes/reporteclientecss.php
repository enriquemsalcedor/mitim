<?php
	include("../conexion.php");
	require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';

	global $mysqli;
	//SESSION
 	$usuario 		  = $_SESSION['usuario'];
	$nivel 			  = $_SESSION['nivel'];
	$idempresas 	  = $_REQUEST['idempresas']; 
	$idclientes 	  = 1;
	$idproyectos 	  = $_REQUEST['idproyectos'];
	$idambientes 	  = $_REQUEST['idambientes'];
	$tipo 	  		  = $_REQUEST['tipo'];
	$fechadesdec	  = $_REQUEST['fechadesdec'];
	$fechahastac      = $_REQUEST['fechahastac']; 
	  
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
	$sheet->setTitle('Resumen');
	
	$fontColor = new \PhpOffice\PhpSpreadsheet\Style\Color();
	$fontColor->setRGB('ffffff');
	
	$azul = new \PhpOffice\PhpSpreadsheet\Style\Color();
	$azul->setRGB('0a70b9');
	
	$style = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
	);
	
	//TITULO	
	$spreadsheet->getActiveSheet()->setCellValue('A1', 'RESUMEN MENSUAL');
	$spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14); 
	$spreadsheet->getActiveSheet()->mergeCells('A1:G1');
	$spreadsheet->getActiveSheet()->getStyle("A1:G1")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->getStyle("A3")->getFont()->setBold(true)->setSize(12);
	$spreadsheet->getActiveSheet()->setCellValue('A3', 'CLIENTE:')->setCellValue('B3',"Caja del Seguro Social"); 
	
	$queryP = " SELECT nombre FROM proyectos WHERE id = ".$idproyectos."";
	$resultP = $mysqli->query($queryP); 
	if($rowP = $resultP->fetch_assoc()){
		$nombreproyecto = $rowP['nombre'];
		$spreadsheet->getActiveSheet()->getStyle("A4")->getFont()->setBold(true)->setSize(12);
		$spreadsheet->getActiveSheet()->setCellValue('A4', 'PROYECTO:')->setCellValue('B4', $nombreproyecto);
	}
	
	$queryP = " SELECT nombre FROM ambientes WHERE id = ".$idambientes."";
	$resultP = $mysqli->query($queryP); 
	if($rowP = $resultP->fetch_assoc()){
		$nombreproyecto = $rowP['nombre'];
		$spreadsheet->getActiveSheet()->getStyle("A5")->getFont()->setBold(true)->setSize(12);
		$spreadsheet->getActiveSheet()->setCellValue('A5', 'UBICACIÓN:')->setCellValue('B5', $nombreproyecto);
		$spreadsheet->getActiveSheet()->setCellValue('C5', ' ');
	}
	
	if($fechadesdec != ''){
		$spreadsheet->getActiveSheet()->setCellValue('D3', 'Desde:')->setCellValue('E3', $fechadesdec);
		$spreadsheet->getActiveSheet()->getStyle("D3")->getFont()->setBold(true)->setSize(12);
	}
	if($fechahastac != ''){
		$spreadsheet->getActiveSheet()->setCellValue('D4', 'Hasta:')->setCellValue('E4', $fechahastac);
		$spreadsheet->getActiveSheet()->getStyle("D4")->getFont()->setBold(true)->setSize(12);
	}
	
	//Variables
	$observacion     		= "";
	$equiposinstalados		= "";
	$correctivosem 			= ""; 
	$preventivosem 			= ""; 
	$correctivosperifericos = "";
	$preventivosperifericos = "";	
	$correctivosit 			= "";
	$preventivosit 			= ""; 
	$pruebasdesempeno		= "";
	$postventas 			= "";
	$festivos 				= "";
	$totaldias 				= 0;
	$fechadesdefueraservicio= "";
	$fechafinfueraservicio= "";
	
	//Dias Festivos Panamá
	$queryFestivos = " SELECT dia FROM diasfestivos ";
	$resultFestivos= $mysqli->query($queryFestivos);
	while($rowFestivos = $resultFestivos->fetch_assoc()){
		$festivos .= ''.$year.'-'.$rowFestivos['dia'].',';  
	}
	$festivos = explode(',', $festivos);
	$festivos = array_filter($festivos);
 	
	$queryE = " SELECT COUNT(*) AS equiposinstalados 
				FROM activos a 
				INNER JOIN ambientes b ON a.idambientes = b.id 
				INNER JOIN clientes c ON b.idclientes= c.id 
				INNER JOIN proyectos d ON b.idproyectos= d.id 
				WHERE b.idclientes = 1 
				AND b.idproyectos IN (".$idproyectos.") ";
	
	if($idambientes != ''){
		$queryE .= " AND a.idambientes = ".$idambientes." ";
	} 
	if($fechadesdec != ''){
		$queryE .= " AND ((a.fechainst >= '".$fechadesdec."' ";
	}
	if($fechahastac != ''){
		$queryE .= " AND a.fechainst <= '".$fechahastac."')) ";
	}
	$queryE .= " AND a.estado = 'Activo' ORDER by a.id DESC";
	
	debugL("EQUIPOS INSTALADOS:".$queryE);
	$resultE = $mysqli->query($queryE); 
	if($rowE = $resultE->fetch_assoc()){
		$equiposinstalados = $rowE['equiposinstalados'];
	}
	
	$queryA = "	SELECT 
				SUM(case when d.nombre = 'Tx Mantenimiento Correctivo' then 1 else 0 end) as correctivosem,
				SUM(case when d.nombre = 'Tx Mantenimiento Preventivo' then 1 else 0 end) as preventivosem, 
				SUM(case when d.nombre = 'Tx Correctivos Periféricos' then 1 else 0 end) as correctivosperifericos,  
				SUM(case when d.nombre = 'Tx Preventivos Periféricos' then 1 else 0 end) as preventivosperifericos,
				SUM(case when d.nombre = 'Tx TI Mantenimiento Preventivo' then 1 else 0 end) as preventivosit,
				SUM(case when d.nombre = 'Tx TI Consorcio' then 1 else 0 end) as correctivosit,
				SUM(case when d.nombre = 'Tx Pruebas de Desempeño' then 1 else 0 end) as pruebasdesempeno, 
				SUM(case when d.nombre = 'Tx Post Venta' then 1 else 0 end) as postventas 
				FROM incidentes a INNER JOIN clientes b ON b.id = a.idclientes 
				INNER JOIN proyectos c ON c.id = a.idproyectos 
				INNER JOIN categorias d ON d.id = a.idcategorias
				WHERE a.idclientes = 1 AND a.idproyectos = ".$idproyectos."";
	if($idambientes != ''){
		$queryA .= " AND a.idambientes = ".$idambientes." ";
	} 
	if($fechadesdec != ''){
		$queryA .= " AND ((a.fechacreacion >= '".$fechadesdec."' ";
	}
	if($fechahastac != ''){
		$queryA .= " AND a.fechacreacion <= '".$fechahastac."')) ";
	} 		
	
	//debugL("CUADRO AGRUPADOS:".$queryA);
	$resultA = $mysqli->query($queryA); 
	if($rowA = $resultA->fetch_assoc()){
		
		$correctivosem 			= $rowA['correctivosem'];
		$preventivosem 			= $rowA['preventivosem'];
		$preventivosperifericos = $rowA['preventivosperifericos'];
		$correctivosperifericos = $rowA['correctivosperifericos'];
		$correctivosit			= $rowA['correctivosit'];
		$preventivosit 			= $rowA['preventivosit'];
		$pruebasdesempeno		= $rowA['pruebasdesempeno'];
		$postventas 			= $rowA['postventas']; 
		
	}
	
	$spreadsheet->getActiveSheet()->setCellValue('A7', 'Datos de Equipos Instalados (Mantenimientos)');
	$spreadsheet->getActiveSheet()->getStyle("A3")->getFont()->setBold(true)->setSize(12);
	$spreadsheet->getActiveSheet()->getStyle("A4")->getFont()->setBold(true)->setSize(12);
	$spreadsheet->getActiveSheet()->getStyle("A5")->getFont()->setBold(true)->setSize(12);
	$spreadsheet->getActiveSheet()->getStyle("A7")->getFont()->setBold(true)->setSize(12);


	$spreadsheet->getActiveSheet()->setCellValue('A8', 'Equipos Instalados')->setCellValue('B8', $equiposinstalados);
	$spreadsheet->getActiveSheet()->setCellValue('A9', 'Mantenimientos Preventivos EM')->setCellValue('B9',$preventivosem);
	$spreadsheet->getActiveSheet()->setCellValue('A10', 'Mantenimientos Correctivos/incidentes EM')->setCellValue('B10',$correctivosem);
	$spreadsheet->getActiveSheet()->setCellValue('A11', 'Mantenimientos Preventivos Perifericos')->setCellValue('B11',$preventivosperifericos);
	$spreadsheet->getActiveSheet()->setCellValue('A12', 'Mantenimientos Correctivos Perifericos')->setCellValue('B12',$correctivosperifericos);
	$spreadsheet->getActiveSheet()->setCellValue('A13', 'Mantenimientos Preventivos IT')->setCellValue('B13', $preventivosit);
	$spreadsheet->getActiveSheet()->setCellValue('A14','Mantenimientos Correctivos IT')->setCellValue('B14',$correctivosit);
	$spreadsheet->getActiveSheet()->setCellValue('A15','Pruebas de Desempeño')->setCellValue('B15',$pruebasdesempeno);
	$spreadsheet->getActiveSheet()->setCellValue('A16','Visitas Post Venta')->setCellValue('B16',$postventas);
	
	$spreadsheet->getActiveSheet()->setCellValue('A20', 'Equipos que han Estado Fuera de Servicio');
	$spreadsheet->getActiveSheet()->getStyle("A20")->getFont()->setBold(true)->setSize(12);


	$spreadsheet->getActiveSheet()
	->setCellValue('A22', 'EQUIPO')
	->setCellValue('B22', 'MARCA')
	->setCellValue('C22', 'UE')
	->setCellValue('D22', 'FECHA DOWN')		
	->setCellValue('E22', 'FECHA UP')
	->setCellValue('F22', 'TIEMPO TOTAL (días)')
	->setCellValue('G22', 'OBSERVACIONES')
	->setCellValue('H22', '');;

	$spreadsheet->getActiveSheet()->getStyle('A22:G22')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle("A22:G22")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->getStyle('A22:G22')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	
	//Consulta Equipos fuera de servicio
	$queryE = "	SELECT a.id, a.idactivos, b.nombre AS ambiente, c.nombre AS equipo, c.serie, c.activo, 
				d.nombre AS marca, e.desde, e.hasta 
				FROM incidentes a 
				INNER JOIN ambientes b ON b.id = a.idambientes 
				INNER JOIN activos c on c.id = a.idactivos AND c.idambientes = a.idambientes 
				INNER JOIN marcas d on c.idmarcas = d.id 
				INNER JOIN fueraservicio e on e.incidente = a.id
				WHERE 1 
				AND a.idclientes = 1 AND a.idproyectos IN (".$idproyectos.") AND fueraservicio = 1 ";
	if($idambientes != ''){
		$queryE .= " AND a.idambientes = ".$idambientes." ";
	} 
	if($fechadesdec != ''){
		$queryE .= " AND e.desde >= '".$fechadesdec."' ";
	}
	if($fechahastac != ''){
		$queryE .= " AND e.hasta <= '".$fechahastac."' ";
	}
	
	$queryE .= " GROUP BY a.idactivos ORDER BY a.id ";
	debugL("EQUIPOS FUERA DE SERVICIO:".$queryE);
	$i = 21;
	$resultE = $mysqli->query($queryE); 
	while($rowE = $resultE->fetch_assoc()){
		 
		//Observación
		$queryO = " SELECT comentario FROM comentarios WHERE idmodulo = ".$rowE['id']." ORDER BY id DESC LIMIT 1 ";
		$resultO = $mysqli->query($queryO); 
		if($rowO = $resultO->fetch_assoc()){
			$observacion = $rowO['comentario'];
		}
		if($rowE['desde'] != "" && $rowE['hasta'] == ""){
			$rowE['hasta']  = date("Y-m-d");
		} 
		
		if($rowE['desde'] == ""){
			$totaldias = "";
		}elseif($rowE['desde'] > $rowE['hasta'] ){
			$totaldias = "";
		}else{
			$totaldias = obtenerDiasLaborales($rowE['desde'],$rowE['hasta'] ,$festivos);
		}
		$spreadsheet->getActiveSheet()
		->setCellValue('A'.$i, $rowE['equipo'])
		->setCellValue('B'.$i, $rowE['marca'])
		->setCellValue('C'.$i, $rowE['ambiente'])
		->setCellValue('D'.$i, $rowE['desde'])		
		->setCellValue('E'.$i, $rowE['hasta'])
		->setCellValue('F'.$i, $totaldias)
		->setCellValue('G'.$i, $observacion)
		->setCellValue('H'.$i, " ");
		$i++;
	}
	//Ancho automatico	
	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(50);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(60);
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
	$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
	$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(40);
	$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(10);
	$hoy = date('dmY');
	$nombreArc = 'ReporteMensualDeClienteCSS - '.$hoy.'.xlsx';
	// redirect output to client browser
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Type: application/vnd.ms-excel'); //xls
	header('Content-Disposition: attachment;filename='.$nombreArc);
	header('Cache-Control: max-age=0');	
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');
	
	function obtenerDiasLaborales($startDate,$endDate,$holidays){ 
		// do strtotime calculations just once
		$endDate = strtotime($endDate);
		$startDate = strtotime($startDate);
		 
		
		//The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
		//We add one to inlude both dates in the interval.
		$days = ($endDate - $startDate) / 86400 + 1;

		$no_full_weeks = floor($days / 7);
		$no_remaining_days = fmod($days, 7);

		//It will return 1 if it's Monday,.. ,7 for Sunday
		$the_first_day_of_week = date("N", $startDate);
		$the_last_day_of_week = date("N", $endDate);

		//---->The two can be equal in leap years when february has 29 days, the equal sign is added here
		//In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
		if ($the_first_day_of_week <= $the_last_day_of_week) {
			if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
			if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
		}
		else {
			// (edit by Tokes to fix an Edge case where the start day was a Sunday
			// and the end day was NOT a Saturday)

			// the day of the week for start is later than the day of the week for end
			if ($the_first_day_of_week == 7) {
				// if the start date is a Sunday, then we definitely subtract 1 day
				$no_remaining_days--;

				if ($the_last_day_of_week == 6) {
					// if the end date is a Saturday, then we subtract another day
					$no_remaining_days--;
				}
			}
			else {
				// the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
				// so we skip an entire weekend and subtract 2 days
				$no_remaining_days -= 2;
			}
		}

		//The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
	//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
	   $workingDays = $no_full_weeks * 5;
		if ($no_remaining_days > 0 )
		{
		  $workingDays += $no_remaining_days;
		}

		//We subtract the holidays
		foreach($holidays as $holiday){
			$time_stamp=strtotime($holiday);
			//If the holiday doesn't fall in weekend
			if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
				$workingDays--;
		}

	  //  echo 'ret:'.$workingDays;
	  //debugL('$workingDays:'.$workingDays); 
		return $workingDays;
		
	}	
?>