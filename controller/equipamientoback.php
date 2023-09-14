<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}

	switch($oper){
		case "incidentes":
			  incidentes();
			  break;
		case "baseincidentes":
			  baseincidentes();
			  break;
		case "del":
			  eliminarincidentes();
			  break;
		case "abrirSolicitudes":
			  abrirSolicitudes();
			  break;
		case "agregarComentario":
			  agregarComentario();
			  break;
		case "comentarios":
			  comentarios();
			  break;
		case "abrirIncidente":
			  abrirIncidente();
			  break;
		case "guardarIncidente":
			  guardarIncidente();
			  break;
		case  "fusionarIncidentes":
			  fusionarIncidentes();
			  break;
		case  "revertirfusion":
			  revertirfusion();
			  break;
		case  "verificarVencidos":
			  verificarVencidos();
			  break;
		case  "historial":
			  historial();
			  break;
		case  "exportarExcel":
			  exportarExcel();
			  break;
		case  "comentariovisto":
			  comentariovisto();
			  break;
		case  "filtroGrid":
			  filtroGrid();
			  break;
		case  "limpiarFiltrosMasivos":
			  limpiarFiltrosMasivos();
			  break;
		case "guardarfiltros":
			 guardarfiltros();
			 break;
		case "abrirfiltros":
			 abrirfiltros();
			 break;
		default:
			  echo "{failure:true}";
			  break;
	}

	function incidentes()
	{
		global $mysqli;
		//FILTROS MASIVO
		$where2 = "";	
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');	
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Activos' AND usuario = '".$_SESSION['usuario']."'";
		$result = $mysqli->query($query);
		if($result->num_rows >0){
			$row = $result->fetch_assoc();				
			if (!isset($_REQUEST['data'])) {
				$data = $row['filtrosmasivos'];
			}
		}
		
		if($data != ''){
			$data = json_decode($data);
			$vowels = array("[", "]");
			$where2 = str_replace($vowels, "", $where2);
		}
		//echo $where2; exit();
		$nivel = $_SESSION['nivel'];
		//$proyecto = $_SESSION['proyecto'];
		$proyecto = 13;
		$query  = " SELECT *
					FROM activos3
					WHERE 1 = 1 $where $where2
					ORDER BY id ";
		debug('$query: '.$query);
		
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			$response['data'][] = array(				
				'check' 		=>	"",
				'acciones' 		=>	"<div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>
											<span class='icon-col red fa fa-trash boton-eliminar' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar' data-placement='right'></span>
											<span class='icon-col blue fa fa-camera boton-evidencias' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Evidencias' data-placement='right'></span>											
										</div>",
				'id' 			=> $row['id'],
				'estado'		=> $row['estado'],
				'equipo'		=> $row['equipo'],
				'cantidad' 		=> $row['cantidad'],
				'ficha'			=> $row['ficha'],
				'serie' 		=> $row['serie'],
				'marca' 		=> $row['marca'],
				'modelo'		=> $row['modelo'],
				'activo'		=> $row['activo'],
				'casamedica'	=> $row['casamedica'],
				'area'			=> $row['area'],
				'fechainst'		=> $row['fechainst'],
				'fechatopemant'	=> $row['fechatopemant']
			);
		}
		echo json_encode($response);		
	}
	
	function baseincidentes()
	{
		global $mysqli;

		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		$data  = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');	
		$buscar  = (!empty($_REQUEST['buscar']) ? $_REQUEST['buscar'] : '');	
		$v = (isset($_REQUEST['v']) ? $_REQUEST['v'] : 1);	
		if ($v!=1) {
			if(!$sidx) $sidx =1;
			$where = "";
			if ($_GET['_search'] == 'true' && !isset($_GET['filters'])) {
				$searchField = $_GET['searchField'];
				$searchOper = $_GET['searchOper'];
				$searchString = $_GET['searchString'];
				$where = getWhereClause($searchField,$searchOper,$searchString);
			} elseif ($_GET['_search'] == 'true') {
				$filters = $_GET['filters'];
				$where = getWhereClauseFilters($filters);
			}
			$where = str_replace('id','a.id',$where);
			$where = str_replace('titulo','a.titulo',$where);
			$where = str_replace('descripcion','a.descripcion',$where);
			$where = str_replace('proyecto','a.proyecto',$where);
			$where = str_replace('ejecutora','a.unidadejecutora',$where);
			$where = str_replace('solicitante','IFNULL(j.nombre, a.solicitante)',$where);
			//$where = str_replace('asignadoa',' l.usuario = "'.$_SESSION['usuario'].'" and l.nombre ',$where);
			$where = str_replace('asignadoa',' l.nombre ',$where);
			$where = str_replace('a.idf.nombre ','f.id',$where);
			$where = str_replace('estado','a.estado',$where);
			//$where = str_replace('idcategoria','a.idcategoria',$where);
			//$where = str_replace('idsubcategoria','a.idsubcategoria',$where);
			$where = str_replace('a.idpriora.idad','a.idprioridad',$where);
			$where = str_replace('marca','m.marca',$where);
			$where = str_replace('modelo','m.modalidad',$where);
			
			//FILTROS MASIVO
			$where2 = "";	
			$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');	
			
			$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Incidentes' AND usuario = '".$_SESSION['usuario']."'";
			$result = $mysqli->query($query);
			if($result->num_rows >0){
				$row = $result->fetch_assoc();				
				if (!isset($_REQUEST['data'])) {
					$data = $row['filtrosmasivos'];
				}
			}
			
			if($data != ''){
				$data = json_decode($data);
				if(!empty($data->filtrodesde)){
					$filtrodesde = json_encode($data->filtrodesde);
					$where2 .= " AND a.fechacreacion >= $filtrodesde ";
					//setcookie('filtrodesde', $filtrodesde, time() + 365 * 24 * 60 * 60, "/"); 
				}
				if(!empty($data->filtrohasta)){
					$filtrohasta = json_encode($data->filtrohasta);
					$where2 .= " AND a.fechacreacion <= $filtrohasta ";
					//setcookie('filtrohasta', $filtrohasta, time() + 365 * 24 * 60 * 60, "/"); 
				}
				if(!empty($data->filtrocat)){
					$filtrocat = json_encode($data->filtrocat);
					$where2 .= " AND a.idcategoria IN ($filtrocat)";
					//setcookie('filtrocat', $filtrocat, time() + 365 * 24 * 60 * 60, "/"); 
				}
				if(!empty($data->filtrosubcat)){
					$filtrosubcat = json_encode($data->filtrosubcat);
					$where2 .= " AND a.idsubcategoria IN ($filtrosubcat)";
					//setcookie('filtrosubcat', $filtrosubcat, time() + 365 * 24 * 60 * 60, "/"); 
				}			
				if(!empty($data->proyectof)){
					$proyectof = json_encode($data->proyectof);
					$where2 .= " AND a.proyecto IN ($proyectof)";
					//setcookie('proyectof', $proyectof, time() + 365 * 24 * 60 * 60, "/"); 
				}
				if(!empty($data->prioridadf)){
					$prioridadf = json_encode($data->prioridadf);
					$where2 .= " AND a.idprioridad IN ($prioridadf)";
					//setcookie('prioridadf', $prioridadf, time() + 365 * 24 * 60 * 60, "/"); 
				}
				if(!empty($data->filtromod)){
					$filtromod = json_encode($data->filtromod);
					$where2 .= " AND m.modalidad IN ($filtromod)";
					//setcookie('filtromod', $filtromod, time() + 365 * 24 * 60 * 60, "/"); 
				}
				if(!empty($data->filtromarca)){
					$filtromarca = json_encode($data->filtromarca);
					$where2 .= " AND m.marca IN ($filtromarca)";
					//setcookie('filtromarca', $filtromarca, time() + 365 * 24 * 60 * 60, "/"); 
				}
				if(!empty($data->solicitantef)){
					$solicitantef = json_encode($data->solicitantef);
					$where2 .= " AND a.solicitante IN ($solicitantef)";
					//setcookie('solicitantef', $solicitantef, time() + 365 * 24 * 60 * 60, "/"); 
				}
				if(!empty($data->estadof)){
					$estadof = json_encode($data->estadof);
					$where2 .= " AND a.estado IN ($estadof)";
					//setcookie('estadof', $estadof, time() + 365 * 24 * 60 * 60, "/");
				}
				if(!empty($data->asignadoaf)){
					$asignadoaf = json_encode($data->asignadoaf);
					//setcookie('asignadoaf', $asignadoaf, time() + 365 * 24 * 60 * 60, "/");
					
					$asignadoaf = '';
					$i = 0;
					foreach($data->asignadoaf as $usuarios){
						if($i > 0)
							$asignadoaf .=",";
						$asignadoaf .= "'$usuarios'";
						$i++;
					}
					$where2 .= " AND a.asignadoa IN ($asignadoaf)";	
					 
				}
				if(!empty($data->unidadejecutoraf)){
					$unidadejecutoraf = json_encode($data->unidadejecutoraf);
					$where2 .= " AND a.unidadejecutora IN ($unidadejecutoraf)";
					//setcookie('unidadejecutoraf', $unidadejecutoraf, time() + 365 * 24 * 60 * 60, "/"); 
				}
						
				$vowels = array("[", "]");
				$where2 = str_replace($vowels, "", $where2);
			}
			
			if($buscar != ''){
				$where .= " AND MATCH(a.resolucion) AGAINST ('$buscar' IN BOOLEAN MODE)  ";
			}
			//echo $where2; exit();
			
			$query  = " SELECT a.id, e.nombre AS estado, a.titulo, a.descripcion, b.nombre AS proyecto, c.unidad AS unidadejecutora,
						IFNULL(j.nombre, a.solicitante) AS solicitante, a.fechacreacion, a.horacreacion, 
						a.asignadoa, 
						MATCH (a.resolucion) AGAINST ('$buscar' IN BOOLEAN MODE) AS relevance,
						f.nombre AS categoria, h.prioridad, a.serie, m.marca, m.modalidad, a.fechareal as fechacierre
						FROM incidentes a
						LEFT JOIN proyectos b ON a.proyecto = b.id
						LEFT JOIN unidades c ON a.unidadejecutora = c.codigo
						LEFT JOIN estados e ON a.estado = e.id
						LEFT JOIN categorias f ON a.idcategoria = f.id
						LEFT JOIN sla h ON a.idprioridad = h.id
						LEFT JOIN usuarios j ON a.solicitante = j.correo
						LEFT JOIN usuarios l ON a.asignadoa = l.correo
						LEFT JOIN activos m ON a.serie = m.codequipo
						WHERE a.idcategoria not in (12,22,35,43) ";
						
			$queryCuenta = "Select a.id 
							From incidentes a
							LEFT JOIN usuarios j ON a.solicitante = j.correo
							LEFT JOIN usuarios l ON a.asignadoa = l.correo
							LEFT JOIN activos m ON a.serie = m.codequipo
							WHERE a.idcategoria not in (12,22,35,43) "; 

			if($_SESSION['nivel'] == 3) {
				if ($_SESSION['sitio']=='Implementacion') {
					$query  .= "AND l.usuario in ('dvillalta','gjames','itejera','jllaurado','jjimenez','laranda','sescalona') ";
					$queryCuenta  .= "AND l.usuario in ('dvillalta','gjames','itejera','jllaurado','jjimenez','laranda','sescalona') ";
				} else {
					$query  .= "AND l.usuario = '".$_SESSION['usuario']."' ";
					$queryCuenta .= "AND l.usuario = '".$_SESSION['usuario']."' ";
				}
			} elseif($_SESSION['nivel'] == 4){
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.unidadejecutora = '".$_SESSION['sitio']."') ";
				$queryCuenta  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.unidadejecutora = '".$_SESSION['sitio']."') ";
			}
			//$query  .= " AND a.estado <> 17 ";
			//$query  .= " AND (a.fechacreacion > DATE_SUB(CURDATE(),INTERVAL 1 YEAR)) ";
			$query  .= " $where $where2";
			//$queryCuenta  .= " AND (a.fechacreacion > DATE_SUB(CURDATE(),INTERVAL 1 YEAR)) ";
			$queryCuenta  .= " $where $where2";
			
			$query  .= " ORDER BY relevance desc, a.id desc ";
			$result = $mysqli->query($queryCuenta);
			$count = $result->num_rows;

			if( $count >0 ) {
				$total_pages = ceil($count/$limit);
			} else {
				$total_pages = 1;
			}
			if ($page > $total_pages) $page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			$query .= " LIMIT ".$start.", ".$limit;
			$result = $mysqli->query($query);

			$response = new StdClass;

			$response->page = $page;
			$response->total = $total_pages;
			$response->records = $count;
			$i=0;
			$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';
			
			while($row = $result->fetch_assoc()){

				$solicitante = $row['solicitante'];
				if(strpos($solicitante, '<') == true){
					preg_match ( $pattern, $solicitante, $solicitante );
				}
				
				if($row['asignadoa']!=''){
					$asignadoa = "";
					$query2 = " SELECT nombre FROM usuarios WHERE ";
					if (filter_var($row['asignadoa'], FILTER_VALIDATE_EMAIL)) {
						$query2 .= "correo = '".$row['asignadoa']."'";
					}else{
						$query2 .= "correo IN (".$row['asignadoa'].") ";
					}
					$consulta = $mysqli->query($query2);
					if($consulta){
						$x=0;
						while($rec = $consulta->fetch_assoc()){
							if($x > 0) 
								$asignadoa .= " - ";
							$asignadoa .= $rec['nombre'];
							$x++;
						}
						$row['asignadoa'] = $asignadoa;
					}				
				}
				
				$response->rows[$i]['id']=$row['id'];
				$tieneEvidencias = directoriovacio('incidentes', $row['id']);
				$response->rows[$i]['cell']=array('',$row['id'],$row['estado'],$row['titulo'],$row['descripcion'],$solicitante,
				$row['fechacreacion'],$row['horacreacion'],$row['proyecto'],$row['categoria'],$row['asignadoa'],$row['unidadejecutora'],
				$row['serie'],$row['marca'],$row['modalidad'],$row['prioridad'],$row['fechacierre'],$tieneEvidencias);
				$i++;
			}
			echo json_encode($response);
		}
	}

	function eliminarincidentes()
	{
		global $mysqli;

		$id = $_REQUEST['id'];
		$tipo   = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');	
		$nivel = $_SESSION['nivel'];
		$usuario = $_SESSION['usuario'];
		
		if($tipo != '' && $tipo == 'delcomment'){
			//Elimino el Comentario solo si es el mismo usuario quien lo creo
			$idincidente = $_REQUEST['idincidente'];
			if ($nivel==1)
				$query = "DELETE FROM comentarios WHERE id = '$id'";
			else
				$query = "DELETE FROM comentarios WHERE id = '$id' and usuario = '$usuario' ";
			$result = $mysqli->query($query);
			bitacora($_SESSION['usuario'], "Incidentes - Comentarios", 'El Comentario #: '.$id.' fue eliminado.', $id, $query);
		}else{
			//Elimino el Incidente
			$query = "DELETE FROM incidentes WHERE id = '$id'";
			$result = $mysqli->query($query);
			bitacora($_SESSION['usuario'], "Incidentes", 'El Incidente #: '.$id.' fue eliminado.', $id, $query);
		}		
	}

	function abrirSolicitudes() {
		$incidente 	= $_REQUEST['incidente'];		
		$_SESSION['incidente'] = $incidente;
		$myPath = '../incidentes/'.$incidente;
		$target_path = utf8_decode($myPath);
		if (!file_exists($target_path)) {
			mkdir($target_path, 0777);
		}
		//$Path = dirname($_SERVER['PHP_SELF']) . '/../incidentes/'.$_SESSION['incidente'].'/';
		$Path = '/soporte/incidentes/'.$_SESSION['incidente'].'/';
		$hash = strtr(base64_encode($Path), '+/=', '-_.');
		$hash = rtrim($hash, '.');
		
		echo "l1_". $hash;		
	}

	function agregarComentario(){
		global $mysqli;
		$incidente	= $_REQUEST['id'];
		$comentario = $_REQUEST['comentario'];
		$usuario 	= $_SESSION['usuario'];
		$visibilidad = $_REQUEST['visibilidad'];;
		$fecha 		= date("Y-m-d");
		$id_preventivo = 0;

		//Busco si la orden viene de un incidente
		/*$query  = "SELECT id FROM mantenimientoprev WHERE incidente = $incidente ";
		$result = $mysqli->query($query);
		if($result->num_rows > 0){
			$result   = $result->fetch_assoc();
			$id_preventivo = $result['id'];
		}

		if($incidente == ''){
			$id_preventivo = 0;
		}*/

		//$queryI = "INSERT INTO comentarios VALUES(null, $id_preventivo, $incidente, '$comentario', '$visibilidad', '$usuario', '$fecha')";
		$queryI = "INSERT INTO comentarios VALUES(null, 'Incidentes', $incidente, '$comentario', '$visibilidad', '$usuario', '$fecha')";
		if($mysqli->query($queryI)){
			$id = $mysqli->insert_id;
			//BITACORA
			bitacora($_SESSION['usuario'], "Incidentes", "Se ha registrado un Comentario para el Incidente #".$incidente, $incidente, $queryI);
			//ENVIAR NOTIFICACION
			notificarComentarios($incidente,$comentario,$visibilidad);
			echo true;
		}else{
			echo false;
		}
	}

	function comentarios(){
		global $mysqli;

		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$nivel = $_SESSION['nivel'];

		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		if(!$sidx) $sidx =1;

		$buscar = (isset($_POST['buscar']) ? $_POST['buscar'] : '');

		$query  = " SELECT a.id, a.idmodulo, a.comentario, a.fecha, b.nombre, a.visibilidad
					FROM comentarios a
					LEFT JOIN usuarios b ON a.usuario = b.usuario
					WHERE modulo = 'Incidentes' AND idmodulo = $id ";
		if($nivel == 4){
			$query .= " AND a.visibilidad = 'Público' ";
		}
		$query .= " ORDER BY a.fecha DESC ";
		//$mysqli->query("UPDATE comentarios SET visto=1 WHERE modulo='Incidentes' AND idmodulo=$id");
		
		$result = $mysqli->query($query);

		if($count = $result->num_rows){ //Corrige la respuesta de error
			if( $count >0 ) {
				$total_pages = ceil($count/$limit);
			} else {
				$total_pages = 0;
			}
			if ($page > $total_pages) $page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)

			$query .= " LIMIT ".$start.", ".$limit;
			$result = $mysqli->query($query);

			$response = new StdClass;

			$response->page = $page;
			$response->total = $total_pages;
			$response->records = $count;
			$i=0; $j=1;
			
			while($row = $result->fetch_assoc()){
				//ADJUNTOS
				$adjuntos   = '';
				$ruta 		= '../incidentes/'.$row['idmodulo'].'/'.$row['id'];
				if (is_dir($ruta)) { 
				  if ($dh = opendir($ruta)) { 
					$num = 1;
					while (($file = readdir($dh)) !== false) { 
						if ($file != "." && $file != ".." && $file != ".quarantine" && $file != ".tmb"){ 
							//$nombrefile = explode('_',$file);
							$nombrefile = $file;
							if($num > 1){
								$adjuntos .= ", ";
							}
							$adjuntos .= "<a href='soporte/".$ruta."/".$file."' target='_blank'>".$nombrefile."</a>";
							$num++;
						}						
					} 
					closedir($dh); 
				  } 
				}
				$response->rows[$i]['id']=$row['id'];
				$response->rows[$i]['cell']=array('',$j,$row['id'],$row['comentario'],$row['nombre'],$row['visibilidad'],$row['fecha'],$adjuntos);
				$i++; $j++;
			}
			echo json_encode($response);
		}
	}
	
	//ENVIAR CORREO DE NOTIFICACION DE COMENTARIO
	function notificarComentarios($incidente,$comentario,$visibilidad){
		global $mysqli;
		//CREADOR - SOLICITANTE - ASIGNADO
		$query  = " SELECT IFNULL(i.correo, a.creadopor) AS creadopor, 
					a.solicitante, a.asignadoa, a.notificar
					FROM incidentes a
					LEFT JOIN usuarios i ON a.creadopor = i.id OR a.creadopor = i.correo
					WHERE a.id = $incidente ";
		$result = $mysqli->query($query);
		while ($row = $result->fetch_assoc()) {
			/*
			if($visibilidad == 'Privado'){
				$correo [] = $row['creadopor'];
			}else
			*/
			if($visibilidad != 'Privado'){
				$correo [] = $row['creadopor'];
				$correo [] = $row['solicitante'];
				$notificar = $row['notificar'];
				
				//Usuarios que quieren que se les notifique (Enviar Notificacion a)
				$notificar = json_decode($notificar);
				if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
					$correo [] = "$notificar";				
				}else{
					foreach($notificar as $notif){
						$correo [] = $notif;
					}
				}
				
			}
			
			//USUARIO O GRUPO DE USUARIOS ASIGNADOS
			$asignadoaN	= '';		
			if($row['asignadoa'] != ''){
				$asignadoa  = $row['asignadoa'];
				if (filter_var($asignadoa, FILTER_VALIDATE_EMAIL)) {
					$correo [] = "$asignadoa";				
				}else{
					foreach([$asignadoa] as $asig){
						$correo [] = $asig;
					}
				}			
				$query2 = " SELECT nombre FROM usuarios WHERE ";
				if (filter_var($row['asignadoa'], FILTER_VALIDATE_EMAIL)) {
					$query2 .= "correo = '".$row['asignadoa']."'";
				}else{
					$query2 .= "correo IN (".$row['asignadoa'].") ";
				}
				$consulta = $mysqli->query($query2);
				while($rec = $consulta->fetch_assoc()){
					$asignadoaN .= $rec['nombre']." , ";
				}			
			}		
		}
		
		//DATOS DEL CORREO
		$usuarioS = $_SESSION['usuario'];
		$consultaUA = $mysqli->query("SELECT nombre FROM usuarios WHERE usuario = '$usuarioS' LIMIT 1 ");
		while ($registroUA = $consultaUA->fetch_assoc()) {
			$usuarioAct = $registroUA['nombre'];
		}
		//DATOS
		$query  = " SELECT a.id, a.titulo, a.descripcion, c.unidad AS unidadejecutora, a.resolucion,
					h.prioridad, a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, IFNULL(j.nombre, a.solicitante) AS solicitante, a.asignadoa,
					a.departamento, IF(a.fechacreacion!='',CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion					
					FROM incidentes a
					LEFT JOIN proyectos b ON a.proyecto = b.id
					LEFT JOIN unidades c ON a.unidadejecutora = c.codigo
					LEFT JOIN activos d ON a.serie = d.codequipo
					LEFT JOIN estados e ON a.estado = e.id
					LEFT JOIN categorias f ON a.idcategoria = f.id
					LEFT JOIN subcategorias g ON a.idsubcategoria = g.id
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.id OR a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.resueltopor = k.correo
					WHERE a.id = $incidente ";
					
		$result = $mysqli->query($query);
		$row 	= $result->fetch_assoc();
		$fechacreacion 	= $row['fechacreacion'];
		$descripcion	= $row['descripcion'];
		$solicitante	= $row['solicitante'];
		$creadopor		= $row['creadopor'];
		$departamento	= $row['departamento'];
		$prioridad		= $row['prioridad'];
		$sitio 			= $row['unidadejecutora'];
		$resolucion 	= $row['resolucion'];
		$nasignadoa 	= $asignadoaN;
		$comentarios	= '';
		$bitacora		= '';
		
		//COMENTARIOS
		$consultaC = $mysqli->query("SELECT comentario FROM comentarios WHERE idmodulo = $incidente ");
		while ($registroC = $consultaC->fetch_assoc()) {
			$comentarios .= $registroC['comentario'].'<br>';
		}
		//BITACORA
		$consultaB = $mysqli->query("SELECT accion FROM bitacora WHERE identificador = $incidente ");
		while ($registroB = $consultaB->fetch_assoc()) {
			$bitacora .= $registroB['accion'].'<br>';
		}
		
		$asunto = "Incidente #$incidente - Comentario";
		
		$mensaje  = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha comentado el incidente #".$incidente."</b></p>			
					<p style='padding-left: 30px;width:100%;'>Comentario ".$visibilidad.": ".$comentario."</p>
					<p style='width:100%;'><br><a href='http://190.14.199.6/soporte/incidentes.php?id=$incidente' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Añadir un comentario</a></p>
					<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin-top: 30px;padding: 10px 10px 0 30px;width:100%;'>Comentarios anteriores</p>
					<p style='padding-left: 30px;width:100%;'>".$comentarios."</p>
					<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin-top: 30px;padding: 10px 10px 0 30px;width:100%;'>Actividad reciente</p>
					<p style='padding-left: 30px;width:100%;'>".$bitacora."</p>
					<br><br>
					<p  style='font-size: 18px;width:100%;'>".$creadopor." ha creado este incidente el ".$fechacreacion."</p>
					<br>
					<p style='width:100%;'>".$descripcion."</p>
					<br>
					<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin: auto;padding: 10px;width:100%;'>Atributos</p>
					<table style='width: 50%;'>
						<tr>
							<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Solicitante del servicio</div>".$solicitante."</td>
							<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Sitio</div>".$sitio."</td>
						</tr>
						<tr>
							<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Recibido en</div>".$fechacreacion."</td>
							<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Departamento</div>".$departamento."</td>
						</tr>
						<tr>
							<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Asignado a</div>".$nasignadoa."</td>
							<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Prioridad</div>".$prioridad."</td>
						</tr>
					<table>
					</div>";
		//USUARIOS DE SOPORTE
		//$correo [] = 'ana.porras@maxialatam.com';
		$correo [] = 'isai.carvajal@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		/*
		$consultaUS = $mysqli->query("SELECT correo FROM usuarios WHERE nivel = 4 ");
		while ($registroUS = $consultaUS->fetch_assoc()) {
			$correo[] = $registroUS['correo'];
		}
		*/		
		enviarMensajeIncidente($asunto,$mensaje,$correo);
	}

	function abrirIncidente(){
		global $mysqli;
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado 	 = '';
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.id AS proyecto,
					c.codigo AS unidad, d.codequipo AS serie, d.activo, d.marca, d.modelo, e.id AS estado, 
					f.id AS categoria, g.id AS subcategoria, h.id AS prioridad, 
					a.solicitante, a.asignadoa, a.departamento, a.modalidad,
					CONCAT_WS('',j.id,' - ', j.titulo) AS fusionado, a.notificar, a.resolucion,
					a.reporteservicio, a.numeroaceptacion, a.estadomantenimiento, a.observaciones, a.fechacertificar, a.horario,
					a.marca, a.modelo, a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, a.modalidad, a.comentariosatisfaccion,
					IFNULL(k.nombre, a.resueltopor) AS resueltopor, 
					IF(a.fechacreacion!='', a.fechacreacion,'') AS fechacreacion, a.horacreacion,
					IF(a.fechavencimiento!='',CONCAT(a.fechavencimiento,'  ', a.horavencimiento),'') AS fechavencimiento,
					IF(a.fecharesolucion!='',CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,
					a.fechacierre, a.horacierre,
					a.fechamodif, a.fechacertificar, 
					a.horastrabajadas, a.periodo 
					FROM incidentes a
					LEFT JOIN proyectos b ON a.proyecto = b.id
					LEFT JOIN unidades c ON a.unidadejecutora = c.codigo
					LEFT JOIN activos d ON a.serie = d.codequipo
					LEFT JOIN estados e ON a.estado = e.id
					LEFT JOIN categorias f ON a.idcategoria = f.id
					LEFT JOIN subcategorias g ON a.idsubcategoria = g.id
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.id OR a.creadopor = i.correo
					LEFT JOIN incidentes j ON a.fusionado = j.id
					LEFT JOIN usuarios k ON a.resueltopor = k.id OR a.resueltopor = k.correo
					WHERE a.id = $id ";
		$result = $mysqli->query($query);

		while($row = $result->fetch_assoc()){
			//los registros viejos les digo que provienen de la tabla usuarios
			/*if(is_numeric($row['asignadoa'])){
				$row['asignadoa']= $row['asignadoa'].'-U';
			} */
			
			if($row['marca'] == '0')
				$row['marca']='';
			if($row['modelo'] == '0')
				$row['modelo']='';
			if($row['modalidad'] == '0')
				$row['modalidad']='';
			if($row['comentariosatisfaccion'] == '0')
				$row['comentariosatisfaccion']='';
			if($row['periodo'] == '0')
				$row['periodo']='';
			if($row['descripcion'] == '0')
				$row['descripcion']='';
			
			//reviso la cadena y solo tomo el correo
			$solicitante = $row['solicitante'];
			$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';
			if(strpos($solicitante, '<') == true){
				preg_match ( $pattern, $solicitante, $solicitante );
			}

			$resultado[] = array(
						'id' 					=> $row['id'],
						'titulo'				=> $row['titulo'],
						'descripcion' 			=> $row['descripcion'],
						'proyecto' 				=> $row['proyecto'],
						'unidad' 				=> $row['unidad'],
						'serie' 				=> $row['serie'],
						'activo' 				=> $row['activo'],
						'marca' 				=> $row['marca'],
						'modelo' 				=> $row['modelo'],
						'estado' 				=> $row['estado'],
						'categoria' 			=> $row['categoria'],
						'subcategoria' 			=> $row['subcategoria'],
						'prioridad' 			=> $row['prioridad'],
						'solicitante' 			=> $solicitante,
						'asignadoa' 			=> $row['asignadoa'],
						'departamento' 			=> $row['departamento'],
						'modalidad' 			=> $row['modalidad'],
						'fusionado' 			=> $row['fusionado'],
						'notificar' 			=> $row['notificar'],
						'resolucion' 			=> $row['resolucion'],						
						'reporteservicio' 		=> $row['reporteservicio'],
						'numeroaceptacion' 		=> $row['numeroaceptacion'],
						'estadomantenimiento' 	=> $row['estadomantenimiento'],
						'observaciones' 		=> $row['observaciones'],
						'horario' 				=> $row['horario'],
						'marca' 				=> $row['marca'],
						'modelo' 				=> $row['modelo'],
						'origen' 				=> $row['origen'],
						'creadopor' 			=> $row['creadopor'],
						'modalidad' 			=> $row['modalidad'],
						'comentariosatisfaccion'=> $row['comentariosatisfaccion'],
						'resueltopor' 			=> $row['resueltopor'],
						'fechacreacion' 		=> $row['fechacreacion'],
						'horacreacion' 			=> $row['horacreacion'],
						'fechavencimiento' 		=> $row['fechavencimiento'],
						'fecharesolucion' 		=> $row['fecharesolucion'],						
						'fechacierre' 			=> $row['fechacierre'],
						'horacierre' 			=> $row['horacierre'],
						'fechamodif' 			=> $row['fechamodif'],
						'fechacertificar' 		=> $row['fechacertificar'],
						'horastrabajadas' 		=> $row['horastrabajadas'],
						'periodo' 				=> $row['periodo']
					);
		}
		echo json_encode($resultado);
	}
	
	function guardarIncidente()
	{
		global $mysqli;
		
		$id   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$data = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');		
		
		//MASIVO
		$idarray = explode(",", $id);
		if(count($idarray) > 1){
			$query = "";
			$query2 = "";
			if($data !=''){				
				$i=0;
				$coma=', ';
				$query .= "UPDATE incidentes SET ";
				foreach($data as $c => $v){
					if($v != ''){
						if($i != 0)
							$query .= $coma;

						//MARCA Y MODELO
						if($c == 'serie'){
							$queryMM  	= " SELECT marca, modelo, activo, modalidad FROM activos WHERE codequipo = '$v' ";
							$resultMM 	= $mysqli->query($queryMM);
							$rowMM 		= $resultMM->fetch_assoc();
							$marca 		= $rowMM['marca'];
							$modelo 	= $rowMM['modelo'];
							$activo 	= $rowMM['activo'];
							$modalidad 	= $rowMM['modalidad'];

							$query .= " $c = '$v', marca = '$marca', modelo = '$modelo', activo = '$activo', modalidad = '$modalidad' ";
						}
						//DIAS Y HORAS
						elseif($c == 'prioridad'){
							$queryV  			= " SELECT dias, horas FROM sla WHERE id = '$v' ";
							$resultV 			= $mysqli->query($queryV);
							$rowV 				= $resultV->fetch_assoc();
							$diasP 				= $rowV['dias'];
							$horasP 			= $rowV['horas'];
							$fechavencimiento 	= date('Y-m-d', strtotime($fechacreacion."+ ".$diasP." days"));
							$horavencimiento  	= date('H:i:s', strtotime($horacreacion." + ".$horasP." hours"));
							$query .= " idprioridad = '$v', fechavencimiento = '$fechavencimiento', horavencimiento = '$horavencimiento' ";
						}
						elseif($c == 'categoria'){
							$query .= " idcategoria = '$v'";
						}
						elseif($c == 'subcategoria'){
							$query .= " idsubcategoria = '$v'";
						}
						elseif($c == 'fecharesolucion'){
							$fecharesolucion = preg_split("/[\s,]+/",$v);
							$query .= " fecharesolucion = '".$fecharesolucion[0]."',";
							$query .= " horaresolucion = '".$fecharesolucion[1]."' ";
						}
						/*
						elseif($c == 'fechacierre'){
							$fechacierre = preg_split("/[\s,]+/",$v);
							$query .= " fechacierre = '".$fechacierre[0]."',";
							$query .= " horacierre = '".$fechacierre[1]."' ";
						}
						*/
						else{
							$query .= " $c = '$v'";
						}
						$i++;
					}
				}
				if($i > 1){
					foreach($idarray as $id){
						if($mysqli->query($query." WHERE id = $id ")){
							bitacora($_SESSION['usuario'], "Incidentes", 'El Incidente #'.$id.' ha sido Editado exitosamente', $id, $query);
							echo true;
						}else{
							echo false;
						}			
					}
				}				
				echo 'Sin datos en el Formulario';
			}
		}
		//NORMAL
		else{
			$titulo 			= $data['titulo'];
			$descripcion 		= (!empty($data['descripcion']) ? $data['descripcion'] : '');
			$proyecto 			= (!empty($data['proyecto']) ? $data['proyecto'] : 0);
			$unidadejecutora 	= (!empty($data['unidadejecutora']) ? $data['unidadejecutora'] : '');
			$serie 				= (!empty($data['serie']) ? $data['serie'] : '');
			$estado 			= (!empty($data['estado']) ? $data['estado'] : 0);
			$categoria 			= (!empty($data['categoria']) ? $data['categoria'] : 0);
			$subcategoria 		= (!empty($data['subcategoria']) ? $data['subcategoria'] : 0);
			$prioridad 			= (!empty($data['prioridad']) ? $data['prioridad'] : 0);
			$origen 			= (isset($data['origen']) ? $data['origen'] : 'sistema');
			$solicitante 		= (!empty($data['solicitante']) ? $data['solicitante'] : '');
			$creadopor			= (!empty($data['creadopor']) ? $data['creadopor'] : $_SESSION['correousuario']);
			$asignadoa 			= (!empty($data['asignadoa']) ? $data['asignadoa'] : '');
			$departamento 		= (!empty($data['departamento']) ? $data['departamento'] : '');
			$notificar 			= (!empty($data['notificar']) ? $data['notificar'] : '');
			$resolucion 		= (!empty($data['resolucion']) ? $data['resolucion'] : '');
			$reporteservicio 	= (!empty($data['reporteservicio']) ? $data['reporteservicio'] : '');
			$numeroaceptacion 	= (!empty($data['numeroaceptacion']) ? $data['numeroaceptacion'] : '');
			$estadomtto 		= (!empty($data['estadomantenimiento']) ? $data['estadomantenimiento'] : '');
			$observaciones 		= (!empty($data['observaciones']) ? $data['observaciones'] : '');	
			$horario 			= (!empty($data['horario']) ? $data['horario'] : '');
			$fechavencimiento	= NULL;
			$horavencimiento  	= NULL;
			$fecharesolucion 	= (!empty($data['fecharesolucion']) ? $data['fecharesolucion'] : '');
			$fechacierre 		= (!empty($data['fechacierre']) ? $data['fechacierre'] : '');
			$horacierre 		= (!empty($data['horacierre']) ? $data['horacierre'] : '');
			$fechacertificar 	= (!empty($data['fechacertificar']) ? $data['fechacertificar'] : '');
			$fechacreacion		= (!empty($data['fechacreacion']) ? $data['fechacreacion'] : date("Ymd"));
			$horacreacion 		= (!empty($data['horacreacion']) ? $data['horacreacion'] : date("H:i:s"));
			$horastrabajadas 	= (!empty($data['horastrabajadas']) ? $data['horastrabajadas'] : '0');
			$estadoInc 			= '';
			
			if($unidadejecutora=='')
				$unidadejecutora=0;
			if($categoria=='')
				$categoria=0;
			if($subcategoria=='')
				$subcategoria=0;
			if($proyecto=='')
				$proyecto=0;
			if($prioridad=='')
				$prioridad=0;
		
			if($fecharesolucion != ''){
				$fecharesolucion = preg_split("/[\s,]+/",$fecharesolucion);
				$horaresolucion  = "'".$fecharesolucion[1]."'";
				$fecharesolucion = "'".$fecharesolucion[0]."'";
			}else{
				$fecharesolucion = 'null';
				$horaresolucion  = 'null';
			}
			
			if($fechacierre == '' && $estado==16){
				$fechacierre	= "'".date('Y-m-d')."'";
				$horacierre 	= "'".date('H:i:s')."'";
			}elseif ($fechacierre == '') {
				$fechacierre = 'null';
				$horacierre  = 'null';
			} else {
				$horacierre  = "'".$horacierre."'";
				$fechacierre = "'".$fechacierre."'";				
			}
			
			//BUSCO EL PERIODO
			$queryPer  = "SELECT periodo FROM cuatrimestres WHERE $fechacreacion BETWEEN fechainicio AND fechafin ";
			$resultPer = $mysqli->query($queryPer);
			if($resultPer->num_rows > 0){
				$resultPer   = $resultPer->fetch_assoc();
				$periodo = $resultPer['periodo'];
			}else{
				$periodo = '';
			}
			//MARCA, MODELO Y MODALIDAD
			if ($serie != '') {
				$queryMM  			= " SELECT marca, modelo, activo, modalidad FROM activos WHERE codequipo = '$serie' ";
				$resultMM 			= $mysqli->query($queryMM);
				$rowMM 				= $resultMM->fetch_assoc();
				$marca 				= $rowMM['marca'];
				$modelo 			= $rowMM['modelo'];
				$activo 			= $rowMM['activo'];
				$modalidad 			= $rowMM['modalidad'];
			} else {
				$marca 				= '';
				$modelo 			= '';
				$activo 			= '';
				$modalidad 			= '';
			}
			//DIAS Y HORAS
			if($prioridad != '0' || $prioridad != ''){
				$queryV  			= " SELECT dias, horas FROM sla WHERE id = '$prioridad' ";
				$resultV 			= $mysqli->query($queryV);
				$rowV 				= $resultV->fetch_assoc();
				$diasP 				= $rowV['dias'];
				$horasP 			= $rowV['horas'];
				$fechavencimiento 	= date('Y-m-d', strtotime($fechacreacion."+ ".$diasP." days"));
				$horavencimiento  	= date('H:i:s', strtotime($horacreacion." + ".$horasP." hours"));
			}

			if($id != ''){
				//CAMPOS ACTUALIZADOS
				$queryInc = $mysqli->query("SELECT estado FROM incidentes WHERE id = '$id'");
				while ($rowInc = $queryInc->fetch_assoc()) {
					$estadoInc = $rowInc['estado'];
				}				
				$descripcion = str_replace("'","",$descripcion);
				$accion = 'El Incidente #'.$id.' ha sido Editado exitosamente';
				$query = "UPDATE incidentes SET titulo = '$titulo', descripcion = '$descripcion', proyecto = $proyecto,
						  unidadejecutora = '$unidadejecutora', serie = '$serie', activo = '$activo', marca = '$marca', modelo = '$modelo', 
						  estado = '$estado', idcategoria = $categoria, idsubcategoria = $subcategoria, idprioridad = $prioridad, 
						  solicitante = '$solicitante', asignadoa = '$asignadoa', departamento = '$departamento',
						  modalidad = '$modalidad' , notificar = '$notificar', resolucion = '$resolucion', reporteservicio = '$reporteservicio',
						  numeroaceptacion = '$numeroaceptacion', estadomantenimiento = '$estadomtto', observaciones = '$observaciones',
						  fechacertificar = '$fechacertificar', horario = '$horario', fecharesolucion = $fecharesolucion, 
						  horaresolucion = $horaresolucion,
						  horastrabajadas = '$horastrabajadas', fechacierre = $fechacierre, horacierre = $horacierre  ";
				$query .= " WHERE id = $id ";
			}else{	
				$fechacreacion	= date('Y-m-d');
				$horacreacion 	= date('H:i:s');

				$accion = 'El Incidente #'.$id.' ha sido Creado exitosamente';
				$query = "INSERT INTO incidentes(id, titulo, descripcion, proyecto, unidadejecutora, serie, activo, marca, modelo, estado, idcategoria,
					  idsubcategoria, idprioridad, origen, creadopor, solicitante, asignadoa, departamento, modalidad, fechacreacion, ";
				if($fechavencimiento != ''){
					$query .= "fechavencimiento, horavencimiento, ";
				}				
				$query .=" horacreacion, periodo, notificar, resolucion, reporteservicio, numeroaceptacion, estadomantenimiento, observaciones, 
					  fechacertificar, horario, fechareal, horareal)
					  VALUES(null, '$titulo', '$descripcion', '$proyecto', '$unidadejecutora', '$serie', '$activo', '$marca', '$modelo', '$estado',
					  '$categoria', '$subcategoria', '$prioridad', '$origen', '$creadopor', '$solicitante', '$asignadoa', '$departamento', '$modalidad',
					  '$fechacreacion', ";
				if($fechavencimiento !=''){
					$query .= "'$fechavencimiento', '$horavencimiento',  ";
				}	  
				$query .= " '$horacreacion', '$periodo','$notificar', '$resolucion', '$reporteservicio', 
					  '$numeroaceptacion', '$estadomtto', '$observaciones', '$fechacertificar', '$horario','$fechacreacion', '$horacreacion' ) ";
			}
			
			if($mysqli->query($query)){
				if($id == ''){
					$id = $mysqli->insert_id;
					//CREAR CARPETA DE ID INCIDENTES Y COMENTARIOS
					$myPath = '../incidentes/';
					if (!file_exists($myPath))
						mkdir($myPath, 0777);
					$myPath = '../incidentes/'.$id.'/';
					$target_path2 = utf8_decode($myPath);
					if (!file_exists($target_path2))
						mkdir($target_path2, 0777);
					
					//ENVIAR CORREO AL CREADOR DEL INCIDENTE
					nuevoincidente($_SESSION['usuario'], $titulo, $descripcion, $id, $fechacreacion, $horacreacion, $solicitante);
					//notificarUsuarios($id,'','creado');					
					notificarCEstado($id,'','creado','',$estado);
					
					if($prioridad == '6'){
						//fueradeservicio($id,$serie);
						$queryfs  = "UPDATE activos set estado = 'INACTIVO' WHERE codequipo = '$serie' ";
						$resultfs = $mysqli->query($queryfs);
						$queryfs  = "INSERT INTO fueraservicio VALUES(null, '$serie', '$fechacreacion', null, $id) ";
						$resultfs = $mysqli->query($queryfs);
					}
					
					//Integracion de los sistemas de soporte e implementación
					/*if($asignadoa != ''){
						$queryInte = "SELECT GROUP_CONCAT('\"', correo, '\"') AS id, a.nombre  
						FROM grupos a
						INNER JOIN gruposusuarios b ON a.id = b.idgrupo
						INNER JOIN usuarios c ON b.idusuario = c.id
						WHERE correo IN ($asignadoa)";
						$resultInte = $mysqli->query($queryInte);
						if($resultInte->num_rows > 0){
							$queryInte = "Insert Into actividades Values(null,$proyecto,'$titulo','','','','','','','','','$asignadoa',$prioridad, '', $estado)";
							$mysqli->query($queryInte);
						}
					}*/
				}else{
					//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
					if($estadoInc != $estado){
						notificarCEstado($id,notificar,'actualizado',$estadoInc,$estado);
						if($prioridad == '6' && ($estado == 16 || $estado == 17)){
							//fueradeservicio($id,$serie);
							$queryfs  = "UPDATE activos set estado = 'ACTIVO' WHERE codequipo = '$serie' ";
							$resultfs = $mysqli->query($queryfs);
							$queryfs  = "UPDATE fueraservicio set hasta = $fecharesolucion WHERE  incidente = $id ";
							$resultfs = $mysqli->query($queryfs);
						}
					} 					
				}
				bitacora($_SESSION['usuario'], "Incidentes", $accion, $id, $query);

				//ENVIAR CORREO DE SATISFACCION - RESUELTO / CERRADO
				if($estado == 16 || $estado == 17){
					//crearMensajeSatisfaccion($id,$titulo,$solicitante);
				}				
				echo true;
			}else{
				echo false;
			}
			//echo true;
		}
	}

	//ENVIAR CORREO AL SOLICITANTE DEL INCIDENTE Y SOPORTE
	function nuevoincidente($usuario, $titulo, $descripcion, $incidente, $fecha, $hora, $solicitante){
		global $mysqli, $mail;
		
		//SOLICITANTE
		if($solicitante !=''){
			if (filter_var($solicitante, FILTER_VALIDATE_EMAIL)) {
					$correo [] = $solicitante;
			}else{
				$result = $mysqli->query("SELECT correo FROM usuarios WHERE id = '$solicitante'");
				while ($row=$result->fetch_assoc()) {
					$correo [] = $row['correo'];
				}
			}
			//Asunto
			$asunto = "Incidente #$incidente ha sido Creado";
			//Cuerpo
			$fecha = implode('/',array_reverse(explode('-', $fecha)));
			$cuerpo = '';		
			$cuerpo .= "<div style='width: 100%; text-align: right;'><b>Fecha:</b> ".$fecha."&nbsp;&nbsp;&nbsp;</div>";
			$cuerpo .= "<br><b>".$titulo."</b>";
			$cuerpo .= "<p style='width: 100%;'>Buen día,<br>Gracias por contactar al Centro de Soporte, su caso ha sido asignado a nuestros Ingenieros especializados quienes los contactarán brevemente para mas detalles sobre el caso.<p>";
			$cuerpo .= "<br><br>";
			//Correo
			enviarMensajeIncidente($asunto,$cuerpo,$correo);
		}		
		
		//SOPORTE
		/*
		$correoS [] = 'isai.carvajal@maxialatam.com';
		//Asunto
		$asuntoS = "Incidente #$incidente ha sido Creado";
		//Cuerpo
		$fecha = implode('/',array_reverse(explode('-', $fecha)));
		$cuerpoS = '';		
		$cuerpoS .= "<div style='width: 100%; text-align: right;'><b>Fecha:</b> ".$fecha."&nbsp;&nbsp;&nbsp;</div>";
		$cuerpoS .= "<br><p style='width: 100%;'>Buen día,<br>Ha sido creado un nuevo incidente bajo el número: <b>".$incidente."</b>.<p>";
		$cuerpoS .= "<br><b>".$titulo."</b>";	
		$cuerpoS .= "<br>".$descripcion;			
		$cuerpoS .= "<br><p style='width: 100%;'><a href='http://web.maxialatam.com:8010/soporte/incidentes.php?id=$incidente' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;'>Ver Incidente</a></p>";
		$cuerpoS .= "<br><br>";
		//Correo
		enviarMensajeIncidente($asuntoS,$cuerpoS,$correoS);	
		*/		
	}

	//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
	function notificarCEstado($incidente,$notificar,$accion,$estadoold,$estadonew){
		global $mysqli;
		
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, c.codigo AS codigounidad, c.unidad AS unidadejecutora,
					a.serie, d.marca, d.modelo, e.nombre AS estado, f.id AS idcategoria, f.nombre AS categoria, g.nombre AS subcategoria,
					h.prioridad, a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, IFNULL(j.nombre, a.solicitante) AS solicitante, a.asignadoa,
					a.departamento, d.modalidad, a.satisfaccion, a.comentariosatisfaccion, a.resolucion, IFNULL(k.nombre, a.resueltopor) AS resueltopor,
					IF(a.fechacreacion!='',CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion,
					IF(a.fechavencimiento!='',CONCAT(a.fechavencimiento,'  ', a.horavencimiento),'') AS fechavencimiento,
					IF(a.fecharesolucion!='',CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,
					IF(a.fechacierre!='',CONCAT(a.fechacierre,'  ', a.horacierre),'') AS fechacierre,
					a.fechamodif, a.fechacertificar, 
					a.horastrabajadas, a.periodo, a.comentariovisto,					
					IFNULL(i.correo, a.creadopor) AS correocreadopor,
					IFNULL(j.correo, a.solicitante) AS correosolicitante
					FROM incidentes a
					LEFT JOIN proyectos b ON a.proyecto = b.id
					LEFT JOIN unidades c ON a.unidadejecutora = c.codigo
					LEFT JOIN activos d ON a.serie = d.codequipo
					LEFT JOIN estados e ON a.estado = e.id
					LEFT JOIN categorias f ON a.idcategoria = f.id
					LEFT JOIN subcategorias g ON a.idsubcategoria = g.id
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.id OR a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.resueltopor = k.correo
					WHERE a.id = $incidente ";
					
		$result = $mysqli->query($query);
		$row 	= $result->fetch_assoc();
		
		//1 para quien quien creo el incidentes (Creado por)
		//$correo [] = $row['correocreadopor'];
		
		//2 para quien solicito o reporto el incidente (Solicitante)
		if($estadonew == 16 || $estadonew == 17){
			$correo [] = $row['correosolicitante'];
		}
		
		//3 para quien se le asigno el incidente (Asignado a)	
		
		//USUARIO O GRUPO DE USUARIOS ASIGNADOS
		$asignadoaN	= '';		
		if($row['asignadoa'] != ''){
			$asignadoa  = $row['asignadoa'];
			if (filter_var($asignadoa, FILTER_VALIDATE_EMAIL)) {
				$correo [] = "$asignadoa";				
			}else{
				foreach([$asignadoa] as $asig){
					$correo [] = $asig;
				}
			}			
			$query2 = " SELECT nombre FROM usuarios WHERE ";
			if (filter_var($row['asignadoa'], FILTER_VALIDATE_EMAIL)) {
				$query2 .= "correo = '".$row['asignadoa']."'";
			}else{
				$query2 .= "correo IN (".$row['asignadoa'].") ";
			}
			$consulta = $mysqli->query($query2);
			while($rec = $consulta->fetch_assoc()){
				$asignadoaN .= $rec['nombre']." , ";
			}			
		}
		
		//ENVIAR CORREO DEL INCIDENTE A LOS USUARIOS SELECCIONADOS
		//4 para los usuarios que quieren que se les notifique (Enviar Notificacion a)
		if($notificar != '[]' && $notificar != ''){
			$asunto    = "Notificación del Incidente #$incidente";
			$notificar = json_decode($notificar);
			if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
				$correo [] = "$notificar";				
			}else{
				foreach($notificar as $notif){
					$correo [] = $notif;
				}
			}
		}
		//else{
			if($accion == 'creado'){
				$asunto = "Incidente #$incidente ha sido Creado";
			}else{ //actualizado
				if ($estadoold != $estadonew && $estadonew == 13)
					$asunto = "Incidente #$incidente ha sido Asignado";			
				elseif ($estadoold != $estadonew && $estadonew == 16)
					$asunto = "Incidente #$incidente ha sido Resuelto";			
				else
					$asunto = "Incidente #$incidente ha sido Actualizado";			
			}
		//}
		//DATOS DEL CORREO
		$usuarioSes = $_SESSION['usuario'];
		$consultaUA = $mysqli->query("SELECT nombre FROM usuarios WHERE usuario = '$usuarioSes' LIMIT 1 ");
		while ($registroUA = $consultaUA->fetch_assoc()) {
			$usuarioAct = $registroUA['nombre'];
		}
		//ESTADO ANTERIOR
		$consultaEO = $mysqli->query("SELECT nombre FROM estados WHERE id = '$estadoold' ");
		$registroEO = $consultaEO->fetch_assoc();
		$estadoant = $registroEO['nombre'];
		//ESTADO NUEVO
		$consultaEN = $mysqli->query("SELECT nombre FROM estados WHERE id = '$estadonew' ");
		$registroEN = $consultaEN->fetch_assoc();
		$estadonue = $registroEN['nombre'];
		
		//DATOS
		$fechacreacion 	= $row['fechacreacion'];
		$descripcion	= $row['descripcion'];
		$solicitante	= $row['solicitante'];
		$creadopor		= $row['creadopor'];
		$departamento	= $row['departamento'];
		$prioridad		= $row['prioridad'];
		$sitio 			= $row['unidadejecutora'];
		$resolucion 	= $row['resolucion'];
		$nasignadoa 	= $asignadoaN;
		//MENSAJE
		if($accion == 'creado'){
			$mensaje = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha creado el incidente #".$incidente."</b></p>";
		}else{ //actualizado
			$mensaje = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha actualizado el incidente #".$incidente."</b></p>";		
		}		
		
		if($estadonew == 13){
			$mensaje .= "<p style='padding-left: 30px;width:100%;'>El incidente ha sido asignado a: ".$nasignadoa."</p>";
		}elseif($estadoant !='' && $estadonue !=''){
			$mensaje .= "<p style='padding-left: 30px;width:100%;'>El Estado cambió de ".$estadoant." a ".$estadonue."</p>";
		}
		$mensaje .= "<p style='width:100%;'><a href='http://190.14.199.6/soporte/incidentes.php?id=".$incidente."' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Incidente</a></p>
						<br><br>
						<p style='font-size: 18px;width:100%;'>".$creadopor." ha creado este incidente el ".$fechacreacion."</p>
						<br>
						<p style='width:100%;'>".$descripcion."</p>
						<br>
						<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin: auto;padding: 10px;width:100%;'>Atributos</p>
						<table style='width: 50%;'>
							<tr>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Solicitante del servicio</div>".$solicitante."</td>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Sitio</div>".$sitio."</td>
							</tr>
							<tr>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Recibido en</div>".$fechacreacion."</td>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Departamento</div>".$departamento."</td>
							</tr>
							<tr>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Asignado a</div>".$nasignadoa."</td>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Prioridad</div>".$prioridad."</td>
							</tr>
						<table>
						";
			if($estadonew == 16 || $estadonew == 17){
				//GENERAR FECHA DE CIERRE 
				$query = "  UPDATE incidentes SET fechacierre = DATE_ADD(fecharesolucion, INTERVAL 3 DAY), horacierre = horaresolucion, 
							estado = 16 WHERE id = '".$incidente."' ";
				$mysqli->query($query);
				$mensaje .= "<br><br><p style='width:100%;'><b>Resolución: </b>".$resolucion."</p>";	
			}
			
			$mensaje .= "</div>";

		//USUARIOS DE SOPORTE
		//$correo [] = 'ana.porras@maxialatam.com';
		$correo [] = 'isai.carvajal@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		enviarMensajeIncidente($asunto,$mensaje,$correo);
	}
	
	//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
	function notificarUsuarios2($incidente,$notificar,$accion){
		global $mysqli;
		
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, c.codigo AS codigounidad, c.unidad AS unidadejecutora,
					a.serie, d.marca, d.modelo, e.nombre AS estado, f.id AS idcategoria, f.nombre AS categoria, g.nombre AS subcategoria,
					h.prioridad, a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, IFNULL(j.nombre, a.solicitante) AS solicitante, a.asignadoa,
					a.departamento, d.modalidad, a.satisfaccion, a.comentariosatisfaccion, a.resolucion, IFNULL(k.nombre, a.resueltopor) AS resueltopor,
					IF(a.fechacreacion!='',CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion,
					IF(a.fechavencimiento!='',CONCAT(a.fechavencimiento,'  ', a.horavencimiento),'') AS fechavencimiento,
					IF(a.fecharesolucion!='',CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,
					IF(a.fechacierre!='',CONCAT(a.fechacierre,'  ', a.horacierre),'') AS fechacierre,
					a.fechamodif, a.fechacertificar, 
					a.horastrabajadas, a.periodo, a.comentariovisto,					
					IFNULL(i.correo, a.creadopor) AS correocreadopor,
					IFNULL(j.correo, a.solicitante) AS correosolicitante
					FROM incidentes a
					LEFT JOIN proyectos b ON a.proyecto = b.id
					LEFT JOIN unidades c ON a.unidadejecutora = c.codigo
					LEFT JOIN activos d ON a.serie = d.codequipo
					LEFT JOIN estados e ON a.estado = e.id
					LEFT JOIN categorias f ON a.idcategoria = f.id
					LEFT JOIN subcategorias g ON a.idsubcategoria = g.id
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.id OR a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.resueltopor = k.correo 
					WHERE a.id = $incidente ";
					
		$result = $mysqli->query($query);
		$row 	= $result->fetch_assoc();
		
		$fcreacion 	= date_create($row['fechacreacion']);
		$fcierre	= date_create($row['fechacierre']);
		if($fcierre == ''){
			$fcierre = date('Y-m-d');
		}
		$interval = date_diff($fcreacion, $fcierre);
		$dif = $interval->format('%R%a días');
		
		//1 para quien quien creo el incidentes (Creado por)
		$correo [] = $row['correocreadopor'];
		
		//2 para quien solicito o reporto el incidente (Solicitante)
		$correo [] = $row['correosolicitante'];
		
		//3 para quien se le asigno el incidente (Asignado a)		
		
		//USUARIO O GRUPO DE USUARIOS ASIGNADOS		
		$asignadoaN	= '';		
		if($row['asignadoa'] != ''){
			$asignadoa  = $row['asignadoa'];
			if (filter_var($asignadoa, FILTER_VALIDATE_EMAIL)) {
				$correo [] = "$asignadoa";				
			}else{
				foreach([$asignadoa] as $asig){
					$correo [] = $asig;
				}
			}			
			$query2 = " SELECT nombre FROM usuarios WHERE ";
			if (filter_var($row['asignadoa'], FILTER_VALIDATE_EMAIL)) {
				$query2 .= "correo = '".$row['asignadoa']."'";
			}else{
				$query2 .= "correo IN (".$row['asignadoa'].") ";
			}
			$consulta = $mysqli->query($query2);
			while($rec = $consulta->fetch_assoc()){
				$asignadoaN .= $rec['nombre']." , ";
			}			
		}
		
		//ENVIAR CORREO DEL INCIDENTE A LOS USUARIOS SELECCIONADOS
		//4 para los usuarios que quieren que se les notifique (Enviar Notificacion a)
		if($notificar != '[]' && $notificar != ''){
			$asunto    = "Notificación del Incidente #$incidente";
			$notificar = json_decode($notificar);
			if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
				$correo [] = $notificar;				
			}else{
				foreach($notificar as $notif){
					$correo [] = $notif;
				}
			}
		}
		else{
			if($accion == 'creado'){
				$asunto = "Incidente #$incidente ha sido Creado";
			}else{ //actualizado
				$asunto = "Incidente #$incidente ha sido Actualizado";			
			}
		}

		$mensaje = "<table border=0>
						<tr><td colspan=4>&nbsp;</td></tr>
						<tr><td colspan=4>&nbsp;</td></tr>
						<tr><td colspan=4><b>Incidente </b> #$incidente </td></tr>
						<tr><td colspan=4>&nbsp;</td></tr>
						<tr><td colspan=4><b>Estado:</b> ".$row['estado']."</td></tr>
						<tr><td colspan=4><b>Titulo:</b> ".$row['titulo']."</td></tr>
						<tr><td colspan=4><b>Descripción:</b> ".$row['descripcion']."</td></tr>
						<tr><td colspan=4><b>Proyecto:</b> ".$row['proyecto']."</td></tr>
						<tr><td colspan=4><b>Unidad Ejecutora:</b> ".$row['unidadejecutora']."</td></tr>
						<tr><td colspan=4><b>Serie:</b> ".$row['serie']."</td></tr>
						<tr><td colspan=4><b>Marca:</b> ".$row['marca']."</td></tr>
						<tr><td colspan=4><b>Modelo:</b> ".$row['modelo']."</td></tr>
						<tr><td colspan=4><b>Categoría:</b> ".$row['categoria']."</td></tr>
						<tr><td colspan=4><b>Subcategoría:</b> ".$row['subcategoria']."</td></tr>
						<tr><td colspan=4><b>Prioridad:</b> ".$row['prioridad']."</td></tr>
						<tr><td colspan=4><b>Origen:</b> ".$row['origen']."</td></tr>
						<tr><td colspan=4><b>Creado por:</b> ".$row['creadopor']."</td></tr>
						<tr><td colspan=4><b>Solicitante:</b> ".$row['solicitante']."</td></tr>
						<tr><td colspan=4><b>Asignado a:</b> ".$asignadoaN."</td></tr>
						<tr><td colspan=4><b>Departamento:</b> ".$row['departamento']."</td></tr>
						<tr><td colspan=4><b>Modalidad:</b> ".$row['modalidad']."</td></tr>
						<tr><td colspan=4><b>Resolución:</b> ".$row['resolucion']."</td></tr>
						<tr><td colspan=4><b>Resuelto por:</b> ".$row['resueltopor']."</td></tr>
						<tr><td colspan=4><b>Fecha de Creación:</b> ".$row['fechacreacion']."</td></tr>
						<tr><td colspan=4><b>Fecha Vencimiento:</b> ".$row['fechavencimiento']."</td></tr>
						<tr><td colspan=4><b>Fecha de Cierre:</b> ".$row['fechacierre']."</td></tr>
						<tr><td colspan=4><b>Tiempo de Servicio:</b> ".$dif."</td></tr>
						<tr><td colspan=4><b>Horas Trabajadas:</b> ".$row['horastrabajadas']."</td></tr>
						<tr><td colspan=4><b>Periodo:</b> ".$row['periodo']."</td></tr>
						<tr><td colspan=4>&nbsp;</td></tr>
						<tr><td colspan=4>&nbsp;</td></tr>
					</table>";
		enviarMensajeIncidente($asunto,$mensaje,$correo);
	}
	
	function crearMensajeSatisfaccion($incidente,$titulo,$solicitante){
		global $mysqli;

		/*$query = "SELECT nombre, correo FROM usuarios WHERE id= '$solicitante'";
		$consulta = $mysqli->query($query);
		if($result->num_rows > 0){
			$rec = $consulta->fetch_assoc()) 
			$correo = $rec['correo'];
			$nombre = $rec['nombre'];
		}*/
		//para quien solicito o reporto el incidente (Solicitante)
		if (filter_var($solicitante, FILTER_VALIDATE_EMAIL)) {
				$correo [] = $solicitante;
		}else{
			$result = $mysqli->query("SELECT nombre,correo FROM usuarios WHERE id = '$solicitante'");
			while ($row=$result->fetch_assoc()) {
				$correo [] = $row['correo'];
			}
		}		
		//$correo = $solicitante;
		
		$asunto = "Satisfacción del Incidente #$incidente";

		$mensajeHtml = "<table border=0>
							<tr><td colspan=4>&nbsp;</td></tr>
							<tr><td colspan=4>&nbsp;</td></tr>
							<tr><td colspan=4>Incidente #$incidente</td></tr>
							<tr><td colspan=4>Titulo: $titulo</td></tr>
							<tr><td colspan=4>&nbsp;</td></tr>
							<tr><td colspan=4>&nbsp;</td></tr>
							<tr><td colspan=>¿Est&aacute; satisfecho con la soluc&oacute;n del Incidente?</td></tr>
							<tr><td colspan=4><br/></td></tr>
							<tr>
								<td colspan=4>
								<a style='background-color:#4caf50;border:medium none;border-radius:3px;color:white;font-size:14px;height:30px;text-decoration:none;padding-top:7px;padding-bottom:7px;padding-left:20px;padding-right:20px' href='http://web.maxialatam.com:8010/soporte/satisfaccion.php?oper=1' target='_blank'>Si</a>
								<a style='background-color:#ef5401;border:medium none;border-radius:3px;color:white;font-size:14px;height:30px;text-decoration:none;padding-top:7px;padding-bottom:7px;padding-left:20px;padding-right:20px' href='http://web.maxialatam.com:8010/soporte/satisfaccion.php?oper=0' target='_blank'>No</a>
								</td>
							</tr>
							<tr><td colspan=4><br/></td></tr>
							<tr><td colspan=4>&nbsp;</td></tr>
						</table>";
		
		enviarMensajeIncidente($asunto,$mensajeHtml,$correo);
	}
	
	//ENVIO DE CORREO SI HAY INCIDENTES VENCIDOS
	function verificarVencidos(){
		global $mysqli;

		$query  = "SELECT a.id, a.titulo,
					CONCAT_WS ('',b.correo, e.correo, IF(RIGHT(a.asignadoa,2) = '-G' OR RIGHT(a.asignadoa,2) = '-U', '', a.asignadoa)) AS asignadoa,
					CONCAT(a.fechacreacion,' ', horacreacion), a.fechavencimiento, f.nombre
					FROM incidentes a
					LEFT JOIN usuarios b ON REPLACE(a.asignadoa,'-G','') = b.id AND RIGHT(a.asignadoa,2) = '-U'
					LEFT JOIN grupos c ON REPLACE(a.asignadoa,'-U','')= c.id AND RIGHT(a.asignadoa,2) = '-G'
					LEFT JOIN gruposusuarios d ON c.id = d.idgrupo
					LEFT JOIN usuarios e ON d.idusuario = e.id
					LEFT JOIN usuariosincidentes f ON a.solicitante = f.id OR a.solicitante = f.correo
					WHERE fechavencimiento < CURDATE() AND a.estado NOT IN (16,17)";

		$result = $mysqli->query($query);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				if($row['fechavencimiento'] < date('Y-m-d') ) {
					if (filter_var($row['asignadoa'], FILTER_VALIDATE_EMAIL)) {
						$incidente 	= $row['incidente'];
						$titulo 	= $row['titulo'];
						$fcreacion 	= $row['fechacreacion'];
						$correo 	= $row['asignadoa'];
						$nombre 	= $row['nombre'];

						$asunto = "Incidente #$incidente VENCIDO - Soporte Maxia Toolkit";

						$mensajeHtml = "<table border=0>
											<tr><td colspan=4>Maxia Toolkit</td></tr>
											<tr><td colspan=4>Gesti&oacute;n de Soporte</td></tr>
											<tr><td colspan=4>&nbsp;</td></tr>
											<tr><td colspan=4>Incidente #$incidente</td></tr>
											<tr><td colspan=4>Titulo: $titulo</td></tr>
											<tr><td colspan=4>Solicitado por: $nombre</td></tr>
											<tr><td colspan=4>El d&iacute;a: $fcreacion</td></tr>
											<tr><td colspan=4>&nbsp;</td></tr>
											<tr><td colspan=4>&nbsp;</td></tr>";

						$mensajeHtml .= '	<tr><td colspan=>No est&aacute; a&uacute;n Resuelto y su fecha limite de cumplimiento establecida es el '.$fechavencimiento.'. Por favor Resolver en la brevedad posible Y dejar un comentario en Incidente mencionado acerca del motivo por el cual no ha sido Resuelto. </td></tr>
											<tr><td colspan=4>Gracias </td></tr>
											<tr><td colspan=4>&nbsp;</td></tr>';
						$mensajeHtml .= '</table>';
						enviarMensajeIncidente($asunto,$mensajeHtml,$correo);
					}
				}
			}
		}
	}

	function enviarMensajeIncidente($asunto,$mensaje,$correos) {
		global $mysqli, $mail;
		$correo = array_unique($correos);
		
		$cuerpo = "";
		$cuerpo .= "<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; '>";
		$cuerpo .= "<img src='http://web.maxialatam.com:8010/repositorio-tema/assets/img/maxia.jpg' style='width: initial;height: 60px;float: left; position: absolute !important;'>";
		$cuerpo .= "<p style='margin:auto; font-weight:bold; width: 100%; text-align: center;'>Maxia Toolkit<br>";
		$cuerpo .= "Gestión de Soporte<br>";
		$cuerpo .= "</div>";
		$cuerpo .= $mensaje;
		$cuerpo .= "<div style='background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;'>";
		$cuerpo .= "© ".date('Y')." Maxia Latam";
		//$cuerpo .= "<p style='color: #eeeeee;font-size: 10px;padding: 0;margin: 0;'>".json_encode($correo)."</p>";		
		$cuerpo .= "</div>";
		
		//$correo = array('lismary.18@gmail.com');
		//$correo [] = 'lismy_18@hotmail.com';		
		
		foreach($correo as $destino){
		   $mail->addAddress($destino);
		}
		//$mail->addAddress($correo);
		//$mail->addReplyTo('daniel.coronel@maxialatam.com', 'Daniel Coronel');
		//$mail->addCC('');
		//$mail->addBCC('');		
		$mail->FromName = "Maxia Toolkit - SYM";
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = $asunto;
		//$mail->MsgHTML($cuerpo);
		$mail->Body = $cuerpo;
		$mail->AltBody = "Maxia Toolkit - SYM: $asunto";
		if(!$mail->send()) {
			echo 'Mensaje no pudo ser enviado. ';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			//echo 'Ha sido enviado el correo Exitosamente';
			echo true;
		}
	}
	
	function fusionarIncidentes()
	{
		global $mysqli;
		$fusioninc 		= $_REQUEST['fusioninc'];
		$idincidentes 	= json_decode($_REQUEST['idincidentes']);

		if($fusioninc != ''){
			//$fusioninc = explode('/',$fusioninc);
			foreach($idincidentes as $incidente){
				//$incidente = explode('/',$incidente);
				//$query = "DELETE FROM incidentes WHERE id = '$incidente' ";
				$query = "UPDATE incidentes SET proyecto = '12', idcategoria = '37', idsubcategoria = '0', estado = 16, fusionado = ".$fusioninc." 
						  WHERE id = '".$incidente."'";
				if($mysqli->query($query)){
					//bitacora($_SESSION['usuario'], "Incidentes", $fusioninc, "El Incidente ".$fusioninc." fue fusionado con: ".$incidente);
					bitacora($_SESSION['usuario'], "Incidentes", 'El Incidente #'.$fusioninc.' se fusiono con: '.$incidente, $fusioninc, $query);
					bitacora($_SESSION['usuario'], "Incidentes", 'El Incidente #'.$incidente.' fue fusionado con: '.$incidente, $incidente, $query);
					echo true;
				}else{
					echo false;
				}
			}
		}else{
			echo false;
		}
	}

	function revertirfusion()
	{
		global $mysqli;
		$id 		= $_REQUEST['id'];
		$incidente	= $_REQUEST['incidente'];
		$fusionado  = $_REQUEST['fusionado'];

		if($id != ''){
			//$query = "DELETE FROM incidentes WHERE id = '$incidente' ";
			$query = "UPDATE incidentes SET idcategoria = 0, estado = 14, fusionado = '0' WHERE id = '$id' ";
			if($mysqli->query($query)){
				//bitacora($_SESSION['usuario'], "Incidentes", $fusioninc, "El Incidente ".$fusioninc." fue fusionado con: ".$incidente);
				bitacora($_SESSION['usuario'], "Incidentes", 'El Incidente #'.$incidente.' se Revirtió la Fusión con: '.$fusionado, $id, $query);
				bitacora($_SESSION['usuario'], "Incidentes", 'El Incidente #'.$fusionado.' se Revirtió la Fusión con: '.$incidente, $id, $query);
				echo true;
			}else{
				echo false;
			}
		}else{
			echo false;
		}
	}

	function historial()
	{
		global $mysqli;
		$id 	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$page 	 = $_GET['page']; // get the requested page
		$limit 	 = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx 	 = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord 	 = $_GET['sord']; // get the direction
		$where 	 = "";

		if(!$sidx) $sidx =1;

		if ($_GET['_search'] == 'true' && !isset($_GET['filters'])) {
			$searchField = $_GET['searchField'];
			$searchOper = $_GET['searchOper'];
			$searchString = $_GET['searchString'];
			$where = getWhereClause($searchField,$searchOper,$searchString);
		} elseif ($_GET['_search'] == 'true') {
			$filters = $_GET['filters'];
			$where = getWhereClauseFilters($filters);
		}
		//$where = str_replace('proyecto', ' a.idproyecto', $where);

		$query  = "SELECT id, usuario, fecha, accion
					FROM bitacora
					WHERE modulo = 'Incidentes' AND identificador = $id ";

		$query .= "$where ORDER BY 1 ";

		if ($sidx!="")
		//$query .= "ORDER BY $sidx $sord ";
		$result = $mysqli->query($query);
		$count = $result->num_rows;

		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 1;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)

		$query .= " LIMIT ".$start.", ".$limit;
		$result = $mysqli->query($query);

		$response = new StdClass;

		$response->page = $page;
		$response->total = $total_pages;
		$response->records = $count;
		$i=0;
		while($row = $result->fetch_assoc()){
			$response->rows[$i]['id']=$row['id'];
			$response->rows[$i]['cell']=array('',$row['id'], $row['usuario'], $row['fecha'], $row['accion']);
			$i++;
		}
		echo json_encode($response);
	}
	
	function exportarExcel() 
	{
		global $mysqli;
		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		//date_default_timezone_set('Europe/London');
		
		$id 	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');		
		//$desde = $_REQUEST['desde'];
		//$hasta = $_REQUEST['hasta'];
		
		/*if($id == '')
			exit();*/

		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

		/** Include PHPExcel */
		//require_once dirname(__FILE__) . '../../repositorio-lib/xls/Classes/PHPExcel.php';
		require_once '../../repositorio-lib/xls/Classes/PHPExcel.php';

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maxia Latam")
		->setLastModifiedBy("Maxia Latam")
		->setTitle("Reporte de Incidentes")
		->setSubject("Reporte de Incidentes")
		->setDescription("Reporte de Incidentes")
		->setKeywords("Reporte de Incidentes")
		->setCategory("Reportes");
		
		//ESTILOS
		$styleArray = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);
		$fontColor = new PHPExcel_Style_Color();
		$fontColor->setRGB('ffffff');

		$fontGreen = new PHPExcel_Style_Color();
		$fontGreen->setRGB('00b355');
		$fontRed = new PHPExcel_Style_Color();
		$fontRed->setRGB('ff0000');
		
		$style = array(
				'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				)
		);
		$style2 = array(
				'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				)
		);

		//TITULO	
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Reporte de Incidentes');
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($style);
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:AE1');
		
		// ENCABEZADO 
		$objPHPExcel->getActiveSheet()->setCellValue('A4', '# Incidente')
		->setCellValue('B4', 'Estado')
		->setCellValue('C4', 'Titulo')
		->setCellValue('D4', 'Descripción')
		->setCellValue('E4', 'Proyecto')
		->setCellValue('F4', 'Unidad Ejecutora')
		->setCellValue('G4', 'Equipo')
		->setCellValue('H4', 'Serie')
		->setCellValue('I4', 'Activo')
		->setCellValue('J4', 'Marca')
		->setCellValue('K4', 'Modelo')
		->setCellValue('L4', 'Categoría')
		->setCellValue('M4', 'Subcategoría')
		->setCellValue('N4', 'Prioridad')
		->setCellValue('O4', 'Origen')
		->setCellValue('P4', 'Creado por')
		->setCellValue('Q4', 'Solicitante')
		->setCellValue('R4', 'Asignado a')
		->setCellValue('S4', 'Departamento')
		->setCellValue('T4', 'Modalidad')
		->setCellValue('U4', 'Satisfacción')
		->setCellValue('V4', 'Comentario de Satisfacción')
		->setCellValue('W4', 'Resolución')
		->setCellValue('X4', 'Resuelto por')
		->setCellValue('Y4', 'Fecha de creación')
		->setCellValue('Z4', 'Hora de creación')
		->setCellValue('AA4', 'Fecha de vencimiento')
		->setCellValue('AB4', 'Hora de vencimiento')
		->setCellValue('AC4', 'Fecha de resolución')
		->setCellValue('AD4', 'Hora de resolución')
		->setCellValue('AE4', 'Fecha de cierre')
		->setCellValue('AF4', 'Hora de cierre')
		->setCellValue('AG4', 'Tiempo de servicio')
		->setCellValue('AH4', 'Horas Trabajadas')
		->setCellValue('AI4', 'Periodo');
		
		//LETRA
		$objPHPExcel->getActiveSheet()->getStyle('A4:AI4')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
		$objPHPExcel->getActiveSheet()->getStyle("A4:AI4")->applyFromArray($style);
		//FONDO
		$objPHPExcel->getActiveSheet()->getStyle('A4:AI4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('63b9db');
		
		//SENTENCIA BASE
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, c.unidad AS unidadejecutora, 
					a.serie, a.activo, m.marca, m.modelo, e.nombre AS estado, f.nombre AS categoria, g.nombre AS subcategoria,
					h.prioridad, a.origen, a.creadopor, a.solicitante, a.asignadoa, a.departamento, m.modalidad, 
					a.satisfaccion, a.comentariosatisfaccion, a.resolucion, a.resueltopor,
					ifnull(a.fechacreacion, '') AS fechacreacion, ifnull(a.fechavencimiento, '') AS fechavencimiento, 
					ifnull(a.fecharesolucion, '') as fecharesolucion, a.horaresolucion,
					ifnull(a.fechacierre, '') as fechacierre, ifnull(a.fechamodif, '') as fechamodif, 
					a.horacreacion, a.horavencimiento, a.horacierre, a.horastrabajadas, a.periodo, m.equipo
					FROM incidentes a
					LEFT JOIN proyectos b ON a.proyecto = b.id
					LEFT JOIN unidades c ON a.unidadejecutora = c.codigo
					LEFT JOIN estados e ON a.estado = e.id
					LEFT JOIN categorias f ON a.idcategoria = f.id
					LEFT JOIN subcategorias g ON a.idsubcategoria = g.id
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN activos m ON a.serie = m.codequipo
					WHERE a.idcategoria not in (12,22,35,43) ";
		
		//DATOS 
		$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Incidentes' AND usuario = '".$_SESSION['usuario']."'";
		$resultF = $mysqli->query($queryF);
		if($resultF->num_rows >0){
			$rowF = $resultF->fetch_assoc();				
			if (!isset($_REQUEST['data'])) {
				$data = $rowF['filtrosmasivos'];
			}
		}
		$where2 = '';
		if($data != ''){
			$data = json_decode($data);
			if(!empty($data->filtrodesde)){
				$filtrodesde = json_encode($data->filtrodesde);
				$where2 .= " AND a.fechacreacion >= $filtrodesde ";
				//setcookie('filtrodesde', $filtrodesde, time() + 365 * 24 * 60 * 60, "/"); 
			}
			if(!empty($data->filtrohasta)){
				$filtrohasta = json_encode($data->filtrohasta);
				$where2 .= " AND a.fechacreacion <= $filtrohasta ";
				//setcookie('filtrohasta', $filtrohasta, time() + 365 * 24 * 60 * 60, "/"); 
			}
			if(!empty($data->filtrocat)){
				$filtrocat = json_encode($data->filtrocat);
				$where2 .= " AND a.idcategoria IN ($filtrocat)";
				//setcookie('filtrocat', $filtrocat, time() + 365 * 24 * 60 * 60, "/"); 
			}
			if(!empty($data->filtrosubcat)){
				$filtrosubcat = json_encode($data->filtrosubcat);
				$where2 .= " AND a.idsubcategoria IN ($filtrosubcat)";
				//setcookie('filtrosubcat', $filtrosubcat, time() + 365 * 24 * 60 * 60, "/"); 
			}			
			if(!empty($data->proyectof)){
				$proyectof = json_encode($data->proyectof);
				$where2 .= " AND a.proyecto IN ($proyectof)";
				//setcookie('proyectof', $proyectof, time() + 365 * 24 * 60 * 60, "/"); 
			}
			if(!empty($data->prioridadf)){
				$prioridadf = json_encode($data->prioridadf);
				$where2 .= " AND a.idprioridad IN ($prioridadf)";
				//setcookie('prioridadf', $prioridadf, time() + 365 * 24 * 60 * 60, "/"); 
			}
			if(!empty($data->filtromod)){
				$filtromod = json_encode($data->filtromod);
				$where2 .= " AND m.modalidad IN ($filtromod)";
				//setcookie('filtromod', $filtromod, time() + 365 * 24 * 60 * 60, "/"); 
			}
			if(!empty($data->filtromarca)){
				$filtromarca = json_encode($data->filtromarca);
				$where2 .= " AND m.marca IN ($filtromarca)";
				//setcookie('filtromarca', $filtromarca, time() + 365 * 24 * 60 * 60, "/"); 
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				$where2 .= " AND a.solicitante IN ($solicitantef)";
				//setcookie('solicitantef', $solicitantef, time() + 365 * 24 * 60 * 60, "/"); 
			}
			if(!empty($data->estadof)){
				$estadof = json_encode($data->estadof);
				$where2 .= " AND a.estado IN ($estadof)";
				//setcookie('estadof', $estadof, time() + 365 * 24 * 60 * 60, "/");
			}
			if(!empty($data->asignadoaf)){
				$asignadoaf = json_encode($data->asignadoaf);
				//setcookie('asignadoaf', $asignadoaf, time() + 365 * 24 * 60 * 60, "/");
				
				$asignadoaf = '';
				$i = 0;
				foreach($data->asignadoaf as $usuarios){
					if($i > 0)
						$asignadoaf .=",";
					$asignadoaf .= "'$usuarios'";
					$i++;
				}
				$where2 .= " AND a.asignadoa IN ($asignadoaf)";	
				 
			}
			if(!empty($data->unidadejecutoraf)){
				$unidadejecutoraf = json_encode($data->unidadejecutoraf);
				$where2 .= " AND a.unidadejecutora IN ($unidadejecutoraf)";
				//setcookie('unidadejecutoraf', $unidadejecutoraf, time() + 365 * 24 * 60 * 60, "/"); 
			}
					
			$vowels = array("[", "]");
			$where2 = str_replace($vowels, "", $where2);
		}
		
		//CUERPO
		//Definir fuente
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);					
		
		/*
		if($_SESSION['nivel'] == 3) {
			$query  .= "AND k.usuario = '".$_SESSION['usuario']."' ";
		} elseif($_SESSION['nivel'] == 4){
			$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.unidadejecutora = '".$_SESSION['sitio']."') ";
		}
		
		if($id != ''){
			$query  .= "AND a.id IN ($id) ";
		}*/
		$query  .= $_SESSION['whereColumnasInc']." $where2 ORDER BY a.id desc ";
		
		$result = $mysqli->query($query);
		$i = 5;
		
		while($row = $result->fetch_assoc()){
			$fcreacion 	= date_create($row['fechacreacion'].' '.$row['horacreacion']);
			$fecharesolucion = date_create($row['fecharesolucion'].' '.$row['horaresolucion']); 
			if($fecharesolucion == ''){
				$fecharesolucion = date('Y-m-d');
			}
			$interval = date_diff($fcreacion, $fecharesolucion);
			$dif = $interval->format('%d d %h h');
			
			//USUARIO O GRUPO DE USUARIOS ASIGNADOS		
			$asignadoaN	= '';		
			if($row['asignadoa'] != ''){			
				$query2 = " SELECT nombre FROM usuarios WHERE ";
				if (filter_var($row['asignadoa'], FILTER_VALIDATE_EMAIL)) {
					$query2 .= "correo = '".$row['asignadoa']."'";
				}else{
					$query2 .= "correo IN ('".$row['asignadoa']."') ";
				}
				$consulta = $mysqli->query($query2);
				while($rec = $consulta->fetch_assoc()){
					$asignadoaN .= $rec['nombre']." , ";
				}			
			}
			
			// conversion de formatos de fecha
			$xfechacreacion = $row['fechacreacion'];
			$xfechavencimiento = $row['fechavencimiento'];
			$xfecharesolucion = $row['fecharesolucion'];
			$xfechacierre = $row['fechacierre'];
			
			if ($row['fechacreacion']!='') {
				$xfechacreacion = date_create_from_format('Y-m-d', $row['fechacreacion']);
				$xfechacreacion = date_format($xfechacreacion, "m/d/Y");
			}
			if ($row['fechavencimiento']!='') {
				$xfechavencimiento = date_create_from_format('Y-m-d', $row['fechavencimiento']);
				$xfechavencimiento = date_format($xfechavencimiento, "m/d/Y");
			}
			if ($row['fecharesolucion']!='') {
				$xfecharesolucion = date_create_from_format('Y-m-d', $row['fecharesolucion']);
				$xfecharesolucion = date_format($xfecharesolucion, "m/d/Y");
			}
			if ($row['fechacierre']!='') {
				$xfechacierre = date_create_from_format('Y-m-d', $row['fechacierre']);
				$xfechacierre = date_format($xfechacierre, "m/d/Y");
			}
			
			
			$numeroreq = str_pad($row['id'], 4, "0", STR_PAD_LEFT);		
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, '#'.$numeroreq)			
			->setCellValue('B'.$i, $row['estado'])
			->setCellValue('C'.$i, $row['titulo'])
			->setCellValue('D'.$i, $row['descripcion'])
			->setCellValue('E'.$i, $row['proyecto'])
			->setCellValue('F'.$i, $row['unidadejecutora'])
			->setCellValue('G'.$i, $row['equipo'])
			->setCellValue('H'.$i, $row['serie'])
			->setCellValue('I'.$i, $row['activo'])
			->setCellValue('J'.$i, $row['marca'])
			->setCellValue('K'.$i, $row['modelo'])
			->setCellValue('L'.$i, $row['categoria'])
			->setCellValue('M'.$i, $row['subcategoria'])
			->setCellValue('N'.$i, $row['prioridad'])
			->setCellValue('O'.$i, $row['origen'])
			->setCellValue('P'.$i, $row['creadopor'])
			->setCellValue('Q'.$i, $row['solicitante'])
			->setCellValue('R'.$i, $asignadoaN)
			->setCellValue('S'.$i, $row['departamento'])
			->setCellValue('T'.$i, $row['modalidad'])
			->setCellValue('U'.$i, $row['satisfaccion'])
			->setCellValue('V'.$i, $row['comentariosatisfaccion'])
			->setCellValue('W'.$i, $row['resolucion'])
			->setCellValue('X'.$i, $row['resueltopor'])
			->setCellValue('Y'.$i, $xfechacreacion) //->setCellValue('W'.$i, implode('/',array_reverse(explode('-', $row['fechacreacion']))))
			->setCellValue('Z'.$i, $row['horacreacion'])
			->setCellValue('AA'.$i, $xfechavencimiento) // ->setCellValue('Y'.$i, implode('/',array_reverse(explode('-', $row['fechavencimiento']))))
			->setCellValue('AB'.$i, $row['horavencimiento'])
			->setCellValue('AC'.$i, $xfecharesolucion)// ->setCellValue('AA'.$i, implode('/',array_reverse(explode('-', $row['fecharesolucion']))))
			->setCellValue('AD'.$i, $row['horaresolucion'])
			->setCellValue('AE'.$i, $xfechacierre) //->setCellValue('AC'.$i, implode('/',array_reverse(explode('-', $row['fechacierre']))))
			->setCellValue('AF'.$i, $row['horacierre'])
			->setCellValue('AG'.$i,$dif)
			->setCellValue('AH'.$i, $row['horastrabajadas'])
			->setCellValue('AI'.$i, $row['periodo']);			
			
			//ESTILOS
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':AI'.$i)->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':AI'.$i)->getAlignment()->applyFromArray(
						array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));
			$objPHPExcel->getActiveSheet()->getStyle('Y'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
			$objPHPExcel->getActiveSheet()->getStyle('Y'.$i)->getAlignment()->applyFromArray(
						array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
			$objPHPExcel->getActiveSheet()->getStyle('AE'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
			$objPHPExcel->getActiveSheet()->getStyle('AE'.$i)->getAlignment()->applyFromArray(
						array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
			$objPHPExcel->getActiveSheet()->getStyle('AA'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
			$objPHPExcel->getActiveSheet()->getStyle('AA'.$i)->getAlignment()->applyFromArray(
						array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
			$objPHPExcel->getActiveSheet()->getStyle('AC'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
			$objPHPExcel->getActiveSheet()->getStyle('AC'.$i)->getAlignment()->applyFromArray(
						array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
			$objPHPExcel->getActiveSheet()->getStyle('AG'.$i)->getAlignment()->applyFromArray(
						array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
			
			
			$i++;
		}

		//Ancho automatico
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);

		//Renombrar hoja de Excel
		$objPHPExcel->getActiveSheet()->setTitle('ReporteIncidentes');

		//Redirigir la salida al navegador del cliente		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="ReporteIncidentes.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();
	}
	
	function comentariovisto()
	{
		global $mysqli;

		$id = $_REQUEST['id'];
		$query = "UPDATE incidentes SET comentariovisto='1' WHERE id = '$id'";
		$mysqli->query($query);
	}
	
	function filtroGrid(){
		global $mysqli;
		$_SESSION['filtrogrid'] = '0';
		$usufiltroexiste = 0;
		$query = "SELECT filtro FROM usuariosfiltros WHERE modulo = 'Incidentes' AND usuario =".$_SESSION['user_id'];
		$result = $mysqli->query($query);
		if($result->num_rows >0){
			//if($where == ''){
				$row = $result->fetch_assoc();				
				$where = $row['filtro'];
			//}
			$usufiltroexiste = 1;
		}	
		if($where != ''){	
			$filtro = str_replace('AND','-',$where);
			$filtro = str_replace('LIKE','=',$filtro);
			$filtro = str_replace("%","",$filtro);
			$filtro = str_replace("'","",$filtro);
			$filtro = str_replace('a.id','id',$filtro);
			$filtro = str_replace('a.estado','estado',$filtro);
			$filtro = str_replace('a.titulo','titulo',$filtro);
			$filtro = str_replace('a.descripcion','descripcion',$filtro);
			$filtro = str_replace('a.proyecto','proyecto',$filtro);
			$filtro = str_replace('c.codigo','unidadejecutora',$filtro);
			$filtro = str_replace('a.activo','activo',$filtro);
			$filtro = str_replace('a.marca','marca',$filtro);
			$filtro = str_replace('a.modelo','modelo',$filtro);
			$filtro = str_replace('a.idcategoria','idcategoria',$filtro);
			$filtro = str_replace('a.idsubcategoria','idsubcategoria',$filtro);
			$filtro = str_replace('a.idprioridad','idprioridad',$filtro);
			$filtro = str_replace('a.origen','origen',$filtro);
			$filtro = str_replace('a.creadopor','creadopor',$filtro);
			$filtro = str_replace('a.solicitante','solicitante',$filtro);
			$filtro = str_replace('a.asignadoa','asignadoa',$filtro);
			$filtro = str_replace('a.departamento','departamento',$filtro);
			
			$filtro = explode('-',$filtro);
			$i=0;
			foreach($filtro as $v){
				if($v!=' '){
					$setset[$i] = explode('=',$v); 
					$setset[$i] = array('campo'=> trim($setset[$i][0])
										,'valor'=> trim($setset[$i][1])
										);
				}
				$i++;

			}
			//$_SESSION['filtrogrid'] = json_encode($setset);	
			echo json_encode($setset);	
		}
		//return [$where,$usufiltroexiste];
		//return $where;	
	}
	
	function limpiarFiltrosMasivos(){
		global $mysqli;
		$usuario = $_SESSION['usuario'];
		
		$query = "DELETE FROM usuariosfiltros WHERE modulo = 'Incidentes' AND usuario = '$usuario' ";
		if($mysqli->query($query))
			echo true;		
	}
	
	function guardarfiltros() {
		global $mysqli;
		$data = $_REQUEST['data'];
		$usuario = $_SESSION['usuario'];
		$query  = "SELECT *
					FROM usuariosfiltros
					WHERE modulo = 'Incidentes' AND usuario = '$usuario' ";

		$result = $mysqli->query($query);
		$count = $result->num_rows;
		
		if( $count > 0 ) 
			$query = "UPDATE usuariosfiltros SET filtrosmasivos = '$data' WHERE modulo = 'Incidentes' AND usuario = '$usuario'";
		else
			$query = "INSERT INTO usuariosfiltros VALUES (null, '$usuario', 'Incidentes', '', '$data')";
		debug($query);
		if($mysqli->query($query))
			echo true;
		
	}
	
	function abrirfiltros() {
		global $mysqli;
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Incidentes' AND usuario = '".$_SESSION['usuario']."'";
		$result = $mysqli->query($query);
		$response = new StdClass;
		if($result->num_rows >0){
			$row = $result->fetch_assoc();				
			$data = $row['filtrosmasivos'];
			$response->data = $data;
		} else {
			$response->data = '';
		}
		
		$response->success = true;
		echo json_encode($response);
	}
		

?>