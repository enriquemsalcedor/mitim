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
	//error_reporting(E_ALL);
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
	$sheet->setTitle('Correctivos');
	
	$fontColor = new \PhpOffice\PhpSpreadsheet\Style\Color();
	$fontColor->setRGB('ffffff');
	$style = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
	);
	
	//TITULO	
	$spreadsheet->getActiveSheet()->setCellValue('A1', 'Reporte de Correctivos');
	$spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
	//$spreadsheet->getActiveSheet()->getStyle("A1")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->mergeCells('A1:AB1');
	
	// ENCABEZADO 
	$spreadsheet->getActiveSheet()
	->setCellValue('A4', '# Correctivos')
	->setCellValue('B4', 'Titulo')
	->setCellValue('C4', 'Descripción')		
	->setCellValue('D4', 'Cliente')
	->setCellValue('E4', 'Proyecto')
	->setCellValue('F4', 'Estado')
	->setCellValue('G4', 'Equipo')
	->setCellValue('H4', 'Serie')
	->setCellValue('I4', 'Activo')
	->setCellValue('J4', 'Marca')
	->setCellValue('K4', 'Modelo')
	->setCellValue('L4', 'Modalidad')
	->setCellValue('M4', 'Estado del equipo')
	->setCellValue('N4', 'Categoría')
	->setCellValue('O4', 'Subcategoría')
	->setCellValue('P4', 'Ambiente')
	->setCellValue('Q4', 'Prioridad')
	->setCellValue('R4', 'Origen')
	->setCellValue('S4', 'Creado por')
	->setCellValue('T4', 'Solicitante')
	->setCellValue('U4', 'Asignado a')
	->setCellValue('V4', 'Departamento')				
	->setCellValue('W4', 'Resuelto por')
	->setCellValue('X4', 'Resolución')
	->setCellValue('Y4', 'Satisfacción')
	->setCellValue('Z4', 'Comentario de Satisfacción')		
	->setCellValue('AA4', 'Fecha de creación')
	->setCellValue('AB4', 'Fecha de primer comentario')
	->setCellValue('AC4', 'Fecha de asignación')
	->setCellValue('AD4', 'Fecha de en proceso')
	->setCellValue('AE4', 'Fecha de resolución')
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
	->setCellValue('AF4', 'Tiempo de servicio')
	->setCellValue('AG4', 'Horas Trabajadas')
	->setCellValue('AH4', 'Periodo')
	->setCellValue('AI4', 'Fuera de Servicio')
	->setCellValue('AJ4', 'Fuera de Servicio desde')
	->setCellValue('AK4', 'Fuera de Servicio hasta')
	->setCellValue('AL4', 'Dias Fuera de Servicio')
	->setCellValue('AM4', 'Atención');	
	
	$spreadsheet->getActiveSheet()->getStyle('A4:AM4')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle("A4:AM4")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->getStyle('A4:AM4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	
	$usuario 		 = $_SESSION['usuario'];
	$nivel 			 = $_SESSION['nivel'];
	$idempresas 	 = $_SESSION['idempresas'];
	$iddepartamentos = $_SESSION['iddepartamentos'];
	$idclientes 	 = $_SESSION['idclientes'];
	$idproyectos 	 = $_SESSION['idproyectos'];
	
	//SENTENCIA BASE
	$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, e.nombre AS estado, m.nombre AS equipo, m.serie, m.activo, 
				mar.nombre as marca, r.nombre as modelo, m.modalidad, m.estado as estadoequipo, f.nombre AS categoria, 
				g.nombre AS subcategoria, c.nombre AS sitio, h.prioridad, a.origen, a.creadopor, a.solicitante, a.asignadoa, 
				o.nombre AS departamento, a.resolucion, a.satisfaccion, a.comentariosatisfaccion, a.estadoant,
				ifnull(a.fechacreacion, '') AS fechacreacion, a.horacreacion, ifnull(a.fecharesolucion, '') as fecharesolucion, 
				a.horaresolucion, a.horastrabajadas, cu.periodo, p.nombre as cliente, a.fechadesdefueraservicio, a.fechafinfueraservicio, 
				a.fueraservicio, (CASE WHEN a.fechafinfueraservicio is null || a.fechafinfueraservicio = '' then (TIMESTAMPDIFF(DAY, a.fechadesdefueraservicio, CURRENT_DATE)) ELSE (TIMESTAMPDIFF(DAY, a.fechadesdefueraservicio, a.fechafinfueraservicio)) END) as diasfueraservicio,
				(SELECT CONCAT(ie.fechacambio, ' ', ie.horacambio) FROM incidentesestados ie WHERE a.id = ie.idincidentes AND ie.estadonuevo = 13 ORDER BY ie.id DESC LIMIT 1) AS estadoasignado,
				(SELECT CONCAT(iep.fechacambio, ' ', iep.horacambio) FROM incidentesestados iep WHERE a.id = iep.idincidentes AND iep.estadonuevo = 33 ORDER BY iep.id DESC LIMIT 1) AS estadoenproceso,
				(SELECT fecha FROM comentarios ci WHERE a.id = ci.idmodulo ORDER BY ci.id ASC LIMIT 1) AS primercomentario,
				(SELECT nombre FROM usuarios nu WHERE a.resueltopor = nu.correo ) AS resueltopor, a.atencion, a.idestados
				FROM incidentes a
				LEFT JOIN proyectos b ON a.idproyectos = b.id
				LEFT JOIN ambientes c ON a.idambientes = c.id
				LEFT JOIN estados e ON a.idestados = e.id
				LEFT JOIN categorias f ON a.idcategorias = f.id
				LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
				LEFT JOIN sla h ON a.idprioridades = h.id
				LEFT JOIN usuarios j ON a.solicitante = j.correo
				LEFT JOIN usuarios l ON a.asignadoa = l.correo
				LEFT JOIN activos m ON a.idactivos = m.id AND a.idambientes = m.idambientes
				LEFT JOIN empresas n ON a.idempresas = n.id
				LEFT JOIN departamentos o ON a.iddepartamentos = o.id
				LEFT JOIN clientes p ON a.idclientes = p.id
				LEFT JOIN marcas mar ON m.idmarcas = mar.id
				LEFT JOIN modelos r ON m.idmodelos = r.id
				LEFT JOIN cuatrimestres cu ON a.fecharesolucion BETWEEN cu.fechainicio AND cu.fechafin
				";
				
	$query  .= " WHERE a.tipo = 'incidentes' ";
	$query .= permisos('correctivos', '', $idusuario);
	/*
	if($nivel != 1 && $nivel != 2){
		$query .= " LEFT JOIN usuarios q ON find_in_set(c.id, q.idambientes) AND q.usuario = '$usuario' ";
	}
	$query  .= " WHERE a.tipo = 'incidentes' ";
	
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
		if($_SESSION['idambientes'] != ''){
			$idambientes = $_SESSION['idambientes'];
			$idambientes = explode(',',$idambientes);
			$idambientes = implode("','", $idambientes);
			$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.idambientes IN ('".$idambientes."') OR a.idclientes in ($idclientes) ) ";
			//$query  .= " OR (j.usuario = '".$_SESSION['usuario']."' OR a.idambientes IN ('".$sitio."') ) ";
		}else{
			//$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
			if($_SESSION['iddepartamentos'] != ''){
				$iddepartamentosSES = $_SESSION['iddepartamentos'];
				$query  .= "AND a.iddepartamentos IN ('".$iddepartamentosSES."')  ";
			}
		}
	}
	*/
	//FILTROS MASIVOS
	$where = "";
	$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Incidentes' AND usuario = '".$_SESSION['usuario']."'";
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
			$where .= " AND a.fechacreacion >= $desdef ";
		}
		if(!empty($data->hastaf)){
			$hastaf = json_encode($data->hastaf);
			$where .= " AND a.fechacreacion <= $hastaf ";
		}
		if(!empty($data->idempresasf)){
			$idempresasf = json_encode($data->idempresasf);
			if($idempresasf != '[""]'){
				$where .= " AND a.idempresas IN ($idempresasf)"; 
			}
		}
		if(!empty($data->idclientesf)){
			$idclientesf = json_encode($data->idclientesf);
			if($idclientesf != '[""]'){
				$where .= " AND a.idclientes IN ($idclientesf)"; 
			}
		}
		if(!empty($data->idproyectosf)){
			$idproyectosf = json_encode($data->idproyectosf);
			if($idproyectosf != '[""]'){
				$where .= " AND a.idproyectos IN ($idproyectosf)"; 
			}
		}
		if(!empty($data->categoriaf)){
			$categoriaf = json_encode($data->categoriaf);
			if($categoriaf != '[""]'){
				$where .= " AND a.idcategorias IN ($categoriaf)";
			}
		}
		if(!empty($data->subcategoriaf)){
			$subcategoriaf = json_encode($data->subcategoriaf);
			if($subcategoriaf != '[""]'){
				$where .= " AND a.idsubcategorias IN ($subcategoriaf)";
			}
		}
		if(!empty($data->iddepartamentosf)){
			$iddepartamentosf = json_encode($data->iddepartamentosf);
			if($iddepartamentosf != '[""]'){
				$where .= " AND a.iddepartamentos IN ($iddepartamentosf)"; 
			}
		}
		if(!empty($data->prioridadf)){
			$prioridadf = json_encode($data->prioridadf);
			if($prioridadf != '[""]'){
				$where .= " AND a.idprioridades IN ($prioridadf)";
			}
		}
		if(!empty($data->modalidadf)){
			$modalidadf = json_encode($data->modalidadf);
			if($modalidadf != '[""]'){
				$where .= " AND m.modalidad IN ($modalidadf)";
			}
		}
		if(!empty($data->marcaf)){
			$marcaf = json_encode($data->marcaf);
			if($marcaf != '[""]'){
				$where .= " AND mar.id IN ($marcaf)"; 
			}
		}
		if(!empty($data->solicitantef)){
			$solicitantef = json_encode($data->solicitantef);
			if($solicitantef != '[""]'){
				$where .= " AND a.solicitante IN ($solicitantef)";
			}
		}
		if(!empty($data->estadof)){
			$estadof = json_encode($data->estadof);
			if($estadof != '[""]'){
				$where .= " AND a.idestados IN ($estadof)";
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
				$where .= " AND a.asignadoa IN ($asignadoaf)";	
			}
		}
		if(!empty($data->unidadejecutoraf)){
			$unidadejecutoraf = json_encode($data->unidadejecutoraf);
			 if($unidadejecutoraf !== '[""]'){ 
				$where .= " AND a.idambientes IN ($unidadejecutoraf)";
			}
		}
		$vowels = array("[", "]");
		$where = str_replace($vowels, "", $where);
	}
		
	//LocalStorage
	if($bid != ''){
		$where .= " AND a.id = $bid ";
	}
	if($bestado != ''){
		$where .= " AND e.nombre like '%".$bestado."%' ";
	}
	if($btitulo != ''){
		$where .= " AND a.titulo like '%".$btitulo."%' ";
	}
	if($bsolicitante != ''){
		$where .= " AND j.nombre like '%".$bsolicitante."%' ";
	}
	if($bcreacion != ''){
		$where .= " AND a.fechacreacion = '".$bcreacion."' ";
	}
	if($bhorac != ''){
		$where .= " AND a.horacreacion = '".$bhorac."' ";
	}		
	if($bempresa != ''){
		$where .= " AND n.descripcion like '%".$bempresa."%' ";
	}
	if($bdepartamento != ''){
		$where .= " AND o.nombre like '%".$bdepartamento."%' ";
	}
	if($bcliente != ''){
		$where .= " AND p.nombre like '%".$bcliente."%' ";
	}		
	if($bproyecto != ''){
		$where .= " AND b.nombre like '%".$bproyecto."%' ";
	}
	if($bcategoria != ''){
		$where .= " AND f.nombre like '%".$bcategoria."%' ";
	}
	if($bsubcategoria != ''){
		$where .= " AND g.nombre like '%".$bsubcategoria."%' ";
	}		
	if($basignadoa != ''){
		$where .= " AND l.nombre like '%".$basignadoa."%' ";
	}
	if($bsitio != ''){
		$where .= " AND c.nombre like '%".$bsitio."%' ";
	}
	if($bmodalidad != ''){
		$where .= " AND m.modalidad like '%".$bmodalidad."%' ";
	}		
	if($bserie != ''){
		$where .= " AND a.idactivos like '%".$bserie."%' ";
	}
	if($bmarca != ''){
		$where .= " AND mar.nombre like '%".$bmarca."%' ";
	}
	if($bmodelo != ''){
		$where .= " AND r.nombre like '%".$bmodelo."%' ";
	}
	if($bprioridad != ''){
		$where .= " AND h.prioridad like '%".$bprioridad."%' ";
	}
	if($bcierre != ''){
		$where .= " AND a.fechacierre = '".$bcierre."' ";
	}
	$query  .= " $where ORDER BY a.id DESC ";
	//$query  .= " LIMIT 10 ";
	debug('INCIDENTES - REPORTE:'.$query);
	
	function sumarHoras($horas) {
		$total = 0;
		foreach($horas as $h) {
			$parts = explode(":", $h);
			$total += $parts[2] + $parts[1]*60 + $parts[0]*3600;        
		}
		return gmdate("H:i:s", $total);
	}
	
	$result = $mysqli->query($query);
	$i = 5;
	while($row = $result->fetch_assoc()){
		$fcreacion 	= date_create($row['fechacreacion'].' '.$row['horacreacion']);
		$fecharesolucion = date_create($row['fecharesolucion'].' '.$row['horaresolucion']); 
		if($fecharesolucion == ''){
			if($hastaf != ""){
				$fecharesolucion = $hastaf;
			}else{
				$fecharesolucion = date('Y-m-d');
			}
		}
		$interval   = date_diff($fcreacion, $fecharesolucion);
		$dias       = $interval->format('%d');
		$horas      = $interval->format('%h');
		$minut      = $interval->format('%m');
		$minutf     = str_pad($minut, 2, "0", STR_PAD_LEFT);
		$diashras   = $dias*24;
		$horasfin   = $diashras + $horas; 
		$horasfinf  = str_pad($horasfin, 2, "0", STR_PAD_LEFT);
		$dif 	    = $horasfinf.":".$minutf;
		$horarioini = "08:00:00";
		$horariofin = "17:00:00";		
		//$dif = $interval->format('%d d %h h');
		//$dif = $interval->format('%hh:%mm');
		
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
			$spreadsheet->getActiveSheet()->getStyle('A'.$i.':AM'.$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b2cbea');
		}
		//Columna atención
		$atencion = $row['atencion'];
		if($atencion=='ensitio'){
			$atencion = "En Sitio";
		}elseif($atencion=='remoto'){
			$atencion = 'Remoto';
		}else{
			$atencion = "";
		}
		
		/*---------------------------- SLA-----------------------------------*/  
		//Variables
		$clientesla      = $row['cliente'];
		$proyectosla     = $row['proyecto'];
		$estadosla       = $row['idestados'];
		$prioridadsla    = $row['idprioridades'];
		$niveldeservicio = $row['niveldeservicio']; 
		$sla 	     	 = "0";
		$festivos        = '';
		$year            = date("Y"); 
		$rangos          = array(); 
		$fechaini = '';
		$horainic = '';
		$fechafin = '';
		$horafin = ''; 
		$comenzarrango 	= 1;
		
		//Dias Festivos Panamá
		$queryFestivos = " SELECT dia FROM diasfestivos ";
		$resultFestivos= $mysqli->query($queryFestivos);
		while($rowFestivos = $resultFestivos->fetch_assoc()){
			$festivos .= ''.$year.'-'.$rowFestivos['dia'].',';  
		}
		$festivos = explode(',', $festivos);
		$festivos = array_filter($festivos); 
		/*
		debugL('-------INICIA PROCESO------------ID:'.$row['id']);
		//Verificar Estados en Espera
		$queryHistEstados = "  SELECT id, estadoanterior, estadonuevo, fechacambio, horacambio
								FROM incidentesestados WHERE idincidentes = ".$row['id']." ORDER BY id ASC ";
		$resultHistEstados = $mysqli->query($queryHistEstados);
		//Si tiene registros en IncidentesEstados
		if($resultHistEstados->num_rows >0){
			while($rowHistE = $resultHistEstados->fetch_assoc()){
				$estadoanterior = $rowHistE['estadoanterior'];
				$estadonuevo 	= $rowHistE['estadonuevo'];
				$fechacambio    = $rowHistE['fechacambio'];
				$horacambio     = $rowHistE['horacambio'];				
				$ide     		= $rowHistE['id'];	
				 
				if($estadonuevo == 14 || $estadonuevo == 18 || $estadonuevo == 42){ //14: A la Espera del Cliente, 18: Reporte Pendiente, 42: En Espera					 
					//debugL('1comenzarrango:'.$comenzarrango); 
					$rangos[] = array(
									'fechainicial' 	=> $fechaini,
									'horainicial' 	=> $horainic,
									'fechafinal' 	=> $fechacambio,
									'horafinal' 	=> $horacambio
								);
					$comenzarrango 	= 1;   
				}else{ 
					if($comenzarrango == 1){
						//debugL('2comenzarrango:'.$comenzarrango); 
						$fechaini = $fechacambio;
						$horainic  = $horacambio;
						if($estadonuevo == 16){ //16: Resuelto
							$fechafin = $fechacambio;
							$horafin = $horacambio;
						}else{
							if($hastaf != ""){
								$fechafin = $hastaf; 
							}else{ 
								$fechafin = date("Y-m-d"); 
							}
							if($hastaf != ""){
								$horafin = $horariofin; 
							}else{ 
								$horafin = date("H:m:s");
							}							 
						}	 
					}else{
						//debugL('3comenzarrango:'.$comenzarrango); 
						$fechafin = $fechacambio;
						$horafin = $horacambio; 
					}
					$comenzarrango++;
				}		
				
			} 
			if(empty($rangos)) {
				//debugL('if empty rangos: '.json_encode($rangos));
				$rangos[] = array(
								'fechainicial' 	=> $fechaini,
								'horainicial' 	=> $horainic,
								'fechafinal' 	=> $fechafin,
								'horafinal' 	=> $horafin
							);
			}else{ 
				if($comenzarrango != 1 && $estadonuevo != 16){ //16: Resuelto
					$rangos[] = array(
									'fechainicial' 	=> $fechaini,
									'horainicial' 	=> $horainic,
									'fechafinal' 	=> $fechafin,
									'horafinal' 	=> $horafin
								); 
				} 
			} 
			
			//OBTENER DÍAS LABORABLES POR RANGOS
			$horasentiempo 	 = 0;
			$minutosentiempo = 0;
			$numr = count($rangos);
			$ri = 1; 
			debugL('rangos:'.json_encode($rangos)); 
			foreach ($rangos as $clave => $rango){  
				//Si es el último ciclo
				if($numr == $ri){ 
					//Si estado no es resuelto ni estados en espera
					 if($estadonuevo != 16 && $estadonuevo != 14 && $estadonuevo != 18 && $estadonuevo != 42){
						 //Si no está almacenada en incidentesestados la fecha resolución
						if($estadosla == 16 && $estadosla != $estadonuevo){
							if($row['fecharesolucion'] != ''){
								$fechafinal = $row['fecharesolucion'];
							}else{
								$fechafinal = $rango['fechafinal'];
							}
							if($row['horaresolucion'] != ''){
								$horafinal = $row['horaresolucion'];
							}else{
								$horafinal = $rango['horafinal'];
							} 
							debugL('DIAS 2');
							$hoyeslab = obtenerDiasLaborales($fechainicio,$fechafinal,$festivos); 
							//Sí último día es laborable, se restan horas correspondientes
							if($hoyeslab > 0){
								$restar = 1;
							}else{
								$restar = 0;
							}
							//debugL('PASO A');
						}else{
							//Si está activo filtro por fechas
							if($hastaf != ""){ 
								if($estadonuevo != 16 && $estadonuevo != 14 && $estadonuevo != 18 && $estadonuevo != 42){
									$fechafinal = $hastaf;
									$horafinal = $horariofin; 
								}else{
									if($hastaf <= $rango['fechafinal']){
										$fechafinal = $hastaf;
										$horafinal = $horariofin;
										debugL('ACA 1');
									}else{
										$fechafinal = $rango['fechafinal'];
										$horafinal = $rango['horafinal']; 
										debugL('ACA 2');
									} 
								} 
							}else{
								debugL('ACA 3');
								$fechafinal = date("Y-m-d"); 
								$horafinal = date("H:i:s");
							} 
							$hoyeslab = obtenerDiasLaborales($fechafinal,$fechafinal,$festivos); 
							debugL('hoyeslab: '.$hoyeslab.', fechafinal: '.$fechafinal);
							//Sí último día es laborable, se restan horas correspondientes
							if($hoyeslab>0){
								$restar = 1;
							}else{
								$restar = 0;
							} 
						}
					 }else{
						 debugL('RANGO FECHA INICIAL:'.$rango['fechainicial']);
						 debugL('HASTAF ES :'.$hastaf);
						 debugL('HORAINI ES :'.$rango['horainicial']);
						 debugL('HORAFIN ES :'.$rango['horafinal']);
						 //Si estados son estados en espera 
						 if($hastaf != ""){ 
								$hastaf = str_replace('"','', $hastaf);
								
								//Si fecha final es mayor que fechahasta
								if($hastaf <= $rango['fechafinal']){ 
									//Si fecha inicial es mayor que fecha hasta
									if($rango['fechainicial'] >= $hastaf){
										//Toma la fecha del filtro
										$rango['fechainicial'] = $hastaf;
										$fechafinal 		   = $hastaf;
										//Si es mayor, coloco que por defecto tome el horario de inicio
										//en ambas para que no las cuente
										if($rango['fechainicial'] >$hastaf){
											
											$rango['horainicial']  = $horarioini;
											$horafinal 			   = $horarioini; 
											debugL('ACA 4');
											
										}elseif($rango['fechainicial'] == $hastaf){
											//Si son iguales, coloco la fecha de la base de datos
											$rango['horainicial']  = $rango['horainicial'];
											$horafinal 			   = $rango['horafinal']; 
											debugL('ACA 5');
										} 
									}else{
										//Si la fecha del filtro es menor, toma las horas del día filtrado
										$fechafinal = $hastaf;
										$horafinal = $horariofin;
										debugL('ACA 6');
									}									
								}else{
									$fechafinal = $rango['fechafinal'];
									$horafinal = $rango['horafinal']; 
									debugL('ACA 7');
								}  
						}else{
							debugL('ACA 8');
							$fechafinal = $rango['fechafinal']; 
							$horafinal = $rango['horafinal'];
						}
					 }
					 
				}else{  
					$fechafinal = $rango['fechafinal'];
					$horafinal = $rango['horafinal']; 
					debugL('ACA 9'); 
				} 
				if($rango['fechainicial']==""){
					$rango['fechainicial'] = $row['fechacreacion'];
				}
				if($rango['horainicial']==""){
					$rango['horainicial'] = $row['horacreacion'];
				} 
				$posini = strpos($rango['fechainicial'],'"'); 
				$posfin = strpos($fechafinal,'"'); 
				if ($posini === false) {
					$rango['fechainicial'] = $rango['fechainicial'];
				} else {
					$rango['fechainicial'] = str_replace('"','', $rango['fechainicial']);
				}
				if ($posfin === false) {
					$fechafinal = $fechafinal;
				} else {
					$fechafinal = str_replace('"','', $fechafinal);
				}
				debugL('acá: fechainicial:'.$rango['fechainicial'].'-fechafinal:'.$fechafinal); 
				$diaslaborales = obtenerDiasLaborales($rango['fechainicial'],$fechafinal,$festivos); 
				debugL('Días laborables:'.$diaslaborales); 
				$tiempo = obtenerHorasSla($diaslaborales,$rango['horainicial'],$horafinal,$restar,$estadonuevo,$hastaf);
				debugL('obtenerHorasSla:'.$diaslaborales.', '.$rango['horainicial'].', '.$horafinal.', '.$restar.', '.$estadonuevo.', '.$hastaf); 
				foreach ($tiempo as $clave => $valor){
					$horas = $valor['horas'];
					$minutos = $valor['minutos'];
					debugL('horas:'.$horas.', minutos:'.$minutos);
					//debugL('minutos:'.$minutos);
				} 
				$minutosentiempo += $minutos;
				$horasentiempo += $horas;  
				//debugL('horasentiempo+=:'.$horasentiempo);
				//debugL('minutosentiempo+=:'.$minutosentiempo);
				$ri++;
			}  
			//debugL('horasentiempo:'.$horasentiempo);
			//debugL('minutosentiempo:'.$minutosentiempo);
			
			$horasentiempo   = str_pad($horasentiempo, 2, "0", STR_PAD_LEFT);	
			$minutosentiempo = str_pad($minutosentiempo, 2, "0", STR_PAD_LEFT);
		}else{
			$estadonuevo = "";
			if($row['fecharesolucion'] != '' && $row['idestados'] == 16){
				$fechafinal = $row['fecharesolucion'];
				//debugL('paso caso a:'.$fechafinal);
			}else{
				if($hastaf != ""){
					$fechafinal = $hastaf;
				}else{
					$fechafinal = date('Y-m-d');
				}
				//debugL('paso caso b:'.$fechafinal);
			}
			if($row['horaresolucion'] != '' && $row['idestados'] == 16){
				$horafinal = $row['horaresolucion'];
			}else{
				 if($hastaf != ""){
					$horafinal = $horariofin; 
				 }else{
					$horafinal = date('H:i:s');
				 }
			} 
			//debugL('pasó caso2');
			//if($row['id']==22584){
				debugL('fechacreacion:'.$row['fechacreacion'].'-horacreacion:'.$row['horacreacion']);
				debugL('$fechafinal:'.$fechafinal.'-$horafinal:'.$horafinal);
			//}
			debugL('DIAS 1');
			$diaslaborales = obtenerDiasLaborales($row['fechacreacion'],$fechafinal,$festivos); 
			$tiempo        = obtenerHorasSla($diaslaborales,$row['horacreacion'],$horafinal,$restar,$estadonuevo,$hastaf);
			foreach ($tiempo as $clave => $valor){
				$horas   = $valor['horas'];
				$minutos = $valor['minutos'];
				//if($row['id']==22584){
					debugL('horas:'.$horas);
					debugL('minutos:'.$minutos);
				//} 
			} 
			$minutosentiempo = $minutos;
			$horasentiempo   = $horas;
		} 
		//if($row['id']==22584){
			debugL('horasentiempo:'.$horasentiempo);
			debugL('minutosentiempo:'.$minutosentiempo);
		//}
		*/
		//Variables
		$hoy 			= date('Y-m-d');
		$horarioinicio  = '08:00:00';
		$horariocierre  = '17:00:00';
		$fechatope = str_replace('"','',$hastaf);
		$esMayorHorasA  = 0;
		$esMayorHorasB  = 0;
		$verHoraInicio  = new DateTime($horarioinicio);
		$verHoraCierre  = new DateTime($horariocierre);
		
		debugL('INCIDENTE: '.$row['id']); 
		if($row['fechacreacion'] == $row['fecharesolucion']){
		    $horacreacion = new DateTime($row['horacreacion']);
			$fecharesolucion = new DateTime($row['horaresolucion']);
			$horaDiff = $fecharesolucion->diff($horacreacion);
			$horasFinales = $horaDiff->format('%H:%I:%S'); 
			debugL('111111');
		}elseif(($row['fechacreacion'] == $fechatope) && $row['fecharesolucion'] == ''){
		    $horariocierre = new DateTime($horariocierre);
			$horacreacion = new DateTime($row['horacreacion']);
			$horaDiff = $horariocierre->diff($horacreacion);
			$horasFinales = $horaDiff->format('%H:%I:%S'); 
			debugL('222222');
		}else{
		    debugL('333333');
		    if($row['fecharesolucion'] == ''){ 
    			if($hastaf != ''){
    				$fechatope = str_replace('"','',$hastaf);
    			}else{
    				$fechatope = $hoy;
    			} 
    			if($fechatope != $hoy){
    				$horatope  = $horariocierre;
    			}else{
    				$horatope  = $hoy;
    			}
    		}else{
    			$fechatope = $row['fecharesolucion'];
    			$horatope = $row['horaresolucion'];
    		}
    		debugL('INCIDENTE: '.$row['id']);
    		//SI LA HORA DE CREACION ES MAYOR A LA HORA DE INICIO DEL HORARIO LABORAL, RESTO ESAS HORAS
    		if($row['horacreacion'] > $horarioinicio){
    		    $horacreacion = new DateTime($row['horacreacion']);
    			$horarioinicio = new DateTime($horarioinicio); 
    			//debugL($row['horacreacion'].', '.$horarioinicio);
    			$horaDiff = $horarioinicio->diff($horacreacion); 
    			$horaA = $horaDiff->format('%H:%I:%S');
				
				$totalVerHoraCierre = $horacreacion->diff($verHoraCierre);
    			$horaCierreRestar = $totalVerHoraCierre->format('%H:%I:%S'); 
				$sumaHorasA = [$horaA, $horaCierreRestar];
				$resultadoSumaHorasA =  sumarHoras($sumaHorasA);
				
				if($horaA > '08:00:00' && $row['horacreacion'] < '17:00:00'){
					$esMayorHorasA = 1;
					debugL('PASO X');
				}else{
					debugL('PASO Y');
					debugL('horaA:'.$horaA);
					debugL('$row[horacreacion]:'.$row['horacreacion']);
					debugL('$horariocierre:'.$$horariocierre);
				}
				
    			//$horaA = $row['horacreacion'] - $horarioinicio;
    			debugL('horaA 1: '.$horaA);
    			debugL('resultadoSumaHorasA: '.$resultadoSumaHorasA);
    			debugL('horaCierreRestar: '.$horaCierreRestar);
    		}else{
    			$horacreacion = new DateTime($row['horacreacion']);
    			$horarioinicio = new DateTime($horarioinicio);
    			$horaDiff = $horacreacion->diff($horarioinicio);
    			$horaA = $horaDiff->format('%H:%I:%S');
    			debugL('horaA 2: '.$horaA);
				
				$totalVerHoraCierre = $horacreacion->diff($verHoraCierre);
    			$horaCierreRestar = $totalVerHoraCierre->format('%H:%I:%S'); 
				$sumaHorasA = [$horaA, $horaCierreRestar];
				$resultadoSumaHorasA =  sumarHoras($sumaHorasA);
				
				if($horaA > '08:00:00' && $row['horacreacion'] < '17:00:00'){
					$esMayorHorasA = 1;
					debugL('PASO Z'); 
				}else{
					debugL('PASO W');
				}
    		}
    		//SI LA HORA FIN(RESOLUCIÓN O HORA ACTUAL EN CASO DE QUE SE TENGA FILTRO HASTA) RESTO ESAS HORAS 
    		//debugL($row['horaresolucion'].', horaActual: '.$horaActual.', horariocierre: '.$horariocierre);
    		if($row['horaresolucion'] != ''){			
    			if($row['horaresolucion'] < $horariocierre){
    				$horaresolucion = new DateTime($row['horaresolucion']);
    				$horariocierre = new DateTime($horariocierre);
    				$horaDiff = $horariocierre->diff($horaresolucion);
    				$horaB = $horaDiff->format('%H:%I:%S');
    				//$horaB = $row['horacreacion'] - $horariocierre;
    				debugL('PASO 1');
					
					$totalVerHoraInicio = $verHoraInicio->diff($horaresolucion);
					$horaInicioRestar = $totalVerHoraInicio->format('%H:%I:%S'); 
					$sumaHorasB = [$horaB, $horaInicioRestar];
					$resultadoSumaHorasB =  sumarHoras($sumaHorasB);
					
					if($horaB > '08:00:00' && $row['horaresolucion'] < '17:00:00'){
						$esMayorHorasB = 1;
						debugL('PASO R'); 
					}else{
						debugL('PASO S');
					}
				
    			}else{
					$horaB = '00:00:00';
    				/* $horaresolucion = new DateTime($row['horaresolucion']);
    				$horariocierre = new DateTime($horariocierre);
    				$horaDiff = $horaresolucion->diff($horariocierre);
    				$horaB = $horaDiff->format('%H:%I:%S'); */
    				debugL('PASO 2');
    			}
    		}else{
    		    //debugL('fechacreacion:'.$row['fechacreacion'].', $hastaf:'.$hastaf);
    		    $fechatope = str_replace('"','',$hastaf);
    		    if($row['fechacreacion'] == $fechatope){
    		        debugL('PASO 3.0: $horariocierre:'.$horariocierre.'-$horacreacion:'.$row['horacreacion']);
    		        $horariocierre = new DateTime($horariocierre);
    				$horacreacion = new DateTime($row['horacreacion']);
    				$horaDiff = $horariocierre->diff($horacreacion);
    				$horaB = $horaDiff->format('%H:%I:%S'); 
					 
    		    }else{
    		        $horaActual = date('H:m:s');
        			debugL('PASO 3: HORAACTUAL:'.$horaActual.'-HORACIERRE:'.$horariocierre);
        			if($horaActual < $horariocierre && $horaActual > $horariocierre){ 
        				$horaActual = new DateTime($horaActual);
        				$horariocierre = new DateTime($horariocierre);
        				$horaDiff = $horariocierre->diff($horaActual);
        				$horaB = $horaDiff->format('%H:%I:%S');
        				//$horaB = $horariocierre - $horaActual; 
						$totalVerHoraInicio = $verHoraInicio->diff($row['horaresolucion']);
						$horaInicioRestar = $totalVerHoraInicio->format('%H:%I:%S'); 
						$sumaHorasB = [$horaB, $horaInicioRestar];
						$resultadoSumaHorasB =  sumarHoras($sumaHorasB);
						
						if($resultadoSumaHorasB > 8){
							$esMayorHorasB = 1;
						} 
        			}else{
						$horaB = '00:00:00';
					}
    		    }
    		}
    		debugL('horaB: '.$horaB);
    		
    		//Calculo días laborales
    		$diaslab = obtenerDiasLaborales($row['fechacreacion'],$fechatope,$festivos);
    		$horaslab = ($diaslab * 8).':00:00';
    		debugL('$horaslab: '.$horaslab);
    		debugL('$$horaA: '.$horaA);
    		debugL('$$horaB: '.$horaB);
    		
			//RESTAMOS A LAS HORAS TOTALES LAS HORAS DE LOS EXTREMOS
			$horasbd = [$horaA, $horaB]; 
			$horasSUM =  sumarHoras($horasbd);
			
			//RESTAR 
			$horaslabP = explode(":", $horaslab);
			$horasSUMP = explode(":", $horasSUM);
			//$horasres = abs($horaslabP[0]) - abs($horasSUMP[0]);
			if($esMayorHorasA == 1){
				$restaA = 1;
			}else{
				$restaA = 0;
			}
			if($esMayorHorasB == 1){
				$restaB = 1;
			}else{
				$restaB = 0;
			} 
			debugL('$horasSUM: '.$horasSUM);
			debugL('$horasSUMP[0] '.$horasSUMP[0]);
			$horasres = $horaslabP[0] - $horasSUMP[0] + $restaA + $restaB;
			//$horasres = $horaslabP[0] - $horasSUMP[0];
			debugL('horaslabP'.$horaslabP[0].'-horasSUMP:'.$horasSUMP[0].'-restaA:'.$restaA.'-restaB:'.$restaB);
			  
			$horasFinales = $horasres.':'.$horasSUMP[1].':'.$horasSUMP[2];
			debugL('$horaslab: '.$horaslab.', $horasSUM: '.$horasSUM.', $horasFinales: '.$horasFinales);
		}
		
		debugL('$horasFinales: '.$horasFinales);
		
		//ESTADO: 14 - A la Espera del Cliente
		/* $queryHistEstados = "   SELECT id, fechadesde, horadesde, fechahasta, horahasta
								FROM incidentesestados2 WHERE idincidentes = ".$row['id']." AND estadonuevo = 14 ";
		$result = $mysqli->query($queryHistEstados);
		while($rowH = $result->fetch_assoc()){
			$diaslabest = 0;
			if($rowH['fechahasta'] != ''){ 
				$diaslabest = obtenerDiasLaborales($rowH['fechadesde'],$rowH['fechahasta'],$festivos);
			}else{
				if($hastaf != ''){
					$fechatope = str_replace('"','',$hastaf);
				}else{
					$fechatope = date('Y-m-d');
				} 
				$diaslabest = obtenerDiasLaborales($rowH['fechadesde'],$fechatope,$festivos);
			}
			$horaslabest = ($diaslabest * 8).':00:00';
			debugL('HORASESTADO-ALAESPERADELCLIENTE:'.$horaslabest);
		} */ 
		 
		$horasentiempo   = str_pad($horasTotal, 2, "0", STR_PAD_LEFT);	
		$minutosentiempo = str_pad($minutosTotal, 2, "0", STR_PAD_LEFT);	
		
		debugL('$horasentiempoantes:'.$horasentiempo);
		if( $row['fueraservicio']== 1 ){
			$fueraservicio = 'Si';
		}else{
			$fueraservicio = 'No';
		} 
		//Si está fuera de servicio se restan 10 días del tiempo de servicio
		if($row['fueraservicio']== 1){
			$horasFinales = $horasFinales - 80;
		}  
		$numeroreq = str_pad($row['id'], 4, "0", STR_PAD_LEFT);		
		$spreadsheet->getActiveSheet()
		->setCellValue('A'.$i, $numeroreq)
		->setCellValue('B'.$i, $row['titulo'])
		->setCellValue('C'.$i, $row['descripcion'])
		->setCellValue('D'.$i, $row['cliente'])
		->setCellValue('E'.$i, $row['proyecto'])
		->setCellValue('F'.$i, $row['estado'])
		->setCellValue('G'.$i, $row['equipo'])
		->setCellValue('H'.$i, $row['serie'])
		->setCellValue('I'.$i, $row['activo'])
		->setCellValue('J'.$i, $row['marca'])
		->setCellValue('K'.$i, $row['modelo'])
		->setCellValue('L'.$i, $row['modalidad'])
		->setCellValue('M'.$i, $row['estadoequipo'])
		->setCellValue('N'.$i, $row['categoria'])
		->setCellValue('O'.$i, $row['subcategoria'])			
		->setCellValue('P'.$i, $row['sitio'])
		->setCellValue('Q'.$i, $row['prioridad'])
		->setCellValue('R'.$i, $row['origen'])
		->setCellValue('S'.$i, $row['creadopor'])
		->setCellValue('T'.$i, $row['solicitante'])
		->setCellValue('U'.$i, $asignadoaN)
		->setCellValue('V'.$i, $row['departamento'])
		->setCellValue('W'.$i, $row['resueltopor'])
		->setCellValue('X'.$i, $row['resolucion'])
		->setCellValue('Y'.$i, $row['satisfaccion'])
		->setCellValue('Z'.$i, $row['comentariosatisfaccion'])
		->setCellValue('AA'.$i, $xfechacreacion.' '.$row['horacreacion'])
		->setCellValue('AB'.$i, $fprimercomentario)
		->setCellValue('AC'.$i, $festadoasignado)
		->setCellValue('AD'.$i, $festadoenproceso)
		->setCellValue('AE'.$i, $xfecharesolucion.' '.$row['horaresolucion'])
		//->setCellValue('AF'.$i, $dif)
		->setCellValue('AF'.$i, $horasFinales)
		->setCellValue('AG'.$i, $row['horastrabajadas'])
		->setCellValue('AH'.$i, $row['periodo'])
		->setCellValue('AI'.$i, $fueraservicio)
		->setCellValue('AJ'.$i, $row['fechadesdefueraservicio'])
		->setCellValue('AK'.$i, $row['fechafinfueraservicio'])
		->setCellValue('AL'.$i, $row['diasfueraservicio'])
		->setCellValue('AM'.$i, $atencion);		

		//ESTILOS
		$spreadsheet->getActiveSheet()->getStyle('A'.$i.':AJ'.$i)->getFont()->setSize(10);
		$spreadsheet->getActiveSheet()->getStyle('A'.$i.':AJ'.$i)->getAlignment()->applyFromArray(
					array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
		$spreadsheet->getActiveSheet()->getStyle('Z'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
		$spreadsheet->getActiveSheet()->getStyle('Z'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
		//$spreadsheet->getActiveSheet()->getStyle('AF'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
		$spreadsheet->getActiveSheet()->getStyle('AF'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
		$spreadsheet->getActiveSheet()->getStyle('AB'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
		$spreadsheet->getActiveSheet()->getStyle('AB'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
		$spreadsheet->getActiveSheet()->getStyle('AD'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
		$spreadsheet->getActiveSheet()->getStyle('AD'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
		$spreadsheet->getActiveSheet()->getStyle('AH'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
												
		$i++;
	}
	
	//Ancho automatico	
	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(60);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
	$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(25);
	$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(25);
	$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(25);
	$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth(60);
	$spreadsheet->getActiveSheet()->getColumnDimension('X')->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension('Y')->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension('Z')->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension('AA')->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension('AB')->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AF')->setWidth(18);
	$spreadsheet->getActiveSheet()->getColumnDimension('AG')->setWidth(24);
	$spreadsheet->getActiveSheet()->getColumnDimension('AH')->setWidth(24);
	$spreadsheet->getActiveSheet()->getColumnDimension('AI')->setWidth(18);
	$spreadsheet->getActiveSheet()->getColumnDimension('AJ')->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
	$hoy = date('dmY');
	$nombreArc = 'Correctivos - '.$hoy.'.xlsx';
	// redirect output to client browser
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Type: application/vnd.ms-excel'); //xls
	header('Content-Disposition: attachment;filename='.$nombreArc);
	header('Cache-Control: max-age=0');	
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');
	
	//FUNCIONES
	function obtenerHorasSla($diaslaborales,$horainicio,$horafin,$restar,$estadonuevo,$hastaf){
		$tiempo = array();
		if($diaslaborales >= 1){
			debugL('horainicio:'.$horainicio);
			debugL('horafin:'.$horafin);
			debugL('restar:'.$restar);
			debugL('estadonuevo:'.$estadonuevo);
			debugL('diaslaborales:'.$diaslaborales);
			
			//Horario de oficina
			$horarioinicio 	= new DateTime('08:00:00');
			$horariofinal 	= new DateTime('17:00:00');
			
			//Resto dos días para calcular las horas inicial / final
			if($diaslaborales>2){
				$diasm = $diaslaborales - 2;
			}else{
				$diasm = 0;
			}
			$horasm    = $diasm * 8;
			
			debugL('$diasm:'.$diasm);
			debugL('$horasm:'.$horasm); 
			
			//RESTA DE LA HORA DE CREACIÓN - EL HORARIO DE CIERRE : /* $horasfinI */
			$hinicia		= explode(':',$horainicio);
			$horainicio 	= new DateTime($horainicio); 
			
			//Si hora de inicio es mayor al horario de salida
			if($hinicia[0] >= 17){ 
				$horasfinI  = 0; 
				$minfinI    = 0;
				$horasfinSM = 0;
				debugL('PASO 1');
			}else{ 
				if($hinicia[0] < 8){
					//Si hora de inicio es menor al horario de inicio
					$horainicio = $horarioinicio;
					debugL('PASO 2');
				}else{
					$horainicio = $horainicio;
					debugL('PASO 3');
				}
				//Ejecuto la resta
				$horasfinSF	= $horariofinal->diff($horainicio);
				$horasfinI 	= $horasfinSF->format("%h");
				$minfinI 	= $horasfinSF->format("%i");
				debugL('Ejecuto la resta: '.$horasfinI.'-'.$minfinI);
			}  
			
			//RESTA DE LA HORA DE FINALIZACIÓN - EL HORARIO DE INICIO: /* $horasfinF */	 
			$hcierre    = explode(':',$horafin); 
			//Si hora de finalización es mayor al horario de salida 
			if($hcierre[0] >= 17){ 
				$horafinal = '17:00:00'; 
				$horasfinSM = 0;
				debugL('PASO 4');
			}else{
				if($hcierre[0] < 8){
					//Si hora de finalización es menor al horario de inicio
					$horafinal = '17:00:00';
					debugL('PASO 5');
				}else{
					$horafinal = $horafin;
					debugL('PASO 6');
				} 
			}
			
			debugL('horafinal:'.$horafinal);
			//Ejecuto la resta		
			$horafinal 	= new DateTime($horafinal);
			$horasfinFSF= $horafinal->diff($horarioinicio);
			$horasfinF 	= $horasfinFSF->format("%h");
			$minfinF 	= $horasfinFSF->format("%i"); 
			
			debugL('HASTA: '.$hastaf);
			
			//Si está activo el filtro de fecha, no resto horas
			if($hastaf != ""){
				if($estadonuevo != 16 && $estadonuevo != 14 && $estadonuevo != 18 && $estadonuevo != 42){
					$horasfinF = 8;
					$minfinF   = 0;
					debugL('ACTIVO FILTRO - NO ESTADOS - ESTADO NUEVO:'.$estadonuevo.'-HASTAF:'.$hastaf);
				}else{
					$horasfinF = $horasfinF;
					$minfinF   = $minfinF;  
					debugL('ACTIVO FILTRO - SI ESTADOS - ESTADO NUEVO:'.$estadonuevo.'-HASTAF:'.$hastaf);
				} 
			}else{
				$horasfinF = $horasfinF;
				$minfinF   = $minfinF;  
				debugL('NO ACTIVO FILTRO - SI ESTADOS - ESTADO NUEVO:'.$estadonuevo.'-HASTAF:'.$hastaf);
			}
			debugL('$minfinF:'.$minfinF);	
			debugL('horasfinF:'.$horasfinF);    
			
			//RESTA DE LA HORA DE INICIO - FINALIZACIÓN (1 día - mismo día ) /* horasSM */
			
			$horafin 	= new DateTime($horafin);
			//Si hora de finalización es mayor a hora de inicio
			
			if($horafin > $horainicio){
				//Si hora de finalización está en el rango de horas 8-17
				if($horafin >= $horarioinicio && $horafin <= $horariofinal){
					$horafin = $horafin;
					debugL('$horafin >= $horarioinicio && $horafin <= $horariofinal. ');
				}else{
					//Si es menor a HORARIO inicio es 08:00:00
					if($horafin < $horarioinicio){
						$horafin = $horarioinicio;
						debugL('$horafin < $horarioinicio');
					//Si es mayor a HORARIO final es 17:00:00
					}elseif($horafin > $horariofinal){
						$horafin = $horariofinal;
						debugL('$horafin > $horariofinal');
					}
				}
				//Si hora de inicio está en el rango de horas 8-17
				if($horainicio >= $horarioinicio && $horainicio <= $horariofinal){
					$horainicio = $horainicio;
					debugL('$horainicio >= $horarioinicio && $horainicio <= $horariofinal');
				}else{
					//Si es menor a HORARIO inicio es 08:00:00
					if($horainicio < $horarioinicio){
						$horainicio = $horarioinicio;
						debugL('$horainicio < $horarioinicio');
					//Si es mayor a HORARIO final es 17:00:00
					}elseif($horainicio > $horariofinal){
						$horainicio = $horariofinal;
						debugL('$horainicio > $horariofinal');
					}
				}  
				
				$horasfinSM = $horafin->diff($horainicio);
				$horasSM    = $horasfinSM->format("%h");
				$minSM      = $horasfinSM->format("%i"); 
				debugL('PASO HORAS horasSM:'.$horasSM);
				debugL('PASO HORAS minSM:'.$minSM);
				debugL('paso $horafin > $horainicio');
			}else{			
				$horasSM = $horasfinF;
				$minSM   = $minfinF;
				debugL('paso $horafin < $horainicio');
			} 
			
			//Si último día SÍ es laborable se restan las horas correspondientes
			if($restar == 1){
				$horasfinF = $horasfinF;
				debugL('paso z');
			}else{
				//Si último día NO es laborable y NO está resuelto, NO se restan horas.
				if($estadonuevo != 16 && $estadonuevo != 14 && $estadonuevo != 18 && $estadonuevo != 42){
					$horasfinF = 8;
					$minfinF   = 0;
					debugL('paso x');
				}else{
					//Si último día NO es laborable y SÍ está resuelto, SÍ se restan las horas correspondientes
					$horasfinF = $horasfinF;
					debugL('paso y');
				}
			} 
			//debugL('restar:'.$restar);
			//Si minutos totales es mayor a 60, le sumo hora adicional $horaA
			$minT  = $minfinI + $minfinF; 
			$minT  = abs($minT);
			if($minT>=60){
				$minT  = $minT-60;
				$horaA = 1;
			}else{
				$minT = $minT;
				$horaA = 0;
			} 
			debugL('horaA: '.$horaA);
			
			//Total horas dias laborales >= 2
			if($diasm != 0 || $diaslaborales>=2){
				$horas = $horasm + $horasfinI + $horasfinF + $horaA;
				$minT  = $minT;
				debugL('paso diasm != 0');
			}else{
				//Total horas dias laborales < 2 
				//Suma horas para la fecha seleccionada del filtro para estados diferentes a En Espera
				if($hastaf != ""){
					if($estadonuevo != 16 && $estadonuevo != 14 && $estadonuevo != 18 && $estadonuevo != 42){
						$horas = $horasSM + 8;
						$minT  = $minSM;
						debugL('Pasó hastaf - no estados:'.$horas); 
					}else{
						$horas = $horasSM;
						$minT  = $minSM;
						debugL('horasSM: '.$horas);
						debugL('Pasó hastaf - si estados:'.$horas);  
					}
				}else{
					$horas = $horasSM;
					$minT  = $minSM;
					debugL('horasSM: '.$horas);
					debugL('paso diasm 0');
				} 
			}
			
			debugL('$horasm: '.$horasm.', horas: '.$horas.', $horasfinI: '.$horasfinI.', $horasfinF: '.$horasfinF);
			 
			debugL('$horas:'.$horas.'-minT:'.$minT.'-minfinI:'.$minfinI.'-minfinF:'.$minfinF);
			$tiempo[] = array(
				'horas'   => $horas,
				'minutos' => $minT
			);
			debugL('tiempo desde funcion:'.json_encode($tiempo));
		}else{
			$tiempo[] = array(
				'horas'   => 0,
				'minutos' => 0
			);
		} 
		return $tiempo;   
	}
	
	function obtenerDiasLaborales($startDate,$endDate,$holidays){
		debugL('---FUNCION DIAS LAB-----');
		debugL('$startDate:'.$startDate);
		debugL('$endDate:'.$endDate);
		debugL('$holidays:'.json_encode($holidays));
		// do strtotime calculations just once
		$endDate = strtotime($endDate);
		$startDate = strtotime($startDate);
		 
		
		//The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
		//We add one to inlude both dates in the interval.
		$days = ($endDate - $startDate) / 86400 + 1;

		$no_full_weeks = floor($days / 7);
		$no_remaining_days = fmod($days, 7);

		//It will return 1 if it's Monday,.. ,7 for Sunday
		$the_first_day_of_week = date("N", $startDate);
		$the_last_day_of_week = date("N", $endDate);

		//---->The two can be equal in leap years when february has 29 days, the equal sign is added here
		//In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
		if ($the_first_day_of_week <= $the_last_day_of_week) {
			if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
			if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
		}
		else {
			// (edit by Tokes to fix an Edge case where the start day was a Sunday
			// and the end day was NOT a Saturday)

			// the day of the week for start is later than the day of the week for end
			if ($the_first_day_of_week == 7) {
				// if the start date is a Sunday, then we definitely subtract 1 day
				$no_remaining_days--;

				if ($the_last_day_of_week == 6) {
					// if the end date is a Saturday, then we subtract another day
					$no_remaining_days--;
				}
			}
			else {
				// the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
				// so we skip an entire weekend and subtract 2 days
				$no_remaining_days -= 2;
			}
		}

		//The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
	//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
	   $workingDays = $no_full_weeks * 5;
		if ($no_remaining_days > 0 )
		{
		  $workingDays += $no_remaining_days;
		}

		//We subtract the holidays
		foreach($holidays as $holiday){
			$time_stamp=strtotime($holiday);
			//If the holiday doesn't fall in weekend
			if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
				$workingDays--;
		}

	  //  echo 'ret:'.$workingDays;
	  debugL('$workingDays:'.$workingDays);
		debugL('---END FUNCION DIAS LAB-----');
		return $workingDays;
		
	} 
	
?>