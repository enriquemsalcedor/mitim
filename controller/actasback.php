<?php
	include_once("../conexion.php");
	/** Include FPDF */
	require_once dirname(__FILE__) . '/../../repositorio-lib/fpdf/fpdf.php';
	
	$periodo 	= $_REQUEST['periodo'];
	$codigounid	= $_REQUEST['unidad'];
	/*
	$query  = " SELECT c.fechafin as fechacertificar, b.unidad, b.codigoaceptacion, c.numero 
				FROM incidentes a, unidades b, cuatrimestres c 
				WHERE a.idcategoria = 12 AND a.periodo = c.periodo AND a.periodo = '".$periodo."' 
				AND b.codigo = '".$codigounid."' AND a.unidadejecutora = b.codigo  
				LIMIT 1 ";
	*/
	$query	= " SELECT c.fechafin as fechacertificar, b.nombre AS unidad, b.codigoaceptacion, c.numero, c.periodo 
				FROM incidentes a
				LEFT JOIN ambientes b ON a.idambientes = b.id
				LEFT JOIN cuatrimestres c ON a.fecharesolucion BETWEEN c.fechainicio AND c.fechafin
				WHERE a.idcategorias = 12 AND c.periodo = '".$periodo."' 
				AND b.id = '".$codigounid."' AND a.idambientes = b.id  
				LIMIT 1";
	debug('actas:'.$query);
	$result = $mysqli->query($query);
	$unidadeje = ''; $numeroaceptacion = '';
	$_SESSION['fechacertificar'] = '';
	while($row = $result->fetch_assoc()){
		if($row['fechacertificar'] != ''){
			$_SESSION['fechacertificar'] = $row['fechacertificar'];
		}		
		$unidadeje 					 = $row['unidad'];
		$numeroaceptacion 			 = 'TR-'.$row['numero'].'-GS-'.$row['codigoaceptacion'];
	}
		
	//PDF
	class PDF extends FPDF{
		// Cabecera de página
		function Header(){				
			 // Logo
			 $this->Image('../../repositorio-tema/assets/img/cssp.jpg',10,6,18); //borde izq, borde sup, ancho
			 // Arial bold 11
			 $this->SetFont('Arial','',11);				 
			 // Título
			 $this->Cell(0,5,'CAJA DE SEGURO SOCIAL',0,1,'C'); //ancho, alto, texto, borde, salto, alineacion				 
			 $this->Cell(0,5,'FORMULARIO DEL SISTEMA DE GESTIÓN DE PROYECTOS',0,1,'C');
			 // Arial bold 11
			 $this->SetFont('Arial','',8);
			 // Salto de línea
			 $this->Ln(6);
			 $this->Cell(30,20,'F-PMO-S004',1,0,'C');
			 $this->Cell(100,5,'DIRECCIÓN NACIONAL DE ADMINISTRACIÓN DE PROYECTOS','TR',0,'C');
			 $this->SetFont('Arial','B',9);
			 $this->Cell(30,5,'Revisión:','LTR',0,'C');
			 $this->SetFont('Arial','',9);
			 $this->Cell(30,5,'Original','TR',1,'C');
			 $this->Cell(30);
			 $this->SetFont('Arial','',8);
			 $this->Cell(100,5,'FORMULARIO DE ACEPTACIÓN DE ENTREGABLE','B',0,'C');
			 $this->SetFont('Arial','B',9);
			 $this->Cell(30,5,'Página:','LTR',0,'C');
			 $this->SetFont('Arial','',9);
			 $this->Cell(30,5,$this->PageNo().' de {nb}','TR',1,'C');
			 $this->Cell(30);
			 $this->SetFont('Arial','',8);
			 $this->Cell(100,10,'PROYECTO: TELERADIOLOGÍA','B',0,'C');
			 $this->SetFont('Arial','B',9);
			 $this->Cell(30,5,'Fecha:','LTR',0,'C');
			 $this->SetFont('Arial','',9);			 
			 $fechacertificar = date('d/m/Y', strtotime($_SESSION['fechacertificar']));			 
			 $this->Cell(30,5,$fechacertificar,'TR',1,'C');			
			 $this->Cell(130);
			 $this->Cell(60,5,'Documento Nivel III',1,1,'C');
			 $this->SetFont('Arial','',8);
			 $this->Cell(0,5,'Elaborado: Dirección Nacional de Administración de Proyectos               Aprobado: Dirección Ejecutiva Nacional de Innovación y Transformación','LRB',1,'C');
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
	$pdf->SetFont('Arial','B',12);	
	$pdf->Cell(0, 5,'Aceptación de Entregable',0,1,'C');
	$pdf->Cell(0, 5,utf8_decode($unidadeje),0,1,'C');
	$pdf->Ln(10);
	$pdf->SetFont('Arial','',12);
	/*
	$pdf->SetFont('Arial','B',12);	
	$pdf->Cell(0, 5,'1. Aprobación',0,1,'L');
	$pdf->Ln(5);			
	
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(52, 8,'','LTR',0,'C');
	$pdf->Cell(50, 8,'Nombre','TR',0,'C');
	$pdf->Cell(30, 8,'Unidad','TR',0,'C');
	$pdf->Cell(30, 8,'Firma','TR',0,'C');
	$pdf->Cell(28, 8,'Fecha','TR',1,'C');
	$pdf->SetFont('Arial','',11);
	*/
	$responsableVP = '';
	$responsable = '';
	
	$query  = "SELECT * FROM reportesaprobacion WHERE 1 = 1 ";
	$result = $mysqli->query($query);
	$i = 0;
	$j = 0;
	$k = 0;
	while($row = $result->fetch_assoc()){
		$aprobacion = $row['aprobacion'];
		$nombre 	= $row['nombre'];
		$cargo 		= $row['cargo'];
		$unidad 	= $row['unidad'];
		$fecha 		= implode('/',array_reverse(explode('-', $row['fecha'])));
		if($j == 0){
			$queryR  = "SELECT nombre, cargo FROM reportesaprobacion WHERE aprobacion = 'Revisado por' ";
			$resultR = $mysqli->query($queryR);			
			while($rowR = $resultR->fetch_assoc()){
				if($j == 0){
					$responsableVP .= utf8_decode($rowR['nombre']).', '.utf8_decode($rowR['cargo']);
					$responsable .= utf8_decode($rowR['nombre']);
				}else{
					$responsableVP .= ' / '.utf8_decode($rowR['nombre']).', '.utf8_decode($rowR['cargo']);
					$responsable .= ' / '.utf8_decode($rowR['nombre']);
				}
				$j++;
			}
		}
		
		$longaprobacion = strlen($aprobacion);
		$longnombre 	= strlen($nombre);
		$longresponsable = strlen($responsable);
		$fechacertificar = date('d/m/Y', strtotime($_SESSION['fechacertificar']));
		/*
		if($aprobacion == 'Revisado por'){
			if($k == 0){
				if($longaprobacion > 27){
					$w=52; 		$x=$pdf->GetX();		$y=$pdf->GetY();
					$pdf->MultiCell(52, 5,utf8_decode($aprobacion).':','LTRB','L');
					$pdf->SetXY($x+$w,$y);
				}else{
					$pdf->Cell(52, 10, utf8_decode($aprobacion).':','LTRB',0,'L');
				}
				if($longresponsable > 27){
					$w=50; 		$x=$pdf->GetX();		$y=$pdf->GetY();
					$pdf->MultiCell(50, 5,$responsable,'TRB','L');
					$pdf->SetXY($x+$w,$y);
				}else{
					$pdf->Cell(50, 10, $responsable,'TRB',0,'L');
				}
				$pdf->Cell(30, 10, utf8_decode($unidad),'TRB',0,'C');
				$pdf->Cell(30, 10, '','TRB',0,'L');
				$pdf->Cell(28, 10, $fechacertificar,'TRB',1,'C');
				$k++;
			}				
		}else{
			if($longaprobacion > 27){
				$w=52; 		$x=$pdf->GetX();		$y=$pdf->GetY();
				$pdf->MultiCell(52, 5,utf8_decode($aprobacion).':','LTRB','L');
				$pdf->SetXY($x+$w,$y);
			}else{
				$pdf->Cell(52, 10, utf8_decode($aprobacion).':','LTRB',0,'L');
			}
			if($longnombre > 27){
				$w=50; 		$x=$pdf->GetX();		$y=$pdf->GetY();
				$pdf->MultiCell(50, 5,utf8_decode($nombre),'TRB','L');
				$pdf->SetXY($x+$w,$y);
			}else{
				$pdf->Cell(50, 10, utf8_decode($nombre),'TRB',0,'L');
			}
			$pdf->Cell(30, 10, utf8_decode($unidad),'TRB',0,'C');
			$pdf->Cell(30, 10, '','TRB',0,'L');
			$pdf->Cell(28, 10, $fechacertificar,'TRB',1,'C');
		}
	*/		
	}
	/*
	$pdf->AddPage();
	*/
	$pdf->SetFont('Arial','B',11);
	//2. Aceptación
	$pdf->Cell(0, 5,'1. Aceptación',0,1,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(5);
	$abrequipomodalidad = '';
	$fechaSol = '';
	$mantenimientosPre = '';
	$query = "	SELECT a.id as incidente, a.idestados, a.reporteservicio, a.fechacreacion, a.fecharesolucion, a.observaciones,
				c.serie, c.nombre AS equipo, e.nombre AS marca, f.nombre AS modelo
				FROM incidentes a
				LEFT JOIN ambientes b ON a.idambientes = b.id
				LEFT JOIN activos c ON a.idactivos = c.id
                LEFT JOIN cuatrimestres d ON a.fecharesolucion BETWEEN d.fechainicio AND d.fechafin
                LEFT JOIN marcas e ON c.idmarcas = e.id
                LEFT JOIN modelos f ON c.idmodelos = f.id
				WHERE a.idcategorias = 12 AND b.id = '$codigounid'
				AND (a.idestados = 16 OR a.idestados = 17) AND d.periodo = '$periodo' AND a.fecharesolucion is not NULL
				ORDER BY a.fecharesolucion ";
	//debug($query);
	$result = $mysqli->query($query);
	$i = 0;
	$j = 0;
	$k = 0;
	$equipoarr = '';
	$initequipo = 1;
	$mantenimientoarr = '';
	//$fechacertificar  = '';
	while($row = $result->fetch_assoc()){		
		//FECHA SOLICITUD
		$equipomodalidad 	= $row['equipo'].', '.$row['marca'].', '.$row['modelo'].', '.$row['serie'].' * '.$row['incidente'];
		$arrequipomodalidad = explode(' * ', $equipomodalidad);
		$abrequipomodalidad .= $equipomodalidad.'|';
		$valor_dia_entrega = implode('/',array_reverse(explode('-', $row['fecharesolucion'])));		
		
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
		$bdserie 	= $row['serie'];
		$arrserie 	= explode(' ', $bdserie);
		if($arrserie[0] != ''){
			$serie 		= $arrserie[0];
		}else{
			$serie 		= $arrserie[1];
		}
		
		$reporte	= $row['reporteservicio'];
		$incidente	= $row['incidente'];
		$arrequipomodalidad  = explode(' , ', $equipomodalidad);
		if(utf8_decode($arrequipomodalidad[0]) != $mantenimientoarr){
			$mantenimientosPre 	.= utf8_decode($arrequipomodalidad[0]).'|';
			$mantenimientosPre 	.= $reporte.'|';
			$mantenimientosPre 	.= $incidente.'|';		
			$mantenimientosPre 	.= $row['fecharesolucion'].'|';
			if( $row['estado'] == '16' || $row['estado'] == '17' ){
				$mantenimientosPre 	.= 'Satisfactorio|';
			}else{
				$mantenimientosPre 	.= 'No Satisfactorio|';
			}
			
			$mantenimientosPre 	.= '^';
		}
		
		$mantenimientoarr = utf8_decode($arrequipomodalidad[0]);
		
		/*
		if($row['fechacertificar'] != '0000-00-00'){
			$fechacertificar 	= $row['fechacertificar'];
		}
		*/
	}
	
	//2.1 Número Único de Aceptación
	$pdf->Cell(0, 5,'1.1 Número Único de Aceptación',0,1,'L');
	$pdf->Cell(0, 5,'      '.$numeroaceptacion,1,1,'L');
	$pdf->Ln(5);
	//2.2 Fecha de Solicitud
	$pdf->Cell(0, 5,'1.2 Fecha de Solicitud',0,1,'L');
	$fechaSol = explode('|', $fechaSol);
	//$countFS = count($fechaSol) - 1;
	$countFS = count($fechaSol);
	unset($fechaSol[$countFS]);
	//$countFSN = count($fechaSol) - 1;
	$countFSN = count($fechaSol);
	$fechaSoli = 1;
	$countMPT = 0;
	foreach($fechaSol as $abre){
		$y = $pdf->GetY();
		$longabre = strlen($abre);
		if($fechaSoli == $countFSN){
			if($fechaSoli > 1){
				if($longabre > 100){
					$pdf->MultiCell(0, 6,'      '.chr(129).' '.utf8_decode($abre),'LRB','L');
				}else{
					$pdf->Cell(0, 6,'      '.chr(129).' '.utf8_decode($abre),'LRB',1,'L');
				}
			}else{
				if($longabre > 100){
					$pdf->MultiCell(0, 6,'      '.chr(129).' '.utf8_decode($abre),1,'L');
				}else{
					$pdf->Cell(0, 6,'      '.chr(129).' '.utf8_decode($abre),1,1,'L');
				}				
			}			
		}elseif($fechaSoli == 1){
			if($longabre > 100){
				$pdf->MultiCell(0, 6,'      '.chr(129).' '.utf8_decode($abre),'LTR','L');
			}else{
				$pdf->Cell(0, 6,'      '.chr(129).' '.utf8_decode($abre),'LTR',1,'L');
			}
		}else{
			if($y >= 265 && $y <= 270){
				if($longabre > 100){
					$pdf->MultiCell(0, 6,'      '.chr(129).' '.utf8_decode($abre),'LRB','L');
				}else{
					$pdf->Cell(0, 6,'      '.chr(129).' '.utf8_decode($abre),'LRB',1,'L');
				}
			}elseif($y > 270){
				if($longabre > 100){
					$pdf->MultiCell(0, 6,'      '.chr(129).' '.utf8_decode($abre),'LRT','L');
				}else{
					$pdf->Cell(0, 6,'      '.chr(129).' '.utf8_decode($abre),'LRT',1,'L');
				}
			}else{
				if($longabre > 100){
					$pdf->MultiCell(0, 6,'      '.chr(129).' '.utf8_decode($abre),'LR','L');
				}else{
					$pdf->Cell(0, 6,'      '.chr(129).' '.utf8_decode($abre),'LR',1,'L');
				}
			}
		}
		$fechaSoli++;
		$countMPT++;
	}
	$pdf->Ln(5);
	//2.3 Fecha para Certificar Aceptación o Rechazo
	$fechacertificar = date('d/m/Y', strtotime($_SESSION['fechacertificar']));
	$pdf->Cell(0, 5,'1.3 Fecha para Certificar Aceptación o Rechazo',0,1,'L');
	$pdf->Cell(0, 5,'      '.$fechacertificar,1,1,'L');
	$pdf->Ln(10);
	
	//3. Entregable
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0, 5,'2. Entregable',0,1,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(5);
	
	//3.1 Descripción
	$pdf->Cell(0, 5,'2.1 Descripción',0,1,'L');
	$pdf->Cell(0, 8,'      Mantenimientos Preventivos Realizados:','LTR',1,'L');
	//$pdf->Cell(0, 8,$mantenimientosPre,'LTR',1,'L');
	$mantenimientosPre = explode('^', $mantenimientosPre);
	$countMP = count($mantenimientosPre) - 1;
	unset($mantenimientosPre[$countMP]);
	$countMPN = count($mantenimientosPre) - 1;
	$fechaSoli = 0;
	$itemA = '';
	foreach($mantenimientosPre as $item){
		$mantenimientoA = explode(' * ', $item);
		if($itemA != $mantenimientoA[0]){
			$y = $pdf->GetY();
			$arrItem = explode('|', $mantenimientoA[0]);
			//$arrItem2 = explode('|', $mantenimientoA[1]);
			//$fechaCr = $arrItem2[3];
			$itemL	 = $arrItem[0];		
			$longitem = strlen($itemL);
			if($fechaSoli == $countMPT-1){
				if($y >= 270 && $y <= 275){
					if($longitem > 100){
						$pdf->MultiCell(0, 6,'      '.chr(129).' '.$arrItem[0],'LRB','L');
					}else{
						$pdf->Cell(0, 6,'      '.chr(129).' '.$arrItem[0],'LRB',1,'L');
					}				
				}elseif($y > 275){
					if($longitem > 100){
						$pdf->MultiCell(0, 6,'      '.chr(129).' '.$arrItem[0],'LRBT','L');
					}else{
						$pdf->Cell(0, 6,'      '.chr(129).' '.$arrItem[0],'LRBT',1,'L');
					}
				}else{
					if($longitem > 100){
						$pdf->MultiCell(0, 6,'      '.chr(129).' '.$arrItem[0],'LRB','L');
					}else{
						$pdf->Cell(0, 6,'      '.chr(129).' '.$arrItem[0],'LRB',1,'L');
					}
				}
			}elseif($fechaSoli == 0){
				if($y >= 270 && $y <= 275){
					if($longitem > 100){
						$pdf->MultiCell(0, 6,'      '.chr(129).' '.$arrItem[0],'LRB','L');
					}else{
						$pdf->Cell(0, 6,'      '.chr(129).' '.$arrItem[0],'LRB',1,'L');
					}
				}elseif($y > 275){
					if($longitem > 100){
						$pdf->MultiCell(0, 6,'      '.chr(129).' '.$arrItem[0],'LRT','L');
					}else{
						$pdf->Cell(0, 6,'      '.chr(129).' '.$arrItem[0],'LRT',1,'L');
					}
				}else{
					if($longitem > 100){
						$pdf->MultiCell(0, 6,'      '.chr(129).' '.$arrItem[0],'LR','L');
					}else{
						$pdf->Cell(0, 6,'      '.chr(129).' '.$arrItem[0],'LR',1,'L');
					}
				}
			}else{
				if($y >= 270 && $y <= 275){
					if($longitem > 100){
						$pdf->MultiCell(0, 6,'      '.chr(129).' '.$arrItem[0],'LRB','L');
					}else{
						$pdf->Cell(0, 6,'      '.chr(129).' '.$arrItem[0],'LRB',1,'L');
					}
				}elseif($y > 275){
					if($longitem > 100){
						$pdf->MultiCell(0, 6,'      '.chr(129).' '.$arrItem[0],'LRT','L');
					}else{
						$pdf->Cell(0, 6,'      '.chr(129).' '.$arrItem[0],'LRT',1,'L');
					}
				}else{
					if($longitem > 100){
						$pdf->MultiCell(0, 6,'      '.chr(129).' '.$arrItem[0],'LR','L');
					}else{
						$pdf->Cell(0, 6,'      '.chr(129).' '.$arrItem[0],'LR',1,'L');
					}				
				}
			}
			$fechaSoli++;
		}
		$itemA = $mantenimientoA[0];			
	}
	$pdf->Ln(5);
	
	//3.2 Criterios o Estándares de Aceptación
	$pdf->Cell(0, 5,'2.2 Criterios o Estándares de Aceptación',0,1,'L');
	$y = $pdf->GetY();
	if($y > 265){
		$pdf->Cell(0, 6,'      • Cronograma de mantenimiento preventivo.','LTRB',1,'L');
	}else{
		$pdf->Cell(0, 6,'      • Cronograma de mantenimiento preventivo.','LTR',1,'L');
	}	
	$y = $pdf->GetY();
	if($y > 270){
		$pdf->Cell(0, 6,'      '.chr(129).' Rutinas de mantenimiento para cada equipo.','LRT',1,'L');		
	}elseif($y > 265){
		$pdf->Cell(0, 6,'      '.chr(129).' Rutinas de mantenimiento para cada equipo.','LRB',1,'L');		
	}else{
		$pdf->Cell(0, 6,'      '.chr(129).' Rutinas de mantenimiento para cada equipo.','LR',1,'L');		
	}
	$y = $pdf->GetY();
	if($y > 270){
		$pdf->Cell(0, 6,'      '.chr(129).' Referencia al Pliego de Cargos (Mantenimientos Preventivos).','LRT',1,'L');
	}elseif($y > 265){
		$pdf->Cell(0, 6,'      '.chr(129).' Referencia al Pliego de Cargos (Mantenimientos Preventivos).','LRB',1,'L');
	}else{
		$pdf->Cell(0, 6,'      '.chr(129).' Referencia al Pliego de Cargos (Mantenimientos Preventivos).','LR',1,'L');
	}
	$y = $pdf->GetY();
	if($y > 270){
		$pdf->Cell(0, 6,'      '.chr(129).' Contrato No. 10071910-08-21 (2013-1-10-0-99-LP-105287)','LRT',1,'L');
	}elseif($y > 265){
		$pdf->Cell(0, 6,'      '.chr(129).' Contrato No. 10071910-08-21 (2013-1-10-0-99-LP-105287)','LRB',1,'L');
	}else{
		$pdf->Cell(0, 6,'      '.chr(129).' Contrato No. 10071910-08-21 (2013-1-10-0-99-LP-105287)','LR',1,'L');
	}	
	$pdf->SetFont('Arial','I',10);
	if($y > 280){
		$pdf->Cell(0, 8,'             *Claúsula Trigésima Tercera*','LRBT',1,'L');
	}else{
		$pdf->Cell(0, 8,'             *Claúsula Trigésima Tercera*','LRB',1,'L');
	}	
	$pdf->SetFont('Arial','',10);		
	$pdf->Ln(10);
	
	//4. Resultados
	//$pdf->AddPage();
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0, 5,'3. Resultados',0,1,'L');
	$pdf->SetFont('Arial','',10);	
	
	$i = 1;
	foreach($mantenimientosPre as $item){
		$j = 1;
		$arrItem 	= explode('|', $item);
		$item	 	= $arrItem[0];
		$reporte	= $arrItem[1];
		$incidente	= $arrItem[2];
		if($arrItem[3] == '0000-00-00'){
			$fecharesolucion = '';
		}else{
			$fecharesolucion = implode('/',array_reverse(explode('-', $arrItem[3])));
		}
		
		$mantenimientoA = explode(' * ', $item);
		
		$resultados	= $arrItem[4];
		$py = $pdf->GetY();
		if($py >= 266){
			$pdf->AddPage();
		}else{
			$pdf->Ln(5);
		}
		
		$longitem = strlen($mantenimientoA[0]);
		if($longitem > 100){
			$pdf->MultiCell(0, 5,'3.'.$i.' ['.$mantenimientoA[0].']',0,'L');
		}else{
			$pdf->Cell(0, 5,'3.'.$i.' ['.$mantenimientoA[0].']',0,1,'L');
		}		
		
		$pdf->Ln(2);
		$pdf->Cell(0, 6,'      3.'.$i.'.'.$j.' Método de Verificación o Prueba Realizada',0,1,'L');
		$pdf->Cell(0, 6,'      Reporte de Solicitud de Servicio #'.$reporte.' (ticket #'.$incidente.')',1,1,'L');
		$j++;
		$py = $pdf->GetY();
		if($py >= 257){
			$pdf->AddPage();
		}else{
			$pdf->Ln(4);
		}		
		$pdf->Cell(0, 8,'      3.'.$i.'.'.$j.' Responsable de la Verificación o Prueba (Nombre y Cargo)',0,1,'L');			
		
		$longresponsableVP = strlen($responsableVP);
		if($longresponsableVP > 50){				
			$pdf->MultiCell(0, 5,'      '.$responsableVP,1,'L');
			$w=0; 		$x=$pdf->GetX();		$y=$pdf->GetY();
			$pdf->SetXY($x+$w,$y);
		}else{
			$pdf->Cell(0, 6,'      '.$responsableVP,1,1,'L');
		}
		
		$j++;
		
		$py = $pdf->GetY();
		if($py >= 258){
			$pdf->AddPage();
		}else{
			$pdf->Ln(4);
		}
		$pdf->Cell(0, 8,'      3.'.$i.'.'.$j.' Fecha en que se realizó',0,1,'L');
		$pdf->Cell(0, 6,'      '.$fecharesolucion,1,1,'L');
		$j++;
		$pdf->Ln(4);
		$pdf->Cell(0, 8,'      3.'.$i.'.'.$j.' Resultados según el Responsable',0,1,'L');
		$pdf->Cell(0, 6,'      '.$resultados,1,1,'L');
		$j++;
		$pdf->Ln(4);
		$pdf->Cell(0, 8,'      3.'.$i.'.'.$j.' Evidencias de los Resultados',0,1,'L');
		$pdf->Cell(0, 6,'      Reporte de Solicitud de Servicio #'.$reporte.' (ticket #'.$incidente.')',1,1,'L');
		
		$pdf->Ln(4);
		$i++;
	}
	
	//5 Certificación
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0, 5,'4. Certificación',0,1,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(5);
	$pdf->MultiCell(0, 5,'Con su firma en este documento, usted certifica que las pruebas y verificaciones fueron realizadas y que este entregable ha sido aceptado y además usted certifica que cumple con todos los requerimientos, criterios y estándares definidos.',0,'L');
	$pdf->Ln(2);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(14, 6,'NOTA:',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(120, 6,'En caso de rechazar el entregable, no firme el documento.',0,1,'L');
	$pdf->Cell(0, 6,'4.1 [   ] Aceptado',0,1,'L');
	$pdf->Cell(0, 6,'Con marcar esta casilla y firmar el documento, acepto el entregable.',0,1,'L');
	$pdf->Cell(0, 6,'4.2 [   ] Rechazado',0,1,'L');
	$pdf->Cell(0, 6,'Con marcar esta casilla y detallar las razones a continuación, rechazo el entregable.',0,1,'L');
	$pdf->Cell(0, 6,'4.3 [   ] En caso de Rechazo, explique las razones',0,1,'L');
	$pdf->Cell(0, 10,'',1,1,'L');
	$pdf->Ln(10);
	
	//6 Administrador del Proyecto
	//$pdf->AddPage();
	$pdf->SetFont('Arial','B',11);
	$py = $pdf->GetY();
	if($py >= 260){
		$pdf->AddPage();
		$pdf->Cell(0, 5,'5. Administrador del Proyecto',0,1,'L');
	}else{
		$pdf->Cell(0, 5,'5. Administrador del Proyecto',0,1,'L');
	}
	
	$pdf->SetFont('Arial','',11);
	$pdf->Ln(5);
	$query  = "SELECT * FROM reportesaprobacion WHERE aprobacion = 'Administrador del Proyecto' ";
	$result = $mysqli->query($query);
	while($row = $result->fetch_assoc()){
		$nombre 	= $row['nombre'];
		$cargo		= $row['cargo'];
		$correo	 	= $row['correo'];
		$telefono 	= $row['telefono'];
		$celular 	= $row['celular'];
		$ubicacion 	= $row['ubicacion'];
		
		$pdf->Cell(0, 8,'5.1 Nombre',0,1,'L');
		$pdf->Cell(0, 8,'      '.utf8_decode($nombre),1,1,'L');
		$pdf->Ln(2);
		$pdf->Cell(0, 8,'5.2 Cargo',0,1,'L');
		$pdf->Cell(0, 8,'      '.utf8_decode($cargo),1,1,'L');
		$pdf->Ln(2);
		$pdf->Cell(0, 8,'5.3 Correo Electrónico',0,1,'L');
		$pdf->Cell(0, 8,'      '.utf8_decode($correo),1,1,'L');
		$pdf->Ln(2);
		$pdf->Cell(0, 8,'5.4 Teléfono',0,1,'L');
		$pdf->Cell(0, 8,'      '.$telefono,1,1,'L');
		$pdf->Ln(2);
		$pdf->Cell(0, 8,'5.5 Celular',0,1,'L');
		$pdf->Cell(0, 8,'      '.$celular,1,1,'L');
		$pdf->Ln(2);
		$pdf->Cell(0, 8,'5.6 Ubicación Física',0,1,'L');
		$pdf->Cell(0, 8,'      '.utf8_decode($ubicacion),1,1,'L');
	}
	$pdf->Ln(10);
	
	//7 Anexos (Evidencias)
	//$pdf->AddPage();
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0, 5,'6. Anexos (Evidencias)',0,1,'L');
	$pdf->SetFont('Arial','',11);
	$pdf->Ln(5);
	
	$queryAnexos  = "SELECT a.id, a.reporteservicio 
					 FROM incidentes a 
					 LEFT JOIN ambientes amb ON a.idambientes = amb.id
					 LEFT JOIN cuatrimestres b ON a.fecharesolucion BETWEEN b.fechainicio AND b.fechafin
					 WHERE b.periodo = '$periodo' AND amb.id = '$codigounid'
					 AND (a.idestados = 16 OR a.idestados = 17) AND a.idcategorias = 12 ";
	$resultAnexos = $mysqli->query($queryAnexos);
	$k = 1;
	while($rowAnexos = $resultAnexos->fetch_assoc()){
		$reporte	= $rowAnexos['reporteservicio'];
		$incidente	= $rowAnexos['id'];
		if($k == 1){	$rom = 'I';		}		if($k == 2){	$rom = 'II';	}
		if($k == 3){	$rom = 'III';	}		if($k == 4){	$rom = 'IV';	}
		if($k == 5){	$rom = 'V';		}		if($k == 6){	$rom = 'VI';	}
		if($k == 7){	$rom = 'VII';	}		if($k == 8){	$rom = 'VIII';	}
		if($k == 9){	$rom = 'IX';	}		if($k == 10){	$rom = 'X';		}
		
		if($k == 11){	$rom = 'XI';	}		if($k == 12){	$rom = 'XII';		}
		if($k == 13){	$rom = 'XIII';	}		if($k == 14){	$rom = 'XIV';		}
		if($k == 15){	$rom = 'XV';	}		if($k == 16){	$rom = 'XVI';		}
		if($k == 17){	$rom = 'XVII';	}		if($k == 18){	$rom = 'XVIII';		}
		if($k == 19){	$rom = 'XIX';	}		if($k == 20){	$rom = 'XX';		}
		
		if($k == 21){	$rom = 'XXI';	}		if($k == 22){	$rom = 'XXII';		}
		if($k == 23){	$rom = 'XXIII';	}		if($k == 24){	$rom = 'XXIV';		}
		if($k == 25){	$rom = 'XXV';	}		if($k == 26){	$rom = 'XXVI';		}
		if($k == 27){	$rom = 'XXVII';	}		if($k == 28){	$rom = 'XXVIII';	}
		if($k == 29){	$rom = 'XXIX';	}		if($k == 30){	$rom = 'XXX';		}
		
		if($k == 31){	$rom = 'XXXI';	}		if($k == 32){	$rom = 'XXXII';		}
		if($k == 33){	$rom = 'XXXIII';}		if($k == 34){	$rom = 'XXXIV';		}
		if($k == 35){	$rom = 'XXXV';	}		if($k == 36){	$rom = 'XXXVI';		}
		if($k == 37){	$rom = 'XXXVII';}		if($k == 38){	$rom = 'XXXVIII';	}
		if($k == 39){	$rom = 'XXXIX';	}		if($k == 40){	$rom = 'XL';		}
		
		if($k == 41){	$rom = 'XLI';	}		if($k == 42){	$rom = 'XLII';		}
		if($k == 43){	$rom = 'XLIII';	}		if($k == 44){	$rom = 'XLIV';		}
		if($k == 45){	$rom = 'XLV';	}		if($k == 46){	$rom = 'XLVI';		}
		if($k == 47){	$rom = 'XLVII';	}		if($k == 48){	$rom = 'XLVIII';	}
		if($k == 49){	$rom = 'XLIX';	}		if($k == 50){	$rom = 'L';			}
		
		if($k == 51){	$rom = 'LI';	}		if($k == 52){	$rom = 'LII';		}
		if($k == 53){	$rom = 'LIII';	}		if($k == 54){	$rom = 'LIV';		}
		if($k == 55){	$rom = 'LV';	}		if($k == 56){	$rom = 'LVI';		}
		if($k == 57){	$rom = 'LVII';	}		if($k == 58){	$rom = 'LVIII';		}
		if($k == 59){	$rom = 'LIX';	}		if($k == 60){	$rom = 'LX';		}
		
		if($k == 61){	$rom = 'LXI';	}		if($k == 62){	$rom = 'LXII';		}
		if($k == 63){	$rom = 'LXIII';	}		if($k == 64){	$rom = 'LXIV';		}
		if($k == 65){	$rom = 'LXV';	}		if($k == 66){	$rom = 'LXVI';		}
		if($k == 67){	$rom = 'LXVII';	}		if($k == 68){	$rom = 'LXVIII';	}
		if($k == 69){	$rom = 'LXIX';	}		if($k == 70){	$rom = 'LXX';		}

		//$anexoCL = "../cuatrimestres/".$periodo."/".$codigounid."/Reporte ".$reporte." - Incidente ".$incidente." - Check List.jpg";
		$anexoCL = "../incidentes/".$incidente."/Reporte ".$reporte." - Incidente ".$incidente." - Check List.jpg";
		if (file_exists($anexoCL)){
			$pdf->Cell(0, 6,'      6.'.$k.' Anexo '.$rom,0,1,'L');
			$pdf->Cell(0, 6,'            - Reporte de Solicitud de Servicio #'.$reporte.' (ticket #'.$incidente.')',0,1,'L');
			$pdf->Cell(0, 6,'            - Check List',0,1,'L');
		}else{
			$pdf->Cell(0, 6,'      6.'.$k.' Anexo '.$rom.' – Reporte de Solicitud de Servicio #'.$reporte.' (ticket #'.$incidente.')',0,1,'L');
		}
		
		$k++;
	}
	//$pdf->AddPage();
	$a = 1;
	//foreach($mantenimientosPre as $item){
	$resultAnexosL = $mysqli->query($queryAnexos);
	while($rowAnexos = $resultAnexosL->fetch_assoc()){
		$pdf->AddPage();
		$reporte	= $rowAnexos['reporteservicio'];
		$incidente	= $rowAnexos['id'];
		
		$pdf->SetFont('Arial','B',13);		
		//$anexo = "../cuatrimestres/".$periodo."/".$codigounid."/Reporte ".$reporte." - Incidente ".$incidente.".jpg";
		//$anexoCL = "../cuatrimestres/".$periodo."/".$codigounid."/Reporte ".$reporte." - Incidente ".$incidente." - Check List.jpg";
		$anexo = "../incidentes/".$incidente."/Reporte ".$reporte.".jpg";
		$anexoCL = "../incidentes/".$incidente."/Reporte ".$reporte." - Check List.jpg";		
		
		if (file_exists($anexoCL)){
			$pdf->Cell(0, 5,'ANEXO '.$a,0,1,'C');
			$pdf->Ln(2);
			$pdf->Image($anexo,45,70,'110%'); //borde izq, borde sup, ancho
			$pdf->AddPage();
			$pdf->Cell(0, 5,'ANEXO '.$a.' - CHECK LIST',0,1,'C');
			$pdf->Ln(2);
			$pdf->Image($anexoCL,45,70,'110%'); //borde izq, borde sup, ancho
		}elseif (file_exists($anexo)){
			$pdf->Cell(0, 5,'ANEXO '.$a,0,1,'C');
			$pdf->Ln(2);
			$pdf->Image($anexo,45,70,'110%'); //borde izq, borde sup, ancho
		}
		$a++;			
	}
	
	$pdf->Output('I',"TR-09-GS-EPB-18.pdf");	
?>