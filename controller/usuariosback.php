<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "cargarusuarios": 
			  cargarusuarios();
			  break;
		case "guardarUsuario":
			  guardarUsuario();
			  break;
		case "editarusuarios":
			  editarusuarios();
			  break;
		case "eliminarusuarios":
			  eliminarusuarios();
			  break;
	    case "getusuarios":
			  getusuarios();
			  break;
		case "cambiarClave":
			  cambiarClave();
			  break;
		case "hayRelacion":
			  hayRelacion();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	

	function cargarusuarios() 
	{ 
		global $mysqli; 

		$nivel 		 = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		$idclientes  = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : '');
		$idproyectos = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : '');
		
		/*--CONFIG-DATATABLE--------------------------------------------------*/
		$vacio = array();
		$columns = (!empty($_REQUEST['columns']) ? $_REQUEST['columns'] : $vacio);
		$start = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$rowperpage = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		$where = "";
		$where2 = array();
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		//contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables
						  
								 
																																		  
																																  
																																												
																																
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);
		/*--------------------------------------------------------------------*/
		
        $query = " SELECT a.id, a.usuario, a.nombre, a.correo, a.telefono, a.cargo, f.nombre as nivel, a.estado, 
				   '' AS idambientes,
                   LEFT(GROUP_CONCAT( DISTINCT b.descripcion SEPARATOR  ', ' ),45) AS idempresas,
				   LEFT(GROUP_CONCAT( DISTINCT c.nombre SEPARATOR  ', ' ),45) AS idclientes, 
				   LEFT(GROUP_CONCAT( DISTINCT d.nombre SEPARATOR  ', ' ),45) AS idproyectos,
				   LEFT(GROUP_CONCAT( DISTINCT e.nombre SEPARATOR  ', ' ),45) AS iddepartamentos,
                   LEFT(GROUP_CONCAT( DISTINCT ee.nombre SEPARATOR  ', ' ),45) AS idgrupos
				   FROM usuarios a
				   LEFT JOIN empresas b ON FIND_IN_SET(b.id, a.idempresas)
                   LEFT JOIN clientes c  ON FIND_IN_SET(c.id, a.idclientes)
                   LEFT JOIN proyectos d  ON FIND_IN_SET(d.id, a.idproyectos)
                   LEFT JOIN departamentos e ON FIND_IN_SET(e.id, a.iddepartamentos) AND e.tipo = 'departamento'
                   LEFT JOIN departamentos ee ON FIND_IN_SET(ee.id, a.iddepartamentos) AND ee.tipo = 'grupo'  
                   INNER JOIN niveles f  ON a.nivel = f.id                   
                   ";
		if($nivel == 4 || $nivel == 5 || $nivel == 7){ 
			if($idclientes != ''){
				$arr = strpos($idclientes, ',');
				if ($arr !== false) {
					$query  .= " AND a.idclientes IN (".$idclientes.") ";
				}else{
					$query  .= " AND find_in_set(".$idclientes.",a.idclientes) ";
				}  
				//$query .= " AND a.nivel = ".$nivel."";
			}
			if($idproyectos != ''){
				$arr = strpos($idproyectos, ',');
				if ($arr !== false) {
					$query  .= " AND a.idproyectos IN (".$idproyectos.") ";
				}else{
					$query  .= " AND find_in_set(".$idproyectos.",a.idproyectos) ";
				}
				//$query .= " AND a.nivel = ".$nivel."";				
			}
			$query .= " AND a.nivel NOT IN (1,2) AND a.correo NOT LIKE '%maxialatam.com%' ";
		}
		
		$hayFiltros = 0;
		$where2 = array();
		for($i=0 ; $i<count($columns);$i++){
			$column = $_REQUEST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			if ($_REQUEST['columns'][$i]['search']['value']!="") {

                
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);
				
				if ($column == 'usuario'){
					$column = 'a.usuario';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'nombre'){
					$column = 'a.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'correo'){
					$column = 'a.correo';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'telefono'){
					$column = 'a.telefono';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'cargo'){
					$column = 'a.cargo';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'nivel'){
					$column = 'f.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'estado'){
					$column = 'a.estado';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'idempresas'){
					$column = 'b.descripcion';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'idclientes'){
					$column = 'c.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'idproyectos'){
					$column = 'd.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'iddepartamentos'){
					$column = 'e.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'idgrupos'){
					$column = 'ee.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				} 				

				$hayFiltros++;
			}
		}		
		if ($hayFiltros > 0)
			$where = " AND ".implode(" AND " , $where2)." ";// id like '%searchValue%' or name like '%searchValue%'
		else
			$where = "";
		$query .= " $where ";
		

		$query .= " GROUP BY a.id ";
		
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		//$query  .= " ORDER BY a.id desc";
		$query  .= " ORDER BY a.id DESC  LIMIT ".$start.",".$rowperpage;
	    //debugL($query,"USUARIOS");
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		while($row = $result->fetch_assoc()){


		$acciones = '<td>
					<div class="dropdown ml-auto text-center">
						<div class="btn-link" data-toggle="dropdown">
							<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
						</div>
					<div class="dropdown-menu dropdown-menu-center droptable">
			        ';


		$btnVer = '<a class="dropdown-item text-warning" href="usuario.php?id='.$row['id'].'&type=view"><i class="fas fa-eye mr-2"></i>Ver</a>';

		$btnEditar = '<a class="dropdown-item text-info" href="usuario.php?id='.$row['id'].'&type=edit"><i class="fas fa-pen mr-2"></i>Editar</a>';
		
		$btnEliminar='<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>';
        /*if($nivel==4 || $nivel==7){
			$acciones.=$btnVer;
		}else{
			$acciones.=$btnEditar;
			$acciones.=$btnEliminar;
		}*/

		$acciones.=$btnEditar;
		$acciones.=$btnEliminar;

		$resultado[] = array(
				'id' 					=>	$row['id'],
				'acciones'				=>	$acciones, 	
				'usuario'				=>	$row['usuario'], 
				'nombre' 				=>	$row['nombre'], 
				'correo' 				=>	$row['correo'], 
				'telefono' 				=>	$row['telefono'], 
				'cargo' 				=>	$row['cargo'], 
				'nivel' 				=>	$row['nivel'],
				'estado' 				=>	$row['estado'],
				'idempresas' 			=>	$row['idempresas'], 
				'idclientes' 			=>	$row['idclientes'], 
				'idproyectos' 			=>	$row['idproyectos'], 
				'iddepartamentos' 		=>	$row['iddepartamentos'],
				'idgrupos' 				=>	$row['idgrupos'] 				
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
	
	function guardarUsuario() 
	{
		global $mysqli;
		$usuario 			= (!empty($_REQUEST['usuario']) ? $_REQUEST['usuario'] : '');
		$clave 				= (!empty($_REQUEST['clave']) ? $_REQUEST['clave'] : '');
		$nombre    			= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$correo    		 	= (!empty($_REQUEST['correo']) ? $_REQUEST['correo'] : '');
		$telefono  		 	= (!empty($_REQUEST['telefono']) ? $_REQUEST['telefono'] : '');
		$cargo     		 	= (!empty($_REQUEST['cargo']) ? $_REQUEST['cargo'] : '');
		$idambientes      	= (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : '');
		$nivel      		= (!empty($_REQUEST['nivel']) ? $_REQUEST['nivel'] : '');
		$estado      		= (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : 'Activo');
		$idempresas 		= (!empty($_REQUEST['idempresas']) ? $_REQUEST['idempresas'] : '0');
		$idclientes 		= (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '0');
		$idproyectos 		= (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '0');
		$iddepartamentos 	= (!empty($_REQUEST['iddepartamentos']) ? $_REQUEST['iddepartamentos'] : '0');
		$idgrupos 			= (!empty($_REQUEST['idgrupos']) ? $_REQUEST['idgrupos'] : '');
		$idproveedor 		= (!empty($_REQUEST['idproveedor']) ? $_REQUEST['idproveedor'] : '');
		$iddepgrup 			=  $iddepartamentos.','.$idgrupos;
		
		//$hashed_pass = hash('sha256', (get_magic_quotes_gpc() ? stripslashes($clave) : $clave));
		
		$comc = "SELECT usuario FROM usuarios where usuario = '$usuario'";
		$resultc = $mysqli->query($comc);
		$totc = $resultc->num_rows;
		
		$comn = "SELECT correo FROM usuarios where correo = '$correo'";
		$resultn = $mysqli->query($comn);		
		$totn = $resultn->num_rows;
		
		if($totc > 0 && $totn > 0){
			echo 4;			
		}elseif($totc > 0 || $totn > 0){
			if ($totc>0){
				echo 2;
			}
			if($totn>0){
				echo 3;
			}
		}elseif($totc <= 0 && $totn <= 0){
			$correo = trim($correo);
			$query = "	INSERT INTO usuarios 			(usuario,clave,nombre,correo,telefono,cargo,idambientes,nivel,estado,idempresas,idclientes,idproyectos,iddepartamentos,idproveedor) 
						VALUES('$usuario', '$clave', '$nombre', '$correo', '$telefono', '$cargo', '$idambientes', '$nivel','$estado','$idempresas','$idclientes','$idproyectos','$iddepgrup','$idproveedor')";
			//debug($query);
			$result = $mysqli->query($query);		
			if($result==true){		    
				$idusuario = $mysqli->insert_id;		   
				$querynotificacion 		= "SELECT * from notificacionesxniveles where idnivel = '$nivel'";
				$resultnotificacion 	= $mysqli->query($querynotificacion);
				$rowresultados = $resultnotificacion ->fetch_assoc();
				$noti1	=	$rowresultados['noti1'];
				$noti2	=	$rowresultados['noti2'];
				$noti3	=	$rowresultados['noti3'];
				$noti4	=	$rowresultados['noti4'];
				$noti5	=	$rowresultados['noti5'];
				$noti6	=	$rowresultados['noti6'];
				$noti7	=	$rowresultados['noti7'];
				$noti8	=	$rowresultados['noti8'];
				$noti9	=	$rowresultados['noti9'];
				$noti10	=	$rowresultados['noti10'];
				$noti11	=	$rowresultados['noti11'];
				$noti12	=	$rowresultados['noti12'];
				$noti13	=	$rowresultados['noti13']; 
				$query = " INSERT INTO notificacionesxusuarios VALUES (null,'$idusuario','$noti1','$noti2','$noti3','$noti4','$noti5','$noti6','$noti7','$noti8','$noti9','$noti10','$noti11','$noti12','$noti13',0) ";
				$result = $mysqli->query($query);
				bitacora($_SESSION['usuario'], "Usuarios", "El usuario #".$idusuario." ha sido creado", $idusuario, $query);			
				echo 1;
			}else{
				echo 0;
			}
		} 		
	}
	
	function editarusuarios() 
	{
		global $mysqli;		
		$idusuarios			=  $_REQUEST['idusuarios'];
		$usuario 			= (!empty($_REQUEST['usuario']) ? $_REQUEST['usuario'] : '');
		$clave 				= (!empty($_REQUEST['clave']) ? $_REQUEST['clave'] : '');
		$nombre    			= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$correo    		 	= (!empty($_REQUEST['correo']) ? $_REQUEST['correo'] : '');
		$telefono  		 	= (!empty($_REQUEST['telefono']) ? $_REQUEST['telefono'] : '');
		$cargo     		 	= (!empty($_REQUEST['cargo']) ? $_REQUEST['cargo'] : '');
		$idambientes      	= (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : '');
		$nivel      		= (!empty($_REQUEST['nivel']) ? $_REQUEST['nivel'] : '');
		$estado      		= (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : 'Activo');
		$idempresas 		= (!empty($_REQUEST['idempresas']) ? $_REQUEST['idempresas'] : '0');
		$idclientes 		= (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '0');
		$idproyectos 		= (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '0');
		$iddepartamentos 	= (!empty($_REQUEST['iddepartamentos']) ? $_REQUEST['iddepartamentos'] : '0');
		$idgrupos 			= (!empty($_REQUEST['idgrupos']) ? $_REQUEST['idgrupos'] : '');
		$idproveedor 		= (!empty($_REQUEST['idproveedor']) ? $_REQUEST['idproveedor'] : '');
		$iddepgrup 			=  '';
		//$hashed_pass = hash('sha256', (get_magic_quotes_gpc() ? stripslashes($clave) : $clave));
		
		if($iddepartamentos != ''){
			$iddepgrup 		=  $iddepartamentos;
			$exitdep 		=  1;
		}
		if($idgrupos != ''){
			if($exitdep == 1){
				$iddepgrup 	.= ',';
			}
			$iddepgrup 		.=  $idgrupos;
		}
		
		$queryS = "SELECT idambientes FROM usuarios WHERE id = $idusuarios ";
		$resultS = $mysqli->query($queryS);
		if($resultS->num_rows >0){
			$rowS = $resultS->fetch_assoc();				
			$idambientesant = $rowS['idambientes'];
		}
		
		$comc = "SELECT usuario FROM usuarios where usuario = '$usuario' and id != '$idusuarios'";
		$resultc = $mysqli->query($comc);
		
		$comn = "SELECT correo FROM usuarios where correo = '$correo' and id != '$idusuarios' AND correo != '' ";
		$resultn = $mysqli->query($comn);
		

		$totc = $resultc->num_rows;
		$totn = $resultn->num_rows;
		
		if($totc > 0 && $totn > 0){
			echo 4;			
		}elseif($totc > 0 || $totn > 0){
			if ($totc>0){
				echo 2;
			}
			if($totn>0){
				echo 3;
			}
		}elseif($totc <= 0 && $totn <= 0){
			$correo = trim($correo);
			$query = "  UPDATE usuarios SET usuario = '$usuario', clave = '$clave', nombre = '$nombre',  
						correo='$correo', telefono='$telefono', cargo='$cargo', idambientes='$idambientes', 
						nivel='$nivel', estado='$estado', idempresas='$idempresas', idclientes='$idclientes',
						idproyectos='$idproyectos', iddepartamentos='$iddepgrup', idproveedor='$idproveedor' 
						WHERE id = '$idusuarios'";
			//debug($query);
			$result = $mysqli->query($query);
			if($result==true){		   
				bitacora($_SESSION['usuario'], "Usuarios", "El usuario #".$idusuarios." ha sido editado", $idusuarios , $query);			
				echo 1;		    
			}else{
				echo 0;
			} 
		}     	
	}
	
	function eliminarusuarios() 
	{
		global $mysqli;
		
		$id = $_REQUEST['id'];
		
		$query = "DELETE FROM usuarios WHERE id = '$id'";
		
		$result = $mysqli->query($query);

        if($result==true){		    
		    bitacora($_SESSION['usuario'], "Usuarios", "El usuario #".$id." ha sido eliminado", $id , $query);
			echo 1;		    
		}else{
			echo 0;
		}	
	}
	
	function cambiarClave() 
	{
		global $mysqli;		
		$id 	= $_SESSION['user_id'];
		$clave  = $_REQUEST['clave'];
		$hashed_pass = hash('sha256', (get_magic_quotes_gpc() ? stripslashes($clave) : $clave));
			
		$query = "UPDATE usuarios SET clave = '$hashed_pass' WHERE id = '$id'";		
		$result = $mysqli->query($query);
		
		if($result==true){		    
		    bitacora($_SESSION['usuario'], "Usuarios", "Se ha cambiado la clave del usuario #".$id."", $id , $query);
			echo 1;
		}else{
			echo 0;
		}		
	}
	
	function getusuarios(){
		global $mysqli;
		
		$idusuarios = $_REQUEST['idusuarios'];
		$query 	= "	SELECT a.*, GROUP_CONCAT( DISTINCT e.id SEPARATOR  ',' ) AS iddepartamentos,
					GROUP_CONCAT( DISTINCT ee.id SEPARATOR  ',' ) AS idgrupos 
					FROM usuarios a 
					LEFT JOIN departamentos e ON FIND_IN_SET(e.id, a.iddepartamentos) AND e.tipo = 'departamento'
                    LEFT JOIN departamentos ee ON FIND_IN_SET(ee.id, a.iddepartamentos) AND ee.tipo = 'grupo'
					WHERE a.id = '$idusuarios'
				";
		//debug($query);
		$result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){			
			$resultado = array(  
				'usuario'			=>	$row['usuario'], 
				'clave'				=>	$row['clave'], 
				'nombre' 			=>	$row['nombre'], 
				'correo' 			=>	$row['correo'], 
				'telefono' 			=>	$row['telefono'], 
				'cargo' 			=>	$row['cargo'], 
				'idambientes' 		=>	$row['idambientes'],
				'nivel' 			=>	$row['nivel'],
				'estado' 			=>	$row['estado'],
				'idempresas' 		=>	$row['idempresas'], 
				'idclientes' 		=>	$row['idclientes'], 
				'idproyectos' 		=>	$row['idproyectos'], 
				'iddepartamentos' 	=>	$row['iddepartamentos'],
				'idgrupos' 			=>	$row['idgrupos'],				
				'idproveedor' 		=>	$row['idproveedor']				
			);
		}
		
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}	




	function hayRelacion(){
	    global $mysqli;
	    
	    $id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
	    $correo ="";
	    $existe_correctivo	= 0;
	    $existe_preventivo	= 0;

	    $existe_laboratorio = 0;

	    $existe_postventa = 0;
	    $existe_activo = 0;

	    $buscarUsuario = "SELECT usuarios.correo FROM usuarios WHERE usuarios.id = $id";
//	    echo $buscarUsuario;
		$resultn = $mysqli->query($buscarUsuario);		
		if($row = $resultn->fetch_assoc()){
//				echo var_dump($row);
				$correo =  $row['correo'];
			    $qact = "SELECT 
							activos.id
						FROM activos 
						WHERE activos.idresponsables = '$id' LIMIT 1;";

		        $rQAct = $mysqli->query($qact);
				if($rQAct->num_rows > 0){ 
		            $existe_activo = 1; 
		        }



			    $qcorr = "SELECT incidentes.id from incidentes 
							WHERE (
									incidentes.solicitante = '$correo' OR 
								    incidentes.creadopor = '$correo' OR
								    incidentes.asignadoa ='$correo' )
									AND tipo = 'incidentes'
							LIMIT 1;";
//				echo $qcorr;
		        $rQcorr = $mysqli->query($qcorr);
				if($rQcorr->num_rows > 0){ 
		            $existe_correctivo = 1; 
		        }
		        


			    $qprev = "SELECT incidentes.id from incidentes 
								WHERE (
										incidentes.solicitante = '$correo' OR 
									    incidentes.creadopor = '$correo' OR
									    incidentes.asignadoa ='$correo' )
										
										AND tipo = 'preventivos'
								LIMIT 1;";

//				echo $qprev;
		        $rQprev = $mysqli->query($qprev);
				if($rQprev->num_rows > 0){ 
		            $existe_preventivo = 1; 
		        }


			    $qlab = "SELECT laboratorio.id from laboratorio 
							WHERE 
								laboratorio.solicitante = '$correo' OR 
							    laboratorio.creadopor = '$correo' OR
							    laboratorio.asignadoa ='$correo'
							LIMIT 1;";
//				echo $qlab;
		        $rQlab = $mysqli->query($qlab);
				if($rQlab->num_rows > 0){ 
		            $existe_laboratorio = 1; 
		        }



			    $qpost= "SELECT postventas.id from postventas 
							WHERE 
								postventas.solicitante = '$correo' OR 
							    postventas.creadopor = '$correo' OR
							    postventas.asignadoa ='$correo'
							LIMIT 1;";

//				echo $qpost;
		        $rQpost = $mysqli->query($qpost);
				if($rQpost->num_rows > 0){ 
		            $existe_postventa = 1; 
		        }

				if(

					($existe_activo  == 1) ||
					($existe_correctivo  == 1) ||
					($existe_preventivo  == 1) ||
					($existe_laboratorio == 1) ||
					($existe_postventa 	 == 1)
				){
					echo 1;
				}else{
					echo 0;
				}





		}else{
			echo "-1";
		}
	}

	
	
?>