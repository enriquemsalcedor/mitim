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

//Call the autoload
require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';

if(isset($_FILES)) {
	$nombre	 	= $_FILES['archivo']['name'];
	$ArrArchivo = explode(".", $nombre);
	$extension 	= strtolower(end($ArrArchivo));
	$randName 	= md5(rand() * time());
	$path 		= '../importpreventivos/';

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
	
	//Se comienza en la fila 5 a procesar el contenido, la fila 1 debe ser el titulo
	for ($row = 5; $row <= $highestRow; $row++){
		// Si ninguna celda esta en blanco
		if (trim($sheet->getCell('A' . $row)->getValue()) != '' && trim($sheet->getCell('B' . $row)->getValue()) != '' 		/*&& 
			 trim($sheet->getCell('C' . $row)->getValue()) != '' */&&  trim($sheet->getCell('D' . $row)->getValue()) != '' && 
			/*  ((trim($sheet->getCell('E' . $row)->getValue()) == '' && trim($sheet->getCell('D' . $row)->getValue()) == 'Infraestructura') || (trim($sheet->getCell('E' . $row)->getValue()) != '' && trim($sheet->getCell('D' . $row)->getValue()) != 'Infraestructura')) 
			&& */ trim($sheet->getCell('F' . $row)->getValue()) != '' && 
			trim($sheet->getCell('G' . $row)->getValue()) != '' && trim($sheet->getCell('H' . $row)->getValue()) != '' && 
			trim($sheet->getCell('I' . $row)->getValue()) != '' && trim($sheet->getCell('J' . $row)->getValue()) != '' &&
			trim($sheet->getCell('K' . $row)->getValue()) != '' && trim($sheet->getCell('L' . $row)->getValue()) != ''
			){
			//$repetida = checkActividadRepetida($sheet->getCell('A' . $row)->getValue(),$sheet->getCell('D' . $row)->getValue());
			$repetida = 0;
			if($repetida == 0){
				$rowData[] = $sheet->rangeToArray('A' . $row . ':' . 'O' . $row, NULL, TRUE, FALSE);
				$importadasExitosas++;
			} else {
				$causasError .= '<li>Error en la fila '.$row.', la actividad ya existe</li>';
				$importadasError++;
			}
		} else {
			if (trim($sheet->getCell('A' . $row)->getValue()) == '' && trim($sheet->getCell('B' . $row)->getValue()) == '' /* && 
				trim($sheet->getCell('C' . $row)->getValue()) == '' */ && trim($sheet->getCell('D' . $row)->getValue()) == '' /* && 
				trim($sheet->getCell('E' . $row)->getValue()) == '' */ && trim($sheet->getCell('F' . $row)->getValue()) == '' &&
				trim($sheet->getCell('G' . $row)->getValue()) == '' && trim($sheet->getCell('H' . $row)->getValue()) == '' && 
				trim($sheet->getCell('I' . $row)->getValue()) == '' && trim($sheet->getCell('J' . $row)->getValue()) == '' &&
				trim($sheet->getCell('K' . $row)->getValue()) == '' && trim($sheet->getCell('L' . $row)->getValue()) == ''
				){
				//FILA VACIA
			}else{
				$importadasError++;
				if(trim($sheet->getCell('A' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', la columna <b>Título</b> está vacía</li>';
				}
				/*
				if(trim($sheet->getCell('B' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', la columna <b>Cliente</b> está vacía</li>';
				}
				if(trim($sheet->getCell('C' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', la columna <b>Proyecto</b> está vacía</li>';
				}
				*/
				if(trim($sheet->getCell('B' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', la columna <b>Categoría</b> está vacía</li>';
				}
				/* if((trim($sheet->getCell('C' . $row)->getValue()) == '' && trim($sheet->getCell('B' . $row)->getValue()) != 'Infraestructura')){
					$causasError .= '<li>Error en la fila '.$row.', la columna <b>Serial 1</b> está vacía</li>';
				}else if((trim($sheet->getCell('C' . $row)->getValue()) != '' && trim($sheet->getCell('B' . $row)->getValue()) == 'Infraestructura')){
				    $causasError .= '<li>Error en la fila '.$row.', los preventivos a infraestructuras no poseen <b>Serial 1</b></li>';
				} */
				if(trim($sheet->getCell('D' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', la columna <b>Ubicaciones</b> está vacía</li>';
				}
				/* if(trim($sheet->getCell('E' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', la columna <b>Área</b> está vacía</li>';
				} */
				if(trim($sheet->getCell('F' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', la columna <b>Fecha de MP</b> está vacía</li>';
				}
				if(trim($sheet->getCell('G' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', la columna <b>Hora de creación</b> está vacía</li>';
				}
				if(trim($sheet->getCell('H' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', la columna <b>Horario</b> está vacía</li>';
				}
				if(trim($sheet->getCell('I' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', la columna <b>Prioridad</b> está vacía</li>';
				} 
				if(trim($sheet->getCell('J' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', la columna <b>Solicitante</b> está vacía</li>';
				} 
				if(trim($sheet->getCell('K' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', la columna <b>Responsables</b> está vacía</li>';
				}
				if(trim($sheet->getCell('L' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', la columna <b>Frecuencia</b> está vacía</li>';
				}
				/*
				if(trim($sheet->getCell('O' . $row)->getValue()) == ''){
					$causasError .= '<li>Error en la fila '.$row.', la columna <b>Costo</b> está vacía</li>';
				}
				*/
			}
		}
	}		
	$causasError .= '</ul>';
	
	$acciones = '';
	$listaImportadas = '<ul>';
	if($rowData != ''){
		debug('ok: ');
    	for ($j = 0; $j < count($rowData); $j++){
    		$ArrItem 	= $rowData[$j][0];
    		/* 
    		$fecha		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[1], "yyyy-mm-dd");
    		$hora 		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[2], "h:mm:ss");
    		*/	
    		
    		$idactivos 		= getId('id', 'activos', $ArrItem[2], 'serie');													  
    		$equipo  		= getId('nombre', 'activos', $ArrItem[2], 'serie');
    		$titulo			= $ArrItem[0];
    		//$cliente		= trim(str_replace(' ', '', $ArrItem[1]));
    		//$proyecto		= trim(str_replace(' ', '', $ArrItem[2]));
			$cliente        = $sheet->getCell('B1')->getValue();
			$proyecto       = $sheet->getCell('D1')->getValue();
    		$categoria		= trim(str_replace(' ', '', $ArrItem[1]));
    		$serie			= trim(str_replace(' ', '', $ArrItem[2]));
    		$sitio			= trim(str_replace(' ', '', $ArrItem[3]));
    		$area			= trim(str_replace(' ', '', $ArrItem[4]));
    		$fechamp		=  \PhpOffice\PhpSpreadsheet\Style\NumberFormat::toFormattedString($ArrItem[5], "yyyy-mm-dd");
    		$horacreacion	=  \PhpOffice\PhpSpreadsheet\Style\NumberFormat::toFormattedString($ArrItem[6], "h:mm:ss");
    		$horario		= trim(str_replace(' ', '', $ArrItem[7]));
    		$prioridad		= trim(str_replace(' ', '', $ArrItem[8]));
    		$solicitante	= trim(str_replace(' ', '', $ArrItem[9]));
    		$responsable	= trim(str_replace(' ', '', $ArrItem[10]));
    		$frecuencia		= strtolower(trim(str_replace(' ', '', $ArrItem[11])));
    		//$costo			= trim(str_replace(' ', '', $ArrItem[12]));
			//$costo			= 0;
    		 
    		//IDS
    		$idempresas  	= 1;
    		$idclientes  	= getId('id', 'clientes', $cliente, 'nombre');
    		$idproyectos  	= getId('id', 'proyectos', $proyecto, 'nombre');
    		$idcategorias  	= getId('id', 'categorias', $categoria, 'nombre');
    		$idsitios 		= getId('id', 'ambientes', $sitio, 'nombre'); 
    		$idarea 		= getId('id', 'subambientes', $area, 'nombre'); 
    		$idprioridades 	= getId('id', 'sla', $prioridad, 'prioridad');
    		$usuresponsable	= buscarUsuario($responsable);
    		$iddepartamento = getId('iddepartamentos', 'usuarios', $usuresponsable, 'correo');
    		$listdep 		= explode(',',$iddepartamento);
    		$iddepartamentos= $listdep[0];
    		$departamento 	= getId('id', 'departamentos', $iddepartamentos, 'nombre');
			//debug('responsable: '.$responsable);
    		if($usuresponsable != ''){
    			$idestados = 13;
    		}else{
    			$idestados = 12;
    		}
    		$ususolicitante	= buscarUsuario($solicitante);
    		if($ususolicitante != ''){
    			$correosol = $ususolicitante;
    		}else{
    			$correosol = $_SESSION['email'];
    		}
			if($iddepartamentos == '') $iddepartamentos = 0;
			
    		if($idarea == '') $idarea = 0;
    		//debugL($idactivos);
    		$query  = " INSERT INTO incidentes (id, titulo, idempresas, idclientes, idproyectos, idcategorias, iddepartamentos, idambientes, idsubambientes, origen, tipo, idactivos, idprioridades, 
    					idestados, creadopor, solicitante, asignadoa, fechacreacion, horacreacion, fechareal, horareal, horario,costo,frecuencia)
    					VALUES (null, '".$titulo."', ".$idempresas.", ".$idclientes.", ".$idproyectos.", ".$idcategorias.", ".$iddepartamentos.", ".$idsitios.", ".$idarea.", 'sistema', 'preventivos', '".$idactivos."', ".$idprioridades.", 
    					".$idestados.", '".$correosol."', '".$correosol."', '".$usuresponsable."', '".$fechamp."', '".$horacreacion."', '".$fechamp."', '".$horacreacion."', '".$horario."', '".$costo."', '".$frecuencia."') ";
    		debugL("INSERTIMPORTAR:".$query,"ERRORIMPORTAR");
			$importadasExito++;
    		$resultImportar = $mysqli->query($query);
    		$id = $mysqli->insert_id;
    		//CREAR REGISTRO EN ESTADOS INCIDENTES
    		$idusuario   = $_SESSION['user_id'];
    		$estadonuevo = 12; 
    		$queryE = " INSERT INTO incidentesestados VALUES(null, ".$id.", ".$estadonuevo.", ".$estadonuevo.", ".$idusuario.", now(), now(), 0) ";
    		$mysqli->query($queryE);
    		
    		//CREAR CARPETA DE ID INCIDENTES
    		$myPath = '../incidentes/';
    		if (!file_exists($myPath))
    			mkdir($myPath, 0777);
    		$myPath = '../incidentes/'.$id.'/';
    		$target_path2 = utf8_decode($myPath);
    		if (!file_exists($target_path2))
    			mkdir($target_path2, 0777);					  
    		if($equipo == '' && $categoria != 'Infraestructura'){
    			$causasError .= '<li>Error: El preventivo '.$id.' no ha sido relacionado con el equipo, ya que el número de serial 1 no existe</li>';
    			$importadasError++;
    		}
    		if($idsitios == ''){
    			$causasError .= '<li>Error: El preventivo '.$id.' no ha sido relacionado con el ambiente, ya que el nombre del ambiente no existe</li>';
    			$importadasError++;
    		}
    		$listaImportadas .= '<li>Id #'.$id.' Empresa: '.$empresa.' Cliente: '.$cliente.' Proyecto: '.$proyecto.' Departamento: '.$departamento.'.</li>';
    	}
	}
	//BD
	
	$listaImportadas .= '</ul>';
	
	if($resultImportar == true){
		$resultado  = $importadasExito.' filas importadas exitosamente. <br/>';
		$resultado .= $importadasError. ' filas con error. <br/>';
		$resultado .= $causasError;
		
		echo $resultado;
		
		// bitacora
		$acciones .= 'Fue importado el archivo '.$nombre.' para la creación de preventivos.<br/><br/>';
		$acciones .= '<b>Resultado:</b><br/>';
		$acciones .= $resultado;
		$acciones .= '<b>Actividades importadas:</b><br/>';
		$acciones .= $listaImportadas;
		
		bitacora($_SESSION['usuario'],'Plan de Mantenimiento',$acciones,0,'');
	}else{
		$resultado = $importadasError. ' filas con error. <br/>';
		$resultado .= $causasError;
		echo $resultado;
	}

}