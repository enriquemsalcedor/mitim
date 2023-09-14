<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "activos": 
			  activos();
			  break;		
		case "createactivo":
			  createactivo();
			  break;
		case "updateactivo":
			  updateactivo();
			  break;
	    case "getactivo":
			  getactivo();
			  break;
		case "deleteactivo":
			  deleteactivo();
			  break;
		case "trasladaractivo":
			  trasladaractivo();
			  break;
		case "traslados":
			  traslados();
			  break;
		case "importaractivos":
			  importaractivos();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	

	function activos() 
	{
		global $mysqli;		
		$where = array();
		$where2 = "";		
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : ''); 
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    $orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		$idusuario 			 = (!empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
		$nivel				 = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		
// 		echo $nivel;
// 		echo $idusuario;
		$query  = " SELECT a.id, a.codequipo, LEFT(a.equipo,45) as equipo, a.activo, a.marca, a.modelo, a.casamedica,
					a.codigound, a.modalidad, a.area, a.estado, a.fase, LEFT(a.comentarios,45) as comentarios, a.fechatopemant,
					a.fechainst, b.nombre as uni, c.descripcion as idempresas, d.nombre as idclientes, e.nombre as idproyectos 
					FROM activos a 
					LEFT JOIN ambientes b ON a.codigound = b.codigo 
					LEFT JOIN empresas c ON a.idempresas = c.id 
					LEFT JOIN clientes d ON a.idclientes = d.id 
					LEFT JOIN proyectos e ON a.idproyectos = e.id 
					LEFT JOIN usuarios u ON u.idproyectos = e.id
					WHERE 1 = 1 ";
		if($nivel == 3 || $idusuario == 329){
		    $query .= " AND u.id = $idusuario ";
		}
// 		die($query);
		$hayFiltros = 0;
		for($i=0 ; $i<count($_REQUEST['columns']);$i++){
			$column = $_REQUEST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			if ($_REQUEST['columns'][$i]['search']['value']!="") {
				
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);
				
				if ($column == 'codequipo') {
					$column = 'a.codequipo';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'equipo') {
					$column = 'a.equipo';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'activo') {
					$column = 'a.activo';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'marca') {
					$column = 'a.marca';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'modelo') {
					$column = 'a.modelo';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'casamedica') {
					$column = 'a.casamedica';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'codigound') {
					$column = 'a.codigound';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'unidad') {
					$column = 'b.nombre';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'modalidad') {
					$column = 'a.modalidad';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'area') {
					$column = 'a.area';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'estado') {
					$column = 'a.estado';
					$where[] = " $column like '".$campo."%' ";
				}
				
				/*if ($column == 'edificio') {
					$column = 'a.edificio';
					$where[] = " $column like '".$campo."%' ";
				}*/
				
				if ($column == 'fase') {
					$column = 'a.fase';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'comentarios') {
					$column = 'a.comentarios';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'fechatopemant') {
					$column = 'a.fechatopemant';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				/*if ($column == 'cotizacion') {
					$column = 'a.cotizacion';
					$where[] = " $column like '".$campo."%' ";
				}
				
				if ($column == 'mesescotizar') {
					$column = 'a.mesescotizar';
					$where[] = " $column like '".$campo."%' ";
				}
				
				if ($column == 'cotizacionmenos') {
					$column = 'a.cotizacionmenos';
					$where[] = " $column like '".$campo."%' ";
				}*/
				
				if ($column == 'fechainst') {
					$column = 'a.fechainst';
					$where[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'idempresas') {
					$column = 'c.descripcion';
					$where[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'idclientes') {
					$column = 'd.nombre';
					$where[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'idproyectos') {
					$column = 'e.nombre';
					$where[] = " $column like '%".$campo."%' ";
				}
				
				$hayFiltros++;
			}
		}
		
		if ($hayFiltros > 0)
			$where = " AND ".implode(" AND " , $where)." ";// id like '%searchValue%' or name like '%searchValue%'
		else
			$where = "";
		
		$query  .= " $where $where2";
		$query .= " GROUP BY a.id ";
		//debug('activos:'.$query);
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		$query  .= " ORDER BY a.id desc LIMIT $start, $length ";
		
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		
		while($row = $result->fetch_assoc()){			
			$resultado[] = array(			
				'id' 					=>	$row['id'],	
				'acciones' 				=>	"<div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'> 
												<span class='icon-col red fa fa-trash boton-eliminar' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Activo' data-placement='right'></span>
                                                <span class='icon-col blue fa fa-arrow-circle-right boton-trasladar' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Trasladar Activo' data-placement='right'></span>
											</div>", 
				'codequipo'				=>	$row['codequipo'], 
				'equipo' 				=>	$row['equipo'], 
				'activo' 				=>	$row['activo'], 
				'marca' 				=>	$row['marca'], 
				'modelo' 				=>	$row['modelo'], 
				'casamedica' 			=>	$row['casamedica'],
				'codigound' 			=>	$row['codigound'],
				'unidad' 				=>	$row['uni'],
				'modalidad' 			=>	$row['modalidad'], 
				'area' 					=>	$row['area'], 
				'estado' 				=>	ucwords(strtolower($row['estado'])),
				'fase' 					=>	$row['fase'], 				
				'comentarios' 			=>	$row['comentarios'], 				
				'fechatopemant' 		=>	$row['fechatopemant'],			
				'fechainst' 			=>	$row['fechainst'],
				'idempresas' 			=>	$row['idempresas'],
				'idclientes' 			=>	$row['idclientes'],
				'idproyectos' 			=>	$row['idproyectos'] 				
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
	
	function  createactivo()
	{
		global $mysqli;
		
		$codigound  	 = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : '');
		$modalidad  	 = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '');
		$codequipo 	     = (!empty($_REQUEST['codequipo']) ? $_REQUEST['codequipo'] : '');
		$equipo 	     = (!empty($_REQUEST['equipo']) ? $_REQUEST['equipo'] : '');
		$marca 		     = (!empty($_REQUEST['marca']) ? $_REQUEST['marca'] : '');
		$modelo 	     = (!empty($_REQUEST['modelo']) ? $_REQUEST['modelo'] : '');
		$activo 	     = (!empty($_REQUEST['activo']) ? $_REQUEST['activo'] : '');
		$casamedica 	 = (!empty($_REQUEST['casamedica']) ? $_REQUEST['casamedica'] : '');
		$area 	    	 = (!empty($_REQUEST['area']) ? $_REQUEST['area'] : '');
		$estado 	     = (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : '');
		//$edificio 	     = $_REQUEST['edificio'];
		$fase 	    	 = (!empty($_REQUEST['fase']) ? $_REQUEST['fase'] : '');
		$comentarios 	 = (!empty($_REQUEST['comentarios']) ? $_REQUEST['comentarios'] : '');
		$fechatopemant 	 = (!empty($_REQUEST['fechatopemant']) ? $_REQUEST['fechatopemant'] : '');
		/*$cotizacion      = $_REQUEST['cotizacion'];
		$mesescotizar 	 = $_REQUEST['mesescotizar'];
		$cotizacionmenos = $_REQUEST['cotizacionmenos'];*/
		$fechainst 	     = (!empty($_REQUEST['fechainst']) ? $_REQUEST['fechainst'] : '');
		$idempresas 	 = (!empty($_REQUEST['idempresas']) ? $_REQUEST['idempresas'] : 1);
		$idclientes 	 = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0);
		$idproyectos 	 = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : 0);
		
		$campos = array(
			'serie' 	  	=> $codequipo,
			'activo' 		=> $activo,
			'equipo' 		=> $equipo,
			'marca' 		=> $marca,
			'modelo' 		=> $modelo,
			'casa Médica'	=> $casamedica,
			'sitio'			=> getId('nombre','ambientes',$codigound,'codigo'),
			'modalidad'		=> $modalidad,
			'area'			=> $area,
			'fase'			=> $fase,
			'fecha Tope. Mant.'	=> $fechatopemant,
			'fecha Inst.'		=> $fechainst,
			'comentarios'		=> $comentarios,
			'empresas'		=> getValor('descripcion','empresas',$idempresas),
			'clientes'		=> getValor('nombre','clientes',$idclientes),
			'proyectos'		=> getValor('nombre','proyectos',$idproyectos)
		);
		
		$conn = "SELECT codequipo FROM activos where codequipo = '$codequipo' AND idempresas = '$idempresas' AND idclientes = '$idclientes' ";
		$resultn = $mysqli->query($conn);
		
		$totn = $resultn->num_rows;
		
		if($totn > 0){
			echo 2;			
		}else{
			$query = " INSERT INTO activos (codigound,unidad,modalidad,codequipo,equipo,marca,modelo,activo,casamedica,area,estado,fase,
						 comentarios,fechatopemant,fechainst,idempresas,idclientes,idproyectos) 
					   VALUES ('$codigound',NULL,'$modalidad', '$codequipo', '$equipo', '$marca', '$modelo', '$activo', '$casamedica',
						'$area', '$estado','$fase', '$comentarios', '$fechatopemant','$fechainst','$idempresas','$idclientes','$idproyectos') ";
					
			$result = $mysqli->query($query);
				
			if($result==true){
				$idactivo = $mysqli->insert_id;
				nuevoRegistro('Activos','Activos',$idactivo,$campos,$query);
				echo 1; 
			}else{
				echo 0; 
			}
		}
	}
		
	function updateactivo() 
	{
		global $mysqli;
		
		$id  	 		 = (!empty($_REQUEST['id']) ? $_REQUEST['id']  : 0); 
		$codequipo 	     = (!empty($_REQUEST['codequipo']) ? $_REQUEST['codequipo'] : '');
		$equipo 	     = (!empty($_REQUEST['equipo']) ? $_REQUEST['equipo'] : '');
		$marca 		     = (!empty($_REQUEST['marca']) ? $_REQUEST['marca'] : '');
		$modelo 	     = (!empty($_REQUEST['modelo']) ? $_REQUEST['modelo'] : '');
		$activo 	     = (!empty($_REQUEST['activo']) ? $_REQUEST['activo'] : '');
		$casamedica 	 = (!empty($_REQUEST['casamedica']) ? $_REQUEST['casamedica'] : '');
		$codigound  	 = (!empty($_REQUEST['unidad']) ?  $_REQUEST['unidad'] : '');
		$modalidad  	 = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '');
		$area 	    	 = (!empty($_REQUEST['area']) ? $_REQUEST['area'] : '');
		//$edificio 	     = $_REQUEST['edificio'];
		$fase 	    	 = (!empty($_REQUEST['fase']) ? $_REQUEST['fase'] : '');
		$comentarios 	 = (!empty($_REQUEST['comentarios']) ? $_REQUEST['comentarios'] : '');
		$fechatopemant 	 = (!empty($_REQUEST['fechatopemant']) ? $_REQUEST['fechatopemant'] : '');
		/*$cotizacion      = $_REQUEST['cotizacion'];
		$mesescotizar 	 = $_REQUEST['mesescotizar'];
		$cotizacionmenos = $_REQUEST['cotizacionmenos'];*/
		$fechainst 	     = (!empty($_REQUEST['fechainst']) ? $_REQUEST['fechainst'] : '');
		$estado 	     = (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : '');
		$idempresas 	 = (!empty($_REQUEST['idempresas']) ? $_REQUEST['idempresas'] : 1);
		$idclientes 	 = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0);
		$idproyectos 	 = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : 0);
		
		
		$campos = array(
			'serie' 	  	=> $codequipo,
			'activo' 		=> $activo,
			'equipo' 		=> $equipo,
			'marca' 		=> $marca,
			'modelo' 		=> $modelo,
			'casamedica'	=> $casamedica,
			'sitio'			=> getId('nombre','ambientes',$codigound,'codigo'),
			'modalidad'		=> $modalidad,
			'area'			=> $area,
			'fase'			=> $fase,
			'fechatopemant'	=> $fechatopemant,
			'fechainst'		=> $fechainst,
			'comentarios'		=> $comentarios,
			'empresas'		=> getValor('descripcion','empresas',$idempresas),
			'clientes'		=> getValor('nombre','clientes',$idclientes),
			'proyectos'		=> getValor('nombre','proyectos',$idproyectos),
			'estado'		=> $estado
		);
		
		$valoresold = getRegistroSQL(" SELECT a.codequipo as serie, a.activo, a.equipo, a.marca, a.modelo, a.casamedica,
										b.nombre as sitio, a.modalidad, a.area, a.fase, a.fechatopemant, a.fechainst, a.comentarios,
										c.descripcion AS empresas, d.nombre AS clientes, e.nombre AS proyectos, a.estado
										FROM activos a 
										LEFT JOIN ambientes b ON b.codigo = a.codigound 
										LEFT JOIN empresas c ON c.id = a.idempresas 
										LEFT JOIN clientes d ON d.id = a.idclientes 
										INNER JOIN proyectos e ON e.id = a.idproyectos
									    WHERE a.id = '".$id."' ");
		
		$nomant 	= getValor('codequipo','activos',$id);
		
		$comn 		= "SELECT codequipo FROM activos where codequipo = '$codequipo' AND codequipo != '$nomant' AND idempresas = $idempresas AND idclientes = $idclientes ";
		$resultn 	= $mysqli->query($comn);
		$totn 		= $resultn->num_rows;
		
		if($totn > 0){
			echo 2;			
		}else{	
			//VERIFICO EL ESTADO
			$query  = "SELECT estado FROM activos WHERE id = $id ";
			$result = $mysqli->query($query);
			if($result->num_rows > 0){
				$result   = $result->fetch_assoc();			
				$estadobd = $result['estado'];
			}
			
			$query = "  UPDATE activos SET codigound = '$codigound', modalidad = '$modalidad', codequipo = '$codequipo',
						equipo = '$equipo', marca = '$marca', modelo = '$modelo', activo = '$activo', casamedica = '$casamedica',
						area = '$area', estado = '$estado', fase = '$fase', comentarios = '$comentarios', fechatopemant = '$fechatopemant',
						fechainst = '$fechainst',idempresas = '$idempresas',idclientes = '$idclientes',idproyectos = '$idproyectos'
						WHERE id = '$id' ";	
			
			$result = $mysqli->query($query);
			if($result == true){
				if($estadobd != $estado){
					$buscar = array(chr(13).chr(10), "\r\n", "\n", "\r", "  ");
					$reemplazar = array(" ", " ", " ", " ", "");
					$queryA = str_ireplace($buscar, $reemplazar, $query);

					$queryB = 'INSERT INTO activosbitacora VALUES(null, "'.$_SESSION['usuario'].'", now(), "'.$id.'", "'.$estado.'", "'.$queryA.'") ';
					
					$resultB = $mysqli->query($queryB);
				}		
				actualizarRegistro('Activos', 'Activos', $id, $valoresold, $campos, $query);
				echo 1;
			}else{
				echo 0;
			}
		} 
	}

	function getactivo(){
		global $mysqli;
		
		$idactivos = (!empty($_REQUEST['idactivos']) ? $_REQUEST['idactivos'] : 0);
		$query 		= "	SELECT *
						FROM activos
						WHERE id = '$idactivos'";
		$result 	= $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			
			$resultado = array(
				'codigound' 			=>	$row['codigound'], 
				'modalidad'	 			=>	$row['modalidad'],
				'codequipo' 			=>	$row['codequipo'],
				'equipo'	 		    =>	$row['equipo'],
				'marca'	 				=>	$row['marca'],
				'modelo'	 			=>	$row['modelo'],
				'activo'	 			=>	$row['activo'],
				'casamedica'	 	    =>	$row['casamedica'],
				'areact'	 		        =>	$row['area'],	
				'estado'	 		    =>	$row['estado'],	
				//'edificio'	 		    =>	$row['edificio'],
				'fase'		 		    =>	$row['fase'],	
				'comentarios'	 		=>	$row['comentarios'],	
				'fechatopemant'	 		=>	$row['fechatopemant'],	
				/*'cotizacion'	 		=>	$row['cotizacion'],	
				'mesescotizar'	 		=>	$row['mesescotizar'],	
				'cotizacionmenos'	 	=>	$row['cotizacionmenos'],*/
				'fechainst'	 		    =>	$row['fechainst'],	
				'idempresas'	 		=>	$row['idempresas'],	
				'idclientes'	 		=>	$row['idclientes'],	
				'idproyectos'	 		=>	$row['idproyectos']	
			);
		}
			if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}	
		
	
	function deleteactivo() 
	{
		global $mysqli;
		
		$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
		$nombre = getValor('equipo','activos',$id);
		
		$query = "DELETE FROM activos WHERE id = '$id'";
		
		$result = $mysqli->query($query); 
		
		if($result==true){
		    
		    eliminarRegistro('Activos','Activos',$nombre,$id,$query);
			
			echo 1;
		    
		}else{
			echo 0;
		}  
	}
	
	function  trasladaractivo()
	{
		global $mysqli;
		
		$idactivo    	 = (!empty($_REQUEST['activo']) ? $_REQUEST['activo'] : 0);
		$unidadanterior	 = (!empty($_REQUEST['unidadanterior']) ? $_REQUEST['unidadanterior'] : '');
		$unidadnueva     = (!empty($_REQUEST['unidadnueva']) ? $_REQUEST['unidadnueva'] : '');
		$usuario 	     = (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : '');
		$fecha           = date('Y-m-d'); 
		
		$actualizar = " UPDATE activos 
		                SET codigound = '$unidadnueva'
		                WHERE id = '$idactivo' ";
		                
		if($result = $mysqli->query($actualizar)){
		    
		    $query = " INSERT INTO activostraslados (activo,unidadanterior,unidadnueva,usuario,fechatraslado) 
		           VALUES ('$idactivo','$unidadanterior','$unidadnueva','$usuario','$fecha') ";
    		
    		
    	    $result = $mysqli->query($query);
    	    	
    		if($result==true){
    		    
    		    $idactivo = $mysqli->insert_id;
    		    
    		    bitacora($_SESSION['usuario'], "Activos", "El activo #".$idactivo." fue trasladado a la unidad # '.$unidadnueva.'", $idactivo, $query);
    			
    			echo 1; 
    		    
    		}else{
    		
    			echo 0; 
    		    
    		}    
		}
		
	}
	
	function traslados(){
		
		global $mysqli;	 
		
		$idactivo = (!empty($_REQUEST['idactivo']) ? $_REQUEST['idactivo']: 0);
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : ''); 
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    $orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		
		$query  = " SELECT a.id, a.activo, a.fechatraslado, b.equipo, c.nombre as nombreusuario, d.unidad as unidadold, e.unidad as unidadnew
					FROM activostraslados a 
					LEFT JOIN activos b 
					ON a.id = b.id 
					LEFT JOIN usuarios c 
					ON a.usuario = c.usuario 
					LEFT JOIN unidades d 
					ON a.unidadanterior = d.codigo 
					LEFT JOIN unidades e 
					ON a.unidadnueva = e.codigo 
					WHERE a.activo = '$idactivo'
					ORDER BY id DESC ";
	    //debug($query);
		$result = $mysqli->query($query); 
		$resultado = array();
		while($row = $result->fetch_assoc()){			
			$resultado[] = array(			
				'id' 					=>	$row['id'], 
				'activo'				=>	$row['activo'], 
				'unidadanterior'		=>	$row['unidadold'], 
				'unidadnueva' 			=>	$row['unidadnew'], 
				'usuario' 				=>	$row['nombreusuario'], 
				'fecha' 				=>	$row['fechatraslado'], 				
			);
		} 
		$response = array( 
			"data" => $resultado
		  );
		echo json_encode($response);
		
	}
	
	function checkActivosRepetida($serie,$equipo,$marca='',$modelo='',$empresa='',$cliente='',$proyecto=''){		
		global $mysqli; 
		
		//$idempresas  	= getId('id', 'empresas', $empresa, 'descripcion');
		$idempresas  	= 1;
		$idclientes  	= getId('id', 'clientes', $cliente, 'nombre');
		$idproyectos  	= getId('id', 'proyectos', $proyecto, 'nombre');
				
		$q = " SELECT id FROM activos WHERE codequipo = '$serie' AND equipo = '$equipo' AND marca = '$marca' AND modelo = '$modelo '
			   AND idempresas = '$idempresas' AND idclientes = '$idclientes' AND idproyectos = '$idproyectos' LIMIT 1 ";
		$r = $mysqli->query($q);
		
		$num = $r->num_rows;
		
		return $num;
	}
	
	function importaractivos(){
		global $mysqli;
		require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';
		
		if(isset($_FILES)) {
			$nombrefile	 	= $_FILES['archivo']['name'];
			$ArrArchivo = explode(".", $nombrefile);
			$extension 	= strtolower(end($ArrArchivo));
			$randName 	= md5(rand() * time());
			$path 		= '../activos/';

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
			$importadasAct = 0;
			$importadasExitosas = 0;
			$importadasError = 0;
			$causasError = '<ul>';	
			
			//Se comienza en la fila 5 a procesar el contenido, la fila 1 debe ser el titulo			
			for ($row = 5; $row < $highestRow+1; $row++){
				// Si ninguna celda esta en blanco
				if (trim($sheet->getCell('A' . $row)->getValue()) != '' && trim($sheet->getCell('B' . $row)->getValue()) != '' ){
					
					$repetida = checkActivosRepetida($sheet->getCell('A' . $row)->getValue(),$sheet->getCell('B' . $row)->getValue(),1,$sheet->getCell('B1')->getValue(),$sheet->getCell('D1')->getValue());
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
					$repetida = 0;
					if($repetida == 0){
						$rowData[] = $sheet->rangeToArray('A' . $row . ':' . 'H' . $row, NULL, TRUE, FALSE);
						$importadasExitosas++;				    
					} else {
						$causasError .= '<li>Error en la fila '.$row.', el activo ya existe</li>';
						$importadasError++;
					}
				} else {	
					if (trim($sheet->getCell('C' . $row)->getValue()) == ''  ){
						//FILA VACIA
					}else{ 
						$importadasError++;
						if(trim($sheet->getCell('A' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', el campo <b>ACTIVO</b> está vacío</li>';
						}
						if(trim($sheet->getCell('B' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', el campo <b>N° DE SERIE</b> está vacío</li>';
						} 
						if(trim($sheet->getCell('D' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', el campo <b>MARCA</b> está vacío</li>';
						}
						if(trim($sheet->getCell('E' . $row)->getValue()) == ''){									  
							$causasError .= '<li>Error en la fila '.$row.', el campo <b>MODELO</b> está vacío</li>';
						}
						if(trim($sheet->getCell('F' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', el campo <b>UBICACIÓN / SITIO</b> está vacío</li>';
						}
						if(trim($sheet->getCell('G' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', el campo <b>ÁREA</b> está vacío</li>';
						}
						if(trim($sheet->getCell('H' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', el campo <b>RESPONSABLE / ASIGNADO</b> está vacío</li>';
						} 
					//}
					}
				}		
				$causasError .= '</ul>';
				
				$acciones = '';
				$listaImportadas = '<ul>';
			}
			//debug(count($rowData));
			//BD			
			for ($j = 0; $j < count($rowData); $j++){
				$ArrItem 	= $rowData[$j][0];
				/* 
				$fecha		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[1], "yyyy-mm-dd");
				$hora 		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[2], "h:mm:ss");
				*/	
				//$formatfechatopemant = PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[10], "yyyy-mm-dd");
				//$formatfechainst     = PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[11], "yyyy-mm-dd");
				
				//$formatfechatopemant = $ArrItem[10];
				//$formatfechainst     = $ArrItem[11];
				 
				//$empresas  	   = 1;
				$clientes      = $sheet->getCell('B1')->getValue();
				$proyectos     = $sheet->getCell('D1')->getValue();
				$equipo        = trim(str_replace(' ', '', $ArrItem[0]));
				$codequipo	   = trim(str_replace(' ', '', $ArrItem[1]));
				$activo        = trim(str_replace(' ', '', $ArrItem[2]));
				$marca         = trim(str_replace(' ', '', $ArrItem[3]));
				$modelo        = trim(str_replace(' ', '', $ArrItem[4]));
				$ambiente      = trim(str_replace(' ', '', $ArrItem[5]));
				$area          = trim(str_replace(' ', '', $ArrItem[6]));
				$casamedica    = trim(str_replace(' ', '', $ArrItem[7]));					
				
				/* $modalidad     = trim(str_replace(' ', '', $ArrItem[7]));
				$fase          = trim(str_replace(' ', '', $ArrItem[9]));
				$fechatopemant = $formatfechatopemant;
				$fechainst     = $formatfechainst; 
				$estado        = trim(str_replace(' ', '', $ArrItem[12]));
				$comentarios   = trim(str_replace(' ', '', $ArrItem[13])); */
				//IDS 
				$codigound 	   = getId('codigo', 'unidades', $ambiente, 'unidad');
				//$idempresas    = getId('id', 'empresas', $empresas, 'descripcion');
				$idclientes    = getId('id', 'clientes', $clientes, 'nombre');
				$idproyectos   = getId('id', 'proyectos', $proyectos, 'nombre');
				
				$queryAc = " SELECT id FROM activos WHERE codequipo = '".$codequipo."' ";
				$resultAc = $mysqli->query($queryAc);
				if($resultAc->num_rows > 0){
					$row = $resultAc->fetch_assoc();				
					$id = $row['id'];
					
					$query = "UPDATE activos SET ";
					//if($idempresas != '')	{	$query .= ", idempresas = '$idempresas' ";	}
					if($idclientes != '')	{	$query .= ", idclientes = '$idclientes' ";	}
					if($idproyectos != '')	{	$query .= ", idproyectos = '$idproyectos' ";	}
					if($codigound != '')	{	$query .= " codigound = '$codigound' ";	}
					if($equipo != '')		{	$query .= ", equipo = '$equipo' ";	}
					if($marca != '')		{	$query .= ", marca = '$marca' ";	}
					if($modelo != '')		{	$query .= ", modelo = '$modelo' ";	}
					if($activo != '')		{	$query .= ", activo = '$activo' ";	}
					if($casamedica != '')	{	$query .= ", casamedica = '$casamedica' ";	}
					if($area != '')			{	$query .= ", area = '$area' ";	}
					
					//if($modalidad != '')	{	$query .= ", modalidad = '$modalidad' ";	}
					//if($estado != '')		{	$query .= ", estado = '$estado' ";	}
					//if($fase != '')			{	$query .= ", fase = '$fase' ";	}
					//if($comentarios != '')	{	$query .= ", comentarios = '$comentarios' ";	}
					//if($fechatopemant != ''){	$query .= ", fechatopemant = '$fechatopemant' ";	}
					//if($fechainst != '')	{	$query .= ", fechainst = '$fechainst' ";	}
					
					$query .= " WHERE codequipo = '$codequipo' ";
					$query = str_replace('SET ,','SET ',$query);					
					$result = $mysqli->query($query);
					$importadasAct++;					
				}else{
					$query = "INSERT INTO activos (codigound,unidad,codequipo,equipo,marca,modelo,activo,casamedica,area,estado, 
							  idempresas,idclientes,idproyectos) 
							  VALUES ('$codigound', NULL, '$codequipo', '$equipo', '$marca', '$modelo', '$activo', '$casamedica',
							  '$area', 'ACTIVO', '1','$idclientes','$idproyectos') ";
					//debug($query);
					$result = $mysqli->query($query);
					$id = $mysqli->insert_id;
					$importadasExito++;
				}				
				
				if($codigound == ''){
					$causasError .= '<li>Error: El Activo con el número de serie: <b>'.$codequipo.'</b> no ha sido relacionado con el sitio, ya que el nombre del sitio no existe</li>';
					$importadasError++;
				}
				$listaImportadas .= '<li> Cliente: '.$clientes.',Proyecto: '.$proyectos.', Activo: '.$equipo.', N° de Serie: '.$codequipo.', N° de Activo: '.$activo.', Marca: '.$marca.', Modelo: '.$modelo.',
										  Ubicación / Sitio: '.$codigound.',  Area: '.$area.', Responsable / Asignado: '.$casamedica.'
									. </li>';
			}			
			$listaImportadas .= '</ul>';
		
			if($result == true){
				$resultado  = $importadasExito.' filas creadas exitosamente. <br/>';
				$resultado .= $importadasAct.' filas actualizadas exitosamente. <br/>';
				$resultado .= $importadasError. ' filas con error. <br/>';
				$resultado .= $causasError;
				echo $resultado;
				
				// bitacora
				$acciones .= 'Fue importado el archivo '.$nombrefile.' para la creación de los activos.<br/><br/>';
				$acciones .= '<b>Resultado:</b><br/>';
				$acciones .= $resultado;
				$acciones .= '<b>Activos importadas:</b><br/>';
				$acciones .= $listaImportadas;
				
				bitacora($_SESSION['usuario'],'Activos',$acciones,0,'');
			}else{
				$resultado = $importadasError. ' filas con error. <br/>';
				$resultado .= $causasError;
				echo $resultado;
			}
		}
	}
?>