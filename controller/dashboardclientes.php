<?php
	include("../conexion.php");

	if (isset($_REQUEST['opcion'])) {
		$opcion = $_REQUEST['opcion'];
		
		if ($opcion=='MAPA')
			mapa();
		elseif ($opcion=='TIEMPOS')
			tiempos();
		elseif ($opcion=='ESTUDIOSMODALIDAD')
			estudiosmodalidad();
		elseif ($opcion=='UNIDADES')
			unidades();
		elseif ($opcion=='DETALLES')
			detalles();
		elseif ($opcion=='DETALLES2')
			detalles2();
		elseif ($opcion=='DATOS')
			datos();
		elseif ($opcion=='DATOS2')
			datos2();
		elseif ($opcion=='DATOSUNIDAD')
			datosunidad();
		elseif ($opcion=='COMBOESTATUS')
			comboEstatus();
		elseif ($opcion=='TECNICOS')
			tecnicos();
		elseif ($opcion=='RADIOLOGOS')
			radiologos();
		elseif ($opcion=='INCIDENTES')
			incidentes();
		elseif ($opcion=='TINCIDENTES')
			tincidentes();
		elseif ($opcion=='HISTORIAL')
			historial();
		elseif ($opcion=='AGENDAMIENTOS')
			agendamientos();
		elseif ($opcion=='TIEMPOSESPERA')
			tiemposEspera();
		elseif ($opcion=='PREVENTIVOS')
			preventivos();
		elseif ($opcion=='CORRECTIVOS')
			correctivos();
		elseif ($opcion=='CORRECTIVOS2')
			correctivos2();
		elseif ($opcion=='CORRECTIVOS3')
			correctivos3();
		elseif ($opcion=='CORRECTIVOS4')
			correctivos4();
		else
			return true;
	}

function mapa() {
	global $mysqli;
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : date("Y"));
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : date("m"));
	$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : 'CSS');
	$modalidad = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '*');
	
	$agno = (int) date('Y');
	$mes = (int) date('m');  
				
	$consulta = "
		select u.codigo as label, u.unidad as title, u.lat as latitud, u.lon as longitud, 9 as zoom, 
		case when u.unidad like '%Hospital%' then 'images/markerh.png' 
		else case when u.unidad like '%Poli%' then 'images/markerp.png' 
		else 'images/markeru.png' end end as icon,
		u.codigo as html
		from unidades u
		where 1 = 1 ";
		
	if ($provincia!="*")
		$consulta .= "and u.provincia = '$provincia' ";
	
	if ($unidad!="CSS")
		$consulta .= "and u.codigo = '$unidad' ";
	
	if ($modalidad!="*")
		$consulta .= "and d.modalidad = '$modalidad' ";
	
	$consulta .= "Group by u.unidad ";
	$result = $mysqli->query($consulta);
	
	$objJson = array();
	while($rec = $result->fetch_assoc()){
		$objJson[] = $rec; 
	}
	
	echo json_encode($objJson);
}

function tiempos() {
	global $mysqli;
			
	$agno = (int) date('Y');
	$mes = date('m');  	
				
	$consulta = "
		select
			ano + (mes/100) as orden, 
			concat(mes, '/', ano) as periodo,
			modalidad,
            sum(estudios*informe) / sum(estudios) as dias
		from tiempos 
		where ano + (mes/100) <= ".$agno.".".$mes."00
		group by orden, periodo, modalidad
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

function estudiosmodalidad() {
	global $mysqli;
			
	$agno = (int) date('Y');
	$mes = date('m');  	
				
	$consulta = "
		select
			ano + (mes/100) as orden, 
			concat(mes, '/', ano) as periodo,
			modalidad,
            sum(realizados) as estudios
		from datos 
		where ano + (mes/100) <= ".$agno.".".$mes."00
		group by orden, periodo, modalidad
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
	
	$agno = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : date("Y"));
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : 'CSS');
	$modalidad = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '*');
	
	$consulta = "
			SELECT ano, modalidad, disponibilidad as total 
			FROM disponibilidad
			WHERE ano<=$agno ";								
	
	/*
	if ($nivel>3)
		$consulta .= "and proyecto = $proyecto ";
	
	if ($desde!="")
		$consulta .= "and fechacreacion >= '$desde' ";
		
	$consulta .= "and fechacreacion <= '$hasta' ";
	*/
	/*
	if ($provincia!="*")
		$consulta .= "and provincia = '$provincia' ";
	
	if ($unidad!="CSS")
		$consulta .= "and codigo = '$unidad' ";
	
	if ($modalidad!="*")
		$consulta .= "and modalidad = '$modalidad' ";
		
	if ($mes!="")
		$consulta .= "and mes in ($mes) ";
	
	$consulta .= "GROUP BY ano, modalidad ";*/
	$consulta .= "ORDER BY ano desc ";
		 
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

function datos2() {
	global $mysqli;
	
	$desde = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '');
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	$sistema = (!empty($_REQUEST['sistema']) ? $_REQUEST['sistema'] : '');
	$tipo = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');
	$proyecto = $_SESSION['proyecto'];
	$nivel = $_SESSION['nivel'];
	$consulta = "
			select 
			    year(fechacreacion) as agno,
				count(id) as ordenes,
				sum(case when estado > 13 then 1 else 0 end) as finalizadas,
				sum(case when estado < 14 then 1 else 0 end) as pendientes,
				sum(case when idcategoria not in (12,22,35) then 1 else 0 end) as correctivas,
				sum(case when idcategoria not in (12,22,35) and estado > 13 then 1 else 0 end) as correctivasfin,
				sum(case when idcategoria not in (12,22,35) and estado < 14 then 1 else 0 end) as correctivaspen,
				sum(case when idcategoria in (12,22,35) then 1 else 0 end) as preventivas,
				sum(case when idcategoria in (12,22,35) and estado > 13 then 1 else 0 end) as preventivasfin,
				sum(case when idcategoria in (12,22,35) and estado < 14 then 1 else 0 end) as preventivaspen
			from incidentes 
			where 1=1 ";								
	
	if ($nivel>3)
		$consulta .= "and proyecto = $proyecto ";
	
	if ($desde!="")
		$consulta .= "and fechacreacion >= '$desde' ";
		
	$consulta .= "and fechacreacion <= '$hasta' 
				  group by year(fechacreacion) 
				  order by year(fechacreacion) desc ";
	
	$result = $mysqli->query($consulta);
	
	$count = $result->num_rows;
	if( $count >0 ) {
		$response = new StdClass;
		$i=0;
		while ($row = $result->fetch_assoc()) {
			$response->rows[$i]=$row;
			$i++;
		}
		echo json_encode($response);
	} else {
		echo '';
	}
}

function datosunidad() {
	global $mysqli;
	
	$desde = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '');
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : 'CSS');
	$modalidad = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '*');
	
	$consulta = "
			SELECT 
			p.codigo, p.unidad,
			sum(p.tecnicos) as tecnicos, 
			sum(p.radiologos) as radiologos, 
			sum(p.teleradiologos) as teleradiologos
			from personal p 
			where 1=1 ";
	
	if ($provincia!="*")
		$consulta .= "and p.provincia = '$provincia' ";
	
	if ($unidad!="CSS")
		$consulta .= "and p.codigo = '$unidad' group by p.codigo ";
	
	//if ($modalidad!="*")
	//	$consulta .= "and modalidad = '$modalidad' ";
	
	
	$consulta .= "
			order by p.codigo, p.ano desc, p.mes desc
			limit 0,1;
			 ";								
	
	$result = $mysqli->query($consulta);
	
	$consulta2 = "
			SELECT 
			count(distinct tipodia) as dias,
			count(distinct tipohora) as turno,
			sum(realizados) as realizados,
			sum(informados) as informados,
			sum(informadosl) as informadosl,
			sum(informadosr) as informadosr
			from datos 
			where 1=1 ";
			
	if ($unidad!="CSS")
		$consulta .= " and unidad = '$unidad' ";								
	
	if ($agno!="")
		$consulta2 .= "and ano in ($agno) ";
	else
		$consulta2 .= "and ano = ".date("Y");
	
	$consulta2 .= " limit 0,1;";
	$result2 = $mysqli->query($consulta2);
	
	$consulta3 = "SELECT
			sum(estudios*informe) / sum(estudios) as informe, 
			sum(estudios*atencion) / sum(estudios) as atencion 
			FROM tiempos
			where 1=1 ";
			
	if ($unidad!="CSS")
		$consulta .= " and unidad = '$unidad' ";								
	
	if ($agno!="")
		$consulta3 .= "and ano in ($agno) ";
	else
		$consulta3 .= "and ano = ".date("Y");
	
	$consulta3 .= " limit 0,1;";
	$result3 = $mysqli->query($consulta3);
	
	$response = new StdClass;
	$rows = array();
	$i=0; $id='';
	if($row = $result->fetch_assoc()){
		if ($unidad=="CSS") {
			$response->rows[$i]['codigo']='CSS';
			$response->rows[$i]['unidad']='Caja del Seguro Social';
		} else {
			$response->rows[$i]['codigo']=$row['codigo'];
			$response->rows[$i]['unidad']=$row['unidad'];
		}
		$response->rows[$i]['tecnicos']=$row['tecnicos'];
		$response->rows[$i]['radiologos']=$row['radiologos'];
		$response->rows[$i]['teleradiologos']=$row['teleradiologos'];
		$id = $row['codigo'];
	}
	if($row = $result2->fetch_assoc()){
		$response->rows[$i]['dias']=$row['dias'];
		$response->rows[$i]['turno']=$row['turno'];
		$response->rows[$i]['realizados']=$row['realizados'];
		$response->rows[$i]['informados']=$row['informados'];
		$response->rows[$i]['informadosl']=$row['informadosr'];
		$response->rows[$i]['informadosr']=$row['informadosl'];
	} 
	if($row = $result3->fetch_assoc()){
		$response->rows[$i]['atencion']=$row['atencion'];
		$response->rows[$i]['informe']=$row['informe'];
		$response->rows[$i]['total']=$row['atencion']+$row['informe'];
	} 
	
	$consulta4 = "SELECT modalidad, 
					count(distinct equipo) as cantidad, 
					sum(agendados) as agendados,
					sum(realizados) as realizados,
					sum(informados) as informados
				FROM datos
			where 1=1 ";
			
	if ($unidad!="CSS")
		$consulta .= " and codigo = '$unidad' ";								
	
	if ($agno!="")
		$consulta4 .= "and ano in ($agno) ";
	else
		$consulta4 .= "and ano = ".date("Y")." ";
		
	if ($mes!="")
		$consulta4 .= "and mes in ($mes) ";
	
	$consulta4 .= "GROUP BY modalidad ";
	 
	$result = $mysqli->query($consulta4); 
	$datos = array();
	while($row = $result->fetch_assoc()){
		$datos[]=$row;
	} 
	
	$response->rows[$i]['datos']=$datos;
		
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
	
	$desde = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '');
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : 'CSS');
	$modalidad = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '*');
	
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
	
	if ($provincia!="*")
		$consulta .= "and provincia = '$provincia' ";
	
	if ($unidad!="CSS")
		$consulta .= "and codigo = '$unidad' ";
	
	if ($modalidad!="*")
		$consulta .= "and modalidad = '$modalidad' ";
		
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
	
	$desde = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '');
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : 'CSS');
	$modalidad = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '*');
	
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
		
	if ($provincia!="*")
		$consulta .= "and provincia = '$provincia' ";
	
	if ($unidad!="CSS")
		$consulta .= "and codigo = '$unidad' ";
	
	if ($modalidad!="*")
		$consulta .= "and modalidad = '$modalidad' ";
	
		
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
		$response->rows[$i]['cell']=array($row['unidad'],$row['nombre'],$row['tipodia'],$row['tipohora'],$row['modalidad'],$row['estudios'],$row['dias'],$row['valor']);
		$i++;
	}        
	echo json_encode($response);
}

/*
CREATE OR REPLACE VIEW vwincidentes AS
select i.id, u.codigo as codigounidad, u.unidad, a.equipo, i.serie, a.marca, i.titulo, e.nombre as estado, ad.modalidad, ad.provincia, i.fechacreacion, i.fecharesolucion, year(i.fechacreacion) as agno, month(i.fechacreacion) as mes, c.nombre
from soporte.incidentes i
inner join soporte.unidades u on u.codigo = i.unidadejecutora
inner join soporte.activos a on a.codequipo = i.serie
inner join soporte.estados e on e.id = i.estado
inner join soporte.categorias c on c.id = i.idcategoria
inner join ododashboard.activos ad on ad.serial = i.serie
*/

function incidentes(){
	global $mysqli;
	
	$desde = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '');
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : 'CSS');
	$modalidad = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '*');
	
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
	
	$consulta = "SELECT a.unidad, a.equipo, a.codequipo as serie, a.marca, c.nombre, i.titulo, i.id, e.nombre as estado, i.fechacreacion
				FROM incidentes i
				INNER JOIN estados e ON e.id = i.estado
				INNER JOIN activos a ON a.codequipo  =i.serie
				INNER JOIN categorias c ON c.id = i.idcategoria
				INNER JOIN unidades u ON u.codigo = i.unidadejecutora
				WHERE i.estado < 14 and i.idcategoria in (10,11,13,16,17,19) and i.fechacreacion <= CURDATE() $where ";
	
	
	if ($agno!="")
		$consulta .= "and year(i.fechacreacion) in ($agno) ";
		
	if ($provincia!="*")
		$consulta .= "and u.provincia = '$provincia' ";
	
	if ($unidad!="CSS")
		$consulta .= "and i.unidadejecutora ='$unidad' ";
	
	if ($modalidad!="*")
		$consulta .= "and a.modalidad = '$modalidad' ";
			
	if ($mes!="")
		$consulta .= "and month(fechacreacion) in ($mes) ";
	
	$consulta .= "GROUP BY unidad, equipo, serie, marca, nombre, titulo, id, estado, fechacreacion ";
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
		$response->rows[$i]['cell']=array($row['unidad'],$row['equipo'],$row['serie'],$row['marca'],$row['nombre'],$row['titulo'],$row['id'],$row['estado'],$row['fechacreacion']);
		$i++;
	}        
	echo json_encode($response);
}

function tincidentes(){
	$mysqli = new mysqli("127.0.0.1", "root", "M4X14W3B", "soporte");
	if ($mysqli->connect_error) {
		echo "Fallo al conectar a MySQL: (" . $mysqli->connect_error . ") " . $mysqli->connect_error;
	}
	$mysqli->query("SET NAMES utf8"); 
	$mysqli->query("SET CHARACTER SET utf8");
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : date("Y"));
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : '');
	$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : 'CSS');
	$modalidad = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '*');
	
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
	
	$consulta = "SELECT u.unidad, a.equipo, a.codequipo as serie, a.marca, c.nombre, i.titulo, i.id, e.nombre as estado, i.fechacreacion
				FROM incidentes i
				INNER JOIN estados e ON e.id = i.estado
				INNER JOIN activos a ON a.codequipo  = i.serie
				INNER JOIN categorias c ON c.id = i.idcategoria
				INNER JOIN unidades u ON u.codigo = i.unidadejecutora
				WHERE i.proyecto = 1 and i.idcategoria <> 12 and i.idcategoria <> 22
				and i.fechacreacion <= CURDATE() $where ";
	
	
	if ($agno!="")
		$consulta .= "and year(i.fechacreacion) in ($agno) ";
		
	if ($provincia!="*")
		$consulta .= "and u.provincia = '$provincia' ";
	
	if ($unidad!="CSS")
		$consulta .= "and i.unidadejecutora ='$unidad' ";
	
	if ($modalidad!="*")
		$consulta .= "and a.modalidad = '$modalidad' ";
			
	if ($mes!="")
		$consulta .= "and month(fechacreacion) in ($mes) ";
	
	$consulta .= "GROUP BY unidad, equipo, serie, marca, nombre, titulo, id, estado, fechacreacion ";
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
		$response->rows[$i]['cell']=array($row['unidad'],$row['equipo'],$row['serie'],$row['marca'],$row['nombre'],$row['titulo'],$row['id'],$row['estado'],$row['fechacreacion']);
		$i++;
	}        
	echo json_encode($response);
}

function historial(){
	$mysqli = new mysqli("127.0.0.1", "root", "M4X14W3B", "soporte");
	if ($mysqli->connect_error) {
		echo "Fallo al conectar a MySQL: (" . $mysqli->connect_error . ") " . $mysqli->connect_error;
	}
	$mysqli->query("SET NAMES utf8"); 
	$mysqli->query("SET CHARACTER SET utf8");
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : '');
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : '');
	$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : 'CSS');
	$modalidad = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '*');
	
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
	
	$consulta = "SELECT a.unidad, a.equipo, a.codequipo as serie, a.marca, c.nombre, i.titulo, i.id, e.nombre as estado, i.fechacreacion, ifnull(i.fecharesolucion,'-') as fecharesolucion
				FROM incidentes i
				INNER JOIN estados e ON e.id = i.estado
				INNER JOIN activos a ON a.codequipo  =i.serie
				INNER JOIN categorias c ON c.id = i.idcategoria
				INNER JOIN unidades u ON u.codigo = i.unidadejecutora
				WHERE i.idcategoria in (10,11,12,13,16,17,19) and i.fechacreacion <= CURDATE() $where ";
	
	
	if ($agno!="")
		$consulta .= "and year(i.fechacreacion) in ($agno) ";
		
	if ($provincia!="*")
		$consulta .= "and u.provincia = '$provincia' ";
	
	if ($unidad!="CSS")
		$consulta .= "and i.unidadejecutora ='$unidad' ";
	
	if ($modalidad!="*")
		$consulta .= "and a.modalidad = '$modalidad' ";
			
	if ($mes!="")
		$consulta .= "and month(fechacreacion) in ($mes) ";
	
	$consulta .= "GROUP BY unidad, equipo, serie, marca, nombre, titulo, id, estado, fechacreacion ";
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
		$response->rows[$i]['cell']=array($row['unidad'],$row['equipo'],$row['serie'],$row['marca'],$row['nombre'],$row['titulo'],$row['id'],$row['estado'],$row['fechacreacion'],$row['fecharesolucion']);
		$i++;
	}        
	echo json_encode($response);
}

function agendamientos() {
	global $mysqli;
			
	$desde = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '');
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : 'CSS');
	$modalidad = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '*');
	
	
	$consulta = "
		SELECT
			ano + (mes/100) as orden, 
			concat(mes, '/', ano) as categoria,
			modalidad,
            sum(estudios) as estudios
		FROM agendamientos
		WHERE 1=1
		";							
	
	//if ($mes!="")
	//	$consulta .= "and mes in ($mes) ";
	
	if ($provincia!="*")
		$consulta .= "and provincia = '$provincia' ";
	
	if ($unidad!="CSS")
		$consulta .= "and codigo = '$unidad' ";
	
	if ($modalidad!="*")
		$consulta .= "and modalidad = '$modalidad' ";
	
	$consulta .= "
		group by orden, categoria, modalidad
		order by orden desc";
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

function tiemposEspera() {
	global $mysqli;
	
	$desde = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '');
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : 'CSS');
	$modalidad = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '*');
	
	$consulta = "
		select modalidad, sum(estudios) as estudios, sum(estudios*atencion) as atencion, sum(estudios*informe) as informes
			from tiempos
		where 1 = 1
		";
	if ($provincia!="*")
		$consulta .= "and provincia = '$provincia' ";
	
	if ($unidad!="CSS")
		$consulta .= "and codigo = '$unidad' ";
	
	if ($modalidad!="*")
		$consulta .= "and modalidad = '$modalidad' ";
	
	$consulta .= "
			group by modalidad";
	
	$result = $mysqli->query($consulta);
	
	$response = new StdClass;
	$rows = array();
	$i=0;
	while ($row = $result->fetch_assoc()){
		$rows[] = $row;
	}
	
	echo json_encode($rows);
}

function preventivos() {
	global $mysqli;
	
	$desde = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '');
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : 'CSS');
	$modalidad = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '*');
	$nivel = $_SESSION["nivel"];
	$proyecto = $_SESSION["proyecto"];
	
	$consulta = "
		SELECT case when a.casamedica='Maxia' then a.marca else a.casamedica end as prov,
		sum(case when i.idcategoria = 12 and i.estado > 15 then 1 else 0 end) as preventivasfin,
		sum(case when i.idcategoria = 12 and i.estado < 16 then 1 else 0 end) as preventivaspen
		FROM activos a
		inner join incidentes i on i.serie = a.codequipo  
		inner join unidades u on u.codigo = i.unidadejecutora
		where i.serie <> 'null' and i.idproyectos = 1 
		and i.idcategoria in (12,22,35,43) ";
	
	if ($nivel>3)
		$consulta .= "and i.proyecto = $proyecto ";
	
	if ($desde!="")
		$consulta .= "and i.fechacreacion >= '$desde' ";
		
	$consulta .= "and i.fechacreacion <= '$hasta' ";
	
	if ($provincia!="*")
		$consulta .= "and u.provincia = '$provincia' ";
	
	if ($unidad!="CSS")
		$consulta .= "and u.codigo = '$unidad' ";
	
	if ($modalidad!="*")
		$consulta .= "and a.modalidad = '$modalidad' ";
	
	$consulta .= "group by prov ";
	debug($consulta);
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

function correctivos() {
	global $mysqli;
			
	$desde = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '');
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : 'CSS');
	$modalidad = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '*');
	$nivel = $_SESSION["nivel"];
	$proyecto = $_SESSION["proyecto"];
	
	$consulta = "
		SELECT
			year(i.fechacreacion) + (month(i.fechacreacion)/100) as orden, 
			concat(month(i.fechacreacion), '/', year(i.fechacreacion)) as categoria,
			sum(case when i.idcategoria = 12 then 1 else 0 end) as preventivas,
			sum(case when i.idcategoria <> 12 then 1 else 0 end) as correctivas
		FROM incidentes i
		inner join activos a on i.serie = a.codequipo  
		inner join unidades u on u.codigo = i.unidadejecutora
		WHERE 1=1
		";							
	
	if ($nivel>3)
		$consulta .= "and i.proyecto = $proyecto ";
	
	if ($desde!="")
		$consulta .= "and i.fechacreacion >= '$desde' ";
		
	$consulta .= "and i.fechacreacion <= '$hasta' ";
	
	if ($provincia!="*")
		$consulta .= "and u.provincia = '$provincia' ";
	
	if ($unidad!="CSS")
		$consulta .= "and u.codigo = '$unidad' ";
	
	if ($modalidad!="*")
		$consulta .= "and a.modalidad = '$modalidad' ";
	
	$consulta .= "
		group by orden, categoria
		order by orden desc 
		limit 0,12 ";
	
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

function correctivos2() {
	global $mysqli;
			
	$desde = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '');
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : 'CSS');
	$modalidad = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '*');
	$nivel = $_SESSION["nivel"];
	$proyecto = $_SESSION["proyecto"];
		
	$consulta = "
		SELECT
			year(i.fechacreacion) + (month(i.fechacreacion)/100) as orden, 
			concat(month(i.fechacreacion), '/', year(i.fechacreacion)) as categoria,
			sum(case when i.idcategoria = 17 then 1 else 0 end) as consorcio,
            sum(case when i.idcategoria in (19,22,36) then 1 else 0 end) as otros,
			sum(case when i.idcategoria = 10 then 1 else 0 end) as correctivas,
            sum(case when i.idcategoria = 12 then 1 else 0 end) as preventivas,
            sum(case when i.idcategoria = 10 and (a.equipo like '%CR%' or a.equipo like '%DR%') then 1 else 0 end) as crdr,
		    sum(case when i.idcategoria = 10 and (a.equipo not like '%CR%' and a.equipo not like '%DR%') then 1 else 0 end) as diagnostico
		FROM incidentes i
		inner join activos a on i.serie = a.codequipo  
		inner join unidades u on u.codigo = i.unidadejecutora
		WHERE 1=1 
		";		
		//year(i.fechacreacion) + (month(i.fechacreacion)/100) <= ".$agno.".".$mes."00
	if ($nivel>3)
		$consulta .= "and i.proyecto = $proyecto ";
	
	if ($desde!="")
		$consulta .= "and i.fechacreacion >= '$desde' ";
		
	$consulta .= "and i.fechacreacion <= '$hasta' ";
	
	if ($provincia!="*")
		$consulta .= "and u.provincia = '$provincia' ";
	
	if ($unidad!="CSS")
		$consulta .= "and u.codigo = '$unidad' ";
	
	if ($modalidad!="*")
		$consulta .= "and a.modalidad = '$modalidad' ";
	
	$consulta .= "
		group by orden, categoria
		order by orden desc 
		limit 0,12 ";
	
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

function correctivos3() {
	global $mysqli;
	
	$agno = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : date("Y"));
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : 'CSS');
	$modalidad = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '*');
	$nivel = $_SESSION["nivel"];
	$proyecto = $_SESSION["proyecto"];
	
	$consulta = "
		select a.marca, 
			sum(case when i.idcategoria = 12 then 1 else 0 end) as preventivos,
			sum(case when i.idcategoria = 10 then 1 else 0 end) as correctivos,
			count(a.codequipo) as equipos
			from incidentes i 
			inner join activos a on a.codequipo = i.serie
			inner join unidades u on u.codigo = i.unidadejecutora		
		where year(i.fechacreacion) = $agno
		";
		
	if ($nivel>3)
		$consulta .= "and i.proyecto = $proyecto ";
	
	if ($desde!="")
		$consulta .= "and i.fechacreacion >= '$desde' ";
		
	$consulta .= "and i.fechacreacion <= '$hasta' ";
	
	if ($provincia!="*")
		$consulta .= "and u.provincia = '$provincia' ";
	
	if ($unidad!="CSS")
		$consulta .= "and u.codigo = '$unidad' ";
	
	if ($modalidad!="*")
		$consulta .= "and a.modalidad = '$modalidad' ";
	
	$consulta .= "
			group by a.marca ";
	$result = $mysqli->query($consulta);
	
	$response = new StdClass;
	$rows = array();
	$i=0;
	while ($row = $result->fetch_assoc()){
		$rows[] = $row;
	}
	
	echo json_encode($rows);
}

function correctivos4() {
	global $mysqli;
	
	$agno = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : date("Y"));
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : date("Ymd"));
	$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : 'CSS');
	$modalidad = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '*');
	$nivel = $_SESSION["nivel"];
	$proyecto = $_SESSION["proyecto"];
	
	$consulta = "
		select a.modalidad, 
			sum(case when i.idcategoria = 12 then 1 else 0 end) as preventivos,
			sum(case when i.idcategoria = 10 then 1 else 0 end) as correctivos,
			count(a.codequipo) as equipos
			from incidentes i 
			inner join activos a on a.codequipo = i.serie
			inner join unidades u on u.codigo = i.unidadejecutora		
		where year(i.fechacreacion) = $agno
		";
		
	if ($nivel>3)
		$consulta .= "and i.proyecto = $proyecto ";
	
	if ($provincia!="*")
		$consulta .= "and u.provincia = '$provincia' ";
	
	if ($unidad!="CSS")
		$consulta .= "and u.codigo = '$unidad' ";
	
	if ($modalidad!="*")
		$consulta .= "and a.modalidad = '$modalidad' ";
	
	$consulta .= "
			group by modalidad ";
	
	$result = $mysqli->query($consulta);
	
	$response = new StdClass;
	$rows = array();
	$i=0;
	while ($row = $result->fetch_assoc()){
		$rows[] = $row;
	}
	
	echo json_encode($rows);
}

?>