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
	$bcategoria 	= $_REQUEST['bcategoria'];	
	$bsubcategoria	= $_REQUEST['bsubcategoria'];
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
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Reporte de Taller');
	$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
	$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($style);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:AE1');
	
	// ENCABEZADO 
	$objPHPExcel->getActiveSheet()
	->setCellValue('A4', '# Taller')
	->setCellValue('B4', 'Titulo')
	->setCellValue('C4', 'Descripción')		
	->setCellValue('D4', 'Cliente')
	->setCellValue('E4', 'Proyecto')
	->setCellValue('F4', 'Estado')
//	->setCellValue('G4', 'Equipo')
	->setCellValue('G4', 'Serie')
//	->setCellValue('I4', 'Activo')
	->setCellValue('H4', 'Marca')
	->setCellValue('I4', 'Modelo')
//	->setCellValue('L4', 'Modalidad')
//	->setCellValue('M4', 'Estado del equipo')
//	->setCellValue('N4', 'Categoría')
//	->setCellValue('O4', 'Subcategoría')
//	->setCellValue('P4', 'Sitio')
	->setCellValue('J4', 'Prioridad')
	->setCellValue('K4', 'Origen')
	->setCellValue('L4', 'Creado por')
	->setCellValue('M4', 'Solicitante')
	->setCellValue('N4', 'Asignado a')
	->setCellValue('O4', 'Departamento')				
//	->setCellValue('W4', 'Resuelto por')
	->setCellValue('P4', 'Resolución')
//	->setCellValue('Y4', 'Satisfacción')
	->setCellValue('Q4', 'Comentario de Satisfacción')		
	->setCellValue('R4', 'Fecha de creación')
	->setCellValue('S4', 'Fecha de primer comentario')
	->setCellValue('T4', 'Fecha de asignación')
	->setCellValue('U4', 'Fecha de en proceso')
	->setCellValue('V4', 'Fecha de resolución');
	/*
	->setCellValue('AB4', 'Hora de creación')
	->setCellValue('AC4', 'Fecha de resolución')
	->setCellValue('AD4', 'Hora de resolución')
	->setCellValue('AE4', 'Fecha de cierre')
	->setCellValue('AF4', 'Hora de cierre')
	->setCellValue('AG4', 'Fecha de vencimiento')
	->setCellValue('AH4', 'Hora de vencimiento')
	->setCellValue('AI4', 'Fecha real')
	->setCellValue('AJ4', 'Hora de real')
	*/	
//	->setCellValue('AF4', 'Tiempo de servicio')
//	->setCellValue('AG4', 'Horas Trabajadas')
//	->setCellValue('AH4', 'Periodo')
//	->setCellValue('AI4', 'Fuera de Servicio')
//	->setCellValue('AJ4', 'Fuera de Servicio desde')
//	->setCellValue('AK4', 'Fuera de Servicio hasta')
//	->setCellValue('AL4', 'Dias Fuera de Servicio');
	
	//LETRA
	$objPHPExcel->getActiveSheet()->getStyle('A4:V4')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
	$objPHPExcel->getActiveSheet()->getStyle("A4:V4")->applyFromArray($style);
	//FONDO
	$objPHPExcel->getActiveSheet()->getStyle('A4:V4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('63b9db');
	
	$usuario 		 = $_SESSION['usuario'];
	$nivel 			 = $_SESSION['nivel'];
	$idempresas 	 = $_SESSION['idempresas'];
	$iddepartamentos = $_SESSION['iddepartamentos'];
	$idclientes 	 = $_SESSION['idclientes'];
	$idproyectos 	 = $_SESSION['idproyectos'];	
	//SENTENCIA BASE
	$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, e.nombre AS estado, m.codequipo, m.activo, a.marca as marca, a.modelo as modelo ,a.serie as serie,
				m.modalidad, m.estado as estadoequipo, f.nombre AS categoria, g.nombre AS subcategoria, c.unidad AS sitio, h.prioridad, a.origen, a.creadopor, 
				a.solicitante, a.asignadoa, o.nombre as departamento, a.resueltopor, a.resolucion, a.satisfaccion, a.comentariosatisfaccion, a.estadoant,
				ifnull(a.fechacreacion, '') AS fechacreacion, a.horacreacion, ifnull(a.fecharesolucion, '') as fecharesolucion, a.horaresolucion,
				a.horastrabajadas, cu.periodo, p.nombre as cliente, a.fechadesdefueraservicio, a.fechafinfueraservicio, 
				(CASE WHEN a.fueraservicio = 0 then 'No' else 'Si' END) as fueraservicio,
				(CASE WHEN a.fechafinfueraservicio is null || a.fechafinfueraservicio = '' then (TIMESTAMPDIFF(DAY, a.fechadesdefueraservicio, CURRENT_DATE)) ELSE (TIMESTAMPDIFF(DAY, a.fechadesdefueraservicio, a.fechafinfueraservicio)) END) as diasfueraservicio,
				(SELECT CONCAT(ie.fechacambio, ' ', ie.horacambio) FROM incidentesestados ie WHERE a.id = ie.idincidentes AND ie.estadonuevo = 13 ORDER BY ie.id DESC LIMIT 1) AS estadoasignado,
				(SELECT CONCAT(iep.fechacambio, ' ', iep.horacambio) FROM incidentesestados iep WHERE a.id = iep.idincidentes AND iep.estadonuevo = 33 ORDER BY iep.id DESC LIMIT 1) AS estadoenproceso,
				(SELECT fecha FROM comentarios ci WHERE a.id = ci.idmodulo ORDER BY ci.id ASC LIMIT 1) AS primercomentario
				FROM taller a
				LEFT JOIN proyectos b ON a.idproyectos = b.id
				LEFT JOIN unidades c ON a.unidadejecutora = c.codigo
				LEFT JOIN estados e ON a.estado = e.id
				LEFT JOIN categorias f ON a.idcategoria = f.id
				LEFT JOIN subcategorias g ON a.idsubcategoria = g.id
				LEFT JOIN sla h ON a.idprioridad = h.id
				LEFT JOIN usuarios j ON a.solicitante = j.correo
				LEFT JOIN usuarios l ON a.asignadoa = l.correo
				LEFT JOIN activos m ON a.serie = m.codequipo AND a.unidadejecutora = m.codigound
				LEFT JOIN empresas n ON a.idempresas = n.id
				LEFT JOIN departamentos o ON a.iddepartamentos = o.id
				LEFT JOIN clientes p ON a.idclientes = p.id
				LEFT JOIN cuatrimestres cu ON a.fecharesolucion BETWEEN cu.fechainicio AND cu.fechafin
				";	
	if($nivel != 1 && $nivel != 2){
		$query .= " LEFT JOIN usuarios q ON find_in_set(c.codigo, q.sitio) AND q.usuario = '$usuario' ";
	}
	$query  .= " WHERE a.idcategoria in (0) ";
	
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
	}
	
	//DATOS 
	$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Taller' AND usuario = '".$_SESSION['usuario']."'";
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
			if($categoriaf != '[""]'){
				$where2 .= " AND a.idcategoria IN ($categoriaf)";
			}
		}
		if(!empty($data->subcategoriaf)){
			$subcategoriaf = json_encode($data->subcategoriaf);
			if($subcategoriaf != '[""]'){
				$where2 .= " AND a.idsubcategoria IN ($subcategoriaf)";
			}
		}			
		if(!empty($data->idempresasf)){
			$idempresasf = json_encode($data->idempresasf);
			if($idempresasf != '[""]'){
				$where2 .= " AND a.idempresas IN ($idempresasf) "; 
			}				
		}
		if(!empty($data->iddepartamentosf)){
			$iddepartamentosf = json_encode($data->iddepartamentosf);
			$where2 .= " AND a.iddepartamentos IN ($iddepartamentosf)"; 
		}
		if(!empty($data->idclientesf)){
			$idclientesf = json_encode($data->idclientesf);
			if($idclientesf != '[""]'){
				$where2 .= " AND a.idclientes IN ($idclientesf) "; 
			}				
		}
		if(!empty($data->idproyectosf)){
			$idproyectosf = json_encode($data->idproyectosf);
			if($idproyectosf != '[""]'){
				$where2 .= " AND a.idproyectos IN ($idproyectosf)"; 
			}				
		}
		if(!empty($data->prioridadf)){
			$prioridadf = json_encode($data->prioridadf);
			if($prioridadf != '[""]'){
				$where2 .= " AND a.idprioridad IN ($prioridadf)";
			}				
		}
		if(!empty($data->modalidadf)){
			$modalidadf = json_encode($data->modalidadf);
			if($modalidadf != '[""]'){
				$where2 .= " AND m.modalidad IN ($modalidadf)";
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
	if($bcategoria != ''){
		$where2 .= " AND f.nombre like '%".$bcategoria."%' ";
	}
	if($bsubcategoria != ''){
		$where2 .= " AND g.nombre like '%".$bsubcategoria."%' ";
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
		$where2 .= " AND a.marca like '%".$bmarca."%' ";
	}
	if($bmodelo != ''){
		$where2 .= " AND a.modelo like '%".$bmodelo."%' ";
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
	
	$query  .= " $where2 ";
	//$query  .= " ORDER BY a.id desc ";
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
		$xfecharesolucion = $row['fecharesolucion'];
		
		if ($row['fechacreacion'] != '') {
			$xfechacreacion = date_create_from_format('Y-m-d', $row['fechacreacion']);
			$xfechacreacion = date_format($xfechacreacion, "m/d/Y");
		}
		if ($row['primercomentario'] != '') {			
			$arrprimercom = explode(' ',$row['primercomentario']);
			$fprimercomentario = date_create_from_format('Y-m-d', $arrprimercom[0]);
			$fprimercomentario = date_format($fprimercomentario, "m/d/Y").' '.$arrprimercom[1];
		}else{
			$fprimercomentario = "";
		}
		if ($row['estadoasignado'] != '') {
			$arrestadoasig = explode(' ',$row['estadoasignado']);
			//debug($row['estadoasignado']);
			$festadoasignado = date_create_from_format('Y-m-d', $arrestadoasig[0]);
			$festadoasignado = date_format($festadoasignado, "m/d/Y").' '.$arrestadoasig[1];
		}else{
			$festadoasignado = "";
		}
		if ($row['estadoenproceso'] != '') {
			$arrestadoproc = explode(' ',$row['estadoenproceso']);
			$festadoenproceso = date_create_from_format('Y-m-d', $arrestadoproc[0]);
			$festadoenproceso = date_format($festadoenproceso, "m/d/Y").' '.$arrestadoproc[1];
		}else{
			$festadoenproceso = "";
		}
		if ($row['fecharesolucion'] != '') {
			$xfecharesolucion = date_create_from_format('Y-m-d', $row['fecharesolucion']);
			$xfecharesolucion = date_format($xfecharesolucion, "m/d/Y");
		}
		
		if($row['estadoant'] == 1){
			//LETRA
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':AQ'.$i)->getFont()->setSize(12)->setColor($fontColor);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':AQ'.$i)->applyFromArray($style);
			//FONDO
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':AQ'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b2cbea');
		}		
		
		$numeroreq = str_pad($row['id'], 4, "0", STR_PAD_LEFT);		
		$objPHPExcel->getActiveSheet()
		->setCellValue('A'.$i, $numeroreq)
		->setCellValue('B'.$i, $row['titulo'])
		->setCellValue('C'.$i, $row['descripcion'])
		->setCellValue('D'.$i, $row['cliente'])
		->setCellValue('E'.$i, $row['proyecto'])
		->setCellValue('F'.$i, $row['estado'])
//		->setCellValue('G'.$i, $row['equipo'])
		->setCellValue('G'.$i, $row['serie'])
//		->setCellValue('I'.$i, $row['activo'])
		->setCellValue('H'.$i, $row['marca'])
		->setCellValue('I'.$i, $row['modelo'])
//		->setCellValue('L'.$i, $row['modalidad'])
//		->setCellValue('M'.$i, $row['estadoequipo'])
//		->setCellValue('N'.$i, $row['categoria'])
//		->setCellValue('O'.$i, $row['subcategoria'])			
//		->setCellValue('P'.$i, $row['sitio'])
		->setCellValue('J'.$i, $row['prioridad'])
		->setCellValue('K'.$i, $row['origen'])
		->setCellValue('L'.$i, $row['creadopor'])
		->setCellValue('M'.$i, $row['solicitante'])
		->setCellValue('N'.$i, $asignadoaN)
		->setCellValue('O'.$i, $row['departamento'])
//		->setCellValue('W'.$i, $row['resueltopor'])
		->setCellValue('P'.$i, $row['resolucion'])
//		->setCellValue('Y'.$i, $row['satisfaccion'])
		->setCellValue('Q'.$i, $row['comentariosatisfaccion'])
		->setCellValue('R'.$i, $xfechacreacion.' '.$row['horacreacion'])
		->setCellValue('S'.$i, $fprimercomentario)
		->setCellValue('T'.$i, $festadoasignado)
		->setCellValue('U'.$i, $festadoenproceso)
		->setCellValue('V'.$i, $xfecharesolucion.' '.$row['horaresolucion']);
//		->setCellValue('AF'.$i, $dif)
//		->setCellValue('AG'.$i, $row['horastrabajadas'])
//		->setCellValue('AH'.$i, $row['periodo'])
//		->setCellValue('AI'.$i, $row['fueraservicio'])
//		->setCellValue('AJ'.$i, $row['fechadesdefueraservicio'])
//		->setCellValue('AK'.$i, $row['fechafinfueraservicio'])
//		->setCellValue('AL'.$i, $row['diasfueraservicio']);
		
		//ESTILOS
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':V'.$i)->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':V'.$i)->getAlignment()->applyFromArray(
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
		$objPHPExcel->getActiveSheet()->getStyle('V'.$i)->getAlignment()->applyFromArray(
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true); /*
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(18);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(24);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(24);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(18);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);*/

	//Renombrar hoja de Excel
	$objPHPExcel->getActiveSheet()->setTitle('Taller');

	//Redirigir la salida al navegador del cliente
	$hoy = date('dmY');
	$nombreArc = 'Taller - '.$hoy.'.xls';
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$nombreArc.'"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit();	
?>