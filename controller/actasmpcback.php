<?php
	include_once("../conexion.php");
	global $mysqli;
	/** Include FPDF */
	require_once dirname(__FILE__) . '/../../repositorio-lib/fpdf/fpdf.php';
	
	//$periodo 	= $_REQUEST['periodo'];
	//$unidadeje 	= $_REQUEST['unidad'];
	$desde 		= $_REQUEST['desde'];
	$hasta 		= $_REQUEST['hasta'];
	$fechacertificar = implode('/',array_reverse(explode('-', $hasta)));
	/*
	$query  = "SELECT fechacertificar FROM incidentes WHERE 1 = 1 ";
	if($desde != ""){
		$query  .= " AND fechacierre >= '".$desde."' ";
	}
	if($hasta != ""){
		$query  .= " AND fechacierre <= '".$hasta."' ";
	}
	$query  .= "  LIMIT 1 ";
	//debug($query);
	$result = $mysqli->query($query);
	while($row = $result->fetch_assoc()){
		$_SESSION['fechacertificar'] = $row['fechacertificar'];
		$fechacertificar = $row['fechacertificar'];
	}
	*/
	//PDF
	class PDF extends FPDF{
		// Cabecera de página
		function Header(){
			include_once("../conexion.php");
			global $mysqli;
			//$unidadeje 	= $_REQUEST['unidad'];
			$desde 		= $_REQUEST['desde'];
			$hasta 		= $_REQUEST['hasta'];
			$fechacertificar = implode('/',array_reverse(explode('-', $hasta)));
	
			 // Logo
			 $this->Image('../../repositorio-tema/assets/img/cssp.jpg',10,6,18); //borde izq, borde sup, ancho
			 // Arial bold 11
			 $this->SetFont('Arial','',11);				 
			 // Título
			 $this->Cell(0,5,'CAJA DE SEGURO SOCIAL',0,1,'C'); //ancho, alto, texto, borde, salto, alineacion				 
			 $this->Cell(0,5,'FORMULARIO DEL SISTEMA DE GESTIÓN DE PROYECTOS',0,1,'C');
			 // Arial bold 11
			 $this->SetFont('Arial','B',8);
			 // Salto de línea
			 $this->Ln(8);
			 $this->Cell(80,4,'BIOMÉDICA NACIONAL','LTR',0,'C');
			 $this->Cell(50,4,'Revisión:','1',0,'C');
			 $this->SetFont('Arial','',8);
			 $this->Cell(60,4,'Original','1',1,'C');
			 $this->SetFont('Arial','B',8);
			 $this->Cell(80,4,'FORMULARIO DE ACEPTACIÓN','LR',0,'C');			 
			 $this->Cell(50,8,'Pagina:','1',0,'C');
			 $this->SetFont('Arial','',8);
			 $this->Cell(60,8,$this->PageNo().' de {nb}','1',1,'C');
			 $this->SetFont('Arial','B',8);
			 $this->SetY(36);
			 $this->Cell(80,4,'DE ENTREGABLE MENSUAL','LBR',1,'C');
			
			 $this->Cell(80,8,'PROYECTO: TELERADIOLOGÍA','LRB',0,'C');
			 $this->Cell(50,4,'Fecha:','LTR',0,'C');
			 $this->SetFont('Arial','',8);			 
			 if($fechacertificar != ''){
				 $ffechacertificar = implode('/',array_reverse(explode('-', $hasta)));
			 }else{
				 $ffechacertificar = '';
			 }
			 $this->Cell(60,4,$ffechacertificar,'TR',1,'C');
			 //$this->Cell(60,4,'20/05/2020','TR',1,'C');
			 $this->Cell(80);
			 $this->SetFont('Arial','B',8);	
			 $this->Cell(110,4,'Documento Nivel III',1,1,'C');
			 $this->SetFont('Arial','',8);
			 $this->Cell(80,5,'Elaborado: Biomédica Nacional','LRB',0,'C');
			 $this->Cell(110,5,'Aprobado: Dirección del Proyecto / Coordinación de Radiología','LRB',1,'C');
			 $this->Ln(10);
		}
	
		// Pie de página
		function Footer(){				
			 //Firmas
			 $this->SetY(-10);
			 $this->SetFont('Arial','',10);				 
			 $this->Cell(0,4,'“Al imprimir este documento será copia no controlada”',0,1,'C');
		}
	}
		
	//Creación del objeto de la clase heredada
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	$pdf->SetFont('Arial','B',14);
	$pdf->Cell(0,5,'Mantenimientos Preventivos y Correctivos Mensuales',0,1,'C');
	$pdf->Ln(10);
	
	$pdf->SetFont('Arial','B',12);
	
	$pdf->Cell(0, 5,'1. Aprobación',0,1,'L');
	$faprobacion = implode('/',array_reverse(explode('-', $hasta)));
	//$faprobacion = '20/05/2020';
	$pdf->Ln(5);			
	
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(54, 8,'','LTR',0,'C');
	$pdf->Cell(50, 8,'Nombre','TR',0,'C');
	$pdf->Cell(30, 8,'Unidad','TR',0,'C');
	$pdf->Cell(30, 8,'Firma','TR',0,'C');
	$pdf->Cell(26, 8,'Fecha','TR',1,'C');
	$pdf->SetFont('Arial','',10);
	
	$pdf->Cell(54, 10,'Biomédica Nacional:','LTR',0,'L');
	$w = 50; 		$x = $pdf->GetX();		$y = $pdf->GetY();
	//$pdf->MultiCell(50, 5,'Aurelio Sánchez',1,'C');
	$pdf->Cell(50, 10,'Olmedo Cedeño','TR',0,'C');
	$pdf->SetXY($x+$w,$y);
	$pdf->Cell(30, 10,'CSS','TR',0,'C');
	$pdf->Cell(30, 10,'','TR',0,'C');
	$pdf->Cell(26, 10,$faprobacion,'TR',1,'C');
	
	$pdf->Cell(54, 10,'Coordinación de Radiología:','LTR',0,'L');
	$w = 50; 		$x = $pdf->GetX();		$y = $pdf->GetY();
	$pdf->Cell(50, 10,'Dra. Ilka Guerrero',1,0,'C');
	//$pdf->MultiCell(50, 5,'Dra. Ilka Guerrero',1,'C');
	$pdf->SetXY($x+$w,$y);
	$pdf->Cell(30, 10,'CSS','TR',0,'C');
	$pdf->Cell(30, 10,'','TR',0,'C');
	$pdf->Cell(26, 10,$faprobacion,'TR',1,'C');
	
	$pdf->Cell(54, 10,'Dirección del Proyecto:','LTR',0,'L');
	$w = 50; 		$x = $pdf->GetX();		$y = $pdf->GetY();
	$pdf->Cell(50, 10,'Dra. Lili Weng',1,0,'C');
	//$pdf->MultiCell(50, 5,'Dra. Lili Weng',1,'C');
	$pdf->SetXY($x+$w,$y);
	$pdf->Cell(30, 10,'CSS','TR',0,'C');
	$pdf->Cell(30, 10,'','TR',0,'C');
	$pdf->Cell(26, 10,$faprobacion,'TR',1,'C');
	
	$w = 54; 		$x = $pdf->GetX();		$y = $pdf->GetY();
	$pdf->MultiCell(54, 7.5,'   Gerente de Control de Proyecto:','LTR','L');
	$pdf->SetXY($x+$w,$y);
	$pdf->Cell(50, 15,'José Barahona','TR',0,'C');
	$w = 30; 		$x = $pdf->GetX();		$y = $pdf->GetY();
	$pdf->MultiCell(30, 5,'CONSORCIO CABLE ONDA / EMSA',1,'C');
	$pdf->SetXY($x+$w,$y);
	$pdf->Cell(30, 15,'','TR',0,'C');
	$pdf->Cell(26, 15,$faprobacion,'TR',1,'C');
	
	$pdf->Cell(54, 15,'Centro de Soporte:',1,0,'L');
	$pdf->Cell(50, 15,'Ana Porras / Maylin Aguero',1,0,'C');
	$w = 30; 		$x = $pdf->GetX();		$y = $pdf->GetY();
	$pdf->MultiCell(30, 5,'CONSORCIO CABLE ONDA / EMSA',1,'C');
	$pdf->SetXY($x+$w,$y);
	$pdf->Cell(30, 15,'',1,0,'C');
	$pdf->Cell(26, 15,$faprobacion,1,1,'C');	
	
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',11);
	//2. Aceptación
	$pdf->Cell(0, 5,'2. Aceptación',0,1,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(5);
	$abrequipomodalidad = '';
	$fechaSol = '';
	$mantenimientosPre = '';
		
	$query  = " SELECT a.*, b.nombre AS equipo, c.numero 
				FROM incidentes a
				LEFT JOIN activos b ON a.idactivos = b.id
				LEFT JOIN cuatrimestres c ON (a.periodo = c.periodo OR a.periodo = c.numero)
				WHERE 1 = 1 AND a.idproyectos = 1 AND a.idcategorias IN (10,11,12) ";
	//CATEGORIAS: 10-Tx Mantenimiento Correctivo, 11-Tx Mantenimiento Correctivo usuarios, 12-Tx Mantenimiento Preventivo 
	
	/*$query  = " SELECT a.*, b.equipo, c.numero 
				FROM incidentes a
				LEFT JOIN activos b ON a.serie = b.codequipo
				LEFT JOIN cuatrimestres c ON (a.periodo = c.periodo OR a.periodo = c.numero)
				WHERE 1 = 1 AND a.proyecto = 1 AND a.idcategoria IN (10,11,12) ";*/
	if($desde != ""){
		$query  .= " AND fechacierre >= '".$desde."' ";
	}
	if($hasta != ""){
		$query  .= " AND fechacierre <= '".$hasta."' ";
	}
	$query  .= " AND a.periodo != '' ";
	$result = $mysqli->query($query);
	$i = 0;
	$j = 0;
	$k = 0;
	$numeroaceptacion = '';
	$equipoarr = '';
	$initequipo = 1;
	$mantenimientoarr = '';
	//$fechacertificar  = '';
	while($row = $result->fetch_assoc()){		
		//FECHA SOLICITUD
		$equipomodalidad    = $row['equipo'];
		$arrequipomodalidad = explode(' - ', $equipomodalidad);
		$abrequipomodalidad .= $arrequipomodalidad[0].'|';
		$valor_dia_entrega = implode('/',array_reverse(explode('-', $row['fechacreacion'])));		
		
		if($arrequipomodalidad[0] != $equipoarr){
			if($initequipo == 1){
				$fechaSol .= $arrequipomodalidad[0].': '.$valor_dia_entrega;
			}else{
				$fechaSol .= '|'.$arrequipomodalidad[0].': '.$valor_dia_entrega;
			}
			$initequipo++;
		}else{
			$fechaSol .= ', '.$valor_dia_entrega;
		}
		
		$equipoarr = $arrequipomodalidad[0];
			
		//MANTENIMIENTOS PREVENTIVOS
		$marca 		= $row['marca'];
		$bdmodelo 	= $row['modelo'];
		$arrmodelo 	= explode('/', $bdmodelo);
		$modelo 	= $arrmodelo[0];
		$serie 	= $row['serie'];
		/*
		$bdserie 	= $row['serie'];
		$arrserie 	= explode(' ', $bdserie);
		if($arrserie[0] != ''){
			$serie 		= $arrserie[0];
		}else{
			$serie 		= $arrserie[1];
		}
		*/
		
		$reporte	= $row['reporteservicio'];
		$incidente	= $row['id'];
		$arrequipomodalidad  = explode(' - ', $equipomodalidad);
		if(utf8_decode($arrequipomodalidad[0]).' - '.$marca.' - Modelo '.$modelo.' - Serie '.$serie != $mantenimientoarr){
			$mantenimientosPre 	.= utf8_decode($arrequipomodalidad[0]).' - '.$marca.' - Modelo '.$modelo.' - Serie '.$serie.'|';
			$mantenimientosPre 	.= $reporte.'|';
			$mantenimientosPre 	.= $incidente.'|';		
			$mantenimientosPre 	.= $row['fechacierre'].'|';
			$mantenimientosPre 	.= $row['resolucion'].'|';
			$mantenimientosPre 	.= '^';
		}
		
		$mantenimientoarr = utf8_decode($arrequipomodalidad[0]).' - '.$marca.' - Modelo '.$modelo.' - Serie '.$serie;		
		$numeroaceptacion = 'TR-'.$row['numero'].'-GS-RDM-01';
	}	
	
	//2.1 Fecha para Certificar Aceptación o Rechazo
	$pdf->Cell(0, 5,'2.1 Fecha para Certificar Aceptación o Rechazo',0,1,'L');
	$pdf->Cell(0, 5,'      '.$fechacertificar,1,1,'L');
	//$pdf->Cell(0, 5,'      20/05/2020',1,1,'L');
	$pdf->Ln(10);
	
	//3. Entregable
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0, 5,'3. Entregable',0,1,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(5);
	
	//3.1 Descripción
	$fechainicial = new DateTime('2017-01-01');
	$fechafinal = new DateTime($desde);
	$diferencia = $fechainicial->diff($fechafinal);
	$meses = ( $diferencia->y * 12 ) + $diferencia->m;
	$cmes = 37+$meses;

	$pdf->Cell(0, 5,'3.1 Descripción',0,1,'L');
	$pdf->Cell(0, 8,'      Ejecución satisfactoria del Contrato No. 10071910-08-21 y sus adendas','LTR',1,'L');
	$pdf->Cell(0, 8,'      Mantenimientos Preventivos y Correctivos Realizados – (período del '.$desde.' al '.$hasta.', mes #'.$cmes.')','LRB',1,'L');	
	//$pdf->Cell(0, 8,'      Mantenimientos Preventivos y Correctivos Realizados – (período del '.$desde.' al 2020-05-20, mes #'.$cmes.')','LRB',1,'L');
	$pdf->Ln(5);
	
	//3.2 Criterios o Estándares de Aceptación
	$pdf->Cell(0, 5,'3.2 Criterios o Estándares de Aceptación',0,1,'L');
	$pdf->Cell(0, 6,'      • Mantenimiento Preventivo y Correctivo: verificación del funcionamiento óptimo de los equipos de diagnóstico.','LTR',1,'L');
	$pdf->Cell(0, 6,'      '.chr(129).' Contrato No. 10071910-08-21 (2013-1-10-0-99-LP-105287)','LR',1,'L');
	$pdf->Cell(0, 8,'            o	Claúsula Trigésima Tercera*','LR',1,'L');
	$pdf->Cell(0, 6,'      '.chr(129).' Adenda No. 1 al Contrato No. 10071910-08-21 de TeleRadiología','LR',1,'L');
	$pdf->Cell(0, 8,'             o	Cláusulas Novena y Décima Primera','LRB',1,'L');
	
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(10);
	
	//4. Resultados
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0, 5,'4. Resultados',0,1,'L');
	$pdf->Ln(5);
	//4.1
	//CORRECTIVOS
	$pdf->Cell(0, 5,'4.1 [ Mantenimientos Correctivos ]',0,1,'L');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','',10);	
	
	$i = 1;
	$queryMC  = "SELECT a.id, a.fechacreacion, b.nombre AS unidad, d.nombre AS marca, c.modalidad
				 FROM incidentes a 
				 LEFT JOIN ambientes b ON a.idambientes = b.id 
				 LEFT JOIN activos c ON a.idactivos = c.id
				 LEFT JOIN marcas d ON c.idmarcas = d.id
				 WHERE 1 = 1 AND a.idproyectos = 1 AND a.idcategorias IN (10,11) ";
	//CATEGORIAS: 10-Tx Mantenimiento Correctivo, 11-Tx Mantenimiento Correctivo usuarios
	//Linea 285 WHERE 1 = 1 AND a.proyecto = 1 AND a.idcategoria IN (10,11) ";			 
	
	if($desde != ""){
		$queryMC  .= " AND fechacreacion >= '".$desde."' ";
	}
	if($hasta != ""){
		$queryMC  .= " AND fechacreacion <= '".$hasta."' ";
		//$queryMC  .= " AND fechacreacion <= '2020-05-20' ";
	}
	$queryMC  .= "  GROUP BY a.id ";
	//debug($queryMC);
	
	$resultMC = $mysqli->query($queryMC);
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(10, 8,'No',1,0,'C');
	$pdf->Cell(18, 8,'# Incidente',1,0,'C');
	$pdf->MultiCell(20, 4,'Fecha de Creación',1,'C');
	$pdf->SetY(83);
	$pdf->Cell(48,8,'',0,0);
	$pdf->Cell(80, 8,'Unidad Ejecutora',1,0,'L');
	$pdf->Cell(25, 8,'Marca',1,0,'L');
	$pdf->Cell(40, 8,'Modalidad',1,1,'L');
	$pdf->SetFont('Arial','',9);
	$idc = 1;
	while($rowMC = $resultMC->fetch_assoc()){
		$pdf->Cell(10, 6,$idc,1,0,'C');
		$pdf->Cell(18, 6,$rowMC['id'],1,0,'C');
		$pdf->Cell(20, 6,$rowMC['fechacreacion'],1,0,'C');
		
		//UNIDAD
		$unidad = trim(utf8_decode($rowMC['unidad']));
		$longunidad = strlen($unidad);
		if($longunidad > 54){				
			$w = 80;	$x = $pdf->GetX();	$y = $pdf->GetY();
			$pdf->MultiCell(80, 3,$unidad,1,'C');
			$pdf->SetXY($x+$w,$y);
		}else{
			$pdf->Cell(80, 6, $unidad,1,0,'L');
		}
		//MARCA
		$pdf->Cell(25, 6,utf8_decode($rowMC['marca']),1,0,'L');		
		//MODALIDAD
		$modelo = trim(utf8_decode($rowMC['modalidad']));
		if($modelo == 'CR - Sistema de Digitalización de imágenes Radiográficas'){
			$modelo = 'CR';
		}elseif($modelo == 'DR - Sistema de detector radiografico digital inalámbrico'){
			$modelo = 'DR';
		}
		$longmodelo = strlen($modelo);
		if($longmodelo > 25){				
			$w = 40;	$x = $pdf->GetX();	$y = $pdf->GetY();
			$pdf->MultiCell(40, 3,$modelo,1,'L');
			//$pdf->SetXY($x+$w,$y);
		}else{
			$pdf->Cell(40, 6, $modelo,1,1,'L');
		}
		$idc++;
	}
	
	//4.2
	//PREVENTIVOS
	$pdf->AddPage();
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0, 5,'4.2 [ Mantenimientos Preventivos ]',0,1,'L');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','',10);	
	
	$i = 1;
	$queryMC = "SELECT a.periodo, c.nombre AS equipo, mar.nombre AS marca, c.serie, a.id as incidente, 
				b.codigo, b.nombre as unidadejecutora, a.fecharesolucion, a.periodo, 
				a.reporteservicio 
				FROM incidentes a
				LEFT JOIN ambientes b ON a.idambientes = b.id
				LEFT JOIN activos c ON a.idactivos = c.id
				LEFT JOIN marcas mar ON c.idmarcas = mar.id
				LEFT JOIN cuatrimestres d ON a.fecharesolucion BETWEEN d.fechainicio AND d.fechafin
				WHERE 1 = 1 AND a.idproyectos = 1 AND a.idcategorias = 12 
				AND (a.idestados = 16 || a.idestados = 17) 
				";
	//CATEGORIAS: 12-Tx Mantenimiento Preventivo 
	//ESTADOS: 16-Resuelto, 17-Cerrado
	if($desde != ""){
		$queryMC  .= " AND a.fecharesolucion >= '".$desde."' ";
	}
	if($hasta != ""){
		$queryMC  .= " AND a.fecharesolucion <= '".$hasta."' ";
	}
	$queryMC  .= "  GROUP BY a.id ORDER BY b.nombre, a.fechacierre ASC  ";
	//debug($queryMC);
	$resultMC = $mysqli->query($queryMC);
	$resultAN = $mysqli->query($queryMC);

	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(57, 8,'Equipo / Modalidad',1,0,'C');
	$pdf->Cell(20, 8,'Marca',1,0,'C');
	$pdf->Cell(22, 8,'Serie',1,0,'C');
	$pdf->MultiCell(17, 4,'# Incidente',1,'C');
	$pdf->SetY(78);
	$pdf->Cell(116,8,'',0,0);
	$pdf->Cell(43, 8,'Unidad Ejecutora',1,0,'L');
	$pdf->MultiCell(20, 4,'Fecha del MP',1,'C');
	$pdf->SetY(78);
	$pdf->Cell(179,8,'',0,0);
	$pdf->Cell(12, 8,'Anexo',1,1,'C');
	
	$pdf->SetFont('Arial','',9);
	$count = $resultMC->num_rows;
	$a = 1;	$h = 0;
	if ($count > 0){
		$unidadejecutora = '';
		$arrincidentes[] = '';
		$arrreportes[] = '';
		$countAnexo = 0; $initAnexo = 1;
		while($rowMC = $resultMC->fetch_assoc()){
			if($unidadejecutora == ''){
				$unidadejecutora = $rowMC['unidadejecutora'];
			}			
			if($unidadejecutora != $rowMC['unidadejecutora']){
				$unidadejecutora 	= $rowMC['unidadejecutora'];
				$arrincidentes[] 	= $rowMC['incidente'];
				$arrreportes[] 		= $rowMC['reporteservicio'];
				
				//$pdf->Cell(0, 10,'incidente: '.$rowMC['incidente'].', reporteservicio: '.$rowMC['reporteservicio'] ,0,1,'C');
				//$pdf->Cell(0, 10,'initAnexo: '.$initAnexo.', countAnexo: '.$countAnexo,0,1,'C');
				//ANEXOS				
				for($i = $initAnexo; $i <= $countAnexo; $i++){
					if($arrincidentes[$i] != ''){
						$pdf->AddPage();						
						//$pdf->Cell(0, 10,'ANEXO '.$i ,0,1,'C');
						//$pdf->Cell(0, 10,'incidente: '.$rowMC['incidente'].', reporteservicio: '.$rowMC['reporteservicio'].', $arrincidentes[$i]: '.$arrincidentes[$i] ,0,1,'C');
						//$anexo = "../cuatrimestres/".$rowMC['periodo']."/".$rowMC['codigo']."/Reporte ".$rowMC['reporteservicio']." - Incidente ".$rowMC['incidente'].".jpg";
						//$anexo = "../incidentes/".$arrincidentes[$i]."/Reporte ".$arrreportes[$i].".jpg";
						$anexo = "";
						if(file_exists("../incidentes/".$arrincidentes[$i]."/Reporte ".$arrreportes[$i].".jpg")){
							$anexo = "../incidentes/".$arrincidentes[$i]."/Reporte ".$arrreportes[$i].".jpg";
						}else{
							if(file_exists("../incidentes/".$arrincidentes[$i]."/Reporte ".$arrreportes[$i].".jpeg")){
								$anexo = "../incidentes/".$arrincidentes[$i]."/Reporte ".$arrreportes[$i].".jpeg";
							}
						}
						if (file_exists($anexo)){
							$pdf->Cell(0, 10,'ANEXO '.$i ,0,0,'C');
							$pdf->Ln(2);
							$pdf->Image($anexo,45,70,'110'); //borde izq, borde sup, ancho
							$k++;
						}else{
							$pdf->Cell(0, 10,$anexo,0,0,'C');
						}
					}
				}
				$pdf->AddPage();
				$countAnexo++;
				$initAnexo = $countAnexo;
				//$arrincidentes[] = '';
				//$arrreportes[] = '';
				
				//ENCABEZADO
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(57, 8,'Equipo / Modalidad',1,0,'C');
				$pdf->Cell(20, 8,'Marca',1,0,'C');
				$pdf->Cell(22, 8,'Serie',1,0,'C');
				$y = $pdf->GetY();
				$pdf->MultiCell(17, 4,'# Incidente',1,'C');				
				$pdf->SetY($y);
				$pdf->Cell(116,8,'',0,0);
				$pdf->Cell(43, 8,'Unidad Ejecutora',1,0,'L');
				$pdf->MultiCell(20, 4,'Fecha del MP',1,'C');
				$pdf->SetY($y);
				$pdf->Cell(179,8,'',0,0);
				$pdf->Cell(12, 8,'Anexo',1,1,'C');
				$pdf->SetFont('Arial','',9);
			}else{
				$countAnexo++;
				$arrincidentes[] 	= $rowMC['incidente'];
				$arrreportes[] 		= $rowMC['reporteservicio'];
			}
			
			$longequipo = strlen($rowMC['equipo']);
			$longmarca = strlen($rowMC['marca']);
			$longserie = strlen($rowMC['serie']);
			$longunidad = strlen($rowMC['unidadejecutora']);
			
			if($pdf->GetY() >= 263){
				$pdf->AddPage();
				//ENCABEZADO
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(57, 8,'Equipo / Modalidad',1,0,'C');
				$pdf->Cell(20, 8,'Marca',1,0,'C');
				$pdf->Cell(22, 8,'Serie',1,0,'C');
				$y = $pdf->GetY();
				$pdf->MultiCell(17, 4,'# Incidente',1,'C');				
				$pdf->SetY($y);
				$pdf->Cell(116,8,'',0,0);
				$pdf->Cell(43, 8,'Unidad Ejecutora',1,0,'L');
				$pdf->MultiCell(20, 4,'Fecha del MP',1,'C');
				$pdf->SetY($y);
				$pdf->Cell(179,8,'',0,0);
				$pdf->Cell(12, 8,'Anexo',1,1,'C');
				$pdf->SetFont('Arial','',9);
			}
			
			//EQUIPO
			$equipo = trim(utf8_decode($rowMC['equipo']));
			if($longequipo > 36){				
				$w = 57;	$x = $pdf->GetX();	$y = $pdf->GetY();
				$pdf->MultiCell(57, 4,$equipo,1,'C');
				$pdf->SetXY($x+$w,$y);	
				$h = 8;
				if($longequipo > 72 ){
					$h = 12;
				}
			}else{
				$pdf->Cell(57, 8, utf8_decode($rowMC['equipo']),1,0,'C');
				$h = 8;
			}
			//MARCA
			$marca = trim(utf8_decode($rowMC['marca']));
			if($longmarca > 13){
				$w = 20;	$x = $pdf->GetX();	$y = $pdf->GetY();
				$pdf->MultiCell(20, ($h/2),$marca,1,'C');
				$pdf->SetXY($x+$w,$y);
			}else{
				$pdf->Cell(20, $h,$marca,1,0,'C');
			}
			//SERIE
			$arrserie = explode(' / ',trim($rowMC['serie']));
			if($longserie > 11){
				$w = 22;	$x = $pdf->GetX();	$y = $pdf->GetY();
				$pdf->MultiCell(22, ($h/2),$arrserie[0],1,'C');
				$pdf->SetXY($x+$w,$y);
			}else{
				$pdf->Cell(22, $h,$arrserie[0],1,0,'C');
			}
			
			$pdf->Cell(17, $h,$rowMC['incidente'],1,0,'C');
			
			if($longunidad > 49){
				$w = 43;	$x = $pdf->GetX();	$y = $pdf->GetY();
				$pdf->MultiCell(43, ($h/3), utf8_decode($rowMC['unidadejecutora']),1,'C');
				$pdf->SetXY($x+$w,$y);
			}elseif($longunidad > 27){
				$w = 43;	$x = $pdf->GetX();	$y = $pdf->GetY();
				$pdf->MultiCell(43, ($h/2), utf8_decode($rowMC['unidadejecutora']),1,'C');
				$pdf->SetXY($x+$w,$y);
			}else{
				$pdf->Cell(43, $h, utf8_decode($rowMC['unidadejecutora']),1,0,'C');
			}			
			
			$pdf->Cell(20, $h,$rowMC['fecharesolucion'],1,0,'C');
			$pdf->Cell(12, $h,$a,1,1,'C');
			$a++;
			
			if($a > $count){
				//ANEXOS				
				for($i = $initAnexo; $i <= $countAnexo; $i++){
					if($arrincidentes[$i] != ''){
						$pdf->AddPage();
						//$pdf->Cell(0, 10,'ANEXO '.$i ,0,1,'C');
						//$pdf->Cell(0, 10,'incidente: '.$rowMC['incidente'].', reporteservicio: '.$rowMC['reporteservicio'].', $arrincidentes[$i]: '.$arrincidentes[$i] ,0,1,'C');
						//$anexo = "../cuatrimestres/".$rowMC['periodo']."/".$rowMC['codigo']."/Reporte ".$rowMC['reporteservicio']." - Incidente ".$rowMC['incidente'].".jpg";
						//$anexo = "../incidentes/".$arrincidentes[$i]."/Reporte ".$arrreportes[$i].".jpg";
						if (file_exists("../incidentes/".$arrincidentes[$i]."/Reporte ".$arrreportes[$i].".jpg")){
							$anexo = "../incidentes/".$arrincidentes[$i]."/Reporte ".$arrreportes[$i].".jpg";
						}else{
							if (file_exists("../incidentes/".$arrincidentes[$i]."/Reporte ".$arrreportes[$i].".jpeg")){
								$anexo = "../incidentes/".$arrincidentes[$i]."/Reporte ".$arrreportes[$i].".jpeg";
							}
						}
						if (file_exists($anexo)){
							$pdf->Cell(0, 10,'ANEXO '.$i ,0,0,'C');
							$pdf->Ln(2);
							$pdf->Image($anexo,45,70,'110'); //borde izq, borde sup, ancho
							$k++;
						}else{
							$pdf->Cell(0, 10,$anexo,0,0,'C');
						}
					}
				}
				$pdf->AddPage();
			}
		}
	}

	//4.3 Responsables de la verificación o prueba (Nombres y Cargos)
	$pdf->Cell(0, 5,'4.3 Responsables de la verificación o prueba (Nombres y Cargos)',0,1,'L');
	$pdf->Cell(0, 6,'      Olmedo Cedeño, Director de Biomédica Nacional','LTRB',1,'L');	
	//$pdf->Cell(0, 6,'      Ing. Nodier Morales, Jefe Biomédica Panamá - Colón Dirección Nacional Biomédica','LRB',1,'L');
	$pdf->Ln(5);
	$pdf->Cell(0, 5,'      4.3.1 Resultados según el Responsable',0,1,'L');
	$pdf->Cell(0, 6,'      Satisfactorios. De acuerdo al contrato.',1,1,'L');
	
	$pdf->Output('I',"TR-09-GS-EPB-18.pdf");
	
?>