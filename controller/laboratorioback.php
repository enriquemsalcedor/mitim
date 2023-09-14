<?php
    include("../conexion.php");
	require_once("Encoding.php");
	use \ForceUTF8\Encoding;
	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}

	switch($oper){
		case "incidentes":
			  incidentes();
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
		case  "estadosbit":
			  estadosbit();
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
		case "notificacionAdjunto":
			 notificacionAdjunto();
			 break;
		case "comentariosleidos":
			 comentariosleidos();
			 break;
		case "exportarExcelConComentarios":
			 exportarExcelConComentarios();
			 break;
		case  "fusionados":
			  fusionados();
			  break;
		case  "generarSalidas":
			 generarSalidas();
			  break;
		case  "consultarCierres":
			  consultarCierres();
			  break;
	    case "guardarcolumnaocultar":
			  guardarcolumnaocultar();
			  break;
		case "consultarcolumnas":
			  consultarcolumnas();
			  break;
		case  "verSalidas":
			  verSalidas();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}
	function obtener_estructura_directorios($ruta){ 
		$contar=0;
		// Se comprueba que realmente sea la ruta de un directorio
		if (is_dir($ruta)){
			// Abre un gestor de directorios para la ruta indicada
			$gestor = opendir($ruta);
			//echo "<ul>";

			// Recorre todos los elementos del directorio
			while (($archivo = readdir($gestor)) !== false)  {
					
				$ruta_completa = $ruta . "/" . $archivo;

				// Se muestran todos los archivos y carpetas excepto "." y ".."
				if ($archivo != "." && $archivo != ".." && $archivo != ".quarantine" && $archivo != ".tmb") {
					// Si es un directorio se recorre recursivamente
					if (is_dir($ruta_completa)) {
						//echo "<li>" . $archivo . "</li>";
						$contar += obtener_estructura_directorios($ruta_completa);
						//debug('paso1:'.$contar);
					} else {
						//echo "<li>" . $archivo . "</li>";
						$contar++; 
											
					}
				}
			}
			
			// Cierra el gestor de directorios
			closedir($gestor);
			//echo "</ul>";
		}   
		return $contar;
	}

	function incidentes()
	{
		global $mysqli;
		 
		
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
	

	    $usuario = (!empty($_SESSION['usuario']) ? $_SESSION['usuario']: '');
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Laboratorio' AND usuario = '".$_SESSION['usuario']."'";
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
			if(!empty($data->idempresasf)){
				$idempresasf = json_encode($data->idempresasf);
				if($idempresasf != '[""]'){
					$where2 .= " AND a.idempresas IN ($idempresasf)"; 
				}				
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
			/*if(!empty($data->categoriaf)){
				$categoriaf = json_encode($data->categoriaf);
				if($categoriaf != '[""]'){
					$where2 .= " AND a.idcategoria IN ($categoriaf)";
				}
			}
			if(!empty($data->subcategoriaf)){
				$subcategoriaf = json_encode($data->subcategoriaf);
				if($subcategoriaf != '[""]'){
					$where2 .= " AND a.idsubcategoria IN ($subcategoriaf)";
				}
			}*/
			if(!empty($data->iddepartamentosf)){
				$iddepartamentosf = json_encode($data->iddepartamentosf);
				if($iddepartamentosf != '[""]'){
					$where2 .= " AND a.iddepartamentos IN ($iddepartamentosf)"; 
				}
			}
			if(!empty($data->idprioridadesf)){
				$idprioridadesf = json_encode($data->idprioridadesf);
				if($idprioridadesf != '[""]'){
					$where2 .= " AND a.idprioridad IN ($idprioridadesf)";
				}				
			}
			/*if(!empty($data->modalidadf)){
				$modalidadf = json_encode($data->modalidadf);
				if($modalidadf != '[""]'){
					$where2 .= " AND a.modalidad IN ($modalidadf)";
				}
			}*/
			if(!empty($data->marcaf)){
				$marcaf = json_encode($data->marcaf);
				if($marcaf != '[""]'){
					$where2 .= " AND a.marca IN ($marcaf)"; 
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
					$where2 .= " AND a.estado IN ($idestadosf)";
				}
			}
			if(!empty($data->diagnosticof)){
				$diagnosticof = json_encode($data->diagnosticof);
				if($diagnosticof != '[""]'){
					$where2 .= " AND a.diagnostico IN ($diagnosticof)";
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
			/*if(!empty($data->unidadejecutoraf)){
				$unidadejecutoraf = json_encode($data->unidadejecutoraf);
				 if($unidadejecutoraf !== '[""]'){ 
					$where2 .= " AND a.unidadejecutora IN ($unidadejecutoraf)";
				}
			}*/
			$vowels = array("[", "]");
			$where2 = str_replace($vowels, "", $where2);
		}
		
		$usuario 		 = $_SESSION['usuario'];
		$nivel 			 = $_SESSION['nivel'];
		$idempresas 	 = $_SESSION['idempresas'];
		$iddepartamentos = $_SESSION['iddepartamentos'];
		$idclientes 	 = $_SESSION['idclientes'];
		$idproyectos 	 = $_SESSION['idproyectos'];
		$query  = " SELECT a.id, e.nombre AS estado, LEFT(a.titulo,45) as titulo, a.titulo as titulott,
					IFNULL(j.nombre, a.solicitante) AS solicitante, IFNULL(k.nombre, a.creadopor) AS creadopor, 
					a.fechacreacion, b.nombre AS idproyectos, 
					a.asignadoa, l.nombre AS nomusuario, a.serie, a.marca, a.modelo, h.prioridad, a.fecharesolucion,  
					n.descripcion as idempresas, o.nombre as iddepartamentos, p.nombre as idclientes, a.estadoant,
					a.diagnostico as estadoequipo,a.fechacierre, e.id as idestados
					FROM laboratorio a
					LEFT JOIN proyectos b ON a.idproyectos = b.id 
					LEFT JOIN estados e ON a.estado = e.id 
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo 
					LEFT JOIN usuarios k ON a.creadopor = k.correo 
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					WHERE 1
					";
		$pos = strpos($iddepartamentos, '4');
		//Validar Solo usuarios Lab / Usuarios Admin Soporte
		if($_SESSION['usuario'] != 'umague' && $_SESSION['usuario'] != 'mbatista' && $_SESSION['usuario'] != 'laboratorio' && $nivel != 1 && $nivel != 2 && $pos !== true){
			$queryCorreoU = " SELECT correo FROM usuarios WHERE usuario = '".$_SESSION['usuario']."'";
			$resultCorreo = $mysqli->query($queryCorreoU);
			if($rowCorreoU = $resultCorreo->fetch_assoc()){
				$correousuario = $rowCorreoU['correo'];
				if($correousuario!=""){
					$query .= " AND (solicitante = '".$correousuario."' OR creadopor = '".$correousuario."')";
				}
			} 
		}
		/*if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idempresas in ($idempresas) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idclientes in ($idclientes) ";
		}
		if ( $nivel != 1 && $nivel != 2 ) {
			$query  .= "AND a.idproyectos in ($idproyectos) ";
		}*/		
		if($nivel == 3) {
			/*$query  .= " AND (
							j.usuario = '".$_SESSION['usuario']."' OR 
							l.usuario = '".$_SESSION['usuario']."' OR
							FIND_IN_SET(a.iddepartamentos,( SELECT GROUP_CONCAT( DISTINCT ee.id SEPARATOR  ',' )			
															FROM usuarios a
															LEFT JOIN departamentos ee ON FIND_IN_SET(ee.id, a.iddepartamentos) AND ee.tipo = 'grupo'
															WHERE a.usuario = '".$_SESSION['usuario']."'))
						)";*/
		}elseif($nivel == 4){
			/*if($_SESSION['sitio'] != ''){
				$sitio = $_SESSION['sitio'];
				$sitio = explode(',',$sitio);
				$sitio = implode("','", $sitio);
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.unidadejecutora IN ('".$sitio."') ) ";
			}else{
				//$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
				if($_SESSION['iddepartamentos'] != ''){
					$iddepartamentosSES = $_SESSION['iddepartamentos'];
					$query  .= "AND FIND_IN_SET(a.iddepartamentos,'".$iddepartamentosSES."')  ";
				}else{
					$query  .= " OR j.usuario = '".$_SESSION['usuario']."' ";
				}
			}	*/		
		}elseif($nivel == 6){
			//$query  .= " AND a.asignadoa = 'soportemaxia@zertifika.com' AND a.estado = 32 ";
			$query  .= " AND a.estado = 32 ";
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
					if($nivel != 6){
							$column = 'e.nombre';
							$where3[] = " $column like '%".$campo."%' ";
					} 
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
				
				if ($column == 'idempresas') {
					$column = 'n.descripcion';
					$where3[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'iddepartamentos') {
					$column = 'o.nombre';
					$where3[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'idclientes') {
					$column = 'p.nombre';
					$where3[] = " $column like '%".$campo."%' ";
				}



				if ($column == 'idproyectos') {
					$column = 'b.nombre';
					$where3[] = " $column like '%".$campo."%' ";
				}

                
				if ($column == 'asignadoa') {
					$column = 'a.asignadoa';
					$where3[] = " $column like '%".$campo."%' ";
				}

				if ($column == 'serie') {
					$column = 'a.serie';
					$where3[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'marca') {
					$column = 'a.marca';
					$where3[] = " $column like '%".$campo."%' ";
				}

				if ($column == 'modelo') {
					$column = 'a.modelo';
					$where3[] = " $column like '%".$campo."%' ";
				}

				if ($column == 'idprioridad') {
					$column = 'h.prioridad';
					$where3[] = " $column like '%".$campo."%' ";
				}

				if ($column == 'fechacierre') {
					$column = 'a.fechacierre';
					$where2[] = " $column like '%".$campo."%' ";
				}
                
				if ($column == 'estadoant') {
					$column = 'a.estadoant';
					$where2[] = " $column like '%".$campo."%' ";
				}


				if ($column == 'estadoequipo') {
					$column = 'a.diagnostico';
					$where2[] = " $column like '%".$campo."%' ";
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
				n.descripcion LIKE '%".$searchGeneral."%' OR
				o.nombre LIKE '%".$searchGeneral."%' OR
				p.nombre LIKE '%".$searchGeneral."%' OR
				b.nombre LIKE '%".$searchGeneral."%' OR
				a.asignadoa LIKE '%".$searchGeneral."%' OR
				a.serie LIKE '%".$searchGeneral."%' OR
				a.marca LIKE '%".$searchGeneral."%' OR
				a.modelo LIKE '%".$searchGeneral."%' OR
				h.prioridad LIKE '%".$searchGeneral."%' OR
				a.fechacierre LIKE '%".$searchGeneral."%' OR
				a.estadoant LIKE '%".$searchGeneral."%' OR
				a.diagnostico LIKE '%".$searchGeneral."%' 

			) ";
		}

		$query.= $where;*/

		$query  .= " GROUP BY a.id ";
		//debug($query);
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
        $query  .= " ORDER BY a.id DESC ";
		//$query  .= " ORDER BY a.id DESC  LIMIT ".$start.",".$rowperpage;
		//debug('funcion:'.$query);
		$resultado = array();
		$result = $mysqli->query($query); 
		while($row = $result->fetch_assoc()){
			$solicitante = $row['solicitante'];
			//ADJUNTOS INCIDENTES
			$tieneEvidencias   = '';
			$rutaE 		= '../laboratorio/'.$row['id'];
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
				// Verifico adjuntos de comentarios  
				$ruta = '../laboratorio/'.$row['id'].'/comentarios';
				$respuesta = obtener_estructura_directorios($ruta); 
				if($respuesta==""){
					$respuesta = 0;
				}  
				if($respuesta > 0){
					$color = 'success';
				}else{
					$color = 'info';
				} 
				
			}
			//COMENTARIOS LEÍDOS 
			$existecom = 0;
			$icon_coment = "";
			
			$sql = " SELECT COUNT(a.id) AS com, COUNT(b.id) AS comv 
					 FROM comentarioslaboratorio a 
					 LEFT JOIN comentariosvistoslaboratorio b ON a.id = b.idcomentario AND b.usuario = '".$usuario."'
					 WHERE a.idmodulo = ".$row['id']."";
			$rta = $mysqli->query($sql);
			if($reg = $rta->fetch_assoc()){
				$com  = $reg["com"];
				$comv = $reg["comv"];
				if($com!=0){
					$existecom = 1;
					$row['fechacreacion'] > "2019-11-14" ? $comv==0 ? $cvisto="text-green" : $cvisto="text-info" : $cvisto="text-info";
				}
			}
			if($existecom==1){
				$icon_coment = '<span class="btn-icon btn-xs boton-coment-'.$row['id'].'" id="boton-comentario" style="padding: 0;">
								<i class="fa fa-comment '.$cvisto.' i-header" aria-hidden="true" style="cursor: initial;"></i></span>';
			}
			$idestados = $row['idestados']; 
			$acciones = '<td>
							<div class="dropdown ml-auto">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
									<span class= "msj-'.$row['id'].' icono-comentario" style="position: absolute;top: -8px;right: 0;">'.$icon_coment.'</span>
								</div>
								<div class="dropdown-menu dropdown-menu-right droptable">
									<a class="dropdown-item text-info" href="laboratorio.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>';
								if(($nivel == 1) || ($nivel == 2 && $pos !== false)){
									$acciones .=	'<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>';
								}
								$acciones .= '<a class="dropdown-item text-'.$color.' boton-evidencias"  data-id='.$row['id'].' "><i class="fas fa-camera mr-2"></i>Evidencias</a>'; 

			$acciones .= 		'</div>
							</div>
						</td>';
			if($row['estadoequipo']=='funcional'){
				$estadoequipo = 'Funcional';
			}elseif($row['estadoequipo']=='irreparable'){
				$estadoequipo = 'Irreparable';
			}elseif($row['estadoequipo']=='sinasignar'){
				$estadoequipo = 'Sin Asignar';
			}

			$resultado[] = array(				
				'check' 			=>	"",
				'acciones' 			=> $acciones,
				'id' 				=> $row['id'],
				'estado' 			=> $row['estado'],
				'titulo' 			=> "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['titulott']."'>".$row['titulo']."</span>",
				'solicitante'		=> $solicitante,
				'fechacreacion' 	=> $row['fechacreacion'], 
				'idempresas'		=> $row['idempresas'],
				'iddepartamentos'	=> $row['iddepartamentos'],
				'idclientes'		=> $row['idclientes'],
				'idproyectos'		=> $row['idproyectos'], 
				'asignadoa'			=> $row['nomusuario'], 
				'serie'				=> $row['serie'],
				'marca'				=> $row['marca'],
				'modelo'			=> $row['modelo'],
				'idprioridad'		=> $row['prioridad'],
				'fechacierre'		=> $row['fechacierre'],
				'estadoant'			=> $row['estadoant'],
				'estadoequipo'		=> $estadoequipo
			);
		} 
		$response = array(
		  "draw" => intval($draw),
		  "recordsTotal" => intval($recordsTotal),
		  "recordsFiltered" => intval($recordsTotal),
		  "data" => $resultado,
//		  "sql"  => $query,
//		  "r"    => $_REQUEST

		);
		echo json_encode($response);
	}  	
	
	function consultarCierres(){
		global $mysqli;	
		
		$entregados = "";
		$noresueltos = "";
		$estadoentregado = 40;
		$estadoresuelto = 16;
		$valores = array();
		
		$id   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		if(is_numeric($id)){
			$query = " SELECT id FROM laboratorio WHERE estado = ".$estadoentregado." AND id =".$id; 
			if($id != ''){							
				if($mysqli->query($query)){
					$result = $mysqli->query($query);
					$count = $result->num_rows; 
					if($count > 0){
						while($row = $result->fetch_assoc()){
							$entregados .=  $row['id']; 
						} 
					}else{
						$query = " SELECT id FROM laboratorio WHERE estado != ".$estadoresuelto." AND id =".$id;
						$result = $mysqli->query($query);
						while($row = $result->fetch_assoc()){
							$noresueltos .=  $row['id']; 
						} 
					} 
					 $valores['entregados'] = $entregados;
					 $valores['noresueltos'] = $noresueltos;
					 echo json_encode($valores);
				}
			}
		}else{
			$idarray = explode(",", $id);
			if(count($idarray) > 1){ 
				foreach($idarray as $id){
					$query = " SELECT id FROM laboratorio WHERE estado = ".$estadoentregado." AND id =".$id; 
					if($id != ''){							
						if($mysqli->query($query)){
							$result = $mysqli->query($query);
							$count = $result->num_rows; 
							if($count > 0){
								while($row = $result->fetch_assoc()){
									$entregados .=  "".$row['id']."";
									$entregados .=  ",";
								} 
							}else{
								$query = " SELECT id FROM laboratorio WHERE estado != ".$estadoresuelto." AND id =".$id;
								$result = $mysqli->query($query);
								while($row = $result->fetch_assoc()){
									$noresueltos .=  "".$row['id']."";
									$noresueltos .=  ",";
								}
							}							
						} 
					} 	
				} 
				$entregados = substr($entregados, 0, -1);
				$noresueltos = substr($noresueltos, 0, -1);
				//echo $entregados;
				$valores['entregados'] = $entregados;
				$valores['noresueltos'] = $noresueltos;
				echo json_encode($valores);
			} 
		} 	 
	}
	
	function generarSalidas(){ 
		global $mysqli;	
		
		$query = ""; 
		$query2 = "";
		$fecha = date('Y-m-d');
		$hora  = date('Hh:mm');
		$id   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		
		$search = " SELECT MAX(orden) as orden FROM laboratoriocierres ";
		$result = $mysqli->query($search);
		$rsn = $result->fetch_assoc();
		$nroorden = $rsn['orden'];
		if($nroorden==""){ 
			$nroorden = 1;
		}else{ 
			$nroorden++;
		}   
		if(is_numeric($id)){
			
			$query .= "UPDATE laboratorio SET estado = 40, fechacierre = CURDATE(), horacierre = DATE_FORMAT(NOW( ), '%H:%i:%s' ) WHERE id = '$id' ";
			$mysqli->query($query);
			
			$cierre = "INSERT INTO laboratoriocierres (idequipo,fecha,hora,usuario) VALUES (".$id.",CURDATE(),DATE_FORMAT(NOW( ), '%H:%i:%s' ),'".$_SESSION['usuario']."')";
			$mysqli->query($cierre); 
			$idcierre = $mysqli->insert_id;
			
			$uporden = " UPDATE laboratoriocierres SET orden = ".$nroorden.", nroorden = 'LAB-".$nroorden."' WHERE id =".$idcierre;
			$mysqli->query($uporden);
			if($id != ''){							
				if($mysqli->query($query)){
					bitacora($_SESSION['usuario'], "Laboratorio", 'El registro #'.$id.' ha sido Editado exitosamente', $id, $query2);
					echo true;
				}else{
					echo false;
				}
			}else{
				echo false;
			}
			
		}else{
			$idarray = explode(",", $id);
			if(count($idarray) > 1){ 
				
				$query .= "UPDATE laboratorio SET estado = 40, fechacierre = CURDATE(), horacierre = DATE_FORMAT(NOW( ), '%H:%i:%s' ) ";  
				foreach($idarray as $id){
					$query2 = $query." WHERE id = '$id' ";  
					
					$cierre = "INSERT INTO laboratoriocierres (idequipo,fecha,hora,usuario) VALUES (".$id.",CURDATE(),DATE_FORMAT(NOW( ), '%H:%i:%s' ),'".$_SESSION['usuario']."')";
					$mysqli->query($cierre);					
					$idcierre = $mysqli->insert_id;
					
					$uporden = " UPDATE laboratoriocierres SET orden = ".$nroorden.", nroorden = 'LAB-".$nroorden."' WHERE id =".$idcierre;
					$mysqli->query($uporden);
					
					if($id != ''){							
						if($mysqli->query($query2)){
							bitacora($_SESSION['usuario'], "Laboratorio", 'El registro #'.$id.' ha sido Editado exitosamente', $id, $query2);
							echo true;
						}else{
							echo false;
						}
					}else{
						echo false;
					}
				} 
			}
		} 
	}
	
	function guardarcolumnaocultar() {
		global $mysqli;
		$tipo 	 	    = $_REQUEST['tipo'];
		$columna 	 	= $_REQUEST['columna'];
		$usuario 		= $_SESSION['user_id'];
		$query = '';
		if($tipo == 'agregar'){
		    $querycolumnausuarios = "SELECT * FROM columnasocultas where modulo = 'Laboratorio' and usuario = '$usuario'";
		    $resultcolumnausuarios = $mysqli->query($querycolumnausuarios);
    		if($resultcolumnausuarios->num_rows > 0){
    		    $rowcolumnas = $resultcolumnausuarios->fetch_assoc();
    			$valorcolumnaanterior = $rowcolumnas['columnas'];
    			$columnaagregar = $valorcolumnaanterior.$columna.',';
    			$query = "UPDATE columnasocultas set columnas = '$columnaagregar' where modulo = 'Laboratorio' and usuario = '$usuario'";
    		}else{
    		    $columnaagregar = $columna.',';
    			$query = " INSERT INTO columnasocultas (id,columnas,usuario,modulo) VALUES (null,'$columnaagregar','$usuario','Laboratorio') ";
    		}
		}else{
		    $querycolumnausuarios = "SELECT * FROM columnasocultas where modulo = 'Laboratorio' and usuario = '$usuario'";
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
                    $query = "DELETE FROM columnasocultas where modulo = 'Laboratorio' and usuario = '$usuario'";
                }else{
    			    $query = "UPDATE columnasocultas set columnas = '$columnaguardar' where modulo = 'Laboratorio' and usuario = '$usuario'";
                }
    // 			echo $query; die();
    		}
		}

		if($mysqli->query($query))
			echo true;				
	}
	
	function consultarcolumnas() {
		global $mysqli;
		$usuario 		= $_SESSION['user_id'];
		$query = "SELECT columnas from columnasocultas where modulo = 'Laboratorio' and usuario = '$usuario'";
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
	
	function verSalidas(){
		global $mysqli; 
		
		$usuario   = (!empty($_SESSION['usuario']) ? $_SESSION['usuario']: '');
		$resultado = array();
		
		$query  = " SELECT DISTINCT(a.nroorden) as orden, a.fecha, a.usuario 
					FROM laboratoriocierres a 
					WHERE 1 ";
		$query  .= "ORDER BY a.id DESC ";
		
		//debug('versalidas:'.$query);
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		while($row = $result->fetch_assoc()){ 
			$acciones = '<td>
							<div class="dropdown ml-auto text-right">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-right droptable"> ';
			 				
				$acciones .= '<a class="dropdown-item text-info boton-ver-salidas" data-id="'.$row['orden'].'"><i class="fas fa-eye mr-2"></i>Ver</a>';	 

			$acciones .= 		'</div>
							</div>
						</td>';
						
			$resultado[] = array(			
				'acciones' 	=> $acciones,
				'orden' 	=> $row['orden'],
				'fecha' 	=> $row['fecha'],
				'usuario'	=> $row['usuario']
			);
		}
		$response = array( 
		  "data" => $resultado,
		  "recordsTotal" => intval($recordsTotal),
		  "recordsFiltered" => intval($recordsTotal),
		);
		echo json_encode($response);
	}
	
	function eliminarincidentes()
	{
		global $mysqli;

		$id 	= $_REQUEST['idincidente'];	
		
		//Verifico si el estado es Entregado (40)
		$queryS = " SELECT estado FROM laboratorio WHERE id = '$id'";
		$resultS = $mysqli->query($queryS);
		if($resultS->num_rows > 0){
			$rowS = $resultS->fetch_assoc();
			$idestados = $rowS['estado'];
		}
		
		$query 	= "DELETE FROM laboratorio WHERE id = '$id'";
		$result = $mysqli->query($query);
		if($result == true){
			
			if($idestados == 40){
			
				//Verifico el Número de Orden del reporte de salida del registro
				$queryO = " SELECT id, nroorden FROM laboratoriocierres WHERE idequipo = '$id'";
				$resultO = $mysqli->query($queryO);
				if($resultO->num_rows > 0){
					$rowO = $resultO->fetch_assoc();
					$idorden  = $rowO['id'];
					$nroorden = $rowO['nroorden'];
				}
				
				$queryD = " DELETE FROM laboratoriocierres WHERE idequipo = '$id'";
				$resultD = $mysqli->query($queryD);
				if($resultD == true){
					bitacora($_SESSION['usuario'], "Laboratorio", 'El registro del equipo #'.$id.' asociado a la Órden #: '.$nroorden.' fue eliminado de la orden con Id # '.$idorden, $id, $queryD);				
				}
			}
			echo 1;
		}else{
			echo 0;
		}
		bitacora($_SESSION['usuario'], "Laboratorio", 'El registro #: '.$id.' fue eliminado.', $id, $query);				
	}
	
	function eliminarcomentarios()
	{
		global $mysqli;

		$idincidente = $_REQUEST['idincidente']; 								   
		$id 	 	 = $_REQUEST['idcomentario']; 
		$nivel 	 	 = $_SESSION['nivel'];
		$usuario 	 = $_SESSION['usuario'];
		 
		//Elimino el comentario si es usuario administrador o soporte
		if ($nivel==1 || $nivel==2){
			$query    = "  DELETE FROM comentarioslaboratorio WHERE id = '$id'";			            
			$resultEs   =    $mysqli->query($query);
			if($resultEs){
				//Elimino evidencias del comentario
				$carpeta = '../laboratorio/'.$idincidente.'/comentarios/'.$id.'/';
				deleteDirectory($carpeta); 		   
			    echo 1;
			}else{
			    echo 0;
			} 			
		}else{
			//Consulto si el usuario es el creador del comentario											
		    $query  = "  SELECT * FROM comentarioslaboratorio WHERE id = '$id' AND usuario = '$usuario' ";    			
    	    $resultNoes =    $mysqli->query($query);    			
			if($resultNoes->num_rows > 0){                     
				//Elimino el comentario		   
				$querySi  = "  DELETE FROM comentarioslaboratorio WHERE id = '$id' AND usuario = '$usuario' ";    			                
				$resultSi =    $mysqli->query($querySi);				
				if($resultSi==true){            		    
					//Elimino evidencias del comentario
					$carpeta = '../laboratorio/'.$idincidente.'/comentarios/'.$id.'/';
					deleteDirectory($carpeta);
				
					echo 1;            		
				}else{            		 
					echo 0;            		
				}    			    
			}else{                    
				echo 2;                
			}			
		}
		bitacora($_SESSION['usuario'], "Laboratorio", 'El Comentario #: '.$id.' fue eliminado.', $id, $query); 
	}

	function deleteDirectory($dir) {
		if(!$dh = @opendir($dir)) return;
		while (false !== ($current = readdir($dh))) {
			if($current != '.' && $current != '..') {
				//echo 'Se ha borrado el archivo '.$dir.'/'.$current.'<br/>';
				if (!@unlink($dir.'/'.$current)) 
					deleteDirectory($dir.'/'.$current);
			}       
		}
		closedir($dh);
		//echo 'Se ha borrado el directorio '.$dir.'<br/>';
		@rmdir($dir);
	}
	function abrirSolicitudes() {
		$incidente 	= $_REQUEST['incidente'];		
		$_SESSION['incidentelab'] = $incidente;
		$_SESSION['comentariolab'] = '';
		$myPathInc = '../laboratorio';
		$target_pathInc = utf8_decode($myPathInc);
		if (!file_exists($target_pathInc)) {
			mkdir($target_pathInc, 0777);
		}
		$myPath = '../laboratorio/'.$incidente;
		$target_path = utf8_decode($myPath);
		if (!file_exists($target_path)) {
			mkdir($target_path, 0777);
		}
		//$Path = dirname($_SERVER['PHP_SELF']) . '/../incidentes/'.$_SESSION['incidente'].'/';
		$Path = '/../laboratorio/'.$incidente.'/';
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
			$queryI = "INSERT INTO comentarioslaboratorio VALUES(null, 'Laboratorio', $incidente, '$comentario', '$visibilidad', '$usuario', NOW(), 'NO')";
			//debug('queryI: '.$_GET['comentario']);
			if($mysqli->query($queryI)){
				$id = $mysqli->insert_id;
				//BITACORA
				bitacora($_SESSION['usuario'], "Laboratorio", "Se ha registrado un Comentario para el registro #".$incidente, $incidente, $queryI);
				//ENVIAR NOTIFICACION
				if($visibilidad == 'Privado'){
					//notificarComentariosSoporte($incidente,$comentario,$visibilidad);
				}else{
					//notificarComentariosSoporte($incidente,$comentario,$visibilidad);
					//notificarComentarios($incidente,$comentario,$visibilidad);
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
				
				$usuarios = json_encode($idusuarios);
				
				$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,fecha,hora,usuarios) VALUES (".$idproyectos.",".$incidente.",'Comentario realizado laboratorio','". date("Y-m-d") ."','". date("H:i:s") ."','".$usuarios."')"; 
	            $rsql = $mysqli->query($sql); 
				
				//*******************************************//
				//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
				//*******************************************//																		
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
		$nivel 	   = $_SESSION['nivel'];
		$id 	   = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$buscar    = (isset($_POST['buscar']) ? $_POST['buscar'] : '');
		$resultado = array();
		$acciones  = '';
		$queryF	   = " SELECT GROUP_CONCAT(id) AS fusionados FROM laboratorio WHERE fusionado = '$id' ";
		$resultF  = $mysqli->query($queryF);
		if($resultF->num_rows > 0){
			$rowF = $resultF->fetch_assoc();
			$fusionados = $rowF['fusionados'];
			if($fusionados == ""){
				$idmodulo = $id;
			}else{
				$idmodulo = $id.','.$fusionados;
			}
		}
		$query  = " SELECT a.id, a.idmodulo, a.comentario, a.fecha, b.nombre, a.visibilidad
					FROM comentarioslaboratorio a
					LEFT JOIN usuarios b ON a.usuario = b.usuario
					WHERE modulo = 'Laboratorio' AND idmodulo IN ($idmodulo) AND a.visibilidad != '' ";
		if($nivel == 4){
			$query .= " AND a.visibilidad = 'Público' ";
		}
		$query .= " ORDER BY a.id DESC ";
		//debug('comentarios:'.$query);
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			//ADJUNTOS
			$adjuntos   = '';
			$ruta 		= '../laboratorio/'.$row['idmodulo'].'/comentarios/'.$row['id'];
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
								<div class="dropdown-menu dropdown-menu-right droptable"> ';
			if($nivel != 4){					
				$acciones .= '<a class="dropdown-item text-danger boton-eliminar-comentarios" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>';	
			}
				$acciones .= '<a class="dropdown-item text-'.$color.' boton-adjuntos-comentarios"  data-id='.$row['id'].' "><i class="fas fa-camera mr-2"></i>Evidencias de comentario</a>'; 

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
				'adjuntos' 		=> $adjuntos
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
	
	function comentariosleidos(){		
		global $mysqli;		
		$idincidente 	= $_REQUEST['idincidente'];
		$usuario        = $_SESSION['usuario'];
		
		
		$queryC = "	SELECT id FROM comentarioslaboratorio WHERE idmodulo = '$idincidente' AND visto != '' "; 
		$resultC = $mysqli->query($queryC);
		while($rowC = $resultC->fetch_assoc()){
			$idc = $rowC['id'];
			
			
		    $queryV = " SELECT count(id) AS id FROM comentariosvistoslaboratorio WHERE idcomentario = '".$idc."' 
						AND usuario = '".$usuario."' ";
			$resultV = $mysqli->query($queryV);
			$rowV = $resultV->fetch_assoc();
			$idv = $rowV['id'];
			if($idv == 0){  
				$query = "INSERT INTO comentariosvistoslaboratorio (idcomentario, usuario, fecha)
						  VALUES ('$idc', '$usuario', NOW())";
				
			    $result = $mysqli->query($query);
				if($result == true){
					$upd = " UPDATE comentarioslaboratorio SET visto = 'SI'
							 WHERE idmodulo = '$idincidente' AND visto = 'NO' ";			
					$resupd = $mysqli->query($upd); 
					echo 1;
				}else{
					echo 0;
				} 
			} 
		} 		
	}
	
/* 	function comentariosleidos(){		
		global $mysqli;		
		$idincidente 	= $_REQUEST['idincidente'];
		
		$query = " SELECT visto FROM comentarios 
				   WHERE visto = 'NO' AND idmodulo = '$idincidente' ";		
		$result = $mysqli->query($query);
		
		if($result->num_rows > 0){
			$upd = " UPDATE comentarios SET visto = 'SI'
					 WHERE idmodulo = '$idincidente' AND visto = 'NO' ";			
			$resupd = $mysqli->query($upd);			
			if($resupd==true){
				echo 1;
			}else{
				echo 0;
			}
		}		
	} */
 
	function adjuntosComentarios() {
		$incidente 	= $_REQUEST['idincidente'];
		$comentario = $_REQUEST['idcomentario'];
		/* $arr 		= explode('-',$incidentecom);
		$incidente 	= $arr[0];
		$comentario = $arr[1];
		$_SESSION['incidentelab'] 	= $incidente;
		$_SESSION['comentariolab'] = $comentario; */
		
		$myPathC 	  = '../laboratorio/'.$incidente.'/comentarios/';
		$target_pathC = utf8_decode($myPathC);
		if (!file_exists($target_pathC)) {
			mkdir($target_pathC, 0777);
		}
		$myPath 	 = '../laboratorio/'.$incidente.'/comentarios/'.$comentario;
		$target_path = utf8_decode($myPath);
		if (!file_exists($target_path)) {
			mkdir($target_path, 0777);
		}
		//$Path = dirname($_SERVER['PHP_SELF']) . '/../incidentes/'.$_SESSION['incidente'].'/';
		$Path = '/../laboratorio/'.$incidente.'/comentarios/'.$comentario.'/';
		$hash = strtr(base64_encode($Path), '+/=', '-_.');
		$hash = rtrim($hash, '.');		
		echo "l1_". $hash;		
	}
	
	//ENVIAR CORREO DE NOTIFICACION DE COMENTARIO
	function notificarComentarios($incidente,$comentario,$visibilidad){
		global $mysqli;
		//CREADOR - SOLICITANTE - ASIGNADO
		$query  = " SELECT a.titulo, IFNULL(i.correo, a.creadopor) AS creadopor, 
					a.solicitante, a.asignadoa, a.notificar, a.titulo
					FROM laboratorio a
					LEFT JOIN usuarios i ON a.creadopor = i.id OR a.creadopor = i.correo
					WHERE a.id = $incidente AND i.id != 0 ";
		$result = $mysqli->query($query);
		while ($row = $result->fetch_assoc()) {
			/*
			if($visibilidad == 'Privado'){
				$correo [] = $row['creadopor'];
			}else
			*/
			if($visibilidad != 'Privado'){
				/*
				if( $row['creadopor'] != 'mesadeayuda@innovacion.gob.pa' ){
					$correo [] = $row['creadopor'];
				}
				if( $row['solicitante'] != 'mesadeayuda@innovacion.gob.pa' ){
					$correo [] = $row['solicitante'];
				}
				*/				
				$correo [] = $row['creadopor'];
				$correo [] = $row['solicitante'];
				
				//Usuarios que quieren que se les notifique (Enviar Notificacion a)
				$notificar = json_decode($row['notificar']);
				if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
					if( $notificar != 'mesadeayuda@innovacion.gob.pa' ){
						$correo [] = $notificar;
					}
				}else{
					foreach($notificar as $notif){
						if( $notif != 'mesadeayuda@innovacion.gob.pa' ){
							$correo [] = $notif;
						}
					}
				}
			}
			
			//USUARIO O GRUPO DE USUARIOS ASIGNADOS
			$asignadoaN	= '';		
			if($row['asignadoa'] != ''){
				$asignadoa  = $row['asignadoa'];
				if (filter_var($asignadoa, FILTER_VALIDATE_EMAIL)) {
					if( $asignadoa != 'mesadeayuda@innovacion.gob.pa' ){
						$correo [] = $asignadoa;
					}
				}else{
					foreach([$asignadoa] as $asig){
						if( $asig != 'mesadeayuda@innovacion.gob.pa' ){
							$correo [] = $asig;
						}
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
		$consultaUA = $mysqli->query(" SELECT nombre FROM usuarios WHERE usuario = '$usuarios' LIMIT 1 ");
		while ($registroUA = $consultaUA->fetch_assoc()) {
			$usuarioAct = $registroUA['nombre'];
		}
		//DATOS
		$query  = " SELECT a.id, a.titulo, a.descripcion, a.resolucion, a.idproyectos,
					h.prioridad, a.origen, a.asignadoa, IFNULL(i.nombre, a.creadopor) AS creadopor, 
					IFNULL(j.nombre, a.solicitante) AS solicitante, a.creadopor AS ccreadopor, a.solicitante AS csolicitante,
					a.departamento, a.fechacreacion
					FROM laboratorio a
					LEFT JOIN proyectos b ON a.idproyectos = b.id  
					LEFT JOIN estados e ON a.estado = e.id 
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
		$csolicitante	= $row['csolicitante'];
		$ccreadopor		= $row['ccreadopor'];
		$departamento	= $row['departamento'];
		$prioridad		= $row['prioridad']; 
		$resolucion 	= $row['resolucion'];
		$idproyectos 	= $row['idproyectos'];
		$nasignadoa 	= $asignadoaN;
		$comentarios	= '';
		$bitacora		= '';
		
		//COMENTARIOS
		$consultaC = $mysqli->query("SELECT comentario FROM comentarioslaboratorio WHERE idmodulo = $incidente AND visibilidad != 'Privado'");
		while ($registroC = $consultaC->fetch_assoc()) {
			$comentarios .= $registroC['comentario'].'<br>';
		}
		//BITACORA
		$consultaB = $mysqli->query("SELECT accion FROM bitacora WHERE identificador = $incidente ");
		while ($registroB = $consultaB->fetch_assoc()) {
			$bitacora .= $registroB['accion'].'<br>';
		}
		$enviar = 1;
		if($csolicitante == 'mesadeayuda@innovacion.gob.pa' || $ccreadopor == 'mesadeayuda@innovacion.gob.pa' ){
			$titulo 	= $row['titulo'];
			$arrtitulo  = explode(':', $titulo);
			$arrnuminc  = $arrtitulo[0];
			$tinc = strpos($titulo, "INC ");
			$treq = strpos($titulo, "REQ ");
			if($tinc !== false){
				$arrnum = explode('INC ', $arrnuminc);
				$isist 	= " - INC ".$arrnum[1];
			}else{
				$arrnum 	= explode('REQ ', $arrnuminc);
				$isist 	= " - REQ ".$arrnum[1];
			}
			$numinc 	= $arrnum[1];
		    $asunto 	= "Incidente #$incidente - Comentario - INC $numinc";
			$enviar 	= 0;
    	} else {
			$numinc 	= '';
    	    $asunto = "Incidente #$incidente - Comentario ";
		}
		
		$mensaje  = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha comentado el incidente #".$incidente." - ".$isist."</b></p>			
					<p style='padding-left: 30px;width:100%;'>Comentario: ".$comentario."</p>
					<p style='width:100%;'><br><a href='http://toolkit.maxialatam.com/soporte/laboratorio.php?id=$incidente' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Añadir un comentario</a></p>
					<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin-top: 30px;padding: 10px 10px 0 30px;width:100%;'>Comentarios anteriores</p>";
					if($comentarios != ''){
						$mensaje .="<p style='padding-left: 30px;width:100%;'>".$comentarios."</p>";
					}
					$mensaje .="
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
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		if ($enviar==1)
			enviarMensajeIncidente($asunto,$mensaje,$correo,'','comentario');
	}
	
	//ENVIAR CORREO DE NOTIFICACION DE COMENTARIO
	function notificarComentariosSoporte($incidente,$comentario,$visibilidad){
		global $mysqli;
		
		//DATOS DEL CORREO
		$usuarios = $_SESSION['usuario'];
		$consultaUA = $mysqli->query(" SELECT nombre FROM usuarios WHERE usuario = '$usuarios' LIMIT 1 ");
		while ($registroUA = $consultaUA->fetch_assoc()) {
			$usuarioAct = $registroUA['nombre'];
		}
		//DATOS
		$query  = " SELECT a.id, a.titulo, a.descripcion, a.resolucion, a.idproyectos,
					h.prioridad, IFNULL(j.nombre, a.solicitante) AS solicitante, a.asignadoa,
					a.departamento, a.fechacreacion, a.idclientes					
					FROM laboratorio a
					LEFT JOIN proyectos b ON a.idproyectos = b.id  
					LEFT JOIN estados e ON a.estado = e.id  
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
		$sitio 			= $row['unidadejecutora'];
		$resolucion 	= $row['resolucion'];
		$idclientes 	= $row['idclientes'];
		$idproyectos 	= $row['idproyectos'];
		$nasignadoa 	= $asignadoaN;
		$comentarios	= '';
		$bitacora		= '';
		
		//COMENTARIOS
		$consultaC = $mysqli->query("SELECT comentario FROM comentarioslaboratorio WHERE idmodulo = $incidente ");
		while ($registroC = $consultaC->fetch_assoc()) {
			$comentarios .= $registroC['comentario'].'<br>';
		}
		//BITACORA
		$consultaB = $mysqli->query("SELECT accion FROM bitacora WHERE identificador = $incidente ");
		while ($registroB = $consultaB->fetch_assoc()) {
			$bitacora .= $registroB['accion'].'<br>';
		}
		$enviar = 1;
		if($solicitante == 'mesadeayuda@innovacion.gob.pa' || $creadopor == 'mesadeayuda@innovacion.gob.pa' ){
			$titulo = $row['titulo'];
			$arrtitulo  = explode(':', $titulo);
			$arrnuminc  = $arrtitulo[0];
			$tinc = strpos($titulo, "INC ");
			$treq = strpos($titulo, "REQ ");
			if($tinc !== false){
				$arrnum 	= explode('INC ', $arrnuminc);
				$isist 	= " - INC ".$arrnum[1];
			}else{
				$arrnum 	= explode('REQ ', $arrnuminc);
				$isist 	= " - REQ ".$arrnum[1];
			}
			$numinc 	= $arrnum[1];
		    $asunto 	= "Incidente #$incidente - Comentario - ".$isist;
			$enviar 	= 0;
    	} else {
			$numinc 	= '';
    	    $asunto = "Incidente #$incidente - Comentario ";
		}
		
		$mensaje  = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha comentado el incidente #".$incidente." - ".$isist."</b></p>			
					<p style='padding-left: 30px;width:100%;'>Comentario ".$visibilidad.": ".$comentario."</p>
					<p style='width:100%;'><br><a href='http://toolkit.maxialatam.com/soporte/laboratorio.php?id=$incidente' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Añadir un comentario</a></p>
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
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		$correo [] = $row['asignadoa'];
		//CLIENTE AIG - USUARIOS DE PRUEBA
		if($idclientes == 13 && $visibilidad == 'Público' && $row['asignadoa'] == 'soportemaxia@zertifika.com'){
			$queryc = " SELECT correo FROM usuarios WHERE nivel = 6 AND idclientes = 13 ";
			$consultac = $mysqli->query($queryc);
			while($recc = $consultac->fetch_assoc()){
				$correo [] = $recc['correo'];	
			}
		}
		if ($enviar==1)
			enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
	}
	function limpiarUTF8($string){
		$stringlm = str_replace('ï¿½','',$string);
		$stringf  = Encoding::fixUTF8($stringlm); 
		$valoract = html_entity_decode($stringf);
		$valornvo    = htmlspecialchars_decode($valoract);
		return $valornvo;
	}

	function abrirIncidente(){
		global $mysqli;
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$iddepartamentos = $_SESSION['iddepartamentos'];
		$usuario 		 = $_SESSION['usuario'];							 
		$pos = strpos($iddepartamentos, '4');
		$resultado 	 = array();
		$query  = " SELECT a.id, a.titulo, a.descripcion, a.idproyectos, a.serie, a.marca, a.modelo, e.id AS estado,  h.id AS prioridad, 
					a.solicitante, a.asignadoa, a.departamento, CONCAT_WS('',j.id,' - ', j.titulo) AS fusionado, a.notificar, a.resolucion, a.comentariosatisfaccion, 
					a.fechacreacion, IF(a.fecharesolucion is not null OR LENGTH(ltrim(rTrim(a.fecharesolucion))) > 0,CONCAT(a.fecharesolucion,'  ', IFNULL(a.horaresolucion,'')),'') AS fecharesolucion,
					a.fechacierre, a.idempresas, a.iddepartamentos, a.idclientes, a.fechaentrada, a.horaentrada, a.diagnostico					
					FROM laboratorio a 
					LEFT JOIN estados e ON a.estado = e.id  
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					LEFT JOIN laboratorio j ON a.fusionado = j.id
					LEFT JOIN usuarios k ON a.resueltopor = k.correo   
					WHERE a.id = $id ";
					
					
		//debug('abrirI:'.$query);
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			if($row['marca'] == '0')
				$row['marca']='';
			if($row['modelo'] == '0')
				$row['modelo']=''; 
			if($row['comentariosatisfaccion'] == '0')
				$row['comentariosatisfaccion']=''; 
			if($row['descripcion'] == '0')
				$row['descripcion']='';
			
			//reviso la cadena y solo tomo el correo
			$solicitante = $row['solicitante'];
			$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';
			if(strpos($solicitante, '<') == true){
				preg_match ( $pattern, $solicitante, $solicitante );
			}
			//NOTIFICAR
			/*
			$notificar = $row['notificar'];
			$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';
			if(strpos($notificar, '<') == true){
				preg_match ( $pattern, $notificar, $notificar );
			}
			*/
			if($row['notificar'] != ''){
				$decoded = json_decode($row['notificar']);
				//$notificararr = array_filter($decoded);
				$notificararr = array_values(array_unique(array_filter($decoded)));
				$notificar = json_encode($notificararr);
			}else{
				$notificar = $row['notificar'];
			}

			//Limpiar Campo Descripción
			$string      = $row['descripcion'];
			$descripcion = limpiarUTF8($string);	

			if($pos !== true){
				$valor = 0;
			}else{
				$valor = 1;
			}
			//Usuario miguel y uzziel (Técnicos)
			$estecnico = 1;
			if($usuario != 'umague' &&  $usuario != 'mbatista'){
				$estecnico = 0;
			}
			$resultado[] = array(
						'id' 					=> $row['id'],
						'titulo'				=> $row['titulo'],
						'descripcion' 			=> $descripcion,
						'idempresas' 			=> $row['idempresas'],
						'iddepartamentos'		=> $row['iddepartamentos'],
						'idclientes' 			=> $row['idclientes'],
						'idproyectos' 			=> $row['idproyectos'], 
						'serie' 				=> $row['serie'], 
						'marca' 				=> $row['marca'],
						'modelo' 				=> $row['modelo'],
						'estado' 				=> $row['estado'], 
						'prioridad' 			=> $row['prioridad'],
						'solicitante' 			=> $solicitante,
						'asignadoa' 			=> $row['asignadoa'], 
						'fusionado' 			=> $row['fusionado'],
						'notificar' 			=> $notificar,
						'resolucion' 			=> $row['resolucion'], 
						'marca' 				=> $row['marca'],
						'modelo' 				=> $row['modelo'], 
						'fechacreacion' 		=> $row['fechacreacion'], 
						'fechaentrada' 			=> $row['fechaentrada'].' '.$row['horaentrada'], 
						'fecharesolucion' 		=> $row['fecharesolucion'],	 
						'diagnostico' 			=> $row['diagnostico'],	 
						'departamentous'		=> $valor,	  
						'estecnico'				=> $estecnico 
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
		$serie 				= (!empty($data['serie']) ? $data['serie'] : '');
		$marca 				= (!empty($data['marca']) ? $data['marca'] : '');
		$modelo 			= (!empty($data['modelo']) ? $data['modelo'] : '');
		$estado 			= (!empty($data['idestados']) ? $data['idestados'] : 12); 
		$prioridad 			= (!empty($data['idprioridades']) ? $data['idprioridades'] : 0); 
		$solicitante 		= (!empty($data['solicitante']) ? $data['solicitante'] : $_SESSION['correousuario']); 
		$creadopor			= (!empty($data['creadopor']) ? $data['creadopor'] : $_SESSION['correousuario']);
		$asignadoa 			= (!empty($data['asignadoa']) ? $data['asignadoa'] : '');
		$departamento 		= (!empty($data['departamento']) ? $data['departamento'] : '');
		$notificar 			= (!empty($data['notificar']) ? $data['notificar'] : '');
		$resolucion 		= (!empty($data['resolucion']) ? $data['resolucion'] : ''); 
		$horario 			= (!empty($data['horario']) ? $data['horario'] : '');
		//$fechavencimiento	= NULL;
		///$horavencimiento  	= NULL;
		$fecharesolucion 	= (!empty($data['fecharesolucion']) ? $data['fecharesolucion'] : '');
		$fechacierre 		= (!empty($data['fechacierre']) ? $data['fechacierre'] : ''); 
		$fechacreacion		= (!empty($data['fechacreacion']) ? $data['fechacreacion'] : date("Y-m-d")); 
		$estadoInc 			= '';
		$atencion	  	 	= '';
		$idusuario 			= $_SESSION['user_id'];
		$nivel	 			= $_SESSION['nivel'];
		//$fueraservicio 		= (!empty($data['fueraservicio']) ? $data['fueraservicio'] : '0');
		$fechaentrada 		= (!empty($data['fechaentrada']) ? $data['fechaentrada'] : '');
		
		if($fechaentrada != ''){
			$arrfechaentrada = explode(' ',str_replace("'","",$fechaentrada));
			$fechaentrada  = $arrfechaentrada[0];
			$horaentrada = $arrfechaentrada[1];			
		}else{
			$fechaentrada = 'null';
			$horaentrada  = 'null';
		}
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
			//$horavencimiento  	= date('H:i:s', strtotime($horacreacion." + ".$horasP." hours"));
		}
		//SOLICITANTE 
		if($solicitante == ''){
			$queryU  	 = " SELECT correo FROM usuarios WHERE id = '".$idusuario."' ";
			$resultU 	 = $mysqli->query($queryU);
			$rowU 	 	 = $resultU->fetch_assoc();
			$solicitante = $rowU['correo'];			
		}
		//AGREGAR
		$fechacreacion	= date('Y-m-d');
		$horacreacion 	= date('H:i:s'); 
		
		//CLIENTES 
		if($idclientes == 0 && $nivel == 4){
			$queryCU  	 = " SELECT idclientes FROM usuarios WHERE id = '".$idusuario."' ";
			$resultCU 	 = $mysqli->query($queryCU);
			$rowCU 	 	 = $resultCU->fetch_assoc();
			$idclientes = $rowCU['idclientes'];			
		}
		
		$query = "  INSERT INTO laboratorio (id, titulo, descripcion,  serie, estado, idprioridad, solicitante, asignadoa, 
					 fechacreacion, notificar, idempresas, idclientes, idproyectos, iddepartamentos, 
					marca, modelo, fechaentrada, horaentrada,creadopor) ";
		$query .= "  VALUES(null, '".$titulo."', '".$descripcion."',  '".$serie."', 
					'".$estado."', '".$prioridad."', '".$solicitante."', 'laboratorio@correo.com', '".$fechacreacion."', '".$notificar."', 
					'".$idempresas."', '".$idclientes."', '".$idproyectos."', 12,
					 '".$marca."','".$modelo."',".$fechaentrada.",".$horaentrada.",'".$creadopor."') ";		 
		
		//debug('guardarI:'.$query);
		if($mysqli->query($query)){
			$id = $mysqli->insert_id;
			if($id != ''){
				//CREAR REGISTRO EN ESTADOS INCIDENTES
				$queryE = " INSERT INTO laboratorioestados VALUES(null, $id, 35, '$estado', $idusuario, now(), now(), now(), now(), 0) ";
				$mysqli->query($queryE);
				
				$queryA = " INSERT INTO laboratorioactivos (nombre,serie,marca,modelo,fechacreacion) VALUES ('".$titulo."','".$serie."','".$marca."','".$modelo."',NOW())";
				$mysqli->query($queryA);
				//CREAR CARPETA DE ID INCIDENTES Y COMENTARIOS
				$myPath = '../laboratorio/';
				if (!file_exists($myPath))
					mkdir($myPath, 0777);
				$myPath = '../laboratorio/'.$id.'/';
				$target_path2 = utf8_decode($myPath);
				if (!file_exists($target_path2))
					mkdir($target_path2, 0777);
				
				if($_SESSION['nivel'] == 4){
					//MOVER DEL TEMP A INCIDENTES
					$num 	= $_SESSION['user_id'];
					$from 	= '../laboratoriotemp/'.$num;
					$to 	= '../laboratorio/'.$id.'/';
					debug('num:'.$num);
					/* //Abro el directorio que voy a leer
					$dir = opendir($from);

					//Recorro el directorio para leer los archivos que tiene
					while(($file = readdir($dir)) !== false){
						//Leo todos los archivos excepto . y ..
						if(strpos($file, '.') !== 0){
							//Copio el archivo manteniendo el mismo nombre en la nueva carpeta
							copy($from.'/'.$file, $to.'/'.$file);
						}
					} */
				} 
				//ENVIAR CORREO AL CREADOR DEL INCIDENTE
				//nuevoincidente($_SESSION['usuario'], $titulo, $descripcion, $id, $fechacreacion, $horacreacion, $solicitante,$notificar); 			
				//notificarCEstado($id,$notificar,'creado','',$estado); //$incidente,$notificar,$accion,$estadoold,$estadonew
			}
			$accion = 'El registro #'.$id.' ha sido Creado exitosamente';
			bitacora($_SESSION['usuario'], "Laboratorio", $accion, $id, $query);

			//ENVIAR CORREO DE SATISFACCION - RESUELTO / CERRADO
			//if($estado == 16 || $estado == 17){
				//crearMensajeSatisfaccion($id,$titulo,$solicitante);
			//}				
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
		$iddepartamentos	= (!empty($data['iddepartamentos']) ? $data['iddepartamentos'] : '');
		$idclientes 		= (!empty($data['idclientes']) ? $data['idclientes'] : '');
		$idproyectos 	    = (!empty($data['idproyectos']) ? $data['idproyectos'] : ''); 
		$serie 				= (!empty($data['serie']) ? $data['serie'] : '');
		$marca 				= (!empty($data['marca']) ? $data['marca'] : '');
		$modelo 			= (!empty($data['modelo']) ? $data['modelo'] : '');
		$estado 			= (!empty($data['idestados']) ? $data['idestados'] : ''); 
		$prioridad 			= (!empty($data['idprioridades']) ? $data['idprioridades'] : '0'); 
		$solicitante 		= (!empty($data['solicitante']) ? $data['solicitante'] : '');
		$creadopor			= (!empty($data['creadopor']) ? $data['creadopor'] : $_SESSION['correousuario']);
		$asignadoa 			= (!empty($data['asignadoa']) ? $data['asignadoa'] : ''); 
		$notificar 			= (!empty($data['notificar']) ? $data['notificar'] : '');  
		$fecharesolucion 	= (!empty($data['fecharesolucion']) ? $data['fecharesolucion'] : '');
		$fechacierre 		= (!empty($data['fechacierre']) ? $data['fechacierre'] : ''); 
		$fechacreacion		= (!empty($data['fechacreacion']) ? $data['fechacreacion'] : date("Y-m-d"));
		$fechaentrada		= (!empty($data['fechaentrada']) ? $data['fechaentrada'] : '');
		$diagnostico		= (!empty($data['diagnostico']) ? $data['diagnostico'] : 'sinasignar'); 
		$resolucion			= (!empty($data['resolucion']) ? $data['resolucion'] : ''); 
		$estadoInc 			= '';
		$asignadoaInc 		= ''; 
		$idusuario 			= $_SESSION['user_id'];  
 
		//Actualizar Estado a Entrada al seleccionar Fecha de Entrada
		if($fechaentrada != ''){ 
			$queryF = $mysqli->query("SELECT fechaentrada as fechaentradaanterior FROM laboratorio WHERE id = $id");
			if($rowFe = $queryF->fetch_assoc()) {
				$fechaentradaanterior = $rowFe['fechaentradaanterior'];
				if($fechaentradaanterior == ''){
					$fechaentrada = "'".$fechaentrada."'";
					$estado 	  = 36; 
				}else{
					$fechaentrada = "'".$fechaentrada."'";
					$estado 	  = $estado; 
				}
			} 
		}else{
			$fechaentrada = 'null';
			$estado 	  = $estado; 
		}
		
		//Actualizar Estado a En Proceso al asignar a técnico
		/* if($asignadoa != 'laboratorio@correo.com' && $estado == 36){
			$estado 	= 37;
			$estadoauto = 1;
		}else{
			$estado 	= $estado;
			$estadoauto = 0;
		} */ 
		if($fechaentrada != '' && $fechaentrada != 'null'){ 
			$arrfechaentrada = explode(' ',str_replace("'","",$fechaentrada));
			$fechaentrada  = "'".$arrfechaentrada[0]."'";
			$horaentrada = "'".$arrfechaentrada[1]."'";			
		}else{
			$fechaentrada = 'null';
			$horaentrada  = 'null';
		}
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
		$fecharesolucion = str_replace("'","",$fecharesolucion);
		$horaresolucion  = str_replace("'","",$horaresolucion);
		$fechacierre 	 = str_replace("'","",$fechacierre);
		$horacierre 	 = str_replace("'","",$horacierre);
		$fechaentrada 	 = str_replace("'","",$fechaentrada);
		$horaentrada 	 = str_replace("'","",$horaentrada);
		//DIAS Y HORAS
		if($prioridad != '0' || $prioridad != ''){
			$queryV  			= " SELECT dias, horas FROM sla WHERE id = '$prioridad' ";
			$resultV 			= $mysqli->query($queryV);
			$rowV 				= $resultV->fetch_assoc();
			$diasP 				= $rowV['dias'];
			$horasP 			= $rowV['horas'];
			$fechavencimiento 	= date('Y-m-d', strtotime($fechaentrada."+ ".$diasP." days"));
			$horavencimiento  	= date('H:i:s', strtotime($horaentrada." + ".$horasP." hours"));
		}
		//ACTUALIZAR
		$queryInc = $mysqli->query("SELECT estado FROM laboratorio WHERE id = '$id'");
		if ($rowInc = $queryInc->fetch_assoc()) {
			$estadoInc = $rowInc['estado'];
		}
		$queryAsig = $mysqli->query("SELECT asignadoa FROM laboratorio WHERE id = '$id'");
		if ($rowAsig = $queryAsig->fetch_assoc()) {
			$asignadoaInc = $rowAsig['asignadoa'];
		} 
		
		$descripcion = str_replace("'","",$descripcion);
		
		$campos = array(
			'Titulo' 				=> $titulo,
			'Descripción' 			=> $descripcion,
			'Empresas' 				=> getValor('descripcion','empresas',$idempresas),
			'Clientes' 				=> getValor('nombre','clientes',$idclientes),
			'Proyectos' 			=> getValor('nombre','proyectos',$idproyectos), 
			'Serie' 				=> $serie,
			'Marca' 				=> $marca,
			'Modelo' 				=> $modelo,
			'Departamentos' 		=> getValor('nombre','departamentos',$iddepartamentos),
			'Asignado a' 			=> getValorEx('nombre','usuarios',$asignadoa,'correo'),
			'Estado' 				=> getValor('nombre','estados',$estado),
			'Prioridad' 			=> getValor('prioridad','sla',$prioridad), 
			'Solicitante' 			=> getValorEx('nombre','usuarios',$solicitante,'correo'), 
			'Fecha de resolución'	=> $fecharesolucion, 
			'Fecha de creación' 	=> $fechacreacion, 
			'Diagnóstico' 			=> $diagnostico,
			'Fecha de entrada'		=> $fechaentrada,
		);
		$queryBit = "SELECT a.titulo as Titulo, a.descripcion as 'Descripción', d.descripcion as Empresas, e.nombre as Clientes, f.nombre as Proyectos, 
										a.serie as Serie, a.marca as Marca, a.modelo as Modelo, k.nombre as Departamentos, n.nombre as 'Asignado a', o.nombre as Estado, p.prioridad as Prioridad,  q.nombre as Solicitante,
										a.fecharesolucion as 'Fecha de resolución', a.fechacreacion as 'Fecha de creación', a.diagnostico as 'Diagnóstico', a.fechaentrada as 'Fecha de entrada' 
										FROM laboratorio a 
										LEFT JOIN empresas d ON a.idempresas = d.id
										LEFT JOIN clientes e ON a.idclientes = e.id
										LEFT JOIN proyectos f ON a.idproyectos = f.id 
										LEFT JOIN departamentos k ON a.iddepartamentos = k.id 
										LEFT JOIN usuarios n ON a.asignadoa = n.correo
										LEFT JOIN estados o ON a.estado = o.id
										LEFT JOIN sla p ON a.idprioridad = p.id
										LEFT JOIN usuarios q ON a.solicitante = q.correo
										WHERE a.id = '".$id."' ";
										
		$valoresold = getRegistroSQL($queryBit);							  
		
		$query = " UPDATE laboratorio SET ";
		if(isset($data['titulo'])){
			$query .= " titulo = '$titulo' ";
		}
		if(isset($data['descripcion'])){
			$query .= ", descripcion = '$descripcion' ";
		}		
		if(isset($data['idempresas'])){
			$query .= ", idempresas = '$idempresas' ";
		}		
		if(isset($data['idclientes'])){
			$query .= ", idclientes = '$idclientes' ";
		}
		if(isset($data['idproyectos'])){
			$query .= ", idproyectos = '$idproyectos' ";
		}
		if(isset($data['iddepartamentos'])){
			$query .= ", iddepartamentos = '$iddepartamentos' ";
		} 
		if(isset($data['serie'])){
			$query .= ", serie = '$serie' ";
		}
		if(isset($data['marca'])){
			$query .= ", marca = '$marca' ";
		}
		if(isset($data['modelo'])){
			$query .= ", modelo = '$modelo' ";
		}
		if(isset($data['idestados'])){
			$query .= ", estado = '$estado' ";
		} 
		if(isset($data['idprioridades'])){
			$query .= ", idprioridad = '$prioridad' ";
		} 
		if(isset($data['solicitante'])){
			$query .= ", solicitante = '$solicitante' ";
		}		
		if(isset($data['asignadoa'])){
			$query .= ", asignadoa = '$asignadoa' ";
		}
		if(isset($data['departamento'])){
			$query .= ", departamento = '$departamento' ";
		}		
		if(isset($data['notificar'])){
			$query .= ", notificar = '$notificar' ";
		}
		if(isset($data['resolucion'])){
			$query .= ", resolucion = '$resolucion' ";
		} 
		if(isset($data['fecharesolucion']) && $data['fecharesolucion'] != null){
			$query .= ", fecharesolucion = '$fecharesolucion' ";
		}
		if($horaresolucion != null && $horaresolucion != 'null'){
			$query .= ", horaresolucion = '$horaresolucion' ";
		}
		if(isset($data['fechacierre']) && $data['fechacierre'] != null){
			$query .= ", fechacierre = '$fechacierre' ";
		} 
		if(isset($data['fechacreacion'])){
			$query .= ", fechacreacion = '$fechacreacion' ";
		} 
		if(isset($data['diagnostico'])){
			$query .= ", diagnostico = '$diagnostico' ";
		}  
		if(isset($data['fechaentrada']) && $data['fechaentrada'] != null){
			$query .= ", fechaentrada = '$fechaentrada' ";
		}
		if($horaentrada != null && $horaentrada != 'null'){
			$query .= ", horaentrada = '$horaentrada' ";
		}
		if($estado < $estadoInc ){
			$query .= " , estadoant = '1' ";
		}/*
		if($estadoInc != 16 && $estado == '16' ){
			$query .= " , resueltopor = '".$_SESSION['correousuario']."' ";
		}	*//*	
		if ($fueraservicio != ''&& $fueraservicio == 1) {
			$query .= " , fueraservicio = '$fueraservicio' ";	
			$query .= " , fechadesdefueraservicio = current_timestamp() ";
		}
		if ($estado!='' && $estado == 16) {
			$query .= " , fechafinfueraservicio = current_timestamp ";			
		}*/
		$query .= " WHERE id = $id ";
		$query = str_replace('SET ,','SET ',$query); 
		
		if($mysqli->query($query)){			
			 //Verificar si fecharesolucion es vacía
			/* if($estado == 16 && (isset($data['fecharesolucion']) && $data['fecharesolucion'] != null) && ($horaresolucion != null && $horaresolucion != 'null')){
				//Verifico si el incidente está fusionado con otros incidentes
				$queryF = " SELECT GROUP_CONCAT(id) AS fusionados FROM laboratorio WHERE fusionado = '$id' ";
				$resultF = $mysqli->query($queryF); 
				if($rowF = $resultF->fetch_assoc()){
					$fusionados = $rowF['fusionados'];
					if($fusionados != "" && $fusionados != null){
						//Actualizo fecha de resolución de incidentes fusionados
						$queryR = " UPDATE laboratorio SET";
						if((isset($data['fecharesolucion']) && $data['fecharesolucion'] != null)){
							$queryR.= " fecharesolucion = '$fecharesolucion' ";
						}
						if(($horaresolucion != null && $horaresolucion != 'null')){
							$queryR.= ", horaresolucion = '$horaresolucion' ";
						}
						$queryR .= " WHERE id IN ($fusionados) ";  
						$resultR = $mysqli->query($queryR);
					} 
				} 
			} */
			
			//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
			if($estadoInc != $estado){
				//CREAR REGISTRO EN ESTADOS INCIDENTES
				//Si existe el registro, la fechacambio será la misma de laboratorioestados																
				$queryE = " SELECT estadonuevo, fechacambio, fechadesde, fechahasta FROM laboratorioestados WHERE idincidentes = '$id' ORDER BY id DESC LIMIT 1 ";
				$resultE = $mysqli->query($queryE);
				if($resultE->num_rows >0){
					$rowE = $resultE->fetch_assoc();
					$estadoanterior = $estadoInc;
					$fechacambio = $rowE['fechacambio'];
					$fechahasta  = $rowE['fechahasta'];					
				}else{
					//Si no existe el registro, la fechacambio será la fecha de creación del registro																	
					$estadoanterior = $estadoInc;
					$qfechac = " SELECT fechacreacion FROM laboratorio WHERE id = $id ";
					$rfechac = $mysqli->query($qfechac);
					$regf = $rfechac->fetch_assoc();
					$fechacambio = $regf['fechacreacion'];
					$fechahasta = $regf['fechacreacion'];					  
				}
				
				$fechahoy = date('Y-m-d');
				$date1 = new DateTime($fechahoy);
				$date2 = new DateTime($fechacambio);
				$diff = $date1->diff($date2);
				 
				$queryE = " INSERT INTO laboratorioestados VALUES(null, $id, '$estadoanterior', '$estado', $idusuario, now(), now(), '$fechahasta', now(), $diff->days) ";
				$mysqli->query($queryE);
	 
				//Se envía el correo, solo si el caso fue resuelto
				if($estado == 16){
					notificarCEstado($id,$notificar,'actualizado',$estadoInc,$estado); 
				} 
				//*******************************************//
				//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
				//*******************************************// 
				 
				//ESTADO ANTERIOR 
				if($estadoInc != ''){
					$consultaEO = $mysqli->query("SELECT nombre FROM estados WHERE id = '".$estadoInc."' ");
					$registroEO = $consultaEO->fetch_assoc();
					$estadoant = $registroEO['nombre'];
				}else{
					$estadoant = '';
				}
				//ESTADO NUEVO
				if($estado != ''){
					$consultaEN = $mysqli->query("SELECT nombre FROM estados WHERE id = '".$estado."' ");
					$registroEN = $consultaEN->fetch_assoc();
					$estadonue = $registroEN['nombre'];
				}else{
					$estadonue = '';
				}
				//solicitante
				if($solicitante != ''){
					$qSol = $mysqli->query("SELECT usuario FROM usuarios WHERE correo = '".$solicitante."' ");
					$regSol = $qSol->fetch_assoc();
					$usuariosolicitante = $regSol['usuario'];
				} 
				
				//Usuarios de soporte
				$idusuarios["icarvajal"] = "0";
				$idusuarios["frios"] = "0";
				$idusuarios["aanderson"] = "0";
				$idusuarios["admin"] = "0";
				
				//Usuario asociado al laboratorio
				if($usuariosolicitante != "") $idusuarios[$usuariosolicitante] = "0";
				
				$usuarios = json_encode($idusuarios);
				
				$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,descripcion,fecha,hora,usuarios) VALUES (".$idproyectos.",".$id.",'Cambio de estado laboratorio',' ".$estadoant." a ".$estadonue."','". date('Y-m-d') ."','". date('H:i:s') ."','".$usuarios."')";  
				$rsql = $mysqli->query($sql);
				
				//*******************************************//
				//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
				//*******************************************//			  
			}  
			//BITACORA
			actualizarRegistro('Laboratorio','Laboratorio',$id,$valoresold,$campos,$query);
			 				
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
				$query .= "UPDATE laboratorio SET ";
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
						} elseif($c == 'prioridadmas'){
							//FECHA CREACION
							/*$queryFC  			= " SELECT fechacreacion FROM laboratorio WHERE id = '$id' ";
							$resultFC 			= $mysqli->query($queryFC);
							$rowFC 				= $resultFC->fetch_assoc();
							$fechacreacion		= $rowFC['fechacreacion'];*/
							//SLA
							//$queryV  			= " SELECT dias, horas FROM sla WHERE id = '$v' ";
							//$resultV 			= $mysqli->query($queryV);
							//$rowV 				= $resultV->fetch_assoc();
							//$diasP 				= $rowV['dias'];
							//$horasP 			= $rowV['horas'];
							////$fechavencimiento 	= date('Y-m-d', strtotime($fechacreacion."+ ".$diasP." days"));
							///$horavencimiento  	= date('H:i:s', strtotime($horacreacion." + ".$horasP." hours"));
							$query .= " idprioridad = '$v' ";
						}elseif($c == 'seriemas'){
							$query .= " serie = '$v' ";
						}elseif($c == 'asignadoamas'){
							$query .= " asignadoa = '$v' ";
						}elseif($c == 'estadomas'){
							$query .= " estado = '$v' ";
						}elseif($c == 'diagnosticomas'){
							$query .= " diagnostico = '$v' ";
						}						
						$i++;
					}
				}
				if($i >= 1){
					foreach($idarray as $id){
						$query2 = '';
						$query2 = $query." WHERE id = '$id' ";
						//debug('masivo:'.$query2);
						if($id != ''){							
							if($mysqli->query($query2)){
								bitacora($_SESSION['usuario'], "Laboratorio", 'El registro #'.$id.' ha sido Editado exitosamente', $id, $query2);
								echo true;
							}else{
								echo false;
							}
						}
					}
				}else{
					//debug('vacio');
					echo false;
				}
			}
		}
	}

	//ENVIAR CORREO AL SOLICITANTE DEL INCIDENTE Y SOPORTE
	function nuevoincidente($usuario, $titulo, $descripcion, $incidente, $fecha, $hora, $solicitante,$notificar){
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
		}
		//USUARIOS EN C.C.
		if($notificar != '[]' && $notificar != ''){ 
			$notificar = json_decode($notificar);
			if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
				$correo [] = "$notificar";
			}else{
				foreach($notificar as $notif){
					if($notif != ''){
						$correo [] = $notif;
					}
				}
			}
		}
		
		//ING-TEC
		$correo [] = 'uzziel.mague@maxialatam.com';
		$correo [] = 'miguel.batista@maxialatam.com';
		//$correo [] = ''; //Correo de laboratorio
		
		//Asunto 
		$asunto = "Registro #$incidente ha sido Creado"; 
		
		//Cuerpo
		$fecha = implode('/',array_reverse(explode('-', $fecha)));
		$cuerpo = '';		
		$cuerpo .= "<div style='width: 100%; text-align: right;'><b>Fecha:</b> ".$fecha."&nbsp;&nbsp;&nbsp;</div>";
		$cuerpo .= "<br><b>".$titulo."</b>";
		$cuerpo .= "<p style='width: 100%;'>Buen día<br><p>";
		$cuerpo .= "<br><br>"; 
			
			//Correo
			//enviarMensajeIncidente($asunto,$cuerpo,$correo,'','');
	}

	//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
	function notificarCEstado($incidente,$notificar,$accion,$estadoold,$estadonew){
		global $mysqli;
		
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto,  
					a.serie, a.marca, a.modelo, e.nombre AS estado,  
					h.prioridad, IFNULL(j.nombre, a.solicitante) AS solicitante, 
					CASE  
						WHEN j.estado = 'Activo' 
							THEN IFNULL(j.correo, a.solicitante) 
						WHEN j.estado = 'Inactivo' 
							THEN '' 
						END 
						AS correosolicitante, 
					a.asignadoa, 
					a.fechacreacion, IF(a.fecharesolucion is not null OR LENGTH(ltrim(rTrim(a.fecharesolucion))) > 0,CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,
					a.fechacierre, a.notificar, a.idclientes, a.idproyectos, a.creadopor
					FROM laboratorio a
					LEFT JOIN proyectos b ON a.idproyectos = b.id  
					LEFT JOIN estados e ON a.estado = e.id  
					LEFT JOIN sla h ON a.idprioridad = h.id 
					LEFT JOIN usuarios j ON a.solicitante = j.correo 
					LEFT JOIN usuarios k ON a.creadopor = k.correo 								
					WHERE a.id = $incidente GROUP BY a.id ";					
		//debug($query);	
		$result = $mysqli->query($query);
		$row 	= $result->fetch_assoc();
		$idclientes = $row['idclientes'];
		$idproyectos = $row['idproyectos'];
		//1 para quien quien creo el incidentes (Creado por)
		//$correo [] = $row['creadopor']; 
		 
		//SOLICITANTE
		//Excluir usuarios inactivos campo Solicitante
		if($row['correosolicitante'] != ""){
			$correo [] = $row['correosolicitante'];
		} 
		 
		//USUARIOS EN C.C.	
		/* if($notificar != '[]' && $notificar != ''){
			$notificar = json_decode($notificar);
			if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
				$correo [] = "$notificar";
			}else{
				foreach($notificar as $notif){
					if($notif != ''){
						$correo [] = $notif;
					}
				}
			}
		} */

		//ING-TEC
		//$correo [] = 'uzziel.mague@maxialatam.com';
		//$correo [] = 'miguel.batista@maxialatam.com';
		//$correo [] = ''; //Correo de laboratorio
		//USUARIO O GRUPO DE USUARIOS ASIGNADOS
		$asignadoaN	= '';
		/* if($row['asignadoa'] != ''){  
			$query2 = " SELECT nombre FROM usuarios WHERE ";
			if (filter_var($row['asignadoa'], FILTER_VALIDATE_EMAIL)) {
				$query2 .= "correo = '".$row['asignadoa']."'";
			}else{
				$query2 .= "correo IN ('".$row['asignadoa']."') ";
			}
			//debug('error:'.$query2);
			$consulta = $mysqli->query($query2);
			while($rec = $consulta->fetch_assoc()){
				$asignadoaN .= $rec['nombre']." , ";
			}
		} */
		//else{
			if($accion == 'creado'){
				$asunto = "Registro #$incidente ha sido Creado";
			}else{ //actualizado
				if ($estadoold != $estadonew && $estadonew == 13) {
					$asunto = "Registro #$incidente ha sido Asignado";			
				} elseif ($estadoold != $estadonew && $estadonew == 16) {
					$asunto = "Registro #$incidente ha sido Resuelto";	 
				}
				else {
					$asunto = "Registro #$incidente ha sido Actualizado";			
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
		if($estadoold != ''){
			$consultaEO = $mysqli->query("SELECT nombre FROM estados WHERE id = '$estadoold' ");
			$registroEO = $consultaEO->fetch_assoc();
			$estadoant = $registroEO['nombre'];
		}else{
			$estadoant = '';
		}
		
		//ESTADO NUEVO
		if($estadonew != ''){
			$consultaEN = $mysqli->query("SELECT nombre FROM estados WHERE id = '$estadonew' ");
			$registroEN = $consultaEN->fetch_assoc();
			$estadonue = $registroEN['nombre'];
		}else{
			$estadonue = '';
		}
		//DATOS
		$fechacreacion 	= $row['fechacreacion'];
		$titulo			= $row['titulo'];
		$descripcion	= $row['descripcion'];
		$solicitante	= $row['solicitante'];
		//$creadopor		= $row['creadopor'];
		$departamento	= 'Laboratorio';
		$prioridad		= $row['prioridad'];
		//$sitio 			= $row['unidadejecutora'];
	//	$resolucion 	= $row['resolucion'];
		$nasignadoa 	= $asignadoaN;
		//MENSAJE
		if($accion == 'creado'){
			$mensaje = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha creado el registro #".$incidente."</b></p>";
		}else{ //actualizado
			$mensaje = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha actualizado el registro #".$incidente."</b></p>";		
		}		
		
		if($estadonew == 13){
			$mensaje .= "<p style='padding-left: 30px;width:100%;'>El registro ha sido asignado a: ".$nasignadoa."</p>";
		}elseif($estadoant !='' && $estadonue !=''){
			$mensaje .= "<p style='padding-left: 30px;width:100%;'>El Estado cambió de ".$estadoant." a ".$estadonue."</p>";
		}
		//<p style='font-size: 18px;width:100%;'>".$creadopor." ha creado este registro el ".$fechacreacion."</p><br>
		$mensaje .= "<p style='width:100%;'>
						<a href='http://toolkit.maxialatam.com/soporte/laboratorio.php?id=".$incidente."' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Registro</a></p>
						<br>
						<br>
						<p style='width:100%;'>".$titulo."</p>
						<br>
						<p style='width:100%;'>".$descripcion."</p> 
						<br>
						<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin: auto;padding: 10px;width:100%;'>Atributos</p>
						<table style='width: 50%;'>
							<tr>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Solicitante del servicio</div>".$solicitante."</td>
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
			
			/*if($estadonew == 39 ){
				//GENERAR FECHA DE CIERRE 
				$query = "  UPDATE laboratorio SET fechacierre = DATE_ADD(fecharesolucion, INTERVAL 3 DAY), horacierre = horaresolucion, 
							estado = 39 WHERE id = '".$incidente."' ";
				$mysqli->query($query);
				//$mensaje .= "<br><br><p style='width:100%;'><b>Resolución: </b>".$resolucion."</p>";	
			}*/
			
			$mensaje .= "</div>"; 
			$mensaje .= "<p style='color: #eeeeee;font-size: 10px;padding: 0;margin: 0;'>".json_encode($correo)."</p>";
		/* if($_SESSION['nivel'] == 4){
			$num 	= $_SESSION['user_id'];
			$from 	= '../laboratoriotemp/'.$num;
			$adjuntos = array();
			//Abro el directorio que voy a leer
			$dir = opendir($from);
			//Recorro el directorio para leer los archivos que tiene
			while(($fileE = readdir($dir)) !== false){
				//Leo todos los archivos excepto . y ..
				if ($fileE != "." && $fileE != ".." && $fileE != ".quarantine" && $fileE != ".tmb" && $fileE != "comentarios"){ 
					$archivo = '../laboratoriotemp/'.$num.'/'.$fileE;
					$adjuntos[] = $archivo;
				}				
			}
		}else{
			$adjuntos = '';
		}  */
		//debugL("notificarCEstadoLABORATORIO-CORREO:".json_encode($correo),"notificarCEstadoLABORATORIO");
		
		if(!empty($correo)){
			enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
		} 
	}
	
	//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
	function notificarCAsignadoa($incidente,$notificar,$accion,$asignadoaInc,$asignadoa){
		global $mysqli;
		
		$query  = " SELECT a.id, a.titulo, a.descripcion,IFNULL(i.nombre, a.creadopor) AS creadopor, a.asignadoa, 
					a.fechacreacion, a.idclientes, a.idproyectos
					FROM laboratorio a 
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					WHERE a.id = $incidente GROUP BY a.id ";
					
		$result = $mysqli->query($query);
		$row 	= $result->fetch_assoc();
		$idclientes = $row['idclientes'];
		$idproyectos = $row['idproyectos'];		
		//1 para quien quien creo el incidentes (Creado por)
		$correo [] = $row['correocreadopor'];
		
		//2 para quien se le asigno el incidente (Asignado a)	
		
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
				$query2 .= "correo IN ('".$row['asignadoa']."') ";
			}
			//debug($query2);
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
		
		//ASIGNADOA ANTERIOR
		$consultaAO = $mysqli->query("SELECT nombre FROM usuarios WHERE correo = '$asignadoaInc' ");
		$registroAO = $consultaAO->fetch_assoc();
		$asignadoaant = $registroAO['nombre'];
		
		//ASIGNADOA NUEVO
		$consultaAN = $mysqli->query("SELECT nombre FROM usuarios WHERE correo = '$asignadoa' ");
		$registroAN = $consultaAN->fetch_assoc();
		$asignadoanue = $registroAN['nombre'];
		//debug('anterior:'.$asignadoaant.'-'.);
		//DATOS
		$fechacreacion 	= $row['fechacreacion'];
		$titulo			= $row['titulo'];
		$descripcion	= $row['descripcion']; 
		$creadopor		= $row['creadopor']; 
		$nasignadoa 	= $asignadoaN;
		
		$asunto = "Registro #$incidente ha sido Actualizado";
		
		//MENSAJE 
		$mensaje = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'>El registro #".$incidente." ha sido modificado de Asignado: ".$asignadoaant." a: ".$asignadoanue."</p>";
		 
		$mensaje .= "<p style='width:100%;'>
						<a href='http://toolkit.maxialatam.com/soporte/laboratorio.php?id=".$incidente."' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Incidente</a></p>
						<br><br>
						<p style='font-size: 18px;width:100%;'>".$creadopor." ha creado este incidente el ".$fechacreacion."</p>
						<br>
						<p style='width:100%;'>".$titulo."</p>
						<br>
						<p style='width:100%;'>".$descripcion."</p>
						<br> 
						";  
		$mensaje .= "</div>";

		//USUARIOS DE SOPORTE
		/*$correo [] = 'ana.porras@maxialatam.com';
		$correo [] = 'isai.carvajal@maxialatam.com';
		$correo [] = 'fernando.rios@maxialatam.com';*/
		  
		enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
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
		
		enviarMensajeIncidente($asunto,$mensajeHtml,$correo,'','');
	}
	
	//ENVIO DE CORREO SI HAY INCIDENTES VENCIDOS
	function verificarVencidos(){
		global $mysqli;

		$query  = " SELECT a.id, a.titulo,
					CONCAT_WS ('',b.correo, e.correo, IF(RIGHT(a.asignadoa,2) = '-G' OR RIGHT(a.asignadoa,2) = '-U', '', a.asignadoa)) AS asignadoa,
					CONCAT(a.fechacreacion,' ', horacreacion), a.fechavencimiento, f.nombre
					FROM laboratorio a
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
						enviarMensajeIncidente($asunto,$mensajeHtml,$correo,'','');
					}
				}
			}
		}
	}

	function enviarMensajeIncidente($asunto,$mensaje,$correos,$adjuntos,$tipo) {
		global $mysqli, $mail;
		$correo = array_unique($correos);
		$cuerpo = "";
		$cuerpo .= "<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; '>";
		$cuerpo .= "<img src='http://toolkit.maxialatam.com/repositorio-tema/assets/img/logosym-header.png' style='width: auto; float: left;'>";
		$cuerpo .= "<p style='margin: auto; font-weight:bold; width: 100%; text-align: center;'>Maxia Toolkit<br>";
		$cuerpo .= "Gestión de Laboratorio<br>";
		$cuerpo .= "</div>";
		$cuerpo .= $mensaje;
		$cuerpo .= "<div style='background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;'>";
		$cuerpo .= "© ".date('Y')." Maxia Latam";
		$cuerpo .= "</div>"; 
		$mail->clearAddresses();
		//$mail->addAddress('lisbethagapornis@gmail.com');
		//$mail->addAddress('christopher.carnevale.p@gmail.com');
		foreach($correo as $destino){
		    //debug('dest:'.json_encode($destino));
			$mail->addAddress($destino);	 // EVITAR ENVÍO DE CORREO A CLIENTES (DESACTIVADO)
		}
		//$mail->addAddress("isai.carvajal@maxialatam.com");
		//$mail->addAddress("fernando.rios@maxialatam.com");
		//$mail->addAddress("axel.anderson@maxialatam.com");
		
		$mail->FromName = "Maxia Toolkit - SYM";
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = $asunto;
		//$mail->MsgHTML($cuerpo);
		$mail->Body = $cuerpo;
		$mail->AltBody = "Maxia Toolkit - SYM: $asunto";
		if($adjuntos != ''){
			foreach($adjuntos as $adjunto){
				//debug('uadjunto: '.$adjunto);
				$mail->AddAttachment($adjunto);
			}
		}
		if(!$mail->send()) {
			echo 'Mensaje no pudo ser enviado. ';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			//echo 'Ha sido enviado el correo Exitosamente';
			if (is_array($adjuntos) || is_object($adjuntos)){
				foreach($adjuntos as $adjunto){
					if(is_file($adjunto))
					unlink($adjunto); //elimino el fichero
				}
			} 
			//echo true;
		} 
	}
	
	function fusionarIncidentes()
	{
		global $mysqli;
		$fusioninc 		= $_REQUEST['fusioninc'];
		$idincidentes 	= json_decode($_REQUEST['idincidentes']);

		if($fusioninc != ''){
			foreach($idincidentes as $incidente){
				$query = "UPDATE laboratorio SET estado = 39, fusionado = ".$fusioninc." 
						  WHERE id = '".$incidente."'";
				//debug($query);
				if($mysqli->query($query)){
					$tieneEvidencias   = '';
					$rutaE 		= '../laboratorio/'.$incidente;
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
						//Copiar evidencias de incidentes fusionados a incidente a prevalecer
						$path = '../laboratorio/'.$fusioninc.'/fusionados/';
						if (!file_exists($path))
							mkdir($path, 0777);
						$path2 = '../laboratorio/'.$fusioninc.'/fusionados/'.$incidente.'/';
						$target_path2 = utf8_decode($path2);
						if (!file_exists($target_path2))
						mkdir($target_path2, 0777); 
			
						$from = '../laboratorio/'.$incidente.'/';
						$to   = '../laboratorio/'.$fusioninc.'/fusionados/'.$incidente.'/';
						
						//Abro el directorio que voy a leer
						$dir = opendir($from);

						//Recorro el directorio para leer los archivos que tiene
						while(($file = readdir($dir)) !== false){
							//Leo todos los archivos excepto . y ..
							if(strpos($file, '.') !== 0){
								//Copio el archivo manteniendo el mismo nombre en la nueva carpeta
								copy($from.'/'.$file, $to.'/'.$file);
							}
						}
					} 
					
					bitacora($_SESSION['usuario'], "Laboratorio", 'El registro #'.$fusioninc.' se fusiono con: '.$incidente, $fusioninc, $query);
					bitacora($_SESSION['usuario'], "Laboratorio", 'El registro #'.$incidente.' fue fusionado con: '.$fusioninc, $incidente, $query);
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
			
			$query = "UPDATE laboratorio SET estado = 35, fusionado = '' WHERE id = '$id' ";
			if($mysqli->query($query)){
				//ELIMINAR DIRECTORIO EVIDENCIAS
				$dir = '../laboratorio/'.$fusionado.'/fusionados/'.$incidente.'/';     
				if (is_dir($dir)) {
					$handle = opendir($dir);
					while ($file = readdir($handle)) {
						if (is_file($dir.$file)) {
							unlink($dir.$file);
						}
					}
					rmdir('../laboratorio/'.$fusionado.'/fusionados/'.$incidente.'/');
					 
					$carpeta = '../laboratorio/'.$fusionado.'/fusionados/'; 
					$files = array ();
					if ( $handle = opendir ( $carpeta ) ) {
						while ( false !== ( $file = readdir ( $handle ) ) ) {
							if ( $file != "." && $file != ".." ) {
								$files [] = $file;
							}
						}
						closedir ( $handle );
					}
					$total = count ( $files );
					if($total == 0){
						rmdir($carpeta);
					}
				} 
				
				bitacora($_SESSION['usuario'], "Laboratorio", 'El Incidente #'.$incidente.' se Revirtió la Fusión con: '.$fusionado, $id, $query);
				bitacora($_SESSION['usuario'], "Laboratorio", 'El Incidente #'.$fusionado.' se Revirtió la Fusión con: '.$incidente, $id, $query);
				echo true;
			}else{
				echo false;
			}
		}else{
			echo false;
		}
	}
	
	function fusionados(){
		global $mysqli;
		$nivel = $_SESSION['nivel'];
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado = array();
		
		$buscarF = " SELECT fusionado FROM laboratorio WHERE id = '$id' ";
		$resultF = $mysqli->query($buscarF);
		$rowF = $resultF->fetch_assoc();
		
		$fusionado = isset($rowF['fusionado']) ? $rowF['fusionado'] : 0;
		
		$query  = " ( SELECT id, titulo, descripcion, fechacreacion
					FROM laboratorio
					WHERE fusionado = '$id' 
					ORDER BY id DESC )
					UNION
					( SELECT id, titulo, descripcion, fechacreacion
					FROM laboratorio 
					WHERE id = '$fusionado' ) ";
		
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			$resultado[] = array(				
				'id' 			=> $row['id'],
				'titulo' 		=> $row['titulo'],
				'descripcion' 	=> $row['descripcion'],
				'fechacreacion'	=> $row['fechacreacion'] 
			);
		}
		$response = array( 
		  "data" => $resultado
		);
		echo json_encode($response);
	}
	
	function estadosbit(){
		global $mysqli;
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado = array();
		
		$query  = " SELECT b.nombre as estadoant, c.nombre as estadoact, a.fechacambio, a.dias, fechadesde, fechahasta, 
					b.id as idestadosant, c.id as idestadosact
					FROM laboratorioestados a 
					LEFT JOIN estados b ON a.estadoanterior = b.id
					LEFT JOIN estados c ON a.estadonuevo = c.id
					WHERE a.idincidentes = $id ORDER BY a.id DESC ";		
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			
			$idestadosant = $row['idestadosant'];
			$idestadosact = $row['idestadosact']; 
			$fechadesde   = $row['fechadesde']; 
			$fechahasta   = $row['fechahasta'];
			$estadoant    = $row['estadoant'];
			$estadoact    = $row['estadoact'];
			
			$date1 = new DateTime($fechadesde);
			$date2 = new DateTime($fechahasta);
			$diferencia = $date1->diff($date2);
			$dias = $diferencia->format('%d');
			$horas = $diferencia->format('%H');
			$minutos = $diferencia->format('%I'); 
			$estadonuevo = 12;
			$estadoenesperaderespuestos = 15;
			$estadoentregado = 40;
			
			if($idestadosant == $estadonuevo || $idestadosant == $estadoenesperaderespuestos || $idestadosant == $estadoentregado ||  $idestadosact == $estadonuevo || $idestadosact == $estadoenesperaderespuestos || $idestadosact == $estadoentregado ){
				$dias = "-";
				$horas = "-";
			}else{
				$dias = $dias;
				$horas = $horas.':'.$minutos;
			} 
			$resultado[] = array(				
				'estadoant' => $row['estadoant'],
				'estadoact' => $row['estadoact'],
				'fecha'		=> $row['fechacambio'],
				'dias'		=> $dias,
				'horas'		=> $horas
			); 
		}
		$response = array( 
		  "data" => $resultado
		);
		echo json_encode($response);
	}
	
	function historial(){
		global $mysqli;
		$nivel = $_SESSION['nivel'];
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado = array();
		
		$query  = "SELECT a.id, a.usuario, b.nombre, a.fecha, a.accion
					FROM bitacora a 
					INNER JOIN usuarios b ON a.usuario = b.usuario
					WHERE a.modulo = 'Laboratorio' AND a.identificador = $id
					ORDER BY a.id DESC ";
		
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			$resultado[] = array(				
				'id' 			=> $row['id'],
				'usuario' 	=> $row['usuario'],
				'nombre' 	=> $row['nombre'],
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
		->setTitle("Reporte de Laboratorio")
		->setSubject("Reporte de Laboratorio")
		->setDescription("Reporte de Laboratorio")
		->setKeywords("Reporte de Laboratorio")
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Reporte de Laboratorio');
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
					f.nombre AS categoria, g.nombre AS subcategoria, c.unidad AS sitio, h.prioridad, 
					a.origen, a.creadopor, a.solicitante, a.asignadoa, a.departamento, a.resueltopor,
					a.resolucion, a.satisfaccion, a.comentariosatisfaccion, 
					ifnull(a.fechacreacion, '') AS fechacreacion,
					ifnull(a.fecharesolucion, '') as fecharesolucion,
					ifnull(a.fechacierre, '') as fechacierre,o.nombre as cliente 
					FROM laboratorio a
					LEFT JOIN proyectos b ON a.idproyectos = b.id 
					LEFT JOIN estados e ON a.estado = e.id 
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo 
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					LEFT JOIN cuatrimestres cu ON a.fecharesolucion BETWEEN cu.fechainicio AND cu.fechafin
					";
		
		if($nivel != 1 && $nivel != 2){
			//$query .= " LEFT JOIN usuarios q ON find_in_set(c.codigo, q.sitio) AND q.usuario = '$usuario' ";
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
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.unidadejecutora IN ('".$sitio."') ) ";
			}else{
				//$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
				if($_SESSION['iddepartamentos'] != ''){
					$iddepartamentosSES = $_SESSION['iddepartamentos'];
					$query  .= "AND a.iddepartamentos IN ('".$iddepartamentosSES."')  ";
				}
			}			
		}elseif($nivel == 6){
			$query  .= " AND a.asignadoa = 'soportemaxia@zertifika.com' AND FIND_IN_SET(a.iddepartamentos,(SELECT GROUP_CONCAT( DISTINCT ee.id SEPARATOR  ',' )			
															FROM usuarios a
															LEFT JOIN departamentos ee ON FIND_IN_SET(ee.id, a.iddepartamentos) AND ee.tipo = 'grupo'
															WHERE a.usuario = '".$_SESSION['usuario']."')) 
						";
		}
		
		//DATOS 
		$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Laboratorio' AND usuario = '".$_SESSION['usuario']."'";
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
			if(!empty($data->modalidadf)){
				$modalidadf = json_encode($data->modalidadf);
				$where2 .= " AND m.modalidad IN ($modalidadf)";
			}
			if(!empty($data->marcaf)){
				$marcaf = json_encode($data->marcaf);
				$where2 .= " AND m.marca IN ($marcaf)"; 
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
			if(!empty($data->unidadejecutoraf)){
				$unidadejecutoraf = json_encode($data->unidadejecutoraf);
				 if($unidadejecutoraf !== '[""]'){ 
					$where2 .= " AND a.unidadejecutora IN ($unidadejecutoraf)";
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
		$objPHPExcel->getActiveSheet()->setTitle('Laboratorio');

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
		$query = "UPDATE laboratorio SET comentariovisto='1' WHERE id = '$id'";
		$mysqli->query($query);
	}
	
	function filtroGrid(){
		global $mysqli;
		$_SESSION['filtrogrid'] = '0';
		$usufiltroexiste = 0;
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Laboratorio' AND usuario =".$_SESSION['user_id'];
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
			//$filtro = str_replace('c.codigo','unidadejecutora',$filtro);
			$filtro = str_replace('a.activo','activo',$filtro);
			$filtro = str_replace('a.marca','marca',$filtro);
			$filtro = str_replace('a.modelo','modelo',$filtro);
			//$filtro = str_replace('a.idcategoria','idcategoria',$filtro);
			//$filtro = str_replace('a.idsubcategoria','idsubcategoria',$filtro);
			$filtro = str_replace('a.idprioridad','idprioridad',$filtro);
			//$filtro = str_replace('a.origen','origen',$filtro);
			//$filtro = str_replace('a.creadopor','creadopor',$filtro);
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
		
		$query = "DELETE FROM usuariosfiltros WHERE modulo = 'Laboratorio' AND usuario = '$usuario' ";
		if($mysqli->query($query))
			echo true;		
	}
	
	function guardarfiltros() {
		global $mysqli;
		$data = $_REQUEST['data'];
		$usuario = $_SESSION['usuario'];
		$query  = " SELECT * FROM usuariosfiltros WHERE modulo = 'Laboratorio' AND usuario = '$usuario' ";

		$result = $mysqli->query($query);
		$count = $result->num_rows;
		
		if( $count > 0 ) 
			$query = "UPDATE usuariosfiltros SET filtrosmasivos = '$data' WHERE modulo = 'Laboratorio' AND usuario = '$usuario'";
		else
			$query = "INSERT INTO usuariosfiltros VALUES (null, '$usuario', 'Laboratorio', '', '$data')";
		if($mysqli->query($query))
			echo true;		
	}
	
	function abrirfiltros() {
		global $mysqli;
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Laboratorio' AND usuario = '".$_SESSION['usuario']."'";
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
		$query = " SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Laboratorio' AND usuario = '".$_SESSION['usuario']."'";
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
		$visibilidad = "";
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
		
		$usuarios = json_encode($idusuarios);
		
		$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,fecha,hora,usuarios) VALUES (".$idproyectos.",".$incidente.",'Adjunto realizado laboratorio','". date("Y-m-d") ."','". date("H:i:s") ."','".$usuarios."')"; 
		$rsql = $mysqli->query($sql); 
		
		echo true;
		//*******************************************//
		//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
		//*******************************************//																		 
		/* if($idcoment!=""){
			$queryC  = " SELECT visibilidad FROM comentarioslaboratorio WHERE id = $idcoment ";
			$resultC = $mysqli->query($queryC);
			if($rowC = $resultC->fetch_assoc()){
				$visibilidad = $rowC['visibilidad'];
			}
		}
		
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
			$correo [] = 'fernando.rios@maxialatam.com';
		
			$query  = " SELECT a.id, a.titulo, IFNULL(i.correo, a.creadopor) AS creadopor, a.notificar,
						IFNULL(j.correo, a.solicitante) AS solicitante, a.asignadoa
						FROM laboratorio a
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
						$correo [] = "$notificar";				
					}else{
						foreach($notificar as $notificarp){
							$correo [] = $notificarp;
						}
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
								<a href='http://toolkit.maxialatam.com/soporte/laboratorio.php?id=".$incidente."' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Incidente</a></p>
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
				if( $destino != 'mesadeayuda@innovacion.gob.pa' ){
					//$mail->addAddress($destino); -- > EVITAR ENVÍO DE CORREO CLIENTES
				}			   
			}
			$mail->addAddress("isai.carvajal@maxialatam.com");
			$mail->addAddress("fernando.rios@maxialatam.com");
			$mail->addAddress("axel.anderson@maxialatam.com");
			
			$mail->FromName = "Maxia Toolkit - SYM";
			$mail->isHTML(true); // Set email format to HTML
			if($row['solicitante'] == 'mesadeayuda@innovacion.gob.pa' || $row['creadopor'] == 'mesadeayuda@innovacion.gob.pa'){
				$mail->Subject = $row['titulo'];
			}else{
				$mail->Subject = "Incidente #".$incidente." - Nuevo adjunto";
			}
			
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
		}*/	
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
		->setTitle("Reporte de Laboratorio")
		->setSubject("Reporte de Laboratorio")
		->setDescription("Reporte de Laboratorio")
		->setKeywords("Reporte de Laboratorio")
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Reporte de Laboratorio');
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
					a.serie, a.marca, a.modelo, h.prioridad, a.solicitante, a.asignadoa,
					a.resolucion, 
					ifnull(a.fechacreacion, '') AS fechacreacion, 
					ifnull(a.fecharesolucion, '') as fecharesolucion, 
					ifnull(a.fechacierre, '') as fechacierre,  
					cu.periodo, o.nombre as cliente, co.comentario 
					FROM laboratorio a
					LEFT JOIN proyectos b ON a.idproyectos = b.id 
					LEFT JOIN estados e ON a.estado = e.id 
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo 
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					LEFT JOIN cuatrimestres cu ON a.fecharesolucion BETWEEN cu.fechainicio AND cu.fechafin
					LEFT JOIN comentarioslaboratorio co ON a.id = co.idmodulo
					";
		
		if($nivel != 1 && $nivel != 2){
			//$query .= " LEFT JOIN usuarios q ON find_in_set(c.codigo, q.sitio) AND q.usuario = '$usuario' ";
		}
		$query  .= " WHERE 1 ";
		
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
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.unidadejecutora IN ('".$sitio."') ) ";
			}else{
				//$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
				if($_SESSION['iddepartamentos'] != ''){
					$iddepartamentosSES = $_SESSION['iddepartamentos'];
					$query  .= "AND a.iddepartamentos IN ('".$iddepartamentosSES."')  ";
				}
			}			
		}
		
		//DATOS 
		$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Laboratorio' AND usuario = '".$_SESSION['usuario']."'";
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
			if(!empty($data->modalidadf)){
				$modalidadf = json_encode($data->modalidadf);
				$where2 .= " AND m.modalidad IN ($modalidadf)";
			}
			if(!empty($data->marcaf)){
				$marcaf = json_encode($data->marcaf);
				$where2 .= " AND m.marca IN ($marcaf)"; 
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
			if(!empty($data->unidadejecutoraf)){
				$unidadejecutoraf = json_encode($data->unidadejecutoraf);
				 if($unidadejecutoraf !== '[""]'){ 
					$where2 .= " AND a.unidadejecutora IN ($unidadejecutoraf)";
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
		$objPHPExcel->getActiveSheet()->setTitle('Laboratorio');

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