<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "unidades":
	  		  unidades();
	  		  break;
		case "unidadesclientes":
	  		  unidadesclientes();
	  		  break;
		case "sitiosclientes":
	  		  sitiosclientes();
	  		  break;
		case "categorias":
	  		  categorias();
	  		  break;
		case "subcategorias":
	  		  subcategorias();
	  		  break;
		case "prioridades":
	  		  prioridades();
	  		  break;
		case "sitios":
	  		  sitios();
	  		  break;
		case "empresas":
	  		  empresas();
	  		  break;
		case "clientes":
	  		  clientes();
	  		  break;
		case "proyectos":
	  		  proyectos();
	  		  break;
		case "proyectosrel":
			  proyectosrel();
	  		  break;
		case "departamentos":
	  		  departamentos();
	  		  break;
		case "departamentosgrupos":
	  		  departamentosgrupos();
	  		  break;
		case "grupos":
	  		  grupos();
	  		  break;
		case "marcas":
	  		  marcas();
	  		  break;
		case "modalidades":
	  		  modalidades();
	  		  break;
		case "modelos":
	  		  modelos();
	  		  break;
		case "casasmedicas":
	  		  casasmedicas();
	  		  break;
		case "estados":
	  		  estados();
	  		  break;
		case "niveles":
	  		  niveles();
	  		  break;
		case "usuarios":
	  		  usuarios();
	  		  break;
		case "serie":
	  		  serie();
	  		  break;
		case "seriesel":
	  		  seriesel();
	  		  break;			  
		case "seriesincidentes":
	  		  seriesincidentes();
	  		  break;
		case "usuariosGrupos":
	  		  usuariosGrupos();
	  		  break;
		case "usuariosDep":
	  		  usuariosDep();
	  		  break;
		case "incidentes":
	  		  incidentes();
	  		  break;
		case "preventivos":
	  		  preventivos();
	  		  break;
	  		  break;
		case "clientes":
	  		  clientes();
	  		  break;
	    case "cuatrimestres":
	  		  cuatrimestres();
	  		  break;
		default:
			  echo "{failure:true}";
			  break;
	}
	
	function unidades()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		
		$query  = " SELECT codigo, unidad FROM unidades WHERE 1 = 1 ORDER BY unidad ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''>  </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['codigo']."'>".$row['unidad']."</option>";
		}		
		echo $combo;
	}
	
	function unidadesclientes()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['unidadact'])){ $unidadact = $_REQUEST['unidadact']; }else{ $unidadact = ''; }
		
		$query  = " SELECT DISTINCT(a.sitio) as codigo, b.unidad 
					FROM usuarios a 
					LEFT JOIN unidades b ON a.sitio = b.codigo
					WHERE idclientes = (SELECT idclientes FROM usuarios where sitio = '$unidadact' GROUP BY idclientes)
					AND b.unidad IS NOT null AND a.sitio != '$unidadact' ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''>  </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['codigo']."'>".$row['unidad']."</option>";
		}		
		echo $combo;
	}
	
	function prioridades()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		if ($_SESSION['nivel'] < 4) {
			$query  = " SELECT id, prioridad FROM sla WHERE 1 = 1 ORDER BY prioridad ASC ";
		} else {
			$query  = " SELECT id, prioridad FROM sla WHERE id < 6 ORDER BY prioridad ASC ";
		}
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''>  </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['prioridad']."</option>";
		}
		echo $combo;
	}
	
	function sitios()
	{
		global $mysqli;		
		
		$query  = " SELECT id, nombre FROM sitios WHERE 1 = 1 ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}
	
	function empresas(){
		global $mysqli;
		$combo 		= '';
		$idempresas = $_SESSION['idempresas'];
		$nivel 		= $_SESSION['nivel'];
		
		$query  = " SELECT id, descripcion FROM empresas WHERE 1 = 1 ";		
		if($idempresas !="" && ($nivel != 1 || $nivel != 2) ){ 
		    $query .= " AND id = 1 ";
		}
		$query  .= " ORDER BY descripcion ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){ 
			$combo .= "<option value='".$row['id']."'>".$row['descripcion']."</option>";
		}	
		echo $combo;  
	}
	
	function departamentos(){
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['idempresas'])){ $idempresas = $_REQUEST['idempresas']; }else{ $idempresas = ''; }
		$idempresas = 1;
		
		//DEPARTAMENTOS
		$query  = " SELECT id, nombre FROM departamentos WHERE 1 = 1 ";		
		if($idempresas !=""){
		    $query .= " AND idempresas in ($idempresas)";
		}
		$query .= " AND tipo = 'departamento' ";
		$query  .= " ORDER BY nombre ASC ";		
		$result = $mysqli->query($query);	
		$combo .= "<option value='0'> Seleccione </option>";
		while($row = $result->fetch_assoc()){ 
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		
		echo $combo;
	}
	
	function grupos(){
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['idempresas'])){ $idempresas = $_REQUEST['idempresas']; }else{ $idempresas = ''; }
		$idempresas = 1;
		
		//GRUPOS
		//$combo .= "<optgroup label='GRUPOS'>";
		$query  = " SELECT id, nombre FROM departamentos WHERE 1 = 1 ";		
		if($idempresas !=""){
		    $query .= " AND idempresas in ($idempresas)";
		}
		$query .= " AND tipo = 'grupo' ";
		$query  .= " ORDER BY nombre ASC ";		
		$result = $mysqli->query($query);	
		//$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){ 
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		
		echo $combo;
	}
	
	function departamentosgrupos(){
		global $mysqli;
		$nivel 		= $_SESSION['nivel'];
		$usuario 	= $_SESSION['usuario'];
		$combo 		= '';
		if(isset($_REQUEST['idempresas'])){ $idempresas = $_REQUEST['idempresas']; }else{ $idempresas = ''; }
		$idempresas = 1;
		
		if($nivel == 1 || $nivel == 2){
			//DEPARTAMENTOS
			$combo .= "<option value='0'> - </option>";			
			$query  = " SELECT id, nombre FROM departamentos WHERE 1 = 1 ";		
			if($idempresas !=""){
				$query .= " AND idempresas in ($idempresas)";
			}
			$query .= " AND tipo = 'departamento' ";
			$query  .= " ORDER BY nombre ASC ";		
			$result = $mysqli->query($query);
			if($result->num_rows > 0){
				$combo .= "<optgroup label='DEPARTAMENTOS'>";
				while($row = $result->fetch_assoc()){ 
					$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
				}
				$combo .= "</optgroup>";
			}
			
			//GRUPOS			
			$query  = " SELECT id, nombre FROM departamentos WHERE 1 = 1 ";		
			if($idempresas !=""){
				$query .= " AND idempresas in ($idempresas)";
			}
			$query .= " AND tipo = 'grupo' ";
			$query  .= " ORDER BY nombre ASC ";		
			$result = $mysqli->query($query);
			if($result->num_rows > 0){
				$combo .= "<optgroup label='GRUPOS'>";
				while($row = $result->fetch_assoc()){ 
					$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
				}
				$combo .= "</optgroup>";
			}
		}else{
			//DEPARTAMENTOS
			$combo .= "<option value='0'> - </option>";
			$query  = " SELECT a.id, a.nombre 
						FROM departamentos a
						LEFT JOIN usuarios b ON find_in_set(a.id, b.iddepartamentos)					
						WHERE usuario = '$usuario' ";		
			if($idempresas !=""){
				$query .= " AND find_in_set(a.idempresas, $idempresas) ";
			}
			$query .= " AND a.tipo = 'departamento' ";
			$query  .= " ORDER BY a.nombre ASC ";
			//debug($query);
			$result = $mysqli->query($query);
			if($result->num_rows > 0){
				$combo .= "<optgroup label='DEPARTAMENTOS'>";				
				while($row = $result->fetch_assoc()){ 
					$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
				}
				$combo .= "</optgroup>";
			}
			//GRUPOS			
			$query  = " SELECT a.id, a.nombre 
						FROM departamentos a
						LEFT JOIN usuarios b ON find_in_set(a.id, b.iddepartamentos)					
						WHERE usuario = '$usuario' ";		
			if($idempresas !=""){
				$query .= " AND find_in_set(a.idempresas, $idempresas) ";
			}
			$query .= " AND a.tipo = 'grupo' ";
			$query  .= " ORDER BY a.nombre ASC ";
			//debug($query);
			$result = $mysqli->query($query);
			if($result->num_rows > 0){
				$combo .= "<optgroup label='GRUPOS'>";
				while($row = $result->fetch_assoc()){ 
					$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
				}
				$combo .= "</optgroup>";
			}
			
		}
		
		echo $combo;
	}
	
	function clientes(){
		global $mysqli;
		$combo	= '';
		$nivel 	= $_SESSION['nivel'];
		$usuario = $_SESSION['usuario'];
		if(isset($_REQUEST['idempresas'])){ $idempresas = $_REQUEST['idempresas']; }else{ $idempresas = '1'; }
		
		$query  = " SELECT a.id, a.nombre FROM clientes a ";
		if($nivel != 1 && $nivel != 2){
			$query .= " LEFT JOIN usuarios b ON find_in_set(a.id, b.idclientes)
						WHERE b.usuario = '$usuario' ";
		}else{
			$query .= " WHERE 1 = 1 ";
		}
		if($idempresas != ""){ 
		    $query .= " AND find_in_set($idempresas, a.idempresas) ";
		}
		$query  .= " ORDER BY a.nombre ASC ";		
		$result = $mysqli->query($query);
		//debug($query);
		$combo .= "<option value='0'> Seleccione </option>";
		while($row = $result->fetch_assoc()){ 
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;  
	}
	
	function proyectos(){
		global $mysqli;
		$combo 	= '';
		$nivel 	= $_SESSION['nivel'];
		$usuario = $_SESSION['usuario'];
		if(isset($_REQUEST['idclientes'])){ $idclientes = $_REQUEST['idclientes']; }else{ $idclientes = ''; }
		
		$query  = " SELECT a.id, a.nombre FROM proyectos a ";
		if($nivel != 1 && $nivel != 2){
			$query .= " LEFT JOIN usuarios b ON find_in_set(a.id, b.idproyectos)
						WHERE b.usuario = '$usuario' ";
		}else{
			$query .= " WHERE 1 = 1 ";
		}
		if($idclientes != ""){
			$query .= " AND find_in_set($idclientes, a.idclientes) ";			 
		}		
		$query  .= " ORDER BY a.nombre ASC ";
		$result = $mysqli->query($query);
		//debug($query);
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;  
	}
	
	function sitiosclientes()
	{
		global $mysqli;
		$combo 	= '';
		$nivel 	= $_SESSION['nivel'];
		$usuario = $_SESSION['usuario'];
		if(isset($_REQUEST['idclientes'])){ $idclientes = $_REQUEST['idclientes']; }else{ $idclientes = ''; }
		
		$query  = " SELECT a.codigo, a.unidad FROM unidades a ";
		if($nivel != 1 && $nivel != 2){
			$query .= " LEFT JOIN usuarios b ON find_in_set(a.codigo, b.sitio)
						WHERE b.usuario = '$usuario' ";
		}else{
			$query .= " LEFT JOIN usuarios b ON find_in_set(a.codigo, b.sitio)
						WHERE 1 = 1 ";
		}
		if($idclientes != ''){
			$query .= " AND find_in_set($idclientes,b.idclientes) ";
		}
		$query .= " GROUP BY a.codigo ORDER BY a.unidad ";
		$result = $mysqli->query($query);
		//debug($query);
		$combo .= "<option value=''>  </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['codigo']."'>".$row['unidad']."</option>";
		}		
		echo $combo;
	}
	
	function proyectosrel(){
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['idclientes'])){ $idclientes = $_REQUEST['idclientes']; }else{ $idclientes = ''; }
		
		$query  = " SELECT a.id, a.nombre, b.siglas as cliente 
					FROM proyectos a 
					LEFT JOIN clientes b ON a.idclientes = b.id 
					WHERE 1 = 1 ";		
		if($idclientes !=""){ 
		     $query .= " AND a.idclientes in ($idclientes) ";   
		}		
		$query  .= " ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']." - ".$row['cliente']."</option>";
		}
		echo $combo;  
	}
	function categorias()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['tipo'])){ $tipo = $_REQUEST['tipo']; }else{ $tipo = ''; }
		if(isset($_REQUEST['idproyectos'])){ $idproyectos = $_REQUEST['idproyectos']; }else{ $idproyectos = ''; }
		
		$query  = " SELECT id, nombre FROM categorias WHERE 1 = 1 ";	
		if($idproyectos != '' && $idproyectos != 'undefined'){
			$query  .= " AND idproyecto in ($idproyectos) ";
		}
		if($tipo != ''){
			if($tipo == 'incidente'){
				$query  .= " AND id not in (12,22,35,43)  ";
			}else{
				$query  .= " AND id in (12,22,35,43)  ";
			}
		}
		$query  .= " ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;  
	}
	
	function subcategorias()
	{
		global $mysqli;	
		$combo = '';
		if(isset($_REQUEST['tipo'])){ $tipo = $_REQUEST['tipo']; }else{ $tipo = ''; }
		if(isset($_REQUEST['idcategoria'])){ $idcategoria = $_REQUEST['idcategoria']; }else{ $idcategoria = ''; }
		
		$query  = " SELECT id, nombre FROM subcategorias WHERE 1 = 1 ";
		
		if($idcategoria != ''){
			$idcategoria = str_replace(["[", "]"], "", $_REQUEST['idcategoria']);
			$query  .= " AND idcategoria IN ($idcategoria) ";
		}
		$query  .= " ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;
		
	}
	
	function marcas()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		
		$query  = " SELECT DISTINCT marca as nombre FROM activos WHERE 1 = 1 ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['nombre']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}
	
	function modalidades()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		
		$query  = " SELECT DISTINCT modalidad as nombre FROM activos WHERE 1 = 1 ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['nombre']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}
	
	function modelos()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		
		$query  = " SELECT id, nombre FROM modelos WHERE 1 = 1 ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['nombre']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}
	
	function casasmedicas()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		
		$query  = " SELECT id, nombre FROM casasmedicas WHERE 1 = 1 ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['nombre']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}
	
	function estados()
	{
		global $mysqli;
		$tipo = $_REQUEST['tipo'];
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		
		$query  = " SELECT id, nombre FROM estados WHERE 1 = 1 AND tipo = '$tipo' AND id <> 17 ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;
		
	}
	
	function niveles()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		
		$query  = " SELECT id, nombre FROM niveles WHERE 1 = 1 ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}	
		echo $combo;
	}
	
	function usuarios()
	{
		global $mysqli;
		$nivel = (!empty($_REQUEST['nivel']) ? $_REQUEST['nivel'] : '');
		$combo 	= '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		
		$query  = " SELECT a.correo, a.nombre FROM usuarios a INNER JOIN niveles b ON a.nivel = b.id WHERE 1 = 1 ";
		if($nivel !=''){
			$query  .=" AND a.nivel IN ($nivel) ";
		}
		$query  .=" ORDER BY a.nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['correo']."'>".$row['nombre']."</option>";
		}	
		echo $combo;
	}
	
	function serie()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		if(isset($_REQUEST['idsitio'])){ $idsitio = $_REQUEST['idsitio']; }else{ $idsitio = ''; }
		
		if($idsitio != ''){
			$query  = " SELECT DISTINCT(codequipo), equipo FROM activos WHERE 1 = 1 AND codequipo != '' AND codigound = '$idsitio' ORDER BY codequipo ASC ";
		}else{
			$query  = " SELECT DISTINCT(codequipo), equipo FROM activos WHERE 1 = 1 AND codequipo != '' ORDER BY codequipo ASC ";
		}
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['codequipo']."'>".$row['codequipo']." - ".$row['equipo']."</option>";
		}		
		echo $combo;
	}
	
	function seriesel()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		if(isset($_REQUEST['idserie'])){ $idserie = $_REQUEST['idserie']; }else{ $idserie = ''; }
		$resultado = '';
		
		if($idserie != ''){
			$query  = " SELECT marca, modelo FROM activos WHERE 1 = 1 AND codequipo = '$idserie' ";
			$result = $mysqli->query($query);
			
			while($row = $result->fetch_assoc()){
				$resultado[] = array('marca' => $row['marca'], 'modelo' => $row['modelo']);
			}
		}else{
			$resultado[] = array('marca' => '', 'modelo' => '');
		}
		
		echo json_encode($resultado);
	}
	
	function seriesincidentes()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		
		$query  = " SELECT DISTINCT(serie) FROM incidentes WHERE 1 = 1 AND serie != '' ORDER BY serie ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['serie']."'>".$row['serie']."</option>";
		}		
		echo $combo;
	}
	
	function usuariosGrupos()
	{
		global $mysqli;
		$combo 	= '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		$nivel = (!empty($_REQUEST['nivel']) ?$_REQUEST['nivel'] : '');
		
		$query  = " SELECT 0 AS id, ' - RESPONSABLES - ' AS nombre
					UNION 
					SELECT CONCAT(a.correo, '') AS id, a.nombre FROM usuarios a
					INNER JOIN niveles b ON a.nivel = b.id  ";
		if($nivel !=''){
			$query  .=" WHERE a.nivel IN ($nivel) ";
		}
		
		$query  .="
					UNION
					SELECT 0 AS id, ' - GRUPOS DE RESPONSABLES - ' AS nombre
					UNION 
					SELECT GROUP_CONCAT('\"', correo, '\"') AS id, a.nombre  
					FROM grupos a
					INNER JOIN gruposusuarios b ON a.id = b.idgrupo
					INNER JOIN usuarios c ON b.idusuario = c.id
					GROUP BY a.id ";
				
		$result = $mysqli->query($query);		
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}	
		echo $combo;
	}
	
	function usuariosDep()
	{
		global $mysqli;
		$combo 	= '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		$nivel = (!empty($_REQUEST['nivel']) ?$_REQUEST['nivel'] : '');
		$iddepartamentos = (!empty($_REQUEST['iddepartamentos']) ?$_REQUEST['iddepartamentos'] : '');
		if (is_array($iddepartamentos))
		$iddepartamentos = implode(',',$iddepartamentos);
		
		$query  = " SELECT id, correo, nombre FROM usuarios WHERE 1 = 1  ";
		//if($iddepartamentos !=''){
			$query  .=" AND find_in_set($iddepartamentos,iddepartamentos) ";
		//}
		$result = $mysqli->query($query);		
		//debug($iddepartamentos);
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['correo']."'>".$row['nombre']."</option>";
		}	
		echo $combo;
	}
	
	function incidentes()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		
		$query  = " SELECT DISTINCT id, titulo FROM incidentes 
					WHERE idcategoria <> 37 AND estado <> 16 AND estado <> 17 AND idproyectos <> 12 
					AND fechacreacion > DATE_SUB(CURDATE(),INTERVAL 1 YEAR) 
					ORDER BY id DESC";		
		
		$combo .= "<option value=''> </option>";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['id'].' - '.$row['titulo']."</option>";
		}		
		echo $combo;
	}
	
	function preventivos()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		
		$query  = " SELECT DISTINCT id, titulo FROM incidentes 
					WHERE idcategoria in (12,22,35,43) AND estado <> 16 AND estado <> 17 AND idproyectos <> 12 
					AND fechacreacion > DATE_SUB(CURDATE(),INTERVAL 1 YEAR) 
					ORDER BY id DESC";		
		
		$combo .= "<option value=''> </option>";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['id'].' - '.$row['titulo']."</option>";
		}	
		echo $combo;
	}
	
	function cuatrimestres()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		
		$query  = " SELECT id, periodo FROM cuatrimestres WHERE id > 9 ORDER BY id ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['periodo']."</option>";
		}
		echo $combo;
	}
?>