<?php
include("../conexion.php");
global $mysqli;

function buscarUsuario($nombre){
	global $mysqli;
	
	$query	= " SELECT correo FROM usuarios WHERE nombre = '".$nombre."' ";
	$result = $mysqli->query($query); 
	$row 	= $result->fetch_assoc();
	$usuario  	= $row['correo'];
	return $usuario;
}
	
function getIdCategoria($nombre,$idproyecto){
	global $mysqli;
	
	$q = "SELECT id FROM categorias WHERE nombre = '$nombre' AND idproyecto = '$idproyecto' LIMIT 1";
	//debug($q);
	$r = $mysqli->query($q);
	$val = $r->fetch_assoc();
	$valor = $val['id'];
	return $valor;
}
	
//Call the autoload
	require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';

if(isset($_FILES)) {
	$nombre	 	= $_FILES['archivo']['name'];
	$ArrArchivo = explode(".", $nombre);
	$extension 	= strtolower(end($ArrArchivo));
	$randName 	= md5(rand() * time());
	$path 		= '../importpostventas/';

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
	$importadasExitosas = 0;
	$importadasError = 0;
	$causasError = '<ul>';	
	
	//Se comienza en la fila 2 a procesar el contenido, la fila 1 debe ser el titulo
	for ($row = 2; $row <= $highestRow; $row++){
		// Si ninguna celda esta en blanco
		if ($sheet->getCell('A' . $row)->getValue() != '' && $sheet->getCell('B' . $row)->getValue() != ''){
			//$repetida = checkActividadRepetida($sheet->getCell('A' . $row)->getValue(),$sheet->getCell('D' . $row)->getValue());
			$repetida = 0;
			if($repetida == 0){
				$rowData[] = $sheet->rangeToArray('A' . $row . ':' . 'I' . $row, NULL, TRUE, FALSE);
				$importadasExitosas++;
			} else {
				$causasError .= '<li>Error en la fila '.$row.', la actividad ya existe</li>';
				$importadasError++;
			}					
		} else {
			$importadasError++;
			if($sheet->getCell('A' . $row)->getValue() == ''){
				$causasError .= '<li>Error en la fila '.$row.', la columna <b>Empresa</b> está vacía</li>';
			}
			if($sheet->getCell('B' . $row)->getValue() == ''){
				$causasError .= '<li>Error en la fila '.$row.', la columna <b>Cliente</b> está vacía</li>';
			}
			if($sheet->getCell('C' . $row)->getValue() == ''){
				$causasError .= '<li>Error en la fila '.$row.', la columna <b>Proyecto</b> está vacía</li>';
			}
			if($sheet->getCell('D' . $row)->getValue() == ''){
				$causasError .= '<li>Error en la fila '.$row.', la columna <b>Categoría</b> está vacía</li>';
			}
			if($sheet->getCell('E' . $row)->getValue() == ''){
				$causasError .= '<li>Error en la fila '.$row.', la columna <b>Ambiente</b> está vacía</li>';
			}
			if($sheet->getCell('F' . $row)->getValue() == ''){
				$causasError .= '<li>Error en la fila '.$row.', la columna <b>Fecha de MP</b> está vacía</li>';
			}
			if($sheet->getCell('G' . $row)->getValue() == ''){
				$causasError .= '<li>Error en la fila '.$row.', la columna <b>Horario</b> está vacía</li>';
			}
			if($sheet->getCell('H' . $row)->getValue() == ''){
				$causasError .= '<li>Error en la fila '.$row.', la columna <b>Prioridad</b> está vacía</li>';
			}
			if($sheet->getCell('I' . $row)->getValue() == ''){
				$causasError .= '<li>Error en la fila '.$row.', la columna <b>Responsables</b> está vacía</li>';
			} /*
			if($sheet->getCell('J' . $row)->getValue() == ''){
				$causasError .= '<li>Error en la fila '.$row.', la columna <b>Responsables</b> está vacía</li>';
			}*/
		}
	}		
	$causasError .= '</ul>';
	
	$acciones = '';
	$listaImportadas = '<ul>';
	
	//BD
	for ($j = 0; $j < count($rowData); $j++){
		$ArrItem 	= $rowData[$j][0];
		/* 
		$fecha		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[1], "yyyy-mm-dd");
		$hora 		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[2], "h:mm:ss");
		*/			
		//$equipo  		= getId('equipo', 'activos', $ArrItem[4], 'codequipo');
		
		$empresa		= trim(str_replace(' ', '', $ArrItem[0]));
		$cliente		= trim(str_replace(' ', '', $ArrItem[1]));
		$proyecto		= trim(str_replace(' ', '', $ArrItem[2]));
		$categoria		= trim(str_replace(' ', '', $ArrItem[3]));
		$idactivos		= "";
		$sitio			= trim(str_replace(' ', '', $ArrItem[4]));
		$titulo			= 'Visita de Postventa '.$sitio;
		$fechamp		= \PhpOffice\PhpSpreadsheet\Style\NumberFormat::toFormattedString($ArrItem[5], "yyyy-mm-dd");
		$horario		= trim(str_replace(' ', '', $ArrItem[6]));
		$prioridad		= trim(str_replace(' ', '', $ArrItem[7]));
		$responsable	= trim(str_replace(' ', '', $ArrItem[8]));
		
		//IDS
		$idempresas  	= getId('id', 'empresas', $empresa, 'descripcion');
		$idclientes  	= getId('id', 'clientes', $cliente, 'nombre');
		$idproyectos  	= getId('id', 'proyectos', $proyecto, 'nombre');
		$idcategorias  	= getIdCategoria($categoria, $idproyectos);
		$idambientes	= getId('id', 'ambientes', $sitio, 'nombre');
		$idprioridades 	= getId('id', 'sla', $prioridad, 'prioridad');
		$usuresponsable	= buscarUsuario($responsable);
		$iddepartamento = getId('iddepartamentos', 'usuarios', $usuresponsable, 'correo');
		$listdep 		= explode(',',$iddepartamento);
		$iddepartamentos= $listdep[0];
		$departamento 	= getId('id', 'departamentos', $iddepartamentos, 'nombre');
		if($usuresponsable != ''){
			$idestados = 13;
		}else{
			$idestados = 12;
		}
		
		//debugL($idactivos);
		$query  = " INSERT INTO postventas (id, titulo, idempresas, idclientes, idproyectos, idcategorias, iddepartamentos, idambientes, idactivos, idprioridades, idestados, asignadoa, fechacreacion, horario)
					VALUES (null, '$titulo', '$idempresas', '$idclientes', '$idproyectos', '$idcategorias', '$iddepartamentos', '$idambientes', '$idactivos', '$idprioridades', '$idestados', '$usuresponsable', '$fechamp', '$horario') ";
		//debug($query);
		$importadasExito++;
		$result = $mysqli->query($query);
		//debug($query);
		$id = $mysqli->insert_id;/*
		if($equipo == ''){
			$causasError .= '<li>Error: El preventivo '.$id.' no ha sido relacionado con el equipo, ya que el número de serie no existe</li>';
			$importadasError++;
		}*/
		if($idambientes == ''){
			$causasError .= '<li>Error: La visita '.$id.' no ha sido relacionado con el ambiente, ya que el nombre del ambiente no existe</li>';
			$importadasError++;
		}
		$listaImportadas .= '<li>Id #'.$id.' Empresa: '.$empresa.' Cliente: '.$cliente.' Proyecto: '.$proyecto.' Departamento: '.$departamento.'.</li>';
	}
	
	$listaImportadas .= '</ul>';
	
	if($result == true){
		$resultado  = $importadasExito.' filas importadas exitosamente. <br/>';
		$resultado .= $importadasError. ' filas con error. <br/>';
		$resultado .= $causasError;
		
		echo $resultado;
		
		// bitacora
		$acciones .= 'Fue importado el archivo '.$nombre.' para la creación de incidentes.<br/><br/>';
		$acciones .= '<b>Resultado:</b><br/>';
		$acciones .= $resultado;
		$acciones .= '<b>Actividades importadas:</b><br/>';
		$acciones .= $listaImportadas;
		
		bitacora($_SESSION['usuario'],'Plan de Visitas',$acciones,0,'');
	}else{
		$resultado = $importadasError. ' filas con error. <br/>';
		$resultado .= $causasError;
		echo $resultado;
	}

}