<?php
	$start_time = microtime(true);
	
	include("../conexion.php");
	

	global $mysqli;
	//SESSION
 	$usuario 		 = $_SESSION['usuario'];
	$nivel 			 = $_SESSION['nivel'];
	//LocalStorage
	$bid 			= $_REQUEST['bid'];
	$bestado		= $_REQUEST['bestado'];
	$bdescripcion 	= $_REQUEST['bdescripcion'];
	$bsolicitante	= $_REQUEST['bsolicitante'];
	$bcreacion 		= $_REQUEST['bcreacion'];
	$bhorac			= $_REQUEST['bhorac'];
	$basignadoa 	= $_REQUEST['basignadoa'];
	$bsitio			= $_REQUEST['bsitio'];
	$bserie			= $_REQUEST['bserie'];
	$bmarca 		= $_REQUEST['bmarca'];
	$bmodelo		= $_REQUEST['bmodelo'];
	
	/** Error reporting */
	//error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);

	
	$usuario 		 = $_SESSION['usuario'];
	$nivel 			 = $_SESSION['nivel'];
	
		$idusuario = $_SESSION['user_id'];
		$query  = " SELECT a.id, c.nombre AS estado, LEFT(a.descripcion,45) as descripcion, a.descripcion as titulott,
					IFNULL(d.nombre, a.solicitante) AS solicitante, a.fechacreacion, a.horacreacion, a.fechacierre, a.asignadoa, e.nombre AS nomusuario, 
					b.nombre AS sitio, f.serie, h.nombre as marca, i.nombre as modelo,a.fecharesolucion, 
					case when a.fechacierre IS NULL OR LENGTH(ltrim(rTrim(a.fechacierre))) > 0
					then a.fechacreacion else a.fechacierre end as fechaorden,a.fechasolicituddesde,a.fechasolicitudhasta,a.horaresolucion,a.origen,a.resueltopor,a.resolucion
					FROM flotassolicitudes a
					LEFT JOIN ambientes b ON a.idambientes = b.id
					LEFT JOIN estados c ON a.idestados = c.id
					LEFT JOIN usuarios d ON a.solicitante = d.correo
					LEFT JOIN usuarios e ON a.asignadoa = e.correo
					LEFT JOIN activos f ON a.idactivos = f.id
					LEFT JOIN marcas h ON f.idmarcas = h.id
					LEFT JOIN modelos i ON f.idmodelos = i.id
					";

	$query  .= " WHERE a.tipo = 'flotas' ";

//	$query .= permisos('correctivos', '', $idusuario);
	//FILTROS MASIVOS
	$where = "";
		$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Solicitud de flotas' AND usuario = '".$_SESSION['usuario']."'";
		$result = $mysqli->query($queryF);
		if($result->num_rows >0){
			$rowF = $resultF->fetch_assoc();
			if (!isset($_REQUEST['data'])) {
				$data = $rowF['filtrosmasivos'];
			}
		}

		
		if($data != ''){
			$data = json_decode($data);
			if(!empty($data->desdef)){
				$desdef = json_encode($data->desdef);
				$where .= " AND a.fechasolicituddesde >= $desdef  ";
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where .= " AND a.fechasolicituddesde <= $hastaf ";
			}
			if(!empty($data->serief)){
				$serief = json_encode($data->serief);
				if($serief != '[""]'){
					$where .= " AND a.idactivos IN ($serief)";
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
				    $estadof = str_replace('"',"",$estadof);
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
			if(!empty($data->fechadevolucionf)){
				$fechadevolucionf = json_encode($data->fechadevolucionf);
				$where .= " AND a.fecharesolucion = $fechadevolucionf ";
			}
			$vowels = array("[", "]");
			$where = str_replace($vowels, "", $where);
		}
		
	//LocalStorage
	/*
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
	*/
	$query  .= " $where ORDER BY a.id DESC ";
//	echo $query;
	//$query  .= " LIMIT 1 ";

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
		if ($row['fecharesolucion'] != '') {
			$xfecharesolucion = date_create_from_format('Y-m-d', $row['fecharesolucion']);
			$xfecharesolucion = date_format($xfecharesolucion, "m/d/Y");
		}

		$numeroreq = str_pad($row['id'], 4, "0", STR_PAD_LEFT);
		$arr = array();
		$arr [] = $numeroreq;
		$arr [] = $row['estado'];
		$arr [] = $row['descripcion'];
		$arr [] = $row['solicitante'];
		$arr [] = $xfechacreacion.' '.$row['horacreacion'];
		$arr [] = $row['fechasolicituddesde'];
		$arr [] = $row['fechasolicitudhasta'];
		$arr [] = $asignadoaN; 
		$arr [] = $row['sitio'];
		$arr [] = $row['serie'];
		$arr [] = $row['marca'];
		$arr [] = $row['modelo'];
		$arr [] = $row['origen'];
		$arr [] = $row['resueltopor'];
		$arr [] = $row['resolucion'];
		$arr [] = $xfecharesolucion.' '.$row['horaresolucion'];
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
	$sheet->setTitle('Solicitudes de Flotas'); 
	$style = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
	);
	
	$spreadsheet->getActiveSheet()->setCellValue('A1', 'Reporte de Solicitudes de Flotas'); 
	$spreadsheet->getActiveSheet()->mergeCells('A1:AP1');
	$spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
	$spreadsheet->getActiveSheet()->getStyle("A1")->applyFromArray($style);
	 
	$titles = array(
		'# Solicitudes',
		'Estado',
		'Motivo',
		'Solicitante',
		'Fecha de creación',
		'Fecha solicitud desde',
		'Fecha solicitud hasta',
		'Conductor',
		'Destino',
		'Placa',
		'Marca',
		'Modelo',
		'Origen',
	    'Devuelto por',
	    'Estado en el cual entrega el auto',
		'Fecha de devolución'
	); 
	 
	$spreadsheet->getActiveSheet()->getStyle('A4:AP4')->getFont()->setBold(true);
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
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
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
	$hoy = date('dmY'); 
	
	$nombreArc = 'Solicitudes de flotas - '.$hoy.'.xlsx';
	// redirect output to client browser
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Type: application/vnd.ms-excel'); //xls
	header('Content-Disposition: attachment;filename='.$nombreArc);
	header('Cache-Control: max-age=0');	
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->setPreCalculateFormulas(false);
	$writer->save('php://output');

?>