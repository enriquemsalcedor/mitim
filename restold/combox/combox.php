<?php
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
  header('content-type: application/json; charset=utf-8');
  header('Content-Type: application/JSON');
  include("../../conexion.php");
	
	
	$method = $_SERVER['REQUEST_METHOD'];        
	if ($method =='GET') 
	{

      $action = $_REQUEST['action'];   
    	switch($action){
            case "usuariosDep":          
                  usuariosDep();
                  break;
            case "proyectos":
                  proyectos();
                  break;
            case "categorias":
                  categorias();
                  break;
           default:
        		  echo "{failure:true}";
        		  break;
	   }
   }

function usuariosDep()
{
		global $mysqli;
    if(isset($_REQUEST['onlydata']))
    { 
      $odata = $_REQUEST['onlydata']; 
    }else{ 
      $odata = ''; }


    $nivel = (!empty($_REQUEST['nivel']) ?$_REQUEST['nivel'] : '');
    
		$iddepartamentos = (!empty($_REQUEST['iddepartamentos']) ?$_REQUEST['iddepartamentos'] : '');
    
    if (is_array($iddepartamentos))
		$iddepartamentos = implode(',',$iddepartamentos);
		
		$query  = " SELECT id, correo, nombre FROM usuarios WHERE 1 = 1  ";
		if($iddepartamentos !=''){
			$query  .=" AND find_in_set($iddepartamentos,iddepartamentos) ";
		}
		$result = $mysqli->query($query);		
		//$combo .= "<select name='asignadoa' id='asignadoa'>";
		if($result->num_rows > 0 ){
	      while($row = $result->fetch_assoc()){
		      $json[]=array(
                    'value'=>$row['id'],
			        'name'=>$row['nombre']);
		}
		echo json_encode($json);	
    }else{
		echo json_encode([]);
    }
}
	


function proyectos()
	{
	global $mysqli;

	if(isset($_REQUEST['onlydata'])){ 
	    $odata = $_REQUEST['onlydata']; 
    }else{ 
        $odata = ''; 
    }
    
    if(isset($_REQUEST['idclientes'])){ 
      $idclientes = $_REQUEST['idclientes']; 
    }else{
       $idclientes = '';
    }
    
    if(isset($_REQUEST['nivel'])){ 
      $nivel = $_REQUEST['nivel']; 
    }else{ 
      $nivel = ''; 
    }
    
    if(isset($_REQUEST['usuario'])){
       $usuario = $_REQUEST['usuario']; 
    }else{ 
       $usuario = ''; 
    }
		
	$query  = " SELECT a.id, a.nombre, b.siglas 
					FROM proyectos a
					LEFT JOIN clientes b ON a.idclientes = b.id ";
		if($nivel != 1 && $nivel != 2){
			$query .= " LEFT JOIN usuarios c ON find_in_set(a.id, c.idproyectos)
						WHERE c.usuario = '$usuario' ";
		}else{
			$query .= " WHERE 1 = 1 ";
		}
		if($idclientes != ""){
			if(is_array($idclientes)){ $idclientes = implode(',',$idclientes); }
			//$query .= " AND a.idclientes IN ($idclientes) ";
			$query .= " AND find_in_set(a.idclientes,'$idclientes') ";			 
		}		
		$query  .= " ORDER BY a.nombre ASC ";
		$result = $mysqli->query($query);
		//debug($query);
		if($result->num_rows > 0 ){
	    while($row = $result->fetch_assoc()){
		    $json[]=array(
          'value'=>$row['id'],
			    'name'=>$row['nombre']);
		}
		  echo json_encode($json);	
    }else{
			echo json_encode([]);
    }
}
	
	function categorias()
	{
		global $mysqli;
		$combo = '';
    if(isset($_REQUEST['onlydata']))
    { $odata = $_REQUEST['onlydata']; 
    }else{ 
      $odata = ''; 
    }
    if(isset($_REQUEST['idproyectos'])){ 
      $proyecto = $_REQUEST['idproyectos']; 
    }else{
       $proyecto = ''; 
    }
		
		$query  = " SELECT id, nombre FROM categorias WHERE 1 = 1 ";	
		if($proyecto != ''){
			$query  .= " AND idproyecto = $proyecto ";
		}
		$query  .= " ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		if($result->num_rows > 0 ){
	    while($row = $result->fetch_assoc()){
		    $json[]=array(
          'value'=>$row['id'],
			    'name'=>$row['nombre']);
		  }
		  echo json_encode($json);	
    }else{
			echo json_encode([]);
    }
	}
?>