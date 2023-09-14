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
		case "guardarcolumnaocultar":
			  guardarcolumnaocultar();
			  break;
		case "consultarcolumnas":
			  consultarcolumnas();
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
		case  "fusionados":
			  fusionados();
			  break;
		case "importaractividades":
			 importaractividades();
			 break;
		case  "costos":
			  costos();
			  break;
		case  "agregarCosto":
			  agregarCosto();
			  break;
		case  "eliminarcostos":
			  eliminarcostos();
			  break;
		case  "adjuntoscostos":
			  adjuntoscostos();
			  break;
		case  "notificacionAdjunto":
			  notificacionAdjunto();
			  break;	 
		case  "guardarCorrectivoTemp":
			  guardarCorrectivoTemp();
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
		
		//FILTROS MASIVO
		$nivel = $_SESSION['nivel'];
		$where = "";  
		$where2 = array();		
		$data2 = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');		
		$searchGeneral = (!empty($_POST['search']['value']) ? $_POST['search']['value'] : '');		
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : '');
	    $start = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$rowperpage = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		$usuario = $_SESSION['usuario'];
        $vacio = array();
		$columns = (!empty($_REQUEST['columns']) ? $_REQUEST['columns'] : $vacio);
		$data = "";
		
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Preventivos' AND usuario = '".$_SESSION['usuario']."'";
		$result = $mysqli->query($query);
		if($result->num_rows >0){
			$row = $result->fetch_assoc();				
			if (!isset($_REQUEST['data'])) {
				$data = $row['filtrosmasivos'];
			}
		}
		
		if($data != ''){
			$data = json_decode($data);
			$optradio = (isset($data->optradio) ? $data->optradio : '');
// 			if($optradio == 'crea'){
				if(!empty($data->desdef)){
					$desdef = json_encode($data->desdef);
					$where .= " AND a.fechacreacion >= $desdef ";
				}
				if(!empty($data->hastaf)){
					$hastaf = json_encode($data->hastaf);
					$where .= " AND a.fechacreacion <= $hastaf ";
				}
// 			}else{
// 				if(!empty($data->desdefreal)){
// 					$desdefreal = json_encode($data->desdefreal);
// 					$where .= " AND a.fechareal >= $desdefreal ";
// 				}
// 				if(!empty($data->hastafreal)){
// 					$hastafreal = json_encode($data->hastafreal);
// 					$where .= " AND a.fechareal <= $hastafreal ";
		 
// 				}
// 			}
			if(!empty($data->idempresasf)){
				$idempresasf = json_encode($data->idempresasf);
				if($idempresasf != '[""]'){
					$where .= " AND a.idempresas IN ($idempresasf)"; 
				}				
			}
			if(!empty($data->idclientesf)){
				$idclientesf = json_encode($data->idclientesf);
				if($idclientesf != '[""]'){
					$where .= " AND a.idclientes IN ($idclientesf)"; 
				}				
			}
			if(!empty($data->idproyectosf)){
				$idproyectosf = json_encode($data->idproyectosf);
				if($idproyectosf != '[""]'){
					$where .= " AND a.idproyectos IN ($idproyectosf)"; 
				}				
			}
			if(!empty($data->categoriaf)){
				$categoriaf = json_encode($data->categoriaf);
				if($categoriaf != '[""]'){
					$where .= " AND a.idcategorias IN ($categoriaf)";
				}
			}
			if(!empty($data->subcategoriaf)){
				$subcategoriaf = json_encode($data->subcategoriaf);
				if($subcategoriaf != '[""]'){
					$where .= " AND a.idsubcategorias IN ($subcategoriaf)";
				}
			}
			if(!empty($data->iddepartamentosf)){
				$iddepartamentosf = json_encode($data->iddepartamentosf);
				if($iddepartamentosf != '[""]'){
					$where .= " AND a.iddepartamentos IN ($iddepartamentosf)"; 
				}
			}
			if(!empty($data->prioridadf)){
				$prioridadf = json_encode($data->prioridadf);
				if($prioridadf != '[""]'){
					$where .= " AND a.idprioridades IN ($prioridadf)";
				}				
			}
			if(!empty($data->modalidadf)){
				$modalidadf = json_encode($data->modalidadf);
				if($modalidadf != '[""]'){
					$where .= " AND ti.id IN ($modalidadf)";
				}
			}
			if(!empty($data->marcaf)){
				$marcaf = json_encode($data->marcaf);
				if($marcaf != '[""]'){
					$where .= " AND mar.id IN ($marcaf)"; 
				}
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				if($solicitantef != '[""]'){
					$where .= " AND a.solicitante IN ($solicitantef)";
				}
			}
			if(!empty($data->estadof)){
				$estadof = json_encode($data->estadof);
				if($estadof != '[""]'){
				    $estadof = str_replace('"',"",$estadof);
					$where .= " AND a.idestados IN ($estadof)";
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
					$where .= " AND a.asignadoa IN ($asignadoaf)";	
				}
			}
			if(!empty($data->unidadejecutoraf)){
				$unidadejecutoraf = json_encode($data->unidadejecutoraf);
				 if($unidadejecutoraf !== '[""]'){ 
					$where .= " AND a.idambientes IN ($unidadejecutoraf)";
				}
			}					
			$vowels = array("[", "]");
			$where = str_replace($vowels, "", $where);
		}
		
		$idusuario 	= $_SESSION['user_id'];
		$nivel		= $_SESSION['nivel'];		
		$query  = " SELECT a.id, e.nombre AS estado, LEFT(a.titulo,45) as titulo, a.titulo as titulott,
					IFNULL(j.nombre, a.solicitante) AS solicitante, a.fechacreacion, a.horacreacion, a.fechacierre, 
					b.nombre AS idproyectos, f.nombre AS categoria, g.nombre AS subcategoria, a.asignadoa, l.nombre AS nomusuario, 
					c.nombre AS ambiente, m.serie, mar.nombre as marca, r.nombre as modelo,  ti.nombre AS modalidad, h.prioridad, a.fecharesolucion, a.fechareal,
					case when a.fechacierre IS NULL OR LENGTH(ltrim(rTrim(a.fechacierre))) > 0
					then a.fechacreacion else a.fechacierre end as fechaorden,
					n.descripcion as idempresas, o.nombre as iddepartamentos, p.nombre as idclientes 
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN activos m ON a.idactivos = m.id AND a.idambientes = m.idambientes
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					LEFT JOIN marcas mar ON m.idmarcas = mar.id
					LEFT JOIN modelos r ON m.idmodelos = r.id
					LEFT JOIN activostipos ti ON ti.id = m.idtipo
					";

		$query  .= " WHERE a.tipo = 'preventivos' ";
		$query .= permisos('preventivos', '', $idusuario);

		$hayFiltros = 0;
		for($i=0 ; $i<count($columns);$i++){
			$column = $_REQUEST['columns'][$i]['data'];
			if ($_REQUEST['columns'][$i]['search']['value']!="") {

                
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);


				if ($column == 'id') {
					$column = 'a.id';
					$where2[] = " $column like '%".$campo."%' ";
				}				
				if ($column == 'estado') {
					$column = 'e.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'titulo') {
					$column = 'a.titulo';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'solicitante') {
					$columnA = 'a.solicitante';
					$columnB = 'j.nombre';
					$where2[] = " ($columnA like '%".$campo."%' OR $columnB like '%".$campo."%')";
				}
				if ($column == 'fechacreacion') {
					$column = 'a.fechacreacion';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'horacreacion') {
					$column = 'a.horacreacion';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'fechareal') {
					$column = 'a.fechareal';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'idempresas') {
					$column = 'n.descripcion';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'iddepartamentos') {
					$column = 'o.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'idclientes') {
					$column = 'p.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'idproyectos') {
					$column = 'b.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'idcategoria') {
					$column = 'f.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}               
				if ($column == 'idsubcategoria') {
					$column = 'g.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}               
				if ($column == 'asignadoa') {
					$column = 'l.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}                
				if ($column == 'sitio') {
					$column = 'c.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'modalidad') {
					$column = 'ti.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'serie') {
					$column = 'm.serie';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'marca') {
					$column = 'mar.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'modelo') {
					$column = 'r.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'idprioridad') {
					$column = 'h.prioridad';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'fecharesolucion') {
					$column = 'a.fecharesolucion';
					$where2[] = " $column like '%".$campo."%' ";
				}
				$hayFiltros++;
			}
		}		
//		
		if ($hayFiltros > 0)
			$where .= " AND ".implode(" AND " , $where2)." ";// id like '%searchValue%' or name like '%searchValue%'
		


		$where3 = "";
		if($searchGeneral!=""){
			$where3.= " AND (

				a.id like '%".$searchGeneral."%' OR
				e.nombre like '%".$searchGeneral."%' OR
				a.titulo like '%".$searchGeneral."%' OR
				a.solicitante like '%".$searchGeneral."%' OR
				j.nombre like '%".$searchGeneral."%' OR
				a.fechacreacion like '%".$searchGeneral."%' OR
				a.horacreacion like '%".$searchGeneral."%' OR
				a.fechareal like '%".$searchGeneral."%' OR
				n.descripcion like '%".$searchGeneral."%' OR
				o.nombre like '%".$searchGeneral."%' OR
				p.nombre like '%".$searchGeneral."%' OR
				b.nombre like '%".$searchGeneral."%' OR
				f.nombre like '%".$searchGeneral."%' OR
				g.nombre like '%".$searchGeneral."%' OR
				l.nombre like '%".$searchGeneral."%' OR
				c.nombre like '%".$searchGeneral."%' OR
				ti.nombre like '%".$searchGeneral."%' OR
				m.serie like '%".$searchGeneral."%' OR
				mar.nombre like '%".$searchGeneral."%' OR
				r.nombre like '%".$searchGeneral."%' OR
				h.prioridad like '%".$searchGeneral."%' OR
				a.fecharesolucion like '%".$searchGeneral."%'
			) ";
		}

		$query  .= " $where ".$where3;
		//$query  .= " GROUP BY a.id ";

		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;


		$query  .= " ORDER BY a.id DESC  LIMIT ".$start.",".$rowperpage;
		//debugL($query,"CARGARPREVENTIVOS"); 
		if(!$result = $mysqli->query($query)){ 
		  die($mysqli->error);  
		}
		$resultado = array();
		$result = $mysqli->query($query);
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
			$evid = '';
			$color = 'info';
			$span_evid = '<span class="btn-icon btn-xs" id="boton-evidencias" style="position: absolute;top: 12px;right: 0;padding: 0;">
						    <i class="fa fa-camera text-green i-header" aria-hidden="true" style="cursor: initial;"></i>
						 </span>';
			if($tieneEvidencias != ''){
				$color = 'success';
				$evid = $span_evid;
			}else{
				// Verifico adjuntos de comentarios  
				$ruta = '../incidentes/'.$row['id'].'/comentarios';
				$respuesta = obtener_estructura_directorios($ruta); 
				if($respuesta > 0){
					$color = 'success';
					$evid = $span_evid;
					 
				}
			}
			//COMENTARIOS LEÍDOS 
			$iconcoment = "";
			$coment = " SELECT count(visto) AS total, MAX(fecha) AS fecha FROM comentarios WHERE idmodulo = '".$row['id']."' ";			
			$rcomen = $mysqli->query($coment);	
			
			$row2 = $rcomen->fetch_assoc();
			$totalco = $row2['total'];
			$fecha   = $row2['fecha'];
						 	
			if($totalco > 0){
    			$comentN = " SELECT (SELECT COUNT(*) FROM comentarios WHERE visto = 'SI' AND idmodulo = ".$row['id'].") AS si, 
			 				(SELECT COUNT(*) FROM comentarios WHERE visto = 'NO' AND idmodulo = ".$row['id'].") AS no ";
				 
			 	$rcomenN = $mysqli->query($comentN);
			 	$rowN = $rcomenN->fetch_assoc();
			 	$totalSi = $rowN['si']; 
			 	$totalNo = $rowN['no']; 
				//debug('$comentN: '.$comentN.' - SI: '.$totalSi.' - NO: '.$totalNo);
			 	if($totalSi > 0 && $totalNo <= 0){ 
			 		if($fecha < "2019-11-14"){
			 			/*$iconcoment = "<span class='icon-col blue fa fa-comment boton-coment-".$row['id']."' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Comentarios' data-placement='right'></span>";*/
			 			$clasecomen = "icono-comentario";			   
			 		}else{
			 			$coment2 = " SELECT count(a.id) AS total 
			 						 FROM `comentariosvistos` a 
			 						 LEFT JOIN comentarios b ON b.id = a.idcomentario 
			 						 WHERE a.usuario = '".$usuario."' 
			 						 AND 
			 						 b.idmodulo = '".$row['id']."'";
								 
			 			$rcomen2 = $mysqli->query($coment2);
								
			 			$rowv = $rcomen2->fetch_assoc();
			 			$totalv = $rowv['total'];
						
			 			if($totalv == $totalco){ 
						}else{ 
						    $iconcoment ='<span class="btn-icon btn-xs boton-coment-'.$row['id'].'" id="boton-comentario" style="padding: 0;">
						    <i class="fa fa-comment text-green i-header" aria-hidden="true" style="cursor: initial;"></i></span>';
			 			}
			 			$clasecomen = "icono-comentario";			   
			 		}
			 	}elseif($totalSi <= 0 && $totalNo > 0){
			 	    $iconcoment ='<span class="btn-icon btn-xs boton-coment-'.$row['id'].'" id="boton-comentario" style="padding: 0;">
						    <i class="fa fa-comment text-green i-header" aria-hidden="true" style="cursor: initial;"></i></span>'; 
			 		$clasecomen = "icono-comentario";				  
			 	}elseif($totalSi >= 0 && $totalNo > 0){ 
			 	    $iconcoment ='<span class="btn-icon btn-xs boton-coment-'.$row['id'].'" id="boton-comentario" style="padding: 0;">
						    <i class="fa fa-comment text-green i-header" aria-hidden="true" style="cursor: initial;"></i></span>';
			 		$clasecomen = "icono-comentario";
			 		//No Visto
			 	} 
			 }else{
			 	$iconcoment = "";
			 	$clasecomen = "";
			 }
			/* $existecom = 0;
			$icon_coment = "";
			
			$sql = " SELECT COUNT(a.id) AS com, COUNT(b.id) AS comv 
					 FROM comentarios a 
					 LEFT JOIN comentariosvistos b ON a.id = b.idcomentario AND b.usuario = '".$usuario."'
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
			} */
			
			//Reporte de Servicio - Verificar si existe el reporte de servicio 
			/* $queryRep   = " SELECT reporteservicio FROM incidentes WHERE id = ".$row['id'];
			$resultRep  = $mysqli->query($queryRep);				
			$rowRep     = $resultRep->fetch_assoc();			
			$numreporte = $rowRep['reporteservicio'];
			
			if($numreporte != ""){
				$reporte1 = "../incidentes/".$row['id']."/reporte ".$numreporte.".jpg";
				$reporte2 = "../incidentes/".$row['id']."/reporte ".$numreporte.".jpeg";
				$reporte3 = "../incidentes/".$row['id']."/Reporte ".$numreporte.".jpg";
				$reporte4 = "../incidentes/".$row['id']."/Reporte ".$numreporte.".jpeg";
				
				if (file_exists($reporte1)) {
					$reporteexiste = 1; 
				} else if(file_exists($reporte2)){
					$reporteexiste = 1; 
				} else if(file_exists($reporte3)){
					$reporteexiste = 1; 
				} else if(file_exists($reporte4)){
					$reporteexiste = 1; 
				} else{
					$reporteexiste = 0; 
				}
			}else{
				$reporteexiste = 0;
			}
			
			//Reporte de Servicio - Solo usuarios Nivel Ing/Tecn y Admin de Soporte
			$pos = strpos($iddepartamentos, '4');
			if($nivel == 3 || ($nivel == 2 && $pos !== true) || $nivel == 1){
				if($reporteexiste == 0){
					$iconrep = '<a class="dropdown-item text-info boton-reporte" data-id="'.$row['id'].'"><i class="fa fa-file mr-2"></i>Reporte de Servicio</a>';
					//$iconrep = "";
				}else{
					$iconrep = "";
				}
			}else{
				$iconrep = "";
			} */ 
			$longtitulo = strlen($row['titulo']);
			if($longtitulo>42){
				$points = " ...";
				$titulo = "<span data-toggle='tooltip' class='prueba' data-placement='right' data-original-title='".$row['titulott']."'>".$row['titulo'].$points."</span>";
			}else{ 
				$titulo = $row['titulo'];
			}
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
									<span class= "msj-'.$row['id'].' '.$clasecomen.'" style="position: absolute;top: -8px;right: 0;">'.$iconcoment.'</span>
									'.$evid.'
								</div>
								<div class="dropdown-menu dropdown-menu-right droptable">
									<a class="dropdown-item text-info" href="preventivo.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>
									<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>'; 
			$acciones .= '<a class="dropdown-item text-'.$color.' boton-evidencias"  data-id='.$row['id'].' "><i class="fas fa-camera mr-2"></i>Evidencias</a>';
			//$acciones .= $iconrep;

			$acciones .= 		'</div>
							</div>
						</td>';
    	    $resultado[] = array(			
				'check' 			=>	"",
				'acciones' 			=> $acciones,
				'id' 				=> $row['id'],
				'estado' 			=> $row['estado'],
				'titulo' 			=> "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['titulott']."'>".$row['titulo']."</span>",
				'solicitante'		=> $solicitante,
				'fechacreacion' 	=> $row['fechacreacion'],
				'horacreacion'		=> $row['horacreacion'],
				'fechareal'			=> $row['fechareal'],
				'idempresas'		=> $row['idempresas'],
				'iddepartamentos'	=> $row['iddepartamentos'],
				'idclientes'		=> $row['idclientes'],
				'idproyectos'		=> $row['idproyectos'],
				'idcategoria'		=> $row['categoria'],
				'idsubcategoria'	=> $row['subcategoria'],
				'asignadoa'			=> $row['nomusuario'],
				'sitio'				=> $row['ambiente'],
				'modalidad'			=> $row['modalidad'],
				'serie'				=> $row['serie'],
				'marca'				=> $row['marca'],
				'modelo'			=> $row['modelo'],
				'idprioridad'		=> $row['prioridad'],
				'fecharesolucion'	=> $row['fecharesolucion']
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
	



	
	function eliminarincidentes()
	{
		global $mysqli;

		$id 	= $_REQUEST['idincidente'];		
		$query 	= "DELETE FROM incidentes WHERE id = '$id'";
		$result = $mysqli->query($query);
		if($result == true){
			echo 1;
		}else{
			echo 0;
		}
		bitacora($_SESSION['usuario'], "Preventivos", 'El Preventivo #: '.$id.' fue eliminado.', $id, $query);				
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
			$queryEs    = " DELETE FROM comentarios WHERE id = '$id'";
			$resultEs   = $mysqli->query($queryEs);
			if($resultEs){
				//Elimino evidencias del comentario
				$carpeta = '../incidentes/'.$idincidente.'/comentarios/'.$id.'/';
				deleteDirectory($carpeta);
			    echo 1;
			}else{
			    echo 0;
			}
		}else{
		    //Consulto si el usuario es el creador del comentario
		    $queryNoes  = "  SELECT * FROM comentarios WHERE id = '$id' AND usuario = '$usuario' ";
    	    $resultNoes =    $mysqli->query($queryNoes);
    		if($resultNoes->num_rows > 0){
				//Elimino el comentario
				$query  = "  DELETE FROM comentarios WHERE id = '$id' AND usuario = '$usuario' ";
				$resultSi =    $mysqli->query($query);
				if($resultSi==true){
					//Elimino evidencias del comentario
					$carpeta = '../incidentes/'.$idincidente.'/comentarios/'.$id.'/';
					deleteDirectory($carpeta);
				
					echo 1;
				}else{
					echo 0;
				}
			}else{
				echo 2;
			}
		}		
		bitacora($_SESSION['usuario'], "Preventivos", 'El Comentario #: '.$id.' fue eliminado.', $id, $query); 
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
		$incidente 	= (!empty($_REQUEST['incidente']) ? $_REQUEST['incidente'] : '');
		$_SESSION['incidente_pre'] = $incidente;
		$_SESSION['comentario_pre'] = '';
		//INCIDENTES
		$myPathInc = '../incidentes';
		$target_pathInc = utf8_decode($myPathInc);
		if (!file_exists($target_pathInc)) {
			mkdir($target_pathInc, 0777);
		}
		//INCIDENTE
		$myPathI = '../incidentes/'.$incidente;
		$target_pathI = utf8_decode($myPathI);
		if (!file_exists($target_pathI)) {
			mkdir($target_pathI, 0777);
		}
		//$Path = dirname($_SERVER['PHP_SELF']) . '/../incidentes/'.$_SESSION['incidente'].'/';
		//RUTA
		$Path = '/../incidentes/'.$incidente.'/';
		//debugL('$Path: '.$incidente);
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
			$queryI = "INSERT INTO comentarios VALUES(null, 'Preventivos', $incidente, '$comentario', '$visibilidad', '$usuario', NOW(), 'NO')";
			//debug('queryI: '.$_GET['comentario']);
			if($mysqli->query($queryI)){
				$id = $mysqli->insert_id;
				//BITACORA
				bitacora($_SESSION['usuario'], "Preventivos", "Se ha registrado un Comentario para el Preventivo #".$incidente, $incidente, $queryI);
				//ENVIAR NOTIFICACION
				if($visibilidad == 'Privado'){
					notificarComentariosSoporte($incidente,$comentario,$visibilidad);
					notificarComentariosAsignados($incidente,$comentario,$visibilidad);
				}else{
					//notificarComentariosSoporte($incidente,$comentario,$visibilidad);
					notificarComentarios($incidente,$comentario,$visibilidad,$id);
				}
				//*******************************************//
				//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
				//*******************************************//
				
				//Usuarios asociados al preventivo
				$qInc = " 	SELECT b.usuario AS usuarioasignadoa, c.usuario AS usuariosolicitante, d.usuario AS correocreadopor
							FROM incidentes a 
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
				$idusuarios["icarvajal"] = "0";
				$idusuarios["frios"] = "0";
				$idusuarios["aanderson"] = "0";
				$idusuarios["admin"] = "0";
				
				$usuarios = json_encode($idusuarios);
				
				$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,fecha,hora,usuarios) VALUES (".$idproyectos.",".$incidente.",'Comentario realizado preventivo','". date("Y-m-d") ."','". date("H:i:s") ."','".$usuarios."')"; 
	            $rsql = $mysqli->query($sql); 
				
				//*******************************************//
				//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
				//*******************************************//
				
				//Se crea carpeta comentarios
				$myPathC 	  = '../incidentes/'.$incidente.'/comentarios';
				$target_pathC = utf8_decode($myPathC);
				if (!file_exists($target_pathC)) {
					mkdir($target_pathC, 0777);
				}
				//Se crea carpeta con identificador de comentario
				$myPath 	 = '../incidentes/'.$incidente.'/comentarios/'.$id;
				$target_path = utf8_decode($myPath);
				if (!file_exists($target_path)) {
					mkdir($target_path, 0777);
				}
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
		
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    $orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy	= (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType	= (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
	    $start   	= (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);
		$length   = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		
		$nivel 		= $_SESSION['nivel'];
		$id 		= (!empty($_GET['id']) ? $_GET['id'] : 0);
		$buscar 	= (isset($_POST['buscar']) ? $_POST['buscar'] : '');
		$resultado 	= array();
		$acciones 	= '';
		
		$queryF	  	= " SELECT GROUP_CONCAT(id) AS fusionados FROM incidentes WHERE fusionado = '$id' ";
		$resultF 	= $mysqli->query($queryF);
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
					FROM comentarios a
					LEFT JOIN usuarios b ON a.usuario = b.usuario
					WHERE modulo = 'Preventivos' AND idmodulo IN ($idmodulo) AND a.visibilidad != '' ";
		if($nivel == 4 || $nivel == 7){
			$query .= " AND a.visibilidad = 'Público' ";
		}
		$query .= " ORDER BY a.id DESC ";
		//debug($query);
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		//$query  .= " LIMIT $start, $length ";
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
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-right droptable">
									<a class="dropdown-item text-'.$color.' boton-adjuntos-comentarios"  data-id="'.$row['idmodulo'].'-'.$row['id'].'"><i class="fas fa-camera mr-2"></i>Evidencias de comentario</a>';
			if($nivel != 4 || $nivel != 7){
				$acciones .= '<a class="dropdown-item text-danger boton-eliminar-comentarios" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar Comentario</a>';
			}

			$acciones .= 		'</div>
							</div>
						</td>';
			
// 			if($adjuntos != ''){
// 				$color = 'green';
// 			}else{
// 				$color = 'blue';
// 			}
// 			if($nivel == 4 || $nivel == 7){
// 				$acciones = " <div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>
// 									<span class='icon-col ".$color." fa fa-camera boton-adjuntos-comentarios' data-id='".$row['idmodulo']."-".$row['id']."' data-toggle='tooltip' data-original-title='Adjuntos Comentario' data-placement='right'></span> 
// 								</div> ";
// 			}else{
// 				$acciones = " <div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>
// 									<span class='icon-col red fa fa-trash boton-eliminar-comentarios' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Comentario' data-placement='right'></span> 
// 									<span class='icon-col ".$color." fa fa-camera boton-adjuntos-comentarios' data-id='".$row['idmodulo']."-".$row['id']."' data-toggle='tooltip' data-original-title='Adjuntos Comentario' data-placement='right'></span> 
// 								</div> ";
// 			}
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
	
		function costos(){
		global $mysqli;
		
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		
		$nivel 		= $_SESSION['nivel'];
		$id 		= (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado 	= array();
		$acciones 	= '';
		 
		$query  = " SELECT a.id, a.idmodulo, a.descripcion, a.monto, b.nombre AS usuario, a.fecha
					FROM incidentescostos a
					LEFT JOIN usuarios b ON a.usuario = b.usuario
					WHERE a.idmodulo = ".$id." ";
		 
		$query .= " ORDER BY a.id DESC "; 
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows; 
		while($row = $result->fetch_assoc()){
			//ADJUNTOS
			$adjuntos   = '';
			$ruta 		= '../incidentes/'.$row['idmodulo'].'/costos/'.$row['id'];
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
			
			$adjuntos != '' ? $color = 'success' : $color = 'info';
			
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-right droptable">
									<a class="dropdown-item text-'.$color.' boton-adjuntos-costos"  data-id="'.$row['idmodulo'].'-'.$row['id'].'"><i class="fas fa-camera mr-2"></i>Adjuntos Costo</a>';
			 
			$acciones .= '<a class="dropdown-item text-danger boton-eliminar-costos" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar Costo</a>'; 

			$acciones .= 		'</div>
							</div>
						</td>';

			$resultado[] = array(
				'id' 			=> $row['id'],
				'acciones' 		=> $acciones,
				'descripcion' 	=> $row['descripcion'],
				'monto'			=> number_format((float)$row['monto'], 2, '.', ','),
				'usuario'		=> $row['usuario'],
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
	
	function agregarCosto(){
		global $mysqli;
		$idincidentes = $_REQUEST['id'];
		$descripcion  = $_REQUEST['descripcion'];
		$monto 		  = $_REQUEST['monto'];
		$usuario 	  = $_SESSION['usuario']; 
		$fecha 		  = date("Y-m-d H:i:s");
		 
		$queryI = " INSERT INTO incidentescostos (idmodulo,modulo,descripcion,monto,usuario,fecha)
					VALUES(".$idincidentes.", 'preventivos',  '".$descripcion."', '".$monto."', '".$usuario."', NOW())";
		$result = $mysqli->query($queryI);
		if($result == true){
			$id = $mysqli->insert_id;
			//BITACORA
			bitacora($_SESSION['usuario'], "Incidentes", "Se ha registrado un registro de costo para el Correctivo #".$idincidentes, $idincidentes, $queryI);
			 
			//Se crea carpeta de costos
			$myPathC 	  = '../incidentes/'.$idincidentes.'/costos';
			$target_pathC = utf8_decode($myPathC);
			if (!file_exists($target_pathC)) {
				mkdir($target_pathC, 0777);
			}
			//Se crea carpeta con identificador de costos
			$myPath 	 = '../incidentes/'.$idincidentes.'/costos/'.$id;
			$target_path = utf8_decode($myPath);
			if (!file_exists($target_path)) {
				mkdir($target_path, 0777);
			} 
			echo 1;
		}else{
			echo 0;
		} 
	}
	
	function eliminarcostos()
	{
		global $mysqli;

		$idincidente = $_REQUEST['idincidente'];
		$idcosto 	 = $_REQUEST['idcosto']; 
		$nivel 	 	 = $_SESSION['nivel'];
		$usuario 	 = $_SESSION['usuario'];
		 
		$query    = " DELETE FROM incidentescostos WHERE id = ".$idcosto."";
		$result   = $mysqli->query($query);
		if($result == true){
			
			//Elimino evidencias del costo
			$carpeta = '../incidentes/'.$idincidente.'/costos/'.$idcosto.'/';
			deleteDirectory($carpeta);
			
			echo 1;
		}else{
			echo 0;
		}
		 
		bitacora($_SESSION['usuario'], "Correctivos", 'El costo para el correctivo #: '.$id.' fue eliminado.', $id, $query); 
	}
	
	function adjuntoscostos() {
		$incidentecosto = (!empty($_REQUEST['incidentecosto']) ? $_REQUEST['incidentecosto'] : '');
		$arr 			= explode('-',$incidentecosto);
		$incidente 		= $arr[0];
		$costo		 	= $arr[1];
		$_SESSION['incidente_cor'] 	= $incidente;
		$_SESSION['comentario_cor'] = $costo;		
		//INCIDENTES
		$myPathInc = '../incidentes';
		$target_pathInc = utf8_decode($myPathInc);
		if (!file_exists($target_pathInc)) {
			mkdir($target_pathInc, 0777);
		}
		//INCIDENTE
		$myPathI = '../incidentes/'.$incidente;
		$target_pathI = utf8_decode($myPathI);
		if (!file_exists($target_pathI)) {
			mkdir($target_pathI, 0777);
		}
		//Costos
		$myPathC 	  = '../incidentes/'.$incidente.'/costos';
		$target_pathC = utf8_decode($myPathC);
		if (!file_exists($target_pathC)) {
			mkdir($target_pathC, 0777);
		}
		//Costos
		$myPath 	 = '../incidentes/'.$incidente.'/costos/'.$costo;
		$target_path = utf8_decode($myPath);
		if (!file_exists($target_path)) {
			mkdir($target_path, 0777);
		}
		 
		//RUTA
		$Path = '/../incidentes/'.$incidente.'/costos/'.$costo.'/';
		$hash = strtr(base64_encode($Path), '+/=', '-_.');
		$hash = rtrim($hash, '.');
		echo "l1_". $hash;
	}
	
	function comentariosleidos(){		
		global $mysqli;		
		$idincidente = $_REQUEST['idincidente'];
		$usuario     = $_SESSION['usuario'];
		
		$queryC = "	SELECT id FROM comentarios WHERE idmodulo = '$idincidente' AND visto != '' "; 
		$resultC = $mysqli->query($queryC);
		while($rowC = $resultC->fetch_assoc()){
			$idc = $rowC['id'];
		    $queryV = " SELECT count(id) AS id FROM comentariosvistos WHERE idcomentario = '".$idc."' 
						AND usuario = '".$usuario."' ";
			$resultV = $mysqli->query($queryV);
			$rowV = $resultV->fetch_assoc();
			$idv = $rowV['id'];
			if($idv == 0){  
				$query = "INSERT INTO comentariosvistos (idcomentario, usuario, fecha)
						  VALUES ('$idc', '$usuario', NOW())";
				
			    $result = $mysqli->query($query);
				if($result == true){
					$upd = " UPDATE comentarios SET visto = 'SI'
							 WHERE idmodulo = '$idincidente' AND visto = 'NO' ";			
					$resupd = $mysqli->query($upd); 
					echo 1;
				}else{
					echo 0;
				} 
			}else{
				echo 2;
			}
		} 		
	}
	
	function adjuntosComentarios() {
		$incidentecom 	= (!empty($_REQUEST['incidentecom']) ? $_REQUEST['incidentecom'] : '');
		$arr 			= explode('-',$incidentecom);
		$incidente 		= $arr[0];
		$comentario 	= $arr[1];
		$_SESSION['incidente_pre'] 	= $incidente;
		$_SESSION['comentario_pre'] = $comentario;		
		//INCIDENTES
		$myPathInc = '../incidentes';
		$target_pathInc = utf8_decode($myPathInc);
		if (!file_exists($target_pathInc)) {
			mkdir($target_pathInc, 0777);
		}
		//INCIDENTE
		$myPathI = '../incidentes/'.$incidente;
		$target_pathI = utf8_decode($myPathI);
		if (!file_exists($target_pathI)) {
			mkdir($target_pathI, 0777);
		}
		//COMENTARIOS
		$myPathC 	  = '../incidentes/'.$incidente.'/comentarios';
		$target_pathC = utf8_decode($myPathC);
		if (!file_exists($target_pathC)) {
			mkdir($target_pathC, 0777);
		}
		//COMENTARIO
		$myPath 	 = '../incidentes/'.$incidente.'/comentarios/'.$comentario;
		$target_path = utf8_decode($myPath);
		if (!file_exists($target_path)) {
			mkdir($target_path, 0777);
		}
		//$Path = dirname($_SERVER['PHP_SELF']) . '/../incidentes/'.$_SESSION['incidente'].'/';
		//RUTA
		$Path = '/../incidentes/'.$incidente.'/comentarios/'.$comentario.'/';
		$hash = strtr(base64_encode($Path), '+/=', '-_.');
		$hash = rtrim($hash, '.');		
		echo "l1_". $hash;		
	}
	
	//ENVIAR CORREO DE NOTIFICACION DE COMENTARIO
	function notificarComentarios($incidente,$comentario,$visibilidad,$idcomentarios){
		global $mysqli;
		//CREADOR - SOLICITANTE - ASIGNADO
		$query  = " SELECT a.titulo, 
					a.notificar,
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
						AS asignadoa, k.nombre AS nombreasignado
					FROM incidentes a
					LEFT JOIN usuarios i ON a.creadopor = i.id OR a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.asignadoa = k.correo
					WHERE a.id = $incidente AND i.id != 0 ";
		$result = $mysqli->query($query);
		while ($row = $result->fetch_assoc()) {
			$nombreasignado = $row['nombreasignado'];
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
								
				//Usuarios que quieren que se les notifique (Enviar Notificacion a)
				$notificar = json_decode($row['notificar']);
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
					$correo [] = $asignadoa;
				}else{
					foreach([$asignadoa] as $asig){
						$correo [] = $asig;
					}
				}			
				$query2 = " SELECT nombre FROM usuarios WHERE ";
				if (filter_var($row['asignadoa'], FILTER_VALIDATE_EMAIL)) {
					$query2 .= "correo = '".$row['asignadoa']."'  AND estado = 'Activo' ";
				}else{
					$query2 .= "correo IN (".$row['asignadoa'].")  AND estado = 'Activo'  ";
				}
				$consulta = $mysqli->query($query2);
				while($rec = $consulta->fetch_assoc()){
					$asignadoaN .= $rec['nombre']." , ";
				}			
			}		
		}
		
		//DATOS DEL CORREO
		$usuarios = $_SESSION['usuario'];
		$consultaUA = $mysqli->query(" SELECT nombre FROM usuarios WHERE usuario = '".$usuarios."' LIMIT 1 ");
		while ($registroUA = $consultaUA->fetch_assoc()) {
			$usuarioAct = $registroUA['nombre'];
		}
		//DATOS
		$query  = " SELECT a.id, a.titulo, a.descripcion, c.nombre AS ambiente, a.resolucion, h.prioridad, a.idproyectos, 
					a.origen, a.asignadoa, IFNULL(i.nombre, a.creadopor) AS creadopor, 
					IFNULL(j.nombre, a.solicitante) AS solicitante, a.creadopor AS ccreadopor, a.solicitante AS csolicitante,
					l.nombre AS departamento, IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0),CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion					
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN activos d ON a.idactivos = d.id
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.resueltopor = k.correo
					LEFT JOIN departamentos l ON a.iddepartamentos = l.id
					WHERE a.id = $incidente ";
		//debug($query);
		$result 		= $mysqli->query($query);
		$row 			= $result->fetch_assoc();
		$titulo 		= $row['titulo'];
		$fechacreacion 	= $row['fechacreacion'];
		$descripcion	= $row['descripcion'];
		$solicitante	= $row['solicitante'];
		$creadopor		= $row['creadopor'];
		$csolicitante	= $row['csolicitante'];
		$ccreadopor		= $row['ccreadopor'];
		$departamento	= $row['departamento'];
		$prioridad		= $row['prioridad'];
		$sitio 			= $row['ambiente'];
		$resolucion 	= $row['resolucion'];
		$idproyectos 	= $row['idproyectos'];
		$nasignadoa 	= $asignadoaN;
		$comentarios	= '';
		$bitacora		= '';
		
		//COMENTARIOS
		$consultaC = $mysqli->query("SELECT a.comentario, b.nombre AS nombreusuario, a.visibilidad, a.fecha FROM comentarios a INNER JOIN usuarios b ON b.usuario = a.usuario WHERE a.idmodulo = ".$incidente." AND a.visibilidad != 'Privado' AND a.id != ".$idcomentarios." ORDER BY a.id DESC LIMIT 3"); 
		if($consultaC->num_rows > 0){
			//COMENTARIOS
			$tablacomentarios = "<table style='border-collapse: collapse; margin: 0 4% 0 4%; width:-webkit-fill-available;'>
								<thead><tr><th align='left'>Fecha</th><th align='left'>Usuario</th><th align='left'>Comentario</th></tr></thead><tbody>";
			while ($registroC = $consultaC->fetch_assoc()) { 
				$numerocom++;
				($numerocom % 2) == 0 ? $backgcol = "#ffffff" : $backgcol = "#f6f6f6";
				$tablacomentarios .= "<tr style='background-color: ".$backgcol."'><td style='border-top: 1px solid #ddd; width: 15%; color: #3e4954; font-size: small; line-height: 150%;'>".$registroC['fecha']."</td><td style='border-top: 1px solid #ddd; width: 16%; color: #3e4954; font-size: small; line-height: 150%;'>".$registroC['nombreusuario']."</td><td style='border-top: 1px solid #ddd; color: #3e4954; font-size: small; line-height: 150%; text-align: justify;'>".$registroC['comentario']."</td></tr>";
			}
			$tablacomentarios .= "</tbody></table>";
		}else{
			$tablacomentarios ="No existen comentarios anteriores.";
		}
		
		//BITACORA
		$consultaB = $mysqli->query("SELECT accion FROM bitacora WHERE identificador = $incidente ");
		while ($registroB = $consultaB->fetch_assoc()) {
			$bitacora .= $registroB['accion'].'<br>';
		}
		
		$asunto = "Preventivo #$incidente - Comentario";
		
		$mensaje  = "<div style='margin: 0 6%; background: #FFFFFF; padding: 30px;font-family: poppins, sans-serif;'>
					<div style='margin: 0 6% 0 6%; font-size: 22px;width:100%; color:#333; margin-left: 4%'>".$usuarioAct." ha comentado el preventivo #".$incidente." ".$isist."</div>				
					<div style='font-size: 14px; margin: 2% 4% 0 4%; text-align: justify; line-height: 150%;'><span style='color: #222; font-weight: 600;'>Comentario:</span> ".$comentario."</div>
					<p style='width:100%; margin-left: 1%;'><br><a href='http://toolkit.maxialatam.com/mitim/preventivo.php?id=$incidente' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Añadir un comentario</a></p>
					<br>
					<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Comentarios anteriores</div><br><div style='margin: 0 4%'>";
					if($tablacomentarios != ''){
							$mensaje .= $tablacomentarios;
						}
					$mensaje .="
					</div><br><br>
					<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Atributos</div>
						<table style='width: 100%; margin: 0 4% 0 4%;'>
						<tr>
							<td style='padding: 15px 0; width: 50%; vertical-align: top; font-size: small;'><div><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Creado por</div>".$creadopor."</div></td>
							<td style='padding: 15px 0; font-size: small;'><div><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Título</div> ".$titulo."</div></td>
						</tr>
						<tr>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Solicitante del servicio</div>".$solicitante."</td>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Ubicación</div>".$sitio."</td>
						</tr>
						<tr>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Recibido en</div>".$fechacreacion."</td>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Departamento</div>".$departamento."</td>
						</tr>
						<tr>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Asignado a</div>".$nombreasignado."</td>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Prioridad</div>".$prioridad."</td>>
						</tr>
					<table>
					</div>";
		//USUARIOS DE SOPORTE
		//$correo [] = 'ana.porras@maxialatam.com';
		$correo [] = 'isai.carvajal@maxialatam.com';
		//$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		$correo [] = 'maria.baena@maxialatam.com';
		
		debugL("notificarComentariosPREVENTIVO-CORREO:".json_encode($correo),"notificarComentariosPREVENTIVO");
		
		//debug($correo);
		foreach ($correo as $key => $value) { 
			$querycorreo = "SELECT * FROM notificacionesxusuarios nu
							left join usuarios u on u.id = nu.idusuario
							where u.correo = '$value' and noti8 = 1";
			$consultacorreo = $mysqli->query($querycorreo);
			if($consultacorreo->num_rows == 0){
				unset($correo[$key]);
			}
		}	
		enviarMensajeIncidente($asunto,$mensaje,$correo,'','comentario');
	}
	
	function notificarComentariosAsignados($incidente,$comentario,$visibilidad){
		global $mysqli;
		//ASIGNADO
		$query  = " SELECT a.titulo, 
					CASE 
						WHEN b.estado = 'Activo' 
							THEN a.asignadoa 
						WHEN b.estado = 'Inactivo' 
							THEN '' 
						END 
						AS asignadoa
					FROM incidentes a
					LEFT JOIN usuarios b ON b.correo = a.asignadoa
					WHERE a.id = ".$incidente." AND b.id != 0 ";
		$result = $mysqli->query($query);
		while ($row = $result->fetch_assoc()) {
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
				$query2 = " SELECT nombre, nivel FROM usuarios WHERE ";
				if (filter_var($row['asignadoa'], FILTER_VALIDATE_EMAIL)) {
					$query2 .= "correo = '".$row['asignadoa']."'  AND estado = 'Activo' ";
				}else{
					$query2 .= "correo IN (".$row['asignadoa'].")  AND estado = 'Activo'  ";
				}
				$consulta = $mysqli->query($query2);
				while($rec = $consulta->fetch_assoc()){
					$asignadoaN .= $rec['nombre']." , ";
					$nivelAsig = $rec['nivel'];
				}
			}
		}
		
		if($nivelAsig == 3){
			//DATOS DEL CORREO
			$usuarios = $_SESSION['usuario'];
			$consultaUA = $mysqli->query(" SELECT nombre FROM usuarios WHERE usuario = '".$usuarios."' LIMIT 1 ");
			while ($registroUA = $consultaUA->fetch_assoc()) {
				$usuarioAct = $registroUA['nombre'];
			}
			
			//DATOS
			$query  = " SELECT a.id, a.titulo, a.descripcion, c.nombre AS ambiente, a.resolucion, h.prioridad, a.idproyectos, 
						a.origen, a.asignadoa, IFNULL(i.nombre, a.creadopor) AS creadopor, 
						IFNULL(j.nombre, a.solicitante) AS solicitante, a.creadopor AS ccreadopor, a.solicitante AS csolicitante,
						a.departamento, IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0),CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion					
						FROM incidentes a
						LEFT JOIN proyectos b ON a.idproyectos = b.id
						LEFT JOIN ambientes c ON a.idambientes = c.id
						LEFT JOIN activos d ON a.idactivos = d.id
						LEFT JOIN estados e ON a.idestados = e.id
						LEFT JOIN categorias f ON a.idcategorias = f.id
						LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
						LEFT JOIN sla h ON a.idprioridades = h.id
						LEFT JOIN usuarios i ON a.creadopor = i.correo
						LEFT JOIN usuarios j ON a.solicitante = j.correo
						LEFT JOIN usuarios k ON a.resueltopor = k.correo
						WHERE a.id = $incidente ";
			//debug($query);
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
			$sitio 			= $row['ambiente'];
			$resolucion 	= $row['resolucion'];
			$idproyectos 	= $row['idproyectos'];
			$nasignadoa 	= $asignadoaN;
			$comentarios	= '';
			$bitacora		= '';
			
			//COMENTARIOS
			$consultaC = $mysqli->query("SELECT comentario FROM comentarios WHERE idmodulo = $incidente ");
			if($consultaC->num_rows > 0){
				//COMENTARIOS
				$tablacomentarios = "<table style='border-collapse: collapse; margin: 0 4% 0 4%; width:-webkit-fill-available;'>
									<thead><tr><th align='left'>Fecha</th><th align='left'>Usuario</th><th align='left'>Comentario</th></tr></thead><tbody>";
				while ($registroC = $consultaC->fetch_assoc()) {
					$numerocom++;
					($numerocom % 2) == 0 ? $backgcol = "#ffffff" : $backgcol = "#f6f6f6";
					$tablacomentarios .= "<tr style='background-color: ".$backgcol."'><td style='border-top: 1px solid #ddd; width: 15%; color: #3e4954; font-size: small; line-height: 150%;'>".$registroC['fecha']."</td><td style='border-top: 1px solid #ddd; width: 16%; color: #3e4954; font-size: small; line-height: 150%;'>".$registroC['nombreusuario']."</td><td style='border-top: 1px solid #ddd; color: #3e4954; font-size: small; line-height: 150%; text-align: justify;'>".$registroC['comentario']."</td></tr>";
				}
			}else{
				$tablacomentarios ="No existen comentarios anteriores.";
			}
			//BITACORA
			$consultaB = $mysqli->query("SELECT accion FROM bitacora WHERE identificador = $incidente ");
			while ($registroB = $consultaB->fetch_assoc()) {
				$bitacora .= $registroB['accion'].'<br>';
			}
			
			$asunto = "Preventivo #$incidente - Comentario ";
			
			$mensaje  = "<div style='padding: 30px;font-family: arial,sans-serif;'>
						<div style='margin: 0 6% 0 6%; font-size: 22px;width:100%; color:#333; margin-left: 4%'>".$usuarioAct." ha comentado el preventivo #".$incidente." ".$isist."</div>				
						<div style='font-size: 14px; margin: 2% 4% 0 4%; text-align: justify; line-height: 150%;'><span style='color: #222; font-weight: 600;'>Comentario:</span> ".$comentario."</div>
						<p style='width:100%; margin-left: 1%;'><br><a href='http://toolkit.maxialatam.com/mitim/preventivo.php?id=$incidente' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Añadir un comentario</a></p>
						<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin-top: 30px;padding: 10px 10px 0 30px;width:100%;'>Comentarios anteriores</p>";
						if($tablacomentarios != ''){
							$mensaje .= $tablacomentarios;
						}
						$mensaje .="
						</div><br><br>
						<p  style='font-size: 18px;width:100%;'>".$creadopor." ha creado este preventivo el ".$fechacreacion."</p>
						<br>
						<p style='width:100%;'>".$descripcion."</p>
						<br>
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Atributos</div> 
						<table style='width: 100%; margin: 0 4% 0 4%;'>
							<tr>
								<td style='padding: 15px 0; width: 50%; vertical-align: top; font-size: small;'><div><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Creado por</div>".$creadopor."</div></td>
								<td style='padding: 15px 0; font-size: small;'><div><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Título</div> ".$titulo."</div></td>
							</tr>
							<tr>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Solicitante del servicio</div>".$solicitante."</td>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Ubicación</div>".$sitio."</td>
							</tr>
							<tr>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Recibido en</div>".$fechacreacion."</td>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Departamento</div>".$departamento."</td>
							</tr>
							<tr>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Asignado a</div>".$nombreasignado."</td>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Prioridad</div>".$prioridad."</td>>
							</tr>
						<table>
						</div>";
		
		//debugL("notificarComentariosAsignadosPREVENTIVO-CORREO:".json_encode($correo),"notificarComentariosAsignadosPREVENTIVO");
						
		foreach ($correo as $key => $value) { 
			$querycorreo = "SELECT * FROM notificacionesxusuarios nu
							left join usuarios u on u.id = nu.idusuario
							where u.correo = '$value' and noti9 = 1";
			$consultacorreo = $mysqli->query($querycorreo);
			if($consultacorreo->num_rows == 0){
				unset($correo[$key]);
			}
		}
			if ($enviar==1)
				enviarMensajeIncidente($asunto,$mensaje,$correo,'','comentario');
		}else{
			return 1;
		}
	}
	
	function notificarComentariosSoporte($incidente,$comentario,$visibilidad){
		global $mysqli;
		
		//DATOS DEL CORREO
		$usuarios = $_SESSION['usuario'];
		$consultaUA = $mysqli->query(" SELECT nombre FROM usuarios WHERE usuario = '$usuarios' LIMIT 1 ");
		while ($registroUA = $consultaUA->fetch_assoc()) {
			$usuarioAct = $registroUA['nombre'];
		}
		//DATOS
		$query  = " SELECT a.id, a.titulo, a.descripcion, c.nombre AS ambiente, a.resolucion, a.idproyectos, h.prioridad, 
					a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, IFNULL(j.nombre, a.solicitante) AS solicitante, 
					CASE 
						WHEN l.estado = 'Activo' 
							THEN a.asignadoa 
						WHEN l.estado = 'Inactivo' 
							THEN '' 
						END 
						AS asignadoa,
					IF(a.fechacreacion IS NOT NULL,CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion, a.idclientes, l.nombre AS nombreasignado, m.nombre AS departamento					
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN activos d ON a.idactivos = d.id
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.resueltopor = k.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN departamentos m ON a.iddepartamentos = m.id
					WHERE a.id = ".$incidente." ";
					
		$result 		= $mysqli->query($query);
		$row 			= $result->fetch_assoc();
		$titulo 		= $row['titulo'];
		$fechacreacion 	= $row['fechacreacion'];
		$nombreasignado	= $row['nombreasignado'];
		$descripcion	= $row['descripcion'];
		$solicitante	= $row['solicitante'];
		$creadopor		= $row['creadopor'];
		$departamento	= $row['departamento'];
		$prioridad		= $row['prioridad'];
		$sitio 			= $row['ambiente'];
		$resolucion 	= $row['resolucion'];
		$idclientes 	= $row['idclientes'];
		$idproyectos 	= $row['idproyectos'];
		$nasignadoa 	= $asignadoaN;
		$comentarios	= '';
		$bitacora		= '';
		
		//COMENTARIOS
		$consultaC = $mysqli->query("SELECT a.comentario, b.nombre AS nombreusuario, a.fechas FROM comentarios a INNER JOIN usuarios b ON b.usuario = a.usuario WHERE a.idmodulo = ".$incidente);
		if($consultaC->num_rows > 0){
			$tablacomentarios = "<table style='border-collapse: collapse; margin: 0 4% 0 4%; width:-webkit-fill-available;'>
								<thead><tr><th align='left'>Fecha</th><th align='left'>Usuario</th><th align='left'>Comentario</th></tr></thead><tbody>";
								
			while ($registroC = $consultaC->fetch_assoc()) {
				$numerocom++;
				($numerocom % 2) == 0 ? $backgcol = "#ffffff" : $backgcol = "#f6f6f6";
				$tablacomentarios .= "<tr style='background-color: ".$backgcol."'><td style='border-top: 1px solid #ddd; width: 15%; color: #3e4954; font-size: small; line-height: 150%;'>".$registroC['fecha']."</td><td style='border-top: 1px solid #ddd; width: 16%; color: #3e4954; font-size: small; line-height: 150%;'>".$registroC['nombreusuario']."</td><td style='border-top: 1px solid #ddd; color: #3e4954; font-size: small; line-height: 150%; text-align: justify;'>".$registroC['comentario']."</td></tr>";
			}
			$tablacomentarios .= "</tbody></table>";
		}else{
			$tablacomentarios ="No existen comentarios anteriores.";
		}
		//BITACORA
		$consultaB = $mysqli->query("SELECT a.fecha, b.nombre AS nombreusuario, a.accion FROM bitacora a INNER JOIN usuarios b ON b.usuario = a.usuario WHERE a.identificador = ".$incidente);
		if($consultaB->num_rows > 0){
			$numerobit++;
			($numerobit % 2) == 0 ? $backgcolb = "#ffffff" : $backgcolb = "#f6f6f6";
			$tablabitacora = "<table style='border-collapse: collapse; margin: 0 4% 0 4%; width:-webkit-fill-available;'>
								<thead><tr><th align='left'>Fecha</th><th align='left'>Usuario</th><th align='left'>Acción</th></thead><tbody>";
			while ($registroB = $consultaB->fetch_assoc()) {
				$tablabitacora .= "<tr style='background-color: ".$backgcolb."'><td style='border-top: 1px solid #ddd; width: 15%; color: #3e4954; font-size: small; line-height: 150%;'>".$registroB['fecha']."</td><td style='border-top: 1px solid #ddd; width: 16%; color: #3e4954; font-size: small; line-height: 150%;'>".$registroB['nombreusuario']."</td><td style='border-top: 1px solid #ddd; width: 15%; color: #3e4954; font-size: small; line-height: 150%;'>".$registroB['accion']."</td></tr>";
			}
			$tablabitacora .= "</tbody></table>";
		}else{
			$tablabitacora ="No existen registros anteriores.";
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
		    $asunto 	= "Preventivo #$incidente - Comentario - ".$isist;
			$enviar 	= 0;
    	} else {
			$numinc 	= '';
    	    $asunto = "Preventivo #$incidente - Comentario ";
		}
		
		$mensaje  = "<div style='margin: 0 6%; background: #FFFFFF; padding: 30px;font-family: poppins, sans-serif;'>
					<div style='margin: 0 6% 0 6%; font-size: 22px;width:100%; color:#333; margin-left: 4%'>".$usuarioAct." ha comentado el preventivo #".$incidente." - ".$isist."</div>			
					<div style='font-size: 14px; margin: 2% 4% 0 4%; text-align: justify; line-height: 150%;'><span style='color: #222; font-weight: 600;'>Comentario:</span> ".$comentario."</div>
					<p style='width:100%; margin-left: 1%;'><br><a href='http://toolkit.maxialatam.com/mitim/incidentes.php?id=$incidente' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Añadir un comentario</a></p>
					<br>
					<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Comentarios anteriores</div>
					<div style='margin: 0 4%'>";
						if($tablacomentarios != ''){
							$mensaje .= $tablacomentarios;
						}
					$mensaje .="
					</div><br><br>
					<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Actividad reciente</div>
					<br>
					<div style='margin: 0 4%'>";
						if($tablabitacora != ''){
							$mensaje .= $tablabitacora;
						}
					$mensaje .="
					</div>
					<br><br>
					<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Atributos</div>
					<table style='width:100%;margin:0 4% 0 4%'>
						<tr>
							<td style='padding: 15px 0; width: 50%; vertical-align: top; font-size: small;'><div><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Creado por</div>".$creadopor."</div></td>
							<td style='padding: 15px 0; font-size: small;'><div><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Título</div> ".$titulo."</div></td>
						</tr>
						<tr>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Solicitante del servicio</div>".$solicitante."</td>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Ubicación</div>".$sitio."</td>
						</tr>
						<tr>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Recibido en</div>".$fechacreacion."</td>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Departamento</div>".$departamento."</td>
						</tr>
						<tr>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Asignado a</div>".$nombreasignado."</td>
							<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Prioridad</div>".$prioridad."</td>
						</tr>
					<table>
					</div>";
		//USUARIOS DE SOPORTE
		//$correo [] = 'ana.porras@maxialatam.com';
		$correo [] = 'isai.carvajal@maxialatam.com';
		//$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		$correo [] = 'maria.baena@maxialatam.com';
		
		if($row['asignadoa'] != ""){
			$correo [] = $row['asignadoa'];
		}
		
		//debugL("notificarComentariosSoportePREVENTIVOS-CORREO:".json_encode($correo),"notificarComentariosSoportePREVENTIVOS");
		
		foreach ($correo as $key => $value) { 
			$querycorreo = "SELECT * FROM notificacionesxusuarios nu
							left join usuarios u on u.id = nu.idusuario
							where u.correo = '$value' and noti9 = 1";
			$consultacorreo = $mysqli->query($querycorreo);
			if($consultacorreo->num_rows == 0){
				unset($correo[$key]);
			}
		}
		//CLIENTE AIG - USUARIOS DE PRUEBA
		if($idclientes == 13 && $visibilidad == 'Público' && $row['asignadoa'] == 'soportemaxia@zertifika.com'){
			$queryc = " SELECT correo FROM usuarios WHERE nivel = 6 AND idclientes = 13  AND estado = 'Activo' ";
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
		$resultado 	 = array();
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.id AS idproyectos, a.periodo, a.numeroaceptacion, c.id AS unidad, 
					d.id AS serie, d.activo, q.nombre as marca, r.nombre as modelo, s.nombre as areaact, e.id AS estado, f.id AS categoria, g.id AS subcategoria,  
					h.id AS prioridad, a.solicitante, a.asignadoa, a.departamento, d.modalidad, a.notificar, a.resolucion,
					CONCAT_WS('',j.id,' - ', j.titulo) AS fusionado, a.reporteservicio, a.estadomantenimiento, a.observaciones, 
					a.fechacertificar, a.horario, a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, a.comentariosatisfaccion, 
					IFNULL(k.nombre, a.resueltopor) AS resueltopor, a.fechacierre, a.horacierre, a.fechamodif, a.fechacertificar,
					IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0), a.fechacreacion,'') AS fechacreacion, a.horacreacion, 
					IF(( a.fechareal is not null OR LENGTH(ltrim(rTrim(a.fechareal))) > 0),CONCAT(a.fechareal,' ',IFNULL(a.horareal,'')),'') AS fechareal,
					IF(( a.fechavencimiento is not null OR LENGTH(ltrim(rTrim(a.fechavencimiento))) > 0),CONCAT(a.fechavencimiento,'  ', a.horavencimiento),'') AS fechavencimiento,
					IF(( a.fecharesolucion is not null OR LENGTH(ltrim(rTrim(a.fecharesolucion))) > 0),CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,
					a.horastrabajadas, n.id as idempresas, o.id as iddepartamentos, p.id as idclientes, a.idsubambientes, a.frecuencia, incorr.idcorrectivos
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN activos d ON a.idactivos = d.id
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
					LEFT JOIN subambientes s ON d.idsubambientes = s.id
					LEFT JOIN ( SELECT id AS idcorrectivos, idpreventivos FROM incidentes WHERE idpreventivos = ".$id.") incorr ON incorr.idpreventivos = a.id
					WHERE a.id = ".$id." ";	 
		
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			if($row['marca'] == '0')
				$row['idmarcas']='';
			if($row['modelo'] == '0')
				$row['idmodelos']='';
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
			$resultado[] = array(
						'id' 					=> $row['id'],
						'titulo'				=> $row['titulo'],
						'descripcion' 			=> $descripcion,
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
						'fusionado' 			=> $row['fusionado'],
						'notificar' 			=> $notificar,
						'resolucion' 			=> $row['resolucion'],						
						'reporteservicio' 		=> $row['reporteservicio'],
						'numeroaceptacion' 		=> $row['numeroaceptacion'],
						'estadomantenimiento' 	=> $row['estadomantenimiento'],
						'observaciones' 		=> $row['observaciones'],
						'horario' 				=> $row['horario'], 
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
						'fechareal' 			=> $row['fechareal'],
						'fechamodif' 			=> $row['fechamodif'],
						'fechacertificar' 		=> $row['fechacertificar'],
						'horastrabajadas' 		=> $row['horastrabajadas'],
						'periodo' 				=> $row['periodo'],
						'idsubambientes' 		=> $row['idsubambientes'],
						'frecuencia' 			=> $row['frecuencia'], 
						'areaact' 				=> $row['areaact'],
						'idcorrectivos' 		=> $row['idcorrectivos']
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
		$idambientes	 	= (!empty($data['unidadejecutora']) ? $data['unidadejecutora'] : '');
		$serie 				= (!empty($data['serie']) ? $data['serie'] : '');
		$estado 			= (!empty($data['estado']) ? $data['estado'] : 12);
		$categoria 			= (!empty($data['categoria']) ? $data['categoria'] : 0);
		$subcategoria 		= (!empty($data['subcategoria']) ? $data['subcategoria'] : 0);
		$prioridad 			= (!empty($data['prioridad']) ? $data['prioridad'] : 0);
		$origen 			= (isset($data['origen']) ? $data['origen'] : 'sistema');
		$solicitante 		= (!empty($data['solicitante']) ? $data['solicitante'] : $_SESSION['correousuario']);
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
		$idsubambientes 	= (!empty($data['area']) ? $data['area'] : '0');
		$frecuencia		 	= (!empty($data['frecuencia']) ? $data['frecuencia'] : '');	
		$estadoInc 			= '';
		$idusuario 			= $_SESSION['user_id'];
		$nivel	 			= $_SESSION['nivel'];
	   	if (is_array($idsubambientes))
		$idsubambientes = implode(',',$idsubambientes);
		
		
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
		if($idclientes == 0 && ($nivel == 4 || $nivel == 7) ){
			$queryCU  	 = " SELECT idclientes FROM usuarios WHERE id = '".$idusuario."' ";
			$resultCU 	 = $mysqli->query($queryCU);
			$rowCU 	 	 = $resultCU->fetch_assoc();
			$idclientes = $rowCU['idclientes'];
	 	}
		if($idambientes !=""){
			$idambientes = $idambientes;
		}else{
			$idambientes = 0;
		}
		$query = "  INSERT INTO incidentes(id, titulo, descripcion, idambientes, idsubambientes, tipo, idactivos, idestados,
					idcategorias, idsubcategorias, idprioridades, origen, creadopor, solicitante, asignadoa, 
					departamento, fechacreacion, ";
		
		if($fechavencimiento != ''){
			$query .= "fechavencimiento, horavencimiento, ";
		}
		//$idsubambientes = 0;
		$query .="  horacreacion, notificar, resolucion, reporteservicio, estadomantenimiento, 
					observaciones, fechacertificar, horario, fechareal, horareal, idempresas, idclientes, idproyectos, iddepartamentos, frecuencia)
					VALUES(null, '$titulo', '$descripcion',".$idambientes.", ".$idsubambientes.", 'preventivos', '$serie', 
					'$estado','$categoria', '$subcategoria', '$prioridad', '$origen', '$creadopor', 
					'$solicitante', '$asignadoa', '$departamento', '$fechacreacion', ";
		if($fechavencimiento !=''){
			$query .= "'$fechavencimiento', '$horavencimiento',  ";
		}	  
		$query .= " '$horacreacion', '$notificar', '$resolucion', '$reporteservicio', '$estadomtto', 
					'$observaciones', '$fechacertificar', '$horario','$fechareal', '$horacreacion',
					'$idempresas', '$idclientes', '$idproyectos', '$iddepartamentos','$frecuencia') ";		 
		
		//debug($query);
		if($mysqli->query($query)){
			$id = $mysqli->insert_id;
			if($id != ''){ 
				//CREAR REGISTRO EN ESTADOS INCIDENTES
				//$queryE = " INSERT INTO incidentesestados VALUES(null, $id, 12, '$estado', $idusuario, now(), now(), 0) ";
				$queryE = " INSERT INTO incidentesestados (idincidentes,estadoanterior,estadonuevo,usuario,fechadesde,horadesde,dias)
							VALUES(".$id.", 12, '".$estado."', ".$idusuario.", now(), now(), 0) ";
				$mysqli->query($queryE);
				
				//CREAR CARPETA DE ID INCIDENTES Y COMENTARIOS
				$myPath = '../incidentes/';
				if (!file_exists($myPath))
					mkdir($myPath, 0777);
				$myPath = '../incidentes/'.$id.'/';
				$target_path2 = utf8_decode($myPath);
				if (!file_exists($target_path2))
					mkdir($target_path2, 0777);
				
				if($_SESSION['nivel'] == 4){
					//MOVER DEL TEMP A INCIDENTES
					$num 	= $_SESSION['user_id'];
					$from 	= '../incidentestemp/'.$num;
					$to 	= '../incidentes/'.$id.'/';
					
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
				//ENVIAR CORREO AL CREADOR DEL INCIDENTE
				nuevoincidente($_SESSION['usuario'], $titulo, $descripcion, $id, $fechacreacion, $horacreacion, $solicitante, $creadopor);
				notificarCEstado($id,'','creado','',$estado); //$incidente,$notificar,$accion,$estadoold,$estadonew
				
				if($prioridad == '6'){
					//fueradeservicio($id,$serie);
					$queryfs  = "UPDATE activos set estado = 'INACTIVO' WHERE serie = '$serie' ";
					$resultfs = $mysqli->query($queryfs);
					$queryfs  = "INSERT INTO fueraservicio VALUES(null, '$serie', '$fechacreacion', null, $id) ";
					$resultfs = $mysqli->query($queryfs);
				}				
			}
			$accion = 'El Preventivo #'.$id.' ha sido Creado exitosamente';
			bitacora($_SESSION['usuario'], "Preventivos", $accion, $id, $query);

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
		$iddepartamentos	= (!empty($data['iddepartamentos']) ? $data['iddepartamentos'] : '0');
		$idclientes 		= (!empty($data['idclientes']) ? $data['idclientes'] : '0');
		$idproyectos 	    = (!empty($data['idproyectos']) ? $data['idproyectos'] : '0');
		$idubicacion		= (!empty($data['unidadejecutora']) ? $data['unidadejecutora'] : '');
		$serie 				= (!empty($data['serie']) ? $data['serie'] : '');
		$estado 			= (!empty($data['estado']) ? $data['estado'] : 12);
		$categoria 			= (!empty($data['categoria']) ? $data['categoria'] : '0');
		$subcategoria 		= (!empty($data['subcategoria']) ? $data['subcategoria'] : '0');
		$prioridad 			= (!empty($data['prioridad']) ? $data['prioridad'] : '0');
		$origen 			= (isset($data['origen']) ? $data['origen'] : 'sistema');
		$solicitante 		= (!empty($data['solicitante']) ? $data['solicitante'] : '');
		$creadopor			= (!empty($data['creadopor']) ? $data['creadopor'] : $_SESSION['correousuario']);
		$asignadoa 			= (!empty($data['asignadoa']) ? $data['asignadoa'] : '');
		$departamento 		= (!empty($data['departamento']) ? $data['departamento'] : '');
		$notificar 			= (!empty($data['notificar']) ? $data['notificar'] : '');
		$resolucion 		= (!empty($data['resolucion']) ? $data['resolucion'] : '');
		$reporteservicio 	= (!empty($data['reporteservicio']) ? $data['reporteservicio'] : '');
		$numeroaceptacion 	= (!empty($data['numeroaceptacion']) ? $data['numeroaceptacion'] : '');
		$estadomtto			= (!empty($data['estadomantenimiento']) ? $data['estadomantenimiento'] : '');
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
		//$horastrabajadas 	= (!empty($data['horastrabajadas_editar']) ? $data['horastrabajadas_editar'] : '0');
		$horast_editar	  	= (!empty($data['horast']) ? $data['horast'] : '00');
		$minutost_editar	= (!empty($data['minutost']) ? $data['minutost'] : '00');
		$idsubambientes		= (!empty($data['area']) ? $data['area'] : '0');		 			   
		$estadoInc 			= '';
		$idusuario 			= $_SESSION['user_id'];
		$frecuencia 		= (!empty($data['frecuencia']) ? $data['frecuencia'] : '');
		$nuevoprev   		= (!empty($_REQUEST['nuevoprev']) ? $_REQUEST['nuevoprev'] : 0);	
		
		if (is_array($idsubambientes))
		$idsubambientes = implode(',',$idsubambientes);
		//Horas trabajadas
		if($horast_editar == '00' && $minutost_editar == '00'){
			$horastrabajadas = '0';
		}else{
			$horastrabajadas = $horast_editar.':'.$minutost_editar;
		}
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
		$fechareal = str_replace("'","",$fechareal);
		$horareal  = str_replace("'","",$horareal); 
		$fecharesolucion = str_replace("'","",$fecharesolucion);
		$horaresolucion  = str_replace("'","",$horaresolucion);
		$fechacierre 	 = str_replace("'","",$fechacierre);
		$horacierre 	 = str_replace("'","",$horacierre);
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
		$queryInc = $mysqli->query("SELECT idestados, asignadoa FROM incidentes WHERE id = '$id'");
		while ($rowInc = $queryInc->fetch_assoc()) {
			$estadoInc = $rowInc['idestados'];
			$asignadoaInc = $rowInc['asignadoa']; 
		} 
		
		$fecharbit = $fecharesolucion;
		if($fecharbit == 'null'){
			$fecharbit = '';
		}
		
		$descripcion = str_replace("'","",$descripcion); 
		$campos = array(
			'Título' 				=> $titulo,
			'Descripción' 			=> $descripcion, 
			'Clientes' 				=> getValor('nombre','clientes',$idclientes),
			'Proyectos' 			=> getValor('nombre','proyectos',$idproyectos),
			'Categorías' 			=> getValor('nombre','categorias',$categoria),
			'Subcategorías' 		=> getValor('nombre','subcategorias',$subcategoria), 
			'Ubicación' 			=> getValor('nombre','ambientes',$idambientes), 
	//		'Área'			 		=> getValor('nombre','subambientes',$idsubambientes), 
			'Serie' 				=> getValor('serie','activos',$serie),
			'Departamentos' 		=> getValor('nombre','departamentos',$iddepartamentos),
			'Asignado a' 			=> getValorEx('nombre','usuarios',$asignadoa,'correo'),
			'Estado' 				=> getValor('nombre','estados',$estado),
			'Prioridad' 			=> getValor('prioridad','sla',$prioridad),
			//'Origen' 				=> $origen,
			'Solicitante' 			=> getValorEx('nombre','usuarios',$solicitante,'correo'),
			//'Numero de aceptación' 	=> $numeroaceptacion,
			//'Estado de mtto.'		=> getValor('nombre','estados',$estadomtto),
			//'Observaciones' 		=> $observaciones,
			//'Horario' 				=> $horario,
			//'Fecha de vencimiento'	=> $fechavencimiento,
			//'Hora de vencimiento' 	=> $horavencimiento,
			'Fecha de resolución'	=> $fecharbit,
			//'Fecha de cierre' 		=> $fechacierre,
			//'Hora de cierre' 		=> $horacierre,
			//'Fecha para certificar'	=> $fechacertificar,
			'Fecha de creación' 	=> $fechacreacion,
			'Hora de creación' 		=> $horacreacion,
			'Resolución' 			=> $resolucion,
			'Reporte de servicio' 	=> $reporteservicio,
			'Horas trabajadas'		=> $horastrabajadas
		);
		
		$valoresold = getRegistroSQL("	SELECT a.titulo as 'Título', a.descripcion as 'Descripción', e.nombre as 'Clientes', 
										f.nombre as Proyectos, h.nombre as 'Categorías', i.nombre as 'Subcategorías', und.nombre AS 'Ubicación', sub.nombre AS 'Subambientes', act.serie as 'Serie', k.nombre as Departamentos, n.nombre as 'Asignado a', o.nombre as Estado, p.prioridad as Prioridad, q.nombre as Solicitante, a.fecharesolucion as 'Fecha de resolución',  a.fechacreacion as 'Fecha de creación', a.horacreacion as 'Hora de creación', a.resolucion as 'Resolución', a.reporteservicio as 'Reporte de servicio', a.horastrabajadas as 'Horas trabajadas'
										FROM incidentes a
										LEFT JOIN ambientes und ON a.idambientes = und.id
										LEFT JOIN subambientes sub ON sub.id = a.idsubambientes
										LEFT JOIN empresas d ON a.idempresas = d.id
										LEFT JOIN clientes e ON a.idclientes = e.id
										LEFT JOIN proyectos f ON a.idproyectos = f.id
										LEFT JOIN categorias h ON a.idcategorias = h.id
										LEFT JOIN subcategorias i ON a.idsubcategorias = i.id
										LEFT JOIN departamentos k ON a.iddepartamentos = k.id 
										LEFT JOIN usuarios n ON a.asignadoa = n.correo
										LEFT JOIN estados o ON a.idestados = o.id
										LEFT JOIN sla p ON a.idprioridades = p.id
										LEFT JOIN usuarios q ON a.solicitante = q.correo 
										LEFT JOIN activos act ON a.idactivos = act.id
										WHERE a.id = '".$id."' ");
		
		$query = " UPDATE incidentes SET ";
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
		if(isset($data['unidadejecutora'])){
			$query .= ", idambientes = '$idubicacion' ";
		}
		if(isset($data['area'])){
			$query .= ", idsubambientes = '$idsubambientes' ";
		} 
		if(isset($data['serie'])){
			$query .= ", idactivos = '$serie' ";
		}
		if(isset($data['estado'])){
			$query .= ", idestados = '$estado' ";
		}
		if(isset($data['categoria'])){
			$query .= ", idcategorias = '$categoria' ";
		}
		if(isset($data['subcategoria'])){
			$query .= ", idsubcategorias = '$subcategoria' ";
		}
		if(isset($data['prioridad'])){
			$query .= ", idprioridades = '$prioridad' ";
		}
		if(isset($data['origen'])){
			$query .= ", origen = '$origen' ";
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
		if(isset($data['reporteservicio'])){
			$query .= ", reporteservicio = '$reporteservicio' ";
		}
		if(isset($data['numeroaceptacion'])){
			$query .= ", numeroaceptacion = '$numeroaceptacion' ";
		}
		if(isset($data['estadomantenimiento'])){
			$query .= ", estadomantenimiento = '$estadomtto' ";
		}
		if(isset($data['observaciones'])){
			$query .= ", observaciones = '$observaciones' ";
		}
		if(isset($data['horario'])){
			$query .= ", horario = '$horario' ";
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
		if(isset($data['horacierre']) && $data['horacierre'] != null){
			$query .= ", horacierre = '$horacierre' ";
		}
		if(isset($data['fechacertificar'])){
			$query .= ", fechacertificar = '$fechacertificar' ";
		}
		if(isset($data['fechacreacion'])){
			$query .= ", fechacreacion = '$fechacreacion' ";
		}
		if(isset($data['horacreacion'])){
			$query .= ", horacreacion = '$horacreacion' ";
		}
		if($horastrabajadas!=""){
			$query .= ", horastrabajadas = '$horastrabajadas' ";
		}
		if($estado < $estadoInc && $estado != '34' ){
			$query .= " , estadoant = '1' ";
		}
		if($estadoInc != 16 && $estado == '16' ){
			$query .= " , resueltopor = '".$_SESSION['correousuario']."' ";
		}
		if ($estado!='' && $estado == 16) {
			$query .= " , fechafinfueraservicio = current_timestamp ";			
		}
		if(isset($data['fechareal'])){
			$query .= ", fechareal = '$fechareal' ";								
		}
		if(isset($data['frecuencia'])){
			$query .= ", frecuencia = '$frecuencia' ";
		} 		
		$query .= " WHERE id = $id ";
		$query = str_replace('SET ,','SET ',$query);
		//echo $query;	
		if($mysqli->query($query)){
			//Verificar si fecharesolucion es vacía
			if($estado == 16 && (isset($data['fecharesolucion']) && $data['fecharesolucion'] != null) && ($horaresolucion != null && $horaresolucion != 'null')){
				//Verifico si el incidente está fusionado con otros incidentes
				$queryF = " SELECT GROUP_CONCAT(id) AS fusionados FROM incidentes WHERE fusionado = '$id' ";
				$resultF = $mysqli->query($queryF); 
				if($rowF = $resultF->fetch_assoc()){
					$fusionados = $rowF['fusionados'];
					if($fusionados != "" && $fusionados != null){
						//Actualizo fecha de resolución de incidentes fusionados
						$queryR = " UPDATE incidentes SET";
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
			}
			
			
			//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
			if($estadoInc != $estado){
				//Creación de próximo mantenimiento preventivo según frecuencia
				
				$sqlE = " SELECT id FROM estados WHERE nombre = 'Resuelto' AND tipo = 'preventivo' ";
				$rsqlE = $mysqli->query($sqlE);
				if($rowE = $rsqlE->fetch_assoc()){
					$epResuelto = $rowE["id"];
				}
				if($estado == $epResuelto){
					 
					if($frecuencia != "" && $nuevoprev == 1){
						// Función WEEKDAY 5 --> Sábado, 6 --> Domingo
						
						$fechaCrea = "SELECT 
									fecharesolucion,
									CASE 
										WHEN frecuencia = 'diaria' THEN
											(CASE 
												WHEN WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 1 DAY)) = 5 THEN DATE_ADD(fecharesolucion,INTERVAL 3 DAY) WHEN 				WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 1 DAY)) = 6 THEN DATE_ADD(fecharesolucion,INTERVAL 2 DAY) else DATE_ADD(fecharesolucion,INTERVAL 1 DAY) END)
										WHEN frecuencia = 'semanal' THEN
											(CASE 
												WHEN WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 7 DAY)) = 5 THEN DATE_ADD(fecharesolucion,INTERVAL 9 DAY) WHEN 				WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 7 DAY)) = 6 THEN DATE_ADD(fecharesolucion,INTERVAL 8 DAY) else DATE_ADD(fecharesolucion,INTERVAL 7 DAY) END)
										WHEN frecuencia = 'quincenal' THEN
											(CASE 
												WHEN WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 15 DAY)) = 5 THEN DATE_ADD(fecharesolucion,INTERVAL 17 DAY) WHEN 				WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 15 DAY)) = 6 THEN DATE_ADD(fecharesolucion,INTERVAL 16 DAY) else DATE_ADD(fecharesolucion,INTERVAL 15 DAY) END)
										WHEN frecuencia = 'mensual' THEN 
											(CASE 
												WHEN WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 30 DAY)) = 5 THEN DATE_ADD(fecharesolucion,INTERVAL 32 DAY) WHEN 				WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 30 DAY)) = 6 THEN DATE_ADD(fecharesolucion,INTERVAL 31 DAY) else DATE_ADD(fecharesolucion,INTERVAL 30 DAY) END)
										WHEN frecuencia = 'bimestral' THEN 
											(CASE 
												WHEN WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 60 DAY)) = 5 THEN DATE_ADD(fecharesolucion,INTERVAL 62 DAY) WHEN 				WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 60 DAY)) = 6 THEN DATE_ADD(fecharesolucion,INTERVAL 61 DAY) else DATE_ADD(fecharesolucion,INTERVAL 60 DAY) END)
										 WHEN frecuencia = 'trimestral' THEN 
											(CASE 
												WHEN WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 90 DAY)) = 5 THEN DATE_ADD(fecharesolucion,INTERVAL 92 DAY) WHEN 				WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 90 DAY)) = 6 THEN DATE_ADD(fecharesolucion,INTERVAL 91 DAY) else DATE_ADD(fecharesolucion,INTERVAL 90 DAY) END)
										WHEN frecuencia = 'cuatrimestral' THEN 
											(CASE 
												WHEN WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 120 DAY)) = 5 THEN DATE_ADD(fecharesolucion,INTERVAL 122 DAY) WHEN 				WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 120 DAY)) = 6 THEN DATE_ADD(fecharesolucion,INTERVAL 121 DAY) else DATE_ADD(fecharesolucion,INTERVAL 120 DAY) END)
										WHEN frecuencia = 'pentamestral' THEN 
											(CASE 
												WHEN WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 150 DAY)) = 5 THEN DATE_ADD(fecharesolucion,INTERVAL 152 DAY) WHEN 				WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 150 DAY)) = 6 THEN DATE_ADD(fecharesolucion,INTERVAL 151 DAY) else DATE_ADD(fecharesolucion,INTERVAL 150 DAY) END)
										WHEN frecuencia = 'semestral' THEN 
											(CASE 
												WHEN WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 180 DAY)) = 5 THEN DATE_ADD(fecharesolucion,INTERVAL 182 DAY) WHEN 				WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 180 DAY)) = 6 THEN DATE_ADD(fecharesolucion,INTERVAL 181 DAY) else DATE_ADD(fecharesolucion,INTERVAL 180 DAY) END)
										WHEN frecuencia = 'anual' THEN 
											(CASE 
												WHEN WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 365 DAY)) = 5 THEN DATE_ADD(fecharesolucion,INTERVAL 367 DAY) WHEN 				WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 365 DAY)) = 6 THEN DATE_ADD(fecharesolucion,INTERVAL 366 DAY) else DATE_ADD(fecharesolucion,INTERVAL 365 DAY) END)
                                        WHEN frecuencia = '18meses' THEN 
											(CASE 
												WHEN WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 545 DAY)) = 5 THEN DATE_ADD(fecharesolucion,INTERVAL 547 DAY) WHEN 				WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 545 DAY)) = 6 THEN DATE_ADD(fecharesolucion,INTERVAL 546 DAY) else DATE_ADD(fecharesolucion,INTERVAL 545 DAY) END)
                                        WHEN frecuencia = '24meses' THEN 
											(CASE 
												WHEN WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 730 DAY)) = 5 THEN DATE_ADD(fecharesolucion,INTERVAL 732 DAY) WHEN 				WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 730 DAY)) = 6 THEN DATE_ADD(fecharesolucion,INTERVAL 731 DAY) else DATE_ADD(fecharesolucion,INTERVAL 730 DAY) END)
                                        WHEN frecuencia = '36meses' THEN 
											(CASE 
												WHEN WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 1095 DAY)) = 5 THEN DATE_ADD(fecharesolucion,INTERVAL 1097 DAY) WHEN 			WEEKDAY(DATE_ADD(fecharesolucion,INTERVAL 1095 DAY)) = 6 THEN DATE_ADD(fecharesolucion,INTERVAL 1096 DAY) else DATE_ADD(fecharesolucion,INTERVAL 1095 DAY) END)
										END as fecharesolucioncambiada
								FROM incidentes WHERE id =".$id."";
						//debugL("QUERY SUMAR DIAS ES:".$fechaCrea);
						$rFechaC = $mysqli->query($fechaCrea);
						if($rFechaC->num_rows >0){
								
							if($asignadoa != ''){
								$idestados = 13;
							}else{
								$idestados = 12;
							}
							
							//Asignar fecha de creación al próximo preventivo
							$rFC = $rFechaC->fetch_assoc(); 
							$fechacreacionNvo = $rFC['fecharesolucioncambiada'];
							
							$crearPrev  = " INSERT INTO incidentes (id, titulo, descripcion, idempresas, idclientes, idproyectos, idcategorias, idsubcategorias, iddepartamentos, idambientes, idsubambientes, origen, tipo, idactivos, idprioridades, idestados, creadopor, solicitante, asignadoa, fechacreacion, horacreacion, fechareal, horareal, horario, frecuencia)
							VALUES (null, '".$titulo."', '".$descripcion."', ".$idempresas.", ".$idclientes.", ".$idproyectos.", ".$categoria.", ".$subcategoria.", ".$iddepartamentos.", '".$idubicacion."', ".$idsubambientes.", 'sistema', 'preventivos', '".$serie."', ".$prioridad.", ".$idestados.", '".$solicitante."', '".$solicitante."', '".$asignadoa."', '".$fechacreacionNvo."', '".$horacreacion."', '".$fechacreacionNvo."', '".$horacreacion."', '".$horario."', '".$frecuencia."') ";
							debugL($crearPrev,"crearpreventivo"); 
							$rPrev = $mysqli->query($crearPrev);
						}  
					} 
				}		  
				//CREAR REGISTRO EN ESTADOS INCIDENTES
				$queryE = " SELECT id, estadonuevo, fechadesde FROM incidentesestados WHERE idincidentes = '".$id."' ORDER BY id DESC LIMIT 1 ";
				$resultE = $mysqli->query($queryE);
				if($resultE->num_rows >0){
					//Busca último registro en Incidentesestados para tomar fechadesde
					$rowE = $resultE->fetch_assoc();
					$estadoanterior = $estadoInc;
					$fechadesde = $rowE['fechadesde'];
					$idIncEstad = $rowE['id'];
				}else{
					//Si no hay registros en Incidentesestados, se crea registro con fechadesde = fechacreacion
					$estadoanterior = $estadoInc;
					$qfechac = " SELECT fechacreacion FROM incidentes WHERE id = '".$id."' ";
					$rfechac = $mysqli->query($qfechac);
					$regf = $rfechac->fetch_assoc();
					$fechadesde = $regf['fechacreacion'];
					$idIncEstad = "";
				}
				
				//Calcula número de días
				$fechahoy = date('Y-m-d');
				$date1 = new DateTime($fechahoy);
				$date2 = new DateTime($fechadesde);
				$diff = $date1->diff($date2);
				$dias = $diff->days;
				
				if($idIncEstad != ""){
					$queryIncE = " UPDATE incidentesestados SET fechahasta = CURDATE(), horahasta = CURTIME() WHERE idincidentes = ".$id." AND id = ".$idIncEstad."";
					$mysqli->query($queryIncE);
				}
				$queryE = " INSERT INTO incidentesestados (idincidentes,estadoanterior,estadonuevo,usuario,fechadesde,horadesde,dias)
							VALUES (".$id.", '".$estadoanterior."', '".$estado."', ".$idusuario.", now(), now(), ".$dias.") ";
				debugL('queryE:'.$queryE);
				$mysqli->query($queryE);
				/* $queryE = " SELECT estadonuevo, fechacambio FROM incidentesestados WHERE idincidentes = '$id' ORDER BY id DESC LIMIT 1 ";
				$resultE = $mysqli->query($queryE);
				if($resultE->num_rows >0){
					$rowE = $resultE->fetch_assoc();
					$estadoanterior = $estadoInc;
					$fechacambio = $rowE['fechacambio'];
				}else{
					$estadoanterior = $estadoInc;
					$qfechac = " SELECT fechacreacion FROM incidentes WHERE id = $id ";
					$rfechac = $mysqli->query($qfechac);
					$regf = $rfechac->fetch_assoc();
					$fechacambio = $regf['fechacreacion'];
				}
				
				$fechahoy = date('Y-m-d');
				$date1 = new DateTime($fechahoy);
				$date2 = new DateTime($fechacambio);
				$diff = $date1->diff($date2);
				$queryE = " INSERT INTO incidentesestados VALUES(null, $id, '$estadoanterior', '$estado', $idusuario, now(), now(), $diff->days) ";
				$mysqli->query($queryE); */
				
				if($estado == 13){
					$query = "SELECT idproyectos FROM usuarios WHERE correo = '$asignadoa' ";
					$result = $mysqli->query($query);
					if($result->num_rows >0){
						$row = $result->fetch_assoc();				
						$proyectosusu = $row['idproyectos'];
					}
					//ACTUALIZAR INCIDENTE
					$queryUP = "UPDATE incidentes SET idproyectos = '$idproyectos' WHERE id = $id ";
					$resultUP = $mysqli->query($queryUP);
				}
				notificarCEstado($id,$notificar,'actualizado',$estadoInc,$estado); //$incidente,$notificar,$accion,$estadoold,$estadonew
				if($prioridad == '6' && ($estado == 16 || $estado == 17)){
					$queryfs  = "UPDATE activos set estado = 'ACTIVO' WHERE serie = '$serie' ";
					$resultfs = $mysqli->query($queryfs);
					$queryfs  = "UPDATE fueraservicio set hasta = $fecharesolucion WHERE  incidente = $id ";
					$resultfs = $mysqli->query($queryfs);
				}
			}
			//Bitácora
			actualizarRegistro('Preventivos','Preventivo',$id,$valoresold,$campos,$query);
			//$accion = 'El Preventivo #'.$id.' ha sido actualizado exitosamente';
			//bitacora($_SESSION['usuario'], "Preventivos", $accion, $id, $query);
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
				$query .= "UPDATE incidentes SET ";
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
						}elseif($c == 'categoriamas'){
							$query .= " idcategorias = '$v' ";
						}elseif($c == 'subcategoriamas'){
							$query .= " idsubcategorias = '$v' ";
						}elseif($c == 'prioridadmas'){
							//FECHA CREACION
							$queryFC  			= " SELECT fechacreacion, horacreacion FROM incidentes WHERE id = '$id' ";
							$resultFC 			= $mysqli->query($queryFC);
							$rowFC 				= $resultFC->fetch_assoc();
							$fechacreacion		= $rowFC['fechacreacion'];
							$horacreacion		= $rowFC['horacreacion'];
							//SLA
							$queryV  			= " SELECT dias, horas FROM sla WHERE id = '$v' ";
							$resultV 			= $mysqli->query($queryV);
							$rowV 				= $resultV->fetch_assoc();
							$diasP 				= $rowV['dias'];
							$horasP 			= $rowV['horas'];
							$fechavencimiento 	= date('Y-m-d', strtotime($fechacreacion."+ ".$diasP." days"));
							$horavencimiento  	= date('H:i:s', strtotime($horacreacion." + ".$horasP." hours"));
							$query .= " idprioridades = '$v', fechavencimiento = '$fechavencimiento', horavencimiento = '$horavencimiento' ";
						}elseif($c == 'unidadejecutoramas'){
							$query .= " idambientes = '$v' ";
						}elseif($c == 'seriemas'){
							$query .= " idactivos = '$v' ";
						}elseif($c == 'asignadoamas'){
							$query .= " asignadoa = '$v' ";
						}elseif($c == 'estadomas'){
							$query .= " idestados = '$v' ";
						}elseif($c == 'fecharesolucionmas'){
							
							$fecharesolucion = preg_split("/[\s,]+/",$v);
							$horaresolucion  = "'".$fecharesolucion[1]."'";
							$fecharesolucion = "'".$fecharesolucion[0]."'";
							
							$fecharesolucion = str_replace("'","",$fecharesolucion);
							$horaresolucion  = str_replace("'","",$horaresolucion);
		
							$query .= "fecharesolucion = '$fecharesolucion', horaresolucion = '$horaresolucion' ";
						
						}elseif($c == 'resolucionmas'){
							$query .= " resolucion = '$v' ";		   
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
								bitacora($_SESSION['usuario'], "Preventivos", 'El Preventivo #'.$id.' ha sido Editado exitosamente', $id, $query2);
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
	function nuevoincidente($usuario, $titulo, $descripcion, $incidente, $fecha, $hora, $solicitante, $creadopor){
		global $mysqli, $mail;
		
		//SOLICITANTE
		if($solicitante !=''){
			if (filter_var($solicitante, FILTER_VALIDATE_EMAIL)) {
				$result = $mysqli->query("SELECT correo FROM usuarios WHERE correo = '".$solicitante."' AND estado = 'Activo' ");
				if ($row=$result->fetch_assoc()) {
					$correo [] = $solicitante;
				} 
			}else{
				$result = $mysqli->query("SELECT correo FROM usuarios WHERE correo = '".$solicitante."' AND estado = 'Activo'");
				while ($row=$result->fetch_assoc()) {
					$correo [] = $row['correo'];
				}
			}
			//Asunto
			$innovacion = 'soporteaig@innovacion.gob.pa';
			if($solicitante == $innovacion || $creadopor == $innovacion){
				$asunto = $titulo;
			}else{
				$asunto = "Preventivo #$incidente ha sido Creado";
			}
			
			//Cuerpo
			$fecha = implode('/',array_reverse(explode('-', $fecha)));
			$cuerpo = '';		
			$cuerpo .= "<div style='background-color: #FFFFFF; margin: 0 6%; padding: 1% 2%; color: #3e4954;'><div style='text-align: right;'><b>Fecha:</b> ".$fecha."</div>";
			$cuerpo .= "<br><b>".$titulo."</b>";
			$cuerpo .= "<p style='width: 100%;'>Buen día,<br>Gracias por contactar al Centro de Soporte, su caso ha sido asignado a nuestros Ingenieros especializados quienes los contactarán brevemente para mas detalles sobre el caso.<p></div>";
			
			debugL("nuevoincidentePREVENTIVO-CORREO:".json_encode($correo),"nuevoincidentePREVENTIVO");		
			if(!empty($correo)){
				//Correo
				enviarMensajeIncidente($asunto,$cuerpo,$correo,'','');
			}
		}
	}

	//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
	function notificarCEstado($incidente,$notificar,$accion,$estadoold,$estadonew){
		global $mysqli;
		
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, c.nombre AS ambiente,
					d.serie, q.nombre AS marca, r.nombre AS modelo, e.nombre AS estado, f.id AS idcategoria, f.nombre AS categoria, g.nombre AS subcategoria,
					h.prioridad, a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, IFNULL(j.nombre, a.solicitante) AS solicitante, CASE WHEN l.estado = 'Activo' THEN a.asignadoa WHEN l.estado = 'Inactivo' THEN '' END AS asignadoa, l.usuario AS usuarioasignadoa,
					a.departamento, d.modalidad, a.satisfaccion, a.comentariosatisfaccion, a.resolucion, IFNULL(k.nombre, a.resueltopor) AS resueltopor,
					IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0),CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion,
					IF(( a.fechavencimiento is not null OR LENGTH(ltrim(rTrim(a.fechavencimiento))) > 0),CONCAT(a.fechavencimiento,'  ', a.horavencimiento),'') AS fechavencimiento,
					IF(( a.fecharesolucion is not null OR LENGTH(ltrim(rTrim(a.fecharesolucion))) > 0),CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,
					IF(( a.fechacierre is not null OR LENGTH(ltrim(rTrim(a.fechacierre))) > 0),CONCAT(a.fechacierre,'  ', a.horacierre),'') AS fechacierre, a.fechamodif, a.fechacertificar, 
					a.horastrabajadas, a.comentariovisto, CASE WHEN i.estado = 'Activo' THEN IFNULL(i.correo, a.creadopor)
					WHEN i.estado = 'Inactivo' THEN '' END AS correocreadopor, i.usuario AS usuariocreadopor, a.notificar,
					CASE  WHEN j.estado = 'Activo' THEN IFNULL(j.correo, a.solicitante) WHEN j.estado = 'Inactivo' THEN '' END AS correosolicitante, j.usuario AS usuariosolicitante, a.idclientes, a.idproyectos
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN activos d ON a.idactivos = d.id
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.resueltopor = k.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN marcas q ON d.idmarcas = q.id
					LEFT JOIN modelos r ON d.idmodelos = r.id
					WHERE a.id = $incidente GROUP BY a.id ";
		//debug($query);			
		$result = $mysqli->query($query);
		$row 	= $result->fetch_assoc();
		$idclientes = $row['idclientes'];
		$idproyectos = $row['idproyectos'];
		//1 para quien quien creo el incidentes (Creado por)
		
		//Excluir usuarios inactivos campo Creado por 
		if($row['correocreadopor'] != ""){
			$correo [] = $row['correocreadopor'];
		}
		$notificar 	= $row['notificar'];
		
		//2 para quien solicito o reporto el incidente (Solicitante)
		if($estadonew == 16 || $estadonew == 17){
			if($row['correosolicitante'] != 'mesadeayuda@innovacion.gob.pa'){
				
				//Excluir usuarios inactivos campo Solicitante
				if($row['correosolicitante'] != ""){
					$correo [] = $row['correosolicitante'];
				} 
			}			
		}
		
		//3 para quien se le asigno el incidente (Asignado a)	
		
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
				$query2 .= "correo = '".$row['asignadoa']."'  AND estado = 'Activo' ";
			}else{
				$query2 .= "correo IN (".$row['asignadoa'].")  AND estado = 'Activo'  ";
			}
			$consulta = $mysqli->query($query2);
			while($rec = $consulta->fetch_assoc()){
				$asignadoaN .= $rec['nombre']." , ";
			}
		}
		//CLIENTE AIG - USUARIOS DE PRUEBA
		if($idclientes == 13 && $estadonew == 32 && $row['asignadoa'] == 'soportemaxia@zertifika.com'){
			$queryc = " SELECT correo FROM usuarios WHERE nivel = 6 AND idclientes = 13 AND estado = 'Activo' ";
			$consultac = $mysqli->query($queryc);
			while($recc = $consultac->fetch_assoc()){
				$correo [] = $recc['correo'];	
			}
		}
		//NOTIFICACION ZERTIFIKA
		if($idclientes == 13 && $estadonew == 26 && $row['asignadoa'] == 'soportemaxia@zertifika.com'){
			$correo [] = 'soportemaxia@zertifika.com';
		}
		
		//ENVIAR CORREO DEL INCIDENTE A LOS USUARIOS SELECCIONADOS
		//4 para los usuarios que quieren que se les notifique (Enviar Notificacion a)
		if($notificar != '[]' && $notificar != ''){
			$asunto    = "Notificación del Incidente #$incidente";
			$notificar = json_decode($notificar);
			if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
				if( $notificar != 'mesadeayuda@innovacion.gob.pa' ){
					
					//Excluir usuarios inactivos campo Notificar a 
					$queryn = " SELECT usuario, correo FROM usuarios WHERE correo = '".$notificar."' AND estado = 'Activo' ";
					$consultan = $mysqli->query($queryn);
					if($recn = $consultan->fetch_assoc()){
						$correo [] = $notificar;	
						$usuarionotificar = $recn['usuario'];
					}
				}
			}else{
				foreach($notificar as $notif){
					if( $notif != 'mesadeayuda@innovacion.gob.pa' ){
							
						//Excluir usuarios inactivos campo Notificar a 
						$queryn = " SELECT usuario, correo FROM usuarios WHERE correo = '".$notificar."' AND estado = 'Activo' ";
						$consultan = $mysqli->query($queryn);
						if($recn = $consultan->fetch_assoc()){
							$correo [] = $notif;	
							$usuarionotificar = $recn['usuario'];
						}
					}
				}
			}
		}
		//else{
			if($accion == 'creado'){
				$asunto = "Preventivo #$incidente ha sido Creado";
			}else{ //actualizado
				if ($estadoold != $estadonew && $estadonew == 13)
					$asunto = "Preventivo #$incidente ha sido Asignado";			
				elseif ($estadoold != $estadonew && $estadonew == 16)
					$asunto = "Preventivo #$incidente ha sido Resuelto";
				else
					$asunto = "Preventivo #$incidente ha sido Actualizado";
			}
		//}
		//DATOS DEL CORREO
		$usuarioSes = $_SESSION['usuario'];
		$consultaUA = $mysqli->query("SELECT nombre FROM usuarios WHERE usuario = '$usuarioSes' LIMIT 1 ");
		while ($registroUA = $consultaUA->fetch_assoc()) {
			$usuarioAct = $registroUA['nombre'];
		}
		//ESTADO ANTERIOR
		$estadoant = '';
		if($estadoold != ''){
			$consultaEO = $mysqli->query("SELECT nombre FROM estados WHERE id = '".$estadoold."' ");
			if($estadonew != ''){
				$registroEO = $consultaEO->fetch_assoc();
				$estadoant = $registroEO['nombre'];
			}
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
		$creadopor		= $row['creadopor'];
		$departamento	= $row['departamento'];
		$prioridad		= $row['prioridad'];
		$sitio 			= $row['ambiente'];
		$resolucion 	= $row['resolucion'];
		$nasignadoa 	= $asignadoaN;
		//MENSAJE
		if($accion == 'creado'){
			$mensaje = "<div style='background-color: #FFFFFF; margin: 0 6%; padding: 30px;font-family: arial,sans-serif;'>
					<div style='font-size: 22px; color: #333; margin: 4% 0 4% 4%;'>".$usuarioAct." ha creado el preventivo #".$incidente."</div>";
		}else{ //actualizado
			$mensaje = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<div style='font-size: 22px; color: #333; margin: 4% 0 4% 4%;'>".$usuarioAct." ha actualizado el preventivo #".$incidente."</div>";		
		}		
		
		if($estadonew == 13){
			$mensaje .= "<p style='padding-left: 30px;width:100%;'>El preventivo ha sido asignado a: ".$nasignadoa."</p>";
		}elseif($estadoant !='' && $estadonue !=''){
			$mensaje .= "<p style='padding-left: 30px;width:100%;'>El Estado cambió de ".$estadoant." a ".$estadonue."</p>";
			//*******************************************//
			//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
			//*******************************************// 
			
			//Usuarios de soporte
			$idusuarios['icarvajal'] = "0";
			$idusuarios['frios'] = "0";
			$idusuarios['aanderson'] = "0"; 
			$idusuarios["admin"] = "0";
			
			//Usuarios relacionados al preventivo  
			if($row['usuariocreadopor'] !="") $idusuarios[$row['usuariocreadopor']] = "0";		
			if($row['usuarioasignadoa'] !="") $idusuarios[$row['usuarioasignadoa']] = "0"; 
			if($row['usuariosolicitante'] !="") $idusuarios[$row['usuariosolicitante']] = "0";
			if($usuarionotificar !="") $idusuarios[$usuarionotificar] = "0"; 
			
			$usuarios = json_encode($idusuarios);
			
			$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,descripcion,fecha,hora,usuarios) VALUES (".$idproyectos.",".$incidente.",'Cambio de estado preventivo',' ".$estadoant." a ".$estadonue."','". date('Y-m-d') ."','". date('H:i:s') ."','".$usuarios."')";  
	        $rsql = $mysqli->query($sql); 
			
			//*******************************************//
			//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
			//*******************************************//
		
		}
		$mensaje .= "<p style='width:100%;'>
						<a href='http://toolkit.maxialatam.com/mitim/preventivo.php?id=".$incidente."' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Preventivo</a></p>";
		if($estadonew == 16 || $estadonew == 17){
			//GENERAR FECHA DE CIERRE 
			$query = "  UPDATE incidentes SET fechacierre = DATE_ADD(fecharesolucion, INTERVAL 3 DAY), horacierre = horaresolucion, 
						idestados = 16 WHERE id = '".$incidente."' ";
			$mysqli->query($query);
			$mensaje .= "<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 7% 4% 1% 4%;'>Resolución</div>
					<div style='margin: 0 4%; color: #3e4954; text-align: justify; line-height: 150%;'>".$resolucion."</div>";	
		}
		$mensaje .= " <div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 3% 4% 0 4%;'>Atributos</div>
						<table style='width: 100%; margin: 0 4% 0 4%;'>
							<tr>
								<td style='padding: 15px 0; font-size: small; width: 50%;'><div style='font-size: 14px;color: #808080;'>Solicitante del servicio</div>".$solicitante."</td>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080;'>Ubicación</div>".$sitio."</td>
							</tr>
							<tr>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080;'>Recibido en</div>".$fechacreacion."</td>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080;'>Departamento</div>".$departamento."</td>
							</tr>
							<tr>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080;'>Asignado a</div>".$nasignadoa."</td>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080;'>Prioridad</div>".$prioridad."</td>
							</tr>
							<tr>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Creado por</div>".$creadopor."</td> 
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Creado desde</div>Sistema</td>
							</tr>
						</table>
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Título</div>
						<div style='margin: 0 4%; color: #3e4954; text-align: justify; line-height: 150%;'>".$titulo."</div>
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 3% 4% 0 4%;'>Descripción</div>
						<div style='margin: 0 4%; color: #3e4954; text-align: justify; line-height: 150%;'>".$descripcion."</div>
						"; 
			
			$mensaje .= "</div>";

		//USUARIOS DE SOPORTE
		//$correo [] = 'ana.porras@maxialatam.com';
		$correo [] = 'isai.carvajal@maxialatam.com';
		if($accion == 'creado'){
			$correo [] = 'fernando.rios@maxialatam.com';	
		}
		$correo [] = 'axel.anderson@maxialatam.com';
		$correo [] = 'maria.baena@maxialatam.com';
		
		debugL("notificarCEstadoPREVENTIVO-CORREO:".json_encode($correo),"notificarCEstadoPREVENTIVO");		
		
		if($accion == 'creado'){
			foreach ($correo as $key => $value) { 
				$querycorreo = "SELECT * FROM notificacionesxusuarios nu
								left join usuarios u on u.id = nu.idusuario
								where u.correo = '$value' and noti1 = 1";
				$consultacorreo = $mysqli->query($querycorreo);
				if($consultacorreo->num_rows == 0){
					unset($correo[$key]);
				}
			}									
		}else if($estadonew == 13){
			foreach ($correo as $key => $value) { 
				$querycorreo = "SELECT * FROM notificacionesxusuarios nu
								left join usuarios u on u.id = nu.idusuario
								where u.correo = '$value' and noti3 = 1";
				$consultacorreo = $mysqli->query($querycorreo);
				if($consultacorreo->num_rows == 0){
					unset($correo[$key]);
				}
			}									
		}else if($estadonew == 14){
			foreach ($correo as $key => $value) { 
				$querycorreo = "SELECT * FROM notificacionesxusuarios nu
								left join usuarios u on u.id = nu.idusuario
								where u.correo = '$value' and noti4 = 1";
				$consultacorreo = $mysqli->query($querycorreo);
				if($consultacorreo->num_rows == 0){
					unset($correo[$key]);
				}
			}									
		}else if($estadonew == 15){
			foreach ($correo as $key => $value) { 
				$querycorreo = "SELECT * FROM notificacionesxusuarios nu
								left join usuarios u on u.id = nu.idusuario
								where u.correo = '$value' and noti5 = 1";
				$consultacorreo = $mysqli->query($querycorreo);
				if($consultacorreo->num_rows == 0){
					unset($correo[$key]);
				}
			}									
		}if($estadonew == 18){
			foreach ($correo as $key => $value) { 
				$querycorreo = "SELECT * FROM notificacionesxusuarios nu
								left join usuarios u on u.id = nu.idusuario
								where u.correo = '$value' and noti6 = 1";
				$consultacorreo = $mysqli->query($querycorreo);
				if($consultacorreo->num_rows == 0){
					unset($correo[$key]);
				}
			}									
		}else if($estadonew == 16){
			foreach ($correo as $key => $value) { 
				$querycorreo = "SELECT * FROM notificacionesxusuarios nu
								left join usuarios u on u.id = nu.idusuario
								where u.correo = '$value' and noti7 = 1";
				$consultacorreo = $mysqli->query($querycorreo);
				if($consultacorreo->num_rows == 0){
					unset($correo[$key]);
				}
			}									
		}
		//ASUNTO
		$innovacion = 'soporteaig@innovacion.gob.pa';
		if($solicitante == $innovacion || $creadopor == $innovacion){
			$asunto = $row['titulo'];
		}
		enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
	}
	
	function crearMensajeSatisfaccion($incidente,$titulo,$solicitante){
		global $mysqli;

		//para quien solicito o reporto el incidente (Solicitante)
		if (filter_var($solicitante, FILTER_VALIDATE_EMAIL)) {
				$correo [] = $solicitante;
		}else{
			$result = $mysqli->query("SELECT nombre,correo FROM usuarios WHERE id = '".$solicitante."' AND estado = 'Activo'");
			while ($row=$result->fetch_assoc()) {
				$correo [] = $row['correo'];
			}
		}
		
		//ASUNTO
		$innovacion = 'soporteaig@innovacion.gob.pa';
		if($solicitante == $innovacion){
			$asunto = $row['titulo'];
		}else{
			$asunto = "Satisfacción del Preventivo #$incidente";
		}

		$mensajeHtml = "<table border=0>
							<tr><td colspan=4>&nbsp;</td></tr>
							<tr><td colspan=4>&nbsp;</td></tr>
							<tr><td colspan=4>Preventivo #$incidente</td></tr>
							<tr><td colspan=4>Titulo: $titulo</td></tr>
							<tr><td colspan=4>&nbsp;</td></tr>
							<tr><td colspan=4>&nbsp;</td></tr>
							<tr><td colspan=>¿Est&aacute; satisfecho con la soluc&oacute;n del Preventivo?</td></tr>
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
					FROM incidentes2 a
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

						$asunto = "Preventivo #$incidente VENCIDO - Soporte Maxia Toolkit";

						$mensajeHtml = "<table border=0>
											<tr><td colspan=4>Maxia Toolkit</td></tr>
											<tr><td colspan=4>Gesti&oacute;n de Soporte</td></tr>
											<tr><td colspan=4>&nbsp;</td></tr>
											<tr><td colspan=4>Preventivo #$incidente</td></tr>
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
		$cuerpo .= "<div style='background:#f6fbf8'>
					<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; margin: 0 6% 0 6%'>";
		$cuerpo .= "		<img src='https://toolkit.maxialatam.com/soporte/images/encabezado-maxia-c.png' style='width: auto; float: left;'>";
		$cuerpo .= "		<div style='width: 100%; text-align: center; margin-right: 27%; padding-top: 1%; color: #333; font-weight: bold;'>
								<div>MiTim</div><div>Gestión de Soporte</div>
							</div>";
		$cuerpo .= "	</div>";
		$cuerpo .= $mensaje;
		$cuerpo .= "	<div style='margin: 0 6% 0 6%; background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;clear: both;'>";
		$cuerpo .= "© ".date('Y')." MiTim";
		$cuerpo .= "	</div>
					</div>";	
		//Eliminar correo Sin Especificar
		foreach ($correo as $key => $value) { 
			if ($value == 'sinespecificar@maxialatam.com') { 
				unset($correo[$key]); 
			}
		}
		$mail->clearAddresses();
		foreach($correo as $destino){
		   $mail->addAddress($destino); // EVITAR ENVÍO DE CORREO A CLIENTES (DESACTIVADO)
		}
		
		//$mail->addAddress("lisbethagapornis@gmail.com");
		//$mail->addAddress("isai.carvajal@maxialatam.com");
		//$mail->addAddress("fernando.rios@maxialatam.com");
		//$mail->addAddress("axel.anderson@maxialatam.com");
		//$mail->addAddress("maylin.aguero@maxialatam.com");
		
		$mail->FromName = "MiTim";
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = $asunto;
		//$mail->MsgHTML($cuerpo);
		$mail->Body = $cuerpo;
		$mail->AltBody = "MiTim: $asunto";
		if($adjuntos != ''){
			foreach($adjuntos as $adjunto){
				//debug('uadjunto: '.$adjunto);
				$mail->AddAttachment($adjunto);
			}
		}
		
		/* if(!$mail->send()) {
			echo 'Mensaje no pudo ser enviado. ';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			//echo 'Ha sido enviado el correo Exitosamente';
			if($adjuntos != ''){
				foreach($adjuntos as $adjunto){
					if(is_file($adjunto))
					unlink($adjunto); //elimino el fichero
				}
			}
			echo true;
		}  */
		
		echo true;
	}
	
	function fusionarIncidentes()
	{
		global $mysqli;
		$fusioninc 		= $_REQUEST['fusioninc'];
		$idincidentes 	= json_decode($_REQUEST['idincidentes']);

		if($fusioninc != ''){
			foreach($idincidentes as $incidente){
				//CATEGORIAS FUSIONADO
				$queryP = " SELECT a.id FROM categorias a 
							LEFT JOIN incidentes b ON a.idproyecto = b.idproyectos 
							WHERE b.id = '$incidente' AND a.nombre = 'Fusionado' ";
				$resultP = $mysqli->query($queryP);
				if($resultP->num_rows >0){
					$rowP = $resultP->fetch_assoc();				
					$idmerge = $rowP['id'];
				}else{
					$idmerge = 6;
				}
				$query = "UPDATE incidentes SET idestados = 16, idcategorias = '$idmerge', fusionado = ".$fusioninc." 
						  WHERE id = '".$incidente."'";
				if($mysqli->query($query)){
					$tieneEvidencias   = '';
					$rutaE 		= '../incidentes/'.$incidente;
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
						$path = '../incidentes/'.$fusioninc.'/fusionados/';
						if (!file_exists($path))
							mkdir($path, 0777);
						$path2 = '../incidentes/'.$fusioninc.'/fusionados/'.$incidente.'/';
						$target_path2 = utf8_decode($path2);
						if (!file_exists($target_path2))
						mkdir($target_path2, 0777); 
			
						$from = '../incidentes/'.$incidente.'/';
						$to   = '../incidentes/'.$fusioninc.'/fusionados/'.$incidente.'/';
						
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
					bitacora($_SESSION['usuario'], "Preventivos", 'El Preventivo #'.$fusioninc.' se fusiono con: '.$incidente, $fusioninc, $query);
					bitacora($_SESSION['usuario'], "Preventivos", 'El Preventivo #'.$incidente.' fue fusionado con: '.$incidente, $incidente, $query);
					echo true;
				}else{
					echo false;
				}
			}
		}else{
			echo false;
		}
	}
	
	function guardarcolumnaocultar() {
		global $mysqli;
		$tipo 	 	    = $_REQUEST['tipo'];
		$columna 	 	= $_REQUEST['columna'];
		$usuario 		= $_SESSION['user_id'];
		$query = '';
		if($tipo == 'agregar'){
		    $querycolumnausuarios = "SELECT * FROM columnasocultas where modulo = 'Preventivos' and usuario = '$usuario'";
		    $resultcolumnausuarios = $mysqli->query($querycolumnausuarios);
    		if($resultcolumnausuarios->num_rows > 0){
    		    $rowcolumnas = $resultcolumnausuarios->fetch_assoc();
    			$valorcolumnaanterior = $rowcolumnas['columnas'];
    			$columnaagregar = $valorcolumnaanterior.$columna.',';
    			$query = "UPDATE columnasocultas set columnas = '$columnaagregar' where modulo = 'Preventivos' and usuario = '$usuario'";
    		}else{
    		    $columnaagregar = $columna.',';
    			$query = " INSERT INTO columnasocultas (id,columnas,usuario,modulo) VALUES (null,'$columnaagregar','$usuario','Preventivos') ";
    		}
		}else{
		    $querycolumnausuarios = "SELECT * FROM columnasocultas where modulo = 'Preventivos' and usuario = '$usuario'";
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
                    $query = "DELETE FROM columnasocultas where modulo = 'Preventivos' and usuario = '$usuario'";
                }else{
    			    $query = "UPDATE columnasocultas set columnas = '$columnaguardar' where modulo = 'Preventivos' and usuario = '$usuario'";
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
		$query = "SELECT columnas from columnasocultas where modulo = 'Preventivos' and usuario = '$usuario'";
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
			$queryP = "SELECT idcategorias FROM incidentes WHERE id = '$fusionado' ";
			$resultP = $mysqli->query($queryP);
			if($resultP->num_rows >0){
				$rowP = $resultP->fetch_assoc();				
				$idcategoria = $rowP['idcategorias'];
			}else{
				$idcategoria = 0;
			}
			
			$query = "UPDATE incidentes SET idestados = 12, fusionado = '', idcategorias = '$idcategoria' WHERE id = '$id' ";
			//debug($query);
			if($mysqli->query($query)){
				//ELIMINAR DIRECTORIO EVIDENCIAS
				$dir = '../incidentes/'.$fusionado.'/fusionados/'.$incidente.'/';
				$handle = opendir($dir);
				while ($file = readdir($handle)) {
					if (is_file($dir.$file)) {
						unlink($dir.$file);
					}
				}
				rmdir('../incidentes/'.$fusionado.'/fusionados/'.$incidente.'/');
				 
				$carpeta = '../incidentes/'.$fusionado.'/fusionados/'; 
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
				bitacora($_SESSION['usuario'], "Preventivos", 'El Preventivo #'.$incidente.' se Revirtió la Fusión con: '.$fusionado, $id, $query);
				bitacora($_SESSION['usuario'], "Preventivos", 'El Preventivo #'.$fusionado.' se Revirtió la Fusión con: '.$incidente, $id, $query);
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
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
		$start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);

		$nivel = $_SESSION['nivel'];
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado = array();
		
		$buscarF = " SELECT fusionado FROM incidentes WHERE id = '$id' ";
		$resultF = $mysqli->query($buscarF);
		if($resultF->num_rows > 0){
			$rowF = $resultF->fetch_assoc();
			$fusionado = $rowF['fusionado'];
		}else{
			$fusionado = 0;
		}
		
		$query  = " ( SELECT id, titulo, descripcion, fechacreacion
					FROM incidentes
					WHERE fusionado = '$id' 
					ORDER BY id DESC )
					UNION
					( SELECT id, titulo, descripcion, fechacreacion
					FROM incidentes 
					WHERE id = '$fusionado' ) ";
		
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		while($row = $result->fetch_assoc()){
			$resultado[] = array(
				'id' 			=> $row['id'],
				'titulo' 		=> $row['titulo'],
				'descripcion' 	=> $row['descripcion'],
				'fechacreacion'	=> $row['fechacreacion'] 
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
	
	function estadosbit(){
		global $mysqli;
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
		$start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);

		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado = array();
		
		$query  = " SELECT b.nombre as estadoant, c.nombre as estadoact, a.fechadesde, a.dias
					FROM incidentesestados a 
					LEFT JOIN estados b ON a.estadoanterior = b.id
					LEFT JOIN estados c ON a.estadonuevo = c.id
					WHERE a.idincidentes = $id ORDER BY a.id DESC ";
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		while($row = $result->fetch_assoc()){
			$resultado[] = array(
				'estadoant' => $row['estadoant'],
				'estadoact' => $row['estadoact'],
				'fecha'		=> $row['fechadesde'],
				'dias'		=> $row['dias']
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

	function historial(){
		global $mysqli;
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
		$start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		
		$nivel = $_SESSION['nivel'];
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado = array();
		
		$query  = "	SELECT a.id, a.usuario, b.nombre, a.fecha, a.accion
					FROM bitacora a 
					INNER JOIN usuarios b ON a.usuario = b.usuario
					WHERE a.modulo = 'Preventivos' AND a.identificador = $id
					ORDER BY a.id DESC ";
		
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		while($row = $result->fetch_assoc()){
			$resultado[] = array(				
				'id' 		=> $row['id'],
				'usuario' 	=> $row['usuario'],
				'nombre' 	=> $row['nombre'],
				'fecha'		=> $row['fecha'],
				'accion'	=> $row['accion']
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
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, e.nombre AS idestados, 
					m.equipo, m.serie as serie, m.activo, m.idmarcas, m.idmodelos, m.modalidad, m.estado as estadoequipo, 
					f.nombre AS categoria, g.nombre AS subcategoria, c.nombre AS sitio, h.prioridad, 
					a.origen, a.creadopor, a.solicitante, a.asignadoa, a.departamento, a.resueltopor,
					a.resolucion, a.satisfaccion, a.comentariosatisfaccion, 
					ifnull(a.fechacreacion, '') AS fechacreacion, a.horacreacion, 
					ifnull(a.fecharesolucion, '') as fecharesolucion, a.horaresolucion,
					ifnull(a.fechacierre, '') as fechacierre, a.horacierre, 
					ifnull(a.fechavencimiento, '') AS fechavencimiento, a.horavencimiento, 
					ifnull(a.fechareal, '') AS fechareal, a.horareal, 
					a.horastrabajadas, n.periodo, o.nombre as cliente
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN activos m ON a.idactivos = m.id AND a.idambientes = m.idambientes
					LEFT JOIN cuatrimestres n ON a.fecharesolucion BETWEEN n.fechainicio AND n.fechafin
					LEFT JOIN clientes o ON a.idclientes = o.id
					WHERE a.tipo = 'preventivos' ";
		
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
		$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Preventivos' AND usuario = '".$_SESSION['usuario']."'";
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
			if(!empty($data->categoriaf)){
				$categoriaf = json_encode($data->categoriaf);
				$where2 .= " AND a.idcategorias IN ($categoriaf)";
			}
			if(!empty($data->subcategoriaf)){
				$subcategoriaf = json_encode($data->subcategoriaf);
				$where2 .= " AND a.idsubcategorias IN ($subcategoriaf)"; 
			}			
			if(!empty($data->idproyectosf)){
				$idproyectosf = json_encode($data->idproyectosf);
				$where2 .= " AND a.idproyectos IN ($idproyectosf)"; 
			}
			if(!empty($data->prioridadf)){
				$prioridadf = json_encode($data->prioridadf);
				$where2 .= " AND a.idprioridades IN ($prioridadf)";
			}
			if(!empty($data->modalidadf)){
				$modalidadf = json_encode($data->modalidadf);
				$where2 .= " AND m.modalidad IN ($modalidadf)"; 
			}
			if(!empty($data->marcaf)){
				$marcaf = json_encode($data->marcaf);
				$where2 .= " AND m.idmarcas IN ($marcaf)";
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				$where2 .= " AND a.solicitante IN ($solicitantef)";
			}
			if(!empty($data->estadof)){
				$estadof = json_encode($data->estadof);
				$where2 .= " AND a.idestados IN ($estadof)";
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
			if(!empty($data->unidadejecutoraf)){
				$unidadejecutoraf = json_encode($data->unidadejecutoraf);
				$where2 .= " AND a.idambientes IN ($unidadejecutoraf)"; 
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
			->setCellValue('J'.$i, $row['idmarcas'])
			->setCellValue('K'.$i, $row['idmodelos'])
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
		$query = "UPDATE incidentes SET comentariovisto='1' WHERE id = '$id'";
		$mysqli->query($query);
	}
	
	function filtroGrid(){
		global $mysqli;
		$_SESSION['filtrogrid'] = '0';
		$usufiltroexiste = 0;
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Preventivos' AND usuario =".$_SESSION['user_id'];
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
			$filtro = str_replace('c.codigo','unidadejecutora',$filtro);
			$filtro = str_replace('a.activo','activo',$filtro);
			$filtro = str_replace('a.idmarcas','marca',$filtro);
			$filtro = str_replace('a.idmodelos','modelo',$filtro);
			$filtro = str_replace('a.idcategorias','idcategoria',$filtro);
			$filtro = str_replace('a.idsubcategorias','idsubcategoria',$filtro);
			$filtro = str_replace('a.idprioridades','idprioridad',$filtro);
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
		
		$query = "DELETE FROM usuariosfiltros WHERE modulo = 'Preventivos' AND usuario = '$usuario' ";
		if($mysqli->query($query))
			echo true;		
	}
	
	function guardarfiltros() {
		global $mysqli;
		$data = $_REQUEST['data'];
		$usuario = $_SESSION['usuario'];
		$query  = " SELECT * FROM usuariosfiltros WHERE modulo = 'Preventivos' AND usuario = '$usuario' ";

		$result = $mysqli->query($query);
		$count = $result->num_rows;
		
		if( $count > 0 ) 
			$query = "UPDATE usuariosfiltros SET filtrosmasivos = '$data' WHERE modulo = 'Preventivos' AND usuario = '$usuario'";
		else
			$query = "INSERT INTO usuariosfiltros VALUES (null, '$usuario', 'Preventivos', '', '$data')";
		if($mysqli->query($query))
			echo true;		
	}
	
	function abrirfiltros() {
		global $mysqli;
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Preventivos' AND usuario = '".$_SESSION['usuario']."'";
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
		$query = " SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Preventivos' AND usuario = '".$_SESSION['usuario']."'";
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
			//$correo [] = 'fernando.rios@maxialatam.com';
			$correo [] = 'axel.anderson@maxialatam.com';
			$correo [] = 'maria.baena@maxialatam.com';
			
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
						FROM incidentes a
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
			
			$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,fecha,hora,usuarios) VALUES (".$idproyectos.",".$incidente.",'Adjunto realizado preventivo','". date("Y-m-d") ."','". date("H:i:s") ."','".$usuarios."')"; 
			$rsql = $mysqli->query($sql); 
				
			//*******************************************//
			//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
			//*******************************************//				
			$cuerpo .= "<div style='background:#f6fbf8'>
						<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; margin: 0 6% 0 6%'>";
			$cuerpo .= "		<img src='https://toolkit.maxialatam.com/soporte/images/encabezado-maxia-c.png' style='width: auto; float: left;'>";
			$cuerpo .= "		<div style='width: 100%; text-align: center; margin-right: 27%; padding-top: 1%; color: #333; font-weight: bold;'>
								<div>MiTim</div><div>Gestión de Soporte</div>
							</div>";
			$cuerpo .= "	</div>";
			$cuerpo .= "<div style='margin: 0 6%; background-color: #FFFFFF; padding: 30px;font-family: arial,sans-serif;'>
							<div style='margin: 0 6% 0 6%; font-size: 22px;width:100%; color:#333; margin-left: 4%'>".$usuarioAct." ha adjuntado nuevo documento al preventivo #".$incidente."</div>";
			$cuerpo .= "	<p style='width:100%;'>
								<a href='http://toolkit.maxialatam.com/mitim/preventivo.php?id=".$incidente."' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver preventivo</a></p>
							</p>
						</div>
						";
			$cuerpo .= "	<div style='margin: 0 6% 0 6%; background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;clear: both;'>";
			$cuerpo .= "© ".date('Y')." MiTim";
			$cuerpo .= "	</div></div>";		
			
			$correo = array_unique($correo);
			 
			
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
					$mail->addAddress($destino);  // EVITAR ENVÍO DE CORREO A CLIENTES (DESACTIVADO)
				}			   
			}			
			//$mail->addAddress("lisbethagapornis@gmail.com");
			//$mail->addAddress("isai.carvajal@maxialatam.com");
			//$mail->addAddress("fernando.rios@maxialatam.com");
			//$mail->addAddress("axel.anderson@maxialatam.com");
			//$mail->addAddress("maylin.aguero@maxialatam.com");
			
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
			
			/* if(!$mail->send()) {
				echo 'Mensaje no pudo ser enviado. ';
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				//echo 'Ha sido enviado el correo Exitosamente';
				echo true;
			}  */
			
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
					m.equipo, m.serie as serie, m.activo, m.idmarcas, m.idmodelos, m.modalidad, m.estado as estadoequipo, 
					f.nombre AS categoria, g.nombre AS subcategoria, c.unidad AS sitio, h.prioridad, 
					a.origen, a.creadopor, a.solicitante, a.asignadoa, a.departamento, a.resueltopor,
					a.resolucion, a.satisfaccion, a.comentariosatisfaccion, 
					ifnull(a.fechacreacion, '') AS fechacreacion, a.horacreacion, 
					ifnull(a.fecharesolucion, '') as fecharesolucion, a.horaresolucion,
					ifnull(a.fechacierre, '') as fechacierre, a.horacierre, 
					ifnull(a.fechavencimiento, '') AS fechavencimiento, a.horavencimiento, 
					ifnull(a.fechareal, '') AS fechareal, a.horareal, 
					a.horastrabajadas, n.periodo, o.nombre as cliente, p.comentario
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN activos m ON a.idactivos = m.id AND a.idambientes = m.idambientes
					LEFT JOIN cuatrimestres n ON a.fecharesolucion BETWEEN n.fechainicio AND n.fechafin
					LEFT JOIN clientes o ON a.idclientes = o.id
					LEFT JOIN comentarios p ON a.id = p.idmodulo
					WHERE a.tipo = 'preventivos' ";
		
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
		$queryF = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Preventivos' AND usuario = '".$_SESSION['usuario']."'";
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
			if(!empty($data->categoriaf)){
				$categoriaf = json_encode($data->categoriaf);
				$where2 .= " AND a.idcategorias IN ($categoriaf)";
			}
			if(!empty($data->subcategoriaf)){
				$subcategoriaf = json_encode($data->subcategoriaf);
				$where2 .= " AND a.idsubcategorias IN ($subcategoriaf)"; 
			}			
			if(!empty($data->idproyectosf)){
				$idproyectosf = json_encode($data->idproyectosf);
				$where2 .= " AND a.idproyectos IN ($idproyectosf)"; 
			}
			if(!empty($data->prioridadf)){
				$prioridadf = json_encode($data->prioridadf);
				$where2 .= " AND a.idprioridades IN ($prioridadf)";
			}
			if(!empty($data->modalidadf)){
				$modalidadf = json_encode($data->modalidadf);
				$where2 .= " AND m.modalidad IN ($modalidadf)"; 
			}
			if(!empty($data->marcaf)){
				$marcaf = json_encode($data->marcaf);
				$where2 .= " AND m.idmarcas IN ($marcaf)";
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				$where2 .= " AND a.solicitante IN ($solicitantef)";
			}
			if(!empty($data->estadof)){
				$estadof = json_encode($data->estadof);
				$where2 .= " AND a.idestados IN ($estadof)";
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
			if(!empty($data->unidadejecutoraf)){
				$unidadejecutoraf = json_encode($data->unidadejecutoraf);
				$where2 .= " AND a.idambientes IN ($unidadejecutoraf)"; 
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
					$query2 .= "correo = '".$row['asignadoa']."' AND estado = 'Activo'";
				}else{
					$query2 .= "correo IN ('".$row['asignadoa']."') AND estado = 'Activo' ";
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
			->setCellValue('J'.$i, $row['idmarcas'])
			->setCellValue('K'.$i, $row['idmodelos'])
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
	
	function importaractividades(){    
		global $mysqli;
		require_once '../../repositorio-lib/xls/Classes/PHPExcel.php';
		//Call the autoload
		require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';

		if(isset($_FILES)) {
			$nombre	 	= $_FILES['archivo']['name'];
			$ArrArchivo = explode(".", $nombre);
			$extension 	= strtolower(end($ArrArchivo));
			$randName 	= md5(rand() * time());
			$path 		= '../importpreventivos/';

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
				if (trim($sheet->getCell('A' . $row)->getValue()) != '' && trim($sheet->getCell('B' . $row)->getValue()) != '' && 
					trim($sheet->getCell('C' . $row)->getValue()) != '' && trim($sheet->getCell('D' . $row)->getValue()) != '' && 
					trim($sheet->getCell('E' . $row)->getValue()) != '' && trim($sheet->getCell('F' . $row)->getValue()) != '' && 
					trim($sheet->getCell('G' . $row)->getValue()) != '' && trim($sheet->getCell('H' . $row)->getValue()) != '' && 
					trim($sheet->getCell('I' . $row)->getValue()) != '' && trim($sheet->getCell('J' . $row)->getValue()) != '' ){
					//$repetida = checkActividadRepetida($sheet->getCell('A' . $row)->getValue(),$sheet->getCell('D' . $row)->getValue());
					$repetida = 0;
					if($repetida == 0){
						$rowData[] = $sheet->rangeToArray('A' . $row . ':' . 'L' . $row, NULL, TRUE, FALSE);
						$importadasExitosas++;
					} else {
						$causasError .= '<li>Error en la fila '.$row.', la actividad ya existe</li>';
						$importadasError++;
					}
				} else {
					if (trim($sheet->getCell('A' . $row)->getValue()) == '' && trim($sheet->getCell('B' . $row)->getValue()) == '' && 
						trim($sheet->getCell('C' . $row)->getValue()) == '' && trim($sheet->getCell('D' . $row)->getValue()) == '' && 
						trim($sheet->getCell('E' . $row)->getValue()) == '' && trim($sheet->getCell('F' . $row)->getValue()) == '' &&
						trim($sheet->getCell('G' . $row)->getValue()) == '' && trim($sheet->getCell('H' . $row)->getValue()) == '' && 
						trim($sheet->getCell('I' . $row)->getValue()) == '' && trim($sheet->getCell('J' . $row)->getValue()) == '' &&
						trim($sheet->getCell('K' . $row)->getValue()) == '' && trim($sheet->getCell('L' . $row)->getValue()) == '' 
						){
						//FILA VACIA
					}else{
						$importadasError++;
						if(trim($sheet->getCell('A' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', la columna <b>Empresa</b> está vacía</li>';
						}
						if(trim($sheet->getCell('B' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', la columna <b>Cliente</b> está vacía</li>';
						}
						if(trim($sheet->getCell('C' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', la columna <b>Proyecto</b> está vacía</li>';
						}
						if(trim($sheet->getCell('D' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', la columna <b>Categoría</b> está vacía</li>';
						}
						if(trim($sheet->getCell('E' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', la columna <b>Nro. de serie</b> está vacía</li>';
						}
						if(trim($sheet->getCell('F' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', la columna <b>Sitio</b> está vacía</li>';
						}
						if(trim($sheet->getCell('G' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', la columna <b>Fecha de MP</b> está vacía</li>';
						}
						if(trim($sheet->getCell('H' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', la columna <b>Horario</b> está vacía</li>';
						}
						if(trim($sheet->getCell('I' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', la columna <b>Prioridad</b> está vacía</li>';
						} 
						if(trim($sheet->getCell('J' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', la columna <b>Responsables</b> está vacía</li>';
						}
					}
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
				$equipo  		= getId('id', 'activos', $ArrItem[4], 'serie');
				$titulo			= 'Mantenimiento Preventivo de '.$equipo;
				$empresa		= trim(str_replace(' ', '', $ArrItem[0]));
				$cliente		= trim(str_replace(' ', '', $ArrItem[1]));
				$proyecto		= trim(str_replace(' ', '', $ArrItem[2]));
				$categoria		= trim(str_replace(' ', '', $ArrItem[3]));
				$serie			= trim(str_replace(' ', '', $ArrItem[4]));
				$sitio			= trim(str_replace(' ', '', $ArrItem[5]));
				$fechamp		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[6], "yyyy-mm-dd");
				$horacreacion	= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[7], "h:mm:ss");
				$horario		= trim(str_replace(' ', '', $ArrItem[8]));
				$prioridad		= trim(str_replace(' ', '', $ArrItem[9]));
				$solicitante	= trim(str_replace(' ', '', $ArrItem[10]));
				$responsable	= trim(str_replace(' ', '', $ArrItem[11]));
				
				//IDS
				$idempresas  	= getId('id', 'empresas', $empresa, 'descripcion');
				$idclientes  	= getId('id', 'clientes', $cliente, 'nombre');
				$idproyectos  	= getId('id', 'proyectos', $proyecto, 'nombre');
				$idcategorias  	= getId('id', 'categorias', $categoria, 'nombre');
				$idsitios 		= getId('id', 'ambientes', $sitio, 'nombre');
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
				$ususolicitante	= buscarUsuario($solicitante);
				if($ususolicitante != ''){
					$correosol = $ususolicitante;
				}else{
					$correosol = $_SESSION['email'];
				}
				
				//debugL($idactivos);
				$query  = " INSERT INTO incidentes (id, titulo, idempresas, idclientes, idproyectos, idcategorias, iddepartamentos, idambientes, idactivos, idprioridades, 
							idestados, creadopor, solicitante, asignadoa, fechacreacion, horacreacion, fechareal, horareal, horario)
							VALUES (null, '$titulo', '$idempresas', '$idclientes', '$idproyectos', '$idcategorias', '$iddepartamentos', '$idsitios', '$serie', '$idprioridades', 
							'$idestados', '$correosol', '$correosol', '$usuresponsable', '$fechamp', '$horacreacion', '$fechamp', '$horacreacion', '$horario') ";
				//debug($query);
				$importadasExito++;
				$result = $mysqli->query($query);
				$id = $mysqli->insert_id;
				if($equipo == ''){
					$causasError .= '<li>Error: El preventivo '.$id.' no ha sido relacionado con el equipo, ya que el número de serie no existe</li>';
					$importadasError++;
				}
				if($idsitios == ''){
					$causasError .= '<li>Error: El preventivo '.$id.' no ha sido relacionado con el sitio, ya que el nombre del sitio no existe</li>';
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
				
				bitacora($_SESSION['usuario'],'Plan de Mantenimiento',$acciones,0,'');
			}else{
				$resultado = $importadasError. ' filas con error. <br/>';
				$resultado .= $causasError;
				echo $resultado;
			}

		}
	}
	function guardarCorrectivoTemp(){
		global $mysqli;
		$data 				= (!empty($_REQUEST['data']) ? $_REQUEST['data'] : ''); 
		$idpreventivos 		= (!empty($_REQUEST['idpreventivos']) ? $_REQUEST['idpreventivos'] : 0);
		$idempresas 		= (!empty($data['idempresas']) ? $data['idempresas'] : 1);
		$iddepartamentos	= (!empty($data['iddepartamentos']) ? $data['iddepartamentos'] : 0);
		$idclientes 		= (!empty($data['idclientes']) ? $data['idclientes'] : 0);
		$idproyectos 		= (!empty($data['idproyectos']) ? $data['idproyectos'] : 0);
		$idambientes 		= (!empty($data['unidadejecutora']) ? $data['unidadejecutora'] : 0);
		$serie 				= (!empty($data['serie']) ? $data['serie'] : '');
		$estado 			= (!empty($data['estado']) ? $data['estado'] : 12); 
		$prioridad 			= (!empty($data['prioridad']) ? $data['prioridad'] : 0);
		$origen 			= (isset($data['origen']) ? $data['origen'] : 'sistema'); 
		$creadopor			= (!empty($data['creadopor']) ? $data['creadopor'] : $_SESSION['correousuario']);
		$asignadoa 			= (!empty($data['asignadoa']) ? $data['asignadoa'] : '');   
		$idsubambientes 	= (!empty($data['area']) ? $data['area'] : '0');  
		$usuario	 		= $_SESSION['usuario'];  
		$fechacreacion		= date('Y-m-d');
		$horacreacion 		= date('H:i:s');  
		
		$query = "  INSERT INTO incidentestemp (idpreventivos,idambientes, idsubambientes, idactivos, idestados, 
					idprioridades, origen, creadopor, asignadoa, idempresas, idclientes, idproyectos, iddepartamentos,usuario)
					VALUES(".$idpreventivos.",".$idambientes.",'".$idsubambientes."', '".$serie."', 12,'".$prioridad."', '".$origen."', '".$creadopor."', 
					 '".$asignadoa."', '".$idempresas."', '".$idclientes."', '".$idproyectos."', '".$iddepartamentos."', '".$usuario."') ";		 
		if($mysqli->query($query)){
			$id = $mysqli->insert_id; 
			echo true;
		}else{
			echo false;
		}
		
	}

?>