<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "proveedores": 
			  proveedores();
			  break;		
		case "guardarProveedor":
			  guardarProveedor();
			  break;
		case "actualizarProveedor":
			  actualizarProveedor();
			  break;
	    case "getProveedor":
			  getProveedor();
			  break;
		case "eliminarProveedor":
			  eliminarProveedor();
			  break;
		case "hayRelacion":
			  hayRelacion();
			  break;
		case "cargarproveedoresclientes":
			  cargarproveedoresclientes();
			  break;
		case "asociarproveedoresclientes":
			  asociarproveedoresclientes();
			  break;
		case "eliminarproveedoresclientes":
			  eliminarproveedoresclientes();
			  break;
		case "hayRelacionPc":
			  hayRelacionPc();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	

	function proveedores() 
	{
		global $mysqli;		
		
		$where = "";
		$where2 = array();
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		//contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);
		/*----------------------------------------------------------------------
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		//Obtener el nombre de la columna de clasificación de su índice
		$orderBy= (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ?$_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );
		//ASC or DESC*/
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); 
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		/*--------------------------------------------------------------------*/
		$nivel				 = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		$idclientes 		 = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : 0);
		$idproyectos 		 = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : 0);
		$nivel				 = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		
		$query  = " SELECT 	LEFT(c.nombre,45) AS cliente, LEFT(d.nombre,45) AS proyecto, 
					a.id, LEFT(a.nombre,45) as nombre, a.encargado, 
					a.telefono, a.correo, a.cuentacontrato, a.fechainiciocontrato, a.fechafincontrato, a.serviciocontratado, a.incluyepiezas, a.horarioatencioncont,a.utilizarasym
		            FROM proveedores a
		            LEFT JOIN proveedorespuente b ON b.idproveedores = a.id 
					LEFT JOIN clientes c ON FIND_IN_SET(c.id, b.idclientes)
					LEFT JOIN proyectos d ON  FIND_IN_SET(d.id, b.idproyectos)
		            WHERE 1 = 1 "; 
		 
		if($nivel != 1 || $nivel != 2){
			if($idclientes != ''){
				$arr = strpos($idclientes, ',');
				if ($arr !== false) {
					$query  .= " AND b.idclientes IN (".$idclientes.") ";
				}else{
					$query  .= " AND find_in_set(".$idclientes.",b.idclientes) ";
				}  
			}
			if($idproyectos != ''){
				$arr = strpos($idproyectos, ',');
				if ($arr !== false) {
					$query  .= " AND b.idproyectos IN (".$idproyectos.") ";
				}else{
					$query  .= " AND find_in_set(".$idproyectos.",b.idproyectos) ";
				}  
			}	
		}		
		
		$hayFiltros = 0;
		for($i=0 ; $i<count($_REQUEST['columns']);$i++){
			$column = $_REQUEST['columns'][$i]['data'];
			
			if ($_REQUEST['columns'][$i]['search']['value']!="") {
			    
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);

				if ($column == 'id') {
					$column = 'a.id';
    				$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column =='fechainiciocontrato') {
					$column = 'a.fechainiciocontrato';
    				$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'fechafincontrato') {
					$column = 'a.fechafincontrato';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'serviciocontratado') {
					$column = 'a.serviciocontratado ';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'incluyepiezas') {
					$column = 'a.incluyepiezas';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'horarioatencioncont') {
					$column = 'a.horarioatencioncont';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'utilizarasym') {
					$column = 'a.utilizarasym';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'cliente') {
					$column = 'c.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'proyecto') {
					$column = 'd.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'nombre') {
					$column = 'a.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'encargado') {
					$column = 'a.encargado';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'telefono') {
					$column = 'a.telefono';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'correo') {
					$column = 'a.correo';
					$where2[]= " $column like '%".$campo."%' ";
				}
			    if ($column == 'cuentacontrato') {
					$column = 'a.cuentacontrato';
					$where2[]= " $column like '%".$campo."%' ";
				}
				
			    $hayFiltros++;
			}
		}

		if ($hayFiltros > 0)
			$where = " AND ".implode(" AND " , $where2)." ";// id like '%searchValue%' or name like '%searchValue%'
		else
			$where = "";
		$query .= " $where ";
		/*
		$searchGeneral= (!empty($_POST['search']['value']) ? $_POST['search']['value'] : '');		
		
		if($searchGeneral != ''){
			$where.= " AND (
			a.id like '%".$searchGeneral."%' OR
			a.nombre like '%".$searchGeneral."%' OR
			a.descripcion like '%".$searchGeneral."%' OR
			a.tipo like '%".$searchGeneral."%' OR
            c.nombre like '%".$searchGeneral."%' OR
			d.nombre like '%".$searchGeneral."%' OR
			b.nombre  like '%".$searchGeneral."%' OR
		    c.nombre like '%".$searchGeneral."%' OR 
			a.id like '%".$searchGeneral."%' OR
			a.nombre like '%".$searchGeneral."%' OR
			a.encargado like '%".$searchGeneral."%' OR
			a.telefono like '%".$searchGeneral."%' OR
			a.correo like '%".$searchGeneral."%' OR
			a.cuentacontrato like '%".$searchGeneral."%' OR
			a.fechainiciocontrato like '%".$searchGeneral."%' OR
			a.fechafincontrato like '%".$searchGeneral."%' OR
			a.serviciocontratado like '%".$searchGeneral."%' OR
			a.incluyepiezas like '%".$searchGeneral."%' OR
			a.horarioatencioncont like '%".$searchGeneral."%' OR
			a.utilizarasym like '%".$searchGeneral."%'
			)";
    	}
	
		$query  .= " $where ";*/
		//debugL($query);
	    $query .= " GROUP BY a.id ";
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		$query  .= " ORDER BY a.id ASC LIMIT $start, $length ";
	//	$query  .= " ORDER BY a.id ASC";
	//	echo $query;
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		$response = array();
		
		while($row = $result->fetch_assoc()){	
			
			$tieneEvidencias   = '';
			$rutaE 		= '../proveedores/'.$row['id'];
			if (is_dir($rutaE)) { 
			  if ($dhE = opendir($rutaE)) { 
				$num = 1;
				while (($fileE = readdir($dhE)) !== false) { 
					if ($fileE != "." && $fileE != ".." && $fileE != ".quarantine" && $fileE != ".tmb" && $fileE != "comentarios"){ 
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
			
			$tieneEvidencias != '' ? $color = 'success' : $color = 'info';
			
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center droptable">

						';

			$btnVer = '<a class="dropdown-item text-warning" href="proveedor.php?id='.$row['id'].'&type=view"><i class="fas fa-eye mr-2"></i>Ver</a>';

			$btnEditar = '<a class="dropdown-item text-info" href="proveedor.php?id='.$row['id'].'&type=edit"><i class="fas fa-pen mr-2"></i>Editar</a>';
			
			$btnAsociar='<a class="dropdown-item text-info" href="proveedorrel.php?id='.$row['id'].'"><i class="fas fa-link mr-2"></i>Asociar</a>';

			$btnEliminar='<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>';
			
			$btnAdjunto='<a class="dropdown-item text-'.$color.' boton-evidencias" data-id="'.$row['id'].'"><i class="fas fa-camera mr-2"></i>Evidencias</a>';

			$acciones.=$btnEditar;
			$acciones.=$btnEliminar;
			$acciones.=$btnAdjunto;

			$acciones.='
								</div>
							</div>
						</td>';

			$resultado[] = array(
				'id' 		=>	$row['id'],	
				'acciones' 			  => 	$acciones,	
				'cliente' 			  =>	$row['cliente'],  
				'proyecto' 			  =>	$row['proyecto'],  
				'nombre' 			  =>	$row['nombre'],  
				'encargado' 		  =>	$row['encargado'],
				'telefono' 			  =>	$row['telefono'],
				'correo' 			  =>	$row['correo'],
				'cuentacontrato' 	  =>	$row['cuentacontrato'],
				'fechainiciocontrato' =>	$row['fechainiciocontrato'],
				'fechafincontrato' 	  =>	$row['fechafincontrato'],
				'serviciocontratado'  =>	$row['serviciocontratado'],
				'incluyepiezas' 	  =>	$row['incluyepiezas'],
				'horarioatencioncont' =>	$row['horarioatencioncont'],
				'utilizarasym' 		  =>	$row['utilizarasym'],
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
	
	function  guardarProveedor()
	{
		global $mysqli;
		$nombre 			 = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : ''); 
		$idclientes 		 = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '0'); 
		$idproyectos 		 = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '0');
		$encargado 			 = (!empty($_REQUEST['encargado']) ? $_REQUEST['encargado'] : '');   
		$telefono 			 = (!empty($_REQUEST['telefono']) ? $_REQUEST['telefono'] : '');   
		$correo 			 = (!empty($_REQUEST['correo']) ? $_REQUEST['correo'] : '');   
		$cuentacontrato 	 = (!empty($_REQUEST['cuentacontrato']) ? $_REQUEST['cuentacontrato'] : '');   
		$fechainiciocontrato = (!empty($_REQUEST['fechainiciocontrato']) ? $_REQUEST['fechainiciocontrato'] : 'null');   
		$fechafincontrato	 = (!empty($_REQUEST['fechafincontrato']) ? $_REQUEST['fechafincontrato'] : 'null');   
		$serviciocontratado  = (!empty($_REQUEST['serviciocontratado']) ? $_REQUEST['serviciocontratado'] : '');   
		$incluyepiezas		 = (!empty($_REQUEST['incluyepiezas']) ? $_REQUEST['incluyepiezas'] : '');   
		$horarioatencioncont = (!empty($_REQUEST['horarioatencioncont']) ? $_REQUEST['horarioatencioncont'] : '');   
		$utilizarasym		 = (!empty($_REQUEST['utilizarasym']) ? $_REQUEST['utilizarasym'] : '');   
		$fechainiciocontrato = limpiarFecha($fechainiciocontrato);
		$fechafincontrato 	 = limpiarFecha($fechafincontrato);
		
		$query  = " INSERT INTO proveedores 
					(nombre,encargado,telefono,correo,cuentacontrato,fechainiciocontrato,fechafincontrato,
					serviciocontratado,incluyepiezas,horarioatencioncont,utilizarasym) VALUES 
					('".$nombre."','".$encargado."','".$telefono."','".$correo."','".$cuentacontrato."',".$fechainiciocontrato.",".$fechafincontrato.",
					'".$serviciocontratado."','".$incluyepiezas."','".$horarioatencioncont."','".$utilizarasym."') ";
	    //echo $query;
		$result = $mysqli->query($query); 
		if($result == true){
		    $idproveedor = $mysqli->insert_id;
			
			$queryP  = " INSERT INTO proveedorespuente 
						(id, idmpresas, idclientes, idproyectos, idproveedores, fechacreacion) VALUES 
						(null, 1, ".$idclientes.", ".$idproyectos.", ".$idproveedor.", now() ) ";
			$resultP = $mysqli->query($queryP);
		
		    bitacora($_SESSION['usuario'], "Proveedores", "El Proveedor #".$idproveedor." ha sido creado", $idproveedor, $query);			
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function actualizarProveedor() 
	{
		global $mysqli;
		$id 			 	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);   
		$nombre 			 = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$idclientes 		 = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '0'); 
		$idproyectos 		 = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '0');
		$encargado 			 = (!empty($_REQUEST['encargado']) ? $_REQUEST['encargado'] : '');   
		$telefono 			 = (!empty($_REQUEST['telefono']) ? $_REQUEST['telefono'] : '');   
		$correo 			 = (!empty($_REQUEST['correo']) ? $_REQUEST['correo'] : '');   
		$cuentacontrato 	 = (!empty($_REQUEST['cuentacontrato']) ? $_REQUEST['cuentacontrato'] : '');   
		$fechainiciocontrato = (!empty($_REQUEST['fechainiciocontrato']) ? $_REQUEST['fechainiciocontrato'] : 'null');   
		$fechafincontrato	 = (!empty($_REQUEST['fechafincontrato']) ? $_REQUEST['fechafincontrato'] : 'null');   
		$serviciocontratado  = (!empty($_REQUEST['serviciocontratado']) ? $_REQUEST['serviciocontratado'] : '');   
		$incluyepiezas		 = (!empty($_REQUEST['incluyepiezas']) ? $_REQUEST['incluyepiezas'] : '');   
		$horarioatencioncont = (!empty($_REQUEST['horarioatencioncont']) ? $_REQUEST['horarioatencioncont'] : '');   
		$utilizarasym		 = (!empty($_REQUEST['utilizarasym']) ? $_REQUEST['utilizarasym'] : '');   
						 
		$campos = array( 
						'Proveedor' 	  					=> $nombre,
						'Nombre del encargado o supervisor' => $encargado,
						'Número de teléfono'  				=> $telefono,
						'Correo'   							=> $correo,
						'¿Cuenta con contrato?' 	 		=> $cuentacontrato,
						'Fecha de inicio de contrato' 		=> $fechainiciocontrato,
						'Fecha de finalización de contrato' => $fechafincontrato,
						'Servicio contratado' 				=> $serviciocontratado,
						'¿Incluye piezas?'  				=> $incluyepiezas,
						'Horario de atención contratada' 	=> $horarioatencioncont,
						'Utilizará SyM' 	 				=> $utilizarasym 
					);
		
		$valoresold = getRegistroSQL("  SELECT a.nombre AS 'Proveedor', 
										a.encargado AS 'Nombre del encargado o supervisor', a.telefono AS 'Número de teléfono', 
										a.correo AS 'Correo', a.cuentacontrato AS '¿Cuenta con contrato?',
										a.fechainiciocontrato AS 'Fecha de inicio de contrato',
										a.fechafincontrato AS 'Fecha de finalización de contrato',
										a.serviciocontratado AS 'Servicio contratado', 
										a.incluyepiezas AS '¿Incluye piezas?', 
										a.horarioatencioncont AS 'Horario de atención contratada',
										a.utilizarasym AS 'Utilizará SyM'
										FROM proveedores a 
										WHERE 1 = 1 
										AND a.id = '".$id."' ");
										
		$fechainiciocontrato = limpiarFecha($fechainiciocontrato);
		$fechafincontrato 	 = limpiarFecha($fechafincontrato);	
		
		$query  = " UPDATE proveedores SET 
					nombre = '".$nombre."',
					encargado = '".$encargado."', 
					telefono = '".$telefono."', 
					correo = '".$correo."', 
					cuentacontrato = '".$cuentacontrato."', 
					fechainiciocontrato = ".$fechainiciocontrato.", 
					fechafincontrato = ".$fechafincontrato.", 
					serviciocontratado = '".$serviciocontratado."', 
					incluyepiezas = '".$incluyepiezas."', 
					horarioatencioncont = '".$horarioatencioncont."', 
					utilizarasym = '".$utilizarasym."' 
					WHERE id = '".$id."' ";	
					//echo $query;
		$result = $mysqli->query($query);
		
		if($result == true){
			$queryP  = " UPDATE proveedorespuente SET 
						idclientes = '".$idclientes."',
						idproyectos = '".$idproyectos."'
						WHERE idproveedores = '".$id."' ";	
			$resultP = $mysqli->query($queryP);
		
		    //bitacora($_SESSION['usuario'], "Proveedores", "El Proveedor #".$id." ha sido actualizado", $id, $query);			
		    actualizarRegistro('Proveedores','Proveedores',$id,$valoresold,$campos,$query);
			
			echo 1;
		}else{
			echo 0;
		}
	}
 
	function limpiarFecha($fecha){
		if($fecha == 'null'){
			$fecha = str_replace("'","",$fecha); 
		}else{
			$fecha = "'".$fecha."'"; 
		}
		return $fecha;
	}
	
	function getProveedor(){
		global $mysqli;
		
		$idproveedor = (!empty($_REQUEST['idproveedor']) ? $_REQUEST['idproveedor'] : '');
		
		$query 	= "	SELECT a.*, b.idclientes, b.idproyectos FROM proveedores a
					LEFT JOIN proveedorespuente b ON b.idproveedores = a.id
					WHERE a.id = '".$idproveedor."' ";
		$result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			$resultado = array( 
				'nombre' 			  =>	$row['nombre'],  
				'idclientes' 		  =>	$row['idclientes'],  
				'idproyectos' 		  =>	$row['idproyectos'],  
				'encargado' 		  =>	$row['encargado'],
				'telefono' 			  =>	$row['telefono'],
				'correo' 			  =>	$row['correo'],
				'cuentacontrato' 	  =>	$row['cuentacontrato'],
				'fechainiciocontrato' =>	$row['fechainiciocontrato'],
				'fechafincontrato' 	  =>	$row['fechafincontrato'],
				'serviciocontratado'  =>	$row['serviciocontratado'],
				'incluyepiezas' 	  =>	$row['incluyepiezas'],
				'horarioatencioncont' =>	$row['horarioatencioncont'],
				'utilizarasym' 		  =>	$row['utilizarasym']
			);
		}
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo 0;
		}
	}
	
	function eliminarProveedor() 
	{
		global $mysqli;
		
		$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		
		$query = "DELETE FROM proveedores WHERE id = '".$id."' ";
		$result = $mysqli->query($query);
				
		if($result == true){
		    bitacora($_SESSION['usuario'], "Proveedores", "El Proveedor #".$id." ha sido eliminado", $id , $query);
			echo 1;
		}else{
			echo 0;
		}
	}	

	function hayRelacion(){
		    global $mysqli;
		    
		    $id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
		    
		    $existe_usuario = 0;
		    $quser = "SELECT * FROM usuarios WHERE find_in_set($id,idproveedor)";

	        $rQUser = $mysqli->query($quser);
			if($rQUser->num_rows > 0){ 
	            $existe_usuario = 1; 
	        }

			if(
				($existe_usuario == 1) 
			){
				echo 1;
			}else{
				echo 0;
			}
		}
		
	function cargarproveedoresclientes(){
		
		global $mysqli; 
		$idproveedor  = (!empty($_REQUEST['idproveedor']) ? $_REQUEST['idproveedor'] : 0);
		
		$query = " 	SELECT a.id, b.nombre, a.idclientes, a.idproyectos, a.idproveedores, c.nombre AS cliente, d.nombre AS proyecto
					FROM proveedorespuente a 
					LEFT JOIN proveedores b ON b.id = a.idproveedores
					LEFT JOIN clientes c ON c.id = a.idclientes
					LEFT JOIN proyectos d ON d.id = a.idproyectos
					WHERE b.id = ".$idproveedor." ORDER BY c.nombre, d.nombre, b.nombre ASC ";
					//echo $query;
		$result = $mysqli->query($query);
		$resultado = array();
		while($row = $result->fetch_assoc()){
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center droptable"> 
									<a class="dropdown-item text-danger boton-eliminar" data-idcliente="'.$row['idcliente'].'" data-idproyecto="'.$row['idproyecto'].'" data-idestado="'.$row['idproveedor'].'" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
									</div>
								</div>
							</td>';
							
			$resultado[] = array(
				'id' 		=>	$row['id'],
				'acciones' 	=>	$acciones, 
				'nombre'	=>	$row['nombre'],  
				'cliente'	=>	$row['cliente'], 
				'proyecto'	=>	$row['proyecto'] 
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
	
	function asociarproveedoresclientes(){
		global $mysqli;		 
		
		$id 		  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idcliente 	  = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : '');
		$idproyecto   = (!empty($_REQUEST['idproyecto']) ? $_REQUEST['idproyecto'] : ''); 
		$usuario	  = $_SESSION['user_id'];
		
		$sql = " SELECT id FROM proveedorespuente 
				 WHERE 
				 idclientes = ".$idcliente." 
				 AND idproyectos = ".$idproyecto." 
				 AND idproveedores = ".$id."";
		//echo $sql;
		$rsql = $mysqli->query($sql);
		
		//Evitar duplicado
		if($rsql->num_rows > 0){
			echo 2;
		}else{ 
			$query 	= "	INSERT INTO	proveedorespuente (idclientes, idproyectos, idproveedores, fechacreacion, idusuarios) 
						VALUES (".$idcliente.", ".$idproyecto.", ".$id.", NOW(), '".$usuario."')";
			$result = $mysqli->query($query);
			$idcate = $mysqli->insert_id;
			$result == true ? $respuesta = 1 : $respuesta = 0;
			echo $respuesta;
		} 
	}
	
	function eliminarproveedoresclientes(){
		
		global $mysqli;
		
		$id   		  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		
		$query 	= "DELETE FROM proveedorespuente WHERE id = ".$id.""; 
		$result = $mysqli->query($query);
		
		if($result==true){
			
			bitacora($_SESSION['usuario'], "Proveedores", "El proveedor #".$id." ha sido eliminado", $id , $query);
			
			echo 1;
		}else{
			echo 0;
		} 
	}
	
	function hayRelacionPc(){
		global $mysqli;
		
		$id   		  	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idcliente    	 = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : '');
		$idproyecto   	 = (!empty($_REQUEST['idproyecto']) ? $_REQUEST['idproyecto'] : '');
		$idproveedor     = (!empty($_REQUEST['idproveedor']) ? $_REQUEST['idproveedor'] : ''); 
		
		$existe_correctivo	= 0;
		$existe_laboratorio = 0;
	    $existe_postventa 	= 0;
		
		$qcorr = "SELECT id FROM incidentes WHERE
					idclientes = ".$idcliente."
					AND idproyectos = ".$idproyecto."
					AND idproveedores = ".$idproveedor." LIMIT 1";

        $rQcorr = $mysqli->query($qcorr);
		if($rQcorr->num_rows > 0){ 
            $existe_correctivo = 1; 
        }
		
		$qlab = "SELECT id FROM laboratorio WHERE
					idclientes = ".$idcliente."
					AND idproyectos = ".$idproyecto."
					AND idproveedores = ".$idproveedor." LIMIT 1";

        $rQlab = $mysqli->query($qcorr);
		if($rQlab->num_rows > 0){ 
            $existe_laboratorio = 1; 
        }
		
		$qPv = "SELECT id FROM postventas WHERE
					idclientes = ".$idcliente."
					AND idproyectos = ".$idproyecto."
					AND idproveedores = ".$idproveedor." LIMIT 1";

        $rqPv = $mysqli->query($qPv);
		if($rqPv->num_rows > 0){ 
            $existe_postventa = 1; 
        }
		
		($existe_correctivo  == 1) ||
			($existe_preventivo  == 1) ||
			($existe_laboratorio == 1) ||
			($existe_postventa 	 == 1) ?
			$respuesta = 1 : $respuesta = 0;
			echo $respuesta;
	}


?>