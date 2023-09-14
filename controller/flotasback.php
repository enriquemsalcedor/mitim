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
		case  "estadosbit":
			  estadosbit();
			  break;
		case  "historial":
			  historial();
			  break;
		case  "comentariovisto":
			  comentariovisto();
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
		case "notificacionAdjunto":
			 notificacionAdjunto();
			 break;
		case "comentariosleidos":
			 comentariosleidos();
			 break;
		case  "validarComentarios":
			  validarComentarios();
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
					}
				}
			}
			
			// Cierra el gestor de directorios
			closedir($gestor);
			//echo "</ul>";
		}   
		return $contar;
	}
	
function incidentes(){
    
		global $mysqli;
		
		//FILTROS MASIVO
		$nivel 			     = $_SESSION['nivel'];
		$where = "";  
		$where2 = array();		

		$data2   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');		
		$searchGeneral   = (!empty($_POST['search']['value']) ? $_POST['search']['value'] : '');		

		$data = "";
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : '');

	    $start    = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$rowperpage   = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);

        $vacio = array();
		$columns   = (!empty($_REQUEST['columns']) ? $_REQUEST['columns'] : $vacio);



		$usuario  = $_SESSION['usuario'];
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Flotas' AND usuario = '".$_SESSION['usuario']."'";
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
				$where .= " AND a.fechasolicituddesde >= $desdef  ";
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where .= " AND a.fechasolicituddesde <= $hastaf ";
			}
			if(!empty($data->serief)){
				$serief = json_encode($data->serief);
				if($serief != '[""]'){
					$where .= " AND a.idactivos IN ($serief)";
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
			if(!empty($data->fechadevolucionf)){
				$fechadevolucionf = json_encode($data->fechadevolucionf);
				$where .= " AND a.fecharesolucion = $fechadevolucionf ";
			}
			$vowels = array("[", "]");
			$where = str_replace($vowels, "", $where);
		}

		$idusuario = $_SESSION['user_id'];
		$query  = " SELECT a.id, c.nombre AS estado, LEFT(a.descripcion,45) as titulo, a.descripcion as titulott,
					IFNULL(d.nombre, a.solicitante) AS solicitante, a.fechacreacion, a.horacreacion, a.fechacierre, a.asignadoa, e.nombre AS nomusuario, 
					a.destino AS destino, f.serie, h.nombre as marca, i.nombre as modelo,a.fecharesolucion, 
					case when a.fechacierre IS NULL OR LENGTH(ltrim(rTrim(a.fechacierre))) > 0
					then a.fechacreacion else a.fechacierre end as fechaorden,a.fechasolicituddesde,a.fechasolicitudhasta,a.horaresolucion
					FROM flotassolicitudes a
					LEFT JOIN ambientes b ON a.idambientes = b.id
					LEFT JOIN estados c ON a.idestados = c.id
					LEFT JOIN usuarios d ON a.solicitante = d.correo
					LEFT JOIN usuarios e ON a.asignadoa = e.correo
					LEFT JOIN activos f ON a.idactivos = f.id
					LEFT JOIN marcas h ON f.idmarcas = h.id
					LEFT JOIN modelos i ON f.idmodelos = i.id
					";

		$query  .= " WHERE a.tipo = 'flotas' ";
		//$query .= permisos('Solicitud de flotas', '', $idusuario);


		$hayFiltros = 0;
		for($i=0 ; $i<count($columns);$i++){
			$column = $_REQUEST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			if ($_REQUEST['columns'][$i]['search']['value']!="") {

                
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);

				if ($column == 'id') {
					$column = 'a.id';
					$where2[] = " $column like '%".$campo."%' ";
				}
				
				if ($column == 'estado') {
					$column = 'c.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'titulo') {
					$column = 'a.titulo';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'solicitante') {
					$column = 'a.solicitante';
					$where2[] = " $column like '%".$campo."%' ";

					$column = 'd.nombre';
					$where2[] = " $column like '%".$campo."%' ";

				}
				if ($column == 'fechasolicituddesde') {
					$column = 'a.fechasolicituddesde';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'fechasolicitudhasta') {
					$column = 'a.fechasolicitudhasta';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'asignadoa') {
					$column = 'e.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'sitio') {
					$column = 'a.destino';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'serie') {
					$column = 'f.serie';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'marca') {
					$column = 'h.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'modelo') {
					$column = 'i.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'fecharesolucion') {
					$column = 'a.fecharesolucion';
					$where2[] = " $column like '%".$campo."%' ";
				}

//				$where2[] = " $column like '%".$campo."%' ";


				$hayFiltros++;
			}
		}		
		
//		echo $hayFiltros;
		if ($hayFiltros > 0){
			$where .= " AND ".implode(" AND " , $where2)." ";// id like '%searchValue%' or name like '%searchValue%'
		}


		$where3 = "";
		if($searchGeneral!=""){
			$where.= " AND (
				a.id like '%".$searchGeneral."%' or 
				c.nombre like '%".$searchGeneral."%' or 
				a.descripcion like '%".$searchGeneral."%' or 
				d.nombre like '%".$searchGeneral."%' or
				a.fechasolicituddesde like '%".$searchGeneral."%' or
				a.fechasolicitudhasta like '%".$searchGeneral."%' or 
				e.nombre like '%".$searchGeneral."%' or
				a.destino like '%".$searchGeneral."%' or
				f.serie like '%".$searchGeneral."%' or
			    h.nombre like '%".$searchGeneral."%' or
			    i.nombre like '%".$searchGeneral."%' or
				a.fecharesolucion like '%".$searchGeneral."%' or
				a.horaresolucion like '%".$searchGeneral."%'
			) ";

		}



		$query  .= " $where ";
//		echo $query;
		//$query  .= " GROUP BY a.id ";


		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;


		//debugL($query);
		//debugL('INC 1:'.$query);
//		$query  .= " ORDER BY a.id desc ";
		$query  .= " ORDER BY a.id DESC  LIMIT ".$start.",".$rowperpage;
		 
	//	echo('INC 2:'.$query);
		//debugL("QUERY ES:".$query);
		//echo $query;
		$resultado = array();
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			$solicitante = $row['solicitante'];
			//ADJUNTOS INCIDENTES
			$tieneEvidencias   = '';
			$rutaE 		= '../flotas/'.$row['id'];
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
			if($tieneEvidencias != ''){
				$color = 'success';
				
				$evid = '<span class="btn-icon btn-xs" id="boton-evidencias" style="position: absolute;top: 12px;right: 0;padding: 0;">
						    <i class="fa fa-camera text-green i-header" aria-hidden="true" style="cursor: initial;"></i>
						 </span>';
			}else{
				// Verifico adjuntos de comentarios  
				$ruta = '../flotas/'.$row['id'].'/comentarios';
				$respuesta = obtener_estructura_directorios($ruta); 
				if($respuesta==""){
					$respuesta = 0;
				}  
				if($respuesta > 0){
					$color = 'success';
					$evid = '<span class="btn-icon btn-xs" id="boton-evidencias" style="position: absolute;top: 12px;right: 0;padding: 0;">
						    <i class="fa fa-camera text-green i-header" aria-hidden="true" style="cursor: initial;"></i>
						 </span>';
				}else{
					$color = 'info';
				}
			}
			//COMENTARIOS
			$iconcoment = "";
			$coment = " SELECT count(visto) AS total, MAX(fecha) AS fecha FROM comentarios WHERE modulo = 'Flotas'AND idmodulo = '".$row['id']."' ";			
			$rcomen = $mysqli->query($coment);	
			
			$row2 = $rcomen->fetch_assoc();
			$totalco = $row2['total'];
			$fecha   = $row2['fecha'];
						 	
			if($totalco > 0){
    			$comentN = " SELECT (SELECT COUNT(*) FROM comentarios WHERE modulo = 'Flotas'AND visto = 'SI' AND idmodulo = ".$row['id'].") AS si, 
			 				(SELECT COUNT(*) FROM comentarios WHERE modulo = 'Flotas'AND visto = 'NO' AND idmodulo = ".$row['id'].") AS no ";
				 
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
			 						 WHERE modulo = 'Flotas'AND a.usuario = '".$usuario."' 
			 						 AND 
			 						 b.idmodulo = '".$row['id']."'";
								 
			 			$rcomen2 = $mysqli->query($coment2);
								
			 			$rowv = $rcomen2->fetch_assoc();
			 			$totalv = $rowv['total'];
						
			 			if($totalv == $totalco){
			 				/*$iconcoment = "<span class='icon-col blue fa fa-comment  boton-coment-".$row['id']."' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Comentarios' data-placement='right'></span>";*/
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
									<a class="dropdown-item text-info" href="flota.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>
									<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>';
				$acciones .= '<a class="dropdown-item text-'.$color.' boton-evidencias"  data-id='.$row['id'].' "><i class="fas fa-camera mr-2"></i>Evidencias</a>';

			$acciones .= 		'</div>
							</div>
						</td>';
			

    	    $resultado[] = array(			
				'acciones' 			=> $acciones, 
				'id' 				=> $row['id'],
				'estado' 			=> $row['estado'],		   
				'titulo' 			=> "<span data-toggle='tooltip' data-placement='right' data-original-title='".$row['titulott']."'>".$row['titulo']."</span>",
				'solicitante'		=> $solicitante,
				'fechasolicituddesde' 	=> $row['fechasolicituddesde'],
				'fechasolicitudhasta'	=> $row['fechasolicitudhasta'],
				'asignadoa'			=> $row['nomusuario'],
				'sitio'				=> $row['destino'],
				'serie'				=> $row['serie'],
				'marca'				=> $row['marca'],
				'modelo'			=> $row['modelo'],
				'fecharesolucion'	=> $row['fecharesolucion'].' '.$row['horaresolucion']
			);
		}

		$response = array(
		  "draw" => intval($draw),
		  "recordsTotal" => intval($recordsTotal),
		  "recordsFiltered" => intval($recordsTotal),
		  "data" => $resultado,
		);

		echo json_encode($response);
	}

			
	function eliminarincidentes()
	{
		global $mysqli;

		$id 	= $_REQUEST['idincidente'];
		$query 	= "DELETE FROM flotassolicitudes WHERE id = '$id'";
		$result = $mysqli->query($query);
		if($result == true){
			echo 1;
		}else{
			echo 0;
		}
		bitacora($_SESSION['usuario'], "Solicitud de flota", 'La solicitud de flota #: '.$id.' fue eliminado.', $id, $query);
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
				$carpeta = '../flotas/'.$idincidente.'/comentarios/'.$id.'/';
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
					$carpeta = '../flotas/'.$idincidente.'/comentarios/'.$id.'/';
					deleteDirectory($carpeta);
				
					echo 1;
				}else{
					echo 0;
				}
			}else{
				echo 2;
			}
		}
		bitacora($_SESSION['usuario'], "Solicitud de flota", 'El Comentario #: '.$id.' fue eliminado.', $id, $query); 
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
		$_SESSION['incidente_cor'] = $incidente;
		$_SESSION['comentario_cor'] = '';
		//INCIDENTES
		$myPathInc = '../flotas';
		$target_pathInc = utf8_decode($myPathInc);
		if (!file_exists($target_pathInc)) {
			mkdir($target_pathInc, 0777);
		}
		//INCIDENTE
		$myPathI = '../flotas/'.$incidente;
		$target_pathI = utf8_decode($myPathI);
		if (!file_exists($target_pathI)) {
			mkdir($target_pathI, 0777);
		}
		//$Path = dirname($_SERVER['PHP_SELF']) . '/../flotas/'.$_SESSION['incidente'].'/';
		//RUTA
		$Path = '/../flotas/'.$incidente.'/';
		$Path2 = '/../flotas/flota/';
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
		$idusuario 	= $_SESSION['user_id'];
		$visibilidad = $_REQUEST['visibilidad'];
		$fecha 		= date("Y-m-d");
		$id_preventivo = 0;
		$sinEncuesta = 0;
		
		if($visibilidad == 'Público'){
			$queryV  = "SELECT a.id FROM comentarios a 
						INNER JOIN encuestasresultados b ON b.idincidentes = a.idmodulo 
						WHERE a.idmodulo = ".$incidente." 
						AND visibilidad = 'Público' AND b.idencuestas = 1 AND b.realizada != '' LIMIT 1";
			$resultV = $mysqli->query($queryV);
			if($resultV->num_rows == 0){
				$sinEncuesta = 1;
				//debugL("PASÓ 1");
			}else{
				//debugL("PASÓ 2");
			}
		}
		/*$queryF	  	= " SELECT id FROM comentarios WHERE idmodulo = '".$incidente."' ";
		//debugL('agregarComentario - queryF - '.$queryF);
		$resultF 	= $mysqli->query($queryF);
		if($resultF->num_rows == 0){
			$result = $mysqli->query("SELECT titulo,solicitante FROM flotassolicitudes WHERE id = '".$incidente."' ");
			//debugL('agregarComentario - result - '."SELECT titulo,solicitante FROM flotassolicitudes WHERE id = '".$incidente."' ");
			while ($row = $result->fetch_assoc()) {
				$titulo = $row['titulo'];
				$solicitante = $row['solicitante'];
			}
			crearMensajeEncuesta($incidente,$titulo,$solicitante,1,$idusuario);
		}*/
		
		if($comentario != ''){
			$queryI = "INSERT INTO comentarios VALUES(null, 'Flotas', $incidente, '$comentario', '$visibilidad', '$usuario', NOW(), 'NO')";
			//debug('queryI: '.$_GET['comentario']);
			if($mysqli->query($queryI)){
				$id = $mysqli->insert_id;
				//BITACORA
				bitacora($_SESSION['usuario'], "Solicitud de flota", "Se ha registrado un Comentario para la Solicitud de flota #".$incidente, $incidente, $queryI);
				//ENVIAR NOTIFICACION
				if($visibilidad == 'Privado'){
					notificarComentariosSoporte($incidente,$comentario,$visibilidad);
					notificarComentariosAsignados($incidente,$comentario,$visibilidad);
				}else{
					//notificarComentariosSoporte($incidente,$comentario,$visibilidad);
					notificarComentarios($incidente,$comentario,$visibilidad);
				}
				
				//*******************************************//
				//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
				//*******************************************//
				
				//Usuarios asociados a la flota
				$qInc = " 	SELECT b.usuario AS usuarioasignadoa, c.usuario AS usuariosolicitante, d.usuario AS correocreadopor
							FROM flotassolicitudes a 
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
				
				//Usuarios de soporte
				$idusuarios["icarvajal"] = "0";
				$idusuarios["frios"] = "0";
				$idusuarios["aanderson"] = "0";  
				$idusuarios["admin"] = "0";
				
				$usuarios = json_encode($idusuarios);
				
				$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,fecha,hora,usuarios) VALUES (0,".$incidente.",'Comentario realizado flota','". date("Y-m-d") ."','". date("H:i:s") ."','".$usuarios."')"; 
				$rsql = $mysqli->query($sql); 
				
				//*******************************************//
				//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
				//*******************************************//
				//Se crea carpeta comentarios
				$myPathC 	  = '../flotas/'.$incidente.'/comentarios';
				$target_pathC = utf8_decode($myPathC);
				if (!file_exists($target_pathC)) {
					mkdir($target_pathC, 0777);
				}
				//Se crea carpeta con identificador de comentario
				$myPath 	 = '../flotas/'.$incidente.'/comentarios/'.$id;
				$target_path = utf8_decode($myPath);
				if (!file_exists($target_path)) {
					mkdir($target_path, 0777);
				}
				if($sinEncuesta == 1){
					echo 2;
				}else{
					echo 1;
				}
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
	}

	function comentarios(){
		global $mysqli;
		
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		
		$nivel 		= $_SESSION['nivel'];
		$id 		= (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado 	= array();
		$acciones 	= '';

		$query  = " SELECT a.id, a.idmodulo, a.comentario, a.fecha, b.nombre, a.visibilidad
					FROM comentarios a
					LEFT JOIN usuarios b ON a.usuario = b.usuario
					WHERE modulo = 'Flotas' AND idmodulo IN ($id) AND a.visibilidad != '' ";
		if($nivel == 4 || $nivel == 7){
			$query .= " AND a.visibilidad = 'Público' ";
		}
		$query .= " ORDER BY a.id DESC ";
		//echo($query);
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		//$query  .= " LIMIT $start, $length ";
		while($row = $result->fetch_assoc()){
			//ADJUNTOS
			$adjuntos   = '';
			$ruta 		= '../flotas/'.$row['idmodulo'].'/comentarios/'.$row['id'];
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
									<a class="dropdown-item text-'.$color.' boton-adjuntos-comentarios"  data-id="'.$row['idmodulo'].'-'.$row['id'].'"><i class="fas fa-camera mr-2"></i>Adjuntos Comentario</a>';
			if($nivel != 4 || $nivel != 7){
				$acciones .= '<a class="dropdown-item text-danger boton-eliminar-comentarios" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar Comentario</a>';
			}

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
		$idincidente = $_REQUEST['idincidente'];
		$usuario     = $_SESSION['usuario'];
		
		$queryC = "	SELECT id FROM comentarios WHERE modulo = 'Flotas'AND idmodulo = '$idincidente' AND visto != '' ";
		$resultC = $mysqli->query($queryC);
		while($rowC = $resultC->fetch_assoc()){
			$idc = $rowC['id'];
		    $queryV = " SELECT count(id) AS id FROM comentariosvistos WHERE modulo = 'Flotas' AND idcomentario = '".$idc."' 
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
							 WHERE modulo = 'Flotas'AND idmodulo = '$idincidente' AND visto = 'NO' ";
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
		$_SESSION['incidente_cor'] 	= $incidente;
		$_SESSION['comentario_cor'] = $comentario;		
		//INCIDENTES
		$myPathInc = '../flotas';
		$target_pathInc = utf8_decode($myPathInc);
		if (!file_exists($target_pathInc)) {
			mkdir($target_pathInc, 0777);
		}
		//INCIDENTE
		$myPathI = '../flotas/'.$incidente;
		$target_pathI = utf8_decode($myPathI);
		if (!file_exists($target_pathI)) {
			mkdir($target_pathI, 0777);
		}
		//COMENTARIOS
		$myPathC 	  = '../flotas/'.$incidente.'/comentarios';
		$target_pathC = utf8_decode($myPathC);
		if (!file_exists($target_pathC)) {
			mkdir($target_pathC, 0777);
		}
		//COMENTARIO
		$myPath 	 = '../flotas/'.$incidente.'/comentarios/'.$comentario;
		$target_path = utf8_decode($myPath);
		if (!file_exists($target_path)) {
			mkdir($target_path, 0777);
		}
		//$Path = dirname($_SERVER['PHP_SELF']) . '/../flotas/'.$_SESSION['incidente'].'/';
		//RUTA
		$Path = '/../flotas/'.$incidente.'/comentarios/'.$comentario.'/';
		$hash = strtr(base64_encode($Path), '+/=', '-_.');
		$hash = rtrim($hash, '.');
		echo "l1_". $hash;
	}
	
	//ENVIAR CORREO DE NOTIFICACION DE COMENTARIO
	function notificarComentarios($incidente,$comentario,$visibilidad){
		global $mysqli;
		//CREADOR - SOLICITANTE - ASIGNADO
		$query  = " SELECT a.titulo, a.notificar,
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
					FROM flotassolicitudes a
					LEFT JOIN usuarios i ON a.creadopor = i.id OR a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.asignadoa = k.correo
					WHERE a.id = ".$incidente." AND i.id != 0 ";
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
					if( $notificar != 'mesadeayuda@innovacion.gob.pa' ){ 
						
						//Excluir usuarios inactivos campo Notificar a 
						$queryn = " SELECT correo FROM usuarios WHERE correo = '".$notificar."' AND estado = 'Activo' ";
						$consultan = $mysqli->query($queryn);
						if($recn = $consultan->fetch_assoc()){
							$correo [] = $notificar;	
						}
					}
				}else{
					if (is_array($notificar) || is_object($notificar)){
						foreach($notificar as $notif){
							if( $notif != 'mesadeayuda@innovacion.gob.pa' ){
								
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
				$query2 = " SELECT nombre, nivel FROM usuarios WHERE ";
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
		$query  = " SELECT a.id, a.titulo, a.descripcion, a.destino AS ambiente, a.resolucion, 
					a.origen, a.asignadoa, IFNULL(i.nombre, a.creadopor) AS creadopor, 
					IFNULL(j.nombre, a.solicitante) AS solicitante, a.creadopor AS ccreadopor, a.solicitante AS csolicitante,
					IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0),CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion
					FROM flotassolicitudes a 
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN activos d ON a.idactivos = d.id
					LEFT JOIN estados e ON a.idestados = e.id 
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
		$sitio 			= $row['ambiente'];
		$resolucion 	= $row['resolucion']; 
		$nasignadoa 	= $asignadoaN;
		$comentarios	= '';
		$bitacora		= '';
		
		//COMENTARIOS
		$consultaC = $mysqli->query("SELECT comentario FROM comentarios WHERE modulo = 'Flotas' AND idmodulo = $incidente AND comentario !='".$comentario."' AND  visibilidad != 'Privado' ORDER BY id DESC ");
		while ($registroC = $consultaC->fetch_assoc()) {
			$comentarios .= $registroC['comentario'].'<br>';
		}
		//BITACORA
		$consultaB = $mysqli->query("SELECT accion FROM bitacora WHERE identificador = $incidente ");
		while ($registroB = $consultaB->fetch_assoc()) {
			$bitacora .= $registroB['accion'].'<br>';
		}
		$enviar = 1;
		$isist = '';
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
				$arrnum = explode('REQ ', $arrnuminc);
				$isist 	= " - REQ ".$arrnum[1];
			}
			$numinc = $arrnum[1];
		    $asunto = "Solicitud de flota #$incidente - Comentario - $numinc";
			$enviar = 0;
    	} else {
			$numinc = '';
    	    $asunto = "Solicitud de flota #$incidente - Comentario ";
		}
		
		$mensaje  = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha comentado el Solicitud de flota #".$incidente." - ".$isist."</b></p>			
					<p style='padding-left: 30px;width:100%;'>Comentario: ".$comentario."</p>
					<p style='width:100%;'><br><a href='http://toolkit.maxialatam.com/soporte/flota.php?id=$incidente&vercom=1' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Añadir un comentario</a></p>
					<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin-top: 30px;padding: 10px 10px 0 30px;width:100%;'>Comentarios anteriores</p>";
					if($comentarios != ''){
						$mensaje .="<p style='padding-left: 30px;width:100%;'>".$comentarios."</p>";
					}
					$mensaje .="
					<br><br>
					<p  style='font-size: 18px;width:100%;'>".$creadopor." ha creado esta solicitud de flota el ".$fechacreacion."</p>
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
							 
						</tr>
						<tr>
							<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Asignado a</div>".$nasignadoa."</td>
							 
						</tr>
					<table>
					</div>";
		//USUARIOS DE SOPORTE
		//$correo [] = 'ana.porras@maxialatam.com';
		$correo [] = 'maria.baena@maxialatam.com';
		$correo [] = 'jesus.barrios@maxialatam.com';
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'maylin.aguero@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		
		//Correos PM Tigo
		foreach ($correo as $key => $value) { 
			if ($value == 'jose.barahona@tigo.com.pa' || $value == 'mariano.saibene@tigo.com.pa' || $value == 'fabio.beascoechea@tigo.com.pa') { 
				unset($correo[$key]); 
			}
		}
		debugL("notificarComentarios-CORREO:".json_encode($correo),"notificarComentarios");
		foreach ($correo as $key => $value) { 
			$querycorreo = "SELECT * FROM notificacionesxusuarios nu
							left join usuarios u on u.id = nu.idusuario
							where u.correo = '$value' and noti8 = 1";
			$consultacorreo = $mysqli->query($querycorreo);
			if($consultacorreo->num_rows == 0){
				unset($correo[$key]);
			}
		}
		if ($enviar==1)
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
					FROM flotassolicitudes a
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
					$query2 .= "correo = '".$row['asignadoa']."' AND estado = 'Activo'";
				}else{
					$query2 .= "correo IN (".$row['asignadoa'].") AND estado = 'Activo'";
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
			$query  = " SELECT a.id, a.titulo, a.descripcion, a.destino AS ambiente, a.resolucion, h.prioridad, a.idproyectos,
						a.origen, a.asignadoa, IFNULL(i.nombre, a.creadopor) AS creadopor, 
						IFNULL(j.nombre, a.solicitante) AS solicitante, a.creadopor AS ccreadopor, a.solicitante AS csolicitante,
						a.departamento, IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0),CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion
						FROM flotassolicitudes a
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
			$consultaC = $mysqli->query("SELECT comentario FROM comentarios WHERE modulo = 'Flotas' AND idmodulo = $incidente ");
			while ($registroC = $consultaC->fetch_assoc()) {
				$comentarios .= $registroC['comentario'].'<br>';
			}
			//BITACORA
			$consultaB = $mysqli->query("SELECT accion FROM bitacora WHERE identificador = $incidente ");
			while ($registroB = $consultaB->fetch_assoc()) {
				$bitacora .= $registroB['accion'].'<br>';
			}
			$enviar = 1;
			$isist = '';
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
					$arrnum = explode('REQ ', $arrnuminc);
					$isist 	= " - REQ ".$arrnum[1];
				}
				$numinc = $arrnum[1];
				$asunto = "Solicitud de flota #$incidente - Comentario - INC $numinc";
				$enviar = 0;
			} else {
				$numinc = '';
				$asunto = "Solicitud de flota #$incidente - Comentario ";
			}
			
			$mensaje  = "<div style='padding: 30px;font-family: arial,sans-serif;'>
						<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha comentado el Solicitud de flota #".$incidente." ".$isist."</b></p>			
						<p style='padding-left: 30px;width:100%;'>Comentario: ".$comentario."</p>
						<p style='width:100%;'><br><a href='http://toolkit.maxialatam.com/soporte/flota.php?id=$incidente' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Añadir un comentario</a></p>
						<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin-top: 30px;padding: 10px 10px 0 30px;width:100%;'>Comentarios anteriores</p>";
						if($comentarios != ''){
							$mensaje .="<p style='padding-left: 30px;width:100%;'>".$comentarios."</p>";
						}
						$mensaje .="
						<br><br>
						<p  style='font-size: 18px;width:100%;'>".$creadopor." ha creado esta Solicitud de flota el ".$fechacreacion."</p>
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
			 //Correos PM Tigo
			foreach ($correo as $key => $value) { 
				if ($value == 'jose.barahona@tigo.com.pa' || $value == 'mariano.saibene@tigo.com.pa' || $value == 'fabio.beascoechea@tigo.com.pa') { 
					unset($correo[$key]); 
				}
			}
			
			debugL("notificarComentariosAsignados-CORREO:".json_encode($correo),"notificarComentariosAsignados");
			
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
		$query  = " SELECT a.id, a.titulo, a.descripcion, a.destino AS ambiente, a.resolucion, a.idproyectos, h.prioridad, 
					a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, IFNULL(j.nombre, a.solicitante) AS solicitante,
					CASE 
						WHEN l.estado = 'Activo' 
							THEN a.asignadoa 
						WHEN l.estado = 'Inactivo' 
							THEN '' 
						END 
						AS asignadoa,
					a.departamento, IF(a.fechacreacion IS NOT NULL,CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion, a.idclientes					
					FROM flotassolicitudes a
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
					WHERE a.id = $incidente ";
					
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
		$idclientes 	= $row['idclientes'];
		$idproyectos 	= $row['idproyectos'];
		$nasignadoa 	= $asignadoaN;
		$comentarios	= '';
		$bitacora		= '';
		
		//COMENTARIOS
		$consultaC = $mysqli->query("SELECT comentario FROM comentarios WHERE modulo = 'Flotas' AND idmodulo = $incidente ");
		while ($registroC = $consultaC->fetch_assoc()) {
			$comentarios .= $registroC['comentario'].'<br>';
		}
		//BITACORA
		$consultaB = $mysqli->query("SELECT accion FROM bitacora WHERE identificador = $incidente ");
		while ($registroB = $consultaB->fetch_assoc()) {
			$bitacora .= $registroB['accion'].'<br>';
		}
		
		$asunto = "Solicitud de flota #$incidente - Comentario ";
		
		$mensaje  = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha comentado el Solicitud de flota #".$incidente." - ".$isist."</b></p>			
					<p style='padding-left: 30px;width:100%;'>Comentario ".$visibilidad.": ".$comentario."</p>
					<p style='width:100%;'><br><a href='http://toolkit.maxialatam.com/soporte/flota.php?id=$incidente' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Añadir un comentario</a></p>
					<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin-top: 30px;padding: 10px 10px 0 30px;width:100%;'>Comentarios anteriores</p>
					<p style='padding-left: 30px;width:100%;'>".$comentarios."</p>
					<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin-top: 30px;padding: 10px 10px 0 30px;width:100%;'>Actividad reciente</p>
					<p style='padding-left: 30px;width:100%;'>".$bitacora."</p>
					<br><br>
					<p  style='font-size: 18px;width:100%;'>".$creadopor." ha creado esta Solicitud de flota el ".$fechacreacion."</p>
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
		$correo [] = 'maria.baena@maxialatam.com';
		$correo [] = 'jesus.barrios@maxialatam.com';
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'maylin.aguero@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		 
		if($row['asignadoa'] != ""){
			$correo [] = $row['asignadoa'];
		}
		
		//CLIENTE AIG - USUARIOS DE PRUEBA
		if($idclientes == 13 && $visibilidad == 'Público' && $row['asignadoa'] == 'soportemaxia@zertifika.com'){
			$queryc = " SELECT correo FROM usuarios WHERE nivel = 6 AND idclientes = 13 AND estado = 'Activo' ";
			$consultac = $mysqli->query($queryc);
			while($recc = $consultac->fetch_assoc()){
				$correo [] = $recc['correo'];	
			}
		}
		//Correos PM Tigo
		foreach ($correo as $key => $value) { 
			if ($value == 'jose.barahona@tigo.com.pa' || $value == 'mariano.saibene@tigo.com.pa' || $value == 'fabio.beascoechea@tigo.com.pa') { 
				unset($correo[$key]); 
			}
		}
		debugL("notificarComentariosSoporte-CORREO:".json_encode($correo),"notificarComentariosSoporte");
		foreach ($correo as $key => $value) { 
			$querycorreo = "SELECT * FROM notificacionesxusuarios nu
							left join usuarios u on u.id = nu.idusuario
							where u.correo = '$value' and noti9 = 1";
			$consultacorreo = $mysqli->query($querycorreo);
			if($consultacorreo->num_rows == 0){
				unset($correo[$key]);
			}
		}
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
		$query  = " SELECT a.id,a.descripcion,
					f.id AS serie, h.nombre as marca,a.destino as destino, i.nombre as modelo, c.id AS estado,a.solicitante, a.asignadoa, a.resolucion,IFNULL(i.nombre, a.creadopor) AS creadopor,a.fechacierre, a.horacierre, 
					IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0), a.fechacreacion,'') AS fechacreacion, a.horacreacion,
					IF(( a.fecharesolucion is not null OR LENGTH(ltrim(rTrim(a.fecharesolucion))) > 0),CONCAT(a.fecharesolucion,'  ', IFNULL(a.horaresolucion,'')),'') AS fecharesolucion,g.id as iddepartamentos,a.fechasolicituddesde,a.fechasolicitudhasta,a.kilometrajeinicial,a.kilometrajefinal,a.gasolinainicial,a.gasolinafinal,a.tarjetagasolina,a.controlpuerta,a.fecharetiro
					FROM flotassolicitudes a
					LEFT JOIN ambientes b ON a.idambientes = b.id
					LEFT JOIN estados c ON a.idestados = c.id
					LEFT JOIN usuarios d ON a.solicitante = d.correo
					LEFT JOIN usuarios e ON a.asignadoa = e.correo
					LEFT JOIN activos f ON a.idactivos = f.id
					LEFT JOIN departamentos g ON a.iddepartamentos = g.id
					LEFT JOIN marcas h ON f.idmarcas = h.id
					LEFT JOIN modelos i ON f.idmodelos = i.id
					WHERE a.id = $id ";
		//echo $query;
		//debug($query);
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			if($row['marca'] == '0')
				$row['idmarcas']='';
			if($row['modelo'] == '0')
				$row['idmodelos']='';
			if($row['descripcion'] == '0')
				$row['descripcion']='';
			
			//reviso la cadena y solo tomo el correo
			$solicitante = $row['solicitante'];
			$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';
			if(strpos($solicitante, '<') == true){
				preg_match ( $pattern, $solicitante, $solicitante );
			}

			//Limpiar Campo Descripción
			$string      = $row['descripcion'];
			$descripcion = limpiarUTF8($string);
			$resultado[] = array(
						'id' 					=> $row['id'],
						'descripcion' 			=> $descripcion,
						'iddepartamentos'		=> $row['iddepartamentos'],
						'destino' 				=> $row['destino'],
						'serie' 				=> $row['serie'],
						'marca' 				=> $row['marca'],
						'modelo' 				=> $row['modelo'],
						'estado' 				=> $row['estado'],
						'solicitante' 			=> $solicitante,
						'asignadoa' 			=> $row['asignadoa'],
						'resolucion' 			=> $row['resolucion'],
						'fechacreacion' 		=> $row['fechacreacion'],
						'horacreacion' 			=> $row['horacreacion'],
						'fechasolicituddesde' 		=> $row['fechasolicituddesde'],
						'fechasolicitudhasta' 		=> $row['fechasolicitudhasta'],
						'fecharetiro' 	    	=> $row['fecharetiro'],
						'fecharesolucion' 		=> $row['fecharesolucion'],
						'fechacierre' 			=> $row['fechacierre'],
						'horacierre' 			=> $row['horacierre'],
						'kilometrajeinicial' 			=> $row['kilometrajeinicial'],
						'kilometrajefinal' 			=> $row['kilometrajefinal'],
						'gasolinainicial' 			=> $row['gasolinainicial'],
						'gasolinafinal' 			=> $row['gasolinafinal'],
						'tarjetagasolina' 			=> $row['tarjetagasolina'],
						'controlpuerta' 			=> $row['controlpuerta']
					);
		}
		echo json_encode($resultado);
	}
	
	function guardarIncidente(){
		global $mysqli;
		$data 				= (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		$descripcion 		= (!empty($data['descripcion']) ? $data['descripcion'] : '');
		$iddepartamentos	= (!empty($data['iddepartamentos']) ? $data['iddepartamentos'] : 0);
		$destino 		    = (!empty($data['destino']) ? $data['destino'] : '');
		$serie 				= (!empty($data['serie']) ? $data['serie'] : '');
		$estado 			= (!empty($data['estado']) ? $data['estado'] : 12);
		$origen 			= (isset($data['origen']) ? $data['origen'] : 'sistema');
		$solicitante 		= (!empty($data['solicitante']) ? $data['solicitante'] : $_SESSION['correousuario']);
		$creadopor			= (!empty($data['creadopor']) ? $data['creadopor'] : $_SESSION['correousuario']);
		$asignadoa 			= (!empty($data['asignadoa']) ? $data['asignadoa'] : '');
		$resolucion 		= (!empty($data['resolucion']) ? $data['resolucion'] : '');	
		$fecharesolucion 	= (!empty($data['fecharesolucion']) ? $data['fecharesolucion'] : '');
		$fechacierre 		= (!empty($data['fechacierre']) ? $data['fechacierre'] : '');
		$horacierre 		= (!empty($data['horacierre']) ? $data['horacierre'] : '');
		$fechasolicituddesde		= (!empty($data['fechasolicituddesde']) ? $data['fechasolicituddesde'] : date("Y-m-d"));
		$fechasolicitudhasta		= (!empty($data['fechasolicitudhasta']) ? $data['fechasolicitudhasta'] : date("Y-m-d"));
		$idsubambientes 	= (!empty($data['area']) ? $data['area'] : '0'); 
		$idusuario 			= $_SESSION['user_id'];
		$nivel	 			= $_SESSION['nivel'];
	    
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

		$query = "  INSERT INTO flotassolicitudes(id,descripcion, destino, idsubambientes, tipo, idactivos, idestados,
		            origen, creadopor, solicitante, asignadoa, fechacreacion,horacreacion,fechasolicituddesde,fechasolicitudhasta,resolucion,iddepartamentos)";
		
		if($idambientes !=""){
			$idambientes = $idambientes;
		}else{
			$idambientes = 0;
		}
		$query .=" 
					VALUES(null,'".$descripcion."', '".$destino."',".$idsubambientes.", 'flotas','".$serie."', 
					'".$estado."', '".$origen."', '".$creadopor."', 
					'".$solicitante."', '".$asignadoa."','".$fechacreacion."','".$horacreacion."','".$fechasolicituddesde."','".$fechasolicitudhasta."', '".$resolucion."','".$iddepartamentos."') ";
		// echo $query;
		//debug('INSERT'.$query);
		if($mysqli->query($query)){
			$id = $mysqli->insert_id;
			if($id != ''){
				//CREAR REGISTRO EN ESTADOS INCIDENTES
				$queryE = " INSERT INTO flotassolicitudesestados (idincidentes,estadoanterior,estadonuevo,usuario,fechadesde,horadesde,dias)
							VALUES(".$id.", 12, '".$estado."', ".$idusuario.", now(), now(), 0) ";
				$mysqli->query($queryE);
				
				//CREAR CARPETA DE ID INCIDENTES Y COMENTARIOS
				$myPath = '../flotas/';
				if (!file_exists($myPath))
					mkdir($myPath, 0777);
				$myPath = '../flotas/'.$id.'/';
				$target_path2 = utf8_decode($myPath);
				if (!file_exists($target_path2))
					mkdir($target_path2, 0777);
				
				if($_SESSION['nivel'] == 4){
					//MOVER DEL TEMP A INCIDENTES
					$num 	= $_SESSION['user_id'];
					$from 	= '../flotastemp/'.$num;
					$to 	= '../flotas/'.$id.'/';
					
					//Abro el directorio que voy a leer
					$dir = opendir($from);

					//Recorro el directorio para leer los archivos que tiene
					while(($file = readdir($dir)) !== false){
						//Leo todos los archivos excepto . y ..
						if(strpos($file, '.') !== 0){
							//Copio el archivo manteniendo el mismo nombre en la nueva carpeta
							copy($from.'/'.$file, $to.'/'.$file);
							unlink($from.'/'.$file);
						}
					}
				}
				//ENVIAR CORREO AL CREADOR DEL INCIDENTE
				nuevoincidente($_SESSION['usuario'],$descripcion, $id, $fechacreacion, $horacreacion, $solicitante,$fechasolicituddesde,$fechasolicitudhasta,$asignadoa);
		//		notificarCEstado($id,'','solicitado','',$estado);
				
			}
			$accion = 'La solicitud de flota #'.$id.' ha sido Creada exitosamente';
			bitacora($_SESSION['usuario'], "Solicitud de flota", $accion, $id, $query);

			echo true;
		}else{
			echo false;
		}
		
	}
	
	function actualizarIncidente(){
		global $mysqli;		
		$id   				= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$data 				= (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		$descripcion 		= (!empty($data['descripcion']) ? $data['descripcion'] : '');
		$iddepartamentos	= (!empty($data['iddepartamentos']) ? $data['iddepartamentos'] : '');
		$destino        	= (!empty($data['destino']) ? $data['destino'] : '');
		$serie 				= (!empty($data['serie']) ? $data['serie'] : '');
		$estado 			= (!empty($data['estado']) ? $data['estado'] : '0');
		$origen 			= (isset($data['origen']) ? $data['origen'] : 'sistema');
		$solicitante 		= (!empty($data['solicitante']) ? $data['solicitante'] : '');
		$creadopor			= (!empty($data['creadopor']) ? $data['creadopor'] : $_SESSION['correousuario']);
		$asignadoa 			= (!empty($data['asignadoa']) ? $data['asignadoa'] : '');
		$resolucion 		= (!empty($data['resolucion']) ? $data['resolucion'] : '');
		$fecharetiro 	    = (!empty($data['fecharetiro']) ? $data['fecharetiro'] : '');
		$fecharesolucion 	= (!empty($data['fecharesolucion']) ? $data['fecharesolucion'] : '');
		$fechacierre 		= (!empty($data['fechacierre']) ? $data['fechacierre'] : '');
		$horacierre 		= (!empty($data['horacierre']) ? $data['horacierre'] : '');
		$notificar 			= (!empty($data['notificar']) ? $data['notificar'] : '');
		$kilometrajeinicial = (!empty($data['kilometrajeinicial']) ? $data['kilometrajeinicial'] : '');
		$kilometrajefinal   = (!empty($data['kilometrajefinal']) ? $data['kilometrajefinal'] : '');
		$gasolinainicial    = (!empty($data['gasolinainicial']) ? $data['gasolinainicial'] : '');
		$gasolinafinal      = (!empty($data['gasolinafinal']) ? $data['gasolinafinal'] : '');
		$tarjetagasolina    = (!empty($data['tarjetagasolina']) ? $data['tarjetagasolina'] : '');
		$controlpuerta      = (!empty($data['controlpuerta']) ? $data['controlpuerta'] : '');
		$fechasolicituddesde		= (!empty($data['fechasolicituddesde']) ? $data['fechasolicituddesde'] : date("Y-m-d"));
		$fechasolicitudhasta		= (!empty($data['fechasolicitudhasta']) ? $data['fechasolicitudhasta'] : date("Y-m-d"));
		$estadoInc 			= '';
		$asignadoaInc 		= '';
		$idusuario 			= $_SESSION['user_id']; 
		
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

		$queryAsig = $mysqli->query("SELECT asignadoa FROM flotassolicitudes WHERE id = '$id'");
		if ($rowAsig = $queryAsig->fetch_assoc()) {
			$asignadoaInc = $rowAsig['asignadoa'];
		} 
		
		$queryInc = $mysqli->query("SELECT idestados FROM flotassolicitudes WHERE id = '$id'");
		if ($rowInc = $queryInc->fetch_assoc()) {
			$estadoOld = $rowInc['idestados']; 
		}
		$descripcion = str_replace("'","",$descripcion);		
		$campos = array(
		    'Solicitante' 			=> getValorEx('nombre','usuarios',$solicitante,'correo'),
			'Motivo por la cual requiere el auto' => $descripcion,
			'Destino' 		    => $destino,
			'Vehículos' => $serie,
			'Departamentos' 		=> getValor('nombre','departamentos',$iddepartamentos),
			'Estado' 				=> getValor('nombre','estados',$estado),
			'Conductor' 			=> getValorEx('nombre','usuarios',$asignadoa,'correo'),
			'Origen' 				=> $origen,
			'Fecha y hora de retiro' => $fecharetiro,
			'Fecha y hora de devolución' => $fecharesolucion,
			'Kilometraje inicial' => $kilometrajeinicial,
			'Kilometraje final' => $kilometrajefinal,
			'Gasolina inicial' => $gasolinainicial,
			'Gasolina final' => $gasolinafinal,
			'Tarjeta de gasolina' => $tarjetagasolina,
			'Control de puerta' => $controlpuerta,
			'Fecha de cierre' 		=> $fechacierre,
			'Hora de cierre' 		=> $horacierre,
			'Estado en el cual entrega el auto' => $resolucion
		);
		//und.unidad as 'Unidad ejecutora', 
		$valoresold = getRegistroSQL("SELECT a.descripcion as 'Motivo por la cual requiere el auto', a.idactivos as 'Vehículos',
		                              k.nombre as Departamentos, n.nombre as 'Conductor', o.nombre as Estado, a.origen as Origen,q.nombre as 'Solicitante',a.fecharetiro as 'Fecha y hora de retiro',a.fecharesolucion as 'Fecha y hora de devolución', a.fechacierre as 'Fecha de cierre', a.horacierre as 'Hora de cierre',a.resolucion as 'Estado en el cual entrega el auto',a.kilometrajeinicial AS 'Kilometraje inicial',a.kilometrajefinal AS 'Kilometraje final',a.gasolinainicial AS 'Gasolina inicial',a.gasolinafinal AS 'Gasolina final',a.tarjetagasolina AS 'Tarjeta de gasolina',a.controlpuerta AS 'Control de puerta',a.destino AS 'Destino'
										FROM flotassolicitudes a
										LEFT JOIN ambientes und ON a.idambientes = und.id
										LEFT JOIN departamentos k ON a.iddepartamentos = k.id 
										LEFT JOIN usuarios n ON a.asignadoa = n.correo
										LEFT JOIN estados o ON a.idestados = o.id
										LEFT JOIN usuarios q ON a.solicitante = q.correo
										WHERE a.id = '".$id."' ");
		
		$query = " UPDATE flotassolicitudes SET ";
		if(isset($data['descripcion'])){
			$query .= ", descripcion = '$descripcion' ";
		}
		if(isset($data['iddepartamentos'])){
			$query .= ", iddepartamentos = '$iddepartamentos' ";
		}
		if(isset($data['destino']) && $destino != ""){
			$query .= ", destino = '$destino' ";
		}
		if(isset($data['fechasolicituddesde'])){
			$query .= ", fechasolicituddesde = '$fechasolicituddesde' ";
		}
		if(isset($data['fechasolicitudhasta'])){
			$query .= ", fechasolicitudhasta = '$fechasolicitudhasta' ";
		}
		if(isset($data['serie'])){
			$query .= ", idactivos = '$serie' ";
		}
		if(isset($data['estado'])){
			$query .= ", idestados = '$estado' ";
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
		if(isset($data['fecharetiro']) && $data['fecharetiro'] != null){
			$query .= ", fecharetiro = '$fecharetiro' ";
		}
		if(isset($data['kilometrajeinicial'])){
			$query .= ", kilometrajeinicial = '$kilometrajeinicial' ";
		}
		if(isset($data['kilometrajefinal'])){
			$query .= ", kilometrajefinal = '$kilometrajefinal' ";
		}
		if(isset($data['gasolinainicial'])){
			$query .= ", gasolinainicial = '$gasolinainicial' ";
		}
		if(isset($data['gasolinafinal'])){
			$query .= ", gasolinafinal = '$gasolinafinal' ";
		}
		if(isset($data['tarjetagasolina'])){
			$query .= ", tarjetagasolina = '$tarjetagasolina' ";
		}
		if(isset($data['controlpuerta'])){
			$query .= ", controlpuerta = '$controlpuerta' ";
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
		if(isset($data['resolucion'])){
			$query .= ", resolucion = '$resolucion' ";
		}
		if($estadoInc != '56' && $estado == '56' ){
			$query .= " , resueltopor = '".$_SESSION['correousuario']."' ";
		}
		$query .= " WHERE id = $id ";
		$query = str_replace('SET ,','SET ',$query);
	//	echo('UPDATEINC:'.$query);
		
		if($mysqli->query($query)){
		    
			//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
			if($estadoInc != $estado){
				//CREAR REGISTRO EN ESTADOS INCIDENTES
				$queryE = " SELECT id, estadonuevo, fechadesde FROM flotassolicitudesestados WHERE idincidentes = '".$id."' ORDER BY id DESC LIMIT 1 ";
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
					$qfechac = " SELECT fechacreacion FROM flotassolicitudes WHERE id = '".$id."' ";
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
					$queryIncE = " UPDATE flotassolicitudesestados SET fechahasta = CURDATE(), horahasta = CURTIME() WHERE idincidentes = ".$id." AND id = ".$idIncEstad."";
					$mysqli->query($queryIncE);
				}
				$queryE = " INSERT INTO flotassolicitudesestados (idincidentes,estadoanterior,estadonuevo,usuario,fechadesde,horadesde,dias)
							VALUES (".$id.", '".$estadoanterior."', '".$estado."', ".$idusuario.", now(), now(), ".$dias.") ";
				//debugL('queryE:'.$queryE);
				$mysqli->query($queryE);			
				
				notificarCEstado($id,$notificar,'actualizado',$estadoanterior,$estado); //$incidente,$notificar,$accion,$estadoold,$estadonew
				//*******************************************//
				//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
				//*******************************************//
				
				//Usuarios de soporte
			//	$idusuarios["icarvajal"] = "0";
			//	$idusuarios["frios"] = "0";
				$idusuarios["aanderson"] = "0";
				$idusuarios["admin"] = "0";
				
				//ESTADO ANTERIOR 
				if($estadoOld != ''){
					$consultaEO = $mysqli->query("SELECT nombre FROM estados WHERE id = '".$estadoOld."' ");
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
				
				$usuarios = json_encode($idusuarios);
				
				$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,descripcion,fecha,hora,usuarios) VALUES (0,".$id.",'Cambio de estado flota',' ".$estadoant." a ".$estadonue."','". date('Y-m-d') ."','". date('H:i:s') ."','".$usuarios."')"; 
				$rsql = $mysqli->query($sql);
				
				//*******************************************//
				//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
				//*******************************************//																																		  
			}
			/*
			if($asignadoaInc != $asignadoa){
				notificarCAsignadoa($id,$notificar,'actualizado',$asignadoaInc,$asignadoa);
			} */
			/*
			if($solicitanteInc != $solicitante){
				notificarCSolicitante($id,$notificar,'actualizado',$solicitanteInc,$solicitante);
			} */
			//BITACORA
			actualizarRegistro('Solicitud de flota','Solicitud de flota',$id,$valoresold,$campos,$query);
			echo true;
		}else{
			echo false;
		} 
	}
	
	//ENVIAR CORREO AL SOLICITANTE DEL INCIDENTE Y SOPORTE
	function nuevoincidente($usuario,$descripcion, $incidente, $fecha, $hora, $solicitante,$fechasolicituddesde,$fechasolicitudhasta,$asignadoa){
		global $mysqli, $mail;
		
		$query  = " SELECT a.id,a.descripcion, a.destino AS ambiente,
					d.serie, q.nombre AS marca, r.nombre AS modelo, e.nombre AS estado, a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, IFNULL(j.nombre, a.solicitante) AS solicitante, CASE WHEN l.estado = 'Activo' THEN a.asignadoa WHEN l.estado = 'Inactivo' THEN '' END AS asignadoa,a.resolucion, IFNULL(k.nombre, a.resueltopor) AS resueltopor,
					IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0),CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion,
					IF(( a.fecharesolucion is not null OR LENGTH(ltrim(rTrim(a.fecharesolucion))) > 0),CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,a.fechasolicituddesde,a.fechasolicitudhasta,a.destino as destino,
					IF(( a.fechacierre is not null OR LENGTH(ltrim(rTrim(a.fechacierre))) > 0),CONCAT(a.fechacierre,'  ', a.horacierre),'') AS fechacierre,CASE WHEN i.estado = 'Activo' THEN IFNULL(i.correo, a.creadopor)
					WHEN i.estado = 'Inactivo' THEN '' END AS correocreadopor, a.notificar, CASE  WHEN j.estado = 'Activo' THEN IFNULL(j.correo, a.solicitante) WHEN j.estado = 'Inactivo' THEN '' END AS correosolicitante
					FROM flotassolicitudes a
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN activos d ON a.idactivos = d.id
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.resueltopor = k.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN marcas q ON d.idmarcas = q.id
					LEFT JOIN modelos r ON d.idmodelos = r.id 
					WHERE a.id = $incidente GROUP BY a.id ";
		//echo $query;
		$result = $mysqli->query($query);
		$row 	= $result->fetch_assoc();
		//1 para quien quien creo el incidentes (Creado por)
		
		//Excluir usuarios inactivos campo Creado por 
		if($row['correocreadopor'] != ""){
			$correo [] = $row['correocreadopor'];
		}		
		//3 para quien se le asigno el incidente (Asignado a)			
		//USUARIO O GRUPO DE USUARIOS ASIGNADOS
		$asignadoaN	= '';
		
		//Excluir usuarios inactivos campo Asignado a
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
				$query2 .= "correo = '".$row['asignadoa']."' AND estado = 'Activo' ";
			}else{
				$query2 .= "correo IN ('".$row['asignadoa']."') AND estado = 'Activo'  ";
			}
			$consulta = $mysqli->query($query2);
			while($rec = $consulta->fetch_assoc()){
				$asignadoaN .= $rec['nombre']." , ";
			}
		}

		//DATOS DEL CORREO
		$usuarioSes = $_SESSION['usuario'];
		$consultaUA = $mysqli->query("SELECT nombre FROM usuarios WHERE usuario = '".$usuarioSes."' LIMIT 1 ");
		while ($registroUA = $consultaUA->fetch_assoc()) {
			$usuarioAct = $registroUA['nombre'];
		}

		
		//DATOS
		$fechacreacion 	= $row['fechacreacion'];
		$descripcion	= $row['descripcion'];
		$solicitante	= $row['solicitante'];
		$creadopor		= $row['creadopor'];
		$fechasolicituddesde = $row['fechasolicituddesde'];
		$fechasolicitudhasta = $row['fechasolicitudhasta'];
		$sitio 			= $row['destino'];
		$resolucion 	= $row['resolucion'];
		$nasignadoa 	= $asignadoaN;
		
			$asunto = "Solicitud de flota #$incidente ha sido creado";
			
			$cuerpo = '';
			$cuerpo = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha creado la solicitud de flota #".$incidente." el ".$fechacreacion."</b></p>";
					$cuerpo .= "<p style='width:100%;'>
						<a href='http://toolkit.maxialatam.com/soporte/flota.php?id=".$incidente."' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Solicitud</a></p>
						<br><br>
						<p style='width:100%;'>".$descripcion."</p>
						<br>
						<br>
						<p style='width:100%;'>Fecha solicitada desde ".$fechasolicituddesde." - hasta ".$fechasolicitudhasta."</p>
						<br>
						<p style='width:100%;'>Creado desde el Sistema</p>
						<br>
						<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin: auto;padding: 10px;width:100%;'>Atributos</p>
						<table style='width: 50%;'>
							<tr>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Solicitante del servicio</div>".$solicitante."</td>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Destino</div>".$sitio."</td>
							</tr>
							<tr>
							    <td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Conductor</div>".$nasignadoa."</td>
							
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Recibido en</div>".$fechacreacion."</td>
								</tr>
						<table>
						";		
			//debugL("NuevoIncidente-CORREO:".json_encode($correo),"nuevoIncidente");
			//Correo
			enviarMensajeIncidente($asunto,$cuerpo,$correo,'','');
		}
	

	//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
	function notificarCEstado($incidente,$notificar,$accion,$estadoold,$estadonew){
		global $mysqli;
		
		$query  = " SELECT a.id,a.descripcion, a.destino AS ambiente,
					d.serie, q.nombre AS marca, r.nombre AS modelo, e.nombre AS estado, a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, IFNULL(j.nombre, a.solicitante) AS solicitante, CASE WHEN l.estado = 'Activo' THEN a.asignadoa WHEN l.estado = 'Inactivo' THEN '' END AS asignadoa,a.resolucion, IFNULL(k.nombre, a.resueltopor) AS resueltopor,
					IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0),CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion,
					IF(( a.fecharesolucion is not null OR LENGTH(ltrim(rTrim(a.fecharesolucion))) > 0),CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,a.fechasolicituddesde,a.fechasolicitudhasta,a.destino as destino,
					IF(( a.fechacierre is not null OR LENGTH(ltrim(rTrim(a.fechacierre))) > 0),CONCAT(a.fechacierre,'  ', a.horacierre),'') AS fechacierre,CASE WHEN i.estado = 'Activo' THEN IFNULL(i.correo, a.creadopor)
					WHEN i.estado = 'Inactivo' THEN '' END AS correocreadopor, a.notificar, CASE  WHEN j.estado = 'Activo' THEN IFNULL(j.correo, a.solicitante) WHEN j.estado = 'Inactivo' THEN '' END AS correosolicitante
					FROM flotassolicitudes a
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN activos d ON a.idactivos = d.id
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.resueltopor = k.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN marcas q ON d.idmarcas = q.id
					LEFT JOIN modelos r ON d.idmodelos = r.id 
					WHERE a.id = $incidente GROUP BY a.id ";
		//echo $query;
		$result = $mysqli->query($query);
		$row 	= $result->fetch_assoc();
		//1 para quien quien creo el incidentes (Creado por)
		
		//Excluir usuarios inactivos campo Creado por 
		if($row['correocreadopor'] != ""){
			$correo [] = $row['correocreadopor'];
		}		
		//3 para quien se le asigno el incidente (Asignado a)			
		//USUARIO O GRUPO DE USUARIOS ASIGNADOS
		$asignadoaN	= '';
		
		//Excluir usuarios inactivos campo Asignado a
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
				$query2 .= "correo = '".$row['asignadoa']."' AND estado = 'Activo' ";
			}else{
				$query2 .= "correo IN ('".$row['asignadoa']."') AND estado = 'Activo'  ";
			}
			$consulta = $mysqli->query($query2);
			while($rec = $consulta->fetch_assoc()){
				$asignadoaN .= $rec['nombre']." , ";
			}
		}
		//4 para los usuarios que quieren que se les notifique (Enviar Notificacion a)
		//else{
			if($accion == 'creado'){
				$asunto = "Solicitud de flota #$incidente ha sido Creado";
			}else{ //actualizado
				if ($estadoold != $estadonew && $estadonew == 13) {
					$asunto = "Solicitud de flota #$incidente ha sido Asignado";
				} elseif ($estadoold != $estadonew && $estadonew == 56) {
					$asunto = "solicitud de flota #$incidente ha sido Devuelto";	
				}
				else {
					$asunto = "Solicitud de flota #$incidente ha sido Actualizado";
				}
			}
		//}
		//DATOS DEL CORREO
		$usuarioSes = $_SESSION['usuario'];
		$consultaUA = $mysqli->query("SELECT nombre FROM usuarios WHERE usuario = '".$usuarioSes."' LIMIT 1 ");
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
			$consultaEN = $mysqli->query("SELECT nombre FROM estados WHERE id = '".$estadonew."' ");
			$registroEN = $consultaEN->fetch_assoc();
			$estadonue = $registroEN['nombre'];
		}else{
			$estadonue = '';
		}
		
		//DATOS
		$fechacreacion 	= $row['fechacreacion'];
		$descripcion	= $row['descripcion'];
		$solicitante	= $row['solicitante'];
		$creadopor		= $row['creadopor'];
		$fechasolicituddesde = $row['fechasolicituddesde'];
		$fechasolicitudhasta = $row['fechasolicitudhasta'];
		$sitio 			= $row['destino'];
		$resolucion 	= $row['resolucion'];
		$placa 	        = $row['serie'];
		$marca 	        = $row['marca'];
		$modelo 	    = $row['modelo'];
		$nasignadoa 	= $asignadoaN;
		//MENSAJE
		if($accion == 'creado'){
			$mensaje = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha creado la solicitud de flota #".$incidente."</b></p>";
		}else{ //actualizado
			$mensaje = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha actualizado la solicitud de flota #".$incidente."</b></p>";		
		}
		
		if($estadonew == 13){
			$mensaje .= "<p style='padding-left: 30px;width:100%;'>La solicitud de flota ha sido asignado con el auto :  ".$marca." ".$modelo." ".$placa."</p>";
		}elseif($estadoant !='' && $estadonue !=''){
			$mensaje .= "<p style='padding-left: 30px;width:100%;'>El Estado cambió de ".$estadoant." a ".$estadonue."</p>";
		}
		$mensaje .= "<p style='width:100%;'>
						<a href='http://toolkit.maxialatam.com/soporte/flota.php?id=".$incidente."' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Solicitud</a></p>
						<br><br>
						<p style='font-size: 18px;width:100%;'>".$creadopor." ha creado esta solicitud de flota el ".$fechacreacion."</p>
						<br>
						<p style='width:100%;'>".$descripcion."</p>
						<br>
						<br>
						<p style='width:100%;'>Fecha solicitada desde ".$fechasolicituddesde." hasta ".$fechasolicitudhasta."</p>
						<br>
						<p style='width:100%;'>Creado desde el Sistema</p>
						<br>
						<p style='background-color: #f5f5f5;color: #999999;font-size: 17px;margin: auto;padding: 10px;width:100%;'>Atributos</p>
						<table style='width: 50%;'>
							<tr>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Solicitante del servicio</div>".$solicitante."</td>
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Destino</div>".$sitio."</td>
							</tr>
							<tr>
							    <td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Conductor</div>".$nasignadoa."</td>
							
								<td style='padding: 15px 0;'><div style='font-size: 14px;color: #808080;'>Recibido en</div>".$fechacreacion."</td>
								</tr>
						<table>
						";
			if($estadonew == 56){
				//GENERAR FECHA DE CIERRE 
				$query = "  UPDATE flotassolicitudes SET fechacierre = DATE_ADD(fecharesolucion, INTERVAL 3 DAY), horacierre = horaresolucion, 
							idestados = 56 WHERE id = '".$incidente."' ";
				$mysqli->query($query);
				$mensaje .= "<br><br><p style='width:100%;'><b>Resolución: </b>".$resolucion."</p>";
			}
			
			$mensaje .= "</div>";

		//USUARIOS DE SOPORTE
		//$correo [] = 'ana.porras@maxialatam.com';
		$correo [] = 'maria.baena@maxialatam.com';
		$correo [] = 'jesus.barrios@maxialatam.com';
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'maylin.aguero@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		
		/* ******************************************************************************** /
		//	Si el solicitante es la AIG solo se le enviará un correo al cambiar el estado 
		//  del incidente a Resuelto
		// ******************************************************************************** */
		
		if($_SESSION['nivel'] == 4){
			$num 	= $_SESSION['user_id'];
			$from 	= '../flotastemp/'.$num;
			$adjuntos = array();
			//Abro el directorio que voy a leer
			$dir = opendir($from);
			//Recorro el directorio para leer los archivos que tiene
			while(($fileE = readdir($dir)) !== false){
				//Leo todos los archivos excepto . y ..
				if ($fileE != "." && $fileE != ".." && $fileE != ".quarantine" && $fileE != ".tmb" && $fileE != "comentarios"){ 
					$archivo = '../flotastemp/'.$num.'/'.$fileE;
					$adjuntos[] = $archivo;
				}
			}
		}else{
			$adjuntos = '';
		}
																														
		//AQUIIII
		/*SELECT * FROM notificacionesxusuarios nu
		left join usuarios u on u.id = nu.idusuario
		where u.correo in () and noti1 = 1;*/
// 		echo "Correos antes: <br>";
// 		print_r($correo);
// 		echo "<br><br>";
		if($accion == 'creado'){
			foreach ($correo as $key => $value) { 
				$querycorreo = "SELECT * FROM notificacionesxusuarios nu
								INNER JOIN usuarios u on u.id = nu.idusuario
								where u.correo = '$value'";
				$consultacorreo = $mysqli->query($querycorreo);
				if($consultacorreo->num_rows == 0){
					unset($correo[$key]);
				}
			}									
		}else if($estadonew == 56){
			foreach ($correo as $key => $value) { 
				$querycorreo = "SELECT * FROM notificacionesxusuarios nu
								INNER join usuarios u on u.id = nu.idusuario
								where u.correo = '$value'";
				$consultacorreo = $mysqli->query($querycorreo);
				if($consultacorreo->num_rows == 0){
					unset($correo[$key]);
				}
			}									
		}
        debugL("notificarCEstado-CORREO:".json_encode($correo),"notificarCEstado"); 
			enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
		
		
		//enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
	}
	
	//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
	function notificarCAsignadoa($incidente,$notificar,$accion,$asignadoaInc,$asignadoa){
		global $mysqli;
		
		$query  = " SELECT a.id,a.descripcion,IFNULL(i.nombre, a.creadopor) AS creadopor, CASE WHEN 
					j.estado = 'Activo' THEN a.asignadoa WHEN j.estado = 'Inactivo' THEN '' END AS asignadoa, 
					IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0),CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion, CASE WHEN i.estado = 'Activo' THEN IFNULL(i.correo, a.creadopor) WHEN i.estado = 'Inactivo' THEN '' END AS correocreadopor
					FROM flotassolicitudes a 
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.asignadoa = j.correo 
					WHERE a.id = $incidente GROUP BY a.id ";
					
		//echo $asignadoaInc;	
		$result = $mysqli->query($query);
		$row 	= $result->fetch_assoc();
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
				$query2 .= "correo = '".$row['asignadoa']."' AND estado = 'Activo' ";
			}else{
				$query2 .= "correo IN ('".$row['asignadoa']."') AND estado = 'Activo' ";
			}
			$consulta = $mysqli->query($query2);
			while($rec = $consulta->fetch_assoc()){
				$asignadoaN .= $rec['nombre']." , ";
			}
		}
		
		//ASIGNADOA ANTERIOR
		$consultaAO = $mysqli->query("SELECT nombre FROM usuarios WHERE correo = '".$asignadoaInc."' AND estado = 'Activo' ");
		if($consultaAO->num_rows > 0){
			$registroAO = $consultaAO->fetch_assoc();
			$asignadoaant = $registroAO['nombre'];
		}else{
			$asignadoaant = '';
		}
		
		//ASIGNADOA NUEVO
		$consultaAN = $mysqli->query("SELECT nombre FROM usuarios WHERE correo = '".$asignadoa."' AND estado = 'Activo' ");
		if($consultaAN->num_rows > 0){
			$registroAN = $consultaAN->fetch_assoc();
			$asignadoanue = $registroAN['nombre'];
		}else{
			$asignadoanue = '';
		}
		//debug('anterior:'.$asignadoaant.'-'.);
		//DATOS
		$fechacreacion 	= $row['fechacreacion'];
		$descripcion	= $row['descripcion']; 
		$creadopor		= $row['creadopor']; 
		$nasignadoa 	= $asignadoaN;
		
		$asunto = "Solicitud de flota #$incidente ha sido Actualizado";
		
		//MENSAJE 
		$mensaje = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'>La solicitud de flota #".$incidente." ha sido modificado de Asignado: ".$asignadoaant." a: ".$asignadoanue."</p>";
		 
		$mensaje .= "<p style='width:100%;'>
						<a href='http://toolkit.maxialatam.com/soporte/flota.php?id=".$incidente."' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver solicitud</a></p>
						<br><br>
						<p style='font-size: 18px;width:100%;'>".$creadopor." ha creado esta solicitud de flota el ".$fechacreacion."</p>
						<br>
						<p style='width:100%;'>".$descripcion."</p>
						<br> 
						";  
		$mensaje .= "</div>";

		//USUARIOS DE SOPORTE
	//  $correo [] = 'jesus.barrios@maxialatam.com';
		$correo [] = 'maria.baena@maxialatam.com';
		$correo [] = 'jesus.barrios@maxialatam.com';
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'maylin.aguero@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
	
		debug("notificarCAsignadoa-CORREO:".json_encode($correo),"notificarCAsignadoa");					 
		enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
	}
	
	

	function notificarCSolicitante($incidente,$notificar,$accion,$solicitanteInc,$solicitante){
		global $mysqli;
		
		$query  = " SELECT a.id, a.titulo, a.descripcion,IFNULL(i.nombre, a.creadopor) AS creadopor, a.asignadoa,
					CASE WHEN 	j.estado = 'Activo' THEN a.solicitante WHEN j.estado = 'Inactivo' THEN '' END AS solicitante, IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0),CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion, CASE WHEN j.estado = 'Activo' THEN IFNULL(i.correo, a.creadopor)
                   	WHEN j.estado = 'Inactivo' THEN '' END AS correocreadopor, a.idclientes, a.idproyectos
					FROM flotassolicitudes a 
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo 
					WHERE a.id = $incidente GROUP BY a.id ";
					
		//echo $asignadoaInc;	
		$result = $mysqli->query($query);
		$row 	= $result->fetch_assoc();
		$idclientes = $row['idclientes'];
		$idproyectos = $row['idproyectos'];
		//1 para quien quien creo el incidentes (Creado por)
		if($row['correocreadopor'] != ""){
			$correo [] = $row['correocreadopor'];
		} 
		
		//2 para quien se le asigno el incidente (Asignado a)	
		
		//USUARIO O GRUPO DE USUARIOS ASIGNADOS
		$solicitanteN	= '';
		if($row['solicitante'] != ''){
			$solicitante  = $row['solicitante'];
			if (filter_var($solicitante, FILTER_VALIDATE_EMAIL)) {
				$correo [] = "$solicitante";				
			}else{
				foreach([$solicitante] as $solic){
					$correo [] = $solic;
				}
			}
			$query2 = " SELECT nombre FROM usuarios WHERE ";
			if (filter_var($row['solicitante'], FILTER_VALIDATE_EMAIL)) {
				$query2 .= "correo = '".$row['solicitante']."' AND estado = 'Activo' ";
			}else{
				$query2 .= "correo IN ('".$row['solicitante']."') AND estado = 'Activo' ";
			}
			$consulta = $mysqli->query($query2);
			while($rec = $consulta->fetch_assoc()){
				$solicitanteN .= $rec['nombre']." , ";
			}
		}
		
		//ENVIAR CORREO DEL INCIDENTE A LOS USUARIOS SELECCIONADOS
		//4 para los usuarios que quieren que se les notifique (Enviar Notificacion a)
		if($notificar != '[]' && $notificar != ''){
			$asunto    = "Notificación del Solicitud de flota #$incidente";
			$notificar = json_decode($notificar);
			if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
				
				//Excluir a usuarios inactivos del campo Notificar a 
				$result = $mysqli->query("SELECT correo FROM usuarios WHERE correo = '".$notificar."' AND estado = 'Activo' ");
				if ($row=$result->fetch_assoc()) {
					$r = $row['correo'];
					if($r != ""){
						$correo [] = "$notificar";
					} 
				} 
				
			}else{
				foreach($notificar as $notif){
					
					//Excluir a usuarios inactivos del campo Notificar a 
					$result = $mysqli->query("SELECT correo FROM usuarios WHERE correo = '".$notif."' AND estado = 'Activo' ");
					if ($row=$result->fetch_assoc()) {
						$r = $row['correo'];
						if($r != ""){
							$correo [] = $notif;
						} 
					}  
				}
			}
		}
		
		//ASIGNADOA ANTERIOR
		$consultaAO = $mysqli->query("SELECT nombre FROM usuarios WHERE correo = '".$solicitanteInc."' AND estado = 'Activo' ");
		if($consultaAO->num_rows > 0){
			$registroAO = $consultaAO->fetch_assoc();
			$solicitanteant = $registroAO['nombre'];
		}else{
			$solicitanteant = '';
		}
		
		//ASIGNADOA NUEVO
		$consultaAN = $mysqli->query("SELECT nombre FROM usuarios WHERE correo = '".$solicitante."' AND estado = 'Activo' ");
		if($consultaAN->num_rows > 0){
			$registroAN = $consultaAN->fetch_assoc();
			$solicitantenue = $registroAN['nombre'];
		}else{
			$solicitantenue = '';
		}
		//debug('anterior:'.$asignadoaant.'-'.);
		//DATOS
		$fechacreacion 	= $row['fechacreacion'];
		$titulo			= $row['titulo'];
		$descripcion	= $row['descripcion']; 
		$creadopor		= $row['creadopor']; 
		$nsolicitante 	= $solicitanteN;
		
		$asunto = "Solicitud de flota #$incidente ha sido Actualizado";
		
		//MENSAJE 
		$mensaje = "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%;'>La solicitud de flota #".$incidente." ha sido modificado de Solicitante: ".$solicitanteant." a: ".$solicitantenue."</p>";
		 
		$mensaje .= "<p style='width:100%;'>
						<a href='http://toolkit.maxialatam.com/soporte/flota.php?id=".$incidente."' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver solicitud</a></p>
						<br><br>
						<p style='font-size: 18px;width:100%;'>".$creadopor." ha creado esta solicitud de flota el ".$fechacreacion."</p>
						<br>
						<p style='width:100%;'>".$titulo."</p>
						<br>
						<p style='width:100%;'>".$descripcion."</p>
						<br> 
						";  
		$mensaje .= "</div>";

		//USUARIOS DE SOPORTE
		//$correo [] = 'ana.porras@maxialatam.com';
	//	$correo [] = 'jesus.barrios@maxialatam.com';
		$correo [] = 'maria.baena@maxialatam.com';
		$correo [] = 'jesus.barrios@maxialatam.com';
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'maylin.aguero@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
		
			//debugL("notificarCSolicitante-CORREO: ANTESSS".json_encode($correo),"notificarCSolicitante");
		//Correos PM Tigo
		foreach ($correo as $key => $value) { 
			if ($value == 'soportemaxia@zertifika.com' || $value == 'jose.barahona@tigo.com.pa' || $value == 'mariano.saibene@tigo.com.pa' || $value == 'fabio.beascoechea@tigo.com.pa') { 
				unset($correo[$key]); 
			}
		}
		
		 
		foreach ($correo as $key => $value) { 
			$querycorreo = "SELECT * FROM notificacionesxusuarios nu
							left join usuarios u on u.id = nu.idusuario
							where u.correo = '$value' and noti11 = 1";
			$consultacorreo = $mysqli->query($querycorreo);
			if($consultacorreo->num_rows == 0){
				unset($correo[$key]);
			}
		}
		//debugL("notificarCSolicitante-CORREO: DESPUESSS".json_encode($correo),"notificarCSolicitante");					 
		enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
	}
	
	function enviarMensajeIncidente($asunto,$mensaje,$correos,$adjuntos,$tipo) {
		global $mysqli, $mail;
		$correo = array_unique($correos);
		$cuerpo = "";
		$cuerpo .= "<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; '>";
		$cuerpo .= "<img src='http://toolkit.maxialatam.com/repositorio-tema/assets/img/logosym-header.png' style='width: auto; float: left;'>";
		$cuerpo .= "<p style='margin:auto; font-weight:bold; width: 100%; text-align: center;'>Maxia Toolkit<br>";
		$cuerpo .= "Gestión de Soporte<br>";
		$cuerpo .= "</div>";
		$cuerpo .= $mensaje;
		$cuerpo .= "<div style='background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;clear: both;'>";
		$cuerpo .= "© ".date('Y')." Maxia Latam";
		$cuerpo .= "</div>";	
		//Eliminar correo Sin Especificar
		foreach ($correo as $key => $value) { 
			if ($value == 'sinespecificar@maxialatam.com') { 
				unset($correo[$key]); 
			}
		}
		$mail->clearAddresses();
		foreach($correo as $destino){
			if($tipo == 'comentario'){ 
				$mail->addAddress($destino); // EVITAR ENVÍO DE CORREO CLIENTES (DESACTIVADO)
			}else{
				if( $destino != 'mesadeayuda@innovacion.gob.pa'){
					$mail->addAddress($destino); // EVITAR ENVÍO DE CORREO CLIENTES (DESACTIVADO)
				}
			}
		}		
		$mail->addAddress("jesus.barrios@maxialatam.com");
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
			if($adjuntos != ''){
				foreach($adjuntos as $adjunto){
					if(is_file($adjunto))
					unlink($adjunto); //elimino el fichero
				}
			}
		//	echo true;
		} 
	//	echo true;
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
					FROM flotassolicitudesestados a 
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
					WHERE a.modulo = 'Solicitud de flota' AND a.identificador = $id
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
	
	function guardarcolumnaocultar() {
		global $mysqli;
		$tipo 	 	    = $_REQUEST['tipo'];
		$columna 	 	= $_REQUEST['columna'];
		$usuario 		= $_SESSION['user_id'];
		$query = '';
		if($tipo == 'agregar'){
		    $querycolumnausuarios = "SELECT * FROM columnasocultas where modulo = 'Flotas' and usuario = '$usuario'";
		    $resultcolumnausuarios = $mysqli->query($querycolumnausuarios);
    		if($resultcolumnausuarios->num_rows > 0){
    		    $rowcolumnas = $resultcolumnausuarios->fetch_assoc();
    			$valorcolumnaanterior = $rowcolumnas['columnas'];
    			$columnaagregar = $valorcolumnaanterior.$columna.',';
    			$query = "UPDATE columnasocultas set columnas = '$columnaagregar' where modulo = 'Flotas' and usuario = '$usuario'";
    		}else{
    		    $columnaagregar = $columna.',';
    			$query = " INSERT INTO columnasocultas (id,columnas,usuario,modulo) VALUES (null,'$columnaagregar','$usuario','Flotas') ";
    		}
		}else{
		    $querycolumnausuarios = "SELECT * FROM columnasocultas where modulo = 'Flotas' and usuario = '$usuario'";
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
                    $query = "DELETE FROM columnasocultas where modulo = 'Flotas' and usuario = '$usuario'";
                }else{
    			    $query = "UPDATE columnasocultas set columnas = '$columnaguardar' where modulo = 'Flotas' and usuario = '$usuario'";
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
		$query = "SELECT columnas from columnasocultas where modulo = 'Flotas' and usuario = '$usuario'";
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
	
	
	function comentariovisto()
	{
		global $mysqli;

		$id = $_REQUEST['id'];
		$query = "UPDATE flotassolicitudes SET comentariovisto='1' WHERE id = '$id'";
		$mysqli->query($query);
	}
	
	function validarComentarios(){
		global $mysqli;
		
		$idincidente  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idestadosnew = (!empty($_REQUEST['idestadosnew']) ? $_REQUEST['idestadosnew'] : '');
		$asignadoanew = (!empty($_REQUEST['asignadoa']) ? $_REQUEST['asignadoa'] : '');
		
		$query = "	SELECT a.idestados, a.asignadoa, (SELECT COUNT(id) FROM comentarios WHERE modulo = 'Flotas' AND idmodulo = ".$idincidente." LIMIT 1) AS totalcomentarios 
					FROM flotassolicitudes a WHERE id = ".$idincidente." ";
		//debugL("VALIDARCOMENTARIOS:".$query); 
		$result = $mysqli->query($query);
		if($row = $result->fetch_assoc()){
			
			$idestadosold 	  = $row['idestados'];	
			$asignadoaold 	  = $row['asignadoa'];	
			$totalcomentarios = $row['totalcomentarios'];
			
			//debugL("IDESTADOSNEW:".$idestadosnew);
			//debugL("IDESTADOSOLD:".$idestadosold); 
			//debugL("TOTALCOMENTARIOS:".$totalcomentarios);
			
			 if($totalcomentarios > 0){
				// debugL("PASÓ1"); 
				  //Si tiene comentarios 
				  echo 0;
			 }else{
				 //Si no tiene comentarios 
				if(($idestadosnew != $idestadosold && $idestadosold == 12) || ($asignadoanew != "0" && $asignadoanew != "" && $asignadoaold == "")){
					//debugL("PASÓ2"); 
					//El incidente es nuevo, y se va a cambiar su estado a otro estado diferente o si va a ser asignado
					echo 1;
				}else{ 
					//debugL("PASÓ3"); 
					echo 0;
				}
			 }
		} 
	}	
		
	
	function limpiarFiltrosMasivos(){
		global $mysqli;
		$usuario = $_SESSION['usuario'];
		
		$query = "DELETE FROM usuariosfiltros WHERE modulo = 'Flotas' AND usuario = '$usuario' ";
		if($mysqli->query($query))
			echo true;
	}
	
	function guardarfiltros() {
		global $mysqli;
		$data = $_REQUEST['data'];
		$usuario = $_SESSION['usuario'];
		$query  = " SELECT * FROM usuariosfiltros WHERE modulo = 'Flotas' AND usuario = '$usuario' ";
		$result = $mysqli->query($query);
		$count = $result->num_rows;
		if( $count > 0 ) 
			$query = "UPDATE usuariosfiltros SET filtrosmasivos = '$data' WHERE modulo = 'Flotas' AND usuario = '$usuario'";
		else
			$query = "INSERT INTO usuariosfiltros VALUES (null, '$usuario', 'Flotas', '', '$data')";
		if($mysqli->query($query))
			echo true;
	}
	
	function abrirfiltros() {
		global $mysqli;
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Flotas' AND usuario = '".$_SESSION['usuario']."'";
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
		$query = " SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Flotas' AND usuario = '".$_SESSION['usuario']."'";
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
		$correo [] = 'maria.baena@maxialatam.com';
		$correo [] = 'jesus.barrios@maxialatam.com';
		$correo [] = 'fernando.rios@maxialatam.com';
		$correo [] = 'maylin.aguero@maxialatam.com';
		$correo [] = 'axel.anderson@maxialatam.com';
			
			$query  = " SELECT a.id, a.titulo, a.notificar, i.usuario AS usuariocreadopor, j.usuario AS usuariosolicitante, k.usuario AS usuarioasignadoa,
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
							AS asignadoa
						FROM flotassolicitudes a
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
					$query2 .= "correo = '".$row['asignadoa']."' AND estado = 'Activo'"; //jesus
				}else{
					$query2 .= "correo IN (".$row['asignadoa'].") AND estado = 'Activo'"; //jesus añadi la linea AND estado ='Activo'
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
						$queryn = " SELECT correo FROM usuarios WHERE correo = '".$notificar."' AND estado = 'Activo' ";
						$consultan = $mysqli->query($queryn);
						if($recn = $consultan->fetch_assoc()){
							$correo [] = $notificar;	
						} 
					}else{
						foreach($notificar as $notificarp){
							 
							//Excluir usuarios inactivos campo Notificar a 
							$queryn = " SELECT correo FROM usuarios WHERE correo = '".$notificarp."' AND estado = 'Activo' ";
							$consultan = $mysqli->query($queryn);
							if($recn = $consultan->fetch_assoc()){
								$correo [] = $notificarp;	
							}
						}
					}
				}
			}
			//*******************************************//
			//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
			//*******************************************//  
				 
			//Usuarios de soporte
			$idusuarios['icarvajal'] = "0";
			$idusuarios['frios'] = "0";
			$idusuarios['aanderson'] = "0"; 
			$idusuarios["admin"] = "0";
			
			//Usuarios relacionados a la flota
			if($row['usuariocreadopor'] !="") $idusuarios[$row['usuariocreadopor']] = "0";		
			if($row['usuarioasignadoa'] !="") $idusuarios[$row['usuarioasignadoa']] = "0"; 
			if($row['usuariosolicitante'] !="") $idusuarios[$row['usuariosolicitante']] = "0";
			if($usuarionotificar !="") $idusuarios[$usuarionotificar] = "0";
			
			$usuarios = json_encode($idusuarios);
			
			$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,fecha,hora,usuarios) VALUES (0,".$incidente.",'Adjunto realizado flota','". date("Y-m-d") ."','". date("H:i:s") ."','".$usuarios."')"; 
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
							<p style='font-size: 22px;width:100%;'><b>".$usuarioAct." ha adjuntado nuevo documento a la solicitud de flota #".$incidente."</b></p>";
			$cuerpo .= "	<p style='width:100%;'>
								<a href='http://toolkit.maxialatam.com/soporte/flota.php?id=".$incidente."' target='_blank' style='background-color: #008fc9;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver solicitud</a></p>
							</p>
						</div>
						";
			$cuerpo .= "<div style='background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;'>";
			$cuerpo .= "© ".date('Y')." Maxia Latam";
			$cuerpo .= "</div>";	
			
			$correo = array_unique($correo);
			//debug(json_encode($correo));
			//echo $correo;
			//debugL("notificacionAdjunto-CORREO:".json_encode($correo),"notificacionAdjunto");			
			
			foreach ($correo as $key => $value) { 
				$querycorreo = "SELECT * FROM notificacionesxusuarios nu
								left join usuarios u on u.id = nu.idusuario
								where u.correo = '$value' ";
				$consultacorreo = $mysqli->query($querycorreo);
				if($consultacorreo->num_rows == 0){
					unset($correo[$key]);
				}
			}
			foreach($correo as $destino){
				if( $destino != 'mesadeayuda@innovacion.gob.pa' ){
					$mail->addAddress($destino); // EVITAR ENVÍO DE CORREO CLIENTES (DESACTIVADO)
				}			   
			}
			
			$mail->addAddress("jesus.barrios@maxialatam.com");
		//	$mail->addAddress("fernando.rios@maxialatam.com");
		//	$mail->addAddress("axel.anderson@maxialatam.com");
			
			//$mail->addAddress("lisbethagapornis@gmail.com");
			$mail->FromName = "Maxia Toolkit - SYM";
			$mail->isHTML(true); // Set email format to HTML
			if($row['solicitante'] == 'mesadeayuda@innovacion.gob.pa' || $row['creadopor'] == 'mesadeayuda@innovacion.gob.pa'){
				$mail->Subject = $row['titulo'];
			}else{
				$mail->Subject = "Solicitud de flota #".$incidente." - Nuevo adjunto";
			}
			
			//$mail->MsgHTML($cuerpo);
			$mail->Body = $cuerpo;
			$mail->AltBody = "Maxia Toolkit - SYM ";
			 if(!$mail->send()) {
				echo 'Mensaje no pudo ser enviado. ';
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				//echo 'Ha sido enviado el correo Exitosamente';
				echo true;
			} 
			echo 1;
		}else{
			echo 0;
		}		
	}


?>