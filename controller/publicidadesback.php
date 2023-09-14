<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){
		case "cargar": 
			  cargar();
			  break;
		case "getpublicidades": 
			  getpublicidades();
			  break;
		case "createpublicidad": 
			  createpublicidad();
			  break;
		case "updatepublicidad": 
			  updatepublicidad();
			  break;
		case "deletepublicidad": 
			  deletepublicidad();
			  break;
		case "hayRelacion":
			  hayRelacion();
			  break;
		case "getpublicidadapp":
			  getpublicidadapp();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	
	
	function cargar(){
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
		//ASC or DESC
		*/
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); 
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		/*--------------------------------------------------------------------*/
		$nivel = $_SESSION["nivel"]; 
		$query = " SELECT a.id, a.titulo, a.estatus FROM publicidad a ";
		$query  .= " WHERE a.id!=0 ";
    	  
        $query  .= " $where ";
		debugL($query,"publicidad");
	    $query .= " GROUP BY a.id ";
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		//$query  .= " ORDER BY a.nombre LIMIT $start, $length ";
		$query  .= " ORDER BY a.titulo";
		
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		$response = array();
		
		while($row = $result->fetch_assoc()){	
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center droptable">

						';



			$btnVer = '<a class="dropdown-item text-warning" href="publicidad.php?id='.$row['id'].'&type=view"><i class="fas fa-eye mr-2"></i>Ver</a>';

			$btnEditar = '<a class="dropdown-item text-info" href="publicidad.php?id='.$row['id'].'&type=edit"><i class="fas fa-pen mr-2"></i>Editar</a>';

			$btnEliminar='<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'" href="#"><i class="fas fa-trash mr-2"></i>Eliminar</a>';


//			$acciones.=$btnVer;

			if(($nivel == 1 ) || ($nivel == 2 ) ){
				$acciones.=$btnEditar;
				$acciones.=$btnEliminar;

			}else if($nivel > 2){ //($nivel == 7)
				$acciones.=$btnVer;

			}


			$acciones.='
									</div>
								</div>
							</td>';

			$estatus = '';
			if($row['estatus']==1){
				$estatus = 'Publicado';
			}else{
				$estatus = 'Sin Publicar';
			}

			$resultado[] = array(
				'id' 			=>	$row['id'],
				'acciones' 		=>	$acciones, 
				'titulo'		=>	$row['titulo'],
				'estatus' 	    =>	$estatus,
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
	
	function createpublicidad(){
		global $mysqli; 	
		
		$titulo			= (!empty($_REQUEST['titulo']) ? $_REQUEST['titulo'] : '');
		$descripcion	= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');
		$imagen 		= (!empty($_REQUEST['imagen']) ? $_REQUEST['imagen'] : '');

		$urlimagen = null;
	
		
		$query 	= '	INSERT INTO	publicidad (titulo, descripcion, urlimagen, fechacreacion, estatus)	VALUES ( "'.$titulo.'", "'.$descripcion.'", "'.$urlimagen.'", now(), 1 ) ';
		$result = $mysqli->query($query);
		$id = $mysqli->insert_id;
		
		if($result == true){
			$campos = array(
				'Titulo' 		=> $titulo,
				'Descripción' 	=> $descripcion,
				'Imagen' 		=> $imagen
			);
			nuevoRegistro('Publicidad','Publicidad',$id,$campos,$query);
			
			//guardo la imagen y actualizo el registro
			if ($_FILES['imagen']){
				$url="../assets/img/publicidad/";
				
				if (!file_exists($url)) {
					mkdir($url, 0777, true);
				}
				if (!file_exists('../assets/img/publicidad/'.$id.'/')) {
					mkdir("../assets/img/publicidad/".$id."/", 0777, true);
				}
				
				$temp = $_FILES['imagen']['tmp_name'];
				$archivo = generarRandom();
	
				$array = explode('.', $_FILES['imagen']['name']);
				$extension = end($array);
	
				$urlimagen = "assets/img/publicidad/$id/".$archivo.".".$extension;
				$file = $url."/".$id."/".$archivo.".".$extension;
		
				if (move_uploaded_file($temp, $file)){
					chmod('../assets/img/publicidad/'.$id.'/' , 0777);	
					$query 	= "	UPDATE publicidad SET urlimagen = '$urlimagen' WHERE id = '$id'";
					$mysqli->query($query);
				}
			}
			echo 1;
		}else{
			echo 0;
		}
		
	}
	
	function getpublicidades(){
		global $mysqli;		
		$nivel		= (isset($_SESSION["nivel"]) ? $_SESSION["nivel"] : null);
		$return_delete = 0;
		if($nivel!=null){//NO SESSION


			$idpublicidades = (!empty($_REQUEST['idpublicidades']) ? $_REQUEST['idpublicidades'] : '');
			$query 			= "	SELECT * FROM publicidad WHERE id = '".$idpublicidades."' ";
			$result 		= $mysqli->query($query);


			while($row = $result->fetch_assoc()){			
				$resultado = array(  
					'titulo'		=>	$row['titulo'],
					'imagen'	 	=>	$row['urlimagen'],
					'descripcion' 	=>	$row['descripcion']	
				);
			}
			
			if( isset($resultado) ) {
				echo json_encode($resultado);
			} else {
				echo "0";
			}
		}else{
			echo "0";// NO SESSION
		}

	}
	
	function updatepublicidad(){
		global $mysqli;
		$path = "https://toolkit.maxialatam.com/mitimqa";

		$id			 	= (!empty($_REQUEST['idpublicidad']) ? $_REQUEST['idpublicidad'] : '');
		$titulo			= (!empty($_REQUEST['titulo']) ? $_REQUEST['titulo'] : '');
		$descripcion	= (!empty($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '');
		$imagen 		= (!empty($_REQUEST['imagen']) ? $_REQUEST['imagen'] : '');

		$urlimagen = null;

		$valoresold = getRegistroSQL("SELECT titulo AS Titulo, descripcion AS 'Descripción' FROM publicidad WHERE id = '".$id."' ");
		$query 	= '	UPDATE publicidad SET titulo = "'.$titulo.'", descripcion = "'.$descripcion.'", urlimagen = "'.$urlimagen.'" WHERE id = "'.$id.'" ';
		$result = $mysqli->query($query);	
		
		if($result == true){
			$campos = array(
				'Titulo' 			=> $titulo,
				'Descripción' 		=> $descripcion
			);
			
			actualizarRegistro('Publicidad','Publicidad',$id,$valoresold,$campos,$query);

			//guardo la imagen y actualizo el registro
			if ($_FILES['imagen']){
				$url="../assets/img/publicidad/";
				
				if (file_exists('../assets/img/publicidad/'.$id.'/')) { 
		    		opendir('../assets/img/publicidad/'.$id.'/');
		    	}
				if (!file_exists($url)) {
					mkdir($url, 0777, true);
				}
				if (!file_exists('../assets/img/publicidad/'.$id.'/')) {
					mkdir("../assets/img/publicidad/".$id."/", 0777, true);
				}
				
				$temp = $_FILES['imagen']['tmp_name'];
				$archivo = generarRandom();
	
				$array = explode('.', $_FILES['imagen']['name']);
				$extension = end($array);
	
				$urlimagen = "assets/img/publicidad/$id/".$archivo.".".$extension;
				$file = $url."/".$id."/".$archivo.".".$extension;
		
				if (move_uploaded_file($temp, $file)){
					chmod('../assets/img/publicidad/'.$id.'/' , 0777);	
					$query 	= "	UPDATE publicidad SET urlimagen = '$urlimagen' WHERE id = '$id'";
					$mysqli->query($query);
				}
			}
			echo 1;
		}else{
			echo 0;
		}

	}
	


		function hayRelacion(){
	    global $mysqli;
	    
	    $id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
	    
	    $existe_activo = 0;
	    $qact = "SELECT 
					activos.id
				FROM activos 
				WHERE activos.idmarcas = '$id' LIMIT 1;"; 

	    $existe_modelo = 0;
	    $qMod = "SELECT modelos.id FROM modelos 
                WHERE modelos.idmarcas= '$id' LIMIT 1;"; 

//	    $qMod = "SELECT modelos.id FROM modelos 
 //               	INNER JOIN marcas ON marcas.id = modelos.idmarcas
 //               WHERE modelos.id= '$id' LIMIT 1;"; 



        $rQAct = $mysqli->query($qact);
		if($rQAct->num_rows > 0){ 
            $existe_activo = 1; 
        }

        $rQMod = $mysqli->query($qMod);
		if($rQMod->num_rows > 0){ 
            $existe_modelo = 1; 
        }


		if(
			($existe_activo == 1) || 
			($existe_modelo == 1) 
		){
			echo 1;
		}else{
			echo 0;
		}
	}


	function deletepublicidad(){
		global $mysqli;
		$nivel		= (isset($_SESSION["nivel"]) ? $_SESSION["nivel"] : null);
		$return_delete = 0;

		if($nivel!=null){//NO SESSION
			if($nivel!=7){//NO AUTORIZADO
				$id		= (!empty($_REQUEST['idpublicidad']) ? $_REQUEST['idpublicidad'] : '');
				$titulo	= (!empty($_REQUEST['titulo']) ? $_REQUEST['titulo'] : '');			
				$query 	=  " DELETE FROM publicidad WHERE id = '$id' ";
				$result =  $mysqli->query($query);	
				if($result == true){		
					eliminarRegistro('Publicidad','Publicidad',$titulo,$id,$query);
					$return_delete = 1;
				}
			}else{
				$return_delete = "No Authorized";

			}
		}else{
			$return_delete = "null";
		}

		echo $return_delete;

		
	}	

	function generarRandom() {
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 10; $i++) {
			$randomString .= $characters[random_int(0, $charactersLength - 1)];
		}
		return $randomString;
	}


	function getpublicidadapp() {
		global $mysqli;
		$ruta = 'https://toolkit.maxialatam.com/mitimqa/';
		
		$query  = " SELECT a.id, a.titulo, a.urlimagen, a.descripcion 
					FROM publicidad a  
					WHERE 1=1
					ORDER BY a.id ASC ";
		 
		$result = $mysqli->query($query);
		$resultado = array();
		$recordsTotal = $result->num_rows;

		while($row = $result->fetch_assoc()){
			
			$resultado[] = array(			
				'id' 			=> $row['id'],
				'urlimagen'		=> $ruta.$row['urlimagen'],
				'titulo' 		=> $row['titulo'],
				'descripcion' 	=> $row['descripcion'],
				
			); 
		}
		$response = array(
		  "records" => intval($recordsTotal),
		  "data" => $resultado,
		); 
		echo json_encode($response);


	}
?>