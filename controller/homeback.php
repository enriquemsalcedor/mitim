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
		elseif ($opcion=='DATOSUNIDADSOP')
			datosunidadsop();
		elseif ($opcion=='COMBOESTATUS')
			comboEstatus();
		elseif ($opcion=='TIEMPOSESPERA')
			tiemposEspera();
		elseif ($opcion=='TECNICOS')
			tecnicos();
		elseif ($opcion=='RADIOLOGOS')
			radiologos();
		elseif ($opcion=='PENDIENTES')
			pendientes();
		else
			return true;
	}

function mapa() {
	global $mysqli;
	
	$desde = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : "");
	$hasta = (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : "");
	$tipo =  (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : "");
	$provincia =  (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : "*");
	$modalidad =  (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : "*");
	
	$agno = (int) date('Y');
	$mes = (int) date('m');  
				
	$consulta = "
		select u.unidad as title, u.lat as latitud, u.lon as longitud, 9 as zoom, 
		case when u.unidad like '%Hospital%' then 'images/markerh.png' 
		else case when u.unidad like '%Poli%' then 'images/markerp.png' 
		else 'images/markeru.png' end end as icon,
		u.codigo as html
		from unidades u
		inner join (select codigo, modalidad from datos group by  codigo, modalidad) d on d.codigo = u.codigo
		where 1 = 1 ";
	if ($tipo!="")
		$consulta .= "and u.unidad like '%$tipo%' ";
	if ($provincia!="*")	
		$consulta .= "and u.provincia = '$provincia' ";
	if ($modalidad!="*") 
		$consulta .= "and d.modalidad like '%$modalidad%' ";
	
	$consulta .= "Group by u.unidad ";
	$result = $mysqli->query($consulta);
	
	$objJson = array();
	while($rec = $result->fetch_assoc()){
		$objJson[] = $rec; 
	}
	
	echo json_encode($objJson);
}


function tendencia() {
	global $mysqli;
			
	$agno = (int) date('Y');
	$mes = date('m');  	
				
	$consulta = "
		select
			ano + (mes/100) as orden, 
			concat(mes, '/', ano) as periodo, 
			sum(realizados) / 1000 as realizados, 
			sum(informados) / 1000 as informados,
			sum(cancelados) / 1000 as cancelados,
			sum(agendados) / 1000 as agendados
		from datos 
		where ano + (mes/100) <= ".$agno.".".$mes."00
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
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : date("Y"));
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : '');
	$unidad = (!empty($_REQUEST['unidad']) ? $_REQUEST['unidad'] : '');
	
	$consulta = "
			SELECT ano, modalidad, sum(realizados) as total 
			FROM datos
			WHERE ano<=$agno ";								
	
	if ($mes!="")
		$consulta .= "and mes in ($mes) ";
	
	if ($unidad!="CSS")
		$consulta .= "and unidad = '$unidad' ";
	
	$consulta .= "GROUP BY ano, modalidad ";
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

function datosunidad() {
	global $mysqli;
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : "2017");
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : '');
	$unidad = $_REQUEST['unidad'];
	
	if ($unidad == 'CSS') {
		$consulta = "
				SELECT *
				from personal
				where ano = $agno
				order by ano desc, mes desc
				limit 0,1;
				 ";								
	} else {
		$consulta = "
				SELECT *
				from personal
				where codigo = '$unidad' and ano = $agno
				order by ano desc, mes desc
				limit 0,1;
				 ";
	}
	$result = $mysqli->query($consulta);
	
	if ($unidad == 'CSS') {
		$consulta2 = "
				SELECT 
				count(distinct tipodia) as dias,
				count(distinct tipohora) as turno,
				sum(realizados) as realizados,
				sum(informados) as informados,
				sum(informadosl) as informadosl,
				sum(informadosr) as informadosr
				from datos
				where 1 = 1 ";								
	} else {
		$consulta2 = "
				SELECT 
				count(distinct tipodia) as dias,
				count(distinct tipohora) as turno,
				sum(realizados) as realizados,
				sum(informados) as informados,
				sum(informadosl) as informadosl,
				sum(informadosr) as informadosr
				from datos
				where codigo = '$unidad' ";	
	}

	if ($agno!="")
		$consulta2 .= "and ano in ($agno) ";
	else
		$consulta2 .= "and ano = ".date("Y");
	
	$consulta2 .= " limit 0,1;";
	
	$result2 = $mysqli->query($consulta2);
	
	
	if ($unidad == 'CSS') {
		$consulta3 = "SELECT
				sum(estudios*informe) / sum(estudios) as informe, 
				sum(estudios*atencion) / sum(estudios) as atencion 
				FROM tiempos
				where 1 = 1 ";
	} else {
		$consulta3 = "SELECT
				sum(estudios*informe) / sum(estudios) as informe, 
				sum(estudios*atencion) / sum(estudios) as atencion 
				FROM tiempos
				where codigo = '$unidad' ";
	}
	
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
		if ($unidad == 'CSS') {
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
		$response->rows[$i]['atencion']=number_format($row['atencion'],1);
		$response->rows[$i]['informe']=number_format($row['informe'],1);
		$response->rows[$i]['total']=number_format($row['atencion']+$row['informe'],1);
	} 
	
	if ($unidad == 'CSS') {
		$consulta4 = "SELECT modalidad, 
						count(distinct equipo) as cantidad, 
						sum(agendados) as agendados,
						sum(realizados) as realizados,
						sum(informados) as informados
					FROM datos
					WHERE 1=1 ";
	} else {
		$consulta4 = "SELECT modalidad, 
						count(distinct equipo) as cantidad, 
						sum(agendados) as agendados,
						sum(realizados) as realizados,
						sum(informados) as informados
					FROM datos
					WHERE codigo = '$unidad' ";
	}
	
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


function datosunidadsop() {
	$mysqli = new mysqli("127.0.0.1", "root", "M4X14W3B", "soporte");
	if ($mysqli->connect_error) {
		echo "Fallo al conectar a MySQL: (" . $mysqli->connect_error . ") " . $mysqli->connect_error;
	}

	$mysqli->query("SET NAMES utf8"); 
	$mysqli->query("SET CHARACTER SET utf8"); 
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : date("Y"));
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : '');
	$unidad = $_REQUEST['unidad'];
	
	if ($unidad == 'CSS') {
		$consulta = "
				SELECT u.codigo, u.unidad,
				sum(case when i.idcategoria = 10 then 1 else 0 end) as correctivas,
				sum(case when i.idcategoria = 10 and i.estado > 15 then 1 else 0 end) as correctivasfin,
				sum(case when i.idcategoria = 10 and i.estado < 16 then 1 else 0 end) as correctivaspen,
				sum(case when i.idcategoria = 12 then 1 else 0 end) as preventivas,
				sum(case when i.idcategoria = 12 and i.estado > 15 then 1 else 0 end) as preventivasfin,
				sum(case when i.idcategoria = 12 and i.estado < 16 then 1 else 0 end) as preventivaspen
				from unidades u
				inner join incidentes i on i.unidadejecutora = u.codigo
				where 1 = 1
				 ";	
				 
		if ($agno!="")
			$consulta .= "and year(i.fechacreacion) in ($agno) ";
		else
			$consulta .= "and year(i.fechacreacion) = ".date("Y")." ";
			
		if ($mes!="")
			$consulta .= "and month(i.fechacreacion) in ($mes) ";
		
		$consulta .= "limit 0,1;";
	} else {
		$consulta = "
			SELECT u.codigo, u.unidad,
			sum(case when i.idcategoria = 10 then 1 else 0 end) as correctivas,
			sum(case when i.idcategoria = 10 and i.estado > 15 then 1 else 0 end) as correctivasfin,
			sum(case when i.idcategoria = 10 and i.estado < 16 then 1 else 0 end) as correctivaspen,
			sum(case when i.idcategoria = 12 then 1 else 0 end) as preventivas,
			sum(case when i.idcategoria = 12 and i.estado > 15 then 1 else 0 end) as preventivasfin,
			sum(case when i.idcategoria = 12 and i.estado < 16 then 1 else 0 end) as preventivaspen
			from unidades u
			inner join incidentes i on i.unidadejecutora = u.codigo
			where u.codigo = '$unidad'
			";
		if ($agno!="")
			$consulta .= "and year(i.fechacreacion) in ($agno) ";
		else
			$consulta .= "and year(i.fechacreacion) = ".date("Y")." ";
			
		if ($mes!="")
			$consulta .= "and month(i.fechacreacion) in ($mes) ";
		
		$consulta .= "group by u.codigo, u.unidad
			limit 0,1;";
	}
	
	$result = $mysqli->query($consulta);

	$response = new StdClass;
	$rows = array();
	$i=0; $id='';
	if($row = $result->fetch_assoc()){
		$response->rows[$i]['codigo']=$row['codigo'];
		$response->rows[$i]['unidad']=$row['unidad'];
		$response->rows[$i]['correctivas']=$row['correctivas'];
		$response->rows[$i]['correctivasfin']=$row['correctivasfin'];
		$response->rows[$i]['correctivaspen']=$row['correctivaspen'];
		$response->rows[$i]['preventivas']=$row['preventivas'];
		$response->rows[$i]['preventivasfin']=$row['preventivasfin'];
		$response->rows[$i]['preventivaspen']=$row['preventivaspen'];
		$id = $row['codigo'];
	}
	
	if ($unidad == 'CSS') {
		$consulta4 = "SELECT a.modalidad, a.equipo, a.estado,
						count(DISTINCT a.codequipo) as equipos,
						sum(case when i.idcategoria = 12 and i.estado > 15 then 1 else 0 end) as preventivos,
						sum(case when i.idcategoria <> 12 and i.estado > 15 then 1 else 0 end) as correctivos
					FROM activos a
					inner join incidentes i on i.serie = a.codequipo  
					where i.serie <> 'null' and 1=1 ";
	} else {
		$consulta4 = "SELECT a.modalidad, a.equipo, a.estado,
						count(DISTINCT a.codequipo) as equipos,
						sum(case when i.idcategoria = 12 and i.estado > 15 then 1 else 0 end) as preventivos,
						sum(case when i.idcategoria <> 12 and i.estado > 15 then 1 else 0 end) as correctivos
					FROM activos a
					inner join incidentes i on i.serie = a.codequipo  
					where i.serie <> 'null' and a.codigound='$unidad' ";
	}
	if ($agno!="")
		$consulta4 .= "and year(i.fechacreacion) in ($agno) ";
	else
		$consulta4 .= "and year(i.fechacreacion) = ".date("Y")." ";
		
	if ($mes!="")
		$consulta4 .= "and month(i.fechacreacion) in ($mes) ";
	$consulta4 .= "GROUP BY a.modalidad, a.equipo ";
	debug($consulta4);
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

function tiemposEspera() {
	global $mysqli;
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : date('Y'));
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : '');
	$modalidad =  (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : "");
	$tipo =  (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : "");
	
	$consulta = "
		select modalidad, sum(estudios) as estudios, sum(estudios*atencion) as atencion, sum(estudios*informe) as informes
			from tiempos
		where 1 = 1
		";
	if ($modalidad!="*") 
		$consulta .= "and modalidad like '%$modalidad%' ";
	if ($agno!="")
		$consulta .= "and ano like '%$agno%' ";
	if ($mes!="")	
		$consulta .= "and mes like '%$mes%' ";
	if ($tipo!="")
		$consulta .= "and unidad like '%$tipo%' ";
	
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
	
	if ($id!="CSS")
		$consulta .= "and unidad = '$unidad' ";
		
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
		
	if ($id!="CSS")
		$consulta .= "and unidad = '$unidad' ";
		
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

function pendientes(){
	$mysqli = new mysqli("127.0.0.1", "root", "M4X14W3B", "soporte");
	if ($mysqli->connect_error) {
		echo "Fallo al conectar a MySQL: (" . $mysqli->connect_error . ") " . $mysqli->connect_error;
	}

	$mysqli->query("SET NAMES utf8"); 
	$mysqli->query("SET CHARACTER SET utf8"); 
	
	
	$agno = (!empty($_REQUEST['agno']) ? $_REQUEST['agno'] : '');
	$mes = (!empty($_REQUEST['mes']) ? $_REQUEST['mes'] : '');
	$id = $_REQUEST['id'];
	
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
	
	$consulta = "SELECT i.id, i.titulo, i.descripcion, e.nombre as estado, i.asignadoa, i.fechacreacion
				FROM incidentes i
				INNER JOIN estados e ON e.id = i.estado
				WHERE i.unidadejecutora='$id' and i.estado < 14 and i.idcategoria not in (12,22,35) and year(i.fechacreacion) = 2018 and i.fechacreacion <= CURDATE() $where ";
	
	if ($agno!="")
		$consulta .= "and year(i.fechacreacion) in ($agno) ";
		
	if ($mes!="")
		$consulta .= "and month(i.fechacreacion) in ($mes) ";
	
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
		$response->rows[$i]['id']=$row['id'];
		$response->rows[$i]['cell']=array($row['id'],$row['titulo'],$row['descripcion'],$row['estado'],$row['asignadoa'],$row['fechacreacion']);
		$i++;
	}        
	echo json_encode($response);
}

?>