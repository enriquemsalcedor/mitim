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
		debugL('insumarhoras:'.$horas);
		foreach($horas as $h) {
			debugL('sumarHoras - $horas: '.$h);
			$parts = explode(":", $h);
			debugL('parts:'.json_encode($parts));
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
		
		//Variables
		$hoy 			= date('Y-m-d');
		$ahora 			= date('H:i:s');
		$horarioinicio  = '08:00:00';
		$horariocierre  = '17:00:00';
		$fechatope = str_replace('"','',$hastaf);
		$esMayorHorasA  = 0;
		$esMayorHorasB  = 0;
		$verHoraInicio  = new DateTime($horarioinicio);
		$verHoraCierre  = new DateTime($horariocierre);
		debugL('----------------------------------------INICIO----------------------------------------------------'); 
		debugL('INCIDENTE: '.$row['id']); 
		if($row['fechacreacion'] == $row['fecharesolucion']){
			if($row['horaresolucion'] > $horariocierre){
				$row['horaresolucion'] = $horariocierre; 
			} 
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
    				$horatope  = $ahora;
    			}
    		}else{
				if($hastaf != ''){
					$fechatope = str_replace('"','',$hastaf);
					$horatope  = $horariocierre;
				}else{
					$fechatope = $row['fecharesolucion'];
					$horatope = $row['horaresolucion'];
				} 
    		}
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
					debugL('$row[horacreacion]:'.$row['horacreacion']);
					debugL('$horariocierre:'.$horariocierre);
				}
				 
    			debugL('resultadoSumaHorasA: '.$resultadoSumaHorasA);
    			debugL('horaCierreRestar: '.$horaCierreRestar);
    		}else{
    			$horacreacion = new DateTime($row['horacreacion']);
    			$horarioinicio = new DateTime($horarioinicio);
    			$horaDiff = $horacreacion->diff($horarioinicio);
    			$horaA = $horaDiff->format('%H:%I:%S'); 
				
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
				 if($row['fecharesolucion'] == ''){ 
					if($hastaf != ''){
						$fechatope = str_replace('"','',$hastaf);
					}else{
						$fechatope = $hoy;
					} 
					if($fechatope != $hoy){
						$horatope  = $horariocierre;
						debugL('horatope1:'.$horatope);
					}else{
						$horatope  = $ahora;
						debugL('horatope2:'.$horatope);
					}
				}else{
					if($hastaf != ''){
						if($row['fecharesolucion'] != $fechatope){
							$horatope  = $horariocierre;
							debugL('horatope3:'.$horatope);
						}else{
							$horatope = $row['horaresolucion'];
						}
					}else{ 
						$horatope = $row['horaresolucion'];
						debugL('horatope4:'.$horatope);
					} 
				} 
				debugL('Hora Tope S:'.$horatope);
    			if($row['horaresolucion'] < $horariocierre){
    				$horatope = new DateTime($horatope);
    				$horariocierre = new DateTime($horariocierre);
    				$horaDiff = $horariocierre->diff($horatope);
    				$horaB = $horaDiff->format('%H:%I:%S');
    				//$horaB = $row['horacreacion'] - $horariocierre;
    				debugL('PASO 1');
					
					$totalVerHoraInicio = $verHoraInicio->diff($horatope);
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
    		
    		//Calculo días laborales
    		$diaslab = obtenerDiasLaborales($row['fechacreacion'],$fechatope,$festivos);
    		$horaslab = ($diaslab * 8).':00:00';
    		debugL('$horaslab: '.$horaslab);
    		debugL('$$horaA: '.$horaA);
    		debugL('$$horaB: '.$horaB);
			
			//ESTADO: 14 - A la Espera del Cliente
			$queryHistEstados = "   SELECT id, fechadesde, horadesde, fechahasta, horahasta
									FROM incidentesestados2 WHERE idincidentes = ".$row['id']." AND estadonuevo = 14 ";
			if($hastaf != ''){
				$queryHistEstados .= " AND fechadesde <= $hastaf ";
			}
			debugL('queryHistEstados-ALAESPERADELCLIENTE:'.$queryHistEstados);
			$resultHE = $mysqli->query($queryHistEstados);
			$horasFinalesH = '00:00:00';
			if($resultHE->num_rows >0){
				debugL('PASÓ EXISTE ESTADO 14');
				while($row14 = $resultHE->fetch_assoc()){
					
					$diasLab14 = 0;
					$fechatope = str_replace('"','',$hastaf);
					//Si tiene filtros activos
					if($fechatope != ''){ 
						if($row14['fechahasta'] != ''){ 
							if($row14['fechahasta'] < $fechatope){
								debugL('UNO 14');
								$diasLab14 = obtenerDiasLaborales($row14['fechadesde'],$row14['fechahasta'],$festivos);
								//RESTAR FINAL
								if($row14['horahasta'] > $horariocierre){
									$horahasta = new DateTime($row14['horahasta']);
									$horariocierre = new DateTime($horariocierre);
									$horaDiff = $horahasta->diff($horariocierre);
									$horaB14 = $horaDiff->format('%H:%I:%S');
									debugL('A horaB14:'.$horaB14);
								}else{
									$horahasta = new DateTime($row14['horahasta']);
									$horariocierre = new DateTime("17:00:00");
									$horaDiff = $horariocierre->diff($horahasta);
									$horaB14 = $horaDiff->format('%H:%I:%S');
									debugL('B horaB14:'.$horaB14);
								}						
							}else{
								debugL('DOS 14');
								$diasLab14 = obtenerDiasLaborales($row14['fechadesde'],$fechatope,$festivos);
								$horaB14 = 0;
							}
						}
					}elseif($row14['fechahasta'] != ''){ 
						debugL('TRES 14');
						$diasLab14 = obtenerDiasLaborales($row14['fechadesde'],$row14['fechahasta'],$festivos);
						//RESTAR FINAL
						if($row14['horahasta'] > $horariocierre){ 
							$horahasta = new DateTime($row14['horahasta']);
							$horariocierre = new DateTime("17:00:00");
							$horaDiff = $horahasta->diff($horariocierre);
							$horaB14 = $horaDiff->format('%H:%I:%S');
							debugL('C-$horaB14:'.$horaB14);
						}else{ 
							debugL('$row14[horahasta]'.$row14['horahasta']); 
							$horahasta = new DateTime($row14['horahasta']);
							$horariocierre = new DateTime("17:00:00");
							$horaDiff = $horariocierre->diff($horahasta);
							$horaB14 = $horaDiff->format('%H:%I:%S');
							debugL('D-$horaB14:'.$horaB14);
						}
					}else{
						debugL('CUATRO 14');
						$fechatope = date('Y-m-d');
						$diasLab14 = obtenerDiasLaborales($row14['fechadesde'],$fechatope,$festivos);
						$horaB14 = 0;
					}
					$horasLab14 = ($diasLab14 * 8).':00:00'; 
					debugL('HORASESTADO-ALAESPERADELCLIENTE:'.$horasLab14);
					//RESTAR HORAS INICIALES
					$horarioinicio14 = '08:00:00';
					if($row14['horadesde'] > $horarioinicio14){
						//debugL('CINCO horadesde: '.$row14['horadesde'].', horarioinicio14: '.$horarioinicio14);
						$horadesde = new DateTime($row14['horadesde']);
						$horarioinicio14 = new DateTime($horarioinicio14);
						$horaDiff = $horadesde->diff($horarioinicio14);
						$horaA14 = $horaDiff->format('%H:%I:%S');
						debugL('E-$horaA14:'.$horaA14);
					}else{
						//debugL('SEIS horadesde: '.$row14['horadesde'].', horarioinicio14: '.$horarioinicio14);
						$horadesde = new DateTime($row14['horadesde']);
						$horarioinicio14 = new DateTime($horarioinicio14);
						$horaDiff = $horarioinicio14->diff($horadesde);
						$horaA14 = $horaDiff->format('%H:%I:%S');
						debugL('F-$horaA14:'.$horaA14);
					} 
					//RESTAMOS A LAS HORAS TOTALES LAS HORAS DE LOS EXTREMOS
					//Para el estado 14
					$horasAB14 = [$horaA14, $horaB14]; 
					$horasAB14 =  sumarHoras($horasAB14);
					
					//RESTAR 
					//Horas laborales E14
					$horasLaborales14 = explode(":", $horasLab14);
					//Resultado resta horas E14
					$arrayhorasAB14 = explode(":", $horasAB14); 
					
					//Resta horas laborales E14 - resta horas E14
					$horasResta14 = $horasLaborales14[0] - $arrayhorasAB14[0];
					debugL('horasResta14:'.$horasResta14);
					debugL('arrayhorasAB14:'.json_encode($arrayhorasAB14));    
					$horasFinales14 = $horasResta14.':'.$arrayhorasAB14[1].':'.$arrayhorasAB14[2];
					debugL('$horasLab14: '.$horasLab14.', $horasAB14: '.$horasAB14.', $horasFinales14: '.$horasFinales14.', $horaA14: '.$horaA14.', $horaB14: '.$horaB14);				
				}
				$existe14 = 1;
			}else{
				debugL('PASÓ NO EXISTE ESTADO 14');
				$existe14 = 0;
			}
			
			//ESTADO: 18 - Reporte Pendiente
			/* $queryHistEstados = "   SELECT id, fechadesde, horadesde, fechahasta, horahasta
									FROM incidentesestados2 WHERE idincidentes = ".$row['id']." AND estadonuevo = 18 ";
			if($hastaf != ''){
				$queryHistEstados .= " AND fechadesde <= $hastaf ";
			}
			debugL('$queryHistEstados:'.$queryHistEstados);
			$resultHE = $mysqli->query($queryHistEstados);
			$horasFinalesH = '00:00:00';
			if($resultHE->num_rows >0){
				debugL('PASÓ EXISTE ESTADO 18');
				while($row18 = $resultHE->fetch_assoc()){
					debugL('queryHistEstados-REPORTEPENDIENTE:'.$queryHistEstados);
					$diasLab18 = 0;
					$fechatope = str_replace('"','',$hastaf);
					//Si tiene filtros activos
					if($fechatope != ''){ 
						if($row18['fechahasta'] != ''){ 
							if($row18['fechahasta'] < $fechatope){
								debugL('UNO 18');
								$diasLab18 = obtenerDiasLaborales($row18['fechadesde'],$row18['fechahasta'],$festivos);
								//RESTAR FINAL
								if($row18['horahasta'] > $horariocierre){
									$horahasta = new DateTime($row18['horahasta']);
									$horariocierre = new DateTime($horariocierre);
									$horaDiff = $horahasta->diff($horariocierre);
									$horaB18 = $horaDiff->format('%H:%I:%S');
									debugL('A horaB18:'.$horaB18);
								}else{ 
									$horahasta = new DateTime($row18['horahasta']);
									$horariocierre = new DateTime("17:00:00");
									$horaDiff = $horariocierre->diff($horahasta);
									$horaB18 = $horaDiff->format('%H:%I:%S');
									debugL('B horaB18:'.$horaB18);
								}						
							}else{
								debugL('DOS 18');
								$diasLab18 = obtenerDiasLaborales($row18['fechadesde'],$fechatope,$festivos);
								$horaB18 = 0;
							}
						}
					}elseif($row18['fechahasta'] != ''){
						debugL('TRES 18');						
						$diasLab18 = obtenerDiasLaborales($row18['fechadesde'],$row18['fechahasta'],$festivos);
						//RESTAR FINAL
						if($row18['horahasta'] > $horariocierre){
							$horahasta = new DateTime($row18['horahasta']);
							$horariocierre = new DateTime("17:00:00");
							$horaDiff = $horahasta->diff($horariocierre);
							$horaB18 = $horaDiff->format('%H:%I:%S');
							debugL('C-$horaB18:'.$horaB18);
						}else{
							debugL('$row18[horahasta]'.$row18['horahasta']); 
							$horahasta = new DateTime($row18['horahasta']);
							$horariocierre = new DateTime("17:00:00");
							$horaDiff = $horariocierre->diff($horahasta);
							$horaB18 = $horaDiff->format('%H:%I:%S');
							debugL('D-$horaB18:'.$horaB18);
						}
					}else{
						debugL('CUATRO 18');
						$fechatope = date('Y-m-d');
						$diasLab18 = obtenerDiasLaborales($row18['fechadesde'],$fechatope,$festivos);
						$horaB18 = 0;
					}
					$horasLabSinResta18 = ($diasLab18 * 8).':00:00';
					debugL('HORASESTADO-REPORTEPENDIENTE:'.$horasLabSinResta18);
					//RESTAR HORAS INICIALES
					$horarioinicio18 = '08:00:00';
					if($row18['horadesde'] > $horarioinicio18){
						//debugL('1. horadesde: '.$row18['horadesde'].', horarioinicio18: '.$horarioinicio18);
						$horadesde = new DateTime($row18['horadesde']);
						$horarioinicio18 = new DateTime($horarioinicio18);
						$horaDiff = $horadesde->diff($horarioinicio18);
						$horaA18 = $horaDiff->format('%H:%I:%S');
						debugL('E-$horaA18:'.$horaA18);
					}else{
						//debugL('2. horadesde: '.$row18['horadesde'].', horarioinicio18: '.$horarioinicio18);
						$horadesde = new DateTime($row18['horadesde']);
						$horarioinicio18 = new DateTime($horarioinicio18);
						$horaDiff = $horarioinicio18->diff($horadesde);
						$horaA18 = $horaDiff->format('%H:%I:%S');
						debugL('F-$horaA18:'.$horaA18);
					} 
					//RESTAMOS A LAS HORAS TOTALES LAS HORAS DE LOS EXTREMOS
					//Para el estado 18
					$horasAB18 = [$horaA18, $horaB18]; 
					$horasSumAB18 =  sumarHoras($horasAB18);
					
					//RESTAR 
					//Horas laborales E18
					$horasLaborales18 = explode(":", $horasLabSinResta18);
					//Resultado resta horas E18
					$arrayhorasSumAB18 = explode(":", $horasSumAB18);
					debugL('$horasSumAB18: '.$horasSumAB18);
					debugL('$arrayhorasSumAB18[0] '.$arrayhorasSumAB18[0]);
					debugL('$horasLaborales18[0] '.$horasLaborales18[0]);
					
					//Resta horas laborales E18 - resta horas E18
					$horasResta18 = $horasLaborales18[0] - $arrayhorasSumAB18[0];
					$horasFinales18 = $horasResta18.':'.$arrayhorasSumAB18[1].':'.$arrayhorasSumAB18[2];
					debugL('$horasLabSinResta18: '.$horasLabSinResta18.', $horasSumAB18: '.$horasSumAB18.', $horasFinales18: '.$horasFinales18.', $horaA18: '.$horaA18.', $horaB18: '.$horaB18);				
				}
			}else{
				debugL('PASÓ NO EXISTE ESTADO 18');
			} 
			
			//ESTADO: 42 - En Espera
			$queryHistEstados = "   SELECT id, fechadesde, horadesde, fechahasta, horahasta
									FROM incidentesestados2 WHERE idincidentes = ".$row['id']." AND estadonuevo = 42 ";
			if($hastaf != ''){
				$queryHistEstados .= " AND fechadesde <= $hastaf ";
			} 
			$resultHE = $mysqli->query($queryHistEstados);
			$horasFinalesH = '00:00:00';
			if($resultHE->num_rows >0){
				debugL('PASÓ EXISTE ESTADO 42');
				while($row42 = $resultHE->fetch_assoc()){
					debugL('queryHistEstados-EN ESPERA:'.$queryHistEstados);
					$diasLab42 = 0;
					$fechatope = str_replace('"','',$hastaf);
					//Si tiene filtros activos
					if($fechatope != ''){ 
						if($row42['fechahasta'] != ''){ 
							if($row42['fechahasta'] < $fechatope){ 
								debugL('UNO 42');
								$diasLab42 = obtenerDiasLaborales($row42['fechadesde'],$row42['fechahasta'],$festivos);
								//RESTAR FINAL
								if($row42['horahasta'] > $horariocierre){
									$horahasta = new DateTime($row42['horahasta']);
									$horariocierre = new DateTime($horariocierre);
									$horaDiff = $horahasta->diff($horariocierre);
									$horaB42 = $horaDiff->format('%H:%I:%S');
									debugL('A horaB42:'.$horaB42);
								}else{ 
									$horahasta = new DateTime($row42['horahasta']);
									$horariocierre = new DateTime("17:00:00");
									$horaDiff = $horariocierre->diff($horahasta);
									$horaB42 = $horaDiff->format('%H:%I:%S');
									debugL('B horaB42:'.$horaB42);
								}						
							}else{ 
								debugL('DOS 42');
								$diasLab42 = obtenerDiasLaborales($row42['fechadesde'],$fechatope,$festivos);
								$horaB42 = 0;
							}
						}
					}elseif($row42['fechahasta'] != ''){  
						debugL('TRES 42');		
						$diasLab42 = obtenerDiasLaborales($row42['fechadesde'],$row42['fechahasta'],$festivos);
						//RESTAR FINAL
						if($row42['horahasta'] > $horariocierre){ 
							$horahasta = new DateTime($row42['horahasta']);
							$horariocierre = new DateTime("17:00:00");
							$horaDiff = $horahasta->diff($horariocierre);
							$horaB42 = $horaDiff->format('%H:%I:%S'); 
							debugL('C-$horaB42:'.$horaB42);
						}else{ 
							debugL('$row42[horahasta]'.$row42['horahasta']); 
							$horahasta = new DateTime($row42['horahasta']);
							$horariocierre = new DateTime("17:00:00");
							$horaDiff = $horariocierre->diff($horahasta);
							$horaB42 = $horaDiff->format('%H:%I:%S'); 
							debugL('D-$horaB42:'.$horaB42);
						}
					}else{ 
						debugL('CUATRO 42');
						$fechatope = date('Y-m-d');
						$diasLab42 = obtenerDiasLaborales($row42['fechadesde'],$fechatope,$festivos);
						$horaB42 = 0;
					}
					$horasLabSinResta42 = ($diasLab42 * 8).':00:00'; 
					debugL('HORASESTADO-ENESPERA:'.$horasLabSinResta42);
					//RESTAR HORAS INICIALES
					$horarioinicio42 = '08:00:00';
					if($row42['horadesde'] > $horarioinicio42){
						//debugL('1. horadesde: '.$row42['horadesde'].', horarioinicio42: '.$horarioinicio42);
						$horadesde = new DateTime($row42['horadesde']);
						$horarioinicio42 = new DateTime($horarioinicio42);
						$horaDiff = $horadesde->diff($horarioinicio42);
						$horaA42 = $horaDiff->format('%H:%I:%S');
						debugL('E-$horaA42:'.$horaA42);
					}else{
						//debugL('2. horadesde: '.$row42['horadesde'].', horarioinicio42: '.$horarioinicio42);
						$horadesde = new DateTime($row42['horadesde']);
						$horarioinicio42 = new DateTime($horarioinicio42);
						$horaDiff = $horarioinicio42->diff($horadesde);
						$horaA42 = $horaDiff->format('%H:%I:%S');
						debugL('F-$horaA42:'.$horaA42);
					} 
					//RESTAMOS A LAS HORAS TOTALES LAS HORAS DE LOS EXTREMOS
					//Para el estado 42
					$horasAB42 = [$horaA42, $horaB42]; 
					$horasSumAB42 =  sumarHoras($horasAB42);
					
					//RESTAR 
					//Horas laborales E42
					$horasLaborales42 = explode(":", $horasLabSinResta42);
					//Resultado resta horas E42
					$arrayhorasSumAB42 = explode(":", $horasSumAB42);
					debugL('$horasSumAB42: '.$horasSumAB42);
					debugL('$arrayhorasSumAB42[0] '.$arrayhorasSumAB42[0]);
					debugL('$horasLaborales42[0] '.$horasLaborales42[0]);
					
					//Resta horas laborales E42 - resta horas E42
					$horasResta42 = $horasLaborales42[0] - $arrayhorasSumAB42[0];
					$horasFinales42 = $horasResta42.':'.$arrayhorasSumAB42[1].':'.$arrayhorasSumAB42[2];
					debugL('$horasLabSinResta42: '.$horasLabSinResta42.', $horasSumAB42: '.$horasSumAB42.', $horasFinales42: '.$horasFinales42.', $horaA42: '.$horaA42.', $horaB42: '.$horaB42);				
				}
			}else{
				debugL('PASÓ NO EXISTE ESTADO 42');
			} */ 
    		
			//RESTAMOS A LAS HORAS TOTALES LAS HORAS DE LOS EXTREMOS
			//Suma de Horas A y B
			$horasayb = [$horaA, $horaB];
			$horasayb =  sumarHoras($horasayb);
			
			$arrayHorasayb = explode(':',$horasayb);
			debugL('$arrayHorasayb:'.json_encode($arrayHorasayb));
			
			if($existe14 == 1){
				//Resultado de horas de Estado 14
				$arrayHorasFinales14 = explode(':',$horasFinales14); 
				debugL('$arrayHorasFinales14:'.json_encode($arrayHorasFinales14)); 
				$sumaTotal14 = ['00:'.$arrayHorasFinales14[1].':'.$arrayHorasFinales14[2], '00:'.$arrayHorasayb[1].':'.$arrayHorasayb[2]];
				//Suma horas Estado 14 + Total
				debugL('$sumaTotal14 antes:'.json_encode($sumaTotal14));
				$sumaTotal14 =  sumarHoras($sumaTotal14); 
				debugL('$sumaTotal14 después:'.$sumaTotal14);
			 }else{
				debugL('TWXY'); 
			} 			
			
			//Resultado de horas de Estado 18
			/* $arrayHorasFinales18 = explode(':',$horasFinales18);
			$arrayHorasayb = explode(':',$horasSinEstados);
			debugL('$arrayHorasFinales18:'.json_encode($arrayHorasFinales18));
			debugL('$arrayHorasayb:'.json_encode($arrayHorasayb));
			$sumaTotal18 = ['00:'.$arrayHorasFinales18[1].':'.$arrayHorasFinales18[2], '00:'.$arrayHorasayb[1].':'.$arrayHorasayb[2]];
			//Suma horas Estado 18 + Total
			$sumaTotal18 =  sumarHoras($sumaTotal18); 
			debugL('$sumaTotal18:'.$sumaTotal18);
			
			//Resultado de horas de Estado 42
			$arrayHorasFinales42 = explode(':',$horasFinales42);
			$arrayHorasayb = explode(':',$horasSinEstados);
			debugL('$arrayHorasFinales42:'.json_encode($arrayHorasFinales42));
			debugL('$arrayHorasayb:'.json_encode($arrayHorasayb));
			$sumaTotal42 = ['00:'.$arrayHorasFinales42[1].':'.$arrayHorasFinales42[2], '00:'.$arrayHorasayb[1].':'.$arrayHorasayb[2]];
			//Suma horas Estado 42 + Total
			$sumaTotal42 =  sumarHoras($sumaTotal42); 
			debugL('$sumaTotal42:'.$sumaTotal42); */
			
			//RESTAR 
			//Horas laborales totales Sin estados
			$horasLabSinEstados = explode(":", $horaslab);
			debugL('sumaTotal14 eee:'.$sumaTotal14);
			if($existe14 == 1){
				//Horas estado 14 + Total
				$horasSumaTotal14 = explode(":", $sumaTotal14);
				debugL('horasSumaTotal14:'.json_encode($horasSumaTotal14));
				debugL('PASO XX');
			 }else{ 
				$horasSumaTotal14 = 0;
				debugL('horasSumaTotal14:'.json_encode($horasSumaTotal14));
				debugL('PASO ZZ');
			} 
			
			//Horas estado 18 + Total
			/*$horasSumaTotal18 	= explode(":", $sumaTotal18);
			debugL('horasSumaTotal18:'.json_encode($horasSumaTotal18));
			
			//Horas estado 42 + Total
			$horasSumaTotal42 	= explode(":", $sumaTotal42);
			debugL('horasSumaTotal42:'.json_encode($horasSumaTotal42));*/
			
			//$horasres = abs($horaslabP[0]) - abs($arrayHorasayb[0]);
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
			
			if($existe14 == 1){
				$arrayHorasFinales14[0] = $arrayHorasFinales14[0];
				$horasSuma14     	 	= $horasSumaTotal14[0];
				//$minSumaTotal14			= 60 - $horasSumaTotal14[1];
				$minSumaTotal14			= $horasSumaTotal14[1];
				$segSumaTotal14			= $horasSumaTotal14[2];
				debugL('$minSumaTotal14[1]:'.$minSumaTotal14);
				debugL('$horasSumaTotal14[1]:'.$horasSumaTotal14[1]);
				debugL('000000000000');
			 }else{
				$arrayHorasFinales14[0] = 0;
				$horasSuma14			= 0;
				//$minSumaTotal14			= 60 - $arrayHorasayb[1]; 
				$minSumaTotal14			= $arrayHorasayb[1]; 
				$segSumaTotal14			= $arrayHorasayb[2];
				debugL('111111111111');
			} 
			//$horasres = $horasLabSinEstados[0] - $arrayHorasayb[0] - $arrayHorasFinales14[0] + $restaA + $restaB;
			//CORRECTA--$horasres = $horasLabSinEstados[0] - $arrayHorasayb[0] - $arrayHorasFinales14[0] - $horasSumaTotal14[0] + $restaA + $restaB;
			$horasres = $horasLabSinEstados[0] - $arrayHorasayb[0] - $arrayHorasFinales14[0] - $horasSuma14 + $restaA + $restaB;
			//$horasres = $horasLabSinEstados[0] - $arrayHorasayb[0] + $restaA + $restaB; //Original
			debugL('horasLabSinEstados'.$horasLabSinEstados[0].', arrayHorasayb:'.$arrayHorasayb[0].', arrayHorasFinales14:'.$arrayHorasFinales14[0].', horasSumaTotal14:'.$horasSuma14.', restaA:'.$restaA.', restaB:'.$restaB);
			  
			debugL('EXISTE14:'.$existe14);
			 
			$horasres		= str_pad($horasres, 2, "0", STR_PAD_LEFT);  
			$minSumaTotal14 = str_pad($minSumaTotal14, 2, "0", STR_PAD_LEFT);  
			$segSumaTotal14 = str_pad($segSumaTotal14, 2, "0", STR_PAD_LEFT);
			$horasFinales = $horasres.':'.$minSumaTotal14.':'.$segSumaTotal14;  
			//$horasFinales = $horasres.':'.$horasSumaTotal14[1].':'.$horasSumaTotal14[2];  
		}
		
		debugL('$horasFinales: '.$horasFinales);
		debugL('----------------------------------------FIN----------------------------------------------------'); 
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