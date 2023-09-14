<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){
		case "cargarclientes": 
			  cargarclientes();
			  break;
		case "getclientes": 
			  getclientes();
			  break;
		case "createclientes": 
			  createclientes();
			  break;
		case "updateclientes": 
			  updateclientes();
			  break;
		case "deleteclientes": 
			  deleteclientes();
			  break;
	    case "showproyectos": 
			  showproyectos();
			  break;
		case "showcategorias": 
			  showcategorias();
			  break;
		case "showsubcategorias": 
			  showsubcategorias();
			  break;
	    case "existeincidentescli": 
			  existeincidentescli();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	
	
	function cargarclientes(){
		global $mysqli;
		
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
    	$where = array();
    	
    	$draw 		= $_REQUEST["draw"];
    	//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
    	$orderByColumnIndex  = $_REQUEST['order'][0]['0'];// index of the sorting column (0 index based - i.e. 0 is the first record)
    	$orderBy 	= 0;//$_REQUEST['id'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
    	$orderType 	= "DESC";//$_REQUEST['order'][0]['dir']; // ASC or DESC
    	$start   	= (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);
    	$length   	= (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		
		$query = " SELECT a.id, a.nombre, a.apellidos, a.direccion, a.telefono, a.correo, a.movil, b.descripcion, c.nombre AS id_provincia, d.nombre AS id_distrito, e.nombre AS id_corregimiento,
				   f.nombre AS id_referido, g.nombre AS id_subreferido	
				   FROM clientes a
				   INNER JOIN empresas b ON a.idempresas = b.id 
				   LEFT JOIN provincias c ON c.id = a.id_provincia
				   LEFT JOIN distritos d ON d.id = a.id_distrito
				   LEFT JOIN corregimientos e ON e.id = a.id_corregimiento
				   LEFT JOIN referidos f ON f.id = a.id_referido
				   LEFT JOIN subreferidos g ON g.id = a.id_subreferido
				   AND a.idempresas = 1
				   WHERE 1=1 ";  
		/*---------------------------------------------
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
				
				if ($column == 'nombre') {
					$column = 'a.nombre';
    				$where2[]= " $column like '%".$campo."%' ";
				}
				
				if ($column == 'apellidos') {
					$column = 'a.apellidos';
    				$where2[]= " $column like '%".$campo."%' ";
				}
				
				if ($column == 'direccion') {
					$column = 'a.direccion';
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
                
                if ($column == 'movil') {
					$column = 'a.movil';
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
    		a.apellidos like '%".$searchGeneral."%' OR
    		a.direccion like '%".$searchGeneral."%' OR
    		a.telefono like '%".$searchGeneral."%' OR
    		a.correo like '%".$searchGeneral."%' OR
    		a.movil like '%".$searchGeneral."%'
			)";
    	}
        $query  .= " $where ";*/
        
	    $query .= " GROUP BY a.id ";
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
										<a class="dropdown-item text-info" href="cliente.php?id='.$row['id'].'&type=edit"><i class="fas fa-pen mr-2"></i>Editar</a><a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
									</div>
								</div>
							</td>';
							
		    $resultado[]= array(
				'id' =>	$row['id'],
				'acciones' => $acciones,
				'nombre' => $row['nombre'],
				'apellidos' => $row['apellidos'],
				'direccion' => $row['direccion'],
				'telefono' => $row['telefono'],
				'correo' =>	$row['correo'],
				'movil' => $row['movil'],
				'id_provincia' => $row['id_provincia'],
				'id_distrito' => $row['id_distrito'],
				'id_corregimiento' => $row['id_corregimiento'],
				'id_referido' => $row['id_referido'],
				'id_subreferido' => $row['id_subreferido']
			);
		}
		$response = array(
			"draw" => intval($draw),
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
	
	function createclientes(){
		global $mysqli;
		 
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
		$creacion_rapida = (!empty($_REQUEST['creacion_rapida']) ? $_REQUEST['creacion_rapida'] : 0);  
		
		$idempresas         = 1;
		$query 	= "	INSERT INTO	clientes (nombre,apellidos,direccion,telefono,correo,movil,idempresas,id_provincia,id_distrito,id_corregimiento,id_referido,id_subreferido,contactado)
					VALUES ('$nombre','$apellidos','$direccion','$telefono','$correo','$movil','$idempresas','$id_provincia','$id_distrito','$id_corregimiento','$id_referido','$id_subreferido',1)";
		$result = $mysqli->query($query);
		$idcliente = $mysqli->insert_id;
		
		if($result == true){ 
		    bitacora($_SESSION['usuario'], "Clientes", "El cliente #".$idcliente." ha sido creado", $idcliente, $query);
		    $respuesta = ($creacion_rapida == 1 ) ? $idcliente : 1;
		    echo $respuesta;
		}else{
		    echo 0;
		}
	}
	
	function showproyectos(){
		global $mysqli;
		
		$idclientes	= (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0);
		/*
		$query 		= " SELECT id,codigo,nombre,correlativo
						FROM proyectos
						WHERE idclientes = '$idclientes' ";
		*/
		$query = "  SELECT a.id, a.codigo, a.nombre, GROUP_CONCAT(' ', b.nombre) AS departamentos
					FROM proyectos a
					LEFT JOIN departamentos b ON find_in_set (b.id,a.iddepartamentos)
					WHERE a.idclientes = '$idclientes'
					GROUP BY a.id ";
		$result = $mysqli->query($query); 
		
		if ($result->num_rows > 0){
			$resultado = '<table id="tbproyectos">
							<thead><tr class="color_header">
								<th></th>
								<th class="text-center">Acciones</th>
								<th>Codigo</th>
								<th>Proyectos</th>
								<th>Departamentos</th>
							</tr></thead>';
			$resultado .= '<tbody>';
				
			while($row = $result->fetch_assoc()){	 
				
				$resultado .= "<tr><td></td>
									<td><span class='icon-col blue fa fa-eye boton-ver-categorias' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Ver Categorías' data-placement='right'></span>
										<span class='icon-col red fa fa-trash boton-eliminar-proyectos' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Proyecto' data-placement='right'></span>
										<span class='icon-col blue fa fa-pencil boton-editar-proyectos' data-id='".$row['id']."' data-original-title='Editar Proyecto' data-placement='right'></span>  
										<span class='icon-col blue fa fa-plus boton-agregar-categorias' data-id='".$row['id']."' data-toggle='modal' data-target='#modalcategorias' data-original-title='Agregar Categoría' data-placement='right'></span> 
									 </td>
									<td class = 'text-left'>".$row['codigo']."</td>
									<td class = 'text-left'>".$row['nombre']."</td>
									<td class = 'text-left'>".$row['departamentos']."</td>
								</tr>";
			}
			
			//eliminar
			/*<span class='icon-col red fa fa-trash boton-eliminar-proyectos' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Proyecto' data-placement='right'></span> */
			
			$resultado .= '</tbody></table>';
			
		} else {
			$resultado = 'No hay resultados que mostrar.';
		}
		
		echo $resultado;
	}
	
	function showcategorias(){
		global $mysqli;
		
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : 0);
		
		$query 		 = " SELECT id, nombre, tipo 
						 FROM categorias 
						 WHERE idproyecto = '".$idproyectos."' ";
				   
		$result = $mysqli->query($query); 
		
		if ($result->num_rows > 0){
			$resultado = '<table id="tbcategorias"><thead><tr class="color_header"><th></th><th class="text-center">Acciones</th><th>Categorías</th><th>Tipo</th></tr></thead>';
			$resultado .= '<tbody>';
				
			while($row = $result->fetch_assoc()){	 
				
				$resultado .= "<tr><td></td>
									<td><span class='icon-col blue fa fa-eye boton-ver-subcategorias' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Ver Subcategorías' data-placement='right'></span>
									 <span class='icon-col red fa fa-trash boton-eliminar-categorias' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Categoría' data-placement='right'></span>
									 <span class='icon-col blue fa fa-pencil boton-editar-categorias' data-id='".$row['id']."' data-original-title='Editar Categoría' data-placement='right'></span>  
									 <span class='icon-col blue fa fa-plus boton-agregar-subcategorias' data-id='".$row['id']."' data-toggle='modal' data-target='#modalsubcategorias' data-original-title='Agregar Categoría' data-placement='right'></span> 
									 </td>
									 <td class = 'text-left'>".$row['nombre']."</td>
									 <td class = 'text-left'>".ucwords($row['tipo'])."</td>
								</tr>";
			}
			
			//eliminar
			/*<span class='icon-col red fa fa-trash boton-eliminar-categorias' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Categoría' data-placement='right'></span>*/
			
			$resultado .= '</tbody></table>';
			
		} else {
			$resultado = 'No hay resultados que mostrar.';
		}
		
		echo $resultado;
	}
	
	function showsubcategorias(){
		global $mysqli;
		
		$idcategorias = (!empty($_REQUEST['idcategorias']) ? $_REQUEST['idcategorias'] : 0);
		
		$query 		 = " SELECT id,nombre 
						 FROM subcategorias 
						 WHERE idcategoria = '$idcategorias' ";
				   
		$result = $mysqli->query($query); 
		
		if ($result->num_rows > 0){
			$resultado = '<table id="tbsubcategorias"><thead><tr class="color_header"><th></th><th class="text-center">Acciones</th><th>Subcategorías</th></tr></thead>';
			$resultado .= '<tbody>';
				
			while($row = $result->fetch_assoc()){	 
				
				$resultado .= "<tr><td></td>
										<td>
										<span class='icon-col red fa fa-trash boton-eliminar-subcategorias' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Subcategoría' data-placement='right'></span>
										 <span class='icon-col blue fa fa-pencil boton-editar-subcategorias' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Editar Subcategoría' data-placement='right'></span> 
										 </td>
										 <td class = 'text-left'>".$row['nombre']."</td></tr>";
			}
			
			//eliminar
			/*<span class='icon-col red fa fa-trash boton-eliminar-subcategorias' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Subcategoría' data-placement='right'></span> */
			
			$resultado .= '</tbody></table>';
			
		} else {
			$resultado = 'No hay resultados que mostrar.';
		}
		
		echo $resultado;
	}
	
	function existeincidentescli(){
	    global $mysqli;
	    
	    $id = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0);
	    
	    $existe = array(
            'incidentes'    => 0,
            'proyectos'     => 0 
        );
        
        $qInc = " SELECT * FROM incidentes 
                  WHERE idclientes = $id ";
                  
        $r = $mysqli->query($qInc);
		if($r->num_rows > 0){ 
            $existe['incidentes'] = 1; 
        }
        
        $qPro = " SELECT * FROM clientes a 
                  INNER JOIN proyectos b 
                  ON a.id = b.idclientes 
                  WHERE a.id = $id ";
                  
        $r1 = $mysqli->query($qPro);
		if($r1->num_rows > 0){ 
            $existe['proyectos'] = 1; 
        }          
                  
	    echo json_encode($existe);
	}

?>