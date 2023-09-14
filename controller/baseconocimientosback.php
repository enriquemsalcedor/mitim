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
		case "comentarios":
			  comentarios();
			  break; 
		case "abrirIncidente":
			  abrirIncidente();
			  break;
		case  "historial":
			  historial();
			 break; 
		default:
			  echo "{failure:true}";
			  break;
	}
	
	function incidentes()
	{
		global $mysqli;
		
		$where = "";
		$where2 = array();
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		//contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);
		/*----------------------------------------------------------------------
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		//Obtener el nombre de la columna de clasificación de su índice
		$orderBy= (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ?$_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );
		//ASC or DESC*/
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); 
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		/*--------------------------------------------------------------------*/
		$usuario 			 = (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : 0);
		
        $vacio = array();
		$columns   = (!empty($_REQUEST['columns']) ? $_REQUEST['columns'] : $vacio);

		/* $query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Incidentes' AND usuario = '".$usuario."'";
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
				$where .= " AND a.fechacreacion >= $desdef ";
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where .= " AND a.fechacreacion <= $hastaf ";
			}
			if(!empty($data->categoriaf)){
				$categoriaf = json_encode($data->categoriaf);
				$where .= " AND a.idcategoria IN ($categoriaf)";
			}
			if(!empty($data->subcategoriaf)){
				$subcategoriaf = json_encode($data->subcategoriaf);
				$where .=  " AND a.idsubcategoria IN ($subcategoriaf)";
			}			
			if(!empty($data->idempresasf)){
				$idempresasf = json_encode($data->idempresasf);
				$where .= " AND a.idempresas IN ($idempresasf)"; 
			}
			if(!empty($data->iddepartamentosf)){
				$iddepartamentosf = json_encode($data->iddepartamentosf);
				$where .= " AND a.iddepartamentos IN ($iddepartamentosf)"; 
			}
			if(!empty($data->idclientesf)){
				$idclientesf = json_encode($data->idclientesf);
				$where .= " AND a.idclientes IN ($idclientesf)"; 
			}
			if(!empty($data->idproyectosf)){
				$idproyectosf = json_encode($data->idproyectosf);
				$where .= " AND a.idproyectos IN ($idproyectosf)"; 
			}
			if(!empty($data->prioridadf)){
				$prioridadf = json_encode($data->prioridadf);
				$where .= " AND a.idprioridad IN ($prioridadf)";
			}
			if(!empty($data->modalidadf)){
				$modalidadf = json_encode($data->modalidadf);
				$where .= " AND m.modalidad IN ($modalidadf)";
			}
			if(!empty($data->marcaf)){
				$marcaf = json_encode($data->marcaf);
				$where .= " AND m.marca IN ($marcaf)"; 
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				$where .= " AND a.solicitante IN ($solicitantef)";
			}
			if(!empty($data->estadof)){
				$estadof = json_encode($data->estadof);
				$where .= " AND a.estado IN ($estadof)";
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
					$where .= " AND a.asignadoa IN ($asignadoaf)";	
				}
			}
			if(!empty($data->unidadejecutoraf)){
				$unidadejecutoraf = json_encode($data->unidadejecutoraf);
				 if($unidadejecutoraf !== '[""]'){ 
					$where .= " AND a.unidadejecutora IN ($unidadejecutoraf)";
				}
			}	
			$vowels = array("[", "]");
			$where .= str_replace($vowels, "", $where);
		} */
		
		$usuario 		 = $_SESSION['usuario'];
		$nivel 			 = $_SESSION['nivel'];
		$idempresas 	 = $_SESSION['idempresas'];
		$iddepartamentos = $_SESSION['iddepartamentos'];
		$idclientes 	 = $_SESSION['idclientes'];
		$idproyectos 	 = $_SESSION['idproyectos'];		
		$query  = " SELECT a.id, e.nombre AS estado, LEFT(a.titulo,45) as titulo,
					a.titulo as titulott,
					LEFT(a.descripcion,45) as descripcion,
					a.descripcion as descripciontt,
					LEFT(a.resolucion,45) as resolucion,
					a.resolucion as resoluciontt,
					LEFT(a.observaciones,45) as observaciones,
					a.observaciones as observacionestt,
					IFNULL(j.nombre, a.solicitante) AS solicitante, 
					a.fechacreacion, a.horacreacion, a.fechacierre,
					b.nombre AS idproyectos, f.nombre AS categoria, g.nombre AS subcategoria,
					a.asignadoa, l.nombre AS nomusuario, c.nombre AS unidadejecutora, m.serie, 
					mar.nombre AS marca, mo.nombre AS modelo, h.prioridad, a.fecharesolucion, 
					case when a.fechacierre IS NULL OR LENGTH(ltrim(rTrim(a.fechacierre))) = 0
					then a.fechacreacion else a.fechacierre end as fechaorden,
					n.descripcion as idempresas, p.nombre as idclientes, 
					a.idcategorias, co.comentario, s.nombre AS modalidad
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN activos m ON a.idactivos = m.id AND a.idambientes = m.id
					LEFT JOIN empresas n ON a.idempresas = n.id 
					LEFT JOIN clientes p ON a.idclientes = p.id
					LEFT JOIN marcas mar ON m.idmarcas = mar.id
					LEFT JOIN modelos mo ON m.idmodelos = mo.id
					LEFT JOIN activostipos s ON s.id = m.idtipo
					LEFT JOIN comentarios co ON a.id = co.idmodulo
					";
		if($nivel != 1 && $nivel != 2){
			$query .= " LEFT JOIN usuarios q ON find_in_set(c.id, q.idambientes) AND q.usuario = '$usuario' ";
		}
		//$query  .= " WHERE a.idcategoria not in (12,22,35,43) ";
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
			if($_SESSION['idambientes'] != ''){
				$idambientes = $_SESSION['idambientes'];
				$idambientes = explode(',',$idambientes);
				$idambientes = implode("','", $idambientes);
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.idambientes IN ('".$idambientes."') OR a.idclientes in ($idclientes) ) ";
				//$query  .= " OR (j.usuario = '".$_SESSION['usuario']."' OR a.idambientes IN ('".$sitio."') ) ";
			}else{
				//$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
				if($_SESSION['iddepartamentos'] != ''){
					$iddepartamentosSES = $_SESSION['iddepartamentos'];
					$query  .= "AND FIND_IN_SET(a.iddepartamentos,'".$iddepartamentosSES."')  ";
				}else{
					$query  .= " OR j.usuario = '".$_SESSION['usuario']."' ";
				}
			}			
		}
		
		$hayFiltros = 0;
        
        //for($i=0 ; $i<count($_REQUEST['columns']);$i++){//JOSDAN

		for($i=0 ; $i<count($columns);$i++){
			$column = $_REQUEST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			if ($_REQUEST['columns'][$i]['search']['value']!="") {
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);

				if ($column == 'id') {
					$column = 'a.id';
	                //$where2[]= " $column = '".$campo."' ";
    				$where2[]= " $column like '%".$campo."%' ";

				}
				
				if ($column == 'tipo') {

					$column = 'a.tipo';
					$where2[]= " $column like '%".$campo."%' ";

					//if($campo == 'PREV' || $campo == 'prev'){
					//	$column = 'a.tipo';
					//	$where2[]= " $column = 'preventivos'";
					//}elseif($campo == 'INC' || $campo == 'inc'){
					//	$column = 'a.tipo';
					//	$where2[]= " $column = 'incidentes'";
					//}else{
					//	$column = 'a.tipo';
    				//	$where2[]= " $column like '%".$campo."%' ";
                    //echo " $column like '%".$campo."%' ";
					//} 
				} 
				if ($column == 'titulo') {
					$column = 'a.titulo';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'descripcion') {
					$column = 'a.descripcion';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'estado') {
					$column = 'e.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}				
				if ($column == 'idempresas') {
					$column = 'n.descripcion';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'iddepartamentos') {
					$column = 'o.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'idclientes') {
					$column = 'p.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'idproyectos') {
					$column = 'b.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'idcategoria') {
					$column = 'f.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'idsubcategoria') {
					$column = 'g.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'asignadoa') {
					$column = 'l.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'sitio') {
					$column = 'c.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'modalidad') {
					$column = 'm.modalidad';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'serie') {
					$column = 'm.serie';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'idprioridad') {
					$column = 'h.prioridad';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'solicitante') {
					$column = 'j.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'fechacierre') {
					$column = 'a.fechacierre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'fechacreacion') {
					$column = 'a.fechacreacion';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'horacreacion') {
					$column = 'a.horacreacion';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'marca') {
					$column = 'mar.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'modelo') {
					$column = 'mo.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'resolucion') {
					$column = 'a.resolucion';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				if ($column == 'observaciones') {
					$column = 'a.observaciones';
					$where2[]= " $column like '%".$campo."%' ";
				} 
				$hayFiltros++;
			}
		}

        //echo $where2;
		if ($hayFiltros > 0)
			$where = " AND ".implode(" AND " , $where2)." ";// id like '%searchValue%' or name like '%searchValue%'

		$searchGeneral   = (!empty($_POST['search']['value']) ? $_POST['search']['value'] : '');		
		if($searchGeneral != ''){
		
			/* $searchGeneral = rtrim($searchGeneral," ");
			$sentence = str_replace(" "," +",$searchGeneral);
			$sentence = '+'.$sentence;
			$where .= " AND (
							MATCH(a.titulo) AGAINST ('$sentence' IN BOOLEAN MODE) OR
							MATCH(a.descripcion) AGAINST ('$sentence' IN BOOLEAN MODE) OR
							MATCH(a.resolucion) AGAINST ('$sentence' IN BOOLEAN MODE) OR
							MATCH(a.observaciones) AGAINST ('$sentence' IN BOOLEAN MODE) OR
							MATCH(co.comentario) AGAINST ('$sentence' IN BOOLEAN MODE) 
						)"; */
			
			
			$where.= " AND (
				a.id like '%".$searchGeneral."%' OR
				a.tipo like '%".$searchGeneral."%' OR
				a.titulo like '%".$searchGeneral."%' OR
				a.descripcion like '%".$searchGeneral."%' OR
				e.nombre like '%".$searchGeneral."%' OR 
				p.nombre like '%".$searchGeneral."%' OR
		    	b.nombre like '%".$searchGeneral."%' OR
				f.nombre like '%".$searchGeneral."%' OR
				g.nombre like '%".$searchGeneral."%' OR
				l.nombre like '%".$searchGeneral."%' OR
				c.nombre like '%".$searchGeneral."%' OR 
				m.serie like '%".$searchGeneral."%' OR
				h.prioridad like '%".$searchGeneral."%' OR
				j.nombre like '%".$searchGeneral."%' OR
				a.fechacierre like '%".$searchGeneral."%' OR
				a.fechacreacion like '%".$searchGeneral."%' OR
				a.horacreacion like '%".$searchGeneral."%' OR
				mar.nombre like '%".$searchGeneral."%' OR
				mo.nombre like '%".$searchGeneral."%' OR
				a.resolucion like '%".$searchGeneral."%' OR
				a.observaciones like '%".$searchGeneral."%' OR 
				s.nombre LIKE '%".$searchGeneral."%'
			)"; 
		}
		$query  .= " $where ";
		
		$query .= " GROUP BY a.id ";
		
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
        //$query  .= " ORDER BY a.id desc ";
		$query  .= " ORDER BY a.id desc LIMIT $start, $length ";
		//$query  .= " ORDER BY a.id desc";		
		debugL($query,"CARGAR-BASECONOCIMIENTOS");
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		while($row = $result->fetch_assoc()){
			$solicitante = $row['solicitante'];
			//ADJUNTOS INCIDENTES
			$tieneEvidencias   = '';
			$rutaE 		= '../incidentes/'.$row['id'];
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
			$coment = " SELECT visto FROM comentarios WHERE idmodulo = '".$row['id']."' ";			
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
			
			if($row['idcategorias'] == 12 || $row['idcategorias'] == 22 || $row['idcategorias'] == 35 || $row['idcategorias'] == 43){
				$tipo = 'PREV';
			}else{
				$tipo = 'INC';
			}

			$longtitulo = strlen($row['titulo']);
			if($longtitulo>42){
				$points = " ...";
				$titulo = "<span data-toggle='tooltip' class='prueba' data-placement='right' data-original-title='".$row['titulott']."'>".$row['titulo'].$points."</span>";
			}else{ 
				$titulo = $row['titulo'];
			}

			$descripcion ="";
			$longdescripcion = strlen($row['descripcion']);
			if($longdescripcion>42){
				$points = " ...";
				$descripcion = "<span data-toggle='tooltip' class='prueba' data-placement='right' data-original-title='".$row['descripciontt']."'>".$row['descripcion'].$points."</span>";
			}else{ 
				$descripcion = $row['descripcion'];
			}


			$resolucion ="";
			$resolucion = strlen($row['resolucion']);
			if($resolucion>42){
				$points = " ...";
				$resolucion = "<span data-toggle='tooltip' class='prueba' data-placement='right' data-original-title='".$row['resoluciontt']."'>".$row['resolucion'].$points."</span>";
			}else{ 
				$resolucion = $row['resolucion'];
			}

			$observaciones ="";
			$longobservaciones = strlen($row['observaciones']);
			if($longobservaciones>42){
				$points = " ...";
				$observaciones = "<span data-toggle='tooltip' class='prueba' data-placement='right' data-original-title='".$row['observacionestt']."'>".$row['observaciones'].$points."</span>";
			}else{ 
				$observaciones = $row['observaciones'];
			}

		    $acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center">
								    <a class="dropdown-item text-warning" href="basedeconocimiento-v.php?id='.$row['id'].'"><i class="fas fa-eye mr-2"></i>Ver</a>
								</div>
							</div>
						</td>';
			
		    $resultado[] = array(
				'acciones' 			=> $acciones, 	
				'id' 				=> $row['id'],
				'tipo' 				=> $tipo,
				'estado' 			=> $row['estado'],
				'titulo' 			=> $titulo,
				'descripcion' 		=> $descripcion,
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
				'sitio'				=> $row['unidadejecutora'],
				'modalidad'			=> $row['modalidad'],
				'serie'				=> $row['serie'],
				'marca'				=> $row['marca'],
				'modelo'			=> $row['modelo'],
				'idprioridad'		=> $row['prioridad'],
				'fechacierre'		=> $row['fechacierre'],
				'resolucion'		=> $resolucion,
				'observaciones'		=> $observaciones
			);
		}
        $response = array(			
		  "draw" => intval($draw),
		  "recordsTotal" => intval($recordsTotal),
		  "recordsFiltered" => intval($recordsTotal),
		  "data" => $resultado
		);
		echo json_encode($response);
	}
	
	function comentarios(){
		global $mysqli;
		$nivel = $_SESSION['nivel'];
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$buscar = (isset($_POST['buscar']) ? $_POST['buscar'] : '');
		$x = array(
		//		'id' => '', 'acciones' => '', 'comentario' => '', 'nombre' => '', 'visibilidad' => '', 'fecha' => '', 'adjuntos' => ''
		);
		$response = array('data'=>$x);
		$acciones = '';

		$query  = " SELECT a.id, a.idmodulo, a.comentario, a.fecha, b.nombre, a.visibilidad
					FROM comentarios a
					LEFT JOIN usuarios b ON a.usuario = b.usuario
					WHERE modulo = 'Incidentes' AND idmodulo = $id AND a.visibilidad != '' ";
		if($nivel == 4){
			$query .= " AND a.visibilidad = 'Público' ";
		}
		$query .= " ORDER BY a.id DESC ";
		
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			//ADJUNTOS
			$adjuntos   = '';
			$ruta 		= '../incidentes/'.$row['idmodulo'].'/comentarios/'.$row['id'];
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
			if($nivel == 4){
				$acciones = " <div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>
									<span class='icon-col ".$color." fa fa-camera boton-adjuntos-comentarios' data-id='".$row['idmodulo']."-".$row['id']."' data-toggle='tooltip' data-original-title='Adjuntos Comentario' data-placement='right'></span> 
								</div> ";
			}else{
				$acciones = " <div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>
									<span class='icon-col red fa fa-trash boton-eliminar-comentarios' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Comentario' data-placement='right'></span> 
									<span class='icon-col ".$color." fa fa-camera boton-adjuntos-comentarios' data-id='".$row['idmodulo']."-".$row['id']."' data-toggle='tooltip' data-original-title='Adjuntos Comentario' data-placement='right'></span> 
								</div> ";
			}
			$response['data'][] = array(				
				'id' 			=> $row['id'],
				'acciones' 		=> $acciones,
				'comentario' 	=> $row['comentario'],
				'nombre'		=> $row['nombre'],
				'visibilidad'	=> $row['visibilidad'],
				'fecha' 		=> $row['fecha'],
				'adjuntos' 		=> $adjuntos
			);
		}
/*		if($response == ''){
			$response['data'][] = array(				
			);
		}*/
		echo json_encode($response);
	}
	
	function abrirIncidente(){
		global $mysqli;
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
//		$resultado_old 	 = "";
		$resultado 	 = array ();
		$query_old  = " SELECT a.id, a.titulo, a.descripcion, b.id AS idproyectos,
					 c.id AS unidad, d.id AS serie, d.activo,  q.nombre as marca, r.nombre as modelo, e.id AS estado, 
					f.id AS categoria, g.id AS subcategoria, h.id AS prioridad, 
					a.solicitante, a.asignadoa, a.departamento, d.modalidad,
					CONCAT_WS('',j.id,' - ', j.titulo) AS fusionado, a.notificar, a.resolucion,
					a.reporteservicio, a.estadomantenimiento, a.observaciones, a.fechacertificar, a.horario,
					a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, a.comentariosatisfaccion,
					IFNULL(k.nombre, a.resueltopor) AS resueltopor, 
					IF(a.fechacreacion!='', a.fechacreacion,'') AS fechacreacion, a.horacreacion,
					IF(a.fechavencimiento!='',CONCAT(a.fechavencimiento,'  ', IFNULL(a.horavencimiento,'')),'') AS fechavencimiento,
					IF(a.fecharesolucion!='',CONCAT(a.fecharesolucion,'  ', IFNULL(a.horaresolucion,'')),'') AS fecharesolucion,
					a.fechacierre, a.horacierre,a.fechamodif, a.fechacertificar, a.horastrabajadas,
					n.id as idempresas, o.id as iddepartamentos, p.id as idclientes, a.atencion, 

					a.fechadesdefueraservicio, a.fechafinfueraservicio, a.numeroaceptacion,
					(CASE WHEN a.fechafinfueraservicio is null || a.fechafinfueraservicio = '' then (TIMESTAMPDIFF(DAY, a.fechadesdefueraservicio, CURRENT_DATE)) ELSE (TIMESTAMPDIFF(DAY, a.fechadesdefueraservicio, a.fechafinfueraservicio)) END) as diasfueraservicio
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN activos d ON a.idactivos = d.id AND d.id != ''
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					LEFT JOIN incidentes j ON a.fusionado = j.id
					LEFT JOIN usuarios k ON a.resueltopor = k.correo
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					LEFT JOIN marcas q ON d.idmarcas = q.id
					LEFT JOIN modelos r ON d.idmodelos = r.id
					WHERE a.id = $id ";
		//debug($query);
		$query = "SELECT		a.id, 
			a.titulo, 
			a.descripcion, 
			b.id AS idproyectos,
			c.id AS unidad, 
			d.id AS serie, 
			d.activo, 
			q.nombre as marca, 
			r.nombre as modelo, 
			e.id AS estado,
			f.id AS categoria,
			g.id AS subcategoria,
			h.id AS prioridad,
			a.solicitante,
			a.asignadoa,
			a.departamento, 
			d.modalidad,
			CONCAT_WS('',j.id,' - ', j.titulo) AS fusionado,
			 a.notificar,
			 a.resolucion,
			 a.reporteservicio,
			 a.estadomantenimiento,
			 a.observaciones,
			 a.fechacertificar,
			 a.horario,
			 a.origen,
			 
			 a.periodo,
			 a.numeroaceptacion,			 
			 
			 IFNULL(i.nombre, a.creadopor) AS creadopor, a.comentariosatisfaccion,
			 IFNULL(k.nombre, a.resueltopor) AS resueltopor, 
			 IF(a.fechacreacion!=null, a.fechacreacion,'') AS fechacreacion, a.horacreacion,
			 IF(a.fechavencimiento!=null,CONCAT(a.fechavencimiento,'  ', IFNULL(a.horavencimiento,'')),'') AS fechavencimiento,
			 IF(a.fecharesolucion!=null,CONCAT(a.fecharesolucion,'  ', IFNULL(a.horaresolucion,'')),'') AS fecharesolucion,
			 a.fechacierre,
			 a.horacierre,a.fechamodif,
			 a.fechacertificar,
			 a.horastrabajadas,
			 n.id as idempresas,
			 o.id as iddepartamentos,
			 p.id as idclientes,
			 a.atencion, 
			 a.fechadesdefueraservicio,
			 a.fechafinfueraservicio,
			 a.fueraservicio,
			 (CASE WHEN a.fechafinfueraservicio is null  then (TIMESTAMPDIFF(DAY, a.fechadesdefueraservicio, CURRENT_DATE)) ELSE (TIMESTAMPDIFF(DAY, a.fechadesdefueraservicio, a.fechafinfueraservicio)) END) as diasfueraservicio
			
			FROM incidentes a

			LEFT JOIN proyectos b ON a.idproyectos = b.id
			LEFT JOIN ambientes c ON a.idambientes = c.id
			LEFT JOIN activos d ON a.idactivos = d.id AND d.id != ''
			LEFT JOIN estados e ON a.idestados = e.id
			LEFT JOIN categorias f ON a.idcategorias = f.id
			LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
			LEFT JOIN sla h ON a.idprioridades = h.id
			LEFT JOIN usuarios i ON a.creadopor = i.correo
			LEFT JOIN incidentes j ON a.fusionado = j.id
			LEFT JOIN usuarios k ON a.resueltopor = k.correo
			LEFT JOIN empresas n ON a.idempresas = n.id
			LEFT JOIN departamentos o ON a.iddepartamentos = o.id
			LEFT JOIN clientes p ON a.idclientes = p.id
			LEFT JOIN marcas q ON d.idmarcas = q.id
			LEFT JOIN modelos r ON d.idmodelos = r.id
			WHERE a.id = $id";		
//		echo $query;
		$result = $mysqli->query($query);

//		echo $query;

		while($row = $result->fetch_assoc()){
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
						'fechadesdefueraservicio' => $row['fechadesdefueraservicio'],
						'fechafinfueraservicio' => $row['fechafinfueraservicio'],
						'diasfueraservicio'		=> $row['diasfueraservicio'],
						'fueraservicio' 		=> $row['fueraservicio'],
						'fechamodif' 			=> $row['fechamodif'],
						'fechacertificar' 		=> $row['fechacertificar'],
						'horastrabajadas' 		=> $row['horastrabajadas'],
						'periodo' 				=> $row['periodo'],
						'atencion' 				=> $row['atencion']
					);
		}
		echo json_encode($resultado);
	}
	
	function historial(){
		global $mysqli;
		$nivel = $_SESSION['nivel'];
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$response_old = '';
		$x = array(
//				'id' => '','usuario' => '', 'fecha' => '', 'accion'	=> ''
		    );
		$response = array('data' => $x);
		
		$query  = "SELECT id, usuario, fecha, accion
					FROM bitacora
					WHERE modulo = 'Incidentes' AND identificador = $id
					ORDER BY id DESC ";
		
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			$response['data'][] = array(				
				'id' 			=> $row['id'],
				'usuario' 	=> $row['usuario'],
				'fecha'		=> $row['fecha'],
				'accion'	=> $row['accion']
			);
		}
//		if($response['data'])

/*
		if($response == ''){
			$response['data'][] = array(				
				'id' => '','usuario' => '', 'fecha' => '', 'accion'	=> ''
			);
		}*/
		echo json_encode($response);
	}
?>