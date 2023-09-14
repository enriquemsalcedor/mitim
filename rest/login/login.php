<?php
	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	header('content-type: application/json; charset=utf-8');
	header('Content-Type: application/JSON');
	header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");
	//capurando el tipo de metodo                
	include("../../conexion.php");

	$method = $_SERVER['REQUEST_METHOD'];
	
	if ($method =='GET') {		
		$usuario = $_REQUEST['email'];
		$clave = $_REQUEST['password'];
	  
		$sentencia = $mysqli->prepare("SELECT * FROM usuarios WHERE usuario = ? AND clave = ? ");
		$sentencia->bind_param("ss", $usuario, $clave );
		$sentencia->execute();
		$resultado = $sentencia->get_result();
				
		if($registro = $resultado->fetch_assoc()) {
			//$response['cod'] = true;
			$response = array();
			$response['nombre'] 	= (!empty($registro['nombre']) ? $registro['nombre'] : '0');
			$response['idusuario'] 	= (!empty($registro['id']) ? $registro['id'] : '0');
			$response['usuario']= (!empty($registro['usuario']) ? $registro['usuario'] : '');
			$response['nivel']= (!empty($registro['nivel']) ? $registro['nivel'] : '0');
            $response['correousuario']= (!empty($registro['correo']) ? $registro['correo'] : '');
        	echo json_encode($response);  
		}else{      
			$response['cod'] = true;
			$response['msg'] = 'usuario o clave incorrecta';
			echo json_encode($response);
		}
	}
?>