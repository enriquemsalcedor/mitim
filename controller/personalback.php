<?php
	include("../conexion.php");

	if (isset($_REQUEST['opcion'])) {
		$opcion = $_REQUEST['opcion'];
		
		if ($opcion=='MAPA')
			mapa();
		elseif ($opcion=='TIEMPOS')
			tiempos();
		elseif ($opcion=='TENDENCIA')
			tendencia();
		elseif ($opcion=='UNIDADES')
			unidades();
		elseif ($opcion=='DETALLES')
			detalles();
		elseif ($opcion=='DETALLES2')
			detalles2();
		elseif ($opcion=='DATOS')
			datos();
		elseif ($opcion=='DATOSUNIDAD')
			datosunidad();
		elseif ($opcion=='COMBOESTATUS')
			comboEstatus();
		elseif ($opcion=='TECNICOS')
			tecnicos();
		elseif ($opcion=='RADIOLOGOS')
			radiologos();
		else
			return true;
	}

function mapa() {
	global $mysqli;
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : date('Y'));
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : '');
	$modalidad =  (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : "*");
	$tipo =  (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : "");
	$provincia =  (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : "*");
	$persona =  (!empty($_REQUEST['persona']) ? $_REQUEST['persona'] : "tecnicos");
	
	$consulta = " select count(distinct nombre) as total
		from $persona d
		where 1=1  
		";
	if ($modalidad!="*" && $modalidad!="") 
		$consulta .= "and d.modalidad like '%$modalidad%' ";
	
	if ($agno!="")
		$consulta .= "and d.ano like '%$agno%' ";
	
	if ($mes!="")	
		$consulta .= "and d.mes like '%$mes%' ";
	
	if ($tipo!="")
		$consulta .= "and d.unidad like '%$tipo%' ";
	
	if ($provincia!="*")	
		$consulta .= "and d.provincia = '$provincia' ";
		
	$result = $mysqli->query($consulta);
	if ($rec = $result->fetch_assoc()){
		$total = $rec['total']; 
	}
	
	
	$consulta = "
		select u.unidad as title, u.lat, u.lon, 9 as zoom, 
		case when u.unidad like '%Hospital%' then 'images/markerh.png' 
		else case when u.unidad like '%Poli%' then 'images/markerp.png' 
		else 'images/markeru.png' end end as icon,
		count(distinct d.nombre) as circle_options,
		count(distinct d.nombre) as html
		from $persona d 
		inner join unidades u on u.codigo=d.codigo
		Where 1=1 ";
		
	if ($modalidad!="*" && $modalidad!="") 
		$consulta .= "and d.modalidad like '%$modalidad%' ";
	
	if ($agno!="")
		$consulta .= "and d.ano like '%$agno%' ";
	
	if ($mes!="")	
		$consulta .= "and d.mes like '%$mes%' ";
	
	if ($tipo!="")
		$consulta .= "and d.unidad like '%$tipo%' ";
	
	if ($provincia!="*")	
		$consulta .= "and d.provincia = '$provincia' ";
	
	$consulta .= "group by title, lat, lon, zoom, icon";
	
	$result = $mysqli->query($consulta);
	
	$objJson = array();
	$objJson2 = array();
	if ($persona=='tecnicos')
		$factor = 3500;
	else
		$factor = 700;
	while($rec = $result->fetch_assoc()){
		$objJson2["strokeColor"] = "#000000";
		if ($rec['icon']=='images/markerh.png')
			$objJson2["fillColor"] = "#3B5998";
		elseif ($rec['icon']=='images/markerp.png')
			$objJson2["fillColor"] = "#0976B4";
		else
			$objJson2["fillColor"] = "#1769FF";
		$objJson2["radius"] =  $rec['circle_options'] * 100 / $total * $factor ;
		$rec['circle_options'] = $objJson2;
		$rec['html'] = $rec['html'] . ' Equipos';
		$objJson[] = $rec; 
	}
	
	echo json_encode($objJson);
}


function tendencia() {
	global $mysqli;
			
	$agno = (int) date('Y');
	$mes = (int) date('m');  	
				
	$consulta = "
		select
			ano + (mes/100) as orden, 
			concat(mes, '/', ano) as periodo, 
			sum(realizados) / 1000 as realizados, 
			sum(informados) / 1000 as informados,
			sum(cancelados) / 1000 as cancelados,
			sum(agendados) / 1000 as agendados
		from datos 
		where ano + (mes/100) < 2017.1000
		group by periodo	
		order by orden desc limit 0,12";
	
	$result = $mysqli->query($consulta);
	
	$nbrows = $result->num_rows;	
	if($nbrows>0){
		$objJson = array();
		while($rec = $result->fetch_assoc()){
			$objJson[] = $rec; 
		}
		
		echo json_encode($objJson);
	} else {
		echo '';
	}
}

function tiempos() {
	global $mysqli;
			
	$agno = (int) date('Y');
	$mes = (int) date('m');  	
				
	$consulta = "
		select
			ano + (mes/100) as orden, 
			concat(mes, '/', ano) as periodo,
            avg(case when modalidad='Angiografía' then dias else NULL end) as Angiografia,
			avg(case when modalidad='Fluoroscopia' then dias else NULL end) as Fluoroscopia,
			avg(case when modalidad='Mamografía' then dias else NULL end) as Mamografia,
			avg(case when modalidad='Otros Estudios' then dias else NULL end) as Dental,
			avg(case when modalidad='Radiología Convencional' then dias else NULL end) as RadiologiaConvencional,
			avg(case when modalidad='Resonancia Magnetica' then dias else NULL end) as ResonanciaMagnetica,
			avg(case when modalidad='Tomografía Computada' then dias else NULL end) as TomografiaComputada,
			avg(case when modalidad='Ultrasonido' then dias else NULL end) as Ultrasonido
		from datos 
		where ano + (mes/100) < 2017.1000
		group by orden, periodo	
		order by orden desc 
		limit 0,48";
	
	$result = $mysqli->query($consulta);
	
	$nbrows = $result->num_rows;	
	if($nbrows>0){
		$objJson = array();
		while($rec = $result->fetch_assoc()){
			$objJson[] = $rec; 
		}
		
		echo json_encode($objJson);
	} else {
		echo '';
	}
}

function unidades(){
	global $mysqli;
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : '');
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : '');
	
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	
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
	
	$consulta = "SELECT codigo, unidad, 
					sum(agendados) as agendados,
					sum(cancelados) as cancelados,
					sum(realizados) as realizados,
					sum(informados) as informados
				FROM datos
				WHERE 1=1 $where ";
	
	if ($agno!="")
		$consulta .= "and ano in ($agno) ";
	else
		$consulta .= "and ano = ".date("Y")." ";
		
	if ($mes!="")
		$consulta .= "and mes in ($mes) ";
	
	$consulta .= "GROUP BY codigo, unidad ";
	 
	$result = $mysqli->query($consulta); 
	$count = $result->num_rows;
	
	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 1;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	
	$consulta .= " LIMIT ".$start.", ".$limit;
	$result = $mysqli->query($consulta);
	
	$response = new StdClass;
	
	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;
	$i=0;
	while($row = $result->fetch_assoc()){
		$response->rows[$i]['id']=$row['codigo'];
		$response->rows[$i]['cell']=array($row['codigo'],$row['unidad'],$row['realizados'],$row['informados']);
		$i++;
	}        
	echo json_encode($response);
}

function detalles(){
	global $mysqli;
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : date("Y"));
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : '');
	$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
	
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	
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
	
	$consulta = "SELECT modalidad, equipo, 
					sum(agendados) as agendados,
					sum(cancelados) as cancelados,
					sum(realizados) as realizados,
					sum(informados) as informados
				FROM datos
				WHERE codigo='$id' $where ";
	
	if ($agno!="")
		$consulta .= "and ano in ($agno) ";
	else
		$consulta .= "and ano = ".date("Y");
		
	if ($mes!="")
		$consulta .= "and mes in ($mes) ";
	
	$consulta .= "GROUP BY modalidad, equipo ";
	 
	$result = $mysqli->query($consulta); 
	$count = $result->num_rows;
	
	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 1;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	
	$consulta .= " LIMIT ".$start.", ".$limit;
	$result = $mysqli->query($consulta);
	
	$response = new StdClass;
	
	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;
	$i=0;
	while($row = $result->fetch_assoc()){
		$response->rows[$i]['id']=$i;
		$response->rows[$i]['cell']=array('',$row['modalidad'],$row['equipo'],$row['realizados'],$row['informados']);
		$i++;
	}        
	echo json_encode($response);
}

function detalles2(){
	global $mysqli;
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : date("Y"));
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : '');
	$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : date("Y"));
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	
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
	
	$consulta = "SELECT modalidad, 
					count(distinct equipo) as cantidad, 
					sum(agendados) as agendados,
					sum(realizados) as realizados,
					sum(informados) as informados
				FROM datos
				WHERE codigo='$id' $where ";
	
	if ($agno!="")
		$consulta .= "and ano in ($agno) ";
	else
		$consulta .= "and ano = ".date("Y")." ";
		
	if ($mes!="")
		$consulta .= "and mes in ($mes) ";
	
	$consulta .= "GROUP BY modalidad ";
	 
	$result = $mysqli->query($consulta); 
	$count = $result->num_rows;
	
	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 1;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	
	$consulta .= " LIMIT ".$start.", ".$limit;
	$result = $mysqli->query($consulta);
	
	$response = new StdClass;
	
	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;
	$i=0;
	while($row = $result->fetch_assoc()){
		$response->rows[$i]['id']=$i;
		$response->rows[$i]['cell']=array($row['modalidad'],$row['cantidad'],$row['agendados'],$row['realizados'],$row['informados']);
		$i++;
	}        
	echo json_encode($response);
}


function datos() {
	global $mysqli;
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : '');
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : '');
	
	$consulta = "Select ano, modalidad, count(distinct nombre) as total 
				 From tecnicos 
				 Where 1=1 ";
	
	if ($mes!="")
		$consulta .= "and mes in ($mes) ";
	
	//if ($agno!="")
	//	$consulta .= "and ano in ($agno) ";
	
	$consulta .= "GROUP BY ano, modalidad ";
		
	$consulta .= "
				UNION 
		   select ano, concat('Radiologo',modalidad) as modalidad, count(distinct nombre) as total from radiologos
		   where 1=1 ";
	
	if ($mes!="")
		$consulta .= "and mes in ($mes) ";
	
	//if ($agno!="")
	//	$consulta .= "and ano in ($agno) ";
	
	$consulta .= "GROUP BY ano, modalidad ";
	$consulta .= "ORDER BY ano desc, modalidad ";
	
	$result = $mysqli->query($consulta);
	
	$response = new StdClass;
	$rows = array();
	$i=0;
	while($row = $result->fetch_assoc()){
		$response->rows[$i]['ano']=$row['ano'];
		$response->rows[$i]['modalidad']=$row['modalidad'];
		$response->rows[$i]['valor']=$row['total'];
		$i++;
	}        
	echo json_encode($response);
}

function datosunidad() {
	global $mysqli;
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : date("Y"));
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : '');
	$unidad = $_REQUEST['unidad'];
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : date("Y"));
	
	$consulta = "
			SELECT *
			from personal
			where unidad = '$unidad' and ano = $agno
			order by ano desc, mes desc
			limit 0,1;
			 ";								
	
	$result = $mysqli->query($consulta);
	$consulta2 = "
			SELECT 
			count(distinct tipodia) as dias,
			count(distinct tipohora) as turno,
			sum(realizados) as realizados,
			sum(informados) as informados
			from datos
			where unidad = '$unidad' ";								
	
	if ($agno!="")
		$consulta2 .= "and ano in ($agno) ";
	else
		$consulta2 .= "and ano = ".date("Y");
	
	$consulta2 .= " limit 0,1;";
	$result2 = $mysqli->query($consulta2);
	
	$response = new StdClass;
	$rows = array();
	$i=0;
	if($row = $result->fetch_assoc()){
		$response->rows[$i]['codigo']=$row['codigo'];
		$response->rows[$i]['unidad']=$row['unidad'];
		$response->rows[$i]['tecnicos']=$row['tecnicos'];
		$response->rows[$i]['radiologos']=$row['radiologos'];
		$response->rows[$i]['teleradiologos']=$row['teleradiologos'];
	}
	if($row = $result2->fetch_assoc()){
		$response->rows[$i]['dias']=$row['dias'];
		$response->rows[$i]['turno']=$row['turno'];
		$response->rows[$i]['realizados']=$row['realizados'];
		$response->rows[$i]['informados']=$row['informados'];
	} 
	echo json_encode($response);
}

function comboEstatus(){
	global $mysqli;

	$query  = "SELECT id, nombre FROM maestro WHERE tipo = 'Estados' ";
	$result = $mysqli->query($query);

	$combo = "<select required='' name='estados' id='cmbestados' class='form-control'>";
	$combo .= "<option value=''> - </option>";
	while($row = $result->fetch_assoc()){
		$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
	}
	$combo .= "</select>";
	echo $combo;
}

function tecnicos(){
	global $mysqli;
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : date("Y"));
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : '');
	$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
	
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	
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
	
	$consulta = "SELECT unidad, nombre, tipodia, tipohora, modalidad,
					sum(estudios) as estudios,
					sum(valor) as valor
				FROM tecnicos
				WHERE 1=1 $where ";
	
	if ($agno!="")
		$consulta .= "and ano in ($agno) ";
	else
		$consulta .= "and ano = ".date("Y")." ";
	
	if ($id!="")
		$consulta .= "and codigo in ($id) ";
		
	if ($mes!="")
		$consulta .= "and mes in ($mes) ";
	
	$consulta .= "GROUP BY unidad, nombre, tipodia, tipohora, modalidad 
					ORDER BY unidad, nombre, modalidad, tipodia DESC, tipohora DESC ";
	 
	$result = $mysqli->query($consulta); 
	$count = $result->num_rows;
	
	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 1;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	
	$consulta .= " LIMIT ".$start.", ".$limit;
	$result = $mysqli->query($consulta);
	
	$response = new StdClass;
	
	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;
	$i=0;
	while($row = $result->fetch_assoc()){
		$response->rows[$i]['id']=$i;
		$response->rows[$i]['cell']=array($row['unidad'],$row['nombre'],$row['tipodia'],$row['tipohora'],$row['modalidad'],$row['estudios'],$row['valor']);
		$i++;
	}        
	echo json_encode($response);
}

function radiologos(){
	global $mysqli;
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : date("Y"));
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : '');
	$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
	
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	
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
	
	$consulta = "SELECT unidad, nombre, tipodia, tipohora, modalidad,
					sum(estudios) as estudios, avg(dias) as dias, sum(valor) as valor
				FROM radiologos
				WHERE 1=1 $where ";
	
	if ($agno!="")
		$consulta .= "and ano in ($agno) ";
		
	if ($id!="")
		$consulta .= "and codigo in ($id) ";
		
	if ($mes!="")
		$consulta .= "and mes in ($mes) ";
	
	$consulta .= "GROUP BY unidad, nombre, tipodia, tipohora, modalidad 
					ORDER BY unidad, nombre, modalidad, tipodia DESC, tipohora DESC ";
	debug($consulta); 
	$result = $mysqli->query($consulta); 
	$count = $result->num_rows;
	
	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 1;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	
	$consulta .= " LIMIT ".$start.", ".$limit;
	$result = $mysqli->query($consulta);
	
	$response = new StdClass;
	
	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;
	$i=0;
	while($row = $result->fetch_assoc()){
		$response->rows[$i]['id']=$i;
		$response->rows[$i]['cell']=array($row['unidad'],$row['nombre'],$row['tipodia'],$row['tipohora'],$row['modalidad'],$row['estudios'],$row['dias'],$row['valor']);
		$i++;
	}        
	echo json_encode($response);
}
?>