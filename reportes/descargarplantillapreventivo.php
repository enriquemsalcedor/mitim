<?php
include('../conexion.php');
global $mysqli;

//date_default_timezone_set('Europe/London');

$data 	 = '';
$nivel  = $_SESSION['nivel'];
$usuario     =  $_SESSION['usuario'];
$idempresas = 1;
$idclientes  = $_REQUEST['idclientes'];
$idproyectos = $_REQUEST['idproyectos'];

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

//Call the autoload
require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';
//load phpspreadsheet class using namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//call xlsx writer class to make an xlsx file
use PhpOffice\PhpSpreadsheet\IOFactory;

//make a new spreadsheet object
$spreadsheet = new Spreadsheet();
//obtener la hoja activa actual, (que es la primera hoja)
$sheet = $spreadsheet->getActiveSheet(); 
$fontColor = new \PhpOffice\PhpSpreadsheet\Style\Color();
$fontColor->setRGB('ffffff');
$style2 = array(
		'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
		)
);
$azulOscuro = '293F76';
// Set document properties
$spreadsheet->getProperties()->setCreator("Maxia Latam")
->setLastModifiedBy("Maxia Latam")
->setTitle("Plantilla creación de preventivos")
->setSubject("Plantilla creación de preventivos")
->setDescription("Plantilla creación de preventivos")
->setKeywords("Plantilla creación de preventivos")
->setCategory("Plantillas"); 
 
$valoreslistas = array();
$sheet = 2;

$c = "SELECT nombre FROM clientes WHERE id = ".$idclientes." ";
$resultclientes = $mysqli->query($c); 
if($cliente = $resultclientes->fetch_assoc()){
	$ncliente = $cliente['nombre'];
}

$p = "SELECT nombre FROM proyectos WHERE id = ".$idproyectos." ";
$resultproyectos = $mysqli->query($p); 
if($proyecto = $resultproyectos->fetch_assoc()){
	$nproyecto = $proyecto['nombre'];
}

// ************************************************ CLIENTES ******************************************
$hojaClientes = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaClientes->setCellValue('A1', 'Clientes');

//LETRA
$hojaClientes->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaClientes->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaClientes->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "SELECT a.nombre FROM clientes a ";
if($nivel != 1 && $nivel != 2){
	$q .= " LEFT JOIN usuarios b ON find_in_set(a.id, b.idclientes)
				WHERE b.usuario = '".$usuario."' ";
}else{
	$q .= " WHERE a.id != 0 ";
}
if($idclientes != 0){
	$arr = strpos($idclientes, ',');
	if ($arr !== false) {
  
		$q  .= " AND a.id IN (".$idclientes.") ";
	}else{
		$q  .= " AND find_in_set($idclientes,a.id) ";
	}  
}
//debugL('CLIENTES: '.$q);
$resultclientes = $mysqli->query($q);
$i = 2;

while($cliente = $resultclientes->fetch_assoc()){
	$hojaClientes->setCellValue('A'.$i, $cliente['nombre']);
	$valoreslistas['clientes'][] = $cliente['nombre'];
	$i++;
}

$hojaClientes->setTitle('Clientes');
$hojaClientes->getColumnDimension('A')->setWidth(60);
//$spreadsheet->getSheetByName('Clientes')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ PROYECTOS ******************************************
$hojaProyectos = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaProyectos->setCellValue('A1', 'Proyectos');

//LETRA
$hojaProyectos->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaProyectos->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaProyectos->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q  = " SELECT a.nombre 
		FROM proyectos a
		LEFT JOIN clientes b ON a.idclientes = b.id ";
if($nivel != 1 && $nivel != 2){
	$q .= " LEFT JOIN usuarios c ON find_in_set(a.id, c.idproyectos)
			WHERE c.usuario = '".$usuario."' ";
}else{
	$q .= " WHERE 1 = 1 ";
}
if($idclientes != 0){
	$arr = strpos($idclientes, ',');
	if ($arr !== false) {
  
		$q  .= " AND a.idclientes IN (".$idclientes.") ";
	}else{
		$q  .= " AND find_in_set($idclientes,a.idclientes) ";
	}  
}
$resultproyectos = $mysqli->query($q);
$i = 2;

while($fila = $resultproyectos->fetch_assoc()){
	$hojaProyectos->setCellValue('A'.$i, $fila['nombre']);
	$valoreslistas['proyectos'][] = $fila['nombre'];
	$i++;
}

$hojaProyectos->setTitle('Proyectos');
$hojaProyectos->getColumnDimension('A')->setWidth(60);
//$spreadsheet->getSheetByName('Proyectos')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ CATEGORIAS ******************************************
$hojaCategorias = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaCategorias->setCellValue('A1', 'Categorias');

//LETRA
$hojaCategorias->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaCategorias->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaCategorias->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "	SELECT nombre FROM categorias a
		INNER JOIN categoriaspuente b ON b.idcategorias = a.id
		WHERE 
		b.tipo LIKE '%Preventivo%' AND b.idclientes = ".$idclientes." AND b.idproyectos = ".$idproyectos." ";
$q  .= " ORDER BY nombre ASC ";
$resultcategorias = $mysqli->query($q);
$i = 2;

while($fila = $resultcategorias->fetch_assoc()){
	$hojaCategorias->setCellValue('A'.$i, $fila['nombre']);
	$valoreslistas['categorias'][] = $fila['nombre'];
	$i++;
}

$hojaCategorias->setTitle('Categorias');
$hojaCategorias->getColumnDimension('A')->setWidth(60);
//$spreadsheet->getSheetByName('Categorias')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ PRIORIDADES ******************************************
$hojaPrioridades = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaPrioridades->setCellValue('A1', 'Prioridades');

//LETRA
$hojaPrioridades->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaPrioridades->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaPrioridades->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "SELECT prioridad as nombre FROM sla ";
$resultprioridades = $mysqli->query($q);
$i = 2;

while($fila = $resultprioridades->fetch_assoc()){
	$hojaPrioridades->setCellValue('A'.$i, $fila['nombre']);
	$valoreslistas['prioridades'][] = $fila['nombre'];
	$i++;
}

$hojaPrioridades->setTitle('Prioridades');
$hojaPrioridades->getColumnDimension('A')->setWidth(60);
//$spreadsheet->getSheetByName('Prioridades')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ UBICACIONES ******************************************
$hojaSitios = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaSitios->setCellValue('A1', 'Ubicaciones');

//LETRA
$hojaSitios->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaSitios->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaSitios->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "	SELECT a.nombre AS ambiente 
		FROM ambientes a 
		INNER JOIN ambientespuente b ON b.idambientes = a.id
		";

$q .= " WHERE 1 = 1 ";
if($idempresas != 0){
	$q .= " AND b.idempresas = ".$idempresas;
}
if($idclientes != 0){
	$arr = strpos($idclientes, ',');
	if ($arr !== false) {
  
		$q  .= " AND b.idclientes IN (".$idclientes.") ";
	}else{
		$q  .= " AND find_in_set($idclientes,b.idclientes) ";
	}  
}
if($idproyectos != 0){
	$arr = strpos($idproyectos, ',');
	if ($arr !== false) {
		$q  .= " AND b.idproyectos IN (".$idproyectos.") ";
	}else{
		$q  .= " AND find_in_set($idproyectos,b.idproyectos) ";
	}  
}
$q .= " GROUP BY a.id ";
$resultsitios = $mysqli->query($q);
$i = 2;

while($fila = $resultsitios->fetch_assoc()){
	$hojaSitios->setCellValue('A'.$i, $fila['ambiente']);
	$valoreslistas['ambientes'][] = $fila['ambiente'];
	$i++;
}

$hojaSitios->setTitle('Ubicaciones');
$hojaSitios->getColumnDimension('A')->setWidth(60);
//$spreadsheet->getSheetByName('Ambientes')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ AREAS ******************************************
$hojaAreas = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaAreas->setCellValue('A1', 'Areas');

//LETRA
$hojaAreas->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaAreas->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaAreas->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "	SELECT s.nombre as subambiente
		FROM subambientes s 
		INNER JOIN subambientespuente sp ON sp.idsubambientes = s.id
		INNER JOIN ambientes a on a.id = sp.idambientes
		";

$q .= " WHERE 1 = 1 ";
if($idempresas != 0){
	$q .= " AND sp.idempresas = ".$idempresas;
}
if($idclientes != 0){
	$arr = strpos($idclientes, ',');
	if ($arr !== false) {
  
		$q  .= " AND sp.idclientes IN (".$idclientes.") ";
	}else{
		$q  .= " AND find_in_set($idclientes,sp.idclientes) ";
	}  
}
$q .= " GROUP BY s.id ";
$resultareas = $mysqli->query($q);
$i = 2;

while($fila = $resultareas->fetch_assoc()){
	$hojaAreas->setCellValue('A'.$i, $fila['subambiente']);
	$valoreslistas['subambientes'][] = $fila['subambiente'];
	$i++;
}

$hojaAreas->setTitle('Areas');
$hojaAreas->getColumnDimension('A')->setWidth(60);
//$spreadsheet->getSheetByName('Ambientes')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ SERIES ******************************************
$hojaSeries = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaSeries->setCellValue('A1', 'Serial1');

//LETRA
$hojaSeries->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaSeries->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaSeries->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "SELECT DISTINCT(a.serie) as nombre FROM activos a WHERE 1 ";
//if($nivel == 7){ 
	if($idclientes != ''){
		$arr = strpos($idclientes, ',');
		if ($arr !== false) {
			$q  .= " AND a.idclientes IN (".$idclientes.") ";
		}else{
			$q  .= " AND find_in_set($idclientes,a.idclientes) ";
		}  
	}
	if($idproyectos != ''){
		$arr = strpos($idproyectos, ',');
		if ($arr !== false) {
			$q  .= " AND a.idproyectos IN (".$idproyectos.") ";
		}else{
			$q  .= " AND find_in_set($idproyectos,a.idproyectos) ";
		}  
	}
//}
debugL('serie: '.$q);
$q .= " ORDER BY a.id DESC ";
$resultseries = $mysqli->query($q);
$i = 2;

while($fila = $resultseries->fetch_assoc()){
	$hojaSeries->setCellValue('A'.$i, $fila['nombre']);
	$valoreslistas['series'][] = $fila['nombre'];
	$i++;
}

$hojaSeries->setTitle('Serial1');
$hojaSeries->getColumnDimension('A')->setWidth(60);
//$spreadsheet->getSheetByName('Series')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ RESPONSABLES ******************************************
$hojaResponsables = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaResponsables->setCellValue('A1', 'Responsables');

//LETRA
$hojaResponsables->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaResponsables->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaResponsables->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "SELECT a.nombre FROM usuarios a WHERE nivel IN (2,3,6)"; 
//if($nivel == 7){
	if($idclientes != ''){
		$arr = strpos($idclientes, ',');
		if ($arr !== false) {
			$q  .= " AND a.idclientes IN (".$idclientes.") ";
		}else{
			$q  .= " AND find_in_set($idclientes,a.idclientes) ";
		}  
	}
	if($idproyectos != ''){
		$arr = strpos($idproyectos, ',');
		if ($arr !== false) {
			$q  .= " AND a.idproyectos IN (".$idproyectos.") ";
		}else{
			$q  .= " AND find_in_set($idproyectos,a.idproyectos) ";
		}  
	}
//}
$resultresp = $mysqli->query($q);
$i = 2;

while($fila = $resultresp->fetch_assoc()){
	$hojaResponsables->setCellValue('A'.$i, $fila['nombre']);
	$valoreslistas['responsables'][] = $fila['nombre'];
	$i++;
}

$hojaResponsables->setTitle('Responsables');
$hojaResponsables->getColumnDimension('A')->setWidth(60);
//$spreadsheet->getSheetByName('Responsables')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ Frecuencias ******************************************
$hojaFrecuencias = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaFrecuencias->setCellValue('A1', 'Frecuencias');

//LETRA
$hojaFrecuencias->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaFrecuencias->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaFrecuencias->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);
$frecuencias = array("Diaria", "Semanal", "Quincenal", "Mensual","Bimestral", "Trimestral", "Cuatrimestral", "Pentamestral", "Semestral", "Anual");
$i = 2;
foreach ($frecuencias as $value) {
	$hojaFrecuencias->setCellValue('A'.$i, $value);
	$valoreslistas['frecuencias'][] = $value;
	$i++;
}

$hojaFrecuencias->setTitle('Frecuencias');
$hojaFrecuencias->getColumnDimension('A')->setWidth(60);
//$spreadsheet->getSheetByName('Responsables')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ ENCABEZADO ****************************************** 
$hojaHeader = $spreadsheet->createSheet(1);

$hojaHeader->setCellValue('A1', 'CLIENTE:'); 
$hojaHeader->setCellValue('B1', $ncliente);
$hojaHeader->setCellValue('C1', 'PROYECTO:'); 
$hojaHeader->setCellValue('D1', $nproyecto);

$hojaHeader->setCellValue('A4', 'Título'); 						// 0
$hojaHeader->setCellValue('B4', 'Categorias'); 					//3
$hojaHeader->setCellValue('C4', 'Serial 1'); 					//4
$hojaHeader->setCellValue('D4', 'Ubicaciones'); 				//5
$hojaHeader->setCellValue('E4', 'Área'); 						//6
$hojaHeader->setCellValue('F4', 'Fecha de MP (yyyy-mm-dd)'); 	//7
$hojaHeader->setCellValue('G4', 'Hora de creación (hh:mm)'); 	//8
$hojaHeader->setCellValue('H4', 'Horario'); 					//9
$hojaHeader->setCellValue('I4', 'Prioridad'); 					//10
$hojaHeader->setCellValue('J4', 'Solicitante'); 				//11
$hojaHeader->setCellValue('K4', 'Responsables'); 				//12
$hojaHeader->setCellValue('L4', 'Frecuencia'); 					//13

//LETRA
$hojaHeader->getStyle('A1:D1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaHeader->getStyle('A1:D1')->applyFromArray($style2);
//FONDO
$hojaHeader->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

//LETRA
$hojaHeader->getStyle('A4:L4')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaHeader->getStyle('A4:L4')->applyFromArray($style2);
//FONDO
$hojaHeader->getStyle('A4:L4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);
// ALTURA
$hojaHeader->getRowDimension('1')->setRowHeight(20);

// DATOS PARA LAS LISTAS
// EMPRESAS
$listaempresas = '';
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

// CATEGORIAS
$listacategorias = '';
$valoreslistas['categorias'] = array();
$arr = $hojaCategorias->rangeToArray(
        'A2:A'.($resultcategorias->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );	
foreach($arr as $ar){
	$listacategorias .= $ar[0].',';
}
$listacategorias = substr($listacategorias,0,-1);

// PRIORIDADES
$listaprioridades = '';
$valoreslistas['prioridades'] = array();
$arr = $hojaPrioridades->rangeToArray(
        'A2:A'.($resultprioridades->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );	
foreach($arr as $ar){
	$listaprioridades .= $ar[0].',';
}
$listaprioridades = substr($listaprioridades,0,-1);

// SITIOS
$listasitios = '';
$valoreslistas['ambientes'] = array();
$arr = $hojaSitios->rangeToArray(
        'A2:A'.($resultsitios->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );	
foreach($arr as $ar){
	$listasitios .= $ar[0].',';
}
$listasitios = substr($listasitios,0,-1);

// AREAS
$listaareas = '';
$valoreslistas['subambientes'] = array();
$arr = $hojaAreas->rangeToArray(
        'A2:A'.($resultareas->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );	
foreach($arr as $ar){
	$listaareas .= $ar[0].',';
}
$listaareas = substr($listaareas,0,-1);

// RESPONSABLES
$listaresp = implode ( ',' , $valoreslistas['responsables'] );
$valoreslistas['responsables'] = array();

// Frecuencias
$listafrecuencias = '';
$valoreslistas['frecuencias'] = array();
/*
$arr = $hojaFrecuencias->rangeToArray(
        'A2:A'.($resultfrecuencias->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );	
foreach($arr as $ar){
	$listafrecuencias .= $ar[0].',';
}
$listafrecuencias = substr($listafrecuencias,0,-1);
*/

// EQUIPOS
$listaseries = implode ( ',' , $valoreslistas['series'] );
$valoreslistas['series'] = array();

// LISTAS
// ******************************************************************** LISTA DE EMPRESAS
	/*
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
	$objValidation->setFormula1('Empresas!$A$2:$A$'.($resultempresas->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('A'.$n)->setDataValidation(clone $objValidation); 
	}
	*/

	// ******************************************************************** LISTA DE CLIENTES
	/*
	$objValidation = $hojaHeader->getCell('B5')->getDataValidation();
	$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setErrorTitle('');
	$objValidation->setAllowBlank(true);
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('Clientes!$A$2:$A$'.($resultresp->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('B'.$n)->setDataValidation(clone $objValidation); 
	}
    */
	// ******************************************************************** LISTA DE PROYECTOS
	/*
	$objValidation = $hojaHeader->getCell('C5')->getDataValidation();
	$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
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
		$hojaHeader->getCell('C'.$n)->setDataValidation(clone $objValidation); 
	}
	*/
	// ******************************************************************** LISTA DE CATEGORIAS
	$objValidation = $hojaHeader->getCell('B5')->getDataValidation();
	$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setErrorTitle('');
	$objValidation->setAllowBlank(true);
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('Categorias!$A$2:$A$'.($resultcategorias->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('B'.$n)->setDataValidation(clone $objValidation); 
	}
		
	// **************************************************************** LISTA DE SERIES
	$objValidation = $hojaHeader->getCell('C5')->getDataValidation();
	$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setAllowBlank(true);
	$objValidation->setErrorTitle('');
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('Serial1!$A$2:$A$'.($resultseries->num_rows+1));	
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('C'.$n)->setDataValidation(clone $objValidation); 
	}
	
	// **************************************************************** LISTA DE SITIOS
	$objValidation = $hojaHeader->getCell('D5')->getDataValidation();
	$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setAllowBlank(true);
	$objValidation->setErrorTitle('');
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('Ubicaciones!$A$2:$A$'.($resultsitios->num_rows+1));	
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('D'.$n)->setDataValidation(clone $objValidation); 
	}
	
	// **************************************************************** LISTA DE AREAS
	$objValidation = $hojaHeader->getCell('E5')->getDataValidation();
	$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setAllowBlank(true);
	$objValidation->setErrorTitle('');
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('Areas!$A$2:$A$'.($resultareas->num_rows+1));	
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('E'.$n)->setDataValidation(clone $objValidation); 
	}
	
	// ******************************************************************** LISTA DE PRIORIDADES
	$objValidation = $hojaHeader->getCell('I5')->getDataValidation();
	$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setErrorTitle('');
	$objValidation->setAllowBlank(true);
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('Prioridades!$A$2:$A$'.($resultprioridades->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('I'.$n)->setDataValidation(clone $objValidation); 
	}

	// **************************************************************** LISTA DE RESPONSABLES
	$objValidation = $hojaHeader->getCell('K5')->getDataValidation();
	$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
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
	
	// **************************************************************** LISTA DE FRECUENCIAS
	$objValidation = $hojaHeader->getCell('L5')->getDataValidation();
	$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setAllowBlank(true);
	$objValidation->setErrorTitle('');
	$objValidation->setError('');
	$objValidation->setPromptTitle('Seleccione un valor');
	$objValidation->setPrompt('');
	$objValidation->setFormula1('Frecuencias!$A$2:$A$'.(count($frecuencias)+1));	
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('L'.$n)->setDataValidation(clone $objValidation); 
	}

//Anchos
$hojaHeader->getColumnDimension('A')->setWidth(20);
$hojaHeader->getColumnDimension('B')->setWidth(25);
$hojaHeader->getColumnDimension('C')->setWidth(25);
$hojaHeader->getColumnDimension('D')->setWidth(30);
$hojaHeader->getColumnDimension('E')->setWidth(25);
$hojaHeader->getColumnDimension('F')->setWidth(30);
$hojaHeader->getColumnDimension('G')->setWidth(28);
$hojaHeader->getColumnDimension('H')->setWidth(28);
$hojaHeader->getColumnDimension('I')->setWidth(15);
$hojaHeader->getColumnDimension('J')->setWidth(20);
$hojaHeader->getColumnDimension('K')->setWidth(30);
$hojaHeader->getColumnDimension('L')->setWidth(30);

//$hojaHeader->getStyle('A5:H'.$i)->getAlignment()->setWrapText(true);

//Renombrar hoja de Excel
$hojaHeader->setTitle('Plan');

// *************** FINAL

$spreadsheet->removeSheetByIndex(
    $spreadsheet->getIndex(
        $spreadsheet->getSheetByName('Worksheet')
    )
);

$spreadsheet->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
//Redirigir la salida al navegador del cliente
$hoy = date('dmY');
$nombreArc = 'Plantilla preventivos - '.$hoy.'.xlsx';
//Agregamos algo al encabezado
header('Content-Disposition: attachment;filename='.$nombreArc);
header('Cache-Control: max-age=0');
//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet,'Xlsx');
//save into php output
$writer->save('php://output');
exit(); 

?>