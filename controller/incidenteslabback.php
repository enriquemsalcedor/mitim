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
		case "baseconocimiento":
			  baseconocimiento();
			  break;
		case "eliminarincidentes":
			  eliminarincidentes();
			  break;
	    case "eliminarcomentarios":
			  eliminarcomentarios();
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
		case "adjuntosComentarios":
			  adjuntosComentarios();
			  break;
		case "abrirIncidente":
			  abrirIncidente();
			  break;
		case "guardarIncidente":
			  guardarIncidente();
			  break;
		case "actualizarIncidente":
			  actualizarIncidente();
			  break;
		case "guardarIncidenteMasivo":
			  guardarIncidenteMasivo();
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
		case "notificacionAdjunto":
			 notificacionAdjunto();
			 break;
		case "comentariosleidos":
			 comentariosleidos();
			 break;
		case "exportarExcelConComentarios":
			 exportarExcelConComentarios();
			 break;
		default:
			  echo "{failure:true}";
			  break;
	}

	function incidentes()
	{
		global $mysqli;
		
		//FILTROS MASIVO
		$where = "";
		$where2 = "";
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		
		$draw = $_REQUEST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    $orderByColumnIndex  = $_REQUEST['order'][0]['column'];// index of the sorting column (0 index based - i.e. 0 is the first record)
	    $orderBy = $_REQUEST['columns'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
	    $orderType = $_REQUEST['order'][0]['dir']; // ASC or DESC
	    $start   = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Incidenteslab' AND usuario = '".$_SESSION['usuario']."'";
		$result = $mysqli->query($query);
		if($result->num_rows >0){
			$row = $result->fetch_assoc();				
			if (!isset($_REQUEST['data'])) {
				$data = $row['filtrosmasivos'];
			}
		}
		
		if($data != ''){
			$data = json_decode($data);
			if(!empty($data->desdef)){
				$desdef = json_encode($data->desdef);
				$where2 .= " AND a.fechacreacion >= $desdef ";
			} else {
				//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where2 .= " AND a.fechacreacion <= $hastaf ";
			} 			
			if(!empty($data->idempresasf)){
				$idempresasf = json_encode($data->idempresasf);
				$where2 .= " AND a.idempresas IN ($idempresasf)"; 
			}
			if(!empty($data->iddepartamentosf)){
				$iddepartamentosf = json_encode($data->iddepartamentosf);
				$where2 .= " AND a.iddepartamentos IN ($iddepartamentosf)"; 
			}
			if(!empty($data->idclientesf)){
				$idclientesf = json_encode($data->idclientesf);
				$where2 .= " AND a.idclientes IN ($idclientesf)"; 
			}
			if(!empty($data->idproyectosf)){
				$idproyectosf = json_encode($data->idproyectosf);
				$where2 .= " AND a.idproyectos IN ($idproyectosf)"; 
			}
			if(!empty($data->prioridadf)){
				$prioridadf = json_encode($data->prioridadf);
				$where2 .= " AND a.idprioridad IN ($prioridadf)";
			}
			if(!empty($data->modalidadf)){
				$modalidadf = $data->modalidadf;
				$where2 .= " AND a.modalidad like '%$modalidadf%' ";
			}
			if(!empty($data->marcaf)){
				$marcaf = $data->marcaf;
				$where2 .= " AND a.marca like '%$marcaf%' "; 
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				$where2 .= " AND a.solicitante IN ($solicitantef)";
			}
			if(!empty($data->estadof)){
				$estadof = json_encode($data->estadof);
				$where2 .= " AND a.estado IN ($estadof)";
			}
			if(!empty($data->asignadoaf)){
				$asignadoaf = json_encode($data->asignadoaf);
				
				$asignadoaf = '';
				$i = 0;
				foreach($data->asignadoaf as $usuarios){
					if($i > 0)
						$asignadoaf .=",";
					$asignadoaf .= "'$usuarios'";
					$i++;
				}
				if($asignadoaf != "''"){
					$where2 .= " AND a.asignadoa IN ($asignadoaf)";	
				}
			}
			if(!empty($data->sitiof)){
				$sitiof = $data->sitiof;
				$where2 .= " AND a.sitio like '%$sitiof%' ";
			}	
			$vowels = array("[", "]");
			$where2 = str_replace($vowels, "", $where2);
		}
		
		$usuario 		 = $_SESSION['usuario'];
		$nivel 			 = $_SESSION['nivel'];
		$idempresas 	 = $_SESSION['idempresas'];
		$iddepartamentos = $_SESSION['iddepartamentos'];
		$idclientes 	 = $_SESSION['idclientes'];
		$idproyectos 	 = $_SESSION['idproyectos'];		
		$query  = " SELECT a.id, e.nombre AS estado, LEFT(a.titulo,45) as titulo,
					IFNULL(j.nombre, a.solicitante) AS solicitante, 
					a.fechacreacion, a.horacreacion, a.fechacierre,
					b.nombre AS idproyectos,a.asignadoa, l.nombre AS nomusuario, 
					a.sitio, a.equipo, a.serie, a.marca, a.modelo, h.prioridad, a.fecharesolucion, 
					case when a.fechacierre IS NULL OR a.fechacierre = ''
					then a.fechacreacion else a.fechacierre end as fechaorden,
					n.descripcion as idempresas, o.nombre as iddepartamentos, 
					p.nombre as idclientes
					FROM incidenteslab a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN unidades c ON a.sitio = c.codigo
					LEFT JOIN estados e ON a.estado = e.id 
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					";
		if($nivel != 1 && $nivel != 2){
			$query .= " LEFT JOIN usuarios q ON find_in_set(c.codigo, q.sitio) AND q.usuario = '$usuario' ";
		} 
		$query  .= " WHERE 1 = 1 ";
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idempresas in ($idempresas) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idclientes in ($idclientes) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idproyectos in ($idproyectos) ";
		}
		if($nivel == 3) {
			$query  .= " AND (
							j.usuario = '".$_SESSION['usuario']."' OR 
							l.usuario = '".$_SESSION['usuario']."' OR
							FIND_IN_SET(a.iddepartamentos,(SELECT GROUP_CONCAT( DISTINCT ee.id SEPARATOR  ',' )			
														FROM usuarios a
														LEFT JOIN departamentos ee ON FIND_IN_SET(ee.id, a.iddepartamentos) AND ee.tipo = 'grupo'
														WHERE a.usuario = '".$_SESSION['usuario']."')) 
						)";
		}elseif($nivel == 4){
			if($_SESSION['sitio'] != ''){
				$sitio = $_SESSION['sitio'];
				$sitio = explode(',',$sitio);
				$sitio = implode("','", $sitio);
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.sitio IN ('".$sitio."') ) ";
			}else{
				//$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
			}
			
		}
		$hayFiltros = 0;
		for($i=0 ; $i<count($_REQUEST['columns']);$i++){
			$column = $_REQUEST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			if ($_REQUEST['columns'][$i]['search']['value']!="") {
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);
				
				if ($column == 'id') {
					$column = 'a.id';
					$where[]=" $column = '".$campo."' ";
				}
				if ($column == 'titulo') {
					$column = 'a.titulo';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'estado') {
					$column = 'e.nombre';
					$where[]=" $column like '%".$campo."%' ";
				}				
				if ($column == 'idempresas') {
					$column = 'n.descripcion';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'iddepartamentos') {
					$column = 'o.nombre';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'idclientes') {
					$column = 'p.nombre';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'idproyectos') {
					$column = 'b.nombre';
					$where[]=" $column like '%".$campo."%' ";
				} 
				if ($column == 'asignadoa') {
					$column = 'l.nombre';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'sitio') {
					$column = 'a.sitio';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'modalidad') {
					$column = 'a.modalidad';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'serie') {
					$column = 'a.serie';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'idprioridad') {
					$column = 'h.prioridad';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'solicitante') {
					$column = 'j.nombre';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'fechacierre') {
					$column = 'a.fechacierre';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'fechacreacion') {
					$column = 'a.fechacreacion';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'horacreacion') {
					$column = 'a.horacreacion';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'marca') {
					$column = 'a.marca';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'modelo') {
					$column = 'a.modelo';
					$where[]=" $column like '%".$campo."%' ";
				} 
				$hayFiltros++;
			}
		}
		if ($hayFiltros > 0)
			$where = " AND ".implode(" AND " , $where)." ";// id like '%searchValue%' or name like '%searchValue%'
		else
			$where = "";
		
		$query  .= " $where $where2";
		
		
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		$query  .= " ORDER BY a.id desc LIMIT $start, $length ";
		debug($query);
		$response = '';
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		while($row = $result->fetch_assoc()){
			$solicitante = $row['solicitante'];
			//ADJUNTOS INCIDENTES
			$tieneEvidencias   = '';
			$rutaE 		= '../incidenteslab/'.$row['id'];
			if (is_dir($rutaE)) { 
			  if ($dhE = opendir($rutaE)) { 
				$num = 1;
				while (($fileE = readdir($dhE)) !== false) { 
					if ($fileE != "." && $fileE != ".." && $fileE != ".quarantine" && $fileE != ".tmb" && $fileE != "comentarios"){ 
						$nombrefile = $fileE;
						if($num > 1){
							$tieneEvidencias .= ", ";
						}
						$tieneEvidencias .= "<a href='".dirname($_SERVER['PHP_SELF'])."/".$rutaE."/".$fileE."' target='_blank'>".$nombrefile."</a>";
						$num++;
					}						
				} 
				closedir($dhE); 
			  } 
			}
			if($tieneEvidencias != ''){
				$color = 'green';
			}else{
				$color = 'blue';
			}
			$coment = " SELECT visto FROM comentarioslab WHERE idmodulo = '".$row['id']."' ";			
			$rcomen = $mysqli->query($coment);			
			if($rcomen->num_rows > 0){
				while($row2 = $rcomen->fetch_assoc()){
					$coment2 = $coment.=" AND visto = 'NO'";
				
					$rcomen2 = $mysqli->query($coment2);
					//$contar  = $rcomen2->num_rows;
					if($rcomen2->num_rows > 0){
						$iconcoment = "<span class='icon-col green fa fa-comment  boton-coment-".$row['id']."' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Comentarios' data-placement='right'></span>";
					}else{
						$iconcoment = "<span class='icon-col blue fa fa-comment boton-coment-".$row['id']."' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Comentarios' data-placement='right'></span>";
					}
				}
			}else{
				$iconcoment = "";
			}
			
			if($nivel == 1 || $nivel == 2){
				$acciones = "<div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>
								<span class='icon-col red fa fa-trash boton-eliminar' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar' data-placement='right'></span>
								<span class='icon-col ".$color." fa fa-camera boton-evidencias' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Evidencias' data-placement='right'></span>											
								<span class= 'msj-".$row['id']."'>".$iconcoment."<span>
							</div>";
			}else{
				$acciones = "<div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>
								<span class='icon-col ".$color." fa fa-camera boton-evidencias' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Evidencias' data-placement='right'></span>											
								<span class= 'msj-".$row['id']."'>".$iconcoment."<span>
							</div>";
			}
			
			$response['data'][] = array(				
				'check' 			=>	"",
				'acciones' 			=> $acciones,
				'id' 				=> $row['id'],
				'estado' 			=> $row['estado'],
				'titulo' 			=> $row['titulo'],
				'solicitante'		=> $solicitante,
				'fechacreacion' 	=> $row['fechacreacion'],
				'horacreacion'		=> $row['horacreacion'],
				'idempresas'		=> $row['idempresas'],
				'iddepartamentos'	=> $row['iddepartamentos'],
				'idclientes'		=> $row['idclientes'],
				'idproyectos'		=> $row['idproyectos'],
				'idcategoria'		=> $row['categoria'],
				'idsubcategoria'	=> $row['subcategoria'],
				'asignadoa'			=> $row['nomusuario'],
				'sitio'				=> $row['sitio'],
				'equipo'			=> $row['equipo'],
				'serie'				=> $row['serie'],
				'marca'				=> $row['marca'],
				'modelo'			=> $row['modelo'],
				'idprioridad'		=> $row['prioridad'],
				'fechacierre'		=> $row['fechacierre']
			);
		}
		if($response == ''){
			$response['data'][] = array(				
				'check' => "", 'acciones' => "",'id' => "", 'estado' => "", 'titulo' => "",
				'solicitante' => "",'fechacreacion' => "", 'horacreacion' => "",
				'idempresas' => "", 'iddepartamentos' => "", 'idclientes' => "", 'idproyectos' => "", 
				'idcategoria' => "", 'idsubcategoria' => "", 'asignadoa' => "", 'sitio' => "", 'equipo' => "",
				'serie' => "", 'marca' => "", 'modelo' => "", 'idprioridad' => "",
				'fechacierre' => ""
			);
		}
		$response['draw'] = intval($draw);
		$response['recordsTotal'] = intval($recordsTotal);
		$response['recordsFiltered'] = intval($recordsTotal);
		//print_r($response);
		echo json_encode($response);
	}
	
	function baseconocimiento()
	{
		global $mysqli;
		
		//FILTROS MASIVO
		$where 	= "";
		$where2 = "";
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		
		$draw = $_REQUEST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    $orderByColumnIndex  = $_REQUEST['order'][0]['column'];// index of the sorting column (0 index based - i.e. 0 is the first record)
	    $orderBy = $_REQUEST['columns'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
	    $orderType = $_REQUEST['order'][0]['dir']; // ASC or DESC
	    $start   = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Incidenteslab' AND usuario = '".$_SESSION['usuario']."'";
		$result = $mysqli->query($query);
		if($result->num_rows >0){
			$row = $result->fetch_assoc();				
			if (!isset($_REQUEST['data'])) {
				$data = $row['filtrosmasivos'];
			}
		}
		
		if($data != ''){
			$data = json_decode($data);
			if(!empty($data->desdef)){
				$desdef = json_encode($data->desdef);
				$where2 .= " AND a.fechacreacion >= $desdef ";
			} else {
				//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where2 .= " AND a.fechacreacion <= $hastaf ";
			}
			if(!empty($data->categoriaf)){
				$categoriaf = json_encode($data->categoriaf);
				$where2 .= " AND a.idcategoria IN ($categoriaf)";
			}
			if(!empty($data->subcategoriaf)){
				$subcategoriaf = json_encode($data->subcategoriaf);
				$where2 .= " AND a.idsubcategoria IN ($subcategoriaf)";
			}			
			if(!empty($data->idempresasf)){
				$idempresasf = json_encode($data->idempresasf);
				$where2 .= " AND a.idempresas IN ($idempresasf)"; 
			}
			if(!empty($data->iddepartamentosf)){
				$iddepartamentosf = json_encode($data->iddepartamentosf);
				$where2 .= " AND a.iddepartamentos IN ($iddepartamentosf)"; 
			}
			if(!empty($data->idclientesf)){
				$idclientesf = json_encode($data->idclientesf);
				$where2 .= " AND a.idclientes IN ($idclientesf)"; 
			}
			if(!empty($data->idproyectosf)){
				$idproyectosf = json_encode($data->idproyectosf);
				$where2 .= " AND a.idproyectos IN ($idproyectosf)"; 
			}
			if(!empty($data->prioridadf)){
				$prioridadf = json_encode($data->prioridadf);
				$where2 .= " AND a.idprioridad IN ($prioridadf)";
			}
			if(!empty($data->modalidadf)){
				$modalidadf = json_encode($data->modalidadf);
				$where2 .= " AND m.modalidad IN ($modalidadf)";
			}
			if(!empty($data->marcaf)){
				$marcaf = json_encode($data->marcaf);
				$where2 .= " AND a.marca IN ($marcaf)"; 
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				$where2 .= " AND a.solicitante IN ($solicitantef)";
			}
			if(!empty($data->estadof)){
				$estadof = json_encode($data->estadof);
				$where2 .= " AND a.estado IN ($estadof)";
			}
			if(!empty($data->asignadoaf)){
				$asignadoaf = json_encode($data->asignadoaf);
				
				$asignadoaf = '';
				$i = 0;
				foreach($data->asignadoaf as $usuarios){
					if($i > 0)
						$asignadoaf .=",";
					$asignadoaf .= "'$usuarios'";
					$i++;
				}
				if($asignadoaf != "''"){
					$where2 .= " AND a.asignadoa IN ($asignadoaf)";	
				}
			}
			if(!empty($data->sitiof)){
				$sitiof = json_encode($data->sitiof);
				 if($sitiof !== '[""]'){ 
					$where2 .= " AND a.sitio IN ($sitiof)";
				}
			}	
			$vowels = array("[", "]");
			$where2 = str_replace($vowels, "", $where2);
		}
		
		$usuario 		 = $_SESSION['usuario'];
		$nivel 			 = $_SESSION['nivel'];
		$idempresas 	 = $_SESSION['idempresas'];
		$iddepartamentos = $_SESSION['iddepartamentos'];
		$idclientes 	 = $_SESSION['idclientes'];
		$idproyectos 	 = $_SESSION['idproyectos'];		
		$query  = " SELECT a.id, e.nombre AS estado, LEFT(a.titulo,45) as titulo,
					LEFT(a.descripcion,45) as descripcion,
					LEFT(a.resolucion,45) as resolucion,
					LEFT(a.observaciones,45) as observaciones,
					IFNULL(j.nombre, a.solicitante) AS solicitante, 
					a.fechacreacion, a.horacreacion, a.fechacierre,
					b.nombre AS idproyectos, f.nombre AS categoria, g.nombre AS subcategoria,
					a.asignadoa, l.nombre AS nomusuario, a.sitio, a.serie, 
					a.marca, a.modelo, h.prioridad, a.fecharesolucion, 
					case when a.fechacierre IS NULL OR a.fechacierre = ''
					then a.fechacreacion else a.fechacierre end as fechaorden,
					n.descripcion as idempresas, o.nombre as iddepartamentos, 
					p.nombre as idclientes
					FROM incidenteslab a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN unidades c ON a.sitio = c.codigo
					LEFT JOIN estados e ON a.estado = e.id
					LEFT JOIN categorias f ON a.idcategoria = f.id
					LEFT JOIN subcategorias g ON a.idsubcategoria = g.id
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					";
		if($nivel != 1 && $nivel != 2){
			$query .= " LEFT JOIN usuarios q ON find_in_set(c.codigo, q.sitio) 
						AND q.usuario = '$usuario' ";
		}
		//$query  .= " WHERE a.idcategoria not in (12,22,35,43) ";
		
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idempresas in ($idempresas) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idclientes in ($idclientes) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idproyectos in ($idproyectos) ";
		}
		
		if($nivel == 3) {
			$query  .= " AND (
							j.usuario = '".$_SESSION['usuario']."' OR 
							l.usuario = '".$_SESSION['usuario']."' OR
							FIND_IN_SET(a.iddepartamentos,(SELECT GROUP_CONCAT( DISTINCT ee.id SEPARATOR  ',' )			
														FROM usuarios a
														LEFT JOIN departamentos ee ON FIND_IN_SET(ee.id, a.iddepartamentos) AND ee.tipo = 'grupo'
														WHERE a.usuario = '".$_SESSION['usuario']."')) 
						)";
		}elseif($nivel == 4){
			if($_SESSION['sitio'] != ''){
				$sitio = $_SESSION['sitio'];
				$sitio = explode(',',$sitio);
				$sitio = implode("','", $sitio);
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.sitio IN ('".$sitio."') ) ";
			}else{
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
			}			
		}
		$hayFiltros = 0;
		for($i=0 ; $i<count($_REQUEST['columns']);$i++){
			$column = $_REQUEST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			if ($_REQUEST['columns'][$i]['search']['value']!="") {
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);
				
				if ($column == 'id') {
					$column = 'a.id';
					$where[]=" $column = '".$campo."' ";
				}
				if ($column == 'titulo') {
					$column = 'a.titulo';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'estado') {
					$column = 'e.nombre';
					$where[]=" $column like '%".$campo."%' ";
				}				
				if ($column == 'idempresas') {
					$column = 'n.descripcion';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'iddepartamentos') {
					$column = 'o.nombre';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'idclientes') {
					$column = 'p.nombre';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'idproyectos') {
					$column = 'b.nombre';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'idcategoria') {
					$column = 'f.nombre';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'idsubcategoria') {
					$column = 'g.nombre';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'asignadoa') {
					$column = 'l.nombre';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'sitio') {
					$column = 'a.sitio';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'modalidad') {
					$column = 'm.modalidad';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'serie') {
					$column = 'a.serie';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'idprioridad') {
					$column = 'h.prioridad';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'solicitante') {
					$column = 'j.nombre';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'fechacierre') {
					$column = 'a.fechacierre';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'fechacreacion') {
					$column = 'a.fechacreacion';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'horacreacion') {
					$column = 'a.horacreacion';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'marca') {
					$column = 'a.marca';
					$where[]=" $column like '%".$campo."%' ";
				}
				if ($column == 'modelo') {
					$column = 'a.modelo';
					$where[]=" $column like '%".$campo."%' ";
				} 
				$hayFiltros++;
			}
		}
		if ($hayFiltros > 0)
			$where = " AND ".implode(" AND " , $where)." ";// id like '%searchValue%' or name like '%searchValue%'
		else
			$where = "";
		
		$buscar = $_REQUEST['search']['value'];
		if($buscar != ''){
			$where .= " AND (
							MATCH(a.titulo) AGAINST ('$buscar' IN BOOLEAN MODE) OR
							MATCH(a.descripcion) AGAINST ('$buscar' IN BOOLEAN MODE) OR
							MATCH(a.resolucion) AGAINST ('$buscar' IN BOOLEAN MODE) OR
							MATCH(a.observaciones) AGAINST ('$buscar' IN BOOLEAN MODE)
						)";
		}
		
		$query  .= " $where $where2";
		
		//debug($query);
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		$query  .= " ORDER BY a.id desc LIMIT $start, $length ";
		
		$response = '';
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		while($row = $result->fetch_assoc()){
			$solicitante = $row['solicitante'];
			//ADJUNTOS INCIDENTES
			$tieneEvidencias   = '';
			$rutaE 		= '../incidenteslab/'.$row['id'];
			if (is_dir($rutaE)) { 
			  if ($dhE = opendir($rutaE)) { 
				$num = 1;
				while (($fileE = readdir($dhE)) !== false) { 
					if ($fileE != "." && $fileE != ".." && $fileE != ".quarantine" && $fileE != ".tmb" && $fileE != "comentarios"){ 
						$nombrefile = $fileE;
						if($num > 1){
							$tieneEvidencias .= ", ";
						}
						$tieneEvidencias .= "<a href='".dirname($_SERVER['PHP_SELF'])."/".$rutaE."/".$fileE."' target='_blank'>".$nombrefile."</a>";
						$num++;
					}						
				} 
				closedir($dhE); 
			  } 
			}
			if($tieneEvidencias != ''){
				$color = 'green';
			}else{
				$color = 'blue';
			}
			$coment = " SELECT visto FROM comentarioslab WHERE idmodulo = '".$row['id']."' ";			
			$rcomen = $mysqli->query($coment);			
			if($rcomen->num_rows > 0){
				while($row2 = $rcomen->fetch_assoc()){
					$coment2 = $coment.=" AND visto = 'NO'";
				
					$rcomen2 = $mysqli->query($coment2);
					//$contar  = $rcomen2->num_rows;
					if($rcomen2->num_rows > 0){
						$iconcoment = "<span class='icon-col green fa fa-comment  boton-coment-".$row['id']."' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Comentarios' data-placement='right'></span>";
					}else{
						$iconcoment = "<span class='icon-col blue fa fa-comment boton-coment-".$row['id']."' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Comentarios' data-placement='right'></span>";
					}
				}
			}else{
				$iconcoment = "";
			}
			
			if($row['categoria'] == 12 || $row['categoria'] == 22 || $row['categoria'] == 35 || $row['categoria'] == 43){
				$tipo = 'PREV';
			}else{
				$tipo = 'INC';
			}
			
			$response['data'][] = array(				
				'tipo' 				=> $tipo,
				'id' 				=> $row['id'],
				'estado' 			=> $row['estado'],
				'titulo' 			=> $row['titulo'],
				'descripcion' 		=> $row['descripcion'],
				'solicitante'		=> $solicitante,
				'fechacreacion' 	=> $row['fechacreacion'],
				'horacreacion'		=> $row['horacreacion'],
				'idempresas'		=> $row['idempresas'],
				'iddepartamentos'	=> $row['iddepartamentos'],
				'idclientes'		=> $row['idclientes'],
				'idproyectos'		=> $row['idproyectos'],
				'idcategoria'		=> $row['categoria'],
				'idsubcategoria'	=> $row['subcategoria'],
				'asignadoa'			=> $row['nomusuario'],
				'sitio'				=> $row['sitio'],
				'serie'				=> $row['serie'],
				'marca'				=> $row['marca'],
				'modelo'			=> $row['modelo'],
				'idprioridad'		=> $row['prioridad'],
				'fechacierre'		=> $row['fechacierre'],
				'resolucion'		=> $row['resolucion'],
				'observaciones'		=> $row['observaciones']
			);
		}
		if($response == ''){
			$response['data'][] = array(				
				'check' => "", 'acciones' => "",'tipo' => "", 'id' => "", 'estado' => "", 'titulo' => "",
				'solicitante' => "",'fechacreacion' => "", 'horacreacion' => "",
				'idempresas' => "", 'iddepartamentos' => "", 'idclientes' => "", 'idproyectos' => "", 
				'idcategoria' => "", 'idsubcategoria' => "", 'asignadoa' => "", 'sitio' => "",
				'serie' => "", 'marca' => "", 'modelo' => "", 'idprioridad' => "",
				'fechacierre' => ""
			);
		}
		$response['draw'] = intval($draw);
		$response['recordsTotal'] = intval($recordsTotal);
		$response['recordsFiltered'] = intval($recordsTotal);
		//print_r($response);
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
			$where = str_replace('idempresas','a.idempresas',$where);
			$where = str_replace('iddepartamentos','a.iddepartamentos',$where);
			$where = str_replace('idclientes','a.idclientes',$where);
			$where = str_replace('idproyectos','a.idproyectos',$where);
			$where = str_replace('ejecutora','a.sitio',$where);
			$where = str_replace('solicitante','IFNULL(j.nombre, a.solicitante)',$where);
			$where = str_replace('asignadoa',' l.nombre ',$where);
			$where = str_replace('a.idf.nombre ','f.id',$where);
			$where = str_replace('estado','a.estado',$where);
			$where = str_replace('a.idpriora.idad','a.idprioridad',$where);
			$where = str_replace('marca','a.marca',$where);
			
			//FILTROS MASIVO
			$where2 = "";	
			$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');	
			
			$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Incidenteslab' AND usuario = '".$_SESSION['usuario']."'";
			$result = $mysqli->query($query);
			if($result->num_rows >0){
				$row = $result->fetch_assoc();				
				if (!isset($_REQUEST['data'])) {
					$data = $row['filtrosmasivos'];
				}
			}
			
			if($data != ''){
				$data = json_decode($data);
				if(!empty($data->desdef)){
					$desdef = json_encode($data->desdef);
					$where2 .= " AND a.fechacreacion >= $desdef "; 
				}
				if(!empty($data->hastaf)){
					$hastaf = json_encode($data->hastaf);
					$where2 .= " AND a.fechacreacion <= $hastaf "; 
				}
				if(!empty($data->categoriaf)){
					$categoriaf = json_encode($data->categoriaf);
					$where2 .= " AND a.idcategoria IN ($categoriaf)";
				}
				if(!empty($data->subcategoriaf)){
					$subcategoriaf = json_encode($data->subcategoriaf);
					$where2 .= " AND a.idsubcategoria IN ($subcategoriaf)"; 
				}			
				if(!empty($data->idempresasf)){
					$idempresasf = json_encode($data->idempresasf);
					$where2 .= " AND a.idempresas IN ($idempresasf)";
				}
				if(!empty($data->iddepartamentosf)){
					$iddepartamentosf = json_encode($data->iddepartamentosf);
					$where2 .= " AND a.iddepartamentos IN ($iddepartamentosf)";
				}
				if(!empty($data->idclientesf)){
					$idclientesf = json_encode($data->idclientesf);
					$where2 .= " AND a.idclientes IN ($idclientesf)";
				}
				if(!empty($data->idproyectosf)){
					$idproyectosf = json_encode($data->idproyectosf);
					$where2 .= " AND a.idproyectos IN ($idproyectosf)";
				}
				if(!empty($data->prioridadf)){
					$prioridadf = json_encode($data->prioridadf);
					$where2 .= " AND a.idprioridad IN ($prioridadf)"; 
				}
				if(!empty($data->marcaf)){
					$marcaf = json_encode($data->marcaf);
					$where2 .= " AND a.marca IN ($marcaf)";
				}
				if(!empty($data->solicitantef)){
					$solicitantef = json_encode($data->solicitantef);
					$where2 .= " AND a.solicitante IN ($solicitantef)";
				}
				if(!empty($data->estadof)){
					$estadof = json_encode($data->estadof);
					$where2 .= " AND a.estado IN ($estadof)";
				}
				if(!empty($data->asignadoaf)){
					$asignadoaf = json_encode($data->asignadoaf);
					
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
				if(!empty($data->sitiof)){
					$sitiof = json_encode($data->sitiof);
					$where2 .= " AND a.sitio IN ($sitiof)";					
				}
				$vowels = array("[", "]");
				$where2 = str_replace($vowels, "", $where2);
			}
			
			if($buscar != ''){
				$where .= " AND MATCH(a.resolucion) AGAINST ('$buscar' IN BOOLEAN MODE)  ";
			}
			
			$query  = " SELECT a.id, e.nombre AS estado, a.titulo, a.descripcion, 
						b.nombre AS idproyectos, a.sitio,
						IFNULL(j.nombre, a.solicitante) AS solicitante, a.fechacreacion, 
						a.horacreacion, a.asignadoa, 
						MATCH (a.resolucion) AGAINST ('$buscar' IN BOOLEAN MODE) AS relevance,
						f.nombre AS categoria, h.prioridad, a.serie, a.marca, a.modelo, 
						a.fechareal as fechacierre
						FROM incidenteslab a
						LEFT JOIN proyectos b ON a.idproyectos = b.id
						LEFT JOIN unidades c ON a.sitio = c.codigo
						LEFT JOIN estados e ON a.estado = e.id
						LEFT JOIN categorias f ON a.idcategoria = f.id
						LEFT JOIN sla h ON a.idprioridad = h.id
						LEFT JOIN usuarios j ON a.solicitante = j.correo
						LEFT JOIN usuarios l ON a.asignadoa = l.correo
						LEFT JOIN empresas n ON a.idempresas = n.id
						LEFT JOIN departamentos o ON a.iddepartamentos = o.id
						LEFT JOIN clientes p ON a.idclientes = p.id
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
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.sitio = '".$_SESSION['sitio']."') ";
				$queryCuenta  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.sitio = '".$_SESSION['sitio']."') ";
			}
			$query  	.= " $where $where2";
			$query  .= " ORDER BY relevance desc, a.id desc ";
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
				$tieneEvidencias = directoriovacio('incidenteslab', $row['id']);
				$response->rows[$i]['cell']=array('',$row['id'],$row['estado'],$row['titulo'],
				$row['descripcion'],$solicitante,$row['fechacreacion'],$row['horacreacion'],
				$row['idempresas'],$row['iddepartamentos'],$row['idclientes'],$row['idproyectos'],
				$row['categoria'],$row['asignadoa'],$row['sitio'],$row['serie'],
				$row['marca'],$row['modalidad'],$row['prioridad'],$row['fechacierre'],$tieneEvidencias);
				$i++;
			}
			echo json_encode($response);
		}
	}

	function eliminarincidentes()
	{
		global $mysqli;

		$id 	= $_REQUEST['idincidente'];		
		$query 	= "DELETE FROM incidenteslab WHERE id = '$id'";
		$result = $mysqli->query($query);
		if($result == true){
			echo 1;
		}else{
			echo 0;
		}
		bitacora($_SESSION['usuario'], "Incidentes Lab.", 'El Incidente #: '.$id.' fue eliminado.', $id, $query);				
	}
	
 function eliminarcomentarios()
	{
		global $mysqli;

		$id 	 = $_REQUEST['idcomentario']; 
		$nivel 	 = $_SESSION['nivel'];
		$usuario = $_SESSION['usuario'];
		 
		//Elimino el comentario si es usuario administrador o soporte
		if ($nivel==1 || $nivel==2){
			$queryEs    = "DELETE FROM comentarioslab WHERE id = '$id'";
			            
			$resultEs	= $mysqli->query($queryEs);
			if($resultEs){
			    echo 1;
			}else{
			    echo 0;
			}
		}else{
		    $queryNoes = "SELECT * FROM comentarioslab WHERE id = '$id' AND usuario = '$usuario'";
    		$resultNoes =    $mysqli->query($queryNoes);
    			
			if($resultNoes->num_rows > 0){ 
				$querySi = "DELETE FROM comentarioslab WHERE id = '$id' AND usuario = '$usuario'";
				$resultSi= $mysqli->query($querySi);
				if($resultSi==true){
					echo 1;
				}else{
					echo 0;
				}
			}else{
				echo 2;
			}
		}		
		bitacora($_SESSION['usuario'], "Incidentes Lab.", 'El Comentario #: '.$id.' fue eliminado.', $id, $query); 
	}

	function abrirSolicitudes() {
		$incidente 	= $_REQUEST['incidente'];		
		$_SESSION['incidente'] = $incidente;
		$_SESSION['comentario'] = '';
		$myPath = '../incidenteslab/'.$incidente;
		$target_path = utf8_decode($myPath);
		if (!file_exists($target_path)) {
			mkdir($target_path, 0777);
		}
		//$Path = dirname($_SERVER['PHP_SELF']) . '/../incidentes/'.$_SESSION['incidente'].'/';
		$Path = '/../incidenteslab/'.$_SESSION['incidente'].'/';
		//debug($Path);
		$hash = strtr(base64_encode($Path), '+/=', '-_.');
		$hash = rtrim($hash, '.');		
		echo "l1_". $hash;		
	}

	function agregarComentario(){
		global $mysqli;
		$incidente	= $_REQUEST['id'];
		$comentario = $_REQUEST['coment'];
		$usuario 	= $_SESSION['usuario'];
		$visibilidad = $_REQUEST['visibilidad'];
		$fecha 		= date("Y-m-d");
		$id_preventivo = 0;
		if($comentario != ''){
			$queryI = "INSERT INTO comentarioslab VALUES(null, 'Incidentes', $incidente, '$comentario', '$visibilidad', '$usuario', '$fecha', 'NO')";
			//debug('queryI: '.$_GET['comentario']);
			if($mysqli->query($queryI)){
				$id = $mysqli->insert_id;
				//BITACORA
				bitacora($_SESSION['usuario'], "Incidentes Lab.", "Se ha registrado un Comentario para el Incidente #".$incidente, $incidente, $queryI);
				//ENVIAR NOTIFICACION
				//notificarComentarios($incidente,$comentario,$visibilidad);
				echo true;
			}else{
				echo false;
			}		
		}else{
			echo false;
		}
	}
	
	function comentarios(){
		global $mysqli;
		$nivel = $_SESSION['nivel'];
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$buscar = (isset($_POST['buscar']) ? $_POST['buscar'] : '');
		$response = '';

		$query  = " SELECT a.id, a.idmodulo, a.comentario, a.fecha, b.nombre, a.visibilidad
					FROM comentarioslab a
					LEFT JOIN usuarios b ON a.usuario = b.usuario
					WHERE modulo = 'Incidentes' AND idmodulo = $id ";
		if($nivel == 4){
			$query .= " AND a.visibilidad = 'Público' ";
		}
		$query .= " ORDER BY a.id DESC ";
		
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			//ADJUNTOS
			$adjuntos   = '';
			$ruta 		= '../incidenteslab/'.$row['idmodulo'].'/comentarios/'.$row['id'];
			if (is_dir($ruta)) { 
			  if ($dh = opendir($ruta)) { 
				$num = 1;
				while (($file = readdir($dh)) !== false) { 
					if ($file != "." && $file != ".." && $file != ".quarantine" && $file != ".tmb"){ 
						$nombrefile = $file;
						if($num > 1){
							$adjuntos .= ", ";
						}
						$adjuntos .= "<a href='".dirname($_SERVER['PHP_SELF'])."/".$ruta."/".$file."' target='_blank'>".$nombrefile."</a>";
						$num++;
					}						
				} 
				closedir($dh); 
			  } 
			}
			if($adjuntos != ''){
				$color = 'green';
			}else{
				$color = 'blue';
			}
			$response['data'][] = array(				
				'id' 			=> $row['id'],
				'acciones' 		=>	"<div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>
										<span class='icon-col red fa fa-trash boton-eliminar-comentarios' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Comentario' data-placement='right'></span> 
										<span class='icon-col ".$color." fa fa-camera boton-adjuntos-comentarios' data-id='".$row['idmodulo']."-".$row['id']."' data-toggle='tooltip' data-original-title='Adjuntos Comentario' data-placement='right'></span> 
									</div>",
				'comentario' 	=> $row['comentario'],
				'nombre'		=> $row['nombre'],
				'visibilidad'	=> $row['visibilidad'],
				'fecha' 		=> $row['fecha'],
				'adjuntos' 		=> $adjuntos
			);
		}
		if($response == ''){
			$response['data'][] = array(				
				'id' => '', 'acciones' => '', 'comentario' => '', 'nombre' => '', 
				'visibilidad' => '', 'fecha' => '', 'adjuntos' => ''
			);
		}
		echo json_encode($response);
	}
	
	function comentariosleidos(){		
		global $mysqli;
		$idincidente 	= $_REQUEST['idincidente'];
		
		$query = " SELECT visto FROM comentarioslab WHERE visto = 'NO' 
				   AND idmodulo = '$idincidente' ";		
		$result = $mysqli->query($query);
		
		if($result->num_rows > 0){
			$upd = " UPDATE comentarioslab SET visto = 'SI' WHERE idmodulo = '$idincidente' 
					 AND visto = 'NO' ";
			$resupd = $mysqli->query($upd);
			
			if($resupd==true){
				echo 1;
			}else{
				echo 0;
			}
		}		
	}
 
	function adjuntosComentarios() {
		$incidentecom 	= $_REQUEST['incidentecom'];
		$arr 			= explode('-',$incidentecom);
		$incidente 		= $arr[0];
		$comentario 	= $arr[1];
		$_SESSION['incidente'] 	= $incidente;
		$_SESSION['comentario'] = $comentario;
		
		$myPathC 	  = '../incidenteslab/'.$incidente.'/comentarios/';
		$target_pathC = utf8_decode($myPathC);
		if (!file_exists($target_pathC)) {
			mkdir($target_pathC, 0777);
		}
		$myPath 	 = '../incidenteslab/'.$incidente.'/comentarios/'.$comentario;
		$target_path = utf8_decode($myPath);
		if (!file_exists($target_path)) {
			mkdir($target_path, 0777);
		}
		//$Path = dirname($_SERVER['PHP_SELF']) . '/../incidentes/'.$_SESSION['incidente'].'/';
		$Path = '/../incidenteslab/'.$incidente.'/comentarios/'.$comentario.'/';
		$hash = strtr(base64_encode($Path), '+/=', '-_.');
		$hash = rtrim($hash, '.');		
		echo "l1_". $hash;		
	}
	
	//ENVIAR CORREO DE NOTIFICACION DE COMENTARIO
	function notificarComentarios($incidente,$comentario,$visibilidad){
		global $mysqli;
		//CREADOR - SOLICITANTE - ASIGNADO
		$query  = " SELECT IFNULL(i.correo, a.creadopor) AS creadopor, 
					a.solicitante, a.asignadoa, a.notificar
					FROM incidenteslab a
					LEFT JOIN usuarios i ON a.creadopor = i.id OR a.creadopor = i.correo
					WHERE a.id = $incidente ";
		$result = $mysqli->query($query);
		while ($row = $result->fetch_assoc()) {
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
		$usuarios = $_SESSION['usuario'];
		$consultaUA = $mysqli->query("SELECT nombre FROM usuarios 
									  WHERE usuario = '$usuarios' LIMIT 1 ");
		while ($registroUA = $consultaUA->fetch_assoc()) {
			$usuarioAct = $registroUA['nombre'];
		}
		//DATOS
		$query  = " SELECT a.id, a.titulo, a.descripcion, a.sitio, a.resolucion,
					h.prioridad, a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, 
					IFNULL(j.nombre, a.solicitante) AS solicitante, a.asignadoa,
					a.departamento, IF(a.fechacreacion!='',CONCAT(a.fechacreacion,'  ', 
					a.horacreacion),'') AS fechacreacion					
					FROM incidenteslab a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN unidades c ON a.sitio = c.codigo
					LEFT JOIN estados e ON a.estado = e.id
					LEFT JOIN categorias f ON a.idcategoria = f.id
					LEFT JOIN subcategorias g ON a.idsubcategoria = g.id
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.resueltopor = k.correo
					WHERE a.id = $incidente ";
					
		$result 		= $mysqli->query($query);
		$row 			= $result->fetch_assoc();
		$fechacreacion 	= $row['fechacreacion'];
		$descripcion	= $row['descripcion'];
		$solicitante	= $row['solicitante'];
		$creadopor		= $row['creadopor'];
		$departamento	= $row['departamento'];
		$prioridad		= $row['prioridad'];
		$sitio 			= $row['sitio'];
		$resolucion 	= $row['resolucion'];
		$nasignadoa 	= $asignadoaN;
		$comentarios	= '';
		$bitacora		= '';
		
		//COMENTARIOS
		$consultaC = $mysqli->query("SELECT comentariolab FROM comentarios 
									 WHERE idmodulo = $incidente ");
		while ($registroC = $consultaC->fetch_assoc()) {
			$comentarios .= $registroC['comentario'].'<br>';
		}
		//BITACORA
		$consultaB = $mysqli->query("SELECT accion FROM bitacora WHERE identificador = $incidente ");
		while ($registroB = $consultaB->fetch_assoc()) {
			$bitacora .= $registroB['accion'].'<br>';
		}
		
		if($solicitante == 'mesadeayuda@innovacion.gob.pa'){
    	    $asunto = $row['titulo']." (Incidente Maxia #$incidente) - Comentario";
    	} else {
    	    $asunto = "Incidente #$incidente - Comentario";
		}
		
		$mensaje  = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha comentado el incidente #".$incidente."</b></p>			
					<p style='padding-left: 30px;width:100%;'>Comentario ".$visibilidad.": ".$comentario."</p>
					<p style='width:100%;'><br><a href='http://toolkit.maxialatam.com/soporte/incidenteslab.php?id=$incidente' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Añadir un comentario</a></p>
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
		enviarMensajeIncidente($asunto,$mensaje,$correo);
	}

	function abrirIncidente(){
		global $mysqli;
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado 	 = '';
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.id AS idproyectos,
					a.sitio, a.equipo, a.serie, a.marca, a.modelo, e.id AS estado, 
					h.id AS prioridad, a.solicitante, a.asignadoa, a.departamento, 
					CONCAT_WS('',j.id,' - ', j.titulo) AS fusionado, a.notificar, 
					a.resolucion, a.fechacertificar, a.horario, a.origen, 
					IFNULL(i.nombre, a.creadopor) AS creadopor, a.comentariosatisfaccion,
					IFNULL(k.nombre, a.resueltopor) AS resueltopor, 
					IF(a.fechacreacion!='', a.fechacreacion,'') AS fechacreacion, a.horacreacion,
					IF(a.fechavencimiento!='',CONCAT(a.fechavencimiento,'  ', a.horavencimiento),'') AS fechavencimiento,
					IF(a.fecharesolucion!='',CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,
					a.fechacierre, a.horacierre,a.fechamodif, a.fechacertificar, a.horastrabajadas,
					n.id as idempresas, o.id as iddepartamentos, p.id as idclientes, a.atencion
					FROM incidenteslab a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN estados e ON a.estado = e.id
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					LEFT JOIN incidentes j ON a.fusionado = j.id
					LEFT JOIN usuarios k ON a.resueltopor = k.correo
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					WHERE a.id = $id ";
		//debug($query);
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			if($row['marca'] == '0')
				$row['marca']='';
			if($row['modelo'] == '0')
				$row['modelo']='';
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
			//NOTIFICAR
			$notificar = $row['notificar'];
			$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';
			if(strpos($notificar, '<') == true){
				preg_match ( $pattern, $notificar, $notificar );
			}

			$resultado[] = array(
						'id' 					=> $row['id'],
						'titulo'				=> $row['titulo'],
						'descripcion' 			=> $row['descripcion'],
						'idempresas' 			=> $row['idempresas'],
						'iddepartamentos'		=> $row['iddepartamentos'],
						'idclientes' 			=> $row['idclientes'],
						'idproyectos' 			=> $row['idproyectos'],
						'sitio' 				=> $row['sitio'],
						'equipo' 				=> $row['equipo'],
						'serie' 				=> $row['serie'],
						'marca' 				=> $row['marca'],
						'modelo' 				=> $row['modelo'],
						'estado' 				=> $row['estado'],
						'prioridad' 			=> $row['prioridad'],
						'solicitante' 			=> $solicitante,
						'asignadoa' 			=> $row['asignadoa'],
						'departamento' 			=> $row['departamento'],
						'modalidad' 			=> $row['modalidad'],
						'fusionado' 			=> $row['fusionado'],
						'notificar' 			=> $row['notificar'],
						'resolucion' 			=> $row['resolucion'],						
						'horario' 				=> $row['horario'],
						'marca' 				=> $row['marca'],
						'modelo' 				=> $row['modelo'],
						'origen' 				=> $row['origen'],
						'creadopor' 			=> $row['creadopor'],
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
						'periodo' 				=> $row['periodo'],
						'atencion' 				=> $row['atencion']
					);
		}
		echo json_encode($resultado);
	}
	
	function guardarIncidente()
	{
		global $mysqli;
		$data 				= (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		$titulo 			= (!empty($data['titulo']) ? $data['titulo'] : '');
		$descripcion 		= (!empty($data['descripcion']) ? $data['descripcion'] : '');
		$idempresas 		= (!empty($data['idempresas']) ? $data['idempresas'] : 1);
		$iddepartamentos	= (!empty($data['iddepartamentos']) ? $data['iddepartamentos'] : 0);
		$idclientes 		= (!empty($data['idclientes']) ? $data['idclientes'] : 0);
		$idproyectos 		= (!empty($data['idproyectos']) ? $data['idproyectos'] : 0);
		$sitio 				= (!empty($data['sitio']) ? $data['sitio'] : '');
		$equipo				= (!empty($data['equipo']) ? $data['equipo'] : '');
		$serie 				= (!empty($data['serie']) ? $data['serie'] : '');
		$marca 				= (!empty($data['marca']) ? $data['marca'] : '');
		$modelo				= (!empty($data['modelo']) ? $data['modelo'] : '');
		$estado 			= (!empty($data['estado']) ? $data['estado'] : 12);
		$prioridad 			= (!empty($data['prioridad']) ? $data['prioridad'] : 0);
		$origen 			= (isset($data['origen']) ? $data['origen'] : 'sistema');
		$solicitante 		= (!empty($data['solicitante']) ? $data['solicitante'] : '');
		$creadopor			= (!empty($data['creadopor']) ? $data['creadopor'] : $_SESSION['correousuario']);
		$asignadoa 			= (!empty($data['asignadoa']) ? $data['asignadoa'] : '');
		$departamento 		= (!empty($data['departamento']) ? $data['departamento'] : '');
		$notificar 			= (!empty($data['notificar']) ? $data['notificar'] : '');
		$resolucion 		= (!empty($data['resolucion']) ? $data['resolucion'] : '');
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
		$atencion	  	 	= '';
	    
		if($fecharesolucion != ''){
			$fecharesolucion = preg_split("/[\s,]+/",$fecharesolucion);
			$horaresolucion  = "'".$fecharesolucion[1]."'";
			$fecharesolucion = "'".$fecharesolucion[0]."'";
		}else{
			$fecharesolucion = 'null';
			$horaresolucion  = 'null';
		}		
		if($fechacierre == '' && $estado == 16){
			$fechacierre	= "'".date('Y-m-d')."'";
			$horacierre 	= "'".date('H:i:s')."'";
		}elseif ($fechacierre == '') {
			$fechacierre = 'null';
			$horacierre  = 'null';
		} else {
			$horacierre  = "'".$horacierre."'";
			$fechacierre = "'".$fechacierre."'";			
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
		//AGREGAR
		$fechacreacion	= date('Y-m-d');
		$horacreacion 	= date('H:i:s');

		$query = "  INSERT INTO incidenteslab(id, titulo, descripcion, sitio, equipo, serie, 
					marca, modelo, estado, idprioridad, origen, creadopor, solicitante, 
					asignadoa, departamento, fechacreacion, ";		
		if($fechavencimiento != ''){
			$query .= "fechavencimiento, horavencimiento, ";
		}				
		$query .="  horacreacion, notificar, resolucion, fechacertificar, horario, fechareal, 
					horareal, idempresas, idclientes, idproyectos, iddepartamentos,atencion)
					VALUES(null, '$titulo', '$descripcion', '$sitio', '$equipo', '$serie', 
					'$marca', '$modelo', '$estado','$prioridad', '$origen', '$creadopor', 
					'$solicitante', '$asignadoa', '$departamento', '$fechacreacion', ";
		if($fechavencimiento !=''){
			$query .= "'$fechavencimiento', '$horavencimiento',  ";
		}	  
		$query .= " '$horacreacion','$notificar','$resolucion','$fechacertificar','$horario',
					'$fechacreacion', '$horacreacion','$idempresas', '$idclientes', 
					'$idproyectos', '$iddepartamentos','$atencion') ";		 
		
		//debug($query);
		if($mysqli->query($query)){
			if($id == ''){
				$id = $mysqli->insert_id;
				$accion = 'El Incidente #'.$id.' ha sido Creado exitosamente';
				//CREAR CARPETA DE ID INCIDENTES Y COMENTARIOS
				$myPath = '../incidenteslab/';
				if (!file_exists($myPath))
					mkdir($myPath, 0777);
				$myPath = '../incidenteslab/'.$id.'/';
				$target_path2 = utf8_decode($myPath);
				if (!file_exists($target_path2))
					mkdir($target_path2, 0777);
				
				//ENVIAR CORREO AL CREADOR DEL INCIDENTE
				//nuevoincidente($_SESSION['usuario'], $titulo, $descripcion, $id, $fechacreacion, $horacreacion, $solicitante);
				//notificarCEstado($id,'','creado','',$estado);
			}
			bitacora($_SESSION['usuario'], "Incidentes Lab.", $accion, $id, $query);

			//ENVIAR CORREO DE SATISFACCION - RESUELTO / CERRADO
			if($estado == 16 || $estado == 17){
				//crearMensajeSatisfaccion($id,$titulo,$solicitante);
			}				
			echo true;
		}else{
			echo false;
		}
	}
	
	function actualizarIncidente()
	{
		global $mysqli;		
		$id   				= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$data 				= (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		$titulo 			= (!empty($data['titulo_editar']) ? $data['titulo_editar'] : '');
		$descripcion 		= (!empty($data['descripcion_editar']) ? $data['descripcion_editar'] : '');
		$idempresas 		= (!empty($data['idempresas_editar']) ? $data['idempresas_editar'] : 1);
		$iddepartamentos	= (!empty($data['iddepartamentos_editar']) ? $data['iddepartamentos_editar'] : 0);
		$idclientes 		= (!empty($data['idclientes_editar']) ? $data['idclientes_editar'] : 0);
		$idproyectos 	    = (!empty($data['idproyectos_editar']) ? $data['idproyectos_editar'] : 0);
		$sitio 				= (!empty($data['sitio_editar']) ? $data['sitio_editar'] : '');
		$equipo 			= (!empty($data['equipo_editar']) ? $data['equipo_editar'] : '');
		$serie 				= (!empty($data['serie_editar']) ? $data['serie_editar'] : '');
		$marca 				= (!empty($data['marca_editar']) ? $data['marca_editar'] : '');
		$modelo				= (!empty($data['modelo_editar']) ? $data['modelo_editar'] : '');
		$estado 			= (!empty($data['estado_editar']) ? $data['estado_editar'] : 12);
		$prioridad 			= (!empty($data['prioridad_editar']) ? $data['prioridad_editar'] : 0);
		$origen 			= (isset($data['origen_editar']) ? $data['origen_editar'] : 'sistema');
		$solicitante 		= (!empty($data['solicitante_editar']) ? $data['solicitante_editar'] : '');
		$creadopor			= (!empty($data['creadopor_editar']) ? $data['creadopor_editar'] : $_SESSION['correousuario']);
		$asignadoa 			= (!empty($data['asignadoa_editar']) ? $data['asignadoa_editar'] : '');
		$departamento 		= (!empty($data['departamento_editar']) ? $data['departamento_editar'] : '');
		$notificar 			= (!empty($data['notificar_editar']) ? $data['notificar_editar'] : '');
		$resolucion 		= (!empty($data['resolucion_editar']) ? $data['resolucion_editar'] : '');
		$horario 			= (!empty($data['horario_editar']) ? $data['horario_editar'] : '');
		$fechavencimiento	= NULL;
		$horavencimiento  	= NULL;
		$fecharesolucion 	= (!empty($data['fecharesolucion_editar']) ? $data['fecharesolucion_editar'] : '');
		$fechacierre 		= (!empty($data['fechacierre_editar']) ? $data['fechacierre_editar'] : '');
		$horacierre 		= (!empty($data['horacierre_editar']) ? $data['horacierre_editar'] : '');
		$fechacertificar 	= (!empty($data['fechacertificar_editar']) ? $data['fechacertificar_editar'] : '');
		$fechacreacion		= (!empty($data['fechacreacion_editar']) ? $data['fechacreacion_editar'] : date("Ymd"));
		$horacreacion 		= (!empty($data['horacreacion_editar']) ? $data['horacreacion_editar'] : date("H:i:s"));
		$horastrabajadas 	= (!empty($data['horastrabajadas_editar']) ? $data['horastrabajadas_editar'] : '0');
		$estadoInc 			= '';
		$atencion	  	 	= (!empty($data['atencion_editar']) ? $data['atencion_editar'] : '');
		
		if($fecharesolucion != ''){
			$fecharesolucion = preg_split("/[\s,]+/",$fecharesolucion);
			$horaresolucion  = "'".$fecharesolucion[1]."'";
			$fecharesolucion = "'".$fecharesolucion[0]."'";
		}else{
			$fecharesolucion = 'null';
			$horaresolucion  = 'null';
		}		
		if($fechacierre == '' && $estado == 16){
			$fechacierre	= "'".date('Y-m-d')."'";
			$horacierre 	= "'".date('H:i:s')."'";
		}elseif ($fechacierre == '') {
			$fechacierre = 'null';
			$horacierre  = 'null';
		} else {
			$horacierre  = "'".$horacierre."'";
			$fechacierre = "'".$fechacierre."'";			
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
		//ACTUALIZAR
		$queryInc = $mysqli->query("SELECT estado FROM incidenteslab WHERE id = '$id'");
		while ($rowInc = $queryInc->fetch_assoc()) {
			$estadoInc = $rowInc['estado'];
		}
		
		$descripcion = str_replace("'","",$descripcion);
		$qverificar = " SELECT titulo, descripcion, sitio, serie, marca, modelo, estado, 
						idprioridad, solicitante, asignadoa, departamento, 
						resolucion, notificar, fechacertificar, horario, fecharesolucion, 
						horaresolucion, horastrabajadas, fechacierre, horacierre, idempresas, 
						idclientes, idproyectos, iddepartamentos, atencion
						FROM incidenteslab 
						WHERE id = $id ";
						
		$rverificar = $mysqli->query($qverificar);
		
		$qestado = " SELECT nombre FROM estados WHERE id = $estado ";
		$restado = $mysqli->query($qestado);
		if($reges = $restado->fetch_assoc()){
			$mostrarestado = $reges['nombre'];
		}
			
		$qclient = " SELECT nombre FROM clientes WHERE id = $idclientes ";
		$rclient = $mysqli->query($qclient);
		if($regcl = $rclient->fetch_assoc()){
			$mostrarcliente = $regcl['nombre'];
		}
		
		$qproye = " SELECT nombre FROM proyectos WHERE id = $idproyectos ";
		$rproye = $mysqli->query($qproye);
		if($regpro = $rproye->fetch_assoc()){
			$mostrarproyecto = $regpro['nombre'];
		}
			
		$qdepto = " SELECT nombre FROM departamentos WHERE id = $iddepartamentos ";
		$rdepto = $mysqli->query($qdepto);
		if($regdepto = $rdepto->fetch_assoc()){
			$mostrardpto = $regdepto['nombre'];
		}
		
		$qprior = " SELECT prioridad FROM sla WHERE id = $prioridad ";
		$rprior = $mysqli->query($qprior);
		if($regprior = $rprior->fetch_assoc()){
			$mostrarprioridad = $regprior['prioridad'];
		}
				
		$accion = " El Usuario: ".$_SESSION['usuario'].", modificó el campo: ";
		if($rowcompare = $rverificar->fetch_assoc()){
			$fechres = "'".$rowcompare['fecharesolucion']."'";
			$horares = "'".$rowcompare['horaresolucion']."'";
			$fechcie = "'".$rowcompare['fechacierre']."'";
			$horacie = "'".$rowcompare['horacierre']."'";
			
			if($rowcompare['titulo']!=$titulo){
				$accion .= 'Título a "'.$titulo.'",'; 
			}
			if($rowcompare['descripcion']!=$descripcion){
				$accion .= 'Descripción a "'.$descripcion.'",'; 
			}
			if($rowcompare['sitio']!=$sitio){
				$accion .= 'Unidad a "'.$sitio.'",'; 
			}
			if($rowcompare['serie']!=$serie){
				$accion .= 'Serie a "'.$serie.'",'; 
			}
			if($rowcompare['marca']!=$marca){
				$accion .= 'Marca a "'.$marca.'",'; 
			}
			if($rowcompare['modelo']!=$modelo){
				$accion .= 'Modelo a "'.$modelo.'",'; 
			}
			if($rowcompare['estado']!=$estado){
				$accion .= 'Estado a "'.$mostrarestado.'",';
			}
			if($rowcompare['idprioridad']!=$prioridad){
				$accion .= 'Prioridad a "'.$mostrarprioridad.'",'; 
			}
			if($rowcompare['solicitante']!=$solicitante){
				$accion .= 'Solicitante a "'.$solicitante.'",'; 
			}
			if($rowcompare['asignadoa']!=$asignadoa){
				$accion .= 'Asignado a "'.$asignadoa.'",'; 
			}
			if($rowcompare['departamento']!=$departamento){
				$accion .= 'Departamento a "'.$departamento.'",'; 
			}
			if($rowcompare['resolucion']!=$resolucion){
				$accion .= 'Resolución a "'.$resolucion.'",'; 
			}
			if($rowcompare['notificar']!=$notificar){
				$accion .= 'Notificar a "'.$notificar.'",'; 
			}
			if($rowcompare['fechacertificar']!=$fechacertificar){
				$accion .= 'Fecha Certificar a "'.$fechacertificar.'",';  
			}
			if($rowcompare['horario']!=$horario){
				$accion .= 'Horario a "'.$horario.'",';  
			}
			if($fecharesolucion!="null"){
				if($fechres!=$fecharesolucion){
					$accion .= 'Fecha Resolución a'.$fechres.' = "'.$fecharesolucion.'",';  
				} 
			}
			if($horaresolucion!="null"){
				if($horares!=$horaresolucion){
					$accion .= 'Hora Resolución a '.$horares.' = "'.$horaresolucion.'",';  
				}
			}
			if($rowcompare['horastrabajadas']!=$horastrabajadas){
				$accion .= 'Horas Trabajadas a "'.$horastrabajadas.'",';  
			}
			if($fechacierre!="null"){
				if($fechcie!=$fechacierre){
					$accion .= 'Fecha Cierre a '.$fechcie.' = "'.$fechacierre.'",';  
				}
			}
			if($horacierre!="null"){
				if($horacie!=$horacierre){
					$accion .= 'Hora Cierre a '.$horacie.' ="'.$horacierre.'",'; 
				}
			}
			if($rowcompare['idempresas']!=$idempresas){
				$accion .= 'Empresa a "'.$idempresas.'",'; 
			}
			if($rowcompare['idclientes']!=$idclientes){
				$accion .= 'Cliente a "'.$mostrarcliente.'",';  
			}
			if($rowcompare['idproyectos']!=$idproyectos){
				$accion .= 'Proyecto a "'.$mostrarproyecto.'",';  
			}
			if($rowcompare['iddepartamentos']!=$iddepartamentos){
				$accion .= 'Departamento a "'.$mostrardpto.'",';  
			}
			if($rowcompare['atencion']!=$atencion){
				$accion .= 'Atención a "'.$atencion.'",'; 
			}				
		} 
		$accion = substr($accion,0,-1); 
		$accion .= ".";
		//$accion = 'El Incidente #'.$id.' ha sido actualizado exitosamente';
		$query = "  UPDATE incidenteslab SET titulo = '$titulo', descripcion = '$descripcion', 
					sitio = '$sitio', equipo = '$equipo', serie = '$serie', marca = '$marca', 
					modelo = '$modelo', estado = '$estado', idprioridad = $prioridad, 
					solicitante = '$solicitante', asignadoa = '$asignadoa', 
					departamento = '$departamento', resolucion = '$resolucion', 
					notificar = '$notificar', fechacertificar = '$fechacertificar', 
					horario = '$horario', fecharesolucion = $fecharesolucion, 
					horaresolucion = $horaresolucion, horastrabajadas = '$horastrabajadas', 
					fechacierre = $fechacierre, horacierre = $horacierre, 
					idempresas = '$idempresas', idclientes = '$idclientes', 
					idproyectos = '$idproyectos', iddepartamentos = '$iddepartamentos', 
					atencion = '$atencion' ";
		$query .= " WHERE id = $id ";		
		//debug($query);
		
		if($mysqli->query($query)){			
			//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
			if($estadoInc != $estado){
				if($estado == 13){
					$query = "SELECT idproyectos FROM usuarios WHERE correo = '$asignadoa' ";
					$result = $mysqli->query($query);
					if($result->num_rows >0){
						$row = $result->fetch_assoc();				
						$proyectosusu = $row['idproyectos'];
					}
				}
				//notificarCEstado($id,$notificar,'actualizado',$estadoInc,$estado);
			}
			bitacora($_SESSION['usuario'], "Incidentes Lab.", $accion, $id, $query);

			//ENVIAR CORREO DE SATISFACCION - RESUELTO / CERRADO
			if($estado == 16 || $estado == 17){
				//crearMensajeSatisfaccion($id,$titulo,$solicitante);
			}				
			echo true;
		}else{
			echo false;
		} 
	}
	
	function guardarIncidenteMasivo()
	{
		global $mysqli;		
		$id   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$data = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');		
		//MASIVO
		$idarray = explode(",", $id);
		if(count($idarray) > 1){
			$query = "";
			if($data != ''){
				$i = 0;
				$coma = ',';
				$query .= "UPDATE incidenteslab SET ";
				foreach($data as $c => $v){
					if($v != '' && $v != '0'){
						if($i != 0){
							$query .= $coma;
						}
						if($c == 'idempresasmas'){
							$query .= " idempresas = '$v' ";
						}elseif($c == 'iddepartamentosmas'){
							$query .= " iddepartamentos = '$v' ";
						}elseif($c == 'idclientesmas'){
							$query .= " idclientes = '$v' ";
						}elseif($c == 'idproyectosmas'){
							$query .= " idproyectos = '$v' ";
						}elseif($c == 'prioridadmas'){
							//FECHA CREACION
							$queryFC  			= " SELECT fechacreacion FROM incidenteslab WHERE id = '$id' ";
							$resultFC 			= $mysqli->query($queryFC);
							$rowFC 				= $resultFC->fetch_assoc();
							$fechacreacion		= $rowFC['fechacreacion'];
							//SLA
							$queryV  			= " SELECT dias, horas FROM sla WHERE id = '$v' ";
							$resultV 			= $mysqli->query($queryV);
							$rowV 				= $resultV->fetch_assoc();
							$diasP 				= $rowV['dias'];
							$horasP 			= $rowV['horas'];
							$fechavencimiento 	= date('Y-m-d', strtotime($fechacreacion."+ ".$diasP." days"));
							$horavencimiento  	= date('H:i:s', strtotime($horacreacion." + ".$horasP." hours"));
							$query .= " idprioridad = '$v', fechavencimiento = '$fechavencimiento', horavencimiento = '$horavencimiento' ";
						}elseif($c == 'sitiomas'){
							$query .= " sitio = '$v' ";
						}elseif($c == 'seriemas'){
							$query .= " serie = '$v' ";
						}elseif($c == 'marcamas'){
							$query .= " marca = '$v' ";
						}elseif($c == 'modelomas'){
							$query .= " modelo = '$v' ";
						}elseif($c == 'asignadoamas'){
							$query .= " asignadoa = '$v' ";
						}elseif($c == 'estadomas'){
							$query .= " estado = '$v' ";
						}						
						$i++;
					}
				}				
				if($i >= 1){
					foreach($idarray as $id){
						$query2 = '';
						$query2 = $query." WHERE id = '$id' ";
						//debug($query2);
						if($id != ''){							
							if($mysqli->query($query2)){
								bitacora($_SESSION['usuario'], "Incidentes Lab.", 'El Incidente #'.$id.' ha sido Editado exitosamente', $id, $query2);
								echo true;
							}else{
								echo false;
							}							
						}						
					}
				}else{
					echo false;
				}				
			}
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
			$innovacion = 'soporteaig@innovacion.gob.pa';
			if($solicitante == $innovacion || $creadopor == $innovacion){
				$asunto = $titulo;
			}else{
				$asunto = "Incidente Lab. #$incidente ha sido Creado";
			}
			
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
	}

	//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
	function notificarCEstado($incidente,$notificar,$accion,$estadoold,$estadonew){
		global $mysqli;
		
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, a.sitio, 
					a.serie, a.marca, a.modelo, e.nombre AS estado, h.prioridad, a.origen, 
					IFNULL(i.nombre, a.creadopor) AS creadopor, 
					IFNULL(j.nombre, a.solicitante) AS solicitante, a.asignadoa,
					a.departamento, a.satisfaccion, a.comentariosatisfaccion, 
					a.resolucion, IFNULL(k.nombre, a.resueltopor) AS resueltopor,
					IF(a.fechacreacion!='',CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion,
					IF(a.fechavencimiento!='',CONCAT(a.fechavencimiento,'  ', a.horavencimiento),'') AS fechavencimiento,
					IF(a.fecharesolucion!='',CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,
					IF(a.fechacierre!='',CONCAT(a.fechacierre,'  ', a.horacierre),'') AS fechacierre,
					a.fechamodif, a.fechacertificar, a.horastrabajadas, a.comentariovisto,					
					IFNULL(i.correo, a.creadopor) AS correocreadopor,
					IFNULL(j.correo, a.solicitante) AS correosolicitante
					FROM incidenteslab a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN unidades c ON a.sitio = c.codigo
					LEFT JOIN estados e ON a.estado = e.id
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.id OR a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.resueltopor = k.correo
					WHERE a.id = $incidente GROUP BY a.id ";
					
		$result = $mysqli->query($query);
		$row 	= $result->fetch_assoc();
		
		//Para quien solicito o reporto el incidente (Solicitante)
		if($estadonew == 16 || $estadonew == 17){
			$correo [] = $row['correosolicitante'];
		}
		
		//Para quien se le asigno el incidente (Asignado a)	
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
			$asunto    = "Notificación del Incidente Lab. #$incidente";
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
				$asunto = "Incidente Lab. #$incidente ha sido Creado";
			}else{ //actualizado
				if ($estadoold != $estadonew && $estadonew == 13) {
					$asunto = "Incidente Lab. #$incidente ha sido Asignado";			
				} elseif ($estadoold != $estadonew && $estadonew == 16) {
					$asunto = "Incidente Lab. #$incidente ha sido Resuelto";	
					//if (substr($row['titulo'],0,14)=='[Service Desk]') {
					if ($row['correosolicitante']=='mesadeayuda@innovacion.gob.pa') {
						$asunto = $row['titulo']." (Incidente Maxia #$incidente) ha sido Resuelta";
					    //$correo [] = 'mesadeayuda@innovacion.gob.pa';
					}
				}
				else {
					$asunto = "Incidente Lab. #$incidente ha sido Actualizado";			
				}
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
		$sitio 			= $row['sitio'];
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
		$mensaje .= "<p style='width:100%;'>
						<a href='http://toolkit.maxialatam.com/soporte/incidenteslab.php?id=".$incidente."' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Incidente</a></p>
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
				$query = "  UPDATE incidenteslab SET fechacierre = DATE_ADD(fecharesolucion, INTERVAL 3 DAY), horacierre = horaresolucion, 
							estado = 16 WHERE id = '".$incidente."' ";
				$mysqli->query($query);
				$mensaje .= "<br><br><p style='width:100%;'><b>Resolución: </b>".$resolucion."</p>";	
			}
			
			$mensaje .= "</div>";

		//USUARIOS DE SOPORTE
		$correo [] = 'ana.porras@maxialatam.com';
		$correo [] = 'isai.carvajal@maxialatam.com';
		//ASUNTO
		$innovacion = 'soporteaig@innovacion.gob.pa';
		if($solicitante == $innovacion || $creadopor == $innovacion){
			$asunto = $row['titulo'];
		}
		enviarMensajeIncidente($asunto,$mensaje,$correo);
	}
	
	function crearMensajeSatisfaccion($incidente,$titulo,$solicitante){
		global $mysqli;

		//para quien solicito o reporto el incidente (Solicitante)
		if (filter_var($solicitante, FILTER_VALIDATE_EMAIL)) {
				$correo [] = $solicitante;
		}else{
			$result = $mysqli->query("SELECT nombre,correo FROM usuarios WHERE id = '$solicitante'");
			while ($row=$result->fetch_assoc()) {
				$correo [] = $row['correo'];
			}
		}
		
		//ASUNTO
		$innovacion = 'soporteaig@innovacion.gob.pa';
		if($solicitante == $innovacion){
			$asunto = $row['titulo'];
		}else{
			$asunto = "Satisfacción del Incidente #$incidente";
		}

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

		$query  = " SELECT a.id, a.titulo,
					CONCAT_WS ('',b.correo, e.correo, IF(RIGHT(a.asignadoa,2) = '-G' OR RIGHT(a.asignadoa,2) = '-U', '', a.asignadoa)) AS asignadoa,
					CONCAT(a.fechacreacion,' ', horacreacion), a.fechavencimiento, f.nombre
					FROM incidenteslab a
					LEFT JOIN usuarios b ON REPLACE(a.asignadoa,'-G','') = b.id AND RIGHT(a.asignadoa,2) = '-U'
					LEFT JOIN grupos c ON REPLACE(a.asignadoa,'-U','')= c.id AND RIGHT(a.asignadoa,2) = '-G'
					LEFT JOIN gruposusuarios d ON c.id = d.idgrupo
					LEFT JOIN usuarios e ON d.idusuario = e.id
					LEFT JOIN usuariosincidentes f ON a.solicitante = f.id OR a.solicitante = f.correo
					WHERE fechavencimiento < CURDATE() AND a.estado NOT IN (16,17) ";

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
		$cuerpo .= "<img src='http://toolkit.maxialatam.com/repositorio-tema/assets/img/maxia.jpg' style='width: initial;height: 60px;float: left; position: absolute !important;'>";
		$cuerpo .= "<p style='margin:auto; font-weight:bold; width: 100%; text-align: center;'>Maxia Toolkit<br>";
		$cuerpo .= "Gestión de Soporte<br>";
		$cuerpo .= "</div>";
		$cuerpo .= $mensaje;
		$cuerpo .= "<div style='background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;'>";
		$cuerpo .= "© ".date('Y')." Maxia Latam";
		$cuerpo .= "</div>";	
		
		foreach($correo as $destino){
		   $mail->addAddress($destino);
		}
		
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

	function historial(){
		global $mysqli;
		$nivel = $_SESSION['nivel'];
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$response = '';
		
		$query  = "SELECT id, usuario, fecha, accion
					FROM bitacora
					WHERE modulo = 'Incidentes Lab.' AND identificador = $id
					ORDER BY id DESC ";
		
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			$response['data'][] = array(				
				'id' 		=> $row['id'],
				'usuario' 	=> $row['usuario'],
				'fecha'		=> $row['fecha'],
				'accion'	=> $row['accion']
			);
		}
		if($response == ''){
			$response['data'][] = array(				
				'id' => '','usuario' => '', 'fecha' => '', 'accion'	=> ''
			);
		}
		echo json_encode($response);
	}
	
	function exportarExcel() 
	{
		global $mysqli;
		$usuario 		 = $_SESSION['usuario'];
		$nivel 			 = $_SESSION['nivel'];
		$idempresas 	 = $_SESSION['idempresas'];
		$iddepartamentos = $_SESSION['iddepartamentos'];
		$idclientes 	 = $_SESSION['idclientes'];
		$idproyectos 	 = $_SESSION['idproyectos'];
		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		//date_default_timezone_set('Europe/London');
		
		$id 	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$data 	 = '';
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

		/** Include PHPExcel */
		//require_once dirname(__FILE__) . '../../repositorio-lib/xls/Classes/PHPExcel.php';
		require_once '../../repositorio-lib/xls/Classes/PHPExcel.php';

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maxia Latam")
		->setLastModifiedBy("Maxia Latam")
		->setTitle("Reporte de Incidentes Laboratorio")
		->setSubject("Reporte de Incidentes Laboratorio")
		->setDescription("Reporte de Incidentes Laboratorio")
		->setKeywords("Reporte de Incidentes Laboratorio")
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Reporte de Incidentes Laboratorio');
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($style);
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:AE1');
		
		// ENCABEZADO 
		$objPHPExcel->getActiveSheet()
		->setCellValue('A4', '# Incidente')
		->setCellValue('B4', 'Titulo')
		->setCellValue('C4', 'Descripción')		
		->setCellValue('D4', 'Cliente')
		->setCellValue('E4', 'Proyecto')
		->setCellValue('F4', 'Estado')
		->setCellValue('G4', 'Equipo')
		->setCellValue('H4', 'Serie')
		->setCellValue('J4', 'Marca')
		->setCellValue('K4', 'Modelo')
		->setCellValue('P4', 'Sitio')
		->setCellValue('Q4', 'Prioridad')
		->setCellValue('R4', 'Origen')
		->setCellValue('S4', 'Creado por')
		->setCellValue('T4', 'Solicitante')
		->setCellValue('U4', 'Asignado a')
		->setCellValue('V4', 'Departamento')				
		->setCellValue('W4', 'Resuelto por')
		->setCellValue('X4', 'Resolución')
		->setCellValue('Y4', 'Satisfacción')
		->setCellValue('Z4', 'Comentario de Satisfacción')		
		->setCellValue('AA4', 'Fecha de creación')
		->setCellValue('AB4', 'Hora de creación')
		->setCellValue('AC4', 'Fecha de resolución')
		->setCellValue('AD4', 'Hora de resolución')
		->setCellValue('AE4', 'Fecha de cierre')
		->setCellValue('AF4', 'Hora de cierre')
		->setCellValue('AG4', 'Fecha de vencimiento')
		->setCellValue('AH4', 'Hora de vencimiento')
		->setCellValue('AI4', 'Fecha real')
		->setCellValue('AJ4', 'Hora de real')		
		->setCellValue('AK4', 'Tiempo de servicio')
		->setCellValue('AL4', 'Horas Trabajadas')
		->setCellValue('AM4', 'Periodo');
		
		//LETRA
		$objPHPExcel->getActiveSheet()->getStyle('A4:AM4')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
		$objPHPExcel->getActiveSheet()->getStyle("A4:AM4")->applyFromArray($style);
		//FONDO
		$objPHPExcel->getActiveSheet()->getStyle('A4:AM4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('63b9db');
		
		//SENTENCIA BASE
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, 
					e.nombre AS estado, a.equipo, a.serie, a.marca, a.modelo, a.sitio, 
					h.prioridad, a.origen, a.creadopor, a.solicitante, a.asignadoa, 
					a.departamento, a.resueltopor, a.resolucion, a.satisfaccion, 
					a.comentariosatisfaccion, ifnull(a.fechacreacion, '') AS fechacreacion, 
					a.horacreacion, ifnull(a.fecharesolucion, '') as fecharesolucion, 
					a.horaresolucion, ifnull(a.fechacierre, '') as fechacierre, a.horacierre, 
					ifnull(a.fechavencimiento, '') AS fechavencimiento, a.horavencimiento, 
					ifnull(a.fechareal, '') AS fechareal, a.horareal, a.horastrabajadas, 
					cu.periodo, o.nombre as cliente 
					FROM incidenteslab a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN estados e ON a.estado = e.id
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN activos m ON a.serie = m.codequipo AND a.sitio = m.codigound
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					LEFT JOIN cuatrimestres cu ON a.fecharesolucion BETWEEN cu.fechainicio AND cu.fechafin
					";
		
		if($nivel != 1 && $nivel != 2){
			$query .= " LEFT JOIN usuarios q ON find_in_set(c.codigo, q.sitio) AND q.usuario = '$usuario' ";
		}
		$query  .= " WHERE a.idcategoria not in (12,22,35,43) ";
		
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idempresas in ($idempresas) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idclientes in ($idclientes) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idproyectos in ($idproyectos) ";
		}
		if($nivel == 3) {
			$query  .= " AND (
							j.usuario = '".$_SESSION['usuario']."' OR 
							l.usuario = '".$_SESSION['usuario']."' OR
							FIND_IN_SET(a.iddepartamentos,(SELECT GROUP_CONCAT( DISTINCT ee.id SEPARATOR  ',' )			
														FROM usuarios a
														LEFT JOIN departamentos ee ON FIND_IN_SET(ee.id, a.iddepartamentos) AND ee.tipo = 'grupo'
														WHERE a.usuario = '".$_SESSION['usuario']."')) 
						)";
		}elseif($nivel == 4){
			if($_SESSION['sitio'] != ''){
				$sitio = $_SESSION['sitio'];
				$sitio = explode(',',$sitio);
				$sitio = implode("','", $sitio);
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.sitio IN ('".$sitio."') ) ";
			}else{
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
			}			
		}
		
		//DATOS 
		$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Incidenteslab' AND usuario = '".$_SESSION['usuario']."'";
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
			if(!empty($data->desdef)){
				$desdef = json_encode($data->desdef);
				$where2 .= " AND a.fechacreacion >= $desdef ";
			} else {
				//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where2 .= " AND a.fechacreacion <= $hastaf ";
			}
			if(!empty($data->categoriaf)){
				$categoriaf = json_encode($data->categoriaf);
				$where2 .= " AND a.idcategoria IN ($categoriaf)";
			}
			if(!empty($data->subcategoriaf)){
				$subcategoriaf = json_encode($data->subcategoriaf);
				$where2 .= " AND a.idsubcategoria IN ($subcategoriaf)";
			}			
			if(!empty($data->idempresasf)){
				$idempresasf = json_encode($data->idempresasf);
				$where2 .= " AND a.idempresas IN ($idempresasf)"; 
			}
			if(!empty($data->iddepartamentosf)){
				$iddepartamentosf = json_encode($data->iddepartamentosf);
				$where2 .= " AND a.iddepartamentos IN ($iddepartamentosf)"; 
			}
			if(!empty($data->idclientesf)){
				$idclientesf = json_encode($data->idclientesf);
				$where2 .= " AND a.idclientes IN ($idclientesf)"; 
			}
			if(!empty($data->idproyectosf)){
				$idproyectosf = json_encode($data->idproyectosf);
				$where2 .= " AND a.idproyectos IN ($idproyectosf)"; 
			}
			if(!empty($data->prioridadf)){
				$prioridadf = json_encode($data->prioridadf);
				$where2 .= " AND a.idprioridad IN ($prioridadf)";
			}
			if(!empty($data->marcaf)){
				$marcaf = json_encode($data->marcaf);
				$where2 .= " AND a.marca IN ($marcaf)"; 
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				$where2 .= " AND a.solicitante IN ($solicitantef)";
			}
			if(!empty($data->estadof)){
				$estadof = json_encode($data->estadof);
				$where2 .= " AND a.estado IN ($estadof)";
			}
			if(!empty($data->asignadoaf)){
				$asignadoaf = json_encode($data->asignadoaf);
				
				$asignadoaf = '';
				$i = 0;
				foreach($data->asignadoaf as $usuarios){
					if($i > 0)
						$asignadoaf .=",";
					$asignadoaf .= "'$usuarios'";
					$i++;
				}
				if($asignadoaf != "''"){
					$where2 .= " AND a.asignadoa IN ($asignadoaf)";	
				}
			}
			if(!empty($data->sitiof)){
				$sitiof = json_encode($data->sitiof);
				 if($sitiof !== '[""]'){ 
					$where2 .= " AND a.sitio IN ($sitiof)";
				}
			}	
			$vowels = array("[", "]");
			$where2 = str_replace($vowels, "", $where2);
		}
		
		//CUERPO
		//Definir fuente
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);					
		
		$query  .= " $where2 ORDER BY a.id desc ";
		//debug('a: '.$query);
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
			$xfechareal = $row['fechareal'];
			
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
			if ($row['fechareal']!='') {
				$xfechareal = date_create_from_format('Y-m-d', $row['fechareal']);
				$xfechareal = date_format($xfechareal, "m/d/Y");
			}			
			
			$numeroreq = str_pad($row['id'], 4, "0", STR_PAD_LEFT);		
			$objPHPExcel->getActiveSheet()
			->setCellValue('A'.$i, $numeroreq)
			->setCellValue('B'.$i, $row['titulo'])
			->setCellValue('C'.$i, $row['descripcion'])
			->setCellValue('D'.$i, $row['cliente'])
			->setCellValue('E'.$i, $row['proyecto'])
			->setCellValue('F'.$i, $row['estado'])
			->setCellValue('G'.$i, $row['equipo'])
			->setCellValue('H'.$i, $row['serie'])
			->setCellValue('J'.$i, $row['marca'])
			->setCellValue('K'.$i, $row['modelo'])
			->setCellValue('P'.$i, $row['sitio'])
			->setCellValue('Q'.$i, $row['prioridad'])
			->setCellValue('R'.$i, $row['origen'])
			->setCellValue('S'.$i, $row['creadopor'])
			->setCellValue('T'.$i, $row['solicitante'])
			->setCellValue('U'.$i, $asignadoaN)
			->setCellValue('V'.$i, $row['departamento'])
			->setCellValue('W'.$i, $row['resueltopor'])
			->setCellValue('X'.$i, $row['resolucion'])
			->setCellValue('Y'.$i, $row['satisfaccion'])
			->setCellValue('Z'.$i, $row['comentariosatisfaccion'])
			->setCellValue('AA'.$i, $xfechacreacion) //->setCellValue('W'.$i, implode('/',array_reverse(explode('-', $row['fechacreacion']))))
			->setCellValue('AB'.$i, $row['horacreacion'])
			->setCellValue('AC'.$i, $xfecharesolucion)// ->setCellValue('AA'.$i, implode('/',array_reverse(explode('-', $row['fecharesolucion']))))
			->setCellValue('AD'.$i, $row['horaresolucion'])
			->setCellValue('AE'.$i, $xfechacierre) //->setCellValue('AC'.$i, implode('/',array_reverse(explode('-', $row['fechacierre']))))
			->setCellValue('AF'.$i, $row['horacierre'])
			->setCellValue('AG'.$i, $xfechavencimiento) // ->setCellValue('Y'.$i, implode('/',array_reverse(explode('-', $row['fechavencimiento']))))
			->setCellValue('AH'.$i, $row['horavencimiento'])
			->setCellValue('AI'.$i, $xfechareal) // ->setCellValue('Y'.$i, implode('/',array_reverse(explode('-', $row['fechavencimiento']))))
			->setCellValue('AJ'.$i, $row['horareal'])
			->setCellValue('AK'.$i, $dif)
			->setCellValue('AL'.$i, $row['horastrabajadas'])
			->setCellValue('AM'.$i, $row['periodo']);
			
			//ESTILOS
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':AJ'.$i)->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':AJ'.$i)->getAlignment()->applyFromArray(
						array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));
			$objPHPExcel->getActiveSheet()->getStyle('Z'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
			$objPHPExcel->getActiveSheet()->getStyle('Z'.$i)->getAlignment()->applyFromArray(
						array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
			$objPHPExcel->getActiveSheet()->getStyle('AF'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
			$objPHPExcel->getActiveSheet()->getStyle('AF'.$i)->getAlignment()->applyFromArray(
						array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
			$objPHPExcel->getActiveSheet()->getStyle('AB'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
			$objPHPExcel->getActiveSheet()->getStyle('AB'.$i)->getAlignment()->applyFromArray(
						array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
			$objPHPExcel->getActiveSheet()->getStyle('AD'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
			$objPHPExcel->getActiveSheet()->getStyle('AD'.$i)->getAlignment()->applyFromArray(
						array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
			$objPHPExcel->getActiveSheet()->getStyle('AH'.$i)->getAlignment()->applyFromArray(
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(24);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(24);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setAutoSize(true);

		//Renombrar hoja de Excel
		$objPHPExcel->getActiveSheet()->setTitle('Incidentes - Laboratorios');

		//Redirigir la salida al navegador del cliente
		$hoy = date('dmY');
		$nombreArc = 'Correctivos - '.$hoy.'.xls';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$nombreArc.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();
	}
	
	function comentariovisto()
	{
		global $mysqli;

		$id = $_REQUEST['id'];
		$query = "UPDATE incidenteslab SET comentariovisto='1' WHERE id = '$id'";
		$mysqli->query($query);
	}
	
	function filtroGrid(){
		global $mysqli;
		$_SESSION['filtrogrid'] = '0';
		$usufiltroexiste = 0;
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Incidenteslab' AND usuario =".$_SESSION['user_id'];
		$result = $mysqli->query($query);
		if($result->num_rows >0){
			$row = $result->fetch_assoc();				
			$where = $row['filtrosmasivos'];
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
			$filtro = str_replace('a.idempresas','idempresas',$filtro);
			$filtro = str_replace('a.iddepartamentos','iddepartamentos',$filtro);
			$filtro = str_replace('a.idclientes','idclientes',$filtro);
			$filtro = str_replace('a.idproyectos','idproyectos',$filtro);
			$filtro = str_replace('c.codigo','sitio',$filtro);
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
					$setset[$i] = array('campo'=> trim($setset[$i][0]),'valor'=> trim($setset[$i][1]));
				}
				$i++;
			}
			echo json_encode($setset);	
		}	
	}
	
	function limpiarFiltrosMasivos(){
		global $mysqli;
		$usuario = $_SESSION['usuario'];
		
		$query = "DELETE FROM usuariosfiltros WHERE modulo = 'Incidenteslab' AND usuario = '$usuario' ";
		if($mysqli->query($query))
			echo true;		
	}
	
	function guardarfiltros() {
		global $mysqli;
		$data = $_REQUEST['data'];
		$usuario = $_SESSION['usuario'];
		$query  = "SELECT *
					FROM usuariosfiltros
					WHERE modulo = 'Incidenteslab' AND usuario = '$usuario' ";

		$result = $mysqli->query($query);
		$count = $result->num_rows;
		
		if( $count > 0 ) 
			$query = "UPDATE usuariosfiltros SET filtrosmasivos = '$data' WHERE modulo = 'Incidenteslab' AND usuario = '$usuario'";
		else
			$query = "INSERT INTO usuariosfiltros VALUES (null, '$usuario', 'Incidenteslab', '', '$data')";
		if($mysqli->query($query))
			echo true;		
	}
	
	function abrirfiltros() {
		global $mysqli;
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Incidenteslab' AND usuario = '".$_SESSION['usuario']."'";
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
	
	function notificacionAdjunto() {
		global $mysqli, $mail;
		$incidente 	= (!empty($_REQUEST['incidente']) ? $_REQUEST['incidente'] : '');
		$imagen 	= (!empty($_REQUEST['imagen']) ? $_REQUEST['imagen'] : '');
		
		if($incidente != ''){
			//DATOS DEL CORREO
			$usuarioSes = $_SESSION['usuario'];
			$consultaUA = $mysqli->query("SELECT nombre FROM usuarios WHERE usuario = '$usuarioSes' LIMIT 1 ");
			while ($registroUA = $consultaUA->fetch_assoc()) {
				$usuarioAct = $registroUA['nombre'];
			}
			
			//USUARIOS DE SOPORTE
			$correo [] = 'ana.porras@maxialatam.com';
			$correo [] = 'isai.carvajal@maxialatam.com';
		
			$query  = " SELECT a.id, IFNULL(i.correo, a.creadopor) AS creadopor, a.notificar,
						IFNULL(j.correo, a.solicitante) AS solicitante, a.asignadoa
						FROM incidenteslab a
						LEFT JOIN usuarios i ON a.creadopor = i.correo
						LEFT JOIN usuarios j ON a.solicitante = j.correo
						WHERE a.id = $incidente ";
			$result = $mysqli->query($query);
			$row 	= $result->fetch_assoc();
			
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
			//ENVIAR CORREO AL USUARIO QUE CREO EL INCIDENTE
			if($row['creadopor'] != ''){
				$creadopor  = $row['creadopor'];
				if (filter_var($creadopor, FILTER_VALIDATE_EMAIL)) {
					$correo [] = "$creadopor";				
				}else{
					foreach($creadopor as $creadop){
						$correo [] = $creadop;
					}
				}
			}
			//ENVIAR CORREO AL SOLICITANTE QUE CREO EL INCIDENTE
			if($row['solicitante'] != '' && $row['solicitante'] != $row['creadopor']){			
				$solicitante  = $row['solicitante'];
				if (filter_var($solicitante, FILTER_VALIDATE_EMAIL)) {
					$correo [] = "$solicitante";				
				}else{
					foreach($solicitante as $solicitantep){
						$correo [] = $solicitantep;
					}
				}
			}
			//ENVIAR CORREO A LOS USUARIOS A NOTIFICAR
			if($row['notificar'] != ''){
				$notificar  = $row['notificar'];
				if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
					$correo [] = "$notificar";				
				}else{
					foreach($notificar as $notificarp){
						$correo [] = $notificarp;
					}
				}
			}
		
			$cuerpo = "";
			$cuerpo .= "<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; '>";
			$cuerpo .= "<img src='http://toolkit.maxialatam.com/repositorio-tema/assets/img/maxia.jpg' style='width: initial;height: 60px;float: left; position: absolute !important;'>";
			$cuerpo .= "<p style='margin:auto; font-weight:bold; width: 100%; text-align: center;'>Maxia Toolkit<br>";
			$cuerpo .= "Gestión de Soporte<br>";
			$cuerpo .= "</p></div>";
			$cuerpo .= "<div style='padding: 30px;font-family: arial,sans-serif;'>
							<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha adjuntado nuevo documento al incidente #".$incidente."</b></p>";
			$cuerpo .= "	<p style='width:100%;'>
								<a href='http://toolkit.maxialatam.com/soporte/incidenteslab.php?id=".$incidente."' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Incidente</a></p>
							</p>
						</div>
						";
			$cuerpo .= "<div style='background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;'>";
			$cuerpo .= "© ".date('Y')." Maxia Latam";
			$cuerpo .= "</div>";	
			
			$correo = array_unique($correo);
			//debug(json_encode($correo));
			//echo $correo;
			
			foreach($correo as $destino){
			   $mail->addAddress($destino);
			}
			
			$mail->FromName = "Maxia Toolkit - SYM";
			$mail->isHTML(true); // Set email format to HTML
			$mail->Subject = "Incidente #".$incidente." - Nuevo adjunto";
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
		}else{
			echo false;
		}		
	}
	
	function exportarExcelConComentarios() 
	{
		global $mysqli;
		$usuario 		 = $_SESSION['usuario'];
		$nivel 			 = $_SESSION['nivel'];
		$idempresas 	 = $_SESSION['idempresas'];
		$iddepartamentos = $_SESSION['iddepartamentos'];
		$idclientes 	 = $_SESSION['idclientes'];
		$idproyectos 	 = $_SESSION['idproyectos'];
		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		//date_default_timezone_set('Europe/London');
		
		$id 	 = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$data 	 = '';
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

		/** Include PHPExcel */
		//require_once dirname(__FILE__) . '../../repositorio-lib/xls/Classes/PHPExcel.php';
		require_once '../../repositorio-lib/xls/Classes/PHPExcel.php';

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maxia Latam")
		->setLastModifiedBy("Maxia Latam")
		->setTitle("Reporte de Incidentes Laboratorio")
		->setSubject("Reporte de Incidentes Laboratorio")
		->setDescription("Reporte de Incidentes Laboratorio")
		->setKeywords("Reporte de Incidentes Laboratorio")
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Reporte de Incidentes Laboratorio');
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($style);
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:AE1');
		
		// ENCABEZADO 
		$objPHPExcel->getActiveSheet()
		->setCellValue('A4', '# Incidente')
		->setCellValue('B4', 'Titulo')
		->setCellValue('C4', 'Descripción')		
		->setCellValue('D4', 'Cliente')
		->setCellValue('E4', 'Proyecto')
		->setCellValue('F4', 'Estado')
		->setCellValue('G4', 'Equipo')
		->setCellValue('H4', 'Serie')
		->setCellValue('J4', 'Marca')
		->setCellValue('K4', 'Modelo')
		->setCellValue('P4', 'Sitio')
		->setCellValue('Q4', 'Prioridad')
		->setCellValue('R4', 'Origen')
		->setCellValue('S4', 'Creado por')
		->setCellValue('T4', 'Solicitante')
		->setCellValue('U4', 'Asignado a')
		->setCellValue('V4', 'Departamento')				
		->setCellValue('W4', 'Resuelto por')
		->setCellValue('X4', 'Resolución')
		->setCellValue('Y4', 'Satisfacción')
		->setCellValue('Z4', 'Comentario de Satisfacción')		
		->setCellValue('AA4', 'Fecha de creación')
		->setCellValue('AB4', 'Hora de creación')
		->setCellValue('AC4', 'Fecha de resolución')
		->setCellValue('AD4', 'Hora de resolución')
		->setCellValue('AE4', 'Fecha de cierre')
		->setCellValue('AF4', 'Hora de cierre')
		->setCellValue('AG4', 'Fecha de vencimiento')
		->setCellValue('AH4', 'Hora de vencimiento')
		->setCellValue('AI4', 'Fecha real')
		->setCellValue('AJ4', 'Hora de real')		
		->setCellValue('AK4', 'Tiempo de servicio')
		->setCellValue('AL4', 'Horas Trabajadas')
		->setCellValue('AM4', 'Periodo')
		->setCellValue('AN4', 'Comentarios');
		
		//LETRA
		$objPHPExcel->getActiveSheet()->getStyle('A4:AN4')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
		$objPHPExcel->getActiveSheet()->getStyle("A4:AN4")->applyFromArray($style);
		//FONDO
		$objPHPExcel->getActiveSheet()->getStyle('A4:AN4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('63b9db');
		
		//SENTENCIA BASE
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, 
					e.nombre AS estado, a.equipo, a.serie, a.marca, a.modelo, a.sitio, 
					h.prioridad, a.origen, a.creadopor, a.solicitante, a.asignadoa, 
					a.departamento, a.resueltopor, a.resolucion, a.satisfaccion, 
					a.comentariosatisfaccion, ifnull(a.fechacreacion, '') AS fechacreacion, 
					a.horacreacion, ifnull(a.fecharesolucion, '') as fecharesolucion, 
					a.horaresolucion, ifnull(a.fechacierre, '') as fechacierre, a.horacierre, 
					ifnull(a.fechavencimiento, '') AS fechavencimiento, a.horavencimiento, 
					ifnull(a.fechareal, '') AS fechareal, a.horareal, a.horastrabajadas, 
					cu.periodo, o.nombre as cliente, co.comentario 
					FROM incidenteslab a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN estados e ON a.estado = e.id
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					LEFT JOIN cuatrimestres cu ON a.fecharesolucion BETWEEN cu.fechainicio AND cu.fechafin
					LEFT JOIN comentarios co ON a.id = co.idmodulo
					";
		
		if($nivel != 1 && $nivel != 2){
			$query .= " LEFT JOIN usuarios q ON find_in_set(c.codigo, q.sitio) AND q.usuario = '$usuario' ";
		}
		$query  .= " WHERE a.idcategoria not in (12,22,35,43) ";
		
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idempresas in ($idempresas) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idclientes in ($idclientes) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idproyectos in ($idproyectos) ";
		}
		if($nivel == 3) {
			$query  .= " AND (
							j.usuario = '".$_SESSION['usuario']."' OR 
							l.usuario = '".$_SESSION['usuario']."' OR
							FIND_IN_SET(a.iddepartamentos,(SELECT GROUP_CONCAT( DISTINCT ee.id SEPARATOR  ',' )			
														FROM usuarios a
														LEFT JOIN departamentos ee ON FIND_IN_SET(ee.id, a.iddepartamentos) AND ee.tipo = 'grupo'
														WHERE a.usuario = '".$_SESSION['usuario']."')) 
						)";
		}elseif($nivel == 4){
			if($_SESSION['sitio'] != ''){
				$sitio = $_SESSION['sitio'];
				$sitio = explode(',',$sitio);
				$sitio = implode("','", $sitio);
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.sitio IN ('".$sitio."') ) ";
			}else{
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
			}			
		}
		
		//DATOS 
		$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Incidenteslab' AND usuario = '".$_SESSION['usuario']."'";
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
			if(!empty($data->desdef)){
				$desdef = json_encode($data->desdef);
				$where2 .= " AND a.fechacreacion >= $desdef ";
			} else {
				//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where2 .= " AND a.fechacreacion <= $hastaf ";
			}
			if(!empty($data->categoriaf)){
				$categoriaf = json_encode($data->categoriaf);
				$where2 .= " AND a.idcategoria IN ($categoriaf)";
			}
			if(!empty($data->subcategoriaf)){
				$subcategoriaf = json_encode($data->subcategoriaf);
				$where2 .= " AND a.idsubcategoria IN ($subcategoriaf)";
			}			
			if(!empty($data->idempresasf)){
				$idempresasf = json_encode($data->idempresasf);
				$where2 .= " AND a.idempresas IN ($idempresasf)"; 
			}
			if(!empty($data->iddepartamentosf)){
				$iddepartamentosf = json_encode($data->iddepartamentosf);
				$where2 .= " AND a.iddepartamentos IN ($iddepartamentosf)"; 
			}
			if(!empty($data->idclientesf)){
				$idclientesf = json_encode($data->idclientesf);
				$where2 .= " AND a.idclientes IN ($idclientesf)"; 
			}
			if(!empty($data->idproyectosf)){
				$idproyectosf = json_encode($data->idproyectosf);
				$where2 .= " AND a.idproyectos IN ($idproyectosf)"; 
			}
			if(!empty($data->prioridadf)){
				$prioridadf = json_encode($data->prioridadf);
				$where2 .= " AND a.idprioridad IN ($prioridadf)";
			}
			if(!empty($data->marcaf)){
				$marcaf = json_encode($data->marcaf);
				$where2 .= " AND a.marca IN ($marcaf)"; 
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				$where2 .= " AND a.solicitante IN ($solicitantef)";
			}
			if(!empty($data->estadof)){
				$estadof = json_encode($data->estadof);
				$where2 .= " AND a.estado IN ($estadof)";
			}
			if(!empty($data->asignadoaf)){
				$asignadoaf = json_encode($data->asignadoaf);
				
				$asignadoaf = '';
				$i = 0;
				foreach($data->asignadoaf as $usuarios){
					if($i > 0)
						$asignadoaf .=",";
					$asignadoaf .= "'$usuarios'";
					$i++;
				}
				if($asignadoaf != "''"){
					$where2 .= " AND a.asignadoa IN ($asignadoaf)";	
				}
			}
			if(!empty($data->sitiof)){
				$sitiof = json_encode($data->sitiof);
				 if($sitiof !== '[""]'){ 
					$where2 .= " AND a.sitio IN ($sitiof)";
				}
			}	
			$vowels = array("[", "]");
			$where2 = str_replace($vowels, "", $where2);
		}
		
		//CUERPO
		//Definir fuente
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);					
		
		$query  .= " $where2 ORDER BY a.id desc ";
		//debug($query);
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
			$xfechareal = $row['fechareal'];
			
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
			if ($row['fechareal']!='') {
				$xfechareal = date_create_from_format('Y-m-d', $row['fechareal']);
				$xfechareal = date_format($xfechareal, "m/d/Y");
			}			
			
			$numeroreq = str_pad($row['id'], 4, "0", STR_PAD_LEFT);		
			$objPHPExcel->getActiveSheet()
			->setCellValue('A'.$i, $numeroreq)
			->setCellValue('B'.$i, $row['titulo'])
			->setCellValue('C'.$i, $row['descripcion'])
			->setCellValue('D'.$i, $row['cliente'])
			->setCellValue('E'.$i, $row['proyecto'])
			->setCellValue('F'.$i, $row['estado'])
			->setCellValue('G'.$i, $row['equipo'])
			->setCellValue('H'.$i, $row['serie'])
			->setCellValue('J'.$i, $row['marca'])
			->setCellValue('K'.$i, $row['modelo'])
			->setCellValue('P'.$i, $row['sitio'])
			->setCellValue('Q'.$i, $row['prioridad'])
			->setCellValue('R'.$i, $row['origen'])
			->setCellValue('S'.$i, $row['creadopor'])
			->setCellValue('T'.$i, $row['solicitante'])
			->setCellValue('U'.$i, $asignadoaN)
			->setCellValue('V'.$i, $row['departamento'])
			->setCellValue('W'.$i, $row['resueltopor'])
			->setCellValue('X'.$i, $row['resolucion'])
			->setCellValue('Y'.$i, $row['satisfaccion'])
			->setCellValue('Z'.$i, $row['comentariosatisfaccion'])
			->setCellValue('AA'.$i, $xfechacreacion) //->setCellValue('W'.$i, implode('/',array_reverse(explode('-', $row['fechacreacion']))))
			->setCellValue('AB'.$i, $row['horacreacion'])
			->setCellValue('AC'.$i, $xfecharesolucion)// ->setCellValue('AA'.$i, implode('/',array_reverse(explode('-', $row['fecharesolucion']))))
			->setCellValue('AD'.$i, $row['horaresolucion'])
			->setCellValue('AE'.$i, $xfechacierre) //->setCellValue('AC'.$i, implode('/',array_reverse(explode('-', $row['fechacierre']))))
			->setCellValue('AF'.$i, $row['horacierre'])
			->setCellValue('AG'.$i, $xfechavencimiento) // ->setCellValue('Y'.$i, implode('/',array_reverse(explode('-', $row['fechavencimiento']))))
			->setCellValue('AH'.$i, $row['horavencimiento'])
			->setCellValue('AI'.$i, $xfechareal) // ->setCellValue('Y'.$i, implode('/',array_reverse(explode('-', $row['fechavencimiento']))))
			->setCellValue('AJ'.$i, $row['horareal'])
			->setCellValue('AK'.$i, $dif)
			->setCellValue('AL'.$i, $row['horastrabajadas'])
			->setCellValue('AM'.$i, $row['periodo'])
			->setCellValue('AN'.$i, $row['comentario']);
			
			//ESTILOS
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':AJ'.$i)->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':AJ'.$i)->getAlignment()->applyFromArray(
						array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));
			$objPHPExcel->getActiveSheet()->getStyle('Z'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
			$objPHPExcel->getActiveSheet()->getStyle('Z'.$i)->getAlignment()->applyFromArray(
						array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
			$objPHPExcel->getActiveSheet()->getStyle('AF'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
			$objPHPExcel->getActiveSheet()->getStyle('AF'.$i)->getAlignment()->applyFromArray(
						array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
			$objPHPExcel->getActiveSheet()->getStyle('AB'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
			$objPHPExcel->getActiveSheet()->getStyle('AB'.$i)->getAlignment()->applyFromArray(
						array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
			$objPHPExcel->getActiveSheet()->getStyle('AD'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
			$objPHPExcel->getActiveSheet()->getStyle('AD'.$i)->getAlignment()->applyFromArray(
						array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
			$objPHPExcel->getActiveSheet()->getStyle('AH'.$i)->getAlignment()->applyFromArray(
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(24);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(24);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setAutoSize(true);

		//Renombrar hoja de Excel
		$objPHPExcel->getActiveSheet()->setTitle('Incidentes - Laboratorios');

		//Redirigir la salida al navegador del cliente
		$hoy = date('dmY');
		$nombreArc = 'Correctivos - '.$hoy.'.xls';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$nombreArc.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();
	}

?>