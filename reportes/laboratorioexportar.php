<?php
	include("../conexion.php");
	require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';

	global $mysqli;
	//SESSION
 	$usuario 		 = $_SESSION['usuario'];
	$nivel 			 = $_SESSION['nivel'];
	$idempresas 	 = $_SESSION['idempresas'];
	$iddepartamentos = $_SESSION['iddepartamentos'];
	$idclientes 	 = $_SESSION['idclientes'];
	$idproyectos 	 = $_SESSION['idproyectos'];
	//LocalStorage
	$bid 			= $_REQUEST['bid'];
	$bestado		= $_REQUEST['bestado'];
	$btitulo 		= $_REQUEST['btitulo'];
	$bsolicitante	= $_REQUEST['bsolicitante'];
	$bcreacion 		= $_REQUEST['bcreacion'];
	$bempresa 		= $_REQUEST['bempresa'];
	$bdepartamento	= $_REQUEST['bdepartamento'];
	$bcliente 		= $_REQUEST['bcliente'];
	$bproyecto		= $_REQUEST['bproyecto'];
	$basignadoa 	= $_REQUEST['basignadoa'];
	$bserie			= $_REQUEST['bserie'];
	$bmarca 		= $_REQUEST['bmarca'];
	$bmodelo		= $_REQUEST['bmodelo'];
	$bprioridad 	= $_REQUEST['bprioridad'];
	$bcierre		= $_REQUEST['bcierre'];
	
	/** Error reporting */
	error_reporting(E_ALL);
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
	$sheet->setTitle('Laboratorio');
	
	$fontColor = new \PhpOffice\PhpSpreadsheet\Style\Color();
	$fontColor->setRGB('ffffff');
	$style = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
	);
	
	//TITULO	
	$spreadsheet->getActiveSheet()->setCellValue('A1', 'Reporte de Laboratorio');
	$spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
	//$spreadsheet->getActiveSheet()->getStyle("A1")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->mergeCells('A1:AB1');
	
	// ENCABEZADO 
	$spreadsheet->getActiveSheet()
	->setCellValue('A4', '# Registro')
	->setCellValue('B4', 'Nombre de activo')
	->setCellValue('C4', 'Detalle del daño')
	->setCellValue('D4', 'Cliente')
	->setCellValue('E4', 'Proyecto')
	->setCellValue('F4', 'Estado') 
	->setCellValue('G4', 'Serie')  
	->setCellValue('H4', 'Marca')
	->setCellValue('I4', 'Modelo') 
	->setCellValue('J4', 'Estado del equipo')
	->setCellValue('K4', 'Prioridad') 
	->setCellValue('L4', 'Solicitante')
	->setCellValue('M4', 'Asignado a')
	->setCellValue('N4', 'Departamento')  
	->setCellValue('O4', 'Creación')  
	->setCellValue('P4', 'Resolución');
	
	$spreadsheet->getActiveSheet()->getStyle('A4:P4')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle("A4:P4")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->getStyle('A4:P4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	
	$usuario 		 = $_SESSION['usuario'];
	$nivel 			 = $_SESSION['nivel'];
	$idempresas 	 = $_SESSION['idempresas'];
	$iddepartamentos = $_SESSION['iddepartamentos'];
	$idclientes 	 = $_SESSION['idclientes'];
	$idproyectos 	 = $_SESSION['idproyectos'];
	
	//SENTENCIA BASE
	$query  = " SELECT a.id, a.titulo, a.descripcion, a.idempresas, p.nombre as cliente, b.nombre AS proyecto, e.nombre AS estado,
				a.serie, a.marca, a.modelo, a.diagnostico,
				h.prioridad, (SELECT nombre FROM usuarios ns WHERE a.solicitante = ns.correo ) AS solicitante, 
				(SELECT nombre FROM usuarios na WHERE a.asignadoa = na.correo ) AS asignadoa, 
				o.nombre AS departamento,
				ifnull(a.fechacreacion, '') AS fechacreacion, 
				ifnull(a.fecharesolucion, '') as fecharesolucion, horaresolucion
				FROM laboratorio a
				INNER JOIN proyectos b ON a.idproyectos = b.id
				INNER JOIN estados e ON a.estado = e.id
				LEFT JOIN sla h ON a.idprioridad = h.id
				LEFT JOIN usuarios j ON a.solicitante = j.correo
				LEFT JOIN usuarios l ON a.asignadoa = l.correo 
				LEFT JOIN usuarios k ON a.creadopor = k.correo
				LEFT JOIN empresas n ON a.idempresas = n.id
				INNER JOIN departamentos o ON a.iddepartamentos = o.id
				INNER JOIN clientes p ON a.idclientes = p.id
				";
	
	$pos = strpos($iddepartamentos, '4');
	//Validar Solo usuarios Lab / Usuarios Admin Soporte
	if($_SESSION['usuario'] != 'umague' && $_SESSION['usuario'] != 'mbatista' && $nivel != 1 && $nivel != 2 && $pos !== true){
		$queryCorreoU = " SELECT correo FROM usuarios WHERE usuario = '".$_SESSION['usuario']."'";
		$resultCorreo = $mysqli->query($queryCorreoU);
		if($rowCorreoU = $resultCorreo->fetch_assoc()){
			$correousuario = $rowCorreoU['correo'];
			if($correousuario!=""){
				$query .= " AND (solicitante = '".$correousuario."' OR creadopor = '".$correousuario."')";
			}
		} 
	}
/* 	if($nivel != 1 && $nivel != 2){
		$query .= " LEFT JOIN usuarios q ON find_in_set(c.codigo, q.sitio) AND q.usuario = '$usuario' ";
	}
	
	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= "AND a.idempresas in ($idempresas) ";
	}
	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= "AND a.idclientes in ($idclientes) ";
	}
	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= "AND a.idproyectos in ($idproyectos) ";
	}
	if($nivel == 3) {
		$query  .= " AND (
						j.usuario = '".$_SESSION['usuario']."' OR 
						l.usuario = '".$_SESSION['usuario']."' OR
						FIND_IN_SET(a.iddepartamentos,(SELECT GROUP_CONCAT( DISTINCT ee.id SEPARATOR  ',' )			
													FROM usuarios a
													LEFT JOIN departamentos ee ON FIND_IN_SET(ee.id, a.iddepartamentos) AND ee.tipo = 'grupo'
													WHERE a.usuario = '".$_SESSION['usuario']."')) 
					)";
	}elseif($nivel == 4){
		if($_SESSION['sitio'] != ''){
			$sitio = $_SESSION['sitio'];
			$sitio = explode(',',$sitio);
			$sitio = implode("','", $sitio);
			$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.unidadejecutora IN ('".$sitio."') ) ";
		}else{
			//$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
			if($_SESSION['iddepartamentos'] != ''){
				$iddepartamentosSES = $_SESSION['iddepartamentos'];
				$query  .= "AND a.iddepartamentos IN ('".$iddepartamentosSES."')  ";
			}
		}
	} */
	
	//FILTROS MASIVOS
	$where	= "";
	$data	= "";
	$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Laboratorio' AND usuario = '".$_SESSION['usuario']."'";
	$resultF = $mysqli->query($queryF);
	if($resultF->num_rows >0){
		$rowF = $resultF->fetch_assoc();				
		if (!isset($_REQUEST['data'])) {
			$data = $rowF['filtrosmasivos'];
		}
	}		
	if($data != ''){
		$data = json_decode($data);
		if(!empty($data->desdef)){
			$desdef = json_encode($data->desdef);
			$where2 .= " AND a.fechacreacion >= $desdef ";
		}
		if(!empty($data->hastaf)){
			$hastaf = json_encode($data->hastaf);
			$where2 .= " AND a.fechacreacion <= $hastaf ";
		}
		if(!empty($data->idempresasf)){
			$idempresasf = json_encode($data->idempresasf);
			if($idempresasf != '[""]'){
				$where2 .= " AND a.idempresas IN ($idempresasf)"; 
			}				
		}			
		if(!empty($data->idclientesf)){
			$idclientesf = json_encode($data->idclientesf);
			if($idclientesf != '[""]'){
				$where2 .= " AND a.idclientes IN ($idclientesf)"; 
			}				
		}
		if(!empty($data->idproyectosf)){
			$idproyectosf = json_encode($data->idproyectosf);
			if($idproyectosf != '[""]'){
				$where2 .= " AND a.idproyectos IN ($idproyectosf)"; 
			}				
		}
		if(!empty($data->iddepartamentosf)){
			$iddepartamentosf = json_encode($data->iddepartamentosf);
			if($iddepartamentosf != '[""]'){
				$where2 .= " AND a.iddepartamentos IN ($iddepartamentosf)"; 
			}
		}
		if(!empty($data->prioridadf)){
			$prioridadf = json_encode($data->prioridadf);
			if($prioridadf != '[""]'){
				$where2 .= " AND a.idprioridad IN ($prioridadf)";
			}				
		}
		if(!empty($data->marcaf)){
			$marcaf = json_encode($data->marcaf);
			if($marcaf != '[""]'){
				$where2 .= " AND a.marca IN ($marcaf)"; 
			}
		}
		if(!empty($data->solicitantef)){
			$solicitantef = json_encode($data->solicitantef);
			if($solicitantef != '[""]'){
				$where2 .= " AND a.solicitante IN ($solicitantef)";
			}
		}
		if(!empty($data->estadof)){
			$estadof = json_encode($data->estadof);
			if($estadof != '[""]'){
				$where2 .= " AND a.estado IN ($estadof)";
			}
		}
		if(!empty($data->diagnosticof)){
			$diagnosticof = json_encode($data->diagnosticof);
			if($diagnosticof != '[""]'){
				$where2 .= " AND a.diagnostico IN ($diagnosticof)";
			}
		}
		if(!empty($data->asignadoaf)){
			$asignadoaf = json_encode($data->asignadoaf);
			$asignadoaf = '';
			$i = 0;
			foreach($data->asignadoaf as $usuarios){
				if($i > 0)
					$asignadoaf .=",";
				$asignadoaf .= "'$usuarios'";
				$i++;
			}
			if($asignadoaf != "''"){
				$where2 .= " AND a.asignadoa IN ($asignadoaf)";	
			}
		}
		$vowels = array("[", "]");
		$where2 = str_replace($vowels, "", $where2);
	}
		
	//LocalStorage
	if($bid != ''){
		$where .= " AND a.id = $bid ";
	}
	if($bestado != ''){
		$where .= " AND e.nombre LIKE '%".$bestado."%' ";
	}
	if($btitulo != ''){
		$where .= " AND a.titulo LIKE '%".$btitulo."%' ";
	}
	if($bsolicitante != ''){
		$where .= " AND j.nombre LIKE '%".$bsolicitante."%' ";
	}
	if($bcreacion != ''){
		$where .= " AND a.fechacreacion LIKE '%".$bcreacion."%' ";
	} 
	if($bdepartamento != ''){
		$where .= " AND o.nombre LIKE '%".$bdepartamento."%' ";
	}
	if($bcliente != ''){
		$where .= " AND p.nombre LIKE '%".$bcliente."%' ";
	}		
	if($bproyecto != ''){
		$where .= " AND b.nombre LIKE '%".$bproyecto."%' ";
	}
	if($basignadoa != ''){
		$where .= " AND l.nombre LIKE '%".$basignadoa."%' ";
	}
	if($bserie != ''){
		$where .= " AND a.serie LIKE '%".$bserie."%' ";
	}
	if($bmarca != ''){
		$where .= " AND a.marca LIKE '%".$bmarca."%' ";
	}
	if($bmodelo != ''){
		$where .= " AND a.modelo LIKE '%".$bmodelo."%' ";
	}
	if($bprioridad != ''){
		$where .= " AND h.prioridad LIKE '%".$bprioridad."%' ";
	}
	if($bcierre != ''){
		$where .= " AND a.fechacierre LIKE '%".$bcierre."%' ";
	}
	$query  .= " $where ORDER BY a.id DESC ";
	//$query  .= " LIMIT 10 ";
	debugL("LABORATORIOS-EXPORTAR:".$query);
	
	$result = $mysqli->query($query);
	$i = 5;
	while($row = $result->fetch_assoc()){
		$fcreacion 	= date_create($row['fechacreacion']);
		$fecharesolucion = date_create($row['fecharesolucion'].' '.$row['horaresolucion']); 
		if($fecharesolucion == ''){
			$fecharesolucion = date('Y-m-d');
		}
		$interval = date_diff($fcreacion, $fecharesolucion);
		$dif = $interval->format('%d d %h h');
		 
		
		// conversion de formatos de fecha
		$xfechacreacion = $row['fechacreacion'];
		 
		if ($row['fecharesolucion'] != '') {
			$xfecharesolucion = date_create_from_format('Y-m-d', $row['fecharesolucion']);
			$xfecharesolucion = date_format($xfecharesolucion, "m/d/Y");
		}else{
			$xfecharesolucion = "";
		}
		
		//$numeroreq = str_pad($row['id'], 4, "0", STR_PAD_LEFT);		
		$spreadsheet->getActiveSheet()
		->setCellValue('A'.$i, $row['id'])
		->setCellValue('B'.$i, $row['titulo'])
		->setCellValue('C'.$i, $row['descripcion'])
		->setCellValue('D'.$i, $row['cliente'])
		->setCellValue('E'.$i, $row['proyecto'])
		->setCellValue('F'.$i, $row['estado']) 
		->setCellValue('G'.$i, $row['serie']) 
		->setCellValue('H'.$i, $row['marca'])
		->setCellValue('I'.$i, $row['modelo']) 
		->setCellValue('J'.$i, ucwords($row['diagnostico']))
		->setCellValue('K'.$i, $row['prioridad'])
		->setCellValue('L'.$i, $row['solicitante'])
		->setCellValue('M'.$i, $row['asignadoa']) 
		->setCellValue('N'.$i, $row['departamento']) 
		->setCellValue('O'.$i, $xfechacreacion)  
		->setCellValue('P'.$i, $xfecharesolucion); 
		$i++;
	}
	
	//Ancho automatico	
	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true); 
	$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true); 
	$spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true); 
	
	$hoy = date('dmY');
	$nombreArc = 'Laboratorio - '.$hoy.'.xlsx';
	// redirect output to client browser
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Type: application/vnd.ms-excel'); //xls
	header('Content-Disposition: attachment;filename='.$nombreArc);
	header('Cache-Control: max-age=0');	
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');
	
?>