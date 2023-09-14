<?php
    include_once("conexion.php");
	
    if(!empty($_POST))
	{
		$usuario = $_REQUEST['txtUsuario'];
		$clave = $_REQUEST['txtClave'];
		// Hash password
        //$hashed_pass = hash('sha256', (get_magic_quotes_gpc() ? stripslashes($clave) : $clave));
        
		$sistema = $_REQUEST['sistema'];		
		if ($usuario!="" && $clave!="") {
			$sentencia = $mysqli->prepare("SELECT * FROM usuarios WHERE usuario = ? AND clave = ? AND estado = 'Activo' ");
			$sentencia->bind_param("ss", $usuario, $clave );
			$consulta = "SELECT * FROM usuarios WHERE usuario = ? AND clave = ? AND estado = 'Activo' ";
			debugL($consulta,'cons:');
			$sentencia->execute();
			$resultado = $sentencia->get_result();
			if ($registro = $resultado->fetch_assoc()) {
				$update = " UPDATE
							session_data_historial a 
							INNER JOIN session_data b ON a.session_id = b.session_id
							SET a.usuario =  '".$usuario."', a.session_data = b.session_data, a.session_expire = b.session_expire, a.fechainicio = NOW()
							WHERE a.session_id = '".$_COOKIE['PHPSESSID']."' AND a.fechasesion = b.fechasesion ";
							
				$result = $mysqli->query($update);
	
				$_SESSION['usuario']		= $registro['usuario'];
				//$_SESSION['clave']		= $registro['clave'];
				$_SESSION['user_id']		= $registro['id'];
				$_SESSION['idusuario']		= $registro['id'];
				$_SESSION['nombreUsuario']	= $registro['nombre'];
				$_SESSION['nivel']			= $registro['nivel'];
				$_SESSION['incidente']		= 0;
				if($registro['idambientes']!=""){
					$_SESSION['idambientes']	= $registro['idambientes'];
				}else{
					$_SESSION['idambientes']	= 0;
				}
				if($registro['idempresas']!=""){
					$_SESSION['idempresas']	= $registro['idempresas'];
				}else{
					$_SESSION['idempresas']	= 0;
				}
				if($registro['idclientes']!=""){
					$_SESSION['idclientes']	= $registro['idclientes'];
				}else{
					$_SESSION['idclientes']	= 0;
				}
				if($registro['idproyectos'] != ""){
					$_SESSION['idproyectos'] = $registro['idproyectos'];
				}else{
					$_SESSION['idproyectos'] = 0;
				}
				if($registro['iddepartamentos']!= ""){
					$_SESSION['iddepartamentos']= $registro['iddepartamentos'];
				}else{
					$_SESSION['iddepartamentos']= 0;
				}
				
				$_SESSION['correousuario']	= $registro['correo']; 
				//COOKIES
				setcookie("usuario", $_SESSION['usuario'], time() + 31536000);
				//setcookie("clave", $_SESSION['clave'], time() + 31536000);
				setcookie("user_id", $_SESSION['user_id'], time() + 31536000);
				setcookie("nombreUsuario", $_SESSION['nombreUsuario'], time() + 31536000);
				setcookie("nivel", $_SESSION['nivel'], time() + 31536000);
				setcookie("unidad", $_SESSION['idambientes'], time() + 31536000);
				setcookie("sitio", $_SESSION['idambientes'], time() + 31536000);
				setcookie("idempresas", $_SESSION['idempresas'], time() + 31536000);
				setcookie("idclientes", $_SESSION['idclientes'], time() + 31536000);
				setcookie("idproyectos", $_SESSION['idproyectos'], time() + 31536000);
				setcookie("iddepartamentos", $_SESSION['iddepartamentos'], time() + 31536000);
				setcookie("correousuario", $_SESSION['correousuario'], time() + 31536000);
				setcookie("sistema", $sistema, time() + 31536000);
				
				if($registro['nivel'] == 3 || $registro['nivel'] == 4){					
					$arrproy = explode(',',$registro['idproyectos']);
					if(in_array("1", $arrproy)){
						$inicio = 'dashboard.php';
					}else{
						$inicio = 'dashboard.php';
					}
				}else{
					if($registro['nivel'] != 7){
						$inicio = 'dashboard.php';
					}else{
						$inicio = 'correctivos.php';
					} 
				}
				
				$consulta2 = $mysqli->query("Insert into bitacora values(0, '$usuario', now(), 'LOGIN', '-', '-', '-')");
				$salida = $registro['usuario'].'|'.$registro['nombre'].'|'.$registro['nivel'];
				echo json_encode(array(
                    'error' => false,
					'msg'   => $inicio
                ));
                exit;
			} else {
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "<div class='alert alert-danger'>Usuario o clave incorrecta!</div>"
                ));
                exit;
			}
		} else {
			echo json_encode(array(
                'error' => true,
                'msg'   => "<div class='alert alert-danger'>Debe llenar todos los campos!</div>"
            ));
            exit;
		}
	}else{
		echo "VACIOS";
	}
?>