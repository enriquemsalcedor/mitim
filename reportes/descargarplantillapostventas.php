<?php
include('../conexion.php');
global $mysqli;

//date_default_timezone_set('Europe/London');

$data 	 = '';
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

// Set document properties
$spreadsheet->getProperties()->setCreator("Maxia Latam")
->setLastModifiedBy("Maxia Latam")
->setTitle("Plantilla creación de postventas")
->setSubject("Plantilla creación de postventas")
->setDescription("Plantilla creación de postventas")
->setKeywords("Plantilla creación de postventas")
->setCategory("Plantillas");

$fontColor = new \PhpOffice\PhpSpreadsheet\Style\Color();
	$fontColor->setRGB('ffffff');
	$style2 = array(
			'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
	); 

$azulOscuro = '1F3D7B';
$valoreslistas = array();
$sheet = 2;

// ************************************************ EMPRESAS ******************************************
$hojaEmpresas = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaEmpresas->setCellValue('A1', 'Empresas');

//LETRA
$hojaEmpresas->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaEmpresas->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaEmpresas->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "SELECT descripcion FROM empresas  WHERE id = 1 ";
$resultempresas = $mysqli->query($q);
$i = 2;

while($empresa = $resultempresas->fetch_assoc()){
	$hojaEmpresas->setCellValue('A'.$i, $empresa['descripcion']);
	$valoreslistas['empresas'][] = $empresa['descripcion'];
	$i++;
}

$hojaEmpresas->setTitle('Empresas');
$hojaEmpresas->getColumnDimension('A')->setWidth(60);
//$spreadsheet->getSheetByName('Empresas')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ CLIENTES ******************************************
$hojaClientes = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaClientes->setCellValue('A1', 'Clientes');

//LETRA
$hojaClientes->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaClientes->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaClientes->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

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

$q = "SELECT nombre FROM categorias WHERE id IN (138,139,140,141,142,143,144,145,146,147)";
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

// ************************************************ SITIOS ******************************************
$hojaSitios = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaSitios->setCellValue('A1', 'Sitios');

//LETRA
$hojaSitios->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaSitios->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaSitios->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "SELECT nombre AS ambiente FROM ambientes ";
$resultsitios = $mysqli->query($q);
$i = 2;

while($fila = $resultsitios->fetch_assoc()){
	$hojaSitios->setCellValue('A'.$i, $fila['ambiente']);
	$valoreslistas['ambientes'][] = $fila['ambiente'];
	$i++;
}

$hojaSitios->setTitle('Ubicaciones');
$hojaSitios->getColumnDimension('A')->setWidth(60);
//$spreadsheet->getSheetByName('Sitios')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ SERIES ******************************************
/*$hojaSeries = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaSeries->setCellValue('A1', 'Series');*/

//LETRA
/*$hojaSeries->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaSeries->getStyle('A1')->applyFromArray($style2); */
//FONDO
/*$hojaSeries->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "SELECT DISTINCT(codequipo) as nombre FROM activos ";
$resultseries = $mysqli->query($q);
$i = 2;

while($fila = $resultseries->fetch_assoc()){
	$hojaSeries->setCellValue('A'.$i, $fila['nombre']);
	$valoreslistas['series'][] = $fila['nombre'];
	$i++;
}

$hojaSeries->setTitle('Series');
$hojaSeries->getColumnDimension('A')->setWidth(60); */
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

$q = "SELECT nombre FROM usuarios WHERE nivel IN(2,3,6)";
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

// ************************************************ ENCABEZADO ****************************************** 
$hojaHeader = $spreadsheet->createSheet(1);

$hojaHeader->setCellValue('A1', 'Empresa');
$hojaHeader->setCellValue('B1', 'Clientes');
$hojaHeader->setCellValue('C1', 'Proyectos');
$hojaHeader->setCellValue('D1', 'Categorias');
//$hojaHeader->setCellValue('E1', 'Nro. de serie');
$hojaHeader->setCellValue('E1', 'Ubicación');
$hojaHeader->setCellValue('F1', 'Fecha de MP (yyyy-mm-dd)');
$hojaHeader->setCellValue('G1', 'Horario');
$hojaHeader->setCellValue('H1', 'Prioridad');
$hojaHeader->setCellValue('I1', 'Responsables');

//LETRA
$hojaHeader->getStyle('A1:I1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaHeader->getStyle('A1:I1')->applyFromArray($style2);
//FONDO
$hojaHeader->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);
// ALTURA
$hojaHeader->getRowDimension('1')->setRowHeight(20);

// DATOS PARA LAS LISTAS
// EMPRESAS
$listaempresas = '';
$valoreslistas['empresas'] = array();
$arr = $hojaEmpresas->rangeToArray(
        'A2:A'.($resultempresas->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );	
foreach($arr as $ar){
	$listaempresas .= $ar[0].',';
}
$listaempresas = substr($listaempresas,0,-1);

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

// STIOS
$listasitios = '';
$valoreslistas['sitios'] = array();
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

// RESPONSABLES
$listaresp = implode ( ',' , $valoreslistas['responsables'] );
$valoreslistas['responsables'] = array();

// EQUIPOS 
/*$listaseries = implode ( ',' , $valoreslistas['series'] );
$valoreslistas['series'] = array(); */

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
	$objValidation->setFormula1('Clientes!$A$2:$A$'.($resultclientes->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('B'.$n)->setDataValidation(clone $objValidation); 
	}
	*/

	// ******************************************************************** LISTA DE PROYECTOS
	/*
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
	$objValidation->setFormula1('Proyectos!$A$2:$A$'.($resultproyectos->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('C'.$n)->setDataValidation(clone $objValidation); 
	}
	*/
	
	// ******************************************************************** LISTA DE CATEGORIAS
	/*
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
	$objValidation->setFormula1('Categorias!$A$2:$A$'.($resultcategorias->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('D'.$n)->setDataValidation(clone $objValidation); 
	}
	*/
		
	// **************************************************************** LISTA DE SERIES
	/*
	$objValidation = $hojaHeader->getCell('E2')->getDataValidation();
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
	$objValidation->setFormula1('Series!$A$2:$A$'.($resultseries->num_rows+1));	
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('E'.$n)->setDataValidation(clone $objValidation); 
	}
	*/
	
	// **************************************************************** LISTA DE SITIOS
	/*
	$objValidation = $hojaHeader->getCell('F2')->getDataValidation();
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
	$objValidation->setFormula1('Sitios!$A$2:$A$'.($resultsitios->num_rows+1));	
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('F'.$n)->setDataValidation(clone $objValidation); 
	}
	*/
	
	// ******************************************************************** LISTA DE PRIORIDADES
	/*
	$objValidation = $hojaHeader->getCell('I2')->getDataValidation();
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
	$objValidation->setFormula1('Prioridades!$A$2:$A$'.($resultprioridades->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('I'.$n)->setDataValidation(clone $objValidation); 
	}
	*/

	// **************************************************************** LISTA DE RESPONSABLES
	/*
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
	$objValidation->setFormula1('Responsables!$A$2:$A$'.($resultresp->num_rows+1));	
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('J'.$n)->setDataValidation(clone $objValidation); 
	}
	*/


//Anchos
$hojaHeader->getColumnDimension('A')->setWidth(20);
$hojaHeader->getColumnDimension('B')->setWidth(25);
$hojaHeader->getColumnDimension('C')->setWidth(25);
$hojaHeader->getColumnDimension('D')->setWidth(30);
//$hojaHeader->getColumnDimension('E')->setWidth(25);
$hojaHeader->getColumnDimension('E')->setWidth(30);
$hojaHeader->getColumnDimension('F')->setWidth(30);
$hojaHeader->getColumnDimension('G')->setWidth(20);
$hojaHeader->getColumnDimension('H')->setWidth(15);
$hojaHeader->getColumnDimension('I')->setWidth(30);

//$hojaHeader->getStyle('A5:H'.$i)->getAlignment()->setWrapText(true);

//Renombrar hoja de Excel
//Renombrar hoja de Excel
$hojaHeader->setTitle('Postventas');

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
$nombreArc = 'Plantilla postventas - '.$hoy.'.xlsx';
//Agregamos algo al encabezado
header('Content-Disposition: attachment;filename='.$nombreArc);
header('Cache-Control: max-age=0');
//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet,'Xlsx');
//save into php output
$writer->save('php://output');
exit();

?>