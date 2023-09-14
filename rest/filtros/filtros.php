<?php
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('content-type: application/json; charset=utf-8');
    //header('Content-Type: application/JSON');
    //capurando el tipo de metodo                
    include("../../conexion.php");
	header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");
	
	$method = $_SERVER['REQUEST_METHOD'];        
	if ($method =='GET') 
	{

  $action = $_REQUEST['action'];   
	switch($action){
		case "filtroUnidades":
	  		 filtroUnidades();
					break;
		case "filtroCategorias":
	  		  filtroCategorias();
	  		  break;
		case "filtroProyectos":
	  		  filtroProyectos();
	  		  break;
		case "filtroEstados":
	  		  filtroEstados();
          break;
    case "filtroAsignadoa":          
          filtroAsignadoa();
          break;
    case "filtroModalidad":
          filtroModalidad();
					break;
		case "filtroEquipo":
					filtroEquipo();
					break;
		case "modalidad":
					modalidades();
					break;
		case "categoria":
					categorias();
					break;
		case "equipos":
					equipos();
					break;
		case "equiposfiltro":
					equiposfiltro();
					break;
		default:
			  echo "{failure:true}";
			  break;
			}
  }

/*------------------------------------------*/
function filtroUnidades() {
	global $mysqli;
  
  $nivel 			= $_REQUEST['nivel'];
	$usuario 		= $_REQUEST['usuario'];
	$idempresas 	= $_REQUEST['idempresas'];
	$idclientes 	= $_REQUEST['idclientes'];
	$idproyectos 	= $_REQUEST['idproyectos'];


	$query  = " SELECT distinct i.unidadejecutora as id, u.unidad as nombre 
				FROM incidentes i 
				INNER JOIN unidades u ON u.codigo = i.unidadejecutora				
				";
	if($nivel != 1 && $nivel != 2){
		$query .= " LEFT JOIN usuarios j ON i.solicitante = j.correo
				    LEFT JOIN usuarios l ON i.asignadoa = l.correo ";
	}
	$query  .= " WHERE 1 = 1 ";
	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= " AND ( j.usuario = '".$usuario."' OR 
							l.usuario = '".$usuario."' ) ";
	}
	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= " AND i.idempresas in ($idempresas) ";
	}
	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= " AND i.idclientes in ($idclientes) ";
	}
	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= " AND i.idproyectos in ($idproyectos) ";
	}
	$query  .= " ORDER BY u.unidad ASC ";
	//debug('filtroUnidades:'.$query);
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
	}}

/*------------------------------------------*/
function filtroCategorias() {
	global $mysqli;

  $nivel 			= $_REQUEST['nivel'];
	$usuario 		= $_REQUEST['usuario'];
	$idempresas 	= $_REQUEST['idempresas'];
	$idclientes 	= $_REQUEST['idclientes'];
	$idproyectos 	= $_REQUEST['idproyectos'];
	
	$query  = " SELECT a.id, a.nombre 
				FROM categorias a
				INNER JOIN proyectos b ON a.idproyecto = b.id
				";
	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= " LEFT JOIN usuarios c ON b.id IN(c.idproyectos) ";
	}
	$query  .= " WHERE 1 = 1  ";
	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= " AND c.usuario = '".$usuario."' ";
	}
	$query  .= "ORDER BY a.nombre ASC ";
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

/*------------------------------------------*/
function filtroProyectos() {
	global $mysqli;
	$nivel 			= $_REQUEST['nivel'];
	$usuario 		= $_REQUEST['usuario'];
	$idempresas 	= $_REQUEST['idempresas'];
	$idclientes 	= $_REQUEST['idclientes'];
	$idproyectos 	= $_REQUEST['idproyectos'];

	$query  = " SELECT a.id, a.nombre 
				FROM proyectos a
				";
	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= " LEFT JOIN usuarios b ON a.id IN(b.idproyectos) ";
	}
	$query  .= " WHERE 1 = 1  ";
	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= " AND b.usuario = '".$usuario."' ";
	}
	$query  .= "ORDER BY a.nombre ASC ";
	//debug($query);
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

/*------------------------------------------*/

function filtroEstados() {
	global $mysqli;
	$nivel 			= $_REQUEST['nivel'];
	$usuario 		= $_REQUEST['usuario'];
	$idempresas 	= $_REQUEST['idempresas'];
	$idclientes 	= $_REQUEST['idclientes'];
	$idproyectos 	= $_REQUEST['idproyectos'];

	$query  = " SELECT a.id, a.nombre 
				FROM estados a
				INNER JOIN proyectos b ON FIND_IN_SET(b.id,a.idproyectos)
				";
	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= " LEFT JOIN usuarios c ON FIND_IN_SET(b.id,c.idproyectos) ";
	
	}
	$query  .= " WHERE a.id <> 17 AND tipo <> 'laboratorio' ";
	

	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= " AND c.usuario = '".$usuario."' ";
	}
	$query .= " GROUP BY a.id"; 
	$query  .= " ORDER BY a.nombre ASC ";
	
	//debug($query);
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

/*------------------------------------------*/
function filtroAsignadoa() {
	global $mysqli;
	$nivel 			= $_REQUEST['nivel'];
	$usuario 		= $_REQUEST['usuario'];
	$idempresas 	= $_REQUEST['idempresas'];
	$idclientes 	= $_REQUEST['idclientes'];
	$idproyectos 	= $_REQUEST['idproyectos'];

	$query  = " SELECT CONCAT(correo, '') AS id, nombre 
				FROM usuarios a ";
	//$query .= "WHERE a.nivel IN (2,3) ";
	$query .= "WHERE 1 = 1 ";
	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= " AND a.idempresas in ($idempresas) ";
	}
	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= " AND a.idclientes in ($idclientes) ";
	}
	if ( $nivel != 1 && $nivel != 2 ) {
		$query  .= " AND a.idproyectos in ($idproyectos) ";
	}
	$query .= "ORDER BY a.nombre ASC ";
	//debug($query);
	
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

/*------------------------------------------*/
function filtroModalidad() {
	global $mysqli;
  
  $unidades = (!empty($_REQUEST['unidadEjecutora']) ? $_REQUEST['unidadEjecutora'] : 0);
  
  //$unidades = $_SESSION['sitio'];
	$nivel 	= $_REQUEST['nivel'];
	$query  = "SELECT distinct modalidad as id, modalidad as nombre 
				FROM activos ";
	if ( $nivel != 1 && $nivel != 2 ) {
		if($unidades != ''){
			$query  .= " WHERE codigound IN ($unidades) ";
		}else{
			$query  .= " WHERE codigound = '0' ";
		}		
	}
	$query  .= "ORDER BY modalidad ASC ";
	//debug($query);
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

/*------------------------------------------*/
function filtroEquipo() {
	global $mysqli;

  $unidades 	 = (!empty($_REQUEST['unidadEjecutora']) ? $_REQUEST['unidadEjecutora'] : 0);	
	$modalidades = $_REQUEST['modalidad'];
	$nivel 		 = $_REQUEST['nivel'];
  
  $query  = "SELECT codequipo as id, concat(equipo, \" - \",marca, \" - \",modelo) as nombre 
				FROM activos 
				WHERE 1=1 ";
				
	if ( $nivel != 1 && $nivel != 2 ) {
		if($unidades != ''){
			$query  .= " AND codigound IN ($unidades) ";
		}else{
			$query  .= " AND codigound = '0' ";
		}		
	}
	if ( $nivel != 1 && $nivel != 2 ) {
		if($modalidades != ''){
			$query  .= " AND modalidad IN ($modalidades) ";
		}else{
			$query  .= " AND modalidad = '0' ";
		}		
	}
	$query  .= "ORDER BY equipo ASC ";
	//debug($query);
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


function modalidades(){
		global $mysqli;
	
		if(isset($_REQUEST['onlydata']))
		{ $odata = $_REQUEST['onlydata']; 
		}else{ $odata = ''; }
		if(isset($_REQUEST['unidad']))
		{ $unidad = $_REQUEST['unidad'];
		 }else{ $unidad = ''; }
		
		$query  = " SELECT GROUP BY modalidad as nombre FROM activos WHERE 1 = 1 ";
		if ($unidad!='')
			$query  .= "AND codigound = '$unidad' ";
			$query  .= "ORDER BY nombre ASC ";
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
	

function categorias(){
	global $mysqli;
	$combo = '';
	
	if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
	if(isset($_REQUEST['idproyecto'])){ $proyecto = $_REQUEST['idproyecto']; }else{ $proyecto = ''; }
		
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

function equipos(){
	global $mysqli;
	$combo = '';
	if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
	if(isset($_REQUEST['unidad'])){ $unidad = $_REQUEST['unidad']; }else{ $unidad = ''; }
	if(isset($_REQUEST['modalidad'])){ $modalidad = $_REQUEST['modalidad']; }else{ $modalidad = ''; }
		
	$query  = "SELECT  codequipo as id, concat(equipo, \" - \",marca, \" - \",modelo) as nombre 
				FROM activos 
				WHERE 1=1 ";
				
	if ($unidad!='')
		$query  .= "AND codigound = '$unidad' ";
		
	if ($modalidad!='')
		$query  .= "AND modalidad = '$modalidad' ";
		
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


function equiposfiltro(){
	global $mysqli;
	$start=0;
	$limit=50;

	$desde  = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '');
	$hasta  = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : '');
	$equipo  = (!empty($_REQUEST['equipo']) ? $_REQUEST['equipo'] : '');
		
		$where = '';
		if($desde != ''){
			$where .= " AND a.fechacreacion >= '$desde' ";
		}
		if($hasta != ''){
			$where .= " AND a.fechacreacion <= '$hasta' ";
		}
		if($equipo != ''){
			$where .= " AND a.serie = '$equipo' ";
		}	
		$query  = " SELECT a.id, e.nombre AS estado, a.titulo, a.descripcion, b.nombre AS proyecto, 
			a.unidadejecutora as codigounidad, c.unidad AS unidadejecutora,
			a.fechacreacion, a.horacreacion, 
			a.asignadoa, a.idcategoria,
			f.nombre AS categoria, h.prioridad, a.serie, m.marca, m.modalidad, m.equipo, 
			m.modelo, m.estado as estadoactivo
			FROM incidentes2 a
			LEFT JOIN proyectos b ON a.idproyectos = b.id
			LEFT JOIN unidades c ON a.unidadejecutora = c.codigo
			LEFT JOIN estados e ON a.estado = e.id
			LEFT JOIN categorias f ON a.idcategoria = f.id
			LEFT JOIN sla h ON a.idprioridad = h.id
			LEFT JOIN usuarios l ON a.asignadoa = l.correo
			LEFT JOIN activos m ON a.serie = m.codequipo
			WHERE 1 = 1 $where ";
		
		$query  .= " ORDER BY a.id desc ";
		$query .= " LIMIT ".$start.", ".$limit;
		$result = $mysqli->query($query);
		
		if($result->num_rows > 0 )
		{
			$row = $result->fetch_assoc();
			$header[]=array(
      	        'equipo'=>$row['equipo'],
				'marca'=>$row['marca'],
				'modelo'=>$row['modelo'],			
				'serie'=>$row['serie'],
			     'estado'=>$row['estadoactivo']);
		
			$result->data_seek(0);
			
			$equipos=array();
			while($row = $result->fetch_assoc())
			
			{	
				$siglasestado = strtoupper(substr(str_replace(' ','',$row['estado']), 0, 2));
	   		    $equipos[]=array(
      	            'id'=>	$row['id'],
				    'titulo'=>$row['titulo'],
				    'Siglas'=>$siglasestado,			
				    'fechacreacion'=>$row['fechacreacion'],
			        'unidadejecutora'=>$row['unidadejecutora']); 
			}
			$json->head=$header;
			$json->iterar=$equipos;
			echo json_encode($json);
			
		}else{
		    echo json_encode([]);
		}
		
}




?>