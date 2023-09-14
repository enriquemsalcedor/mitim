<?php
    include_once("conexion.php");
    $method = $_SERVER['REQUEST_METHOD'];        
    $action=(isset($_REQUEST['op']) ? $_REQUEST['op']: $data['data']['op']);
    
    if ($method =='GET'){
        $action = $_REQUEST['op'];   
        switch($action){ 
            case 'get':
                get();
                break;
            default:
                echo "{failure-GET:true}";
                break;
        }
    }elseif($method =='POST') {
        switch($action){ 
    	case "dologin":
    		dologin();
    		break;
    	default:
            echo "{failure-POST:true}";
            break;
        } 
	}
	
	function cifrarClave($clave){
        $pass = hash('sha256', (get_magic_quotes_gpc() ? stripslashes($clave) : $clave));
        return $pass;
    }
	
	function formulario(){

        $data['usuario'] = (!empty($_REQUEST['usuario']) ? $_REQUEST['usuario'] : '');
        $data['clave']   = (!empty($_REQUEST['clave']) ? $_REQUEST['clave'] : '');
    	$data['fecha']   = date('Y/m/d');
	    return $data;
    }
	 
	function dologin(){
	    global $mysqli;
        $data=formulario();
	
		/*$sentencia = $mysqli->prepare("SELECT * FROM usuarios WHERE usuario = ? AND clave = ? ");
		$sentencia->bind_param("ss","maxia","adminmt");
		$sentencia->execute();
		$resultado = $sentencia->get_result();*/
	    
	    $query = "SELECT * FROM usuarios WHERE usuario ='".$data['usuario']."' AND clave = '".$data['clave']."'";
	    
		$resultado = $mysqli->query($query);
				
		if($registro = $resultado->fetch_assoc()) {
			$response = array();
    	    $response['data'] =  array(
                    'id'      => $registro['id'],
			        'nombre'  => $registro['nombre'],
			        'nivel'   => $registro['nivel'],
                    'correo'  => $registro['correo']);
                		
            $response['rsp']=1;
            echo json_encode($response);
            
    	} else {
            echo json_encode(array(
                'rsp' => 2,
                'msg' => "Correo o clave incorrecta"));
            exit;
        }
	}

?>