<?php
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
  header('content-type: application/json; charset=utf-8');
  header('Content-Type: application/JSON');
  
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Psr\Http\Message\ResponseInterface as Response;
  include("../../conexion.php");
  require '../vendor/autoload.php';
  
  $app = new \Slim\App;

	$app->post('/log', function (Request $request, Response $response, array $args) {
  //captura de parametros  
	$usuario=$request->getParam('email');
	$clave=$request->getParam('password');	
		
	$sentencia = $mysqli->prepare("SELECT * FROM usuarios WHERE usuario = ? AND clave = ? ");
	$sentencia->bind_param("ss", $usuario, $clave );
	$sentencia->execute();
	$resultado = $sentencia->get_result();
            
  if ($registro = $resultado->fetch_assoc()) {
		//mysqli_query($connect, "insert into bitacora values('','$usuario',now(),'Login')");
		    $update = " UPDATE
				session_data_historial a 
				INNER JOIN session_data b ON a.session_id = b.session_id
				SET a.usuario =  '".$usuario."', a.session_data = b.session_data, a.session_expire = b.session_expire, a.fechainicio = NOW()
				WHERE a.session_id = '".$_COOKIE['PHPSESSID']."' AND a.fechasesion = b.fechasesion ";
				$result = $mysqli->query($update);	
      //creando array asociativo
      $reponse="";
      //debug('login:'.$update);  
      $reponse['usuario']=$registro['usuario'];
      $reponse['clave']=$registro['clave'];
      $reponse['user_id']=$registro['id'];
      $reponse['nombreUsuari']=$registro['nombre'];
      $reponse['nivel']=$registro['nivel'];
 
      if($registro['sitio']!=""){
					$reponse['sitio']	= $registro['sitio'];
			}else{ 
					$reponse['sitio']	= 0;
			} 
			if($registro['idempresas']!=""){
					$reponse['idempresas']	= $registro['idempresas'];
			}else{
					$reponse['idempresas']	= 0;
			} 
			if($registro['idclientes']!=""){
					$reponse['idclientes']	= $registro['idclientes'];
			}else{
					$reponse['idclientes']	= 0;
			}
			if($registro['idproyectos'] != ""){
					$reponse['idproyectos'] = $registro['idproyectos'];
			}else{
					$reponse['idproyectos'] = 0;
			}
			if($registro['iddepartamentos']!= ""){
					$reponse['iddepartamentos']= $registro['iddepartamentos'];
			}else{
					$reponse['iddepartamentos']= 0;
			} 
				  $reponse['correousuario']=$registro['correo'];
          $consulta2 = $mysqli->query("Insert into bitacora values(0, '$usuario', now(), 'LOGIN', '-', '-', '-')");
				  $salida = $registro['usuario'].'|'.$registro['nombre'].'|'.$registro['nivel'];
          echo json_encode(array('error' => false));
          exit;

    echo json_encode($response);
  
  } else {
      
    $response['status'] = "fail";
		$response['cod'] = '004';
    /*--respuesta de retorno del servidor--*/
    echo json_encode($response);
  }
 });

  
  $app->run();
