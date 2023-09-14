<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){ 
		case "getsubcategorias": 
			  getsubcategorias();
			  break;
		case "createsubcategoria": 
			  createsubcategoria();
			  break; 
		case "editarsubcategoria": 
			  editarsubcategoria();
			  break;
		case "deletesubcategorias": 
			  deletesubcategorias();
			  break;
	    case "existeincidentesubc": 
			  existeincidentesubc();
			  break;
		case "cargarsubcategorias": 
			  cargarsubcategorias();
			  break; 
		case "hayRelacion": 
			  hayRelacion();
			  break;
		case "cargarsubcategoriaspuente": 
			  cargarsubcategoriaspuente();
			  break;
		case "asociarsubcategoriaspuente": 
			  asociarsubcategoriaspuente();
			  break;
		case "eliminarsubcategoriaspuente": 
			  eliminarsubcategoriaspuente();
			  break;
		case "hayRelacionPs": 
			  hayRelacionPs();
			  break; 
		case "categorias_subcategorias": 
			  categorias_subcategorias();
			  break;
		case "agregar_categoria_subcategoria": 
			  agregar_categoria_subcategoria();
			  break;
		case "eliminar_categoria_subcategoria": 
			  eliminar_categoria_subcategoria();
			  break;	  
		default:
			  echo "{failure:true}";
			  break;
	}	 
	
	function cargarsubcategorias(){
		
		global $mysqli; 
		
		$query = " 	SELECT a.id, a.nombre AS subcategoria, GROUP_CONCAT( DISTINCT c.nombre SEPARATOR ', ' ) AS categorias
					FROM subcategorias a 
					INNER JOIN categorias_subcategorias b ON a.id = b.id_subcategoria 
					INNER JOIN categorias c ON c.id = b.id_categoria
					WHERE 1 GROUP BY a.id "; 
		$result = $mysqli->query($query);
		$resultado = array();
		while($row = $result->fetch_assoc()){
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center droptable"> 
										<a class="dropdown-item text-info" href="subcategoria.php?id='.$row['id'].'&type=edit"><i class="fas fa-pen mr-2"></i>Editar</a>
										<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
									</div>
								</div>
							</td>';
							
			$resultado[] = array(
				'id' =>	$row['id'],
				'acciones' => $acciones, 
				'subcategoria' => $row['subcategoria'], 
				'categorias'	=> $row['categorias'], 
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
	
	function getsubcategorias(){
		
		global $mysqli; 
		
		$idsubcategoria = $_REQUEST['idsubcategoria'];
		
		$query 		= "	 SELECT a.id, a.nombre
						 FROM subcategorias a 
						 WHERE a.id = ".$idsubcategoria." ";
		$result 	= $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			
			$resultado = array(   
				'nombre' => $row['nombre'] 	
			);
		}
		
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}	
	
	
	function deletesubcategorias(){
		global $mysqli;
		
		$idsubcategorias = $_REQUEST['idsubcategorias'];
		$query = "DELETE FROM subcategorias WHERE id = '$idsubcategorias'";
		$result = $mysqli->query($query);		
		if($result==true){ 
			
			//Borrar relación categoría - subcategoría
			$delete_categorias_subcategorias = "DELETE FROM categorias_subcategorias WHERE idsubcategoria = ".$idsubcategorias;
			$result = $mysqli->query($delete_categorias_subcategorias);		
			
		    bitacora($_SESSION['usuario'], "Subcategorias", "La subcategoria #".$idsubcategorias." ha sido eliminada", $idsubcategorias , $query);
		    
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function editarsubcategoria(){
		global $mysqli;		 
		$id		= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 		
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : ''); 		
		$id_categoria = (!empty($_REQUEST['id_categoria']) ? $_REQUEST['id_categoria'] : ''); 
		
		$existe = " SELECT id FROM subcategorias WHERE nombre = '".$nombre."' AND id != ".$id."";
		$verificar = $mysqli->query($existe); 
		
		if($verificar->num_rows > 0){
			echo 2;
		}else{
			$query 	= "	UPDATE subcategorias SET nombre = '".$nombre."'
						WHERE id = ".$id."";
			$result = $mysqli->query($query);	
			
			if($result == true){		    
				bitacora($_SESSION['usuario'], "Subcategorias", "La subcategoría #".$idsubcategoria." ha sido editada", $idsubcategoria , $query);		    
				echo 1;
			}else{
				echo 0;
			}
		} 
	} 
	
	function createsubcategoria(){
		global $mysqli;
		 
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : ''); 
		$id_categoria = (!empty($_REQUEST['id_categoria']) ? $_REQUEST['id_categoria'] : ''); 
		
		$existe = " SELECT nombre FROM subcategorias WHERE nombre = '".$nombre."'";
		$verificar = $mysqli->query($existe); 
		
		if($verificar->num_rows > 0){
			echo 2;
		}else{
			$query 	= "	INSERT INTO	subcategorias (nombre)
						VALUES ('".$nombre."')";
			
			$result = $mysqli->query($query); 
			
			if($result==true){
				
				$id_subcategoria = $mysqli->insert_id;  	
				
				bitacora($_SESSION['usuario'], "Subcategorias", "La subcategoria #".$id_subcategoria." ha sido creada", $id_subcategoria, $query);
				
				echo $id_subcategoria;
			}else{
				echo 0;
			}
		} 
	} 
	
	function asociarsubcategoriaspuente(){
		
		global $mysqli;	
		
		$id 		  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idcategorias = (!empty($_REQUEST['idcategorias']) ? $_REQUEST['idcategorias'] : '');  
		$idusuarios	  = (!empty($_SESSION['user_id']) ? $_SESSION['user_id'] : '');
		
		$sql = " SELECT id FROM subcategoriaspuente 
				 WHERE 
				 idclientes = ".$idcliente." 
				 AND idproyectos = ".$idproyecto." 
				 AND idcategorias = ".$idcategoria."
				 AND idsubcategorias = ".$id.""; 
				// echo $sql;
		$rsql = $mysqli->query($sql);
		
		//Evitar duplicado
		if($rsql->num_rows > 0){
			echo 2;
		}else{
			$query 	= "	INSERT INTO	subcategoriaspuente (idclientes, idproyectos, idcategorias, idsubcategorias, idusuarios) VALUES (".$idclientes.", ".$idproyectos.", ".$idcategorias.", ".$id.", ".$idusuarios.")";
			//echo $query;
			$result = $mysqli->query($query);
			
			$result == true ? $response = 1 : $response = 0;
			echo $response;
		}
	}  
	
	function hayRelacion(){
		global $mysqli;
		$id = $_REQUEST['id'];
		
		$existe = array( 
            'categorias'     => 0, 
            'correctivos' 	=> 0,
            'preventivos' 	=> 0
        );
		
		$qInc = " SELECT * FROM incidentes 
		          WHERE tipo = 'incidentes' AND idsubcategorias = ".$id." ";
       //echo $qInc;
		$rInc = $mysqli->query($qInc);
		if($rInc->num_rows > 0){ 
            $existe['correctivos'] = 1; 
        }
		
		$qPrev = " SELECT * FROM incidentes 
		          WHERE tipo = 'preventivos' AND idsubcategorias = ".$id." ";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['preventivos'] = 1; 
        } 
		
		$qCat = " SELECT * FROM categorias_subcategorias 
		          WHERE id_subcategoria = ".$id." ";
       
		$rCat = $mysqli->query($qCat);
		if($rCat->num_rows > 0){ 
            $existe['categorias'] = 1; 
        } 
		
		echo json_encode($existe);
	}
	
	
    function existeincidentesubc(){
		global $mysqli;
		$id = $_REQUEST['id'];
		$count = 0; 
		
		$query = " SELECT * FROM incidentes a 
		           INNER JOIN subcategorias b 
		           ON a.idsubcategoria = b.id 
		           AND b.id = $id ";
		           
		$result = $mysqli->query($query);
		$count = $result->num_rows;
		echo $count;
	}
	
	function cargarsubcategoriaspuente(){
		
		global $mysqli; 
		$idsubcategoria  = (!empty($_REQUEST['idsubcategoria']) ? $_REQUEST['idsubcategoria'] : 0);
		
		$query = " 	SELECT b.id, a.nombre, b.idclientes, b.idproyectos, b.idcategorias, b.idsubcategorias,
					c.nombre AS categoria, d.nombre AS cliente, e.nombre AS proyecto 
					FROM subcategorias a 
					INNER JOIN subcategoriaspuente b ON b.idsubcategorias = a.id 
					INNER JOIN categorias c ON b.idcategorias = c.id 
					INNER JOIN clientes d ON b.idclientes = d.id 
					INNER JOIN proyectos e ON b.idproyectos = e.id 
					WHERE a.id = ".$idsubcategoria." 
					ORDER BY d.nombre, e.nombre, c.nombre ASC ";
					//echo $query;
		$result = $mysqli->query($query);
		$resultado = array();
		while($row = $result->fetch_assoc()){
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center"><a class="dropdown-item text-danger boton-eliminar" data-idclientes="'.$row['idclientes'].'" data-idproyectos="'.$row['idproyectos'].'" data-idcategorias="'.$row['idcategorias'].'" data-idsubcategorias="'.$row['idsubcategorias'].'" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
									</div>
								</div>
							</td>';
							
			$resultado[] = array(
				'id' 		=>	$row['id'],
				'acciones' 	=>	$acciones, 
				'nombre'	=>	$row['nombre'], 
				'tipo'		=>	ucfirst($row['tipo']), 
				'cliente'	=>	$row['cliente'], 
				'proyecto'	=>	$row['proyecto'], 
				'categoria'	=>	$row['categoria'], 
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
	
	function eliminarsubcategoriaspuente(){
		
		global $mysqli;
		
		$id = $_REQUEST['id'];
		
		$query 	= "DELETE FROM subcategoriaspuente WHERE id = ".$id."";
		$result = $mysqli->query($query);
		
		if($result==true){
		    
		    bitacora($_SESSION['usuario'], "Subcategorías", "La subcategoría #".$id." ha sido eliminada", $id , $query);
		    
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function hayRelacionPs(){
		global $mysqli;
		
		$id   		     = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idclientes      = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos     = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idcategorias    = (!empty($_REQUEST['idcategorias']) ? $_REQUEST['idcategorias'] : '');
		$idsubcategorias = (!empty($_REQUEST['idsubcategorias']) ? $_REQUEST['idsubcategorias'] : '');
		$existe = array(   
            'correctivos' 	=> 0,
            'preventivos' 	=> 0,
            'postventas' 	=> 0
        );
		
		$qInc = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'incidentes' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idcategorias = ".$idcategorias." 
				  AND idsubcategorias = ".$idsubcategorias." 
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
				  AND idcategorias = ".$idcategorias." 
				  AND idsubcategorias = ".$idsubcategorias." 
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['preventivos'] = 1; 
        }
		
		$qPost = " SELECT id FROM postventas 
					WHERE  
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idcategorias = ".$idcategorias." 
				  AND idsubcategorias = ".$idsubcategorias." 
				  LIMIT 1";
       
		$rPost = $mysqli->query($qPost);
		if($rPost->num_rows > 0){ 
            $existe['postventas'] = 1; 
        }  
		
		echo json_encode($existe);
	}
	
	function categorias_subcategorias(){
		
		global $mysqli; 
		$idsubcategorias  = (!empty($_REQUEST['idsubcategorias']) ? $_REQUEST['idsubcategorias'] : 0);
		
		$query = " 	SELECT a.id, b.nombre AS categoria
					FROM categorias_subcategorias a 
					INNER JOIN categorias b ON b.id = a.id_categoria 
					WHERE a.id_subcategoria = ".$idsubcategorias." ";
		$result = $mysqli->query($query);
		$resultado = array();
		while($row = $result->fetch_assoc()){
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center"><a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
									</div>
								</div>
							</td>';
							
			$resultado[] = array(
				'id' =>	$row['id'],
				'acciones' => $acciones, 
				'categoria'	=> $row['categoria'] 
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
	
	function agregar_categoria_subcategoria(){
		
		global $mysqli;	
		
		$id_categoria = (!empty($_REQUEST['id_categoria']) ? $_REQUEST['id_categoria'] : '');
		$id_subcategoria = (!empty($_REQUEST['id_subcategoria']) ? $_REQUEST['id_subcategoria'] : '');
		
		$sql = " SELECT id FROM categorias_subcategorias 
				 WHERE  1
				 AND id_categoria = ".$id_categoria."
				 AND id_subcategoria = ".$id_subcategoria.""; 
				 //echo $sql;
		$rsql = $mysqli->query($sql);
		
		//Evitar duplicado
		if($rsql->num_rows > 0){
			echo 2;
		}else{
			$query 	= "	INSERT INTO	categorias_subcategorias (id_categoria,id_subcategoria) VALUES (".$id_categoria.", ".$id_subcategoria.")";
			$result = $mysqli->query($query);
			
			$result == true ? $response = 1 : $response = 0;
			echo $response;
		}
	}
	
	function eliminar_categoria_subcategoria(){
		global $mysqli;
		
		$id = $_REQUEST['id'];
		
		$query 	         = "DELETE FROM categorias_subcategorias WHERE id = '$id'";
		$result          = $mysqli->query($query);		
		if($result==true){  		    
			echo 1;
		}else{
			echo 0;
		}
	}

?>