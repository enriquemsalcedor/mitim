<?php
	include_once("../conexion.php");

	if (isset($_REQUEST['oper'])) {
		$opcion = $_REQUEST['oper'];
		
		if ($opcion=='incidentes')
			incidentes();
		if ($opcion=='preventivos')
			preventivos();
		elseif ($opcion=='abrirIncidente')
			abrirIncidente();
		elseif ($opcion=='CATEGORIAS')
			categorias();
		else
			return true;
	}

	function incidentes() {
		global $mysqli;
	
		//FILTROS MASIVO
		$where = "";
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
				if($categoriaf != '[""]'){
					$where2 .= " AND a.idcategoria IN ($categoriaf)";
				}
			}
			if(!empty($data->subcategoriaf)){
				$subcategoriaf = json_encode($data->subcategoriaf);
				if($subcategoriaf != '[""]'){
					$where2 .= " AND a.idsubcategoria IN ($subcategoriaf)";
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
			if(!empty($data->prioridadf)){
				$prioridadf = json_encode($data->prioridadf);
				if($prioridadf != '[""]'){
					$where2 .= " AND a.idprioridad IN ($prioridadf)";
				}				
			}
			if(!empty($data->modalidadf)){
				$modalidadf = json_encode($data->modalidadf);
				if($modalidadf != '[""]'){
					$where2 .= " AND m.modalidad IN ($modalidadf)";
				}
			}
			if(!empty($data->marcaf)){
				$marcaf = json_encode($data->marcaf);
				if($marcaf != '[""]'){
					$where2 .= " AND m.marca IN ($marcaf)"; 
				}
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				if($solicitantef != '[""]'){
					$where2 .= " AND a.solicitante IN ($solicitantef)";
				}
			}
			if(!empty($data->estadof)){
				$estadof = json_encode($data->estadof);
				if($estadof != '[""]'){
					$where2 .= " AND a.estado IN ($estadof)";
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
			if(!empty($data->unidadejecutoraf)){
				$unidadejecutoraf = json_encode($data->unidadejecutoraf);
				 if($unidadejecutoraf !== '[""]'){ 
					$where2 .= " AND a.unidadejecutora IN ($unidadejecutoraf)";
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
		$query  = " SELECT a.id, e.nombre AS estado, a.estado as idestado, a.titulo,
					IFNULL(j.nombre, a.solicitante) AS solicitante, 
					a.fechacreacion, a.horacreacion, a.fechacierre,
					b.nombre AS idproyectos, f.nombre AS categoria, g.nombre AS subcategoria,
					a.asignadoa, l.nombre AS nomusuario, c.unidad AS unidadejecutora, a.serie, 
					m.marca, m.modelo, m.modalidad, h.prioridad, a.fecharesolucion, 
					case when a.fechacierre IS NULL OR a.fechacierre = ''
					then a.fechacreacion else a.fechacierre end as fechaorden,
					n.descripcion as idempresas, o.nombre as iddepartamentos, 
					p.nombre as idclientes
					FROM incidentes2 a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN unidades c ON a.unidadejecutora = c.codigo
					LEFT JOIN estados e ON a.estado = e.id
					LEFT JOIN categorias f ON a.idcategoria = f.id
					LEFT JOIN subcategorias g ON a.idsubcategoria = g.id
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN activos m ON a.serie = m.codequipo AND a.unidadejecutora = m.codigound
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
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
				$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR a.unidadejecutora IN ('".$sitio."') ) ";
			}else{
				//$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
			}			
		}
		$hayFiltros = 0;
		
		$where = "";
		
		$query  .= " $where $where2";
		$query  .= " GROUP BY a.id ";
		debug($query);
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		$query  .= " ORDER BY a.id desc LIMIT 0, 50 ";
		//debug($query);
		$response = '';
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
			
			echo '
			<div class="news-list-item">
				<a href="javascript:abrirIncidente('.$row['id'].')">
					<img src="img/'.$row['idestado'].'.png">
					<strong>'.$row['titulo'].'</strong>
					<span>Estado: '.$row['estado'].' | Creado el: '.$row['fechacreacion'].'</span>
				</a>
			</div>		
			';
			
		}
		
		
	}
	
	function preventivos() {
		global $mysqli;
		
		//FILTROS MASIVO
		$where = "";
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
				if($categoriaf != '[""]'){
					$where2 .= " AND a.idcategoria IN ($categoriaf)";
				}
			}
			if(!empty($data->subcategoriaf)){
				$subcategoriaf = json_encode($data->subcategoriaf);
				if($subcategoriaf != '[""]'){
					$where2 .= " AND a.idsubcategoria IN ($subcategoriaf)";
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
			if(!empty($data->prioridadf)){
				$prioridadf = json_encode($data->prioridadf);
				if($prioridadf != '[""]'){
					$where2 .= " AND a.idprioridad IN ($prioridadf)";
				}				
			}
			if(!empty($data->modalidadf)){
				$modalidadf = json_encode($data->modalidadf);
				if($modalidadf != '[""]'){
					$where2 .= " AND m.modalidad IN ($modalidadf)";
				}
			}
			if(!empty($data->marcaf)){
				$marcaf = json_encode($data->marcaf);
				if($marcaf != '[""]'){
					$where2 .= " AND m.marca IN ($marcaf)"; 
				}
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				if($solicitantef != '[""]'){
					$where2 .= " AND a.solicitante IN ($solicitantef)";
				}
			}
			if(!empty($data->estadof)){
				$estadof = json_encode($data->estadof);
				if($estadof != '[""]'){
					$where2 .= " AND a.estado IN ($estadof)";
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
			if(!empty($data->unidadejecutoraf)){
				$unidadejecutoraf = json_encode($data->unidadejecutoraf);
				 if($unidadejecutoraf !== '[""]'){ 
					$where2 .= " AND a.unidadejecutora IN ($unidadejecutoraf)";
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
		$query  = " SELECT a.id, e.nombre AS estado, a.estado as idestado, a.titulo,
					IFNULL(j.nombre, a.solicitante) AS solicitante, 
					a.fechacreacion, a.horacreacion, a.fechacierre,
					b.nombre AS idproyectos, f.nombre AS categoria, g.nombre AS subcategoria,
					a.asignadoa, l.nombre AS nomusuario, c.unidad AS unidadejecutora, a.serie, 
					m.marca, m.modelo, m.modalidad, h.prioridad, a.fecharesolucion, 
					case when a.fechacierre IS NULL OR a.fechacierre = ''
					then a.fechacreacion else a.fechacierre end as fechaorden,
					n.descripcion as idempresas, o.nombre as iddepartamentos, 
					p.nombre as idclientes
					FROM incidentes2 a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN unidades c ON a.unidadejecutora = c.codigo
					LEFT JOIN estados e ON a.estado = e.id
					LEFT JOIN categorias f ON a.idcategoria = f.id
					LEFT JOIN subcategorias g ON a.idsubcategoria = g.id
					LEFT JOIN sla h ON a.idprioridad = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN activos m ON a.serie = m.codequipo AND a.unidadejecutora = m.codigound
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					";
		if($nivel != 1 && $nivel != 2){
			$query .= " LEFT JOIN usuarios q ON find_in_set(c.codigo, q.sitio) AND q.usuario = '$usuario' ";
		}
		$query  .= " WHERE a.idcategoria in (12,22,35,43) ";
		
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
			}			
		}
		$hayFiltros = 0;
		
		$where = "";
		
		$query  .= " $where $where2";
		$query  .= " GROUP BY a.id ";
		debug($query);
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		$query  .= " ORDER BY a.id desc LIMIT 0, 50 ";
		//debug($query);
		$response = '';
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
			
			echo '
			<div class="news-list-item">
				<a href="javascript:abrirIncidente('.$row['id'].')">
					<img src="img/'.$row['idestado'].'.png">
					<strong>'.$row['titulo'].'</strong>
					<span>Estado: '.$row['estado'].' | Creado el: '.$row['fechacreacion'].'</span>
				</a>
			</div>		
			';
			
		}
		
		
	}



	function abrirIncidente(){
		global $mysqli;
		$id = (!empty($_GET['id']) ? $_GET['id'] : 0);
		$resultado 	 = '';
		$query  = " SELECT a.id, a.titulo, a.descripcion, b.id AS idproyectos,
					c.codigo AS unidad, d.codequipo AS serie, d.activo, d.marca, d.modelo, e.id AS estado, 
					f.id AS categoria, g.id AS subcategoria, h.id AS prioridad, 
					a.solicitante, a.asignadoa, a.departamento, d.modalidad,
					CONCAT_WS('',j.id,' - ', j.titulo) AS fusionado, a.notificar, a.resolucion,
					a.reporteservicio, a.estadomantenimiento, a.observaciones, a.fechacertificar, a.horario,
					a.origen, IFNULL(i.nombre, a.creadopor) AS creadopor, a.comentariosatisfaccion,
					IFNULL(k.nombre, a.resueltopor) AS resueltopor, 
					IF(a.fechacreacion!='', a.fechacreacion,'') AS fechacreacion, a.horacreacion,
					IF(a.fechavencimiento!='',CONCAT(a.fechavencimiento,'  ', a.horavencimiento),'') AS fechavencimiento,
					IF(a.fecharesolucion!='',CONCAT(a.fecharesolucion,'  ', a.horaresolucion),'') AS fecharesolucion,
					a.fechacierre, a.horacierre,a.fechamodif, a.fechacertificar, a.horastrabajadas,
					n.id as idempresas, o.id as iddepartamentos, p.id as idclientes, a.atencion
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN unidades c ON a.unidadejecutora = c.codigo
					LEFT JOIN activos d ON a.serie = d.codequipo AND d.codequipo != ''
					LEFT JOIN estados e ON a.estado = e.id
					LEFT JOIN categorias f ON a.idcategoria = f.id
					LEFT JOIN subcategorias g ON a.idsubcategoria = g.id
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
						'fechamodif' 			=> $row['fechamodif'],
						'fechacertificar' 		=> $row['fechacertificar'],
						'horastrabajadas' 		=> $row['horastrabajadas'],
						'periodo' 				=> $row['periodo'],
						'atencion' 				=> $row['atencion']
					);
		}
		echo json_encode($resultado);
	}

function categorias() {
	global $mysqli;
	
	$desde = date("Y") - 1 . date("m") + 1 . "01";
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	$usuario 		 = $_SESSION['usuario'];
	$nivel 			 = $_SESSION['nivel'];
	$idempresas 	 = $_SESSION['idempresas'];
	$iddepartamentos = $_SESSION['iddepartamentos'];
	$idclientes 	 = $_SESSION['idclientes'];
	$idproyectos 	 = $_SESSION['idproyectos'];
	$query = "SELECT concat(l.nombre, ' ', p.nombre) as name, count(i.id) as y 
			FROM incidentes2 i 
			INNER JOIN proyectos p ON p.id = i.idproyectos
			INNER JOIN clientes l ON l.id = i.idclientes
			INNER JOIN usuarios u ON i.asignadoa = u.correo
			INNER JOIN usuarios j ON i.solicitante = j.correo
			WHERE i.fechacreacion <= '$hasta' ";

	if ($nivel>2) 
		$query .= "	AND i.idempresas in ($idempresas) 
			AND i.idclientes in ($idclientes)
			AND i.idproyectos in ($idproyectos) ";
			
	if($_SESSION['sitio'] != ''){
		$sitio = $_SESSION['sitio'];
		$sitio = explode(',',$sitio);
		$sitio = implode("','", $sitio);
		$query  .= "AND (j.usuario = '".$_SESSION['usuario']."' OR i.unidadejecutora IN ('".$sitio."') ) ";
	}else{
		//$query  .= "AND (j.usuario = '".$_SESSION['usuario']."') ";
	}			
	$query .= "
			GROUP BY name ";
	$result = $mysqli->query($query);
	debug($query);
	$response = new StdClass;
	$rows = array();
	$i=0;
	while ($row = $result->fetch_assoc()){
		$rows[] = $row;
	}
	
	echo json_encode($rows);
}

?>