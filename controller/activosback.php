<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "activos": 
			  activos();
			  break;		
		case "createactivo":
			  createactivo();
			  break;
		case "updateactivo":
			  updateactivo();
			  break;
	    case "getactivo":
			  getactivo();
			  break;
		case "deleteactivo":
			  deleteactivo();
			  break;
		case "trasladaractivo":
			  trasladaractivo();
			  break;
		case "traslados":
			  traslados();
			  break;
		case "importaractivos":
			  importaractivos();
			  break;
		case "comentarios":
			  comentarios();
			  break;
		case "agregarComentario":
			  agregarComentario();
			  break;
		case "eliminarcomentarios":
			  eliminarcomentarios();
			  break;
		case "serialesbit":
			  serialesbit();
			  break;
		case "fueraservicio":
			  fueraservicio();
			  break;
		case "adjuntosComentarios":
			  adjuntosComentarios();
			  break;
		case "comentariosleidos":
			  comentariosleidos();
			  break;
		case "abrirSolicitudes":
			  abrirSolicitudes();
			  break;
		case "correctivos":
			  correctivos();
			  break;
		case "preventivos":
			  preventivos();
			  break;
		case "existeMttosFuturos":
			  existeMttosFuturos();
			  break;
		case "hayRelacion":
			  hayRelacion();
			  break;
		case "editarMasivo":
			  editarMasivo();
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
	
	function activos() 
	{		
		global $mysqli;		 
		$usuario 	 = (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : '');
		$idusuario 	 = (!empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
		$nivel		 = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		$idclientes  = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : 0);
		$idproyectos = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : 0);

		/*$where = ""; codigo anterior
		$data2   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');		
		$searchGeneral   = (!empty($_POST['search']['value']) ? $_POST['search']['value'] : '');
		$data = "";
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : '');
	    $start    = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$rowperpage   = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
        $vacio = array();
		$columns   = (!empty($_REQUEST['columns']) ? $_REQUEST['columns'] : $vacio);*/
		$vacio = array();
		$columns = (!empty($_REQUEST['columns']) ? $_REQUEST['columns'] : $vacio);
		$start = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$rowperpage = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		$where = "";
		$where2 = array();
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		//contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);
		/*----------------------------------------------------------------------
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		//Obtener el nombre de la columna de clasificación de su índice
		$orderBy= (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ?$_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );
		//ASC or DESC
		*/
		/* $orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); 
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10); */
		/*--------------------------------------------------------------------*/
		
		$query  = " SELECT a.id, a.serie, LEFT(a.nombre,45) AS nombre, a.activo, ma.nombre AS marca, mo.nombre AS modelo, ur.nombre AS responsable,
					a.idambientes, a.modalidad, sa.nombre AS subambiente, a.estado, a.fase, a.fechatopemant,
					a.fechainst, b.nombre AS ambiente, c.descripcion AS idempresas, d.nombre AS idclientes, 
					e.nombre AS idproyectos, ti.nombre AS tipo
					FROM activos a 
					LEFT JOIN ambientes b ON a.idambientes = b.id 
					LEFT JOIN subambientes sa ON a.idsubambientes = sa.id 
					LEFT JOIN empresas c ON a.idempresas = c.id 
					INNER JOIN clientes d ON a.idclientes = d.id 
					INNER JOIN proyectos e ON a.idproyectos = e.id 
					LEFT JOIN usuarios u ON u.idproyectos = e.id
					LEFT JOIN usuarios ur ON a.idresponsables = ur.id
					LEFT JOIN marcas ma ON a.idmarcas = ma.id
					LEFT JOIN modelos mo ON a.idmodelos = mo.id
					LEFT JOIN activostipos ti ON a.idtipo = ti.id
					WHERE 1 = 1 ";
					
		$query .= permisos('activos', '', $idusuario);
		
		/* if($nivel == 3 || $idusuario == 329){
		    $query .= " AND u.id = $idusuario ";
		}
		
		if($nivel == 4 || $nivel == 7){
			if($idclientes != ''){
				$arr = strpos($idclientes, ',');
				if ($arr !== false) {
					$query  .= " AND a.idclientes IN (".$idclientes.") ";
				}else{
					$query  .= " AND find_in_set(".$idclientes.",a.idclientes) ";
				}  
			}
			if($idproyectos != ''){
				$arr = strpos($idproyectos, ',');
				if ($arr !== false) {
					$query  .= " AND a.idproyectos IN (".$idproyectos.") ";
				}else{
					$query  .= " AND find_in_set(".$idproyectos.",a.idproyectos) ";
				}  
			}	
		} */

		$hayFiltros = 0;
		$where2 = array();
		for($i=0 ; $i<count($columns);$i++){
			$column = $_REQUEST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			if ($_REQUEST['columns'][$i]['search']['value']!="") {

                
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);
				
				if ($column == 'serie'){
					$column = 'a.serie';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'activo'){
					$column = 'a.activo';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'nombre'){
					$column = 'a.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'modalidad'){
					$column = 'ti.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'marca'){
					$column = 'ma.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'modelo'){
					$column = 'mo.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'responsable'){
					$column = 'ur.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'idambientes'){
					$column = 'a.idambientes';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'ambiente'){
					$column = 'b.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'subambiente'){
					$column = 'sa.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'fase'){
					$column = 'a.fase';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'fechatopemant'){
					$column = 'a.fechatopemant';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'fechainst'){
					$column = 'a.fechainst';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'idempresas'){
					$column = 'c.descripcion';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'idclientes'){
					$column = 'd.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'idproyectos'){
					$column = 'e.nombre';
					$where2[]= " $column like '%".$campo."%' ";
				}
				if ($column == 'estado'){
					$column = 'a.estado';
					$where2[]= " $column like '%".$campo."%' ";
				}

				$hayFiltros++;
			}
		}		
		if ($hayFiltros > 0)
			$where = " AND ".implode(" AND " , $where2)." ";// id like '%searchValue%' or name like '%searchValue%'
		else
			$where = "";
		

		$searchGeneral   = (!empty($_POST['search']['value']) ? $_POST['search']['value'] : '');		
		if($searchGeneral != ''){
			$where.= " AND (
								a.serie LIKE '%".$searchGeneral."%' OR
								a.activo LIKE '%".$searchGeneral."%' OR
								a.nombre LIKE '%".$searchGeneral."%' OR
								ti.nombre LIKE '%".$searchGeneral."%' OR
								ma.nombre LIKE '%".$searchGeneral."%' OR
								mo.nombre LIKE '%".$searchGeneral."%' OR
								ur.nombre LIKE '%".$searchGeneral."%' OR
								a.idambientes LIKE '%".$searchGeneral."%' OR
								b.nombre LIKE '%".$searchGeneral."%' OR
								sa.nombre LIKE '%".$searchGeneral."%' OR
								a.fase LIKE '%".$searchGeneral."%' OR
								a.fechatopemant LIKE '%".$searchGeneral."%' OR
								a.fechainst LIKE '%".$searchGeneral."%' OR
								c.descripcion LIKE '%".$searchGeneral."%' OR
								d.nombre LIKE '%".$searchGeneral."%' OR
								e.nombre LIKE '%".$searchGeneral."%' OR
								a.estado LIKE '%".$searchGeneral."%' 
			)";


		} 
		
		$query  .= " $where ";

		$query .= " GROUP BY a.id "; 
		debugL($query,"activos");
// 		echo $query;
		 
		$result = $mysqli->query($query);
//		$recordsTotal = $result->num_rows;
		$recordsTotal = $result->num_rows;
		//$query  .= " ORDER BY a.id DESC ";
		$query  .= " ORDER BY a.id DESC  LIMIT ".$start.",".$rowperpage;
//		$recordsFiltered = $result->num_rows;
		debugL("ACTIVOS ES:".$query,'DEBUGLACTIVOS');
		$response = array();
		$response['data'] = array();
		$resultado = array();
		$result = $mysqli->query($query);
	
		while($row = $result->fetch_assoc()){
			$tieneEvidencias   = '';
			$rutaE 		= '../activos/'.$row['id'];
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
				$ruta = '../activos/'.$row['id'].'/comentarios';
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
			$coment = " SELECT count(visto) AS total, MAX(fecha) AS fecha FROM comentariosactivos WHERE idmodulo = '".$row['id']."' ";			
			$rcomen = $mysqli->query($coment);	
			//debugL($coment);
			$row2 = $rcomen->fetch_assoc();
			$totalco = $row2['total'];
			$fecha   = $row2['fecha'];
						 	
			if($totalco > 0){				
				$comentN = " SELECT (SELECT COUNT(*) FROM comentariosactivos WHERE visto = 'SI' AND idmodulo = ".$row['id'].") AS si, 
							(SELECT COUNT(*) FROM comentariosactivos WHERE visto = 'NO' AND idmodulo = ".$row['id'].") AS no ";
				 
				$rcomenN = $mysqli->query($comentN);
				$rowN = $rcomenN->fetch_assoc();
				$totalSi = $rowN['si']; 
				$totalNo = $rowN['no']; 
				 
				if($totalSi > 0 && $totalNo <= 0){ 
					if($fecha < "2020-07-29"){
						$iconcoment = "<span class='icon-col blue fa fa-comment boton-coment-".$row['id']."' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Comentarios' data-placement='right'></span>";
					}else{
						$coment2 = " SELECT count(a.id) AS total 
									 FROM `comentariosvistosactivos` a 
									 LEFT JOIN comentariosactivos b ON b.id = a.idcomentario 
									 WHERE a.usuario = '".$usuario."' 
									 AND 
									 b.idmodulo = '".$row['id']."'";
								 
						$rcomen2 = $mysqli->query($coment2);
								
						$rowv = $rcomen2->fetch_assoc();
						$totalv = $rowv['total'];
						
						if($totalv == $totalco){
							$iconcoment = "<span class='icon-col blue fa fa-comment  boton-coment-".$row['id']."' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Comentarios' data-placement='right'></span>";
						}else{
							$iconcoment = "<span class='icon-col green fa fa-comment boton-coment-".$row['id']."' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Comentarios' data-placement='right'></span>";
						} 			   
					}
				}elseif($totalSi <= 0 && $totalNo > 0){
					$iconcoment = "<span class='icon-col green fa fa-comment boton-coment-".$row['id']."' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Comentarios' data-placement='right'></span>";
				}elseif($totalSi >= 0 && $totalNo > 0){
					$iconcoment = "<span class='icon-col green fa fa-comment boton-coment-".$row['id']."' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Comentarios' data-placement='right'></span>";
					//No Visto
				} 
			}else{
				$iconcoment = ""; 
			} 
			
			$btnVer = '<a class="dropdown-item text-warning" href="activo.php?id='.$row['id'].'&type=view"><i class="fas fa-eye mr-2"></i>Ver</a>';

			$btnEditar = '<a class="dropdown-item text-info" href="activo.php?id='.$row['id'].'&type=edit"><i class="fas fa-pen mr-2"></i>Editar</a>';

			$btnAdjuntar ='<a class="dropdown-item text-'.$color.' boton-adjuntar" data-id="'.$row['id'].'"><i class="fas fa-camera mr-2"></i>Evidencias</a>';
			
			$btnEliminar='<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>';
			
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center droptable">';
								
			if($nivel != 3 && $nivel != 4 && $nivel != 5){									
				$acciones.=$btnEditar;
				$acciones.=$btnAdjuntar;
				$acciones.=$btnEliminar;
			}else{
				$acciones.=$btnVer;
				$acciones.=$btnAdjuntar;
			} 
								$acciones .= '</div>
							</div>
						</td>'; 
			
			$resultado[] = array(	
				'check' 			=>	"",
				'id' 				=>	$row['id'],	
				'acciones' 			=>	$acciones,
				'serie'				=>	mb_strtoupper($row['serie']), 
				'nombre' 			=>	mb_strtoupper($row['nombre']), 
				'activo' 			=>	mb_strtoupper($row['activo']), 
				'marca' 			=>	mb_strtoupper($row['marca']), 
				'modelo' 			=>	mb_strtoupper($row['modelo']), 
				'responsable' 		=>	mb_strtoupper($row['responsable']),
				'idambientes' 		=>	$row['idambientes'],
				'ambiente' 			=>	mb_strtoupper($row['ambiente']),
				'modalidad' 		=>	mb_strtoupper($row['tipo']), 
				'subambiente' 		=>	mb_strtoupper($row['subambiente']), 
				'estado' 			=>	ucwords(strtolower($row['estado'])),
				'fase' 				=>	mb_strtoupper($row['fase']),
				'fechatopemant' 	=>	$row['fechatopemant'],			
				'fechainst' 		=>	$row['fechainst'],
				'idempresas' 		=>	mb_strtoupper($row['idempresas']),
				'idclientes' 		=>	mb_strtoupper($row['idclientes']),
				'idproyectos' 		=>	mb_strtoupper($row['idproyectos']) 				
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


	
	function  createactivo()
	{
		global $mysqli;
		header('Content-Type: application/json');
		$idambientes	= (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : '');
		$modalidad		= (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '');
		$serie 	     	= (!empty($_REQUEST['serie']) ? $_REQUEST['serie'] : '');
		$nombre			= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$idmarcas		= (!empty($_REQUEST['idmarcas']) ? $_REQUEST['idmarcas'] : '');
		$idmodelos		= (!empty($_REQUEST['idmodelos']) ? $_REQUEST['idmodelos'] : '');
		$activo			= (!empty($_REQUEST['activo']) ? $_REQUEST['activo'] : '');
		$idresponsables = (!empty($_REQUEST['idresponsables']) ? $_REQUEST['idresponsables'] : '');
		$idambientes	= (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : '');
		$idsubambientes	= (!empty($_REQUEST['idsubambientes']) ? $_REQUEST['idsubambientes'] : '');
		$estado			= (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : '');
		$edificio		= (!empty($_REQUEST['edificio']) ? $_REQUEST['edificio'] : '');
		$fase			= (!empty($_REQUEST['fase']) ? $_REQUEST['fase'] : '');
		//$comentarios	= (!empty($_REQUEST['comentarios']) ? $_REQUEST['comentarios'] : '');
		$fechatopemant	= (!empty($_REQUEST['fechatopemant']) ? $_REQUEST['fechatopemant'] : '');
		$fechainst		= (!empty($_REQUEST['fechainst']) ? $_REQUEST['fechainst'] : '');
		$idempresas		= (!empty($_REQUEST['idempresas']) ? $_REQUEST['idempresas'] : 1);
		$idclientes		= (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0);
		$idproyectos	= (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : 0);
		$idtipo			= (!empty($_REQUEST['idtipo']) ? $_REQUEST['idtipo'] : 0);
		$idsubtipo		= (!empty($_REQUEST['idsubtipo']) ? $_REQUEST['idsubtipo'] : 0);
		$contenido		= (!empty($_REQUEST['contenido']) ? $_REQUEST['contenido'] : 0);
		$contenidolimpio= (!empty($_REQUEST['contenidolimpio']) ? $_REQUEST['contenidolimpio'] : 0);
		$vidautil		= (!empty($_REQUEST['vidautil']) ? $_REQUEST['vidautil'] : "");
		$ingresos		= (!empty($_REQUEST['ingresos']) ? $_REQUEST['ingresos'] : "");
		$usuario 		= $_SESSION['usuario'];
		$fecha 			= date("Y-m-d"); 
		$esfecha = esFecha($dateinst,'m/d/Y');
		/* if($esfecha == 1){
			$date 			= date_create($dateinst);
			$fechainst 		= date_format($date, 'Y-m-d');
		}else{
			$fechainst = "";
		} */
		$idusuario 		= $_SESSION['user_id'];
		
		$campos = array(
			'Serie' 	  	=> $serie,
			'Activo' 		=> $activo,
			'Nombre' 		=> $nombre,
			'Marca' 		=> getValor('nombre','marcas',$idmarcas),
			'Modelo' 		=> getValor('nombre','modelos',$idmodelos),
			'Modalidad'		=> $modalidad,
			'Responsable'	=> getValor('nombre','usuarios',$idresponsables),
			'Ambiente'		=> getValor('nombre','ambientes',$idambientes),		
			'Subambiente'	=> getValor('nombre','subambientes',$idsubambientes),
			'Fase'			=> $fase,
			'Fecha Tope. Mant.'	=> $fechatopemant,
			'Fecha Inst.'		=> $fechainst,
			//'Comentarios'		=> $comentarios,
			'Empresas'		=> getValor('descripcion','empresas',$idempresas),
			'Clientes'		=> getValor('nombre','clientes',$idclientes),
			'Proyectos'		=> getValor('nombre','proyectos',$idproyectos),
			'Tipo'			=> getValor('nombre','activostipos',$idtipo),
			'Subtipo'		=> getValor('nombre','activossubtipos',$idsubtipo)
		);
		
		$conn = "SELECT serie FROM activos where serie = '$serie' AND idempresas = '$idempresas' AND idclientes = '$idclientes' ";
		$resultn = $mysqli->query($conn);		
		$totn = $resultn->num_rows;
		
		if($totn > 0){
			echo 2;			
		}else{
			$query = "  INSERT INTO activos (id, nombre, serie, activo, modalidad, idmarcas, idmodelos, idempresas, idclientes, idproyectos, 
						idambientes, idsubambientes, idresponsables, estado, edificio, fase, fechatopemant, fechainst, idtipo, idsubtipo, campossubtipos,vidautil,ingresos) 
					    VALUES (null, '".$nombre."', '".$serie."', '".$activo."', '".$modalidad."', '".$idmarcas."', '".$idmodelos."', '".$idempresas."',
					    '".$idclientes."', '".$idproyectos."', '".$idambientes."', '".$idsubambientes."', '".$idresponsables."', '".$estado."', '".$edificio."',
						'".$fase."', '".$fechatopemant."', '".$fechainst."', '".$idtipo."', '".$idsubtipo."', '".$contenidolimpio."', '".$vidautil."', '".$ingresos."') ";
			debugL('INSERTACTIVO:'.$query);
			$result = $mysqli->query($query);
				
			if($result==true){
				$idactivo = $mysqli->insert_id; 
				
				if($idambientes != ""){
					$sqlT = "  INSERT INTO activostraslados 	(idactivos,ambienteanterior,ambientenuevo,usuario,fechatraslado) 
							VALUES ('".$idactivo."','Ninguno','".$idambientes."','".$usuario."','".$fecha."') "; 
					debugL("CREATEACTIVO:".$sqlT);
					$rsqlT = $mysqli->query($sqlT);
				} 
			
				$data = json_decode($contenido, true);
				if(is_array($data))
				{
					foreach($data as $item){ 
						$valorid   = $item['id'];
						$valoritem = $item['valor'];
						debug("VALORITEMES:".$valoritem);
						debugL("VALORITEMES:".$valoritem);
						
						$queryU = "UPDATE activos SET campossubtipos = JSON_REPLACE(campossubtipos,'$.".$valorid."','".$valoritem."') WHERE id ='".$idactivo."'"; 
						debugL("QUERYUPDATE:".$queryU);
						
						$result = $mysqli->query($queryU);
					} 
				}else{
					echo json_encode('no array');
				}
				
				
				$queryE = "  INSERT INTO activosseriales
							(idactivos,serialanterior,serialnuevo,usuario,fechacambio,horacambio,dias)
							 VALUES($idactivo, '$serie', '$serie', $idusuario, CURDATE(), CURTIME(), 0) ";
				$mysqli->query($queryE);
				//debug('ACT:'.$queryE);
				nuevoRegistro('Activos','Activos',$idactivo,$campos,$query);
				echo 1; 
			}else{
				echo 0; 
			} 
		}
	}
		
	function updateactivo() 
	{
		global $mysqli;
		
		$id  	 		 = (!empty($_REQUEST['id']) ? $_REQUEST['id']  : 0); 
		$idambientes	= (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : '');
		$modalidad		= (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '');
		$serie 	     	= (!empty($_REQUEST['serie']) ? $_REQUEST['serie'] : '');
		$nombre			= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$idmarcas		= (!empty($_REQUEST['idmarcas']) ? $_REQUEST['idmarcas'] : '');
		$idmodelos		= (!empty($_REQUEST['idmodelos']) ? $_REQUEST['idmodelos'] : '');
		$activo			= (!empty($_REQUEST['activo']) ? $_REQUEST['activo'] : '');
		$idresponsables = (!empty($_REQUEST['idresponsables']) ? $_REQUEST['idresponsables'] : '');
		$idambientes	= (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : '');
		$idsubambientes	= (!empty($_REQUEST['idsubambientes']) ? $_REQUEST['idsubambientes'] : '');
		$estado			= (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : '');
		$edificio		= (!empty($_REQUEST['edificio']) ? $_REQUEST['edificio'] : '');
		$fase			= (!empty($_REQUEST['fase']) ? $_REQUEST['fase'] : '');
		//$comentarios	= (!empty($_REQUEST['comentarios']) ? $_REQUEST['comentarios'] : '');
		$fechatopemant	= (!empty($_REQUEST['fechatopemant']) ? $_REQUEST['fechatopemant'] : '');
		$fechainst		= (!empty($_REQUEST['fechainst']) ? $_REQUEST['fechainst'] : '');
		$idempresas		= (!empty($_REQUEST['idempresas']) ? $_REQUEST['idempresas'] : 1);
		$idclientes		= (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0);
		$idproyectos	= (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : 0);
		$idtipo			= (!empty($_REQUEST['idtipo']) ? $_REQUEST['idtipo'] : 0);
		$idsubtipo		= (!empty($_REQUEST['idsubtipo']) ? $_REQUEST['idsubtipo'] : 0);
		$contenido		= (!empty($_REQUEST['contenido']) ? $_REQUEST['contenido'] : 0);
		$contenidolimpio= (!empty($_REQUEST['contenidolimpio']) ? $_REQUEST['contenidolimpio'] : 0);
		$vidautil		= (!empty($_REQUEST['vidautil']) ? $_REQUEST['vidautil'] : "");
		$ingresos		= (!empty($_REQUEST['ingresos']) ? $_REQUEST['ingresos'] : "");
		$idusuario 		= $_SESSION['user_id'];
		$usuario 		= $_SESSION['usuario'];
		$fecha			= date("Y-m-d");  
		$ahora 			= date('H:i:s'); 
		$esfecha = esFecha($dateinst,'m/d/Y');
		
		/* if($esfecha == 1){ 
			$date = date_create($dateinst);
			$fechainst = date_format($date, 'Y-m-d');
		}else{ 
			$fechainst = "";
		} */
		
		$campos = array(
			'Serie' 	  	=> $serie,
			'Activo' 		=> $activo,
			'Nombre' 		=> $nombre,
			'Marca' 		=> getValor('nombre','marcas',$idmarcas),
			'Modelo' 		=> getValor('nombre','modelos',$idmodelos),
			'Modalidad'		=> $modalidad,
			'Responsable'	=> getValor('nombre','usuarios',$idresponsables),
			'Ambiente'		=> getValor('nombre','ambientes',$idambientes),		
			'Subambiente'	=> getValor('nombre','subambientes',$idsubambientes),
			'Fase'			=> $fase,
			'Fecha Tope. Mant.'	=> $fechatopemant,
			'Fecha Inst.'		=> $fechainst,
			//'Comentarios'		=> $comentarios,
			'Empresas'		=> getValor('descripcion','empresas',$idempresas),
			'Clientes'		=> getValor('nombre','clientes',$idclientes),
			'Proyectos'		=> getValor('nombre','proyectos',$idproyectos),
			'Tipo'			=> getValor('nombre','activostipos',$idtipo),
			'Subtipo'		=> getValor('nombre','activossubtipos',$idsubtipo),
			'Estado'		=> $estado,
		);
		
		$valoresold = getRegistroSQL("  SELECT a.serie AS Serie, a.activo AS Activo, a.nombre AS Nombre, ma.nombre AS Marca, mo.nombre AS Modelo, 
										a.modalidad AS Modalidad, ur.nombre AS Responsable, b.nombre as Ambiente, sa.nombre AS Subambiente, 
										a.fase AS Fase, a.fechatopemant AS 'Fecha Tope. Mant.', a.fechainst AS 'Fecha Inst.',
										c.descripcion AS Empresas, d.nombre AS Clientes, e.nombre AS Proyectos, at.nombre AS 'Tipo', ast.nombre AS 'Subtipo', a.estado AS Estado
										FROM activos a 
										LEFT JOIN ambientes b ON a.idambientes = b.id 
										LEFT JOIN subambientes sa ON a.idsubambientes = sa.id 
										LEFT JOIN empresas c ON a.idempresas = c.id 
										LEFT JOIN clientes d ON a.idclientes = d.id 
										LEFT JOIN proyectos e ON a.idproyectos = e.id 
										LEFT JOIN usuarios u ON u.idproyectos = e.id
										LEFT JOIN usuarios ur ON a.idresponsables = ur.id
										LEFT JOIN marcas ma ON a.idmarcas = ma.id
										LEFT JOIN modelos mo ON a.idmodelos = mo.id
										LEFT JOIN activostipos at ON a.idtipo = at.id
										LEFT JOIN activossubtipos ast ON a.idsubtipo = ast.id
									    WHERE a.id = '".$id."' ");
		
		$nomant 	= getValor('serie','activos',$id);		
		$comn 		= "SELECT serie FROM activos where serie = '$serie' AND serie != '$nomant' AND idempresas = $idempresas AND idclientes = $idclientes ";
		$resultn 	= $mysqli->query($comn);
		$totn 		= $resultn->num_rows;
		
		if($totn > 0){
			echo 2;			
		}else{	
			//VERIFICO EL ESTADO
			$query  = "SELECT estado, serie, idambientes FROM activos WHERE id = ".$id." ";
			$result = $mysqli->query($query);
			if($result->num_rows > 0){
				$result   	   = $result->fetch_assoc();			
				$estadobd 	   = $result['estado'];
				$seriebd 	   = $result['serie'];
				$idambientesbd = $result['idambientes'];
			}
			
			if($idambientesbd != $idambientes && $idambientes != "" && $idambientesbd != ""){
				$sqlT = "  INSERT INTO activostraslados (idactivos,ambienteanterior,ambientenuevo,usuario,fechatraslado) 
						VALUES ('".$id."','".$idambientesbd."','".$idambientes."','".$usuario."','".$fecha."') "; 
				$rsqlT = $mysqli->query($sqlT);
				if($rsqlT == true){
					$sqlP = " 	SELECT id FROM incidentes WHERE tipo = 'preventivos' AND ((fechacreacion = '".$fecha."' AND horacreacion > '".$ahora."') OR (fechacreacion > '".$fecha."'))
							AND idactivos = '".$id."' AND idambientes = '".$idambientesbd."'";
							debugL("QUERY sqlP:".$sqlP);
							$rsqlP = $mysqli->query($sqlP); 
							while($rowP = $rsqlP->fetch_assoc()){
								$idpreventivo = $rowP['id'];
								$sqlUp = " UPDATE incidentes SET 
											idambientes = '".$idambientes."' 
											WHERE 
											idactivos = '".$id."'
											AND tipo = 'preventivos'
											AND idambientes = '".$idambientesbd."'
											AND id = '".$idpreventivo."'";
								$rsqlUp = $mysqli->query($sqlUp); 
							}
				}
				
			}
			
			if($seriebd != $serie){
				//CREAR REGISTRO EN ACTIVOS SERIALES
				$queryE = " SELECT serialnuevo, fechacambio FROM activosseriales WHERE idactivos = '$id' ORDER BY id DESC LIMIT 1 ";
				$resultE = $mysqli->query($queryE);
				if($resultE->num_rows >0){
					$rowE = $resultE->fetch_assoc();
					$serialanterior = $seriebd;
					$fechacambio = $rowE['fechacambio'];
				}/* else{
					$serialanterior = $seriebd;
					$qfechac = " SELECT fechacreacion FROM incidentes WHERE id = $id ";
					$rfechac = $mysqli->query($qfechac);
					$regf = $rfechac->fetch_assoc();
					$fechacambio = $regf['fechacreacion'];
				} */
				
				$fechahoy = date('Y-m-d');
				$date1 = new DateTime($fechahoy);
				$date2 = new DateTime($fechacambio);
				$diff = $date1->diff($date2);
				
				$queryE = " INSERT INTO activosseriales (idactivos, serialanterior, serialnuevo, usuario, fechacambio, horacambio, dias ) 
							VALUES ($id, '$seriebd', '$serie', $idusuario, CURDATE(), CURTIME(), $diff->days) ";
				$mysqli->query($queryE);  
				//debug('UPACT:'.$queryE);
			}
			
			$query = "  UPDATE activos SET idambientes = '$idambientes', modalidad = '$modalidad', serie = '$serie',
						nombre = '$nombre', idmarcas = '$idmarcas', idmodelos = '$idmodelos', activo = '$activo', idresponsables = '$idresponsables',
						idsubambientes = '$idsubambientes', estado = '$estado', fase = '$fase', fechatopemant = '$fechatopemant',
						fechainst = '$fechainst',idempresas = '$idempresas',idclientes = '$idclientes',idproyectos = '$idproyectos', idtipo = '$idtipo', idsubtipo = '$idsubtipo', campossubtipos = '$contenidolimpio', vidautil = '$vidautil', ingresos = '$ingresos'
						WHERE id = '$id' ";	
			
			$result = $mysqli->query($query);
			if($result == true){
				//Actualizar campos del subtipo
				$data = json_decode($contenido, true);
				if(is_array($data))
				{
					foreach($data as $item){ 
						$valorid   = $item['id'];
						$valoritem = $item['valor'];
						debug("VALORITEMES:".$valoritem);
						debugL("VALORITEMES:".$valoritem);
						
						$queryU = "UPDATE activos SET campossubtipos = JSON_REPLACE(campossubtipos,'$.".$valorid."','".$valoritem."') WHERE id ='".$id."'"; 
						debugL("QUERYUPDATE:".$queryU);
						
						$result = $mysqli->query($queryU);
					} 
				}else{
					echo json_encode('no array');
				}
				
				if($estadobd != $estado){
					$buscar = array(chr(13).chr(10), "\r\n", "\n", "\r", "  ");
					$reemplazar = array(" ", " ", " ", " ", "");
					$queryA = str_ireplace($buscar, $reemplazar, $query);

					$queryB = 'INSERT INTO activosbitacora VALUES(null, "'.$_SESSION['usuario'].'", now(), "'.$id.'", "'.$estado.'", "'.$queryA.'") ';
					
					$resultB = $mysqli->query($queryB);
				}		
				actualizarRegistro('Activos', 'Activos', $id, $valoresold, $campos, $query);
				echo 1;
			}else{
				echo 0;
			}
		} 
	}

	function getactivo(){
		global $mysqli;
		
		$idactivos = (!empty($_REQUEST['idactivos']) ? $_REQUEST['idactivos'] : 0);
		$query 		= "	SELECT * FROM activos WHERE id = '".$idactivos."' ";
		//debug('getactivo: '.$query);
		$result 	= $mysqli->query($query);
		
		if($row = $result->fetch_assoc()){ 
			/* $fechainst = "";	   
			$date = date_create($row['fechainst']);
			if($row['fechainst'] != ""){
				
				$esfecha = esFecha($row['fechainst'],"Y-m-d");
				if($esfecha == 1){ 
					$fechainst = date_format($date, 'm/d/Y');
				}else{ 
					$fechainst = "";
				} 
			}else{ 
				$fechainst = "";
			} */ 
			debug('idambientes: '.$query.'-'.$row['serie'].'-'.$row['idambientes']);
			$ingresos = number_format((float)$row['ingresos'], 2, '.', ',');
			
			$resultado = array(
				'modalidad'	 		=>	$row['modalidad'],
				'serie' 			=>	$row['serie'],
				'nombre'	 		=>	$row['nombre'],
				'idmarcas'	 		=>	$row['idmarcas'],
				'idmodelos'	 		=>	$row['idmodelos'],
				'activo'	 		=>	$row['activo'],
				'idresponsables'	=>	$row['idresponsables'],
				'idambientes'	 	=>	$row['idambientes'],	
				'idsubambientes'	=>	$row['idsubambientes'],	
				'estado'	 		=>	$row['estado'],	
				//'edificio'	 	=>	$row['edificio'],
				'fase'		 		=>	$row['fase'],	
				//'comentarios'	 	=>	$row['comentarios'],	
				'fechatopemant'	 	=>	$row['fechatopemant'],	 	
				//'fechainst'	 		=>	$fechainst,	
				'fechainst'	 		=>	$row['fechainst'],	
				'idempresas'	 	=>	$row['idempresas'],	
				'idclientes'	 	=>	$row['idclientes'],	
				'idproyectos'	 	=>	$row['idproyectos'],	
				'idtipo'	 		=>	$row['idtipo'],	
				'idsubtipo'	 		=>	$row['idsubtipo'],	
				'vidautil'	 		=>	$row['vidautil'],	
				//'ingresos'	 		=>	$row['ingresos'],	
				'ingresos'	 		=>	$ingresos,	
				//'campossubtipos'	=>	(array)$row['campossubtipos']	
				'campossubtipos'	=>	$row['campossubtipos']	
			);
		}
			if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}	
		
	function esFecha($x,$formato) {
		if (date($formato, strtotime($x)) == $x) {
		  return 1;
		} else {
		  return 0;
		}
	}
	function deleteactivo() 
	{
		global $mysqli;
		
		$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
		$nombre = getValor('nombre','activos',$id);
		
		$query = "DELETE FROM activos WHERE id = '$id'";
		
		$result = $mysqli->query($query); 
		
		if($result==true){
		    
			$sqlT = " DELETE FROM activostraslados WHERE idactivos = ".$id."";
			$rsqlT = $mysqli->query($sqlT); 
			
		    eliminarRegistro('Activos','Activos',$nombre,$id,$query);
			
			echo 1;
		    
		}else{
			echo 0;
		}  
	}
	
	function  trasladaractivo()
	{
		global $mysqli; 
		$idactivos    	 = (!empty($_REQUEST['idactivos']) ? $_REQUEST['idactivos'] : 0);
		$ambienteanterior= (!empty($_REQUEST['ambienteanterior']) ? $_REQUEST['ambienteanterior'] : '');
		$subambienteanterior= (!empty($_REQUEST['subambienteanterior']) ? $_REQUEST['subambienteanterior'] : '');
		$ambientenuevo   = (!empty($_REQUEST['ambientenuevo']) ? $_REQUEST['ambientenuevo'] : '');
		$subambientenuevo   = (!empty($_REQUEST['subambientenuevo']) ? $_REQUEST['subambientenuevo'] : '');
		$usuario 	     = (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : '');
		$fecha           = date('Y-m-d'); 
		$ahora 			 = date('H:i:s');
		//debugL("FECHA:".$fecha."-AHORA:".$ahora);
		$actualizar = " UPDATE activos SET idambientes = '$ambientenuevo', idsubambientes = '$subambientenuevo' WHERE id = '$idactivos' ";
		                
		if($result = $mysqli->query($actualizar)){		    
		    $query = "  INSERT INTO activostraslados (idactivos,ambienteanterior,ambientenuevo,subambienteanterior,subambientenuevo,usuario,fechatraslado) 
						VALUES ('$idactivos','$ambienteanterior','$ambientenuevo','$subambienteanterior','$subambientenuevo','$usuario','$fecha') ";
    		
    	    $result = $mysqli->query($query);    	    	
    		if($result == true){
				$sqlP = " 	SELECT id FROM incidentes WHERE tipo = 'preventivos' AND  ((fechacreacion = '".$fecha."' AND horacreacion > '".$ahora."') OR (fechacreacion > '".$fecha."'))
							AND idactivos = '".$idactivos."' AND idambientes = '".$ambienteanterior."' AND idsubambientes = '".$subambienteanterior."' ";
							debugL("QUERY sqlP-II:".$sqlP);
							$rsqlP = $mysqli->query($sqlP); 
							while($rowP = $rsqlP->fetch_assoc()){
								$idpreventivo = $rowP['id'];
								$sqlUp = " UPDATE incidentes SET 
											idambientes = '".$ambientenuevo."', idsubambientes = '".$subambientenuevo."' 
											WHERE 
											idactivos = '".$idactivos."'
											AND tipo = 'preventivos'
											AND idambientes = '".$ambienteanterior."' AND idsubambientes = '".$subambienteanterior."'
											AND id = '".$idpreventivo."'";
								$rsqlUp = $mysqli->query($sqlUp);  
							} 
    		    bitacora($_SESSION['usuario'], "Activos", "El activo #".$idactivos." fue trasladado al ambiente #'".$ambientenuevo."', subambiente #'".$subambientenuevo."' ", $idactivos, $query);    			
    			echo 1;     		    
    		}else{    		
    			echo 0;     		    
    		}    
		}		
	}
	
	function existeMttosFuturos(){
		global $mysqli;
		
		$idactivos    	 = (!empty($_REQUEST['idactivos']) ? $_REQUEST['idactivos'] : 0);
		
		$fecha = date("Y-m-d");
		$ahora = date("H:i:s");
		
		$sqlP = " 	SELECT COUNT(*) AS total FROM incidentes WHERE tipo = 'preventivos' AND ((fechacreacion = '".$fecha."' AND horacreacion > '".$ahora."') OR (fechacreacion > '".$fecha."'))
					AND idactivos = '".$idactivos."'";
					debugL("QUERY sqlP:".$sqlP);
					$rsqlP = $mysqli->query($sqlP); 
					if($rowP = $rsqlP->fetch_assoc()){
						$existePr = $rowP["total"];
						debugL("EXISTETR:".$existePr);
						if($existePr > 0){
							echo 1;
						}else{
							echo 0;
						}
					}
	}
	
	function comentarios(){
		global $mysqli; 
		
		$nivel 		= $_SESSION['nivel'];
		$idmodulo	= (!empty($_GET['id']) ? $_GET['id'] : 0);
		$buscar 	= (isset($_POST['buscar']) ? $_POST['buscar'] : '');
		$resultado 	= array();
		$acciones 	= ''; 
		
		$query  = " SELECT a.id, a.idmodulo, a.comentario, a.fecha, b.nombre, a.visibilidad
					FROM comentariosactivos a
					LEFT JOIN usuarios b ON a.usuario = b.usuario
					WHERE idmodulo IN (".$idmodulo.") AND a.visibilidad != '' ";
		/* if($nivel == 4){
			$query .= " AND a.visibilidad = 'Público' ";
		} */
		$query .= " ORDER BY a.id DESC ";
		//debug($query);
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		//$query  .= " LIMIT $start, $length ";
		while($row = $result->fetch_assoc()){
			//ADJUNTOS
			$adjuntos   = '';
			$ruta 		= '../activos/'.$row['idmodulo'].'/comentarios/'.$row['id'];
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
									<a class="dropdown-item text-'.$color.' boton-adjuntos-comentarios"  data-id="'.$row['idmodulo'].'-'.$row['id'].'"><i class="fas fa-camera mr-2"></i>Evidencias de comentario</a>';
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
	
	function agregarComentario(){
		global $mysqli;
		$idmodulo	= $_REQUEST['id'];
		$comentario = $_REQUEST['coment'];
		$usuario 	= $_SESSION['usuario'];
		$visibilidad = $_REQUEST['visibilidad'];
		$fecha 		= date("Y-m-d");
		$id_preventivo = 0;
		if($comentario != ''){
			$queryI = "	INSERT INTO comentariosactivos (idmodulo,comentario,visibilidad,usuario,fecha,visto) 
						VALUES($idmodulo, '$comentario', '$visibilidad', '$usuario', NOW(), 'NO')";
			//debug('queryI: '.$_GET['comentario']);
			if($mysqli->query($queryI)){
				$id = $mysqli->insert_id;
				//BITACORA
				bitacora($_SESSION['usuario'], "Activos", "Se ha registrado un Comentario para el Activo #".$idmodulo, $incidente, $queryI);
				//ENVIAR NOTIFICACION
				/* if($visibilidad == 'Privado'){
					notificarComentariosSoporte($incidente,$comentario,$visibilidad);
					notificarComentariosAsignados($incidente,$comentario,$visibilidad);
				}else{
					//notificarComentariosSoporte($incidente,$comentario,$visibilidad);
					notificarComentarios($incidente,$comentario,$visibilidad);
				} */
				echo true;
			}else{
				echo false;
			}
		}else{
			echo false;
		}
	}
	
	function eliminarcomentarios()
	{
		global $mysqli;

		$idactivos	 = $_REQUEST['idactivos'];
		$id 	 	 = $_REQUEST['idcomentario']; 
		$nivel 	 	 = $_SESSION['nivel'];
		$usuario 	 = $_SESSION['usuario'];
		 
		//Elimino el comentario si es usuario administrador o soporte
		//if ($nivel==1 || $nivel==2){
			$queryEs    = " DELETE FROM comentariosactivos WHERE id = '$id'";
			$resultEs   = $mysqli->query($queryEs);
			if($resultEs){
				//Elimino evidencias del comentario
				$carpeta = '../activos/'.$idactivos.'/comentarios/'.$id.'/';
				deleteDirectory($carpeta);
			    echo 1;
			}else{
			    echo 0;
			}
		/* }else{
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
		} */
		bitacora($_SESSION['usuario'], "Activos", 'El Comentario #: '.$id.' fue eliminado.', $id, $query); 
	}
	
	function comentariosleidos(){
		global $mysqli;		
		$idactivos = $_REQUEST['idactivos'];
		$usuario   = $_SESSION['usuario'];
		
		$queryC = "	SELECT id FROM comentariosactivos WHERE idmodulo = '$idactivos' AND visto != '' ";
		$resultC = $mysqli->query($queryC);
		while($rowC = $resultC->fetch_assoc()){
			$idc = $rowC['id'];
		    $queryV = " SELECT count(id) AS id FROM comentariosvistosactivos WHERE idcomentario = '".$idc."' 
						AND usuario = '".$usuario."' ";
			$resultV = $mysqli->query($queryV);
			$rowV = $resultV->fetch_assoc();
			$idv = $rowV['id'];
			if($idv == 0){  
				$query = "INSERT INTO comentariosvistosactivos (idcomentario, usuario, fecha)
						  VALUES ('$idc', '$usuario', NOW())";
				debug('CV:'.$query);
			    $result = $mysqli->query($query);
				if($result == true){
					$upd = " UPDATE comentariosactivos SET visto = 'SI'
							 WHERE idmodulo = '$idactivos' AND visto = 'NO' ";
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
	
	function abrirSolicitudes() {
		$idactivos 	= (!empty($_REQUEST['idactivos']) ? $_REQUEST['idactivos'] : '');
		$_SESSION['incidente_cor'] = $idactivos;
		//Activos
		$myPathInc = '../activos';
		$target_pathInc = utf8_decode($myPathInc);
		if (!file_exists($target_pathInc)) {
			mkdir($target_pathInc, 0777);
		}
		//Activo
		$myPathI = '../activos/'.$idactivos;
		$target_pathI = utf8_decode($myPathI);
		if (!file_exists($target_pathI)) {
			mkdir($target_pathI, 0777);
		} 
		//Ruta
		$Path = '/../activos/'.$idactivos.'/';
		$hash = strtr(base64_encode($Path), '+/=', '-_.');
		$hash = rtrim($hash, '.');
		echo "l1_". $hash;
	}
	
	function adjuntosComentarios() {
		$idactivoscom 	= $_REQUEST['idactivos'];
		$arr 			= explode('-',$idactivoscom);
		$activo 		= $arr[0];
		$comentario 	= $arr[1];
		$_SESSION['activo'] 	= $activo;
		$_SESSION['comentario'] = $comentario;
		
		$myPathA 	  = '../activos/'.$activo;
		$target_pathA = utf8_decode($myPathA);
		if (!file_exists($target_pathA)) {
			mkdir($target_pathA, 0777);
		}
		
		$myPathC 	  = '../activos/'.$activo.'/comentarios/';
		$target_pathC = utf8_decode($myPathC);
		if (!file_exists($target_pathC)) {
			mkdir($target_pathC, 0777);
		}
		$myPath 	 = '../activos/'.$activo.'/comentarios/'.$comentario;
		$target_path = utf8_decode($myPath);
		if (!file_exists($target_path)) {
			mkdir($target_path, 0777);
		}
		//$Path = dirname($_SERVER['PHP_SELF']) . '/../incidentes/'.$_SESSION['incidente'].'/';
		$Path = '/../activos/'.$activo.'/comentarios/'.$comentario.'/';
		$hash = strtr(base64_encode($Path), '+/=', '-_.');
		$hash = rtrim($hash, '.');		
		echo "l1_". $hash;		
	}
	
	function serialesbit(){
		global $mysqli;
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
		$start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);

		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado = array();
		
		$query  = " SELECT a.serialanterior, a.serialnuevo, a.fechacambio, a.dias
					FROM activosseriales a
					WHERE a.idactivos = $id ORDER BY a.id DESC ";
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		while($row = $result->fetch_assoc()){
			$resultado[] = array(
				'serialant' => $row['serialanterior'],
				'serialact' => $row['serialnuevo'],
				'fecha'		=> $row['fechacambio'],
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
	
	function fueraservicio(){
		global $mysqli;
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
		$start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);

		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado = array();
		
		$query  = " SELECT a.codequipo, a.desde, a.hasta, a.incidente
					FROM fueraservicio a
					WHERE a.codequipo = '".$id."' ORDER BY a.id DESC ";
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		while($row = $result->fetch_assoc()){
			$resultado[] = array(
				'serial' 	=> $row['codequipo'],
				'desde' 	=> $row['desde'],
				'hasta'		=> $row['hasta'],
				'incidente'	=> $row['incidente']
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
	
	function correctivos(){
		global $mysqli;
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
		$start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);

		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado = array();
		
		$query  = " SELECT a.id,a.titulo,d.nombre AS estado,a.fechacreacion,b.nombre AS solicitante,c.nombre AS asignadoa 
					FROM incidentes a 
					LEFT JOIN usuarios b ON b.correo = a.solicitante 
					LEFT JOIN usuarios c ON c.correo = a.asignadoa 
					INNER JOIN estados d ON d.id = a.idestados 
					WHERE idactivos = ".$id." 
					AND a.tipo = 'incidentes' ORDER BY a.id DESC ";
		//echo $query;
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		while($row = $result->fetch_assoc()){
			$resultado[] = array(
				'id' 			=> $row['id'],
				'titulo' 		=> $row['titulo'],
				'estado'		=> $row['estado'],
				'fechacreacion'	=> $row['fechacreacion'],
				'solicitante'	=> $row['solicitante'],
				'asignadoa'		=> $row['asignadoa'],
				'accion'		=> 	"<div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>  
										<span class='icon-col blue fa fa-arrow-right boton-ir-correctivo' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Ir a correctivo' data-placement='right'></span>
									</div>"
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
	
	function preventivos(){
		global $mysqli;
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
		$start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);

		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado = array();
		
		$query  = " SELECT a.id,a.titulo,d.nombre AS estado,a.fechacreacion,b.nombre AS solicitante,c.nombre AS asignadoa 
					FROM incidentes a 
					LEFT JOIN usuarios b ON b.correo = a.solicitante 
					LEFT JOIN usuarios c ON c.correo = a.asignadoa 
					INNER JOIN estados d ON d.id = a.idestados  
					WHERE idactivos = ".$id." 
					AND a.tipo = 'preventivos' ORDER BY a.id DESC ";
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		while($row = $result->fetch_assoc()){
			$resultado[] = array(
				'id' 			=> $row['id'],
				'titulo' 		=> $row['titulo'],
				'estado'		=> $row['estado'],
				'fechacreacion'	=> $row['fechacreacion'],
				'solicitante'	=> $row['solicitante'],
				'asignadoa'		=> $row['asignadoa'],
				'accion'		=> 	"<div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>  
										<span class='icon-col blue fa fa-arrow-right boton-ir-preventivo' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Ir a preventivo' data-placement='right'></span>
									</div>"
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
	
	function traslados(){		
		global $mysqli;	 
		
		$idactivo = (!empty($_REQUEST['idactivo']) ? $_REQUEST['idactivo']: 0);
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : ''); 
		$draw 				 = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    $orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		$orderBy		     = (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ? $_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );//Get name of the sorting column from its index
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); // ASC or DESC
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		
		$query  = " SELECT a.id, a.idactivos, a.fechatraslado, b.nombre, c.nombre as nombreusuario, d.nombre as ambienteold, e.nombre as ambientenew,
		            f.nombre as subambienteold, g.nombre as subambientenew
					FROM activostraslados a 
					LEFT JOIN activos b ON a.idactivos = b.id 
					LEFT JOIN usuarios c ON a.usuario = c.usuario 
					LEFT JOIN ambientes d ON a.ambienteanterior = d.id 
					LEFT JOIN ambientes e ON a.ambientenuevo = e.id 
					LEFT JOIN subambientes f ON a.subambienteanterior = f.id 
					LEFT JOIN subambientes g ON a.subambientenuevo = g.id 
					WHERE a.idactivos = '".$idactivo."'
					ORDER BY id DESC ";
	    //echo $query;
		$result = $mysqli->query($query); 
		$resultado = array();
		while($row = $result->fetch_assoc()){			
			$resultado[] = array(			
				'id' 					=>	$row['id'], 
				'idactivos'				=>	$row['idactivos'], 
				'ambienteanterior'		=>	$row['ambienteold'], 
				'subambienteanterior'	=>	$row['subambienteold'], 
				'ambientenuevo' 		=>	$row['ambientenew'], 
				'subambientenuevo' 		=>	$row['subambientenew'], 
				'usuario' 				=>	$row['nombreusuario'], 
				'fecha' 				=>	$row['fechatraslado']
			);
		} 
		$response = array( 
			"data" => $resultado
		  );
		echo json_encode($response);
		
	}
	
	function editarMasivo(){
		global $mysqli;		
		
		$id   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$data = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
		 
		$idarray = explode(",", $id);
		if(count($idarray) > 1){
		 
			$query = "";
			if($data != ''){
		
				$i = 0;
				$coma = ',';
				$query .= "UPDATE activos SET ";
				foreach($data as $c => $v){
		
					if($v != '' && $v != '0'){
		
						if($i != 0){
							$query .= $coma;
						}
						if($c == 'idclientesmas'){
							$query .= " idclientes = '$v' ";
						}elseif($c == 'idproyectosmas'){
							$query .= " idproyectos = '$v' ";
						}elseif($c == 'idmarcasmas'){
							$query .= " idmarcas = '$v' ";
						}elseif($c == 'idmodelosmas'){
							$query .= " idmodelos = '$v' ";
						}elseif($c == 'idresponsablesmas'){
							$query .= " idresponsables = '$v' ";
						}elseif($c == 'idubicacionesmas'){
							$query .= " idambientes = '$v' ";
						}elseif($c == 'idareasmas'){
							$query .= " idsubambientes = '$v' ";
						}elseif($c == 'estadomas'){
							$query .= " estado = '$v' ";
						}elseif($c == 'idtiposmas'){
							$query .= " idtipo = '$v' ";
						}elseif($c == 'idsubtiposmas'){
							$query .= " idsubtipo = '$v' ";
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
								bitacora($_SESSION['usuario'], "Activos", 'El Activo #'.$id.' ha sido Editado exitosamente', $id, $query2);
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
	
	function checkActivosRepetida($serie,$nombre,$marca='',$modelo='',$empresa='',$cliente='',$proyecto=''){		
		global $mysqli; 
		
		//$idempresas  	= getId('id', 'empresas', $empresa, 'descripcion');
		$idempresas  	= 1;
		$idclientes  	= getId('id', 'clientes', $cliente, 'nombre');
		$idproyectos  	= getId('id', 'proyectos', $proyecto, 'nombre');
				
		$q = " SELECT id FROM activos WHERE serie = '$serie' AND nombre = '$nombre' AND idmarcas = '$marca' AND idmodelos = '$modelo '
			   AND idempresas = '$idempresas' AND idclientes = '$idclientes' AND idproyectos = '$idproyectos' LIMIT 1 ";
		$r = $mysqli->query($q);
		
		$num = $r->num_rows;
		
		return $num;
	}
	
	function importaractivos(){
		global $mysqli;
		require_once '../../repositorio-lib/xls/Classes/PHPExcel.php';
		require '../../repositorio-lib/phpspreadsheet/vendor/autoload.php';
		
		if(isset($_FILES)) {
			$nombrefile	 	= $_FILES['archivo']['name'];
			$ArrArchivo = explode(".", $nombrefile);
			$extension 	= strtolower(end($ArrArchivo));
			$randName 	= md5(rand() * time());
			$path 		= '../activos/';

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
				//$objReader = new PHPExcel_Reader_Excel2007();
				$objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
				$objReader->setReadDataOnly(true);						
			}
			//CODIGO DE LECTURA Y ESCRITURA
			$objPHPExcel = $objReader->load($rutaA);			
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			
			$importadasExito = 0;
			$importadasAct = 0;
			$importadasExitosas = 0;
			$importadasError = 0;
			$causasError = '<ul>';	
			
			//Se comienza en la fila 5 a procesar el contenido, la fila 1 debe ser el titulo			
			for ($row = 5; $row < $highestRow+1; $row++){
				// Si ninguna celda esta en blanco
				if (trim($sheet->getCell('A' . $row)->getValue()) != '' && trim($sheet->getCell('B' . $row)->getValue()) != '' ){
					
					$repetida = checkActivosRepetida($sheet->getCell('A' . $row)->getValue(),$sheet->getCell('B' . $row)->getValue(),1,$sheet->getCell('B1')->getValue(),$sheet->getCell('D1')->getValue());
					/*
					$titulo = $sheet->getCell('C' . $row)->getValue();
					if($titulo != ''){
						$btitulo  = "SELECT titulo FROM plan where titulo = '$titulo' ";
						$resultbp = $mysqli->query($btitulo);
						$nbrows = $resultbp->num_rows;
						if($nbrows > 0){
							$causasError .= '<li>Error en la fila '.$row.', la actividad ya existe</li>';
							$importadasError++;
						}
					}
					*/
					$repetida = 0;
					if($repetida == 0){
						$rowData[] = $sheet->rangeToArray('A' . $row . ':' . 'H' . $row, NULL, TRUE, FALSE);
						$importadasExitosas++;				    
					} else {
						$causasError .= '<li>Error en la fila '.$row.', el activo ya existe</li>';
						$importadasError++;
					}
				} else {	
					if (trim($sheet->getCell('C' . $row)->getValue()) == ''  ){
						//FILA VACIA
					}else{ 
						$importadasError++;
						if(trim($sheet->getCell('A' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', el campo <b>ACTIVO</b> está vacío</li>';
						}
						if(trim($sheet->getCell('B' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', el campo <b>N° DE SERIE</b> está vacío</li>';
						} 
						if(trim($sheet->getCell('D' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', el campo <b>MARCA</b> está vacío</li>';
						}
						if(trim($sheet->getCell('E' . $row)->getValue()) == ''){									  
							$causasError .= '<li>Error en la fila '.$row.', el campo <b>MODELO</b> está vacío</li>';
						}
						if(trim($sheet->getCell('F' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', el campo <b>UBICACIÓN / SITIO</b> está vacío</li>';
						}
						if(trim($sheet->getCell('G' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', el campo <b>ÁREA</b> está vacío</li>';
						}
						if(trim($sheet->getCell('H' . $row)->getValue()) == ''){
							$causasError .= '<li>Error en la fila '.$row.', el campo <b>RESPONSABLE / ASIGNADO</b> está vacío</li>';
						} 
					//}
					}
				}		
				$causasError .= '</ul>';
				
				$acciones = '';
				$listaImportadas = '<ul>';
			}
			//debug(count($rowData));
			//BD			
			for ($j = 0; $j < count($rowData); $j++){
				$ArrItem 	= $rowData[$j][0];
				/* 
				$fecha		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[1], "yyyy-mm-dd");
				$hora 		= PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[2], "h:mm:ss");
				*/	
				//$formatfechatopemant = PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[10], "yyyy-mm-dd");
				//$formatfechainst     = PHPExcel_Style_NumberFormat::toFormattedString($ArrItem[11], "yyyy-mm-dd");
				
				//$formatfechatopemant = $ArrItem[10];
				//$formatfechainst     = $ArrItem[11];
				 
				//$empresas  	   = 1;
				$clientes      = $sheet->getCell('B1')->getValue();
				$proyectos     = $sheet->getCell('D1')->getValue();
				$nombre        = trim(str_replace(' ', '', $ArrItem[0]));
				$serie	   = trim(str_replace(' ', '', $ArrItem[1]));
				$activo        = trim(str_replace(' ', '', $ArrItem[2]));
				$marca         = trim(str_replace(' ', '', $ArrItem[3]));
				$modelo        = trim(str_replace(' ', '', $ArrItem[4]));
				$ambiente      = trim(str_replace(' ', '', $ArrItem[5]));
				$subambiente          = trim(str_replace(' ', '', $ArrItem[6]));
				$responsable    = trim(str_replace(' ', '', $ArrItem[7]));					
				
				/* $modalidad     = trim(str_replace(' ', '', $ArrItem[7]));
				$fase          = trim(str_replace(' ', '', $ArrItem[9]));
				$fechatopemant = $formatfechatopemant;
				$fechainst     = $formatfechainst; 
				$estado        = trim(str_replace(' ', '', $ArrItem[12]));
				$comentarios   = trim(str_replace(' ', '', $ArrItem[13])); */
				//IDS 
				$idambientes 	   = getId('codigo', 'ambientes', $ambiente, 'ambiente');
				//$idempresas    = getId('id', 'empresas', $empresas, 'descripcion');
				$idclientes    = getId('id', 'clientes', $clientes, 'nombre');
				$idproyectos   = getId('id', 'proyectos', $proyectos, 'nombre');
				
				$queryAc = " SELECT id FROM activos WHERE serie = '".$serie."' ";
				$resultAc = $mysqli->query($queryAc);
				if($resultAc->num_rows > 0){
					$row = $resultAc->fetch_assoc();				
					$id = $row['id'];
					
					$query = "UPDATE activos SET ";
					//if($idempresas != '')	{	$query .= ", idempresas = '$idempresas' ";	}
					if($idclientes != '')	{	$query .= ", idclientes = '$idclientes' ";	}
					if($idproyectos != '')	{	$query .= ", idproyectos = '$idproyectos' ";	}
					if($idambientes != '')	{	$query .= " idambientes = '$idambientes' ";	}
					if($nombre != '')		{	$query .= ", nombre = '$nombre' ";	}
					if($marca != '')		{	$query .= ", idmarcas = '$marca' ";	}
					if($modelo != '')		{	$query .= ", idmodelos = '$modelo' ";	}
					if($activo != '')		{	$query .= ", activo = '$activo' ";	}
					if($responsable != '')	{	$query .= ", responsable = '$responsable' ";	}
					if($subambiente != '')			{	$query .= ", subambiente = '$subambiente' ";	}
					
					//if($modalidad != '')	{	$query .= ", modalidad = '$modalidad' ";	}
					//if($estado != '')		{	$query .= ", estado = '$estado' ";	}
					//if($fase != '')			{	$query .= ", fase = '$fase' ";	}
					//if($comentarios != '')	{	$query .= ", comentarios = '$comentarios' ";	}
					//if($fechatopemant != ''){	$query .= ", fechatopemant = '$fechatopemant' ";	}
					//if($fechainst != '')	{	$query .= ", fechainst = '$fechainst' ";	}
					
					$query .= " WHERE serie = '$serie' ";
					$query = str_replace('SET ,','SET ',$query);					
					$result = $mysqli->query($query);
					$importadasAct++;					
				}else{
					$query = "INSERT INTO activos (idambientes,ambiente,serie,nombre,idmarcas,idmodelos,activo,responsable,subambiente,estado, 
							  idempresas,idclientes,idproyectos) 
							  VALUES ('$idambientes', NULL, '$serie', '$nombre', '$marca', '$modelo', '$activo', '$responsable',
							  '$subambiente', 'ACTIVO', '1','$idclientes','$idproyectos') ";
					//debug($query);
					$result = $mysqli->query($query);
					$id = $mysqli->insert_id;
					$importadasExito++;
				}				
				
				if($idambientes == ''){
					$causasError .= '<li>Error: El Activo con el número de serie: <b>'.$serie.'</b> no ha sido relacionado con el sitio, ya que el nombre del sitio no existe</li>';
					$importadasError++;
				}
				$listaImportadas .= '<li> Cliente: '.$clientes.',Proyecto: '.$proyectos.', Activo: '.$nombre.', N° de Serie: '.$serie.', N° de Activo: '.$activo.', Marca: '.$marca.', Modelo: '.$modelo.',
										  Ubicación / Sitio: '.$idambientes.',  subambiente: '.$subambiente.', Responsable / Asignado: '.$responsable.'
									. </li>';
			}			
			$listaImportadas .= '</ul>';
		
			if($result == true){
				$resultado  = $importadasExito.' filas creadas exitosamente. <br/>';
				$resultado .= $importadasAct.' filas actualizadas exitosamente. <br/>';
				$resultado .= $importadasError. ' filas con error. <br/>';
				$resultado .= $causasError;
				echo $resultado;
				
				// bitacora
				$acciones .= 'Fue importado el archivo '.$nombrefile.' para la creación de los activos.<br/><br/>';
				$acciones .= '<b>Resultado:</b><br/>';
				$acciones .= $resultado;
				$acciones .= '<b>Activos importadas:</b><br/>';
				$acciones .= $listaImportadas;
				
				bitacora($_SESSION['usuario'],'Activos',$acciones,0,'');
			}else{
				$resultado = $importadasError. ' filas con error. <br/>';
				$resultado .= $causasError;
				echo $resultado;
			}
		}
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
	
	function hayRelacion(){
	    global $mysqli;
	    
	    $id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
	    
	    $existe = array(
            'correctivos' => 0,
            'preventivos' => 0  
        ); 
        
        $qC = " SELECT id FROM incidentes a 
                WHERE idactivos = ".$id." AND tipo = 'incidentes' LIMIT 1 ";
                  
        $rC = $mysqli->query($qC);
		if($rC->num_rows > 0){ 
            $existe['correctivos'] = 1; 
        } 
		
		$qP = " SELECT id FROM incidentes a 
                WHERE idactivos = ".$id." AND tipo = 'preventivos' LIMIT 1 ";
                  
        $rP = $mysqli->query($qP);
		if($rP->num_rows > 0){ 
            $existe['preventivos'] = 1; 
        }        
                  
	    echo json_encode($existe);
	}
?>