<?php
	include("../conexion.php");
	require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';
	//load phpspreadsheet class using namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//call xlsx writer class to make an xlsx file
use PhpOffice\PhpSpreadsheet\IOFactory;
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
	$bfechar		= $_REQUEST['bfechar'];
	$bempresa 		= $_REQUEST['bempresa'];
	$bdepartamento	= $_REQUEST['bdepartamento'];
	$bcliente 		= $_REQUEST['bcliente'];
	$bproyecto		= $_REQUEST['bproyecto'];
	$bcategoria 	= $_REQUEST['bcategoria'];	
	$bsubcategoria	= $_REQUEST['bsubcategoria'];
	$basignadoa 	= $_REQUEST['basignadoa'];
	$bambiente		= $_REQUEST['bambiente']; 
	$bprioridad 	= $_REQUEST['bprioridad'];
	$bcierre		= $_REQUEST['bcierre'];  	
	/** Error reporting */
	//error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE); 
	//date_default_timezone_set('Europe/London');
	
	$id 	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
	$data 	 = '';
	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

 
//make a new spreadsheet object
$spreadsheet = new Spreadsheet();
//obtener la hoja activa actual, (que es la primera hoja)
$sheet = $spreadsheet->getActiveSheet(); 
$sheet->setTitle('Postventas');								 
$fontColor = new \PhpOffice\PhpSpreadsheet\Style\Color();
	$fontColor->setRGB('ffffff'); 
	$style = array(
			'alignment' => array( 
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
	);
	// Set document properties
	$spreadsheet->getProperties()->setCreator("Maxia Latam")
	->setLastModifiedBy("Maxia Latam")
	->setTitle("Reporte de postventas")
	->setSubject("Reporte de postventas")
	->setDescription("Reporte de Postventas")
	->setKeywords("Reporte de Postventas")
	->setCategory("Reportes"); 			   
	//TITULO	
	$spreadsheet->getActiveSheet()->setCellValue('A1', 'Reporte de Postventas');
	$spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
	$spreadsheet->getActiveSheet()->getStyle("A1")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->mergeCells('A1:AA1');
	 
	// ENCABEZADO 
		$spreadsheet->getActiveSheet()
		->setCellValue('A4', '# Visita')
		->setCellValue('B4', 'Titulo')
		->setCellValue('C4', 'Objetivo de la visita')
		->setCellValue('D4', 'Cliente')
		->setCellValue('E4', 'Proyecto')
		->setCellValue('F4', 'Estado') 
		->setCellValue('G4', 'Categoría') 
		->setCellValue('H4', 'Ubicación')
		->setCellValue('I4', 'Prioridad') 
		->setCellValue('J4', 'Creado por')
		->setCellValue('K4', 'Solicitante')
		->setCellValue('L4', 'Asignado a')
		->setCellValue('M4', 'Departamento') 
		->setCellValue('N4', 'Resolución') 	
		->setCellValue('O4', 'Fecha de creación')
		->setCellValue('P4', 'Hora de creación')
		->setCellValue('Q4', 'Fecha de resolución')
		->setCellValue('R4', 'Hora de resolución')
		->setCellValue('S4', 'Fecha de cierre')
		->setCellValue('T4', 'Hora de cierre')
		//->setCellValue('V4', 'Fecha de vencimiento')
		->setCellValue('U4', 'Hora de vencimiento')
		->setCellValue('V4', 'Fecha real')
		->setCellValue('W4', 'Hora de real')		
		->setCellValue('X4', 'Tiempo de servicio')
		->setCellValue('Y4', 'Horas Trabajadas') 
		->setCellValue('Z4', 'Compromiso') 
		->setCellValue('AA4', 'Usuario') 
		->setCellValue('AB4', 'Visibilidad') 
		->setCellValue('AC4', 'Fecha Compromiso') 
		->setCellValue('AD4', 'Resolución Compromiso') 
		->setCellValue('AE4', 'Estado Compromiso') 
		;
	
	$spreadsheet->getActiveSheet()->getStyle('A4:AE4')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle("A4:AE4")->applyFromArray($style);
	$spreadsheet->getActiveSheet()->getStyle('A4:AE4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('1F3D7B');
	//SENTENCIA BASE
	$query  = " SELECT a.id, e.nombre AS estado, LEFT(a.titulo,45) as titulo, IFNULL(j.nombre, a.solicitante) AS solicitante, a.fechacreacion,
				a.horacreacion, a.fechacierre, b.nombre AS proyecto, f.nombre AS categoria, g.nombre AS subcategoria, a.asignadoa, 
				l.nombre AS nomusuario, c.nombre AS ambiente, m.serie, mar.nombre as marca, r.nombre as modelo, m.modalidad, h.prioridad, 
				a.fecharesolucion, a.fechareal,	IFNULL(a.fechacierre,a.fechacreacion) AS fechaorden, n.descripcion as empresa, 
				o.nombre as departamento, p.nombre as cliente, a.horaresolucion, a.descripcion, us.nombre AS creadopor, a.resolucion, a.horacierre,
				a.horavencimiento, a.horareal, a.horastrabajadas, co.comentario AS compromiso, co.visibilidad, us.nombre AS nombreusuario, co.fecha AS fechacompromiso,
				co.estado AS estadocompromiso, co.resolucion AS resolucioncompromiso
				FROM postventas a
				LEFT JOIN proyectos b ON a.idproyectos = b.id
				LEFT JOIN ambientes c ON a.idambientes = c.id
				LEFT JOIN estados e ON a.idestados = e.id
				LEFT JOIN categorias f ON a.idcategorias = f.id
				LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
				LEFT JOIN sla h ON a.idprioridades = h.id
				LEFT JOIN usuarios j ON a.solicitante = j.correo
				LEFT JOIN usuarios l ON a.asignadoa = l.correo
				LEFT JOIN usuarios uc ON a.asignadoa = uc.correo
				LEFT JOIN activos m ON a.idactivos = m.id
				LEFT JOIN empresas n ON a.idempresas = n.id
				LEFT JOIN departamentos o ON a.iddepartamentos = o.id
				LEFT JOIN clientes p ON a.idclientes = p.id
				LEFT JOIN marcas mar ON m.idmarcas = mar.id
				LEFT JOIN modelos r ON m.idmodelos = r.id
				LEFT JOIN compromisos co ON a.id = co.idmodulo
				LEFT JOIN usuarios us ON co.usuario = us.usuario
				";
	//debug($query);
	if($nivel != 1 && $nivel != 2){
		$query .= " LEFT JOIN usuarios q ON find_in_set(c.id, q.idambientes) AND q.usuario = '".$usuario."' ";
	}
	$query  .= " WHERE 1 ";
	
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
		}else{
			//$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
		}			
	}
	
	//DATOS 
	$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Postventas' AND usuario = '".$_SESSION['usuario']."'";
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
				$where2 .= " AND a.idcategorias IN ($categoriaf)";
			}
		}
		if(!empty($data->subcategoriaf)){
			$subcategoriaf = json_encode($data->subcategoriaf);
			if($subcategoriaf != '[""]'){
				$where2 .= " AND a.idsubcategorias IN ($subcategoriaf)";
			}
		}			
		if(!empty($data->idempresasf)){
			$idempresasf = json_encode($data->idempresasf);
			if($idempresasf != '[""]'){
				$where2 .= " AND a.idempresas IN ($idempresasf)"; 
			}				
		}
		if(!empty($data->iddepartamentosf)){
			$iddepartamentosf = json_encode($data->iddepartamentosf);
			$where2 .= " AND a.iddepartamentos IN ($iddepartamentosf)"; 
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
		if(!empty($data->prioridadf)){
			$prioridadf = json_encode($data->prioridadf);
			if($prioridadf != '[""]'){
				$where2 .= " AND a.idprioridades IN ($prioridadf)";
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
				$where2 .= " AND a.idestados IN ($estadof)";
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
		if(!empty($data->idambientesf)){
			$idambientesf = json_encode($data->idambientesf);
			 if($idambientesf !== '[""]'){ 
				$where2 .= " AND a.idambientes IN ($idambientesf)";
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
		$where2 .= " AND e.nombre LIKE '%".$bestado."%' ";
	}
	if($btitulo != ''){
		$where2 .= " AND a.titulo LIKE '%".$btitulo."%' ";
	}
	if($bsolicitante != ''){
		$where2 .= " AND j.nombre LIKE '%".$bsolicitante."%' ";
	}
	if($bcreacion != ''){
		$where2 .= " AND a.fechacreacion LIKE '%".$bcreacion."%' ";
	}
	if($bhorac != ''){
		$where2 .= " AND a.horacreacion LIKE '%".$bhorac."%' ";
	}
	if($bfechar != ''){
		$where2 .= " AND a.fechareal LIKE '%".$bfechar."%' ";
	}
	if($bempresa != ''){
		$where2 .= " AND n.descripcion LIKE '%".$bempresa."%' ";
	}
	if($bdepartamento != ''){
		$where2 .= " AND o.nombre LIKE '%".$bdepartamento."%' ";
	}
	if($bcliente != ''){
		$where2 .= " AND p.nombre LIKE '%".$bcliente."%' ";
	}		
	if($bproyecto != ''){
		$where2 .= " AND b.nombre LIKE '%".$bproyecto."%' ";
	}
	if($bcategoria != ''){
		$where2 .= " AND f.nombre LIKE '%".$bcategoria."%' ";
	}
	if($bsubcategoria != ''){
		$where2 .= " AND g.nombre LIKE '%".$bsubcategoria."%' ";
	}		
	if($basignadoa != ''){
		$where2 .= " AND l.nombre LIKE '%".$basignadoa."%' ";
	}
	if($bambiente != ''){
		$where2 .= " AND c.nombre LIKE '%".$bambiente."%' ";
	} 
	if($bprioridad != ''){
		$where2 .= " AND h.prioridad LIKE '%".$bprioridad."%' ";
	}
	if($bcierre != ''){
		$where2 .= " AND a.fecharesolucion LIKE '%".$bcierre."%' ";
	}
	
	//CUERPO
	//Definir fuente
	$spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);					
	
	$query  .= " $where2 ORDER BY a.id desc ";
	debugL('EXPORTAR POSTVENTAS: '.$query);
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
		$xfechacierre = $row['fechacierre'];
		$xfechareal = $row['fechareal'];
		
		if ($row['fechacreacion']!='') {
			$xfechacreacion = date_create_from_format('Y-m-d', $row['fechacreacion']);
			$xfechacreacion = date_format($xfechacreacion, "m/d/Y");
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
		$spreadsheet->getActiveSheet()
		->setCellValue('A'.$i, $numeroreq)
		->setCellValue('B'.$i, $row['titulo'])
		->setCellValue('C'.$i, $row['descripcion'])
		->setCellValue('D'.$i, $row['cliente'])
		->setCellValue('E'.$i, $row['proyecto'])
		->setCellValue('F'.$i, $row['estado']) 
		->setCellValue('G'.$i, $row['categoria']) 			
		->setCellValue('H'.$i, $row['ambiente'])
		->setCellValue('I'.$i, $row['prioridad']) 								
		->setCellValue('J'.$i, $row['creadopor'])
		->setCellValue('K'.$i, $row['solicitante'])
		->setCellValue('L'.$i, str_replace(',',' ', $asignadoaN))
		->setCellValue('M'.$i, $row['departamento']) 
		->setCellValue('N'.$i, $row['resolucion'])  
		->setCellValue('O'.$i, $xfechacreacion) 
		->setCellValue('P'.$i, $row['horacreacion'])
		->setCellValue('Q'.$i, $xfecharesolucion)
		->setCellValue('R'.$i, $row['horaresolucion'])
		->setCellValue('S'.$i, $xfechacierre) 
		->setCellValue('T'.$i, $row['horacierre'])
																																		  
		->setCellValue('U'.$i, $row['horavencimiento'])
		->setCellValue('V'.$i, $xfechareal) 
		->setCellValue('W'.$i, $row['horareal'])
		->setCellValue('X'.$i, $dif)
		->setCellValue('Y'.$i, $row['horastrabajadas'])
		->setCellValue('Z'.$i, $row['compromiso'])
		->setCellValue('AA'.$i, $row['nombreusuario'])
		->setCellValue('AB'.$i, $row['visibilidad'])
		->setCellValue('AC'.$i, $row['fechacompromiso'])
		->setCellValue('AD'.$i, $row['resolucioncompromiso'])
		->setCellValue('AE'.$i, $row['estadocompromiso'])
		;
		
		//ESTILOS
		$spreadsheet->getActiveSheet()->getStyle('A'.$i.':AB'.$i)->getFont()->setSize(10);
		$spreadsheet->getActiveSheet()->getStyle('A'.$i.':AB'.$i)->getAlignment()->applyFromArray(
					array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
		$spreadsheet->getActiveSheet()->getStyle('P'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
		$spreadsheet->getActiveSheet()->getStyle('P'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
		$spreadsheet->getActiveSheet()->getStyle('U'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
		$spreadsheet->getActiveSheet()->getStyle('U'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
		$spreadsheet->getActiveSheet()->getStyle('Q'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
		$spreadsheet->getActiveSheet()->getStyle('Q'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
		$spreadsheet->getActiveSheet()->getStyle('S'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
		$spreadsheet->getActiveSheet()->getStyle('S'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
		$spreadsheet->getActiveSheet()->getStyle('W'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)); 
		$i++;
	}

	//Ancho automatico
	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(60);
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true); 
	$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(50);
	$spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(18);
	$spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(24);
	$spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth(24);
	$spreadsheet->getActiveSheet()->getColumnDimension('X')->setWidth(18);
	$spreadsheet->getActiveSheet()->getColumnDimension('Y')->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension('Z')->setWidth(50);
	$spreadsheet->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);  
	$spreadsheet->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);  
	$spreadsheet->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);  
	$spreadsheet->getActiveSheet()->getColumnDimension('AD')->setWidth(50); 
	$spreadsheet->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true); 
	$hoy = date('dmY');
	$nombreArc = 'Postventas - '.$hoy.'.xlsx';
	// redirect output to client browser								 
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename='.$nombreArc);
	header('Cache-Control: max-age=0');
	//create IOFactory object
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	//save into php output
	$writer->save('php://output'); 
?>