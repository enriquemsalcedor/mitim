<?php
    include("../conexion.php");
    
    $mysqli = new mysqli("67.17.202.170", "maxia", "maxiaadm", "products");
    //$mysqli = new mysqli("127.0.0.1", "root", "", "soporte");
    if ($mysqli->connect_error) {
        echo "Fallo al conectar a MySQL: (" . $mysqli->connect_error . ") " . $mysqli->connect_error;
    }
    $sistemaactual = 'MITIM';
    $mysqli->query("SET NAMES utf8"); 
    $mysqli->query("SET CHARACTER SET utf8");
	
	require_once("Encoding.php");
	use \ForceUTF8\Encoding;
	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	debug('Productos');

	switch($oper){
		case "productos":
			  productos();
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
		case  "existeSubcategoria":
			  existeSubcategoria();
			  break;
		case  "encuestasIncidente":
			  encuestasIncidente();
			  break;
		case  "validarComentarios":
			  validarComentarios();
			  break; 
		case  "enviarEncuesta":
			  enviarEncuesta();
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
		case  "facturacion":
			  facturacion();
			  break;
		case  "agregar_item_facturacion":
			  agregar_item_facturacion();
			  break;
		case  "eliminar_item_facturacion":
			  eliminar_item_facturacion();
			  break;
		case  "adjunto_item_facturacion":
			  adjunto_item_facturacion();
			  break;
		case  "abrirCorrectivoTemp":
			  abrirCorrectivoTemp();   
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
	
function productos()
	{
		global $mysqli;
		
		//FILTROS MASIVO
		$nivel = $_SESSION['nivel'];
		$where = "";  
		$where2 = array();		
		$data2 = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');		
		$searchGeneral = (!empty($_POST['search']['value']) ? $_POST['search']['value'] : '');
		$data = "";
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : '');
	    $start = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$rowperpage = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
        $vacio = array();
		$columns = (!empty($_REQUEST['columns']) ? $_REQUEST['columns'] : $vacio);
		$usuario  = $_SESSION['usuario'];
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Productos' AND usuario = '".$_SESSION['usuario']."'";
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
			$where = str_replace($vowels, "", $where);
		}
		
		$idusuario = $_GET['user_id'];
		$query  = " SELECT * 
		            FROM product_table
					";
		$hayFiltros = 0;
		for($i=0 ; $i<count($columns);$i++){
			$column = $_REQUEST['columns'][$i]['data'];
			if ($_REQUEST['columns'][$i]['search']['value']!="") {

				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);

				if ($column == 'id') {
					$column = 'id';
					$where2[] = " $column like '%".$campo."%' ";
				}				
				if ($column == 'nombre') {
					$column = 'name';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'precio') {
					$column = 'price';
					$where2[] = " $column = ".$campo;
				}
				if ($column == 'existencia') {
					$column = 'existence';
					$where2[] = " $column = ".$campo;
				}
				if ($column == 'cart') {
					$column = 'cart';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'ubicacion') {
					$column = 'ubicacion';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'compania') {
					$column = 'company';
					$where2[] = " $column like '%".$campo."%' ";
				}
				$hayFiltros++;
			}
		}		
		
		if ($hayFiltros > 0){
			$where .= " AND ".implode(" AND " , $where2)." ";// id like '%searchValue%' or name like '%searchValue%'
		}

		if($searchGeneral!=""){
			$where.= " AND (
				id like '%".$searchGeneral."%' or 
				name like '%".$searchGeneral."%' or 
				ubication like '%".$searchGeneral."%' or 
				company like '%".$searchGeneral."%'  
			) ";

		}

		$query  .= " $where ";

		$f = fopen('debugprod.txt','w');
		fwrite($f,$query);
		fclose($f);

		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		//$query  .= " ORDER BY name DESC  LIMIT ".$start.",".$rowperpage;
		$query  .= " LIMIT ".$start.",".$rowperpage;
		$resultado = array();
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			//ADJUNTOS INCIDENTES
			$evid = '';
			$color = 'info';
			$span_evid = '<span class="btn-icon btn-xs" id="boton-evidencias" style="position: absolute;top: 12px;right: 0;padding: 0;">
						    <i class="fa fa-camera text-green i-header" aria-hidden="true" style="cursor: initial;"></i>
						 </span>';
			
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
									<span class= "msj-'.$row['id'].' '.$clasecomen.'" style="position: absolute;top: -8px;right: 0;">'.$iconcoment.'</span>
									'.$evid.'
								</div>
								<div class="dropdown-menu dropdown-menu-right droptable">
									<a class="dropdown-item text-info" href="correctivo.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>
									
									';
			if($nivel == 1 || $nivel == 2 || $nivel == 7){
				$acciones .= '<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>
				<a class="dropdown-item text-'.$color.' boton-evidencias"  data-id='.$row['id'].' "><i class="fas fa-camera mr-2"></i>Evidencias</a>';
			}else{
				$acciones .= '<a class="dropdown-item text-'.$color.' boton-evidencias"  data-id='.$row['id'].' "><i class="fas fa-camera mr-2"></i>Evidencias</a>';
			}

			$acciones .= 		'</div>
							</div>
						</td>';
			
			$array = json_decode($row['ubication']);
			$cadena = '<ul>';
			foreach ($array as $nombre => $value) {
               $cadena .= '<li><a href="https://www.google.es/maps?q='. $value[0] .', '.$value[1].'" target=”_blank”>'.$nombre.' <i class="fa fa-map-marker" aria-hidden="true"></i></a></li>';
            }
            $cadena .= '</ul>';
			
			//Calcular horas
			date_default_timezone_set("America/Panama"); 
			$fechahoy = date("Y-m-d");
			$horahoy  = date("H:i:s");

    	    $resultado[] = array(			
				'check' 		=>	"",
				'acciones' 		=> $acciones, 
				'id' 			=> $row['id'],
				'name' 			=> $row['name'],
				'price'		    => $row['price'],
				'existence'		=> $row['existence'],
				'cart'		    => $row['cart'],
				'ubication'	    => $cadena, // $row['ubication'],
				'created_date'	=> $row['created_date'],
				'image'			=> '<img src="'.$row['image'].'" alt="Girl in a jacket" width="150">',
				'company'		=> $row['company']
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













	function diferencia($fechadesde,$horadesde,$fechahasta,$horahasta){
		
		$fecha1 = strtotime($fechadesde."".$horadesde);
		$fecha2 = strtotime($fechahasta."".$horahasta);  
		$dif = $fecha2 - $fecha1; 

		$horas = floor($dif/3600);
		$minutos = floor(($dif-($horas*3600))/60);
		$segundos = $dif-($horas*3600)-($minutos*60);
		 
		return $horas;
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
		bitacora($_SESSION['usuario'], "Incidentes", 'El Correctivo #: '.$id.' fue eliminado.', $id, $query);
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
		bitacora($_SESSION['usuario'], "Incidentes", 'El Comentario #: '.$id.' fue eliminado.', $id, $query); 
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
		$Path2 = '/../incidentes/incidente/';
		//debugL('$Path: '.$incidente);
		$hash = strtr(base64_encode($Path), '+/=', '-_.');
		$hash = rtrim($hash, '.');
		echo "l1_". $hash;
	}
	
	function encuestasIncidente(){
		global $mysqli;
		$incidente	= $_REQUEST['idincidente'];
		$resultadosEncuesta = '';
		$valores = array('', 'Muy insatisfecho', 'Insatisfecho', 'Neutral', 'Satisfecho', 'Muy satisfecho');
		
		$queryF	  	= " SELECT b.nombre AS encuesta, c.nombre AS pregunta, a.evaluacion
						FROM encuestasresultados a 
						INNER JOIN encuestas b ON a.idencuestas = b.id
						INNER JOIN encuestaspreguntas c ON b.id = c.idencuestas
						WHERE a.idincidentes = '".$incidente."' AND evaluacion != 0 ";
		$resultF 	= $mysqli->query($queryF);
		if($resultF->num_rows > 0){
			
			while ($row = $resultF->fetch_assoc()) {
				$encuesta = $row['encuesta'];
				$pregunta = $row['pregunta'];
				$evaluacion = intval($row['evaluacion']);
				$img = str_replace(' ','',$valores[$evaluacion]).'.png';
				$res = $valores[$evaluacion];
				
				$resultadosEncuesta .= '<div style="display: inline-block;width: 100%;">
											<h3 style="text-align: center;">'.$encuesta.'</h3>
											<h5>'.$pregunta.'</h5>
											<div style="float:left">
												<img src="https://toolkit.maxialatam.com/soporteqa/images/encuesta/'.$img.'" style="margin: 0 auto" />
												<p>'.$res.'</p>
											</div>
										</div>';
			}
		}else{
			$resultadosEncuesta = '<h4>No hay resultados de encuesta para este correctivo</h4>';
		}
		echo $resultadosEncuesta;
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
			$result = $mysqli->query("SELECT titulo,solicitante FROM incidentes WHERE id = '".$incidente."' ");
			//debugL('agregarComentario - result - '."SELECT titulo,solicitante FROM incidentes WHERE id = '".$incidente."' ");
			while ($row = $result->fetch_assoc()) {
				$titulo = $row['titulo'];
				$solicitante = $row['solicitante'];
			}
			crearMensajeEncuesta($incidente,$titulo,$solicitante,1,$idusuario);
		}*/
		
		if($comentario != ''){
			$queryI = "INSERT INTO comentarios VALUES(null, 'Incidentes', $incidente, '$comentario', '$visibilidad', '$usuario', NOW(), 'NO')";
			//debug('queryI: '.$_GET['comentario']);
			if($mysqli->query($queryI)){
				$id = $mysqli->insert_id;
				//BITACORA
				bitacora($_SESSION['usuario'], "Incidentes", "Se ha registrado un Comentario para el Correctivo #".$incidente, $incidente, $queryI);
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
				
				//Usuarios asociados al correctivo
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
				
				$usuarios = json_encode($idusuarios);
				
				$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,fecha,hora,usuarios) VALUES (".$idproyectos.",".$incidente.",'Comentario realizado correctivo','". date("Y-m-d") ."','". date("H:i:s") ."','".$usuarios."')"; 
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
	function enviarEncuesta(){
		global $mysqli;
		$idincidente  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : "");
		$user_id 	  = (!empty($_SESSION['user_id']) ? $_SESSION['user_id'] : "");

		$query = "SELECT titulo,solicitante FROM incidentes WHERE id = '".$idincidente."' LIMIT 1";
		
		$result = $mysqli->query($query);

		if ($row = $result->fetch_assoc()) {

			$titulo = $row['titulo'];
			$solicitante = $row['solicitante'];

		}
		//debugL("VA A ENVIAR LA ENCUESTA CON LOS DATOS : IDINCIDENTE".$idincidente."- TITULO:".$titulo."-SOLICITANTE:".$solicitante."-USERID:".$user_id);
		crearMensajeEncuesta($idincidente,$titulo,$solicitante,1,$user_id);
		echo 1;
	}
	
	function comentarios(){
		global $mysqli;
		
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		
		$nivel 		= $_SESSION['nivel'];
		$id 		= (!empty($_GET['id']) ? $_GET['id'] : 0);
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
					WHERE modulo = 'Incidentes' AND idmodulo IN ($idmodulo) AND a.visibilidad != '' ";
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

	function facturacion(){
		global $mysqli;
		
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		
		$nivel 		= $_SESSION['nivel'];
		$id 		= (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado 	= array();
		$acciones 	= '';
		 
		$query  = " SELECT a.id, a.idmodulo, a.descripcion, a.monto, b.nombre AS usuario, a.fecha
					FROM incidentesfacturacion a
					LEFT JOIN usuarios b ON a.usuario = b.usuario
					WHERE a.idmodulo = ".$id." ";
		 
		$query .= " ORDER BY a.id DESC "; 
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows; 
		while($row = $result->fetch_assoc()){ 
			
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-right droptable"> ';
			 
			$acciones .= '<a class="dropdown-item text-danger boton-eliminar-facturacion" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar Item</a>'; 

			$acciones .= 		'</div>
							</div>
						</td>';

			$resultado[] = array(
				'id' 			=> $row['id'],
				'acciones' 		=> $acciones,
				'descripcion' 	=> $row['descripcion'],
				'monto'			=> number_format((float)$row['monto'], 2, '.', ','),
				'usuario'		=> $row['usuario'],
				'fecha' 		=> $row['fecha']
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
					VALUES(".$idincidentes.", 'correctivos', '".$descripcion."', '".$monto."', '".$usuario."', NOW())";
		$result = $mysqli->query($queryI);
		if($result == true){
			$id = $mysqli->insert_id;
			//BITACORA
			bitacora($_SESSION['usuario'], "Incidentes", "Se ha registrado un registro de costo para el Correctivo #".$idincidentes, $idincidentes, $queryI);
			
			//Carpeta incidentes
			$myPathBase = '../incidentes/';
			if (!file_exists($myPathBase))
				mkdir($myPathBase, 0777);
			
			//Se crea carpeta de incidente
			$myPathInc = '../incidentes/'.$idincidentes.'/';
			$target_pathInc = utf8_decode($myPathInc);
			if (!file_exists($target_pathInc))
				mkdir($target_pathInc, 0777);
			//Se crea carpeta de costos
			$myPathCostos = '../incidentes/'.$idincidentes.'/costos';
			$target_pathC = utf8_decode($myPathCostos);
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

	function agregar_item_facturacion(){
		global $mysqli;
		$idincidentes = $_REQUEST['id'];
		$descripcion  = $_REQUEST['descripcion'];
		$monto 		  = $_REQUEST['monto'];
		$usuario 	  = $_SESSION['usuario']; 
		$fecha 		  = date("Y-m-d H:i:s");
		 
		$queryI = " INSERT INTO incidentesfacturacion (idmodulo,modulo,descripcion,monto,usuario,fecha)
					VALUES(".$idincidentes.", 'correctivos', '".$descripcion."', '".$monto."', '".$usuario."', NOW())";
		$result = $mysqli->query($queryI);
		if($result == true){
			$id = $mysqli->insert_id;
			//BITACORA
			bitacora($_SESSION['usuario'], "Incidentes", "Se ha creado un registro de facturación para el Correctivo #".$idincidentes, $idincidentes, $queryI); 
			echo 1;
		}else{
			echo 0;
		} 
	}
			
	function eliminar_item_facturacion()
	{
		global $mysqli;

		$idincidente = $_REQUEST['idincidente'];
		$id_item 	 = $_REQUEST['id_item']; 
		$nivel 	 	 = $_SESSION['nivel'];
		$usuario 	 = $_SESSION['usuario'];
		 
		$query    = " DELETE FROM incidentesfacturacion WHERE id = ".$id_item."";
		$result   = $mysqli->query($query);
		if($result == true){
			deleteDirectory($carpeta);
			echo 1;
		}else{
			echo 0;
		}
		 
		bitacora($_SESSION['usuario'], "Correctivos", 'El registro de facturación # '.$id_item.' para el correctivo #: '.$idincidente.' fue eliminado.', $id_item, $query); 
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
		$_SESSION['incidente_cor'] 	= $incidente;
		$_SESSION['comentario_cor'] = $comentario;		
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
						AS asignadoa, k.nombre AS nombreasignado	
					FROM incidentes a
					LEFT JOIN usuarios i ON a.creadopor = i.id OR a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios k ON a.asignadoa = k.correo
					WHERE a.id = ".$incidente." AND i.id != 0 ";
		$result = $mysqli->query($query);
		while ($row = $result->fetch_assoc()) {
			$nombreasignado = $row['nombreasignado'];
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
		$query  = " SELECT a.id, a.titulo, a.descripcion, c.nombre AS ambiente, a.resolucion, h.prioridad, a.idclientes, a.idproyectos,
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
		$idclientes 	= $row['idclientes'];
		$idproyectos 	= $row['idproyectos'];
		$nasignadoa 	= $asignadoaN;
		$tablacomentarios	= '';
		$bitacora		= '';
		$numerocom 		= 0;
		
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
		$enviar = 1;
		$isist = '';
		if($csolicitante == 'mesadeayuda@innovacion.gob.pa' || $ccreadopor == 'mesadeayuda@innovacion.gob.pa' ){
			//$titulo 	= $row['titulo'];
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
		    $asunto = "Correctivo #$incidente - Comentario - INC $numinc";
			$enviar = 0;
    	} else {
			$numinc = '';
    	    $asunto = "Correctivo #$incidente - Comentario ";
		}
		$textoinicial = $usuarioAct." ha comentado el correctivo #".$incidente." - ".$isist."";
		if($isist == '') $textoinicial = str_replace(" - ","",$textoinicial);
		
		$mensaje  = "<div style='margin: 0 6%; background: #FFFFFF; padding: 30px;font-family: poppins, sans-serif;'>
						<div style='margin: 0 6% 0 6%; font-size: 22px;width:100%; color:#333; margin-left: 4%'>".$textoinicial."</div>
						<div style='font-size: 14px; margin: 2% 4% 0 4%; text-align: justify; line-height: 150%;'><span style='color: #222; font-weight: 600;'>Comentario:</span> ".$comentario."</div>
						<p style='width:100%; margin-left: 1%;'><br><a href='http://toolkit.maxialatam.com/mitim/correctivo.php?id=$incidente&vercom=1' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Añadir un comentario</a></p>
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
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Prioridad</div>".$prioridad."</td>
							</tr>
						</table>
					</div>";
		//USUARIOS DE SOPORTE
		$correo [] = 'isai.carvajal@maxialatam.com';
		if($idclientes != 55){ //No enviar correos de proyecto MiTim
			//$correo [] = 'ana.porras@maxialatam.com';
			$correo [] = 'fernando.rios@maxialatam.com';
			$correo [] = 'axel.anderson@maxialatam.com';
			$correo [] = 'maria.baena@maxialatam.com';
			//$correo [] = 'yamarys.powell@maxialatam.com';
		}
		
		//Correos PM Tigo
		foreach ($correo as $key => $value) { 
			if ($value == 'jose.barahona@tigo.com.pa' || $value == 'mariano.saibene@tigo.com.pa' || $value == 'fabio.beascoechea@tigo.com.pa') { 
				unset($correo[$key]); 
			}
		}
		 
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
						AS asignadoa, b.nombre AS nombreasignado
					FROM incidentes a
					LEFT JOIN usuarios b ON b.correo = a.asignadoa
					WHERE a.id = ".$incidente." AND b.id != 0 ";
		$result = $mysqli->query($query);
		while ($row = $result->fetch_assoc()) {
			$nombreasignado = $row['nombreasignado'];
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
			$tablacomentarios	= '';
			$bitacora		= '';
			$numerocom 		= 0;
			
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
				$asunto = "Correctivo #$incidente - Comentario - INC $numinc";
				$enviar = 0;
			} else {
				$numinc = '';
				$asunto = "Correctivo #$incidente - Comentario ";
			}
			
			$mensaje  = "<div style='padding: 30px;font-family: arial,sans-serif;'>
						<div style='margin: 0 6% 0 6%; font-size: 22px;width:100%; color:#333; margin-left: 4%'>".$usuarioAct." ha comentado el correctivo #".$incidente." ".$isist."</div>				
						<div style='font-size: 14px; margin: 2% 4% 0 4%; text-align: justify; line-height: 150%;'><span style='color: #222; font-weight: 600;'>Comentario:</span> ".$comentario."</div>
						<p style='width:100%;margin-left: 1%;'><br><a href='http://toolkit.maxialatam.com/mitim/correctivo.php?id=$incidente' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Añadir un comentario</a></p>
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Comentarios anteriores</div><br><div style='margin: 0 4%'>";
						if($tablacomentarios != ''){
							$mensaje .= $tablacomentarios;
						}
						$mensaje .="
						</div><br><br>
						<p  style='font-size: 18px;width:100%;'>".$creadopor." ha creado este correctivo el ".$fechacreacion."</p>
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
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Prioridad</div>".$prioridad."</td>
							</tr>
						<table>
						</div>";
			 //Correos PM Tigo
			foreach ($correo as $key => $value) { 
				if ($value == 'jose.barahona@tigo.com.pa' || $value == 'mariano.saibene@tigo.com.pa' || $value == 'fabio.beascoechea@tigo.com.pa') { 
					unset($correo[$key]); 
				}
			}
			 
			
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
		$query  = " SELECT a.id, a.titulo, a.descripcion, c.nombre AS ambiente, a.resolucion, a.idproyectos, h.prioridad, 
					a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, IFNULL(j.nombre, a.solicitante) AS solicitante,
					CASE 
						WHEN l.estado = 'Activo' 
							THEN a.asignadoa 
						WHEN l.estado = 'Inactivo' 
							THEN '' 
						END 
						AS asignadoa,
					a.departamento, IF(a.fechacreacion IS NOT NULL,CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion, a.idclientes, l.nombre AS nombreasignado					
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
					WHERE a.id = $incidente ";
					
		$result 		= $mysqli->query($query);
		$row 			= $result->fetch_assoc();
		$nombreasignado = $row['nombreasignado'];
		$titulo 		= $row['titulo'];
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
		$tablacomentarios = '';
		$tablabitacora	= '';
		$numerocom 		= 0;
		$numerobit 		= 0;
		
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
		
		$asunto = "Correctivo #$incidente - Comentario ";
		
		$mensaje  = "<div style='margin: 0 6%; background: #FFFFFF; padding: 30px;font-family: poppins, sans-serif;'>
					<div style='margin: 0 6% 0 6%; font-size: 22px;width:100%; color:#333; margin-left: 4%'>".$usuarioAct." ha comentado el correctivo #".$incidente." - ".$isist."</div>			
					<div style='font-size: 14px; margin: 2% 4% 0 4%; text-align: justify; line-height: 150%;'><span style='color: #222; font-weight: 600;'>Comentario:</span> ".$comentario."</div>
					<p style='width:100%; margin-left: 1%;'><br><a href='http://toolkit.maxialatam.com/mitim/correctivo.php?id=$incidente' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Añadir un comentario</a></p>
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
		$correo [] = 'isai.carvajal@maxialatam.com';
		if($idclientes != 55){ //No enviar correos de proyecto MiTim
			//$correo [] = 'ana.porras@maxialatam.com';
			$correo [] = 'fernando.rios@maxialatam.com';
			$correo [] = 'axel.anderson@maxialatam.com';
			$correo [] = 'maria.baena@maxialatam.com';
			//$correo [] = 'yamarys.powell@maxialatam.com';
		}
		 
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
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.id AS idproyectos, a.periodo, a.numeroaceptacion, c.id AS unidad, 
					d.id AS serie, d.activo, q.nombre as marca, r.nombre as modelo, e.id AS estado, f.id AS categoria, g.id AS subcategoria, 
					h.id AS prioridad, a.solicitante, a.asignadoa, a.departamento, d.modalidad,	a.notificar, a.resolucion,				
					CONCAT_WS('',j.id,' - ', j.titulo) AS fusionado, a.reporteservicio, a.estadomantenimiento, a.observaciones, 
					a.fechacertificar, a.horario, a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, a.comentariosatisfaccion,
					IFNULL(k.nombre, a.resueltopor) AS resueltopor, a.fechacierre, a.horacierre, a.fechamodif, a.fechacertificar, 
					IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0), a.fechacreacion,'') AS fechacreacion, a.horacreacion,
					IF(( a.fechavencimiento is not null OR LENGTH(ltrim(rTrim(a.fechavencimiento))) > 0),CONCAT(a.fechavencimiento,'  ', IFNULL(a.horavencimiento,'')),'') AS fechavencimiento,
					IF(( a.fecharesolucion is not null OR LENGTH(ltrim(rTrim(a.fecharesolucion))) > 0),CONCAT(a.fecharesolucion,'  ', IFNULL(a.horaresolucion,'')),'') AS fecharesolucion,
					(CASE WHEN a.fechafinfueraservicio is null || LENGTH(ltrim(rTrim(a.fechafinfueraservicio))) = '' then (TIMESTAMPDIFF(DAY, a.fechadesdefueraservicio, CURRENT_DATE)) ELSE (TIMESTAMPDIFF(DAY, a.fechadesdefueraservicio, a.fechafinfueraservicio)) END) as diasfueraservicio,
					a.horastrabajadas, n.id as idempresas, a.atencion, o.id as iddepartamentos, p.id as idclientes, a.idsubambientes, 
					a.fechadesdefueraservicio, a.fechafinfueraservicio, a.fueraservicio, a.contacto, et.id AS idetiquetas, et.nombre, et.idcolores
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
					LEFT JOIN etiquetas et ON a.idetiquetas = et.id
					WHERE a.id = ".$id.""; 
		//echo $query;
		//debug($query);
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
				//debug(($row['notificar']));
				$decoded = json_decode($row['notificar']);
				//$notificararr = array_filter($decoded);
				$notificararr = array_values(array_unique(array_filter($decoded)));
				$notificar = json_encode($notificararr);
				//$notificar = json_encode($row['notificar']);
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
						'modalidad' 			=> $row['modalidad'],
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
						'fechadesdefueraservicio' => $row['fechadesdefueraservicio'],
						'fechafinfueraservicio' => $row['fechafinfueraservicio'],
						'diasfueraservicio'		=> $row['diasfueraservicio'],
						'fueraservicio' 		=> $row['fueraservicio'],
						'fechamodif' 			=> $row['fechamodif'],
						'fechacertificar' 		=> $row['fechacertificar'],
						'horastrabajadas' 		=> $row['horastrabajadas'],
						'periodo' 				=> $row['periodo'],
						'idsubambientes' 		=> $row['idsubambientes'],
						'atencion' 				=> $row['atencion'],
						'contacto' 				=> $row['contacto'],
						'ingresos' 				=> $row['costo'],
						'idetiquetas'			=> $row['idetiquetas']
					);
		}
		echo json_encode($resultado);
	}
	
	function guardarIncidente(){
		global $mysqli;
		$data 				= (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		$titulo 			= (!empty($data['titulo']) ? $data['titulo'] : '');
		$titulo 			= str_replace("'",'"',$titulo);
		$descripcion 		= (!empty($data['descripcion']) ? $data['descripcion'] : '');
		$descripcion 		= str_replace("'",'"',$descripcion);
		$idempresas 		= (!empty($data['idempresas']) ? $data['idempresas'] : 1);
		$iddepartamentos	= (!empty($data['iddepartamentos']) ? $data['iddepartamentos'] : 0);
		$idclientes 		= (!empty($data['idclientes']) ? $data['idclientes'] : 0);
		$idproyectos 		= (!empty($data['idproyectos']) ? $data['idproyectos'] : 0);
		$idambientes 		= (!empty($data['unidadejecutora']) ? $data['unidadejecutora'] : '');
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
		$fechacreacion		= (!empty($data['fechacreacion']) ? $data['fechacreacion'] : date("Y-m-d"));
		$horacreacion 		= (!empty($data['horacreacion']) ? $data['horacreacion'] : date("H:i:s"));
		$horastrabajadas 	= (!empty($data['horastrabajadas']) ? $data['horastrabajadas'] : '0');
		$idsubambientes 	= (!empty($data['area']) ? $data['area'] : '0'); 
		$estadoInc 			= '';
		$atencion	  	 	= '';
		$idusuario 			= $_SESSION['user_id'];
		$nivel	 			= $_SESSION['nivel'];
	    $fueraservicio 		= (!empty($data['fueraservicio']) ? $data['fueraservicio'] : '0');
	    $contacto	 		= (!empty($data['contacto']) ? $data['contacto'] : '0');
	    $idpreventivos	 	= (!empty($data['idpreventivos']) ? $data['idpreventivos'] : '0');
		$idetiquetas	 	= (!empty($data['idetiquetas']) ? $data['idetiquetas'] : '0');
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
		//$fechacreacion	= date('Y-m-d');
		//$horacreacion 	= date('H:i:s'); 
		
		//CLIENTES 
		if($idclientes == 0 && ($nivel == 4 || $nivel == 7) ){
			$queryCU  	 = " SELECT idclientes FROM usuarios WHERE id = '".$idusuario."' ";
			$resultCU 	 = $mysqli->query($queryCU);
			$rowCU 	 	 = $resultCU->fetch_assoc();
			$idclientes = $rowCU['idclientes'];
		}
		if($idproyectos == 0 && ($nivel == 4 || $nivel == 7) ){
			$queryCU  	 = " SELECT idproyectos FROM usuarios WHERE id = '".$idusuario."' ";
			$resultCU 	 = $mysqli->query($queryCU);
			$rowCU 	 	 = $resultCU->fetch_assoc();
			$idproyectos = $rowCU['idproyectos'];
		}
		//Guardar Correctivo			  
		$query = "  INSERT INTO incidentes(titulo, descripcion, idambientes, idsubambientes, tipo, idactivos, idestados, 
					idcategorias, idsubcategorias, idprioridades, origen, creadopor, solicitante, asignadoa, 
					departamento, fechacreacion, ";
		
		if($fechavencimiento != '') $query .= "fechavencimiento, horavencimiento, ";
   
							
		if ($fueraservicio == 1) $query .= "fechadesdefueraservicio,";
   
						
						
		$idambientes != "" ? $idambientes = $idambientes : $idambientes = 0;
		$query .="  fueraservicio, horacreacion, notificar, resolucion, reporteservicio, estadomantenimiento, 
					observaciones, fechacertificar, horario, fechareal, horareal, idempresas, idclientes, idproyectos,
					iddepartamentos,atencion,contacto,idpreventivos,idetiquetas)
					VALUES ( '".$titulo."', '".$descripcion."', ".$idambientes.",'".$idsubambientes."', 'incidentes','".$serie."', 
					'".$estado."','".$categoria."', '".$subcategoria."', '".$prioridad."', '".$origen."', '".$creadopor."', 
					'".$solicitante."', '".$asignadoa."', '".$departamento."', '".$fechacreacion."', ";
		
					if($fechavencimiento !='') $query .= "'$fechavencimiento', '$horavencimiento',  ";
	
							
					if ($fueraservicio == 1) $query .= "current_timestamp(),";

		$query .= " '".$fueraservicio."', '".$horacreacion."', '".$notificar."', '".$resolucion."', '".$reporteservicio."', '".$estadomtto."', 
					'".$observaciones."', '".$fechacertificar."', '".$horario."','".$fechacreacion."', '".$horacreacion."',
					'".$idempresas."', '".$idclientes."', '".$idproyectos."', '".$iddepartamentos."','".$atencion."','".$contacto."',".$idpreventivos.",".$idetiquetas.") ";		 
		
		
		if($mysqli->query($query)){
			$id = $mysqli->insert_id;
			if($id != ''){
				$hoy = date("Y-m-d");
				//Guardar en bitácora ubicación original del activo
				if($idambientes != "" && $serie != ""){
					
					/* $sqlE = " SELECT COUNT(*) AS total FROM activostraslados WHERE idactivos = '".$serie."'";
					debugL("QUERY SQLE:".$sqlE);
					$rsqlE = $mysqli->query($sqlE); 
					if($rowT = $rsqlE->fetch_assoc()){
						$existetr = $rowT["total"];
						debugL("EXISTETR:".$existetr);
						if($existetr == 0){ */
							$sqlI = " INSERT INTO activostraslados (idactivos,ambienteanterior,ambientenuevo,usuario,fechatraslado,modulo,accion) 
							VALUES ('".$serie."','".$idambientes."','".$idambientes."','".$_SESSION['usuario']."','".$hoy."','Correctivos','Creación de correctivo # ".$id."')";
							
							$mysqli->query($sqlI);
						//} 
					//} 
				}	 
				
				//CREAR REGISTRO EN ESTADOS INCIDENTES
				$queryE = " INSERT INTO incidentesestados (idincidentes,estadoanterior,estadonuevo,usuario,fechadesde,horadesde,dias)
							VALUES(".$id.", 12, '".$estado."', ".$idusuario.", now(), now(), 0) ";
				$mysqli->query($queryE);
				
				
				//CREAR CARPETA DE ID INCIDENTES Y COMENTARIOS
				$myPath = '../incidentes/';
				if (!file_exists($myPath)){
					mkdir($myPath, 0777);
				}
				$myPath = '../incidentes/'.$id.'/';
				$target_path2 = utf8_decode($myPath);
				if (!file_exists($target_path2)){
					mkdir($target_path2, 0777);
				}
				
				if($_SESSION['nivel'] == 4){
				// 	//MOVER DEL TEMP A INCIDENTES
					$num 	= $_SESSION['user_id'];
					$from 	= '../incidentestemp/'.$num;
					$to 	= '../incidentes/'.$id.'/';
					$verificarruta = '../incidentestemp/'.$num.'/';
				// 	//Abro el directorio que voy a leer
					$target_path2 = utf8_decode($verificarruta);
				// 	echo $target_path2;
				    if (file_exists($target_path2)){
				        // echo "paso por aqui";
					    $dir = opendir($from);
    					while(($file = readdir($dir)) !== false){
    						//Leo todos los archivos excepto . y ..
    						if(strpos($file, '.') !== 0){
    							//Copio el archivo manteniendo el mismo nombre en la nueva carpeta
    							copy($from.'/'.$file, $to.'/'.$file);
    							unlink($from.'/'.$file);
    						}
    					}
				        
				    }

				// 	//Recorro el directorio para leer los archivos que tiene
				}
				//ENVIAR CORREO AL CREADOR DEL INCIDENTE
				//nuevoincidente($_SESSION['usuario'], $titulo, $descripcion, $id, $fechacreacion, $horacreacion, $solicitante, $creadopor, $idclientes, $idproyectos);
				//notificarCEstado($id,'','creado','',$estado); //$incidente,$notificar,$accion,$estadoold,$estadonew
				
				if ($fueraservicio == 1) { 
					if($estado != 16){
						$sqlact  = "UPDATE activos SET fueraservicio = 1, estado = 'INACTIVO' WHERE id = ".$serie."";;
						$rsqlact = $mysqli->query($sqlact);
						$queryfs  = "INSERT INTO fueraservicio VALUES(null, '$serie', '$fechacreacion', null, $id) ";
						$resultfs = $mysqli->query($queryfs);
					} 
				}				
				if($idpreventivos != ""){ 
					//Borrar correctivo temporal
					$delTemp = " DELETE FROM incidentestemp WHERE usuario = '".$_SESSION['usuario']."'";
					$mysqli->query($delTemp);
					//Agregar comentario al preventivo asociado
					$sqlC = "	INSERT INTO comentarios (modulo,idmodulo,comentario,visibilidad,usuario,fecha,visto) 
								VALUES('Preventivos', ".$idpreventivos.", 'Del preventivo #".$idpreventivos." se ha generado el correctivo #".$id."', 'Público', '".$_SESSION['usuario']."', NOW(), 'NO')";
					debugL($sqlC,"debugLComentarios");			
					$mysqli->query($sqlC);
				}
			}
			$accion = 'El Correctivo #'.$id.' ha sido Creado exitosamente';
			bitacora($_SESSION['usuario'], "Correctivos", $accion, $id, $query);

			//ENVIAR CORREO DE SATISFACCION - RESUELTO / CERRADO
			if($estado == 16 || $estado == 17){
				//crearMensajeEncuesta($id,$titulo,$solicitante,2,$idusuario);
				//crearMensajeSatisfaccion($id,$titulo,$solicitante);
			}
			echo true;
		}else{
			echo false;
		}
		
	}
	
	function actualizarIncidente(){
		global $mysqli;		
		$id   				= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$data 				= (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		$titulo 			= (!empty($data['titulo']) ? $data['titulo'] : '');
		$titulo 			= str_replace("'",'"',$titulo);
		$descripcion 		= (!empty($data['descripcion']) ? $data['descripcion'] : '');
		$descripcion 		= str_replace("'",'"',$descripcion);
		$idempresas 		= (!empty($data['idempresas']) ? $data['idempresas'] : 1);
		$iddepartamentos	= (!empty($data['iddepartamentos']) ? $data['iddepartamentos'] : '');
		$idclientes 		= (!empty($data['idclientes']) ? $data['idclientes'] : '');
		$idproyectos 	    = (!empty($data['idproyectos']) ? $data['idproyectos'] : '');
		$unidadejecutora 	= (!empty($data['unidadejecutora']) ? $data['unidadejecutora'] : '');
		$serie 				= (!empty($data['serie']) ? $data['serie'] : '');
		$estado 			= (!empty($data['estado']) ? $data['estado'] : '0');
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
		$estadomtto 		= (!empty($data['estadomantenimiento']) ? $data['estadomantenimiento'] : '');
		$observaciones 		= (!empty($data['observaciones']) ? $data['observaciones'] : '');	
		$horario 			= (!empty($data['horario']) ? $data['horario'] : '');
		$fechavencimiento	= NULL;
		$horavencimiento  	= NULL;
		$fecharesolucion 	= (!empty($data['fecharesolucion']) ? $data['fecharesolucion'] : '');
		$fechacierre 		= (!empty($data['fechacierre']) ? $data['fechacierre'] : '');
		$horacierre 		= (!empty($data['horacierre']) ? $data['horacierre'] : '');
		$fechacertificar 	= (!empty($data['fechacertificar']) ? $data['fechacertificar'] : '');
		$fechacreacion		= (!empty($data['fechacreacion']) ? $data['fechacreacion'] : date("Y-m-d"));
		$horacreacion 		= (!empty($data['horacreacion']) ? $data['horacreacion'] : date("H:i:s"));
		//$horastrabajadas 	= (!empty($data['horastrabajadas_editar']) ? $data['horastrabajadas_editar'] : '0');
		$idsubambientes 	= (!empty($data['area']) ? $data['area'] : '0');
		$estadoInc 			= '';
		$asignadoaInc 		= '';
		$atencion	  	 	= (!empty($data['atencion']) ? $data['atencion'] : '');		
		$fueraservicio	  	= (!empty($data['fueraservicio']) ? $data['fueraservicio'] : '');
		$horast_editar	  	= (!empty($data['horast']) ? $data['horast'] : '00');
		$minutost_editar	= (!empty($data['minutost']) ? $data['minutost'] : '00');
		$contacto_editar	= (!empty($data['contacto']) ? $data['contacto'] : '');
		$idetiquetas		= (!empty($data['idetiquetas']) ? $data['idetiquetas'] : '0');
		$idusuario 			= $_SESSION['user_id']; 
		
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
		$queryInc = $mysqli->query("SELECT solicitante, asignadoa, idestados, fueraservicio FROM incidentes WHERE id = '$id'");
		if ($rowInc = $queryInc->fetch_assoc()) {
			$estadoInc = $rowInc['idestados'];
			$fueraservicioInc = $rowInc['fueraservicio'];
   
			$asignadoaInc = $rowInc['asignadoa'];
			$solicitanteInc = $rowInc['solicitante'];
										 
		} 
		
		$descripcion = str_replace("'","",$descripcion);
		$campos = array(
			'Titulo' 				=> $titulo,
			'Descripción' 			=> $descripcion,
			'Empresas' 				=> getValor('descripcion','empresas',$idempresas),
			'Clientes' 				=> getValor('nombre','clientes',$idclientes),
			'Proyectos' 			=> getValor('nombre','proyectos',$idproyectos),
			'Categorias' 			=> getValor('nombre','categorias',$categoria),
			'Subcategorias' 		=> getValor('nombre','subcategorias',$subcategoria),
			//'Unidad ejecutora' 	=> getValor('unidad','unidades',$unidadejecutora),
			'Serie' 				=> $serie,
			'Departamentos' 		=> getValor('nombre','departamentos',$iddepartamentos),
			'Asignado a' 			=> getValorEx('nombre','usuarios',$asignadoa,'correo'),
			'Estado' 				=> getValor('nombre','estados',$estado),
			'Prioridad' 			=> getValor('prioridad','sla',$prioridad),
			'Origen' 				=> $origen,
			'Solicitante' 			=> getValorEx('nombre','usuarios',$solicitante,'correo'),
			'Numero de aceptación' 	=> $numeroaceptacion,
			'Estado de mtto.'		=> getValor('nombre','estados',$estadomtto),
			'Observaciones' 		=> $observaciones,
			'Horario' 				=> $horario,
			'Fecha de vencimiento'	=> $fechavencimiento,
			'Hora de vencimiento' 	=> $horavencimiento,
			'Fecha de resolución'	=> $fecharesolucion,
			'Fecha de cierre' 		=> $fechacierre,
			'Hora de cierre' 		=> $horacierre,
			'Fecha para certificar'	=> $fechacertificar,
			'Fecha de creación' 	=> $fechacreacion,
			'Hora de creación' 		=> $horacreacion,
			'Resolución' 			=> $resolucion,
			'Reporte de servicio' 	=> $reporteservicio,
			'Horas trabajadas'		=> $horastrabajadas,
			'Atención' 				=> $atencion,
			'Contacto' 				=> $contacto_editar
		);
		//und.unidad as 'Unidad ejecutora', 
		$valoresold = getRegistroSQL("	SELECT a.titulo as Titulo, a.descripcion as 'Descripción', d.descripcion as Empresas, e.nombre as Clientes, f.nombre as Proyectos, 
										h.nombre as Categorias, i.nombre as Subcategorias, a.idactivos as Serie, k.nombre as Departamentos, 
										n.nombre as 'Asignado a', o.nombre as Estado, p.prioridad as Prioridad, a.origen as Origen, q.nombre as Solicitante, 
										a.numeroaceptacion as 'Numero de aceptación', a.estadomantenimiento as 'Estado de mtto.', a.observaciones as Observaciones, 
										a.horario as Horario, a.fechavencimiento as 'Fecha de vencimiento', a.horavencimiento as 'Hora de vencimiento',
										a.fecharesolucion as 'Fecha de resolución', a.fechacierre as 'Fecha de cierre', a.horacierre as 'Hora de cierre', 
										a.fechacertificar as 'Fecha para certificar', a.fechacreacion as 'Fecha de creación', a.horacreacion as 'Hora de creación', 
										a.resolucion as 'Resolución', a.reporteservicio as 'Reporte de servicio', a.horastrabajadas as 'Horas trabajadas', 
										a.atencion as 'Atención', a.contacto as 'Contacto'
										FROM incidentes a
										LEFT JOIN ambientes und ON a.idambientes = und.id
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
		if(isset($data['unidadejecutora']) && $unidadejecutora != ""){
			$query .= ", idambientes = $unidadejecutora ";
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
			$query .= ", fechareal = '$fechacreacion' ";
		}
		if(isset($data['horacreacion'])){
			$query .= ", horacreacion = '$horacreacion' ";
			$query .= ", horareal = '$horacreacion' ";
		}
		if($horastrabajadas!=""){
			$query .= ", horastrabajadas = '$horastrabajadas' ";
		}
		if(isset($data['atencion'])){
			$query .= ", atencion = '$atencion' ";
		}
		if($estado < $estadoInc && $estado != '34' ){
			$query .= " , estadoant = '1' ";
		}
		if($estadoInc != 16 && $estado == '16' ){
			$query .= " , resueltopor = '".$_SESSION['correousuario']."' ";
		}
		if ($fueraservicio != '' && $fueraservicio == 1) {
			$query .= " , fueraservicio = '$fueraservicio' ";	
			$query .= " , fechadesdefueraservicio = current_timestamp() ";
		}
		if ($estado!='' && $estado == 16) {
			$query .= " , fechafinfueraservicio = current_timestamp ";			
		}
		if(isset($data['contacto'])){
			$query .= ", contacto = '".$contacto_editar."' ";
		} 
		if(isset($data['idetiquetas'])){
			$query .= ", idetiquetas = '".$idetiquetas."' ";
		}
		$query .= " WHERE id = $id ";
		$query = str_replace('SET ,','SET ',$query);
		debugL("USUARIO:".$_SESSION['usuario']."-QUERYUPDATE:".$query,"DEBUGL-ACTUALIZARINC");
		
		if($mysqli->query($query)){
			//Verificar si fecharesolucion es vacía
			if($estado == 16 && (isset($data['fecharesolucion_editar']) && $data['fecharesolucion_editar'] != null) && ($horaresolucion != null && $horaresolucion != 'null')){
				//Verifico si el incidente está fusionado con otros incidentes
				$queryF = " SELECT GROUP_CONCAT(id) AS fusionados FROM incidentes WHERE fusionado = '$id' ";
				$resultF = $mysqli->query($queryF); 
				if($rowF = $resultF->fetch_assoc()){
					$fusionados = $rowF['fusionados'];
					if($fusionados != "" && $fusionados != null){
						//Actualizo fecha de resolución de incidentes fusionados
						$queryR = " UPDATE incidentes SET";
						if((isset($data['fecharesolucion_editar']) && $data['fecharesolucion_editar'] != null)){
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
			
			//CREAR REGISTRO FUERA DE SERVICIO
			$hoy = date("Y-m-d"); 
			if($fueraservicioInc != $fueraservicio){
				if($fueraservicio == '1' && $estado != 16 && $estadoInc != 16){ 
				
					//Coloca activo fuera de servicio y crea registro en tabla fueraservicio
					$sqlact  = "UPDATE activos SET fueraservicio = 1, estado = 'INACTIVO' WHERE id = ".$serie."";;
					$rsqlact = $mysqli->query($sqlact);
					$queryfs  = "INSERT INTO fueraservicio VALUES(null, '".$serie."', '".$hoy."', null, '".$id."') ";
					$resultfs = $mysqli->query($queryfs);
					//*******************************************//
					//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
					//*******************************************// 
					
					//Usuarios de soporte
					$idusuarios['icarvajal'] = "0";
					$idusuarios['frios'] = "0";
					$idusuarios['aanderson'] = "0";  					
					
					$usuarios = json_encode($idusuarios);
					
					$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,descripcion,fecha,hora,usuarios) VALUES (".$idproyectos.",".$id.",'Fuera de servicio','','". date('Y-m-d') ."','". date('H:i:s') ."','".$usuarios."')"; 
					$rsql = $mysqli->query($sql); 
					
					//*******************************************//
					//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
					//*******************************************//
				}
			}else{
				if($fueraservicio == '1' && $estadoInc != 16 && $estado == 16){
					
					//Coloca activo como disponible y actualiza tabla fueraservicio
					$sqlact  = "UPDATE activos SET fueraservicio = 0, estado = 'ACTIVO' WHERE id = ".$serie."";;
					$rsqlact = $mysqli->query($sqlact); 
					$queryfs  = "UPDATE fueraservicio SET hasta = '".$fecharesolucion."' WHERE  incidente = ".$id." ";
					$resultfs = $mysqli->query($queryfs);
					
				}elseif($fueraservicio == '1' && $estadoInc == 16 && $estado != 16 ){	
				
					//Coloca activo en fuera de servicio si ya pasó una vez por ese estado
					$sqlact  = "UPDATE activos SET fueraservicio = 1, estado = 'INACTIVO' WHERE id = ".$serie."";;
					$rsqlact = $mysqli->query($sqlact);
				}
			}
			
			//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
			if($estadoInc != $estado){
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
				
				$mysqli->query($queryE);			
				
			
				if($estado == 13){
					$query = "SELECT idproyectos FROM usuarios WHERE correo = '$asignadoa' ";
					$result = $mysqli->query($query);
					if($result->num_rows >0){
						$row = $result->fetch_assoc();
						$proyectosusu = $row['idproyectos'];
					}
					
				}
				notificarCEstado($id,$notificar,'actualizado',$estadoanterior,$estado); //$incidente,$notificar,$accion,$estadoold,$estadonew 
			}
			
			if($asignadoaInc != $asignadoa){
				notificarCAsignadoa($id,$notificar,'actualizado',$asignadoaInc,$asignadoa);
			} 
			if($solicitanteInc != $solicitante){
				notificarCSolicitante($id,$notificar,'actualizado',$solicitanteInc,$solicitante);
			} 
			//BITACORA
			actualizarRegistro('Incidentes','Incidente',$id,$valoresold,$campos,$query);
			//ENVIAR CORREO DE SATISFACCION - RESUELTO / CERRADO
			if($estado == 16 || $estado == 17){
				crearMensajeEncuesta($id,$titulo,$solicitante,2,$idusuario);
				//crearMensajeSatisfaccion($id,$titulo,$solicitante);
			}
			//Enviar mensaje en cambio de estado Facturación
			if($estado == 14){
				cambioEstadoFacturacion($id);
			}
			echo true;
		}else{
			echo false;
		} 
	}
	
	function guardarIncidenteMasivo(){
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
								bitacora($_SESSION['usuario'], "Incidentes", 'El Correctivo #'.$id.' ha sido Editado exitosamente', $id, $query2);
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
	function nuevoincidente($usuario, $titulo, $descripcion, $incidente, $fecha, $hora, $solicitante, $creadopor, $idclientes, $idproyectos){
		global $mysqli, $mail;
		
		//SOLICITANTE
		if($solicitante !=''){
			if (filter_var($solicitante, FILTER_VALIDATE_EMAIL)) {
				$result = $mysqli->query("SELECT correo FROM usuarios WHERE correo = '".$solicitante."' AND estado = 'Activo' ");
				if ($row=$result->fetch_assoc()) {
					$correo [] = $solicitante;
				} 
			} 
			//Asunto
			$innovacion = 'soporteaig@innovacion.gob.pa';
			if($solicitante == $innovacion || $creadopor == $innovacion || $solicitante == 'mesadeayuda@innovacion.gob.pa' ){
				$asunto = $titulo;
			}else{
				$asunto = "Correctivo #$incidente ha sido Creado";
			}
			
			//Correos PM Tigo
			if(($idclientes == 1 && $idproyectos == 1) || ($idclientes == 38 && $idproyectos == 68)){ 
				$pmtigo = array("jose.barahona@tigo.com.pa","mariano.saibene@tigo.com.pa","fabio.beascoechea@tigo.com.pa","roljrangel@hotmail.com");
				foreach ($pmtigo as $value) {
					if(!empty($correo)){
						if(in_array($value, $correo)) {
						}else{ 
							$correo [] = $value;
						}
					}else{
						$correo [] = $value;
					} 
				} 
			}
			//Cuerpo
			$fecha = implode('/',array_reverse(explode('-', $fecha)));
			$cuerpo = '';
			$cuerpo .= "<div style='background-color: #FFFFFF; margin: 0 6%; padding: 1% 2%; color: #3e4954;'><div style='text-align: right;'><b>Fecha:</b> ".$fecha."</div>";
			$cuerpo .= "<br><b>".$titulo."</b>";
			$cuerpo .= "<p style='width: 100%;'>Buen día,<br>Gracias por contactar al Centro de Soporte, su caso ha sido asignado a nuestros Ingenieros especializados quienes los contactarán brevemente para mas detalles sobre el caso.</p></div>"; 
			
			$correo = array_diff($correo, array("roljrangel@hotmail.com"));
			$correo = array_diff($correo, array("mariano.saibene@tigo.com.pa"));
			$correo = array_diff($correo, array("jose.barahona@tigo.com.pa"));
			$correo = array_diff($correo, array("fabio.beascoechea@tigo.com.pa"));
			
			debugL($incidente."-NuevoIncidente-CORREO:".json_encode($correo),"nuevoIncidente");
			//Correo
			if(!empty($correo)){
				enviarMensajeIncidente($asunto,$cuerpo,$correo,'','');
			}
		}
	}
	
	function cambioEstadoFacturacion($incidente){
		global $mysqli;
		
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, c.nombre AS ambiente,
					d.serie, q.nombre AS marca, r.nombre AS modelo, e.nombre AS estado, f.id AS idcategorias, f.nombre AS categoria, g.nombre AS subcategoria,
					h.prioridad, a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, IFNULL(j.nombre, a.solicitante) AS solicitante, CASE WHEN l.estado = 'Activo' THEN a.asignadoa WHEN l.estado = 'Inactivo' THEN '' END AS asignadoa, l.usuario AS usuarioasignadoa,
					l.nombre AS nombreasignadoa, s.nombre AS departamento, d.modalidad, a.satisfaccion, a.comentariosatisfaccion, a.resolucion, IFNULL(k.nombre, a.resueltopor) AS resueltopor,
					IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0),CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion,
					IF(( a.fechavencimiento is not null OR LENGTH(ltrim(rTrim(a.fechavencimiento))) > 0),CONCAT(a.fechavencimiento,'  ', a.horavencimiento),'') AS fechavencimiento,
					IF(( a.fecharesolucion is not null OR LENGTH(ltrim(rTrim(a.fecharesolucion))) > 0),CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,
					IF(( a.fechacierre is not null OR LENGTH(ltrim(rTrim(a.fechacierre))) > 0),CONCAT(a.fechacierre,'  ', a.horacierre),'') AS fechacierre, a.fechamodif, a.fechacertificar, 
					a.horastrabajadas, a.comentariovisto, CASE WHEN i.estado = 'Activo' THEN IFNULL(i.correo, a.creadopor)
					WHEN i.estado = 'Inactivo' THEN '' END AS correocreadopor, i.usuario AS usuariocreadopor, a.notificar, CASE  WHEN j.estado = 'Activo' THEN IFNULL(j.correo, a.solicitante) WHEN j.estado = 'Inactivo' THEN '' END AS correosolicitante, j.usuario AS usuariosolicitante, a.idclientes, a.idproyectos
					FROM incidentes a
					INNER JOIN proyectos b ON a.idproyectos = b.id
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
					LEFT JOIN departamentos s ON a.iddepartamentos = s.id
					WHERE a.id = $incidente GROUP BY a.id ";
					
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
		
		//DATOS
		$fechacreacion 	= $row['fechacreacion'];
		$titulo			= $row['titulo'];
		$descripcion	= $row['descripcion'];
		$solicitante	= $row['solicitante'];
		$creadopor		= $row['creadopor'];
		$departamento	= $row['departamento'];
		$prioridad		= $row['prioridad'];
		$sitio 			= $row['ambiente'];
		$nombreasignadoa = $row['nombreasignadoa'];
		
		$asunto = "Correctivo #$incidente ha sido Actualizado";
		
		$mensaje .= "<p style='width:100%;'>
						<a href='http://toolkit.maxialatam.com/mitim/correctivo.php?id=".$incidente."' target='_blank' style='background-color: #434286;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Correctivo</a></p>";
				
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
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Asignado a</div>".$nombreasignadoa."</td>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Prioridad</div>".$prioridad."</td>
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
			$correo [] = 'ginela.barcos@maxialatam.com';
			$correo [] = 'yimara.desedas@maxialatam.com'; 
			$correo [] = 'marlon.antepara@maxialatam.com'; 
			$correo [] = 'javier.diaz@maxialatam.com'; 
			enviarCorreo($asunto,$mensaje,$correo,'','');
				
	}
	
	//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
	function notificarCEstado($incidente,$notificar,$accion,$estadoold,$estadonew){
		global $mysqli;
		
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.nombre AS proyecto, c.nombre AS ambiente,
					d.serie, q.nombre AS marca, r.nombre AS modelo, e.nombre AS estado, f.id AS idcategorias, f.nombre AS categoria, g.nombre AS subcategoria,
					h.prioridad, a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, IFNULL(j.nombre, a.solicitante) AS solicitante, CASE WHEN l.estado = 'Activo' THEN a.asignadoa WHEN l.estado = 'Inactivo' THEN '' END AS asignadoa, l.usuario AS usuarioasignadoa,
					l.nombre AS nombreasignadoa, s.nombre AS departamento, d.modalidad, a.satisfaccion, a.comentariosatisfaccion, a.resolucion, IFNULL(k.nombre, a.resueltopor) AS resueltopor,
					IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0),CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion,
					IF(( a.fechavencimiento is not null OR LENGTH(ltrim(rTrim(a.fechavencimiento))) > 0),CONCAT(a.fechavencimiento,'  ', a.horavencimiento),'') AS fechavencimiento,
					IF(( a.fecharesolucion is not null OR LENGTH(ltrim(rTrim(a.fecharesolucion))) > 0),CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,
					IF(( a.fechacierre is not null OR LENGTH(ltrim(rTrim(a.fechacierre))) > 0),CONCAT(a.fechacierre,'  ', a.horacierre),'') AS fechacierre, a.fechamodif, a.fechacertificar, 
					a.horastrabajadas, a.comentariovisto, CASE WHEN i.estado = 'Activo' THEN IFNULL(i.correo, a.creadopor)
					WHEN i.estado = 'Inactivo' THEN '' END AS correocreadopor, i.usuario AS usuariocreadopor, a.notificar, CASE  WHEN j.estado = 'Activo' THEN IFNULL(j.correo, a.solicitante) WHEN j.estado = 'Inactivo' THEN '' END AS correosolicitante, j.usuario AS usuariosolicitante, a.idclientes, a.idproyectos
					FROM incidentes a
					INNER JOIN proyectos b ON a.idproyectos = b.id
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
					LEFT JOIN departamentos s ON a.iddepartamentos = s.id
					WHERE a.id = $incidente GROUP BY a.id ";
		//echo $query;
		$result = $mysqli->query($query);
		$row 	= $result->fetch_assoc();
		$idclientes = $row['idclientes'];
		$idproyectos = $row['idproyectos'];
		$nombreasignadoa = $row['nombreasignadoa'];
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
			$asunto    = "Notificación del Correctivo #$incidente";
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
						$queryn = " SELECT usuario, correo FROM usuarios WHERE correo = '".$notif."' AND estado = 'Activo' ";
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
				$asunto = "Correctivo #$incidente ha sido Creado";
			}else{ //actualizado
				if ($estadoold != $estadonew && $estadonew == 13) {
					$asunto = "Correctivo #$incidente ha sido Asignado";
				} elseif ($estadoold != $estadonew && $estadonew == 16) {
					$asunto = "Correctivo #$incidente ha sido Resuelto";	
					//if (substr($row['titulo'],0,14)=='[Service Desk]') {
					if ($row['correosolicitante']=='mesadeayuda@innovacion.gob.pa') {
						$asunto = $row['titulo']." (Incidente MiTim #$incidente) ha sido Resuelto";
					    //$correo [] = 'mesadeayuda@innovacion.gob.pa';
					}
				}
				else {
					$asunto = "Correctivo #$incidente ha sido Actualizado";
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
					<div style='font-size: 22px; color: #333; margin: 4% 0 4% 4%;'>".$usuarioAct." ha creado el correctivo #".$incidente."</div>";
		}else{ //actualizado
			$mensaje = "<div style='background-color: #FFFFFF; margin: 0 6%; padding: 30px;font-family: arial,sans-serif;'>
							<div style='font-size: 22px; color: #333; margin: 4% 0 0 4%;'>".$usuarioAct." ha actualizado el correctivo #".$incidente."</div>";		
		}
		
		if($estadonew == 13){
			$mensaje .= "<p style='color: #3e4954; margin-left: 4%; width:100%;'>El correctivo ha sido asignado a: ".$nasignadoa."</p>";
		}elseif($estadoant !='' && $estadonue !=''){
			$mensaje .= "<p style='color: #3e4954; margin: 2% 0 5% 4%; width:100%; font-size: 22px;'>El Estado cambió de ".$estadoant." a <b>".$estadonue."</b></p>";
			//*******************************************//
			//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
			//*******************************************//  
			
			//Usuarios de soporte
			$idusuarios['icarvajal'] = "0";
			$idusuarios['frios'] = "0";
			$idusuarios['aanderson'] = "0"; 
			
			//Usuarios relacionados al correctivo
			if($row['usuariocreadopor'] !="") $idusuarios[$row['usuariocreadopor']] = "0";		
			if($row['usuarioasignadoa'] !="") $idusuarios[$row['usuarioasignadoa']] = "0"; 
			if($row['usuariosolicitante'] !="") $idusuarios[$row['usuariosolicitante']] = "0";
			if($usuarionotificar !="") $idusuarios[$usuarionotificar] = "0";
			
			$usuarios = json_encode($idusuarios);
			
			$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,descripcion,fecha,hora,usuarios) VALUES (".$idproyectos.",".$incidente.",'Cambio de estado correctivo',' ".$estadoant." a ".$estadonue."','". date('Y-m-d') ."','". date('H:i:s') ."','".$usuarios."')"; 
	        $rsql = $mysqli->query($sql); 
			
			//*******************************************//
			//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
			//*******************************************//				
		}
		$mensaje .= "<p style='width:100%;'>
						<a href='http://toolkit.maxialatam.com/mitim/correctivo.php?id=".$incidente."' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Correctivo</a></p>";
		if($estadonew == 16 || $estadonew == 17){
			//GENERAR FECHA DE CIERRE 
			$query = "  UPDATE incidentes SET fechacierre = DATE_ADD(fecharesolucion, INTERVAL 3 DAY), horacierre = horaresolucion, 
						idestados = 16 WHERE id = '".$incidente."' ";
			$mysqli->query($query);
			//$mensaje .= "<br><br><p style='width:100%;'><b>Resolución: </b>".$resolucion."</p>";
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
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Asignado a</div>".$nombreasignadoa."</td>
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Prioridad</div>".$prioridad."</td>
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
		$correo [] = 'isai.carvajal@maxialatam.com';
		if($idclientes != 55){ //No enviar correos de proyecto MiTim
			//$correo [] = 'ana.porras@maxialatam.com';
			$correo [] = 'fernando.rios@maxialatam.com';
			$correo [] = 'axel.anderson@maxialatam.com';
			$correo [] = 'maria.baena@maxialatam.com';
			//$correo [] = 'yamarys.powell@maxialatam.com';	
		}
		
		
		/* ******************************************************************************** /
		//	Si el solicitante es la AIG solo se le enviará un correo al cambiar el estado 
		//  del incidente a Resuelto
		// ******************************************************************************** */
		
		if($_SESSION['nivel'] == 4){
			$num 	= $_SESSION['user_id'];
			$from 	= '../incidentestemp/'.$num;
			$verificarruta = '../incidentestemp/'.$num.'/';
			$adjuntos = array();
			//Abro el directorio que voy a leer
			$target_path2 = utf8_decode($verificarruta);
			if (file_exists($target_path2)){
				$dir = opendir($from);
				//Recorro el directorio para leer los archivos que tiene
				while(($fileE = readdir($dir)) !== false){
					//Leo todos los archivos excepto . y ..
					if ($fileE != "." && $fileE != ".." && $fileE != ".quarantine" && $fileE != ".tmb" && $fileE != "comentarios"){ 
						$archivo = '../incidentestemp/'.$num.'/'.$fileE;
						$adjuntos[] = $archivo;
					}
				}
			}
		}else{
			$adjuntos = '';
		}
		//Correos PM Tigo
		foreach ($correo as $key => $value) { 
			if ($value == 'soportemaxia@zertifika.com' || $value == 'jose.barahona@tigo.com.pa' || $value == 'mariano.saibene@tigo.com.pa' || $value == 'fabio.beascoechea@tigo.com.pa') { 
				unset($correo[$key]); 
			}
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
        //debugL($incidente." - notificarCEstado-CORREO:".json_encode($correo),"notificarCEstado"); 
        //debugL("notificarCEstado-CORREO:".json_encode($correo)); 
		if ($row['correosolicitante']=='mesadeayuda@innovacion.gob.pa') {
			$asunto = $row['titulo']." (Incidente MiTim #$incidente) ha sido Resuelto";
			if ($estadoold != $estadonew && $estadonew == 16) {
				//enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
			}
		} else {
			//enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
		}
		
		//enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
	}
	
	//ENVIA CORREO DE LA ACTUALIZACION DEL INCIDENTE
	function notificarCAsignadoa($incidente,$notificar,$accion,$asignadoaInc,$asignadoa){
		global $mysqli;
		
		$query  = " SELECT a.id, a.titulo, a.descripcion,IFNULL(i.nombre, a.creadopor) AS creadopor, CASE WHEN 
					j.estado = 'Activo' THEN a.asignadoa WHEN j.estado = 'Inactivo' THEN '' END AS asignadoa, 
					IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0),CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion, CASE WHEN i.estado = 'Activo' THEN IFNULL(i.correo, a.creadopor) WHEN i.estado = 'Inactivo' THEN '' END AS correocreadopor, a.idclientes, a.idproyectos
					FROM incidentes a 
					LEFT JOIN usuarios i ON a.creadopor = i.correo
					LEFT JOIN usuarios j ON a.asignadoa = j.correo 
					WHERE a.id = $incidente GROUP BY a.id ";
					
		//echo $asignadoaInc;	
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
				$query2 .= "correo = '".$row['asignadoa']."' AND estado = 'Activo' ";
			}else{
				$query2 .= "correo IN ('".$row['asignadoa']."') AND estado = 'Activo' ";
			}
			$consulta = $mysqli->query($query2);
			while($rec = $consulta->fetch_assoc()){
				$asignadoaN .= $rec['nombre']." , ";
			}
		}
		
		//ENVIAR CORREO DEL INCIDENTE A LOS USUARIOS SELECCIONADOS
		//4 para los usuarios que quieren que se les notifique (Enviar Notificacion a)
		if($notificar != '[]' && $notificar != ''){
			$asunto    = "Notificación del Correctivo #$incidente";
			$notificar = json_decode($notificar);
			if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
				
				//Excluir a usuarios inactivos del campo Notificar a 
				$result = $mysqli->query("SELECT correo FROM usuarios WHERE correo = '".$notificar."' AND estado = 'Activo' ");
				if ($rowUs=$result->fetch_assoc()) {
					$r = $rowUs['correo'];
					if($r != ""){
						$correo [] = "$notificar";
					} 
				}
			}else{
				foreach($notificar as $notif){
					
					//Excluir a usuarios inactivos del campo Notificar a 
					$result = $mysqli->query("SELECT correo FROM usuarios WHERE correo = '".$notif."' AND estado = 'Activo' ");
					if ($rowUs=$result->fetch_assoc()) {
						$r = $rowUs['correo'];
						if($r != ""){
							$correo [] = $notif;
						} 
					}
				}
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
		$titulo			= $row['titulo'];
		$descripcion	= $row['descripcion']; 
		$creadopor		= $row['creadopor']; 
		$nasignadoa 	= $asignadoaN;
		
		$asunto = "Correctivo #$incidente ha sido Actualizado";
		
		//MENSAJE 
		$mensaje = "<div style='margin: 0 6%; background-color: #fff; padding: 1% 3%;font-family: arial,sans-serif;'>
					<p style='font-size: 22px;width:100%; margin-left: 4%; color: #333;'>El correctivo #".$incidente." ha sido modificado de Asignado.</p>
						<p style='font-size: 22px;width:100%; margin-left: 4%; color: #333;'>Asignado anterior: ".$asignadoaant.", Asignado nuevo: <b>".$asignadoanue."</b></p>";
		 
		$mensaje .= 	"<p style='margin-left: 1%; margin-top: 3%; width:100%;'>
						<a href='http://toolkit.maxialatam.com/mitim/correctivo.php?id=".$incidente."' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Correctivo</a></p>
						<br><br>
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Atributos</div>
						<table style='width: 100%; margin: 0 4% 2% 4%;'> 
							<tr>
								<td style='padding: 15px 0; font-size: small; vertical-align: top;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Creado por</div>".$creadopor."</td> 
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080;'>Recibido en</div>".$fechacreacion."</td>
							</tr>
						</table> 
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Título</div>
						<div style='margin: 1% 4% 2% 4%; color: #3e4954; text-align: justify; line-height: 150%;'>".$titulo."</div>
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Descripción</div>
						<div style='margin: 0 4% 2% 4%; color: #3e4954; text-align: justify; line-height: 150%;'>".$descripcion."</div>";  
		   
		  
		$mensaje .= "</div>";

		//USUARIOS DE SOPORTE
		$correo [] = 'isai.carvajal@maxialatam.com';
		if($idclientes != 55){ //No enviar correos de proyecto MiTim
			//$correo [] = 'ana.porras@maxialatam.com';
			$correo [] = 'fernando.rios@maxialatam.com';
			$correo [] = 'axel.anderson@maxialatam.com';
			$correo [] = 'maria.baena@maxialatam.com';
			//$correo [] = 'yamarys.powell@maxialatam.com';
		}
		
		//Correos PM Tigo
		foreach ($correo as $key => $value) { 
			if ($value == 'soportemaxia@zertifika.com' || $value == 'jose.barahona@tigo.com.pa' || $value == 'mariano.saibene@tigo.com.pa' || $value == 'fabio.beascoechea@tigo.com.pa' || $value == 'roljrangel@hotmail.com') { 
				unset($correo[$key]); 
			}
		}
		
		debugL("notificarCAsignadoa-CORREO:".json_encode($correo),"notificarCAsignadoa");					 
		enviarMensajeIncidente($asunto,$mensaje,$correo,'','');
	}
	
	

	function notificarCSolicitante($incidente,$notificar,$accion,$solicitanteInc,$solicitante){
		global $mysqli;
		
		$query  = " SELECT a.id, a.titulo, a.descripcion,IFNULL(i.nombre, a.creadopor) AS creadopor, a.asignadoa,
					CASE WHEN 	j.estado = 'Activo' THEN a.solicitante WHEN j.estado = 'Inactivo' THEN '' END AS solicitante, IF(( a.fechacreacion is not null OR LENGTH(ltrim(rTrim(a.fechacreacion))) > 0),CONCAT(a.fechacreacion,'  ', a.horacreacion),'') AS fechacreacion, CASE WHEN j.estado = 'Activo' THEN IFNULL(i.correo, a.creadopor)
                   	WHEN j.estado = 'Inactivo' THEN '' END AS correocreadopor, a.idclientes, a.idproyectos
					FROM incidentes a 
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
			$asunto    = "Notificación del Correctivo #$incidente";
			$notificar = json_decode($notificar);
			if (filter_var($notificar, FILTER_VALIDATE_EMAIL)) {
				
				//Excluir a usuarios inactivos del campo Notificar a 
				$result = $mysqli->query("SELECT correo FROM usuarios WHERE correo = '".$notificar."' AND estado = 'Activo' ");
				if ($rowUs=$result->fetch_assoc()) {
					$r = $rowUs['correo'];
					if($r != ""){
						$correo [] = "$notificar";
					} 
				} 
				
			}else{
				foreach($notificar as $notif){
					
					//Excluir a usuarios inactivos del campo Notificar a 
					$result = $mysqli->query("SELECT correo FROM usuarios WHERE correo = '".$notif."' AND estado = 'Activo' ");
					if ($rowUs=$result->fetch_assoc()) {
						$r = $rowUs['correo'];
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
			$solicitanteant = 'Sin Asignar';
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
		
		$asunto = "Correctivo #$incidente ha sido Actualizado";
		
		//MENSAJE 
		$mensaje = "<div style='padding: 1% 3%;font-family: arial,sans-serif; margin: 0 6%; background-color: #FFFFFF;'>
					<p style='font-size: 22px; width:100%; margin-left: 4%; color: #333;'>El correctivo #".$incidente." ha sido modificado de Solicitante.</p>
					<p style='font-size: 22px; width:100%; margin-left: 4%; color: #333;'>Solicitante anterior: ".$solicitanteant.", Solicitante nuevo: <b>".$solicitantenue."</b></p>";
		 
		$mensaje .= "<p style='margin-left: 1%; width:100%;'>
						<a href='http://toolkit.maxialatam.com/mitim/correctivo.php?id=".$incidente."' target='_blank' style='background-color: #2eab51; color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Correctivo</a></p>
						<br>
						<br> 
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Atributos</div> 
						<table style='width: 100%; margin: 0 4% 2% 4%;'> 
							<tr>
								<td style='padding: 15px 0; font-size: small; vertical-align: top;'><div style='font-size: 14px;color: #808080; padding-bottom: 3px;'>Creado por</div>".$creadopor."</td> 
								<td style='padding: 15px 0; font-size: small;'><div style='font-size: 14px;color: #808080;'>Recibido en</div>".$fechacreacion."</td>
							</tr>
						</table>
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Título</div>
						<div style='margin: 1% 4% 2% 4%; color: #3e4954; text-align: justify; line-height: 150%;'>".$titulo."</div>
						<div style='background-color: #f5f5f5;color: #726969;font-size: 14px; margin: 0 4% 0 4%;'>Descripción</div>
						<div style='margin: 0 4% 2% 4%; color: #3e4954; text-align: justify; line-height: 150%;'>".$descripcion."</div>
						";  
		$mensaje .= "</div>";

		//USUARIOS DE SOPORTE
		$correo [] = 'isai.carvajal@maxialatam.com';
		if($idclientes != 55){ //No enviar correos de proyecto MiTim
			//$correo [] = 'ana.porras@maxialatam.com';
			$correo [] = 'fernando.rios@maxialatam.com';
			$correo [] = 'axel.anderson@maxialatam.com';
			$correo [] = 'maria.baena@maxialatam.com';
			//$correo [] = 'yamarys.powell@maxialatam.com';
		}
		
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
	
	function crearMensajeEncuesta($idincidentes,$titulo,$solicitante,$idencuestas,$idusuarios){
		global $mysqli;
		
		//debugL('crearMensajeEncuesta: '.$idincidentes.' - '.$titulo.' - '.$solicitante.' - '.$idencuestas.' - '.$idusuarios);
		//para quien solicito o reporto el incidente (Solicitante)
		if (filter_var($solicitante, FILTER_VALIDATE_EMAIL)) {
				
				//Excluir a usuarios inactivos - Campo solicitante
				$result = $mysqli->query("SELECT nombre,correo FROM usuarios WHERE correo = '".$solicitante."' AND estado = 'Activo' ");
				if ($row=$result->fetch_assoc()) {
					$correo [] = $solicitante;
				} 
		}else{
			
			//Excluir a usuarios inactivos - Campo solicitante
			$result = $mysqli->query("SELECT nombre,correo FROM usuarios WHERE id = '".$solicitante."' AND estado = 'Activo' ");
			if ($row=$result->fetch_assoc()) {
				$correo [] = $row['correo'];
			}
		}
		
		//ASUNTO
		$asunto = "Encuesta de atención del Correctivo #".$idincidentes;
		if($idencuestas == 1){
			$idpreguntas = 1;
			$pregunta = '¿Cómo calificaría la atención de nuestro Call Center?';
			$url = "http://toolkit.maxialatam.com/mitim/encuestas.php?idencuestas=".$idencuestas."&idpreguntas=".$idpreguntas."&idincidentes=".$idincidentes."&idusuarios=".$idusuarios;
		}else{
			$idpreguntas = 1;
			$pregunta = '¿Qué tan satisfecho está con la resolución del caso/consulta/solicitud reportado?';
			$url = "http://toolkit.maxialatam.com/mitim/encuestas.php?idencuestas=".$idencuestas."&idpreguntas=".$idpreguntas."&idincidentes=".$idincidentes."&idusuarios=".$idusuarios;
		}		

		$mensajeHtml = '<div style="padding: 30px;font-family: arial,sans-serif;margin-left: 6%;margin-right: 6%;background: #fff;padding-left: 15rem;padding-right: 16rem;">
							<div style="font-weight: 600;color: #5d5b5b; text-align: center">'.$pregunta.'</div>
							<div style="display: inline-flex;">
								<p  style="text-align:center;width: 90px;margin: 30px 15px;padding-top: 3rem;">
									<a href="'.$url.'&evaluacion=5" style="font-size: 12px;text-decoration: none; color: #444444;">
										<img src="http://toolkit.maxialatam.com/mitim/images/encuesta/muysatisfecho.png" width="40" alt="" style="margin-bottom:15px">
										<br>Muy Satisfecho
									</a>
								</p>
								<p style="text-align:center;width: 90px;margin: 30px 15px;padding-top: 3rem;">
									<a href="'.$url.'&evaluacion=4" style="font-size: 12px;text-decoration: none; color: #444444;">
										<img src="http://toolkit.maxialatam.com/mitim/images/encuesta/satisfecho.png" width="40" alt="" style="margin-bottom:15px">
										<br>Satisfecho
									</a>
								</p>
								<p style="text-align:center;width: 90px;margin: 30px 15px;padding-top: 3rem;">
									<a href="'.$url.'&evaluacion=3" style="font-size: 12px;text-decoration: none; color: #444444;">
										<img src="http://toolkit.maxialatam.com/mitim/images/encuesta/neutral.png" width="40" alt="" style="margin-bottom:15px">
										<br>Neutral
									</a>
								</p>
								<p style="text-align:center;width: 90px;margin: 30px 15px;padding-top: 3rem;">
									<a href="'.$url.'&evaluacion=2" style="font-size: 12px;text-decoration: none; color: #444444;">
										<img src="http://toolkit.maxialatam.com/mitim/images/encuesta/insatisfecho.png" width="40" alt="" style="margin-bottom:15px">
										<br>Insatisfecho
									</a>
								</p>
								<p style="text-align:center;width: 90px;margin: 30px 15px;padding-top: 3rem;">
									<a href="'.$url.'&evaluacion=1" style="font-size: 12px;text-decoration: none; color: #444444;">
										<img src="http://toolkit.maxialatam.com/mitim/images/encuesta/muyinsatisfecho.png" width="40" alt="" style="margin-bottom:15px">
										<br>Muy insatisfecho
									</a>
								</p>
							</div>
						</div> 
						';
		
		$queryE = " INSERT INTO encuestasresultados (id, idencuestas, idpreguntas, evaluacion, idincidentes, idusuarios, enviada)
					VALUES(null, ".$idencuestas.", ".$idpreguntas.", 0, ".$idincidentes.", ".$idusuarios.", now() )";
		//debugL($queryE);
		$mysqli->query($queryE);
		debugL($idincidentes."-crearMensajeEncuesta-CORREO:".json_encode($correo),"crearMensajeEncuesta");	
		
		$correo = array_diff($correo, array("roljrangel@hotmail.com"));		
		$correo = array_diff($correo, array("mariano.saibene@tigo.com.pa"));		
		$correo = array_diff($correo, array("jose.barahona@tigo.com.pa"));		
		$correo = array_diff($correo, array("fabio.beascoechea@tigo.com.pa"));		
		
		if(!empty($correo)){
			enviarMensajeIncidente($asunto,$mensajeHtml,$correo,'','');
		}		 
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
			$asunto = "Satisfacción del Correctivo #$incidente";
		}

		$mensajeHtml = "<table border=0>
							<tr><td colspan=4>&nbsp;</td></tr>
							<tr><td colspan=4>&nbsp;</td></tr>
							<tr><td colspan=4>Correctivo #$incidente</td></tr>
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

						$asunto = "Correctivo #$incidente VENCIDO - MiTim";

						$mensajeHtml = "<table border=0>
											<tr><td colspan=4>MiTim</td></tr>
											<tr><td colspan=4>Gesti&oacute;n de Soporte</td></tr>
											<tr><td colspan=4>&nbsp;</td></tr>
											<tr><td colspan=4>Correctivo #$incidente</td></tr>
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
	
	function existeSubcategoria(){
		global $mysqli, $mail;
		$idproyectos  = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : "");
		$idcategorias = (!empty($_REQUEST['idcategorias']) ? $_REQUEST['idcategorias'] : "");
		
		$query  = " SELECT idsubcategorias FROM subcategoriaspuente WHERE idcategorias = ".$idcategorias." AND idproyectos = ".$idproyectos.""; 
		$result = $mysqli->query($query);
		if($result->num_rows >0){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function enviarCorreo($asunto,$mensaje,$correos,$adjuntos,$tipo) {
		global $mysqli, $mail;
		$correo = array_unique($correos);
		$cuerpo = "";
		$cuerpo .= "<div style='background:#f6fbf8'>
						<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; margin: 0 6% 0 6%'>";
		$cuerpo .= "		<img src='http://toolkit.maxialatam.com/mitim/images/loginmitim.jpeg' style='width: 5%; float: left;'>";
		$cuerpo .= "		<div style='width: 100%; text-align: center; margin-right: 27%; padding-top: 1%; color: #333; font-weight: bold;'>
								<div>MiTim</div><div>Gestión de Soporte</div>
							</div>";
		$cuerpo .= "	</div>";
		$cuerpo .= $mensaje;
		$cuerpo .= "	<div style='margin: 0 6% 0 6%; background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;clear: both;'>";
		$cuerpo .= "© ".date('Y')." MiTim";
		$cuerpo .= "	</div>
					</div>";	
		$mail->clearAddresses();
		foreach($correo as $destino){
			$mail->addAddress($destino); 
		}		  
		
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
			//echo true;
		} 
		//echo true;
	}
	
	function fusionarIncidentes()
	{
		global $mysqli;
		$fusioninc 	  = $_REQUEST['fusioninc'];
		$idincidentes = json_decode($_REQUEST['idincidentes']);
		$usuario 	  = $_SESSION['usuario'];
		$visibilidad  = "Público"; 
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
				$resolucion = " Este caso ha sido cerrado, pero será atendido por medio del caso con el # ".$fusioninc.", creado previamente sobre el mismo tema.";  
				//Guardar resolución automática a incidente resuelto																																		  
				$query = "UPDATE incidentes SET idestados = 16, idcategorias = '$idmerge', fusionado = ".$fusioninc.", resolucion = '".$resolucion."' 
						  WHERE id = '".$incidente."'";
				if($mysqli->query($query)){
					//Guardar comentario a correctivo a prevalecer
					$comentario = "El correctivo # ".$incidente." ha sido cerrado y será atendido por medio de este caso, creado previamente sobre el mismo tema.";
					if($fusioninc!=""){
						$queryC = "INSERT INTO comentarios VALUES(null, 'Incidentes', ".$fusioninc.", '".$comentario."', '".$visibilidad."', '".$usuario."', NOW(), 'NO')";
						$mysqli->query($queryC);
					}
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
					bitacora($_SESSION['usuario'], "Incidentes", 'El Correctivo #'.$fusioninc.' se fusiono con: '.$incidente, $fusioninc, $query);
					bitacora($_SESSION['usuario'], "Incidentes", 'El Correctivo #'.$incidente.' fue fusionado con: '.$fusioninc, $incidente, $query);
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
			$queryP = "SELECT idcategorias FROM incidentes WHERE id = '$fusionado' ";
			$resultP = $mysqli->query($queryP);
			if($resultP->num_rows >0){
				$rowP = $resultP->fetch_assoc();
				$idcategoria = $rowP['idcategorias'];
			}else{
				$idcategoria = 0;
			}
			
			$query = "UPDATE incidentes SET idestados = 12, fusionado = '', idcategorias = '$idcategoria', resolucion = '' WHERE id = '$id' ";
			if($mysqli->query($query)){
				//Eliminar comentario asociado a fusión, en el incidente a prevalecer
				$comentario = "El correctivo # ".$id." ha sido cerrado y será atendido por medio de este caso, creado previamente sobre el mismo tema.";
				$queryC = " SELECT id FROM comentarios WHERE comentario like '%".$comentario."%' AND idmodulo = ".$fusionado."";
				
				$result = $mysqli->query($queryC);
				if($row = $result->fetch_assoc()){
					$idcomentario = isset($row['id']) ? $row['id'] : 0;
					if($idcomentario != 0){
						$queryD = " DELETE FROM comentarios WHERE id = ".$idcomentario."";
						$mysqli->query($queryD); 
					}
				}
				//ELIMINAR DIRECTORIO EVIDENCIAS
				$dir = '../incidentes/'.$fusionado.'/fusionados/'.$incidente.'/';
				if (!file_exists($dir)) {
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
				}
				 
				bitacora($_SESSION['usuario'], "Incidentes", 'El Correctivo #'.$incidente.' se Revirtió la Fusión con: '.$fusionado, $id, $query);
				bitacora($_SESSION['usuario'], "Incidentes", 'El Correctivo #'.$fusionado.' se Revirtió la Fusión con: '.$incidente, $id, $query);
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
					WHERE a.modulo IN ('Incidentes','Correctivos') AND a.identificador = $id
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
		    $querycolumnausuarios = "SELECT * FROM columnasocultas where modulo = 'Correctivos' and usuario = '$usuario'";
		    $resultcolumnausuarios = $mysqli->query($querycolumnausuarios);
    		if($resultcolumnausuarios->num_rows > 0){
    		    $rowcolumnas = $resultcolumnausuarios->fetch_assoc();
    			$valorcolumnaanterior = $rowcolumnas['columnas'];
    			$columnaagregar = $valorcolumnaanterior.$columna.',';
    			$query = "UPDATE columnasocultas set columnas = '$columnaagregar' where modulo = 'Correctivos' and usuario = '$usuario'";
    		}else{
    		    $columnaagregar = $columna.',';
    			$query = " INSERT INTO columnasocultas (id,columnas,usuario,modulo) VALUES (null,'$columnaagregar','$usuario','Correctivos') ";
    		}
		}else{
		    $querycolumnausuarios = "SELECT * FROM columnasocultas where modulo = 'Correctivos' and usuario = '$usuario'";
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
                    $query = "DELETE FROM columnasocultas where modulo = 'Correctivos' and usuario = '$usuario'";
                }else{
    			    $query = "UPDATE columnasocultas set columnas = '$columnaguardar' where modulo = 'Correctivos' and usuario = '$usuario'";
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
		$query = "SELECT columnas from columnasocultas where modulo = 'Correctivos' and usuario = '$usuario'";
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
		$objPHPExcel->getProperties()->setCreator("MiTim")
		->setLastModifiedBy("MiTim")
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
		$query  = " SELECT a.id, a.titulo, LEFT(a.descripcion,38) AS descripcion, b.nombre AS proyecto, e.nombre AS estado, 
					m.equipo, m.serie as serie, m.activo, m.idmarcas, m.idmodelos, m.modalidad, m.estado as estadoequipo, 
					f.nombre AS categoria, g.nombre AS subcategoria, c.nombre AS sitio, h.prioridad, 
					a.origen, a.creadopor, a.solicitante, a.asignadoa, a.departamento, a.resueltopor,
					a.resolucion, a.satisfaccion, a.comentariosatisfaccion, 
					ifnull(a.fechacreacion, '') AS fechacreacion, a.horacreacion, 
					ifnull(a.fecharesolucion, '') as fecharesolucion, a.horaresolucion,
					ifnull(a.fechacierre, '') as fechacierre, a.horacierre, 
					ifnull(a.fechavencimiento, '') AS fechavencimiento, a.horavencimiento, 
					ifnull(a.fechareal, '') AS fechareal, a.horareal, 
					a.horastrabajadas, cu.periodo, o.nombre as cliente 
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
					LEFT JOIN cuatrimestres cu ON a.fecharesolucion BETWEEN cu.fechainicio AND cu.fechafin
					";		
		if($nivel != 1 && $nivel != 2){
			$query .= " LEFT JOIN usuarios q ON find_in_set(c.codigo, q.sitio) AND q.usuario = '$usuario' ";
		}
		$query  .= " WHERE tipo = 'incidentes' ";
		
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
		}elseif($nivel == 4 || $nivel == 7){
			if($_SESSION['sitio'] != ''){
				$sitio = $_SESSION['sitio'];
				$sitio = explode(',',$sitio);
				$sitio = implode("','", $sitio);
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.idambientes IN ('".$sitio."') ) ";
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
		
		//echo $query;
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
				$where2 .= " AND a.idcategorias IN ($categoriaf)";
			}
			if(!empty($data->subcategoriaf)){
				$subcategoriaf = json_encode($data->subcategoriaf);
				$where2 .= " AND a.idsubcategorias IN ($subcategoriaf)";
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
				if($asignadoaf != "''"){
					$where2 .= " AND a.asignadoa IN ($asignadoaf)";	
				}
			}
			if(!empty($data->unidadejecutoraf)){
				$unidadejecutoraf = json_encode($data->unidadejecutoraf);
				 if($unidadejecutoraf !== '[""]'){ 
					$where2 .= " AND a.idambientes IN ($unidadejecutoraf)";
				}
			}	
			$vowels = array("[", "]");
			$where2 = str_replace($vowels, "", $where2);
		}
		
		//CUERPO
		//Definir fuente
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
		
		$query  .= " $where2 ORDER BY a.id desc ";
		$query.= " LIMIT 50 ";
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
					$query2 .= "correo = '".$row['asignadoa']."' AND estado = 'Activo'";
				}else{
					$query2 .= "correo IN ('".$row['asignadoa']."') AND estado = 'Activo'";
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
			->setCellValue('C'.$i, $row['descripcion']);/*
			->setCellValue('D'.$i, $row['cliente'])
			->setCellValue('E'.$i, $row['proyecto'])
			->setCellValue('F'.$i, $row['estado'])
			->setCellValue('G'.$i, $row['equipo'])
			->setCellValue('H'.$i, $row['idactivos'])
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
			->setCellValue('AM'.$i, $row['periodo']);*/
			
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
		$objPHPExcel->getActiveSheet()->setTitle('Incidentes - Correctivos');

		//Redirigir la salida al navegador del cliente
		$hoy = date('dmY');
		$nombreArc = 'Correctivos - '.$hoy.'.xls';
		header('Content-Type: application/vnd.ms-excel; charset=utf-8');
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
	
	function validarComentarios(){
		global $mysqli;
		
		$idincidente  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idestadosnew = (!empty($_REQUEST['idestadosnew']) ? $_REQUEST['idestadosnew'] : '');
		$asignadoanew = (!empty($_REQUEST['asignadoa']) ? $_REQUEST['asignadoa'] : '');
		
		$query = "	SELECT a.idestados, a.asignadoa, (SELECT COUNT(id) FROM comentarios WHERE idmodulo = ".$idincidente." LIMIT 1) AS totalcomentarios 
					FROM incidentes a WHERE id = ".$idincidente." ";
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
		
	function filtroGrid(){
		global $mysqli;
		$_SESSION['filtrogrid'] = '0';
		$usufiltroexiste = 0;
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Incidentes' AND usuario =".$_SESSION['user_id'];
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
		
		$query = "DELETE FROM usuariosfiltros WHERE modulo = 'Incidentes' AND usuario = '$usuario' ";
		if($mysqli->query($query))
			echo true;
	}
	
	function guardarfiltros() {
		global $mysqli;
		$data = $_REQUEST['data'];
		$usuario = $_SESSION['usuario'];
		$query  = " SELECT * FROM usuariosfiltros WHERE modulo = 'Incidentes' AND usuario = '$usuario' ";
		$result = $mysqli->query($query);
		$count = $result->num_rows;
		if( $count > 0 ) 
			$query = "UPDATE usuariosfiltros SET filtrosmasivos = '$data' WHERE modulo = 'Incidentes' AND usuario = '$usuario'";
		else
			$query = "INSERT INTO usuariosfiltros VALUES (null, '$usuario', 'Incidentes', '', '$data')";
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
	
	function verificarfiltros() {
		global $mysqli;
		$query = " SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Incidentes' AND usuario = '".$_SESSION['usuario']."'";
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
			
		
			
			
			$query  = " SELECT a.id, a.idclientes, a.titulo, a.notificar, i.usuario AS usuariocreadopor, j.usuario AS usuariosolicitante, k.usuario AS usuarioasignadoa,
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
						FROM incidentes a
						LEFT JOIN usuarios i ON a.creadopor = i.correo
						LEFT JOIN usuarios j ON a.solicitante = j.correo
						LEFT JOIN usuarios k ON a.asignadoa = k.correo
						WHERE a.id = ".$incidente." ";
			$result = $mysqli->query($query);
			$row 	= $result->fetch_assoc();
			$idclientes = $row['idclientes'];
			
			//USUARIOS DE SOPORTE
			$correo [] = 'isai.carvajal@maxialatam.com';
			if($idclientes != 55){ //No enviar correos de proyecto MiTim
				//$correo [] = 'ana.porras@maxialatam.com';
				$correo [] = 'fernando.rios@maxialatam.com';
				$correo [] = 'axel.anderson@maxialatam.com';
				$correo [] = 'maria.baena@maxialatam.com';
				//$correo [] = 'yamarys.powell@maxialatam.com';
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
			
			//Usuarios relacionados al correctivo
			if($row['usuariocreadopor'] !="") $idusuarios[$row['usuariocreadopor']] = "0";		
			if($row['usuarioasignadoa'] !="") $idusuarios[$row['usuarioasignadoa']] = "0"; 
			if($row['usuariosolicitante'] !="") $idusuarios[$row['usuariosolicitante']] = "0";
			if($usuarionotificar !="") $idusuarios[$usuarionotificar] = "0";
			
			$usuarios = json_encode($idusuarios);
			
			$sql = " INSERT INTO proyectosnotificaciones (idproyectos,idmodulo,tipo,fecha,hora,usuarios) VALUES (".$idproyectos.",".$incidente.",'Adjunto realizado correctivo','". date("Y-m-d") ."','". date("H:i:s") ."','".$usuarios."')"; 
			$rsql = $mysqli->query($sql); 
				
			//*******************************************//
			//	GUARDAR EN NOTIFICACIONES DEL SISTEMA	 //
			//*******************************************//				
			$cuerpo = "<div style='background:#f6fbf8'>
						<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; margin: 0 6% 0 6%'>";
			$cuerpo .= 	"<img src='http://toolkit.maxialatam.com/mitim/images/encabezado-MiTim-c.png' style='width: auto; float: left;'>";
			$cuerpo .= "		<div style='width: 100%; text-align: center; margin-right: 27%; padding-top: 1%; color: #333; font-weight: bold;'>
								<div>MiTim</div><div>Gestión de Soporte</div>
							</div>";
			$cuerpo .= "	</div>";
			$cuerpo .= "<div style='margin: 0 6%; background-color: #FFFFFF; padding: 30px;font-family: arial,sans-serif;'>
							<div style='margin: 0 6% 0 6%; font-size: 22px;width:100%; color:#333; margin-left: 4%'>".$usuarioAct." ha adjuntado nuevo documento al correctivo #".$incidente."</div><br>";
			$cuerpo .= "	<p style='width:100%;'>
								<a href='http://toolkit.maxialatam.com/mitim/correctivo.php?id=".$incidente."' target='_blank' style='background-color: #2eab51;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;margin-left: 30px;'>Ver Correctivo</a></p>
							</p>
						</div>
						";
			$cuerpo .= "<div style='margin: 0 6% 0 6%; background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;clear: both;'>";
			$cuerpo .= "© ".date('Y')." MiTim";
			$cuerpo .= "	</div></div>";	
			
			$correo = array_unique($correo);
			//debug(json_encode($correo));
			//echo $correo;
			//Correos PM Tigo
			foreach ($correo as $key => $value) { 
				if ($value == 'jose.barahona@tigo.com.pa' || $value == 'mariano.saibene@tigo.com.pa' || $value == 'fabio.beascoechea@tigo.com.pa') { 
					unset($correo[$key]); 
				}
			}			 
			//debugL("notificacionAdjunto-CORREO:".json_encode($correo),"notificacionAdjunto");			
			
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
					$mail->addAddress($destino);		// EVITAR ENVÍO DE CORREO A CLIENTES (DESACTIVADO)
				}			   
			}
			//$mail->addAddress("lisbethagapornis@gmail.com");
			//$mail->addAddress("isai.carvajal@maxialatam.com");
			//$mail->addAddress("fernando.rios@maxialatam.com"); 
			//$mail->addAddress("axel.anderson@maxialatam.com");
			
			$mail->FromName = "MiTim";
			$mail->isHTML(true); // Set email format to HTML
			if($row['solicitante'] == 'mesadeayuda@innovacion.gob.pa' || $row['creadopor'] == 'mesadeayuda@innovacion.gob.pa'){
				$mail->Subject = $row['titulo'];
			}else{
				$mail->Subject = "Correctivo #".$incidente." - Nuevo adjunto";
			}
			
			//$mail->MsgHTML($cuerpo);
			$mail->Body = $cuerpo;
			$mail->AltBody = "MiTim";
			/* if(!$mail->send()) {
				echo 'Mensaje no pudo ser enviado. ';
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				//echo 'Ha sido enviado el correo Exitosamente';
				echo true;
			} */
			echo 1;
		}else{
			echo 0;
		}		
	}
	
	function abrirCorrectivoTemp(){
		global $mysqli;
		$usuario = $_SESSION['usuario'];
		$resultado 	 = array();
		$query  = " SELECT a.id, a.idpreventivos, b.id AS idproyectos, c.id AS unidad, 
					d.id AS serie, d.activo, q.nombre as marca, r.nombre as modelo, e.id AS estado,  
					h.id AS prioridad, a.asignadoa,   
					 a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor,   
					n.id as idempresas, o.id as iddepartamentos, p.id as idclientes, a.idsubambientes 
					FROM incidentestemp a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN activos d ON a.idactivos = d.id AND d.id != ''
					LEFT JOIN estados e ON a.idestados = e.id 
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios i ON a.creadopor = i.correo 
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id 
					LEFT JOIN marcas q ON d.idmarcas = q.id
					LEFT JOIN modelos r ON d.idmodelos = r.id
					WHERE a.usuario = '".$usuario."' ";
		//echo $query;
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){ 
			 
			$resultado[] = array(
						'id' 					=> $row['id'], 
						'idpreventivos' 		=> $row['idpreventivos'], 
						'idempresas' 			=> $row['idempresas'],
						'iddepartamentos'		=> $row['iddepartamentos'],
						'idclientes' 			=> $row['idclientes'],
						'idproyectos' 			=> $row['idproyectos'],
						'unidad' 				=> $row['unidad'],
						'serie' 				=> $row['serie'],
						'activo' 				=> $row['activo'], 
						'estado' 				=> $row['estado'], 
						'prioridad' 			=> $row['prioridad'], 
						'asignadoa' 			=> $row['asignadoa'], 
						'origen' 				=> $row['origen'],
						'creadopor' 			=> $row['creadopor'],  
						'idsubambientes' 		=> $row['idsubambientes'], 
						'marca' 				=> $row['marca'], 
						'modelo' 				=> $row['modelo'], 
						'solicitante' 			=> $row['solicitante'],   
					);
		}
		echo json_encode($resultado);
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
		$objPHPExcel->getProperties()->setCreator("MiTim")
		->setLastModifiedBy("MiTim")
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
					f.nombre AS categoria, g.nombre AS subcategoria, c.nombre AS sitio, h.prioridad, 
					a.origen, a.creadopor, a.solicitante, a.asignadoa, a.departamento, a.resueltopor,
					a.resolucion, a.satisfaccion, a.comentariosatisfaccion, 
					ifnull(a.fechacreacion, '') AS fechacreacion, a.horacreacion, 
					ifnull(a.fecharesolucion, '') as fecharesolucion, a.horaresolucion,
					ifnull(a.fechacierre, '') as fechacierre, a.horacierre, 
					ifnull(a.fechavencimiento, '') AS fechavencimiento, a.horavencimiento, 
					ifnull(a.fechareal, '') AS fechareal, a.horareal, a.horastrabajadas, 
					cu.periodo, o.nombre as cliente, co.comentario 
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.id = c.id
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
					LEFT JOIN cuatrimestres cu ON a.fecharesolucion BETWEEN cu.fechainicio AND cu.fechafin
					LEFT JOIN comentarios co ON a.id = co.idmodulo
					";
		
		if($nivel != 1 && $nivel != 2){
			$query .= " LEFT JOIN usuarios q ON find_in_set(c.codigo, q.sitio) AND q.usuario = '$usuario' ";
		}
		$query  .= " WHERE a.tipo = 'incidentes' ";
		
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
		}elseif($nivel == 4 || $nivel == 7){
			if($_SESSION['sitio'] != ''){
				$sitio = $_SESSION['sitio'];
				$sitio = explode(',',$sitio);
				$sitio = implode("','", $sitio);
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.idambientes IN ('".$sitio."') ) ";
			}else{
				//$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
				if($_SESSION['iddepartamentos'] != ''){
					$iddepartamentosSES = $_SESSION['iddepartamentos'];
					$query  .= "AND a.iddepartamentos IN ('".$iddepartamentosSES."')  ";
				}
			}
		}
		
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
				$where2 .= " AND a.idcategorias IN ($categoriaf)";
			}
			if(!empty($data->subcategoriaf)){
				$subcategoriaf = json_encode($data->subcategoriaf);
				$where2 .= " AND a.idsubcategorias IN ($subcategoriaf)";
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
				if($asignadoaf != "''"){
					$where2 .= " AND a.asignadoa IN ($asignadoaf)";	
				}
			}
			if(!empty($data->unidadejecutoraf)){
				$unidadejecutoraf = json_encode($data->unidadejecutoraf);
				 if($unidadejecutoraf !== '[""]'){ 
					$where2 .= " AND a.idambientes IN ($unidadejecutoraf)";
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
			->setCellValue('F'.$i, $row['idestados'])
			->setCellValue('G'.$i, $row['equipo'])
			->setCellValue('H'.$i, $row['idactivos'])
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
		$objPHPExcel->getActiveSheet()->setTitle('Incidentes - Correctivos');

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