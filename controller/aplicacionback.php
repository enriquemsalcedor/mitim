<?php
    include("../conexion.php");
	
	require_once("Encoding.php");
	use \ForceUTF8\Encoding;
	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
    
	switch($oper){
		case "incidentesapp":
			incidentesapp();
			  break;
		case "guardarIncidenteApp":
			guardarIncidenteApp();
			  break;
		case "eliminarincidentes":
			eliminarincidentes();   
			  break; 
		case "validarClienteApp":
			validarClienteApp();   
			  break; 
		case "categoriasApp":
			categoriasApp();
				break;
		case "subcategoriasApp":
			subcategoriasApp();
				break;
		case "perfilClienteApp":
			perfilClienteApp();
			    break;
	    case "actualizarPerfil":
			actualizarPerfil();
				break;
		case "crearClientesApp":
			crearClientesApp();
				break;
		case "crearPropiedad":
			crearPropiedad();
				break;
		case "listarPropiedades":
			listarPropiedades();
				break;
		default:
		  echo "{failure:true}";
		  break;
	}

	function incidentesapp()
	{
		global $mysqli;
		
		//FILTROS MASIVO
		//$nivel = $_SESSION['nivel'];
		$data = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');		
		//$usuario  = $_SESSION['usuario'];
		$where = "";
		
		if($data != ''){
			$data = json_decode($data);
			if(!empty($data->desdef)){
				$desdef = json_encode($data->desdef);
				$where .= " AND a.fechacreacion >= $desdef ";
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where .= " AND a.fechacreacion <= $hastaf ";
			}
			if(!empty($data->idclientesf)){
				$idclientesf = json_encode($data->idclientesf);
				if($idclientesf != '[""]'){
					$where .= " AND a.idclientes IN ($idclientesf)"; 
				}
			}
			if(!empty($data->categoriaf)){
				$categoriaf = json_encode($data->categoriaf);
				if($categoriaf != '[""]'){
					$where .= " AND a.idcategorias IN ($categoriaf)";
				}
			}
			if(!empty($data->subcategoriaf)){
				$subcategoriaf = json_encode($data->subcategoriaf);
				if($subcategoriaf != '[""]'){
					$where .= " AND a.idsubcategorias IN ($subcategoriaf)";
				}
			}
			if(!empty($data->estadof)){
				$estadof = json_encode($data->estadof);
				if($estadof != '[""]'){
				    $estadof = str_replace('"',"",$estadof);
					$where .= " AND a.idestados IN ($estadof)";
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
					$where .= " AND a.asignadoa IN ($asignadoaf)";	
				}
			}
			$vowels = array("[", "]");
			$where = str_replace($vowels, "", $where);
		}
		
		$idcliente = $_GET['idcliente'];
		$estado = $_GET['estado'];
		$queryEstado = "";
		if($estado != ''){
		    if ($estado == '0'){
		        $queryEstado = " and a.idestados IN(3)";
		    }
		     if ($estado == '1'){
		        $queryEstado = " and a.idestados IN(4,7,12)";
		    }
		     if ($estado == '2'){
		        $queryEstado = " and a.idestados IN(14,16)";
		    }
		}
		
		$query  = " SELECT a.id, e.nombre AS estado, a.titulo, a.descripcion,
					IFNULL(j.nombre, a.solicitante) AS solicitante, a.fechacreacion, a.horacreacion, a.fechacierre,
					b.nombre AS idproyectos, f.nombre AS categoria, g.nombre AS subcategoria, a.asignadoa, l.nombre AS nomusuario, 
					c.nombre AS ambiente, m.serie, mar.nombre as marca, r.nombre as modelo, ti.nombre AS modalidad, h.prioridad, a.fecharesolucion, 
					case when a.fechacierre IS NULL OR LENGTH(ltrim(rTrim(a.fechacierre))) > 0
					then a.fechacreacion else a.fechacierre end as fechaorden,
					n.descripcion as idempresas, o.nombre as iddepartamentos, CONCAT(p.nombre,' ',p.apellidos) AS idclientes, a.estadoant, a.idetiquetas, LEFT(et.nombre,25) AS etiqueta, 
					et.nombre AS etiquetatt, col.valor AS color
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN activos m ON a.idactivos = m.id AND a.idambientes = m.idambientes
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					LEFT JOIN marcas mar ON m.idmarcas = mar.id
					LEFT JOIN modelos r ON m.idmodelos = r.id
					LEFT JOIN activostipos ti ON m.idtipo = ti.id
					LEFT JOIN etiquetas et ON a.idetiquetas = et.id
					LEFT JOIN colores col ON et.idcolores = col.id
					";
		
		$query  .= " WHERE a.tipo = 'incidentes' and a.idclientes = $idcliente ";
		//$query .= permisos('correctivos', '', $idusuario);
		//$query  .= " $where ";
		//$query  .= " GROUP BY a.id ";
        $query  .= " $queryEstado ";
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;

		$query  .= " ORDER BY a.id DESC";
		debugL($query,"DEBUGLCORRECTIVOSCARGAR");
		$resultado = array();
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){

			$url_imagen = null;
			$query2 = "SELECT urlimagen FROM incidentesimagenes WHERE idincidentes=".$row['id']." ORDER BY id LIMIT 1";
			$result2 = $mysqli->query($query2);
			$total = $result2->num_rows;

			if($total>0){
				while($rowimg = $result2->fetch_assoc()){
					$url_imagen = $rowimg['urlimagen'];
				}
			}

			$resultado[] = array(			
				'id' 				=> intval($row['id']),
				'estado' 			=> $row['estado'],
				'titulo' 			=> $row['titulo'],
				'descripcion' 		=> $row['descripcion'],	   
				'fechacreacion' 	=> $row['fechacreacion'],
				'horacreacion'		=> $row['horacreacion'],
				'idempresas'		=> $row['idempresas'],
				'iddepartamentos'	=> $row['iddepartamentos'],
				'idclientes'		=> $row['idclientes'],
				'idproyectos'		=> $row['idproyectos'],
				'idcategoria'		=> $row['categoria'],
				'idsubcategoria'	=> $row['subcategoria'],
				'asignadoa'			=> $row['nomusuario'],
				'sitio'				=> $row['ambiente'],
				'modalidad'			=> $row['modalidad'],
				'serie'				=> $row['serie'],
				'marca'				=> $row['marca'],
				'modelo'			=> $row['modelo'],
				'idprioridad'		=> $row['prioridad'],
				'fechacierre'		=> $row['fechacierre'],
				'estadoant'			=> $row['estadoant'],
				'imagenprincipal'   => $url_imagen,
			);
		}

		$response = array(
		  "recordsTotal" => intval($recordsTotal),
		  "data" => $resultado,
		);

		echo json_encode($response);
	}

	function guardarIncidenteApp(){
		
		global $mysqli;		

		$idcategorias  = (!empty($_REQUEST['idcategorias']) ? $_REQUEST['idcategorias'] : 0);
		$idsubcategorias  = (!empty($_REQUEST['idsubcategorias']) ? $_REQUEST['idsubcategorias'] : 0);
		$titulo  = (!empty($_REQUEST['titulo']) ? $_REQUEST['titulo'] : '');
		$descripcion  = (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');
		$idclientes  = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0);
		$imagenes  = (!empty($_REQUEST['imagenes']) ? $_REQUEST['imagenes'] : 0);

		$archivo = $_FILES['imagenes'];
		$idIncidente;
		$path = "https://toolkit.maxialatam.com/mitimdes";

		$query = "	INSERT INTO incidentes(idcategorias,idsubcategorias,titulo,descripcion,fechacreacion,horacreacion,idclientes,tipo,origen,idestados) VALUES 
					(".$idcategorias.",".$idsubcategorias.",'".ucfirst($titulo)."','".$descripcion."',now(),now(),".$idclientes.",'incidentes','APP',3) ";
		$result = ($mysqli->query($query));
		if($result == true){
			$id = $mysqli->insert_id;
			$idIncidente = $id;
			//CREAR REGISTRO EN ESTADOS INCIDENTES
			
			$queryE = " INSERT INTO incidentesestados (idincidentes,estadoanterior,estadonuevo,usuario,fechadesde,horadesde,dias)
			VALUES(".$id.", 3, 3, ".$_SESSION['user_id'].", now(), now(), 0) ";
			$mysqli->query($queryE);

			$accion = 'El Correctivo #'.$id.' ha sido Creado exitosamente';
			bitacora('0', "Correctivos", $accion, $id, $query);
			
			
			//
			if ($_FILES['imagenes']){
	            $total = count($_FILES['imagenes']['name']);
	            echo $total;
		        for( $i=0 ; $i < $total ; $i++ ) {
		        	$url="../assets/img/correctivos/".$idIncidente."/";
		        	
		        	if (!file_exists('../assets/img/correctivos/')) {
	    				mkdir("../assets/img/correctivos/", 0777, true);
					}
		        	if (!file_exists($url)) {
	    				mkdir($url, 0777, true);
					}
					$temp = $_FILES['imagenes']['tmp_name'][$i];
					$archivo = $_FILES['imagenes']['name'][$i];
					$url_imagen = $url.$archivo;
					echo $archivo;
					$url = $path."/assets/img/correctivos/".$idIncidente."/".$archivo;

					move_uploaded_file($temp, $url_imagen);					

		        	$query = "INSERT INTO incidentesimagenes(idincidentes,urlimagen,fechacreacion,horacreacion) VALUES 
							(".$idIncidente.",'".$url."',now(),now()) ";
					$result = ($mysqli->query($query)); 
					     	
		        }
		    }
			echo 1;
		}else{
			echo 0;
		}
	}

	function eliminarincidentes()
	{
		global $mysqli;

		$id 	= $_REQUEST['idincidente'];
		$query 	= "DELETE FROM incidentes WHERE id = '$id'";
		$result = $mysqli->query($query);
		if($result == true){
			echo 1;
		}else{
			echo 0;
		}
		bitacora($_SESSION['usuario'], "Incidentes", 'El Correctivo #: '.$id.' fue eliminado.', $id, $query);
	}

	function categoriasApp()
	{
		
		global $mysqli;
		$ruta = 'https://toolkit.maxialatam.com/mitimdes/assets/img/categories_app/';
		
		$query  = " SELECT a.id, a.nombre 
					FROM categorias a  
					WHERE 1=1
					ORDER BY a.id ASC ";
		 
		$result = $mysqli->query($query);
		$resultado = array();
		$recordsTotal = $result->num_rows;

		while($row = $result->fetch_assoc()){
			
			$resultado[] = array(			
				'id' 				=> $row['id'],
				'categoria' 		=> $row['nombre'],
				'url_imagen'		=> $ruta . $row['id'] . '.png'
				
			); 
		}
		$response = array(
		  "recordsTotal" => intval($recordsTotal),
		  "data" => $resultado,
		); 
		echo json_encode($response);

	} 

	function subcategoriasApp()
	{
		global $mysqli;	
		$idcategoria   = (!empty($_REQUEST['idcategoria']) ? $_REQUEST['idcategoria'] : ''); 
		$tipo   	   = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');		
		 
		$query  = " SELECT a.id, a.nombre 
					FROM subcategorias a  
					INNER JOIN categorias_subcategorias b ON b.id_subcategoria = a.id
					WHERE b.id_categoria IN (".$idcategoria.")
					ORDER BY a.nombre ASC ";
		$result = $mysqli->query($query);
		$resultado = array();
		$recordsTotal = $result->num_rows;

		while($row = $result->fetch_assoc()){
			$resultado[] = array(			
				'id' 				=> $row['id'],
				'subcategoria' 		=> $row['nombre']
				
			);
		}

		$response = array(
		  "recordsTotal" => intval($recordsTotal),
		  "data" => $resultado,
		);

		echo json_encode($response);	
	}

	function validarClienteApp()
	{
		global $mysqli;		
		
		$telefono = $_REQUEST['telefono'];

		$query  = "SELECT c.id, c.nombre, c.apellidos, c.correo, c.telefono, c.movil, c.direccion,
					p.nombre as provincia, d.nombre as distrito, co.nombre as corregimiento,
					r.nombre as referido, e.descripcion as empresa
					FROM `clientes` c 
					LEFT JOIN provincias p ON c.id_provincia = p.id
					LEFT JOIN distritos d ON c.id_distrito = d.id
					LEFT JOIN corregimientos co ON c.id_corregimiento = co.id
					LEFT JOIN referidos r ON c.id_referido = r.id
					LEFT JOIN empresas e ON c.idempresas = e.id 
					WHERE c.telefono = '".$telefono."' OR c.movil = '".$telefono."'";
					
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		if($recordsTotal>0){
			while($row = $result->fetch_assoc()){
				$resultado = array(			
					'id' 				=> intval($row['id']),
					'nombre' 			=> $row['nombre'],
					'apellidos' 		=> $row['apellidos'],
					'direccion' 		=> $row['direccion'],	   
					'telefono' 			=> $row['telefono'],
					'movil'				=> $row['movil'],
					'provincia'			=> $row['provincia'],
					'distrito'			=> $row['distrito'],
					'corregimiento'		=> $row['corregimiento'],
					'referido'			=> $row['referido'],
					'empresa'			=> $row['empresa'],
				);
			}

		}else{
			$resultado = null;
		}

		echo json_encode($resultado);
	}
	
	function perfilClienteApp()
	{
		global $mysqli;		
		
		$idcliente = $_REQUEST['idcliente'];

		$query  = "SELECT c.nombre, c.apellidos, c.correo, c.telefono, c.imagen				
					FROM `clientes` c 
					WHERE c.id = '".$idcliente."'";
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		if($recordsTotal>0){
			while($row = $result->fetch_assoc()){
				$resultado = array(			
					'nombre' 			=> $row['nombre'],
					'apellido' 			=> $row['apellidos'],
					'correo' 			=> $row['correo'],	   
					'telefono' 			=> $row['telefono'],
					'nombreCompleto'	=> $row['nombre'] ." ". $row['apellidos'],
					'imagen'			=> $row['imagen']			
				);
			}

		}else{
			$resultado = null;
		}

		echo json_encode($resultado);
	}
	
	function actualizarPerfil(){
		
		global $mysqli;	
				$ruta = 'https://toolkit.maxialatam.com/mitimdes/assets/img/clientes/';	

		$idcliente  = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : 0);
		$nombre  = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$apellido  = (!empty($_REQUEST['apellido']) ? $_REQUEST['apellido'] : '');
		$telefono  = (!empty($_REQUEST['telefono']) ? $_REQUEST['telefono'] : '');
		$correo  = (!empty($_REQUEST['correo']) ? $_REQUEST['correo'] : '');
		
		$archivo = $_FILES['imagen']['name'];
		$imagen = null;


		$query 	= "	UPDATE clientes SET nombre = '$nombre', apellidos = '$apellido', telefono = '$telefono', correo = '$correo'		
					WHERE id = '$idcliente'";

		$result = ($mysqli->query($query));
		if($result == true){
			
			if (isset($archivo) && $archivo != "") {
		     	//Obtenemos algunos datos necesarios sobre el archivo
		    	$tipo = $_FILES['imagen']['type'];
		    	$tamano = $_FILES['imagen']['size'];
		    	$temp = $_FILES['imagen']['tmp_name'];
		    	$ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
		    	
		    	if (file_exists('../assets/img/clientes/'.$idcliente.'/')) { 
		    		opendir('../assets/img/clientes/'.$idcliente.'/');
		    	}
		      	if (!file_exists('../assets/img/clientes/')) {
	    			mkdir("../assets/img/clientes/", 0777, true);
				}
				if (!file_exists('../assets/img/clientes/'.$idcliente.'/')) { 
	    			mkdir('../assets/img/clientes/'.$idcliente.'/', 0777, true);
				}
		        if (move_uploaded_file($temp, '../assets/img/clientes/'.$idcliente.'/'.$idcliente. '.' . $ext )) {
		            chmod('../assets/img/clientes/'.$idcliente.'/' , 0777);	
		            $imagen = $ruta.$idcliente.'/'.$idcliente. '.' . $ext;
		            $query 	= "	UPDATE clientes SET imagen = '$imagen' WHERE id = '$idcliente'";
					$mysqli->query($query);
					
		        }
		        		        
		    }
		    echo 1;
		}else{
			echo 0;
		}
	}


	function crearClientesApp(){
		global $mysqli;
		$resultado = array();
		$mensaje = "";
		$respuesta = 0;

		$ruta = 'https://toolkit.maxialatam.com/mitimdes/assets/img/clientes/';
		$idcliente  = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : 0);
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$apellidos = (!empty($_REQUEST['apellidos']) ? $_REQUEST['apellidos'] : '');
		$telefono = (!empty($_REQUEST['telefono']) ? $_REQUEST['telefono'] : '');
		$correo = (!empty($_REQUEST['correo']) ? $_REQUEST['correo'] : '');
		
		$archivo = $_FILES['imagen']['name'];
		$imagen = null;

		$validar1 = "SELECT id FROM clientes WHERE correo = '$correo'";
		$existeCorreo = $mysqli->query($validar1)->num_rows;

		if($existeCorreo >= 1){
			$mensaje = "No puede registrarse como cliente, el correo ingresado ya existe.";
		}

		$validar2 = "SELECT id FROM clientes WHERE telefono = '$telefono'";
		$existeTelefono = $mysqli->query($validar2)->num_rows;

		if($existeTelefono >= 1){
			$mensaje = "No puede registrarse como cliente, el teléfono ingresado ya existe.";
		}

		if($existeCorreo >= 1 && $existeTelefono >= 1){
			$mensaje = "No puede registrarse como cliente, el teléfono y el correo ingresados ya existen.";
		}

		if($existeCorreo == 0 && $existeTelefono == 0){

			$query 	= "	INSERT INTO	clientes (nombre,apellidos,telefono,correo,imagen,idempresas)
					VALUES ('$nombre','$apellidos','$telefono','$correo','$imagen',1)";
			$result = $mysqli->query($query);
			$idcliente = $mysqli->insert_id;
			
			if($result == true){ 
			    bitacora(0, "Clientes", "El cliente #".$idcliente." ha sido creado", $idcliente, $query);
			    if (isset($archivo) && $archivo != "") {

			     	//Obtenemos algunos datos necesarios sobre el archivo
			    	$tipo = $_FILES['imagen']['type'];
			    	$tamano = $_FILES['imagen']['size'];
			    	$temp = $_FILES['imagen']['tmp_name'];
			    	$ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);

			      	if (!file_exists('../assets/img/clientes/')) {
		    			mkdir("../assets/img/clientes/", 0777, true);
					}
					if (!file_exists('../assets/img/clientes/'.$idcliente.'/')) {
		    			mkdir('../assets/img/clientes/'.$idcliente.'/', 0777, true);
					}		        	
			        if (move_uploaded_file($temp, '../assets/img/clientes/'.$idcliente.'/'.$idcliente. '.' . $ext )) {
			            chmod('../assets/img/clientes/'.$idcliente.'/' , 0777);	
			            $imagen = $ruta.$idcliente.'/'.$idcliente. '.' . $ext;
			            $query 	= "	UPDATE clientes SET imagen = '$imagen' WHERE id = '$idcliente'";
						$mysqli->query($query);
						
			        }			        
			    }
			    
			    $mensaje = "Felicidades. Has sido registrado como cliente de manera satisfactoria.";
			    $respuesta = 1;
			}else{
			    $mensaje = "Ha ocurrido un error";
			}
		}
		
		$response = array(
		  "mensaje" => $mensaje,
		  "respuesta" => $respuesta
		);

		echo json_encode($response);
	}


	function crearPropiedad(){
		
		global $mysqli;		

		$idcliente  = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : 0);
		$nombre_propiedad  = (!empty($_REQUEST['nombre_propiedad']) ? $_REQUEST['nombre_propiedad'] : '');

		$calle  = (!empty($_REQUEST['calle']) ? $_REQUEST['calle'] : '');
		$direccion  = (!empty($_REQUEST['direccion']) ? $_REQUEST['direccion'] : '');
		$referencia  = (!empty($_REQUEST['referencia']) ? $_REQUEST['referencia'] : '');
		$longitud  = (!empty($_REQUEST['longitud']) ? $_REQUEST['longitud'] : '');
		$latitud  = (!empty($_REQUEST['latitud']) ? $_REQUEST['latitud'] : '');

		$query = "	INSERT INTO propiedades(idcliente,nombre_propiedad,fecha_propiedad) VALUES 
					('$idcliente','$nombre_propiedad',now()) ";

		$result = ($mysqli->query($query));
		if($result == true){
			$id = $mysqli->insert_id;
			
			$query2 = " INSERT INTO direccionespropiedades (calle,fecha,direccion,referencia,idpropiedad,idcliente,longitud,latitud)
			VALUES('$calle',now(),'$direccion','$referencia','$id','$idcliente','$longitud','$latitud') ";
			$mysqli->query($query2);

			$accion = 'La Propiedad #'.$id.' ha sido Creada exitosamente';
			bitacora('0', "Propiedades", $accion, $id, $query);
			
			
			echo 1;
		}else{
			echo 0;
		}
	}

	function listarPropiedades(){
		
		global $mysqli;		

		$idcliente  = (!empty($_REQUEST['idcliente']) ? $_REQUEST['idcliente'] : 0);

		$query  = " SELECT p.id,p.nombre_propiedad,d.calle,d.direccion,d.referencia,d.longitud,d.latitud
					FROM propiedades p
					LEFT JOIN direccionespropiedades d ON p.id = d.idpropiedad
					WHERE p.idcliente = '$idcliente'";

		$resultado = array();
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;

		if($recordsTotal>0){
			while($row = $result->fetch_assoc()){
				$resultado[] = array(			
					'id' 				=> intval($row['id']),
					'nombre_propiedad' 	=> $row['nombre_propiedad'],
					'calle' 		    => $row['calle'],
					'direccion' 		=> $row['direccion'],	   
					'referencia' 		=> $row['referencia'],
					'longitud'			=> $row['longitud'],
					'latitud'			=> $row['latitud'],
					
				);
			}

		}else{
			$resultado = null;
		}

		$response = array(
		  "recordsTotal" => intval($recordsTotal),
		  "data" => $resultado,
		);

		echo json_encode($response);
	}

?>