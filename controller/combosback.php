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
		case "unidadesUsuarios":
	  		  unidadesUsuarios();
	  		  break;
		case "ambientesclientes":
	  		  ambientesclientes();
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
		case "provincias":
			provincias();
			break;
		case "distritos":
			distritos();
			break;
		case "corregimientos":
			corregimientos();
			break;
		case "referidos":
			referidos();
			break;
		case "subreferidos":
			subreferidos();
			break;
		case "ambientes":
	  		  ambientes();
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
		case "departamentosgruposLab":
			  departamentosgruposLab();
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
		case "responsablesactivos":
	  		  responsablesactivos();
	  		  break;
		case "estados":
	  		  estados();
			  break;
		case "estadosLaboratorio":
			  estadosLaboratorio();
			  break;
		case "niveles":
	  		  niveles();
	  		  break;
		case "tiponotificaciones":
	  		  tiponotificaciones();
	  		  break;
		case "notificaciones":
	  		  notificaciones();
	  		  break;
		case "usuarios":
	  		  usuarios();
	  		  break;
		case "usuariosLab":
	  		  usuariosLab();
	  		  break;		 
		case "serie":
	  		  serie();
	  		  break;
		case "activos":
	  		  activos();
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
		case "usuariosDepLab":
			  usuariosDepLab();
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
		case "ambientesget":
	  		  ambientesget();
	  		  break;
		case "subambientes":
	  	      subambientes();
			  break;
		case "responsables":
	  		  responsables();
	  		  break;
		case "responsablesActas":
	  		  responsablesActas();
	  		  break;
		case "centrocostos":
	  		  centrocostos();
	  		  break;
		case "entregables":
	  		  entregables();
	  		  break;
		case "equipos":
	  		  equipos();
				break;
		case "laboratorios":
			  laboratorios();
				break;
		case "tipos":
			  tipos();
				break;
		case "subtipos":
			  subtipos();
				break;
		case "proveedores":
			  proveedores();
				break;
		case "areas":
			  areas();
				break;
    	case "usuarioscompromisos":
			  usuarioscompromisos();
				break; 
		case "estadosfiltrosmasivos":
			  estadosfiltrosmasivos();
				break; 
		case "autos":
	  		  autos();
	  		  break;
	  	case "estadosflotas":
	  		  estadosflotas();
	  		  break;
	  	case "usuariosmaxia":
				usuariosmaxia();
	  		  break;
		case "categoriasautoc":
			  categoriasautoc();
				break;
		case "ambientesautoc":
			  ambientesautoc();
				break;
		case "estadosautoc":
			  estadosautoc();
				break;
		case "departamentosautoc":
			  departamentosautoc();
				break;
		case "subcategoriasautoc":
			  subcategoriasautoc();
				break;
		case "clientesautoc":
			  clientesautoc();
				break;
		case "proyectosautoc":
			  proyectosautoc();
				break;
		case "prioridadesautoc":
			  prioridadesautoc();
				break;
		case "proyectosetiquetas":
			proyectosetiquetas();
				break;
		default:
			  echo "{failure:true}";
			  break;
	}
	
	function camposelectm($campo, $valor, $condicion){
		$query = '';
		if(is_array($valor)){
			$counter = 1;
			$tot = count($valor);
			$test= " ".$condicion." ( ";
			foreach($valor as $val){
				$test .= " find_in_set($val,$campo) ";
				if($counter != $tot){
					$test .=" OR ";
				}
				$counter++;
			}
			$test .= " ) ";
			$query  .= $test;
		}else{
			$arr = strpos($valor, ',');
			if ($arr !== false) {
				$arrvalor = explode(',',$valor);
				$test= " ".$condicion." ( ";
				$tot = count($arrvalor);
				$counter = 1;
				foreach($arrvalor as $val){
					$test .= " find_in_set($val,$campo) ";
					if($counter != $tot){
						$test .=" OR ";
					}
					$counter++;
				}
				$test .= " ) ";

				$query  .= $test;
			}else{
				$query  .= " ".$condicion." find_in_set(".$valor.",$campo) ";
			}
		}
		return $query;
	}
	function unidades()
	{
		global $mysqli;
		$combo = '';
		//if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		if(isset($_REQUEST['tipo'])){ $tipo = $_REQUEST['tipo']; }else{ $tipo = ''; }
		if(isset($_REQUEST['idempresas'])){ $idempresas = $_REQUEST['idempresas']; }else{ $idempresas = ''; }
		if(isset($_REQUEST['idclientes'])){ $idclientes = $_REQUEST['idclientes']; }else{ $idclientes = ''; }
		if(isset($_REQUEST['idproyectos'])){ $idproyectos = $_REQUEST['idproyectos']; }else{ $idproyectos = ''; }
		
		$query  = " SELECT id, nombre FROM ambientes a WHERE 1 = 1 ";
		
		if( $tipo != "" ){
			if($idempresas != '' && $idempresas != 'undefined'){
				$valor = strpos($idempresas, ','); 
				if($valor == true){
					$query  .= " AND a.idempresas in ($idempresas) ";
				}else{
					$query  .= " AND FIND_IN_SET($idempresas,a.idempresas) ";
				}
			}
			if($idclientes != '' && $idclientes != 'undefined'){
				$valor = strpos($idclientes, ','); 
				if($valor == true){
					$query  .= " AND a.idclientes in ($idclientes) ";
				}else{
					$query  .= " AND FIND_IN_SET($idclientes,a.idclientes) ";
				}
			}
			if($idproyectos != '' && $idproyectos != 'undefined'){
				$valor = strpos($idproyectos, ','); 
				if($valor == true){
					$query  .= " AND a.idproyectos in ($idproyectos) ";
				}else{
					$query  .= " AND FIND_IN_SET($idproyectos,a.idproyectos) ";
				} 
			} 
		}
		//debug($query);
		$query .= " ORDER BY nombre ASC ";
		echo $query;
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}		
		echo $combo;
	}
	
	function unidadesUsuarios()
	{
		global $mysqli;
		$combo = ''; 
		$nivel = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);  
		
		$query  = " SELECT a.id, a.nombre FROM ambientes a  
					WHERE 1 = 1 
					GROUP BY a.nombre ORDER BY a.nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}		
		echo $combo;
	}	  
	
	function prioridades()
	{
		global $mysqli;
		$combo = '';
		$nivel 	     = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		$idclientes	 = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0);
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : 0);
		
		$query  = " SELECT a.id, a.prioridad FROM sla a WHERE a.activo = 'Activo' ORDER BY a.prioridad ASC ";
	    //echo $query;
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['prioridad']."</option>";
		}
		echo $combo;
	}

	function provincias(){
        global $mysqli;
        
        $query = "SELECT DISTINCT(id) AS id, nombre FROM provincias ";
        
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            echo "Error en la preparación de la consulta: " . $mysqli->error;
            return;
        }
        
        if (!$stmt->execute()) {
            echo "Error al ejecutar la consulta: " . $stmt->error;
            return;
        }
        
        $result = $stmt->get_result();
        if (!$result) {
            echo "Error al obtener el resultado de la consulta: " . $stmt->error;
            return;
        }
        
        $combo = "<option value='0'>Seleccione</option>";
        
        while ($row = $result->fetch_assoc()) {
            $id = htmlspecialchars($row['id']);
            $nombre = htmlspecialchars($row['nombre']);
            $combo .= "<option value='$id'>$nombre</option>";
        }
        
        echo $combo;
        
        $stmt->close();
    }

    function distritos(){
        global $mysqli;
        
        $id_provincia = $_REQUEST['id_provincia'];
        
        $query = "SELECT DISTINCT(id) AS id, nombre FROM distritos  ";
        
        if ($id_provincia !== 0) {
            $query .= " WHERE id_provincia = ?";
        } 
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            echo "Error en la preparación de la consulta: " . $mysqli->error;
            return;
        }
        if ($id_provincia !== 0) {
        $stmt->bind_param("i", $id_provincia);
        }
        if (!$stmt->execute()) {
            echo "Error al ejecutar la consulta: " . $stmt->error;
            return;
        }
        
        $result = $stmt->get_result();
        if (!$result) {
            echo "Error al obtener el resultado de la consulta: " . $stmt->error;
            return;
        }
        
        $combo = "<option value='0'>Seleccione</option>";
        
        while ($row = $result->fetch_assoc()) {
            $id = htmlspecialchars($row['id']);
            $nombre = htmlspecialchars($row['nombre']);
            $combo .= "<option value='$id'>$nombre</option>";
        }
        
        echo $combo;
        
        $stmt->close();
    }

    function corregimientos(){
        global $mysqli;
        
        $id_provincia = $_REQUEST['id_provincia'];
        $id_distrito = $_REQUEST['id_distrito'];
        
        $query = "SELECT DISTINCT(id) AS id, nombre FROM corregimientos ";
        
        if ($id_provincia !== 0) {
            $query .= " WHERE id_provincia = ?";
        }
        if ($id_distrito !== 0) {
            $query .= " AND id_distrito = ?";
        }
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            echo "Error en la preparación de la consulta: " . $mysqli->error;
            return;
        }
        if ($id_provincia !== 0 && $id_distrito !== 0) {
        $stmt->bind_param("ii", $id_provincia, $id_distrito);
        }
        
        if (!$stmt->execute()) {
            echo "Error al ejecutar la consulta: " . $stmt->error;
            return;
        }
        
        $result = $stmt->get_result();
        if (!$result) {
            echo "Error al obtener el resultado de la consulta: " . $stmt->error;
            return;
        }
        
        $combo = "<option value='0'>Seleccione</option>";
        
        while ($row = $result->fetch_assoc()) {
            $id = htmlspecialchars($row['id']);
            $nombre = htmlspecialchars($row['nombre']);
            $combo .= "<option value='$id'>$nombre</option>";
        }
        
        echo $combo;
        
        $stmt->close();
    }

	
	
    function referidos(){
        global $mysqli;
        
        $query = "SELECT DISTINCT(id) AS id, nombre 
                  FROM referidos
                  ORDER BY nombre ASC";
        
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            echo "Error en la preparación de la consulta: " . $mysqli->error;
            return;
        }
        
        if (!$stmt->execute()) {
            echo "Error al ejecutar la consulta: " . $stmt->error;
            return;
        }
        
        $result = $stmt->get_result();
        if (!$result) {
            echo "Error al obtener el resultado de la consulta: " . $stmt->error;
            return;
        }
        
        $combo .= "<option value='0'>Seleccione</option>";
        
        while ($row = $result->fetch_assoc()) {
            $id = htmlspecialchars($row['id']);
            $nombre = htmlspecialchars($row['nombre']);
            $combo .= "<option value='$id'>$nombre</option>";
        }
        
        echo $combo;
        
        $stmt->close();
    }

    function subreferidos(){
        global $mysqli;
        
        $id_referido = $_REQUEST['id_referido'];
        
        $query = "SELECT DISTINCT id, nombre 
                  FROM subreferidos 
                  WHERE id_referido = ? ";
        
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            echo "Error en la preparación de la consulta: " . $mysqli->error;
            return;
        }
        
        $stmt->bind_param("i", $id_referido);
        
        if (!$stmt->execute()) {
            echo "Error al ejecutar la consulta: " . $stmt->error;
            return;
        }
        
        $result = $stmt->get_result();
        if (!$result) {
            echo "Error al obtener el resultado de la consulta: " . $stmt->error;
            return;
        }
        
        $combo .= "<option value='0'>Seleccione</option>";
        
        while ($row = $result->fetch_assoc()) {
            $id = htmlspecialchars($row['id']);
            $nombre = htmlspecialchars($row['nombre']);
            $combo .= "<option value='$id'>$nombre</option>";
        }
        
        echo $combo;
        
        $stmt->close();
    }
	
	function empresas(){
		global $mysqli;
		$combo 		= '';
		$idempresas = (!empty($_SESSION['idempresas']) ? $_SESSION['idempresas'] : 0);
		$nivel 		= (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		
		$query  = " SELECT id, descripcion FROM empresas WHERE 1 = 1 ";		
		if($idempresas !="" && ($nivel != 1 || $nivel != 2) ){ 
		    $query .= " AND id = 1 ";
		}
		$query  .= " ORDER BY descripcion ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){ 
			$combo .= "<option value='".$row['id']."'>".$row['descripcion']."</option>";
		}	
		echo $combo;  
	} 
	
	function departamentos(){
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['idproyectos'])){ $idproyectos = $_REQUEST['idproyectos']; }else{ $idproyectos = ''; } 
		
		//GRUPOS
		$query  = " SELECT a.id, a.nombre,a.tipo
					FROM departamentos a  
					WHERE 1 = 1 ";
		
		/* if($idproyectos != '' && $idproyectos != 'undefined'){
			if(is_array($idproyectos)){ $idproyectos = implode(',',$idproyectos); }
			$query  .= " AND b.idproyectos in ($idproyectos) ";
		}  */
		$query .= " AND a.tipo = 'departamento' ";
		$query  .= " GROUP BY a.nombre ORDER BY a.nombre ASC ";		
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
		if(isset($_REQUEST['idproyectos'])){ $idproyectos = $_REQUEST['idproyectos']; }else{ $idproyectos = ''; } 
		
		//GRUPOS
		$query  = " SELECT a.id, a.nombre,a.tipo
					FROM departamentos a  
					WHERE 1 = 1 ";

		/* if($idproyectos != '' && $idproyectos != 'undefined'){
			if(is_array($idproyectos)){ $idproyectos = implode(',',$idproyectos); }
			$query  .= " AND b.idproyectos in ($idproyectos) ";
		}  */
		$query .= " AND a.tipo = 'grupo' ";
		$query  .= " GROUP BY a.id ORDER BY a.nombre ASC ";
	 
		$result = $mysqli->query($query);	
		//$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){ 
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		
		echo $combo;
	}

	function departamentosgrupos(){
		global $mysqli;
		$combo = ''; 
		$nivel 		= (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		$usuario 	= (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : 0);
		if(isset($_REQUEST['idproyectos'])){ $idproyectos = $_REQUEST['idproyectos']; }else{ $idproyectos = ''; } 
		 
		 
		if($nivel == 1 || $nivel == 2 || $nivel == 4 || $nivel == 7){
			//DEPARTAMENTOS
			if($nivel != 7){
				$combo .= "<option value='0'> Sin Asignar </option>";
		} 
		$query  = " SELECT a.id, a.nombre,a.tipo
					FROM departamentos a  
					WHERE 1 = 1 ";
		
		/* if($idproyectos != '' && $idproyectos != 'undefined'){
			if(is_array($idproyectos)){ $idproyectos = implode(',',$idproyectos); }
			$query  .= " AND b.idproyectos in ($idproyectos) ";
		} */ 
		$query .= " AND a.tipo = 'departamento' ";
		$query .= " GROUP BY a.id ";
		$query  .= " ORDER BY a.nombre ASC ";
		
		$result = $mysqli->query($query);
		
		if($result->num_rows > 0){
		    //DEPARTAMENTOS
				if($nivel != 7){
					$combo .= "<optgroup label='DEPARTAMENTOS'>";
				}
				while($row = $result->fetch_assoc()){ 
					$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
				}
				if($nivel != 7){
					$combo .= "</optgroup>";
				}
			}
			
			//GRUPOS			
			$query  = " SELECT a.id, a.nombre 
						FROM departamentos a  
						INNER JOIN usuarios c ON FIND_IN_SET(a.id, c.iddepartamentos)
						WHERE 1 = 1
						";
			/* if($nivel == 4 || $nivel == 7){
				if($idproyectos != '' && $idproyectos != 'undefined'){
					if(is_array($idproyectos)){ $idproyectos = implode(',',$idproyectos); }
					$query  .= " AND b.idproyectos in ($idproyectos) ";
	        	} 
			} */

			$query .= " AND a.tipo = 'grupo' ";
			$query .= " GROUP BY a.id ";
			$query  .= " ORDER BY a.nombre ASC ";
			
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
						INNER JOIN usuarios c ON FIND_IN_SET(a.id, c.iddepartamentos)
						WHERE c.usuario = '$usuario' ";		
			/* if($idproyectos != '' && $idproyectos != 'undefined'){
				if(is_array($idproyectos)){ $idproyectos = implode(',',$idproyectos); }
				$query  .= " AND b.idproyectos in ($idproyectos) ";
	    	} */ 			
			$query .= " AND a.tipo = 'departamento' ";
			$query  .= " ORDER BY a.nombre ASC ";
			
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
						INNER JOIN usuarios c ON FIND_IN_SET(a.id, c.iddepartamentos)
						WHERE c.usuario = '$usuario' ";		
		    /* if($idproyectos != '' && $idproyectos != 'undefined'){
				if(is_array($idproyectos)){ $idproyectos = implode(',',$idproyectos); }
				$query  .= " AND b.idproyectos in ($idproyectos) ";
	    	} */			
			$query .= " AND a.tipo = 'grupo' ";
			$query  .= " GROUP BY a.nombre ORDER BY a.nombre ASC ";
			
			$result = $mysqli->query($query);
			if($result->num_rows > 0){
				$combo .= "<optgroup label='GRUPOS'>";
				while($row = $result->fetch_assoc()){ 
					$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
				}
				$combo .= "</optgroup>";
			}
			
		}	
	//	$combo .= "<option value='1,2,3,4,5,6,7,10,11,12,13,15,16,17,18,19,20,21,22,23,24'>Todos</option>";
		echo $combo;		
	}
	
	function departamentosgruposLab(){
		global $mysqli;
		$combo	= ''; 
		$query  = " SELECT id, nombre FROM departamentos WHERE 1 = 1 AND id = 12";	 
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
		echo $combo;
	}
	
	function clientes(){
		global $mysqli;
		$combo	 = '';
		$nivel 	 = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		$usuario = (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : 0);
		$idempresas = (!empty($_REQUEST['idempresas']) ? $_REQUEST['idempresas'] : 1);
		
		if(isset($_REQUEST['tipo'])){ $tipo = $_REQUEST['tipo']; }else{ $tipo = ''; }																	   
		//if(isset($_REQUEST['idempresas'])){ $idempresas = $_REQUEST['idempresas']; }else{ $idempresas = '1'; }
		
		$query  = " SELECT a.id, a.nombre, a.apellidos, a.telefono FROM clientes a ";
		if($nivel != 1 && $nivel != 2){
			$query .= " LEFT JOIN usuarios b ON find_in_set(a.id, b.idclientes)
						WHERE b.usuario = '".$usuario."' ";
		}else{
			$query .= " WHERE a.id != 0 ";
		}
  
		if( $tipo != "" ){
			if($idempresas != '' && $idempresas != 'undefined'){
				if(is_array($idempresas)){ $idempresas = implode(',',$idempresas); }
				$query  .= " AND a.idempresas in ($idempresas) ";
			}
		}else{
			if($idempresas != "" && $idempresas != 'undefined'){
				if(is_array($idempresas)){ $idempresas = implode(',',$idempresas); }
				//$query .= " AND a.idempresas IN ($idempresas) ";
				$query  .=" AND find_in_set($idempresas,a.idempresas) ";
			}
		}
		$query  .= " ORDER BY a.nombre ASC ";
		//debugL('query:'.$query);
		$result = $mysqli->query($query);
		$combo .= "<option value='0' data-telf='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){ 
			$combo .= "<option value='".$row['id']."' data-telf='".$row['telefono']."'>".$row['nombre']." ".$row['apellidos']."</option>";
		}
		echo $combo;  
	}
	
	function proyectos(){
		global $mysqli;
		$combo 	= '';
		$nivel 	= $_SESSION['nivel'];
		$usuario = $_SESSION['usuario']; 
		
		$query  = " SELECT a.id, a.nombre
					FROM proyectos a 
					WHERE 1 = 1
					ORDER BY a.nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
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
		if(isset($_REQUEST['idclientes'])){ $idclientes = $_REQUEST['idclientes']; }else{ $idclientes = '0'; }
		if(isset($_REQUEST['idproyectos'])){ $idproyectos = $_REQUEST['idproyectos']; }else{ $idproyectos = '0'; }
		 ; 
		
		$query  = " SELECT a.id, a.nombre FROM ambientes a 
					WHERE 1 GROUP BY a.id ORDER BY a.nombre ";
		 
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}		
		echo $combo;
	}
	
	function proyectosrel(){
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['tipo'])){ $tipo = $_REQUEST['tipo']; }else{ $tipo = ''; }
		if(isset($_REQUEST['idclientes'])){ $idclientes = $_REQUEST['idclientes']; }else{ $idclientes = ''; }
		$nivel = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0); 
		$idclientess = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : 0); 
		$idproyectoss = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : 0);						
		
		$query  = " SELECT a.id, a.nombre, concat(b.nombre, ' ', b.apellidos) as cliente
					FROM proyectos a 
					LEFT JOIN clientes b ON a.idclientes = b.id 
					WHERE 1 = 1 ";		
		if( $tipo != "" ){
			if($idclientes != '' && $idclientes != 'undefined'){
				if(is_array($idclientes)){ $idclientes = implode(',',$idclientes); }
				$query  .= " AND a.idclientes in ($idclientes) ";
			}
			if($nivel == 4 || $nivel == 7){
				if($idclientess != ""){
					$query  .=" AND a.idclientes IN (".$idclientess.")";
				}
				if($idproyectoss != ""){
					$query  .=" AND a.id IN (".$idproyectoss.")";
				} 
				//debug("IDPROYECTOSES:".$idproyectoss);
			}
		}else{ 
			if($idclientes !=""){ 
				//$query .= " AND a.idclientes in ($idclientes) ";   
				$query  .=" AND find_in_set($idclientes,a.idclientes) ";
			}
		} 
		$query  .= " ORDER BY nombre ASC ";
		//echo $query;
		$result = $mysqli->query($query);
		//debug($query);
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']." ".$row['cliente']."</option>";
		}
		echo $combo;  
	}
	
	function categorias(){
		global $mysqli;
		$combo = ''; 
		if(isset($_REQUEST['idproyectos'])){ $idproyectos = $_REQUEST['idproyectos']; }else{ $idproyectos = ''; } 
		if(isset($_REQUEST['tipo'])){ $tipo = $_REQUEST['tipo']; }else{ $tipo = ''; }
		$nivel = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		 
		$query  = " SELECT a.id, a.nombre 
					FROM categorias a  
					WHERE 1=1
					ORDER BY a.nombre ASC ";
		 
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>"; 
		}
		 
		echo $combo;		
	} 
	
	function subcategorias()
	{
		global $mysqli;	
		$combo 		   = ''; 
		$idproyectos   = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$idcategoria   = (!empty($_REQUEST['idcategoria']) ? $_REQUEST['idcategoria'] : ''); 
		$tipo   	   = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');		
		$nivel		   = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		 
		$query  = " SELECT a.id, a.nombre 
					FROM subcategorias a  
					INNER JOIN categorias_subcategorias b ON b.id_subcategoria = a.id
					WHERE b.id_categoria IN (".$idcategoria.")
					ORDER BY a.nombre ASC ";
					//echo $query;
  
		$result = $mysqli->query($query);
		 
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		} 
		echo $combo;		
	}
	
	function marcas()
	{
		global $mysqli;
		$combo = '';
		
		
		//Variables de sesión de usuario
		$nivel 		 = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);  
		$idclientes  = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : 0);
		$idproyectos = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : 0);
		
		//Variables de formulario 
		$tipo		= (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : ""); 
		$idcliente	= (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : ""); 
		$idproyecto	= (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : ""); 
		
		$query  = " SELECT DISTINCT(a.id) AS id, a.nombre FROM marcas a ";
		if($nivel == 4 || $nivel == 7){
			if($tipo != "maestro"){
				$query  .= " INNER JOIN activos b ON a.id = b.idmarcas ";
				//Sessión
				if($idclientes != ''){
					$arr = strpos($idclientes, ',');
					if ($arr !== false) {
						$query  .= " AND b.idclientes IN (".$idclientes.") ";
					}else{
						$query  .= " AND find_in_set($idclientes,b.idclientes) ";
					}  
				} 
				//Sessión
				if($idproyectos != ''){
					$arr = strpos($idproyectos, ',');
					if ($arr !== false) {
						$query  .= " AND b.idproyectos IN (".$idproyectos.") ";
					}else{
						$query  .= " AND find_in_set($idproyectos,b.idproyectos) ";
					}  
				}
				
			}  
		}else{
			if($tipo == 'filtrosmasivos'){
				$query  .= " INNER JOIN activos b ON a.id = b.idmarcas ";
			if($idproyectos != '' && $idproyectos != 'undefined'){
			if(is_array($idproyectos)){ $idproyectos = implode(',',$idproyectos); }
			$query  .= " AND b.idproyectos in ($idproyectos) ";
		} 
				/*
				if($idcliente != ''){ 
					$arr = strpos($idcliente, ',');
					if ($arr !== false) {
						$query  .= " AND b.idclientes IN (".$idcliente.") ";
					}else{
						$query  .= " AND find_in_set(".$idcliente.",b.idclientes) ";
					} 
				}
				if($idproyecto != ''){ 
					$arr = strpos($idproyecto, ',');
					if ($arr !== false) {
						$query  .= " AND b.idproyectos IN (".$idproyecto.") ";
					}else{
						$query  .= " AND find_in_set(".$idproyecto.",b.idproyectos) ";
					}
				} */
			}
		} 
		$query  .= " WHERE 1 = 1 "; 
		 
		$query .= " ORDER BY a.nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}
	
	function modelos(){
		global $mysqli;
		$combo		= '';
		$idmarcas 	= (!empty($_REQUEST['idmarcas']) ? $_REQUEST['idmarcas'] : '');
		
		$query  = " SELECT a.id, a.nombre FROM modelos a WHERE 1 = 1 ";
		if($idmarcas != 0){ 
			$widmarcas = camposelectm('a.idmarcas', $idmarcas, 'AND');
			//$query  .= ' AND '.$widmarcas;
			$query  .= $widmarcas;
		}
			
		$query  .= " ORDER BY a.nombre ASC ";
		//debugL($query);
		$result = $mysqli->query($query);
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){ 
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}
	
	function modalidades()
	{
		global $mysqli;
		
		$combo = ''; 
		$tipo = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : "");  
		
		$query  = " SELECT DISTINCT(a.id) AS id, a.nombre FROM activostipos a ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}
	
	function ambientes(){
		global $mysqli;		
		$combo 		 = '';
		$query  	 = " SELECT a.id, a.nombre FROM ambientes a 
 						 WHERE 1 = 1  
						 ORDER BY nombre ASC "; 
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}
	
	function ambientesclientes()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['ambienteactual'])){ $ambienteactual = $_REQUEST['ambienteactual']; }else{ $ambienteactual = ''; }
		$nivel 	= (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);  
		$idclientes  = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : 0);
		$idproyectos = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : 0);
		
		//Variables formulario
		$idcliente 	= (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$idproyecto	= (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		
		$query = "	SELECT DISTINCT(b.id) as id, b.nombre FROM ambientes b 
					WHERE b.nombre IS NOT null  "; //AND b.id != '".$ambienteactual."'
		//Cliente Cemi - Director / Gerente - Cliente SyM
		if($nivel == 4 || $nivel == 5 || $nivel == 7){  
			if($idclientes != ''){
				$arr = strpos($idclientes, ',');
				if ($arr !== false) {
					$query  .= " AND b.idclientes IN (".$idclientes.") ";
				}else{
					$query  .= " AND find_in_set(".$idclientes.",b.idclientes) ";
				}  
			}
			if($idproyectos != ''){
				$arr = strpos($idproyectos, ',');
				if ($arr !== false) {
					$query  .= " AND b.idproyectos IN (".$idproyectos.") ";
				}else{
					$query  .= " AND find_in_set(".$idproyectos.",b.idproyectos) ";
				}  
			}
		}
		
		if($idcliente != ''){
			$query  .= " AND find_in_set(".$idcliente.",a.idclientes) ";
		}
		if($idproyecto != ''){
			$query  .= " AND find_in_set(".$idproyecto.",a.idproyectos) ";
		}
		
		$query .= "  ORDER BY b.id ";
		//echo "AMBIENTESCLIENTES:".$query;
		//debugL($query);
		$result = $mysqli->query($query);
		
		//$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}		
		echo $combo;
	}
	
	function responsablesactivos()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		
		//Variables de sesión
		$nivel 		 = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		$idclientes  = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : 0);
		$idproyectos = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : 0);
		
		//Variables de formulario
		$idcliente  = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0);
		$idproyecto = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : 0);
		
		$query  = " SELECT a.id, a.nombre FROM usuarios a WHERE 1 = 1 ";
		
		//Cliente Cemi - Director / Gerente - Cliente SyM
		if($nivel == 4 || $nivel == 5 || $nivel == 7){
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
		}
		
		if($idcliente != ''){
			$query  .= " AND find_in_set(".$idcliente.",a.idclientes) ";
		}
		if($idproyecto != ''){
			$query  .= " AND find_in_set(".$idproyecto.",a.idproyectos) ";
		}
		
		$query .= " ORDER BY nombre ASC ";
		//echo $query;
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}
	
	function estados(){
		global $mysqli;
		$combo = ''; 
		if(isset($_REQUEST['idproyectos'])){ $idproyectos = $_REQUEST['idproyectos']; }else{ $idproyectos = ''; }
		if(isset($_REQUEST['tipo'])){ $tipo = $_REQUEST['tipo']; }else{ $tipo = ''; }
		 
		$query  = " SELECT a.id, a.nombre
					FROM estados a  
					WHERE 1 = 1   ";
		
		/* if($idproyectos != '' && $idproyectos != 'undefined'){
			if(is_array($idproyectos)){ $idproyectos = implode(',',$idproyectos); }
			$query  .= " AND b.idproyectos in ($idproyectos) ";
		}  */
		$query  .= " ORDER BY a.nombre ASC ";
		
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>"; 
		}
		 
		echo $combo;		
	}
	
	
	function estadosLaboratorio()
	{
		global $mysqli;
		$tipo = $_REQUEST['tipo'];
		$formulario = $_REQUEST['formulario'];
		$nivel = $_SESSION['nivel'];
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		if($nivel != 1 || $nivel != 2){
			if(isset($_REQUEST['idempresas'])){ $idempresas = $_REQUEST['idempresas']; }else{ $idempresas = $_SESSION['idempresas']; }
			if(isset($_REQUEST['idclientes'])){ $idclientes = $_REQUEST['idclientes']; }else{ $idclientes = $_SESSION['idclientes']; }
			if(isset($_REQUEST['idproyectos'])){ $idproyectos = $_REQUEST['idproyectos']; }else{ $idproyectos = $_SESSION['idproyectos']; }
		}else{
			if(isset($_REQUEST['idempresas'])){ $idempresas = $_REQUEST['idempresas']; }else{ $idempresas = '0'; }
			if(isset($_REQUEST['idclientes'])){ $idclientes = $_REQUEST['idclientes']; }else{ $idclientes = '0'; }
			if(isset($_REQUEST['idproyectos'])){ $idproyectos = $_REQUEST['idproyectos']; }else{ $idproyectos = '0'; }
		}
		$query  = " SELECT a.id, a.nombre,b.tipo 
					FROM estados a 
					INNER JOIN estadospuente b ON b.idestados = a.id  					
					WHERE 1 = 1 AND FIND_IN_SET('".$tipo."',b.tipo)  ";
		if($formulario == 'nuevo'){
			//Nuevo
			$query .= " AND a.nombre = 'Nuevo'";
		} 
		if($idempresas != ''){
			$arr = strpos($idempresas, ',');
			if ($arr !== false) {
				$query  .= " AND b.idempresas IN (".$idempresas.") ";
			}else{
				$query  .= " AND find_in_set($idempresas,b.idempresas) ";
			}
			//$query  .= " AND find_in_set($idempresas,idempresas) ";
			
		}
		
		if(is_array($idclientes)){
			$counter = 1;
			$tot = count($idclientes);
			$test= " AND ( ";
			foreach($idclientes as $val){
				$test .= " find_in_set($val,b.idclientes) ";
				if($counter != $tot){
					$test .=" OR ";
				}
				$counter++;
			}
			$test .= " ) ";
			$query  .= $test;
		}else{
			$arr = strpos($idclientes, ',');
			if ($arr !== false) {
				$arrclientes = explode(',',$idclientes);
				$test= " AND ( ";
				$tot = count($arrclientes);
				$counter = 1;
				foreach($arrclientes as $val){
					$test .= " find_in_set($val,b.idclientes) ";
					if($counter != $tot){
						$test .=" OR ";
					}
					$counter++;
				}
				$test .= " ) ";

				$query  .= $test;
			}else{
				$query  .= " AND find_in_set($idclientes,b.idclientes) ";
			}
		}
		
		if(is_array($idproyectos)){
			$counter = 1;
			$tot = count($idproyectos);
			$test= " AND ( ";
			foreach($idproyectos as $val){
				$test .= " find_in_set($val,b.idproyectos) ";
				if($counter != $tot){
					$test .=" OR ";
				}
				$counter++;
			}
			$test .= " ) ";
			$query  .= $test;
		}else{
			$arr = strpos($idclientes, ',');
			if ($arr !== false) {
				$arrclientes = explode(',',$idclientes);
				$test= " AND ( ";
				$tot = count($arrclientes);
				$counter = 1;
				foreach($arrclientes as $val){
					$test .= " find_in_set($val,b.idclientes) ";
					if($counter != $tot){
						$test .=" OR ";
					}
					$counter++;
				}
				$test .= " ) ";

				$query  .= $test;
			}else{
				$query  .= " AND find_in_set($idclientes,b.idclientes) ";
			}
		} 
		$query .= " GROUP BY a.nombre ORDER BY a.nombre ASC  ";
		//echo $query;
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
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
		$nivel  = $_SESSION['nivel'];
		
		$query  = " SELECT id, nombre FROM niveles WHERE 1 = 1  ";
		if($nivel == 7){
			$query .=" AND id IN (3,7) ";
		}
		$query .=" ORDER BY nombre ASC ";
		
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}	
		echo $combo;
	}

	function tiponotificaciones()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		
		$query  = " SELECT id, tiponotificacion FROM tiponotificaciones WHERE 1 = 1  ";
		
		$query .=" ORDER BY tiponotificacion ASC ";
		
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['tiponotificacion']."</option>";
		}	
		echo $combo;
	}

	function notificaciones()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		
		$query  = " SELECT id, nombre FROM notificaciones WHERE 1 = 1  ";
		
		$query .=" ORDER BY nombre ASC ";
		
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
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
		
		//Variables de sesión
		$nivelU 	= (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0); 
		$idclientes = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : 0);
		$idproyectos = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : 0);
		
		//Variables de formulario
		$tipo		= (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : ""); 
		$idcliente	= (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : ""); 
		$idproyecto	= (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : ""); 
		
		$query  = " SELECT a.correo, a.nombre FROM usuarios a INNER JOIN niveles b ON a.nivel = b.id WHERE a.nivel = 2 and a.estado = 'Activo' ";
		
		//Niveles diferentes a Administrador
		if($nivelU != 1){
			if($idclientes != '0' || $idclientes != ''){
				$widclientes = camposelectm('a.idclientes', $idclientes, 'AND');
				$query  .= $widclientes;
			}
			/*if($idclientes != ''){
				$arr = strpos($idclientes, ',');
				if ($arr !== false) {
					$query  .= " AND a.idclientes IN (".$idclientes.") ";
				}else{
					$query  .= " AND find_in_set($idclientes,a.idclientes) ";
				}  
			}*/
		}
		
		//Si nivel Cliente
		if($nivelU==4 || $nivelU == 7){
			if($idclientes != ''){
				$arr = strpos($idclientes, ',');
				if ($arr !== false) {
					$query  .= " AND a.idclientes IN (".$idclientes.") ";
				}else{
					$query  .= " AND find_in_set($idclientes,a.idclientes) ";
				}  
			}
			if($idproyectos != ''){
				$arr = strpos($idproyectos, ',');
				if ($arr !== false) {
					$query  .= " AND a.idproyectos IN (".$idproyectos.") ";
				}else{
					$query  .= " AND find_in_set($idproyectos,a.idproyectos) ";
				}  
			}
		}else{
			if($tipo == 'filtrosmasivos'){ 
				if($idcliente != ''){ 
					$arr = strpos($idcliente, ',');
					if ($arr !== false) {
						$query  .= " AND a.idclientes IN (".$idcliente.") ";
					}else{
						$query  .= " AND find_in_set(".$idcliente.",a.idclientes) ";
					} 
				}
				if($idproyecto != ''){ 
					$arr = strpos($idproyecto, ',');
					if ($arr !== false) {
						$query  .= " AND a.idproyectos IN (".$idproyecto.") ";
					}else{
						$query  .= " AND find_in_set(".$idproyecto.",a.idproyectos) ";
					}
				} 
			}
		}
		
		if($nivel !=''){
			//$query  .=" AND a.nivel IN ($nivel) ";
			$query  .=" AND find_in_set(a.nivel,'".$nivel."') ";
		}
		$query  .=" ORDER BY a.nombre ASC ";
		//debugL($query,"solicitantes");
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		//debug($query);
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['correo']."'>".$row['nombre']."</option>";
		}	
		echo $combo;
	}
	
	function usuariosLab()
	{
		global $mysqli;
		
		$nivel = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : '');
		$usuario = (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : 0);
		$iddepartamentos = (!empty($_SESSION['iddepartamentos']) ? $_SESSION['iddepartamentos'] : 0);
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		$combo 	= '';
		
		$pos = strpos($iddepartamentos, '4');
		//Solo usuarios Lab / Usuarios Admin Soporte
		$query  = " SELECT a.correo, a.nombre FROM usuarios a INNER JOIN niveles b ON a.nivel = b.id WHERE 1 = 1 "; 
		if($_SESSION['usuario'] != 'umague' && $_SESSION['usuario'] != 'mbatista' && $nivel != 1 && $nivel != 2 && $pos !== true){
			$query  .=" AND a.usuario = '".$usuario."' ";
		}
		$query  .=" ORDER BY a.nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		//debug($query);
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['correo']."'>".$row['nombre']."</option>";
		}	
		echo $combo;
	}
	
	function activos()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		if(isset($_REQUEST['idambientes'])){ $idambientes = $_REQUEST['idambientes']; }else{ $idambientes = ''; }
		
		if($idambientes != ''){
			$query  = " SELECT DISTINCT(serie), id, nombre FROM activos WHERE 1 = 1 AND serie != '' AND idambientes = '$idambientes' ORDER BY serie ASC ";
		}else{
			$query  = " SELECT DISTINCT(serie), id, nombre FROM activos WHERE 1 = 1 AND serie != '' AND idambientes = '' ORDER BY serie ASC ";
		}
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['serie']." - ".$row['nombre']."</option>";
		}		
		echo $combo;
	}
	
	function serie()
	{
		global $mysqli;
		$combo = '';  
		$idclientes = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0); 
		
		$query  = " SELECT DISTINCT(serie), id, nombre 
					FROM activos 
					WHERE serie != '' ";
						
		if($idclientes != ''){ 
			$query  .= " AND idclientes IN (".$idclientes.") ";
		}
		$query .= " ORDER BY serie ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['serie']." - ".$row['nombre']."</option>";
		}		
		echo $combo;
	}
	
	function seriesel()
	{
		global $mysqli;
		$combo = ''; 
		if(isset($_REQUEST['idserie'])){ $idactivos = $_REQUEST['idserie']; }else{ $idactivos = ''; }
		$resultado = array();
		
		if($idactivos != ''){
			$query  = " SELECT b.nombre AS marca, c.nombre AS modelo
						FROM activos a 
						LEFT JOIN marcas b ON b.id = a.idmarcas
						LEFT JOIN modelos c ON c.id = a.idmodelos 
						WHERE 1 = 1 
						AND a.id = $idactivos ";
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
		
		$combo .= "<option value='0'> Sin Asignar </option>";
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
		$nivel 		 = (!empty($_REQUEST['nivel']) ?$_REQUEST['nivel'] : '');
		$nivelU 	 = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0); 
		$idclientes  = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : 0);
		$idproyectos = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : 0);
		
		$query  = " SELECT 0 AS id, ' - RESPONSABLES - ' AS nombre
					UNION 
					SELECT CONCAT(a.correo, '') AS id, a.nombre 
					FROM usuarios a
					WHERE a.nivel = 2 and a.estado = 'Activo'
					GROUP BY a.id";
		
		$result = $mysqli->query($query);		
		
		//$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			if($row['id'] != ''){
				$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
			}			
		}	
		echo $combo;
	}
	
	function usuariosDep()
	{
		global $mysqli;
		$combo 	= '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		$nivel 			 = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		$idclientes 	 = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : 0);
		$idproyectoss 	 = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : 0);
		$iddepartamentos = (!empty($_REQUEST['iddepartamentos']) ? $_REQUEST['iddepartamentos'] : '');
		$idproyectos 	 = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$tipo 			 = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');
		if (is_array($iddepartamentos))
		$iddepartamentos = implode(',',$iddepartamentos);
		
		$query  = " SELECT id, correo, nombre, estado 
					FROM usuarios WHERE nombre != ''  ";
		if($tipo != ""){
			if($iddepartamentos != ""){
				$query  .=" AND iddepartamentos IN (".$iddepartamentos.") ";
			}
		}else{
			if($iddepartamentos !='' && $iddepartamentos !='undefined'){
				$query  .=" AND find_in_set(".$iddepartamentos.",iddepartamentos) ";
			}
		}
		if($nivel != 1){
			//$query  .=" AND idclientes IN(".$idclientes.") ";
			if($idclientes != '' && $idclientes != 'undefined'){
				$widclientes = camposelectm('idclientes', $idclientes, 'AND');
				$query  .= $widclientes;
			}
		}
		if($nivel == 4 || $nivel == 7){
			/* if($idproyectoss != ""){
				$query  .=" AND idproyectos IN (".$idproyectoss.")";
			} */
			if($idproyectoss != ''){
				$arr = strpos($idproyectoss, ',');
				if ($arr !== false) {
					$query  .= " AND idproyectos IN (".$idproyectoss.") ";
				}else{
					$query  .= " AND find_in_set(".$idproyectoss.",idproyectos) ";
				}
				//$query .= " AND a.nivel = ".$nivel."";				
			}
		}
		if($idproyectos != ""){
			$query  .=" AND find_in_set(".$idproyectos.",idproyectos) ";
		}
		$query  .=" ORDER BY nombre ASC ";
		$result = $mysqli->query($query);		
		//debug($query);
		//echo $query;
		//if($nivel != 7){
			$combo .= "<option value='0'> Sin Asignar </option>";
		//}		
		while($row = $result->fetch_assoc()){
			if($row['estado'] == 'Inactivo'){
				$combo .= "<option value='".$row['correo']."' disabled='disabled'>".$row['nombre']."</option>";
			}else{
				$combo .= "<option value='".$row['correo']."'>".$row['nombre']."</option>";
			}
		}	
		echo $combo;
	}
	
	function usuariosDepLab(){
		global $mysqli;
		$combo 	= '';
		$iddepartamentos = (!empty($_REQUEST['iddepartamentos']) ?$_REQUEST['iddepartamentos'] : '');
		$tipo			 = (!empty($_REQUEST['tipo']) ?$_REQUEST['tipo'] : '');
		if (is_array($iddepartamentos))
		$iddepartamentos = implode(',',$iddepartamentos);
		$query  = " SELECT id, correo, nombre, estado FROM usuarios WHERE 1 = 1  ";
		$pos = strpos($iddepartamentos, '4');
		if(($_SESSION['usuario'] != 'umague' && $_SESSION['usuario'] != 'mbatista' && $_SESSION['nivel'] != 1) && ($_SESSION['nivel'] != 2 && $pos !== true)){
			$query .=" AND usuario = 'laboratorio' ";
		}
		
		$query  .=" AND find_in_set($iddepartamentos,iddepartamentos) ";
		$query  .=" AND nivel = 3 "; 
		//debug($query);
		$result = $mysqli->query($query); 
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			if($row['estado'] == 'Inactivo'){
				$combo .= "<option value='".$row['correo']."' disabled='disabled'>".$row['nombre']."</option>";
			}else{
				$combo .= "<option value='".$row['correo']."'>".$row['nombre']."</option>";
			} 
		}	
		echo $combo;
	}

	function incidentes()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		$id = (!empty($_REQUEST['id']) ?$_REQUEST['id'] : '');
		
		$query  = " SELECT DISTINCT id, titulo FROM incidentes 
					WHERE tipo = 'incidentes' AND idestados <> 16 AND idestados <> 17 AND idproyectos <> 12 
					AND fechacreacion > DATE_SUB(CURDATE(),INTERVAL 1 YEAR) 
					";		
		if($id != ""){
			$query .= " AND id != ".$id." ";
		}
		
		$query .= "ORDER BY id DESC";
		
		$combo .= "<option value='0'> Sin Asignar </option>";
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
					WHERE tipo = 'preventivos' AND idestados <> 16 AND idestados <> 17 AND idproyectos <> 12 
					AND fechacreacion > DATE_SUB(CURDATE(),INTERVAL 1 YEAR) 
					ORDER BY id DESC";		
		
		$combo .= "<option value='0'> Sin Asignar </option>";
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
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['periodo']."</option>";
		}
		echo $combo;
	}
	
	function ambientesget()
	{
		global $mysqli;
		$combo 			 = '';  
		
		$query  = " SELECT id, nombre FROM ambientes WHERE id != 0 AND estado = 'Activo' ";
		$query  .= " ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'>Sin Asignar</option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;  
	}
	
	function subambientes() {
		global $mysqli;
		$combo = ''; 
		 
		$query  = " SELECT a.id, a.nombre FROM subambientes a 			
					WHERE 1 ORDER BY a.nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		
		echo $combo;  
	}
	
	function responsables()
	{
		global $mysqli;
		$usuario 	= $_SESSION['usuario'];
		$nivel 		= $_SESSION['nivel'];
		
		$query  = " SELECT a.usuario, a.nombre, a.estado 
					FROM usuarios a  
					WHERE a.nivel = 5 AND a.estado = 'Activo' ";
		
		if($nivel == 5){
			$query  .= " AND a.usuario = '$usuario' ";
		}
		//debug($query);
		$result = $mysqli->query($query);
		
		//$combo = "<select name='responsables' id='responsables' style='width:100%'>";
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['usuario']."'>".$row['nombre']."</option>";
		}
		$combo .= "</select>";
		echo $combo;
	}
	
	function responsablesActas(){
		global $mysqli;
		$usuario 	= $_SESSION['usuario'];
		$nivel 		= $_SESSION['nivel'];
		
		$query  = "  SELECT id, nombre from usuarios 
					 WHERE nivel = 3 ";
		 
		$result = $mysqli->query($query);
		 
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		$combo .= "</select>";
		echo $combo;
	}
	
	function centrocostos()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['idclientes'])){ $idclientes = $_REQUEST['idclientes']; }else{ $idclientes = ''; } 
		
		$query  = " SELECT id, nombre, estado FROM centrocostos WHERE id != 0 "; 		
		if($idclientes != ''){ 
			$query  .= " AND idclientes = '".$idclientes."' ";
		}
		$query  .= " ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'>Sin Asignar</option>";
		while($row = $result->fetch_assoc()){
			if($row['estado'] == 'Inactivo' || $row['id'] == 0){
				$combo .= "<option value='".$row['id']."' disabled='disabled'>".$row['nombre']."</option>";
			}else{
				$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
			}
		}
		echo $combo;  
	}
	
	function entregables()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['idclientes'])){ $idclientes = $_REQUEST['idclientes']; }else{ $idclientes = ''; } 
		
		$query  = " SELECT id, nombre, estado FROM entregables WHERE id != 0 "; 		
		if($idclientes != ''){ 
			$query  .= " AND idclientes = '".$idclientes."' ";
		}
		$query  .= " ORDER BY nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'>Sin Asignar</option>";
		while($row = $result->fetch_assoc()){
			if($row['estado'] == 'Inactivo' || $row['id'] == 0){
				$combo .= "<option value='".$row['id']."' disabled='disabled'>".$row['nombre']."</option>";
			}else{
				$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
			}
		}
		echo $combo;  
	}
	
	function equipos()
	{
		global $mysqli;
		$combo 	= '';
		if(isset($_REQUEST['idambientes'])){ $idambientes = $_REQUEST['idambientes']; }else{ $idambientes = ''; }
		if(isset($_REQUEST['idsubambientes'])){ $idsubambientes = $_REQUEST['idsubambientes']; }else{ $idsubambientes = ''; }
		if(isset($_REQUEST['tipo'])){ $tipo = $_REQUEST['tipo']; }else{ $tipo = ''; } 
		$query  = " SELECT a.id, a.nombre, a.activo, a.serie
					FROM activos a 
					INNER JOIN ambientes b ON b.codigo = a.codigound 
					WHERE 1 = 1 ";
		if($idambientes != ''){ 
			$query  .= " AND b.id = '".$idambientes."' ";
		}
		if($idsubambientes != ''){ 
			$query  .= " AND b.idsubambiente = '".$idsubambientes."' ";
		}
		if($tipo != ''){ 
			$query  .= " AND b.estado = 'Activo' ";
		}
		$query  .= " ORDER BY a.nombre ASC ";
		//debug($queryL);
		$result = $mysqli->query($query);
		//debug($query);
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']." - SERIE: ".$row['serie']."</option>";
		}
		echo $combo;  
	}
	
	function laboratorios()
	{
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		if(isset($_REQUEST['idfusion'])){ $idfusion = $_REQUEST['idfusion']; }else{ $idfusion = ''; }																					   
		
		$query  = " SELECT DISTINCT id, titulo FROM laboratorio 
					WHERE  fechacreacion > DATE_SUB(CURDATE(),INTERVAL 1 YEAR) 
					 ";		
		if($idfusion!=""){
			$query .= "AND id != '".$idfusion."'";
		}
		$query .= "ORDER BY id DESC";		
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['id'].' - '.$row['titulo']."</option>";
		}		
		echo $combo;
	}
	
	function tipos(){
		global $mysqli;
		$combo 		= ''; 
		
		$query  = " SELECT a.id, a.nombre FROM activostipos a WHERE 1 = 1 ";	 
		$query  .= " ORDER BY a.nombre ASC ";
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){ 
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}	
		echo $combo;  
	}
	
	function subtipos(){
		global $mysqli;
		
		$combo 		= ''; 
		if(isset($_REQUEST['idtipo'])){ $idtipo = $_REQUEST['idtipo']; }else{ $idtipo = ''; } 
		$nivel 		 = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);  
		$idclientes  = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : 0);
		$idproyectos = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : 0);
		
		$query  = " SELECT a.id, a.nombre FROM activossubtipos a WHERE 1 = 1 ";	 
		if($idtipo != ""){
			$query .= "AND a.idtipo = '".$idtipo."'";
		}
		if($nivel == 5 || $nivel == 7){
			if($idclientes != ''){
				$arr = strpos($idclientes, ',');
				if ($arr !== false) {
					$query  .= " AND a.idcliente IN (".$idclientes.") ";
				}else{
					$query  .= " AND find_in_set(".$idclientes.",a.idcliente) ";
				}  
			}
			if($idproyectos != ''){
				$arr = strpos($idproyectos, ',');
				if ($arr !== false) {
					$query  .= " AND a.idproyecto IN (".$idproyectos.") ";
				}else{
					$query  .= " AND find_in_set(".$idproyectos.",a.idproyecto) ";
				}  
			}
		}
		$query  .= " ORDER BY a.nombre ASC ";
		//echo $query;
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){ 
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}	
		echo $combo;  
	}
	
	function proveedores(){
		global $mysqli;
		$combo = ''; 
		if(isset($_REQUEST['idproyectos'])){ $idproyectos = $_REQUEST['idproyectos']; }else{ $idproyectos = ''; } 
		 
		$query  = " SELECT a.id, a.nombre 
					FROM proveedores a  
					WHERE 1 = 1
					ORDER BY a.nombre ASC ";
	
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>"; 
		}
		 
		echo $combo;		
	}
	
	function areas(){
		global $mysqli;
		$combo 		 = ''; 
		$idambientes = (!empty($_REQUEST['idubicacion']) ? $_REQUEST['idubicacion'] : 0);
		$nivel		 = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		$idclientes  = (!empty($_SESSION['idclientes']) ? $_SESSION['idclientes'] : 0);
		$idproyectos = (!empty($_SESSION['idproyectos']) ? $_SESSION['idproyectos'] : 0);
		
		$query  = "	SELECT a.id, a.nombre FROM subambientes a  
					WHERE 1 
					GROUP BY a.id ORDER BY a.nombre ASC ";
		
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){ 
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}	
		echo $combo;  
	} 
	
	function usuarioscompromisos(){
		    
		global $mysqli;
		
		$combo 	= '';
		
		$query  = " SELECT  a.nombre,a.usuario FROM usuarios a  WHERE 1 = 1 ";
		
		$query  .=" ORDER BY a.nombre ASC ";
		//debug("USUARIOS:".$query);
		$result = $mysqli->query($query);
		
		$combo .= "<option value=''> Sin Asignar </option>";
		//debug($query);
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['usuario']."'>".$row['nombre']."</option>";
		}	
		echo $combo;
	}
	
	function estadosfiltrosmasivos(){
			global $mysqli;
		$combo = ''; 
		if(isset($_REQUEST['idproyectos'])){ $idproyectos = $_REQUEST['idproyectos']; }else{ $idproyectos = ''; }
		if(isset($_REQUEST['tipo'])){ $tipo = $_REQUEST['tipo']; }else{ $tipo = ''; }
		 
		$query  = " SELECT a.id, a.nombre,a.tipo 
					FROM estados a  
					WHERE 1 = 1 
					ORDER BY a.nombre ASC ";
		
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>"; 
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>"; 
		}
		$combo .= "<option value='12,13,14,15,28,33,42,43,44,48,49,50,51,52'>No resueltos</option>";
		 
		echo $combo;
	}

	function autos(){
		    
		global $mysqli;
		$combo = '';
		if(isset($_REQUEST['onlydata'])){ $odata = $_REQUEST['onlydata']; }else{ $odata = ''; }
		$idempresas = (!empty($_REQUEST['idempresas']) ? $_REQUEST['idempresas'] : 1);
		$idclientes = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 9);
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : 34);
		
		$query  = " SELECT DISTINCT(serie), id, nombre 
					FROM activos 
					WHERE serie != '' AND nombre='auto' AND idresponsables=601 AND idempresas='$idempresas' AND idclientes='$idclientes' AND idproyectos='$idproyectos' ";
					
		$query .= " ORDER BY serie ASC ";
		//		echo $query;
		//debugL($query);
	//	echo $query;
		$result = $mysqli->query($query);
		
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['serie']." - ".$row['nombre']."</option>";
		}		
		echo $combo;
	}
	
		function estadosflotas()
	{
		global $mysqli;
		$tipo 	= (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : 'Flota');
		$nivel 	= (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		$combo = ''; 
		$idproyectos = 91;
		//$query  = "SELECT id, nombre FROM estados WHERE id IN (12,13,53,54) "; //Nuevo,Asignado,Devuelto,Cancelado
		$query  = " SELECT a.id, a.nombre,b.tipo 
					FROM estados a 
					INNER JOIN estadospuente b ON b.idestados = a.id  					
					WHERE 1 = 1 AND FIND_IN_SET('".$tipo."',b.tipo) AND b.idproyectos='34' ";
		
		$query .= "ORDER BY nombre ASC "; 
		//echo $query;
		$result = $mysqli->query($query);
		$combo .= "<option value='0'> Sin Asignar </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}
	
	function categoriasautoc(){
		global $mysqli;
		$combo	= '';
		
		$query  = " SELECT id, nombre FROM categorias WHERE id != 0 ";
		$query  .= " ORDER BY nombre ASC ";		
		$result = $mysqli->query($query);
	
		
		$arrCLI = array();
		while($row = $result->fetch_assoc()){
			$arrCLI[] = array( "value" => $row['nombre'], "id" => $row['id'], "nombre" => $row['nombre'] );
		}
		echo json_encode($arrCLI);
	}	
	
	function ambientesautoc(){
		global $mysqli;
		$combo	= '';
		
		$query  = " SELECT id, nombre FROM ambientes WHERE id != 0 ";
		$query  .= " ORDER BY nombre ASC ";		
		$result = $mysqli->query($query);
	
		
		$arrCLI = array();
		while($row = $result->fetch_assoc()){
			$arrCLI[] = array( "value" => $row['nombre'], "id" => $row['id'], "nombre" => $row['nombre'] );
		}
		echo json_encode($arrCLI);
	}	
	
	function estadosautoc(){
		global $mysqli;
		$combo	= '';
		
		$query  = " SELECT id, nombre, descripcion, tipo FROM estados WHERE id != 0 ";
		$query  .= " ORDER BY nombre ASC ";		
		$result = $mysqli->query($query);
	
		
		$arrCLI = array();
		while($row = $result->fetch_assoc()){
			$arrCLI[] = array( "value" => $row['nombre'], "id" => $row['id'], "nombre" => $row['nombre']);
		}
		echo json_encode($arrCLI);
	}
	
	function departamentosautoc(){
		global $mysqli;
		$combo	= '';
		
		$query  = " SELECT id, nombre, tipo FROM departamentos WHERE id != 0 ";
		$query  .= " ORDER BY nombre ASC ";		
		$result = $mysqli->query($query);
	
		
		$arrCLI = array();
		while($row = $result->fetch_assoc()){
			$arrCLI[] = array( "value" => $row['nombre'], "id" => $row['id'], "nombre" => $row['nombre'], "tipo" => $row['tipo'] );
		}
		echo json_encode($arrCLI);
	}
	
	function subcategoriasautoc(){
		global $mysqli;
		$combo	= '';
		 
		$idcategoria = (!empty($_REQUEST['idcategoria']) ? $_REQUEST['idcategoria'] : 0);
		
		$query  = " SELECT id, nombre FROM subcategorias  WHERE id != 0 ";
		$query  .= "ORDER BY nombre ASC ";		
		$result = $mysqli->query($query);
	
		
		$arrCLI = array();
		while($row = $result->fetch_assoc()){
			$arrCLI[] = array( "value" => $row['nombre'], "id" => $row['id'], "nombre" => $row['nombre'] );
		}
		echo json_encode($arrCLI);
	}
	
	function clientesautoc(){
		global $mysqli;
		$combo	= '';
		
		$query  = " SELECT id, nombre, apellidos, direccion, telefono, movil, contacto FROM clientes WHERE id != 0 ";
		$query  .= " ORDER BY nombre ASC ";		
		$result = $mysqli->query($query);
	
		
		$arrCLI = array();
		while($row = $result->fetch_assoc()){
			$arrCLI[] = array( "value" => $row['nombre'], "id" => $row['id'], "nombre" => $row['nombre'], "apellidos" => $row['apellidos'], "direccion" => $row['direccion'], "telefono" => $row['telefono'], "contacto" => $row['contacto'] , "movil" => $row['movil']);
		}
		echo json_encode($arrCLI);
	}
	
	function proyectosautoc(){
		global $mysqli;
		$combo	= '';
		$idclientes = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		
		$query  = " SELECT id, codigo, nombre, descripcion, correlativo FROM proyectos WHERE id != 0 ";
		if($idclientes != '') $query .= " AND idclientes = ".$idclientes."";
		$query  .= " ORDER BY nombre ASC ";		
		$result = $mysqli->query($query);
	
		
		$arrCLI = array();
		while($row = $result->fetch_assoc()){
			$arrCLI[] = array( "value" => $row['nombre'], "id" => $row['id'], "nombre" => $row['nombre'], "codigo" => $row['codigo'], "descripcion" => $row['descripcion'], "correlativo" => $row['correlativo']);
		}
		echo json_encode($arrCLI);
	} 
	
	function prioridadesautoc(){
		global $mysqli;
		$combo	= '';
		
		$query  = " SELECT id, prioridad AS nombre, descripcion FROM sla WHERE id != 0 ";
		$query  .= " ORDER BY prioridad ASC ";		
		$result = $mysqli->query($query);
	
		
		$arrCLI = array();
		while($row = $result->fetch_assoc()){
			$arrCLI[] = array( "value" => $row['nombre'], "id" => $row['id'], "nombre" => $row['nombre'], "descripcion" => $row['descripcion'] );
		}
		echo json_encode($arrCLI);
	}
	
	function proyectosetiquetas()
	{
		global $mysqli;
		$combo = '';
		$nivel 	     = (!empty($_SESSION['nivel']) ? $_SESSION['nivel'] : 0);
		$idclientes	 = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : 0);
		$idproyectos = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : 0);
 
		$query = " SELECT 
						a.idetiquetas, b.nombre, c.valor AS color
					FROM 
						proyectosetiquetas a 
					INNER JOIN etiquetas b ON b.id = a.idetiquetas
					INNER JOIN colores c ON c.id = b.idcolores 
					WHERE 1 ";
		
		
		if(is_array($idclientes)) $idclientes = implode(',',$idclientes);  //Filtros masivos
		$query  .= " AND a.idclientes in ($idclientes) ";
		if(is_array($idproyectos)) $idproyectos = implode(',',$idproyectos); //Filtros masivos
		$query  .= " AND a.idproyectos in ($idproyectos) "; 
		
		// Varios proyectos
		$pos = strpos($idproyectos,',');  
		if ($pos !== false) $query .= " GROUP BY a.idetiquetas "; 
		$result = $mysqli->query($query);
		
		$arrEtq = array();
		while($row = $result->fetch_assoc()){
			$arrEtq[] = array(  "idetiquetas" => $row['idetiquetas'], "nombre" => $row['nombre'], "color" => $row['color'] );
		}
		echo json_encode($arrEtq);
	}
?>