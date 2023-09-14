<?php
    include_once("../conexion.php");

	$tarea = '';
	/*if (isset($_POST['tareaimp'])) {
		$tarea = $_POST['tareaimp'];   
	}*/

		if (isset($_GET['tareaimp'])) {
			$tarea = $_GET['tareaimp'];   
		}


	switch($tarea){
		case "ORDENMTTO": 
              //ordenmtto();
			  break;
		case "IMPORDENMTTO": 
              impordenmtto();
			  break;
		case "ACTAORDEN": 
              actaorden();
			  break;
		case "ACTAORDENXLS": 
              actaordenxls();
			  break;
		case "ACTAORDENPDF": 
              actaordenpdf();
			  break;
		case "ORDENPROJECT": 
              ordenproject();
			  break;
		case "IMPENTSAL": 
              impentsal();
			  break;
		case "IMPINCIDENTES": 
              impincidentes();
			  break;
		case "IMPINCIDENTESDET": 
              impincidentesdet();
			  break;
		case "IMPINCIDENTESVAN": 
              impincidentesvan();
			  break;
		case "IMPORDENES": 
              impordenes();
			  break;
		case "IMPORDENESDET": 
              impordenesdet();
			  break;
		case "IMPORDENESCOR": 
              impordenescor();
			  break;
		case "ReporteOrdenesXLS": 
              ReporteOrdenesXLS();
			  break;
		case "ReportIncidentesXLS": 
              ReportIncidentesXLS();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	

	function filtroReporte($servicio, $subservicio, $tipo, $sala, $buscar, $codigo='', $descripcion='') {
		$cadena = "Filtro del reporte:";
		
		if ($servicio!="")
				$cadena .= " Servicio: $servicio";
			
		if ($subservicio!="" && $subservicio!="-")
			$cadena .= " Subservicio: $subservicio";
		
		if ($tipo!="")
			$cadena .= " Tipo: $tipo";
		
		if ($sala!="")
			$cadena .= " Sala: $sala";
		
		if ($buscar!="")
			$cadena .= " Buscar: $buscar ";
		
		if ($codigo!="")
			$cadena .= utf8_decode(" CÃ³digo: $codigo ");
		
		if ($descripcion!="")
			$cadena .= utf8_decode(" DescripciÃ³n: $descripcion ");
		
		if ($cadena == "Filtro del reporte:")
			$cadena = "Filtro del reporte: (Sin Filtro) ";

		return utf8_decode($cadena);
	}
	
	function crearOrden($sistema, $frecuencia, $formulario, $plan, $servicio, $actividad) {
		global $mysqli;
		
		$fecha = date('Ymd');
		$crear = 0;
		
		$year=date('Y');
		$month=date('m');
		$day=date('d');
		$semana=date("W",mktime(0,0,0,$month,$day,$year));
		$diaSemana=date("w",mktime(0,0,0,$month,$day,$year));
		 
		$tipo = 'Preventiva';
		if (substr_count($servicio, 'OPERA') > 0)
			$tipo = 'Operacion';

		if ($frecuencia == 'Diaria') {
			$crear=1;
		} elseif (substr_count($frecuencia, 'Semanal') > 0) {
			if($diaSemana==1)
				$crear = 1;
		} elseif ($frecuencia == 'Quincenal') {
			if ($day == 1 || $day == 16 )
				$crear = 1;
		} elseif ($frecuencia == 'Mensual') {
			if ($day == 1)
				$crear = 1;
		} elseif ($frecuencia == 'Bimensual') {
			if ($day == 1 && ($month = 2 || $month = 4 || $month = 6 || $month = 8 || $month = 10 || $month = 12) )
				$crear = 1;
		} elseif ($frecuencia == 'Trimestral') {
			if ($day == 1 && ($month = 3 || $month = 6 || $month = 9 || $month = 12) )
				$crear = 1;
		} elseif ($frecuencia == 'Cuatrimestral') {
			if ($day == 1 && ($month = 4 || $month = 8 || $month = 12) )
				$crear = 1;
		}
		if ($crear==1) {
			$usuarioActual 	= $_SESSION['usuario'];
			$query = "Insert Into ordenes Values(null, '$fecha', '$sistema', '-', 'Creada', '-', '$tipo', '$frecuencia', '$formulario', $plan, '$actividad', '$servicio', '$fecha', 0, '$sector', '$edificio', '$usuarioActual','-','5')";
			if ($consulta = $mysqli->query($query))
				return true;
			else 
				return false;
		}	
	}
	
	/*function ordenmtto() {
		global $mysqli;
		
		$sistema = (!empty($_POST['sistema']) ? $_POST['sistema'] : '');
		$buscar = (!empty($_POST['buscar']) ? $_POST['buscar'] : '');
		$frecuencia = (!empty($_POST['frecuencia']) ? $_POST['frecuencia'] : '');
		
		$query  = "SELECT m.* ";
		$query .= "FROM plan m ";
		// $query .= "INNER JOIN equipos e on e.serial = m.serial ";
		$query .= "WHERE 1=1 ";
		
		if ($sistema!="")
			$query .= "AND m.sistema = '$componente' ";
		
		if ($buscar!="")
			$query .= "AND m.actividad LIKE '%$buscar%' ";
			
		if ($frecuencia!="")
			$query .= "AND m.frecuencia = '$frecuencia' ";
		
		$fecha = date("Ymd");
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;	
		$total = 0;
		if($nbrows>0){
			$f = fopen('ordenes.txt', 'w');
			fclose($f);
			while ($registro=$result->fetch_assoc())  {
				if (crearOrden($registro['sistema'], $registro['frecuencia'], $registro['formulario'], $registro['id'], $registro['servicio'], $registro['actividad']))
					$total++;
			}
			echo '({"success":"true", "total":'.$total.', "results":""})';
		} else {
			echo '({"success":"false", "total":"0", "results":""})';
		}
	}
	*/
	
	function impordenmtto() {
		global $mysqli;
		require_once('fpdf17/fpdf.php');
		
		$numero = $_POST['numero'];
		
		$query  = "SELECT * ";
		$query .= "FROM incidentes ";
		$query .= "WHERE numero=$numero ";
		
		class PDF extends FPDF
		{
			public $servicio = '';
			public $sistema = '';
			public $actividad = '';
			public $numeroOrden = '';
			public $fecha = '';
			
			function Header()
			{
				$this->Image('images/mingob.png',10,8,22);
				$this->Image('images/logo-pen.png',180,8,18);
				$this->SetFont('Arial','B',12);
				$this->Cell(90);
				$this->Cell(60,6,'',0,1,'C');
				$this->Cell(0,6,'MINISTERIO DE GOBIERNO',0,1,'C');
				$this->Cell(0,6,'DIRECCION GENERAL DEL SISTEMA PENITENCIARIO',0,1,'C');
				$this->Cell(0,6,'ORDEN DE TRABAJO DE MANTENIMIENTO CORRECTIVO',0,1,'C');
				$this->SetFont('Arial','B',10);
				$this->Ln(3);
				$this->Cell(60,6,'Fecha: '.$this->fecha,1,0);
				$this->Cell(60,6,'Orden Nro.: '.ceros($this->numeroOrden,8),1,0,'C');
				$this->Cell(76,6,'F-MAXIA-TK-LNJ-02',1,1,'C');
				$this->Cell(0,7,'Servicio: '.utf8_decode($this->servicio),1,1);
				$this->Cell(0,7,'Sistema: '.utf8_decode($this->sistema),1,1);
				$this->Cell(0,7,'Actividad: '.utf8_decode($this->actividad),1,1);
				$this->Ln(3);
			}
			
			function Footer()
			{
				$this->SetY(-15);
				$this->SetFont('Arial','I',8);
				$this->Cell(65,5,'Realizado por:___________________________',0,0,'C');
				$this->Cell(66,5,'Firma Maxia:___________________________',0,0,'C');
				$this->Cell(65,5,'Firma DGSP/DAEI - LNJ:___________________________',0,1,'C');
				$this->Cell(0,5,utf8_decode('Ministerio de Gobierno - DirecciÃ³n General del Sistema Penitenciario - Maxia Latam - Pag. '.$this->PageNo().'/{nb}'),0,0,'C');
			}
		}
		
		$pdf = new PDF('P', 'mm', 'letter');
		$pdf->AliasNbPages();
		
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;	
		$registro=$result->fetch_assoc();
		if($nbrows>0){
			$pdf->SetFont('Arial','',7);
			$auxServicio="";
			$item=0;
			$fecha = date('Ymd');
			$sistema = $registro['sistema'];
			$descripcion = $registro['descripcion'];
			$servicio = $registro['servicio'];
			if ($servicio=='')
				$servicio = 'SERVICIO - 03: MANTENIMIENTO DE ACTIVOS (PREVENTIVOS & CORRECTIVOS)';
			$actividad = $registro['actividad'];
			$responsable = $registro['responsable'];
			$observaciones = $registro['observaciones'];
			
			
			
			$pdf->servicio = $servicio;
			$pdf->sistema = $sistema;
			$pdf->actividad = $actividad;
			$pdf->fecha = $fecha;
			
			$query3 = "Insert Into ordenes Values(null, '$fecha', '$sistema', '$responsable', 'Creada', '$descripcion', 'Correctiva', '-', 'ORDEN CORRECTIVA', $numero, '$actividad', '$servicio', '$fecha', 0, 0, 0, '-', '-', '4')";
			$consulta3 = $mysqli->query($query3);
			$pdf->numeroOrden = $mysqli->insert_id;
			
			$query2 = "Update incidentes set estatus = 'Creada' where  numero = $numero";
			$consulta2 = $mysqli->query($query2);
			
			
			$pdf->AddPage();
			$pdf->SetFont('Times','',7);
			$result = $mysqli->query($query);
			$nbrows = $result->num_rows;	
		
			if ($registro=$result->fetch_assoc())  {
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(0,8,utf8_decode('DescripciÃ³n'),1,1);
				$pdf->SetFont('Arial','',10);
				$pdf->MultiCell(0,25,utf8_decode($descripcion),1);
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(0,8,utf8_decode('Observaciones'),1,1);
				$pdf->SetFont('Arial','',10);
				$pdf->MultiCell(0,25,utf8_decode($observaciones),1);
			}
			srand (time());
			$num = rand(1,1000); 
			$fecha = date("Ymd");
			$nombrePDF = "listados/orden-".$num."-".$fecha.".pdf";
			$pdf->Output($nombrePDF, "F");
			$_SESSION['pdf']=$nombrePDF;
			echo $nombrePDF;
		} else {
			echo '({"total":"0", "results":""})';
		}
	}
	
	function actaorden() {
		global $mysqli;
		require_once('fpdf17/fpdf.php');
		
		$componente = (!empty($_POST['componente']) ? $_POST['componente'] : '');
		$buscar = (!empty($_POST['buscar']) ? $_POST['buscar'] : '');
		$desde = (!empty($_POST['desde']) ? $_POST['desde'] : '');
		$hasta = (!empty($_POST['hasta']) ? $_POST['hasta'] : '');
		
		$query  = "SELECT numero, sistema, DATE_FORMAT(fecha,'%d/%m/%Y') as fecha, estatus, tipo ";
		$query .= "FROM ordenes  ";
		$query .= "WHERE estatus in ('Finalizada', 'Parcial', 'Por Firmar') ";
		
		if ($componente!="")
			$query .= "AND sistema = '$componente' ";
		
		if ($buscar!="")
			$query .= "AND actividad LIKE '%$buscar%' ";
			
		if ($desde!="")
			$query .= "AND fecha >= '$desde' ";
		
		if ($hasta!="")
			$query .= "AND fecha <= '$hasta' ";
		
		class PDF extends FPDF
		{
			public $desde = '';
			public $hasta = '';
			
			function Header()
			{
				$this->Image('images/mingob.png',10,8,22);
				$this->Image('images/logo-pen.png',180,8,18);
				$this->SetFont('Arial','B',12);
				$this->Cell(90);
				$this->Cell(60,6,'',0,1,'C');
				$this->Cell(0,6,'MINISTERIO DE GOBIERNO',0,1,'C');
				$this->Cell(0,6,'DIRECCION GENERAL DEL SISTEMA PENITENCIARIO',0,1,'C');
				$this->Cell(0,6,'ACTA DE SERVICIOS REALIZADOS',0,1,'C');
				$this->SetFont('Arial','B',10);
				$this->Ln(3);
				$this->Cell(60,6,'Desde: '.$this->desde,1,0);
				$this->Cell(60,6,'Hasta: '.$this->hasta,1,0);
				$this->Cell(76,6,'F-MAXIA-TK-LNJ-03',1,1,'C');
				$this->Ln(3);
				$this->SetFont('Arial','B',7);
				$this->Cell(7,10,'Item',1,0,'C');
				$this->Cell(19,10,utf8_decode('NÃºmero Ord.'),1,0,'C');
				$this->Cell(100,10,'Sistema',1,0,'C');
				$this->Cell(20,10,'Fecha',1,0,'C');
				$this->Cell(20,10,'Estatus',1,0,'C');
				$this->Cell(20,10,'Tipo',1,0,'C');
				$this->Cell(10,10,'Chk',1,1,'C');
				$this->SetFont('Arial','',8);
			}
			
			function Footer()
			{
				$this->SetY(-15);
				$this->SetFont('Arial','I',8);
				$this->Cell(98,5,'Firma Maxia:___________________________',0,0,'C');
				$this->Cell(98,5,'Firma DGSP/DAEI - LNJ:___________________________',0,1,'C');
				$this->Cell(0,5,utf8_decode('MInisterio de Gobierno - DirecciÃ³n General del Sistema Penitenciario - Maxia Latam - Pag. '.$this->PageNo().'/{nb}'),0,0,'C');
			}
		}
		
		$pdf = new PDF('P', 'mm', 'letter');
		$pdf->AliasNbPages();
		
		
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;	
		if($nbrows>0){
			$item=0;
			$date1 = new DateTime($desde);
			$date2 = new DateTime($hasta);
			$pdf->desde = $date1->format('d/m/Y');
			$pdf->hasta = $date2->format('d/m/Y');
			$pdf->AddPage();
			$pdf->SetFont('Times','',7);
			while ($registro=$result->fetch_assoc())  {
				$item++;
				$pdf->Cell(7,7,$item,1,0,'C');
				$pdf->Cell(19,7,$registro['numero'],1,0,'C');
				$pdf->Cell(100,7,utf8_decode($registro['sistema']),1,0);
				$pdf->Cell(20,7,$registro['fecha'],1,0,'C');
				$pdf->Cell(20,7,$registro['estatus'],1,0,'C');
				$pdf->Cell(20,7,$registro['tipo'],1,0,'C');
				$pdf->Cell(10,7,'',1,1,'C');
			}
			$pdf->Ln(5);
			$pdf->SetFont('Arial','B',10);
			srand (time());
			$num = rand(1,1000); 
			$fecha = date("Ymd");
			$nombrePDF = "listados/acta-".$num."-".$fecha.".pdf";
			$pdf->Output($nombrePDF, "F");
			$_SESSION['pdf']=$nombrePDF;
			echo $nombrePDF;
		} else {
			echo '({"total":"0", "results":""})';
		}
	}
	
	function valoresActa($numero) {
		$valor = '';
		switch ($numero) {
			case '1.1':
				$valor = "18%";
				break;
			case '1.3':
				$valor = "14%";
				break;
			case '1.4':
				$valor = "02%";
				break;
			case '1.5':
				$valor = "08%";
				break;
			case '2.1':
				$valor = "06%";
				break;
			case '3.1':
				$valor = "15%";
				break;
			case '3.2':
				$valor = "10%";
				break;
			case '3.3':
				$valor = "15%";
				break;
			case '4.1':
				$valor = "12%";
				break;
			default:
				$valor = "";
		}
		return $valor;
	}
	
	function actaordenxls()
	{
		global $mysqli;
		
		$desde = (!empty($_GET['desde']) ? $_GET['desde'] : '');
		$hasta = (!empty($_GET['hasta']) ? $_GET['hasta'] : '');
		
		$query = "SELECT servicio, sistema, ";
		$query .= "SUBSTRING(actividad,1,CASE WHEN INSTR(actividad, ' - Sector') > 0 THEN INSTR(actividad, ' - Sector') ELSE CASE WHEN INSTR(actividad, ' - Cust') > 0 THEN INSTR(actividad, ' - Cust') ELSE LENGTH(actividad) END END) as actividad, ";
		$query .= "count(numero) as cantidad, sum(case when estatus = 'Finalizada' or estatus = 'Por Firmar' then 1 else 0 end) as finalizadas ";
		$query .= "FROM ordenes ";
		$query .= "WHERE tipo in ('Operacion','Preventiva') and estatus <> 'Cancelada'  ";
		
		if ($desde!="")
			$query .= "AND fecha >= '$desde' ";
		
		if ($hasta!="")
			$query .= "AND fecha <= '$hasta' ";
		
		$query .= "GROUP BY servicio, sistema, SUBSTRING(actividad,1,CASE WHEN INSTR(actividad, ' - Sector') > 0 THEN INSTR(actividad, ' - Sector') ELSE CASE WHEN INSTR(actividad, ' - Cust') > 0 THEN INSTR(actividad, ' - Cust') ELSE LENGTH(actividad) END END) ";
		
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;	
		
		$xdesde =date_format(date_create($desde),"d/m/Y");
		$xhasta =date_format(date_create($hasta),"d/m/Y");
		
		require_once '../xls/Classes/PHPExcel.php';
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Maxia.Toolkit")
									 ->setLastModifiedBy("Maxia.Toolkit")
									 ->setTitle("Registro de Actividades")
									 ->setSubject("Office 2007 XLSX")
									 ->setDescription("Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Reporte");
									 									 
		
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(8); 
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A2', 'REGISTRO DE ACTIVIDADES')
					->setCellValue('A3', 'COMPLEJO PENITENCIARIO LA NUEVA JOYA')
					->setCellValue('A4', 'PRESENTACIÓN DE CUENTA E INDICADORES')
					->setCellValue('A5', 'PERÍODO DESDE: '. $xdesde . ' HASTA: '. $xhasta)
					->setCellValue('A7', 'Nro.')
					->setCellValue('B7', 'Concepto')
					->setCellValue('C7', 'Órdenes Creadas')
					->setCellValue('D7', 'Órdenes Finalizadas')
					->setCellValue('E7', '% Cumplimiento')
					->setCellValue('F7', 'Observaciones');
					
		$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
		$objPHPExcel->getActiveSheet()->mergeCells('A3:F3');
		$objPHPExcel->getActiveSheet()->mergeCells('A4:F4');
		$objPHPExcel->getActiveSheet()->mergeCells('A5:F5');
		$objPHPExcel->getActiveSheet()->getStyle('A2:F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A2:F7')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);		
	    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(130);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
		
		$objPHPExcel->getActiveSheet()->getStyle('A7:F7')->getAlignment()->setWrapText(true);
		
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Maxia');
		$objDrawing->setDescription('Maxia');
		$objDrawing->setPath('../images/maxia.png');
		//$objDrawing->setHeight(36);
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		$objDrawing->setCoordinates('A1');
		
		
		$objDrawing2 = new PHPExcel_Worksheet_Drawing();
		$objDrawing2->setName('Mingob');
		$objDrawing2->setDescription('Mingob');
		$objDrawing2->setPath('../images/mingob.png');
		//$objDrawing2->setHeight(36);
		$objDrawing2->setWorksheet($objPHPExcel->getActiveSheet());
		$objDrawing2->setCoordinates('F1');
		$objDrawing2->setOffsetX(20);
		
		$objPHPExcel->getActiveSheet()->getStyle('A7:F7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$objPHPExcel->getActiveSheet()->getStyle('D8:F3000')->getNumberFormat()->setFormatCode('#,##0');
		$objPHPExcel->getActiveSheet()->getStyle('A8:F8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(8); 

		$fila=8;
		$nservicio = 1;
		$nsistema  = 1;
		$registro=$result->fetch_assoc();
		
		$servicioactual = $registro['servicio'];
		$sistemaactual = $registro['sistema'];
		
		$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getFill()->getStartColor()->setARGB('91376091');
		$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("A$fila", $nservicio)
					->setCellValue("B$fila", $registro['servicio']);
		$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOTTED);
		$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$fila++;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("A$fila", "$nservicio.$nsistema")
					->setCellValue("B$fila", $registro['sistema']);
		$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOTTED);
		$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$fila++;
		$result = $mysqli->query($query);
		while ($registro=$result->fetch_assoc())  {
			if ($servicioactual != $registro['servicio']) {
				$servicioactual = $registro['servicio'];
				$sistemaactual = $registro['sistema'];
				$nservicio++;
				$nsistema = 1;
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getFill()->getStartColor()->setARGB('91376091');
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue("A$fila", $nservicio)
							->setCellValue("B$fila", $registro['servicio']);
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOTTED);
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$fila++;
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue("A$fila", "$nservicio.$nsistema")
							->setCellValue("B$fila", $registro['sistema']);
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOTTED);
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$fila++;
			}
			if ($sistemaactual != $registro['sistema']) {
				$sistemaactual = $registro['sistema'];
				$nsistema++;
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue("A$fila", "$nservicio.$nsistema")
							->setCellValue("B$fila", $registro['sistema']);
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOTTED);
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$fila++;
			}
			if ($registro['cantidad'] > 0)
				$porc = $registro['finalizadas'] * 100 / $registro['cantidad'];
			else 
				$porc = 0;
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("B$fila", $registro['actividad'])
					->setCellValue("C$fila", ' ')
					->setCellValue("C$fila", $registro['cantidad'])
					->setCellValue("D$fila", $registro['finalizadas'])
					->setCellValue("E$fila", $porc);
			$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOTTED);
			$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			$fila++;
		}
		$objPHPExcel->getActiveSheet()->getStyle("A$fila:F$fila")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Presentación de Cuenta');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		
		srand (time());
		$num = rand(1,1000); 
		$fecha = date("Ymd");
		//$nombreXLS = "ordenes/acta-".$num.'-'.$fecha.".xlsx";
		//$_SESSION['xls']=$nombreXLS;
		//$objWriter->save($nombreXLS);
		//echo $nombreXLS;

		//Redirigir la salida al navegador del cliente	
		header('charset=utf-8');	
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Acta_'.$num.'_'.$fecha.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}
	
	function actaordenpdf() {
		global $mysqli;
		require_once('../fpdf/fpdf.php');
		
		$sistema = (!empty($_GET['componente']) ? $_GET['componente'] : '');
		//$actividad = (!empty($_REQUEST['actividad']) ? $_REQUEST['actividad'] : '');
		$buscar = (!empty($_GET['buscar']) ? $_GET['buscar'] : '');
		$desde = (!empty($_GET['desde']) ? $_GET['desde'] : '');
		$hasta = (!empty($_GET['hasta']) ? $_GET['hasta'] : '');
		//$estado = (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : '');
		//$responsable = (!empty($_POST['responsable']) ? $_POST['responsable'] : '');
		
		$query = "SELECT servicio, sistema, ";
		$query .= "SUBSTRING(actividad,1,CASE WHEN INSTR(actividad, ' - Sector') > 0 THEN INSTR(actividad, ' - Sector') ELSE CASE WHEN INSTR(actividad, ' - Cust') > 0 THEN INSTR(actividad, ' - Cust') ELSE LENGTH(actividad) END END) as actividad, ";
		$query .= "count(numero) as cantidad, sum(case when estatus = 'Finalizada' or estatus = 'Por Firmar' then 1 else 0 end) as finalizadas ";
		$query .= "FROM ordenes ";
		$query .= "WHERE tipo in ('Operacion','Preventiva') and estatus <> 'Cancelada'  ";
		
		if ($desde!="")
			$query .= "AND fecha >= '$desde' ";
		
		if ($hasta!="")
			$query .= "AND fecha <= '$hasta' ";
		
		$query .= "GROUP BY servicio, sistema, SUBSTRING(actividad,1,CASE WHEN INSTR(actividad, ' - Sector') > 0 THEN INSTR(actividad, ' - Sector') ELSE CASE WHEN INSTR(actividad, ' - Cust') > 0 THEN INSTR(actividad, ' - Cust') ELSE LENGTH(actividad) END END) ";
		
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;	
		
		$xdesde =date_format(date_create($desde),"d/m/Y");
		$xhasta =date_format(date_create($hasta),"d/m/Y");
		
		class PDF extends FPDF
		{
			var $desde, $hasta;
			
			function Header()
			{
				$this->Image('../images/mingob.png',10,8,22);
				$this->Image('../images/maxia.png',230,8,18);
				$this->SetFont('Arial','B',12);
				$this->Cell(90);
				$this->Cell(60,6,'',0,1,'C');
				$this->Cell(0,6,'MINISTERIO DE GOBIERNO',0,1,'C');
				$this->Cell(0,6,'DIRECCION GENERAL DEL SISTEMA PENITENCIARIO',0,1,'C');
				$this->Cell(0,6,utf8_decode('PRESENTACIÓN DE CUENTA E INDICADORES'),0,1,'C');
				$this->Cell(0,6,utf8_decode('PERÍODO DESDE:'.$this->desde.' - HASTA:'.$this->hasta),0,1,'C');
				$this->SetFont('Arial','B',10);
				$this->Ln(3);
				$this->Cell(0,6,'F-MAXIA-TK-LNJ-110',1,1,'R');
				$this->Ln(3);
				$this->SetFont('Arial','B',7);
				$this->Cell(6,6,'Nro.',1,0,'C');
				$this->Cell(201,6,'Concepto',1,0,'C');
				$this->Cell(17,6,utf8_decode('Creadas'),1,0,'C');
				$this->Cell(17,6,utf8_decode('Finalizadas'),1,0,'C');
				$this->Cell(18,6,utf8_decode('% Cump.'),1,1,'C');			
			}
			
			function Footer()
			{
				$this->SetY(-15);
				$this->SetFont('Arial','I',8);
				$this->Cell(0,5,utf8_decode('Ministerio de Gobierno - Dirección General del Sistema Penitenciario - Maxia Latam - Pag. '.$this->PageNo().'/{nb}'),0,0,'C');
			}
		}
		
		$pdf = new PDF('L', 'mm', 'letter');
		$pdf->AliasNbPages();
		//$pdf->SetLineWidth(0.1);
		
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;	
		//if($nbrows>0){
			$pdf->SetFont('Arial','',7);
			$pdf->desde=$xdesde;
			$pdf->hasta=$xhasta;
			$auxServicio="";
			$item=0;
			$pdf->AddPage();
			$pdf->SetFont('Times','',7);
			$nservicio = 1;
			$nsistema  = 1;
			$registro=$result->fetch_assoc();
			
			$servicioactual = $registro['servicio'];
			$sistemaactual = $registro['sistema'];
			$pdf->SetFont('Times','B',7);
			$pdf->Cell(6,7,$nservicio,1,0);
			$pdf->Cell(253,7,utf8_decode($registro['servicio']),1,1);
			$pdf->Cell(6,6,$nservicio.'.'.$nsistema,1,0);
			$pdf->Cell(253,6,utf8_decode($registro['sistema']),1,1);
			$pdf->SetFont('Times','',7);
			$result = $mysqli->query($query);
			while ($registro=$result->fetch_assoc())  {
				if ($servicioactual != $registro['servicio']) {
					$servicioactual = $registro['servicio'];
					$sistemaactual = $registro['sistema'];
					$nservicio++;
					$nsistema = 1;
					$pdf->SetFont('Times','B',7);
					$pdf->Cell(6,7,$nservicio,1,0);
					$pdf->Cell(253,7,utf8_decode($registro['servicio']),1,1);
					$pdf->Cell(6,6,$nservicio.'.'.$nsistema,1,0);
					$pdf->Cell(253,6,utf8_decode($registro['sistema']),1,1);
					$pdf->SetFont('Times','',7);
				}
				if ($sistemaactual != $registro['sistema']) {
					$sistemaactual = $registro['sistema'];
					$nsistema++;
					$pdf->SetFont('Times','B',7);
					$pdf->Cell(6,6,$nservicio.'.'.$nsistema,1,0);
					$pdf->Cell(253,6,utf8_decode($registro['sistema']),1,1);
					$pdf->SetFont('Times','',7);
				}
				if ($registro['cantidad']>0)
					$porc = number_format($registro['finalizadas'] * 100 / $registro['cantidad'], 2, ',', '.');
				else
					$porc = 0;
				$pdf->Cell(6,6,'',1,0,'C');
				$pdf->Cell(201,6,ucwords(strtolower(utf8_decode(substr($registro['actividad'],0,190)))),1,0);
				$pdf->Cell(17,6,$registro['cantidad'],1,0,'R');
				$pdf->Cell(17,6,$registro['finalizadas'],1,0,'R');
				$pdf->Cell(18,6,$porc,1,1,'R');
				//$directorios[] = 'ordenes/'.$registro['numero'];			
			}
			
			$query = "SELECT numero ";
			$query .= "FROM ordenes ";
			$query .= "WHERE tipo in ('Operacion','Preventiva') and estatus <> 'Cancelada'  ";
			
			if ($desde!="")
				$query .= "AND fecha >= '$desde' ";
			
			if ($hasta!="")
				$query .= "AND fecha <= '$hasta' ";
			
			$result = $mysqli->query($query);
			//debug($query);
			$nbrows = $result->num_rows;	
			$directorios = array();
			if($nbrows>0){
				while ($registro=$result->fetch_assoc())  {
					$directorios[] = 'ordenes/'.$registro['numero'];
				}
			}
			
			
			$pdf->Ln(5);
			$pdf->SetFont('Arial','B',10);
			srand (time());
			$num = rand(1,1000); 
			$fecha = date("Ymd");
			/*$nombrePDF = "listados/ordenes-".$num."-".$fecha.".pdf";
			//generate_pub_pdf($directorios,$nombrePDF);
			$pdf->Output($nombrePDF, "F");
			$_SESSION['pdf']=$nombrePDF;
			$salidaPDF = unirevidencias($directorios,$nombrePDF);
			echo $salidaPDF;*/
			$nombrePDF ="Acta_".$num."_".$fecha.".pdf";			
			$pdf->Output($nombrePDF, "D");
		/*} else {
			echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
		}*/
	}
	
	
	function ordenproject()
	{
		global $mysqli;
		
		$desde = (!empty($_POST['desde']) ? $_POST['desde'] : '');
		$hasta = (!empty($_POST['hasta']) ? $_POST['hasta'] : '');
		
		$query = "SELECT actividad, ";
		$query .= "DATE_FORMAT(fecha,'%m/%d/%Y') as fecha, proveedor as responsable, case when estatus = 'Finalizada' then 1 else 0 end as avance ";
		$query .= "FROM ordenes ";
		$query .= "WHERE 1 = 1 ";
		
		if ($desde!="")
			$query .= "AND fecha >= '$desde' ";
		
		if ($hasta!="")
			$query .= "AND fecha <= '$hasta' ";
		
		$xdesde =date_format(date_create($desde),"d/m/Y");
		$xhasta =date_format(date_create($hasta),"d/m/Y");
		
		require_once 'xls/Classes/PHPExcel.php';
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Maxia.Toolkit")
									 ->setLastModifiedBy("Maxia.Toolkit")
									 ->setTitle("Registro de Actividades")
									 ->setSubject("Office 2007 XLSX")
									 ->setDescription("Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Reporte");
									 									 
		
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(8); 
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Nombre')
					->setCellValue('B1', 'Comienzo')
					->setCellValue('C1', 'Fin')
					->setCellValue('D1', 'Nombres de los recursos')
					->setCellValue('E1', '% completado')
					->setCellValue('F1', 'DuraciÃ³n');
					
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(100);		
	    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(100);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
	    
		$fila=2;
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;
		$id = 1;		
		while ($registro=$result->fetch_assoc())  {
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("A$fila", $registro['actividad'])
					->setCellValue("B$fila", $registro['fecha'])
					->setCellValue("C$fila", $registro['fecha'])
					->setCellValue("D$fila", $registro['responsable'])
					->setCellValue("E$fila", $registro['avance'])
					->setCellValue("F$fila", 1);
			$fila++;
		}
		$objPHPExcel->getActiveSheet()->getStyle("A$fila:G$fila")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$objPHPExcel->getActiveSheet()->setTitle('MSProject');
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		
		srand (time());
		$num = rand(1,1000); 
		$fecha = date("Ymd");
		$nombreXLS = "ordenes/exportarproject-".$fecha.".xlsx";
		$_SESSION['xls']=$nombreXLS;
		$objWriter->save($nombreXLS);
		echo $nombreXLS;
	}
	
	function ordenprojectxml()
	{
		global $mysqli;
		
		$desde = (!empty($_POST['desde']) ? $_POST['desde'] : '');
		$hasta = (!empty($_POST['hasta']) ? $_POST['hasta'] : '');
		
		$query = "SELECT servicio, sistema, actividad, ";
		$query .= "date(fecha) as fecha, proveedor as responsable, case when estatus = 'Finalizada' then 100 else 0 end as avance ";
		$query .= "FROM ordenes ";
		$query .= "WHERE 1 = 1 ";
		
		if ($desde!="")
			$query .= "AND fecha >= '$desde' ";
		
		if ($hasta!="")
			$query .= "AND fecha <= '$hasta' ";
		
		$fila=2;
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;
		$id = 1;
		$f = fopen("ordenes/project.xml", "w");
		fwrite($f, "<?xml version='1.0'?>\r\n");
		fwrite($f, "<Datos>\r\n");
		while ($registro=$result->fetch_assoc())  {
			fwrite($f, "<Dato>\r\n");
			fwrite($f, "<Id>".$id++."</Id>\r\n");
			fwrite($f, "<Servicio>".$registro["servicio"]."</Servicio>\r\n");
			fwrite($f, "<Sistema>".$registro["sistema"]."</Sistema>\r\n");
			fwrite($f, "<Actividad>".$registro["actividad"]."</Actividad>\r\n");
			fwrite($f, "<Fecha>".$registro["fecha"]."</Fecha>\r\n");
			fwrite($f, "<Responsable>".$registro["responsable"]."</Responsable>\r\n");
			fwrite($f, "<Avance>".$registro["avance"]."</Avance>\r\n");
			fwrite($f, "</Dato>\r\n");
		}
		fwrite($f, "</Datos>\r\n");
		fclose($f);
		$fecha = date("Ymd");
		$nombreXML = "ordenes/project.xml";
		echo $nombreXML;
	}
	
	function impentsal() {
		global $mysqli;
		require_once('fpdf17/fpdf.php');
		
		$numero = $_POST['numero'];
		
		$query  = "SELECT es.numero, DATE_FORMAT(es.fecha,'%d/%m/%Y') as fecha, es.tipo, es.movimiento, ";
		$query  .= "esd.codigo, esd.nombre, esd.marca, esd.unidad, esd.cantidad ";
		$query .= "FROM entsal es ";
		$query .= "INNER JOIN entsaldetalle esd on esd.numero = es.numero ";
		$query .= "WHERE es.numero=$numero ";
		
		class PDF extends FPDF
		{
			public $movimiento = '';
			public $numero = '';
			public $fecha = '';
			
			function Header()
			{
				$this->Image('images/mingob.png',10,8,22);
				$this->Image('images/logo-pen.png',280,8,18);
				$this->SetFont('Arial','B',12);
				$this->Cell(90);
				$this->Cell(60,6,'',0,1,'C');
				$this->Cell(0,6,'MINISTERIO DE GOBIERNO',0,1,'C');
				$this->Cell(0,6,'DIRECCION GENERAL DEL SISTEMA PENITENCIARIO',0,1,'C');
				$this->Cell(0,6,'MOVIMIENTO DE INVENTARIO',0,1,'C');
				$this->SetFont('Arial','B',10);
				$this->Ln(3);
				$this->Cell(49,6,'Fecha: '.$this->fecha,1,0);
				$this->Cell(49,6,'Tipo: '.$this->movimiento,1,0,'C');
				$this->Cell(49,6,'Nro.: '.ceros($this->numero,8),1,0,'C');
				$this->Cell(49,6,'F-MAXIA-TK-LNJ-04',1,1,'C');
				$this->Ln(3);
				$this->SetFont('Arial','B',7);
				$this->Cell(7,10,'Item',1,0,'C');
				$this->Cell(70,10,utf8_decode('CÃ³digo'),1,0,'C');
				$this->Cell(300,10,'Nombre',1,0,'C');
				$this->Cell(10,10,'Marca',1,0,'C');
				$this->Cell(14,10,'Unidad',1,0,'C');
				$this->Cell(15,10,'Cantidad',1,1,'C');
				$this->SetFont('Arial','',8);
			}
			
			function Footer()
			{
				$this->SetY(-15);
				$this->SetFont('Arial','I',8);
				$this->Cell(98,5,'Firma Maxia:___________________________',0,0,'C');
				$this->Cell(98,5,'Firma Responsable:___________________________',0,1,'C');
				$this->Cell(0,5,utf8_decode('MInisterio de Gobierno - DirecciÃ³n General del Sistema Penitenciario - Maxia Latam - Pag. '.$this->PageNo().'/{nb}'),0,0,'C');
			}
		}
		
		$pdf = new PDF('P', 'mm', 'letter');
		$pdf->AliasNbPages();
		
		
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;	
		$registro=$result->fetch_assoc();
		if($nbrows>0){
			$pdf->SetFont('Arial','',7);
			$auxServicio="";
			$item=0;
			$pdf->movimiento = $registro['movimiento'];
			$pdf->numero = $registro['numero'];
			$pdf->fecha = $registro['fecha'];
			$pdf->AddPage();
			$pdf->SetFont('Times','',7);
			$result = $mysqli->query($query);
			$nbrows = $result->num_rows;	
		
			while ($registro=$result->fetch_assoc())  {
				$item++;
				$pdf->Cell(7,7,$item,1,0,'C');
				$pdf->Cell(70,7,utf8_decode($registro['codigo']),1,0);
				$pdf->Cell(300,7,utf8_decode($registro['nombre']),1,0);
				$pdf->Cell(10,7,utf8_decode($registro['marca']),1,0,'C');
				$pdf->Cell(14,7,$registro['unidad'],1,0,'C');
				$pdf->Cell(15,7,$registro['cantidad'],1,1,'R');
			}
			$pdf->Ln(5);
			$pdf->SetFont('Arial','B',10);
			srand (time());
			$num = rand(1,1000); 
			$fecha = date("Ymd");
			$nombrePDF = "listados/entsal-".$num."-".$fecha.".pdf";
			$pdf->Output($nombrePDF, "F");
			$_SESSION['pdf']=$nombrePDF;
			echo $nombrePDF;
		} else {
			echo '({"total":"0", "results":""})';
		}
	}
	
	function impincidentes() {
		global $mysqli;
		require_once('../fpdf/fpdf.php');
		
		$sistema = (!empty($_GET['componente']) ? $_GET['componente'] : '');
		$buscar = (!empty($_GET['buscar']) ? $_GET['buscar'] : '');
		$desde = (!empty($_GET['desde']) ? $_GET['desde'] : '');
		$hasta = (!empty($_GET['hasta']) ? $_GET['hasta'] : '');
		
		$query  = "SELECT numero, sistema, DATE_FORMAT(fecha,'%d/%m/%Y') as fecha, estatus, descripcion, sector, edificio ";
		$query .= "FROM incidentes  ";
		$query .= "WHERE 1 = 1 ";
		
		if ($sistema!="")
			$query .= "AND sistema = '$sistema' ";
		
		if ($buscar!="")
			$query .= "AND descripcion LIKE '%$buscar%' ";
			
		if ($desde!="")
			$query .= "AND fecha >= '$desde' ";
		
		if ($hasta!="")
			$query .= "AND fecha <= '$hasta' ";
		
		class PDF extends FPDF
		{
			function Header()
			{
				$this->Image('../images/mingob.png',10,8,22);
				$this->Image('../images/maxia.png',250,8,18);
				$this->SetFont('Arial','B',12);
				$this->Cell(90);
				$this->Cell(60,6,'',0,1,'C');
				$this->Cell(0,6,'MINISTERIO DE GOBIERNO',0,1,'C');
				$this->Cell(0,6,'DIRECCION GENERAL DEL SISTEMA PENITENCIARIO',0,1,'C');
				$this->Cell(0,6,'LISTADO DE INCIDENTES',0,1,'C');
				$this->SetFont('Arial','B',10);
				$this->Ln(3);
				$this->Cell(0,6,'F-MAXIA-TK-LNJ-05',1,1,'R');
				$this->Ln(3);
				$this->SetFont('Arial','B',7);
				$this->Cell(7,10,'Item',1,0,'C');
				$this->Cell(60,10,'Sistema',1,0,'C');
				$this->Cell(14,10,'Fecha',1,0,'C');
				$this->Cell(10,10,'Estado',1,0,'C');
				$this->Cell(150,10,utf8_decode('Descripción'),1,0,'C');
				$this->Cell(20,10,utf8_decode('Ubicación'),1,1,'C');
				$this->SetFont('Arial','',8);
			}
			
			function Footer()
			{
				$this->SetY(-15);
				$this->SetFont('Arial','I',8);
				$this->Cell(0,5,utf8_decode('Ministerio de Gobierno - Dirección General del Sistema Penitenciario - Maxia Latam - Pag. '.$this->PageNo().'/{nb}'),0,0,'C');
			}
		}
		
		$pdf = new PDF('L', 'mm', 'letter');
		$pdf->AliasNbPages();
		
		
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;	
		$registro=$result->fetch_assoc();
		//if($nbrows>0){
			$pdf->SetFont('Arial','',7);
			$auxServicio="";
			$item=0;
			$pdf->AddPage();
			$pdf->SetFont('Times','',7);
			$result = $mysqli->query($query);
			$nbrows = $result->num_rows;	
		
			while ($registro=$result->fetch_assoc())  {
				$item++;
				$pdf->Cell(7,7,$registro['numero'],1,0,'C');
				$pdf->Cell(60,7,ucwords(substr(utf8_decode($registro['sistema']),0,37)),1,0);
				$pdf->Cell(14,7,$registro['fecha'],1,0);
				$pdf->Cell(10,7,substr($registro['estatus'],0,5),1,0,'C');
				$pdf->Cell(150,7,ucfirst(strtolower(substr(utf8_decode($registro['descripcion']),0,145))),1,0);
				$pdf->Cell(20,7,substr($registro['sector'].' - '.$registro['edificio'],0,12),1,1);
			}
			$pdf->Ln(5);
			$pdf->SetFont('Arial','B',10);
			srand (time());
			$num = rand(1,1000); 
			$fecha = date("Ymd");
			/*$nombrePDF = "listados/incidentes-".$num."-".$fecha.".pdf";
			$pdf->Output($nombrePDF, "F");
			$_SESSION['pdf']=$nombrePDF;
			echo $nombrePDF;*/
			$nombrePDF ="ListadoIncidentes_".$num."_".$fecha.".pdf";			
			$pdf->Output($nombrePDF, "D");
		/*} else {
			echo '({"total":"0", "results":""})';
		}*/
	}
	
	
	function imprimirimagenes($pdf, $dir) {
		$images = array();
		if (is_dir($dir)) {
			$d = dir($dir);
			$titulo = true;
			$salto = false;
			$x = 5;
			$y = $pdf->GetY();
			while($name = $d->read()){
				if ($name!="."&&$name!="..") {
					if ($titulo) {
						$pdf->Cell(0,7,utf8_decode("Evidencias: "),1,1);
						$titulo = false;
					}
					//$pdf->Cell(0,7,utf8_decode($dir.$name),0,1);
					if(!preg_match('/\.(jpg|jpeg|gif|png|pdf|JPG|JPEG|GIF|PNG|PDF)$/', $name)) continue;
					$name = html_entity_decode($name);
					if(preg_match('/\.(pdf)$/', $name)) {
						//$pdf->Image($dir.$name,$x,$y,250);
						//$pdf->Ln(8);
					} else {
						$pdf->Image($dir.$name,$x,$y,50,50);
						$x += 60;
					}
					if ($x==130) {
						$pdf->Ln(50);
						$salto = true;
						$x=5;
						$y = $pdf->GetY();
					
					}
				}
			}
			$d->close();
			if (!$salto && !$titulo)
				$pdf->Ln(50);
						
		}
	}
	
	function impincidentesdet() {
		global $mysqli;
		require_once('../fpdf/fpdf.php');
		
		$sistema = (!empty($_GET['componente']) ? $_GET['componente'] : '');
		$buscar = (!empty($_GET['buscar']) ? $_GET['buscar'] : '');
		$desde = (!empty($_GET['desde']) ? $_GET['desde'] : '');
		$hasta = (!empty($_GET['hasta']) ? $_GET['hasta'] : '');
		
		$query  = "SELECT i.*, DATE_FORMAT(i.fecha,'%d/%m/%Y') as fecha, u.nombre ";
		$query .= "FROM incidentes i ";
		$query .= "INNER JOIN  usuarios u on u.usuario = i.usuarioactual ";
		$query .= "WHERE 1 = 1 ";
		
		if ($sistema!="")
			$query .= "AND sistema = '$sistema' ";
		
		if ($buscar!="")
			$query .= "AND descripcion LIKE '%$buscar%' ";
			
		if ($desde!="")
			$query .= "AND fecha >= '$desde' ";
		
		if ($hasta!="")
			$query .= "AND fecha <= '$hasta' ";
		
		class PDF extends FPDF
		{
			function Header()
			{
				$this->Image('../images/mingob.png',10,8,22);
				$this->Image('../images/maxia.png',190,8,18);
				$this->SetFont('Arial','B',12);
				$this->Cell(90);
				$this->Cell(60,6,'',0,1,'C');
				$this->Cell(0,6,'MINISTERIO DE GOBIERNO',0,1,'C');
				$this->Cell(0,6,'DIRECCION GENERAL DEL SISTEMA PENITENCIARIO',0,1,'C');
				$this->Cell(0,6,'LISTADO DETALLADO DE INCIDENTES',0,1,'C');
				$this->SetFont('Arial','B',10);
				$this->Ln(3);
				$this->Cell(0,6,'F-MAXIA-TK-LNJ-06',1,1,'R');
				$this->Ln(3);
			}
			
			function Footer()
			{
				$this->SetY(-15);
				$this->SetFont('Arial','I',8);
				$this->Cell(0,5,utf8_decode('Ministerio de Gobierno - Dirección General del Sistema Penitenciario - Maxia Latam - Pag. '.$this->PageNo().'/{nb}'),0,0,'C');
			}
		}
		
		$pdf = new PDF('P', 'mm', 'letter');
		$pdf->AliasNbPages();
		//$pdf->SetLineWidth(0.1);
		
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;	
		$registro=$result->fetch_assoc();
		//if($nbrows>0){
			$pdf->SetFont('Arial','',7);
			$auxServicio="";
			$item=0;
			$pdf->AddPage();
			$pdf->SetFont('Times','',7);
			$result = $mysqli->query($query);
			$nbrows = $result->num_rows;	
			$directorios = array();
			while ($registro=$result->fetch_assoc())  {
				$item++;
				$pdf->Cell(30,7,utf8_decode("Incidente Nro.: ").$registro['numero'],1,0,'C');
				$pdf->Cell(50,7,"Fecha: ".$registro['fecha'],1,0,'C');
				$pdf->Cell(50,7,"Estatus: ".$registro['estatus'],1,0,'C');
				$pdf->Cell(66,7,utf8_decode("Ubicación: ").$registro['sector'].' - '.$registro['edificio'],1,1,'C');
				$pdf->Cell(50,7,"Creado por: ".$registro['nombre'],1,0);
				$pdf->Cell(50,7,"Responsable: ".$registro['responsable'],1,0);
				$pdf->Cell(96,7,"Sistema: ".ucwords(strtolower(utf8_decode($registro['sistema']))),1,1);
				$pdf->MultiCell(0,7,"Actividad: ".ucwords(substr(utf8_decode($registro['actividad']),0,235)),1,1);
				if ($registro['vandalismo']==1)
					$pdf->Cell(0,7,"Incidente registrado como vandalismo",1,1);
				$pdf->MultiCell(0,7,utf8_decode("Descripción: ").ucfirst(strtolower(substr(utf8_decode($registro['descripcion']),0,145))),1,1);
				if ($registro['observaciones']!="") {
					$pdf->Cell(0,7,utf8_decode("Observaciones: "),'LTR',1);
					$pdf->Cell(0,7,ucfirst(strtolower(substr(utf8_decode($registro['observaciones']),0,180))),'LR',1);
					$i = 181;
					while ($i <= strlen($registro['observaciones'])) {
						$pdf->Cell(0,7,ucfirst(strtolower(substr(utf8_decode($registro['observaciones']),$i,180))),'LR',1);
						$i += 180;
					}
					$pdf->Cell(0,7," ",'LBR',1);
				}
				$pdf->Cell(0,7,utf8_decode("Comentarios: "),'LTR',1);
				$result2 = $mysqli->query("SELECT comentario FROM comentarios WHERE id_incidente = ".$registro['numero']);
				if($result2->num_rows>0){					
					while ($res2=$result2->fetch_assoc())  {
						$pdf->Cell(0,7,ucfirst(strtolower(substr(utf8_decode($res2['comentario']),0,180))),'LR',1);
					}
								
				}
				$pdf->Cell(0,7," ",'LBR',1);
				//imprimirimagenes($pdf, "incidentes/".$registro['numero']."/");
				//$directorios[] = 'incidentes/'.$registro['numero'];
				$pdf->Ln(6);
			}
			$pdf->Ln(5);
			$pdf->SetFont('Arial','B',10);
			srand (time());
			$num = rand(1,1000); 
			$fecha = date("Ymd");
			/*$nombrePDF = "listados/incidentes-".$num."-".$fecha.".pdf";
			$pdf->Output($nombrePDF, "F");
			$_SESSION['pdf']=$nombrePDF;
			$salidaPDF = unirevidencias($directorios,$nombrePDF);
			echo $salidaPDF;*/
			$nombrePDF ="ListadoIncidentesDetallado_".$num."_".$fecha.".pdf";			
			$pdf->Output($nombrePDF, "D");
		/*} else {
			echo '({"total":"0", "results":""})';
		}*/
	}
	
	function impincidentesvan() {
		global $mysqli;
		require_once('../fpdf/fpdf.php');
		
		$sistema = (!empty($_GET['componente']) ? $_GET['componente'] : '');
		$buscar = (!empty($_GET['buscar']) ? $_GET['buscar'] : '');
		$desde = (!empty($_GET['desde']) ? $_GET['desde'] : '');
		$hasta = (!empty($_GET['hasta']) ? $_GET['hasta'] : '');
		
		$query  = "SELECT i.*, DATE_FORMAT(i.fecha,'%d/%m/%Y') as fecha, u.nombre ";
		$query .= "FROM incidentes i ";
		$query .= "INNER JOIN  usuarios u on u.usuario = i.usuarioactual ";
		$query .= "WHERE vandalismo = 1 ";
		
		if ($sistema!="")
			$query .= "AND sistema = '$sistema' ";
		
		if ($buscar!="")
			$query .= "AND descripcion LIKE '%$buscar%' ";
			
		if ($desde!="")
			$query .= "AND fecha >= '$desde' ";
		
		if ($hasta!="")
			$query .= "AND fecha <= '$hasta' ";
		
		class PDF extends FPDF
		{
			function Header()
			{
				$this->Image('../images/mingob.png',10,8,22);
				$this->Image('../images/maxia.png',190,8,18);
				$this->SetFont('Arial','B',12);
				$this->Cell(90);
				$this->Cell(60,6,'',0,1,'C');
				$this->Cell(0,6,'MINISTERIO DE GOBIERNO',0,1,'C');
				$this->Cell(0,6,'DIRECCION GENERAL DEL SISTEMA PENITENCIARIO',0,1,'C');
				$this->Cell(0,6,'LISTADO DETALLADO DE INCIDENTES (VANDALISMOS)',0,1,'C');
				$this->SetFont('Arial','B',10);
				$this->Ln(3);
				$this->Cell(0,6,'F-MAXIA-TK-LNJ-06',1,1,'R');
				$this->Ln(3);
			}
			
			function Footer()
			{
				$this->SetY(-15);
				$this->SetFont('Arial','I',8);
				$this->Cell(0,5,utf8_decode('Ministerio de Gobierno - Dirección General del Sistema Penitenciario - Maxia Latam - Pag. '.$this->PageNo().'/{nb}'),0,0,'C');
			}
		}
		
		$pdf = new PDF('P', 'mm', 'letter');
		$pdf->AliasNbPages();
		//$pdf->SetLineWidth(0.1);
		
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;	
		$registro=$result->fetch_assoc();
		//if($nbrows>0){
			$pdf->SetFont('Arial','',7);
			$auxServicio="";
			$item=0;
			$pdf->AddPage();
			$pdf->SetFont('Times','',7);
			$result = $mysqli->query($query);
			$nbrows = $result->num_rows;	
			$directorios = array();
			while ($registro=$result->fetch_assoc())  {
				$item++;
				$pdf->Cell(30,7,utf8_decode("Incidente Nro.: ").$registro['numero'],1,0,'C');
				$pdf->Cell(50,7,"Fecha: ".$registro['fecha'],1,0,'C');
				$pdf->Cell(50,7,"Estatus: ".$registro['estatus'],1,0,'C');
				$pdf->Cell(66,7,utf8_decode("Ubicación: ").$registro['sector'].' - '.$registro['edificio'],1,1,'C');
				$pdf->Cell(50,7,"Creado por: ".$registro['nombre'],1,0);
				$pdf->Cell(50,7,"Responsable: ".$registro['responsable'],1,0);
				$pdf->Cell(96,7,"Sistema: ".ucwords(strtolower(utf8_decode($registro['sistema']))),1,1);
				$pdf->MultiCell(0,7,"Actividad: ".ucwords(substr(utf8_decode($registro['actividad']),0,235)),1,1);
				$pdf->MultiCell(0,7,utf8_decode("Descripción: ").ucfirst(strtolower(substr(utf8_decode($registro['descripcion']),0,145))),1,1);
				if ($registro['observaciones']!="") {
					$pdf->Cell(0,7,utf8_decode("Observaciones: "),'LTR',1);
					$pdf->Cell(0,7,ucfirst(strtolower(substr(utf8_decode($registro['observaciones']),0,180))),'LR',1);
					$i = 180;
					while ($i <= strlen($registro['observaciones'])) {
						$pdf->Cell(0,7,ucfirst(strtolower(substr(utf8_decode($registro['observaciones']),$i,180))),'LR',1);
						$i += 180;
					}
					$pdf->Cell(0,7," ",'LBR',1);
				}
				$pdf->Cell(0,7,utf8_decode("Comentarios: "),'LTR',1);
				$result2 = $mysqli->query("SELECT comentario FROM comentarios WHERE id_incidente = ".$registro['numero']);
				if($result2->num_rows>0){					
					while ($res2=$result2->fetch_assoc())  {
						$pdf->Cell(0,7,ucfirst(strtolower(substr(utf8_decode($res2['comentario']),0,180))),'LR',1);
					}
								
				}
				$pdf->Cell(0,7," ",'LBR',1);
				$pdf->Ln(6);
				//$directorios[] = 'incidentes/'.$registro['numero'];
			}
			$pdf->Ln(5);
			$pdf->SetFont('Arial','B',10);
			srand (time());
			$num = rand(1,1000); 
			$fecha = date("Ymd");
			/*$nombrePDF = "listados/incidentes-".$num."-".$fecha.".pdf";
			$pdf->Output($nombrePDF, "F");
			$_SESSION['pdf']=$nombrePDF;
			$salidaPDF = unirevidencias($directorios,$nombrePDF);
			echo $salidaPDF;*/
			$nombrePDF ="ListadoIncidentesVandalismos_".$num."_".$fecha.".pdf";			
			$pdf->Output($nombrePDF, "D");
		/*} else {
			echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
		}*/
	}
	
	function impordenes() {
		global $mysqli;
		require_once('../fpdf/fpdf.php');

		$sistema = (!empty($_GET['componente']) ? $_GET['componente'] : '');
		$actividad = (!empty($_REQUEST['actividad']) ? $_REQUEST['actividad'] : '');
		$buscar = (!empty($_GET['buscar']) ? $_GET['buscar'] : '');
		$desde = (!empty($_GET['desde']) ? $_GET['desde'] : '');
		$hasta = (!empty($_GET['hasta']) ? $_GET['hasta'] : '');
		$estado = (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : '');
		$responsable = (!empty($_GET['responsable']) ? $_GET['responsable'] : '');
		
		$query  = "SELECT numero, sistema, DATE_FORMAT(fecha,'%d/%m/%Y') as fecha, actividad, tipo, estatus ";
		$query .= "FROM ordenes  ";
		$query .= "WHERE 1 = 1 ";
		
		if ($sistema!="")
			$query .= "AND sistema = '$sistema' ";
		
		if ($actividad!="")
			$query .= "AND actividad = '$actividad' ";
		
		if ($estado!="")
			$query .= "AND estatus = '$estado' ";
		
		if ($buscar!="")
			$query .= "AND concat(actividad,tipo) LIKE '%$buscar%' ";
			
		if ($desde!="")
			$query .= "AND fecha >= '$desde' ";
		
		if ($hasta!="")
			$query .= "AND fecha <= '$hasta' ";
		
		if ($responsable!="")
			$query .= "AND proveedor = '$responsable' ";
		
		class PDF extends FPDF
		{
			function Header()
			{
				$this->Image('../images/mingob.png',10,8,22);
				$this->Image('../images/maxia.png',250,8,18);
				$this->SetFont('Arial','B',12);
				$this->Cell(90);
				$this->Cell(60,6,'',0,1,'C');
				$this->Cell(0,6,'MINISTERIO DE GOBIERNO',0,1,'C');
				$this->Cell(0,6,'DIRECCION GENERAL DEL SISTEMA PENITENCIARIO',0,1,'C');
				$this->Cell(0,6,'LISTADO GENERAL DE ACTIVIDADES',0,1,'C');
				$this->SetFont('Arial','B',10);
				$this->Ln(3);
				$this->Cell(0,6,'F-MAXIA-TK-LNJ-100',1,1,'R');
				$this->Ln(3);
				$this->SetFont('Arial','B',7);
				$this->Cell(7,10,'Item',1,0,'C');
				$this->Cell(60,10,'Sistema',1,0,'C');
				$this->Cell(14,10,'Fecha',1,0,'C');
				$this->Cell(160,10,'Actividad',1,0,'C');
				$this->Cell(10,10,'Tipo',1,0,'C');
				$this->Cell(10,10,'Estado',1,1,'C');
				$this->SetFont('Arial','',8);
			}
			
			function Footer()
			{
				$this->SetY(-15);
				$this->SetFont('Arial','I',8);
				$this->Cell(0,5,utf8_decode('Ministerio de Gobierno - Dirección General del Sistema Penitenciario - Maxia Latam - Pag. '.$this->PageNo().'/{nb}'),0,0,'C');
			}
		}
		
		$pdf = new PDF('L', 'mm', 'letter');
		$pdf->AliasNbPages();
		
		
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;	
		$registro=$result->fetch_assoc();
		//if($nbrows>0){
			$pdf->SetFont('Arial','',7);
			$auxServicio="";
			$item=0;
			$pdf->AddPage();
			$pdf->SetFont('Times','',7);
			$result = $mysqli->query($query);
			$nbrows = $result->num_rows;	
		
			while ($registro=$result->fetch_assoc())  {
				$item++;
				$pdf->Cell(7,7,$registro['numero'],1,0,'C');
				$pdf->Cell(60,7,ucwords(substr(utf8_decode($registro['sistema']),0,37)),1,0);
				$pdf->Cell(14,7,$registro['fecha'],1,0);
				$pdf->Cell(160,7,ucfirst(strtolower(substr(utf8_decode($registro['actividad']),0,155))),1,0);
				$pdf->Cell(10,7,substr($registro['tipo'],0,5),1,0,'C');
				$pdf->Cell(10,7,substr($registro['estatus'],0,5),1,1,'C');
			}
			$pdf->Ln(5);
			$pdf->SetFont('Arial','B',10);
			srand (time());
			$num = rand(1,1000); 
			$fecha = date("Ymd");
			//$nombrePDF = "../listados/ordenes-".$num."-".$fecha.".pdf";
			//$_SESSION['pdf']=$nombrePDF;
			//echo $nombrePDF;
			$nombrePDF ="ListadoOrdenes_".$num."_".$fecha.".pdf";			
			$pdf->Output($nombrePDF, "D");
		/*} else {
			echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
		}*/
	}
	
	function impordenesdet() {
		global $mysqli;
		require_once('../fpdf/fpdf.php');		
		
		$sistema = (!empty($_GET['componente']) ? $_GET['componente'] : '');
		$actividad = (!empty($_REQUEST['actividad']) ? $_REQUEST['actividad'] : '');
		$buscar = (!empty($_GET['buscar']) ? $_GET['buscar'] : '');
		$desde = (!empty($_GET['desde']) ? $_GET['desde'] : '');
		$hasta = (!empty($_GET['hasta']) ? $_GET['hasta'] : '');
		$estado = (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : '');
		$responsable = (!empty($_GET['responsable']) ? $_GET['responsable'] : '');
		
		$query  = "SELECT o.*, DATE_FORMAT(o.fecha,'%d/%m/%Y') as fecha, u.nombre ";
		$query .= "FROM ordenes o ";
		$query .= "INNER JOIN usuarios u on u.usuario = o.proveedor ";
		//$query .= "LEFT JOIN comentarios c on o.numero = c.id_orden ";
		$query .= "WHERE 1 = 1 ";
		
		if ($sistema!="")
			$query .= "AND o.sistema = '$sistema' ";
		
		if ($actividad!="")
			$query .= "AND actividad = '$actividad' ";
		
		if ($estado!="")
			$query .= "AND estatus = '$estado' ";
		
		if ($buscar!="")
			$query .= "AND concat(o.actividad,o.tipo,o.observacion) LIKE '%$buscar%' ";
			
		if ($desde!="")
			$query .= "AND o.fecha >= '$desde' ";
		
		if ($hasta!="")
			$query .= "AND o.fecha <= '$hasta' ";
		
		if ($responsable!="")
			$query .= "AND o.proveedor = '$responsable' ";
		
		class PDF extends FPDF
		{
			function Header()
			{
				$this->Image('../images/mingob.png',10,8,22);
				$this->Image('../images/maxia.png',190,8,18);
				$this->SetFont('Arial','B',12);
				$this->Cell(90);
				$this->Cell(60,6,'',0,1,'C');
				$this->Cell(0,6,'MINISTERIO DE GOBIERNO				666666',0,1,'C');
				$this->Cell(0,6,'DIRECCION GENERAL DEL SISTEMA PENITENCIARIO',0,1,'C');
				$this->Cell(0,6,'LISTADO DETALLADO DE ACTIVIDADES',0,1,'C');
				$this->SetFont('Arial','B',10);
				$this->Ln(3);
				$this->Cell(0,6,'F-MAXIA-TK-LNJ-101',1,1,'R');
				$this->Ln(3);
			}
			
			function Footer()
			{
				$this->SetY(-15);
				$this->SetFont('Arial','I',8);
				$this->Cell(0,5,utf8_decode('Ministerio de Gobierno - Dirección General del Sistema Penitenciario - Maxia Latam - Pag. '.$this->PageNo().'/{nb}'),0,0,'C');
			}
		}
		
		$pdf = new PDF('P', 'mm', 'letter');
		$pdf->AliasNbPages();
		//$pdf->SetLineWidth(0.1);
		
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;	
		$registro=$result->fetch_assoc();
		//if($nbrows>0){$result = $mysqli->query($query);
		$nbrows = $result->num_rows;	
		$registro=$result->fetch_assoc();
		//if($nbrows>0){
			$pdf->SetFont('Arial','',7);
			$auxServicio="";
			$item=0;
			$pdf->AddPage();
			$pdf->SetFont('Times','',7);
			$result = $mysqli->query($query);
			$nbrows = $result->num_rows;	
			$directorios = array();
			while ($res=$result->fetch_assoc())  {
				$pdf->Cell(30,7,utf8_decode("Orden Nroaaa.: ").$res['numero'],1,0,'C');
				$pdf->Cell(50,7,"Fecha: ".$res['fecha'],1,0,'C');
				$pdf->Cell(50,7,"Estatus: ".$res['estatus'],1,0,'C');
				$pdf->Cell(66,7,"Responsable: ".$res['nombre'],1,1);
				$pdf->Cell(0,7,"Sistema: ".ucwords(strtolower(utf8_decode($res['sistema']))),1,1);
				$pdf->MultiCell(0,7,utf8_decode("Actividad (Ubicación): ").ucwords(substr(utf8_decode($res['actividad']),0,235)),1,1);
				if ($res['observaciones']!="") {
					$pdf->Cell(0,7,utf8_decode("Observaciones: "),'LTR',1);
					$pdf->Cell(0,7,ucfirst(strtolower(substr(utf8_decode($res['observaciones']),0,180))),'LR',1);
					$i = 181;
					while ($i <= strlen($res['observaciones'])) {
						$pdf->Cell(0,7,ucfirst(strtolower(substr(utf8_decode($res['observaciones']),$i,180))),'LR',1);
						$i += 180;
					}
					$pdf->Cell(0,7," ",'LBR',1);
				}
				$pdf->Cell(0,7,utf8_decode("Comentarios: "),'LTR',1);
				$result2 = $mysqli->query("SELECT comentario FROM comentarios WHERE id_orden = ".$res['numero']);
				if($result2->num_rows>0){					
					while ($res2=$result2->fetch_assoc())  {
						$pdf->Cell(0,7,ucfirst(strtolower(substr(utf8_decode($res2['comentario']),0,180))),'LR',1);
					}
								
				}
				$pdf->Cell(0,7," ",'LBR',1);
				$pdf->Ln(6);
			}
			$pdf->Ln(5);
			$pdf->SetFont('Arial','B',10);
			srand (time());
			$num = rand(1,1000); 
			$fecha = date("Ymd");
			//$nombrePDF = "listados/ordenes-".$num."-".$fecha.".pdf";
			//generate_pub_pdf($directorios,$nombrePDF);
			//$pdf->Output($nombrePDF, "F");			
			//$_SESSION['pdf']=$nombrePDF;
			//$salidaPDF = unirevidencias($directorios,$nombrePDF);
			//echo $salidaPDF;
			$nombrePDF ="ListadoOrdenesDetallado_".$num."_".$fecha.".pdf";			
			$pdf->Output($nombrePDF, "D");
		/*} else {
			echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
		}*/
	}
	
	function impordenescor() {
		global $mysqli;
		require_once('../fpdf/fpdf.php');	
		
		$sistema = (!empty($_GET['componente']) ? $_GET['componente'] : '');
		$actividad = (!empty($_REQUEST['actividad']) ? $_REQUEST['actividad'] : '');
		$buscar = (!empty($_GET['buscar']) ? $_GET['buscar'] : '');
		$desde = (!empty($_GET['desde']) ? $_GET['desde'] : '');
		$hasta = (!empty($_GET['hasta']) ? $_GET['hasta'] : '');
		$estado = (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : '');
		$responsable = (!empty($_GET['responsable']) ? $_GET['responsable'] : '');
		
		$query  = "SELECT o.*, DATE_FORMAT(o.fecha,'%d/%m/%Y') as fecha, u.nombre ";
		$query .= "FROM ordenes o ";
		$query .= "INNER JOIN  usuarios u on u.usuario = o.proveedor ";
		$query .= "WHERE tipo = 'Correctiva' ";
		
		if ($sistema!="")
			$query .= "AND o.sistema = '$sistema' ";
		
		if ($actividad!="")
			$query .= "AND actividad = '$actividad' ";
		
		if ($estado!="")
			$query .= "AND estatus = '$estado' ";
		
		if ($buscar!="")
			$query .= "AND concat(o.actividad,o.tipo,o.observacion) LIKE '%$buscar%' ";
			
		if ($desde!="")
			$query .= "AND o.fecha >= '$desde' ";
		
		if ($hasta!="")
			$query .= "AND o.fecha <= '$hasta' ";
		
		if ($responsable!="")
			$query .= "AND o.proveedor = '$responsable' ";
		
		class PDF extends FPDF
		{
			function Header()
			{
				$this->Image('../images/mingob.png',10,8,22);
				$this->Image('../images/maxia.png',190,8,18);
				$this->SetFont('Arial','B',12);
				$this->Cell(90);
				$this->Cell(60,6,'',0,1,'C');
				$this->Cell(0,6,'MINISTERIO DE GOBIERNO',0,1,'C');
				$this->Cell(0,6,'DIRECCION GENERAL DEL SISTEMA PENITENCIARIO',0,1,'C');
				$this->Cell(0,6,'LISTADO DETALLADO DE ACTIVIDADES CORRECTIVAS',0,1,'C');
				$this->SetFont('Arial','B',10);
				$this->Ln(3);
				$this->Cell(0,6,'F-MAXIA-TK-LNJ-102',1,1,'R');
				$this->Ln(3);
			}
			
			function Footer()
			{
				$this->SetY(-15);
				$this->SetFont('Arial','I',8);
				$this->Cell(0,5,utf8_decode('Ministerio de Gobierno - Dirección General del Sistema Penitenciario - Maxia Latam - Pag. '.$this->PageNo().'/{nb}'),0,0,'C');
			}
		}
		
		$pdf = new PDF('P', 'mm', 'letter');
		$pdf->AliasNbPages();
		//$pdf->SetLineWidth(0.1);
		
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;	
		$registro=$result->fetch_assoc();
		//if($nbrows>0){
			$pdf->SetFont('Arial','',7);
			$auxServicio="";
			$item=0;
			$pdf->AddPage();
			$pdf->SetFont('Times','',7);
			$result = $mysqli->query($query);
			$nbrows = $result->num_rows;	
		
			while ($registro=$result->fetch_assoc())  {
				$item++;
				$pdf->Cell(30,7,utf8_decode("Orden Nro.: ").$registro['numero'],1,0,'C');
				$pdf->Cell(50,7,"Fecha: ".$registro['fecha'],1,0,'C');
				$pdf->Cell(50,7,"Estatus: ".$registro['estatus'],1,0,'C');
				$pdf->Cell(66,7,"Responsable: ".$registro['nombre'],1,1);
				$pdf->Cell(0,7,"Sistema: ".ucwords(strtolower(utf8_decode($registro['sistema']))),1,1);
				$pdf->MultiCell(0,7,utf8_decode("Actividad (Ubicación): ").ucwords(substr(utf8_decode($registro['actividad']),0,235)),1,1);
				if ($registro['observaciones']!="") {
					$pdf->Cell(0,7,utf8_decode("Observaciones: "),'LTR',1);
					$pdf->Cell(0,7,ucfirst(strtolower(substr(utf8_decode($registro['observaciones']),0,180))),'LR',1);
					$i = 181;
					while ($i <= strlen($registro['observaciones'])) {
						$pdf->Cell(0,7,ucfirst(strtolower(substr(utf8_decode($registro['observaciones']),$i,180))),'LR',1);
						$i += 180;
					}
					$pdf->Cell(0,7," ",'LBR',1);
				}
				$pdf->Cell(0,7,utf8_decode("Comentarios: "),'LTR',1);
				$result2 = $mysqli->query("SELECT comentario FROM comentarios WHERE id_orden = ".$registro['numero']);
				if($result2->num_rows>0){					
					while ($res2=$result2->fetch_assoc())  {
						$pdf->Cell(0,7,ucfirst(strtolower(substr(utf8_decode($res2['comentario']),0,180))),'LR',1);
					}
								
				}
				$pdf->Cell(0,7," ",'LBR',1);
				//imprimirimagenes($pdf, "incidentes/".$registro['numero']."/");
				$pdf->Ln(6);
			}
			$pdf->Ln(5);
			$pdf->SetFont('Arial','B',10);
			srand (time());
			$num = rand(1,1000); 
			$fecha = date("Ymd");
			$nombrePDF = "ListadosOrnedesCorrectivas-".$num."_".$fecha.".pdf";
			$pdf->Output($nombrePDF, "D");
			//$_SESSION['pdf']=$nombrePDF;
			echo $nombrePDF;
		/*} else {
			echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
		}*/
	}
	
	function ReporteOrdenesXLS(){
		global $mysqli;
		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');
		$desde = $_GET['desde'];
		$hasta = $_GET['hasta'];
		$nivel = $_SESSION['nivel'];
	
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	
		/** Include PHPExcel */
		require_once dirname(__FILE__) . '/../xls/Classes/PHPExcel.php';
	
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
	
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maxia Latam")
		->setLastModifiedBy("Maxia Latam")
		->setTitle("Reporte de Ordenes")
		->setSubject("Reporte de Ordenes")
		->setDescription("Reporte de Ordenes")
		->setKeywords("Reporte de ordenes")
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Reporte de Ordenes');
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($style);
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:F1');
	
			
		// ENCABEZADO 
		$objPHPExcel->getActiveSheet()->setCellValue('A4', '# Numero')
		->setCellValue('B4', 'Sistema')
		->setCellValue('C4', 'Actividad')
		->setCellValue('D4', 'Fecha')
		->setCellValue('E4', 'Estado')
		->setCellValue('F4', 'Observaciones');
		//LETRA
		$objPHPExcel->getActiveSheet()->getStyle('A4:F4')->getFont()->setBold(true)->setSize(10)->setColor($fontColor);
		$objPHPExcel->getActiveSheet()->getStyle("A4:F4")->applyFromArray($style);
		//FONDO
		$objPHPExcel->getActiveSheet()->getStyle('A4:F4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('63b9db');
		
		
		//CUERPO
		//Definir fuente
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
	
		$query  = " SELECT DISTINCT numero, sistema, actividad, DATE(fecha) AS fecha, estatus, observaciones,
					concat(servicio, ' : ', sistema) as grupo
					FROM ordenes a
					LEFT JOIN usuarios b ON a.usuario=b.usuario
					WHERE 1=1";
		if($desde != ''){
			$query  .= " AND fecha >= '".$desde."' ";
		}
		if($hasta != ''){
			$query  .= " AND fecha <= '".$hasta."' ";
		}
		if($nivel ==5 || $nivel ==10){
			$query .=" AND b.nivel = $nivel ";
		}
		$query .= " ORDER BY grupo ASC";
		//debug('Encabezado: '.$query);
		$result = $mysqli->query($query);
		$i = 5;
		$temp ="";
		if($result){
			while($row = $result->fetch_assoc()){		
				$numeroreq = str_pad($row['numero'], 4, "0", STR_PAD_LEFT);	
			
				if($row['sistema']!=$temp){
			
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $row['grupo']);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$i.':F'.$i);
					$i++;
					$primera=false;
				}	
			
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, '#'.$numeroreq)
				->setCellValue('B'.$i, $row['sistema'])
				->setCellValue('C'.$i, $row['actividad'])
				->setCellValue('D'.$i, implode('/',array_reverse(explode('-', $row['fecha']))))
				->setCellValue('E'.$i, $row['estatus'])
				->setCellValue('F'.$i, $row['observaciones']);
					
			
				//ESTILOS
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->setSize(10);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getAlignment()->applyFromArray(
			 				array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));
			
				$temp = $row['sistema'];
				$i++;
			}
		}
	
		//Ancho automatico
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(45);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(60);
	
		//Renombrar hoja de Excel
		$objPHPExcel->getActiveSheet()->setTitle('ReporteOrdenes');
	
		//Redirigir la salida al navegador del cliente		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="ReporteOrdenes.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();
	}
	
	function ReportIncidentesXLS(){
		global $mysqli;
		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');
		$desde = $_REQUEST['desde'];
		$hasta = $_REQUEST['hasta'];
	
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	
		/** Include PHPExcel */
		require_once dirname(__FILE__) . '/../xls/Classes/PHPExcel.php';
	
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
	
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maxia Latam")
		->setLastModifiedBy("Maxia Latam")
		->setTitle("Reporte de Incidentes")
		->setSubject("Reporte de Incidentes")
		->setDescription("Reporte de Incidentes")
		->setKeywords("Reporte de Incidentes")
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Reporte de Incidentes');
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($style);
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:I1');
	
		
		// ENCABEZADO 
		$objPHPExcel->getActiveSheet()->setCellValue('A4', '# numero')
		->setCellValue('B4', 'Fecha')
		->setCellValue('C4', 'Estado')
		->setCellValue('D4', 'Actividad')
		->setCellValue('E4', 'Prioridad')
		->setCellValue('F4', 'Sistema')
		->setCellValue('G4', 'Ubicacion')
		->setCellValue('H4', 'Responsable')
		->setCellValue('I4', 'Registrado por');
		//LETRA
		$objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getFont()->setBold(true)->setSize(10)->setColor($fontColor);
		$objPHPExcel->getActiveSheet()->getStyle("A4:I4")->applyFromArray($style);
		//FONDO
		$objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('63b9db');
		
		
		//CUERPO
		//Definir fuente
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
		
		$query  = " SELECT DISTINCT numero, DATE(fecha) AS fecha, estatus, descripcion, '-' as prioridad, 
					sistema, concat(sector, ' ', edificio) as ubicacion, responsable, b.nombre as creador
					FROM incidentes a
					INNER JOIN usuarios b ON b.usuario = a.usuarioactual
					WHERE 1=1";
		if($desde != ''){
			$query  .= " AND a.fecha >= '".$desde."' ";
		}
		if($hasta != ''){
			$query  .= " AND a.fecha <= '".$hasta."' ";
		}
		$query .= " ORDER BY numero";
		//debug('Encabezado: '.$query);
		$result = $mysqli->query($query);
		$i = 5;
		
		while($row = $result->fetch_assoc()){		
			
			$numeroreq = str_pad($row['numero'], 4, "0", STR_PAD_LEFT);		
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, '#'.$numeroreq)
			->setCellValue('B'.$i, implode('/',array_reverse(explode('-', $row['fecha']))))
			->setCellValue('C'.$i, $row['estatus'])
			->setCellValue('D'.$i, $row['descripcion'])
			->setCellValue('E'.$i, $row['prioridad'])
			->setCellValue('F'.$i, $row['sistema'])
			->setCellValue('G'.$i, $row['ubicacion'])
			->setCellValue('H'.$i, $row['responsable'])
			->setCellValue('I'.$i, $row['creador']);
			
			//ESTILOS
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getAlignment()->applyFromArray(
	    				array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));
			
			$i++;
		}
	
		//Ancho automatico
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	
		//Renombrar hoja de Excel
		$objPHPExcel->getActiveSheet()->setTitle('ReporteIncidentes');
	
		//Redirigir la salida al navegador del cliente		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="ReporteIncidentes.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();
	}

?>
