<?php
	include("../../conexion.php");
	use PhpOffice\PhpWord\Element\Section;
	use PhpOffice\PhpWord\Shared\Converter;
	use PhpOffice\PhpWord\Style\TablePosition;
	//require_once __DIR__ . '/bootstrap.php';

	include_once 'Sample_Header.php';

	//SESSION
 	//$usuario 		  = $_SESSION['usuario'];
	//$nivel 			  = $_SESSION['nivel'];	
	//GET
	$idempresas 	  = (!empty($_REQUEST['idempresas']) ? $_REQUEST['idempresas'] : '1');
	$idclientes 	  = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '0');
	$idproyectos 	  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '0');
	$tipo 	  		  = 'incidentes,preventivos';
	$fechadesdec	  = (!empty($_REQUEST['fechadesdec']) ? $_REQUEST['fechadesdec'] : '');
	$fechahastac      = (!empty($_REQUEST['fechahastac']) ? $_REQUEST['fechahastac'] : '');
	$fechadesder	  = (!empty($_REQUEST['fechadesder']) ? $_REQUEST['fechadesder'] : '');
	$fechahastar      = (!empty($_REQUEST['fechahastar']) ? $_REQUEST['fechahastar'] : '');
	//DESDE
	$arrfechad		  = explode('-',$fechadesdec);
	$diad 			  = $arrfechad[2];
	$mesd 			  = $arrfechad[1];
	$yeard 			  = substr($arrfechad[0],-2);
	//HASTA
	$arrfechah		  = explode('-',$fechahastac);
	$diah 			  = $arrfechah[2];
	$mesh 			  = $arrfechah[1];
	$yearh 			  = substr($arrfechah[0],-2);
	
	// New Word document
	$phpWord = new \PhpOffice\PhpWord\PhpWord();
	$phpWord->setDefaultFontName('colfax');
	$section = $phpWord->addSection( array("marginTop" => 0, "marginBottom" => 0) );
	
	//FONDO AZUL
	$section->addImage(
		'images/fondo.png',
		array(
			'positioning'      => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
			'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
			'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
			'posVertical'      => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
			'posVerticalRel'   => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,
			'marginTop'          => -50,
			'marginLeft'         => 0,
			'width'              => 700,
			'height'             => 900,
			'wrappingStyle'      => 'behind',
			//'wrapDistanceRight'  => Converter::cmToPoint(1),
			//'wrapDistanceBottom' => Converter::cmToPoint(1),
		)
	);
	//LOGO TEXTO
	$section->addImage(
		'images/blanco.png',
		array(
			'width'            => '150',
			//'height'         => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(3),
			'positioning'      => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
			'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
			'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
			'posVertical'      => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
			'posVerticalRel'   => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,
			'alignment' 		=> \PhpOffice\PhpWord\SimpleType\Jc::RIGHT,
			'marginLeft'       => 150,
			'marginTop'        => 0,		
			'wrappingStyle'    => 'behind',
		)
	);
	//LOGO
	$section->addImage(
		'images/avatar-blanco.png',
		array(
			'width'            => '40',
			//'height'         => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(3),
			'positioning'      => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
			'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_LEFT,
			'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
			'posVertical'      => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
			'posVerticalRel'   => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,
			'alignment' 		=> \PhpOffice\PhpWord\SimpleType\Jc::RIGHT,
			'marginLeft'       => 150,
			'marginTop'        => 0,		
			'wrappingStyle'    => 'behind',
		)
	);
	$section->addTextBreak(10);

	$fontStyle0 = array('size' => 22, 'color' => 'FFFFFF', 'vAlign' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT);
	$fontStyle1 = array('size' => 20, 'color' => 'FFFFFF', 'vAlign' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT);
	$fontStyle2 = array('size' => 24, 'color' => 'FFFFFF', 'bold' => true, 'alignment' => 'right');
	$fontStyle3 = array('size' => 14, 'color' => 'FFFFFF', 'alignment' => 'right');

	//CLIENTE
	if($idclientes != ""){
		$queryC = " SELECT siglas, nombre FROM clientes WHERE id = ".$idclientes."";
		$resultC = $mysqli->query($queryC); 
		if($rowC = $resultC->fetch_assoc()){
			$siglascliente = $rowC['siglas'];
			$nombrecliente = $rowC['nombre'];
		}
	}
	//PROYECTO
	if($idproyectos != ""){
		$queryC = " SELECT GROUP_CONCAT(nombre) AS nombre FROM proyectos WHERE id IN (".$idproyectos.")";
		$resultC = $mysqli->query($queryC); 
		if($rowC = $resultC->fetch_assoc()){
			$nombreproyecto = $rowC['nombre'];
		}
	}

	$section->addText('                               Informe Mensual', $fontStyle0);
	$section->addText('                             '.$nombrecliente, $fontStyle2);
	$section->addTextBreak(2);
	printSeparator($section);
	//$section->addText($text);
	$section->addText('                                  '.$nombreproyecto, $fontStyle1);
	$section->addText('                                  '.$diad.'.'.$mesd.'.'.$yeard.'-'.$diah.'.'.$mesh.'.'.$yearh, $fontStyle1);
	$section->addTextBreak(2);
	$section->addText('                                  Versión CSC-'.$siglascliente.'-'.$mesd.''.$yeard, $fontStyle1);
	$section->addTextBreak(8);
	$section->addText('                                                                                  maxialatam.com', $fontStyle3);

	//TABLA DE CONTENIDO
	$section = $phpWord->addSection();
	$fontStyle4 = array('size' => 9, 'color' => '000000', 'alignment' => 'left', 'bold' => true, 'spaceAfter' => 10);
	$section->addText('TABLA DE CONTENIDO', $fontStyle4);
	$section->addTextBreak(2);
	$section->addText('1.	MANTENIMIENTOS PREVENTIVOS REALIZADOS ............................................................. 2', $fontStyle4);
	$section->addText('2.	ESTADISTICA PREVENTIVOS REALIZADOS VS PROGRAMADOS .................................. 2', $fontStyle4);
	$section->addText('3.	GESTIÓN DE INCIDENCIAS / SOLICITUDES ....................................................................... 3', $fontStyle4);
	$section->addText('4.	ESTADISTICA INCIDENTES RECIBIDOS VS RESUELTOS .................................................. 3', $fontStyle4);
	$section->addText('5.	EQUIPOS FUERA DE SERVICIO ........................................................................................... 3', $fontStyle4);
	$section->addText('6.	REEMPLAZO DE EQUIPOS ................................................................................................... 3', $fontStyle4);
	$section->addText('7.	SERVICIO DE INTERNET ....................................................................................................... 4', $fontStyle4);
	$section->addText('8.	MONITOREO DE ANALISIS PREVENTIVO EN INFRAESTRUCTURA TECNOLÓGICA ..... 4', $fontStyle4);

	//MANTENIMIENTOS PREVENTIVOS
	$section = $phpWord->addSection();
	$fontStyle5 = array('size' => 9, 'color' => '293f76', 'alignment' => 'left', 'bold' => true, 'spaceAfter' => 10);
	$fontStyle6 = array('size' => 9, 'color' => '000000', 'alignment' => 'left', 'spaceAfter' => 10);
	$fontStyleSubtitle = array('size' => 9, 'color' => '000000', 'alignment' => 'left', 'bold' => true, 'spaceAfter' => 10);
	//TABLA
	$styleTable = array('borderSize' => 6, 'borderColor' => '999999', 'size' => 9);
	$styleCell = array('color' => '000000', 'bgColor' => 'cee1e8', 'valign' => 'center', 'size' => 9);
	$styleCellEnc = array('bgColor' => '293f76', 'color' => 'FFFFFF', 'valign' => 'center', 'size' => 9);
	$cellHCenteredEnc = array('color' => 'FFFFFF', 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'size' => 9);
	$cellVCentered = array('valign' => 'center', 'size' => 9);
	$cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'size' => 9);
	
	
	/*
	$phpWord->addTableStyle('Mantenimientos preventivos', $styleTable);
	$table = $section->addTable('Mantenimientos preventivos');
	$row = $table->addRow();
	$row->addCell(1000, $cellVCentered)->addText('Preventivo', $cellHCentered);
	$row->addCell(1300, $cellVCentered)->addText('Título', $cellHCentered);
	$row->addCell(1000, $cellVCentered)->addText('Estado', $cellHCentered);
	$row->addCell(1000, $cellVCentered)->addText('Equipo', $cellHCentered);
	$row->addCell(1000, $cellVCentered)->addText('Serie', $cellHCentered);
	$row->addCell(1000, $cellVCentered)->addText('Marca', $cellHCentered);
	$row->addCell(1000, $cellVCentered)->addText('Ubicación', $cellHCentered);
	$row->addCell(1000, $cellVCentered)->addText('Fecha de resolución', $cellHCentered);
	$row->addCell(1000, $cellVCentered)->addText('Horas trabajadas', $cellHCentered);
	*/
	//SENTENCIA BASE
	$query  = " SELECT a.id, a.titulo, b.nombre AS proyecto, a.tipo, c.nombre AS estado, d.nombre AS categoria, 
				a.horastrabajadas, ifnull(a.fechacreacion, '') AS fechacreacion, a.horacreacion, 
				ifnull(a.fecharesolucion, '') AS fecharesolucion, a.horaresolucion, f.nombre AS cliente, 
				a.idclientes, a.idproyectos, a.idestados, a.idprioridades, a.resolucion,
				g.nombre AS equipo, g.serie, h.nombre AS marca, i.nombre AS ubicacion, IFNULL(j.nombre,'Sin Asignar') AS asignado
				FROM incidentes a
				LEFT JOIN proyectos b ON a.idproyectos = b.id 
				LEFT JOIN estados c ON a.idestados = c.id
				LEFT JOIN categorias d ON a.idcategorias = d.id  
				LEFT JOIN empresas e ON a.idempresas = e.id 
				LEFT JOIN clientes f ON a.idclientes = f.id 
				LEFT JOIN activos g ON a.idactivos = g.id 
				LEFT JOIN marcas h ON g.idmarcas = h.id 
				LEFT JOIN ambientes i ON g.idambientes = i.id 
				LEFT JOIN usuarios j ON a.asignadoa = j.correo
				WHERE a.idestados = 16 ";	
	//FILTROS	 
	if($idclientes != ''){
		$query .= " AND a.idclientes = ".$idclientes." ";
	}
	if($idproyectos != ''){
		$query .= " AND a.idproyectos IN (".$idproyectos.") ";
	}
	if($tipo != ''){
		$query .= " AND a.tipo IN ('preventivos') ";
	}
	if($fechadesdec != ''){
		$query .= " AND ((a.fechacreacion >= '".$fechadesdec."' ";
	}
	if($fechahastac != ''){
		$query .= " AND a.fechacreacion <= '".$fechahastac."') ";
	}
	if($fechadesder != ''){
		$query .= " OR (a.fecharesolucion >= '".$fechadesder."' ";
	}
	if($fechahastar != ''){
		$query .= " AND a.fecharesolucion <= '".$fechahastar."')) ";
	} 
	
	$query  .= " ORDER BY a.asignadoa ASC ";
	$result = $mysqli->query($query);
	$count = 0;
	$nrohoras = 0;
	$nrominut = 0;
	//echo $query;
	$recordsTotal = $result->num_rows;
	$asignadoactual = '';
	//echo $recordsTotal;
	
	$section->addText('1.	Mantenimientos Preventivos Realizados ('.$recordsTotal.')', $fontStyle5);
	$section->addText('Se muestran por proveedor todos los mantenimientos preventivos realizados en el periodo. (mensual). Se incluye columna de costo por mantenimiento.', $fontStyle6);
	//$section->addTextBreak();
	
	while($rowc = $result->fetch_assoc()){
		$year = 2021;
		$idestadosInc    = $rowc['idestados'];
		$festivos = '';
		//Horas Trabajadas
		if($rowc['fecharesolucion'] != '' && $rowc['horastrabajadas'] == 0){
			//Dias Festivos Panamá
			$queryFestivos = " SELECT dia FROM diasfestivos ";
			$resultFestivos= $mysqli->query($queryFestivos);
			while($rowFestivos = $resultFestivos->fetch_assoc()){
				$festivos .= ''.$year.'-'.$rowFestivos['dia'].',';  
			}
			$festivos = explode(',', $festivos);
			$festivos = array_filter($festivos);
		
			$arrayresolucion = explode("-",$rowc['fecharesolucion']);
			$mesresolucion = $arrayresolucion[1];
			$arrayfechadesder = explode("-",$fechadesder);
			$mesdesder = $arrayfechadesder[1];
			$arrayfechahastar = explode("-",$fechahastar);
			$meshastar = $arrayfechahastar[1];
			
			if($rowc['horastrabajadas'] != 0){
				$horastrabajadas = $rowc['horastrabajadas'];
				$arrhoras = explode(":",$horastrabajadas);								
				if($idestadosInc == 16 && ($mesresolucion == $mesdesder || $mesresolucion == $meshastar)){
					//TOTAL HORAS
					$nrohoras += $arrhoras[0];
					$nrominut += $arrhoras[1];
				}
				$totalFinal = $nrohoras.":".$nrominut;
			}else{
				$totalFinal = 0;
			}
			
		}else{
			$totalFinal = $rowc['horastrabajadas'];
		}		
		
		$numeroreq = str_pad($rowc['id'], 4, "0", STR_PAD_LEFT);
		$fechacre = implode('/',array_reverse(explode('-', $rowc['fechacreacion'])));
		$fechares = implode('/',array_reverse(explode('-', $rowc['fecharesolucion'])));
		  
		if($asignadoactual != $rowc['asignado']){
			
			$asignadoactual = $rowc['asignado'];
			$section->addTextBreak();
			$section->addText($asignadoactual, $fontStyle5);
			
			$phpWord->addTableStyle('Mantenimientos preventivos '.$asignadoactual, $styleTable);
			$table = $section->addTable('Mantenimientos preventivos '.$asignadoactual);
			$row = $table->addRow();
			$row->addCell(1000, $styleCellEnc)->addText('Preventivo', $cellHCenteredEnc);
			$row->addCell(1300, $styleCellEnc)->addText('Título', $cellHCenteredEnc);
			$row->addCell(1000, $styleCellEnc)->addText('Estado', $cellHCenteredEnc);
			$row->addCell(1000, $styleCellEnc)->addText('Equipo', $cellHCenteredEnc);
			$row->addCell(1000, $styleCellEnc)->addText('Serie', $cellHCenteredEnc);
			$row->addCell(1000, $styleCellEnc)->addText('Marca', $cellHCenteredEnc);
			$row->addCell(1000, $styleCellEnc)->addText('Ubicación', $cellHCenteredEnc);
			$row->addCell(1000, $styleCellEnc)->addText('Fecha de creación', $cellHCenteredEnc);
			$row->addCell(1000, $styleCellEnc)->addText('Fecha de resolución', $cellHCenteredEnc);
			$row->addCell(1000, $styleCellEnc)->addText('Horas trabajadas', $cellHCenteredEnc);
		}
		if($asignadoactual == $rowc['asignado']){
			$table = $section->addTable('Mantenimientos preventivos '.$asignadoactual);
		}

		$row = $table->addRow();
		$row->addCell(1000, $cellVCentered)->addText($numeroreq, $cellHCentered);
		$row->addCell(1300)->addText($rowc['titulo']);
		if($rowc['estado'] != 'Resuelto'){
			$row->addCell(1000, $styleCell)->addText($rowc['estado']);
		}else{
			$row->addCell(1000, $cellVCentered)->addText($rowc['estado']);
		}
		$row->addCell(1000, $cellVCentered)->addText($rowc['equipo']);
		$row->addCell(1000, $cellVCentered)->addText($rowc['serie']);
		$row->addCell(1000, $cellVCentered)->addText($rowc['marca']);
		$row->addCell(1000, $cellVCentered)->addText($rowc['ubicacion']);
		$row->addCell(1000, $cellVCentered)->addText($fechacre, $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($fechares, $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($totalFinal, $cellHCentered);
		$count++;
	}
	if($count == 0){
		$table = $section->addTable('Mantenimientos preventivos '.$asignadoactual);
		$row = $table->addRow();
		$row->addCell(1000)->addText('');
		$row->addCell(1300)->addText('');
		$row->addCell(1000)->addText('');
		$row->addCell(1000)->addText('');
		$row->addCell(1000)->addText('');
		$row->addCell(1000)->addText('');
		$row->addCell(1000)->addText('');
		$row->addCell(1000)->addText('');
		$row->addCell(1000)->addText('');
		$section->addText('**No se realizaron preventivos durante este periodo');
	}

	//PREVENTIVOS REALIZADOS VS PROGRAMADOS
	$section->addTextBreak(2);
	$section->addText('2.	Estadística preventivos realizados vs programados', $fontStyle5);
	$section->addText('Durante este periodo se muestran todos los preventivos realizados, y se agregan los preventivos programados pero No realizados y su causa/motivo de reprogramación.', $fontStyle6);
	$section->addTextBreak();
	//TABLA
	$phpWord->addTableStyle('Preventivos realizados', $styleTable);
	$table = $section->addTable('Preventivos realizados');

	$row = $table->addRow();
	$row->addCell(1000, $styleCellEnc)->addText('Preventivos programados', $cellHCenteredEnc);
	$row->addCell(1300, $styleCellEnc)->addText('Preventivos Realizados', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('%  Efectividad', $cellHCenteredEnc);
	
	//SENTENCIA BASE
	$query  = " SELECT a.id, a.idestados,
				ifnull(a.fechacreacion, '') AS fechacreacion, ifnull(a.fecharesolucion, '') as fecharesolucion
				FROM incidentes a
				WHERE 1 = 1 ";	 //MONTH(a.fechacreacion) = ".$mesd."
	//FILTROS	 
	if($idclientes != ''){
		$query .= " AND a.idclientes = ".$idclientes." ";
	}
	if($idproyectos != ''){
		$query .= " AND a.idproyectos IN (".$idproyectos.") ";
	}
	if($tipo != ''){
		$query .= " AND a.tipo IN ('preventivos') ";
	}
	if($fechadesdec != ''){
		$query .= " AND ((a.fechacreacion >= '".$fechadesdec."' ";
	}
	if($fechahastac != ''){
		$query .= " AND a.fechacreacion <= '".$fechahastac."')) ";
	}   
	/* $resultP = $mysqli->query($query.")");
	$totalp = $resultP->num_rows;
	if($fechadesder != ''){
		$query .= " OR (a.fecharesolucion >= '".$fechadesder."' ";
	}
	if($fechahastar != ''){
		$query .= " AND a.fecharesolucion <= '".$fechahastar."')) ";
	}  */
	
	//echo $query;
	$result = $mysqli->query($query);
	$recordsTotal = $result->num_rows;
	$programados = 0;
	$realizados = 0;
	//echo $recordsTotal;
	while($rowc = $result->fetch_assoc()){
		if($rowc['fecharesolucion'] != '' && $rowc['idestados'] == '16'){
			$realizados++;
		}
		$programados++;
	}
	if($programados != 0){
		$efectividad = round(($realizados*100)/$programados);
	}else{
		$efectividad = 0;
	} 	
	/* if($totalp != 0){
		$efectividad = round(($realizados*100)/$totalp);
	}else{
		$efectividad = 0;
	}*/
	$norealizados = $programados - $realizados; 
	$row = $table->addRow();
	$row->addCell(3000, $cellVCentered)->addText($programados, $cellHCentered);
	$row->addCell(3000, $cellVCentered)->addText($realizados, $cellHCentered);
	$row->addCell(3000, $cellVCentered)->addText($efectividad, $cellHCentered);
	
	if($efectividad < 100){
		$section->addText('No realizados ('.$norealizados.')', $fontStyleSubtitle);
		/*
		$phpWord->addTableStyle('Preventivos no realizados', $styleTable);
		$table = $section->addTable('Preventivos no realizados');
		$row = $table->addRow();
		$row->addCell(1000, $styleCellEnc)->addText('Preventivo', $cellHCenteredEnc);
		$row->addCell(1300, $styleCellEnc)->addText('Título', $cellHCenteredEnc);
		$row->addCell(1000, $styleCellEnc)->addText('Estado', $cellHCenteredEnc);
		$row->addCell(1000, $styleCellEnc)->addText('Equipo', $cellHCenteredEnc);
		$row->addCell(1000, $styleCellEnc)->addText('Serie', $cellHCenteredEnc);
		$row->addCell(1000, $styleCellEnc)->addText('Marca', $cellHCenteredEnc);
		$row->addCell(1000, $styleCellEnc)->addText('F.Programada', $cellHCenteredEnc);
		$row->addCell(1000, $styleCellEnc)->addText('Observaciones', $cellHCenteredEnc);
		
		//SENTENCIA BASE
		$query  = " SELECT a.id, a.titulo, a.tipo, c.nombre AS estado, ifnull(a.fechacreacion, '') AS fechacreacion, 
					a.idclientes, a.idproyectos, a.idestados, g.nombre as equipo, g.serie, h.nombre as marca,
					i.nombre as ubicacion, a.observaciones
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id 
					LEFT JOIN estados c ON a.idestados = c.id
					LEFT JOIN clientes f ON a.idclientes = f.id 
					LEFT JOIN activos g ON a.idactivos = g.id 
					LEFT JOIN marcas h ON g.idmarcas = h.id 
					LEFT JOIN ambientes i ON g.idambientes = i.id 
					WHERE a.fecharesolucion is null ";	
		//FILTROS	 
		if($idclientes != ''){
			$query .= " AND a.idclientes = ".$idclientes." ";
		}
		if($idproyectos != ''){
			$query .= " AND a.idproyectos IN (".$idproyectos.") ";
		}
		if($tipo != ''){
			$query .= " AND a.tipo IN (".$tipo.") ";
		}
		if($fechadesdec != ''){
			$query .= " AND (a.fechacreacion >= '".$fechadesdec."' ";
		}
		if($fechahastac != ''){
			$query .= " AND a.fechacreacion <= '".$fechahastac."') ";
		}
		
		$query  .= " ORDER BY a.id DESC ";
		//echo $query;
		$result = $mysqli->query($query);
	
		while($rowc = $result->fetch_assoc()){
			$numeroreq = str_pad($rowc['id'], 4, "0", STR_PAD_LEFT);			
			$row = $table->addRow();
			$row->addCell(1000, $cellVCentered)->addText($numeroreq, $cellHCentered);
			$row->addCell(1300, $cellVCentered)->addText($rowc['titulo'], $cellHCentered);
			if($rowc['estado'] != 'Resuelto'){
				$row->addCell(1000, $styleCell)->addText($rowc['estado'], $cellHCentered);
			}else{
				$row->addCell(1000, $cellVCentered)->addText($rowc['estado'], $cellHCentered);
			}
			$row->addCell(1000, $cellVCentered)->addText($rowc['equipo'], $cellHCentered);
			$row->addCell(1000, $cellVCentered)->addText($rowc['serie'], $cellHCentered);
			$row->addCell(1000, $cellVCentered)->addText($rowc['marca'], $cellHCentered);
			$row->addCell(1000, $cellVCentered)->addText($rowc['fechacreacion'], $cellHCentered);
			$row->addCell(1000, $cellVCentered)->addText($rowc['observaciones'], $cellHCentered);		
		}
		*/
	}

	//INCIDENCIAS / SOLICITUDES
	$section->addTextBreak(2);	
 
	
	//SENTENCIA BASE
	$query  = " SELECT a.id, a.titulo, a.tipo, c.nombre AS estado, a.horastrabajadas, 
				ifnull(a.fechacreacion, '') AS fechacreacion, ifnull(a.fecharesolucion, '') AS fecharesolucion, 
				a.idclientes, a.idproyectos, a.idestados, a.resolucion, e.prioridad, IFNULL(f.nombre,'Sin Asignar') AS asignado				
				FROM incidentes a
				LEFT JOIN proyectos b ON a.idproyectos = b.id 
				LEFT JOIN estados c ON a.idestados = c.id 
				LEFT JOIN clientes d ON a.idclientes = d.id 
				LEFT JOIN sla e ON a.idprioridades = e.id 
				LEFT JOIN usuarios f ON a.asignadoa = f.correo 
				WHERE 1 ";	
	//FILTROS	 
	if($idclientes != ''){
		$query .= " AND a.idclientes = ".$idclientes." ";
	}
	if($idproyectos != ''){
		$query .= " AND a.idproyectos IN (".$idproyectos.") ";
	}
	if($tipo != ''){
		$query .= " AND a.tipo IN ('incidentes') ";
	}
	/* if($fechadesdec != ''){
		$query .= " AND ((a.fechacreacion >= '".$fechadesdec."' ";
	}
	if($fechahastac != ''){
		$query .= " AND a.fechacreacion <= '".$fechahastac."') ";
	}
	if($fechadesder != ''){
		$query .= " OR (a.fecharesolucion >= '".$fechadesder."' ";
	}
	if($fechahastar != ''){
		$query .= " AND a.fecharesolucion <= '".$fechahastar."')) ";
	} */ 
	if($fechadesder != ''){
		$query .= " AND (a.fecharesolucion >= '".$fechadesder."' ";
	}
	if($fechahastar != ''){
		$query .= " AND a.fecharesolucion <= '".$fechahastar."') ";
	}
	$query  .= " ORDER BY a.asignadoa ASC ";
	$result = $mysqli->query($query);
	$recordsTotal = $result->num_rows;
	
	$section->addText('3.	Gestión de Incidencias / Solicitudes ('.$recordsTotal.')', $fontStyle5);
	$section->addText('En este cuadro se muestran todas las incidencias y solicitudes recibidas durante el periodo (mensual). Se divide por proveedor.', $fontStyle6);
	
	$asignado3  = '';
	$nrohoras 	= 0;
	$nrominut 	= 0;
	$totalFinal = 0;
	$contar 	= 0;
	$totalsql 	= $result->num_rows;
	$totalsqlf  = $totalsql - 1;
	
	while($rowc = $result->fetch_assoc()){
		 
		if($asignado3 != $rowc['asignado']){ 
			$asignado3 = $rowc['asignado'];
			
			if($contar != 1 && $contar != 0){			
				$row = $table->addRow();
				$row->addCell(1000)->addText('');
				$row->addCell(1300)->addText('');
				$row->addCell(1000)->addText('');
				$row->addCell(1000)->addText('');
				$row->addCell(1000)->addText('');
				$row->addCell(1000)->addText(''); 
				$row->addCell(1000)->addText('');
				$row->addCell(1000)->addText($totalFinal, $fontStyle5);  
			}
			
			$nrohoras = 0;
			$nrominut = 0;
			
			$section->addTextBreak();
			$section->addText($asignado3, $fontStyle5);
			//TABLA
			$phpWord->addTableStyle('Incidencias '.$asignado3, $styleTable);
			$table = $section->addTable('Incidencias '.$asignado3);
			$row = $table->addRow(); 
			$row->addCell(1000, $styleCellEnc)->addText('Caso', $cellHCenteredEnc);
			$row->addCell(1300, $styleCellEnc)->addText('Título', $cellHCenteredEnc);
			$row->addCell(1000, $styleCellEnc)->addText('Estado', $cellHCenteredEnc);
			$row->addCell(1000, $styleCellEnc)->addText('Prioridad', $cellHCenteredEnc);
			$row->addCell(1000, $styleCellEnc)->addText('Resolución', $cellHCenteredEnc);
			$row->addCell(1000, $styleCellEnc)->addText('Fecha de creación', $cellHCenteredEnc);
			$row->addCell(1000, $styleCellEnc)->addText('Fecha de resolución', $cellHCenteredEnc);
			$row->addCell(1000, $styleCellEnc)->addText('Horas trabajadas', $cellHCenteredEnc);
		}
		$numeroreq = str_pad($rowc['id'], 4, "0", STR_PAD_LEFT);		
		$row = $table->addRow();
		$row->addCell(1000, $cellVCentered)->addText($numeroreq, $cellHCentered);
		$row->addCell(1300, $cellVCentered)->addText($rowc['titulo'], $cellHCentered);
		if($rowc['estado'] != 'Resuelto'){
			$row->addCell(1000, $styleCell)->addText($rowc['estado'], $cellHCentered);
		}else{
			$row->addCell(1000, $cellVCentered)->addText($rowc['estado'], $cellHCentered);
		}
		$row->addCell(1000, $cellVCentered)->addText($rowc['prioridad'], $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($rowc['resolucion'], $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($rowc['fechacreacion'], $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($rowc['fecharesolucion'], $cellHCentered);   
		$row->addCell(1000, $cellVCentered)->addText($rowc['horastrabajadas'], $cellHCentered);  
		 
		
		
		$horastrabajadas = $rowc['horastrabajadas'];
		if($horastrabajadas == '0'){
			$horastrabajadas = "00:00";
		}else{
			$horastrabajadas = $horastrabajadas;
		}
		//if($horastrabajadas != 0){
			$arrhoras = explode(":",$horastrabajadas);								 
			//TOTAL HORAS
			$nrohoras += $arrhoras[0];  
			$nrominut += $arrhoras[1]; 
			
			//Ajustar minutos a horas - Horas Trabajadas
			$mindiv = $nrominut / 60;
			$minutf = $nrominut - (intval($mindiv) * 60); 
			$minutf = str_pad($minutf, 2, "0", STR_PAD_LEFT);
			
			$horasf = $nrohoras + intval($mindiv);
			$totalFinal = $horasf.":".$minutf;
		/* }else{
			$nrohoras += $horastrabajadas;
			$nrominut += 0;
		} */
		 
		if($contar == 0 || $contar == $totalsqlf){
			$row = $table->addRow();
			$row->addCell(1000)->addText('');
			$row->addCell(1300)->addText('');
			$row->addCell(1000)->addText('');
			$row->addCell(1000)->addText('');
			$row->addCell(1000)->addText('');
			$row->addCell(1000)->addText('');
			$row->addCell(1000)->addText('');
			$row->addCell(1000)->addText($totalFinal, $fontStyle5);  
		}
		
		$contar ++;
		 
	}
	 
	////////////////////////////////////////////////////////////////////////////////////////////////////
	//Punto 4: Estadística incidentes recibidos vs  incidentes resueltos – incidentes NO resueltos 	  //
	//( todos los estatus diferentes a RESUELTO) 													  //		
	////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$section->addTextBreak(2);
	$section->addText('4.	Estadística de incidentes recibidos Vs resueltos', $fontStyle5); 
	$section->addTextBreak();
	//TABLA
	$phpWord->addTableStyle('Preventivos realizados', $styleTable);
	$table = $section->addTable('Preventivos realizados');

	$row = $table->addRow();
	$row->addCell(1000, $styleCellEnc)->addText('Incidentes Recibidos', $cellHCenteredEnc);
	$row->addCell(1300, $styleCellEnc)->addText('Incidentes Resueltos', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('Incidentes No Resueltos', $cellHCenteredEnc);
	
	//SENTENCIA BASE
	$query  = " SELECT a.id, a.idestados,
				ifnull(a.fechacreacion, '') AS fechacreacion, ifnull(a.fecharesolucion, '') as fecharesolucion
				FROM incidentes a
				WHERE 1 = 1 ";	 
	//FILTROS	 
	if($idclientes != ''){
		$query .= " AND a.idclientes = ".$idclientes." ";
	}
	if($idproyectos != ''){
		$query .= " AND a.idproyectos IN (".$idproyectos.") ";
	}
	if($tipo != ''){
		$query .= " AND a.tipo IN ('incidentes') ";
	}
	if($fechadesdec != ''){
		$query .= " AND ((a.fechacreacion >= '".$fechadesdec."' ";
	}
	if($fechahastac != ''){
		$query .= " AND a.fechacreacion <= '".$fechahastac."') ";
	}   
	$resultP = $mysqli->query($query.")");
	$totalp = $resultP->num_rows;
	if($fechadesder != ''){
		$query .= " OR (a.fecharesolucion >= '".$fechadesder."' ";
	}
	if($fechahastar != ''){
		$query .= " AND a.fecharesolucion <= '".$fechahastar."')) ";
	} 
	
	//echo $query;
	$result = $mysqli->query($query);
	$recordsTotal = $result->num_rows;
	
	$recibidos 	 = 0;
	$resueltos 	 = 0;
	$noresueltos = 0;
	
	while($rowc = $result->fetch_assoc()){
		if($rowc['fecharesolucion'] != '' && $rowc['idestados'] == '16'){
			$resueltos++;
		}
		$recibidos++;
	} 	 
	$noresueltos = $recibidos - $resueltos;
	$row = $table->addRow();
	
	$row->addCell(3000, $cellVCentered)->addText($recibidos, $cellHCentered);
	$row->addCell(3000, $cellVCentered)->addText($resueltos, $cellHCentered);
	$row->addCell(3000, $cellVCentered)->addText($noresueltos, $cellHCentered);
	
	////////////////////////////////////////////////////////////////////////////////////////////////////
	//Punto 5: Equipos fuera de servicio															  //		
	////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//INCIDENCIAS / SOLICITUDES
	$section->addTextBreak(2);	
	//SENTENCIA BASE
	$query  = " SELECT a.id, a.nombre, a.serie, e.nombre AS marca, f.nombre AS modelo,
				d.nombre AS ubicacion, a.idclientes, a.idproyectos, g.desde, g.hasta				
				FROM activos a
				LEFT JOIN proyectos b ON a.idproyectos = b.id 
				LEFT JOIN clientes c ON a.idclientes = c.id 
				LEFT JOIN ambientes d ON a.idambientes = d.id 
				LEFT JOIN marcas e ON a.idmarcas = e.id 
				LEFT JOIN modelos f ON a.idmodelos = f.id 
				LEFT JOIN fueraservicio g ON a.serie = g.codequipo 
				WHERE g.desde is not null AND a.prioridad = 1 ";	//AND g.hasta is not null
	//FILTROS	 
	if($idclientes != ''){
		$query .= " AND a.idclientes = ".$idclientes." ";
	}
	if($idproyectos != ''){
		$query .= " AND a.idproyectos IN (".$idproyectos.") ";
	}
	if($fechadesdec != ''){
		$query .= " AND (g.desde >= '".$fechadesdec."' ";
	}
	if($fechahastac != ''){
		$query .= " AND g.desde <= '".$fechahastac."') ";
	}
	if($fechadesdec != ''){
		$query .= " AND (g.hasta >= '".$fechadesdec."' ";
	}
	if($fechahastac != ''){
		$query .= " AND g.hasta <= '".$fechahastac."') ";
	}
	
	$query  .= " ORDER BY a.id DESC ";
	//echo $query;
	$result = $mysqli->query($query);
	$recordsTotal = $result->num_rows;
	
	$section->addText('5.	Equipos fuera de servicio ('.$recordsTotal.')', $fontStyle5);
	$section->addText('Cuadro con listado de equipos prioridad 1 fuera de servicio, con monto de pérdidas.', $fontStyle6);
	
	//TABLA
	$phpWord->addTableStyle('Fuera de servicio', $styleTable);
	$table = $section->addTable('Fuera de servicio');
	$row = $table->addRow();
	$row->addCell(1000, $styleCellEnc)->addText('Nombre', $cellHCenteredEnc);
	$row->addCell(1300, $styleCellEnc)->addText('Serial1', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('Marca', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('Modelo', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('Ubicación', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('Ingresos que genera', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('Fuera de servicio desde', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('Fuera de servicio hasta', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('Días de fuera de servicio', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('Pérdida', $cellHCenteredEnc);
	
	$countfs = 0;
	while($rowc = $result->fetch_assoc()){
		if($rowc['desde'] != '' && $rowc['hasta'] == ''){
			$firstDate  = new DateTime($rowc['desde']);
			$secondDate = new DateTime($rowc['hasta']);
			$intvl = $firstDate->diff($secondDate);
			$diasfs = $intvl->days;
		}else{
			$diasfs = 0;
		}
		$row = $table->addRow();
		$row->addCell(1000, $cellVCentered)->addText($rowc['nombre'], $cellHCentered);
		$row->addCell(1300, $cellVCentered)->addText($rowc['serie'], $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($rowc['marca'], $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($rowc['modelo'], $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($rowc['ubicacion'], $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText('', $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($rowc['desde'], $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($rowc['hasta'], $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($diasfs, $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText('', $cellHCentered);
		$countfs++;
	}
	
	if($countfs == 0){
		$row = $table->addRow();
		$row->addCell(1000)->addText('');
		$row->addCell(1300)->addText('');
		$row->addCell(1000)->addText('');
		$row->addCell(1000)->addText('');
		$row->addCell(1000)->addText('');
		$row->addCell(1000)->addText('');
		$row->addCell(1000)->addText('');
		$row->addCell(1000)->addText('');
		$row->addCell(1000)->addText('');
		$row->addCell(1000)->addText('');
		$section->addText('**No existen equipos fuera de servicio');
	}

	//REEMPLAZO EQUIPOS 
	$section->addTextBreak(2);	
	//SENTENCIA BASE
	$query  = " SELECT g.nombre AS equipo, g.serie, h.nombre AS marca, i.nombre as modelo, j.nombre AS ubicacion, 
				g.fechainst, g.vidautil, g.fechareemplazo 
				FROM activos g 
				LEFT JOIN marcas h ON g.idmarcas = h.id 
				LEFT JOIN modelos i ON g.idmodelos = i.id 
				LEFT JOIN ambientes j ON g.idambientes = j.id 
				WHERE g.vidautil <= 12 AND g.vidautil is not null AND g.vidautil != 0 ";
	//FILTROS	 
	if($idclientes != ''){
		$query .= " AND g.idclientes = ".$idclientes." ";
	}
	if($idproyectos != ''){
		$query .= " AND g.idproyectos IN (".$idproyectos.") ";
	}
	/*
	if($fechadesdec != ''){
		$query .= " AND ((a.fechacreacion >= '".$fechadesdec."' ";
	}
	if($fechahastac != ''){
		$query .= " AND a.fechacreacion <= '".$fechahastac."') ";
	}
	if($fechadesder != ''){
		$query .= " OR (a.fecharesolucion >= '".$fechadesder."' ";
	}
	if($fechahastar != ''){
		$query .= " AND a.fecharesolucion <= '".$fechahastar."')) ";
	}
	*/
	
	$query  .= " ORDER BY g.nombre ASC ";
	//echo $query;
	$result = $mysqli->query($query);
	$recordsTotal = $result->num_rows;
	
	$section->addText('6.	Reemplazo de equipos('.$recordsTotal.')', $fontStyle5);
	$section->addText('Lista de equipos cuya fecha de reemplazo es igual o menor a 12 meses.', $fontStyle6);
	
	//TABLA
	$phpWord->addTableStyle('Reemplazo de equipos', $styleTable);
	$table = $section->addTable('Reemplazo de equipos');
	$row = $table->addRow();
	$row->addCell(1000, $styleCellEnc)->addText('Nombre', $cellHCenteredEnc);
	$row->addCell(1300, $styleCellEnc)->addText('Serial1', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('Marca', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('Modelo', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('Ubicación', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('F. instalación', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('Vida util (meses)', $cellHCenteredEnc);
	$row->addCell(1000, $styleCellEnc)->addText('F. reemplazo', $cellHCenteredEnc);
	
	$countfs = 0;
	while($rowc = $result->fetch_assoc()){
		$row = $table->addRow();
		$row->addCell(1000, $cellVCentered)->addText($rowc['equipo'], $cellHCentered);
		$row->addCell(1300, $cellVCentered)->addText($rowc['serie'], $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($rowc['marca'], $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($rowc['modelo'], $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($rowc['ubicacion'], $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($rowc['fechainst'], $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($rowc['vidautil'], $cellHCentered);
		$row->addCell(1000, $cellVCentered)->addText($rowc['fechareemplazo'], $cellHCentered);
		$countfs++;
	}
	
	if($countfs == 0){
		$row = $table->addRow();
		$row->addCell(1000)->addText('');
		$row->addCell(1300)->addText('');
		$row->addCell(1000)->addText('');
		$row->addCell(1000)->addText('');
		$row->addCell(1000)->addText('');
		$section->addText('**No existen equipos cuya fecha de reemplazo es igual o menor a 12 meses');
	}

	//SERVICIO DE INTERNET
	$section->addTextBreak(2);
	$section->addText('7.	Servicio de Internet', $fontStyle5);
	$section->addText('Se puede observar que durante el tiempo monitoreado el servicio estuvo XXX disponible, con perdida de paquetes XXX', $fontStyle6);

	//MONITOREO DE ANALISIS PREVENTIVO EN INFRAESTRUCTURA TECNOLOGICA
	$section->addTextBreak(2);
	$section->addText('8.	Monitoreo de análisis preventivo en infraestructura tecnológica', $fontStyle5);
	$section->addText('En el monitoreo diario a través de la herramienta PRTG monitoreamos los sensores configurados en los principales equipos de infraestructura tecnológica, que son los siguientes:', $fontStyle6);

//FIN
$section = $phpWord->addSection( array("marginTop" => 0, "marginBottom" => 0) );
$section->addImage(
	'images/fondo.png',
	array(
		'positioning'      => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
		'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
		'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
		'posVertical'      => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
		'posVerticalRel'   => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,
		'marginTop'          => -50,
		'marginLeft'         => 0,
		'width'              => 700,
		'height'             => 900,
		'wrappingStyle'      => 'behind'
	)
);
	
$section->addImage(
    'images/blanco.png',
    array(
        'width'            => '150',
        //'height'         => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(3),
        'positioning'      => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
        'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
        'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
        'posVertical'      => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
        'posVerticalRel'   => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,
		'alignment' 		=> \PhpOffice\PhpWord\SimpleType\Jc::RIGHT,
        'marginLeft'       => 150,
        'marginTop'        => 0,		
		'wrappingStyle'    => 'behind',
    )
);
$section->addImage(
    'images/avatar-blanco.png',
    array(
        'width'            => '40',
        //'height'         => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(3),
        'positioning'      => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
        'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_LEFT,
        'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
        'posVertical'      => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
        'posVerticalRel'   => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,
		'alignment' 		=> \PhpOffice\PhpWord\SimpleType\Jc::RIGHT,
        'marginLeft'       => 150,
        'marginTop'        => 0,		
		'wrappingStyle'    => 'behind',
    )
);
$section->addTextBreak(28);

$fontStyle3 = array('size' => 12, 'color' => 'FFFFFF', 'alignment' => 'right');
$section->addText('                                                                                  info@maxialatam.com', $fontStyle3);
$section->addText('                                                                                  Ave. Samuel Lewis y Calle 54', $fontStyle3);
$section->addText('                                                                                  T302-0112F302-0115', $fontStyle3);
printSeparatorFooter($section);
$section->addText('                                                                                  maxialatam.com', $fontStyle3);
$section->addText('                                                                                  @maxialatam', $fontStyle3);


function printSeparator(Section $section)
{
    $section->addTextBreak();
    $lineStyle = array('weight' => 2, 'width' => 260, 'height' => 0, 'align' => 'right', 'color' => 'white');
    $section->addLine($lineStyle);
    //$section->addTextBreak(2);
}

function printSeparatorFooter(Section $section)
{
    $section->addTextBreak();
    $lineStyle = array('weight' => 1, 'width' => 175, 'height' => 0, 'align' => 'right', 'color' => 'white');
    $section->addLine($lineStyle);
}

// Save file
// Saving the document as OOXML file...
//$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
//$objWriter->save('helloWorld.docx');
 
/* echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
} */

/////////////////////////////////DESCARGA SIN GUARDAR
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007', $download = true);
 
header('Content-Disposition: attachment; filename='.$siglascliente.''.date("Y_m_d").'.docx'); 
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document'); 

//header('Content-Disposition: attachment; filename='.$siglascliente.''.date("Y_m_d").'.docx; charset=iso-8859-1'); 
ob_clean();
$objWriter->save("php://output");
////////////////////////////////////