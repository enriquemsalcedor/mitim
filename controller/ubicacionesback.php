<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "sitios": 
			  sitios();
			  break;		
		case "createsitio":
			  createsitio();
			  break;
		case "updatesitio":
			  updatesitio();
			  break;
	    case "getsitio":
			  getsitio();
			  break;
		case "deletesitio":
			  deletesitio();
			  break;
		case "hayRelacion":
			  hayRelacion();
			  break;
		case "cargarambientesclientes":
			  cargarambientesclientes();
			  break;
		case "asociarambientesclientes":
			  asociarambientesclientes();
			  break;
		case "eliminarambientesclientes":
			  eliminarambientesclientes();
			  break;
		case "hayRelacionPc":
			  hayRelacionPc();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	

	function sitios() 
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
		
		$query  = " SELECT a.id, LEFT(a.nombre,45) as unidad,
					LEFT(a.responsables,45) as responsables, 
					GROUP_CONCAT( DISTINCT b.nombre SEPARATOR ', ' ) AS nombreusuarios, 
				    LEFT(GROUP_CONCAT( DISTINCT d.nombre SEPARATOR  ', ' ),45) AS clientes, 
				    LEFT(GROUP_CONCAT( DISTINCT e.nombre SEPARATOR  ', ' ),45) AS proyectos,
				    GROUP_CONCAT( DISTINCT d.nombre SEPARATOR  ', ' ) AS clientestt, 
				    GROUP_CONCAT( DISTINCT e.nombre SEPARATOR  ', ' ) AS proyectostt
		            FROM ambientes a 
		            LEFT JOIN usuarios b ON FIND_IN_SET(b.correo, a.responsables)
		            INNER JOIN ambientespuente c ON c.idambientes = a.id
					INNER JOIN clientes d ON FIND_IN_SET (d.id, c.idclientes)
					INNER JOIN proyectos e ON FIND_IN_SET (e.id, c.idproyectos)
		            WHERE 1 = 1 "; 
					
		if($nivel != 1 || $nivel != 2){
			if($idclientes != ''){
				$arr = strpos($idclientes, ',');
				if ($arr !== false) {
					$query  .= " AND c.idclientes IN (".$idclientes.") ";
				}else{
					$query  .= " AND find_in_set(".$idclientes.",c.idclientes) ";
				}  
			}
			if($idproyectos != ''){
				$arr = strpos($idproyectos, ',');
				if ($arr !== false) {
					$query  .= " AND c.idproyectos IN (".$idproyectos.") ";
				}else{
					$query  .= " AND find_in_set(".$idproyectos.",c.idproyectos) ";
				}  
			}	
		}
		
	    /*----------------------------------------------------------------------
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
				if ($column == 'unidad') {
					$column = 'a.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'responsables') {
					$column = 'a.responsables';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'idempresas') {
					$column = 'c.descripcion';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'idclientes') {
					$column = 'd.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'idproyectos') {
					$column = 'e.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				
				$hayFiltros++;
			}
		}

		if ($hayFiltros > 0)
			$where = " AND ".implode(" AND " , $where2)." ";

		$searchGeneral= (!empty($_POST['search']['value']) ? $_POST['search']['value'] : '');		
		
		if($searchGeneral != ''){
			$where.= " AND (
			a.id like '%".$searchGeneral."%' OR 
			a.nombre like '%".$searchGeneral."%' OR 
			a.responsables like '%".$searchGeneral."%' OR
			c.descripcion like '%".$searchGeneral."%' OR
			d.nombre like '%".$searchGeneral."%' OR
			e.nombre like '%".$searchGeneral."%'
			)";
    	}
    	
    	$query  .= " $where ";*/
		debugL($query,"estados");
	    $query .= " GROUP BY a.id ";
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		//$query  .= " ORDER BY a.id desc LIMIT $start, $length ";
		$query  .= " ORDER BY a.id desc";
		
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		$response = array();

		/*$query .= " GROUP BY id ";
		$result = $mysqli->query($query);

		$recordsTotal = $result->num_rows;
		$query  .= " ORDER BY id desc";
		//debug($query);
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;*/
		
		while($row = $result->fetch_assoc()){	
			//$lat = $row['lat'];
			//$lon = $row['lon'];
			
			/* if($lat==0){
				$lat = "";
			}
			
			if($lon==0){
				$lon = "";
			} */
			
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center droptable">

						';

		    $btnVer = '<a class="dropdown-item text-warning" href="ambiente.php?id='.$row['id'].'&type=view"><i class="fas fa-eye mr-2"></i>Ver</a>';

			$btnEditar = '<a class="dropdown-item text-info" href="ambiente.php?id='.$row['id'].'&type=edit"><i class="fas fa-pen mr-2"></i>Editar</a>';
		    $btnAsociar = '<a class="dropdown-item text-info" href="ambienterel.php?id='.$row['id'].'"><i class="fas fa-link mr-2"></i>Asociar</a>';

			$btnEliminar='<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>';

			if($nivel == 1 || $nivel == 2){
				$acciones.=$btnEditar;
				$acciones.=$btnEliminar;
			}
			if($nivel > 2){
				$acciones.=$btnVer;
			}

            /*
			if($nivel==1 || $nivel==2){//ADMIN  Y SOPORTE -> TODOS 
			}
			if($nivel==3){//ING/TEC ->  ¿TODOS?
			}
			if($nivel==4){//CLIENTE SYM ->  ¿TODOS?
			}
			if($nivel==4){//CLIENTE SYM ->  ¿TODOS?
			}
			if($nivel==5){//DIRECTORES / GERENTES ->  ¿TODOS?
			}
			if($nivel==6){//QA ->  ¿TODOS?
			}
			if($nivel==7){//CLIENTE SYM -> VER Y CREAR
			}
            */

			$acciones.='
								</div>
							</div>
						</td>';
			$resultado[] = array(
				'id' 				=>	$row['id'],	
				'acciones' 			=> 	$acciones, 	
				'unidad' 			=>	$row['unidad'],  
				'responsables' 		=>	$row['responsables'],
				'clientes'    => "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['clientestt']."'>".$row['clientes']."</span>",
				'proyectos' 	=> "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['proyectostt']."'>".$row['proyectos']."</span>",

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
	
	function  createsitio()
	{
		global $mysqli;
		
		$unidad  	      = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : '');  
		$responsables 	  = (!empty($_REQUEST['responsables']) ? $_REQUEST['responsables'] : '');  
	//	$ip               = (!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
		

		$query = " INSERT INTO ambientes (nombre,responsables) 
		           VALUES ('$unidad','$responsables') ";
	//	echo $query;
	    $result = $mysqli->query($query);
	    	
		if($result==true){
		    
		    $idsitio = $mysqli->insert_id;
		    
		    bitacora($_SESSION['usuario'], "Ubicaciones", "La ubicación #".$idsitio." ha sido creada", $idsitio, $query);
			
			echo 1; 
		    
		}else{
		
			echo 0; 
		    
		}
	}
		
	function updatesitio() 
	{
		global $mysqli;
		
		$id  	 		  =  (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$unidad 	      =  (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : '');
		$responsables 	  =  (!empty($_REQUEST['responsables']) ? $_REQUEST['responsables'] : '');
	//	$ip               =  (!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');									   
  		

		$query = " UPDATE ambientes 
					SET 
					nombre = '$unidad', 
					responsables = '$responsables'
				   WHERE id = '$id' ";	
		//echo $query;		   
		//debug('updatesi:'.$query);

		$result = $mysqli->query($query);
		
		if($result==true){
		    
		    $idsitio = $mysqli->insert_id;
		    
		    bitacora($_SESSION['usuario'], "Ubicaciones", "La ubicación #".$idsitio." ha sido actualizada", $idsitio, $query);
			
			echo 1; 
		    
		}else{
		
			echo 0; 
		    
		} 
	}

	function getsitio(){
		global $mysqli;
		
		$idsitios = (!empty($_REQUEST['idsitios']) ? $_REQUEST['idsitios'] : '');
		$query 		= "	SELECT *
						FROM ambientes
						WHERE id = '$idsitios'";
		$result 	= $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			
			$responsables = $row['responsables'];
		    //$responsables = str_replace(", ", ",", $responsables);

			
			$resultado = array( 
				'unidad' 			=>	$row['nombre'], 
				'responsables' 		=>	$responsables
			);
		}
			if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}	
		
	
	function deletesitio() 
	{
		global $mysqli;
		
		$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		
		$query = "DELETE FROM ambientes WHERE id = '$id'";
		
		$result = $mysqli->query($query); 
		
		if($result==true){
		    
		    bitacora($_SESSION['usuario'], "Ubicaciones", "La ubicación #".$id." ha sido eliminada", $id , $query);
			
			echo 1;
		    
		}else{
			echo 0;
		}
		
		
	}	


	function hayRelacion(){
	    global $mysqli;
	    
	    $id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
	    
	    $existe_correctivo	= 0;
	    $existe_preventivo	= 0;

	    $existe_activo = 0;

	    $existe_postventa = 0;


	    $qcorr = "SELECT 
						incidentes.id
					FROM incidentes
					WHERE incidentes.idambientes = $id  AND incidentes.tipo = 'incidentes' LIMIT 1;";

        $rQcorr = $mysqli->query($qcorr);
		if($rQcorr->num_rows > 0){ 
            $existe_correctivo = 1; 
        }
        


	    $qprev = "SELECT 
						incidentes.id
					FROM incidentes
					WHERE incidentes.idambientes = $id AND incidentes.tipo = 'preventivos' LIMIT 1;";

        $rQprev = $mysqli->query($qprev);
		if($rQprev->num_rows > 0){ 
            $existe_preventivo = 1; 
        }


	    $qact = "SELECT 
					activos.id
				FROM activos 
				WHERE activos.idambientes = '$id' LIMIT 1;";

        $rQAct = $mysqli->query($qact);
		if($rQAct->num_rows > 0){ 
            $existe_activo = 1; 
        }



	    $qpost= "SELECT postventas.id FROM activos
					INNER JOIN postventas ON activos.id = postventas.idactivos
				WHERE activos.idambientes = $id LIMIT 1;";

        $rQpost = $mysqli->query($qpost);
		if($rQpost->num_rows > 0){ 
            $existe_postventa = 1; 
        }

		if(
			($existe_correctivo  == 1) ||
			($existe_preventivo  == 1) ||
			($existe_activo == 1) ||
			($existe_postventa 	 == 1)
		){
			echo 1;
		}else{
			echo 0;
		}
	}

	function cargarambientesclientes(){
		
		global $mysqli; 
		$idambiente  = (!empty($_REQUEST['idambiente']) ? $_REQUEST['idambiente'] : 0);
		
		$query = " 	SELECT a.id, b.nombre, a.idclientes, a.idproyectos, a.idambientes, c.nombre AS cliente, d.nombre AS proyecto
					FROM ambientespuente a 
					LEFT JOIN ambientes b ON b.id = a.idambientes
					LEFT JOIN clientes c ON c.id = a.idclientes
					LEFT JOIN proyectos d ON d.id = a.idproyectos
					WHERE b.id = ".$idambiente." ORDER BY c.nombre, d.nombre, b.nombre ASC ";
					//echo $query;
		$result = $mysqli->query($query);
		$resultado = array();
		while($row = $result->fetch_assoc()){
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center"> 
									<a class="dropdown-item text-danger boton-eliminar" data-idcliente="'.$row['idcliente'].'" data-idproyecto="'.$row['idproyecto'].'" data-idambiente="'.$row['idambiente'].'" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
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
	
	function asociarambientesclientes(){
		global $mysqli;		 
		
		$id 		  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idcliente 	  = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyecto   = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : ''); 
		$usuario	  = $_SESSION['user_id'];
		
		$sql = " SELECT id FROM ambientespuente
				 WHERE 
				 idclientes = ".$idcliente." 
				 AND idproyectos = ".$idproyecto." 
				 AND idambientes = ".$id."";
		//echo $sql;
		$rsql = $mysqli->query($sql);
		
		//Evitar duplicado
		if($rsql->num_rows > 0){
			echo 2;
		}else{ 
			$query 	= "	INSERT INTO	ambientespuente (idclientes, idproyectos, idambientes, fechacreacion, idusuarios) 
						VALUES (".$idcliente.", ".$idproyecto.", ".$id.", NOW(), '".$usuario."')";
						
			$result = $mysqli->query($query);
			$idcate = $mysqli->insert_id;
			$result == true ? $respuesta = 1 : $respuesta = 0;
			echo $respuesta;
		} 
	}
	
	function eliminarambientesclientes(){
		
		global $mysqli;
		
		$id   		  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		
		$query 	= "DELETE FROM ambientespuente WHERE id = ".$id.""; 
		$result = $mysqli->query($query);
		
		if($result==true){
			
			bitacora($_SESSION['usuario'], "Ubicaciones", "La ubicación #".$id." ha sido eliminado", $id , $query);
			
			echo 1;
		}else{
			echo 0;
		} 
	}
	
	function hayRelacionPc(){
		global $mysqli;
		 
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idambientes = (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : '');
		$existe = array(  
            'subambientes' => 0,
            'correctivos' 	=> 0,
            'preventivos' 	=> 0,
            'postventas' 	=> 0,
        );
		
		$qInc = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'incidentes' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idambientes = ".$idambientes." 
				  LIMIT 1";
       //echo $qInc;
		$rInc = $mysqli->query($qInc);
		if($rInc->num_rows > 0){ 
            $existe['correctivos'] = 1; 
        }
		
		$qPrev = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'preventivos' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idambientes = ".$idambientes." 
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['preventivos'] = 1; 
        } 
		
		$qSub = "  	SELECT id FROM subambientespuente
						WHERE 
				    AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idambientes = ".$idambientes."
				  LIMIT 1"; 
                   
        $rSub = $mysqli->query($qSub);
		if($rSub->num_rows > 0){ 
            $existe['subambientes'] = 1;
        }
		
		$qPrev = " SELECT id FROM postventas 
					WHERE  
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idambientes = ".$idambientes." 
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['postventas'] = 1; 
        }
		
		echo json_encode($existe);
	}
	
		function existeincidentesamb(){
		global $mysqli;
		$id = $_REQUEST['id'];
		
		$existe = array(
            'incidentes'    => 0,
            'subambientes' => 0
        ); 
        
		$qInc = " SELECT * FROM incidentes 
		          WHERE idambientes = $id ";
       
		$r = $mysqli->query($qInc);
		if($r->num_rows > 0){ 
            $existe['incidentes'] = 1; 
        }
        
        $qSub = "  SELECT * FROM ambientes b 
                   INNER JOIN subambientes c 
                   ON c.idambientes = b.id and b.id = $id"; 
                   
        $r1 = $mysqli->query($qSub);
		if($r1->num_rows > 0){ 
            $existe['subambientes'] = 1;
        }
         
        echo json_encode($existe);
        
	}
?>