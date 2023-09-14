<?php  
function checkActivosRepetida($serie,$equipo,$marca='',$modelo='',$empresa='',$cliente='',$proyecto=''){		
	global $mysqli; 
	
	//$idempresas  	= getId('id', 'empresas', $empresa, 'descripcion');
	$idempresas  	= 1;
	$idclientes  	= getId('id', 'clientes', $cliente, 'nombre');
	$idproyectos  	= getId('id', 'proyectos', $proyecto, 'nombre');
	$idmarcas	  	= getId('id', 'marcas', $marca, 'nombre');
	$idmodelos   	= getId('id', 'modelos', $modelo, 'nombre');
			
	$q = " SELECT id FROM activos WHERE serie = '$serie' AND nombre = '$equipo' AND idmarcas = '$idmarcas' AND idmodelos = '$idmodelos'
		   AND idempresas = '$idempresas' AND idclientes = '$idclientes' AND idproyectos = '$idproyectos' LIMIT 1 ";
	$r = $mysqli->query($q);
	
	$num = $r->num_rows;
	
	return $num;
} 

include("../conexion.php");
global $mysqli;
require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';

if(isset($_FILES)) {
	$nombrefile	 	= $_FILES['archivo']['name'];
	$ArrArchivo = explode(".", $nombrefile);
	$extension 	= strtolower(end($ArrArchivo));
	$randName 	= md5(rand() * time());
	$path 		= '../activos/';

	$nombre_tmp = $_FILES["archivo"]["tmp_name"];
	$nombreA 	= $randName.'-'.basename($_FILES["archivo"]["name"]);
	$rutaA 		= $path."".$nombreA;
	move_uploaded_file($nombre_tmp, $rutaA);		
	
	//DEFINIR LA VERSION DE EXCEL
	if ($extension == 'xls'){
		//debug('xls 1');
		$objReader = PHPExcel_IOFactory::createReader('Excel5');				
	}elseif ($extension == 'xlsx'){
		//debug('xlsx 1');
		//$objReader = new PHPExcel_Reader_Excel2007();
		$objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
		$objReader->setReadDataOnly(true);					
	}
	//CODIGO DE LECTURA Y ESCRITURA
	$objPHPExcel = $objReader->load($rutaA);			
	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow();
	$highestColumn = $sheet->getHighestColumn();
	
	$importadasExito = 0;
	$importadasAct = 0;
	$importadasExitosas = 0;
	$importadasError = 0;
	$causasError = '<ul>';	
// 	print_r($sheet);
	//Se comienza en la fila 5 a procesar el contenido, la fila 1 debe ser el titulo			
	for ($row = 5; $row < $highestRow+1; $row++){
		// Si ninguna celda esta en blanco
		if (trim($sheet->getCell('A' . $row)->getValue()) != '' && trim($sheet->getCell('B' . $row)->getValue()) != '' ){
			
			$repetida = checkActivosRepetida($sheet->getCell('A' . $row)->getValue(),$sheet->getCell('B' . $row)->getValue(),1,$sheet->getCell('B1')->getValue(),$sheet->getCell('D1')->getValue());
			/*
			$titulo = $sheet->getCell('C' . $row)->getValue();
			if($titulo != ''){
				$btitulo  = "SELECT titulo FROM plan where titulo = '$titulo' ";
				$resultbp = $mysqli->query($btitulo);
				$nbrows = $resultbp->num_rows;
				if($nbrows > 0){
					$causasError .= '<li>Error en la fila '.$row.', la actividad ya existe</li>';
					$importadasError++;
				}
			}
			*/
			$repetida = 0;
			if($repetida == 0){
				$rowData[] = $sheet->rangeToArray('A' . $row . ':' . 'N' . $row, NULL, TRUE, FALSE);
				$importadasExitosas++;				    
			} else {
				$causasError .= '<li>Error en la fila '.$row.', el activo ya existe</li>';
				$importadasError++;
			}
		} else {	
			if (trim($sheet->getCell('C' . $row)->getValue()) == ''  ){
				//FILA VACIA
			}else{ 
				$importadasError++;
				if(trim($sheet->getCell('A' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', el campo <b>ACTIVO</b> está vacío</li>';
				}
				if(trim($sheet->getCell('B' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', el campo <b>N° DE SERIE</b> está vacío</li>';
				} 
				if(trim($sheet->getCell('D' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', el campo <b>MARCA</b> está vacío</li>';
				}
				if(trim($sheet->getCell('E' . $row)->getValue()) == ''){									  
					$causasError .= '<li>Error en la fila '.$row.', el campo <b>MODELO</b> está vacío</li>';
				}
				if(trim($sheet->getCell('F' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', el campo <b>AMBIENTE</b> está vacío</li>';
				}
				if(trim($sheet->getCell('G' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', el campo <b>SUBAMBIENTE</b> está vacío</li>';
				}
				if(trim($sheet->getCell('H' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', el campo <b>RESPONSABLE / ASIGNADO</b> está vacío</li>';
				} 
				if(trim($sheet->getCell('I' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', el campo <b>FECHA TOPE MANTENIMIENTO</b> está vacío</li>';
				} 
				if(trim($sheet->getCell('J' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', el campo <b>FECHA INSTALACIÓN</b> está vacío</li>';
				} 
				if(trim($sheet->getCell('K' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', el campo <b>VIDA ÚTIL (MESES)</b> está vacío</li>';
				} 
				if(trim($sheet->getCell('L' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', el campo <b>INGRESOS QUE GENERA</b> está vacío</li>';
				} 
				if(trim($sheet->getCell('M' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', el campo <b>ESTADO</b> está vacío</li>';
				} 
				if(trim($sheet->getCell('N' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', el campo <b>TIPO</b> está vacío</li>';
				} 
			//}
			}
		}		
		$causasError .= '</ul>';
		
		$acciones = '';
		$listaImportadas = '<ul>';
	}
	//debug(count($rowData));
	//BD			
	for ($j = 0; $j < count($rowData); $j++){
		$ArrItem 	= $rowData[$j][0];
		/* 
		$fecha		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[1], "yyyy-mm-dd");
		$hora 		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[2], "h:mm:ss");
		*/	
		//$formatfechatopemant = PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[10], "yyyy-mm-dd");
		//$formatfechainst     = PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[11], "yyyy-mm-dd");
		
		//$formatfechatopemant = $ArrItem[10];
		//$formatfechainst     = $ArrItem[11];
		 
		//$empresas  	   = 1;
		$clientes      		= $sheet->getCell('B1')->getValue();
		$proyectos     		= $sheet->getCell('D1')->getValue();
		$nombre        		= trim(str_replace(' ', '', $ArrItem[0]));
		$serie 		   		= trim(str_replace(' ', '', $ArrItem[1]));
		$activo        		= trim(str_replace(' ', '', $ArrItem[2]));
		$marca         		= trim(str_replace(' ', '', $ArrItem[3]));
		$modelo        		= trim(str_replace(' ', '', $ArrItem[4]));
		$ambiente      		= trim(str_replace(' ', '', $ArrItem[5]));
		$subambiente   		= trim(str_replace(' ', '', $ArrItem[6]));
		$responsable   		= trim(str_replace(' ', '', $ArrItem[7]));	
		$fechatope			= \PhpOffice\PhpSpreadsheet\Style\NumberFormat::toFormattedString($ArrItem[8], "yyyy-mm-dd");
		$fechainstalacion   = \PhpOffice\PhpSpreadsheet\Style\NumberFormat::toFormattedString($ArrItem[9], "yyyy-mm-dd");
		$vidautil   		= trim(str_replace(' ', '', $ArrItem[10]));	
		$ingresos   		= trim(str_replace(' ', '', $ArrItem[11]));					
		$estado   			= trim(str_replace(' ', '', $ArrItem[12]));	
		$tipo   			= trim(str_replace(' ', '', $ArrItem[13]));	
		
		/* $modalidad     = trim(str_replace(' ', '', $ArrItem[7]));
		$fase          = trim(str_replace(' ', '', $ArrItem[9]));
		$fechatopemant = $formatfechatopemant;
		$fechainst     = $formatfechainst; 
		$estado        = trim(str_replace(' ', '', $ArrItem[12]));
		$comentarios   = trim(str_replace(' ', '', $ArrItem[13])); */
		//IDS 
		$idambientes 		= getId('id', 'ambientes', $ambiente, 'nombre');
		$idsubambientes 	= getId('id', 'subambientes', $subambiente, 'nombre');
		//$idempresas    = getId('id', 'empresas', $empresas, 'descripcion');
		$idclientes    		= getId('id', 'clientes', $clientes, 'nombre');
		$idproyectos   		= getId('id', 'proyectos', $proyectos, 'nombre');
		$idmarcas 	   		= getId('id', 'marcas', $marca, 'nombre');
		$idmodelos     		= getId('id', 'modelos', $modelo, 'nombre');
		$idresponsables		= getId('id', 'usuarios', $responsable, 'nombre');
		$idtipo				= getId('id', 'activostipos', $tipo, 'nombre');
		
		$queryAc = " SELECT id FROM activos WHERE serie = '".$serie."' ";
		$resultAc = $mysqli->query($queryAc);
		if($resultAc->num_rows > 0){
			$row = $resultAc->fetch_assoc();				
			$id = $row['id'];
			
			$query = "UPDATE activos SET ";
			//if($idempresas != '')	{	$query .= ", idempresas = '$idempresas' ";	}
			if($idclientes != '')			{	$query .= ", idclientes = '$idclientes' ";	}
			if($idproyectos != '')			{	$query .= ", idproyectos = '$idproyectos' ";	}
			if($idambientes != '')			{	$query .= ", idambientes = '$idambientes' ";	}
			if($equipo != '')				{	$query .= ", nombre = '$nombre' ";	}
			if($marca != '')				{	$query .= ", idmarcas = '$idmarcas' ";	}
			if($modelo != '')				{	$query .= ", idmodelos = '$idmodelos' ";	}
			if($activo != '')				{	$query .= ", activo = '$activo' ";	} 
			if($area != '')					{	$query .= ", idsubambientes = '$idsubambientes' ";	}
			if($idresponsables != '') 		{	$query .= ", idresponsables = '$idresponsables' ";	}
			if($fechatope != '') 			{	$query .= ", fechatopemant = '$fechatope' ";	}
			if($fechainstalacion != '') 	{	$query .= ", fechainst = '$fechainstalacion' ";	}
			if($vidautil != '') 			{	$query .= ", vidautil = '$vidautil' ";	}
			if($ingresos != '') 			{	$query .= ", ingresos = '$ingresos' ";	}
			if($estado != '') 				{	$query .= ", estado = '$estado' ";	}
			if($tipo != '') 				{	$query .= ", idtipo = '$idtipo' ";	}
		
			//if($modalidad != '')	{	$query .= ", modalidad = '$modalidad' ";	}
			//if($estado != '')		{	$query .= ", estado = '$estado' ";	}
			//if($fase != '')			{	$query .= ", fase = '$fase' ";	}
			//if($comentarios != '')	{	$query .= ", comentarios = '$comentarios' ";	}
			//if($fechatopemant != ''){	$query .= ", fechatopemant = '$fechatopemant' ";	}
			//if($fechainst != '')	{	$query .= ", fechainst = '$fechainst' ";	}
			$query .= " WHERE serie = '$serie' ";
			$query = str_replace('SET ,','SET ',$query);					
			$result = $mysqli->query($query);
			$importadasAct++;					
		}else{
			$query = "INSERT INTO activos (serie,nombre,idmarcas,idmodelos,activo,idambientes,idsubambientes, 
					  idempresas,idclientes,idproyectos,idresponsables, fechatopemant, fechainst, vidautil, ingresos, estado, idtipo) 
					  VALUES ('$serie', '$nombre', '$idmarcas', '$idmodelos', '$activo', '$idambientes', '$idsubambientes',
					  '1','$idclientes','$idproyectos','$idresponsables','$fechatope','$fechainstalacion','$vidautil','$ingresos','$estado','$idtipo') ";
			debug('activos:'.$query);
			$result = $mysqli->query($query);
			$id = $mysqli->insert_id;
			$importadasExito++;
		}				
		
		if($ambiente == ''){
			$causasError .= '<li>Error: El Activo con el número de serie: <b>'.$serie.'</b> no ha sido relacionado con el ambiente, ya que el nombre del ambiente no existe</li>';
			$importadasError++;
		}
		$listaImportadas .= '<li> Cliente: '.$clientes.',Proyecto: '.$proyectos.', Activo: '.$nombre.', N° de Serie: '.$serie.', N° de Activo: '.$activo.', Marca: '.$marca.', Modelo: '.$modelo.',
								  Ambiente: '.$ambiente.',  Subambiente: '.$subambiente.', Responsable / Asignado: '.$responsable.', Fecha tope mantenimiento: '.$fechatope.', 
								  Fecha de instalación: '.$fechainstalacion.', Vida útil: '.$vidautil.', Ingresos que genera: '.$ingresos.', Estado: '.$estado.', Tipo: '.$idtipo.'
							. </li>';
	}			
	$listaImportadas .= '</ul>';

	if($result == true){
		$resultado  = $importadasExito.' filas creadas exitosamente. <br/>';
		$resultado .= $importadasAct.' filas actualizadas exitosamente. <br/>';
		$resultado .= $importadasError. ' filas con error. <br/>';
		$resultado .= $causasError;
		echo $resultado;
		
		// bitacora
		$acciones .= 'Fue importado el archivo '.$nombrefile.' para la creación de los activos.<br/><br/>';
		$acciones .= '<b>Resultado:</b><br/>';
		$acciones .= $resultado;
		$acciones .= '<b>Activos importadas:</b><br/>';
		$acciones .= $listaImportadas;
		
		bitacora($_SESSION['usuario'],'Activos',$acciones,0,'');
	}else{
		$resultado = $importadasError. ' filas con error. <br/>';
		$resultado .= $causasError;
		echo $resultado;
	}
}