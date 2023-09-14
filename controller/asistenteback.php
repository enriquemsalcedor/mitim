<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){ 
		case "createclientes": 
			  createclientes();
			  break;
		case "updateclientes": 
			  updateclientes();
			  break;
		case "createproyectos": 
			  createproyectos();
			  break;
		case "updateproyectos": 
			  updateproyectos();
			  break;
		case "updateConfiguracion": 
			  updateConfiguracion();
			  break;
	    case "createcontacto": 
			  createcontacto();
			  break;
		case "deletecontacto": 
			  deletecontacto();
			  break;
		case "getetiquetas": 
			  getetiquetas();
			  break;
		case "asociaretiquetas": 
			  asociaretiquetas();
			  break;
		case "createetiquetas": 
			  createetiquetas();
			  break;
		case "editetiquetas": 
			  editetiquetas();
			  break;
		case "getcolores": 
				getcolores();
				break;  
		default:
			  echo "{failure:true}";
			  break;
	} 
	
	function createclientes(){
		global $mysqli;
		 
		$nombre 			= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$siglas 			= (!empty($_REQUEST['siglas']) ? $_REQUEST['siglas'] : '');
		$direccion 			= (!empty($_REQUEST['direccion']) ? $_REQUEST['direccion'] : '');
		$telefono			= (!empty($_REQUEST['telefono']) ? $_REQUEST['telefono'] : '');
		$contacto			= (!empty($_REQUEST['contacto']) ? $_REQUEST['contacto'] : '');
		$movil		    	= (!empty($_REQUEST['movil']) ? $_REQUEST['movil'] : '');  
		$idempresas         = 1;
		$query 	= "	INSERT INTO	clientes (nombre,siglas,direccion,telefono,contacto,movil,idempresas)
					VALUES ('$nombre','$siglas','$direccion','$telefono','$contacto','$movil',1)";
		$result = $mysqli->query($query);
		 
		if($result == true){
		    $idclientes = $mysqli->insert_id;    
		    bitacora($_SESSION['usuario'], "Asistente de configuración de proyectos", "El cliente #".$idclientes." ha sido creado", $idclientes, $query);
		    
		    echo $idclientes;
		}else{
		    echo 0;
		}
	}
	
	function updateclientes(){
		global $mysqli;
		
		$id 				= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0 );
		$nombre 			= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$siglas 			= (!empty($_REQUEST['siglas']) ? $_REQUEST['siglas'] : '');
		$direccion 			= (!empty($_REQUEST['direccion']) ? $_REQUEST['direccion'] : '');
		$telefono			= (!empty($_REQUEST['telefono']) ? $_REQUEST['telefono'] : '');
		$contacto			= (!empty($_REQUEST['contacto']) ? $_REQUEST['contacto'] : '');
		$movil		    	= (!empty($_REQUEST['movil']) ? $_REQUEST['movil'] : ''); 
		//$idempresas			= (!empty($_REQUEST['idempresas']) ? $_REQUEST['idempresas'] : '');
		$idempresas         = 1;
		
		
		$query 	= "	UPDATE clientes SET nombre = '$nombre', siglas = '$siglas', direccion = '$direccion', telefono = '$telefono', contacto = '$contacto' ,movil = '$movil', idempresas = '$idempresas' WHERE id = '$id'";
		$result = $mysqli->query($query);	
		
		if($result == true){
		     
		    echo $id;
		}else{
		    echo 0;
		}
	}
	
	function createproyectos(){
		global $mysqli;
		
		$codigo		    	= (!empty($_REQUEST['codigo']) ? $_REQUEST['codigo'] : '');
		$nombre		    	= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$correlativo    	= (!empty($_REQUEST['correlativo']) ? $_REQUEST['correlativo'] : ''); 
		$idclientes	    	= (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$descripcion    	= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');
		$estado	            = (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : '');
		$tiposervicio   	= (!empty($_REQUEST['tiposervicio']) ? $_REQUEST['tiposervicio'] : '');
		$horascontratadas	= (!empty($_REQUEST['horascontratadas']) ? $_REQUEST['horascontratadas'] : '');
		$fechainicio    	= (!empty($_REQUEST['fechainicio']) ? $_REQUEST['fechainicio'] : '');
		$fechafin	        = (!empty($_REQUEST['fechafin']) ? $_REQUEST['fechafin'] : '');
		$contactos	        = (!empty($_REQUEST['contactos']) ? $_REQUEST['contactos'] : '');
		
		//Evitar duplicado
		$sql = "SELECT nombre FROM proyectos WHERE nombre = '".$nombre."' AND idclientes = ".$idclientes.""; 
		//echo $sql;
		$rsql = $mysqli->query($sql);
		if($rsql->num_rows > 0){
		    echo 2;
		}else{
    		$query 	= "	INSERT INTO	proyectos (nombre,fecha,idclientes,descripcion,estado)
			VALUES ('".$nombre."',CURDATE(),'".$idclientes."','".$descripcion."','".$estado."')"; 
			//echo $query;
    		$result = $mysqli->query($query); 
    		
    		if($result==true){ 
				$id = $mysqli->insert_id; 
				
				//Guardar Contactos
				if (is_array($contactos) || is_object($contactos))
				{
					foreach($contactos as $contacto){

						$nombre_contacto = $contacto['nombre'];
						$tlf_oficina	 = $contacto['tlf_oficina'];
						$movil 			 = $contacto['movil'];
						$email 			 = $contacto['email'];
						
						$sqlC = " INSERT INTO proyectoscontactos (idproyectos,nombre,tlf_oficina,movil,email) 
									VALUES (".$id.",'".$nombre_contacto."','".$tlf_oficina."','".$movil."','".$email."')";
						$rsqlC = $mysqli->query($sqlC); 
						if($rsqlC == true){
						    $idcontacto = $mysqli->insert_id; 
							bitacora($_SESSION['usuario'], "Asistente de configuración de proyectos", "El contacto #".$idcontacto." ha sido creado", $idcontacto, $sqlC);
						}
					}
				} 
    		    $sql = " INSERT INTO proyectoscontratos (idproyectos,fechainicio,fechafin,horascontratadas,tiposervicio)
    		             VALUES (".$id.",'".$fechainicio."','".$fechafin."',".$horascontratadas.",'".$tiposervicio."')";
    		    $rsql = $mysqli->query($sql); 
    		    if($rsql == true){
    		        $idcontrato = $mysqli->insert_id;
    		        bitacora($_SESSION['usuario'], "Asistente de configuración de proyectos", "El contrato #".$idcontrato." ha sido creado", $idcontrato, $sql);
    		    }
    		    bitacora($_SESSION['usuario'], "Asistente de configuración de proyectos", "El proyecto #".$id." ha sido creado", $id, $query);
    		    echo $id;  
    			
    		}else{
    			echo 0;
    		}
		} 
	}
	
	function updateproyectos(){
		global $mysqli;
		
		$id 			    = $_REQUEST['id'];  
		$codigo		     	= (!empty($_REQUEST['codigo']) ? $_REQUEST['codigo'] : '');
		$nombre		    	= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$correlativo    	= (!empty($_REQUEST['correlativo']) ? $_REQUEST['correlativo'] : ''); 
		$idclientes		    = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : ''); 
		$descripcion	    = (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : ''); 
		$tiposervicio       = (!empty($_REQUEST['tiposervicio']) ? $_REQUEST['tiposervicio'] : '');
		$horascontratadas	= (!empty($_REQUEST['horascontratadas']) ? $_REQUEST['horascontratadas'] : '');
		$fechainicio    	= (!empty($_REQUEST['fechainicio']) ? $_REQUEST['fechainicio'] : '');
		$fechafin	        = (!empty($_REQUEST['fechafin']) ? $_REQUEST['fechafin'] : '');
		
		$query 	= "	UPDATE 
		                proyectos
		            SET codigo = '".$codigo."', 
    		            nombre = '".$nombre."', 
    		            correlativo = '".$correlativo."', 
    		            idclientes = '".$idclientes."', 
    		            descripcion = '".$descripcion."' 
		            WHERE id = ".$id."";
		            //echo $query;
		$result = $mysqli->query($query);	
		
		if($result==true){ 
		     $queryC = " UPDATE 
		                    proyectoscontratos 
		                 SET tiposervicio = '".$tiposervicio."',
		                     horascontratadas = '".$horascontratadas."',
		                     fechainicio = '".$fechainicio."',
		                     fechafin = '".$fechafin."'
		                 WHERE idproyectos = ".$id."";
		      $resultC = $mysqli->query($queryC);
		      
		     bitacora($_SESSION['usuario'], "Configuración de proyectos", "El proyecto #".$id." ha sido actualizado", $id, $query);
	        echo $id;
	    }else{
	        echo 0;
	    }
	}
	
	function updateConfiguracion(){
		global $mysqli;
		
		$idclientes  = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0 );
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		 
		$query 	= "	UPDATE 
		                proyectos 
		            SET 
		                idclientes = ".$idclientes." 
		            WHERE id = ".$idproyectos."";
		            
		$result = $mysqli->query($query);	
		if($result == true){
		      $qCat = "UPDATE categoriaspuente SET idclientes = ".$idclientes." WHERE idproyectos = ".$idproyectos."";
		      $rCat = $mysqli->query($qCat); 
		      
		      $qSubcat = "UPDATE subcategoriaspuente SET idclientes = ".$idclientes." WHERE idproyectos = ".$idproyectos."";
		      $rSubcat = $mysqli->query($qSubcat); 
		      
		      $qAmb = "UPDATE ambientespuente SET idclientes = ".$idclientes." WHERE idproyectos = ".$idproyectos."";
		      $rAmb = $mysqli->query($qAmb); 
		      
		      $qSubamb = "UPDATE subambientespuente SET idclientes = ".$idclientes." WHERE idproyectos = ".$idproyectos."";
		      $rSubamb = $mysqli->query($qSubamb); 
		      
		      $qEst = "UPDATE estadospuente SET idclientes = ".$idclientes." WHERE idproyectos = ".$idproyectos."";
		      $rEst = $mysqli->query($qEst); 
		      
		      $qDep = "UPDATE departamentospuente SET idclientes = ".$idclientes." WHERE idproyectos = ".$idproyectos."";
		      $rDep = $mysqli->query($qDep); 
		      
		      echo 1;
		}else{
		    echo 0;
		}
	}
	
	function createcontacto(){
		global $mysqli;
		
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : ''); 
		$nombre		= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$tlfoficina = (!empty($_REQUEST['tlfoficina']) ? $_REQUEST['tlfoficina'] : ''); 
		$movil	    = (!empty($_REQUEST['movil']) ? $_REQUEST['movil'] : '');
		$email    	= (!empty($_REQUEST['email']) ? $_REQUEST['email'] : ''); 
		 
		$sqlC = " INSERT INTO proyectoscontactos (idproyectos,nombre,tlf_oficina,movil,email) 
					VALUES (".$idproyectos.",'".$nombre."','".$tlfoficina."','".$movil."','".$email."')";
		$rsqlC = $mysqli->query($sqlC); 
		if($rsqlC == true){
		    $idcontacto = $mysqli->insert_id; 
		    bitacora($_SESSION['usuario'], "Asistente de configuración de proyectos", "El contacto #".$idcontacto." ha sido creado", $idcontacto, $sqlC);
		    echo 1;
		}else{
    		echo 0; 
		} 
	}
	
	function deletecontacto(){
		
		global $mysqli;
		
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : ''); 
		$email        = (!empty($_REQUEST['email']) ? $_REQUEST['email'] : ''); 
		
		$query 	= "DELETE FROM proyectoscontactos WHERE idproyectos = ".$idproyectos." AND email = '".$email."'"; 
		//echo $query;
		$result = $mysqli->query($query);
		
		if($result==true){
			
			bitacora($_SESSION['usuario'], "Configuración de proyectos", "El contacto #".$id." ha sido eliminado", $id , $query);
			
			echo 1;
		}else{
			echo 0;
		} 
	}
	 
	function createetiquetas(){
		global $mysqli;
		 
		$idclientes  = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : ''); 
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : ''); 
		$idcolores 	 = (!empty($_REQUEST['idcolores']) ? $_REQUEST['idcolores'] : '');
		$nombre 	 = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		
		//Evitar duplicado
		$sql = " SELECT id FROM etiquetas WHERE nombre = '".$nombre."' ";
		//echo $sql;
		$rsql = $mysqli->query($sql);
		if($rsql->num_rows > 0){
			echo 2;
		}else{
			//Guardar en la tabla etiquetas
			$query 	= "	INSERT INTO	etiquetas (idcolores,nombre)
						VALUES (".$idcolores.",'".$nombre."')";
			$result = $mysqli->query($query);
			
			if($result == true){
				$idetiquetas = $mysqli->insert_id;  		
				
				//Bitácora
				bitacora($_SESSION['usuario'], "Configuración de proyectos", "La etiqueta #".$idetiquetas." ha sido creada", $idetiquetas, $query);
				
				echo $idetiquetas;
			}else{
				echo 0;
			}
		} 
	}
	
	function editetiquetas(){
		global $mysqli;
		 
		$idetiquetas  = (!empty($_REQUEST['idetiquetas']) ? $_REQUEST['idetiquetas'] : ''); 
		$idclientes  = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : ''); 
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : ''); 
		$idcolores 	 = (!empty($_REQUEST['idcolores']) ? $_REQUEST['idcolores'] : '');
		$nombre 	 = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		
		//Evitar duplicado
		$sql = " SELECT id FROM etiquetas WHERE nombre = '".$nombre."' AND idcolores = ".$idcolores."";
		//echo $sql;
		$rsql = $mysqli->query($sql);
		if($rsql->num_rows > 0){
			echo 2;
		}else{
			//Actualizar tabla etiquetas
			$query 	= "	UPDATE 
							etiquetas 
						SET 
							nombre = '".$nombre."', 
							idcolores = ".$idcolores." 
						WHERE 
							id = ".$idetiquetas." ";
			$result = $mysqli->query($query);
			
			if($result == true){
				$idetiquetas = $mysqli->insert_id;  		
				
				//Bitácora
				bitacora($_SESSION['usuario'], "Configuración de proyectos", "La etiqueta #".$idetiquetas." ha sido actualizada", $idetiquetas, $query);
				
				echo 1;
			}else{
				echo 0;
			}
		} 
	} 
	  
	function getetiquetas(){
		global $mysqli;
		$combo	= ''; 
		
		$query  = " SELECT a.id, a.nombre, b.valor 
					FROM etiquetas a 
					INNER JOIN colores b ON b.id = a.idcolores 
					WHERE 1 ORDER BY a.nombre ASC ";		
		$result = $mysqli->query($query); 
		
		$arrEtq = array();
		while($row = $result->fetch_assoc()){
			$arrEtq[] = array(  "id" => $row['id'],  "valor" => $row['valor'], "nombre" => $row['nombre'] );
		}
		echo json_encode($arrEtq);
	} 
	
	function asociaretiquetas(){
		global $mysqli;
		 
		$idclientes  = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : ''); 
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : ''); 
		$etiquetas 	 = (!empty($_REQUEST['etiquetas']) ? $_REQUEST['etiquetas'] : ''); 
		$response 	 = 1;
		
		if(is_array($etiquetas)){
			foreach ($etiquetas as $etiq){ 
				$query 	= "	INSERT INTO	proyectosetiquetas (idclientes,idproyectos,idetiquetas)
							VALUES (".$idclientes.",".$idproyectos.",".$etiq.")";
				$result = $mysqli->query($query); 
				if (!$result) {
					$response = 0;
				}
			}
		}else{
			if($etiquetas != ""){
				$query 	= "	INSERT INTO	proyectosetiquetas (idclientes,idproyectos,idetiquetas)
							VALUES (".$idclientes.",".$idproyectos.",".$etiq.")";
				$result = $mysqli->query($query);
				if (!$result) {
					$response = 0;
				}
			}
		}
		
		echo $response;
	}
	
	function getcolores(){
		global $mysqli;
		$combo	= ''; 
		
		$query  = " SELECT a.id, a.valor, CASE WHEN et.id IS NOT NULL THEN 1 WHEN et.id IS NULL THEN 0 ELSE 0 END AS existe
					FROM colores a LEFT JOIN (SELECT id, idcolores FROM etiquetas) et ON et.idcolores = a.id 
					WHERE 1 ORDER BY a.id ASC ";		
		$result = $mysqli->query($query); 
		
		$arrCol = array();
		while($row = $result->fetch_assoc()){
			$existe = $row['existe'];
			if($existe != 1){
				$arrCol[] = array(  "id" => $row['id'],  "valor" => $row['valor'] );
			} 
		}
		echo json_encode($arrCol);
	}
?>