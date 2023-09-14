<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){ 
		case "getcategorias": 
			  getcategorias();
			  break;
		case "createcategorias": 
			  createcategorias();
			  break; 
		case "editarcategoria": 
			  editarcategoria();
			  break;
		case "deletecategorias": 
			  deletecategorias();
			  break;
	    case "existeincidentescate": 
			  existeincidentescate();
			  break; 
		case "cargarcategorias": 
			  cargarcategorias();
			  break; 
		case "cargarcategoriaspuente": 
			  cargarcategoriaspuente();
			  break; 
		case "hayRelacion": 
			  hayRelacion();
			  break;
		case "eliminarcategoriaspuente": 
			  eliminarcategoriaspuente();
			  break;
		case "asociarcategoriaspuente": 
			  asociarcategoriaspuente();
			  break;
		case "hayRelacionPc": 
			  hayRelacionPc();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	} 	
	
	function cargarcategorias(){
		
		global $mysqli; 
		
		$query = " 	SELECT a.id, a.nombre,
				    LEFT(GROUP_CONCAT( DISTINCT c.nombre SEPARATOR  ', ' ),45) AS clientes, 
				    LEFT(GROUP_CONCAT( DISTINCT d.nombre SEPARATOR  ', ' ),45) AS proyectos,
				    GROUP_CONCAT( DISTINCT c.nombre SEPARATOR  ', ' ) AS clientestt, 
				    GROUP_CONCAT( DISTINCT d.nombre SEPARATOR  ', ' ) AS proyectostt
		            FROM categorias a
		            LEFT JOIN categoriaspuente b ON b.idcategorias = a.id
				    LEFT JOIN clientes c  ON FIND_IN_SET(c.id, b.idclientes)
                    LEFT JOIN proyectos d  ON FIND_IN_SET(d.id, b.idproyectos)
					WHERE 1 ";
		
		$query .= " GROUP BY a.id ORDER BY a.nombre ASC  ";			
		$result = $mysqli->query($query);
		$resultado = array();
		while($row = $result->fetch_assoc()){
			
			$id = $row["id"];  
			
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center droptable"> 
										<a class="dropdown-item text-info" href="categoria.php?id='.$row['id'].'&type=edit"><i class="fas fa-pen mr-2"></i>Editar</a>
										<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
									</div>
								</div>
							</td>';
							
			$resultado[] = array(
				'id' 		=>	$row['id'],
				'acciones' 	=>	$acciones, 
				'nombre'	=>	$row['nombre'],
				'clientes'    => "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['clientestt']."'>".$row['clientes']."</span>",
				'proyectos' 	=> "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['proyectostt']."'>".$row['proyectos']."</span>"
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
	
	function cargarcategoriaspuente(){
		
		global $mysqli; 
		$idcategoria  = (!empty($_REQUEST['idcategoria']) ? $_REQUEST['idcategoria'] : 0);
		
		$query = " 	SELECT b.id, a.nombre, b.idclientes, b.idproyectos, b.idcategorias, c.nombre AS cliente, d.nombre AS proyecto
					FROM categorias a 
					INNER JOIN categoriaspuente b ON b.idcategorias = a.id 
					INNER JOIN clientes c ON b.idclientes = c.id 
					INNER JOIN proyectos d ON b.idproyectos = d.id 
					WHERE a.id = ".$idcategoria." 
					ORDER BY c.nombre, d.nombre, a.nombre ASC";
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
									<a class="dropdown-item text-danger boton-eliminar" data-idclientes="'.$row['idclientes'].'" data-idproyectos="'.$row['idproyectos'].'" data-idcategorias="'.$row['idcategorias'].'" data-id="'.$row['id'].'" ><i class="fas fa-trash mr-2"></i>Eliminar</a>
									</div>
								</div>
							</td>';
							
			$resultado[] = array(
				'id' 		=>	$row['id'],
				'acciones' 	=>	$acciones, 
				'nombre'	=>	$row['nombre'],   
				'clientes'    => "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['clientestt']."'>".$row['clientes']."</span>",
				'proyectos' 	=> "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['proyectostt']."'>".$row['proyectos']."</span>"
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
	
	function getcategorias(){
		global $mysqli;
		
		$idcategorias = $_REQUEST['idcategorias'];
		
		$query 		= "	 SELECT id, nombre 
						 FROM categorias 
						 WHERE id = ".$idcategorias."";
		$result 	= $mysqli->query($query);
		
		if($row = $result->fetch_assoc()){ 
			
			$resultado = array( 
				'id' 	 =>	$row['id'], 	
				'nombre' =>	$row['nombre'] 
			);
		}
		
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	} 
	
	function deletecategorias(){
		global $mysqli;
		
		$idcategorias 	= $_REQUEST['idcategorias'];
		$query 	= "DELETE FROM categorias WHERE id = '$idcategorias'";
		$result = $mysqli->query($query);		
		if($result==true){
		    
		    bitacora($_SESSION['usuario'], "Categorias", "La categoria #".$idcategorias." ha sido eliminada", $idcategorias , $query);
		    
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function eliminarcategoriaspuente(){
		
		global $mysqli;
		
		$id   		  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		
		$query 	= "DELETE FROM categoriaspuente WHERE id = ".$id.""; 
		$result = $mysqli->query($query);
		
		if($result==true){
			
			bitacora($_SESSION['usuario'], "Categorias", "La categoria #".$id." ha sido eliminada", $id , $query);
			
			echo 1;
		}else{
			echo 0;
		} 
	}
	
	function editarcategoria(){
		global $mysqli;		 
		$id 	= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 		
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : ''); 		
		
		$existe = " SELECT id FROM categorias WHERE nombre = '".$nombre."' AND id != ".$id."";
		$verificar = $mysqli->query($existe); 
		
		if($verificar->num_rows > 0){
			echo 2;
		}else{
			$query 	= "	UPDATE categorias SET nombre = '".$nombre."'
						WHERE id = ".$id."";
			$result = $mysqli->query($query);	
			
			if($result == true){		    
				bitacora($_SESSION['usuario'], "Categorias", "La categoria #".$id." ha sido editada", $id , $query);		    
				echo 1;
			}else{
				echo 0;
			}
		} 
	} 
	
	function createcategorias(){
		
		global $mysqli;	
		
		$nombre 	= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');   
		$usuario 	= $_SESSION['usuario'];
		
		//Evitar duplicado
		$bCat = " SELECT nombre FROM categorias WHERE nombre = '".$nombre."'";
		$rNom = $mysqli->query($bCat); 
		
		if($rNom->num_rows > 0){
			echo 2;
		}else{
			
			$query 	= "	INSERT INTO	categorias (nombre) VALUES ('".$nombre."')";
			$result = $mysqli->query($query);		
			$result == true ? $respuesta = 1 : $respuesta = 0; 
			echo $respuesta;
			
		} 
	}
	
	function asociarcategoriaspuente(){
		global $mysqli;		 
		
		$id 		  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idusuarios	  = $_SESSION['user_id'];
		
		$sql = " SELECT id FROM categoriaspuente 
				 WHERE 
				 idclientes = ".$idclientes." 
				 AND idproyectos = ".$idproyectos."
				 AND idcategorias = ".$id."";
		//echo $sql;
		$rsql = $mysqli->query($sql);
		
		//Evitar duplicado
		if($rsql->num_rows > 0){
			echo 2;
		}else{ 
			$query 	= "	INSERT INTO	categoriaspuente (idempresas,idclientes, idproyectos, idcategorias, idusuarios) 
						VALUES (1, ".$idclientes.", ".$idproyectos.", ".$id.", ".$idusuarios.")";
						//echo $query;
					
			$result = $mysqli->query($query);
			$idcate = $mysqli->insert_id;
			$result == true ? $respuesta = 1 : $respuesta = 0;
			echo $respuesta;
		} 
	}
	  
	function hayRelacionPc(){
		global $mysqli;
		 
		$idclientes   = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idcategorias = (!empty($_REQUEST['idcategorias']) ? $_REQUEST['idcategorias'] : '');
		$existe = array(  
            'subcategorias' => 0,
            'correctivos' 	=> 0,
            'preventivos' 	=> 0,
            'postventas' 	=> 0,
        );
		
		$qInc = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'incidentes' 
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idcategorias = ".$idcategorias." 
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
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['preventivos'] = 1; 
        } 
		
		$qSub = "  	SELECT id FROM subcategoriaspuente
						WHERE 
				    AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idcategorias = ".$idcategorias."
				  LIMIT 1"; 
                   
        $rSub = $mysqli->query($qSub);
		if($rSub->num_rows > 0){ 
            $existe['subcategorias'] = 1;
        }
		
		$qPrev = " SELECT id FROM postventas 
					WHERE  
				  AND idclientes = ".$idclientes." 
				  AND idproyectos = ".$idproyectos." 
				  AND idcategorias = ".$idcategorias." 
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['postventas'] = 1; 
        }
		
		echo json_encode($existe);
	}

	
	function hayRelacion(){
		global $mysqli;
		$id = $_REQUEST['id'];
		
		$existe = array(  
            'subcategorias' => 0,
            'correctivos' 	=> 0,
            'preventivos' 	=> 0
        );
		
		$qInc = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'incidentes' 
				  AND idcategorias = ".$id." 
				  LIMIT 1";
       //echo $qInc;
		$rInc = $mysqli->query($qInc);
		if($rInc->num_rows > 0){ 
            $existe['correctivos'] = 1; 
        }
		
		$qPrev = " SELECT id FROM incidentes 
					WHERE 
				  tipo = 'preventivos' 
				  AND idcategorias = ".$id." 
				  LIMIT 1";
       
		$rPrev = $mysqli->query($qPrev);
		if($rPrev->num_rows > 0){ 
            $existe['preventivos'] = 1; 
        }
		
		$qSub = " SELECT id FROM categorias_subcategorias 
					WHERE 
				  id_categoria = ".$id."
				  LIMIT 1";
       
		$rSub = $mysqli->query($qSub);
		if($rSub->num_rows > 0){ 
            $existe['subcategorias'] = 1; 
        }
		
		echo json_encode($existe);
	}
	
	function existeincidentescate(){
		global $mysqli;
		$id = $_REQUEST['id'];
		
		$existe = array(
            'incidentes'    => 0,
            'subcategorias' => 0
        ); 
        
		$qInc = " SELECT * FROM incidentes 
		          WHERE idcategoria = $id ";
       
		$r = $mysqli->query($qInc);
		if($r->num_rows > 0){ 
            $existe['incidentes'] = 1; 
        }
        
        $qSub = "  SELECT * FROM categorias b 
                   INNER JOIN subcategorias c 
                   ON c.idcategoria = b.id and b.id = $id"; 
                   
        $r1 = $mysqli->query($qSub);
		if($r1->num_rows > 0){ 
            $existe['subcategorias'] = 1;
        }
         
        echo json_encode($existe);
        
	}

?>