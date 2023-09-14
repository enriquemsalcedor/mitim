<?php
include('../conexion.php');
global $mysqli;

//date_default_timezone_set('Europe/London');

$data 	 = '';
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
//require_once '../../repositorio-lib/xls/Classes/PHPExcel.php';
require_once '../../repositorio-lib/xls/Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maxia Latam")
->setLastModifiedBy("Maxia Latam")
->setTitle("Plantilla creación de plan de mantenimiento")
->setSubject("Plantilla creación de plan de mantenimiento")
->setDescription("Plantilla creación de plan de mantenimiento")
->setKeywords("Plantilla creación de plan de mantenimiento")
->setCategory("Plantillas");

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
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	)
);
$style2 = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
	)
);

$azulOscuro = '1F3D7B';
$valoreslistas = array();
$sheet = 2;

// ************************************************ CLIENTES ******************************************
$hojaClientes = $objPHPExcel->createSheet($sheet);
$sheet++;
$hojaClientes->setCellValue('A1', 'Clientes');

//LETRA
$hojaClientes->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaClientes->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaClientes->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "SELECT nombre FROM clientes ";
$resultclientes = $mysqli->query($q);
$i = 2;

while($cliente = $resultclientes->fetch_assoc()){
	$hojaClientes->setCellValue('A'.$i, $cliente['nombre']);
	$valoreslistas['clientes'][] = $cliente['nombre'];
	$i++;
}

$hojaClientes->setTitle('Clientes');
$hojaClientes->getColumnDimension('A')->setWidth(60);
$objPHPExcel->getSheetByName('Clientes')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ PROYECTOS ******************************************
$hojaProyectos = $objPHPExcel->createSheet($sheet);
$sheet++;
$hojaProyectos->setCellValue('A1', 'Proyectos');

//LETRA
$hojaProyectos->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaProyectos->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaProyectos->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "SELECT nombre FROM proyectos ";
$resultproyectos = $mysqli->query($q);
$i = 2;

while($fila = $resultproyectos->fetch_assoc()){
	$hojaProyectos->setCellValue('A'.$i, $fila['nombre']);
	$valoreslistas['proyectos'][] = $fila['nombre'];
	$i++;
}

$hojaProyectos->setTitle('Proyectos');
$hojaProyectos->getColumnDimension('A')->setWidth(60);
$objPHPExcel->getSheetByName('Proyectos')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ DEPARTAMENTOS ******************************************
$hojaDepartamentos = $objPHPExcel->createSheet($sheet);
$sheet++;
$hojaDepartamentos->setCellValue('A1', 'Departamentos');

//LETRA
$hojaDepartamentos->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaDepartamentos->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaDepartamentos->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "SELECT nombre FROM departamentos ";
$resultdepartamentos = $mysqli->query($q);
$i = 2;

while($fila = $resultdepartamentos->fetch_assoc()){
	$hojaDepartamentos->setCellValue('A'.$i, $fila['nombre']);
	$valoreslistas['departamentos'][] = $fila['nombre'];
	$i++;
}

$hojaDepartamentos->setTitle('Departamentos');
$hojaDepartamentos->getColumnDimension('A')->setWidth(60);
$objPHPExcel->getSheetByName('Departamentos')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ SERVICIOS ******************************************
$hojaServicios = $objPHPExcel->createSheet($sheet);
$sheet++;
$hojaServicios->setCellValue('A1', 'Servicios');

//LETRA
$hojaServicios->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaServicios->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaServicios->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "SELECT nombre FROM servicios";
$resultservicios = $mysqli->query($q);

$i = 2;

while($servicio = $resultservicios->fetch_assoc()){
	$hojaServicios->setCellValue('A'.$i, $servicio['nombre']);
	$valoreslistas['servicios'][] = $servicio['nombre'];
	$i++;
}

$hojaServicios->setTitle('Servicios');
$hojaServicios->getColumnDimension('A')->setWidth(60);
$objPHPExcel->getSheetByName('Servicios')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ SISTEMAS ******************************************
$hojaSistemas = $objPHPExcel->createSheet($sheet);
$sheet++;
$hojaSistemas->setCellValue('A1', 'Sistemas');

//LETRA
$hojaSistemas->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaSistemas->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaSistemas->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "SELECT nombre FROM sistemas ";
$resultsistemas = $mysqli->query($q);
$i = 2;

while($fila = $resultsistemas->fetch_assoc()){
	$hojaSistemas->setCellValue('A'.$i, $fila['nombre']);
	$valoreslistas['sistemas'][] = $fila['nombre'];
	$i++;
}

$hojaSistemas->setTitle('Sistemas');
$hojaSistemas->getColumnDimension('A')->setWidth(60);
$objPHPExcel->getSheetByName('Sistemas')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ FRECUENCIAS ******************************************
$hojaFrecuencias = $objPHPExcel->createSheet($sheet);
$sheet++;
$hojaFrecuencias->setCellValue('A1', 'Frecuencias');

//LETRA
$hojaFrecuencias->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaFrecuencias->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaFrecuencias->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$hojaFrecuencias
		->setCellValue('A2', 'Diaria')
		->setCellValue('A3', 'Semanal')
		->setCellValue('A4', 'Quincenal')
		->setCellValue('A5', 'Mensual')
		->setCellValue('A6', 'Bimestral')
		->setCellValue('A7', 'Trimestral')
		->setCellValue('A8', 'Cuatrimestral')
		->setCellValue('A9', 'Pentamestral')
		->setCellValue('A10', 'Semestral')
		->setCellValue('A11', 'Anual');

$hojaFrecuencias->setTitle('Frecuencias');
$hojaFrecuencias->getColumnDimension('A')->setWidth(60);
$objPHPExcel->getSheetByName('Frecuencias')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ TIPO DE PLAN ******************************************
$hojaPlan = $objPHPExcel->createSheet($sheet);
$sheet++;
$hojaPlan->setCellValue('A1', 'Tipo de plan');

//LETRA
$hojaPlan->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaPlan->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaPlan->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);


$hojaPlan
		->setCellValue('A2', 'Automático')
		->setCellValue('A3', 'Manual')
		->setCellValue('A4', 'Desactivar');

$hojaPlan->setTitle('Tipo de plan');
$hojaPlan->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getSheetByName('Tipo de plan')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ AMBIENTES ******************************************
$hojaAmbientes = $objPHPExcel->createSheet($sheet);
$sheet++;
$hojaAmbientes->setCellValue('A1', 'Ambientes');

//LETRA
$hojaAmbientes->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaAmbientes->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaAmbientes->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "SELECT nombre FROM ambientes ";
$resultambientes = $mysqli->query($q);
$i = 2;

while($fila = $resultambientes->fetch_assoc()){
	$hojaAmbientes->setCellValue('A'.$i, $fila['nombre']);
	$valoreslistas['ambientes'][] = $fila['nombre'];
	$i++;
}

$hojaAmbientes->setTitle('Ambientes');
$hojaAmbientes->getColumnDimension('A')->setWidth(60);
$objPHPExcel->getSheetByName('Ambientes')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ RESPONSABLES ******************************************
$hojaResponsables = $objPHPExcel->createSheet($sheet);
$sheet++;
$hojaResponsables->setCellValue('A1', 'Responsables');

//LETRA
$hojaResponsables->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaResponsables->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaResponsables->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "SELECT nombre FROM usuarios";
$resultresp = $mysqli->query($q);
$i = 2;

while($fila = $resultresp->fetch_assoc()){
	$hojaResponsables->setCellValue('A'.$i, $fila['nombre']);
	$valoreslistas['responsables'][] = $fila['nombre'];
	$i++;
}

$hojaResponsables->setTitle('Responsables');
$hojaResponsables->getColumnDimension('A')->setWidth(60);
$objPHPExcel->getSheetByName('Responsables')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ ENCABEZADO ****************************************** 
$hojaHeader = $objPHPExcel->createSheet(1);

$hojaHeader->setCellValue('A1', 'Clientes');
$hojaHeader->setCellValue('B1', 'Proyectos');
$hojaHeader->setCellValue('C1', 'Departamentos');
$hojaHeader->setCellValue('D1', 'Servicios');
$hojaHeader->setCellValue('E1', 'Sistemas');
$hojaHeader->setCellValue('F1', 'Actividad');
$hojaHeader->setCellValue('G1', 'Frecuencia');
$hojaHeader->setCellValue('H1', 'Formulario');
$hojaHeader->setCellValue('I1', 'Observación');
$hojaHeader->setCellValue('J1', 'Tipo de plan');
$hojaHeader->setCellValue('K1', 'Responsables');
$hojaHeader->setCellValue('L1', 'Ambientes');

//LETRA
$hojaHeader->getStyle('A1:L1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaHeader->getStyle('A1:L1')->applyFromArray($style2);
//FONDO
$hojaHeader->getStyle('A1:L1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);
// ALTURA
$hojaHeader->getRowDimension('1')->setRowHeight(20);

// DATOS PARA LAS LISTAS
// CLIENTES
$listaclientes = '';
$valoreslistas['clientes'] = array();

$arr = $hojaClientes->rangeToArray(
        'A2:A'.($resultclientes->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );
	
foreach($arr as $ar){
	$listaclientes .= $ar[0].',';
}
$listaclientes = substr($listaclientes,0,-1);

// PROYECTOS
$listaproyectos = '';
$valoreslistas['proyectos'] = array();

$arr = $hojaProyectos->rangeToArray(
        'A2:A'.($resultproyectos->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );
	
foreach($arr as $ar){
	$listaproyectos .= $ar[0].',';
}
$listaproyectos = substr($listaproyectos,0,-1);

// DEPARTAMENTOS
$listadepartamentos = '';
$valoreslistas['departamentos'] = array();

$arr = $hojaDepartamentos->rangeToArray(
        'A2:A'.($resultdepartamentos->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );
	
foreach($arr as $ar){
	$listadepartamentos .= $ar[0].',';
}
$listadepartamentos = substr($listadepartamentos,0,-1);

// SERVICIOS
$listaservicios = '';
//$listaservicios = implode ( ',' , $valoreslistas['servicios'] );
$valoreslistas['servicios'] = array();

$arr = $hojaServicios->rangeToArray(
        'A2:A'.($resultservicios->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );
	
foreach($arr as $ar){
	$listaservicios .= $ar[0].',';
}
$listaservicios = substr($listaservicios,0,-1);

// SISTEMAS
$listasistemas = implode ( ',' , $valoreslistas['sistemas'] );
$valoreslistas['sistemas'] = array();

$listasistemas = '';
$arr = $hojaSistemas->rangeToArray(
        'A2:A'.($resultsistemas->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );
	
foreach($arr as $ar){
	$listasistemas .= $ar[0].',';
}
$listasistemas = substr($listasistemas,0,-1);

// FRECUENCIAS
$arr = $hojaFrecuencias->rangeToArray('A2:A10',NULL,TRUE,FALSE);
$listafrecuencias = '';
foreach($arr as $pos) {
	$listafrecuencias .= $pos[0].',';
}
$listafrecuencias = substr($listafrecuencias,0,-1);

// PLANES
$arr = $hojaPlan->rangeToArray('A2:A4',NULL,TRUE,FALSE);
$listaplan = '';
foreach($arr as $pos) {
	$listaplan .= $pos[0].',';
}
$listaplan = substr($listaplan,0,-1);

// RESPONSABLES
$listaresp = implode ( ',' , $valoreslistas['responsables'] );
$valoreslistas['responsables'] = array();

// AMBIENTES
$listaambientes = implode ( ',' , $valoreslistas['ambientes'] );
$valoreslistas['ambientes'] = array();

// LISTAS
	// ******************************************************************** LISTA DE CLIENTES
	$objValidation = $hojaHeader->getCell('A2')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setErrorTitle('');
	$objValidation->setAllowBlank(true);
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('Clientes!$A$2:$A$'.($resultclientes->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('A'.$n)->setDataValidation(clone $objValidation); 
	}

// ******************************************************************** LISTA DE PROYECTOS
	$objValidation = $hojaHeader->getCell('B2')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setErrorTitle('');
	$objValidation->setAllowBlank(true);
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('Proyectos!$A$2:$A$'.($resultproyectos->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('B'.$n)->setDataValidation(clone $objValidation); 
	}

// ******************************************************************** LISTA DE DEPARTAMENTOS
	$objValidation = $hojaHeader->getCell('C2')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setErrorTitle('');
	$objValidation->setAllowBlank(true);
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('Departamentos!$A$2:$A$'.($resultdepartamentos->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('C'.$n)->setDataValidation(clone $objValidation); 
	}

// ******************************************************************** LISTA DE SERVICIOS
	$objValidation = $hojaHeader->getCell('D2')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setErrorTitle('');
	$objValidation->setAllowBlank(true);
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('Servicios!$A$2:$A$'.($resultservicios->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('D'.$n)->setDataValidation(clone $objValidation); 
	}	

	// ******************************************************************** LISTA DE SISTEMAS
	$objValidation = $hojaHeader->getCell('E2')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setErrorTitle('');
	$objValidation->setAllowBlank(true);
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('Sistemas!$A$2:$A$'.($resultsistemas->num_rows+1));
	
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('E'.$n)->setDataValidation(clone $objValidation); 
	}	

	// ************************************** LISTA DE FRECUENCIAS
	$objValidation = $hojaHeader->getCell('G2')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setErrorTitle('');
	$objValidation->setAllowBlank(true);
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('"'.$listafrecuencias.'"');
	
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('G'.$n)->setDataValidation(clone $objValidation); 
	}

	// ********************************************** LISTA DE PLAN
	$objValidation = $hojaHeader->getCell('J2')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setAllowBlank(true);
	$objValidation->setErrorTitle('');
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('"'.$listaplan.'"');
	
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('J'.$n)->setDataValidation(clone $objValidation); 
	}

	// **************************************************************** LISTA DE RESPONSABLES
	$objValidation = $hojaHeader->getCell('K2')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setAllowBlank(true);
	$objValidation->setErrorTitle('');
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('Responsables!$A$2:$A$'.($resultresp->num_rows+1));
	
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('K'.$n)->setDataValidation(clone $objValidation); 
	}

	// **************************************************************** LISTA DE AMBIENTES
	$objValidation = $hojaHeader->getCell('L2')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setAllowBlank(true);
	$objValidation->setErrorTitle('');
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('Ambientes!$A$2:$A$'.($resultambientes->num_rows+1));
	
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('L'.$n)->setDataValidation(clone $objValidation); 
	}

//Anchos
$hojaHeader->getColumnDimension('A')->setWidth(30);
$hojaHeader->getColumnDimension('B')->setWidth(45);
$hojaHeader->getColumnDimension('C')->setWidth(30);
$hojaHeader->getColumnDimension('D')->setAutoSize(true);
$hojaHeader->getColumnDimension('E')->setAutoSize(true);
$hojaHeader->getColumnDimension('F')->setAutoSize(true);
$hojaHeader->getColumnDimension('G')->setAutoSize(true);
$hojaHeader->getColumnDimension('H')->setWidth(22);
$hojaHeader->getColumnDimension('I')->setWidth(14);
$hojaHeader->getColumnDimension('J')->setWidth(28);
$hojaHeader->getColumnDimension('K')->setWidth(28);
$hojaHeader->getColumnDimension('L')->setWidth(28);

//$hojaHeader->getStyle('A5:H'.$i)->getAlignment()->setWrapText(true);

//Renombrar hoja de Excel
$hojaHeader->setTitle('Plan');

// *************** FINAL

$objPHPExcel->removeSheetByIndex(
    $objPHPExcel->getIndex(
        $objPHPExcel->getSheetByName('Worksheet')
    )
);

$objPHPExcel->setActiveSheetIndex(0);


//Redirigir la salida al navegador del cliente
$hoy = date('dmY');
$nombreArc = 'Plantilla plan de mantenimiento - '.$hoy.'.xls';
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$nombreArc.'"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit();

?>