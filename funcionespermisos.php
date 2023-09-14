<?php
	
	function camposelect($campo, $valor, $condicion){
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
	
	function permisos($modulo, $seccion = '', $idusuario = ''){
		global $mysqli;
		
		$filtro = '';
		
		if($idusuario == ''){
			//SESIONES
			$idusuario 	= $_SESSION['user_id'];
			$usuario 	= $_SESSION['usuario'];
			
		}
		//VALORES USUARIO
		
		$queryj = "SELECT nivel,usuario, correo, idempresas, idclientes, idproyectos, idambientes, iddepartamentos FROM usuarios WHERE id=".$idusuario;
		
		$resultj = $mysqli->query($queryj); 
		$row = $resultj->fetch_assoc();
		$usuario  	 = $row['usuario'];
		$nivel  	 = $row['nivel'];
		$correo  	 = $row['correo'];
		$idempresas  = $row['idempresas'];
		$idclientes  = $row['idclientes'];
		$idproyectos = $row['idproyectos'];
		$idambientes = implode(',', array_filter(explode(',', $row['idambientes'])));
		$iddepartamentos = implode(',', array_filter(explode(',', $row['iddepartamentos'])));
		$posdepartamentos = strpos($iddepartamentos,",");
		$posdepartamentos !== false ? $validarfiltrosdepartamentos = 0 : $validarfiltrosdepartamentos = 1;
		$ejecutarfiltrosdepartamentos = 0;
		
		if($validarfiltrosdepartamentos == 1){
			$existecliente = 0;
			$existeproyecto = 0;
			$existedepartamento = 0;
			$poscliente = strpos($idclientes,"38");
			$posproyecto = strpos($idproyectos,"68"); 
			$posdepartamento = strpos($iddepartamentos,"20");
			$poscliente !== false ? $existecliente = 1 : $existecliente = 0;
			$posproyecto !== false ? $existeproyecto = 1 : $existeproyecto = 0;
			$posdepartamento !== false ? $existedepartamento = 1 : $existedepartamento = 0; 
			if($existecliente == 1 && $existeproyecto == 1 && $existedepartamento == 1){
				$ejecutarfiltrosdepartamentos = 1;
				$filtrodepartamentocategoria = 361;	
			} 
		}
		
		if($modulo == 'dashboard'){ /********** ********** ********** DASHBOARD ********** ********** **********/
			if($nivel == 2){ 
				//SOPORTE
				$filtro  .= " AND a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.") ";
			}elseif($nivel == 3){ 
				//INGENIEROS / TECNICOS
				$correousuario = '"'.$correo.'"';
				$filtro  .= " AND ( ";
				$filtro  .= " b.idempresas IN (".$idempresas.") AND b.idclientes IN (".$idclientes.") AND b.idproyectos IN (".$idproyectos.") ";
				$filtro  .= " AND (
							j.id = '".$idusuario."' OR 
							l.id = '".$idusuario."' OR
							IF((b.notificar IS NULL OR b.notificar = ''), NULL, JSON_CONTAINS(b.notificar, '[".$correousuario."]' )) OR
							FIND_IN_SET(b.iddepartamentos,( SELECT GROUP_CONCAT( DISTINCT ee.id SEPARATOR  ',' )			
															FROM usuarios a
															LEFT JOIN departamentos ee ON FIND_IN_SET(ee.id, a.iddepartamentos) AND ee.tipo = 'grupo'
															WHERE a.id = '".$idusuario."'))
						)";
				$filtro .= " ) ";
			}elseif($nivel == 4 || $nivel == 7){ 
				//4: CLIENTE, 7: CLIENTE GENERAL
				/* $filtro  .= " AND ( ";
				$filtro  .= " a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.")  "; */
				$filtro  .= " AND  ((";
				$filtro  .= " b.idempresas IN (".$idempresas.") AND b.idclientes IN (".$idclientes.") AND b.idproyectos IN (".$idproyectos.")  ";
				/* if($idambientes != ''){
					$filtro  .= " AND a.idambientes IN (".$idambientes.") ";
				}
				if($iddepartamentos != ''){
					$filtro  .= " AND FIND_IN_SET(a.iddepartamentos,'".$iddepartamentos."') ";
				} */
				/* $filtro  .= " OR j.id = '".$idusuario."' "; $filtro .= " ) "; */
				
				$filtro  .= " AND (l.id = '".$idusuario."' OR j.id = '".$idusuario."' ) ) OR (b.idempresas IN (".$idempresas.") AND b.idclientes IN (0) AND b.idproyectos IN (0) AND creadopor = '".$correo."') ) ";
				$filtro .= "  ";
			}elseif($nivel == 5 || $nivel == 6 || $nivel == 8){
				//5: DIRECTORES / GERENTES, 6: QA TESTER , 8: COORDINADOR
				$filtro  .= " AND b.idempresas IN (".$idempresas.") AND b.idclientes IN (".$idclientes.") AND b.idproyectos IN (".$idproyectos.") ";
			}
			//Configuración filtro-global departamentos/categorías 
			if($ejecutarfiltrosdepartamentos == 1){
				if($usuario == 'mbonilla') $filtro .= " AND b.idcategorias IN (".$filtrodepartamentocategoria.")";
			} 
		}elseif($modulo == 'calendario'){ /********** ********** ********** CALENDARIO ********** ********** **********/
			if($nivel == 2){ 
				//SOPORTE
				$filtro  .= " AND a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.") ";
			}elseif($nivel == 3){ 
				//INGENIEROS / TECNICOS
				$correousuario = '"'.$correo.'"';
				$filtro  .= " AND ( ";
				$filtro  .= " a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.") ";
				$filtro  .= " AND (
							j.id = '".$idusuario."' OR 
							l.id = '".$idusuario."' OR
							IF((a.notificar IS NULL OR a.notificar = ''), NULL, JSON_CONTAINS(a.notificar, '[".$correousuario."]' )) OR
							FIND_IN_SET(a.iddepartamentos,( SELECT GROUP_CONCAT( DISTINCT ee.id SEPARATOR  ',' )			
															FROM usuarios a
															LEFT JOIN departamentos ee ON FIND_IN_SET(ee.id, a.iddepartamentos) AND ee.tipo = 'grupo'
															WHERE a.id = '".$idusuario."'))
						)";
				$filtro .= " ) ";
			}elseif($nivel == 4 || $nivel == 7){ 
				//CLIENTE
				$filtro  .= " AND ( ";
				$filtro  .= " a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.")  ";
				if($idambientes != ''){
					$filtro  .= " AND a.idambientes IN (".$idambientes.") ";
				}
				if($iddepartamentos != ''){
					$filtro  .= " AND FIND_IN_SET(a.iddepartamentos,'".$iddepartamentos."') ";
				}
				$filtro  .= " OR j.id = '".$idusuario."' ";
				$filtro .= " ) ";
			}elseif($nivel == 5 || $nivel == 6 || $nivel == 8){
				//5: DIRECTORES / GERENTES, 6: QA TESTER, 8: COORDINADOR
				$filtro  .= " AND a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.") ";
			}
			
			//Configuración filtro-global departamentos/categorías 
			if($ejecutarfiltrosdepartamentos == 1){
				if($usuario == 'mbonilla')  $filtro .= " AND a.idcategorias IN (".$filtrodepartamentocategoria.")";
			}
		}elseif($modulo == 'correctivos'){ /********** ********** ********** CORRECTIVOS ********** ********** **********/
			if($nivel == 2){ 
				//SOPORTE
				//$filtro  .= " AND a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.") ";
			}elseif($nivel == 3){ 
				//INGENIEROS / TECNICOS
				$correousuario = '"'.$correo.'"';
				$filtro  .= " AND (( ";
				$filtro  .= " a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.") ";
				$filtro  .= " AND (
							j.id = '".$idusuario."' OR 
							l.id = '".$idusuario."' OR
							IF((a.notificar IS NULL OR a.notificar = ''), NULL, a.notificar LIKE '%".$correo."%' ) OR
							FIND_IN_SET(a.iddepartamentos,( SELECT GROUP_CONCAT( DISTINCT ee.id SEPARATOR  ',' )			
															FROM usuarios a
															LEFT JOIN departamentos ee ON FIND_IN_SET(ee.id, a.iddepartamentos) AND ee.tipo = 'grupo'
															WHERE a.id = '".$idusuario."'))
						)";
				$filtro .= " ) OR (a.idempresas IN (".$idempresas.") AND a.idclientes IN (0) AND a.idproyectos IN (0) AND creadopor = '".$correo."') )";
				//IF((a.notificar IS NULL OR a.notificar = ''), NULL, JSON_CONTAINS(a.notificar, '[".$correousuario."]' )) OR
			}elseif($nivel == 4 || $nivel == 7){ 
				//4: CLIENTE, 7: CLIENTE GENERAL
				/* $filtro  .= " AND ( ";
				$filtro  .= " a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.")  "; */
				$filtro  .= " AND  ((";
				$filtro  .= " a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.")  ";
				/* if($idambientes != ''){
					$filtro  .= " AND a.idambientes IN (".$idambientes.") ";
				}
				if($iddepartamentos != ''){
					$filtro  .= " AND FIND_IN_SET(a.iddepartamentos,'".$iddepartamentos."') ";
				} */
				/* $filtro  .= " OR j.id = '".$idusuario."' "; $filtro .= " ) "; */
				$filtro  .= " AND (l.id = '".$idusuario."' OR j.id = '".$idusuario."' ) ) OR (a.idempresas IN (".$idempresas.") AND a.idclientes IN (0) AND a.idproyectos IN (0) AND creadopor = '".$correo."') ) ";
				$filtro .= "  ";
			}elseif($nivel == 5 || $nivel == 6 || $nivel == 8){
				//5: DIRECTORES / GERENTES, 6: QA TESTER / 8: COORDINADOR
				$filtro  .= " AND ( (a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.") ) OR (a.idempresas IN (".$idempresas.") AND a.idclientes IN (0) AND a.idproyectos IN (0) AND creadopor = '".$correo."')) ";
			}
			
			//Configuración filtro-global departamentos/categorías 
			if($ejecutarfiltrosdepartamentos == 1){
				if($usuario == 'mbonilla') $filtro .= " AND a.idcategorias IN (".$filtrodepartamentocategoria.")";
			} 
		}elseif($modulo == 'preventivos'){ /********** ********** ********** PREVENTIVOS ********** ********** **********/
		    if($nivel == 2){ 
				//SOPORTE
				//$filtro  .= " AND a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.") ";
			}elseif($nivel == 3){ 
				//INGENIEROS / TECNICOS
				$correousuario = '"'.$correo.'"';
				$filtro  .= " AND ( ";
				$filtro  .= " a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.") ";
				$filtro  .= " AND (
							j.id = '".$idusuario."' OR 
							l.id = '".$idusuario."' OR
							IF((a.notificar IS NULL OR a.notificar = ''), NULL, JSON_CONTAINS(a.notificar, '[".$correousuario."]' )) OR
							FIND_IN_SET(a.iddepartamentos,( SELECT GROUP_CONCAT( DISTINCT ee.id SEPARATOR  ',' )			
															FROM usuarios a
															LEFT JOIN departamentos ee ON FIND_IN_SET(ee.id, a.iddepartamentos) AND ee.tipo = 'grupo'
															WHERE a.id = '".$idusuario."'))
						)";
				$filtro .= " ) ";
			}elseif($nivel == 4 || $nivel == 7){ 
				//4: CLIENTE, 7: CLIENTE GENERAL
				$filtro  .= " AND ( ";
				$filtro  .= " a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.")  ";
				if($idambientes != ''){
					$filtro  .= " AND a.idambientes IN (".$idambientes.") ";
				}
				if($iddepartamentos != ''){
					$filtro  .= " AND FIND_IN_SET(a.iddepartamentos,'".$iddepartamentos."') ";
				}
				$filtro  .= " OR j.id = '".$idusuario."' ";
				$filtro .= " ) ";
			}elseif($nivel == 5 || $nivel == 6 || $nivel == 8){
				//5: DIRECTORES / GERENTES, 6: QA TESTER / COORDINADOR
				$filtro  .= " AND a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.") ";
			}
			
			//Configuración filtro-global departamentos/categorías 
			if($ejecutarfiltrosdepartamentos == 1){
				//if($usuario == 'mbonilla') $filtro .= " AND a.idcategorias IN (".$filtrodepartamentocategoria.")";
			}
		}elseif($modulo == 'activos'){ /********** ********** ********** ACTIVOS ********** ********** **********/
		    if($nivel == 2){ 
				//SOPORTE
				$filtro  .= " AND a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.") ";
			}elseif($nivel == 3){ 
				//INGENIEROS / TECNICOS
				$correousuario = '"'.$correo.'"';
				$filtro  .= " AND ( ";
				$filtro  .= " a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.") ";
				/* $filtro  .= " AND (
							j.id = '".$idusuario."' OR 
							l.id = '".$idusuario."' OR
							IF((a.notificar IS NULL OR a.notificar = ''), NULL, JSON_CONTAINS(a.notificar, '[".$correousuario."]' )) OR
							FIND_IN_SET(a.iddepartamentos,( SELECT GROUP_CONCAT( DISTINCT ee.id SEPARATOR  ',' )			
															FROM usuarios a
															LEFT JOIN departamentos ee ON FIND_IN_SET(ee.id, a.iddepartamentos) AND ee.tipo = 'grupo'
															WHERE a.id = '".$idusuario."'))
						)"; */
				$filtro .= " ) ";
			}elseif($nivel == 4 || $nivel == 7){ 
				//4: CLIENTE, 7: CLIENTE GENERAL
				$filtro  .= " AND ( ";
				$filtro  .= " a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.")  ";
				if($idambientes != ''){
					$filtro  .= " AND a.idambientes IN (".$idambientes.") ";
				}
				/* if($iddepartamentos != ''){
					$filtro  .= " AND FIND_IN_SET(a.iddepartamentos,'".$iddepartamentos."') ";
				}
				$filtro  .= " OR j.id = '".$idusuario."' "; */
				$filtro .= " ) ";
			}elseif($nivel == 5 || $nivel == 6 || $nivel == 8){
				//5: DIRECTORES / GERENTES, 6: QA TESTER, 8: COORDINADOR
				$filtro  .= " AND a.idempresas IN (".$idempresas.") AND a.idclientes IN (".$idclientes.") AND a.idproyectos IN (".$idproyectos.") ";
			}
		}elseif($modulo == 'nuevoReporte'){ /********** ********** ********** POSTVENTAS ********** ********** **********/
        
		}elseif($modulo == 'laboratorios'){  /********** ********** ********** LABORATORIOS ********** ********** **********/			
		    
		}elseif($modulo == 'postventas'){ /********** ********** ********** POSTVENTAS ********** ********** **********/
            
		}elseif($modulo == 'comentario'){ /********** ********** ********** POSTVENTAS ********** ********** **********/
          if($nivel == 4)
          {
		    $filtro .= " AND a.visibilidad = 'Público' ";
	      }  
		}elseif($modulo == 'combos'){ /********** ********** ********** COMBOS ********** ********** **********/
			if($seccion == 'empresas'){}
			//CLIENTES
			if($seccion == 'clientes'){				
				if($nivel == 1 || $nivel == 2){
					//1: Administrador, 2: Soporte
					$filtro .= " WHERE a.id != 0 ";
				}elseif($nivel == 3 || $nivel == 4 || $nivel == 5 || $nivel == 6 || $nivel == 7 || $nivel == 8){ 
					//3: Ingenieros / Tecnicos, 4: Clientes, 5: Directores / Gerentes, 6: QA Tester, 8: Coordinador
					$filtro .= " LEFT JOIN usuarios b ON FIND_IN_SET(a.id, b.idclientes)
								 WHERE b.usuario = '".$usuario."' ";
					//EMPRESAS
					$widempresas = camposelect('a.idempresas', $idempresas, 'AND');
					$filtro  .= $widempresas;
				}
			}
			//PROYECTOS
			if($seccion == 'proyectos'){				
				if($nivel == 1 || $nivel == 2){
					//1: Administrador, 2: Soporte
					$filtro .= " WHERE 1 = 1 ";
				}elseif($nivel == 3 || $nivel == 4 || $nivel == 5 || $nivel == 6 || $nivel == 7 || $nivel == 8){ 
					//3: Ingenieros / Tecnicos, 4: Clientes, 5: Directores / Gerentes, 6: QA Tester, 8: Coordinador
					$filtro .= " LEFT JOIN usuarios c ON FIND_IN_SET(a.id, c.idproyectos)
						WHERE c.usuario = '".$usuario."' ";
					//EMPRESAS
					$widempresas = camposelect('c.idempresas',$idempresas, 'AND');
					$filtro  .= $widempresas;
					//CLIENTES
					$widclientes = camposelect('c.idclientes',$idclientes, 'AND');
					$filtro  .= $widclientes;
				}
			}
			//CATEGORIAS
			if($seccion == 'categorias'){
				//PROYECTOS
				$widproyectos = camposelect('a.idproyecto',$idproyectos, 'AND');
				$filtro  .= $widproyectos;
			}
			//AMBIENTES
			if($seccion == 'ambientes'){
				if($nivel == 4){
					//CLIENTES
					$widclientes = camposelect('a.idclientes',$idclientes, 'AND');
					$filtro  .= $widclientes;
					//PROYECTOS
					$widproyectos = camposelect('a.idproyectos',$idproyectos, 'AND');
					$filtro  .= $widproyectos;
				}
			}
			//ESTADOS
			if($seccion == 'estados'){
				if($nivel == 4){
					//EMPRESAS
					$widempresas = camposelect('a.idempresas',$idempresas, 'AND');
					$filtro  .= $widempresas;
					//CLIENTES
					$widclientes = camposelect('a.idclientes',$idclientes, 'AND');
					$filtro  .= $widclientes;
					//PROYECTOS
					$widproyectos = camposelect('a.idproyectos',$idproyectos, 'AND');
					$filtro  .= $widproyectos;
				}
			}
			//PRIORIDADES
			if($seccion == 'prioridades'){				
				if ($nivel < 4) {
					$filtro .= " AND a.id <> 7 AND activo = 'Activo' ";
				} else {
					$filtro .= " AND a.id < 6  AND a.id <> 7 AND activo = 'Activo' ";
				}
			}
			//USUARIOS DEPARTAMENTOS
			if($seccion == 'usuariosDep'){
				
				$tipo != "";
				
				if($tipo != "")
				{
			        $filtro  .=" AND iddepartamentos IN ($iddepartamentos) ";
		        }else{
		            if($iddepartamentos !='' && $iddepartamentos !='undefined'){
				    //DEPARTAMENTOS
    				$widdepartamentos = camposelect('iddepartamentos', $iddepartamentos, 'AND');
    				$filtro  .= $widdepartamentos;
		            }
		      }	
			    if($idproyectos != ""){
				    //PROYECTOS
    		        $widproyectos = camposelect('idproyectos', $idproyectos, 'AND');
    				$filtro  .= $widproyectos;
			    }			
    		
			}
			//MODALIDADES
			if($seccion == 'modalidades'){
				
    		    if($nivel==4){
    			    if($idclientes != ''){
    				    //$arr = strpos($idclientes, ',');
    				    if ($arr !== false) {
    					     $filtro .= " AND idclientes IN (".$idclientes.") ";
    				    }else{
    					    //$query  .= " AND find_in_set($idclientess,idclientes) ";
    					    $widclientes = camposelect('idclientes', $idclientes, 'AND');
				            $filtro  .= $widclientes;
    				}  
    			}
    			
    			if($idproyectos != ''){
    				$arr = strpos($idproyectos, ',');
    				if ($arr !== false) {
    					 $filtro  .= " AND idproyectos IN (".$idproyectos.") ";
    				}else{
    				    
    					//$query  .= " AND find_in_set($idproyectoss,idproyectos) ";
    					$widproyectos = camposelect('idproyectos',  $idproyectos, 'AND');
				            $filtro  .= $widproyectos;
    				}  
    			}
		    }
		 }
		    
		}
		return $filtro;		
	}

?>