<?php
	include("../conexion.php");

	global $mysqli;
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
	$bhorac			= $_REQUEST['bhorac'];
	$bempresa 		= $_REQUEST['bempresa'];
	$bdepartamento	= $_REQUEST['bdepartamento'];
	$bcliente 		= $_REQUEST['bcliente'];
	$bproyecto		= $_REQUEST['bproyecto'];  
	$basignadoa 	= $_REQUEST['basignadoa'];
	$bsitio			= $_REQUEST['bsitio'];
	$bmodalidad 	= $_REQUEST['bmodalidad'];
	$bserie			= $_REQUEST['bserie'];
	$bmarca 		= $_REQUEST['bmarca'];
	$bmodelo		= $_REQUEST['bmodelo'];
	$bprioridad 	= $_REQUEST['bprioridad'];
	$bcierre		= $_REQUEST['bcierre'];	
	/** Error reporting */
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	//date_default_timezone_set('Europe/London');
	
	$id 	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
	$data 	 = '';
	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

	/** Include PHPExcel */
	//require_once dirname(__FILE__) . '../../repositorio-lib/xls/Classes/PHPExcel.php';
	require_once '../../repositorio-lib/xls/Classes/PHPExcel.php';

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
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:AE1');
	
	// ENCABEZADO 
	$objPHPExcel->getActiveSheet()
	->setCellValue('A4', '# Incidente')
	->setCellValue('B4', 'Titulo')
	->setCellValue('C4', 'Descripción')		
	->setCellValue('D4', 'Cliente')
	->setCellValue('E4', 'Proyecto')
	->setCellValue('F4', 'Estado')
	->setCellValue('G4', 'Equipo') 
	->setCellValue('H4', 'Serie') 
	->setCellValue('I4', 'Marca')
	->setCellValue('J4', 'Modelo') 
	->setCellValue('K4', 'Sitio') 
	->setCellValue('L4', 'Prioridad')
	->setCellValue('M4', 'Origen')
	->setCellValue('N4', 'Creado por')
	->setCellValue('O4', 'Solicitante')
	->setCellValue('P4', 'Asignado a')
	->setCellValue('Q4', 'Departamento')				
	->setCellValue('R4', 'Resuelto por')
	->setCellValue('S4', 'Resolución')
	->setCellValue('T4', 'Satisfacción')
	->setCellValue('U4', 'Comentario de Satisfacción')		
	->setCellValue('V4', 'Fecha de creación')
	->setCellValue('W4', 'Hora de creación')
	->setCellValue('X4', 'Fecha de resolución')
	->setCellValue('Y4', 'Hora de resolución')
	->setCellValue('Z4', 'Fecha de cierre')
	->setCellValue('AA4', 'Hora de cierre')
	->setCellValue('AB4', 'Fecha de vencimiento')
	->setCellValue('AC4', 'Hora de vencimiento')
	->setCellValue('AD4', 'Fecha real')
	->setCellValue('AE4', 'Hora de real')		
	->setCellValue('AF4', 'Tiempo de servicio')
	->setCellValue('AG4', 'Horas Trabajadas')
	->setCellValue('AH4', 'Periodo')
	->setCellValue('AI4', 'Comentarios');
	
	//LETRA
	$objPHPExcel->getActiveSheet()->getStyle('A4:AI4')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
	$objPHPExcel->getActiveSheet()->getStyle("A4:AI4")->applyFromArray($style);
	//FONDO
	$objPHPExcel->getActiveSheet()->getStyle('A4:AI4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('63b9db');
	
	//SENTENCIA BASE
	$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, e.nombre AS estado,
				 a.sitio, a.equipo, h.prioridad, a.serie, a.marca, a.modelo,
					a.origen, a.creadopor, a.solicitante, a.asignadoa, a.departamento, a.resueltopor,
					a.resolucion, a.satisfaccion, a.comentariosatisfaccion, 
					ifnull(a.fechacreacion, '') AS fechacreacion, a.horacreacion, 
					ifnull(a.fecharesolucion, '') as fecharesolucion, a.horaresolucion,
					ifnull(a.fechacierre, '') as fechacierre, a.horacierre, 
					ifnull(a.fechavencimiento, '') AS fechavencimiento, a.horavencimiento, 
					ifnull(a.fechareal, '') AS fechareal, a.horareal, a.horastrabajadas, 
					cu.periodo, o.nombre as cliente, co.comentario 
					FROM incidenteslab a
					LEFT JOIN proyectos b ON a.idproyectos = b.id 
					LEFT JOIN estados e ON a.estado = e.id 
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo 
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					LEFT JOIN cuatrimestres cu ON a.fecharesolucion BETWEEN cu.fechainicio AND cu.fechafin
					LEFT JOIN comentarioslab co ON a.id = co.idmodulo
				";
	
	if($nivel != 1 && $nivel != 2){
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
		}			
	}
	
	//DATOS 
	$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Incidentes' AND usuario = '".$_SESSION['usuario']."'";
	$resultF = $mysqli->query($queryF);
	if($resultF->num_rows >0){
		$rowF = $resultF->fetch_assoc();				
		if (!isset($_REQUEST['data'])) {
			$data = $rowF['filtrosmasivos'];
		}
	}
	$where2 = '';
	if($data != ''){
		$data = json_decode($data);
		if(!empty($data->desdef)){
			$desdef = json_encode($data->desdef);
			$where2 .= " AND a.fechacreacion >= $desdef ";
		} else {
			//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
		}
		if(!empty($data->hastaf)){
			$hastaf = json_encode($data->hastaf);
			$where2 .= " AND a.fechacreacion <= $hastaf ";
		}
		if(!empty($data->categoriaf)){
			$categoriaf = json_encode($data->categoriaf);
			$where2 .= " AND a.idcategoria IN ($categoriaf)";
		}
		if(!empty($data->subcategoriaf)){
			$subcategoriaf = json_encode($data->subcategoriaf);
			$where2 .= " AND a.idsubcategoria IN ($subcategoriaf)";
		}			
		if(!empty($data->idempresasf)){
			$idempresasf = json_encode($data->idempresasf);
			$where2 .= " AND a.idempresas IN ($idempresasf)"; 
		}
		if(!empty($data->iddepartamentosf)){
			$iddepartamentosf = json_encode($data->iddepartamentosf);
			$where2 .= " AND a.iddepartamentos IN ($iddepartamentosf)"; 
		}
		if(!empty($data->idclientesf)){
			$idclientesf = json_encode($data->idclientesf);
			$where2 .= " AND a.idclientes IN ($idclientesf)"; 
		}
		if(!empty($data->idproyectosf)){
			$idproyectosf = json_encode($data->idproyectosf);
			$where2 .= " AND a.idproyectos IN ($idproyectosf)"; 
		}
		if(!empty($data->prioridadf)){
			$prioridadf = json_encode($data->prioridadf);
			$where2 .= " AND a.idprioridad IN ($prioridadf)";
		}
		if(!empty($data->modalidadf)){
			$modalidadf = json_encode($data->modalidadf);
			$where2 .= " AND m.modalidad IN ($modalidadf)";
		}
		if(!empty($data->marcaf)){
			$marcaf = json_encode($data->marcaf);
			$where2 .= " AND m.marca IN ($marcaf)"; 
		}
		if(!empty($data->solicitantef)){
			$solicitantef = json_encode($data->solicitantef);
			$where2 .= " AND a.solicitante IN ($solicitantef)";
		}
		if(!empty($data->estadof)){
			$estadof = json_encode($data->estadof);
			$where2 .= " AND a.estado IN ($estadof)";
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
		if(!empty($data->unidadejecutoraf)){
			$unidadejecutoraf = json_encode($data->unidadejecutoraf);
			 if($unidadejecutoraf !== '[""]'){ 
				$where2 .= " AND a.unidadejecutora IN ($unidadejecutoraf)";
			}
		}	
		$vowels = array("[", "]");
		$where2 = str_replace($vowels, "", $where2);
	}
	//LocalStorage
	if($bid != ''){
		$where2 .= " AND a.id = $bid ";
	}
	if($bestado != ''){
		$where2 .= " AND e.nombre like '%".$bestado."%' ";
	}
	if($btitulo != ''){
		$where2 .= " AND a.titulo like '%".$btitulo."%' ";
	}
	if($bsolicitante != ''){
		$where2 .= " AND j.nombre like '%".$bsolicitante."%' ";
	}
	if($bcreacion != ''){
		$where2 .= " AND a.fechacreacion = '".$bcreacion."' ";
	}
	if($bhorac != ''){
		$where2 .= " AND a.horacreacion = '".$bhorac."' ";
	}		
	if($bempresa != ''){
		$where2 .= " AND n.descripcion like '%".$bempresa."%' ";
	}
	if($bdepartamento != ''){
		$where2 .= " AND o.nombre like '%".$bdepartamento."%' ";
	}
	if($bcliente != ''){
		$where2 .= " AND p.nombre like '%".$bcliente."%' ";
	}		
	if($bproyecto != ''){
		$where2 .= " AND b.nombre like '%".$bproyecto."%' ";
	} 
	if($basignadoa != ''){
		$where2 .= " AND l.nombre like '%".$basignadoa."%' ";
	}
	if($bsitio != ''){
		$where2 .= " AND c.unidad like '%".$bsitio."%' ";
	}
	if($bmodalidad != ''){
		$where2 .= " AND m.modalidad like '%".$bmodalidad."%' ";
	}		
	if($bserie != ''){
		$where2 .= " AND a.serie like '%".$bserie."%' ";
	}
	if($bmarca != ''){
		$where2 .= " AND m.marca like '%".$bmarca."%' ";
	}
	if($bmodelo != ''){
		$where2 .= " AND m.modelo like '%".$bmodelo."%' ";
	}
	if($bprioridad != ''){
		$where2 .= " AND h.prioridad like '%".$bprioridad."%' ";
	}
	if($bcierre != ''){
		$where2 .= " AND a.fechacierre = '".$bcierre."' ";
	}
	
	//CUERPO
	//Definir fuente
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);					
	
	$query  .= " $where2 ORDER BY a.id desc ";
	//debug('Exportar: '.$query);
	$result = $mysqli->query($query);
	$i = 5;		
	while($row = $result->fetch_assoc()){
		$fcreacion 	= date_create($row['fechacreacion'].' '.$row['horacreacion']);
		$fecharesolucion = date_create($row['fecharesolucion'].' '.$row['horaresolucion']); 
		if($fecharesolucion == ''){
			$fecharesolucion = date('Y-m-d');
		}
		$interval = date_diff($fcreacion, $fecharesolucion);
		$dif = $interval->format('%d d %h h');
		
		//USUARIO O GRUPO DE USUARIOS ASIGNADOS		
		$asignadoaN	= '';		
		if($row['asignadoa'] != ''){			
			$query2 = " SELECT nombre FROM usuarios WHERE ";
			if (filter_var($row['asignadoa'], FILTER_VALIDATE_EMAIL)) {
				$query2 .= "correo = '".$row['asignadoa']."'";
			}else{
				$query2 .= "correo IN ('".$row['asignadoa']."') ";
			}
			$consulta = $mysqli->query($query2);
			while($rec = $consulta->fetch_assoc()){
				$asignadoaN .= $rec['nombre']." , ";
			}			
		}
		
		// conversion de formatos de fecha
		$xfechacreacion = $row['fechacreacion'];
		$xfechavencimiento = $row['fechavencimiento'];
		$xfecharesolucion = $row['fecharesolucion'];
		$xfechacierre = $row['fechacierre'];
		$xfechareal = $row['fechareal'];
		
		if ($row['fechacreacion']!='') {
			$xfechacreacion = date_create_from_format('Y-m-d', $row['fechacreacion']);
			$xfechacreacion = date_format($xfechacreacion, "m/d/Y");
		}
		if ($row['fechavencimiento']!='') {
			$xfechavencimiento = date_create_from_format('Y-m-d', $row['fechavencimiento']);
			$xfechavencimiento = date_format($xfechavencimiento, "m/d/Y");
		}
		if ($row['fecharesolucion']!='') {
			$xfecharesolucion = date_create_from_format('Y-m-d', $row['fecharesolucion']);
			$xfecharesolucion = date_format($xfecharesolucion, "m/d/Y");
		}
		if ($row['fechacierre']!='') {
			$xfechacierre = date_create_from_format('Y-m-d', $row['fechacierre']);
			$xfechacierre = date_format($xfechacierre, "m/d/Y");
		}
		if ($row['fechareal']!='') {
			$xfechareal = date_create_from_format('Y-m-d', $row['fechareal']);
			$xfechareal = date_format($xfechareal, "m/d/Y");
		}			
		
		$numeroreq = str_pad($row['id'], 4, "0", STR_PAD_LEFT);		
		$objPHPExcel->getActiveSheet()
		->setCellValue('A'.$i, $numeroreq)
		->setCellValue('B'.$i, $row['titulo'])
		->setCellValue('C'.$i, $row['descripcion'])
		->setCellValue('D'.$i, $row['cliente'])
		->setCellValue('E'.$i, $row['proyecto'])
		->setCellValue('F'.$i, $row['estado'])
		->setCellValue('G'.$i, $row['equipo']) 
		->setCellValue('H'.$i, $row['serie']) 
		->setCellValue('I'.$i, $row['marca'])
		->setCellValue('J'.$i, $row['modelo'])
		->setCellValue('K'.$i, $row['sitio'])
		->setCellValue('L'.$i, $row['prioridad'])
		->setCellValue('M'.$i, $row['origen'])
		->setCellValue('N'.$i, $row['creadopor'])
		->setCellValue('O'.$i, $row['solicitante'])
		->setCellValue('P'.$i, $asignadoaN)
		->setCellValue('Q'.$i, $row['departamento'])
		->setCellValue('R'.$i, $row['resueltopor'])
		->setCellValue('S'.$i, $row['resolucion'])
		->setCellValue('T'.$i, $row['satisfaccion'])
		->setCellValue('U'.$i, $row['comentariosatisfaccion'])
		->setCellValue('V'.$i, $xfechacreacion) //->setCellValue('W'.$i, implode('/',array_reverse(explode('-', $row['fechacreacion']))))
		->setCellValue('W'.$i, $row['horacreacion'])
		->setCellValue('X'.$i, $xfecharesolucion)// ->setCellValue('AA'.$i, implode('/',array_reverse(explode('-', $row['fecharesolucion']))))
		->setCellValue('Y'.$i, $row['horaresolucion'])
		->setCellValue('Z'.$i, $xfechacierre) //->setCellValue('AC'.$i, implode('/',array_reverse(explode('-', $row['fechacierre']))))
		->setCellValue('AA'.$i, $row['horacierre'])
		->setCellValue('AB'.$i, $xfechavencimiento) // ->setCellValue('Y'.$i, implode('/',array_reverse(explode('-', $row['fechavencimiento']))))
		->setCellValue('AC'.$i, $row['horavencimiento'])
		->setCellValue('AD'.$i, $xfechareal) // ->setCellValue('Y'.$i, implode('/',array_reverse(explode('-', $row['fechavencimiento']))))
		->setCellValue('AE'.$i, $row['horareal'])
		->setCellValue('AF'.$i, $dif)
		->setCellValue('AG'.$i, $row['horastrabajadas'])
		->setCellValue('AH'.$i, $row['periodo'])
		->setCellValue('AI'.$i, $row['comentario']);
		
		//ESTILOS
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':AJ'.$i)->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':AJ'.$i)->getAlignment()->applyFromArray(
					array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));
		$objPHPExcel->getActiveSheet()->getStyle('Z'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
		$objPHPExcel->getActiveSheet()->getStyle('Z'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
		$objPHPExcel->getActiveSheet()->getStyle('AF'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
		$objPHPExcel->getActiveSheet()->getStyle('AF'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
		$objPHPExcel->getActiveSheet()->getStyle('AB'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
		$objPHPExcel->getActiveSheet()->getStyle('AB'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
		$objPHPExcel->getActiveSheet()->getStyle('AD'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
		$objPHPExcel->getActiveSheet()->getStyle('AD'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
		$objPHPExcel->getActiveSheet()->getStyle('AH'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
		$i++;
	}

	//Ancho automatico
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')-> setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(18);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(24);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(24);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(18);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);

	//Renombrar hoja de Excel
	$objPHPExcel->getActiveSheet()->setTitle('Incidentes - Correctivos');

	//Redirigir la salida al navegador del cliente
	$hoy = date('dmY');
	$nombreArc = 'Correctivos - '.$hoy.'.xls';
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$nombreArc.'"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit();
?>