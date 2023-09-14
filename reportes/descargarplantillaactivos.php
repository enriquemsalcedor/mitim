<?php
include('../conexion.php');
global $mysqli;

//date_default_timezone_set('Europe/London');

$data 	 = '';
$nivel  = $_SESSION['nivel'];
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
  
// Set document properties
$spreadsheet->getProperties()->setCreator("Maxia Latam")
->setLastModifiedBy("Maxia Latam")
->setTitle("Plantilla creación de activos")
->setSubject("Plantilla creación de activos")
->setDescription("Plantilla creación de activos")
->setKeywords("Plantilla creación de activos")
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
/* $hojaEmpresas = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaEmpresas->setCellValue('A1', 'Empresas');

//LETRA
$hojaEmpresas->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaEmpresas->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaEmpresas->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "SELECT descripcion FROM empresas ";
$resultempresas = $mysqli->query($q);
$i = 2;

while($empresa = $resultempresas->fetch_assoc()){
	$hojaEmpresas->setCellValue('A'.$i, $empresa['descripcion']);
	$valoreslistas['empresas'][] = $empresa['descripcion'];
	$i++;
}

$hojaEmpresas->setTitle('Empresas');
$hojaEmpresas->getColumnDimension('A')->setWidth(60); */
//$spreadsheet->getSheetByName('Empresas')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

$c = "SELECT nombre FROM clientes WHERE id = ".$idclientes." ";
$resultclientes = $mysqli->query($c); 
if($cliente = $resultclientes->fetch_assoc()){
	$cliente = $cliente['nombre'];
}

$p = "SELECT nombre FROM proyectos WHERE id = ".$idproyectos." ";
$resultproyectos = $mysqli->query($p); 
if($proyecto = $resultproyectos->fetch_assoc()){
	$proyecto = $proyecto['nombre'];
}

// ************************************************ CLIENTES ******************************************
/* $hojaClientes = $spreadsheet->createSheet($sheet);
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
$hojaClientes->getColumnDimension('A')->setWidth(60); */
//$spreadsheet->getSheetByName('Clientes')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ PROYECTOS ******************************************
/* $hojaProyectos = $spreadsheet->createSheet($sheet);
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
$hojaProyectos->getColumnDimension('A')->setWidth(60); */
//$spreadsheet->getSheetByName('Proyectos')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ CASAS MEDICAS ******************************************
$hojaResp = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaResp->setCellValue('A1', 'Responsables');

//LETRA
$hojaResp->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaResp->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaResp->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = " SELECT nombre from usuarios 
	   WHERE nivel = 3
	  ";
	if($idempresas != 0){
		$q .= " AND idempresas IN(".$idempresas.") ";
	}
	/* if($idclientes != 0){
		$q .= " AND idclientes IN(".$idclientes.") ";
	}
	if($idproyectos != 0){
		$q .= " AND idproyectos IN(".$idproyectos.") ";
	} */
	if($idclientes != 0){
		$arr = strpos($idclientes, ',');
		if ($arr !== false) {
			$q  .= " AND idclientes IN (".$idclientes.") ";
		}else{
			$q  .= " AND find_in_set($idclientes,idclientes) ";
		}  
	}
	if($idproyectos != 0){
		$arr = strpos($idproyectos, ',');
		if ($arr !== false) {
			$q  .= " AND idproyectos IN (".$idproyectos.") ";
		}else{
			$q  .= " AND find_in_set($idproyectos,idproyectos) ";
		}  
	}								
$resultresp = $mysqli->query($q);
$i = 2;

while($resp = $resultresp->fetch_assoc()){
	$hojaResp->setCellValue('A'.$i, $resp['nombre']);
	$valoreslistas['responsables'][] = $resp['nombre'];
	$i++;
}

$hojaResp->setTitle('Responsables');
$hojaResp->getColumnDimension('A')->setWidth(60);
$spreadsheet->getSheetByName('Responsables')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ AMBIENTES ******************************************
$hojaAmbientes = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaAmbientes->setCellValue('A1', 'Ubicaciones');

//LETRA
$hojaAmbientes->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaAmbientes->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaAmbientes->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

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
	
$resultambientes = $mysqli->query($q);
$i = 2;

while($fila = $resultambientes->fetch_assoc()){
	$hojaAmbientes->setCellValue('A'.$i, $fila['ambiente']);
	$valoreslistas['ambientes'][] = $fila['ambiente'];
	$i++;
}

$hojaAmbientes->setTitle('Ambientes');
$hojaAmbientes->getColumnDimension('A')->setWidth(60);
$spreadsheet->getSheetByName('Ambientes')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ SUBAMBIENTES ******************************************
$hojaSubambientes = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaSubambientes->setCellValue('A1', 'Áreas');

//LETRA
$hojaSubambientes->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaSubambientes->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaSubambientes->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

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
if($idproyectos != 0){
	$arr = strpos($idproyectos, ',');
	if ($arr !== false) {
		$q  .= " AND sp.idproyectos IN (".$idproyectos.") ";
	}else{
		$q  .= " AND find_in_set($idproyectos,sp.idproyectos) ";
	}  
}
$q .= " GROUP BY s.id ";

$resultsubambientes = $mysqli->query($q);
$i = 2;

while($fila = $resultsubambientes->fetch_assoc()){
	$hojaSubambientes->setCellValue('A'.$i, $fila['subambiente']);
	$valoreslistas['subambientes'][] = $fila['subambiente'];
	$i++;
}

$hojaSubambientes->setTitle('Subambientes');
$hojaSubambientes->getColumnDimension('A')->setWidth(60);
$spreadsheet->getSheetByName('Subambientes')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ MARCAS ******************************************
$hojaMarcas = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaMarcas->setCellValue('A1', 'Marcas');

//LETRA
$hojaMarcas->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaMarcas->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaMarcas->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "	SELECT a.nombre AS marca 
		FROM marcas a 
		";
if($nivel == 7){
	$q  .= " INNER JOIN activos b ON a.id = b.idmarcas ";
	if($idclientes != ''){
		$arr = strpos($idclientes, ',');
		if ($arr !== false) {
			$q  .= " AND b.idclientes IN (".$idclientes.") ";
		}else{
			$q  .= " AND find_in_set($idclientes,b.idclientes) ";
		}  
	}
	if($idproyectos != ''){
		$arr = strpos($idproyectos, ',');
		if ($arr !== false) {
			$q  .= " AND b.idproyectos IN (".$idproyectos.") ";
		}else{
			$q  .= " AND find_in_set($idproyectos,b.idproyectos) ";
		}  
	}
}
$q .= " WHERE 1 = 1 "; 
$q .= " GROUP BY a.id "; 
$resultmarcas = $mysqli->query($q);
$i = 2;

while($fila = $resultmarcas->fetch_assoc()){
	$hojaMarcas->setCellValue('A'.$i, $fila['marca']);
	$valoreslistas['marcas'][] = $fila['marca'];
	$i++;
}

$hojaMarcas->setTitle('Marcas');
$hojaMarcas->getColumnDimension('A')->setWidth(60);
$spreadsheet->getSheetByName('Marcas')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ MODELOS ******************************************
$hojaModelos = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaModelos->setCellValue('A1', 'Modelos');

//LETRA
$hojaModelos->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaModelos->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaModelos->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "	SELECT a.nombre AS modelo 
		FROM modelos a 
		";
if($nivel == 7){
		$q  .= " INNER JOIN marcas b ON a.idmarcas = b.id
					 INNER JOIN activos c ON b.id = c.idmarcas AND a.id = c.idmodelos ";
		if($idclientes != ''){
			$arr = strpos($idclientes, ',');
			if ($arr !== false) {
				$q  .= " AND c.idclientes IN (".$idclientes.") ";
			}else{
				$q  .= " AND find_in_set($idclientes,c.idclientes) ";
			}  
		}
		if($idproyectos != ''){
			$arr = strpos($idproyectos, ',');
			if ($arr !== false) {
				$q  .= " AND c.idproyectos IN (".$idproyectos.") ";
			}else{
				$q  .= " AND find_in_set($idproyectos,c.idproyectos) ";
			}  
		}
	}else{
		$query  .= " WHERE 1 ";
	}
$q .= " WHERE 1 = 1 "; 
$q .= " GROUP BY a.id "; 
$resultmodelos = $mysqli->query($q);
$i = 2;

while($fila = $resultmodelos->fetch_assoc()){
	$hojaModelos->setCellValue('A'.$i, $fila['modelo']);
	$valoreslistas['modelos'][] = $fila['modelo'];
	$i++;
}

$hojaModelos->setTitle('Modelos');
$hojaModelos->getColumnDimension('A')->setWidth(60);
$spreadsheet->getSheetByName('Modelos')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
// ************************************************ TIPOS ******************************************
$hojaTipos = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaTipos->setCellValue('A1', 'Tipos');

//LETRA
$hojaTipos->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaTipos->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaTipos->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$q = "	SELECT DISTINCT t.nombre as tipos
		FROM activostipos t
		";
if($nivel == 7){
	$q  .= " inner join activos a on a.idtipo = t.id ";
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
}
$q .= " WHERE 1 = 1 "; 
$q .= " GROUP BY t.id "; 
$resulttipos = $mysqli->query($q);
$i = 2;

while($fila = $resulttipos->fetch_assoc()){
	$hojaTipos->setCellValue('A'.$i, $fila['tipos']);
	$valoreslistas['tipos'][] = $fila['tipos'];
	$i++;
}

$hojaTipos->setTitle('Tipos');
$hojaTipos->getColumnDimension('A')->setWidth(60);
$spreadsheet->getSheetByName('Tipos')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

// ************************************************ Estados ******************************************
$hojaEstados = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaEstados->setCellValue('A1', 'Estados');

//LETRA
$hojaEstados->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaEstados->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaEstados->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);
$estados = array("Activo", "Inactivo");
$i = 2;
foreach ($estados as $value) {
	$hojaEstados->setCellValue('A'.$i, $value);
	$valoreslistas['estados'][] = $value;
	$i++;
}



$hojaEstados->setTitle('Estados');
$hojaEstados->getColumnDimension('A')->setWidth(60);
$spreadsheet->getSheetByName('Estados')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
// ************************************************ ESTADOS ******************************************
/* $hojaEstados = $spreadsheet->createSheet($sheet);
$sheet++;
$hojaEstados->setCellValue('A1', 'Estados');

//LETRA
$hojaEstados->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaEstados->getStyle('A1')->applyFromArray($style2);
//FONDO
$hojaEstados->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);

$hojaEstados
		->setCellValue('A2', 'Activo')
		->setCellValue('A3', 'Inactivo');

$hojaEstados->setTitle('Estados');
$hojaEstados->getColumnDimension('A')->setWidth(60); */
//$spreadsheet->getSheetByName('Estados')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);


// ************************************************ ENCABEZADO ****************************************** 
$hojaHeader = $spreadsheet->createSheet(1);

$hojaHeader->setCellValue('A1', 'CLIENTE:'); 
$hojaHeader->setCellValue('B1', $cliente);
$hojaHeader->setCellValue('C1', 'PROYECTO:'); 
$hojaHeader->setCellValue('D1', $proyecto);
$hojaHeader->setCellValue('A4', '*ACTIVO');
$hojaHeader->setCellValue('B4', '*N° DE SERIAL 1');
$hojaHeader->setCellValue('C4', 'N° DE SERIAL 2');
$hojaHeader->setCellValue('D4', 'MARCA');
$hojaHeader->setCellValue('E4', 'MODELO');
$hojaHeader->setCellValue('F4', 'UBICACIÓN');
$hojaHeader->setCellValue('G4', 'ÁREA');
$hojaHeader->setCellValue('H4', 'RESPONSABLE / ASIGNADO');
$hojaHeader->setCellValue('I4', 'FECHA TOPE MANTENIMIENTO');
$hojaHeader->setCellValue('J4', 'FECHA INSTALACIÓN');
$hojaHeader->setCellValue('K4', 'VIDA ÚTIL (MESES)');
$hojaHeader->setCellValue('L4', 'INGRESOS QUE GENERA');
$hojaHeader->setCellValue('M4', 'ESTADO');
$hojaHeader->setCellValue('N4', 'TIPO');


//LETRA
$hojaHeader->getStyle('A1')->getFont()->setBold(true)->setSize(11);
$hojaHeader->getStyle('C1')->getFont()->setBold(true)->setSize(11);
$hojaHeader->getStyle('A4:N4')->getFont()->setBold(true)->setSize(11)->setColor($fontColor);
$hojaHeader->getStyle('A4:N4')->applyFromArray($style2);
//FONDO
$hojaHeader->getStyle('A4:N4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($azulOscuro);
// ALTURA
$hojaHeader->getRowDimension('1')->setRowHeight(20);

// DATOS PARA LAS LISTAS
// EMPRESAS
/* $listaempresas = '';
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
$listaempresas = substr($listaempresas,0,-1); */

// CLIENTES
/* $listaclientes = '';
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
$listaclientes = substr($listaclientes,0,-1); */

// PROYECTOS
/* $listaproyectos = '';
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
$listaproyectos = substr($listaproyectos,0,-1); */

// CASAMEDICA
$listaresp = '';
$valoreslistas['responsables'] = array();

$arr = $hojaResp->rangeToArray(
        'A5:A'.($resultresp->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );
	
foreach($arr as $ar){
	$listaresp .= $ar[0].',';
}
$listaresp = substr($listaresp,0,-1);

// AMBIENTES
$listaambiente = '';
//debug($resultambientes->num_rows);
$valoreslistas['ambientes'] = array();

$arr = $hojaAmbientes->rangeToArray(
        'A5:A'.($resultambientes->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );
	
foreach($arr as $ar){
	$listaambiente .= $ar[0].',';
}
$listaambiente = substr($listaambiente,0,-1);


// SUBAMBIENTES
$listasubambiente = '';
//debug($resultambientes->num_rows);
$valoreslistas['ambientes'] = array();

$arr = $hojaSubambientes->rangeToArray(
        'A5:A'.($resultsubambientes->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );
	
foreach($arr as $ar){
	$listasubambiente .= $ar[0].',';
}
$listasubambiente = substr($listasubambiente,0,-1);

// MARCAS
$listamarcas = '';
//debug($resultambientes->num_rows);
$valoreslistas['marcas'] = array();

$arr = $hojaMarcas->rangeToArray(
        'A5:A'.($resultmarcas->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );
	
foreach($arr as $ar){
	$listamarcas .= $ar[0].',';
}
$listamarcas = substr($listamarcas,0,-1);

// Estados
$listaestados = '';
//debug($resultambientes->num_rows);
$valoreslistas['estados'] = array();

$arr = $hojaEstados->rangeToArray(
        'A5:A'.($resultestados->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );
	
foreach($arr as $ar){
	$listaestados .= $ar[0].',';
}
$listaestados = substr($listaestados,0,-1);

// Tipos
$listaTipos = '';
//debug($resultambientes->num_rows);
$valoreslistas['tipos'] = array();

$arr = $hojaTipos->rangeToArray(
        'A5:A'.($resulttipos->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );
	
foreach($arr as $ar){
	$listaTipos .= $ar[0].',';
}
$listaTipos = substr($listaTipos,0,-1);

// MODELOS
$listamodelos = '';
//debug($resultambientes->num_rows);
$valoreslistas['modelos'] = array();

$arr = $hojaModelos->rangeToArray(
        'A5:A'.($resultmodelos->num_rows+1),     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );
	
foreach($arr as $ar){
	$listamodelos .= $ar[0].',';
}
$listamodelos = substr($listamodelos,0,-1);
// ESTADO
// $arr = $hojaEstados->rangeToArray('A2:A10',NULL,TRUE,FALSE);
// $listaestado = '';
// foreach($arr as $pos) {
	// $listaestado .= $pos[0].',';
// }
// $listaestado = substr($listaestado,0,-1);

// LISTAS
	/*
	// ******************************************************************** LISTA DE EMPRESAS
	$objValidation = $hojaHeader->getCell('O2')->getDataValidation();
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
	$objValidation->setFormula1('Empresas!$A$2:$A$'.($resultempresas->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('O'.$n)->setDataValidation(clone $objValidation); 
	}
	*/
	// ******************************************************************** LISTA DE CLIENTES
/* 	$objValidation = $hojaHeader->getCell('A2')->getDataValidation();
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
	$objValidation->setFormula1('Clientes!$A$2:$A$'.($resultclientes->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('A'.$n)->setDataValidation(clone $objValidation); 
	} */
	
	// ******************************************************************** LISTA DE PROYECTOS
/* 	$objValidation = $hojaHeader->getCell('B2')->getDataValidation();
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
		$hojaHeader->getCell('B'.$n)->setDataValidation(clone $objValidation); 
		
	} */
	
	// ******************************************************************** LISTA DE CASAMEDICA
	$objValidation = $hojaHeader->getCell('H5')->getDataValidation();
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
	$objValidation->setFormula1('Responsables!$A$2:$A$'.($resultresp->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('H'.$n)->setDataValidation(clone $objValidation); 
	}

 // ******************************************************************** LISTA DE MARCAS
	$objValidation = $hojaHeader->getCell('D5')->getDataValidation();
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
	$objValidation->setFormula1('Marcas!$A$2:$A$'.($resultmarcas->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('D'.$n)->setDataValidation(clone $objValidation); 
	}

	// ******************************************************************** LISTA DE tipos
	$objValidation = $hojaHeader->getCell('N5')->getDataValidation();
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
	$objValidation->setFormula1('Tipos!$A$2:$A$'.($resultmarcas->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('N'.$n)->setDataValidation(clone $objValidation); 
	}

	// ******************************************************************** LISTA DE estados
	$objValidation = $hojaHeader->getCell('M5')->getDataValidation();
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
	$objValidation->setFormula1('Estados!$A$2:$A$'.($resultmarcas->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('M'.$n)->setDataValidation(clone $objValidation); 
	}

	// ******************************************************************** LISTA DE MODELOS
	$objValidation = $hojaHeader->getCell('E5')->getDataValidation();
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
	$objValidation->setFormula1('Modelos!$A$2:$A$'.($resultmodelos->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('E'.$n)->setDataValidation(clone $objValidation); 
	}
	
 // ******************************************************************** LISTA DE AMBIENTES
	$objValidation = $hojaHeader->getCell('F5')->getDataValidation();
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
	$objValidation->setFormula1('Ambientes!$A$2:$A$'.($resultambientes->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('F'.$n)->setDataValidation(clone $objValidation); 
	} 
 // ******************************************************************** LISTA DE SUBAMBIENTES
	$objValidation = $hojaHeader->getCell('G5')->getDataValidation();
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
	$objValidation->setFormula1('Subambientes!$A$2:$A$'.($resultsubambientes->num_rows+1));
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('G'.$n)->setDataValidation(clone $objValidation); 
	}
	
 
	/*
	// ************************************** LISTA DE ESTADO
	$objValidation = $hojaHeader->getCell('M2')->getDataValidation();
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
	$objValidation->setFormula1('"'.$listaestado.'"');	
	for($n=3;$n<100;$n++){
		$hojaHeader->getCell('M'.$n)->setDataValidation(clone $objValidation); 
	}
	*/
	
//Anchos
$hojaHeader->getColumnDimension('A')->setWidth(30);
$hojaHeader->getColumnDimension('B')->setWidth(45);
$hojaHeader->getColumnDimension('C')->setWidth(30);
$hojaHeader->getColumnDimension('D')->setWidth(30);
$hojaHeader->getColumnDimension('E')->setWidth(30);
$hojaHeader->getColumnDimension('F')->setWidth(30);
$hojaHeader->getColumnDimension('G')->setWidth(30);
$hojaHeader->getColumnDimension('H')->setWidth(30); 
$hojaHeader->getColumnDimension('I')->setWidth(30); 
$hojaHeader->getColumnDimension('J')->setWidth(30); 
$hojaHeader->getColumnDimension('K')->setWidth(30); 
$hojaHeader->getColumnDimension('L')->setWidth(30); 
$hojaHeader->getColumnDimension('M')->setWidth(30); 
$hojaHeader->getColumnDimension('N')->setWidth(30); 

//$hojaHeader->getStyle('A5:H'.$i)->getAlignment()->setWrapText(true);

//Renombrar hoja de Excel
$hojaHeader->setTitle('Activos');

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
$nombreArc = 'Plantilla activos - '.$hoy.'.xlsx';
//Agregamos algo al encabezado
header('Content-Disposition: attachment;filename='.$nombreArc);
header('Cache-Control: max-age=0');
//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet,'Xlsx');
//save into php output
$writer->save('php://output');
exit();

?>