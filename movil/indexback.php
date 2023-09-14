<?php
    include_once("../conexion.php");
	
    if(!empty($_POST))
	{
		$usuario = $_REQUEST['txtUsuario'];
		$clave = $_REQUEST['txtClave'];		
		if ($usuario!="" && $clave!="") {
			$consulta = $mysqli->query("SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave='$clave' AND estado = 'Activo' ");			
			if ($registro=$consulta->fetch_assoc()) {
				$_SESSION['usuario']		= $registro['usuario'];
				//$_SESSION['clave']		= $registro['clave'];
				$_SESSION['user_id']		= $registro['id'];
				$_SESSION['nombreUsuario']	= $registro['nombre'];
				$_SESSION['nivel']			= $registro['nivel'];
				$_SESSION['unidad']			= $registro['sitio'];
				$_SESSION['sitio']			= $registro['sitio'];
				$_SESSION['idempresas']		= $registro['idempresas'];
				$_SESSION['idclientes']		= $registro['idclientes'];
				$_SESSION['idproyectos'] 	= $registro['idproyectos'];
				$_SESSION['iddepartamentos']= $registro['iddepartamentos'];
				$_SESSION['correousuario']	= $registro['correo'];
				//COOKIES
				setcookie("usuario", $_SESSION['usuario'], time() + 31536000);
				//setcookie("clave", $_SESSION['clave'], time() + 31536000);
				setcookie("user_id", $_SESSION['user_id'], time() + 31536000);
				setcookie("nombreUsuario", $_SESSION['nombreUsuario'], time() + 31536000);
				setcookie("nivel", $_SESSION['nivel'], time() + 31536000);
				setcookie("unidad", $_SESSION['unidad'], time() + 31536000);
				setcookie("sitio", $_SESSION['sitio'], time() + 31536000);
				setcookie("idempresas", $_SESSION['idempresas'], time() + 31536000);
				setcookie("idclientes", $_SESSION['idclientes'], time() + 31536000);
				setcookie("idproyectos", $_SESSION['idproyectos'], time() + 31536000);
				setcookie("iddepartamentos", $_SESSION['iddepartamentos'], time() + 31536000);
				setcookie("correousuario", $_SESSION['correousuario'], time() + 31536000);
				
				if($registro['nivel'] == 3 || $registro['nivel'] == 4){					
					$arrproy = explode(',',$registro['idproyectos']);
					if(in_array("1", $arrproy)){
						$inicio = 'dashboard.php';
					}else{
						$inicio = 'incidentes.php';
					}
				}else{
					$inicio = 'dashboard.php';
				}
				
				$consulta2 = $mysqli->query("Insert into bitacora values(0, '$usuario', now(), 'LOGIN', '-', '-', '-')");
				$salida = $registro['usuario'].'|'.$registro['nombre'].'|'.$registro['nivel'];
				echo json_encode(array(
                    'error' => false,
					'msg'   => $inicio,
					'userid' => $registro['id'],
					'nombreUsuario' => $registro['nombre'],
					'nivel'   => $registro['nivel']
                ));
                exit;
			} else {
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "Usuario o clave incorrecta!"
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