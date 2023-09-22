<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){
		case "getpropiedades": 
              getpropiedades();
			  break;
		case "getpropiedad": 
			  getpropiedad();
			  break;
		case "crearpropiedad": 
              crearpropiedad();
			  break;
		case "updatepropiedades": 
			  updatepropiedades();
			  break;
		case "deletepropiedades": 
			  deletepropiedades();
			  break;	    
		default:
			  echo "{failure:true}";
			  break;
	}	
	
	function getpropiedades(){
		global $mysqli;
		
		$id   = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : '');

        $query = " SELECT a.id, a.nombre, a.direccion, 
                    c.nombre AS provincia, d.nombre AS distrito, e.nombre AS corregimiento
				   FROM propiedades a
				   LEFT JOIN provincias c ON c.id = a.id_provincia
				   LEFT JOIN distritos d ON d.id = a.id_distrito
				   LEFT JOIN corregimientos e ON e.id = a.id_corregimiento
				   WHERE a.id_cliente =".$id;  
        
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		$query  .= " ORDER BY a.nombre ASC";
		
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		$response = array();
		//debugL($query,"clientes");
		
		while($row = $result->fetch_assoc()){
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center droptable"> 
										<a class="dropdown-item text-info" id="editar" href="#"><i class="fas fa-pen mr-2"></i>Editar</a>
                                        <a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
									</div>
								</div>
							</td>';
							
		    $resultado[]= array(
				'id' =>	$row['id'],
				'acciones' => $acciones,
				'nombre' => $row['nombre'],
				'provincia' => $row['provincia'],
				'distrito' => $row['distrito'],
				'corregimiento' => $row['corregimiento'],
			);
		}
		$response = array(
			"recordsTotal" => intval($recordsTotal),
			"recordsFiltered" => intval($recordsTotal),
			"data" => $resultado
		  );
		; 
		echo json_encode($response); 
	}
 
	function getclientes(){
		global $mysqli;
		
		$idclientes = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0);
		$query 		= "	SELECT *
						FROM clientes
						WHERE id = '$idclientes' ";
		$result 	= $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			
			$resultado = array(
				'nombre' =>	$row['nombre'],
				'apellidos' => $row['apellidos'],
				'direccion' => $row['direccion'],
				'telefono' => $row['telefono'],
				'correo' =>	$row['correo'],
				'movil' => $row['movil'],
				'id_provincia' => $row['id_provincia'],
				'id_distrito' => $row['id_distrito'],
				'id_corregimiento' => $row['id_corregimiento'],
				'id_referido' => $row['id_referido'],
				'id_subreferido' =>	$row['id_subreferido']/*, 
				'idempresas'	 		=>	$row['idempresas']*/	
			);
		}
		
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}	
	
	
	function deleteclientes(){
		global $mysqli;
		
		$id 	= (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0);
		$query 	= "DELETE FROM clientes WHERE id = '$id'";
		$result = $mysqli->query($query);	
		
		if($result == true){
		    
		    //Actualiza Clientes asociados al usuario
		    $qUser = " SELECT * FROM usuarios 
	                   WHERE idclientes LIKE '$id,%' OR idclientes LIKE '%,$id' OR idclientes LIKE '%,$id,%' ";
		    
		    $result = $mysqli->query($qUser);
		    
		    while($row = $result->fetch_assoc()){
	            
	            $idusuario   = $row['id']; 
			    $idclieusers = $row['idclientes']; 
    		    $idclieusers = str_replace($id.',','',$idclieusers);
    		    $idclieusers = str_replace(','.$id.',',',',$idclieusers);
    		    $idclieusers = str_replace(','.$id,'',$idclieusers);
    		    
    		    $qUpd   = "UPDATE usuarios SET idclientes = '$idclieusers' WHERE id = $idusuario ";
    		    $resUpd = $mysqli->query($qUpd); 
    		     
	        }
		    
		    bitacora($_SESSION['usuario'], "Clientes", "El cliente #".$id." ha sido eliminado", $id , $query);
		    
		    echo 1;
		}else{
		    echo 0;
		}
		
	}
	
	function updateclientes(){
		global $mysqli;
		
		$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0 );
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$apellidos = (!empty($_REQUEST['apellidos']) ? $_REQUEST['apellidos'] : '');
		$direccion = (!empty($_REQUEST['direccion']) ? $_REQUEST['direccion'] : '');
		$telefono = (!empty($_REQUEST['telefono']) ? $_REQUEST['telefono'] : '');
		$correo = (!empty($_REQUEST['correo']) ? $_REQUEST['correo'] : '');
		$movil = (!empty($_REQUEST['movil']) ? $_REQUEST['movil'] : '');  
		$id_provincia = (!empty($_REQUEST['id_provincia']) ? $_REQUEST['id_provincia'] : 0); 
		$id_distrito = (!empty($_REQUEST['id_distrito']) ? $_REQUEST['id_distrito'] : 0);  
		$id_corregimiento = (!empty($_REQUEST['id_corregimiento']) ? $_REQUEST['id_corregimiento'] : 0);  
		$id_referido = (!empty($_REQUEST['id_referido']) ? $_REQUEST['id_referido'] : 0); 
		$id_subreferido = (!empty($_REQUEST['id_subreferido']) ? $_REQUEST['id_subreferido'] : 0);
		$idempresas         = 1;
		
		
		$query 	= "	UPDATE 
						clientes
					SET
						nombre = '$nombre', 
						apellidos = '$apellidos', 
						direccion = '$direccion',
						telefono = '$telefono', 
						correo = '$correo',
						movil = '$movil', 
						idempresas = '$idempresas',
						id_provincia = '$id_provincia',
						id_distrito = '$id_distrito',
						id_corregimiento = '$id_corregimiento',
						id_referido = '$id_referido',
						id_subreferido = '$id_subreferido'
					WHERE id = '$id'";
					
		$result = $mysqli->query($query);	
		
		if($result == true){
		    
		    bitacora($_SESSION['usuario'], "Clientes", "El cliente #".$id." ha sido editado", $id , $query);
		    
		    echo 1;
		}else{
		    echo 0;
		}
	}
	
	function crearpropiedad(){
		global $mysqli;
		 
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$direccion = (!empty($_REQUEST['direccion']) ? $_REQUEST['direccion'] : '');
		$id_provincia = (!empty($_REQUEST['id_provincia']) ? $_REQUEST['id_provincia'] : 0); 
		$id_distrito = (!empty($_REQUEST['id_distrito']) ? $_REQUEST['id_distrito'] : 0);  
		$id_corregimiento = (!empty($_REQUEST['id_corregimiento']) ? $_REQUEST['id_corregimiento'] : 0);  
		$id_cliente = (!empty($_REQUEST['id_cliente']) ? $_REQUEST['id_cliente'] : 0); 
		
		
		$query 	= "	INSERT INTO	propiedades (nombre,direccion,id_provincia,id_distrito,id_corregimiento,id_cliente)
					VALUES ('$nombre','$direccion','$id_provincia','$id_distrito','$id_corregimiento','$id_cliente')";
		$result = $mysqli->query($query);
		$id = $mysqli->insert_id;
		
		if($result == true){ 
		    bitacora($_SESSION['usuario'], "Propiedades", "La propiedad #".$id." ha sido creada", $id, $query);
		    $respuesta = 1;
		    echo $respuesta;
		}else{
		    echo 0;
		}
	}
	
	
?>