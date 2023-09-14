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
		case "incidentesgantt":
			  incidentesgantt();
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
		case "verificarfiltros":
			 verificarfiltros();
			 break;
		case "comentariosleidos":
			 comentariosleidos();
			 break;
		case "exportarExcelConComentarios":
			 exportarExcelConComentarios();
			 break;
		case "importaractividades":
			 importaractividades();
			 break;
		case "updatecomentario":
			 updatecomentario();
			 break;
		case "getcomentario":
			  getcomentario();	  
			 break;
		case "guardarcolumnaocultar":
			  guardarcolumnaocultar();
			  break;
		case "consultarcolumnas":
			  consultarcolumnas();
			  break;
		case  "notificacionAdjunto":
			  notificacionAdjunto();
			  break;	 
		default:
			  echo "{failure:true}";
			  break;
	}


	function incidentes()
	{
		global $mysqli;
		
		//FILTROS MASIVO 
		$usuario = (!empty($_SESSION['usuario']) ? $_SESSION['usuario']: '');
		
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Postventas' AND usuario = '".$usuario."'";
		$result = $mysqli->query($query);
		
		$where = "";  
		$where2 = "";  


		/*$data2   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');		
		$searchGeneral   = (!empty($_POST['search']['value']) ? $_POST['search']['value'] : '');		
		$data = "";
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : '');
	    $start    = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$rowperpage   = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
        $vacio = array();
		$columns   = (!empty($_REQUEST['columns']) ? $_REQUEST['columns'] : $vacio);*/
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
			if(!empty($data->idcategoriasf)){
				$idcategoriasf = json_encode($data->idcategoriasf);
				if($idcategoriasf != '[""]'){
					$where2 .= " AND a.idcategorias IN ($idcategoriasf)";
				}
			}
			if(!empty($data->idsubcategoriasf)){
				$idsubcategoriasf = json_encode($data->idsubcategoriasf);
				if($idsubcategoriasf != '[""]'){
					$where2 .= " AND a.idsubcategorias IN ($idsubcategoriasf)";
				}
			}			
			if(!empty($data->idempresasf)){
				$idempresasf = json_encode($data->idempresasf);
				if($idempresasf != '[""]'){
					$where2 .= " AND a.idempresas IN ($idempresasf)"; 
				}				
			}
			if(!empty($data->iddepartamentosf)){
				$iddepartamentosf = json_encode($data->iddepartamentosf);
				$where2 .= " AND a.iddepartamentos IN ($iddepartamentosf)"; 
			}
			if(!empty($data->idclientesf)){
				$idclientesf = json_encode($data->idclientesf);
				if($idclientesf != '[""]'){
					$where2 .= " AND a.idclientes IN ($idclientesf)"; 
				}				
			}
			if(!empty($data->idproyectosf)){
				$idproyectosf = json_encode($data->idproyectosf);
				if($idproyectosf != '[""]'){
					$where2 .= " AND a.idproyectos IN ($idproyectosf)"; 
				}				
			}
			if(!empty($data->idprioridadesf)){
				$idprioridadesf = json_encode($data->idprioridadesf);
				if($idprioridadesf != '[""]'){
					$where2 .= " AND a.idprioridades IN ($idprioridadesf)";
				}				
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				if($solicitantef != '[""]'){
					$where2 .= " AND a.solicitante IN ($solicitantef)";
				}
			}
			if(!empty($data->idestadosf)){
				$idestadosf = json_encode($data->idestadosf);
				if($idestadosf != '[""]'){
					$where2 .= " AND a.idestados IN ($idestadosf)";
				}
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
			if(!empty($data->idambientesf)){
				$idambientesf = json_encode($data->idambientesf);
				 if($idambientesf !== '[""]'){ 
					$where2 .= " AND a.idambientes IN ($idambientesf)";
				}
			}					
			$vowels = array("[", "]");
			$where2 = str_replace($vowels, "", $where2);
		}
		
		$usuario	 = $_SESSION['usuario'];
		$nivel		 = $_SESSION['nivel'];		
	//	$idusuario	 = $_SESSION['user_id'];
		$idusuario	 = $_GET['user_id'];
		$queryj 	 = "SELECT idempresas, idclientes, idproyectos, iddepartamentos from usuarios where id = ".$idusuario;
		$resultj 	 = $mysqli->query($queryj); 
		$row 		 = $resultj->fetch_assoc();
		$idempresas  = $row['idempresas'];
		$idclientes  = $row['idclientes'];
		$idproyectos = $row['idproyectos'];
		$iddepartamentos = $row['iddepartamentos'];
		
		$query  = " SELECT a.id, e.nombre AS estado, LEFT(a.titulo,45) as titulo, IFNULL(j.nombre, a.solicitante) AS solicitante, a.fechacreacion,
					a.horacreacion, a.fechacierre, b.nombre AS proyecto, f.nombre AS categoria, g.nombre AS subcategoria, a.asignadoa, 
					l.nombre AS nomusuario, c.nombre AS ambiente, m.serie, mar.nombre as marca, r.nombre as modelo, m.modalidad, h.prioridad, 
					a.fecharesolucion, a.fechareal,	IFNULL(a.fechacierre,a.fechacreacion) AS fechaorden, n.descripcion as empresa, 
					o.nombre as departamento, p.nombre as cliente 
					FROM postventas a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN activos m ON a.idactivos = m.id
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					LEFT JOIN marcas mar ON m.idmarcas = mar.id
					LEFT JOIN modelos r ON m.idmodelos = r.id
					";		
		if($nivel != 1 && $nivel != 2){
			$query .= " LEFT JOIN usuarios q ON find_in_set(c.id, q.idambientes) AND q.usuario = '".$usuario."' ";
		}
		$query .= " WHERE 1 ";
		//$query  .= " WHERE f.tipo = 'postventas' OR (f.tipo = 'correctivos' AND f.nombre = 'Tx Post Venta') ";
		
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
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.idambientes IN ('".$sitio."') ) ";
			}else{
				//$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
			}
			
		} 
		$query  .= " $where2"; 

		/*$where3 = array();
		$hayFiltros = 0;
		for($i=0 ; $i<count($columns);$i++){
			$column = $_REQUEST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			if ($_REQUEST['columns'][$i]['search']['value']!="") {


				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);

				if ($column == 'id') {
					$column = 'a.id';
					$where3[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'estado') {
					$column = 'e.nombre';
					$where3[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'titulo') {
					$column = 'a.titulo';
					$where3[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'solicitante') {
					$column = 'a.solicitante';
					$where3[] = " $column like '%".$campo."%' ";

					$column = 'j.nombre';
					$where3[] = " $column like '%".$campo."%' ";

				}
				if ($column == 'fechacreacion') {
					$column = 'a.fechacreacion';
					$where3[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'horacreacion') {
					$column = 'a.horacreacion';
					$where3[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'horacreacion') {
					$column = 'a.horacreacion';
					$where3[] = " $column like '%".$campo."%' ";
				}

				if ($column == 'fechareal') {
					$column = 'a.fechareal';
					$where3[] = " $column like '%".$campo."%' ";
				}


				if ($column == 'empresa') {
					$column = 'n.descripcion';
					$where3[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'departamento') {
					$column = 'o.nombre';
					$where3[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'cliente') {
					$column = 'p.nombre';
					$where3[] = " $column like '%".$campo."%' ";
				}

				if ($column == 'proyecto') {
					$column = 'b.nombre';
					$where3[] = " $column like '%".$campo."%' ";
				}

				if ($column == 'categoria') {
					$column = 'f.nombre';
					$where3[] = " $column like '%".$campo."%' ";
				}
                
				if ($column == 'subcategoria') {
					$column = 'g.nombre';
					$where3[] = " $column like '%".$campo."%' ";
				}
                
				if ($column == 'asignadoa') {
					$column = 'a.asignadoa';
					$where3[] = " $column like '%".$campo."%' ";
				}
                
				if ($column == 'sitio') {
					$column = 'c.nombre';
					$where3[] = " $column like '%".$campo."%' ";
				}

				if ($column == 'modalidad') {
					$column = 'm.modalidad';
					$where3[] = " $column like '%".$campo."%' ";
				}

				if ($column == 'serie') {
					$column = 'm.serie';
					$where3[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'marca') {
					$column = 'mar.nombre';
					$where3[] = " $column like '%".$campo."%' ";
				}

				if ($column == 'modelo') {
					$column = 'r.nombre';
					$where3[] = " $column like '%".$campo."%' ";
				}

				if ($column == 'idprioridad') {
					$column = 'h.prioridad';
					$where3[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'fecharesolucion') {
					$column = 'a.fecharesolucion';
					$where3[] = " $column like '%".$campo."%' ";
				}



            //$where3[] = " $column like '%".$campo."%' ";


				$hayFiltros++;
			}
		}		
		
        //echo $hayFiltros;
		if ($hayFiltros > 0){
			$where = " AND ".implode(" AND " , $where3)." ";// id like '%searchValue%' or name like '%searchValue%'
		}else
			$where = "";

		if($searchGeneral!=""){
			$where.= " AND (
				a.id LIKE '%".$searchGeneral."%' OR
				e.nombre LIKE '%".$searchGeneral."%' OR
				a.titulo LIKE '%".$searchGeneral."%' OR
				a.solicitante LIKE '%".$searchGeneral."%' OR
				j.nombre LIKE '%".$searchGeneral."%' OR
				a.fechacreacion LIKE '%".$searchGeneral."%' OR
				a.horacreacion LIKE '%".$searchGeneral."%' OR
				a.horacreacion LIKE '%".$searchGeneral."%' OR
				a.fechareal LIKE '%".$searchGeneral."%' OR
				n.descripcion LIKE '%".$searchGeneral."%' OR
				o.nombre LIKE '%".$searchGeneral."%' OR
				p.nombre LIKE '%".$searchGeneral."%' OR
				b.nombre LIKE '%".$searchGeneral."%' OR
				f.nombre LIKE '%".$searchGeneral."%' OR
				g.nombre LIKE '%".$searchGeneral."%' OR
				a.asignadoa LIKE '%".$searchGeneral."%' OR
				c.nombre LIKE '%".$searchGeneral."%' OR
				m.modalidad LIKE '%".$searchGeneral."%' OR
				m.serie LIKE '%".$searchGeneral."%' OR
				mar.nombre LIKE '%".$searchGeneral."%' OR
				r.nombre LIKE '%".$searchGeneral."%' OR
				h.prioridad LIKE '%".$searchGeneral."%' OR
				a.fecharesolucion LIKE '%".$searchGeneral."%'

			) ";
			}
		$query.= $where;*/
    	$query  .= " GROUP BY a.id ";

		
		if(!$result = $mysqli->query($query)){ 
		  die($mysqli->error);  
		}
		
		$recordsTotal = $result->num_rows;
		$query  .= " ORDER BY a.id DESC ";
		//$query  .= " ORDER BY a.id DESC  LIMIT ".$start.",".$rowperpage;
		debugL("CARGAR-POSTVENTAS:".$query);
		
		$resultado = array();
		$result = $mysqli->query($query);
		//$recordsFiltered = $result->num_rows;
		while($row = $result->fetch_assoc()){
			$solicitante = $row['solicitante'];
			//ADJUNTOS INCIDENTES
			$tieneEvidencias   = '';
			$rutaE 		= '../postventas/'.$row['id'];
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
				$color = 'success';
			}else{
				$color = 'info';
			}
			/* $coment = " SELECT visto FROM compromisos WHERE idmodulo = '".$row['id']."' ";			
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
			} */ 
			
			$acciones = '<td>
							<div class="dropdown ml-auto">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-right droptable">
									<a class="dropdown-item text-info" href="postventa.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>';
								if($nivel == 1 || $nivel == 2){
									$acciones .=	'<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>';
								}
								$acciones .= '<a class="dropdown-item text-'.$color.' boton-evidencias"  data-id='.$row['id'].' "><i class="fas fa-camera mr-2"></i>Evidencias</a>'; 

			$acciones .= 		'</div>
							</div>
						</td>';
						
			$resultado[] = array(				
				'check' 			=>	"",
				'acciones' 			=> $acciones,
				'id' 				=> $row['id'],
				'estado' 			=> $row['estado'],
				'titulo' 			=> $row['titulo'],
				'solicitante'		=> $solicitante,
				'fechacreacion' 	=> $row['fechacreacion'],
				'horacreacion'		=> $row['horacreacion'],
				'fechareal'			=> $row['fechareal'],
				'empresa'			=> $row['empresa'],
				'departamento'		=> $row['departamento'],
				'cliente'			=> $row['cliente'],
				'proyecto'			=> $row['proyecto'],
				'categoria'			=> $row['categoria'],
				'subcategoria'		=> $row['subcategoria'],
				'asignadoa'			=> $row['nomusuario'],
				'sitio'				=> $row['ambiente'],
				'modalidad'			=> $row['modalidad'],
				'serie'				=> $row['serie'],
				'marca'				=> $row['marca'],
				'modelo'			=> $row['modelo'],
				'prioridad'			=> $row['prioridad'],
				'fecharesolucion'	=> $row['fecharesolucion']

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

	  
	function eliminarincidentes()
	{
		global $mysqli;
		$id 	= $_REQUEST['idincidente'];
		
		$query 	= " DELETE FROM postventas WHERE id = '".$id."' ";
		$result = $mysqli->query($query);
		if($result == true){
			echo 1;
		}else{
			echo 0;
		}
		bitacora($_SESSION['usuario'], "Postventas", 'La Visita #: '.$id.' fue eliminada.', $id, $query);				
	}
	
	function eliminarcomentarios()
	{
		global $mysqli;
		$id 	 = $_REQUEST['idcomentario']; 
		$nivel 	 = $_SESSION['nivel'];
		$usuario = $_SESSION['usuario'];
		 
		//Elimino el comentario si es usuario administrador o soporte
		if ($nivel == 1 || $nivel == 2){
			$queryEs  = " DELETE FROM compromisos WHERE id = '".$id."' ";
			$resultEs = $mysqli->query($queryEs);
			if($resultEs){
			    echo 1;
			}else{
			    echo 0;
			}			
		}else{
		    $queryNoes = " SELECT * FROM compromisos WHERE id = '".$id."' AND usuario = '".$usuario."' ";    			
    	    $resultNoes =    $mysqli->query($queryNoes);
    			
			if($resultNoes->num_rows > 0){
				$querySi  = " DELETE FROM compromisos WHERE id = '".$id."' AND usuario = '".$usuario."' ";
				$resultSi = $mysqli->query($querySi);
				if($resultSi == true){
					echo 1;
				}else{
					echo 0;
				}
			}else{
				echo 2;
			}			
		}  
		
		bitacora($_SESSION['usuario'], "Postventas", 'El Compromiso #: '.$id.' fue eliminado.', $id, $queryEs); 
	}

	function abrirSolicitudes() {
		$incidente 	= $_REQUEST['incidente'];		
		$_SESSION['incidentepv'] = $incidente;
		$_SESSION['comentariopv'] = '';
		$myPathInc = '../postventas';
		$target_pathInc = utf8_decode($myPathInc);
		if (!file_exists($target_pathInc)) {
			mkdir($target_pathInc, 0777);
		}
		$myPath = '../postventas/'.$incidente;
		$target_path = utf8_decode($myPath);
		if (!file_exists($target_path)) {
			mkdir($target_path, 0777);
		}
		//$Path = dirname($_SERVER['PHP_SELF']) . '/../incidentes/'.$_SESSION['incidente'].'/';
		$Path = '/../postventas/'.$incidente.'/';
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
		$visibilidad = $_REQUEST['visibilidad'];;
		$fecha 		= date("Y-m-d");
		$estado = $_REQUEST['estado'];
		$resolucion = $_REQUEST['resolucion'];
		$id_preventivo = 0;
		$queryI = "INSERT INTO compromisos VALUES(null, 'Postventas', ".$incidente.", '".$comentario."', '".$visibilidad."', '".$usuario."', NOW(), 'NO', '".$estado."', '".$resolucion."') ";
		//debug($queryI);
		if($mysqli->query($queryI)){
			$id = $mysqli->insert_id;
			//BITACORA
			bitacora($_SESSION['usuario'], "Postventas", "Se ha registrado un Compromiso para La visita #".$incidente, $incidente, $queryI);
			//ENVIAR NOTIFICACION
			notificarComentarios($incidente,$comentario,$visibilidad);
			//*******************************************//
			//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
			//*******************************************//
			
			//Usuarios asociados a la postventa
			$qInc = " 	SELECT b.usuario AS usuarioasignadoa, c.usuario AS usuariosolicitante, d.usuario AS correocreadopor
						FROM postventas a 
						INNER JOIN usuarios b ON b.correo = a.asignadoa 
						INNER JOIN usuarios c ON c.correo = a.solicitante
						INNER JOIN usuarios d ON d.correo = a.creadopor
						WHERE a.id = ".$incidente.""; 
			$rInc = $mysqli->query($qInc); 
			
			if ($r = $rInc->fetch_assoc()) {
				if($visibilidad != 'Privado'){
					if($r["usuarioasignadoa"] != "") $idusuarios[$r["usuarioasignadoa"]] = "0"; 
					if($r["usuariosolicitante"] != "") $idusuarios[$r["usuariosolicitante"]] = "0";
					if($r["correocreadopor"] != "") $idusuarios[$r["correocreadopor"]] = "0";
				}else{
					if($r["usuarioasignadoa"] != "") $idusuarios[$r["usuarioasignadoa"]] = "0";
				}
			}
			
			$verp = " SELECT idproyectos FROM incidentes WHERE id = ".$incidente."";
			$rverp = $mysqli->query($verp);
			if ($reg = $rverp->fetch_assoc()) {
				$idproyectos = $reg['idproyectos'];
			} 
			 
			//Usuarios de soporte
			$idusuarios['icarvajal'] = "0";
			$idusuarios['frios'] = "0";
			$idusuarios['aanderson'] = "0"; 
			$idusuarios["admin"] = "0";
			
			$usuarios = json_encode($idusuarios);
			
			$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,fecha,hora,usuarios) VALUES (".$idproyectos.",".$incidente.",'Compromiso realizado postventa','". date("Y-m-d") ."','". date("H:i:s") ."','".$usuarios."')"; 
			
			$rsql = $mysqli->query($sql); 
			
			//*******************************************//
			//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
			//*******************************************//					 
			echo true;
		}else{
			echo false;
		}
	}
	
	function comentarios(){
		global $mysqli;
		
		$nivel 	   = $_SESSION['nivel'];
		$id 	   = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$buscar    = (isset($_POST['buscar']) ? $_POST['buscar'] : '');
		$resultado = array();

		$query  = " SELECT a.id, a.idmodulo, a.comentario, a.fecha, b.nombre, a.visibilidad, a.estado, a.resolucion
					FROM compromisos a
					LEFT JOIN usuarios b ON a.usuario = b.usuario
					WHERE modulo = 'Postventas' AND idmodulo = ".$id." ";
		if($nivel == 4){
			$query .= " AND a.visibilidad = 'Público' ";
		}
		$query .= " ORDER BY a.id DESC ";
		//debugL($query);
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		while($row = $result->fetch_assoc()){
			//ADJUNTOS
			$adjuntos   = '';
			$ruta 		= '../postventas/'.$row['idmodulo'].'/compromisos/'.$row['id'];
			if (is_dir($ruta)) { 
			  if ($dh = opendir($ruta)) { 
				$num = 1;
				while (($file = readdir($dh)) !== false) { 
					if ($file != "." && $file != ".." && $file != ".quarantine" && $file != ".tmb"){ 
						$nombrefile = $file;
						if($num > 1){
							$adjuntos .= ",<br> ";
						}
						$adjuntos .= "<a href='".dirname($_SERVER['PHP_SELF'])."/".$ruta."/".$file."' target='_blank'>".$nombrefile."</a>";
						$num++;
					}						
				} 
				closedir($dh); 
			  } 
			}
			if($adjuntos != ''){
				$color = 'success';
			}else{
				$color = 'info';
			}
			
			$acciones = '<td>
							<div class="dropdown ml-auto text-right">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-right droptable">
									<a class="dropdown-item text-info boton-editar-compromisos" data-id='.$row['id'].'><i class="fas fa-pen mr-2"></i>Editar</a>
									<a class="dropdown-item text-danger boton-eliminar-comentarios" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a><a class="dropdown-item text-'.$color.' boton-adjuntos-comentarios"  data-id='.$row['id'].' "><i class="fas fa-camera mr-2"></i>Evidencias de compromisos</a>'; 

			$acciones .= 		'</div>
							</div>
						</td>';
						
			$resultado[] = array(
				
				'id' 			=> $row['id'],
				'acciones' 		=> $acciones,
				'comentario' 	=> $row['comentario'],
				'nombre'		=> $row['nombre'],
				'visibilidad'	=> $row['visibilidad'],
				'fecha' 		=> $row['fecha'],
				'adjuntos' 		=> $adjuntos,
				'estado' 		=> $row['estado'],
				'resolucion' 	=> $row['resolucion']
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
	
	function getcomentario(){
		global $mysqli;
		
		$id	= $_REQUEST['idcomentario'];
		$query 		= "	SELECT * FROM compromisos WHERE id = '".$id."' ";
		$result 	= $mysqli->query($query);
		//debug($query);	
		while($row = $result->fetch_assoc()){
			$resultado = array(
				'comentario' 	=> $row['comentario'],
				'usuario' 	    => $row['usuario'],
				'fecha' 		=> $row['fecha'],
				'estado' 		=> $row['estado'],
				'resolucion' 	=> $row['resolucion']	
			);
		}
		
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}	

	function updatecomentario(){
		global $mysqli;
		
		$id 			= $_REQUEST['id'];
		$estado 		= (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : '');
		$resolucion 	= (!empty($_REQUEST['resolucion']) ? $_REQUEST['resolucion'] : '');
		$usuario	= (!empty($_REQUEST['usuario']) ? $_REQUEST['usuario'] : '');
		
		$query 	= "	UPDATE compromisos SET usuario = '".$usuario."',estado = '".$estado."', resolucion = '".$resolucion."' WHERE id = '".$id."' ";
		$result = $mysqli->query($query);	
		//debug($query);
		if($result == true){		    
		    bitacora($_SESSION['usuario'], "Postventas", "El compromiso #".$id." ha sido editado", $id , $query);		    
		    echo 1;
		}else{
		    echo 0;
		}
	}

	function comentariosleidos(){		
		global $mysqli;
		
		$idincidente 	= $_REQUEST['idincidente'];
		
		$query = " SELECT visto 
				   FROM compromisos 
				   WHERE visto = 'NO' 
				   AND idmodulo = '".$idincidente."' ";
		
		$result = $mysqli->query($query);
		
		if($result->num_rows > 0){
			
			$upd = " UPDATE compromisos 
					 SET visto = 'SI'
					 WHERE idmodulo = '".$idincidente."'
					 AND visto = 'NO' ";
			
			$resupd = $mysqli->query($upd);
			
			if($resupd == true){
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
		$_SESSION['incidentepv'] 	= $incidente;
		$_SESSION['comentariopv'] = $comentario;
		
		$myPathC 	  = '../postventas/'.$incidente.'/compromisos/';
		$target_pathC = utf8_decode($myPathC);
		if (!file_exists($target_pathC)) {
			mkdir($target_pathC, 0777);
		}
		$myPath 	 = '../postventas/'.$incidente.'/compromisos/'.$comentario;
		$target_path = utf8_decode($myPath);
		if (!file_exists($target_path)) {
			mkdir($target_path, 0777);
		}
		//$Path = dirname($_SERVER['PHP_SELF']) . '/../incidentes/'.$_SESSION['incidente'].'/';
		$Path = '../postventas/'.$incidente.'/compromisos/'.$comentario.'/';
		$hash = strtr(base64_encode($Path), '+/=', '-_.');
		$hash = rtrim($hash, '.');		
		echo "l1_". $hash;		
	}
	
	//ENVIAR CORREO DE NOTIFICACION DE COMENTARIO
	function notificarComentarios($incidente,$comentario,$visibilidad){
		global $mysqli;
		//CREADOR - SOLICITANTE - ASIGNADO
		$query  = " SELECT a.notificar,
					CASE 
						WHEN i.estado = 'Activo' 
							THEN IFNULL(i.correo, a.creadopor) 
						WHEN i.estado = 'Inactivo' 
							THEN '' 
						END 
						AS creadopor,
					CASE 
						WHEN j.estado = 'Activo' 
							THEN a.solicitante 
						WHEN j.estado = 'Inactivo' 
							THEN '' 
						END 
						AS solicitante,
					CASE 
						WHEN k.estado = 'Activo' 
							THEN a.asignadoa 
						WHEN k.estado = 'Inactivo' 
							THEN '' 
						END 
						AS asignadoa
					FROM postventas a
					LEFT JOIN usuarios i ON a.creadopor = i.id OR a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.asignadoa = k.correo
					WHERE a.id = ".$incidente." ";
		$result = $mysqli->query($query);
		while ($row = $result->fetch_assoc()) {
			/*
			if($visibilidad == 'Privado'){
				$correo [] = $row['creadopor'];
			}else
			*/
			if($visibilidad != 'Privado'){
				
				//Excluir usuarios inactivos campo Creado por
				if($row['creadopor'] != ""){
					$correo [] = $row['creadopor'];
				}
				
				//Excluir usuarios inactivos campo Solicitante
				if($row['solicitante'] != ""){
					$correo [] = $row['solicitante'];
				}
				
				$notificar = $row['notificar'];
				
				//Usuarios que quieren que se les notifique (Enviar Notificacion a)
				$notificar = json_decode($notificar);
				if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
					
					//Excluir usuarios inactivos campo Notificar a 
					$queryn = " SELECT correo FROM usuarios WHERE correo = '".$notificar."' AND estado = 'Activo' ";
					$consultan = $mysqli->query($queryn);
					if($recn = $consultan->fetch_assoc()){
						$correo [] = $notificar;	
					}		
					
				}else{
					if (is_array($notificar) || is_object($notificar)){
						foreach($notificar as $notif){
							
							//Excluir usuarios inactivos campo Notificar a 
								$queryn = " SELECT correo FROM usuarios WHERE correo = '".$notificar."' AND estado = 'Activo' ";
								$consultan = $mysqli->query($queryn);
								if($recn = $consultan->fetch_assoc()){
									$correo [] = $notif;	
								}
						}
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
					$query2 .= "correo = '".$row['asignadoa']."' AND estado = 'Activo' ";
				}else{
					$query2 .= "correo IN (".$row['asignadoa'].") AND estado = 'Activo' ";
				}
				$consulta = $mysqli->query($query2);
				while($rec = $consulta->fetch_assoc()){
					$asignadoaN .= $rec['nombre']." , ";
				}			
			}		
		}
		
		//DATOS DEL CORREO
		$usuarios = $_SESSION['usuario'];
		$consultaUA = $mysqli->query(" SELECT nombre FROM usuarios WHERE usuario = '$usuarios' LIMIT 1 ");
		while ($registroUA = $consultaUA->fetch_assoc()) {
			$usuarioAct = $registroUA['nombre'];
		}
		//DATOS
		$query  = " SELECT a.id, a.titulo, a.descripcion, c.nombre AS ambiente, a.resolucion,
					h.prioridad, a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, IFNULL(j.nombre, a.solicitante) AS solicitante, a.asignadoa,
					a.departamento, IF(a.fechacreacion IS NOT NULL,CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion					
					FROM postventas a
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					WHERE a.id = ".$incidente." ";
					
		$result 		= $mysqli->query($query);
		$row 			= $result->fetch_assoc();
		$fechacreacion 	= $row['fechacreacion'];
		$descripcion	= $row['descripcion'];
		$solicitante	= $row['solicitante'];
		$creadopor		= $row['creadopor'];
		$departamento	= $row['departamento'];
		$prioridad		= $row['prioridad'];
		$sitio 			= $row['ambiente'];
		$resolucion 	= $row['resolucion'];
		$nasignadoa 	= $asignadoaN;
		$comentarios	= '';
		$bitacora		= '';
		
		//COMENTARIOS
		$consultaC = $mysqli->query("SELECT comentario FROM compromisos WHERE idmodulo = ".$incidente." ");
		while ($registroC = $consultaC->fetch_assoc()) {
			$comentarios .= $registroC['comentario'].'<br>';
		}
		//BITACORA
		$consultaB = $mysqli->query("SELECT accion FROM bitacora WHERE identificador = ".$incidente." ");
		while ($registroB = $consultaB->fetch_assoc()) {
			$bitacora .= $registroB['accion'].'<br>';
		}
		
		$asunto = "Postventas Visita #$incidente - Compromiso";
		
		$mensaje  = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha añadido un compromiso a la visita #".$incidente."</b></p>			
					<p style='padding-left: 30px;width:100%;'>Compromiso ".$visibilidad.": ".$comentario."</p>
					<p style='width:100%;'><br><a href='http://toolkit.maxialatam.com/mitim/postventas.php?id=$incidente' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Añadir un compromiso</a></p>
					<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin-top: 30px;padding: 10px 10px 0 30px;width:100%;'>Compromisos anteriores</p>
					<p style='padding-left: 30px;width:100%;'>".$comentarios."</p>
					<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin-top: 30px;padding: 10px 10px 0 30px;width:100%;'>Actividad reciente</p>
					<p style='padding-left: 30px;width:100%;'>".$bitacora."</p>
					<br><br>
					<p  style='font-size: 18px;width:100%;'>".$creadopor." ha creado esta visita el ".$fechacreacion."</p>
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
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		
		debugL("notificarComentariosPOSTVENTA-CORREO:".json_encode($correo),"notificarComentariosPOSTVENTA");
		
		//debug($correo);
		enviarMensajeIncidente($asunto,$mensaje,$correo);
	}

	function abrirIncidente(){
		global $mysqli;
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado 	 = array();
		$query  = " SELECT a.id, a.titulo, a.descripcion,a.idempresas, a.idclientes, a.idproyectos, a.idcategorias, a.idsubcategorias, a.idambientes,  
					a.idprioridades, a.idestados, a.iddepartamentos, a.asignadoa, IF(a.fechacreacion IS NOT NULL, a.fechacreacion,'') AS fechacreacion, a.horacreacion,
					a.fechacierre, a.horacierre, IF(a.fecharesolucion IS NOT NULL,CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,					
					IF(a.fechareal IS NOT NULL,CONCAT(a.fechareal,'  ',a.horareal),'') AS fechareal, IFNULL(i.nombre, a.creadopor) AS creadopor,
					a.reporteservicio, a.horastrabajadas, a.resolucion, a.solicitante,CONCAT_WS('',j.id,' - ', j.titulo) AS fusionado, a.notificar					
					FROM postventas a
					LEFT JOIN postventas j ON a.fusionado = j.id
					LEFT JOIN usuarios i ON a.creadopor = i.correo				
					WHERE a.id = $id ";		 
		$result = $mysqli->query($query);
		//debug($query);
		while($row = $result->fetch_assoc()){
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
						'idclientes' 			=> $row['idclientes'],
						'idproyectos' 			=> $row['idproyectos'],
						'idcategorias' 			=> $row['idcategorias'],
						'idsubcategorias' 		=> $row['idsubcategorias'],
						'idambientes' 			=> $row['idambientes'],
						'idprioridades' 		=> $row['idprioridades'],
						'idestados' 			=> $row['idestados'],
						'iddepartamentos'		=> $row['iddepartamentos'],
						'asignadoa' 			=> $row['asignadoa'],
						'fechacreacion' 		=> $row['fechacreacion'],
						'horacreacion' 			=> $row['horacreacion'],
						'fechacierre' 			=> $row['fechacierre'],
						'horacierre' 			=> $row['horacierre'],
						'fecharesolucion' 		=> $row['fecharesolucion'],
						'fechareal' 			=> $row['fechareal'],
						'creadopor' 			=> $row['creadopor'],						
						'reporteservicio' 		=> $row['reporteservicio'],
						'horastrabajadas' 		=> $row['horastrabajadas'],
						'resolucion' 			=> $row['resolucion'],						
						'solicitante' 			=> $solicitante,						
						'fusionado' 			=> $row['fusionado'],
						'notificar' 			=> $row['notificar']
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
		$idambientes	 	= (!empty($data['idambientes']) ? $data['idambientes'] : '');
		$idactivos			= (!empty($data['idactivos']) ? $data['idactivos'] : '');
		$idestados 			= (!empty($data['idestados']) ? $data['idestados'] : 12);
		$idcategorias 		= (!empty($data['idcategorias']) ? $data['idcategorias'] : 0);
		$idsubcategorias 	= (!empty($data['idsubcategorias']) ? $data['idsubcategorias'] : 0);
		$idprioridades		= (!empty($data['idprioridades']) ? $data['idprioridades'] : 0);
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
		//$fechareal	 		= (!empty($data['fechareal']) ? $data['fechareal'] : date("H:i:s"));
		$fechareal	 		= (!empty($data['fechareal']) ? $data['fechareal'] : date("Ymd"));
		$horastrabajadas 	= (!empty($data['horastrabajadas']) ? $data['horastrabajadas'] : '0');
		$estadoInc 			= '';
	    
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
		
		$query = "  INSERT INTO postventas(id, titulo, descripcion, idambientes, idactivos, idestados, 
					idcategorias, idsubcategorias, idprioridades, origen, creadopor, solicitante, asignadoa, 
					departamento, fechacreacion, ";
		
		if($fechavencimiento != ''){
			$query .= "fechavencimiento, horavencimiento, ";
		}				
		$query .="  horacreacion, notificar, resolucion, reporteservicio, estadomantenimiento, 
					observaciones, fechacertificar, horario, fechareal, horareal, idempresas, idclientes, idproyectos, iddepartamentos)
					VALUES(null, '$titulo', '$descripcion', '$idambientes', '$idactivos', 
					'$idestados','$idcategorias', '$idsubcategorias', '$idprioridades', '$origen', '$creadopor', 
					'$solicitante', '$asignadoa', '$departamento', '$fechacreacion', ";
		if($fechavencimiento !=''){
			$query .= "'$fechavencimiento', '$horavencimiento',  ";
		}	  
		$query .= " '$horacreacion', '$notificar', '$resolucion', '$reporteservicio', '$estadomtto', 
					'$observaciones', '$fechacertificar', '$horario','$fechareal', '$horacreacion',
					'$idempresas', '$idclientes', '$idproyectos', '$iddepartamentos') ";		 
		
		//debugL($query);
		if($mysqli->query($query)){
			$id = $mysqli->insert_id;
			if($id != ''){
				//CREAR CARPETA DE ID INCIDENTES Y COMENTARIOS
				$myPath = '../postventas/';
				if (!file_exists($myPath))
					mkdir($myPath, 0777);
				$myPath = '../postventas/'.$id.'/';
				$target_path2 = utf8_decode($myPath);
				if (!file_exists($target_path2))
					mkdir($target_path2, 0777);
				
				//ENVIAR CORREO AL CREADOR DEL INCIDENTE
			/*	nuevoincidente($_SESSION['usuario'], $titulo, $descripcion, $id, $fechacreacion, $horacreacion, $solicitante);
				notificarCEstado($id,'','creado','',$estado); */
				
				if($prioridad == '6'){
					//fueradeservicio($id,$serie);
					$queryfs  = "UPDATE activos set estado = 'INACTIVO' WHERE codequipo = '$serie' ";
					$resultfs = $mysqli->query($queryfs);
					$queryfs  = "INSERT INTO fueraservicio VALUES(null, '$serie', '$fechacreacion', null, $id) ";
					$resultfs = $mysqli->query($queryfs);
				}				
			}
			$accion = 'La Visita #'.$id.' ha sido Creado exitosamente';
			bitacora($_SESSION['usuario'], "Postventas", $accion, $id, $query);

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
		$titulo 			= (!empty($data['titulo']) ? $data['titulo'] : '');
		$descripcion 		= (!empty($data['descripcion']) ? $data['descripcion'] : '');
		$idempresas 		= (!empty($data['idempresas']) ? $data['idempresas'] : 1);
		$iddepartamentos	= (!empty($data['iddepartamentos']) ? $data['iddepartamentos'] : 0);
		$idclientes 		= (!empty($data['idclientes']) ? $data['idclientes'] : 0);
		$idproyectos 	    = (!empty($data['idproyectos']) ? $data['idproyectos'] : 0);
		$idambientes 		= (!empty($data['idambientes']) ? $data['idambientes'] : '');
		$idactivos			= (!empty($data['idactivos']) ? $data['idactivos'] : '');
		$idestados 			= (!empty($data['idestados']) ? $data['idestados'] : 12);
		$categoria 			= (!empty($data['idcategorias']) ? $data['idcategorias'] : 0);
		$subcategoria 		= (!empty($data['idsubcategorias']) ? $data['idsubcategorias'] : 0);
		$prioridad 			= (!empty($data['idprioridades']) ? $data['idprioridades'] : 0);
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
		$fechareal			= (!empty($data['fechareal']) ? $data['fechareal'] : date("Ymd"));
		$horastrabajadas 	= (!empty($data['horastrabajadas']) ? $data['horastrabajadas'] : '0');
		$estadoInc 			= '';

		if($fecharesolucion != ''){
			$fecharesolucion = preg_split("/[\s,]+/",$fecharesolucion);
			$horaresolucion  = "'".$fecharesolucion[1]."'";
			$fecharesolucion = "'".$fecharesolucion[0]."'";
		}else{
			$fecharesolucion = 'null';
			$horaresolucion  = 'null';
		}
		$fechareal = trim($fechareal);
		if($fechareal != "" && $fechareal != " " && $fechareal != '00:00:00'){
			$fechareal = preg_split("/[\s,]+/",$fechareal);
			$horareal  = "'".$fechareal[1]."'";
			$fechareal = "'".$fechareal[0]."'";
		}else{
			$fechareal = 'null';
			$horareal  = 'null';
		}
		//debug($fechareal);
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
		$queryInc = $mysqli->query("SELECT idestados FROM postventas WHERE id = '".$id."' ");
		if ($rowInc = $queryInc->fetch_assoc()) {
			$estadoInc = $rowInc['idestados'];
		}
		$queryAsig = $mysqli->query("SELECT asignadoa FROM postventas WHERE id = '".$id."' ");
		if ($rowAsig = $queryAsig->fetch_assoc()) {
			$asignadoaInc = $rowAsig['asignadoa'];
		}

		$descripcion = str_replace("'","",$descripcion); 
		$qverificar = " SELECT titulo, descripcion, idambientes, idactivos, idestados, idcategorias,
						idsubcategorias, idprioridades, solicitante, asignadoa, iddepartamentos, 
						resolucion, notificar, reporteservicio, estadomantenimiento,
						observaciones, fechacertificar, horario, fecharesolucion, 
						horaresolucion, horastrabajadas, fechacierre, horacierre, idempresas, 
						idclientes, idproyectos, iddepartamentos, fechareal
						FROM postventas
						WHERE id = $id ";
		//debugL($qverificar);				
		$rverificar = $mysqli->query($qverificar);
		
		$qestado = " SELECT nombre FROM estados WHERE id = '".$idestados."' ";
		$restado = $mysqli->query($qestado);
			if($reges = $restado->fetch_assoc()){
				$mostrarestado = $reges['nombre'];
			}
			
		$qclient = " SELECT nombre FROM clientes WHERE id = '".$idclientes."' ";
		$rclient = $mysqli->query($qclient);
			if($regcl = $rclient->fetch_assoc()){
				$mostrarcliente = $regcl['nombre'];
			}
		
		$qproye = " SELECT nombre FROM proyectos WHERE id = '".$idproyectos."' ";
		$rproye = $mysqli->query($qproye);
			if($regpro = $rproye->fetch_assoc()){
				$mostrarproyecto = $regpro['nombre'];
			}
			
		$qdepto = " SELECT nombre FROM departamentos WHERE id = '".$iddepartamentos."' ";
		$rdepto = $mysqli->query($qdepto);
			if($regdepto = $rdepto->fetch_assoc()){
				$mostrardpto = $regdepto['nombre'];
			}
		
		$qprior = " SELECT prioridad FROM sla WHERE id = '".$idprioridades."' ";
		$rprior = $mysqli->query($qprior);
			if($regprior = $rprior->fetch_assoc()){
				$mostrarprioridad = $regprior['prioridad'];
			}
		
		$qcate = " SELECT nombre FROM categorias WHERE id = '".$idcategorias."' ";
		$rcate = $mysqli->query($qcate);
			if($regcate = $rcate->fetch_assoc()){
				$mostrarcategoria = $regcate['nombre'];
			}
			
		$qsubc = " SELECT nombre FROM subcategorias WHERE id = '".$idsubcategorias."' ";
		$rsubc = $mysqli->query($qsubc);
			if($regsubc = $rsubc->fetch_assoc()){
				$mostrarsubcategoria = $regsubc['nombre'];
			}
		
			$txtpri = " El Usuario: ".$_SESSION['usuario'].", modificó el campo: ";
			$accion = "";
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
				if($rowcompare['idambientes']!=$idambientes){
					$accion .= 'Ambiente a "'.$idambientes.'",'; 
				}
				if($rowcompare['idactivos']!=$idactivos){
					$accion .= 'Activo a "'.$idactivos.'",'; 
				}
				if($rowcompare['idestados'] != $idestados){
					$accion .= 'Estado a "'.$mostrarestado.'",';
				}
				if($rowcompare['idcategorias']!=$idcategorias){
					$accion .= 'Categoría a "'.$mostrarcategoria.'",'; 
				}
				if($rowcompare['idsubcategorias']!=$idsubcategorias){
					$accion .= 'Subcategoría a "'.$mostrarsubcategoria.'",'; 
				}
				if($rowcompare['idprioridades']!=$idprioridades){
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
				if($rowcompare['reporteservicio']!=$reporteservicio){
					$accion .= 'Reporte Servicio a "'.$reporteservicio.'",'; 
				}
				if($rowcompare['fechacertificar']!=$fechacertificar){
					$accion .= 'Fecha Certificar a "'.$fechacertificar.'",';  
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
				$bdfechareal = "'".$rowcompare['fechareal']."'";
				if($bdfechareal!=$fechareal){
					$freal=str_replace("'","",$fechareal); 
					$accion .= 'Fecha Real a '.$freal.','; 
				}				
			}  
		//$accion = 'El Preventivo #'.$id.' ha sido actualizado exitosamente';
		$query = "  UPDATE postventas SET titulo = '$titulo', descripcion = '$descripcion', 
					idambientes = '$idambientes', idactivos = '$idactivos', idestados = '$idestados', 
					solicitante = '$solicitante', asignadoa = '$asignadoa',  
					resolucion = '$resolucion', notificar = '$notificar', reporteservicio = '$reporteservicio',
					fechacertificar = '$fechacertificar', horario = '$horario', fecharesolucion = $fecharesolucion,
					horaresolucion = $horaresolucion, horastrabajadas = '$horastrabajadas', fechacierre = $fechacierre, 
					horacierre = $horacierre, fechareal = $fechareal, horareal = $horareal, idempresas = '$idempresas', 
					fechacreacion = '$fechacreacion', horacreacion = '$horacreacion' ";
		//$query .= " WHERE id = $id ";
		if($idcategorias != ''){
			$query .= " , idcategorias = '$idcategorias' ";
		}
		if($idsubcategorias != ''){
			$query .= " , idsubcategorias = '$idsubcategorias' ";
		}
		if($idprioridades != ''){
			$query .= " , idprioridades = '$idprioridades' ";
		}
		if($idclientes != ''){
			$query .= " , idclientes = '$idclientes' ";
		}
		if($idproyectos != ''){
			$query .= " , idproyectos = '$idproyectos' ";
		}
		if($iddepartamentos != ''){
			$query .= " , iddepartamentos = '$iddepartamentos' ";
		}
		$query .= " WHERE id = $id ";
		//debug($query);

		if($mysqli->query($query)){			
			//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
			if($estadoInc != $idestados){
				if($idestados == 13){
					$query = "SELECT idproyectos FROM usuarios WHERE correo = '$asignadoa' ";
					$result = $mysqli->query($query);
					if($result->num_rows >0){
						$row = $result->fetch_assoc();				
						$proyectosusu = $row['idproyectos'];
					}
					//ACTUALIZAR INCIDENTE
					$queryUP = "UPDATE postventas SET idproyectos = '$idproyectos' WHERE id = $id ";
					$resultUP = $mysqli->query($queryUP);
				}
				notificarCEstado($id,$notificar,'actualizado',$estadoInc,$idestados);
				if($prioridad == '6' && ($idestados == 16 || $idestados == 17)){
					$queryfs  = "UPDATE activos set estado = 'ACTIVO' WHERE id = '$idactivos' ";
					$resultfs = $mysqli->query($queryfs);
					$queryfs  = "UPDATE fueraservicio set hasta = $fecharesolucion WHERE  incidente = $id ";
					$resultfs = $mysqli->query($queryfs);
				}
			}
			if($accion !=""){
				$accion = substr($accion,0,-1); 
				$accion .= ".";
				$accion = $txtpri.$accion;
				bitacora($_SESSION['usuario'], "Postventas", $accion, $id, $query);

			} 
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
				$coma =', ';
				$query .= "UPDATE postventas SET ";
				foreach($data as $c => $v){
					if($v != ''){
						if($i != 0)
							$query .= $coma;
				
						if($c == 'idempresasmas'){
							$query .= " idempresas = '$v' ";
						}elseif($c == 'iddepartamentosmas'){
							$query .= " iddepartamentos = '$v' ";
						}elseif($c == 'idclientesmas'){
							$query .= " idclientes = '$v' ";
						}elseif($c == 'idproyectosmas'){
							$query .= " idproyectos = '$v' ";
						}elseif($c == 'idcategoriasmas'){
							$query .= " idcategorias = '$v' ";
						}elseif($c == 'idsubcategoriasmas'){
							$query .= " idsubcategorias = '$v' ";
						}elseif($c == 'idprioridadesmas'){
							$queryV  			= " SELECT dias, horas FROM sla WHERE id = '$v' ";
							$resultV 			= $mysqli->query($queryV);
							$rowV 				= $resultV->fetch_assoc();
							$diasP 				= $rowV['dias'];
							$horasP 			= $rowV['horas'];
							$fechavencimiento 	= date('Y-m-d', strtotime($fechacreacion."+ ".$diasP." days"));
							$horavencimiento  	= date('H:i:s', strtotime($horacreacion." + ".$horasP." hours"));
							$query .= " idprioridades = '$v', fechavencimiento = '$fechavencimiento', horavencimiento = '$horavencimiento' ";
						}elseif($c == 'idambientesmas'){
							$query .= " idambientes = '$v' ";
						}elseif($c == 'idactivosmas'){
							$query .= " serie = '$v' ";
						}elseif($c == 'asignadoamas'){
							$query .= " asignadoa = '$v' ";
						}elseif($c == 'idestadosmas'){
							$query .= " estado = '$v' ";
						}						
						$i++;
					}
				}				
				if($i >= 1){
					foreach($idarray as $id){
						$query2 = '';
						$query2 = $query." WHERE id = '$id' ";
						if($id != ''){

							if($mysqli->query($query2)){
								bitacora($_SESSION['usuario'], "Postventas", 'La Visita #'.$id.' ha sido Editado exitosamente', $id, $query2);
								echo true;
							}else{
								echo false;
							}	

						}						
					}
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
				$asunto = "Postventa Visita #$incidente ha sido Creado";
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
		
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.id AS idproyectos, b.nombre AS proyecto, c.id AS idambientes,
					e.nombre AS estado, f.id AS idcategorias, f.nombre AS categoria, g.nombre AS subcategoria,
					h.prioridad, a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor,
					a.departamento, a.satisfaccion, a.comentariosatisfaccion, a.resolucion, IFNULL(k.nombre, a.resueltopor) AS resueltopor,
					IF(a.fechacreacion IS NOT NULL,CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion,
					IF(a.fechavencimiento IS NOT NULL,CONCAT(a.fechavencimiento,'  ', a.horavencimiento),'') AS fechavencimiento,
					IF(a.fecharesolucion IS NOT NULL,CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,
					IF(a.fechacierre IS NOT NULL,CONCAT(a.fechacierre,'  ', a.horacierre),'') AS fechacierre,
					a.fechamodif, a.fechacertificar, 
					a.horastrabajadas, a.comentariovisto,IFNULL(j.nombre, a.solicitante) AS solicitante, i.usuario AS usuariocreadopor,
					j.usuario AS usuariosolicitante, l.usuario AS usuarioasignadoa,
					CASE 
						WHEN j.estado = 'Activo' 
							THEN IFNULL(j.correo, a.solicitante) 
						WHEN j.estado = 'Inactivo' 
							THEN '' 
						END 
						AS correosolicitante,
					CASE 
						WHEN l.estado = 'Activo' 
							THEN a.asignadoa 
						WHEN l.estado = 'Inactivo' 
							THEN '' 
						END 
						AS asignadoa, 
					CASE 
						WHEN i.estado = 'Activo' 
							THEN IFNULL(i.correo, a.creadopor) 
						WHEN i.estado = 'Inactivo' 
							THEN '' 
						END 
						AS correocreadopor
					FROM postventas a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id 
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.id OR a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.resueltopor = k.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					WHERE a.id = $incidente ";
					
		$result = $mysqli->query($query);
		$row 	= $result->fetch_assoc();
		
		//1 para quien quien creo el incidentes (Creado por)
		//$correo [] = $row['correocreadopor'];
		
		//2 para quien solicito o reporto el incidente (Solicitante)
		if($estadonew == 16 || $estadonew == 17){
			if($row['correosolicitante'] != ""){
				$correo [] = $row['correosolicitante'];
			} 
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
				$query2 .= "correo = '".$row['asignadoa']."' AND estado = 'Activo' ";
			}else{
				$query2 .= "correo IN (".$row['asignadoa'].")  AND estado = 'Activo'  ";
			}
			$consulta = $mysqli->query($query2);
			while($rec = $consulta->fetch_assoc()){
				$asignadoaN .= $rec['nombre']." , ";
			}			
		}
		
		//ENVIAR CORREO DEL INCIDENTE A LOS USUARIOS SELECCIONADOS
		//4 para los usuarios que quieren que se les notifique (Enviar Notificacion a)
		if($notificar != '[]' && $notificar != ''){
			$asunto    = "Notificación de la Visita #$incidente";
			$notificar = json_decode($notificar);
			if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) { 
				
					//Excluir usuarios inactivos campo Notificar a 
					$queryn = " SELECT correo FROM usuarios WHERE correo = '".$notificar."' AND estado = 'Activo' ";
					$consultan = $mysqli->query($queryn);
					if($recn = $consultan->fetch_assoc()){
						$correo [] = $notificar;	
					}				
			}else{
				foreach($notificar as $notif){
					
					//Excluir usuarios inactivos campo Notificar a 
					$queryn = " SELECT correo FROM usuarios WHERE correo = '".$notificar."' AND estado = 'Activo' ";
					$consultan = $mysqli->query($queryn);
					if($recn = $consultan->fetch_assoc()){
						$correo [] = $notif;	
					} 
				}
			}
		}
		//else{
			if($accion == 'creado'){
				$asunto = "Visita #$incidente ha sido Creado";
			}else{ //actualizado
				if ($estadoold != $estadonew && $estadonew == 13)
					$asunto = "Visita #$incidente ha sido Asignado";			
				elseif ($estadoold != $estadonew && $estadonew == 16)
					$asunto = "Visita #$incidente ha sido Resuelto"; 
				else
					$asunto = "Visita #$incidente ha sido Actualizado"; 
			}
		//}
		//DATOS DEL CORREO
		$usuarioses = $_SESSION['usuario'];
		$consultaUA = $mysqli->query("SELECT nombre FROM usuarios WHERE usuario = '$usuarioses' LIMIT 1 ");
		if ($registroUA = $consultaUA->fetch_assoc()) {
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
		$idproyectos 	= $row['idproyectos']; 
		$fechacreacion 	= $row['fechacreacion'];
		$descripcion	= $row['descripcion'];
		$solicitante	= $row['solicitante'];
		$creadopor		= $row['creadopor'];
		$departamento	= $row['departamento'];
		$prioridad		= $row['prioridad'];
		$sitio 			= $row['idambientes'];
		$resolucion 	= $row['resolucion'];
		$nasignadoa 	= $asignadoaN;
		//MENSAJE
		if($accion == 'creado'){
			$mensaje = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha creado la visita #".$incidente."</b></p>";
		}else{ //actualizado
			$mensaje = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha actualizado la visita #".$incidente."</b></p>";		
		}		
		
		if($estadonew == 13){
			$mensaje .= "<p style='padding-left: 30px;width:100%;'>La visita ha sido asignado a: ".$nasignadoa."</p>";
		}elseif($estadoant !='' && $estadonue !=''){
			$mensaje .= "<p style='padding-left: 30px;width:100%;'>El Estado cambió de ".$estadoant." a ".$estadonue."</p>";
			//*******************************************//
			//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
			//*******************************************// 
			
			//Usuarios de soporte
			$idusuarios["icarvajal"] = "0";
			$idusuarios["frios"] = "0";
			$idusuarios["aanderson"] = "0"; 
			$idusuarios["admin"] = "0";
			
			//Usuarios relacionados a la postventa
			$idusuarios[$row['usuariocreadopor']] = "0";
			$idusuarios[$row['usuarioasignadoa']] = "0";
			$idusuarios[$row['usuariosolicitante']] = "0";
			$idusuarios[$usuarionotificar] = "0";
			
			$usuarios = json_encode($idusuarios);
			
			$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,descripcion,fecha,hora,usuarios) VALUES (".$idproyectos.",".$incidente.",'Cambio de estado postventa',' ".$estadoant." a ".$estadonue."','". date('Y-m-d') ."','". date('H:i:s') ."','".$usuarios."')"; 
	        $rsql = $mysqli->query($sql);
			
			//*******************************************//
			//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
			//*******************************************//					
		}
		$mensaje .= "<p style='width:100%;'>
						<a href='http://toolkit.maxialatam.com/mitim/incidentes.php?id=".$incidente."' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Visita</a></p>
						<br><br>
						<p style='font-size: 18px;width:100%;'>".$creadopor." ha creado esta visita el ".$fechacreacion."</p>
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
				$query = "  UPDATE postventas SET fechacierre = DATE_ADD(fecharesolucion, INTERVAL 3 DAY), horacierre = horaresolucion, 
							estado = 16 WHERE id = '".$incidente."' ";
				$mysqli->query($query);
				$mensaje .= "<br><br><p style='width:100%;'><b>Resolución: </b>".$resolucion."</p>";	
			}
			
			$mensaje .= "</div>";

		//USUARIOS DE SOPORTE
		//$correo [] = 'ana.porras@maxialatam.com';
		$correo [] = 'isai.carvajal@maxialatam.com';
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		
		debugL("notificarCEstadoPOSTVENTA-CORREO:".json_encode($correo),"notificarCEstadoPOSTVENTA");
		
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
			$asunto = "Satisfacción de la Visita #$incidente";
		}

		$mensajeHtml = "<table border=0>
							<tr><td colspan=4>&nbsp;</td></tr>
							<tr><td colspan=4>&nbsp;</td></tr>
							<tr><td colspan=4>Visita #$incidente</td></tr>
							<tr><td colspan=4>Titulo: $titulo</td></tr>
							<tr><td colspan=4>&nbsp;</td></tr>
							<tr><td colspan=4>&nbsp;</td></tr>
							<tr><td colspan=>¿Est&aacute; satisfecho con la soluc&oacute;n de la Visita?</td></tr>
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
					FROM postventas a
					LEFT JOIN usuarios b ON REPLACE(a.asignadoa,'-G','') = b.id AND RIGHT(a.asignadoa,2) = '-U'
					LEFT JOIN grupos c ON REPLACE(a.asignadoa,'-U','')= c.id AND RIGHT(a.asignadoa,2) = '-G'
					LEFT JOIN gruposusuarios d ON c.id = d.idgrupo
					LEFT JOIN usuarios e ON d.idusuario = e.id
					LEFT JOIN usuariosincidentes f ON a.solicitante = f.id OR a.solicitante = f.correo
					WHERE fechavencimiento < CURDATE() AND a.idestados NOT IN (16,17) ";

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

						$asunto = "Visita #$incidente VENCIDO - Soporte Maxia Toolkit";

						$mensajeHtml = "<table border=0>
											<tr><td colspan=4>Maxia Toolkit</td></tr>
											<tr><td colspan=4>Gesti&oacute;n de Soporte</td></tr>
											<tr><td colspan=4>&nbsp;</td></tr>
											<tr><td colspan=4>Visita #$incidente</td></tr>
											<tr><td colspan=4>Titulo: $titulo</td></tr>
											<tr><td colspan=4>Solicitado por: $nombre</td></tr>
											<tr><td colspan=4>El d&iacute;a: $fcreacion</td></tr>
											<tr><td colspan=4>&nbsp;</td></tr>
											<tr><td colspan=4>&nbsp;</td></tr>";

						$mensajeHtml .= '	<tr><td colspan=>No est&aacute; a&uacute;n Resuelto y su fecha limite de cumplimiento establecida es el '.$fechavencimiento.'. Por favor Resolver en la brevedad posible Y dejar un compromiso en la Visita mencionada acerca del motivo por el cual no ha sido Resuelto. </td></tr>
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
		//debug($correo);
		
		$cuerpo = "";
		$cuerpo .= "<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; '>";
		$cuerpo .= "<img src='http://toolkit.maxialatam.com/repositorio-tema/assets/img/logosym-header.png' style='width: auto; float: left;'>";
		$cuerpo .= "<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -176px;'>Maxia Toolkit<br>";
		$cuerpo .= "Gestión de Soporte<br>";
		$cuerpo .= "</div>";
		$cuerpo .= $mensaje;
		$cuerpo .= "<div style='background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;'>";
		$cuerpo .= "© ".date('Y')." MiTim";
		$cuerpo .= "</div>";	
		
		foreach($correo as $destino){
		   $mail->addAddress($destino); // EVITAR ENVÍO DE CORREO A CLIENTES (DESACTIVADO)
		}
		//$mail->addAddress("isai.carvajal@maxialatam.com");
		//$mail->addAddress("fernando.rios@maxialatam.com");
		//$mail->addAddress("axel.anderson@maxialatam.com");
		//$mail->addAddress("esli.villalobos@maxialatam.com");
		
		$mail->FromName = "MiTim";
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = $asunto;
		//$mail->MsgHTML($cuerpo);
		$mail->Body = $cuerpo;
		$mail->AltBody = "MiTim: $asunto";
		/* if(!$mail->send()) {
			echo 'Mensaje no pudo ser enviado. ';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			//echo 'Ha sido enviado el correo Exitosamente';
			echo true;
		} */ 
	}
	
	function fusionarIncidentes()
	{
		global $mysqli;
		$fusioninc 		= $_REQUEST['fusioninc'];
		$idincidentes 	= json_decode($_REQUEST['idincidentes']);

		if($fusioninc != ''){
			foreach($idincidentes as $incidente){
				//CATEGORIAS MERGED
				$queryP = " SELECT a.id FROM categorias a 
							LEFT JOIN incidentes b ON a.idproyecto = b.idproyectos 
							WHERE b.id = '$incidente' AND a.nombre = 'Merged' ";
				$resultP = $mysqli->query($queryP);
				if($resultP->num_rows >0){
					$rowP = $resultP->fetch_assoc();				
					$idmerge = $rowP['id'];
				}else{
					$idmerge = 6;
				}
				$query = "UPDATE postventas SET estado = 16, idcategorias = '$idmerge', fusionado = ".$fusioninc." 
						  WHERE id = '".$incidente."'";
				if($mysqli->query($query)){
					bitacora($_SESSION['usuario'], "Postventas", 'La Visita #'.$fusioninc.' se fusiono con: '.$incidente, $fusioninc, $query);
					bitacora($_SESSION['usuario'], "Postventas", 'La Visita #'.$incidente.' fue fusionado con: '.$incidente, $incidente, $query);
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
		$id 			= $_REQUEST['id'];
		$incidente		= $_REQUEST['incidente'];
		$cadfusionado  	= $_REQUEST['fusionado'];
		$arrfusionado 	= explode(' - ',$cadfusionado);
		$fusionado 		= $arrfusionado[0];

		if($id != ''){
			//CATEGORIA INICIAL
			$queryP = "SELECT idcategorias FROM postventas WHERE id = '$fusionado' ";
			$resultP = $mysqli->query($queryP);
			if($resultP->num_rows >0){
				$rowP = $resultP->fetch_assoc();				
				$idcategorias = $rowP['idcategoria'];
			}else{
				$idcategorias = 0;
			}
			
			$query = "UPDATE postventas SET estado = 12, fusionado = '', idcategorias = '$idcategoria' WHERE id = '$id' ";
			//debug($query);
			if($mysqli->query($query)){
				bitacora($_SESSION['usuario'], "Postventas", 'La Visita #'.$incidente.' se Revirtió la Fusión con: '.$fusionado, $id, $query);
				bitacora($_SESSION['usuario'], "Postventas", 'La Visita #'.$fusionado.' se Revirtió la Fusión con: '.$incidente, $id, $query);
				echo true;
			}else{
				echo false;
			}
		}else{
			echo false;
		}
	}

	function historial(){
		global $mysqli;
		$nivel = $_SESSION['nivel'];
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado = array();
		
		$query  = " SELECT id, usuario, fecha, accion 
					FROM bitacora 
					WHERE modulo = 'Postventas' 
					AND identificador = $id
					ORDER BY id DESC ";
		
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			$resultado[] = array(				
				'id' 			=> $row['id'],
				'usuario' 	=> $row['usuario'],
				'fecha'		=> $row['fecha'],
				'accion'	=> $row['accion']
			);
		}
		$response = array( 
		  "data" => $resultado
		);
		echo json_encode($response);
	}
	
	function exportarExcel() 
	{
		global $mysqli;
		$idempresas 	 = $_SESSION['idempresas'];
		$idclientes 	 = $_SESSION['idclientes'];
		$idproyectos 	 = $_SESSION['idproyectos'];		
		$iddepartamentos = $_SESSION['iddepartamentos'];
		$nivel 			 = $_SESSION['nivel'];
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
		$objPHPExcel->getProperties()->setCreator("MiTim")
		->setLastModifiedBy("MiTim")
		->setTitle("Reporte de Preventivos")
		->setSubject("Reporte de Preventivos")
		->setDescription("Reporte de Preventivos")
		->setKeywords("Reporte de Preventivos")
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
		$objPHPExcel->getActiveSheet()
		->setCellValue('A4', '# Incidente')
		->setCellValue('B4', 'Titulo')
		->setCellValue('C4', 'Descripción')		
		->setCellValue('D4', 'Cliente')
		->setCellValue('E4', 'Proyecto')
		->setCellValue('F4', 'Estado')
		->setCellValue('G4', 'Equipo')
		->setCellValue('H4', 'Serie')
		->setCellValue('I4', 'Activo')
		->setCellValue('J4', 'Marca')
		->setCellValue('K4', 'Modelo')
		->setCellValue('L4', 'Modalidad')
		->setCellValue('M4', 'Estado del equipo')
		->setCellValue('N4', 'Categoría')
		->setCellValue('O4', 'Subcategoría')
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
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, e.nombre AS estado, 
					m.equipo, m.codequipo as serie, m.activo, m.marca, m.modelo, m.modalidad, m.estado as estadoequipo, 
					f.nombre AS categoria, g.nombre AS subcategoria, c.idambientes AS sitio, h.prioridad, 
					a.origen, a.creadopor, a.solicitante, a.asignadoa, a.departamento, a.resueltopor,
					a.resolucion, a.satisfaccion, a.comentariosatisfaccion, 
					ifnull(a.fechacreacion, '') AS fechacreacion, a.horacreacion, 
					ifnull(a.fecharesolucion, '') as fecharesolucion, a.horaresolucion,
					ifnull(a.fechacierre, '') as fechacierre, a.horacierre, 
					ifnull(a.fechavencimiento, '') AS fechavencimiento, a.horavencimiento, 
					ifnull(a.fechareal, '') AS fechareal, a.horareal, 
					a.horastrabajadas, n.periodo, o.nombre as cliente
					FROM postventas a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.codigo
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN activos m ON a.serie = m.codequipo
					LEFT JOIN cuatrimestres n ON a.fecharesolucion BETWEEN n.fechainicio AND n.fechafin
					LEFT JOIN clientes o ON a.idclientes = o.id
					WHERE a.idcategorias in (138,139,140,141,142,143,144,145,146,147) ";
		
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idempresas in ($idempresas) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idclientes in ($idclientes) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idproyectos in ($idproyectos) ";
		}
		/*
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.iddepartamentos in ($iddepartamentos) ";
		}
		*/
		
		//DATOS 
		$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Postventas' AND usuario = '".$_SESSION['usuario']."'";
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
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where2 .= " AND a.fechacreacion <= $hastaf ";
			}
			if(!empty($data->idcategoriasf)){
				$idcategoriasf = json_encode($data->idcategoriasf);
				$where2 .= " AND a.idcategorias IN ($idcategoriasf)";
			}
			if(!empty($data->idsubcategoriasf)){
				$idsubcategoriasf = json_encode($data->idsubcategoriasf);
				$where2 .= " AND a.idsubcategorias IN ($idsubcategoriasf)"; 
			}			
			if(!empty($data->idproyectosf)){
				$idproyectosf = json_encode($data->idproyectosf);
				$where2 .= " AND a.idproyectos IN ($idproyectosf)"; 
			}
			if(!empty($data->idprioridadesf)){
				$idprioridadesf = json_encode($data->idprioridadesf);
				$where2 .= " AND a.idprioridades IN ($idprioridadesf)";
			}
			if(!empty($data->modalidadf)){
				$modalidadf = json_encode($data->modalidadf);
				$where2 .= " AND m.modalidad IN ($modalidadf)"; 
			}
			if(!empty($data->idmarcasf)){
				$idmarcasf = json_encode($data->idmarcasf);
				$where2 .= " AND m.marca IN ($idmarcasf)";
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				$where2 .= " AND a.solicitante IN ($solicitantef)";
			}
			if(!empty($data->idestadosf)){
				$idestadosf = json_encode($data->idestadosf);
				$where2 .= " AND a.idestados IN ($idestadosf)";
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
			if(!empty($data->idambientesf)){
				$idambientesf = json_encode($data->idambientesf);
				$where2 .= " AND a.idambientes IN ($idambientesf)"; 
			}
					
			$vowels = array("[", "]");
			$where2 = str_replace($vowels, "", $where2);
		}
		
		//CUERPO
		//Definir fuente
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);					
		
		$query  .= " $where2 ORDER BY a.id desc ";
		
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
			->setCellValue('I'.$i, $row['activo'])
			->setCellValue('J'.$i, $row['marca'])
			->setCellValue('K'.$i, $row['modelo'])
			->setCellValue('L'.$i, $row['modalidad'])
			->setCellValue('M'.$i, $row['estadoequipo'])
			->setCellValue('N'.$i, $row['categoria'])
			->setCellValue('O'.$i, $row['subcategoria'])			
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
		$objPHPExcel->getActiveSheet()->setTitle('Incidentes - Preventivos');

		//Redirigir la salida al navegador del cliente
		$hoy = date('dmY');
		$nombreArc = 'Preventivos - '.$hoy.'.xls';
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
		$query = "UPDATE postventas SET comentariovisto='1' WHERE id = '$id'";
		$mysqli->query($query);
	}
	
	function filtroGrid(){
		global $mysqli;
		$_SESSION['filtrogrid'] = '0';
		$usufiltroexiste = 0;
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Postventas' AND usuario =".$_SESSION['user_id'];
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
			$filtro = str_replace('a.idestados','estado',$filtro);
			$filtro = str_replace('a.titulo','titulo',$filtro);
			$filtro = str_replace('a.descripcion','descripcion',$filtro);
			$filtro = str_replace('a.idempresas','idempresas',$filtro);
			$filtro = str_replace('a.iddepartamentos','iddepartamentos',$filtro);
			$filtro = str_replace('a.idclientes','idclientes',$filtro);
			$filtro = str_replace('a.idproyectos','idproyectos',$filtro);
			$filtro = str_replace('c.codigo','idambientes',$filtro);
			$filtro = str_replace('a.activo','activo',$filtro);
			$filtro = str_replace('a.marca','marca',$filtro);
			$filtro = str_replace('a.modelo','modelo',$filtro);
			$filtro = str_replace('a.idcategoria','idcategoria',$filtro);
			$filtro = str_replace('a.idsubcategoria','idsubcategoria',$filtro);
			$filtro = str_replace('a.idprioridad','idprioridades',$filtro);
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
		
		$query = "DELETE FROM usuariosfiltros WHERE modulo = 'postventas' AND usuario = '$usuario' ";
		if($mysqli->query($query))
			echo true;		
	}
	
	function guardarfiltros() {
		global $mysqli;
		$data = $_REQUEST['data'];
		$usuario = $_SESSION['usuario'];
		$query  = "SELECT *
					FROM usuariosfiltros
					WHERE modulo = 'postventas' AND usuario = '".$usuario."' ";

		$result = $mysqli->query($query);
		$count = $result->num_rows;
		
		if( $count > 0 ) 
			$query = "UPDATE usuariosfiltros SET filtrosmasivos = '".$data."' WHERE modulo = 'Postventas' AND usuario = '".$usuario."' ";
		else
			$query = "INSERT INTO usuariosfiltros VALUES (null, '$usuario', 'Postventas', '', '$data')";
		if($mysqli->query($query))
			echo true;		
	}
	
	function abrirfiltros() {
		global $mysqli;
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Postventas' AND usuario = '".$_SESSION['usuario']."'";
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
	
	function verificarfiltros() {
		global $mysqli;
		$query = "SELECT filtrosmasivos FROM usuariosfiltros 
				  WHERE modulo = 'postventas' 
				  AND usuario = '".$_SESSION['usuario']."'";
		$result = $mysqli->query($query);
		$response = 0;
		if($result->num_rows >0){
			$row = $result->fetch_assoc();				
			$data = $row['filtrosmasivos'];
			$filtrosmasivos = json_decode($data);
			foreach($filtrosmasivos as $clave => $valor){
				if($valor != '' || $valor != 0){
					$response = 1;
				}
			}
		} else {
			$response = 0;
		}
		echo $response;
	}

	function notificacionAdjunto() {
		global $mysqli, $mail;
		$incidente 	= (!empty($_REQUEST['incidente']) ? $_REQUEST['incidente'] : '');
		$imagen 	= (!empty($_REQUEST['imagen']) ? $_REQUEST['imagen'] : '');
		$idcoment 	= (!empty($_REQUEST['idcoment']) ? $_REQUEST['idcoment'] : '');
		
		if($idcoment!=""){
			$queryC  = " SELECT visibilidad FROM comentarios WHERE id = $idcoment ";
			$resultC = $mysqli->query($queryC);
			if($rowC = $resultC->fetch_assoc()){
				$visibilidad = $rowC['visibilidad'];
			}else{
				$visibilidad = "";
			}
		}
		
		if($incidente != ''){
			//DATOS DEL CORREO
			$usuarioSes = $_SESSION['usuario'];
			$consultaUA = $mysqli->query("SELECT nombre FROM usuarios WHERE usuario = '".$usuarioSes."' AND estado = 'Activo' LIMIT 1 ");
			while ($registroUA = $consultaUA->fetch_assoc()) {
				$usuarioAct = $registroUA['nombre'];
			}
			
			//USUARIOS DE SOPORTE
			//$correo [] = 'ana.porras@maxialatam.com';
			$correo [] = 'isai.carvajal@maxialatam.com';
			$correo [] = 'fernando.rios@maxialatam.com';
			$correo [] = 'axel.anderson@maxialatam.com';
			
			$query  = " SELECT a.id, a.titulo, i.usuario AS usuariocreadopor, j.usuario AS usuariosolicitante, k.usuario AS usuarioasignadoa,
						CASE 
							WHEN i.estado = 'Activo' 
								THEN IFNULL(i.correo, a.creadopor)
							WHEN i.estado = 'Inactivo' 
								THEN '' 
							END 
							AS creadopor,
						CASE 
							WHEN j.estado = 'Activo' 
								THEN IFNULL(j.correo, a.solicitante)
							WHEN j.estado = 'Inactivo' 
								THEN '' 
							END 
							AS solicitante,
						CASE 
							WHEN k.estado = 'Activo' 
								THEN a.asignadoa
							WHEN k.estado = 'Inactivo' 
								THEN '' 
							END 
							AS asignadoa,
						a.notificar 
						FROM postventas a
						LEFT JOIN usuarios i ON a.creadopor = i.correo
						LEFT JOIN usuarios j ON a.solicitante = j.correo
						LEFT JOIN usuarios k ON a.asignadoa = k.correo
						WHERE a.id = ".$incidente." ";
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
					$query2 .= "correo = '".$row['asignadoa']."' AND estado = 'Activo'";
				}else{
					$query2 .= "correo IN (".$row['asignadoa'].") AND estado = 'Activo'";
				}
				$consulta = $mysqli->query($query2);
				while($rec = $consulta->fetch_assoc()){
					$asignadoaN .= $rec['nombre']." , ";
				}			
			}		
			//ENVIAR CORREO AL USUARIO QUE CREO EL INCIDENTE
			if($visibilidad != 'Privado'){					 
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
			}
			//ENVIAR CORREO AL SOLICITANTE QUE CREO EL INCIDENTE
			if($visibilidad != 'Privado'){
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
			}
			//ENVIAR CORREO A LOS USUARIOS A NOTIFICAR
			if($visibilidad != 'Privado'){
				if($row['notificar'] != ''){
					$notificar  = $row['notificar'];
					if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
						
						//Excluir usuarios inactivos campo Notificar a 
						$queryn = " SELECT usuario, correo FROM usuarios WHERE correo = '".$notificar."' AND estado = 'Activo' ";
						$consultan = $mysqli->query($queryn);
						if($recn = $consultan->fetch_assoc()){
							$correo [] = $notificar;
							$usuarionotificar  = $recn['usuario'];							
						}
						
					}else{
						if(is_array($notificar)){
							foreach($notificar as $notificarp){ 
							
								//Excluir usuarios inactivos campo Notificar a 
								$queryn = " SELECT usuario, correo FROM usuarios WHERE correo = '".$notificarp."' AND estado = 'Activo' ";
								$consultan = $mysqli->query($queryn);
								if($recn = $consultan->fetch_assoc()){
									$correo [] = $notificarp;	
									$usuarionotificar  = $recn['usuario'];
								}
							}
						}else{
							$corchetea = '["';
							$corcheteb = '"]';
							//Excluir usuarios inactivos campo Notificar a 
							$queryn = " SELECT usuario, correo FROM usuarios WHERE correo = REPLACE(REPLACE('".$notificar."','".$corchetea."',''),'".$corcheteb."','') AND estado = 'Activo' "; 
							$consultan = $mysqli->query($queryn);
							if($recn = $consultan->fetch_assoc()){
								$correo [] = $notificarp;	
								$usuarionotificar  = $recn['usuario']; 
							}
						} 
					}
				}
			}
			
			//*******************************************//
			//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
			//*******************************************// 
			$verp = " SELECT idproyectos FROM incidentes WHERE id = ".$incidente."";
			$rverp = $mysqli->query($verp);
			if ($reg = $rverp->fetch_assoc()) {
				$idproyectos = $reg['idproyectos'];
			} 
			 
			//Usuarios de soporte
			$idusuarios["icarvajal"] = "0";
			$idusuarios["frios"] = "0";
			$idusuarios["aanderson"] = "0"; 
			$idusuarios["admin"] = "0";
			
			//Usuarios relacionados al preventivo  
			if($row['usuariocreadopor'] !="") $idusuarios[$row['usuariocreadopor']] = "0";		
			if($row['usuarioasignadoa'] !="") $idusuarios[$row['usuarioasignadoa']] = "0"; 
			if($row['usuariosolicitante'] !="") $idusuarios[$row['usuariosolicitante']] = "0";
			if($usuarionotificar !="") $idusuarios[$usuarionotificar] = "0";
			
			$usuarios = json_encode($idusuarios);
			
			$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,fecha,hora,usuarios) VALUES (".$idproyectos.",".$incidente.",'Adjunto realizado postventa','". date("Y-m-d") ."','". date("H:i:s") ."','".$usuarios."')"; 
			$rsql = $mysqli->query($sql); 
				
			//*******************************************//
			//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
			//*******************************************//
			
			$cuerpo = "";
			$cuerpo .= "<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; '>";
			$cuerpo .= "<img src='http://toolkit.maxialatam.com/repositorio-tema/assets/img/maxia.jpg' style='width: initial;height: 60px;float: left; position: absolute !important;'>";
			$cuerpo .= "<p style='margin:auto; font-weight:bold; width: 100%; text-align: center;'>Maxia Toolkit<br>";
			$cuerpo .= "Gestión de Soporte<br>";
			$cuerpo .= "</p></div>";
			$cuerpo .= "<div style='padding: 30px;font-family: arial,sans-serif;'>
							<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha adjuntado nuevo documento al incidente #".$incidente."</b></p>";
			$cuerpo .= "	<p style='width:100%;'>
								<a href='http://toolkit.maxialatam.com/mitim/incidentes.php?id=".$incidente."' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Incidente</a></p>
							</p>
						</div>
						";
			$cuerpo .= "<div style='background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;'>";
			$cuerpo .= "© ".date('Y')." MiTim";
			$cuerpo .= "</div>";	
			
			$correo = array_unique($correo);
			//debug(json_encode($correo));
			//echo $correo; 
			
			foreach ($correo as $key => $value) { 
				$querycorreo = "SELECT * FROM notificacionesxusuarios nu
								left join usuarios u on u.id = nu.idusuario
								where u.correo = '$value' and noti10 = 1";
				$consultacorreo = $mysqli->query($querycorreo);
				if($consultacorreo->num_rows == 0){
					unset($correo[$key]);
				}
			}
			
			foreach($correo as $destino){
				if( $destino != 'mesadeayuda@innovacion.gob.pa' ){
					$mail->addAddress($destino);
				}			   
			}			
			//$mail->addAddress("lisbethagapornis@gmail.com");
			
			$mail->FromName = "MiTim";
			$mail->isHTML(true); // Set email format to HTML
			if($row['solicitante'] == 'mesadeayuda@innovacion.gob.pa' || $row['creadopor'] == 'mesadeayuda@innovacion.gob.pa'){
				$mail->Subject = $row['titulo'];
			}else{
				$mail->Subject = "Preventivo #".$incidente." - Nuevo adjunto";
			}
			
			//$mail->MsgHTML($cuerpo);
			$mail->Body = $cuerpo;
			$mail->AltBody = "MiTim: $asunto";
			
			/*if(!$mail->send()) {
				echo 'Mensaje no pudo ser enviado. ';
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				//echo 'Ha sido enviado el correo Exitosamente';
				echo true;
			} */
			
			echo true;
		}else{
			echo false;
		}		
	}
	function exportarExcelConComentarios() 
	{
		global $mysqli;
		$idempresas 	 = $_SESSION['idempresas'];
		$idclientes 	 = $_SESSION['idclientes'];
		$idproyectos 	 = $_SESSION['idproyectos'];		
		$iddepartamentos = $_SESSION['iddepartamentos'];
		$nivel 			 = $_SESSION['nivel'];
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
		$objPHPExcel->getProperties()->setCreator("MiTim")
		->setLastModifiedBy("MiTim")
		->setTitle("Reporte de Preventivos")
		->setSubject("Reporte de Preventivos")
		->setDescription("Reporte de Preventivos")
		->setKeywords("Reporte de Preventivos")
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
		$objPHPExcel->getActiveSheet()
		->setCellValue('A4', '# Incidente')
		->setCellValue('B4', 'Titulo')
		->setCellValue('C4', 'Descripción')		
		->setCellValue('D4', 'Cliente')
		->setCellValue('E4', 'Proyecto')
		->setCellValue('F4', 'Estado')
		->setCellValue('G4', 'Equipo')
		->setCellValue('H4', 'Serie')
		->setCellValue('I4', 'Activo')
		->setCellValue('J4', 'Marca')
		->setCellValue('K4', 'Modelo')
		->setCellValue('L4', 'Modalidad')
		->setCellValue('M4', 'Estado del equipo')
		->setCellValue('N4', 'Categoría')
		->setCellValue('O4', 'Subcategoría')
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
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, e.nombre AS estado, 
					m.equipo, m.codequipo as serie, m.activo, m.marca, m.modelo, m.modalidad, m.estado as estadoequipo, 
					f.nombre AS categoria, g.nombre AS subcategoria, c.idambientes AS sitio, h.prioridad, 
					a.origen, a.creadopor, a.solicitante, a.asignadoa, a.departamento, a.resueltopor,
					a.resolucion, a.satisfaccion, a.comentariosatisfaccion, 
					ifnull(a.fechacreacion, '') AS fechacreacion, a.horacreacion, 
					ifnull(a.fecharesolucion, '') as fecharesolucion, a.horaresolucion,
					ifnull(a.fechacierre, '') as fechacierre, a.horacierre, 
					ifnull(a.fechavencimiento, '') AS fechavencimiento, a.horavencimiento, 
					ifnull(a.fechareal, '') AS fechareal, a.horareal, 
					a.horastrabajadas, n.periodo, o.nombre as cliente, p.comentario
					FROM postventas a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.codigo
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN activos m ON a.serie = m.codequipo
					LEFT JOIN cuatrimestres n ON a.fecharesolucion BETWEEN n.fechainicio AND n.fechafin
					LEFT JOIN clientes o ON a.idclientes = o.id
					LEFT JOIN comentarios p ON a.id = p.idmodulo
					WHERE a.idcategorias in (138,139,140,141,142,143,144,145,146,147) ";
		
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idempresas in ($idempresas) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idclientes in ($idclientes) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idproyectos in ($idproyectos) ";
		}
		/*
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.iddepartamentos in ($iddepartamentos) ";
		}
		*/
		
		//DATOS 
		$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Postventas' AND usuario = '".$_SESSION['usuario']."'";
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
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where2 .= " AND a.fechacreacion <= $hastaf ";
			}
			if(!empty($data->idcategoriasf)){
				$idcategoriasf = json_encode($data->idcategoriasf);
				$where2 .= " AND a.idcategorias IN ($idcategoriasf)";
			}
			if(!empty($data->idsubcategoriasf)){
				$idsubcategoriasf = json_encode($data->idsubcategoriasf);
				$where2 .= " AND a.idsubcategorias IN ($idsubcategoriasf)"; 
			}			
			if(!empty($data->idproyectosf)){
				$idproyectosf = json_encode($data->idproyectosf);
				$where2 .= " AND a.idproyectos IN ($idproyectosf)"; 
			}
			if(!empty($data->idprioridadesf)){
				$idprioridadesf = json_encode($data->idprioridadesf);
				$where2 .= " AND a.idprioridades IN ($idprioridadesf)";
			}
			if(!empty($data->modalidadf)){
				$modalidadf = json_encode($data->modalidadf);
				$where2 .= " AND m.modalidad IN ($modalidadf)"; 
			}
			if(!empty($data->idmarcasf)){
				$idmarcasf = json_encode($data->idmarcasf);
				$where2 .= " AND m.marca IN ($idmarcasf)";
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				$where2 .= " AND a.solicitante IN ($solicitantef)";
			}
			if(!empty($data->idestadosf)){
				$idestadosf = json_encode($data->idestadosf);
				$where2 .= " AND a.idestados IN ($idestadosf)";
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
			if(!empty($data->idambientesf)){
				$idambientesf = json_encode($data->idambientesf);
				$where2 .= " AND a.idambientes IN ($idambientesf)"; 
			}
					
			$vowels = array("[", "]");
			$where2 = str_replace($vowels, "", $where2);
		}
		
		//CUERPO
		//Definir fuente
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);					
		
		$query  .= " $where2 ORDER BY a.id desc ";
		
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
			->setCellValue('I'.$i, $row['activo'])
			->setCellValue('J'.$i, $row['marca'])
			->setCellValue('K'.$i, $row['modelo'])
			->setCellValue('L'.$i, $row['modalidad'])
			->setCellValue('M'.$i, $row['estadoequipo'])
			->setCellValue('N'.$i, $row['categoria'])
			->setCellValue('O'.$i, $row['subcategoria'])			
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
		$objPHPExcel->getActiveSheet()->setTitle('Incidentes - Preventivos');

		//Redirigir la salida al navegador del cliente
		$hoy = date('dmY');
		$nombreArc = 'Preventivos - '.$hoy.'.xls';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$nombreArc.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();
	}
	
	function buscarUsuario($nombre){
		global $mysqli;
		
		$query	= " SELECT correo FROM usuarios WHERE nombre = '".$nombre."' ";
		$result = $mysqli->query($query); 
		$row 	= $result->fetch_assoc();
		$usuario  	= $row['correo'];
		return $usuario;
	}

	function getIdCategoria($nombre,$idproyecto){
		global $mysqli;
		
		$q = "SELECT id FROM categorias WHERE nombre = '$nombre' AND idproyecto = '$idproyecto' LIMIT 1";
		//debug($q);
		$r = $mysqli->query($q);
		$val = $r->fetch_assoc();
		$valor = $val['id'];
		return $valor;
	}
	
	function importaractividades(){    
		global $mysqli;
		require_once '../../repositorio-lib/xls/Classes/PHPExcel.php';

		if(isset($_FILES)) {
			$nombre	 	= $_FILES['archivo']['name'];
			$ArrArchivo = explode(".", $nombre);
			$extension 	= strtolower(end($ArrArchivo));
			$randName 	= md5(rand() * time());
			$path 		= '../importpostventas/';

			$nombre_tmp = $_FILES["archivo"]["tmp_name"];
			$nombreA 	= $randName.'-'.basename($_FILES["archivo"]["name"]);
			$rutaA 		= $path."".$nombreA;
			move_uploaded_file($nombre_tmp, $rutaA);		
			
			//DEFINIR LA VERSION DE EXCEL
			if ($extension == 'xls'){
				//debug('xls 1');
				$objReader = PHPExcel_IOFactory::createReader('Excel5');			
			}elseif ($extension == 'xlsx'){
				//debug('xlsx 1');
				$objReader = new PHPExcel_Reader_Excel2007();
				$objReader->setReadDataOnly(true);						
			}
			//CODIGO DE LECTURA Y ESCRITURA
			$objPHPExcel = $objReader->load($rutaA);			
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			
			$importadasExito = 0;
			$importadasExitosas = 0;
			$importadasError = 0;
			$causasError = '<ul>';	
			
			//Se comienza en la fila 2 a procesar el contenido, la fila 1 debe ser el titulo
			for ($row = 2; $row <= $highestRow; $row++){
				// Si ninguna celda esta en blanco
				if ($sheet->getCell('A' . $row)->getValue() != '' && $sheet->getCell('B' . $row)->getValue() != '' && $sheet->getCell('C' . $row)->getValue() != '' && 
					$sheet->getCell('D' . $row)->getValue() != '' /*&& $sheet->getCell('E' . $row)->getValue() != ''*/ && $sheet->getCell('E' . $row)->getValue() != '' && 
					$sheet->getCell('F' . $row)->getValue() != '' && $sheet->getCell('G' . $row)->getValue() != '' && $sheet->getCell('H' . $row)->getValue() != '' && 
					$sheet->getCell('I' . $row)->getValue() != '' ){
					//$repetida = checkActividadRepetida($sheet->getCell('A' . $row)->getValue(),$sheet->getCell('D' . $row)->getValue());
					$repetida = 0;
					if($repetida == 0){
						$rowData[] = $sheet->rangeToArray('A' . $row . ':' . 'I' . $row, NULL, TRUE, FALSE);
						$importadasExitosas++;
					} else {
						$causasError .= '<li>Error en la fila '.$row.', la actividad ya existe</li>';
						$importadasError++;
					}					
				} else {
					$importadasError++;
					if($sheet->getCell('A' . $row)->getValue() == ''){
						$causasError .= '<li>Error en la fila '.$row.', la columna <b>Empresa</b> está vacía</li>';
					}
					if($sheet->getCell('B' . $row)->getValue() == ''){
						$causasError .= '<li>Error en la fila '.$row.', la columna <b>Cliente</b> está vacía</li>';
					}
					if($sheet->getCell('C' . $row)->getValue() == ''){
						$causasError .= '<li>Error en la fila '.$row.', la columna <b>Proyecto</b> está vacía</li>';
					}
					if($sheet->getCell('D' . $row)->getValue() == ''){
						$causasError .= '<li>Error en la fila '.$row.', la columna <b>Categoría</b> está vacía</li>';
					}
					if($sheet->getCell('E' . $row)->getValue() == ''){
						$causasError .= '<li>Error en la fila '.$row.', la columna <b>Sitio</b> está vacía</li>';
					}
					if($sheet->getCell('F' . $row)->getValue() == ''){
						$causasError .= '<li>Error en la fila '.$row.', la columna <b>Fecha de MP</b> está vacía</li>';
					}
					if($sheet->getCell('G' . $row)->getValue() == ''){
						$causasError .= '<li>Error en la fila '.$row.', la columna <b>Horario</b> está vacía</li>';
					}
					if($sheet->getCell('H' . $row)->getValue() == ''){
						$causasError .= '<li>Error en la fila '.$row.', la columna <b>Prioridad</b> está vacía</li>';
					}
					if($sheet->getCell('I' . $row)->getValue() == ''){
						$causasError .= '<li>Error en la fila '.$row.', la columna <b>Responsables</b> está vacía</li>';
					} /*
					if($sheet->getCell('J' . $row)->getValue() == ''){
						$causasError .= '<li>Error en la fila '.$row.', la columna <b>Responsables</b> está vacía</li>';
					}*/
				}
			}		
			$causasError .= '</ul>';
			
			$acciones = '';
			$listaImportadas = '<ul>';
			
			//BD
			for ($j = 0; $j < count($rowData); $j++){
				$ArrItem 	= $rowData[$j][0];
				/* 
				$fecha		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[1], "yyyy-mm-dd");
				$hora 		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[2], "h:mm:ss");
				*/			
				$equipo  		= getId('equipo', 'activos', $ArrItem[4], 'codequipo');
				$titulo			= 'Visita de Postventa'.$equipo;
				$empresa		= trim(str_replace(' ', '', $ArrItem[0]));
				$cliente		= trim(str_replace(' ', '', $ArrItem[1]));
				$proyecto		= trim(str_replace(' ', '', $ArrItem[2]));
				$categoria		= trim(str_replace(' ', '', $ArrItem[3]));
			//	$serie			= trim(str_replace(' ', '', $ArrItem[4]));
				$sitio			= trim(str_replace(' ', '', $ArrItem[4]));
				$fechamp		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[5], "yyyy-mm-dd");
				$horario		= trim(str_replace(' ', '', $ArrItem[6]));
				$prioridad		= trim(str_replace(' ', '', $ArrItem[7]));
				$responsable	= trim(str_replace(' ', '', $ArrItem[8]));
				
				//IDS
				$idempresas  	= getId('id', 'empresas', $empresa, 'descripcion');
				$idclientes  	= getId('id', 'clientes', $cliente, 'nombre');
				$idproyectos  	= getId('id', 'proyectos', $proyecto, 'nombre');
				$idcategorias  	= getIdCategoria($categoria, $idproyectos);
				$idsitios 		= getId('codigo', 'unidades', $sitio, 'unidad');
				$idprioridades 	= getId('id', 'sla', $prioridad, 'prioridad');
				$usuresponsable	= buscarUsuario($responsable);
				$iddepartamento = getId('iddepartamentos', 'usuarios', $usuresponsable, 'correo');
				$listdep 		= explode(',',$iddepartamento);
				$iddepartamentos= $listdep[0];
				if($usuresponsable != ''){
					$idestados = 13;
				}else{
					$idestados = 12;
				}
				
				//debugL($idactivos);
				$query  = " INSERT INTO postventas (id, titulo, idempresas, idclientes, idproyectos, idcategorias, iddepartamentos, idambientes, serie, idprioridades, estado, asignadoa, fechacreacion, horario)
							VALUES (null, '$titulo', '$idempresas', '$idclientes', '$idproyectos', '$idcategorias', '$iddepartamentos', '$idsitios', '$serie', '$idprioridades', '$idestados', '$usuresponsable', '$fechamp', '$horario') ";
				//debug($query);
				$importadasExito++;
				$result = $mysqli->query($query);
				//debug($query);
				$id = $mysqli->insert_id;/*
				if($equipo == ''){
					$causasError .= '<li>Error: El preventivo '.$id.' no ha sido relacionado con el equipo, ya que el número de serie no existe</li>';
					$importadasError++;
				}*/
				if($idsitios == ''){
					$causasError .= '<li>Error: La visita '.$id.' no ha sido relacionado con el sitio, ya que el nombre del sitio no existe</li>';
					$importadasError++;
				}
				$listaImportadas .= '<li>Id #'.$id.' Empresa: '.$empresa.' Cliente: '.$cliente.' Proyecto: '.$proyecto.' Departamento: '.$departamento.'.</li>';
			}
			
			$listaImportadas .= '</ul>';
			
			if($result == true){
				$resultado  = $importadasExito.' filas importadas exitosamente. <br/>';
				$resultado .= $importadasError. ' filas con error. <br/>';
				$resultado .= $causasError;
				
				echo $resultado;
				
				// bitacora
				$acciones .= 'Fue importado el archivo '.$nombre.' para la creación de incidentes.<br/><br/>';
				$acciones .= '<b>Resultado:</b><br/>';
				$acciones .= $resultado;
				$acciones .= '<b>Actividades importadas:</b><br/>';
				$acciones .= $listaImportadas;
				
				bitacora($_SESSION['usuario'],'Plan de Visitas',$acciones,0,'');
			}else{
				$resultado = $importadasError. ' filas con error. <br/>';
				$resultado .= $causasError;
				echo $resultado;
			}

		}
	}

	
	function guardarcolumnaocultar() {
		global $mysqli;
		$tipo 			 = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo']: '');
		$columna 			 = (!empty($_REQUEST['columna']) ? $_REQUEST['columna']: '');
		$usuario 			 = (!empty($_SESSION['user_id']) ? $_SESSION['user_id']: '');
		$query = '';
		if($tipo == 'agregar'){
		    $querycolumnausuarios = "SELECT * FROM columnasocultas where modulo = 'Postventas' and usuario = '$usuario'";
		    $resultcolumnausuarios = $mysqli->query($querycolumnausuarios);
    		if($resultcolumnausuarios->num_rows > 0){
    		    $rowcolumnas = $resultcolumnausuarios->fetch_assoc();
    			$valorcolumnaanterior = $rowcolumnas['columnas'];
    			$columnaagregar = $valorcolumnaanterior.$columna.',';
    			$query = "UPDATE columnasocultas set columnas = '$columnaagregar' where modulo = 'Postventas' and usuario = '$usuario'";
    		}else{
    		    $columnaagregar = $columna.',';
    			$query = " INSERT INTO columnasocultas (id,columnas,usuario,modulo) VALUES (null,'$columnaagregar','$usuario','Postventas') ";
    		}
		}else{
		    $querycolumnausuarios = "SELECT * FROM columnasocultas where modulo = 'Postventas' and usuario = '$usuario'";
		    $resultcolumnausuarios = $mysqli->query($querycolumnausuarios);
    		if($resultcolumnausuarios->num_rows > 0){
    		    $columnaeliminar = $columna;
    		    $rowcolumnas = $resultcolumnausuarios->fetch_assoc();
    			$valorcolumnaanterior = $rowcolumnas['columnas'];
    			$arreglo = array();
			    $arreglo = explode(',',$valorcolumnaanterior);
    			$arreglofinal = array_diff($arreglo, array($columnaeliminar));
    			$columnaguardar = implode(",", $arreglofinal);
    // 			$resultadocolumna = str_replace("$columnaeliminar", "", $valorcolumnaanterior);
                if($columnaguardar == ''){
                    $query = "DELETE FROM columnasocultas where modulo = 'Postventas' and usuario = '$usuario'";
                }else{
    			    $query = "UPDATE columnasocultas set columnas = '$columnaguardar' where modulo = 'Postventas' and usuario = '$usuario'";
                }
    // 			echo $query; die();
    		}
		}

		if($mysqli->query($query))
			echo true;				
	}
	
	function consultarcolumnas() {
		global $mysqli;
		$usuario 			 = (!empty($_SESSION['user_id']) ? $_SESSION['user_id']: '');
		$query = "SELECT columnas from columnasocultas where modulo = 'Postventas' and usuario = '$usuario'";
// 		echo $query;
		$result = $mysqli->query($query);
		if($result->num_rows > 0){
		    $row = $result->fetch_assoc();
		    $columna = $row['columnas'];
		    $columnamostrar = substr($columna, 0, -1);
		    echo $columnamostrar;
		}else{
		    echo 0;
		}
	}
	
?>