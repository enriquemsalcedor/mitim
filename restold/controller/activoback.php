 <?php
	
	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	header('content-type: application/json; charset=utf-8');
	header('Content-Type: application/JSON');
	
	include("../../conexion.php");
	$method = $_SERVER['REQUEST_METHOD'];        
  
	if ($method =='GET') {
        
        $action = $_REQUEST['action'];   
		switch($action)
		{
			case "activoDetails":
				activoDetails();
				break;
			case "activoDetail":	
				activoDetail();
				break;
			default:
				  echo "{failure:true}";
				  break;
	    }
	}elseif ($method =='POST') {
        
        $action = '';
    	if (isset($_REQUEST['action'])) 
    	{
    		$action = $_REQUEST['action'];
    	}
    	    switch($action)
            { 
    		case "activoDetails":
				activoDetails();
				break;
			case "activoDetail":	
				activoDetail();
				break;
		   default:
                echo "{failure:true}";
                break;
            } 
	}

    
function activoDetails()
{
    global $mysqli;
	if(isset($_REQUEST['idactivo'])){ $idactivo = $_REQUEST['idactivo']; }else{ $idactivo = ''; }
	
	$resultado = array();
	
	if($idactivo != '')
	{
		$query="SELECT a.id, a.serie, LEFT(a.nombre,45) AS nombre, a.activo, ma.nombre AS marca, mo.nombre AS modelo,
				a.idambientes, a.modalidad,a.estado, a.fase, a.fechatopemant,
				a.fechainst, b.nombre AS ambiente, c.descripcion AS idempresas, d.nombre AS idclientes, e.nombre AS idproyectos
				FROM activos a 
				LEFT JOIN ambientes b ON a.idambientes = b.id 
				LEFT JOIN empresas c ON a.idempresas = c.id 
				LEFT JOIN clientes d ON a.idclientes = d.id 
				LEFT JOIN proyectos e ON a.idproyectos = e.id 
				LEFT JOIN marcas ma ON a.idmarcas = ma.id
				LEFT JOIN modelos mo ON a.idmodelos = mo.id
				WHERE a.id= $idactivo";
		debugl($query);				
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc())
			{
			$resultado['data'] = array(
			                     'serie' => $row['serie'],
				                 'nombre' => $row['nombre'], 
				                 'marca' => $row['marca'],
				                 'modelo' => $row['modelo'],
				                 'estado' => $row['estado'], 
				                 'ambiente' => $row['ambiente'],
				                 'empresa' => $row['idempresa'],
				                 'cliente' => $row['idcliente']
				                 );
			}
	}else{
	   $resultado['notificacion'] = array('msn' =>'Por favor llene el campo');
	   $resultado['data'] = array();
	}   
	   $resultado['estatus'] = "ok";
	   echo json_encode($resultado);    
}
    	
function activoDetail()
{
	global $mysqli;
	if(isset($_REQUEST['idactivo'])){ $idactivos = $_REQUEST['idactivo']; }else{ $idactivos = ''; }
	
	$resultado = array();
	
	if($idactivos != '')
	{
		    
		$query ="SELECT b.nombre AS marca, c.nombre AS modelo,a.modalidad AS modalidad, a.estado AS estado, a.nombre AS nombre FROM activos a 
				LEFT JOIN marcas b ON b.id = a.idmarcas
				LEFT JOIN marcas c ON c.id = a.idmodelos
                WHERE 1 = 1 AND a.id = $idactivos";
		debug($query);				
		$result = $mysqli->query($query);
			
		while($row = $result->fetch_assoc())
			{
			$resultado['data'] = array('marca' => $row['marca'], 
				                 'modelo' => $row['modelo'],
				                 'nombre' => $row['nombre'], 
				                 'estado' => $row['estado']);
			}
	}else{
	   $resultado['notificacion'] = array('msn' =>'Por favor llene el campo');
	   $resultado['data'] = array();
	}   
	   $resultado['estatus'] = "ok";
	   echo json_encode($resultado);
}

?>