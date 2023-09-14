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
	$bfechar		= $_REQUEST['bfechar'];
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
	
	$id 	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
	$data 	 = '';
	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

	//load phpspreadsheet class using namespaces
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	//call xlsx writer class to make an xlsx file
	use PhpOffice\PhpSpreadsheet\IOFactory;
	//make a new spreadsheet object
	$spreadsheet = new Spreadsheet();
	//obtener la hoja activa actual, (que es la primera hoja)
	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setTitle('Preventivos');
	
	$fontColor = new \PhpOffice\PhpSpreadsheet\Style\Color();
	$fontColor->setRGB('ffffff');
	$style = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
	);
	
	//TITULO	
	$spreadsheet->getActiveSheet()->setCellValue('A1', 'Reporte de Preventivos');
	$spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
	//$spreadsheet->getActiveSheet()->getStyle("A1")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->mergeCells('A1:AM1');
	
	// ENCABEZADO 
	$spreadsheet->getActiveSheet()
	->setCellValue('A4', '# Preventivo')
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
	->setCellValue('L4', 'Tipo')
	->setCellValue('M4', 'Estado del equipo')
	->setCellValue('N4', 'Categoría')
	->setCellValue('O4', 'Subcategoría')
	->setCellValue('P4', 'Ubicación')
	->setCellValue('Q4', 'Prioridad')
	->setCellValue('R4', 'Origen')
	->setCellValue('S4', 'Creado por')
	->setCellValue('T4', 'Solicitante')
	->setCellValue('U4', 'Asignado a') 				
	->setCellValue('V4', 'Resuelto por')
	->setCellValue('W4', 'Resolución')
	->setCellValue('X4', 'Satisfacción')
	->setCellValue('Y4', 'Comentario de Satisfacción')		
	->setCellValue('Z4', 'Fecha de creación')
	->setCellValue('AA4', 'Hora de creación')
	->setCellValue('AB4', 'Fecha de resolución')
	->setCellValue('AC4', 'Hora de resolución')
	->setCellValue('AD4', 'Fecha de cierre')
	->setCellValue('AE4', 'Hora de cierre')
	->setCellValue('AF4', 'Fecha de vencimiento')
	->setCellValue('AG4', 'Hora de vencimiento')
	->setCellValue('AH4', 'Fecha real')
	->setCellValue('AI4', 'Hora de real')		
	->setCellValue('AJ4', 'Tiempo de servicio')
	->setCellValue('AK4', 'Horas Trabajadas')
	->setCellValue('AL4', 'Periodo');
	
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
	$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, e.nombre AS estado, 
				m.nombre AS equipo, m.serie, m.activo, mar.nombre as marca, r.nombre as modelo, m.modalidad, m.estado as estadoequipo, 
				f.nombre AS categoria, g.nombre AS subcategoria, c.nombre AS sitio, h.prioridad, 
				a.origen, a.creadopor, a.solicitante, a.asignadoa, o.nombre AS departamento, a.resueltopor,
				a.resolucion, a.satisfaccion, a.comentariosatisfaccion, 
				ifnull(a.fechacreacion, '') AS fechacreacion, a.horacreacion, 
				ifnull(a.fecharesolucion, '') as fecharesolucion, a.horaresolucion,
				ifnull(a.fechacierre, '') as fechacierre, a.horacierre, 
				ifnull(a.fechavencimiento, '') AS fechavencimiento, a.horavencimiento, 
				ifnull(a.fechareal, '') AS fechareal, a.horareal, 
				a.horastrabajadas, cu.periodo, p.nombre as cliente, a.idestados, a.idprioridades 
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
	
	if($nivel != 1 && $nivel != 2){
		$query .= " LEFT JOIN usuarios q ON find_in_set(c.id, q.idambientes) AND q.usuario = '$usuario' ";
	}
	$query  .= " WHERE a.tipo = 'preventivos' ";
	
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
				$query  .= "AND FIND_IN_SET(a.iddepartamentos,'".$iddepartamentosSES."')  ";
			}else{
				$query  .= " OR j.usuario = '".$_SESSION['usuario']."' ";
			}
		}			
	}
	
	//DATOS
	$where = "";
	$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Preventivos' AND usuario = '".$_SESSION['usuario']."'";
	$resultF = $mysqli->query($queryF);
	if($resultF->num_rows >0){
		$rowF = $resultF->fetch_assoc();				
		if (!isset($_REQUEST['data'])) {
			$data = $rowF['filtrosmasivos'];
		}
	}		
	if($data != ''){
		$data = json_decode($data);
		/* if(!empty($data->desdef)){
			$desdef = json_encode($data->desdef);
			$where .= " AND a.fechacreacion >= $desdef ";
		}
		if(!empty($data->hastaf)){
			$hastaf = json_encode($data->hastaf);
			$where .= " AND a.fechacreacion <= $hastaf ";
		} */						
		$optradio = (isset($data->optradio) ? $data->optradio : '');
		if($optradio == 'crea'){
			if(!empty($data->desdef)){
				$desdef = json_encode($data->desdef);
				$where .= " AND a.fechacreacion >= $desdef ";
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where .= " AND a.fechacreacion <= $hastaf ";
			}
		}else{
			if(!empty($data->desdefreal)){
				$desdefreal = json_encode($data->desdefreal);
				$where .= " AND a.fechareal >= $desdefreal ";
			}
			if(!empty($data->hastafreal)){
				$hastafreal = json_encode($data->hastafreal);
				$where .= " AND a.fechareal <= $hastafreal ";
	 
			}
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
	if($bfechar != ''){
		$where .= " AND a.fechareal = '".$bfechar."' ";
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
		$where .= " AND m.serie like '%".$bserie."%' ";
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
	debugL("PREVENTIVOS:".$query);
	function diferencia($fechadesde,$horadesde,$fechahasta,$horahasta){
		$fecha1 = strtotime($fechadesde."".$horadesde);
		$fecha2 = strtotime($fechahasta."".$horahasta);  
		$dif = $fecha2 - $fecha1; 

		$horas = floor($dif/3600);
		$minutos = floor(($dif-($horas*3600))/60);
		$segundos = $dif-($horas*3600)-($minutos*60);
		 
		return $horas.":".$minutos.":".$segundos;
	}
	
	function suma($hora1,$hora2){
		$hora1=explode(":",$hora1);
		$hora2=explode(":",$hora2);
		$horas=(int)$hora1[0]+(int)$hora2[0];
		$minutos=(int)$hora1[1]+(int)$hora2[1];
		$segundos=(int)$hora1[2]+(int)$hora2[2];
		$horas+=(int)($minutos/60);
		$minutos=(int)($minutos%60)+(int)($segundos/60);
		$segundos=(int)($segundos%60);
		return (intval($horas)<10?'0'.intval($horas):intval($horas)).':'.($minutos<10?'0'.$minutos:$minutos).':'.($segundos<10?'0'.$segundos:$segundos);
	}
	
	function resta($hora1,$hora2){
		$hora1=explode(":",$hora1);
		$hora2=explode(":",$hora2);
		
		$horas1=(int)$hora1[0];
		$minutos1=(int)$hora1[1];
		$segundos1=(int)$hora1[2];
		
		$horas2=(int)$hora2[0];
		$minutos2=(int)$hora2[1];
		$segundos2=(int)$hora2[2];
		
		if($minutos1 < $minutos2){
			$horas1 = $horas1 - 1;
			$minutos1 =  $minutos1 + 60 - 1;
			$segundos1 =  $segundos1 + 60; 
		}else{
			$horas1 = $horas1;
			$minutos1 = $minutos1;
			$segundos1 = $segundos1;
		} 
		if($segundos1 < $segundos2){ 
			$minutos1 =  $minutos1 - 1;
			$segundos1 =  $segundos1 + 60; 
		}else{ 
			$minutos1 = $minutos1;
			$segundos1 = $segundos1;
		} 
		$horast = $horas1 - $horas2; 
		$minutost = $minutos1 - $minutos2; 
		$segundost = $segundos1 - $segundos2;
		
		return ''.$horast.':'.$minutost.':'.$segundost;
	}
	
	function sumaArray($horas) {		
		$total = 0;
		debugL('insumarhoras:'.$horas);
		foreach($horas as $h) { 
			$arreglo = explode(":", $h);
			$hora = $arreglo[0];
			$min = $arreglo[1];
			$seg = $arreglo[2];
			
			$horat += $hora;
			$mint += $min;
			$segt += $seg;
			
			if($mint > 60){
				$horat = $horat +1;
				$mint  = $mint - 60;
			}else{
				$horat = $horat;
				$mint = $mint;
			}
			
			if($segt > 60){
				$mint = $mint +1;
				$segt  = $segt - 60;
			}else{
				$mint = $mint;
				$segt = $segt;
			}
			
		}
		debugL("HORA:".$horat."-MINUTOS:".$mint."-SEGUNDOS:".$segt); 
		return $horat.":".$mint.":".$segt;
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
		
		/*---------------------------- SLA-----------------------------------*/  
		//Variables 
		$idestadosInc    = $row['idestados'];
		$prioridadsla    = $row['idprioridades'];
		//$niveldeservicio = $row['niveldeservicio']; 
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
		$fechatope 		= str_replace('"','',$hastaf);
		$fechaespera    = date('Y-m-d');
		$esMayorHorasA  = 0;
		$esMayorHorasB  = 0;
		$verHoraInicio  = new DateTime($horarioinicio);
		$verHoraCierre  = new DateTime($horariocierre);
		debugL('----------------------------------------INICIO----------------------------------------------------'); 
		//Variables nuevas
		$horaA     = 0;
		$horaB 	   = 0;
		$horaX 	   = 0;
		$tipoAyB   = 0;
		
		debugL('INCIDENTE: '.$row['id']); 
		//Si fecha creación es igual a fecha resolución
		if($row['fechacreacion'] > $hoy){
			$horaX = "00:00:00";
			debugL('Fecha posterior');
		}elseif($row['fechacreacion'] == $row['fecharesolucion']){
			if($row['horaresolucion'] > $horariocierre){
				$row['horaresolucion'] = $horariocierre; 
			} 
			if($row['horaresolucion'] < $row['horacreacion']){
				$horaX = "00:00:00";
			}else{
				$horaX = diferencia($row['fechacreacion'],$row['horacreacion'],$row['fecharesolucion'],$row['horaresolucion']); 
			}
		    
			debugL('HORA X:'.$horaX); 
			debugL('111111'); 
		//Si fecha creación es igual a fecha tope y fecha resolucion es vacía
		}elseif(($row['fechacreacion'] == $fechatope) && $row['fecharesolucion'] == ''){
			$horaX = diferencia($row['fechacreacion'],$row['horacreacion'],$fechatope,$horariocierre); 
			debugL('HORA X:'.$horaX);
			debugL('222222');
		}else{
		    debugL('333333');
		    if($row['fecharesolucion'] == ''){ 
    			if($hastaf != ''){
    				$fechatope = str_replace('"','',$hastaf);
					debugL('A');
    			}else{
    				$fechatope = $hoy;
					debugL('B');
    			} 
    			if($fechatope != $hoy){
    				$horatope  = $horariocierre;
					debugL('C');
    			}else{
    				$horatope  = $ahora;
					debugL('D');
    			}
    		}else{
				if($hastaf != ''){
					debugL('E');
					$fechatope = str_replace('"','',$hastaf);
					if($row['fecharesolucion'] <= $fechatope){
						$fechatope = $row['fecharesolucion'];
						$horatope  = $row['horaresolucion'];
						debugL('E-1');
					}else{
						$fechatope = $fechatope;
						$horatope  = $horariocierre;
						debugL('E-2');
					}
					
				}else{ 
					$fechatope = $row['fecharesolucion'];
					$horatope = $row['horaresolucion'];
					debugL('F');
				} 
    		} 
			/* if($idestadosInc == 14 || $idestadosInc == 18 || $idestadosInc == 42){
				$queryV = " SELECT fechadesde FROM incidentesestados 
				WHERE idincidentes = ".$row['id']." AND estadonuevo = ".$idestadosInc." 
				ORDER BY id DESC LIMIT 1";
				debugL('queryV:'.$queryV);
				$resultV = $mysqli->query($queryV);
				if($rowV = $resultV->fetch_assoc()){
					$fechatope = $rowV['fechadesde'];
				}else{
					$fechatope = $fechatope;
				}
			} */ 
			if($horatope > $horariocierre){
				$horatope = $horariocierre;
			}elseif($horatope<$horarioinicio){
				$horatope = $horarioinicio;
			}else{
				$horatope = $horatope;
			}
			$tipoAyB = 1;
			if($row['horacreacion'] > $horariocierre){
				$horaA = "00:00:00";
			}else{
				debugL('UNO');  
				$horaA = diferencia($row['fechacreacion'],$row['horacreacion'],$row['fechacreacion'],$horariocierre);
			}
			
			if($row['fechacreacion'] == $fechatope){
				$horaB = 0;
			}else{
				debugL('DOS'); 
				$horaB = diferencia($fechatope,$horarioinicio,$fechatope,$horatope);
			}
			debugL("HORA A:".$horaA);  
			debugL("HORA B:".$horaB);  
			$sumaAB = suma($horaA,$horaB);
			debugL("SUMA A+B:".$sumaAB);  
		} 
		debugL('FECHA TOPE:'.$fechatope);
		$diasLaborales = obtenerDiasLaborales($row['fechacreacion'],$fechatope,$festivos);
		debugL('diasLaborales:'.$diasLaborales);  
		 
		$creacionEsLab	 = obtenerDiasLaborales($row['fechacreacion'],$row['fechacreacion'],$festivos);
		$resolucionEsLab = obtenerDiasLaborales($fechatope,$fechatope,$festivos);
		
		if($tipoAyB == 1){
			if($diasLaborales > 2){
				if($creacionEsLab != 0 && $resolucionEsLab != 0){
					$diasLaborales = $diasLaborales - 2;
				}elseif($creacionEsLab != 1 && $resolucionEsLab != 0){
					$diasLaborales = $diasLaborales - 1;
				}elseif($creacionEsLab != 0 && $resolucionEsLab != 1){
					$diasLaborales = $diasLaborales - 1;
				}
				
				debugL('PASO1 -diasLaborales:'.$diasLaborales);
				$horasLaboralesGeneral = $diasLaborales * 8;
				$horasLaboralesGeneral = $horasLaboralesGeneral.":00:00";
				debugL('$horasLaboralesGeneral:'.$horasLaboralesGeneral);
			}else{
				$diasLaborales 		   = 0;
				$horasLaboralesGeneral = '00:00:00';
				debugL('PASO2 -diasLaborales:'.$diasLaborales);
				debugL('$horasLaboralesGeneral:'.$horasLaboralesGeneral);
			}
		}else{
			$diasLaborales = 0;
			debugL('PASO3 -diasLaborales'.$diasLaborales);
		}
		
		
		if($tipoAyB == 1){
			$sumaABG = suma($sumaAB,$horasLaboralesGeneral);
			$totalGeneral = $sumaABG;
			debugL('PASO4');
		}else{
			$totalGeneral = $horaX;
			debugL('PASO5');
		}	 
		
		//Total General
		debugL("totalGeneral:".$totalGeneral);
		
		$horas14 = '00:00:00';
		$horas18 = '00:00:00';
		$horas42 = '00:00:00';
		
		$getHoras14 = obtenerHorasEstados($horariocierre,$horarioinicio,$row['id'],14,$hastaf,$festivos,$row['fechacreacion'],$row['fecharesolucion'],$row['horaresolucion']);
		if($getHoras14['existeestado'] == 1){
			$horas14 = $getHoras14['totalestado'];
		} 
		 debugL('$horas14:'.$horas14);
		 debugL('$getHoras14:'.json_encode($getHoras14));
		 
		$getHoras18 = obtenerHorasEstados($horariocierre,$horarioinicio,$row['id'],18,$hastaf,$festivos,$row['fechacreacion'],$row['fecharesolucion'],$row['horaresolucion']);
		debugL('getHoras18[0]:'.$getHoras18[0]);
		debugL('getHoras18[1]:'.$getHoras18[1]);
		if($getHoras18['existeestado'] == 1){
			$horas18 = $getHoras18['totalestado'];
		} 
		
		$getHoras42 = obtenerHorasEstados($horariocierre,$horarioinicio,$row['id'],42,$hastaf,$festivos,$row['fechacreacion'],$row['fecharesolucion'],$row['horaresolucion']);
		if($getHoras42['existeestado'] == 1){
			$horas42 = $getHoras42['totalestado'];
		} 
		
		
		
		
		$suma1418   = suma($horas14,$horas18);
		debugL('suma1418'.$suma1418);
		$suma141842 = suma($suma1418,$horas42);
		$horasNoServicio = $suma141842;
		debugL('horasNoServicio'.$horasNoServicio);
		$totalFinal = resta($totalGeneral,$horasNoServicio);
		debugL('totalFinal: '.$totalFinal);
		 
		
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
		->setCellValue('V'.$i, $row['resueltopor'])
		->setCellValue('W'.$i, $row['resolucion'])
		->setCellValue('X'.$i, $row['satisfaccion'])
		->setCellValue('Y'.$i, $row['comentariosatisfaccion'])
		->setCellValue('Z'.$i, $xfechacreacion) //->setCellValue('W'.$i, implode('/',array_reverse(explode('-', $row['fechacreacion']))))
		->setCellValue('AA'.$i, $row['horacreacion'])
		->setCellValue('AB'.$i, $xfecharesolucion)// ->setCellValue('AA'.$i, implode('/',array_reverse(explode('-', $row['fecharesolucion']))))
		->setCellValue('AC'.$i, $row['horaresolucion'])
		->setCellValue('AD'.$i, $xfechacierre) //->setCellValue('AC'.$i, implode('/',array_reverse(explode('-', $row['fechacierre']))))
		->setCellValue('AE'.$i, $row['horacierre'])
		->setCellValue('AF'.$i, $xfechavencimiento) // ->setCellValue('Y'.$i, implode('/',array_reverse(explode('-', $row['fechavencimiento']))))
		->setCellValue('AG'.$i, $row['horavencimiento'])
		->setCellValue('AH'.$i, $xfechareal) // ->setCellValue('Y'.$i, implode('/',array_reverse(explode('-', $row['fechavencimiento']))))
		->setCellValue('AI'.$i, $row['horareal'])
		//->setCellValue('AK'.$i, $dif)
		->setCellValue('AJ'.$i, $totalFinal)
		->setCellValue('AK'.$i, $row['horastrabajadas'])
		->setCellValue('AL'.$i, $row['periodo']);
		
		//ESTILOS
		$spreadsheet->getActiveSheet()->getStyle('A'.$i.':AJ'.$i)->getFont()->setSize(10);
		$spreadsheet->getActiveSheet()->getStyle('A'.$i.':AJ'.$i)->getAlignment()->applyFromArray(
					array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
		$spreadsheet->getActiveSheet()->getStyle('Z'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
		$spreadsheet->getActiveSheet()->getStyle('Z'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
		$spreadsheet->getActiveSheet()->getStyle('AF'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
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
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(60);
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
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
	$spreadsheet->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('X')->setWidth(50);
	$spreadsheet->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AF')->setWidth(18);
	$spreadsheet->getActiveSheet()->getColumnDimension('AG')->setWidth(24);
	$spreadsheet->getActiveSheet()->getColumnDimension('AH')->setWidth(24);
	$spreadsheet->getActiveSheet()->getColumnDimension('AI')->setWidth(18);
	$spreadsheet->getActiveSheet()->getColumnDimension('AJ')->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AL')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AM')->setAutoSize(true);
	
	$hoy = date('dmY');
	$nombreArc = 'Preventivos - '.$hoy.'.xlsx';
	// redirect output to client browser
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Type: application/vnd.ms-excel'); //xls
	header('Content-Disposition: attachment;filename='.$nombreArc);
	header('Cache-Control: max-age=0');	
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	ob_start();
	$writer->save('php://output');
	$xlsData = ob_get_contents();
	ob_end_clean();

	$response =  array(
			'name' => $nombreArc,
			'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
		);

	die(json_encode($response)); 
	
	function obtenerDiasLaborales($startDate,$endDate,$holidays){
		debugL('FECHAINICIOLAB:'.$startDate);
		debugL('FECHAFINLAB:'.$endDate);
		//The function returns the no. of business days between two dates and it skips the holidays
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

		return $workingDays;
	}
	
	function obtenerHorasEstados($horariocierre,$horarioinicio,$idincidentes,$idestados,$hastaf,$festivos,$fechacreacion,$fecharesolucion,$horaresolucion){ 
		global $mysqli;
		debugL('idincidentes:'.$idincidentes);
		$contar    = 0;
		$horaA14   = 0;
		$horaB14   = 0;
		$horaX14   = 0;
		$existe14  = 0;
		$hoy       = date("Y-m-d");
		$ahora     = date("H:i:s");
		$valores   = array();
		
		$queryHistEstados14 = "   SELECT id, fechadesde, horadesde, fechahasta, horahasta
								FROM incidentesestados WHERE idincidentes = ".$idincidentes." AND estadonuevo = ".$idestados." ";
		if($hastaf != ''){
			$queryHistEstados14 .= " AND fechadesde <= $hastaf ";
		} 
		debugL('$queryHistEstados14:'.$queryHistEstados14);
		$result14 = $mysqli->query($queryHistEstados14); 
		if($result14->num_rows >0){
			$existe14 = 1;
			
			while($row14 = $result14->fetch_assoc()){
				$contar++;
				debugL('PASÓ EXISTE ESTADO '.$idestados);
				debugL('-----------------------------INICIO ESTADO:'.$idestados.'-----------------------------------------');
				
				//Si fecha desde es mayor a fecha de resolución, no se toman en cuenta las horas
				if(($row14['fechadesde'] < $fecharesolucion && $fecharesolucion !="") ||
					($fecharesolucion == "")){
						 
					if((($row14['fechadesde'] >= $fechacreacion && $row14['fechadesde'] <= $fecharesolucion)&&
						(($row14['fechahasta'] >= $fechacreacion /* && $row14['fechahasta'] <= $fecharesolucion */)||
						($row14['fechahasta'] == "")))||
						($row14['fechadesde'] >= $fechacreacion && $fecharesolucion == "")){
							
						$fechatope = str_replace('"','',$hastaf);
						if($fechatope != ""){
							if($row14['fechahasta'] < $fechatope && $row14['fechahasta'] != ""){
								$diasLaborales14 = obtenerDiasLaborales($row14['fechadesde'],$row14['fechahasta'],$festivos);
								debugL('III');
							}else{
								$diasLaborales14 = obtenerDiasLaborales($row14['fechadesde'],$fechatope,$festivos);
								debugL('JJJ');
							}
						}else{
							if($row14['fechahasta'] !=""){
								$diasLaborales14 = obtenerDiasLaborales($row14['fechadesde'],$row14['fechahasta'],$festivos);
								debugL('KKK');
							}else{
								$diasLaborales14 = obtenerDiasLaborales($row14['fechadesde'],$hoy,$festivos);
							}
						}
						debugL("FECHATOPE:".$fechatope); 
						//Si tiene filtros activos 
							 
							if($fechatope != ''){ 
								if($row14['fechadesde'] == $fechatope){
									if($fechatope != $row14['fechahasta']){
										$horahasta = $horariocierre;
									}else{
										$horahasta = $row14['horahasta'];
									}
									if($row14['horadesde'] > $horariocierre){
										$horaA14 = '00:00:00';
									}else{
										$horaA14 = diferencia($fechatope,$row14['horadesde'],$fechatope,$horahasta);
									}
									$horaB14 = '00:00:00';
									debugL('ANT 1');
								}else{
									if($row14['fechahasta'] != ''){ 
									debugL('UNO 14');
										if($row14['fechahasta'] <= $fechatope){
												debugL('UNO 14-2');
												if($row14['horadesde'] > $horariocierre){
													$horaA14 = '00:00:00';
												}else{
													$horaA14 = diferencia($row14['fechadesde'],$row14['horadesde'],$row14['fechadesde'],$horariocierre);							
												}
												$horaB14 = diferencia($fechatope,$horarioinicio,$fechatope,$row14['horahasta']);						
										   }else{ 
											debugL('DOS 14');
											if($row14['horadesde'] > $horariocierre){
												$horaA14 = '00:00:00';
											}else{
												$horaA14 = diferencia($row14['fechadesde'],$row14['horadesde'],$row14['fechadesde'],$horariocierre);						
											}
											$horaB14 = '08:00:00';
										}
									}else{
										if($row14['horadesde'] > $horariocierre){
											$horaA14 = '00:00:00';
										}else{
											$horaA14 = diferencia($row14['fechadesde'],$row14['horadesde'],$row14['fechadesde'],$horariocierre);						
										}
										$horaB14 = '08:00:00';
										debugL("ABC");
									}
								} 
							}elseif($row14['fechahasta'] != ''){ 
								if($row14['fechadesde'] == $row14['fechahasta']){
									$horaA14 = diferencia($fechatope,$row14['horadesde'],$fechatope,$row14['horahasta']);
									debugL('ANT2'); 
								}else{
									debugL('TRES 14'); 
									$horadesde = $row14['horadesde'];
									if($horadesde > $horariocierre){
										$horadesde = $horariocierre;
									}elseif($horadesde<$horarioinicio){
										$horadesde = $horarioinicio;
									}else{
										$horadesde = $horadesde;
									}
									$horaA14 = diferencia($row14['fechadesde'],$horadesde,$row14['fechadesde'],$horariocierre);						
									$horahasta = $row14['horahasta'];
									if($horahasta > $horariocierre){
										$horahasta = $horariocierre;
									}elseif($horahasta<$horarioinicio){
										$horahasta = $horarioinicio;
									}else{
										$horahasta = $horahasta;
									}
									debugL('$horahasta:'.$horahasta); 
									 $horaB14 = diferencia($row14['fechahasta'],$horarioinicio,$row14['fechahasta'],$horahasta);						
									//if($row14['horahasta'] > $horariocierre){ 
										// $horaB14 = diferencia($row14['fechahasta'],$horarioinicio,$row14['fechahasta'],$row14['horahasta']);						
									/* }else{
										debugL("CUATRO 14");
									}  */ 
								}						
							}else{ 
								if($row14['fechahasta'] == ''){
									$horadesde = $row14['horadesde'];
									if($horadesde > $horariocierre){
										$horadesde = $horariocierre;
									}elseif($horadesde<$horarioinicio){
										$horadesde = $horarioinicio;
									}else{
										$horadesde = $horadesde;
									}
									$horaA14 = diferencia($row14['fechadesde'],$horadesde,$row14['fechadesde'],$horariocierre);						
									if($fecharesolucion != ""){
										if($horaresolucion>$horariocierre){
											$horaresolucion = $horariocierre;
										}elseif($horaresolucion<$horarioinicio){
											$horaresolucion = $horarioinicio;
										}else{
											$horaresolucion = $horaresolucion;
										}
										
										$horaB14 = diferencia($fecharesolucion,$horarioinicio,$fecharesolucion,$horaresolucion);						
									}else{
										if($ahora>$horariocierre){
											$ahora = $horariocierre;
										}elseif($ahora<$horarioinicio){
											$ahora = $horarioinicio;
										}else{
											$ahora = $ahora;
										}
										debugL("ahora:".$ahora);
										if($row14['fechadesde'] == $hoy){
											$horaB14 = '00:00:00';
										}else{
											debugL('J-1');
											$horaB14 = diferencia($hoy,$horarioinicio,$hoy,$ahora);							
										}
									}
									debugL("HORADESDE:".$horadesde);
								}else{
									$horaA14 = diferencia($row14['fechadesde'],$row14['horadesde'],$row14['fechadesde'],$horariocierre);						
									$horaB14 = '00:00:00';
								}
							} 
						
						debugL("HORA A14:".$horaA14);  
						debugL("HORA B14:".$horaB14);  
						$sumaAB14 = suma($horaA14,$horaB14);
						debugL("SUMA A14+B14:".$sumaAB14);
						
						if($diasLaborales14 > 2){
							$diasLaborales14 = $diasLaborales14 - 2;
							debugL('PASO1 -$diasLaborales14:'.$diasLaborales14);
							$horasLaborales14 = $diasLaborales14 * 8;
							$horasLaborales14 = $horasLaborales14.":00:00";
							debugL('$horasLaborales14:'.$horasLaborales14);
						}else{
							$diasLaborales14  = 0;
							$horasLaborales14 = '00:00:00';
							debugL('PASO2 -$diasLaborales14:'.$diasLaborales14);
							debugL('$horasLaborales14:'.$horasLaborales14);
						}
						
						$sumaABG14 = suma($sumaAB14,$horasLaborales14);
						$total14   = $sumaABG14; 
						debugL('TOTAL EN ESTADO:'.$total14);
						$resultado = array(
							'totalestado' => $total14,
							'existeestado'=> $existe14 
						); 
						debugL('$resultado[0]:'.$resultado['totalestado']);
						debugL('$resultado[1]:'.$resultado['existeestado']);
						$valores[] = $total14;   
						
					}else{
						debugL('RSTTT');
					} 
				}else{
					debugL('UVWWW');
					$resultado = array(
						'totalestado' => '00:00:00',
						'existeestado'=> 0 
					);
				} 
				
					debugL('--------------------------FIN PASÓ ESTADO:'.$idestados.'-------------------------------');
			} //Fin while
			
			
			$sumarHorasEstados   = $valores;
			$resultadosSumaHoras =  sumaArray($sumarHorasEstados);
			debugL('VALORES:'.json_encode($valores));
			debugL('SUMAESTADOS:'.$resultadosSumaHoras);
			$count = count($valores);
			if($count>1){
				$resultado = array(
						'totalestado' => $resultadosSumaHoras,
						'existeestado'=> 1 
					);
			}else{
				$resultado = $resultado;
			}
			return $resultado;
		} 
		
		
	}
?>