<?php
    include("../conexion.php");  
	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}

	switch($oper){ 
		case "consultarReporte":
			  consultarReporte();
			  break;
	    case "consultarReporteGn":
    		  consultarReportesGenerados();
    		  break; 
		case "consultarCorrectivo":
			  consultarCorrectivo();
			  break; 
		case "guardarReporte":
			  guardarReporte();
			  break;
		case "editarReporte":
			  editarReporte();
			  break; 
		case "guardarFechasTemp":
			  guardarFechasTemp();
			  break; 
		case "guardarRepuestosTemp":
			  guardarRepuestosTemp();
			  break;
		case "borrarFechasTemp":
			  borrarFechasTemp();
			  break; 
		case "borrarRepuestosTemp":
			  borrarRepuestosTemp();
			  break; 
		case "guardarFechasDef":
			  guardarFechasDef();
			  break; 
		case "guardarRepuestosDef":
			  guardarRepuestosDef();
			  break;
		case "borrarFechasDef":
			  borrarFechasDef();
			  break; 
		case "borrarRepuestosDef":
			  borrarRepuestosDef();
			  break; 
		case "listarFechasTemp":
			  listarFechasTemp();
			  break;
	    case "listarFechasDef":
			  listarFechasDef();
			  break;
		case "listarRepuestosTemp":
			  listarRepuestosTemp();
			  break; 
		case "listarRepuestosDef":
			  listarRepuestosDef();
			  break;
		case "validarFechasTemp":
			  validarFechasTemp();
			  break;
		case "validarFechasDef":
			  validarFechasDef();
			  break;
     	case "validarGenerarReporte":
			  validarGenerarReporte();
			  break;
		case "limpiarTemporales":
			  limpiarTemporales();
			  break;  
		default:
			  echo "{failure:true}";
			  break;
	} 
	
	function validarGenerarReporte(){
		global $mysqli;
		$idreporte = (!empty($_REQUEST['idreporte']) ? $_REQUEST['idreporte'] : 0);
		
		$query = "  SELECT a.id, a.idincidente, a.departamento, a.ubicacionactivo, a.tiposervicio, a.fallareportada,
					a.trabajorealizado, a.observaciones, a.estadoactivo, a.firmatecnico, a.firmacliente1, 
					a.fechafirmatecnico, a.nombrecliente1, a.fechafirmacliente
					FROM `reporteservicios` a  
					WHERE a.id = '".$idreporte."'";
					
		$result = $mysqli->query($query);
		if($row = $result->fetch_assoc()){    
			$departamento  			= isset($row['departamento']) ? $row['departamento'] : 0;
			$ubicacionactivo 		= isset($row['ubicacionactivo']) ?  $row['ubicacionactivo'] : 0;
			$tiposervicio  			= isset($row['tiposervicio']) ? $row['tiposervicio'] : 0;
			$fallareportada  		= isset($row['fallareportada']) ? $row['fallareportada'] : 0;
			$trabajorealizado  		= isset($row['trabajorealizado']) ?  $row['trabajorealizado'] : 0;
			$observaciones  		= isset($row['observaciones']) ? $row['observaciones'] : 0;
			$firmatecnico 		 	= isset($row['firmatecnico']) ?  $row['firmatecnico'] : 0;
			$firmacliente1 		 	= isset($row['firmacliente1']) ? $row['firmacliente1'] : 0; 
			$fechafirmatecnico  	= isset($row['fechafirmatecnico']) ? $row['fechafirmatecnico'] : 0;
			$nombrecliente1  		= isset($row['nombrecliente1']) ?  $row['nombrecliente1'] : 0;
			$fechafirmacliente  	= isset($row['fechafirmacliente']) ?  $row['fechafirmacliente'] : 0;
			$estadoactivo  			= isset($row['estadoactivo']) ? $row['estadoactivo'] : 0; 
			 
			/* if($departamento == ""){
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "Debe grabar el campo Departamento"
                ));
			}elseif($ubicacionactivo == ""){
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "Debe grabar el campo Ubicación"
                ));
			}else */
			if($tiposervicio == ""){
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "Debe grabar el campo Tipo de Servicio"
                ));
			}elseif($fallareportada == ""){
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "Debe grabar el campo Falla o error reportado"
                ));
			}elseif($trabajorealizado == ""){
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "Debe grabar el campo Trabajo realizado"
                ));
			}elseif($observaciones == ""){
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "Debe grabar el campo Observaciones"
                ));
			}elseif($firmatecnico == ""){
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "Debe grabar la Firma del Técnico"
                ));
			}elseif($firmacliente1 == ""){
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "Debe grabar la Firma del Cliente 1"
                ));
			}elseif($fechafirmatecnico == ""){
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "Debe grabar el campo Fecha de firma del reporte"
                ));
			}elseif($nombrecliente1 == ""){
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "Debe grabar el campo Nombre 1 del Cliente"
                ));
			}elseif($fechafirmacliente == ""){
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "Debe grabar el campo Fecha de firma del Cliente"
                ));
			}elseif($estadoactivo == ""){
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "Debe grabar el campo Estado Final del activo"
                ));
			}else{
				echo 1;
			}
			
		}else{
			echo 0;
		}		
	}
	
	function guardarReporte(){
		global $mysqli;
		
		$idincidente	  = (!empty($_REQUEST['idincidente']) ? $_REQUEST['idincidente'] : 0);
		$departamento 	  = (!empty($_REQUEST['departamento']) ? $_REQUEST['departamento'] : '');
		$tiposervicio 	  = (!empty($_REQUEST['tiposervicio']) ? $_REQUEST['tiposervicio'] : 'sinasignar');
		$ubicacionactivo  = (!empty($_REQUEST['ubicacionactivo']) ? $_REQUEST['ubicacionactivo'] : '');
		$estadoactivo     = (!empty($_REQUEST['estadoactivo']) ? $_REQUEST['estadoactivo'] : 'sinasignar');
		$fallareportada   = (!empty($_REQUEST['fallareportada']) ? $_REQUEST['fallareportada'] : '');
		$trabajorealizado = (!empty($_REQUEST['trabajorealizado']) ?  $_REQUEST['trabajorealizado'] : '');
		$observaciones	  = (!empty($_REQUEST['observaciones']) ?  $_REQUEST['observaciones'] : '');
		$fechafirmatecnico= (!empty($_REQUEST['fechafirmatecnico']) ?  $_REQUEST['fechafirmatecnico'] : '');
		$nombrecliente1	  = (!empty($_REQUEST['nombrecliente1']) ? $_REQUEST['nombrecliente1'] : '');
		$nombrecliente2	  = (!empty($_REQUEST['nombrecliente2']) ? $_REQUEST['nombrecliente2'] : '');
		$fechafirmacliente= (!empty($_REQUEST['fechafirmacliente']) ? $_REQUEST['fechafirmacliente'] : '');
		$firmatecnico	  = (!empty($_REQUEST['firmatecnico']) ? $_REQUEST['firmatecnico'] : '');
		$firmacliente1	  = (!empty($_REQUEST['firmacliente1']) ? $_REQUEST['firmacliente1'] : '');
		$firmacliente2	  = (!empty($_REQUEST['firmacliente2']) ?  $_REQUEST['firmacliente2'] : '');
		$usuario 		  = (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : 0);
		
		if($fechafirmacliente != ''){ 
			$fechafirmacliente  = "'".$fechafirmacliente."'"; 	
		}else{
			$fechafirmacliente = 'null'; 
		}
		
		//--NUMERO-DE-REGISTRO------------------------------------------------------------------------------
        	$queryRow  = " SELECT a.id, a.idincidente, a.codigo FROM `reporteservicios` a WHERE a.idincidente = ".$idincidente.""; 
        	$resultRow = $mysqli->query($queryRow);
        	$row_cnt = $resultRow->num_rows;
        	$numReporte= $row_cnt === 0 ? $numReporte=1:$numReporte=$row_cnt+1;
        //------------------------------------------------------------------------------------------------------
		
		if($usuario != ""){
			//Guarda cabecera de reporte
			$query = "INSERT INTO reporteservicios (idincidente,departamento,ubicacionactivo,tiposervicio,estadoactivo,fallareportada,trabajorealizado,observaciones,fechafirmatecnico,nombrecliente1,nombrecliente2,fechafirmacliente,firmatecnico,firmacliente1,firmacliente2,fecha)
						VALUES ($idincidente,'$departamento','$ubicacionactivo','$tiposervicio','$estadoactivo','$fallareportada','$trabajorealizado','$observaciones','$fechafirmatecnico','$nombrecliente1','$nombrecliente2',$fechafirmacliente,'$firmatecnico','$firmacliente1','$firmacliente2',NOW())";
			//debug('repor:'.$query);
			$result = $mysqli->query($query);
			if($result == true){
				$idreporte = $mysqli->insert_id;
				
				//Generar código de reporte de servicio
				$generarcodigo = " UPDATE reporteservicios SET codigo = 'RS-".$idincidente."-".$numReporte."' WHERE id = ".$idreporte." ";
				$resultGc = $mysqli->query($generarcodigo);
				
				//Guarda fechas de reporte
				$fechas = " INSERT INTO reporteserviciosfechas (fecha,tiempoviaje,tiempolabor,tiempoespera,horainicio,horafin,idreporte,idincidente)
								SELECT fecha,tiempoviaje,tiempolabor,tiempoespera,horainicio,horafin,".$idreporte.",idincidente FROM reporteserviciosfechastemp
								WHERE usuario = '".$usuario."' AND idincidente = '".$idincidente."'"; 
				debug('fechas:'.$fechas);
				$resultF = $mysqli->query($fechas);
				if($resultF == true){
					$delete = " DELETE FROM reporteserviciosfechastemp WHERE usuario = '".$usuario."' AND idincidente = '".$idincidente."'";
					$mysqli->query($delete);
				} 
				
				//Guarda repuestos de reporte
				$repuestos = " INSERT INTO reporteserviciosrepuestos (codigo,cantidad,descripcion,idreporte,idincidente)
								SELECT codigo,cantidad,descripcion,".$idreporte.",idincidente FROM reporteserviciosrepuestostemp
								WHERE usuario = '".$usuario."' AND idincidente = '".$idincidente."'"; 
				$resultR = $mysqli->query($repuestos);
				if($resultR == true){
					$delete = " DELETE FROM reporteserviciosrepuestostemp WHERE usuario = '".$usuario."' AND idincidente = '".$idincidente."'";
					$mysqli->query($delete);
				}
				if($estadoactivo=='fueraservicio'){
					$queryF = " UPDATE incidentes SET fueraservicio = 1 WHERE id = ".$idincidente."";
					$resultF = $mysqli->query($queryF);
				}
				//BITACORA
				bitacora($_SESSION['usuario'], "Incidentes", "Se ha guardado el reporte de servicio del correctivo  #".$idincidente, $idincidente, $query);
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo 0;
		} 
	}

	function editarReporte(){
		global $mysqli;
		
		$idincidente	  = (!empty($_REQUEST['idincidente']) ? $_REQUEST['idincidente'] : 0);
		$idreporte	  	  = (!empty($_REQUEST['idreporte']) ? $_REQUEST['idreporte'] : 0);
		$departamento 	  = (!empty($_REQUEST['departamento']) ? $_REQUEST['departamento'] : '');
		$tiposervicio 	  = (!empty($_REQUEST['tiposervicio']) ? $_REQUEST['tiposervicio'] : 'sinasignar');
		$ubicacionactivo  = (!empty($_REQUEST['ubicacionactivo']) ? $_REQUEST['ubicacionactivo'] : '');
		$estadoactivo     = (!empty($_REQUEST['estadoactivo']) ? $_REQUEST['estadoactivo'] : 'sinasignar');
		$fallareportada   = (!empty($_REQUEST['fallareportada']) ? $_REQUEST['fallareportada'] : '');
		$trabajorealizado = (!empty($_REQUEST['trabajorealizado']) ? $_REQUEST['trabajorealizado'] : '');  
		$observaciones	  = (!empty($_REQUEST['observaciones']) ? $_REQUEST['observaciones'] : '');  
		$fechafirmatecnico= (!empty($_REQUEST['fechafirmatecnico']) ? $_REQUEST['fechafirmatecnico'] : ''); 
		$nombrecliente1	  = (!empty($_REQUEST['nombrecliente1']) ? $_REQUEST['nombrecliente1'] : '');
		$nombrecliente2	  = (!empty($_REQUEST['nombrecliente2']) ? $_REQUEST['nombrecliente2'] : '');
		$fechafirmacliente= (!empty($_REQUEST['fechafirmacliente']) ? $_REQUEST['fechafirmacliente'] : '');
		$firmatecnico	  = (!empty($_REQUEST['firmatecnico']) ? $_REQUEST['firmatecnico'] : '');
		$firmacliente1	  = (!empty($_REQUEST['firmacliente1']) ? $_REQUEST['firmacliente1'] : ''); 
		$firmacliente2	  = (!empty($_REQUEST['firmacliente2']) ? $_REQUEST['firmacliente2'] : '');
		
		if($fechafirmacliente != ''){ 
			$fechafirmacliente  = "'".$fechafirmacliente."'"; 		
		}else{
			$fechafirmacliente = 'null'; 
		}
		 
		$query = "	UPDATE reporteservicios SET departamento = '$departamento', ubicacionactivo = '$ubicacionactivo',
					tiposervicio = '$tiposervicio', estadoactivo = '$estadoactivo', fallareportada = '$fallareportada', trabajorealizado = '$trabajorealizado', 
					observaciones = '$observaciones', fechafirmatecnico = '$fechafirmatecnico', nombrecliente1 = '$nombrecliente1',
					nombrecliente2 = '$nombrecliente2', fechafirmacliente = $fechafirmacliente ";
					
		if($firmatecnico !== 0 && $firmatecnico !== ''){
			$query .= " ,firmatecnico = '$firmatecnico'";
		}
		//Permitir que guarde firma vacía al editar.
		if($firmacliente1 !== '-' /* && $firmacliente1 !== '' */){
			$query .= " ,firmacliente1 = '$firmacliente1'";
			//echo "cli1-1".$firmacliente1;
		}else{
			//echo "cli1-2".$firmacliente1;
		} 
		if($firmacliente2 !== '-'){
			$query .= " ,firmacliente2 = '$firmacliente2'"; 
			//echo "cli2-1".$firmacliente2;
		}else{
			//echo "cli2-2".$firmacliente2;
		} 
		$query .= "	WHERE id = '".$idreporte."'"; 
		//debug('editar'.$query);
		$result = $mysqli->query($query);
		if($result == true){
			if($estadoactivo=='fueraservicio'){
				$queryF = " UPDATE incidentes SET fueraservicio = 1 WHERE id = ".$idincidente."";
				$resultF = $mysqli->query($queryF);
			}
			//BITACORA
			bitacora($_SESSION['usuario'], "Incidentes", "Se ha editado el reporte de servicio del correctivo  #".$idincidente, $idincidente, $query);
			echo true;
		}else{
			echo false;
		} 
	}
	
	function guardarFechasTemp(){
		global $mysqli;
		
		$idincidente	= $_REQUEST['idincidente'];
		$fechaatencion	= $_REQUEST['fechaatencion'];
		$tiempoviaje 	= $_REQUEST['tiempoviaje'];
		//$tiempolabor 	= $_REQUEST['tiempolabor'];
		$tiempoespera 	= $_REQUEST['tiempoespera'];
		$horainicio     = $_REQUEST['horainicio']; 
		$horafin 		= $_REQUEST['horafin']; 
		$usuario 		= $_SESSION['usuario'];  
		$tiempolabor    = date("H:i",strtotime("00:00") +strtotime($horafin) - strtotime($horainicio) ); 

		$query = "INSERT INTO reporteserviciosfechastemp (idincidente,fecha,tiempoviaje,tiempolabor,tiempoespera,horainicio,horafin,usuario)
					VALUES ('$idincidente','$fechaatencion','$tiempoviaje','$tiempolabor','$tiempoespera','$horainicio','$horafin','$usuario')";
		//debug('tiempolabor:'.$tiempolabor);
		if($mysqli->query($query)){
			$id = $mysqli->insert_id;
			//BITACORA
			//bitacora($_SESSION['usuario'], "Incidentes", "Se ha editado el reporte de servicio del correctivo #".$incidente, $incidente, $queryI);
			echo true;
		}else{
			echo false;
		} 
	}

	function guardarFechasDef(){
		global $mysqli;
		
		$idincidente	= $_REQUEST['idincidente'];
		$idreporte		= $_REQUEST['idreporte'];
		$fechaatencion	= $_REQUEST['fechaatencion'];
		$tiempoviaje 	= $_REQUEST['tiempoviaje'];
		//$tiempolabor 	= $_REQUEST['tiempolabor'];
		$tiempoespera 	= $_REQUEST['tiempoespera'];
		$horainicio     = $_REQUEST['horainicio']; 
		$horafin 		= $_REQUEST['horafin'];  
		$tiempolabor    = date("H:i",strtotime("00:00") +strtotime($horafin) - strtotime($horainicio) ); 
		
		$query = "INSERT INTO reporteserviciosfechas (idreporte,idincidente,fecha,tiempoviaje,tiempolabor,tiempoespera,horainicio,horafin)
					VALUES ('$idreporte','$idincidente','$fechaatencion','$tiempoviaje','$tiempolabor','$tiempoespera','$horainicio','$horafin')";
		//debug('fechasdef:'.$query);
		if($mysqli->query($query)){
			$id = $mysqli->insert_id;
			//BITACORA
			//bitacora($_SESSION['usuario'], "Incidentes", "Se ha rgenerado  #".$incidente, $incidente, $queryI);
			echo true;
		}else{
			echo false;
		} 
	}
	
	function borrarFechasTemp()
	{
		global $mysqli;

		$id 	  = $_REQUEST['id'];  
		$query    = " DELETE FROM reporteserviciosfechastemp WHERE id = '$id'";
		$result   = $mysqli->query($query);
		if($result){ 
			echo 1;
		}else{
			echo 0;
		} 
		//bitacora($_SESSION['usuario'], "Incidentes", 'El Comentario #: '.$id.' fue eliminado.', $id, $query); 
	} 

	function borrarFechasDef()
	{
		global $mysqli;

		$id 	  = $_REQUEST['id'];  
		$query    = " DELETE FROM reporteserviciosfechas WHERE id = '$id'";
		$result   = $mysqli->query($query);
		if($result){ 
			echo 1;
		}else{
			echo 0;
		} 
		//bitacora($_SESSION['usuario'], "Incidentes", 'El Comentario #: '.$id.' fue eliminado.', $id, $query); 
	}
	
	function guardarRepuestosTemp(){
		global $mysqli;
		
		$idincidente = $_REQUEST['idincidente'];
		$codigo		 = $_REQUEST['codigo'];
		$cantidad 	 = $_REQUEST['cantidad'];
		$descripcion = $_REQUEST['descripcion']; 
		$usuario 	 = $_SESSION['usuario']; 
		
		$query = "INSERT INTO reporteserviciosrepuestostemp (idincidente,codigo,cantidad,descripcion,usuario)
					VALUES ('$idincidente','$codigo','$cantidad','$descripcion','$usuario')";
		//debug('repuestostemp:'.$query);
		if($mysqli->query($query)){
			$id = $mysqli->insert_id;
			//BITACORA
			//bitacora($_SESSION['usuario'], "Incidentes", "Se ha rgenerado  #".$incidente, $incidente, $queryI);
			echo true;
		}else{
			echo false;
		} 
	}	
	
	function guardarRepuestosDef(){
		global $mysqli;
		
		$idreporte	 = $_REQUEST['idreporte'];
		$idincidente = $_REQUEST['idincidente'];
		$codigo		 = $_REQUEST['codigo'];
		$cantidad 	 = $_REQUEST['cantidad'];
		$descripcion = $_REQUEST['descripcion']; 
		$usuario 	 = $_SESSION['usuario']; 
		
		$query = "INSERT INTO reporteserviciosrepuestos (idreporte,idincidente,codigo,cantidad,descripcion)
					VALUES ('$idreporte','$idincidente','$codigo','$cantidad','$descripcion')";
		//debug('repuestosdef:'.$query);
		if($mysqli->query($query)){
			$id = $mysqli->insert_id;
			//BITACORA
			//bitacora($_SESSION['usuario'], "Incidentes", "Se ha rgenerado  #".$incidente, $incidente, $queryI);
			echo true;
		}else{
			echo false;
		} 
	}
	
	function borrarRepuestosTemp()
	{
		global $mysqli;

		$id 	  = $_REQUEST['id'];  
		$query    = " DELETE FROM reporteserviciosrepuestostemp WHERE id = '$id'";
		$result   = $mysqli->query($query);
		if($result){ 
			echo 1;
		}else{
			echo 0;
		} 
		//bitacora($_SESSION['usuario'], "Incidentes", 'El Comentario #: '.$id.' fue eliminado.', $id, $query); 
	}

	function borrarRepuestosDef()
	{
		global $mysqli;

		$id 	  = $_REQUEST['id'];  
		$query    = " DELETE FROM reporteserviciosrepuestos WHERE id = '$id'";
		$result   = $mysqli->query($query);
		if($result){ 
			echo 1;
		}else{
			echo 0;
		} 
		//bitacora($_SESSION['usuario'], "Incidentes", 'El Comentario #: '.$id.' fue eliminado.', $id, $query); 
	}
	
	function limpiarTemporales(){
		global $mysqli;
		$usuario = $_SESSION['usuario'];
		
		$queryF = " DELETE FROM reporteserviciosfechastemp WHERE usuario = '$usuario'";
		$resultF = $mysqli->query($queryF);
		
		$queryR = " DELETE FROM reporteserviciosrepuestostemp WHERE usuario = '$usuario'";
		$resultR = $mysqli->query($queryR);
	}
	
	function consultarCorrectivo(){
		global $mysqli;
		$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
		$resultado 	 = array();
		$query  = " SELECT a.id, a.titulo, b.nombre AS sitio, 
					c.serie AS serie, c.nombre AS equipo, ma.nombre AS marca, mo.nombre AS modelo, a.asignadoa,  
					IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0), a.fechacreacion,'') AS fechacreacion,
					a.horacreacion,	a.fueraservicio, d.nombre as nombretecnico
					FROM incidentes a 
					LEFT JOIN ambientes b ON a.idambientes = b.id
					LEFT JOIN activos c ON a.idactivos = c.id AND c.serie != '' 
					LEFT JOIN usuarios d ON a.asignadoa = d.correo 
					LEFT JOIN marcas ma ON c.idmarcas = ma.id
					LEFT JOIN modelos mo ON c.idmodelos = mo.id 
					WHERE a.id = $id "; 
		//debug($query);
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			if($row['marca'] == '0')
				$row['marca']='';
			if($row['modelo'] == '0')
				$row['modelo']=''; 
			  
			$resultado[] = array(
						'id' 					=> $row['id'], 
						'sitio' 				=> $row['sitio'],
						'serie' 				=> $row['serie'], 
						'equipo' 				=> $row['equipo'], 
						'marca' 				=> $row['marca'],
						'modelo' 				=> $row['modelo'], 
						'asignadoa' 			=> $row['asignadoa'], 
						'fechacreacion' 		=> $row['fechacreacion'], 
						'fueraservicio' 		=> $row['fueraservicio'],
						'nombretecnico' 		=> $row['nombretecnico']
					);
		}
		echo json_encode($resultado);
	}
	
	function consultarReporte(){
		global $mysqli;
		$idincidente = (!empty($_REQUEST['idincidente']) ? $_REQUEST['idincidente'] : 0);
		
		$resultado 	 = array();
		$query  = " SELECT a.id, a.idincidente, a.codigo, a.departamento, a.ubicacionactivo, a.tiposervicio, a.fallareportada,
					a.trabajorealizado, a.observaciones, a.estadoactivo, c.nombre AS sitio, d.serie AS serie, d.nombre AS equipo, ma.nombre AS marca, mo.nombre AS modelo, 
					b.asignadoa, IF(( b.fechacreacion is not null OR LENGTH(ltrim(rTrim(b.fechacreacion))) > 0), b.fechacreacion,'')
					AS fechacreacion, b.horacreacion, b.fueraservicio, a.firmatecnico, a.firmacliente1, a.firmacliente2, 
					a.fechafirmatecnico, a.nombrecliente1, a.nombrecliente2, a.fechafirmacliente, e.nombre as nombretecnico, a.estatus
					FROM `reporteservicios` a 
					INNER JOIN incidentes b ON a.idincidente = b.id 
					LEFT JOIN ambientes c ON b.idambientes = c.id
					LEFT JOIN activos d ON b.idactivos = d.id AND d.serie != ''
					LEFT JOIN usuarios e ON b.asignadoa = e.correo
					LEFT JOIN marcas ma ON d.idmarcas = ma.id
					LEFT JOIN modelos mo ON d.idmodelos = mo.id 
					WHERE a.idincidente = ".$idincidente." AND a.estatus !=1 "; 
					
		//debug($query);
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			if($row['marca'] == '0')
				$row['marca']='';
			if($row['modelo'] == '0')
				$row['modelo']=''; 
			  
			$resultado[] = array(
						'id' 					=> $row['id'], 
						'codigo'				=> $row['codigo'], 
						'sitio' 				=> $row['sitio'],
						'serie' 				=> $row['serie'], 
						'equipo' 				=> $row['equipo'], 
						'marca' 				=> $row['marca'],
						'modelo' 				=> $row['modelo'], 
						'asignadoa' 			=> $row['asignadoa'], 
						'fechacreacion' 		=> $row['fechacreacion'],  
						'fueraservicio' 		=> $row['fueraservicio'],
						'departamento' 			=> $row['departamento'], 
						'ubicacionactivo' 		=> $row['ubicacionactivo'], 
						'tiposervicio' 			=> $row['tiposervicio'], 
						'fallareportada' 		=> $row['fallareportada'], 
						'trabajorealizado' 		=> $row['trabajorealizado'], 
						'observaciones' 		=> $row['observaciones'], 
						'firmatecnico'		 	=> $row['firmatecnico'], 
						'firmacliente1'		 	=> $row['firmacliente1'], 
						'firmacliente2'		 	=> $row['firmacliente2'], 
						'fechafirmatecnico' 	=> $row['fechafirmatecnico'], 
						'nombrecliente1' 		=> $row['nombrecliente1'], 
						'nombrecliente2' 		=> $row['nombrecliente2'], 
						'fechafirmacliente' 	=> $row['fechafirmacliente'], 
						'estadoactivo' 			=> $row['estadoactivo'],
						'nombretecnico' 		=> $row['nombretecnico'],
						'estatus'		 		=> $row['estatus']
					);
		}
		if(!empty($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
		
	} 
	
	/*-LIST-REPORTE-DE-SERVICO-GENERADOS------------------------------------------------------------------*/
	function consultarReportesGenerados(){
		global $mysqli;
		/*-PAGINACION---------------------------------------------------------------------------------*/
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
		$start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		
		/*------------------------------------------------------------------------------------------------*/
		$idincidente = (!empty($_REQUEST['idincidente']) ? $_REQUEST['idincidente'] : 0);
		$resultado 	 = array();
		$query  = " SELECT a.id, a.idincidente, a.codigo, a.departamento, a.ubicacionactivo, a.tiposervicio, a.fallareportada,
					a.trabajorealizado, a.observaciones, a.estadoactivo, c.nombre AS sitio, d.serie AS serie, d.nombre AS equipo, ma.nombre AS marca, mo.nombre AS modelo, 
					b.asignadoa, IF(( b.fechacreacion is not null OR LENGTH(ltrim(rTrim(b.fechacreacion))) > 0), b.fechacreacion,'')
					AS fechacreacion, b.horacreacion, b.fueraservicio, a.firmatecnico, a.firmacliente1, a.firmacliente2, 
					a.fechafirmatecnico, a.nombrecliente1, a.nombrecliente2, a.fechafirmacliente, e.nombre as nombretecnico, a.estatus
					FROM `reporteservicios` a 
					INNER JOIN incidentes b ON a.idincidente = b.id 
					LEFT JOIN ambientes c ON b.idambientes = c.id
					LEFT JOIN activos d ON b.idactivos = d.id AND d.serie != ''
					LEFT JOIN usuarios e ON b.asignadoa = e.correo
					LEFT JOIN marcas ma ON d.idmarcas = ma.id
					LEFT JOIN modelos mo ON d.idmodelos = mo.id 
					WHERE a.idincidente = ".$idincidente." AND a.estatus =1" ; 
					
		//debug($query);
		$query  .= " ORDER BY a.id ASC ";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			/*-----------------------------------------------------------------------*/
			 $enlace ='<a href="https://toolkit.maxialatam.com/soporteqa/incidentes/'.$idincidente.'/Reporte '.$row['codigo'].'.jpg" target="_blank" class="dt-blue">'.$row['codigo'].'</a>';
		  
			/*-----------------------------------------------------------------------*/
			if($row['marca'] == '0')
				$row['marca']='';
			if($row['modelo'] == '0')
				$row['modelo']=''; 
			  
			$resultado[] = array(
						'id' 					=> $row['id'], 
						'codigo'				=> $row['codigo'], 
						'fechacreacion' 		=> $row['fechacreacion'],  
						'nombretecnico' 	=> $row['nombretecnico'], 
						'enlace' 		    => $enlace,
					);
		}
		$response = array(
		  "draw" => intval($draw),
		  "recordsTotal" => intval($recordsTotal),
		  "recordsFiltered" => intval($recordsTotal),
		  "data" => $resultado
		);
		echo json_encode($response);
	} 
/*-------------------------------------------------------------------------------------------------------------------------*/

	function listarFechasTemp(){
		global $mysqli;
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
		$start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		
		$idincidente   		 = (!empty($_REQUEST['idincidente']) ? $_REQUEST['idincidente'] : 0);
		
		$usuario 			 = $_SESSION['usuario'];
		 
		$resultado = array();
		
		$query  = " SELECT a.id, DATE(a.fecha) as fecha, DATE_FORMAT(a.tiempoviaje,'%H:%i') AS tiempoviaje,
					DATE_FORMAT(a.tiempolabor,'%H:%i') AS tiempolabor, DATE_FORMAT(a.tiempoespera,'%H:%i') AS tiempoespera, 
					DATE_FORMAT(a.horainicio,'%H:%i') AS horainicio, DATE_FORMAT(a.horafin,'%H:%i') AS horafin 
					FROM reporteserviciosfechastemp a  
					WHERE a.usuario = '$usuario' AND idincidente = '$idincidente' ORDER BY a.id DESC ";
		debug('cris'.$query);			 
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		while($row = $result->fetch_assoc()){
			$acciones = " <div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>
							<span class='icon-col red fa fa-trash boton-eliminar-fecha' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Fecha' data-placement='right'></span>   
						</div> ";
						
			$resultado[] = array(
				'id'			=> $row['id'],
				'acciones'		=> $acciones,
				'fecha'			=> $row['fecha'],
				'tiempoviaje'	=> $row['tiempoviaje'],
				'tiempolabor'	=> $row['tiempolabor'],
				'tiempoespera'	=> $row['tiempoespera'],
				'horainicio'	=> $row['horainicio'],
				'horafin'		=> $row['horafin']
			);
		}
		$response = array(
		  "draw" => intval($draw),
		  "recordsTotal" => intval($recordsTotal),
		  "recordsFiltered" => intval($recordsTotal),
		  "data" => $resultado
		);
		echo json_encode($response);
	}
	
	function listarFechasDef(){
		global $mysqli;
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
		$start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10); 
		$idreporte   		 = (!empty($_REQUEST['idreporte']) ? $_REQUEST['idreporte'] : 0); 
		 
		$resultado = array();
		
		$query  = " SELECT a.id, DATE(a.fecha) as fecha, DATE_FORMAT(a.tiempoviaje,'%H:%i') AS tiempoviaje,
					DATE_FORMAT(a.tiempolabor,'%H:%i') AS tiempolabor, DATE_FORMAT(a.tiempoespera,'%H:%i') AS tiempoespera, 
					DATE_FORMAT(a.horainicio,'%H:%i') AS horainicio, DATE_FORMAT(a.horafin,'%H:%i') AS horafin 
					FROM reporteserviciosfechas a  
					WHERE a.idreporte = '$idreporte' ORDER BY a.id DESC ";
		 			
		$queryE = " SELECT estatus FROM reporteservicios WHERE id = '".$idreporte."'";
		$resultE = $mysqli->query($queryE);
		if($rowE = $resultE->fetch_assoc()){
			$estatus = $rowE['estatus'];
		}
			
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		while($row = $result->fetch_assoc()){
			
			if($estatus == 0){
				$eliminar = "<span class='icon-col red fa fa-trash boton-eliminar-fecha' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Fecha' data-placement='right'></span>";
			}else{
				$eliminar = "<span class='icon-col red fa fa-trash' style='background:#D5DBDB' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Fecha' data-placement='right'></span>";
			}
			
			$acciones = " <div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>".$eliminar."</div>";
					
			$resultado[] = array(
				'id'			=> $row['id'],
				'acciones'		=> $acciones,
				'fecha'			=> $row['fecha'],
				'tiempoviaje'	=> $row['tiempoviaje'],
				'tiempolabor'	=> $row['tiempolabor'],
				'tiempoespera'	=> $row['tiempoespera'],
				'horainicio'	=> $row['horainicio'],
				'horafin'		=> $row['horafin']
			);
		}
		$response = array(
		  "draw" => intval($draw),
		  "recordsTotal" => intval($recordsTotal),
		  "recordsFiltered" => intval($recordsTotal),
		  "data" => $resultado
		);
		echo json_encode($response);
	}	
	
	function listarRepuestosTemp(){
		global $mysqli;
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
		$start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		$idincidente   		 = (!empty($_REQUEST['idincidente']) ? $_REQUEST['idincidente'] : 0);
		$usuario 			 = $_SESSION['usuario'];
		 
		$resultado = array();
		
		$query  = " SELECT a.id, a.codigo, a.cantidad, a.descripcion
					FROM reporteserviciosrepuestostemp a  
					WHERE a.usuario = '$usuario' AND idincidente = '$idincidente' ORDER BY a.id DESC ";
		 
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		while($row = $result->fetch_assoc()){
			$acciones = " <div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>
							<span class='icon-col red fa fa-trash boton-eliminar-repuesto' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Repuesto' data-placement='right'></span>   
						</div> "; 
						
			$resultado[] = array(
				'id'			=> $row['id'],
				'acciones'		=> $acciones,
				'codigo'		=> $row['codigo'],
				'cantidad'		=> $row['cantidad'],
				'descripcion'	=> $row['descripcion'] 
			);
		}
		$response = array(
		  "draw" => intval($draw),
		  "recordsTotal" => intval($recordsTotal),
		  "recordsFiltered" => intval($recordsTotal),
		  "data" => $resultado
		);
		echo json_encode($response);
	}
	
	function listarRepuestosDef(){
		global $mysqli;
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
		$start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10); 
		$idreporte   		 = (!empty($_REQUEST['idreporte']) ? $_REQUEST['idreporte'] : 0); 
		 
		$resultado = array();
		
		$query  = " SELECT a.id, a.codigo, a.cantidad, a.descripcion
					FROM reporteserviciosrepuestos a  
					WHERE a.idreporte = '$idreporte' ORDER BY a.id DESC ";
		
		$queryE = " SELECT estatus FROM reporteservicios WHERE id = '".$idreporte."'";
		$resultE = $mysqli->query($queryE);
		if($rowE = $resultE->fetch_assoc()){
			$estatus = $rowE['estatus'];
		}		
		
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		
		while($row = $result->fetch_assoc()){
			
			if($estatus == 0){
				$eliminar = "<span class='icon-col red fa fa-trash boton-eliminar-repuesto' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Repuesto' data-placement='right'></span>";
			}else{
				$eliminar = "<span class='icon-col red fa fa-trash' style='background:#D5DBDB' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Repuesto' data-placement='right'></span>"; 
			}
			
			$acciones = "<div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>".$eliminar."</div> ";
			$resultado[] = array(
				'id'			=> $row['id'],
				'acciones'		=> $acciones,
				'codigo'		=> $row['codigo'],
				'cantidad'		=> $row['cantidad'],
				'descripcion'	=> $row['descripcion']
			);
		}
		$response = array(
		  "draw" => intval($draw),
		  "recordsTotal" => intval($recordsTotal),
		  "recordsFiltered" => intval($recordsTotal),
		  "data" => $resultado
		);
		echo json_encode($response);
	}
	
	/*
	function validarFechasTemp(){
		global $mysqli;
		
		$usuario = $_SESSION['usuario'];
		
		$query  = " SELECT a.id as total
					FROM reporteserviciosfechastemp a  
					WHERE a.usuario = '$usuario' ORDER BY a.id DESC ";
				
		$result = $mysqli->query($query);
		$total  = $result->num_rows;	 
		if($total>0){
			echo 1;
		}else{
			echo 0;
		}
	}
    */
    
    function validarFechasTemp(){
		global $mysqli;
	
		$usuario = $_SESSION['usuario'];
		$idincidente   		 = (!empty($_REQUEST['idincidente']) ? $_REQUEST['idincidente'] : 0);
		
		$query  = " SELECT a.id as total FROM reporteserviciosfechastemp a 
		            WHERE a.usuario = '$usuario'AND a.idincidente ='$idincidente' ORDER BY a.id DESC ";
				
		$result = $mysqli->query($query);
		$total  = $result->num_rows;	 
		if($total>0){
			echo 1;
		}else{
			echo 0;
		}
	}
    
    
    
	function validarFechasDef(){
		global $mysqli;
		
		$idreporte  = (!empty($_REQUEST['idreporte']) ? $_REQUEST['idreporte'] : 0);
		
		$query  = " SELECT a.id as total
					FROM reporteserviciosfechas a  
					WHERE a.idreporte = '".$idreporte."' ORDER BY a.id DESC ";
					
		$result = $mysqli->query($query);
		$total  = $result->num_rows;	
		if($total>0){
			echo 1;
		}else{
			echo 0;
		}
	}
?>