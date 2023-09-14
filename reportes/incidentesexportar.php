<?php
	$start_time = microtime(true);
	
	include("../conexion.php");
	

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
				a.horaresolucion, a.horastrabajadas, p.nombre as cliente, a.fechadesdefueraservicio, a.fechafinfueraservicio, 
				a.fueraservicio, (CASE WHEN a.fechafinfueraservicio is null then (TIMESTAMPDIFF(DAY, a.fechadesdefueraservicio, CURRENT_DATE)) ELSE (TIMESTAMPDIFF(DAY, a.fechadesdefueraservicio, a.fechafinfueraservicio)) END) as diasfueraservicio,  
				nu.nombre AS resueltopor, a.atencion, a.idestados,
				CONCAT(ie.fechadesde, ' ', ie.horadesde) AS estadoasignado,
				CONCAT(iep.fechadesde, ' ', iep.horadesde) AS estadoenproceso, et.nombre AS etiqueta,
				ci.fecha AS primercomentario
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
				LEFT JOIN etiquetas et ON et.id = a.idetiquetas
				LEFT JOIN
					(
						SELECT idincidentes, fechadesde, horadesde FROM incidentesestados where estadonuevo = 33 ORDER BY id DESC LIMIT 1) iep ON a.id = iep.idincidentes
				LEFT JOIN 
					(
						SELECT idincidentes, fechadesde, horadesde FROM incidentesestados ie WHERE estadonuevo = 13 ORDER BY id DESC LIMIT 1
					) ie ON a.id = ie.idincidentes
				LEFT JOIN (
						SELECT nombre, correo FROM usuarios
					) nu ON a.resueltopor = nu.correo
				LEFT JOIN (
						SELECT fecha, idmodulo FROM comentarios ORDER BY id ASC LIMIT 1
					) ci ON a.id = ci.idmodulo
				";
	$idusuario=$_SESSION['idusuario'];
	
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
				$estadof = str_replace('"','',$estadof);
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
	if($betiqueta != ''){
		$where .= " AND et.nombre = '".$betiqueta."' ";
	}
	$query  .= " $where ORDER BY a.id DESC ";
	//$query  .= " LIMIT 1 ";
	debugL("EXPORTAR REGISTROS:".$query,"INCIDENTES-EXPORTAR");
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
		//debugL('insumarhoras:'.$horas);
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
		//debugL("HORA:".$horat."-MINUTOS:".$mint."-SEGUNDOS:".$segt); 
		return $horat.":".$mint.":".$segt;
	}
	/* function sumarHoras($horas) {		
		$total = 0;
		//debugL('insumarhoras:'.$horas);
		foreach($horas as $h) {
			//debugL('sumarHoras - $horas: '.$h);
			$parts = explode(":", $h);
			//debugL('parts:'.json_encode($parts));
			$total += $parts[2] + $parts[1]*60 + $parts[0]*3600;   
			 
		}
		//debugL('TOTALENFUNCIÓN:'.$total);
		return gmdate("H:i:s", $total);
	} */ 
	$result = $mysqli->query($query);
	$i = 5; 
	$inicio = $i;
	//debugL("PASÓ","ANTES DE WHILE");
	$arrayData = [];
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
		
		/* if($row['estadoant'] == 1){
			//LETRA
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':AQ'.$i)->getFont()->setSize(12)->setColor($fontColor);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':AQ'.$i)->applyFromArray($style);
			//FONDO
			$spreadsheet->getActiveSheet()->getStyle('A'.$i.':AM'.$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b2cbea');
		} */
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
		$idestadosInc    = $row['idestados'];
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
		$fechatope 		= str_replace('"','',$hastaf);
		$fechaespera    = date('Y-m-d');
		$esMayorHorasA  = 0;
		$esMayorHorasB  = 0;
		$verHoraInicio  = new DateTime($horarioinicio);
		$verHoraCierre  = new DateTime($horariocierre);
		//debugL('----------------------------------------INICIO----------------------------------------------------'); 
		//Variables nuevas
		$horaA     = 0;
		$horaB 	   = 0;
		$horaX 	   = 0;
		$tipoAyB   = 0;
		
		//debugL('INCIDENTE: '.$row['id']); 
		//Si fecha creación es igual a fecha resolución
		if($row['fechacreacion'] == $row['fecharesolucion']){
			if($row['horaresolucion'] > $horariocierre){
				$row['horaresolucion'] = $horariocierre; 
			} 
			if($row['horaresolucion'] < $row['horacreacion']){
				$horaX = "00:00:00";
			}else{
				$horaX = diferencia($row['fechacreacion'],$row['horacreacion'],$row['fecharesolucion'],$row['horaresolucion']); 
			}
		    
			//debugL('HORA X:'.$horaX); 
			//debugL('111111'); 
		//Si fecha creación es igual a fecha tope y fecha resolucion es vacía
		}elseif(($row['fechacreacion'] == $fechatope) && $row['fecharesolucion'] == ''){
			$horaX = diferencia($row['fechacreacion'],$row['horacreacion'],$fechatope,$horariocierre); 
			//debugL('HORA X:'.$horaX);
			//debugL('222222');
		}else{
		    //debugL('333333');
		    if($row['fecharesolucion'] == ''){ 
    			if($hastaf != ''){
    				$fechatope = str_replace('"','',$hastaf);
					//debugL('A');
    			}else{
    				$fechatope = $hoy;
					//debugL('B');
    			} 
    			if($fechatope != $hoy){
    				$horatope  = $horariocierre;
					//debugL('C');
    			}else{
    				$horatope  = $ahora;
					//debugL('D');
    			}
    		}else{
				if($hastaf != ''){
					//debugL('E');
					$fechatope = str_replace('"','',$hastaf);
					if($row['fecharesolucion'] <= $fechatope){
						$fechatope = $row['fecharesolucion'];
						$horatope  = $row['horaresolucion'];
						//debugL('E-1');
					}else{
						$fechatope = $fechatope;
						$horatope  = $horariocierre;
						//debugL('E-2');
					}
					
				}else{ 
					$fechatope = $row['fecharesolucion'];
					$horatope = $row['horaresolucion'];
					//debugL('F');
				} 
    		} 
			 
			/* if($idestadosInc == 14 || $idestadosInc == 18 || $idestadosInc == 42){
				$queryV = " SELECT fechadesde FROM incidentesestados 
				WHERE idincidentes = ".$row['id']." AND estadonuevo = ".$idestadosInc." 
				ORDER BY id DESC LIMIT 1";
				//debugL('queryV:'.$queryV);
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
				//debugL('UNO');
				$horaA = diferencia($row['fechacreacion'],$row['horacreacion'],$row['fechacreacion'],$horariocierre);
			}
			
			if($row['fechacreacion'] == $fechatope){
				$horaB = 0;
			}else{
				//debugL('DOS');
				$horaB = diferencia($fechatope,$horarioinicio,$fechatope,$horatope);
			}
			//debugL("HORA A:".$horaA);  
			//debugL("HORA B:".$horaB);  
			$sumaAB = suma($horaA,$horaB);
			//debugL("SUMA A+B:".$sumaAB);  
		} 
		//debugL('FECHA TOPE:'.$fechatope);
		$diasLaborales = obtenerDiasLaborales($row['fechacreacion'],$fechatope,$festivos);
		//debugL('diasLaborales:'.$diasLaborales);  
		 
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
				
				//debugL('PASO1 -diasLaborales:'.$diasLaborales);
				$horasLaboralesGeneral = $diasLaborales * 8;
				$horasLaboralesGeneral = $horasLaboralesGeneral.":00:00";
				//debugL('$horasLaboralesGeneral:'.$horasLaboralesGeneral);
			}else{
				$diasLaborales 		   = 0;
				$horasLaboralesGeneral = '00:00:00';
				//debugL('PASO2 -diasLaborales:'.$diasLaborales);
				//debugL('$horasLaboralesGeneral:'.$horasLaboralesGeneral);
			}
		}else{
			$diasLaborales = 0;
			//debugL('PASO3 -diasLaborales'.$diasLaborales);
		}
		
		
		if($tipoAyB == 1){
			$sumaABG = suma($sumaAB,$horasLaboralesGeneral);
			$totalGeneral = $sumaABG;
			//debugL('PASO4');
		}else{
			$totalGeneral = $horaX;
			//debugL('PASO5');
		}	 
		
		//Total General
		//debugL("totalGeneral:".$totalGeneral);
		
		$horas14 = '00:00:00';
		$horas18 = '00:00:00';
		$horas42 = '00:00:00';
		
		$getHoras14 = obtenerHorasEstados($horariocierre,$horarioinicio,$row['id'],14,$hastaf,$festivos,$row['fechacreacion'],$row['fecharesolucion'],$row['horaresolucion']);
		if($getHoras14['existeestado'] == 1){
			$horas14 = $getHoras14['totalestado'];
		} 
		 //debugL('$horas14:'.$horas14);
		 //debugL('$getHoras14:'.json_encode($getHoras14));
		 
		$getHoras18 = obtenerHorasEstados($horariocierre,$horarioinicio,$row['id'],18,$hastaf,$festivos,$row['fechacreacion'],$row['fecharesolucion'],$row['horaresolucion']);
		//debugL('getHoras18[0]:'.$getHoras18[0]);
		//debugL('getHoras18[1]:'.$getHoras18[1]);
		if($getHoras18['existeestado'] == 1){
			$horas18 = $getHoras18['totalestado'];
		} 
		
		$getHoras42 = obtenerHorasEstados($horariocierre,$horarioinicio,$row['id'],42,$hastaf,$festivos,$row['fechacreacion'],$row['fecharesolucion'],$row['horaresolucion']);
		if($getHoras42['existeestado'] == 1){
			$horas42 = $getHoras42['totalestado'];
		}  
		
		$suma1418   = suma($horas14,$horas18);
		//debugL('suma1418'.$suma1418);
		$suma141842 = suma($suma1418,$horas42);
		$horasNoServicio = $suma141842;
		//debugL('horasNoServicio'.$horasNoServicio);
		$totalFinal = resta($totalGeneral,$horasNoServicio);
		//debugL('totalFinal: '.$totalFinal);
		 
		if( $row['fueraservicio']== 1 ){
			$fueraservicio = 'Si';
		}else{
			$fueraservicio = 'No';
		} 
		//Si está fuera de servicio se restan 10 días del tiempo de servicio
		if($row['fueraservicio']== 1){
			$totalFinal = resta($totalFinal,'80:00:00');
		} 
		
		$numeroreq = str_pad($row['id'], 4, "0", STR_PAD_LEFT);
		$arr = array();
		$arr [] = $numeroreq;
		$arr [] = $row['titulo'];
		$arr [] = strip_tags($row['descripcion']);
		$arr [] = $row['cliente'];
		$arr [] = $row['proyecto'];
		$arr [] = $row['estado'];
		$arr [] = $row['equipo'];
		$arr [] = $row['serie'];
		$arr [] = $row['activo'];
		$arr [] = $row['marca'];
		$arr [] = $row['modelo'];
		$arr [] = $row['modalidad'];
		$arr [] = $row['estadoequipo'];
		$arr [] = $row['categoria'];
		$arr [] = $row['subcategoria'];
		$arr [] = $row['sitio'];
		$arr [] = $row['prioridad']; 
		$arr [] = $row['origen'];
		$arr [] = $row['creadopor'];
		$arr [] = $row['solicitante'];
		$arr [] = $asignadoaN; 
		$arr [] = $row['departamento'];
		$arr [] = $row['resueltopor'];
		$arr [] = $row['resolucion'];
		$arr [] = $row['satisfaccion'];
		$arr [] = $row['comentariosatisfaccion'];
		$arr [] = $xfechacreacion.' '.$row['horacreacion'];
		$arr [] = $fprimercomentario;
		$arr [] = $festadoasignado;
		$arr [] = $festadoenproceso;
		$arr [] = $xfecharesolucion.' '.$row['horaresolucion'];
		$arr [] = $totalFinal;
		$arr [] = $row['horastrabajadas'];
		$arr [] = $fueraservicio;
		$arr [] = $row['fechadesdefueraservicio'];
		$arr [] = $row['fechafinfueraservicio'];
		$arr [] = $row['diasfueraservicio'];
		$arr [] = $atencion;
		$arr [] = $row['etiqueta'];
		$arrayData [] = $arr;
		  					
		$i++; 
	} 
	
	require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';
	
	//load phpspreadsheet class using namespaces
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	//call xlsx writer class to make an xlsx file
	use PhpOffice\PhpSpreadsheet\IOFactory;
	//make a new spreadsheet object
	$spreadsheet = new Spreadsheet();
	 
	//obtener la hoja activa actual, (que es la primera hoja)
	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setTitle('Correctivos'); 
	$style = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
	);
	
	$spreadsheet->getActiveSheet()->setCellValue('A1', 'Reporte de Correctivos'); 
	$spreadsheet->getActiveSheet()->mergeCells('A1:AN1');
	$spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
	$spreadsheet->getActiveSheet()->getStyle("A1")->applyFromArray($style);
	 
	$titles = array(
		'# Correctivos',
		'Título',
		'Descripción',
		'Cliente',
		'Proyecto',
		'Estado',
		'Equipo',
		'Serie',
		'Activo',
		'Marca',
		'Modelo',
		'Tipo',
		'Estado del equipo',
		'Categoría',
		'Subcategoría',
		'Ambiente',
		'Prioridad',
		'Origen',
		'Creado por',
		'Solicitante',
		'Asignado a',
		'Departamento',
		'Resuelto por',
		'Resolución',
		'Satisfacción',
		'Comentario de Satisfacción',
		'Fecha de creación', 
		'Fecha de primer comentario',
		'Fecha de asignación',
		'Fecha de en proceso',
		'Fecha de resolución',
		'Tiempo de servicio',
		'Horas Trabajadas',
		'Fuera de Servicio',
		'Fuera de Servicio desde',
		'Fuera de Servicio hasta',
		'Dias Fuera de Servicio',
		'Atención',
		'Etiqueta'
	); 
	 
	$spreadsheet->getActiveSheet()->getStyle('A4:AN4')->getFont()->setBold(true);
	$spreadsheet->getActiveSheet()
		->fromArray(
			$titles,  // The data to set
			NULL,        // Array values with this value will not be set
			'A4'         // Top left coordinate of the worksheet range where
						 //    we want to set these values (default is A1)
		);
 
	$spreadsheet->getActiveSheet()
		->fromArray(
			$arrayData,  // The data to set
			NULL,        // Array values with this value will not be set
			'A5'         // Top left coordinate of the worksheet range where
						 //    we want to set these values (default is A1)
		);
  			
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
	$spreadsheet->getActiveSheet()->getColumnDimension('AN')->setWidth(20);
	$hoy = date('dmY'); 
	
	$nombreArc = 'Correctivos - '.$hoy.'.xlsx';
	// redirect output to client browser
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Type: application/vnd.ms-excel'); //xls
	header('Content-Disposition: attachment;filename='.$nombreArc);
	header('Cache-Control: max-age=0');	
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	ob_start();
	$writer->setPreCalculateFormulas(false);
	$writer->save('php://output');
	$xlsData = ob_get_contents();
	ob_end_clean();

	$response =  array(
			'name' => $nombreArc,
			'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
		);

	die(json_encode($response));
	
	function obtenerDiasLaborales($startDate,$endDate,$holidays){
		//debugL('---FUNCION DIAS LAB-----');
		//debugL('Fecha Inicio:'.$startDate);
		//debugL('Fecha Fin:'.$endDate); 
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
	  ////debugL('$workingDays:'.$workingDays);
		//debugL('---END FUNCION DIAS LAB-----');
		return $workingDays;
		
	} 
	
	function obtenerHorasEstados($horariocierre,$horarioinicio,$idincidentes,$idestados,$hastaf,$festivos,$fechacreacion,$fecharesolucion,$horaresolucion){ 
		global $mysqli;
		//debugL('idincidentes:'.$idincidentes);
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
		//debugL('$queryHistEstados14:'.$queryHistEstados14);
		$result14 = $mysqli->query($queryHistEstados14); 
		if($result14->num_rows >0){
			$existe14 = 1;
			
			while($row14 = $result14->fetch_assoc()){
				$contar++;
				//debugL('PASÓ EXISTE ESTADO '.$idestados);
				//debugL('-----------------------------INICIO ESTADO:'.$idestados.'-----------------------------------------');
				
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
								//debugL('III');
							}else{
								$diasLaborales14 = obtenerDiasLaborales($row14['fechadesde'],$fechatope,$festivos);
								//debugL('JJJ');
							}
						}else{
							if($row14['fechahasta'] !=""){
								$diasLaborales14 = obtenerDiasLaborales($row14['fechadesde'],$row14['fechahasta'],$festivos);
								//debugL('KKK');
							}else{
								$diasLaborales14 = obtenerDiasLaborales($row14['fechadesde'],$hoy,$festivos);
							}
						}
						//debugL("FECHATOPE:".$fechatope); 
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
										//debugL('A-1');
										$horaA14 = diferencia($fechatope,$row14['horadesde'],$fechatope,$horahasta);
									}
									$horaB14 = '00:00:00';
									//debugL('ANT 1');
								}else{
									if($row14['fechahasta'] != ''){ 
									//debugL('UNO 14');
										if($row14['fechahasta'] <= $fechatope){
												//debugL('UNO 14-2');
												if($row14['horadesde'] > $horariocierre){
													$horaA14 = '00:00:00';
												}else{
													//debugL('B-1');
													$horaA14 = diferencia($row14['fechadesde'],$row14['horadesde'],$row14['fechadesde'],$horariocierre);							
												}
												$horaB14 = diferencia($fechatope,$horarioinicio,$fechatope,$row14['horahasta']);						
										   }else{ 
											//debugL('DOS 14');
											if($row14['horadesde'] > $horariocierre){
												$horaA14 = '00:00:00';
											}else{
												//debugL('C-1');
												$horaA14 = diferencia($row14['fechadesde'],$row14['horadesde'],$row14['fechadesde'],$horariocierre);						
											}
											$horaB14 = '08:00:00';
										}
									}else{
										if($row14['horadesde'] > $horariocierre){
											$horaA14 = '00:00:00';
										}else{
											//debugL('D-1');
											$horaA14 = diferencia($row14['fechadesde'],$row14['horadesde'],$row14['fechadesde'],$horariocierre);						
										}
										$horaB14 = '08:00:00';
										//debugL("ABC");
									}
								} 
							}elseif($row14['fechahasta'] != ''){ 
								if($row14['fechadesde'] == $row14['fechahasta']){
									//debugL('E-1');
									$horaA14 = diferencia($fechatope,$row14['horadesde'],$fechatope,$row14['horahasta']);
									//debugL('ANT2'); 
								}else{
									//debugL('TRES 14'); 
									$horadesde = $row14['horadesde'];
									if($horadesde > $horariocierre){
										$horadesde = $horariocierre;
									}elseif($horadesde<$horarioinicio){
										$horadesde = $horarioinicio;
									}else{
										$horadesde = $horadesde;
									}
									//debugL('F-1');
									$horaA14 = diferencia($row14['fechadesde'],$horadesde,$row14['fechadesde'],$horariocierre);						
									$horahasta = $row14['horahasta'];
									if($horahasta > $horariocierre){
										$horahasta = $horariocierre;
									}elseif($horahasta<$horarioinicio){
										$horahasta = $horarioinicio;
									}else{
										$horahasta = $horahasta;
									}
									//debugL('$horahasta:'.$horahasta); 
									//debugL('G-1');
									 $horaB14 = diferencia($row14['fechahasta'],$horarioinicio,$row14['fechahasta'],$horahasta);						
									//if($row14['horahasta'] > $horariocierre){ 
										// $horaB14 = diferencia($row14['fechahasta'],$horarioinicio,$row14['fechahasta'],$row14['horahasta']);						
									/* }else{
										//debugL("CUATRO 14");
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
									//debugL('H-1');
									$horaA14 = diferencia($row14['fechadesde'],$horadesde,$row14['fechadesde'],$horariocierre);						
									if($fecharesolucion != ""){
										if($horaresolucion>$horariocierre){
											$horaresolucion = $horariocierre;
										}elseif($horaresolucion<$horarioinicio){
											$horaresolucion = $horarioinicio;
										}else{
											$horaresolucion = $horaresolucion;
										}
										//debugL('I-1');
										$horaB14 = diferencia($fecharesolucion,$horarioinicio,$fecharesolucion,$horaresolucion);						
									}else{
										if($ahora>$horariocierre){
											$ahora = $horariocierre;
										}elseif($ahora<$horarioinicio){
											$ahora = $horarioinicio;
										}else{
											$ahora = $ahora;
										}
										//debugL("ahora:".$ahora);
										//error
										if($row14['fechadesde'] == $hoy){
											$horaB14 = '00:00:00';
										}else{
											//debugL('J-1');
											$horaB14 = diferencia($hoy,$horarioinicio,$hoy,$ahora);							
										}
									}
									//debugL("HORADESDE:".$horadesde);
								}else{
									//debugL('K-1');
									$horaA14 = diferencia($row14['fechadesde'],$row14['horadesde'],$row14['fechadesde'],$horariocierre);						
									$horaB14 = '00:00:00';
								}
							} 
						
						//debugL("HORA A14:".$horaA14);  
						//debugL("HORA B14:".$horaB14);  
						$sumaAB14 = suma($horaA14,$horaB14);
						//debugL("SUMA A14+B14:".$sumaAB14);
						
						if($diasLaborales14 > 2){
							$diasLaborales14 = $diasLaborales14 - 2;
							//debugL('PASO1 -$diasLaborales14:'.$diasLaborales14);
							$horasLaborales14 = $diasLaborales14 * 8;
							$horasLaborales14 = $horasLaborales14.":00:00";
							//debugL('$horasLaborales14:'.$horasLaborales14);
						}else{
							$diasLaborales14  = 0;
							$horasLaborales14 = '00:00:00';
							//debugL('PASO2 -$diasLaborales14:'.$diasLaborales14);
							//debugL('$horasLaborales14:'.$horasLaborales14);
						}
						
						$sumaABG14 = suma($sumaAB14,$horasLaborales14);
						$total14   = $sumaABG14; 
						//debugL('TOTAL EN ESTADO:'.$total14);
						$resultado = array(
							'totalestado' => $total14,
							'existeestado'=> $existe14 
						); 
						//debugL('$resultado[0]:'.$resultado['totalestado']);
						//debugL('$resultado[1]:'.$resultado['existeestado']);
						$valores[] = $total14;   
						
					}else{
						//debugL('RSTTT');
					} 
				}else{
					//debugL('UVWWW');
					$resultado = array(
						'totalestado' => '00:00:00',
						'existeestado'=> 0 
					);
				} 
				
					//debugL('--------------------------FIN PASÓ ESTADO:'.$idestados.'-------------------------------');
			} //Fin while
			
			
			$sumarHorasEstados   = $valores;
			$resultadosSumaHoras =  sumaArray($sumarHorasEstados);
			//debugL('VALORES:'.json_encode($valores));
			//debugL('SUMAESTADOS:'.$resultadosSumaHoras);
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