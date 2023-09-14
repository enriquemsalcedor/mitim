<?php
include("../conexion.php");

$oper = '';
if (isset($_REQUEST['oper'])) {
	$oper = $_REQUEST['oper'];   
}
	
	switch($oper){
		case "actividades": 
			  actividades();
			  break;
		case "eliminaractividad":
			  eliminaractividad();
			  break;
	  	case "consultar_existencia":
	  		  consultar_existencia();
	  		  break;
		case "guardarActividad":
	  		  guardarActividad();
	  		  break;
		case "guardarActividadMasivo":
			  guardarActividadMasivo();
			  break;
  		case "cargarActividad":
  		  	  cargarActividad();
  		  	  break;
		case "importaractividades":
  		  	  importaractividades();
  		  	  break;
		case "generarOrdenes":
			  generarOrdenes();
			  break;
		case  "guardarfiltros":
		      guardarfiltros();
			  break;
	    case  "abrirfiltros":
		      abrirfiltros();
			  break;
	    case  "verificarfiltros":
		      verificarfiltros();
			  break;
	    case  "limpiarFiltrosMasivos":
		      limpiarFiltrosMasivos();
			  break;
		case  "abrirEvidencias":
		      abrirEvidencias();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	

	function actividades(){
		global $mysqli;
		$where = array();
		$where2 = "";
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
			
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : '');//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['0']) ? $_REQUEST['order'][0]['0']: '');// index of the sorting column (0 index based - i.e. 0 is the first record)
		$orderBy   = 0;//$_REQUEST['id'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
		$orderType = "DESC";//$_REQUEST['order'][0]['dir']; // ASC or DESC
		$start     = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length    = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		
		/* $query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'actividades' AND usuario = '".$_SESSION['usuario']."'";
		$result = $mysqli->query($query);
		if($result->num_rows >0){
			$row = $result->fetch_assoc();
			if (!isset($_REQUEST['data'])) {
				$data = $row['filtrosmasivos'];
			}
		}		
		if($data != ''){
			$data = json_decode($data);
			if(!empty($data->desdef)){
				$desdef = json_encode($data->desdef);
				$where2 .= " AND a.fechaatencion >= $desdef ";
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where2 .= " AND a.fechaatencion <= $hastaf ";
			}
			if(!empty($data->idempresasf)){
				$idempresasf = json_encode($data->idempresasf);
				if($idempresasf != '[""]'){
					$where2 .= " AND a.idempresas IN ($idempresasf)"; 
				}				
			}			
			if(!empty($data->idclientesf)){
				$idclientesf = json_encode($data->idclientesf);
				if($idclientesf != '[""]'){
					$where2 .= " AND a.idclientes IN ($idclientesf)"; 
				}				
			}
			if(!empty($data->idcontratosf)){
				$idcontratosf = json_encode($data->idcontratosf);
				if($idcontratosf != '[""]'){
					$where2 .= " AND a.idcontratos IN ($idcontratosf)"; 
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
					$where2 .= " AND a.responsable IN ($asignadoaf)";	
				}
			}
			if(!empty($data->idambientes)){
				$idambientes = json_encode($data->idambientes);
				 if($idambientes !== '[""]'){ 
					$where2 .= " AND a.idambientes IN ($idambientes)";
				}
			}
			if(!empty($data->idsubambientes)){
				$idsubambientes = json_encode($data->idsubambientes);
				 if($idsubambientes !== '[""]'){ 
					$where2 .= " AND a.idsubambientes IN ($idsubambientes)";
				}
			}
			$vowels = array("[", "]");
			$where2 = str_replace($vowels, "", $where2);
		} */
		
		$query = "  SELECT a.id, b.nombre as activo, a.titulo, a.descripcion, a.diainiciofrecuencia, a.frecuencia,
					 a.tipoplan as tipo, b.nombre AS cliente, d.nombre AS responsable, c.nombre AS proyecto 
					FROM plan a
					INNER JOIN clientes b ON b.id = a.idclientes
					INNER JOIN proyectos c ON a.idproyectos = c.id
					INNER JOIN usuarios d ON d.correo = a.responsable
					WHERE 1 = 1";	
	  
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		//debug($query);
		$recordsTotal = $result->num_rows;
		$query  .= " ORDER BY a.id DESC LIMIT $start, $length ";
		$response = array();
		$response['data'] = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
	//	debug($query);
		while($row = $result->fetch_assoc()){
			/* $response->rows[$i]['id'] = $row['id']; */ 
			$frecuencia = $row['frecuencia'];
			
			if($frecuencia == 'Diaria' || $frecuencia == 'Quincenal'){
				$queryDF 	= "  SELECT nombre FROM diasfrecuencia WHERE idplan = ".$row['id'];
				$resultDF 	= $mysqli->query($queryDF);
				$count 		= $resultDF->num_rows;
				$num 		= 1;
				$diasfrecuencia = '';
				while($rowDF = $resultDF->fetch_assoc()){
					if($num == $count){
						$diasfrecuencia .= $rowDF['nombre'];
					}else{
						$diasfrecuencia .= $rowDF['nombre'].',';
					}
					$num++;
				}
				$frecuencia = $frecuencia.'<br><p style="font-size:10px;line-height: 1;margin: 0px;">('.$diasfrecuencia.')</p>';
				
			}
			//ADJUNTOS INCIDENTES
			$tieneEvidencias   = '';
			$rutaE 		= '../planes/'.$row['id'];
			if (is_dir($rutaE)) { 
			  if ($dhE = opendir($rutaE)) { 
				$num = 1;
				while (($fileE = readdir($dhE)) !== false) { 
					if ($fileE != "." && $fileE != ".." && $fileE != ".quarantine" && $fileE != ".tmb"){ 
						$nombrefile = $fileE;
						if($num > 1){
							$tieneEvidencias .= ", ";
						}
						$tieneEvidencias .= "<a href='".dirname($_SERVER['PHP_SELF'])."/".$rutaE."/".$fileE."' target='_blank'>".$nombrefile."</a>";
						$num++;
					}						
				} 
				closedir($dhE); 
			  } 
			}
			if($tieneEvidencias != ''){
				$color = 'green';
			}else{
				$color = 'blue';
			}  
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-right droptable">
									<a class="dropdown-item text-info" href="planactividad.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>
									<a class="dropdown-item text-danger eliminar-actividad" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
								</div>
							</div>
						</td>';
			$response['data'][] = array(
				'check' 				=>	"", 
				'acciones' 				=> $acciones,
				'id' 					=> $row['id'],
				'tipo' 					=> ucfirst($row['tipo']),
				'frecuencia' 			=> ucfirst($frecuencia),
				'diainiciofrecuencia' 	=> $row['diainiciofrecuencia'],	
				'responsable' 			=> $row['responsable'],
				'titulo' 				=> $row['titulo'],
				'descripcion' 			=> $row['descripcion'],
				'cliente' 				=> $row['cliente'],
				'proyecto'	 			=> $row['proyecto']
			);
		}
		$response['draw'] = intval($draw);
		$response['recordsTotal'] = intval($recordsTotal);
		$response['recordsFiltered'] = intval($recordsTotal);
		
		echo json_encode($response);
	}

	function cargarActividad(){
		global $mysqli;
		$id = $_REQUEST['id'];
		
		$query = "  SELECT a.id, a.titulo, a.idclientes, a.idproyectos,a.idcategorias,a.idsubcategorias,a.idambientes,a.idsubambientes, a.tipoplan, a.frecuencia, a.observacion,a.idactivos,a.iddepartamentos,a.idprioridades,
					a.responsable, a.descripcion, a.diainiciofrecuencia
					FROM plan a
					WHERE a.id = '".$id."' ";		
		$result = $mysqli->query($query);
		$i = 0;
		$response = array();
		$diasfrecuencia = '';
		//debug($query);
		while($row = $result->fetch_assoc()){				
			$frecuencia = $row['frecuencia'];			
			if($frecuencia == 'diaria' || $frecuencia == 'quincenal'){
				$queryDF 	= "  SELECT nombre FROM diasfrecuencia WHERE idplan = ".$row['id'];
				$resultDF 	= $mysqli->query($queryDF);
				$count 		= $resultDF->num_rows;
				$num 			= 1;				
				while($rowDF = $resultDF->fetch_assoc()){
					if($num == $count){
						$diasfrecuencia .= $rowDF['nombre'];
					}else{
						$diasfrecuencia .= $rowDF['nombre'].'|';
					}
					$num++;
				}		
			}else{
				$queryDF 	= "  SELECT nombre FROM mesfrecuencia WHERE idplan = ".$row['id'];
				$resultDF 	= $mysqli->query($queryDF);
				$count 		= $resultDF->num_rows;
				if($count > 0){
					$rowDF = $resultDF->fetch_assoc();
					$diasfrecuencia .= $rowDF['nombre'];
				}else{
					$diasfrecuencia .= 'enero';
				}
			}		
			$response[] = array(
					'id' 					=> $row['id'],
					'titulo' 				=> $row['titulo'],
					'descripcion' 			=> $row['descripcion'],
					'diainiciofrecuencia' 	=> $row['diainiciofrecuencia'],
					'idclientes' 			=> $row['idclientes'],
					'idproyectos' 			=> $row['idproyectos'], 
					'idcategorias' 			=> $row['idcategorias'],
					'idsubcategorias' 		=> $row['idsubcategorias'], 
					'idambientes' 			=> $row['idambientes'],
					'idsubambientes' 		=> $row['idsubambientes'], 
					'idactivos' 	    	=> $row['idactivos'], 
					'idprioridades' 	   	=> $row['idprioridades'], 
					'iddepartamentos' 		=> $row['iddepartamentos'], 
					'tipoplan' 				=> $row['tipoplan'],
					'frecuencia' 			=> $frecuencia,
					'diasfrecuencia' 		=> $diasfrecuencia,
					'observacion' 			=> $row['observacion'],
					'responsable'			=> $row['responsable']
			);
		}
		echo json_encode($response);
	}

	function guardarActividad() {	
		global $mysqli;
		$id 				= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0 );
		$titulo 			= (!empty($_REQUEST['titulo']) ? $_REQUEST['titulo'] : '' );
		$descripcion 		= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '' );
		$idclientes	 		= (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0 );
		$idproyectos 		= (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : 0 ); 
		$idcategorias 		= (!empty($_REQUEST['idcategorias']) ? $_REQUEST['idcategorias'] : 0);
		$idsubcategorias 	= (!empty($_REQUEST['idsubcategorias']) ? $_REQUEST['idsubcategorias'] : 0);
	    $idambientes 		= (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : 0);
		$idsubambientes 	= (!empty($_REQUEST['idsubambientes']) ? $_REQUEST['idsubambientes'] : "");
		$idprioridades   	= (!empty($_REQUEST['idprioridades']) ? $_REQUEST['idprioridades'] : 0);
	    $idactivos 	        = (!empty($_REQUEST['idactivos']) ? $_REQUEST['idactivos'] : 0);
		$iddepartamentos	= (!empty($_REQUEST['iddepartamentos']) ? $_REQUEST['iddepartamentos'] : 0);
		$tipoplan 			= (!empty($_REQUEST['tipoplan']) ? $_REQUEST['tipoplan'] : '' );
		$frecuencia 		= (!empty($_REQUEST['frecuencia']) ? $_REQUEST['frecuencia'] : '' );
		$observacion 		= (!empty($_REQUEST['observacion']) ? $_REQUEST['observacion'] : '' );
		$responsable 		= (!empty($_REQUEST['responsable']) ? $_REQUEST['responsable'] : 0 );
		$diasfrecuencia 	= (!empty($_REQUEST['diasfrecuencia']) ? $_REQUEST['diasfrecuencia'] : '' );
		$iniciofrecuencia 	= (!empty($_REQUEST['iniciofrecuencia']) ? $_REQUEST['iniciofrecuencia'] : '' );
		$diainiciofrecuencia = (!empty($_REQUEST['diainiciofrecuencia']) ? $_REQUEST['diainiciofrecuencia'] : 0 );
		$usuario 			= $_SESSION['usuario'];
		$idusuario 			= $_SESSION['user_id'];
		$fecha 				= date("Y-m-d");
		
		
		$campos = array(
			'Título' 			=> $titulo,
			'Descripción' 		=> $descripcion,
			'Día de inicio de frecuencia' => $diainiciofrecuencia, 
			'Cliente' 			=> getValor('nombre','clientes',$idclientes),
			'Proyecto' 			=> getValor('nombre','proyectos',$idproyectos),  
		    'Categoría' 		=> getValor('nombre','categorias',$idcategorias),
			'Subcategoría' 		=> getValor('nombre','subcategorias',$idsubcategorias),
			'Ubicación' 		=> getValor('nombre','ambientes',$idambientes),
		//	'Área' 		        => getValor('nombre','subambientes',$idsubambientes),
			'Prioridad' 	    => getValor('prioridad','sla',$idprioridades),
			'Tipo de plan' 		=> $tipoplan,
			'Frecuencia' 		=> $frecuencia, 
			'Observación' 		=> $observacion,
			'Activo' 		    => getValor('nombre','activos',$idactivos),
			'Departamento / Grupo' 	=> getValor('nombre','departamentos',$iddepartamentos),
		//	'Responsable' 		=> getValor('nombre','usuarios',$responsable)
		);
		
		if($id == ''){		
			$queryI  = "INSERT INTO plan(titulo, descripcion, frecuencia, observacion, tipoplan, responsable, 
						idclientes, idproyectos,idcategorias,idsubcategorias,idambientes,idsubambientes,idprioridades,idactivos,iddepartamentos, diainiciofrecuencia,creadopor) 
						VALUES('".$titulo."', '".$descripcion."', '".$frecuencia."', '".$observacion."', '".$tipoplan."', 
						'".$responsable."', '".$idclientes."', '".$idproyectos."', '".$idcategorias."', '".$idsubcategorias."', '".$idambientes."', '".$idsubambientes."', '".$idprioridades."', '".$idactivos."', '".$iddepartamentos."', '".$diainiciofrecuencia."','".$idusuario."')";
		//	echo $queryI;
			$resultI = $mysqli->query($queryI);
			$idplan  = $mysqli->insert_id;
			nuevoRegistro('Plan de mantenimiento','Plan de mantenimiento',$idplan,$campos,$queryI);
			//$resultD = true;
		}else{		
			$valoresold = getRegistroSQL("	SELECT a.titulo AS 'Título', a.descripcion AS 'Descripción',
											a.diainiciofrecuencia AS 'Día de inicio de frecuencia', 
											b.nombre AS 'Cliente', c.nombre AS 'Proyecto',d.nombre AS 'Ubicación',
											e.nombre AS 'Área',f.nombre AS 'Categoría', g.nombre AS 'Subcategoría', 
											a.tipoplan as 'Tipo de plan',a.frecuencia AS 'Frecuencia',
											a.observacion AS 'Observación', h.nombre AS 'Activo', i.nombre AS 'Departamento / Grupo', j.nombre AS 'Responsable',k.nombre AS 'Prioridad'
											FROM plan a 
											INNER JOIN clientes b ON a.idclientes = b.id
											INNER JOIN proyectos c ON a.idproyectos = c.id 
											INNER JOIN ambientes d ON a.idambientes = d.id
											INNER JOIN subambientes e ON a.idsubambientes = e.id
											INNER JOIN categorias f ON a.idcategorias = f.id
											LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
											LEFT JOIN activos h ON a.idactivos = h.id
											INNER JOIN departamentos i ON a.iddepartamentos = i.id
											INNER JOIN usuarios j ON a.responsable = j.correo
											INNER JOIN sla k ON a.idprioridades = k.id
											WHERE a.id = '".$id."' ");
			$queryU = " UPDATE plan SET
						titulo = '".$titulo."', 
						descripcion = '".$descripcion."',
						frecuencia = '".$frecuencia."', 
						observacion = '".$observacion."',
						tipoplan = '".$tipoplan."',
						responsable = '".$responsable."',
						idclientes = '".$idclientes."',
						idproyectos = '".$idproyectos."',
						idambientes = '".$idambientes."',
						idsubambientes = '".$idsubambientes."',
						idprioridades = '".$idprioridades."',
						idcategorias = '".$idcategorias."',
						idsubcategorias = '".$idsubcategorias."',
						idactivos = '".$idactivos."',
						iddepartamentos = '".$iddepartamentos."',
						diainiciofrecuencia = '".$diainiciofrecuencia."'
						WHERE id = '".$id."'";
			//echo('$queryU: '.$queryU);
			$resultU = $mysqli->query($queryU);
			
			//MES INICIO FRECUENCIA
			$queryIF  = "UPDATE mesfrecuencia SET nombre = '".$iniciofrecuencia."' WHERE idplan = '".$id."' ";
			$resultIF = $mysqli->query($queryIF);
		//	echo('id: ' .$id);
			//actualizarRegistro('Plan de mantenimiento','Plan de mantenimiento',$id,$valoresold,$campos,$queryU);
			$queryD	 = "DELETE FROM diasfrecuencia WHERE idplan = '".$id."'";
			$resultD = $mysqli->query($queryD);
			$idplan  = $id;
		} 
		if($diasfrecuencia!=''){
			$arrDias = explode(',',$diasfrecuencia);
			foreach ($arrDias as $dia){
				$dia = trim($dia);
				$queryD  = "INSERT INTO diasfrecuencia (nombre,idplan) VALUES('".$dia."', '".$idplan."')";
				$resultD = $mysqli->query($queryD);
			}
		}
		if($iniciofrecuencia!=''){
			$queryI  = "INSERT INTO mesfrecuencia (nombre,idplan) VALUES('".$iniciofrecuencia."', '".$idplan."')";
			$resultI = $mysqli->query($queryI);
		}
		echo true;
	}

	function guardarActividadMasivo(){
		global $mysqli;		
		$id   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$data = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		$b   = ',';
		$pos = strpos($id, $b);		
		
		if ($pos === false) {
			$idarray	= array();
			$idarray 	= $id;
		}else{
			$idarray = explode(",", $id);
		}
		//MASIVO
		if(count($idarray) > 1){
			$query = "";
			if($data != ''){
				$i = 0;
				$coma = ',';
				$query .= "UPDATE plan SET ";
				foreach($data as $c => $v){
					if($v != '' && $v != '0'){
						if($i != 0){
							$query .= $coma;
						}
						if($c == 'idempresasmas'){
							$query .= " idempresas = '$v' ";
						}elseif($c == 'idclientesmas'){
							$query .= " idclientes = '$v' ";
						}elseif($c == 'idcontratosmas'){
							$query .= " idcontratos = '$v' ";
						}elseif($c == 'idambientesmas'){
							$query .= " idambientes = '$v' ";
						}elseif($c == 'idsubambientesmas'){
							$query .= " idsubambientes = '$v' ";
						}elseif($c == 'asignadoamas'){
							$query .= " responsable = '$v' ";
						}elseif($c == 'tipoplanmas'){
							$query .= " tipoplan = '$v' ";
						}
						$i++;
					}
				}
				if($i >= 1){
					if(is_array($idarray) == true){
						foreach($idarray as $valor => $id){							
							$query2 = '';
							$query2 = $query." WHERE id = '$id' ";
							if($id != ''){							
								if($mysqli->query($query2)){
									bitacora($_SESSION['usuario'], "Plan de actividades", 'El Plan de actividades #'.$id.' ha sido Editado exitosamente', $id, $query2);
									echo true;
								}else{
									echo false;
								}
							}
						}
					}else{
						$query2 = '';
						$query2 = $query." WHERE id = '$id' ";
						if($id != ''){							
							if($mysqli->query($query2)){
								bitacora($_SESSION['usuario'], "Plan de actividades", 'El Plan de actividades #'.$id.' ha sido Editado exitosamente', $id, $query2);
								echo true;
							}else{
								echo false;
							}
						}
					}
					
				}else{
					echo false;
				}
			}
		}
	}

	function generarOrdenes() {
		global $mysqli;
		
		$desde 		  = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : date("Y-m-d")); 
		$hasta 		  = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Y-m-d")); 
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0 );
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : 0 );
		$usuario 	  = $_SESSION['usuario'];
		$cantidad	  = 0;
		
		$fechaInicio	= strtotime($desde);
		$fechaFin		= strtotime($hasta);
		for($i = $fechaInicio; $i <= $fechaFin; $i += 86400){
			$cantidad += generarOrdenesdia(date("Ymd", $i),$idclientes,$idproyectos);
		}
	//	echo $cantidad;
		if ($cantidad == 0) {
			echo '{"success": false}'; 
		}else {
			echo '{"success": true, "ordenes": '.$cantidad.'}';
		}	
	}

	function generarOrdenesdia($fecha,$idclientes,$idproyectos) { //
		global $mysqli;
		$solicitante = $_SESSION['correousuario'];
		$xfecha	= strtotime($fecha);
		$year	= date('Y', $xfecha);
		$month	= date('m', $xfecha);
		$day	= date('d', $xfecha);
		$diaSemana = date("w",mktime(0,0,0,$month,$day,$year));
		
		$query  = " SELECT a.* FROM plan a WHERE a.tipoplan = 'Automático'";
	//	echo $query;
		
		if($idclientes != 0){
			$query  .= " AND idclientes = '".$idclientes."' ";
		}
		if($idproyectos != 0){
			$query  .= " AND idproyectos = '".$idproyectos."' ";
		}
		 
		$result = $mysqli->query($query);
		$nbrows = $result->num_rows;
	//	echo $nbrows;
		$total = 0;
		if($nbrows > 0){ 
			while ($registro = $result->fetch_assoc())  {
				if (crearOrden( $registro['id'],$registro['titulo'],$registro['descripcion'],$registro['idclientes'],$registro['idproyectos'],$registro['idcategorias'],$registro['idsubcategorias'],$registro['idambientes'],$registro['idsubambientes'],$registro['idactivos'],$registro['idprioridades'],$registro['frecuencia'],$registro['tipoplan'],$fecha,$registro['diainiciofrecuencia'],$registro['iddepartamentos'],$registro['responsable'],$registro['observacion'],
					$solicitante))
				$total++;
			}
		} 
			
		
		return $total;
	}

	function crearOrden($plan,$titulo,$descripcion,$idclientes,$idproyectos,$idcategorias,$idsubactegorias,$idambientes,$idsubambientes,$idactivos,$idprioridades,$frecuencia,$tipoplan,$fecha,$diainiciofrecuencia,$iddepartamentos,$responsable,$observacion,$solicitante) {
							
		global $mysqli;
		$crear 	= 0;	
		$xfecha	= strtotime($fecha);
		$year	= date('Y', $xfecha);
		$month	= date('m', $xfecha);
		$day	= date('d', $xfecha);
		
		$fechabitacora 	= date('Y-m-d',$xfecha);
		//$semana=date("W",mktime(0,0,0,$month,$day,$year));
		$diaSemana 		= date("w",mktime(0,0,0,$month,$day,$year));
		$numeroS 		= date("W",mktime(0,0,0,$month,$day,$year));
		$numeroSemana 	= ltrim($numeroS,0);
		
		//Esto retorna el numero de semana del mes. Al final no se uso pues se uso el correlativo de semanas de la tala secuencias
		$fechaparasemana = mktime (0, 0, 0, $month, 1, $year);
		$semana = ceil (($day + (date ("w", $fechaparasemana)-1)) / 7);	
		 
		$tipo = 'preventivos';
		if ($frecuencia == 'diaria') {
			$diasf = diasFrecuencia($plan, $diaSemana);
			//echo $diasf;
			if($diasf == 1){
				$crear = 1;
			}else{
				$crear = 0;
			}
		} /* elseif (substr_count($frecuencia, 'Semanal') > 0) {
			if ($diaSemana == 1)
				$crear = 1;
		} */ elseif (substr_count($frecuencia, 'quincenal') > 0) {
				$diasf = diasFrecuencia($plan, $diaSemana);
				if($diasf == 1 && $numeroSemana%2==0){
					$crear = 1;
				}else{
					$crear = 0;
				}
		} elseif (substr_count($frecuencia, 'mensual') > 0) {
			$mesini = inicioFrecuencia($plan);		
			if ($day == $diainiciofrecuencia && $mesini <= $month){
				$crear = 1;			
			}		
		} elseif ($frecuencia == 'bimestral') {
			$mesini = inicioFrecuencia($plan);
			$rangof = rangoFrecuencia($mesini, $month, 2);
			if ($day == $diainiciofrecuencia && $mesini <= $month && $rangof == 1)
				$crear = 1;
		} elseif ($frecuencia == 'trimestral') {
			$mesini = inicioFrecuencia($plan);
			$rangof = rangoFrecuencia($mesini, $month, 3);
			if ($day == $diainiciofrecuencia && $mesini <= $month && $rangof == 1)
				$crear = 1;
		} elseif ($frecuencia == 'cuatrimestral') {
			$mesini = inicioFrecuencia($plan);
			$rangof = rangoFrecuencia($mesini, $month, 4);
			if ($day == $diainiciofrecuencia && $mesini <= $month && $rangof == 1)
				$crear = 1;
		} elseif ($frecuencia == 'pentamestral') {
			$mesini = inicioFrecuencia($plan);
			$rangof = rangoFrecuencia($mesini, $month, 5);
			if ($day == $diainiciofrecuencia && $mesini <= $month && $rangof == 1)
				$crear = 1;
		} elseif ($frecuencia == 'semestral') {
			$mesini = inicioFrecuencia($plan);
			$rangof = rangoFrecuencia($mesini, $month, 6);
			if ($day == $diainiciofrecuencia && $mesini <= $month && $rangof == 1)
				$crear = 1;
		} elseif ($frecuencia == 'anual') {
			$mesini = inicioFrecuencia($plan);
			$rangof = rangoFrecuencia($mesini, $month, 12);
			if ($day == $diainiciofrecuencia && $mesini <= $month && $rangof == 1)
				$crear = 1;
		} elseif (convertirFrecuencia($frecuencia, $diaSemana, $idsistemas)) {
			$crear = 1;
		}
	//	echo('frecuencia: '.$frecuencia.', day: '.$day.', mesini: '.$mesini.', month: '.$month.', crear: '.$crear);
		if ($crear == 1) {
			$query = " SELECT id FROM incidentes WHERE idplan = $plan AND fechacreacion = '$fecha' and tipo= '$tipo' ";
			//echo $query;
			$result = $mysqli->query($query);
			/* if($responsable != 0){
				$estado = '42';
			}else{
				$estado = '41';
			} */
			$estado = '13';
			if($result->num_rows >0){
				$row = $result->fetch_assoc();				
				$idinc = $row['id'];
				
				$query = "  UPDATE incidentes SET 
				            titulo = '$titulo', 
				            descripcion = '$descripcion', 
							fechacreacion = '$fecha', 
							idclientes = '$idclientes', 
							idproyectos = '$idproyectos', 
							idcategorias = '$idcategorias', 
							idsubcategorias = '$idsubcategorias', 
							idambientes = '$idambientes', 
							idsubambientes = '$idsubambientes',
							idactivos = '$idactivos', 
							idprioridades = '$idprioridades', 
							iddepartamentos = '$iddepartamentos',
							asignadoa = '$responsable',
							WHERE id = $idinc";
			}else{
				$query = "  INSERT INTO incidentes (titulo,descripcion,idestados,origen,creadopor,solicitante, 
							asignadoa,fechacreacion,idempresas,idclientes,idproyectos,idcategorias,idsubcategorias,idambientes,idsubambientes,idactivos,idprioridades,iddepartamentos, tipo,fechageneracion,idplan)
							VALUES ('$titulo', '$descripcion', '$estado', 'web', '$solicitante','$solicitante',
							'$responsable','$fecha',1,'$idclientes', '$idproyectos','$idcategorias','$idsubcategorias','$idambientes','$idsubambientes','$idactivos','$idprioridades','$iddepartamentos', '$tipo',NOW(), '$plan');";
			}		
			//debug($query);
		//	echo $query;
			
			if ($consulta = $mysqli->query($query)){
				$numeroIncidente = $mysqli->insert_id;			
				// BITACORA
				/* $campos = array(
					'Título' 			=> $titulo,
					'Descripción' 		=> $descripcion,
					'Empresas' 			=> getValor('nombre','empresas',$idempresas,''),
					'Clientes' 			=> getValor('nombre','clientes',$idclientes,''),
					'Contratos' 		=> getValor('descripcion','contratos',$idcontratos,''),
					'Ambientes' 		=> getValor('nombre','ambientes',$idambientes,''),
					'Subambientes' 		=> getValor('nombre','subambientes',$idsubambientes,''),
					'Activos' 			=> getValor('activo','activos',$idactivos,''),
					'Responsable' 		=> getValor('nombre','usuarios',$responsable,''),
				); */
				//nuevoRegistro('Plan de mantenimiento','Órdenes de trabajo',$numeroIncidente,$campos,$query);
				//Calendario
				//calendario($_SESSION['usuario'], $fecha, 'Orden', $numeroIncidente);
				return true;
			}else {
				//echo $query;
			   // die($mysqli->error);
				return false;
			}
		}	
	}

	function diasFrecuencia($plan, $diaSemana){
		global $mysqli;
		
		$bplan = " SELECT id FROM diasfrecuencia where idplan = '$plan' ";
		$rplan = $mysqli->query($bplan);			
		if($rplan->num_rows > 0){
			$diasem = array('0'=> 'domingo', '1'=>'lunes', '2'=>'martes', '3'=>'miercoles', '4'=>'jueves', '5'=>'viernes', '6'=>'sabado');
			$diat 	= $diasem[$diaSemana];
			
			$query  = "SELECT nombre FROM diasfrecuencia where idplan = '$plan' AND nombre = '$diat' ";
			$resultbp = $mysqli->query($query);
			$nbrows = $resultbp->num_rows;
			if($nbrows > 0){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 1;
		}
	}

	function inicioFrecuencia($plan){
		global $mysqli;
		
		$bplan = " SELECT nombre FROM mesfrecuencia where idplan = '$plan' ";
		$rplan = $mysqli->query($bplan);
		if($rplan->num_rows > 0){
			$registro 	= $rplan->fetch_assoc();
			$mesini 	= $registro['nombre'];
			$meses 		= array('enero' => '1', 'febrero' => '2', 'marzo' => '3', 'abril' => '4', 'mayo' => '5', 'junio' => '6', 
								'julio' => '7', 'agosto' => '8', 'septiembre' => '9', 'octubre' => '10', 'noviembre' => '11', 'diciembre' => '12');
			$mes 		= $meses[$mesini];
			return $mes;
		}else{
			return 1;
		}
	}

	function rangoFrecuencia($mesini, $month, $frecuencia){
		//debug('mesini: '.$mesini.', month: '.$month.', frecuencia: '.$frecuencia);
		$fmesb = $mesini - $month;
		if($fmesb < 0){
			$fmesb = $fmesb*-1;
		}
		if($fmesb == 0){
			$pas = 1;
		}elseif($fmesb%$frecuencia==0){
			$pas = 1;
		}else{
			$pas = 0;
		}
		return $pas;
	}

	function convertirFrecuencia($f, $d, $sistema) { //
		global $semanaaire, $semanamtto;
		
		$c = 0;
		$valor = false;
		if (substr_count($f, '-') > 0) {
			$diaaconvertir = explode('-',$f);
			$f = $diaaconvertir[0];
			$sem = $diaaconvertir[1];
		} else {
			$sem = '';
		}
		
		if ($f=='Lunes')
			$c = 1;
		elseif ($f=='Martes')
			$c = 2;
		elseif ($f=='Miercoles')
			$c = 3;
		elseif ($f=='Jueves')
			$c = 4;
		elseif ($f=='Viernes')
			$c = 5;
		elseif ($f=='Sabados')
			$c = 6;
			
		if ($d == $c || $d == 'Diaria')
			$valor = true;
		
		if ($sem != '') {
			if ($sistema == 'SISTEMA DE AIRES ACONDICIONADOS')
				$s = $semanaaire;
			else
				$s = $semanamtto;
			
			if ($s != $sem)
				$valor = false;
		}
		
		return $valor;
	}

	function buscarUsuario($nombre){
		global $mysqli;
		
		$query	= " SELECT correo FROM usuarios WHERE nombre = '".$nombre."' ";
		$result = $mysqli->query($query); 
		$row 	= $result->fetch_assoc();
		$usuario  	= $row['correo'];
		return $usuario;
	}

	function importaractividades(){
		
		global $mysqli;
		require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';

		if(isset($_FILES)) {
			$nombre	 	= $_FILES['archivo']['name'];
			$ArrArchivo = explode(".", $nombre);
			$extension 	= strtolower(end($ArrArchivo));
			$randName 	= md5(rand() * time());
			$path 		= '../planactividades/';

			$nombre_tmp = $_FILES["archivo"]["tmp_name"];
			$nombreA 	= $randName.'-'.basename($_FILES["archivo"]["name"]);
			$rutaA 		= $path."".$nombreA;
			move_uploaded_file($nombre_tmp, $rutaA);		
			
			//DEFINIR LA VERSION DE EXCEL
			if ($extension == 'xls'){
				//debug('xls 1');
				$objReader = PHPExcel_IOFactory::createReader('Excel5');				
			}elseif ($extension == 'xlsx'){
				//debug('xlsx 1');
				//$objReader = new PHPExcel_Reader_Excel2007();
				$objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
				$objReader->setReadDataOnly(true);						
			}
			//CODIGO DE LECTURA Y ESCRITURA
			$objPHPExcel = $objReader->load($rutaA);			
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			
			$importadasExito = 0;
			$importadasExitosas = 0;
			$importadasError = 0;
			$causasError = '<ul>';	
			
			//Se comienza en la fila 2 a procesar el contenido, la fila 1 debe ser el titulo
			for ($row = 2; $row <= $highestRow; $row++){
				// Si ninguna celda esta en blanco
				if ($sheet->getCell('A' . $row)->getValue() != '' && $sheet->getCell('B' . $row)->getValue() != '' && $sheet->getCell('C' . $row)->getValue() != '' && $sheet->getCell('D' . $row)->getValue() != '' ){
					//$repetida = checkActividadRepetida($sheet->getCell('A' . $row)->getValue(),$sheet->getCell('D' . $row)->getValue());
					$repetida = 0;
					/*
					$titulo = $sheet->getCell('C' . $row)->getValue();
					if($titulo != ''){
						$btitulo  = "SELECT titulo FROM plan where titulo = '$titulo' ";
						$resultbp = $mysqli->query($btitulo);
						$nbrows = $resultbp->num_rows;
						if($nbrows > 0){
							$causasError .= '<li>Error en la fila '.$row.', la actividad ya existe</li>';
							$importadasError++;
						}
					}
					*/
					if($repetida == 0){
						$rowData[] = $sheet->rangeToArray('A' . $row . ':' . 'L' . $row, NULL, TRUE, FALSE);
						$importadasExitosas++;				    
					} else {
						$causasError .= '<li>Error en la fila '.$row.', la actividad ya existe</li>';
						$importadasError++;
					}
					
				} else {
					$importadasError++;
					if($sheet->getCell('A' . $row)->getValue() == ''){
						$causasError .= '<li>Error en la fila '.$row.', el campo <b>Empresa</b> está vacío</li>';
					}
					if($sheet->getCell('B' . $row)->getValue() == ''){
						$causasError .= '<li>Error en la fila '.$row.', el campo <b>Cliente</b> está vacío</li>';
					}
					if($sheet->getCell('C' . $row)->getValue() == ''){
						$causasError .= '<li>Error en la fila '.$row.', el campo <b>Contrato</b> está vacío</li>';
					}
					if($sheet->getCell('D' . $row)->getValue() == ''){
						$causasError .= '<li>Error en la fila '.$row.', el campo <b>Actividad</b> está vacío</li>';
					}
				}
			}		
			$causasError .= '</ul>';
			
			$acciones = '';
			$listaImportadas = '<ul>';
			
			//BD
			for ($j = 0; $j < count($rowData); $j++){
				$ArrItem 	= $rowData[$j][0];
				/* 
				$fecha		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[1], "yyyy-mm-dd");
				$hora 		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[2], "h:mm:ss");
				*/			
				$empresa		= str_replace(' ', '', $ArrItem[0]);
				$cliente		= str_replace(' ', '', $ArrItem[1]);
				$contrato		= str_replace(' ', '', $ArrItem[2]);
				$actividad		= str_replace(' ', '', $ArrItem[3]);
				$frecuencia		= str_replace(' ', '', $ArrItem[4]);
				$formulario		= str_replace(' ', '', $ArrItem[5]);
				$observacion	= str_replace(' ', '', $ArrItem[6]);
				$tipoplan		= str_replace(' ', '', $ArrItem[7]);
				$responsable	= str_replace(' ', '', $ArrItem[8]);
				$ambiente		= str_replace(' ', '', $ArrItem[9]);
				$subambiente	= str_replace(' ', '', $ArrItem[10]);
				$activo			= str_replace(' ', '', $ArrItem[11]);
				
				//IDS
				$idempresas  	= getId('id', 'empresas', $empresa, 'nombre');
				$idclientes  	= getId('id', 'clientes', $cliente, 'nombre');
				$idcontratos  	= getId('id', 'contratos', $contrato, 'descripcion');				
				$idambientes  	= getId('id', 'ambientes', $ambiente, 'nombre');
				$idsubambientes	= getId('id', 'subambientes', $subambiente, 'nombre');
				$idresponsable	= getId('id', 'usuarios', $responsable, 'nombre');
				//$idactivos  	= getId('id', 'activos', $activo, 'codequipo');
				$idtipoplan 	= substr($tipoplan, 0, 1);
				//$usuresponsable	= buscarUsuario($responsable);
			/*	
				$arractivo = explode(' | ',$activo);
				$queryact = " SELECT id FROM activos WHERE nombre = '".$arractivo[0]."' AND serie = '".$arractivo[1]."' ";
				echo ($queryact);				
				$ract = $mysqli->query($queryact);
				$rowact = $ract->fetch_assoc();
				$idactivos = $rowact['id'];
				//debugL($idactivos);*/
				$query  = " INSERT INTO plan (id, titulo, frecuencia, formulario, observacion, tipoplan, responsable, idambientes, idsubambientes, idactivos, idempresas, idclientes)
							VALUES (null, '$actividad', '$frecuencia', '$formulario', '$observacion', '$idtipoplan', '$idresponsable', '$idambientes', '$idsubambientes', '$activo', '$idempresas', '$idclientes') ";
		//		debugL($query);
		//echo($query);
				$importadasExito++;
				$result = $mysqli->query($query);
				$id = $mysqli->insert_id;
				$listaImportadas .= '<li>Id #'.$id.' Empresa: '.$empresa.' Cliente: '.$cliente.' Contrato: '.$contrato.' Actividad: '.$actividad.'. Frecuencia: '.$frecuencia.'. Formulario: '.$formulario.'. Observación: '.$observacion.'. Tipo de plan: '.$tipoplan.'. Responsable: '.$responsable.'. Ambiente: '.$ambiente.'. Subambiente: '.$subambiente.'.</li>';
			}
			
			$listaImportadas .= '</ul>';
			
			if($result == true){
				$resultado  = $importadasExito.' filas importadas exitosamente. <br/>';
				$resultado .= $importadasError. ' filas con error. <br/>';
				$resultado .= $causasError;
				
				echo $resultado;
				
				// bitacora
				$acciones .= 'Fue importado el archivo '.$nombre.' para la creación de actividades del plan de mantenimiento.<br/><br/>';
				$acciones .= '<b>Resultado:</b><br/>';
				$acciones .= $resultado;
				$acciones .= '<b>Actividades importadas:</b><br/>';
				$acciones .= $listaImportadas;
				
				bitacora($_SESSION['usuario'],'Plan de Mantenimiento',$acciones,0,'');
			}else{
				$resultado = $importadasError. ' filas con error. <br/>';
				$resultado .= $causasError;
				echo $resultado;
			}

		}
	}

	function eliminaractividad(){
		global $mysqli;
		$id = $_REQUEST['id'];	
		$nombreactividad = getValor('titulo','plan',$id,'');
		//PLAN
		$query 	= "DELETE FROM plan WHERE id = '$id'";
		$result = $mysqli->query($query);
		//DIAS RECURRENCIA
		$queryD 	= "DELETE FROM diasfrecuencia WHERE idplan = '$id'";
		$resultD = $mysqli->query($queryD);
		//MES RECURRENCIA
		$queryM 	= "DELETE FROM mesfrecuencia WHERE idplan = '$id'";
		$resultM = $mysqli->query($queryM);
		if($result == true){
			echo 1;
			bitacora($_SESSION['usuario'], 'Plan de Mantenimiento', 'Fue eliminada la actividad <b>'.$nombreactividad.'</b> con el id #'.$id, $id, $query);
		}else{
			echo 0;
		}
	}

	function guardarfiltros() {
		global $mysqli;
		$data = $_REQUEST['data'];
		$usuario = $_SESSION['usuario'];
		$query  = "SELECT * FROM usuariosfiltros WHERE modulo = 'actividades' AND usuario = '$usuario' ";

		$result = $mysqli->query($query);
		$count = $result->num_rows;
		
		if( $count > 0 ){ 
			$query = "UPDATE usuariosfiltros SET filtrosmasivos = '$data' WHERE modulo = 'actividades' AND usuario = '$usuario'";
			bitacora($_SESSION['usuario'], 'Plan de Mantenimiento', 'Fueron actualizados los filtros masivos', 0, $query);
		}else{
			$query = "INSERT INTO usuariosfiltros VALUES (null, '$usuario', 'Actividades', '', '$data')";
			bitacora($_SESSION['usuario'], 'Plan de Mantenimiento', 'Fueron agregados los filtros masivos', 0, $query);
		}
		if($mysqli->query($query))
			echo true;		
	}

	function abrirfiltros() {
		global $mysqli;
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'actividades' AND usuario = '".$_SESSION['usuario']."'";
		$result = $mysqli->query($query);
		$response = new StdClass;
		if($result->num_rows >0){
			$row = $result->fetch_assoc();				
			$data = $row['filtrosmasivos'];
			$response->data = $data;
		} else {
			$response->data = '';
		}
		
		$response->success = true;
		echo json_encode($response);
	}

	function verificarfiltros() {
		global $mysqli;
		$query = " SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'actividades' AND usuario = '".$_SESSION['usuario']."' ";
		$result = $mysqli->query($query);
		$response = 0;
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();				
			$data = $row['filtrosmasivos'];
			$filtrosmasivos = json_decode($data);
			foreach($filtrosmasivos as $clave => $valor){
				if($valor != '' || $valor != 0){
					$response = 1;
				}
			}
		} else {
			$response = 0;
		}
		echo $response;
	}

	function limpiarFiltrosMasivos(){
		global $mysqli;
		$usuario = $_SESSION['usuario'];
		
		$query = "DELETE FROM usuariosfiltros WHERE modulo = 'actividades' AND usuario = '$usuario' ";
		bitacora($_SESSION['usuario'], 'Plan de Mantenimiento', 'Fueron eliminados los filtros masivos', 0, $query);
		if($mysqli->query($query))
			echo true;		
	}

	function abrirEvidencias() {
		$plan 	= $_REQUEST['plan'];		
		$_SESSION['plan'] = $plan;
		$myPath = '../planes/'.$plan;
		$target_path = utf8_decode($myPath);
		if (!file_exists($target_path)) {
			mkdir($target_path, 0777);
		}
		//$Path = dirname($_SERVER['PHP_SELF']) . '/../incidentes/'.$_SESSION['incidente'].'/';
		$Path = '/../planes/'.$_SESSION['plan'].'/';
		//debug($Path);
		$hash = strtr(base64_encode($Path), '+/=', '-_.');
		$hash = rtrim($hash, '.');		
		echo "l1_". $hash;		
	}

	function consultar_existencia(){
		global $mysqli;
		$id 		= $_REQUEST['id'];
		$titulo 	= $_REQUEST['tarea'];
		$idempresas = $_REQUEST['idempresas'];
		$idclientes = $_REQUEST['idclientes'];
		$idcontratos = $_REQUEST['idcontratos'];
		$responsable = $_REQUEST['responsable'];
		if($id == ''){
			$query  =  "SELECT id FROM plan WHERE titulo = '$titulo' AND idempresas = '$idempresas' AND idclientes = '$idclientes' AND idcontratos = '$idcontratos' ";
		}else{
			$query  =  "SELECT id FROM plan 
						WHERE titulo = '$titulo' AND idempresas = '$idempresas' AND idclientes = '$idclientes' AND idcontratos = '$idcontratos' AND responsable = '$responsable'
						AND	id != '$id' ";
		}
		
		$result = $mysqli->query($query); 
		if ($row = $result->fetch_assoc()){
			echo 1;
		} else {
			echo 0;
		}
	}

?>