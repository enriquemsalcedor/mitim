<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){
		case "cargarempresas": 
			  cargarempresas();
			  break;
		case "getempresas": 
			  getempresas();
			  break;
		case "createempresas": 
			  createempresas();
			  break;
		case "updateempresas": 
			  updateempresas();
			  break;
		case "deleteempresas": 
			  deleteempresas();
			  break;
		case "existeempresa":
			  existeempresa();
			  break;
		case "showclientes":
			  showclientes();
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
		default:
			  echo "{failure:true}";
			  break;
	}	
	
	function cargarempresas(){
		global $mysqli;
		$query = " SELECT id,ruc,descripcion,telef_princ,telef_otro,correo,direccion 
				   FROM empresas ";
		$result = $mysqli->query($query); 
		$resultado = '';
		while($row = $result->fetch_assoc()){			
			$resultado['data'][] = array(
				'id' 						=>	$row['id'],
				'acciones' 					=>	"<div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>
													<span class='icon-col blue fa fa-eye boton-ver-clientes' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Ver Clientes' data-placement='right'></span>
													<span class='icon-col blue fa fa-pencil boton-editar-empresas' data-id='".$row['id']."'  data-original-title='Editar Empresa' data-placement='right'></span>
													<span class='icon-col red fa fa-remove boton-eliminar-empresas' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Empresa' data-placement='right'></span>
													<span class='icon-col blue fa fa-plus boton-agregar-clientes' data-id='".$row['id']."' data-toggle='modal' data-target='#modaladdclientes' data-original-title='Agregar Cliente' data-placement='right'></span>
												</div>", 
				'ruc'			 			=>	$row['ruc'],
				'descripcion'	 			=>	$row['descripcion'],
				'telef_princ' 				=>	$row['telef_princ'],
				'telef_otro'	 			=>	$row['telef_otro'],
				'correo'	 				=>	$row['correo'],
				'direccion'	 				=>	$row['direccion']
			);
		}
		
		echo json_encode($resultado);
	}	
	
	
	function getempresas(){
		global $mysqli;
		
		$idempresas 	= $_REQUEST['idempresas'];
		$query 	= "	SELECT *
					FROM empresas
					WHERE id = '$idempresas'";
		$result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			
			$resultado = array(  
				'ruc'			 			=>	$row['ruc'],
				'descripcion'	 			=>	$row['descripcion'],
				'telef_princ' 				=>	$row['telef_princ'],
				'telef_otro'	 			=>	$row['telef_otro'],
				'correo'	 				=>	$row['correo'],
				'direccion'	 				=>	$row['direccion']		
			);
		}
		
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}	
	
	
	function deleteempresas(){
		global $mysqli;
		
		$idempresas	=  $_REQUEST['idempresas'];
		$query 		=  " DELETE FROM empresas 
						 WHERE id = '$idempresas' ";
		$result 	=  $mysqli->query($query);	
		
		if($result == true){
			echo 1;
		}else{
			echo 0;
		}
		
	}
	
	function updateempresas(){
		global $mysqli;
		
		$idempresas 		= $_REQUEST['idempresas'];  
		$ruc				= (!empty($_REQUEST['ruc']) ? $_REQUEST['ruc'] : '');
		$descripcion		= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$telef_princ		= (!empty($_REQUEST['tlf']) ? $_REQUEST['tlf'] : '');
		$telef_otro			= (!empty($_REQUEST['tlf_otro']) ? $_REQUEST['tlf_otro'] : ''); 
		$correo				= (!empty($_REQUEST['correo']) ? $_REQUEST['correo'] : '');
		$direccion			= (!empty($_REQUEST['direccion']) ? $_REQUEST['direccion'] : '');
		
		$query 	= "	UPDATE empresas SET ruc = '$ruc', descripcion = '$descripcion', telef_princ = '$telef_princ' ,telef_otro = '$telef_otro', correo = '$correo', direccion = '$direccion' 
					WHERE id = '$idempresas'";
		$result = $mysqli->query($query);	
		
		if($result == true){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function createempresas(){
		global $mysqli; 
		
		$ruc				= (!empty($_REQUEST['ruc']) ? $_REQUEST['ruc'] : '');
		$descripcion		= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$telef_princ		= (!empty($_REQUEST['tlf']) ? $_REQUEST['tlf'] : '');
		$telef_otro			= (!empty($_REQUEST['otro_tlf']) ? $_REQUEST['otro_tlf'] : ''); 
		$correo				= (!empty($_REQUEST['correo']) ? $_REQUEST['correo'] : '');
		$direccion			= (!empty($_REQUEST['direccion']) ? $_REQUEST['direccion'] : '');
		
		$query 	= "	INSERT INTO	empresas (ruc,descripcion,telef_princ,telef_otro,correo,direccion)
					VALUES ('$ruc','$descripcion','$telef_princ','$telef_otro','$correo','$direccion')";
		$result = $mysqli->query($query);
		$idcontacto = $mysqli->insert_id;
		
		if($result==true){
			echo $param;
		}else{
			echo 0;
		}
	}
	
	function existeempresa(){
		global $mysqli;
		$ruc = $_REQUEST['ruc'];
		$count = 0;
		$query = "SELECT ruc FROM empresas WHERE id = '$id'";
		$result = $mysqli->query($query);
		$count = $result->num_rows;
		echo $count;
	} 
	
	function showclientes(){
		global $mysqli;
		
		$idempresas	= $_REQUEST['id'];
		
		$query 		= " SELECT id,nombre,siglas,direccion,telefono,contacto 
						FROM clientes
						WHERE idempresas = '$idempresas' ";
				   
		$result = $mysqli->query($query); 
		
		if ($result->num_rows > 0){
			$resultado = '<table id="tbclientes"><thead><tr class="color_header"><th></th><th class="text-center">Acciones</th><th>Clientes</th></tr></thead>';
			$resultado .= '<tbody>';
				
			while($row = $result->fetch_assoc()){	 
				
				$resultado .= "<tr><td></td>
										<td id='tr-".$row['id']."'><span class='icon-col blue fa fa-eye boton-ver-proyectos' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Ver Proyectos' data-placement='right'></span> 
										 <span class='icon-col blue fa fa-pencil boton-editar-clientes' data-id='".$row['id']."' id='editclientes' data-original-title='Editar Clientes' data-placement='right'></span> 
										 <span class='icon-col red fa fa-remove boton-eliminar-clientes' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Clientes' data-placement='right'></span> 
										 <span class='icon-col blue fa fa-plus boton-agregar-proyectos' data-id='".$row['id']."' data-toggle='modal' data-target='#modaladdproyectos' data-original-title='Agregar Proyectos' data-placement='right'></span> 
										 </td>
										<td class = 'text-left'>".$row['nombre']."</td></tr>";
			}
			
			$resultado .= '</tbody></table>';
			
		} else {
			$resultado = 'No hay resultados que mostrar.';
		}
		
		echo $resultado;
	}
	
	function showproyectos(){
		global $mysqli;
		
		$idclientes	= $_REQUEST['idclientes'];
		
		$query 		= " SELECT id,codigo,nombre,correlativo
						FROM proyectos
						WHERE idclientes = '$idclientes' ";
				   
		$result = $mysqli->query($query); 
		
		if ($result->num_rows > 0){
			$resultado = '<table id="tbproyectos"><thead><tr class="color_header"><th></th><th class="text-center">Acciones</th><th>Proyectos</th></tr></thead>';
			$resultado .= '<tbody>';
				
			while($row = $result->fetch_assoc()){	 
				
				$resultado .= "<tr><td></td>
										<td><span class='icon-col blue fa fa-eye boton-ver-categorias' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Ver Categorías' data-placement='right'></span> 
										 <span class='icon-col blue fa fa-pencil boton-editar-proyectos' data-id='".$row['id']."' data-original-title='Editar Proyecto' data-placement='right'></span> 
										 <span class='icon-col red fa fa-remove boton-eliminar-proyectos' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Proyecto' data-placement='right'></span> 
										 <span class='icon-col blue fa fa-plus boton-agregar-categorias' data-id='".$row['id']."' data-toggle='modal' data-target='#modaladdcategorias' data-original-title='Agregar Categoría' data-placement='right'></span> 
										 </td>
										<td class = 'text-left'>".$row['nombre']."</td></tr>";
			}
			
			$resultado .= '</tbody></table>';
			
		} else {
			$resultado = 'No hay resultados que mostrar.';
		}
		
		echo $resultado;
	}
	
	function showcategorias(){
		global $mysqli;
		
		$idproyectos = $_REQUEST['idproyectos'];
		
		$query 		 = " SELECT id,nombre 
						 FROM categorias 
						 WHERE idproyecto = '$idproyectos' ";
				   
		$result = $mysqli->query($query); 
		
		if ($result->num_rows > 0){
			$resultado = '<table id="tbcategorias"><thead><tr class="color_header"><th></th><th class="text-center">Acciones</th><th>Categorías</th></tr></thead>';
			$resultado .= '<tbody>';
				
			while($row = $result->fetch_assoc()){	 
				
				$resultado .= "<tr><td></td>
										<td><span class='icon-col blue fa fa-eye boton-ver-subcategorias' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Ver Subcategorías' data-placement='right'></span> 
										 <span class='icon-col blue fa fa-pencil boton-editar-categorias' data-id='".$row['id']."' data-original-title='Editar Categoría' data-placement='right'></span> 
										 <span class='icon-col red fa fa-remove boton-eliminar-categorias' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Categoría' data-placement='right'></span> 
										 <span class='icon-col blue fa fa-plus boton-agregar-subcategorias' data-id='".$row['id']."' data-toggle='modal' data-target='#modaladdsubcategorias' data-original-title='Agregar Categoría' data-placement='right'></span> 
										 </td><td class = 'text-left'>".$row['nombre']."</td></tr>";
			}
			
			$resultado .= '</tbody></table>';
			
		} else {
			$resultado = 'No hay resultados que mostrar.';
		}
		
		echo $resultado;
	}
	
	function showsubcategorias(){
		global $mysqli;
		
		$idcategorias = $_REQUEST['idcategorias'];
		
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
										 <span class='icon-col blue fa fa-pencil boton-editar-subcategorias' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Editar Subcategoría' data-placement='right'></span> 
										 <span class='icon-col red fa fa-remove boton-eliminar-subcategorias' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Subcategoría' data-placement='right'></span> 
										 </td>
										 <td class = 'text-left'>".$row['nombre']."</td></tr>";
			}
			
			$resultado .= '</tbody></table>';
			
		} else {
			$resultado = 'No hay resultados que mostrar.';
		}
		
		echo $resultado;
	}

?>